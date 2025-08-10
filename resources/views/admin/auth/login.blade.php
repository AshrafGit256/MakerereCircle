<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Makerere Circle | Admin Login</title>

  <!-- AdminLTE & dependencies -->
  <link rel="stylesheet"
    href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <link rel="stylesheet"
    href="{{ asset('assets/plugins/fontawesome-free/css/all.min.css') }}">
  <link rel="stylesheet"
    href="{{ asset('assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
  <link rel="stylesheet"
    href="{{ asset('assets/dist/css/adminlte.min.css') }}">

  <!-- Your custom admin auth overrides -->
  <link rel="stylesheet" href="{{ asset('css/admin-auth.css') }}">

</head>

<body class="hold-transition login-page admin-login">
  <div class="login-box">
    <div class="login-logo">
      <a href="#"><b>Lost</b>&FOUND</a>
    </div>

    <div class="card admin-card">
      <div class="card-body login-card-body">
        <p class="login-box-msg">Restricted to admin use only</p>

        @include('admin.layouts._message')

        <form action="#" method="POST">
          @csrf

          <div class="input-group mb-3">
            <input type="email"
              class="form-control @error('email') is-invalid @enderror"
              name="email"
              required
              placeholder="Email">
            <div class="input-group-append">
              <div class="input-group-text">
                <i class="fas fa-envelope"></i>
              </div>
            </div>
            @error('email')
            <span class="invalid-feedback" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror
          </div>

          <div class="input-group mb-3">
            <input type="password"
              class="form-control @error('password') is-invalid @enderror"
              name="password"
              required
              placeholder="Password">
            <div class="input-group-append">
              <div class="input-group-text">
                <i class="fas fa-lock"></i>
              </div>
            </div>
            @error('password')
            <span class="invalid-feedback" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror
          </div>

          <div class="row">
            <div class="col-8">
              <div class="icheck-primary">
                <input type="checkbox" id="remember" name="remember">
                <label for="remember">
                  Remember Me
                </label>
              </div>
            </div>

            <div class="col-4">
              <button type="submit" class="btn btn-primary btn-block">
                Sign In
              </button>
            </div>
          </div>
        </form>

        <p class="mb-1 mt-3">
          <a href="#">I forgot my password</a>
        </p>

      </div>
    </div>
  </div>

  <!-- Scripts -->
  <script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script>
  <script src="{{ asset('assets/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset('assets/dist/js/adminlte.min.js') }}"></script>
</body>

</html>