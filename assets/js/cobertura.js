/* ==========================================
   ANIMACIÓN SCROLL
========================================== */

const coverageCards =
document.querySelectorAll(".coverage-card");

const coverageObserver =
new IntersectionObserver((entries)=>{

  entries.forEach(entry=>{

    if(entry.isIntersecting){

      entry.target.classList.add("show");

    }

  });

},{
  threshold:.15
});

coverageCards.forEach(card=>{
  coverageObserver.observe(card);
});


