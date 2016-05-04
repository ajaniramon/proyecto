CREATE SCHEMA IF NOT EXISTS SHOP;
USE SHOP;
SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
USE `shop`;
DROP TABLE IF EXISTS `linea_pedido`, `pedido`, `cliente`, `articulo`,`categoria` ;

CREATE TABLE IF NOT EXISTS `categoria` (
  `idcategoria` int(3) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(40) DEFAULT NULL,
  PRIMARY KEY (`idcategoria`)
);

INSERT INTO `categoria` (`idcategoria`, `nombre`) VALUES
(1, 'Panaderia y reposteria'),
(2, 'Pastas y arroces'),
(3, 'Legumbres'),
(4, 'Entrantes'),
(5, 'Sopas y cremas'),
(6, 'Ensaladas'),
(7, 'Bebidas'),
(8, 'Setas, Champinones y tofu');

CREATE TABLE IF NOT EXISTS `articulo` (
  `idarticulo` int(4) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(40) DEFAULT NULL,
  `descripcion` text,
  `precio` double(7,2) DEFAULT NULL,
  `imagen` varchar(40) DEFAULT NULL,
  `stock` int(3),
  `categoria` int(4) DEFAULT NULL,
  INDEX `fk_categoria` (`categoria`),
  CONSTRAINT `fk_categoria` FOREIGN KEY (`categoria`) REFERENCES `categoria`(`idcategoria`),
  PRIMARY KEY (`idarticulo`)
);

INSERT INTO `articulo` (`idarticulo`, `nombre`, `descripcion`, `precio`, `imagen`, `stock`, `categoria`) VALUES
(1, 'Hot cakes esponjosos', 'Gruesos y esponjosos. Estos hot cakes salen simplemente deliciosos. Cubiertos con fresas y crema batida son imposibles de resistir. También son deliciosos con mantequilla, miel de maple y rebanadas de plátano.', 3.00, 'articulos/hot-cakes.jpg', 10, 1),
(2, 'Pan brasileño de queso', 'Prueba esta receta tradicional brasileña de panecillos de queso parmesano con harina de mandioca.¡Libres de gluten!.', 4.00, 'articulos/pan-brasilenyo.jpg', 10, 1),
(3, 'Espagueti al chipotle', 'Una receta de espagueti a la mexicana. La pasta se baña en una salsa de crema con chipotle y champiñones. ', 20.00, 'articulos/espagueti-chipotle.jpg', 10, 2),
(4, 'Arroz blanco con elote y crema', 'Arroz blanco con granos de elote salteados en mantequilla y crema ácida. Una receta fácil y de un sabor muy especial.', 10.00, 'articulos/arroz-elote.jpg', 10, 2),
(5, 'Lasagna vegetariana', 'Lasaña rellena de una salsa de tomate con champiñones, pimiento morón, zanahoria y otras hierbas y especias, en lugar de carne.', 10.00, 'articulos/lasagna-vegetariana.jpg', 10, 2),
(6, 'Hamburguesas de lentejas y arroz', 'Por su base de lentejas, estas hamburguesas tienen un bajo contenido en grasas y un alto contenido en proteínas.', 5.00, 'articulos/hamburguesa-lentejas.jpg', 10, 3),
(7, 'Garbanzos a las especias', 'Los garbanzos son una legumbre que se puede comer en multitud de recetas, por ejemplo en esta receta de la India que resulta, como todas las de este país, muy aromática y muy sabrosa.', 3.00, 'articulos/garbanzos_a_las_especias.jpg', 10, 3),
(8, 'Hummus', 'El hummus es una de las recetas m&aacute;s populares del Medio Oriente. Se sirve con pan de pita fresco o tostado, y se compone basicamente de pué de garbanzos y zumo de limón, se suele servir como aperitivo y/o acompañamiento.', 3.00, 'articulos/hummus.jpg', 10, 4),
(9, 'Cogollos a la cordobesa', 'Muchas veces nos complicamos la vida con platos laboriosos y no pensamos que en la sencillez, está el buen gusto. Los cogollos de Tudela con crujiente de ajo frito es un plato versátil ya que puedes presentarlo a modo de entrante.', 3.00, 'articulos/cogollos.jpg', 10, 4),
(10, 'Croquetas de seitán y bechamel', 'Hoy traigo una deliciosa razón para que te chupes los dedos: en la oficina, en la universidad o en el coche pero especialmente te invito a que las pruebes en tu casa, tranquilamente y con consciencia.', 3.00, 'articulos/croquetas-seitan.png', 10, 4),
(11, 'Crema de boniato a la naranja', 'Dulce, aterciopelada y aromática, esta crema la hice para mi padre en una de sus paradas en mi casa rumbo a Francia. Era diciembre, quería un plato que le calentase ( le puse jengibre para activar la circulación), nutritivo y a la vez consistente.', 3.00, 'articulos/crema-boniato.jpg', 10, 5),
(12, 'Gazpacho andaluz', 'Receta del clásico gazpacho andaluz. Un primer plato vegano tradicional perfecto para los meses de calor. También ideal como bebida refrescante de verano.', 3.00, 'articulos/gazpacho.jpg', 10, 5),
(13, 'Crema de ésparragos', 'Una sopa de espárragos hecha con yogurt, limón y queso parmesano. ¡A todo el mundo le encanta!. Puedes sustituir la crema y el yogur con productos de soya si quieres hacer una crema vegana.', 3.00, 'articulos/crema-esparragos.jpg', 10, 5),
(14, 'Ensalada griega', 'Tomates, cebolla y pepino, aderezados con aceite de oliva y cubiertos con queso feta y aceitunas negras. Esta ensalada griega es, además de deliciosa, saludable y nutritiva.', 3.00, 'articulos/ensalada-griega.jpg', 10, 6),
(15, 'Ensalada caprese', 'Rebanadas de tomate, queso mozzarella fresco, olivas y albahaca bañados en aceite de oliva con un toque de sal y pimienta.', 3.00, 'articulos/ensalada-caprese.jpg', 10, 6),
(16, 'Zumo de manzana, pepino y col', 'Zumo de manzana, fruta depurativa por excelencia, combinada con la col rizada o col kale, de excelentes propiedades gracias a los glucosinolatos de las crucíferas.', 3.00, 'articulos/zumo-pepino.jpg', 10, 7),
(17, 'Smoothie de sandia y kiwi', 'Si quieres probar una receta de smoothie refrescante para este verano, no puedes perderte este smoothie de sandía que tenemos preparado hoy para que lo puedas hacer rápido en casa. Además es una forma perfecta de usar las sobras de sandía para que no se ponga mala.', 3.00, 'articulos/smoothie-sandia.jpg', 10, 7),
(18, 'Champiñones en salsa', 'Una de las recetas más ricas y completas que os mostramos es esta con la que preparar unos riquísimos champiñones en salsa. Además es una receta sencilla de hacer y que siempre quedan bien.', 3.00, 'articulos/champinyones.jpg', 10, 8),
(19, 'Crepes de setas y berenjena', '¿Estás buscando una nueva opción para comer verduras?,¿quieres recetas más saludables para evitar la comida chatarra o preparada? Entonces mira esta receta para preparar unos deliciosos crepes de setas con berenjena.', 3.00, 'articulos/crepes-setas.jpg', 10, 8);

CREATE TABLE `cliente` (
  `idcliente` int(4) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(40) NOT NULL,
  `apellido` varchar(40) NOT NULL,
  `dni` varchar(9) NOT NULL,
  `direccion` varchar(40) NOT NULL,
  `telefono` varchar(40) DEFAULT NULL,
  `correo` varchar(40) NOT NULL,
  `contrasenya` varchar(40) NOT NULL,
  `empleado` varchar(50) DEFAULT 'false',
  `enabled` int(11) DEFAULT '0',
  `token` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`idcliente`),
  UNIQUE KEY `dni` (`dni`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8;



INSERT INTO `cliente` (`idcliente`, `nombre`, `apellido`, `dni`, `direccion`, `telefono`, `correo`, `contrasenya`, `empleado`) VALUES (null, 'asd', 'asd', '88159546V', 'asd', 0, 'asd@domain.com', '7815696ecbf1c96e6894b779456d330e', 'false');
INSERT INTO `cliente` (`idcliente`, `nombre`, `apellido`, `dni`, `direccion`, `telefono`, `correo`, `contrasenya`, `empleado`) VALUES (null, 'Francisco', 'de Pádua', '52671236V', 'Calle me da igual', 600303356, 'cliente@domain.com', '4983a0ab83ed86e0e7213c8783940193', 'false');
INSERT INTO `cliente` (`idcliente`, `nombre`, `apellido`, `dni`, `direccion`, `telefono`, `correo`, `contrasenya`, `empleado`) VALUES (null, 'Juan María', 'Luque', '98615907A', 'Calle Barón de Cárcer 13-11', 666807393, 'juanma@domain.com', '65a368f66ad6b9ee45263577713d8a95', 'false');
INSERT INTO `cliente` (`idcliente`, `nombre`, `apellido`, `dni`, `direccion`, `telefono`, `correo`, `contrasenya`, `empleado`) VALUES (null, 'Marshall', 'Slim Shady', '55456242E', 'Detroit D12', 0, 'emi@domain.com', '26b637ed41273425be243e8d42e5b461', 'false');
INSERT INTO `cliente` (`idcliente`, `nombre`, `apellido`, `dni`, `direccion`, `telefono`, `correo`, `contrasenya`, `empleado`) VALUES (null, 'pepe', 'de sanchez', '03017416T', 'agh', 0, 'pepe@domain.es', '926e27eecdbc7a18858b3798ba99bddd', 'false');
INSERT INTO `cliente` (`idcliente`, `nombre`, `apellido`, `dni`, `direccion`, `telefono`, `correo`, `contrasenya`, `empleado`) VALUES (null, 'Ramón', 'Martínez Tarín', '48689633J', 'Calle Azorín 15-1', 625275130, 'ra1996@gmail.com', '938158c8de29463e27216fc7aa2abfca', 'true');
INSERT INTO `cliente` (`idcliente`, `nombre`, `apellido`, `dni`, `direccion`, `telefono`, `correo`, `contrasenya`, `empleado`) VALUES (null, 'slim', 'shady', '44115531J', 'lol', 625275130, 'slim@domain.com', '7815696ecbf1c96e6894b779456d330e', 'false');
INSERT INTO `cliente` (`idcliente`, `nombre`, `apellido`, `dni`, `direccion`, `telefono`, `correo`, `contrasenya`, `empleado`) VALUES (null, 'testr', 'test', '22549402H', 'test', 4444, 'test@domain.com', '098f6bcd4621d373cade4e832627b4f6', 'false');

CREATE TABLE IF NOT EXISTS `pedido` (
  `idpedido` int(4) NOT NULL AUTO_INCREMENT,
  `fecha` datetime DEFAULT NULL,
  `total` double(7,2) NOT NULL,
  `dni` varchar(9) NOT NULL,
  INDEX `fk_dni` (`dni`),
  CONSTRAINT `fk_dni` FOREIGN KEY (`dni`) REFERENCES `cliente`(`dni`),
  PRIMARY KEY (`idpedido`)
);

CREATE TABLE IF NOT EXISTS `linea_pedido` (
  `idpedido` int(4) NOT NULL,
  `idarticulo` int(4) NOT NULL,
  `unidad` int(4) NOT NULL,
  `precio` double(7,2) NOT NULL,
  `precioTotal` double(7,2) NOT NULL,
  INDEX `fk_idPedido` (`idPedido`),
  CONSTRAINT `fk_idPedido` FOREIGN KEY (`idpedido`) REFERENCES `pedido`(`idpedido`),
  INDEX `fk_idArticulo` (`idArticulo`),
  CONSTRAINT `idarticulo` FOREIGN KEY (`idarticulo`) REFERENCES `articulo`(`idarticulo`),
  PRIMARY KEY(`idpedido`, `idarticulo`)
);

-- NUEVAS TABLAS --
CREATE TABLE `shop`.`empleado` (
  `idempleado` INT NOT NULL AUTO_INCREMENT,
  `email` VARCHAR(80) NULL,
  `contrasena` VARCHAR(45) NULL,
  `nombre` VARCHAR(40) NULL,
  `apellido` VARCHAR(40) NULL,
  `dni` VARCHAR(9) NULL,
  `direccion` VARCHAR(45) NULL,
  
  PRIMARY KEY (`idempleado`),
  UNIQUE INDEX `dni_UNIQUE` (`dni` ASC),
  UNIQUE INDEX `email_UNIQUE` (`email` ASC));


/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
