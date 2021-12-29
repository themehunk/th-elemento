<?php
include_once 'post-setting.php';
add_action('wp_ajax_elemento_simple_post', 'elemento_simple_post');
add_action('wp_ajax_nopriv_elemento_simple_post', 'elemento_simple_post');
function elemento_simple_post()
{

    // print_r($_POST);
    // return;

    // echo '<pre>';
    if (isset($_POST['post_data']['current_page']) && isset($_POST['post_data']['total_page'])) {
        $postSEttings = new elemento_post_simple();
        $allSEttings = $_POST['post_data'];
        $numOfPost = intval($allSEttings['post_per_page']);
        $category_ = $allSEttings['category'];
        // $trigger_page = $_POST['trigger'];
        $currentPage = $allSEttings['current_page'];
        $args = array(
            'post_type' => 'post',
            'posts_per_page' => $numOfPost,
        );
        // post show by 
        if ($allSEttings['post_show_by'] !==  'recent') {
            $args['orderby'] = $allSEttings['post_show_by'];
        }
        // page if number 
        // $checkINtPage = intval($trigger_page);
        if ($currentPage) {
            $args['paged'] = $currentPage;
        }
        $postHtml = '';
        $stringCate = implode(",", $category_);
        if (!in_array('all', $category_)) {
            $args['category_name'] = $stringCate;
        }
        // html options 
        $query = new WP_Query($args);

        if ($query->have_posts()) {
            // pagination data -------------- 
            // $pagination_ = '';
            $pagination_ = $postSEttings->pagination($allSEttings['total_page'], $currentPage);
            while ($query->have_posts()) {
                $query->the_post();
                $post_id_ = get_the_ID();
                $postHtml .= '<div class="elemento-post-layout-iteme">';
                $postHtml .= $postSEttings->postContentHtml($post_id_, $allSEttings['options']);
                $postHtml .= '</div>';
            }
            // wp_send_json_success($items);
            $sendData = ['success' => true, 'posthtml' => $postHtml, 'pagination' => $pagination_, 'settings' => $allSEttings];
            wp_send_json_success($sendData);
        } else {
            $sendData = ['error' => true];
            wp_send_json_error($sendData);
        }
    }
}