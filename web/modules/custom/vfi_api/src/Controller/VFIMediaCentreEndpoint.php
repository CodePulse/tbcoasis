<?php

namespace Drupal\vfi_api\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\media\Entity\Media;
use Symfony\Component\HttpFoundation\JsonResponse;

class VFIMediaCentreEndpoint extends ControllerBase {

  /**
   * @param $endpoint
   */
  public function view($endpoint) {
    $eligibleNodes = $this->processNodes($endpoint);
    return new JsonResponse($eligibleNodes);
  }

  /**
   * Gather nodes for this endpoint
   *
   * @param $endpoint
   *
   * @return array|\Drupal\Core\Entity\EntityInterface[]
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  protected function gatherNodes($endpoint) {
    $properties = [
      'field_type' => $endpoint,
      'type' => 'message',
    ];
    $nodes = \Drupal::entityTypeManager()
      ->getStorage('node')
      ->loadByProperties($properties);
    if (!empty($nodes)) {
      return $nodes;
    }
    else {
      return [];
    }
  }


  /**
   * Format nodes for api endpoint.
   *
   * @param $endpoint
   *
   * @return array
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  protected function processNodes($endpoint) {
    $apiFormattedNodes = [];
    $listOfNodes = $this->gatherNodes($endpoint);
    if (!empty($listOfNodes)) {
      /** @var \Drupal\node\Entity\Node $listOfNode */
      foreach ($listOfNodes as $key => $listOfNode) {
        $apiFormattedNodes[$key]['id'] = $listOfNode->id();
        $apiFormattedNodes[$key]['title'] = $listOfNode->label();
        $apiFormattedNodes[$key]['date'] = $this->processDate($listOfNode);
        $apiFormattedNodes[$key]['link'] = $this->processMedia($listOfNode);
      }
    }

    // Sort by the custom date field in descending order.
    usort($apiFormattedNodes, function($a, $b) {
      $t1 = strtotime($a['date']);
      $t2 = strtotime($b['date']);
      return $t2 - $t1;

    });
    return $apiFormattedNodes;
  }

  /**
   * @param \Drupal\node\Entity\Node $node
   */
  protected function processDate($node) {
    if ($node->hasField('field_message_date') && !$node->get('field_message_date')
        ->isEmpty()) {
      $date = $node->get('field_message_date')->getString();
      $timestamp = strtotime($date);
      $formatted = \Drupal::service('date.formatter')
        ->format($timestamp, 'custom', 'd F Y');
      return $formatted;
    }
    return '';

  }

  /**
   * Get media audio file url.
   *
   * @param $node
   *
   * @return string
   */
  protected function processMedia($node) {
    if ($node->hasField('field_sermon_file') && !$node->get('field_sermon_file')
        ->isEmpty()) {

      $mediaId = $node->get('field_sermon_file')->getString();
      $mediaEntity = Media::load($mediaId);
      
      if (!empty($mediaEntity)) {
        $fileEntity = $mediaEntity->get('field_media_audio_file')
          ->first()
          ->get('entity')
          ->getTarget()
          ->getValue();

        $url = file_create_url($fileEntity->getFileUri());
        return $url;
      }
    }
    return '';
  }
}
