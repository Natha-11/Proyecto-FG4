/**
 * LÓGICA DE INTERACCIÓN GLOBAL
 * Este archivo maneja los efectos visuales transversales como el cursor personalizado,
 * las animaciones de aparición (Reveal) y el comportamiento del menú móvil.
 */
document.addEventListener('DOMContentLoaded', () => {
    // Lógica del cursor personalizado
    const cursorDot = document.getElementById('cursor-dot');
    const cursorOutline = document.getElementById('cursor-outline');

    // Idealmente solo activo en dispositivos sin pantalla táctil, pero es más sencillo simplemente ejecutarlo
    window.addEventListener('mousemove', (e) => {
        const posX = e.clientX;
        const posY = e.clientY;

        // El punto sigue instantáneamente
        cursorDot.style.left = `${posX}px`;
        cursorDot.style.top = `${posY}px`;

        // El contorno sigue con retraso (generalmente manejado por la transición CSS o la animación JS)
        // Usando animate para un efecto de seguimiento más suave
        cursorOutline.animate({
            left: `${posX}px`,
            top: `${posY}px`
        }, { duration: 500, fill: "forwards" });
    });

    // Efectos de desplazamiento para el cursor
    const interactiveElements = document.querySelectorAll('a, button, .product-card');
    interactiveElements.forEach(el => {
        el.addEventListener('mouseenter', () => {
            cursorOutline.style.transform = 'translate(-50%, -50%) scale(1.5)';
            cursorOutline.style.backgroundColor = 'rgba(212, 165, 165, 0.1)';
            cursorOutline.style.borderColor = 'transparent';
        });
        el.addEventListener('mouseleave', () => {
            cursorOutline.style.transform = 'translate(-50%, -50%) scale(1)';
            cursorOutline.style.backgroundColor = 'transparent';
            cursorOutline.style.borderColor = 'var(--primary-color)';
        });
    });

    // Alternancia del menú móvil
    const menuToggle = document.getElementById('mobile-menu');
    const navLinks = document.querySelector('.nav-links');

    menuToggle.addEventListener('click', () => {
        navLinks.style.display = navLinks.style.display === 'flex' ? 'none' : 'flex';
    });

    // Animaciones de desplazamiento (Intersection Observer)
    const observerOptions = {
        threshold: 0.1
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('active');
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);

    const animatedElements = document.querySelectorAll('.reveal');
    animatedElements.forEach(el => {
        observer.observe(el);
    });

    // Scroll header effect
    const header = document.getElementById('navbar');
    window.addEventListener('scroll', () => {
        if (window.scrollY > 50) {
            header.classList.add('scrolled');
        } else {
            header.classList.remove('scrolled');
        }
    });


    // Efecto de Parallax Suave para el Hero Visual
    const heroVisual = document.querySelector('.hero-visual');
    window.addEventListener('scroll', () => {
        const scrolled = window.scrollY;
        if (heroVisual) {
            heroVisual.style.transform = `translateY(${scrolled * 0.1}px) rotate(${scrolled * 0.01}deg)`;
        }
    });

    // Interacciones del cursor mejoradas para el nuevo diseño
    interactiveElements.forEach(el => {
        el.addEventListener('mouseenter', () => {
            cursorOutline.style.width = '80px';
            cursorOutline.style.height = '80px';
            cursorOutline.style.backgroundColor = 'rgba(223, 207, 190, 0.1)';
            cursorOutline.style.borderColor = 'var(--primary-color)';
        });
        el.addEventListener('mouseleave', () => {
            cursorOutline.style.width = '40px';
            cursorOutline.style.height = '40px';
            cursorOutline.style.backgroundColor = 'transparent';
            cursorOutline.style.borderColor = 'rgba(223, 207, 190, 0.5)';
        });
    });
});
