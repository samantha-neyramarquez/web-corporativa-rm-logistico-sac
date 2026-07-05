<?php

session_start();
// Configurar zona horaria
date_default_timezone_set('America/Lima');  


/**
 * Envío del formulario de cotización con PHPMailer
 */

header("X-Content-Type-Options: nosniff");
header("X-Frame-Options: SAMEORIGIN");
header("Referrer-Policy: strict-origin-when-cross-origin");

header("Content-Security-Policy: default-src 'self';");
header("Content-Security-Policy: default-src 'self'; script-src 'self'; style-src 'self' 'unsafe-inline';");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';
require 'RateLimiter.php';
require 'Logger.php';

// =============================
// Configuración del archivo Log
// =============================

$logger = new Logger(

    __DIR__ . "/logs/cotizaciones.log"

);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

// =============================
// Honeypot
// =============================
$ip = $_SERVER['REMOTE_ADDR'];
$website = trim($_POST['website'] ?? '');

if (!empty($website)) {

    $logger->warning(

        $ip,

        "",

        "",

        "",

        "Resultado: BLOQUEADO | Honeypot detectó posible bot"

    );

    exit('
    <div style="max-width:600px;margin:50px auto;text-align:center;font-family:sans-serif;">

        <h2>❌ Solicitud rechazada</h2>

        <p>

        No fue posible procesar la solicitud.

        </p>

        <a href="../cotiza.php">

        ⬅ Regresar

        </a>

    </div>');

}


// =============================
// Validar Token CSRF
// =============================
$captcha = $_POST['g-recaptcha-response'] ?? '';
$secretKey = "6LcSUDstAAAAALSEIXFDUAanmwG0xvckUDATo-_V";

// Verificar que el usuario marcó el reCAPTCHA

if (empty($captcha)) {

    $logger->error(

        $ip,

        "",

        "",

        "",

        "Resultado: ERROR | reCAPTCHA no completado"

    );

    exit('
    <div style="max-width:600px;margin:50px auto;text-align:center;font-family:sans-serif;">
        <h2>❌ Verificación requerida</h2>
        <p>Debe completar el reCAPTCHA.</p>
        <a href="../cotiza.php">⬅ Regresar</a>
    </div>');

}

//esto es solo
// Validar el token con Google

$url = "https://www.google.com/recaptcha/api/siteverify";

$data = [

    'secret' => $secretKey,

    'response' => $captcha,

    'remoteip' => $_SERVER['REMOTE_ADDR']

];

$options = [

    'http' => [

        'header' => "Content-type: application/x-www-form-urlencoded",

        'method' => 'POST',

        'content' => http_build_query($data)

    ]

];

$context = stream_context_create($options);

$response = file_get_contents($url, false, $context);

$result = json_decode($response);

if (!$result->success) {

    $logger->error(

        $ip,

        "",

        "",

        "",

        "Resultado: ERROR | reCAPTCHA inválido"

    );

    exit('
    <div style="max-width:600px;margin:50px auto;text-align:center;font-family:sans-serif;">
        <h2>❌ Verificación fallida</h2>
        <p>No fue posible validar el reCAPTCHA.</p>
        <a href="../cotiza.php">⬅ Regresar</a>
    </div>');

}


if (

    !isset($_POST['csrf_token']) ||

    !isset($_SESSION['csrf_token']) ||

    !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])

) {

        $logger->error(

        $ip,

        "",

        "",

        "",

        "Resultado: ERROR | Token CSRF inválido"

    );

    exit('
    <div style="max-width:600px;margin:50px auto;text-align:center;font-family:sans-serif;">
        <h2>❌ Solicitud no válida</h2>

        <p>
        No fue posible validar la solicitud enviada.
        </p>

        <a href="../cotiza.php">⬅ Regresar</a>
    </div>');

}

    // ============================
    // Obtener datos del formulario
    // ============================

    $fullname = trim($_POST['fullname'] ?? '');
    $phone    = trim($_POST['phone'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $service  = trim($_POST['service'] ?? '');


// =============================
// RATE LIMITING
// =============================

$rateLimiter = new RateLimiter(

    __DIR__ . "/logs/rate_limit.json",

    5,      // máximo de solicitudes

    10      // minutos

);

if (!$rateLimiter->verificar($ip, $email)) {

    $logger->warning(

        $ip,

        "",

        $email,

        "",

        "Resultado: BLOQUEADO | Rate Limiting"

    );

    http_response_code(429);

    exit('

    <div style="max-width:600px;margin:50px auto;text-align:center;font-family:sans-serif;">

        <h2>⛔ Demasiadas solicitudes</h2>

        <p>

        Ha excedido el número permitido de envíos.

        Espere unos minutos antes de volver a intentarlo.

        </p>

        <a href="../cotiza.php">

        ⬅ Regresar

        </a>

    </div>

    ');

}


    // ============================
    // Validar campos
    // ============================

    if (
        empty($fullname) ||
        empty($phone) ||
        empty($email) ||
        empty($service)
    ) {
       
          exit('
    <div style="max-width:600px;margin:50px auto;text-align:center;font-family:sans-serif;">
        <h2>❌ Campos incompletos</h2>

        <p>Todos los campos son obligatorios.</p>

        <a href="../cotiza.php">⬅ Regresar</a>
    </div>');
    }

    // ============================
    // Validar longitud del nombre
    // ============================

    if (strlen($fullname) < 5 || strlen($fullname) > 100) {

        exit('
        <div style="max-width:600px;margin:50px auto;text-align:center;font-family:sans-serif;">
            <h2>❌ Nombre no válido</h2>

            <p>
            El nombre debe contener entre 5 y 100 caracteres.
            </p>

            <a href="../cotiza.php">⬅ Regresar</a>
        </div>');

    }

    // ============================
// Validar caracteres del nombre
// ============================

if (!preg_match('/^[A-Za-zÁÉÍÓÚáéíóúÑñÜü\s]+$/u', $fullname)) {

    exit('
    <div style="max-width:600px;margin:50px auto;text-align:center;font-family:sans-serif;">
        <h2>❌ Nombre no válido</h2>

        <p>
        El nombre únicamente puede contener letras y espacios.
        </p>

        <a href="../cotiza.php">⬅ Regresar</a>
    </div>');

}

    // ============================
    // Validar teléfono
    // ============================

    if (!preg_match('/^[0-9]{9}$/', $phone)) {

        exit('
        <div style="max-width:600px;margin:50px auto;text-align:center;font-family:sans-serif;">
            <h2>❌ Teléfono no válido</h2>

            <p>
            El teléfono debe contener exactamente 9 dígitos.
            </p>

            <a href="../cotiza.php">⬅ Regresar</a>
        </div>');

    }

    // ============================
    // Validar correo
    // ============================

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

           $logger->error(

    $ip,

    $fullname,

    $email,

    "",

    "Resultado: ERROR | Correo electrónico inválido"

);

        exit('
        <div style="max-width:600px;margin:50px auto;text-align:center;font-family:sans-serif;">
            <h2>❌ Correo electrónico no válido</h2>

            <p>
            Ingrese un correo electrónico válido.
            </p>

            <a href="../cotiza.php">⬅ Regresar</a>
        </div>');

    }

    // ============================
    // Servicios permitidos
    // ============================

    $serviciosPermitidos = [

        "Negocios Internacionales",

        "Importación",

        "Exportación",

        "Agenciamiento de Aduanas",

        "Transporte Logístico",

        "Asesoría Aduanera"

    ];

    if (!in_array($service, $serviciosPermitidos)) {

    exit('
    <div style="max-width:600px;margin:50px auto;text-align:center;font-family:sans-serif;">
        <h2>❌ Servicio no válido</h2>

        <p>
        Seleccione un servicio válido.
        </p>

        <a href="../cotiza.php">⬅ Regresar</a>
    </div>');

}

    // ============================
    // Sanitizar datos
    // ============================

    $fullname = htmlspecialchars($fullname, ENT_QUOTES, 'UTF-8');
    $phone    = htmlspecialchars($phone, ENT_QUOTES, 'UTF-8');
    $email    = htmlspecialchars($email, ENT_QUOTES, 'UTF-8');
    $service  = htmlspecialchars($service, ENT_QUOTES, 'UTF-8');

    // ============================
    // Configuración SMTP
    // ============================

    $mailHost     = 'smtp.gmail.com';
    $mailUsername = 'educativoautonoma@gmail.com'; 
    $mailPassword = 'ubcl cwdx nkjm rygw';
    $mailPort     = 587;

    $mail = new PHPMailer(true);

    try {

        // Configuración SMTP

        $mail->isSMTP();
        $mail->CharSet = 'UTF-8';
        $mail->Host = $mailHost;
        $mail->SMTPAuth = true;
        $mail->Username = $mailUsername;
        $mail->Password = $mailPassword;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = $mailPort;

        // Remitente

        $mail->setFrom($mailUsername, 'Formulario de Cotización');

        // Destinatario

        $mail->addAddress($mailUsername, 'Administrador');

        // Responder al cliente

        $mail->addReplyTo($email, $fullname);

        // ============================
        // Contenido del correo
        // ============================

        $mail->isHTML(true);

        $mail->Subject = 'Nueva Solicitud de Cotización - R&M Logístico SAC';

        $mail->Body = "

        <h2>Nueva Solicitud de Cotización - R&M Logístico SAC</h2>

        <hr>

        <p><strong>Nombre completo:</strong> {$fullname}</p>

        <p><strong>Teléfono:</strong> {$phone}</p>

        <p><strong>Correo:</strong> {$email}</p>

        <p><strong>Servicio de interés:</strong> {$service}</p>

        ";

        $mail->AltBody =
        "Nueva Solicitud de Cotización - R&M Logístico SAC

        Nombre: {$fullname}

        Teléfono: {$phone}

        Correo: {$email}

        Servicio: {$service}";

        // Enviar

        //$mail->SMTPDebug = 2;
        //$mail->Debugoutput = 'html';
        
        $mail->send();

        unset($_SESSION['csrf_token']);

        $logger->info(

        $ip,

        $fullname,

        $email,

        $service,

        "Resultado: ÉXITO | Cotización enviada correctamente"

    );

        echo '
        <div style="max-width:600px;margin:50px auto;text-align:center;font-family:sans-serif;">
            <h2>✅ Cotización enviada correctamente</h2>
            <p>Nos comunicaremos contigo lo antes posible.</p>
            <a href="../cotiza.php">⬅ Volver</a>
        </div>';

    } 
    // catch (Exception $e) {

    //     echo '
    //     <div style="max-width:600px;margin:50px auto;text-align:center;font-family:sans-serif;">
    //         <h2>❌ Error al enviar la cotización</h2>
    //         <p>'.$mail->ErrorInfo.'</p>
    //         <a href="../cotiza.html">⬅ Regresar</a>
    //     </div>';

    // }
    catch (Exception $e) {

    $mensaje = "No fue posible enviar la cotización. Inténtelo nuevamente más tarde.";

    // Error por dirección de correo inválida
    if (strpos($mail->ErrorInfo, "Invalid address") !== false) {

        $mensaje = "No se pudo procesar la dirección de correo electrónico ingresada. Verifique que esté escrita correctamente.";

    }

    // Error de conexión SMTP
    elseif (
        strpos($mail->ErrorInfo, "SMTP connect() failed") !== false ||
        strpos($mail->ErrorInfo, "SMTP Error") !== false
    ) {

        $mensaje = "El servicio de correo no se encuentra disponible temporalmente. Por favor, inténtelo nuevamente más tarde.";

    }

    $logger->error(

        $ip,

        $fullname,

        $email,

        $service,

        "Resultado: ERROR | " . $mensaje

    );


    echo '
    <div style="max-width:600px;margin:50px auto;text-align:center;font-family:sans-serif;">
        <h2>❌ Error al enviar la cotización</h2>

        <p>'.$mensaje.'</p>

        <a href="../cotiza.php">⬅ Regresar</a>
    </div>';

}

} else {

    echo 'Método no permitido.';

}