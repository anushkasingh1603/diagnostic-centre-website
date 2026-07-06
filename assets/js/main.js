(function () {
  "use strict";

  // Smooth scroll
  document.addEventListener("click", function (e) {
    const a = e.target && e.target.closest ? e.target.closest("a[href^='#']") : null;
    if (!a) return;

    const id = a.getAttribute("href");
    if (!id || id === "#") return;

    const el = document.querySelector(id);
    if (!el) return;

    e.preventDefault();
    el.scrollIntoView({ behavior: "smooth", block: "start" });
  });

  // Scroll reveal
  const revealEls = document.querySelectorAll(".reveal");
  if (revealEls.length) {
    const io = new IntersectionObserver(
      (entries) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting) {
            entry.target.classList.add("is-visible");
            io.unobserve(entry.target);
          }
        });
      },
      { threshold: 0.12 }
    );

    revealEls.forEach((el) => io.observe(el));
  }

  // Animated counters
  const counterEls = Array.from(document.querySelectorAll(".stat-value[data-target]"));
  if (counterEls.length) {
    const counterObserver = new IntersectionObserver(
      (entries) => {
        entries.forEach((entry) => {
          if (!entry.isIntersecting) return;
          const el = entry.target;
          const target = Number(el.getAttribute("data-target")) || 0;
          const duration = 1400; // ms
          const start = performance.now();
          const initial = 0;

          function step(now) {
            const t = Math.min(1, (now - start) / duration);
            const eased = 1 - Math.pow(1 - t, 3);
            const value = Math.floor(initial + (target - initial) * eased);
            el.textContent = value.toLocaleString();
            if (t < 1) requestAnimationFrame(step);
          }

          requestAnimationFrame(step);
          counterObserver.unobserve(el);
        });
      },
      { threshold: 0.35 }
    );

    counterEls.forEach((el) => counterObserver.observe(el));
  }

  // Contact page: show success alert if ?success=1
  const params = new URLSearchParams(window.location.search);
  if (params.get("success") === "1") {
    const host = document.getElementById("contactSuccess");
    if (host) host.classList.remove("d-none");
  }
})();





