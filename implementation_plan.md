# Laravel Resume Generator with AI Chatbot

A full-stack Laravel web application for building professional resumes with an integrated AI chatbot assistant — powered by MySQL (WAMP/XAMPP), styled with premium HTML/CSS/JS.

---

## Overview

This project will be a complete resume generator platform where users can:
- Register/Login securely
- Fill out resume sections step by step
- Preview resumes in real-time
- Download/Print resumes as PDF
- Chat with an AI assistant for resume writing tips

---

## Tech Stack

| Layer | Technology |
|---|---|
| Framework | Laravel 10.x |
| Frontend | Blade templates + Vanilla CSS + JS |
| Database | MySQL via WAMP/XAMPP |
| PDF Export | DomPDF (Laravel package) |
| AI Chatbot | OpenAI GPT API (via PHP backend) |
| Icons | Font Awesome |
| Fonts | Google Fonts (Inter, Outfit) |

---

## Proposed Changes

### 1. Laravel Project Setup

#### [NEW] Laravel Project (root)
- Initialize fresh Laravel project with Composer
- Configure `.env` for MySQL (WAMP/XAMPP: `DB_HOST=127.0.0.1`, `DB_PORT=3306`)
- Install packages: `barryvdh/laravel-dompdf`, `guzzlehttp/guzzle`

---

### 2. Database & Migrations

#### [NEW] Migrations
- `users` — Auth table (name, email, password)
- `resumes` — Master resume record per user
- `resume_sections` — Personal info, education, work experience, skills, projects, certifications, languages, summary

#### [NEW] Models
- `User`, `Resume`, `Education`, `WorkExperience`, `Skill`, `Project`, `Certification`, `Language`

---

### 3. Routes & Controllers

#### [NEW] `routes/web.php`
- Auth routes (login, register, logout)
- Dashboard
- Resume CRUD routes
- AI Chatbot API route

#### [NEW] Controllers
- `AuthController` — Login, Register, Logout
- `DashboardController` — User dashboard
- `ResumeController` — Create, Edit, Update, Delete, Preview, Download PDF
- `ChatbotController` — Handle AI chat messages (calls OpenAI API)

---

### 4. Frontend Views (Blade + CSS + JS)

#### [NEW] Layout `resources/views/layouts/app.blade.php`
- Premium dark sidebar layout
- Glassmorphism nav
- Animated background

#### [NEW] Pages
- `auth/login.blade.php` — Premium login page
- `auth/register.blade.php` — Register page
- `dashboard/index.blade.php` — Resume cards grid with stats
- `resume/create.blade.php` — Multi-step wizard form
- `resume/edit.blade.php` — Edit resume
- `resume/preview.blade.php` — Live preview with template switcher
- `resume/templates/` — 3 beautiful resume templates

#### [NEW] AI Chatbot Widget
- Floating chat button (bottom-right)
- Slide-up chat panel with message history
- Resume-context-aware suggestions
- Typing animation indicator

#### [NEW] CSS `public/css/app.css`
- Complete design system with CSS variables
- Dark mode theme (deep navy + purple gradients)
- Glassmorphism cards
- Multi-step form animations
- Resume template print styles
- Chatbot widget styles

#### [NEW] JS `public/js/app.js`
- Multi-step form logic with validation
- Live resume preview refresh
- Chatbot message send/receive
- PDF download trigger
- Skills tag input
- Form auto-save to localStorage

---

### 5. Resume Templates

Three premium resume templates rendered as HTML/CSS:
- **Classic** — Clean, ATS-friendly, two-column
- **Modern** — Colorful accent sidebar, icons
- **Minimal** — Ultra-clean single column

---

### 6. AI Chatbot

The chatbot will:
- Use the OpenAI Chat Completions API (`gpt-3.5-turbo`)
- Be configured with a system prompt focused on resume writing help
- Pass conversation history for context
- Provide suggestions like: bullet point improvements, skill recommendations, summary writing, etc.

> **Note**: Requires an OpenAI API key set in `.env` as `OPENAI_API_KEY=sk-...`
> If no key is provided, the chatbot will fall back to a rule-based helper with curated resume tips.

---

## Open Questions

> [!IMPORTANT]
> **OpenAI API Key**: Do you have an OpenAI API key to use for the chatbot? If not, I'll implement a smart rule-based fallback chatbot that provides resume tips without needing an external API.

> [!IMPORTANT]
> **Server**: Are you using WAMP or XAMPP? Both work the same way — just confirm so I can set the correct default port in `.env`.

> [!NOTE]
> **Authentication**: Should the resume generator require login, or should guest users also be able to create resumes without registration?

---

## Verification Plan

### Automated Tests
- `php artisan test` — Run included feature tests

### Manual Verification
- Start WAMP/XAMPP, create `resume_generator` database
- Run `php artisan migrate --seed`
- Visit `http://localhost:8000` via `php artisan serve`
- Register a user, create a resume, preview it, download PDF, test chatbot
