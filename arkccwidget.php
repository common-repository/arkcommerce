<?php
/*
ARKCommerce
Copyright (C) 2017-2018 Milan Semen

This program is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program. If not, see https://www.gnu.org/licenses/
*/
//////////////////////////////////////////////////////////////////////////////////////////
// START OF ARKCOMMERCE CURRENCY CONVERSION WIDGET										//
//////////////////////////////////////////////////////////////////////////////////////////
define( 'ARKCOMMERCE_CCW_VERSION', '1.0.0' );

// Prohibit direct access
if( !defined( 'ABSPATH' ) ) exit;

//////////////////////////////////////////////////////////////////////////////////////////
// Add the Widget to WP Widgets															//
//////////////////////////////////////////////////////////////////////////////////////////
function arkcommerce_cc_widget_load_cc_widget()
{
    register_widget( 'WP_Widget_ARKCC' );
}
add_action( 'widgets_init', 'arkcommerce_cc_widget_load_cc_widget' );

//////////////////////////////////////////////////////////////////////////////////////////
// Add ARKCommerce CC Widget Scripts													//
//////////////////////////////////////////////////////////////////////////////////////////
function arkcommerce_ccwidget_add_scripts()
{
	wp_enqueue_script( 'arkcommerce_cc_widget', plugin_dir_url( __FILE__ ) . 'assets/js/convertintoark.js' );
}
add_action( 'wp_enqueue_scripts', 'arkcommerce_ccwidget_add_scripts' );

//////////////////////////////////////////////////////////////////////////////////////////
// ARKCommerce Conversion Widget														//
// @class 		WP_Widget_ARKCC															//
// @extends		WP_Widget																//
// @package		WordPress/Classes/Widget												//
//////////////////////////////////////////////////////////////////////////////////////////
class WP_Widget_ARKCC extends WP_Widget 
{
	function __construct()
	{
		parent::__construct( 'arkcommerce_cc_widget', 'ARKCommerce Converter', array( 'description' => __( 'ARK Conversion', 'arkcommerce' ), ) );
	}
//////////////////////////////////////////////////////////////////////////////////////////
//	ARKCommerce Conversion Widget Presentation											//
//	@output ARKCommerce Conversion Widget												//
//////////////////////////////////////////////////////////////////////////////////////////
	public function widget( $args, $instance )
	{
		// Gather and/or set variables
		$store_currency = get_woocommerce_currency();
		$arkgatewaysettings = get_option( 'woocommerce_ark_gateway_settings' );
		$arkexchangerate = arkcommerce_get_exchange_rate();
		$title = apply_filters( 'widget_title', $instance['title'] );
		
		// Before and after widget arguments are defined by themes
		echo $args['before_widget'];
		if( ! empty( $title ) ) echo $args['before_title'] . $title . $args['after_title'];

		// Conversion Display Form
		echo( '<form onsubmit="return false;"><fieldset><legend>' . __( 'Exchange Rate', 'arkcommerce' ) . '</legend>' . $arkexchangerate . ' ' . $store_currency . ' ' . __( 'per ARK', 'arkcommerce') . '</fieldset><input type="hidden" id="arkexchangerate" value="' . floatval( $arkexchangerate ) . '"><br><fieldset><legend>' . $store_currency . '</legend><input type="number" step="0.01" id="fiatamount" value="0.00" style="width: 100%; color: #8c979e; background-color: black;"></fieldset><br><fieldset><legend>ARK</legend>Ѧ<span id="convertedarkamount"></span></fieldset><br><input type="button" style="width: 100%;" value="' . __( 'Convert', 'arkcommerce' ) . '" onclick="' . "convertToARK(document.getElementById('fiatamount').value, document.getElementById('arkexchangerate').value);return false;" . '"></form>' );
		
		// After widget arguments defined by themes
		echo $args['after_widget'];
	}
//////////////////////////////////////////////////////////////////////////////////////////
//	ARKCommerce Conversion Widget Administration										//
//	@param array $instance																//
//	@output ARKCommerce Conversion Widget Settings										//
//////////////////////////////////////////////////////////////////////////////////////////
	public function form( $instance )
	{
		// Gather and/or set variables
		if ( isset( $instance[ 'title' ] ) ) $title = $instance[ 'title' ];
		else $title = __( 'Currency Converter', 'arkcommerce' );
	
		// Widget admin form
		echo( '<p><label for="' . $this->get_field_id( 'title' ) . '"><' . __( 'Title:', 'arkcommerce' ) . '</label> <input class="widefat" id="' . $this->get_field_id( 'title' ) . '" name="' . $this->get_field_name( 'title' ) . '" type="text" value="' . esc_attr( $title ) . '" /></p>' );
	}
//////////////////////////////////////////////////////////////////////////////////////////
//	ARKCommerce Conversion Widget Instance Updating										//
//	return arr $instance																//
//////////////////////////////////////////////////////////////////////////////////////////
	public function update( $new_instance, $old_instance )
	{
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		return $instance;
	}
}
//////////////////////////////////////////////////////////////////////////////////////////
//	END OF ARKCOMMERCE CURRENCY CONVERSION WIDGET										//
//////////////////////////////////////////////////////////////////////////////////////////