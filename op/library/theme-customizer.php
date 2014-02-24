<?php

/**
 *
 * This file exists to implement support for the Theme Customizer,
 * introduced in WordPress 3.4.
 *
 **/
add_action( 'customize_register', 'upfw_customize_register' );

function upfw_customize_register($wp_customize) {

	/**
	 * Globalize the variable that holds
	 * the Settings Page tab definitions
	 *
	 * @global	array	Settings Page Tab definitions
	 */
	global $up_tabs;

	/**
	 * Register each tab section in the Theme Customizer
	 *
	 * @todo Add description.
	 *
	 */
	foreach ( $up_tabs as $tab ) {
		$tabname = $tab['name'];
		$tabsections = $tab['sections'];
		foreach ( $tabsections as $section ) {
			$sectionname = $section['name'];
			$sectiontitle = $section['title'];
			$sectiondescription = $section['description'];

			$wp_customize->add_section($sectionname, array(
				'title' => $sectiontitle,
				'description' => $sectiondescription
			) );

		}
	}

	$upfw_options = upfw_get_options();

	$upfw_option_parameters = upfw_get_option_parameters();

	foreach( $upfw_option_parameters as $option ){

		if( $option['type'] == 'editor' || $option['type'] == 'multicheck' ){
			continue;
		}

		$optionname = $option['name'];
		$theme_id = upfw_get_current_theme_id();
		$optiondb = "theme_{$theme_id}_options[{$optionname}]";
		$option_section_name =  $option['section'];

		$wp_customize->add_setting( $optiondb, array(
			'default'		=> $option['default'],
			'type'			=> 'option',
			'capabilities'	=> 'edit_theme_options'
		) );

		if( $option['type'] == 'text' || $option['type'] == 'textarea' ){

			$wp_customize->add_control( $option['name'], array(
				'label'   => $option['title'],
				'section' => $option_section_name,
				'settings' => $optiondb,
				'type'    => 'text',
			) );

		}

		if( $option['type'] == 'color' ){

			$wp_customize->add_control(
				new WP_Customize_Color_Control(
					$wp_customize,
					$option['name'],
					array(
						'label'   => $option['title'],
						'section' => $option_section_name,
						'settings'=> $optiondb,
					)
				)
			);
		}

		if( $option['type'] == 'upload' || $option['type'] == 'image' ){

			$wp_customize->add_control(
				new UpThemes_Customize_Image_Control(
					$wp_customize,
					$option['name'],
					array(
						'label'    => $option['title'],
						'section'  => $option_section_name,
						'settings' => $optiondb,
						'context'  => $option['name'] // For UpThemes_Customize_Image_Control
					)
				)
			);
		}

		if( $option['type'] == 'radio_image' ){
			$wp_customize->add_control(
				new UpThemes_Image_Radio_Control(
					$wp_customize,
					$option['name'],
					array(
						'label'    => $option['title'],
						'section'  => $option_section_name,
						'settings' => $optiondb,
						'choices'  => upfw_extract_valid_options_radio_image($option['valid_options'])
					)
				)
			);
		}

		if( $option['type'] == 'radio' || $option['type'] == 'select' ){

			$wp_customize->add_control( $option['name'], array(
				'label'   => $option['title'],
				'section' => $option_section_name,
				'settings'=> $optiondb,
				'type'    => $option['type'],
				'choices' => upfw_extract_valid_options($option['valid_options'])
			) );
		}
	}

}

function upfw_extract_valid_options($options){
	$new_options = array();
	foreach($options as $option){
		$new_options[$option['name']] = $option['title'];
	}
	return $new_options;
}

function upfw_extract_valid_options_radio_image($options){
	$new_options = array();
	foreach($options as $option){
		$new_options[$option['name']] = array( 'title' => $option['title'], 'image' => $option['image'] );
	}
	return $new_options;
}

if ( class_exists( 'WP_Customize_Image_Control' ) && ! class_exists( 'UpThemes_Image_Radio_Control' ) ) {
/**
 * Creates Customizer control for radio replacement images fields
 */
class UpThemes_Image_Radio_Control extends WP_Customize_Control {

		public $type = 'images_radio';

		public function render_content() {
			if ( empty( $this->choices ) )
				return;

			?>

			<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>

			<?php
			foreach ( $this->choices as $value => $choice ) {
				?>
				<label class="radio_image" for="<?php echo esc_html( $this->id ); ?>_<?php echo esc_html( $value ); ?>">
					<input id="<?php echo esc_html( $this->id ); ?>_<?php echo esc_html( $value ); ?>" class="image-radio" type="radio" value="<?php echo esc_attr( $value ); ?>" name="<?php echo esc_html( $this->id ); ?>" <?php $this->link(); checked( $this->value(), $value ); ?> />
					<img src="<?php echo $choice['image']; ?>" alt="<?php echo esc_html( $this->label ); ?>" />
				</label>
				<?php
			} // end foreach

        }

		public function enqueue() {
			wp_enqueue_style(
				'upfw_styles',
				upfw_get_theme_options_directory_uri() . '/css/up_framework.css'
			);
		}

}

}

if ( class_exists( 'WP_Customize_Image_Control' ) && ! class_exists( 'UpThemes_Customize_Image_Control' ) ) :
/**
 * UpThemes_Customize_Image_Control
 *
 * Extend WP_Customize_Image_Control allowing access to uploads made within the same context.
 *
 * @link  https://gist.github.com/eduardozulian/4739075
 * @since 1.0.
 */

class UpThemes_Customize_Image_Control extends WP_Customize_Image_Control {
	/**
	* Constructor.
	*
	* @since 3.4.0
	* @uses WP_Customize_Image_Control::__construct()
	*
	* @param WP_Customize_Manager $manager
	*/
	public function __construct( $manager, $id, $args = array() ) {

	parent::__construct( $manager, $id, $args );

	}

	/**
	* Search for images within the defined context
	* If there's no context, it'll bring all images from the library
	*
	*/
	public function tab_uploaded() {
	$my_context_uploads = get_posts( array(
	    'post_type'  => 'attachment',
	    'meta_key'   => '_wp_attachment_context',
	    'meta_value' => $this->context,
	    'orderby'    => 'post_date',
	    'nopaging'   => true,
	) );

	?>

	<div class="uploaded-target"></div>

	<?php
	if ( empty( $my_context_uploads ) )
	    return;

	foreach ( (array) $my_context_uploads as $my_context_upload )
	    $this->print_tab_image( esc_url_raw( $my_context_upload->guid ) );
	}

}

endif;