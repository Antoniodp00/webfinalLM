/**
 * Fitness360 - Archivo JavaScript Principal
 * Este archivo contiene las funcionalidades JavaScript esenciales para el sitio web Fitness360
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
     * Botón para volver arriba
     */
    let backtotop = select('.volver-arriba');
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
     * Paginación de servicios
     */
    const initServicesPagination = () => {
        const paginationContainer = select('#servicios-pagination');
        if (!paginationContainer) return;

        const serviceCards = select('.servicio-card', true);
        const pageLinks = select('#servicios-pagination li[data-page]', true);
        const prevButton = select('#prev-page');
        const nextButton = select('#next-page');
        let currentPage = 1;
        const totalPages = pageLinks.length;

        // Función para mostrar una página específica
        const showPage = (pageNumber) => {
            // Ocultar todas las tarjetas
            serviceCards.forEach(card => {
                card.style.display = 'none';
            });

            // Mostrar solo las tarjetas de la página actual
            serviceCards.forEach(card => {
                if (parseInt(card.dataset.page) === pageNumber) {
                    card.style.display = '';
                }
            });

            // Actualizar la clase active en los enlaces de paginación
            pageLinks.forEach(link => {
                if (parseInt(link.dataset.page) === pageNumber) {
                    link.classList.add('active');
                    link.setAttribute('aria-current', 'page');
                } else {
                    link.classList.remove('active');
                    link.removeAttribute('aria-current');
                }
            });

            // Actualizar el estado de los botones anterior/siguiente
            if (pageNumber === 1) {
                prevButton.classList.add('disabled');
                prevButton.querySelector('a').setAttribute('aria-disabled', 'true');
            } else {
                prevButton.classList.remove('disabled');
                prevButton.querySelector('a').removeAttribute('aria-disabled');
            }

            if (pageNumber === totalPages) {
                nextButton.classList.add('disabled');
                nextButton.querySelector('a').setAttribute('aria-disabled', 'true');
            } else {
                nextButton.classList.remove('disabled');
                nextButton.querySelector('a').removeAttribute('aria-disabled');
            }

            // Actualizar la página actual
            currentPage = pageNumber;
        };

        // Añadir event listeners a los enlaces de paginación
        pageLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const pageNumber = parseInt(this.dataset.page);
                showPage(pageNumber);
            });
        });

        // Añadir event listeners a los botones anterior/siguiente
        if (prevButton) {
            prevButton.addEventListener('click', function(e) {
                e.preventDefault();
                if (currentPage > 1) {
                    showPage(currentPage - 1);
                }
            });
        }

        if (nextButton) {
            nextButton.addEventListener('click', function(e) {
                e.preventDefault();
                if (currentPage < totalPages) {
                    showPage(currentPage + 1);
                }
            });
        }

        // Mostrar la primera página al cargar
        showPage(1);
    };

    // Inicializar la paginación de servicios
    initServicesPagination();

    /**
     * Persistencia de pestañas en el panel de cliente
     */
    const clientTabs = document.querySelector('#pestanasPanelControl');
    if (clientTabs) {
        // Obtener la pestaña activa del localStorage
        const activeTab = localStorage.getItem('activeClientTab');

        // Si hay una pestaña activa almacenada, activarla
        if (activeTab) {
            const tab = document.querySelector(`#pestanasPanelControl button[data-bs-target="${activeTab}"]`);
            if (tab) {
                const tabInstance = new bootstrap.Tab(tab);
                tabInstance.show();
            }
        }

        // Almacenar la pestaña activa cuando se hace clic en una pestaña
        const tabs = document.querySelectorAll('#pestanasPanelControl button');
        tabs.forEach(tab => {
            tab.addEventListener('shown.bs.tab', function(event) {
                localStorage.setItem('activeClientTab', event.target.getAttribute('data-bs-target'));
            });
        });
    }

    /**
     * Function to show "Coming Soon" message for features in development
     */
    window.showComingSoon = function() {
        alert('Esta funcionalidad estará disponible próximamente.');
    };
});
