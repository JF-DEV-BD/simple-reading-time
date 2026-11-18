<?php
/**
 * Plugin Name:       Simple Reading Time
 * Plugin URI:        https://github.com/yourusername/simple-reading-time
 * Description:       Automatically calculates and displays the estimated reading time for posts, or via the [reading_time] shortcode.
 * Version:           1.0.0
 * Requires at least: 5.8
 * Requires PHP:      7.4
 * Author:            Your Name
 * Author URI:        https://github.com/yourusername
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       simple-reading-time
 */

// Prevent direct file access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Simple_Reading_Time {

	/**
	 * Average adult reading speed in words per minute.
	 */
	const WORDS_PER_MINUTE = 200;

	/**
	 * Initialize hooks.
	 */
	public function __construct() {
		add_filter( 'the_content', array( $this, 'prepend_reading_time' ) );
		add_shortcode( 'reading_time', array( $this, 'shortcode_callback' ) );
	}

	/**
	 * Calculate estimated reading time in minutes for a given post.
	 *
	 * @param int|null $post_id Post ID. Defaults to current global post.
	 * @return int Estimated minutes (minimum 1).
	 */
	public function get_reading_time( $post_id = null ) {
		$post = get_post($post_id );

		if ( ! $post instanceof WP_Post ) {
			return 1;
		}

		// Strip shortcodes and HTML tags for an accurate word count.
		$clean_content = wp_strip_all_tags( strip_shortcodes($post->post_content ) );
		$word_count    = str_word_count($clean_content );

		$wpm     = (int) apply_filters( 'srt_words_per_minute', self::WORDS_PER_MINUTE );$wpm     = max( 1, $wpm ); // Prevent division by zero.$minutes = (int) ceil( $word_count / $wpm );

		return max( 1, $minutes );
	}

	/**
	 * Format the reading time HTML badge.
	 *
	 * @param int $minutes Number of minutes.
	 * @return string Escaped HTML output.
	 */
	public function format_output( $minutes ) {$label = sprintf(
			/* translators: %d: estimated number of minutes */
			_n( '%d min read', '%d mins read', $minutes, 'simple-reading-time' ),$minutes
		);

		$html = sprintf(
			'<p class="simple-reading-time" style="font-size: 0.9em; opacity: 0.8; margin-bottom: 1em;">📖 %s</p>',
			esc_html( $label )
		);

		return apply_filters( 'srt_output_html', $html,$minutes );
	}

	/**
	 * Automatically prepend the reading time to single post content.
	 *
	 * @param string $content Post content.
	 * @return string Modified post content.
	 */
	public function prepend_reading_time( $content ) {
		if ( is_singular( 'post' ) && in_the_loop() && is_main_query() ) {
			$minutes =$this->get_reading_time();
			return $this->format_output( $minutes ) .$content;
		}

		return $content;
	}

	/**
	 * Render the [reading_time] shortcode.
	 *
	 * @param array $atts Shortcode attributes.
	 * @return string Escaped HTML output.
	 */
	public function shortcode_callback( $atts ) {$atts = shortcode_atts(
			array(
				'id' => get_the_ID(),
			),
			$atts,
			'reading_time'
		);

		$minutes = $this->get_reading_time( absint($atts['id'] ) );
		return $this->format_output($minutes );
	}
}

new Simple_Reading_Time();
