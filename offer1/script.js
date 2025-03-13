// Script para a página de oferta 1
document.addEventListener('DOMContentLoaded', function() {
    console.log('Página de oferta 1 carregada com sucesso!');
    
    // Animação do banner de oferta
    const offerBanner = document.querySelector('.offer-banner');
    setInterval(() => {
        offerBanner.style.backgroundColor = offerBanner.style.backgroundColor === 'rgb(255, 107, 107)' 
            ? '#e74c3c' 
            : '#ff6b6b';
    }, 2000);
    
    // Adiciona contador de tempo para criar urgência
    let countdownMinutes = 30;
    let countdownSeconds = 0;
    
    // Adiciona elemento de contagem regressiva ao banner
    const countdownElement = document.createElement('div');
    countdownElement.classList.add('countdown');
    countdownElement.style.marginTop = '10px';
    countdownElement.style.fontWeight = 'bold';
    offerBanner.appendChild(countdownElement);
    
    // Atualiza o contador a cada segundo
    const countdownInterval = setInterval(() => {
        countdownElement.textContent = `Oferta expira em: ${countdownMinutes}:${countdownSeconds < 10 ? '0' + countdownSeconds : countdownSeconds}`;
        
        if (countdownMinutes === 0 && countdownSeconds === 0) {
            clearInterval(countdownInterval);
            countdownElement.textContent = 'OFERTA EXPIRADA!';
        } else {
            if (countdownSeconds === 0) {
                countdownMinutes--;
                countdownSeconds = 59;
            } else {
                countdownSeconds--;
            }
        }
    }, 1000);
    
    // Adiciona efeito ao botão de compra
    const comprarBtn = document.getElementById('comprar-btn');
    if (comprarBtn) {
        comprarBtn.addEventListener('mouseover', function() {
            this.style.transform = 'scale(1.05) translateY(-5px)';
            this.style.boxShadow = '0 10px 20px rgba(0,0,0,0.2)';
        });
        
        comprarBtn.addEventListener('mouseout', function() {
            this.style.transform = 'translateY(-3px)';
            this.style.boxShadow = '0 5px 15px rgba(0,0,0,0.2)';
        });
        
        comprarBtn.addEventListener('click', function(e) {
            // Apenas para efeito visual, não impede a navegação
            this.style.transform = 'translateY(0)';
            this.style.boxShadow = '0 2px 5px rgba(0,0,0,0.2)';
            
            // Simula um pequeno atraso antes de navegar
            setTimeout(() => {
                this.style.transform = 'translateY(-3px)';
                this.style.boxShadow = '0 5px 15px rgba(0,0,0,0.2)';
            }, 100);
        });
    }
}); 