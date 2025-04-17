// Dark mode functionality
function toggleDarkMode() {
    document.body.classList.toggle('dark-mode');
    const isDarkMode = document.body.classList.contains('dark-mode');
    localStorage.setItem('darkMode', isDarkMode);
}

// Check for saved dark mode preference
document.addEventListener('DOMContentLoaded', () => {
    const isDarkMode = localStorage.getItem('darkMode') === 'true';
    if (isDarkMode) {
        document.body.classList.add('dark-mode');
    }
    
    // Create water effect
    const waterEffect = document.createElement('div');
    waterEffect.className = 'water-effect';
    document.body.appendChild(waterEffect);
    
    // Create bubbles
    createBubbles();
});

// Bubble creation
function createBubbles() {
    setInterval(() => {
        const bubble = document.createElement('div');
        bubble.className = 'bubble';
        
        // Random size between 10px and 30px
        const size = Math.random() * 20 + 10;
        bubble.style.width = `${size}px`;
        bubble.style.height = `${size}px`;
        
        // Random position
        bubble.style.left = `${Math.random() * 100}%`;
        
        document.body.appendChild(bubble);
        
        // Remove bubble after animation
        setTimeout(() => {
            bubble.remove();
        }, 4000);
    }, 300);
}
