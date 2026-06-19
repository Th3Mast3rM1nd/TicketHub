<?php
require_once __DIR__ . '/includes/session.php';
require_once __DIR__ . '/config.php';
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Ticketsystem &mdash; Manage tickets. Stay organized.</title>
  <meta name="description" content="Open-source ticket management with a built-in AI assistant powered by Ollama.">
  <link rel="stylesheet" href="/public/css/app.css">
  <script src="/public/js/theme.js"></script>
  <link rel="stylesheet" href="/public/css/landing.css">
</head>
<body>

<!-- ═══ Navbar ═══ -->
<nav class="nav" id="lp-nav">
  <div class="nav-inner">
    <a href="/" class="nav-logo">
      <span class="nav-logo-mark">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" style="display:block"><path d="M3 4 L9 4 Q12 7 15 4 L21 4 Q23 4 23 6 L23 18 Q23 20 21 20 L15 20 Q12 17 9 20 L3 20 Q1 20 1 18 L1 6 Q1 4 3 4 Z"/><polyline points="8,13 11,16 16.5,9"/></svg>
      </span>
      Ticketsystem
    </a>
    <ul class="nav-links">
      <li><a href="#features">Features</a></li>
      <li><a href="#ai">AI Assistant</a></li>
      <li><a href="#faq">FAQ</a></li>
    </ul>
    <div class="nav-actions">
      <a href="/auth/login.php" class="nav-sign-in">Sign in</a>
      <a href="/auth/register.php" class="btn btn-primary">Get started</a>
      <button class="nav-hamburger" id="nav-hamburger" aria-label="Open menu" aria-expanded="false">
        <span></span><span></span><span></span>
      </button>
    </div>
  </div>
</nav>

<!-- Mobile menu -->
<nav id="nav-mobile" class="nav-mobile" aria-label="Mobile menu">
  <a href="#features">Features</a>
  <a href="#ai">AI Assistant</a>
  <a href="#faq">FAQ</a>
  <a href="/auth/login.php">Sign in</a>
  <a href="/auth/register.php" class="btn btn-primary">Get started</a>
</nav>

<!-- ═══ Hero ═══ -->
<section class="lp-hero">
  <div class="lp-hero-inner">

    <div class="lp-hero-text">
      <div class="hero-badge" data-reveal>
        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
        Now with AI assistance
      </div>
      <h1 class="hero-h1" data-reveal data-delay="1">
        Support tickets,<br>simplified.
      </h1>
      <p class="hero-sub" data-reveal data-delay="2">
        Manage, prioritize, and resolve every request faster &mdash; with a built-in AI assistant powered by Ollama.
      </p>
      <div class="hero-actions" data-reveal data-delay="3">
        <a href="/auth/register.php" class="btn btn-primary">Get started free</a>
        <a href="#how-it-works" class="btn btn-ghost">See how it works &rarr;</a>
      </div>
      <div class="hero-micro" data-reveal data-delay="3">
        <span>Open source</span>
        <span>Built with PHP &amp; MySQL</span>
        <span>AI-powered</span>
      </div>
    </div>

    <div class="lp-hero-visual" data-reveal data-delay="2">
      <div class="browser-frame">
        <div class="browser-bar">
          <span class="browser-dot browser-dot--red"></span>
          <span class="browser-dot browser-dot--yellow"></span>
          <span class="browser-dot browser-dot--green"></span>
          <div class="browser-url">ticketsystem.local/dashboard</div>
        </div>
        <div class="browser-content">
          <div class="mock-table-head">
            <span>ID</span><span>Title</span><span>Status</span><span>Priority</span>
          </div>
          <div class="mock-row">
            <span class="mock-id">TH-001</span>
            <span class="mock-title">Login page broken</span>
            <span class="mock-pill open">Open</span>
            <span class="mock-pill high">High</span>
          </div>
          <div class="mock-row">
            <span class="mock-id">TH-002</span>
            <span class="mock-title">Email not sending</span>
            <span class="mock-pill progress">In Progress</span>
            <span class="mock-pill medium">Medium</span>
          </div>
          <div class="mock-row">
            <span class="mock-id">TH-003</span>
            <span class="mock-title">Export fails on CSV</span>
            <span class="mock-pill open">Open</span>
            <span class="mock-pill high">High</span>
          </div>
          <div class="mock-row">
            <span class="mock-id">TH-004</span>
            <span class="mock-title">Dark mode glitch</span>
            <span class="mock-pill closed">Closed</span>
            <span class="mock-pill low">Low</span>
          </div>
        </div>
        <div class="mock-float">
          <span>3 resolved today</span>
          <span class="mock-float-up">&#8593; 12%</span>
        </div>
      </div>
    </div>

  </div>
</section>

<!-- ═══ Stats Band ═══ -->
<section class="lp-stats-band" aria-label="Key stats">
  <div class="lp-stats-inner">
    <div class="lp-stat" data-reveal>
      <div class="lp-stat-value">Open source</div>
      <div class="lp-stat-label">Free to use forever</div>
    </div>
    <div class="lp-stat" data-reveal data-delay="1">
      <div class="lp-stat-value">AI-powered</div>
      <div class="lp-stat-label">Built-in Ollama assistant</div>
    </div>
    <div class="lp-stat" data-reveal data-delay="2">
      <div class="lp-stat-value">3 statuses</div>
      <div class="lp-stat-label">Open &middot; In Progress &middot; Closed</div>
    </div>
  </div>
</section>

<!-- ═══ Features Grid ═══ -->
<section id="features" class="lp-features">
  <div class="lp-features-head">
    <p class="lp-label" data-reveal>Everything included</p>
    <h2 class="lp-h2" data-reveal data-delay="1">Built for teams that<br>need to move fast.</h2>
  </div>
  <div class="feat-grid">

    <div class="feat-grid-card" data-reveal data-delay="1">
      <div class="feat-grid-icon">
        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="22 12 16 12 14 15 10 15 8 12 2 12"/><path d="M5.45 5.11L2 12v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-6l-3.45-6.89A2 2 0 0 0 16.76 4H7.24a2 2 0 0 0-1.79 1.11z"/></svg>
      </div>
      <div class="feat-grid-title">Ticket Tracking</div>
      <p class="feat-grid-body">Every request logged, timestamped, and searchable. Nothing falls through the cracks.</p>
    </div>

    <div class="feat-grid-card" data-reveal data-delay="1">
      <div class="feat-grid-icon">
        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z"/><line x1="4" y1="22" x2="4" y2="15"/></svg>
      </div>
      <div class="feat-grid-title">Priority Levels</div>
      <p class="feat-grid-body">High, medium, or low &mdash; always know exactly what needs your attention first.</p>
    </div>

    <div class="feat-grid-card" data-reveal data-delay="1">
      <div class="feat-grid-icon">
        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg>
      </div>
      <div class="feat-grid-title">AI Assistant</div>
      <p class="feat-grid-body">Powered by Ollama and running locally. Ask questions about tickets in plain language.</p>
    </div>

    <div class="feat-grid-card" data-reveal data-delay="2">
      <div class="feat-grid-icon">
        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
      </div>
      <div class="feat-grid-title">Comments</div>
      <p class="feat-grid-body">Threaded discussion on every ticket. Keep all context, decisions, and history in one place.</p>
    </div>

    <div class="feat-grid-card" data-reveal data-delay="2">
      <div class="feat-grid-icon">
        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
      </div>
      <div class="feat-grid-title">Admin Panel</div>
      <p class="feat-grid-body">Full control over every ticket and user. Update status, change priority, manage roles.</p>
    </div>

    <div class="feat-grid-card" data-reveal data-delay="2">
      <div class="feat-grid-icon">
        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="2" y="2" width="20" height="8" rx="2" ry="2"/><rect x="2" y="14" width="20" height="8" rx="2" ry="2"/><line x1="6" y1="6" x2="6.01" y2="6"/><line x1="6" y1="18" x2="6.01" y2="18"/></svg>
      </div>
      <div class="feat-grid-title">Self-Hosted</div>
      <p class="feat-grid-body">Runs on your own infrastructure. No third-party cloud. Your data never leaves your server.</p>
    </div>

  </div>
</section>

<!-- ═══ Dashboard Preview ═══ -->
<section class="lp-preview">
  <div class="lp-preview-inner">
    <div class="lp-preview-head">
      <p class="lp-label" data-reveal>The product</p>
      <h2 class="lp-h2" data-reveal data-delay="1">See it in action.</h2>
    </div>
    <div class="preview-browser" data-reveal data-delay="2">
      <div class="preview-bar">
        <span class="browser-dot browser-dot--red"></span>
        <span class="browser-dot browser-dot--yellow"></span>
        <span class="browser-dot browser-dot--green"></span>
        <div class="preview-url">ticketsystem.local/dashboard</div>
      </div>
      <div class="preview-app">

        <!-- Sidebar -->
        <div class="preview-sidebar">
          <div class="preview-logo">
            <div class="preview-logo-mark">
              <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" style="display:block"><path d="M3 4 L9 4 Q12 7 15 4 L21 4 Q23 4 23 6 L23 18 Q23 20 21 20 L15 20 Q12 17 9 20 L3 20 Q1 20 1 18 L1 6 Q1 4 3 4 Z"/><polyline points="8,13 11,16 16.5,9"/></svg>
            </div>
            Ticketsystem
          </div>
          <div class="preview-nav-section">Workspace</div>
          <div class="preview-nav-item active">
            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="22 12 16 12 14 15 10 15 8 12 2 12"/><path d="M5.45 5.11L2 12v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-6l-3.45-6.89A2 2 0 0 0 16.76 4H7.24a2 2 0 0 0-1.79 1.11z"/></svg>
            Tickets
          </div>
          <div class="preview-nav-section">Account</div>
          <div class="preview-nav-item">
            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
            Change password
          </div>
          <div class="preview-nav-section">Admin</div>
          <div class="preview-nav-item">
            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
            Users
          </div>
        </div>

        <!-- Main area -->
        <div class="preview-main">
          <div class="preview-main-head">
            <div class="preview-page-title">Welcome back.</div>
            <div class="preview-btn">New ticket</div>
          </div>
          <div class="preview-stats">
            <div class="preview-stat">
              <div class="preview-stat-num" style="color:var(--status-open)">12</div>
              <div class="preview-stat-label">Open</div>
            </div>
            <div class="preview-stat">
              <div class="preview-stat-num" style="color:var(--status-progress)">4</div>
              <div class="preview-stat-label">In Progress</div>
            </div>
            <div class="preview-stat">
              <div class="preview-stat-num" style="color:var(--priority-high)">3</div>
              <div class="preview-stat-label">High Priority</div>
            </div>
            <div class="preview-stat">
              <div class="preview-stat-num">24</div>
              <div class="preview-stat-label">Closed</div>
            </div>
          </div>
          <div class="preview-chips">
            <span class="preview-chip active">All <strong>40</strong></span>
            <span class="preview-chip">My tickets</span>
            <span class="preview-chip">High priority</span>
            <span class="preview-chip">Unresolved</span>
          </div>
          <div class="preview-table">
            <div class="preview-table-head">
              <span>ID</span><span>Title</span><span>Status</span><span>Priority</span>
            </div>
            <div class="preview-table-row">
              <span class="preview-tid">TH-001</span>
              <span class="preview-title">Login page broken on Safari</span>
              <span class="preview-pill open">Open</span>
              <span class="preview-pill high">High</span>
            </div>
            <div class="preview-table-row">
              <span class="preview-tid">TH-002</span>
              <span class="preview-title">Email notifications not sending</span>
              <span class="preview-pill progress">In Progress</span>
              <span class="preview-pill medium">Medium</span>
            </div>
            <div class="preview-table-row">
              <span class="preview-tid">TH-003</span>
              <span class="preview-title">CSV export fails for large datasets</span>
              <span class="preview-pill open">Open</span>
              <span class="preview-pill high">High</span>
            </div>
            <div class="preview-table-row">
              <span class="preview-tid">TH-004</span>
              <span class="preview-title">Dark mode contrast issue</span>
              <span class="preview-pill closed">Closed</span>
              <span class="preview-pill low">Low</span>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
</section>

<!-- ═══ Feature 1 — Track Tickets ═══ -->
<section class="lp-feature">
  <div class="lp-feature-inner">
    <div class="lp-feature-text" data-reveal>
      <p class="lp-label">Features</p>
      <h2 class="lp-h2">One place for<br>every request.</h2>
      <p class="lp-body">Every ticket is logged, timestamped, and organized. No more losing track of what&rsquo;s open, what&rsquo;s being handled, and what&rsquo;s done.</p>
      <a href="/auth/register.php" class="lp-link">Start tracking &rarr;</a>
    </div>
    <div class="lp-feature-visual" data-reveal data-delay="1">
      <div class="feat-card">
        <div class="feat-card-header">
          All Tickets <span class="feat-badge">12 open</span>
        </div>
        <div class="feat-ticket-row">
          <div class="feat-tick-accent" style="background:var(--status-open)"></div>
          <div class="feat-tick-info">
            <span class="feat-tick-id">TH-001</span>
            <span class="feat-tick-title">Login page broken</span>
          </div>
          <span class="feat-pill open">Open</span>
          <span class="feat-time">2m ago</span>
        </div>
        <div class="feat-ticket-row">
          <div class="feat-tick-accent" style="background:var(--status-progress)"></div>
          <div class="feat-tick-info">
            <span class="feat-tick-id">TH-002</span>
            <span class="feat-tick-title">Email not sending</span>
          </div>
          <span class="feat-pill progress">In Progress</span>
          <span class="feat-time">1h ago</span>
        </div>
        <div class="feat-ticket-row">
          <div class="feat-tick-accent" style="background:var(--priority-high)"></div>
          <div class="feat-tick-info">
            <span class="feat-tick-id">TH-003</span>
            <span class="feat-tick-title">Export fails on CSV</span>
          </div>
          <span class="feat-pill open">Open</span>
          <span class="feat-time">3h ago</span>
        </div>
        <div class="feat-ticket-row">
          <div class="feat-tick-accent" style="background:var(--status-closed)"></div>
          <div class="feat-tick-info">
            <span class="feat-tick-id">TH-004</span>
            <span class="feat-tick-title">Dark mode glitch</span>
          </div>
          <span class="feat-pill closed">Closed</span>
          <span class="feat-time">1d ago</span>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ═══ Feature 2 — AI Assistant ═══ -->
<section id="ai" class="lp-feature lp-feature--alt lp-feature--flip">
  <div class="lp-feature-inner">
    <div class="lp-feature-text" data-reveal>
      <p class="lp-label">AI Assistant</p>
      <h2 class="lp-h2">Ask questions.<br>Get answers.</h2>
      <p class="lp-body">The built-in Ollama assistant knows your tickets. Ask why something is open, what&rsquo;s urgent, or what happened last week &mdash; in plain language.</p>
      <a href="/auth/register.php" class="lp-link">Meet the assistant &rarr;</a>
    </div>
    <div class="lp-feature-visual" data-reveal data-delay="1">
      <div class="chat-window">
        <div class="chat-header">
          <span class="chat-online-dot"></span>
          AI Assistant
          <span class="chat-online-label">Online</span>
        </div>
        <div class="chat-body">
          <div class="chat-bubble user">Why is TH-023 still open?</div>
          <div class="chat-bubble ai">TH-023 has been open for 3 days. Priority: High. Last update: no response from assignee. Suggested action: follow up or escalate.</div>
          <div class="chat-bubble user">Escalate it</div>
          <div class="chat-bubble ai">Done. TH-023 priority updated to Critical.</div>
        </div>
        <div class="chat-input-bar">
          <div class="chat-input-bar-field">Ask about your tickets&hellip;</div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ═══ Feature 3 — Priorities & Status ═══ -->
<section class="lp-feature">
  <div class="lp-feature-inner">
    <div class="lp-feature-text" data-reveal>
      <p class="lp-label">Workflow</p>
      <h2 class="lp-h2">Always know<br>what&rsquo;s urgent.</h2>
      <p class="lp-body">Three priority levels. Three statuses. A clear, simple workflow from the moment a ticket is created to the moment it&rsquo;s resolved.</p>
      <a href="/auth/register.php" class="lp-link">See the workflow &rarr;</a>
    </div>
    <div class="lp-feature-visual" data-reveal data-delay="1">
      <div class="kanban-board">
        <div class="kanban-col">
          <div class="kanban-col-head open">Open</div>
          <div class="kanban-cards">
            <div class="kanban-card"><span class="kanban-card-id">TH-001</span>Login page broken</div>
            <div class="kanban-card"><span class="kanban-card-id">TH-003</span>Export fails</div>
          </div>
        </div>
        <div class="kanban-col">
          <div class="kanban-col-head progress">In Progress</div>
          <div class="kanban-cards">
            <div class="kanban-card"><span class="kanban-card-id">TH-002</span>Email not sending</div>
          </div>
        </div>
        <div class="kanban-col">
          <div class="kanban-col-head closed">Closed</div>
          <div class="kanban-cards">
            <div class="kanban-card"><span class="kanban-card-id">TH-004</span>Dark mode <span class="kanban-card-check">&#10003;</span></div>
            <div class="kanban-card"><span class="kanban-card-id">TH-005</span>API timeout <span class="kanban-card-check">&#10003;</span></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ═══ Testimonials ═══ -->
<section class="lp-testimonials">
  <div class="lp-testimonials-head">
    <p class="lp-label" data-reveal>Testimonials</p>
    <h2 class="lp-h2" data-reveal data-delay="1">Trusted by teams.</h2>
  </div>
  <div class="testimonials-grid">
    <div class="testimonial-card" data-reveal data-delay="1">
      <p class="testimonial-quote">&ldquo;We switched from spreadsheets to Ticketsystem and never looked back. The AI assistant saves us hours every week.&rdquo;</p>
      <div class="testimonial-author">
        <img src="https://randomuser.me/api/portraits/women/44.jpg" alt="Sarah K." loading="lazy" width="44" height="44">
        <div>
          <div class="testimonial-name">Sarah K.</div>
          <div class="testimonial-role">IT Manager, M&uuml;ller AG</div>
        </div>
      </div>
    </div>
    <div class="testimonial-card" data-reveal data-delay="2">
      <p class="testimonial-quote">&ldquo;Clean, fast, and the priority system is exactly what our team needed. Setup took minutes.&rdquo;</p>
      <div class="testimonial-author">
        <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="Thomas B." loading="lazy" width="44" height="44">
        <div>
          <div class="testimonial-name">Thomas B.</div>
          <div class="testimonial-role">Support Lead, Weber GmbH</div>
        </div>
      </div>
    </div>
    <div class="testimonial-card" data-reveal data-delay="3">
      <p class="testimonial-quote">&ldquo;Open source and self-hosted. Finally a ticket tool I can trust with our internal data.&rdquo;</p>
      <div class="testimonial-author">
        <img src="https://randomuser.me/api/portraits/women/68.jpg" alt="Lena M." loading="lazy" width="44" height="44">
        <div>
          <div class="testimonial-name">Lena M.</div>
          <div class="testimonial-role">Developer, Stark Solutions</div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ═══ Steps ═══ -->
<section id="how-it-works" class="lp-steps">
  <div class="lp-steps-head">
    <p class="lp-label" data-reveal>How it works</p>
    <h2 class="lp-h2" data-reveal data-delay="1">Up and running in minutes.</h2>
  </div>
  <div class="steps-grid lp-inner">
    <div class="step" data-reveal data-delay="1">
      <div class="step-num">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><line x1="18" y1="2" x2="22" y2="6"/><path d="M7.5 20.5L19 9l-4-4L3.5 16.5 2 22z"/></svg>
      </div>
      <div class="step-title">Create a ticket</div>
      <p class="step-body">Describe the issue, set a priority level, and submit. Takes 30 seconds.</p>
    </div>
    <div class="step" data-reveal data-delay="2">
      <div class="step-num">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="22 7 13.5 15.5 8.5 10.5 2 17"/><polyline points="16 7 22 7 22 13"/></svg>
      </div>
      <div class="step-title">Track progress</div>
      <p class="step-body">Update the status as work happens. Open &rarr; In Progress &rarr; Closed.</p>
    </div>
    <div class="step" data-reveal data-delay="3">
      <div class="step-num">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg>
      </div>
      <div class="step-title">Resolve with AI</div>
      <p class="step-body">Ask the Ollama assistant for context, summaries, or to suggest next actions.</p>
    </div>
  </div>
</section>

<!-- ═══ FAQ ═══ -->
<section id="faq" class="lp-faq">
  <div class="faq-section-head">
    <p class="lp-label" data-reveal>FAQ</p>
    <h2 class="lp-h2" data-reveal data-delay="1">Common questions.</h2>
  </div>
  <div class="lp-faq-inner" data-reveal data-delay="2">

    <div class="faq-item">
      <button class="faq-q">What is Ticketsystem?<svg class="faq-chevron" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg></button>
      <div class="faq-body"><div class="faq-body-inner">Ticketsystem is an open-source ticket management web app built with PHP and MySQL. It lets teams create, track, and resolve support tickets with a built-in AI assistant powered by Ollama.</div></div>
    </div>

    <div class="faq-item">
      <button class="faq-q">Is it really free?<svg class="faq-chevron" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg></button>
      <div class="faq-body"><div class="faq-body-inner">Yes. Ticketsystem is released under the MIT License. You can use, modify, and distribute it freely. There are no paid plans or vendor lock-in.</div></div>
    </div>

    <div class="faq-item">
      <button class="faq-q">How does the AI assistant work?<svg class="faq-chevron" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg></button>
      <div class="faq-body"><div class="faq-body-inner">The AI assistant runs locally via Ollama &mdash; no data leaves your server. You can ask it questions about your tickets in plain language and it will respond using the context from your ticket database.</div></div>
    </div>

    <div class="faq-item">
      <button class="faq-q">What are the system requirements?<svg class="faq-chevron" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg></button>
      <div class="faq-body"><div class="faq-body-inner">PHP 8.0+, MySQL 5.7+ or MariaDB 10.4+, and a web server (Apache or Nginx). For the AI assistant you also need Ollama installed on the same server or accessible via the network.</div></div>
    </div>

    <div class="faq-item">
      <button class="faq-q">Can multiple users work together?<svg class="faq-chevron" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg></button>
      <div class="faq-body"><div class="faq-body-inner">Yes. Ticketsystem supports two roles: regular users who can create and manage their own tickets, and admins who can view and manage all tickets across the system.</div></div>
    </div>

    <div class="faq-item">
      <button class="faq-q">Is my data secure?<svg class="faq-chevron" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg></button>
      <div class="faq-body"><div class="faq-body-inner">Passwords are hashed with bcrypt. All database queries use PDO prepared statements. Sessions are regenerated on login. Since you self-host, your data never leaves your own infrastructure.</div></div>
    </div>

    <div class="faq-item">
      <button class="faq-q">How do I get started?<svg class="faq-chevron" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg></button>
      <div class="faq-body"><div class="faq-body-inner">Create an account using the &ldquo;Get started free&rdquo; button above. You&rsquo;ll be up and running in under two minutes.</div></div>
    </div>

    <div class="faq-item">
      <button class="faq-q">Can I change the priority after creating a ticket?<svg class="faq-chevron" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg></button>
      <div class="faq-body"><div class="faq-body-inner">Yes. You can edit any ticket you created at any time, including its title, description, status, and priority. Admins can edit all tickets.</div></div>
    </div>

  </div>
</section>

<!-- ═══ CTA Band ═══ -->
<section class="lp-cta-band" aria-label="Get started">
  <h2 data-reveal>Ready to get organized?</h2>
  <p data-reveal data-delay="1">Free, open source, and self-hosted. No vendor lock-in.</p>
  <div class="cta-actions" data-reveal data-delay="2">
    <a href="/auth/register.php" class="btn-white">Create your account</a>
    <a href="#features" class="btn-ghost-white">View features</a>
  </div>
</section>

<!-- ═══ Footer ═══ -->
<footer class="lp-footer">
  <div class="lp-footer-inner">
    <div class="footer-brand">
      <a href="/" class="nav-logo">
        <span class="nav-logo-mark">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" style="display:block"><path d="M3 4 L9 4 Q12 7 15 4 L21 4 Q23 4 23 6 L23 18 Q23 20 21 20 L15 20 Q12 17 9 20 L3 20 Q1 20 1 18 L1 6 Q1 4 3 4 Z"/><polyline points="8,13 11,16 16.5,9"/></svg>
        </span>
        Ticketsystem
      </a>
      <p class="footer-tagline">An open-source ticket management system with a built-in AI assistant. Self-hosted, privacy-first.</p>
      <p class="footer-copy">&copy; <?php echo date('Y'); ?> Ticketsystem. MIT License.</p>
    </div>
    <div class="footer-col">
      <h4>Product</h4>
      <ul>
        <li><a href="#features">Features</a></li>
        <li><a href="#ai">AI Assistant</a></li>
        <li><a href="#how-it-works">How it works</a></li>
        <li><a href="#faq">FAQ</a></li>
      </ul>
    </div>
    <div class="footer-col">
      <h4>Legal</h4>
      <ul>
        <li><a href="/legal/datenschutz.php">Datenschutz</a></li>
        <li><a href="/legal/impressum.php">Impressum</a></li>
        <li><a href="/legal/nutzungsbedingungen.php">Nutzungsbedingungen</a></li>
      </ul>
    </div>
  </div>
</footer>

<script src="/public/js/landing.js"></script>
</body>
</html>
