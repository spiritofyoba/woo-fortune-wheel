<?php

/**
 * Sanitize a multidimensional array recursively using sanitize_text_field.
 *
 * @param array $array The input array to sanitize.
 * @return array The sanitized array.
 */
if (!function_exists('sanitize_nested_array')) {
    function sanitize_nested_array($array)
    {
        return array_map(function ($item) {
            return is_array($item)
                ? sanitize_nested_array($item)
                : sanitize_text_field($item);
        }, $array);
    }
}

/**
 * Replace bracket-style tags like {br} or {b}text{b} with actual HTML tags.
 *
 * @param string $string The input string with bracket tags.
 * @return string The string with replaced HTML tags.
 */
if (!function_exists('replace_bracket_tags_with_html')) {
    function replace_bracket_tags_with_html($string)
    {
        $string = preg_replace_callback('/\{([a-zA-Z0-9]+)\}(.*?)\{\1\}/s', function ($matches) {
            $tag = $matches[1];
            $content = $matches[2];
            return "<$tag>$content</$tag>";
        }, $string);

        $string = preg_replace_callback('/\{([a-zA-Z0-9]+)\}/', function ($matches) {
            $tag = $matches[1];
            return "<$tag>";
        }, $string);

        return $string;
    }
}


/**
 * Load a WordPress template partial with variables passed in.
 * The variables are accessible using get_query_var() inside the template.
 *
 * @param string $template_path Full path to the template file.
 * @param array $vars Associative array of variables to be set as query vars.
 * @return void
 */
if (!function_exists('load_partial_with_vars')) {
    function load_partial_with_vars($template_path, array $vars = [])
    {
        foreach ($vars as $key => $value) {
            set_query_var($key, $value);
        }
        load_template($template_path, true);
    }
}

function enable_disable_plugin()
{
    $settings = json_decode(get_option('wof_settings_json', '{}'), true);
    return $settings['enable_plugin'] ?? true;
}
