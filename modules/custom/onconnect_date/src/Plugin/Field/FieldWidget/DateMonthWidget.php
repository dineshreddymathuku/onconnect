<?php

namespace Drupal\onconnect_date\Plugin\Field\FieldWidget;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\datetime\Plugin\Field\FieldWidget\DateTimeWidgetBase;

/**
 * Plugin implementation of the 'onconnect_date' widget.
 *
 * @FieldWidget(
 *   id = "onconnect_date",
 *   module = "onconnect_date",
 *   label = @Translation("US Date widget"),
 *   field_types = {
 *     "datetime"
 *   }
 * )
 */
class DateMonthWidget extends DateTimeWidgetBase {

  /**
   * {@inheritdoc}
   */
  public static function defaultSettings() {
    return [
        'date_order' => 'MDY',
      ] + parent::defaultSettings();
  }

  /**
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {
    $element = parent::formElement($items, $delta, $element, $form, $form_state);

    // Wrap all of the select elements with a fieldset.
    $element['#theme_wrappers'][] = 'fieldset';

    $date_order = $this->getSetting('date_order');

    switch ($date_order) {
      default:
      case 'YM':
        $date_part_order = ['year', 'month'];
        break;

      case 'MY':
        $date_part_order = ['month', 'year'];
        break;
case 'MDY':
        $date_part_order = ['month','day', 'year'];
        break;        

    }

    $element['value'] = [
        '#type' => 'datelist',
        '#date_part_order' => $date_part_order,
      ] + $element['value'];

    return $element;
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {
    $element = parent::settingsForm($form, $form_state);

    $element['date_order'] = [
      '#type' => 'select',
      '#title' => t('Date part order'),
      '#default_value' => $this->getSetting('date_order'),
      '#options' => [ 'MDY' => t('Month/Date/Year'), 'MY' => t('Month/Year'), 'YM' => t('Year/Month')],
    ];

    return $element;
  }

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
    $summary = [];

    $summary[] = t('Date part order: @order', ['@order' => $this->getSetting('date_order')]);

    return $summary;
  }

}
