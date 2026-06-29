(function () {
    'use strict';

    function triggerHeroAnimate(container) {
        if (!container) return;
        container.classList.remove('is-visible');
        void container.offsetWidth;
        container.classList.add('is-visible');
    }

    function initCountUp(container) {
        if (!container) return;
        container.querySelectorAll('.cms-count-up').forEach(function (el) {
            var target = parseInt(el.getAttribute('data-target'), 10) || 0;
            if (target <= 0) {
                el.textContent = '0';
                return;
            }
            var current = 0;
            var step = Math.max(1, Math.ceil(target / 30));
            var timer = setInterval(function () {
                current += step;
                if (current >= target) {
                    current = target;
                    clearInterval(timer);
                }
                el.textContent = current + (target >= 10 ? '+' : '');
            }, 40);
        });
    }

    function initWordRotate(root) {
        var scope = root || document;
        scope.querySelectorAll('.cms-hero-word-rotate').forEach(function (wrap) {
            var raw = wrap.getAttribute('data-words');
            var words = [];
            try { words = JSON.parse(raw); } catch (e) { words = []; }
            if (!words.length) return;

            var inner = wrap.querySelector('.cms-hero-word-inner');
            if (!inner) return;

            var idx = 0;
            setInterval(function () {
                inner.classList.add('is-out');
                setTimeout(function () {
                    idx = (idx + 1) % words.length;
                    inner.textContent = words[idx];
                    inner.classList.remove('is-out');
                }, 350);
            }, 3200);
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
        if (typeof AOS !== 'undefined') {
            AOS.init({ duration: 700, once: true, offset: 60 });
        }

        var navbar = document.querySelector('.cms-navbar');

        function updateNavbarHeight() {
            if (!navbar) return;
            var height = Math.ceil(navbar.getBoundingClientRect().height);
            document.documentElement.style.setProperty('--cms-navbar-height', height + 'px');
        }

        if (navbar) {
            updateNavbarHeight();
            window.addEventListener('resize', updateNavbarHeight);
            window.addEventListener('load', updateNavbarHeight);

            window.addEventListener('scroll', function () {
                var wasScrolled = navbar.classList.contains('scrolled');
                navbar.classList.toggle('scrolled', window.scrollY > 20);
                if (window.scrollY < 5 || wasScrolled !== navbar.classList.contains('scrolled')) {
                    updateNavbarHeight();
                }
            }, { passive: true });

            var navCollapse = document.getElementById('cmsNav');
            if (navCollapse && typeof bootstrap !== 'undefined') {
                navCollapse.addEventListener('shown.bs.collapse', updateNavbarHeight);
                navCollapse.addEventListener('hidden.bs.collapse', updateNavbarHeight);
                navCollapse.querySelectorAll('.cms-nav-link').forEach(function (link) {
                    link.addEventListener('click', function () {
                        if (window.innerWidth < 992 && navCollapse.classList.contains('show')) {
                            bootstrap.Collapse.getOrCreateInstance(navCollapse).hide();
                        }
                    });
                });
            }
        }

        var heroSwiperEl = document.querySelector('.cms-hero-swiper');
        var heroSwiper = null;

        if (heroSwiperEl && typeof Swiper !== 'undefined') {
            heroSwiper = new Swiper('.cms-hero-swiper', {
                loop: true,
                autoplay: { delay: 6000, disableOnInteraction: false },
                effect: 'fade',
                fadeEffect: { crossFade: true },
                speed: 900,
                pagination: { el: '.cms-hero-pagination', clickable: true },
                navigation: {
                    nextEl: '.cms-hero-next',
                    prevEl: '.cms-hero-prev'
                },
                on: {
                    init: function () {
                        var active = heroSwiperEl.querySelector('.swiper-slide-active .cms-hero-animate');
                        triggerHeroAnimate(active);
                        initCountUp(active);
                        initWordRotate(document);
                    },
                    slideChangeTransitionStart: function () {
                        heroSwiperEl.querySelectorAll('.cms-hero-animate').forEach(function (el) {
                            el.classList.remove('is-visible');
                        });
                    },
                    slideChangeTransitionEnd: function () {
                        var active = heroSwiperEl.querySelector('.swiper-slide-active .cms-hero-animate');
                        triggerHeroAnimate(active);
                    }
                }
            });
        } else {
            var fallbackAnimate = document.querySelector('.cms-hero-fallback .cms-hero-animate');
            if (fallbackAnimate) {
                triggerHeroAnimate(fallbackAnimate);
                initCountUp(fallbackAnimate);
            }
            initWordRotate(document);
        }

        if (typeof GLightbox !== 'undefined') {
            GLightbox({ selector: '.glightbox' });
        }

        var backTop = document.getElementById('cmsBackTop');
        if (backTop) {
            window.addEventListener('scroll', function () {
                backTop.classList.toggle('show', window.scrollY > 400);
            });
            backTop.addEventListener('click', function (e) {
                e.preventDefault();
                window.scrollTo({ top: 0, behavior: 'smooth' });
            });
        }

        document.querySelectorAll('.cms-scroll-hint').forEach(function (hint) {
            hint.addEventListener('click', function (e) {
                var href = hint.getAttribute('href');
                if (!href || href.charAt(0) !== '#') return;
                var target = document.querySelector(href);
                if (!target) return;
                e.preventDefault();
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            });
        });

        var scrollHint = document.querySelector('.cms-scroll-hint');
        if (scrollHint) {
            window.addEventListener('scroll', function () {
                scrollHint.style.opacity = window.scrollY > 80 ? '0' : '1';
                scrollHint.style.pointerEvents = window.scrollY > 80 ? 'none' : 'auto';
            });
        }

        document.querySelectorAll('[data-cms-video]').forEach(function (btn) {
            btn.addEventListener('click', function (e) {
                e.preventDefault();
                var url = btn.getAttribute('data-cms-video');
                if (!url) return;
                var modal = document.getElementById('cmsVideoModal');
                if (!modal) return;
                var iframe = modal.querySelector('iframe');
                iframe.src = url;
                var bsModal = new bootstrap.Modal(modal);
                bsModal.show();
                modal.addEventListener('hidden.bs.modal', function () {
                    iframe.src = '';
                }, { once: true });
            });
        });
    });
})();
