<?php

namespace Drupal\onconnect_top_banner\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\user\Entity\User;

/**
 * Provides a block for Latest publications.
 *
 * @Block(
 *   id = "top_banner",
 *   admin_label = @Translation("Top Banner"),
 *   category = "Oncology"
 * )
 */
class TopBanner extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    // Load the current user.
    $user = User::load(\Drupal::currentUser()->id());
    // Retrieve field data from that user.
    $name = $user->get('name')->value;
    if ($name) {
      $name = ', ' . $name;
    }
    // Set greeting message according to the time.
    $numeric_date = date("G");
    if ($numeric_date >= 0 && $numeric_date <= 11) {
      $message = 'Good morning' . $name;
    }
    elseif ($numeric_date >= 12 && $numeric_date <= 16) {
      $message = 'Good afternoon' . $name;
    }
    elseif ($numeric_date >= 17 && $numeric_date <= 23) {
      $message = 'Good evening' . $name;
    }
    return [
      '#theme' => 'top_banner',
      '#message' => $message,
      '#cache' => ['max-age' => 0],
    ];
  }

}
