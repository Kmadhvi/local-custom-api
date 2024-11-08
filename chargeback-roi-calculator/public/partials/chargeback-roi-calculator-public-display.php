<?php if( ! defined( 'ABSPATH' ) ) { die; } // If this file is called directly, abort.

/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       https://www.worldwebtechnology.com
 * @since      1.0.0
 *
 * @package    Chargeback_Roi_Calculator
 * @subpackage Chargeback_Roi_Calculator/public/partials
 */
?>

<div class="main-content">
    <div class="container">
        <section>
          <?php echo html_entity_decode($croic_settings_content);?>
        </section>
    </div>
</div>
<div class="main-form">
    <div class="container">
        <div id="jquery-steps">
            <section>
                <div id="croic_loader"></div>
                <form action="" method="post" class="form-horizontal roi_tool_step_form" id="croic_user_form">
                    <h3><?php _e('ROI Tool', 'chargeback-roi-calculator'); ?></h3>
                        <fieldset>
                            <div class="form-group">
                                <label for="croic_email" class="form-control"><?php _e('Enter Your Email', 'chargeback-roi-calculator'); ?></label>
                                <input type="email" placeholder="Enter your email" class="form-control" name="croic_email" id="croic_email" value="">
                                <br><span class="error" id="error_email"></span>
                            </div>
                            <div class="form-group">
                                <button type="button" class="btn btn-primary next-btn"><?php _e('Next' , 'chargeback-roi-calculator');?></button>
                            </div>
                        </fieldset>

                        <fieldset class="step-2">
                            <div class="form-group">
                                <label for="croic_industry_options" class="form-control" ><?php _e('Which industry do you operate in?  ' , 'chargeback-roi-calculator'); ?><i class="fa fa-industry" aria-hidden="true"></i></label>
                                <select name="croic_industry_options" id="croic_industry_options" class="form-control multiselect">
                                    <option><?php _e('------------Select Industry----------');?></option>
                                    <?php if( $croic_industry ) {
                                            foreach ($croic_industry as $key => $value) { 
                                                echo '<option value="' . $value['croic_industry'] . '">' . $value['croic_industry'] . '</option>';
                                            }
                                    } ?>                            
                                </select> 
                                <span class="error" id="error_industry_options"></span><br>

                                <label for="croic_psp_options" class="form-control" ><?php _e('Which payment service providers (PSP) do you use? Select all that apply?  ' , 'chargeback-roi-calculator'); ?><i class="fa fa-money" aria-hidden="true"></i></label>
                                <select name="croic_psp_options[]" id="croic_psp_options" class="form-control multiselect" multiple="multiple">
                                    
                                    <?php foreach ($croic_psp as $key => $value) { 
                                        echo '<option value="' . $value['croic_psp'] . '">' . $value['croic_psp'] . '</option>';
                                    } ?>
                                </select>
                                <span class="error" id="error_psp_options"></span><br>
                                <label for="croic_user_month_chargeback" class="form-control psp_percentage_label" style="display:none;" ><?php _e('Do you know which % of your transactions is processed by each PSP? If not, we’ll assume they are distributed equally.' , 'chargeback-roi-calculator'); ?></label>

                                <div id="text_input_container"></div>

                            </div>     
                            <div class="form-group" >
                                <button type="button" class="btn btn-primary prev-btn"><?php _e('Previous' , 'chargeback-roi-calculator');?></button>
                                <button type="button" class="btn btn-primary next-btn"><?php _e('Next' , 'chargeback-roi-calculator');?></button>
                            </div>                    
                        </fieldset>

                        <fieldset class="step-3">
                            <div class="form-group">
                                <label for="croic_user_month_chargeback" class="form-control" ><?php _e('How many chargebacks do you deal with per month, on average?' , 'chargeback-roi-calculator'); ?></label>
                                <input type="number" placeholder="i.e 5" class="form-control" id="croic_user_month_chargeback" name="croic_user_month_chargeback">
                                <span class="error" id="error_chargeback_number"></span><br>
                                
                                <label for="croic_user_fraudulent_chargebacks" class="form-control" ><?php _e('Which % of your total chargebacks are fraud chargebacks? If you don’t know, we’ll assume 50%.' , 'chargeback-roi-calculator'); ?></label>
                                <input type="number" placeholder="i.e 0.45" class="form-control" id ="croic_user_fraudulent_chargebacks" name="croic_user_fraudulent_chargebacks">
                                <span class="error" id="error_chargeback_percentage"></span><br>

                                <label for="croic_user_average_chargeback" class="form-control" ><?php _e('What is your average chargeback transaction value?' , 'chargeback-roi-calculator'); ?></label>
                                <input type="number" placeholder="i.e 2000" class="form-control" id="croic_user_average_chargeback" name="croic_user_average_chargeback">
                                <span class="error" id="error_chargeback_amount"></span><br>
                            </div>
                            <div class="form-group">
                                <button type="button" class="btn btn-primary prev-btn"><?php _e('Previous' , 'chargeback-roi-calculator');?></button>
                                <button type="button" class="btn btn-primary next-btn" ><?php _e('Next' , 'chargeback-roi-calculator');?></button>
                            </div>
                        </fieldset>


                        <fieldset class="step-4">
                            <p><?php _e("Almost done! Enter your details to get your results. We’ll also send you  a copy of our Chargeback Solutions Buyer’s Guide!" ,'chargeback-roi-calculator');?></p>
                            <div class="form-group">
                                <label for="croic_name" class="form-control" ><?php _e('Enter Your Name' , 'chargeback-roi-calculator'); ?></label>
                               <input type="text" name="croic_name" id="croic_name" placeholder="Enter Your Name">
                               <span class="error" id="error_name"></span>
                                <label for="croic_email" class="form-control" ><?php _e('Enter Your Email' , 'chargeback-roi-calculator'); ?></label>
                               <input type="email" name="croic_email" id="email" placeholder="Enter Your Email">
                               <span class="error" id="error_email"></span>
                                <label for="croic_company_website" class="form-control" ><?php _e('Enter Your Company Website (Optional)' , 'chargeback-roi-calculator'); ?></label>
                               <input type="text" name="croic_company_website" id="croic_company_website" placeholder="Enter Your Company Website">
                               <span class="error" id="error_company_website"></span>
                            </div>
                            <div class="form-group">
                                <input type="hidden" name="action" value="process_roi_calculator_form">
                                <input type="hidden" name="nonce" value="<?php echo wp_create_nonce('nonce_action'); ?>">
      
                                <?php wp_nonce_field( 'nonce_action', 'nonce_field' ); ?>
                                <button type="button" class="btn btn-primary prev-btn"><?php _e('Previous' , 'chargeback-roi-calculator');?></button>
                                <button type="button" class="btn btn-primary submit-btn" id="form_submit"><?php _e('Submit', 'chargeback-roi-calculator'); ?></button>
                            </div>
                        </fieldset>
                </form>
                <div id="message" style="display:none;"></div>
            </section>
        </div>
    </div>
</div>
<div class="main-faq">
    <div class="container">
        <section>
             <?php echo do_shortcode("[roi_faqs]");?> 
        </section>
    </div>
</div>