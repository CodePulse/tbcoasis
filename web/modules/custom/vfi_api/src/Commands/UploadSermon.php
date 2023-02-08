<?php

namespace Drupal\vfi_api\Commands;

use Drupal\Core\Entity\EntityStorageException;
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
    $getAudioData = $this->getAudioData();
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
      'All Night' => 'friday-intercession',
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
    return $json_result->data->transcripts[0];
  }

  protected function getAudioData() {
    $query = '{"query":"query transcripts($user_id: String, $limit: Int, $skip: Int){\n  transcripts(user_id: $user_id, limit: $limit, skip: $skip){\n    id\n    title\n    host_email\n    organizer_email\n    fireflies_users\n    participants\n    date\n    transcript_url\n    duration\n    custom_topics{\n      sentence_index\n      sentence\n      name\n      phrases\n    }\n  }\n}"}';
    $transcript = $this->getResponse($query);
    $url = '';
    $id = '';
    $titleProcess = '';

    if (!empty($transcript)) {
      $title = trim($transcript->title);
      $titleProcess = trim($transcript->title);
      $title = str_replace(' ', '', $title);
//      $url = "https://rtmp-server-ff.s3.amazonaws.com/$transcript->id/$title-$transcript->id.mp3"; // old format
      $url = "https://rtmp-server-ff.s3.amazonaws.com/$transcript->id/audio.mp3";
      $id = $transcript->id;
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
      'title' => 'New Recording',
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
