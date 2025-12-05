<!DOCTYPE html>
<html lang="es">

<head>
    <!-- Datos generales e imprescindibles -->
    <meta charset="UTF-8" />
    <title> Vientos del Sur </title>
    <!-- Etiquetas para SEO -->
    <meta name="description" content="Página web para la compra ficticia de artículos musicales ficticios.">
    <meta name="author" content="Jesús López Martínez">
    <meta name="generator" content="VSCode">
    <meta name="google" content="notranslate">
    <meta name="robots" content="noindex, nofollow, noimageindex">
    <!-- Recursos externos -->
    <link rel="icon" href="resources/logo_sin_texto.png" />
    <link rel="stylesheet" href="css/main-page.css">
</head>

<body>
    <header>
        <ul id="header-ul">
            <li><a href=""><img src="resources/user.png" /></a></li>
            <li><img src="resources/vientos-del-sur-high-resolution-logo-transparent.png" id="logo" /></li>
            <li><a href=""><img src="resources/shopping-cart.png" /></a></li>
        </ul>
    </header>
    <nav class="navbar">
        <input type="checkbox" id="nav-toggle" class="nav-toggle">
        <label for="nav-toggle" class="nav-toggle-label">
            <span></span>
        </label>

        <ul class="nav-links">
            <li><a href="index.html">Home</a></li>
            <li class="dropdown">
                <a href="#">Instrumentos <i class="fa fa-caret-down"></i></a>
                <ul class="dropdown-content">
                    <li><a href="#">Guitarras y bajos</a></li>
                    <li><a href="#">Teclados y pianos</a></li>
                    <li><a href="#">Baterías</a></li>
                </ul>
            </li>
            <li class="dropdown">
                <a href="#">Accesorios <i class="fa fa-caret-down"></i></a>
                <ul class="dropdown-content">
                    <li><a href="#">Cables</a></li>
                    <li><a href="#">Fundas</a></li>
                    <li><a href="#">Correas</a></li>
                    <li><a href="#">Soportes</a></li>
                </ul>
            </li>
            <li><a href="https://dle.rae.es/servicio" target="_blank">Servicios</a></li>
        </ul>
    </nav>

    <main class="producto-main">
        <section class="producto-wrapper">
            <div class="producto-galeria">
                <div class="imagen-principal">
                    <img id="main-img" src="resources/guitarra_clasica.png" alt="Fender Stratocaster Frontal">
                </div>
                <div class="miniaturas">
                    <img src="resources/guitarra_electrica.png" onclick="changeImage(this)" alt="Vista frontal">
                    <img src="resources/pandereta.png" onclick="changeImage(this)" alt="Vista trasera"> <img
                        src="resources/guitarra_clasica.png" onclick="changeImage(this)" alt="Detalle pastillas">
                    <img src="resources/armonica.png" onclick="changeImage(this)" alt="Pala">
                </div>
            </div>

            <div class="producto-datos">
                <h1 class="titulo-producto">Guitarra</h1>
                <p class="sku">Ref: FND-STRAT-60-SB</p>

                <div class="precio-box">
                    <span class="precio-antiguo">1.200€</span>
                    <span class="precio-actual">980€</span>
                    <span class="etiqueta-oferta">-18%</span>
                </div>

                <p class="stock-status"><i class="fa fa-check-circle"></i> En Stock (Envío en 24h)</p>

                <p class="descripcion-corta">
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore
                    et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut
                    aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
                    cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in
                    culpa qui officia deserunt mollit anim id est laborum.
                </p>

                <div class="acciones-compra">
                    <input type="number" value="1" min="1" max="5">
                    <button class="btn-anadir">Añadir al Carrito</button>
                </div>

                <div class="detalles-tecnicos">
                    <h3>Características Destacadas</h3>
                    <ul>
                        <li><strong>Cuerpo:</strong> --- </li>
                        <li><strong>Mástil:</strong> --- </li>
                        <li><strong>Diapasón:</strong> --- </li>
                        <li><strong>Pastillas:</strong> ---</li>
                        <li><strong>Puente:</strong> --- </li>
                    </ul>
                </div>
            </div>
        </section>
    </main>

    <footer class="site-footer">
        <div class="footer-container">
            <div class="footer-col">
                <h3>Vientos del Sur</h3>
                <p>Tu tienda de confianza para instrumentos musicales ficticios. Desde guitarras clásicas hechas de
                    ilusión hasta sintetizadores
                    modernos con 0% de materia física, llevamos la música a tu hogar.</p>
                <div class="social-links">
                    <a href="https://www.facebook.com/?locale=es_ES" target="_blank"><i class="fa fa-facebook"><img
                                src="resources/facebook.png" /></i></a>
                    <a href="https://www.instagram.com" target="_blank"><i class="fa fa-instagram"><img
                                src="resources/instagram.png" /></i></a>
                    <a href="https://x.com/?lang=es" target="_blank"><i class="fa fa-x"><img
                                src="resources/twitter.png" /></i></a>
                    <a href="https://www.youtube.com" target="_blank"><i class="fa fa-youtube"><img
                                src="resources/youtube.png" /></i></a>
                </div>
            </div>

            <div class="footer-col">
                <h3>Atención al Cliente</h3>
                <ul>
                    <li><a href="#">Envíos y Devoluciones</a></li>
                    <li><a href="#">Preguntas Frecuentes</a></li>
                    <li><a href="#">Garantía</a></li>
                    <li><a href="#">Estado del pedido</a></li>
                </ul>
            </div>

            <div class="footer-col">
                <h3>Contacto</h3>
                <ul class="contact-info">
                    <li><i class="fa fa-map-marker"></i> Calle Mentira 111, Sevilla</li>
                    <li><i class="fa fa-phone"></i> +34 XXX XXX XXX</li>
                    <li><i class="fa fa-envelope"></i> hola@vientosdelsur.com</li>
                </ul>
            </div>

            <div class="footer-col">
                <h3>Newsletter</h3>
                <p>Suscríbete para recibir ofertas exclusivas.</p>
                <form class="newsletter-form">
                    <input type="email" placeholder="Tu email aquí..." required>
                    <button type="submit"><img src="resources/send.png" /></button>
                </form>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2025 Vientos del Sur. Todos los derechos reservados, si fuese una web de verdad.</p>
        </div>
    </footer>

    <script>
        function changeImage(thumbnail) {
            document.getElementById('main-img').src = thumbnail.src;
        }
    </script>
</body>

</html>