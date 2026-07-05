<?php
session_start();

if (empty($_SESSION['csrf_token'])) {

    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

}
?>
<!doctype html>
<html lang="es">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>
Cotiza tu Operación de Comercio Exterior | R&M Logístico SAC
</title>

<meta
name="description"
content="Solicita una cotización personalizada para importación, exportación, aduanas, transporte y logística nacional e internacional."
>

<meta
name="keywords"
content="cotización logística, comercio exterior, importación, exportación, aduanas, transporte internacional"
>

<link
rel="canonical"
href="https://www.rmaduanas.com/cotiza.html">

<meta
  property="og:title"
  content="Cotiza | R&M Logístico SAC"
/>

<meta
  property="og:description"
  content="Solicita cotización personalizada de R&M Logístico SAC."
/>

<meta
  property="og:url"
  content="https://www.rmaduanas.com/cotiza.html"
/>
    <!-- ************************  -->
    <!-- ************************  -->
    <!-- librerias externas -->
    <!-- Favicon -->
    <link rel="icon" href="assets/img/logo-rm-fondo.png" />

    <!-- Bootstrap -->
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css"
      rel="stylesheet"
    />

    <!-- Google Fonts -->
    <link
      href="https://fonts.googleapis.com/css2?family=Oswald:wght@300;400;500;600;700&display=swap"
      rel="stylesheet"
    />

    <!-- Font Awesome -->
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
    />

    <!-- Conectar Estilos css -->
    <link rel="stylesheet" href="assets/css/cotiza.css" />
    <link rel="stylesheet" href="assets/css/styles.css" />
    <!-- ************************  -->
    <!-- ************************  -->
    
    <script
    src="https://www.google.com/recaptcha/api.js"
    async
    defer>

    </script>

  </head>
  <body>
       <!-- HEADER -->
    <!-- HEADER SUPERIOR -->
    <header>
      <div class="top-bar">
        <div class="wrapper top-content">
          <div class="contact-item">
            <i class="fas fa-map-marker-alt"></i>
            <span>JR. Daniel Hernández 1235, Pueblo Libre, Lima 21</span>
          </div>

          <div class="contact-item">
            <i class="fas fa-phone"></i>
            <span>+51 992 153 190</span>
          </div>

          <div class="contact-item">
            <i class="fas fa-envelope"></i>
            <span>roeltuyagerencia@rmaduanas.com</span>
          </div>
        </div>
      </div>

      <!-- NAVBAR -->

      <nav class="navbar">
        <div class="wrapper navbar-content">
          <!-- LOGO -->
          <a href="index.html" class="logo">
            <img
              src="assets/img/logo-header.png"
              alt="Logo de R&M Logístico S.A.C"
            />
          </a>

          <!-- MENU -->
          <ul class="nav-links" id="navLinks">
            <li><a href="index.html">Inicio</a></li>

            <li><a href="nosotros.html">Nosotros</a></li>

            <li><a href="servicios.html">Servicios</a></li>

            <li><a href="cobertura.html">Cobertura</a></li>

            <li><a href="contacto.html">Contacto</a></li>
          </ul>

          <!-- BOTONES -->
          <div class="nav-buttons">
            <a href="cotiza.php" class="btn-cotizar">
              Cotiza Ahora
              <i class="fas fa-file-lines"></i>
            </a>
            <a
              href="https://wa.me/51992153190"
              target="_blank"
              class="btn-whatsapp"
              aria-label="WhatsApp"
            >
              <i class="fab fa-whatsapp"></i>
            </a>
          </div>
          <!-- HAMBURGUESA -->
          <button
            class="menu-toggle"
            id="menuToggle"
            aria-label="Abrir menú"
            aria-expanded="false"
            aria-controls="navLinks"
          >
            <i class="fas fa-bars"></i>
          </button>
        </div>
      </nav>
    </header>
     <main class="quote-page">

  <!-- HERO -->

  <section class="quote-hero">

    <div class="wrapper">

      <h1>COTIZA</h1>

      <p>
        "Contáctanos y recibe asesoría personalizada"
      </p>

    </div>

  </section>

  <!-- INTRO -->

  <section class="quote-intro">

    <div class="wrapper intro-container">

      <div class="intro-icon">
        <i class="fas fa-headset"></i>
      </div>

      <div class="intro-content">

        <h2>
          ESTAMOS LISTOS PARA IMPULSAR TU NEGOCIO
        </h2>

        <p class="intro-text">
          Por favor rellena los siguientes campos y nos pondremos
          en contacto lo antes posible:
        </p>

        <p>
          Ingresa tus campos de contacto.
        </p>

        <span>
          Campos obligatorios *
        </span>

      </div>

    </div>

  </section>

  <!-- FORMULARIO -->

  <section class="quote-form-section">

    <div class="wrapper">

      <form
    class="quote-form"
    id="quoteForm"
    action="backend/send.php"
    method="POST"
  >
   <!-- CAMPO OCULTO Token CSRF -->
  <input
    type="hidden"
    name="csrf_token"
    value="<?php echo $_SESSION['csrf_token']; ?>"
>

  <h3>DATOS DE CONTACTO</h3>

  <div class="form-group">

    <label for="fullname">
      NOMBRES Y APELLIDOS *
    </label>

    <input
      type="text"
      id="fullname"
      name="fullname"
      placeholder="Ingresa tu nombre completo"
      required
      minlength="5"
      maxlength="100"
      pattern="^[A-Za-zÁÉÍÓÚáéíóúÑñÜü\s]+$"
      title="Ingrese únicamente letras y espacios (mínimo 5 caracteres)."
    />

  </div>

  <div class="form-group">

    <label for="phone">
      TELÉFONO DE CONTACTO *
    </label>

    <input
      type="tel"
      id="phone"
      name="phone"
      placeholder="987654321"
      required
      pattern="^[0-9]{9}$"
      maxlength="9"
      inputmode="numeric"
      title="Ingrese un número telefónico de 9 dígitos."
    />

  </div>

  <div class="form-group">

    <label for="email">
      CORREO DE CONTACTO *
    </label>

    <input
      type="email"
      id="email"
      name="email"
      placeholder="xxx@gmail.com"
      required
    >

  </div>

  <div class="form-group">

    <label for="service">
      SERVICIO DE INTERÉS *
    </label>

    <select
      id="service"
      name="service"
      required
    >

      <option value="" selected disabled>
        Selecciona un servicio
      </option>

      <option value="Negocios Internacionales">
        Negocios Internacionales
      </option>

      <option value="Importación">
        Importación
      </option>

      <option value="Exportación">
        Exportación
      </option>

      <option value="Agenciamiento de Aduanas">
        Agenciamiento de Aduanas
      </option>

      <option value="Transporte Logístico">
        Transporte Logístico
      </option>

      <option value="Asesoría Aduanera">
        Asesoría Aduanera
      </option>

    </select>

  </div>

  <!-- Honeypot -->
<!-- <div> -->
<div style="display:none;">
    <label for="website">

        Sitio web

    </label>

    <input
        type="text"
        id="website"
        name="website"
        autocomplete="off"
    >

</div>

 <!-- reCAPTCHA v2  -->

  <div

  class="g-recaptcha"

  data-sitekey="6LcSUDstAAAAAIuOAe4inImZKBP5p1HBfXqMtYko">

  </div>


  <button type="submit">
    Enviar
  </button>

</form>

    </div>

  </section>

</main>
<!-- PIE DE PÁGINA -->
    <footer>
      <div class="wrapper footer-container">
        <div class="footer-logo">
          <!-- <link rel="icon" href="assets/img/logo-rm.png"> -->
          <img
            src="assets/img/logo-footer.png"
            alt="Logo R&M Logístico S.A.C"
          />
        </div>

        <div class="footer-item">
          <i class="fas fa-phone"></i>

          <span>+51 992 153 190</span>
        </div>

        <div class="footer-item">
          <i class="fas fa-envelope"></i>

          <span>roeltuyagerencia@rmaduanas.com</span>
        </div>

        <div class="footer-item">
          <i class="fas fa-map-marker-alt"></i>

          <span> JR. Daniel Hernández 1235, Pueblo Libre, Lima 21 </span>
        </div>

        <div class="footer-item">
          <i class="fas fa-clock"></i>

          <span> Lun - Vie | 8:30 am - 6:00 pm </span>
        </div>
      </div>
    </footer>
    <!-- Conectar JS -->
    <script src="assets/js/cotiza.js"></script>
    <script src="assets/js/main.js"></script>

  </body>
</html>
