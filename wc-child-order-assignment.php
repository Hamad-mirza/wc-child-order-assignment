<?php
/**
 * Plugin Name: WC Child Order Assignment
 * Description: Assign WooCommerce orders to children created via WC Child Subscription Manager.
 * Version: 1.1.0
Author: <a href="https://mrhammad.com">Muhammad Hamad</a>
Author URI: https://mrhammad.com
Plugin URI: https://github.com/Hamad-mirza/
 */

if (!defined('ABSPATH')) {
    exit;
}

class WC_Child_Order_Assignment {

    public function __construct() {

        // Checkout
        add_action('woocommerce_before_order_notes', array($this, 'add_child_dropdown'));
        add_action('woocommerce_checkout_process', array($this, 'validate_child_selection'));
        add_action('woocommerce_checkout_create_order', array($this, 'save_child_to_order'), 20, 2);

        // Admin Order View
        add_action('woocommerce_admin_order_data_after_billing_address', array($this, 'display_child_admin'));

        // My Account Order View
        add_action('woocommerce_order_details_after_order_table', array($this, 'display_child_frontend'));

        // Emails
        add_action('woocommerce_email_order_meta', array($this, 'display_child_email'), 20, 4);
    }

    /**
     * Get children of a parent
     */
    private function get_user_children($user_id) {

        return get_posts(array(
            'post_type'      => 'wc_child',
            'posts_per_page' => -1,
            'post_status'    => 'publish',
            'meta_key'       => '_parent_user_id',  // Fetch by assigned parent
            'meta_value'     => $user_id,           // Only children assigned to this parent
            'orderby'        => 'title',
            'order'          => 'ASC',
        ));
    }

    /**
     * Add dropdown on checkout
     */
    public function add_child_dropdown($checkout) {

        if (!is_user_logged_in()) {
            return;
        }

        $user_id  = get_current_user_id();
        $children = $this->get_user_children($user_id);

        echo '<div id="wc-child-selection">';
        echo '<h3>Select Child</h3>';

        if (empty($children)) {
            echo '<p>You have not added any children yet.</p>';
            echo '<a href="' . esc_url(site_url('/children/')) . '" class="button" target="_blank" style="margin-top:10px;">Add Child</a>';
            echo '</div>';
            return;
        }

        $options = array('' => 'Select a Child');
        foreach ($children as $child) {
            $options[$child->ID] = $child->post_title;
        }

        woocommerce_form_field('selected_child', array(
            'type'     => 'select',
            'class'    => array('form-row-wide'),
            'label'    => 'Assign this order to a child',
            'required' => true,
            'options'  => $options,
        ), $checkout->get_value('selected_child'));

        echo '</div>';
    }

    /**
     * Validate selection
     */
    public function validate_child_selection() {

        if (!is_user_logged_in()) return;

        if (empty($_POST['selected_child'])) {
            wc_add_notice('Please select a child.', 'error');
        }
    }

    /**
     * Save selected child to order
     */
    public function save_child_to_order($order, $data) {

        if (empty($_POST['selected_child'])) return;

        $child_id = absint($_POST['selected_child']);
        $child    = get_post($child_id);

        if ($child && $child->post_type === 'wc_child') {

            // Check if this child actually belongs to the current parent
            $parent_id = get_post_meta($child_id, '_parent_user_id', true);

            if ($parent_id == get_current_user_id()) {
                $order->update_meta_data('_selected_child_id', $child_id);
                $order->update_meta_data('_selected_child_name', $child->post_title);
            }
        }
    }

    /**
     * Show in Admin Order
     */
    public function display_child_admin($order) {

        $child_name = $order->get_meta('_selected_child_name');

        if ($child_name) {
            echo '<p><strong>Assigned Child:</strong> ' . esc_html($child_name) . '</p>';
        }
    }

    /**
     * Show in My Account
     */
    public function display_child_frontend($order) {

        $child_name = $order->get_meta('_selected_child_name');

        if ($child_name) {
            echo '<p><strong>Child:</strong> ' . esc_html($child_name) . '</p>';
        }
    }

    /**
     * Show in Emails
     */
    public function display_child_email($order, $sent_to_admin, $plain_text, $email) {

        $child_name = $order->get_meta('_selected_child_name');

        if (!$child_name) return;

        if ($plain_text) {
            echo "\nChild: " . $child_name . "\n";
        } else {
            echo '<p><strong>Child:</strong> ' . esc_html($child_name) . '</p>';
        }
    }
}

new WC_Child_Order_Assignment();
