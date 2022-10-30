<?php

namespace Drupal\onconnect_quick_links_block\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Url;

/**
 * Provides a block for Static Details Page Quick Links.
 *
 * @Block(
 *   id = "static_details_quick_links",
 *   admin_label = @Translation("Static Details Page Quick Links"),
 *   category = "Oncology"
 * )
 */
class StaticDetails extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function getCacheMaxAge() {
    return 0;
  }

  /**
   * Build function.
   */
  public function build() {
    $data = [];
    $node = \Drupal::routeMatch()->getParameter('node');
    $nid = $node->get('nid')->value;
    if (!empty($node)) {
      // Render data for quick links.
      $links = $node->get('field_quick_links')->getValue();
    
      if (!empty($links)) {
  $data['Quick Links'] = [];
        foreach ($links as $key => $value) {
          $url = Url::fromUri($value['uri']);
          $data['Quick Links'][$key]['uri'] = $url->toString();
          $data['Quick Links'][$key]['title'] = $value['title'];

        }
      }
      // Render data for metrics.
      // $data['Metrics'] = [];.
      if($node->get('field_abstracts_submitted')->value){
        $data['metrics']['abstract']['title'] = $node->get('field_abstracts_submitted')->getfieldDefinition()->label();
        $data['metrics']['abstract']['uri'] = $node->get('field_abstracts_submitted')->value;
      }
      if($node->get('field_manuscripts_published')->value){
       $data['metrics']['manuscript']['title'] = $node->get('field_manuscripts_published')->getfieldDefinition()->label();
       $data['metrics']['manuscript']['uri'] = $node->get('field_manuscripts_published')->value;
     }
     if($node->get('field_abstracts_submitted')->value){
      $data['metrics']['abstract']['title'] = $node->get('field_abstracts_submitted')->getfieldDefinition()->label();
      $data['metrics']['abstract']['uri'] = $node->get('field_abstracts_submitted')->value ;
    }
    if($node->get('field_oral_presentations')->value){
      $data['metrics']['oral_presentation']['title'] = $node->get('field_oral_presentations')->getfieldDefinition()->label();
      $data['metrics']['oral_presentation']['uri'] = $node->get('field_oral_presentations')->value;
    }
    if($node->get('field_poster_presentations')->value){
      $data['metrics']['poster_presentation']['title'] = $node->get('field_poster_presentations')->getfieldDefinition()->label();
      $data['metrics']['poster_presentation']['uri'] = $node->get('field_poster_presentations')->value;
    }
    if($node->get('field_plain_language_summaries')->value){
      $data['metrics']['plain_language_summaries']['title'] = $node->get('field_plain_language_summaries')->getfieldDefinition()->label();
      $data['metrics']['plain_language_summaries']['uri'] = $node->get('field_plain_language_summaries')->value;
    }


  }
  $state = \Drupal::state();
  $hoverDashboard = $state->get('onconnect_metrics_hover_dashboard');
  return [
    '#theme' => 'static_details_quick_links',
    '#data' => $data,
    '#hoverDashboard' => $hoverDashboard,
  ];
}

}
