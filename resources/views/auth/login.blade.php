<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{ config('app.name') }} | Log in</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet"
    href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ asset('assets/plugins/fontawesome-free/css/all.min.css') }}">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="{{ asset('assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset('assets/dist/css/adminlte.min.css') }}">
</head>

<body class="hold-transition login-page">
  <div class="login-box">
    <div class="login-logo">
      {{-- Replace with your logo/text --}}
      <a href="{{ url('/') }}">Campus<b>Vibe</b></a>
    </div>

    <div class="card">
      <div class="card-body login-card-body">
        <p class="login-box-msg">{{ __('General User Login form') }}</p>

        {{-- Session Status --}}
        @if (session('status'))
        <div class="alert alert-success">
          {{ session('status') }}
        </div>
        @endif

        <form action="{{ route('login') }}" method="POST">

          @csrf

          {{-- Email --}}
          <div class="input-group mb-3">
            <input
              type="email"
              name="email"
              class="form-control @error('email') is-invalid @enderror"
              placeholder="{{ __('Email') }}"
              value="{{ old('email') }}"
              required
              autofocus>
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-envelope"></span>
              </div>
            </div>
            @error('email')
            <span class="invalid-feedback" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror
          </div>

          {{-- Password --}}
          <div class="input-group mb-3">
            <input
              type="password"
              name="password"
              class="form-control @error('password') is-invalid @enderror"
              placeholder="{{ __('Password') }}"
              required
              autocomplete="current-password">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-lock"></span>
              </div>
            </div>
            @error('password')
            <span class="invalid-feedback" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror
          </div>

          <div class="row">
            {{-- Remember Me --}}
            <div class="col-8">
              <div class="icheck-primary">
                <input
                  type="checkbox"
                  id="remember"
                  name="remember"
                  {{ old('remember') ? 'checked' : '' }}>
                <label for="remember">
                  {{ __('Remember Me') }}
                </label>
              </div>
            </div>

            {{-- Submit --}}
            <div class="col-4">
              <button type="submit" class="btn btn-primary btn-block">
                {{ __('Sign In') }}
              </button>
            </div>
          </div>
        </form>

        <p class="mb-1">
          @if (Route::has('password.request'))
          <a href="{{ route('password.request') }}">
            {{ __('I forgot my password') }}
          </a>
          @endif
        </p>
        <p class="mb-0">
          @if (Route::has('register'))
          <a href="{{ route('register') }}" class="text-center">
            {{ __('Register a new membership') }}
          </a>
          @endif
        </p>
      </div>
    </div>
  </div>

  <!-- REQUIRED SCRIPTS -->
  <script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script>
  <script src="{{ asset('assets/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset('assets/dist/js/adminlte.min.js') }}"></script>
</body>

</html>