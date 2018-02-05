<?php
class WP_CTA_Listing extends WP_List_Table
{
    /**
     * Prepare the items for the table to process
     *
     * @return Void
     */
    public function prepare_items()
    {
        global $wpdb;        
        $query = "SELECT cta_id,cta_name,cta_btn_text,cta_update_date FROM ".WP_CTA_TBL;  
        //echo $query;
        //ORDER By and ORDER
        $orderby = !empty($_GET["orderby"]) ? $_GET["orderby"] : 'ASC';
        $order = !empty($_GET["order"]) ? $_GET["order"] : '';
        if(!empty($orderby) & !empty($order)){ $query.=' ORDER BY '.$orderby.' '.$order; }
        
        //TOTAL RECORDS
        $totalitem = $wpdb->get_results($query);  
       
        $totalitems = $wpdb->num_rows;
        
        $perpage = 10;
        $paged=!empty($_GET["paged"])?$_GET["paged"] : '';
        
        if(empty($paged) || !is_numeric($paged) || $paged<=0 ){ $paged=1; }
        $totalpages = ceil($totalitems/$perpage);
        
        if(!empty($paged) && !empty($perpage)){
            $offset=($paged-1)*$perpage;
            $query.=' LIMIT '.(int)$offset.','.(int)$perpage;
        }            
        
        
        /* -- Register the pagination -- */
        $this->set_pagination_args( array(
           "total_items" => $totalitems,
           "total_pages" => $totalpages,
           "per_page" => $perpage,
        ));
        $columns = $this->get_columns();
        $hidden = $this->get_hidden_columns();
        $sortable = $this->get_sortable_columns();
        $this->_column_headers = array($columns, $hidden, $sortable);       
        /* -- Fetch the items -- */        
        $this->items = $wpdb->get_results($query,ARRAY_A);
    }
    /**
     * Override the parent columns method. Defines the columns to use in your listing table
     *
     * @return Array
     */
    public function get_columns()
    {
        $columns = array(
            'cta_id' => 'ID',
            'cta_name'=> 'Title',
            'cta_btn_text'  => 'Description',
            'cta_update_date'        => 'Date'
        );
        return $columns;
    }
    /**
     * Define which columns are hidden
     *
     * @return Array
     */
    public function get_hidden_columns()
    {
        return array();
    }
    /**
     * Define the sortable columns
     *
     * @return Array
     */
    public function get_sortable_columns()
    {
        return array('cta_name' => array('cta_name', false));
    }
    /**
     * Get the table data
     *
     * @return Array
     */
    
    /**
     * Define what data to show on each column of the table
     *
     * @param  Array $item        Data
     * @param  String $column_name - Current column name
     *
     * @return Mixed
     */
    public function column_default( $item, $column_name )
    {
        switch( $column_name ) {
            case 'cta_id':
            case 'cta_name':
            case 'cta_btn_text':
            case 'cta_update_date':            
                return $item[ $column_name ];
            default:
                return print_r( $item, true ) ;
        }
    }
    function column_cta_id($item){
        return sprintf('<input type="checkbox" name="%1$s[]" value="%2$s" />','cta_id', $item['cta_id']);
    }
    function column_cta_name($item){
        //return sprintf($item['cta_name'].'<input type="checkbox" name="%1$s[]" value="%2$s" />');
        $actions = array(
            'edit'      => sprintf('<a href="?page=%s&action=%s&id=%s">Edit</a>','wp-cta-plugin','edit',$item['cta_id']),
            'delete'    => sprintf('<a href="?page=%s&action=%s&id=%s">Delete</a>','wp-cta-plugin','delete',$item['cta_id']),
        );      
        return sprintf('%1$s %2$s',$item['cta_name'],$this->row_actions($actions));
    }
    
    
}
?>
<div class="wrap">
    <h1 class="wp-heading-inline">WP Call To Action Lists</h1>
        <a href="<?php  menu_page_url('wp-cta-plugin'); ?>&action=add" class="page-title-action">Add New</a>
    <?php 
        $CTAListTable = new WP_CTA_Listing();
        $CTAListTable->prepare_items();  
        $CTAListTable->display();
    ?>
</div>