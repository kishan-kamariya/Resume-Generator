<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register — Resume Generator</title>
    <meta name="description" content="Create your free account on Resume Generator">
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

        <h1 class="auth-title">Create Account</h1>
        <p class="auth-subtitle">Start building your professional resume today</p>

        @if($errors->any())
            <div class="alert alert-error" style="margin-bottom:20px;">
                <i class="fas fa-exclamation-circle"></i>
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('register.post') }}" id="register-form">
            @csrf

            <div class="form-group">
                <label class="form-label" for="reg-name">Full Name</label>
                <input
                    type="text"
                    name="name"
                    id="reg-name"
                    class="form-control"
                    value="{{ old('name') }}"
                    placeholder="John Doe"
                    required
                    autocomplete="name"
                >
            </div>

            <div class="form-group">
                <label class="form-label" for="reg-email">Email Address</label>
                <input
                    type="email"
                    name="email"
                    id="reg-email"
                    class="form-control"
                    value="{{ old('email') }}"
                    placeholder="you@example.com"
                    required
                    autocomplete="email"
                >
            </div>

            <div class="form-group">
                <label class="form-label" for="reg-password">Password</label>
                <div style="position:relative;">
                    <input
                        type="password"
                        name="password"
                        id="reg-password"
                        class="form-control"
                        placeholder="Min. 8 characters"
                        required
                        autocomplete="new-password"
                        style="padding-right: 42px;"
                    >
                    <button type="button" onclick="togglePassword('reg-password', this)"
                        style="position:absolute;right:12px;top:50%;transform:translateY(-50%);background:none;border:none;color:var(--text-muted);cursor:pointer;font-size:0.9rem;">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="reg-confirm">Confirm Password</label>
                <input
                    type="password"
                    name="password_confirmation"
                    id="reg-confirm"
                    class="form-control"
                    placeholder="Repeat password"
                    required
                    autocomplete="new-password"
                >
            </div>

            <button type="submit" class="btn btn-primary w-full btn-lg" id="btn-register-submit" style="margin-top:4px;">
                <i class="fas fa-rocket"></i> Create Account
            </button>
        </form>

        <div class="auth-divider"><span>Already have an account?</span></div>

        <a href="{{ route('login') }}" class="btn btn-secondary w-full" style="justify-content:center;">
            <i class="fas fa-sign-in-alt"></i> Sign In
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
