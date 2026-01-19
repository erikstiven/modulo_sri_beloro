<?php

include_once('../../Include/config.inc.php');
include_once(path(DIR_INCLUDE).'conexiones/db_conexion.php');
include_once(path(DIR_INCLUDE).'comun.lib.php');

global $DSN_Ifx, $DSN;

session_start();

$oCon = new Dbo;
$oCon->DSN = $DSN;
$oCon->Conectar();

$oIfx = new Dbo;
$oIfx->DSN = $DSN_Ifx;
$oIfx->Conectar();

$datos              = $_POST;
$array_fact_json    = base64_decode($datos["documento"]);
$val                = json_decode($array_fact_json);
$id_factura         = $val[0];
$clave_acceso_resp 	= $val[21];

try {
    
    $oIfx->QueryT('BEGIN;');

	//VARIABLES GLOBALES
	$id_empresa = $_SESSION['U_EMPRESA'];
    $id_usuario = $_SESSION['U_ID'];

    session_write_close(); //ESTA FUNCION INPIDE QUE SE BLOQUEEN LOS DEMAS PROCESOS PHP

	//datos de la empresa
	$sql = "SELECT empr_nom_empr, empr_ruc_empr, empr_tip_firma,empr_ac2_empr,
			empr_dir_empr, empr_conta_sn, empr_num_resu, empr_rimp_sn, empr_det_fac
			from saeempr 
			where empr_cod_empr = $id_empresa ";
	if ($oIfx->Query($sql)) {
		if ($oIfx->NumFilas() > 0) {
			$nombre_empr = trim($oIfx->f('empr_nom_empr'));
			$ruc_empr = $oIfx->f('empr_ruc_empr');
			$dir_empr = trim($oIfx->f('empr_dir_empr'));
			$conta_sn = $oIfx->f('empr_conta_sn');
			$empr_num_resu = $oIfx->f('empr_num_resu');
			$empr_tip_firma = $oIfx->f('empr_tip_firma');
			$empr_rimp_sn = $oIfx->f('empr_rimp_sn');
			$empr_det_fac = $oIfx->f('empr_det_fac');
			$empr_ac2_empr = trim($oIfx->f('empr_ac2_empr'));
		}
	}
	$oIfx->Free();

	//direccion sucursales
	$sql = "SELECT sucu_cod_sucu, sucu_dir_sucu from saesucu where sucu_cod_empr = $id_empresa";
	if ($oIfx->Query($sql)) {
		if ($oIfx->NumFilas() > 0) {
			unset($arrayDireSucu);
			do {
				$arrayDireSucu[$oIfx->f('sucu_cod_sucu')] = $oIfx->f('sucu_dir_sucu');
			} while ($oIfx->SiguienteRegistro());
		}
	}
	$oIfx->Free();

	if ($conta_sn == 'S') {
		$conta_sn = 'SI';
	} else {
		$conta_sn = 'NO';
	}

    
    $xml        = '';
    $xmlDeta    = '';
    $descuento  = 0;

    if ($id_factura > 0) {

        //COMSULTAMOS LOS DATOS Y FORMAMOS LOS XML
        $num8 				            = 12345678;
        $fact_nom_cliente 	            = $val[3];
        $fact_tlf_cliente 	            = $val[9];
        $fact_dir_clie 		            = trim($val[10]);
        $fact_ruc_clie 		            = $val[2];
        $fact_fech_fact 	            = $val[11];
        $fact_num_preimp 	            = $val[1];
        $fact_nse_fact 		            = $val[12];
        $fact_cod_clpv 		            = $val[13];
        $fact_con_miva 		            = $val[5];
        $fact_sin_miva 		            = $val[6];
        $fact_iva 			            = $val[4];
        $fact_ice 			            = $val[14];
        $fact_val_irbp 		            = $val[15];
        $fact_email_clpv 	            = $val[8];
        $tipoIdentificacionComprador    = $val[16];
        $cod_almacen 		            = trim($val[17]);
        $orden_compra 		            = $val[18];
        $id_sucursal 		            = $val[19];
        $fact_cm1_fact 		            = trim($val[20]);
        $clave_acceso 		            = $val[21];
        $totalDescuento 	            = 0;
        $baseImponibleIce 	            = 0;
        $baseImponibleIceTotal          = 0;

        $sql = "";

        //genera clave de acceso
        $ambiente = ambienteEmisionSri($oIfx, $id_empresa, $id_sucursal, 1);
        $tip_emis = ambienteEmisionSri($oIfx, $id_empresa, $id_sucursal, 2);

        //Validacion por tipo de documento ficha proveedor/cliente SUEJO4967
        $sql = "SELECT clv_con_clpv from saeclpv where clpv_ruc_clpv='$fact_ruc_clie'";
        $tipo_documento = consulta_string($sql, 'clv_con_clpv', $oIfx, '');

        //VALIDAICON CONVERSION DE UNBIDADES
        $sqlfa="SELECT para_conv_sn from saepara where para_cod_empr= $id_empresa and para_cod_sucu = $id_sucursal";
        $para_conv_sn=consulta_string($sqlfa,'para_conv_sn',$oIfx,'');

        if (!empty($tipo_documento)) {

            if ($tipo_documento == '01') {
                $tipoIdentificacionComprador = '04'; //ruc
            }
            if ($tipo_documento == '02') {
                $tipoIdentificacionComprador = '05'; //cedula
            }
            if ($tipo_documento == '03') {
                $tipoIdentificacionComprador = '06'; //pasaporte
            }
        } else {		//FIN DE VALIDACION TIPO DOCUMENTO SUEJO4967		

            //TIPO DOCUMENTO DEPENDE DE CANTIDAD DE CARACTERES
            $clv_con_clpv = strlen($fact_ruc_clie);
           
            if ($clv_con_clpv == 13)
                $tipoIdentificacionComprador = '04'; //ruc XML
            else if ($clv_con_clpv == 10)
                $tipoIdentificacionComprador = '05'; //cedula
            else
                $tipoIdentificacionComprador = '06'; //pasaporte
        }

        $fact_tot_fact      = $fact_con_miva + $fact_sin_miva + $fact_iva + $fact_val_irbp;
        $totalSinImpuestos  = $fact_tot_fact - $fact_ice - $fact_iva;
        $importeTotal 		= round($totalSinImpuestos + $fact_iva + $fact_ice + $fact_val_irbp, 2);

        $estable    = substr($fact_nse_fact, 0, 3);
        $serie      = substr($fact_nse_fact, 3, 6);

        $fec_fact = date("d/m/Y", strtotime($fact_fech_fact));

        $sqlDetalle = "SELECT *
                     from saedfac where 
                        dfac_cod_fact = $id_factura and 
                        dfac_cod_sucu = $id_sucursal and 
                        dfac_cod_empr = $id_empresa ";
        $baseImponibleIRBP 	= 0;
        $tot_nobiva 		= 0;
        $tot_exeiva 		= 0;
        if ($oIfx->Query($sqlDetalle)) {
            $xmlDeta .= '<detalles>';
            $bandera = 2;

            do {
                $dfac_cod_prod      = $oIfx->f("dfac_cod_prod");
                $dfac_cant_dfac     = $oIfx->f("dfac_cant_dfac");
                $dfac_precio_dfac   = $oIfx->f("dfac_precio_dfac");
                $dfac_mont_total    = $oIfx->f("dfac_mont_total");
                $dfac_mont_total_i  = $oIfx->f("dfac_mont_total");
                $dfac_por_iva       = $oIfx->f("dfac_por_iva");
                $dfac_por_irbp      = $oIfx->f("dfac_por_irbp");
                $dfac_des1          = $oIfx->f("dfac_des1_dfac");
                $dfac_des2          = $oIfx->f("dfac_des2_dfac");
                $dfac_des3          = $oIfx->f("dfac_des3_dfac");
                $dfac_des4          = $oIfx->f("dfac_des4_dfac");
                $dfac_des5          = $oIfx->f("dfac_por_dsg");
                $dfac_por_ice       = $oIfx->f("dfac_por_ice");
                $dfac_det_dfac      = trim($oIfx->f("dfac_det_dfac"));
                $dfac_cant_conv     = $oIfx->f('dfac_cant_conv');

                $descuento = 0;
                $desctoItem = 0;
                if ($dfac_des1 != 0 || $dfac_des2 != 0 || $dfac_des3 != 0 || $dfac_des4 != 0 || $dfac_des5 != 0) {
                    $descuento = ($dfac_cant_dfac * $dfac_precio_dfac) - $dfac_mont_total;
                    $desctoItem = ($dfac_cant_dfac * $dfac_precio_dfac) - $dfac_mont_total;
                    if ($descuento != 0) {
                        $totalDescuento += $descuento;
                    }
                } // fin if

                $descuento = number_format($descuento, 2, '.', '');

                //PRODUCTO
                $sqlDescripcionProd = "SELECT prod_nom_prod, prod_cod_barra, prod_sn_noi, prod_sn_exe, prod_apli_prod from saeprod where 
                                            prod_cod_prod = '$dfac_cod_prod' and 
                                            prod_cod_empr = $id_empresa and 
                                            prod_cod_sucu = $id_sucursal ";
                if ($oCon->Query($sqlDescripcionProd)) {
                    if ($oCon->NumFilas() > 0) {
                        $prod_nom_prod 		= trim($oCon->f('prod_nom_prod'));
                        $prod_cod_barra 	= $oCon->f('prod_cod_barra');
                        $prod_sn_noi 		= $oCon->f('prod_sn_noi');
                        $prod_sn_exe 		= $oCon->f('prod_sn_exe');
                        $prod_apli_prod 	= trim($oCon->f('prod_apli_prod'));
                    }
                }
                $oCon->Free();

                //CAMPO APLICACION - ACTUALMENTE SOLO ES UTILIZADO POR AL EMPRESA LUBAMAQUI
                if(!empty($prod_apli_prod)){
                    $prod_apli_prod =strip_tags($prod_apli_prod );
                    if($prod_nom_prod==$dfac_det_dfac){
                        $dfac_det_dfac=substr($prod_apli_prod,0,300);
                    }
                    else{
                        $dfac_det_dfac= $dfac_det_dfac.' '.$prod_apli_prod;
                        $dfac_det_dfac=substr($dfac_det_dfac,0,300);
                    }
                    
                }

                $sqlfa = "SELECT para_dfa_para from saepara where para_cod_empr= $id_empresa and para_cod_sucu=$id_sucursal";
                $para_dfa_para = consulta_string($sqlfa, 'para_dfa_para', $oCon, '');

                if ($para_dfa_para == 1) {
                    if (!empty($dfac_det_dfac)) {
                        $prod_nom_prod = $dfac_det_dfac;
                    }
                }

                if (empty($prod_cod_barra)) {
                    $prod_cod_barra = $dfac_cod_prod;
                }
                    //VALIDAICON CONVERSION DE UNIDADES
                if($para_conv_sn=='S'){
                    if(round($dfac_cant_conv,2)>0){
                        $dfac_cant_dfac= $dfac_cant_conv;
                        $dfac_precio_dfac=$dfac_mont_total/$dfac_cant_dfac;
                    }  
                }

                $xmlDeta .= '<detalle>';
                $xmlDeta .= "<codigoPrincipal>$dfac_cod_prod</codigoPrincipal>";
                $xmlDeta .= "<codigoAuxiliar>$prod_cod_barra</codigoAuxiliar>";
                $xmlDeta .= "<descripcion>$prod_nom_prod</descripcion>";
                $xmlDeta .= "<cantidad>$dfac_cant_dfac</cantidad>";
                $xmlDeta .= "<precioUnitario>" . round($dfac_precio_dfac, 4) . "</precioUnitario>";
                $xmlDeta .= "<descuento>" . round($descuento, 4) . "</descuento>";
                $xmlDeta .= "<precioTotalSinImpuesto>" . round($dfac_mont_total, 2) . "</precioTotalSinImpuesto>";

                if (strlen($dfac_det_dfac) > 0) {
                    $xmlDeta .= '<detallesAdicionales>';
                    $xmlDeta .= '<detAdicional nombre="Detalle" valor="' . $dfac_det_dfac . '"/>';
                    $xmlDeta .= '</detallesAdicionales>';
                }


                $xmlDeta .= '<impuestos>';

                $totalDetalle = 0;
                $valorIce = 0;
                if ($dfac_por_ice > 0) {
                    $totalDetalle = round($dfac_cant_dfac * $dfac_precio_dfac, 2);
                    $valorIce = round(($totalDetalle * 15 / 100), 2);
                    $dfac_mont_total = $totalDetalle - $desctoItem;
                }

                if ($dfac_por_iva == 0) {
                    if ($prod_sn_noi == 'S') {
                        $codigoPorcentaje = 6;
                        $valor 			  = 0.00;
                        $tarifa           = 0.00;
                        $tot_nobiva 	 += $dfac_mont_total;
                    } elseif ($prod_sn_exe == 'S') {
                        $codigoPorcentaje = 7;
                        $valor 			  = 0.00;
                        $tarifa           = 0.00;
                        $tot_exeiva 	 += $dfac_mont_total;
                    } else {
                        $codigoPorcentaje = 0;
                        $valor 			  = 0.00;
                        $tarifa           = 0.00;
                    }
                } elseif ($dfac_por_iva == 12) {
                    $codigoPorcentaje = 2;
                    if ($dfac_por_ice > 0) {
                        $valor_ice_cal = round((($dfac_mont_total * $dfac_por_ice) / 100), 2);
                        $dfac_mont_total = $dfac_mont_total + $valor_ice_cal;
                    }
                    $valor = round((($dfac_mont_total * $dfac_por_iva) / 100), 2);
                    $tarifa = 12.00;
                    $bandera = 2;
                } elseif ($dfac_por_iva == 14) {
                    $codigoPorcentaje = 3;
                    if ($dfac_por_ice > 0) {
                        $valor_ice_cal = round((($dfac_mont_total * $dfac_por_ice) / 100), 2);
                        $dfac_mont_total = $dfac_mont_total + $valor_ice_cal;
                    }
                    $valor = round((($dfac_mont_total * $dfac_por_iva) / 100), 2);
                    $tarifa = 14.00;
                    $bandera = 3;
                }
                //NUEVOS CODIGOS
                elseif ($dfac_por_iva == 15) {
                    $codigoPorcentaje = 4;
                    if ($dfac_por_ice > 0) {
                        $valor_ice_cal = round((($dfac_mont_total * $dfac_por_ice) / 100), 2);
                        $dfac_mont_total = $dfac_mont_total + $valor_ice_cal;
                    }
                    $valor = round((($dfac_mont_total * $dfac_por_iva) / 100), 2);
                    $tarifa = 15.00;
                    $bandera = 4;
                } elseif ($dfac_por_iva == 5) {
                    $codigoPorcentaje = 5;
                    if ($dfac_por_ice > 0) {
                        $valor_ice_cal = round((($dfac_mont_total * $dfac_por_ice) / 100), 2);
                        $dfac_mont_total = $dfac_mont_total + $valor_ice_cal;
                    }
                    $valor = round((($dfac_mont_total * $dfac_por_iva) / 100), 2);
                    $tarifa = 5.00;
                    $bandera = 5;
                } elseif ($dfac_por_iva == 13) {
                    $codigoPorcentaje = 10;
                    if ($dfac_por_ice > 0) {
                        $valor_ice_cal = round((($dfac_mont_total * $dfac_por_ice) / 100), 2);
                        $dfac_mont_total = $dfac_mont_total + $valor_ice_cal;
                    }
                    $valor = round((($dfac_mont_total * $dfac_por_iva) / 100), 2);
                    $tarifa = 13.00;
                    $bandera = 10;
                } elseif ($dfac_por_iva == 8) {
                    $codigoPorcentaje = 8;
                    if ($dfac_por_ice > 0) {
                        $valor_ice_cal = round((($dfac_mont_total * $dfac_por_ice) / 100), 2);
                        $dfac_mont_total = $dfac_mont_total + $valor_ice_cal;
                    }
                    $valor = round((($dfac_mont_total * $dfac_por_iva) / 100), 2);
                    $tarifa = 8.00;
                    $bandera = 8;
                }


                $xmlDeta .= '<impuesto>';
                $xmlDeta .= '<codigo>2</codigo>';
                $xmlDeta .= "<codigoPorcentaje>$codigoPorcentaje</codigoPorcentaje>";
                $xmlDeta .= "<tarifa>$tarifa</tarifa>";
                $xmlDeta .= "<baseImponible>" . round($dfac_mont_total, 2) . "</baseImponible>";
                $xmlDeta .= "<valor>" . round($valor, 2) . "</valor>";
                $xmlDeta .= '</impuesto>';

                $unid_caja = 1;
                if ($dfac_por_irbp > 0) {
                    // UNIDA X CAJA
                    $sql_unid = "select nvl(prod_uni_caja,1) as prod_uni_caja  from saeprod where
                                                                prod_cod_empr = $id_empresa and
                                                                prod_cod_sucu = $id_sucursal and
                                                                prod_cod_prod = '$dfac_cod_prod' ";
                    $unid_caja = consulta_string_func($sql_unid, 'prod_uni_caja', $oIfx, 1);

                    $xmlDeta .= '<impuesto>';
                    $xmlDeta .= '<codigo>5</codigo>';
                    $xmlDeta .= "<codigoPorcentaje>5001</codigoPorcentaje>";
                    $xmlDeta .= "<tarifa>0.00</tarifa>";
                    $xmlDeta .= "<baseImponible>" . round($dfac_mont_total, 2) . "</baseImponible>";
                    $xmlDeta .= "<valor>" . number_format($dfac_por_irbp * $dfac_cant_dfac * $unid_caja, 2, '.', '') . "</valor>";
                    $xmlDeta .= '</impuesto>';
                    $baseImponibleIRBP += $dfac_mont_total;
                } // fin
                $baseImponibleIce = 0;
                if ($dfac_por_ice > 0) {
                    $baseImponibleIce = round($dfac_mont_total_i * $dfac_cant_dfac, 2);
                    $porcIce = round($baseImponibleIce * $dfac_por_ice / 100, 2);

                    $xmlDeta .= '<impuesto>';
                    $xmlDeta .= '<codigo>3</codigo>';
                    $xmlDeta .= "<codigoPorcentaje>3092</codigoPorcentaje>";
                    $xmlDeta .= "<tarifa>15.00</tarifa>";
                    $xmlDeta .= "<baseImponible>" . round($baseImponibleIce, 2) . "</baseImponible>";
                    $xmlDeta .= "<valor>" . number_format($porcIce, 2, '.', '') . "</valor>";
                    $xmlDeta .= '</impuesto>';
                    $baseImponibleIceTotal += $baseImponibleIce;
                } // fin

                $xmlDeta .= '</impuestos>';
                $xmlDeta .= '</detalle>';
            } while ($oIfx->SiguienteRegistro());
            $xmlDeta .= '</detalles>';
        } // fin ifx
        $oIfx->Free();

        $xmlDeta . -'</totalConImpuestos>';

        $rimpe = "";
        if ($empr_rimp_sn == "S") {
            $rimpe = "<contribuyenteRimpe>CONTRIBUYENTE RÉGIMEN RIMPE</contribuyenteRimpe>";
        }

        //correccion de caracteres especiales en xml
        $caracteres_errores = array("&");
        $caracteres_corregidos = array("&#38;");
        
        //re realizara el reemplazo de los caracteres con sus correcciones al final del  xml
        //$nombre_empr = str_replace($caracteres_errores, $caracteres_corregidos, $nombre_empr);


        ///CABECERA DEL $XML
        $xml .= '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
                            <factura id="comprobante" version="1.1.0">
                                <infoTributaria>
                                    <ambiente>' . $ambiente . '</ambiente>
                                    <tipoEmision>' . $tip_emis . '</tipoEmision>
                                    <razonSocial>' . htmlspecialchars($nombre_empr) . '</razonSocial>
                                    <nombreComercial>' . htmlspecialchars($nombre_empr) . '</nombreComercial>
                                    <ruc>' . $ruc_empr . '</ruc>
                                    <claveAcceso>' . $clave_acceso . '</claveAcceso>
                                    <codDoc>01</codDoc>
                                    <estab>' . $estable . '</estab>
                                    <ptoEmi>' . $serie . '</ptoEmi>
                                    <secuencial>' . $fact_num_preimp . '</secuencial>
                                    <dirMatriz>' . $dir_empr . '</dirMatriz>';
        if (!empty($empr_ac2_empr)) {
            $aret = explode('-', $empr_ac2_empr);
            $numret = intval($aret[2]);
            $xml .= '<agenteRetencion>' . $numret . '</agenteRetencion>';
        }

        $xml .= '' . $rimpe . '
                                </infoTributaria>
                                <infoFactura>
                                    <fechaEmision>' . $fec_fact . '</fechaEmision>
                                    <dirEstablecimiento>' . $arrayDireSucu[$id_sucursal] . '</dirEstablecimiento>';
        if (strlen($empr_num_resu) > 0)
            $xml .= '<contribuyenteEspecial>' . $empr_num_resu . '</contribuyenteEspecial>';

        $xml .= '<obligadoContabilidad>' . $conta_sn . '</obligadoContabilidad>
                            <tipoIdentificacionComprador>' . $tipoIdentificacionComprador . '</tipoIdentificacionComprador>
                            <razonSocialComprador>' . htmlspecialchars($fact_nom_cliente) . '</razonSocialComprador>
                            <identificacionComprador>' . $fact_ruc_clie . '</identificacionComprador>';
                            if (strlen($fact_dir_clie) > 0) {
                                $xml .= '<direccionComprador>' . htmlspecialchars(substr($fact_dir_clie,0,300)) . '</direccionComprador>';
                            }
                    
                            $xml.='
                            <totalSinImpuestos>' . round($totalSinImpuestos, 2) . '</totalSinImpuestos>
                            <totalDescuento>' . round($totalDescuento, 2) . '</totalDescuento>';

        $xml .= "<totalConImpuestos> ";
        if ($fact_con_miva != '') {
            $xml .= '<totalImpuesto>
                                 <codigo>2</codigo>
                                 <codigoPorcentaje>' . $bandera . '</codigoPorcentaje>
                                 <baseImponible>' . round($fact_con_miva, 2) . '</baseImponible>
                                 <valor>' . round($fact_iva, 2) . '</valor>
                             </totalImpuesto>';
        }
        if ($fact_sin_miva != '') {
            /* if($tot_nobiva > 0){
                $xml .= '<totalImpuesto>
                                 <codigo>6</codigo>
                                 <codigoPorcentaje>0</codigoPorcentaje>
                                 <baseImponible>' . round($tot_nobiva, 2) . '</baseImponible>
                                 <valor>0.00</valor>
                          </totalImpuesto>';
            } */
            if ($tot_exeiva > 0) {
                $xml .= '<totalImpuesto>
                                 <codigo>7</codigo>
                                 <codigoPorcentaje>0</codigoPorcentaje>
                                 <baseImponible>' . round($tot_exeiva, 2) . '</baseImponible>
                                 <valor>0.00</valor>
                          </totalImpuesto>';
            }

            $xml .= '<totalImpuesto>
                                 <codigo>2</codigo>
                                 <codigoPorcentaje>0</codigoPorcentaje>
                                 <baseImponible>' . round(($fact_sin_miva - $tot_nobiva - $tot_exeiva), 2) . '</baseImponible>
                                 <valor>0.00</valor>
                     </totalImpuesto>';
        }
        if ($fact_val_irbp > 0) {
            $xml .= '<totalImpuesto>
                                 <codigo>5</codigo>
                                 <codigoPorcentaje>5001</codigoPorcentaje>
                                 <baseImponible>' . $baseImponibleIRBP . '</baseImponible>
                                 <valor>' . round($fact_val_irbp, 2) . '</valor>
                               </totalImpuesto>';
        }
        if ($fact_ice > 0) {
            $xml .= '<totalImpuesto>
                                 <codigo>3</codigo>
                                 <codigoPorcentaje>3092</codigoPorcentaje>
                                 <baseImponible>' . $baseImponibleIceTotal . '</baseImponible>
                                 <valor>' . round($fact_ice, 2) . '</valor>
                               </totalImpuesto>';
        }

        $xml .= "</totalConImpuestos> ";

        $xml .= '<propina>0.00</propina>
                <importeTotal>' . round($importeTotal, 2) . '</importeTotal>
                <moneda>DOLAR</moneda>';

        //query forma de pago
        $sqlFPago = "SELECT fp.fpag_cod_fpagop, fx.fxfp_val_fxfp, fx.fxfp_num_dias
                    from saefact f, saefxfp fx, saefpag fp
                    where f.fact_cod_fact = fx.fxfp_cod_fact and
                    fp.fpag_cod_fpag = fx.fxfp_cod_fpag and
                    f.fact_cod_empr = $id_empresa and
                    f.fact_cod_sucu = $id_sucursal and
                    f.fact_cod_fact = $id_factura";
        if ($oIfx->Query($sqlFPago)) {
            if ($oIfx->NumFilas() > 0) {
                $xml .= '<pagos>';
                do {
                    $fpag_cod_fpagop = $oIfx->f('fpag_cod_fpagop');
                    $fxfp_val_fxfp = $oIfx->f('fxfp_val_fxfp');
                    $fxfp_num_dias = $oIfx->f('fxfp_num_dias');

                    $xml .= '<pago>
                                <formaPago>' . $fpag_cod_fpagop . '</formaPago>
                                <total>' . round($fxfp_val_fxfp, 2) . '</total>
                                <plazo>' . $fxfp_num_dias . '</plazo> 
                                <unidadTiempo>dias</unidadTiempo> 
                            </pago>';
                } while ($oIfx->SiguienteRegistro());
                $xml .= '</pagos>';
            }
        }
        $oIfx->Free();

        $xml .= '</infoFactura>';
        $xml .= $xmlDeta;

        if (strlen($fact_dir_clie) > 0) {
            $aDataInfoAdic['Direccion'] 	= $fact_dir_clie;
        }
        if (strlen($fact_tlf_cliente) > 0) {
            $aDataInfoAdic['Telefono'] 		= $fact_tlf_cliente;
        }
        if (strlen($fact_email_clpv) > 0) {
            $aDataInfoAdic['Email'] 		= $fact_email_clpv;
        }
        if (strlen($orden_compra) > 0) {
            $aDataInfoAdic['PreEntrada'] 	= $orden_compra;
        }

        if (strlen($empr_det_fac) > 0) {
            /*$empr_det_fac = str_replace("<br>", "", $empr_det_fac);
            $empr_det_fac = str_replace("</br>", "", $empr_det_fac);
            $empr_det_fac = str_replace("<b>", "", $empr_det_fac);
            $empr_det_fac = str_replace("</b>", "", $empr_det_fac);*/
            $aDataInfoAdic['Observacion'] 	= strip_tags($empr_det_fac);
        }


        //$aDataInfoAdic['AGENTE RETENCION'] = 'NAC-DNCRASC20-00000001';

        $fact_cm1_fact = str_replace('<br />', "", $fact_cm1_fact);
        $fact_cm1_fact = trim(ltrim($fact_cm1_fact));
        if (strlen($fact_cm1_fact) > 0) {
            $aDataInfoAdic['Observaciones'] = $fact_cm1_fact;
        }

        if (strlen($cod_almacen) > 0) {
            $aDataInfoAdic['codigoAlmacen'] = $cod_almacen;
        }

        $sqlxml = "select ixml_tit_ixml, ixml_det_ixml from saeixml where ixml_cod_empr=$id_empresa 
        and ixml_est_deleted ='S' and ixml_sn_xml='S' order by ixml_ord_ixml";

        if ($oIfx->Query($sqlxml)) {
            if ($oIfx->NumFilas() > 0) {
                do {
                    $titulo = $oIfx->f('ixml_tit_ixml');
                    $detalle = $oIfx->f('ixml_det_ixml');
                    $aDataInfoAdic[$titulo] = $detalle;
                } while ($oIfx->SiguienteRegistro());
            }
        }
        $oIfx->Free();


        $etiqueta = array_keys($aDataInfoAdic);
        if (count($etiqueta) > 0) {
            $xml .= '<infoAdicional>';
            foreach ($etiqueta as $nom) {
                if ($aDataInfoAdic[$nom] != '')
                    $xml .= "<campoAdicional nombre=\"$nom\">" . trim($aDataInfoAdic[$nom]) . "</campoAdicional>";
            }
            $xml .= '</infoAdicional>';
        }

        $xml .= '</factura>';

        $xml = str_replace($caracteres_errores, $caracteres_corregidos, $xml);

        if ($empr_tip_firma == 'N') {

            // firma Normal
            $serv = "C:/Jireh/";
            $ruta = $serv . "Comprobantes Electronicos";

            // CARPETA EMPRESA
            $ruta_gene = $ruta . "/generados";
            if (!file_exists($ruta))
                mkdir($ruta);

            if (!file_exists($ruta_gene))
                mkdir($ruta_gene);

            $nombre = $clave_acceso . ".xml";
            $archivo = fopen($ruta_gene . '/' . $nombre, "w+");

            //fwrite($archivo, $xml);
            fwrite($archivo, utf8_encode($xml));
            fclose($archivo);
        } elseif ($empr_tip_firma == 'M') {

            //MultiFirma
            $nombre = $clave_acceso . ".xml";
            $serv = '';
            $archivo = '';

            $serv = DIR_FACTELEC . "modulos/sri_offline/documentoselectronicos/generados";

            $archivo = fopen($serv . '/' . $nombre, "w+");

            fwrite($archivo, utf8_encode($xml));
            fclose($archivo);
        }

        $ret_num_fact = '';
        $asto_cod_ejer = 0;
        $rete_cod_asto = '';

        $contenido_xml = file_get_contents(DIR_FACTELEC . "modulos/sri_offline/documentoselectronicos/generados/$clave_acceso.xml");

        unset($array_erro);

        //tipo firma empresa
        $sqlEmpr = "SELECT empr_tip_firma from saeempr where empr_cod_empr = $id_empresa";
        $empr_tip_firma = consulta_string_func($sqlEmpr, 'empr_tip_firma', $oIfx, 'N');

        if ($empr_tip_firma == 'N') {

            //SETEAMOS EL WEB SERVICE PARA FIRMAR LOS COMPROBANTES
            $clientOptions = array(
                "useMTOM" => FALSE,
                'trace' => 1,
                'stream_context' => stream_context_create(array('http' => array('protocol_version' => 1.0)))
            );
            $wsdlFirma[$clave_acceso] = new SoapClient("http://localhost:8080/WebServFirma/firmaComprobante?WSDL", $clientOptions);


            //time por documento
            $sql = "select d.time from doc_time d where d.id_time = $doc ";
            $tiempoEspera = consulta_string_func($sql, 'time', $oCon, 10);

            //datos sucursal ambiente, token
            $tipoAmbiente = ambienteEmisionSri($oIfx, $id_empresa, $id_sucursal, 1);
            $token = ambienteEmisionSri($oIfx, $id_empresa, $id_sucursal, 3);

            //crear directorio firmados
            $serv = "C:/Jireh/";
            $ruta = $serv . "Comprobantes Electronicos";

            // CARPETA EMPRESA
            $pathFirmados = $ruta . "/firmados";

            if (!file_exists($ruta)) {
                mkdir($ruta);
            }

            if (!file_exists($pathFirmados)) {
                mkdir($pathFirmados);
            }

            $pathArchivo = "C:/Jireh/comprobantes electronicos/generados/" . $nombre_archivo;
            $password = null;

            $aFirma = array(
                "ruc" => $ruc, "tipoAmbiente" => $tipoAmbiente, "tiempoEspera" => $tiempoEspera,
                "token" => $token, "pathArchivo" => $pathArchivo, "pathFirmados" => $pathFirmados,
                "password" => $password
            );

            $respFirm = $wsdlFirma[$clave_acceso]->FirmarDocumento($aFirma);

            $respFirm = strtoupper($respFirm->return);

            
        } elseif ($empr_tip_firma == 'M') {

            //datos de la firma
            $sqlEmprToke = "SELECT empr_nom_toke,empr_pass_toke, empr_token_api from saeempr where empr_cod_empr = $id_empresa";
            if ($oIfx->Query($sqlEmprToke)) {
                $empr_nom_toke = $oIfx->f("empr_nom_toke");
                $empr_pass_toke = $oIfx->f("empr_pass_toke");
                $empr_api_toke = $oIfx->f("empr_token_api");
            }

            $oIfx->Free();

            $contenido_xml = file_get_contents(DIR_FACTELEC . "modulos/sri_offline/documentoselectronicos/generados/$clave_acceso.xml");
           
            if ($contenido_xml) {

                $headers = array(
                    "Content-Type:application/json",
                    "Token-Api:$empr_api_toke"
                );
                $data = array(
                    "clave_acceso" => $clave_acceso,
                    "contenido_xml" => base64_encode($xml),
                    "ambiente_prueba" => ($tipoAmbiente == 1) ? true : false
                );


                $ch = curl_init();
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch, CURLOPT_URL, URL_JIREH_WS . "/api/facturacion/electronica/firmar/comprobante");
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
                curl_setopt($ch, CURLOPT_TIMEOUT, 60); 

                $respuesta = curl_exec($ch);
                $resultado = json_decode($respuesta, true);

                $documento_firmado = $resultado["documento_firmado"];
                $contenido_xml = base64_decode($resultado["contenido_xml"]);
                file_put_contents(DIR_FACTELEC . "modulos/sri_offline/documentoselectronicos/firmados/$clave_acceso.xml", $contenido_xml);

                if ($documento_firmado == true) {
                    $arrayTipoAmbiente[1] = 'PRUEBAS';
                    $arrayTipoAmbiente[2] = 'PRODUCCION';
            
                    $tipoAmbiente = ambienteEmisionSri($oIfx, $id_empresa, $id_sucursal, 1);
                    $tipoEsquema = ambienteEmisionSri($oIfx, $id_empresa, $id_sucursal, 4);
            
                    if ($empr_tip_firma == 'N') {
                        $rutaFirm = "C:/Jireh/Comprobantes Electronicos/firmados/" . $nombre_archivo;
                    } elseif ($empr_tip_firma == 'M') {
                        $rutaFirm = DIR_FACTELEC . "modulos/sri_offline/documentoselectronicos/firmados/" . $nombre_archivo;
                    }
            
                    $contenido_xml = file_get_contents(DIR_FACTELEC . "modulos/sri_offline/documentoselectronicos/firmados/$clave_acceso.xml");

                    $headers = array(
                        "Content-Type:application/json",
                        "Token-Api:$empr_api_toke"
                    );

                    $data = array(
                        "documento_xml" => base64_encode($contenido_xml),
                        "ambiente_prueba" => ($tipoAmbiente == 1) ? true : false
                    );

                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                    curl_setopt($ch, CURLOPT_URL, URL_JIREH_WS . "/api/facturacion/electronica/validar/comprobante");
                    curl_setopt($ch, CURLOPT_POST, true);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
                    curl_setopt($ch, CURLOPT_TIMEOUT, 60); 

                    $respuesta = curl_exec($ch);
                    $resultado = json_decode($respuesta, true);

                    $estado = $resultado["estado"];
                    $informacion_adicional =  $resultado["informacion_adicional"];
                    
                    $fechaAutorizacion = '';
                    $mensaje =  $resultado["mensaje"];
                    
                    $op_autoriza = false;
                    if (strcmp($estado, "RECIBIDA") == 0) {
                        $op_autoriza = true;
                    }else if(strcmp($estado, "DEVUELTA") == 0 && strcmp($mensaje, "CLAVE ACCESO REGISTRADA") == 0) {
                        $op_autoriza = true;
                    }else {
                        //ERROR EN FIRMAR
                        if(empty($mensaje)){
                            $mensaje = $informacion_adicional;
                        }

                        if(empty($mensaje)){
                            $mensaje = 'Sin respuesta SRI';
                        }

                        throw new Exception($mensaje);
                    }

                    

                    if($op_autoriza){
                        $sqlUpdaComp = "UPDATE saefact set 
                                        fact_aprob_sri = 'F',
                                        fact_auto_sri = '$clave_acceso',
                                        fact_user_sri = $id_usuario,
                                        fact_fech_sri = '',
                                        fact_erro_sri = '',
                                        fact_clav_sri  = '$clave_acceso'
                                        WHERE 
                                        fact_cod_empr = $id_empresa AND 
                                        fact_cod_sucu = $id_sucursal AND 
                                        fact_cod_clpv = $fact_cod_clpv AND 
                                        fact_cod_fact = $id_factura" ;
                        $oIfx->QueryT($sqlUpdaComp);
                        //PROCESO DE AUTORIZAR
                        $headers = array(
                            "Content-Type:application/json",
                            "Token-Api:$empr_api_toke"
                        );
                        $data = array(
                            "clave_acceso" => $clave_acceso,
                            "ambiente_prueba" => ($tipoAmbiente == 1) ? true : false
                        );
                
                        $ch = curl_init();
                        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                        curl_setopt($ch, CURLOPT_URL, URL_JIREH_WS . "/api/facturacion/electronica/autorizacion/comprobante");
                        curl_setopt($ch, CURLOPT_POST, true);
                        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
                        curl_setopt($ch, CURLOPT_TIMEOUT, 60); 

                        $respuesta = curl_exec($ch);
                        $resultado = json_decode($respuesta, true);
                
                        $estado = $resultado["estado"];
                        $fecha_autorizacion = $resultado["fechaAutorizacion"];
                        $claveAccesoConsultada = $resultado["numeroAutorizacion"];
                
                        $tipo_valida = ($tipoAmbiente == 1) ? "NO DISPONIBLE" : "AUTORIZADO";

                        if (strcmp($estado, $tipo_valida) == 0) {
                            //if(true){
                            $sqlUpdaComp = "UPDATE saefact set 
                                            fact_aprob_sri = 'S',
                                            fact_auto_sri = '$clave_acceso',
                                            fact_user_sri = $id_usuario,
                                            fact_fech_sri = '$fecha_autorizacion',
                                            fact_erro_sri = '',
                                            fact_clav_sri  = '$clave_acceso'
                                            WHERE 
                                            fact_cod_empr = $id_empresa AND 
                                            fact_cod_sucu = $id_sucursal AND 
                                            fact_cod_clpv = $fact_cod_clpv AND 
                                            fact_cod_fact = $id_factura" ;
                            $oIfx->QueryT($sqlUpdaComp);
                            //ESTA AUTORIZADO

                            $oIfx->QueryT('COMMIT;');
                
                            $dia = substr($claveAccesoConsultada[$clave_acceso], 0, 2);
                            $mes = substr($claveAccesoConsultada[$clave_acceso], 2, 2);
                            $an = substr($claveAccesoConsultada[$clave_acceso], 4, 4);
                
                            $CarpDocu = 'FACTURAS VENTAS';
                            $docu = "Fact_";
                            //VALIDACION FORMATO PERSONALIZADO
                            $sql = "select ftrn_ubi_web from saeftrn  where ftrn_cod_empr = $id_empresa and ftrn_des_ftrn = 'FACTURA' and ftrn_cod_modu=7 
                            and (ftrn_ubi_web is not null or ftrn_ubi_web != '') and ftrn_ubi_web like '%Formatos%'";
                            $ubi = consulta_string($sql, 'ftrn_ubi_web', $oIfx, '');
                
                            if (!empty($ubi)) {
                                include_once('../../' . $ubi . '');
                                reporte_factura_personalizado($id_factura, $clave_acceso, $id_sucursal, $rutaPdf);
                            }
                            else{
                                reporte_factura($id_factura, $clave_acceso, $id_sucursal, $rutaPdf);
                            }

                            //CREO LOS DIRECTORIOS DE LOS RIDES
                            $serv = "C:/Jireh";
                            $rutaRide = $serv . "/RIDE";
                            $rutaComp = $rutaRide . "/" . $CarpDocu;
                            $rutaAo = $rutaComp . "/" . $an;
                            $rutaMes = $rutaAo . "/" . $mes;
                            $rutaDia = $rutaMes . "/" . $dia;
                
                            if (!file_exists($rutaRide)) {
                                mkdir($rutaRide);
                            }
                
                            if (!file_exists($rutaComp)) {
                                mkdir($rutaComp);
                            }
                
                            if (!file_exists($rutaAo)) {
                                mkdir($rutaAo);
                            }
                
                            if (!file_exists($rutaMes)) {
                                mkdir($rutaMes);
                            }
                
                            if (!file_exists($rutaDia)) {
                                mkdir($rutaDia);
                            }
                
                            $numeDocu = substr($claveAccesoConsultada[$clave_acceso], 24, 15);
                            $nombre = $docu . $numeDocu . "_" . "$dia-$mes-$an" . ".xml";
                
                            //FORMO EL RIDE
                            $ride .= '<?xml version="1.0" encoding="UTF-8"?>';
                            $ride .= '<autorizacion>';
                            $ride .= "<estado>$estado[$clave_acceso]</estado>";
                            $ride .= "<numeroAutorizacion>$numeroAutorizacion[$clave_acceso]</numeroAutorizacion>";
                            $ride .= "<fechaAutorizacion>$fechaAutorizacion[$clave_acceso]</fechaAutorizacion>";
                            $ride .= "<ambiente>$ambiente[$clave_acceso]</ambiente>";
                            $ride .= "<comprobante><![CDATA[$comprobante[$clave_acceso]]]></comprobante>";
                            $ride .= '</autorizacion>';
                
                            $rutaDia = DIR_FACTELEC . "modulos/sri_offline/documentoselectronicos/firmados";
                
                            // ruta del xml
                            $archivo_xml = fopen($rutaDia . '/' . $nombre, "w+");
                            fwrite($archivo_xml, $ride);
                            fclose($archivo_xml);
                            $array_erro3[] = array($claveAccesoConsultada[$clave_acceso], 'Autorizacion Comprobante', $estado[$clave_acceso]);
                
                
                            $ride = '' . $rutaDia . '/' . $nombre;
                
                            $ride = DIR_FACTELEC . "modulos/sri_offline/documentoselectronicos/firmados/$clave_acceso.xml";
                            $rutaPdf = DIR_FACTELEC . 'Include/archivos/' . $clave_acceso . '.pdf';
                
                            $numeDocu = substr($clave_acceso, 24, 15);
                
                            $sqlnom = "SELECT clpv_nom_clpv from saeclpv where clpv_cod_clpv=$fact_cod_clpv";
                            $nom_clpv = consulta_string($sqlnom, 'clpv_nom_clpv', $oIfx, '');
                
                            $correoMsj = '';
                            if(!empty($fact_email_clpv)){
                                $correoMsj = envio_correo_adj_2($oIfx, $oCon, $fact_email_clpv, $ride, $rutaPdf, $nom_clpv, $clave_acceso, $numeDocu, $fact_cod_clpv, 1, $id_sucursal);
                            }
                            
                            $respuesta = array(
                                "status" => 200,
                                "clave_acceso" => $clave_acceso,
                                "respuesta" => "AUTORIZADO ($correoMsj)",
                                "id_factura" => $id_factura
                            );
                            
                        } else {
                            $respFirma = "NO AUTORIZADO";
                            throw new Exception($respFirma);
                        }
                    }
                } else {

                    $detalles = '';
                    if(isset($resultado["detail"]["msg"])){
                        $detalles = $resultado["detail"]["msg"];
                    }
                    
                    //ERROR EN FIRMAR
                    $respFirma = "NO FIRMADO ". $detalles;
                    throw new Exception($respFirma);
                }
            } else {
                //NO SE ENCUENTRA EL XML
                $respFirma = "NO SE ENCUENTRA EL DOCUMENTO GENERADO";
                throw new Exception($respFirma);
            }
        }
    } // fin check

} catch (Exception $e) {
    $mensaje = $e->getMessage();

    $oIfx->QueryT('ROLLBACK;');

    $respuesta = array(
        "status" => 500,
        "mensaje" => $mensaje,
        "clave_acceso" => $clave_acceso_resp,
        "id_factura" => $id_factura
    );

    $oCon->QueryT('BEGIN;');

    $expresion_regular = '/[^a-zA-Z0-9\s]/';
    $mensaje_g = preg_replace($expresion_regular, '', $mensaje);

    $sql_error = "UPDATE saefact set fact_erro_sri = '$mensaje' WHERE fact_cod_fact = $id_factura";
    $oCon->QueryT($sql_error);
    
    $oCon->QueryT('COMMIT;');
}

echo json_encode($respuesta);

?>