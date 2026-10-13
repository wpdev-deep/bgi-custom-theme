/**
 * Global scripts for BGI theme (header, mobile menu, preloader, cursor, demo modal).
 */

/* ==================== PRELOADER ==================== */
window.addEventListener('load', () => {
  const preloader = document.getElementById('preloader');
  if (preloader) {
    setTimeout(() => preloader.classList.add('done'), 700);
  }
});

/* ==================== CUSTOM CURSOR ==================== */
(function initCursor() {
  const dot = document.getElementById('cursorDot');
  const ring = document.getElementById('cursorRing');
  const isTouch = window.matchMedia('(hover:none), (pointer:coarse)').matches;
  if (isTouch || !dot || !ring) return;
  let mx = 0, my = 0, rx = 0, ry = 0;
  window.addEventListener('mousemove', (e) => {
    mx = e.clientX;
    my = e.clientY;
    dot.style.left = mx + 'px';
    dot.style.top = my + 'px';
  });
  function loop() {
    rx += (mx - rx) * 0.18;
    ry += (my - ry) * 0.18;
    ring.style.left = rx + 'px';
    ring.style.top = ry + 'px';
    requestAnimationFrame(loop);
  }
  loop();
  document.querySelectorAll('a, button, .tilt, input, textarea').forEach((el) => {
    el.addEventListener('mouseenter', () => ring.classList.add('hovered'));
    el.addEventListener('mouseleave', () => ring.classList.remove('hovered'));
  });
})();

/* ==================== HEADER SCROLL ==================== */
const header = document.getElementById('siteHeader');
if (header) {
  window.addEventListener('scroll', () => {
    header.classList.toggle('scrolled', window.scrollY > 40);
  }, { passive: true });
}

/* ==================== MOBILE MENU ==================== */
const hamburgerBtn = document.getElementById('hamburgerBtn');
const mobileMenu = document.getElementById('mobileMenu');
const menuOverlay = document.getElementById('menuOverlay');
function closeMenu() {
  if (mobileMenu) mobileMenu.classList.remove('open');
  if (menuOverlay) menuOverlay.classList.remove('open');
  if (hamburgerBtn) hamburgerBtn.classList.remove('open');
}
if (hamburgerBtn && mobileMenu && menuOverlay) {
  hamburgerBtn.addEventListener('click', () => {
    const isOpen = mobileMenu.classList.toggle('open');
    menuOverlay.classList.toggle('open', isOpen);
    hamburgerBtn.classList.toggle('open', isOpen);
  });
  menuOverlay.addEventListener('click', closeMenu);
  mobileMenu.querySelectorAll('a').forEach((a) => a.addEventListener('click', closeMenu));
}

/* ==================== DEMO MODAL ==================== */
const demoOverlay = document.getElementById('demoOverlay');
const demoModal = document.getElementById('demoModal');
function openDemoModal() {
  closeMenu();
  if (demoOverlay) demoOverlay.classList.add('open');
  if (demoModal) demoModal.classList.add('open');
  document.body.style.overflow = 'hidden';
  const firstField = document.getElementById('dm-name');
  if (firstField) firstField.focus();
}
function closeDemoModal() {
  if (demoOverlay) demoOverlay.classList.remove('open');
  if (demoModal) demoModal.classList.remove('open');
  document.body.style.overflow = '';
}
if (demoOverlay) demoOverlay.addEventListener('click', closeDemoModal);
const demoModalClose = document.getElementById('demoModalClose');
if (demoModalClose) demoModalClose.addEventListener('click', closeDemoModal);
window.addEventListener('keydown', (e) => {
  if (e.key === 'Escape' && demoModal && demoModal.classList.contains('open')) {
    closeDemoModal();
  }
});
const demoForm = document.getElementById('demoForm');
if (demoForm) {
  demoForm.addEventListener('submit', function (e) {
    e.preventDefault();
    const note = document.getElementById('demoFormNote');
    if (note) note.classList.add('show');
    this.reset();
    setTimeout(closeDemoModal, 1800);
  });
}
window.openDemoModal = openDemoModal;
window.closeDemoModal = closeDemoModal;
