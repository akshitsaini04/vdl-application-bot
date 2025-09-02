<?php
session_start();

if (!isset($_SESSION['discord_user'])) {
    $_SESSION['discord_user'] = ['username' => 'akbooster', 'id' => '123456789'];
}
$discord_user = $_SESSION['discord_user'];

// Check for submission status and error messages
$status = $_GET['status'] ?? null;
$error_message = $_SESSION['form_error'] ?? 'Please try again or contact an admin.';
unset($_SESSION['form_error']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VDL Roleplay - Application Portal</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Orbitron:wght@400;700;900&family=Space+Grotesk:wght@300;400;500;600;700&display=swap');
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: linear-gradient(135deg, #0f0f23, #1a1a2e, #16213e); color: white; min-height: 100vh; overflow-x: hidden; }
        .background-grid { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-image: linear-gradient(rgba(0, 198, 255, 0.03) 1px, transparent 1px), linear-gradient(90deg, rgba(0, 198, 255, 0.03) 1px, transparent 1px); background-size: 50px 50px; z-index: -1; }
        .floating-shapes { position: fixed; width: 100%; height: 100%; z-index: -1; pointer-events: none; }
        .shape { position: absolute; border-radius: 50%; background: radial-gradient(circle, rgba(0, 198, 255, 0.1), transparent); animation: float 8s infinite ease-in-out; }
        @keyframes float { 0%, 100% { transform: translateY(0px) rotate(0deg); } 50% { transform: translateY(-30px) rotate(180deg); } }
        .navbar { position: sticky; top: 0; z-index: 1000; background: rgba(12, 12, 12, 0.85); backdrop-filter: blur(15px); border-bottom: 1px solid rgba(255, 255, 255, 0.05); height: 60px; display: flex; align-items: center; }
        .nav-container { max-width: 1200px; margin: 0 auto; padding: 0 30px; display: flex; align-items: center; justify-content: space-between; width: 100%; }
        .logo-container { display: flex; align-items: center; gap: 10px; }
        .logo-wrapper { position: relative; display: flex; align-items: center; justify-content: center; }
        .logo-img { width: 42px; height: 42px; z-index: 2; position: relative; border-radius: 6%; }
        .glow-cube { position: absolute; width: 50px; height: 50px; background: linear-gradient(135deg, rgba(134, 75, 236, 0.6), rgba(118, 75, 162, 0.3)); z-index: 1; border-radius: 10px; filter: blur(18px); animation: cubeGlow 4s ease-in-out infinite; }
        @keyframes cubeGlow { 0% { transform: scale(1) rotate(0); opacity: 0.7; } 50% { transform: scale(1.15) rotate(3deg); opacity: 1; } 100% { transform: scale(1) rotate(0); opacity: 0.7; } }
        .logo-main { font-family: 'Space Grotesk', sans-serif; font-size: 20px; font-weight: 800; color: #fff; letter-spacing: 3px; }
        .user-info { color: rgba(255,255,255,0.8); display: flex; align-items: center; gap: 10px; }
        .container { max-width: 1200px; margin: 0 auto; padding: 50px 30px; }
        .page-title { text-align: center; margin-bottom: 60px; }
        .page-title h2 { font-size: 48px; font-weight: 700; font-family: 'Orbitron', monospace; background: linear-gradient(270deg, #ffffff, #00c6ff, #0072ff); background-size: 300% 300%; -webkit-background-clip: text; -webkit-text-fill-color: transparent; animation: gradientShift 4s ease-in-out infinite; margin-bottom: 15px; }
        .page-title h2 span { background: linear-gradient(270deg, #ffffff, #00c6ff); -webkit-background-clip: text; }
        .page-title p { font-size: 18px; color: rgba(255, 255, 255, 0.8); }
        @keyframes gradientShift { 0%, 100% { background-position: 0% 50%; } 50% { background-position: 100% 50%; } }
        .department-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 30px; margin-bottom: 50px; }
        .department-card { background: rgba(255, 255, 255, 0.05); backdrop-filter: blur(20px); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 20px; padding: 40px 30px; text-align: center; cursor: pointer; transition: all 0.3s ease; position: relative; overflow: hidden; }
        .department-card::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 3px; background: linear-gradient(90deg, transparent, var(--dept-color), transparent); opacity: 0; transition: opacity 0.3s ease; }
        .department-card:hover::before { opacity: 1; }
        .department-card:hover { transform: translateY(-10px); border-color: var(--dept-color); box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3); }
        .department-card.selected { border-color: var(--dept-color); background: rgba(255, 255, 255, 0.08); transform: translateY(-10px); box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3); }
        .department-card.selected::before { opacity: 1; }
        .dept-icon { width: 80px; height: 80px; margin: 0 auto 20px; background: var(--dept-color); border-radius: 20px; display: flex; align-items: center; justify-content: center; font-size: 36px; color: white; }
        .dept-title { font-size: 24px; font-weight: 600; margin-bottom: 10px; color: white; }
        .dept-description { font-size: 14px; color: rgba(255, 255, 255, 0.7); line-height: 1.5; }
        .pd-card { --dept-color: #1e40af; } .ems-card { --dept-color: #dc2626; } .gang-card { --dept-color: #7c2d12; } .staff-card { --dept-color: #7c3aed; }
        .application-form { background: rgba(255, 255, 255, 0.05); backdrop-filter: blur(20px); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 20px; padding: 40px; margin-top: 40px; display: none; }
        .application-form.active { display: block; animation: slideIn 0.5s ease; }
        @keyframes slideIn { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .form-section { margin-bottom: 40px; }
        .form-section h3 { font-size: 20px; font-weight: 600; margin-bottom: 25px; color: #66a1ff; display: flex; align-items: center; gap: 10px; }
        .form-group { margin-bottom: 30px; }
        .form-group label { display: block; margin-bottom: 10px; font-weight: 500; color: rgba(255, 255, 255, 0.9); }
        .form-group input, .form-group textarea, .form-group select { width: 100%; padding: 15px; background: rgba(0, 0, 0, 0.2); border: 1px solid rgba(255, 255, 255, 0.2); border-radius: 12px; color: white; font-size: 16px; transition: all 0.3s ease; font-family: 'Inter', sans-serif; }
        .form-group input:focus, .form-group textarea:focus, .form-group select:focus { outline: none; border-color: var(--selected-color); box-shadow: 0 0 0 3px rgba(0, 198, 255, 0.1); }
        .form-group textarea { min-height: 120px; resize: vertical; }
        .form-group select { appearance: none; -webkit-appearance: none; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='rgba(255,255,255,0.7)'%3E%3Cpath d='M7.41 8.59L12 13.17l4.59-4.58L18 10l-6 6-6-6 1.41-1.41z'/%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right 15px center; background-size: 24px; padding-right: 50px; }
        .form-group select option { background: #1a1a2e; color: white; }
        .submit-btn { background: linear-gradient(135deg, var(--selected-color), #0072ff); color: white; border: none; padding: 18px 40px; border-radius: 12px; font-size: 18px; font-weight: 600; cursor: pointer; transition: all 0.3s ease; display: flex; align-items: center; gap: 10px; margin: 30px auto 0; }
        .submit-btn:hover { transform: translateY(-2px); box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3); }
        .submit-btn:disabled { opacity: 0.6; cursor: not-allowed; }
        .success-message, .error-message { border-radius: 12px; padding: 20px; text-align: center; display: none; margin-top: 30px; animation: slideIn 0.5s ease; }
        .success-message { background: rgba(34, 197, 94, 0.1); border: 1px solid rgba(34, 197, 94, 0.3); }
        .error-message { background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.3); }
        .success-message.show, .error-message.show { display: block; }
        .loading { display: inline-block; width: 20px; height: 20px; border: 3px solid rgba(255, 255, 255, 0.3); border-radius: 50%; border-top-color: white; animation: spin 1s ease-in-out infinite; }
        @keyframes spin { to { transform: rotate(360deg); } }
        .footer { padding: 40px 20px; text-align: center; background: rgba(12, 12, 12, 0.85); backdrop-filter: blur(15px); border-top: 1px solid rgba(255, 255, 255, 0.05); margin-top: 80px; font-size: 15px; color: rgba(255, 255, 255, 0.6); line-height: 1.6; }
        .footer p:first-child { font-weight: 500; margin-bottom: 8px; font-size: 16px; color: rgba(255, 255, 255, 0.8); }
        .footer a { color: #667eea; text-decoration: none; font-weight: 500; transition: color 0.3s ease; }
        .footer a:hover { color: #764ba2; }
        @media (max-width: 768px) { .department-grid { grid-template-columns: 1fr; } .page-title h2 { font-size: 36px; } .application-form { padding: 30px 20px; } }
    </style>
</head>
<body>
    <div class="background-grid"></div>
    <div class="floating-shapes" id="floatingShapes"></div>
    <nav class="navbar">
        <div class="nav-container">
            <div class="logo-container">
                <a href="/" class="logo"><div class="logo-wrapper"><img src="images/logo.png" alt="VDL Logo" class="logo-img"><span class="glow-cube"></span></div></a>
                <div class="logo-text"><div class="logo-main">VDL Roleplay</div></div>
            </div>
            <div class="user-info"><i class="fab fa-discord"></i><span>Welcome, <strong><?php echo htmlspecialchars($discord_user['username']); ?></strong></span></div>
        </div>
    </nav>
    <div class="container">
        <div class="page-title">
            <h2>Welcome, <span><?php echo htmlspecialchars($discord_user['username']); ?>!</span></h2>
            <p>Choose your desired department to start your journey in VDL Roleplay</p>
        </div>
        <div class="department-grid">
            <div class="department-card pd-card" onclick="selectDepartment('pd', this)"><div class="dept-icon"><i class="fas fa-shield-alt"></i></div><div class="dept-title">Police Department</div><div class="dept-description">Serve and protect the citizens. Join the LSPD and maintain law and order.</div></div>
            <div class="department-card ems-card" onclick="selectDepartment('ems', this)"><div class="dept-icon"><i class="fas fa-ambulance"></i></div><div class="dept-title">Medical Services</div><div class="dept-description">Save lives and provide medical assistance. Be the first responder in critical situations.</div></div>
            <div class="department-card gang-card" onclick="selectDepartment('gang', this)"><div class="dept-icon"><i class="fas fa-mask"></i></div><div class="dept-title">Gang Organization</div><div class="dept-description">Join organized crime and create your own criminal empire in the city.</div></div>
             <div class="department-card staff-card" onclick="selectDepartment('staff', this)"><div class="dept-icon"><i class="fas fa-crown"></i></div><div class="dept-title">Server Staff</div><div class="dept-description">Help manage the server and ensure everyone has a great roleplay experience.</div></div>
        </div>

        <form class="application-form" id="applicationForm" method="POST" action="submit_application.php">
            <input type="hidden" name="discord_id" value="<?php echo htmlspecialchars($discord_user['id']); ?>">
            <input type="hidden" name="discord_name" value="<?php echo htmlspecialchars($discord_user['username']); ?>">
            <input type="hidden" id="department" name="department">
            <div class="form-section">
                <h3><i class="fas fa-user"></i> Personal Information</h3>
                <div class="form-group"><label for="age">Age?</label><input type="number" id="age" name="age" min="16" max="99" required></div>
                <div class="form-group"><label for="timezone">Time-Zone? (Where do you live?)</label><select id="timezone" name="timezone" required><option value="">Select Timezone</option><option value="IST">IST (India Standard)</option><option value="EST">EST (Eastern)</option><option value="CST">CST (Central)</option><option value="PST">PST (Pacific)</option><option value="GMT">GMT (Greenwich)</option></select></div>
                <div class="form-group"><label for="availability">How much time can you dedicate to your role each week? (Minimum 15)</label><input type="number" id="availability" name="availability" min="15" required placeholder="e.g., 20"></div>
            </div>
            <div class="form-section">
                <h3><i class="fas fa-clipboard-list"></i> Application Questions</h3>
                <div class="form-group"><label for="experience">Past Experience in any organization? If yes, what ranks?</label><textarea id="experience" name="experience" required placeholder="Answers can be submitted in Hindi or Punjabi as well."></textarea></div>
                <div class="form-group"><label for="why_choose">Why should we choose you? How can you bring changes to our server? (100+ words)</label><textarea id="why_choose" name="why_choose" required minlength="100" placeholder="ਤੁਸੀਂ ਪੰਜਾਬੀ ਵਿੱਚ ਵੀ ਜਵਾਬ ਦੇ ਸਕਦੇ ਹੋ। (You can also reply in Punjabi)"></textarea></div>
            </div>
            <button type="submit" class="submit-btn" id="submitBtn"><i class="fas fa-paper-plane"></i> Submit Application</button>
        </form>
        
        <div class="success-message" id="successMessage">
            <i class="fas fa-check-circle" style="font-size: 48px; color: #22c55e; margin-bottom: 15px;"></i>
            <h3>Application Submitted Successfully!</h3>
            <p>Thank you. Our admin team will review it and get back to you soon.</p>
        </div>
        <div class="error-message" id="errorMessage">
            <i class="fas fa-times-circle" style="font-size: 48px; color: #ef4444; margin-bottom: 15px;"></i>
            <h3>Submission Failed!</h3>
            <p><?php echo htmlspecialchars($error_message); ?></p>
        </div>
    </div>
    
    <footer class="footer">
        <p>&copy; <span id="currentYear"></span> VDL RP - All rights reserved</p>
        <p>Have questions? Contact our staff on <a href="https://discord.gg/your-invite-code" target="_blank" rel="noopener noreferrer">Discord</a></p>
    </footer>

    <script>
        document.getElementById('currentYear').textContent = new Date().getFullYear();
        let selectedDepartment = null;
        const departmentConfig = { pd: { name: 'Police Department', color: '#1e40af' }, ems: { name: 'Medical Services', color: '#dc2626' }, gang: { name: 'Gang Organization', color: '#7c2d12' }, staff: { name: 'Server Staff', color: '#7c3aed' } };
        
        function createFloatingShapes() {
            const container = document.getElementById('floatingShapes');
            for (let i = 0; i < 6; i++) {
                const shape = document.createElement('div');
                shape.className = 'shape';
                shape.style.width = Math.random() * 100 + 50 + 'px';
                shape.style.height = shape.style.width;
                shape.style.left = Math.random() * 100 + '%';
                shape.style.top = Math.random() * 100 + '%';
                shape.style.animationDelay = Math.random() * 8 + 's';
                shape.style.animationDuration = (Math.random() * 4 + 6) + 's';
                container.appendChild(shape);
            }
        }
        
        function selectDepartment(dept, element) {
            document.querySelectorAll('.department-card').forEach(card => card.classList.remove('selected'));
            element.classList.add('selected');
            selectedDepartment = dept;
            const config = departmentConfig[dept];
            document.documentElement.style.setProperty('--selected-color', config.color);
            document.getElementById('department').value = config.name;
            const form = document.getElementById('applicationForm');
            if (!form.classList.contains('active')) {
                form.classList.add('active');
                form.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        }
        
        document.addEventListener('DOMContentLoaded', function() {
            createFloatingShapes();
            const urlParams = new URLSearchParams(window.location.search);
            const status = urlParams.get('status');
            const form = document.getElementById('applicationForm');
            
            if (status === 'success') {
                form.style.display = 'none';
                const successMessage = document.getElementById('successMessage');
                successMessage.classList.add('show');
                successMessage.scrollIntoView({ behavior: 'smooth', block: 'center' });
            } else if (status === 'error') {
                form.style.display = 'none';
                const errorMessage = document.getElementById('errorMessage');
                errorMessage.classList.add('show');
                errorMessage.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }

            form.addEventListener('submit', function(e) {
                if (!selectedDepartment) {
                    e.preventDefault();
                    alert('Please select a department first!');
                    return;
                }
                document.getElementById('submitBtn').disabled = true;
                document.getElementById('submitBtn').innerHTML = '<div class="loading"></div> Submitting...';
            });
        });
    </script>
</body>
</html>

