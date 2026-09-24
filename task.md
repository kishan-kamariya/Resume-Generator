# Resume Generator - Task List

## Phase 1: Project Setup
- [x] Create implementation plan
- [ ] Scaffold Laravel project
- [ ] Configure .env for MySQL
- [ ] Install composer packages (dompdf, guzzle)

## Phase 2: Database
- [ ] Create migrations (users, resumes, education, work_experience, skills, projects, certifications, languages)
- [ ] Create Eloquent models with relationships
- [ ] Create seeders (optional demo data)

## Phase 3: Backend
- [ ] AuthController (login, register, logout)
- [ ] DashboardController
- [ ] ResumeController (CRUD + PDF download)
- [ ] ChatbotController (rule-based AI)
- [ ] Define all routes in web.php + api.php

## Phase 4: Frontend Views
- [ ] layouts/app.blade.php (main layout with nav/sidebar)
- [ ] auth/login.blade.php
- [ ] auth/register.blade.php
- [ ] dashboard/index.blade.php
- [ ] resume/create.blade.php (multi-step wizard)
- [ ] resume/edit.blade.php
- [ ] resume/preview.blade.php (template switcher)
- [ ] resume/templates/classic.blade.php
- [ ] resume/templates/modern.blade.php
- [ ] resume/templates/minimal.blade.php

## Phase 5: Assets
- [ ] public/css/app.css (full design system)
- [ ] public/js/app.js (form logic, chatbot, preview)
- [ ] Chatbot widget (floating button + panel)

## Phase 6: Verify
- [ ] Test migrations run correctly
- [ ] Test auth flow
- [ ] Test resume creation
- [ ] Test PDF download
- [ ] Test chatbot responses
