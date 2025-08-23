<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

  <!-- External Custom CSS (pakai secure_asset biar https) -->
  <link href="{{ secure_asset('css/login-style.css') }}" rel="stylesheet">
</head>
<body class="login-body">

  <div class="text-center">
    <!-- Login Card -->
    <form method="POST" action="{{ route('login') }}" class="login-card text-start">
      @csrf

      <!-- Logo di dalam form -->
      <div class="text-center mb-3">
        <img src="{{ secure_asset('images/logokosgoro.png') }}" alt="Logo Kosgoro" class="login-logo">
      </div>

      <h4 class="text-center text-orange-custom mb-4">LOGIN KOPERASI</h4>

      @error('email')
        <div class="alert alert-danger">{{ $message }}</div>
      @enderror

      <!-- Username -->
      <div class="mb-3 position-relative">
        <i class="bi bi-person form-icon"></i>
        <input type="email" name="email" class="form-control login-input" placeholder="Username" required>
      </div>

      <!-- Password -->
      <div class="mb-4 position-relative">
        <i class="bi bi-lock form-icon"></i>
        <input type="password" name="password" class="form-control login-input" placeholder="Password" required>
      </div>

      <!-- Submit -->
      <button type="submit" class="btn btn-orange-custom w-100">LOGIN</button>
    </form>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
