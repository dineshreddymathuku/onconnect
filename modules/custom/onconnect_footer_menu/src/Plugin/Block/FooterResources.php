<?php

namespace Drupal\onconnect_footer_menu\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Url;
use Drupal\taxonomy\Entity\Term;

/**
 * Provides a block for Footer Resources Block.
 *
 * @Block(
 *   id = "footer_resources_block",
 *   admin_label = @Translation("Footer (Resources)"),
 *   category = "Oncology"
 * )
 */
class FooterResources extends BlockBase {

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
    $current_path = \Drupal::service('path.current')->getPath();
    $pathAlias = \Drupal::service('path_alias.manager')
      ->getAliasByPath($current_path);
    $vocabularies = \Drupal::entityTypeManager()
      ->getStorage('taxonomy_vocabulary')
      ->loadMultiple();
    $aliasManager = \Drupal::service('path_alias.manager');
    $vid = 'resources';
    $terms = \Drupal::entityTypeManager()
      ->getStorage('taxonomy_term')
      ->loadTree($vid, 0, 1);
    $termDataAll['name'] = isset($vocabularies[$vid]) ? $vocabularies[$vid]->label() : '';
    foreach ($terms as $term) {
      $pathActive = "in-active";
      $termLoadParent = Term::load($term->tid);
      if ($termLoadParent) {
        $termAlias = $aliasManager->getAliasByPath('/taxonomy/term/' . $term->tid);
        if ($pathAlias == $termAlias) {
          $pathActive = "active highlighted";
        }
        $termDataAll['children'][$term->tid]["tid"] = $term->tid;
        $termDataAll['children'][$term->tid]["name"] = $term->name;
        $termDataAll['children'][$term->tid]["alias"] = $termAlias;
        $termDataAll['children'][$term->tid]["active"] = $pathActive;

        if ($termLoadParent->hasField("field_url")) {
          if ($termLoadParent->get("field_url")->uri) {
            $url = $aliasManager->getAliasByPath(Url::fromUri($termLoadParent->get("field_url")->uri)
              ->toString());
            $termDataAll['children'][$term->tid]["alias"] = $url;
          }
        }
        if ($termLoadParent->hasField("field_link")) {
          if ($termLoadParent->get("field_link")->value) {
            $termDataAll['children'][$term->tid]["alias"] = $termLoadParent->get("field_link")->value;
          }
        }
      }
    }

    return [
      '#theme' => 'footer_menu',
      '#menus' => $termDataAll,
    ];
  }

}
