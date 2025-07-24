class WofSettings {
    constructor(addBtnId, wrapperId) {
        this.addBtn = document.getElementById(addBtnId);
        this.wrapper = document.getElementById(wrapperId);

        this.init();
    }

    init() {
        if (this.addBtn) {
            this.addBtn.addEventListener('click', this.handleAddClick.bind(this));
        }

        if (this.wrapper) {
            this.wrapper.addEventListener('click', this.handleWrapperClick.bind(this));
        }

        this.initTooltips();
    }

    initTooltips() {
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.forEach(tooltipTriggerEl => {
            new bootstrap.Tooltip(tooltipTriggerEl);
        });
    }

    recalculateIndexes() {
        const containers = this.wrapper.querySelectorAll('.select-container');
        containers.forEach((container, index) => {
            container.setAttribute('data-index', index);

            container.querySelectorAll('select').forEach(select => {
                const name = select.getAttribute('name');
                if (name) {
                    const newName = name.replace(/wheel_items\[\d+]/, `wheel_items[${index}]`);
                    select.setAttribute('name', newName);
                }
            });
        });
    }

    handleAddClick(e) {
        e.preventDefault();

        const containers = this.wrapper.querySelectorAll('.select-container');
        const lastContainer = containers[containers.length - 1];
        const newIndex = containers.length;

        if (!lastContainer) return;

        const clone = lastContainer.cloneNode(true);
        clone.setAttribute('data-index', newIndex);

        clone.querySelectorAll('select').forEach(select => {
            select.value = '';
            const name = select.getAttribute('name');
            if (name) {
                const newName = name.replace(/\[\d+]/, `[${newIndex}]`);
                select.setAttribute('name', newName);
            }
        });

        clone.querySelectorAll('input').forEach(input => {
            const type = input.type.toLowerCase();
            if (['text', 'email', 'number', 'tel', 'url', 'search', 'password'].includes(type)) {
                input.value = '';
            } else if (type === 'checkbox' || type === 'radio') {
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
    }


    handleWrapperClick(e) {
        const deleteBtn = e.target.closest('.delete-row');
        if (!deleteBtn) return;

        e.preventDefault();

        const row = deleteBtn.closest('.select-container');
        if (row) {
            row.remove();
            this.recalculateIndexes();

            // Re-initialize tooltips after removing elements
            this.initTooltips();
        }
    }
}

document.addEventListener('DOMContentLoaded', () => {
    new WofSettings('add-select-btn', 'wof-wrapper');
});
