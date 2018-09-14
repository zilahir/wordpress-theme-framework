<?php

namespace JustCoded\WP\Framework\ACF;


use StoutLogic\AcfBuilder\FieldBuilder;
use StoutLogic\AcfBuilder\FieldsBuilder;
use JustCoded\WP\Framework\Objects\Singleton;

/**
 * Class ACF_Register
 *
 * @property FieldBuilder[] $fields
 */
abstract class ACF_Register {
	use Singleton;
	use Has_ACF_Fields;

	/**
	 * Post Type for hide content.
	 *
	 * @var $hide_content
	 */
	public $hide_content;

	/**
	 * ACF_Register constructor.
	 * - run init method to set fields configuration.
	 * - define acf hook to register fields
	 */
	protected function __construct() {
		$this->init();
		// init WordPress hook for hide content box.
		add_action( 'init', function() {
			remove_post_type_support( $this->hide_content, 'editor' );
		});
		// init ACF hook for register fields.
		add_action( 'acf/init', array( $this, 'register' ) );
	}

	/**
	 * Init fields configuration method
	 *
	 * @return void
	 */
	abstract public function init();

	/**
	 * Register fields with ACF functions.
	 */
	public function register() {
		foreach ( $this->fields as $field ) {
			acf_add_local_field_group( $field->build() );
		}
	}

	/**
	 * ACF add_options_page function wrapper to check for exists.
	 *
	 * @param string $name Page name.
	 *
	 * @return bool
	 */
	public function add_options_page( $name ) {
		if ( function_exists( 'acf_add_options_page' ) ) {
			acf_add_options_page( $name );

			return true;
		}

		return false;
	}
}
