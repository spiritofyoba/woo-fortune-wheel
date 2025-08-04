class WheelOfFortune {
    constructor(options) {
        this.canvas = options.canvas;
        this.ctx = this.canvas.getContext('2d');
        this.center = this.canvas.width / 2;
        this.radius = this.center;
        this.sections = options.sections || [];
        this.ajaxUrl = options.ajaxUrl;
        this.nonce = options.nonce;
        this.resultEl = options.resultEl; // element to show result messages
        this.phone = options.phone || '';
        this.anglePerSection = (2 * Math.PI) / this.sections.length;
        this.currentAngle = 0;
        this.spinning = false;
        this.continuousSpinId = null;
        this.pointerDistance = 50;
        this.pointerSize = 28;
        this.modal = document.getElementById('wof-modal');
        this.delay = 1000;

        this.init();
    }

    init() {
        this.resizeCanvas();

        window.addEventListener('resize', () => this.resizeCanvas());

        window.addEventListener('DOMContentLoaded', () => {
            this.showAfterDelay();
            this.bindClose();
        });

        if (this.spinBtn) {
            this.spinBtn.addEventListener('click', () => this.handleSpin());
        }
    }

    setSpinButton(button) {
        this.spinBtn = button;
        this.spinBtn.addEventListener('click', () => this.handleSpin());
    }

    showAfterDelay() {
        if (this.getCookie('wof_last_win')) {
            return;
        }

        setTimeout(() => {
            this.modal.classList.remove('wof-hidden');
        }, this.delay);
    }

    bindClose() {
        const closeBtn = this.modal.querySelector('.wheel-form .close');
        if (closeBtn) {
            closeBtn.addEventListener('click', () => {
                this.modal.classList.add('wof-hidden');
            });
        }
    }

    drawWheel() {
        const isMobile = window.innerWidth <= 768;
        console.log(isMobile);
        const ctx = this.ctx;
        const center = this.center;
        const radius = this.radius;

        ctx.clearRect(0, 0, this.canvas.width, this.canvas.height);

        // Draw slices with chessboard colors
        this.sections.forEach((section, i) => {
            const startAngle = this.currentAngle + i * this.anglePerSection - Math.PI / 2;
            const endAngle = startAngle + this.anglePerSection;

            ctx.beginPath();
            ctx.moveTo(center, center);
            ctx.arc(center, center, radius, startAngle, endAngle);
            const bgColor = (i % 2 === 0) ? '#fff' : 'rgba(104, 91, 199, 1)';
            ctx.fillStyle = bgColor;
            ctx.fill();
        });

        // Draw main wheel border
        ctx.lineWidth = 25;
        ctx.strokeStyle = 'rgba(238, 66, 133, 1)';
        ctx.beginPath();
        ctx.arc(center, center, radius, 0, 2 * Math.PI);
        ctx.stroke();

        // Draw labels centered in each section with required styles
        this.sections.forEach((section, i) => {
            const startAngle = this.currentAngle + i * this.anglePerSection - Math.PI / 2;
            ctx.save();
            ctx.translate(center, center);
            ctx.rotate(startAngle + this.anglePerSection / 2);

            ctx.textAlign = "center";
            ctx.textBaseline = "middle";
            console.log(section)
            if (section.type === 'discount') {
                ctx.font = isMobile ? "bold 22px Rubik, sans-serif" : "bold 28px Rubik, sans-serif";
                ctx.fillStyle = (i % 2 === 0) ? 'rgba(104, 91, 199, 1)' : '#fff';
            } else {
                ctx.font = isMobile ? "700 10px Rubik, sans-serif" : "700 12.71px Rubik, sans-serif";
                ctx.fillStyle = (i % 2 === 0) ? 'rgba(104, 91, 199, 1)' : '#fff';
            }

            const maxTextWidth = radius - 100 // 100px padding right, 15px left
            const x = !isMobile ? radius - 90 + 15 : radius - 90 + 25;            // Shift to center of padded area
            const y = 0;
            this.wrapText(ctx, section.label.toUpperCase(), x, y, maxTextWidth, 14);

            ctx.restore();
        });

        // Draw white center circle with border
        ctx.beginPath();
        ctx.arc(center, center, 50, 0, 2 * Math.PI);
        ctx.fillStyle = '#fff';
        ctx.fill();

        ctx.lineWidth = 11;
        ctx.strokeStyle = 'rgba(238, 66, 133, 1)';
        ctx.stroke();

        // Draw pointer: 28x28 triangle, 50px from center, pointing right
        ctx.beginPath();
        ctx.moveTo(center + this.pointerDistance, center - this.pointerSize / 2);
        ctx.lineTo(center + this.pointerDistance + this.pointerSize, center);
        ctx.lineTo(center + this.pointerDistance, center + this.pointerSize / 2);
        ctx.closePath();

        ctx.fillStyle = 'rgba(238, 66, 133, 1)';
        ctx.fill();
    }

    resizeCanvas() {
        const parent = this.canvas.parentElement;

        if (!parent) return;

        // Resize canvas based on parent width (e.g., 100% width)
        const size = Math.min(parent.offsetWidth, window.innerHeight * 0.5);
        this.canvas.width = size;
        this.canvas.height = size;

        this.center = size / 2;
        this.radius = this.center;
        this.anglePerSection = (2 * Math.PI) / this.sections.length;

        this.drawWheel();
    }

    wrapText(ctx, text, x, y, maxWidth, lineHeight) {
        const words = text.split(' ');
        let line = '';
        let lines = [];

        for (let n = 0; n < words.length; n++) {
            const testLine = line + words[n] + ' ';
            const metrics = ctx.measureText(testLine);
            const testWidth = metrics.width;
            if (testWidth > maxWidth && n > 0) {
                lines.push(line);
                line = words[n] + ' ';
            } else {
                line = testLine;
            }
        }
        lines.push(line);

        const totalHeight = lines.length * lineHeight;
        const offsetY = y - totalHeight / 2 + lineHeight / 2;

        for (let i = 0; i < lines.length; i++) {
            ctx.fillText(lines[i].trim(), x, offsetY + i * lineHeight);
        }
    }

    setPhoneInput(inputEl) {
        this.phoneInput = inputEl;

        if (typeof Inputmask !== 'undefined') {
            Inputmask({mask: '(099)9999999'}).mask(this.phoneInput);
        }

        this.phoneInput.addEventListener('input', () => {
            if (this.resultEl) this.resultEl.textContent = '';
        });
    }

    getPhoneValue() {
        return this.phoneInput?.value?.replace(/\D/g, '') || '';
    }

    isValidPhone(phone) {
        return /^0\d{9}$/.test(phone);
    }

    setCookie(name, value, days) {
        const expires = new Date(Date.now() + days * 864e5).toUTCString();
        document.cookie = name + '=' + encodeURIComponent(value) + '; expires=' + expires + '; path=/';
    }

    getCookie(name) {
        const value = `; ${document.cookie}`;
        const parts = value.split(`; ${name}=`);
        if (parts.length === 2) return parts.pop().split(';').shift();
    }

    handleSpin() {
        if (this.spinning) return;

        const phone = this.getPhoneValue();
        if (!this.isValidPhone(phone)) {
            if (this.resultEl) this.resultEl.textContent = 'Введіть коректний номер у форматі 0991234567';
            return;
        }

        if (this.resultEl) this.resultEl.textContent = 'Зачекайте...';

        this.spinning = true;

        fetch(this.ajaxUrl, {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: new URLSearchParams({
                action: 'wof_spin',
                nonce: this.nonce,
                phone: phone,
            }),
        })
            .then(response => response.json())
            .then(data => {

                if (!data.success) throw new Error(data.data?.message || 'Серверна помилка.');
                if (this.resultEl) this.resultEl.textContent = '';

                const backendId = data.data.result;
                const targetIndex = this.sections.findIndex(s => s.id === backendId);
                if (targetIndex === -1) throw new Error('Некоректний ID призу.');

                this.animateFinalSpin(targetIndex);

                const self = this;
                setTimeout(function () {
                    if (data.data.html) {
                        self.modal.innerHTML = data.data.html;
                    }
                }, 6000);
            })
            .catch(err => {
                this.spinning = false;
                if (this.resultEl) this.resultEl.textContent = err.message || 'Помилка при обертанні.';
            });
    }

    startContinuousSpin() {
        let angle = this.currentAngle;
        const speed = 0.3;

        const spin = () => {
            angle += speed;
            this.currentAngle = angle % (2 * Math.PI);
            this.drawWheel();
            this.continuousSpinId = requestAnimationFrame(spin);
        };
        spin();
    }

    setPrizeProductPrice() {

    }

    stopContinuousSpin() {
        if (this.continuousSpinId) {
            cancelAnimationFrame(this.continuousSpinId);
            this.continuousSpinId = null;
        }
    }

    animateFinalSpin(targetIndex) {
        // Align pointer exactly to the center of winning section
        const targetAngle = (Math.PI / 2) - (targetIndex * this.anglePerSection) - this.anglePerSection / 2;

        const spins = 5;
        const finalAngle = spins * 2 * Math.PI + targetAngle;

        let start = null;
        const duration = 5000; // ms
        const startAngle = this.currentAngle;

        const animate = (timestamp) => {
            if (!start) start = timestamp;
            const elapsed = timestamp - start;

            const progress = Math.min(elapsed / duration, 1);
            const ease = 1 - Math.pow(1 - progress, 3);

            this.currentAngle = startAngle + (finalAngle - startAngle) * ease;
            this.drawWheel();

            if (progress < 1) {
                requestAnimationFrame(animate);
            } else {
                this.currentAngle %= 2 * Math.PI;
                this.spinning = false;

                console.log(`🎉 You won: ${this.sections[targetIndex].label}`);

                this.setCookie('wof_last_win', JSON.stringify({
                    prize: this.sections[targetIndex].label,
                    date: new Date().toISOString()
                }), 7);

                if (typeof Cart === 'function') {
                    const cartInstance = new Cart();
                    if (typeof cartInstance.updateCartInformation === 'function') {
                        cartInstance.updateCartInformation();
                    }
                }
            }
        };

        requestAnimationFrame(animate);
    }
}


const wheel = new WheelOfFortune({
    canvas: document.getElementById('wheel'),
    sections: WOF_JS.wheelItems.map((item, index) => ({
        id: index,
        label: item.text,
        type: item.type
    })),
    ajaxUrl: WOF_JS.ajaxUrl,
    nonce: WOF_JS.nonce,
    resultEl: document.getElementById('result'),
});


document.getElementById('wheel-phone').addEventListener('input', () => {
    const resultEl = document.getElementById('result');
    if (resultEl) resultEl.textContent = '';
});

wheel.setSpinButton(document.getElementById('spinBtn'));
wheel.setPhoneInput(document.getElementById('wheel-phone'));
