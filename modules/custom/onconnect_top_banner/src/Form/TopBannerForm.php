<?php

namespace Drupal\onconnect_top_banner\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * TopBannerForm class.
 */
class TopBannerForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'top_banner_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $state = \Drupal::state();
    $form['top_banner_image'] = [
      '#type' => 'managed_file',
      '#title' => t('Home Banner Image.'),
      '#upload_validators' => [
        'file_validate_extensions' => ['gif png jpg jpeg'],
        'file_validate_size' => [],
      ],
      '#upload_location' => 'public://top_banner_image',
      '#default_value' => $state->get('top_banner_image') ? [$state->get('top_banner_image')] : '',
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
    $mid = $form_state->getValue('top_banner_image')[0];

    if ($mid) {
      \Drupal::state()->set('top_banner_image', $mid);
    }
    \Drupal::messenger()
      ->addMessage($this->t('Configuration saved successfully.'));
  }

}
