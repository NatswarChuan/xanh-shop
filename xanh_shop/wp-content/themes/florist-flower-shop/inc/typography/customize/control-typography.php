<?php
/**
 * Typography control class.
 *
 * @since  1.0.0
 * @access public
 */

class Florist_Flower_Shop_Control_Typography extends WP_Customize_Control {

	/**
	 * The type of customize control being rendered.
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    string
	 */
	public $type = 'florist-flower-shop-typography';

	/**
	 * Array 
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    string
	 */
	public $l10n = array();

	/**
	 * Set up our control.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  object  $manager
	 * @param  string  $id
	 * @param  array   $args
	 * @return void
	 */
	public function __construct( $manager, $id, $args = array() ) {

		// Let the parent class do its thing.
		parent::__construct( $manager, $id, $args );

		// Make sure we have labels.
		$this->l10n = wp_parse_args(
			$this->l10n,
			array(
				'color'       => esc_html__( 'Font Color', 'florist-flower-shop' ),
				'family'      => esc_html__( 'Font Family', 'florist-flower-shop' ),
				'size'        => esc_html__( 'Font Size',   'florist-flower-shop' ),
				'weight'      => esc_html__( 'Font Weight', 'florist-flower-shop' ),
				'style'       => esc_html__( 'Font Style',  'florist-flower-shop' ),
				'line_height' => esc_html__( 'Line Height', 'florist-flower-shop' ),
				'letter_spacing' => esc_html__( 'Letter Spacing', 'florist-flower-shop' ),
			)
		);
	}

	/**
	 * Enqueue scripts/styles.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function enqueue() {
		wp_enqueue_script( 'florist-flower-shop-ctypo-customize-controls' );
		wp_enqueue_style(  'florist-flower-shop-ctypo-customize-controls' );
	}

	/**
	 * Add custom parameters to pass to the JS via JSON.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function to_json() {
		parent::to_json();

		// Loop through each of the settings and set up the data for it.
		foreach ( $this->settings as $setting_key => $setting_id ) {

			$this->json[ $setting_key ] = array(
				'link'  => $this->get_link( $setting_key ),
				'value' => $this->value( $setting_key ),
				'label' => isset( $this->l10n[ $setting_key ] ) ? $this->l10n[ $setting_key ] : ''
			);

			if ( 'family' === $setting_key )
				$this->json[ $setting_key ]['choices'] = $this->get_font_families();

			elseif ( 'weight' === $setting_key )
				$this->json[ $setting_key ]['choices'] = $this->get_font_weight_choices();

			elseif ( 'style' === $setting_key )
				$this->json[ $setting_key ]['choices'] = $this->get_font_style_choices();
		}
	}

	/**
	 * Underscore JS template to handle the control's output.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function content_template() { ?>

		<# if ( data.label ) { #>
			<span class="customize-control-title">{{ data.label }}</span>
		<# } #>

		<# if ( data.description ) { #>
			<span class="description customize-control-description">{{{ data.description }}}</span>
		<# } #>

		<ul>

		<# if ( data.family && data.family.choices ) { #>

			<li class="typography-font-family">

				<# if ( data.family.label ) { #>
					<span class="customize-control-title">{{ data.family.label }}</span>
				<# } #>

				<select {{{ data.family.link }}}>

					<# _.each( data.family.choices, function( label, choice ) { #>
						<option value="{{ choice }}" <# if ( choice === data.family.value ) { #> selected="selected" <# } #>>{{ label }}</option>
					<# } ) #>

				</select>
			</li>
		<# } #>

		<# if ( data.weight && data.weight.choices ) { #>

			<li class="typography-font-weight">

				<# if ( data.weight.label ) { #>
					<span class="customize-control-title">{{ data.weight.label }}</span>
				<# } #>

				<select {{{ data.weight.link }}}>

					<# _.each( data.weight.choices, function( label, choice ) { #>

						<option value="{{ choice }}" <# if ( choice === data.weight.value ) { #> selected="selected" <# } #>>{{ label }}</option>

					<# } ) #>

				</select>
			</li>
		<# } #>

		<# if ( data.style && data.style.choices ) { #>

			<li class="typography-font-style">

				<# if ( data.style.label ) { #>
					<span class="customize-control-title">{{ data.style.label }}</span>
				<# } #>

				<select {{{ data.style.link }}}>

					<# _.each( data.style.choices, function( label, choice ) { #>

						<option value="{{ choice }}" <# if ( choice === data.style.value ) { #> selected="selected" <# } #>>{{ label }}</option>

					<# } ) #>

				</select>
			</li>
		<# } #>

		<# if ( data.size ) { #>

			<li class="typography-font-size">

				<# if ( data.size.label ) { #>
					<span class="customize-control-title">{{ data.size.label }} (px)</span>
				<# } #>

				<input type="number" min="1" {{{ data.size.link }}} value="{{ data.size.value }}" />

			</li>
		<# } #>

		<# if ( data.line_height ) { #>

			<li class="typography-line-height">

				<# if ( data.line_height.label ) { #>
					<span class="customize-control-title">{{ data.line_height.label }} (px)</span>
				<# } #>

				<input type="number" min="1" {{{ data.line_height.link }}} value="{{ data.line_height.value }}" />

			</li>
		<# } #>

		<# if ( data.letter_spacing ) { #>

			<li class="typography-letter-spacing">

				<# if ( data.letter_spacing.label ) { #>
					<span class="customize-control-title">{{ data.letter_spacing.label }} (px)</span>
				<# } #>

				<input type="number" min="1" {{{ data.letter_spacing.link }}} value="{{ data.letter_spacing.value }}" />

			</li>
		<# } #>

		</ul>
	<?php }

	/**
	 * Returns the available fonts.  Fonts should have available weights, styles, and subsets.
	 *
	 * @todo Integrate with Google fonts.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return array
	 */
	public function get_fonts() { return array(); }

	/**
	 * Returns the available font families.
	 *
	 * @todo Pull families from `get_fonts()`.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return array
	 */
	function get_font_families() {

		return array(
			'' => __( 'No Fonts', 'florist-flower-shop' ),
        'Abril Fatface' => __( 'Abril Fatface', 'florist-flower-shop' ),
        'Acme' => __( 'Acme', 'florist-flower-shop' ),
        'Anton' => __( 'Anton', 'florist-flower-shop' ),
        'Architects Daughter' => __( 'Architects Daughter', 'florist-flower-shop' ),
        'Arimo' => __( 'Arimo', 'florist-flower-shop' ),
        'Arsenal' => __( 'Arsenal', 'florist-flower-shop' ),
        'Arvo' => __( 'Arvo', 'florist-flower-shop' ),
        'Alegreya' => __( 'Alegreya', 'florist-flower-shop' ),
        'Alfa Slab One' => __( 'Alfa Slab One', 'florist-flower-shop' ),
        'Averia Serif Libre' => __( 'Averia Serif Libre', 'florist-flower-shop' ),
        'Bangers' => __( 'Bangers', 'florist-flower-shop' ),
        'Boogaloo' => __( 'Boogaloo', 'florist-flower-shop' ),
        'Bad Script' => __( 'Bad Script', 'florist-flower-shop' ),
        'Bitter' => __( 'Bitter', 'florist-flower-shop' ),
        'Bree Serif' => __( 'Bree Serif', 'florist-flower-shop' ),
        'BenchNine' => __( 'BenchNine', 'florist-flower-shop' ),
        'Cabin' => __( 'Cabin', 'florist-flower-shop' ),
        'Cardo' => __( 'Cardo', 'florist-flower-shop' ),
        'Courgette' => __( 'Courgette', 'florist-flower-shop' ),
        'Cherry Swash' => __( 'Cherry Swash', 'florist-flower-shop' ),
        'Cormorant Garamond' => __( 'Cormorant Garamond', 'florist-flower-shop' ),
        'Crimson Text' => __( 'Crimson Text', 'florist-flower-shop' ),
        'Cuprum' => __( 'Cuprum', 'florist-flower-shop' ),
        'Cookie' => __( 'Cookie', 'florist-flower-shop' ),
        'Chewy' => __( 'Chewy', 'florist-flower-shop' ),
        'Days One' => __( 'Days One', 'florist-flower-shop' ),
        'Dosis' => __( 'Dosis', 'florist-flower-shop' ),
        'Droid Sans' => __( 'Droid Sans', 'florist-flower-shop' ),
        'Economica' => __( 'Economica', 'florist-flower-shop' ),
        'Fredoka One' => __( 'Fredoka One', 'florist-flower-shop' ),
        'Fjalla One' => __( 'Fjalla One', 'florist-flower-shop' ),
        'Francois One' => __( 'Francois One', 'florist-flower-shop' ),
        'Frank Ruhl Libre' => __( 'Frank Ruhl Libre', 'florist-flower-shop' ),
        'Gloria Hallelujah' => __( 'Gloria Hallelujah', 'florist-flower-shop' ),
        'Great Vibes' => __( 'Great Vibes', 'florist-flower-shop' ),
        'Handlee' => __( 'Handlee', 'florist-flower-shop' ),
        'Hammersmith One' => __( 'Hammersmith One', 'florist-flower-shop' ),
        'Inconsolata' => __( 'Inconsolata', 'florist-flower-shop' ),
        'Indie Flower' => __( 'Indie Flower', 'florist-flower-shop' ),
        'IM Fell English SC' => __( 'IM Fell English SC', 'florist-flower-shop' ),
        'Julius Sans One' => __( 'Julius Sans One', 'florist-flower-shop' ),
        'Josefin Slab' => __( 'Josefin Slab', 'florist-flower-shop' ),
        'Josefin Sans' => __( 'Josefin Sans', 'florist-flower-shop' ),
        'Kanit' => __( 'Kanit', 'florist-flower-shop' ),
        'Lobster' => __( 'Lobster', 'florist-flower-shop' ),
        'Lato' => __( 'Lato', 'florist-flower-shop' ),
        'Lora' => __( 'Lora', 'florist-flower-shop' ),
        'Libre Baskerville' => __( 'Libre Baskerville', 'florist-flower-shop' ),
        'Lobster Two' => __( 'Lobster Two', 'florist-flower-shop' ),
        'Merriweather' => __( 'Merriweather', 'florist-flower-shop' ),
        'Monda' => __( 'Monda', 'florist-flower-shop' ),
        'Montserrat' => __( 'Montserrat', 'florist-flower-shop' ),
        'Muli' => __( 'Muli', 'florist-flower-shop' ),
        'Marck Script' => __( 'Marck Script', 'florist-flower-shop' ),
        'Noto Serif' => __( 'Noto Serif', 'florist-flower-shop' ),
        'Open Sans' => __( 'Open Sans', 'florist-flower-shop' ),
        'Overpass' => __( 'Overpass', 'florist-flower-shop' ),
        'Overpass Mono' => __( 'Overpass Mono', 'florist-flower-shop' ),
        'Oxygen' => __( 'Oxygen', 'florist-flower-shop' ),
        'Orbitron' => __( 'Orbitron', 'florist-flower-shop' ),
        'Patua One' => __( 'Patua One', 'florist-flower-shop' ),
        'Pacifico' => __( 'Pacifico', 'florist-flower-shop' ),
        'Padauk' => __( 'Padauk', 'florist-flower-shop' ),
        'Playball' => __( 'Playball', 'florist-flower-shop' ),
        'Playfair Display' => __( 'Playfair Display', 'florist-flower-shop' ),
        'PT Sans' => __( 'PT Sans', 'florist-flower-shop' ),
        'Philosopher' => __( 'Philosopher', 'florist-flower-shop' ),
        'Permanent Marker' => __( 'Permanent Marker', 'florist-flower-shop' ),
        'Poiret One' => __( 'Poiret One', 'florist-flower-shop' ),
        'Quicksand' => __( 'Quicksand', 'florist-flower-shop' ),
        'Quattrocento Sans' => __( 'Quattrocento Sans', 'florist-flower-shop' ),
        'Raleway' => __( 'Raleway', 'florist-flower-shop' ),
        'Rubik' => __( 'Rubik', 'florist-flower-shop' ),
        'Rokkitt' => __( 'Rokkitt', 'florist-flower-shop' ),
        'Russo One' => __( 'Russo One', 'florist-flower-shop' ),
        'Righteous' => __( 'Righteous', 'florist-flower-shop' ),
        'Slabo' => __( 'Slabo', 'florist-flower-shop' ),
        'Source Sans Pro' => __( 'Source Sans Pro', 'florist-flower-shop' ),
        'Shadows Into Light Two' => __( 'Shadows Into Light Two', 'florist-flower-shop'),
        'Shadows Into Light' => __( 'Shadows Into Light', 'florist-flower-shop' ),
        'Sacramento' => __( 'Sacramento', 'florist-flower-shop' ),
        'Shrikhand' => __( 'Shrikhand', 'florist-flower-shop' ),
        'Tangerine' => __( 'Tangerine', 'florist-flower-shop' ),
        'Ubuntu' => __( 'Ubuntu', 'florist-flower-shop' ),
        'VT323' => __( 'VT323', 'florist-flower-shop' ),
        'Varela Round' => __( 'Varela Round', 'florist-flower-shop' ),
        'Vampiro One' => __( 'Vampiro One', 'florist-flower-shop' ),
        'Vollkorn' => __( 'Vollkorn', 'florist-flower-shop' ),
        'Volkhov' => __( 'Volkhov', 'florist-flower-shop' ),
        'Yanone Kaffeesatz' => __( 'Yanone Kaffeesatz', 'florist-flower-shop' )
		);
	}

	/**
	 * Returns the available font weights.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return array
	 */
	public function get_font_weight_choices() {

		return array(
			'' => esc_html__( 'No Fonts weight', 'florist-flower-shop' ),
			'100' => esc_html__( 'Thin',       'florist-flower-shop' ),
			'300' => esc_html__( 'Light',      'florist-flower-shop' ),
			'400' => esc_html__( 'Normal',     'florist-flower-shop' ),
			'500' => esc_html__( 'Medium',     'florist-flower-shop' ),
			'700' => esc_html__( 'Bold',       'florist-flower-shop' ),
			'900' => esc_html__( 'Ultra Bold', 'florist-flower-shop' ),
		);
	}

	/**
	 * Returns the available font styles.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return array
	 */
	public function get_font_style_choices() {

		return array(
			'' => esc_html__( 'No Fonts Style', 'florist-flower-shop' ),
			'normal'  => esc_html__( 'Normal', 'florist-flower-shop' ),
			'italic'  => esc_html__( 'Italic', 'florist-flower-shop' ),
			'oblique' => esc_html__( 'Oblique', 'florist-flower-shop' )
		);
	}
}
