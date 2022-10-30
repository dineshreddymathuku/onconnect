<?php

namespace Drupal\onconnect_primary_menu\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Url;
use Drupal\taxonomy\Entity\Term;

/**
 * Provides a block for Latest primary_menu.
 *
 * @Block(
 *   id = "primary_menu_block",
 *   admin_label = @Translation("Primary Menu Block"),
 *   category = "Oncology"
 * )
 */
class PrimaryMenuBlock extends BlockBase {

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
    $entityManager = \Drupal::entityTypeManager();
    $vocabularies = $entityManager->getStorage('taxonomy_vocabulary')
      ->loadMultiple();
    $aliasManager = \Drupal::service('path_alias.manager');
    $vids = [
      'disease_areas',
      'congresses',
      'data_dissemination',
      'resources',
    ];
    foreach ($vids as $vid) {
      $terms = $entityManager->getStorage('taxonomy_term')
        ->loadTree($vid, 0, 1);
      $termDataAll[$vid]['name'] = isset($vocabularies[$vid]) ? $vocabularies[$vid]->label() : '';
      foreach ($terms as $term) {
        $termLoadParent = Term::load($term->tid);

        $item = $entityManager->getStorage('taxonomy_term')
          ->loadByProperties(['tid' => $term->tid, 'vid' => $vid]);

        if ($item[$term->tid]->field_show_in_primary_menu->value && ($vid == 'congresses' || $vid == 'disease_areas')) {

          if ($item[$term->tid]->field_group_header->value && $vid == 'disease_areas') {

            $group_disease_areas = $entityManager->getStorage('taxonomy_term')
              ->loadTree($vid, $term->tid, 1);
            if (!empty($group_disease_areas)) {
              foreach ($group_disease_areas as $group_child) {
                // Load group child.
                $groupChildLoad = Term::load($group_child->tid);

                if ($groupChildLoad->field_show_in_primary_menu->value) {
                  $termDataAll[$vid]['group_header'][trim($term->name)]['children'][trim($group_child->name)]["tid"] = $group_child->tid;
                  $termDataAll[$vid]['group_header'][trim($term->name)]['children'][trim($group_child->name)]["name"] = $group_child->name;
                  $termDataAll[$vid]['group_header'][trim($term->name)]['children'][trim($group_child->name)]["alias"] = $aliasManager->getAliasByPath('/taxonomy/term/' . $group_child->tid);

                  $group_sub_child = $entityManager->getStorage('taxonomy_term')
                    ->loadTree($vid, $group_child->tid, 2);
                  if ($group_sub_child) {
                    foreach ($group_sub_child as $disease_sub_child) {
                      $disease_sub_childLoad = Term::load($disease_sub_child->tid);
                      if ($disease_sub_childLoad) {
                        if ($disease_sub_childLoad->field_show_in_primary_menu->value && !empty($entityManager->getStorage('taxonomy_term')->loadParents($disease_sub_child->tid))) {
                          $termAlias = $aliasManager->getAliasByPath('/taxonomy/term/' . $disease_sub_child->tid);
                          $termDataAll[$vid]['group_header'][trim($term->name)]['children'][trim($group_child->name)]['sub_child'][$termAlias]["alias"] = $termAlias;
                          $termDataAll[$vid]['group_header'][trim($term->name)]['children'][trim($group_child->name)]['sub_child'][$termAlias]["tid"] = $disease_sub_child->tid;
                          $termDataAll[$vid]['group_header'][trim($term->name)]['children'][trim($group_child->name)]['sub_child'][$termAlias]["name"] = $disease_sub_child->name;
                          $termDataAll[$vid]['group_header'][trim($term->name)]['children'][trim($group_child->name)]['view_all'] = $url ?: $aliasManager->getAliasByPath('/taxonomy/term/' . $group_child->tid);
                          $termDataAll[$vid]['group_header'][trim($term->name)]['children'][trim($group_child->name)]['sub_child'][$termAlias]["description"] = html_entity_decode($disease_sub_childLoad->get("description")->value);
                          if ($groupChildLoad->hasField("field_publication_concept_link")) {
                            if ($groupChildLoad->get("field_publication_concept_link")->uri) {
                              $field_publication_concept_link_url = $groupChildLoad->get("field_publication_concept_link")->uri;
                              $termDataAll[$vid]['group_header'][trim($term->name)]['children'][trim($group_child->name)]["submit_publication"] = $field_publication_concept_link_url;
                            }
                          }    if ($disease_sub_childLoad->hasField("field_reference")) {
                            if ($disease_sub_childLoad->get("field_reference")->target_id) {
                              $node = $disease_sub_childLoad->get("field_reference")->entity;
                              $termDataAll[$vid]['group_header'][trim($term->name)]['children'][trim($group_child->name)]['sub_child'][$termAlias]["node"]['label'] = $node->getTitle() . " Scientific Platform";
                              $termDataAll[$vid]['group_header'][trim($term->name)]['children'][trim($group_child->name)]['sub_child'][$termAlias]["node"]['alias'] = $aliasManager->getAliasByPath('/node/' . $node->id());
                            }
                          }
                        }

                      }

                    }
                  }

                  /*if ($termDataAll[$vid]['group_header'][trim($term->name)]['children'][trim($group_child->name)]['sub_child']) {

                  $termDataAll[$vid]['group_header'][trim($term->name)]['children'][trim($group_child->name)]['sub_child'] = array_slice($termDataAll[$vid]['group_header'][trim($term->name)]['children'][trim($group_child->name)]['sub_child'], 0, 4);
                  }*/
                }
                else {
                  $termDataAll[$vid]['group_header'][trim($term->name)]['alias'] = $aliasManager->getAliasByPath('/taxonomy/term/' . $term->tid);
                }

              }
            }
            else {
              // $termDataAll[$vid]['group_header'][trim($term->name)]['name'] = trim($term->name);
              $termDataAll[$vid]['group_header'][trim($term->name)]['alias'] = $aliasManager->getAliasByPath('/taxonomy/term/' . $term->tid);
            }
          }
          else {
            $termDataAll[$vid]['children'][trim($term->name)]["tid"] = $term->tid;
          }

          if ($vid == "congresses") {
            $termDataAll[$vid]['children'][trim($term->name)]["name"] = $item[$term->tid]->field_congress_abbr->value ?: trim($term->name);
          }
          else {
            $termDataAll[$vid]['children'][trim($term->name)]["name"] = trim($term->name);
          }

          $termDataAll[$vid]['children'][trim($term->name)]["alias"] = $aliasManager->getAliasByPath('/taxonomy/term/' . $term->tid);

          if ($termLoadParent->hasField("field_publication_concept_link")) {
            if ($termLoadParent->get("field_publication_concept_link")->uri) {
              $field_publication_concept_link_url = $termLoadParent->get("field_publication_concept_link")->uri;
              $termDataAll[$vid]['children'][trim($term->name)]["submit_publication"] = $field_publication_concept_link_url;
            }
          }
          if ($termLoadParent->hasField("field_url")) {
            if ($termLoadParent->get("field_url")->uri) {
              $url = $aliasManager->getAliasByPath(Url::fromUri($termLoadParent->get("field_url")->uri)
                ->toString());
              $termDataAll[$vid]['children'][trim($term->name)]["alias"] = $url;
            }
          }
          if ($vid == "disease_areas") {
            $termDataAll[$vid]['children'][trim($term->name)]['view_all'] = $url ?: $aliasManager->getAliasByPath('/taxonomy/term/' . $term->tid);

          }
          $terms_2 = $entityManager->getStorage('taxonomy_term')
            ->loadTree($vid, $term->tid, 1);
          if ($terms_2) {
            // array_slice() to restrict only four sub-child for disease_area.
            /* if ($vid == "disease_areas") {
            $terms_2 = array_slice($terms_2, 0, 4);
            }*/
            foreach ($terms_2 as $term_2) {
              $termLoad = Term::load($term_2->tid);
              if ($termLoad) {
                $termAlias = $aliasManager->getAliasByPath('/taxonomy/term/' . $term_2->tid);
                $termDataAll[$vid]['children'][trim($term->name)]['sub_child'][$termAlias]["tid"] = $term_2->tid;
                $termDataAll[$vid]['children'][trim($term->name)]['sub_child'][$termAlias]["name"] = $term_2->name;
                if ($vid == "disease_areas") {
                  $termDataAll[$vid]['children'][trim($term->name)]['sub_child'][$termAlias]["description"] = html_entity_decode($termLoad->get("description")->value);

                }
                $termDataAll[$vid]['children'][trim($term->name)]['sub_child'][$termAlias]["alias"] = $termAlias;
                if ($termLoad->hasField("field_reference")) {
                  if ($termLoad->get("field_reference")->target_id) {
                    $node = $termLoad->get("field_reference")->entity;
                    $termDataAll[$vid]['children'][trim($term->name)]['sub_child'][$termAlias]["node"]['label'] = $node->getTitle() . " Scientific Platform";
                    $termDataAll[$vid]['children'][trim($term->name)]['sub_child'][$termAlias]["node"]['alias'] = $aliasManager->getAliasByPath('/node/' . $node->id());
                  }
                }
                if (!$termLoad->get("field_show_in_primary_menu")->value && $vid == 'disease_areas') {
                  unset($termDataAll[$vid]['children'][trim($term->name)]['sub_child'][$termAlias]);
                }
              }
            }
            if ($termDataAll['disease_areas']['children'][trim($term->name)]['sub_child']) {
              array_slice($termDataAll['disease_areas']['children'][trim($term->name)]['sub_child'], 0, 4);
            }

            if (isset($termDataAll['congresses']['children'][trim($term->name)]['sub_child'])) {
              krsort($termDataAll['congresses']['children'][trim($term->name)]['sub_child']);
            }
          }
        }
        elseif ($vid == 'data_dissemination') {
          $pathActive = "in-active";
          $termDataAll[$vid]['children'][trim($term->name)]["name"] = trim($term->name);
          $termDataAll[$vid]['children'][trim($term->name)]["alias"] = $aliasManager->getAliasByPath('/taxonomy/term/' . $term->tid);
          $termDataAll[$vid]['children'][trim($term->name)]["active"] = $pathActive;
          $termLoadParent = Term::load($term->tid);
          $current_path = \Drupal::service('path.current')->getPath();
          $pathAlias = \Drupal::service('path_alias.manager')
            ->getAliasByPath($current_path);
          if ($pathAlias == $termDataAll[$vid]['children'][trim($term->name)]["alias"]) {
            $pathActive = "active highlighted";
          }
          if ($termLoadParent->hasField("field_url")) {
            if ($termLoadParent->get("field_url")->uri) {
              $url = $aliasManager->getAliasByPath(Url::fromUri($termLoadParent->get("field_url")->uri)
                ->toString());
              $termDataAll[$vid]['children'][trim($term->name)]["alias"] = $url;
              if ($pathAlias == $url) {
                $pathActive = "active highlighted";
                $termDataAll[$vid]['children'][trim($term->name)]["active"] = $pathActive;
              }
            }
          }
        }
        elseif ($vid == 'resources') {
          $termDataAll[$vid]['children'][trim($term->name)]["name"] = trim($term->name);
          $termDataAll[$vid]['children'][trim($term->name)]["alias"] = $aliasManager->getAliasByPath('/taxonomy/term/' . $term->tid);
          $pathActive = "in-active";
          $termDataAll[$vid]['children'][trim($term->name)]["active"] = $pathActive;
          $termLoadParent = Term::load($term->tid);
          $current_path = \Drupal::service('path.current')->getPath();
          $pathAlias = \Drupal::service('path_alias.manager')
            ->getAliasByPath($current_path);
          if ($pathAlias == $termDataAll[$vid]['children'][trim($term->name)]["alias"]) {
            $pathActive = "active highlighted";
          }
          if ($termLoadParent->hasField("field_url")) {
            if ($termLoadParent->get("field_url")->uri) {
              $url = $aliasManager->getAliasByPath(Url::fromUri($termLoadParent->get("field_url")->uri)
                ->toString());
              $termDataAll[$vid]['children'][trim($term->name)]["alias"] = $url;
            }
          }

          if ($termLoadParent->hasField("field_link")) {
            if ($termLoadParent->get("field_link")->value) {
              $termDataAll[$vid]['children'][trim($term->name)]["alias"] = $termLoadParent->get("field_link")->value;
            }
          }

          if ($pathAlias == $aliasManager->getAliasByPath('/taxonomy/term/' . $term->tid)) {

            $pathActive = "active highlighted";
            $termDataAll[$vid]['children'][trim($term->name)]["active"] = $pathActive;
          }

        }

        if ($item[$term->tid]->field_group_header->value) {
          unset($termDataAll[$vid]['children'][trim($term->name)]);
        }
      }
    }

    // For congresses construct first path as main parent alias.
    foreach ($termDataAll as $dataID => $termData) {
      if ($dataID == "congresses") {
        if (!empty($termData['children'])) {
          foreach ($termData['children'] as $mainID => $mainData) {
            $sub_childs = isset($mainData['sub_child']) ? $mainData['sub_child'] : [];
            if ($sub_childs) {
              foreach ($sub_childs as $sub_child) {
                $sub_childAlias = $sub_child['alias'];
                $termDataAll[$dataID]['children'][$mainID]['alias'] = $sub_childAlias;
                break;
              }
            }
          }
        }

      }

    }
    $termDataAll['disease_areas']['children']['other_indications'] = [
      'name' => $this->t("Other Indications"),
      'alias' => "/disease-areas/other-indications",
      'view_all' => "/disease-areas/other-indications",
    ];
    // ksort($termDataAll['congresses']['children']);.
    if (!empty($termDataAll['congresses']['children'])) {
      natcasesort($termDataAll['congresses']['children']);
    }

    $termDataAll['congresses']['children']['other_congresses'] = [
      'name' => $this->t("Other Congresses"),
      'alias' => "/congresses/other-congresses",
      'view_all' => "/congresses/other-congresses",
    ];

    return [
      '#theme' => 'primary_menu',
      '#menus' => $termDataAll,
      '#attached' => [
        'library' => [
          'onconnect_primary_menu/primary_menu_main',
        ],
      ],
    ];
  }

}
