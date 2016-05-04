--------------------------------------------
Ejemplo de sistema de validacion con CAPTCHA
--------------------------------------------

En algunas aplicaciones, sobre todo si están colgadas en Internet, es
recomendable utilizar sistemas que eviten la obtención de las credenciales
mediante ataques por fuerza bruta. Para estos casos, interesa incorporar al
sistema de validación un captcha.

Para probar este sistema debes incluir el contenido de esta carpeta en el 
directorio include/validacion de tu aplicación a excepción del fichero 
login.php que debe ubicarse en el raiz.

En este ejemplo se han tomado precauciones para evitar la obtención del texto 
del captcha a través de la url. En todo caso, se trata de un ejemplo y debe 
ser escrupuloso a la hora de seguir las recomendaciones de seguridad.
