<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="styles.css">
</head> 
<body>
    <div class="page">
        <section class="container">
            <nav class="navbar" style="display: flex;">
                <div class="navbar__content">
                    <img src="imageness/logo.jpg" alt="hp">
                    <ul>
                        <li><a href="http://almacen.test/">EXCLUSIVOS</a></li>
                        <li><a href="/">NUEVOS</a></li>
                        <li><a href="/">GIFT CARDS</a></li>
                        <li><a href="/">FIND A STORE</a></li>
                    </ul>
                </div>
            </nav>

            <br>
            <section class="main-content">
                <p> Diseña tu</p>
                <p> Pedido</p>
                <p> Tenemos opciones variadas de remeras de alta calidad y variedad de precios. De Remeras de Algodon, poliester, lino, spandes, modal, entre muchos otros.</p>
                <p> Precios accesibles los cuales van dependiendo de la cantidad que compres, mientras mas compres, mas descuentos se te suman  </p>
            </section>
            <section class="side-content">
                <div></div>
                <p>Tendencias</p>
                <img src="imageness/remeras-png-3-removebg-preview-removebg-preview.png" alt="remera negra">
            </section>

            <section class="footer">
                <div id="button1">
                    <img src="imageness/remeras-png-3-removebg-preview-removebg-preview.png" alt="remra larga">
                </div>
                <div id="button2">
                    <img src="imageness/remeras-png-2-removebg-preview.png" alt="remra blanca">
                </div>
                <div id="button3">
                    <img src="imageness/OIP__4_-removebg-preview.png" alt="Remera negra">
                </div>
            </section>

            <section class="loader">
                <div></div>
                <div></div>
                <div></div>
            </section>

            <section class="modal hidden">
                <div class="modal__content">
                    <img src="./imageness/icons8-close-64.png" class="modal__content--close">
                    <div class="modal__content--slider">
                        <div class="cards" id="cards-container">
                            <!-- Vienen del js -->
                        </div>
                    </div>
                </div>
            </section>

        </div>
    </body>
    <script src="script.js"></script>
</html>
