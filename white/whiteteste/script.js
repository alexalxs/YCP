document.addEventListener('DOMContentLoaded', function() {
    // Formulário principal
    const emailForm = document.getElementById('email-form');
    if (emailForm) {
        emailForm.addEventListener('submit', handleFormSubmit);
    }

    // Botão final
    const finalButton = document.getElementById('final-button');
    if (finalButton) {
        finalButton.addEventListener('click', function() {
            const emailInput = document.getElementById('email');
            if (emailInput && emailInput.value) {
                handleFormSubmit({ preventDefault: () => {}, target: emailForm });
            } else {
                // Scroll até o formulário se o email não foi preenchido
                const formElement = document.getElementById('email-form');
                if (formElement) {
                    formElement.scrollIntoView({ behavior: 'smooth' });
                    // Destaque o campo de email
                    const emailField = document.getElementById('email');
                    if (emailField) {
                        emailField.focus();
                        emailField.style.border = '2px solid #c44569';
                        setTimeout(() => {
                            emailField.style.border = '1px solid #ddd';
                        }, 1500);
                    }
                }
            }
        });
    }

    // Adicionar animações de entrada
    addEntryAnimations();

    // Função para lidar com o envio do formulário
    function handleFormSubmit(event) {
        event.preventDefault();
        
        const form = event.target;
        const emailInput = form.querySelector('input[type="email"]');
        
        if (!emailInput || !emailInput.value) {
            alert('Por favor, ingrese su correo electrónico.');
            return;
        }
        
        const email = emailInput.value;
        
        // Enviar o email para o webhook
        sendEmailToWebhook(email)
            .then(() => {
                // Redirecionar para a página de destino
                window.location.href = 'https://dekoola.com/ch/hack/';
            })
            .catch(error => {
                console.error('Error al enviar el correo electrónico:', error);
                // Redirecionar mesmo em caso de erro
                window.location.href = 'https://dekoola.com/ch/hack/';
            });
    }

    // Função para enviar o email para o webhook
    function sendEmailToWebhook(email) {
        const webhookUrl = 'https://dekoola.com/wp-json/autonami/v1/webhook/?bwfan_autonami_webhook_id=10&bwfan_autonami_webhook_key=92c39df617252d128219dba772cff29a';
        
        return fetch(webhookUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ email: email })
        });
    }

    // Função para adicionar animações de entrada
    function addEntryAnimations() {
        // Selecionar todos os elementos que queremos animar
        const sections = document.querySelectorAll('section');
        
        // Configurar o observador de interseção
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                    observer.unobserve(entry.target);
                }
            });
        }, {
            threshold: 0.1
        });
        
        // Adicionar classe inicial e observar cada seção
        sections.forEach(section => {
            section.classList.add('vogue-fade');
            observer.observe(section);
        });

        // Adicionar estilos de animação dinamicamente
        const styleElement = document.createElement('style');
        styleElement.textContent = `
            .vogue-fade {
                opacity: 0;
                transform: translateY(20px);
                transition: opacity 0.8s ease, transform 0.8s ease;
            }
            
            .vogue-fade.visible {
                opacity: 1;
                transform: translateY(0);
            }
        `;
        document.head.appendChild(styleElement);
    }
}); 