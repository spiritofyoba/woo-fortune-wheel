<?php $fields = json_decode(get_option('wof_settings_json', '{}')); ?>

<div id="wof-modal" class="wof-hidden">
    <div class="wheel-form">
        <div class="close"></div>
        <p class="title"><?php echo replace_bracket_tags_with_html($fields->main_title); ?></p>
        <div class="wheel-block">
            <canvas id="wheel" width="380" height="380"></canvas>
            <img src="<?php echo WOF_PLUGIN_URL . '/assets/dist/img/present.png' ?>" alt="">
        </div>
        <input type="tel" id="wheel-phone"
               placeholder="<?php echo __('Введи номер телефону', WOF_PLUGIN_TEXTDOMAIN); ?>">
        <p id="result"></p>
        <button class="wheel-button" id="spinBtn">Крутануть🎯</button>
    </div>
</div>
