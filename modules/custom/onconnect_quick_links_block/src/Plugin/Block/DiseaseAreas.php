<?php

namespace Drupal\onconnect_quick_links_block\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\taxonomy\Entity\Term;

/**
 * Provides a block for Disease Areas Landing Page Quick Links.
 *
 * @Block(
 *   id = "disease_areas_quick_links",
 *   admin_label = @Translation("Disease Areas Quick Links"),
 *   category = "Oncology"
 * )
 */
class DiseaseAreas extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function getCacheMaxAge() {
    return 0;
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $data = [];
    $term = \Drupal::routeMatch()->getParameter('taxonomy_term');
    $term_id = $term->get('tid')->value;
    $term_name = $term->get('name')->value;
    $parent = $term->parent->target_id;

    $childrens = \Drupal::entityTypeManager()
      ->getStorage('taxonomy_term')
      ->loadChildren($term->id());

    $termData = Term::load($term_id);
    if ($term) {
      // Render data for quick links.
      // $links = $term->get('field_q')->getValue();
      if ($parent && empty($childrens)) {

        // Render data for Related Disease Type.
        $related_disease_type = [];
        // Load parents.
        $ancestors = \Drupal::service('entity_type.manager')
          ->getStorage("taxonomy_term")
          ->loadAllParents($term_id);
        foreach ($ancestors as $ancestor) {
          $related_disease_type[$key]['title'] = $ancestor->label();
          $related_disease_type[$key]['uri'] = $ancestor->toUrl()
            ->toString();
        }
      }
      else {

        $data['congresses'] = [];
        /*if ($term->get('field_congresses')) {
        foreach ($term->get('field_congresses') as $key => $item) {
        if ($item->entity) {
        $entity_id = $item->entity->tid->value;
        // 1 to get only immediate children
        $depth = 1;
        $child_terms = \Drupal::entityTypeManager()
        ->getStorage('taxonomy_term')
        ->loadTree('congresses', $entity_id, $depth, TRUE);
        if ($child_terms) {
        $child_term_url = [];
        foreach ($child_terms as $child_term) {
        $child_term_url[] = $child_term->toUrl()->toString();
        $data['congresses'][$key]['uri'] = current($child_term_url);
        }
        }
        else {
        $data['congresses'][$key]['uri'] = $item->entity->toUrl()
        ->toString();
        }

        $data['congresses'][$key]['title'] = $item->entity->name->value;
        }
        }
        }*/
        // Render data for assets/products.
        $data['Assets/Products'] = [];
        if (!empty($childrens)) {
          foreach ($childrens as $children) {
            $sub_childrens = \Drupal::entityTypeManager()
              ->getStorage('taxonomy_term')
              ->loadChildren($children->id());
            foreach ($sub_childrens as $key => $sub_children) {
              if (!empty($sub_children)) {
                $data['Assets/Products'][$key]['title'] = $sub_children->label();
                $data['Assets/Products'][$key]['uri'] = $sub_children->toUrl()->toString();
                $data['Assets/Products'][$key]['weight'] = $sub_children->getWeight();
              }
            }

          }
        }
        usort($data['Assets/Products'], function ($a, $b) {
        return $a['weight'] <=> $b['weight'];
      });
      }
      
    }

    // $data['Quick Links'] = [];
    /* if (!empty($links)) {

    foreach ($links as $key => $value) {
    $url = Url::fromUri($value['uri']);
    $data['Quick Links'][$key]['uri'] = $url->toString();
    $data['Quick Links'][$key]['title'] = $value['title'];

    }
    }*/
    // Render data for metrics.
    $data['metrics'] = [];

    $data['metrics']['abstract']['title'] = $term->get('field_abstracts_submitted')->getfieldDefinition()->label();
    $data['metrics']['abstract']['uri'] = $term->get('field_abstracts_submitted')->value ? $term->get('field_abstracts_submitted')->value : 'N/A';
    $data['metrics']['manuscript']['title'] = $term->get('field_manuscripts_published')->getfieldDefinition()->label();
    $data['metrics']['manuscript']['uri'] = $term->get('field_manuscripts_submitted')->value ? $term->get('field_manuscripts_submitted')->value : 'N/A';
    $data['metrics']['manuscript_s']['title'] = $term->get('field_manuscripts_submitted')->getfieldDefinition()->label();
    $data['metrics']['manuscript_s']['uri'] = $term->get('field_manuscripts_published')->value ? $term->get('field_manuscripts_published')->value : 'N/A';
    $data['metrics']['oral_presentation']['title'] = $term->get('field_oral_presentations')->getfieldDefinition()->label();
    $data['metrics']['oral_presentation']['uri'] = $term->get('field_oral_presentations')->value ? $term->get('field_poster_presentations')->value : 'N/A';
    $data['metrics']['poster_presentation']['title'] = $term->get('field_poster_presentations')->getfieldDefinition()->label();
    $data['metrics']['poster_presentation']['uri'] = $term->get('field_poster_presentations')->value ? $term->get('field_poster_presentations')->value : 'N/A';
     $data['metrics']['plain_language_summaries']['title'] = $term->get('field_plain_language_summaries')->getfieldDefinition()->label();
      $data['metrics']['plain_language_summaries']['uri'] = $term->get('field_plain_language_summaries')->value ? $term->get('field_plain_language_summaries')->value : 'N/A';
    $state = \Drupal::state();
    $hoverDashboard = $state->get('onconnect_metrics_hover_dashboard');
    if ($parent && empty($childrens)) {
      $data['Related Disease Type'] = $related_disease_type;
    }
    return [
      '#theme' => 'diseases_quick_links',
      '#data' => $data,
      '#hoverDashboard' => $hoverDashboard,
    ];
  }

}
