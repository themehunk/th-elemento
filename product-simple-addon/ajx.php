<?php
if (!class_exists('simple_elemento_addon')) {
    class simple_elemento_addon
    {
        function __construct()
        {
            add_action('wp_ajax_elemento_quick_view_product_simple', [$this, 'elemento_quick_view_product_simple_']);
            add_action('wp_ajax_nopriv_elemento_quick_view_product_simple', [$this, 'elemento_quick_view_product_simple_']);
        }
        function elemento_quick_view_product_simple_()
        {
            if (isset($_POST['product_id']) && intval($_POST['product_id'])) {
                $product_id = intval($_POST['product_id']);
                echo $this->getQuickViewHtml($product_id);
            }
            wp_die();
        }
        function  elemento_add_tocart($product, $quickview = false)
        {
            // <a href="%s" rel="nofollow" data-product_id="%s" data-product_sku="%s" data-quantity="%s" class="button th-button %s %s"><span class="dashicons dashicons-cart"></span></a>'
            if ($quickview == 'quickview') {
                $cart_url =  apply_filters(
                    'woocommerce_loop_add_to_cart_link',
                    sprintf(
                        '<a href="%s" rel="nofollow" data-product_id="%s" data-product_sku="%s" data-quantity="%s" class="%s %s">Add To Cart</a>',
                        esc_url($product->add_to_cart_url()),
                        esc_attr($product->get_id()),
                        esc_attr($product->get_sku()),
                        1,
                        // esc_attr(isset($quantity) ? $quantity : 1),
                        $product->is_purchasable() && $product->is_in_stock() ? 'add_to_cart_button' : '',
                        $product->is_purchasable() && $product->is_in_stock() && $product->supports('ajax_add_to_cart') ? 'ajax_add_to_cart' : '',
                        null
                        // esc_html($product->add_to_cart_text())
                    ),
                    $product
                );
            } else {
                $cart_url =  apply_filters(
                    'woocommerce_loop_add_to_cart_link',
                    sprintf(
                        '<a href="%s" rel="nofollow" data-product_id="%s" data-product_sku="%s" data-quantity="%s" class="%s %s"><span class="dashicons dashicons-cart"></span></a>',
                        esc_url($product->add_to_cart_url()),
                        esc_attr($product->get_id()),
                        esc_attr($product->get_sku()),
                        1,
                        // esc_attr(isset($quantity) ? $quantity : 1),
                        $product->is_purchasable() && $product->is_in_stock() ? 'add_to_cart_button' : '',
                        $product->is_purchasable() && $product->is_in_stock() && $product->supports('ajax_add_to_cart') ? 'ajax_add_to_cart' : '',
                        null
                        // esc_html($product->add_to_cart_text())
                    ),
                    $product
                );
            }
            return $cart_url;
        }
        function getQuickViewHtml($product_id)
        {
            $product = wc_get_product($product_id);
            $addToCArt = $this->elemento_add_tocart($product, 'quickview');
            // $productLink = get_permalink($productId);
            $regularPrice = $product->get_regular_price();
            $currentPrice = $product->get_price();
            $checkSale = $regularPrice > $currentPrice ? true : false;
            $rating  = $product->get_average_rating();
            $count_rating   = $product->get_rating_count();
            $ratingHtml = wc_get_rating_html($rating, $count_rating);

            $attachment_ids = $product->get_gallery_image_ids();
            $leftContentImg = $product->get_image();
            if (!empty($attachment_ids)) {


                // slider setting ----------------
                $sliderSetting = [
                    'items' => 1,
                    'autoplay' => true,
                    'autoPlaySpeed' => 2,
                    'slider_loop' => 1,
                    // 'autoPlayDirection' => 1,
                    'slider_controll' => 'dot'
                ];
                // .............. 
                $dataSetting = wp_json_encode($sliderSetting);
                $postHtml = '<div class="elemento-addons-quick-view-slider">';
                $postHtml .= "<div class='elemento-addons-block-slide-wrapper elemento-owl-slider-common-secript' id='elemento-addons-block-slide-wrapper' data-setting='" . $dataSetting . "'>";
                $postHtml .= '<div class="elemento-addons-owl-np-cln elemento-addons-owl-prev"><span class="dashicons dashicons-arrow-left-alt"></span></div>';
                $postHtml .= '<div class="elemento-addons-owl-np-cln elemento-addons-owl-next"><span class="dashicons dashicons-arrow-right-alt"></span></div>';

                $postHtml .= "<div class='elemento-owl-slider owl-carousel owl-theme'>";
                // main image 
                $postHtml .= "<div class='item'>";
                $imageUrl = wp_get_attachment_image_src(get_post_thumbnail_id($product_id), 'single-post-thumbnail');
                $postHtml .= '<div class="elemento-quick-view-slides">';
                $postHtml .= '<img src="' . $imageUrl[0] . '">';
                $postHtml .= '</div>';
                $postHtml .= '</div>';

                foreach ($attachment_ids as $attatchId) {
                    $postHtml .= "<div class='item'>";
                    $imageUrl = wp_get_attachment_url($attatchId);
                    $postHtml .= '<div class="elemento-quick-view-slides">';
                    $postHtml .= '<img src="' . $imageUrl . '">';
                    $postHtml .= '</div>';
                    $postHtml .= '</div>';
                }
                $postHtml .= "</div>";

                $postHtml .= "</div>";
                $postHtml .= "</div>";
                $leftContentImg = $postHtml;
            }

            $ps_sale = $checkSale ? '<div class="elemento-addons-sale">
                        <span class="elemento-addons-sale-tag">Sale</span>
                    </div>' : "";
            $html =  '';
            $html .= '<div class="elemento-quickview-wrapper">';
            $html .= '<div>';
            // close btn 
            $html .= "<span class='elemento-quickview-close'><i class='dashicons dashicons-no-alt'></i></span>";
            // sale tag
            $html .= $ps_sale;
            // left content 
            $html .= '<div class="left_content_">';
            $html .= $leftContentImg;
            $html .= '</div>';
            // left content 
            // right content 
            $html .= '<div class="right_content_">';
            $html .= '<span class="title_">';
            $html .= $product->get_name();
            $html .= '</span>';
            $html .= '<span class="price_">';
            $html .= $product->get_price_html();
            $html .= '</span>';

            $html .= $ratingHtml ? '<div class="elemento-addons-rating">' . $ratingHtml . '</div>' : '';

            $html .= '<span class="description_">';
            $html .= $product->get_description();
            $html .= '</span>';
            $html .= '<span class="category_">';
            $html .= 'Categories : ' . $product->get_categories();
            $html .= '</span>';
            // add to cart 
            $html .= '<div class="quickview-add-to-cart">';
            $html .= '<div class="plus-minus">';
            $html .= '<span class="minus_">-</span>';
            $html .= '<span class="counter_">1</span>';
            $html .= '<span class="plus_">+</span>';
            $html .= '</div>';
            $html .= $addToCArt;
            $html .= '</div>';
            // add to cart 
            $html .= '</div>';
            // right content 
            $html .= '</div>';
            $html .= '</div>';

            return $html;
            // print_r($product);
        }
    }
}
new simple_elemento_addon();
