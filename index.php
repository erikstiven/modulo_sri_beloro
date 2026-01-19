<?php

/**
 * @author www.Mantareys.com
 * @copyright 2016
 */

// Cargamos configuracion y librerias
include("config.php");

/* FIRMAMOS EL XML */

// Creamos el objeto de Firma Electronica
$firma = new FirmaElectronica("galo_fernando_cevallos_duran.p12", "CEga2015");

// Indicamos los nombres de los XML a firmar y los nombres de salida
$nombreXMLfirmado = "0205201701099144986800120010010000371121234567817.xml";
$nombreXMLsalida = "0205201701099144986800120010010000371121234567817.xml";

// Firmamos el XML y capturamos el resultado | RETORNA true|false segun sea el caso
$resultado = $firma->FirmarXML($nombreXMLfirmado, $nombreXMLsalida);
echo 'hola '.$resultado;
if($resultado){
    echo "Documento firmado con exito!";
} else {
    echo "El documento no pudo ser firmado. El certificado y clave son validos? Reviso los nombres de archivos y rutas?";
}

// Mostramos resultados
if(_DEBUG_){
    echo "<br /><br /><strong>FIRMAMOS EL XML</strong><br />";
    echo "XML Generado: <a href='". _URL_ . "documentoselectronicos/firmados/" . $nombreXMLfirmado ."' target='_blank'>".$nombreXMLfirmado."</a><br />";
    if($resultado){
        echo '<div id="firma" style="width:80%; height:200px; margin:20px; overflow: scroll;">';
        echo "<strong>DATOS DE LA FIRMA</strong><br />";
        $datosCertificado = $firma->LeerCertificado();
        echo "<pre>";
        print_r($datosCertificado);
        echo "</pre>";
        echo '</div>';
    } else {
        echo "ERROR: No se pudo firmar el XML :(<br /><br />";
    }
}