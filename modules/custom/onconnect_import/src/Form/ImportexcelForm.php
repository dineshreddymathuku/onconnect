<?php

namespace Drupal\onconnect_import\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use PhpOffice\PhpSpreadsheet\IOFactory;

/**
 * Provides the form for adding countries.
 */
class ImportexcelForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'onconnect_content_import_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $form = [
      '#attributes' => ['enctype' => 'multipart/form-data'],
    ];

    $form['file_upload_details'] = [
      '#markup' => t('<b>The File</b>'),
    ];
    $extensions = 'xlsx xls';
    $validators = [
      'file_validate_extensions' => [$extensions],
    ];
    $form['excel_file'] = [
      '#type' => 'managed_file',
      '#name' => 'excel_file',
      '#title' => t('File *'),
      '#size' => 20,
      '#description' => t('Excel format only'),
      '#upload_validators' => $validators,
      '#upload_location' => 'public://content/excel_files/',
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
    if ($form_state->getValue('excel_file') == NULL) {
      $form_state->setErrorByName('excel_file', $this->t('upload proper File.'));
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

    $file = \Drupal::entityTypeManager()->getStorage('file')
      ->load($form_state->getValue('excel_file')[0]);

    $full_path = $file->get('uri')->value;
    $file_name = basename($full_path);

    $inputFileName = \Drupal::service('file_system')->realpath('public://content/excel_files/' . $file_name);

    $spreadsheet = IOFactory::load($inputFileName);

    $sheetData = $spreadsheet->getActiveSheet();

    $rows = [];
    foreach ($sheetData->getRowIterator() as $row) {

      $cellIterator = $row->getCellIterator();
      $cellIterator->setIterateOnlyExistingCells(FALSE);
      $cells = [];
      foreach ($cellIterator as $cell) {
        $cells[] = $cell->getValue();
      }

      $rows[] = $cells;

    }

    array_shift($rows);
    $operations = [
            [
              '\Drupal\onconnect_import\ImportData::createPublicationNode',
              [$rows],
            ],
    ];
    $batch = [
      'title' => $this->t('Importing Publications ...'),
      'operations' => $operations,
      'finished' => '\Drupal\onconnect_import\ImportData::callbackFinishedImport',
    ];
    batch_set($batch);

  }

}
