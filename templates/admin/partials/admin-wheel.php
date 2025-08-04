<?php $wheel_items = get_query_var('wheel_items', []); ?>
<div class="container bg-white rounded-3 pb-3 mt-4">
    <div class="row">
        <div class="col-12">
            <?php do_settings_sections('wof-settings'); ?>
            <?php wp_nonce_field('wof_save_settings', 'wof_settings_nonce'); ?>


            <div class="row pt-3">
                <div class="col">
                    <p class="h3"><?php echo __('Елементи колеса', WOF_PLUGIN_TEXTDOMAIN); ?></p>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <p class="m-0 fs-5">
                        <?php echo __('Тип', WOF_PLUGIN_TEXTDOMAIN); ?>
                        <span data-bs-toggle="tooltip"
                              data-bs-html="true"
                              data-bs-title="<?php echo __('Селектор типу виграшу', WOF_PLUGIN_TEXTDOMAIN); ?>">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                     class="bi bi-question-circle-fill" viewBox="0 0 16 16">
                                    <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M5.496 6.033h.825c.138 0 .248-.113.266-.25.09-.656.54-1.134 1.342-1.134.686 0 1.314.343 1.314 1.168 0 .635-.374.927-.965 1.371-.673.489-1.206 1.06-1.168 1.987l.003.217a.25.25 0 0 0 .25.246h.811a.25.25 0 0 0 .25-.25v-.105c0-.718.273-.927 1.01-1.486.609-.463 1.244-.977 1.244-2.056 0-1.511-1.276-2.241-2.673-2.241-1.267 0-2.655.59-2.75 2.286a.237.237 0 0 0 .241.247m2.325 6.443c.61 0 1.029-.394 1.029-.927 0-.552-.42-.94-1.029-.94-.584 0-1.009.388-1.009.94 0 .533.425.927 1.01.927z"></path>
                                </svg>
                            </span>
                    </p>
                </div>
                <div class="col">
                    <p class="m-0 fs-5">
                        <?php echo __('Купон/Продукт', WOF_PLUGIN_TEXTDOMAIN); ?>
                        <span data-bs-toggle="tooltip"
                              data-bs-html="true"
                              data-bs-title="<?php echo __('Поле для купону або ID продукту (або ID варіації, якщо це варіативний продукт). Можна залишити порожнім, якщо обрано "Спробуй ще"', WOF_PLUGIN_TEXTDOMAIN); ?>">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                     class="bi bi-question-circle-fill" viewBox="0 0 16 16">
                                    <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M5.496 6.033h.825c.138 0 .248-.113.266-.25.09-.656.54-1.134 1.342-1.134.686 0 1.314.343 1.314 1.168 0 .635-.374.927-.965 1.371-.673.489-1.206 1.06-1.168 1.987l.003.217a.25.25 0 0 0 .25.246h.811a.25.25 0 0 0 .25-.25v-.105c0-.718.273-.927 1.01-1.486.609-.463 1.244-.977 1.244-2.056 0-1.511-1.276-2.241-2.673-2.241-1.267 0-2.655.59-2.75 2.286a.237.237 0 0 0 .241.247m2.325 6.443c.61 0 1.029-.394 1.029-.927 0-.552-.42-.94-1.029-.94-.584 0-1.009.388-1.009.94 0 .533.425.927 1.01.927z"></path>
                                </svg>
                            </span>
                    </p>
                </div>
                <div class="col">
                    <p class="m-0 fs-5">
                        <?php echo __('Текст', WOF_PLUGIN_TEXTDOMAIN); ?>
                        <span data-bs-toggle="tooltip"
                              data-bs-html="true"
                              data-bs-title="<?php echo __('Текст елементу який буде показаний на колесі', WOF_PLUGIN_TEXTDOMAIN); ?>">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                     class="bi bi-question-circle-fill" viewBox="0 0 16 16">
                                    <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M5.496 6.033h.825c.138 0 .248-.113.266-.25.09-.656.54-1.134 1.342-1.134.686 0 1.314.343 1.314 1.168 0 .635-.374.927-.965 1.371-.673.489-1.206 1.06-1.168 1.987l.003.217a.25.25 0 0 0 .25.246h.811a.25.25 0 0 0 .25-.25v-.105c0-.718.273-.927 1.01-1.486.609-.463 1.244-.977 1.244-2.056 0-1.511-1.276-2.241-2.673-2.241-1.267 0-2.655.59-2.75 2.286a.237.237 0 0 0 .241.247m2.325 6.443c.61 0 1.029-.394 1.029-.927 0-.552-.42-.94-1.029-.94-.584 0-1.009.388-1.009.94 0 .533.425.927 1.01.927z"></path>
                                </svg>
                            </span>
                    </p>
                </div>
                <div class="col">
                    <p class="m-0 fs-5">
                        <?php echo __('Коефіцієнт', WOF_PLUGIN_TEXTDOMAIN); ?>
                        <span data-bs-toggle="tooltip"
                              data-bs-html="true"
                              data-bs-title="<?php echo __('Коефіцієнт виграшу. Сума усіх коефіцієнтів не повинна бути більше 1 (0.1 = 10%)', WOF_PLUGIN_TEXTDOMAIN); ?>">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                     class="bi bi-question-circle-fill" viewBox="0 0 16 16">
                                    <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M5.496 6.033h.825c.138 0 .248-.113.266-.25.09-.656.54-1.134 1.342-1.134.686 0 1.314.343 1.314 1.168 0 .635-.374.927-.965 1.371-.673.489-1.206 1.06-1.168 1.987l.003.217a.25.25 0 0 0 .25.246h.811a.25.25 0 0 0 .25-.25v-.105c0-.718.273-.927 1.01-1.486.609-.463 1.244-.977 1.244-2.056 0-1.511-1.276-2.241-2.673-2.241-1.267 0-2.655.59-2.75 2.286a.237.237 0 0 0 .241.247m2.325 6.443c.61 0 1.029-.394 1.029-.927 0-.552-.42-.94-1.029-.94-.584 0-1.009.388-1.009.94 0 .533.425.927 1.01.927z"></path>
                                </svg>
                            </span>
                    </p>
                </div>
                <div class="col-1"></div>
            </div>
            <?php foreach ($wheel_items as $index => $item): ?>
                <div class="row pt-3 select-container align-items-center"
                     data-index="<?php echo esc_attr($index); ?>">
                    <div class="col">
                        <select class="form-select" required
                                name="wheel_items[<?php echo esc_attr($index); ?>][type]">
                            <option value="shipping" <?php selected($item['type'] ?? '', 'shipping'); ?>>
                                <?php echo __('Безкоштовна доставка', WOF_PLUGIN_TEXTDOMAIN); ?>
                            </option>
                            <option value="discount" <?php selected($item['type'] ?? '', 'discount'); ?>>
                                <?php echo __('Знижка', WOF_PLUGIN_TEXTDOMAIN); ?>
                            </option>
                            <option value="prize" <?php selected($item['type'] ?? '', 'prize'); ?>>
                                <?php echo __('Подарунок', WOF_PLUGIN_TEXTDOMAIN); ?>
                            </option>
                        </select>
                    </div>
                    <div class="col">
                        <input type="text" value="<?php echo $item['coupon'] ?? ''; ?>"
                               name="wheel_items[<?php echo esc_attr($index); ?>][coupon]" required
                               placeholder="<?php echo __('Код купону', WOF_PLUGIN_TEXTDOMAIN); ?>"
                               class="form-control">
                    </div>
                    <div class="col">
                        <input type="text" value="<?php echo $item['text'] ?? ''; ?>"
                               name="wheel_items[<?php echo esc_attr($index); ?>][text]" required
                               placeholder="<?php echo __('Текст елементу', WOF_PLUGIN_TEXTDOMAIN); ?>"
                               class="form-control">
                    </div>
                    <div class="col">
                        <input type="number" step="0.1" value="<?php echo $item['rate'] ?? ''; ?>"
                               name="wheel_items[<?php echo esc_attr($index); ?>][rate]" required
                               placeholder="<?php echo __('Коефіцієнт', WOF_PLUGIN_TEXTDOMAIN); ?>"
                               class="form-control">
                    </div>
                    <div class="col-1 text-center">
                        <a href="#" class="delete-row p-1 text-danger">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                 fill="currentColor"
                                 class="bi bi-x-circle-fill" viewBox="0 0 16 16">
                                <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293z"></path>
                            </svg>
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>

            <div class="row pt-3">
                <div class="col">
                    <button type="button" id="add-select-btn"
                            class="btn btn-outline-success">
                        <?php echo __('Додати елемент', WOF_PLUGIN_TEXTDOMAIN); ?>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>