<?php
$client_id = "1407114756053139588";
$redirect_uri = "http://localhost/discord-app/callback.php";
$scope = "identify guilds";
$response_type = "code";

// Discord OAuth URL
$oauth_url = "https://discord.com/api/oauth2/authorize?client_id=$client_id&redirect_uri=" . urlencode($redirect_uri) . "&response_type=$response_type&scope=$scope";
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>VDL Roleplay - Premium FiveM Experience</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Space+Grotesk:wght@300;400;500;600;700&display=swap');

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    :root {
      --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      --secondary-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
      --accent-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
      --dark-gradient: linear-gradient(135deg, #0c0c0c 0%, #1a1a2e 50%, #16213e 100%);
      --glass-bg: rgba(255, 255, 255, 0.05);
      --glass-border: rgba(255, 255, 255, 0.1);
    }

    body {
      font-family: 'Inter', sans-serif;
      background: var(--dark-gradient);
      color: white;
      overflow-x: hidden;
      min-height: 100vh;
      position: relative;
    }

    /* --- FINAL PRELOADER & REVEAL CSS --- */
    #preloader {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      z-index: 9999;
      display: grid;
      place-items: center;
      transition: opacity 1s ease-in-out;
    }
    
    .preloader-background {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      overflow: hidden;
      z-index: -1;
    }
    
    .preloader-background::after {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.6);
    }

    .preloader-text {
      color: #fff;
      font-family: 'Space Grotesk', sans-serif;
      font-size: clamp(24px, 5vw, 48px);
      font-weight: 600;
      letter-spacing: 5px;
      text-shadow: 0 0 15px rgba(0,0,0,0.5);
      animation: textAppear 2.5s ease-in-out forwards;
    }

    @keyframes textAppear {
      0% { opacity: 0; transform: scale(0.9); }
      30%, 70% { opacity: 1; transform: scale(1); }
      100% { opacity: 0; transform: scale(0.9); }
    }
    
    #preloader.hidden {
      opacity: 0;
      pointer-events: none;
    }

    body.is-loading .navbar,
    body.is-loading .hero-content,
    body.is-loading .login-container {
      opacity: 0;
    }

    body.is-loading .navbar {
      transform: translateY(-100%);
    }

    body.is-loading .hero-content {
      transform: translateX(-100px);
    }

    body.is-loading .login-container {
      transform: translateX(100px);
    }

    .navbar, .hero-content, .login-container {
      transition: opacity 1.2s ease-out, transform 1.2s ease-out;
    }
    /* --- END PRELOADER & REVEAL CSS --- */

    @keyframes fadeZoom {
      0% { opacity: 0; transform: scale(1); }
      5% { opacity: 1; transform: scale(1.05); }
      25% { opacity: 1; transform: scale(1.1); }
      30% { opacity: 0; transform: scale(1.1); }
      100% { opacity: 0; }
    }
    
    .background-wrapper {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      z-index: -2;
    }

    .video-background {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      overflow: hidden;
      z-index: -1;
    }

    .video-background::after {
      content: "";
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.55);
      z-index: 1;
      pointer-events: none;
    }
    
    /* Updated CSS for 11-image slideshow */
    .preloader-background img,
    .video-background img {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        opacity: 0;
        animation: fadeZoom 44s infinite; /* 11 images * 8s = 88s */
        z-index: 0;
    }
    
    .preloader-background img:nth-child(1), .video-background img:nth-child(1) { animation-delay: 0s; }
    .preloader-background img:nth-child(2), .video-background img:nth-child(2) { animation-delay: 8s; }
    .preloader-background img:nth-child(3), .video-background img:nth-child(3) { animation-delay: 16s; }
    .preloader-background img:nth-child(4), .video-background img:nth-child(4) { animation-delay: 24s; }
    .preloader-background img:nth-child(5), .video-background img:nth-child(5) { animation-delay: 32s; }
    .preloader-background img:nth-child(6), .video-background img:nth-child(6) { animation-delay: 40s; }
    .preloader-background img:nth-child(7), .video-background img:nth-child(7) { animation-delay: 48s; }
    .preloader-background img:nth-child(8), .video-background img:nth-child(8) { animation-delay: 56s; }
    .preloader-background img:nth-child(9), .video-background img:nth-child(9) { animation-delay: 64s; }
    .preloader-background img:nth-child(10), .video-background img:nth-child(10) { animation-delay: 72s; }
    .preloader-background img:nth-child(11), .video-background img:nth-child(11) { animation-delay: 80s; }
    
    .gradient-overlay {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: radial-gradient(circle at 20% 80%, rgba(102, 126, 234, 0.1) 0%, transparent 50%),
                  radial-gradient(circle at 80% 20%, rgba(245, 87, 108, 0.1) 0%, transparent 50%),
                  radial-gradient(circle at 40% 40%, rgba(79, 172, 254, 0.05) 0%, transparent 50%),
                  linear-gradient(135deg, rgba(12, 12, 12, 0.8) 0%, rgba(26, 26, 46, 0.9) 100%);
    }

    .particles-container {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      z-index: -1;
      pointer-events: none;
      overflow: hidden;
    }

    .particle {
      position: absolute;
      width: 4px;
      height: 4px;
      background: linear-gradient(45deg, #4facfe, #00f2fe);
      border-radius: 50%;
      opacity: 0.6;
      animation: floatUp 8s infinite linear;
    }

    @keyframes floatUp {
      0% {
        transform: translateY(100vh) rotate(0);
        opacity: 0;
      }
      10% { opacity: 0.6; }
      90% { opacity: 0.6; }
      100% {
        transform: translateY(-10vh) rotate(360deg);
        opacity: 0;
      }
    }
    
    /* Navigation */
    .navbar {
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      z-index: 1000;
      background: rgba(12, 12, 12, 0.85);
      backdrop-filter: blur(15px);
      border-bottom: 1px solid rgba(255, 255, 255, 0.05);
      height: 60px;
      display: flex;
      align-items: center;
    }

    .nav-container {
      max-width: 1400px;
      margin: 0 auto;
      padding: 0 25px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      width: 100%;
    }

    .logo-container {
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .logo-wrapper {
      position: relative;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .logo-img {
      width: 42px;
      height: 42px;
      z-index: 2;
      position: relative;
      border-radius: 6%;
    }

    .glow-cube {
      position: absolute;
      width: 50px;
      height: 50px;
      border-radius: 30%;
      background: linear-gradient(135deg, rgba(134, 75, 236, 0.6), rgba(118, 75, 162, 0.3));
      z-index: 1;
      border-radius: 10px;
      filter: blur(18px);
      animation: cubeGlow 4s ease-in-out infinite;
    }

    @keyframes cubeGlow {
      0% { transform: scale(1) rotate(0); opacity: 0.7; }
      50% { transform: scale(1.15) rotate(3deg); opacity: 1; }
      100% { transform: scale(1) rotate(0); opacity: 0.7; }
    }

    .logo-main {
      font-size: 20px;
      font-weight: 800;
      color: #fff;
      letter-spacing: 3px;
    }

    .logo-sub {
      display: none;
    }

    .nav-links {
      display: flex;
      gap: 30px;
      align-items: center;
    }

    .nav-link {
      color: rgba(255, 255, 255, 0.8);
      text-decoration: none;
      font-weight: 500;
      font-size: 14px;
      transition: color 0.3s ease;
    }

    .nav-link:hover {
      color: #fff;
    }

    .discord-nav-btn {
      background: linear-gradient(135deg, #667eea, #764ba2);
      color: #fff;
      padding: 7px 18px;
      border-radius: 8px;
      font-size: 14px;
      font-weight: 600;
      text-decoration: none;
      box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
      transition: all 0.3s ease;
    }

    .discord-nav-btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 16px rgba(102, 126, 234, 0.45);
    }
    
    /* Hero Section */
    .hero-section {
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 80px 30px 50px;
    }

    .hero-container {
      max-width: 1400px;
      width: 100%;
      display: grid;
      grid-template-columns: 1.2fr 1fr;
      gap: 80px;
      align-items: center;
    }

    .hero-content {
      display: flex;
      flex-direction: column;
      gap: 30px;
    }

    .hero-badge {
      display: inline-flex;
      align-items: center;
      gap: 10px;
      background: var(--glass-bg);
      backdrop-filter: blur(20px);
      border: 1px solid var(--glass-border);
      padding: 12px 20px;
      border-radius: 50px;
      font-size: 14px;
      font-weight: 600;
      color: rgba(255, 255, 255, 0.9);
      width: fit-content;
      box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
    }

    .hero-badge i {
      color: #4facfe;
    }

    .hero-title {
      font-size: clamp(48px, 6vw, 72px);
      font-weight: 800;
      font-family: 'Space Grotesk', sans-serif;
      line-height: 1.1;
      letter-spacing: -2px;
    }

    .title-gradient {
      background: linear-gradient(135deg, #fff 0, #4facfe 50%, #00f2fe 100%);
      background-size: 200% 200%;
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      animation: gradientMove 4s ease-in-out infinite;
    }

    @keyframes gradientMove {
      0%, 100% { background-position: 0 50%; }
      50% { background-position: 100% 50%; }
    }

    .hero-subtitle {
      font-size: 20px;
      font-weight: 400;
      color: rgba(255, 255, 255, 0.7);
      line-height: 1.6;
      max-width: 500px;
    }

    .hero-stats {
      display: flex;
      gap: 40px;
      margin-top: 20px;
    }

    .stat-item {
      display: flex;
      flex-direction: column;
      gap: 5px;
      align-items: center;
    }

    .stat-number {
      font-size: 32px;
      font-weight: 700;
      font-family: 'Space Grotesk', sans-serif;
      background: var(--accent-gradient);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
    }

    .stat-label {
      font-size: 13px;
      color: rgba(255, 255, 255, 0.5);
      text-transform: uppercase;
      font-weight: 600;
      letter-spacing: 1px;
    }
    
    /* Login Card */
    .login-container {
      position: relative;
    }

    .login-card {
      background: var(--glass-bg);
      backdrop-filter: blur(30px);
      border: 1px solid var(--glass-border);
      border-radius: 24px;
      padding: 50px 40px;
      position: relative;
      overflow: hidden;
      box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
    }

    .login-card::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 2px;
      background: var(--primary-gradient);
      border-radius: 24px 24px 0 0;
    }

    .card-glow {
      position: absolute;
      top: -50%;
      left: -50%;
      width: 200%;
      height: 200%;
      background: radial-gradient(circle, rgba(102, 126, 234, 0.1) 0, transparent 70%);
      opacity: 0;
      transition: opacity 0.3s ease;
      pointer-events: none;
    }

    .login-card:hover .card-glow {
      opacity: 1;
    }

    .login-header {
      text-align: center;
      margin-bottom: 40px;
      position: relative;
      z-index: 2;
    }

    .login-title {
      font-size: 28px;
      font-weight: 700;
      font-family: 'Space Grotesk', sans-serif;
      margin-bottom: 10px;
    }

    .login-subtitle {
      color: rgba(255, 255, 255, 0.6);
      font-size: 16px;
      line-height: 1.5;
    }

    .discord-btn {
      width: 100%;
      background: linear-gradient(135deg, #5865f2 0, #4752c4 100%);
      color: #fff;
      border: none;
      padding: 18px 30px;
      border-radius: 16px;
      font-size: 16px;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s ease;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 12px;
      text-decoration: none;
      margin-bottom: 30px;
      position: relative;
      overflow: hidden;
      box-shadow: 0 8px 25px rgba(88, 101, 242, 0.3);
    }

    .discord-btn::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
      transition: left 0.5s ease;
    }

    .discord-btn:hover::before {
      left: 100%;
    }

    .discord-btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 12px 35px rgba(88, 101, 242, 0.4);
    }

    .discord-icon {
      font-size: 20px;
    }

    .features-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 20px;
      margin-bottom: 30px;
    }

    .feature-item {
      display: flex;
      align-items: center;
      gap: 12px;
      padding: 15px;
      background: rgba(255, 255, 255, 0.02);
      border-radius: 12px;
      border: 1px solid rgba(255, 255, 255, 0.05);
      transition: all 0.3s ease;
    }

    .feature-item:hover {
      background: rgba(255, 255, 255, 0.05);
      border-color: rgba(102, 126, 234, 0.2);
    }

    .feature-icon {
      width: 35px;
      height: 35px;
      background: var(--primary-gradient);
      border-radius: 8px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 14px;
      color: #fff;
      flex-shrink: 0;
    }

    .feature-text {
      font-size: 14px;
      font-weight: 500;
      color: rgba(255, 255, 255, 0.8);
    }

    .security-notice {
      background: rgba(79, 172, 254, 0.1);
      border: 1px solid rgba(79, 172, 254, 0.2);
      border-radius: 12px;
      padding: 20px;
      text-align: center;
    }

    .security-notice p {
      font-size: 13px;
      color: rgba(255, 255, 255, 0.7);
      margin: 0;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
    }

    .security-notice i {
      color: #4facfe;
    }
    
    /* Footer styles */
    .footer {
        padding: 40px 20px;
        text-align: center;
        background: rgba(12, 12, 12, 0.85); /* Match navbar background */
        backdrop-filter: blur(15px);
        border-top: 1px solid rgba(255, 255, 255, 0.05);
        margin-top: 80px; /* Provide some space from the hero section */
        font-size: 15px;
        color: rgba(255, 255, 255, 0.6);
        line-height: 1.6;
    }

    .footer p:first-child {
        font-weight: 500;
        margin-bottom: 8px;
        font-size: 16px;
        color: rgba(255, 255, 255, 0.8);
    }

    .footer a {
        color: #667eea; /* Discord-like blue for links */
        text-decoration: none;
        transition: color 0.3s ease;
    }

    .footer a:hover {
        color: #764ba2;
    }

    /* Media Queries */
    @media (max-width:1024px) {
      .hero-container {
        grid-template-columns: 1fr;
        gap: 60px;
        text-align: center;
      }
      .hero-stats {
        justify-content: center;
      }
    }
    @media (max-width:768px) {
      .nav-container {
        padding: 15px 20px;
      }
      .nav-links {
        display: none;
      }
      .hero-section {
        padding: 100px 20px 50px;
      }
      .login-card {
        padding: 40px 25px;
      }
      .features-grid {
        grid-template-columns: 1fr;
      }
      .hero-stats {
        flex-direction: column;
        gap: 20px;
        align-items: center;
      }
      .stat-item {
        align-items: center;
      }
    }
  </style>
</head>
<body class="is-loading">

  <div id="preloader">
    <div class="preloader-background">
      <img src="images/ss1.jpg?v=2" alt="">
      <img src="images/ss2.jpg?v=2" alt="">
      <img src="images/ss3.jpg?v=2" alt="">
      <img src="images/ss4.jpg?v=2" alt="">
      <img src="images/ss5.jpg?v=2" alt="">
      <img src="images/ss6.jpg?v=2" alt="">
      <img src="images/ss7.jpg?v=2" alt="">
      <img src="images/ss8.jpg?v=2" alt="">
      <img src="images/ss9.jpg?v=2" alt="">
      <img src="images/ss10.jpg?v=2" alt="">
      <img src="images/ss11.jpg?v=2" alt="">
    </div>
    <div class="preloader-text">VDL ROLEPLAY</div>
  </div>

  <div class="video-background">
    <img src="images/ss1.jpg?v=2" alt="Background 1">
    <img src="images/ss2.jpg?v=2" alt="Background 2">
    <img src="images/ss3.jpg?v=2" alt="Background 3">
    <img src="images/ss4.jpg?v=2" alt="Background 4">
    <img src="images/ss5.jpg?v=2" alt="Background 5">
    <img src="images/ss6.jpg?v=2" alt="Background 6">
    <img src="images/ss7.jpg?v=2" alt="Background 7">
    <img src="images/ss8.jpg?v=2" alt="Background 8">
    <img src="images/ss9.jpg?v=2" alt="Background 9">
    <img src="images/ss10.jpg?v=2" alt="Background 10">
    <img src="images/ss11.jpg?v=2" alt="Background 11">
  </div>

  <div class="particles-container" id="particlesContainer"></div>

  <nav class="navbar" id="navbar">
    <div class="nav-container">
      <div class="logo-container">
        <a href="#" class="logo">
          <div class="logo-wrapper">
            <img src="images/logo.png" alt="VDL Logo" class="logo-img">
            <span class="glow-cube"></span>
          </div>
        </a>
        <div class="logo-text">
          <div class="logo-main">VDL Roleplay</div>
          <div class="logo-sub">Premium Experience</div>
        </div>
      </div>
      <div class="nav-links">
        <a href="#" class="nav-link">Home</a>
        <a href="#" class="nav-link">Rules</a>
        <a href="#" class="nav-link">Store</a>
        <a href="#" class="nav-link">Community</a>
        <a href="#" class="discord-nav-btn">
          <i class="fab fa-discord"></i>
          Join Discord
        </a>
      </div>
    </div>
  </nav>

  <section class="hero-section">
    <div class="hero-container">
      <div class="hero-content">
        <div class="hero-badge">
          <i class="fas fa-crown"></i>
          First Punjabi FiveM Server
        </div>
        
        <h1 class="hero-title">
          The Ultimate <br>
          <span class="title-gradient">Roleplay Experience</span>
        </h1>
        
        <p class="hero-subtitle">
          Join today in the most immersive and realistic FiveM roleplay server. Experience custom jobs, realistic economy, and endless possibilities.
        </p>
        
        <div class="hero-stats">
          <div class="stat-item">
            <div class="stat-number">24/7</div>
            <div class="stat-label">Support System</div>
          </div>
          <div class="stat-item">
            <div class="stat-number">99.9%</div>
            <div class="stat-label">Uptime</div>
          </div>
          <div class="stat-item">
            <div class="stat-number">50+</div>
            <div class="stat-label">Custom Features</div>
          </div>
        </div>
      </div>

      <div class="login-container">
        <div class="login-card">
          <div class="card-glow"></div>
          
          <div class="login-header">
            <h2 class="login-title">Access Your Account</h2>
            <p class="login-subtitle">Connect with Discord to join our exclusive community</p>
          </div>

          <a href="<?php echo $oauth_url; ?>" class="discord-btn">
            <i class="fab fa-discord discord-icon"></i>
            Continue with Discord
          </a>

          <div class="features-grid">
            <div class="feature-item"><div class="feature-icon"><i class="fas fa-shield-alt"></i></div><div class="feature-text">Secure Authentication</div></div>
            <div class="feature-item"><div class="feature-icon"><i class="fas fa-users"></i></div><div class="feature-text">Exclusive Access</div></div>
            <div class="feature-item"><div class="feature-icon"><i class="fas fa-gamepad"></i></div><div class="feature-text">Premium Features</div></div>
            <div class="feature-item"><div class="feature-icon"><i class="fas fa-headset"></i></div><div class="feature-text">24/7 Support</div></div>
          </div>

          <div class="security-notice">
            <p><i class="fas fa-lock"></i>Your privacy is our priority. We only access basic profile information.</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <footer class="footer">
    <p>&copy; <span id="currentYear"></span> VDL RP - All rights reserved</p>
    <p>Have questions? Contact our staff on <a href="https://discord.gg/your-discord-invite" target="_blank" rel="noopener noreferrer">Discord</a></p>
  </footer>

  <script>
    // Set current year for copyright
    document.getElementById('currentYear').textContent = new Date().getFullYear();

    // Create animated particles
    function createParticles() {
      const container = document.getElementById('particlesContainer');
      const particleCount = 70;
      for (let i = 0; i < particleCount; i++) {
        const particle = document.createElement('div');
        particle.className = 'particle';
        particle.style.left = Math.random() * 100 + '%';
        particle.style.animationDelay = Math.random() * 8 + 's';
        particle.style.animationDuration = (Math.random() * 4 + 6) + 's';
        container.appendChild(particle);
      }
    }

    // Navbar scroll effect
    function handleNavbarScroll() {
      const navbar = document.getElementById('navbar');
      if (window.scrollY > 50) {
        navbar.classList.add('scrolled');
      } else {
        navbar.classList.remove('scrolled');
      }
    }

    // Login card mouse tracking effect
    function initCardEffects() {
      const loginCard = document.querySelector('.login-card');
      const cardGlow = document.querySelector('.card-glow');
      loginCard.addEventListener('mousemove', (e) => {
        const rect = loginCard.getBoundingClientRect();
        const x = e.clientX - rect.left;
        const y = e.clientY - rect.top;
        const centerX = rect.width / 2;
        const centerY = rect.height / 2;
        const deltaX = (x - centerX) / centerX;
        const deltaY = (y - centerY) / centerY;
        loginCard.style.transform = `perspective(1000px) rotateX(${deltaY * 2}deg) rotateY(${deltaX * 2}deg) translateZ(10px)`;
        cardGlow.style.background = `radial-gradient(circle at ${x}px ${y}px, rgba(102, 126, 234, 0.15) 0%, transparent 50%)`;
      });
      loginCard.addEventListener('mouseleave', () => {
        loginCard.style.transform = 'perspective(1000px) rotateX(0deg) rotateY(0deg) translateZ(0px)';
      });
    }

    // Smooth scroll for internal links
    function initSmoothScroll() {
      document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
          e.preventDefault();
          const target = document.querySelector(this.getAttribute('href'));
          if (target) {
            target.scrollIntoView({ behavior: 'smooth', block: 'start' });
          }
        });
      });
    }

    // Initialize everything when DOM is loaded
    document.addEventListener('DOMContentLoaded', function() {
      createParticles();
      initCardEffects();
      initSmoothScroll();
      window.addEventListener('scroll', handleNavbarScroll);
      
      document.querySelector('.discord-btn').addEventListener('click', function() {
        this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Connecting...';
      });
      
      document.querySelectorAll('.feature-item').forEach(item => {
        item.addEventListener('mouseenter', function() {
          this.style.transform = 'translateX(5px)';
        });
        item.addEventListener('mouseleave', function() {
          this.style.transform = 'translateX(0)';
        });
      });
    });
  </script>

  <script>
    window.addEventListener('load', function() {
      const preloader = document.getElementById('preloader');
      
      // Wait for the text animation to finish (2.5s)
      setTimeout(() => {
        // Start fading out the preloader
        preloader.classList.add('hidden');
        // Start animating the main content in by removing the loading class
        document.body.classList.remove('is-loading');
      }, 2500);

      // Completely remove the preloader after its fade-out transition is done (2.5s + 1s)
      setTimeout(() => {
        preloader.style.display = 'none';
      }, 3500);
    });
  </script>

</body>
</html>