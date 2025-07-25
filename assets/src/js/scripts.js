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

        this.init();
    }

    init() {
        this.drawWheel();

        if (this.spinBtn) {
            this.spinBtn.addEventListener('click', () => this.handleSpin());
        }
    }

    setSpinButton(button) {
        this.spinBtn = button;
        this.spinBtn.addEventListener('click', () => this.handleSpin());
    }

    drawWheel() {
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
            ctx.font = "700 12.71px Rubik, sans-serif";
            ctx.fillStyle = (i % 2 === 0) ? 'rgba(104, 91, 199, 1)' : '#fff';
            ctx.fillText(section.label.toUpperCase(), radius / 2, 0);
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

    handleSpin() {
        if (this.spinning) return;
        this.spinning = true;

        // Start continuous spin animation
        this.startContinuousSpin();

        // Send backend request to get prize ID
        fetch(this.ajaxUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams({
                action: 'wof_spin',
                nonce: this.nonce,
                phone: this.phone,
            }),
        })
            .then(response => response.json())
            .then(data => {
                if (!data.success) throw new Error(data.data?.message || 'Spin failed');

                const backendId = data.data.result;
                const targetIndex = this.sections.findIndex(s => s.id === backendId);
                if (targetIndex === -1) throw new Error('Invalid prize id');

                // Stop continuous spin and animate final spin
                this.stopContinuousSpin();
                this.animateFinalSpin(targetIndex);
            })
            .catch(err => {
                this.stopContinuousSpin();
                this.spinning = false;
                if (this.resultEl) this.resultEl.textContent = err.message || 'Error occurred during spin.';
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

                alert(`🎉 You won: ${this.sections[targetIndex].label}`);

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
    sections: [
        {id: 0, label: 'Prize 0'},
        {id: 1, label: 'Prize 1'},
        {id: 2, label: 'Prize 2'},
        {id: 3, label: 'Prize 3'},
        {id: 4, label: 'Prize 4'},
        {id: 5, label: 'Prize 5'},
        {id: 6, label: 'Prize 6'},
        {id: 7, label: 'Prize 7'},
    ],
    ajaxUrl: WOF_JS.ajax_url,
    nonce: WOF_JS.nonce,
    phone: '+380991231250',
    resultEl: document.getElementById('result'),
});

const spinBtn = document.getElementById('spinBtn');
wheel.setSpinButton(spinBtn);
