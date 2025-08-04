<?php
$titles = get_query_var('titles', []);
$result_text = get_query_var('result_text', []);
$main_title = get_query_var('main_title', '');
?>
<div class="container bg-white rounded-3 py-3 mt-4">
    <div class="row">
        <div class="col-12">
            <p class="h3"><?php echo __('Текст', WOF_PLUGIN_TEXTDOMAIN); ?></p>
            <div class="form-group">
                <label for="mainTitle">
                    <?php echo __('Заголовок екрану з колесом', WOF_PLUGIN_TEXTDOMAIN); ?>
                    <span data-bs-toggle="tooltip" data-bs-html="true"
                          data-bs-title="Щоб перенести текст на наступний рядок, вставте {br} у тому місці, де необхідний перенос.">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                     class="bi bi-question-circle-fill" viewBox="0 0 16 16">
                                    <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M5.496 6.033h.825c.138 0 .248-.113.266-.25.09-.656.54-1.134 1.342-1.134.686 0 1.314.343 1.314 1.168 0 .635-.374.927-.965 1.371-.673.489-1.206 1.06-1.168 1.987l.003.217a.25.25 0 0 0 .25.246h.811a.25.25 0 0 0 .25-.25v-.105c0-.718.273-.927 1.01-1.486.609-.463 1.244-.977 1.244-2.056 0-1.511-1.276-2.241-2.673-2.241-1.267 0-2.655.59-2.75 2.286a.237.237 0 0 0 .241.247m2.325 6.443c.61 0 1.029-.394 1.029-.927 0-.552-.42-.94-1.029-.94-.584 0-1.009.388-1.009.94 0 .533.425.927 1.01.927z"></path>
                                </svg>
                            </span></label>
                <input type="text" class="form-control" id="mainTitle" name="main_title"
                       value="<?php echo $main_title; ?>"
                       placeholder="<?php echo __('Заголовок', WOF_PLUGIN_TEXTDOMAIN); ?>">
            </div>
        </div>
    </div>
    <div class="row pt-3">
        <label>
            <?php echo __('Заголовоки екрану з результатом', WOF_PLUGIN_TEXTDOMAIN); ?>
            <span data-bs-toggle="tooltip" data-bs-html="true"
                  data-bs-title="На екрані результату відображатиметься випадковий заголовок із цього списку.">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                     class="bi bi-question-circle-fill" viewBox="0 0 16 16">
                                    <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M5.496 6.033h.825c.138 0 .248-.113.266-.25.09-.656.54-1.134 1.342-1.134.686 0 1.314.343 1.314 1.168 0 .635-.374.927-.965 1.371-.673.489-1.206 1.06-1.168 1.987l.003.217a.25.25 0 0 0 .25.246h.811a.25.25 0 0 0 .25-.25v-.105c0-.718.273-.927 1.01-1.486.609-.463 1.244-.977 1.244-2.056 0-1.511-1.276-2.241-2.673-2.241-1.267 0-2.655.59-2.75 2.286a.237.237 0 0 0 .241.247m2.325 6.443c.61 0 1.029-.394 1.029-.927 0-.552-.42-.94-1.029-.94-.584 0-1.009.388-1.009.94 0 .533.425.927 1.01.927z"></path>
                                </svg>
                            </span>
        </label>
    </div>
    <?php $has_titles = !empty($titles);
    $rows = $has_titles ? $titles : [''];
    foreach ($rows as $index => $item): ?>
        <div class="row pb-3 titles-container align-items-center"
             data-index="<?php echo esc_attr($index); ?>">
            <div class="col position-relative d-flex">
                <input type="text"
                       value="<?php echo esc_attr($item); ?>"
                       name="secondary_titles[<?php echo esc_attr($index); ?>]"
                       required
                       placeholder="<?php echo __('Текст елементу', WOF_PLUGIN_TEXTDOMAIN); ?>"
                       class="form-control">
                <a href="#" class="delete-row p-1 text-danger">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                         class="bi bi-x-circle-fill" viewBox="0 0 16 16">
                        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293z"></path>
                    </svg>
                </a>
            </div>
        </div>
    <?php endforeach; ?>
    <div class="row">
        <div class="col">
            <button type="button" id="add-title-btn" class="btn btn-outline-success">
                Додати елемент
            </button>
        </div>
    </div>

    <div class="row pt-3">
        <div class="col">
            <label for="resultScreenText" class="form-label">
                <?php echo __('Текст екрану з результатом', WOF_PLUGIN_TEXTDOMAIN); ?>
                <span data-bs-toggle="tooltip" data-bs-html="true"
                      data-bs-title="Щоб перенести текст на наступний рядок, вставте {br} у тому місці, де необхідний перенос.">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                     class="bi bi-question-circle-fill" viewBox="0 0 16 16">
                                    <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M5.496 6.033h.825c.138 0 .248-.113.266-.25.09-.656.54-1.134 1.342-1.134.686 0 1.314.343 1.314 1.168 0 .635-.374.927-.965 1.371-.673.489-1.206 1.06-1.168 1.987l.003.217a.25.25 0 0 0 .25.246h.811a.25.25 0 0 0 .25-.25v-.105c0-.718.273-.927 1.01-1.486.609-.463 1.244-.977 1.244-2.056 0-1.511-1.276-2.241-2.673-2.241-1.267 0-2.655.59-2.75 2.286a.237.237 0 0 0 .241.247m2.325 6.443c.61 0 1.029-.394 1.029-.927 0-.552-.42-.94-1.029-.94-.584 0-1.009.388-1.009.94 0 .533.425.927 1.01.927z"></path>
                                </svg>
                            </span>
            </label>
            <textarea class="form-control" id="resultScreenText" name="result_text"
                      rows="3"><?php echo $result_text ?? ''; ?></textarea>
        </div>
    </div>
</div>