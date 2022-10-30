<?php

namespace Drupal\onconnect_quick_links_block\Plugin\Block;

use Drupal\taxonomy\Entity\Term;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Url;

/**
 * Provides a block for Congresses Quick Links.
 *
 * @Block(
 *   id = "congresses_quick_links",
 *   admin_label = @Translation("Congresses Quick Links"),
 *   category = "Oncology"
 * )
 */
class Congresses extends BlockBase {

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
    $term = \Drupal::routeMatch()->getParameter('taxonomy_term');
    if (!empty($term)) {
      // Render data for quick links.
      // Load the field definitions.
     /* $definitions = \Drupal::service('entity_field.manager')->getFieldDefinitions('taxonomy_term', 'congresses');
      if (isset($definitions['field_q'])) {
        $default_quick_links = $definitions['field_q']->getDefaultValueLiteral();
      }*/

      $quick_links = $term->get('field_q')->getValue();

      if (!empty($quick_links)) {
      //  $links = array_merge($default_quick_links, $quick_links);
        $links =  $quick_links;
      }
      //else {
       // $links = $default_quick_links;
     // }

      $data['Quick Links'] = [];
      if (!empty($links)) {
        foreach ($links as $key => $value) {
          $url = Url::fromUri($value['uri']);
          $data['Quick Links'][$key]['uri'] = $url->toString();
          $data['Quick Links'][$key]['title'] = $value['title'];
        }
      }
     
      // Render data for metrics.
      $data['metrics'] = [];

      $data['metrics']['abstract']['title'] = $term->get('field_abstracts_submitted')->getfieldDefinition()->label();
      $data['metrics']['abstract']['uri'] = $term->get('field_abstracts_submitted')->value ? $term->get('field_abstracts_submitted')->value : 'N/A';
      $data['metrics']['manuscript']['title'] = $term->get('field_manuscripts_published')->getfieldDefinition()->label();
      $data['metrics']['manuscript']['uri'] = $term->get('field_manuscripts_submitted')->value ? $term->get('field_manuscripts_submitted')->value : 'N/A' ;
      $data['metrics']['manuscript_s']['title'] = $term->get('field_manuscripts_submitted')->getfieldDefinition()->label();
      $data['metrics']['manuscript_s']['uri'] = $term->get('field_manuscripts_published')->value ? $term->get('field_manuscripts_published')->value : 'N/A';
      $data['metrics']['oral_presentation']['title'] = $term->get('field_oral_presentations')->getfieldDefinition()->label();
      $data['metrics']['oral_presentation']['uri'] = $term->get('field_oral_presentations')->value ? $term->get('field_oral_presentations')->value : 'N/A';
      $data['metrics']['poster_presentation']['title'] = $term->get('field_poster_presentations')->getfieldDefinition()->label();
      $data['metrics']['poster_presentation']['uri'] = $term->get('field_poster_presentations')->value ? $term->get('field_poster_presentations')->value : 'N/A';
      $data['metrics']['plain_language_summaries']['title'] = $term->get('field_plain_language_summaries')->getfieldDefinition()->label();
      $data['metrics']['plain_language_summaries']['uri'] = $term->get('field_plain_language_summaries')->value ? $term->get('field_plain_language_summaries')->value : 'N/A';
      // Render data for favorites.
      // $data['Favorites'] = [];
      // Render data for Related Disease Type.
      $related_disease_type = $term->get('field_related_disease_type')
        ->getValue();
      $data['Related Disease Type'] = [];
      if (!empty($related_disease_type)) {
        foreach ($related_disease_type as $key => $value) {
          $termLoad = Term::load($value['target_id']);
          $data['Related Disease Type'][$key]['title'] = $termLoad->get('name')->value;
          $data['Related Disease Type'][$key]['uri'] = $termLoad->toUrl()
            ->toString();
        }
      }
    }
    $state = \Drupal::state();
    $hoverDashboard = $state->get('onconnect_metrics_hover_dashboard');
    return [
      '#theme' => 'congresses_quick_links',
      '#data' => $data,
      '#hoverDashboard' => $hoverDashboard,
    ];
  }

}
