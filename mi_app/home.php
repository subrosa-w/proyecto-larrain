<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit();
}
$usuario = $_SESSION['usuario'];
$rol = $_SESSION['rol'] ?? 'usuario';
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <title>Inicio - Mi Aplicación</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
  <style>
    /* Remove the navbar's default margin-bottom and rounded borders */ 
    .navbar {
      margin-bottom: 0;
      border-radius: 0;
    }
    
    /* Add a gray background color and some padding to the footer */
    footer {
      background-color: #f2f2f2;
      padding: 25px;
    }
    
    .carousel-inner > .item > img, .carousel-inner img {
      width: 100% !important;
      height: 700px !important;
      object-fit: cover !important;
      margin: auto;
      background-color: var(--azul-gris);
    }

    /* Hide the carousel text when the screen is less than 600 pixels wide */
    @media (max-width: 600px) {
      .carousel-caption {
        display: none; 
      }
    }

    .carousel-caption {
      background: rgba(13, 27, 42, 0.37);
      border-radius: 10px;
      padding: 20px;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      height: 100%;
      left: 0;
      right: 0;
      top: 0;
      bottom: 0;
      position: absolute;
      text-align: center;
    }
    .carousel-caption h3 {
      font-size: 3.5rem;
      font-weight: bold;
    }
    .carousel-caption p {
      font-size: 2rem;
      margin-top: 1rem;
    }

    /* Estilos para la sección Features */
    .b-example-divider {
      width: 100%;
      height: 3rem;
      background-color: rgba(0, 0, 0, .1);
      border: solid rgba(0, 0, 0, .15);
      border-width: 1px 0;
      box-shadow: inset 0 .5em 1.5em rgba(0, 0, 0, .1), inset 0 .125em .5em rgba(0, 0, 0, .15);
    }

    .feature-icon {
      width: 4rem;
      height: 4rem;
    }

    .icon-link {
      display: inline-flex;
      align-items: center;
      text-decoration: inherit;
      color: var(--bs-link-color);
    }

    .icon-link:hover {
      color: var(--bs-link-hover-color);
    }

    .icon-link > .bi {
      margin-left: 0.125em;
      transition: transform 0.25s ease-in-out;
    }

    .icon-link:hover > .bi {
      transform: translateX(0.25em);
    }

    .text-body-emphasis {
      color: #333 !important;
    }

    .bg-body-tertiary {
      background-color: #f8f9fa !important;
    }

    .text-bg-primary {
      color: #fff !important;
      background-color: #337ab7 !important;
    }

    .bg-gradient {
      background-image: linear-gradient(135deg, #337ab7 0%, #23527c 100%) !important;
    }

    .fs-2 {
      font-size: 2rem !important;
    }

    .fs-3 {
      font-size: 1.75rem !important;
    }

    .border-bottom {
      border-bottom: 1px solid #ddd !important;
    }

    .pb-2 {
      padding-bottom: 0.5rem !important;
    }

    .py-5 {
      padding-top: 3rem !important;
      padding-bottom: 3rem !important;
    }

    .px-4 {
      padding-right: 1.5rem !important;
      padding-left: 1.5rem !important;
    }

    .my-5 {
      margin-top: 3rem !important;
      margin-bottom: 3rem !important;
    }

    .mb-3 {
      margin-bottom: 1rem !important;
    }

    .g-4 {
      margin-left: -15px;
      margin-right: -15px;
    }

    .g-4 > .col {
      padding-left: 15px;
      padding-right: 15px;
    }

    .row-cols-1 > * {
      width: 100%;
    }

    .row-cols-lg-3 > * {
      width: 33.33333333%;
    }

    @media (min-width: 992px) {
      .row-cols-lg-3 > * {
        width: 33.33333333%;
      }
    }

    .rounded-3 {
      border-radius: 6px !important;
    }

    .d-inline-flex {
      display: inline-flex !important;
    }

    .align-items-center {
      align-items: center !important;
    }

    .justify-content-center {
      justify-content: center !important;
    }

    /* Estilos específicos para Bootstrap 3 */
    #featured-3 .row {
      display: flex;
      flex-wrap: wrap;
      margin-left: -15px;
      margin-right: -15px;
    }

    #featured-3 .feature {
      float: left;
      width: 33.33333333%;
      padding-left: 15px;
      padding-right: 15px;
      margin-bottom: 30px;
    }

    @media (max-width: 991px) {
      #featured-3 .feature {
        width: 100%;
        float: none;
      }
    }

    /* Espaciado adicional para la sección */
    #featured-3 {
      padding: 60px 0;
    }

    #featured-3 h2 {
      margin-bottom: 40px;
      text-align: center;
    }

    #featured-3 .feature {
      text-align: center;
      padding: 20px;
    }

    #featured-3 .feature h3 {
      margin-top: 20px;
      margin-bottom: 15px;
    }

    #featured-3 .feature p {
      line-height: 1.6;
      color: #666;
    }

    /* Estilos para el panel de usuario */
    .user-panel {
      background: #f8f9fa;
      border: 1px solid #dee2e6;
      border-radius: 5px;
      padding: 15px;
      margin-bottom: 20px;
    }

    .role-badge {
      display: inline-block;
      padding: 4px 8px;
      border-radius: 4px;
      font-size: 12px;
      font-weight: bold;
      text-transform: uppercase;
    }

    .role-admin {
      background-color: #d9534f;
      color: white;
    }

    .role-gestor {
      background-color: #f0ad4e;
      color: white;
    }

    .role-usuario {
      background-color: #5bc0de;
      color: white;
    }
  </style>
</head>
<body>

<nav class="navbar navbar-inverse">
  <div class="container-fluid">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>                        
      </button>
      <a class="navbar-brand" href="#">Importadora Larrain</a>
    </div>
    <div class="collapse navbar-collapse" id="myNavbar">
      <ul class="nav navbar-nav">
        <li class="active"><a href="home.php">Inicio</a></li>
        <li><a href="catalogo.php">Catálogo</a></li>
        <?php if ($rol === 'gestor_precios'): ?>
        <li><a href="editar_precios.php">Gestión de Precios</a></li>
        <?php endif; ?>
      </ul>
      <ul class="nav navbar-nav navbar-right">
        <li><a href="logout.php"><span class="glyphicon glyphicon-log-out"></span> Cerrar sesión</a></li>
      </ul>
    </div>
  </div>
</nav>

<div id="myCarousel" class="carousel slide" data-ride="carousel">
    <!-- Indicators -->
    <ol class="carousel-indicators">
      <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
      <li data-target="#myCarousel" data-slide-to="1"></li>
    </ol>

    <!-- Wrapper for slides -->
    <div class="carousel-inner" role="listbox">
      <div class="item active">
        <img src="imagenes/1c0f440a8d430d36b4b338ca48cbc0e9.jpg" alt="Importadora Larraín">
        <div class="carousel-caption">
          <h3>Bienvenido a Importadora Larraín</h3>
          <p>15 años importando innovación y calidad desde China.</p>
        </div>      
      </div>

      <div class="item">
        <img src="imagenes/pexels-photo-3031670.jpeg" alt="Accesorios Electrónicos">
        <div class="carousel-caption">
          <h3>Especialistas en Accesorios Electrónicos</h3>
          <p>Parlantes, pendrives, llaveros, punteros, carcasas y mucho más.</p>
        </div>      
      </div>
    </div>

    <!-- Left and right controls -->
    <a class="left carousel-control" href="#myCarousel" role="button" data-slide="prev">
      <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
      <span class="sr-only">Previous</span>
    </a>
    <a class="right carousel-control" href="#myCarousel" role="button" data-slide="next">
      <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
      <span class="sr-only">Next</span>
    </a>
</div>

<div class="b-example-divider"></div>
<div class="container my-5">
  <div class="p-5 text-center bg-body-tertiary rounded-3">
    <h1 class="text-body-emphasis">¿Quiénes somos?</h1>
    <p class="lead">
      Ubicados en calle San Alfonso, somos una empresa con una sólida trayectoria en la importación y venta de accesorios electrónicos. Nuestra oferta incluye parlantes, pendrives, llaveros, punteros, carcasas de celulares y otros productos innovadores.
    </p>
  </div>
</div>

<div class="b-example-divider"></div>

<div class="container" id="featured-3">
  <h2 class="pb-2 border-bottom">¿Por qué elegirnos?</h2>
  <div class="row">
    <div class="col-md-4 feature">
      <div class="feature-icon d-inline-flex align-items-center justify-content-center text-bg-primary bg-gradient fs-2 mb-3 rounded-3">
        <svg class="bi" width="1em" height="1em"><use xlink:href="#people-circle"></use></svg>
      </div>
      <h3 class="fs-2 text-body-emphasis">Experiencia y confianza</h3>
      <p>Con más de 15 años en el mercado, hemos construido una sólida reputación basada en la confianza de nuestros clientes. Nuestro equipo de supervisores y personal capacitado está disponible en ambas sucursales para brindar la mejor atención personalizada.</p>
    </div>
    <div class="col-md-4 feature">
      <div class="feature-icon d-inline-flex align-items-center justify-content-center text-bg-primary bg-gradient fs-2 mb-3 rounded-3">
        <svg class="bi" width="1em" height="1em"><use xlink:href="#collection"></use></svg>
      </div>
      <h3 class="fs-2 text-body-emphasis">Variedad de productos</h3>
      <p>Contamos con un extenso catálogo que incluye parlantes, pendrives, llaveros, punteros láser, carcasas de celulares y muchos más accesorios electrónicos de alta calidad. Todos nuestros productos son cuidadosamente seleccionados para garantizar la mejor experiencia del usuario.</p>
    </div>
    <div class="col-md-4 feature">
      <div class="feature-icon d-inline-flex align-items-center justify-content-center text-bg-primary bg-gradient fs-2 mb-3 rounded-3">
        <svg class="bi" width="1em" height="1em"><use xlink:href="#toggles2"></use></svg>
      </div>
      <h3 class="fs-2 text-body-emphasis">Atención personalizada</h3>
      <p>Nuestro compromiso es brindar una atención personalizada a cada cliente. Contamos con personal capacitado en ambas sucursales que está listo para asesorarte y ayudarte a encontrar exactamente lo que necesitas para tus proyectos electrónicos.</p>
    </div>
  </div>
</div>

<div class="b-example-divider"></div>

<div class="container my-5">
  <div class="user-panel">
    <h4>Información de Usuario</h4>
    <p><strong>Usuario:</strong> <?= htmlspecialchars($usuario) ?></p>
    <p><strong>Rol:</strong> 
      <span class="role-badge <?= $rol === 'admin' ? 'role-admin' : ($rol === 'gestor_precios' ? 'role-gestor' : 'role-usuario') ?>">
        <?= $rol === 'admin' ? 'Administrador' : ($rol === 'gestor_precios' ? 'Gestor de Precios' : 'Usuario') ?>
      </span>
    </p>
    <div class="mt-3">
      <a href="catalogo.php" class="btn btn-primary">Ver Catálogo</a>
      <?php if ($rol === 'gestor_precios'): ?>
        <a href="editar_precios.php" class="btn btn-warning">Gestión de Precios</a>
      <?php endif; ?>
    </div>
  </div>
</div>

<footer class="py-4 my-4 border-top">
  <div class="container">
    <div class="row">
      <div class="col-md-6">
        <h5>Contacto</h5>
        <p class="mb-1"><strong>Teléfono:</strong> +56 9 1234 5678</p>
        <p class="mb-1"><strong>Email:</strong> contacto@importadoralarrain.cl</p>
        <p class="mb-1"><strong>Dirección:</strong> Calle San Alfonso</p>
      </div>
      <div class="col-md-6">
        <h5>Enlaces Rápidos</h5>
        <ul class="list-unstyled">
          <li><a href="home.php" class="text-decoration-none">Inicio</a></li>
          <li><a href="catalogo.php" class="text-decoration-none">Catálogo</a></li>
          <?php if ($rol === 'gestor_precios'): ?>
          <li><a href="editar_precios.php" class="text-decoration-none">Gestión de Precios</a></li>
          <?php endif; ?>
        </ul>
      </div>
    </div>
    <div class="row mt-3">
      <div class="col-12 text-center">
        <p class="text-body-secondary mb-0">&copy; 2024 Importadora Larraín. Todos los derechos reservados.</p>
      </div>
    </div>
  </div>
</footer>

<svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
  <symbol id="collection" viewBox="0 0 16 16">
    <path d="M2.5 3.5a.5.5 0 0 1 0-1h11a.5.5 0 0 1 0 1h-11zm2-2a.5.5 0 0 1 0-1h7a.5.5 0 0 1 0 1h-7zM0 13a1.5 1.5 0 0 0 1.5 1.5h13A1.5 1.5 0 0 0 16 13V6a1.5 1.5 0 0 0-1.5-1.5h-13A1.5 1.5 0 0 0 0 6v7zm1.5.5A.5.5 0 0 1 1 13V6a.5.5 0 0 1 .5-.5h13a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-.5.5h-13z"/>
  </symbol>
  <symbol id="people-circle" viewBox="0 0 16 16">
    <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0z"/>
    <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1z"/>
  </symbol>
  <symbol id="toggles2" viewBox="0 0 16 16">
    <path d="M9.465 10H7a3 3 0 1 1 0-6h5a3 3 0 1 1 0 6h-2.535c.34.588.535 1.271.535 2 0 1.657-1.343 3-3 3s-3-1.343-3-3c0-.729.195-1.412.535-2z"/>
    <path d="M7 4a4 4 0 1 0 0 8 4 4 0 0 0 0-8zm0 6a2 2 0 1 1 0-4 2 2 0 0 1 0 4z"/>
  </symbol>
  <symbol id="arrow-right" viewBox="0 0 16 16">
    <path fill-rule="evenodd" d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8z"/>
  </symbol>
</svg>

</body>
</html>