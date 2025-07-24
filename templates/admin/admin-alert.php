<?php $messages = get_settings_errors('wof_messages');
foreach ($messages as $message) :
    $type = $message['type'] === 'updated' ? 'success' : 'danger';
    ?>
    <div class="alert alert-<?php echo esc_attr($type); ?> alert-dismissible fade show" role="alert">
        <?php echo esc_html($message['message']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endforeach; ?>