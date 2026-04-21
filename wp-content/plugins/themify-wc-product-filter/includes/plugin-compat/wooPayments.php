<?php

/**
 * WooPayments
 * @link https://wordpress.org/plugins/woocommerce-payments/
 */
class Themify_WPF_Plugin_Compat_wooPayments {

	public static function init() {
		add_filter( 'wpf_min_max_price', [ __CLASS__, 'wpf_min_max_price' ] );
		add_filter( 'wpf_filter_by_price', [ __CLASS__, 'wpf_filter_by_price' ] );
	}

	/** Adjust the price displayed in WPF form */
	public static function wpf_min_max_price( $price ) : array {
		if ( self::multi_price_enabled() ) {
            $MultiCurrencyInstance = self::get_mc_instance();
            $price[0] = $MultiCurrencyInstance->get_price( $price[0], 'product' );
            $price[1] = $MultiCurrencyInstance->get_price( $price[1], 'product' );
        }

		return $price;
	}

	/** Adjust the price for price queries from DB */
    public static function wpf_filter_by_price( $price ) : array {
		if ( self::multi_price_enabled() ) {
            $MultiCurrencyInstance = self::get_mc_instance();
            $current_currency_code = $MultiCurrencyInstance->get_selected_currency()->get_code();
            $default_currency_code = $MultiCurrencyInstance->get_default_currency()->get_code();
            if ( $current_currency_code !== $default_currency_code ) {
                $price['from'] = $MultiCurrencyInstance->get_raw_conversion( $price['from'], $default_currency_code, $current_currency_code );
                $price['to'] = $MultiCurrencyInstance->get_raw_conversion( $price['to'], $default_currency_code, $current_currency_code );
            }
        }

        return $price;
    }

    private static function get_mc_instance() {
        return WCPay\MultiCurrency\MultiCurrency::instance();
    }

    private static function multi_price_enabled() : bool {
        $MultiCurrencyInstance = self::get_mc_instance();
        if ( ! $MultiCurrencyInstance->get_compatibility()->should_disable_currency_switching() ) {
            $enabled_currencies = $MultiCurrencyInstance->get_enabled_currencies();
            if ( count( $enabled_currencies ) > 1 ) {
                return true;
            }
        }

        return false;
    }
}