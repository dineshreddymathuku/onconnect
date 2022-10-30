<?php

namespace Drupal\onconnect_custom\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\node\Entity\Node;
use Drupal\taxonomy\Entity\Term;

/**
 * Provides a block for Term publications.
 *
 * @Block(
 *   id = "term_publications",
 *   admin_label = @Translation("Term Publications"),
 *   category = "Oncology"
 * )
 */
class TermPublications extends BlockBase {

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
    if ($term = \Drupal::routeMatch()->getParameter('taxonomy_term')) {
      $params = \Drupal::request()->query->all();
      $page = $params['page'] ? ($params['page'] + 1) : ($params['page'] + 1);
      // $pagerCount = $params['pagerCount'] ? (24 + $params['pagerCount'] * 2) : 24;
      $pagerCount = $params['pagerCount'] ? (9 + $params['pagerCount'] * 6) : 9;
      $filters = FALSE;
      $offset = 0;
      $pager = $params['pagerCount'] ? ($params['pagerCount'] + 1) : 1;
      $limit = $pagerCount;
      // $limit = 2;
      // $show = 2;
      $bibliography = FALSE;
      if ($params) {
        if (count($params) == 1 && isset($params['pagerCount'])) {
          $filters = FALSE;
        }
        else {
          $filters = TRUE;
        }
      }
      $sort = [
        'recent' => [
          'value' => $this->t("Recent"),
          'checked' => in_array("recent", $params, TRUE) ? "checked" : '',
        ],
        'a-z' => [
          'value' => $this->t("Title: A - Z"),
          'checked' => in_array("a-z", $params, TRUE) ? "checked" : '',
        ],
        'z-a' => [
          'value' => $this->t("Title: Z - A"),
          'checked' => in_array("z-a", $params, TRUE) ? "checked" : '',
        ],
      ];
      $paramLinks = [];
      $tids[$term->id()] = $term->id();
      $vocabularyID = $term->bundle();
      if ($vocabularyID == "disease_areas") {
        $terms_2 = \Drupal::entityTypeManager()
          ->getStorage('taxonomy_term')
          ->loadTree($vocabularyID, $term->id(), 1);

        if ($terms_2) {
          foreach ($terms_2 as $term_2) {

            $tids[$term_2->tid] = $term_2->tid;
          }
        }
      }
      foreach ($params as $paramKey => $paramValue) {
        if (strpos($paramKey, 'type-') !== FALSE) {
          $typesData[$paramValue] = $paramValue;
        }
        if (strpos($paramKey, 'congress-') !== FALSE) {
          $congressData[$paramValue] = $paramValue;
        }
        /* if (strpos($paramKey, 'study-') !== FALSE) {
        $studyData[$paramValue] = $paramValue;
        }*/
        if (strpos($paramKey, 'indication-') !== FALSE) {
          $indicationData[$paramValue] = $paramValue;
        }
        if (strpos($paramKey, 'product-') !== FALSE) {
          $productData[$paramValue] = $paramValue;
        }
      }
      $nid = \Drupal::entityQuery('node')
        ->accessCheck(FALSE);
      $nid->condition('type', 'publication');

      if ($vocabularyID == "disease_areas") {
        if ($productData && $indicationData) {
          $tids = $productData;

        }
        else {
          if ($productData) {
            $tids = $productData;
          }
          if ($indicationData) {
            $tids = $indicationData;
          }
        }
        $nid->condition("field_related_disease_areas", $tids, "IN");
      }
      if ($vocabularyID == "congresses") {
        $nid->condition("field_related_to_congresses", $tids, "IN");
        if ($productData && $indicationData) {

          $nid->condition("field_related_disease_areas", array_merge($productData, $indicationData), "IN");
        }
        else {
          if ($productData) {
            $nid->condition("field_related_disease_areas", $productData, "IN");
          }
          if ($indicationData) {
            $nid->condition("field_related_disease_areas", $indicationData, "IN");
          }
        }
      }
      $nid->condition('status', 1);
      if ($params['sort'] == "a-z") {
        // $nid->sort("title", 'ASC');
        $nid->sort("field_title_text", 'ASC');
      }
      elseif ($params['sort'] == "z-a") {
        // $nid->sort("title", 'DESC');
        $nid->sort("field_title_text", 'DESC');
      }
      $nid->sort("field_publication_year", "DESC");

      if ($typesData) {
        $nid->condition("field_publication_type", $typesData, "IN");
      }
      if ($congressData) {
        $nid->condition("field_related_to_congresses", $congressData, "IN");
      }
      /*if ($productData) {
      $nid->condition("field_related_disease_areas", $productData, "IN");
      }
      /*if ($studyData) {
      $nid->condition("field_study_name", $studyData, "IN");
      }*/

      $count_query = clone $nid;
      $nid->range($offset, $limit);
      // dsm($nid->__toString());
      $nids = $nid->execute();

      $totalcount = $count_query->count()->accessCheck(FALSE)->execute();
      $nodesData = [];
      $aliasManager = \Drupal::service('path_alias.manager');
      $relatedDiseases = [];
      $relatedCongresses = [];
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
        // $description_text = $node_load->get("body")->value;
        if ($node_load->get("field_title_text")->value) {
          $description_text = $node_load->get("field_title_text")->value;
        }
        elseif ($node_load->get("field_name")->value) {
          $description_text = $node_load->get("field_name")->value;
        }
        $str = $description_text;
        if (strlen($str) > 250) {
          $description = explode("\n", wordwrap($str, 50));
          $description_text = $description[0] . '...';
        }
        $alias = $aliasManager->getAliasByPath('/node/' . $nid);
        $publication_type = $node_load->get("field_publication_type")->entity->label();
        if ($publication_type) {

          $types[$publication_type] = [
            'value' => $publication_type,
            'id' => $node_load->get("field_publication_type")->target_id,
            // 'checked' => in_array($node_load->get("field_publication_type")->target_id, $params, TRUE) ? "checked" : '',
            'checked' => array_key_exists(str_replace(" ", "_", 'type-' . $publication_type), $params) ? "checked" : '',
          ];
        }
        /* $studyName = $node_load->get("field_study_name")->value;
        if ($studyName) {
        $studies[$studyName] = [
        'value' => $studyName,
        'checked' => in_array($studyName, $params, TRUE) ? "checked" : '',
        ];
        }*/
        $congressValues = $node_load->get("field_related_to_congresses")
          ->getValue();
        if ($congressValues) {
          foreach ($congressValues as $congressValue) {
            $congressLoad = Term::load($congressValue['target_id']);
            if ($congressLoad->parent->target_id) {
              // $congresses[$congressLoad->label()] = [
              $congresses[$congressLoad->field_congress_abbr->value] = [
                'id' => $congressValue['target_id'],
                // 'value' => $congressLoad->label(),
                'value' => $congressLoad->field_congress_abbr->value . ' ' . $congressLoad->label(),
                'checked' => in_array($congressValue['target_id'], $params, TRUE) ? "checked" : '',
              ];
            }
          }
        }
        $diseaseAreaValues = $node_load->get("field_related_disease_areas")
          ->getValue();
        if ($diseaseAreaValues) {
          // Check the term has parent or not.
          foreach ($diseaseAreaValues as $key => $diseaseAreaValue) {
            // dsm($diseaseAreaValue['target_id']);.
            $target_id[$key] = $diseaseAreaValue['target_id'];
            $diseaseAreaLoad = Term::load($diseaseAreaValue['target_id']);
            $child_terms = \Drupal::entityTypeManager()
              ->getStorage('taxonomy_term')
              ->loadTree('disease_areas', $diseaseAreaValue['target_id'], 1);
            if ($diseaseAreaLoad->parent->target_id && !$child_terms) {

              $diseaseAreas[$diseaseAreaLoad->label()] = [
                'id' => $diseaseAreaValue['target_id'],
                'value' => $diseaseAreaLoad->label(),
                'checked' => in_array($diseaseAreaValue['target_id'], $params, TRUE) ? "checked" : '',
              ];

            }
            else {
              if ($diseaseAreaLoad && $diseaseAreaLoad->parent->target_id) {
                if (in_array($diseaseAreaLoad->parent->target_id, $target_id)) {
                  $indications[$diseaseAreaLoad->label()] = [
                    'id' => $diseaseAreaValue['target_id'],
                    'value' => $diseaseAreaLoad->label(),
                    'checked' => in_array($diseaseAreaValue['target_id'], $params, TRUE) ? "checked" : '',
                  ];
                }

              }

            }
          }
        }
        /*$terms = \Drupal::entityTypeManager()
        ->getStorage('taxonomy_term')
        ->loadByProperties([
        'vid' => 'disease_areas',
        'tid' => $node_load->get("field_related_disease_areas")->target_id,
        ]);
        if ($parent_term_id) {
        $indication = '';
        }
        else {
        $indication = $term->name->value;
        }*/
        if ($node_load->get("field_related_disease_areas")
          ->referencedEntities()) {
          $terms = $node_load->get("field_related_disease_areas")
            ->referencedEntities();

          if (count($terms) == 1) {
            foreach ($terms as $term) {
              $parent_term_id = $term->parent->target_id;
              if (!$parent_term_id) {
                $indication = $term->label();
              }
            }
          }
          else {
            foreach ($terms as $term) {
              $parent_term_id = $term->parent->target_id;
              if ($term->hasField('field_group_header')) {
                if ($term->field_group_header->value) {
                  unset($term);
                }
                else {
                  // dsm($term->label());
                  $term_children = \Drupal::entityTypeManager()
                    ->getStorage('taxonomy_term')
                    ->loadChildren($term->id());
                  if ($term_children) {
                    $indication = $term->label();
                  }
                }
              }
              else {
                $term_children = \Drupal::entityTypeManager()
                  ->getStorage('taxonomy_term')
                  ->loadChildren($term->id());
                if ($term_children) {
                  $indication = $term->label();
                }
              }
            }
          }
        }
        if ($node_load->get("field_related_disease_areas")->getValue()) {
          $termAlias = $aliasManager->getAliasByPath('/taxonomy/term/' . $node_load->get("field_related_disease_areas")->entity->id());
          $relatedDiseases[$termAlias] = $node_load->get("field_related_disease_areas")->entity->label();
        }
        if ($node_load->get("field_related_to_congresses")->getValue()) {
          $term_congresses = $node_load->get("field_related_to_congresses")->getValue();
          foreach ($term_congresses as $term_congress) {
            if ($term_congress['target_id']) {
              $term_congress_load = Term::Load($term_congress['target_id']);
              $term_congress_children = \Drupal::entityTypeManager()
                ->getStorage('taxonomy_term')
                ->loadChildren($term_congress['target_id']);
              if (!empty($term_congress_load)) {
                if ($term_congress_children) {
                  unset($term_congress_load);

                }
                else {
                  $termAlias = $aliasManager->getAliasByPath('/taxonomy/term/' . $term_congress_load->id());
                  $relatedCongresses[$termAlias] = $term_congress_load->field_congress_abbr->value . ' ' . $term_congress_load->label();
                  // dsm(array_unique($relatedCongresses));
                }
              }

            }

          }

        }
        // dsm($nodesData);
        $nodesData[$nid] = [
          'publication_type' => $node_load->get("field_publication_type")->entity->label(),
          'journal' => $node_load->get("field_publication_journal")->value,
          // 'indication' => $node_load->get("field_publication_indication")->value,
          'indication' => $indication,
          'latest' => $latest,
          'datePublished' => $datePublished,
          'description' => $description_text,
          'alias' => $alias,
          'nid' => $nid,
          'checkBibliograpy' => check_bibliograpy($nid),
        ];
      }
      foreach ($params as $paramKey => $paramValue) {
        $filterKey = str_replace("_", "+", $paramKey);
        if ($paramKey == "sort") {
          $paramLinks[$filterKey] = [
            'value' => $paramValue,
            'label' => (string) $sort[$paramValue]['value'],
          ];
        }
        if (strpos($paramKey, 'type-') !== FALSE) {
          $typeRemove = str_replace("type-", "", str_replace("_", " ", $paramKey));
          $paramLinks[$filterKey] = [
            'value' => $paramValue,
            'label' => $types[$typeRemove]['value'],
          ];
        }
        if (strpos($paramKey, 'product-') !== FALSE) {
          $productRemove = str_replace("product-", "", str_replace("_", " ", $paramKey));

          $paramLinks[$filterKey] = [
            'value' => $paramValue,
            // 'label' => $diseaseAreas[$productRemove]['value'],
            'label' => $productRemove,
          ];
        }
        if (strpos($filterKey, 'indication-') !== FALSE) {
          $indicationRemove = str_replace("indication-", "", str_replace("_", " ", $paramKey));

          $paramLinks[$filterKey] = [
            'value' => $paramValue,
            'label' => $indicationRemove,
          ];
        }
        /* if (strpos($paramKey, 'study-') !== FALSE) {
        $studyRemove = str_replace("study-", "", str_replace("_", " ", $paramKey));
        $paramLinks[$filterKey] = [
        'value' => $paramValue,
        'label' => $studies[$studyRemove]['value'],
        ];
        }*/
        if (strpos($filterKey, 'congress-') !== FALSE) {
          $congressRemove = str_replace("congress-", "", str_replace("_", " ", $paramKey));
          $paramLinks[$filterKey] = [
            'value' => $paramValue,
            // 'label' => $congresses[$congressRemove]['value'],
            'label' => $congressRemove,
          ];
        }
      }
      $build = [
        '#theme' => 'term_publications',
        // '#tid' => $tids,
        '#data' => $nodesData,
        '#sort' => $sort,
        '#types' => $types,
        // '#studies' => $studies,
        '#congresses' => $congresses,
        '#diseaseAreas' => $diseaseAreas,
        '#indications' => $indications,
        '#filters' => $filters,
        '#paramLinks' => $paramLinks,
        '#show' => $show,
        '#count' => $totalcount,
        '#relatedDiseases' => $relatedDiseases,
        '#relatedCongresses' => $relatedCongresses,
        '#tid' => $term->id(),
        '#vid' => $term->bundle(),
        '#offset' => $offset,
        '#limit' => $limit,
        '#pagerCount' => $pager,
        '#bibliography' => $bibliography,
        '#vocabularyID' => $vocabularyID,
        '#attached' => [
          'library' => [
            'onconnect_custom/publications',
            'onconnect_bibliograpy/bibliography',
          ],
        ],
        '#pager' => [
          '#type' => 'pager',
        ],
        'pager' => [
          '#type' => 'pager',
        ],
      ];
      if ($vocabularyID == "congresses") {
        $build['#attached']['drupalSettings']['relatedDiseases'] = $relatedDiseases;
      }
      if ($vocabularyID == "disease_areas") {
        $build['#attached']['drupalSettings']['relatedCongresses'] = $relatedCongresses;
      }
      return $build;
    }
  }

}
