// admin.js - Modern Admin Panel Protection
class AdminProtection {
    constructor() {
        this.init();
    }

    init() {
        console.log('🛡️ Admin panel protection initialized');
        
        // Отключаем основной script.js если он был загружен
        this.disableMainScript();
        
        // Защищаем админ-панель
        this.setupProtection();
        
        // Инициализируем функциональность
        this.initializeFunctions();
    }

    disableMainScript() {
        // Находим и отключаем все скрипты script.js
        const scripts = document.querySelectorAll('script[src*="script.js"]');
        scripts.forEach(script => {
            console.log('🚫 Disabling script.js for admin panel');
            script.remove();
        });

        // Также удаляем любые обработчики из script.js
        this.cleanupMainScriptHandlers();
    }

    cleanupMainScriptHandlers() {
        // Клонируем и заменяем body чтобы сбросить все обработчики
        const body = document.body;
        const newBody = body.cloneNode(true);
        body.parentNode.replaceChild(newBody, body);
        
        // Переинициализируем DOMContentLoaded для admin.js
        document.addEventListener('DOMContentLoaded', () => {
            this.setupProtection();
            this.initializeFunctions();
        });
    }

    setupProtection() {
        this.disableProblematicScripts();
        this.protectAdminDOM();
        this.startEmergencyProtection();
    }

    disableProblematicScripts() {
        // Блокируем удаление админ-элементов
        const originalRemoveChild = Node.prototype.removeChild;
        Node.prototype.removeChild = function(child) {
            if (this.isAdminElement(child)) {
                console.warn('Blocked removal of admin element');
                return child;
            }
            return originalRemoveChild.call(this, child);
        }.bind(this);

        const originalRemove = Element.prototype.remove;
        Element.prototype.remove = function() {
            if (this.isAdminElement(this)) {
                console.warn('Blocked removal of admin element');
                return;
            }
            return originalRemove.call(this);
        }.bind(this);
    }

    isAdminElement(element) {
        if (!element || !element.tagName) return false;
        
        const adminSelectors = [
            'a[href*="create"]', 'a[href*="update"]', 'a[href*="delete"]',
            'a[href*="block"]', 'a[href*="unblock"]', 'button[type="submit"]',
            '.bg-white.rounded-2xl', '.bg-gradient-to-r', '[class*="shadow"]'
        ];
        
        return adminSelectors.some(selector => 
            element.matches?.(selector) || element.closest?.(selector)
        );
    }

    protectAdminDOM() {
        // Резервное копирование админ-панели
        const adminPanel = document.querySelector('.max-w-7xl.mx-auto');
        if (!adminPanel) return;
        
        const backupHTML = adminPanel.innerHTML;
        
        // Мониторинг изменений
        setInterval(() => {
            const currentHTML = adminPanel.innerHTML;
            if (currentHTML !== backupHTML && currentHTML.length < backupHTML.length * 0.8) {
                console.warn('Admin panel content was modified, restoring...');
                adminPanel.innerHTML = backupHTML;
                setTimeout(() => this.initializeFunctions(), 100);
            }
        }, 500);
    }

    initializeFunctions() {
        this.initializeMobileSidebar();
        this.initializeSmoothScroll();
        this.initializeBackToTop();
        this.initializeAdminButtonHandlers();
        
        console.log('✅ Admin functions initialized');
    }

    initializeAdminButtonHandlers() {
        // Обработчики подтверждения для опасных действий
        document.querySelectorAll('a[href*="delete"], a[href*="block"]').forEach(link => {
            link.addEventListener('click', function(e) {
                const message = this.getAttribute('data-confirm');
                if (message && !confirm(message)) {
                    e.preventDefault();
                    e.stopPropagation();
                }
            });
        });
    }

    initializeMobileSidebar() {
        const mobileMenuButton = document.getElementById('mobile-menu-button');
        const mobileSidebar = document.getElementById('mobile-sidebar');

        if (!mobileMenuButton || !mobileSidebar) return;

        mobileMenuButton.addEventListener('click', () => {
            mobileSidebar.style.transform = 'translateX(0)';
            document.body.style.overflow = 'hidden';
        });

        document.addEventListener('click', (e) => {
            if (e.target.id === 'close-sidebar' || 
                e.target.id === 'sidebar-overlay' ||
                (e.key === 'Escape' && mobileSidebar.style.transform === 'translateX(0px)')) {
                mobileSidebar.style.transform = 'translateX(-100%)';
                document.body.style.overflow = 'auto';
            }
        });
    }

    initializeSmoothScroll() {
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            });
        });
    }

    initializeBackToTop() {
        const backToTopButton = document.getElementById('back-to-top');
        if (!backToTopButton) return;
        
        const handleScroll = () => {
            if (window.pageYOffset > 300) {
                backToTopButton.classList.remove('opacity-0', 'invisible');
                backToTopButton.classList.add('opacity-100', 'visible');
            } else {
                backToTopButton.classList.remove('opacity-100', 'visible');
                backToTopButton.classList.add('opacity-0', 'invisible');
            }
        };
        
        window.addEventListener('scroll', handleScroll);
        backToTopButton.addEventListener('click', (e) => {
            e.preventDefault();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
        
        handleScroll();
    }

    startEmergencyProtection() {
        setInterval(() => {
            const adminButtons = document.querySelectorAll('a[href*="create"], a[href*="update"], a[href*="delete"]');
            const visibleButtons = Array.from(adminButtons).filter(btn => {
                const style = window.getComputedStyle(btn);
                return style.display !== 'none' && style.visibility !== 'hidden';
            });
            
            if (visibleButtons.length === 0 && adminButtons.length > 0) {
                console.error('EMERGENCY: Admin buttons missing, reloading...');
                window.location.reload();
            }
        }, 2000);
    }
}

// Modern module initialization
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => new AdminProtection());
} else {
    new AdminProtection();
}