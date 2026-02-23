<?php
namespace FFPS\Rest;

use WP_REST_Request;
use FFPS\Core\Logger;

if ( ! defined('ABSPATH') ) exit;

final class PingRoute {

  public function init(): void {
    add_action('rest_api_init', [$this, 'register']);
  }

  public function register(): void {
    register_rest_route('ffps/v1', '/ping', [
      'methods'  => 'GET',
      'callback' => [$this, 'handle'],
      'permission_callback' => function(){
        // Change to "return true" for public endpoints.
        return current_user_can('manage_options');
      }
    ]);
  }

  public function handle(WP_REST_Request $req) {
    Logger::info('REST ping called.', ['user' => get_current_user_id()]);
    return rest_ensure_response([
      'ok' => true,
      'time' => current_time('mysql'),
      'version' => FFPS_VERSION,
    ]);
  }
}