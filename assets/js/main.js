/**
 * Water Prime Su Arıtma - Main JavaScript
 */

document.addEventListener('DOMContentLoaded', function() {
    // Initialize all components
    initStickyHeader();
    initSmoothScroll();
    initLazyLoading();
    initFormValidation();
    initPhoneTracking();
});

/**
 * Sticky Header Shadow on Scroll
 */
function initStickyHeader() {
    const header = document.querySelector('header');
    if (!header) return;

    let lastScroll = 0;

    window.addEventListener('scroll', () => {
        const currentScroll = window.pageYOffset;

        // Add shadow when scrolled
        if (currentScroll > 50) {
            header.classList.add('scrolled');
        } else {
            header.classList.remove('scrolled');
        }

        // Hide/show header on scroll direction (optional - currently disabled)
        // if (currentScroll > lastScroll && currentScroll > 100) {
        //     header.style.transform = 'translateY(-100%)';
        // } else {
        //     header.style.transform = 'translateY(0)';
        // }

        lastScroll = currentScroll;
    });
}

/**
 * Smooth Scroll for Anchor Links
 */
function initSmoothScroll() {
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            const href = this.getAttribute('href');
            if (href === '#') return;

            e.preventDefault();
            const target = document.querySelector(href);

            if (target) {
                const headerOffset = 100;
                const elementPosition = target.getBoundingClientRect().top;
                const offsetPosition = elementPosition + window.pageYOffset - headerOffset;

                window.scrollTo({
                    top: offsetPosition,
                    behavior: 'smooth'
                });
            }
        });
    });
}

/**
 * Lazy Loading for Images
 */
function initLazyLoading() {
    // Check for native lazy loading support
    if ('loading' in HTMLImageElement.prototype) {
        const images = document.querySelectorAll('img[loading="lazy"]');
        images.forEach(img => {
            if (img.dataset.src) {
                img.src = img.dataset.src;
            }
        });
    } else {
        // Fallback for older browsers using Intersection Observer
        const lazyImages = document.querySelectorAll('img[data-src]');

        if ('IntersectionObserver' in window) {
            const imageObserver = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        img.src = img.dataset.src;
                        img.removeAttribute('data-src');
                        observer.unobserve(img);
                    }
                });
            }, {
                rootMargin: '50px 0px'
            });

            lazyImages.forEach(img => imageObserver.observe(img));
        } else {
            // Fallback for browsers without Intersection Observer
            lazyImages.forEach(img => {
                img.src = img.dataset.src;
            });
        }
    }
}

/**
 * Form Validation
 */
function initFormValidation() {
    const forms = document.querySelectorAll('form[data-validate]');

    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            let isValid = true;

            // Check required fields
            const requiredFields = form.querySelectorAll('[required]');
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    isValid = false;
                    showFieldError(field, 'Bu alan zorunludur');
                } else {
                    clearFieldError(field);
                }
            });

            // Validate email fields
            const emailFields = form.querySelectorAll('input[type="email"]');
            emailFields.forEach(field => {
                if (field.value && !isValidEmail(field.value)) {
                    isValid = false;
                    showFieldError(field, 'Geçerli bir e-posta adresi giriniz');
                }
            });

            // Validate phone fields
            const phoneFields = form.querySelectorAll('input[type="tel"]');
            phoneFields.forEach(field => {
                if (field.value && !isValidPhone(field.value)) {
                    isValid = false;
                    showFieldError(field, 'Geçerli bir telefon numarası giriniz');
                }
            });

            if (!isValid) {
                e.preventDefault();
            }
        });

        // Clear errors on input
        form.querySelectorAll('input, textarea').forEach(field => {
            field.addEventListener('input', () => clearFieldError(field));
        });
    });
}

function showFieldError(field, message) {
    field.classList.add('border-red-500');

    // Remove existing error message
    const existingError = field.parentNode.querySelector('.field-error');
    if (existingError) existingError.remove();

    // Add error message
    const errorEl = document.createElement('p');
    errorEl.className = 'field-error text-red-500 text-sm mt-1';
    errorEl.textContent = message;
    field.parentNode.appendChild(errorEl);
}

function clearFieldError(field) {
    field.classList.remove('border-red-500');
    const errorEl = field.parentNode.querySelector('.field-error');
    if (errorEl) errorEl.remove();
}

function isValidEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(email);
}

function isValidPhone(phone) {
    // Turkish phone number validation
    const cleaned = phone.replace(/\D/g, '');
    return cleaned.length >= 10 && cleaned.length <= 12;
}

/**
 * Phone Click Tracking (for Google Ads conversion)
 */
function initPhoneTracking() {
    const phoneLinks = document.querySelectorAll('a[href^="tel:"]');

    phoneLinks.forEach(link => {
        link.addEventListener('click', function() {
            // Track phone click event
            if (typeof gtag !== 'undefined') {
                gtag('event', 'click', {
                    'event_category': 'Contact',
                    'event_label': 'Phone Click',
                    'value': 1
                });
            }

            // Also track for Facebook Pixel if exists
            if (typeof fbq !== 'undefined') {
                fbq('track', 'Contact', {
                    content_name: 'Phone Click'
                });
            }
        });
    });

    // WhatsApp click tracking
    const whatsappLinks = document.querySelectorAll('a[href*="wa.me"]');

    whatsappLinks.forEach(link => {
        link.addEventListener('click', function() {
            if (typeof gtag !== 'undefined') {
                gtag('event', 'click', {
                    'event_category': 'Contact',
                    'event_label': 'WhatsApp Click',
                    'value': 1
                });
            }

            if (typeof fbq !== 'undefined') {
                fbq('track', 'Contact', {
                    content_name: 'WhatsApp Click'
                });
            }
        });
    });
}

/**
 * AJAX Form Submit
 */
function submitFormAjax(form, successCallback, errorCallback) {
    const formData = new FormData(form);
    const submitBtn = form.querySelector('button[type="submit"]');

    // Add loading state
    if (submitBtn) {
        submitBtn.classList.add('btn-loading');
        submitBtn.disabled = true;
    }

    fetch(form.action, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            if (successCallback) successCallback(data);
        } else {
            if (errorCallback) errorCallback(data);
        }
    })
    .catch(error => {
        console.error('Form submit error:', error);
        if (errorCallback) errorCallback({ message: 'Bir hata oluştu. Lütfen tekrar deneyin.' });
    })
    .finally(() => {
        // Remove loading state
        if (submitBtn) {
            submitBtn.classList.remove('btn-loading');
            submitBtn.disabled = false;
        }
    });
}

/**
 * Show Toast Notification
 */
function showToast(message, type = 'success') {
    // Remove existing toast
    const existingToast = document.querySelector('.toast-notification');
    if (existingToast) existingToast.remove();

    // Create toast element
    const toast = document.createElement('div');
    toast.className = `toast-notification fixed bottom-24 right-6 z-50 px-6 py-4 rounded-xl shadow-lg transform transition-all duration-300 translate-y-full opacity-0`;

    if (type === 'success') {
        toast.classList.add('bg-green-500', 'text-white');
    } else if (type === 'error') {
        toast.classList.add('bg-red-500', 'text-white');
    } else {
        toast.classList.add('bg-gray-800', 'text-white');
    }

    toast.textContent = message;
    document.body.appendChild(toast);

    // Animate in
    requestAnimationFrame(() => {
        toast.classList.remove('translate-y-full', 'opacity-0');
    });

    // Auto remove after 5 seconds
    setTimeout(() => {
        toast.classList.add('translate-y-full', 'opacity-0');
        setTimeout(() => toast.remove(), 300);
    }, 5000);
}

/**
 * Format Phone Number as User Types
 */
function formatPhoneNumber(input) {
    let value = input.value.replace(/\D/g, '');

    if (value.length > 0) {
        if (value.startsWith('90')) {
            value = value.substring(2);
        }
        if (value.startsWith('0')) {
            value = value.substring(1);
        }

        // Format: 5XX XXX XX XX
        if (value.length <= 3) {
            input.value = value;
        } else if (value.length <= 6) {
            input.value = value.slice(0, 3) + ' ' + value.slice(3);
        } else if (value.length <= 8) {
            input.value = value.slice(0, 3) + ' ' + value.slice(3, 6) + ' ' + value.slice(6);
        } else {
            input.value = value.slice(0, 3) + ' ' + value.slice(3, 6) + ' ' + value.slice(6, 8) + ' ' + value.slice(8, 10);
        }
    }
}

// Make functions globally available
window.submitFormAjax = submitFormAjax;
window.showToast = showToast;
window.formatPhoneNumber = formatPhoneNumber;
