document.addEventListener('DOMContentLoaded', function() {
    // Inicializa componentes
    initPreloader();
    initMobileMenu();
    initTestimonialSlider();
    initScrollAnimations();
    initSmoothScroll();
});

/**
 * Inicializa e gerencia o preloader de revista
 */
function initPreloader() {
    const preloader = document.getElementById('preloader');
    
    if (!preloader) return;
    
    // Oculta o preloader após o carregamento completo
    window.addEventListener('load', function() {
        setTimeout(function() {
            preloader.style.opacity = '0';
            
            setTimeout(function() {
                preloader.style.display = 'none';
                
                // Anima elementos da página após o preloader
                animatePageElements();
            }, 500);
        }, 2000);
    });
}

/**
 * Anima elementos principais da página após o carregamento
 */
function animatePageElements() {
    const elementsToAnimate = [
        document.querySelector('.magazine-header'),
        document.querySelector('.feature-image-container'),
        document.querySelector('.feature-category'),
        document.querySelector('.feature-title'),
        document.querySelector('.feature-author'),
        document.querySelector('.feature-excerpt'),
        document.querySelector('.feature-meta'),
        document.querySelector('.feature-cta')
    ];
    
    // Adiciona estilos para animação
    const style = document.createElement('style');
    style.textContent = `
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .animate-in {
            animation: fadeInUp 0.8s ease forwards;
            opacity: 0;
        }
    `;
    document.head.appendChild(style);
    
    // Aplica animação com atraso sequencial
    elementsToAnimate.forEach((element, index) => {
        if (element) {
            element.classList.add('animate-in');
            element.style.animationDelay = `${index * 0.15}s`;
        }
    });
}

/**
 * Inicializa o menu móvel
 */
function initMobileMenu() {
    const menuToggle = document.getElementById('menuToggle');
    const nav = document.querySelector('.magazine-nav');
    
    if (!menuToggle || !nav) return;
    
    menuToggle.addEventListener('click', function() {
        nav.classList.toggle('open');
        
        // Anima as linhas do botão hamburger
        const lines = this.querySelectorAll('.toggle-line');
        
        if (nav.classList.contains('open')) {
            lines[0].style.transform = 'rotate(45deg) translate(5px, 5px)';
            lines[1].style.opacity = '0';
            lines[2].style.transform = 'rotate(-45deg) translate(5px, -5px)';
        } else {
            lines[0].style.transform = 'none';
            lines[1].style.opacity = '1';
            lines[2].style.transform = 'none';
        }
    });
    
    // Fecha o menu ao clicar em um link
    const navLinks = document.querySelectorAll('.nav-link');
    
    navLinks.forEach(link => {
        link.addEventListener('click', function() {
            if (nav.classList.contains('open')) {
                menuToggle.click();
            }
        });
    });
    
    // Fecha o menu ao redimensionar a janela para desktop
    window.addEventListener('resize', function() {
        if (window.innerWidth > 768 && nav.classList.contains('open')) {
            nav.classList.remove('open');
            
            const lines = menuToggle.querySelectorAll('.toggle-line');
            lines[0].style.transform = 'none';
            lines[1].style.opacity = '1';
            lines[2].style.transform = 'none';
        }
    });
}

/**
 * Inicializa o slider de depoimentos
 */
function initTestimonialSlider() {
    const slider = document.getElementById('testimonialSlider');
    
    if (!slider) return;
    
    const slides = slider.querySelectorAll('.testimonial-slide');
    const dots = slider.querySelectorAll('.dot');
    const prevBtn = document.getElementById('prevSlide');
    const nextBtn = document.getElementById('nextSlide');
    
    if (slides.length === 0) return;
    
    let currentSlide = 0;
    let slideInterval;
    
    // Função para mostrar um slide específico
    function showSlide(index) {
        // Oculta todos os slides
        slides.forEach(slide => {
            slide.style.display = 'none';
        });
        
        // Remove a classe active de todos os dots
        dots.forEach(dot => {
            dot.classList.remove('active');
        });
        
        // Mostra o slide atual
        slides[index].style.display = 'block';
        
        // Adiciona a classe active ao dot atual
        if (dots[index]) {
            dots[index].classList.add('active');
        }
        
        // Atualiza o índice atual
        currentSlide = index;
    }
    
    // Função para ir para o próximo slide
    function nextSlide() {
        let newIndex = currentSlide + 1;
        if (newIndex >= slides.length) {
            newIndex = 0;
        }
        showSlide(newIndex);
    }
    
    // Função para ir para o slide anterior
    function prevSlide() {
        let newIndex = currentSlide - 1;
        if (newIndex < 0) {
            newIndex = slides.length - 1;
        }
        showSlide(newIndex);
    }
    
    // Adiciona event listeners aos botões
    if (prevBtn) {
        prevBtn.addEventListener('click', function() {
            prevSlide();
            resetInterval();
        });
    }
    
    if (nextBtn) {
        nextBtn.addEventListener('click', function() {
            nextSlide();
            resetInterval();
        });
    }
    
    // Adiciona event listeners aos dots
    dots.forEach((dot, index) => {
        dot.addEventListener('click', function() {
            showSlide(index);
            resetInterval();
        });
    });
    
    // Inicia o intervalo para trocar os slides automaticamente
    function startInterval() {
        slideInterval = setInterval(nextSlide, 5000);
    }
    
    // Reseta o intervalo
    function resetInterval() {
        clearInterval(slideInterval);
        startInterval();
    }
    
    // Mostra o primeiro slide e inicia o intervalo
    showSlide(0);
    startInterval();
}

/**
 * Inicializa animações baseadas no scroll
 */
function initScrollAnimations() {
    const elementsToAnimate = [
        ...document.querySelectorAll('.section-header'),
        ...document.querySelectorAll('.method-step'),
        ...document.querySelectorAll('.author-card'),
        ...document.querySelectorAll('.sidebar-facts'),
        ...document.querySelectorAll('.interview-question'),
        ...document.querySelectorAll('.interview-answer'),
        ...document.querySelectorAll('.purchase-card'),
        document.querySelector('.purchase-guarantee')
    ];
    
    // Adiciona estilos para animação
    const style = document.createElement('style');
    style.textContent = `
        .scroll-animate {
            opacity: 0;
            transform: translateY(30px);
            transition: opacity 0.8s ease, transform 0.8s ease;
        }
        
        .scroll-animate.visible {
            opacity: 1;
            transform: translateY(0);
        }
    `;
    document.head.appendChild(style);
    
    // Aplica classe inicial
    elementsToAnimate.forEach(element => {
        if (element) {
            element.classList.add('scroll-animate');
        }
    });
    
    // Função para verificar se elemento está visível
    function checkVisibility() {
        elementsToAnimate.forEach(element => {
            if (!element) return;
            
            const rect = element.getBoundingClientRect();
            const windowHeight = window.innerHeight || document.documentElement.clientHeight;
            
            if (rect.top <= windowHeight * 0.85) {
                element.classList.add('visible');
            }
        });
    }
    
    // Verifica visibilidade inicial e durante a rolagem
    checkVisibility();
    window.addEventListener('scroll', checkVisibility);
}

/**
 * Implementa rolagem suave para links de ancoragem
 */
function initSmoothScroll() {
    const links = document.querySelectorAll('a[href^="#"]');
    
    links.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            
            const targetId = this.getAttribute('href');
            
            if (targetId === '#') return;
            
            const targetElement = document.querySelector(targetId);
            
            if (targetElement) {
                const headerHeight = document.querySelector('.magazine-header').offsetHeight;
                const targetPosition = targetElement.getBoundingClientRect().top + window.pageYOffset - headerHeight;
                
                window.scrollTo({
                    top: targetPosition,
                    behavior: 'smooth'
                });
            }
        });
    });
} 