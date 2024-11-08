<style type="text/css">

/* 	

.roi-faq-title {
  background-color: #eee;
  color: #444;
  cursor: pointer;
  padding: 18px;
  width: 100%;
  text-align: left;
  border: none;
  outline: none;
  transition: 0.4s;
}


.roi-faq-title.active, .roi-faq-title:hover {
  background-color: #ccc;
}

*/

.roi-faq-title:after {
  content: '\002B';  
  float: right;
  margin-left: 5px;
}

.roi-faq-title.active:after {
  content: "\2212";
}

.roi-faq-body {

  padding: 0 18px;
  display: none;
  overflow: hidden;
}

</style>

<?php 

$args = array(
  'numberposts' => -1,
  'post_type'   => 'roi_faqs',
  'post_status'    => 'publish', 
  'order' => 'asc'
);

$roi_faqs = get_posts( $args );


?>

<h3><?php _e('Frequently Asked Questions' , 'chargeback-roi-calculator'); ?></h3>

<?php 

if(!empty($roi_faqs)) {

foreach ( $roi_faqs as $post ) {
   	
   setup_postdata( $post );

   ?>
   <h4 class="roi-faq-title"><?php echo $post->post_title; ?></h4>
   <div class="roi-faq-body"><?php the_content(); ?></div>

<?php 

  }

}

