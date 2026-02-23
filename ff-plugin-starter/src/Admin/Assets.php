<?php
namespace FFPS\Admin;

if ( ! defined('ABSPATH') ) exit;

final class Assets {

  public function init(): void {
    add_action('admin_enqueue_scripts', [$this, 'enqueue']);
  }

  public function enqueue(string $hook): void {
    if ( strpos($hook, 'ffps') === false ) return;

    wp_enqueue_style('ffps-admin', FFPS_URL . 'assets/admin.css', [], FFPS_VERSION);
    wp_enqueue_script('ffps-admin', FFPS_URL . 'assets/admin.js', ['jquery'], FFPS_VERSION, true);
  }
}