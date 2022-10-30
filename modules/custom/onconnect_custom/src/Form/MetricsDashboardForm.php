<?php

namespace Drupal\onconnect_custom\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Configure Metrics data for this site.
 */
class MetricsDashboardForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'onconnect_custom_metrics_dashboard';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $state = \Drupal::state();
    $form['onconnect_metrics_hover_dashboard'] = [
      '#type' => 'textarea',
      '#required' => TRUE,
      '#title' => $this->t('Hover Content'),
      '#default_value' => $state->get('onconnect_metrics_hover_dashboard'),
    ];
    $form['onconnect_metrics_abstracts_submitted'] = [
      '#type' => 'textfield',
      '#required' => TRUE,
      '#title' => $this->t('Abstracts Submitted'),
      '#default_value' => $state->get('onconnect_metrics_abstracts_submitted'),
    ];
    $form['onconnect_metrics_twitter_followers'] = [
      '#type' => 'textfield',
      '#required' => TRUE,
      '#title' => $this->t('Twitter Followers'),
      '#default_value' => $state->get('onconnect_metrics_twitter_followers'),
    ];
    $form['onconnect_metrics_qmd_article_launched'] = [
      '#type' => 'textfield',
      '#required' => TRUE,
      '#title' => $this->t('QxMD article launched'),
      '#default_value' => $state->get('onconnect_metrics_qmd_article_launched'),
    ];
    $form['onconnect_metrics_total_congress_exhibits'] = [
      '#type' => 'textfield',
      '#required' => TRUE,
      '#title' => $this->t('Total Congress Exhibits'),
      '#default_value' => $state->get('onconnect_metrics_total_congress_exhibits'),
    ];

    $form['onconnect_metrics_publication_metrics'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Publication Metrics'),
    ];
    $form['onconnect_metrics_publication_metrics']['publication_metrics_abstracts_submitted'] = [
      '#type' => 'textfield',
      '#required' => TRUE,
      '#title' => $this->t('Abstracts Submitted'),
      '#default_value' => $state->get('publication_metrics_abstracts_submitted'),
    ];
    $form['onconnect_metrics_publication_metrics']['publication_metrics_manuscripts_published'] = [
      '#type' => 'textfield',
      '#required' => TRUE,
      '#title' => $this->t('Manuscripts Published'),
      '#default_value' => $state->get('publication_metrics_manuscripts_published'),
    ];
    $form['onconnect_metrics_publication_metrics']['publication_metrics_posted_presentations'] = [
      '#type' => 'textfield',
      '#required' => TRUE,
      '#title' => $this->t('Posted Presentations'),
      '#default_value' => $state->get('publication_metrics_posted_presentations'),
    ];
    $form['onconnect_metrics_publication_metrics']['publication_metrics_manuscripts_submitted'] = [
      '#type' => 'textfield',
      '#required' => TRUE,
      '#title' => $this->t('Manuscripts submitted'),
      '#default_value' => $state->get('publication_metrics_manuscripts_submitted'),
    ];
    $form['onconnect_metrics_publication_metrics']['publication_metrics_oral_presentations'] = [
      '#type' => 'textfield',
      '#required' => TRUE,
      '#title' => $this->t('Oral Presentations'),
      '#default_value' => $state->get('publication_metrics_oral_presentations'),
    ];
    $form['onconnect_metrics_publication_metrics']['publication_metrics_plain_language_summaries'] = [
      '#type' => 'textfield',
      '#required' => TRUE,
      '#title' => $this->t('Plain Language Summaries'),
      '#default_value' => $state->get('publication_metrics_plain_language_summaries'),
    ];

    $form['onconnect_metrics_social_media_metrics'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Social Media Metrics'),
    ];
    $form['onconnect_metrics_social_media_metrics']['social_media_metrics_no_followers'] = [
      '#type' => 'textfield',
      '#required' => TRUE,
      '#title' => $this->t('Number of Followers'),
      '#default_value' => $state->get('social_media_metrics_no_followers'),
    ];
    $form['onconnect_metrics_social_media_metrics']['social_media_metrics_no_tweets'] = [
      '#type' => 'textfield',
      '#required' => TRUE,
      '#title' => $this->t('Number of tweets'),
      '#default_value' => $state->get('social_media_metrics_no_tweets'),
    ];
    $form['onconnect_metrics_social_media_metrics']['social_media_metrics_total_cumulative_reach'] = [
      '#type' => 'textfield',
      '#required' => TRUE,
      '#title' => $this->t('Total cumulative reach'),
      '#default_value' => $state->get('social_media_metrics_total_cumulative_reach'),
    ];

    $form['onconnect_metrics_closed_platform_metrics'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Closed Platform Metrics'),
    ];
    $form['onconnect_metrics_closed_platform_metrics']['closed_platform_metrics_qmd_total_articles'] = [
      '#type' => 'textfield',
      '#required' => TRUE,
      '#title' => $this->t('QxMD Total articles'),
      '#default_value' => $state->get('closed_platform_metrics_qmd_total_articles'),
    ];
    $form['onconnect_metrics_closed_platform_metrics']['closed_platform_metrics_qmd_total_readership_views'] = [
      '#type' => 'textfield',
      '#required' => TRUE,
      '#title' => $this->t('QxMD Total readership views'),
      '#default_value' => $state->get('closed_platform_metrics_qmd_total_readership_views'),
    ];
    $form['onconnect_metrics_closed_platform_metrics']['closed_platform_metrics_qmd_fold_increase'] = [
      '#type' => 'textfield',
      '#required' => TRUE,
      '#title' => $this->t('QxMD Fold-increase'),
      '#default_value' => $state->get('closed_platform_metrics_qmd_fold_increase'),
    ];
    $form['onconnect_metrics_closed_platform_metrics']['closed_platform_metrics_trendmd_total_articles'] = [
      '#type' => 'textfield',
      '#required' => TRUE,
      '#title' => $this->t('TrendMD Total articles'),
      '#default_value' => $state->get('closed_platform_metrics_trendmd_total_articles'),
    ];
    $form['onconnect_metrics_closed_platform_metrics']['closed_platform_metrics_trendmd_total_impressions'] = [
      '#type' => 'textfield',
      '#required' => TRUE,
      '#title' => $this->t('TrendMD Total impressions'),
      '#default_value' => $state->get('closed_platform_metrics_trendmd_total_impressions'),
    ];
    $form['onconnect_metrics_closed_platform_metrics']['closed_platform_metrics_trendmd_total_views'] = [
      '#type' => 'textfield',
      '#required' => TRUE,
      '#title' => $this->t('TrendMD Total views'),
      '#default_value' => $state->get('closed_platform_metrics_trendmd_total_views'),
    ];

    $form['onconnect_metrics_congress_presence'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Congress Presence'),
    ];
    $form['onconnect_metrics_congress_presence']['congress_presence_total_congress_exhibits'] = [
      '#type' => 'textfield',
      '#required' => TRUE,
      '#title' => $this->t('Total Congress Exhibits'),
      '#default_value' => $state->get('congress_presence_total_congress_exhibits'),
    ];
    $form['onconnect_metrics_congress_presence']['congress_presence_total_congress_exhibit_visitors'] = [
      '#type' => 'textfield',
      '#required' => TRUE,
      '#title' => $this->t('Total Congress Exhibit Visitors'),
      '#default_value' => $state->get('congress_presence_total_congress_exhibit_visitors'),
    ];
    $form['onconnect_metrics_congress_presence']['congress_presence_symposia'] = [
      '#type' => 'textfield',
      '#required' => TRUE,
      '#title' => $this->t('Symposia'),
      '#default_value' => $state->get('congress_presence_symposia'),
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
  public function validateForm(array &$form, FormStateInterface $form_state) {
    $values = $form_state->getValues();
    $onconnect_metrics_abstracts_submitted = $values['onconnect_metrics_abstracts_submitted'];
    $onconnect_metrics_twitter_followers = $values['onconnect_metrics_twitter_followers'];
    $onconnect_metrics_qmd_article_launched = $values['onconnect_metrics_qmd_article_launched'];
    $onconnect_metrics_total_congress_exhibits = $values['onconnect_metrics_total_congress_exhibits'];
    if (!is_numeric($onconnect_metrics_abstracts_submitted)) {
      $form_state->setErrorByName("onconnect_metrics_abstracts_submitted", $this->t("Abstracts Submitted should be numeric."));
    }
    if (!is_numeric($onconnect_metrics_twitter_followers)) {
      $form_state->setErrorByName("onconnect_metrics_twitter_followers", $this->t("Twitter followers should be numeric."));
    }
    if (!is_numeric($onconnect_metrics_qmd_article_launched)) {
      $form_state->setErrorByName("onconnect_metrics_qmd_article_launched", $this->t("QxMD article launched should be numeric."));
    }
    if (!is_numeric($onconnect_metrics_total_congress_exhibits)) {
      $form_state->setErrorByName("onconnect_metrics_total_congress_exhibits", $this->t("Total Congress Exhibits should be numeric."));
    }
    if (is_numeric($onconnect_metrics_abstracts_submitted) && $onconnect_metrics_abstracts_submitted < 0) {
      $form_state->setErrorByName("onconnect_metrics_abstracts_submitted", $this->t("Abstracts Submitted should be greater than 0."));
    }
    if (is_numeric($onconnect_metrics_twitter_followers) && $onconnect_metrics_twitter_followers < 0) {
      $form_state->setErrorByName("onconnect_metrics_twitter_followers", $this->t("Twitter followers should be greater than 0."));
    }
    if (is_numeric($onconnect_metrics_qmd_article_launched) && $onconnect_metrics_qmd_article_launched < 0) {
      $form_state->setErrorByName("onconnect_metrics_qmd_article_launched", $this->t("QxMD article launched should be greater than 0."));
    }
    if (is_numeric($onconnect_metrics_total_congress_exhibits) && $onconnect_metrics_total_congress_exhibits < 0) {
      $form_state->setErrorByName("onconnect_metrics_total_congress_exhibits", $this->t("Total Congress Exhibits should be greater than 0."));
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $values = $form_state->getValues();
    $onconnect_metrics_abstracts_submitted = $values['onconnect_metrics_abstracts_submitted'];
    $onconnect_metrics_twitter_followers = $values['onconnect_metrics_twitter_followers'];
    $onconnect_metrics_qmd_article_launched = $values['onconnect_metrics_qmd_article_launched'];
    $onconnect_metrics_total_congress_exhibits = $values['onconnect_metrics_total_congress_exhibits'];
    $onconnect_metrics_hover_dashboard = $values['onconnect_metrics_hover_dashboard'];
    $form_values = [
      'onconnect_metrics_abstracts_submitted' => $onconnect_metrics_abstracts_submitted,
      'onconnect_metrics_twitter_followers' => $onconnect_metrics_twitter_followers,
      'onconnect_metrics_qmd_article_launched' => $onconnect_metrics_qmd_article_launched,
      'onconnect_metrics_total_congress_exhibits' => $onconnect_metrics_total_congress_exhibits,
      'onconnect_metrics_hover_dashboard' => $onconnect_metrics_hover_dashboard,
      'publication_metrics_abstracts_submitted' => $values['publication_metrics_abstracts_submitted'],
      'publication_metrics_manuscripts_published' => $values['publication_metrics_manuscripts_published'],
      'publication_metrics_posted_presentations' => $values['publication_metrics_posted_presentations'],
      'publication_metrics_manuscripts_submitted' => $values['publication_metrics_manuscripts_submitted'],
      'publication_metrics_oral_presentations' => $values['publication_metrics_oral_presentations'],
      'publication_metrics_plain_language_summaries' => $values['publication_metrics_plain_language_summaries'],
      'social_media_metrics_no_followers' => $values['social_media_metrics_no_followers'],
      'social_media_metrics_no_tweets' => $values['social_media_metrics_no_tweets'],
      'social_media_metrics_total_cumulative_reach' => $values['social_media_metrics_total_cumulative_reach'],
      'closed_platform_metrics_qmd_total_articles' => $values['closed_platform_metrics_qmd_total_articles'],
      'closed_platform_metrics_qmd_total_readership_views' => $values['closed_platform_metrics_qmd_total_readership_views'],
      'closed_platform_metrics_qmd_fold_increase' => $values['closed_platform_metrics_qmd_fold_increase'],
      'closed_platform_metrics_trendmd_total_articles' => $values['closed_platform_metrics_trendmd_total_articles'],
      'closed_platform_metrics_trendmd_total_impressions' => $values['closed_platform_metrics_trendmd_total_impressions'],
      'closed_platform_metrics_trendmd_total_views' => $values['closed_platform_metrics_trendmd_total_views'],
      'congress_presence_total_congress_exhibits' => $values['congress_presence_total_congress_exhibits'],
      'congress_presence_total_congress_exhibit_visitors' => $values['congress_presence_total_congress_exhibit_visitors'],
      'congress_presence_symposia' => $values['congress_presence_symposia'],
    ];
    \Drupal::state()->setMultiple($form_values);
    \Drupal::messenger()
      ->addMessage($this->t('Configuration saved successfully.'));
  }

}
