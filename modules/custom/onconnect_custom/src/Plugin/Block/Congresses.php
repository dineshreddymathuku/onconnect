<?php

namespace Drupal\onconnect_custom\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a block for Prostate Cancer.
 *
 * @Block(
 *   id = "congresses",
 *   admin_label = @Translation("Congresses"),
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
   * {@inheritdoc}
   */
  public function build() {
    $data = [];
    if ($term = \Drupal::routeMatch()->getParameter('taxonomy_term')) {
      $parent = \Drupal::entityTypeManager()
        ->getStorage('taxonomy_term')
        ->loadParents($term->id());
      $parent = reset($parent);
      if ($parent) {
        $abbr = $parent->field_congress_abbr->value;
        $children = \Drupal::entityTypeManager()
          ->getStorage('taxonomy_term')
          ->loadChildren($parent->id());
        if ($children) {
          $current_path = \Drupal::service('path.current')->getPath();
          $current_path_alias = \Drupal::service('path_alias.manager')
            ->getAliasByPath($current_path);
          foreach ($children as $child) {
            $alias = \Drupal::service('path_alias.manager')
              ->getAliasByPath('/taxonomy/term/' . $child->id());
            if ($current_path_alias == $alias) {
              $selected = 'selected';
              $selected_class = 'selected-congresses';
            }
            else {
              $selected = '';
              $selected_class = "";
            }
            $data['child_name'][$child->get("name")->value] = [
              'name' => $child->get("name")->value,
              'alias' => $alias,
              'selected' => $selected,
              'selected_class' => $selected_class,
            ];
          }
          krsort($data['child_name']);
        }
        // $target_id = $term->get('field_banner_image')->target_id;
        // if ($target_id) {
        // $bannerLoad = Media::load($target_id);
        // $mediaImage = $bannerLoad->get("field_media_image")->target_id;
        // $file = File::load($mediaImage);
        // $path = $file->createFileUrl();
        // $data['banner_url'] = file_create_url($path);
        // }
        $data['name'] = $term->get('name')->value;
        // $data['parentName'] = $parent->get('name')->value;
        $data['parentName'] = $abbr;
        $data['description'] = $term->get('description')->value;
      }
    }
    $build = [
      '#theme' => 'congresses',
      '#data' => $data,
    ];
    return $build;
  }

}
