<?php
/* Prevent direct access to this file */
if (!defined('ABSPATH')) {
	exit;
}

/* WP admin dashboard widget class */
class Svea_Dwnlds_Admin {
	/* Adding admin dashboard widget */
	private $api;
	public function __construct($api) {
		$this->api = $api;
		add_action('wp_dashboard_setup', array( $this, 'add_dashboard_widget'));
	}

	/* Add widget to WP dashboard */
	public function add_dashboard_widget() {
		wp_add_dashboard_widget(
			'svea_downloads_widget',
			'SVEA-checkout-downloads',
			array( $this, 'display_dashboard_widget')
		);
	}

	/* Display admin widget */
	public function display_dashboard_widget() {
		$downloads = $this->api->get_downloads();
		if (is_wp_error($downloads)) {
			echo '<p>';
			echo esc_html($downloads->get_error_message());
			echo '</p>';
		} else {
			echo '<p>All time downloads: ';
			echo esc_html(number_format($downloads, 0, ',', ' '));
			echo '</p>';
		}
	}
}
