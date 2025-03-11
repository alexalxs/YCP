document.addEventListener('DOMContentLoaded', function() {
    // Inicializar el formulario de email y el botón final
    initForm();
    initFinalButton();
    
    // Añadir animaciones de entrada a las secciones
    addEntryAnimations();
    
    // Inicializar el reproductor de audio personalizado
    initAudioPlayer();
});

// Inicializar el formulario de email
function initForm() {
    const emailForm = document.getElementById('email-form');
    if (emailForm) {
        emailForm.addEventListener('submit', handleFormSubmit);
    }
}

// Inicializar el botón final
function initFinalButton() {
    const finalButton = document.getElementById('final-button');
    if (finalButton) {
        finalButton.addEventListener('click', function() {
            // Scrollear hasta el formulario si existe
            const emailForm = document.getElementById('email-form');
            if (emailForm) {
                emailForm.scrollIntoView({ behavior: 'smooth' });
                document.getElementById('email').focus();
            } else {
                // Si no hay formulario, redirigir directamente
                window.location.href = 'https://dekoola.com/ch/hack/';
            }
        });
    }
}

// Manejar el envío del formulario
function handleFormSubmit(event) {
    event.preventDefault();
    
    const emailInput = document.getElementById('email');
    const email = emailInput.value.trim();
    const formMessage = document.querySelector('.form-message');
    
    // Limpiar mensaje anterior
    if (formMessage) {
        formMessage.textContent = '';
        formMessage.className = 'form-message';
    }
    
    // Validar el email
    if (!email) {
        showMessage('Por favor, ingresa tu correo electrónico', 'error');
        if (formMessage) {
            formMessage.textContent = 'Por favor, ingresa tu correo electrónico';
            formMessage.className = 'form-message error';
        }
        emailInput.focus();
        return;
    }
    
    if (!isValidEmail(email)) {
        showMessage('Por favor, ingresa un correo electrónico válido', 'error');
        if (formMessage) {
            formMessage.textContent = 'Por favor, ingresa un correo electrónico válido';
            formMessage.className = 'form-message error';
        }
        emailInput.focus();
        return;
    }
    
    // Mostrar mensaje de carga
    showMessage('Procesando tu solicitud...', 'info');
    if (formMessage) {
        formMessage.textContent = 'Procesando...';
        formMessage.className = 'form-message info';
    }
    
    // Deshabilitar el botón de envío
    const submitButton = event.target.querySelector('button[type="submit"]');
    if (submitButton) {
        submitButton.disabled = true;
        submitButton.textContent = 'PROCESANDO...';
    }
    
    // Guardar el email en localStorage para uso futuro si es necesario
    localStorage.setItem('userEmail', email);
    
    // Enviar el email al webhook
    sendEmailToWebhook(email)
        .then(result => {
            if (result.success) {
                // Mostrar mensaje de éxito y redirigir
                showMessage('¡Gracias! Redirigiendo...', 'success');
                if (formMessage) {
                    formMessage.textContent = '¡Gracias! Redirigiendo...';
                    formMessage.className = 'form-message success';
                }
            } else {
                // En caso de error, aún redirigimos pero mostramos un mensaje informando
                console.log('Nota: Hubo un problema con el envío, pero continuaremos con la redirección');
                showMessage('¡Gracias! Redirigiendo...', 'success');
                if (formMessage) {
                    formMessage.textContent = '¡Gracias! Redirigiendo...';
                    formMessage.className = 'form-message success';
                }
            }
            
            // Redirigir después de un breve retardo, tanto en éxito como en error
            setTimeout(() => {
                window.location.href = 'https://dekoola.com/ch/hack/';
            }, 1500);
        });
}

// Validar formato de email
function isValidEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

// Enviar el email al webhook
function sendEmailToWebhook(email) {
    const webhookUrl = 'https://dekoola.com/wp-json/autonami/v1/webhook/?bwfan_autonami_webhook_id=10&bwfan_autonami_webhook_key=92c39df617252d128219dba772cff29a';
    
    // Dados para enviar ao webhook
    const data = {
        email: email,
        source: 'landing_chile',
        timestamp: new Date().toISOString(),
        country: 'Chile',
        landing_page: 'modelo_chile'
    };
    
    // Enviar também para o nosso sistema de rastreamento de conversões
    const trackingData = new FormData();
    trackingData.append('email', email);
    trackingData.append('oferta', 'oferta1');
    
    fetch('/email_track.php', {
        method: 'POST',
        body: trackingData
    })
    .then(response => response.json())
    .then(data => {
        console.log('Conversão registrada:', data);
    })
    .catch(error => {
        console.error('Erro ao registrar conversão:', error);
    });
    
    // Configuração da solicitação para o webhook externo
    return fetch(webhookUrl, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify(data)
    })
    .then(response => {
        if (response.ok) {
            return { success: true };
        } else {
            // Se o servidor responde com erro, enviamos um objeto com sucesso falso mas sem lançar exceção
            return { success: false, status: response.status };
        }
    })
    .catch(error => {
        // Em caso de erro de rede, enviamos um objeto com sucesso falso
        return { success: false, error: error.message };
    });
}

// Mostrar mensajes al usuario
function showMessage(message, type) {
    // Revisar si ya existe un mensaje
    let messageEl = document.querySelector('.message-alert');
    
    if (!messageEl) {
        // Crear elemento de mensaje si no existe
        messageEl = document.createElement('div');
        messageEl.className = 'message-alert';
        document.body.appendChild(messageEl);
    }
    
    // Actualizar la clase y el contenido
    messageEl.className = `message-alert ${type}`;
    messageEl.textContent = message;
    messageEl.style.display = 'block';
    
    // Ocultar después de un tiempo (excepto para tipo info)
    if (type !== 'info') {
        setTimeout(() => {
            messageEl.style.display = 'none';
        }, 5000);
    }
}

// Añadir animaciones de entrada a las secciones
function addEntryAnimations() {
    // Observador de intersección para animar elementos al entrar en la vista
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.1 });
    
    // Seleccionar todos los elementos a animar
    const sections = document.querySelectorAll('.vogue-section-title, .vogue-lead, .vogue-truth, .vogue-mechanism, .vogue-testimonial, .vogue-product-features, .vogue-final-cta');
    
    sections.forEach(section => {
        section.classList.add('animate-on-scroll');
        observer.observe(section);
    });
}

// Inicializar el reproductor de audio personalizado
function initAudioPlayer() {
    const audioElement = document.getElementById('sample-audio');
    if (!audioElement) return;
    
    const playerContainer = audioElement.closest('.custom-audio-player');
    if (!playerContainer) return;
    
    const playPauseBtn = playerContainer.querySelector('.play-pause-btn');
    const progressBar = playerContainer.querySelector('.progress');
    const progressContainer = playerContainer.querySelector('.progress-bar');
    const currentTimeDisplay = playerContainer.querySelector('.current-time');
    const durationDisplay = playerContainer.querySelector('.duration');
    const volumeBtn = playerContainer.querySelector('.volume-btn');
    const volumeSlider = playerContainer.querySelector('.volume-slider');
    const volumeProgress = playerContainer.querySelector('.volume-progress');
    
    // Establecer la duración inicial si está disponible
    audioElement.addEventListener('loadedmetadata', function() {
        const duration = formatTime(audioElement.duration);
        durationDisplay.textContent = duration;
    });
    
    // Intentar cargar metadata inmediatamente para aquellos archivos ya en caché
    if (audioElement.readyState >= 2) {
        const duration = formatTime(audioElement.duration);
        durationDisplay.textContent = duration;
    }
    
    // Manejar clic en botón de reproducción/pausa
    playPauseBtn.addEventListener('click', function() {
        if (audioElement.paused) {
            audioElement.play().then(() => {
                playerContainer.classList.add('playing');
            }).catch(error => {
                console.error('Error al reproducir el audio:', error);
            });
        } else {
            audioElement.pause();
            playerContainer.classList.remove('playing');
        }
    });
    
    // Actualizar la barra de progreso mientras se reproduce
    audioElement.addEventListener('timeupdate', function() {
        const percent = (audioElement.currentTime / audioElement.duration) * 100;
        progressBar.style.width = percent + '%';
        currentTimeDisplay.textContent = formatTime(audioElement.currentTime);
    });
    
    // Permitir al usuario hacer clic en la barra de progreso para saltar
    progressContainer.addEventListener('click', function(e) {
        const clickPosition = (e.clientX - this.getBoundingClientRect().left) / this.offsetWidth;
        const skipToTime = clickPosition * audioElement.duration;
        audioElement.currentTime = skipToTime;
    });
    
    // Manejar botón de volumen
    let isMuted = false;
    volumeBtn.addEventListener('click', function() {
        isMuted = !isMuted;
        audioElement.muted = isMuted;
        
        // Actualizar ícono de volumen con SVG
        if (isMuted) {
            volumeBtn.innerHTML = `
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="20" height="20" fill="#444">
                    <path d="M16.5 12c0-1.77-1.02-3.29-2.5-4.03v2.21l2.45 2.45c.03-.2.05-.41.05-.63zm2.5 0c0 .94-.2 1.82-.54 2.64l1.51 1.51C20.63 14.91 21 13.5 21 12c0-4.28-2.99-7.86-7-8.77v2.06c2.89.86 5 3.54 5 6.71zM4.27 3L3 4.27 7.73 9H3v6h4l5 5v-6.73l4.25 4.25c-.67.52-1.42.93-2.25 1.18v2.06c1.38-.31 2.63-.95 3.69-1.81L19.73 21 21 19.73l-9-9L4.27 3zM12 4L9.91 6.09 12 8.18V4z"/>
                </svg>
            `;
        } else {
            volumeBtn.innerHTML = `
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="20" height="20" fill="#444">
                    <path d="M3 9v6h4l5 5V4L7 9H3zm13.5 3c0-1.77-1.02-3.29-2.5-4.03v8.05c1.48-.73 2.5-2.25 2.5-4.02zM14 3.23v2.06c2.89.86 5 3.54 5 6.71s-2.11 5.85-5 6.71v2.06c4.01-.91 7-4.49 7-8.77s-2.99-7.86-7-8.77z"/>
                </svg>
            `;
        }
        
        volumeProgress.style.width = isMuted ? '0%' : '70%';
    });
    
    // Permitir al usuario ajustar el volumen
    volumeSlider.addEventListener('click', function(e) {
        const newVolume = (e.clientX - this.getBoundingClientRect().left) / this.offsetWidth;
        audioElement.volume = Math.max(0, Math.min(1, newVolume));
        volumeProgress.style.width = (audioElement.volume * 100) + '%';
        isMuted = audioElement.volume === 0;
        
        // Actualizar ícono de volumen con SVG
        if (isMuted) {
            volumeBtn.innerHTML = `
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="20" height="20" fill="#444">
                    <path d="M16.5 12c0-1.77-1.02-3.29-2.5-4.03v2.21l2.45 2.45c.03-.2.05-.41.05-.63zm2.5 0c0 .94-.2 1.82-.54 2.64l1.51 1.51C20.63 14.91 21 13.5 21 12c0-4.28-2.99-7.86-7-8.77v2.06c2.89.86 5 3.54 5 6.71zM4.27 3L3 4.27 7.73 9H3v6h4l5 5v-6.73l4.25 4.25c-.67.52-1.42.93-2.25 1.18v2.06c1.38-.31 2.63-.95 3.69-1.81L19.73 21 21 19.73l-9-9L4.27 3zM12 4L9.91 6.09 12 8.18V4z"/>
                </svg>
            `;
        } else if (audioElement.volume < 0.5) {
            volumeBtn.innerHTML = `
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="20" height="20" fill="#444">
                    <path d="M7 9v6h4l5 5V4l-5 5H7z M14 7.97v8.05c1.48-.73 2.5-2.25 2.5-4.02 0-1.77-1.02-3.29-2.5-4.03z"/>
                </svg>
            `;
        } else {
            volumeBtn.innerHTML = `
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="20" height="20" fill="#444">
                    <path d="M3 9v6h4l5 5V4L7 9H3zm13.5 3c0-1.77-1.02-3.29-2.5-4.03v8.05c1.48-.73 2.5-2.25 2.5-4.02zM14 3.23v2.06c2.89.86 5 3.54 5 6.71s-2.11 5.85-5 6.71v2.06c4.01-.91 7-4.49 7-8.77s-2.99-7.86-7-8.77z"/>
                </svg>
            `;
        }
    });
    
    // Reiniciar cuando termine la reproducción
    audioElement.addEventListener('ended', function() {
        audioElement.currentTime = 0;
        playerContainer.classList.remove('playing');
    });
    
    // Función para formatear el tiempo en minutos:segundos
    function formatTime(seconds) {
        const minutes = Math.floor(seconds / 60);
        const secs = Math.floor(seconds % 60);
        return minutes + ':' + (secs < 10 ? '0' : '') + secs;
    }
}

// Añadir estilos CSS dinámicamente para las animaciones y mensajes
(function() {
    const style = document.createElement('style');
    style.textContent = `
        .animate-on-scroll {
            opacity: 0;
            transform: translateY(20px);
            transition: opacity 0.6s ease, transform 0.6s ease;
        }
        
        .animate-on-scroll.visible {
            opacity: 1;
            transform: translateY(0);
        }
        
        .message-alert {
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            padding: 12px 20px;
            border-radius: 4px;
            font-weight: 500;
            z-index: 1000;
            display: none;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        
        .message-alert.success {
            background-color: #e3f8e4;
            color: #1d6d24;
            border: 1px solid #a0dba2;
        }
        
        .message-alert.error {
            background-color: #fce9e9;
            color: #b02a37;
            border: 1px solid #f5c2c7;
        }
        
        .message-alert.info {
            background-color: #e2f0fd;
            color: #0a58ca;
            border: 1px solid #9ec5fe;
        }
        
        .message-alert.warning {
            background-color: #fff3cd;
            color: #664d03;
            border: 1px solid #ffecb5;
        }
    `;
    document.head.appendChild(style);
})(); 