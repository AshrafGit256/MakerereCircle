<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Mak Social - Login</title>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
      background: linear-gradient(135deg, #DC143C 0%, #1a1a1a 50%, #228B22 100%);
      min-height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      padding: 20px;
      position: relative;
      overflow: hidden;
    }

    /* Animated background with all three colors */
    body::before {
      content: '';
      position: absolute;
      width: 600px;
      height: 600px;
      background: radial-gradient(circle, rgba(220, 20, 60, 0.3) 0%, transparent 70%);
      border-radius: 50%;
      top: -300px;
      right: -200px;
      animation: float 8s ease-in-out infinite;
    }

    body::after {
      content: '';
      position: absolute;
      width: 500px;
      height: 500px;
      background: radial-gradient(circle, rgba(34, 139, 34, 0.3) 0%, transparent 70%);
      border-radius: 50%;
      bottom: -250px;
      left: -150px;
      animation: float 10s ease-in-out infinite reverse;
    }

    @keyframes float {

      0%,
      100% {
        transform: translateY(0px) translateX(0px) rotate(0deg);
      }

      50% {
        transform: translateY(-30px) translateX(30px) rotate(10deg);
      }
    }

    .container {
      display: flex;
      max-width: 1200px;
      width: 100%;
      background: rgba(255, 255, 255, 0.98);
      border-radius: 24px;
      overflow: hidden;
      box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5);
      position: relative;
      z-index: 1;
      border: 3px solid transparent;
      background-clip: padding-box;
    }

    .container::before {
      content: '';
      position: absolute;
      inset: 0;
      border-radius: 24px;
      padding: 3px;
      background: linear-gradient(135deg, #DC143C, #1a1a1a, #228B22);
      -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
      -webkit-mask-composite: xor;
      mask-composite: exclude;
      z-index: -1;
    }

    /* Left Section - University Branding with All Three Colors */
    .branding-section {
      flex: 1;
      background: linear-gradient(180deg, #DC143C 0%, #1a1a1a 50%, #228B22 100%);
      padding: 60px 50px;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      text-align: center;
      color: white;
      position: relative;
      overflow: hidden;
    }

    .branding-section::before {
      content: '';
      position: absolute;
      width: 400px;
      height: 400px;
      background: rgba(255, 255, 255, 0.05);
      border-radius: 50%;
      top: -150px;
      right: -150px;
      border: 2px solid rgba(255, 255, 255, 0.1);
    }

    .branding-section::after {
      content: '';
      position: absolute;
      width: 300px;
      height: 300px;
      background: rgba(255, 255, 255, 0.05);
      border-radius: 50%;
      bottom: -100px;
      left: -100px;
      border: 2px solid rgba(255, 255, 255, 0.1);
    }

    .logo-container {
      margin-bottom: 40px;
      position: relative;
      z-index: 1;
      padding: 30px;
      background: rgba(255, 255, 255, 0.1);
      backdrop-filter: blur(10px);
      border-radius: 20px;
      border: 2px solid rgba(255, 255, 255, 0.2);
    }

    .logo-container img {
      max-width: 280px;
      height: auto;
      filter: brightness(0) invert(1);
    }

    .branding-section h1 {
      font-size: 42px;
      font-weight: 800;
      margin-bottom: 20px;
      position: relative;
      z-index: 1;
      text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.3);
      background: linear-gradient(90deg, #fff, #f0f0f0);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
    }

    .branding-section p {
      font-size: 18px;
      line-height: 1.6;
      margin-bottom: 15px;
      opacity: 0.95;
      position: relative;
      z-index: 1;
    }

    .university-stripe {
      display: flex;
      height: 8px;
      width: 100%;
      margin: 30px 0;
      border-radius: 4px;
      overflow: hidden;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
    }

    .stripe-red {
      flex: 1;
      background: #DC143C;
    }

    .stripe-black {
      flex: 1;
      background: #1a1a1a;
    }

    .stripe-green {
      flex: 1;
      background: #228B22;
    }

    .feature-badges {
      display: grid;
      grid-template-columns: repeat(2, 1fr);
      gap: 12px;
      margin-top: 30px;
      position: relative;
      z-index: 1;
      width: 100%;
    }

    .badge {
      background: rgba(255, 255, 255, 0.15);
      backdrop-filter: blur(10px);
      padding: 12px 16px;
      border-radius: 12px;
      font-size: 14px;
      font-weight: 600;
      border: 1px solid rgba(255, 255, 255, 0.25);
      transition: all 0.3s ease;
    }

    .badge:nth-child(1) {
      border-left: 4px solid #DC143C;
    }

    .badge:nth-child(2) {
      border-left: 4px solid #228B22;
    }

    .badge:nth-child(3) {
      border-left: 4px solid #1a1a1a;
    }

    .badge:nth-child(4) {
      border-left: 4px solid #DC143C;
    }

    .badge:hover {
      background: rgba(255, 255, 255, 0.25);
      transform: translateX(5px);
    }

    .stats {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 20px;
      margin-top: 40px;
      position: relative;
      z-index: 1;
      width: 100%;
    }

    .stat-item {
      text-align: center;
      padding: 20px 15px;
      background: rgba(255, 255, 255, 0.1);
      backdrop-filter: blur(10px);
      border-radius: 12px;
      border: 1px solid rgba(255, 255, 255, 0.2);
      transition: all 0.3s ease;
    }

    .stat-item:nth-child(1) {
      border-bottom: 3px solid #DC143C;
    }

    .stat-item:nth-child(2) {
      border-bottom: 3px solid #1a1a1a;
    }

    .stat-item:nth-child(3) {
      border-bottom: 3px solid #228B22;
    }

    .stat-item:hover {
      transform: translateY(-5px);
      background: rgba(255, 255, 255, 0.2);
    }

    .stat-number {
      font-size: 36px;
      font-weight: 800;
      display: block;
      margin-bottom: 5px;
    }

    .stat-label {
      font-size: 12px;
      opacity: 0.9;
      text-transform: uppercase;
      letter-spacing: 1px;
    }

    /* Right Section - Login Form */
    .form-section {
      flex: 1;
      padding: 60px 50px;
      display: flex;
      flex-direction: column;
      justify-content: center;
      background: white;
    }

    .form-header {
      margin-bottom: 40px;
    }

    .form-header h2 {
      font-size: 32px;
      font-weight: 700;
      background: linear-gradient(135deg, #DC143C 0%, #1a1a1a 50%, #228B22 100%);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
      margin-bottom: 10px;
    }

    .form-header p {
      color: #666;
      font-size: 16px;
    }

    .university-colors {
      display: flex;
      gap: 12px;
      margin-bottom: 25px;
      align-items: center;
    }

    .color-block {
      flex: 1;
      height: 6px;
      border-radius: 3px;
      position: relative;
      overflow: hidden;
    }

    .color-block::after {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.6), transparent);
      animation: shimmer 2s infinite;
    }

    @keyframes shimmer {
      100% {
        left: 100%;
      }
    }

    .color-red {
      background: #DC143C;
    }

    .color-black {
      background: #1a1a1a;
    }

    .color-green {
      background: #228B22;
    }

    .form-group {
      margin-bottom: 24px;
    }

    .form-group label {
      display: block;
      margin-bottom: 8px;
      font-weight: 600;
      font-size: 14px;
      background: linear-gradient(135deg, #DC143C, #1a1a1a);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
    }

    .input-wrapper {
      position: relative;
    }

    .input-icon {
      position: absolute;
      left: 16px;
      top: 50%;
      transform: translateY(-50%);
      font-size: 18px;
      z-index: 1;
    }

    .form-group input {
      width: 100%;
      padding: 14px 16px 14px 48px;
      border: 2px solid #e0e0e0;
      border-radius: 12px;
      font-size: 15px;
      transition: all 0.3s ease;
      background: #f8f9fa;
      position: relative;
    }

    .form-group input:focus {
      outline: none;
      border: 2px solid transparent;
      background: white;
      box-shadow: 0 0 0 3px rgba(220, 20, 60, 0.1);
      background-image: linear-gradient(white, white), linear-gradient(135deg, #DC143C, #1a1a1a, #228B22);
      background-origin: border-box;
      background-clip: padding-box, border-box;
    }

    .form-options {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 24px;
    }

    .remember-me {
      display: flex;
      align-items: center;
      gap: 8px;
      cursor: pointer;
    }

    .remember-me input[type="checkbox"] {
      width: 18px;
      height: 18px;
      cursor: pointer;
      accent-color: #DC143C;
    }

    .remember-me label {
      color: #666;
      font-size: 14px;
      cursor: pointer;
      user-select: none;
    }

    .forgot-link {
      color: #DC143C;
      text-decoration: none;
      font-size: 14px;
      font-weight: 600;
      transition: all 0.3s ease;
      position: relative;
    }

    .forgot-link::after {
      content: '';
      position: absolute;
      bottom: -2px;
      left: 0;
      width: 0;
      height: 2px;
      background: linear-gradient(90deg, #DC143C, #228B22);
      transition: width 0.3s ease;
    }

    .forgot-link:hover::after {
      width: 100%;
    }

    .btn-login {
      width: 100%;
      padding: 16px;
      background: linear-gradient(135deg, #DC143C 0%, #1a1a1a 50%, #228B22 100%);
      background-size: 200% 100%;
      color: white;
      border: none;
      border-radius: 12px;
      font-size: 16px;
      font-weight: 700;
      cursor: pointer;
      transition: all 0.4s ease;
      box-shadow: 0 4px 15px rgba(220, 20, 60, 0.3);
      text-transform: uppercase;
      letter-spacing: 1px;
      position: relative;
      overflow: hidden;
    }

    .btn-login::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
      transition: left 0.5s ease;
    }

    .btn-login:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 25px rgba(220, 20, 60, 0.4);
      background-position: 100% 0;
    }

    .btn-login:hover::before {
      left: 100%;
    }

    .btn-login:active {
      transform: translateY(0);
    }

    .divider {
      display: flex;
      align-items: center;
      margin: 30px 0;
      color: #999;
      font-size: 13px;
      font-weight: 600;
    }

    .divider::before,
    .divider::after {
      content: '';
      flex: 1;
      height: 2px;
      background: linear-gradient(90deg, transparent, #DC143C 20%, #1a1a1a 50%, #228B22 80%, transparent);
    }

    .divider::before {
      margin-right: 15px;
    }

    .divider::after {
      margin-left: 15px;
    }

    .social-login {
      display: flex;
      gap: 12px;
      margin-bottom: 30px;
    }

    .btn-social {
      flex: 1;
      padding: 12px;
      border: 2px solid #e0e0e0;
      border-radius: 12px;
      background: white;
      cursor: pointer;
      transition: all 0.3s ease;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      font-weight: 600;
      color: #666;
      position: relative;
    }

    .btn-social:nth-child(1):hover {
      border-color: #DC143C;
      background: #fff5f7;
      transform: translateY(-2px);
    }

    .btn-social:nth-child(2):hover {
      border-color: #228B22;
      background: #f5fff7;
      transform: translateY(-2px);
    }

    .signup-prompt {
      text-align: center;
      padding: 24px;
      background: linear-gradient(135deg, rgba(220, 20, 60, 0.05) 0%, rgba(26, 26, 26, 0.05) 50%, rgba(34, 139, 34, 0.05) 100%);
      border-radius: 12px;
      margin-top: 20px;
      border: 2px solid transparent;
      background-clip: padding-box;
      position: relative;
    }

    .signup-prompt::before {
      content: '';
      position: absolute;
      inset: 0;
      border-radius: 12px;
      padding: 2px;
      background: linear-gradient(135deg, #DC143C, #1a1a1a, #228B22);
      -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
      -webkit-mask-composite: xor;
      mask-composite: exclude;
      z-index: -1;
    }

    .signup-prompt p {
      color: #666;
      margin-bottom: 0;
      font-weight: 500;
    }

    .signup-link {
      background: linear-gradient(135deg, #DC143C, #228B22);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
      text-decoration: none;
      font-weight: 800;
      transition: all 0.3s ease;
      position: relative;
    }

    .signup-link:hover {
      letter-spacing: 0.5px;
    }

    /* Responsive Design */
    @media (max-width: 968px) {
      .container {
        flex-direction: column;
      }

      .branding-section {
        padding: 40px 30px;
      }

      .form-section {
        padding: 40px 30px;
      }

      .feature-badges {
        grid-template-columns: 1fr;
      }
    }

    @media (max-width: 480px) {
      .branding-section h1 {
        font-size: 32px;
      }

      .form-header h2 {
        font-size: 24px;
      }

      .social-login {
        flex-direction: column;
      }

      .stats {
        grid-template-columns: 1fr;
      }
    }

    /* Loading animation */
    .btn-login.loading {
      pointer-events: none;
    }

    .btn-login.loading::after {
      content: '';
      position: absolute;
      width: 20px;
      height: 20px;
      border: 3px solid rgba(255, 255, 255, 0.3);
      border-top-color: white;
      border-radius: 50%;
      animation: spin 0.8s linear infinite;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
    }

    @keyframes spin {
      to {
        transform: translate(-50%, -50%) rotate(360deg);
      }
    }

    /* Error message styling */
    .error-message {
      background: linear-gradient(135deg, rgba(220, 20, 60, 0.1), rgba(220, 20, 60, 0.05));
      color: #DC143C;
      padding: 14px;
      border-radius: 10px;
      margin-bottom: 20px;
      font-size: 14px;
      border-left: 4px solid #DC143C;
      font-weight: 500;
    }
  </style>
</head>

<body>
  <div class="container">
    <!-- Left Section - University Branding -->
    <div class="branding-section">
      <div class="logo-container">
        <img src="{{ asset('assets/MakSocial10.png') }}" alt="Mak Social Logo">
      </div>

      <h1>Welcome to Mak Social</h1>
      <p>Connect, Network, and Grow with the Makerere University Community</p>

      <div class="university-stripe">
        <div class="stripe-red"></div>
        <div class="stripe-black"></div>
        <div class="stripe-green"></div>
      </div>

      <p style="font-size: 15px; opacity: 0.95;">Your gateway to building meaningful connections with students, alumni, and professionals across Uganda</p>

      <div class="feature-badges">
        <div class="badge">🎓 Alumni Network</div>
        <div class="badge">💼 Career Hub</div>
        <div class="badge">🤝 Mentorship</div>
        <div class="badge">🌍 Regional Connect</div>
      </div>

      <div class="stats">
        <div class="stat-item">
          <span class="stat-number">5K+</span>
          <span class="stat-label">Students</span>
        </div>
        <div class="stat-item">
          <span class="stat-number">2K+</span>
          <span class="stat-label">Alumni</span>
        </div>
        <div class="stat-item">
          <span class="stat-number">500+</span>
          <span class="stat-label">Companies</span>
        </div>
      </div>
    </div>

    <!-- Right Section - Login Form -->
    <div class="form-section">
      <div class="form-header">
        <div class="university-colors">
          <div class="color-block color-red"></div>
          <div class="color-block color-black"></div>
          <div class="color-block color-green"></div>
        </div>
        <h2>Sign in to your account</h2>
        <p>Enter your credentials to access your network</p>
      </div>

      @if ($errors->any())
      <div class="error-message">
        <strong>⚠️ Error:</strong> {{ $errors->first() }}
      </div>
      @endif

      <form action="{{ route('login') }}" method="POST">
        @csrf

        <div class="form-group">
          <label for="email">Email Address</label>
          <div class="input-wrapper">
            <span class="input-icon">✉️</span>
            <input
              type="email"
              id="email"
              name="email"
              placeholder="your.email@mak.ac.ug"
              value="{{ old('email') }}"
              required
              autofocus>
          </div>
        </div>

        <div class="form-group">
          <label for="password">Password</label>
          <div class="input-wrapper">
            <span class="input-icon">🔒</span>
            <input
              type="password"
              id="password"
              name="password"
              placeholder="Enter your password"
              required
              autocomplete="current-password">
          </div>
        </div>

        <div class="form-options">
          <div class="remember-me">
            <input
              type="checkbox"
              id="remember"
              name="remember"
              {{ old('remember') ? 'checked' : '' }}>
            <label for="remember">Remember me</label>
          </div>
          @if (Route::has('password.request'))
          <a href="{{ route('password.request') }}" class="forgot-link">
            Forgot password?
          </a>
          @endif
        </div>

        <button type="submit" class="btn-login">
          Sign In
        </button>
      </form>

      <div class="divider">
        <span>OR CONTINUE WITH</span>
      </div>

      <div class="social-login">
        <button class="btn-social">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="#4285F4">
            <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" />
            <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853" />
            <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05" />
            <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335" />
          </svg>
          Google
        </button>
        <button class="btn-social">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="#1877F2">
            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
          </svg>
          Facebook
        </button>
      </div>

      <div class="signup-prompt">
        <p>
          Don't have an account?
          @if (Route::has('register'))
          <a href="{{ route('register') }}" class="signup-link">
            Create your account here →
          </a>
          @endif
        </p>
      </div>
    </div>
  </div>

  <script>
    // Add loading state to button on form submit
    document.querySelector('form').addEventListener('submit', function() {
      const btn = document.querySelector('.btn-login');
      btn.classList.add('loading');
      btn.textContent = '';
    });

    // Input focus animation
    const inputs = document.querySelectorAll('input');
    inputs.forEach(input => {
      input.addEventListener('focus', function() {
        this.parentElement.style.transform = 'scale(1.01)';
        this.parentElement.style.transition = 'transform 0.3s ease';
      });
      input.addEventListener('blur', function() {
        this.parentElement.style.transform = 'scale(1)';
      });
    });
  </script>
</body>

</html>