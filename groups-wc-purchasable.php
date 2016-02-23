<?php
/**
 * groups-wc-purchasable.php
*
* Copyright (c) www.itthinx.com
*
* This code is released under the GNU General Public License.
* See COPYRIGHT.txt and LICENSE.txt.
*
* This code is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU General Public License for more details.
*
* This header and all notices must be kept intact.
*
* @author itthinx
* @package groups
* @since groups 1.0.0
*
* Plugin Name: Groups WooCommerce Purchasable - Example Plugin
* Plugin URI: http://www.itthinx.com/plugins/groups
* Description: An example of how to have products that can only be purchased by group members for WooCommerce with Groups.
* Version: 1.0.0
* Author: itthinx
* Author URI: http://www.itthinx.com
* Donate-Link: http://www.itthinx.com
* License: GPLv3
*/

/**
 * Restrict products that belong to a certain category to be purchasable only by members
 * of a group.
 */
class Groups_WC_Purchasable {

	/**
	 * Product category and group name used to determine if a product should be purchasable by members only.
	 * @var string
	 */
	private static $group = 'Members';

	/**
	 * Registers the restrictions filter.
	 */
	public static function init() {
		add_filter( 'woocommerce_is_purchasable', array( __CLASS__, 'woocommerce_is_purchasable' ), 10, 2 );
	}

	/**
	 * If the product is in the $group product category, it will return true if the current user
	 * belongs to the $group, or false if the user doesn't.
	 * If the product is not in the $group product category, it will return $is_purchasable.
	 * @param boolean $is_purchasable
	 * @param WC_Product $product
	 * @return boolean
	 */
	public static function woocommerce_is_purchasable( $is_purchasable, $product ) {
		$result = $is_purchasable;
		if ( has_term( self::$group, 'product_cat', $product->id ) ) {
			if ( $user_id = get_current_user_id() ) {
				if ( $members = Groups_Group::read_by_name( self::$group ) ) {
					$result = Groups_User_Group::read( $user_id, $group_id ) ? true : false;
				}
			}
		}
		return $result;
	}

}
Groups_WC_Purchasable::init();
