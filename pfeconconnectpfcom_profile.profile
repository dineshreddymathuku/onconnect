<?php

/**
 * @file
 * Configuration for pfeconconnectpfcom Site installation.
 */

use Drupal\user\Entity\User;
use Drupal\user\UserInterface;
use Drupal\Core\Messenger\MessengerInterface;

/**
 * Implements hook_install_tasks().
 */
function pfeconconnectpfcom_profile_install_tasks($install_state) {
  $tasks = [
    'pfeconconnectpfcom_profile_setup_cleanup' => [
      'display_name' => t('Cleanup'),
      'display' => FALSE,
      'type' => 'normal',
    ],
    'pfeconconnectpfcom_profile_setup_translations' => [
      'display_name' => t('Translations import'),
      'type' => 'batch',
    ],
  ];

  return $tasks;
}

/**
 * Post profile install function.
 *
 * This will set the site email and the email and name fileds
 * for user 1.  This is done here, because it will run after installation.
 * If the site is installed with Drush, hardcoded values for these fields
 * in the drush site-install command will override anything in the .install
 * file.  When added here, it will update the fields to the correct values.
 */
function pfeconconnectpfcom_profile_setup_cleanup() {
  $email = 'noreply@pfizer.com';

  // Only set system site settings on initial install if a sync config exists.
  if (!file_exists(DRUPAL_ROOT . '/profiles/pfeconconnectpfcom_profile/config/sync/system.site.yml')) {
    \Drupal::configFactory()->getEditable('system.site')
      ->set('page.front', '/node')
      ->set('mail', $email)
      ->set('name', 'pfeconconnectpfcom')
      ->save(TRUE);
  }

  // Set user-name and email for user 1.
  $user = User::load(1);
  $user->set('name', 'pfizer-admin');
  $user->set('mail', $email);
  $user->save();
  \Drupal::messenger()->addMessage(t('User 1 was renamed to pfizer-admin.'));

  // Only allow administrators create accounts.
  $user_settings = \Drupal::configFactory()->getEditable('user.settings');
  $user_settings->set('register', UserInterface::REGISTER_ADMINISTRATORS_ONLY)->save(TRUE);

  // Enable the admin theme.
  \Drupal::configFactory()->getEditable('node.settings')->set('use_admin_theme', TRUE)->save(TRUE);

}

/**
 * Installation task to import translations.
 */
function pfeconconnectpfcom_profile_setup_translations() {
  $moduleHandler = \Drupal::service('module_handler');
  if (!$moduleHandler->moduleExists('locale')) {
    return;
  }

  $options = _locale_translation_default_update_options();
  $batch = locale_translation_batch_update_build(['pfeconconnectpfcom_profile'], [], $options);

  return $batch;
}
