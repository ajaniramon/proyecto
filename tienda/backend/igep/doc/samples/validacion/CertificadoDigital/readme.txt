-------------------------------------
Validacion con Certificados Digitales
-------------------------------------

Ejemplo de configuración para Apache
Se supone que la aplicación es "APLIC"
-------------------------------------

	LoadModule ssl_module modules/mod_ssl.so
	Listen 443
	SSLVerifyClient none
	SSLPassPhraseDialog  builtin
	SSLSessionCache         shmcb:/var/cache/mod_ssl/scache(512000)
	SSLSessionCacheTimeout  300
	SSLMutex default
	SSLRandomSeed startup file:/dev/urandom  256
	SSLRandomSeed connect builtin
	SSLCryptoDevice builtin
	
	<VirtualHost _default_:443>
	ServerName APLIC.domain.es:443
	ErrorLog logs/ssl_error_log
	TransferLog logs/ssl_access_log
	LogLevel warn
	SSLEngine on
	
	SSLProtocol all -SSLv2
	SSLCipherSuite ALL:!ADH:!EXPORT:!SSLv2:RC4+RSA:+HIGH:+MEDIUM:+LOW
	SSLCertificateFile /opt/certificados/apachessl.crt
	SSLCertificateKeyFile /opt/certificados/apachessl.key
	
	<Files ~ "\.(cgi|shtml|phtml|php3?)$">
	    SSLOptions +StdEnvVars
	</Files>
	<Directory "/var/www/cgi-bin">
	    SSLOptions +StdEnvVars
	</Directory>
	
	SetEnvIf User-Agent ".*MSIE.*" \
	         nokeepalive ssl-unclean-shutdown \
	         downgrade-1.0 force-response-1.0
	
	CustomLog logs/ssl_request_log \
	          "%t %h %{SSL_PROTOCOL}x %{SSL_CIPHER}x \"%r\" %b" 
	
	#Para que funcione Internet Explorer 6x
	SSLInsecureRenegotiation on
	
	DocumentRoot /rootAplicaciones/APLIC
	<Directory "/rootAplicaciones/APLIC">
	        AllowOverride All
	        Order Deny,Allow
	        Allow from all
	
	        #Certificado
	        SSLCACertificateFile "/rootAplicaciones/APLIC/include/valida/rootca/accv-dnie.pem" 
	        #SSLVerifyClient require
	        SSLVerifyClient optional
	        SSLVerifyDepth 5
	        SSLOptions +StdEnvVars +ExportCertData +OptRenegotiate
	
	        #Para listados PDF
	        SetEnv JAVA_HOME /usr/lib/jvm/jre-1.7.0-openjdk.x86_64
	        #Para acceso Oracle
	        SetEnv ORACLE_BASE /usr/lib/oracle/11.2/client64/lib
	        SetEnv ORACLE_HOME /usr/lib/oracle/11.2/client64/lib
	        SetEnv TNS_ADMIN=/usr/lib/oracle/11.2/client64/lib
	        SetEnv NLS_LANG=SPANISH_SPAIN.WE8ISO8859P15
	
	        #Deshabilitar listado de directorios
	        Options -Indexes
	        <IfModule php5_module>
	                #Impedir ficheros php con tag corto
	                php_admin_flag short_open_tag off
	                #Evitar mostrar errores en el navegador
	                #php_admin_flag display_errors off
	                #Evitar creacion y acceso a variables globales
	                php_admin_value register_globals off
	                #Evitar cargar ficheros desde urls
	                php_admin_flag allow_url_fopen off
	                #Deshabilitar ejecución de comandos de sistema
	                php_admin_value disable_functions "system, shell_exec, passthru, popen, proc_open" 
	                #Nivel de log de errores en error_log
	                #Produccion: (php.ini)error_reporting = E_COMPILE_ERROR|E_RECOVERABLE_ERROR|E_ERROR|E_CORE_ERROR
	                php_admin_value error_reporting 4177
	        </IfModule>
	
	        #Evitar servir ficheros de configuración
	        <FilesMatch \.xml$>
	                Order allow,deny
	                Deny from all
	        </FilesMatch>
	  </Directory>
	</VirtualHost>




Ubicación ficheros:
-------------------------------------
	"login.php":
		Se ubica en el directorio raíz de la aplicación.
		Realiza la carga del resto del módulo y define las funciones de validación.
	"include/valida/rootca":
		En este directorio se ubican los certificados Root CA de la ACCV y de el DNIe 
		concatenados y en formato PEM, para poder negociar. 
	"include/valida/AuthCD.php":
		Clase Auth para trabajar con certificados
	"include/valida/gvhBaseAuthCert.php"
	"include/valida/UserCert.php"
	"include/valida/validacion_cd_base.php"
	"include/valida/validacion_cd.php"


	
	
