<?php
/**
 * Media Sitemap for Google
 *
 * @package    Media Sitemap for Google
 * @subpackage MediaSitemapAdmin Management screen
/*
	Copyright (c) 2017- Katsushi Kawamori (email : dodesyoswift312@gmail.com)
	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation; version 2 of the License.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 */

$mediasitemapadmin = new MediaSitemapAdmin();

/** ==================================================
 * Management screen
 */
class MediaSitemapAdmin {

	/** ==================================================
	 * Construct
	 *
	 * @since 1.02
	 */
	public function __construct() {

		add_action( 'init', array( $this, 'register_settings' ) );

		add_action( 'admin_menu', array( $this, 'plugin_menu' ) );
		add_filter( 'plugin_action_links', array( $this, 'settings_link' ), 10, 2 );
	}

	/** ==================================================
	 * Add a "Settings" link to the plugins page
	 *
	 * @param  array  $links  links array.
	 * @param  string $file   file.
	 * @return array  $links  links array.
	 * @since 1.00
	 */
	public function settings_link( $links, $file ) {
		static $this_plugin;
		if ( empty( $this_plugin ) ) {
			$this_plugin = 'media-sitemap/mediasitemap.php';
		}
		if ( $file == $this_plugin ) {
			$links[] = '<a href="' . admin_url( 'options-general.php?page=MediaSitemap' ) . '">' . __( 'Settings' ) . '</a>';
		}
		return $links;
	}

	/** ==================================================
	 * Settings page
	 *
	 * @since   1.00
	 */
	public function plugin_menu() {
		add_options_page( 'MediaSitemap Options', 'Media Sitemap for Google', 'manage_options', 'MediaSitemap', array( $this, 'plugin_options' ) );
	}

	/** ==================================================
	 * Settings page
	 *
	 * @since 1.00
	 */
	public function plugin_options() {

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'You do not have sufficient permissions to access this page.' ) );
		}

		$this->options_updated();

		$scriptname = admin_url( 'options-general.php?page=MediaSitemap' );

		?>
		<div class="wrap">
		<h2>Media Sitemap for Google</h2>
			<details>
			<summary><strong><?php esc_html_e( 'Various links of this plugin', 'media-sitemap' ); ?></strong></summary>
			<?php $this->credit(); ?>
			</details>

			<?php
			$google_image_sitemaps_url = 'https://developers.google.com/search/docs/advanced/sitemaps/image-sitemaps';
			$google_video_sitemaps_url = 'https://developers.google.com/search/docs/advanced/sitemaps/video-sitemaps';
			$image_sitemap_url = home_url( '?feed=googleimagesitemap' );
			$video_sitemap_url = home_url( '?feed=googlevideossitemap' );
			$google_api_url = 'https://console.cloud.google.com/apis/';
			?>

			<form method="post" action="<?php echo esc_url( $scriptname ); ?>">
			<?php wp_nonce_field( 'msfg_set', 'mediasitemapforgoogle_settings' ); ?>

				<h3><a style="text-decoration: none;" href="<?php echo esc_url( $google_image_sitemaps_url ); ?>" target="_blank" rel="noopener noreferrer"><strong><?php esc_html_e( 'Image sitemaps', 'media-sitemap' ); ?><span class="dashicons dashicons-editor-help"></span></strong></a> <span class="dashicons dashicons-arrow-right-alt"></span> <a style="text-decoration: none;" href="<?php echo esc_url( $image_sitemap_url ); ?>" target="_blank" rel="noopener noreferrer"><span style="font-weight: bold;"><?php echo esc_url( $image_sitemap_url ); ?></span><span class="dashicons dashicons-rss"></span></a></h3>
				<div style="margin: 5px; padding: 5px;">
					<h3><?php esc_html_e( 'Image\'s MIME Type', 'media-sitemap' ); ?></h3>
					<p class="description">
					<?php esc_html_e( 'Check the images you want to sitemap.', 'media-sitemap' ); ?>
					</p>

					<?php
					$list_types = array(
						'image/jpeg',
						'image/png',
						'image/bmp',
						'image/gif',
						'image/webp',
					);
					foreach ( $list_types as $value ) {
						if ( in_array( $value, get_option( 'msfg_mime_types' ) ) ) {
							$check = 1;
						} else {
							$check = 0;
						}
						?>
						<div style="display: block;padding:5px 5px"><input type="checkbox" name="list_types[<?php echo esc_attr( $value ); ?>]" value="1" <?php checked( '1', $check ); ?> /><?php echo esc_html( $value ); ?></div>
						<?php
					}
					?>
				</div>

				<h3><a style="text-decoration: none;" href="<?php echo esc_url( $google_video_sitemaps_url ); ?>" target="_blank" rel="noopener noreferrer"><strong><?php esc_html_e( 'Video sitemaps', 'media-sitemap' ); ?><span class="dashicons dashicons-editor-help"></span></strong></a> <span class="dashicons dashicons-arrow-right-alt"></span> <a style="text-decoration: none;" href="<?php echo esc_url( $video_sitemap_url ); ?>" target="_blank" rel="noopener noreferrer"><span style="font-weight: bold;"><?php echo esc_url( $video_sitemap_url ); ?></span><span class="dashicons dashicons-rss"></span></a></h3>
				<div style="margin: 5px; padding: 5px;">
					<h3><?php echo 'YouTube Data API v3 ' . esc_html__( 'Key' ); ?> <a style="text-decoration: none;" href="<?php echo esc_url( $google_api_url ); ?>" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Get it on Google Cloud Platform', 'media-sitemap' ); ?></a></h3>
					<p class="description">
					<?php esc_html_e( 'This is necessary to retrieve the data from YouTube embeds if any are present in the content.', 'media-sitemap' ); ?>
					</p>
					<div style="margin: 5px; padding: 5px;">
						<input type="text" name="youtube_api" style="width: 400px;" value="<?php echo esc_attr( get_option( 'msfg_youtube_api' ) ); ?>">
					</div>
				</div>
			<?php submit_button( __( 'Save Changes' ), 'large', 'Msfg_Save', true ); ?>
			</form>

		</div>
		<?php
	}

	/** ==================================================
	 * Update wp_options table.
	 *
	 * @since 2.00
	 */
	private function options_updated() {

		if ( isset( $_POST['Msfg_Save'] ) && ! empty( $_POST['Msfg_Save'] ) ) {
			if ( check_admin_referer( 'msfg_set', 'mediasitemapforgoogle_settings' ) ) {
				if ( isset( $_POST['list_types'] ) && ! empty( $_POST['list_types'] ) ) {
					$list_types = array();
					$tmps = filter_var(
						wp_unslash( $_POST['list_types'] ),
						FILTER_CALLBACK,
						array(
							'options' => function ( $value ) {
								return sanitize_text_field( $value );
							},
						)
					);
					foreach ( $tmps as $key => $value ) {
						$list_types[] = $key;
					}
					update_option( 'msfg_mime_types', $list_types );
					/* translators: MIME Type */
					echo '<div class="notice notice-success is-dismissible"><ul><li>' . esc_html( sprintf( __( 'Saved %s', 'media-sitemap' ), __( 'MIME Type', 'media-sitemap' ) ) ) . '</li></ul></div>';
				} else {
					update_option( 'msfg_mime_types', array() );
					echo '<div class="notice notice-success is-dismissible"><ul><li>' . esc_html__( 'All types removed.', 'media-sitemap' ) . '</li></ul></div>';
				}
				if ( ! empty( $_POST['youtube_api'] ) ) {
					update_option( 'msfg_youtube_api', sanitize_text_field( wp_unslash( $_POST['youtube_api'] ) ) );
					/* translators: Add API */
					echo '<div class="notice notice-success is-dismissible"><ul><li>' . esc_html( sprintf( __( 'Saved %s', 'media-sitemap' ), 'API' ) ) . '</li></ul></div>';
				}
			}
		}
	}

	/** ==================================================
	 * Settings register
	 *
	 * @since 2.01
	 */
	public function register_settings() {

		if ( ! get_option( 'msfg_mime_types' ) ) {
			$list_types = array(
				'image/jpeg',
				'image/png',
				'image/bmp',
				'image/gif',
				'image/webp',
			);
			update_option( 'msfg_mime_types', $list_types );
		}
	}

	/** ==================================================
	 * Credit
	 *
	 * @since 1.00
	 */
	private function credit() {

		$plugin_name    = null;
		$plugin_ver_num = null;
		$plugin_path    = plugin_dir_path( __DIR__ );
		$plugin_dir     = untrailingslashit( wp_normalize_path( $plugin_path ) );
		$slugs          = explode( '/', $plugin_dir );
		$slug           = end( $slugs );
		$files          = scandir( $plugin_dir );
		foreach ( $files as $file ) {
			if ( '.' === $file || '..' === $file || is_dir( $plugin_path . $file ) ) {
				continue;
			} else {
				$exts = explode( '.', $file );
				$ext  = strtolower( end( $exts ) );
				if ( 'php' === $ext ) {
					$plugin_datas = get_file_data(
						$plugin_path . $file,
						array(
							'name'    => 'Plugin Name',
							'version' => 'Version',
						)
					);
					if ( array_key_exists( 'name', $plugin_datas ) && ! empty( $plugin_datas['name'] ) && array_key_exists( 'version', $plugin_datas ) && ! empty( $plugin_datas['version'] ) ) {
						$plugin_name    = $plugin_datas['name'];
						$plugin_ver_num = $plugin_datas['version'];
						break;
					}
				}
			}
		}
		$plugin_version = __( 'Version:' ) . ' ' . $plugin_ver_num;
		/* translators: FAQ Link & Slug */
		$faq       = sprintf( __( 'https://wordpress.org/plugins/%s/faq', 'media-sitemap' ), $slug );
		$support   = 'https://wordpress.org/support/plugin/' . $slug;
		$review    = 'https://wordpress.org/support/view/plugin-reviews/' . $slug;
		$translate = 'https://translate.wordpress.org/projects/wp-plugins/' . $slug;
		$facebook  = 'https://www.facebook.com/katsushikawamori/';
		$twitter   = 'https://twitter.com/dodesyo312';
		$youtube   = 'https://www.youtube.com/channel/UC5zTLeyROkvZm86OgNRcb_w';
		$donate    = __( 'https://shop.riverforest-wp.info/donate/', 'media-sitemap' );

		?>
		<span style="font-weight: bold;">
		<div>
		<?php echo esc_html( $plugin_version ); ?> | 
		<a style="text-decoration: none;" href="<?php echo esc_url( $faq ); ?>" target="_blank" rel="noopener noreferrer">FAQ</a> | <a style="text-decoration: none;" href="<?php echo esc_url( $support ); ?>" target="_blank" rel="noopener noreferrer">Support Forums</a> | <a style="text-decoration: none;" href="<?php echo esc_url( $review ); ?>" target="_blank" rel="noopener noreferrer">Reviews</a>
		</div>
		<div>
		<a style="text-decoration: none;" href="<?php echo esc_url( $translate ); ?>" target="_blank" rel="noopener noreferrer">
		<?php
		/* translators: Plugin translation link */
		echo esc_html( sprintf( __( 'Translations for %s' ), $plugin_name ) );
		?>
		</a> | <a style="text-decoration: none;" href="<?php echo esc_url( $facebook ); ?>" target="_blank" rel="noopener noreferrer"><span class="dashicons dashicons-facebook"></span></a> | <a style="text-decoration: none;" href="<?php echo esc_url( $twitter ); ?>" target="_blank" rel="noopener noreferrer"><span class="dashicons dashicons-twitter"></span></a> | <a style="text-decoration: none;" href="<?php echo esc_url( $youtube ); ?>" target="_blank" rel="noopener noreferrer"><span class="dashicons dashicons-video-alt3"></span></a>
		</div>
		</span>

		<div style="width: 250px; height: 180px; margin: 5px; padding: 5px; border: #CCC 2px solid;">
		<h3><?php esc_html_e( 'Please make a donation if you like my work or would like to further the development of this plugin.', 'media-sitemap' ); ?></h3>
		<div style="text-align: right; margin: 5px; padding: 5px;"><span style="padding: 3px; color: #ffffff; background-color: #008000">Plugin Author</span> <span style="font-weight: bold;">Katsushi Kawamori</span></div>
		<button type="button" style="margin: 5px; padding: 5px;" onclick="window.open('<?php echo esc_url( $donate ); ?>')"><?php esc_html_e( 'Donate to this plugin &#187;' ); ?></button>
		</div>

		<?php
	}
}


