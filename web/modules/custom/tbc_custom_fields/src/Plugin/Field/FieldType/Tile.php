<?php

namespace Drupal\tbc_custom_fields\Plugin\Field\FieldType;

use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\Core\TypedData\DataDefinition;
use Drupal\image\Plugin\Field\FieldType\ImageItem;
use Drupal\file\Entity\File;

/**
 * Plugin implementation of the 'tile_text' field type.
 *
 * @FieldType (
 *   id = "tile_text",
 *   label = @Translation("TileText"),
 *   description = @Translation("Stores values for tile field."),
 *   default_widget = "tile_text",
 *   default_formatter = "tile_text"
 * )
 */
class Tile extends ImageItem {

  /**
   * {@inheritdoc}
   */
  public function preSave() {
    parent::preSave();

    if (isset($this->target_id) && is_numeric($this->target_id)) {
      $file = File::load($this->target_id);
      if (!$file->isPermanent()) {
        $file->setPermanent();
        $file->save();
      }
    }
  }

  /**
   * {@inheritdoc}
   */
  public static function schema(FieldStorageDefinitionInterface $field_definition) {
    return [
      'columns' => [
        'target_id' => [
          'description' => 'The ID of the file entity.',
          'type' => 'int',
          'unsigned' => TRUE,
        ],
        'alt' => [
          'description' => "Alternative image text, for the image's 'alt' attribute.",
          'type' => 'varchar',
          'length' => 512,
        ],
        'title' => [
          'description' => "Image title text, for the image's 'title' attribute.",
          'type' => 'varchar',
          'length' => 1024,
        ],
        'width' => [
          'description' => 'The width of the image in pixels.',
          'type' => 'int',
          'unsigned' => TRUE,
        ],
        'height' => [
          'description' => 'The height of the image in pixels.',
          'type' => 'int',
          'unsigned' => TRUE,
        ],
        'slide_title' => [
          'type' => 'varchar',
          'length' => 255,
        ],
        'slide_text' => [
          'type' => 'varchar',
          'length' => 255,
        ],
        'cta_text' => [
          'type' => 'varchar',
          'length' => 255,
        ],
        'cta_link' => [
          'type' => 'varchar',
          'length' => 255,
        ],
      ],
      'indexes' => [
        'target_id' => ['target_id'],
      ],
      'foreign keys' => [
        'target_id' => [
          'table' => 'file_managed',
          'columns' => ['target_id' => 'fid'],
        ],
      ],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public static function propertyDefinitions(FieldStorageDefinitionInterface $field_definition) {
    $properties = parent::propertyDefinitions($field_definition);
    // Add our properties.
    $properties['slide_title'] = DataDefinition::create('string')
      ->setLabel(t('Slide title'))
      ->setDescription(t('Title for current slide'));

    $properties['slide_text'] = DataDefinition::create('string')
      ->setLabel(t('Slide text'))
      ->setDescription(t('Text for current slide'));

    $properties['cta_text'] = DataDefinition::create('string')
      ->setLabel(t('Button text'))
      ->setDescription(t('Button text for current slide'));

    $properties['cta_link'] = DataDefinition::create('string')
      ->setLabel(t('Button link'))
      ->setDescription(t('Button link for current slide'));

    return $properties;
  }

}
