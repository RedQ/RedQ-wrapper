<div class="wrap">
  <?php  

 global $tada;
 var_dump($tada);



    do_action('theader');

   ?>


<h2 class="nav-tab-wrapper">
<?php   foreach ($this->settings as $tab => $section){ 

       $tabname = strtolower(str_replace(" ", "_", $tab));
  ?>
       
            <a href="#<?php  echo $tabname;  ?>" id="<?php echo $tabname; ?>-tab" class="nav-tab"><?php  echo $tab;  ?></a>


       
 <?php  } ?>
</h2>


<br>






<?php   foreach ($this->settings as $tab => $section){ 

       $tabname = strtolower(str_replace(" ", "_", $tab));
  ?>
  
        <div id="<?php echo $tabname; ?>" class="group">
  <div class="ui  segment">


       <form method="post" action="options.php">   
          <?php
            settings_fields($tabname); 
            //do_settings_fields( $tabname, $tabname );
            do_settings_sections( $tabname );
            submit_button();
         ?>
</form>
        </div>

        </div>


<?php  }  ?>










<br>
<h2>Semantic</h2>




<div class="ui grid">

	<div class="left floated four wide column">

       <div class="ui vertical pointing demo menu">


      <?php   foreach ($this->settings as $tab => $section): ?>

      		<a class="item" data-tab="<?php  echo strtolower(str_replace(" ", "_", $tab)); ?>"> <?php echo $tab;  ?></a>			

      <?php   endforeach;  ?>


      </div>





</div>

  <div class="right floated twelve wide  column">
      <?php  foreach ($this->settings as $tab => $section): ?>
      	<div class="ui bottom attached piled segment tab" data-tab="<?php echo strtolower(str_replace(" ", "_", $tab));    ?>"> 
      <form method="post" action="options.php">

<div class="ui form">




      		<?php  
        $tabname = strtolower(str_replace(" ", "_", $tab));
        settings_fields($tabname); 
        do_settings_sections( $tabname );
     		 ?> 


      	
        <?php  submit_button();  ?>

        </div>
      </form>
      </div>

      <?php  endforeach;  ?>

  </div>


</div>







