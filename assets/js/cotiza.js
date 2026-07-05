// const quoteForm =
// document.getElementById("quoteForm");

// quoteForm.addEventListener("submit",(e)=>{

//   //e.preventDefault();

//   alert(
//     "Gracias por contactarnos. Nos comunicaremos contigo pronto."
//   );

//   //quoteForm.reset();

// });

quoteForm.addEventListener("submit", function (e) {

    if (!nameRegex.test(fullname.value.trim())) {

        e.preventDefault();

        //alert("Ingrese un nombre válido (solo letras y espacios).");

        fullname.focus();

        return;

    }

    if (!phoneRegex.test(phone.value.trim())) {

        e.preventDefault();

        //alert("El teléfono debe contener exactamente 9 dígitos.");

        phone.focus();

        return;

    }

    if (service.value === "") {

        e.preventDefault();

        //alert("Seleccione un servicio de interés.");

        service.focus();

        return;

    }

    alert("Gracias por contactarnos. Nos comunicaremos contigo pronto.");

});