<?php
add_action('wp_ajax_nopriv_get_models', 'get_smartwatch_models');
add_action('wp_ajax_get_models', 'get_smartwatch_models');

//Function to fetch smartwatch models after selecting the smartwatch brand
function get_smartwatch_models() {
    if (isset($_GET['brand'])) {
        $selected_brand = sanitize_text_field($_GET['brand']);
        global $wpdb;
        $table_name = $wpdb->prefix . 'smartwatch';
        $models_list = $wpdb->get_col($wpdb->prepare(
            "SELECT DISTINCT model_name FROM $table_name WHERE brand_name = %s ORDER BY model_name ASC",
            $selected_brand
        ));
        $not_selected_brand = "blank_data";
        if (!empty($models_list)) {
            wp_send_json_success($models_list);
        }else {
            wp_send_json_success($not_selected_brand);
        }
    }
    wp_send_json_error();
}
?>