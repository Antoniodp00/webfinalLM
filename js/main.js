/**
 * Fitness360 - Archivo JavaScript Principal
 * Este archivo contiene toda la funcionalidad JavaScript para el sitio web Fitness360
 */

document.addEventListener('DOMContentLoaded', function() {
    'use strict';

    /**
     * Función auxiliar para seleccionar elementos fácilmente
     */
    const select = (el, all = false) => {
        el = el.trim();
        if (all) {
            return [...document.querySelectorAll(el)];
        } else {
            return document.querySelector(el);
        }
    };

    /**
     * Función auxiliar para añadir event listeners fácilmente
     */
    const on = (type, el, listener, all = false) => {
        let selectEl = select(el, all);
        if (selectEl) {
            if (all) {
                selectEl.forEach(e => e.addEventListener(type, listener));
            } else {
                selectEl.addEventListener(type, listener);
            }
        }
    };

    /**
     * Función auxiliar para añadir event listeners de scroll fácilmente
     */
    const onscroll = (el, listener) => {
        el.addEventListener('scroll', listener);
    };

    /**
     * Estado activo de los enlaces de la barra de navegación al hacer scroll
     */
    let navbarlinks = select('#navbarNav .nav-link', true);
    const navbarlinksActive = () => {
        let position = window.scrollY + 200;
        navbarlinks.forEach(navbarlink => {
            if (!navbarlink.hash) return;
            let section = select(navbarlink.hash);
            if (!section) return;
            if (position >= section.offsetTop && position <= (section.offsetTop + section.offsetHeight)) {
                navbarlink.classList.add('active');
            } else {
                navbarlink.classList.remove('active');
            }
        });
    };
    window.addEventListener('load', navbarlinksActive);
    onscroll(document, navbarlinksActive);

    /**
     * Desplazamiento a un elemento con compensación del encabezado
     */
    const scrollto = (el) => {
        let header = select('header');
        let offset = header.offsetHeight;

        let elementPos = select(el).offsetTop;
        window.scrollTo({
            top: elementPos - offset,
            behavior: 'smooth'
        });
    };

    /**
     * Alternar la clase .header-scrolled en el encabezado cuando se desplaza la página
     */
    let selectHeader = select('header');
    if (selectHeader) {
        const headerScrolled = () => {
            if (window.scrollY > 100) {
                selectHeader.classList.add('header-scrolled');
            } else {
                selectHeader.classList.remove('header-scrolled');
            }
        };
        window.addEventListener('load', headerScrolled);
        onscroll(document, headerScrolled);
    }

    /**
     * Botón para volver arriba
     */
    let backtotop = select('.back-to-top');
    if (backtotop) {
        const toggleBacktotop = () => {
            if (window.scrollY > 100) {
                backtotop.classList.add('active');
            } else {
                backtotop.classList.remove('active');
            }
        };
        window.addEventListener('load', toggleBacktotop);
        onscroll(document, toggleBacktotop);
    }

    /**
     * Alternar navegación móvil
     */
    on('click', '.mobile-nav-toggle', function(e) {
        select('body').classList.toggle('mobile-nav-active');
        this.classList.toggle('bi-list');
        this.classList.toggle('bi-x');
    });

    /**
     * Desplazamiento con compensación en enlaces con el nombre de clase .scrollto
     */
    on('click', '.scrollto', function(e) {
        if (select(this.hash)) {
            e.preventDefault();

            let body = select('body');
            if (body.classList.contains('mobile-nav-active')) {
                body.classList.remove('mobile-nav-active');
                let navbarToggle = select('.mobile-nav-toggle');
                navbarToggle.classList.toggle('bi-list');
                navbarToggle.classList.toggle('bi-x');
            }
            scrollto(this.hash);
        }
    }, true);

    /**
     * Desplazamiento con compensación al cargar la página con enlaces hash en la URL
     */
    window.addEventListener('load', () => {
        if (window.location.hash) {
            if (select(window.location.hash)) {
                scrollto(window.location.hash);
            }
        }
    });

    /**
     * Animación al hacer scroll
     */
    window.addEventListener('load', () => {
        AOS.init({
            duration: 1000,
            easing: 'ease-in-out',
            once: true,
            mirror: false
        });
    });

    /**
     * Validación y envío de formularios
     */
    const contactForm = document.querySelector('.php-email-form');
    if (contactForm) {
        contactForm.addEventListener('submit', function(e) {
            e.preventDefault();

            // Validación simple
            const name = this.querySelector('#name').value;
            const email = this.querySelector('#email').value;
            const subject = this.querySelector('#subject').value;
            const message = this.querySelector('#message').value;

            if (!name || !email || !subject || !message) {
                alert('Por favor, rellena todos los campos del formulario.');
                return;
            }

            // Validación de email
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                alert('Por favor, introduce un email válido.');
                return;
            }

            // Mostrar mensaje de carga
            this.querySelector('.loading').style.display = 'block';
            this.querySelector('.error-message').style.display = 'none';
            this.querySelector('.sent-message').style.display = 'none';

            // Simular envío de formulario (en un escenario real, esto sería una llamada AJAX)
            setTimeout(() => {
                this.querySelector('.loading').style.display = 'none';
                this.querySelector('.sent-message').style.display = 'block';
                this.reset();
            }, 2000);
        });
    }

    /**
     * Carrusel de testimonios
     */
    const testimonialCarousel = document.getElementById('testimonialCarousel');
    if (testimonialCarousel) {
        // Inicializar el carrusel de Bootstrap
        const carousel = new bootstrap.Carousel(testimonialCarousel, {
            interval: 5000
        });

        // Añadir event listeners para controles personalizados
        const prevButton = testimonialCarousel.querySelector('.carousel-control-prev');
        const nextButton = testimonialCarousel.querySelector('.carousel-control-next');

        if (prevButton) {
            prevButton.addEventListener('click', function() {
                carousel.prev();
            });
        }

        if (nextButton) {
            nextButton.addEventListener('click', function() {
                carousel.next();
            });
        }
    }

    /**
     * Efecto hover en las tarjetas de servicios
     */
    const serviceCards = document.querySelectorAll('.services .card');
    if (serviceCards.length > 0) {
        serviceCards.forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-10px)';
                this.style.boxShadow = '0px 10px 30px rgba(1, 41, 112, 0.2)';
            });

            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
                this.style.boxShadow = '0px 0 30px rgba(1, 41, 112, 0.1)';
            });
        });
    }

    /**
     * Navegación responsive
     */
    const handleResponsiveNav = () => {
        const width = window.innerWidth;
        const navItems = document.querySelectorAll('.navbar-nav .nav-item');

        if (width < 768) {
            // Para móvil: Añadir clase especial a ciertos elementos de navegación
            navItems.forEach(item => {
                if (item.classList.contains('hide-sm')) {
                    item.style.display = 'none';
                }
            });
        } else {
            // Para escritorio: Restablecer visualización
            navItems.forEach(item => {
                item.style.display = '';
            });
        }
    };

    // Ejecutar al cargar y redimensionar
    window.addEventListener('load', handleResponsiveNav);
    window.addEventListener('resize', handleResponsiveNav);

    /**
     * Persistencia de pestañas en el panel de cliente
     */
    const clientTabs = document.querySelector('#dashboardTabs');
    if (clientTabs) {
        // Obtener la pestaña activa del localStorage
        const activeTab = localStorage.getItem('activeClientTab');

        // Si hay una pestaña activa almacenada, activarla
        if (activeTab) {
            const tab = document.querySelector(`#dashboardTabs button[data-bs-target="${activeTab}"]`);
            if (tab) {
                const tabInstance = new bootstrap.Tab(tab);
                tabInstance.show();
            }
        }

        // Almacenar la pestaña activa cuando se hace clic en una pestaña
        const tabs = document.querySelectorAll('#dashboardTabs button');
        tabs.forEach(tab => {
            tab.addEventListener('shown.bs.tab', function(event) {
                localStorage.setItem('activeClientTab', event.target.getAttribute('data-bs-target'));
            });
        });
    }
});
