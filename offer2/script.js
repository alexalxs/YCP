// Script para a página de oferta 2
document.addEventListener('DOMContentLoaded', function() {
    console.log('Página de oferta 2 carregada com sucesso!');
    
    // Contador para criar sensação de urgência
    let hours = 23;
    let minutes = 59;
    let seconds = 59;
    
    function updateTimer() {
        seconds--;
        if (seconds < 0) {
            seconds = 59;
            minutes--;
            if (minutes < 0) {
                minutes = 59;
                hours--;
                if (hours < 0) {
                    hours = 23;
                }
            }
        }
        
        document.querySelectorAll('.timer-digits')[0].textContent = hours.toString().padStart(2, '0');
        document.querySelectorAll('.timer-digits')[1].textContent = minutes.toString().padStart(2, '0');
        document.querySelectorAll('.timer-digits')[2].textContent = seconds.toString().padStart(2, '0');
    }
    
    // Atualiza o timer a cada segundo
    setInterval(updateTimer, 1000);
    
    // Adiciona efeito de pulsação ao timer
    const timerElement = document.querySelector('.offer-timer');
    let pulseDirection = 1;
    let pulseOpacity = 1;
    
    setInterval(() => {
        pulseOpacity += 0.01 * pulseDirection;
        if (pulseOpacity >= 1) {
            pulseDirection = -1;
        } else if (pulseOpacity <= 0.7) {
            pulseDirection = 1;
        }
        timerElement.style.opacity = pulseOpacity;
    }, 50);
    
    // Adiciona efeito ao botão de compra
    const comprarBtn = document.getElementById('comprar-btn');
    if (comprarBtn) {
        comprarBtn.addEventListener('mouseover', function() {
            this.style.transform = 'scale(1.05) translateY(-5px)';
            this.style.boxShadow = '0 15px 25px rgba(0,0,0,0.3)';
        });
        
        comprarBtn.addEventListener('mouseout', function() {
            this.style.transform = 'translateY(-3px)';
            this.style.boxShadow = '0 8px 20px rgba(0,0,0,0.2)';
        });
        
        comprarBtn.addEventListener('click', function() {
            this.style.transform = 'translateY(0)';
            this.style.boxShadow = '0 3px 10px rgba(0,0,0,0.2)';
        });
    }
    
    // Adiciona efeito de hover nos benefícios
    const benefits = document.querySelectorAll('.benefit');
    benefits.forEach(benefit => {
        benefit.addEventListener('mouseover', function() {
            this.style.backgroundColor = '#f8f9fa';
        });
        
        benefit.addEventListener('mouseout', function() {
            this.style.backgroundColor = 'white';
        });
    });
    
    // Contador de estoque para criar urgência
    const productHeader = document.querySelector('.product-header p');
    let stockCount = 50;
    
    // Diminui o estoque aleatoriamente
    setInterval(() => {
        if (Math.random() > 0.7 && stockCount > 1) {
            stockCount--;
            productHeader.textContent = `Apenas ${stockCount} unidades disponíveis`;
            
            // Adiciona efeito visual quando o estoque diminui
            productHeader.style.color = '#ff6b6b';
            setTimeout(() => {
                productHeader.style.color = 'white';
            }, 500);
        }
    }, 10000);
}); 