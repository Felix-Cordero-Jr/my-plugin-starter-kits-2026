<?php
namespace FFPS\Core;

if ( ! defined('ABSPATH') ) exit;

final class Deactivator {
  public static function deactivate(): void {
    // Unschedule cron
    $timestamp = wp_next_scheduled('ffps_cron_hourly');
    if ( $timestamp ) {
      wp_unschedule_event($timestamp, 'ffps_cron_hourly');
    }
  }
}