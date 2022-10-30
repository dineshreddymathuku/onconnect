<?php

namespace Drupal\onconnect_custom\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Url;
use Drupal\paragraphs\Entity\Paragraph;
use Drupal\media\Entity\Media;
use Drupal\file\Entity\File;

/**
 * Provides a block for Resources.
 *
 * @Block(
 *   id = "attachment_resources",
 *   admin_label = @Translation("Resources"),
 *   category = "Oncology"
 * )
 */
class Resources extends BlockBase {

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
    $params = \Drupal::request()->query->all();
    if ($term = \Drupal::routeMatch()->getParameter('taxonomy_term')) {
      $id = $term->id();
      $vocabularyID = $term->bundle();
      $attachmentsData = [];
      $attachments = $term->get("field_attachments")->getValue();
      if ($attachments) {
        foreach ($attachments as $attachment) {
          $attachmentLoad = Paragraph::load($attachment['target_id']);
          if ($attachmentLoad) {
            $type = $attachmentLoad->get("field_type")->value;
            if ($type == "link") {
              $uri = $attachmentLoad->get("field_resource_link")->uri;
              $title = $attachmentLoad->get("field_resource_link")->title;
              if (strpos($uri, 'internal:') !== FALSE || strpos($uri, 'entity:') !== FALSE) {
                $url = Url::fromUri($uri)->toString();
                $link = $url;
              }
              else {
                $link = $uri;
              }
              $attachmentsData["links"][] = [
                'url' => $link,
                'title' => $title,
              ];
            }
            if ($type == "file") {
              $mediaID = $attachmentLoad->get("field_upload_file")->target_id;
              if ($mediaID) {
                $mediaLoad = Media::load($mediaID);
                if ($mediaLoad) {
                  $fileID = $mediaLoad->get("field_media_file")->target_id;
                  $fileLoad = File::load($fileID);

                  if ($fileLoad) {
                    $attachmentsData["file"][] = [
                      'url' => $fileID,
                      'title' => $fileLoad->getFilename(),
                      'fileSize' => $this->formatBytes($fileLoad->getSize()),
                      'type' => strtoupper(str_replace("application/", "", $fileLoad->getMimeType())),
                    ];
                  }
                }
              }
            }
          }
        }
      }
      $build = [
        '#theme' => 'resources',
        '#attachments' => $attachmentsData,
      ];
      return $build;
    }
  }

  /**
   * Implements to get Size in KB or MB.
   */
  public function formatBytes($bytes) {
    if ($bytes > 0) {
      $i = floor(log($bytes) / log(1024));
      $sizes = ['B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];
      return sprintf('%.02F', round($bytes / pow(1024, $i), 1)) * 1 . ' ' . @$sizes[$i];
    }
    else {
      return 0;
    }
  }

}
