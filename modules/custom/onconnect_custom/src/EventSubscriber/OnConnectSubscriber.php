<?php

namespace Drupal\onconnect_custom\EventSubscriber;

use Drupal\user\Entity\User;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * OnConnectSubscriber Class.
 */
class OnConnectSubscriber implements EventSubscriberInterface {

  /**
   * {@inheritdoc}
   */
  public function checkForRedirection(RequestEvent $event) {
    $currentuser = \Drupal::currentUser();
    if ($currentuser->isAuthenticated()) {
      $lastViewedLink = isset($_COOKIE['latestHref']) ? $_COOKIE['latestHref'] : '';
      $lastViewedtitle = isset($_COOKIE['latestTitle']) ? $_COOKIE['latestTitle'] : '';
      if ($lastViewedLink && $lastViewedtitle) {
        $userLoad = User::load($currentuser->id());
        $userLoad->field_last_viewed->uri = "internal:" . $lastViewedLink;
        $userLoad->field_last_viewed->title = $lastViewedtitle;
        $userLoad->save();
        setcookie("latestHref", NULL, time() - 3600, "/");
        setcookie("latestTitle", NULL, time() - 3600, "/");
      }
    }
  }

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    $events[KernelEvents::REQUEST][] = ['checkForRedirection'];
    return $events;
  }

}
