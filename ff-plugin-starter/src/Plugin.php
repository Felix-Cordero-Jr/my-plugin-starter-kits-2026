<?php
namespace FFPS;

if ( ! defined('ABSPATH') ) exit;

final class Plugin {

  public function init(): void {

    // Admin
    if ( is_admin() ) {
      $menu = new \FFPS\Admin\Menu();
      $menu->init();
    }

    // Frontend (Login portal)
    $login = new \FFPS\Front\Login_Shortcode();
    $login->init();
  }
}