<div class="container">
    <div class="background-image"></div>
    <div class="menu-overlay"></div>
    <div class="particles"></div>

    <div class="content">
        <h1 class="title">PokeFight</h1>
        <div class="pokeball-decoration"></div>

        <nav class="menu-buttons">
            <a href="/battle" class="menu-button">
                <div class="button-inner">
                    <span class="button-text">Combat</span>
                    <div class="button-background"></div>
                </div>
            </a>

            <a href="/heal" class="menu-button">
                <div class="button-inner">
                    <span class="button-text">Centre Pokémon</span>
                    <div class="button-background"></div>
                </div>
            </a>

            <a href="/home/selectPokemon" class="menu-button">
                <div class="button-inner">
                    <span class="button-text">Pokemons</span>
                    <div class="button-background"></div>
                </div>
            </a>
        </nav>
    </div>
</div>

<style>
    :root {
        --primary-color: #ffcb05;
        --secondary-color: #2a75bb;
        --text-color: #fff;
        --hover-color: rgba(255, 255, 255, 0.1);
    }

    .container {
        position: fixed;
        width: 100%;
        height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
        background-color: #000;
    }

    .background-image {
        position: fixed;
        inset: 0;
        background: url('https://moewalls.com/wp-content/uploads/2023/06/rayquaza-flying-in-the-clouds-pokemon-thumb.jpg') center/cover no-repeat;
        transform: translateZ(0);
        will-change: transform;
        animation: softZoom 20s ease-in-out infinite alternate;
        filter: brightness(0.7);
    }

    .particles {
        position: fixed;
        inset: 0;
        z-index: 1;
        pointer-events: none;
    }

    .menu-overlay {
        position: fixed;
        inset: 0;
        background: radial-gradient(circle at center, transparent 0%, rgba(0, 0, 0, 0.7) 100%);
        backdrop-filter: blur(3px);
    }

    .content {
        position: relative;
        z-index: 2;
        text-align: center;
    }

    .title {
        font-size: clamp(2rem, 6vw, 6rem);
        color: var(--primary-color);
        text-shadow:
            0 0 10px rgba(255, 203, 5, 0.5),
            0 0 20px rgba(255, 203, 5, 0.3),
            0 0 30px rgba(255, 203, 5, 0.1);
        margin-bottom: 5vh;
        font-family: 'Pokemon Solid', sans-serif;
        letter-spacing: 0.2em;
        animation: titleFloat 3s ease-in-out infinite;
    }

    .pokeball-decoration {
        width: 50px;
        height: 50px;
        margin: 0 auto 30px;
        background: linear-gradient(to bottom, #ff0000 50%, #ffffff 50%);
        border-radius: 50%;
        position: relative;
        animation: rotate 3s infinite linear;
    }

    .pokeball-decoration::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 10px;
        height: 10px;
        background: #ffffff;
        border-radius: 50%;
        transform: translate(-50%, -50%);
        box-shadow: 0 0 0 2px #000, 0 0 0 10px #ffffff;
    }

    .menu-buttons {
        display: flex;
        flex-direction: column;
        gap: 2vh;
        perspective: 1000px;
    }

    .menu-button {
        position: relative;
        text-decoration: none;
        transform-style: preserve-3d;
        transition: transform 0.3s ease;
    }

    .menu-button:hover {
        transform: translateZ(20px);
    }

    .menu-button:active {
        transform: translateZ(10px);
    }

    .button-inner {
        position: relative;
        padding: 1.5vh 4vw;
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 8px;
        overflow: hidden;
    }

    .button-text {
        position: relative;
        z-index: 1;
        color: var(--text-color);
        font-size: clamp(1rem, 1.8vw, 2rem);
        letter-spacing: 0.2em;
        text-transform: uppercase;
        transition: color 0.3s ease;
    }

    .button-background {
        position: absolute;
        inset: 0;
        background: var(--hover-color);
        transform: scaleX(0);
        transform-origin: left;
        transition: transform 0.3s ease;
    }

    .menu-button:hover .button-background {
        transform: scaleX(1);
    }

    @keyframes titleFloat {

        0%,
        100% {
            transform: translateY(0);
        }

        50% {
            transform: translateY(-10px);
        }
    }

    @keyframes rotate {
        from {
            transform: rotate(0deg);
        }

        to {
            transform: rotate(360deg);
        }
    }

    @keyframes softZoom {
        0% {
            transform: scale(1);
        }

        100% {
            transform: scale(1.1);
        }
    }
</style>

<script>
    class Particle {
        constructor(canvas) {
            this.canvas = canvas;
            this.ctx = canvas.getContext('2d');
            this.x = Math.random() * canvas.width;
            this.y = Math.random() * canvas.height;
            this.size = Math.random() * 3 + 1;
            this.speedX = Math.random() * 3 - 1.5;
            this.speedY = Math.random() * 3 - 1.5;
            this.opacity = Math.random() * 0.5 + 0.3;
        }

        update() {
            this.x += this.speedX;
            this.y += this.speedY;

            if (this.x > this.canvas.width || this.x < 0) this.speedX *= -1;
            if (this.y > this.canvas.height || this.y < 0) this.speedY *= -1;
        }

        draw() {
            this.ctx.fillStyle = `rgba(255,255,255,${this.opacity})`;
            this.ctx.beginPath();
            this.ctx.arc(this.x, this.y, this.size, 0, Math.PI * 2);
            this.ctx.fill();
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        const canvas = document.createElement('canvas');
        canvas.classList.add('particles');
        document.querySelector('.particles').appendChild(canvas);

        const resizeCanvas = () => {
            canvas.width = window.innerWidth;
            canvas.height = window.innerHeight;
        };

        resizeCanvas();
        window.addEventListener('resize', resizeCanvas);

        const particles = Array.from({
            length: 50
        }, () => new Particle(canvas));
        const ctx = canvas.getContext('2d');

        const animate = () => {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            particles.forEach(particle => {
                particle.update();
                particle.draw();
            });
            requestAnimationFrame(animate);
        };

        animate();
    });
</script>