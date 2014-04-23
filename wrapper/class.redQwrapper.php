<?php
/**
 * RedQ Wrapper
 *
 * Create wp settings page , settings fields very easily
 *
 * @package    RedQ Wrapper
 * @author     RedQ <https://github.com/RedQ>
 * @copyright  RedQ <https://github.com/RedQ>
 * @license    For non-commercial, personal, or open source projects and applications, you may use RedQ Wrapper under the terms of the GPL v3 License.
 * @version    Release: 0.1
 * @link       https://github.com/RedQ/RedQ-wrapper
 */


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly






class Page{

	protected $slug;
	protected $hook;
	protected $page_title;
	protected $menu_title;
	protected $capability;
	protected $icon;
	protected $markup_top;
	protected $markup_bottom;
	protected $type;
	protected $parent_slug;


	public function __construct($menu_title , $settings = array() ) {
		$this->menu_title  = $menu_title;


		

		$default = array(
			'slug' => (isset($settings['slug'])) ? $settings['slug'] : sanitize_title_with_dashes($menu_title),
			'page_title' => (isset($settings['page_title'])) ? $settings['page_title'] : $menu_title,
			'capability' => (isset($settings['capability'])) ? $settings['capability'] : 'manage_options',
			'icon' => (isset($settings['icon'])) ? $settings['icon'] : 'icon-options-general',
			'type' => 'settings'
		);

		$settings = array_merge( $default , $settings );


		foreach ($settings as $key => $value) {
			if(property_exists($this, $key)){
				$this->$key = $value;

			}
		}

	}


	public function __get($key)
	{
		if(property_exists($this, $key)) {

			return $this->$key;
		}
	}



}





class TSettingsApi{

	public $page;

	function __construct( $page , $settings = array() ) {

		//path

		define('TSAPI', get_template_directory_uri() .'/wrapper' );
		define('TSAPICSS', TSAPI .'/assets/css/' );
		define('TSAPIJS', TSAPI .'/assets/js/' );

		
		$this->page = $page;
		$this->settings = $settings;

		add_action( 'admin_enqueue_scripts', array($this,'add_style') );
		add_action( 'admin_menu' , array($this,'register_settings_menu') );
		add_action('admin_init', array($this, 'register_fields'));

     	// add style and js 

		$this->get_alldata();

	}


	public function get_alldata(){

		global $tada;
        $tab_option = array();
		foreach ($this->settings as $tab => $fields) {
		   $tabname = strtolower(str_replace(" ", "_", $tab));

		   if( get_option($tabname) ){
		  		$tab_option[]= get_option($tabname); 	
		   }  

		}	

		$tada = $this->array_flatten($tab_option,array());
	}


	public function add_style(){

        wp_enqueue_style( 'wp-color-picker' );
        wp_enqueue_style( 'thickbox' );


		wp_register_style( 'custom_wp_admin_css', TSAPICSS. 'style.css', false, '1.0.0' );
        wp_enqueue_style( 'custom_wp_admin_css' );


        wp_enqueue_script( 'jquery' );


        wp_enqueue_script( 'wp-color-picker' );

		if ( function_exists( 'wp_enqueue_media' ) ){
			wp_enqueue_media();
		}

			


        wp_register_script( 'semantic', TSAPIJS. 'semantic.min.js', false, '1.0.0' );
        wp_enqueue_script( 'semantic' );

        

        wp_register_script( 'custom_wp_admin_js', TSAPIJS. 'script.js', false, '1.0.0' );
        wp_enqueue_script( 'custom_wp_admin_js' );

	}


	public function register_fields()
	{

		foreach ($this->settings as $tab => $section) {

			$tabname = strtolower(str_replace(" ", "_", $tab));
     		add_settings_section( $tabname, $tab, array($this,'callback_funn'), $tabname  );


			foreach ($section as $key => $value) {
				foreach ($value as $options) {

  				$args = array(
                    'id' => $options['name'],
                    'desc' => isset( $options['desc'] ) ? $options['desc'] : '',
                    'name' => $options['label'],
                    'section' => $tabname,
                    'size' => isset( $options['size'] ) ? $options['size'] : null,
                    'options' => isset( $options['options'] ) ? $options['options'] : '',
                    'std' => isset( $options['default'] ) ? $options['default'] : '',
                    'type'=> $options['type'],
                    'sanitize_callback' => isset( $options['sanitize_callback'] ) ? $options['sanitize_callback'] : '',
                );

  				

	

					add_settings_field($tabname . '[' . $options['name'] . ']', $options['label'], array( $this, 'add_fields' ), $tabname , $tabname, $args );
				}
			}


			

			register_setting( $tabname, $tabname , array($this,'sanitize_options'));
		}




	}


	public function add_fields($args = array()){

		extract($args);


		$value = esc_attr( $this->get_option( $id, $section, $std ) );

		$uname = $section.'['.$id.']';
		
		switch ( $type ) {
			
			case 'heading':
				echo '</td></tr><tr valign="top"><td colspan="2"><h4>' . $desc . '</h4>';
				break;




			
			case 'checkbox':
				
				echo '<input class="checkbox" type="checkbox"  id="'.$section.'[' . $id . ']" name="'.$section.'[' . $id . ']"   value="1" ' . checked( $value, 1, false ) . ' /> <label for="' . $id . '">' . $desc . '</label>';
				
				break;




			
			case 'select':
				echo '<select class="select"  id="'.$section.'[' . $id . ']" name="'.$section.'[' . $id . ']" >';
				
				 foreach ( $options as $key => $label ) {
					echo '<option value="' . esc_attr( $key ) . '"' . selected( $value, $key, false ) . '>' . $label . '</option>';
					}
				echo '</select>';
				
				if ( $desc != '' )
					echo '<br /><span class="description">' . $desc . '</span>';
				
				break;





			
			case 'radio':

				$i = 0;
				foreach ( $options as $key => $label ) {
					echo '<input class="radio" type="radio" id="'.$section.'['. $id .']['.$key.']" name="'.$section.'[' . $id . ']"   value="' . esc_attr( $key ) . '" ' . checked( $value, $key, false ) . '> <label for="'.$section.'[' . $id . ']['.$key.']" >' . $label . '</label>';
					if ( $i < count( $options ) - 1 )
						echo '<br /> <br/>';
					$i++;
				}
				
				if ( $desc != '' )
					echo '<br /><span class="description">' . $desc . '</span>';
				
				break;





			
			case 'textarea':


				echo '<textarea class=""  id="'.$section.'[' . $id . ']" name="'.$section.'[' . $id . ']"    placeholder="' . $std . '" rows="5" cols="30">' . esc_attr( $value ) . '</textarea>';
				
				if ( $desc != '' )
					echo '<br /><span class="description">' . $desc . '</span>';
				
				break;



			case 'color':


				
				echo '<input class="regeular-text wp-color-picker-field" type="text"   id="'.$section.'[' . $id . ']" name="'.$section.'[' . $id . ']"  value="'.esc_attr( $value ).'"  /> ';

				if ( $desc != '' )
					echo '<br /><span class="description">' . $desc . '</span>';
				
				break;


		   case 'editor':

		        echo '<div>';

		        wp_editor( $value, $section.'['.$id.']', array( 'tinymce' =>true, 'textarea_rows' => '' ) );

		        echo '</div>';

				if ( $desc != '' )
					echo '<br /><span class="description">' . $desc . '</span>';				
				break;
		   	    break;



			
			case 'password':


				echo '<input class="regular-text" type="password"  id="'.$section.'[' . $id . ']" name="'.$section.'[' . $id . ']"  value="' .esc_attr( $value ). '" />';
				
				if ( $desc != '' )
					echo '<br /><span class="description">' . $desc . '</span>';
				
				break;
			

			case 'page':

				$s = $section.'['.$id.']';
				$output = wp_dropdown_pages( array( 'name' => $s, 'echo' => 0, 
					'selected' => $value ,'show_option_none '=>'' ) );
				echo $output;

			break;


			case 'category':

				$s = $section.'['.$id.']';
				$output = wp_dropdown_categories( array('name' => $s, 'echo' => 0, 
					'selected' => $value ) );
				echo $output;

			break;

			case 'media':

				$display = "";
				if(empty($value)){
					$display = 'none;';
				} 

				echo '<div class="uploader">';
				echo '<input type="text" name="'.$uname.'" class="'.$id.'" value="'.$value.'" />';
				echo '<button class="upload_image_button button" style="margin-left:5px;" ref="'.$id.'" name="'.$uname.'_button" id="'.$uname.'_button">Upload</button>';
				echo '<button class="cancel button cancel_'.$id.'" ref="'.$id.'" style="margin-left:5px;display:'.$display.' " >Cancel</button>';
				
            	echo '<br/><br/><img class="'.$id.'" src="'.$value.'"  style="border:5px solid #ccc; width:300px; display:'.$display.'"/>';
		
				echo '</div>';

			break ;


			case 'repeat_text':



					 $counter = 0;
					 
						$output = '<div class="of-repeat-loop">';
					 
						if( is_array( $value ) ) foreach ( (array)$value as $item_value ){
					 
							$output .= '<div class="of-repeat-group">';
							$output .= '<input class="of-input" name="' . esc_attr( $uname. '['.$counter.']' ) . '" type="text" value="' . esc_attr( $item_value ) . '" />';
							$output .= '<button class="dodelete button icon delete">'. __('Remove') .'</button>';
					 
							$output .= '</div><!--.of-repeat-group-->';
					 
							$counter++;
						}
					 
						$output .= '<div class="of-repeat-group to-copy">';
						$output .= '<input class="of-input" data-rel="' . esc_attr( $uname ) . '" type="text" value="" />';
						$output .= '<button class="dodelete button icon delete">'. __('Remove') .'</button>';
						$output .= '</div><!--.of-repeat-group-->';
					 
					 
						$output .= '<button class="docopy button icon add">Add</button>';
					 
						$output .= '</div><!--.of-repeat-loop-->';
 

    			echo $output;


			break;


			
			case 'text':
			default:
		 		echo '<input class="regular-text" type="text" id="'.$section.'[' . $id . ']" name="'.$section.'[' . $id . ']" placeholder="' . $std . '" value="' . esc_attr( $value ) . '" />';
		 		
		 		if ( $desc != '' )
		 			echo '<br /><span class="description">' . $desc . '</span>';
		 		
		 		break;
		 	
		}


	}



    function sanitize_options( $options ) {


        foreach( $options as $option_slug => $option_value ) {


            
            if ( !is_array( $option_value ) ) {
                $options[ $option_slug ] = sanitize_text_field( $option_value );
                continue;
            }

		    if( is_array( $option_value ) ){
		    	$options[ $option_slug ]  = array_map( 'sanitize_text_field', $option_value);
		    }
		        





        }
        return $options;
    }


	function something($input){

	}





	public function callback_funn($arg){




	}





    function get_option( $option, $section, $default = '' ) {

        $options = get_option( $section );

        if ( isset( $options[$option] ) ) {
            return $options[$option];
        }

        return $default;
    }




	public function register_settings_menu(){

		switch($this->page->type) {

			case 'menu':
				add_menu_page( $this->page->title, $this->page->menu_title, $this->page->capability, $this->page->slug, array($this, 'render') );
				break;

			case 'submenu':
				add_submenu_page( $this->page->parent_slug, $this->page->title, $this->page->menu_title, $this->page->capability, $this->page->slug, array($this, 'render') );
				break;


			case 'settings':
				add_options_page( $this->page->title, $this->page->menu_title, $this->page->capability, $this->page->slug, array($this, 'render') );
				break;

			default:
				add_theme_page( $this->page->title, $this->page->menu_title, $this->page->capability, $this->page->slug, array($this, 'render') );
				break;
		}


	}

	public function array_flatten($array,$return){

	   foreach($array as $key => $value){
	    if(is_array($value))
	    {
	      $return = $this->array_flatten($value,$return);
	    }
	    elseif($value)
	    {
	      $return[$key] = $value;
	    }
	  }
	  return $return;

	}


	public function render(){

	   	include_once('views/frontend.php' );

	}



}
