<?php
/* Prevent direct access to this file */
if (!defined('ABSPATH')) {
	exit;
}

class Svea_Dwnlds_Api {
	/* Get download stats for SVEA-checkout plugin */
	public function get_downloads() {
		/* Check if cache is valid */
		$cached_downloads = get_transient('svea_downloads');
		if (false !== $cached_downloads) {
			return $cached_downloads;
		}

		/* If no cache, connect to Wordpress.org API */
		$slug = 'svea-checkout-for-woocommerce';
		$url = 'https://api.wordpress.org/stats/plugin/1.0/downloads.php?slug=' . $slug . '&historical_summary=1';

		/* Set timeout, 10 seconds, in case of Wordpress.org is not responding */
		$response = wp_remote_get(
			$url,
			array(
				'timeout' => 10,
			)
		);

		/* If error, return error message */
		if (is_wp_error($response)) {
			return $response;
		}

		/* Get response and decode json */
		$body      = wp_remote_retrieve_body($response);
		$downloads = json_decode($body, true);

		/* Check if HTTP response status is ok */
		$status_code = wp_remote_retrieve_response_code($response);
		if (200 !== $status_code) {
			return new WP_Error(
				'svea_api_http_error',
				'Unable to get statistics from Wordpress.org.'
			);
		}

		/* Check if the API response contains the expected data */
		if (! is_array($downloads) || ! isset($downloads['all_time'])) {
			return new WP_Error(
				'svea_invalid_api_response',
				'Invalid response from Wordpress.org.'
			);
		}

		/* Convert all_time to integer and save to cache for one hour */
		$downloads = (int) $downloads['all_time'];
		set_transient(
			'svea_downloads',
			$downloads,
			HOUR_IN_SECONDS
		);

		/* Return result to Svea_Dwnlds_Admin class */
		return $downloads;
	}
}
