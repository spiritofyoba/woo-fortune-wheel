<?php
defined('ABSPATH') || exit;

global $wpdb;
$table_name = $wpdb->prefix . 'wof_spins';

$per_page = 20;
$current_page = isset($_GET['paged']) ? max(1, intval($_GET['paged'])) : 1;
$offset = ($current_page - 1) * $per_page;

$phone_search = isset($_GET['phone_search']) ? sanitize_text_field($_GET['phone_search']) : '';
$sort_date = isset($_GET['sort_date']) && in_array(strtolower($_GET['sort_date']), ['asc', 'desc']) ? strtolower($_GET['sort_date']) : 'desc';

$where = '';
$params = [];
if ($phone_search !== '') {
    $where = "WHERE phone LIKE %s";
    $params[] = '%' . $wpdb->esc_like($phone_search) . '%';
}

if ($where) {
    $total_items = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $table_name $where", ...$params));
} else {
    $total_items = $wpdb->get_var("SELECT COUNT(*) FROM $table_name");
}
$total_pages = ceil($total_items / $per_page);

$sql = "SELECT * FROM $table_name $where ORDER BY created_at $sort_date LIMIT %d OFFSET %d";
$params[] = $per_page;
$params[] = $offset;

$results = $wpdb->get_results($wpdb->prepare($sql, ...$params));

function wof_build_url($overrides = [])
{
    $params = $_GET;
    foreach ($overrides as $key => $value) {
        $params[$key] = $value;
    }

    if (!isset($overrides['paged'])) {
        unset($params['paged']);
    }
    return esc_url(add_query_arg($params));
}

?>

<div id="wof-wrapper">
    <div class="container">
        <div class="row">
            <div class="col">
                <h1 class="mb-3 mt-5"><?php echo __('Усі ліди', WOF_PLUGIN_TEXTDOMAIN) ?></h1>
            </div>
        </div>
    </div>

    <div class="container bg-white rounded-3 pt-3">
        <div class="row justify-content-between align-items-center">
            <div class="col">
                <form method="GET" class="mb-4 row g-3 align-items-center">
                    <input type="hidden" name="page" value="<?php echo esc_attr($_GET['page']); ?>">
                    <input type="hidden" name="sort_date" value="<?php echo esc_attr($sort_date); ?>">

                    <div class="col-auto">
                        <input type="text" id="phone_search" name="phone_search"
                               value="<?php echo esc_attr($phone_search); ?>" class="form-control"
                               placeholder="+380XXXXXXXXX">
                    </div>
                    <div class="col-auto">
                        <button type="submit"
                                class="btn btn-outline-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                 class="bi bi-search" viewBox="0 0 16 16">
                                <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0"></path>
                            </svg>
                            <?php echo __('Пошук', WOF_PLUGIN_TEXTDOMAIN); ?>
                        </button>
                    </div>
                </form>
            </div>
            <div class="col d-flex justify-content-end">
                <form method="GET" class="mb-4 row g-3 align-items-center">
                    <input type="hidden" name="page" value="<?php echo esc_attr($_GET['page']); ?>">
                    <input type="hidden" name="phone_search" value="<?php echo esc_attr($phone_search); ?>">
                    <div class="col-auto">
                        <select name="sort_date" id="sort_date" class="form-select" onchange="this.form.submit()">
                            <option value="desc" <?php selected($sort_date, 'desc'); ?>><?php echo __('Нове спочатку', WOF_PLUGIN_TEXTDOMAIN); ?></option>
                            <option value="asc" <?php selected($sort_date, 'asc'); ?>><?php echo __('Старіше спочатку', WOF_PLUGIN_TEXTDOMAIN); ?></option>
                        </select>
                    </div>
                </form>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <div class="table-responsive">
                    <table class="table align-middle table-hover">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th><?php echo __('Номер телефону', WOF_PLUGIN_TEXTDOMAIN); ?></th>
                            <th><?php echo __('Результат', WOF_PLUGIN_TEXTDOMAIN); ?></th>
                            <th>IP</th>
                            <th><?php echo __('Дата гри', WOF_PLUGIN_TEXTDOMAIN); ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if ($results): ?>
                            <?php foreach ($results as $row): ?>
                                <tr>
                                    <td><?php echo esc_html($row->id); ?></td>
                                    <td><?php echo esc_html($row->phone); ?></td>
                                    <td><?php echo esc_html($row->result); ?></td>
                                    <td><?php echo esc_html($row->ip); ?></td>
                                    <td><?php echo esc_html($row->created_at); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5"
                                    class="text-center"><?php echo __('Немає записів', WOF_PLUGIN_TEXTDOMAIN); ?></td>
                            </tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Пагінація -->
                <?php if ($total_pages > 1): ?>
                    <nav aria-label="Page navigation example" class="mt-4">
                        <ul class="pagination">
                            <li class="page-item <?php echo ($current_page <= 1) ? 'disabled' : ''; ?>">
                                <a class="page-link"
                                   href="<?php echo wof_build_url(['paged' => max(1, $current_page - 1)]); ?>"
                                   aria-label="Previous">
                                    <span aria-hidden="true">&laquo;</span>
                                </a>
                            </li>

                            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                <li class="page-item <?php echo ($i === $current_page) ? 'active' : ''; ?>">
                                    <a class="page-link"
                                       href="<?php echo wof_build_url(['paged' => $i]); ?>"><?php echo $i; ?></a>
                                </li>
                            <?php endfor; ?>

                            <li class="page-item <?php echo ($current_page >= $total_pages) ? 'disabled' : ''; ?>">
                                <a class="page-link"
                                   href="<?php echo wof_build_url(['paged' => min($total_pages, $current_page + 1)]); ?>"
                                   aria-label="Next">
                                    <span aria-hidden="true">&raquo;</span>
                                </a>
                            </li>
                        </ul>
                    </nav>
                <?php endif; ?>

            </div>
        </div>
    </div>
</div>
