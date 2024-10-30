<?php
/**
 * Media Sitemap for Google
 *
 * @package    Media Sitemap for Google
 * @subpackage MediaSitemap Main Functions
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

$mediasitemap = new MediaSitemap();

/** ==================================================
 * Main Functions
 */
class MediaSitemap {

	/** ==================================================
	 * Path
	 *
	 * @var $upload_url  upload_url.
	 */
	private $upload_url;

	/** ==================================================
	 * Construct
	 *
	 * @since 1.02
	 */
	public function __construct() {

		list($upload_dir, $this->upload_url, $upload_path) = $this->upload_dir_url_path();

		add_filter( 'request', array( $this, 'myfeed_request' ) );
		add_action( 'init', array( $this, 'add_custom_feed' ) );
		add_action( 'wp_head', array( $this, 'google_media_sitemap_alternate' ) );
	}

	/** ==================================================
	 * Add feed
	 *
	 * @since 1.00
	 */
	public function add_custom_feed() {

		add_feed( 'googleimagesitemap', array( $this, 'load_posts_create_images_feed' ) );
		add_feed( 'googlevideossitemap', array( $this, 'load_posts_create_videos_feed' ) );
		add_feed( 'gis-xsl', array( $this, 'load_posts_create_images_xsl_feed' ) );
		add_feed( 'gvs-xsl', array( $this, 'load_posts_create_videos_xsl_feed' ) );
	}

	/** ==================================================
	 * Create images feed
	 *
	 * @since 1.00
	 */
	public function load_posts_create_images_feed() {

		$arg = array(
			'posts_per_page'    => -1,
			'orderby'           => 'date',
			'order'             => 'DESC',
			'post_type'         => $this->post_custom_types(),
			'post_status'       => 'publish',
		);
		$all_posts = get_posts( $arg );

		header( 'Content-Type: ' . feed_content_type( 'rss-http' ) . '; charset=' . get_option( 'blog_charset' ), true );

		echo '<?'; ?>xml version="1.0" encoding="<?php echo esc_attr( get_option( 'blog_charset' ) ); ?>"<?php echo '?>';
		echo '<?'; ?>xml-stylesheet type="text/xsl" href="<?php echo esc_url( home_url( '?feed=gis-xsl' ) ); ?>"<?php echo '?>'; ?>

<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">
<?php
		foreach ( $all_posts as $post ) {
			$this->post_images( $post->ID, $post->post_content );
		}
?>
</urlset>
<?php
	}

	/** ==================================================
	 * Create images XSL feed
	 *
	 * @since 1.00
	 */
	public function load_posts_create_images_xsl_feed() {

		header( 'Content-Type: ' . feed_content_type( 'rss-http' ) . '; charset=' . get_option( 'blog_charset' ), true );

		echo '<?'; ?>xml version="1.0" encoding="<?php echo esc_attr( get_option( 'blog_charset' ) ); ?>"<?php echo '?>'; ?>

<xsl:stylesheet version='2.0'
	xmlns:html='http://www.w3.org/TR/REC-html40'
	xmlns:sitemap='http://www.sitemaps.org/schemas/sitemap/0.9'
	xmlns:image='http://www.google.com/schemas/sitemap-image/1.1'
	xmlns:xsl='http://www.w3.org/1999/XSL/Transform'>
<xsl:output method='html' version='1.0' encoding='UTF-8' indent='yes'/>
<xsl:template match="/">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>XML <?php esc_html_e( 'Image sitemaps', 'media-sitemap' ); ?></title>
	<meta http-equiv='Content-Type' content='text/html; charset=utf-8'/>
	<style type='text/css'>
		body {
			font: 14px 'Open Sans', Helvetica, Arial, sans-serif;
			margin: 0;
		}

		a {
			color: #3498db;
			text-decoration: none;
		}

		h1 {
			margin: 0;
		}

		#description {
			background-color: #f17059;
			color: #FFF;
			padding: 30px 30px 20px;
		}

		#description a {
			color: #fff;
		}

		#content {
			padding: 10px 30px 30px;
			background: #fff;
		}

		a:hover {
			border-bottom: 1px solid;
		}

		th, td {
			font-size: 12px;
		}

		th {
			text-align: left;
			border-bottom: 1px solid #ccc;
		}

		th, td {
			padding: 10px 15px;
		}

		.odd {
			background-color: #f7d6c5;
		}

		#footer {
			margin: 20px 30px;
			font-size: 12px;
			color: #999;
		}

		#footer a {
			color: inherit;
		}

		#description a, #footer a {
			border-bottom: 1px solid;
		}

		#description a:hover, #footer a:hover {
			border-bottom: none;
		}

		img.thumbnail {
			max-height: 100px;
			max-width: 100px;
		}
	</style>
</head>
<body>
	<div id='description'>
		<h1>XML <?php esc_html_e( 'Image sitemaps', 'media-sitemap' ); ?></h1>
	</div>
	<div id='content'>
		<!-- <xsl:value-of select="count(sitemap:urlset/sitemap:url)"/> -->
		<table>
			<tr>
				<th>#</th>
				<th><?php esc_html_e( 'Post' ); ?>, <?php esc_html_e( 'Pages' ); ?>, <?php esc_html_e( 'Custom posts', 'media-sitemap' ); ?></th>
				<th><?php esc_html_e( 'Image' ); ?></th>
			</tr>
			<xsl:for-each select="sitemap:urlset/sitemap:url">
				<tr>
					<xsl:choose>
						<xsl:when test='position() mod 2 != 1'>
							<xsl:attribute name="class">odd</xsl:attribute>
						</xsl:when>
					</xsl:choose>
					<td>
						<xsl:value-of select = "position()" />
					</td>
					<td>
						<xsl:variable name='pageURL'>
							<xsl:value-of select='sitemap:loc'/>
						</xsl:variable>
						<a href='{$pageURL}'>
							<xsl:value-of select='sitemap:loc'/>
						</a>
					</td>
					<td>
						<xsl:for-each select="image:image">
							<xsl:variable name='itemURL'>
								<xsl:value-of select='image:loc'/>
							</xsl:variable>
							<div>
							<a href='{$itemURL}'>
								<xsl:value-of select='image:loc'/>
							</a>
							</div>
						</xsl:for-each>
					</td>
				</tr>
			</xsl:for-each>
		</table>
	</div>
	<div id='footer'>
		<p><a href="<?php echo esc_url( 'https://wordpress.org/plugins/media-sitemap/', 'media-sitemap' ); ?>">Media Sitemap for Google</a></p>
	</div>
</body>
</html>
</xsl:template>
</xsl:stylesheet>

<?php
	}

	/** ==================================================
	 * Create videos feed
	 *
	 * @since 1.00
	 */
	public function load_posts_create_videos_feed() {

		$arg = array(
			'posts_per_page'    => -1,
			'orderby'           => 'date',
			'order'             => 'DESC',
			'post_type'         => $this->post_custom_types(),
			'post_status'       => 'publish',
		);
		$all_posts = get_posts( $arg );

		header( 'Content-Type: ' . feed_content_type( 'rss-http' ) . '; charset=' . get_option( 'blog_charset' ), true );

		echo '<?'; ?>xml version="1.0" encoding="<?php echo esc_attr( get_option( 'blog_charset' ) ); ?>"<?php echo '?>';
		echo '<?'; ?>xml-stylesheet type="text/xsl" href="<?php echo esc_url( home_url( '?feed=gvs-xsl' ) ); ?>"<?php echo '?>'; ?>

<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd" xmlns:video="http://www.google.com/schemas/sitemap-video/1.1">
<?php
		foreach ( $all_posts as $post ) {
			$this->post_videos( $post->ID, $post->post_content );
		}
?>
</urlset>
<?php
	}

	/** ==================================================
	 * Create images XSL feed
	 *
	 * @since 1.00
	 */
	public function load_posts_create_videos_xsl_feed() {

		header( 'Content-Type: ' . feed_content_type( 'rss-http' ) . '; charset=' . get_option( 'blog_charset' ), true );

		echo '<?'; ?>xml version="1.0" encoding="<?php echo esc_attr( get_option( 'blog_charset' ) ); ?>"<?php echo '?>'; ?>

<xsl:stylesheet version='2.0'
	xmlns:html='http://www.w3.org/TR/REC-html40'
	xmlns:sitemap='http://www.sitemaps.org/schemas/sitemap/0.9'
	xmlns:video='http://www.google.com/schemas/sitemap-video/1.1'
	xmlns:xsl='http://www.w3.org/1999/XSL/Transform'>
<xsl:output method='html' version='1.0' encoding='UTF-8' indent='yes'/>
<xsl:template match="/">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>XML <?php esc_html_e( 'Video sitemaps', 'media-sitemap' ); ?></title>
	<meta http-equiv='Content-Type' content='text/html; charset=utf-8'/>
	<style type='text/css'>
		body {
			font: 14px 'Open Sans', Helvetica, Arial, sans-serif;
			margin: 0;
		}

		a {
			color: #3498db;
			text-decoration: none;
		}

		h1 {
			margin: 0;
		}

		#description {
			background-color: #f17059;
			color: #FFF;
			padding: 30px 30px 20px;
		}

		#description a {
			color: #fff;
		}

		#content {
			padding: 10px 30px 30px;
			background: #fff;
		}

		a:hover {
			border-bottom: 1px solid;
		}

		th, td {
			font-size: 12px;
		}

		th {
			text-align: left;
			border-bottom: 1px solid #ccc;
			padding: 10px 15px;
		}

		td {
			padding: 10px 15px;
			vertical-align: top;
			line-height: 100px;
		}

		.odd {
			background-color: #f7d6c5;
		}

		#footer {
			margin: 20px 30px;
			font-size: 12px;
			color: #999;
		}

		#footer a {
			color: inherit;
		}

		#description a, #footer a {
			border-bottom: 1px solid;
		}

		#description a:hover, #footer a:hover {
			border-bottom: none;
		}

		img.thumbnail {
			max-height: 100px;
			max-width: 100px;
		}
	</style>
</head>
<body>
	<div id='description'>
		<h1>XML <?php esc_html_e( 'Video sitemaps', 'media-sitemap' ); ?></h1>
	</div>
	<div id='content'>
		<!-- <xsl:value-of select="count(sitemap:urlset/sitemap:url)"/> -->
		<table>
			<tr>
				<th>#</th>
				<th><?php esc_html_e( 'Post' ); ?>, <?php esc_html_e( 'Pages' ); ?>, <?php esc_html_e( 'Custom posts', 'media-sitemap' ); ?></th>
				<th><?php esc_html_e( 'Video' ); ?></th>
				<th><?php esc_html_e( 'Title' ); ?></th>
				<th><?php esc_html_e( 'Description' ); ?></th>
				<th><?php esc_html_e( 'Thumbnail' ); ?></th>
				<th><?php esc_html_e( 'Duration', 'media-sitemap' ); ?></th>
			</tr>
			<xsl:for-each select="sitemap:urlset/sitemap:url">
				<tr>
					<xsl:choose>
						<xsl:when test='position() mod 2 != 1'>
							<xsl:attribute name="class">odd</xsl:attribute>
						</xsl:when>
					</xsl:choose>
					<td>
						<xsl:value-of select = "position()" />
					</td>
					<td>
						<xsl:variable name='pageURL'>
							<xsl:value-of select='sitemap:loc'/>
						</xsl:variable>
						<a href='{$pageURL}'>
							<xsl:value-of select='sitemap:loc'/>
						</a>
					</td>
					<td>
						<xsl:for-each select="video:video">
							<xsl:variable name='itemURL'>
								<xsl:value-of select='video:player_loc'/>
							</xsl:variable>
							<div>
							<a href='{$itemURL}'>
								<xsl:value-of select='video:player_loc'/>
							</a>
							</div>
						</xsl:for-each>
					</td>
					<td>
						<xsl:for-each select="video:video">
							<div>
							<xsl:value-of select='video:title'/>
							</div>
						</xsl:for-each>
					</td>
					<td>
						<xsl:for-each select="video:video">
							<div>
							<xsl:value-of select='video:description'/>
							</div>
						</xsl:for-each>
					</td>
					<td>
						<xsl:for-each select="video:video">
							<xsl:variable name='thumbURL'>
								<xsl:value-of select='video:thumbnail_loc'/>
							</xsl:variable>
							<div>
							<a href='{$thumbURL}'>
								<img class='thumbnail' src='{$thumbURL}'/>
							</a>
							</div>
						</xsl:for-each>
					</td>
					<td>
						<xsl:for-each select="video:video">
							<div>
							<xsl:value-of select='video:duration'/>
							</div>
						</xsl:for-each>
					</td>
				</tr>
			</xsl:for-each>
		</table>
	</div>
	<div id='footer'>
		<p><a href="<?php echo esc_url( 'https://wordpress.org/plugins/media-sitemap/', 'media-sitemap' ); ?>">Media Sitemap for Google</a></p>
	</div>
</body>
</html>
</xsl:template>
</xsl:stylesheet>

<?php
	}

	/** ==================================================
	 * Image xml
	 *
	 * @param int    $pid  pid.
	 * @param string $content  content.
	 * @since 1.00
	 */
	private function post_images( $pid, $content ) {

		/* for short code */
		$short_code = null;
		$pattern_short_code = '/\[.+?\]/im';
		if ( ! empty( $content ) && preg_match( $pattern_short_code, $content ) ) {
			preg_match_all( $pattern_short_code, $content, $retshortcode );
			foreach ( $retshortcode as $ret => $shortcodes ) {
				foreach ( $shortcodes as $shortcode ) {
					$short_code = do_shortcode( $shortcode );
					$content = str_replace( $shortcode, $short_code, $content );
				}
			}
		}

		$urls = array();
		$image_urls = array();
		if ( preg_match_all( '(https?://[-_.!~*()a-zA-Z0-9;/?:@&=+$%#纊-黑亜-熙ぁ-んァ-ヶ]+)', $content, $result ) !== false ) {
			$unique = array_unique( $result[0] );
			$urls = array_values( $unique );
			foreach ( $urls as $value ) {
				$filetype = wp_check_filetype( $value );
				$ext = $filetype['ext'];
				$mime_type = $filetype['type'];
				if ( in_array( $mime_type, get_option( 'msfg_mime_types' ) ) ) {
					$image_urls[] = $value;
				}
			}
		}

		if ( ! empty( $image_urls ) ) {
			?>
<url>
<loc><?php echo esc_url( get_permalink( $pid ) ); ?></loc>
<?php
			foreach ( $image_urls as $value ) {
?>
<image:image>
<image:loc><?php echo esc_url( $value ); ?></image:loc>
</image:image>
<?php
			}
			?>
</url>
<?php
		}
	}

	/** ==================================================
	 * Video xml
	 *
	 * @param int    $pid  pid.
	 * @param string $content  content.
	 * @since 1.00
	 */
	private function post_videos( $pid, $content ) {

		/* for short code */
		$short_code = null;
		$pattern_short_code = '/\[.+?\]/im';
		if ( ! empty( $content ) && preg_match( $pattern_short_code, $content ) ) {
			preg_match_all( $pattern_short_code, $content, $retshortcode );
			foreach ( $retshortcode as $ret => $shortcodes ) {
				foreach ( $shortcodes as $shortcode ) {
					$short_code = do_shortcode( $shortcode );
					$content = str_replace( $shortcode, $short_code, $content );
				}
			}
		}

		$flag = false;
		$videos_url = array();
		if ( preg_match_all( '(https?://[-_.!~*()a-zA-Z0-9;/?:@&=+$%#纊-黑亜-熙ぁ-んァ-ヶ]+)', $content, $result ) !== false ) {
			$unique = array_unique( $result[0] );
			$videos_url = array_values( $unique );
			foreach ( $videos_url as $value ) {
				$exts = explode( '.', $value );
				$ext = end( $exts );
				$ext2type = wp_ext2type( $ext );
				if ( 'video' === $ext2type ||
						false !== strpos( $value, 'https://www.youtube.com/embed/' ) ||
						false !== strpos( $value, 'https://youtu.be/' ) ) {
					$flag = true;
					break;
				}
			}
		}

		if ( $flag ) {
			?>
<url>
<loc><?php echo esc_url( get_permalink( $pid ) ); ?></loc>
<?php
			foreach ( $videos_url as $value ) {
				$exts = explode( '.', $value );
				$ext = end( $exts );
				$ext2type = wp_ext2type( $ext );
				if ( 'video' === $ext2type ) {
					$thumbnail = null;
					$title = null;
					$caption = null;
					$duration = null;
					$v_id = $this->id_from_url( $value );
					if ( $v_id ) {
						$thumbnail = $this->siteurl() . '/' . WPINC . '/images/media/video.png';
						$v_file = get_attached_file( $v_id );
						$t_base_file = substr( $v_file, 0, -( intval( strlen( $ext ) ) ) );
						$t_base_url = substr( $value, 0, -( intval( strlen( $ext ) ) ) );
						$t_exts = array( 'jpg', 'png' );
						foreach ( $t_exts as $t_ext ) {
							if ( file_exists( $t_base_file . $t_ext ) ) {
								$thumbnail = $t_base_url . $t_ext;
								break;
							}
						}
						$title = get_the_title( $v_id );
						$caption = wp_get_attachment_caption( $v_id );
						$metadata = get_post_meta( $v_id, '_wp_attachment_metadata', true );
						if ( ! empty( $metadata ) && array_key_exists( 'length', $metadata ) ) {
							$duration = $metadata['length'];
						}
					}
?>
<video:video>
<video:thumbnail_loc><?php echo esc_url( $thumbnail ); ?></video:thumbnail_loc>
<video:title><?php echo esc_html( $title ); ?></video:title>
<video:description><?php echo esc_html( $caption ); ?></video:description>
<video:player_loc><?php echo esc_url( $value ); ?></video:player_loc>
<video:duration><?php echo esc_html( $duration ); ?></video:duration>
</video:video>
<?php
				} elseif ( get_option( 'msfg_youtube_api' ) &&
							( false !== strpos( $value, 'https://www.youtube.com/embed/' ) ||
							false !== strpos( $value, 'https://youtu.be/' ) ) ) {
					if ( false !== strpos( $value, 'https://www.youtube.com/embed/' ) ) {
						$vid = substr( $value, 30, 11 );
					} elseif ( false !== strpos( $value, 'https://youtu.be/' ) ) {
						$vid = substr( $value, 17, 11 );
					}
					$youtube_api = 'https://www.googleapis.com/youtube/v3/videos?id=' . $vid . '&key=' . get_option( 'msfg_youtube_api' ) . '&part=snippet,contentDetails,statistics,status';
					$response = wp_remote_get( $youtube_api );
					if ( ! is_wp_error( $response ) ) {
						$youtube_arr = json_decode( $response['body'], true );
						$thumbnail = $youtube_arr['items'][0]['snippet']['thumbnails']['default']['url'];
						$title = $youtube_arr['items'][0]['snippet']['title'];
						$description = $youtube_arr['items'][0]['snippet']['description'];
						$duration_org = $youtube_arr['items'][0]['contentDetails']['duration'];
?>
<video:video>
<video:thumbnail_loc><?php echo esc_url( $thumbnail ); ?></video:thumbnail_loc>
<video:title><?php echo esc_html( $title ); ?></video:title>
<video:description><?php echo esc_html( $description ); ?></video:description>
<video:player_loc><?php echo esc_url( $value ); ?></video:player_loc>
<video:duration><?php echo esc_html( $this->youtube_duration( $duration_org ) ); ?></video:duration>
</video:video>
<?php
					}
				}
			}
			?>
</url>
<?php
		}
	}

	/** ==================================================
	 * Youtube duration calc
	 *
	 * @param string $duration_org  duration_org.
	 * @return int $duration
	 * @since 2.00
	 */
	private function youtube_duration( $duration_org ) {

		$duration_str = str_replace( 'PT', '', $duration_org );
		$duration_str = str_replace( 'S', '', $duration_str );
		$duration_csv = str_replace( 'M', ',', $duration_str );
		$duration_csv = str_replace( 'H', ',', $duration_csv );
		if ( false !== strpos( $duration_csv, ',' ) ) {
			$duration_arr = explode( ',', $duration_csv );
			if ( 2 === count( $duration_arr ) ) {
				$duration = $duration_arr[0] * 60 + $duration_arr[1];
			} else {
				$duration = $duration_arr[0] * 3600 + $duration_arr[1] * 60 + $duration_arr[2];
			}
		} else {
			$duration = $duration_csv;
		}

		return $duration;
	}

	/** ==================================================
	 * Feed request
	 *
	 * @param string $url  url.
	 * @return int or bool $id
	 * @since 1.00
	 */
	private function id_from_url( $url ) {

		global $wpdb;
		$attachments = $wpdb->get_results(
			"
			SELECT ID
			FROM {$wpdb->prefix}posts
			WHERE post_type = 'attachment'
			AND post_mime_type LIKE 'video%'
			"
		);

		$id = false;
		foreach ( $attachments as $attachment ) {
			$attach_url = $this->upload_url . '/' . get_post_meta( $attachment->ID, '_wp_attached_file', true );
			if ( $attach_url === $url ) {
				$id = $attachment->ID;
			}
		}

		return $id;
	}

	/** ==================================================
	 * Feed request
	 *
	 * @param array $qv  qv.
	 * @return array $qv
	 * @since 1.00
	 */
	public function myfeed_request( $qv ) {

		if ( isset( $qv['feed'] ) && ! isset( $qv['post_type'] ) ) {
			$qv['post_type'] = array( 'post', 'page', '$post-type' );
		}

		return $qv;
	}

	/** ==================================================
	 * Feed alternate
	 *
	 * @since 1.00
	 */
	public function google_media_sitemap_alternate() {

		echo '<link rel="alternate" type="' . esc_attr( feed_content_type() ) . '" title="' . esc_attr( get_bloginfo( 'name' ) ) . ' &raquo; Google Image Sitemap" href="' . esc_url( home_url( '?feed=googleimagesitemap' ) ) . "\" />\n";
		echo '<link rel="alternate" type="' . esc_attr( feed_content_type() ) . '" title="' . esc_attr( get_bloginfo( 'name' ) ) . ' &raquo; Google Video Sitemap" href="' . esc_url( home_url( '?feed=googlevideossitemap' ) ) . "\" />\n";
	}

	/** ==================================================
	 * Upload Path
	 *
	 * @return array $upload_dir,$upload_url,$upload_path  uploadpath.
	 * @since 1.10
	 */
	private function upload_dir_url_path() {

		$wp_uploads = wp_upload_dir();

		$relation_path_true = strpos( $wp_uploads['baseurl'], '../' );
		if ( $relation_path_true > 0 ) {
			$relationalpath = substr( $wp_uploads['baseurl'], $relation_path_true );
			$basepath       = substr( $wp_uploads['baseurl'], 0, $relation_path_true );
			$upload_url     = $this->realurl( $basepath, $relationalpath );
			$upload_dir     = wp_normalize_path( realpath( $wp_uploads['basedir'] ) );
		} else {
			$upload_url = $wp_uploads['baseurl'];
			$upload_dir = wp_normalize_path( $wp_uploads['basedir'] );
		}

		if ( is_ssl() ) {
			$upload_url = str_replace( 'http:', 'https:', $upload_url );
		}

		if ( $relation_path_true > 0 ) {
			$upload_path = $relationalpath;
		} else {
			$upload_path = str_replace( site_url( '/' ), '', $upload_url );
		}

		$upload_dir  = untrailingslashit( $upload_dir );
		$upload_url  = untrailingslashit( $upload_url );
		$upload_path = untrailingslashit( $upload_path );

		return array( $upload_dir, $upload_url, $upload_path );
	}

	/** ==================================================
	 * Site url
	 *
	 * @return $siteurl
	 * @since 1.10
	 */
	public function siteurl() {
		if ( is_multisite() ) {
			global $blog_id;
			$siteurl = get_blog_details( $blog_id )->siteurl;
		} else {
			$siteurl = site_url();
		}
		return $siteurl;
	}

	/** ==================================================
	 * Post & Custom Post Type Name
	 *
	 * @since 1.10
	 */
	private function post_custom_types() {

		$post_custom_types = array( 'post', 'page' );
		$args = array(
			'public'   => true,
			'_builtin' => false,
		);
		$custom_post_types = get_post_types( $args, 'objects', 'and' );
		foreach ( $custom_post_types as $post_type ) {
			$post_custom_types[] = $post_type->name;
		}

		return $post_custom_types;
	}
}
