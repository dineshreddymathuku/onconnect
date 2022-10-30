<?php

namespace Drupal\onconnect_footer_menu\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Url;
use Drupal\taxonomy\Entity\Term;

/**
 * Provides a block for Footer Disease Areas Block.
 *
 * @Block(
 *   id = "footer_disease_areas_block",
 *   admin_label = @Translation("Footer (Disease Areas)"),
 *   category = "Oncology"
 * )
 */
class FooterDiseaseAreas extends BlockBase {

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
    $vocabularies = \Drupal::entityTypeManager()
      ->getStorage('taxonomy_vocabulary')
      ->loadMultiple();
    $aliasManager = \Drupal::service('path_alias.manager');
    $vid = 'disease_areas';
    $terms = \Drupal::entityTypeManager()
      ->getStorage('taxonomy_term')
      ->loadTree($vid, 0, 1);
    $termDataAll['name'] = isset($vocabularies[$vid]) ? $vocabularies[$vid]->label() : '';
    foreach ($terms as $term) {

      $termLoadParent = Term::load($term->tid);
      $item = \Drupal::entityTypeManager()->getStorage('taxonomy_term')
        ->loadByProperties(['tid' => $term->tid, 'vid' => $vid]);

      if ($item[$term->tid]->field_show_in_primary_menu->value) {
        if ($item[$term->tid]->field_group_header->value) {

          $group_disease_areas = $entityManager->getStorage('taxonomy_term')
            ->loadTree($vid, $term->tid, 1);

          if (!empty($group_disease_areas)) {
            foreach ($group_disease_areas as $group_child) {

              $groupChildLoad = Term::load($group_child->tid);
              if ($groupChildLoad->field_show_in_primary_menu->value) {
                $termDataAll['group_header'][trim($term->name)]['children'][trim($group_child->name)]["name"] = $group_child->name;
                $termDataAll['group_header'][trim($term->name)]['children'][trim($group_child->name)]["alias"] = $aliasManager->getAliasByPath('/taxonomy/term/' . $group_child->tid);

              }
              else {
                $termDataAll['group_header'][trim($term->name)]['alias'] = $aliasManager->getAliasByPath('/taxonomy/term/' . $term->tid);
              }

            }
          }
          else {

            $termDataAll['group_header'][trim($term->name)]['alias'] = $aliasManager->getAliasByPath('/taxonomy/term/' . $term->tid);
          }

        }
        $termDataAll['children'][$term->tid]["tid"] = $term->tid;
        $termDataAll['children'][$term->tid]["name"] = $term->name;
        $termDataAll['children'][$term->tid]["alias"] = $aliasManager->getAliasByPath('/taxonomy/term/' . $term->tid);

        if ($termLoadParent->hasField("field_url")) {
          if ($termLoadParent->get("field_url")->uri) {
            $url = $aliasManager->getAliasByPath(Url::fromUri($termLoadParent->get("field_url")->uri)
              ->toString());
            $termDataAll['children'][$term->tid]["alias"] = $url;
          }
        }

      }

      if ($item[$term->tid]->field_group_header->value) {
        unset($termDataAll['children'][trim($term->tid)]);
      }
    }
    $termDataAll['group_header']['Other Indications'] = [
      'name' => $this->t("Other Indications"),
      'alias' => "/disease-areas/other-indications",
      'view_all' => "/disease-areas/other-indications",
    ];
    return [
      '#theme' => 'footer_menu',
      '#menus' => $termDataAll,
    ];
  }

}
