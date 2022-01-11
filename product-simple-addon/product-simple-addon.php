<?php
// Elementor Classes.
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Core\Schemes\Color as Scheme_Color;

class elementoProductSimple extends Widget_Base
{
    public function get_name()
    {
        return 'elemento-product-simple';
    }
    private function post_filter()
    {
        return new Th_Simple_Post_filter();
    }
    public function get_title()
    {
        return __('Products Simple Addon', 'elemento-addons');
    }

    public function get_icon()
    {
        return 'eicon-products';
    }

    public function get_categories()
    {
        return ['elemento-addon-simple-cate', 'prodect-shop-category'];
    }
    protected function _register_controls()
    {
        $this->productSetting();
        $this->SliderControlls();
        $this->containerStyle();
        $this->titleStyle();
        $this->priceStyle();
        $this->product_sale_style();
        if (function_exists('th_elemento_addon_quickView_enable')) {
            $this->product_quick_view_style();
        }
        $this->ratingStyle();
        $this->cartButton();
    }
    // content general controlls register 
    protected function product_quick_view_style()
    {
        $this->start_controls_section(
            'quick_view_style',
            [
                'label' => __('Quick View', 'elemento-addons'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );
        // quick view color ---------------------------------+++++++++++++++++
        $this->add_control(
            'quick_viewsection_preview',
            [
                'label'        => __('Preview', 'elemento-addons'),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __('Yes', 'elemento-addons'),
                'label_off'    => __('No', 'elemento-addons'),
                'return_value' => 'on',
                'default'      => '',
                'prefix_class' => 'elemento-simple-product-preview-2',
                'description' => __('This is only for backend Preview on hover Items.', 'elemento-addons'),
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'quickview_typography',
                'selector' => '{{WRAPPER}} .elemento-product-outer-wrap a.elemento-addons-quickview-simple',
                'fields_options' => [
                    'font_size' => [
                        'default' => [
                            'unit' => 'px',
                            'size' => '13',
                        ],
                    ],
                    'letter_spacing' => [
                        'default' => [
                            'unit' => 'px',
                            'size' => '0.5',
                        ],
                    ],
                ],
            ]
        );
        $this->start_controls_tabs('tabs_quickview_style');
        $this->start_controls_tab(
            'tabs_quickview_style_normal',
            [
                'label'     => __('Normal', 'elemento-addons'),
            ]
        );

        $this->add_control(
            'quickview_btn',
            [
                'label'     => __('Color', 'elemento-addons'),
                'type'      => Controls_Manager::COLOR,
                "default"   => "white",
                'scheme'    => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_1,
                ],
                'selectors' => [
                    '{{WRAPPER}} .elemento-product-outer-wrap a.elemento-addons-quickview-simple' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'quickview_btn_bg',
            [
                'label'     => __('Background Color', 'elemento-addons'),
                'type'      => Controls_Manager::COLOR,
                "default"   => "#20C9AE",
                'scheme'    => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_1,
                ],
                'selectors' => [
                    '{{WRAPPER}} .elemento-product-outer-wrap a.elemento-addons-quickview-simple' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'tabs_quickview_style_hover',
            [
                'label'     => __('Hover', 'elemento-addons'),
            ]
        );
        $this->add_control(
            'quickview_btn_hover',
            [
                'label'     => __('Color', 'elemento-addons'),
                'type'      => Controls_Manager::COLOR,
                "default"   => "white",
                'scheme'    => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_1,
                ],
                'selectors' => [
                    '{{WRAPPER}} .elemento-product-outer-wrap a.elemento-addons-quickview-simple:hover' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'quickview_btn_bg_hover',
            [
                'label'     => __('background Color', 'elemento-addons'),
                'type'      => Controls_Manager::COLOR,
                "default"   => "#20C9AE",
                'scheme'    => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_1,
                ],
                'selectors' => [
                    '{{WRAPPER}} .elemento-product-outer-wrap a.elemento-addons-quickview-simple:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();
    }
    protected function productSetting()
    {
        $this->start_controls_section(
            'product_content',
            [
                'label' => __("Product Settings", 'elemento-addons'),
            ]
        );
        $this->add_control(
            'post_category',
            [
                'label'       => __('Select Product Category', 'elemento-addons'),
                'type'        => Controls_Manager::SELECT2,
                'default'     => ['all'],
                'label_block' => false,
                'multiple'    => true,
                'options'     => $this->post_filter()->elemento_get_category(),
            ]
        );
        $this->add_control(
            'number_of_product',
            [
                'type'        => Controls_Manager::NUMBER,
                'label'       => __('Number of Product', 'elemento-addons'),
                'default'     => 12,
            ]
        );
        $this->add_control(
            'product_show_by',
            [
                'label'   => __('Choose Option', 'elemento-addons'),
                'type'    => Controls_Manager::SELECT,
                'options' => [
                    'recent' => "Recent",
                    'random' => "Random",
                    'featured' => "Featured",
                ],
                'default' => 'recent',
            ]
        );
        $this->end_controls_section();
    }
    protected function SliderControlls()
    {
        $this->start_controls_section(
            'slider_content',
            [
                'label' => "Slider Settings",
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );
        $this->add_responsive_control(
            'number_of_column_slide',
            [
                'type'        => Controls_Manager::NUMBER,
                'label'       => __('Number of Column', 'elemento-addons'),
                'devices' => ['desktop', 'tablet', 'mobile'],
                'min' => 1,
                'max' => 6,
                'desktop_default' => 4,
                'tablet_default' => 3,
                'mobile_default' => 2,
                'frontend_available' => true,

            ]
        );
        $this->add_control(
            'number_of_row',
            [
                'label'   => __('Number of Row', 'elemento-addons'),
                'type'    => Controls_Manager::SELECT,
                'options' => [
                    '1' => 1,
                    '2' => 2,
                ],
                'default' => '1',
            ]
        );
        $this->add_control(
            'slider_auto_play',
            [
                'label'        => __('AutoPlay', 'elemento-addons'),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __('Yes', 'elemento-addons'),
                'label_off'    => __('No', 'elemento-addons'),
                'return_value' => 'on',
                'default'      => '',
            ]
        );
        $this->add_control(
            'autoPlaySpeed',
            [
                'type'        => Controls_Manager::NUMBER,
                'label'       => __('Slider Speed(second)', 'elemento-addons'),
                'default'     => 3,
                'min'         => 1,
                'max'         => 10,
                'condition' => [
                    'slider_auto_play' => 'on',
                ],
            ]
        );
        $this->add_control(
            'autoPlayDirection',
            [
                'label'       => __('Autoplay Slide Direction', 'elemento-addons'),
                'type'    => Controls_Manager::SELECT,
                'options' => [
                    'r' => __('Right', 'elemento-addons'),
                    'l' => __('Left', 'elemento-addons'),
                ],
                'default' => 'r',
                'condition' => [
                    'slider_auto_play' => 'on',
                ],
            ]
        );
        $this->add_control(
            'slider_loop',
            [
                'label'   => __('Infinite Loop', 'elemento-addons'),
                'type'    => Controls_Manager::SELECT,
                'options' => [
                    '1' => __('Yes', 'elemento-addons'),
                    '0' => __('No', 'elemento-addons'),
                ],
                'default' => '1',
            ]
        );
        $this->add_control(
            'slider_controll',
            [
                'label'   => __('Slider Controlls', 'elemento-addons'),
                'type'    => Controls_Manager::SELECT,
                'options' => [
                    'arr' => __('Arrows', 'elemento-addons'),
                    'dot' => __('Dots', 'elemento-addons'),
                    '0' => __('None', 'elemento-addons'),
                ],
                'default' => 'dot',
            ]
        );
        // $this->add_control(
        //     'arrow_type',
        //     [
        //         'label'   => __('Choose Arrow Type', 'elemento-addons'),
        //         'type'    => Controls_Manager::SELECT,
        //         'options' => [
        //             '1' => __('Type 1', 'elemento-addons'),
        //             '2' => __('Type 2', 'elemento-addons'),
        //             '3' => __('Type 3', 'elemento-addons'),
        //         ],
        //         'default' => '1',
        //         'condition' => [
        //             'slider_controll' => ['ar_do', 'arr']
        //         ],
        //     ]
        // );
        $this->end_controls_section();
    }
    protected function containerStyle()
    {
        $this->start_controls_section(
            'container_style',
            [
                'label' => __('Product Box Style', 'elemento-addons'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            'container_bg_color',
            [
                'label'     => __('Background Color', 'elemento-addons'),
                'type'      => Controls_Manager::COLOR,
                'default'   => "#F9F9F9",
                'scheme'    => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_1,
                ],
                'selectors' => [
                    '{{WRAPPER}} .elemento-product-outer-wrap .elemento-product-simple-inner-wrap,{{WRAPPER}} .elemento-product-simple-inner-bottom,{{WRAPPER}} .elemento-product-simple-inner-bottom:before' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'box_border_heading',
            [
                'label' => __('Box Border', 'elemento-addons'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'border',
                'label' => __('Border', 'elemento-addons'),
                'selector' => '{{WRAPPER}} .elemento-product-simple-inner-wrap,{{WRAPPER}} .elemento-product-simple-inner-bottom',
                'fields_options' => [
                    'border' => [
                        'default' => 'solid',
                    ],
                    'width' => [
                        'default' => [
                            'top' => '1',
                            'right' => '1',
                            'bottom' => '1',
                            'left' => '1',
                            'isLinked' => false,
                        ],
                    ],
                    'color' => [
                        'default' => '#E9E9E9',
                    ],
                ],
            ]
        );



        $this->add_responsive_control(
            'box_border_radius',
            [
                'label'     => __('Border Radius', 'elemento-addons'),
                'type'      => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range'     => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 0,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .elemento-product-simple-inner-wrap,{{WRAPPER}} .elemento-product-simple-inner-bottom' => 'border-radius : {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'container_margin_slide',
            [
                'label'     => __('Box Spacing', 'elemento-addons'),
                'type'      => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range'     => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 10,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ea-simple-product-slider .item' => 'padding : 0 {{SIZE}}{{UNIT}};',
                ]
            ]
        );
        $this->add_responsive_control(
            'container_margin_slide_row',
            [
                'label'     => __('Row Spacing', 'elemento-addons'),
                'type'      => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range'     => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 10,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ea-simple-product-slider .elemento-product-outer-wrap + .elemento-product-outer-wrap' => 'margin-top :calc({{SIZE}}{{UNIT}} * 2);',
                ],
                'condition' => [
                    'number_of_row' => '2'
                ]
            ]
        );
        // section_layout
        $this->add_control(
            'container_padding',
            [
                'label' => __('Padding', 'elemento-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'default' => [
                    'top' => 15,
                    'right' => 15,
                    'bottom' => 15,
                    'left' => 15,
                    'unit' => 'px',
                    'isLinked' => true,
                ],
                'selectors' => [
                    '{{WRAPPER}} .elemento-product-simple-inner-wrap,{{WRAPPER}} .elemento-product-outer-wrap .elemento-product-simple-inner-bottom' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',

                    '{{WRAPPER}} .hovered.elemento-product-outer-wrap .elemento-product-simple-inner-bottom' => 'transform:translateY(-{{BOTTOM}}{{UNIT}})'
                ],
            ]
        );
        $this->add_control(
            'button_alignment',
            [
                'label' => __('Content Alignment', 'elemento-addons'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'baseline' => [
                        'title' => __('Left', 'elemento-addons'),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'center' => [
                        'title' => __('Center', 'elemento-addons'),
                        'icon' => 'eicon-h-align-center',
                    ],
                    'flex-end' => [
                        'title' => __('Right', 'elemento-addons'),
                        'icon' => 'eicon-h-align-right',
                    ]
                ],
                'default' => 'center',
                'selectors' => [
                    '{{WRAPPER}} .elemento-product-simple-inner-wrap,{{WRAPPER}} .elemento-product-outer-wrap .elemento-product-simple-inner-bottom' => 'align-items: {{VALUE}};',
                ],
                'toggle' => true,
            ]
        );
        $this->add_responsive_control(
            'box_content_spacing',
            [
                'label'     => __('Box Content Spacing', 'elemento-addons'),
                'type'      => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range'     => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 12,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .elemento-product-simple-inner-wrap,{{WRAPPER}} .elemento-product-simple-inner-bottom' => 'grid-gap : {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .elemento-product-outer-wrap .elemento-product-simple-inner-bottom .elemento-add-to-cart-btn' => 'margin-top : {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs('box_tabs_tab');
        $this->start_controls_tab(
            'box_tabs_normal',
            [
                'label'     => __('Normal', 'elemento-addons'),

            ]
        );
        // $this->add_group_control(
        //     \Elementor\Group_Control_Box_Shadow::get_type(),
        //     [
        //         'name' => 'box_shadow_box',
        //         'label' => __('Box Shadow', 'elemento-addons'),
        //         'selector' => '{{WRAPPER}} .elemento-product-simple-inner-wrap,{{WRAPPER}} .elemento-product-simple-inner-bottom',
        //         'separator' => "before",
        //         'exclude' => ['box_shadow_horizontal', 'horizontal'],
        //     ]
        // );
        $this->add_control(
            'box_shadow_box',
            [
                'label'     => __('Box Shadow Color', 'elemento-addons'),
                'type'      => Controls_Manager::COLOR,
                'default'   => "#4B58FF00",
                'scheme'    => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_1,
                ],
                'selectors' => [
                    '{{WRAPPER}} .elemento-product-simple-inner-wrap,{{WRAPPER}} .elemento-product-simple-inner-bottom' => 'color: {{VALUE}};',
                ],
            ]
        );


        $this->end_controls_tab();
        $this->start_controls_tab(
            'box_tabs_hover',
            [
                'label'     => __('Hover', 'elemento-addons'),
            ]
        );
        $this->add_control(
            'box_shadow_box_hover',
            [
                'label'     => __('Box Shadow Color', 'elemento-addons'),
                'type'      => Controls_Manager::COLOR,
                'default'   => "#B0ADAD40",
                'scheme'    => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_1,
                ],
                'selectors' => [
                    '{{WRAPPER}} .elemento-product-outer-wrap:hover .elemento-product-simple-inner-wrap,{{WRAPPER}} .elemento-product-outer-wrap:hover .elemento-product-simple-inner-bottom' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();



        $this->end_controls_section();
    }

    protected function titleStyle()
    {
        $this->start_controls_section(
            'section_style',
            [
                'label' => __('Product Title', 'elemento-addons'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .elemento-addons-product-title',
                'fields_options' => [
                    'font_size' => [
                        'default' => [
                            'unit' => 'px',
                            'size' => '14',
                        ],
                    ],
                    'letter_spacing' => [
                        'default' => [
                            'unit' => 'px',
                            'size' => '0.2',
                        ],
                    ],
                ],
            ]
        );
        $this->start_controls_tabs('product_title_style_tab');
        $this->start_controls_tab(
            'product_title_style_normal',
            [
                'label'     => __('Normal', 'elemento-addons'),

            ]
        );
        $this->add_control(
            'heading_color',
            [
                'label'     => __('Color', 'elemento-addons'),
                'type'      => Controls_Manager::COLOR,
                "default"   => "#3B3B3B",
                'scheme'    => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_1,
                ],
                'selectors' => [
                    '{{WRAPPER}} .elemento-addons-product-title' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'product_title_style_hover',
            [
                'label'     => __('Hover', 'elemento-addons'),
            ]
        );

        $this->add_control(
            'heading_hover_color',
            [
                'label'     => __('Color', 'elemento-addons'),
                'type'      => Controls_Manager::COLOR,
                "default"   => "#181818",
                'scheme'    => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_1,
                ],
                'selectors' => [
                    '{{WRAPPER}} .elemento-addons-product-title:hover' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();
    }
    protected function ratingStyle()
    {
        $this->start_controls_section(
            'rating_style',
            [
                'label' => __('Rating Style', 'elemento-addons'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_responsive_control(
            'rating_font_size',
            [
                'label'     => __('Font Size', 'elemento-addons'),
                'type'      => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range'     => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .elemento-addons-rating .star-rating' => 'font-size : {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'rating_color',
            [
                'label'     => __('Color', 'elemento-addons'),
                'type'      => Controls_Manager::COLOR,
                "default"   => "#ffd200",
                'scheme'    => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_1,
                ],
                'selectors' => [
                    '{{WRAPPER}} .elemento-addons-rating .star-rating:before' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'rating_bg_color',
            [
                'label'     => __('Background Color', 'elemento-addons'),
                'type'      => Controls_Manager::COLOR,
                "default"   => "#191919",
                'scheme'    => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_1,
                ],
                'selectors' => [
                    '{{WRAPPER}} .elemento-addons-rating .star-rating' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_section();
    }
    protected function priceStyle()
    {
        $this->start_controls_section(
            'price_style',
            [
                'label' => __('Price', 'elemento-addons'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'regular_price_typography',
                'selector' => '{{WRAPPER}} .elemento-product-outer-wrap .elemento-addons-price',
            ]
        );
        $this->add_control(
            'regular_price',
            [
                'label'     => __('Color', 'elemento-addons'),
                'type'      => Controls_Manager::COLOR,
                "default"   => "#20C9AE",
                'scheme'    => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_1,
                ],
                'selectors' => [
                    '{{WRAPPER}} .elemento-product-outer-wrap .elemento-addons-price > ins' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .elemento-product-outer-wrap .elemento-addons-price > .amount > bdi' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'sale_price',
            [
                'label'     => __('Sale Price Color', 'elemento-addons'),
                'type'      => Controls_Manager::COLOR,
                'default'   => "#878787",
                'scheme'    => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_1,
                ],
                'selectors' => [
                    '{{WRAPPER}} .elemento-product-outer-wrap .elemento-addons-price > del' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_section();
    }

    protected function cartButton()
    {
        $this->start_controls_section(
            'cart_button',
            [
                'label' => __('Add To Cart', 'elemento-addons'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );


        $this->add_control(
            'section_preview',
            [
                'label'        => __('Preview', 'elemento-addons'),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __('Yes', 'elemento-addons'),
                'label_off'    => __('No', 'elemento-addons'),
                'return_value' => 'on',
                'default'      => '',
                'prefix_class' => 'elemento-simple-product-preview',
                'description' => __('This is only for backend Preview on hover Items.', 'elemento-addons'),
            ]
        );

        $this->add_control(
            'add_to_cart_icon_on',
            [
                'label'        => __('Icon (Enable/Disable)', 'elemento-addons'),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __('Yes', 'elemento-addons'),
                'label_off'    => __('No', 'elemento-addons'),
                'return_value' => 'on',
                'default'      => '',
            ]
        );
        $this->add_control(
            'add_to_cart_text',
            [
                'label' => __("Cart Text", 'elemnto-addons'),
                'label_block' => true,
                'type' => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => __("Add To Cart", 'elemento-addons'),
            ]
        );
        $this->add_control(
            'icon_text__spacing',
            [
                'label'     => __('Text Spacing', 'elemento-addons'),
                'type'      => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'default' => [
                    'unit' => 'px',
                    'size' => 4,
                ],
                'range'     => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .elemento-product-outer-wrap a.elemento-add-to-cart-btn .add-to-cart-text' => 'padding-left : {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'add_to_cart_text_on' => 'on',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'add_to_cart_typography',
                'scheme' => Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} .elemento-product-outer-wrap a.elemento-add-to-cart-btn',
                // typo 
                'fields_options' => [
                    'font_size' => [
                        'default' => [
                            'unit' => 'px',
                            'size' => '12',
                        ],
                    ],
                    'font_weight' => [
                        'default' => 700,
                    ],
                ],
                'exclude' => ['font_size'],
                // typo 
            ]
        );
        $this->add_control(
            'icon_font_size',
            [
                'label'     => __('Font Size', 'elemento-addons'),
                'type'      => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'default' => [
                    'unit' => 'px',
                    'size' => 14,
                ],
                'range'     => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .elemento-product-outer-wrap a.elemento-add-to-cart-btn' => 'font-size : {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'add_to_cart_btn_padding',
            [
                'label' => __('Padding', 'elemento-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .elemento-product-outer-wrap a.elemento-add-to-cart-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'default' => [
                    'top' => 6,
                    'right' => 60,
                    'bottom' => 6,
                    'left' => 60,
                    'unit' => 'px',
                    'isLinked' => true,
                ]
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'add_to_cart_border',
                'label' => __('Border', 'elemento-addons'),
                'selector' => '{{WRAPPER}} .elemento-product-outer-wrap a.elemento-add-to-cart-btn',
                'fields_options' => [
                    'border' => [
                        'default' => 'solid',
                    ],
                    'width' => [
                        'default' => [
                            'top' => '1',
                            'right' => '1',
                            'bottom' => '1',
                            'left' => '1',
                            'isLinked' => false,
                        ],
                    ],
                    'color' => [
                        'default' => '#20C9AE',
                    ],
                ],
            ]
        );
        $this->add_control(
            'add_to_cart_btn_border_one',
            [
                'label' => __('Border Radius', 'elemento-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .elemento-product-outer-wrap a.elemento-add-to-cart-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'default' => [
                    'top' => 4,
                    'right' => 4,
                    'bottom' => 4,
                    'left' => 4,
                    'unit' => 'px',
                    'isLinked' => true,
                ]
            ]
        );
        $this->start_controls_tabs('add_to_cart_tab');
        $this->start_controls_tab(
            'add_to_cart_normal',
            [
                'label'     => __('Normal', 'elemento-addons'),

            ]
        );
        $this->add_control(
            'add_to_cart_color',
            [
                'label'     => __('Color', 'elemento-addons'),
                'type'      => Controls_Manager::COLOR,
                "default"   => "#20C9AE",
                'scheme'    => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_1,
                ],
                'selectors' => [
                    '{{WRAPPER}} .ea-simple-product-slider .elemento-product-outer-wrap .elemento-add-to-cart-btn' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'add_to_cart_bg_color',
            [
                'label'     => __('Background Color', 'elemento-addons'),
                'type'      => Controls_Manager::COLOR,
                "default"   => "transparent",
                'scheme'    => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_1,
                ],
                'selectors' => [
                    '{{WRAPPER}} .ea-simple-product-slider .elemento-product-outer-wrap .elemento-add-to-cart-btn' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'add_to_cart_hover',
            [
                'label'     => __('Hover', 'elemento-addons'),
            ]
        );

        $this->add_control(
            'add_to_cart_color_hover',
            [
                'label'     => __('Color', 'elemento-addons'),
                'type'      => Controls_Manager::COLOR,
                "default"   => "#20C9AE",
                'scheme'    => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_1,
                ],
                'selectors' => [
                    '{{WRAPPER}} .ea-simple-product-slider .elemento-product-outer-wrap .elemento-add-to-cart-btn:hover' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'add_to_cart_bg_color_hover',
            [
                'label'     => __('Background Color', 'elemento-addons'),
                'type'      => Controls_Manager::COLOR,
                "default"   => "transparent",
                'scheme'    => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_1,
                ],
                'selectors' => [
                    '{{WRAPPER}} .ea-simple-product-slider .elemento-product-outer-wrap .elemento-add-to-cart-btn:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->end_controls_section();
    }


    protected function product_sale_style()
    {
        $this->start_controls_section(
            'sale_style',
            [
                'label' => __('Sale Text', 'elemento-addons'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'sale_tag_typography',
                'scheme' => Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} .elemento-product-outer-wrap .elemento-addons-sale-tag',
                // typo 
                'fields_options' => [
                    'font_size' => [
                        'default' => [
                            'unit' => 'px',
                            'size' => '14',
                        ],
                    ]
                ],
                // typo 
            ]
        );
        $this->add_control(
            'sale_bg_color',
            [
                'label'     => __('Background Color', 'elemento-addons'),
                'type'      => Controls_Manager::COLOR,
                "default"   => "black",
                'scheme'    => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_1,
                ],
                'selectors' => [
                    '{{WRAPPER}} .elemento-product-outer-wrap .elemento-addons-sale-tag' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'sale_color',
            [
                'label'     => __('Color', 'elemento-addons'),
                'type'      => Controls_Manager::COLOR,
                "default"   => "white",
                'scheme'    => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_1,
                ],
                'selectors' => [
                    '{{WRAPPER}} .elemento-product-outer-wrap .elemento-addons-sale-tag' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'sale_tag_margin',
            [
                'label' => __('Margin', 'elemento-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .elemento-product-outer-wrap .elemento-addons-sale-tag' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'top' => 5, 'right' => 5, 'bottom' => 5, 'left' => 5, 'unit' => 'px', 'isLinked' => true
            ]
        );
        $this->end_controls_section();
    }
    // php render 
    protected function render()
    {
        $settings = $this->get_settings();
        $productCategory = $settings['post_category'];
        $getProductByCategory = $this->post_filter()->simple_product_slider($productCategory, $settings);
        echo $getProductByCategory;
    }
    // class end 
}

Elementor\Plugin::instance()->widgets_manager->register_widget_type(new elementoProductSimple());


// Cannot validate since a PHP installation could not be found. Use the setting 'php.validate.executablePath' to configure the PHP executable.