document.addEventListener('DOMContentLoaded', function() {
    const isAuthPage = document.getElementById('login-page') || document.getElementById('register-page');
    
    // Initialize all core functionality for ALL pages
    initializeMobileSidebar();
    initializeAjaxForms();
    initializeFloatingLabels();
    initializePasswordStrength();
    initializeSmoothScroll();
    initializeFlashMessages();
    initializeApplications();
    initializeBackToTop();
    
    // Initialize password features
    initializePasswordVisibility();
    initializeEnhancedPasswordValidation();
    
    // Initialize application form
    initializeApplicationForm();
    
    // Initialize auth animations ONLY on auth pages
    if (isAuthPage) {
        setTimeout(() => {
            initializeAuthAnimations();
        }, 100);
    }
});

function initializeAuthAnimations() {
    console.log('Initializing auth animations...');
    
    const isLoginPage = document.getElementById('login-page');
    const isRegisterPage = document.getElementById('register-page');
    
    if (!isLoginPage && !isRegisterPage) {
        console.log('Not on auth page, skipping animations');
        return;
    }

    const authContainer = isLoginPage || isRegisterPage;
    const formCard = authContainer.querySelector('.bg-white.rounded-2xl');
    
    if (!formCard) return;

    // Reset any previous styles
    formCard.style.cssText = 'opacity: 0; transform: translateY(50px) scale(0.95); transition: all 0.8s ease;';

    const formElements = formCard.querySelectorAll('input, button, .flex, .relative, a, .grid, .space-y-6 > *, .space-y-5 > *');
    formElements.forEach(element => {
        element.style.cssText = 'opacity: 0; transform: translateY(20px); transition: all 0.6s ease;';
    });

    setTimeout(() => {
        formCard.style.opacity = '1';
        formCard.style.transform = 'translateY(0) scale(1)';
        
        setTimeout(() => {
            formElements.forEach((element, index) => {
                setTimeout(() => {
                    element.style.opacity = '1';
                    element.style.transform = 'translateY(0)';
                }, index * 60);
            });
        }, 200);
    }, 200);
}

function initializeApplicationForm() {
    const applicationForm = document.getElementById('application-form');
    if (!applicationForm) return;

    console.log('Initializing application form...');

    // File input styling
    const fileInputs = document.querySelectorAll('.hidden-file-input');
    fileInputs.forEach(input => {
        const display = input.nextElementSibling;
        if (!display) return;

        input.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                display.textContent = this.files[0].name;
                display.classList.add('has-file');
            } else {
                display.textContent = 'Файл не выбран';
                display.classList.remove('has-file');
            }
        });
    });

    // Form validation
    applicationForm.addEventListener('submit', function(e) {
        const requiredFields = applicationForm.querySelectorAll('[required]');
        let isValid = true;

        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                field.style.borderColor = '#ef4444';
                isValid = false;
            } else {
                field.style.borderColor = '#d1d5db';
            }
        });

        if (!isValid) {
            e.preventDefault();
            showNotification('Пожалуйста, заполните все обязательные поля', 'error');
        }
    });
}

// Show notification function
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg transition-all duration-300 transform translate-x-full ${
        type === 'error' ? 'bg-red-500 text-white' : 
        type === 'success' ? 'bg-green-500 text-white' : 
        'bg-blue-500 text-white'
    }`;
    notification.textContent = message;

    document.body.appendChild(notification);

    setTimeout(() => notification.style.transform = 'translateX(0)', 100);
    setTimeout(() => {
        notification.style.transform = 'translateX(100%)';
        setTimeout(() => notification.remove(), 300);
    }, 5000);
}

function initializeMobileSidebar() {
    const mobileMenuButton = document.getElementById('mobile-menu-button');
    const closeSidebar = document.getElementById('close-sidebar');
    const sidebarOverlay = document.getElementById('sidebar-overlay');
    const mobileSidebar = document.getElementById('mobile-sidebar');

    if (!mobileMenuButton || !mobileSidebar) return;

    function openSidebar() {
        mobileSidebar.style.transform = 'translateX(0)';
        document.body.style.overflow = 'hidden';
    }

    function closeSidebarFunc() {
        mobileSidebar.style.transform = 'translateX(-100%)';
        document.body.style.overflow = 'auto';
    }

    mobileMenuButton.addEventListener('click', openSidebar);
    if (closeSidebar) closeSidebar.addEventListener('click', closeSidebarFunc);
    if (sidebarOverlay) sidebarOverlay.addEventListener('click', closeSidebarFunc);

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && mobileSidebar.style.transform === 'translateX(0px)') {
            closeSidebarFunc();
        }
    });
}

function initializePasswordVisibility() {
    const passwordInputs = document.querySelectorAll('input[type="password"]');
    
    passwordInputs.forEach(input => {
        const parent = input.parentElement;
        if (!parent || parent.querySelector('.password-toggle')) return;
        
        const toggleButton = document.createElement('button');
        toggleButton.type = 'button';
        toggleButton.className = 'password-toggle absolute inset-y-0 right-0 pr-3 flex items-center';
        toggleButton.innerHTML = `
            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
            </svg>
        `;
        
        toggleButton.addEventListener('click', function() {
            const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
            input.setAttribute('type', type);
            
            this.innerHTML = type === 'text' ? 
                `<svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                </svg>` :
                `<svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>`;
        });
        
        parent.classList.add('relative');
        parent.appendChild(toggleButton);
    });
}

function initializeEnhancedPasswordValidation() {
    const registerForm = document.getElementById('register-form');
    if (!registerForm) return;
    
    const passwordInput = registerForm.querySelector('input[name="RegisterForm[password]"]');
    const passwordRepeatInput = registerForm.querySelector('input[name="RegisterForm[password_repeat]"]');
    
    if (!passwordInput || !passwordRepeatInput) return;
    
    const validationContainer = document.createElement('div');
    validationContainer.className = 'mt-2 space-y-1';
    passwordInput.parentNode.appendChild(validationContainer);
    
    const matchContainer = document.createElement('div');
    matchContainer.className = 'mt-2';
    passwordRepeatInput.parentNode.appendChild(matchContainer);
    
    function validatePassword(password) {
        const requirements = {
            length: password.length >= 6,
            lowercase: /[a-z]/.test(password),
            uppercase: /[A-Z]/.test(password),
            number: /\d/.test(password),
            special: /[^a-zA-Z\d]/.test(password)
        };
        
        const passed = Object.values(requirements).filter(Boolean).length;
        const strength = (passed / Object.keys(requirements).length) * 100;
        
        return { requirements, strength };
    }
    
    function updateValidationUI(password) {
        const { requirements, strength } = validatePassword(password);
        
        let validationHTML = `
            <div class="text-xs font-medium text-gray-700 mb-1">Сложность пароля:</div>
            <div class="w-full bg-gray-200 rounded-full h-2 mb-2">
                <div class="h-2 rounded-full transition-all duration-500 ${ 
                    strength < 40 ? 'bg-red-500' : 
                    strength < 70 ? 'bg-yellow-500' : 'bg-green-500'
                }" style="width: ${strength}%"></div>
            </div>
            <div class="space-y-1 text-xs">
        `;
        
        const requirementText = {
            length: 'Минимум 6 символов',
            lowercase: 'Строчная буква (a-z)',
            uppercase: 'Заглавная буква (A-Z)',
            number: 'Цифра (0-9)',
            special: 'Специальный символ'
        };
        
        Object.keys(requirements).forEach(key => {
            const isValid = requirements[key];
            validationHTML += `
                <div class="flex items-center">
                    <svg class="h-3 w-3 mr-1 ${isValid ? 'text-green-500' : 'text-gray-400'}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="${isValid ? 'M5 13l4 4L19 7' : 'M6 18L18 6M6 6l12 12'}" />
                    </svg>
                    <span class="${isValid ? 'text-green-600' : 'text-gray-500'}">${requirementText[key]}</span>
                </div>
            `;
        });
        
        validationHTML += '</div>';
        validationContainer.innerHTML = validationHTML;
    }
    
    function checkPasswordMatch() {
        const password = passwordInput.value;
        const passwordRepeat = passwordRepeatInput.value;
        
        if (!passwordRepeat) {
            matchContainer.innerHTML = '';
            passwordRepeatInput.classList.remove('border-red-500', 'border-green-500');
            return;
        }
        
        if (password === passwordRepeat && password.length > 0) {
            matchContainer.innerHTML = `
                <div class="flex items-center text-green-600 text-xs">
                    <svg class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Пароли совпадают
                </div>
            `;
            passwordRepeatInput.classList.remove('border-red-500');
            passwordRepeatInput.classList.add('border-green-500');
        } else {
            matchContainer.innerHTML = `
                <div class="flex items-center text-red-600 text-xs">
                    <svg class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    Пароли не совпадают
                </div>
            `;
            passwordRepeatInput.classList.remove('border-green-500');
            passwordRepeatInput.classList.add('border-red-500');
        }
    }
    
    passwordInput.addEventListener('input', function() {
        updateValidationUI(this.value);
        checkPasswordMatch();
    });
    
    passwordRepeatInput.addEventListener('input', checkPasswordMatch);
}

function initializeAjaxForms() {
    function setupAjaxForm(formId) {
        const form = document.getElementById(formId);
        if (!form) return;

        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const submitButton = form.querySelector('button[type="submit"]');
            const originalText = submitButton.textContent;
            
            submitButton.disabled = true;
            submitButton.textContent = formId === 'login-form' ? 'Вход...' : 'Регистрация...';
            
            const formData = new FormData(form);
            
            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.text())
            .then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const newForm = doc.getElementById(formId);
                
                if (newForm) {
                    form.innerHTML = newForm.innerHTML;
                    setupAjaxForm(formId);
                } else {
                    window.location.reload();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Произошла ошибка при отправке формы');
            })
            .finally(() => {
                submitButton.disabled = false;
                submitButton.textContent = originalText;
            });
        });
    }

    if (document.getElementById('login-form')) setupAjaxForm('login-form');
    if (document.getElementById('register-form')) setupAjaxForm('register-form');
}

function initializeFloatingLabels() {
    const inputs = document.querySelectorAll('input[type="text"], input[type="email"], input[type="password"]');
    if (inputs.length === 0) return;
    
    inputs.forEach(input => {
        const parent = input.parentElement;
        
        function checkValue() {
            if (input.value) {
                parent.classList.add('has-value');
            } else {
                parent.classList.remove('has-value');
            }
        }
        
        input.addEventListener('focus', function() {
            parent.classList.add('focused');
        });
        
        input.addEventListener('blur', function() {
            parent.classList.remove('focused');
            checkValue();
        });
        
        checkValue();
    });
}

function initializePasswordStrength() {
    const passwordInputs = document.querySelectorAll('input[type="password"]');
    if (passwordInputs.length === 0) return;
    
    passwordInputs.forEach(input => {
        input.addEventListener('input', function() {
            const strengthIndicator = document.getElementById('password-strength');
            if (!strengthIndicator) return;
            
            const password = input.value;
            let strength = 0;
            
            if (password.length >= 6) strength += 25;
            if (password.match(/[a-z]/) && password.match(/[A-Z]/)) strength += 25;
            if (password.match(/\d/)) strength += 25;
            if (password.match(/[^a-zA-Z\d]/)) strength += 25;
            
            strengthIndicator.style.width = strength + '%';
            strengthIndicator.className = 'h-1 rounded-full transition-all duration-300 ';
            
            if (strength < 50) {
                strengthIndicator.classList.add('bg-red-500');
            } else if (strength < 75) {
                strengthIndicator.classList.add('bg-yellow-500');
            } else {
                strengthIndicator.classList.add('bg-green-500');
            }
        });
    });
}

function initializeSmoothScroll() {
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
}

function initializeFlashMessages() {
    const flashMessages = document.querySelectorAll('.bg-green-50, .bg-red-50');
    if (flashMessages.length === 0) return;
    
    flashMessages.forEach(message => {
        setTimeout(() => {
            message.style.opacity = '0';
            message.style.transition = 'opacity 0.5s ease';
            setTimeout(() => message.remove(), 500);
        }, 5000);
    });
}

function initializeApplications() {
    const applicationsPage = document.querySelector('body')?.classList.contains('applications-page') || 
                           window.location.pathname.includes('application');
    
    if (!applicationsPage) return;

    initializeFilters();
    initializeCardInteractions();
}

function initializeFilters() {
    const filterToggle = document.getElementById('filter-toggle');
    const filterContent = document.getElementById('filter-content');
    const filterShow = document.querySelector('.filter-show');
    const filterHide = document.querySelector('.filter-hide');
    
    if (!filterToggle || !filterContent) return;
    
    const urlParams = new URLSearchParams(window.location.search);
    const hasActiveFilters = Array.from(urlParams.entries()).some(([key, value]) => 
        key.includes('ApplicationSearch') && value !== ''
    );
    
    if (hasActiveFilters) {
        filterContent.classList.remove('hidden');
        if (filterShow) filterShow.classList.add('hidden');
        if (filterHide) filterHide.classList.remove('hidden');
    }
    
    filterToggle.addEventListener('click', function() {
        const isHidden = filterContent.classList.contains('hidden');
        filterContent.classList.toggle('hidden');
        if (filterShow) filterShow.classList.toggle('hidden');
        if (filterHide) filterHide.classList.toggle('hidden');
    });
}

function initializeCardInteractions() {
    const cards = document.querySelectorAll('.application-card, .bg-white.rounded-2xl');
    if (cards.length === 0) return;
    
    cards.forEach(card => {
        card.addEventListener('click', function(e) {
            if (e.target.tagName === 'A' || e.target.closest('a')) return;
            
            const viewLink = this.querySelector('a[href*="view"]');
            if (viewLink) window.location.href = viewLink.href;
        });
    });
}

function initializeBackToTop() {
    const backToTopButton = document.getElementById('back-to-top');
    if (!backToTopButton) return;
    
    function handleScroll() {
        if (window.pageYOffset > 300) {
            backToTopButton.classList.remove('opacity-0', 'invisible');
            backToTopButton.classList.add('opacity-100', 'visible');
        } else {
            backToTopButton.classList.remove('opacity-100', 'visible');
            backToTopButton.classList.add('opacity-0', 'invisible');
        }
    }
    
    window.addEventListener('scroll', handleScroll);
    backToTopButton.addEventListener('click', (e) => {
        e.preventDefault();
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });
    
    backToTopButton.style.transition = 'all 0.3s ease-in-out';
    handleScroll();
}