// Menu Toggle
document.addEventListener('DOMContentLoaded', function() {
    const menuToggle = document.getElementById('menuToggle');
    const navLinks = document.getElementById('navLinks');

    menuToggle.addEventListener('click', function() {
        navLinks.classList.toggle('active');
        
        // Animate hamburger menu
        const spans = menuToggle.getElementsByTagName('span');
        spans[0].style.transform = navLinks.classList.contains('active') ? 'rotate(45deg) translate(6px, 6px)' : '';
        spans[1].style.opacity = navLinks.classList.contains('active') ? '0' : '1';
        spans[2].style.transform = navLinks.classList.contains('active') ? 'rotate(-45deg) translate(6px, -6px)' : '';
    });

    // Close menu when clicking outside
    document.addEventListener('click', function(event) {
        if (!menuToggle.contains(event.target) && !navLinks.contains(event.target)) {
            navLinks.classList.remove('active');
            const spans = menuToggle.getElementsByTagName('span');
            spans[0].style.transform = '';
            spans[1].style.opacity = '1';
            spans[2].style.transform = '';
        }
    });
});

// Smooth scroll for navigation links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            const navHeight = document.querySelector('.navbar').offsetHeight;
            const targetPosition = target.offsetTop - navHeight;
            window.scrollTo({
                top: targetPosition,
                behavior: 'smooth'
            });
            
            // Close mobile menu if open
            document.getElementById('navLinks').classList.remove('active');
            const spans = document.getElementById('menuToggle').getElementsByTagName('span');
            spans[0].style.transform = '';
            spans[1].style.opacity = '1';
            spans[2].style.transform = '';
        }
    });
});

// Form submission
document.getElementById('contactForm')?.addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = {
        nombre: document.getElementById('nombre').value,
        email: document.getElementById('email').value,
        mensaje: document.getElementById('mensaje').value
    };
    
    console.log('Datos del formulario:', formData);
    // Aquí se agregaría la llamada al backend cuando esté implementado
    
    // Limpiar el formulario
    this.reset();
    alert('Mensaje enviado correctamente');
});

// Animaciones al hacer scroll
const animateOnScroll = () => {
    const elements = document.querySelectorAll('.service-card, .feature-card, .about-content, .contact-card');
    
    elements.forEach(element => {
        const elementTop = element.getBoundingClientRect().top;
        const elementBottom = element.getBoundingClientRect().bottom;
        
        if (elementTop < window.innerHeight && elementBottom > 0) {
            element.style.opacity = '1';
            element.style.transform = 'translateY(0)';
        }
    });
};

// Inicializar animaciones
document.addEventListener('DOMContentLoaded', () => {
    const elements = document.querySelectorAll('.service-card, .feature-card, .about-content, .contact-card');
    elements.forEach(element => {
        element.style.opacity = '0';
        element.style.transform = 'translateY(20px)';
        element.style.transition = 'all 0.6s ease-out';
    });
    
    animateOnScroll();
    window.addEventListener('scroll', animateOnScroll);
});