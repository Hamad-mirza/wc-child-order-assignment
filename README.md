# WC Child Order Assignment

**Version:** 1.1.0  
**Author:** Muhammad Hamad  
**Company:** Innovatek Solutions

Assign WooCommerce orders to children created via **WC Child Subscription Manager**. This plugin adds a child selection field during checkout, allowing parents to associate each order with one of their registered children.

---

## Description

WC Child Order Assignment extends WooCommerce by introducing a child selection dropdown on the checkout page. Parents can select which child an order belongs to, and the selected child information is stored with the order.

The assigned child is displayed:

- In the WooCommerce Admin Order screen
- In the customer's My Account order details
- In WooCommerce order emails

This plugin is designed to work alongside a child management system that creates a custom post type named `wc_child` and stores parent-child relationships using the `_parent_user_id` meta key.

---

## Features

- Adds a required **Select Child** dropdown at checkout.
- Displays only children assigned to the logged-in parent.
- Validates child selection before order submission.
- Saves child information directly to the order.
- Displays assigned child in:
  - WooCommerce Admin Orders
  - Customer Order Details
  - Order Emails
- Prevents assignment of children that do not belong to the current user.
- Provides a quick link to add a child when none exist.

---

## Requirements

- WordPress 5.0 or higher
- WooCommerce 5.0 or higher
- WC Child Subscription Manager (or compatible plugin) that:
  - Creates a `wc_child` custom post type
  - Stores parent relationships in `_parent_user_id`

---

## Installation

### Automatic Installation

1. Download the plugin ZIP file.
2. Go to **WordPress Admin → Plugins → Add New**.
3. Click **Upload Plugin**.
4. Select the ZIP file and upload it.
5. Activate the plugin.

### Manual Installation

1. Upload the plugin folder to:

   ```
   wp-content/plugins/wc-child-order-assignment/
   ```

2. Go to **Plugins** in WordPress Admin.
3. Activate **WC Child Order Assignment**.

---

## Usage

### Step 1: Create Children

Ensure children are created through WC Child Subscription Manager and assigned to a parent account.

### Step 2: Checkout

When a logged-in parent places an order:

1. A **Select Child** dropdown appears on the checkout page.
2. The parent chooses the child associated with the purchase.
3. The order is completed normally.

### Step 3: View Assignment

The selected child information will be available in:

- WooCommerce Admin Orders
- Customer My Account → Order Details
- WooCommerce Emails

---

## Order Metadata

The plugin stores the following order metadata:

| Meta Key | Description |
|-----------|-------------|
| `_selected_child_id` | Selected child ID |
| `_selected_child_name` | Selected child name |

---

## Security

The plugin includes multiple validation checks:

- Only logged-in users can assign children.
- Child selection is required during checkout.
- Child IDs are sanitized using `absint()`.
- Selected children must belong to the current parent account.
- Output is escaped using WordPress security functions.

---

## WooCommerce Hooks Used

| Hook | Purpose |
|--------|----------|
| `woocommerce_before_order_notes` | Display child dropdown |
| `woocommerce_checkout_process` | Validate selection |
| `woocommerce_checkout_create_order` | Save child assignment |
| `woocommerce_admin_order_data_after_billing_address` | Show child in admin |
| `woocommerce_order_details_after_order_table` | Show child in My Account |
| `woocommerce_email_order_meta` | Show child in emails |

---

## Compatibility

Tested with:

- WordPress 6.x
- WooCommerce 8.x and above

Compatible with standard WooCommerce checkout and email templates.

---

## Changelog

### Version 1.1.0

- Added child selection dropdown during checkout.
- Added checkout validation.
- Saved child information to order metadata.
- Displayed child assignment in admin order details.
- Displayed child assignment in customer order details.
- Displayed child assignment in WooCommerce emails.
- Added ownership validation for enhanced security.

---

## Support

For support, custom development, or feature requests:

**Muhammad Hamad**  
Website: https://mrhammad.com

**Innovatek Solutions**  
Website: https://innovateksol.com

GitHub: https://github.com/Hamad-mirza/

---

## License

GPL v2 or later

This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation.
