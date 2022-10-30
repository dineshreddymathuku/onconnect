<?php

namespace Drupal\onconnect_import\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\file\Entity\File;
use Drupal\taxonomy\Entity\Term;

/**
 * Provides the form for uploading metrics.
 */
class ImportMetricsForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'onconnect_content_import_metrics';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form = [
      '#attributes' => ['enctype' => 'multipart/form-data'],
    ];
    $extensions = 'csv';
    $validators = [
      'file_validate_extensions' => [$extensions],
    ];
    $form['metrics_type'] = [
      '#type' => 'select',
      '#title' => $this->t('Type'),
      '#options' => [
        'congress' => $this->t('Congress'),
        'disease_area' => $this->t('Disease Area'),
        'asset' => $this->t('Asset'),
      ],
      '#required' => TRUE,
      '#empty_option' => $this->t("Select"),
    ];
    $form['congress_headers_fieldset'] = [
      '#type' => 'fieldset',
      '#states' => [
        'visible' => [
          ':input[name=metrics_type]' => ['value' => 'congress'],
        ],
      ],
    ];
    $congress_headers = [
      'Congress name',
      'Abstracts submitted',
      'Poster presentations',
      'Oral presentations',
    ];
    $form['congress_headers_data'] = [
      '#type' => 'hidden',
      '#value' => $congress_headers,
    ];
    $form['congress_headers_fieldset']['congress_headers'] = [
      '#type' => 'markup',
      '#markup' => "CSV Headers will be in below format.<br><ul><li>Congress name</li><li>Abstracts submitted</li><li>Poster presentations</li><li>Oral presentations</li></ul>",
    ];
    $form['disease_area_headers_fieldset'] = [
      '#type' => 'fieldset',
      '#states' => [
        'visible' => [
          ':input[name=metrics_type]' => ['value' => 'disease_area'],
        ],
      ],
    ];
    $form['disease_area_headers_fieldset']['disease_area'] = [
      '#type' => 'markup',
      '#markup' => "CSV Headers will be in below format.<br><ul>
<li>Disease area name</li>
<li>Abstracts submitted</li>
<li>Manuscripts submitted</li>
<li>Manuscripts published</li>
<li>Poster presentations</li>
<li>Oral presentations</li>
</ul>",
    ];
    $disease_area_headers = [
      'Disease area name',
      'Abstracts submitted',
      'Manuscripts submitted',
      'Manuscripts published',
      'Poster presentations',
      'Oral presentations',
    ];
    $form['disease_area_headers_data'] = [
      '#type' => 'hidden',
      '#value' => $disease_area_headers,
    ];
    $asset_headers = [
      'Asset name',
      'Abstracts submitted',
      'Manuscripts submitted',
      'Manuscripts published',
      'Poster presentations',
      'Oral presentations',
    ];
    $form['asset_headers_data'] = [
      '#type' => 'hidden',
      '#value' => $asset_headers,
    ];
    $form['asset_headers_fieldset'] = [
      '#type' => 'fieldset',
      '#states' => [
        'visible' => [
          ':input[name=metrics_type]' => ['value' => 'asset'],
        ],
      ],
    ];
    $form['asset_headers_fieldset']['asset'] = [
      '#type' => 'markup',
      '#markup' => "CSV Headers will be in below format.<br><ul>
<li>Asset name</li>
<li>Abstracts submitted</li>
<li>Manuscripts submitted</li>
<li>Manuscripts published</li>
<li>Poster presentations</li>
<li>Oral presentations</li>
</ul>",
    ];
    $form['metrics_file'] = [
      '#required' => TRUE,
      '#type' => 'managed_file',
      '#name' => 'metrics_file',
      '#title' => t('File'),
      '#size' => 20,
      '#description' => t('CSV format only'),
      '#upload_validators' => $validators,
      '#upload_location' => 'public://content/metrics_files/',
    ];
    $form['actions']['#type'] = 'actions';
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Import'),
      '#button_type' => 'primary',
    ];

    return $form;

  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    $values = $form_state->getValues();
    $metrics_type = $values['metrics_type'];
    $metrics_file = $values['metrics_file'][0];
    $fileLoad = File::load($metrics_file);
    $csv = [];
    $i = 0;
    if (($handle = fopen($fileLoad->getFileUri(), "r")) !== FALSE) {
      $columns = fgetcsv($handle, 1000, ",");
      while (($row = fgetcsv($handle, 1000, ",")) !== FALSE) {
        $csv[$i] = array_combine($columns, $row);
        $i++;
      }
      fclose($handle);
    }
    $csvHeader = array_keys($csv[0]);
    if ($metrics_type == "congress") {
      $headers = $values['congress_headers_data'];
      if (array_diff($csvHeader, $headers) != array_diff($headers, $csvHeader)) {
        $form_state->setErrorByName("metrics_file", $this->t("Congress headers not matched."));
      }
    }
    if ($metrics_type == "disease_area") {
      $headers = $values['disease_area_headers_data'];
      if (array_diff($csvHeader, $headers) != array_diff($headers, $csvHeader)) {
        $form_state->setErrorByName("metrics_file", $this->t("Disease area headers not matched."));
      }
    }
    if ($metrics_type == "asset") {
      $headers = $values['asset_headers_data'];
      if (array_diff($csvHeader, $headers) != array_diff($headers, $csvHeader)) {
        $form_state->setErrorByName("metrics_file", $this->t("Asset headers not matched."));
      }
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $values = $form_state->getValues();
    $form_state->setRebuild();
    $metrics_type = $values['metrics_type'];
    $metrics_file = $values['metrics_file'][0];
    $fileLoad = File::load($metrics_file);
    $csv = [];
    $i = 0;
    if (($handle = fopen($fileLoad->getFileUri(), "r")) !== FALSE) {
      $columns = fgetcsv($handle, 1000, ",");
      while (($row = fgetcsv($handle, 1000, ",")) !== FALSE) {
        $csv[$i] = array_combine($columns, $row);
        $i++;
      }
      fclose($handle);
    }
    $csvHeader = array_keys($csv[0]);
    if ($metrics_type == "congress") {
      foreach ($csv as $csvHeader => $csvData) {
        if ($csvData['Congress name']) {
          $termName = $csvData['Congress name'];
        }
        if ($termName) {
          $term = $this->getTidByName($termName, "congresses");
          if ($term) {
            $termLoad = Term::load($term);
            if ($csvData['Abstracts submitted']) {
              $termLoad->set("field_abstracts_submitted", $csvData['Abstracts submitted']);
            }
            if ($csvData['Poster presentations']) {
              $termLoad->set("field_poster_presentations", $csvData['Poster presentations']);
            }
            if ($csvData['Oral presentations']) {
              $termLoad->set("field_oral_presentations", $csvData['Oral presentations']);
            }
            $termLoad->save();
          }
        }
      }
    }
    if ($metrics_type == "disease_area") {
      foreach ($csv as $csvHeader => $csvData) {
        if ($csvData['Disease area name']) {
          $termName = $csvData['Disease area name'];
        }
        if ($termName) {
          $term = $this->getTidByName($termName, "disease_areas");
          if ($term) {
            $termLoad = Term::load($term);
            if ($csvData['Abstracts submitted']) {
              $termLoad->set("field_abstracts_submitted", $csvData['Abstracts submitted']);
            }
            if ($csvData['Manuscripts submitted']) {
              $termLoad->set("field_manuscripts_submitted", $csvData['Manuscripts submitted']);
            }
            if ($csvData['Manuscripts published']) {
              $termLoad->set("field_manuscripts_published", $csvData['Manuscripts published']);
            }
            if ($csvData['Poster presentations']) {
              $termLoad->set("field_poster_presentations", $csvData['Poster presentations']);
            }
            if ($csvData['Oral presentations']) {
              $termLoad->set("field_oral_presentations", $csvData['Oral presentations']);
            }
            $termLoad->save();
          }
        }
      }
    }
    if ($metrics_type == "asset") {
      $headers = $values['asset_headers_data'];
      if (array_diff($csvHeader, $headers) != array_diff($headers, $csvHeader)) {
        $form_state->setErrorByName("metrics_file", $this->t("Asset headers not matched."));
      }
    }
    \Drupal::messenger()->addStatus("Metrics data imported successfully.");
  }

  /**
   * Utility: find term by name and vid.
   */
  protected function getTidByName($name = NULL, $vid = NULL) {
    $properties = [];
    if (!empty($name)) {
      $properties['name'] = $name;
    }
    if (!empty($vid)) {
      $properties['vid'] = $vid;
    }
    $terms = \Drupal::entityTypeManager()
      ->getStorage('taxonomy_term')
      ->loadByProperties($properties);
    $term = reset($terms);
    return !empty($term) ? $term->id() : 0;
  }

}
