<?php

// ----- add column to orders that shows which products were ordered as well as another column with order notes -----
function ec_order_items_column($columns)
{
    $new_columns = array();
    foreach ($columns as $key => $column) {
        $new_columns[$key] = $columns[$key];
        if ($key === 'order_date') {
            $new_columns['ordered_products'] = __('Purchased Courses', 'woo-custom-ec');
            $new_columns['order_notes'] = __('Order Notes');
        }
    }
    return $new_columns;

    //$columns['order_products'] = "Purchased Items";
    //$columns['order_notes'] = "Order Notes";
    //return $columns;
}

//adds filter option for screen view 
add_filter('manage_edit-shop_order_columns', 'ec_order_items_column', 99);
add_filter('manage_edit_shop_order_columns', 'ec_order_notes_column', 99);

// ----- add data to new column that shows which products were ordered -----
function ec_order_items_column_cnt($column)
{
    global $the_order; // the global order object
    if ($column == 'ordered_products') {
        // get items from the order global object
        $order_items = $the_order->get_items();
        if (!is_wp_error($order_items)) {
            foreach ($order_items as $order_item) {
                echo $order_item['quantity'] . '&nbsp;&times;&nbsp;<a href="' . admin_url('post.php?post=' . $order_item['product_id'] . '&action=edit') . '">' . $order_item['name'] . '</a><br />';
            }
        }
    }
}
add_action('manage_shop_order_posts_custom_column', 'ec_order_items_column_cnt', 99);
