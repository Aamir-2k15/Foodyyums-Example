# Foodyyums-Example
# WCFM Custom Plugin

This repository contains a custom WordPress plugin for managing restaurant menus and customer registrations. The plugin includes various functionalities divided into multiple PHP files for modularity.

## Directory Structure

### Root Directory (`wcfm/`):
- **`shortcodes.php`**: Registers and includes files for various shortcodes.

### `inc/` Directory:
- **`add_menu_item.php`**: Handles AJAX request to add a new menu item as a product.
- **`menu_item_extra.php`**: Contains additional functionality for menu items.
- **`update_menu_item.php`**: Handles AJAX request to update an existing menu item.
- **`view_categories.php`**: Displays a list of categories.
- **`view_menu_items.php`**: Displays a list of menu items.

### `products-manager/` Directory:
- **`add_category_and_list_categories.php`**: Adds a category and lists all categories.
- **`add_menu_item_and_list_menu_items.php`**: Adds a menu item and lists all menu items.
- **`wcfm-view-products-manage.php`**: Manages the view for products.

### `shortcodes_inc/` Directory:
- **`add_restorant_form.php`**: Form to add a restaurant.
- **`listings.php` - `listings_12.php`**: Various listing functionalities for displaying items.
- **`listings_left-sidebar.php`**: Listings with a left sidebar.
- **`listings_right-sidebar.php`**: Listings with a right sidebar.
- **`register_customer.php`**: Handles customer registration.
- **`register_customer_form.php`**: Form for customer registration.
- **`register_restaurant.php`**: Handles restaurant registration.
- **`restaurant_functions.php`**: Contains various functions related to restaurants.
- **`search_form.php`**: Contains a search form for searching items.
- **`_listings-old.php`**: An old version of the listings file.

### `store/` Directory:
- **`right-side.php`**: Manages the right side view of the store.
- **`wcfmmp-view-store-about.php`**: View for the store about section.
- **`wcfmmp-view-store-articles.php`**: View for store articles.
- **`wcfmmp-view-store-header.php`**: View for the store header.
- **`wcfmmp-view-store-products.php`**: View for store products.
- **`wcfmmp-view-store-reviews.php`**: View for store reviews.
- **`wcfmmp-view-store-sidebar.php`**: View for the store sidebar.
- **`wcfmmp-view-store-tabs.php`**: View for the store tabs.
- **`wcfmmp-view-store.php`**: Main view for the store.

## Overall Functionality

This system is a custom plugin for managing restaurant menus and customer registrations in WordPress. It handles adding and displaying menu items, managing categories, and registering customers and restaurants through various shortcodes and AJAX actions. The views and functionalities are divided into different PHP files for modularity.
