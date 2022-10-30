<?php

namespace Drupal\onconnect_import\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\node\Entity\Node;

/**
 * Publish nodes.
 */
class PublishNodesForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'onconnect_content_publish_nodes';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['nodes_start'] = [
      '#type' => 'textfield',
      '#required' => TRUE,
      '#title' => t('Node ID (Start)'),
    ];
    $form['nodes_end'] = [
      '#type' => 'textfield',
      '#required' => TRUE,
      '#title' => t('Node ID (End)'),
    ];
    $form['actions']['#type'] = 'actions';
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Publish'),
      '#button_type' => 'primary',
    ];
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $values = $form_state->getValues();
    $nodes_start = $values['nodes_start'];
    $nodes_end = $values['nodes_end'];
    for ($i = $nodes_start; $i <= $nodes_end; $i++) {
      $node = Node::load($i);
      if ($node) {
        // $moderationState = $node->get('moderation_state')->value;
        // if ($moderationState != "published") {
        $node->set('moderation_state', 'needs_review');
        $node->save();
        $node->set('moderation_state', 'published');
        $node->save();
        // }
      }
    }
  }

}
