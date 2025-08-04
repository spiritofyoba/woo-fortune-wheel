class WofSettings {
    constructor(wrapperId, addSelectBtnId, addTitleBtnId) {
        this.wrapper = document.getElementById(wrapperId);
        this.addSelectBtn = document.getElementById(addSelectBtnId);
        this.addTitleBtn = document.getElementById(addTitleBtnId);

        this.init();
    }

    init() {
        if (this.addSelectBtn) {
            this.addSelectBtn.addEventListener('click', this.addSelectRow.bind(this));
        }

        if (this.addTitleBtn) {
            this.addTitleBtn.addEventListener('click', this.addTitleRow.bind(this));
        }

        if (this.wrapper) {
            this.wrapper.addEventListener('click', this.handleWrapperClick.bind(this));
        }

        this.initTooltips();
        this.toggleDeleteButtons(); // handle initial state
    }

    initTooltips() {
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.forEach(el => new bootstrap.Tooltip(el));
    }

    handleWrapperClick(e) {
        const deleteBtn = e.target.closest('.delete-row');
        if (!deleteBtn) return;

        e.preventDefault();

        const row = deleteBtn.closest('.select-container') || deleteBtn.closest('.titles-container');
        if (!row) return;

        const rows = row.parentElement.querySelectorAll(`.${row.classList[0]}`);
        if (rows.length <= 1) return;

        row.remove();

        this.recalculateIndexes();
        this.toggleDeleteButtons();
    }

    recalculateIndexes() {
        // Reindex select-container
        const selectRows = this.wrapper.querySelectorAll('.select-container');
        selectRows.forEach((container, index) => {
            container.setAttribute('data-index', index);

            container.querySelectorAll('[name]').forEach(field => {
                const name = field.getAttribute('name');
                if (name) {
                    const newName = name.replace(/\[\d+]/, `[${index}]`);
                    field.setAttribute('name', newName);
                }
            });
        });

        // Reindex titles-container (if needed)
        const titleRows = this.wrapper.querySelectorAll('.titles-container');
        titleRows.forEach((container, index) => {
            container.setAttribute('data-index', index);

            container.querySelectorAll('[name]').forEach(field => {
                const name = field.getAttribute('name');
                if (name) {
                    const newName = name.replace(/\[\d+]/, `[${index}]`);
                    field.setAttribute('name', newName);
                }
            });
        });

        this.toggleDeleteButtons();
    }

    toggleDeleteButtons() {
        const groups = ['select-container', 'titles-container'];

        groups.forEach(selector => {
            const rows = this.wrapper.querySelectorAll(`.${selector}`);
            const shouldHide = rows.length <= 1;

            rows.forEach(row => {
                const btn = row.querySelector('.delete-row');
                if (btn) {
                    btn.style.display = shouldHide ? 'none' : '';
                }
            });
        });
    }

    addSelectRow(e) {
        e.preventDefault();

        const containers = this.wrapper.querySelectorAll('.select-container');
        const lastContainer = containers[containers.length - 1];
        const newIndex = containers.length;

        if (!lastContainer) return;

        const clone = lastContainer.cloneNode(true);
        clone.setAttribute('data-index', newIndex);

        clone.querySelectorAll('input').forEach(input => {
            const type = input.type.toLowerCase();
            if (['text', 'email', 'number', 'tel', 'url', 'search', 'password'].includes(type)) {
                input.value = '';
            } else if (['checkbox', 'radio'].includes(type)) {
                input.checked = false;
            }

            const name = input.getAttribute('name');
            if (name) {
                const newName = name.replace(/\[\d+]/, `[${newIndex}]`);
                input.setAttribute('name', newName);
            }
        });

        lastContainer.insertAdjacentElement('afterend', clone);

        this.initTooltips();
        this.recalculateIndexes();
    }

    addTitleRow(e) {
        e.preventDefault();

        const containers = this.wrapper.querySelectorAll('.titles-container');
        const lastContainer = containers[containers.length - 1];
        const newIndex = containers.length;

        if (!lastContainer) return;

        const clone = lastContainer.cloneNode(true);
        clone.setAttribute('data-index', newIndex);

        clone.querySelectorAll('input').forEach(input => {
            const type = input.type.toLowerCase();
            if (['text', 'email', 'number', 'tel', 'url', 'search', 'password'].includes(type)) {
                input.value = '';
            } else if (['checkbox', 'radio'].includes(type)) {
                input.checked = false;
            }

            const name = input.getAttribute('name');
            if (name) {
                const newName = name.replace(/\[\d+]/, `[${newIndex}]`);
                input.setAttribute('name', newName);
            }
        });

        lastContainer.insertAdjacentElement('afterend', clone);

        this.initTooltips();
        this.recalculateIndexes();
    }
}

document.addEventListener('DOMContentLoaded', () => {
    new WofSettings('wof-wrapper', 'add-select-btn', 'add-title-btn');
});
