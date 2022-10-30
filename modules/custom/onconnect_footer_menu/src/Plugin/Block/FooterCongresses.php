<?php

namespace Drupal\onconnect_footer_menu\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Url;
use Drupal\taxonomy\Entity\Term;

/**
 * Provides a block for Footer Congresses Block.
 *
 * @Block(
 *   id = "footer_congresses_block",
 *   admin_label = @Translation("Footer (Congresses)"),
 *   category = "Oncology"
 * )
 */
class FooterCongresses extends BlockBase {

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
    $vocabularies = \Drupal::entityTypeManager()
      ->getStorage('taxonomy_vocabulary')
      ->loadMultiple();
    $aliasManager = \Drupal::service('path_alias.manager');
    $vid = 'congresses';
    $terms = \Drupal::entityTypeManager()
      ->getStorage('taxonomy_term')
      ->loadTree($vid, 0, 1);
    $termDataAll['name'] = isset($vocabularies[$vid]) ? $vocabularies[$vid]->label() : '';
    foreach ($terms as $term) {
      $termLoadParent = Term::load($term->tid);
      $item = \Drupal::entityTypeManager()->getStorage('taxonomy_term')
        ->loadByProperties(['tid' => $term->tid, 'vid' => $vid]);
      // $termDataAll['children'][trim($term->name)]["name"] = $term->name;
      if ($item[$term->tid]->field_show_in_primary_menu->value) {
        $termDataAll['children'][trim($term->name)]["tid"] = $term->tid;
        $termDataAll['children'][trim($term->name)]["name"] = ($item[$term->tid]->field_congress_abbr->value ? $item[$term->tid]->field_congress_abbr->value : $term->name);
        $termDataAll['children'][trim($term->name)]["alias"] = $aliasManager->getAliasByPath('/taxonomy/term/' . $term->tid);
        if ($termLoadParent->hasField("field_url")) {
          if ($termLoadParent->get("field_url")->uri) {
            $url = $aliasManager->getAliasByPath(Url::fromUri($termLoadParent->get("field_url")->uri)
              ->toString());
            $termDataAll['children'][trim($term->name)]["alias"] = $url;
          }
        }
        $terms_2 = \Drupal::entityTypeManager()
          ->getStorage('taxonomy_term')
          ->loadTree($vid, $term->tid, 1);
        if ($terms_2) {
          foreach ($terms_2 as $term_2) {
            $termLoad = Term::load($term_2->tid);
            if ($termLoad) {
              $cihldTermAlias = $aliasManager->getAliasByPath('/taxonomy/term/' . $term_2->tid);
              $termDataAll['children'][trim($term->name)]['sub_child'][$cihldTermAlias]["tid"] = $term_2->tid;
              $termDataAll['children'][trim($term->name)]['sub_child'][$cihldTermAlias]["name"] = $term_2->name;
              if ($vid == "disease_areas") {
                $termDataAll['children'][trim($term->name)]['sub_child'][$cihldTermAlias]["description"] = $termLoad->get("description")->value;
              }
              $termDataAll['children'][trim($term->name)]['sub_child'][$cihldTermAlias]["alias"] = $cihldTermAlias;
              if ($termLoad->hasField("field_reference")) {
                if ($termLoad->get("field_reference")->target_id) {
                  $node = $termLoad->get("field_reference")->entity;
                  $termDataAll['children'][trim($term->name)]['sub_child'][$cihldTermAlias]["node"]['label'] = $node->getTitle() . " Scientific Platform";
                  $termDataAll['children'][trim($term->name)]['sub_child'][$cihldTermAlias]["node"]['alias'] = $aliasManager->getAliasByPath('/node/' . $node->id());
                }
              }
            }
            krsort($termDataAll['children'][trim($term->name)]['sub_child']);
          }
        }
        else {
          unset($termDataAll['children'][trim($term->name)]);
        }
      }
    }
    // For congresses construct first path as main parent alias.
    foreach ($termDataAll as $termData) {
      if (is_array($termData)) {
        foreach ($termData as $mainID => $mainData) {
          krsort($mainData['sub_child']);
          $sub_childs = isset($mainData['sub_child']) ? $mainData['sub_child'] : [];
          if ($sub_childs) {
            foreach ($sub_childs as $sub_child) {
              $sub_childAlias = $sub_child['alias'];
              $termDataAll['children'][$mainID]['alias'] = $sub_childAlias;
              break;
            }
          }
        }
      }
    }
    if (!empty($termDataAll['children'])) {
      ksort($termDataAll['children']);
    }

    $termDataAll['children']['other_congresses'] = [
      'name' => $this->t("Other Congresses"),
      'alias' => "/congresses/other-congresses",
      'view_all' => "/congresses/other-congresses",
    ];
    return [
      '#theme' => 'footer_menu',
      '#menus' => $termDataAll,
    ];
  }

}
