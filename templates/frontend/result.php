<?php
$fields = json_decode(get_option('wof_settings_json', '{}'));
$secondary_titles = $fields->secondary_titles;
$random_title = $secondary_titles[array_rand($secondary_titles)];
$enable_plugin = get_query_var('prize_text', true);
?>

<div class="wheel-form">
    <div class="close"></div>
    <p class="title result-title"><?php echo $random_title; ?></p>
    <img class="prize-image" src="<?php echo WOF_PLUGIN_URL . '/assets/dist/img/gift.png' ?>" alt="">
    <p class="prize"><?php echo $enable_plugin; ?></p>
    <div class="content">
        <?php echo replace_bracket_tags_with_html($fields->result_text); ?>
    </div>

    <a class="wheel-button result" href="<?php echo get_permalink(wc_get_page_id('shop')) ?>">
        <?php echo __('перейти до каталогу', WOF_PLUGIN_TEXTDOMAIN); ?>
    </a>
</div>
