<?php

namespace Drupal\onconnect_top_banner\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Scientific Platform Form Class.
 */
class ScientificPlatformForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'scientific_platform_banner_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $state = \Drupal::state();
    $form['scientific_platform_banner_image'] = [
      '#type' => 'managed_file',
      '#title' => t('Scientific Platform Banner Image.'),
      '#upload_validators' => [
        'file_validate_extensions' => ['gif png jpg jpeg'],
        'file_validate_size' => [],
      ],
      '#upload_location' => 'public://banner_image',
      '#default_value' => $state->get('scientific_platform_banner_image') ? [$state->get('scientific_platform_banner_image')] : '',
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
    $state = \Drupal::state();
    $scientific_platform_banner_image = $form_state->getValue('scientific_platform_banner_image')[0];
    if ($scientific_platform_banner_image) {
      $state->set('scientific_platform_banner_image', $scientific_platform_banner_image);
    }
    \Drupal::messenger()
      ->addMessage($this->t('Configuration saved successfully.'));
  }

}
