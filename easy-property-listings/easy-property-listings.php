<?php
/**
 * Plugin Name: Easy Property Listings
 * Plugin URI: https://www.easypropertylistings.com.au/
 * Description:  Fast. Flexible. Forward-thinking solution for real estate agents using WordPress. Easy Property Listing is one of the most dynamic and feature rich Real Estate plugin for WordPress available on the market today. Built for scale, contact generation and works with any theme!
 * Author: Merv Barrett
 * Author URI: https://www.realestateconnected.com.au/
 * Version: 3.5.15
 * Text Domain: easy-property-listings
 * Domain Path: languages
 *
 * Easy Property Listings is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * any later version.
 *
 * Easy Property Listings is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Easy Property Listings. If not, see <http://www.gnu.org/licenses/>.
 *
 * @package EPL
 * @category Core
 * @author Merv Barrett
 * @version 3.5.10
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Easy_Property_Listings' ) ) :
	/**
	 * Main Easy_Property_Listings Class
	 *
	 * @since 1.0.0
	 */
	final class Easy_Property_Listings {
		/**
		 * Instance
		 *
		 * @var Easy_Property_Listings The one true Easy_Property_Listings
		 * @since 1.0.0
		 */
		private static $instance;

		/**
		 * EPL search fields display object.
		 *
		 * @since 3.0.0
		 * @var   EPL_Search_Fields
		 */
		public $search_fields;

		/**
		 * EPL session.
		 *
		 * @since 3.0.0
		 * @var   EPL_Session
		 */
		public $session;

		/**
		 * Render Fields.
		 *
		 * @since 3.0.0
		 * @var   EPL_Render_Fields
		 */
		public $render_fields;


		/**
		 * Main Easy_Property_Listings Instance
		 *
		 * Insures that only one instance of Easy_Property_Listings exists in memory at any one time.
		 * Also prevents needing to define globals all over the place.
		 *
		 * @return Easy_Property_Listings one true Easy_Property_Listings
		 * @uses Easy_Property_Listings::includes() Include the required files
		 * @see  EPL()
		 * @since 1.0.0
		 * @static
		 * @staticvar array $instance
		 */
		public static function instance() {
			if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Easy_Property_Listings ) ) {
				self::$instance = new Easy_Property_Listings();
				self::$instance->setup_constants();

				add_action( 'plugins_loaded', array( self::$instance, 'load_textdomain' ) );

				self::$instance->includes();
				// Search fields display object.
				self::$instance->search_fields = new EPL_Search_Fields();
				self::$instance->session       = new EPL_Session();
				self::$instance->render_fields = new EPL_Render_Fields();
				self::$instance->search_fields->init();

				define( 'EPL_RUNNING', true );
			}
			return self::$instance;
		}

		/**
		 * Setup plugin constants
		 *
		 * @access public
		 *
		 * @since 1.0.0
		 * @since 3.5.4 Tweak: Sanitisation check on global page constant.
		 *
		 * @return void
		 */
		public function setup_constants() {
			// Plugin version.
			if ( ! defined( 'EPL_PROPERTY_VER' ) ) {
				define( 'EPL_PROPERTY_VER', '3.5.15' );
			}
			// Plugin DB version.
			if ( ! defined( 'EPL_PROPERTY_DB_VER' ) ) {
				define( 'EPL_PROPERTY_DB_VER', '3.3' );
			}
			// Current Page.
			if ( ! defined( 'EPL_CURRENT_PAGE' ) ) {
				$php_self = ! empty( $_SERVER['PHP_SELF'] ) ? sanitize_text_field( wp_unslash( $_SERVER['PHP_SELF'] ) ) : '';
				define( 'EPL_CURRENT_PAGE', basename( sanitize_text_field( $php_self ) ) );
			}
			// Plugin Root File.
			if ( ! defined( 'EPL_PLUGIN_FILE' ) ) {
				define( 'EPL_PLUGIN_FILE', plugin_basename( __FILE__ ) );
			}
			// Plugin Folder URL.
			if ( ! defined( 'EPL_PLUGIN_URL' ) ) {
				define( 'EPL_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
			}
			// Plugin Folder Path.
			if ( ! defined( 'EPL_PLUGIN_PATH' ) ) {
				define( 'EPL_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
			}
			// Plugin Sub-Directory Paths.
			if ( ! defined( 'EPL_PATH_LIB' ) ) {
				define( 'EPL_PATH_LIB', EPL_PLUGIN_PATH . 'lib/' );
			}
			// Plugin Path Updates.
			if ( ! defined( 'EPL_PATH_UPDATES' ) ) {
				define( 'EPL_PATH_UPDATES', EPL_PATH_LIB . 'updates/' );
			}
			// Plugin Path Templates.
			if ( ! defined( 'EPL_PATH_TEMPLATES' ) ) {
				define( 'EPL_PATH_TEMPLATES', EPL_PATH_LIB . 'templates/' );
			}
			// Plugin Path Compatibility.
			if ( ! defined( 'EPL_COMPATABILITY' ) ) {
				define( 'EPL_COMPATABILITY', EPL_PATH_LIB . 'compatibility/' );
			}
			// Plugin Path Templates Content.
			if ( ! defined( 'EPL_PATH_TEMPLATES_CONTENT' ) ) {
				define( 'EPL_PATH_TEMPLATES_CONTENT', EPL_PATH_TEMPLATES . 'content/' );
			}
			// Plugin Path Templates Theme.
			if ( ! defined( 'EPL_PATH_TEMPLATES_POST_TYPES' ) ) {
				define( 'EPL_PATH_TEMPLATES_POST_TYPES', EPL_PATH_TEMPLATES . 'themes/' );
			}
			// Plugin Path Templates Default.
			if ( ! defined( 'EPL_PATH_TEMPLATES_POST_TYPES_DEFAULT' ) ) {
				define( 'EPL_PATH_TEMPLATES_POST_TYPES_DEFAULT', EPL_PATH_TEMPLATES_POST_TYPES . 'default/' );
			}
			// Plugin Path Templates iThemes Builder.
			if ( ! defined( 'EPL_PATH_TEMPLATES_POST_TYPES_ITHEMES' ) ) {
				define( 'EPL_PATH_TEMPLATES_POST_TYPES_ITHEMES', EPL_PATH_TEMPLATES_POST_TYPES . 'ithemes-builder/' );
			}
			// Plugin Path Templates Genesis.
			if ( ! defined( 'EPL_PATH_TEMPLATES_POST_TYPES_GENESIS' ) ) {
				define( 'EPL_PATH_TEMPLATES_POST_TYPES_GENESIS', EPL_PATH_TEMPLATES_POST_TYPES . 'genesis/' );
			}
			// Disable php sessions, use WP sessions.
			if ( ! defined( 'EPL_USE_PHP_SESSIONS' ) ) {
				define( 'EPL_USE_PHP_SESSIONS', false );
			}
		}

		/**
		 * Include required files.
		 *
		 * @access private
		 * @since 1.0.0
		 * @return void
		 */
		private function includes() {

			// WordPress core functions for keeping compatibility.
			require_once EPL_COMPATABILITY . 'wp-functions-compat.php';

			global $epl_settings;

			require_once EPL_PATH_LIB . 'includes/register-settings.php';
			$epl_settings = epl_get_settings();
			require_once EPL_PATH_LIB . 'includes/class-epl-session.php';
			require_once EPL_PATH_LIB . 'includes/actions.php';
			require_once EPL_PATH_LIB . 'includes/functions.php';
			require_once EPL_COMPATABILITY . 'functions-compat.php';
			require_once EPL_COMPATABILITY . 'extensions.php';
			require_once EPL_PATH_LIB . 'includes/options-global.php';
			require_once EPL_PATH_LIB . 'includes/formatting.php';

			require_once EPL_PATH_LIB . 'assets/assets.php';
			require_once EPL_PATH_LIB . 'assets/assets-svg.php';
			require_once EPL_PATH_LIB . 'includes/class-epl-cpt.php';
			require_once EPL_PATH_LIB . 'includes/class-epl-form-builder.php';
			require_once EPL_PATH_LIB . 'includes/class-epl-cron.php';

			// Activate post types based on settings.
			if ( isset( $epl_settings['activate_post_types'] ) ) {
				$epl_activated_post_types = $epl_settings['activate_post_types'];
			} else {
				$epl_activated_post_types = '';
			}

			if ( is_array( $epl_activated_post_types ) ) {
				foreach ( $epl_activated_post_types as $key => $value ) {
					switch ( $value ) {

						case 'property':
							require_once EPL_PATH_LIB . 'post-types/post-type-property.php';
							break;

						case 'land':
							require_once EPL_PATH_LIB . 'post-types/post-type-land.php';
							break;

						case 'rental':
							require_once EPL_PATH_LIB . 'post-types/post-type-rental.php';
							break;

						case 'rural':
							require_once EPL_PATH_LIB . 'post-types/post-type-rural.php';
							break;

						case 'business':
							require_once EPL_PATH_LIB . 'post-types/post-type-business.php';
							break;

						case 'commercial':
							require_once EPL_PATH_LIB . 'post-types/post-type-commercial.php';
							break;

						case 'commercial_land':
							require_once EPL_PATH_LIB . 'post-types/post-type-commercial-land.php';
							break;

						default:
							break;
					}
				}
			}
			require_once EPL_PATH_LIB . 'post-types/post-type-contact.php';

			require_once EPL_PATH_LIB . 'taxonomies/tax-location.php';
			require_once EPL_PATH_LIB . 'taxonomies/tax-features.php';
			require_once EPL_PATH_LIB . 'taxonomies/tax-business-listings.php';
			require_once EPL_PATH_LIB . 'taxonomies/tax-contact-tags.php';

			require_once EPL_PATH_LIB . 'widgets/widget-functions.php';
			require_once EPL_PATH_LIB . 'widgets/class-epl-widget-author.php';
			require_once EPL_PATH_LIB . 'widgets/class-epl-widget-recent-property.php';
			require_once EPL_PATH_LIB . 'widgets/class-epl-widget-property-gallery.php';
			require_once EPL_PATH_LIB . 'widgets/class-epl-widget-property-search.php';
			require_once EPL_PATH_LIB . 'widgets/class-epl-widget-contact-capture.php';

			require_once EPL_PATH_LIB . 'includes/class-epl-property-meta.php';
			require_once EPL_PATH_LIB . 'includes/class-epl-author.php';
			require_once EPL_PATH_LIB . 'includes/class-epl-author-loader.php';
			require_once EPL_PATH_LIB . 'includes/class-epl-author-meta.php';
			require_once EPL_PATH_LIB . 'includes/conditional-tags.php';
			require_once EPL_PATH_LIB . 'includes/template-functions.php';
			require_once EPL_PATH_LIB . 'includes/error-tracking.php';

			require_once EPL_PATH_LIB . 'includes/class-epl-pagination-call.php';
			require_once EPL_PATH_LIB . 'includes/pagination.php';

			require_once EPL_PATH_LIB . 'includes/class-epl-contact.php';
			require_once EPL_PATH_LIB . 'meta-boxes/meta-box-init.php';

			if ( is_admin() ) {
				require_once EPL_PATH_LIB . 'includes/admin/plugins.php';
				require_once EPL_PATH_LIB . 'includes/class-epl-metabox.php';
				require_once EPL_PATH_LIB . 'post-types/post-types.php';
				require_once EPL_PATH_LIB . 'includes/admin/admin-functions.php';
				require_once EPL_PATH_LIB . 'includes/admin/admin-actions.php';
				require_once EPL_PATH_LIB . 'includes/class-epl-license.php';
				require_once EPL_PATH_LIB . 'includes/user.php';
				require_once EPL_PATH_LIB . 'includes/admin/menus/menus.php';
				require_once EPL_PATH_LIB . 'includes/admin/menus/class-epl-welcome.php';
				require_once EPL_PATH_LIB . 'meta-boxes/meta-boxes.php';
				require_once EPL_PATH_LIB . 'includes/admin/contacts/contacts.php';
				require_once EPL_PATH_LIB . 'includes/admin/contacts/contact-functions.php';
				require_once EPL_PATH_LIB . 'includes/admin/contacts/contact-actions.php';
				require_once EPL_PATH_LIB . 'includes/admin/reports/graphing.php';
				require_once EPL_PATH_LIB . 'includes/admin/reports/reports.php';
				require_once EPL_PATH_LIB . 'includes/admin/reports/class-epl-graph.php';
				require_once EPL_PATH_LIB . 'widgets/widget-admin-dashboard.php';
				require_once EPL_PATH_LIB . 'includes/admin/help.php';
				require_once EPL_PATH_LIB . 'includes/admin/help-single.php';
				require_once EPL_PATH_LIB . 'includes/admin/listing-elements-gui.php';
				// require_once EPL_PATH_LIB . 'includes/admin/class-epl-admin-images.php'; // Image management pending.
			} else {
				require_once EPL_PATH_LIB . 'templates/themes/themes.php';
			}

			require_once EPL_PATH_LIB . 'includes/options-front-end.php';
			require_once EPL_PATH_LIB . 'hooks/hook-property-map.php';
			require_once EPL_PATH_LIB . 'hooks/hook-external-links.php';
			require_once EPL_PATH_LIB . 'hooks/hook-floorplan.php';
			require_once EPL_PATH_LIB . 'hooks/hook-mini-web.php';
			require_once EPL_PATH_LIB . 'hooks/hook-read-more.php';
			require_once EPL_PATH_LIB . 'hooks/hook-energy-certificate.php';
			require_once EPL_PATH_LIB . 'shortcodes/shortcode-listing-map.php';
			require_once EPL_PATH_LIB . 'shortcodes/class-epl-advanced-shortcode-listing.php';
			require_once EPL_PATH_LIB . 'shortcodes/class-epl-listing-elements.php';
			require_once EPL_PATH_LIB . 'shortcodes/shortcode-listing.php';
			require_once EPL_PATH_LIB . 'shortcodes/shortcode-listing-advanced.php';
			require_once EPL_PATH_LIB . 'shortcodes/shortcode-listing-search.php';
			require_once EPL_PATH_LIB . 'shortcodes/shortcode-contact-form.php';
			require_once EPL_PATH_LIB . 'shortcodes/shortcode-listing-open.php';
			require_once EPL_PATH_LIB . 'shortcodes/shortcode-listing-category.php';
			require_once EPL_PATH_LIB . 'shortcodes/shortcode-listing-tax-feature.php';
			require_once EPL_PATH_LIB . 'shortcodes/shortcode-listing-tax-location.php';
			require_once EPL_PATH_LIB . 'shortcodes/shortcode-listing-auction.php';
			require_once EPL_PATH_LIB . 'shortcodes/shortcode-listing-results.php';
			require_once EPL_PATH_LIB . 'shortcodes/shortcode-listing-meta-doc.php';

			require_once EPL_PATH_LIB . 'includes/install.php';
			require_once EPL_PATH_LIB . 'includes/class-epl-search-fields.php';
			require_once EPL_PATH_LIB . 'includes/class-epl-search.php';
			require_once EPL_PATH_LIB . 'includes/class-epl-render-fields.php';
			require_once EPL_PATH_LIB . 'includes/class-epl-rest-api.php';

			if ( file_exists( get_stylesheet_directory() . '/easypropertylistings/functions.php' ) ) {
				include_once get_stylesheet_directory() . '/easypropertylistings/functions.php';
			}
		}

		/**
		 * Loads the plugin language files.
		 *
		 * @access public
		 * @since 1.1.2
		 * @return void
		 */
		public function load_textdomain() {
			// Set filter for plugin's languages directory.
			$epl_lang_dir = EPL_PLUGIN_PATH . 'languages/';
			$epl_lang_dir = apply_filters( 'epl_languages_directory', $epl_lang_dir );

			// Traditional WordPress plugin locale filter.
			$locale = apply_filters( 'plugin_locale', get_locale(), 'easy-property-listings' );
			$mofile = sprintf( '%1$s-%2$s.mo', 'easy-property-listings', $locale );

			// Setup paths to current locale file.
			$mofile_local  = $epl_lang_dir . $mofile;
			$mofile_global = WP_LANG_DIR . '/epl/' . $mofile;

			if ( file_exists( $mofile_global ) ) {
				// Look in global /wp-content/languages/epl folder.
				load_textdomain( 'easy-property-listings', $mofile_global );
			} elseif ( file_exists( $mofile_local ) ) {
				// Look in local /wp-content/plugins/easy-property-listings/languages/ folder.
				load_textdomain( 'easy-property-listings', $mofile_local );
			} else {
				// Load the default language files.
				load_plugin_textdomain( 'easy-property-listings', false, $epl_lang_dir );
			}
		}
	}
endif; // End if class_exists check.

/**
 * The main function responsible for returning the one true Easy_Property_Listings
 * Instance to functions everywhere.
 *
 * Use this function like you would a global variable, except without needing
 * to declare the global.
 *
 * Example: <?php $epl = EPL(); ?>
 *
 * @since 1.0.0
 * @return object The one true Easy_Property_Listings Instance
 */
function EPL() { //phpcs:ignore
	return Easy_Property_Listings::instance();
}

// Get EPL Running.
EPL();
