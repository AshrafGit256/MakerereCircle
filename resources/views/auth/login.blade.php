<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Mak Social</title>
  <style>
    /* This CSS block contains all the styling for the page */
    body {
      font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
      background-color: #f0f2f5;
      /* Lighter background for a cleaner look */
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
      margin: 0;
      flex-direction: column;
      color: #1a1a1a;
      /* Main text color is black */
    }

    .container {
      display: flex;
      justify-content: center;
      align-items: center;
      width: 100%;
      max-width: 935px;
      padding: 0 20px;
    }

    .image-section {
      position: relative;
      width: 380px;
      height: 580px;
      margin-right: 32px;
      /* Placeholder for the phone graphic, could be a custom image */
      background-image: url('https://static.cdninstagram.com/images/instagram/xig/homepage/phones/home-phones.png?__d=1');
      background-size: cover;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .phone-slides {
      position: absolute;
      top: 27px;
      left: 113px;
      width: 250px;
      height: 540px;
      overflow: hidden;
      border-radius: 12px;
      /* Added rounded corners to the sliding images */
    }

    .phone-slides img {
      position: absolute;
      width: 100%;
      opacity: 0;
      transition: opacity 1s ease-in-out;
    }

    .phone-slides img.active {
      opacity: 1;
    }

    .form-section {
      width: 350px;
      display: flex;
      flex-direction: column;
      align-items: center;
    }

    .form-box {
      background-color: #fff;
      border: 1px solid #c9c9c9;
      /* Slightly darker border for contrast */
      border-radius: 8px;
      /* More prominent rounded corners */
      padding: 40px;
      text-align: center;
      margin-bottom: 10px;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
      /* Subtle shadow for a modern look */
    }

    .form-box h1 {
      font-family: 'Billabong', cursive;
      font-size: 52px;
      margin-bottom: 20px;
      color: #1a1a1a;
    }

    .form-box input {
      width: 100%;
      padding: 12px;
      /* Increased padding for better touch targets */
      margin-bottom: 8px;
      border: 1px solid #dcdcdc;
      border-radius: 6px;
      box-sizing: border-box;
      background-color: #f7f7f7;
    }

    .form-box input:focus {
      outline: none;
      border-color: #a0a0a0;
      box-shadow: 0 0 0 1px #a0a0a0;
    }

    .form-box button {
      width: 100%;
      padding: 10px;
      background-color: #C00000;
      /* Primary button color: Red */
      color: #fff;
      border: none;
      border-radius: 6px;
      font-weight: bold;
      cursor: pointer;
      margin-top: 10px;
      transition: background-color 0.3s ease;
    }

    .form-box button:hover {
      background-color: #990000;
      /* Darker red on hover */
    }

    .form-box .separator {
      display: flex;
      align-items: center;
      text-align: center;
      margin: 20px 0;
      color: #a0a0a0;
      font-weight: bold;
      font-size: 14px;
      text-transform: uppercase;
    }

    .form-box .separator::before,
    .form-box .separator::after {
      content: '';
      flex: 1;
      border-bottom: 1px solid #dcdcdc;
    }

    .form-box .separator:not(:empty)::before {
      margin-right: .25em;
    }

    .form-box .separator:not(:empty)::after {
      margin-left: .25em;
    }

    .form-box .social-login {
      color: #006400;
      /* Green for the social login link */
      font-weight: bold;
      text-decoration: none;
      display: flex;
      align-items: center;
      justify-content: center;
      margin-top: 10px;
      transition: color 0.3s ease;
    }

    .form-box .social-login:hover {
      color: #004d00;
    }

    .form-box .social-login svg {
      margin-right: 8px;
    }

    .form-box a {
      color: #C00000;
      /* Red for the "forgot password" link */
      text-decoration: none;
      font-size: 12px;
      margin-top: 15px;
      display: block;
      transition: color 0.3s ease;
    }

    .form-box a:hover {
      color: #990000;
    }

    .signup-box {
      background-color: #fff;
      border: 1px solid #c9c9c9;
      border-radius: 8px;
      padding: 20px;
      text-align: center;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }

    .signup-box p {
      margin: 0;
      color: #1a1a1a;
      font-size: 14px;
    }

    .signup-box a {
      color: #006400;
      /* Green for the "sign up" link */
      text-decoration: none;
      font-weight: bold;
    }

    .footer {
      margin-top: 20px;
      text-align: center;
      color: #6b6b6b;
      font-size: 12px;
    }

    .footer .links {
      margin-bottom: 15px;
    }

    .footer .links a {
      color: #6b6b6b;
      text-decoration: none;
      margin: 0 8px;
    }

    .footer .copyright {
      margin-top: 15px;
    }
  </style>
</head>

<body>
  <div class="container">
    <div class="image-section">
      <div class="phone-slides">
        <!-- Dummy images to simulate the sliding effect. Using placeholder images for now -->
        <img class="active" src="https://via.placeholder.com/250x540/C00000/ffffff?text=Mak+Social" alt="Mak Social screenshot 1">
        <img src="https://via.placeholder.com/250x540/006400/ffffff?text=Mak+Social" alt="Mak Social screenshot 2">
        <img src="https://via.placeholder.com/250x540/1a1a1a/ffffff?text=Mak+Social" alt="Mak Social screenshot 3">
        <img src="https://via.placeholder.com/250x540/C00000/ffffff?text=Mak+Social" alt="Mak Social screenshot 4">
      </div>
    </div>
    <div class="form-section">
      <div class="form-box">
        <img src="{{ asset('assets/MakSocial8.png') }}" alt="Mak Social" class="logo" style="width: 260px; height: auto">
        <form action="#" method="POST">
          <!-- The form inputs are set up here. You can add Blade directives here if needed. -->
          <input type="text" name="username" placeholder="Student number or email" required>
          <input type="password" name="password" placeholder="Password" required>
          <button type="submit">Log In</button>
        </form>
        <div class="separator">OR</div>
        <a href="#" class="social-login">
          Log in with University ID
        </a>
        <a href="#">Forgotten your password?</a>
      </div>
      <div class="signup-box">
        <p>Don't have a Mak Social account? <a href="#">Sign up here</a></p>
      </div>
    </div>
  </div>
  <footer class="footer">
    <div class="links">
      <a href="#">About Mak Social</a>
      <a href="#">Help Center</a>
      <a href="#">Privacy</a>
      <a href="#">Terms</a>
      <a href="#">Careers</a>
      <a href="#">API</a>
    </div>
    <div class="copyright">
      <span>English (UK)</span>
      <span>&copy; 2025 Mak Social from Makerere University</span>
    </div>
  </footer>

  <script>
    // This JavaScript handles the sliding images on the left side.
    document.addEventListener('DOMContentLoaded', function() {
      const slides = document.querySelectorAll('.phone-slides img');
      let currentSlide = 0;

      function nextSlide() {
        slides[currentSlide].classList.remove('active');
        currentSlide = (currentSlide + 1) % slides.length;
        slides[currentSlide].classList.add('active');
      }

      // Change slide every 5 seconds
      setInterval(nextSlide, 5000);
    });
  </script>
</body>

</html>