<?php
namespace FFPS\Front;

if ( ! defined('ABSPATH') ) exit;

final class Assets {

  public function init(): void {
    add_action('wp_enqueue_scripts', [$this, 'enqueue']);
  }

  public function enqueue(): void {
    // Only load when needed if you add a shortcode/template later.
    wp_enqueue_style('ffps-front', FFPS_URL . 'assets/front.css', [], FFPS_VERSION);
    wp_enqueue_script('ffps-front', FFPS_URL . 'assets/front.js', [], FFPS_VERSION, true);
  }
}