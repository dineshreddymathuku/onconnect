<?php

namespace Drupal\onconnect_import\Form;

use Drupal\Core\Form\ConfirmFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\node\Entity\Node;

/**
 * Delete nodes.
 */
class DeleteNodesForm extends ConfirmFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'onconnect_content_delete_nodes';
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $nids = \Drupal::entityQuery('node')
      ->condition('type', 'publication')
      ->accessCheck(FALSE)
      ->execute();
    $batch = [
      'title' => t('Deleting publication nodes...'),
      'init_message' => t('Deleting'),
      'error_message' => t('An error occurred during processing'),
      'finished' => '\Drupal\onconnect_import\Form\DeleteNodesForm::deleteNodesFinishedCallback',
    ];
    $count = count($nids);
    $i = 1;
    foreach ($nids as $nid) {
      $batch['operations'][] = [
        '\Drupal\onconnect_import\Form\DeleteNodesForm::batchDelete',
        [$nid, $count, $i],
      ];
      $i++;
    }
    batch_set($batch);
  }

  /**
   * {@inheritdoc}
   */
  public function getQuestion() {
    return $this->t('Are you sure you want to delete all publication nodes?');
  }

  /**
   * {@inheritdoc}
   */
  public function getConfirmText() {
    return $this->t('Delete');
  }

  /**
   * {@inheritdoc}
   */
  public function getCancelUrl() {
    return new Url('onconnect_import.delete_nodes');
  }

  /**
   * Implement the operation method.
   */
  public static function batchDelete($nid, $count, $current, &$context) {
    $node = Node::load($nid);
    if ($node) {
      $message = t("Deleting @label", [
        '@label' => $node->label(),
      ]);
      $node->delete();
    }
    $context['message'] = $message;
    $context['results'] = $count;
  }

  /**
   * {@inheritdoc}
   */
  public static function deleteNodesFinishedCallback($success, $results, $operations) {
    if ($success) {
      $message = t('@count publications has been deleted successfully.', ['@count' => $results]);
    }
    else {
      $message = t('Finished with an error.');
    }
    \Drupal::messenger()->addStatus($message);
  }

}
