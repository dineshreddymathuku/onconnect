<?php

namespace Drupal\onconnect_import;

use Drupal\node\Entity\Node;

/**
 * Delete Node.
 */
class DeleteNodes {

  /**
   * {@inheritdoc}
   */
  public function __construct($nid, $count, $current, &$context) {

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
