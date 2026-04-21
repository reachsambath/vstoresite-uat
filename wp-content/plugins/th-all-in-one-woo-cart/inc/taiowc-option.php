<?php
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'Taiowc_Options' ) ):

    class Taiowc_Options {

        /**
         * Member Variable
         *
         * @var object instance
         */
        private static $instance;

        /**
         * Initiator
         */
        public static function get_instance() {
            if ( ! isset( self::$instance ) ) {
                self::$instance = new self();
            }
            return self::$instance;
        }
        /**
         * Constructor
         */
        public function __construct(){
           add_action( 'init', array( $this,'taiowc_option_settings'), 2 );
        }

        public function taiowc_option_settings(){

            taiowc()->add_setting(
            'taiowc_integration', esc_html__( 'Integration', 'th-all-in-one-woo-cart' ), apply_filters(
            'taiowc_integration_settings_section', array(
                array(
                    'title'  => esc_html__( 'HOW TO ADD CART IN YOUR WEBSITE?','th-all-in-one-woo-cart' ),
                    'fields' => apply_filters(
                        'taiowc_integration_setting_fields', array(
                            array(
                                'id'      => 'taiowc-how-to-integrate',
                                'type'    => 'html',
                                'title'   => esc_html__( 'How To Add','th-all-in-one-woo-cart'),
                            )
                        )
                    )
                 )
              )
            ),apply_filters( 'taiowc_integration_settings_default_active', true )
          );
          taiowc()->add_setting(
            'taiowc_general', esc_html__( 'General', 'th-all-in-one-woo-cart' ), apply_filters(
            'taiowc_general_settings_section', array(
                array(
                    'title'  => esc_html__( 'General', 'th-all-in-one-woo-cart' ),
                    'fields' => apply_filters(
                        'taiowc_general_setting_fields', array(
                            
                            
                            array(
                                'id'      => 'taiowc-show_cart',
                                'type'    => 'checkbox',
                                'title'   => esc_html__( 'Enable Cart', 'th-all-in-one-woo-cart' ),
                                'desc'    => esc_html__( 'Uncheck to disable Floating and Fixed cart. Still you can use "Shortcode" and "Menu" cart', 'th-all-in-one-woo-cart' ),
                                'default' => true
                            ),
                            
                            array(
                                'id'      => 'cart_style',
                                'type'    => 'radio-image',
                                'title'   => esc_html__( 'Cart Style', 'th-all-in-one-woo-cart' ),
                                
                                'options' => array(
                                    'style-1' => esc_url( TAIOWC_IMAGES_URI.'floating-cart.png' ),
                                    'style-2' => esc_url( TAIOWC_IMAGES_URI.'fixed-cart.png' ),
                                    
                                ),

                                'default' => 'style-1'
                            ),
                            array(
                                'id'      => 'taiowc-fxd_cart_position',
                                'type'    => 'select',
                                'title'   => esc_html__( 'Fixed Cart Position', 'th-all-in-one-woo-cart' ),
                                'default' => 'fxd-right',
                                'options' => array(

                                    'fxd-right' => esc_html__( 'Right', 'th-all-in-one-woo-cart' ),
                                    'fxd-left'  => esc_html__( 'Left', 'th-all-in-one-woo-cart'),
                                    
                                ),
                                
                            ),

                            array(
                                'id'      => 'taiowc-fxd_cart_frm_right',
                                'type'    => 'number',
                                'title'   => esc_html__( 'Fixed Cart Position From Right', 'th-all-in-one-woo-cart' ),
                                'default' => 36,
                                'min'     => 1,
                                'max'     => 400,
                                'suffix'  => 'px',
                                
                            ),
                            array(
                                'id'      => 'taiowc-fxd_cart_frm_left',
                                'type'    => 'number',
                                'title'   => esc_html__( 'Fixed Cart Position From Left', 'th-all-in-one-woo-cart' ),
                                'default' => 36,
                                'min'     => 1,
                                'max'     => 400,
                                'suffix'  => 'px',
                                
                            ),

                             array(
                                'id'      => 'taiowc-fxd_cart_frm_btm',
                                'type'    => 'number',
                                'title'   => esc_html__( 'Fixed Cart Position From Bottom', 'th-all-in-one-woo-cart' ),
                                'default' => 36,
                                'min'     => 1,
                                'max'     => 400,
                                'suffix'  => 'px',

                            ),

                            array(
                                'id'      => 'taiowc-cart_effect',
                                'type'    => 'select',
                                'title'   => esc_html__( 'Cart Open Style', 'th-all-in-one-woo-cart' ),
                                'default' =>'taiowc-slide-right',
                                'options' => array(

                                    'taiowc-slide-right'   => esc_html__( 'Slide Right', 'th-all-in-one-woo-cart' ),
                                    'taiowc-slide-left' => esc_html__( 'Slide Left (Pro)', 'th-all-in-one-woo-cart' ),
                                    
                                    'taiowc-click-dropdown' => esc_html__( 'Dropdown on Click (Pro)', 'th-all-in-one-woo-cart' ),
                                    
                                ),
                                
                            ),

                            array(
                                'id'      => 'taiowc-basket_count',
                                'type'    => 'select',
                                'title'   => esc_html__( 'Basket Count', 'th-all-in-one-woo-cart' ),
                                'default' =>'numb_prd',
                                'options' => array(

                                    'numb_prd'   => esc_html__( 'Number of Product', 'th-all-in-one-woo-cart' ),
                                    'quant_prd' => esc_html__( 'Sum of quantity of all products', 'th-all-in-one-woo-cart' ),
                                    
                                ),
                                
                            ),

                            array(
                                'id'      => 'taiowc-cart_item_order',
                                'type'    => 'select',
                                'title'   => esc_html__( 'Cart Product Order (Pro)', 'th-all-in-one-woo-cart' ),
                                'default' =>'prd_first',
                                'options' => array(

                                    'prd_first' => esc_html__( 'Add Product at the Top', 'th-all-in-one-woo-cart' ),

                                    'prd_last'   => esc_html__( 'Add Product at the Bottom (Procoach)','th-all-in-one-woo-cart'),
                                    
                                    
                                ),
                                
                            ),
                            array(
                                'id'      => 'taiowc-not_showing_page',
                                'type'    => 'textarea',
                                'title'   => esc_html__( 'Hide Cart from Pages', 'th-all-in-one-woo-cart' ),
                                'desc'   => esc_html__( 'Use post type/page id/slug separated by comma. For eg: post,contact-us,about-us .For all non woocommerce pages, use no-woocommerce. For checkout page use checkout, for cart page -> cart', 'th-all-in-one-woo-cart' ),
                                'default' =>'',
                                
                                
                            ),

                            array(
                                'id'      => 'taiowc-cart_hd',
                                'type'    => 'text',
                                'title'   => esc_html__( 'Cart Heading', 'th-all-in-one-woo-cart' ),
                                
                                'default' => esc_html__( 'Cart', 'th-all-in-one-woo-cart' ),

                                
                                
                            ),

                            array(
                                'id'      => 'taiowc-empty_cart_txt',
                                'type'    => 'text',
                                'title'   => esc_html__( 'Empty Cart Button Text', 'th-all-in-one-woo-cart' ),
                                
                                'default' => esc_html__( 'Back To Shop', 'th-all-in-one-woo-cart' ),

                                
                                
                            ),

                            array(
                                'id'      => 'taiowc-empty_cart_url',
                                'type'    => 'text',
                                'title'   => esc_html__( 'Empty Cart Button URL', 'th-all-in-one-woo-cart' ),
                                'desc'   => esc_html__( 'Add URL to which you want to redirect user in case of empty cart. By default user will be redirected to shop page.', 'th-all-in-one-woo-cart' ),
                                
                                'default' =>'',
                                
                                
                            ),

                            array(
                                'id'      => 'taiowc-cart-icon',
                                'type'    => 'radio-image',
                                'title'   => esc_html__( 'Choose Cart Icon (Pro)', 'th-all-in-one-woo-cart' ),
                                
                                'options' => array(
                                    'icon-1' => esc_url( TAIOWC_IMAGES_URI.'icon-1.png' ),
                                    'icon-2' => esc_url( TAIOWC_IMAGES_URI.'icon-2.png' ),
                                    'icon-3' => esc_url( TAIOWC_IMAGES_URI.'icon-3.png' ),
                                    'icon-4' => esc_url( TAIOWC_IMAGES_URI.'icon-4.png' ),
                                    'icon-5' => esc_url( TAIOWC_IMAGES_URI.'icon-5.png' ),
                                    'icon-6' => esc_url( TAIOWC_IMAGES_URI.'icon-6.png' ),
                                    'icon-7' => esc_url( TAIOWC_IMAGES_URI.'custom-icon.png' ),
                                ),

                                'default' => 'icon-1'
                            ),

                            array(
                                'id'      => 'taiowc-cart_open',
                                'type'    => 'select',
                                'title'   => esc_html__( 'Cart Open Style (Pro)', 'th-all-in-one-woo-cart' ),
                                'default' =>'simple-open',
                                'options' => array(

                                    'simple-open'   => esc_html__( 'Auto Open with Ajax', 'th-all-in-one-woo-cart' ),
                                    'fly-image-open' => esc_html__( 'Auto Open with Image fly Effect (Pro)', 'th-all-in-one-woo-cart' ),
                                    
                                    
                                ),
                                'desc'    => esc_html__( 'These options will open cart panel as soon as product added to the cart.', 'th-all-in-one-woo-cart' ),
                                
                            )


                                
                        )
                     )
                 )
                

               )
             )
           );

          taiowc()->add_setting(
            'taiowc_cart', esc_html__( 'Cart Settings', 'th-all-in-one-woo-cart' ), apply_filters(
            'taiowc_cart_settings_section', array(
                array(
                    'title'  => esc_html__( 'Product List (Pro)', 'th-all-in-one-woo-cart' ),
                    'fields' => apply_filters(
                        'taiowc_cart_setting_fields', array(
                            array(
                                'id'      => 'taiowc-show_prd_img',
                                'type'    => 'checkbox',
                                'title'   => esc_html__( 'Product Image', 'th-all-in-one-woo-cart' ),
                                'desc'    => esc_html__( 'Uncheck to hide product image from cart panel.', 'th-all-in-one-woo-cart' ),
                                'default' => true
                            ),  
                            array(
                                'id'      => 'taiowc-show_prd_title',
                                'type'    => 'checkbox',
                                'title'   => esc_html__( 'Product Title', 'th-all-in-one-woo-cart' ),
                                'desc'    => esc_html__( 'Uncheck to hide product Title from cart panel.', 'th-all-in-one-woo-cart' ),
                                'default' => true
                            ),  
                            array(
                                'id'      => 'taiowc-show_prd_price',
                                'type'    => 'checkbox',
                                'title'   => esc_html__( 'Product Price', 'th-all-in-one-woo-cart' ),
                                'desc'    => esc_html__( 'Uncheck to hide product Price from cart panel.', 'th-all-in-one-woo-cart' ),
                                'default' => true
                            ),  
                            array(
                                'id'      => 'taiowc-show_prd_quantity',
                                'type'    => 'checkbox',
                                'title'   => esc_html__( 'Product Quantity', 'th-all-in-one-woo-cart' ),
                                'desc'    => esc_html__( 'Uncheck to hide product Quantity from cart panel.', 'th-all-in-one-woo-cart' ),
                                'default' => true
                            ),  
                            array(
                                'id'      => 'taiowc-show_prd_rating',
                                'type'    => 'checkbox',
                                'title'   => esc_html__( 'Product Rating', 'th-all-in-one-woo-cart' ),
                                'desc'    => esc_html__( 'Uncheck to hide product Rating from cart panel.', 'th-all-in-one-woo-cart' ),
                                'default' => true
                            )
                            
                            
                        )
                    )
                 ),


                array(
                    'title'  => esc_html__('PRODUCTS YOU MAY ALSO LIKE (Pro)', 'th-all-in-one-woo-cart' ),
                    'fields' => apply_filters(
                        'taiowc_related_product_setting_fields', array(
                            array(
                                'id'      => 'taiowc-show_rld_product',
                                'type'    => 'checkbox',
                                'title'   => esc_html__( 'Enable', 'th-all-in-one-woo-cart' ),
                                'desc'    => esc_html__( 'Uncheck to hide product slider display below added product list in the cart panel. This slider display products from Cross Sell, Up Sell, Related or custom products depending on the option you choose below.', 'th-all-in-one-woo-cart' ),
                                'default' => true
                            ),  

                            array(
                                'id'      => 'taiowc-product_may_like_tle',
                                'type'    => 'text',
                                'title'   => esc_html__( 'Heading', 'th-all-in-one-woo-cart' ),
                                'default' => esc_html__( 'Products you may like', 'th-all-in-one-woo-cart' ),
                                    
                            ),

                            array(

                                'id'      => 'taiowc-choose_prdct_like',
                                'type'    => 'select',
                                'title'   => esc_html__( 'Choose Product', 'th-all-in-one-woo-cart' ),
                                'default' =>'croos-sell',
                                'options' => array(
                                    'cross-sell'   => esc_html__( 'Cross Sell', 'th-all-in-one-woo-cart' ),
                                    'up-sell'      => esc_html__( 'Up Sell', 'th-all-in-one-woo-cart' ),
                                    'related'      => esc_html__( 'Related', 'th-all-in-one-woo-cart' ),
                                    'product-by-slug'     => esc_html__( 'Your Products', 'th-all-in-one-woo-cart' ),        
                                ),
                                
                            ),  

                            array(
                                'id'      => 'taiowc-product_may_like_id',
                                'type'    => 'text',
                                'title'   => esc_html__( 'Product Slug', 'th-all-in-one-woo-cart' ),
                                'default' => '',
                                'desc'    => esc_html__( 'Use Product Slug separated by comma. For eg: product-1, product-2 ', 'th-all-in-one-woo-cart' ),    
                            )
                            
                        )
                    )
                 ),


                    array(
                    'title'  => esc_html__( 'Payment Settings (Pro)', 'th-all-in-one-woo-cart' ),
                    'fields' => apply_filters(
                        'taiowc_payment_setting_fields', array(
                            array(
                                'id'      => 'taiowc-pay_hd',
                                'type'    => 'text',
                                'title'   => esc_html__( 'Payment Heading', 'th-all-in-one-woo-cart' ),
                                'default' =>esc_html__( 'Payment Details', 'th-all-in-one-woo-cart' ),    
                                
                            ),
                            array(
                                'id'      => 'taiowc-sub_total',
                                'type'    => 'text',
                                'title'   => esc_html__( 'Sub Total Text', 'th-all-in-one-woo-cart' ),
                                'default' =>esc_html__( 'Sub Total', 'th-all-in-one-woo-cart' ),    
                                
                            ),  

                            array(
                                'id'      => 'taiowc-show_shipping',
                                'type'    => 'checkbox',
                                'title'   => esc_html__( 'Show Shipping', 'th-all-in-one-woo-cart' ),
                                'desc'    => esc_html__( 'Uncheck to hide shipping details from cart panel.', 'th-all-in-one-woo-cart' ),
                                'default' => true
                            ),
                            array(
                                'id'      => 'taiowc-ship_txt',
                                'type'    => 'text',
                                'title'   => esc_html__( 'Shipping Text', 'th-all-in-one-woo-cart' ),
                                'default' =>esc_html__( 'Shipping', 'th-all-in-one-woo-cart' ),    
                                
                            ),

                            array(
                                'id'      => 'taiowc-show_discount',
                                'type'    => 'checkbox',
                                'title'   => esc_html__( 'Show Discount', 'th-all-in-one-woo-cart' ),
                                'desc'    => esc_html__( 'Uncheck to hide product discount from cart panel.', 'th-all-in-one-woo-cart' ),
                                'default' => true
                            ),
                            array(
                                'id'      => 'taiowc-discount_txt',
                                'type'    => 'text',
                                'title'   => esc_html__( 'Discount Text', 'th-all-in-one-woo-cart' ),
                                'default' =>esc_html__( 'Discount', 'th-all-in-one-woo-cart' ),    
                                
                            ),
                            array(
                                'id'      => 'taiowc-total_txt',
                                'type'    => 'text',
                                'title'   => esc_html__( 'Total Text', 'th-all-in-one-woo-cart' ),
                                'default' =>esc_html__( 'Total', 'th-all-in-one-woo-cart' ),    
                                
                            )

                            
                            
                        )
                    )
                 ),
                    array(
                    'title'  => esc_html__( 'COUPON SETTINGS', 'th-all-in-one-woo-cart' ),
                    'fields' => apply_filters(
                        'taiowc_coupon_setting_fields', array(   
                              array(

                                'id'      => 'taiowc-show_coupon',
                                'type'    => 'checkbox',
                                'title'   => esc_html__( 'Show Coupon', 'th-all-in-one-woo-cart' ),
                                'default' => true,
                                'desc'   => esc_html__( 'Uncheck to hide coupon details from cart panel.', 'th-all-in-one-woo-cart' ),
                              ),

                             array(

                                'id'      => 'taiowc-coupon_plchdr_txt',
                                'type'    => 'text',
                                'title'   => esc_html__( 'Placeholder Text', 'th-all-in-one-woo-cart' ),
                                'default' =>esc_html__( 'Enter your Promo Code', 'th-all-in-one-woo-cart' ),    
                                
                              ),

                             array(

                                'id'      => 'taiowc-coupon_aply_txt',
                                'type'    => 'text',
                                'title'   => esc_html__( 'Apply Coupon Button Text', 'th-all-in-one-woo-cart' ),
                                'default' =>esc_html__( 'Apply', 'th-all-in-one-woo-cart' ),    
                                
                              ),

                              array(

                                'id'      => 'taiowc-show_coupon_list',
                                'type'    => 'checkbox',
                                'title'   => esc_html__( 'Show Coupon List', 'th-all-in-one-woo-cart' ),
                                'desc'    => esc_html__( 'Uncheck to hide coupon list from cart panel.', 'th-all-in-one-woo-cart' ),
                                'default' => true
                            ),

                              array(
                                'id'      => 'taiowc-coupon_btn_txt',
                                'type'    => 'text',
                                'title'   => esc_html__( 'View Coupon Link Text', 'th-all-in-one-woo-cart' ),
                                'default' =>esc_html__( 'View Coupons', 'th-all-in-one-woo-cart' ),    
                                
                            ),

                               array(

                                'id'      => 'taiowc-show_added_coupon',
                                'type'    => 'checkbox',
                                'title'   => esc_html__( 'Show Added Coupon', 'th-all-in-one-woo-cart' ),
                                'desc'    => esc_html__( 'Uncheck to hide applied coupons list.', 'th-all-in-one-woo-cart' ),
                                'default' => true
                              )
                            
                         )
                      )
                  )        


              )

            )
          );

          taiowc()->add_setting(
            'taiowc_cart_style', esc_html__( 'Cart Style', 'th-all-in-one-woo-cart' ), apply_filters(
            'taiowc_cart_style_settings_section', array(
                array(
                    'title'  => esc_html__( 'MENU CART / SHORTCODE CART (Pro)', 'th-all-in-one-woo-cart' ),
                    'fields' => apply_filters(
                        'taiowc_top_cart_setting_fields', array(

                            array(

                                'id'      => 'taiowc-tpcrt_show_price',
                                'type'    => 'checkbox',
                                'title'   => esc_html__( 'Show Price', 'th-all-in-one-woo-cart' ),
                                'desc'    => '',
                                'default' => true
                              ),

                            array(

                                'id'      => 'taiowc-tpcrt_show_quantity',
                                'type'    => 'checkbox',
                                'title'   => esc_html__( 'Show Quantity', 'th-all-in-one-woo-cart' ),
                                'desc'    => '',
                                'default' => true
                              ),

                             array(
                                'id'      => 'taiowc-tpcrt_prc_font_size',
                                'type'    => 'number',
                                'title'   => esc_html__( 'Price Font Size', 'th-all-in-one-woo-cart' ),
                                'default' => 14,
                                'min'     => 1,
                                'max'     => 50,
                                'suffix'  => 'px'
                            ),

                             array(
                                'id'      => 'taiowc-tpcrt_icon_size',
                                'type'    => 'number',
                                'title'   => esc_html__( 'Icon Size', 'th-all-in-one-woo-cart' ),
                                'default' => 32,
                                'min'     => 1,
                                'max'     => 200,
                                'suffix'  => 'px'
                            ),

                             array(
                                        'id'      => 'taiowc-tpcrt_bg_clr',
                                        'type'    => 'colorpkr',
                                        'title'   => esc_html__( 'Background Color', 'th-all-in-one-woo-cart' ),
                                        'default' => '#fff'
                                        
                                ),
                             array(
                                        'id'      => 'taiowc-tpcrt_price_clr',
                                        'type'    => 'colorpkr',
                                        'title'   => esc_html__( 'Price Color', 'th-all-in-one-woo-cart' ),
                                        'default' => '#111'
                                        
                                ),
                             array(
                                        'id'      => 'taiowc-tpcrt_quantity_bg_clr',
                                        'type'    => 'colorpkr',
                                        'title'   => esc_html__( 'Quantity BG Color', 'th-all-in-one-woo-cart' ),
                                        'default' => '#111'
                                        
                                ),
                              array(
                                        'id'      => 'taiowc-tpcrt_quantity_clr',
                                        'type'    => 'colorpkr',
                                        'title'   => esc_html__( 'Quantity Color', 'th-all-in-one-woo-cart' ),
                                        'default' => '#fff'
                                        
                                ),
                               array(
                                        'id'      => 'taiowc-tpcrt_cart_icon_clr',
                                        'type'    => 'colorpkr',
                                        'title'   => esc_html__( 'Cart Icon Color', 'th-all-in-one-woo-cart' ),
                                        'default' => '#111'
                                        
                                )
                            
                        )
                    )
                 ),
                    array(
                    'title'  => esc_html__( 'FIXED CART / FLOATING CART (Pro)', 'th-all-in-one-woo-cart' ),
                    'fields' => apply_filters(
                        'taiowc_fix_cart_setting_fields', array(

                             array(
                                'id'      => 'taiowc-fxcrt_icon_size',
                                'type'    => 'number',
                                'title'   => esc_html__( 'Icon Size', 'th-all-in-one-woo-cart' ),
                                'default' => 32,
                                'min'     => 1,
                                'max'     => 400,
                                'suffix'  => 'px'
                            ),

                             array(
                                'id'      => 'taiowc-fxcrt_icon_brd_rds',
                                'type'    => 'number',
                                'title'   => esc_html__( 'Border Radius', 'th-all-in-one-woo-cart' ),
                                'default' => 32,
                                'min'     => 0,
                                'max'     => 100,
                                'suffix'  => '%'
                            ),

                             array(

                                'id'      => 'taiowc-fxcrt_show_quantity',
                                'type'    => 'checkbox',
                                'title'   => esc_html__( 'Show Quantity', 'th-all-in-one-woo-cart' ),
                                'desc'    => '',
                                'default' => true
                              ),

                              array(
                                        'id'      => 'taiowc-fxcrt_cart_bg_clr',
                                        'type'    => 'colorpkr',
                                        'title'   => esc_html__( 'Cart BG Color', 'th-all-in-one-woo-cart' ),
                                        'default' => '#fff'
                                        
                                ),

                              array(
                                        'id'      => 'taiowc-fxcrt_cart_icon_clr',
                                        'type'    => 'colorpkr',
                                        'title'   => esc_html__( 'Cart Icon Color', 'th-all-in-one-woo-cart' ),
                                        'default' => '#111'
                                        
                                ),

                              array(
                                        'id'      => 'taiowc-fxcrt_qnty_bg_clr',
                                        'type'    => 'colorpkr',
                                        'title'   => esc_html__( 'Quantity BG Color', 'th-all-in-one-woo-cart' ),
                                        'default' => '#111'
                                        
                                ),
                              array(
                                        'id'      => 'taiowc-fxcrt_qnty_clr',
                                        'type'    => 'colorpkr',
                                        'title'   => esc_html__( 'Quantity Color', 'th-all-in-one-woo-cart' ),
                                        'default' => '#fff'
                                        
                                )
                        
                        )
                    )
                 ),

                array(
                    'title'  => esc_html__( 'Cart Panel Style (Pro)', 'th-all-in-one-woo-cart' ),
                    'fields' => apply_filters(
                        'taiowc_cart_pan_setting_fields', array(

                              array(

                                'id'      => 'taiowc-cart_pan_icon_shw',
                                'type'    => 'checkbox',
                                'title'   => esc_html__( 'Show Icon', 'th-all-in-one-woo-cart' ),
                                'desc'    => '',
                                'default' => true
                              ),
                              array(
                                        'id'      => 'taiowc-cart_pan_icon_clr',
                                        'type'    => 'colorpkr',
                                        'title'   => esc_html__( 'Icon Color', 'th-all-in-one-woo-cart' ),
                                        'default' => '#111'
                                        
                                ),
                               array(
                                        'id'      => 'taiowc-cart_pan_hd_clr',
                                        'type'    => 'colorpkr',
                                        'title'   => esc_html__( 'Heading Color', 'th-all-in-one-woo-cart' ),
                                        'default' => '#111'
                                        
                                ),
                               array(
                                        'id'      => 'taiowc-cart_pan_cls_clr',
                                        'type'    => 'colorpkr',
                                        'title'   => esc_html__( 'close Color', 'th-all-in-one-woo-cart' ),
                                        'default' => '#111'
                                        
                                ),

                               array(
                                        'id'      => 'taiowc-cart_pan_hdr_bg_clr',
                                        'type'    => 'colorpkr',
                                        'title'   => esc_html__( 'Header BG Color', 'th-all-in-one-woo-cart' ),
                                        'default' => '#fff'
                                        
                                ),  
                               array(
                                        'id'      => 'taiowc-cart_pan_bg_clr',
                                        'type'    => 'colorpkr',
                                        'title'   => esc_html__( 'Cart BG Color', 'th-all-in-one-woo-cart' ),
                                        'default' => '#f3f3f3'
                                        
                                ),  

                               array(
                                        'id'      => 'taiowc-cart_pan_prd_bg_clr',
                                        'type'    => 'colorpkr',
                                        'title'   => esc_html__( 'Product BG Color', 'th-all-in-one-woo-cart' ),
                                        'default' => '#fff'
                                        
                                ),
                               array(
                                        'id'      => 'taiowc-cart_pan_prd_tle_clr',
                                        'type'    => 'colorpkr',
                                        'title'   => esc_html__( 'Product Title Color', 'th-all-in-one-woo-cart' ),
                                        'default' => '#111'
                                        
                                ),
                                array(
                                        'id'      => 'taiowc-cart_pan_prd_rat_clr',
                                        'type'    => 'colorpkr',
                                        'title'   => esc_html__( 'Product Rating Color', 'th-all-in-one-woo-cart' ),
                                        'default' => '#e5a632'
                                        
                                ),
                                array(
                                        'id'      => 'taiowc-cart_pan_prd_dlt_clr',
                                        'type'    => 'colorpkr',
                                        'title'   => esc_html__( 'Product Delete Color', 'th-all-in-one-woo-cart' ),
                                        'default' => '#ef6238'
                                        
                                ),
                                array(
                                        'id'      => 'taiowc-cart_pan_prd_txt_clr',
                                        'type'    => 'colorpkr',
                                        'title'   => esc_html__( 'Product Text Color', 'th-all-in-one-woo-cart' ),
                                        'default' => '#111'
                                        
                                ),
                                array(
                                        'id'      => 'taiowc-cart_pan_prd_brd_clr',
                                        'type'    => 'colorpkr',
                                        'title'   => esc_html__( 'Product Border Color', 'th-all-in-one-woo-cart' ),
                                        'default' => '#ebebeb'
                                        
                                )
                        )
                    )
                 ),

                array(
                    'title'  => esc_html__( 'Cart Panel May you like Style (Pro)', 'th-all-in-one-woo-cart' ),
                    'fields' => apply_filters(
                        'taiowc_cart_pan_rltd_setting_fields', array(

                                array(
                                        'id'      => 'taiowc-cart_pan_rltd_hd_bg_clr',
                                        'type'    => 'colorpkr',
                                        'title'   => esc_html__( 'Heading BG Color', 'th-all-in-one-woo-cart' ),
                                        'default' => '#fff'
                                        
                                ),
                                array(
                                        'id'      => 'taiowc-cart_pan_rltd_hd_clr',
                                        'type'    => 'colorpkr',
                                        'title'   => esc_html__( 'Heading Color', 'th-all-in-one-woo-cart' ),
                                        'default' => '#111'
                                        
                                ),
                                array(
                                        'id'      => 'taiowc-cart_pan_rltd_prd_bg_clr',
                                        'type'    => 'colorpkr',
                                        'title'   => esc_html__( 'Product Bg Color', 'th-all-in-one-woo-cart' ),
                                        'default' => '#fff'
                                        
                                ),
                                array(
                                        'id'      => 'taiowc-cart_pan_rltd_prd_tle_clr',
                                        'type'    => 'colorpkr',
                                        'title'   => esc_html__( 'Product Title Color', 'th-all-in-one-woo-cart' ),
                                        'default' => '#111'
                                        
                                ),
                                array(
                                        'id'      => 'taiowc-cart_pan_rltd_prd_rat_clr',
                                        'type'    => 'colorpkr',
                                        'title'   => esc_html__( 'Product Rating Color', 'th-all-in-one-woo-cart' ),
                                        'default' => '#e5a632'    
                                ),
                                array(
                                        'id'      => 'taiowc-cart_pan_rltd_prd_prc_clr',
                                        'type'    => 'colorpkr',
                                        'title'   => esc_html__( 'Product Price Color', 'th-all-in-one-woo-cart' ),
                                        'default' => '#111'    
                                ),
                                array(
                                        'id'      => 'taiowc-cart_pan_rltd_prd_add_bg_clr',
                                        'type'    => 'colorpkr',
                                        'title'   => esc_html__( 'Product Add to cart BG Color', 'th-all-in-one-woo-cart' ),
                                        'default' => '#111'    
                                ),
                                array(
                                        'id'      => 'taiowc-cart_pan_rltd_prd_add_clr',
                                        'type'    => 'colorpkr',
                                        'title'   => esc_html__( 'Product Add to cart Color', 'th-all-in-one-woo-cart' ),
                                        'default' => '#fff'    
                                )
                                
                          )
                      )
                 ),

                array(
                    'title'  => esc_html__( 'Cart Panel payment Style (Pro)', 'th-all-in-one-woo-cart' ),
                    'fields' => apply_filters(
                        'taiowc_cart_pan_pay_setting_fields', array(

                                array(
                                        'id'      => 'taiowc-cart_pan_taiowc-pay_hd_bg_clr',
                                        'type'    => 'colorpkr',
                                        'title'   => esc_html__( 'Heading BG Color', 'th-all-in-one-woo-cart' ),
                                        'default' => '#f3f3f3'
                                        
                                ),
                                array(
                                        'id'      => 'taiowc-cart_pan_taiowc-pay_hd_clr',
                                        'type'    => 'colorpkr',
                                        'title'   => esc_html__( 'Heading Color', 'th-all-in-one-woo-cart' ),
                                        'default' => '#111'
                                        
                                ),
                                array(
                                        'id'      => 'taiowc-cart_pan_pay_bg_clr',
                                        'type'    => 'colorpkr',
                                        'title'   => esc_html__( 'BG Color', 'th-all-in-one-woo-cart' ),
                                        'default' => '#fff'
                                        
                                ),
                                array(
                                        'id'      => 'taiowc-cart_pan_pay_txt_clr',
                                        'type'    => 'colorpkr',
                                        'title'   => esc_html__( 'Text Color', 'th-all-in-one-woo-cart' ),
                                        'default' => '#111'
                                        
                                ),
                                array(
                                        'id'      => 'taiowc-cart_pan_pay_link_clr',
                                        'type'    => 'colorpkr',
                                        'title'   => esc_html__( 'Link Color', 'th-all-in-one-woo-cart' ),
                                        'default' => '#111'
                                        
                                ),

                                array(
                                        'id'      => 'taiowc-cart_pan_pay_btn_bg_clr',
                                        'type'    => 'colorpkr',
                                        'title'   => esc_html__( 'Button BG Color', 'th-all-in-one-woo-cart' ),
                                        'default' => '#111'
                                        
                                ),
                                array(
                                        'id'      => 'taiowc-cart_pan_pay_btn_clr',
                                        'type'    => 'colorpkr',
                                        'title'   => esc_html__( 'Button Color', 'th-all-in-one-woo-cart' ),
                                        'default' => '#fff'
                                        
                                )
                    
                        
                                
                          )
                      )
                 ),

                    array(
                    'title'  => esc_html__( 'Coupon Style (Pro)', 'th-all-in-one-woo-cart' ),
                    'fields' => apply_filters(
                        'taiowc_cart_coupon_setting_fields', array(
                                array(
                                        'id'      => 'taiowc-cart_coupon_box_bg_clr',
                                        'type'    => 'colorpkr',
                                        'title'   => esc_html__( 'Coupon Box BG Color', 'th-all-in-one-woo-cart' ),
                                        'default' => '#f3f3f3'
                                        
                                ),
                                array(
                                        'id'      => 'taiowc-cart_coupon_box_brd_clr',
                                        'type'    => 'colorpkr',
                                        'title'   => esc_html__( 'Coupon Box Border Color', 'th-all-in-one-woo-cart' ),
                                        'default' => '#f3f3f3'
                                        
                                ),
                                array(
                                        'id'      => 'taiowc-cart_coupon_box_txt_clr',
                                        'type'    => 'colorpkr',
                                        'title'   => esc_html__( 'Coupon Box Text Color', 'th-all-in-one-woo-cart' ),
                                        'default' => '#111'
                                        
                                ),
                                array(
                                        'id'      => 'taiowc-cart_coupon_box_submt_clr',
                                        'type'    => 'colorpkr',
                                        'title'   => esc_html__( 'Coupon Box Submit Color', 'th-all-in-one-woo-cart' ),
                                        'default' => '#ef6238'
                                        
                                ),
                                array(
                                        'id'      => 'taiowc-cart_coupon_box_view_clr',
                                        'type'    => 'colorpkr',
                                        'title'   => esc_html__( 'Coupon View Color', 'th-all-in-one-woo-cart' ),
                                        'default' => '#03cd00'
                                        
                                ),

                                array(
                                        'id'      => 'taiowc-cart_coupon_code_bg_clr',
                                        'type'    => 'colorpkr',
                                        'title'   => esc_html__( 'Coupon Code BG Color', 'th-all-in-one-woo-cart' ),
                                        'default' => '#FFF'
                                        
                                ),
                                array(
                                        'id'      => 'taiowc-cart_coupon_code_brd_clr',
                                        'type'    => 'colorpkr',
                                        'title'   => esc_html__( 'Coupon Code Border Color', 'th-all-in-one-woo-cart' ),
                                        'default' => 'rgba(129,129,129,.2)'
                                        
                                ),
                                array(
                                        'id'      => 'taiowc-cart_coupon_code_txt_clr',
                                        'type'    => 'colorpkr',
                                        'title'   => esc_html__( 'Coupon Code Text Color', 'th-all-in-one-woo-cart' ),
                                        'default' => '#111'
                                        
                                ),
                                array(
                                        'id'      => 'taiowc-cart_coupon_code_ofr_clr',
                                        'type'    => 'colorpkr',
                                        'title'   => esc_html__( 'Coupon Code Offer Color', 'th-all-in-one-woo-cart' ),
                                        'default' => '#4CAF50'
                                        
                                ),

                                array(
                                        'id'      => 'taiowc-cart_coupon_code_btn_bg_clr',
                                        'type'    => 'colorpkr',
                                        'title'   => esc_html__( 'Coupon Code Button Bg Color', 'th-all-in-one-woo-cart' ),
                                        'default' => '#111'
                                        
                                ),
                                array(
                                        'id'      => 'taiowc-cart_coupon_code_btn_txt_clr',
                                        'type'    => 'colorpkr',
                                        'title'   => esc_html__( 'Coupon Code Button Text Color', 'th-all-in-one-woo-cart' ),
                                        'default' => '#fff'
                                        
                                ),
                                array(
                                        'id'      => 'taiowc-cart_coupon_code_add_bg_clr',
                                        'type'    => 'colorpkr',
                                        'title'   => esc_html__( 'Added Coupon BG Color', 'th-all-in-one-woo-cart' ),
                                        'default' => '#f6f7f7'
                                        
                                ),
                                array(
                                        'id'      => 'taiowc-cart_coupon_code_add_txt_clr',
                                        'type'    => 'colorpkr',
                                        'title'   => esc_html__( 'Added Coupon Text Color', 'th-all-in-one-woo-cart' ),
                                        'default' => '#111'
                                        
                                ),
                                array(
                                        'id'      => 'taiowc-cart_coupon_code_add_dlt_clr',
                                        'type'    => 'colorpkr',
                                        'title'   => esc_html__( 'Added Coupon Cross Color', 'th-all-in-one-woo-cart' ),
                                        'default' => '#ef6238'
                                        
                                )
                                
                    
                        
                                
                          )
                      )
                 ),

                array(
                    'title'  => esc_html__( 'Cart Panel Notification (Pro)', 'th-all-in-one-woo-cart' ),
                    'fields' => apply_filters(
                        'taiowc_cart_pan_notify_setting_fields', array(

                                array(

                                'id'      => 'taiowc-cart_pan_notify_shw',
                                'type'    => 'checkbox',
                                'title'   => esc_html__( 'Show Notification', 'th-all-in-one-woo-cart' ),
                                'desc'    => '',
                                'default' => true
                              ),
                                array(
                                        'id'      => 'taiowc-success_mgs_bg_clr',
                                        'type'    => 'colorpkr',
                                        'title'   => esc_html__( 'Success BG Color', 'th-all-in-one-woo-cart' ),
                                        'default' => '#4db359'
                                        
                                ),
                                array(
                                        'id'      => 'taiowc-success_mgs_txt_clr',
                                        'type'    => 'colorpkr',
                                        'title'   => esc_html__( 'Success Text Color', 'th-all-in-one-woo-cart' ),
                                        'default' => '#fff'
                                        
                                ),
                                array(
                                        'id'      => 'taiowc-error_mgs_bg_clr',
                                        'type'    => 'colorpkr',
                                        'title'   => esc_html__( 'Error BG Color', 'th-all-in-one-woo-cart' ),
                                        'default' => '#b73d3d'
                                        
                                ),
                                array(
                                        'id'      => 'taiowc-error_mgs_txt_clr',
                                        'type'    => 'colorpkr',
                                        'title'   => esc_html__( 'Error Text Color', 'th-all-in-one-woo-cart' ),
                                        'default' => '#fff'
                                        
                                )
                        
                                
                          )
                      )
                 )
              )
            )
          );

          taiowc()->add_setting(
            'taiowc_mobile_cart', esc_html__( 'Mobile Cart', 'th-all-in-one-woo-cart' ), apply_filters(
            'taiowc_mobile_cart_settings_section', array(
                array(
                    'title'  => esc_html__( 'Menu Cart / Shortcode Cart (Pro)', 'th-all-in-one-woo-cart' ),
                    'fields' => apply_filters(
                        'taiowc_mobile_menu_cart_setting_fields', array(            
                                array(
                                'id'      => 'taiowc-dsble_mnu_crt',
                                'type'    => 'checkbox',
                                'title'   => esc_html__( 'Disable', 'th-all-in-one-woo-cart' ),
                                'desc'    => esc_html__( 'Disable Menu Cart / Shortcode Cart in mobile', 'th-all-in-one-woo-cart' ),
                                'default' => false
                                ),
                                array(
                                'id'      => 'taiowc-dsble_mnu_crt_qnty',
                                'type'    => 'checkbox',
                                'title'   => esc_html__( 'Disable Cart Quantity', 'th-all-in-one-woo-cart' ),
                                'desc'    => '',
                                'default' => false
                                ),
                                array(
                                'id'      => 'taiowc-dsble_mnu_crt_price',
                                'type'    => 'checkbox',
                                'title'   => esc_html__( 'Disable Cart Price', 'th-all-in-one-woo-cart' ),
                                'desc'    => '',
                                'default' => false
                                )

                                
                          )
                      )
                 ),
                   array(
                    'title'  => esc_html__( 'FIXED CART / FLOATING CART (Pro)', 'th-all-in-one-woo-cart' ),
                    'fields' => apply_filters(
                        'taiowc_fxd_mobile_cart_setting_fields', array(            
                                array(
                                'id'      => 'taiowc-dsble_fxd_crt',
                                'type'    => 'checkbox',
                                'title'   => esc_html__( 'Disable', 'th-all-in-one-woo-cart' ),
                                'desc'    => esc_html__( 'Disable Fixed Cart / Floating Cart in mobile', 'th-all-in-one-woo-cart' ),
                                'default' => false
                                ),
                                array(
                                'id'      => 'taiowc-dsble_fxd_crt_qnty',
                                'type'    => 'checkbox',
                                'title'   => esc_html__( 'Disable Quantity', 'th-all-in-one-woo-cart' ),
                                'desc'    => '',
                                'default' => false
                                )    
                          )
                      )
                 ),
                   array(
                    'title'  => esc_html__( 'Cart Panel (Pro)', 'th-all-in-one-woo-cart' ),
                    'fields' => apply_filters(
                        'taiowc_mob_cart_pnl_setting_fields', array(            
                                array(
                                'id'      => 'taiowc-dsble_mob_rel_prd_crt',
                                'type'    => 'checkbox',
                                'title'   => esc_html__( 'Disable Product you may like', 'th-all-in-one-woo-cart' ),
                                'desc'    => '',
                                'default' => false
                                ),
                                array(
                                'id'      => 'taiowc-dsble_mob_ship',
                                'type'    => 'checkbox',
                                'title'   => esc_html__( 'Disable Shipping', 'th-all-in-one-woo-cart' ),
                                'desc'    => '',
                                'default' => false
                                ),
                                 array(
                                'id'      => 'taiowc-dsble_mob_coupan',
                                'type'    => 'checkbox',
                                'title'   => esc_html__( 'Disable Coupon', 'th-all-in-one-woo-cart' ),
                                'desc'    => '',
                                'default' => false
                                ),
                                            
                          )
                      )
                 )
              )
        ));
         
         taiowc()->add_setting(
            'taiowc_reset', esc_html__( 'Reset All Setting', 'th-all-in-one-woo-cart' ), apply_filters(
            'taiowc_reset_settings_section', array(
                array(
                    'title'  => esc_html__( 'Reset All Plugin Settings', 'th-all-in-one-woo-cart' ),
                    'fields' => apply_filters(
                        'taiowc_reset_setting_fields', array(
                            
                        )
                    )
                 )
              )
            )
          );

        taiowc()->add_setting(
            'taiowc_usefull_plugin', esc_html__( 'Themehunk Useful Plugins', 'th-all-in-one-woo-cart' ), apply_filters(
            'taiowc_usefull_plugin_settings_section', array(
                array(
                    'title'  => esc_html__( 'Themehunk Useful Plugins', 'th-all-in-one-woo-cart' ),
                    'fields' => apply_filters(
                        'taiowc_usefull_plugin_setting_fields', array(
                            array(
                                'id'         => 'taiowc-th-Variation-Swatches',
                                'title'      =>'',
                                'type'       => 'usefullplugin',
                                'desc'       => '',
                                'usefull'          => true,
                                'plugin_image' => esc_url('https://ps.w.org/th-variation-swatches/assets/icon-128x128.gif'),
                                'plugin_title'  => esc_html__( 'TH Variation Swatches', 'th-all-in-one-woo-cart' ),
                                'plugin_link'  => esc_url('https://themehunk.com/th-variation-swatches/'),    
                                
                            ),
                            array(
                                'id'         => 'taiowc-th-advance-search',
                                'title'      =>'',
                                'type'       => 'usefullplugin',
                                'desc'       => '',
                                'usefull'          => true,
                                'plugin_image' => esc_url('https://ps.w.org/th-advance-product-search/assets/icon-256x256.gif'),
                                'plugin_title'  => esc_html__( 'TH Advance Product Search', 'th-all-in-one-woo-cart' ),
                                'plugin_link'  => esc_url('https://themehunk.com/advance-product-search/'),    
                                
                            ),
                            array(
                                'id'         => 'taiowc-th-product-compare',
                                'title'      =>'',
                                'type'       => 'usefullplugin',
                                'desc'       => '',
                                'usefull'          => true,
                                'plugin_image' => esc_url('https://ps.w.org/th-product-compare/assets/icon-128x128.gif'),
                                'plugin_title'  => esc_html__( 'Th Product Compare', 'th-all-in-one-woo-cart' ),
                                'plugin_link'  => esc_url('https://themehunk.com/th-product-compare-plugin/'),    
                                
                            ),
                            array(
                                'id'         => 'taiowc-lead-form-builder',
                                'title'      =>'',
                                'type'       => 'usefullplugin',
                                'desc'       => '',
                                'usefull'          => true,
                                'plugin_image' => esc_url('https://ps.w.org/lead-form-builder/assets/icon-128x128.png'),
                                'plugin_title'  => esc_html__( 'Lead Form Builder', 'th-all-in-one-woo-cart' ),
                                'plugin_link'  => esc_url('https://themehunk.com/product/lead-form-builder-pro/'),    
                                
                            ),
                            array(
                                'id'         => 'taiowc-wp-popup-builder',
                                'title'      =>'',
                                'type'       => 'usefullplugin',
                                'desc'       => '',
                                'usefull'          => true,
                                'plugin_image' => esc_url('https://ps.w.org/wp-popup-builder/assets/icon-128x128.png'),
                                'plugin_title'  => esc_html__( 'WP Popup Builder – Popup Forms & Newsletter', 'th-all-in-one-woo-cart' ),
                                'plugin_link'  => esc_url('https://themehunk.com/product/wp-popup-builder/'),    
                                
                            ),
                        )
                    )
                 )
              )
            )
          );

        }

    }
endif;  

Taiowc_Options::get_instance();