<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Resume Generator - Build professional resumes in minutes with AI assistance">
    <title>@yield('title', 'Dashboard') — Resume Generator</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Outfit:wght@400;600;700;800&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- App CSS -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    @stack('styles')
</head>
<body>

<!-- Sidebar -->
<aside class="sidebar" id="sidebar">
    <div class="sidebar-logo">
        <div class="logo-icon"><i class="fas fa-file-alt"></i></div>
        <span class="logo-text">ResumeAI</span>
    </div>

    <nav class="sidebar-nav">
        <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}" id="nav-dashboard">
            <i class="fas fa-th-large"></i>
            <span>Dashboard</span>
        </a>
        <a href="{{ route('resume.create') }}" class="nav-item {{ request()->routeIs('resume.create') ? 'active' : '' }}" id="nav-create">
            <i class="fas fa-plus-circle"></i>
            <span>New Resume</span>
        </a>
        <a href="#" class="nav-item" id="nav-chatbot-open" onclick="toggleChatbot(); return false;">
            <i class="fas fa-robot"></i>
            <span>AI Assistant</span>
            <span class="nav-badge">AI</span>
        </a>
    </nav>

    <div class="sidebar-footer">
        <div class="user-info">
            <div class="user-avatar">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>
            <div class="user-details">
                <span class="user-name">{{ Auth::user()->name }}</span>
                <span class="user-email">{{ Auth::user()->email }}</span>
            </div>
        </div>
        <form method="POST" action="{{ route('logout') }}" id="logout-form">
            @csrf
            <button type="submit" class="logout-btn" id="btn-logout" title="Logout">
                <i class="fas fa-sign-out-alt"></i>
            </button>
        </form>
    </div>
</aside>

<!-- Main Content -->
<div class="main-wrapper">
    <!-- Top Bar -->
    <header class="topbar">
        <button class="sidebar-toggle" id="sidebar-toggle" onclick="document.getElementById('sidebar').classList.toggle('open')">
            <i class="fas fa-bars"></i>
        </button>
        <div class="topbar-title">@yield('page-title', 'Dashboard')</div>
        <div class="topbar-actions">
            <a href="{{ route('resume.create') }}" class="btn btn-primary btn-sm" id="topbar-create-btn">
                <i class="fas fa-plus"></i> New Resume
            </a>
        </div>
    </header>

    <!-- Alerts -->
    <div class="alerts-container">
        @if(session('success'))
            <div class="alert alert-success" id="alert-success">
                <i class="fas fa-check-circle"></i>
                {{ session('success') }}
                <button onclick="this.parentElement.remove()" class="alert-close"><i class="fas fa-times"></i></button>
            </div>
        @endif
        @if(session('error') || $errors->any())
            <div class="alert alert-error" id="alert-error">
                <i class="fas fa-exclamation-circle"></i>
                {{ session('error') ?? $errors->first() }}
                <button onclick="this.parentElement.remove()" class="alert-close"><i class="fas fa-times"></i></button>
            </div>
        @endif
    </div>

    <!-- Page Content -->
    <main class="main-content">
        @yield('content')
    </main>
</div>

<!-- ====== AI CHATBOT WIDGET ====== -->
<div class="chatbot-fab" id="chatbot-fab" onclick="toggleChatbot()" title="AI Resume Assistant">
    <i class="fas fa-robot" id="chatbot-fab-icon"></i>
    <div class="chatbot-pulse"></div>
</div>

<div class="chatbot-panel" id="chatbot-panel">
    <div class="chatbot-header">
        <div class="chatbot-header-info">
            <div class="chatbot-avatar"><i class="fas fa-robot"></i></div>
            <div>
                <div class="chatbot-name">AI Resume Assistant</div>
                <div class="chatbot-status"><span class="status-dot"></span> Online</div>
            </div>
        </div>
        <button class="chatbot-close" id="chatbot-close-btn" onclick="toggleChatbot()">
            <i class="fas fa-times"></i>
        </button>
    </div>

    <div class="chatbot-messages" id="chatbot-messages">
        <div class="chat-message bot-message">
            <div class="chat-bubble">
                👋 <strong>Hello! I'm your AI Resume Assistant!</strong><br><br>
                I can help you with writing summaries, experience bullets, skills, formatting tips, and more!<br><br>
                Try asking: <em>"How do I write a good summary?"</em>
            </div>
        </div>
    </div>

    <div class="chatbot-suggestions" id="chatbot-suggestions">
        <button class="suggestion-chip" onclick="sendSuggestion('How to write a professional summary?')">📝 Summary tips</button>
        <button class="suggestion-chip" onclick="sendSuggestion('How to write strong experience bullets?')">💼 Experience</button>
        <button class="suggestion-chip" onclick="sendSuggestion('What skills should I list?')">🛠️ Skills</button>
        <button class="suggestion-chip" onclick="sendSuggestion('Give me resume format tips')">📄 Format tips</button>
    </div>

    <div class="chatbot-input-area">
        <input type="text" class="chatbot-input" id="chatbot-input" placeholder="Ask about resume tips..." maxlength="500">
        <button class="chatbot-send" id="chatbot-send-btn" onclick="sendChatMessage()">
            <i class="fas fa-paper-plane"></i>
        </button>
    </div>
</div>

<!-- App JS -->
<script src="{{ asset('js/app.js') }}"></script>
@stack('scripts')

<script>
// Auto-dismiss alerts
setTimeout(() => {
    document.querySelectorAll('.alert').forEach(a => {
        a.style.opacity = '0';
        setTimeout(() => a.remove(), 400);
    });
}, 5000);
</script>
</body>
</html>
