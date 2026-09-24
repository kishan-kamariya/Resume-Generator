<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ResumeAI — AI Resume Analyzer & Smart Builder</title>
    <meta name="description" content="Build, optimize and analyze ATS-friendly resumes with AI assistance. Free, fast and professional.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Outfit:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <style>
        body {
            display: block !important;
            background: #0a0e1a;
            color: #f1f5f9;
            font-family: 'Inter', sans-serif;
            overflow-x: hidden;
            min-height: 100vh;
        }

        /* Ambient Glows */
        .landing-bg-glow {
            position: fixed;
            pointer-events: none;
            border-radius: 50%;
            filter: blur(120px);
            z-index: 0;
        }
        .glow-1 {
            width: 600px;
            height: 600px;
            top: -150px;
            left: -100px;
            background: radial-gradient(circle, rgba(108, 99, 255, 0.22) 0%, transparent 70%);
        }
        .glow-2 {
            width: 500px;
            height: 500px;
            top: 40%;
            right: -150px;
            background: radial-gradient(circle, rgba(6, 182, 212, 0.16) 0%, transparent 70%);
        }
        .glow-3 {
            width: 650px;
            height: 650px;
            bottom: -150px;
            left: 25%;
            background: radial-gradient(circle, rgba(167, 139, 250, 0.18) 0%, transparent 70%);
        }

        /* Nav Header */
        .portal-nav {
            position: sticky;
            top: 0;
            z-index: 100;
            background: rgba(10, 14, 26, 0.85);
            backdrop-filter: blur(16px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            padding: 14px 28px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .portal-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
        }

        .portal-brand .brand-badge {
            font-size: 0.72rem;
            padding: 2px 8px;
            border-radius: 20px;
            background: rgba(108, 99, 255, 0.2);
            border: 1px solid rgba(108, 99, 255, 0.4);
            color: #a78bfa;
            font-weight: 600;
        }

        .portal-links {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .portal-link {
            color: #94a3b8;
            font-size: 0.88rem;
            font-weight: 500;
            transition: all 0.2s ease;
            text-decoration: none;
        }
        .portal-link:hover {
            color: #fff;
        }

        /* Hero Informatics Section */
        .info-hero {
            position: relative;
            z-index: 1;
            padding: 56px 24px 40px;
            max-width: 1200px;
            margin: 0 auto;
            text-align: center;
        }

        .hero-chip {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 6px 16px;
            background: rgba(108, 99, 255, 0.14);
            border: 1px solid rgba(108, 99, 255, 0.35);
            border-radius: 999px;
            color: #c4b5fd;
            font-size: 0.82rem;
            font-weight: 600;
            margin-bottom: 20px;
            animation: pulseChip 3s infinite alternate;
        }

        @keyframes pulseChip {
            0% { box-shadow: 0 0 0 rgba(108, 99, 255, 0); }
            100% { box-shadow: 0 0 20px rgba(108, 99, 255, 0.35); }
        }

        .info-hero h1 {
            font-family: 'Outfit', sans-serif;
            font-size: clamp(2.2rem, 5vw, 3.4rem);
            font-weight: 800;
            line-height: 1.15;
            margin-bottom: 16px;
            letter-spacing: -0.5px;
        }

        .gradient-text {
            background: linear-gradient(135deg, #a78bfa 0%, #6c63ff 50%, #38bdf8 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .info-hero p {
            max-width: 720px;
            margin: 0 auto 36px;
            font-size: 1.05rem;
            color: #94a3b8;
            line-height: 1.6;
        }

        /* Quick Feature Grid */
        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 20px;
            max-width: 1200px;
            margin: 0 auto 50px;
            padding: 0 20px;
            position: relative;
            z-index: 1;
        }

        .info-card {
            background: rgba(21, 28, 46, 0.65);
            backdrop-filter: blur(14px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 16px;
            padding: 24px;
            text-align: left;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .info-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 2px;
            background: linear-gradient(90deg, transparent, var(--accent-primary), transparent);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .info-card:hover {
            transform: translateY(-4px);
            border-color: rgba(108, 99, 255, 0.4);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.4), 0 0 25px rgba(108, 99, 255, 0.15);
        }
        .info-card:hover::before {
            opacity: 1;
        }

        .info-icon-box {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
            margin-bottom: 16px;
        }
        .icon-purple { background: rgba(108, 99, 255, 0.18); color: #a78bfa; }
        .icon-cyan   { background: rgba(6, 182, 212, 0.18); color: #22d3ee; }
        .icon-green  { background: rgba(16, 185, 129, 0.18); color: #34d399; }
        .icon-orange { background: rgba(245, 158, 11, 0.18); color: #fbbf24; }

        .info-card h3 {
            font-family: 'Outfit', sans-serif;
            font-size: 1.15rem;
            font-weight: 700;
            color: #f1f5f9;
            margin-bottom: 8px;
        }

        .info-card p {
            font-size: 0.88rem;
            color: #94a3b8;
            line-height: 1.5;
        }

        /* Metrics Bar */
        .metrics-strip {
            display: flex;
            align-items: center;
            justify-content: center;
            flex-wrap: wrap;
            gap: 36px;
            padding: 20px;
            margin: 0 auto 50px;
            max-width: 960px;
            background: rgba(17, 24, 39, 0.5);
            border: 1px solid rgba(255, 255, 255, 0.06);
            border-radius: 20px;
            position: relative;
            z-index: 1;
        }

        .metric-item {
            text-align: center;
            padding: 0 10px;
        }
        .metric-val {
            font-family: 'Outfit', sans-serif;
            font-size: 1.7rem;
            font-weight: 800;
            color: #f1f5f9;
        }
        .metric-lbl {
            font-size: 0.78rem;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 600;
            margin-top: 2px;
        }

        /* Auth Section */
        .auth-section {
            position: relative;
            z-index: 2;
            padding: 20px 20px 80px;
            max-width: 480px;
            margin: 0 auto;
        }

        .auth-portal-card {
            background: rgba(21, 28, 46, 0.85);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border: 1px solid rgba(108, 99, 255, 0.25);
            border-radius: 24px;
            padding: 36px 32px;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.5), 0 0 40px rgba(108, 99, 255, 0.12);
        }

        .auth-section-title {
            text-align: center;
            font-family: 'Outfit', sans-serif;
            font-size: 1.5rem;
            font-weight: 700;
            color: #f1f5f9;
            margin-bottom: 6px;
        }
        .auth-section-subtitle {
            text-align: center;
            font-size: 0.88rem;
            color: #94a3b8;
            margin-bottom: 24px;
        }

        .scroll-down-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-top: 8px;
        }

        @media (max-width: 768px) {
            .portal-links { display: none; }
            .info-hero { padding: 36px 16px 24px; }
            .features-grid { grid-template-columns: 1fr; }
            .metrics-strip { gap: 20px; }
            .auth-portal-card { padding: 28px 20px; }
        }
    </style>
</head>
<body>

    <!-- Ambient Glowing Orbs -->
    <div class="landing-bg-glow glow-1"></div>
    <div class="landing-bg-glow glow-2"></div>
    <div class="landing-bg-glow glow-3"></div>

    <!-- Navigation Header -->
    <header class="portal-nav">
        <a href="{{ route('login') }}" class="portal-brand">
            <div class="logo-icon"><i class="fas fa-file-alt"></i></div>
            <span class="logo-text">ResumeAI</span>
            <span class="brand-badge"><i class="fas fa-sparkles"></i> AI Powered</span>
        </a>
        <div class="portal-links">
            <a href="#features" class="portal-link"><i class="fas fa-cubes"></i> Features</a>
            <a href="#about" class="portal-link"><i class="fas fa-shield-alt"></i> ATS Scoring</a>
            <a href="#auth-box" class="btn btn-primary btn-sm scroll-down-btn">
                <i class="fas fa-sign-in-alt"></i> Sign In
            </a>
        </div>
    </header>

    <!-- INFORMATICS SECTION -->
    <section class="info-hero" id="about">
        <div class="hero-chip">
            <i class="fas fa-robot"></i> Smart AI Resume Engine & ATS Optimizer
        </div>
        <h1>
            Create Resumes That <span class="gradient-text">Get You Hired</span>
        </h1>
        <p>
            ResumeAI combines intelligent ATS optimization, automated completeness checking, and real-time AI guidance so you can build standout resumes and land dream interviews.
        </p>
        <div style="display: flex; justify-content: center; gap: 14px; flex-wrap: wrap;">
            <a href="#auth-box" class="btn btn-primary btn-lg">
                <i class="fas fa-rocket"></i> Get Started Now
            </a>
            <a href="#features" class="btn btn-secondary btn-lg">
                <i class="fas fa-layer-group"></i> Explore Capabilities
            </a>
        </div>
    </section>

    <!-- METRICS STRIP -->
    <div class="metrics-strip">
        <div class="metric-item">
            <div class="metric-val" style="color:#34d399;">99%</div>
            <div class="metric-lbl">ATS Parsability</div>
        </div>
        <div class="metric-item">
            <div class="metric-val" style="color:#a78bfa;">Instant</div>
            <div class="metric-lbl">AI Completeness Score</div>
        </div>
        <div class="metric-item">
            <div class="metric-val" style="color:#38bdf8;">3 Styles</div>
            <div class="metric-lbl">Industry Ready Templates</div>
        </div>
        <div class="metric-item">
            <div class="metric-val" style="color:#fbbf24;">1-Click</div>
            <div class="metric-lbl">PDF Export & Preview</div>
        </div>
    </div>

    <!-- FEATURES & DETAILS GRID -->
    <section class="features-grid" id="features">
        <div class="info-card">
            <div class="info-icon-box icon-purple">
                <i class="fas fa-tachometer-alt"></i>
            </div>
            <h3>ATS Score & Gap Analysis</h3>
            <p>
                Get real-time feedback on your keywords, structural formatting, contact info, and bullet strength to easily pass recruiter filters.
            </p>
        </div>

        <div class="info-card">
            <div class="info-icon-box icon-cyan">
                <i class="fas fa-robot"></i>
            </div>
            <h3>AI Career Assistant</h3>
            <p>
                Interactive AI chatbot suggests powerful action verbs, optimizes work experiences, and tailors summary sections for your target role.
            </p>
        </div>

        <div class="info-card">
            <div class="info-icon-box icon-green">
                <i class="fas fa-palette"></i>
            </div>
            <h3>Professional Templates</h3>
            <p>
                Choose between Classic Corporate, Tech Modern, or Executive Minimal layouts designed specifically to highlight your accomplishments.
            </p>
        </div>

        <div class="info-card">
            <div class="info-icon-box icon-orange">
                <i class="fas fa-check-double"></i>
            </div>
            <h3>Completeness Checker</h3>
            <p>
                Section-by-section progress audit ensuring you never miss critical items like certifications, projects, languages, or links.
            </p>
        </div>
    </section>

    <!-- AUTHENTICATION SECTION (BELOW INFORMATICS) -->
    <section class="auth-section" id="auth-box">
        <div class="auth-portal-card">
            <div class="auth-logo" style="margin-bottom: 20px;">
                <div class="logo-icon"><i class="fas fa-user-circle"></i></div>
                <span class="logo-text">Account Access</span>
            </div>

            <h2 class="auth-section-title">Welcome Back</h2>
            <p class="auth-section-subtitle">Sign in to your account or create a new one below</p>

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
                    <i class="fas fa-sign-in-alt"></i> Sign In to ResumeAI
                </button>
            </form>

            <div class="auth-divider"><span>New to ResumeAI?</span></div>

            <a href="{{ route('register') }}" class="btn btn-secondary w-full" style="justify-content:center;">
                <i class="fas fa-user-plus"></i> Create New Account
            </a>
        </div>
    </section>

    <!-- Footer -->
    <footer style="text-align: center; padding: 24px; color: var(--text-muted); font-size: 0.82rem; border-top: 1px solid rgba(255, 255, 255, 0.05); position: relative; z-index: 1;">
        <p>&copy; {{ date('Y') }} ResumeAI — AI Resume Generator & Analyzer. All rights reserved.</p>
    </footer>

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
