<?php
	
	include_once('../../Include/config.inc.php');
	include_once(path(DIR_INCLUDE).'conexiones/db_conexion.php');
	include_once(path(DIR_INCLUDE).'comun.lib.php');

	if (session_status() !== PHP_SESSION_ACTIVE) {session_start();}
    global $DSN_Ifx, $DSN;

	$oCon = new Dbo;
    $oCon->DSN = $DSN;
    $oCon->Conectar();
	
    $tabla = '';

    $sql = "select id, codigo, error, solucion
			from comercial.sri_errores
			order by id";
    if($oCon->Query($sql)){
    	if($oCon->NumFilas() > 0){
    		do{
				$id = $oCon->f('id');
    			$codigo = $oCon->f('codigo');
                $error = $oCon->f('error');
				$solucion = $oCon->f('solucion');
				
    			$tabla.='{
					"codigo":"'.$codigo.'",
					"error":"'.$error.'",
					"solucion":"'.$solucion.'"
				},';

			}while($oCon->SiguienteRegistro());
    	}
	}
	$oCon->Free();
		
	//eliminamos la coma que sobra
	$tabla = substr($tabla,0, strlen($tabla) - 1);
	
	echo '{"data":['.$tabla.']}';
	
	
	
	
?>