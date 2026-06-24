/* ==========================================
   ANIMACIÓN AL HACER SCROLL
========================================== */

const observer = new IntersectionObserver(
  (entries) => {
    entries.forEach((entry) => {

      if (entry.isIntersecting) {
        entry.target.classList.add("show-element");
      }

    });
  },
  {
    threshold: 0.15
  }
);

document
  .querySelectorAll(
    ".about-history-image, .about-history-content, .mv-card, .value-card"
  )
  .forEach((el) => observer.observe(el));

/* ==========================================
   LOGOS CLIENTES
========================================== */

const logos = document.querySelectorAll(".clients-logos img");

logos.forEach((logo) => {

  logo.addEventListener("mouseenter", () => {
    logo.style.transform = "scale(1.08)";
  });

  logo.addEventListener("mouseleave", () => {
    logo.style.transform = "scale(1)";
  });

});

/* ==========================================
   TARJETAS GIRATORIAS
========================================== */

const cards = document.querySelectorAll(".value-card");

cards.forEach((card) => {

  card.addEventListener("click", () => {

    card.classList.toggle("flipped");

  });

});


/* ==========================================
   CONTADOR ESTADÍSTICAS
========================================== */

const statsSection = document.querySelector(".about-stats");

let counterStarted = false;

const counterObserver = new IntersectionObserver(
  (entries) => {

    entries.forEach((entry) => {

      if (entry.isIntersecting && !counterStarted) {

        counterStarted = true;

        const counters = document.querySelectorAll(".counter");

        counters.forEach((counter) => {

          const target = +counter.dataset.target;

          let current = 0;

          const increment = target / 80;

          const updateCounter = () => {

            if (current < target) {

              current += increment;

              counter.textContent = Math.ceil(current);

              requestAnimationFrame(updateCounter);

            } else {

              counter.textContent = target;

            }

          };

          updateCounter();

        });

      }

    });

  },
  {
    threshold: 0.4
  }
);

counterObserver.observe(statsSection);

