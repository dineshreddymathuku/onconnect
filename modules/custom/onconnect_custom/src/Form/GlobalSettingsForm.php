<?php

namespace Drupal\onconnect_custom\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Configure Global settings for this site.
 */
class GlobalSettingsForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'onconnect_custom_admin_settings';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $state = \Drupal::state();
    $form['onconnect_custom_manuscript_id'] = [
      '#type' => 'textfield',
      '#required' => TRUE,
      '#title' => $this->t('Manuscript term ID'),
      '#default_value' => $state->get('onconnect_custom_manuscript_id'),
      '#description' => t('Used to display the only Manuscript (publication type) in Latest publications block in home page'),
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
    $onconnect_custom_manuscript_id = $values['onconnect_custom_manuscript_id'];
    $form_values = [
      'onconnect_custom_manuscript_id' => $onconnect_custom_manuscript_id,
    ];
    \Drupal::state()->setMultiple($form_values);
    \Drupal::messenger()
      ->addMessage($this->t('Configuration saved successfully.'));
  }

}
