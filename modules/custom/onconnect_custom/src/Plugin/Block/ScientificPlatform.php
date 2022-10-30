<?php

namespace Drupal\onconnect_custom\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\file\Entity\File;

/**
 * Provides a block for Scientific Platform.
 *
 * @Block(
 *   id = "scientific_platform",
 *   admin_label = @Translation("Scientific Platform"),
 *   category = "Oncology"
 * )
 */
class ScientificPlatform extends BlockBase {

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
    if ($node = \Drupal::routeMatch()->getParameter('node')) {
      if ($node->getType() == "scientific_platform") {
        if ($node->get("field_banner_image")->target_id) {
          $imageUrl = File::load($node->get("field_banner_image")->target_id)
            ->createFileUrl();
          $data['url'] = $imageUrl;
        }
        else {
          if (\Drupal::config('onconnect_top_banner.admin_settings')
            ->get('scientific_platform_banner_image')) {
            $mid = \Drupal::config('onconnect_top_banner.admin_settings')
              ->get('scientific_platform_banner_image');
            if ($mid) {
              $file = File::load($mid);
              if ($file) {
                $path = $file->createFileUrl();
                $data['url'] = $path;
              }
            }
          }
        }
      }
      $data['title'] = $node->getTitle();
      $data['nid'] = $node->id();
      $data['updated'] = $node->get('field_updated_date')->value ? date("m/d/Y", strtotime($node->get('field_updated_date')->value)) : '';
      $data['field_platform_file'] = $node->get('field_platform_file')->getValue()[0]['uri'];
    }
    $build = [
      '#theme' => 'scientific_platform',
      '#data' => $data,
    ];
    return $build;
  }

}
