class WheelOfFortune {
    static messages = {
        invalidPhone: 'Введіть коректний номер у форматі +380XXXXXXXXX',
        processing: 'Обробка...',
        connectionError: "Помилка зʼєднання з сервером",
        errorOccurred: 'Сталася помилка',
        alreadyPlayed: 'Ви вже брали участь.',
        successPrefix: 'Ви виграли: ',
    };

    constructor(options) {
        this.modal = document.getElementById(options.modalId);
        this.triggerBtn = document.getElementById(options.triggerBtnId);
        this.spinBtn = document.getElementById(options.spinBtnId);
        this.phoneInput = document.getElementById(options.phoneInputId);
        this.resultEl = document.getElementById(options.resultElId);
        this.ajaxUrl = options.ajaxUrl;
        this.nonce = options.nonce;

        this.init();
    }

    init() {
        setTimeout(() => this.showModal(), 15000);

        document.addEventListener('mouseleave', e => {
            if (e.clientY < 0) {
                this.showModal();
            }
        });

        this.triggerBtn.addEventListener('click', () => this.showModal());

        this.spinBtn.addEventListener('click', () => this.handleSpin());
    }

    showModal() {
        this.modal.classList.remove('wof-hidden');
    }

    handleSpin() {
        const phone = this.phoneInput.value.trim();
        const phonePattern = /^\+380\d{9}$/;

        if (!phonePattern.test(phone)) {
            this.resultEl.textContent = WheelOfFortune.messages.invalidPhone;
            return;
        }

        this.resultEl.textContent = WheelOfFortune.messages.processing;

        fetch(this.ajaxUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams({
                action: 'wof_spin',
                nonce: this.nonce,
                phone: phone,
            }),
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    this.resultEl.textContent = WheelOfFortune.messages.successPrefix + data.data.result;

                    if (typeof Cart === 'function') {
                        const cartInstance = new Cart();
                        if (typeof cartInstance.updateCartInformation === 'function') {
                            cartInstance.updateCartInformation();
                        }
                    }
                } else {
                    const msg = data.data?.message || WheelOfFortune.messages.errorOccurred;
                    this.resultEl.textContent = msg;
                }
            })
            .catch(() => {
                this.resultEl.textContent = WheelOfFortune.messages.connectionError;
            });
    }
}

document.addEventListener('DOMContentLoaded', () => {
    new WheelOfFortune({
        modalId: 'wof-modal',
        triggerBtnId: 'wof-trigger-btn',
        spinBtnId: 'wof-spin-btn',
        phoneInputId: 'wof-phone',
        resultElId: 'wof-result',
        ajaxUrl: WOF_JS.ajax_url,
        nonce: WOF_JS.nonce,
    });
});