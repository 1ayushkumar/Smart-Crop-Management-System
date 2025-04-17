// Create water wave effect
function createWaterWave() {
    const wave = document.createElement('div');
    wave.className = 'water-wave';
    document.body.appendChild(wave);
}

// Create floating bubbles
function createBubble() {
    const bubble = document.createElement('div');
    bubble.className = 'bubble';
    
    // Random size between 10px and 30px
    const size = Math.random() * 20 + 10;
    bubble.style.width = `${size}px`;
    bubble.style.height = `${size}px`;
    
    // Random position
    bubble.style.left = `${Math.random() * 100}%`;
    bubble.style.bottom = '-50px';
    
    document.body.appendChild(bubble);
    
    // Remove bubble after animation
    setTimeout(() => {
        bubble.remove();
    }, 6000);
}

// Initialize effects
document.addEventListener('DOMContentLoaded', () => {
    createWaterWave();
    
    // Create bubbles periodically
    setInterval(createBubble, 2000);
    
    // Add glow effect to important elements
    document.querySelectorAll('.btn-primary, .card-header').forEach(element => {
        element.classList.add('glow-effect');
    });
    
    // Add background pattern
    document.body.classList.add('bg-pattern');
});
