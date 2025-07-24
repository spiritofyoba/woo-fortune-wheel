<?php
if (!function_exists('sanitize_nested_array')) {
    function sanitize_nested_array($array) {
        return array_map(function ($item) {
            return is_array($item)
                ? sanitize_nested_array($item)
                : sanitize_text_field($item);
        }, $array);
    }
}
