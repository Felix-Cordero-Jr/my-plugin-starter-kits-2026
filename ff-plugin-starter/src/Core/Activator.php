<?php
namespace FFPS\Core;

if ( ! defined('ABSPATH') ) exit;

final class Activator {
  public static function activate(): void {
    // Default option(s)
    if ( get_option('ffps_options', null) === null ) {
      add_option('ffps_options', [
        'enabled' => 1,
        'api_key' => '',
      ]);
    }

    // Schedule cron if not scheduled
    if ( ! wp_next_scheduled('ffps_cron_hourly') ) {
      wp_schedule_event(time() + 60, 'hourly', 'ffps_cron_hourly');
    }
  }
}