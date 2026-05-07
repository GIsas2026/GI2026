// ============================================
// MANEJO DEL MENÚ HAMBURGUESA
// ============================================
document.addEventListener('DOMContentLoaded', function() {
    const hamburger = document.querySelector('.hamburger');
    const navLinks = document.querySelector('.nav-links');

    if (hamburger) {
        hamburger.addEventListener('click', function() {
            navLinks.classList.toggle('active');
            hamburger.classList.toggle('active');
        });

        // Cerrar menú al hacer clic en un enlace
        document.querySelectorAll('.nav-link').forEach(link => {
            link.addEventListener('click', function() {
                navLinks.classList.remove('active');
                hamburger.classList.remove('active');
            });
        });
    }

    // ============================================
    // FORMULARIO DE CONTACTO
    // ============================================
    const contactoForm = document.getElementById('contactoForm');
    
    if (contactoForm) {
        contactoForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            // Obtener datos del formulario
            const formData = {
                nombre: document.getElementById('nombre').value.trim(),
                correo: document.getElementById('correo').value.trim(),
                telefono: document.getElementById('telefono').value.trim(),
                servicio: document.getElementById('servicio').value,
                descripcion: document.getElementById('descripcion').value.trim()
            };

            // Validar campos requeridos
            if (!formData.nombre || !formData.correo || !formData.servicio || !formData.descripcion) {
                mostrarMensaje('Por favor, completa todos los campos requeridos.', 'error');
                return;
            }

            // Validar formato de correo
            if (!validarEmail(formData.correo)) {
                mostrarMensaje('Por favor, ingresa un correo válido.', 'error');
                return;
            }

            // Mostrar estado de envío
            const submitBtn = contactoForm.querySelector('.submit-btn');
            const textOriginal = submitBtn.textContent;
            submitBtn.disabled = true;
            submitBtn.textContent = 'Enviando...';

            try {
                // Enviar datos al servidor
                const response = await fetch('api/enviar-contacto.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(formData)
                });

                const resultado = await response.json();

                if (response.ok && resultado.exito) {
                    mostrarMensaje('¡Mensaje enviado correctamente! Nos pondremos en contacto pronto.', 'success');
                    contactoForm.reset();
                } else {
                    mostrarMensaje(resultado.mensaje || 'Error al enviar el mensaje. Intenta nuevamente.', 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                mostrarMensaje('Error de conexión. Por favor, intenta nuevamente.', 'error');
            } finally {
                submitBtn.disabled = false;
                submitBtn.textContent = textOriginal;
            }
        });
    }

    // ============================================
    // FUNCIONES AUXILIARES
    // ============================================
    
    // Validar formato de email
    function validarEmail(email) {
        const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return regex.test(email);
    }

    // Mostrar mensaje de formulario
    function mostrarMensaje(mensaje, tipo) {
        const messageDiv = document.getElementById('formMessage');
        messageDiv.textContent = mensaje;
        messageDiv.className = 'form-message ' + tipo;
        
        // Auto-ocultar después de 5 segundos
        setTimeout(() => {
            messageDiv.className = 'form-message';
        }, 5000);
    }

    // ============================================
    // SCROLL SUAVE
    // ============================================
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            const href = this.getAttribute('href');
            if (href !== '#') {
                const target = document.querySelector(href);
                if (target) {
                    e.preventDefault();
                    target.scrollIntoView({
                        behavior: 'smooth'
                    });
                }
            }
        });
    });

    // ============================================
    // ANIMACIONES AL SCROLL
    // ============================================
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -100px 0px'
    };

    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);

    document.querySelectorAll('.servicio-card').forEach(card => {
        observer.observe(card);
    });

    // ============================================
    // VALIDACIÓN EN TIEMPO REAL
    // ============================================
    const inputNombre = document.getElementById('nombre');
    const inputCorreo = document.getElementById('correo');
    const inputDescripcion = document.getElementById('descripcion');

    if (inputNombre) {
        inputNombre.addEventListener('input', function() {
            if (this.value.trim().length > 0) {
                this.style.borderColor = '#27ae60';
            } else {
                this.style.borderColor = '#dddddd';
            }
        });
    }

    if (inputCorreo) {
        inputCorreo.addEventListener('blur', function() {
            if (validarEmail(this.value)) {
                this.style.borderColor = '#27ae60';
            } else if (this.value.trim().length > 0) {
                this.style.borderColor = '#e74c3c';
            } else {
                this.style.borderColor = '#dddddd';
            }
        });
    }

    if (inputDescripcion) {
        inputDescripcion.addEventListener('input', function() {
            if (this.value.trim().length > 0) {
                this.style.borderColor = '#27ae60';
            } else {
                this.style.borderColor = '#dddddd';
            }
        });
    }
});

// ============================================
// AGREGAR CLASE VISIBLE PARA ANIMACIONES
// ============================================
const style = document.createElement('style');
style.textContent = `
    .servicio-card {
        opacity: 0;
        transform: translateY(20px);
        transition: opacity 0.6s ease, transform 0.6s ease;
    }
    
    .servicio-card.visible {
        opacity: 1;
        transform: translateY(0);
    }
`;
document.head.appendChild(style);