<?php
/**
 * Plugin Name: FF Plugin Starter Kit
 * Description: Reusable WordPress plugin starter kit (OOP + Settings + REST + Cron + Logger).
 * Version: 0.1.0
 * Author: Felix Cordero Jr.
 * Requires at least: 6.0
 * Requires PHP: 7.4
 */

if ( ! defined('ABSPATH') ) exit;

define('FFPS_VERSION', '0.1.0');
define('FFPS_FILE', __FILE__);
define('FFPS_DIR', plugin_dir_path(__FILE__));
define('FFPS_URL', plugin_dir_url(__FILE__));
define('FFPS_BASENAME', plugin_basename(__FILE__));

// Simple PSR-4-ish autoloader for the "FFPS\" namespace.
spl_autoload_register(function($class){
  $prefix = 'FFPS\\';
  if ( strpos($class, $prefix) !== 0 ) return;

  $relative = substr($class, strlen($prefix));
  $relative = str_replace('\\', DIRECTORY_SEPARATOR, $relative);
  $path = FFPS_DIR . 'src/' . $relative . '.php';

  if ( file_exists($path) ) require_once $path;
});

register_activation_hook(FFPS_FILE, ['FFPS\\Core\\Activator', 'activate']);
register_deactivation_hook(FFPS_FILE, ['FFPS\\Core\\Deactivator', 'deactivate']);

add_action('plugins_loaded', function(){
  // Load text domain if you later add /languages
  // load_plugin_textdomain('ffps', false, dirname(FFPS_BASENAME) . '/languages');

  $plugin = new FFPS\Plugin();
  $plugin->init();
});