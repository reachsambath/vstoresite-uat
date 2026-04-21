<?php
/**
 * WooCommerce functions
 *
 * @package LiveChat
 */

namespace LiveChat;

use stdClass;

/**
 * Get numeric value from string.
 *
 * @param string $value Stringified numeric value.
 * @return float
 */
function get_numeric_value_from_string( string $value ): float {
	return floatval( preg_replace( '/[^\d\.]/', '', $value ) );
}

/**
 * Get the WooCommerce product variant title.
 *
 * @param array $variation The product variation.
 * @return string
 */
function get_variant_title( array $variation ): string {
	$variant_title = '';

	foreach ( $variation as $attribute_name => $attribute_value ) {
		// Output the variation attribute values (e.g., "Large / black").
		$variant_title .= $attribute_value . ' / ';
	}

	// remove the last ' / '.
	return rtrim( $variant_title, ' / ' );
}

/**
 * Get the WooCommerce cart.
 *
 * @param \WC_Cart $cart The WooCommerce cart.
 * @param string   $currency The currency.
 * @return void
 */
function text_get_cart( \WC_Cart $cart, string $currency ): void {
	$response = new stdClass();

	$response->currency = $currency;
	$response->total    = get_numeric_value_from_string( $cart->get_cart_contents_total() );
	$response->subtotal = get_numeric_value_from_string( $cart->get_subtotal() );

	$response->items = array();

	$items = $cart->get_cart_contents();

	$product_ids = array();
	foreach ( $items as $item ) {
		$product_ids[] = $item['product_id'];
	}

	$products = wc_get_products(
		array(
			'include' => $product_ids,
		)
	);

	foreach ( $items as $item ) {
		$item_product_id = $item['product_id'];
		$product         = $products[ array_search(
			$item_product_id,
			array_map(
				function ( $product ) {
					return $product->get_id();
				},
				$products
			),
			true
		) ];

		$subtotal = $item['line_subtotal'];
		$value    = $item['line_total'];

		$discount = $subtotal - $value;

		$response->items[] = array(
			'id'                => $item_product_id,
			'thumbnailUrl'      => get_the_post_thumbnail_url( $product->get_id(), 'shop_thumbnail' ),
			'title'             => $product->get_name(),
			'variantTitle'      => get_variant_title( $item['variation'] ),
			'variantId'         => $item['variation_id'],
			'discounts'         => array(
				array(
					'amount' => $discount,
				),
			),
			'qty'               => $item['quantity'],
			'value'             => $value,
			'productPreviewUrl' => $product->get_permalink(),
			'sku'               => $product->get_sku(),
		);
	}

	wp_send_json_success( $response );
}

/**
 * Refresh cart action for CI.
 *
 * @return void
 */
function refresh_cart_action(): void {
	$woocommerce = WC();

	$cart = $woocommerce->cart;

	text_get_cart( $cart, get_woocommerce_currency() );
}
