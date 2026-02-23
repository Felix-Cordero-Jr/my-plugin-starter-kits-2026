<?php
namespace FFPS\Core;

if ( ! defined('ABSPATH') ) exit;

final class Logger {

  /**
   * Turn logging ON/OFF via option.
   * Default true (dev-friendly).
   * You can set it to false later in production.
   */
  const OPTION_KEY = 'ffps_debug';

  public static function enabled(): bool {
    return (bool) get_option(self::OPTION_KEY, true);
  }

  /**
   * Safe logger (writes to wp-content/debug.log if WP_DEBUG_LOG is enabled)
   */
  public static function log($message, array $context = []): void {
    if ( ! self::enabled() ) return;

    if ( is_array($message) || is_object($message) ) {
      $message = wp_json_encode($message);
    }

    if ( ! empty($context) ) {
      $message .= ' | ' . wp_json_encode($context);
    }

    error_log('[FFPS] ' . $message);
  }
}