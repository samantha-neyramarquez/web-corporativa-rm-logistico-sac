/* ==========================================
ANIMACIÓN SCROLL
========================================== */

const serviceCards =
document.querySelectorAll(".service-box");

const serviceObserver =
new IntersectionObserver((entries)=>{

  entries.forEach(entry=>{

    if(entry.isIntersecting){

      entry.target.classList.add("show-service");

    }

  });

},{
  threshold:.15
});

serviceCards.forEach(card=>{
  serviceObserver.observe(card);
});