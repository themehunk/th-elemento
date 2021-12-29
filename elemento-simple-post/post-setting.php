<?php
class elemento_post_simple
{
    //post category title and slug
    public static function postcategory()
    {
        $category = get_categories();
        if (!empty($category)) {
            $arrcate = ['all' => "All"];
            foreach ($category as $cate_value) {
                $arrcate[$cate_value->slug] = __($cate_value->name, 'elemento-addons');
            }
            return $arrcate;
        }
    }
    // show in page 
    function post_html($settings)
    {
        $html_ = '<div class="elemento-addons-simple-post">';
        $html_ .= $this->ListGridLayout($settings);
        $html_ .= '</div>';
        return $html_;
    }
    //grid list layout 
    private function ListGridLayout($options)
    {
        // print_r($options);
        // return;
        // return $cate;
        if (isset($options['post_category']) && is_array($options['post_category']) && !empty($options['post_category'])) {
            $numOfPost = $options['number_of_post'];
            // $numOfcolumn = $options['number_of_column'];
            $args = array(
                'post_type' => 'post',
                'posts_per_page' => $numOfPost,
            );
            $stringCate = $dataSetting = $postHtml = '';
            $stringCate = implode(",", $options['post_category']);
            if (!in_array('all', $options['post_category'])) {
                $args['category_name'] = $stringCate;
            }
            // post show by 
            if ($options['post_show_by'] != 'recent') {
                $args['orderby'] = $options['post_show_by'];
            }
            // html options 
            $query = new WP_Query($args);
            if ($query->have_posts()) {
                // post pagination 
                $pagination_ = '';
                if ($options['pagination_show'] == 'on') {
                    $totalPosts = $query->found_posts;
                    if ($totalPosts > $numOfPost) {
                        $totalPages = ceil($totalPosts / $numOfPost);
                        $dataSetting_array = array(
                            "current_page" => 1,
                            "total_page" => $totalPages,
                            "post_per_page" => $numOfPost,
                            "category" => $options['post_category'],
                            'post_show_by' => $options['post_show_by'],
                            'pagination' => [
                                'pagination_show' => $options['pagination_show'],
                                // 'pagination_page_limit' => $options['pagination_page_limit'],
                                // 'pagination_shorten' => $options['pagination_shorten']
                            ],
                            'options' => [
                                // 'post_title_anable' => $options['post_title_anable'],
                                'post_title_tag' => $options['post_title_tag'],
                                'post_meta_data' => $options['post_meta_data'],
                                'excerpt_anable' => $options['excerpt_anable'],
                                'excerpt_length' => $options['excerpt_length'],
                                // 'read_more_enable' => $options['read_more_enable'],
                                'read_more_new_tab' => $options['read_more_new_tab'],
                                'read_more_text' => $options['read_more_text'],
                            ]
                        );
                        $dataSetting = wp_json_encode($dataSetting_array);
                        $pagination_ = $this->pagination($totalPages, 1);
                    }
                }
                // post pagination 
                $postHtml .= "<div class='elemento-post-layout-listGrid' data-setting='" . $dataSetting . "'>";
                while ($query->have_posts()) {
                    $query->the_post();
                    $post_id_ = get_the_ID();
                    $postHtml .= '<div class="elemento-post-layout-iteme"><div>';
                    $postHtml .= $this->postContentHtml($post_id_, $options);
                    $postHtml .= '</div></div>';
                }
                $postHtml .= '</div>';
                $postHtml .= $pagination_;
            }
            return $postHtml;
        }
    }
    public function pagination($totalPAges, $currentPage, $ExternalProvideLinks = 4)
    {
        $pagination_ = '';
        $currentPAgeVAr2 =  $currentPage;
        $pagination_ .= '<div class="elemento-addons-pagination">';
        // next previous btn 
        $disableAndEnable =  $currentPage < 2 ? 'disable' : "";
        $pagination_ .= '<a href="#" data-link="prev" class="elemento-post-link ' . $disableAndEnable . '">' . __('Previous') . '</a>';
        // pagination link number 
        // -------------------------
        // 1 - left links = 1 ,self link = 1 ==then==   2 
        // 2 - now show links ExternalProvideLinks - 2 step 1
        $shoLInksRight = $ExternalProvideLinks - 2;
        // left link 
        if ($currentPAgeVAr2 > 1) {
            $paginationMinus = $currentPAgeVAr2 - 1;
            $pagination_ .= '<a href="#" data-link="' . $paginationMinus . '" class="elemento-post-link">' . $paginationMinus . '</a>';
        }
        // current link
        $pagination_ .= '<a href="#" data-link="' . $currentPAgeVAr2 . '" class="elemento-post-link active">' . $currentPAgeVAr2 . '</a>';
        // right links -----------
        $calculateLink = $currentPAgeVAr2 + $shoLInksRight;
        $appearLast = true;
        //check total
        if ($totalPAges > $calculateLink) {
            $calculateLoop = $calculateLink;
        } else {
            $appearLast = false;
            $calculateLoop = $totalPAges;
        }
        $loopNumberGEt = $calculateLoop - $currentPAgeVAr2;

        for ($_i = 0; $_i < $loopNumberGEt; $_i++) {
            $currentPAgeVAr2++;
            $pagination_ .= '<a href="#" data-link="' . $currentPAgeVAr2 . '" class="elemento-post-link">' . $currentPAgeVAr2 . '</a>';
        }
        // right links -----------
        // last page link -----------
        if ($appearLast) {
            $pagination_ .= '<a href="#" class="_last-page">...</a>';
            $pagination_ .= '<a href="#" data-link="' . $totalPAges . '" class="elemento-post-link _last-page">' . $totalPAges . '</a>';
        }
        // -------------------------
        // next previous btn 
        $disableAndEnable = $currentPage == $totalPAges ? 'disable' : "";
        $pagination_ .= '<a href="#" data-link="next" class="elemento-post-link ' . $disableAndEnable . '">' . __('Next') . '</a>';
        $pagination_ .= '</div>';
        // pagination data -------------- 
        return $pagination_;
    }

    //slider list html
    public function postContentHtml($post_id_, $options)
    {
        $postLink = get_permalink();
        $imageFeaturedImage = '';

        $category_detail = get_the_category($post_id_);
        $category_ = '';
        if (!empty($category_detail)) {
            $category_detail = json_decode(json_encode($category_detail), true);
            $newCAtegory = array_slice($category_detail, 0, 3);
            $category_ .= '<div class="_category_">';
            foreach ($newCAtegory as $value_) {
                $category_link = get_category_link($value_['cat_ID']);
                $category_ .= "<a target='_blank' href='" . esc_url($category_link) . "'>";
                $category_ .= $value_['name'];
                $category_ .= "</a>";
            }
            $category_ .= '</div>';
        }

        if (get_the_post_thumbnail_url()) {
            $imageFeaturedImage .= "<a href='" . $postLink . "' class='elemento-featured-image'>";
            $imageFeaturedImage .= "<img src='" . get_the_post_thumbnail_url() . "' class='elemento-featured-image-image'>";
            $imageFeaturedImage .= "</a>";
        }
        $html = '';
        $html .= "<div class='elemento-post-content-all'>";
        // content ------------------
        $html .= $imageFeaturedImage;
        $html .= "<div class='elemento-post-content'>";
        // image 
        $html .= $category_;
        // title 
        $html .= '<' . $options['post_title_tag'] . ' class="elemento-post-title">';
        $html .= '<a href="' . $postLink . '">';
        $html .= get_the_title();
        $html .= '</a>';
        $html .= '</' . $options['post_title_tag'] . '>';
        //post meta 
        if (!empty($options['post_meta_data'])) {
            $html .= '<div class="elemento-post-meta-data">';
            if (in_array("author", $options['post_meta_data'])) {
                $html .= '<span class="elemento-post-author">' . get_the_author() . '</span>';
            }
            if (in_array("date", $options['post_meta_data'])) {
                $html .= '<span class="elemento-post-date">' . get_the_date('F j, Y') . '</span>';
            }
            // if (in_array("time", $options['post_meta_data'])) {
            //     $html .= '<span class="elemento-post-time">' . get_post_time() . '</span>';
            // }
            if (in_array("comments", $options['post_meta_data'])) {
                $showComment = get_comments_number() ? get_comments_number() : __('No', 'elemento-addons');
                $html .= '<span class="elemento-post-comments">' . $showComment  . ' ' . __('Comment', 'elemento-addons') . '</span>';
            }
            // if (in_array("datemodified", $options['post_meta_data'])) {
            //     $html .= '<span class="elemento-post-date">' . get_the_author() . '</span>';
            // }
            $html .= '</div>';
        }
        // excerpt 
        if ($options['excerpt_anable'] == 'on') {
            $excerpt_ = get_the_excerpt();
            $excerpt_ = substr($excerpt_, 0, $options['excerpt_length']);
            $result_ = substr($excerpt_, 0, strrpos($excerpt_, ' '));
            $html .= '<div class="elemento-post-excerpt"><p>' . $result_ . '</p></div>';
        }
        // read more 
        // read_more_new_tab
        $readMoreNewTab = $options['read_more_new_tab'] == 'on' ? 'target="_blank"' : "";
        $html .= '<a class="elemento-post-read-more" ' . $readMoreNewTab . ' href="' . $postLink . '">' . $options['read_more_text'] . '</a>';
        $html .= "</div>";
        // content ------------------
        $html .= "</div>";
        return $html;
    }
    //grid list layout

}
