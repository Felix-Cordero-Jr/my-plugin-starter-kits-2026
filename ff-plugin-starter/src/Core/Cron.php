<?php
namespace FFPS\Core;

if ( ! defined('ABSPATH') ) exit;

final class Cron {

  public function init(): void {
    add_action('ffps_cron_hourly', [$this, 'run_hourly']);
  }

  public function run_hourly(): void {
    $opts = get_option('ffps_options', []);
    if ( empty($opts['enabled']) ) return;

    Logger::info('Cron ran hourly.', [
      'time' => current_time('mysql'),
      'site' => home_url(),
    ]);

    // Do your hourly job here...
  }
}