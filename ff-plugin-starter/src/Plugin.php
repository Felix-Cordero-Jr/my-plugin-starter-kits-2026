<?php
namespace FFPS;

use FFPS\Admin\Menu;
use FFPS\Admin\Settings;
use FFPS\Admin\Assets as AdminAssets;
use FFPS\Front\Assets as FrontAssets;
use FFPS\Rest\PingRoute;
use FFPS\Core\Cron;

if ( ! defined('ABSPATH') ) exit;

final class Plugin {

  public function init(): void {
    // Core
    (new Cron())->init();

    // REST
    (new PingRoute())->init();

    // Admin-only
    if ( is_admin() ) {
      (new Menu())->init();
      (new Settings())->init();
      (new AdminAssets())->init();
    }

    // Front-end
    (new FrontAssets())->init();
  }
}