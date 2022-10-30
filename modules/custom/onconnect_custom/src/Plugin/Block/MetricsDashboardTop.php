<?php

namespace Drupal\onconnect_custom\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a block for Metrics Dashboard.
 *
 * @Block(
 *   id = "onconnect_metrics_dashboard_top",
 *   admin_label = @Translation("Metrics Dashboard Top region"),
 *   category = "Oncology"
 * )
 */
class MetricsDashboardTop extends BlockBase {

  /**
   * Function.
   */
  public function getCacheMaxAge() {
    return 0;
  }

  /**
   * Function.
   */
  public function build() {
    $state = \Drupal::state();
    $data = [
      'abstracts_submitted' => $state->get('onconnect_metrics_abstracts_submitted'),
      'twitter_followers' => $state->get('onconnect_metrics_twitter_followers'),
      'qmd_article_launched' => $state->get('onconnect_metrics_qmd_article_launched'),
      'total_congress_exhibits' => $state->get('onconnect_metrics_total_congress_exhibits'),
      'hover_dashboard' => $state->get('onconnect_metrics_hover_dashboard'),
      'publication_metrics_abstracts_submitted' => $state->get('publication_metrics_abstracts_submitted'),
      'social_media_metrics_no_followers' => $state->get('social_media_metrics_no_followers'),
      'closed_platform_metrics_qmd_total_articles' => $state->get('closed_platform_metrics_qmd_total_articles'),
       'congress_presence_total_congress_exhibits' => $state->get('congress_presence_total_congress_exhibits'),
    ];
    $build = [
      '#theme' => 'metrics_dashboard_top',
      '#data' => $data,
      '#cache' => ['max-age' => 0],
    ];
    return $build;
  }

}
