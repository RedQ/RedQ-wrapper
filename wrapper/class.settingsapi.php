<?php

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


		foreach ($settings as $tab => $value) {
		   $tabname = strtolower(str_replace(" ", "_", $tab));

		   if( get_option($tabname) ){
		  		$tab_op[]= get_option($tabname); 	
		   }  

		}


		global $tada;

		$tada = $this->array_flatten($tab_op,array());

        // break the option type and go with the data . 

	}


	public function add_style(){

        wp_enqueue_style( 'wp-color-picker' );
        wp_enqueue_style( 'thickbox' );


		wp_register_style( 'custom_wp_admin_css', TSAPICSS. 'style.css', false, '1.0.0' );
        wp_enqueue_style( 'custom_wp_admin_css' );

        // wp_register_script( 'jquery-new', TSAPIJS. 'jquery.js', false, '1.0.0' );
        wp_enqueue_script( 'jquery' );


       wp_enqueue_script( 'wp-color-picker' );

       wp_enqueue_script( 'media-upload' );
       wp_enqueue_script( 'thickbox' );

        wp_register_script( 'semantic', TSAPIJS. 'semantic.min.js', false, '1.0.0' );
        wp_enqueue_script( 'semantic' );

        

        wp_register_script( 'custom_wp_admin_js', TSAPIJS. 'script.js', false, '1.0.0' );
        wp_enqueue_script( 'custom_wp_admin_js' );

	}


	public function register_fields()
	{
	//	var_dump($this->settings);


		// section thik korte hobe.
		// field register korte hobe. 

		

		foreach ($this->settings as $tab => $section) {

			 $tabname = strtolower(str_replace(" ", "_", $tab));
			// if( get_option($tabname) == false)
			// {
			// 	add_option( $tabname );


			// }




			//$callback = '__return_false';

			

			//add_settings_section( $id, $title, $callback, $page );

			add_settings_section( $tabname, $tab, array($this,'callback_funn'), $tabname  );

			// add_settings_field( $id, $title, $callback, $page, $section, $args );

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

  				//var_dump($args);


				// 	add_settings_field($tabname . '[' . $options['name'] . ']', $options['label'], array( $this, 'callback_' . $options['type'] ), $tabname , $tabname, $args );

					add_settings_field($tabname . '[' . $options['name'] . ']', $options['label'], array( $this, 'add_fields' ), $tabname , $tabname, $args );
				}
			}


			

			register_setting( $tabname, $tabname , array($this,'sanitize_options'));
		}

	}


	public function add_fields($args = array()){
		extract($args);
		//echo $type;

		$value = esc_attr( $this->get_option( $id, $section, $std ) );
		
		switch ( $type ) {
			
			case 'heading':
				echo '</td></tr><tr valign="top"><td colspan="2"><h4>' . $desc . '</h4>';
				break;
			
			case 'checkbox':
				
				echo '<input class="checkbox' . $field_class . '" type="checkbox" id="' . $id . '" name="mytheme_options[' . $id . ']" value="1" ' . checked( $options[$id], 1, false ) . ' /> <label for="' . $id . '">' . $desc . '</label>';
				
				break;
			
			case 'select':
				echo '<select class="select' . $field_class . '" name="mytheme_options[' . $id . ']">';
				
				foreach ( $choices as $value => $label )
					echo '<option value="' . esc_attr( $value ) . '"' . selected( $options[$id], $value, false ) . '>' . $label . '</option>';
				
				echo '</select>';
				
				if ( $desc != '' )
					echo '<br /><span class="description">' . $desc . '</span>';
				
				break;
			
			case 'radio':
				$i = 0;
				foreach ( $choices as $value => $label ) {
					echo '<input class="radio' . $field_class . '" type="radio" name="mytheme_options[' . $id . ']" id="' . $id . $i . '" value="' . esc_attr( $value ) . '" ' . checked( $options[$id], $value, false ) . '> <label for="' . $id . $i . '">' . $label . '</label>';
					if ( $i < count( $options ) - 1 )
						echo '<br />';
					$i++;
				}
				
				if ( $desc != '' )
					echo '<br /><span class="description">' . $desc . '</span>';
				
				break;
			
			case 'textarea':
				echo '<textarea class="' . $field_class . '" id="' . $id . '" name="mytheme_options[' . $id . ']" placeholder="' . $std . '" rows="5" cols="30">' . wp_htmledit_pre( $options[$id] ) . '</textarea>';
				
				if ( $desc != '' )
					echo '<br /><span class="description">' . $desc . '</span>';
				
				break;
			
			case 'password':
				echo '<input class="regular-text' . $field_class . '" type="password" id="' . $id . '" name="mytheme_options[' . $id . ']" value="' . esc_attr( $options[$id] ) . '" />';
				
				if ( $desc != '' )
					echo '<br /><span class="description">' . $desc . '</span>';
				
				break;
			
			case 'text':
			default:
		 		echo '<input class="regular-text" type="text" id="'.$section.'[' . $id . ']" name="'.$section.'[' . $id . ']" placeholder="' . $std . '" value="' . esc_attr( $value ) . '" />';
		 		
		 		if ( $desc != '' )
		 			echo '<br /><span class="description">' . $desc . '</span>';
		 		
		 		break;
		 	
		}


	}


    /**
     * Sanitize callback for Settings API
     */
    function sanitize_options( $options ) {
        foreach( $options as $option_slug => $option_value ) {
            // $sanitize_callback = $this->get_sanitize_callback( $option_slug );

            // // If callback is set, call it
            // if ( $sanitize_callback ) {
            //     $options[ $option_slug ] = call_user_func( $sanitize_callback, $option_value );
            //     continue;
            // }

            // Treat everything that's not an array as a string
            if ( !is_array( $option_value ) ) {
                $options[ $option_slug ] = sanitize_text_field( $option_value );
                continue;
            }
        }
        return $options;
    }


	function something($input){

	}

    function callback_text( $args ) {

        $value = esc_attr( $this->get_option( $args['id'], $args['section'], $args['std'] ) );


        $size = isset( $args['size'] ) && !is_null( $args['size'] ) ? $args['size'] : 'regular';

        $html = sprintf( '<input type="text" class="%1$s-text" id="%2$s[%3$s]" name="%2$s[%3$s]" value="%4$s"/>', $size, $args['section'], $args['id'] ,$value );
        $html .= sprintf( '<span class="description"> %s</span>', $args['desc'] );

        echo $html;
    }



	public function callback_funn($arg){




	  // echo "section intro text here";
	  // echo "<p>id: $arg[id]</p>\n";             // id: eg_setting_section
	  // echo "<p>title: $arg[title]</p>\n";       // title: Example settings section in reading
	 


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
				//$this->set_page_hook('toplevel_page_');
				break;

			case 'submenu':
				add_submenu_page( $this->page->parent_slug, $this->page->title, $this->page->menu_title, $this->page->capability, $this->page->slug, array($this, 'render') );
				break;


			case 'settings':

			   //$page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position

				add_options_page( $this->page->title, $this->page->menu_title, $this->page->capability, $this->page->slug, array($this, 'render') );
				//$this->set_page_hook('settings_page_');
				break;


			default:
				add_theme_page( $this->page->title, $this->page->menu_title, $this->page->capability, $this->page->slug, array($this, 'render') );
				//$this->set_page_hook('appearance_page_');
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