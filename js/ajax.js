'use strict';

document.addEventListener('DOMContentLoaded', function () {

  // =========================================================
  // 1. Particles.js initialization
  // =========================================================
  if (typeof particlesJS !== 'undefined' && document.getElementById('particles-js')) {
    particlesJS('particles-js', {
      particles: {
        number: { value: 60, density: { enable: true, value_area: 800 } },
        color: { value: '#c9a84c' },
        shape: { type: 'circle' },
        opacity: { value: 0.3, random: true },
        size: { value: 3, random: true },
        line_linked: { enable: true, distance: 150, color: '#c9a84c', opacity: 0.1, width: 1 },
        move: { enable: true, speed: 1.5, direction: 'none', random: true, straight: false, out_mode: 'out' }
      },
      interactivity: {
        detect_on: 'canvas',
        events: { onhover: { enable: true, mode: 'repulse' }, onclick: { enable: true, mode: 'push' }, resize: true },
        modes: { repulse: { distance: 100 }, push: { particles_nb: 4 } }
      },
      retina_detect: true
    });
  }

  // =========================================================
  // 2. IntersectionObserver – scroll animations
  // =========================================================
  const scrollObserver = new IntersectionObserver(function (entries, observer) {
    entries.forEach(function (entry) {
      if (entry.isIntersecting) {
        entry.target.classList.add('animated');
        observer.unobserve(entry.target);
      }
    });
  }, { threshold: 0.15, rootMargin: '0px 0px -50px 0px' });

  document.querySelectorAll('.animate-on-scroll').forEach(function (el) {
    scrollObserver.observe(el);
  });

  // =========================================================
  // 3. Animated counter
  // =========================================================
  function animateCounter(element, target, duration) {
    duration = duration !== undefined ? duration : 2000;
    var raw = String(target).trim();
    var hasPlus = raw.endsWith('+');
    var hasPercent = raw.endsWith('%');
    var numericStr = raw.replace(/[+%]/g, '');
    var end = parseFloat(numericStr);
    var start = 0;
    var startTime = null;

    function easeOutQuart(t) {
      return 1 - Math.pow(1 - t, 4);
    }

    function step(timestamp) {
      if (!startTime) startTime = timestamp;
      var elapsed = timestamp - startTime;
      var progress = Math.min(elapsed / duration, 1);
      var easedProgress = easeOutQuart(progress);
      var current = Math.floor(easedProgress * end);

      var display = current.toLocaleString('de-DE');
      if (hasPlus) display += '+';
      if (hasPercent) display += '%';
      element.textContent = display;

      if (progress < 1) {
        requestAnimationFrame(step);
      } else {
        var finalDisplay = end.toLocaleString('de-DE');
        if (hasPlus) finalDisplay += '+';
        if (hasPercent) finalDisplay += '%';
        element.textContent = finalDisplay;
      }
    }

    requestAnimationFrame(step);
  }

  var counterObserver = new IntersectionObserver(function (entries, observer) {
    entries.forEach(function (entry) {
      if (entry.isIntersecting) {
        var el = entry.target;
        var target = el.getAttribute('data-target') || el.textContent.trim();
        animateCounter(el, target);
        observer.unobserve(el);
      }
    });
  }, { threshold: 0.3 });

  document.querySelectorAll('[data-counter]').forEach(function (el) {
    counterObserver.observe(el);
  });

  // =========================================================
  // 4. Live activity widget
  // =========================================================
  var activityMessages = [
    { text: 'Neue Analyse gestartet', time: 'gerade eben', color: '#28a745' },
    { text: 'Anfrage aus München eingegangen', time: 'vor 3 Min.', color: '#c9a84c' },
    { text: 'Blockchain-Prüfung abgeschlossen', time: 'vor 8 Min.', color: '#17a2b8' },
    { text: 'Termin bestätigt', time: 'vor 12 Min.', color: '#28a745' },
    { text: 'Wallet-Analyse gestartet', time: 'vor 15 Min.', color: '#c9a84c' },
    { text: 'Anfrage aus Berlin eingegangen', time: 'vor 18 Min.', color: '#17a2b8' }
  ];

  var activityIndex = 0;
  var activityWidget = document.getElementById('activityWidget');
  var activityText = document.getElementById('activityText');
  var activityTime = document.getElementById('activityTime');
  var activityDot = document.getElementById('activityDot');

  function updateActivity() {
    if (!activityWidget || !activityText || !activityTime) return;
    var msg = activityMessages[activityIndex % activityMessages.length];
    activityWidget.style.opacity = '0';
    activityWidget.style.transition = 'opacity 0.4s ease';

    setTimeout(function () {
      activityText.textContent = msg.text;
      activityTime.textContent = msg.time;
      if (activityDot) activityDot.style.backgroundColor = msg.color;
      activityWidget.style.opacity = '1';
      activityIndex++;
    }, 400);
  }

  if (activityWidget) {
    updateActivity();
    setInterval(updateActivity, 4000);
  }

  // =========================================================
  // 5. Multi-step form logic
  // =========================================================
  var multiStepForm = document.getElementById('multiStepForm');
  var formSteps = document.querySelectorAll('.form-step');
  var totalSteps = formSteps.length;
  var currentStep = 1;

  function showStep(step) {
    formSteps.forEach(function (el, idx) {
      el.style.display = (idx + 1 === step) ? 'block' : 'none';
    });
    updateProgressBar();
    updateStepDots();
  }

  function updateProgressBar() {
    var bar = document.getElementById('formProgressBar');
    if (bar) {
      bar.style.width = ((currentStep / totalSteps) * 100) + '%';
      bar.setAttribute('aria-valuenow', Math.round((currentStep / totalSteps) * 100));
    }
  }

  function updateStepDots() {
    var dots = document.querySelectorAll('.step-dot');
    dots.forEach(function (dot, idx) {
      dot.classList.toggle('active', idx + 1 === currentStep);
      dot.classList.toggle('completed', idx + 1 < currentStep);
    });
  }

  var nextBtn = document.getElementById('nextBtn');
  var prevBtn = document.getElementById('prevBtn');
  var submitBtn = document.getElementById('submitBtn');

  if (nextBtn) {
    nextBtn.addEventListener('click', function () {
      if (validateStep(currentStep)) {
        if (currentStep < totalSteps) {
          currentStep++;
          showStep(currentStep);
        }
        if (prevBtn) prevBtn.style.display = currentStep > 1 ? 'inline-block' : 'none';
        if (nextBtn) nextBtn.style.display = currentStep < totalSteps ? 'inline-block' : 'none';
        if (submitBtn) submitBtn.style.display = currentStep === totalSteps ? 'inline-block' : 'none';
      }
    });
  }

  if (prevBtn) {
    prevBtn.addEventListener('click', function () {
      if (currentStep > 1) {
        currentStep--;
        showStep(currentStep);
      }
      if (prevBtn) prevBtn.style.display = currentStep > 1 ? 'inline-block' : 'none';
      if (nextBtn) nextBtn.style.display = currentStep < totalSteps ? 'inline-block' : 'none';
      if (submitBtn) submitBtn.style.display = currentStep === totalSteps ? 'inline-block' : 'none';
    });
  }

  if (formSteps.length > 0) showStep(currentStep);

  // =========================================================
  // 6. Form field validation
  // =========================================================
  function clearErrors(form) {
    if (!form) return;
    form.querySelectorAll('.field-error').forEach(function (el) { el.remove(); });
    form.querySelectorAll('.is-invalid').forEach(function (el) { el.classList.remove('is-invalid'); });
  }

  function showFieldError(field, message) {
    if (!field) return;
    field.classList.add('is-invalid');
    var existing = field.parentNode.querySelector('.field-error');
    if (existing) existing.remove();
    var err = document.createElement('div');
    err.className = 'field-error text-danger small mt-1';
    err.textContent = message;
    field.parentNode.appendChild(err);
  }

  function validateStep(stepNumber) {
    var form = multiStepForm;
    if (!form) return true;

    var isValid = true;
    var step = form.querySelector('.form-step:nth-child(' + stepNumber + ')');
    if (!step) {
      var steps = form.querySelectorAll('.form-step');
      step = steps[stepNumber - 1];
    }
    if (!step) return true;

    step.querySelectorAll('.field-error').forEach(function (el) { el.remove(); });
    step.querySelectorAll('.is-invalid').forEach(function (el) { el.classList.remove('is-invalid'); });

    var germanPhoneRegex = /^(\+49|0049|0)[1-9][0-9]{6,14}$/;
    var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    if (stepNumber === 1) {
      var vorname = form.querySelector('[name="vorname"]');
      var nachname = form.querySelector('[name="nachname"]');
      var telefon = form.querySelector('[name="telefon"]');
      var email = form.querySelector('[name="email"]');

      if (!vorname || !vorname.value.trim() || vorname.value.trim().length < 2) {
        showFieldError(vorname, 'Bitte geben Sie Ihren Vornamen ein (min. 2 Zeichen).');
        isValid = false;
      }
      if (!nachname || !nachname.value.trim() || nachname.value.trim().length < 2) {
        showFieldError(nachname, 'Bitte geben Sie Ihren Nachnamen ein (min. 2 Zeichen).');
        isValid = false;
      }
      var telefonVal = telefon ? telefon.value.trim().replace(/\s/g, '') : '';
      if (!telefonVal || !germanPhoneRegex.test(telefonVal)) {
        showFieldError(telefon, 'Bitte geben Sie eine gültige deutsche Telefonnummer ein.');
        isValid = false;
      }
      if (!email || !email.value.trim() || !emailRegex.test(email.value.trim())) {
        showFieldError(email, 'Bitte geben Sie eine gültige E-Mail-Adresse ein.');
        isValid = false;
      }
    }

    if (stepNumber === 2) {
      var verlustbetrag = form.querySelector('[name="verlustbetrag"]');
      var plattform = form.querySelector('[name="plattform"]');
      var zahlungsmethode = form.querySelector('[name="zahlungsmethode"]');
      var land = form.querySelector('[name="land"]');

      if (!verlustbetrag || !verlustbetrag.value) {
        showFieldError(verlustbetrag, 'Bitte wählen Sie eine Schadenshöhe aus.');
        isValid = false;
      }
      if (!plattform || plattform.value.trim().length < 3) {
        showFieldError(plattform, 'Bitte geben Sie die Plattform ein (min. 3 Zeichen).');
        isValid = false;
      }
      if (!zahlungsmethode || !zahlungsmethode.value) {
        showFieldError(zahlungsmethode, 'Bitte wählen Sie eine Zahlungsmethode aus.');
        isValid = false;
      }
      if (!land || !land.value) {
        showFieldError(land, 'Bitte wählen Sie Ihr Land aus.');
        isValid = false;
      }
    }

    if (stepNumber === 3) {
      var nachricht = form.querySelector('[name="nachricht"]');
      var datenschutz = form.querySelector('[name="datenschutz"]');

      if (!nachricht || nachricht.value.trim().length < 20) {
        showFieldError(nachricht, 'Bitte beschreiben Sie Ihren Fall (min. 20 Zeichen).');
        isValid = false;
      }
      if (!datenschutz || !datenschutz.checked) {
        showFieldError(datenschutz, 'Bitte akzeptieren Sie die Datenschutzerklärung.');
        isValid = false;
      }
    }

    return isValid;
  }

  // =========================================================
  // 7. Loading spinner
  // =========================================================
  function showSpinner() {
    var spinner = document.getElementById('loadingSpinner');
    if (spinner) spinner.style.display = 'flex';
    if (submitBtn) submitBtn.disabled = true;
  }

  function hideSpinner() {
    var spinner = document.getElementById('loadingSpinner');
    if (spinner) spinner.style.display = 'none';
    if (submitBtn) submitBtn.disabled = false;
  }

  // =========================================================
  // 8. Success / error message display
  // =========================================================
  function showMessage(type, message) {
    var msgDiv = document.getElementById('formMessage');
    if (!msgDiv) return;
    var alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
    msgDiv.innerHTML = '<div class="alert ' + alertClass + ' alert-dismissible fade show" role="alert">' +
      message +
      '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Schließen"></button>' +
      '</div>';
    msgDiv.style.display = 'block';
    setTimeout(function () {
      msgDiv.style.display = 'none';
      msgDiv.innerHTML = '';
    }, 8000);
  }

  // =========================================================
  // 9. AJAX form submission
  // =========================================================
  async function submitForm(formData) {
    var csrfToken = document.querySelector('meta[name="csrf-token"]') ?
      document.querySelector('meta[name="csrf-token"]').getAttribute('content') : null;
    if (csrfToken) formData.append('csrf_token', csrfToken);

    try {
      var response = await fetch('submit.php', {
        method: 'POST',
        body: formData
      });
      var data = await response.json();
      return data;
    } catch (e) {
      return { success: false, message: 'Verbindungsfehler. Bitte versuchen Sie es erneut.' };
    }
  }

  if (multiStepForm) {
    multiStepForm.addEventListener('submit', async function (e) {
      e.preventDefault();
      if (!validateStep(currentStep)) return;

      showSpinner();
      var formData = new FormData(multiStepForm);
      var result = await submitForm(formData);
      hideSpinner();

      if (result && result.success) {
        showMessage('success', result.message || 'Vielen Dank! Ihre Anfrage wurde erfolgreich übermittelt. Wir melden uns innerhalb von 24 Stunden.');
        multiStepForm.reset();
        currentStep = 1;
        showStep(currentStep);
        if (prevBtn) prevBtn.style.display = 'none';
        if (nextBtn) nextBtn.style.display = 'inline-block';
        if (submitBtn) submitBtn.style.display = 'none';
      } else {
        showMessage('error', (result && result.message) ? result.message : 'Es ist ein Fehler aufgetreten. Bitte versuchen Sie es erneut.');
      }
    });
  }

  // =========================================================
  // 10. Smooth scroll for anchor links
  // =========================================================
  document.querySelectorAll('a[href^="#"]').forEach(function (anchor) {
    anchor.addEventListener('click', function (e) {
      var href = anchor.getAttribute('href');
      if (href === '#' || href === '#!') return;
      var target = document.querySelector(href);
      if (!target) return;
      e.preventDefault();
      var offset = 80;
      var targetPosition = target.getBoundingClientRect().top + window.pageYOffset - offset;
      window.scrollTo({ top: targetPosition, behavior: 'smooth' });
    });
  });

  // =========================================================
  // 11. Sticky navbar
  // =========================================================
  var navbar = document.querySelector('.navbar');

  function handleNavbarScroll() {
    if (!navbar) return;
    if (window.scrollY > 50) {
      navbar.classList.add('scrolled');
    } else {
      navbar.classList.remove('scrolled');
    }
  }

  window.addEventListener('scroll', handleNavbarScroll, { passive: true });
  handleNavbarScroll();

  // =========================================================
  // 12. Scroll-to-top button
  // =========================================================
  var scrollTopBtn = document.getElementById('scrollTopBtn');

  function handleScrollTopBtn() {
    if (!scrollTopBtn) return;
    scrollTopBtn.style.display = window.scrollY > 300 ? 'flex' : 'none';
  }

  window.addEventListener('scroll', handleScrollTopBtn, { passive: true });
  handleScrollTopBtn();

  if (scrollTopBtn) {
    scrollTopBtn.addEventListener('click', function () {
      window.scrollTo({ top: 0, behavior: 'smooth' });
    });
  }

  // =========================================================
  // 13. WhatsApp button handler
  // =========================================================
  var whatsappBtn = document.getElementById('whatsappBtn');
  if (whatsappBtn) {
    whatsappBtn.addEventListener('click', function (e) {
      e.preventDefault();
      var number = (window.siteConfig && window.siteConfig.whatsappNumber) ? window.siteConfig.whatsappNumber : '';
      window.open(
        'https://wa.me/' + number + '?text=Guten%20Tag%2C%20ich%20ben%C3%B6tige%20Hilfe%20bei%20einem%20Betrugsfall.',
        '_blank'
      );
    });
  }

  // =========================================================
  // 14. Callback form AJAX submission
  // =========================================================
  var callbackForm = document.getElementById('callbackForm');
  if (callbackForm) {
    callbackForm.addEventListener('submit', async function (e) {
      e.preventDefault();
      var nameField = callbackForm.querySelector('[name="callback_name"]');
      var phoneField = callbackForm.querySelector('[name="callback_phone"]');
      var cbValid = true;

      callbackForm.querySelectorAll('.field-error').forEach(function (el) { el.remove(); });
      callbackForm.querySelectorAll('.is-invalid').forEach(function (el) { el.classList.remove('is-invalid'); });

      if (!nameField || nameField.value.trim().length < 2) {
        showFieldError(nameField, 'Bitte geben Sie Ihren Namen ein.');
        cbValid = false;
      }
      var germanPhoneRegexCb = /^(\+49|0049|0)[1-9][0-9]{6,14}$/;
      var phoneVal = phoneField ? phoneField.value.trim().replace(/\s/g, '') : '';
      if (!phoneVal || !germanPhoneRegexCb.test(phoneVal)) {
        showFieldError(phoneField, 'Bitte geben Sie eine gültige Telefonnummer ein.');
        cbValid = false;
      }

      if (!cbValid) return;

      var cbData = new FormData(callbackForm);
      cbData.append('action', 'callback');
      var csrfToken = document.querySelector('meta[name="csrf-token"]') ?
        document.querySelector('meta[name="csrf-token"]').getAttribute('content') : null;
      if (csrfToken) cbData.append('csrf_token', csrfToken);

      var cbSubmitBtn = callbackForm.querySelector('[type="submit"]');
      if (cbSubmitBtn) cbSubmitBtn.disabled = true;

      try {
        var response = await fetch('submit.php', { method: 'POST', body: cbData });
        var result = await response.json();
        var cbMsg = document.getElementById('callbackMessage');
        if (cbMsg) {
          cbMsg.textContent = result.success
            ? (result.message || 'Vielen Dank! Wir rufen Sie zurück.')
            : (result.message || 'Fehler. Bitte versuchen Sie es erneut.');
          cbMsg.className = result.success ? 'text-success small mt-1' : 'text-danger small mt-1';
          cbMsg.style.display = 'block';
        }
        if (result.success) callbackForm.reset();
      } catch (err) {
        var cbMsgErr = document.getElementById('callbackMessage');
        if (cbMsgErr) {
          cbMsgErr.textContent = 'Verbindungsfehler. Bitte versuchen Sie es erneut.';
          cbMsgErr.className = 'text-danger small mt-1';
          cbMsgErr.style.display = 'block';
        }
      } finally {
        if (cbSubmitBtn) cbSubmitBtn.disabled = false;
      }
    });
  }

  // =========================================================
  // 15. Bootstrap tooltip and popover initialization
  // =========================================================
  if (typeof bootstrap !== 'undefined') {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (el) { return new bootstrap.Tooltip(el); });

    var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
    popoverTriggerList.map(function (el) { return new bootstrap.Popover(el); });
  }

  // =========================================================
  // 16. Lazy loading for images
  // =========================================================
  var lazyObserver = new IntersectionObserver(function (entries, observer) {
    entries.forEach(function (entry) {
      if (entry.isIntersecting) {
        var img = entry.target;
        var src = img.getAttribute('data-src');
        if (src) {
          img.src = src;
          img.removeAttribute('data-src');
          img.addEventListener('load', function () {
            img.classList.add('loaded');
          }, { once: true });
        }
        observer.unobserve(img);
      }
    });
  }, { threshold: 0.1, rootMargin: '0px 0px 200px 0px' });

  document.querySelectorAll('img[data-src]').forEach(function (img) {
    lazyObserver.observe(img);
  });

  // =========================================================
  // 17. Navbar mobile menu auto-close
  // =========================================================
  var navbarCollapse = document.querySelector('.navbar-collapse');
  if (navbarCollapse) {
    document.querySelectorAll('.navbar-nav .nav-link').forEach(function (link) {
      link.addEventListener('click', function () {
        if (navbarCollapse.classList.contains('show')) {
          if (typeof bootstrap !== 'undefined') {
            var bsCollapse = bootstrap.Collapse.getInstance(navbarCollapse);
            if (bsCollapse) bsCollapse.hide();
          } else {
            navbarCollapse.classList.remove('show');
          }
        }
      });
    });
  }

  // =========================================================
  // 18. Callback bar show/hide based on scroll
  // =========================================================
  var callbackBar = document.getElementById('callbackBar');
  var heroSection = document.querySelector('#hero, .hero-section, header');

  function handleCallbackBar() {
    if (!callbackBar) return;
    var heroHeight = heroSection ? heroSection.offsetHeight : window.innerHeight;
    if (window.scrollY > heroHeight) {
      callbackBar.classList.add('visible');
      callbackBar.style.display = 'block';
    } else {
      callbackBar.classList.remove('visible');
      callbackBar.style.display = 'none';
    }
  }

  window.addEventListener('scroll', handleCallbackBar, { passive: true });
  handleCallbackBar();

  // =========================================================
  // 19. Form character counter for textarea
  // =========================================================
  var nachrichtTextarea = document.querySelector('[name="nachricht"]');
  var charCounter = document.getElementById('charCounter');
  var maxChars = 1000;

  if (nachrichtTextarea) {
    if (!charCounter) {
      charCounter = document.createElement('small');
      charCounter.id = 'charCounter';
      charCounter.className = 'text-muted d-block text-end mt-1';
      nachrichtTextarea.parentNode.appendChild(charCounter);
    }

    function updateCharCounter() {
      var remaining = maxChars - nachrichtTextarea.value.length;
      charCounter.textContent = remaining + ' Zeichen verbleibend';
      charCounter.style.color = remaining < 50 ? '#dc3545' : '';
      if (nachrichtTextarea.value.length > maxChars) {
        nachrichtTextarea.value = nachrichtTextarea.value.substring(0, maxChars);
      }
    }

    nachrichtTextarea.setAttribute('maxlength', maxChars);
    nachrichtTextarea.addEventListener('input', updateCharCounter);
    updateCharCounter();
  }

});

/* ============================================================
   SUPPLEMENTAL – Index.php specific handlers
   ============================================================ */
(function () {
  'use strict';

  document.addEventListener('DOMContentLoaded', function () {

    // -------------------------------------------------------
    // Counter elements with .counter class + data-target
    // -------------------------------------------------------
    var counterEls = document.querySelectorAll('.counter[data-target]');
    if (counterEls.length) {
      var cObs = new IntersectionObserver(function (entries, obs) {
        entries.forEach(function (entry) {
          if (!entry.isIntersecting) return;
          var el = entry.target;
          var target = parseInt(el.getAttribute('data-target'), 10);
          var duration = 2000;
          var start = 0;
          var startTime = null;
          function ease(t) { return 1 - Math.pow(1 - t, 4); }
          function step(ts) {
            if (!startTime) startTime = ts;
            var prog = Math.min((ts - startTime) / duration, 1);
            el.textContent = Math.floor(ease(prog) * target).toLocaleString('de-DE');
            if (prog < 1) { requestAnimationFrame(step); }
            else { el.textContent = target.toLocaleString('de-DE'); }
          }
          requestAnimationFrame(step);
          obs.unobserve(el);
        });
      }, { threshold: 0.3 });
      counterEls.forEach(function (el) { cObs.observe(el); });
    }

    // -------------------------------------------------------
    // Multi-step form – contactForm with .next-step / .prev-step
    // -------------------------------------------------------
    var form = document.getElementById('contactForm');
    if (!form) return;

    var steps = form.querySelectorAll('.form-step');
    var progressSteps = form.querySelectorAll('.progress-step');
    var currentStep = 0;

    function showFormStep(idx) {
      steps.forEach(function (s, i) {
        s.classList.toggle('active', i === idx);
      });
      progressSteps.forEach(function (p, i) {
        p.classList.remove('active', 'completed');
        if (i < idx)  p.classList.add('completed');
        if (i === idx) p.classList.add('active');
      });
    }

    function validateCurrentStep() {
      var active = steps[currentStep];
      if (!active) return true;
      var inputs = active.querySelectorAll('[required]');
      var ok = true;
      inputs.forEach(function (inp) {
        inp.classList.remove('is-invalid');
        var val = inp.value.trim();
        if (inp.type === 'checkbox') {
          if (!inp.checked) { inp.classList.add('is-invalid'); ok = false; }
        } else if (!val) {
          inp.classList.add('is-invalid'); ok = false;
        } else if (inp.type === 'email' && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(val)) {
          inp.classList.add('is-invalid'); ok = false;
        }
      });
      return ok;
    }

    // Next buttons
    form.querySelectorAll('.next-step').forEach(function (btn) {
      btn.addEventListener('click', function () {
        if (!validateCurrentStep()) return;
        if (currentStep < steps.length - 1) {
          currentStep++;
          showFormStep(currentStep);
        }
      });
    });

    // Prev buttons
    form.querySelectorAll('.prev-step').forEach(function (btn) {
      btn.addEventListener('click', function () {
        if (currentStep > 0) {
          currentStep--;
          showFormStep(currentStep);
        }
      });
    });

    // Submit
    form.addEventListener('submit', async function (e) {
      e.preventDefault();
      if (!validateCurrentStep()) return;

      var btn = document.getElementById('submitBtn');
      var spinner = document.getElementById('submitSpinner');
      var successEl = document.getElementById('formSuccess');
      var errorEl = document.getElementById('formError');
      var errorText = document.getElementById('formErrorText');

      if (btn) btn.disabled = true;
      if (spinner) spinner.classList.remove('d-none');
      if (successEl) successEl.classList.add('d-none');
      if (errorEl)   errorEl.classList.add('d-none');

      var fd = new FormData(form);
      // CSRF from meta tag
      var csrfMeta = document.querySelector('meta[name="csrf-token"]');
      if (csrfMeta) fd.set('csrf_token', csrfMeta.getAttribute('content'));
      fd.append('action', 'contact');

      try {
        var resp = await fetch('submit.php', { method: 'POST', body: fd });
        var data = await resp.json();
        if (data && data.success) {
          form.querySelectorAll('.form-step').forEach(function (s) { s.classList.remove('active'); });
          if (successEl) successEl.classList.remove('d-none');
          form.reset();
          currentStep = 0;
          showFormStep(0);
          // scroll into view
          successEl && successEl.scrollIntoView({ behavior: 'smooth', block: 'center' });
        } else {
          if (errorText) errorText.textContent = (data && data.message) ? data.message : 'Fehler beim Senden. Bitte erneut versuchen.';
          if (errorEl) errorEl.classList.remove('d-none');
        }
      } catch (err) {
        if (errorText) errorText.textContent = 'Verbindungsfehler. Bitte versuchen Sie es erneut.';
        if (errorEl) errorEl.classList.remove('d-none');
      } finally {
        if (btn) btn.disabled = false;
        if (spinner) spinner.classList.add('d-none');
      }
    });

    // Initialise first step
    showFormStep(0);

    // -------------------------------------------------------
    // Callback form – cb_name / cb_telefon field names
    // -------------------------------------------------------
    var cbForm = document.getElementById('callbackForm');
    if (cbForm) {
      cbForm.addEventListener('submit', async function (e) {
        e.preventDefault();
        var nameF  = cbForm.querySelector('[name="cb_name"]');
        var phoneF = cbForm.querySelector('[name="cb_telefon"]');
        var ok = true;
        [nameF, phoneF].forEach(function (f) {
          if (f) { f.classList.remove('is-invalid'); if (!f.value.trim()) { f.classList.add('is-invalid'); ok = false; } }
        });
        if (!ok) return;

        var fd2 = new FormData(cbForm);
        var csrfMeta2 = document.querySelector('meta[name="csrf-token"]');
        if (csrfMeta2) fd2.set('csrf_token', csrfMeta2.getAttribute('content'));
        fd2.append('action', 'callback');

        var cbBtn = cbForm.querySelector('button[type="submit"]');
        if (cbBtn) cbBtn.disabled = true;
        try {
          var r = await fetch('submit.php', { method: 'POST', body: fd2 });
          var d = await r.json();
          if (d && d.success) { cbForm.reset(); }
        } catch (ex) {} finally {
          if (cbBtn) cbBtn.disabled = false;
        }
      });
    }

    // -------------------------------------------------------
    // Scroll-to-top visible class
    // -------------------------------------------------------
    var scrollTopBtn = document.getElementById('scrollTopBtn');
    if (scrollTopBtn) {
      function updateScrollTop() {
        scrollTopBtn.classList.toggle('visible', window.scrollY > 400);
      }
      window.addEventListener('scroll', updateScrollTop, { passive: true });
      updateScrollTop();
      scrollTopBtn.addEventListener('click', function () {
        window.scrollTo({ top: 0, behavior: 'smooth' });
      });
    }

    // -------------------------------------------------------
    // Activity widget – activityText without activityTime/Dot
    // -------------------------------------------------------
    var actEl = document.getElementById('activityText');
    if (actEl) {
      var msgs = [
        'Neue Analyse gestartet – gerade eben',
        'Anfrage aus München eingegangen – vor 3 Min.',
        'Blockchain-Prüfung abgeschlossen – vor 8 Min.',
        'Termin bestätigt – vor 12 Min.',
        'Wallet-Analyse gestartet – vor 15 Min.',
        'Anfrage aus Berlin eingegangen – vor 18 Min.',
      ];
      var ai = 0;
      var widget = document.getElementById('activityWidget');
      function rotateActivity() {
        if (widget) { widget.style.opacity = '0'; }
        setTimeout(function () {
          actEl.textContent = msgs[ai % msgs.length];
          ai++;
          if (widget) { widget.style.opacity = '1'; }
        }, 400);
      }
      if (widget) { widget.style.transition = 'opacity 0.4s ease'; }
      rotateActivity();
      setInterval(rotateActivity, 4000);
    }

  });
}());
