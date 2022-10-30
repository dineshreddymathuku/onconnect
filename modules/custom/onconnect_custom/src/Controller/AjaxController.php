<?php

namespace Drupal\onconnect_custom\Controller;

use Drupal\Component\Serialization\Json;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\node\Entity\Node;
use Drupal\taxonomy\Entity\Term;
use Symfony\Component\HttpFoundation\Request;

/**
 * AjaxController Class.
 */
class AjaxController extends ControllerBase {

  /**
   * Function.
   */
  public function callAjax(Request $request) {
    $filters = FALSE;
    $params = [];
    $url = \Drupal::request()->request->get('url');
    $url_components = parse_url($url);
    parse_str($url_components['query'], $params);
    if ($params) {
      $filters = TRUE;
    }
    $tid = \Drupal::request()->request->get('tid');
    $vocabularyID = \Drupal::request()->request->get('vid');
    $offset = \Drupal::request()->request->get('offset');
    $limit = \Drupal::request()->request->get('limit');
    $show = \Drupal::request()->request->get('show');
    $current_user = \Drupal::currentUser();
    $roles = $current_user->getRoles();
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
    $tids[$tid] = $tid;

    if ($vocabularyID == "disease_areas") {

      $terms_2 = \Drupal::entityTypeManager()
        ->getStorage('taxonomy_term')
        ->loadTree($vocabularyID, $tid, 1);
      if ($terms_2) {
        foreach ($terms_2 as $term_2) {
          $tids[$term_2->tid] = $term_2->tid;
        }
      }
    }
    $nid = \Drupal::entityQuery('node')
      ->accessCheck(FALSE);
    $nid->condition('type', 'publication');
    if ($vocabularyID == "disease_areas") {
      $nid->condition("field_related_disease_areas", $tids, "IN");
    }
    if ($vocabularyID == "congresses") {
      $nid->condition("field_related_to_congresses", $tids, "IN");
    }
    $nid->condition('status', 1);
    if ($params['sort'] == "a-z") {
      $nid->sort("title", 'DESC');
    }
    elseif ($params['sort'] == "z-a") {
      $nid->sort("title", 'ASC');
    }
    else {
      $nid->sort("changed", 'DESC');
    }
    foreach ($params as $paramKey => $paramValue) {
      if (strpos($paramKey, 'type-') !== FALSE) {
        $typesData[$paramValue] = $paramValue;
      }
      if (strpos($paramKey, 'congress-') !== FALSE) {
        $congressData[$paramValue] = $paramValue;
      }
      if (strpos($paramKey, 'study-') !== FALSE) {
        $studyData[$paramValue] = $paramValue;
      }
      if (strpos($paramKey, 'indication-') !== FALSE) {
        $indicationData[$paramValue] = $paramValue;
      }
      if (strpos($paramKey, 'product-') !== FALSE) {
        $productData[$paramValue] = $paramValue;
      }
    }
    if ($typesData) {
      $nid->condition("field_publication_type", $typesData, "IN");
    }
    if ($congressData) {
      $nid->condition("field_related_to_congresses", $congressData, "IN");
    }
    if ($productData) {
      $nid->condition("field_related_disease_areas", $productData, "IN");
    }
    if ($studyData) {
      $nid->condition("field_study_name", $studyData, "IN");
    }
    if ($indicationData) {
      $nid->condition("field_publication_indication", $indicationData, "IN");
    }
    $nid->range($offset + $limit, $show);
    $nids = $nid->execute();
    $nodesData = [];
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
      $description_text = $node_load->get("body")->value;
      $str = $description_text;
      if (strlen($str) > 250) {
        $description = explode("\n", wordwrap($str, 50));
        $description_text = $description[0] . '...';
      }
      $alias = \Drupal::service('path_alias.manager')
        ->getAliasByPath('/node/' . $nid);
      $publication_type = $node_load->get("field_publication_type")->entity->label();
      if ($publication_type) {
        $types[$publication_type] = [
          'value' => $publication_type,
          'id' => $node_load->get("field_publication_type")->target_id,
          'checked' => in_array($node_load->get("field_publication_type")->target_id, $params, TRUE) ? "checked" : '',
        ];
      }
      $studyName = $node_load->get("field_study_name")->value;
      if ($studyName) {
        $studies[$studyName] = [
          'value' => $studyName,
          'checked' => in_array($studyName, $params, TRUE) ? "checked" : '',
        ];
      }
      $indication = $node_load->get("field_publication_indication")->value;
      if ($indication) {
        $indications[$indication] = [
          'value' => $indication,
          'checked' => in_array($indication, $params, TRUE) ? "checked" : '',
        ];
      }
      $congressValues = $node_load->get("field_related_to_congresses")
        ->getValue();
      if ($congressValues) {
        foreach ($congressValues as $congressValue) {
          $congressLoad = Term::load($congressValue['target_id']);
          $congresses[$congressLoad->label()] = [
            'id' => $congressValue['target_id'],
            'value' => $congressLoad->label(),
            'checked' => in_array($congressValue['target_id'], $params, TRUE) ? "checked" : '',
          ];
        }
      }
      $diseaseAreaValues = $node_load->get("field_related_disease_areas")
        ->getValue();
      if ($diseaseAreaValues) {
        foreach ($diseaseAreaValues as $diseaseAreaValue) {
          $diseaseAreaLoad = Term::load($diseaseAreaValue['target_id']);
          $diseaseAreas[$diseaseAreaLoad->label()] = [
            'id' => $diseaseAreaValue['target_id'],
            'value' => $diseaseAreaLoad->label(),
            'checked' => in_array($diseaseAreaValue['target_id'], $params, TRUE) ? "checked" : '',
          ];
        }
      }
      $nodesData[$nid] = [
        'publication_type' => $node_load->get("field_publication_type")->entity->label(),
        'journal' => $node_load->get("field_publication_journal")->value,
        'indication' => $node_load->get("field_publication_indication")->value,
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
          'label' => $diseaseAreas[$productRemove]['value'],
        ];
      }
      if (strpos($paramKey, 'study-') !== FALSE) {
        $studyRemove = str_replace("study-", "", str_replace("_", " ", $paramKey));
        $paramLinks[$filterKey] = [
          'value' => $paramValue,
          'label' => $studies[$studyRemove]['value'],
        ];
      }
      if (strpos($filterKey, 'congress-') !== FALSE) {
        $congressRemove = str_replace("congress-", "", str_replace("_", " ", $paramKey));
        $paramLinks[$filterKey] = [
          'value' => $paramValue,
          'label' => $congresses[$congressRemove]['value'],
        ];
      }
      if (strpos($filterKey, 'indication-') !== FALSE) {
        $indicationRemove = str_replace("indication-", "", str_replace("_", " ", $paramKey));
        $paramLinks[$filterKey] = [
          'value' => $paramValue,
          'label' => $indications[$indicationRemove]['value'],
        ];
      }
    }

    $grid = '';
    $list = '';
    foreach ($nodesData as $node) {

      $grid .= '<div class="publications-grid-data">
	   <div class="publications-' . strtolower(str_replace(' ', '-', $node['publication_type'])) . '">
            <span class="bookmark"><a class="use-ajax"
                                      id="modal-popup-scroll"
                                      data-dialog-options="{&quot;width&quot;:488,&quot;position&quot;:&quot;right&quot;}"
                                      data-dialog-type="modal"
                                      href="' . $node['alias'] . '">' . $node['publication_type'] . '</a></span>
              <span data-nid="' . $node['nid'] . '" class="menu-icon menu-icon-grid">
              <a href="#" data-nid="' . $node['nid'] . '" aria-label="list-icon">
                <img src="/profiles/pfeconconnectpfcom_profile/themes/onconnect/images/icons/list_icon.svg"
                     alt="list-view-icon">
              </a>
            </span>
              <div id="menu-link-grid-' . $node['nid'] . '" class="menu-link-grid-dropdown hidden">
   <ul class="list-unstyled dropdown-grid">' .
        (in_array("self_service_editor", $roles) || in_array("self_service_editor", $roles) || in_array("self_service_editor", $roles) ? '<li>
                      <a href="/node/' . $node['nid'] . '/edit">' . t("Edit") . '</a>
                    </li>' : '') . '

                  <li>
                    <a class="bibliograpy-links' . $node['checkBibliograpy']['class'] . '"
                       data-nid="' . $node['nid'] . '" id="publication-' . $node['nid'] . '" onclick="return false;"
                       href="' . $node['checkBibliograpy']['link'] . '">' . $node['checkBibliograpy']['title'] . '</a>
                  </li>
                  <li>
                    <a class="use-ajax" href="/node/' . $node['nid'] . '"
                       data-dialog-options="{&quot;width&quot;:488,&quot;position&quot;:&quot;right&quot;}"
                       data-dialog-type="modal">' . t("Open") . '</a>
                  </li>
                </ul>
              </div>
            </div>
					<div class="publications-date">' .
        ($node['latest'] ? '<span class="latest">
					<img
                    src="/profiles/pfeconconnectpfcom_profile/themes/onconnect/images/icons/Ellipse.svg" alt="dot-icon">
              </span>' : ' ') . '
                <span class="date">' . $node['datePublished'] . '</span>
            </div>

            <div class="publications-description grid-text">' . $node['description'] . '</div>
            <div class="publication-journal-container clearfix pt-3">' .

        ($node['journal'] ? '<div class="publication-journal">
                  <div class="grid-journal-title">' . t("Journal") . '</div>
                  <div class="grid-journal">' . t($node['journal']) . '</div>
					</div>' : ' ') .

        ($node['indication'] ? '
				  <div class="publication-indication-grid">
                  <div class="grid-indication-title">' . t("Indication") . '</div>
                  <div class="grid-indication">' . t($node['indication']) . '</div>
                </div>' : ' ') . '
            </div>
            </div>';
      // List.
      $list .= '<div class = "publications-list hidden">
      <div class="publications-list-data row">
          <div class="col-lg-2 col-md-2 publications-' . strtolower(str_replace(' ', '-', $node['publication_type'])) . '">
          <span class="bookmark"><a class="use-ajax" id="modal-popup-scroll-list"
                                    data-dialog-options="{&quot;width&quot;:488,&quot;position&quot;:&quot;right&quot;}"
                                    data-dialog-type="modal"
                                    href="' . $node['alias'] . '">' . $node['publication_type'] . '</a></span>

             <div class="publications-date">' .
        ($node['latest'] ? '<span class="latest"><img
                    src="/profiles/pfeconconnectpfcom_profile/themes/onconnect/images/icons/Ellipse.svg" alt="dot-icon">
            </span>' : '') . '
              <span class="date">' . $node['datePublished'] . '</span>
            </div>
          </div>

          <div class="col-lg-4 col-md-4">
            <span class="list-title">' . t('title') . '</span>
            <div class="publications-description list-text">' . $node['description'] . '</div>
          </div>
		    <div class="col-lg-4 col-md-4">
            <div class="row">
              <div class="col-lg-6 col-md-6">' .
        ($node['journal'] ? '<div class="list-journal-title">' . t("Journal") . '</div>
                  <div class="publication-journal">' . t($node['journal']) . '</div>' : ' ') . '
              </div>
              <div class="col-lg-6 col-md-6">' .
        ($node['indication'] ? '<div class="list-indication-title"> ' . t("Indication") . '</div>
                  <div class="publication-indication">' . t($node['indication']) . '</div>' : ' ') . '
              </div>
              </div>
              </div>

			  <div data-nid="' . $node['nid'] . '" class="col-lg-2 col-md-2 menu-icon menu-icon-list"><img
              src="/profiles/pfeconconnectpfcom_profile/themes/onconnect/images/icons/list_icon.svg"
              alt="list-view-icon">
			  <div id="menu-link-list-' . $node['nid'] . '" class="menu-link-list-dropdown hidden">
			   <ul class="list-unstyled dropdown-list">' .
             (in_array("self_service_editor", $roles) || in_array("self_service_editor", $roles) || in_array("self_service_editor", $roles) ? '<li>
                      <a href="/node/' . $node['nid'] . '/edit">' . t("Edit") . '</a>
                    </li>' : '') . '
			       <li>
                    <a class="bibliograpy-links' . $node['checkBibliograpy']['class'] . '"
                       data-nid="' . $node['nid'] . '" id="publication-' . $node['nid'] . '" onclick="return false;"
                       href="' . $node['checkBibliograpy']['link'] . '">' . $node['checkBibliograpy']['title'] . '</a>
                  </li>
                  <li>
                    <a class="use-ajax" href="/node/' . $node['nid'] . '"
                       data-dialog-options="{&quot;width&quot;:488,&quot;position&quot;:&quot;right&quot;}"
                       data-dialog-type="modal">' . t("Open") . '</a>
                  </li>
              </ul>
              </div>
              </div>
              </div>
              </div>';
    }
    $output = ['grid' => $grid, 'list' => $list];
    $build = [
      '#type' => 'markup',
      '#markup' => JSON::encode($output),
    ];
    return new AjaxResponse($output);
  }

}
