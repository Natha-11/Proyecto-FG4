/**
 * LÓGICA DE INTERACCIÓN GLOBAL
 * Este archivo maneja los efectos visuales transversales como el cursor personalizado,
 * las animaciones de aparición (Reveal) y el comportamiento del menú móvil.
 */
document.addEventListener('DOMContentLoaded', () => {
    // Lógica del cursor personalizado
    const cursorDot = document.getElementById('cursor-dot');
    const cursorOutline = document.getElementById('cursor-outline');
    let mouseX = 0;
    let mouseY = 0;
    let outlineX = 0;
    let outlineY = 0;

    const shouldUseCustomCursor = cursorDot && cursorOutline && window.matchMedia('(pointer: fine)').matches;

    if (shouldUseCustomCursor) {
        cursorDot.style.display = 'block';
        cursorOutline.style.display = 'block';

        window.addEventListener('mousemove', (e) => {
            mouseX = e.clientX;
            mouseY = e.clientY;

            cursorDot.style.left = `${mouseX}px`;
            cursorDot.style.top = `${mouseY}px`;
        });

        const animateCursorOutline = () => {
            outlineX += (mouseX - outlineX) * 0.18;
            outlineY += (mouseY - outlineY) * 0.18;
            cursorOutline.style.left = `${outlineX}px`;
            cursorOutline.style.top = `${outlineY}px`;
            requestAnimationFrame(animateCursorOutline);
        };

        requestAnimationFrame(animateCursorOutline);
    }

    // Efectos de desplazamiento para el cursor
    const interactiveElements = document.querySelectorAll('a, button, .product-card');
    interactiveElements.forEach(el => {
        el.addEventListener('mouseenter', () => {
            if (!cursorOutline) return;
            cursorOutline.style.transform = 'translate(-50%, -50%) scale(1.5)';
            cursorOutline.style.backgroundColor = 'rgba(212, 165, 165, 0.1)';
            cursorOutline.style.borderColor = 'transparent';
            cursorOutline.style.width = '80px';
            cursorOutline.style.height = '80px';
        });
        el.addEventListener('mouseleave', () => {
            if (!cursorOutline) return;
            cursorOutline.style.transform = 'translate(-50%, -50%) scale(1)';
            cursorOutline.style.backgroundColor = 'transparent';
            cursorOutline.style.borderColor = 'rgba(223, 207, 190, 0.5)';
            cursorOutline.style.width = '40px';
            cursorOutline.style.height = '40px';
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

});
