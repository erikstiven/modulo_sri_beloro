<?php

/**
 * @author www.Mantareys.com
 * @copyright 2016
 */

# Configuramos nuestro script

    // Configuramos la rutas del script
	define('_PATH_', dirname(__FILE__)."/");
    // Configuramos la ruta a las clases
	define('_PATH_CLASSES_', _PATH_."classes/");
	// Configuramos el directorio raiz del script
	define('_HOME_', "/sri_offline/");
    // Activamos o desactivamos la depuracion
    define('_DEBUG_', TRUE); // TRUE|FALSE
    // Definimos uso horario
    date_default_timezone_set(ZONA_HORARIA);
    
    # Configuramos las rutas de los documentos electronicos
    
    // Directorio donde se encuentren los documentos para ser firmados.
	define('_PATH_XML_GENERADOS_', _PATH_."documentoselectronicos/generados/");
    // Directorio donde se guardar�n los documentos firmados electr�nicamente de manera satisfactoria.
	define('_PATH_XML_FIRMADOS_', _PATH_."documentoselectronicos/firmados/");
    // Directorio donde almacenar�n los comprobantes firmados electr�nicamente remitidos a la administraci�n tributaria y no se ha recibido una respuesta.
	define('_PATH_XML_FIRMADOS_RECHAZADOS_', _PATH_."documentoselectronicos/firmados/rechazados/");
    // Directorio donde no cumple esquemas o sin autorizaci�n de emisi�n.
    define('_PATH_XML_FIRMADOS_SIN_AUTORIZACION_', _PATH_."documentoselectronicos/firmados/sin_autorizacion/");
    // Directorio donde se almacenar�n los comprobantes autorizados por el SRI y autom�ticamente deber�n eliminarse de los directorios 1 y/o 2 �nicamente si son autorizados.
	define('_PATH_XML_AUTORIZADOS_', _PATH_."documentoselectronicos/autorizados/");
    // Directorio donde se almacenar�n los archivos con los motivos de por qu� no se autoriz� los comprobantes.
	define('_PATH_XML_NO_AUTORIZADOS_', _PATH_."documentoselectronicos/no_autorizados/");
    
    # Configuramos la ruta a los certificados
    
    define('_PATH_CERTIFICADOS_', _PATH_."firmaselectronicas/");
    
# Cargamos las clases
    
    # Clase para firmar documentos XML
    include(_PATH_CLASSES_."FirmaElectronica.php");