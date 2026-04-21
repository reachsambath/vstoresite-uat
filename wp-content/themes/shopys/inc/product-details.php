<?php
/**
 * Product Details — WP Admin sidebar page
 *
 * Adds a "Product Details" submenu under the Shopys top-level menu
 * to display products with count and bulk delete functionality with date filtering.
 *
 * @package Shopys
 */

// ── Add submenu for Product Details ──────────────────────────────────────────
add_action( 'admin_menu', 'shopys_product_details_menu' );
function shopys_product_details_menu() {
    add_submenu_page(
        'shopys-dashboard',
        __( 'Product Details', 'shopys' ),
        __( 'Product Details', 'shopys' ),
        'manage_woocommerce',
        'shopys-product-details',
        'shopys_product_details_page'
    );
}

// ── Handle bulk delete by date range ────────────────────────────────────────
add_action( 'admin_init', 'shopys_handle_delete_by_date_range' );
function shopys_handle_delete_by_date_range() {
    if ( ! isset( $_POST['shopys_delete_by_date_nonce'] ) ) return;
    if ( ! wp_verify_nonce( $_POST['shopys_delete_by_date_nonce'], 'shopys_delete_by_date' ) ) wp_die( __( 'Security check failed', 'shopys' ) );
    if ( ! current_user_can( 'manage_woocommerce' ) ) wp_die( __( 'No permission', 'shopys' ) );
    if ( 'delete_by_date' !== ( isset( $_POST['shopys_action'] ) ? sanitize_text_field( $_POST['shopys_action'] ) : '' ) ) return;

    $date_from = isset( $_POST['delete_date_from'] ) ? sanitize_text_field( $_POST['delete_date_from'] ) : '';
    $date_to   = isset( $_POST['delete_date_to'] )   ? sanitize_text_field( $_POST['delete_date_to'] )   : '';

    if ( empty( $date_from ) || empty( $date_to ) ) {
        add_settings_error( 'shopys_product_details', 'no_dates', __( 'Please select both start and end dates.', 'shopys' ) );
        return;
    }

    $query = new WP_Query( [
        'post_type'      => 'product',
        'posts_per_page' => -1,
        'fields'         => 'ids',
        'date_query'     => [ [ 'after' => $date_from . ' 00:00:00', 'before' => $date_to . ' 23:59:59', 'inclusive' => true ] ],
    ] );

    if ( empty( $query->posts ) ) {
        add_settings_error( 'shopys_product_details', 'no_products_in_range', __( 'No products found in the selected date range.', 'shopys' ) );
        return;
    }

    $deleted = 0;
    foreach ( $query->posts as $id ) { if ( wp_trash_post( $id ) ) $deleted++; }

    add_settings_error( 'shopys_product_details', 'delete_by_date_success',
        sprintf( __( '%d product(s) moved to trash from %s to %s.', 'shopys' ), $deleted, $date_from, $date_to ), 'updated' );
}

// ── Handle recover from trash ───────────────────────────────────────────────
add_action( 'admin_init', 'shopys_handle_recover_from_trash' );
function shopys_handle_recover_from_trash() {
    if ( ! isset( $_POST['shopys_recover_nonce'] ) ) return;
    if ( ! wp_verify_nonce( $_POST['shopys_recover_nonce'], 'shopys_recover' ) ) wp_die( __( 'Security check failed', 'shopys' ) );
    if ( ! current_user_can( 'manage_woocommerce' ) ) wp_die( __( 'No permission', 'shopys' ) );
    if ( 'recover' !== ( isset( $_POST['shopys_action'] ) ? sanitize_text_field( $_POST['shopys_action'] ) : '' ) ) return;

    $product_ids = isset( $_POST['product_ids'] ) ? array_map( 'intval', (array) $_POST['product_ids'] ) : [];
    if ( empty( $product_ids ) ) { add_settings_error( 'shopys_product_details', 'no_products', __( 'No products selected.', 'shopys' ) ); return; }

    $count = 0;
    foreach ( $product_ids as $id ) { if ( wp_untrash_post( $id ) ) $count++; }
    add_settings_error( 'shopys_product_details', 'recover_success', sprintf( __( '%d product(s) recovered.', 'shopys' ), $count ), 'updated' );
}

// ── Handle permanent delete ───────────────────────────────────────────────
add_action( 'admin_init', 'shopys_handle_permanent_delete' );
function shopys_handle_permanent_delete() {
    if ( ! isset( $_POST['shopys_permanent_delete_nonce'] ) ) return;
    if ( ! wp_verify_nonce( $_POST['shopys_permanent_delete_nonce'], 'shopys_permanent_delete' ) ) wp_die( __( 'Security check failed', 'shopys' ) );
    if ( ! current_user_can( 'manage_woocommerce' ) ) wp_die( __( 'No permission', 'shopys' ) );
    if ( 'permanent_delete' !== ( isset( $_POST['shopys_action'] ) ? sanitize_text_field( $_POST['shopys_action'] ) : '' ) ) return;

    $product_ids = isset( $_POST['product_ids'] ) ? array_map( 'intval', (array) $_POST['product_ids'] ) : [];
    if ( empty( $product_ids ) ) { add_settings_error( 'shopys_product_details', 'no_products', __( 'No products selected.', 'shopys' ) ); return; }

    $count = 0;
    foreach ( $product_ids as $id ) { if ( wp_delete_post( $id, true ) ) $count++; }
    add_settings_error( 'shopys_product_details', 'permanent_delete_success', sprintf( __( '%d product(s) permanently deleted.', 'shopys' ), $count ), 'updated' );
}

// ── Handle bulk delete ────────────────────────────────────────────────────
add_action( 'admin_init', 'shopys_handle_product_delete' );
function shopys_handle_product_delete() {
    if ( ! isset( $_POST['shopys_delete_products_nonce'] ) ) return;
    if ( ! wp_verify_nonce( $_POST['shopys_delete_products_nonce'], 'shopys_delete_products' ) ) wp_die( __( 'Security check failed', 'shopys' ) );
    if ( ! current_user_can( 'manage_woocommerce' ) ) wp_die( __( 'No permission', 'shopys' ) );
    if ( 'delete_selected' !== ( isset( $_POST['shopys_action'] ) ? sanitize_text_field( $_POST['shopys_action'] ) : '' ) ) return;

    $product_ids = isset( $_POST['product_ids'] ) ? array_map( 'intval', (array) $_POST['product_ids'] ) : [];
    if ( empty( $product_ids ) ) { add_settings_error( 'shopys_product_details', 'no_products', __( 'No products selected.', 'shopys' ) ); return; }

    $count = 0;
    foreach ( $product_ids as $id ) { if ( wp_trash_post( $id ) ) $count++; }
    add_settings_error( 'shopys_product_details', 'delete_success', sprintf( __( '%d product(s) moved to trash.', 'shopys' ), $count ), 'updated' );
}

// ── Render the Product Details page ──────────────────────────────────────────
function shopys_product_details_page() {
    $search       = isset( $_GET['s'] )            ? sanitize_text_field( $_GET['s'] )            : '';
    $date_from    = isset( $_GET['date_from'] )    ? sanitize_text_field( $_GET['date_from'] )    : '';
    $date_to      = isset( $_GET['date_to'] )      ? sanitize_text_field( $_GET['date_to'] )      : '';
    $trash_filter = isset( $_GET['trash_filter'] ) ? sanitize_text_field( $_GET['trash_filter'] ) : 'published';

    $args = [
        'post_type'      => 'product',
        'posts_per_page' => 20,
        'paged'          => isset( $_GET['paged'] ) ? intval( $_GET['paged'] ) : 1,
        'orderby'        => 'date',
        'order'          => 'DESC',
        'post_status'    => 'trash' === $trash_filter ? 'trash' : 'publish',
    ];
    if ( ! empty( $search ) ) $args['s'] = $search;
    if ( ! empty( $date_from ) || ! empty( $date_to ) ) {
        $dq = [];
        if ( ! empty( $date_from ) ) $dq['after']  = $date_from . ' 00:00:00';
        if ( ! empty( $date_to ) )   $dq['before'] = $date_to . ' 23:59:59';
        $dq['inclusive'] = true;
        $args['date_query'] = [ $dq ];
    }

    $products_query   = new WP_Query( $args );
    $total_products   = (int) wp_count_posts( 'product' )->publish;
    $trashed_products = (int) wp_count_posts( 'product' )->trash;
    $filtered_count   = $products_query->found_posts;
    $is_trash         = 'trash' === $trash_filter;
    ?>
    <div class="spd-wrap">

        <?php // ── INLINE CSS ── ?>
        <style>
        :root {
            --spd-primary:   #6366f1;
            --spd-secondary: #8b5cf6;
            --spd-success:   #10b981;
            --spd-danger:    #ef4444;
            --spd-warning:   #f59e0b;
            --spd-info:      #0ea5e9;
            --spd-dark:      #1e1b4b;
            --spd-gray:      #64748b;
            --spd-light:     #f8fafc;
            --spd-border:    #e2e8f0;
            --spd-white:     #ffffff;
            --spd-radius:    14px;
            --spd-radius-sm: 8px;
            --spd-shadow:    0 4px 24px rgba(99,102,241,.10);
            --spd-shadow-lg: 0 8px 40px rgba(99,102,241,.18);
        }
        *,*::before,*::after{box-sizing:border-box;}

        /* ── Wrap ── */
        .spd-wrap{
            max-width:100%;
            padding:24px 28px;
            background:#f1f5f9;
            min-height:100vh;
            font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,sans-serif;
        }

        /* ── Hero Header ── */
        .spd-hero{
            background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 50%, #a855f7 100%);
            border-radius: var(--spd-radius);
            padding: 36px 40px;
            margin-bottom: 28px;
            position: relative;
            overflow: hidden;
            box-shadow: var(--spd-shadow-lg);
        }
        .spd-hero::before{
            content:'';
            position:absolute;
            top:-60px; right:-60px;
            width:260px; height:260px;
            background:rgba(255,255,255,.08);
            border-radius:50%;
        }
        .spd-hero::after{
            content:'';
            position:absolute;
            bottom:-80px; left:20%;
            width:200px; height:200px;
            background:rgba(255,255,255,.05);
            border-radius:50%;
        }
        .spd-hero-inner{ position:relative; z-index:1; display:flex; align-items:center; gap:20px; }
        .spd-hero-icon{
            width:56px; height:56px;
            background:rgba(255,255,255,.18);
            border-radius:16px;
            display:flex; align-items:center; justify-content:center;
            font-size:26px;
            flex-shrink:0;
        }
        .spd-hero h1{
            margin:0 0 4px 0;
            font-size:26px;
            font-weight:700;
            color:#fff;
            letter-spacing:-.3px;
        }
        .spd-hero p{
            margin:0;
            font-size:14px;
            color:rgba(255,255,255,.78);
            font-weight:400;
        }

        /* ── Stat Cards ── */
        .spd-stats{
            display:grid;
            grid-template-columns:repeat(auto-fit,minmax(200px,1fr));
            gap:16px;
            margin-bottom:24px;
        }
        .spd-stat{
            background:var(--spd-white);
            border-radius:var(--spd-radius);
            padding:22px 24px;
            display:flex;
            align-items:center;
            gap:16px;
            box-shadow:var(--spd-shadow);
            border:1px solid var(--spd-border);
            transition:transform .2s,box-shadow .2s;
        }
        .spd-stat:hover{ transform:translateY(-3px); box-shadow:var(--spd-shadow-lg); }
        .spd-stat-icon{
            width:48px; height:48px;
            border-radius:12px;
            display:flex; align-items:center; justify-content:center;
            font-size:22px;
            flex-shrink:0;
        }
        .spd-stat:nth-child(1) .spd-stat-icon{ background:#eef2ff; }
        .spd-stat:nth-child(2) .spd-stat-icon{ background:#f0fdf4; }
        .spd-stat:nth-child(3) .spd-stat-icon{ background:#fef2f2; }
        .spd-stat-body{}
        .spd-stat-label{
            font-size:11px;
            font-weight:600;
            text-transform:uppercase;
            letter-spacing:.6px;
            color:var(--spd-gray);
            margin-bottom:4px;
        }
        .spd-stat-value{
            font-size:28px;
            font-weight:700;
            line-height:1;
        }
        .spd-stat:nth-child(1) .spd-stat-value{ color:var(--spd-primary); }
        .spd-stat:nth-child(2) .spd-stat-value{ color:var(--spd-success); }
        .spd-stat:nth-child(3) .spd-stat-value{ color:var(--spd-danger); }

        /* ── Notices ── */
        .spd-wrap .notice,.spd-wrap .settings-error{
            border-radius:var(--spd-radius-sm);
            border-left-width:4px;
            margin:0 0 20px 0;
        }

        /* ── Tabs ── */
        .spd-tabs{
            display:flex;
            gap:4px;
            background:var(--spd-white);
            padding:6px;
            border-radius:var(--spd-radius);
            box-shadow:var(--spd-shadow);
            border:1px solid var(--spd-border);
            margin-bottom:20px;
            width:fit-content;
        }
        .spd-tab{
            display:inline-flex;
            align-items:center;
            gap:6px;
            padding:9px 20px;
            text-decoration:none;
            color:var(--spd-gray);
            font-weight:600;
            font-size:13px;
            border-radius:10px;
            transition:all .2s;
        }
        .spd-tab:hover{ color:var(--spd-primary); background:#f5f3ff; }
        .spd-tab.active{
            background: linear-gradient(135deg,var(--spd-primary),var(--spd-secondary));
            color:#fff;
            box-shadow:0 4px 12px rgba(99,102,241,.3);
        }
        .spd-tab-badge{
            background:rgba(255,255,255,.25);
            color:inherit;
            font-size:11px;
            font-weight:700;
            padding:1px 7px;
            border-radius:20px;
        }
        .spd-tab:not(.active) .spd-tab-badge{
            background:#e0e7ff;
            color:var(--spd-primary);
        }

        /* ── Card ── */
        .spd-card{
            background:var(--spd-white);
            border-radius:var(--spd-radius);
            box-shadow:var(--spd-shadow);
            border:1px solid var(--spd-border);
            margin-bottom:20px;
            overflow:hidden;
        }
        .spd-card-header{
            padding:18px 24px;
            border-bottom:1px solid var(--spd-border);
            display:flex;
            align-items:center;
            gap:10px;
        }
        .spd-card-header-icon{
            width:34px; height:34px;
            border-radius:8px;
            background:#fef2f2;
            display:flex; align-items:center; justify-content:center;
            font-size:16px;
        }
        .spd-card-title{
            font-size:14px;
            font-weight:700;
            color:#1e293b;
            margin:0;
        }
        .spd-card-subtitle{
            font-size:12px;
            color:var(--spd-gray);
            margin:2px 0 0 0;
        }
        .spd-card-body{ padding:20px 24px; }

        /* ── Delete by Date Form ── */
        .spd-date-row{
            display:flex;
            align-items:flex-end;
            gap:16px;
            flex-wrap:wrap;
        }
        .spd-form-group{ display:flex; flex-direction:column; flex:1; min-width:160px; }
        .spd-form-label{
            font-size:12px;
            font-weight:600;
            color:#374151;
            margin-bottom:6px;
            letter-spacing:.2px;
        }
        .spd-form-label span{ color:var(--spd-danger); margin-left:3px; }
        .spd-input{
            padding:10px 14px;
            border:1.5px solid var(--spd-border);
            border-radius:var(--spd-radius-sm);
            font-size:13px;
            color:#1e293b;
            background:#fafbfc;
            transition:border-color .2s,box-shadow .2s;
            font-weight:500;
        }
        .spd-input:hover{ border-color:#a5b4fc; }
        .spd-input:focus{ outline:none; border-color:var(--spd-primary); box-shadow:0 0 0 3px rgba(99,102,241,.12); background:#fff; }

        /* ── Filter Bar ── */
        .spd-filter-bar{
            display:flex;
            align-items:flex-end;
            gap:14px;
            flex-wrap:wrap;
        }
        .spd-search-group{ flex:2; min-width:220px; }
        .spd-date-group{ flex:1; min-width:160px; }
        .spd-filter-actions{ display:flex; gap:8px; align-items:flex-end; padding-bottom:0; }

        /* ── Buttons ── */
        .spd-btn{
            display:inline-flex;
            align-items:center;
            gap:6px;
            padding:10px 18px;
            border-radius:var(--spd-radius-sm);
            font-size:13px;
            font-weight:600;
            border:none;
            cursor:pointer;
            text-decoration:none;
            transition:all .2s;
            white-space:nowrap;
            line-height:1;
        }
        .spd-btn-primary{
            background:linear-gradient(135deg,var(--spd-primary),var(--spd-secondary));
            color:#fff;
            box-shadow:0 3px 10px rgba(99,102,241,.3);
        }
        .spd-btn-primary:hover{ transform:translateY(-1px); box-shadow:0 5px 16px rgba(99,102,241,.4); color:#fff; }
        .spd-btn-ghost{
            background:#f1f5f9;
            color:var(--spd-gray);
            border:1.5px solid var(--spd-border);
        }
        .spd-btn-ghost:hover{ background:#e2e8f0; color:#1e293b; }
        .spd-btn-danger{
            background:linear-gradient(135deg,var(--spd-danger),#dc2626);
            color:#fff;
            box-shadow:0 3px 10px rgba(239,68,68,.25);
        }
        .spd-btn-danger:hover{ transform:translateY(-1px); box-shadow:0 5px 16px rgba(239,68,68,.35); color:#fff; }
        .spd-btn-success{
            background:linear-gradient(135deg,var(--spd-success),#059669);
            color:#fff;
            box-shadow:0 3px 10px rgba(16,185,129,.25);
        }
        .spd-btn-success:hover{ transform:translateY(-1px); color:#fff; }
        .spd-btn-sm{ padding:7px 13px; font-size:12px; }
        .spd-btn-info{
            background:linear-gradient(135deg,var(--spd-info),#0284c7);
            color:#fff;
            box-shadow:0 3px 10px rgba(14,165,233,.25);
        }
        .spd-btn-info:hover{ transform:translateY(-1px); color:#fff; }

        /* ── Table ── */
        .spd-table-wrap{
            overflow-x:auto;
            border-radius:var(--spd-radius);
            box-shadow:var(--spd-shadow);
            border:1px solid var(--spd-border);
            margin-bottom:16px;
        }
        .spd-table{
            width:100%;
            border-collapse:collapse;
            font-size:13px;
            background:#fff;
        }
        .spd-table thead tr{
            background:linear-gradient(135deg,#1e1b4b 0%,#312e81 100%);
        }
        .spd-table thead th{
            padding:15px 16px;
            font-size:11px;
            font-weight:700;
            text-transform:uppercase;
            letter-spacing:.7px;
            color:rgba(255,255,255,.9) !important;
            border:none;
            text-align:left;
            white-space:nowrap;
        }
        .spd-table thead th:first-child{ border-radius:var(--spd-radius) 0 0 0; }
        .spd-table thead th:last-child{ border-radius:0 var(--spd-radius) 0 0; }
        .spd-table tbody tr{
            border-bottom:1px solid #f1f5f9;
            transition:background .15s;
        }
        .spd-table tbody tr:last-child{ border-bottom:none; }
        .spd-table tbody tr:hover{ background:#fafbff; }
        .spd-table tbody td{
            padding:14px 16px;
            vertical-align:middle;
            border:none;
            color:#374151;
        }
        .spd-table tbody tr:nth-child(even){ background:#fafcff; }
        .spd-table tbody tr:nth-child(even):hover{ background:#f5f7ff; }

        /* Checkbox col */
        .spd-col-check{ width:44px; text-align:center; }
        .spd-col-check input[type="checkbox"]{ width:16px; height:16px; cursor:pointer; accent-color:var(--spd-primary); }

        /* Image col */
        .spd-col-image{ width:74px; text-align:center; }
        .spd-thumb-link{ display:inline-block; }
        .spd-thumb{
            width:52px; height:52px;
            object-fit:cover;
            border-radius:10px;
            border:2px solid #e2e8f0;
            display:block;
            transition:transform .2s,box-shadow .2s,border-color .2s;
        }
        .spd-thumb-link:hover .spd-thumb{
            transform:scale(1.12);
            box-shadow:0 6px 18px rgba(0,0,0,.18);
            border-color:var(--spd-primary);
        }

        /* ID badge */
        .spd-id{
            display:inline-block;
            background:#eef2ff;
            color:var(--spd-primary);
            padding:3px 10px;
            border-radius:20px;
            font-size:11px;
            font-weight:700;
            letter-spacing:.2px;
        }
        .spd-col-id{ width:70px; }

        /* Product name */
        .spd-product-link{
            color:#1e293b;
            text-decoration:none;
            font-weight:600;
            font-size:13px;
            transition:color .15s;
        }
        .spd-product-link:hover{ color:var(--spd-primary); }

        /* SKU */
        .spd-sku{
            font-size:11px;
            color:var(--spd-gray);
            font-family:monospace;
            background:#f1f5f9;
            padding:2px 8px;
            border-radius:4px;
        }

        /* Price */
        .spd-price{
            font-weight:700;
            color:#059669;
            font-size:13px;
        }

        /* Stock */
        .spd-stock-badge{
            display:inline-block;
            padding:3px 10px;
            border-radius:20px;
            font-size:11px;
            font-weight:700;
        }
        .spd-stock-in{ background:#dcfce7; color:#16a34a; }
        .spd-stock-out{ background:#fef2f2; color:#dc2626; }
        .spd-stock-na{ background:#f1f5f9; color:var(--spd-gray); }

        /* Date */
        .spd-date{ font-size:12px; color:var(--spd-gray); white-space:nowrap; }

        /* Action */
        .spd-col-action{ width:150px; white-space:nowrap; }
        .spd-action-btns{ display:flex; gap:6px; flex-wrap:nowrap; }

        /* Empty */
        .spd-empty-cell{ text-align:center; padding:60px 20px !important; }
        .spd-empty-state{ display:flex; flex-direction:column; align-items:center; gap:12px; color:var(--spd-gray); }
        .spd-empty-icon{ font-size:52px; opacity:.5; }
        .spd-empty-text{ font-size:15px; font-weight:600; }

        /* ── Bulk Action Bar ── */
        .spd-bulk-bar{
            background:#fff;
            border:1px solid var(--spd-border);
            border-radius:var(--spd-radius);
            padding:16px 20px;
            display:flex;
            align-items:center;
            gap:16px;
            box-shadow:var(--spd-shadow);
            margin-bottom:16px;
        }
        .spd-bulk-label{
            font-size:13px;
            font-weight:600;
            color:#374151;
            display:flex;
            align-items:center;
            gap:6px;
        }
        .spd-bulk-label::before{
            content:'';
            width:4px; height:22px;
            background:linear-gradient(var(--spd-primary),var(--spd-secondary));
            border-radius:2px;
            flex-shrink:0;
        }
        .spd-bulk-actions{ display:flex; gap:8px; flex-wrap:wrap; }

        /* ── Pagination ── */
        .spd-pagination{
            background:#fff;
            border:1px solid var(--spd-border);
            border-radius:var(--spd-radius);
            padding:14px 20px;
            text-align:center;
            box-shadow:var(--spd-shadow);
        }
        .spd-pagination .page-numbers{
            display:inline-flex; align-items:center; justify-content:center;
            width:34px; height:34px;
            margin:0 2px;
            border-radius:8px;
            font-size:13px;
            font-weight:600;
            text-decoration:none;
            background:#f1f5f9;
            color:#374151;
            transition:all .2s;
        }
        .spd-pagination .page-numbers:hover{ background:var(--spd-primary); color:#fff; }
        .spd-pagination .page-numbers.current{ background:linear-gradient(135deg,var(--spd-primary),var(--spd-secondary)); color:#fff; box-shadow:0 3px 10px rgba(99,102,241,.3); }
        .spd-pagination .page-numbers.prev,.spd-pagination .page-numbers.next{ width:auto; padding:0 14px; }

        /* ── Responsive ── */
        @media(max-width:900px){
            .spd-wrap{ padding:14px; }
            .spd-hero{ padding:24px 20px; }
            .spd-col-image,.spd-col-date,.spd-col-sku{ display:none; }
            .spd-bulk-bar{ flex-direction:column; align-items:flex-start; }
        }
        </style>

        <?php // ── HERO ── ?>
        <div class="spd-hero">
            <div class="spd-hero-inner">
                <div class="spd-hero-icon">📦</div>
                <div>
                    <h1><?php esc_html_e( 'Product Details', 'shopys' ); ?></h1>
                    <p><?php esc_html_e( 'Manage, filter, and bulk-delete your WooCommerce products with ease.', 'shopys' ); ?></p>
                </div>
            </div>
        </div>

        <?php // ── STATS ── ?>
        <div class="spd-stats">
            <div class="spd-stat">
                <div class="spd-stat-icon">📦</div>
                <div class="spd-stat-body">
                    <div class="spd-stat-label"><?php esc_html_e( 'Total Products', 'shopys' ); ?></div>
                    <div class="spd-stat-value"><?php echo esc_html( number_format( $total_products ) ); ?></div>
                </div>
            </div>
            <div class="spd-stat">
                <div class="spd-stat-icon">🔍</div>
                <div class="spd-stat-body">
                    <div class="spd-stat-label"><?php esc_html_e( 'Filtered Results', 'shopys' ); ?></div>
                    <div class="spd-stat-value"><?php echo esc_html( number_format( $filtered_count ) ); ?></div>
                </div>
            </div>
            <div class="spd-stat">
                <div class="spd-stat-icon">🗑️</div>
                <div class="spd-stat-body">
                    <div class="spd-stat-label"><?php esc_html_e( 'In Trash', 'shopys' ); ?></div>
                    <div class="spd-stat-value"><?php echo esc_html( number_format( $trashed_products ) ); ?></div>
                </div>
            </div>
        </div>

        <?php settings_errors( 'shopys_product_details' ); ?>

        <?php // ── TABS ── ?>
        <div class="spd-tabs">
            <a href="?page=shopys-product-details&trash_filter=published" class="spd-tab <?php echo ! $is_trash ? 'active' : ''; ?>">
                📦 <?php esc_html_e( 'Products', 'shopys' ); ?>
                <span class="spd-tab-badge"><?php echo esc_html( number_format( $total_products ) ); ?></span>
            </a>
            <a href="?page=shopys-product-details&trash_filter=trash" class="spd-tab <?php echo $is_trash ? 'active' : ''; ?>">
                🗑️ <?php esc_html_e( 'Trash', 'shopys' ); ?>
                <span class="spd-tab-badge"><?php echo esc_html( number_format( $trashed_products ) ); ?></span>
            </a>
        </div>

        <?php // ── DELETE BY DATE ── ?>
        <div class="spd-card">
            <div class="spd-card-header">
                <div class="spd-card-header-icon">🗓️</div>
                <div>
                    <p class="spd-card-title"><?php esc_html_e( 'Delete Products by Date Range', 'shopys' ); ?></p>
                    <p class="spd-card-subtitle"><?php esc_html_e( 'Move all products created within a date range to trash.', 'shopys' ); ?></p>
                </div>
            </div>
            <div class="spd-card-body">
                <form method="post" action="">
                    <?php wp_nonce_field( 'shopys_delete_by_date', 'shopys_delete_by_date_nonce' ); ?>
                    <input type="hidden" name="shopys_action" value="delete_by_date" />
                    <div class="spd-date-row">
                        <div class="spd-form-group">
                            <label class="spd-form-label" for="del-date-from"><?php esc_html_e( 'Start Date', 'shopys' ); ?> <span>*</span></label>
                            <input class="spd-input" type="date" id="del-date-from" name="delete_date_from" required />
                        </div>
                        <div class="spd-form-group">
                            <label class="spd-form-label" for="del-date-to"><?php esc_html_e( 'End Date', 'shopys' ); ?> <span>*</span></label>
                            <input class="spd-input" type="date" id="del-date-to" name="delete_date_to" required />
                        </div>
                        <div>
                            <button type="submit" class="spd-btn spd-btn-danger"
                                onclick="return confirm('<?php esc_attr_e( 'Move all products in this date range to trash?', 'shopys' ); ?>');">
                                🗑️ <?php esc_html_e( 'Delete Range', 'shopys' ); ?>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <?php // ── FILTER BAR ── ?>
        <div class="spd-card">
            <div class="spd-card-body" style="padding:18px 24px;">
                <form method="get" action="">
                    <input type="hidden" name="page" value="shopys-product-details" />
                    <?php if ( $is_trash ) : ?><input type="hidden" name="trash_filter" value="trash" /><?php endif; ?>
                    <div class="spd-filter-bar">
                        <div class="spd-form-group spd-search-group">
                            <label class="spd-form-label" for="spd-search">🔎 <?php esc_html_e( 'Search Products', 'shopys' ); ?></label>
                            <input class="spd-input" type="text" id="spd-search" name="s" value="<?php echo esc_attr( $search ); ?>" placeholder="<?php esc_attr_e( 'Name or ID…', 'shopys' ); ?>" />
                        </div>
                        <div class="spd-form-group spd-date-group">
                            <label class="spd-form-label" for="spd-date-from">📅 <?php esc_html_e( 'From', 'shopys' ); ?></label>
                            <input class="spd-input" type="date" id="spd-date-from" name="date_from" value="<?php echo esc_attr( $date_from ); ?>" />
                        </div>
                        <div class="spd-form-group spd-date-group">
                            <label class="spd-form-label" for="spd-date-to">📅 <?php esc_html_e( 'To', 'shopys' ); ?></label>
                            <input class="spd-input" type="date" id="spd-date-to" name="date_to" value="<?php echo esc_attr( $date_to ); ?>" />
                        </div>
                        <div class="spd-filter-actions">
                            <button type="submit" class="spd-btn spd-btn-primary">🔍 <?php esc_html_e( 'Filter', 'shopys' ); ?></button>
                            <a href="?page=shopys-product-details<?php echo $is_trash ? '&trash_filter=trash' : ''; ?>" class="spd-btn spd-btn-ghost">✕ <?php esc_html_e( 'Reset', 'shopys' ); ?></a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <?php // ── PRODUCTS TABLE ── ?>
        <form method="post" action="">
            <?php
            if ( $is_trash ) {
                wp_nonce_field( 'shopys_recover', 'shopys_recover_nonce' );
                wp_nonce_field( 'shopys_permanent_delete', 'shopys_permanent_delete_nonce' );
            } else {
                wp_nonce_field( 'shopys_delete_products', 'shopys_delete_products_nonce' );
            }
            ?>
            <input type="hidden" name="shopys_action" value="<?php echo $is_trash ? 'recover' : 'delete_selected'; ?>" />

            <div class="spd-table-wrap">
                <table class="spd-table">
                    <thead>
                        <tr>
                            <th class="spd-col-check"><input type="checkbox" id="spd-select-all" /></th>
                            <th class="spd-col-image"><?php esc_html_e( 'Image', 'shopys' ); ?></th>
                            <th class="spd-col-id"><?php esc_html_e( 'ID', 'shopys' ); ?></th>
                            <th><?php esc_html_e( 'Product', 'shopys' ); ?></th>
                            <th class="spd-col-sku"><?php esc_html_e( 'SKU', 'shopys' ); ?></th>
                            <th><?php esc_html_e( 'Price', 'shopys' ); ?></th>
                            <th><?php esc_html_e( 'Stock', 'shopys' ); ?></th>
                            <th class="spd-col-date"><?php esc_html_e( 'Date', 'shopys' ); ?></th>
                            <th class="spd-col-action"><?php esc_html_e( 'Actions', 'shopys' ); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ( $products_query->have_posts() ) :
                            while ( $products_query->have_posts() ) :
                                $products_query->the_post();
                                $product       = wc_get_product( get_the_ID() );
                                $product_id    = get_the_ID();
                                $sku           = $product->get_sku();
                                $price_val     = $product->get_price();
                                $stock_qty     = $product->get_stock_quantity();
                                $manage_stock  = $product->get_manage_stock();
                                $date_created  = get_the_date( 'M d, Y' );
                                $thumb_id      = get_post_thumbnail_id( $product_id );
                                $thumb_url     = $thumb_id
                                    ? wp_get_attachment_image_url( $thumb_id, [ 60, 60 ] )
                                    : wc_placeholder_img_src( [ 60, 60 ] );

                                // Stock display
                                if ( $manage_stock && $stock_qty !== null ) {
                                    $stock_class = $stock_qty > 0 ? 'spd-stock-in' : 'spd-stock-out';
                                    $stock_label = $stock_qty > 0 ? $stock_qty : __( 'Out', 'shopys' );
                                } else {
                                    $stock_class = 'spd-stock-na';
                                    $stock_label = '—';
                                }
                            ?>
                            <tr>
                                <td class="spd-col-check">
                                    <input type="checkbox" name="product_ids[]" value="<?php echo esc_attr( $product_id ); ?>" />
                                </td>
                                <td class="spd-col-image">
                                    <a href="<?php echo esc_url( get_permalink( $product_id ) ); ?>" target="_blank" class="spd-thumb-link" title="<?php echo esc_attr( get_the_title() ); ?>">
                                        <img src="<?php echo esc_url( $thumb_url ); ?>" alt="<?php echo esc_attr( get_the_title() ); ?>" class="spd-thumb" width="52" height="52" loading="lazy" />
                                    </a>
                                </td>
                                <td class="spd-col-id">
                                    <span class="spd-id"><?php echo esc_html( $product_id ); ?></span>
                                </td>
                                <td>
                                    <a href="<?php echo esc_url( get_edit_post_link( $product_id ) ); ?>" target="_blank" class="spd-product-link">
                                        <?php echo esc_html( get_the_title() ); ?>
                                    </a>
                                </td>
                                <td class="spd-col-sku">
                                    <?php if ( $sku ) : ?>
                                        <span class="spd-sku"><?php echo esc_html( $sku ); ?></span>
                                    <?php else : ?>
                                        <span style="color:#cbd5e1;">—</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ( $price_val ) : ?>
                                        <span class="spd-price"><?php echo wp_kses_post( wc_price( $price_val ) ); ?></span>
                                    <?php else : ?>
                                        <span style="color:#cbd5e1;">—</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="spd-stock-badge <?php echo esc_attr( $stock_class ); ?>"><?php echo esc_html( $stock_label ); ?></span>
                                </td>
                                <td class="spd-col-date">
                                    <span class="spd-date"><?php echo esc_html( $date_created ); ?></span>
                                </td>
                                <td class="spd-col-action">
                                    <div class="spd-action-btns">
                                        <a href="<?php echo esc_url( get_edit_post_link( $product_id ) ); ?>" class="spd-btn spd-btn-ghost spd-btn-sm" target="_blank">✏️ <?php esc_html_e( 'Edit', 'shopys' ); ?></a>
                                        <a href="<?php echo esc_url( get_permalink( $product_id ) ); ?>" class="spd-btn spd-btn-info spd-btn-sm" target="_blank">👁 <?php esc_html_e( 'View', 'shopys' ); ?></a>
                                    </div>
                                </td>
                            </tr>
                            <?php endwhile;
                        else : ?>
                            <tr>
                                <td colspan="9" class="spd-empty-cell">
                                    <div class="spd-empty-state">
                                        <div class="spd-empty-icon">📭</div>
                                        <div class="spd-empty-text"><?php esc_html_e( 'No products found', 'shopys' ); ?></div>
                                    </div>
                                </td>
                            </tr>
                        <?php endif;
                        wp_reset_postdata(); ?>
                    </tbody>
                </table>
            </div>

            <?php if ( $products_query->have_posts() || $products_query->post_count > 0 ) : ?>
            <div class="spd-bulk-bar">
                <div class="spd-bulk-label">⚡ <?php esc_html_e( 'Bulk Actions', 'shopys' ); ?></div>
                <div class="spd-bulk-actions">
                    <?php if ( $is_trash ) : ?>
                        <button type="submit" class="spd-btn spd-btn-success"
                            onclick="this.form.shopys_action.value='recover'; return confirm('<?php esc_attr_e( 'Recover selected products?', 'shopys' ); ?>');">
                            ↩️ <?php esc_html_e( 'Recover Selected', 'shopys' ); ?>
                        </button>
                        <button type="submit" class="spd-btn spd-btn-danger"
                            onclick="this.form.shopys_action.value='permanent_delete'; return confirm('<?php esc_attr_e( 'PERMANENTLY delete selected products? This cannot be undone!', 'shopys' ); ?>');">
                            🗑️ <?php esc_html_e( 'Delete Permanently', 'shopys' ); ?>
                        </button>
                    <?php else : ?>
                        <button type="submit" class="spd-btn spd-btn-danger"
                            onclick="return confirm('<?php esc_attr_e( 'Move selected products to trash?', 'shopys' ); ?>');">
                            🗑️ <?php esc_html_e( 'Move to Trash', 'shopys' ); ?>
                        </button>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>

        </form>

        <?php // ── PAGINATION ── ?>
        <?php if ( $products_query->max_num_pages > 1 ) : ?>
        <div class="spd-pagination">
            <?php
            echo wp_kses_post( paginate_links( [
                'base'      => add_query_arg( 'paged', '%#%' ),
                'format'    => '',
                'prev_text' => '← ' . __( 'Prev', 'shopys' ),
                'next_text' => __( 'Next', 'shopys' ) . ' →',
                'total'     => $products_query->max_num_pages,
                'current'   => max( 1, isset( $_GET['paged'] ) ? intval( $_GET['paged'] ) : 1 ),
            ] ) );
            ?>
        </div>
        <?php endif; ?>

    </div>

    <?php // ── JAVASCRIPT ── ?>
    <script>
    (function () {
        const all  = document.getElementById('spd-select-all');
        const cbs  = () => document.querySelectorAll('input[name="product_ids[]"]');
        if (!all) return;
        all.addEventListener('change', () => cbs().forEach(c => c.checked = all.checked));
        document.addEventListener('change', e => {
            if (e.target.name === 'product_ids[]')
                all.checked = [...cbs()].every(c => c.checked);
        });
    })();
    </script>
    <?php
}
