<?php

namespace Drupal\onconnect_metrics\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Configure onconnect_metrics settings for this site.
 */
class MetricsSettingsForm extends ConfigFormBase {

  /**
   * Config settings.
   *
   * @var string
   */
  const SETTINGS = 'onconnect_metrics.settings';

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'onconnect_metrics_admin_settings';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      static::SETTINGS,
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config(static::SETTINGS);

    $form['onconnect_metrics_therapeutic_area_description'] = [
      '#type' => 'select',
      '#title' => $this->t('Therapeutic Area Description'),
      '#options' => [t('--- SELECT ---'), t('Antibacterials'), t('Anti Infectives'), t('Cardiovascular'),
        t('CV/MET'), t('Diversified Brands'), t('Early Development'), t('Inflammation/Immunology'),
        t('Non Product Biosimilars'), t('Non Product Oncology'), t('Oncology'),
        t('Pain/CNS'), t('Rare Diseases'), t('Vaccines'), t('WH/MH'),
      ],
      '#default_value' => $config->get('onconnect_metrics_therapeutic_area_description'),
    ];

    $form['onconnect_metrics_abstract_submit'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Abstract Submit'),
      '#default_value' => $config->get('onconnect_metrics_abstract_submit'),
    ];

    $form['onconnect_metrics_abstract_published'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Abstract Published'),
      '#default_value' => $config->get('onconnect_metrics_abstract_published'),
    ];

    $form['onconnect_metrics_manuscript_submit'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Manuscript Submit'),
      '#default_value' => $config->get('onconnect_metrics_manuscript_submit'),
    ];

    $form['onconnect_metrics_manuscript_published'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Manuscript Published'),
      '#default_value' => $config->get('onconnect_metrics_manuscript_published'),
    ];

    $form['onconnect_metrics_posters'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Posters'),
      '#default_value' => $config->get('onconnect_metrics_posters'),
    ];

    $form['onconnect_metrics_presentation'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Presentation'),
      '#default_value' => $config->get('onconnect_metrics_presentation'),
    ];

    $form['onconnect_metrics_man_pls_submit'] = [
      '#type' => 'textfield',
      '#title' => $this->t('ManPLSSubmit'),
      '#default_value' => $config->get('onconnect_metrics_man_pls_submit'),
    ];

    $form['onconnect_metrics_man_pls_published'] = [
      '#type' => 'textfield',
      '#title' => $this->t('ManPLSPublished'),
      '#default_value' => $config->get('onconnect_metrics_man_pls_published'),
    ];

    $form['onconnect_metrics_abs_pls_submitted'] = [
      '#type' => 'textfield',
      '#title' => $this->t('AbsPLSSubmitted'),
      '#default_value' => $config->get('onconnect_metrics_abs_pls_submitted'),
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // Retrieve the configuration.
    $this->configFactory->getEditable(static::SETTINGS)
      // Set the submitted configuration setting.
      ->set('onconnect_metrics_thing', $form_state->getValue('onconnect_metrics_thing'))
      // You can set multiple configurations at once by making
      // multiple calls to set().
      ->set('other_things', $form_state->getValue('other_things'))
      ->save();

    parent::submitForm($form, $form_state);
  }

}
