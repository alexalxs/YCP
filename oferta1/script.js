/**
 * Script de rastreamento de conversões de email
 * Este script captura automaticamente envios de formulários e envia os dados para o endpoint de rastreamento
 */
(function() {
    // Configuração
    const trackingEndpoint = '/email_track.php';
    const debug = true; // Ativar modo de depuração para diagnóstico
    
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
        
        // Verificar se estamos em uma pasta de oferta específica
        const ofertaMatches = path.match(/\/oferta([0-9]+)/);
        if (ofertaMatches && ofertaMatches.length > 1) {
            return 'oferta' + ofertaMatches[1];
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
        log('Formulário enviado');
        
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
                log('Campo de email encontrado: ' + input.name);
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
                        log('Campo de texto com email encontrado: ' + input.name);
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
                log('Email encontrado: ' + email + ', Oferta: ' + oferta);
                sendConversion(email, oferta);
            } else {
                log('Campo de email está vazio');
            }
        } else {
            log('Nenhum campo de email encontrado no formulário');
        }
    }
    
    // Função para adicionar campo oculto de oferta aos formulários
    function addHiddenOfferField() {
        const forms = document.querySelectorAll('form');
        const oferta = getOfferFromPath();
        
        log('Adicionando campo de oferta aos formulários. Oferta: ' + oferta);
        log('Encontrados ' + forms.length + ' formulários');
        
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
            } else {
                log('Campo de oferta já existe no formulário');
            }
        });
    }
    
    // Função para adicionar disclaimer
    function addDisclaimer() {
        if (!document.querySelector('.disclaimer')) {
            const disclaimerHtml = `
            <div class="disclaimer" style="margin-top: 30px; padding: 15px; background-color: #f8f9fa; border-top: 1px solid #dee2e6; font-size: 12px; color: #6c757d;">
                <p>Este site e sua oferta não são afiliados, associados, endossados ou patrocinados pelo Facebook. Facebook é uma marca registrada de Meta Platforms, Inc.</p>
                <p>Os resultados individuais podem variar. Os depoimentos apresentados são experiências reais de usuários do nosso método, mas os resultados não são garantidos e dependem de diversos fatores individuais.</p>
                <p>As informações contidas neste site destinam-se apenas a fins informativos e educacionais e não substituem aconselhamento profissional em relacionamentos, psicológico ou médico.</p>
                <p>&copy; 2023 Gatillos Invisibles de la Atracción. Todos los derechos reservados.</p>
            </div>`;
            
            const body = document.querySelector('body');
            if (body) {
                const div = document.createElement('div');
                div.innerHTML = disclaimerHtml;
                body.appendChild(div);
                log('Disclaimer adicionado à página');
            }
        }
    }
    
    // Função de inicialização
    function init() {
        log('Inicializando rastreador de conversões');
        
        // Adicionar campos ocultos de oferta aos formulários
        addHiddenOfferField();
        
        // Adicionar disclaimer
        addDisclaimer();
        
        // Capturar envios de formulários
        document.addEventListener('submit', function(event) {
            // Não impedir o envio normal do formulário
            processFormSubmit(event);
        });
        
        // Capturar cliques em botões que possam ser de envio
        document.addEventListener('click', function(event) {
            const target = event.target;
            
            // Verificar se é um botão que não está dentro de um formulário
            if (target.tagName === 'BUTTON' && !target.closest('form')) {
                log('Clique em botão fora de formulário: ' + target.textContent);
                
                // Verificar se há um campo de email na página
                const emailInput = document.querySelector('input[type="email"], input[name*="email"], input[id*="email"]');
                if (emailInput) {
                    const email = emailInput.value.trim();
                    if (email) {
                        const oferta = getOfferFromPath();
                        log('Email encontrado: ' + email + ', Oferta: ' + oferta);
                        sendConversion(email, oferta);
                    }
                }
            }
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