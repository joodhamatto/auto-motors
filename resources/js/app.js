import './bootstrap';
import * as bootstrap from 'bootstrap';

window.bootstrap = bootstrap;

const navbar = document.querySelector('#navbar');
const backToTop = document.querySelector('#backTop');

const updateScrollState = () => {
    navbar?.classList.toggle('scrolled', scrollY > 40);
    backToTop?.classList.toggle('show', scrollY > 500);
};

addEventListener('scroll', updateScrollState, { passive: true });
updateScrollState();

const reducedMotion = matchMedia('(prefers-reduced-motion: reduce)').matches;
const hero = document.querySelector('#home.hero');
const heroCar = hero?.querySelector('.hero-car');

if (heroCar) {
    const motionPreference = matchMedia('(prefers-reduced-motion: reduce)');
    const compactScreen = matchMedia('(max-width: 1199px)');
    let frame = null;
    let heroTop = 0;
    let heroHeight = 1;

    const renderCar = () => {
        frame = null;
        if (motionPreference.matches) {
            heroCar.style.removeProperty('transform');
            return;
        }

        const progress = Math.min(1, Math.max(0, (scrollY - heroTop) / heroHeight));
        const compact = compactScreen.matches;
        const x = compact ? 2 - progress * 3 : 7 - progress * 10;
        const y = compact ? -progress * 0.5 : 1 - progress * 2;
        const z = compact ? 0 : -30 + progress * 50;
        const scale = compact ? 1.04 - progress * 0.02 : 1.16 - progress * 0.1;
        const rotateY = compact ? -2 + progress * 3 : -8 + progress * 12;
        const rotateX = compact ? 0.3 - progress * 0.5 : 1 - progress * 2;

        heroCar.style.transform = `translate3d(${x}%, ${y}%, ${z}px) scale(${scale}) rotateY(${rotateY}deg) rotateX(${rotateX}deg)`;
    };

    const scheduleCar = () => {
        if (frame === null && !motionPreference.matches) {
            frame = requestAnimationFrame(renderCar);
        }
    };

    const measureHero = () => {
        const bounds = hero.getBoundingClientRect();
        heroTop = bounds.top + scrollY;
        heroHeight = Math.max(1, bounds.height);
        scheduleCar();
    };

    motionPreference.addEventListener('change', () => {
        if (frame !== null) {
            cancelAnimationFrame(frame);
            frame = null;
        }
        renderCar();
    });
    addEventListener('scroll', scheduleCar, { passive: true });
    addEventListener('resize', measureHero, { passive: true });
    addEventListener('pageshow', measureHero);
    new ResizeObserver(measureHero).observe(hero);
    measureHero();
}

const revealElements = document.querySelectorAll('.reveal');

if (reducedMotion) {
    revealElements.forEach(element => element.classList.add('visible'));
} else {
    const observer = new IntersectionObserver(entries => {
        entries.forEach(entry => {
            if (! entry.isIntersecting) {
                return;
            }

            entry.target.classList.add('visible');
            observer.unobserve(entry.target);
        });
    }, { threshold: 0.12 });

    revealElements.forEach(element => observer.observe(element));
}

const catalogue = document.querySelector('#catalogue');

if (catalogue) {
    let pendingRequest;
    let catalogueUrl = location.pathname + location.search;

    const updateCatalogue = async (url, pushHistory = true) => {
        pendingRequest?.abort();
        const request = new AbortController();
        pendingRequest = request;
        catalogue.setAttribute('aria-busy', 'true');

        try {
            const response = await fetch(url, { signal: request.signal });
            if (!response.ok) {
                throw new Error('Catalogue request failed');
            }
            const page = new DOMParser().parseFromString(await response.text(), 'text/html');
            const nextCatalogue = page.querySelector('#catalogue');
            if (!nextCatalogue) {
                throw new Error('Catalogue missing');
            }
            if (request.signal.aborted) {
                return;
            }

            // Keep the document from shrinking under a reader already in the results.
            catalogue.style.minHeight = `${catalogue.getBoundingClientRect().height}px`;
            catalogue.replaceChildren(...nextCatalogue.childNodes);
            catalogue.querySelectorAll('.reveal').forEach(element => element.classList.add('visible'));
            catalogueUrl = url.pathname + url.search;
            if (pushHistory) {
                history.pushState({ ...history.state, catalogue: true }, '', url);
            }
        } catch (error) {
            if (error.name !== 'AbortError') {
                // Keep the current results and position; allow retry without navigation.
                catalogue.querySelector('[data-catalogue-error]').hidden = false;
            }
        } finally {
            if (pendingRequest === request) {
                catalogue.removeAttribute('aria-busy');
            }
        }
    };

    catalogue.addEventListener('click', event => {
        const link = event.target.closest('.filter-btn, .pagination a');
        if (!link || event.button !== 0 || event.ctrlKey || event.metaKey || event.shiftKey || event.altKey) {
            return;
        }
        event.preventDefault();
        const url = new URL(link.href);
        url.hash = '';
        updateCatalogue(url);
    });

    addEventListener('popstate', () => {
        pendingRequest?.abort();
        if (location.pathname + location.search !== catalogueUrl) {
            updateCatalogue(new URL(location.href), false);
        }
    });
}

const sections = document.querySelectorAll('main section[id]');

if (sections.length) {
    const sectionObserver = new IntersectionObserver(entries => {
        entries.forEach(entry => {
            if (! entry.isIntersecting) {
                return;
            }

            document.querySelectorAll('.navbar .nav-link').forEach(link => {
                link.classList.toggle('active', link.getAttribute('href') === `#${entry.target.id}`);
            });
        });
    }, { rootMargin: '-35% 0px -55%' });

    sections.forEach(section => sectionObserver.observe(section));
}

document.querySelectorAll('[data-confirm]').forEach(form => {
    form.addEventListener('submit', event => {
        if (! confirm(form.dataset.confirm)) {
            event.preventDefault();
        }
    });
});

document.querySelectorAll('.navbar .nav-link').forEach(link => {
    link.addEventListener('click', () => {
        const menu = document.querySelector('#mainNav');

        if (menu?.classList.contains('show')) {
            bootstrap.Collapse.getOrCreateInstance(menu).hide();
        }
    });
});

const adminSidebar = document.querySelector('#adminSidebar');

adminSidebar?.querySelectorAll('a').forEach(link => {
    link.addEventListener('click', () => {
        if (innerWidth < 1200 && adminSidebar.classList.contains('show')) {
            bootstrap.Offcanvas.getOrCreateInstance(adminSidebar).hide();
        }
    });
});

const sidebarCollapse = document.querySelector('#sidebarCollapse');
const sidebarTooltips = [...document.querySelectorAll('[data-sidebar-tooltip]')].map(element => (
    bootstrap.Tooltip.getOrCreateInstance(element, {
        container: 'body',
        placement: 'right',
        trigger: 'hover focus',
        customClass: 'sidebar-tooltip',
    })
));

const updateSidebar = () => {
    if (! sidebarCollapse) {
        return;
    }

    const collapsed = document.documentElement.classList.contains('admin-sidebar-collapsed');
    const desktop = matchMedia('(min-width: 1200px)').matches;
    const action = collapsed ? 'Expand sidebar' : 'Collapse sidebar';

    sidebarCollapse.setAttribute('aria-pressed', String(collapsed));
    sidebarCollapse.setAttribute('aria-label', action);
    sidebarCollapse.title = action;

    sidebarTooltips.forEach(tooltip => {
        tooltip.hide();
        collapsed && desktop ? tooltip.enable() : tooltip.disable();
    });
};

updateSidebar();

sidebarCollapse?.addEventListener('click', () => {
    const collapsed = document.documentElement.classList.toggle('admin-sidebar-collapsed');

    try {
        localStorage.setItem('autoMotors.adminSidebarCollapsed', String(collapsed));
    } catch {
        // The sidebar still works when browser storage is unavailable.
    }

    updateSidebar();
});

addEventListener('resize', updateSidebar, { passive: true });
