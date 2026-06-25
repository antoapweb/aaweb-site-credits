<?php
/**
 * Main plugin core.
 *
 * @package AAWEB_Site_Credits
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Core plugin class.
 */
final class AAWEB_Site_Credits_Core {

	/**
	 * Option key.
	 *
	 * @var string
	 */
	private const OPTION_KEY = 'aaweb_site_credits_options';

	/**
	 * Init plugin hooks.
	 *
	 * @return void
	 */
	public static function init(): void {
		add_action( 'init', array( __CLASS__, 'register_block' ) );
		add_filter( 'plugin_action_links_' . plugin_basename( AAWEB_SITE_CREDITS_FILE ), array( __CLASS__, 'plugin_action_links' ) );
		add_action( 'admin_menu', array( __CLASS__, 'add_admin_page' ) );
		add_action( 'admin_init', array( __CLASS__, 'register_settings' ) );
		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'register_front_assets' ) );
		add_shortcode( 'aaweb_site_credits', array( __CLASS__, 'shortcode' ) );

		if ( did_action( 'elementor/loaded' ) ) {
			add_action( 'elementor/elements/categories_registered', array( __CLASS__, 'register_elementor_category' ) );
			add_action( 'elementor/widgets/register', array( __CLASS__, 'register_elementor_widget' ) );
		}
	}



	/**
	 * Add Settings shortcut on the Plugins screen.
	 *
	 * @param array<int,string> $links Existing plugin action links.
	 * @return array<int,string>
	 */
	public static function plugin_action_links( array $links ): array {
		$settings_link = sprintf(
			'<a href="%1$s">%2$s</a>',
			esc_url( admin_url( 'options-general.php?page=aaweb-site-credits' ) ),
			esc_html__( 'Settings', 'aaweb-site-credits' )
		);

		array_unshift( $links, $settings_link );

		return $links;
	}

	/**
	 * Default options.
	 *
	 * @return array<string,string>
	 */
	public static function defaults(): array {
		return array(
			'copyright_text'  => __( 'Copyright', 'aaweb-site-credits' ),
			'site_name'       => get_bloginfo( 'name' ),
			'rights_text'     => __( 'All Rights Reserved.', 'aaweb-site-credits' ),
			'enable_credit'   => '0',
			'powered_by_text' => '',
			'developer_name'  => '',
			'developer_url'   => '',
			'separator'       => '|',
			'show_symbol'     => '1',
			'open_new_tab'    => '1',
		);
	}

	/**
	 * Get saved options merged with defaults.
	 *
	 * @return array<string,string>
	 */
	public static function get_options(): array {
		$saved = get_option( self::OPTION_KEY, array() );

		return wp_parse_args( is_array( $saved ) ? $saved : array(), self::defaults() );
	}

	/**
	 * Sanitize options.
	 *
	 * @param array<string,mixed> $input Raw input.
	 * @return array<string,string>
	 */
	public static function sanitize_options( array $input ): array {
		$defaults = self::defaults();

		return array(
			'copyright_text'  => sanitize_text_field( $input['copyright_text'] ?? $defaults['copyright_text'] ),
			'site_name'       => sanitize_text_field( $input['site_name'] ?? $defaults['site_name'] ),
			'rights_text'     => sanitize_text_field( $input['rights_text'] ?? $defaults['rights_text'] ),
			'enable_credit'   => ! empty( $input['enable_credit'] ) ? '1' : '0',
			'powered_by_text' => sanitize_text_field( $input['powered_by_text'] ?? '' ),
			'developer_name'  => sanitize_text_field( $input['developer_name'] ?? '' ),
			'developer_url'   => esc_url_raw( $input['developer_url'] ?? '' ),
			'separator'       => sanitize_text_field( $input['separator'] ?? $defaults['separator'] ),
			'show_symbol'     => ! empty( $input['show_symbol'] ) ? '1' : '0',
			'open_new_tab'    => ! empty( $input['open_new_tab'] ) ? '1' : '0',
		);
	}

	/**
	 * Add settings page.
	 *
	 * @return void
	 */
	public static function add_admin_page(): void {
		add_options_page(
			esc_html__( 'AAWEB Site Credits', 'aaweb-site-credits' ),
			esc_html__( 'AAWEB Site Credits', 'aaweb-site-credits' ),
			'manage_options',
			'aaweb-site-credits',
			array( __CLASS__, 'render_admin_page' )
		);
	}

	/**
	 * Register settings.
	 *
	 * @return void
	 */
	public static function register_settings(): void {
		register_setting(
			'aaweb_site_credits_group',
			self::OPTION_KEY,
			array(
				'type'              => 'array',
				'sanitize_callback' => array( __CLASS__, 'sanitize_options' ),
				'default'           => self::defaults(),
			)
		);
	}

	/**
	 * Render admin page.
	 *
	 * @return void
	 */
	public static function render_admin_page(): void {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$options = self::get_options();
		?>
		<div class="wrap">
			<h1><?php esc_html_e( 'AAWEB Site Credits', 'aaweb-site-credits' ); ?></h1>
			<p><?php esc_html_e( 'Set the default values for the Elementor widget, Gutenberg block and shortcode output.', 'aaweb-site-credits' ); ?></p>

			<form method="post" action="options.php">
				<?php settings_fields( 'aaweb_site_credits_group' ); ?>

				<table class="form-table" role="presentation">
					<?php
					self::admin_text_field( 'copyright_text', __( 'Copyright text', 'aaweb-site-credits' ), 'Copyright' );
					self::admin_text_field( 'site_name', __( 'Site / company name', 'aaweb-site-credits' ), get_bloginfo( 'name' ) );
					self::admin_text_field( 'rights_text', __( 'Rights text', 'aaweb-site-credits' ), 'All Rights Reserved.' );
					?>
					<tr>
						<th scope="row"><?php esc_html_e( 'Credit attribution', 'aaweb-site-credits' ); ?></th>
						<td>
							<label>
								<input type="checkbox" name="<?php echo esc_attr( self::OPTION_KEY ); ?>[enable_credit]" value="1" <?php checked( $options['enable_credit'], '1' ); ?>>
								<?php esc_html_e( 'Display optional developer credit on the public site', 'aaweb-site-credits' ); ?>
							</label>
							<p class="description"><?php esc_html_e( 'The developer credit is disabled by default and will only appear when this box is checked.', 'aaweb-site-credits' ); ?></p>
						</td>
					</tr>
					<?php
					self::admin_text_field( 'powered_by_text', __( 'Powered by text', 'aaweb-site-credits' ), 'Powered by' );
					self::admin_text_field( 'developer_name', __( 'Developer / brand name', 'aaweb-site-credits' ), 'Developer name' );
					?>
					<tr>
						<th scope="row"><label for="aaweb_developer_url"><?php esc_html_e( 'Developer / brand URL', 'aaweb-site-credits' ); ?></label></th>
						<td>
							<input type="url" id="aaweb_developer_url" class="regular-text" name="<?php echo esc_attr( self::OPTION_KEY ); ?>[developer_url]" value="<?php echo esc_url( $options['developer_url'] ); ?>" placeholder="https://antoapweb.gr">
						</td>
					</tr>

					<?php self::admin_text_field( 'separator', __( 'Separator', 'aaweb-site-credits' ), '|' ); ?>

					<tr>
						<th scope="row"><?php esc_html_e( 'Options', 'aaweb-site-credits' ); ?></th>
						<td>
							<label>
								<input type="checkbox" name="<?php echo esc_attr( self::OPTION_KEY ); ?>[show_symbol]" value="1" <?php checked( $options['show_symbol'], '1' ); ?>>
								<?php esc_html_e( 'Show © symbol', 'aaweb-site-credits' ); ?>
							</label>
							<br>
							<label>
								<input type="checkbox" name="<?php echo esc_attr( self::OPTION_KEY ); ?>[open_new_tab]" value="1" <?php checked( $options['open_new_tab'], '1' ); ?>>
								<?php esc_html_e( 'Open developer link in a new tab', 'aaweb-site-credits' ); ?>
							</label>
						</td>
					</tr>
				</table>

				<?php submit_button(); ?>
			</form>

			<hr>
			<h2><?php esc_html_e( 'Usage', 'aaweb-site-credits' ); ?></h2>
			<p><strong><?php esc_html_e( 'Elementor:', 'aaweb-site-credits' ); ?></strong> <?php esc_html_e( 'Search for the AAWEB Site Credits widget.', 'aaweb-site-credits' ); ?></p>
			<p><strong><?php esc_html_e( 'Shortcode:', 'aaweb-site-credits' ); ?></strong> <code>[aaweb_site_credits]</code></p>
			<p><strong><?php esc_html_e( 'Gutenberg:', 'aaweb-site-credits' ); ?></strong> <?php esc_html_e( 'Add the AAWEB Site Credits block.', 'aaweb-site-credits' ); ?></p>
		</div>
		<?php
	}

	/**
	 * Render admin text field.
	 *
	 * @param string $key Option key.
	 * @param string $label Field label.
	 * @param string $placeholder Placeholder.
	 * @return void
	 */
	private static function admin_text_field( string $key, string $label, string $placeholder = '' ): void {
		$options = self::get_options();
		?>
		<tr>
			<th scope="row"><label for="aaweb_<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $label ); ?></label></th>
			<td>
				<input type="text" id="aaweb_<?php echo esc_attr( $key ); ?>" class="regular-text" name="<?php echo esc_attr( self::OPTION_KEY ); ?>[<?php echo esc_attr( $key ); ?>]" value="<?php echo esc_attr( $options[ $key ] ?? '' ); ?>" placeholder="<?php echo esc_attr( $placeholder ); ?>">
			</td>
		</tr>
		<?php
	}

	/**
	 * Register frontend CSS.
	 *
	 * @return void
	 */
	public static function register_front_assets(): void {
		wp_register_style(
			'aaweb-site-credits',
			AAWEB_SITE_CREDITS_URL . 'assets/site-credits.css',
			array(),
			AAWEB_SITE_CREDITS_VERSION
		);
	}

	/**
	 * Shortcode output.
	 *
	 * @param array<string,mixed> $atts Shortcode attributes.
	 * @return string
	 */
	public static function shortcode( array $atts = array() ): string {
		wp_enqueue_style( 'aaweb-site-credits' );

		$atts = shortcode_atts(
			array(
				'align' => 'center',
				'class' => '',
			),
			$atts,
			'aaweb_site_credits'
		);

		return self::render_output( array(), $atts );
	}

	/**
	 * Render frontend output.
	 *
	 * @param array<string,mixed> $settings Custom settings.
	 * @param array<string,mixed> $args Render arguments.
	 * @return string
	 */
	public static function render_output( array $settings = array(), array $args = array() ): string {
		$options = self::get_options();
		$data    = wp_parse_args( $settings, $options );

		$year         = date_i18n( 'Y' );
		$show_symbol  = self::bool_value( $data['show_symbol'] ?? '1' );
		$open_new_tab = self::bool_value( $data['open_new_tab'] ?? '1' );

		$copyright_text  = trim( (string) ( $data['copyright_text'] ?? 'Copyright' ) );
		$site_name       = trim( (string) ( $data['site_name'] ?? '' ) );
		$rights_text     = trim( (string) ( $data['rights_text'] ?? '' ) );
		$enable_credit   = self::bool_value( $data['enable_credit'] ?? '0' );
		$powered_by_text = $enable_credit ? trim( (string) ( $data['powered_by_text'] ?? '' ) ) : '';
		$developer_name  = $enable_credit ? trim( (string) ( $data['developer_name'] ?? '' ) ) : '';
		$developer_url   = trim( (string) ( $data['developer_url'] ?? '' ) );
		$separator       = trim( (string) ( $data['separator'] ?? '|' ) );

		$allowed_alignments = array( 'left', 'center', 'right' );
		$align              = ! empty( $args['align'] ) && in_array( $args['align'], $allowed_alignments, true ) ? $args['align'] : 'center';
		$class              = ! empty( $args['class'] ) ? sanitize_html_class( $args['class'] ) : '';
		$style              = self::build_inline_style( $args );

		$classes = array( 'aaweb-site-credits', 'aaweb-site-credits--align-' . $align );

		if ( $class ) {
			$classes[] = $class;
		}

		$left_parts = array();

		if ( '' !== $copyright_text ) {
			$left_parts[] = $copyright_text;
		}

		if ( $show_symbol ) {
			$left_parts[] = '©';
		}

		$left_parts[] = $year;

		if ( '' !== $site_name ) {
			$left_parts[] = $site_name;
		}

		if ( '' !== $rights_text ) {
			$left_parts[] = $rights_text;
		}

		$show_credit    = $enable_credit && ( '' !== $powered_by_text || '' !== $developer_name );
		$developer_html = esc_html( $developer_name );

		if ( '' !== $developer_url && '' !== $developer_name ) {
			$target         = $open_new_tab ? ' target="_blank" rel="noopener noreferrer"' : '';
			$developer_html = sprintf(
				'<a class="aaweb-site-credits__developer-link" href="%s"%s>%s</a>',
				esc_url( $developer_url ),
				$target,
				esc_html( $developer_name )
			);
		}

		ob_start();
		?>
		<div class="<?php echo esc_attr( implode( ' ', array_filter( $classes ) ) ); ?>"<?php echo $style ? ' style="' . esc_attr( $style ) . '"' : ''; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
			<span class="aaweb-site-credits__left"><?php echo esc_html( implode( ' ', array_filter( $left_parts ) ) ); ?></span>

			<?php if ( $show_credit && '' !== $separator ) : ?>
				<span class="aaweb-site-credits__separator"><?php echo esc_html( $separator ); ?></span>
			<?php endif; ?>

			<?php if ( $show_credit ) : ?>
				<span class="aaweb-site-credits__powered">
					<?php echo esc_html( $powered_by_text ); ?>
					<?php echo '' !== $developer_name ? wp_kses_post( ' ' . $developer_html ) : ''; ?>
				</span>
			<?php endif; ?>
		</div>
		<?php

		return trim( ob_get_clean() );
	}


	/**
	 * Build safe inline style from block attributes.
	 *
	 * @param array<string,mixed> $args Render arguments.
	 * @return string
	 */
	private static function build_inline_style( array $args ): string {
		$styles = array();

		$color_props = array(
			'textColor'             => '--aaweb-site-credits-text-color',
			'backgroundColor'       => '--aaweb-site-credits-background-color',
			'borderColor'           => '--aaweb-site-credits-border-color',
			'linkColor'             => '--aaweb-site-credits-link-color',
			'textHoverColor'        => '--aaweb-site-credits-hover-text-color',
			'linkHoverColor'        => '--aaweb-site-credits-hover-link-color',
			'backgroundHoverColor'  => '--aaweb-site-credits-hover-background-color',
			'borderHoverColor'      => '--aaweb-site-credits-hover-border-color',
		);

		foreach ( $color_props as $key => $property ) {
			if ( ! empty( $args[ $key ] ) ) {
				$color = sanitize_hex_color( (string) $args[ $key ] );

				if ( $color ) {
					$styles[] = $property . ':' . $color;
				}
			}
		}

		$pixel_props = array(
			'fontSize'      => 'font-size',
			'paddingTop'    => 'padding-top',
			'paddingRight'  => 'padding-right',
			'paddingBottom' => 'padding-bottom',
			'paddingLeft'   => 'padding-left',
			'marginTop'     => 'margin-top',
			'marginBottom'  => 'margin-bottom',
			'borderRadius'  => 'border-radius',
			'borderWidth'   => 'border-width',
			'letterSpacing' => 'letter-spacing',
		);

		foreach ( $pixel_props as $key => $property ) {
			if ( isset( $args[ $key ] ) && '' !== $args[ $key ] ) {
				$value = absint( $args[ $key ] );

				if ( $value > 0 ) {
					$styles[] = $property . ':' . $value . 'px';
				}
			}
		}

		if ( ! empty( $args['borderWidth'] ) && absint( $args['borderWidth'] ) > 0 ) {
			$styles[] = 'border-style:solid';
		}

		if ( isset( $args['transitionDuration'] ) && '' !== $args['transitionDuration'] ) {
			$duration = absint( $args['transitionDuration'] );

			if ( $duration >= 0 && $duration <= 5000 ) {
				$styles[] = '--aaweb-site-credits-transition-duration:' . $duration . 'ms';
			}
		}

		if ( ! empty( $args['lineHeight'] ) ) {
			$line_height = (float) $args['lineHeight'];

			if ( $line_height > 0 && $line_height <= 5 ) {
				$styles[] = 'line-height:' . rtrim( rtrim( number_format( $line_height, 2, '.', '' ), '0' ), '.' );
			}
		}

		if ( ! empty( $args['textTransform'] ) ) {
			$text_transform = (string) $args['textTransform'];

			if ( in_array( $text_transform, array( 'none', 'uppercase', 'lowercase', 'capitalize' ), true ) ) {
				$styles[] = 'text-transform:' . $text_transform;
			}
		}

		if ( ! empty( $args['fontWeight'] ) ) {
			$font_weight = (string) $args['fontWeight'];

			if ( preg_match( '/^(300|400|500|600|700|800)$/', $font_weight ) ) {
				$styles[] = 'font-weight:' . $font_weight;
			}
		}

		if ( ! empty( $args['customCss'] ) ) {
			$custom_css = self::sanitize_css_properties( (string) $args['customCss'] );

			if ( '' !== $custom_css ) {
				$styles[] = $custom_css;
			}
		}

		return implode( ';', array_filter( $styles ) );
	}

	/**
	 * Sanitize advanced CSS property list.
	 *
	 * Allows simple CSS declarations only. Selectors and braces are removed.
	 *
	 * @param string $css CSS declarations.
	 * @return string
	 */
	private static function sanitize_css_properties( string $css ): string {
		$css = wp_strip_all_tags( $css );
		$css = str_replace( array( '{', '}' ), '', $css );
		$css = preg_replace( '/expression\s*\(|javascript\s*:|data\s*:/i', '', $css );

		if ( ! is_string( $css ) ) {
			return '';
		}

		$declarations = array();

		foreach ( explode( ';', $css ) as $declaration ) {
			$declaration = trim( $declaration );

			if ( '' === $declaration || false === strpos( $declaration, ':' ) ) {
				continue;
			}

			list( $property, $value ) = array_map( 'trim', explode( ':', $declaration, 2 ) );

			if ( ! preg_match( '/^[a-zA-Z\-]+$/', $property ) || '' === $value ) {
				continue;
			}

			$value = preg_replace( '/[^#%.,()\sa-zA-Z0-9_\-\/]/', '', $value );

			if ( is_string( $value ) && '' !== trim( $value ) ) {
				$declarations[] = strtolower( $property ) . ':' . trim( $value );
			}
		}

		return implode( ';', $declarations );
	}

	/**
	 * Convert mixed switcher values to bool.
	 *
	 * @param mixed $value Value.
	 * @return bool
	 */
	private static function bool_value( $value ): bool {
		return in_array( $value, array( true, 1, '1', 'yes', 'true', 'on' ), true );
	}

	/**
	 * Register Elementor category.
	 *
	 * @param object $elements_manager Elementor elements manager.
	 * @return void
	 */
	public static function register_elementor_category( $elements_manager ): void {
		$elements_manager->add_category(
			'aaweb-widgets',
			array(
				'title' => esc_html__( 'AAWEB Widgets', 'aaweb-site-credits' ),
				'icon'  => 'fa fa-plug',
			)
		);
	}

	/**
	 * Register Elementor widget.
	 *
	 * @param object $widgets_manager Elementor widgets manager.
	 * @return void
	 */
	public static function register_elementor_widget( $widgets_manager ): void {
		require_once AAWEB_SITE_CREDITS_DIR . 'includes/elementor/class-aaweb-site-credits-widget.php';

		if ( class_exists( 'AAWEB_Site_Credits_Elementor_Widget' ) ) {
			$widgets_manager->register( new AAWEB_Site_Credits_Elementor_Widget() );
		}
	}


	/**
	 * Get Gutenberg block attributes.
	 *
	 * @return array<string,array<string,mixed>>
	 */
	private static function get_block_attributes(): array {
		return array(
			'align'           => array(
				'type'    => 'string',
				'default' => 'center',
			),
			'className'       => array(
				'type'    => 'string',
				'default' => '',
			),
			'textColor'       => array(
				'type'    => 'string',
				'default' => '',
			),
			'linkColor'       => array(
				'type'    => 'string',
				'default' => '',
			),
			'backgroundColor' => array(
				'type'    => 'string',
				'default' => '',
			),
			'borderColor'     => array(
				'type'    => 'string',
				'default' => '',
			),
			'textHoverColor'  => array(
				'type'    => 'string',
				'default' => '',
			),
			'linkHoverColor'  => array(
				'type'    => 'string',
				'default' => '',
			),
			'backgroundHoverColor' => array(
				'type'    => 'string',
				'default' => '',
			),
			'borderHoverColor' => array(
				'type'    => 'string',
				'default' => '',
			),
			'fontSize'        => array(
				'type'    => 'number',
				'default' => 14,
			),
			'fontWeight'      => array(
				'type'    => 'string',
				'default' => '',
			),
			'textTransform'   => array(
				'type'    => 'string',
				'default' => '',
			),
			'lineHeight'      => array(
				'type'    => 'number',
				'default' => 0,
			),
			'letterSpacing'   => array(
				'type'    => 'number',
				'default' => 0,
			),
			'paddingTop'      => array(
				'type'    => 'number',
				'default' => 0,
			),
			'paddingRight'    => array(
				'type'    => 'number',
				'default' => 0,
			),
			'paddingBottom'   => array(
				'type'    => 'number',
				'default' => 0,
			),
			'paddingLeft'     => array(
				'type'    => 'number',
				'default' => 0,
			),
			'marginTop'       => array(
				'type'    => 'number',
				'default' => 0,
			),
			'marginBottom'    => array(
				'type'    => 'number',
				'default' => 0,
			),
			'borderRadius'    => array(
				'type'    => 'number',
				'default' => 0,
			),
			'borderWidth'     => array(
				'type'    => 'number',
				'default' => 0,
			),
			'transitionDuration' => array(
				'type'    => 'number',
				'default' => 200,
			),
			'customCss'       => array(
				'type'    => 'string',
				'default' => '',
			),
		);
	}

	/**
	 * Register Gutenberg block.
	 *
	 * @return void
	 */
	public static function register_block(): void {
		wp_register_style(
			'aaweb-site-credits',
			AAWEB_SITE_CREDITS_URL . 'assets/site-credits.css',
			array(),
			AAWEB_SITE_CREDITS_VERSION
		);

		wp_register_script(
			'aaweb-site-credits-block',
			AAWEB_SITE_CREDITS_URL . 'assets/block.js',
			array( 'wp-blocks', 'wp-element', 'wp-components', 'wp-block-editor', 'wp-server-side-render', 'wp-i18n' ),
			AAWEB_SITE_CREDITS_VERSION,
			true
		);

		register_block_type(
			'aaweb/site-credits',
			array(
				'editor_script'   => 'aaweb-site-credits-block',
				'style'           => 'aaweb-site-credits',
				'render_callback' => array( __CLASS__, 'render_block' ),
				'attributes'      => self::get_block_attributes(),
				'supports'        => array(
					'customClassName' => true,
				),
			)
		);
	}

	/**
	 * Render dynamic block.
	 *
	 * @param array<string,mixed> $attributes Block attributes.
	 * @return string
	 */
	public static function render_block( array $attributes ): string {
		wp_enqueue_style( 'aaweb-site-credits' );

		$args = $attributes;
		$args['class'] = $attributes['className'] ?? '';

		return self::render_output( array(), $args );
	}
}
