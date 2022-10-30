<?php

namespace Drupal\onconnect_custom\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\user\Entity\User;

/**
 * Provides a block for Last Viewed.
 *
 * @Block(
 *   id = "onconnect_user_last_view",
 *   admin_label = @Translation("Last Viewed"),
 *   category = "Oncology"
 * )
 */
class UserLastView extends BlockBase {

  /**
   * Function.
   */
  public function getCacheMaxAge() {
    return 0;
  }

  /**
   * Function.
   */
  public function build() {
    $currentUser = \Drupal::currentUser();
    $userLoad = User::load($currentUser->id());
    if ($userLoad && $currentUser->isAuthenticated()) {
      $lastViewedLink = "internal:" . $userLoad->get("field_last_viewed")->uri;
      $lastViewedTitle = $userLoad->get("field_last_viewed")->title;
      return [
        '#markup' => "<div class='last-viewed'><a href='$lastViewedLink'>$lastViewedTitle</a></div>",
        '#cache' => ['max-age' => 0],
      ];
    }
    return [
      '#cache' => ['max-age' => 0],
    ];
  }

}
