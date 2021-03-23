<?php

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class Stamp_IC_WC_Admin_Notice {

	/**
	 * The notice type. Can be notice, notice-info, updated, error or a custom notice type.
	 *
	 * @var string
	 */
	protected $type;

	/**
	 * The notice heading. Optional property.
	 *
	 * @var string
	 */
	protected $heading;

	/**
	 * The notice's main content.
	 *
	 * @var string
	 */
	protected $content;

	/**
	 * The notice's content type. Can be 'simple' or 'html'.
	 *
	 * @var string
	 */
	protected $content_type;

	protected $attributes;

	/**
	 * The URL used to dismiss the notice. Optional property.
	 *
	 * @var string
	 */
	protected $dismiss_url;

	/**
	 * A list of actions the user can take
	 * @var array
	 */
	protected $actions;

	/**
	 * Constructor.
	 *
	 * @param string $type The notice type. Can be notice, notice-info, updated, error or a custom notice type.
	 * @param array  $attributes The container div's attributes. Optional.
	 * @param string $dismiss_url The URL used to dismiss the notice. Optional.
	 */
	public function __construct( $type, array $attributes = array(), $dismiss_url = '' ) {
		$this->type        = $type;
		$this->attributes  = $attributes;
		$this->dismiss_url = $dismiss_url;
	}

	public function display() {
		if ( 'admin_notices' !== current_filter() ) {
			add_action( 'admin_notices', array( $this, __FUNCTION__ ) );
			return;
		}

		$template_name = 'html-admin-notice.php';
		$template_path = __DIR__ . '/views/';

		if ( function_exists( 'wc_get_template' ) ) {
			wc_get_template( $template_name, array( 'notice' => $this ), '', $template_path );
		} else {
			$notice = $this;
			include( $template_path . $template_name );
		}
	}

	/**
	 * Whether the admin notice is dismissible.
	 *
	 * @return boolean
	 */
	public function is_dismissible() {
		return ! empty( $this->dismiss_url );
	}

	/**
	 * Whether the admin notice has a heading or not.
	 * 
	 * @return boolean
	 */
	public function has_heading() {
		return ! empty( $this->heading );
	}

	/**
	 * Whether the admin notice has actions or not.
	 * 
	 * @return boolean
	 */
	public function has_actions() {
		return ! empty( $this->actions ) && is_array( $this->actions );
	}

	/* Printers */

	/**
	 * Print the notice's heading.
	 *
	 */
	public function print_heading() {
		echo esc_html( $this->heading );
	}

	/**
	 * Get the notice's content.
	 *
	 * Will wrap simple notices in paragraph elements (<p></p>) for correct styling and print HTML notices unchanged.
	 *
	 */
	public function print_content() {
		switch ( $this->content_type ) {
			case 'simple':
				echo '<p>' . wp_kses_post( $this->content ) . '</p>';
				break;
			case 'html':
				echo wp_kses_post( $this->content );
				break;
			case 'template':
				wc_get_template( $this->content['template_name'], $this->content['args'], '', $this->content['template_path'] );
				break;
		}
	}

	/**
	 * Print the notice's attributes.
	 *
	 * Turns the attributes array into 'id="id" class="class class class"' strings.
	 *
	 */
	public function print_attributes() {
		$attributes            = $this->attributes;
		$attributes['class'][] = $this->type;

		if ( $this->is_dismissible() ) {
			$attributes['style'][] = 'position: relative;';
		}

		foreach ( $attributes as $attribute => $values ) {
			$attributes[ $attribute ] = $attribute . '="' . implode( ' ', array_map( 'esc_attr', $values ) ) . '"';
		}

		echo wp_kses_post( implode( ' ', $attributes ) );
	}

	/**
	 * Print the notice's dismiss URL.
	 *
	 */
	public function print_dismiss_url() {
		echo esc_attr( $this->dismiss_url );
	}

	/* Getters */

	/**
	 * Get the notice's actions.
	 * 
	 * @return array
	 */
	public function get_actions() {
		return $this->actions;
	}

	/* Setters */

	/**
	 * Set the notice's content to a simple string.
	 *
	 * @param string $content The notice content.
	 */
	public function set_simple_content( $content ) {
		$this->content_type = 'simple';
		$this->content      = $content;
	}

	/**
	 * Set the notice's content to a string containing HTML elements.
	 *
	 * @param string $html The notice content.
	 */
	public function set_html_content( $html ) {
		$this->content_type = 'html';
		$this->content      = $html;
	}

	/**
	 * Set the notice's content to a string containing HTML elements.
	 * 
	 * @param string $template_name Template name.
	 * @param string $template_path Template path.
	 * @param array  $args          Arguments. (default: array).
	 */
	public function set_content_template( $template_name, $template_path, $args = array() ) {
		$this->content_type = 'template';
		$this->content      = array(
			'template_name' => $template_name,
			'template_path' => $template_path,
			'args'          => $args,
		);
	}

	/**
	 * Set actions the user can make in response to this notice.
	 * 
	 * @param array $actions The actions the user can make. Example format:
	 * array(
	 *    array(
	 *        'name'  => 'The actions's name', // This arg will appear as the button text.
	 *        'url'   => 'url', // The url the user will be directed to if clicked.
	 *        'class' => 'class string', // The class attribute string used in the link element. Optional. Will default to 'docs button' - a plain button.
	 *    )
	 * )
	 */
	public function set_actions( array $actions ) {
		$this->actions = $actions;
	}

	/**
	 * Set notice's heading. If set this will appear at the top of the notice wrapped in a h2 element.
	 * 
	 * @param string $heading The notice heading.
	 */
	public function set_heading( $heading ) {
		$this->heading = $heading;
	}
}
