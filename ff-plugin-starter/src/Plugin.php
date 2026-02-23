<?php
namespace FFPS;

if ( ! defined('ABSPATH') ) exit;

final class Plugin {

  public function init(): void {

    // Admin-only boot
    if ( is_admin() ) {
      $menu = new \FFPS\Admin\Menu();
      $menu->init();
    }

  }
}