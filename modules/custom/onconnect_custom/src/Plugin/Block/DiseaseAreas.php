<?php

namespace Drupal\onconnect_custom\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a block for Prostate Cancer.
 *
 * @Block(
 *   id = "disease_areas",
 *   admin_label = @Translation("Disease Areas"),
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
    if ($term = \Drupal::routeMatch()->getParameter('taxonomy_term')) {
      $parent = TRUE;
      $parentCheck = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadParents($term->id());
      $childCheck = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadTree('disease_areas', $term->id(), 1);

      $parentCheck = reset($parentCheck);
      if ($parentCheck) {
        if ($childCheck) {
          $parent = TRUE;
        }
        else {
          $parent = FALSE;
        }

      }
      else {
        $parent = TRUE;
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
      $data['description'] = $term->get('description')->value;
      $reference = $term->get('field_reference')->target_id;
      if ($reference) {
        $referenceItem = $term->get('field_reference')->entity;
        $data['referenceName'] = $referenceItem->label();
      }
      $data['reference'] = $reference ? \Drupal::service('path_alias.manager')
        ->getAliasByPath('/node/' . $reference) : "";
      $data['parent'] = $parent;
    }
    $build = [
      '#theme' => 'disease_areas',
      '#data' => $data,
    ];
    return $build;
  }

}
