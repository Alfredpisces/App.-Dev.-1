import './bootstrap';
// FIX: Use default import for tsParticles and name it 'Particles'
// This is the correct way
import { tsParticles as Particles } from "tsparticles";

// AlpineJS is now initialized in bootstrap.js.
// We just need to start it here.
window.Alpine.start();


// --- Global function to load and configure the finance-themed particle effect ---
window.loadFinanceParticles = (id) => {
    // FIX: Use the imported name 'Particles' to load the config
    Particles.load({
        id: id,
        options: {
            // Configuration for a subtle, moving finance-themed effect (coins, networks)
            particles: {
                // Use a reasonable number of particles for the background effect
                number: { value: 80, density: { enable: true, value_area: 800 } },
                color: { 
                    value: ["#FFD700", "#00FF00", "#FF4500"], // Gold (stability), green (profit), orange-red (alert/loss)
                    animation: { h: { speed: 5 } } // Subtle hue shift for market volatility
                },
                shape: { type: "circle" }, // Circles as "coins"; could swap to "star" for flair
                // Subtle opacity and size variations (like fluctuating values)
                opacity: { value: 0.7, random: true, anim: { enable: true, speed: 2, opacity_min: 0.3, sync: false } },
                size: { value: 3, random: true, anim: { enable: true, speed: 3, size_min: 1, sync: false } },
                line_linked: { 
                    enable: true, 
                    distance: 120, 
                    color: "#4682B4", // Steel blue for transaction lines/stock charts
                    opacity: 0.3, 
                    width: 0.5 
                },
                move: {
                    enable: true,
                    speed: 0.8, // Gentle drift like market trends
                    direction: "none",
                    random: true, // Random paths for unpredictability
                    straight: false,
                    out_mode: "bounce", // Bounce back like resilient markets
                    bounce: { horizontal: true, vertical: false },
                    attract: { enable: false, rotateX: 600, rotateY: 1200 }
                },
            },
            interactivity: {
                detect_on: "canvas",
                events: { 
                    onhover: { enable: true, mode: "grab" }, // Grab particles like collecting funds
                    onclick: { enable: true, mode: "push" }, // Push new particles like investing
                    resize: true 
                },
                modes: { 
                    grab: { distance: 100, line_linked: { opacity: 0.8 } }, 
                    push: { particles_nb: 3, default: { size: 2 } } // Smaller "new investments"
                }
            },
            retina_detect: true,
            background: {
                // Dark navy background for professional finance aesthetic
                color: { value: "#121620" } 
            },
        }
    });
};