<?php

namespace Drupal\onconnect_custom\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\node\Entity\Node;

/**
 * Provides a block for Latest publications.
 *
 * @Block(
 *   id = "latest_publications",
 *   admin_label = @Translation("Latest Publications"),
 *   category = "Oncology"
 * )
 */
class LatestPublications extends BlockBase {

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
    $target_id = \Drupal::state()->get('onconnect_custom_manuscript_id');

    $query = \Drupal::database()->select('node', 'n')
      ->condition('n.type', 'publication')
      ->condition('nt.field_publication_type_target_id', $target_id, '=')
      ->condition('nfd.status', '1')
      ->fields('n', ['nid']);
    $query->join('node_field_data', 'nfd', 'n.nid = nfd.nid');

    $query->Join('node__field_publication_type', 'nt', 'nt.entity_id = n.nid');
    $query->range(0, 3);
    // $query->orderBy('n.nid', 'DESC');
    $query->orderBy('nfd.changed', 'DESC');
    $results = $query->execute()->fetchAll();

    $nids = [];
    foreach ($results as $key => $result) {

      $nids[$key] = $result->nid;
    }

    /* $query = \Drupal::entityQuery('node');
    $query->condition('status', 1);
    $query->condition('type', 'publication');
    $query->condition('field_publication_type.entity:node.field_publication_type', $target_id);
    $query->range(0, 3);
    $query->sort('changed', 'DESC');
    $nids = $query->execute();*/

    if ($nids) {
      $nodesData = [];
      foreach ($nids as $nid) {
        $node_load = Node::load($nid);
        $updated = $node_load->get("field_updated")->value;
        if ($updated) {
          $nodeTimeLabel = "Updated";
        }
        else {
          $nodeTimeLabel = "Published";
        }
        $updated_date = $node_load->get("changed")->value;
        $checkUpdatedDate = strtotime("now") - 86400;
        if ($checkUpdatedDate > $updated_date && $updated_date < strtotime('now')) {
          $latest = FALSE;
          $datePublished = $this->t("$nodeTimeLabel @date", ["@date" => date("M d, Y", $updated_date)]);
        }
        else {
          $latest = TRUE;
          $datePublished = $this->t("$nodeTimeLabel @date", [
            "@date" => get_time_difference($updated_date),
          ]);
        }
        // $description_text = $node_load->get("title")->value;
        if ($node_load->get("field_title_text")->value) {
          $description_text = $node_load->get("field_title_text")->value;
        }
        else {
          $description_text = $node_load->get("field_name")->value;
        }
        $str = $description_text;
        if (strlen($str) > 250) {
          $description = explode("\n", wordwrap($str, 50));
          $description_text = $description[0] . '...';
        }
        $alias = \Drupal::service('path_alias.manager')
          ->getAliasByPath('/node/' . $nid);

        /*  if ($node_load->get("field_related_disease_areas")->target_id) {
        $terms = \Drupal::entityTypeManager()
        ->getStorage('taxonomy_term')
        ->loadByProperties([
        'vid' => 'disease_areas',
        'tid' => $node_load->get("field_related_disease_areas")->target_id,
        ]);
        foreach ($terms as $term) {
        $parent_term_id = $term->parent->target_id;
        if ($parent_term_id) {
        $indication = '';
        }
        else {
        $indication = $term->name->value;
        }
        }
        }*/
        if ($node_load->get("field_related_disease_areas")->referencedEntities()) {

          $terms = $node_load->get("field_related_disease_areas")->referencedEntities();
          foreach ($terms as $term) {
            $parent_term_id = $term->parent->target_id;
            if ($term->hasField('field_group_header')) {
              if ($term->field_group_header->value) {
                unset($term);
              }
              else {
                $term_children = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadChildren($term->id());
                if ($term_children) {
                  $indication = $term->label();
                }
              }
            }
            else {
              $term_children = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadChildren($term->id());
              if ($term_children) {
                $indication = $term->label();
              }
            }

          }
        }

        if (strtolower($node_load->get("field_publication_type")->entity->label()) == 'manuscript') {
          $nodesData[$nid] = [
            'publication_type' => $node_load->get("field_publication_type")->entity->label(),
            'journal' => ($node_load->get("field_publication_journal")->value ? $node_load->get("field_publication_journal")->value : 'N/A'),
            'indication' => $indication,
            'latest' => $latest,
            'datePublished' => $datePublished,
            'description' => $description_text,
            'alias' => $alias,
            'nid' => $nid,
            'checkBibliograpy' => check_bibliograpy($nid),
          ];
        }
      }
      if ($nodesData) {
        $build = [
          '#theme' => 'publications',
          '#data' => $nodesData,
          '#attached' => [
            'library' => [
              'onconnect_custom/publications',
              'onconnect_bibliograpy/bibliography',
            ],
          ],
        ];
      }
      else {
        $build = [
          '#markup' => $this->t(" No Latest publications"),
        ];
      }
    }
    else {
      $build = [
        '#markup' => $this->t(" No Latest publications"),
      ];
    }
    return $build;
  }

}
