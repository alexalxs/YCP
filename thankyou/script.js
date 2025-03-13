document.addEventListener('DOMContentLoaded', function() {
    // Animação do ícone de sucesso
    const successIcon = document.querySelector('.success-icon');
    if (successIcon) {
        successIcon.style.opacity = 1;
    }

    // Gerar número de pedido aleatório
    const orderNumber = document.getElementById('order-number');
    if (orderNumber) {
        const randomOrderId = 'PED-' + Math.floor(100000 + Math.random() * 900000);
        orderNumber.textContent = randomOrderId;
    }

    // Definir data atual
    const orderDate = document.getElementById('order-date');
    if (orderDate) {
        const now = new Date();
        const options = { year: 'numeric', month: 'long', day: 'numeric' };
        orderDate.textContent = now.toLocaleDateString('pt-BR', options);
    }

    // Efeito de hover nos links de navegação
    const navLinks = document.querySelectorAll('.navigation a');
    navLinks.forEach(link => {
        link.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-2px)';
            this.style.transition = 'transform 0.3s';
        });
        
        link.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });

    // Contador regressivo para processamento do pedido
    let processingTime = 300; // 5 minutos em segundos
    const processingCounter = document.getElementById('processing-time');
    
    if (processingCounter) {
        const updateCounter = setInterval(function() {
            processingTime--;
            
            const minutes = Math.floor(processingTime / 60);
            const seconds = processingTime % 60;
            
            processingCounter.textContent = `${minutes}:${seconds < 10 ? '0' : ''}${seconds}`;
            
            if (processingTime <= 0) {
                clearInterval(updateCounter);
                processingCounter.textContent = "Concluído!";
                
                const statusElement = document.querySelector('.status-processing');
                if (statusElement) {
                    statusElement.textContent = "Concluído";
                    statusElement.style.backgroundColor = "#4CAF50";
                    statusElement.style.color = "white";
                }
            }
        }, 1000);
    }
});