<?php
global $wpdb;
$action = isset($_REQUEST['action'])?$_REQUEST['action']:'';
$id = isset($_REQUEST['id'])?$_REQUEST['id']:'';
if(isset($_REQUEST['cta_save'])){
    $data['cta_name'] = sanitize_text_field($_REQUEST['cta_name']);
    $data['cta_description'] = sanitize_text_field($_REQUEST['cta_description']);
    $data['cta_btn_text'] = sanitize_text_field($_REQUEST['cta_btn_text']);
    $data['cta_btn_css'] = sanitize_text_field($_REQUEST['cta_btn_css']);
    $data['cta_type'] = '';
    $data['cta_update_date'] = date('Y-m-d G:i:s');
    if($_REQUEST['action']=='add'){
        $wpdb->insert( WP_CTA_TBL, $data);
        echo "<script>window.location = '".menu_page_url('wp-cta-plugin')."&msg=add';</script>";
        exit;
    }
    if($_REQUEST['action']=='edit'){
        $wpdb->update(WP_CTA_TBL,$data,array('cta_id'=>$id));
        echo "<script>window.location = '".menu_page_url('wp-cta-plugin')."&msg=update';</script>";
        exit;
    }
}
if($action=='edit'){
    $data = $wpdb->get_row("SELECT * FROM ".WP_CTA_TBL." WHERE cta_id =$id", ARRAY_A );
}
?>
<div class="admin_cta_content">
    <h1>Manage WPA</h1>
    <form method="post" action="<?php  menu_page_url('wp-cta-plugin'); ?>" >          
        <div class="cta_fields">
            <label class="lbl">Name</label>
            <input class="txt" name="cta_name" type="text" value="<?php if(isset($data['cta_name'])){echo $data['cta_name'];} ?>" />
        </div>
        <div class="cta_fields">
            <label class="lbl">Description</label>
            <?php 
            $settings = array(
                'quicktags'     => TRUE,
                'media_buttons' => TRUE
            );
            $dt = isset($data['cta_description'])?$data['cta_description']:"";
            wp_editor($dt,'cta_description',$settings); ?>
        </div>
        <div class="cta_fields">
            <label class="lbl">Button Text</label>
            <input class="txt" name="cta_btn_text" value="<?php if(isset($data['cta_btn_text'])){echo $data['cta_btn_text'];} ?>" type="text" />
        </div>
        <div class="cta_fields">
            <label class="lbl">Button color</label>
            <input name="cta_btn_css" value="<?php if(isset($data['cta_btn_css'])){echo $data['cta_btn_css'];} ?>" class="txt jscolor" type="text" />
        </div>
        <div class="cta_fields">
            <label class="lbl"></label>.
            <input type="hidden" name="action" value="<?php echo $action; ?>">
            <input type="hidden" name="id" value="<?php echo $id; ?>">
            <input class="button button-primary" name="cta_save" type="submit" value="Save"  />
        </div>
    </form>
</div>
