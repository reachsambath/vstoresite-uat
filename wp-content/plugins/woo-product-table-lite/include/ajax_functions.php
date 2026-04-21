<?php
add_action( 'wp_ajax_Itwpt_save_data_form', 'Itwpt_save_data_form' );
function Itwpt_save_data_form() {

	// GLOBAL VARIABLE
	global $wpdb;

	if ( !isset($_REQUEST['nonce']) || ! wp_verify_nonce( $_REQUEST['nonce'], 'itwpt' ) ) {
		$arr = array(
			'success'  => 'no-nonce',
			'products' => array()
		);
		echo ( $arr );
		die();
	}

	$date = date('m/d/Y h:i:s', time());
	$table = $wpdb->prefix.'itpt_posts';

    if ($_REQUEST['dbAction'] == 'update') {
        $data =
            array(
                'plugin_name' => PREFIX_ITWPT_PLUGIN_NAME,
                'title'       => sanitize_text_field($_REQUEST['name']),
                'data'        => sanitize_text_field($_REQUEST['data']),
                'update'      => $date
            );
        $wpdb->update($table, $data, array('id' => sanitize_text_field($_REQUEST['id'])));
    } else {
        $data =
            array(
                'plugin_name' => PREFIX_ITWPT_PLUGIN_NAME,
                'title'       => sanitize_text_field($_REQUEST['name']),
                'data'        => sanitize_text_field($_REQUEST['data']),
                'date'        => $date,
                'update'      => '-'
            );
        $wpdb->insert($table,$data);
        $insert_id = $wpdb->insert_id;
        echo $insert_id;
    }

	die( 0 );

}

add_action( 'wp_ajax_Itwpt_delete_form', 'Itwpt_delete_form' );
function Itwpt_delete_form() {

	// GLOBAL VARIABLE
	global $wpdb;

	if ( !isset($_REQUEST['nonce']) || ! wp_verify_nonce( $_REQUEST['nonce'], 'itwpt' ) ) {
		$arr = array(
			'success'  => 'no-nonce',
			'products' => array()
		);
		echo ( $arr );
		die();
	}

    $table = $wpdb->prefix . sanitize_text_field($_REQUEST['table']);
    $wpdb->delete($table, array('id' => sanitize_text_field($_REQUEST['id'])));

	die( 0 );

}

add_action( 'wp_ajax_Itwpt_copy_form', 'Itwpt_copy_form' );
function Itwpt_copy_form() {

	// GLOBAL VARIABLE
	global $wpdb;

	if ( !isset($_REQUEST['nonce']) || ! wp_verify_nonce( $_REQUEST['nonce'], 'itwpt' ) ) {
		$arr = array(
			'success'  => 'no-nonce',
			'products' => array()
		);
		echo ( $arr );
		die();
	}

    $date = date('m/d/Y h:i:s', time());
    $table = $wpdb->prefix . 'itpt_posts';
    $data = Itwpt_Get_Data_Table('itpt_posts', array('id' => sanitize_text_field($_REQUEST['id'])))[0];

    $data = array(
        'plugin_name' => PREFIX_ITWPT_PLUGIN_NAME,
        'title'       => $data->title . ' - Copy',
        'data'        => $data->data,
        'date'        => $date,
        'update'      => '-'
    );
    $wpdb->insert($table,$data);

	die( 0 );

}

add_action( 'wp_ajax_Itwpt_multi_ajax', 'Itwpt_multi_ajax' );
function Itwpt_multi_ajax() {

	// GLOBAL VARIABLE
	global $wpdb;

	if ( !isset($_REQUEST['nonce']) || ! wp_verify_nonce( $_REQUEST['nonce'], 'itwpt' ) ) {
		$arr = array(
			'success'  => 'no-nonce',
			'products' => array()
		);
		echo ( $arr );
		die();
	}

	// CATEGORY
    $array = array();
    $args = array(
        'taxonomy'     => sanitize_text_field($_REQUEST['vAction']),
        'orderby'      => 'name',
        'show_count'   => 0,
        'pad_counts'   => 0,
        'hierarchical' => 1,
        'title_li'     => '',
        'hide_empty'   => 0
    );

    if($_REQUEST['vAction'] == 'get_product'){

        $product_dp_arg = array(
            'post_type'      => 'product',
            'posts_per_page' => 10,
            'search_title'   => sanitize_text_field($_REQUEST['search']),
        );

        $product_dp = new WP_Query($product_dp_arg);
        $count = 1;
        while ($product_dp->have_posts()) : $product_dp->the_post();
            global $product;
            if ($count++ < 20) {
                $array[] =
                    array(
                        'id'   => get_the_ID(),
                        'text' => get_the_title()
                    );
            }
        endwhile;

        echo json_encode($array);
        die(0);

    }

    $all_categories = get_categories($args);
    foreach ($all_categories as $index => $item) {
        if ($index < 20 && preg_match_all("/" . strtolower($_REQUEST['search']) . "/", strtolower($item->name))) {
            $array[] =
                array(
                    'id'   => $item->term_id,
                    'text' => $item->name
                );
        }
    }
    echo json_encode($array);

	die( 0 );

}

add_action( 'wp_ajax_Itwpt_add_template', 'Itwpt_add_template' );
function Itwpt_add_template() {

	// GLOBAL VARIABLE
	global $wpdb;

	if ( !isset($_REQUEST['nonce']) || ! wp_verify_nonce( $_REQUEST['nonce'], 'itwpt' ) ) {
		$arr = array(
			'success'  => 'no-nonce',
			'products' => array()
		);
		echo ( $arr );
		die();
	}

	// VARIABLES
    $table_prefix = $wpdb->prefix;
    $table = 'itpt_itwpt_templates';

    // CHECK EDIT
    if (empty($_REQUEST['id'])) {

        $data =
            array(
                'name'  => sanitize_text_field($_REQUEST['name']),
                'image' => sanitize_text_field($_REQUEST['image']),
                'data'  => sanitize_text_field($_REQUEST['data']),
            );
        $wpdb->insert($table_prefix . $table, $data);
        $insert_id = $wpdb->insert_id;
        echo $insert_id;

    } else {

        $data =
            array(
                'name'  => sanitize_text_field($_REQUEST['name']),
                'image' => sanitize_text_field($_REQUEST['image']),
                'data'  => sanitize_text_field($_REQUEST['data']),
            );
        $wpdb->update($table_prefix . $table, $data, array('id' => sanitize_text_field($_REQUEST['id'])));

    }

	die( 0 );

}

add_action( 'wp_ajax_Itwpt_add_option', 'Itwpt_add_option' );
function Itwpt_add_option() {

	// GLOBAL VARIABLE
	global $wpdb;

	if ( !isset($_REQUEST['nonce']) || ! wp_verify_nonce( $_REQUEST['nonce'], 'itwpt' ) ) {
		$arr = array(
			'success'  => 'no-nonce',
			'products' => array()
		);
		echo ( $arr );
		die();
	}

	// VARIABLES
    $date = date('m/d/Y h:i:s', time());
    $table_prefix = $wpdb->prefix;
    $table = 'itpt_options';

    $data =
        array(
            'plugin_name' => PREFIX_ITWPT_PLUGIN_NAME,
            'date'        => $date,
            'name'        => sanitize_text_field($_REQUEST['name']),
            'data'        => sanitize_text_field($_REQUEST['data']),
        );
    $adf = $wpdb->update($table_prefix . $table, $data, array('plugin_name' => PREFIX_ITWPT_PLUGIN_NAME, 'name' => 'general'));
    if ($adf === 0) {
        $wpdb->insert($table_prefix . $table, $data);
    }

	die( 0 );

}

add_action( 'wp_ajax_Itwpt_Ajax_Get_Product_Cart', 'Itwpt_Ajax_Get_Product_Cart' );
add_action( 'wp_ajax_nopriv_Itwpt_Ajax_Get_Product_Cart', 'Itwpt_Ajax_Get_Product_Cart' );
function Itwpt_Ajax_Get_Product_Cart() {

    // GLOBAL VARIABLE
    global $wpdb;

    if ( !isset($_REQUEST['nonce']) || ! wp_verify_nonce( $_REQUEST['nonce'], 'itwpt' ) ) {
        $arr = array(
            'success'  => 'no-nonce',
            'products' => array()
        );
        echo ( $arr );
        die();
    }

    echo Itwpt_Get_Product_Cart(true);
    die(0);

}

add_action( 'wp_ajax_Itwpt_Ajax_Remove_All_Product_Cart', 'Itwpt_Ajax_Remove_All_Product_Cart' );
add_action( 'wp_ajax_nopriv_Itwpt_Ajax_Remove_All_Product_Cart', 'Itwpt_Ajax_Remove_All_Product_Cart' );
function Itwpt_Ajax_Remove_All_Product_Cart() {

    // GLOBAL VARIABLE
    global $woocommerce;

    if ( !isset($_REQUEST['nonce']) || ! wp_verify_nonce( $_REQUEST['nonce'], 'itwpt' ) ) {
        $arr = array(
            'success'  => 'no-nonce',
            'products' => array()
        );
        echo ( $arr );
        die();
    }

    $woocommerce->cart->empty_cart();
    WC_AJAX :: get_refreshed_fragments();
    die(0);

}

add_action( 'wp_ajax_Itwpt_Ajax_Remove_By_Id_Product_Cart', 'Itwpt_Ajax_Remove_By_Id_Product_Cart' );
add_action( 'wp_ajax_nopriv_Itwpt_Ajax_Remove_By_Id_Product_Cart', 'Itwpt_Ajax_Remove_By_Id_Product_Cart' );
function Itwpt_Ajax_Remove_By_Id_Product_Cart() {

    // GLOBAL VARIABLE
    global $woocommerce;

    if ( !isset($_REQUEST['nonce']) || ! wp_verify_nonce( $_REQUEST['nonce'], 'itwpt' ) ) {
        $arr = array(
            'success'  => 'no-nonce',
            'products' => array()
        );
        echo ( $arr );
        die();
    }

    WC()->cart->remove_cart_item(sanitize_text_field($_REQUEST['id']));
    WC_AJAX :: get_refreshed_fragments();
    die(0);

}

add_action('wp_ajax_Itwpt_Ajax_Add_Group_Product_Cart', 'Itwpt_Ajax_Add_Group_Product_Cart');
add_action('wp_ajax_nopriv_Itwpt_Ajax_Add_Group_Product_Cart', 'Itwpt_Ajax_Add_Group_Product_Cart');
function Itwpt_Ajax_Add_Group_Product_Cart() {

    if ( !isset($_REQUEST['nonce']) || ! wp_verify_nonce( $_REQUEST['nonce'], 'itwpt' ) ) {
        $arr = array(
            'success'  => 'no-nonce',
            'products' => array()
        );
        echo ( $arr );
        die();
    }

    if (!empty($_REQUEST['products'])) {

        $message_success = array();
        $message_error = array();

        foreach ($_REQUEST['products'] as $item) {

            $_pf = new WC_Product_Factory();
            $_productC = $_pf->get_product($item['id']);

            if (
                (empty($_productC->get_stock_quantity()) ? $item['quantity'] + Itwpt_Get_Product_Quantity_By_Id($item['id']) : (empty($_productC->get_stock_quantity())?$item['quantity']+9:$_productC->get_stock_quantity()))
                >=
                ($item['quantity'] + Itwpt_Get_Product_Quantity_By_Id($item['id']))
                &&
                $_productC->is_in_stock()
                &&
                $_productC->get_stock_status() !== 'onbackorder'
            ) {
                Itwpt_Add_To_Cart_Product($item['id'], $item['variation'], $item['quantity']);
                $message_success[] = $_productC->get_title();
            } else {
                $message_error[] = $_productC->get_title();
            }

        }

        setcookie('itwpt_message_cookie', json_encode(
            array(
                'success' => $message_success,
                'error'   => $message_error
            )
        ), time() + (60), "/");

        WC_AJAX:: get_refreshed_fragments();

    }
    wp_die();

}

add_action('wp_ajax_Itwpt_Ajax_Search', 'Itwpt_Ajax_Search');
add_action('wp_ajax_nopriv_Itwpt_Ajax_Search', 'Itwpt_Ajax_Search');
function Itwpt_Ajax_Search() {

    global $itwpt_rand;
    global $itwpt_data;
    global $itwpt_query;
    global $itwpt_query_data;

    if (!isset($_REQUEST['nonce']) || !wp_verify_nonce($_REQUEST['nonce'], 'itwpt')) {
        $arr = array(
            'success'  => 'no-nonce',
            'products' => array()
        );
        echo($arr);
        die();
    }

    $itwpt_rand = sanitize_text_field($_REQUEST['id']);

    // SET DATA
    $temp_array = array();
    $itwpt_data = json_decode(urldecode($_REQUEST['data']));
    Itwpt_Object_To_Array($itwpt_data, $temp_array);
    $itwpt_data = $temp_array;

    // QUERY
    Itwpt_Data_query($itwpt_data);
    $query = Itwpt_Query_Front();

    // VARIABLE
    $row = Itwpt_Create_Row($query, $itwpt_rand);
    $pagination = Itwpt_Pagination($query);

    wp_send_json(array(
        'row'        => $row,
        'empty'      => empty($row),
        'pagination' => array(
            'count' => $query->max_num_pages,
            'paged' => sanitize_text_field($_REQUEST['paged']),
            'html'  => $pagination
        ),
    ));

}