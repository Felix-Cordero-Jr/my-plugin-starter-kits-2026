<?php
if ( ! defined('WP_UNINSTALL_PLUGIN') ) exit;

delete_option('ffps_options');
delete_option('ffps_logs');

// If you add custom tables later, drop them here carefully.