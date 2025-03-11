/**
 * Sistema de Rastreamento de Conversões - Yellow Cloaker
 * Script não-invasivo para capturar dados de formulários de email
 */

document.addEventListener('DOMContentLoaded', function() {
    // Configuração
    const trackingConfig = {
        oferta: window.location.pathname.split('/').filter(Boolean).pop() || 'desconhecida',
        redirectUrl: 'https://site-destino.com', // URL para redirecionamento após captura
        trackingEndpoint: '/email_track.php'     // Seu script de rastreamento
    };
    
    console.log('Rastreamento de conversões iniciado para oferta:', trackingConfig.oferta);
    
    // Capturar todos os formulários de email na página
    const emailForms = document.querySelectorAll('form');
    const emailButtons = document.querySelectorAll('button[type="submit"], .vogue-button, #final-button');
    
    // Configurar todos os formulários encontrados
    emailForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const emailInput = this.querySelector('input[type="email"]');
            const email = emailInput ? emailInput.value : '';
            
            if (email) {
                console.log('Formulário detectado com email:', email);
                // Registrar a conversão e depois redirecionar
                trackConversion(email, trackingConfig.oferta, trackingConfig.redirectUrl);
            } else {
                // Se não encontrar campo de email, apenas redireciona
                window.location.href = trackingConfig.redirectUrl;
            }
        });
    });
    
    // Configurar botões que não estão em formulários
    emailButtons.forEach(button => {
        if (!button.closest('form')) { // Apenas para botões que não estão dentro de formulários
            button.addEventListener('click', function(e) {
                e.preventDefault();
                
                // Tentar encontrar qualquer campo de email na página
                const emailInputs = document.querySelectorAll('input[type="email"]');
                let email = '';
                
                if (emailInputs.length > 0) {
                    // Usar o primeiro campo de email preenchido que encontrar
                    for (let input of emailInputs) {
                        if (input.value) {
                            email = input.value;
                            break;
                        }
                    }
                }
                
                if (email) {
                    console.log('Botão clicado com email:', email);
                    trackConversion(email, trackingConfig.oferta, trackingConfig.redirectUrl);
                } else {
                    // Se não tiver email já preenchido, pedir ao usuário
                    const userEmail = prompt('Por favor, digite seu email para continuar:');
                    if (userEmail && validateEmail(userEmail)) {
                        trackConversion(userEmail, trackingConfig.oferta, trackingConfig.redirectUrl);
                    } else if (userEmail) {
                        alert('Por favor, digite um email válido.');
                    } else {
                        // Se usuário cancelar prompt, apenas redirecionar
                        window.location.href = trackingConfig.redirectUrl;
                    }
                }
            });
        }
    });
    
    // Função para rastrear a conversão
    function trackConversion(email, oferta, redirectUrl) {
        // Indicador visual de que o sistema está processando
        const processingDiv = document.createElement('div');
        processingDiv.style.position = 'fixed';
        processingDiv.style.top = '0';
        processingDiv.style.left = '0';
        processingDiv.style.width = '100%';
        processingDiv.style.height = '100%';
        processingDiv.style.backgroundColor = 'rgba(0,0,0,0.5)';
        processingDiv.style.zIndex = '9999';
        processingDiv.style.display = 'flex';
        processingDiv.style.justifyContent = 'center';
        processingDiv.style.alignItems = 'center';
        processingDiv.style.color = 'white';
        processingDiv.style.fontSize = '20px';
        processingDiv.textContent = 'Processando...';
        document.body.appendChild(processingDiv);
        
        // Criar os dados para enviar
        const formData = new FormData();
        formData.append('email', email);
        formData.append('oferta', oferta);
        formData.append('redirect_url', redirectUrl);
        
        // Adicionar timestamp e informações do navegador
        formData.append('timestamp', new Date().toISOString());
        formData.append('user_agent', navigator.userAgent);
        formData.append('referrer', document.referrer);
        formData.append('page_url', window.location.href);
        
        // Enviar dados para o servidor
        fetch(trackingConfig.trackingEndpoint, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            console.log('Conversão registrada:', data);
            // Redirecionar para o destino após o registro
            window.location.href = redirectUrl;
        })
        .catch(error => {
            console.error('Erro ao registrar conversão:', error);
            // Redirecionar mesmo se houver erro, para não atrapalhar a experiência
            window.location.href = redirectUrl;
        });
        
        // Backup de redirecionamento após 3 segundos caso o fetch falhe
        setTimeout(() => {
            if (document.body.contains(processingDiv)) {
                document.body.removeChild(processingDiv);
            }
            
            if (window.location.href !== redirectUrl) {
                window.location.href = redirectUrl;
            }
        }, 3000);
    }
    
    // Função para validar email
    function validateEmail(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(String(email).toLowerCase());
    }
}); 