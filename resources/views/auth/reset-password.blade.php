<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Reset Password | Mak<b> Social</b></title>

  <!-- Google Font -->
  <link rel="stylesheet"
    href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet"
    href="{{ asset('assets/plugins/fontawesome-free/css/all.min.css') }}">
  <!-- icheck bootstrap -->
  <link rel="stylesheet"
    href="{{ asset('assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
  <!-- AdminLTE -->
  <link rel="stylesheet" href="{{ asset('assets/dist/css/adminlte.min.css') }}">
</head>

<body class="hold-transition login-page">
  <div class="login-box">
    <div class="login-logo">
      <a href="#">Mak<b>Social</b></a>
    </div>

    <div class="card">
      <div class="card-body login-card-body">
        <p class="login-box-msg">You’re only one step away from your new password</p>

        <form method="POST" action="{{ route('password.store') }}">
          @csrf

          <!-- Reset Token -->
          <input type="hidden"
            name="token"
            value="{{ $request->route('token') }}">

          <!-- Email (hidden) -->
          <input type="hidden"
            name="email"
            value="{{ old('email', $request->email) }}">

          @error('email')
          <div class="alert alert-danger">{{ $message }}</div>
          @enderror

          <!-- New Password -->
          <div class="input-group mb-3">
            <input type="password"
              name="password"
              class="form-control @error('password') is-invalid @enderror"
              placeholder="New Password"
              required
              autocomplete="new-password">
            <div class="input-group-append">
              <div class="input-group-text"><span class="fas fa-lock"></span></div>
            </div>
            @error('password')
            <span class="invalid-feedback d-block" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror
          </div>

          <!-- Confirm Password -->
          <div class="input-group mb-3">
            <input type="password"
              name="password_confirmation"
              class="form-control @error('password_confirmation') is-invalid @enderror"
              placeholder="Confirm Password"
              required
              autocomplete="new-password">
            <div class="input-group-append">
              <div class="input-group-text"><span class="fas fa-lock"></span></div>
            </div>
            @error('password_confirmation')
            <span class="invalid-feedback d-block" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror
          </div>

          <!-- Submit -->
          <div class="row">
            <div class="col-12">
              <button type="submit"
                class="btn btn-primary btn-block">
                Reset Password
              </button>
            </div>
          </div>
        </form>

        <p class="mt-3 mb-1">
          <a href="{{ route('login') }}">Login</a>
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