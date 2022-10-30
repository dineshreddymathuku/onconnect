<?php

namespace Drupal\onconnect_top_banner\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Congres Form Class.
 */
class CongressesForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'congresses_banner_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $state = \Drupal::state();
    $form['congresses_landing_banner_image'] = [
      '#type' => 'managed_file',
      '#title' => t('Congresses Landing Banner Image.'),
      '#upload_validators' => [
        'file_validate_extensions' => ['gif png jpg jpeg'],
        'file_validate_size' => [],
      ],
      '#upload_location' => 'public://banner_image',
      '#default_value' => $state->get('congresses_landing_banner_image') ? [$state->get('congresses_landing_banner_image')] : '',
    ];
    $form['congresses_sublanding_banner_image'] = [
      '#type' => 'managed_file',
      '#title' => t('Congresses Sub-landing Banner Image.'),
      '#upload_validators' => [
        'file_validate_extensions' => ['gif png jpg jpeg'],
        'file_validate_size' => [],
      ],
      '#upload_location' => 'public://banner_image',
      '#default_value' => $state->get('congresses_sublanding_banner_image') ? [$state->get('congresses_sublanding_banner_image')] : '',
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
    $values = $form_state->getValues();
    $congresses_landing = $form_state->getValue('congresses_landing_banner_image')[0];
    $congresses_sublanding = $form_state->getValue('congresses_sublanding_banner_image')[0];
    $state = \Drupal::state();
    if ($congresses_landing) {
      $state->set('congresses_landing_banner_image', $congresses_landing);
    }
    if ($congresses_sublanding) {
      $state->set('congresses_sublanding_banner_image', $congresses_sublanding);
    }
    \Drupal::messenger()
      ->addMessage($this->t('Configuration saved successfully.'));
  }

}
