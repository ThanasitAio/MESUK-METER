<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Page Not Found</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700;800&display=swap');
        
        :root {
            --primary-color: #D3EE98;
            --secondary-color: #A8D672;
            --accent-color: #7FB069;
            --dark-color: #2E382E;
            --light-color: #F5F9F0;
            --shadow-color: rgba(211, 238, 152, 0.3);
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        html, body {
            height: 100%;
            overflow: hidden; /* ป้องกันการสกอร์ */
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, var(--dark-color) 0%, #1a2a1a 100%);
            color: var(--light-color);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }
        
        .container-404 {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 900px;
            padding: 2rem;
        }
        
        .error-content {
            background: rgba(46, 56, 46, 0.7);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 3rem 2rem;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.5);
            border: 1px solid rgba(211, 238, 152, 0.2);
            position: relative;
            overflow: hidden;
        }
        
        .error-content::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(211, 238, 152, 0.1) 0%, transparent 70%);
            z-index: -1;
        }
        
        .error-code {
            font-size: 15rem;
            font-weight: 800;
            background: linear-gradient(45deg, var(--primary-color), var(--accent-color));
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            text-shadow: 0 10px 30px var(--shadow-color);
            margin-bottom: -2rem;
            line-height: 1;
            animation: glow 3s infinite alternate;
            position: relative;
            display: inline-block;
        }
        
        .error-code::after {
            content: '';
            position: absolute;
            bottom: 10px;
            left: 10%;
            width: 80%;
            height: 8px;
            background: linear-gradient(90deg, transparent, var(--primary-color), transparent);
            border-radius: 50%;
            filter: blur(5px);
            animation: shimmer 3s infinite;
        }
        
        .error-title {
            font-size: 3rem;
            margin-bottom: 1.5rem;
            font-weight: 700;
            color: var(--primary-color);
            animation: slideIn 1s ease-out;
            text-shadow: 0 2px 10px rgba(211, 238, 152, 0.3);
        }
        
        .error-description {
            font-size: 1.3rem;
            margin-bottom: 2.5rem;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
            animation: slideIn 1s 0.3s ease-out both;
            color: #e0e0e0;
            line-height: 1.6;
        }
        
        .btn-home {
            background: linear-gradient(45deg, var(--primary-color), var(--accent-color));
            color: var(--dark-color);
            border: none;
            padding: 15px 40px;
            border-radius: 50px;
            font-weight: 700;
            font-size: 1.2rem;
            box-shadow: 0 8px 25px var(--shadow-color);
            transition: all 0.4s ease;
            position: relative;
            overflow: hidden;
            animation: slideIn 1s 0.6s ease-out both;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            z-index: 1;
        }
        
        .btn-home:hover {
            transform: translateY(-5px) scale(1.05);
            box-shadow: 0 12px 30px rgba(211, 238, 152, 0.5);
            color: var(--dark-color);
        }
        
        .btn-home:before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
            transition: all 0.6s ease;
            z-index: -1;
        }
        
        .btn-home:hover:before {
            left: 100%;
        }
        
        .floating-elements {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: 0;
        }
        
        .floating-element {
            position: absolute;
            background: rgba(211, 238, 152, 0.15);
            border-radius: 50%;
            animation: float 20s infinite linear;
            box-shadow: 0 0 20px rgba(211, 238, 152, 0.2);
        }
        
        .floating-element:nth-child(1) {
            width: 100px;
            height: 100px;
            top: 10%;
            left: 5%;
            animation-duration: 25s;
        }
        
        .floating-element:nth-child(2) {
            width: 150px;
            height: 150px;
            top: 70%;
            left: 85%;
            animation-duration: 30s;
        }
        
        .floating-element:nth-child(3) {
            width: 80px;
            height: 80px;
            top: 50%;
            left: 3%;
            animation-duration: 20s;
        }
        
        .floating-element:nth-child(4) {
            width: 120px;
            height: 120px;
            top: 15%;
            left: 90%;
            animation-duration: 35s;
        }
        
        .floating-element:nth-child(5) {
            width: 60px;
            height: 60px;
            top: 80%;
            left: 15%;
            animation-duration: 22s;
        }
        
        .floating-element:nth-child(6) {
            width: 90px;
            height: 90px;
            top: 25%;
            left: 75%;
            animation-duration: 28s;
        }
        
        .lost-text {
            font-size: 1.8rem;
            margin-bottom: 2rem;
            color: var(--primary-color);
            font-weight: 700;
            /* เอา animation: shake ออก */
            animation: glowText 3s infinite alternate;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: rgba(46, 56, 46, 0.7);
            padding: 10px 20px;
            border-radius: 50px;
            border: 1px solid rgba(211, 238, 152, 0.3);
        }
        
        .additional-links {
            margin-top: 3rem;
            padding-top: 2rem;
            border-top: 1px solid rgba(211, 238, 152, 0.2);
        }
        
        .link-item {
            display: inline-flex;
            align-items: center;
            color: var(--primary-color);
            text-decoration: none;
            margin: 0 15px;
            padding: 8px 20px;
            border-radius: 30px;
            transition: all 0.3s ease;
            background: rgba(46, 56, 46, 0.5);
            border: 1px solid rgba(211, 238, 152, 0.2);
        }
        
        .link-item:hover {
            background: rgba(211, 238, 152, 0.2);
            color: var(--light-color);
            transform: translateY(-3px);
            box-shadow: 0 5px 15px var(--shadow-color);
        }
        
        .pulse-dot {
            position: absolute;
            width: 20px;
            height: 20px;
            background: var(--primary-color);
            border-radius: 50%;
            animation: pulseDot 2s infinite;
            box-shadow: 0 0 20px var(--primary-color);
        }
        
        .pulse-dot:nth-child(1) {
            top: 20%;
            left: 10%;
        }
        
        .pulse-dot:nth-child(2) {
            top: 70%;
            left: 90%;
            animation-delay: 0.5s;
        }
        
        .pulse-dot:nth-child(3) {
            top: 85%;
            left: 15%;
            animation-delay: 1s;
        }
        
        .pulse-dot:nth-child(4) {
            top: 10%;
            left: 80%;
            animation-delay: 1.5s;
        }
        
        @keyframes glow {
            0% {
                text-shadow: 0 5px 15px rgba(211, 238, 152, 0.4);
            }
            100% {
                text-shadow: 0 5px 30px rgba(211, 238, 152, 0.8), 0 0 40px rgba(211, 238, 152, 0.4);
            }
        }
        
        @keyframes shimmer {
            0% {
                opacity: 0.3;
                transform: scaleX(0.8);
            }
            50% {
                opacity: 1;
                transform: scaleX(1);
            }
            100% {
                opacity: 0.3;
                transform: scaleX(0.8);
            }
        }
        
        @keyframes slideIn {
            0% {
                opacity: 0;
                transform: translateY(30px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @keyframes float {
            0% {
                transform: translateY(0) rotate(0deg) scale(1);
            }
            33% {
                transform: translateY(-30px) rotate(120deg) scale(1.05);
            }
            66% {
                transform: translateY(20px) rotate(240deg) scale(0.95);
            }
            100% {
                transform: translateY(0) rotate(360deg) scale(1);
            }
        }
        
        @keyframes glowText {
            0% {
                box-shadow: 0 0 10px rgba(211, 238, 152, 0.3);
            }
            100% {
                box-shadow: 0 0 20px rgba(211, 238, 152, 0.6), 0 0 30px rgba(211, 238, 152, 0.3);
            }
        }
        
        @keyframes pulseDot {
            0% {
                transform: scale(0.8);
                opacity: 0.7;
            }
            50% {
                transform: scale(1.2);
                opacity: 1;
            }
            100% {
                transform: scale(0.8);
                opacity: 0.7;
            }
        }
        
        /* Particle effect styles */
        #particles-js {
            position: absolute;
            width: 100%;
            height: 100%;
            z-index: 1;
        }
        
        /* Responsive adjustments */
        @media (max-width: 768px) {
            .error-code {
                font-size: 10rem;
            }
            
            .error-title {
                font-size: 2.2rem;
            }
            
            .error-description {
                font-size: 1.1rem;
            }
            
            .lost-text {
                font-size: 1.4rem;
            }
            
            .link-item {
                margin: 5px;
                display: block;
                text-align: center;
            }
        }
        
        @media (max-width: 576px) {
            .error-code {
                font-size: 8rem;
            }
            
            .error-title {
                font-size: 1.8rem;
            }
            
            .container-404 {
                padding: 1rem;
            }
            
            .error-content {
                padding: 2rem 1rem;
            }
        }
    </style>
</head>
<body>
    <!-- Particle Background -->
    <div id="particles-js"></div>
    
    <!-- Floating background elements -->
    <div class="floating-elements">
        <div class="floating-element"></div>
        <div class="floating-element"></div>
        <div class="floating-element"></div>
        <div class="floating-element"></div>
        <div class="floating-element"></div>
        <div class="floating-element"></div>
    </div>
    
    <!-- Pulse dots -->
    <div class="pulse-dot"></div>
    <div class="pulse-dot"></div>
    <div class="pulse-dot"></div>
    <div class="pulse-dot"></div>
    
    <div class="container container-404">
        <div class="row justify-content-center">
            <div class="col-lg-10 text-center">
                <div class="error-content">
                    <h1 class="error-code">404</h1>
                    
                    <div class="lost-text">
                        <i class="fas fa-exclamation-triangle"></i>Oops! You seem to be lost
                    </div>
                    
                    <h2 class="error-title">Page Not Found</h2>
                    
                    <p class="error-description">
                        The page you are looking for might have been removed, had its name changed, 
                        or is temporarily unavailable. Let's get you back on track!
                    </p>
                    
                    <a href="/mesuk" class="btn btn-home">
                        <i class="fas fa-home me-2"></i>Go Home
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Include Particles.js library -->
    <script src="https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>
    
    <script>
        // Initialize particles.js
        document.addEventListener('DOMContentLoaded', function() {
            particlesJS('particles-js', {
                particles: {
                    number: { value: 80, density: { enable: true, value_area: 800 } },
                    color: { value: "#D3EE98" },
                    shape: { type: "circle" },
                    opacity: { value: 0.5, random: true },
                    size: { value: 3, random: true },
                    line_linked: {
                        enable: true,
                        distance: 150,
                        color: "#D3EE98",
                        opacity: 0.3,
                        width: 1
                    },
                    move: {
                        enable: true,
                        speed: 2,
                        direction: "none",
                        random: true,
                        out_mode: "out",
                        bounce: false
                    }
                },
                interactivity: {
                    detect_on: "canvas",
                    events: {
                        onhover: { enable: true, mode: "repulse" },
                        onclick: { enable: true, mode: "push" },
                        resize: true
                    }
                },
                retina_detect: true
            });
            
            // Button click effect
            const btn = document.querySelector('.btn-home');
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                
                // Create ripple effect
                const ripple = document.createElement('span');
                const rect = btn.getBoundingClientRect();
                const size = Math.max(rect.width, rect.height);
                const x = e.clientX - rect.left - size / 2;
                const y = e.clientY - rect.top - size / 2;
                
                ripple.style.cssText = `
                    position: absolute;
                    border-radius: 50%;
                    background: rgba(255, 255, 255, 0.7);
                    transform: scale(0);
                    animation: ripple 0.6s linear;
                    pointer-events: none;
                    width: ${size}px;
                    height: ${size}px;
                    left: ${x}px;
                    top: ${y}px;
                `;
                
                btn.appendChild(ripple);
                
                // Navigate after animation
                setTimeout(() => {
                    window.location.href = '/mesuk';
                }, 800);
            });
            
            // Add CSS for ripple animation
            const style = document.createElement('style');
            style.textContent = `
                @keyframes ripple {
                    to {
                        transform: scale(4);
                        opacity: 0;
                    }
                }
            `;
            document.head.appendChild(style);
        });
    </script>
</body>
</html>