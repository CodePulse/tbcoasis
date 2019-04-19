<?php

namespace Drupal\tbc_custom_fields\Plugin\Field\FieldType;

use Drupal\Core\Field\FieldItemBase;
use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\Core\StringTranslation\TranslatableMarkup;
use Drupal\Core\TypedData\DataDefinition;

/**
 * Plugin implementation of the 'accordion_field_type' field type.
 *
 * @FieldType(
 *   id = "accordion_field_type",
 *   label = @Translation("Accordion"),
 *   description = @Translation("Accordion"),
 *   default_widget = "accordion_widget_type",
 *   default_formatter = "accordion_formatter_type"
 * )
 */
class AccordionFieldType extends FieldItemBase {

  /**
   * {@inheritdoc}
   */
  public function preSave() {
    if (!empty($this->description)) {
      $this->format = $this->description['format'];
      $this->value = $this->description['value'];
    }
  }

  /**
   * {@inheritdoc}
   */
  public function setValue($values, $notify = TRUE) {
    // Treat the values as property value of the first property, if no array is
    // given.
    if (isset($values) && !is_array($values)) {
      $keys = array_keys($this->definition->getPropertyDefinitions());
      $values = [$keys[0] => $values];
    }
    parent::setValue($values, $notify);
  }

  /**
   * {@inheritdoc}
   */
  public static function propertyDefinitions(FieldStorageDefinitionInterface $field_definition) {
    $properties['title'] = DataDefinition::create('string')
      ->setLabel(new TranslatableMarkup('Title'))
      ->setDescription(new TranslatableMarkup('Title'));

    $properties['value'] = DataDefinition::create('string')
      ->setLabel(t('Description value'));

    $properties['format'] = DataDefinition::create('filter_format')
      ->setLabel(t('Description format'));

    return $properties;
  }

  /**
   * {@inheritdoc}
   */
  public static function schema(FieldStorageDefinitionInterface $field_definition) {
    $schema = [
      'columns' => [
        'title' => [
          'type' => 'varchar',
          'length' => 2048,
          'default' => '',
        ],
        'value' => [
          'type' => 'text',
          'size' => 'big',
        ],
        'format' => [
          'type' => 'varchar_ascii',
          'length' => 255,
        ],
      ],
    ];

    return $schema;
  }

  /**
   * {@inheritdoc}
   */
  public function isEmpty() {
    $title = $this->get('title')->getValue();
    $description = $this->get('value')->getValue();
    return empty($title) && empty($description);
  }

}
