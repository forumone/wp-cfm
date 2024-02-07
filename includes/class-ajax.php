<?php

class WPCFM_Ajax {


	function __construct() {
		add_action( 'wp_ajax_wpcfm_load', array( $this, 'load_settings' ) );
		add_action( 'wp_ajax_wpcfm_save', array( $this, 'save_settings' ) );
		add_action( 'wp_ajax_wpcfm_push', array( $this, 'push_settings' ) );
		add_action( 'wp_ajax_wpcfm_pull', array( $this, 'pull_settings' ) );
		add_action( 'wp_ajax_wpcfm_diff', array( $this, 'load_diff' ) );
	}


	/**
	 * Load admin settings
	 */
	public function load_settings() {
		if ( current_user_can( 'manage_options' ) && check_ajax_referer( 'wpcfm_ajax_nonce' ) ) {
			$bundles = WPCFM()->helper->get_bundles();
			wp_send_json(
				array(
					'success' => true,
					'data' => array(
						'bundles' => $bundles,
					),
				)
			);
		}

		wp_send_json_error( array( 'message' => __( 'Unauthorized request!', 'wp-cfm' ) ), 403 );
	}


	/**
	 * Save admin settings
	 */
	public function save_settings() {
		if ( current_user_can( 'manage_options' ) && check_ajax_referer( 'wpcfm_ajax_nonce' ) ) {
			$settings = stripslashes( $_POST['data'] );

			// Save the option
			WPCFM()->options->update( 'wpcfm_settings', $settings );

			// Delete orphan bundles
			$file_bundles = array_keys( WPCFM()->helper->get_file_bundles() );
			$new_bundles = WPCFM()->helper->get_bundles();

			foreach ( $file_bundles as $bundle_name ) {
				if ( ! isset( $new_bundles[ $bundle_name ] ) || false === $new_bundles[ $bundle_name ]['is_db'] ) {
					WPCFM()->readwrite->delete_file( $bundle_name );
				}
			}

			wp_send_json(
				array(
					'success' => true,
					'data' => array( 'message' => __( 'Settings saved', 'wp-cfm' ) ),
				)
			);
		}

		wp_send_json_error( array( 'message' => __( 'Unauthorized request!', 'wp-cfm' ) ), 403 );
	}


	public function load_diff() {
		if ( current_user_can( 'manage_options' ) && check_ajax_referer( 'wpcfm_ajax_nonce' ) ) {
			$bundle_name = stripslashes( $_POST['data']['bundle_name'] );
			$comparison = WPCFM()->readwrite->compare_bundle( $bundle_name );

			// The pretty-text-diff.js will do its best on these print_r()s.
			if ( isset( $comparison['file'] ) ) {
				$comparison['file'] = print_r( $comparison['file'], true );
			}
			if ( isset( $comparison['db'] ) ) {
				$comparison['db'] = print_r( $comparison['db'], true );
			}

			wp_send_json(
				array(
					'success' => true,
					'data' => $comparison,
				)
			);
		}

		wp_send_json_error( array( 'message' => __( 'Unauthorized request!', 'wp-cfm' ) ), 403 );
	}


	/**
	 * Push settings to filesystem
	 */
	public function push_settings() {
		if ( current_user_can( 'manage_options' ) && check_ajax_referer( 'wpcfm_ajax_nonce' ) ) {
			$bundle_name = stripslashes( $_POST['data']['bundle_name'] );
			WPCFM()->readwrite->push_bundle( $bundle_name );
			wp_send_json(
				array(
					'success' => true,
					'data' => array( 'message' => __( 'Push successful', 'wp-cfm' ) ),
				)
			);
		}

		wp_send_json_error( array( 'success' => false ), 403 );
	}


	/**
	 * Pull settings into DB
	 */
	public function pull_settings() {
		if ( current_user_can( 'manage_options' ) && check_ajax_referer( 'wpcfm_ajax_nonce' ) ) {
			$bundle_name = stripslashes( $_POST['data']['bundle_name'] );
			WPCFM()->readwrite->pull_bundle( $bundle_name );
			wp_send_json(
				array(
					'success' => true,
					'data' => array( 'message' => __( 'Pull successful', 'wp-cfm' ) ),
				)
			);
		}

		wp_send_json_error( array( 'message' => __( 'Unauthorized request!', 'wp-cfm' ) ), 403 );
	}
}
