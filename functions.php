<?php
 //  require_once 'sap/settings-api.php';

    require_once 'wrapper/class.settingsapi.php';

    $page = new Page('Settings', array('type' => 'menu'));


$settings = array();


$settings['Tab One'] = array();
$settings['Tab Two'] = array();
$settings['Tab Three'] = array();


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
        'type'  => 'text',
        'name'  => 'my_textfield_1',
        'label' => 'My Text Field'
      ),
    array(
        'type'  => 'text',
        'name'  => 'my_textfield_2',
        'label' => 'My Text Field'
      ),
    array(
        'type'  => 'text',
        'name'  => 'my_textfield_3',
        'label' => 'My Text Field'
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
    
  new TSettingsApi( $page , $settings );







 // global $tada;

 // var_dump($tada);





//   if( file_exists(get_template_directory().'/op/options.php') )
//     include_once(get_template_directory().'/op/options.php');


