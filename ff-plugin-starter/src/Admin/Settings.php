<?php
namespace FFPS\Admin;

if ( ! defined('ABSPATH') ) exit;

final class Settings {

  private const OPTION = 'ffps_options';

  public function init(): void {
    add_action('admin_init', [$this, 'register_settings']);
    add_action('admin_menu', [$this, 'add_settings_submenu']);
  }

  public function add_settings_submenu(): void {
    add_submenu_page(
      'ffps',
      'Settings',
      'Settings',
      'manage_options',
      'ffps-settings',
      [$this, 'render']
    );
  }

  public function register_settings(): void {
    register_setting('ffps_settings_group', self::OPTION, [
      'type' => 'array',
      'sanitize_callback' => [$this, 'sanitize'],
      'default' => [
        'enabled' => 1,
        'api_key' => '',
      ],
    ]);

    add_settings_section('ffps_main', 'Main Settings', function(){
      echo '<p>Basic options for this plugin.</p>';
    }, 'ffps-settings');

    add_settings_field('enabled', 'Enabled', [$this, 'field_enabled'], 'ffps-settings', 'ffps_main');
    add_settings_field('api_key', 'API Key', [$this, 'field_api_key'], 'ffps-settings', 'ffps_main');
  }

  public function sanitize($input): array {
    $out = [];
    $out['enabled'] = ! empty($input['enabled']) ? 1 : 0;
    $out['api_key'] = isset($input['api_key']) ? sanitize_text_field($input['api_key']) : '';
    return $out;
  }

  public function field_enabled(): void {
    $opts = get_option(self::OPTION, []);
    $val = ! empty($opts['enabled']) ? 1 : 0;
    echo '<label><input type="checkbox" name="' . esc_attr(self::OPTION) . '[enabled]" value="1" ' . checked(1, $val, false) . '> Enable plugin features</label>';
  }

  public function field_api_key(): void {
    $opts = get_option(self::OPTION, []);
    $val = isset($opts['api_key']) ? (string)$opts['api_key'] : '';
    echo '<input type="text" class="regular-text" name="' . esc_attr(self::OPTION) . '[api_key]" value="' . esc_attr($val) . '" autocomplete="off">';
    echo '<p class="description">Example: used for external API calls.</p>';
  }

  public function render(): void {
    if ( ! current_user_can('manage_options') ) return;

    echo '<div class="wrap">';
    echo '<h1>FF Plugin Settings</h1>';
    echo '<form method="post" action="options.php">';
    settings_fields('ffps_settings_group');
    do_settings_sections('ffps-settings');
    submit_button();
    echo '</form>';
    echo '</div>';
  }
}