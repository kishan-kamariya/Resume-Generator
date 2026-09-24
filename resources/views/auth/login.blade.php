<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — Resume Generator</title>
    <meta name="description" content="Sign in to Resume Generator to build your professional resume with AI assistance">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Outfit:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <style>
        body { display: block; }
        .auth-body { display: flex; }
    </style>
</head>
<body class="auth-body">
    <div class="auth-bg">
        <div class="auth-bg-circle c1"></div>
        <div class="auth-bg-circle c2"></div>
    </div>

    <div class="auth-card">
        <div class="auth-logo">
            <div class="logo-icon"><i class="fas fa-file-alt"></i></div>
            <span class="logo-text">ResumeAI</span>
        </div>

        <h1 class="auth-title">Welcome Back</h1>
        <p class="auth-subtitle">Sign in to continue building your career</p>

        @if($errors->any())
            <div class="alert alert-error" style="margin-bottom:20px;">
                <i class="fas fa-exclamation-circle"></i>
                {{ $errors->first() }}
            </div>
        @endif

        @if(session('success'))
            <div class="alert alert-success" style="margin-bottom:20px;">
                <i class="fas fa-check-circle"></i>
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login.post') }}" id="login-form">
            @csrf

            <div class="form-group">
                <label class="form-label" for="login-email">Email Address</label>
                <input
                    type="email"
                    name="email"
                    id="login-email"
                    class="form-control"
                    value="{{ old('email') }}"
                    placeholder="you@example.com"
                    required
                    autocomplete="email"
                >
            </div>

            <div class="form-group">
                <label class="form-label" for="login-password">Password</label>
                <div style="position:relative;">
                    <input
                        type="password"
                        name="password"
                        id="login-password"
                        class="form-control"
                        placeholder="••••••••"
                        required
                        autocomplete="current-password"
                        style="padding-right: 42px;"
                    >
                    <button type="button" onclick="togglePassword('login-password', this)"
                        style="position:absolute;right:12px;top:50%;transform:translateY(-50%);background:none;border:none;color:var(--text-muted);cursor:pointer;font-size:0.9rem;">
                        <i class="fas fa-eye" id="eye-login-password"></i>
                    </button>
                </div>
            </div>

            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:24px;">
                <label class="form-check" for="remember">
                    <input type="checkbox" id="remember" name="remember">
                    Remember me
                </label>
            </div>

            <button type="submit" class="btn btn-primary w-full btn-lg" id="btn-login-submit">
                <i class="fas fa-sign-in-alt"></i> Sign In
            </button>
        </form>

        <div class="auth-divider"><span>Don't have an account?</span></div>

        <a href="{{ route('register') }}" class="btn btn-secondary w-full" style="justify-content:center;">
            <i class="fas fa-user-plus"></i> Create Account
        </a>
    </div>

    <script>
    function togglePassword(id, btn) {
        const input = document.getElementById(id);
        const icon  = btn.querySelector('i');
        if (input.type === 'password') {
            input.type = 'text';
            icon.className = 'fas fa-eye-slash';
        } else {
            input.type = 'password';
            icon.className = 'fas fa-eye';
        }
    }
    </script>
</body>
</html>
