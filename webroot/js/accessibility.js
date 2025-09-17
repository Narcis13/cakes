/**
 * Accessibility Widget for Hospital Management System
 * Provides various accessibility options for users
 */

class AccessibilityWidget {
    constructor() {
        this.isOpen = false;
        this.settings = this.loadSettings();
        this.init();
    }

    init() {
        this.createWidget();
        this.bindEvents();
        this.applySettings();
    }

    createWidget() {
        const widget = document.createElement('div');
        widget.className = 'accessibility-widget';
        widget.innerHTML = `
            <button class="accessibility-toggle" id="accessibilityToggle" title="Instrumente de accesibilitate">
                <i class="fas fa-universal-access"></i>
            </button>
            <div class="accessibility-panel" id="accessibilityPanel">
                <div class="accessibility-header">
                    <i class="fas fa-universal-access"></i>
                    <span>Instrumente de accesibilitate</span>
                </div>
                <div class="accessibility-content">
                    <div class="accessibility-option" data-action="increase-text">
                        <i class="fas fa-search-plus"></i>
                        <span>Mărește textul</span>
                    </div>
                    <div class="accessibility-option" data-action="decrease-text">
                        <i class="fas fa-search-minus"></i>
                        <span>Micșorează textul</span>
                    </div>
                    <div class="accessibility-option" data-action="grayscale">
                        <i class="fas fa-palette"></i>
                        <span>Tonuri de gri</span>
                    </div>
                    <div class="accessibility-option" data-action="high-contrast">
                        <i class="fas fa-adjust"></i>
                        <span>Contrast mare</span>
                    </div>
                    <div class="accessibility-option" data-action="negative-contrast">
                        <i class="fas fa-eye"></i>
                        <span>Contrast negativ</span>
                    </div>
                    <div class="accessibility-option" data-action="bright-background">
                        <i class="fas fa-lightbulb"></i>
                        <span>Fundal luminos</span>
                    </div>
                    <div class="accessibility-option" data-action="underlined-links">
                        <i class="fas fa-link"></i>
                        <span>Legături subliniate</span>
                    </div>
                    <div class="accessibility-option" data-action="readable-font">
                        <i class="fas fa-font"></i>
                        <span>Font lizibil</span>
                    </div>
                    <div class="accessibility-option" data-action="reset">
                        <i class="fas fa-undo"></i>
                        <span>Resetează</span>
                    </div>
                </div>
            </div>
        `;

        document.body.appendChild(widget);
    }

    bindEvents() {
        const toggle = document.getElementById('accessibilityToggle');
        const panel = document.getElementById('accessibilityPanel');
        const options = document.querySelectorAll('.accessibility-option');

        // Toggle panel visibility
        toggle.addEventListener('click', (e) => {
            e.stopPropagation();
            this.togglePanel();
        });

        // Handle option clicks
        options.forEach(option => {
            option.addEventListener('click', (e) => {
                e.stopPropagation();
                const action = option.dataset.action;
                this.handleAction(action, option);
            });
        });

        // Close panel when clicking outside
        document.addEventListener('click', (e) => {
            if (!e.target.closest('.accessibility-widget')) {
                this.closePanel();
            }
        });

        // Handle escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && this.isOpen) {
                this.closePanel();
            }
        });
    }

    togglePanel() {
        const panel = document.getElementById('accessibilityPanel');
        if (this.isOpen) {
            this.closePanel();
        } else {
            panel.classList.add('show');
            this.isOpen = true;
        }
    }

    closePanel() {
        const panel = document.getElementById('accessibilityPanel');
        panel.classList.remove('show');
        this.isOpen = false;
    }

    handleAction(action, optionElement) {
        switch (action) {
            case 'increase-text':
                this.toggleTextSize('large');
                break;
            case 'decrease-text':
                this.toggleTextSize('small');
                break;
            case 'grayscale':
                this.toggleFeature('grayscale', optionElement);
                break;
            case 'high-contrast':
                this.toggleFeature('high-contrast', optionElement);
                break;
            case 'negative-contrast':
                this.toggleFeature('negative-contrast', optionElement);
                break;
            case 'bright-background':
                this.toggleFeature('bright-background', optionElement);
                break;
            case 'underlined-links':
                this.toggleFeature('underlined-links', optionElement);
                break;
            case 'readable-font':
                this.toggleFeature('readable-font', optionElement);
                break;
            case 'reset':
                this.resetAll();
                break;
        }
        this.saveSettings();
    }

    toggleTextSize(size) {
        const body = document.body;
        const largeOption = document.querySelector('[data-action="increase-text"]');
        const smallOption = document.querySelector('[data-action="decrease-text"]');
        
        if (size === 'large') {
            if (body.classList.contains('large-text')) {
                body.classList.remove('large-text');
                largeOption.classList.remove('active');
                this.settings.textSize = 'normal';
            } else {
                body.classList.remove('small-text');
                body.classList.add('large-text');
                largeOption.classList.add('active');
                smallOption.classList.remove('active');
                this.settings.textSize = 'large';
            }
        } else if (size === 'small') {
            if (body.classList.contains('small-text')) {
                body.classList.remove('small-text');
                smallOption.classList.remove('active');
                this.settings.textSize = 'normal';
            } else {
                body.classList.remove('large-text');
                body.classList.add('small-text');
                smallOption.classList.add('active');
                largeOption.classList.remove('active');
                this.settings.textSize = 'small';
            }
        }
    }

    toggleFeature(className, optionElement) {
        const body = document.body;
        const isActive = body.classList.contains(className);
        
        if (isActive) {
            body.classList.remove(className);
            optionElement.classList.remove('active');
            this.settings[className] = false;
        } else {
            body.classList.add(className);
            optionElement.classList.add('active');
            this.settings[className] = true;
        }
    }

    resetAll() {
        const body = document.body;
        const options = document.querySelectorAll('.accessibility-option');
        
        // Remove all accessibility classes
        const classes = ['large-text', 'small-text', 'grayscale', 'high-contrast', 'negative-contrast', 'bright-background', 'underlined-links', 'readable-font'];
        classes.forEach(cls => body.classList.remove(cls));
        
        // Remove active state from all options
        options.forEach(option => option.classList.remove('active'));
        
        // Reset settings
        this.settings = {};
        this.saveSettings();
    }

    saveSettings() {
        localStorage.setItem('accessibilitySettings', JSON.stringify(this.settings));
    }

    loadSettings() {
        const saved = localStorage.getItem('accessibilitySettings');
        return saved ? JSON.parse(saved) : {};
    }

    applySettings() {
        // Wait for DOM elements to be available
        setTimeout(() => {
            const body = document.body;
            
            // Apply text size
            if (this.settings.textSize === 'large') {
                body.classList.add('large-text');
                const option = document.querySelector('[data-action="increase-text"]');
                if (option) option.classList.add('active');
            } else if (this.settings.textSize === 'small') {
                body.classList.add('small-text');
                const option = document.querySelector('[data-action="decrease-text"]');
                if (option) option.classList.add('active');
            }
            
            // Apply other features
            const features = ['grayscale', 'high-contrast', 'negative-contrast', 'bright-background', 'underlined-links', 'readable-font'];
            features.forEach(feature => {
                if (this.settings[feature]) {
                    body.classList.add(feature);
                    const option = document.querySelector(`[data-action="${feature}"]`);
                    if (option) {
                        option.classList.add('active');
                    }
                }
            });
        }, 100);
    }
}

// Initialize the accessibility widget when DOM is loaded
function initAccessibilityWidget() {
    if (document.getElementById('accessibilityToggle')) {
        return; // Already initialized
    }
    new AccessibilityWidget();
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initAccessibilityWidget);
} else {
    initAccessibilityWidget();
}