<?php $version= env('JS_VERSION'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Primary SEO -->
    <title>Shikshora – Smart Student Management System | School ERP Software</title>

    <meta name="description" content="Shikshora ek smart student management system hai jo school, college aur institutes ke liye complete ERP solution provide karta hai – attendance, fees, students & reports sab ek jagah.">

    <meta name="keywords" content="student management system, school ERP software, college management system, online student ERP, attendance management system, fee management software, school management India, Shikshora software, education ERP solution">

    <meta name="author" content="Aadhyasri Web Solutions">

    <!-- Open Graph (Facebook, WhatsApp, LinkedIn) -->
    <meta property="og:title" content="Shikshora – Smart Student Management System">
    <meta property="og:description" content="Manage students, fees, attendance & reports easily with Shikshora ERP. Perfect solution for schools & colleges.">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url('/') }}">
    <meta property="og:image" content="{{ url('assets/img/favicon.png') }}">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Shikshora – Smart Student Management System">
    <meta name="twitter:description" content="All-in-one school ERP software to manage students, attendance and fees efficiently.">
    <meta name="twitter:image" content="{{ url('assets/img/favicon.png') }}">

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" sizes="32x32" href="{{ url('assets/img/favicon.png') }}">

    <!-- CSS -->
    <link href="{{ url('front-end/css/custom.css?v='.$version) }}" rel="stylesheet">
</head>
<body>

  <!-- NAV -->
  <nav>
    <a href="{{url('/')}}" class="nav-logo">
      <div class="logo-icon">
        <svg viewBox="0 0 24 24"><path d="M12 3L1 9l11 6 9-4.91V17h2V9L12 3zM5 13.18v4L12 21l7-3.82v-4L12 17l-7-3.82z"/></svg>
      </div>
      <img src="{{url('assets/img/sx1-logo.png')}}">
      
    </a>
    <ul class="nav-links">
      <li><a href="#features">Features</a></li>
      <li><a href="#audience">Who It's For</a></li>
      <li><a href="#how">How It Works</a></li>
      <li><a href="#testimonials">Reviews</a></li>
      <li><a href="{{url('login')}}" class="nav-cta">Login</a></li>
    </ul>
  </nav>

  <!-- HERO -->
  <section class="hero">
    <div class="hero-bg"></div>
    <div class="hero-grid"></div>

    <div class="hero-badge"><span></span> Trusted by 500+ Institutions Across India</div>

    <h1>The Smartest Way to<br>Manage <span class="highlight">Every Student</span></h1>

    <p class="hero-sub">
      Shikshora brings admissions, attendance, fees, grades, and communication
      into one powerful platform — built for schools, colleges, universities & coaching institutes.
    </p>

    <div class="hero-actions">
      <a href="#cta" class="btn-primary">
        <svg width="18" height="18" fill="white" viewBox="0 0 24 24"><path d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
        Start Free Trial
      </a>
      <a href="#features" class="btn-secondary">
        <svg width="18" height="18" fill="#343aa0" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
        Watch Demo
      </a>
    </div>

    <div class="hero-stats">
      <div class="stat">
        <div class="stat-num">1K+</div>
        <div class="stat-label">Students Managed</div>
      </div>
      <div class="stat-divider"></div>
      <div class="stat">
        <div class="stat-num">10+</div>
        <div class="stat-label">Institutions</div>
      </div>
      <div class="stat-divider"></div>
      <div class="stat">
        <div class="stat-num">99.9%</div>
        <div class="stat-label">Uptime SLA</div>
      </div>
      <div class="stat-divider"></div>
      <div class="stat">
        <div class="stat-num">4.9★</div>
        <div class="stat-label">Average Rating</div>
      </div>
    </div>

    <!-- DASHBOARD MOCKUP -->
    <div class="hero-mockup">
      <div class="mockup-window">
        <div class="mockup-bar">
          <div class="mockup-dot" style="background:#ff5f57"></div>
          <div class="mockup-dot" style="background:#febc2e"></div>
          <div class="mockup-dot" style="background:#28c840"></div>
        </div>
        <div class="mockup-body">
          <div class="mockup-sidebar">
            <div class="sidebar-item active"><span class="dot"></span> Dashboard</div>
            <div class="sidebar-item"><span class="dot"></span> Students</div>
            <div class="sidebar-item"><span class="dot"></span> Attendance</div>
            <div class="sidebar-item"><span class="dot"></span> Fees & Billing</div>
            <div class="sidebar-item"><span class="dot"></span> Examinations</div>
            <div class="sidebar-item"><span class="dot"></span> Reports</div>
            <div class="sidebar-item"><span class="dot"></span> Communication</div>
            <div class="sidebar-item"><span class="dot"></span> Settings</div>
          </div>
          <div class="mockup-main">
            <div class="dash-title">📊 Overview — April 2026</div>
            <div class="dash-cards">
              <div class="dash-card">
                <div class="dash-card-label">Total Students</div>
                <div class="dash-card-val">2,847</div>
                <div class="dash-card-tag tag-green">↑ 12% this month</div>
              </div>
              <div class="dash-card">
                <div class="dash-card-label">Attendance Today</div>
                <div class="dash-card-val">91.4%</div>
                <div class="dash-card-tag tag-blue">↑ Above average</div>
              </div>
              <div class="dash-card">
                <div class="dash-card-label">Fees Collected</div>
                <div class="dash-card-val">₹8.2L</div>
                <div class="dash-card-tag tag-orange">74% of target</div>
              </div>
              <div class="dash-card">
                <div class="dash-card-label">Pending Dues</div>
                <div class="dash-card-val">148</div>
                <div class="dash-card-tag tag-orange">Reminders sent</div>
              </div>
            </div>
            <div class="dash-table">
              <div class="dash-table-head">
                <span>Student Name</span>
                <span>Class</span>
                <span>Attendance</span>
                <span>Status</span>
              </div>
              <div class="dash-row">
                <span>Priya Sharma</span><span>XII-A</span><span>96%</span>
                <span><span class="badge badge-green">Active</span></span>
              </div>
              <div class="dash-row">
                <span>Rahul Mehta</span><span>X-B</span><span>78%</span>
                <span><span class="badge badge-orange">At Risk</span></span>
              </div>
              <div class="dash-row">
                <span>Anjali Gupta</span><span>XI-C</span><span>91%</span>
                <span><span class="badge badge-green">Active</span></span>
              </div>
              <div class="dash-row">
                <span>Vikram Rao</span><span>IX-A</span><span>85%</span>
                <span><span class="badge badge-blue">Fee Due</span></span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- FEATURES -->
  <section class="section features" id="features">
    <div class="section-label">Core Features</div>
    <h2 class="section-title">Everything Your Institution Needs</h2>
    <p class="section-sub">From day one to graduation, Shikshora handles every administrative task so educators can focus on teaching.</p>

    <div class="features-grid">
      <div class="feature-card">
        <div class="feature-icon">
          <svg viewBox="0 0 24 24"><path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/></svg>
        </div>
        <h3>Student Admissions</h3>
        <p>Online application forms, document uploads, merit-based selection, and automated enrollment — all paperless and instant.</p>
      </div>
      <div class="feature-card">
        <div class="feature-icon green">
          <svg viewBox="0 0 24 24"><path d="M19 3h-1V1h-2v2H8V1H6v2H5c-1.11 0-1.99.9-1.99 2L3 19c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V8h14v11zM7 10h5v5H7z"/></svg>
        </div>
        <h3>Attendance Tracking</h3>
        <p>Biometric, RFID, or manual entry. Real-time dashboards and automated SMS/email alerts to parents for absentees.</p>
      </div>
      <div class="feature-card">
        <div class="feature-icon orange">
          <svg viewBox="0 0 24 24"><path d="M11.8 10.9c-2.27-.59-3-1.2-3-2.15 0-1.09 1.01-1.85 2.7-1.85 1.78 0 2.44.85 2.5 2.1h2.21c-.07-1.72-1.12-3.3-3.21-3.81V3h-3v2.16c-1.94.42-3.5 1.68-3.5 3.61 0 2.31 1.91 3.46 4.7 4.13 2.5.6 3 1.48 3 2.41 0 .69-.49 1.79-2.7 1.79-2.06 0-2.87-.92-2.98-2.1h-2.2c.12 2.19 1.76 3.42 3.68 3.83V21h3v-2.15c1.95-.37 3.5-1.5 3.5-3.55 0-2.84-2.43-3.81-4.7-4.4z"/></svg>
        </div>
        <h3>Fee Management</h3>
        <p>Custom fee structures, online payments, automatic receipts, due reminders, and comprehensive financial reports.</p>
      </div>
      <div class="feature-card">
        <div class="feature-icon">
          <svg viewBox="0 0 24 24"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-5 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z"/></svg>
        </div>
        <h3>Exam & Grades</h3>
        <p>Schedule exams, enter marks, generate report cards, track progress over terms, and auto-calculate rankings and GPA.</p>
      </div>
      <div class="feature-card">
        <div class="feature-icon green">
          <svg viewBox="0 0 24 24"><path d="M20 2H4c-1.1 0-2 .9-2 2v18l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm0 14H6l-2 2V4h16v12z"/></svg>
        </div>
        <h3>Parent Communication</h3>
        <p>Broadcast announcements, send report cards, share notices, and have direct chats with parents through the app.</p>
      </div>
      <div class="feature-card">
        <div class="feature-icon orange">
          <svg viewBox="0 0 24 24"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-7 3c1.93 0 3.5 1.57 3.5 3.5S13.93 13 12 13s-3.5-1.57-3.5-3.5S10.07 6 12 6zm7 13H5v-.23c0-.62.28-1.2.76-1.58C7.47 15.82 9.64 15 12 15s4.53.82 6.24 2.19c.48.38.76.97.76 1.58V19z"/></svg>
        </div>
        <h3>Staff & HR Module</h3>
        <p>Manage teacher profiles, assign subjects, track their attendance, process payroll, and evaluate performance.</p>
      </div>
    </div>
  </section>

  <!-- AUDIENCE -->
  <section class="section audience" id="audience">
    <div class="section-label">Built For</div>
    <h2 class="section-title">Designed for Every Type of Institution</h2>
    <p class="section-sub">Whether you run a small coaching centre or a large university, Shikshora scales to fit your exact needs.</p>

    <div class="audience-grid">
      <div class="audience-card blue">
        <div class="audience-emoji">🏫</div>
        <h3>Schools</h3>
        <p>From KG to Class XII — manage classes, timetables, homework, parent meetings, and annual events effortlessly.</p>
      </div>
      <div class="audience-card green">
        <div class="audience-emoji">🎓</div>
        <h3>Colleges</h3>
        <p>Handle semester systems, elective choices, internal marks, backlogs, and placement tracking in one place.</p>
      </div>
      <div class="audience-card purple">
        <div class="audience-emoji">🏛️</div>
        <h3>Universities</h3>
        <p>Multi-campus management, department-wise reports, research tracking, and regulatory compliance made simple.</p>
      </div>
      <div class="audience-card orange">
        <div class="audience-emoji">📚</div>
        <h3>Coaching Institutes</h3>
        <p>Batch management, test series, student rankings, demo class scheduling, and enquiry-to-enrollment funnels.</p>
      </div>
    </div>
  </section>

  <!-- HOW IT WORKS -->
  <section class="section how" id="how">
    <div style="text-align:center">
      <div class="section-label">How It Works</div>
      <h2 class="section-title" style="margin:0 auto">Up & Running in 3 Simple Steps</h2>
      <p class="section-sub" style="margin:14px auto 0">No technical expertise needed. Our team handles the entire onboarding for you.</p>
    </div>
    <div class="steps">
      <div class="step">
        <div class="step-num">1</div>
        <h3>Create Your Account</h3>
        <p>Sign up free, enter your institution details, and get a customised Shikshora workspace within minutes.</p>
      </div>
      <div class="step">
        <div class="step-num">2</div>
        <h3>Import Your Data</h3>
        <p>Upload student lists via Excel or migrate from your existing system. Our team assists at every step.</p>
      </div>
      <div class="step">
        <div class="step-num">3</div>
        <h3>Go Live</h3>
        <p>Invite your staff, share the parent app, and start managing your institution smarter from day one.</p>
      </div>
      <div class="step">
        <div class="step-num">4</div>
        <h3>Grow with Insights</h3>
        <p>Use powerful analytics to identify trends, improve performance, and make data-driven decisions.</p>
      </div>
    </div>
  </section>

  <!-- TESTIMONIALS -->
  <section class="section testimonials" id="testimonials">
    <div class="section-label">Testimonials</div>
    <h2 class="section-title">Loved by Educators Nationwide</h2>
    <p class="section-sub">Real feedback from principals, administrators, and teachers who use Shikshora every day.</p>

    <div class="testi-grid">
      <div class="testi-card">
        <div class="testi-stars">★★★★★</div>
        <p class="testi-text">"The parent communication module is outstanding. Parents love the real-time updates and we've seen much better engagement since we switched."</p>
        <div class="testi-author">
          <div class="testi-avatar">SB</div>
          <div>
            <div class="testi-name">Sudeep Banerjee</div>
            <div class="testi-role">Principal, Swami Vivekanand Academy Junior High School, Haridwar</div>
          </div>
        </div>
      </div>
      <div class="testi-card">
        <div class="testi-stars">★★★★★</div>
        <p class="testi-text">"Shikshora completely transformed how we handle fees and attendance. What used to take our staff a full day now takes under an hour."</p>
        <div class="testi-author">
          <div class="testi-avatar">GH</div>
          <div>
            <div class="testi-name">Gaurav Hujela</div>
            <div class="testi-role">Principal, Gyan Ganga Mata School, Haridwar</div>
          </div>
        </div>
      </div>
      
     <!--  <div class="testi-card">
        <div class="testi-stars">★★★★★</div>
        <p class="testi-text">"We manage 4,000 students across two campuses. Shikshora's multi-campus support and detailed reports have been a game changer for our team."</p>
        <div class="testi-author">
          <div class="testi-avatar">AK</div>
          <div>
            <div class="testi-name">Subash Rathor</div>
            <div class="testi-role">Paradise Academy High School</div>
          </div>
        </div>
      </div>
    </div> -->
  </section>

  <!-- CTA -->

  <section class="cta-section" id="cta">
    <div class="cta-box">
      <h2>Ready to Modernise Your Institution?</h2>
      <p>Join 10+ institutions already using Shikshora. Get in touch with our team — we'll help you get started with full onboarding support.</p>
      <div class="contact-items">
        <a href="mailto:aadhyasriwebsolutions@gmail.com" class="contact-item">
          <div class="contact-icon">
            <svg viewBox="0 0 24 24" fill="white"><path d="M20 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"></path></svg>
          </div>
          <div class="contact-info">
            <span class="contact-label">Email Us</span>
            <span class="contact-value">aadhyasriwebsolutions@gmail.com</span>
          </div>
        </a>
        <a href="tel:+917351334717" class="contact-item">
          <div class="contact-icon green">
            <svg viewBox="0 0 24 24" fill="white"><path d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"></path></svg>
          </div>
          <div class="contact-info">
            <span class="contact-label">Call Us</span>
            <span class="contact-value">+91-7351334717</span>
          </div>
        </a>
        <a href="tel:+917088262941" class="contact-item">
          <div class="contact-icon green">
            <svg viewBox="0 0 24 24" fill="white"><path d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"></path></svg>
          </div>
          <div class="contact-info">
            <span class="contact-label">Call Us</span>
            <span class="contact-value">+91-7088262941</span>
          </div>
        </a>
      </div>
      <p style="margin-top:24px; font-size:0.8rem; color:var(--muted)">✓ Free Demo Available &nbsp; ✓ Full Onboarding Support &nbsp; ✓ Quick Response Guaranteed</p>
    </div>
  </section>
 <!--  <section class="cta-section" id="cta">
    <div class="cta-box">
      <h2>Ready to Modernise Your Institution?</h2>
      <p>Join 10+ institutions already using Shikshora. Start your free 30-day trial — no credit card required, full support included.</p>
      <div class="cta-form">
        <input class="cta-input" type="email" placeholder="Enter your email address" />
        <a href="#" class="btn-primary">Get Free Trial →</a>
      </div>
      <p style="margin-top:16px; font-size:0.8rem; color:var(--muted)">✓ Free for 30 days &nbsp; ✓ Full feature access &nbsp; ✓ Dedicated onboarding support</p>
      <ul>
        <li>
          <a href="mailto:aadhyasriwebsolutions@gmail.com">aadhyasriwebsolutions@gmail.com</a>
        </li>
        <li>
          <a href="tel:+91-7351334717">+91-7351334717</a>
        </li>
        <li>
          <a href="tel:+91-7088262941">+91-7088262941</a>
        </li>
      </ul>
    </div>
  </section> -->

  <!-- FOOTER -->
  <footer>
    <div class="footer-grid">
      <div class="footer-brand">
        <a href="#" class="nav-logo">
          <div class="logo-icon">
            <svg viewBox="0 0 24 24"><path d="M12 3L1 9l11 6 9-4.91V17h2V9L12 3zM5 13.18v4L12 21l7-3.82v-4L12 17l-7-3.82z"/></svg>
          </div>
          <img src="{{url('assets/img/sx1-logo-light.png')}}">
        </a>
        <p>Empowering institutions across India with smart, simple, and scalable student management software.</p>
      </div>
      <!-- <div class="footer-col">
        <h4>Product</h4>
        <ul>
          <li><a href="#">Features</a></li>
          <li><a href="#">Pricing</a></li>
          <li><a href="#">Integrations</a></li>
          <li><a href="#">Changelog</a></li>
          <li><a href="#">Roadmap</a></li>
        </ul>
      </div>
      <div class="footer-col">
        <h4>Company</h4>
        <ul>
          <li><a href="#">About Us</a></li>
          <li><a href="#">Careers</a></li>
          <li><a href="#">Blog</a></li>
          <li><a href="#">Press Kit</a></li>
          <li><a href="#">Contact</a></li>
        </ul>
      </div>
      <div class="footer-col">
        <h4>Support</h4>
        <ul>
          <li><a href="#">Help Centre</a></li>
          <li><a href="#">Documentation</a></li>
          <li><a href="#">Video Tutorials</a></li>
          <li><a href="#">Privacy Policy</a></li>
          <li><a href="#">Terms of Service</a></li>
        </ul>
      </div> -->
    </div>
    <div class="footer-bottom">
      <p>© 2026 Shikshora. All rights reserved. Made with ❤️ in India.</p>
      <p>📧 <a href="mailto:aadhyasriwebsolutions@gmail.com">aadhyasriwebsolutions@gmail.com</a> &nbsp; 📞 <a href="tel:7351334717">+91-7351334717</a>,&nbsp;<a href="tel:7088262941">+91-7088262941</a></p>
    </div>
  </footer>

</body>
</html>
