<?php if ( ! defined( 'ABSPATH' ) ) { die; } // If this file is called directly, abort.

    if( isset($_POST['croic_settings_content_editor_field']) ){
        $value = htmlentities( wpautop($_POST['croic_settings_content_editor_field']) );
        update_option( 'croic_settings_content_editor_field', $value );
    }
?>
<style type="text/css">
    .croic-setting-wrap{
        padding: 15px;   
    }
    .croic-setting-page-content{
        margin-top: 50px;
    }
    .croic-setting-page-table{
        width:100%;
    }
    .croic-setting-page-table th{
        text-align: left;
        padding: 20px;
        padding-left: 0px;
        font-size: 18px;
    }
</style>
<div class="croic-setting-wrap">
    <h1><?php esc_html_e('Calculator Settings','chargeback-roi-calculator');?></h1>
    <div class="croic-setting-page-content">
        <form method="post" action="">
            <table class="croic-setting-page-table">   
                <tr>
                    <th><?php esc_html_e('Calculator Page Content : ','chargeback-roi-calculator');?></th>
                </tr>
                <tr>
                    <td>
                    <?php 
                        $editor_id = 'croic_settings_editor';
                        $option_name='croic_settings_content_editor_field';
                        $default_content= get_option( 'croic_settings_content_editor_field' );
                        $default_content=html_entity_decode($default_content);
                        $default_content=stripslashes($default_content);
                        wp_editor( $default_content, $editor_id,array('textarea_name' => $option_name,'media_buttons' => true,'editor_height' => 350,'teeny' => false)  );
                    ?>
                    </td>
                </tr>
                <tr>
                    <td>
                    <?php submit_button('Save', 'primary'); ?>
                    </td>
                </tr>
            </table>
        </form>
    </div>
</div>