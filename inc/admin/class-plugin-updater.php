<?php
/**
 * Plugin Updater
 *
 * @package Holler_Elementor
 * @subpackage Admin
 * @since 1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Holler Plugin Updater Class
 *
 * Handles the plugin updates from GitHub repository
 *
 * @since 1.0.0
 */
class Holler_Plugin_Updater {

    /**
     * Update checker instance
     *
     * @var object
     */
    private $update_checker;

    /**
     * Constructor
     *
     * @param string $repository GitHub repository URL.
     * @param string $main_file   Main plugin file path.
     * @param string $slug        Plugin slug.
     * @param string $branch      GitHub branch to use for updates.
     */
    public function __construct( $repository, $main_file, $slug, $branch = 'master' ) {
        // Make sure the plugin update checker is loaded
        require_once HOLLER_ELEMENTOR_DIR . 'inc/plugin-update-checker/plugin-update-checker.php';
        
        // Initialize the update checker
        $this->update_checker = Puc_v4_Factory::buildUpdateChecker(
            $repository,
            $main_file,
            $slug
        );
        
        // Set the branch that contains the stable release
        $this->update_checker->setBranch( $branch );
        $this->update_checker->getVcsApi()->enableReleaseAssets();
    }

    /**
     * Set authentication for private repositories
     *
     * @param string $token GitHub access token.
     */
    public function set_authentication( $token ) {
        if ( ! empty( $token ) ) {
            $this->update_checker->setAuthentication( $token );
        }
    }
}
