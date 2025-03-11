/**
 * Script de rastreamento de conversões de email
 * Este script captura automaticamente envios de formulários e envia os dados para o endpoint de rastreamento
 */
(function() {
    // Configuração
    const trackingEndpoint = '/email_track.php';
    const debug = false;
    
    // Função para log (apenas em modo debug)
    function log(message) {
        if (debug) {
            console.log('[Conversion Tracker] ' + message);
        }
    }
    
    // Função para obter o valor da oferta da URL ou do caminho
    function getOfferFromPath() {
        // Tentar obter da URL primeiro
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.has('oferta')) {
            return urlParams.get('oferta');
        }
        
        // Tentar obter do caminho
        const path = window.location.pathname;
        const matches = path.match(/\/offers\/([^\/]+)/);
        if (matches && matches.length > 1) {
            return matches[1];
        }
        
        // Valor padrão
        return 'oferta1';
    }
    
    // Função para enviar dados de conversão
    function sendConversion(email, oferta) {
        log('Enviando conversão: ' + email + ' - ' + oferta);
        
        // Criar FormData
        const formData = new FormData();
        formData.append('email', email);
        formData.append('oferta', oferta);
        
        // Enviar via fetch
        fetch(trackingEndpoint, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            log('Resposta do servidor: ' + JSON.stringify(data));
        })
        .catch(error => {
            log('Erro ao enviar conversão: ' + error);
        });
    }
    
    // Função para processar o envio de formulário
    function processFormSubmit(event) {
        // Obter o formulário
        const form = event.target;
        
        // Procurar campo de email
        let emailField = null;
        const inputs = form.querySelectorAll('input');
        
        for (let i = 0; i < inputs.length; i++) {
            const input = inputs[i];
            
            // Verificar se é um campo de email
            if (input.type === 'email' || 
                input.name.toLowerCase().includes('email') || 
                input.id.toLowerCase().includes('email')) {
                emailField = input;
                break;
            }
        }
        
        // Se não encontrou campo de email, verificar outros campos de texto
        if (!emailField) {
            for (let i = 0; i < inputs.length; i++) {
                const input = inputs[i];
                
                // Verificar se é um campo de texto que pode conter email
                if (input.type === 'text') {
                    const value = input.value.trim();
                    if (value.includes('@') && value.includes('.')) {
                        emailField = input;
                        break;
                    }
                }
            }
        }
        
        // Se encontrou um campo de email, enviar a conversão
        if (emailField) {
            const email = emailField.value.trim();
            const oferta = getOfferFromPath();
            
            if (email) {
                sendConversion(email, oferta);
            }
        }
    }
    
    // Função para adicionar campo oculto de oferta aos formulários
    function addHiddenOfferField() {
        const forms = document.querySelectorAll('form');
        const oferta = getOfferFromPath();
        
        forms.forEach(form => {
            // Verificar se já existe um campo de oferta
            let ofertaField = form.querySelector('input[name="oferta"]');
            
            // Se não existir, criar um
            if (!ofertaField) {
                ofertaField = document.createElement('input');
                ofertaField.type = 'hidden';
                ofertaField.name = 'oferta';
                ofertaField.value = oferta;
                form.appendChild(ofertaField);
                log('Campo de oferta adicionado ao formulário');
            }
        });
    }
    
    // Função de inicialização
    function init() {
        log('Inicializando rastreador de conversões');
        
        // Adicionar campos ocultos de oferta aos formulários
        addHiddenOfferField();
        
        // Capturar envios de formulários
        document.addEventListener('submit', function(event) {
            processFormSubmit(event);
        });
        
        log('Rastreador de conversões inicializado');
    }
    
    // Inicializar quando o DOM estiver pronto
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})(); 