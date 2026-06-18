/* ============================================================
   RASEL PORTFOLIO — MAIN.JS
   Vanilla ES6+ interactivity
   ============================================================ */
(function () {
    'use strict';

    /* ---------- Preloader ---------- */
    window.addEventListener('load', function () {
        const preloader = document.getElementById('preloader');
        if (preloader) {
            setTimeout(function () {
                preloader.classList.add('hide');
            }, 400);
        }
    });

    document.addEventListener('DOMContentLoaded', function () {

        const body = document.body;

        /* ---------- Navbar scroll effect ---------- */
        const navbar = document.getElementById('navbar');
        function handleNavScroll() {
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        }
        window.addEventListener('scroll', handleNavScroll, { passive: true });
        handleNavScroll();

        /* ---------- Mobile hamburger toggle ---------- */
        const hamburger = document.getElementById('hamburger');
        const navMenu = document.getElementById('navMenu');

        function closeMenu() {
            hamburger.classList.remove('open');
            navMenu.classList.remove('open');
            body.classList.remove('nav-open');
            hamburger.setAttribute('aria-expanded', 'false');
        }

        if (hamburger && navMenu) {
            hamburger.addEventListener('click', function () {
                const isOpen = navMenu.classList.toggle('open');
                hamburger.classList.toggle('open', isOpen);
                body.classList.toggle('nav-open', isOpen);
                hamburger.setAttribute('aria-expanded', String(isOpen));
            });

            navMenu.querySelectorAll('a').forEach(function (link) {
                link.addEventListener('click', closeMenu);
            });

            document.addEventListener('click', function (e) {
                if (
                    navMenu.classList.contains('open') &&
                    !navMenu.contains(e.target) &&
                    !hamburger.contains(e.target)
                ) {
                    closeMenu();
                }
            });
        }

        /* ---------- Smooth scroll with navbar offset ---------- */
        const navHeight = 64;
        document.querySelectorAll('a[href^="#"]').forEach(function (anchor) {
            anchor.addEventListener('click', function (e) {
                const targetId = this.getAttribute('href');
                if (!targetId || targetId === '#') return;
                const target = document.querySelector(targetId);
                if (!target) return;

                e.preventDefault();
                const offsetTop = target.getBoundingClientRect().top + window.scrollY - navHeight;

                window.scrollTo({
                    top: offsetTop,
                    behavior: 'smooth',
                });
            });
        });

        /* ---------- Active nav link on scroll (IntersectionObserver) ---------- */
        const sections = document.querySelectorAll('section[id]');
        const navLinks = document.querySelectorAll('.nav-link');

        const sectionObserver = new IntersectionObserver(
            function (entries) {
                entries.forEach(function (entry) {
                    if (entry.isIntersecting) {
                        const id = entry.target.getAttribute('id');
                        navLinks.forEach(function (link) {
                            link.classList.toggle(
                                'active',
                                link.getAttribute('href') === '#' + id
                            );
                        });
                    }
                });
            },
            { rootMargin: '-45% 0px -50% 0px', threshold: 0 }
        );

        sections.forEach(function (section) {
            sectionObserver.observe(section);
        });

        /* ---------- Scroll reveal animations ---------- */
        const revealEls = document.querySelectorAll('.reveal');
        const revealObserver = new IntersectionObserver(
            function (entries, observer) {
                entries.forEach(function (entry, index) {
                    if (entry.isIntersecting) {
                        const delay = entry.target.dataset.delay || (index * 60);
                        setTimeout(function () {
                            entry.target.classList.add('active');
                        }, Math.min(delay, 300));
                        observer.unobserve(entry.target);
                    }
                });
            },
            { threshold: 0.12, rootMargin: '0px 0px -60px 0px' }
        );

        revealEls.forEach(function (el) {
            revealObserver.observe(el);
        });

        /* ---------- Skill bar animation ---------- */
        const skillBars = document.querySelectorAll('.skill-bar-fill');
        const skillSection = document.getElementById('skills');

        function animateSkillBars() {
            skillBars.forEach(function (bar) {
                const width = bar.dataset.width || '0%';
                bar.style.width = width;
            });
        }

        if (skillSection) {
            const skillObserver = new IntersectionObserver(
                function (entries, observer) {
                    entries.forEach(function (entry) {
                        if (entry.isIntersecting) {
                            animateSkillBars();
                            observer.unobserve(entry.target);
                        }
                    });
                },
                { threshold: 0.2 }
            );
            skillObserver.observe(skillSection);
        }

        /* ---------- Animated number counters ---------- */
        const counters = document.querySelectorAll('.stat-number');

        function animateCounter(el) {
            const target = parseInt(el.dataset.target, 10) || 0;
            const duration = 1800;
            const startTime = performance.now();

            function update(now) {
                const elapsed = now - startTime;
                const progress = Math.min(elapsed / duration, 1);
                const eased = 1 - Math.pow(1 - progress, 3);
                el.textContent = Math.floor(eased * target).toLocaleString();

                if (progress < 1) {
                    requestAnimationFrame(update);
                } else {
                    el.textContent = target.toLocaleString() + '+';
                }
            }

            requestAnimationFrame(update);
        }

        const counterObserver = new IntersectionObserver(
            function (entries, observer) {
                entries.forEach(function (entry) {
                    if (entry.isIntersecting) {
                        animateCounter(entry.target);
                        observer.unobserve(entry.target);
                    }
                });
            },
            { threshold: 0.5 }
        );

        counters.forEach(function (counter) {
            counterObserver.observe(counter);
        });

        /* ---------- Typing / typewriter effect ---------- */
        const typedEl = document.getElementById('typedText');
        if (typedEl) {
            const defaultTitles = ['Developer'];
            let titles = defaultTitles;

            const dataAttr = typedEl.dataset.titles || (document.body.dataset.titles || '');
            if (dataAttr) {
                try {
                    const parsed = JSON.parse(dataAttr);
                    if (Array.isArray(parsed) && parsed.length) titles = parsed;
                } catch (err) {
                    titles = dataAttr.split(',').map(function (t) { return t.trim(); });
                }
            }

            let titleIndex = 0;
            let charIndex = 0;
            let isDeleting = false;

            function typeLoop() {
                const current = titles[titleIndex] || '';

                if (isDeleting) {
                    charIndex--;
                } else {
                    charIndex++;
                }

                typedEl.textContent = current.substring(0, charIndex);

                let delay = isDeleting ? 50 : 110;

                if (!isDeleting && charIndex === current.length) {
                    delay = 1800;
                    isDeleting = true;
                } else if (isDeleting && charIndex === 0) {
                    isDeleting = false;
                    titleIndex = (titleIndex + 1) % titles.length;
                    delay = 400;
                }

                setTimeout(typeLoop, delay);
            }

            typeLoop();
        }

        /* ---------- Back to top button ---------- */
        const backToTop = document.getElementById('backToTop');
        if (backToTop) {
            window.addEventListener('scroll', function () {
                if (window.scrollY > 500) {
                    backToTop.classList.add('show');
                } else {
                    backToTop.classList.remove('show');
                }
            }, { passive: true });

            backToTop.addEventListener('click', function () {
                window.scrollTo({ top: 0, behavior: 'smooth' });
            });
        }

        /* ---------- Contact form AJAX submit ---------- */
        const contactForm = document.getElementById('contactForm');
        const submitBtn = document.getElementById('submitBtn');
        const ajaxSuccess = document.getElementById('ajaxSuccess');
        const ajaxError = document.getElementById('ajaxError');
        const ajaxErrorText = document.getElementById('ajaxErrorText');

        function showAlert(el) {
            [ajaxSuccess, ajaxError].forEach(function (a) {
                if (a && a !== el) a.style.display = 'none';
            });
            if (el) {
                el.style.display = 'flex';
                el.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            }
        }

        if (contactForm) {
            contactForm.addEventListener('submit', function (e) {
                e.preventDefault();

                const csrfToken = document.querySelector('meta[name="csrf-token"]');
                if (!csrfToken) return;
                const token = csrfToken.getAttribute('content');

                if (ajaxSuccess) ajaxSuccess.style.display = 'none';
                if (ajaxError) ajaxError.style.display = 'none';

                contactForm.querySelectorAll('.form-error').forEach(function (el) {
                    el.remove();
                });
                contactForm.querySelectorAll('.input-error').forEach(function (el) {
                    el.classList.remove('input-error');
                });

                const formData = new FormData(contactForm);

                submitBtn.classList.add('loading');

                fetch(contactForm.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': token,
                        'X-Requested-With': 'XMLHttpRequest',
                        Accept: 'application/json',
                    },
                    body: formData,
                })
                    .then(function (response) {
                        return response.json().then(function (data) {
                            return { ok: response.ok, status: response.status, data: data };
                        });
                    })
                    .then(function (res) {
                        if (res.ok && res.data.success) {
                            contactForm.reset();
                            if (ajaxSuccess) {
                                const msgEl = ajaxSuccess.querySelector('span');
                                if (msgEl && res.data.message) msgEl.textContent = res.data.message;
                                showAlert(ajaxSuccess);
                            }
                        } else {
                            handleErrors(res.data, res.status);
                        }
                    })
                    .catch(function () {
                        handleErrors({}, 0);
                    })
                    .finally(function () {
                        submitBtn.classList.remove('loading');
                    });

                function handleErrors(data, status) {
                    if (data && data.errors && Object.keys(data.errors).length) {
                        Object.keys(data.errors).forEach(function (field) {
                            const input = contactForm.querySelector('[name="' + field + '"]');
                            if (input) {
                                input.classList.add('input-error');
                                const error = document.createElement('span');
                                error.className = 'form-error';
                                error.textContent = data.errors[field][0];
                                input.closest('.form-group').appendChild(error);
                            }
                        });

                        const firstError = contactForm.querySelector('.input-error');
                        if (firstError) firstError.focus();
                        if (ajaxErrorText) ajaxErrorText.textContent = 'Please correct the highlighted fields.';
                        showAlert(ajaxError);
                    } else if (status === 419) {
                        if (ajaxErrorText) ajaxErrorText.textContent = 'Your session expired. Please refresh the page and try again.';
                        showAlert(ajaxError);
                    } else if (data && data.message) {
                        if (ajaxErrorText) ajaxErrorText.textContent = data.message;
                        showAlert(ajaxError);
                    } else {
                        if (ajaxErrorText) ajaxErrorText.textContent = 'Something went wrong. Please try again or email me directly.';
                        showAlert(ajaxError);
                    }
                }
            });
        }

        /* ---------- Auto-dismiss flash alerts after 6s ---------- */
        const flashes = document.querySelectorAll('#formSuccess, #formError');
        flashes.forEach(function (el) {
            setTimeout(function () {
                el.style.transition = 'opacity 0.5s ease';
                el.style.opacity = '0';
                setTimeout(function () {
                    el.style.display = 'none';
                }, 500);
            }, 6000);
        });
    });
})();

/* ============================================================
   THEME TOGGLE
   ============================================================ */
(function () {
    var toggle = document.getElementById('themeToggle');
    if (!toggle) return;

    toggle.addEventListener('click', function () {
        var html = document.documentElement;
        var current = html.getAttribute('data-theme') || 'light';
        var next = current === 'dark' ? 'light' : 'dark';

        html.setAttribute('data-theme', next);
        localStorage.setItem('theme', next);
    });

    // If the OS preference changes and the user hasn't manually chosen,
    // follow the system setting.
    var mediaQuery = window.matchMedia('(prefers-color-scheme: dark)');
    mediaQuery.addEventListener('change', function (e) {
        if (!localStorage.getItem('theme')) {
            document.documentElement.setAttribute('data-theme', e.matches ? 'dark' : 'light');
        }
    });
})();
