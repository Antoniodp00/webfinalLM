/**
 * Fitness360 - Main JavaScript File
 * This file contains all the JavaScript functionality for the Fitness360 website
 */

document.addEventListener('DOMContentLoaded', function() {
    'use strict';

    /**
     * Easy selector helper function
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
     * Easy event listener function
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
     * Easy on scroll event listener 
     */
    const onscroll = (el, listener) => {
        el.addEventListener('scroll', listener);
    };

    /**
     * Navbar links active state on scroll
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
     * Scrolls to an element with header offset
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
     * Toggle .header-scrolled class to header when page is scrolled
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
     * Back to top button
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
     * Mobile nav toggle
     */
    on('click', '.mobile-nav-toggle', function(e) {
        select('body').classList.toggle('mobile-nav-active');
        this.classList.toggle('bi-list');
        this.classList.toggle('bi-x');
    });

    /**
     * Scroll with offset on links with a class name .scrollto
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
     * Scroll with offset on page load with hash links in the url
     */
    window.addEventListener('load', () => {
        if (window.location.hash) {
            if (select(window.location.hash)) {
                scrollto(window.location.hash);
            }
        }
    });

    /**
     * Animation on scroll
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
     * Form validation and submission
     */
    const contactForm = document.querySelector('.php-email-form');
    if (contactForm) {
        contactForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Simple validation
            const name = this.querySelector('#name').value;
            const email = this.querySelector('#email').value;
            const subject = this.querySelector('#subject').value;
            const message = this.querySelector('#message').value;
            
            if (!name || !email || !subject || !message) {
                alert('Por favor, rellena todos los campos del formulario.');
                return;
            }
            
            // Email validation
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                alert('Por favor, introduce un email válido.');
                return;
            }
            
            // Show loading message
            this.querySelector('.loading').style.display = 'block';
            this.querySelector('.error-message').style.display = 'none';
            this.querySelector('.sent-message').style.display = 'none';
            
            // Simulate form submission (in a real scenario, this would be an AJAX call)
            setTimeout(() => {
                this.querySelector('.loading').style.display = 'none';
                this.querySelector('.sent-message').style.display = 'block';
                this.reset();
            }, 2000);
        });
    }

    /**
     * Testimonial carousel
     */
    const testimonialCarousel = document.getElementById('testimonialCarousel');
    if (testimonialCarousel) {
        // Initialize the Bootstrap carousel
        const carousel = new bootstrap.Carousel(testimonialCarousel, {
            interval: 5000
        });
        
        // Add event listeners for custom controls
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
     * Service card hover effect
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
     * Responsive navigation
     */
    const handleResponsiveNav = () => {
        const width = window.innerWidth;
        const navItems = document.querySelectorAll('.navbar-nav .nav-item');
        
        if (width < 768) {
            // For mobile: Add special class to certain nav items
            navItems.forEach(item => {
                if (item.classList.contains('hide-sm')) {
                    item.style.display = 'none';
                }
            });
        } else {
            // For desktop: Reset display
            navItems.forEach(item => {
                item.style.display = '';
            });
        }
    };
    
    // Run on load and resize
    window.addEventListener('load', handleResponsiveNav);
    window.addEventListener('resize', handleResponsiveNav);
});