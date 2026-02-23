<?php
namespace FFPS\Admin;

if ( ! defined('ABSPATH') ) exit;

final class Menu {

  const SLUG = 'ffps';

  public function init(): void {
    add_action('admin_menu', [$this, 'register_menu']);
  }

  public function register_menu(): void {
    // Capability: manage_options (Admins)
    add_menu_page(
      'FF Plugin Starter Kit',
      'FFPS',
      'manage_options',
      self::SLUG,
      [$this, 'render_page'],
      'dashicons-admin-generic',
      58
    );
  }

  public function render_page(): void {
    if ( ! current_user_can('manage_options') ) {
      wp_die('You do not have permission to access this page.');
    }

    // ✅ Use fully-qualified class name so no "use" import mistakes.
    if ( class_exists('\FFPS\Core\Logger') ) {
      \FFPS\Core\Logger::log('Admin page loaded', [
        'user_id' => get_current_user_id(),
        'page'    => self::SLUG,
      ]);
    }

    echo '<div class="wrap">';
    echo '<h1>FF Plugin Starter Kit</h1>';
    echo '<p>Your admin menu is working. 🎉</p>';
    echo '</div>';
  }
}