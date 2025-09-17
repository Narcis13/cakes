/**
 * Simple Accessibility Widget - Debug Version
 */

console.log('Accessibility script loading...');

function createAccessibilityWidget() {
    console.log('Creating accessibility widget...');
    
    // Check if widget already exists
    if (document.getElementById('accessibilityToggle')) {
        console.log('Widget already exists');
        return;
    }
    
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
    console.log('Widget HTML added to body');
    
    // Add event listeners
    const toggle = document.getElementById('accessibilityToggle');
    const panel = document.getElementById('accessibilityPanel');
    
    if (toggle) {
        console.log('Toggle button found, adding click listener');
        toggle.addEventListener('click', function(e) {
            console.log('Toggle button clicked!');
            e.preventDefault();
            e.stopPropagation();
            
            if (panel.classList.contains('show')) {
                panel.classList.remove('show');
                console.log('Panel hidden');
            } else {
                panel.classList.add('show');
                console.log('Panel shown');
            }
        });
    } else {
        console.error('Toggle button not found!');
    }
    
    // Add option click handlers
    const options = document.querySelectorAll('.accessibility-option');
    console.log('Found', options.length, 'accessibility options');
    
    options.forEach(option => {
        option.addEventListener('click', function(e) {
            const action = this.dataset.action;
            console.log('Option clicked:', action);
            
            const body = document.body;
            
            switch(action) {
                case 'increase-text':
                    body.classList.toggle('large-text');
                    body.classList.remove('small-text');
                    this.classList.toggle('active');
                    document.querySelector('[data-action="decrease-text"]').classList.remove('active');
                    console.log('Large text toggled');
                    break;
                    
                case 'decrease-text':
                    body.classList.toggle('small-text');
                    body.classList.remove('large-text');
                    this.classList.toggle('active');
                    document.querySelector('[data-action="increase-text"]').classList.remove('active');
                    console.log('Small text toggled');
                    break;
                    
                case 'grayscale':
                    body.classList.toggle('grayscale');
                    this.classList.toggle('active');
                    console.log('Grayscale toggled');
                    break;
                    
                case 'high-contrast':
                    body.classList.toggle('high-contrast');
                    this.classList.toggle('active');
                    console.log('High contrast toggled');
                    break;
                    
                case 'negative-contrast':
                    body.classList.toggle('negative-contrast');
                    this.classList.toggle('active');
                    console.log('Negative contrast toggled');
                    break;
                    
                case 'bright-background':
                    body.classList.toggle('bright-background');
                    this.classList.toggle('active');
                    console.log('Bright background toggled');
                    break;
                    
                case 'underlined-links':
                    body.classList.toggle('underlined-links');
                    this.classList.toggle('active');
                    console.log('Underlined links toggled');
                    break;
                    
                case 'readable-font':
                    body.classList.toggle('readable-font');
                    this.classList.toggle('active');
                    console.log('Readable font toggled');
                    break;
                    
                case 'reset':
                    const classes = ['large-text', 'small-text', 'grayscale', 'high-contrast', 'negative-contrast', 'bright-background', 'underlined-links', 'readable-font'];
                    classes.forEach(cls => body.classList.remove(cls));
                    options.forEach(opt => opt.classList.remove('active'));
                    console.log('All settings reset');
                    break;
            }
        });
    });
    
    console.log('All event listeners added');
}

// Initialize when DOM is ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', function() {
        console.log('DOM loaded, creating widget...');
        createAccessibilityWidget();
    });
} else {
    console.log('DOM already loaded, creating widget now...');
    createAccessibilityWidget();
}

console.log('Accessibility script loaded');