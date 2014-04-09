<?php

require_once 'wrapper/class.redQwrapper.php';





$page = new Page('RedQ', array('type' => 'menu'));


$settings = array();


$settings['Tab One'] = array();
$settings['Tab Two'] = array();
$settings['Tab Three'] = array();
$settings['Tab four'] = array();
$settings['Tab five'] = array();



// Section One
// ------------------------//


// Section One
// ------------------------//


  $fields = array();

  $fields = array(
    array(
        'type'  => 'heading',
        'name'  => 'my_heading',
        'label' => 'My heading Field',
        'desc'  => 'I\'m awesome',
    ),
    array(
        'type'  => 'text',
        'name'  => 'my_textfield',
        'label' => 'My Text Field',
        'desc'  => 'I\'m awesome',
    ),
    array(
        'type'  => 'textarea',
        'name'  => 'my_textarea_1',
        'label' => 'My Text Field',
        'desc'  => 'i\'m really awesome'
      ),
    array(
        'type'  => 'select',
        'name'  => 'my_selectbox',
        'label' => 'My Text Field',
        'options' => array(
              'one'   => 'One',
              'two'   => 'Two',
              'three' => 'Three',
              'four'  => 'Four'
        )
      ),
    array(

        'type' => 'checkbox',
        'name'  => 'my_checkbox',
        'label' => 'My checkbox',
        'desc'  => 'i\'m really awesome'
    ),

    array(
        'type'  => 'radio',
        'name'  => 'my_radio_field',
        'label' => 'My Text Field',
        'options' => array(
            'yes' => 'Yes',
            'no'  => 'No'
        )
      ),

    array(
        'type'  => 'editor',
        'name'  => 'my_editor',
        'label' => 'My Editor'
      ),
    array(
        'type'  => 'color',
        'name'  => 'my_color_field',
        'label' => 'choose colour'
      ),

    array(
        'type'  => 'media',
        'name'  => 'my_media',
        'label' => 'choose colour'
      ),

    array(
        'type'  => 'media',
        'name'  => 'my_media_2',
        'label' => 'my media 2'
      ),

    array(
        'type'  => 'media',
        'name'  => 'my_media_20',
        'label' => 'my media'
      ),

    array(
        'type'  => 'repeat_text',
        'name'  => 'my_repeat_200',
        'label' => 'my media'
      )

  );



  $settings['Tab One']['fields'] = $fields;




  $fields = array();
  $fields = array(
    array(
        'type'  => 'text',
        'name'  => 'my_textfield_8',
        'label' => 'My Text Field'
      ),
    array(
        'type'  => 'text',
        'name'  => 'my_textfield_9',
        'label' => 'My Text Field'
      )


  );

  $settings['Tab Two']['fields'] = $fields;



  $fields = array();
  $fields = array(
    array(
        'type'  => 'text',
        'name'  => 'my_textfield_10',
        'label' => 'My Text Field',
        'desc'  => 'by shishir vai'
      ),
    array(
        'type'  => 'page',
        'name'  => 'my_page',
        'label' => 'My Text Field'
      )


  );

  $settings['Tab Three']['fields'] = $fields;

  $fields = array();
  $fields = array(
    array(
        'type'  => 'text',
        'name'  => 'my_textfield_10',
        'label' => 'My Text Field',
        'desc'  => 'by shishir vai'
      ),
    array(
        'type'  => 'page',
        'name'  => 'my_page',
        'label' => 'My Text Field'
      ),
    array(
        'type'  => 'category',
        'name'  => 'my_category',
        'label' => 'My Category Field'
      )


  );

  $settings['Tab five']['fields'] = $fields;


    
  new TSettingsApi( $page , $settings );



function output_about_text() {
    echo '<h1>Exploring wp settings api</h1>';
    echo '<p>wow , nice hooking </p>';
}
add_action('theader','output_about_text');




 // global $tada;

 // var_dump($tada);




//Add the Meta Box
function add_custom_meta_box() {
    add_meta_box(
        'custom_meta_box', // $id
        'Tareq', // $title 
        'show_custom_meta_box', // $callback
        'post', // $page
        'normal', // $context
        'high'); // $priority
}
add_action('add_meta_boxes', 'add_custom_meta_box');







// The Callback
function show_custom_meta_box() {
global $custom_meta_fields, $post;
echo '<p>
Lorem ipsum dolor sit amet, quo clita affert torquatos ex, nam et illud expetenda. Quas cetero scribentur ea mei, sea probo laudem eripuit in. Tota lobortis conceptam ut his. Regione inermis voluptaria his ea, agam diceret quo eu, blandit moderatius interesset ei mei. Cu diam legendos invenire eum.
</p>

<p>
Verear urbanitas eloquentiam et nam. Modus commune omittantur eam eu, ut sale aperiam quaestio sit, sit at tibique percipit. Ne his doming eripuit. Mei eu saperet percipitur. Mel quod integre ea, quando pertinax eu mel, elit explicari necessitatibus ne cum.
</p>

<p>
Ut vis nonumy accusata temporibus, ei sed oporteat legendos scribentur, cu magna propriae vim. Ex eum partem omnesque. Errem vocent complectitur cum ei. Cu vitae deterruisset vis. Id est elitr aperiri. Vim oblique eripuit ea, ex sed graeco feugiat legimus.
</p>


<p>
Cum ex tota veniam nominavi. Ocurreret quaerendum nam et. Tollit molestiae ea sed, duo assum facete ut. Vim ex augue interesset repudiandae, in est eleifend omittantur vituperatoribus.
</p>

<p>
At illum abhorreant eum. In ubique fuisset posidonium vis, duo et illum deseruisse, has at affert soleat laoreet. Esse integre mei ut. Pro ad amet corpora, alii interpretaris pro ut. Id pro viderer accommodare. Ex cum decore nonumes.
</p>';
}







/**
 * Calls the class on the post edit screen.
 */
function call_someClass() {
    new someClass();
}

if ( is_admin() ) {
    add_action( 'load-post.php', 'call_someClass' );
    add_action( 'load-post-new.php', 'call_someClass' );
}

/** 
 * The Class.
 */
class someClass {

  /**
   * Hook into the appropriate actions when the class is constructed.
   */
  public function __construct() {
    add_action( 'add_meta_boxes', array( $this, 'add_meta_box' ) );
    add_action( 'save_post', array( $this, 'save' ) );
  }

  /**
   * Adds the meta box container.
   */
  public function add_meta_box( $post_type ) {
            $post_types = array('post', 'page');     //limit meta box to certain post types
            if ( in_array( $post_type, $post_types )) {
    add_meta_box(
      'some_meta_box_name'
      ,__( 'Some Meta Box Headline', 'myplugin_textdomain' )
      ,array( $this, 'render_meta_box_content' )
      ,$post_type
      ,'advanced'
      ,'high'
    );
            }
  }

  /**
   * Save the meta when the post is saved.
   *
   * @param int $post_id The ID of the post being saved.
   */
  public function save( $post_id ) {
  
    /*
     * We need to verify this came from the our screen and with proper authorization,
     * because save_post can be triggered at other times.
     */

    // Check if our nonce is set.
    if ( ! isset( $_POST['myplugin_inner_custom_box_nonce'] ) )
      return $post_id;

    $nonce = $_POST['myplugin_inner_custom_box_nonce'];

    // Verify that the nonce is valid.
    if ( ! wp_verify_nonce( $nonce, 'myplugin_inner_custom_box' ) )
      return $post_id;

    // If this is an autosave, our form has not been submitted,
                //     so we don't want to do anything.
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
      return $post_id;

    // Check the user's permissions.
    if ( 'page' == $_POST['post_type'] ) {

      if ( ! current_user_can( 'edit_page', $post_id ) )
        return $post_id;
  
    } else {

      if ( ! current_user_can( 'edit_post', $post_id ) )
        return $post_id;
    }

    /* OK, its safe for us to save the data now. */

    // Sanitize the user input.
    $mydata = sanitize_text_field( $_POST['myplugin_new_field'] );

    // Update the meta field.
    update_post_meta( $post_id, '_my_meta_value_key', $mydata );
  }


  /**
   * Render Meta Box content.
   *
   * @param WP_Post $post The post object.
   */
  public function render_meta_box_content( $post ) {
  
    // Add an nonce field so we can check for it later.
    wp_nonce_field( 'myplugin_inner_custom_box', 'myplugin_inner_custom_box_nonce' );

    // Use get_post_meta to retrieve an existing value from the database.
    $value = get_post_meta( $post->ID, '_my_meta_value_key', true );

    // Display the form, using the current value.
    echo '<label for="myplugin_new_field">';
    _e( 'Description for this field', 'myplugin_textdomain' );
    echo '</label> ';
    echo '<input type="text" id="myplugin_new_field" name="myplugin_new_field"';
                echo ' value="' . esc_attr( $value ) . '" size="25" />';
  }
}








