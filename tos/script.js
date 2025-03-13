document.addEventListener('DOMContentLoaded', function() {
    // Atualizar a data de última atualização dinamicamente
    const lastUpdatedElement = document.getElementById('last-updated-date');
    if (lastUpdatedElement) {
        // Você pode definir a data manualmente ou usar a data atual
        // Para este exemplo, vamos usar uma data fixa recente
        const lastUpdated = new Date();
        const options = { year: 'numeric', month: 'long', day: 'numeric' };
        lastUpdatedElement.textContent = lastUpdated.toLocaleDateString('pt-BR', options);
    }

    // Adicionar efeito de destaque nas seções ao rolar
    const sections = document.querySelectorAll('.tos-section');
    
    function checkScroll() {
        const triggerBottom = window.innerHeight * 0.8;
        
        sections.forEach(section => {
            const sectionTop = section.getBoundingClientRect().top;
            
            if (sectionTop < triggerBottom) {
                section.style.opacity = '1';
                section.style.transform = 'translateY(0)';
            }
        });
    }
    
    // Aplicar estilo inicial para animação
    sections.forEach(section => {
        section.style.opacity = '0.7';
        section.style.transform = 'translateY(20px)';
        section.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
    });
    
    // Verificar no carregamento inicial
    checkScroll();
    
    // Verificar ao rolar
    window.addEventListener('scroll', checkScroll);

    // Adicionar efeito de hover nos links de navegação
    const navLinks = document.querySelectorAll('.navigation a');
    navLinks.forEach(link => {
        link.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-2px)';
        });
        
        link.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });

    // Adicionar funcionalidade para destacar seções ao clicar nos links internos
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            
            const targetId = this.getAttribute('href');
            const targetElement = document.querySelector(targetId);
            
            if (targetElement) {
                targetElement.scrollIntoView({
                    behavior: 'smooth'
                });
                
                // Destacar a seção
                targetElement.style.backgroundColor = '#f8f8f8';
                setTimeout(() => {
                    targetElement.style.backgroundColor = 'transparent';
                    targetElement.style.transition = 'background-color 1s ease';
                }, 100);
            }
        });
    });
}); 