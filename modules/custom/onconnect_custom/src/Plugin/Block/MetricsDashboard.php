<?php

namespace Drupal\onconnect_custom\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a block for Metrics Dashboard.
 *
 * @Block(
 *   id = "onconnect_metrics_dashboard",
 *   admin_label = @Translation("Metrics Dashboard"),
 *   category = "Oncology"
 * )
 */
class MetricsDashboard extends BlockBase {

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
      'publication_metrics_manuscripts_published' => $state->get('publication_metrics_manuscripts_published'),
      'publication_metrics_posted_presentations' => $state->get('publication_metrics_posted_presentations'),
      'publication_metrics_manuscripts_submitted' => $state->get('publication_metrics_manuscripts_submitted'),
      'publication_metrics_oral_presentations' => $state->get('publication_metrics_oral_presentations'),
      'publication_metrics_plain_language_summaries' => $state->get('publication_metrics_plain_language_summaries'),
      'social_media_metrics_no_followers' => $state->get('social_media_metrics_no_followers'),
      'social_media_metrics_no_tweets' => $state->get('social_media_metrics_no_tweets'),
      'social_media_metrics_total_cumulative_reach' => $state->get('social_media_metrics_total_cumulative_reach'),
      'closed_platform_metrics_qmd_total_articles' => $state->get('closed_platform_metrics_qmd_total_articles'),
      'closed_platform_metrics_qmd_total_readership_views' => $state->get('closed_platform_metrics_qmd_total_readership_views'),
      'closed_platform_metrics_qmd_fold_increase' => $state->get('closed_platform_metrics_qmd_fold_increase'),
      'closed_platform_metrics_trendmd_total_articles' => $state->get('closed_platform_metrics_trendmd_total_articles'),
      'closed_platform_metrics_trendmd_total_impressions' => $state->get('closed_platform_metrics_trendmd_total_impressions'),
      'closed_platform_metrics_trendmd_total_views' => $state->get('closed_platform_metrics_trendmd_total_views'),
      'congress_presence_total_congress_exhibits' => $state->get('congress_presence_total_congress_exhibits'),
      'congress_presence_total_congress_exhibit_visitors' => $state->get('congress_presence_total_congress_exhibit_visitors'),
      'congress_presence_symposia' => $state->get('congress_presence_symposia'),
    ];
    $build = [
      '#theme' => 'metrics_dashboard',
      '#data' => $data,
      '#cache' => ['max-age' => 0],
    ];
    return $build;
  }

}
