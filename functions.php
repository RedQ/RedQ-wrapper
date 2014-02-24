<?php
 //  require_once 'sap/settings-api.php';

    require_once 'wrapper/class.settingsapi.php';

    $page = new Page('shishir', array('type' => 'menu'));


$settings = array();


$settings['Tab One'] = array();
$settings['Tab Two'] = array();
$settings['Tab Three'] = array();
$settings['Tab four'] = array();


// Section One
// ------------------------//


// Section One
// ------------------------//


  $fields = array();

  $fields = array(
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
        'type'  => 'radio',
        'name'  => 'my_radio_field',
        'label' => 'My Text Field',
        'options' => array(
            'yes' => 'Yes',
            'no'  => 'No'
        )
      ),

    array(
        'type'  => 'text',
        'name'  => 'my_textfield_4',
        'label' => 'My Text Field'
      ),
    array(
        'type'  => 'text',
        'name'  => 'my_textfield_5',
        'label' => 'My Text Field'
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
        'type'  => 'text',
        'name'  => 'my_textfield_11',
        'label' => 'My Text Field'
      )


  );

  $settings['Tab Three']['fields'] = $fields;



    
  new TSettingsApi( $page , $settings );



function output_about_text() {
    echo '<h1>Exploring wp settings api</h1>';
    echo '<p>wow , nice hooking </p>';
}
add_action('theader','output_about_text');




 // global $tada;

 // var_dump($tada);





//   if( file_exists(get_template_directory().'/op/options.php') )
//     include_once(get_template_directory().'/op/options.php');


