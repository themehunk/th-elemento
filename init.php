<?php
/*
* Plugin Name: A TH simple addon
* Author: ThemeHunk
*/
// file path and url 
// define('SIMPLE_ADDON_URL', TH_WISHLIST_URL.'elemento-simple-addons');
// define('SIMPLE_ADDON_PATH', TH_WISHLIST_PATH.'elemento-simple-addons);

define('SIMPLE_ADDON_URL', plugin_dir_url(__FILE__));
define('SIMPLE_ADDON_PATH', plugin_dir_path(__FILE__));

// return;

class ElementoSimpleAddon
{
    function __construct()
    {
        add_action('elementor/frontend/after_enqueue_styles', [$this, 'style_enque']);
        add_action('elementor/frontend/after_register_scripts', [$this, 'widget_scripts']);
    }

    public function style_enque()
    {
       
        wp_register_style('owl-carousel-css', SIMPLE_ADDON_URL . 'assets/owl-slider/owl.carousel.css');
        wp_register_style('owl-carousel-css-green', SIMPLE_ADDON_URL . 'assets/owl-slider/owl.theme.green.min.css');
        wp_register_style('elemento-addons-simple', SIMPLE_ADDON_URL . 'assets/style.css');
        wp_enqueue_style('owl-carousel-css');
        wp_enqueue_style('owl-carousel-css-green');
        wp_enqueue_style('elemento-addons-simple');
    }

    public function widget_scripts()
    {
        wp_register_script('owl-carousel', SIMPLE_ADDON_URL . 'assets/owl-slider/owl.carousel.min.js', array('jquery'), '', true);
        wp_register_script('owl-carousel-script-simple', SIMPLE_ADDON_URL . 'assets/owl-slider/owl-slider-script.js', [], '', true);
        wp_register_script('simple-addon-secript', SIMPLE_ADDON_URL . 'assets/custom.js', [], '', true);
        wp_enqueue_script('owl-carousel');
        wp_enqueue_script('owl-carousel-script-simple');
        wp_enqueue_script('simple-addon-secript');
        wp_localize_script('simple-addon-secript', 'elemento_simple_url', array('admin_ajax' => admin_url('admin-ajax.php')));

        // elite addons 
    }
}
$ElementoSimpleAddonobj = new ElementoSimpleAddon();

// category register
if (!function_exists('product_shop_add_category')) {
    function elemento_addons_simple_category($elements_manager)
    {
        $elements_manager->add_category(
            'elemento-addon-simple-cate',
            [
                'title' => __('Elemento Addons', 'elemento-addons'),
                'icon' => 'eicon-pro-icon',
            ]
        );
    }
    add_action('elementor/elements/categories_registered', 'elemento_addons_simple_category', 1);
}
// addon register 
if (!function_exists('elemento_addons_simple_addons')) {
    include_once 'post-filter.php';

    function elemento_addons_simple_addons()
    {
        include_once 'product-simple-addon/product-simple-addon.php';
        include_once 'elemento-simple-post/elemento-post.php';
    }
    add_action('elementor/widgets/widgets_registered', 'elemento_addons_simple_addons');
}
// ajx fn 
include_once 'elemento-simple-post/ajx.php';

// wishlist 
if (!function_exists('elemento_addons_wishlist_wpc')) {
    function elemento_addons_wishlist_wpc($productId)
    {
        if (intval($productId) && shortcode_exists('yith_wcwl_add_to_wishlist')) {
            $html = '';
            // $html .= do_shortcode('[yith_wcwl_add_to_wishlist product_id="' . $productId . '" already_in_wishslist_text="<span>' . __('added', 'th-elemento') . '</span>"]');
            $html .= do_shortcode('[woosw id="' . $productId . '"]');
            return $html;
        }
    }
}
// compare 
if (!function_exists('elemento_addons_compare')) {
    function elemento_addons_compare($productId)
    {
        if (intval($productId) && shortcode_exists('th_compare')) {
            $html = '<a href="#" class="th-product-compare-btn button" data-th-product-id="' . $productId . '">';
            $html .= '<i class="fa fa-exchange"></i>';
            $html .= '<span>' . __('Compare', 'th-elemento') . '</span>';
            $html .= '</a>';
            return $html;
        }
    }
}
