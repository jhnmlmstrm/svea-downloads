<?php
/* Prevent direct access to this file */
if (!defined('ABSPATH')) {
	exit;
}

/* Main plugin class */
class Svea_Dwnlds {
	public function __construct() {
		$plugin_api = new Svea_Dwnlds_Api();
		new Svea_Dwnlds_Admin($plugin_api);
	}
}
