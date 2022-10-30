<?php

namespace Drupal\onconnect_top_banner\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Disease Area Class.
 */
class DiseaseAreasForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'disease_areas_banner_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $state = \Drupal::state();
    $form['disease_area_landing_banner_image'] = [
      '#type' => 'managed_file',
      '#title' => t('Disease Area Landing Banner Image.'),
      '#upload_validators' => [
        'file_validate_extensions' => ['gif png jpg jpeg'],
        'file_validate_size' => [],
      ],
      '#upload_location' => 'public://default_banner_image',
      '#default_value' => $state->get('disease_area_landing_banner_image') ? [$state->get('disease_area_landing_banner_image')] : '',
    ];
    $form['asset_product_landing_banner_image'] = [
      '#type' => 'managed_file',
      '#title' => t('Asset/Product Landing Banner Image.'),
      '#upload_validators' => [
        'file_validate_extensions' => ['gif png jpg jpeg'],
        'file_validate_size' => [],
      ],
      '#upload_location' => 'public://default_banner_image',
      '#default_value' => $state->get('asset_product_landing_banner_image') ? [$state->get('asset_product_landing_banner_image')] : '',
    ];
    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Save configuration'),
    ];
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $disease_area_landing = $form_state->getValue('disease_area_landing_banner_image')[0];
    $state = \Drupal::state();
    if ($disease_area_landing) {
      $state->set('disease_area_landing_banner_image', $disease_area_landing);
    }
    $asset_product_landing = $form_state->getValue('asset_product_landing_banner_image')[0];
    if ($asset_product_landing) {
      $state->set('asset_product_landing_banner_image', $asset_product_landing);
    }
    \Drupal::messenger()
      ->addMessage($this->t('Configuration saved successfully.'));
  }

}
