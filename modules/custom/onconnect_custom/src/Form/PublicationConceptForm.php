<?php

namespace Drupal\onconnect_custom\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Configure PublicationConceptForm for this site.
 */
class PublicationConceptForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'onconnect_custom_publication_concept';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    /*$form['proposal_type'] = [
    '#type' => 'radios',
    '#title' => t('Proposal Type'),
    //'#title_display' => 'invisible',
    '#options' => [
    0=>('Congress and Manuscript'),
    1=>('Manuscript only'),
    2=>('Congress only')
    ],
    '#default_value' => 0,
    '#required' => false,
    ];*/
    $form['spc_product'] = [
      '#type' => 'select',
      '#title' => t('SPC & Product'),
      '#options' => [
        t('select'),
        t('Congress and Manuscript'),
        t('Manuscript only'),
        t('Congress only'),
      ],
      '#default_value' => 'Congress and Manuscript',
      '#required' => FALSE,
      '#label_classes' => [
        'publication-form-label',
      ],
    ];
    $form['level_spc_support'] = [
      '#type' => 'select',
      '#title' => t('Level of SPC Support'),
      '#options' => [
        t('select'),
        t('Congress and Manuscript'),
        t('Manuscript only'),
        t('Congress only'),
      ],
      '#default_value' => 'Congress and Manuscript',
      '#required' => FALSE,
      '#label_classes' => [
        'publication-form-label',
      ],
    ];
    $form['indication'] = [
      '#type' => 'select',
      '#title' => t('Indication'),
      '#options' => [
        t('select'),
        t('Congress and Manuscript'),
        t('Manuscript only'),
        t('Congress only'),
      ],
      '#default_value' => 'Congress and Manuscript',
      '#required' => FALSE,
      '#label_classes' => [
        'publication-form-label',
      ],
    ];
    $form['publication_type'] = [
      '#type' => 'select',
      '#title' => t('Publication type'),
      '#options' => [
        t('select'),
        t('Congress and Manuscript'),
        t('Manuscript only'),
        t('Congress only'),
      ],
      '#default_value' => t('Congress and Manuscript'),
      '#required' => FALSE,
      '#label_classes' => [
        'publication-form-label',
      ],
    ];
    $form['pfizer_publication_owner'] = [
      '#type' => 'dropdown',
      '#title' => t('Pfizer Publication Owner'),
      '#options' => [
        t('select'),
        t('Congress and Manuscript'),
        t('Manuscript only'),
        t('Congress only'),
      ],
      '#default_value' => 'Congress and Manuscript',
      '#required' => FALSE,
      '#label_classes' => [
        'publication-form-label',
      ],
    ];
    $form['title_request'] = [
      '#type' => 'textfield',
      '#title' => t('Title of Request'),
      '#default_value' => '',
      '#required' => FALSE,
      '#label_classes' => [
        'publication-form-label',
      ],

    ];
    $form['pfizer_publication_owner'] = [
      '#type' => 'textfield',
      '#title' => t('pfizer publication owner'),
      '#default_value' => '',
      '#required' => FALSE,
      '#label_classes' => [
        'publication-form-label',
      ],

    ];
    $form['description'] = [
      '#type' => 'textfield',
      '#title' => t('Description'),
      '#default_value' => '',
      '#required' => FALSE,
      '#label_classes' => [
        'publication-form-label',
      ],
    ];
    $form['int_author'] = [
      '#type' => 'textfield',
      '#title' => t('Internal author(s)'),
      '#default_value' => '',
      '#required' => FALSE,
      '#label_classes' => [
        'publication-form-label',
      ],
    ];
    $form['ext_author'] = [
      '#type' => 'textfield',
      '#title' => t('External author(s)'),
      '#default_value' => '',
      '#required' => FALSE,
      '#label_classes' => [
        'publication-form-label',
      ],
    ];
    $form['comments'] = [
      '#type' => 'textarea',
      '#title' => t('Comments / requests (optional)'),
      '#default_value' => '',
      '#required' => FALSE,
      '#label_classes' => [
        'publication-form-label',
      ],
    ];
    $form['attachments'] = [
      '#type' => 'file',
      '#title' => t('Attachments (optional)'),
      '#default_value' => '',
      '#required' => FALSE,
      '#label_classes' => [
        'publication-form-label',
      ],
    ];
    $form['save'] = [
      '#type' => 'submit',
      '#value' => t('Save'),
    ];
    $form['cancel'] = [
      '#type' => 'button',
      '#value' => 'Cancel',
      '#class' => 'form-cancel',
      // '#attributes' => ['onclick' => 'this.form.target="/home"; return true;'],
      '#attributes' => ['onclick' => "location.href = '/home'; return true;"],
    ];
    $form['submit'] = [
      '#type' => 'submit',
      '#value' => t('Submit'),
    ];
    $form['#theme'] = 'publication_concept_form';
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $values = $form_state->getValues();
  }

}
