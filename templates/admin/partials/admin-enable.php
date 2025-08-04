<?php $enable_plugin = get_query_var('enable', true); ?>

<div class="container bg-white rounded-3 py-3">
    <div class="row">
        <div class="col-12">
            <div class="form-check form-switch p-0 d-flex align-items-center">
                <input type="checkbox" name="enable_plugin" id="switch" <?php checked($enable_plugin); ?> />
                <label class="switch-label" for="switch">Toggle</label>
                <span class="px-3 d-flex"><?php echo __('Увімкнути або вимкнути плагін', WOF_PLUGIN_TEXTDOMAIN); ?></span>
            </div>
        </div>
    </div>
</div>