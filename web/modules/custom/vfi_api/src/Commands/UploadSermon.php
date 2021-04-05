<?php

namespace Drupal\vfi_api\Commands;

use Drupal\Core\Entity\EntityStorageException;
use Drupal\file\Entity\File;
use Drupal\media\Entity\Media;
use Drupal\node\Entity\Node;
use Drush\Commands\DrushCommands;

/**
 * A drush command file.
 *
 * @package Drupal\vfi_api\Commands
 */
class UploadSermon extends DrushCommands {

  const FIREFLIES_ENDPOINT = 'https://api.fireflies.ai/graphql';
  const FIREFLIES_API_KEY = '908311cbbb3d45910da87615ad77aea9';

  /**
   * Drush command that automatically upload recent sermon.
   *
   * @command sermon:upload
   * @aliases upload-sermon us
   * @usage sermon:upload
   */
  public function upload() {
    $recent_transcript = $this->getRecentTranscript();
    $getAudioData = $this->getAudioData($recent_transcript);
    $sermonType = $this->processType($getAudioData['title']);
    if (!$this->sermonExists($getAudioData['id'], $sermonType)) {
      $fileEntity = $this->createFileEntity($getAudioData['url']);
      $mediaEntity = $this->createMediaEntity($fileEntity);
      $this->createMessageNode($getAudioData['id'], $mediaEntity, $sermonType);
    }
    else {
      $this->output()->writeln('This sermon exists already');
    }

  }

  protected function processType($title) {
    $types = [
      'Morning Glory' => 'morning-glory',
      'Bible Studies' => 'bible-studies',
      'Friday Intercession' => 'friday-intercession',
      'Sunday Intercession' => 'sunday-intercession',
      'Sunday Sermon' => 'sunday-sermon'
    ];

    return $types[$title];
  }

  protected function getResponse($query) {
    $headers = array();
    $headers[] = 'Content-Type: application/json';
    $headers[] = 'Authorization: Bearer ' . self::FIREFLIES_API_KEY;

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, self::FIREFLIES_ENDPOINT);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $query);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $result = curl_exec($ch);
    $json_result = json_decode($result);
    curl_close($ch);
    return $json_result;
  }

  protected function getRecentTranscript() {
    $query = '{"query":"query {user{email,recent_transcript}}"}';

    $recent_transcript = $this->getResponse($query);

    if (!empty($recent_transcript) && isset($recent_transcript->data->user->recent_transcript)) {
      $this->output()->writeln('Got the latest transcript!');
      return $recent_transcript->data->user->recent_transcript;
    }
    return '';
  }

  protected function getAudioData($recent_transcript) {
    $query = '{"query":"{\n  transcript(id: \"' . $recent_transcript . '\"){\n    id\n    title\n    fireflies_users\n    participants\n    date\n    transcript_url\n    duration\n  }\n}"}';
    $transcript = $this->getResponse($query);
    $url = '';
    $id = '';
    $titleProcess = '';

    if (!empty($transcript)) {
      $title = trim($transcript->data->transcript->title);
      $titleProcess = trim($transcript->data->transcript->title);
      $title = str_replace(' ', '', $title);
      $url = "https://rtmp-server-ff.s3.amazonaws.com/$recent_transcript/$title-$recent_transcript.mp3";
      $id = $transcript->data->transcript->id;
    }
    $this->output()->writeln('URL created: ' . $url);
    return ['url' => $url, 'id' => $id, 'title' => $titleProcess];
  }

  protected function createFileEntity($audio_url) {
    $name = basename($audio_url);
    $destination = "public://$name";
    $audio = file_get_contents($audio_url);
    $file = file_save_data($audio, $destination);
    $this->output()->writeln('Downloaded the sermon and uploaded as a file');
    return $file;
  }

  protected function createMediaEntity($fileEntity) {
    // Create media entity with saved file.
    $audio_media = Media::create([
      'bundle' => 'audio',
      'uid' => \Drupal::currentUser()->id(),
      'status' => 1,
      'field_media_audio_file' => [
        'target_id' => $fileEntity->id(),
      ],
    ]);
    try {
      $audio_media->save();
      $this->output()->writeln('Created the Audio Media Entity.');
      return $audio_media;
    } catch (EntityStorageException $e) {

    }
  }

  protected function createMessageNode($id, $audioMedia, $type) {
    $now = strtotime('now');
    $formattedDate = \Drupal::service('date.formatter')->format($now, 'custom', 'Y-m-d');

    $messageNode = Node::create([
      'type' => 'message',
      'title' => 'Title Coming Soon',
      'uid' => 1,
      'status' => 1,
      'field_sermon_file' => [
        'target_id' => $audioMedia->id(),
      ],
      'field_type' => $type,
      'field_message_date' => $formattedDate,
      'field_author' => 'Somebody...',
      'field_fireflies_id' => $id
    ]);
    $messageNode->save();
    $this->output()->writeln('Message Created!');
  }

  protected function sermonExists($id, $sermonType) {
    $properties = [
      'type' => 'message',
      'field_fireflies_id' => $id,
      'field_type' => $sermonType,
    ];

    $messages = \Drupal::entityTypeManager()->getStorage('node')->loadByProperties($properties);
    if (!empty($messages)) {
      return TRUE;
    }
    return FALSE;

  }

}
