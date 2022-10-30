<?php

namespace Drupal\onconnect_import;

use Drupal\node\Entity\Node;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Drupal\taxonomy\Entity\Term;

/**
 * Import Data.
 */
class ImportData {

  /**
   * {@inheritdoc}
   */
  public static function createPublicationNode($rows, &$context) {
    $message = 'Importing content..';
    $results = [];
    $related_disease_type = [];

    foreach ($rows as $row) {
      // Considering ID as first column.
      $title_text = trim($row[14]);

      // Check whether the publication exist in our system or not.
      $values = \Drupal::entityQuery('node')
        ->condition('title', $title_text)
        ->condition('field_id', $row[18])
        ->accessCheck(FALSE)
        ->execute();
      $node_not_exists = empty($values);

      $data = [];
      // ID.
      $data['id'] = ($row[18] ? $row[18] : '');
      // Title.
      $data['title_text'] = trim($row[14]);
      if (strlen($title_text) > 255) {
        $data['title'] = substr($title_text, 0, 250);
      }
      else {
        $data['title'] = $title_text;
      }
      // Publication type.
      if ($row[1]) {
        $publication = \Drupal::entityTypeManager()->getStorage('taxonomy_term')
          ->loadByProperties([
            'name' => trim($row[1]),
            'vid' => 'publication_type',
          ]);
        if ($publication) {
          $publication = reset($publication);
        }
        else {
          $publication = Term::create([
            'name' => trim($row[1]),
            'vid' => 'publication_type',
          ]);
          $publication->save();
        }
        $data['publication_type'] = $publication->id();
      }
      else {
        $data['publication_type'] = 0;
      }
      // Disease area, Indications & Product Mapping.
      if ($row[4]) {
        $disease_area = \Drupal::entityTypeManager()
          ->getStorage('taxonomy_term')
          ->loadByProperties([
            'name' => trim($row[4]),
            'vid' => 'disease_areas',
          ]);
        if ($disease_area) {
          $disease_area = reset($disease_area);
        }
        else {
          $disease_area = Term::create([
            'name' => trim($row[4]),
            'vid' => 'disease_areas',
          // 'description' => TRUE,
          ]);
          $disease_area->save();
        }
        $data['disease_area'] = $disease_area->id();
      }
      // Get Indication term id.
      if ($row[3]) {
        $indication = \Drupal::entityTypeManager()->getStorage('taxonomy_term')
          ->loadByProperties([
            'name' => trim($row[3]),
            'vid' => 'disease_areas',
            'parent' => ($data['disease_area'] ? $data['disease_area'] : ''),
          ]);
        if ($indication) {
          $indication = reset($indication);
        }
        else {
          $indication = Term::create([
            'parent' => ($data['disease_area'] ? $data['disease_area'] : ''),
            'name' => trim($row[3]),
            'vid' => 'disease_areas',
             // 'description' => TRUE,
          ]);
          $indication->save();
        }
        $data['indication'] = $indication->id();
      }
      // Get Product term id.
      if ($row[2]) {

        if (strstr(trim($row[2]), "+")) {
          $spilt_products_array = explode("+", str_replace(" ", "", trim($row[2])));

          $product = [];
          foreach ($spilt_products_array as $key => $split_product) {

            $spilt_product_load = \Drupal::entityTypeManager()->getStorage('taxonomy_term')
              ->loadByProperties([
                'name' => trim($split_product),
                'vid' => 'disease_areas',
                'parent' => ($data['indication'] ? $data['indication'] : ''),
              ]);

            if ($spilt_product_load) {
              $spilt_product_load = reset($spilt_product_load);
            }
            else {
              $spilt_product_load = Term::create([
                'parent' => ($data['indication'] ? $data['indication'] : ''),
                'name' => trim($split_product),
                'vid' => 'disease_areas',
               // 'description' => TRUE,
              ]);
              $spilt_product_load->save();
            }
            $product[$key] = $spilt_product_load->id();
          }

          // $data['product'] = implode(",",$product);
          $data['product'] = $product;
          $related_disease_type = [$data['disease_area'],
            $data['indication'],
          ];
          $related_disease_type = array_merge($related_disease_type, $data['product']);

        }
        else {

          $product = \Drupal::entityTypeManager()->getStorage('taxonomy_term')
            ->loadByProperties([
              'name' => trim($row[2]),
              'vid' => 'disease_areas',
              'parent' => ($data['indication'] ? $data['indication'] : ''),
            ]);

          if ($product) {
            $product = reset($product);
          }
          else {
            $product = Term::create([
              'parent' => ($data['indication'] ? $data['indication'] : ''),
              'name' => trim($row[2]),
              'vid' => 'disease_areas',
              // 'description' => TRUE,
            ]);
            $product->save();
          }
          $data['product'] = $product->id();
          $related_disease_type = [
            $data['disease_area'],
            $data['indication'],
            $data['product'],
          ];
        }

      }
      else {
        $data['product'] = 0;
        $related_disease_type = [
          $data['disease_area'],
          $data['indication'],
          $data['product'],
        ];
      }
      // Journal.
      $data['journal'] = ($row[5] ? trim($row[5]) : '');

      // Publication Year.
      $data['publication_year'] = ($row[6] ? date('Y-m-d', Date::excelToTimestamp($row[6])) : '');

      // Congresses.
      if (!$row[7] || ($row[7] == 'N/A')) {
        $data['congresses'] = 0;
      }
      else {
        $congresses = \Drupal::entityTypeManager()->getStorage('taxonomy_term')
          ->loadByProperties(['name' => trim($row[7]), 'vid' => 'congresses']);
        if ($congresses) {
          $congresses = reset($congresses);
        }
        else {
          $congresses = Term::create([
            'name' => trim($row[7]),
            'field_congress_abbr' => $row[8],
            'vid' => 'congresses',
          ]);
          $congresses->save();
        }
        $data['congresses'] = $congresses->id();
        if ($data['congresses']) {
          // Congresses Years terms.
          if ($row[10] && ($row[11])) {

            $year = ($row[10] ? date('Y', Date::excelToTimestamp($row[10])) : '');

            $start = date('Y-m-d', Date::excelToTimestamp($row[10]));
            $end = date('Y-m-d', Date::excelToTimestamp($row[11]));

            $cong_year = \Drupal::entityTypeManager()->getStorage('taxonomy_term')
              ->loadByProperties([
                'name' => $year,
                'vid' => 'congresses',
                'parent' => $data['congresses'],
              ]);

            if ($cong_year) {
              $cong_year = reset($cong_year);
            }
            else {
              $cong_year = Term::create([
                'name' => $year,
                'field_congress_abbr' => $row[8],
                'field_congress_loc' => trim($row[9]),
                'field_congressstart' => $start,
                'field_congressend' => $end,
                'parent' => $data['congresses'],
                'vid' => 'congresses',
              ]);
              $cong_year->save();
            }
            $data['cong_year'] = $cong_year->id();
          }
          else {
            $data['cong_year'] = 0;
          }
        }
      }

      // DocAttachFileName.
      $data['resource_link'] = "https://s3-pfe-onconnect-s3-production.s3.amazonaws.com/" . $row[16] . '/' . $row[0];

      // Authors.
      $data['authors'] = ($row[13] ? trim($row[13]) : '');

      // Citation Information.
      $data['reference_citation'] = trim($row[15]);

      // ID.
      $data['id'] = ($row[18] ? $row[18] : '');

      // dsm($related_disease_type);
      // die();
      // If node does not exist create new node.
      if ($node_not_exists) {
        $node = \Drupal::entityTypeManager()->getStorage('node')->create([
          'type' => 'publication',
          'title' => $data['title'],
          'field_id' => $data['id'],
          'field_publication_type' => $data['publication_type'],
        /*'field_related_disease_areas' => [
        $data['disease_area'],
        $data['indication'],
        $data['product'],
        ],*/
          'field_related_disease_areas' => $related_disease_type,
          'field_publication_journal' => $data['journal'],
          'field_publication_year' => $data['publication_year'],
          'field_related_to_congresses' => [
            $data['congresses'],
            $data['cong_year'],
          ],
          'field_doc_authors' => $data['authors'],
          'field_title_text' => $data['title_text'],
          'field_reference_citation' => $data['reference_citation'],
          'field_resource_link' => $data['resource_link'],
        ]);
        $node->setPublished(TRUE);
        $node->set('moderation_state', 'published');
        $results[] = $node->save();
        // dsm($data);
      }
      else {
        // dsm("here");
        // If node exist update the node.
        $nid = reset($values);

        if ($nid) {
          $node = Node::load($nid);
          $node->set('title', $data['title']);
          $node->set('field_id', $data['id']);
          $node->set('field_title_text', $data['title_text']);
          $node->set('field_publication_type', $data['publication_type']);
          /*$node->set('field_related_disease_areas', [
          $data['disease_area'],
          $data['indication'],
          $data['product'],
          ]);*/
          $node->set('field_related_disease_areas', $related_disease_type);
          $node->set('field_publication_journal', $data['journal']);
          $node->set('field_publication_year', $data['publication_year']);
          $node->set('field_related_to_congresses', [
            $data['congresses'],
            $data['cong_year'],
          ]);
          $node->set('field_doc_authors', $data['authors']);
          $node->set('field_reference_citation', $data['reference_citation']);
          $node->set('field_resource_link', $data['resource_link']);
          $node->setPublished(TRUE);

          // $node->set('moderation_state', 'needs_review');
          // $node->save();
          $node->set('moderation_state', 'published');
          $results[] = $node->save();
          // dsm($data);
        }
      }
    }
    $context['message'] = $message;
    $context['results'] = $results;
  }

  /**
   * {@inheritdoc}
   */
  public static function callbackFinishedImport($success, $results, $operations) {
    if ($success) {
      $message = 'Import completed for ' . count($results);
    }
    else {
      $message = t('Finished with an error.');
    }
    \Drupal::messenger()->addStatus($message);
  }

}
