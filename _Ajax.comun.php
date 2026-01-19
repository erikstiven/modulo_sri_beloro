<?php
/* ARCHIVO COMUN PARA LA EJECUCION DEL SERVIDOR AJAX DEL MODULO */
/***************************************************/
/* NO MODIFICAR */
include_once('../../Include/config.inc.php');
include_once(path(DIR_INCLUDE).'conexiones/db_conexion.php');
include_once(path(DIR_INCLUDE).'comun.lib.php');
include_once(path(DIR_INCLUDE).'Clases/Formulario/Formulario.class.php');
require_once (path(DIR_INCLUDE).'Clases/xajax/xajax_core/xajax.inc.php');
/***************************************************/
/* INSTANCIA DEL SERVIDOR AJAX DEL MODULO*/
$xajax = new xajax('_Ajax.server.php');
$xajax->setCharEncoding('SISTEMA_CHARSET');
/***************************************************/
//	FUNCIONES PUBLICAS DEL SERVIDOR AJAX DEL MODULO 
//	Aqui registrar todas las funciones publicas del servidor ajax
//	Ejemplo,
//	$xajax->registerFunction("Nombre de la Funcion");
/***************************************************/
//FUNCIONES CABECERA NOTA DE CREDITO
$xajax->registerFunction("genera_formulario");

$xajax->registerFunction("consultar_factVent");
$xajax->registerFunction("consultar_notaDebi");
$xajax->registerFunction("consultar_notaCred");
$xajax->registerFunction("consultar_guiaRemi");
$xajax->registerFunction("consultar_reteGast");
$xajax->registerFunction("consultar_reteInve");
$xajax->registerFunction("consultar_factExpor");
$xajax->registerFunction("consultar_factFlor");
$xajax->registerFunction("consultar_factFlorExpor");
$xajax->registerFunction("consultar_guiaRemiFlor");
$xajax->registerFunction("consultar_liquidacionCompra");
//DOCUMENTOS
$xajax->registerFunction("genera_formulario_documentos");
$xajax->registerFunction("update_comprobante");

// SRI
$xajax->registerFunction("firmar");
$xajax->registerFunction("validaAutoriza");
$xajax->registerFunction("autorizaComprobante");

// FIRMAR DOCUMENTOS
$xajax->registerFunction("firmar_factVent");
$xajax->registerFunction("firmar_notaCred");
$xajax->registerFunction("firmar_notaDebi");
$xajax->registerFunction("firmar_guiaRemi");
$xajax->registerFunction("firmar_reteGast");
$xajax->registerFunction("firmar_reteInve");
$xajax->registerFunction("firmar_factExpor");
$xajax->registerFunction("firmar_factFlor");
$xajax->registerFunction("firmar_factFlorExpor");
$xajax->registerFunction("firmar_guiaRemiFlor");
$xajax->registerFunction("firmar_liquidacionCompra");


// AUTORIZASCION SRI
$xajax->registerFunction("enviar_factVent");
$xajax->registerFunction("enviar_notaCred");
$xajax->registerFunction("enviar_notaDebi");
$xajax->registerFunction("enviar_guiaRemi");
$xajax->registerFunction("enviar_reteGast");
$xajax->registerFunction("enviar_reteInve");
$xajax->registerFunction("enviar_factExpor");
$xajax->registerFunction("enviar_factFlor");
$xajax->registerFunction("enviar_factFlorExpor");
$xajax->registerFunction("enviar_guiaRemiFlor");
$xajax->registerFunction("enviar_liquCompras");

// FUNCIONES SRI
$xajax->registerFunction("updateError");
$xajax->registerFunction("update_comprobante");
$xajax->registerFunction("genera_reporte");
$xajax->registerFunction("genera_documento");

$xajax->registerFunction("enviar_mail");
$xajax->registerFunction("enviaEmail");
$xajax->registerFunction("genera_busqueda_cliente");
$xajax->registerFunction("editar_contactos");
$xajax->registerFunction("guardar_editar_contactos");

/***************************************************/
?>