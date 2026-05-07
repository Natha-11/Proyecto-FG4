/**
 * LÓGICA DE INTERACCIÓN GLOBAL
 * Este archivo maneja los efectos visuales transversales como el cursor personalizado,
 * las animaciones de aparición (Reveal) y el comportamiento del menú móvil.
 */
document.addEventListener('DOMContentLoaded', () => {
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
