const quoteForm =
document.getElementById("quoteForm");

quoteForm.addEventListener("submit",(e)=>{

  e.preventDefault();

  alert(
    "Gracias por contactarnos. Nos comunicaremos contigo pronto."
  );

  quoteForm.reset();

});