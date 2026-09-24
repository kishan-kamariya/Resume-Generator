/* ============================================
   RESUME GENERATOR — MAIN JS
   ============================================ */

// ── Chatbot ──────────────────────────────────
let chatHistory = [];
let chatbotOpen = false;

function toggleChatbot() {
    chatbotOpen = !chatbotOpen;
    const panel = document.getElementById('chatbot-panel');
    const icon  = document.getElementById('chatbot-fab-icon');
    if (panel) {
        panel.classList.toggle('open', chatbotOpen);
    }
    if (icon) {
        icon.className = chatbotOpen ? 'fas fa-times' : 'fas fa-robot';
    }
    if (chatbotOpen) {
        const input = document.getElementById('chatbot-input');
        if (input) setTimeout(() => input.focus(), 300);
        scrollChatToBottom();
    }
}

function scrollChatToBottom() {
    const msgs = document.getElementById('chatbot-messages');
    if (msgs) msgs.scrollTop = msgs.scrollHeight;
}

function appendMessage(text, role) {
    const msgs = document.getElementById('chatbot-messages');
    if (!msgs) return;

    const div = document.createElement('div');
    div.className = `chat-message ${role === 'user' ? 'user-message' : 'bot-message'}`;

    const bubble = document.createElement('div');
    bubble.className = 'chat-bubble';

    // Simple markdown rendering
    const rendered = text
        .replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>')
        .replace(/\*(.*?)\*/g, '<em>$1</em>')
        .replace(/`(.*?)`/g, '<code>$1</code>')
        .replace(/\n• /g, '<br>• ')
        .replace(/\n/g, '<br>');

    bubble.innerHTML = rendered;
    div.appendChild(bubble);
    msgs.appendChild(div);
    scrollChatToBottom();
}

function showTyping() {
    const msgs = document.getElementById('chatbot-messages');
    if (!msgs) return null;

    const div = document.createElement('div');
    div.className = 'chat-message bot-message';
    div.id = 'typing-indicator';

    const typing = document.createElement('div');
    typing.className = 'typing-indicator';
    [1,2,3].forEach(() => {
        const dot = document.createElement('div');
        dot.className = 'typing-dot';
        typing.appendChild(dot);
    });

    div.appendChild(typing);
    msgs.appendChild(div);
    scrollChatToBottom();
    return div;
}

function removeTyping() {
    const el = document.getElementById('typing-indicator');
    if (el) el.remove();
}

async function sendChatMessage() {
    const input   = document.getElementById('chatbot-input');
    const sendBtn = document.getElementById('chatbot-send-btn');
    if (!input) return;

    const message = input.value.trim();
    if (!message) return;

    input.value = '';
    input.disabled = true;
    if (sendBtn) sendBtn.disabled = true;

    // Hide suggestions after first message
    const suggestions = document.getElementById('chatbot-suggestions');
    if (suggestions) suggestions.style.display = 'none';

    appendMessage(message, 'user');
    chatHistory.push({ role: 'user', content: message });

    const typingEl = showTyping();

    try {
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const response = await fetch('/chatbot', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token,
                'Accept': 'application/json',
            },
            body: JSON.stringify({ message, history: chatHistory }),
        });

        const data = await response.json();
        removeTyping();

        const reply = data.reply || "Sorry, I couldn't process that. Please try again.";
        appendMessage(reply, 'bot');
        chatHistory.push({ role: 'assistant', content: reply });

    } catch (err) {
        removeTyping();
        appendMessage("⚠️ Connection error. Please check your internet and try again.", 'bot');
    } finally {
        input.disabled = false;
        if (sendBtn) sendBtn.disabled = false;
        input.focus();
    }
}

function sendSuggestion(text) {
    const input = document.getElementById('chatbot-input');
    if (input) input.value = text;
    sendChatMessage();
}

// Enter key for chatbot
document.addEventListener('DOMContentLoaded', () => {
    const chatInput = document.getElementById('chatbot-input');
    if (chatInput) {
        chatInput.addEventListener('keydown', (e) => {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                sendChatMessage();
            }
        });
    }
});

// ── Multi-Step Wizard ─────────────────────────
let currentStep = 0;
const STEPS = ['personal', 'summary', 'experience', 'education', 'skills', 'projects', 'certifications', 'languages', 'template'];

function initWizard() {
    const wizard = document.getElementById('resume-wizard');
    if (!wizard) return;

    showStep(0);

    // Step click navigation — only allow going back to completed steps
    document.querySelectorAll('.wizard-step').forEach((step, idx) => {
        step.addEventListener('click', () => {
            if (idx < currentStep || step.classList.contains('completed')) {
                showStep(idx);
            }
        });
    });

    // Realtime email validation for @gmail.com
    document.querySelectorAll('input[type="email"][name="email"]').forEach(input => {
        input.addEventListener('input', () => {
            if (input.dataset.touched === 'true') {
                validateEmailField(input, false);
            }
        });
        input.addEventListener('blur', () => {
            input.dataset.touched = 'true';
            if (input.value.trim() !== '') {
                validateEmailField(input, true);
            }
        });
    });

    // Event delegation: clear inline errors when user corrects a field
    document.querySelectorAll('.wizard-pane').forEach(pane => {
        pane.addEventListener('input', (e) => {
            const field = e.target;
            if (field.tagName === 'INPUT' || field.tagName === 'TEXTAREA' || field.tagName === 'SELECT') {
                if (field.value.trim()) {
                    clearInlineError(field);
                }
            }
        });
        pane.addEventListener('change', (e) => {
            const field = e.target;
            // When "currently working" is checked, clear end_date error in that block
            if (field.type === 'checkbox' && field.name && field.name.includes('currently_working')) {
                const block = field.closest('.section-block');
                if (block) {
                    const endInput = block.querySelector('input[name*="[end_date]"]');
                    if (endInput) clearInlineError(endInput);
                }
            }
        });
    });

    // Form submission: validate ALL steps before allowing submit
    const form = document.getElementById('resume-form');
    if (form) {
        form.addEventListener('submit', function (e) {
            for (let i = 0; i < STEPS.length; i++) {
                const result = validateStep(i);
                if (!result.valid) {
                    e.preventDefault();
                    showStep(i);
                    if (result.focusEl) setTimeout(() => result.focusEl.focus(), 250);
                    return false;
                }
            }
        });
    }
}

// ── Toast Notification System ─────────────────
function showToastNotification(message, type = 'error') {
    let container = document.getElementById('validation-toast-container');
    if (!container) {
        container = document.createElement('div');
        container.id = 'validation-toast-container';
        container.className = 'validation-toast-container';
        document.body.appendChild(container);
    }

    const toast = document.createElement('div');
    toast.className = `validation-toast ${type === 'success' ? 'toast-success' : ''}`;
    toast.innerHTML =
        '<i class="fas ' + (type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle') + '" style="font-size:1.2rem;color:' + (type === 'success' ? 'var(--success)' : '#f87171') + ';flex-shrink:0;"></i>' +
        '<div style="flex:1;line-height:1.4;">' + message + '</div>' +
        '<button type="button" style="background:none;border:none;color:inherit;cursor:pointer;opacity:0.6;font-size:0.9rem;" onclick="this.parentElement.remove()">' +
        '<i class="fas fa-times"></i></button>';
    container.appendChild(toast);

    setTimeout(() => {
        toast.style.opacity = '0';
        toast.style.transform = 'translateY(-10px)';
        setTimeout(() => toast.remove(), 350);
    }, 4500);
}

function shakeField(el) {
    if (!el) return;
    el.classList.remove('shake-field');
    void el.offsetWidth; // trigger reflow
    el.classList.add('shake-field');
    setTimeout(() => el.classList.remove('shake-field'), 450);
}

// ── Inline Error Helpers ──────────────────────
/**
 * Show an inline error message below a field.
 * Creates a .field-inline-error element if it doesn't exist yet.
 */
function showInlineError(field, message) {
    if (!field) return;
    field.classList.add('is-invalid');
    shakeField(field);

    // Look for existing inline error sibling
    const parent = field.closest('.form-group') || field.parentElement;
    let errEl = parent ? parent.querySelector('.field-inline-error') : null;

    if (!errEl) {
        errEl = document.createElement('div');
        errEl.className = 'form-error field-inline-error';
        errEl.style.animation = 'errorSlideIn 0.2s ease forwards';
        if (parent) parent.appendChild(errEl);
    }
    errEl.innerHTML = '<i class="fas fa-exclamation-circle"></i> <span>' + message + '</span>';
    errEl.style.display = 'flex';
}

/**
 * Clear inline error for a field.
 */
function clearInlineError(field) {
    if (!field) return;
    field.classList.remove('is-invalid');
    const parent = field.closest('.form-group') || field.parentElement;
    const errEl = parent ? parent.querySelector('.field-inline-error') : null;
    if (errEl) {
        errEl.style.display = 'none';
        errEl.textContent = '';
    }
}

function clearAllInlineErrors(pane) {
    if (!pane) return;
    pane.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
    pane.querySelectorAll('.field-inline-error').forEach(el => {
        el.style.display = 'none';
        el.textContent = '';
    });
}

// ── Email Validator ───────────────────────────
function validateEmailField(input, showAlertToast = false) {
    if (!input) return true;
    const val = input.value.trim();
    const errorEl = document.getElementById('r-email-error');
    const errorText = document.getElementById('r-email-error-text');

    const gmailRegex = /^[a-zA-Z0-9._%+-]+@gmail\.com$/i;

    if (!val) {
        showInlineError(input, 'Email address is required.');
        if (errorEl && errorText) { errorText.textContent = 'Email address is required.'; errorEl.style.display = 'flex'; }
        if (showAlertToast) showToastNotification('Email address is required.', 'error');
        return false;
    }

    if (!gmailRegex.test(val)) {
        showInlineError(input, 'Please enter a valid @gmail.com address.');
        if (errorEl && errorText) { errorText.textContent = 'Please enter a valid @gmail.com address (e.g. name@gmail.com).'; errorEl.style.display = 'flex'; }
        if (showAlertToast) showToastNotification('Email must be a valid @gmail.com address.', 'error');
        return false;
    }

    input.classList.remove('is-invalid');
    if (errorEl) errorEl.style.display = 'none';
    return true;
}

// ── Date Parsing & Validation ─────────────────
const MONTH_MAP = {
    jan: 1, january: 1, feb: 2, february: 2, mar: 3, march: 3,
    apr: 4, april: 4, may: 5, jun: 6, june: 6, jul: 7, july: 7,
    aug: 8, august: 8, sep: 9, september: 9, oct: 10, october: 10,
    nov: 11, november: 11, dec: 12, december: 12
};

/**
 * Parse a flexible human date string (e.g. "Jan 2022", "January 2022", "2022", "Sep 2018")
 * Returns {year, month} or null if unparseable.
 */
function parseFlexDate(str) {
    if (!str) return null;
    str = str.trim();

    // "Present" / "Current" — treat as today
    if (/^(present|current|now)$/i.test(str)) {
        const now = new Date();
        return { year: now.getFullYear(), month: now.getMonth() + 1 };
    }

    // "MMM YYYY" or "MMMM YYYY"  e.g. "Jan 2022"
    const monthYear = str.match(/^([a-zA-Z]+)\s+(\d{4})$/);
    if (monthYear) {
        const month = MONTH_MAP[monthYear[1].toLowerCase()];
        const year = parseInt(monthYear[2], 10);
        if (month && year >= 1900 && year <= 2100) return { year, month };
        return null;
    }

    // "YYYY-MM" e.g. "2022-01"
    const isoYM = str.match(/^(\d{4})-(\d{2})$/);
    if (isoYM) {
        const year = parseInt(isoYM[1], 10);
        const month = parseInt(isoYM[2], 10);
        if (month >= 1 && month <= 12 && year >= 1900) return { year, month };
        return null;
    }

    // "YYYY" alone — accept as Jan of that year for comparison
    const yearOnly = str.match(/^(\d{4})$/);
    if (yearOnly) {
        const year = parseInt(yearOnly[1], 10);
        if (year >= 1900 && year <= 2100) return { year, month: 1 };
        return null;
    }

    return null;
}

/**
 * Compare two parsed dates. Returns -1, 0, or 1.
 */
function compareDates(a, b) {
    if (a.year !== b.year) return a.year < b.year ? -1 : 1;
    if (a.month !== b.month) return a.month < b.month ? -1 : 1;
    return 0;
}

/**
 * Validate a date input: must be parseable and non-future (optional).
 * Returns error message string or null if valid.
 */
function validateDateInput(value, label) {
    if (!value || !value.trim()) return null; // empty = not required here (checked separately)
    const parsed = parseFlexDate(value.trim());
    if (!parsed) {
        return '"' + value + '" is not a valid date. Use formats like "Jan 2022" or "2022-01".';
    }
    return null; // valid
}

/**
 * Validate start/end date pair.
 * Returns error message or null.
 */
function validateDateRange(startVal, endVal) {
    if (!startVal || !endVal) return null;
    if (/^(present|current|now)$/i.test(endVal.trim())) return null;

    const start = parseFlexDate(startVal.trim());
    const end   = parseFlexDate(endVal.trim());
    if (!start || !end) return null; // individual errors handled separately

    if (compareDates(end, start) < 0) {
        return 'End date cannot be before start date.';
    }
    return null;
}

// ── Per-Step Validation ───────────────────────
/**
 * Returns {valid: bool, focusEl: HTMLElement|null}
 */
function validateStep(stepIdx) {
    switch (stepIdx) {
        case 0: return validatePersonal();
        case 1: return { valid: true, focusEl: null }; // Summary is optional
        case 2: return validateExperience();
        case 3: return validateEducation();
        case 4: return validateSkills();
        case 5: return validateProjects();
        case 6: return validateCertifications();
        case 7: return validateLanguages();
        case 8: return { valid: true, focusEl: null }; // Template always has a default
        default: return { valid: true, focusEl: null };
    }
}

function validatePersonal() {
    const pane = document.getElementById('pane-0');
    if (!pane) return { valid: true, focusEl: null };

    // Resume title
    const titleInput = pane.querySelector('#r-title');
    if (titleInput && !titleInput.value.trim()) {
        showInlineError(titleInput, 'Resume title is required.');
        showToastNotification('Please fill in: Resume Title', 'error');
        return { valid: false, focusEl: titleInput };
    }

    // Full name
    const nameInput = pane.querySelector('#r-full-name');
    if (nameInput && !nameInput.value.trim()) {
        showInlineError(nameInput, 'Full name is required.');
        showToastNotification('Please fill in: Full Name', 'error');
        return { valid: false, focusEl: nameInput };
    }

    // Email
    const emailInput = pane.querySelector('input[type="email"][name="email"]');
    if (emailInput) {
        emailInput.dataset.touched = 'true';
        if (!validateEmailField(emailInput, true)) {
            return { valid: false, focusEl: emailInput };
        }
    }

    return { valid: true, focusEl: null };
}

function validateExperience() {
    const pane = document.getElementById('pane-2');
    if (!pane) return { valid: true, focusEl: null };

    const blocks = pane.querySelectorAll('.section-block');
    for (let b = 0; b < blocks.length; b++) {
        const block = blocks[b];

        const companyInput = block.querySelector('input[name*="[company]"]');
        const positionInput = block.querySelector('input[name*="[position]"]');
        const startInput = block.querySelector('input[name*="[start_date]"]');
        const endInput = block.querySelector('input[name*="[end_date]"]');
        const currentlyWorkingCb = block.querySelector('input[name*="[currently_working]"]');

        // Only validate if at least one field in this block is filled (partial fill guard)
        const anyFilled = [companyInput, positionInput, startInput].some(el => el && el.value.trim());
        if (!anyFilled) continue;

        // Company name
        if (companyInput && !companyInput.value.trim()) {
            showInlineError(companyInput, 'Company name is required.');
            showToastNotification('Experience #' + (b + 1) + ': Company name is required.', 'error');
            return { valid: false, focusEl: companyInput };
        }

        // Position
        if (positionInput && !positionInput.value.trim()) {
            showInlineError(positionInput, 'Position / role is required.');
            showToastNotification('Experience #' + (b + 1) + ': Position is required.', 'error');
            return { valid: false, focusEl: positionInput };
        }

        // Start date
        if (startInput && !startInput.value.trim()) {
            showInlineError(startInput, 'Start date is required.');
            showToastNotification('Experience #' + (b + 1) + ': Start date is required.', 'error');
            return { valid: false, focusEl: startInput };
        }
        if (startInput && startInput.value.trim()) {
            const startErr = validateDateInput(startInput.value, 'Start date');
            if (startErr) {
                showInlineError(startInput, startErr);
                showToastNotification('Experience #' + (b + 1) + ': ' + startErr, 'error');
                return { valid: false, focusEl: startInput };
            }
        }

        // End date (required unless currently working)
        const isCurrentlyWorking = currentlyWorkingCb && currentlyWorkingCb.checked;
        if (!isCurrentlyWorking && endInput) {
            if (!endInput.value.trim()) {
                showInlineError(endInput, 'End date is required, or check "Currently working here".');
                showToastNotification('Experience #' + (b + 1) + ': End date required or mark as current.', 'error');
                return { valid: false, focusEl: endInput };
            }
            const endErr = validateDateInput(endInput.value, 'End date');
            if (endErr) {
                showInlineError(endInput, endErr);
                showToastNotification('Experience #' + (b + 1) + ': ' + endErr, 'error');
                return { valid: false, focusEl: endInput };
            }
            if (startInput && startInput.value.trim()) {
                const rangeErr = validateDateRange(startInput.value, endInput.value);
                if (rangeErr) {
                    showInlineError(endInput, rangeErr);
                    showToastNotification('Experience #' + (b + 1) + ': ' + rangeErr, 'error');
                    return { valid: false, focusEl: endInput };
                }
            }
        }
    }

    return { valid: true, focusEl: null };
}

function validateEducation() {
    const pane = document.getElementById('pane-3');
    if (!pane) return { valid: true, focusEl: null };

    const blocks = pane.querySelectorAll('.section-block');
    for (let b = 0; b < blocks.length; b++) {
        const block = blocks[b];

        const institutionInput = block.querySelector('input[name*="[institution]"]');
        const degreeInput = block.querySelector('input[name*="[degree]"]');
        const fieldInput = block.querySelector('input[name*="[field_of_study]"]');
        const startInput = block.querySelector('input[name*="[start_date]"]');
        const endInput = block.querySelector('input[name*="[end_date]"]');

        const anyFilled = [institutionInput, degreeInput, fieldInput, startInput].some(el => el && el.value.trim());
        if (!anyFilled) continue;

        if (institutionInput && !institutionInput.value.trim()) {
            showInlineError(institutionInput, 'Institution name is required.');
            showToastNotification('Education #' + (b + 1) + ': Institution name is required.', 'error');
            return { valid: false, focusEl: institutionInput };
        }
        if (degreeInput && !degreeInput.value.trim()) {
            showInlineError(degreeInput, 'Degree is required.');
            showToastNotification('Education #' + (b + 1) + ': Degree is required.', 'error');
            return { valid: false, focusEl: degreeInput };
        }
        if (fieldInput && !fieldInput.value.trim()) {
            showInlineError(fieldInput, 'Field of study is required.');
            showToastNotification('Education #' + (b + 1) + ': Field of study is required.', 'error');
            return { valid: false, focusEl: fieldInput };
        }
        if (startInput && startInput.value.trim()) {
            const startErr = validateDateInput(startInput.value, 'Start date');
            if (startErr) {
                showInlineError(startInput, startErr);
                showToastNotification('Education #' + (b + 1) + ': ' + startErr, 'error');
                return { valid: false, focusEl: startInput };
            }
        }
        if (endInput && endInput.value.trim()) {
            const endErr = validateDateInput(endInput.value, 'End date');
            if (endErr) {
                showInlineError(endInput, endErr);
                showToastNotification('Education #' + (b + 1) + ': ' + endErr, 'error');
                return { valid: false, focusEl: endInput };
            }
            if (startInput && startInput.value.trim()) {
                const rangeErr = validateDateRange(startInput.value, endInput.value);
                if (rangeErr) {
                    showInlineError(endInput, rangeErr);
                    showToastNotification('Education #' + (b + 1) + ': ' + rangeErr, 'error');
                    return { valid: false, focusEl: endInput };
                }
            }
        }
    }

    return { valid: true, focusEl: null };
}

function validateSkills() {
    const pane = document.getElementById('pane-4');
    if (!pane) return { valid: true, focusEl: null };

    const blocks = pane.querySelectorAll('.section-block');
    for (let b = 0; b < blocks.length; b++) {
        const block = blocks[b];
        const nameInput = block.querySelector('input[name*="[name]"]');
        if (nameInput && !nameInput.value.trim()) {
            showInlineError(nameInput, 'Skill name is required.');
            showToastNotification('Skill #' + (b + 1) + ': Skill name is required.', 'error');
            return { valid: false, focusEl: nameInput };
        }
    }

    return { valid: true, focusEl: null };
}

function validateProjects() {
    const pane = document.getElementById('pane-5');
    if (!pane) return { valid: true, focusEl: null };

    const blocks = pane.querySelectorAll('.section-block');
    for (let b = 0; b < blocks.length; b++) {
        const block = blocks[b];
        const nameInput = block.querySelector('input[name*="[name]"]');
        const descInput = block.querySelector('textarea[name*="[description]"]');

        const anyFilled = [nameInput, descInput].some(el => el && el.value.trim());
        if (!anyFilled) continue;

        if (nameInput && !nameInput.value.trim()) {
            showInlineError(nameInput, 'Project name is required.');
            showToastNotification('Project #' + (b + 1) + ': Project name is required.', 'error');
            return { valid: false, focusEl: nameInput };
        }
        if (descInput && !descInput.value.trim()) {
            showInlineError(descInput, 'Project description is required.');
            showToastNotification('Project #' + (b + 1) + ': Description is required.', 'error');
            return { valid: false, focusEl: descInput };
        }
    }

    return { valid: true, focusEl: null };
}

function validateCertifications() {
    const pane = document.getElementById('pane-6');
    if (!pane) return { valid: true, focusEl: null };

    const blocks = pane.querySelectorAll('.section-block');
    for (let b = 0; b < blocks.length; b++) {
        const block = blocks[b];
        const nameInput = block.querySelector('input[name*="[name]"]');
        const issuerInput = block.querySelector('input[name*="[issuer]"]');
        const issueDateInput = block.querySelector('input[name*="[issue_date]"]');
        const expiryDateInput = block.querySelector('input[name*="[expiry_date]"]');

        const anyFilled = [nameInput, issuerInput, issueDateInput].some(el => el && el.value.trim());
        if (!anyFilled) continue;

        if (nameInput && !nameInput.value.trim()) {
            showInlineError(nameInput, 'Certification name is required.');
            showToastNotification('Certification #' + (b + 1) + ': Name is required.', 'error');
            return { valid: false, focusEl: nameInput };
        }
        if (issuerInput && !issuerInput.value.trim()) {
            showInlineError(issuerInput, 'Issuing organization is required.');
            showToastNotification('Certification #' + (b + 1) + ': Issuer is required.', 'error');
            return { valid: false, focusEl: issuerInput };
        }
        if (issueDateInput && !issueDateInput.value.trim()) {
            showInlineError(issueDateInput, 'Issue date is required.');
            showToastNotification('Certification #' + (b + 1) + ': Issue date is required.', 'error');
            return { valid: false, focusEl: issueDateInput };
        }
        if (issueDateInput && issueDateInput.value.trim()) {
            const dateErr = validateDateInput(issueDateInput.value, 'Issue date');
            if (dateErr) {
                showInlineError(issueDateInput, dateErr);
                showToastNotification('Certification #' + (b + 1) + ': ' + dateErr, 'error');
                return { valid: false, focusEl: issueDateInput };
            }
        }
        if (expiryDateInput && expiryDateInput.value.trim()) {
            const expErr = validateDateInput(expiryDateInput.value, 'Expiry date');
            if (expErr) {
                showInlineError(expiryDateInput, expErr);
                showToastNotification('Certification #' + (b + 1) + ': ' + expErr, 'error');
                return { valid: false, focusEl: expiryDateInput };
            }
            if (issueDateInput && issueDateInput.value.trim()) {
                const rangeErr = validateDateRange(issueDateInput.value, expiryDateInput.value);
                if (rangeErr) {
                    showInlineError(expiryDateInput, rangeErr);
                    showToastNotification('Certification #' + (b + 1) + ': ' + rangeErr, 'error');
                    return { valid: false, focusEl: expiryDateInput };
                }
            }
        }
    }

    return { valid: true, focusEl: null };
}

function validateLanguages() {
    const pane = document.getElementById('pane-7');
    if (!pane) return { valid: true, focusEl: null };

    const blocks = pane.querySelectorAll('.section-block');
    for (let b = 0; b < blocks.length; b++) {
        const block = blocks[b];
        const nameInput = block.querySelector('input[name*="[name]"]');
        if (nameInput && !nameInput.value.trim()) {
            showInlineError(nameInput, 'Language name is required.');
            showToastNotification('Language #' + (b + 1) + ': Language name is required.', 'error');
            return { valid: false, focusEl: nameInput };
        }
    }

    return { valid: true, focusEl: null };
}

// ── Wizard Navigation ─────────────────────────
function showStep(idx) {
    currentStep = idx;

    document.querySelectorAll('.wizard-pane').forEach((pane, i) => {
        pane.classList.toggle('active', i === idx);
    });

    document.querySelectorAll('.wizard-step').forEach((step, i) => {
        step.classList.remove('active', 'completed');
        if (i === idx) step.classList.add('active');
        if (i < idx) step.classList.add('completed');
    });

    const prevBtn = document.getElementById('wizard-prev');
    const nextBtn = document.getElementById('wizard-next');
    const submitBtn = document.getElementById('wizard-submit');

    if (prevBtn) prevBtn.style.display = idx === 0 ? 'none' : 'flex';
    if (nextBtn) nextBtn.style.display = idx === STEPS.length - 1 ? 'none' : 'flex';
    if (submitBtn) submitBtn.style.display = idx === STEPS.length - 1 ? 'flex' : 'none';

    // Save to localStorage
    autoSave();
}

function nextStep() {
    // Temporarily disable the button to prevent double-clicks
    const nextBtn = document.getElementById('wizard-next');
    if (nextBtn) nextBtn.disabled = true;

    const result = validateStep(currentStep);
    if (!result.valid) {
        if (nextBtn) setTimeout(() => { nextBtn.disabled = false; }, 500);
        if (result.focusEl) setTimeout(() => result.focusEl.focus(), 100);
        return;
    }

    if (nextBtn) nextBtn.disabled = false;
    if (currentStep < STEPS.length - 1) showStep(currentStep + 1);
}

function prevStep() {
    if (currentStep > 0) showStep(currentStep - 1);
}

// ── Dynamic Sections (add/remove) ────────────
function addSection(containerId, template) {
    const container = document.getElementById(containerId);
    if (!container) return;

    const count = container.querySelectorAll('.section-block').length;
    const div = document.createElement('div');
    div.className = 'section-block';

    const html = template.replace(/\[IDX\]/g, count);
    div.innerHTML =
        '<div class="section-block-header">' +
        '<span class="section-block-title">#' + (count + 1) + '</span>' +
        '<button type="button" class="btn-remove-section" onclick="removeSection(this)">' +
        '<i class="fas fa-trash-alt"></i> Remove</button></div>' + html;
    container.appendChild(div);
}

function removeSection(btn) {
    const block = btn.closest('.section-block');
    if (block) {
        block.style.opacity = '0';
        block.style.transform = 'translateY(-8px)';
        block.style.transition = 'all 0.3s';
        setTimeout(() => block.remove(), 300);
    }
}

// ── Auto-save to localStorage ─────────────────
function autoSave() {
    const form = document.getElementById('resume-form');
    if (!form) return;

    const data = {};
    new FormData(form).forEach((value, key) => {
        data[key] = value;
    });
    localStorage.setItem('resume_draft', JSON.stringify(data));
}

function loadDraft() {
    const draft = localStorage.getItem('resume_draft');
    if (!draft) return;
    const data = JSON.parse(draft);

    Object.entries(data).forEach(([key, value]) => {
        const el = document.querySelector('[name="' + key + '"]');
        if (el && el.tagName !== 'BUTTON') {
            if (el.type === 'checkbox') el.checked = value === 'on';
            else el.value = value;
        }
    });
}

// ── Template Selector ─────────────────────────
function selectTemplate(value) {
    document.querySelectorAll('.template-option').forEach(opt => {
        opt.classList.toggle('selected', opt.dataset.value === value);
    });
    const input = document.getElementById('template-input');
    if (input) input.value = value;
}

// ── Photo Preview ─────────────────────────────
function previewPhoto(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = (e) => {
            const preview = document.getElementById('photo-preview');
            if (preview) {
                preview.src = e.target.result;
                preview.style.display = 'block';
            }
        };
        reader.readAsDataURL(input.files[0]);
    }
}

// ── Template Switcher (Preview Page) ─────────
function switchTemplate(template, resumeId) {
    const frame = document.getElementById('preview-frame');
    if (frame) {
        frame.src = '/resume/' + resumeId + '/preview?template=' + template;
    }

    document.querySelectorAll('.template-radio').forEach(radio => {
        radio.classList.toggle('active', radio.dataset.template === template);
    });
}

// ── Confirm Delete ─────────────────────────────
function confirmDelete(form) {
    if (confirm('Are you sure you want to delete this resume? This action cannot be undone.')) {
        form.submit();
    }
    return false;
}

// ── Init ──────────────────────────────────────
document.addEventListener('DOMContentLoaded', () => {
    initWizard();

    // Animate stat numbers
    document.querySelectorAll('.stat-value[data-count]').forEach(el => {
        const target = parseInt(el.dataset.count, 10);
        let current = 0;
        const step = Math.ceil(target / 20);
        const timer = setInterval(() => {
            current = Math.min(current + step, target);
            el.textContent = current;
            if (current >= target) clearInterval(timer);
        }, 50);
    });
});
