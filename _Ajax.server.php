<?php

require("_Ajax.comun.php"); // No modificar esta linea
include("config.php");
/* :::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
  // S E R V I D O R   A J A X //
  :::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::: */
/**
Herramientas de apoyo
 */
ini_set('memory_limit', '1024M');
/* * *********************************************************************** */
/* DF01 :: G E N E R A    F O R M U L A R I O    F A C T U R A C I O N    */
/* * *********************************************************************** */

function editar_contactos($tipoDocu, $id ,$id_empresa, $id_sucursal=0, $clpv=0, $num_fact='', $ejer=0, $asto='', $fec_emis='', $num_comp=0){

	    //Definiciones
		global $DSN_Ifx, $DSN;

		session_start();
	
		$oCon = new Dbo();
		$oCon->DSN = $DSN;
		$oCon->Conectar();
	
		$oConA = new Dbo();
		$oConA->DSN = $DSN;
		$oConA->Conectar();
	
		$oReturn = new xajaxResponse();

		if(empty($clpv)){
			$clpv=0;
		}

		if(empty($id_sucursal)){
			$id_sucursal=0;
		}

		if(empty($ejer)){
			$ejer=0;
		}

		if(empty($num_comp)){
			$num_comp=0;
		}
		
		switch ($tipoDocu) {
			case 1:
				$sqlUpdaComp = "select fact_email_clpv, fact_tlf_cliente, fact_num_preimp from saefact where fact_cod_fact=$id
				and fact_cod_empr=$id_empresa";
				$correo=consulta_string($sqlUpdaComp,'fact_email_clpv',$oCon,'');
				$telefono=consulta_string($sqlUpdaComp,'fact_tlf_cliente',$oCon,'');
				$secuencial=consulta_string($sqlUpdaComp,'fact_num_preimp',$oCon,'');
				$titulo='FACTURA DE VENTA';
				break;
			case 2:
				$sqlUpdaComp = "select fact_email_clpv, fact_tlf_cliente from saefact where fact_cod_fact=$id
				and fact_cod_empr=$id_empresa";
				$correo=consulta_string($sqlUpdaComp,'fact_email_clpv',$oCon,'');
				$telefono=consulta_string($sqlUpdaComp,'fact_tlf_cliente',$oCon,'');
				$secuencial=consulta_string($sqlUpdaComp,'fact_num_preimp',$oCon,'');
				$titulo='NOTA DE DEBITO';
				break;
			case 3:
				$sqlUpdaComp = "select ncre_email_clpv, ncre_tlf_cliente, ncre_num_preimp from saencre where ncre_cod_ncre=$id
				and ncre_cod_empr=$id_empresa";
				$correo=consulta_string($sqlUpdaComp,'ncre_email_clpv',$oCon,'');
				$telefono=consulta_string($sqlUpdaComp,'ncre_tlf_cliente',$oCon,'');
				$secuencial=consulta_string($sqlUpdaComp,'ncre_num_preimp',$oCon,'');
				$titulo='NOTA DE CREDITO';
				break;
			case 4:
				$sqlUpdaComp = "select guia_email_clpv, guia_tlf_cliente, guia_num_preimp from saeguia where guia_cod_guia=$id
				and guia_cod_empr=$id_empresa";
				$correo=consulta_string($sqlUpdaComp,'guia_email_clpv',$oCon,'');
				$telefono=consulta_string($sqlUpdaComp,'guia_tlf_cliente',$oCon,'');
				$secuencial=consulta_string($sqlUpdaComp,'guia_num_preimp',$oCon,'');
				$titulo='GUIA DE REMISION';
				break;
			case 5:
				$sqlUpdaComp = "SELECT r.asto_cod_sucu, r.rete_cod_asto, f.fprv_fec_emis, r.ret_ser_ret, r.ret_num_ret,r.rete_telf_benf, 
						r.rete_nom_benf, r.rete_dire_benf, f.fprv_num_seri, f.fprv_aprob_sri,
						r.ret_num_fact,  r.ret_cod_clpv, f.fprv_clav_sri,
						r.asto_cod_ejer, f.fprv_erro_sri, r.ret_email_clpv , c.clv_con_clpv, f.fprv_cod_tran,f.fprv_rete_fec,
						sum(r.ret_valor) as total
						from saeret r , saeasto a , saefprv f , saeclpv c where 
						c.clpv_cod_clpv = f.fprv_cod_clpv and
						c.clpv_cod_clpv = r.ret_cod_clpv and
						a.asto_cod_asto = f.fprv_cod_asto and
						a.asto_cod_asto = r.rete_cod_asto and
						a.asto_cod_ejer = r.asto_cod_ejer and
						r.ret_num_fact  = f.fprv_num_fact and
						f.fprv_cod_sucu = r.asto_cod_sucu and
						a.asto_cod_sucu = r.asto_cod_sucu and
						c.clpv_cod_empr = $id_empresa and
						c.clpv_clopv_clpv = 'PV' and
						r.asto_cod_empr = $id_empresa and
						a.asto_cod_empr = $id_empresa and
						a.asto_est_asto <> 'AN' and
						f.fprv_cod_empr = $id_empresa and
						r.ret_elec_sn ='S' and
						f.fprv_cod_sucu = $id_sucursal  and 
                        f.fprv_cod_clpv = $clpv and 
                        f.fprv_num_fact = '$num_fact' and 
                        f.fprv_cod_ejer = '$ejer' and  
                        f.fprv_cod_asto = '$asto' and  
                        f.fprv_fec_emis = '$fec_emis'
						group by 1,2,3,4,5,6,7,8,9,10,11,12, 13, 14, 15, 16, 17,18,19";
						
				$correo=consulta_string($sqlUpdaComp,'ret_email_clpv',$oCon,'');
				$telefono=consulta_string($sqlUpdaComp,'rete_telf_benf',$oCon,'');
				$secuencial=consulta_string($sqlUpdaComp,'ret_num_ret',$oCon,'');

				
				$titulo='RETENCION DE GASTO';
				break;
			case 6:
				$sqlUpdaComp = "select r.asto_cod_sucu, r.rete_cod_asto, m.minv_fmov, r.ret_ser_ret, r.ret_num_ret,r.rete_telf_benf, 
							r.rete_nom_benf, r.rete_dire_benf, m.minv_ser_docu, 
							r.ret_num_fact, sum(r.ret_valor) as total, r.ret_cod_clpv, 
							r.asto_cod_ejer, m.minv_erro_sri, r.ret_email_clpv, m.minv_num_comp,
							c.clv_con_clpv, m.minv_aprob_sri, m.minv_clav_sri, m.minv_fec_ret
							from saeminv m, saeret r, saeasto a  , saeclpv c where
							c.clpv_cod_clpv = m.minv_cod_clpv and
							c.clpv_cod_clpv = r.ret_cod_clpv and
							c.clpv_cod_empr = $id_empresa and
							m.minv_cod_sucu = r.asto_cod_sucu and
							m.minv_cod_empr = r.asto_cod_empr and
							a.asto_cod_sucu = r.asto_cod_sucu and
							c.clpv_clopv_clpv = 'PV' and
							m.minv_cod_clpv = r.ret_cod_clpv and
							m.minv_cod_ejer = r.asto_cod_ejer and
							a.asto_cod_ejer = r.asto_cod_ejer and
							m.minv_fac_prov = r.ret_num_fact and
							a.asto_cod_asto = r.rete_cod_asto  and
							a.asto_cod_asto = m.minv_comp_cont and
							m.minv_comp_cont = r.rete_cod_asto and
							m.minv_fmov = '$fec_emis' and
							m.minv_cod_empr = $id_empresa and
							m.minv_est_minv <> '0' and
							r.asto_cod_empr = $id_empresa and
							a.asto_cod_empr = $id_empresa and
							a.asto_est_asto <> 'AN' and 
							m.minv_cod_sucu = $id_sucursal  and 
                            m.minv_cod_clpv = $clpv and 
                            m.minv_fac_prov = '$num_fact' and 
                            m.minv_cod_ejer = '$ejer' and  
                            m.minv_comp_cont = '$asto' 
							group by r.asto_cod_sucu, r.ret_num_fact, r.ret_ser_ret, r.ret_num_ret,r.rete_telf_benf,
							r.rete_nom_benf,r.rete_dire_benf, m.minv_fmov, 
							m.minv_ser_docu, r.rete_cod_asto, r.ret_cod_clpv, 
							r.asto_cod_ejer,m.minv_erro_sri,r.ret_email_clpv, m.minv_num_comp, c.clv_con_clpv,
							m.minv_aprob_sri, m.minv_clav_sri, m.minv_fec_ret";

				$correo=consulta_string($sqlUpdaComp,'ret_email_clpv',$oCon,'');
				$telefono=consulta_string($sqlUpdaComp,'rete_telf_benf',$oCon,'');
				$secuencial=consulta_string($sqlUpdaComp,'ret_num_ret',$oCon,'');
				$titulo='RETENCION DE INVENTARIO';
				break;
			case 7:
				$sqlUpdaComp = "select fact_email_clpv, fact_tlf_cliente from saefact where fact_cod_fact=$id
				and fact_cod_empr=$id_empresa";
				$correo=consulta_string($sqlUpdaComp,'fact_email_clpv',$oCon,'');
				$telefono=consulta_string($sqlUpdaComp,'fact_tlf_cliente',$oCon,'');
				$secuencial=consulta_string($sqlUpdaComp,'fact_num_preimp',$oCon,'');
				$titulo='FACTURA EXPORTADORES';
				break;
			case 8:
				/*$sqlUpdaComp = "select fact_email_clpv, fact_tlf_cliente from saefact where fact_cod_fact=$id
				and fact_cod_empr=$id_empresa";
				$correo=consulta_string($sqlUpdaComp,'fact_email_clpv',$oCon,'');
				$telefono=consulta_string($sqlUpdaComp,'fact_tlf_cliente',$oCon,'');
				$secuencial=consulta_string($sqlUpdaComp,'fact_num_preimp',$oCon,'');¨*/
				$titulo='FACTURA FLORICOLA';
				break;
			case 9:
				/*$sqlUpdaComp = "select fact_email_clpv, fact_tlf_cliente from saefact where fact_cod_fact=$id
				and fact_cod_empr=$id_empresa";
				$correo=consulta_string($sqlUpdaComp,'fact_email_clpv',$oCon,'');
				$telefono=consulta_string($sqlUpdaComp,'fact_tlf_cliente',$oCon,'');
				$secuencial=consulta_string($sqlUpdaComp,'fact_num_preimp',$oCon,'');*/
				$titulo='FACTURA FLORICOLA EXPORTADOR';
				break;
			case 10:
				/*$sqlUpdaComp = "select fact_email_clpv, fact_tlf_cliente from saefact where fact_cod_fact=$id
				and fact_cod_empr=$id_empresa";
				$correo=consulta_string($sqlUpdaComp,'fact_email_clpv',$oCon,'');
				$telefono=consulta_string($sqlUpdaComp,'fact_tlf_cliente',$oCon,'');
				$secuencial=consulta_string($sqlUpdaComp,'fact_num_preimp',$oCon,'');*/
				$titulo='GUIA DE REMISION FLORICOLA';
				break;
			case 12:
				$sqlUpdaComp = "
				select  a.asto_cod_sucu,  m.minv_fmov, m.minv_fac_prov, c.clpv_nom_clpv, minv_email_clpv, m.minv_num_comp, 
                        (select max(dire_dir_dire)
                        from saedire
                        where dire_cod_empr = c.clpv_cod_empr
                        and dire_cod_sucu = c.clpv_cod_sucu
                        and dire_cod_clpv = c.clpv_cod_clpv) as direccion, 
                        m.minv_cod_clpv, m.minv_ser_docu, m.minv_aprob_liqu, m.minv_clav_sri,
                        a.asto_cod_ejer, m.minv_erro_sri, c.clv_con_clpv, m.minv_cod_tran, c.clpv_ruc_clpv, 
                        (select max(tlcp_tlf_tlcp)
                        from saetlcp
                        where tlcp_cod_empr = c.clpv_cod_empr
                        and tlcp_cod_sucu = c.clpv_cod_sucu
                        and tlcp_cod_clpv = c.clpv_cod_clpv) as telefono,
                        minv_con_iva,
                        minv_sin_iva,
                        (minv_iva_valo) as valor_iva,
                        minv_tot_minv,
                        COALESCE(minv_val_noi, '0') as no_ojeto_iva,
                        COALESCE(minv_val_exe, '0') as exento_iva,
                        m.minv_cod_fpagop, ( m.minv_fec_entr - m.minv_fmov ) as dias_plazo
                        
                from  saeasto a , saeminv m , saeclpv c, saedmov d
                where   a.asto_cod_asto = m.minv_tran_minv and
                        a.asto_cod_empr = m.minv_cod_empr and
                        a.asto_cod_sucu = m.minv_cod_sucu and		
                        a.asto_cod_ejer = m.minv_cod_ejer and
                        c.clpv_cod_clpv = m.minv_cod_clpv and
                        c.clpv_cod_empr = m.minv_cod_empr and 
                        d.dmov_cod_empr = m.minv_cod_empr and
                        d.dmov_cod_sucu = m.minv_cod_sucu and		
                        d.dmov_cod_ejer = m.minv_cod_ejer and
                        d.dmov_num_comp = m.minv_num_comp and	
                        c.clpv_cod_empr = $id_empresa and
                        c.clpv_clopv_clpv = 'PV' and
                        a.asto_cod_empr = $id_empresa and
                        a.asto_est_asto <> 'AN' and
                        m.minv_cod_empr = $id_empresa and
						m.minv_cod_sucu = $id_sucursal  and 
                        m.minv_cod_clpv = $clpv and 
                        m.minv_elec_sn ='S' and
                        m.minv_fmov  = '$fec_emis' and
						m.minv_num_comp= $num_comp
                    group by 1,2,3,4,5,6,7,8,9,10,11,12, 13, 14,15,16,17, 18, 19, 20, 21,22, 23, 24, 25";
				$correo=consulta_string($sqlUpdaComp,'minv_email_clpv',$oCon,'');
				$telefono=consulta_string($sqlUpdaComp,'telefono',$oCon,'');
				$secuencial=consulta_string($sqlUpdaComp,'minv_fac_prov',$oCon,'');
				$titulo='LIQUIDACION DE COMPRAS';
				break;	
		}
		$sHtml = '';
		$sHtml .= '<div class="modal-dialog modal-lg" >
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4><b>'.$titulo.' Nro:</b> ' . $secuencial . ' </h4>
				</div>
		<div class="modal-body">';
	
		$sHtml .= '<div class="row">
		
		<div class="col-xs-12 col-sm-12 col-md-6">
               <div class="form-group">
                   <label class="control-label" for="correo_edit">E-mail:</label>
                   <input type="text" id="correo_edit" name="correo_edit" class="form-control" value="' . $correo . '" placeholder="EMAIL" >
               </div>
        </div>

		<div class="col-xs-12 col-sm-12 col-md-6">
               <div class="form-group">
                   <label class="control-label" for="telf_edit">Telefono:</label>
                   <input type="text" id="telf_edit" name="telf_edit" class="form-control" value="' . $telefono . '" placeholder="CELULAR" >
               </div>
        </div>
		
		</div>';
	
		$sHtml .= '          </div>
							<div class="modal-footer">
							<button type="button" class="btn btn-primary"  onClick="guarda_edita_contactos('.$tipoDocu.', '.$id.' ,'.$id_empresa.', '.$id_sucursal.', '.$clpv.', \''.$num_fact.'\', '.$ejer.', \''.$asto.'\', \''.$fec_emis.'\', '.$num_comp.')">Actualizar</button>
								<button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
	
							</div>
						</div>
					</div>
				</div>';
	
	
		$oReturn->assign("ModalEdita", "innerHTML", $sHtml);
		return $oReturn;		
		

}
function guardar_editar_contactos($aForm='', $tipoDocu, $id ,$id_empresa, $id_sucursal, $clpv=0, $num_fact='', $ejer=0, $asto='', $fec_emis='', $num_comp=0){

	    //Definiciones
		global $DSN_Ifx, $DSN;

		session_start();
	
		$oCon = new Dbo();
		$oCon->DSN = $DSN;
		$oCon->Conectar();
	
		$oConA = new Dbo();
		$oConA->DSN = $DSN;
		$oConA->Conectar();
	
		$oReturn = new xajaxResponse();

		$correo=trim($aForm['correo_edit']);
		$telefono=trim($aForm['telf_edit']);
		$usuario_web = $_SESSION['U_ID'];
		$fecha = date('Y-m-d H:i:s');


		try {
			// commit
			$oCon->QueryT('BEGIN');
		
		switch ($tipoDocu) {
			case 1:
				$sqlUpdaComp = "update saefact set fact_email_clpv='$correo', fact_tlf_cliente='$telefono', fac_edi_sri=$usuario_web, fact_fedi_sri ='$fecha'  
				where fact_cod_fact=$id and fact_cod_empr=$id_empresa";
				$oCon->QueryT($sqlUpdaComp);
				$titulo='FACTURA DE VENTA';
				break;
			case 2:
				$sqlUpdaComp = "update saefact set fact_email_clpv='$correo', fact_tlf_cliente='$telefono', fac_edi_sri=$usuario_web, fact_fedi_sri ='$fecha'  
				where fact_cod_fact=$id and fact_cod_empr=$id_empresa";
				$oCon->QueryT($sqlUpdaComp);
				$titulo='NOTA DE DEBITO';
				break;
			case 3:
				$sqlUpdaComp = "update saencre set  ncre_email_clpv='$correo', ncre_tlf_cliente='$telefono', ncre_edi_sri=$usuario_web, ncre_fedi_sri='$fecha' 
				where ncre_cod_ncre=$id and ncre_cod_empr=$id_empresa";
				$oCon->QueryT($sqlUpdaComp);
				$titulo='NOTA DE CREDITO';
				break;
			case 4:
				$sqlUpdaComp = "update saeguia set guia_email_clpv='$correo', guia_tlf_cliente='$telefono', guia_edi_sri=$usuario_web, guia_fedi_sri='$fecha'  
				where guia_cod_guia=$id and guia_cod_empr=$id_empresa";
				$oCon->QueryT($sqlUpdaComp);
				$titulo='GUIA DE REMISION';
				break;
			case 5:
				$sqlUpdaComp = "update saefprv set fprv_edi_sri=$usuario_web, fprv_fedi_sri='$fecha' where 
                                    fprv_cod_empr = $id_empresa and 
                                    fprv_cod_sucu = $id_sucursal  and 
                                    fprv_cod_clpv = $clpv and 
                                    fprv_num_fact = '$num_fact' and 
                                    fprv_cod_ejer = '$ejer' and  
                                    fprv_cod_asto = '$asto' and  
                                    fprv_fec_emis = '$fec_emis' ";
				$oCon->QueryT($sqlUpdaComp);


				$sqlUpdaComp = "update saeret set ret_email_clpv='$correo', rete_telf_benf='$telefono' where
				ret_cod_clpv = $clpv and
				ret_num_fact = '$num_fact' and 
				asto_cod_ejer = '$ejer' and 
				asto_cod_sucu = $id_sucursal and 
				rete_cod_asto = '$asto' and
				ret_elec_sn ='S'";

				$oCon->QueryT($sqlUpdaComp);

				$titulo='RETENCION DE GASTO';
				break;
			case 6:

				$sqlUpdaComp = "update saeminv set minv_edi_sri=$usuario_web, minv_fedi_sri='$fecha' where 
									minv_cod_empr = $id_empresa and 
                                    minv_cod_sucu = $id_sucursal  and 
                                    minv_cod_clpv = $clpv and 
                                    minv_fac_prov = '$num_fact' and 
                                    minv_cod_ejer = '$ejer' and  
                                    minv_comp_cont = '$asto' and  
                                    minv_fmov      = '$fec_emis'";
				$oCon->QueryT($sqlUpdaComp);

				$sqlUpdaComp = "update saeret set ret_email_clpv='$correo', rete_telf_benf='$telefono' where
				ret_cod_clpv = $clpv and
				ret_num_fact = '$num_fact' and 
				asto_cod_ejer = '$ejer' and 
				asto_cod_sucu = $id_sucursal and 
				rete_cod_asto = '$asto'";

				$oCon->QueryT($sqlUpdaComp);

				$titulo='RETENCION DE INVENTARIO';
				break;
			case 7:
				$sqlUpdaComp = "update saefact set fact_email_clpv='$correo', fact_tlf_cliente='$telefono', fac_edi_sri=$usuario_web, fact_fedi_sri ='$fecha'  
				where fact_cod_fact=$id and fact_cod_empr=$id_empresa";
				$oCon->QueryT($sqlUpdaComp);
				$titulo='FACTURA EXPORTADORES';
				break;
			case 8:
				/*$sqlUpdaComp = "select fact_email_clpv, fact_tlf_cliente from saefact where fact_cod_fact=$id
				and fact_cod_empr=$id_empresa";
				$correo=consulta_string($sqlUpdaComp,'fact_email_clpv',$oCon,'');
				$telefono=consulta_string($sqlUpdaComp,'fact_tlf_cliente',$oCon,'');
				$secuencial=consulta_string($sqlUpdaComp,'fact_num_preimp',$oCon,'');¨*/
				$titulo='FACTURA FLORICOLA';
				break;
			case 9:
				/*$sqlUpdaComp = "select fact_email_clpv, fact_tlf_cliente from saefact where fact_cod_fact=$id
				and fact_cod_empr=$id_empresa";
				$correo=consulta_string($sqlUpdaComp,'fact_email_clpv',$oCon,'');
				$telefono=consulta_string($sqlUpdaComp,'fact_tlf_cliente',$oCon,'');
				$secuencial=consulta_string($sqlUpdaComp,'fact_num_preimp',$oCon,'');*/
				$titulo='FACTURA FLORICOLA EXPORTADOR';
				break;
			case 10:
				/*$sqlUpdaComp = "select fact_email_clpv, fact_tlf_cliente from saefact where fact_cod_fact=$id
				and fact_cod_empr=$id_empresa";
				$correo=consulta_string($sqlUpdaComp,'fact_email_clpv',$oCon,'');
				$telefono=consulta_string($sqlUpdaComp,'fact_tlf_cliente',$oCon,'');
				$secuencial=consulta_string($sqlUpdaComp,'fact_num_preimp',$oCon,'');*/
				$titulo='GUIA DE REMISION FLORICOLA';
				break;
			case 12:
				$sqlUpdaComp = "update saeminv set minv_edi_sri=$usuario_web, minv_fedi_sri='$fecha', minv_email_clpv='$correo'  where 
				minv_cod_empr = $id_empresa and 
				minv_cod_sucu = $id_sucursal  and 
				minv_cod_clpv = $clpv and 
				minv_fmov      = '$fec_emis' and
				minv_num_comp= $num_comp";
				$oCon->QueryT($sqlUpdaComp);

				

				$sqlUpdaComp = "
				select  a.asto_cod_sucu,  m.minv_fmov, m.minv_fac_prov, c.clpv_nom_clpv, minv_email_clpv, m.minv_num_comp, 
                        (select max(dire_dir_dire)
                        from saedire
                        where dire_cod_empr = c.clpv_cod_empr
                        and dire_cod_sucu = c.clpv_cod_sucu
                        and dire_cod_clpv = c.clpv_cod_clpv) as direccion, 
                        m.minv_cod_clpv, m.minv_ser_docu, m.minv_aprob_liqu, m.minv_clav_sri,
                        a.asto_cod_ejer, m.minv_erro_sri, c.clv_con_clpv, m.minv_cod_tran, c.clpv_ruc_clpv, 
                        (select max(tlcp_tlf_tlcp)
                        from saetlcp
                        where tlcp_cod_empr = c.clpv_cod_empr
                        and tlcp_cod_sucu = c.clpv_cod_sucu
                        and tlcp_cod_clpv = c.clpv_cod_clpv) as telefono,
                        minv_con_iva,
                        minv_sin_iva,
                        (minv_iva_valo) as valor_iva,
                        minv_tot_minv,
                        COALESCE(minv_val_noi, '0') as no_ojeto_iva,
                        COALESCE(minv_val_exe, '0') as exento_iva,
                        m.minv_cod_fpagop, ( m.minv_fec_entr - m.minv_fmov ) as dias_plazo
                        
                from  saeasto a , saeminv m , saeclpv c, saedmov d
                where   a.asto_cod_asto = m.minv_tran_minv and
                        a.asto_cod_empr = m.minv_cod_empr and
                        a.asto_cod_sucu = m.minv_cod_sucu and		
                        a.asto_cod_ejer = m.minv_cod_ejer and
                        c.clpv_cod_clpv = m.minv_cod_clpv and
                        c.clpv_cod_empr = m.minv_cod_empr and 
                        d.dmov_cod_empr = m.minv_cod_empr and
                        d.dmov_cod_sucu = m.minv_cod_sucu and		
                        d.dmov_cod_ejer = m.minv_cod_ejer and
                        d.dmov_num_comp = m.minv_num_comp and	
                        c.clpv_cod_empr = $id_empresa and
                        c.clpv_clopv_clpv = 'PV' and
                        a.asto_cod_empr = $id_empresa and
                        a.asto_est_asto <> 'AN' and
                        m.minv_cod_empr = $id_empresa and
						m.minv_cod_sucu = $id_sucursal  and 
                        m.minv_cod_clpv = $clpv and 
                        m.minv_elec_sn ='S' and
                        m.minv_fmov  = '$fec_emis' and
						m.minv_num_comp= $num_comp
                    group by 1,2,3,4,5,6,7,8,9,10,11,12, 13, 14,15,16,17, 18, 19, 20, 21,22, 23, 24, 25";
				$telf=consulta_string($sqlUpdaComp,'telefono',$oCon,'');


				$sqlUpdaComp = "update saetlcp set tlcp_tlf_tlcp='$telefono' 
				where tlcp_cod_clpv = $clpv and tlcp_tlf_tlcp='$telf'";
				$oCon->QueryT($sqlUpdaComp);
				
				$titulo='LIQUIDACION DE COMPRAS';
				break;	
		}


		$oCon->QueryT('COMMIT');
        $oReturn->script("alertSwal('$titulo Actualizada Correctamente', 'success');");
        $oReturn->script('cerrarModalEdita();');
		$oReturn->script('consultar_documento();');
        
    } catch (Exception $e) {
        $oCon->QueryT('ROLLBACK');
        $oReturn->alert($e->getMessage());
    }
	return $oReturn;
}


function genera_busqueda_cliente($campo_like = '', $tipo_doc='')
{
        //Definiciones
        global $DSN_Ifx, $DSN;

        session_start();

        $oIfx = new Dbo;
        $oIfx->DSN = $DSN_Ifx;
        $oIfx->Conectar();

        $oIfxA = new Dbo;
        $oIfxA->DSN = $DSN_Ifx;
        $oIfxA->Conectar();

        $oCon = new Dbo;
        $oCon->DSN = $DSN;
        $oCon->Conectar();

        $ifu = new Formulario;
        $ifu->DSN = $DSN_Ifx;

        $oReturn = new xajaxResponse();

        //VARIABLES DE SESION
        $idempresa = $_SESSION['U_EMPRESA'];
        $idsucursal = $_SESSION['U_SUCURSAL'];
        $usuario_informix = $_SESSION['U_USER_INFORMIX'];


        // VARIABLES
        $user_vendedor =  $_SESSION['U_VENDEDOR'];

        $perfil =  $_SESSION['U_PERFIL'];

        if ($perfil == 1 || $perfil == 2) {
                $fil_vend = '';
        } elseif ($perfil != 1 || $perfil != 2) {
                if ($user_vendedor != '' && !empty($user_vendedor)) {
                        $fil_vend = "and c.clpv_cod_vend='$user_vendedor'";
                } else {
                        $fil_vend = '';
                }
        }


        // Ciudad
        unset($arrayCiud);
        $pais_cod = $_SESSION['U_PAIS_COD'];
        $sql = "select ciud_cod_ciud, ciud_nom_ciud from saeciud where ciud_cod_pais = '$pais_cod'  order by 2";
        if ($oIfx->Query($sql)) {
                if ($oIfx->NumFilas() > 0) {
                        do {
                                $arrayCiud[$oIfx->f('ciud_cod_ciud')] = $oIfx->f('ciud_nom_ciud');
                        } while ($oIfx->SiguienteRegistro());
                }
        }
        $oIfx->Free();

		$fildoc='';
		//PARA RENTENCIONESSE TOMA EL FILTRO PORVEEDOR
		if(intval($tipo_doc)==5||intval($tipo_doc)==6||intval($tipo_doc)==12){
			$fildoc="and c.clpv_clopv_clpv = 'PV'";
		}
		else{
			$fildoc="and c.clpv_clopv_clpv = 'CL'";
		}


        $sql = "
    select * from (
		select *,
		consulta.clpv_nom_clpv || ' ' || consulta.clpv_ruc_clpv  || ' ' || consulta.telefono  || ' ' || consulta.email AS coincidencias
		from (select 
		c.clpv_cod_clpv,
		c.clpv_nom_clpv, 
		c.clpv_ruc_clpv, 
		c.clpv_cod_ciud, 
		c.clpv_cod_vend, 
		c.clpv_dsc_prpg,
		c.clpv_pre_ven,
		c.clpv_est_clpv,
		c.clpv_dsc_clpv,	
		c.clpv_pro_pago, 
		c.clv_con_clpv,
		(select COALESCE(max(dire_dir_dire),'') from saedire where
											dire_cod_empr = c.clpv_cod_empr and
											dire_cod_clpv = c.clpv_cod_clpv limit 1 ) as direccion,
		(select COALESCE(max(tlcp_tlf_tlcp),'') from saetlcp where
											tlcp_cod_empr = c.clpv_cod_empr and
											tlcp_cod_clpv = c.clpv_cod_clpv  ) as telefono,
		(select COALESCE(max(emai_ema_emai),'') from saeemai where
											emai_cod_empr = c.clpv_cod_empr and
											emai_cod_clpv = c.clpv_cod_clpv  ) as email
		FROM
		saeclpv c 
		where (c.clpv_cod_empr = $idempresa $fildoc ) and 
		(UPPER(c.clpv_ruc_clpv) like UPPER('%$campo_like%') or
		UPPER(c.clpv_nom_clpv) like UPPER('%$campo_like%'))
		order by c.clpv_nom_clpv limit 1000 ) as consulta ) as  consulta2
		where UPPER(coincidencias) like UPPER('%$campo_like%') limit 30";

        if ($oIfx->Query($sql)) {

                $sHtml = '<table id="tbclientes" class="table table-condensed table-responsive">';
                $sHtml .= '<thead>';

                $sHtml .= '<tr>
                    <th>No.</th>
                    <th>CODIGO</th>
                    <th>IDENTIFICACION</th>
                    <th>NOMBRE</th>
                    <th>EMAIL</th>
                    <th>TELEFONO</th>
                    <th>ESTADO</th>
                  </tr>';

                $sHtml .= '</thead>';
                $sHtml .= '<tbody>';

                if ($oIfx->NumFilas() > 0) {
                        $i = 1;
                        do {
                                $codigo = ($oIfx->f('clpv_cod_clpv'));
                                $paciente = ($oIfx->f('clpv_nom_clpv'));
                                $paciente_ruc = ($oIfx->f('clpv_ruc_clpv'));
                                $vendedor = $oIfx->f('clpv_cod_vend');
                                $contacto = $oIfx->f('clpv_cot_clpv');
                                $precio = round($oIfx->f('clpv_pre_ven'), 0);
                                $clpv_est_clpv = $oIfx->f('clpv_est_clpv');
                                $clv_con_clpv = $oIfx->f('clv_con_clpv');
                                $clpv_dsc_clpv = $oIfx->f('clpv_dsc_clpv');
                                $clpv_dsc_prpg = $oIfx->f('clpv_dsc_prpg');
                                $clpv_lim_cred = $oIfx->f('clpv_lim_cred');
                                $clpv_cod_ciud = $oIfx->f('clpv_cod_ciud');
                                $clpv_pro_pago = $oIfx->f('clpv_pro_pago');


                                $dire = $oIfx->f('direccion');
                                $telefono = $oIfx->f('telefono');
                                $email = $oIfx->f('email');
                                $celular = '';

                                if ($clpv_est_clpv == 'A') {
                                        $estado = 'ACTIVO';
                                        $color = 'blue';
                                } elseif ($clpv_est_clpv == 'P') {
                                        $estado = 'PENDIENTE';
                                        $color = 'green';
                                } elseif ($clpv_est_clpv == 'S') {
                                        $estado = 'SUSPENDIDO';
                                        $color = 'red';
                                } else {
                                        $estado = '--';
                                        $color = '';
                                }


                                $sHtml .= '<tr height="25"  
                            onclick="asignar_seg(' . $codigo . ', \'' . $paciente . '\', \'' . $paciente_ruc . '\', \'' . $dire . '\',
                                                \'' . $telefono . '\', \'' . $celular . '\', \'' . $vendedor . '\', \'' . $contacto . '\',  
						\'' . $precio . '\', \'' . $clpv_est_clpv . '\', \'' . $clv_con_clpv . '\',
                                                \'' . $clpv_dsc_clpv . '\', \'' . $clpv_dsc_prpg . '\',  \'' . $clpv_lim_cred . '\'
                                                ,  \'' . $arrayCiud[$clpv_cod_ciud] . '\',  \'' . $email . '\' ,' . $clpv_pro_pago . ')">
                            <td>' . $i . '</td>
                            <td>' . $codigo . '</td>       
                            <td>' . $paciente_ruc . '</td>                   
                            <td>' . $paciente . '</td>                       
                            <td>' . $email . '</td>                       
                            <td>' . $telefono . '</td>                       
                            <td>' . $estado . '</td> 
                            </tr>';
                                $i++;
                        } while ($oIfx->SiguienteRegistro());
                }
        }

        $sHtml .= '</tbody>';
        $sHtml .= '</table>';

        $oReturn->assign('divBusquedaSeg', 'innerHTML', $sHtml);
        $oReturn->assign('cliente_', 'focus()', '');
        $oReturn->script("jsRemoveWindowLoad();");
        $oReturn->script("initcli()");

        $oReturn->script("jsRemoveWindowLoad();");

        return $oReturn;
}

function genera_formulario($sAccion = '', $aForm = '')
{
	//Definiciones
	global $DSN_Ifx, $DSN;
	if (session_status() !== PHP_SESSION_ACTIVE) {
		session_start();
	}

	$oCon = new Dbo;
	$oCon->DSN = $DSN;
	$oCon->Conectar();

	$ifu = new Formulario;
	$ifu->DSN = $DSN_Ifx;

	$fu = new Formulario;
	$fu->DSN = $DSN;

	$oReturn = new xajaxResponse();

	//variables de session
	$idempresa = $_SESSION['U_EMPRESA'];
	$id_sucursal =  $_SESSION['U_SUCURSAL'];
	$usuario_web = $_SESSION['U_ID'];
	$perfil = $_SESSION['U_PERFIL'];

	///TABLA INFORMACION ADICIONAL -XML

	$sqlinf = "SELECT count(*) as conteo
    FROM INFORMATION_SCHEMA.COLUMNS
    WHERE  TABLE_NAME = 'saeixml' and table_schema='public'";
	$ctralter = consulta_string($sqlinf, 'conteo', $oCon, 0);
	if ($ctralter == 0) {
		$sqltb = "CREATE TABLE public.saeixml(
        id int4 NOT NULL GENERATED ALWAYS AS IDENTITY (
          INCREMENT 1
          MINVALUE  1
          MAXVALUE 2147483647
          START 1
          ),
		  ixml_cod_empr int,
		  ixml_tit_ixml varchar(255) COLLATE \"pg_catalog\".\"default\",
          ixml_det_ixml text COLLATE \"pg_catalog\".\"default\",
		  ixml_est_ixml varchar(1) COLLATE \"pg_catalog\".\"default\",
		  ixml_ord_ixml int,
          ixml_user_web   int,
          ixml_created_at timestamp,
          ixml_user_created int,
          ixml_updated_at timestamp,
          ixml_user_updated int, 
		  ixml_deleted_at timestamp,
          ixml_user_deleted int, 
		  ixml_est_deleted varchar(1) default 'S', 
          CONSTRAINT \"id_ixml\" PRIMARY KEY (\"id\")
          );";
		$oCon->QueryT($sqltb);
	}


	try {

		$lista_docu = '<option value="0">Seleccione una opcion..</option>'; 
		$sql = "SELECT id_time, documento from comercial.doc_time where estado = 'S' and tipo = 'E' AND documento != 'CORREOS MASIVOS' order by documento";
		$lista_docu = lista_boostrap_func($oCon, $sql, 1, 'id_time',  'documento' ); 

		$lista_sucu = '<option value="0">Seleccione una opcion..</option>'; 
		$sql = "SELECT sucu_cod_sucu, sucu_nom_sucu from saesucu where sucu_cod_empr = $idempresa order by sucu_nom_sucu";
		$lista_sucu .= lista_boostrap_func($oCon, $sql, $id_sucursal, 'sucu_cod_sucu',  'sucu_nom_sucu' ); 

		$sql = "SELECT codigo, estado from comercial.sri_estado order by 2";
		$lista_estado = lista_boostrap_func($oCon, $sql, 'N', 'codigo',  'estado' ); 

		//$fu->AgregarCampoListaSQL('tipo_documento', 'Documento|left', "select id_time, documento from comercial.doc_time where estado = 'S' and tipo = 'E' order by documento", true, 100, 150, true);

		//$ifu->AgregarCampoListaSQL('sucursal', 'Sucursal|left', "select sucu_cod_sucu, sucu_nom_sucu from saesucu where sucu_cod_empr = $idempresa order by sucu_nom_sucu", false, 100, 150, true);

		//$fu->AgregarCampoListaSQL('estado', 'Estado|left', "select codigo, estado from comercial.sri_estado order by 2", false, 100, 150, true);

		//$ifu->AgregarCampoFecha('fecha_inicio', 'Inicio|left', true, date('Y-m-01'), 100, 150, true);

		//$ifu->AgregarCampoFecha('fecha_final', 'Fin|left', true, date('Y-m-d'), 100, 150, true);

		$ifu->AgregarCampoTexto('cliente_nombre', 'Cliente/Proveedor|left', false, '', 'auto', '', true);
		$ifu->AgregarComandoAlEscribir('cliente_nombre', 'autocompletar( event, 1); form1.cliente_nombre.value=form1.cliente_nombre.value.toUpperCase();');

		$ifu->AgregarCampoOculto('cliente', 'Codigo|left', false, '', 'auto', '', true);


		//sql usuario
		if ($perfil == 1 || $perfil == 2) {
			$lista_usua = '<option value="0">Seleccione una opcion...</option>'; 
			$sql = "select usuario_id, usuario_user from comercial.usuario where empresa_id = $idempresa and perfil_id in (1,2,6,14) order by usuario_user";
			$lista_usua .= lista_boostrap_func($oCon, $sql, 0, 'usuario_id',  'usuario_user' ); 
		} else {
			$lista_usua = '<option value="0">Seleccione una opcion...</option>'; 
			$sql = "select usuario_id, usuario_user from comercial.usuario where usuario_id = $usuario_web";
			$lista_usua .= lista_boostrap_func($oCon, $sql, $usuario_web, 'usuario_id',  'usuario_user' ); 
		}
		/* if ($perfil == 1 || $perfil == 2) {
			$sql = "select usuario_id, usuario_user from comercial.usuario where empresa_id = $idempresa and perfil_id in (1,2,6,14) order by usuario_user";
			$fu->AgregarCampoListaSQL('usuario', 'User|left', $sql, false, 100, 150, true);
		} else {
			$sql = "select usuario_id, usuario_user from comercial.usuario where usuario_id = $usuario_web";
			$fu->AgregarCampoListaSQL('usuario', 'User|left', $sql, false, 100, 150, true);
			$fu->AgregarComandoAlPonerEnfoque('usuario', 'this.blur()');
			$fu->cCampos["usuario"]->xValor = $usuario_web;
		} */

		$fu->cCampos["tipo_documento"]->xValor = 1;
		$fu->cCampos["estado"]->xValor = 'N';

		$sHtml .= '<div class="table-responsive"><table class="table table-striped table-condensed table-responsive" style="width: 100%; margin-bottom:10px;" align="center">
					<tr>
						<td colspan="13" align="right">
							<a href="#" onclick="descargar()" class="text-primary" style="font-size: 12px;">
								<span class="glyphicon glyphicon-download-alt"></span>
								Ficha Tecnica
							</a>
							&nbsp;
							<a href="#" onclick="listaErrores()" class="text-primary" style="font-size: 12px;">
								<span class="glyphicon glyphicon-thumbs-up"></span>
								Ayuda
							</a>
						</td>
					</tr>
					<tr>
						<td colspan="13" align="center" class="bg-primary">DOCUMENTOS ELECTRONICOS - ESQUEMA OFFLINE</td>
					</tr>
					<tr>
						<td colspan="13" align="center" class="bg-info">Los campos con * son de ingreso obligatorio</td>
					</tr>';
		$sHtml .= '<tr>
						<td width="7%">* Documento</td>

						<td width="18%">
                            <select id="tipo_documento" name="tipo_documento" class="form-control select2 input-sm" onchange="valida_btn();" style="width:100%">
                                '.$lista_docu.'
                            </select>
						</td>
						<td width="7%">Sucursal</td>
						<td width="18%">
							<select id="sucursal" name="sucursal" class="form-control select2 input-sm" style="width:100%">
								'.$lista_sucu.'
							</select>
						</td>
						<td width="7%">* Inicio</td>
						<td width="18%">
							<input type="date" class="form-control input-sm" value='.date("Y-m-01").' id="fecha_inicio" name="fecha_inicio">
						</td> 
						<td width="7%">* Fin</td>
						<td width="18%">
							<input type="date" class="form-control input-sm" value='.date("Y-m-d").' id="fecha_final" name="fecha_final">
						</td>
					</tr>
					<tr>
						<td>Estado</td>
						<td>
							<select id="estado" name="estado" class="form-control select2 input-sm" style="width:100%" onchange="valida_btn_2()">
								'.$lista_estado.'
							</select>
						</td>
						
						<td>Usuario</td>
						<td>
							<select id="usuario" name="usuario" class="form-control select2 input-sm" style="width:100%">
								'.$lista_usua.'
							</select>
						</td>

						<td>' . $ifu->ObjetoHtmlLBL('cliente_nombre') . '</td>
						<td>' . $ifu->ObjetoHtml('cliente_nombre') . ' ' . $ifu->ObjetoHtml('cliente') . '</td>

						<td colspan="2" align="center">
							<div class="btn btn-primary btn-sm" onclick="genera_formulario();">
								<span class="glyphicon glyphicon-file"></span> Nuevo
							</div>
							
							<div class="btn btn-primary btn-sm" onclick="consultar_documento();">
								<span class="glyphicon glyphicon-search"></span> Buscar
							</div> 
							
							<div id="btn_firmar" class="btn btn-primary btn-sm" onclick="firmar_documento();">
								<span class="glyphicon glyphicon-list-alt"></span> Firmar
							</div>
							
							<div id="btn_enviar" class="btn btn-primary btn-sm" onclick="enviar_sri();">
								<span class="glyphicon glyphicon-transfer"></span> Enviar
							</div>
					</td>
					</tr>';
		$sHtml .= '</table></div>';

		$oReturn->assign("divFormularioCabecera", "innerHTML", $sHtml);
		$oReturn->assign("divFormularioCabecera", "innerHTML", $sHtml);
		$oReturn->assign("cliente", "value", '');
		$oReturn->assign("cliente_nombre", "placeholder", "DIGITE NOMBRE PRESIONE ENTER O F4;");

		$oReturn->script("
		$(\"#tipo_documento\").select2({});
        $(\"#estado\").select2({});
		$(\"#usuario\").select2({});
		$(\"#sucursal\").val($id_sucursal).select2({});
		");
		$oReturn->script("valida_btn()");
		$oReturn->script("valida_btn_2()");
	} catch (Exception $e) {
		$oReturn->alert($e->getMessage());
	}

	return $oReturn;
}

// CONSULTA DOCUMENTOS
function consultar_factVentOld($aForm = '')
{
	//Definiciones
	global $DSN_Ifx, $DSN;
	if (session_status() !== PHP_SESSION_ACTIVE) {
		session_start();
	}

	$oIfx = new Dbo;
	$oIfx->DSN = $DSN_Ifx;
	$oIfx->Conectar();

	$oIfxA = new Dbo;
	$oIfxA->DSN = $DSN_Ifx;
	$oIfxA->Conectar();

	$oCon = new Dbo;
	$oCon->DSN = $DSN;
	$oCon->Conectar();

	$ifu = new Formulario;
	$ifu->DSN = $DSN_Ifx;

	//VARIABLES GLOBALES
	$id_empresa = $_SESSION['U_EMPRESA'];
	$id_sucursal = $_SESSION['U_SUCURSAL'];
	unset($_SESSION['U_FACT_ENVIO']);

	$oReturn = new xajaxResponse();

	//DATOS DEL FORMULARIO
	$sucursal = $aForm['sucursal'];
	$estado = $aForm['estado'];
	$usuario = $aForm['usuario'];
	$fecha_inicio = fecha_informix($aForm['fecha_inicio']);
	$fecha_final = fecha_informix($aForm['fecha_final']);
	$campo = 0;
	$tipo_docu = "factura_venta";

	$cliente = $aForm['cliente'];
	$filclpv='';
	if(!empty($cliente)){
		$filclpv="and c.clpv_cod_clpv=$cliente";
	}
	try {

		//array sucursal
		$sqlSucursal = "select sucu_cod_sucu, sucu_nom_sucu 
						from saesucu where
						sucu_cod_empr = $id_empresa";
		if ($oIfx->Query($sqlSucursal)) {
			if ($oIfx->NumFilas() > 0) {
				unset($arraySucursal);
				do {
					$arraySucursal[$oIfx->f('sucu_cod_sucu')] = $oIfx->f('sucu_nom_sucu');
				} while ($oIfx->SiguienteRegistro());
			}
		}
		$oIfx->Free();

		//array estado sri
		$sqlEstadoSri = "select codigo, estado from comercial.sri_estado";
		if ($oCon->Query($sqlEstadoSri)) {
			if ($oCon->NumFilas() > 0) {
				unset($arrayEstadoSri);
				do {
					$arrayEstadoSri[$oCon->f('codigo')] = $oCon->f('estado');
				} while ($oCon->SiguienteRegistro());
			}
		}
		$oCon->Free();

		$tabla_reporte .= '<table id="tbfact"class="table table-striped table-bordered table-hover table-condensed table-responsive" style="width: 100%; margin-top: 10px;" align="center">';
		$tabla_reporte .= '<thead>';
		$tabla_reporte .= '<tr>
								<th colspan="17" >FACTURAS DE VENTA PENDIENTES DE AUTORIZAR</th>
							</tr>
							<tr style="font-size:90%;">
								<th>N.-</th>
								<th>Sucursal</th>
								<th>Fecha</th>
								<th>Cliente</th>
								<th>Ruc</th>
								<th>Serie</th>
								<th>Factura</th>
								<th>Total</th>
								<th>Email</th>
								<th>Editar</th>
								<th>RIDE</th>';
		if ($estado != 'N') {
			$tabla_reporte .= '<th>XML</th>';
		}

		$tabla_reporte .= '<th>Clave /  Autorizacion SRI</th>
								<th>Estado SRI</th>
								<th>Error</th>
								<th align="center"><input type="checkbox" onclick="marcar(this);" align="center"></th>
						  </tr>';
		$tabla_reporte .= '</thead>';
		$tabla_reporte .= '<tbody>';

		//sucursal sql

		$tmpSucursal = '';
		$tmpSucursal_1 = '';
		if (!empty($sucursal)) {
			$tmpSucursal = " and f.fact_cod_sucu = $sucursal";
			$tmpSucursal_1 = " and para_cod_sucu = $sucursal";
		}

		//estado sql
		$tmpEstado = '';
		if (!empty($estado)) {
			$tmpEstado = " and f.fact_aprob_sri = '$estado'";
		}

		//usuario sql
		$tmpUser = '';
		if (!empty($usuario)) {
			$tmpUser = " and f.fact_user_web = $usuario";
		}

		$sqlFactVent = "select f.fact_cod_fact, f.fact_cod_sucu, f.fact_fech_fact, f.fact_num_preimp, f.fact_ruc_clie, f.fact_nom_cliente, f.fact_iva, 
							f.fact_con_miva,   f.fact_sin_miva, f.fact_tot_fact, f.fact_erro_sri, f.fact_email_clpv  , f.fact_erro_sri, 
							f.fact_tlf_cliente,  f.fact_dir_clie, f.fact_nse_fact,  f.fact_cod_clpv ,  f.fact_con_miva, f.fact_sin_miva, 
							f.fact_iva, fact_ice,   f.fact_val_irbp, c.clv_con_clpv , f.fact_cm2_fact,  f.fact_opc_fact, f.fact_clav_sri,
							f.fact_cm1_fact, f.fact_aprob_sri
							from saefact f, saeclpv c where 
							c.clpv_cod_clpv = f.fact_cod_clpv and
							(f.fact_ruc_clie is not null OR f.fact_ruc_clie !='') and 
							--c.clpv_ruc_clpv = f.fact_ruc_clie and
							c.clpv_cod_empr = $id_empresa and
							c.clpv_clopv_clpv = 'CL' $filclpv and
							f.fact_elec_sn ='S' and
							f.fact_tip_vent <> '99' and 
							f.fact_fech_fact between '$fecha_inicio' and  '$fecha_final' and
							f.fact_cod_empr = $id_empresa  and  
							f.fact_fon_fact in ( select para_fac_cxc  from saepara where
													para_cod_empr = $id_empresa
													$tmpSucursal_1)   and
							f.fact_tip_vent = '18' and
							f.fact_est_fact <> 'AN'
							$tmpSucursal
							$tmpEstado
							$tmpUser
							order by f.fact_num_preimp";

		unset($array_fact);
		if ($oIfx->Query($sqlFactVent)) {
			if ($oIfx->NumFilas() > 0) {
				$i = 1;
				do {
					$fact_cod_fact = $oIfx->f("fact_cod_fact");
					$fecha_fact = fecha_mysql_func($oIfx->f("fact_fech_fact"));
					$secuencial = $oIfx->f("fact_num_preimp");
					$ruc = $oIfx->f("fact_ruc_clie");
					$cliente = $oIfx->f("fact_nom_cliente");
					$iva = $oIfx->f("fact_iva");
					$con_iva = $oIfx->f("fact_con_miva");
					$sin_iva = $oIfx->f("fact_sin_miva");
					$fact_tot_fact = $oIfx->f("fact_tot_fact");
					$correo = $oIfx->f("fact_email_clpv");
					$error = $oIfx->f("fact_erro_sri");
					$telefono = $oIfx->f("fact_tlf_cliente");
					$dire = $oIfx->f("fact_dir_clie");
					$fecha = $oIfx->f("fact_fech_fact");
					$nse_fact = $oIfx->f("fact_nse_fact");
					$cod_clpv = $oIfx->f("fact_cod_clpv");
					$fact_ice = $oIfx->f("fact_ice");
					$fact_val_irbp = $oIfx->f("fact_val_irbp");
					$clv_con_clpv = $oIfx->f("clv_con_clpv");
					$cod_almacen = trim($oIfx->f("fact_cm2_fact"));
					$orden_compra = $oIfx->f("fact_opc_fact");
					$fact_cod_sucu = $oIfx->f("fact_cod_sucu");
					$fact_clav_sri = $oIfx->f("fact_clav_sri");
					$fact_cm1_fact = trim($oIfx->f("fact_cm1_fact"));
					$fact_aprob_sri = $oIfx->f("fact_aprob_sri");

					$btnedit = '';
					$eventedit = 'onClick="edita_contactos(' . $fact_cod_fact . ', '.$id_empresa.','.$fact_cod_sucu.')"';

					$total = $con_iva + $sin_iva + $iva;


					$fact_clav_sri = generaClaveAccesoSri($oIfxA, '01', $id_empresa, $fact_cod_sucu, $oIfx->f("fact_fech_fact"), $nse_fact, $secuencial);

					if ($clv_con_clpv == '01') {                  // RUC
						$tipoIdentificacionComprador = '04';
					} elseif ($clv_con_clpv == '02') {            // CEDULA
						$tipoIdentificacionComprador = '05';
					} elseif ($clv_con_clpv == '03') {            // PASAPORTE
						$tipoIdentificacionComprador = '06';
					} elseif ($clv_con_clpv == '07') {            // CONSUMIDOR FINAL
						$tipoIdentificacionComprador = '07';
					} elseif ($clv_con_clpv == '04') {            // EXTRANJERIA
						$tipoIdentificacionComprador = '08';
					} elseif (empty($clv_con_clpv)) {
						$charid = strlen($ruc);


						if ($charid == 13) {
							$tipoIdentificacionComprador = '04';
						} elseif ($charid == 10) {
							$tipoIdentificacionComprador = '05';
						} elseif ($charid > 13) {
							$tipoIdentificacionComprador = '06';
						}
					}

					//$clave_acceso, $correo, $tipoDocu, $serial, $clpv, $num_fact, $ejer, $asto, $fec_emis, $idSucursal
					if ($fact_aprob_sri == 'S') {
						$btnedit = 'disabled';
						$eventedit = '';
						$img = '<div class="btn btn-danger btn-sm" onclick="enviar_mail(\'' . $fact_clav_sri . '\', \'' . $correo . '\', \'' . $tipo_docu . '\', 
																					' . $fact_cod_fact . ', ' . $cod_clpv . ',  \'' . $secuencial . '\',
																					' . $campo . ', ' . $campo . ', \'' . $fecha_fact . '\', ' . $fact_cod_sucu . ');">
									<span class=" glyphicon glyphicon-envelope"></span>
								</div>';
					} else {
						$ifu->AgregarCampoCheck($fact_cod_fact . '_f', 'S/N', false, 'N');
						$img =  $ifu->ObjetoHtml($fact_cod_fact . '_f');
					}

					$array_fact[] = array(
						$fact_cod_fact, $secuencial, $ruc, $cliente, $iva, $con_iva, $sin_iva, $fact_tot_fact, $correo, $telefono,
						$dire, $fecha, $nse_fact, $cod_clpv, $fact_ice, $fact_val_irbp, $tipoIdentificacionComprador,
						$cod_almacen, $orden_compra, $fact_cod_sucu, $fact_cm1_fact, $fact_clav_sri
					);

					$edit = '<span class="btn btn-warning btn-sm" title="Editar Cobro" value="Editar"  ' . $btnedit . ' ' . $eventedit . '>
								<i class="glyphicon glyphicon-pencil"></i>
					</span>';

					$tabla_reporte .= '<tr>';
					$tabla_reporte .= '<td align="center">' . $i . '</td>';
					$tabla_reporte .= '<td align="left">' . $arraySucursal[$fact_cod_sucu] . '</td>';
					$tabla_reporte .= '<td align="left">' . $fecha_fact . '</td>';
					$tabla_reporte .= '<td align="left">' . $cliente . '</td>';
					$tabla_reporte .= '<td align="left">' . $ruc . '</td>';
					$tabla_reporte .= '<td align="left">' . $nse_fact . '</td>';
					$tabla_reporte .= '<td align="left">' . $secuencial . '</td>';
					$tabla_reporte .= '<td align="right">' . $total  . '</td>';
					$tabla_reporte .= '<td align="left">' . $correo . '</td>';
					$tabla_reporte .= '<td align="center">' . $edit . '</td>';
					$tabla_reporte .= '<td align="center">
										<div class="btn btn-primary btn-sm" onclick="genera_documento(1, ' . $fact_cod_fact . ', \'' . $fact_clav_sri . '\', ' . $campo . ', ' . $campo . ', ' . $campo . ', ' . $campo . ', ' . $campo . ', ' . $fact_cod_sucu . ');">
											<span class="glyphicon glyphicon-print"></span>
											RIDE
										</div>
									</td>';
					if ($estado != 'N') {
						$contenido_xml = "../../modulos/sri_offline/documentoselectronicos/firmados/$fact_clav_sri.xml";
						if (file_exists($contenido_xml)) {
							$eti = '<a href="' . $contenido_xml . '" target="_blank" class="btn btn-success btn-sm" onclick="">
							<span class="glyphicon glyphicon-list"></span>
							XML</a>';
						} else {
							$eti = '<font color="red">NO GENERADO</font>';
						}
						$tabla_reporte .= '<td align="center">
                                           ' . $eti . '
                                        </td>';
					}
					$tabla_reporte .= '<td align="left">' . $fact_clav_sri . '</td>';
					$tabla_reporte .= '<td align="left" id="' . $fact_clav_sri . '">' . $arrayEstadoSri[$fact_aprob_sri] . '</td>';
					$tabla_reporte .= '<td align="left">' . $error . '</td>';
					$tabla_reporte .= '<td align="center">' . $img . '</td>';
					$tabla_reporte .= '</tr>';
					$i++;
				} while ($oIfx->SiguienteRegistro());
			} else {
				$tabla_reporte = 'SIN DATOS....';
			}
		}
		$tabla_reporte .= '<tbody>';
		$tabla_reporte .= '</table>';

		$_SESSION['U_FACT_ENVIO'] = $array_fact;

		$oReturn->script("jsRemoveWindowLoad()");
		$oReturn->assign("divFormularioDetalle", "innerHTML", $tabla_reporte);
		$oReturn->script("init('tbfact')");
		
	} catch (Exception $e) {
		$oReturn->alert($e->getMessage());
	}

	return $oReturn;
}

function consultar_factVent($aForm = '')
{
	//Definiciones
	global $DSN_Ifx, $DSN;
	if (session_status() !== PHP_SESSION_ACTIVE) {
		session_start();
	}

	$oIfx = new Dbo;
	$oIfx->DSN = $DSN_Ifx;
	$oIfx->Conectar();

	$oIfxA = new Dbo;
	$oIfxA->DSN = $DSN_Ifx;
	$oIfxA->Conectar();

	$oCon = new Dbo;
	$oCon->DSN = $DSN;
	$oCon->Conectar();

	$ifu = new Formulario;
	$ifu->DSN = $DSN_Ifx;

	//VARIABLES GLOBALES
	$id_empresa 	= $_SESSION['U_EMPRESA'];
	$id_sucursal 	= $_SESSION['U_SUCURSAL'];
	unset($_SESSION['U_FACT_ENVIO']);

	$oReturn = new xajaxResponse();

	//DATOS DEL FORMULARIO
	$sucursal 		= $aForm['sucursal'];
	$estado 		= $aForm['estado'];
	$estado_valida 	= $aForm['estado'];
	$usuario 		= $aForm['usuario'];
	$fecha_inicio 	= fecha_informix($aForm['fecha_inicio']);
	$fecha_final 	= fecha_informix($aForm['fecha_final']);
	$campo 			= 0;
	$tipo_docu 		= "factura_venta";

	$cliente 		= $aForm['cliente'];
	$filclpv='';
	if(!empty($cliente)){
		$filclpv="and c.clpv_cod_clpv=$cliente";
	}
	try {

		//array sucursal
		$sqlSucursal = "SELECT sucu_cod_sucu, sucu_nom_sucu 
						from saesucu where
						sucu_cod_empr = $id_empresa";
		if ($oIfx->Query($sqlSucursal)) {
			if ($oIfx->NumFilas() > 0) {
				unset($arraySucursal);
				do {
					$arraySucursal[$oIfx->f('sucu_cod_sucu')] = $oIfx->f('sucu_nom_sucu');
				} while ($oIfx->SiguienteRegistro());
			}
		}
		$oIfx->Free();

		//array estado sri
		$sqlEstadoSri = "SELECT codigo, estado from comercial.sri_estado";
		if ($oCon->Query($sqlEstadoSri)) {
			if ($oCon->NumFilas() > 0) {
				unset($arrayEstadoSri);
				do {
					$arrayEstadoSri[$oCon->f('codigo')] = $oCon->f('estado');
				} while ($oCon->SiguienteRegistro());
			}
		}
		$oCon->Free();

		$cabecera = array(
			
				'N.-',
				'Sucursal',
				'Fecha',
				'Cliente',
				'Ruc',
				'Serie',
				'Factura',
				'Total',
				'Email',
				'Editar',
				'RIDE',
				'XML', 
				'Clave /  Autorizacion SRI',
				'Estado SRI',
				'Error',
				'<input type="checkbox" onclick="marcar(this);" align="center">'
			
		);

		$contenido = array();

		//sucursal sql

		$tmpSucursal = '';
		$tmpSucursal_1 = '';
		if (!empty($sucursal)) {
			$tmpSucursal = " and f.fact_cod_sucu = $sucursal";
			$tmpSucursal_1 = " and para_cod_sucu = $sucursal";
		}

		//estado sql
		$tmpEstado = '';
		if (!empty($estado)) {
			$tmpEstado = " and f.fact_aprob_sri = '$estado'";
		}

		//usuario sql
		$tmpUser = '';
		if (!empty($usuario)) {
			$tmpUser = " and f.fact_user_web = $usuario";
		}

		$array_ruc_empr = [];
		$sql = "SELECT empr_cod_empr, empr_ruc_empr from saeempr";
		if ($oIfx->Query($sql)) {
			if ($oIfx->NumFilas() > 0) {
				do {
					$empr_cod_empr = $oIfx->f('empr_cod_empr');
					$empr_ruc_empr = $oIfx->f('empr_ruc_empr');

					$array_ruc_empr[$empr_cod_empr] = $empr_ruc_empr;
				} while ($oIfx->SiguienteRegistro());
			}
		}
		$oIfx->Free();

		$array_tip_sri = [];
		//datos de la sucursal para armar clave de acceso
		$sql = "SELECT  sucu_tip_ambi, sucu_tip_emis, sucu_cod_empr, sucu_cod_sucu from saesucu";
		if ($oIfx->Query($sql)) {
			if ($oIfx->NumFilas() > 0) {
				do {
					$sucu_tip_ambi = $oIfx->f('sucu_tip_ambi');
					$sucu_tip_emis = $oIfx->f('sucu_tip_emis');
					$sucu_cod_empr = $oIfx->f('sucu_cod_empr');
					$sucu_cod_sucu = $oIfx->f('sucu_cod_sucu');

					$array_tip_sri[$sucu_cod_empr][$sucu_cod_sucu] = [
						$sucu_tip_ambi, $sucu_tip_emis
					];
				} while ($oIfx->SiguienteRegistro());
			}
		}
		$oIfx->Free();

		$esquema_fact_elec = false;
		$array_fact_elect = [];
		$sql = "SELECT EXISTS (
					SELECT 1 
					FROM pg_namespace 
					WHERE nspname = 'facturacion_electronica'
				) AS valida_esquema";
		if ($oIfx->Query($sql)) {
			if ($oIfx->NumFilas() > 0) {
				do {
					$esquema_fact_elec = $oIfx->f('valida_esquema');
				} while ($oIfx->SiguienteRegistro());
			}
		}
		$oIfx->Free();

		if($esquema_fact_elec == '1'){
			$sql = "SELECT cod_fact, xml FROM facturacion_electronica.doc_facturas";
			if ($oIfx->Query($sql)) {
				if ($oIfx->NumFilas() > 0) {
					do {
						$cod_fact 	= $oIfx->f('cod_fact');
						$xml 		= $oIfx->f('xml');

						$array_fact_elect[$cod_fact] = $xml;
					} while ($oIfx->SiguienteRegistro());
				}
			}
			$oIfx->Free();
		}

		$sqlFactVent = "SELECT f.fact_cod_fact, f.fact_cod_sucu, f.fact_fech_fact, f.fact_num_preimp, f.fact_ruc_clie, f.fact_nom_cliente, f.fact_iva, 
							f.fact_con_miva,   f.fact_sin_miva, f.fact_tot_fact, f.fact_erro_sri, f.fact_email_clpv  , f.fact_erro_sri, 
							f.fact_tlf_cliente,  f.fact_dir_clie, f.fact_nse_fact,  f.fact_cod_clpv ,  f.fact_con_miva, f.fact_sin_miva, 
							f.fact_iva, fact_ice,   f.fact_val_irbp, c.clv_con_clpv , f.fact_cm2_fact,  f.fact_opc_fact, f.fact_clav_sri,
							f.fact_cm1_fact, f.fact_aprob_sri
							from saefact f, saeclpv c where 
							c.clpv_cod_clpv = f.fact_cod_clpv and
							(f.fact_ruc_clie is not null OR f.fact_ruc_clie !='') and 
							--c.clpv_ruc_clpv = f.fact_ruc_clie and
							c.clpv_cod_empr = $id_empresa and
							c.clpv_clopv_clpv = 'CL' $filclpv and
							f.fact_elec_sn ='S' and
							f.fact_tip_vent <> '99' and 
							f.fact_fech_fact between '$fecha_inicio' and  '$fecha_final' and
							f.fact_cod_empr = $id_empresa  and  
							f.fact_fon_fact in ( select para_fac_cxc  from saepara where
													para_cod_empr = $id_empresa
													$tmpSucursal_1)   and
							f.fact_tip_vent = '18' and
							f.fact_est_fact <> 'AN'
							$tmpSucursal
							$tmpEstado
							$tmpUser
							order by f.fact_cod_fact, f.fact_num_preimp";

		unset($array_fact);
		if ($oIfx->Query($sqlFactVent)) {
			if ($oIfx->NumFilas() > 0) {
				$i = 1;
				do {
					$fact_cod_fact = $oIfx->f("fact_cod_fact");
					$fecha_fact = fecha_mysql_func($oIfx->f("fact_fech_fact"));
					$secuencial = $oIfx->f("fact_num_preimp");
					$ruc = $oIfx->f("fact_ruc_clie");
					$cliente = $oIfx->f("fact_nom_cliente");
					$iva = $oIfx->f("fact_iva");
					$con_iva = $oIfx->f("fact_con_miva");
					$sin_iva = $oIfx->f("fact_sin_miva");
					$fact_tot_fact = $oIfx->f("fact_tot_fact");
					$correo = $oIfx->f("fact_email_clpv");
					$error = $oIfx->f("fact_erro_sri");
					$telefono = $oIfx->f("fact_tlf_cliente");
					$dire = $oIfx->f("fact_dir_clie");
					$fecha = $oIfx->f("fact_fech_fact");
					$nse_fact = $oIfx->f("fact_nse_fact");
					$cod_clpv = $oIfx->f("fact_cod_clpv");
					$fact_ice = $oIfx->f("fact_ice");
					$fact_val_irbp = $oIfx->f("fact_val_irbp");
					$clv_con_clpv = $oIfx->f("clv_con_clpv");
					$cod_almacen = trim($oIfx->f("fact_cm2_fact"));
					$orden_compra = $oIfx->f("fact_opc_fact");
					$fact_cod_sucu = $oIfx->f("fact_cod_sucu");
					$fact_clav_sri = $oIfx->f("fact_clav_sri");
					$fact_cm1_fact = trim($oIfx->f("fact_cm1_fact"));
					$fact_aprob_sri = $oIfx->f("fact_aprob_sri");

					$btnedit = '';
					$eventedit = 'onClick="edita_contactos(' . $fact_cod_fact . ', '.$id_empresa.','.$fact_cod_sucu.')"';

					$total = $con_iva + $sin_iva + $iva;

					$empr_ruc_empr = '';
					if(isset($array_ruc_empr[$id_empresa])){
						$empr_ruc_empr = $array_ruc_empr[$id_empresa];
					}

					$sucu_tip_ambi = '';
					$sucu_tip_emis = '';
					if(isset($array_tip_sri[$id_empresa][$fact_cod_sucu])){
						$sucu_tip_ambi = $array_tip_sri[$id_empresa][$fact_cod_sucu][0];
						$sucu_tip_emis = $array_tip_sri[$id_empresa][$fact_cod_sucu][1];
					}
					
					//$fact_clav_sri = generaClaveAccesoSri($oIfxA, '01', $id_empresa, $fact_cod_sucu, $oIfx->f("fact_fech_fact"), $nse_fact, $secuencial);
					$fact_clav_sri = generaClaveAccesoSri2($empr_ruc_empr, $sucu_tip_ambi, $sucu_tip_emis, '01', $oIfx->f("fact_fech_fact"), $nse_fact, $secuencial);

					if ($clv_con_clpv == '01') {                  // RUC
						$tipoIdentificacionComprador = '04';
					} elseif ($clv_con_clpv == '02') {            // CEDULA
						$tipoIdentificacionComprador = '05';
					} elseif ($clv_con_clpv == '03') {            // PASAPORTE
						$tipoIdentificacionComprador = '06';
					} elseif ($clv_con_clpv == '07') {            // CONSUMIDOR FINAL
						$tipoIdentificacionComprador = '07';
					} elseif ($clv_con_clpv == '04') {            // EXTRANJERIA
						$tipoIdentificacionComprador = '08';
					} elseif (empty($clv_con_clpv)) {
						$charid = strlen($ruc);


						if ($charid == 13) {
							$tipoIdentificacionComprador = '04';
						} elseif ($charid == 10) {
							$tipoIdentificacionComprador = '05';
						} elseif ($charid > 13) {
							$tipoIdentificacionComprador = '06';
						}
					}

					

					$array_fact = array();
					$array_fact_json = "";
					$array_fact_b64 = "";

					$array_fact = array(
						$fact_cod_fact, $secuencial, $ruc, $cliente, $iva, $con_iva, $sin_iva, $fact_tot_fact, $correo, $telefono,
						$dire, $fecha, $nse_fact, $cod_clpv, $fact_ice, $fact_val_irbp, $tipoIdentificacionComprador,
						$cod_almacen, $orden_compra, $fact_cod_sucu, $fact_cm1_fact, $fact_clav_sri
					);

					$array_fact_json = json_encode($array_fact);
					$array_fact_b64 = base64_encode($array_fact_json);

					//$clave_acceso, $correo, $tipoDocu, $serial, $clpv, $num_fact, $ejer, $asto, $fec_emis, $idSucursal
					if ($fact_aprob_sri == 'S') {
						$btnedit = 'disabled';
						$eventedit = '';
						$img = '<div class="btn btn-danger btn-sm" onclick="enviar_mail(\'' . $fact_clav_sri . '\', \'' . $correo . '\', \'' . $tipo_docu . '\', 
																					' . $fact_cod_fact . ', ' . $cod_clpv . ',  \'' . $secuencial . '\',
																					' . $campo . ', ' . $campo . ', \'' . $fecha_fact . '\', ' . $fact_cod_sucu . ');">
									<span class=" glyphicon glyphicon-envelope"></span>
								</div>';
					} else {
						/* $ifu->AgregarCampoCheck($fact_cod_fact . '_f', 'S/N', false, 'N');
						$img =  $ifu->ObjetoHtml($fact_cod_fact . '_f'); */

						$img = '<div id="div_check_'.$fact_cod_fact.'"><input id="check_'.$fact_cod_fact.'" type="checkbox" name="documentos" align="center" value=\'' . $array_fact_b64 . '\'>';
					}

					$edit = '<span class="btn btn-warning btn-xs" title="Editar Cobro" value="Editar"  ' . $btnedit . ' ' . $eventedit . '>
								<i class="glyphicon glyphicon-pencil"></i>
					</span>';

					$estado_sri = '';
					$estado_sri = '<div id="' . $fact_clav_sri . '"> '.$arrayEstadoSri[$fact_aprob_sri].' </div>';

					$eti = '<font color="red">NO GENERADO</font>';
					if ($estado != 'N') {
						$contenido_xml ="../../modulos/sri_offline/documentoselectronicos/firmados/$fact_clav_sri.xml";
						if (file_exists($contenido_xml)) {
							$eti = '<a href="' . $contenido_xml . '" target="_blank" class="btn btn-success btn-sm" onclick="">
							<span class="glyphicon glyphicon-list"></span>
							XML</a>';
						}

						if(isset($array_fact_elect[$fact_cod_fact])){

							$xml_b64 = $array_fact_elect[$fact_cod_fact];

							$eti = '<button class="btn btn-success btn-sm" onclick="descarga_xml_b64(\'' . $xml_b64 . '\',\'' . $fact_clav_sri . '.xml\')">
										<span class="glyphicon glyphicon-list"></span>XML
									</button>';
						}
					}

					$datos_indi = array(
						$i,
						$arraySucursal[$fact_cod_sucu],
						$fecha_fact,
						$cliente,
						$ruc,
						$nse_fact,
						$secuencial,
						$total,
						$correo,
						$edit,
						'<div class="btn btn-primary btn-xs" onclick="genera_documento(1, ' . $fact_cod_fact . ', \'' . $fact_clav_sri . '\', ' . $campo . ', ' . $campo . ', ' . $campo . ', ' . $campo . ', ' . $campo . ', ' . $fact_cod_sucu . ');">
							<span class="glyphicon glyphicon-print"></span> RIDE
						</div>',
						$eti,
						$fact_clav_sri,
						$estado_sri,
						$error,
						$img
					);

					array_push($contenido, $datos_indi);
					
					$i++;
				} while ($oIfx->SiguienteRegistro());
			} else {
				throw new Exception("Sin datos para mostrar");
			}
		}

		$nombre_archivo = 'FACTURAS_VENTA';

        foreach ($contenido as $elemento) {
            $elemento_sin_tabulaciones = str_replace("\t", "", $elemento);
            $elemento_sin_tabulaciones = preg_replace('/[\x00-\x1F]/u', '', $elemento_sin_tabulaciones);
            $contenido_2[] = $elemento_sin_tabulaciones;
        }

        $datos_envio = array(
            "cabecera" => array($cabecera),
            "contenido" => $contenido_2,
            "pie" => "",
            "nombre_archivo" => $nombre_archivo
        );

        if(count($contenido_2) > 0){
            $contenido_json = json_encode($datos_envio);

            $contenido_json_64 = base64_encode($contenido_json);

            $oReturn->script('generar_reporte(\''.$contenido_json_64.'\');');

			if($estado_valida == 'N' || $estado_valida == 'F'){
				$num_documentos = count($contenido_2);

				$msn = "Documentos pendientes <br> por autorizar";
				if($estado_valida == 'F'){
					$msn = "Documentos firmados <br> por autorizar";
				}

				$icono = '<i class="fa fa-solid fa-hourglass-half fa-5x"></i>';
				$oReturn->assign("num_doc_estatus", "innerHTML", $num_documentos);
				$oReturn->assign("nombre_docu_estado", "innerHTML", $msn);
				$oReturn->assign("icono_img", "innerHTML", $icono);
				$oReturn->script('$("#div_pending").show()');
				$oReturn->script('$("#div_dashboard").show()');
				
			}

			if($estado_valida == 'S' ){
				$num_documentos = count($contenido_2);

				$msn = "Documentos autorizados";

				$icono = '<i class="fa fa-solid fa-clipboard-check fa-5x"></i>';
				$oReturn->assign("num_doc_estatus", "innerHTML", $num_documentos);
				$oReturn->assign("nombre_docu_estado", "innerHTML", $msn);
				$oReturn->assign("icono_img", "innerHTML", $icono);
				$oReturn->script('$("#div_pending").show()');
				$oReturn->script('$("#div_dashboard").show()');
				
			}
        }else{
			throw new Exception("Sin datos para mostrar");
		}
		
	} catch (Exception $e) {
		$oReturn->alert($e->getMessage());
		$oReturn->assign("divFormularioDetalle", "innerHTML", '');
		$oReturn->script("jsRemoveWindowLoad();");
		$oReturn->script('$("#div_pending").hide()');
		$oReturn->script('$("#div_dashboard").hide()');
	}

	return $oReturn;
}

function consultar_notaDebi($aForm = '')
{
	//Definiciones
	global $DSN_Ifx, $DSN;
	if (session_status() !== PHP_SESSION_ACTIVE) {
		session_start();
	}

	$oIfx = new Dbo;
	$oIfx->DSN = $DSN_Ifx;
	$oIfx->Conectar();

	$oIfxA = new Dbo;
	$oIfxA->DSN = $DSN_Ifx;
	$oIfxA->Conectar();


	$ifu = new Formulario;
	$ifu->DSN = $DSN_Ifx;

	//VARIABLES GLOBALES
	$id_empresa = $_SESSION['U_EMPRESA'];
	$id_sucursal = $_SESSION['U_SUCURSAL'];
	unset($_SESSION['U_FACT_ENVIO']);

	$oReturn = new xajaxResponse();

	//DATOS DEL FORMULARIO
	$fecha_inicio = fecha_informix($aForm['fecha_inicio']);
	$fecha_final = fecha_informix($aForm['fecha_final']);
	$estado = $aForm['estado'];

	//estado sql
	$tmpEstado = '';
	if (!empty($estado)) {
		$tmpEstado = " and f.fact_aprob_sri = '$estado'";
	}

	//array sucursal
	$sqlSucursal = "select sucu_cod_sucu, sucu_nom_sucu 
					from saesucu where
					sucu_cod_empr = $id_empresa";
	if ($oIfx->Query($sqlSucursal)) {
		if ($oIfx->NumFilas() > 0) {
			unset($arraySucursal);
			do {
				$arraySucursal[$oIfx->f('sucu_cod_sucu')] = $oIfx->f('sucu_nom_sucu');
			} while ($oIfx->SiguienteRegistro());
		}
	}
	$oIfx->Free();

	$sqlTipo = "select  sucu_tip_ambi, sucu_tip_emis  from saesucu where
                    sucu_cod_sucu = $id_sucursal ";
	if ($oIfx->Query($sqlTipo)) {
		if ($oIfx->NumFilas() > 0) {
			$ambiente = $oIfx->f('sucu_tip_ambi');
			$tip_emis = $oIfx->f('sucu_tip_emis');
		}
	}
	$oIfx->Free();

	$sql = "select empr_nom_empr, empr_ruc_empr , empr_dir_empr, empr_conta_sn,
                empr_num_resu from saeempr where empr_cod_empr = $id_empresa ";
	if ($oIfx->Query($sql)) {
		if ($oIfx->NumFilas() > 0) {
			$nombre_empr = trim($oIfx->f('empr_nom_empr'));
			$ruc_empr = $oIfx->f('empr_ruc_empr');
			$dir_empr = trim($oIfx->f('empr_dir_empr'));
			$conta_sn = $oIfx->f('empr_conta_sn');
			$empr_num_resu = $oIfx->f('empr_num_resu');
		}
	}
	$oIfx->Free();

	$campo = 0;
	$num8 = 12345678;

	$cliente = $aForm['cliente'];
	$filclpv='';
	if(!empty($cliente)){
		$filclpv="and c.clpv_cod_clpv=$cliente";
	}

	$tabla_reporte .= '<table id="tbndebi"class="table table-striped table-bordered table-hover table-condensed table-responsive" style="width: 100%; margin-top: 10px;" align="center">';
	$tabla_reporte .= '<thead>';
	$tabla_reporte .= '<tr>
								<th colspan="17" >NOTAS DE DEBITO PENDIENTES DE AUTORIZAR</th>
							</tr>
							<tr>
								<th>N.-</th>
								<th>Sucursal</th>
								<th>Fecha</th>
								<th>Cliente</th>
								<th>Ruc</th>
								<th>Serie</th>
								<th>Nota Debito</th>
								<th>Total</th>
								<th>Email</th>
								<th>Editar</th>
								<th>RIDE</th>';
	if ($estado != 'N') {
		$tabla_reporte .= '<th>XML</th>';
	}

	$tabla_reporte .= '<th>Clave /  Autorizacion SRI</th>
								<th>Estado SRI</th>
								<th>Error</th>
								<th align="center"><input type="checkbox" onclick="marcar(this);" align="center"></th>
						  </tr>';
	$tabla_reporte .= '</thead>';
	$tabla_reporte .= '<tbody>';


	$sqlNotaDebi = "select f.fact_cod_fact, f.fact_fech_fact, f.fact_num_preimp, f.fact_ruc_clie, f.fact_nom_cliente, f.fact_iva, 
                    f.fact_con_miva,   f.fact_sin_miva, f.fact_tot_fact, f.fact_erro_sri, f.fact_email_clpv  , f.fact_erro_sri, 
                    f.fact_tlf_cliente,  f.fact_dir_clie, f.fact_nse_fact,  f.fact_cod_clpv ,  f.fact_con_miva, f.fact_sin_miva, 
                    f.fact_iva, fact_ice,   f.fact_val_irbp, c.clv_con_clpv, f.fact_aux_preimp,  f.fact_fec_emis_aux, f.fact_cod_sucu
                    from saefact f, saeclpv c where 
                    c.clpv_cod_clpv = f.fact_cod_clpv $filclpv and
                    f.fact_fech_fact between '$fecha_inicio' and '$fecha_final' and
                    f.fact_cod_empr = $id_empresa and 
                    f.fact_cod_sucu = $id_sucursal and 
                    f.fact_est_fact <> 'AN' and
                    f.fact_fon_fact = 'NDB' and
                    f.fact_tip_vent != '99' 
					$tmpEstado
					order by fact_num_preimp";
	//$oReturn->alert($sqlFactVent);
	$i = 1;
	unset($array_nd);
	if ($oIfx->Query($sqlNotaDebi)) {
		if ($oIfx->NumFilas() > 0) {
			do {

				$fact_cod_fact = $oIfx->f("fact_cod_fact");
				$fecha_fact = fecha_mysql_func($oIfx->f("fact_fech_fact"));
				$secuencial = $oIfx->f("fact_num_preimp");
				$ruc = $oIfx->f("fact_ruc_clie");
				$cliente = $oIfx->f("fact_nom_cliente");
				$iva = $oIfx->f("fact_iva");
				$con_iva = $oIfx->f("fact_con_miva");
				$sin_iva = $oIfx->f("fact_sin_miva");
				$total = $oIfx->f("fact_tot_fact");
				$correo = $oIfx->f("fact_email_clpv");
				$error = $oIfx->f("fact_erro_sri");
				$telefono = $oIfx->f("fact_tlf_cliente");
				$dire = $oIfx->f("fact_dir_clie");
				$fecha = $oIfx->f("fact_fech_fact");
				$nse_fact = $oIfx->f("fact_nse_fact");
				$cod_clpv = $oIfx->f("fact_cod_clpv");
				$con_miva = $oIfx->f("fact_con_miva");
				$sin_miva = $oIfx->f("fact_sin_miva");
				$fact_iva = $oIfx->f("fact_iva");
				$fact_ice = $oIfx->f("fact_ice");
				$fact_val_irbp = $oIfx->f("fact_val_irbp");
				$clv_con_clpv = $oIfx->f("clv_con_clpv");
				$clv_con_clpv = $oIfx->f("clv_con_clpv");
				$fact_aux_preimp = $oIfx->f("fact_aux_preimp");
				$fact_fec_emis_aux = $oIfx->f("fact_fec_emis_aux");
				$id_sucursal = $oIfx->f("fact_cod_sucu");

				$btnedit = '';
				$eventedit = 'onClick="edita_contactos(' . $fact_cod_fact . ', '.$id_empresa.')"';

				if ($clv_con_clpv == '01') {                  //RUC
					$tipoIdentificacionComprador = '04';
				} elseif ($clv_con_clpv == '02') {            // CEDULA
					$tipoIdentificacionComprador = '05';
				} elseif ($clv_con_clpv == '03') {            // PASAPORTE
					$tipoIdentificacionComprador = '06';
				} elseif ($clv_con_clpv == '07') {            // CONSUMIDOR FINAL
					$tipoIdentificacionComprador = '07';
				} elseif ($clv_con_clpv == '04') {            // EXTRANJERIA
					$tipoIdentificacionComprador = '08';
				} elseif (empty($clv_con_clpv)) {
					$charid = strlen($ruc);


					if ($charid == 13) {
						$tipoIdentificacionComprador = '04';
					} elseif ($charid == 10) {
						$tipoIdentificacionComprador = '05';
					} elseif ($charid > 13) {
						$tipoIdentificacionComprador = '06';
					}
				}

				$ifu->AgregarCampoCheck($fact_cod_fact . '_nd', 'S/N', false, 'N');

				$fact_clav_sri = generaClaveAccesoSri($oIfxA, '05', $id_empresa, $id_sucursal, $fecha, $nse_fact, $secuencial);
				$clave_acceso = $fact_clav_sri;

				$array_nd[] = array(
					$fact_cod_fact, $secuencial, $ruc, $cliente, $iva, $con_iva, $sin_iva, $total, $correo, $telefono,
					$dire, $fecha, $nse_fact, $cod_clpv, $fact_ice, $fact_val_irbp, $tipoIdentificacionComprador, $fact_aux_preimp, $fact_fec_emis_aux, $fact_clav_sri, $id_sucursal
				);

				if ($estado == 'S') 
				{
					$btnedit = 'disabled';
					$eventedit = '';

				}
				$edit = '<span class="btn btn-warning btn-sm" title="Editar Cobro" value="Editar"  ' . $btnedit . ' ' . $eventedit . '>
				<i class="glyphicon glyphicon-pencil"></i>
				</span>';

				$tabla_reporte .= '<tr>';
				$tabla_reporte .= '<td align="right">' . $i . '</td>';
				$tabla_reporte .= '<td align="left">' . $arraySucursal[$id_sucursal] . '</td>';
				$tabla_reporte .= '<td align="right">' . $fecha_fact . '</td>';
				$tabla_reporte .= '<td align="left">' . $cliente . '</td>';
				$tabla_reporte .= '<td align="right">' . $ruc . '</td>';
				$tabla_reporte .= '<td align="right">' . $nse_fact . '</td>';
				$tabla_reporte .= '<td align="left">' . $secuencial . '</td>';
				$tabla_reporte .= '<td align="right">' . ($total  + $fact_val_irbp) . '</td>';
				$tabla_reporte .= '<td align="right">' . $correo . '</td>';
				$tabla_reporte .= '<td align="center">' . $edit . '</td>';
				$tabla_reporte .= '<td align="center">
										<div class="btn btn-primary btn-sm" onclick="genera_documento(2, ' . $fact_cod_fact . ', \'' . $fact_clav_sri . '\', ' . $campo . ', ' . $campo . ', ' . $campo . ', ' . $campo . ', ' . $campo . ', ' . $id_sucursal . ');">
											<span class="glyphicon glyphicon-print"></span>
											RIDE
										</div>
									</td>';
				if ($estado != 'N') {
					$contenido_xml = "../../modulos/sri_offline/documentoselectronicos/firmados/$fact_clav_sri.xml";
					if (file_exists($contenido_xml)) {
						$eti = '<a href="' . $contenido_xml . '" target="_blank" class="btn btn-success btn-sm" onclick="">
							<span class="glyphicon glyphicon-list"></span>
							XML</a>';
					} else {
						$eti = '<font color="red">NO GENERADO</font>';
					}
					$tabla_reporte .= '<td align="center">
                                           ' . $eti . '
                                        </td>';
				}
				$tabla_reporte .= '<td align="left">' . $fact_clav_sri . '</td>';
				$tabla_reporte .= '<td align="right" id="' . $clave_acceso . '">N</td>';
				$tabla_reporte .= '<td align="right">' . $error . '</td>';
				$tabla_reporte .= '<td align="right">' . $ifu->ObjetoHtml($fact_cod_fact . '_nd') . '</td>';
				$tabla_reporte .= '</tr>';
				$i++;
			} while ($oIfx->SiguienteRegistro());

			$tabla_reporte .= '</tbody>';
			$tabla_reporte .= '</table>';
		} else {
			$tabla_reporte = 'SIN DATOS....';
		}
	}

	$_SESSION['U_FACT_ENVIO'] = $array_nd;

	$oReturn->assign("divFormularioDetalle", "innerHTML", $tabla_reporte);
	$oReturn->script("init('tbndebi')");


	return $oReturn;
}

function consultar_notaCred($aForm = '')
{
	//Definiciones
	global $DSN_Ifx, $DSN;
	if (session_status() !== PHP_SESSION_ACTIVE) {
		session_start();
	}

	$oIfx = new Dbo;
	$oIfx->DSN = $DSN_Ifx;
	$oIfx->Conectar();

	$oIfxA = new Dbo;
	$oIfxA->DSN = $DSN_Ifx;
	$oIfxA->Conectar();

	$oCon = new Dbo;
	$oCon->DSN = $DSN;
	$oCon->Conectar();

	$ifu = new Formulario;
	$ifu->DSN = $DSN_Ifx;

	$oReturn = new xajaxResponse();

	//VARIABLES GLOBALES
	unset($_SESSION['U_FACT_ENVIO']);
	$id_empresa = $_SESSION['U_EMPRESA'];

	//DATOS DEL FORMULARIO
	$sucursal = $aForm['sucursal'];
	$estado = $aForm['estado'];
	$fecha_inicio = fecha_informix($aForm['fecha_inicio']);
	$fecha_final = fecha_informix($aForm['fecha_final']);
	$usuario = $aForm['usuario'];
	$campo = 0;
	$tipo_docu = "nota_credito";

	$cliente = $aForm['cliente'];
	$filclpv='';
	if(!empty($cliente)){
		$filclpv="and c.clpv_cod_clpv=$cliente";
	}

	try {

		$sqlSucursal = "select sucu_cod_sucu, sucu_nom_sucu 
						from saesucu where
						sucu_cod_empr = $id_empresa";
		if ($oIfx->Query($sqlSucursal)) {
			if ($oIfx->NumFilas() > 0) {
				unset($arraySucursal);
				do {
					$arraySucursal[$oIfx->f('sucu_cod_sucu')] = $oIfx->f('sucu_nom_sucu');
				} while ($oIfx->SiguienteRegistro());
			}
		}
		$oIfx->Free();

		//array estado sri
		$sqlEstadoSri = "select codigo, estado from comercial.sri_estado";
		if ($oCon->Query($sqlEstadoSri)) {
			if ($oCon->NumFilas() > 0) {
				unset($arrayEstadoSri);
				do {
					$arrayEstadoSri[$oCon->f('codigo')] = $oCon->f('estado');
				} while ($oCon->SiguienteRegistro());
			}
		}
		$oCon->Free();

		$tabla_reporte .= '<table id="tbncre"class="table table-striped table-bordered table-hover table-condensed table-responsive" style="width: 100%; margin-top: 10px;" align="center">';
		$tabla_reporte .= '<thead>';
		$tabla_reporte .= '<tr>
								<th colspan="16" >NOTAS DE CREDITO PENDIENTES DE AUTORIZAR</th>
							</tr>
							<tr>
								<th>N.-</th>
								<th>Sucursal</th>
								<th>Fecha</th>
								<th>Cliente</th>
								<th>Ruc</th>
								<th>Serie</th>
								<th>Nota Credito</th>
								<th>Total</th>
								<th>Email</th>
								<th>Editar</th>
								<th>RIDE</th>';
		if ($estado != 'N') {
			$tabla_reporte .= '<th>XML</th>';
		}
		$tabla_reporte .= '<th>Clave /  Autorizacion SRI</th>
								<th>Estado SRI</th>
								<th>Error</th>
								<th align="center"><input type="checkbox" onclick="marcar(this);" align="center"></th>
						  </tr>';
		$tabla_reporte .= '</thead>';
		$tabla_reporte .= '<tbody>';


		//sucursal sql
		$tmpSucursal = '';
		if (!empty($sucursal)) {
			$tmpSucursal = " and n.ncre_cod_sucu = $sucursal";
		}

		//estado sql
		$tmpEstado = '';
		if (!empty($estado)) {
			$tmpEstado = " and n.ncre_aprob_sri = '$estado'";
		}

		//usuario sql
		$tmpUser = '';
		if (!empty($usuario)) {
			$tmpUser = " and n.ncre_user_web = $usuario";
		}

		$sqlNotaCred = "select n.ncre_cod_ncre, n.ncre_fech_fact, n.ncre_num_preimp, n.ncre_ruc_clie, 
							n.ncre_nom_cliente, n.ncre_iva, n.ncre_con_miva, 
							n.ncre_sin_miva, n.ncre_erro_sri, n.ncre_email_clpv , 
							n.ncre_tot_fact, n.ncre_irbp, c.clv_con_clpv, n.ncre_irbp, n.ncre_tlf_cliente,
							n.ncre_dir_clie , n.ncre_nse_ncre,  n.ncre_cod_clpv, 
							n.ncre_cod_fact, n.ncre_ice,  n.ncre_otr_fact,   n.ncre_fle_fact,
							n.ncre_cm1_ncre, n.ncre_clav_sri, n.ncre_cod_sucu, n.ncre_aprob_sri,ncre_dsg_valo,ncre_cod_aux,
							n.ncre_fech_docu
							from saencre n , saeclpv c where 
							c.clpv_cod_clpv = n.ncre_cod_clpv and
							C.clpv_ruc_clpv = n.ncre_ruc_clie and                                 
							c.clpv_cod_empr = $id_empresa and
							c.clpv_clopv_clpv = 'CL' $filclpv and
							n.ncre_fech_fact between '$fecha_inicio' and  '$fecha_final'  and
							n.ncre_cod_empr = $id_empresa and 
							n.ncre_est_fact <> 'AN' 
							$tmpSucursal
							$tmpEstado
							$tmpUser
							order by ncre_num_preimp ";

		//$oReturn->alert($sqlNotaCred);
		unset($array_ncre);
		if ($oIfx->Query($sqlNotaCred)) {
			if ($oIfx->NumFilas() > 0) {
				$i = 1;
				do {
					$ncre_cod_ncre = $oIfx->f("ncre_cod_ncre");
					$fecha_fact = fecha_mysql_func($oIfx->f("ncre_fech_fact"));
					$fecha_docu = $oIfx->f("ncre_fech_docu");
					$secuencial = $oIfx->f("ncre_num_preimp");
					$ruc = $oIfx->f("ncre_ruc_clie");
					$cliente = $oIfx->f("ncre_nom_cliente");
					$iva = $oIfx->f("ncre_iva");
					$con_iva = $oIfx->f("ncre_con_miva");
					$sin_iva = $oIfx->f("ncre_sin_miva");
					$total = $oIfx->f("ncre_tot_fact");
					$irbp = $oIfx->f("ncre_irbp");
					$correo = $oIfx->f("ncre_email_clpv");
					$ncre_tlf_cliente = $oIfx->f("ncre_tlf_cliente");
					$ncre_dir_clie = $oIfx->f("ncre_dir_clie");
					$ncre_nse_ncre = $oIfx->f("ncre_nse_ncre");
					$ncre_cod_clpv = $oIfx->f("ncre_cod_clpv");
					$ncre_cod_fact = $oIfx->f("ncre_cod_fact");
					$ncre_ice = $oIfx->f("ncre_ice");
					$ncre_otr_fact = $oIfx->f("ncre_otr_fact");
					$ncre_fle_fact = $oIfx->f("ncre_fle_fact");
					$ncre_cm1_ncre = $oIfx->f("ncre_cm1_ncre");
					$ncre_erro_sri = $oIfx->f("ncre_erro_sri");
					$fecha = $oIfx->f("ncre_fech_fact");
					$clv_con_clpv = $oIfx->f("clv_con_clpv");
					$cod_clpv = $oIfx->f("ncre_cod_clpv");
					$ncre_clav_sri = $oIfx->f("ncre_clav_sri");
					$ncre_cod_sucu = $oIfx->f("ncre_cod_sucu");
					$ncre_aprob_sri = $oIfx->f("ncre_aprob_sri");
					$ncre_dsg_valo = $oIfx->f("ncre_dsg_valo");
					$ncre_cod_aux = $oIfx->f("ncre_cod_aux");


					$btnedit = '';
					$eventedit = 'onClick="edita_contactos(' . $ncre_cod_ncre . ', '.$id_empresa.')"';

					if (!$ncre_clav_sri) {
						$ncre_clav_sri = generaClaveAccesoSri($oIfxA, '04', $id_empresa, $ncre_cod_sucu, $oIfx->f("ncre_fech_fact"), $ncre_nse_ncre, $secuencial);
					}
					if ($clv_con_clpv == '01') {                  // RUC
						$tipoIdentificacionComprador = '04';
					} elseif ($clv_con_clpv == '02') {            // CEDULA
						$tipoIdentificacionComprador = '05';
					} elseif ($clv_con_clpv == '03') {            // PASAPORTE
						$tipoIdentificacionComprador = '06';
					} elseif ($clv_con_clpv == '07') {            // CONSUMIDOR FINAL
						$tipoIdentificacionComprador = '07';
					} elseif ($clv_con_clpv == '04') {            // EXTRANJERIA
						$tipoIdentificacionComprador = '08';
					} elseif (empty($clv_con_clpv)) {
						$charid = strlen($ruc);


						if ($charid == 13) {
							$tipoIdentificacionComprador = '04';
						} elseif ($charid == 10) {
							$tipoIdentificacionComprador = '05';
						} elseif ($charid > 13) {
							$tipoIdentificacionComprador = '06';
						}
					}

					//$clave_acceso, $correo, $tipoDocu, $serial, $clpv, $num_fact, $ejer, $asto, $fec_emis, $idSucursal
					if ($ncre_aprob_sri == 'S') {
						$btnedit = 'disabled';
						$eventedit = '';
						$img = '<div class="btn btn-danger btn-sm" onclick="enviar_mail(\'' . $ncre_clav_sri . '\', \'' . $correo . '\', \'' . $tipo_docu . '\', 
																					' . $ncre_cod_ncre . ', ' . $cod_clpv . ',  \'' . $secuencial . '\',
																					' . $campo . ', ' . $campo . ', \'' . $fecha . '\', ' . $ncre_cod_sucu . ');">
									<span class=" glyphicon glyphicon-envelope"></span>
								</div>';
					} else {
						$ifu->AgregarCampoCheck($ncre_cod_ncre . '_n', 'S/N', false, 'N');
						$img =  $ifu->ObjetoHtml($ncre_cod_ncre . '_n');
					}

					$array_ncre[] = array(
						$ncre_cod_ncre, $secuencial, $ruc, $cliente, $iva, $con_iva, $sin_iva, $total, $correo, $ncre_tlf_cliente,
						$ncre_dir_clie, $fecha, $ncre_nse_ncre, $cod_clpv, $ncre_ice, $irbp, $tipoIdentificacionComprador,
						$ncre_otr_fact, $ncre_fle_fact, $ncre_cm1_ncre, $ncre_cod_fact, $ncre_cod_sucu, $ncre_clav_sri, $ncre_cod_aux,$fecha_docu
					);
					$edit = '<span class="btn btn-warning btn-sm" title="Editar Cobro" value="Editar"  ' . $btnedit . ' ' . $eventedit . '>
								<i class="glyphicon glyphicon-pencil"></i>
					</span>';

					$tabla_reporte .= '<tr>';
					$tabla_reporte .= '<td align="right">' . $i . '</td>';
					$tabla_reporte .= '<td align="left">' . $arraySucursal[$ncre_cod_sucu] . '</td>';
					$tabla_reporte .= '<td align="left">' . $fecha_fact . '</td>';
					$tabla_reporte .= '<td align="left">' . $cliente . '</td>';
					$tabla_reporte .= '<td align="left">' . $ruc . '</td>';
					$tabla_reporte .= '<td align="left">' . $ncre_nse_ncre . '</td>';
					$tabla_reporte .= '<td align="left">' . $secuencial . '</td>';
					//$tabla_reporte .='<td align="right">' . ($total + $iva + $irbp) . '</td>';
					$tabla_reporte .= '<td align="right">' . round((($total + $iva + $irbp + $ncre_ice) - $ncre_dsg_valo), 2) . '</td>';

					$tabla_reporte .= '<td align="left">' . $correo . '</td>';
					$tabla_reporte .= '<td align="center">' . $edit . '</td>';
					$tabla_reporte .= '<td align="center">
										<div class="btn btn-primary btn-sm" onclick="genera_documento(3, ' . $ncre_cod_ncre . ', \'' . $ncre_clav_sri . '\', ' . $campo . ', ' . $campo . ', ' . $campo . ', ' . $campo . ', ' . $campo . ', ' . $ncre_cod_sucu . ');">
											<span class="glyphicon glyphicon-print"></span>
											RIDE
										</div>
									</td>';
					if ($estado != 'N') {
						$contenido_xml = "../../modulos/sri_offline/documentoselectronicos/firmados/$ncre_clav_sri.xml";
						if (file_exists($contenido_xml)) {
							$eti = '<a href="' . $contenido_xml . '" target="_blank" class="btn btn-success btn-sm" onclick="">
							<span class="glyphicon glyphicon-list"></span>
							XML</a>';
						} else {
							$eti = '<font color="red">NO GENERADO</font>';
						}
						$tabla_reporte .= '<td align="center">
                                           ' . $eti . '
                                        </td>';
					}
					$tabla_reporte .= '<td align="left">' . $ncre_clav_sri . '</td>';
					$tabla_reporte .= '<td align="left" id="' . $ncre_clav_sri . '">' . $arrayEstadoSri[$ncre_aprob_sri] . '</td>';
					$tabla_reporte .= '<td align="left">' . $ncre_erro_sri . '</td>';
					$tabla_reporte .= '<td align="center">' . $img . '</td>';
					$i++;
				} while ($oIfx->SiguienteRegistro());

				$tabla_reporte .= '</tbody>';
				$tabla_reporte .= '</table>';
			} else {
				$tabla_reporte = 'SIN DATOS....';
			}
		}
		$oIfx->Free();

		$_SESSION['U_FACT_ENVIO'] = $array_ncre;

		$oReturn->assign("divFormularioDetalle", "innerHTML", $tabla_reporte);
		$oReturn->script("init('tbncre')");
	} catch (Exception $e) {
		$oReturn->alert($e->getMessage());
	}

	return $oReturn;
}

function consultar_guiaRemi($aForm = '')
{
	//Definiciones
	global $DSN_Ifx, $DSN;
	if (session_status() !== PHP_SESSION_ACTIVE) {
		session_start();
	}

	$oIfx = new Dbo;
	$oIfx->DSN = $DSN_Ifx;
	$oIfx->Conectar();

	$oIfxA = new Dbo;
	$oIfxA->DSN = $DSN_Ifx;
	$oIfxA->Conectar();

	$oCon = new Dbo;
	$oCon->DSN = $DSN;
	$oCon->Conectar();

	$ifu = new Formulario;
	$ifu->DSN = $DSN_Ifx;

	//VARIABLES GLOBALES
	$id_empresa = $_SESSION['U_EMPRESA'];
	$id_sucursal = $_SESSION['U_SUCURSAL'];
	unset($_SESSION['U_FACT_ENVIO']);

	$oReturn = new xajaxResponse();

	//DATOS DEL FORMULARIO
	$sucursal = $aForm['sucursal'];
	$estado = $aForm['estado'];
	$usuario = $aForm['usuario'];
	$fecha_inicio = fecha_informix($aForm['fecha_inicio']);
	$fecha_final = fecha_informix($aForm['fecha_final']);
	$campo = 0;
	$tipo_docu = "guia_remision";

	$cliente = $aForm['cliente'];
	$filclpv='';
	if(!empty($cliente)){
		$filclpv="and c.clpv_cod_clpv=$cliente";
	}

	try {

		// TRANPORTISTA
		$sql = "select trta_cod_trta , trta_nom_trta, trta_cid_trta, trta_tip_iden from saetrta where trta_cod_empr = $id_empresa ";
		unset($array_trta);
		if ($oIfx->Query($sql)) {
			if ($oIfx->NumFilas() > 0) {
				do {
					$array_trta[$oIfx->f('trta_cod_trta')] = array($oIfx->f('trta_cid_trta'), $oIfx->f('trta_nom_trta'), $oIfx->f('trta_tip_iden'));
				} while ($oIfx->SiguienteRegistro());
			}
		}
		$oIfx->Free();


		//array sucursal
		$sqlSucursal = "select sucu_cod_sucu, sucu_nom_sucu 
						from saesucu where
						sucu_cod_empr = $id_empresa";
		if ($oIfx->Query($sqlSucursal)) {
			if ($oIfx->NumFilas() > 0) {
				unset($arraySucursal);
				do {
					$arraySucursal[$oIfx->f('sucu_cod_sucu')] = $oIfx->f('sucu_nom_sucu');
				} while ($oIfx->SiguienteRegistro());
			}
		}
		$oIfx->Free();

		//array estado sri
		$sqlEstadoSri = "select codigo, estado from comercial.sri_estado";
		if ($oCon->Query($sqlEstadoSri)) {
			if ($oCon->NumFilas() > 0) {
				unset($arrayEstadoSri);
				do {
					$arrayEstadoSri[$oCon->f('codigo')] = $oCon->f('estado');
				} while ($oCon->SiguienteRegistro());
			}
		}
		$oCon->Free();

		//sucursal sql
		$tmpSucursal = '';
		if (!empty($sucursal)) {
			$tmpSucursal = " and g.guia_cod_sucu = $sucursal";
		}

		//estado sql
		$tmpEstado = '';
		if (!empty($estado)) {
			$tmpEstado = " and g.guia_aprob_sri = '$estado'";
		}

		//usuario sql
		$tmpUser = '';
		if (!empty($usuario)) {
			$tmpUser = " and g.guia_user_web = $usuario";
		}

		$tabla_reporte .= '<table id="tbguia"class="table table-striped table-bordered table-hover table-condensed table-responsive" style="width: 100%; margin-top: 10px;" align="center">';
		$tabla_reporte .= '<thead>';
		$tabla_reporte .= '<tr>
								<th>N.-</th>
								<th>Sucursal</th>
								<th>Cliente</th>
								<th>Ruc</th>
								<th>Guia Remision</th>
								<th>Fecha</th>
								<th>Total</th>
								<th>Email</th>
								<th>Editar</th>
								<th>RIDE</th>';
		if ($estado != 'N') {
			$tabla_reporte .= '<th>XML</th>';
		}
		$tabla_reporte .= '<th>Clave /  Autorizacion SRI</th>
								<th>Estado SRI</th>
								<th>Error</th>
								<th align="center"><input type="checkbox" onclick="marcar(this);" align="center"></th>
						  </tr>';
		$tabla_reporte .= '</thead>';
		$tabla_reporte .= '<tbody>';


		$sqlGuiaRemi = "select g.guia_cod_sucu, g.guia_cod_guia, g.guia_fech_guia, g.guia_num_preimp, g.guia_ruc_clie, 
							g.guia_nom_cliente, g.guia_iva, g.guia_con_miva, g.guia_tot_guia,
							g.guia_sin_miva, g.guia_email_clpv, g.guia_erro_sri , c.clv_con_clpv,
							g.guia_tlf_cliente, g.guia_dir_clie, g.guia_hos_guia, g.guia_hol_guia,
							g.guia_num_plac, g.guia_cm3_guia, g.guia_cod_trta, g.guia_nse_guia, g.guia_cod_clpv,
							g.guia_clav_sri, g.guia_aprob_sri, g.guia_inf_adi, g.guia_cod_dest, g.guia_doc_adua, g.guia_ciu_ori
							from saeguia g, saeclpv c where 
							c.clpv_cod_clpv = g.guia_cod_clpv and
							c.clpv_cod_empr = $id_empresa and
							c.clpv_clopv_clpv = 'CL' $filclpv and
							g.guia_fech_guia between '$fecha_inicio' and '$fecha_final' and
							g.guia_cod_empr = $id_empresa and 
							g.guia_est_guia <> 'AN' 
							$tmpSucursal
							$tmpEstado
							$tmpUser
							order by g.guia_num_preimp ";
		$i = 1;
		unset($array_guia);
		if ($oIfx->Query($sqlGuiaRemi)) {
			if ($oIfx->NumFilas() > 0) {
				do {
					$guia_cod_sucu = $oIfx->f("guia_cod_sucu");
					$guia_cod_guia = $oIfx->f("guia_cod_guia");
					$fecha_fact = fecha_mysql_func($oIfx->f("guia_fech_guia"));
					$secuencial = $oIfx->f("guia_num_preimp");
					$ruc = $oIfx->f("guia_ruc_clie");
					$cliente = $oIfx->f("guia_nom_cliente");
					$iva = $oIfx->f("guia_iva");
					$con_iva = $oIfx->f("guia_con_miva");
					$sin_iva = $oIfx->f("guia_sin_miva");
					$total = $oIfx->f("guia_tot_guia");
					$erro_sri = $oIfx->f("guia_erro_sri");
					$correo = $oIfx->f("guia_email_clpv");

					$guia_cod_clpv = $oIfx->f("guia_cod_clpv");
					$guia_nom_cliente = $oIfx->f("guia_nom_cliente");
					$guia_tlf_cliente = $oIfx->f("guia_tlf_cliente");
					$guia_dir_clie = $oIfx->f("guia_dir_clie");
					$guia_ruc_clie = $oIfx->f("guia_ruc_clie");
					$guia_fech_guia = $oIfx->f("guia_fech_guia");
					$guia_hos_guia = $oIfx->f("guia_hos_guia");
					$guia_hol_guia = $oIfx->f("guia_hol_guia");
					$guia_num_preimp = secuencialSri($oIfx->f("guia_num_preimp"));
					$guia_num_plac = $oIfx->f("guia_num_plac");
					$guia_cm3_guia = $oIfx->f("guia_cm3_guia");
					$guia_cod_trta = $oIfx->f("guia_cod_trta");
					$guia_email_clpv = $oIfx->f("guia_email_clpv");
					$guia_nse_guia = $oIfx->f("guia_nse_guia");
					$clv_con_clpv = $oIfx->f("clv_con_clpv");
					$guia_clav_sri = $oIfx->f("guia_clav_sri");
					$guia_aprob_sri = $oIfx->f("guia_aprob_sri");

					$guia_inf_adi = $oIfx->f('guia_inf_adi');
					$guia_cod_dest = trim($oIfx->f('guia_cod_dest'));//CODIGO ESTABLECIMIENTO DESTINO
					$guia_doc_adua = trim($oIfx->f('guia_doc_adua'));//CODIGO UNICO ADUANERO

					$guia_ciu_ori = $oIfx->f('guia_ciu_ori');
            		if(empty($guia_ciu_ori)) $guia_ciu_ori='NULL';



					$btnedit = '';
					$eventedit = 'onClick="edita_contactos(' . $guia_cod_guia . ', '.$id_empresa.')"';

					$guia_clav_sri = generaClaveAccesoSri($oIfxA, '06', $id_empresa, $guia_cod_sucu, $oIfx->f("guia_fech_guia"), $guia_nse_guia, $guia_num_preimp);

					if ($clv_con_clpv == '01') {                  // RUC
						$tipoIdentificacionComprador = '04';
					} elseif ($clv_con_clpv == '02') {            // CEDULA
						$tipoIdentificacionComprador = '05';
					} elseif ($clv_con_clpv == '03') {            // PASAPORTE
						$tipoIdentificacionComprador = '06';
					} elseif ($clv_con_clpv == '07') {            // CONSUMIDOR FINAL
						$tipoIdentificacionComprador = '07';
					} elseif ($clv_con_clpv == '04') {            // EXTRANJERIA
						$tipoIdentificacionComprador = '08';
					} elseif (empty($clv_con_clpv)) {
						$charid = strlen($ruc);


						if ($charid == 13) {
							$tipoIdentificacionComprador = '04';
						} elseif ($charid == 10) {
							$tipoIdentificacionComprador = '05';
						} elseif ($charid > 13) {
							$tipoIdentificacionComprador = '06';
						}
					}

					if (empty($total)) {
						$total = 0;
					}

					// TRANSPORTISTA
					$cid_trta = '';
					$nom_trta = '';
					$iden_trta = '';    // 1 RUC --- 2 CEDULA   ---- 3 PASAPORTE
					list($cid_trta, $nom_trta, $iden_trta) = $array_trta[$guia_cod_trta];

					if ($iden_trta == '1') {                         // RUC
						$tipoIdentificacionTransportista = '04';
					} else if ($iden_trta == '2') {                   // CEDULA
						$tipoIdentificacionTransportista = '05';
					} else if ($iden_trta == '3') {                   // PASAPORTE
						$tipoIdentificacionTransportista = '06';
					}

					$array_guia[] = array(
						$guia_cod_guia, $guia_cod_clpv, $guia_nom_cliente, $guia_tlf_cliente, $guia_dir_clie,
						$guia_ruc_clie, $tipoIdentificacionComprador, cambioFechaSri($guia_fech_guia, 'aaaa-mm-dd', 'dd/mm/aaaa'), cambioFechaSri($guia_hos_guia, 'aaaa-mm-dd', 'dd/mm/aaaa'),
						cambioFechaSri($guia_hol_guia, 'aaaa-mm-dd', 'dd/mm/aaaa'), $guia_num_preimp, $guia_num_plac, $guia_cm3_guia, $guia_email_clpv,
						$guia_nse_guia, $nom_trta, $cid_trta, $tipoIdentificacionTransportista, $guia_cod_sucu, $guia_clav_sri, $guia_inf_adi, $guia_cod_dest, $guia_doc_adua, $guia_ciu_ori
					);


					//$clave_acceso, $correo, $tipoDocu, $serial, $clpv, $num_fact, $ejer, $asto, $fec_emis, $idSucursal
					if ($guia_aprob_sri == 'S') {
						$btnedit = 'disabled';
						$eventedit = '';
						$img = '<div class="btn btn-danger btn-sm" onclick="enviar_mail(\'' . $guia_clav_sri . '\', \'' . $correo . '\', \'' . $tipo_docu . '\', 
																					' . $guia_cod_guia . ', ' . $guia_cod_clpv . ',  \'' . $guia_num_preimp . '\',
																					' . $campo . ', ' . $campo . ', \'' . $guia_fech_guia . '\', ' . $guia_cod_sucu . ');">
									<span class=" glyphicon glyphicon-envelope"></span>
								</div>';
					} else {
						$ifu->AgregarCampoCheck($guia_cod_guia . '_guia', 'S/N', false, 'N');
						$img =  $ifu->ObjetoHtml($guia_cod_guia . '_guia');
					}

					$edit = '<span class="btn btn-warning btn-sm" title="Editar Cobro" value="Editar"  ' . $btnedit . ' ' . $eventedit . '>
								<i class="glyphicon glyphicon-pencil"></i>
					</span>';

					$tabla_reporte .= '<tr>';
					$tabla_reporte .= '<td align="center">' . $i . '</td>';
					$tabla_reporte .= '<td align="left">' . $arraySucursal[$guia_cod_sucu] . '</td>';
					$tabla_reporte .= '<td align="left">' . $cliente . '</td>';
					$tabla_reporte .= '<td align="left">' . $ruc . '</td>';
					$tabla_reporte .= '<td align="left">' . $secuencial . '</td>';
					$tabla_reporte .= '<td align="left">' . $fecha_fact . '</td>';
					$tabla_reporte .= '<td align="right">' . $total . '</td>';
					$tabla_reporte .= '<td align="left">' . $correo . '</td>';
					$tabla_reporte .= '<td align="center">' . $edit . '</td>';
					$tabla_reporte .= '<td align="center">
										<div class="btn btn-primary btn-sm" onclick="genera_documento(4, ' . $guia_cod_guia . ', \'' . $guia_clav_sri . '\', ' . $campo . ', ' . $campo . ', ' . $campo . ', ' . $campo . ', ' . $campo . ', ' . $guia_cod_sucu . ');">
											<span class="glyphicon glyphicon-print"></span>
											RIDE
										</div>
									</td>';
					if ($estado != 'N') {
						$contenido_xml = "../../modulos/sri_offline/documentoselectronicos/firmados/$guia_clav_sri.xml";
						if (file_exists($contenido_xml)) {
							$eti = '<a href="' . $contenido_xml . '" target="_blank" class="btn btn-success btn-sm" onclick="">
							<span class="glyphicon glyphicon-list"></span>
							XML</a>';
						} else {
							$eti = '<font color="red">NO GENERADO</font>';
						}
						$tabla_reporte .= '<td align="center">
                                           ' . $eti . '
                                        </td>';
					}
					$tabla_reporte .= '<td align="left">' . $guia_clav_sri . '</td>';
					$tabla_reporte .= '<td align="left" id="' . $guia_clav_sri . '">' . $arrayEstadoSri[$guia_aprob_sri] . '</td>';
					$tabla_reporte .= '<td align="left">' . $erro_sri . '</td>';
					$tabla_reporte .= '<td align="center">' . $img . '</td>';
					$i++;
				} while ($oIfx->SiguienteRegistro());

				$tabla_reporte .= '</tbody>';
				$tabla_reporte .= '</table>';
			} else {
				$tabla_reporte = 'SIN DATOS....';
			}
		}
		$oIfx->Free();

		$_SESSION['U_FACT_ENVIO'] = $array_guia;

		$oReturn->assign("divFormularioDetalle", "innerHTML", $tabla_reporte);
		$oReturn->script("init('tbguia')");
	} catch (Exception $e) {
		$oReturn->alert($e->getMessage());
	}

	return $oReturn;
}

function consultar_guiaRemiFlor($aForm = '')
{
	//Definiciones
	global $DSN_Ifx, $DSN;
	if (session_status() !== PHP_SESSION_ACTIVE) {
		session_start();
	}

	$oIfx = new Dbo;
	$oIfx->DSN = $DSN_Ifx;
	$oIfx->Conectar();

	$ifu = new Formulario;
	$ifu->DSN = $DSN_Ifx;

	//VARIABLES GLOBALES
	$id_empresa = $_SESSION['U_EMPRESA'];
	$id_sucursal = $_SESSION['U_SUCURSAL'];
	unset($_SESSION['U_FACT_ENVIO']);

	$oReturn = new xajaxResponse();

	//DATOS DEL FORMULARIO
	$fecha_inicio = fecha_informix($aForm['fecha_inicio']);
	$fecha_final = fecha_informix($aForm['fecha_final']);

	// TRANPORTISTA
	$sql = "select trta_cod_trta, trta_nom_trta, trta_cid_trta, trta_tip_iden, trta_plc_cami from saetrta where trta_cod_empr = $id_empresa ";
	unset($array_trta);
	if ($oIfx->Query($sql)) {
		if ($oIfx->NumFilas() > 0) {
			do {
				$array_trta[$oIfx->f('trta_cod_trta')] = array($oIfx->f('trta_cid_trta'), $oIfx->f('trta_nom_trta'), $oIfx->f('trta_tip_iden'), $oIfx->f('trta_plc_cami'));
			} while ($oIfx->SiguienteRegistro());
		}
	}


	// DATOS DE LA EMPRESA
	$sqlTipo = "select  sucu_tip_ambi, sucu_tip_emis  from saesucu where  sucu_cod_sucu = $id_sucursal ";
	if ($oIfx->Query($sqlTipo)) {
		if ($oIfx->NumFilas() > 0) {
			$ambiente = $oIfx->f('sucu_tip_ambi');
			$tipoEmision = $oIfx->f('sucu_tip_emis');
		}
	}
	$oIfx->Free();

	$sql = "select empr_nom_empr, empr_ruc_empr , empr_dir_empr, empr_conta_sn,
                empr_num_resu from saeempr where empr_cod_empr = $id_empresa ";
	if ($oIfx->Query($sql)) {
		if ($oIfx->NumFilas() > 0) {
			$nombre_empr = trim($oIfx->f('empr_nom_empr'));
			$ruc_empr = $oIfx->f('empr_ruc_empr');
			$dir_empr = trim($oIfx->f('empr_dir_empr'));
			$conta_sn = $oIfx->f('empr_conta_sn');
			$empr_num_resu = $oIfx->f('empr_num_resu');
		}
	}
	$oIfx->Free();

	if ($conta_sn == 'S') {
		$conta_sn = 'SI';
	} else {
		$conta_sn = 'NO';
	}

	//OTROS DATOS SRI
	$codDoc = '06';


	$tabla_reporte .= '<fieldset style="border:#999999 1px solid; padding:2px; text-align:center; width:90%;">';
	$tabla_reporte .= '<legend class="Titulo">GUIA REMISION FLOR</legend>';
	$tabla_reporte .= '<table align="center" cellpadding="0" cellspacing="2" width="99%" border="0">';
	$tabla_reporte .= '<tr>
                            <th class="diagrama">N.-</th>
                            <th class="diagrama">Cliente</th>
                            <th class="diagrama">Ruc</th>
                            <th class="diagrama">Guia Remision</th>
                            <th class="diagrama">Fecha</th>
                            <th class="diagrama">Email</th>
                            <th class="diagrama">Firma</th>
                            <th class="diagrama">Error</th>
                            <th class="diagrama">Enviar</th>
                      </tr>';

	$sqlGuiaRemi = "select g.gref_cod_gref, g.gref_fec_emis, g.graf_num_guia, c.clpv_ruc_clpv, 
                    c.clpv_nom_clpv, g.gref_email_clpv, g.gref_clav_sri, g.gref_cod_clpv,
                    g.gref_erro_sri, c.clv_con_clpv, g.gref_nse_gref, g.gref_nom_trans,
                    g.gref_fec_sali, g.gref_fec_lleg, g.gref_dir_ccli, g.gref_mot_tras
                    from saegref g, saeclpv c where 
                    c.clpv_cod_clpv = g.gref_cod_clpv and
                    c.clpv_cod_empr = $id_empresa and
                    c.clpv_clopv_clpv = 'CL' and
                    g.gref_fec_emis between '$fecha_inicio' and '$fecha_final' and
                    g.gref_cod_empr = $id_empresa and 
                    g.gref_cod_sucu = $id_sucursal and
                    g.gref_aprob_sri != 'S'
                    order by g.graf_num_guia";
	$i = 1;
	unset($array_guia);
	if ($oIfx->Query($sqlGuiaRemi)) {
		if ($oIfx->NumFilas() > 0) {
			do {
				$guia_cod_guia = $oIfx->f("gref_cod_gref");
				$fecha_fact = fecha_mysql_func($oIfx->f("gref_fec_emis"));
				$secuencial = $oIfx->f("graf_num_guia");
				$ruc = $oIfx->f("clpv_ruc_clpv");
				$cliente = $oIfx->f("clpv_nom_clpv");
				$erro_sri = $oIfx->f("gref_erro_sri");
				$correo = $oIfx->f("gref_email_clpv");

				//query direccion clpv
				//$sql_dire = "select dire"

				$guia_cod_clpv = $oIfx->f("gref_cod_clpv");
				$guia_nom_cliente = $oIfx->f("clpv_nom_clpv");
				$guia_tlf_cliente = $oIfx->f("guia_tlf_cliente");
				$guia_dir_clie = $oIfx->f("gref_dir_ccli");
				$guia_ruc_clie = $oIfx->f("clpv_ruc_clpv");
				$guia_fech_guia = $oIfx->f("guia_fech_guia");
				$guia_hos_guia = $oIfx->f("gref_fec_sali");
				$guia_hol_guia = $oIfx->f("gref_fec_lleg");
				$guia_num_preimp = secuencialSri($oIfx->f("graf_num_guia"));
				$guia_cm3_guia = $oIfx->f("gref_mot_tras");
				$guia_cod_trta = $oIfx->f("gref_nom_trans");
				$guia_email_clpv = $oIfx->f("gref_email_clpv");
				$guia_nse_guia = $oIfx->f("gref_nse_gref");
				$clv_con_clpv = $oIfx->f("clv_con_clpv");

				if ($clv_con_clpv == '01') {                  // RUC
					$tipoIdentificacionComprador = '04';
				} elseif ($clv_con_clpv == '02') {            // CEDULA
					$tipoIdentificacionComprador = '05';
				} elseif ($clv_con_clpv == '03') {            // PASAPORTE
					$tipoIdentificacionComprador = '06';
				} elseif ($clv_con_clpv == '07') {            // CONSUMIDOR FINAL
					$tipoIdentificacionComprador = '07';
				} elseif ($clv_con_clpv == '04') {            // EXTRANJERIA
					$tipoIdentificacionComprador = '08';
				}

				if (empty($total)) {
					$total = 0;
				}

				// TRANSPORTISTA
				$cid_trta = '';
				$nom_trta = '';
				$iden_trta = '';    // 1 RUC --- 2 CEDULA   ---- 3 PASAPORTE
				$plc_trta = '';
				list($cid_trta, $nom_trta, $iden_trta,  $plc_trta) = $array_trta[$guia_cod_trta];

				if ($iden_trta == '1') {                         // RUC
					$tipoIdentificacionTransportista = '04';
				} else if ($iden_trta == '2') {                   // CEDULA
					$tipoIdentificacionTransportista = '05';
				} else if ($iden_trta == '3') {                   // PASAPORTE
					$tipoIdentificacionTransportista = '06';
				}

				$array_guia[] = array(
					$guia_cod_guia, $guia_cod_clpv, $guia_nom_cliente, $guia_tlf_cliente, $guia_dir_clie,
					$guia_ruc_clie, $tipoIdentificacionComprador, $guia_fech_guia, $guia_hos_guia,
					$guia_hol_guia, $guia_num_preimp, $plc_trta, $guia_cm3_guia, $guia_email_clpv,
					$guia_nse_guia, $nom_trta, $cid_trta, $tipoIdentificacionTransportista
				);

				$ifu->AgregarCampoCheck($guia_cod_guia . '_guia', 'S/N', false, 'N');

				// CLAVE DE ACCESO
				$fec_xml = fecha_clave($guia_fech_guia);
				$num8 = 12345678;
				$claveAcceso = $fec_xml . $codDoc . $ruc_empr . $ambiente . $guia_nse_guia . $guia_num_preimp . $num8 . $tipoEmision;
				$digitoVerificador = digitoVerificador($claveAcceso);
				$claveAcceso = $claveAcceso . $digitoVerificador;

				if ($sClass == 'off')
					$sClass = 'on';
				else
					$sClass = 'off';
				$tabla_reporte .= '<tr height="20" class="' . $sClass . '"
                                        onMouseOver="javascript:this.className=\'link\';"
                                        onMouseOut="javascript:this.className=\'' . $sClass . '\';">';
				$tabla_reporte .= '<td align="right">' . $i . '</td>';
				$tabla_reporte .= '<td align="left">' . $cliente . '</td>';
				$tabla_reporte .= '<td align="right">' . $ruc . '</td>';
				$tabla_reporte .= '<td align="left">' . $secuencial . '</td>';
				$tabla_reporte .= '<td align="right">' . $fecha_fact . '</td>';
				$tabla_reporte .= '<td align="right">' . $correo . '</td>';
				$tabla_reporte .= '<td align="right" id="' . $claveAcceso . '">N</td>';
				$tabla_reporte .= '<td align="right">' . $erro_sri . '</td>';
				$tabla_reporte .= '<td align="center">' . $ifu->ObjetoHtml($guia_cod_guia . '_guia') . '</td>';
				$i++;
			} while ($oIfx->SiguienteRegistro());
		} else {
			$tabla_reporte = 'SIN DATOS....';
		}
	}

	$_SESSION['U_FACT_ENVIO'] = $array_guia;

	$oReturn->assign("divFormularioDetalle", "innerHTML", $tabla_reporte);

	return $oReturn;
}

function consultar_reteGast($aForm = '')
{
	//Definiciones
	global $DSN_Ifx, $DSN;
	if (session_status() !== PHP_SESSION_ACTIVE) {
		session_start();
	}

	$oIfx = new Dbo;
	$oIfx->DSN = $DSN_Ifx;
	$oIfx->Conectar();

	$oIfx1 = new Dbo;
	$oIfx1->DSN = $DSN_Ifx;
	$oIfx1->Conectar();

	$oCon = new Dbo;
	$oCon->DSN = $DSN;
	$oCon->Conectar();

	$ifu = new Formulario;
	$ifu->DSN = $DSN_Ifx;

	//VARIABLES GLOBALES
	$idEmpresa = $_SESSION['U_EMPRESA'];
	unset($_SESSION['U_FACT_ENVIO']);

	$oReturn = new xajaxResponse();

	//DATOS DEL FORMULARIO
	$sucursal = $aForm['sucursal'];
	$estado = $aForm['estado'];
	$fecha_inicio = fecha_informix($aForm['fecha_inicio']);
	$fecha_final = fecha_informix($aForm['fecha_final']);
	$usuario = $aForm['usuario'];
	$campo = 0;
	$tipo_docu = "retencion_gasto";

	$cliente = $aForm['cliente'];
	$filclpv='';
	if(!empty($cliente)){
		$filclpv="and c.clpv_cod_clpv=$cliente";
	}

	try {

		//array estado sri
		$sqlEstadoSri = "select codigo, estado from comercial.sri_estado";
		if ($oCon->Query($sqlEstadoSri)) {
			if ($oCon->NumFilas() > 0) {
				unset($arrayEstadoSri);
				do {
					$arrayEstadoSri[$oCon->f('codigo')] = $oCon->f('estado');
				} while ($oCon->SiguienteRegistro());
			}
		}
		$oCon->Free();

		//datos de la sucursal
		$sqlTipo = "select sucu_cod_sucu, sucu_nom_sucu 
					from saesucu 
					where  sucu_cod_empr = $idEmpresa ";
		if ($oIfx->Query($sqlTipo)) {
			if ($oIfx->NumFilas() > 0) {
				unset($arraySucursal);
				do {
					$arraySucursal[$oIfx->f('sucu_cod_sucu')] = $oIfx->f('sucu_nom_sucu');
				} while ($oIfx->SiguienteRegistro());
			}
		}
		$oIfx->Free();

		//array transacciones
		$sql = "select tran_cod_sucu, tran_cod_tran, trans_tip_comp  
				from saetran where
				tran_cod_empr = $idEmpresa and
				tran_cod_modu = 4 ";
		unset($array_tran);
		if ($oIfx->Query($sql)) {
			if ($oIfx->NumFilas() > 0) {
				do {
					$array_tran[$oIfx->f('tran_cod_sucu')][$oIfx->f('tran_cod_tran')] = $oIfx->f('trans_tip_comp');
				} while ($oIfx->SiguienteRegistro());
			}
		}
		$oIfx->Free();

		//OTROS DATOS SRI
		$codDoc = '07';

		$tabla_reporte .= '<div class="table-responsive"><table id="tbretgast" class="table table-striped table-bordered table-hover table-condensed table-responsive" style="width: 100%; margin-top: 10px;" align="center">';
		$tabla_reporte .= '<thead>';
		$tabla_reporte .= '<tr>
								<th colspan="17" >RETENCIONES DE GASTO PENDIENTES DE AUTORIZAR</th>
							</tr>
							<tr>
								<th>N.-</th>
								<th>Sucursal</th>
								<th>Fecha</th>
								<th>Nombre</th>								
								<th>Serie Retencion</th>
								<th>Retencion</th>
								<th>Serie Factura</th>
								<th>Factura</th>
								<th>Total</th>
								<th>Email</th>
								<th>Editar</th>
								<th>RIDE</th>';
		if ($estado != 'N') {
			$tabla_reporte .= '<th>XML</th>';
		}

		$tabla_reporte .= '<th>Clave / Autorizacion SRI</th>
								<th>Firma</th>
								<th>Error</th>
								<th align="center"><input type="checkbox" onclick="marcar(this);" align="center"></th>
							</tr>';
		$tabla_reporte .= '</thead>';
		$tabla_reporte .= '<tbody>';

		$tmpSucursal = '';
		if (!empty($sucursal)) {
			$tmpSucursal = " and r.asto_cod_sucu = $sucursal";
		}

		//estado sql
		$tmpEstado = '';
		if (!empty($estado)) {
			$tmpEstado = " and f.fprv_aprob_sri = '$estado'";
		}

		$sqlReteGast = "SELECT r.asto_cod_sucu, r.rete_cod_asto, f.fprv_fec_emis, r.ret_ser_ret, r.ret_num_ret, 
						r.rete_nom_benf, r.rete_dire_benf, f.fprv_num_seri, f.fprv_aprob_sri,
						r.ret_num_fact,  r.ret_cod_clpv, f.fprv_clav_sri,
						r.asto_cod_ejer, f.fprv_erro_sri, r.ret_email_clpv , c.clv_con_clpv, f.fprv_cod_tran,f.fprv_rete_fec,
						sum(r.ret_valor) as total
						from saeret r , saeasto a , saefprv f , saeclpv c where 
						c.clpv_cod_clpv = f.fprv_cod_clpv and
						c.clpv_cod_clpv = r.ret_cod_clpv and
						a.asto_cod_asto = f.fprv_cod_asto and
						a.asto_cod_asto = r.rete_cod_asto and
						a.asto_cod_ejer = r.asto_cod_ejer and
						r.ret_num_fact  = f.fprv_num_fact and
						f.fprv_cod_sucu = r.asto_cod_sucu and
						a.asto_cod_sucu = r.asto_cod_sucu and
						c.clpv_cod_empr = $idEmpresa and
						c.clpv_clopv_clpv = 'PV' $filclpv and
						r.asto_cod_empr = $idEmpresa and
						a.asto_cod_empr = $idEmpresa and
						a.asto_est_asto <> 'AN' and
						f.fprv_cod_empr = $idEmpresa and
						r.ret_elec_sn ='S' and
						f.fprv_fec_emis between '$fecha_inicio' and '$fecha_final' 
						--f.fprv_cod_tran in ( 'FAC', 'LQC', 'GFAC', 'GLQC' ) and
						--f.fprv_elec_sn = 'S' 
						$tmpSucursal
						$tmpEstado
						group by 1,2,3,4,5,6,7,8,9,10,11,12, 13, 14, 15, 16, 17,18
						order by r.asto_cod_sucu, r.ret_num_ret";
		//$oReturn->alert($sqlReteGast);
		$i = 1;
		unset($array_gasto);
		if ($oIfx->Query($sqlReteGast)) {
			if ($oIfx->NumFilas() > 0) {
				do {
					$asto_cod_sucu = $oIfx->f("asto_cod_sucu");
					$fecha_ret = fecha_mysql_func($oIfx->f("fprv_fec_emis"));
					$serie_ret = $oIfx->f("ret_ser_ret");
					$retencion = $oIfx->f("ret_num_ret");
					$proveedor = $oIfx->f("rete_nom_benf");
					$direccion = $oIfx->f("rete_dire_benf");
					$serie_fact = $oIfx->f("fprv_num_seri");
					$factura = $oIfx->f("ret_num_fact");
					$total = $oIfx->f("total");
					$clpv_cod = $oIfx->f("ret_cod_clpv");
					$sqlp = "select clpv_ruc_clpv from saeclpv where clpv_cod_clpv=$clpv_cod";
					$ruc_clpv = trim(consulta_string_func($sqlp, 'clpv_ruc_clpv', $oIfx1, ''));

					//  AMBIENTE - EMISION
					$sqlambi = "select sucu_tip_ambi, sucu_tip_emis  from saesucu where sucu_cod_empr = $idEmpresa and sucu_cod_sucu = $asto_cod_sucu ";
					$ambiente_sri = consulta_string_func($sqlambi, 'sucu_tip_ambi', $oIfx1, '');

					$fecha_emis = $oIfx->f("fprv_fec_emis");
					$ejer = $oIfx->f("asto_cod_ejer");
					$correo = $oIfx->f("ret_email_clpv");
					$eror_sri = $oIfx->f("fprv_erro_sri");
					$clv_con_clpv = $oIfx->f("clv_con_clpv");
					$rete_cod_asto = $oIfx->f("rete_cod_asto");
					$fprv_cod_tran = $oIfx->f("fprv_cod_tran");
					$fprv_clav_sri = $oIfx->f("fprv_clav_sri");
					$fprv_aprob_sri = $oIfx->f("fprv_aprob_sri");
					$codsustento = $array_tran[$asto_cod_sucu][$fprv_cod_tran];

					

					$fecha_retencion = $oIfx->f('fprv_rete_fec');

					$btnedit = '';
					$eventedit = 'onClick="edita_contactos(0,'.$idEmpresa.', '.$asto_cod_sucu.', '.$clpv_cod.', \''.$factura.'\', ' . $ejer . ', \'' . $rete_cod_asto . '\', \'' . $fecha_emis . '\')"';

					if(empty($fecha_retencion))
					{
						$fecha_retencion =$fecha_emis;
					}

					if(strlen($fecha_retencion)<11) $fecha_retencion=$fecha_retencion.' 23:59:59';

					//$fecha_retencion='2024-09-03 11:00:00';
					$date_retencion = new DateTime($fecha_retencion);
					$date_actual = new DateTime();

					// Inicializar las horas
					$horas = 0;

					// Recorrer cada día entre las fechas
					for ($date = clone $date_retencion; $date <= $date_actual; $date->modify('+1 day')) {
						// Si el día es laborable (de lunes a viernes)
						if ($date->format('N') < 6) { // 'N' devuelve 1 (lunes) a 7 (domingo)
							if ($date->format('Y-m-d') == $date_retencion->format('Y-m-d')) {
								// Si es el día de retención, sumar solo las horas hasta ahora
								$horas += (24 - $date_retencion->format('H'));
							} elseif ($date->format('Y-m-d') == $date_actual->format('Y-m-d')) {
								// Si es hoy, sumar las horas hasta ahora
								$horas += $date_actual->format('H');
							} else {
								// Si es un día completo, sumar 24 horas
								$horas += 24;
							}
						}
					}

					// Verificar si la diferencia supera las 72 horas
					if ($horas > 96 && $ambiente_sri==2) {
						$color = 'red';
						$color_text='white';
					} else {
						$color = ''; // O cualquier otro color que prefieras
						$color_text='black';
					}

					
					$fecha_retencion=date('Y-m-d', strtotime($fecha_retencion));

					$fprv_clav_sri = generaClaveAccesoSri($oIfx1, '07', $idEmpresa, $asto_cod_sucu, $fecha_retencion, $serie_ret, $retencion);

					if (empty($proveedor)) {
						$sqlClpv = "select clpv_nom_clpv from saeclpv where clpv_cod_clpv = $clpv_cod";
						$proveedor = consulta_string_func($sqlClpv, 'clpv_nom_clpv', $oIfx1, '');
					}

					// direccion
					if (empty($direccion)) {
						$sql = "select min( dire_dir_dire ) as dire  from saedire where
								dire_cod_empr = $idEmpresa and
								dire_cod_clpv = $clpv_cod ";
						$direccion = consulta_string_func($sql, 'dire', $oIfx1, '');
					}

					// validacion email
					$sql = "select emai_ema_emai  from saeemai where
							emai_cod_empr = $idEmpresa and
							emai_cod_clpv = $clpv_cod";
					//var_dump($sql);
					unset($array_correo);
					$array_correo = array_dato($oIfx1, $sql, 'emai_ema_emai', 'emai_ema_emai');
					if (count($array_correo) > 0) {
						$correo_clpv = '';
						foreach ($array_correo as $arrayc) {
							$correo_clpv .= $arrayc . '; ';
						}
						$correo = trim($correo_clpv, '; ');
					}

					if ($clv_con_clpv == '01') {                  // RUC
						$tipoIdentificacionComprador = '04';
					} elseif ($clv_con_clpv == '02') {            // CEDULA
						$tipoIdentificacionComprador = '05';
					} elseif ($clv_con_clpv == '03') {            // PASAPORTE
						$tipoIdentificacionComprador = '06';
					} elseif ($clv_con_clpv == '07') {            // CONSUMIDOR FINAL
						$tipoIdentificacionComprador = '07';
					} elseif ($clv_con_clpv == '04') {            // EXTRANJERIA
						$tipoIdentificacionComprador = '08';
					} elseif (empty($clv_con_clpv)) {
						$charid = strlen($ruc_clpv);


						if ($charid == 13) {
							$tipoIdentificacionComprador = '04';
						} elseif ($charid == 10) {
							$tipoIdentificacionComprador = '05';
						} elseif ($charid > 13) {
							$tipoIdentificacionComprador = '06';
						}
					}

					if (empty($total)) {
						$total = 0;
					}

					//$clave_acceso, $correo, $tipoDocu, $serial, $clpv, $num_fact, $ejer, $asto, $fec_emis, $idSucursal
					if ($fprv_aprob_sri == 'S') {
						$btnedit = 'disabled';
						$eventedit = '';
						$img = '<div class="btn btn-danger btn-sm" onclick="enviar_mail(\'' . $fprv_clav_sri . '\', \'' . $correo . '\', \'' . $tipo_docu . '\', 
																					' . $campo . ', ' . $clpv_cod . ',  \'' . $factura . '\',
																					' . $ejer . ', \'' . $rete_cod_asto . '\', \'' . $fecha_emis . '\', ' . $asto_cod_sucu . ');">
									<span class=" glyphicon glyphicon-envelope"></span>
								</div>';
						$color = '';
						$color_text='black';
					} else {
						$serial = $clpv_cod . '_' . $retencion . '_' . $ejer . '_' . $factura . '_' . $idEmpresa . '_' . $asto_cod_sucu . '_rg';
						$ifu->AgregarCampoCheck($serial, 'S/N', false, 'N');
						$img =  $ifu->ObjetoHtml($serial);
						
						if($color=='red'){
							$img ='';
							$eror_sri='La retencion no fue enviada en el plazo establecido de 72 horas permitidas debe anular y volver a generar el documento';
						}
					}

					$array_gasto[] = array(
						$clpv_cod, $proveedor, $direccion, $correo, $fecha_emis, $ejer, $factura,
						$serie_fact, $retencion, $serie_ret, $tipoIdentificacionComprador, $total,
						$codsustento, $rete_cod_asto, $asto_cod_sucu, $fprv_clav_sri
					);

					$edit = '<span class="btn btn-warning btn-sm" title="Editar Cobro" value="Editar"  ' . $btnedit . ' ' . $eventedit . '>
								<i class="glyphicon glyphicon-pencil"></i>
					</span>';

					$fecha_ret = str_replace('/', '', $fecha_ret);
					$tabla_reporte .= '<tr style="background-color:'.$color.'">';
					$tabla_reporte .= '<td style="color:'.$color_text.';" align="right">' . $i . '</td>';
					$tabla_reporte .= '<td style="color:'.$color_text.';" align="left">' . $arraySucursal[$asto_cod_sucu] . '</td>';
					$tabla_reporte .= '<td style="color:'.$color_text.';" align="left">' . $fecha_ret . '</td>';
					$tabla_reporte .= '<td style="color:'.$color_text.';" align="left">' . $proveedor . '</td>';
					$tabla_reporte .= '<td style="color:'.$color_text.';" align="left">' . $serie_ret . '</td>';
					$tabla_reporte .= '<td style="color:'.$color_text.';" align="left">' . $retencion . '</td>';
					$tabla_reporte .= '<td style="color:'.$color_text.';" align="left">' . $serie_fact . '</td>';
					$tabla_reporte .= '<td style="color:'.$color_text.';" align="left">' . $factura . '</td>';
					$tabla_reporte .= '<td style="color:'.$color_text.';" align="right">' . $total . '</td>';
					$tabla_reporte .= '<td style="color:'.$color_text.';" align="left">' . $correo . '</td>';
					$tabla_reporte .= '<td style="color:'.$color_text.';" align="center">' . $edit . '</td>';
					$tabla_reporte .= '<td align="center">
										<div class="btn btn-primary btn-sm" onclick="javascript:genera_documento(5, \'' . $campo . '\',\'' . $guia_clav_sri . '\' ,
                                                                                         \'' . $clpv_cod . '\'  , \'' . $factura . '\', \'' . $ejer . '\',
                                                                                         \'' . $rete_cod_asto . '\',  \'' . $fecha_emis . '\', ' . $asto_cod_sucu . ')">
											<span class="glyphicon glyphicon-print"></span>
											RIDE
										</div>
									</td>';
					if ($estado != 'N') {
						$contenido_xml = "../../modulos/sri_offline/documentoselectronicos/firmados/$fprv_clav_sri.xml";
						if (file_exists($contenido_xml)) {
							$eti = '<a href="' . $contenido_xml . '" target="_blank" class="btn btn-success btn-sm" onclick="">
							<span class="glyphicon glyphicon-list"></span>
							XML</a>';
						} else {
							$eti = '<font color="red">NO GENERADO</font>';
						}
						$tabla_reporte .= '<td align="center">
                                           ' . $eti . '
                                        </td>';
					}
					$tabla_reporte .= '<td style="color:'.$color_text.';" align="left">' . $fprv_clav_sri . '</td>';
					$tabla_reporte .= '<td style="color:'.$color_text.';" align="left" id="' . $fprv_clav_sri . '">' . $arrayEstadoSri[$fprv_aprob_sri] . '</td>';
					$tabla_reporte .= '<td style="color:'.$color_text.';" align="justify">' . $eror_sri . '</td>';
					$tabla_reporte .= '<td align="center">' . $img . '</td>';
					$tabla_reporte .= '</tr>';
					$i++;
				} while ($oIfx->SiguienteRegistro());

				$tabla_reporte .= '</tbody>';
				$tabla_reporte .= '</table></div>';
			} else {
				$tabla_reporte = 'SIN DATOS....';
			}
		}
		$oIfx->Free();

		
		$_SESSION['U_FACT_ENVIO'] = $array_gasto;

		$oReturn->assign("divFormularioDetalle", "innerHTML", $tabla_reporte);
		$oReturn->script("init('tbretgast')");
	} catch (Exception $e) {
		$oReturn->alert($e->getMessage());
	}

	return $oReturn;
}


function consultar_reteInve($aForm = '')
{
	//Definiciones
	global $DSN_Ifx, $DSN;
	if (session_status() !== PHP_SESSION_ACTIVE) {
		session_start();
	}

	$oIfx = new Dbo;
	$oIfx->DSN = $DSN_Ifx;
	$oIfx->Conectar();

	$oIfx1 = new Dbo;
	$oIfx1->DSN = $DSN_Ifx;
	$oIfx1->Conectar();

	$oCon = new Dbo;
	$oCon->DSN = $DSN;
	$oCon->Conectar();

	$ifu = new Formulario;
	$ifu->DSN = $DSN_Ifx;

	//VARIABLES GLOBALES
	$idEmpresa = $_SESSION['U_EMPRESA'];
	$idSucursal = $_SESSION['U_SUCURSAL'];
	unset($_SESSION['U_FACT_ENVIO']);

	$oReturn = new xajaxResponse();

	// VARIABLE
	$sucursal = $aForm['sucursal'];
	$estado = $aForm['estado'];
	$fecha_inicio = fecha_informix($aForm['fecha_inicio']);
	$fecha_final = fecha_informix($aForm['fecha_final']);
	$usuario = $aForm['usuario'];
	$campo = 0;
	$tipo_docu = "retencion_inventario";

	$cliente = $aForm['cliente'];
	$filclpv='';
	if(!empty($cliente)){
		$filclpv="and c.clpv_cod_clpv=$cliente";
	}

	try {

		if (empty($sucursal)) {
			throw new Exception('Debe seleccionar la Sucursal');
		}

		//array estado sri
		$sqlEstadoSri = "select codigo, estado from comercial.sri_estado";
		if ($oCon->Query($sqlEstadoSri)) {
			if ($oCon->NumFilas() > 0) {
				unset($arrayEstadoSri);
				do {
					$arrayEstadoSri[$oCon->f('codigo')] = $oCon->f('estado');
				} while ($oCon->SiguienteRegistro());
			}
		}
		$oCon->Free();

		//datos de la sucursal
		$sqlTipo = "select  sucu_cod_sucu, sucu_tip_ambi, sucu_tip_emis, sucu_nom_sucu 
					from saesucu 
					where  sucu_cod_empr = $idEmpresa ";
		if ($oIfx->Query($sqlTipo)) {
			if ($oIfx->NumFilas() > 0) {
				unset($arraySucursal);
				do {
					$arraySucursal[$oIfx->f('sucu_cod_sucu')] = $oIfx->f('sucu_nom_sucu');
				} while ($oIfx->SiguienteRegistro());
			}
		}
		$oIfx->Free();

		//OTROS DATOS SRI
		$codDoc = '07';
		$codDocSustento = '01';

		$tabla_reporte = '';
		$tabla_reporte .= '<table id="tbretinve"class="table table-striped table-bordered table-hover table-condensed table-responsive" style="width: 100%; margin-top: 10px;" align="center">';
		$tabla_reporte .= '<thead>';
		$tabla_reporte .= '<tr>
								<th colspan="17" >RETENCIONES DE INVENTARIO PENDIENTES DE AUTORIZAR</th>
							</tr>
							<tr>
								<th>N.-</th>
								<th>Sucursal</th>
								<th>Fecha</th>
								<th>Nombre</th>
								<th>Serie Retencion</th>
								<th>Retencion</th>
								<th>Serie Factura</th>
								<th>Factura</th>
								<th>Total</th>
								<th>Email</th>
								<th>Editar</th>
								<th>RIDE</th>';

		if ($estado != 'N') {
			$tabla_reporte .= '<th>XML</th>';
		}


		$tabla_reporte .= '<th>Clave / Autorizacion SRI</th>
								<th>Firma</th>
								<th>Error</th>
								<th align="center"><input type="checkbox" onclick="marcar(this);" align="center"></th>
						  </tr>';
		$tabla_reporte .= '</thead>';
		$tabla_reporte .= '<tbody>';

		$tmpSucursal = '';
		if (!empty($sucursal)) {
			$tmpSucursal = " and r.asto_cod_sucu = $sucursal";
		}

		//estado sql
		$tmpEstado = '';
		if (!empty($estado)) {
			$tmpEstado = " and m.minv_aprob_sri = '$estado'";
		}

		$sqlReteInve = "select r.asto_cod_sucu, r.rete_cod_asto, m.minv_fmov, r.ret_ser_ret, r.ret_num_ret, 
							r.rete_nom_benf, r.rete_dire_benf, m.minv_ser_docu, 
							r.ret_num_fact, sum(r.ret_valor) as total, r.ret_cod_clpv, 
							r.asto_cod_ejer, m.minv_erro_sri, r.ret_email_clpv, m.minv_num_comp,
							c.clv_con_clpv, m.minv_aprob_sri, m.minv_clav_sri, m.minv_fec_ret
							from saeminv m, saeret r, saeasto a  , saeclpv c where
							c.clpv_cod_clpv = m.minv_cod_clpv and
							c.clpv_cod_clpv = r.ret_cod_clpv and
							c.clpv_cod_empr = $idEmpresa and
							m.minv_cod_sucu = r.asto_cod_sucu and
							m.minv_cod_empr = r.asto_cod_empr and
							a.asto_cod_sucu = r.asto_cod_sucu and
							c.clpv_clopv_clpv = 'PV' $filclpv and
							m.minv_cod_clpv = r.ret_cod_clpv and
							m.minv_cod_ejer = r.asto_cod_ejer and
							a.asto_cod_ejer = r.asto_cod_ejer and
							m.minv_fac_prov = r.ret_num_fact and
							a.asto_cod_asto = r.rete_cod_asto  and
							a.asto_cod_asto = m.minv_comp_cont and
							m.minv_comp_cont = r.rete_cod_asto and
							m.minv_fmov between '$fecha_inicio' and '$fecha_final' and
							m.minv_cod_empr = $idEmpresa and
							m.minv_est_minv <> '0' and
							r.asto_cod_empr = $idEmpresa and
							a.asto_cod_empr = $idEmpresa and
							a.asto_est_asto <> 'AN' 
							$tmpSucursal
							$tmpEstado
							group by r.asto_cod_sucu, r.ret_num_fact, r.ret_ser_ret, r.ret_num_ret, 
							r.rete_nom_benf,r.rete_dire_benf, m.minv_fmov, 
							m.minv_ser_docu, r.rete_cod_asto, r.ret_cod_clpv, 
							r.asto_cod_ejer,m.minv_erro_sri,r.ret_email_clpv, m.minv_num_comp, c.clv_con_clpv,
							m.minv_aprob_sri, m.minv_clav_sri, m.minv_fec_ret
							order by r.ret_num_ret ";
		$i = 1;
		unset($array_compra);
		if ($oIfx->Query($sqlReteInve)) {
			if ($oIfx->NumFilas() > 0) {
				do {
					$asto_cod_sucu = $oIfx->f("asto_cod_sucu");
					$fecha_ret = fecha_mysql_func($oIfx->f("minv_fmov"));
					$serie_ret = $oIfx->f("ret_ser_ret");
					$retencion = $oIfx->f("ret_num_ret");
					$proveedor = $oIfx->f("rete_nom_benf");
					$direccion = $oIfx->f("rete_dire_benf");
					$serie_fact = $oIfx->f("minv_ser_docu");
					$factura = $oIfx->f("ret_num_fact");
					$total = $oIfx->f("total");
					$clpv_cod = $oIfx->f("ret_cod_clpv");
					$fecha_emis = $oIfx->f("minv_fmov");
					$ejer = $oIfx->f("asto_cod_ejer");
					$minv_cod = $oIfx->f("minv_num_comp");
					$correo = $oIfx->f("ret_email_clpv");
					$clv_con_clpv = $oIfx->f("clv_con_clpv");
					$rete_cod_asto = $oIfx->f("rete_cod_asto");
					$error_sri = $oIfx->f("minv_erro_sri");
					$minv_aprob_sri = $oIfx->f("minv_aprob_sri");
					$minv_clav_sri = $oIfx->f("minv_clav_sri");
					$fecha_retencion=  $oIfx->f("minv_fec_ret");

					//  AMBIENTE - EMISION
					$sqlambi = "select sucu_tip_ambi, sucu_tip_emis  from saesucu where sucu_cod_empr = $idEmpresa and sucu_cod_sucu = $asto_cod_sucu ";
					$ambiente_sri = consulta_string_func($sqlambi, 'sucu_tip_ambi', $oIfx1, '');

					$btnedit = '';
					$eventedit = 'onClick="edita_contactos(0,'.$idEmpresa.', '.$asto_cod_sucu.', '.$clpv_cod.', \''.$factura.'\', ' . $ejer . ', \'' . $rete_cod_asto . '\', \'' . $fecha_emis . '\')"';

					if(empty($fecha_retencion)) $fecha_retencion=$fecha_emis;

					if(strlen($fecha_retencion)<11) $fecha_retencion=$fecha_retencion.' 23:59:59';

					//$fecha_retencion='2024-09-03 11:00:00';
					$date_retencion = new DateTime($fecha_retencion);
					$date_actual = new DateTime();

					// Inicializar las horas
					$horas = 0;

					// Recorrer cada día entre las fechas
					for ($date = clone $date_retencion; $date <= $date_actual; $date->modify('+1 day')) {
						// Si el día es laborable (de lunes a viernes)
						if ($date->format('N') < 6) { // 'N' devuelve 1 (lunes) a 7 (domingo)
							if ($date->format('Y-m-d') == $date_retencion->format('Y-m-d')) {
								// Si es el día de retención, sumar solo las horas hasta ahora
								$horas += (24 - $date_retencion->format('H'));
							} elseif ($date->format('Y-m-d') == $date_actual->format('Y-m-d')) {
								// Si es hoy, sumar las horas hasta ahora
								$horas += $date_actual->format('H');
							} else {
								// Si es un día completo, sumar 24 horas
								$horas += 24;
							}
						}
					}

					// Verificar si la diferencia supera las 72 horas
					if ($horas > 96 && $ambiente_sri==2) {
						$color = 'red';
						$color_text='white';
					} else {
						$color = ''; // O cualquier otro color que prefieras
						$color_text='black';
					}
					

					$sqlp = "select clpv_ruc_clpv from saeclpv where clpv_cod_clpv=$clpv_cod";
					$ruc_clpv = trim(consulta_string_func($sqlp, 'clpv_ruc_clpv', $oIfx1, ''));

					$fecha_retencion=date('Y-m-d', strtotime($fecha_retencion));
					$minv_clav_sri = generaClaveAccesoSri($oIfx1, '07', $idEmpresa, $asto_cod_sucu, $fecha_retencion, $serie_ret, $retencion);

					if (empty($proveedor)) {
						$sqlClpv = "select clpv_nom_clpv from saeclpv where clpv_cod_clpv = $clpv_cod";
						$proveedor = consulta_string_func($sqlClpv, 'clpv_nom_clpv', $oIfx1, '');
					}

					// direccion
					if (empty($direccion)) {
						$sql = "select min( dire_dir_dire ) as dire  from saedire where
								dire_cod_empr = $idEmpresa and
								dire_cod_clpv = $clpv_cod ";
						$direccion = consulta_string_func($sql, 'dire', $oIfx1, '');
					}

					// validacion email
					$sql = "select emai_ema_emai  from saeemai where
							emai_cod_empr = $idEmpresa and
							emai_cod_clpv = $clpv_cod";
					//var_dump($sql);
					unset($array_correo);
					$array_correo = array_dato($oIfx1, $sql, 'emai_ema_emai', 'emai_ema_emai');
					if (count($array_correo) > 0) {
						$correo_clpv = '';
						foreach ($array_correo as $arrayc) {
							$correo_clpv .= $arrayc . '; ';
						}
						$correo = trim($correo_clpv, '; ');
					}

					if ($clv_con_clpv == '01') {                  // RUC
						$tipoIdentificacionComprador = '04';
					} elseif ($clv_con_clpv == '02') {            // CEDULA
						$tipoIdentificacionComprador = '05';
					} elseif ($clv_con_clpv == '03') {            // PASAPORTE
						$tipoIdentificacionComprador = '06';
					} elseif ($clv_con_clpv == '07') {            // CONSUMIDOR FINAL
						$tipoIdentificacionComprador = '07';
					} elseif ($clv_con_clpv == '04') {            // EXTRANJERIA
						$tipoIdentificacionComprador = '08';
					} elseif (empty($clv_con_clpv)) {
						$charid = strlen($ruc_clpv);


						if ($charid == 13) {
							$tipoIdentificacionComprador = '04';
						} elseif ($charid == 10) {
							$tipoIdentificacionComprador = '05';
						} elseif ($charid > 13) {
							$tipoIdentificacionComprador = '06';
						}
					}

					if (empty($total)) {
						$total = 0;
					}

					//$clave_acceso, $correo, $tipoDocu, $serial, $clpv, $num_fact, $ejer, $asto, $fec_emis, $idSucursal
					if ($minv_aprob_sri == 'S') {
						$btnedit = 'disabled';
						$eventedit = '';
						$img = '<div class="btn btn-danger btn-sm" onclick="enviar_mail(\'' . $minv_clav_sri . '\', \'' . $correo . '\', \'' . $tipo_docu . '\', 
																					' . $minv_cod . ', ' . $clpv_cod . ',  \'' . $factura . '\',
																					' . $ejer . ', \'' . $rete_cod_asto . '\', \'' . $fecha_emis . '\', ' . $asto_cod_sucu . ');">
									<span class=" glyphicon glyphicon-envelope"></span>
								</div>';
						$color = '';
						$color_text='black';
					} else {
						$serial = $minv_cod . '_rc';
						$ifu->AgregarCampoCheck($serial, 'S/N', false, 'N');
						$img =  $ifu->ObjetoHtml($serial);
						if($color=='red'){
							$img ='';
							$error_sri='La retencion no fue enviada en el plazo establecido de 72 horas permitidas debe anular y volver a generar el documento';
						}
					}

					$array_compra[] = array(
						$clpv_cod, $proveedor, $direccion, $correo, $fecha_emis, $ejer, $factura,
						$serie_fact, $retencion, $serie_ret, $tipoIdentificacionComprador, $total,
						'01', $rete_cod_asto, $minv_cod, $asto_cod_sucu, $minv_clav_sri, $fecha_retencion
					);

					$edit = '<span class="btn btn-warning btn-sm" title="Editar Cobro" value="Editar"  ' . $btnedit . ' ' . $eventedit . '>
								<i class="glyphicon glyphicon-pencil"></i>
					</span>';


					$tabla_reporte .= '<tr style="background-color:'.$color.'">';
					$tabla_reporte .= '<td style="color:'.$color_text.';" align="right">' . $i . '</td>';
					$tabla_reporte .= '<td style="color:'.$color_text.';" align="left">' . $arraySucursal[$asto_cod_sucu] . '</td>';
					$tabla_reporte .= '<td style="color:'.$color_text.';" align="left">' . $fecha_ret . '</td>';
					$tabla_reporte .= '<td style="color:'.$color_text.';" align="left">' . $proveedor . '</td>';
					$tabla_reporte .= '<td style="color:'.$color_text.';" align="left">' . $serie_ret . '</td>';
					$tabla_reporte .= '<td style="color:'.$color_text.';" align="left">' . $retencion . '</td>';
					$tabla_reporte .= '<td style="color:'.$color_text.';" align="left">' . $serie_fact . '</td>';
					$tabla_reporte .= '<td style="color:'.$color_text.';" align="left">' . $factura . '</td>';
					$tabla_reporte .= '<td style="color:'.$color_text.';" align="right">' . $total . '</td>';
					$tabla_reporte .= '<td style="color:'.$color_text.';" align="left">' . $correo . '</td>';
					$tabla_reporte .= '<td align="center">' . $edit . '</td>';
					$tabla_reporte .= '<td align="center">
											<div class="btn btn-primary btn-sm" onclick="javascript:genera_documento(6, \'' . $minv_cod . '\',\'' . $minv_clav_sri . '\' ,
																							 \'' . $clpv_cod . '\'  , \'' . $factura . '\', \'' . $ejer . '\',
																							 \'' . $rete_cod_asto . '\',  \'' . $fecha_emis . '\', ' . $asto_cod_sucu . ')">
												<span class="glyphicon glyphicon-print"></span>
												RIDE
											</div>
										</td>';
					if ($estado != 'N') {
						$contenido_xml = "../../modulos/sri_offline/documentoselectronicos/firmados/$minv_clav_sri.xml";
						if (file_exists($contenido_xml)) {
							$eti = '<a href="' . $contenido_xml . '" target="_blank" class="btn btn-success btn-sm" onclick="">
							<span class="glyphicon glyphicon-list"></span>
							XML</a>';
						} else {
							$eti = '<font color="red">NO GENERADO</font>';
						}
						$tabla_reporte .= '<td align="center">
                                           ' . $eti . '
                                        </td>';
					}
					$tabla_reporte .= '<td style="color:'.$color_text.';" align="left">' . $minv_clav_sri . '</td>';
					$tabla_reporte .= '<td style="color:'.$color_text.';" align="left" id="' . $minv_clav_sri . '">' . $arrayEstadoSri[$minv_aprob_sri] . '</td>';
					$tabla_reporte .= '<td style="color:'.$color_text.';" align="justify">' . $error_sri . '</td>';
					$tabla_reporte .= '<td align="center">' . $img . '</td>';
					$tabla_reporte .= '</tr>';
					$i++;
				} while ($oIfx->SiguienteRegistro());

				$tabla_reporte .= '</tbody>';
				$tabla_reporte .= '</table>';
			} else {
				$tabla_reporte = 'SIN DATOS....';
			}
		}


		$_SESSION['U_FACT_ENVIO'] = $array_compra;
		$oReturn->assign("divFormularioDetalle", "innerHTML", $tabla_reporte);
		$oReturn->script("init('tbretinve')");
	} catch (Exception $e) {
		$oReturn->alert($e->getMessage());
	}

	return $oReturn;
}


function consultar_factExpor($aForm = '')
{
	//Definiciones
	global $DSN_Ifx, $DSN;
	if (session_status() !== PHP_SESSION_ACTIVE) {
		session_start();
	}

	$oIfx = new Dbo;
	$oIfx->DSN = $DSN_Ifx;
	$oIfx->Conectar();

	$oIfxA = new Dbo;
	$oIfxA->DSN = $DSN_Ifx;
	$oIfxA->Conectar();

	$ifu = new Formulario;
	$ifu->DSN = $DSN_Ifx;
	$oCon = new Dbo;
	$oCon->DSN = $DSN;
	$oCon->Conectar();
	//VARIABLES GLOBALES
	$id_empresa = $_SESSION['U_EMPRESA'];

	$id_sucursal = $aForm['sucursal'];

	$estado = $aForm['estado'];
	unset($_SESSION['U_FACT_ENVIO']);

	$oReturn = new xajaxResponse();

	//DATOS DEL FORMULARIO
	$fecha_inicio = fecha_informix($aForm['fecha_inicio']);
	$fecha_final = fecha_informix($aForm['fecha_final']);

	$sqlSucursal = "select sucu_cod_sucu, sucu_nom_sucu 
						from saesucu where
						sucu_cod_empr = $id_empresa";
	if ($oIfx->Query($sqlSucursal)) {
		if ($oIfx->NumFilas() > 0) {
			unset($arraySucursal);
			do {
				$arraySucursal[$oIfx->f('sucu_cod_sucu')] = $oIfx->f('sucu_nom_sucu');
			} while ($oIfx->SiguienteRegistro());
		}
	}
	$tmpSucursal = '';
	$tmpSucursal_1 = '';
	if (!empty($id_sucursal)) {
		$tmpSucursal = " and f.fact_cod_sucu = $id_sucursal";
		$tmpSucursal_1 = " and para_cod_sucu = $id_sucursal";
	}
	$sqlTipo = "select  sucu_tip_ambi, sucu_tip_emis  from saesucu where
                    sucu_cod_sucu = $id_sucursal ";
	$oIfx->Free();

	$sql = "select empr_nom_empr, empr_ruc_empr , empr_dir_empr, empr_conta_sn,
                empr_num_resu from saeempr where empr_cod_empr = $id_empresa ";
	if ($oIfx->Query($sql)) {
		if ($oIfx->NumFilas() > 0) {
			$nombre_empr = trim($oIfx->f('empr_nom_empr'));
			$ruc_empr = $oIfx->f('empr_ruc_empr');
			$dir_empr = trim($oIfx->f('empr_dir_empr'));
			$conta_sn = $oIfx->f('empr_conta_sn');
			$empr_num_resu = $oIfx->f('empr_num_resu');
		}
	}
	$oIfx->Free();

	$num8 = 12345678;
	$tmpEstado = '';
	if (!empty($estado)) {
		$tmpEstado = " and f.fact_aprob_sri = '$estado'";
	}
	//$tabla_reporte .='<table class="table table-striped table-bordered table-hover table-condensed" style="width: 100%; margin-top: 10px;" align="center">';
	//$tabla_reporte .='<tr>
	//array estado sri
	$sqlEstadoSri = "select codigo, estado from comercial.sri_estado";
	if ($oCon->Query($sqlEstadoSri)) {
		if ($oCon->NumFilas() > 0) {
			unset($arrayEstadoSri);
			do {
				$arrayEstadoSri[$oCon->f('codigo')] = $oCon->f('estado');
			} while ($oCon->SiguienteRegistro());
		}
	}
	$tabla_reporte .= '<table id="tbexpo" class="table table-striped table-bordered table-hover table-condensed table-responsive" style="width: 100%; margin-top: 10px;" align="center">';
	$tabla_reporte .= '<thead>';
	$tabla_reporte .= '<tr>
							<th colspan="17" >FACTURAS EXPORTACION PENDIENTES DE AUTORIZAR</th>
						</tr>
						<tr>
                            <th>N.-</th>
                            <th>Sucursal</th>
                            <th>Fecha</th>
                            <th>Cliente</th>
                            <th>Ruc</th>
                            <th>Factura</th>
                            <th>Total</th>
                            <th>Email</th>
                            <th>Editar</th>							
							<th>Ride</th>';
	if ($estado != 'N') {
		$tabla_reporte .= '<th>XML</th>';
	}

	$tabla_reporte .= '<th>Clave / Autorizacion SRI</th>
                            <th>Error</th>
                            <th>Estado SRI</th>
                            <th>Enviar</th>
                      </tr>';

	$tabla_reporte .= '</thead>';
	$tabla_reporte .= '<tbody>';


	$sqlFactVent = "select f.fact_cod_fact, f.fact_fech_fact, f.fact_num_preimp, f.fact_ruc_clie, f.fact_nom_cliente, f.fact_iva, 
                        f.fact_con_miva,   f.fact_sin_miva, f.fact_tot_fact, f.fact_erro_sri, f.fact_email_clpv  , f.fact_erro_sri, 
                        f.fact_tlf_cliente,  f.fact_dir_clie, f.fact_nse_fact,  f.fact_cod_clpv ,  f.fact_con_miva, f.fact_sin_miva, 
                        f.fact_iva, fact_ice,   f.fact_val_irbp, c.clv_con_clpv, f.fact_term_expor,
                        f.fact_prto_emb, f.fact_prto_dest, f.fact_cod_pais, f.fact_fle_fact,
                        f.fact_fin_fact, f.fact_otr_fact, f.fact_fp_expor, f.fact_dsg_valo, f.fact_dfp_expor, f.fact_cod_sucu, f.fact_aprob_sri, f.fact_cm1_fact
                        from saefact f, saeclpv c where 
                        c.clpv_cod_clpv = f.fact_cod_clpv and
                        c.clpv_cod_empr = $id_empresa and
                        c.clpv_clopv_clpv = 'CL' and
                        f.fact_fech_fact between '$fecha_inicio' and  '$fecha_final' and
                        f.fact_cod_empr = $id_empresa  and 
                       
                           
							f.fact_fon_fact in ( select para_fac_cxc  from saepara where
													para_cod_empr = $id_empresa
													$tmpSucursal_1)   and
						f.fact_tip_docu = 'E' and
						f.fact_tip_vent = '16' and
                        f.fact_est_fact <> 'AN' $tmpEstado  $tmpSucursal
                        order by f.fact_num_preimp";
	$i = 1;
	//  echo $sqlFactVent;exit;
	//	$oReturn->alert($sqlFactVent);
	unset($array_fact);
	$campo = 0;
	if ($oIfx->Query($sqlFactVent)) {
		if ($oIfx->NumFilas() > 0) {
			do {

				$fact_cod_fact = $oIfx->f("fact_cod_fact");
				$fecha_fact = $oIfx->f("fact_fech_fact");
				$secuencial = $oIfx->f("fact_num_preimp");
				$ruc = $oIfx->f("fact_ruc_clie");
				$cliente = $oIfx->f("fact_nom_cliente");
				$iva = $oIfx->f("fact_iva");
				$con_iva = $oIfx->f("fact_con_miva");
				$sin_iva = $oIfx->f("fact_sin_miva");
				$total = $oIfx->f("fact_tot_fact");
				$correo = $oIfx->f("fact_email_clpv");
				$error = $oIfx->f("fact_erro_sri");
				$telefono = $oIfx->f("fact_tlf_cliente");
				$dire = $oIfx->f("fact_dir_clie");
				$fecha = $oIfx->f("fact_fech_fact");
				$nse_fact = $oIfx->f("fact_nse_fact");
				$cod_clpv = $oIfx->f("fact_cod_clpv");
				$con_miva = $oIfx->f("fact_con_miva");
				$sin_miva = $oIfx->f("fact_sin_miva");
				$fact_iva = $oIfx->f("fact_iva");
				$fact_ice = $oIfx->f("fact_ice");
				$fact_val_irbp = $oIfx->f("fact_val_irbp");
				$clv_con_clpv = $oIfx->f("clv_con_clpv");
				$fact_term_expor = $oIfx->f("fact_term_expor");
				$fact_prto_emb = $oIfx->f("fact_prto_emb");
				$fact_prto_dest = $oIfx->f("fact_prto_dest");
				$pais = $oIfx->f("fact_cod_pais");
				$flete = $oIfx->f("fact_fle_fact");
				$anticipo = $oIfx->f("fact_fin_fact");
				$otros = $oIfx->f("fact_otr_fact");
				$fact_fp_expor = $oIfx->f("fact_fp_expor");
				$fact_dsg_valo = $oIfx->f("fact_dsg_valo");
				$fact_dfp_expor = $oIfx->f("fact_dfp_expor");
				$cod_sucu = $oIfx->f("fact_cod_sucu");
				$fact_aprob_sri = $oIfx->f("fact_aprob_sri");
				$fact_cm1_fact = trim($oIfx->f("fact_cm1_fact"));

				$btnedit = '';
				$eventedit = 'onClick="edita_contactos(' . $fact_cod_fact . ', '.$id_empresa.')"';

				if ($clv_con_clpv == '01') {                  // RUC
					$tipoIdentificacionComprador = '04';
				} elseif ($clv_con_clpv == '02') {            // CEDULA
					$tipoIdentificacionComprador = '05';
				} elseif ($clv_con_clpv == '03') {            // PASAPORTE
					$tipoIdentificacionComprador = '06';
				} elseif ($clv_con_clpv == '07') {            // CONSUMIDOR FINAL
					$tipoIdentificacionComprador = '07';
				} elseif ($clv_con_clpv == '04') {            // EXTRANJERIA
					$tipoIdentificacionComprador = '08';
				}

				$ifu->AgregarCampoCheck($fact_cod_fact . '_f', 'S/N', false, 'N');

				// CLAVE DE ACCESO
				$fact_clav_sri = generaClaveAccesoSri($oIfxA, '01', $id_empresa, $cod_sucu, $oIfx->f("fact_fech_fact"), $nse_fact, $secuencial);

				$array_fact[] = array(
					$fact_cod_fact, $secuencial, $ruc, $cliente, $iva, $con_iva, $sin_iva, $total, $correo, $telefono,
					$dire, $fecha, $nse_fact, $cod_clpv, $fact_ice, $fact_val_irbp, $tipoIdentificacionComprador, $fact_term_expor,
					$fact_prto_emb, $fact_prto_dest, $pais, $flete, $anticipo, $otros, $fact_iva,
					$fact_fp_expor, $fact_dsg_valo, $fact_dfp_expor, $fact_clav_sri, $cod_sucu, $fact_cm1_fact
				);

				if ($fact_aprob_sri == 'S') {
					$btnedit = 'disabled';
					$eventedit = '';
					$img = '<div class="btn btn-danger btn-sm" onclick="enviar_mail(\'' . $fact_clav_sri . '\', \'' . $correo . '\', \'' . $tipo_docu . '\', 
																				' . $fact_cod_fact . ', ' . $cod_clpv . ',  \'' . $secuencial . '\',
																				' . $campo . ', ' . $campo . ', \'' . $fecha_fact . '\', ' . $cod_sucu . ');">
								<span class=" glyphicon glyphicon-envelope"></span>
							</div>';
				} else {
					$ifu->AgregarCampoCheck($fact_cod_fact . '_f', 'S/N', false, 'N');
					$img =  $ifu->ObjetoHtml($fact_cod_fact . '_f');
				}

				$edit = '<span class="btn btn-warning btn-sm" title="Editar Cobro" value="Editar"  ' . $btnedit . ' ' . $eventedit . '>
								<i class="glyphicon glyphicon-pencil"></i>
					</span>';

				if ($sClass == 'off')
					$sClass = 'on';
				else
					$sClass = 'off';
				$tot = ($total);
				$tabla_reporte .= '<tr height="20" class="' . $sClass . '"
                                    onMouseOver="javascript:this.className=\'link\';"
                                    onMouseOut="javascript:this.className=\'' . $sClass . '\';">';
				$tabla_reporte .= '<td align="right">' . $i . '</td>';
				$tabla_reporte .= '<td align="left">' . $arraySucursal[$cod_sucu] . '</td>';
				$tabla_reporte .= '<td align="right">' . $fecha_fact . '</td>';
				$tabla_reporte .= '<td align="left">' . $cliente . '</td>';
				$tabla_reporte .= '<td align="right">' . $ruc . '</td>';
				$tabla_reporte .= '<td align="left">' . $secuencial . '</td>';
				$tabla_reporte .= '<td align="right">' . number_format($tot, 2, '.', ',') . '</td>';
				$tabla_reporte .= '<td align="right">' . $correo . '</td>';
				$tabla_reporte .= '<td align="center">' . $edit . '</td>';
				$tabla_reporte .= '<td align="center">
										<div class="btn btn-primary btn-sm" onclick="genera_documento(7, ' . $fact_cod_fact . ', \'' . $fact_clav_sri . '\', ' . $campo . ', ' . $campo . ', ' . $campo . ', ' . $campo . ', ' . $campo . ', ' . $cod_sucu . ');">
											<span class="glyphicon glyphicon-print"></span>
											RIDE
										</div>
									</td>';
				if ($estado != 'N') {
					$contenido_xml = "../../modulos/sri_offline/documentoselectronicos/firmados/$fact_clav_sri.xml";
					if (file_exists($contenido_xml)) {
						$eti = '<a href="' . $contenido_xml . '" target="_blank" class="btn btn-success btn-sm" onclick="">
							<span class="glyphicon glyphicon-list"></span>
							XML</a>';
					} else {
						$eti = '<font color="red">NO GENERADO</font>';
					}
					$tabla_reporte .= '<td align="center">
                                           ' . $eti . '
                                        </td>';
				}
				$tabla_reporte .= '<td >' . $fact_clav_sri . '</td>';
				$tabla_reporte .= '<td align="right">' . $error . '</td>';
				$tabla_reporte .= '<td align="left" id="' . $fact_clav_sri . '">' . $arrayEstadoSri[$fact_aprob_sri] . '</td>';
				$tabla_reporte .= '<td align="right">' . $img . '</td>';

				$tabla_reporte .= '</tr>';
				$i++;
			} while ($oIfx->SiguienteRegistro());
			$tabla_reporte .= '</tbody>';
			$tabla_reporte .= '</table>';
		} else {
			$tabla_reporte = 'SIN DATOS....';
		}
	}

	$tabla_reporte .= '</table></fieldset>';

	$_SESSION['U_FACT_ENVIO'] = $array_fact;

	$oReturn->assign("divFormularioDetalle", "innerHTML", $tabla_reporte);
	$oReturn->script("init('tbexpo')");
	return $oReturn;
}


function consultar_factFlor($aForm = '')
{
	//Definiciones
	global $DSN_Ifx, $DSN;
	if (session_status() !== PHP_SESSION_ACTIVE) {
		session_start();
	}

	$oIfx = new Dbo;
	$oIfx->DSN = $DSN_Ifx;
	$oIfx->Conectar();

	$ifu = new Formulario;
	$ifu->DSN = $DSN_Ifx;

	//VARIABLES GLOBALES
	$id_empresa = $_SESSION['U_EMPRESA'];
	$id_sucursal = $_SESSION['U_SUCURSAL'];
	unset($_SESSION['U_FACT_ENVIO']);

	$oReturn = new xajaxResponse();

	//DATOS DEL FORMULARIO
	$fecha_inicio = fecha_informix($aForm['fecha_inicio']);
	$fecha_final = fecha_informix($aForm['fecha_final']);

	$sqlTipo = "select  sucu_tip_ambi, sucu_tip_emis  from saesucu where
                    sucu_cod_sucu = $id_sucursal ";
	if ($oIfx->Query($sqlTipo)) {
		if ($oIfx->NumFilas() > 0) {
			$ambiente = $oIfx->f('sucu_tip_ambi');
			$tip_emis = $oIfx->f('sucu_tip_emis');
		}
	}
	$oIfx->Free();

	$sql = "select empr_nom_empr, empr_ruc_empr , empr_dir_empr, empr_conta_sn,
                empr_num_resu from saeempr where empr_cod_empr = $id_empresa ";
	if ($oIfx->Query($sql)) {
		if ($oIfx->NumFilas() > 0) {
			$nombre_empr = trim($oIfx->f('empr_nom_empr'));
			$ruc_empr = $oIfx->f('empr_ruc_empr');
			$dir_empr = trim($oIfx->f('empr_dir_empr'));
			$conta_sn = $oIfx->f('empr_conta_sn');
			$empr_num_resu = $oIfx->f('empr_num_resu');
		}
	}
	$oIfx->Free();

	$num8 = 12345678;

	$tabla_reporte .= '<fieldset style="border:#999999 1px solid; padding:2px; text-align:center; width:90%;">';
	$tabla_reporte .= '<legend class="Titulo">FACTURA DE VENTA</legend>';
	$tabla_reporte .= '<table align="center" cellpadding="0" cellspacing="2" width="99%" border="0">';
	$tabla_reporte .= '<tr>
                            <th class="diagrama">N.-</th>
                            <th class="diagrama">Cliente</th>
                            <th class="diagrama">Ruc</th>
                            <th class="diagrama">Factura</th>
                            <th class="diagrama">Fecha</th>
                            <th class="diagrama">Con Iva</th>
                            <th class="diagrama">Sin Iva</th>
                            <th class="diagrama">Iva</th>
                            <th class="diagrama">Irbp</th>
                            <th class="diagrama">Total</th>
                            <th class="diagrama">Email</th>
                            <th class="diagrama">Firma</th>
                            <th class="diagrama">Error</th>
                            <th class="diagrama">Enviar</th>
                      </tr>';
	$sqlFactVent = "select (f.fpak_cod_fpak) as fact_cod_fact, (f.fpak_fec_pack) as fact_fech_fact, (f.fpak_num_fact) as fact_num_preimp, (c.clpv_ruc_clpv) as fact_ruc_clie, (c.clpv_nom_clpv) as fact_nom_cliente, (f.fpak_iva_natu) as fact_iva,
                            (f.fpak_sub_natu) as fact_con_miva, 0.00 as fact_sin_miva, (f.fpak_sub_natu) as fact_tot_fact, (f.fpak_erro_sri) as fact_erro_sri,
                           (select max(emai_ema_emai) from saeemai where emai_cod_clpv = f.fpak_cod_clpv) as fact_email_clpv,
                           (select  max(tlcp_tlf_tlcp) from saetlcp where tlcp_cod_clpv = f.fpak_cod_clpv) as fact_tlf_cliente,
                           (select dire_dir_dire from saedire where dire_cod_clpv = f.fpak_cod_clpv) as fact_dir_clie,
                           (f.fpak_ser_asri) as fact_nse_fact, (f.fpak_cod_clpv) as fact_cod_clpv, 0.00 as val_ice, 0.00 as val_irbp, c.clv_con_clpv, (f.fpak_obse_fpak) as fact_cm2_fact, (f.fpak_num_nump) as fact_opc_fact 
                           from saefpak f, saeclpv c
                           where f.fpak_cod_clpv = c.clpv_cod_clpv and
                           f.fpak_cod_empr = c.clpv_cod_empr and
                           f.fpak_cod_tven = 'D' and
                           f.fpak_cod_empr = $id_empresa and
                           f.fpak_cod_finc = $id_sucursal and
                           COALESCE(f.fpak_aprob_sri, '')!= 'S' and
                           f.fpak_fec_pack between '$fecha_inicio' and  '$fecha_final'";
	$i = 1;
	//$oReturn->alert($sqlFactVent );
	unset($array_fact);
	if ($oIfx->Query($sqlFactVent)) {
		if ($oIfx->NumFilas() > 0) {
			do {

				$fact_cod_fact = $oIfx->f("fact_cod_fact");
				$fecha_fact = fecha_mysql_func($oIfx->f("fact_fech_fact"));
				$secuencial = $oIfx->f("fact_num_preimp");
				$ruc = $oIfx->f("fact_ruc_clie");
				$cliente = $oIfx->f("fact_nom_cliente");
				$iva = $oIfx->f("fact_iva");
				$con_iva = $oIfx->f("fact_con_miva");
				$sin_iva = $oIfx->f("fact_sin_miva");
				$total = $oIfx->f("fact_tot_fact");
				$correo = $oIfx->f("fact_email_clpv");
				$error = $oIfx->f("fact_erro_sri");
				$telefono = $oIfx->f("fact_tlf_cliente");
				$dire = $oIfx->f("fact_dir_clie");
				$fecha = $oIfx->f("fact_fech_fact");
				$nse_fact = $oIfx->f("fact_nse_fact");
				$cod_clpv = $oIfx->f("fact_cod_clpv");
				$con_miva = $oIfx->f("fact_con_miva");
				$sin_miva = $oIfx->f("fact_sin_miva");
				$fact_iva = $oIfx->f("fact_iva");
				$fact_ice = $oIfx->f("fact_ice");
				$fact_val_irbp = $oIfx->f("fact_val_irbp");
				$clv_con_clpv = $oIfx->f("clv_con_clpv");
				$cod_almacen = $oIfx->f("fact_cm2_fact");
				$orden_compra = $oIfx->f("fact_opc_fact");

				if ($clv_con_clpv == '01') {                  // RUC
					$tipoIdentificacionComprador = '04';
				} elseif ($clv_con_clpv == '02') {            // CEDULA
					$tipoIdentificacionComprador = '05';
				} elseif ($clv_con_clpv == '03') {            // PASAPORTE
					$tipoIdentificacionComprador = '06';
				} elseif ($clv_con_clpv == '07') {            // CONSUMIDOR FINAL
					$tipoIdentificacionComprador = '07';
				} elseif ($clv_con_clpv == '04') {            // EXTRANJERIA
					$tipoIdentificacionComprador = '08';
				}

				$ifu->AgregarCampoCheck($fact_cod_fact . '_f', 'S/N', false, 'N');

				$array_fact[] = array(
					$fact_cod_fact, $secuencial, $ruc, $cliente, $iva, $con_iva, $sin_iva, $total, $correo, $telefono,
					$dire, $fecha, $nse_fact, $cod_clpv, $fact_ice, $fact_val_irbp, $tipoIdentificacionComprador,
					$cod_almacen, $orden_compra
				);

				// CLAVE DE ACCESO
				$fec_xml = fecha_clave($fecha);
				$clave_acceso = $fec_xml . '01' . $ruc_empr . $ambiente . $nse_fact . $secuencial . $num8 . $tip_emis;
				$digitoVerificador = digitoVerificador($clave_acceso);
				$clave_acceso = $clave_acceso . $digitoVerificador;

				if ($sClass == 'off')
					$sClass = 'on';
				else
					$sClass = 'off';
				$tabla_reporte .= '<tr height="20" class="' . $sClass . '"
                                    onMouseOver="javascript:this.className=\'link\';"
                                    onMouseOut="javascript:this.className=\'' . $sClass . '\';">';
				$tabla_reporte .= '<td align="right">' . $i . '</td>';
				$tabla_reporte .= '<td align="left">' . $cliente . '</td>';
				$tabla_reporte .= '<td align="right">' . $ruc . '</td>';
				$tabla_reporte .= '<td align="left">' . $secuencial . '</td>';
				$tabla_reporte .= '<td align="right">' . $fecha_fact . '</td>';
				$tabla_reporte .= '<td align="right">' . $con_iva . '</td>';
				$tabla_reporte .= '<td align="right">' . $sin_iva . '</td>';
				$tabla_reporte .= '<td align="right">' . $iva . '</td>';
				$tabla_reporte .= '<td align="right">' . $fact_val_irbp . '</td>';
				$tabla_reporte .= '<td align="right">' . ($total + $iva + $fact_val_irbp) . '</td>';
				$tabla_reporte .= '<td align="right">' . $correo . '</td>';
				$tabla_reporte .= '<td align="right" id="' . $clave_acceso . '">N</td>';
				$tabla_reporte .= '<td align="right">' . $error . '</td>';
				$tabla_reporte .= '<td align="right">' . $ifu->ObjetoHtml($fact_cod_fact . '_f') . '</td>';
				$tabla_reporte .= '</tr>';
				$i++;
			} while ($oIfx->SiguienteRegistro());
		} else {
			$tabla_reporte = 'SIN DATOS....';
		}
	}

	$tabla_reporte .= '</table></fieldset>';

	$_SESSION['U_FACT_ENVIO'] = $array_fact;

	$oReturn->assign("divFormularioDetalle", "innerHTML", $tabla_reporte);
	return $oReturn;
}

function consultar_factFlorExpor($aForm = '')
{
	//Definiciones
	global $DSN_Ifx, $DSN;
	if (session_status() !== PHP_SESSION_ACTIVE) {
		session_start();
	}

	$oIfx = new Dbo;
	$oIfx->DSN = $DSN_Ifx;
	$oIfx->Conectar();

	$ifu = new Formulario;
	$ifu->DSN = $DSN_Ifx;

	//VARIABLES GLOBALES
	$id_empresa = $_SESSION['U_EMPRESA'];
	$id_sucursal = $_SESSION['U_SUCURSAL'];
	$sucursal = $aForm['sucursal'];
	unset($_SESSION['U_FACT_ENVIO']);

	$oReturn = new xajaxResponse();

	//DATOS DEL FORMULARIO
	$fecha_inicio = fecha_informix($aForm['fecha_inicio']);
	$fecha_final = fecha_informix($aForm['fecha_final']);

	$sqlTipo = "select  sucu_tip_ambi, sucu_tip_emis  from saesucu where
                    sucu_cod_sucu = $id_sucursal ";
	if ($oIfx->Query($sqlTipo)) {
		if ($oIfx->NumFilas() > 0) {
			$ambiente = $oIfx->f('sucu_tip_ambi');
			$tip_emis = $oIfx->f('sucu_tip_emis');
		}
	}
	$oIfx->Free();

	$sql = "select empr_nom_empr, empr_ruc_empr , empr_dir_empr, empr_conta_sn,
                empr_num_resu from saeempr where empr_cod_empr = $id_empresa ";
	if ($oIfx->Query($sql)) {
		if ($oIfx->NumFilas() > 0) {
			$nombre_empr = trim($oIfx->f('empr_nom_empr'));
			$ruc_empr = $oIfx->f('empr_ruc_empr');
			$dir_empr = trim($oIfx->f('empr_dir_empr'));
			$conta_sn = $oIfx->f('empr_conta_sn');
			$empr_num_resu = $oIfx->f('empr_num_resu');
		}
	}
	$oIfx->Free();

	$num8 = 12345678;

	$tabla_reporte .= '<fieldset style="border:#999999 1px solid; padding:2px; text-align:center; width:90%;">';
	$tabla_reporte .= '<legend class="Titulo">FACTURA EXPORTADORES</legend>';
	$tabla_reporte .= '<table align="center" cellpadding="0" cellspacing="2" width="99%" border="0">';
	$tabla_reporte .= '<tr>
                            <th class="diagrama">N.-</th>
                            <th class="diagrama">Cliente</th>
                            <th class="diagrama">Ruc</th>
                            <th class="diagrama">Factura</th>
                            <th class="diagrama">Fecha</th>
                            <th class="diagrama">Con Iva</th>
                            <th class="diagrama">Sin Iva</th>
                            <th class="diagrama">Iva</th>
                            <th class="diagrama">Irbp</th>
                            <th class="diagrama">Total</th>
                            <th class="diagrama">Email</th>
                            <th class="diagrama">Firma</th>
                            <th class="diagrama">Error</th>
                            <th class="diagrama">Enviar</th>
                      </tr>';

	$sqlFactVent = "select (f.fpak_cod_fpak) as fact_cod_fact, (f.fpak_fec_pack) as fact_fech_fact, (f.fpak_num_fact) as fact_num_preimp, (c.clpv_ruc_clpv) as fact_ruc_clie, (c.clpv_nom_clpv) as fact_nom_cliente, (f.fpak_iva_natu) as fact_iva,
                            0.00 as fact_con_miva, (f.fpak_sub_natu) as fact_sin_miva, (f.fpak_sub_natu) as fact_tot_fact, (f.fpak_erro_sri) as fact_erro_sri,
                           (select max(emai_ema_emai) from saeemai where emai_cod_clpv = f.fpak_cod_clpv) as fact_email_clpv,
                           (select  max(tlcp_tlf_tlcp) from saetlcp where tlcp_cod_clpv = f.fpak_cod_clpv) as fact_tlf_cliente,
                           (select dire_dir_dire from saedire where dire_cod_clpv = f.fpak_cod_clpv) as fact_dir_clie,
                           (f.fpak_ser_asri) as fact_nse_fact, (f.fpak_cod_clpv) as fact_cod_clpv, 0.00 as val_ice, 0.00 as val_irbp, c.clv_con_clpv, (f.fpak_obse_fpak) as fact_cm2_fact, (f.fpak_num_nump) as fact_opc_fact,
                           (f.fpak_term_expor) as fact_term_expor, 
                           (f.fpak_prto_origen) as fact_prto_emb,
                           (f.fpak_prto_destino) as fact_prto_dest,
                           (f.fpak_cod_pais) as fact_cod_pais,
                           (fpak_fle_natu) as fact_fle_fact,
                           (fpak_fpag_expo) as fact_fp_expor,
                           (f.fpak_fec_pago - f.fpak_fec_pack) as fact_dfp_expor
                           from saefpak f, saeclpv c
                           where f.fpak_cod_clpv = c.clpv_cod_clpv and
                           f.fpak_cod_empr = c.clpv_cod_empr and
                           f.fpak_cod_tven = 'C' and
                           f.fpak_cod_empr = $id_empresa and
                           f.fpak_cod_finc = $id_sucursal and
                           COALESCE(f.fpak_aprob_sri, '')!= 'S' and
                           f.fpak_fec_pack between '$fecha_inicio' and  '$fecha_final'";
	$i = 1;
	//$oReturn->alert($sqlFactVent);
	unset($array_fact);
	if ($oIfx->Query($sqlFactVent)) {
		if ($oIfx->NumFilas() > 0) {
			do {

				$fact_cod_fact = $oIfx->f("fact_cod_fact");
				$fecha_fact = fecha_mysql_func($oIfx->f("fact_fech_fact"));
				$secuencial = $oIfx->f("fact_num_preimp");
				$ruc = $oIfx->f("fact_ruc_clie");
				$cliente = $oIfx->f("fact_nom_cliente");
				$iva = $oIfx->f("fact_iva");
				$con_iva = $oIfx->f("fact_con_miva");
				$sin_iva = $oIfx->f("fact_sin_miva");
				$total = $oIfx->f("fact_tot_fact");
				$correo = $oIfx->f("fact_email_clpv");
				$error = $oIfx->f("fact_erro_sri");
				$telefono = $oIfx->f("fact_tlf_cliente");
				$dire = $oIfx->f("fact_dir_clie");
				$fecha = $oIfx->f("fact_fech_fact");
				$nse_fact = $oIfx->f("fact_nse_fact");
				$cod_clpv = $oIfx->f("fact_cod_clpv");
				$con_miva = $oIfx->f("fact_con_miva");
				$sin_miva = $oIfx->f("fact_sin_miva");
				$fact_iva = $oIfx->f("fact_iva");
				$fact_ice = $oIfx->f("fact_ice");
				$fact_val_irbp = $oIfx->f("fact_val_irbp");
				$clv_con_clpv = $oIfx->f("clv_con_clpv");
				$fact_term_expor = $oIfx->f("fact_term_expor");
				$fact_prto_emb = $oIfx->f("fact_prto_emb");
				$fact_prto_dest = $oIfx->f("fact_prto_dest");
				$pais = $oIfx->f("fact_cod_pais");
				$flete = $oIfx->f("fact_fle_fact");
				$anticipo = $oIfx->f("fact_fin_fact");
				$otros = $oIfx->f("fact_otr_fact");
				$fact_fp_expor = $oIfx->f("fact_fp_expor");
				$fact_dsg_valo = $oIfx->f("fact_dsg_valo");
				$fact_dfp_expor = $oIfx->f("fact_dfp_expor");

				if ($clv_con_clpv == '01') {                  // RUC
					$tipoIdentificacionComprador = '04';
				} elseif ($clv_con_clpv == '02') {            // CEDULA
					$tipoIdentificacionComprador = '05';
				} elseif ($clv_con_clpv == '03') {            // PASAPORTE
					$tipoIdentificacionComprador = '06';
				} elseif ($clv_con_clpv == '07') {            // CONSUMIDOR FINAL
					$tipoIdentificacionComprador = '07';
				} elseif ($clv_con_clpv == '04') {            // EXTRANJERIA
					$tipoIdentificacionComprador = '08';
				}

				$ifu->AgregarCampoCheck($fact_cod_fact . '_f', 'S/N', false, 'N');

				$array_fact[] = array(
					$fact_cod_fact, $secuencial, $ruc, $cliente, $iva, $con_iva, $sin_iva, $total, $correo, $telefono,
					$dire, $fecha, $nse_fact, $cod_clpv, $fact_ice, $fact_val_irbp, $tipoIdentificacionComprador, $fact_term_expor,
					$fact_prto_emb, $fact_prto_dest, $pais, $flete, $anticipo, $otros, $fact_iva,
					$fact_fp_expor, $fact_dsg_valo, $fact_dfp_expor
				);

				// CLAVE DE ACCESO
				$fec_xml = fecha_clave($fecha);
				$clave_acceso = $fec_xml . '01' . $ruc_empr . $ambiente . $nse_fact . $secuencial . $num8 . $tip_emis;
				$digitoVerificador = digitoVerificador($clave_acceso);
				$clave_acceso = $clave_acceso . $digitoVerificador;


				if ($sClass == 'off')
					$sClass = 'on';
				else
					$sClass = 'off';
				$tabla_reporte .= '<tr height="20" class="' . $sClass . '"
                                    onMouseOver="javascript:this.className=\'link\';"
                                    onMouseOut="javascript:this.className=\'' . $sClass . '\';">';
				$tabla_reporte .= '<td align="right">' . $i . '</td>';
				$tabla_reporte .= '<td align="left">' . $cliente . '</td>';
				$tabla_reporte .= '<td align="right">' . $ruc . '</td>';
				$tabla_reporte .= '<td align="left">' . $secuencial . '</td>';
				$tabla_reporte .= '<td align="right">' . $fecha_fact . '</td>';
				$tabla_reporte .= '<td align="right">' . $con_iva . '</td>';
				$tabla_reporte .= '<td align="right">' . $sin_iva . '</td>';
				$tabla_reporte .= '<td align="right">' . $iva . '</td>';
				$tabla_reporte .= '<td align="right">' . $fact_val_irbp . '</td>';
				$tabla_reporte .= '<td align="right">' . ($total + $iva + $fact_val_irbp) . '</td>';
				$tabla_reporte .= '<td align="right">' . $correo . '</td>';
				$tabla_reporte .= '<td align="right" id="' . $clave_acceso . '">N</td>';
				$tabla_reporte .= '<td align="right">' . $error . '</td>';
				$tabla_reporte .= '<td align="right">' . $ifu->ObjetoHtml($fact_cod_fact . '_f') . '</td>';
				$tabla_reporte .= '</tr>';
				$i++;
			} while ($oIfx->SiguienteRegistro());
		} else {
			$tabla_reporte = 'SIN DATOS....';
		}
	}

	$tabla_reporte .= '</table></fieldset>';

	$_SESSION['U_FACT_ENVIO'] = $array_fact;

	$oReturn->assign("divFormularioDetalle", "innerHTML", $tabla_reporte);
	return $oReturn;
}


function consultar_liquidacionCompra($aForm = '')
{
	//Definiciones
	global $DSN_Ifx, $DSN;
	if (session_status() !== PHP_SESSION_ACTIVE) {
		session_start();
	}

	$oIfx = new Dbo;
	$oIfx->DSN = $DSN_Ifx;
	$oIfx->Conectar();

	$oIfxA = new Dbo;
	$oIfxA->DSN = $DSN_Ifx;
	$oIfxA->Conectar();

	$oCon = new Dbo;
	$oCon->DSN = $DSN;
	$oCon->Conectar();

	$ifu = new Formulario;
	$ifu->DSN = $DSN_Ifx;

	//VARIABLES GLOBALES
	$id_empresa = $_SESSION['U_EMPRESA'];
	$id_sucursal = $_SESSION['U_SUCURSAL'];

	unset($_SESSION['U_FACT_ENVIO']);

	$oReturn = new xajaxResponse();

	//DATOS DEL FORMULARIO
	$sucursal = $aForm['sucursal'];
	$estado = $aForm['estado'];
	$usuario = $aForm['usuario'];
	$fecha_inicio = fecha_informix($aForm['fecha_inicio']);
	$fecha_final = fecha_informix($aForm['fecha_final']);
	$anio = substr($fecha_inicio, -10, 4);

	$campo = 0;
	$tipo_docu = "liqu_compras";

	$cliente = $aForm['cliente'];
	$filclpv='';
	if(!empty($cliente)){
		$filclpv="and c.clpv_cod_clpv=$cliente";
	}

	if (empty($sucursal)) $sucursal = $id_sucursal;

	$sql = "select ejer_cod_ejer
            from saeejer
            where ejer_cod_empr = $id_empresa
            and extract(year from ejer_fec_finl) = '$anio'";
	$ejercicio = consulta_string($sql, 'ejer_cod_ejer', $oIfx, '');

	try {

		//array sucursal
		$sqlSucursal = "select sucu_cod_sucu, sucu_nom_sucu 
						from saesucu where
						sucu_cod_empr = $id_empresa";
		if ($oIfx->Query($sqlSucursal)) {
			if ($oIfx->NumFilas() > 0) {
				unset($arraySucursal);
				do {
					$arraySucursal[$oIfx->f('sucu_cod_sucu')] = $oIfx->f('sucu_nom_sucu');
				} while ($oIfx->SiguienteRegistro());
			}
		}
		$oIfx->Free();

		//array estado sri
		$sqlEstadoSri = "select codigo, estado from comercial.sri_estado";
		if ($oCon->Query($sqlEstadoSri)) {
			if ($oCon->NumFilas() > 0) {
				unset($arrayEstadoSri);
				do {
					$arrayEstadoSri[$oCon->f('codigo')] = $oCon->f('estado');
				} while ($oCon->SiguienteRegistro());
			}
		}
		$oCon->Free();

		$tabla_reporte .= '<table id="tbliqu"class="table table-striped table-bordered table-hover table-condensed table-responsive" style="width: 100%; margin-top: 10px;" align="center">';
		$tabla_reporte .= '<thead>';
		$tabla_reporte .= '<tr>
								<th colspan="17" >LIQUIDACIONES DE COMPRAS PENDIENTES DE AUTORIZAR</th>
							</tr>
							<tr>
								<th>N.-</th>
								<th>Sucursal</th>
								<th>Fecha</th>
								<th>Cliente</th>
								<th>Ruc</th>
								<th>Serie</th>
								<th>Factura</th>
								<th>Total</th>
								<th>Email</th>
								<th>Editar</th>
								<th>RIDE</th>';
		if ($estado != 'N') {
			$tabla_reporte .= '<th>XML</th>';
		}

		$tabla_reporte .= '<th>Clave /  Autorizacion SRI</th>
								<th>Estado SRI</th>
								<th>Error</th>
								<th align="center"><input type="checkbox" onclick="marcar(this);" align="center"></th>
						  </tr>';
		$tabla_reporte .= '</thead>';
		$tabla_reporte .= '<tbody>';


		//sucursal sql
		$tmpSucursal = '';
		$tmpSucursal_1 = '';
		if (!empty($sucursal)) {
			$tmpSucursal = " and f.fprv_cod_sucu = $sucursal";
			$tmpSucursal_1 = " and para_cod_sucu = $sucursal";
		}

		//estado sql
		$tmpEstado = '';
		if (!empty($estado)) {
			$tmpEstado = " and m.minv_aprob_liqu = '$estado'";
		}

		//usuario sql
		$tmpUser = '';
		if (!empty($usuario)) {
			$tmpUser = " and f.fprv_user_web = $usuario";
		}
		// TRANSACCION LIQ.
		$sql = "select tran_cod_tran
                from saetran 
                where tran_cod_modu = 10
                and tran_cod_empr = $id_empresa
                and tran_cod_sucu = $id_sucursal
                and tran_cod_tran = (select defi_cod_tran from saedefi
                                    where defi_tip_defi = '0'
                                    and defi_cod_empr = $id_empresa
                                    and defi_cod_sucu = $id_sucursal
                                    and defi_pro_defi = 'PV'
                                    and defi_tip_comp = '03')";
		$tran_liqu = consulta_string($sql, 'tran_cod_tran', $oIfx, '');
		$sqlLiqComp = "select  a.asto_cod_sucu,  m.minv_fmov, m.minv_fac_prov, c.clpv_nom_clpv, minv_email_clpv, m.minv_num_comp, 
                        (select max(dire_dir_dire)
                        from saedire
                        where dire_cod_empr = c.clpv_cod_empr
                        and dire_cod_sucu = c.clpv_cod_sucu
                        and dire_cod_clpv = c.clpv_cod_clpv) as direccion, 
                        m.minv_cod_clpv, m.minv_ser_docu, m.minv_aprob_liqu, m.minv_clav_sri,
                        a.asto_cod_ejer, m.minv_erro_sri, c.clv_con_clpv, m.minv_cod_tran, c.clpv_ruc_clpv, 
                        (select max(tlcp_tlf_tlcp)
                        from saetlcp
                        where tlcp_cod_empr = c.clpv_cod_empr
                        and tlcp_cod_sucu = c.clpv_cod_sucu
                        and tlcp_cod_clpv = c.clpv_cod_clpv) as telefono,
                        minv_con_iva,
                        minv_sin_iva,
                        (minv_iva_valo) as valor_iva,
                        minv_tot_minv,
                        COALESCE(minv_val_noi, '0') as no_ojeto_iva,
                        COALESCE(minv_val_exe, '0') as exento_iva,
                        m.minv_cod_fpagop, ( m.minv_fec_entr - m.minv_fmov ) as dias_plazo
                        
                from  saeasto a , saeminv m , saeclpv c, saedmov d
                where   a.asto_cod_asto = m.minv_tran_minv and
                        a.asto_cod_empr = m.minv_cod_empr and
                        a.asto_cod_sucu = m.minv_cod_sucu and		
                        a.asto_cod_ejer = m.minv_cod_ejer and
                        c.clpv_cod_clpv = m.minv_cod_clpv and
                        c.clpv_cod_empr = m.minv_cod_empr and 
                        d.dmov_cod_empr = m.minv_cod_empr and
                        d.dmov_cod_sucu = m.minv_cod_sucu and		
                        d.dmov_cod_ejer = m.minv_cod_ejer and
                        d.dmov_num_comp = m.minv_num_comp and
                        m.minv_cod_tran = '$tran_liqu' and		
                        c.clpv_cod_empr = $id_empresa and
                        m.minv_cod_ejer = $ejercicio and
                        c.clpv_clopv_clpv = 'PV' $filclpv and
                        a.asto_cod_empr = $id_empresa and
                        a.asto_est_asto <> 'AN' and
                        m.minv_cod_empr = $id_empresa and
                        m.minv_elec_sn ='S' and
                        m.minv_fmov between '$fecha_inicio' and '$fecha_final'
                        $tmpEstado
                    group by 1,2,3,4,5,6,7,8,9,10,11,12, 13, 14,15,16,17, 18, 19, 20, 21,22, 23, 24, 25
                    order by a.asto_cod_sucu, m.minv_fac_prov";

		unset($array_fact);
		if ($oIfx->Query($sqlLiqComp)) {
			if ($oIfx->NumFilas() > 0) {
				$i = 1;
				do {
					$minv_num_comp = $oIfx->f("minv_num_comp");
					$fprv_fec_emis = fecha_mysql_func($oIfx->f("minv_fmov"));
					$secuencial = $oIfx->f("minv_fac_prov");
					$ruc = $oIfx->f("clpv_ruc_clpv");
					$cliente = $oIfx->f("clpv_nom_clpv");

					$correo = $oIfx->f("minv_email_clpv");
					$error = $oIfx->f("fprv_erro_sri");
					$telefono = $oIfx->f("telefono");
					$dire = $oIfx->f("direccion");
					$fecha = $oIfx->f("minv_fmov");
					$nse_fact = $oIfx->f("minv_ser_docu");
					$cod_clpv = $oIfx->f("minv_cod_clpv");
					$clv_con_clpv = $oIfx->f("clv_con_clpv");

					$total_graba_12 = $oIfx->f("minv_con_iva");
					$total_graba_0 = $oIfx->f("minv_sin_iva");
					$valor_iva = $oIfx->f("valor_iva");
					$porc_iva = $oIfx->f("dmov_iva_porc");
					$fprv_cod_tpago = $oIfx->f("minv_cod_fpagop");
					$fprv_num_dias = $oIfx->f("dias_plazo");


					$cod_sucu = $oIfx->f("asto_cod_sucu");
					$fprv_clav_sri = $oIfx->f("minv_clav_sri");
					$fprv_aprob_sri = $oIfx->f("minv_aprob_liqu");

					$btnedit = '';
					$eventedit = 'onClick="edita_contactos(0,'.$id_empresa.', '.$cod_sucu.', '.$cod_clpv.',\'\', 0, \'\', \'' . $fecha . '\', '.$minv_num_comp.')"';

					$total = $total_graba_12 + $total_graba_0 + $valor_iva;

					$fprv_clav_sri = generaClaveAccesoSri($oIfxA, '03', $id_empresa, $cod_sucu, $fecha, $nse_fact, $secuencial);

					if ($clv_con_clpv == '01') {                  // RUC
						$tipoIdentificacionComprador = '04';
					} elseif ($clv_con_clpv == '02') {            // CEDULA
						$tipoIdentificacionComprador = '05';
					} elseif ($clv_con_clpv == '03') {            // PASAPORTE
						$tipoIdentificacionComprador = '06';
					} elseif ($clv_con_clpv == '07') {            // CONSUMIDOR FINAL
						$tipoIdentificacionComprador = '07';
					} elseif ($clv_con_clpv == '04') {            // EXTRANJERIA
						$tipoIdentificacionComprador = '08';
					} elseif (empty($clv_con_clpv)) {
						$charid = strlen($ruc);


						if ($charid == 13) {
							$tipoIdentificacionComprador = '04';
						} elseif ($charid == 10) {
							$tipoIdentificacionComprador = '05';
						} elseif ($charid > 13) {
							$tipoIdentificacionComprador = '06';
						}
					}
					$tipo_docu = 'liqu_compras';
					//$clave_acceso, $correo, $tipoDocu, $serial, $clpv, $num_fact, $ejer, $asto, $fec_emis, $idSucursal
					if ($fprv_aprob_sri == 'S') {
						$btnedit = 'disabled';
						$eventedit = '';
						$img = '<div class="btn btn-danger btn-sm" onclick="enviar_mail(\'' . $fprv_clav_sri . '\', \'' . $correo . '\', \'' . $tipo_docu . '\', 
																					' . $minv_num_comp . ', ' . $cod_clpv . ',  \'' . $secuencial . '\',
																					' . $campo . ', ' . $campo . ', \'' . $fprv_fec_emis . '\', ' . $cod_sucu . ');">
									<span class=" glyphicon glyphicon-envelope"></span>
								</div>';
					} else {
						$ifu->AgregarCampoCheck($secuencial . '_f', 'S/N', false, 'N');
						$img =  $ifu->ObjetoHtml($secuencial . '_f');
					}

					$array_fact[] = array(
						$secuencial, $ruc, $cliente,  $correo, $telefono,
						$dire, $fecha, $nse_fact, $cod_clpv, $tipoIdentificacionComprador,
						$cod_sucu,  $fprv_clav_sri, $total_graba_0, $total_graba_12, $valor_iva,
						$porc_iva, $fprv_cod_tpago, $fprv_num_dias, $minv_num_comp
					);

					$edit = '<span class="btn btn-warning btn-sm" title="Editar Cobro" value="Editar"  ' . $btnedit . ' ' . $eventedit . '>
								<i class="glyphicon glyphicon-pencil"></i>
					</span>';

					$tabla_reporte .= '<tr>';
					$tabla_reporte .= '<td align="center">' . $i . '</td>';
					$tabla_reporte .= '<td align="left">' . $arraySucursal[$cod_sucu] . '</td>';
					$tabla_reporte .= '<td align="left">' . $fecha . '</td>';
					$tabla_reporte .= '<td align="left">' . $cliente . '</td>';
					$tabla_reporte .= '<td align="left">' . $ruc . '</td>';
					$tabla_reporte .= '<td align="left">' . $nse_fact . '</td>';
					$tabla_reporte .= '<td align="left">' . $secuencial . '</td>';
					$tabla_reporte .= '<td align="right">' . $total  . '</td>';
					$tabla_reporte .= '<td align="left">' . $correo . '</td>';
					$tabla_reporte .= '<td align="center">' . $edit . '</td>';
					$tabla_reporte .= '<td align="center">
										<div class="btn btn-primary btn-sm" onclick="genera_documento(12, ' . $minv_num_comp . ', \'' . $fprv_clav_sri . '\', ' . $campo . ', ' . $campo . ', ' . $campo . ', ' . $campo . ', ' . $campo . ', ' . $cod_sucu . ');">
											<span class="glyphicon glyphicon-print"></span>
											RIDE
										</div>
									</td>';
					if ($estado != 'N') {
						$contenido_xml = "../../modulos/sri_offline/documentoselectronicos/firmados/$fprv_clav_sri.xml";
						if (file_exists($contenido_xml)) {
							$eti = '<a href="' . $contenido_xml . '" target="_blank" class="btn btn-success btn-sm" onclick="">
							<span class="glyphicon glyphicon-list"></span>
							XML</a>';
						} else {
							$eti = '<font color="red">NO GENERADO</font>';
						}
						$tabla_reporte .= '<td align="center">
                                           ' . $eti . '
                                        </td>';
					}
					$tabla_reporte .= '<td align="left">' . $fprv_clav_sri . '</td>';
					$tabla_reporte .= '<td align="left" id="' . $fprv_clav_sri . '">' . $arrayEstadoSri[$fprv_aprob_sri] . '</td>';
					$tabla_reporte .= '<td align="left">' . $error . '</td>';
					$tabla_reporte .= '<td align="center">' . $img . '</td>';
					$tabla_reporte .= '</tr>';
					$i++;
				} while ($oIfx->SiguienteRegistro());
				$tabla_reporte .= '</tbody>';
				$tabla_reporte .= '</table>';
			} else {
				$tabla_reporte = 'SIN DATOS....';
			}
		}

		$tabla_reporte .= '</table>';

		$_SESSION['U_FACT_ENVIO'] = $array_fact;

		$oReturn->assign("divFormularioDetalle", "innerHTML", $tabla_reporte);
		$oReturn->script("init('tbliqu')");
	} catch (Exception $e) {
		$oReturn->alert($e->getMessage());
	}

	return $oReturn;
}

// FIRMAR DOCUMENTOS

function firmar_factVent_TMP($aForm = '')
{

	global $DSN_Ifx, $DSN;
	if (session_status() !== PHP_SESSION_ACTIVE) {
		session_start();
	}

	$oReturn = new xajaxResponse();

	$oIfx = new Dbo;
	$oIfx->DSN = $DSN_Ifx;
	$oIfx->Conectar();

	$oIfx1 = new Dbo;
	$oIfx1->DSN = $DSN_Ifx;
	$oIfx1->Conectar();

	//VARIABLES GLOBALES
	$id_empresa = $_SESSION['U_EMPRESA'];
	$array_fact = $_SESSION['U_FACT_ENVIO'];

	unset($_SESSION['aDataGirdErro']);
	unset($array);

	//datos de la empresa
	$sql = "select empr_nom_empr, empr_ruc_empr, empr_tip_firma,
			empr_dir_empr, empr_conta_sn, empr_num_resu, empr_rimp_sn
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
		}
	}
	$oIfx->Free();

	if ($conta_sn == 'S') {
		$conta_sn = 'SI';
	} else {
		$conta_sn = 'NO';
	}

	// DATOS
	if (count($array_fact) > 0) {
		foreach ($array_fact as $val) {
			$id_factura = $val[0];
			$check = $aForm[$id_factura . '_f'];     //$fact_cod_fact.'_f'

			$xml = '';
			$xmlDeta = '';
			$descuento = 0;
			//$oReturn->alert($check);
			if (!empty($check)) {
				//COMSULTAMOS LOS DATOS Y FORMAMOS LOS XML
				$num8 = 12345678;
				$fact_nom_cliente = $val[3];
				$fact_tlf_cliente = $val[9];
				$fact_dir_clie = $val[10];
				$fact_ruc_clie = $val[2];
				$fact_fech_fact = $val[11];
				$fact_num_preimp = $val[1];
				$fact_nse_fact = $val[12];
				$fact_cod_clpv = $val[13];
				$fact_con_miva = $val[5];
				$fact_sin_miva = $val[6];
				$fact_tot_fact = $val[7];
				$fact_iva = $val[4];
				$fact_ice = $val[14];
				$fact_val_irbp = $val[15];
				$fact_email_clpv = $val[8];
				$tipoIdentificacionComprador = $val[16];
				$cod_almacen = $val[17];
				$orden_compra = $val[18];
				$id_sucursal = $val[19];
				$fact_cm1_fact = trim($val[20]);
				$clave_acceso = $val[21];
				$totalDescuento = 0;

				$baseImponibleIce = 0;
				$baseImponibleIceTotal = 0;

				//genera clave de acceso
				$ambiente = ambienteEmisionSri($oIfx, $id_empresa, $id_sucursal, 1);
				$tip_emis = ambienteEmisionSri($oIfx, $id_empresa, $id_sucursal, 2);

				//TIPO DOCUMENTO DEPENDE DE CANTIDAD DE CARACTERES
				$clv_con_clpv = strlen($fact_ruc_clie);
				//$oReturn->alert($clv_con_clpv);
				if ($clv_con_clpv == 13)
					$tipoIdentificacionComprador = '04';
				else if ($clv_con_clpv == 10)
					$tipoIdentificacionComprador = '05';
				else
					$tipoIdentificacionComprador = '06';


				$totalSinImpuestos = $fact_con_miva + $fact_sin_miva;
				$importeTotal = round($fact_tot_fact + $fact_iva + $fact_ice + $fact_val_irbp, 2);

				$estable = substr($fact_nse_fact, 0, 3);
				$serie = substr($fact_nse_fact, 3, 6);

				$fec_fact = cambioFecha($fact_fech_fact, 'mm/dd/aaaa', 'dd/mm/aaaa');

				$sqlDetalle = "select * from saedfac where 
								dfac_cod_fact = $id_factura and 
								dfac_cod_sucu = $id_sucursal and 
								dfac_cod_empr = $id_empresa ";
				$baseImponibleIRBP = 0;
				if ($oIfx1->Query($sqlDetalle)) {
					$xmlDeta .= '<detalles>';
					$bandera = 2;
					//$oReturn->alert($sqlDetalle);
					do {
						$dfac_cod_prod = $oIfx1->f("dfac_cod_prod");
						$dfac_cant_dfac = $oIfx1->f("dfac_cant_dfac");
						$dfac_precio_dfac = $oIfx1->f("dfac_precio_dfac");
						$dfac_mont_total = $oIfx1->f("dfac_mont_total");
						$dfac_por_iva = $oIfx1->f("dfac_por_iva");
						$dfac_por_irbp = $oIfx1->f("dfac_por_irbp");
						$dfac_des1 = $oIfx1->f("dfac_des1_dfac");
						$dfac_des2 = $oIfx1->f("dfac_des2_dfac");
						$dfac_des3 = $oIfx1->f("dfac_des3_dfac");
						$dfac_des4 = $oIfx1->f("dfac_des4_dfac");
						$dfac_des5 = $oIfx1->f("dfac_por_dsg");
						$dfac_por_ice = $oIfx1->f("dfac_por_ice");

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
						$sqlDescripcionProd = "select prod_nom_prod, prod_cod_barra from saeprod where 
                                                    prod_cod_prod = '$dfac_cod_prod' and 
                                                    prod_cod_empr = $id_empresa and 
                                                    prod_cod_sucu = $id_sucursal ";
						if ($oIfx->Query($sqlDescripcionProd)) {
							if ($oIfx->NumFilas() > 0) {
								$prod_nom_prod = $oIfx->f('prod_nom_prod');
								$prod_cod_barra = $oIfx->f('prod_cod_barra');
							}
						}
						$oIfx->Free();

						if (empty($prod_cod_barra)) {
							$prod_cod_barra = $dfac_cod_prod;
						}

						$xmlDeta .= '<detalle>';
						$xmlDeta .= "<codigoPrincipal>$dfac_cod_prod</codigoPrincipal>";
						$xmlDeta .= "<codigoAuxiliar>$prod_cod_barra</codigoAuxiliar>";
						$xmlDeta .= "<descripcion>" . htmlspecialchars($prod_nom_prod) . "</descripcion>";
						$xmlDeta .= "<cantidad>$dfac_cant_dfac</cantidad>";
						$xmlDeta .= "<precioUnitario>" . round($dfac_precio_dfac, 4) . "</precioUnitario>";
						$xmlDeta .= "<descuento>" . round($descuento, 4) . "</descuento>";
						$xmlDeta .= "<precioTotalSinImpuesto>" . round($dfac_mont_total, 2) . "</precioTotalSinImpuesto>";
						$xmlDeta .= '<impuestos>';

						$totalDetalle = 0;
						$valorIce = 0;
						if ($dfac_por_ice > 0) {
							$totalDetalle = round($dfac_cant_dfac * $dfac_precio_dfac, 2);
							$valorIce = round(($totalDetalle * 15 / 100), 2);
							$dfac_mont_total = $totalDetalle + $valorIce - $desctoItem;
						}

						if ($dfac_por_iva == 0) {
							$codigoPorcentaje = 0;
							$valor = 0.00;
							$tarifa = 0.00;
						} elseif ($dfac_por_iva == 12) {
							$codigoPorcentaje = 2;
							$valor = round((($dfac_mont_total * $dfac_por_iva) / 100), 2);
							$tarifa = 12.00;
							$bandera = 2;
						} elseif ($dfac_por_iva == 14) {
							$codigoPorcentaje = 3;
							$valor = round((($dfac_mont_total * $dfac_por_iva) / 100), 2);
							$tarifa = 14.00;
							$bandera = 3;
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
							$sql_unid = "select COALESCE(prod_uni_caja,1) as prod_uni_caja  from saeprod where
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

							$baseImponibleIce = round($dfac_cant_dfac * $dfac_precio_dfac, 2);
							$porcIce = round($baseImponibleIce * 15 / 100, 2);

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
					} while ($oIfx1->SiguienteRegistro());
					$xmlDeta .= '</detalles>';
				} // fin ifx
				$oIfx1->Free();

				$xmlDeta . -'</totalConImpuestos>';

				$rimpe = "";
				if ($empr_rimp_sn == "S") {
					$rimpe = "<contribuyenteRimpe>CONTRIBUYENTE RÉGIMEN RIMPE</contribuyenteRimpe>";
				}

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
                                            <dirMatriz>' . $dir_empr . '</dirMatriz>
											' . $rimpe . '
                                        </infoTributaria>
                                        <infoFactura>
                                            <fechaEmision>' . $fec_fact . '</fechaEmision>';
				if ($empr_num_resu != '')
					$xml .= '<contribuyenteEspecial>' . $empr_num_resu . '</contribuyenteEspecial>';

				$xml .= '<obligadoContabilidad>' . $conta_sn . '</obligadoContabilidad>
                                    <tipoIdentificacionComprador>' . $tipoIdentificacionComprador . '</tipoIdentificacionComprador>
                                    <razonSocialComprador>' . htmlspecialchars($fact_nom_cliente) . '</razonSocialComprador>
                                    <identificacionComprador>' . $fact_ruc_clie . '</identificacionComprador>
                                    <totalSinImpuestos>' . round($fact_tot_fact, 2) . '</totalSinImpuestos>
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
					$xml .= '<totalImpuesto>
                                         <codigo>2</codigo>
                                         <codigoPorcentaje>0</codigoPorcentaje>
                                         <baseImponible>' . round($fact_sin_miva, 2) . '</baseImponible>
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
				//$oReturn->alert($baseImponibleIRBP);

				$xml .= "</totalConImpuestos> ";

				$xml .= '<propina>0.00</propina>
                        <importeTotal>' . round($importeTotal, 2) . '</importeTotal>
                        <moneda>DOLAR</moneda>';

				//query forma de pago
				$sqlFPago = "select fp.fpag_cod_fpagop, fx.fxfp_val_fxfp, fx.fxfp_num_dias
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


				$aDataInfoAdic['Direccion'] = $fact_dir_clie;
				$aDataInfoAdic['Telefono'] = $fact_tlf_cliente;
				$aDataInfoAdic['Email'] = $fact_email_clpv;

				if (strlen($fact_cm1_fact) > 0) {
					$fact_cm1_fact = str_replace('<br />', "\n", $fact_cm1_fact);
					$aDataInfoAdic['Observaciones'] = $fact_cm1_fact;
				}

				if (!empty($cod_almacen)) {
					$aDataInfoAdic['codigoAlmacen'] = $cod_almacen;
				}

				if (!empty($orden_compra)) {
					$aDataInfoAdic['ordenCompra'] = $orden_compra;
				}

				$etiqueta = array_keys($aDataInfoAdic);
				if (count($etiqueta) > 0) {
					$xml .= '<infoAdicional>';
					foreach ($etiqueta as $nom) {
						if ($aDataInfoAdic[$nom] != '')
							$xml .= "<campoAdicional nombre=\"$nom\">$aDataInfoAdic[$nom]</campoAdicional>";
					}
					$xml .= '</infoAdicional>';
				}

				$xml .= '</factura>';

				//$oReturn->alert($xml);

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
					//$oReturn->alert($serv);

					$archivo = fopen($serv . '/' . $nombre, "w+");

					fwrite($archivo, utf8_encode($xml));
					fclose($archivo);
				}

				$sql_fact = "where fact_cod_empr = $id_empresa and fact_cod_sucu = $id_sucursal   and fact_cod_clpv = $fact_cod_clpv and fact_cod_fact = $id_factura ";

				unset($_SESSION['id']);
				$_SESSION['id'] = $id_factura;

				$_SESSION['contrRepor'] = $clave_acceso;
				$ret_num_fact = '';
				$asto_cod_ejer = 0;
				$rete_cod_asto = '';
				$oReturn->script("firmar('$nombre','$clave_acceso','$ruc_empr', '$fact_email_clpv', 'factura_venta', '$sql_fact', $id_factura,
                                          $fact_cod_clpv, '$ret_num_fact', '$asto_cod_ejer', '$rete_cod_asto', '$fact_fech_fact', 1, $id_sucursal)");
			} // fin check
		} // fin foreach
	} else {
		$oReturn->alert('Por favor realice una Busqueda...');
	}

	return $oReturn;
}

function firmar_factVent($aForm = '')
{

	global $DSN_Ifx, $DSN;
	session_start();

	$oReturn = new xajaxResponse();

	$oIfx = new Dbo;
	$oIfx->DSN = $DSN_Ifx;
	$oIfx->Conectar();

	$oIfx1 = new Dbo;
	$oIfx1->DSN = $DSN_Ifx;
	$oIfx1->Conectar();

	//VARIABLES GLOBALES
	$id_empresa = $_SESSION['U_EMPRESA'];
	$array_fact = $_SESSION['U_FACT_ENVIO'];

	unset($_SESSION['aDataGirdErro']);
	unset($array);



	//datos de la empresa
	$sql = "select empr_nom_empr, empr_ruc_empr, empr_tip_firma,empr_ac2_empr,
			empr_dir_empr, empr_conta_sn, empr_num_resu, empr_rimp_sn, empr_det_fac, empr_rrhh_nom
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
			$empr_rrhh_nom = $oIfx->f('empr_rrhh_nom');
		}
	}
	$oIfx->Free();

	//PARAMETRO SAEEMPR


   

	//direccion sucursales
	$sql = "select sucu_cod_sucu, sucu_dir_sucu from saesucu where sucu_cod_empr = $id_empresa";
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

	// DATOS
	if (count($array_fact) > 0) {
		foreach ($array_fact as $val) {
			$id_factura = $val[0];
			$check = $aForm[$id_factura . '_f'];     //$fact_cod_fact.'_f'

			$xml = '';
			$xmlDeta = '';
			$descuento = 0;
			//$oReturn->alert($check);
			if (!empty($check)) {
				//COMSULTAMOS LOS DATOS Y FORMAMOS LOS XML
				$num8 				= 12345678;
				$fact_nom_cliente 	= $val[3];
				$fact_tlf_cliente 	= $val[9];
				$fact_dir_clie 		= trim($val[10]);
				$fact_ruc_clie 		= $val[2];
				$fact_fech_fact 	= $val[11];
				$fact_num_preimp 	= $val[1];
				$fact_nse_fact 		= $val[12];
				$fact_cod_clpv 		= $val[13];
				$fact_con_miva 		= $val[5];
				$fact_sin_miva 		= $val[6];
				//				$fact_tot_fact 		= $val[7];
				$fact_iva 			= $val[4];
				$fact_ice 			= $val[14];
				$fact_val_irbp 		= $val[15];
				$fact_email_clpv 	= $val[8];
				$tipoIdentificacionComprador = $val[16];
				$cod_almacen 		= trim($val[17]);
				$orden_compra 		= $val[18];
				$id_sucursal 		= $val[19];
				$fact_cm1_fact 		= trim($val[20]);
				$clave_acceso 		= $val[21];
				$totalDescuento 	= 0;
				$baseImponibleIce 	= 0;
				$baseImponibleIceTotal = 0;
				//direccion sucursal
				$sql = "";

				//genera clave de acceso
				$ambiente = ambienteEmisionSri($oIfx, $id_empresa, $id_sucursal, 1);
				$tip_emis = ambienteEmisionSri($oIfx, $id_empresa, $id_sucursal, 2);

				//TIPO DOCUMENTO DEPENDE DE CANTIDAD DE CARACTERES
				/*$clv_con_clpv = strlen($fact_ruc_clie);
				//$oReturn->alert($clv_con_clpv);
				if ($clv_con_clpv == 13)
					$tipoIdentificacionComprador = '04';
				else if ($clv_con_clpv == 10)
					$tipoIdentificacionComprador = '05';
				else
					$tipoIdentificacionComprador = '06';*/


				//Validacion por tipo de documento ficha proveedor/cliente SUEJO4967
				$sql = "SELECT clv_con_clpv from saeclpv where clpv_ruc_clpv='$fact_ruc_clie'";
				$tipo_documento = consulta_string($sql, 'clv_con_clpv', $oIfx, '');

				//VALIDAICON CONVERSION DE UNBIDADES
				$sqlfa="select  para_conv_sn from saepara where para_cod_empr= $id_empresa and para_cod_sucu=$id_sucursal";
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
					//$oReturn->alert($clv_con_clpv);
					if ($clv_con_clpv == 13)
						$tipoIdentificacionComprador = '04'; //ruc XML
					else if ($clv_con_clpv == 10)
						$tipoIdentificacionComprador = '05'; //cedula
					else
						$tipoIdentificacionComprador = '06'; //pasaporte
				}


				$fact_tot_fact  = $fact_con_miva + $fact_sin_miva + $fact_iva + $fact_val_irbp;
				$totalSinImpuestos  = $fact_tot_fact - $fact_ice - $fact_iva;
				$importeTotal 		= round($totalSinImpuestos + $fact_iva + $fact_ice + $fact_val_irbp, 2);

				$estable = substr($fact_nse_fact, 0, 3);
				$serie = substr($fact_nse_fact, 3, 6);

				//$fec_fact = cambioFecha($fact_fech_fact, 'mm/dd/aaaa', 'dd/mm/aaaa');
				$fec_fact = date("d/m/Y", strtotime($fact_fech_fact));

				$sqlDetalle = "select * from saedfac where 
								dfac_cod_fact = $id_factura and 
								dfac_cod_sucu = $id_sucursal and 
								dfac_cod_empr = $id_empresa ";
				$baseImponibleIRBP 	= 0;
				$tot_nobiva 		= 0;
				$tot_exeiva 		= 0;
				if ($oIfx1->Query($sqlDetalle)) {
					$xmlDeta .= '<detalles>';
					$bandera = 2;
					//$oReturn->alert($sqlDetalle);
					do {
						$dfac_cod_prod = $oIfx1->f("dfac_cod_prod");
						$dfac_cant_dfac = $oIfx1->f("dfac_cant_dfac");
						$dfac_precio_dfac = $oIfx1->f("dfac_precio_dfac");
						$dfac_mont_total = $oIfx1->f("dfac_mont_total");
						$dfac_mont_total_i = $oIfx1->f("dfac_mont_total");
						$dfac_por_iva = $oIfx1->f("dfac_por_iva");
						$dfac_por_irbp = $oIfx1->f("dfac_por_irbp");
						$dfac_des1 = $oIfx1->f("dfac_des1_dfac");
						$dfac_des2 = $oIfx1->f("dfac_des2_dfac");
						$dfac_des3 = $oIfx1->f("dfac_des3_dfac");
						$dfac_des4 = $oIfx1->f("dfac_des4_dfac");
						$dfac_des5 = $oIfx1->f("dfac_por_dsg");
						$dfac_por_ice = $oIfx1->f("dfac_por_ice");
						$dfac_det_dfac = trim($oIfx1->f("dfac_det_dfac"));
						$dfac_cant_conv = $oIfx1->f('dfac_cant_conv');
						

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
						$sqlDescripcionProd = "select prod_nom_prod, prod_cod_barra, prod_sn_noi, prod_sn_exe, prod_apli_prod from saeprod where 
                                                    prod_cod_prod = '$dfac_cod_prod' and 
                                                    prod_cod_empr = $id_empresa and 
                                                    prod_cod_sucu = $id_sucursal ";
						if ($oIfx->Query($sqlDescripcionProd)) {
							if ($oIfx->NumFilas() > 0) {
								$prod_nom_prod 		= trim($oIfx->f('prod_nom_prod'));
								$prod_cod_barra 	= $oIfx->f('prod_cod_barra');
								$prod_sn_noi 		= $oIfx->f('prod_sn_noi');
								$prod_sn_exe 		= $oIfx->f('prod_sn_exe');
								$prod_apli_prod 	= trim($oIfx->f('prod_apli_prod'));
							}
						}
						$oIfx->Free();

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

						$sqlfa = "select para_dfa_para from saepara where para_cod_empr= $id_empresa and para_cod_sucu=$id_sucursal";
						$para_dfa_para = consulta_string($sqlfa, 'para_dfa_para', $oIfx, '');

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
					} while ($oIfx1->SiguienteRegistro());
					$xmlDeta .= '</detalles>';
				} // fin ifx
				$oIfx1->Free();

				$xmlDeta . -'</totalConImpuestos>';

				$rimpe = "";
				if ($empr_rimp_sn == "S") {
					$rimpe = "<contribuyenteRimpe>CONTRIBUYENTE RÉGIMEN RIMPE</contribuyenteRimpe>";
				}

				//correccion de caracteres especiales en xml
				$caracteres_errores = array("&");
				$caracteres_corregidos = array("&#38;");
				$nombre_empr = str_replace($caracteres_errores, $caracteres_corregidos, $nombre_empr);


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
				//$oReturn->alert($baseImponibleIRBP);

				$xml .= "</totalConImpuestos> ";

				$xml .= '<propina>0.00</propina>
                        <importeTotal>' . round($importeTotal, 2) . '</importeTotal>
                        <moneda>DOLAR</moneda>';

				//query forma de pago
				$sqlFPago = "select fp.fpag_cod_fpagop, fx.fxfp_val_fxfp, fx.fxfp_num_dias
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
				//// Banchito /////
				if (strlen($empr_rrhh_nom) > 0) {
					$aDataInfoAdic['Contribuyente'] 	= $empr_rrhh_nom;
				}
				///// Hasta Aquí /////
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
					//$xml .= "<campoAdicional nombre=\"$nom\">" . trim($empr_rrhh_nom) . "</campoAdicional>";
					$xml .= '</infoAdicional>';
				}

				$xml .= '</factura>';

				//$oReturn->alert($xml);

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
					//$oReturn->alert($serv);

					$archivo = fopen($serv . '/' . $nombre, "w+");

					fwrite($archivo, utf8_encode($xml));
					fclose($archivo);
				}

				$sql_fact = "where fact_cod_empr = $id_empresa and fact_cod_sucu = $id_sucursal   and fact_cod_clpv = $fact_cod_clpv and fact_cod_fact = $id_factura ";

				unset($_SESSION['id']);
				$_SESSION['id'] = $id_factura;

				$_SESSION['contrRepor'] = $clave_acceso;
				$ret_num_fact = '';
				$asto_cod_ejer = 0;
				$rete_cod_asto = '';

				$oReturn->script("firmar('$nombre','$clave_acceso','$ruc_empr', '$fact_email_clpv', 'factura_venta', '$sql_fact', $id_factura,
                                          $fact_cod_clpv, '$ret_num_fact', '$asto_cod_ejer', '$rete_cod_asto', '$fact_fech_fact', 1, $id_sucursal)");
			} // fin check
		} // fin foreach
	} else {
		$oReturn->alert('Por favor realice una Busqueda...');
	}

	return $oReturn;
}


//FUNCION PARA PGSQL
/* NO CALCULA BIEN EL ICE
function firmar_factVent($aForm = '') {

	global $DSN_Ifx, $DSN;
	if (session_status() !== PHP_SESSION_ACTIVE) {session_start();}

	$oReturn = new xajaxResponse();

	$oIfx = new Dbo;
	$oIfx->DSN = $DSN_Ifx;
	$oIfx->Conectar();

	$oIfx1 = new Dbo;
	$oIfx1->DSN = $DSN_Ifx;
	$oIfx1->Conectar();

	//VARIABLES GLOBALES
	$id_empresa = $_SESSION['U_EMPRESA'];
	$array_fact = $_SESSION['U_FACT_ENVIO'];

	unset($_SESSION['aDataGirdErro']);
	unset($array);

	//  LECTURA SUCIA
	//
	//datos de la empresa
	$sql = "select empr_ac2_empr, empr_nom_empr, empr_ruc_empr, empr_tip_firma,
			empr_dir_empr, empr_conta_sn, empr_num_resu, empr_rimp_sn
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
			$empr_ac2_empr = trim($oIfx->f('empr_ac2_empr'));
			$empr_rimp_sn = $oIfx->f('empr_rimp_sn');
		}
	}
	$oIfx->Free();

	//direccion sucursales
	$sql = "select sucu_cod_sucu, sucu_dir_sucu from saesucu where sucu_cod_empr = $id_empresa";
	if ($oIfx->Query($sql)) {
		if ($oIfx->NumFilas() > 0) {
			unset($arrayDireSucu);
			do{
				$arrayDireSucu[$oIfx->f('sucu_cod_sucu')] = $oIfx->f('sucu_dir_sucu');
			}while($oIfx->SiguienteRegistro());
		}
	}
	$oIfx->Free();

	if ($conta_sn == 'S') {
		$conta_sn = 'SI';
	} else {
		$conta_sn = 'NO';
	}

	// DATOS
	if (count($array_fact) > 0) {
		foreach ($array_fact as $val) {
			$id_factura = $val[0];
			$check = $aForm[$id_factura . '_f'];     //$fact_cod_fact.'_f'

			$xml = '';
			$xmlDeta = '';
			$descuento = 0;
			//$oReturn->alert($check);
			if (!empty($check)) {
				//COMSULTAMOS LOS DATOS Y FORMAMOS LOS XML
				$num8 				= 12345678;
				$fact_nom_cliente 	= $val[3];
				$fact_tlf_cliente 	= $val[9];
				$fact_dir_clie 		= $val[10];
				$fact_ruc_clie 		= $val[2];
				$fact_fech_fact 	= $val[11];
				$fact_num_preimp 	= $val[1];
				$fact_nse_fact 		= $val[12];
				$fact_cod_clpv 		= $val[13];
				$fact_con_miva 		= $val[5];
				$fact_sin_miva 		= $val[6];
				$fact_tot_fact 		= $val[7];
				$fact_iva 			= $val[4];
				$fact_ice 			= $val[14];
				$fact_val_irbp 		= $val[15];
				$fact_email_clpv 	= $val[8];
				$tipoIdentificacionComprador = $val[16];
				$cod_almacen 		= $val[17];
				$orden_compra 		= $val[18];
				$id_sucursal 		= $val[19];
				$fact_cm1_fact 		= $val[20];
				$clave_acceso 		= $val[21];
				$totalDescuento 	= 0;

				$baseImponibleIce 	= 0;
				$baseImponibleIceTotal = 0;

				//direccion sucursal
				$sql = "";

				//genera clave de acceso
				$ambiente = ambienteEmisionSri($oIfx, $id_empresa, $id_sucursal, 1);
				$tip_emis = ambienteEmisionSri($oIfx, $id_empresa, $id_sucursal, 2);

				//TIPO DOCUMENTO DEPENDE DE CANTIDAD DE CARACTERES
				$clv_con_clpv = strlen($fact_ruc_clie);
				//$oReturn->alert($clv_con_clpv);
				if ($clv_con_clpv == 13)
					$tipoIdentificacionComprador = '04';
				else if ($clv_con_clpv == 10)
					$tipoIdentificacionComprador = '05';
				else
					$tipoIdentificacionComprador = '06';


				$totalSinImpuestos  = $fact_con_miva + $fact_sin_miva;
				$importeTotal 		= round($totalSinImpuestos + $fact_iva + $fact_ice + $fact_val_irbp,2);

				$estable = substr($fact_nse_fact, 0, 3);
				$serie = substr($fact_nse_fact, 3, 6);

				//$fec_fact = cambioFecha($fact_fech_fact, 'mm/dd/aaaa', 'dd/mm/aaaa');
				$fec_fact = date("d/m/Y", strtotime($fact_fech_fact));

				$sqlDetalle = "select * from saedfac where
								dfac_cod_fact = $id_factura and
								dfac_cod_sucu = $id_sucursal and
								dfac_cod_empr = $id_empresa ";
				$baseImponibleIRBP 	= 0;
				$tot_nobiva 		= 0;
				$tot_exeiva 		= 0;
				if ($oIfx1->Query($sqlDetalle)) {
					$xmlDeta.='<detalles>';
					$bandera = 2;
					//$oReturn->alert($sqlDetalle);
					do {
						$dfac_cod_prod = $oIfx1->f("dfac_cod_prod");
						$dfac_cant_dfac = $oIfx1->f("dfac_cant_dfac");
						$dfac_precio_dfac = $oIfx1->f("dfac_precio_dfac");
						$dfac_mont_total = $oIfx1->f("dfac_mont_total");
						$dfac_por_iva = $oIfx1->f("dfac_por_iva");
						$dfac_por_irbp = $oIfx1->f("dfac_por_irbp");
						$dfac_des1 = $oIfx1->f("dfac_des1_dfac");
						$dfac_des2 = $oIfx1->f("dfac_des2_dfac");
						$dfac_des3 = $oIfx1->f("dfac_des3_dfac");
						$dfac_des4 = $oIfx1->f("dfac_des4_dfac");
						$dfac_des5 = $oIfx1->f("dfac_por_dsg");
						$dfac_por_ice = $oIfx1->f("dfac_por_ice");

						$descuento = 0;
						$desctoItem = 0;
						if ($dfac_des1 != 0 || $dfac_des2 != 0 || $dfac_des3 != 0 || $dfac_des4 != 0 || $dfac_des5 != 0) {
							$descuento = ( $dfac_cant_dfac * $dfac_precio_dfac ) - $dfac_mont_total;
							$desctoItem = ( $dfac_cant_dfac * $dfac_precio_dfac ) - $dfac_mont_total;
							if ($descuento != 0) {
								$totalDescuento += $descuento;
							}
						}// fin if

						$descuento = number_format($descuento, 2, '.', '');

						//PRODUCTO
						$sqlDescripcionProd = "select prod_nom_prod, prod_cod_barra, prod_sn_noi, prod_sn_exe from saeprod where
                                                    prod_cod_prod = '$dfac_cod_prod' and
                                                    prod_cod_empr = $id_empresa and
                                                    prod_cod_sucu = $id_sucursal ";
						if ($oIfx->Query($sqlDescripcionProd)) {
							if ($oIfx->NumFilas() > 0) {
								$prod_nom_prod 		= $oIfx->f('prod_nom_prod');
								$prod_cod_barra 	= $oIfx->f('prod_cod_barra');
								$prod_sn_noi 		= $oIfx->f('prod_sn_noi');
								$prod_sn_exe 		= $oIfx->f('prod_sn_exe');
							}
						}
						$oIfx->Free();

						if (empty($prod_cod_barra)) {
							$prod_cod_barra = $dfac_cod_prod;
						}

						//CODIGO ALTERNO
						$sql_alt = "select prod_alt_clie from saeprod
                        where prod_cod_prod = '$dfac_cod_prod' and prod_alt_clie is not null limit 1;";
						$codigo_alterno = consulta_string($sql_alt, 'prod_alt_clie', $oIfx, '');
						if(strlen($codigo_alterno) > 0 ){
							$dfac_cod_prod = $codigo_alterno;
						}

						$xmlDeta.='<detalle>';
						$xmlDeta.="<codigoPrincipal>$dfac_cod_prod</codigoPrincipal>";
						$xmlDeta.="<codigoAuxiliar>$prod_cod_barra</codigoAuxiliar>";
						$xmlDeta.="<descripcion>$prod_nom_prod</descripcion>";
						$xmlDeta.="<cantidad>$dfac_cant_dfac</cantidad>";
						$xmlDeta.="<precioUnitario>" . round($dfac_precio_dfac, 4) . "</precioUnitario>";
						$xmlDeta.="<descuento>" . round($descuento, 4) . "</descuento>";
						$xmlDeta.="<precioTotalSinImpuesto>" . round($dfac_mont_total, 2) . "</precioTotalSinImpuesto>";
						$xmlDeta.='<impuestos>';

						$totalDetalle = 0;
						$valorIce = 0;
						if($dfac_por_ice > 0){
							$totalDetalle = round($dfac_cant_dfac * $dfac_precio_dfac,2);
							$valorIce = round(($totalDetalle * 15 / 100), 2 );
							$dfac_mont_total = $totalDetalle + $valorIce - $desctoItem;
						}

						if ($dfac_por_iva == 0) {
							if($prod_sn_noi=='S'){
								$codigoPorcentaje = 6;
								$valor 			  = 0.00;
								$tarifa           = 0.00;
								$tot_nobiva 	 += $dfac_mont_total;
							}elseif($prod_sn_exe=='S'){
								$codigoPorcentaje = 7;
								$valor 			  = 0.00;
								$tarifa           = 0.00;
								$tot_exeiva 	 += $dfac_mont_total;
							}else{
								$codigoPorcentaje = 0;
								$valor 			  = 0.00;
								$tarifa           = 0.00;
							}
						} elseif($dfac_por_iva == 12) {
							$codigoPorcentaje = 2;
							$valor = round((($dfac_mont_total * $dfac_por_iva) / 100), 2);
							$tarifa = 12.00;
							$bandera = 2;
						} elseif($dfac_por_iva == 14) {
							$codigoPorcentaje = 3;
							$valor = round((($dfac_mont_total * $dfac_por_iva) / 100), 2);
							$tarifa = 14.00;
							$bandera = 3;
						}

						$xmlDeta.='<impuesto>';
						$xmlDeta.='<codigo>2</codigo>';
						$xmlDeta.="<codigoPorcentaje>$codigoPorcentaje</codigoPorcentaje>";
						$xmlDeta.="<tarifa>$tarifa</tarifa>";
						$xmlDeta.="<baseImponible>" . round($dfac_mont_total, 2) . "</baseImponible>";
						$xmlDeta.="<valor>" . round($valor, 2) . "</valor>";
						$xmlDeta.='</impuesto>';

						$unid_caja = 1;
						if ($dfac_por_irbp > 0) {
							// UNIDA X CAJA
							$sql_unid = "select nvl(prod_uni_caja,1) as prod_uni_caja  from saeprod where
                                                                        prod_cod_empr = $id_empresa and
                                                                        prod_cod_sucu = $id_sucursal and
                                                                        prod_cod_prod = '$dfac_cod_prod' ";
							$unid_caja = consulta_string_func($sql_unid, 'prod_uni_caja', $oIfx, 1);

							$xmlDeta.='<impuesto>';
							$xmlDeta.='<codigo>5</codigo>';
							$xmlDeta.="<codigoPorcentaje>5001</codigoPorcentaje>";
							$xmlDeta.="<tarifa>0.00</tarifa>";
							$xmlDeta.="<baseImponible>" . round($dfac_mont_total, 2) . "</baseImponible>";
							$xmlDeta.="<valor>" . number_format($dfac_por_irbp * $dfac_cant_dfac * $unid_caja, 2, '.', '') . "</valor>";
							$xmlDeta.='</impuesto>';
							$baseImponibleIRBP += $dfac_mont_total;
						}// fin
						$baseImponibleIce = 0;
						if ($dfac_por_ice > 0) {

							$baseImponibleIce = round($dfac_cant_dfac * $dfac_precio_dfac,2);
							$porcIce = round($baseImponibleIce * 15 / 100,2);

							$xmlDeta.='<impuesto>';
							$xmlDeta.='<codigo>3</codigo>';
							$xmlDeta.="<codigoPorcentaje>3092</codigoPorcentaje>";
							$xmlDeta.="<tarifa>15.00</tarifa>";
							$xmlDeta.="<baseImponible>" . round($baseImponibleIce, 2) . "</baseImponible>";
							$xmlDeta.="<valor>" . number_format($porcIce, 2, '.', '') . "</valor>";
							$xmlDeta.='</impuesto>';
							$baseImponibleIceTotal += $baseImponibleIce;
						}// fin

						$xmlDeta.='</impuestos>';
						$xmlDeta.='</detalle>';
					} while ($oIfx1->SiguienteRegistro());
					$xmlDeta.='</detalles>';
				}// fin ifx
				$oIfx1->Free();

				$xmlDeta . -'</totalConImpuestos>';

				$rimpe="";
                if($empr_rimp_sn=="S"){
                    $rimpe = "<contribuyenteRimpe>CONTRIBUYENTE RÉGIMEN RIMPE</contribuyenteRimpe>";
                }

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
                                            <dirMatriz>' . $dir_empr . '</dirMatriz>
											'.$rimpe.'';
											if(!empty($empr_ac2_empr)){
                                                $aret=explode('-',$empr_ac2_empr);

												$numret=intval($aret[2]);

												$xml.='<agenteRetencion>'.$numret.'</agenteRetencion>';
											}
                                        $xml.='</infoTributaria>
                                        <infoFactura>
                                            <fechaEmision>' . $fec_fact . '</fechaEmision>
											<dirEstablecimiento>' . $arrayDireSucu[$id_sucursal] . '</dirEstablecimiento>';
				if ($empr_num_resu != '')
					$xml .= '<contribuyenteEspecial>' . $empr_num_resu . '</contribuyenteEspecial>';

				$xml .= '<obligadoContabilidad>' . $conta_sn . '</obligadoContabilidad>
                                    <tipoIdentificacionComprador>' . $tipoIdentificacionComprador . '</tipoIdentificacionComprador>
                                    <razonSocialComprador>' . htmlspecialchars($fact_nom_cliente) . '</razonSocialComprador>
                                    <identificacionComprador>' . $fact_ruc_clie . '</identificacionComprador>
                                    <totalSinImpuestos>' . round($totalSinImpuestos, 2) . '</totalSinImpuestos>
                                    <totalDescuento>' . round($totalDescuento, 2) . '</totalDescuento>';

				$xml .= "<totalConImpuestos> ";
				if ($fact_con_miva != '') {
					$xml .= '<totalImpuesto>
                                         <codigo>2</codigo>
                                         <codigoPorcentaje>'.$bandera.'</codigoPorcentaje>
                                         <baseImponible>' . round($fact_con_miva, 2) . '</baseImponible>
                                         <valor>' . round($fact_iva, 2) . '</valor>
                                     </totalImpuesto>';
				}
				if ($fact_sin_miva != '') {
					if($tot_nobiva > 0){
						$xml .= '<totalImpuesto>
                                         <codigo>6</codigo>
                                         <codigoPorcentaje>0</codigoPorcentaje>
                                         <baseImponible>' . round($tot_nobiva, 2) . '</baseImponible>
                                         <valor>0.00</valor>
                                  </totalImpuesto>';
					}
					if($tot_exeiva > 0){
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
				//$oReturn->alert($baseImponibleIRBP);

				$xml .= "</totalConImpuestos> ";

				$xml .='<propina>0.00</propina>
                        <importeTotal>' . round($importeTotal, 2) . '</importeTotal>
                        <moneda>DOLAR</moneda>';

				//query forma de pago
				$sqlFPago = "select fp.fpag_cod_fpagop, fx.fxfp_val_fxfp, fx.fxfp_num_dias
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

				$xml .='</infoFactura>';
				$xml .= $xmlDeta;


				$aDataInfoAdic['Direccion'] 	= $fact_dir_clie;
				$aDataInfoAdic['Telefono'] 		= $fact_tlf_cliente;
				$aDataInfoAdic['Email'] 		= $fact_email_clpv;
				$aDataInfoAdic['PreEntrada'] 	= $orden_compra;

				if(!empty($fact_cm1_fact)){
					$aDataInfoAdic['Observaciones'] = $fact_cm1_fact;
				}

				if (!empty($cod_almacen)) {
					$aDataInfoAdic['codigoAlmacen'] = $cod_almacen;
				}

				$etiqueta = array_keys($aDataInfoAdic);
				if (count($etiqueta) > 0) {
					$xml .= '<infoAdicional>';
					foreach ($etiqueta as $nom) {
						if ($aDataInfoAdic[$nom] != '')
							$xml.= "<campoAdicional nombre=\"$nom\">$aDataInfoAdic[$nom]</campoAdicional>";
					}
					$xml .= '</infoAdicional>';
				}

				$xml .='</factura>';

				//$oReturn->alert($xml);

				if($empr_tip_firma == 'N'){

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

				}elseif($empr_tip_firma == 'M'){

					//MultiFirma
					$nombre = $clave_acceso . ".xml";
					$serv = '';
					$archivo = '';

					$serv = DIR_FACTELEC."modulos/sri_offline/documentoselectronicos/generados";
					//$oReturn->alert($serv);
					$archivo = fopen($serv . '/' . $nombre, "w+");

					fwrite($archivo, utf8_encode($xml));
					fclose($archivo);
				}

				$sql_fact = "where fact_cod_empr = $id_empresa and fact_cod_sucu = $id_sucursal   and fact_cod_clpv = $fact_cod_clpv and fact_cod_fact = $id_factura ";

				unset($_SESSION['id']);
				$_SESSION['id'] = $id_factura;

				$_SESSION['contrRepor'] = $clave_acceso;
				$ret_num_fact = '';
				$asto_cod_ejer = 0;
				$rete_cod_asto = '';
				$oReturn->script("firmar('$nombre','$clave_acceso','$ruc_empr', '$fact_email_clpv', 'factura_venta', '$sql_fact', $id_factura,
                                          $fact_cod_clpv, '$ret_num_fact', '$asto_cod_ejer', '$rete_cod_asto', '$fact_fech_fact', 1, $id_sucursal)");
			}// fin check
		}// fin foreach
	}else {
		$oReturn->alert('Por favor realice una Busqueda...');
	}

	return $oReturn;
}
*/

//POSTGRESQL
function firmar_notaDebi($aForm = '')
{

	global $DSN_Ifx, $DSN;
	if (session_status() !== PHP_SESSION_ACTIVE) {
		session_start();
	}

	$oReturn = new xajaxResponse();

	$oIfx = new Dbo;
	$oIfx->DSN = $DSN_Ifx;
	$oIfx->Conectar();

	$oIfx1 = new Dbo;
	$oIfx1->DSN = $DSN_Ifx;
	$oIfx1->Conectar();

	//VARIABLES GLOBALES
	$id_empresa = $_SESSION['U_EMPRESA'];
	$id_sucursal = $_SESSION['U_SUCURSAL'];
	$array_nd = $_SESSION['U_FACT_ENVIO'];

	unset($_SESSION['aDataGirdErro']);
	unset($array);

	//  LECTURA SUCIA


	$sqlTipo = "select  sucu_tip_ambi, sucu_tip_emis  from saesucu where
                    sucu_cod_sucu = $id_sucursal ";

	if ($oIfx->Query($sqlTipo)) {
		if ($oIfx->NumFilas() > 0) {
			$ambiente = $oIfx->f('sucu_tip_ambi');
			$tip_emis = $oIfx->f('sucu_tip_emis');
		}
	}
	$oIfx->Free();

	$sql = "select empr_ac2_empr, empr_nom_empr, empr_ruc_empr , empr_dir_empr, empr_conta_sn, empr_rimp_sn,
                empr_num_resu from saeempr where empr_cod_empr = $id_empresa ";

	if ($oIfx->Query($sql)) {
		if ($oIfx->NumFilas() > 0) {
			$nombre_empr = trim($oIfx->f('empr_nom_empr'));
			$ruc_empr = $oIfx->f('empr_ruc_empr');
			$dir_empr = trim($oIfx->f('empr_dir_empr'));
			$conta_sn = $oIfx->f('empr_conta_sn');
			$empr_num_resu = $oIfx->f('empr_num_resu');
			$empr_ac2_empr = trim($oIfx->f('empr_ac2_empr'));
			$empr_rimp_sn = $oIfx->f('empr_rimp_sn');
		}
	}
	$oIfx->Free();

	if ($conta_sn == 'S') {
		$conta_sn = 'SI';
	} else {
		$conta_sn = 'NO';
	}

	//$oReturn->alert($array_fact);
	// DATOS
	if (count($array_nd) > 0) {
		foreach ($array_nd as $val) {
			$id_factura = $val[0];
			$check = $aForm[$id_factura . '_nd'];     //$fact_cod_fact.'_f'

			$xml = '';
			$xmlDeta = '';
			$descuento = 0;
			//$oReturn->alert($check);
			if (!empty($check)) {
				//COMSULTAMOS LOS DATOS Y FORMAMOS LOS XML
				$num8 = 12345678;
				$fact_nom_cliente = $val[3];
				$fact_tlf_cliente = $val[9];
				$fact_dir_clie = $val[10];
				$fact_ruc_clie = $val[2];
				$fact_fech_fact = $val[11];
				$fact_num_preimp = $val[1];
				$fact_nse_fact = $val[12];
				$fact_cod_clpv = $val[13];
				$fact_con_miva = $val[5];
				$fact_sin_miva = $val[6];
				$fact_iva = $val[4];
				$fact_ice = $val[14];
				$fact_val_irbp = $val[15];
				$fact_email_clpv = $val[8];
				$tipoIdentificacionComprador = $val[16];
				$numDocModificado = $val[17];
				$fechaEmisionDocSustento = $val[18];
				$clave_acceso = $val[19];
				$id_sucursal = $val[20];

				//                $fec_fact = $fact_fech_fact;
				//                print_r($fact_fech_fact);exit;
				$fec_fact = date("d/m/Y", strtotime($fact_fech_fact));
				$fechaEmisionDocSustento = date("d/m/Y", strtotime($fechaEmisionDocSustento));
				//                print_r($fechaEmisionDocSustento);exit;

				//$oReturn->alert($numDocModificado);
				//numDocModifica
				$seriFact = substr($numDocModificado, 0, 3);
				$seriPtoE = substr($numDocModificado, 3, 3);
				$secuFact = substr($numDocModificado, 7, 14);

				$numDocModificado = $seriFact . '-' . $seriPtoE . '-' . $secuFact;

				//$oReturn->alert($numDocModificado);

				$totalSinImpuestos = $fact_con_miva + $fact_sin_miva;
				$importeTotal = $totalSinImpuestos + $fact_iva + $fact_ice + $fact_val_irbp;

				$estable = substr($fact_nse_fact, 0, 3);
				$serie = substr($fact_nse_fact, 3, 6);


				$sqlDetalle = "select * from saedfac where 
                                    dfac_cod_fact = $id_factura and 
                                    dfac_cod_sucu = $id_sucursal and 
                                    dfac_cod_empr = $id_empresa ";
				$baseImponibleIRBP = 0;
				if ($oIfx1->Query($sqlDetalle)) {
					//$xmlDeta.='<detalles>';
					$bandera = 3;
					do {
						$dfac_cod_prod = $oIfx1->f("dfac_cod_prod");
						$dfac_cant_dfac = $oIfx1->f("dfac_cant_dfac");
						$dfac_precio_dfac = $oIfx1->f("dfac_precio_dfac");
						$dfac_mont_total = $oIfx1->f("dfac_mont_total");
						$dfac_por_iva = $oIfx1->f("dfac_por_iva");
						$dfac_por_irbp = $oIfx1->f("dfac_por_irbp");
						$dfac_des1_dfac = $oIfx1->f("dfac_des1_dfac");
						$dfac_des2_dfac = $oIfx1->f("dfac_des2_dfac");
						$descuento_general = $oIfx1->f("dfac_por_dsg");

						$descuento = $dfac_des1_dfac + $dfac_des2_dfac + $descuento_general;
						if ($descuento > 0)
							$descuento = number_format(($dfac_cant_dfac * number_format($dfac_precio_dfac, 2, '.', '')), 2, '.', '') - $dfac_mont_total;
						else
							$descuento = 0;

						//PRODUCTO
						$sqlDescripcionProd = "select prod_nom_prod, prod_cod_barra from saeprod where 
                                                    prod_cod_prod = '$dfac_cod_prod' and 
                                                    prod_cod_empr = $id_empresa and 
                                                    prod_cod_sucu = $id_sucursal ";
						if ($oIfx->Query($sqlDescripcionProd)) {
							if ($oIfx->NumFilas() > 0) {
								$prod_nom_prod = $oIfx->f('prod_nom_prod');
								$prod_cod_barra = $oIfx->f('prod_cod_barra');
							}
						}

						if (empty($prod_cod_barra)) {
							$prod_cod_barra = $dfac_cod_prod;
						}

						$xml_det .= '<motivo>
                                            <razon>' . $prod_nom_prod . '</razon>
                                            <valor>' . $dfac_mont_total . '</valor>
                                        </motivo>';

						//$prod_nom_prod = consulta_string_func($sqlDescripcionProd,"prod_nom_prod", $oIfx, '');

						/* $xmlDeta.='<detalle>';
                          $xmlDeta.="<codigoPrincipal>$dfac_cod_prod</codigoPrincipal>";
                          $xmlDeta.="<codigoAuxiliar>$prod_cod_barra</codigoAuxiliar>";
                          $xmlDeta.="<descripcion>$prod_nom_prod</descripcion>";
                          $xmlDeta.="<cantidad>$dfac_cant_dfac</cantidad>";
                          $xmlDeta.="<precioUnitario>".round($dfac_precio_dfac,2)."</precioUnitario>";
                          $xmlDeta.="<descuento>".round($descuento,2)."</descuento>";
                          $xmlDeta.="<precioTotalSinImpuesto>".round($dfac_mont_total,2)."</precioTotalSinImpuesto>";
                          $xmlDeta.='<impuestos>'; */

						if ($dfac_por_iva == 0) {
							$codigoPorcentaje = 0;
							$valor = 0.00;
							$tarifa = 0.00;
						} elseif ($dfac_por_iva == 12) {
							$codigoPorcentaje = 2;
							$valor = round((($dfac_mont_total * $dfac_por_iva) / 100), 2);
							$tarifa = 12.00;
							$bandera = 2;
						} elseif ($dfac_por_iva == 14) {
							$codigoPorcentaje = 3;
							$valor = round((($dfac_mont_total * $dfac_por_iva) / 100), 2);
							$tarifa = 14.00;
							$bandera = 3;
						} elseif ($dfac_por_iva == 15) {
							$codigoPorcentaje = 4;
							$valor = round((($dfac_mont_total * $dfac_por_iva) / 100), 2);
							$tarifa = 15.00;
							$bandera = 4;
						} elseif ($dfac_por_iva == 5) {
							$codigoPorcentaje = 5;
							$valor = round((($dfac_mont_total * $dfac_por_iva) / 100), 2);
							$tarifa = 5.00;
							$bandera = 5;
						} elseif ($dfac_por_iva == 13) {
							$codigoPorcentaje = 10;
							$valor = round((($dfac_mont_total * $dfac_por_iva) / 100), 2);
							$tarifa = 13.00;
							$bandera = 10;
						} elseif ($dfac_por_iva == 8) {
							$codigoPorcentaje = 8;
							$valor = round((($dfac_mont_total * $dfac_por_iva) / 100), 2);
							$tarifa = 8.00;
							$bandera = 8;
						}


						/* $xmlDeta.='<impuesto>';
                          $xmlDeta.='<codigo>2</codigo>';
                          $xmlDeta.="<codigoPorcentaje>$codigoPorcentaje</codigoPorcentaje>";
                          $xmlDeta.="<tarifa>$tarifa</tarifa>";
                          $xmlDeta.="<baseImponible>" . round($dfac_mont_total, 2) . "</baseImponible>";
                          $xmlDeta.="<valor>" . round($valor, 2) . "</valor>";
                          $xmlDeta.='</impuesto>'; */

						$unid_caja = 1;
						if ($dfac_por_irbp > 0) {
							// UNIDA X CAJA
							$sql_unid = "select nvl(prod_uni_caja,1) as prod_uni_caja  from saeprod where
                                                                        prod_cod_empr = $id_empresa and
                                                                        prod_cod_sucu = $id_sucursal and
                                                                        prod_cod_prod = '$dfac_cod_prod' ";
							$unid_caja = consulta_string_func($sql_unid, 'prod_uni_caja', $oIfx, 1);

							/* $xmlDeta.='<impuesto>';
                              $xmlDeta.='<codigo>5</codigo>';
                              $xmlDeta.="<codigoPorcentaje>5001</codigoPorcentaje>";
                              $xmlDeta.="<tarifa>0.00</tarifa>";
                              $xmlDeta.="<baseImponible>" . round($dfac_mont_total, 2) . "</baseImponible>";
                              $xmlDeta.="<valor>" . number_format($dfac_por_irbp * $dfac_cant_dfac * $unid_caja, 2, '.', '') . "</valor>";
                              $xmlDeta.='</impuesto>';
                              $baseImponibleIRBP += $dfac_mont_total; */
						} // fin
						//$xmlDeta.='</impuestos>';
						// $xmlDeta.='</detalle>';
					} while ($oIfx1->SiguienteRegistro());
					//$xmlDeta.='</detalles>';
				} // fin ifx
				//
				//$xmlDeta . -'</totalConImpuestos>';

				$rimpe = "";
				if ($empr_rimp_sn == "S") {
					$rimpe = "<contribuyenteRimpe>CONTRIBUYENTE RÉGIMEN RIMPE</contribuyenteRimpe>";
				}

				//correccion de caracteres especiales para xml
				$caracteres_errores = array("&");
				$caracteres_corregidos = array("&#38;");
				$nombre_empr = str_replace($caracteres_errores, $caracteres_corregidos, $nombre_empr);


				///CABECERA DEL $XML
				$xml .= '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
                                    <notaDebito id="comprobante" version="1.0.0">
                                        <infoTributaria>
                                            <ambiente>' . $ambiente . '</ambiente>
                                            <tipoEmision>' . $tip_emis . '</tipoEmision>
                                            <razonSocial>' . $nombre_empr . '</razonSocial>
                                            <nombreComercial>' . $nombre_empr . '</nombreComercial>
                                            <ruc>' . $ruc_empr . '</ruc>
                                            <claveAcceso>' . $clave_acceso . '</claveAcceso>
                                            <codDoc>05</codDoc>
                                            <estab>' . $estable . '</estab>
                                            <ptoEmi>' . $serie . '</ptoEmi>
                                            <secuencial>' . $fact_num_preimp . '</secuencial>
                                            <dirMatriz>' . $dir_empr . '</dirMatriz>
											' . $rimpe . '';
				if (!empty($empr_ac2_empr)) {
					$aret = explode('-', $empr_ac2_empr);

					$numret = intval($aret[2]);

					$xml .= '<agenteRetencion>' . $numret . '</agenteRetencion>';
				}
				$xml .= '</infoTributaria>
                                        <infoNotaDebito>
                                            <fechaEmision>' . $fec_fact . '</fechaEmision>';

				$xml .= ' <tipoIdentificacionComprador>' . $tipoIdentificacionComprador . '</tipoIdentificacionComprador>
                                    <razonSocialComprador>' . htmlspecialchars($fact_nom_cliente) . '</razonSocialComprador>
                                    <identificacionComprador>' . $fact_ruc_clie . '</identificacionComprador>';
				if ($empr_num_resu != '')
					$xml .= '<contribuyenteEspecial>' . $empr_num_resu . '</contribuyenteEspecial>';
				$xml .= '<obligadoContabilidad>' . $conta_sn . '</obligadoContabilidad>
                                            <codDocModificado>01</codDocModificado>
                                            <numDocModificado>' . $numDocModificado . '</numDocModificado>
                                            <fechaEmisionDocSustento>' . $fechaEmisionDocSustento . '</fechaEmisionDocSustento>
                                           <totalSinImpuestos>' . round($totalSinImpuestos, 2) . '</totalSinImpuestos>';
				$xml .= '<impuestos>';
				if ($fact_sin_miva > 0) {
					$xml .= '<impuesto>
                               <codigo>2</codigo>
                               <codigoPorcentaje>0</codigoPorcentaje>
                               <tarifa>0.00</tarifa>
                               <baseImponible>' . number_format($fact_sin_miva, 2, '.', '') . '</baseImponible>
                               <valor>0</valor>
                            </impuesto>';
				}
				if ($fact_con_miva > 0) {
					$xml .= '<impuesto>
                               <codigo>2</codigo>
                               <codigoPorcentaje>' . $bandera . '</codigoPorcentaje>
                               <tarifa>' . $tarifa . '</tarifa>
                               <baseImponible>' . number_format($fact_con_miva, 2, '.', '') . '</baseImponible>
                               <valor>' . number_format($fact_iva, 2, '.', '') . '</valor>
                            </impuesto>';
				}
				$xml .= '</impuestos>';

				$xml .= ' <valorTotal>' . round($importeTotal, 2) . '</valorTotal>
                         </infoNotaDebito>';

				$xml .= '<motivos>';
				$xml .= $xml_det;
				$xml .= '</motivos>';

				$aDataInfoAdic['Direccion'] = $fact_dir_clie;
				$aDataInfoAdic['Telefono'] = $fact_tlf_cliente;
				$aDataInfoAdic['Email'] = $fact_email_clpv;

				$etiqueta = array_keys($aDataInfoAdic);
				if (count($etiqueta) > 0) {
					$xml .= '<infoAdicional>';
					foreach ($etiqueta as $nom) {
						if ($aDataInfoAdic[$nom] != '')
							$xml .= "<campoAdicional nombre=\"$nom\">" . trim($aDataInfoAdic[$nom]) . "</campoAdicional>";
					}
					$xml .= '</infoAdicional>';
				}

				$xml .= '</notaDebito>';

				//$oReturn->alert($xml);
				// CREAR CARPETA ANEXO
				//$serv = "C:/Jireh/";
				//$ruta = $serv . "Comprobantes Electronicos";

				// CARPETA EMPRESA
				//$ruta_gene = $ruta . "/generados";
				$ruta_gene = DIR_FACTELEC . "modulos/sri_offline/documentoselectronicos/generados";
				//if (!file_exists($ruta))
				//    mkdir($ruta);

				if (!file_exists($ruta_gene))
					mkdir($ruta_gene);

				$nombre = $clave_acceso . ".xml";
				$archivo = fopen($ruta_gene . '/' . $nombre, "w+");

				//fwrite($archivo, $xml);
				fwrite($archivo, utf8_encode($xml));
				fclose($archivo);

				$sql_fact = "where fact_cod_empr = $id_empresa and fact_cod_sucu = $id_sucursal   and fact_cod_clpv = $fact_cod_clpv and fact_cod_fact = $id_factura ";

				unset($_SESSION['id']);
				$_SESSION['id'] = $id_factura;

				$_SESSION['contrRepor'] = $clave_acceso;
				$rete_cod_clpv = 0;
				$ret_num_fact = '';
				$asto_cod_ejer = 0;
				$rete_cod_asto = '';
				$fprv_fec_emis = '';
				$oReturn->script("firmar('$nombre','$clave_acceso','$ruc_empr', '$fact_email_clpv', 'nota_debito', '$sql_fact', $id_factura,
                                          $rete_cod_clpv, '$ret_num_fact', '$asto_cod_ejer', '$rete_cod_asto', '$fprv_fec_emis', 2  ,'$id_sucursal')");
			} // fin check
		} // fin foreach
	} else {
		$oReturn->alert('Por favor realice una Busqueda...');
	}

	//    $_SESSION['aDataGirdErro'] = $array;
	//    $oReturn->script("reporte();");
	return $oReturn;
}

//POSTGRESQL
function firmar_notaCred($aForm = '')
{

	global $DSN_Ifx, $DSN;
	if (session_status() !== PHP_SESSION_ACTIVE) {
		session_start();
	}

	$oReturn = new xajaxResponse();

	$oIfx = new Dbo;
	$oIfx->DSN = $DSN_Ifx;
	$oIfx->Conectar();

	$oIfx1 = new Dbo;
	$oIfx1->DSN = $DSN_Ifx;
	$oIfx1->Conectar();

	//VARIABLES GLOBALES
	$id_empresa = $_SESSION['U_EMPRESA'];
	$array_ncre = $_SESSION['U_FACT_ENVIO'];

	unset($_SESSION['aDataGirdErro']);

	unset($array);

	//  LECTURA SUCIA


	$sql = "select empr_ac2_empr, empr_nom_empr, empr_ruc_empr , empr_dir_empr, empr_rimp_sn,
			empr_num_resu, empr_conta_sn, empr_tip_firma
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
			$empr_ac2_empr = trim($oIfx->f('empr_ac2_empr'));
			$empr_rimp_sn = $oIfx->f('empr_rimp_sn');
		}
	}
	$oIfx->Free();

	//direccion sucursales
	$sql = "select sucu_cod_sucu, sucu_dir_sucu from saesucu where sucu_cod_empr = $id_empresa";
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

	//OTROS DATOS SRI
	$codDoc = '04';

	// DATOS
	if (count($array_ncre) > 0) {
		foreach ($array_ncre as $val) {
			$id_ncre = $val[0];
			$check = $aForm[$id_ncre . '_n'];

			$xml = '';
			$xmlDeta = '';
			$descuento = 0;
			//            $oReturn->alert($check);
			if (!empty($check)) {
				//COMSULTAMOS LOS DATOS Y FORMAMOS LOS XML
				$num8 = 12345678;
				$ncre_nom_cliente = $val[3];
				$ncre_tlf_cliente = $val[9];
				$ncre_dir_clie = $val[10];
				$ncre_dir_clie = str_replace('&','Y',$ncre_dir_clie);
				$ncre_ruc_clie = $val[2];
				$ncre_fech_fact = $val[11];
				$ncre_num_preimp = secuencialSri($val[1]);
				$ncre_nse_ncre = $val[12];
				$ncre_cod_clpv = $val[13];
				$ncre_con_miva = $val[5];
				$ncre_sin_miva = $val[6];
				$ncre_iva = $val[4];
				$ncre_ice = $val[14];
				$ncre_val_irbp = $val[15];
				$ncre_email_clpv = $val[8];
				$ncre_otr_fact = $val[17];
				$ncre_fle_fact = $val[18];
				$ncre_cm1_ncre = $val[19];
				$ncre_cod_fact = $val[20];
				$tipoIdentificacionComprador = $val[16];
				$id_sucursal = $val[21];
				$claveAcceso = $val[22];
				$ncre_cod_aux = $val[23];
				$fecha_docu = $val[24];

				//genera clave de acceso
				$ambiente = ambienteEmisionSri($oIfx, $id_empresa, $id_sucursal, 1);
				$tipoEmision = ambienteEmisionSri($oIfx, $id_empresa, $id_sucursal, 2);

				$totalSinImpuestos = $ncre_con_miva + $ncre_sin_miva;
				$valorModificacion = round(($totalSinImpuestos + $ncre_iva + $ncre_ice + $ncre_val_irbp), 2);

				//DATOS PARA EL XML
				$estab = substr($ncre_nse_ncre, 0, 3);
				$ptoEmi = substr($ncre_nse_ncre, 3, 6);

				$moneda = "DOLAR";
				$fec_xml = fecha_clave($ncre_fech_fact);
				//$fechaEmision = cambioFecha($ncre_fech_fact, 'mm/dd/aaaa', 'dd/mm/aaaa');
				$fechaEmision = date("d/m/Y", strtotime($ncre_fech_fact));
				//CONSULTO EL NUMERO CON LA SERIE DE LA FACTURA A LA QUE APLICA LA NOTA DE CREDITO
				$sqlFact = "select fact_nse_fact, fact_num_preimp, fact_fech_fact from saefact where 
                                    fact_cod_fact = $ncre_cod_fact and 
                                    fact_cod_empr = $id_empresa and 
                                    fact_cod_sucu = $id_sucursal";
				if ($oIfx1->Query($sqlFact)) {
					if ($oIfx1->NumFilas() > 0) {
						$fact_nse_fact = $oIfx1->f('fact_nse_fact');
						$fact_num_preimp = $oIfx1->f('fact_num_preimp');
						//$fact_fech_fact = fecha_sri($oIfx1->f('fact_fech_fact'));
						$fact_fech_fact = $oIfx1->f('fact_fech_fact');
						$serie = substr($fact_nse_fact, 0, 3);
						$puntEmis = substr($fact_nse_fact, 3, 6);
						$numDocModificado = $serie . '-' . $puntEmis . '-' . $fact_num_preimp;
					} else {
						if(!empty($fecha_docu)){
							$fact_fech_fact =date('Y-m-d', strtotime($fecha_docu));
						}
						$numDocModificado = trim($ncre_cod_aux);
					}
				}
				$oIfx1->Free();


				$sqlDetalle = "select * from saedncr where 
                                    dncr_cod_ncre = $id_ncre and 
                                    dncr_cod_sucu = $id_sucursal and 
                                    dncr_cod_empr = $id_empresa ";
				$baseImponibleIRBP = 0;
				$descuento = 0;
				if ($oIfx1->Query($sqlDetalle)) {
					if ($oIfx1->NumFilas() > 0) {
						$xmlDeta = '';
						$xmlTotaImpu = '';
						$bandera = 3;
						do {
							$dncr_por_iva = $oIfx1->f("dncr_por_iva");
							$dncr_mont_total = $oIfx1->f("dncr_mont_total");
							$dncr_precio_dfac = $oIfx1->f("dncr_precio_dfac");
							$dncr_cant_dfac = $oIfx1->f("dncr_cant_dfac");
							$dncr_cod_prod = $oIfx1->f("dncr_cod_prod");
							$dncr_por_irbp = $oIfx1->f("dncr_por_irbp");
							$dfac_des1_dfac = $oIfx1->f("dncr_des1_dfac");
							$dfac_des2_dfac = $oIfx1->f("dncr_des2_dfac");
							$descuento_general = $oIfx1->f("dncr_por_dsg");

							$descuento = $dfac_des1_dfac + $dfac_des2_dfac + $descuento_general;
							if ($descuento > 0)
								$descuento = number_format(($dncr_cant_dfac * number_format($dncr_precio_dfac, 2, '.', '')), 2, '.', '') - $dncr_mont_total;
							else
								$descuento = 0;

							//PRODUCTO
							$sqlDescripcionProd = "select prod_nom_prod, prod_apli_prod from saeprod where 
                                                            prod_cod_prod = '$dncr_cod_prod' and 
                                                            prod_cod_empr = $id_empresa and 
                                                            prod_cod_sucu = $id_sucursal ";
							$prod_nom_prod = consulta_string_func($sqlDescripcionProd, "prod_nom_prod", $oIfx, '');
							$prod_apli_prod = trim(consulta_string_func($sqlDescripcionProd, "prod_apli_prod", $oIfx, ''));
							if(!empty($prod_apli_prod)){
								$prod_apli_prod =strip_tags($prod_apli_prod );
								$prod_apli_prod=substr($prod_apli_prod,0,300);
							}

							if ($dncr_por_iva == 0) {
								$codigoPorcentaje = 0;
								$valor = 0.00;
								$tarifa = 0.00;
							} elseif ($dncr_por_iva == 12) {
								$codigoPorcentaje = 2;
								$valor = round((($dncr_mont_total * $dncr_por_iva) / 100), 2);
								$tarifa = 12.00;
								$bandera = 2;
							} elseif ($dncr_por_iva == 14) {
								$codigoPorcentaje = 3;
								$valor = round((($dncr_mont_total * $dncr_por_iva) / 100), 2);
								$tarifa = 14.00;
								$bandera = 3;
							} elseif ($dncr_por_iva == 15) {
								$codigoPorcentaje = 4;
								$valor = round((($dncr_mont_total * $dncr_por_iva) / 100), 2);
								$tarifa = 15.00;
								$bandera = 4;
							} elseif ($dncr_por_iva == 5) {
								$codigoPorcentaje = 5;
								$valor = round((($dncr_mont_total * $dncr_por_iva) / 100), 2);
								$tarifa = 5.00;
								$bandera = 5;
							} elseif ($dncr_por_iva == 13) {
								$codigoPorcentaje = 10;
								$valor = round((($dncr_mont_total * $dncr_por_iva) / 100), 2);
								$tarifa = 13.00;
								$bandera = 10;
							}
							elseif ($dncr_por_iva == 8) {
								$codigoPorcentaje = 8;
								$valor = round((($dncr_mont_total * $dncr_por_iva) / 100), 2);
								$tarifa = 8.00;
								$bandera = 8;
							}

							$xmlDeta .= '<detalle>';

							$xmlDeta .= "<codigoInterno>$dncr_cod_prod</codigoInterno>";
							$xmlDeta .= "<codigoAdicional>$dncr_cod_prod</codigoAdicional>";
							$xmlDeta .= "<descripcion>$prod_nom_prod</descripcion>";
							$xmlDeta .= "<cantidad>$dncr_cant_dfac</cantidad>";
							$xmlDeta .= "<precioUnitario>" . round($dncr_precio_dfac, 2) . "</precioUnitario>";
							$xmlDeta .= "<descuento>" . round($descuento, 2) . "</descuento>";
							$xmlDeta .= "<precioTotalSinImpuesto>" . round($dncr_mont_total, 2) . "</precioTotalSinImpuesto>";

							//VALIDAICON CAMPO APLICAICON LUBAMAQUI
							if (strlen($prod_apli_prod) > 0) {
								$xmlDeta .= '<detallesAdicionales>';
								$xmlDeta .= '<detAdicional nombre="Detalle" valor="' . $prod_apli_prod . '"/>';
								$xmlDeta .= '</detallesAdicionales>';
							}

							$xmlDeta .= '<impuestos>';

							$xmlDeta .= '<impuesto>';
							$xmlDeta .= '<codigo>2</codigo>';
							$xmlDeta .= "<codigoPorcentaje>$codigoPorcentaje</codigoPorcentaje>";
							$xmlDeta .= "<tarifa>$tarifa</tarifa>";
							$xmlDeta .= "<baseImponible>" . round($dncr_mont_total, 2) . "</baseImponible>";
							$xmlDeta .= "<valor>" . number_format($valor, 2, '.', '') . "</valor>";
							$xmlDeta .= '</impuesto>';

							if ($dncr_por_irbp > 0) {
								// UNIDA X CAJA
								$sql_unid = "select nvl(prod_uni_caja,1) as prod_uni_caja  from saeprod where
                                                            prod_cod_empr = $id_empresa and
                                                            prod_cod_sucu = $id_sucursal and
                                                            prod_cod_prod = '$dncr_cod_prod' ";
								$unid_caja = consulta_string_func($sql_unid, 'prod_uni_caja', $oIfx, 1);

								$xmlDeta .= '<impuesto>';
								$xmlDeta .= '<codigo>5</codigo>';
								$xmlDeta .= "<codigoPorcentaje>5001</codigoPorcentaje>";
								$xmlDeta .= "<tarifa>0.00</tarifa>";
								$xmlDeta .= "<baseImponible>" . number_format($dncr_mont_total, 2, '.', '') . "</baseImponible>";
								$xmlDeta .= "<valor>" . number_format($dncr_por_irbp * $dncr_cant_dfac * $unid_caja, 2, '.', '') . "</valor>";
								$xmlDeta .= '</impuesto>';

								$baseImponibleIRBP += $dncr_mont_total;
							}

							$xmlDeta .= '</impuestos>';

							$xmlDeta .= '</detalle>';
						} while ($oIfx1->SiguienteRegistro());
					}
				}


				$oIfx1->Free();

				$date = date_create($fact_fech_fact);
				$fact_fech_fact = date_format($date, 'd/m/Y');

				$rimpe = "";
				if ($empr_rimp_sn == "S") {
					$rimpe = "<contribuyenteRimpe>CONTRIBUYENTE RÉGIMEN RIMPE</contribuyenteRimpe>";
				}

				///CABECERA DEL $XML
				$xml .= '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
                        <notaCredito id="comprobante" version="1.1.0">';
				$xml .= "<infoTributaria>
                            <ambiente>$ambiente</ambiente>
                            <tipoEmision>$tipoEmision</tipoEmision>
                            <razonSocial>$nombre_empr</razonSocial>
                            <nombreComercial>$nombre_empr</nombreComercial>
                            <ruc>$ruc_empr</ruc>
                            <claveAcceso>$claveAcceso</claveAcceso>
                            <codDoc>$codDoc</codDoc>
                            <estab>$estab</estab>
                            <ptoEmi>$ptoEmi</ptoEmi>
                            <secuencial>$ncre_num_preimp</secuencial>
                            <dirMatriz>$dir_empr</dirMatriz>
							" . $rimpe . "";
				if (!empty($empr_ac2_empr)) {
					$aret = explode('-', $empr_ac2_empr);

					$numret = intval($aret[2]);

					//$xml .= '<agenteRetencion>' . $numret . '</agenteRetencion>';
				}

				$xml .= "</infoTributaria>";

				$xml .= "<infoNotaCredito>
                            <fechaEmision>$fechaEmision</fechaEmision>
							<dirEstablecimiento>" . $arrayDireSucu[$id_sucursal] . "</dirEstablecimiento>
                            <tipoIdentificacionComprador>$tipoIdentificacionComprador</tipoIdentificacionComprador>
                            <razonSocialComprador>" . htmlspecialchars($ncre_nom_cliente) . "</razonSocialComprador>
                            <identificacionComprador>$ncre_ruc_clie</identificacionComprador>";
				if ($empr_num_resu != '')
					$xml .= '<contribuyenteEspecial>' . $empr_num_resu . '</contribuyenteEspecial>';
				$xml .= "    <obligadoContabilidad>$conta_sn</obligadoContabilidad>
                            <codDocModificado>01</codDocModificado>
                            <numDocModificado>$numDocModificado</numDocModificado>
                            <fechaEmisionDocSustento>$fact_fech_fact</fechaEmisionDocSustento>
                            <totalSinImpuestos>" . round($totalSinImpuestos, 2) . "</totalSinImpuestos>
                            <valorModificacion>" . round($valorModificacion, 2) . "</valorModificacion>
                            <moneda>$moneda</moneda>";

				if ($ncre_con_miva > 0) {
					$xmlTotaImpu .= "<totalImpuesto>
                                    <codigo>2</codigo>
                                    <codigoPorcentaje>" . $bandera . "</codigoPorcentaje>
                                    <baseImponible>" . number_format($ncre_con_miva, 2, '.', '') . "</baseImponible>
                                    <valor>" . number_format($ncre_iva, 2, '.', '') . "</valor>
                            </totalImpuesto>";
				}

				if ($ncre_sin_miva > 0) {
					$xmlTotaImpu .= "<totalImpuesto>
                                    <codigo>2</codigo>
                                    <codigoPorcentaje>0</codigoPorcentaje>
                                    <baseImponible>" . round($ncre_sin_miva, 2) . "</baseImponible>
                                    <valor>0.00</valor>
                            </totalImpuesto>";
				}

				if ($ncre_val_irbp > 0) {
					$xmlTotaImpu .= '<totalImpuesto>
                                            <codigo>5</codigo>
                                            <codigoPorcentaje>5001</codigoPorcentaje>
                                            <baseImponible>' . $baseImponibleIRBP . '</baseImponible>
                                            <valor>' . round($ncre_val_irbp, 2) . '</valor>
                                      </totalImpuesto>';
				}


				$xml .= "<totalConImpuestos>" . $xmlTotaImpu . "</totalConImpuestos>
                            <motivo>$ncre_cm1_ncre</motivo>";
				$xml .= " </infoNotaCredito>";

				$xml .= "<detalles>" . $xmlDeta . "</detalles>";


				$aDataInfoAdic['Direccion'] = $ncre_dir_clie;
				$aDataInfoAdic['Telefono'] = $ncre_tlf_cliente;
				$aDataInfoAdic['Email'] = $ncre_email_clpv;

				$etiqueta = array_keys($aDataInfoAdic);
				if (count($etiqueta) > 0) {
					$xml .= '<infoAdicional>';
					foreach ($etiqueta as $nom) {
						if ($aDataInfoAdic[$nom] != '')
							$xml .= "<campoAdicional nombre=\"$nom\">$aDataInfoAdic[$nom]</campoAdicional>";
					}
					$xml .= '</infoAdicional>';
				}

				$xml .= '</notaCredito>';

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

					$nombre = $claveAcceso . ".xml";
					$archivo = fopen($ruta_gene . '/' . $nombre, "w+");

					//fwrite($archivo, $xml);
					fwrite($archivo, utf8_encode($xml));
					fclose($archivo);
				} elseif ($empr_tip_firma == 'M') {

					//MultiFirma
					$nombre = $claveAcceso . ".xml";
					$serv = '';
					$archivo = '';
					$serv = DIR_FACTELEC . "modulos/sri_offline/documentoselectronicos/generados";
					$archivo = fopen($serv . '/' . $nombre, "w+");

					//fwrite($archivo, $xml);
					fwrite($archivo, utf8_encode($xml));
					fclose($archivo);
				}

				$sql_ncre = "where ncre_cod_empr = $id_empresa and ncre_cod_sucu = $id_sucursal and ncre_cod_clpv = $ncre_cod_clpv and ncre_cod_ncre = $id_ncre ";

				$_SESSION['id'] = $id_ncre;

				$rete_cod_clpv = 0;
				$ret_num_fact = '';
				$asto_cod_ejer = 0;
				$rete_cod_asto = '';
				$fprv_fec_emis = '';

				$oReturn->script("firmar('$nombre','$claveAcceso','$ruc_empr', '$ncre_email_clpv', 'nota_credito', '$sql_ncre', $id_ncre,
                                          $ncre_cod_clpv,  '$ret_num_fact', '$asto_cod_ejer', '$rete_cod_asto', '$fprv_fec_emis', 3, $id_sucursal)");
			} // fin check
		} // fin foreach
	} else {
		$oReturn->alert('Por favor realice una Busqueda...');
	}

	//    $_SESSION['aDataGirdErro'] = $array;
	//    $oReturn->script("reporte();");
	return $oReturn;
}

//POSTGRESQL
function firmar_guiaRemi($aForm = '')
{

	global $DSN_Ifx, $DSN;
	session_start();

	$oReturn = new xajaxResponse();

	$oIfx = new Dbo;
	$oIfx->DSN = $DSN_Ifx;
	$oIfx->Conectar();

	$oIfx1 = new Dbo;
	$oIfx1->DSN = $DSN_Ifx;
	$oIfx1->Conectar();

	$oIfx2 = new Dbo;
	$oIfx2->DSN = $DSN_Ifx;
	$oIfx2->Conectar();

	//VARIABLES GLOBALES
	$id_empresa = $_SESSION['U_EMPRESA'];
	$array_guia = $_SESSION['U_FACT_ENVIO'];


	unset($_SESSION['aDataGirdErro']);
	unset($array);

	$sql = "select empr_nom_empr, empr_ruc_empr , empr_dir_empr, empr_conta_sn,
			empr_tip_firma, empr_num_resu, empr_ac2_empr 
			from saeempr where empr_cod_empr = $id_empresa ";
	if ($oIfx->Query($sql)) {
		if ($oIfx->NumFilas() > 0) {
			$nombre_empr = trim($oIfx->f('empr_nom_empr'));
			$ruc_empr = $oIfx->f('empr_ruc_empr');
			$dir_empr = trim($oIfx->f('empr_dir_empr'));
			$conta_sn = $oIfx->f('empr_conta_sn');
			$empr_num_resu = $oIfx->f('empr_num_resu');
			$empr_tip_firma = $oIfx->f('empr_tip_firma');
			$empr_ac2_empr = trim($oIfx->f('empr_ac2_empr'));
		}
	}
	$oIfx->Free();

	//direccion sucursales
	$sql = "select sucu_cod_sucu, sucu_dir_sucu from saesucu where sucu_cod_empr = $id_empresa";
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

	//OTROS DATOS SRI
	$codDoc = '06';

	if (count($array_guia) > 0) {
		foreach ($array_guia as $val) {
			$id_guia = $val[0];
			$check = $aForm[$id_guia . '_guia'];

			$xml = '';
			$xmlDeta = '';
			$descuento = 0;
			if (!empty($check)) {
				//COMSULTAMOS LOS DATOS Y FORMAMOS LOS XML
				$num8 = 12345678;
				$guia_cod_clpv = $val[1];
				$guia_nom_cliente = $val[2];
				$guia_tlf_cliente = $val[3];
				$guia_dir_clie = $val[4];
				$guia_ruc_clie = $val[5];
				$tipoIdentificacionComprador = $val[6];
				$guia_fech_guia = $val[7];
				$guia_hos_guia = $val[8];
				$guia_hol_guia = $val[9];
				$guia_num_preimp = $val[10];
				$guia_num_plac = $val[11];
				$guia_cm3_guia = trim($val[12]);
				$guia_email_clpv = $val[13];
				$guia_nse_guia = $val[14];
				$nom_trta = $val[15];
				$cid_trta = $val[16];
				$tipoIdentificacionTransportista = $val[17];
				$id_sucursal = $val[18];
				$claveAcceso = $val[19];
				$infoAdicional = $val[20];
				$codEstdest = $val[21];//CODIGO ESTABLECIMIENTO DESTINO
				$codAduanero = $val[22];//CODIGO ADUANERO
				$punto_partida = $val[23];//DIRECCION DE PARTIDA


				$config_jire = obtener_configuracion_jire($id_empresa, $id_sucursal);
				$para_inf_gfac = $config_jire[17];

				//genera clave de acceso
				$ambiente = ambienteEmisionSri($oIfx, $id_empresa, $id_sucursal, 1);
				$tipoEmision = ambienteEmisionSri($oIfx, $id_empresa, $id_sucursal, 2);

				$estab = substr($guia_nse_guia, 0, 3);
				$ptoEmi = substr($guia_nse_guia, 3, 6);

				///CABECERA DEL $XML
				$xml .= '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
                        <guiaRemision id="comprobante" version="1.0.0">';
				$xml .= "<infoTributaria>
                            <ambiente>$ambiente</ambiente>
                            <tipoEmision>$tipoEmision</tipoEmision>
                            <razonSocial>$nombre_empr</razonSocial>
                            <nombreComercial>$nombre_empr</nombreComercial>
                            <ruc>$ruc_empr</ruc>
                            <claveAcceso>$claveAcceso</claveAcceso>
                            <codDoc>$codDoc</codDoc>
                            <estab>$estab</estab>
                            <ptoEmi>$ptoEmi</ptoEmi>
                            <secuencial>$guia_num_preimp</secuencial>
                            <dirMatriz>$dir_empr</dirMatriz>
                            </infoTributaria>";
				$xml .= "<infoGuiaRemision>";

				//VALIDACION DIRECCION PARAMETRO INFO ADICIONAL GUIAS SAEPARA
				if($para_inf_gfac=='S'){

					// ciudades
					$sql = "select ciud_cod_ciud, ciud_nom_ciud, ciud_cod_pais, ciud_cod_prov  from saeciud where ciud_cod_ciud = $punto_partida";
					$ciud_ori = consulta_string($sql, 'ciud_nom_ciud', $oIfx2, '');
					$pais_ori = consulta_string($sql, 'ciud_cod_pais', $oIfx2, 0);
					$prov_ori = consulta_string($sql, 'ciud_cod_prov', $oIfx2, 0);
		
					//provincia origen
					$sql="select prov_des_prov from saeprov where prov_cod_prov=$prov_ori";
					$provincia_origen = consulta_string($sql, 'prov_des_prov', $oIfx2, '');
		
					//pais origen
					$sql="select pais_des_pais from saepais where pais_cod_pais=$pais_ori";
					$pais_origen = consulta_string($sql, 'pais_des_pais', $oIfx2, '');


					$xml .="<dirPartida>$ciud_ori $provincia_origen $pais_origen</dirPartida>";

				}
				else{
					$xml .="<dirPartida>$dir_empr</dirPartida>";
				}


                $xml .="<razonSocialTransportista>$nom_trta</razonSocialTransportista>
                            <tipoIdentificacionTransportista>$tipoIdentificacionTransportista</tipoIdentificacionTransportista>
                            <rucTransportista>$cid_trta</rucTransportista>
                            <obligadoContabilidad>$conta_sn</obligadoContabilidad>";
				if (!empty($empr_num_resu)) {
					$xml .= '   <contribuyenteEspecial>' . $empr_num_resu . '</contribuyenteEspecial>';
				}

				$xml .= "    <fechaIniTransporte>$guia_hos_guia</fechaIniTransporte>
                            <fechaFinTransporte>$guia_hol_guia</fechaFinTransporte>
                            <placa>$guia_num_plac</placa>
                        </infoGuiaRemision>";

				$xml .= "<destinatarios>";

				//SI LA CONFIGURACION ES GUIA-FACTURA O PEDIDO-GUIA
				$sql = "select para_sec_para from saepara where para_cod_empr = $id_empresa and para_cod_sucu = $id_sucursal";
				$para_sec_para = consulta_string_func($sql, "para_sec_para", $oIfx2, '');
				$sqlDetaGuia = "select dgui_fac_dgui from saedgui where 
                                        dgui_cod_guia = $id_guia and 
                                        dgui_cod_empr = $id_empresa  and 
                                        dgui_cod_sucu = $id_sucursal group by dgui_fac_dgui";
				if ($oIfx1->Query($sqlDetaGuia)) {
					if ($oIfx1->NumFilas() > 0) {
						do {
							$dgui_fac_dgui = $oIfx1->f("dgui_fac_dgui");
							$xml .= " <destinatario>
                                            <identificacionDestinatario>$guia_ruc_clie</identificacionDestinatario>
                                            <razonSocialDestinatario>" . htmlspecialchars($guia_nom_cliente) . "</razonSocialDestinatario>
                                            <dirDestinatario>$guia_dir_clie</dirDestinatario>
                                            <motivoTraslado>$guia_cm3_guia</motivoTraslado>";
											if(!empty($codAduanero)){
												$xml .= "<docAduaneroUnico>$codAduanero</docAduaneroUnico>";
											}
											if(!empty($codEstdest)){
												$xml .= "<codEstabDestino>$codEstdest</codEstabDestino>";
											}
										
							if ($dgui_fac_dgui != '' && $para_sec_para == 1) {
								$sqlFact = "select fact_nse_fact, fact_num_preimp, fact_auto_sri, fact_fech_fact from saefact where
                                                       fact_cod_fact = $dgui_fac_dgui and 
                                                       fact_cod_empr = $id_empresa and 
                                                       fact_cod_sucu = $id_sucursal ";
								if ($oIfx2->Query($sqlFact)) {
									if ($oIfx2->NumFilas() > 0) {
										do {
											$serie = substr($oIfx2->f("fact_nse_fact"), 0, 3);
											$ptoEmi = substr($oIfx2->f("fact_nse_fact"), 3, 6);
											$fact_num_preimp = $oIfx2->f("fact_num_preimp");
											$fact_auto_sri = $oIfx2->f("fact_auto_sri");
											$fact_fech_fact = cambioFechaSri($oIfx2->f("fact_fech_fact"), 'aaaa-mm-dd', 'dd/mm/aaaa');

											$numDocSustento = $serie . '-' . $ptoEmi . '-' . $fact_num_preimp;

											$xml .= "<codDocSustento>01</codDocSustento>
                                                            <numDocSustento>$numDocSustento</numDocSustento>
                                                            <numAutDocSustento>$fact_auto_sri</numAutDocSustento>
                                                            <fechaEmisionDocSustento>$fact_fech_fact</fechaEmisionDocSustento>";
										} while ($oIfx2->SiguienteRegistro());
									}
								} // if oix2
								$sqlDeta = "select dgui_cod_prod, dgui_cant_dgui, dgui_nom_prod from saedgui where 
                                                        dgui_fac_dgui = '$dgui_fac_dgui' and 
                                                        dgui_cod_guia = $id_guia     and 
                                                        dgui_cod_empr = $id_empresa and 
                                                        dgui_cod_sucu = $id_sucursal";
							} elseif ($dgui_fac_dgui == '' && $para_sec_para == 1) {
								$sqlDeta = "select dgui_cod_prod, dgui_cant_dgui, dgui_nom_prod from saedgui where 
                                                    (dgui_fac_dgui = ''  or  dgui_fac_dgui is null ) and 
                                                     dgui_cod_guia = $id_guia  and 
                                                     dgui_cod_empr = $id_empresa and 
                                                     dgui_cod_sucu = $id_sucursal ";
							} else {
								$sqlDeta = "select dgui_cod_prod, dgui_cant_dgui, dgui_nom_prod from saedgui where 
                                                    dgui_cod_guia = $id_guia  and 
                                                    dgui_cod_empr = $id_empresa and 
                                                    dgui_cod_sucu = $id_sucursal";
							}


							$xml .= "<detalles>";
							if ($oIfx2->Query($sqlDeta)) {
								if ($oIfx2->NumFilas() > 0) {
									do {
										$codigoInterno = $codigoAdicional = $oIfx2->f("dgui_cod_prod");
										$cantidad = $oIfx2->f("dgui_cant_dgui");
										$descripcion = $oIfx2->f("dgui_nom_prod");

										$xml .= "<detalle>
                                                    <codigoInterno>$codigoInterno</codigoInterno>
                                                    <codigoAdicional>$codigoAdicional</codigoAdicional>
                                                    <descripcion>$descripcion</descripcion>
                                                    <cantidad>$cantidad</cantidad>
                                                </detalle>";
									} while ($oIfx2->SiguienteRegistro());
								}
							}
							$oIfx2->Free();

							$xml .= "</detalles>
                                    </destinatario>";
						} while ($oIfx1->SiguienteRegistro());
					}
				} // fin oIfx1
				$oIfx1->Free();

				$xml .= "</destinatarios>";

				

				///INFORMACION ADICIONAL GUIAS DE REMISION 
				if(!empty($infoAdicional)){
					// Separar las líneas usando <br /> como delimitador
					$texto_adi = explode('<br />', $infoAdicional);

					// Recorrer cada línea
					foreach ($texto_adi as $val) {
						// Eliminar espacios en blanco extra al principio y al final
						$val = trim($val);

						if (!empty($val)) {
							// Separar por el primer ":"
							$text_linea = explode(':', $val, 2); // Limitar la separación a 2 partes

							if (count($text_linea) == 2) {
								// Si se encontró el ":", agregar al XML
								$campo_nombre = trim($text_linea[0]);
								$campo_valor = trim($text_linea[1]);
								$campo_valor = substr($campo_valor,0,300);
								$aDataInfoAdic[htmlspecialchars($campo_nombre.':')] = htmlspecialchars($campo_valor);
							} 
						}
					}

				}
				//$aDataInfoAdic['AGENTE RETENCION'] = 'NAC-DNCRASC20-00000001';
				if(!empty($empr_ac2_empr)){
					$aDataInfoAdic['AGENTE DE RETENCION'] = $empr_ac2_empr;
				}

				$aDataInfoAdic['Email'] = $guia_email_clpv;
				$aDataInfoAdic['Telefono'] = $guia_tlf_cliente;

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
							$xml .= "<campoAdicional nombre=\"$nom\">$aDataInfoAdic[$nom]</campoAdicional>";
					}
					//  $xml.= '<campoAdicional nombre="dasd"></campoAdicional>';
					$xml .= '</infoAdicional>';
				}
				$xml .= '</guiaRemision>';


				if ($empr_tip_firma == 'N') {

					// CREAR CARPETA ANEXO
					$serv = "C:/Jireh/";
					$ruta = $serv . "Comprobantes Electronicos";
					// CARPETA EMPRESA
					$ruta_gene = $ruta . "/generados";
					if (!file_exists($ruta)) {
						mkdir($ruta);
					}

					if (!file_exists($ruta_gene)) {
						mkdir($ruta_gene);
					}

					$nombre = $claveAcceso . ".xml";
					$archivo = fopen($ruta_gene . '/' . $nombre, "w+");
					fwrite($archivo, utf8_encode($xml));
					fclose($archivo);
				} elseif ($empr_tip_firma == 'M') {

					//MultiFirma	
					$nombre = $claveAcceso . ".xml";
					$serv = '';
					$archivo = '';
					$serv = DIR_FACTELEC . "modulos/sri_offline/documentoselectronicos/generados";
					$archivo = fopen($serv . '/' . $nombre, "w+");

					//fwrite($archivo, $xml);
					fwrite($archivo, utf8_encode($xml));
					fclose($archivo);
				}

				$sql_guia = "where guia_cod_empr = $id_empresa and guia_cod_sucu = $id_sucursal and guia_cod_clpv = $guia_cod_clpv and guia_cod_guia = $id_guia ";

				$asto_cod_ejer = 0;
				$rete_cod_asto = '';
				$oReturn->script("firmar('$nombre','$claveAcceso','$ruc_empr', '$guia_email_clpv', 'guia_remision', '$sql_guia', $id_guia,
                                         $guia_cod_clpv, '$guia_num_preimp', '$asto_cod_ejer', '$rete_cod_asto', '$guia_fech_guia', 4, $id_sucursal)");
			} // fin check
		} // fin foreach
	} else {
		$oReturn->alert('Por favor realice una Busqueda...');
	}

	return $oReturn;
}

//NO SE USA
function firmar_guiaRemiFlor($aForm = '')
{

	global $DSN_Ifx, $DSN;
	if (session_status() !== PHP_SESSION_ACTIVE) {
		session_start();
	}

	$oReturn = new xajaxResponse();

	$oIfx = new Dbo;
	$oIfx->DSN = $DSN_Ifx;
	$oIfx->Conectar();

	$oIfx1 = new Dbo;
	$oIfx1->DSN = $DSN_Ifx;
	$oIfx1->Conectar();

	$oIfx2 = new Dbo;
	$oIfx2->DSN = $DSN_Ifx;
	$oIfx2->Conectar();

	//VARIABLES GLOBALES
	$id_empresa = $_SESSION['U_EMPRESA'];
	$id_sucursal = $_SESSION['U_SUCURSAL'];
	$array_guia = $_SESSION['U_FACT_ENVIO'];

	unset($_SESSION['aDataGirdErro']);
	unset($array);

	// DATOS DE LA EMPRESA
	$sqlTipo = "select  sucu_tip_ambi, sucu_tip_emis  from saesucu where  sucu_cod_sucu = $id_sucursal ";
	if ($oIfx->Query($sqlTipo)) {
		if ($oIfx->NumFilas() > 0) {
			$ambiente = $oIfx->f('sucu_tip_ambi');
			$tipoEmision = $oIfx->f('sucu_tip_emis');
		}
	}
	$oIfx->Free();

	$sql = "select empr_nom_empr, empr_ruc_empr , empr_dir_empr, empr_conta_sn, empr_rimp_sn,
                empr_num_resu from saeempr where empr_cod_empr = $id_empresa ";
	if ($oIfx->Query($sql)) {
		if ($oIfx->NumFilas() > 0) {
			$nombre_empr = trim($oIfx->f('empr_nom_empr'));
			$ruc_empr = $oIfx->f('empr_ruc_empr');
			$dir_empr = trim($oIfx->f('empr_dir_empr'));
			$conta_sn = $oIfx->f('empr_conta_sn');
			$empr_num_resu = $oIfx->f('empr_num_resu');
			$empr_rimp_sn = $oIfx->f('empr_rimp_sn');
		}
	}
	$oIfx->Free();

	if ($conta_sn == 'S') {
		$conta_sn = 'SI';
	} else {
		$conta_sn = 'NO';
	}

	//OTROS DATOS SRI
	$codDoc = '06';

	if (count($array_guia) > 0) {
		foreach ($array_guia as $val) {
			$id_guia = $val[0];
			$check = $aForm[$id_guia . '_guia'];

			$xml = '';
			$xmlDeta = '';
			$descuento = 0;
			if (!empty($check)) {
				//COMSULTAMOS LOS DATOS Y FORMAMOS LOS XML
				$num8 = 12345678;
				$guia_cod_clpv = $val[1];
				$guia_nom_cliente = $val[2];
				$guia_tlf_cliente = $val[3];
				$guia_dir_clie = $val[4];
				$guia_ruc_clie = $val[5];
				$tipoIdentificacionComprador = $val[6];
				$guia_fech_guia = $val[7];
				$guia_hos_guia = $val[8];
				$guia_hol_guia = $val[9];
				$guia_num_preimp = $val[10];
				$guia_num_plac = $val[11];
				$guia_cm3_guia = trim($val[12]);
				$guia_email_clpv = $val[13];
				$guia_nse_guia = $val[14];
				$nom_trta = $val[15];
				$cid_trta = $val[16];
				$tipoIdentificacionTransportista = $val[17];

				$estab = substr($guia_nse_guia, 0, 3);
				$ptoEmi = substr($guia_nse_guia, 3, 6);
				$fec_xml = fecha_clave($guia_fech_guia);

				$claveAcceso = $fec_xml . $codDoc . $ruc_empr . $ambiente . $guia_nse_guia . $guia_num_preimp . $num8 . $tipoEmision;
				$digitoVerificador = digitoVerificador($claveAcceso);
				$claveAcceso = $claveAcceso . $digitoVerificador;

				$rimpe = "";
				if ($empr_rimp_sn == "S") {
					$rimpe = "<contribuyenteRimpe>CONTRIBUYENTE RÉGIMEN RIMPE</contribuyenteRimpe>";
				}

				///CABECERA DEL $XML
				$xml .= '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
                        <guiaRemision id="comprobante" version="1.0.0">';
				$xml .= "<infoTributaria>
                            <ambiente>$ambiente</ambiente>
                            <tipoEmision>$tipoEmision</tipoEmision>
                            <razonSocial>$nombre_empr</razonSocial>
                            <nombreComercial>$nombre_empr</nombreComercial>
                            <ruc>$ruc_empr</ruc>
                            <claveAcceso>$claveAcceso</claveAcceso>
                            <codDoc>$codDoc</codDoc>
                            <estab>$estab</estab>
                            <ptoEmi>$ptoEmi</ptoEmi>
                            <secuencial>$guia_num_preimp</secuencial>
                            <dirMatriz>$dir_empr</dirMatriz>
							" . $rimpe . "
                            </infoTributaria>";
				$xml .= "<infoGuiaRemision>
                            <dirPartida>$dir_empr</dirPartida>
                            <razonSocialTransportista>$nom_trta</razonSocialTransportista>
                            <tipoIdentificacionTransportista>$tipoIdentificacionTransportista</tipoIdentificacionTransportista>
                            <rucTransportista>$cid_trta</rucTransportista>
                            <obligadoContabilidad>$conta_sn</obligadoContabilidad>";
				$xml .= '   <contribuyenteEspecial>' . $empr_num_resu . '</contribuyenteEspecial>';
				$xml .= "    <fechaIniTransporte>$guia_hos_guia</fechaIniTransporte>
                            <fechaFinTransporte>$guia_hol_guia</fechaFinTransporte>
                            <placa>$guia_num_plac</placa>
                        </infoGuiaRemision>";

				$xml .= "<destinatarios>";

				//SI LA CONFIGURACION ES GUIA-FACTURA O PEDIDO-GUIA
				$sql = "select para_sec_para from saepara where para_cod_empr = $id_empresa and para_cod_sucu = $id_sucursal";
				$para_sec_para = consulta_string_func($sql, "para_sec_para", $oIfx2, '');

				$sqlDetaGuia = "select max(dguf_cod_fpak) as dguf_cod_fpak from saedguf where dguf_cod_gref = $id_guia and dguf_cod_empr = $id_empresa
                                and dguf_cod_sucu = $id_sucursal";
				if ($oIfx1->Query($sqlDetaGuia)) {
					if ($oIfx1->NumFilas() > 0) {
						do {
							$dgui_fac_dgui = $oIfx1->f("dguf_cod_fpak");
							$xml .= " <destinatario>
                                            <identificacionDestinatario>$guia_ruc_clie</identificacionDestinatario>
                                            <razonSocialDestinatario>" . htmlspecialchars($guia_nom_cliente) . "</razonSocialDestinatario>
                                            <dirDestinatario>$guia_dir_clie</dirDestinatario>
                                            <motivoTraslado>$guia_cm3_guia</motivoTraslado>";
							if ($dgui_fac_dgui != '' && $para_sec_para == 1) {
								$sqlFact = "select fact_nse_fact, fact_num_preimp, fact_auto_sri, fact_fech_fact from saefact where
                                                       fact_cod_fact = $dgui_fac_dgui and 
                                                       fact_cod_empr = $id_empresa and 
                                                       fact_cod_sucu = $id_sucursal ";
								if ($oIfx2->Query($sqlFact)) {
									if ($oIfx2->NumFilas() > 0) {
										do {
											$serie = substr($oIfx2->f("fact_nse_fact"), 0, 3);
											$ptoEmi = substr($oIfx2->f("fact_nse_fact"), 3, 6);
											$fact_num_preimp = $oIfx2->f("fact_num_preimp");
											$fact_auto_sri = $oIfx2->f("fact_auto_sri");
											$fact_fech_fact = cambioFecha($oIfx2->f("fact_fech_fact"), 'mm/dd/aaaa', 'dd/mm/aaaa');

											$numDocSustento = $serie . '-' . $ptoEmi . '-' . $fact_num_preimp;

											$xml .= "<codDocSustento>01</codDocSustento>
                                                            <numDocSustento>$numDocSustento</numDocSustento>
                                                            <numAutDocSustento>$fact_auto_sri</numAutDocSustento>
                                                            <fechaEmisionDocSustento>$fact_fech_fact</fechaEmisionDocSustento>";
										} while ($oIfx2->SiguienteRegistro());
									}
								} // if oix2
								$sqlDeta = "select dguf_cod_ramo, dguf_can_dguf, dguf_cod_vard from saedguf where dguf_cod_gref = $id_guia and dguf_cod_fpak = $dgui_fac_dgui
                                            and dguf_cod_empr = $id_empresa and dguf_cod_sucu = $id_sucursal";
							} elseif ($dgui_fac_dgui == '' && $para_sec_para == 1) {
								$sqlDeta = "select dguf_cod_ramo, dguf_can_dguf, dguf_cod_vard from saedguf where dguf_cod_gref = $id_guia and (dguf_cod_fpak is null or dguf_cod_fpak = '') 
                                            and dguf_cod_empr = $id_empresa and dguf_cod_sucu = $id_sucursal";
							} else {
								$sqlDeta = "select dguf_cod_ramo, dguf_can_dguf, dguf_cod_vard from saedguf where dguf_cod_gref = $id_guia 
                                            and dguf_cod_empr = $id_empresa and dguf_cod_sucu = $id_sucursal";
							}


							$xml .= "<detalles>";
							// $oReturn->alert($sqlDeta);
							if ($oIfx2->Query($sqlDeta)) {
								if ($oIfx2->NumFilas() > 0) {
									do {
										$codigoInterno = $codigoAdicional = $oIfx2->f("dguf_cod_ramo");
										$cantidad = $oIfx2->f("dguf_can_dguf");
										$dguf_cod_vard = $oIfx2->f("dguf_cod_vard");

										//query producto
										$sql = "select v.vard_nom_vard, e.espe_nom_espe from saevard v, saeespe e
                                                where
                                                v.vard_cod_espe = e.espe_cod_espe and
                                                v.vard_cod_finc = e.finc_cod_finc and
                                                v.vard_cod_vard = '$dguf_cod_vard'";
										$vard_nom_vard = consulta_string_func($sql, 'vard_nom_vard', $oIfx, '');
										$espe_nom_espe = consulta_string_func($sql, 'espe_nom_espe', $oIfx, '');

										$descripcion = $espe_nom_espe . ' ' . $vard_nom_vard;

										$xml .= "<detalle>
                                                    <codigoInterno>$codigoInterno</codigoInterno>
                                                    <codigoAdicional>$codigoAdicional</codigoAdicional>
                                                    <descripcion>$descripcion</descripcion>
                                                    <cantidad>$cantidad</cantidad>
                                                </detalle>";
									} while ($oIfx2->SiguienteRegistro());
								}
							}
							$oIfx2->Free();

							$xml .= "</detalles>
                                    </destinatario>";
						} while ($oIfx1->SiguienteRegistro());
					}
				} // fin oIfx1
				$oIfx1->Free();

				$xml .= "</destinatarios>";

				$aDataInfoAdic['Email'] = $guia_email_clpv;
				$aDataInfoAdic['Telefono'] = $guia_tlf_cliente;

				$etiqueta = array_keys($aDataInfoAdic);
				if (count($etiqueta) > 0) {
					$xml .= '<infoAdicional>';
					foreach ($etiqueta as $nom) {
						if ($aDataInfoAdic[$nom] != '')
							$xml .= "<campoAdicional nombre=\"$nom\">$aDataInfoAdic[$nom]</campoAdicional>";
					}
					//  $xml.= '<campoAdicional nombre="dasd"></campoAdicional>';
					$xml .= '</infoAdicional>';
				}

				$xml .= '</guiaRemision>';

				//$oReturn->alert($xml);

				// CREAR CARPETA ANEXO
				$serv = "C:/Jireh/";
				$ruta = $serv . "Comprobantes Electronicos";
				// CARPETA EMPRESA
				$ruta_gene = $ruta . "/generados";
				if (!file_exists($ruta)) {
					mkdir($ruta);
				}

				if (!file_exists($ruta_gene)) {
					mkdir($ruta_gene);
				}

				$nombre = $claveAcceso . ".xml";
				$archivo = fopen($ruta_gene . '/' . $nombre, "w+");
				fwrite($archivo, utf8_encode($xml));
				fclose($archivo);

				$sql_guia = "where gref_cod_empr = $id_empresa and gref_cod_sucu = $id_sucursal and gref_cod_clpv = $guia_cod_clpv and gref_cod_gref = $id_guia ";

				$asto_cod_ejer = 0;
				$rete_cod_asto = '';
				$oReturn->script("firmar('$nombre','$claveAcceso','$ruc_empr', '$guia_email_clpv', 'guia_remision_flor', '$sql_guia', $id_guia,
                                          $guia_cod_clpv, '$guia_num_preimp', '$asto_cod_ejer', '$rete_cod_asto', '$guia_fech_guia', 10  )");
			} // fin check
		} // fin foreach
	} else {
		$oReturn->alert('Por favor realice una Busqueda...');
	}

	return $oReturn;
}

//POSTGRESQL
function firmar_reteGastV1($aForm = '')
{

	global $DSN_Ifx, $DSN;
	if (session_status() !== PHP_SESSION_ACTIVE) {
		session_start();
	}

	$oReturn = new xajaxResponse();

	$oIfx = new Dbo;
	$oIfx->DSN = $DSN_Ifx;
	$oIfx->Conectar();

	$oIfx1 = new Dbo;
	$oIfx1->DSN = $DSN_Ifx;
	$oIfx1->Conectar();

	$oIfxA = new Dbo;
	$oIfxA->DSN = $DSN_Ifx;
	$oIfxA->Conectar();

	//VARIABLES GLOBALES
	$id_empresa = $_SESSION['U_EMPRESA'];
	$array_gasto = $_SESSION['U_FACT_ENVIO'];

	unset($_SESSION['aDataGirdErro']);
	unset($array);

	//  LECTURA SUCIA


	$sql = "select empr_ac2_empr, empr_nom_empr, empr_ruc_empr, empr_dir_empr, empr_rimp_sn,
			empr_conta_sn, empr_num_resu, empr_tip_firma 
			from saeempr 
			where empr_cod_empr = $id_empresa ";
	if ($oIfx->Query($sql)) {
		if ($oIfx->NumFilas() > 0) {
			$razonSocial = trim($oIfx->f('empr_nom_empr'));
			$ruc_empr = $oIfx->f('empr_ruc_empr');
			$dirMatriz = trim($oIfx->f('empr_dir_empr'));
			$conta_sn = $oIfx->f('empr_conta_sn');
			$empr_num_resu = $oIfx->f('empr_num_resu');
			$empr_tip_firma = $oIfx->f('empr_tip_firma');
			$empr_ac2_empr = trim($oIfx->f('empr_ac2_empr'));
			$empr_rimp_sn = $oIfx->f('empr_rimp_sn');
		}
	}
	$oIfx->Free();

	//direccion sucursales
	$sql = "select sucu_cod_sucu, sucu_dir_sucu from saesucu where sucu_cod_empr = $id_empresa";
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
		$obligadoContabilidad = 'SI';
	} else {
		$obligadoContabilidad = 'NO';
	}

	//OTROS DATOS SRI
	$codDoc = '07';

	if (count($array_gasto) > 0) {
		foreach ($array_gasto as $val) {
			$rete_cod_clpv = $val[0];
			$ret_num_ret = $val[8];
			$ret_num_fact = $val[6];
			$asto_cod_ejer = $val[5];
			$id_sucursal = $val[14];
			$serial = $rete_cod_clpv . '_' . $ret_num_ret . '_' . $asto_cod_ejer . '_' . $ret_num_fact . '_' . $id_empresa . '_' . $id_sucursal . '_rg';
			$check = $aForm[$serial];

			$xml = '';
			$xmlImpu = '';
			$descuento = 0;
			if (!empty($check)) {
				//COMSULTAMOS LOS DATOS Y FORMAMOS LOS XML
				$num8 = 12345678;
				$fprv_num_seri = $val[7];
				$codDocSustento = $val[12];
				$rete_nom_benf = $val[1];
				$rete_dire_benf = $val[2];
				$fprv_fec_emis = $val[4];
				$ret_email_clpv = $val[3];
				$rete_cod_asto = $val[13];
				$ret_ser_ret = $val[9];
				$tipoIdentificacionSujetoRetenido = $val[10];
				$claveAcceso = $val[15];

				//genera clave de acceso
				$ambiente = ambienteEmisionSri($oIfx, $id_empresa, $id_sucursal, 1);
				$tipoEmision = ambienteEmisionSri($oIfx, $id_empresa, $id_sucursal, 2);

				//COMSULTAMOS LOS DATOS Y FORMAMOS LOS XML

				$sqlReteGast = "select * from saefprv  where
                    fprv_cod_empr = $id_empresa and 
                    fprv_cod_sucu = $id_sucursal  and 
                    fprv_cod_clpv = $rete_cod_clpv and 
                    fprv_num_fact = '$ret_num_fact' and 
                    fprv_cod_ejer = '$asto_cod_ejer' and  
                    fprv_cod_asto = '$rete_cod_asto' and  
                    fprv_fec_emis = '$fprv_fec_emis' ";

				//$oReturn->alert($sqlReteGast);
				if ($oIfx->Query($sqlReteGast)) {
					if ($oIfx->NumFilas() > 0) {
						do {

							$rete_ruci_benf = $oIfx->f('fprv_ruc_prov');
							$rete_telf_benf = $oIfx->f('fprv_tel_clpv');
							//RETENCIONE SAEFPRV
							$fprv_val_ret1 = $oIfx->f('fprv_val_ret1');
							$fprv_cod_rtf1 = $oIfx->f('fprv_cod_rtf1');
							$fprv_por_ret1 = $oIfx->f('fprv_por_ret1');
							$fprv_val_bas1 = $oIfx->f('fprv_val_bas1');

							$fprv_val_ret2 = $oIfx->f('fprv_val_ret2');
							$fprv_cod_rtf2 = $oIfx->f('fprv_cod_rtf2');
							$fprv_por_ret2 = $oIfx->f('fprv_por_ret2');
							$fprv_val_bas2 = $oIfx->f('fprv_val_bas2');

							$fprv_val_iva1 = $oIfx->f('fprv_val_iva1');
							$fprv_cod_riva1 = $oIfx->f('fprv_cod_riva1');
							$fprv_por_iva1 = $oIfx->f('fprv_por_iva1');
							$fprv_val_bas3 = $oIfx->f('fprv_val_bas3');

							$fprv_val_iva2 = $oIfx->f('fprv_val_iva2');
							$fprv_cod_riva2 = $oIfx->f('fprv_cod_riva2');
							$fprv_por_iva2 = $oIfx->f('fprv_por_iva2');
							$fprv_val_bas4 = $oIfx->f('fprv_val_bas4');


							$fechaEmisionDocSustento = $fechaEmision = date_format(date_create($fprv_fec_emis), "d/m/Y");

							$numDocSustento = $fprv_num_seri . $ret_num_fact;

							if (empty($rete_ruci_benf)) {
								$sqlClpv = "select clpv_ruc_clpv from saeclpv where clpv_cod_clpv = $rete_cod_clpv";
								$rete_ruci_benf = consulta_string_func($sqlClpv, 'clpv_ruc_clpv', $oIfx1, '');
							}


							if ($fprv_val_ret1 > 0 || $fprv_cod_rtf1 != '') {

								$sql = "select tret_imp_ret ,  tret_cod_imp  from saetret where
                                            tret_cod_empr = $id_empresa and
                                            tret_cod      = '$fprv_cod_rtf1' and
                                            tret_porct    = $fprv_por_ret1 ";
								if ($oIfxA->Query($sql)) {
									$ret_cta_ret = $oIfxA->f('tret_cod_imp');
									$codigo = $oIfxA->f('tret_imp_ret');
								}
								$xmlImpu .= '<impuesto>';
								$xmlImpu .= "<codigo>$codigo</codigo>
															<codigoRetencion>$ret_cta_ret</codigoRetencion>
															<baseImponible>" . round($fprv_val_bas1, 2) . "</baseImponible>
															<porcentajeRetener>$fprv_por_ret1</porcentajeRetener>
															<valorRetenido>" . round($fprv_val_ret1, 2) . "</valorRetenido>
															<codDocSustento>$codDocSustento</codDocSustento>
															<numDocSustento>" . trim($numDocSustento) . "</numDocSustento>
															<fechaEmisionDocSustento>$fechaEmisionDocSustento</fechaEmisionDocSustento>";
								$xmlImpu .= '</impuesto>';
							}

							if ($fprv_val_ret2 > 0 || $fprv_cod_rtf2 != '') {

								$sql = "select tret_imp_ret ,  tret_cod_imp  from saetret where
                                            tret_cod_empr = $id_empresa and
                                            tret_cod      = '$fprv_cod_rtf2' and
                                            tret_porct    = $fprv_por_ret2 ";
								if ($oIfxA->Query($sql)) {
									$ret_cta_ret = $oIfxA->f('tret_cod_imp');
									$codigo = $oIfxA->f('tret_imp_ret');
								}
								$xmlImpu .= '<impuesto>';
								$xmlImpu .= "<codigo>$codigo</codigo>
															<codigoRetencion>$ret_cta_ret</codigoRetencion>
															<baseImponible>" . round($fprv_val_bas2, 2) . "</baseImponible>
															<porcentajeRetener>$fprv_por_ret2</porcentajeRetener>
															<valorRetenido>" . round($fprv_val_ret2, 2) . "</valorRetenido>
															<codDocSustento>$codDocSustento</codDocSustento>
															<numDocSustento>" . trim($numDocSustento) . "</numDocSustento>
															<fechaEmisionDocSustento>$fechaEmisionDocSustento</fechaEmisionDocSustento>";
								$xmlImpu .= '</impuesto>';
							}
							if ($fprv_val_iva1 > 0 || $fprv_cod_riva1 != '') {
								$sql = "select tret_imp_ret ,  tret_cod_imp  from saetret where
                                            tret_cod_empr = $id_empresa and
                                            tret_cod      = '$fprv_cod_riva1' and
                                            tret_porct    = $fprv_por_iva1";
								if ($oIfxA->Query($sql)) {
									$ret_cta_ret = $oIfxA->f('tret_cod_imp');
									$codigo = $oIfxA->f('tret_imp_ret');
								}
								$xmlImpu .= '<impuesto>';
								$xmlImpu .= "<codigo>$codigo</codigo>
															<codigoRetencion>$ret_cta_ret</codigoRetencion>
															<baseImponible>" . round($fprv_val_bas3, 2) . "</baseImponible>
															<porcentajeRetener>$fprv_por_iva1</porcentajeRetener>
															<valorRetenido>" . round($fprv_val_iva1, 2) . "</valorRetenido>
															<codDocSustento>$codDocSustento</codDocSustento>
															<numDocSustento>" . trim($numDocSustento) . "</numDocSustento>
															<fechaEmisionDocSustento>$fechaEmisionDocSustento</fechaEmisionDocSustento>";
								$xmlImpu .= '</impuesto>';
							}
							if ($fprv_val_iva2 > 0 || $fprv_cod_riva2 != '') {
								$sql = "select tret_imp_ret ,  tret_cod_imp  from saetret where
                                            tret_cod_empr = $id_empresa and
                                            tret_cod      = '$fprv_cod_riva2' and
                                            tret_porct    = $fprv_por_iva2";
								if ($oIfxA->Query($sql)) {
									$ret_cta_ret = $oIfxA->f('tret_cod_imp');
									$codigo = $oIfxA->f('tret_imp_ret');
								}
								$xmlImpu .= '<impuesto>';
								$xmlImpu .= "<codigo>$codigo</codigo>
															<codigoRetencion>$ret_cta_ret</codigoRetencion>
															<baseImponible>" . round($fprv_val_bas4, 2) . "</baseImponible>
															<porcentajeRetener>$fprv_por_iva2</porcentajeRetener>
															<valorRetenido>" . round($fprv_val_iva2, 2) . "</valorRetenido>
															<codDocSustento>$codDocSustento</codDocSustento>
															<numDocSustento>" . trim($numDocSustento) . "</numDocSustento>
															<fechaEmisionDocSustento>$fechaEmisionDocSustento</fechaEmisionDocSustento>";
								$xmlImpu .= '</impuesto>';
							}


							//}
						} while ($oIfx->SiguienteRegistro());
					}
				}
				$oIfx->Free();

				$estab = substr($ret_ser_ret, 0, 3);
				$ptoEmi = substr($ret_ser_ret, 3, 6);

				$periodoFiscal = date_format(date_create($fprv_fec_emis), "d/m/Y");
				$periodoFiscal = substr($periodoFiscal, -7, 7);

				$rimpe = "";
				if ($empr_rimp_sn == "S") {
					$rimpe = "<contribuyenteRimpe>CONTRIBUYENTE RÉGIMEN RIMPE</contribuyenteRimpe>";
				}

				$xml .= '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>';
				$xml .= '<comprobanteRetencion id="comprobante" version="1.0.0">';
				$xml .= '<infoTributaria>';
				$xml .= "<ambiente>$ambiente</ambiente>
                                    <tipoEmision>$tipoEmision</tipoEmision>
                                    <razonSocial>" . htmlspecialchars($razonSocial) . "</razonSocial>
                                    <nombreComercial>" . htmlspecialchars($razonSocial) . "</nombreComercial>
                                    <ruc>$ruc_empr</ruc>
                                    <claveAcceso>$claveAcceso</claveAcceso>
                                    <codDoc>$codDoc</codDoc>
                                    <estab>$estab</estab>
                                    <ptoEmi>$ptoEmi</ptoEmi>
                                    <secuencial>$ret_num_ret</secuencial>
                                    <dirMatriz>$dirMatriz</dirMatriz>
									" . $rimpe . "";
				if (!empty($empr_ac2_empr)) {
					$aret = explode('-', $empr_ac2_empr);
					$numret = intval($aret[2]);
					$xml .= '<agenteRetencion>' . $numret . '</agenteRetencion>';
				}
				$xml .= '</infoTributaria>';

				$xml .= '<infoCompRetencion>';
				$xml .= "<fechaEmision>$fechaEmision</fechaEmision>
						<dirEstablecimiento>" . $arrayDireSucu[$id_sucursal] . "</dirEstablecimiento>";
				if ($empr_num_resu != '')
					$xml .= '<contribuyenteEspecial>' . $empr_num_resu . '</contribuyenteEspecial>';

				$xml .= "<obligadoContabilidad>$obligadoContabilidad</obligadoContabilidad>
                                    <tipoIdentificacionSujetoRetenido>$tipoIdentificacionSujetoRetenido</tipoIdentificacionSujetoRetenido>
                                    <razonSocialSujetoRetenido>" . htmlspecialchars($rete_nom_benf) . "</razonSocialSujetoRetenido>
                                    <identificacionSujetoRetenido>$rete_ruci_benf</identificacionSujetoRetenido>
                                    <periodoFiscal>$periodoFiscal</periodoFiscal>";
				$xml .= '</infoCompRetencion>';

				$xml .= '<impuestos>';
				$xml .= $xmlImpu;
				$xml .= '</impuestos>';

				//$rete_dire_benf = "MANUEL VEGA 6-54 Y PRESIDENTE CORDOVA CUENCA AZU ECUADOR";

				$aDataInfoAdic['Direccion'] = $rete_dire_benf;
				$aDataInfoAdic['Telefono'] = $rete_telf_benf;
				$aDataInfoAdic['Email'] = $ret_email_clpv;

				$etiqueta = array_keys($aDataInfoAdic);
				if (count($etiqueta) > 0) {
					$xml .= '<infoAdicional>';
					foreach ($etiqueta as $nom) {
						if ($aDataInfoAdic[$nom] != '')
							$xml .= "<campoAdicional nombre=\"$nom\">$aDataInfoAdic[$nom]</campoAdicional>";
					}
					$xml .= '</infoAdicional>';
				}

				$xml .= '</comprobanteRetencion>';

				//$oReturn->alert($xml);

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

					$nombre = $claveAcceso . ".xml";
					$archivo = fopen($ruta_gene . '/' . $nombre, "w+");

					//fwrite($archivo, $xml);
					fwrite($archivo, utf8_encode($xml));
					fclose($archivo);
				} elseif ($empr_tip_firma == 'M') {

					//MultiFirma
					$nombre = $claveAcceso . ".xml";
					$serv = '';
					$archivo = '';
					$serv = DIR_FACTELEC . "modulos/sri_offline/documentoselectronicos/generados";
					$archivo = fopen($serv . '/' . $nombre, "w+");

					//fwrite($archivo, $xml);
					fwrite($archivo, utf8_encode($xml));
					fclose($archivo);
				}
				$sql_gasto = '';
				$id = 0;

				$oReturn->script("firmar('$nombre','$claveAcceso','$ruc_empr', '$ret_email_clpv', 'retencion_gasto', 
                                            '$sql_gasto', $id,  $rete_cod_clpv,  '$ret_num_fact', '$asto_cod_ejer', 
                                            '$rete_cod_asto', '$fprv_fec_emis' , 5, $id_sucursal)");
			} // fin check
		} // fin foreach
	} else {
		$oReturn->alert('Por favor realice una Busqueda...');
	}

	return $oReturn;
}

function firmar_reteGast($aForm = '')
{

	global $DSN_Ifx, $DSN;
	if (session_status() !== PHP_SESSION_ACTIVE) {
		session_start();
	}

	$oReturn = new xajaxResponse();

	$oIfx = new Dbo;
	$oIfx->DSN = $DSN_Ifx;
	$oIfx->Conectar();

	$oIfx1 = new Dbo;
	$oIfx1->DSN = $DSN_Ifx;
	$oIfx1->Conectar();

	$oIfx2 = new Dbo;
	$oIfx2->DSN = $DSN_Ifx;
	$oIfx2->Conectar();

	$oIfxA = new Dbo;
	$oIfxA->DSN = $DSN_Ifx;
	$oIfxA->Conectar();

	//VARIABLES GLOBALES
	$id_empresa = $_SESSION['U_EMPRESA'];
	$array_gasto = $_SESSION['U_FACT_ENVIO'];


	unset($_SESSION['aDataGirdErro']);
	unset($array);

	//  LECTURA SUCIA


	$sql = "select empr_ac2_empr, empr_nom_empr, empr_ruc_empr, empr_dir_empr, 
			empr_conta_sn, empr_num_resu, empr_tip_firma, empr_rimp_sn
			from saeempr 
			where empr_cod_empr = $id_empresa ";
	if ($oIfx->Query($sql)) {
		if ($oIfx->NumFilas() > 0) {
			$razonSocial = trim($oIfx->f('empr_nom_empr'));
			$ruc_empr = $oIfx->f('empr_ruc_empr');
			$dirMatriz = trim($oIfx->f('empr_dir_empr'));
			$conta_sn = $oIfx->f('empr_conta_sn');
			$empr_num_resu = $oIfx->f('empr_num_resu');
			$empr_tip_firma = $oIfx->f('empr_tip_firma');
			$empr_ac2_empr = trim($oIfx->f('empr_ac2_empr'));
			$empr_rimp_sn = $oIfx->f('empr_rimp_sn');
		}
	}
	$oIfx->Free();

	//correccion de caracteres especiales para xml
	//$caracteres_errores = array("&");
	//$caracteres_corregidos = array("&#38;");
	//$razonSocial = str_replace($caracteres_errores,$caracteres_corregidos,$razonSocial);

	//direccion sucursales
	$sql = "select sucu_cod_sucu, sucu_dir_sucu from saesucu where sucu_cod_empr = $id_empresa";
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
		$obligadoContabilidad = 'SI';
	} else {
		$obligadoContabilidad = 'NO';
	}

	//OTROS DATOS SRI
	$codDoc = '07';

	if (count($array_gasto) > 0) {
		foreach ($array_gasto as $val) {
			$rete_cod_clpv = $val[0];
			$ret_num_ret = $val[8];
			$ret_num_fact = $val[6];
			$asto_cod_ejer = $val[5];
			$id_sucursal = $val[14];
			$serial = $rete_cod_clpv . '_' . $ret_num_ret . '_' . $asto_cod_ejer . '_' . $ret_num_fact . '_' . $id_empresa . '_' . $id_sucursal . '_rg';
			$check = $aForm[$serial];

			$xml = '';
			$xmlImpu = '';
			$descuento = 0;
			if (!empty($check)) {
				//COMSULTAMOS LOS DATOS Y FORMAMOS LOS XML
				$num8 = 12345678;
				$fprv_num_seri = $val[7];
				$codDocSustento = $val[12];
				$rete_nom_benf = $val[1];
				$rete_dire_benf = $val[2];
				$fprv_fec_emis = $val[4];
				$ret_email_clpv = $val[3];
				$rete_cod_asto = $val[13];
				$ret_ser_ret = $val[9];
				$tipoIdentificacionSujetoRetenido = $val[10];
				$claveAcceso = $val[15];

				//genera clave de acceso
				$ambiente = ambienteEmisionSri($oIfx, $id_empresa, $id_sucursal, 1);
				$tipoEmision = ambienteEmisionSri($oIfx, $id_empresa, $id_sucursal, 2);

				//DOCUMENTO SUSTENTO
				$sql_cod_sus = "select fprv_cre_fisc from saefprv  where fprv_cod_asto='$rete_cod_asto' and fprv_cod_ejer=$asto_cod_ejer ";
				$cod_suste = consulta_string_func($sql_cod_sus, 'fprv_cre_fisc', $oIfx1, '');

				//COMSULTAMOS LOS DATOS Y FORMAMOS LOS XML
				$xmReembolsos = '';
				$sqlReteGast = "select * from saefprv  where
                    fprv_cod_empr = $id_empresa and 
                    fprv_cod_sucu = $id_sucursal  and 
                    fprv_cod_clpv = $rete_cod_clpv and 
                    fprv_num_fact = '$ret_num_fact' and 
                    fprv_cod_ejer = '$asto_cod_ejer' and  
                    fprv_cod_asto = '$rete_cod_asto' and  
                    fprv_fec_emis = '$fprv_fec_emis' ";
				$valorPago = 0;
				//$oReturn->alert($sqlReteGast);
				if ($oIfx->Query($sqlReteGast)) {
					if ($oIfx->NumFilas() > 0) {
						do {

							$rete_ruci_benf = $oIfx->f('fprv_ruc_prov');
							$rete_telf_benf = $oIfx->f('fprv_tel_clpv');
							//RETENCIONE SAEFPRV
							$fprv_val_ret1 = $oIfx->f('fprv_val_ret1');
							$fprv_cod_rtf1 = $oIfx->f('fprv_cod_rtf1');
							$fprv_por_ret1 = $oIfx->f('fprv_por_ret1');
							$fprv_val_bas1 = $oIfx->f('fprv_val_bas1');

							$fprv_val_ret2 = $oIfx->f('fprv_val_ret2');
							$fprv_cod_rtf2 = $oIfx->f('fprv_cod_rtf2');
							$fprv_por_ret2 = $oIfx->f('fprv_por_ret2');
							$fprv_val_bas2 = $oIfx->f('fprv_val_bas2');

							$fprv_val_iva1 = $oIfx->f('fprv_val_iva1');
							$fprv_cod_riva1 = $oIfx->f('fprv_cod_riva1');
							$fprv_por_iva1 = $oIfx->f('fprv_por_iva1');
							$fprv_val_bas3 = $oIfx->f('fprv_val_bas3');

							$fprv_val_iva2 = $oIfx->f('fprv_val_iva2');
							$fprv_cod_riva2 = $oIfx->f('fprv_cod_riva2');
							$fprv_por_iva2 = $oIfx->f('fprv_por_iva2');
							$fprv_val_bas4 = $oIfx->f('fprv_val_bas4');

							$fprv_ruc_prov = $oIfx->f('fprv_ruc_prov');
							$fprv_cod_paisp = $oIfx->f('fprv_cod_paisp');
							$fprv_cod_tprov = $oIfx->f('fprv_cod_tprov');
							$fprv_num_seri = $oIfx->f('fprv_num_seri');
							$fprv_num_rete = $oIfx->f('fprv_num_rete');
							$fprv_fec_emis = $oIfx->f('fprv_fec_emis');
							$fprv_num_auto = $oIfx->f('fprv_num_auto');
							$fprv_cre_fisc = $oIfx->f('fprv_cre_fisc');
							$fprv_val_gra0 = $oIfx->f('fprv_val_gra0');
							$fprv_val_piva = $oIfx->f('fprv_val_piva');
							$fprv_val_viva = $oIfx->f('fprv_val_viva');
							$fprv_val_totl = $oIfx->f('fprv_val_totl');
							$fecha_retencion = $oIfx->f('fprv_rete_fec');

							if(empty($fecha_retencion))
							{
								$fecha_retencion =$fprv_fec_emis;
							}
							
							

							$fechaEmision = date_format(date_create($fecha_retencion), "d/m/Y");

							$fechaEmisionDocSustento = date_format(date_create($fprv_fec_emis), "d/m/Y");

							$numDocSustento = $fprv_num_seri . $ret_num_fact;

							$sqlClpv = "select clpv_cod_tprov from saeclpv where clpv_cod_clpv = $rete_cod_clpv";
							$clpv_cod_tprov = consulta_string_func($sqlClpv, 'clpv_cod_tprov', $oIfx1, '');

							$sqlClpv = "select clpv_par_rela from saeclpv where clpv_cod_clpv = $rete_cod_clpv";
							$clpv_par_rela = consulta_string_func($sqlClpv, 'clpv_cod_tprov', $oIfx1, '');

							if (empty($rete_ruci_benf)) {
								$sqlClpv = "select clpv_ruc_clpv from saeclpv where clpv_cod_clpv = $rete_cod_clpv";
								$rete_ruci_benf = consulta_string_func($sqlClpv, 'clpv_ruc_clpv', $oIfx1, '');
							}


							///VALIDACION RETENCIONES


							//COMSULTAMOS LOS DATOS Y FORMAMOS LOS XML
							$sqlReteGasto = "select rete_ruci_benf, rete_telf_benf,  ret_cta_ret,   
								ret_bas_imp, ret_porc_ret, ret_valor, ret_fpag_divi, ret_anio_divi, ret_ir_divi
								from saeret where 
								asto_cod_empr = $id_empresa and 
								asto_cod_sucu = $id_sucursal and 
								ret_num_ret   = '$ret_num_ret' and 
								ret_num_fact  = '$ret_num_fact' and 
								rete_cod_asto = '$rete_cod_asto'  and 
								ret_cod_clpv  = $rete_cod_clpv and 
								asto_cod_ejer = $asto_cod_ejer ";

							if ($oIfxA->Query($sqlReteGasto)) {
								if ($oIfxA->NumFilas() > 0) {
									do {

										$ret_fpag_divi  	= $oIfxA->f('ret_fpag_divi');
										$ret_anio_divi  	= $oIfxA->f('ret_anio_divi');
										$ret_ir_divi    	= $oIfxA->f('ret_ir_divi');
										if (empty($ret_ir_divi)) {
											$ret_ir_divi    	= 0;
										}
										if (empty($ret_fpag_divi)) {
											$fecha_pago_dividendo = $fechaEmision;
											$fechaEmi = explode('/', $fechaEmision);

											$ret_anio_divi = $fechaEmi[2];
										} else {
											$fecha_pago_dividendo = date_format(date_create($ret_fpag_divi), "d/m/Y");
										}
									} while ($oIfxA->SiguienteRegistro());
								}
							}


							if ($fprv_val_ret1 > 0 || $fprv_cod_rtf1 != '') {

								$sql = "select tret_imp_ret ,  tret_cod_imp  from saetret where
                                            tret_cod_empr = $id_empresa and
                                            tret_cod      = '$fprv_cod_rtf1' and
                                            tret_porct    = $fprv_por_ret1 ";
								if ($oIfxA->Query($sql)) {
									$ret_cta_ret = $oIfxA->f('tret_cod_imp');
									$codigo = $oIfxA->f('tret_imp_ret');
								}

								$xmlImpu .= "<retencion> 
											<codigo>$codigo</codigo>
															<codigoRetencion>$ret_cta_ret</codigoRetencion>
															<baseImponible>" . round($fprv_val_bas1, 2) . "</baseImponible>
															<porcentajeRetener>$fprv_por_ret1</porcentajeRetener>
															<valorRetenido>" . round($fprv_val_ret1, 2) . "</valorRetenido>";
								$xmlDividendo = '';
								if ($cod_suste == '10') {

									$xmlDividendo .= '<dividendos>';
									$xmlDividendo .= "<fechaPagoDiv>" . $fecha_pago_dividendo . "</fechaPagoDiv>";
									$xmlDividendo .= "<imRentaSoc>" . $ret_ir_divi . "</imRentaSoc>";
									$xmlDividendo .= "<ejerFisUtDiv>" . $ret_anio_divi . "</ejerFisUtDiv>";
									$xmlDividendo .= '</dividendos>';
								}
								$xmlImpu .= $xmlDividendo;
								$xmlImpu .= "</retencion>";

								if ($codigo == 1) $valorPago = $valorPago + $fprv_val_ret1;
							}

							if ($fprv_val_ret2 > 0 || $fprv_cod_rtf2 != '') {

								$sql = "select tret_imp_ret ,  tret_cod_imp  from saetret where
                                            tret_cod_empr = $id_empresa and
                                            tret_cod      = '$fprv_cod_rtf2' and
                                            tret_porct    = $fprv_por_ret2 ";
								if ($oIfxA->Query($sql)) {
									$ret_cta_ret = $oIfxA->f('tret_cod_imp');
									$codigo = $oIfxA->f('tret_imp_ret');
								}




								$xmlImpu .= "<retencion> 
											<codigo>$codigo</codigo>
															<codigoRetencion>$ret_cta_ret</codigoRetencion>
															<baseImponible>" . round($fprv_val_bas2, 2) . "</baseImponible>
															<porcentajeRetener>$fprv_por_ret2</porcentajeRetener>
															<valorRetenido>" . round($fprv_val_ret2, 2) . "</valorRetenido>";
								$xmlDividendo = '';
								if ($cod_suste == '10') {

									$xmlDividendo .= '<dividendos>';
									$xmlDividendo .= "<fechaPagoDiv>" . $fecha_pago_dividendo . "</fechaPagoDiv>";
									$xmlDividendo .= "<imRentaSoc>" . $ret_ir_divi . "</imRentaSoc>";
									$xmlDividendo .= "<ejerFisUtDiv>" . $ret_anio_divi . "</ejerFisUtDiv>";
									$xmlDividendo .= '</dividendos>';
								}
								$xmlImpu .= $xmlDividendo;
								$xmlImpu .= "</retencion>";
								if ($codigo == 1) $valorPago = $valorPago + $fprv_val_ret2;
							}
							if ($fprv_val_iva1 > 0 || $fprv_cod_riva1 != '') {
								$sql = "select tret_imp_ret ,  tret_cod_imp  from saetret where
                                            tret_cod_empr = $id_empresa and
                                            tret_cod      = '$fprv_cod_riva1' and
                                            tret_porct    = $fprv_por_iva1";

								if ($oIfxA->Query($sql)) {
									$ret_cta_ret = $oIfxA->f('tret_cod_imp');
									$codigo = $oIfxA->f('tret_imp_ret');
								}

								$xmlImpu .= "<retencion> 
											<codigo>$codigo</codigo>
															<codigoRetencion>$ret_cta_ret</codigoRetencion>
															<baseImponible>" . round($fprv_val_bas3, 2) . "</baseImponible>
															<porcentajeRetener>$fprv_por_iva1</porcentajeRetener>
															<valorRetenido>" . round($fprv_val_iva1, 2) . "</valorRetenido>";

								$xmlDividendo = '';
								if ($cod_suste == '10') {

									$xmlDividendo .= '<dividendos>';
									$xmlDividendo .= "<fechaPagoDiv>" . $fecha_pago_dividendo . "</fechaPagoDiv>";
									$xmlDividendo .= "<imRentaSoc>" . $ret_ir_divi . "</imRentaSoc>";
									$xmlDividendo .= "<ejerFisUtDiv>" . $ret_anio_divi . "</ejerFisUtDiv>";
									$xmlDividendo .= '</dividendos>';
								}
								$xmlImpu .= $xmlDividendo;

								$xmlImpu .= "</retencion>";
								if ($codigo == 1) $valorPago = $valorPago + $fprv_val_iva1;
							}
							if ($fprv_val_iva2 > 0 || $fprv_cod_riva2 != '') {
								$sql = "select tret_imp_ret ,  tret_cod_imp  from saetret where
                                            tret_cod_empr = $id_empresa and
                                            tret_cod      = '$fprv_cod_riva2' and
                                            tret_porct    = $fprv_por_iva2";
								if ($oIfxA->Query($sql)) {
									$ret_cta_ret = $oIfxA->f('tret_cod_imp');
									$codigo = $oIfxA->f('tret_imp_ret');
								}

								$xmlImpu .= "<retencion> 
											<codigo>$codigo</codigo>
															<codigoRetencion>$ret_cta_ret</codigoRetencion>
															<baseImponible>" . round($fprv_val_bas4, 2) . "</baseImponible>
															<porcentajeRetener>$fprv_por_iva2</porcentajeRetener>
															<valorRetenido>" . round($fprv_val_iva2, 2) . "</valorRetenido>";
								$xmlDividendo = '';
								if ($cod_suste == '10') {

									$xmlDividendo .= '<dividendos>';
									$xmlDividendo .= "<fechaPagoDiv>" . $fecha_pago_dividendo . "</fechaPagoDiv>";
									$xmlDividendo .= "<imRentaSoc>" . $ret_ir_divi . "</imRentaSoc>";
									$xmlDividendo .= "<ejerFisUtDiv>" . $ret_anio_divi . "</ejerFisUtDiv>";
									$xmlDividendo .= '</dividendos>';
								}
								$xmlImpu .= $xmlDividendo;

								$xmlImpu .= "</retencion>";

								if ($codigo == 1) $valorPago = $valorPago + $fprv_val_iva2;
							}



							$periodoFiscal = date_format(date_create($fprv_fec_emis), "d/m/Y");
							$periodoFiscal = substr($periodoFiscal, -7, 7);
							//REEMBOLSOS
							$fprv_fec_emisDocReemb = explode('-', $fprv_fec_emis);
							$fprv_fec_emisDocReemb = $fprv_fec_emisDocReemb[2] . '/' . $fprv_fec_emisDocReemb[1] . '/' . $fprv_fec_emisDocReemb[0];
						} while ($oIfx->SiguienteRegistro());
					}
				}
				$oIfx->Free();

				$estab = substr($ret_ser_ret, 0, 3);
				$ptoEmi = substr($ret_ser_ret, 3, 6);

				// Verificar rimpe
				$rimpe = "";
				if ($empr_rimp_sn == "S") {
					$rimpe = "<contribuyenteRimpe>CONTRIBUYENTE RÉGIMEN RIMPE</contribuyenteRimpe>";
				}


				$xml .= '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>';
				$xml .= '<comprobanteRetencion id="comprobante" version="2.0.0">';
				$xml .= '<infoTributaria>';
				$xml .= "<ambiente>$ambiente</ambiente>
                                    <tipoEmision>$tipoEmision</tipoEmision>
                                    <razonSocial>" . htmlspecialchars($razonSocial) . "</razonSocial>
                                    <nombreComercial>" . htmlspecialchars($razonSocial) . "</nombreComercial>
                                    <ruc>$ruc_empr</ruc>
                                    <claveAcceso>$claveAcceso</claveAcceso>
                                    <codDoc>$codDoc</codDoc>
                                    <estab>$estab</estab>
                                    <ptoEmi>$ptoEmi</ptoEmi>
                                    <secuencial>$ret_num_ret</secuencial>
                                    <dirMatriz>$dirMatriz</dirMatriz>
									";

				if (!empty($empr_ac2_empr)) {
					$aret = explode('-', $empr_ac2_empr);
					$numret = intval($aret[2]);
					$xml .= '<agenteRetencion>' . $numret . '</agenteRetencion>';
				}

				$xml .= $rimpe;
				$xml .= '</infoTributaria>';

				$xml .= '<infoCompRetencion>';
				$xml .= "<fechaEmision>$fechaEmision</fechaEmision>
						<dirEstablecimiento>" . $arrayDireSucu[$id_sucursal] . "</dirEstablecimiento>";
				if ($empr_num_resu != '')
					$xml .= '<contribuyenteEspecial>' . $empr_num_resu . '</contribuyenteEspecial>';

				$xml .= "<obligadoContabilidad>$obligadoContabilidad</obligadoContabilidad>
				<tipoIdentificacionSujetoRetenido>$tipoIdentificacionSujetoRetenido</tipoIdentificacionSujetoRetenido>";
				if ($obligadoContabilidad == "08") {
					$xml .= "<tipoSujetoRetenido>$clpv_cod_tprov</tipoSujetoRetenido>";
				}
				if ($clpv_par_rela == 'S') {
					$clpv_par_rela = 'SI';
				} else {
					$clpv_par_rela = 'NO';
				}
				$xml .= "
							<parteRel>$clpv_par_rela</parteRel>";
				$xml .= "<razonSocialSujetoRetenido>" . utf8_encode(htmlspecialchars($rete_nom_benf)) . "</razonSocialSujetoRetenido>";
				$xml .= "
							<identificacionSujetoRetenido>$rete_ruci_benf</identificacionSujetoRetenido>
							<periodoFiscal>$periodoFiscal</periodoFiscal>";
				$xml .= '</infoCompRetencion>';




				$xmldocsustento = '';
				$xmldocsustento .= "<docsSustento>
				<docSustento>
				<codSustento>" . $cod_suste . "</codSustento>
				<codDocSustento>" . $codDocSustento . "</codDocSustento>
				<numDocSustento>" . trim($numDocSustento) . "</numDocSustento>
				<fechaEmisionDocSustento>" . $fechaEmisionDocSustento . "</fechaEmisionDocSustento>";


				$sqlFact = "select fact_auto_sri from saefact where
                                                       fact_cod_fact = $ret_num_fact and 
                                                       fact_cod_empr = $id_empresa and 
                                                       fact_cod_sucu = $id_sucursal ";
				$cod_suste = consulta_string_func($sql_cod_sus, 'fact_auto_sri', $oIfx1, '');

				//SON CAMPOS OPCIONALES fechaRegistroContable Y numAutDocSustento - REVISAR DE DONDE SACAR ESA INFORMACION
				//$xmldocsustento.="<fechaRegistroContable>15/03/2012</fechaRegistroContable>";
				//$xmldocsustento.="<numAutDocSustento>$fact_auto_sri</numAutDocSustento>";

				$pagoLocExt = "01";
				$xmldocsustento .= "
					<pagoLocExt>01</pagoLocExt>";
				if ($pagoLocExt == "02") {
					$tipoRegi = "01";
					$xmldocsustento .= "<paisEfecPago>593<paisEfecPago>";
					$aplicConvDobTrib = "NO";
					$xmldocsustento .= "<aplicConvDobTrib>$aplicConvDobTrib</aplicConvDobTrib>";
					if ($aplicConvDobTrib == "NO") {
						$xmldocsustento .= "<pagExtSujRetNorLeg>NO</pagExtSujRetNorLeg>";
					}
					$aplicConvDobTrib = "SI";
					$xmldocsustento .= "<pagoRegFis>$aplicConvDobTrib</pagoRegFis>";
				}

				//// si es reembolso 041
				if ($codDocSustento == "41") {
					/*
					$sql = "select count(*) as numero_registros, 
									sum(minr_con_miva) + sum(minr_sin_miva)   as suma_base, 
									sum(minr_iva_valo ) as suma_iva, 
									sum(minr_con_miva) + sum(minr_iva_valo)+ sum(minr_sin_miva) as total_fact
								 from saeminr 
								 where minr_num_comp = $ret_num_fact
								 and minr_cod_empr = $id_empresa
								 and minr_cod_sucu = $id_sucursal";
					$numero_registros = consulta_string($sql, 'numero_registros', $oIfx, 0);
					$suma_base = consulta_string($sql, 'suma_base', $oIfx, 0);
					$suma_comp = consulta_string($sql, 'total_fact', $oIfx, 0);
					$suma_iva = consulta_string($sql, 'suma_iva', $oIfx, 0);
					*/
					$numero_registros = 0;
					if ($numero_registros > 0) {
						$total_base_imponible = $suma_base;
						$tatal_comprobantes = $suma_comp;
						$total_iva = $suma_iva;
						$xmldocsustento .= '<totalComprobantesReembolso>' . round($tatal_comprobantes, 2) . '</totalComprobantesReembolso>
									<totalBaseImponibleReembolso>' . round($total_base_imponible, 2) . '</totalBaseImponibleReembolso> 
									<totalImpuestoReembolso>' . round($total_iva, 2) . '</totalImpuestoReembolso>';
					} else {

						$sql_remb = "select fprr_cod_fprr,fprr_ruc_prov,fprr_tip_iden,fprr_seri_fprv,
                                    fprr_num_seri,fprr_num_esta,fprr_fec_emis,fprr_num_auto, 
                                    fprr_val_vivb,fprr_val_pivb,fprr_val_grab, fprr_val_vivs,fprr_val_pivs,
                                    fprr_val_gras,fprr_val_gra0,fprr_val_grs0,trans_tip_comp,fprr_val_totl
                                    from saefprr, saetran
                                    where fprr_fac_fprv like '%$ret_num_fact%'
                                    and fprr_cod_empr = $id_empresa
                                    and fprr_cod_sucu = $id_sucursal
                                    and tran_cod_modu = 4
                                    and tran_cod_tran = fprr_cod_tran 
                                    group by 1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18
                                    order by fprr_cod_fprr";
						//echo  $sql_remb; exit;
						$xmReembolsos = '';
						$total_comprobantes_reembolso = 0;
						$total_base_imponible_reembolso = 0;
						$total_impuesto_reembolso = 0;
						if ($oIfx->Query($sql_remb)) {
							if ($oIfx->NumFilas() > 0) {
								do {

									$fprr_val_vivb 	=  $oIfx->f('fprr_val_vivb');
									$fprr_val_grab 	=  $oIfx->f('fprr_val_grab');
									$fprr_val_vivs 	=  $oIfx->f('fprr_val_vivs');
									$fprr_val_gras 	=  $oIfx->f('fprr_val_gras');
									$fprr_val_gra0 =  $oIfx->f('fprr_val_gra0');
									$fprr_val_grs0 =  $oIfx->f('fprr_val_grs0');
									$fprr_val_totl =  $oIfx->f('fprr_val_totl');

									//SERVICIOS+BIENES
									$total_comprobantes_reembolso = $total_comprobantes_reembolso + $fprr_val_totl;
									$total_base_imponible_reembolso = $total_base_imponible_reembolso + $fprr_val_gras + $fprr_val_grs0 + $fprr_val_grab + $fprr_val_gra0;
									$total_impuesto_reembolso = $total_impuesto_reembolso + $fprr_val_vivs + $fprr_val_vivb;
								} while ($oIfx->SiguienteRegistro());
								$xmldocsustento .= '<totalComprobantesReembolso>' . round($total_comprobantes_reembolso, 2) . '</totalComprobantesReembolso>
									<totalBaseImponibleReembolso>' . round($total_base_imponible_reembolso, 2) . '</totalBaseImponibleReembolso> 
									<totalImpuestoReembolso>' . round($total_impuesto_reembolso, 2) . '</totalImpuestoReembolso>';
							}
						}
					}
				}



				//COMSULTAMOS LOS DATOS Y FORMAMOS LOS XML
				$sqlReteGast = "select rete_ruci_benf, rete_telf_benf,  ret_cta_ret,   
                                    ret_bas_imp, ret_porc_ret, ret_valor, ret_fpag_divi
                                    from saeret where 
                                    asto_cod_empr = $id_empresa and 
                                    asto_cod_sucu = $id_sucursal and 
                                    ret_num_ret   = '$ret_num_ret' and 
                                    ret_num_fact  = '$ret_num_fact' and 
                                    rete_cod_asto = '$rete_cod_asto'  and 
                                    ret_cod_clpv  = $rete_cod_clpv and 
                                    asto_cod_ejer = $asto_cod_ejer ";
				//$xmlDividendo = '';
				$totalsinimpuestos = 0;
				$importetotal = 0;
				if ($oIfx->Query($sqlReteGast)) {
					if ($oIfx->NumFilas() > 0) {
						do {
							$rete_ruci_benf 	= $oIfx->f('rete_ruci_benf');
							$rete_telf_benf 	= $oIfx->f('rete_telf_benf');
							$ret_cta_ret 		= $oIfx->f('ret_cta_ret');
							$ret_bas_imp 		= $oIfx->f('ret_bas_imp');
							$ret_porc_ret 		= $oIfx->f('ret_porc_ret');
							$ret_valor 			= $oIfx->f('ret_valor');
							$ret_fpag_divi  	= $oIfx->f('ret_fpag_divi');
							$ret_anio_divi  	= 0;
							$ret_ir_divi    	= 0;
							if (empty($ret_fpag_divi)) {
								$fecha_pago_dividendo = $fechaEmision;
							} else {
								$fecha_pago_dividendo = date_format(date_create($ret_fpag_divi), "d/m/Y");
							}

							//$fecha_pago_dividendo = cambioFecha($ret_fpag_divi, 'mm/dd/aaaa', 'dd/mm/aaaa');


							if (empty($rete_ruci_benf)) {
								$sqlClpv = "select clpv_ruc_clpv, clpv_cod_tprov from saeclpv where clpv_clopv_clpv='PV' and clpv_cod_clpv = $rete_cod_clpv";
								$rete_ruci_benf = consulta_string_func($sqlClpv, 'clpv_ruc_clpv', $oIfx1, '');
								$clpv_cod_tprov = consulta_string_func($sqlClpv, 'clpv_cod_tprov', $oIfx1, '');
							}
							$sql = "select tret_imp_ret ,  tret_cod_imp from saetret where
                                            tret_cod_empr = $id_empresa and
                                            tret_cod      = '$ret_cta_ret' and
                                            tret_porct    = $ret_porc_ret ";
							if ($oIfxA->Query($sql)) {
								$ret_cta_ret  	= $oIfxA->f('tret_cod_imp');
								$codigo       	= $oIfxA->f('tret_imp_ret');
							}
							//si es 327 t


							/*if ($ret_cta_ret == '327') {
								$xmlDividendo .= '<dividendos>';
								$xmlDividendo .= "<fechaPagoDiv>" . $fecha_pago_dividendo . "</fechaPagoDiv>";
								$xmlDividendo .= "<imRentaSoc>" . $ret_ir_divi . "</imRentaSoc>";
								$xmlDividendo .= "<ejerFisUtDiv>" . $ret_anio_divi . "</ejerFisUtDiv>";
								$xmlDividendo .= '</dividendos>';
							}*/

							if (substr($ret_cta_ret, 0, 1) == 3) {
								$totalsinimpuestos = $totalsinimpuestos + $ret_bas_imp;
								$importetotal = $importetotal + $ret_valor;
							};

							$xmldocsustentoTotal = "
					<totalSinImpuestos>" . round($totalsinimpuestos, 2) . "</totalSinImpuestos>";
							$xmldocsustentoTotal .= "
					<importeTotal>" . round($importetotal, 2) . "</importeTotal>";
						} while ($oIfx->SiguienteRegistro());
					}
				}
				$oIfx->Free();
				$xmldocsustento .= $xmldocsustentoTotal;

				$cod_iva = 0;

				if (intval($fprv_val_iva1) == 0 && intval($fprv_val_iva2) == 0) {
					$cod_iva = 0;
				} 



				$xmlimpdocdocsustento = "";
				if ($cod_iva == 0) {
					$xmlimpdocdocsustento .= "
					<impuestoDocSustento>
					<codImpuestoDocSustento>2</codImpuestoDocSustento>
					<codigoPorcentaje>" . $cod_iva . "</codigoPorcentaje> 
					<baseImponible>" . round($fprv_val_viva, 2) . "</baseImponible>	
					<tarifa>0</tarifa>
					<valorImpuesto>0</valorImpuesto>	
					</impuestoDocSustento>
					";
				} elseif (intval($fprv_val_iva1) > 0) {
					$xmlimpdocdocsustento .= "
					<impuestoDocSustento>
					<codImpuestoDocSustento>2</codImpuestoDocSustento>
					<codigoPorcentaje>0</codigoPorcentaje> 
					<baseImponible>" . round($fprv_val_bas3, 2) . "</baseImponible>	
					<tarifa>0</tarifa>
					<valorImpuesto>0</valorImpuesto>	
					</impuestoDocSustento>
					";
				} elseif (intval($fprv_val_iva2) > 0) {
					$xmlimpdocdocsustento .= "
					<impuestoDocSustento>
					<codImpuestoDocSustento>2</codImpuestoDocSustento>
					<codigoPorcentaje>0</codigoPorcentaje> 
					<baseImponible>" . round($fprv_val_bas4, 2) . "</baseImponible>	
					<tarifa>0</tarifa>
					<valorImpuesto>0</valorImpuesto>	
					</impuestoDocSustento>
					";
				}


				if ($xmlimpdocdocsustento != "") {
					$xmldocsustento .= "
					<impuestosDocSustento>
					" . $xmlimpdocdocsustento . "
					</impuestosDocSustento>";
				}


				if ($xmlImpu != "") {
					$xmldocsustento .= '
					<retenciones>';
					$xmldocsustento .= $xmlImpu;
					$xmldocsustento .= '</retenciones>';
				}


				$xml .= $xmldocsustento;

				/*if ($xmlDividendo != '') {
					$xml .= $xmlDividendo;
				}*/
				//////   si es reembolso 041
				$sql_remb = "select minr_tipo_iden, minr_ide_prov, minr_fpagop_prov, minr_tipo_prov, minr_tipo_docu, 
                             minr_num_esta, minr_pto_emis, minr_sec_docu, minr_fec_emis, minr_num_auto, minr_con_miva, 
                             minr_sin_miva, minr_iva_porc, minr_iva_valo
                             from saeminr 
                             where minr_num_comp = $ret_num_fact
                             and minr_cod_empr = $id_empresa
                             and minr_cod_sucu = $id_sucursal";
				//echo  $sql_remb; exit;
				if ($oIfx->Query($sql_remb)) {
					if ($oIfx->NumFilas() > 0) {
						$xmReembolsos .= '<reembolsos>';
						do {
							$minr_tipo_iden 	=  $oIfx->f('minr_tipo_iden');
							$minr_ide_prov		=  $oIfx->f('minr_ide_prov');
							$minr_fpagop_prov 	=  $oIfx->f('minr_fpagop_prov');
							$minr_tipo_prov 	=  $oIfx->f('minr_tipo_prov');
							$minr_tipo_docu 	=  $oIfx->f('minr_tipo_docu');
							$minr_num_esta 		=  $oIfx->f('minr_num_esta');
							$minr_pto_emis 		=  $oIfx->f('minr_pto_emis');
							$minr_sec_docu 		=  $oIfx->f('minr_sec_docu');
							$minr_fec_emis 		=  fecha_mysql_func($oIfx->f('minr_fec_emis'));
							$minr_num_auto 		=  $oIfx->f('minr_num_auto');
							$minr_con_miva 		=  round(($oIfx->f('minr_con_miva')), 2);
							$minr_sin_miva 		=  round(($oIfx->f('minr_sin_miva')), 2);
							$minr_iva_porc 		=  round($oIfx->f('minr_iva_porc'), 0);
							$minr_iva_valo 		=  $oIfx->f('minr_iva_valo');
							if ($minr_iva_porc == 0) $cod_iva = 0;
							if ($minr_iva_porc == 12) $cod_iva = 2;
							if ($minr_iva_porc == 14) $cod_iva = 3;
							if ($minr_iva_porc == 15) $cod_iva = 4;
							if ($minr_iva_porc == 5) $cod_iva = 5;
							if ($minr_iva_porc == 13) $cod_iva = 10;
							if ($minr_iva_porc == 8) $cod_iva = 8;

							$xmReembolsos .= '<reembolsoDetalle> 
                                    <tipoIdentificacionProveedorReembolso>' . $minr_tipo_iden . '</tipoIdentificacionProveedorReembolso>
                                    <identificacionProveedorReembolso>' . $minr_ide_prov . '</identificacionProveedorReembolso>
                                    <codPaisPagoProveedorReembolso>' . $minr_fpagop_prov . '</codPaisPagoProveedorReembolso > 
                                    <tipoProveedorReembolso>' . $minr_tipo_prov . '</tipoProveedorReembolso> 
                                    <codDocReembolso>' . $minr_tipo_docu . '</codDocReembolso> 
                                    <estabDocReembolso>' . $minr_num_esta . '</estabDocReembolso> 
                                    <ptoEmiDocReembolso>' . $minr_pto_emis . '</ptoEmiDocReembolso>
                                    <secuencialDocReembolso>' . $minr_sec_docu . '</secuencialDocReembolso>
                                    <fechaEmisionDocReembolso>' . $minr_fec_emis . '</fechaEmisionDocReembolso>
                                    <numeroAutorizacionDocReemb>' . $minr_num_auto . '</numeroAutorizacionDocReemb>';

							if ($cod_iva == 0) {
								$xmReembolsos .= '<detalleImpuestos> 
                                        <detalleImpuesto> 
                                            <codigo>2</codigo> 
                                            <codigoPorcentaje>' . $cod_iva . '</codigoPorcentaje>                                   
                                            <tarifa>' . $minr_iva_porc . '</tarifa>                  
                                            <baseImponibleReembolso>' . $minr_sin_miva . '</baseImponibleReembolso> 
                                            <impuestoReembolso>' . round($minr_iva_valo, 2) . '</impuestoReembolso>
                                        </detalleImpuesto> 
                                    </detalleImpuestos> 
                                </reembolsoDetalle>';
							} else if ($cod_iva == 2||$cod_iva == 3||$cod_iva == 4||$cod_iva == 5||$cod_iva == 10||$cod_iva == 8) {
								$xmReembolsos .= '<detalleImpuestos> 
                                        <detalleImpuesto> 
                                            <codigo>2</codigo> 
                                            <codigoPorcentaje>' . $cod_iva . '</codigoPorcentaje>                                   
                                            <tarifa>' . $minr_iva_porc . '</tarifa>                  
                                            <baseImponibleReembolso>' . $minr_con_miva . '</baseImponibleReembolso> 
                                            <impuestoReembolso>' . round($minr_iva_valo, 2) . '</impuestoReembolso>
                                        </detalleImpuesto> 
                                    </detalleImpuestos> 
                                </reembolsoDetalle>';
							}
						} while ($oIfx->SiguienteRegistro());
						$xmReembolsos .= '</reembolsos>';
					}
				}

				if ($codDocSustento == "41") {
					$sql_remb = "select fprr_cod_fprr,fprr_ruc_prov,fprr_tip_iden,fprr_seri_fprv,
                                    fprr_num_seri,fprr_num_esta,fprr_fec_emis,fprr_num_auto, 
                                    fprr_val_vivb,fprr_val_pivb,fprr_val_grab, fprr_val_vivs,fprr_val_pivs,
                                    fprr_val_gras,fprr_val_gra0,fprr_val_grs0,trans_tip_comp
                                    from saefprr, saetran
                                    where fprr_fac_fprv like '%$ret_num_fact%'
                                    and fprr_cod_empr = $id_empresa
                                    and fprr_cod_sucu = $id_sucursal
                                    and tran_cod_modu = 4
                                    and tran_cod_tran = fprr_cod_tran 
                                    group by 1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17
                                    order by fprr_cod_fprr";
					//echo  $sql_remb; exit;
					$xmReembolsos = '';

					if ($oIfx->Query($sql_remb)) {
						if ($oIfx->NumFilas() > 0) {
							do {

								$fprr_cod_fprr 	=  $oIfx->f('fprr_cod_fprr');
								$fprr_ruc_prov 	=  $oIfx->f('fprr_ruc_prov');
								$fprr_tip_iden 	=  $oIfx->f('fprr_tip_iden');
								$fprr_num_seri 	=  $oIfx->f('fprr_num_seri');
								$fprr_num_esta 	=  $oIfx->f('fprr_num_esta');
								$fprr_fec_emis 	=  $oIfx->f('fprr_fec_emis');
								$fprr_num_auto 	=  $oIfx->f('fprr_num_auto');
								$fprr_val_vivb 	=  $oIfx->f('fprr_val_vivb');
								$fprr_val_pivb 	=  $oIfx->f('fprr_val_pivb');
								$fprr_val_grab 	=  $oIfx->f('fprr_val_grab');
								$fprr_val_vivs 	=  $oIfx->f('fprr_val_vivs');
								$fprr_val_pivs 	=  $oIfx->f('fprr_val_pivs');
								$fprr_val_gras 	=  $oIfx->f('fprr_val_gras');
								$trans_tip_comp =  $oIfx->f('trans_tip_comp');
								$fprr_val_gra0 =  $oIfx->f('fprr_val_gra0');
								$fprr_val_grs0 =  $oIfx->f('fprr_val_grs0');
								$ll_numeros = strlen($fprr_cod_fprr);

								if ($fprr_tip_iden == '01') {
									$fprr_tip_iden = '04';
								} elseif ($fprr_tip_iden == '02') {
									$fprr_tip_iden = '05';
								} elseif ($fprr_tip_iden == '03') {
									$fprr_tip_iden = '06';
								}
								$fprv_fec_emisDocReemb = explode('-', $fprr_fec_emis);
								$fprv_fec_emisDocReemb = $fprv_fec_emisDocReemb[2] . '/' . $fprv_fec_emisDocReemb[1] . '/' . $fprv_fec_emisDocReemb[0];

								if ($fprr_tip_iden == '' || $fprr_tip_iden == NULL) {
									if (strlen($fprr_ruc_prov) == 10) {
										$fprr_tip_iden = '05';
									} elseif (strlen($fprr_ruc_prov) == 13) {
										$fprr_tip_iden = '04';
									} else {
										$fprr_tip_iden = '06';
									}
								} elseif ($fprr_tip_iden == '03') {
									$fprr_tip_iden = '06';
								}


								$xmReembolsos .= '<reembolsoDetalle> 
                                    <tipoIdentificacionProveedorReembolso>' . $fprr_tip_iden . '</tipoIdentificacionProveedorReembolso>
                                    <identificacionProveedorReembolso>' . $fprr_ruc_prov . '</identificacionProveedorReembolso>
                                    <codPaisPagoProveedorReembolso>' . $fprv_cod_paisp . '</codPaisPagoProveedorReembolso > 
                                    <tipoProveedorReembolso>' . $clpv_cod_tprov . '</tipoProveedorReembolso> 
                                    <codDocReembolso>' . $fprv_cre_fisc . '</codDocReembolso> 
                                    <estabDocReembolso>' . $fprr_num_seri . '</estabDocReembolso> 
                                    <ptoEmiDocReembolso>' . $fprr_num_esta . '</ptoEmiDocReembolso>
                                    <secuencialDocReembolso>' . cero_mas_func('0', 9 - $ll_numeros) . $fprr_cod_fprr . '</secuencialDocReembolso>
                                    <fechaEmisionDocReembolso>' . $fprv_fec_emisDocReemb . '</fechaEmisionDocReembolso>
                                    <numeroAutorizacionDocReemb>' . $fprr_num_auto . '</numeroAutorizacionDocReemb>
                                ';


								$xmReembolsos .= '<detalleImpuestos> ';
								if (($fprr_val_gra0) != 0) {
									$xmReembolsos .= ' 
                                        <detalleImpuesto> 
                                            <codigo>2</codigo> 
                                            <codigoPorcentaje>0</codigoPorcentaje>                                   
                                            <tarifa>0</tarifa>                  
											<baseImponibleReembolso>' . $fprr_val_gra0 . '</baseImponibleReembolso> 
                                            <impuestoReembolso>' . round(0, 2) . '</impuestoReembolso>
                                        </detalleImpuesto>  
                                    ';
								}
								if (($fprr_val_grs0) != 0) {
									$xmReembolsos .= ' 
                                        <detalleImpuesto> 
                                            <codigo>2</codigo> 
                                            <codigoPorcentaje>0</codigoPorcentaje>                                   
                                            <tarifa>0</tarifa>                  
											<baseImponibleReembolso>' . $fprr_val_grs0 . '</baseImponibleReembolso> 
                                            <impuestoReembolso>' . round(0, 2) . '</impuestoReembolso>
                                        </detalleImpuesto>  
                                    ';
								}
								if (($fprr_val_vivb) != 0) {
									if (intval($fprr_val_pivb) == 0) {
										$cod_iva = 0;
									} else if (intval($fprr_val_pivb) == 12) {
										$cod_iva = 2;
									} elseif (intval($fprr_val_pivb) == 14) {
										$cod_iva = 3;
									} elseif (intval($fprr_val_pivb) == 15) {
										$cod_iva = 4;
									} elseif (intval($fprr_val_pivb) == 5) {
										$cod_iva = 5;
									} elseif (intval($fprr_val_pivb) == 13) {
										$cod_iva = 10;
									}
									elseif (intval($fprr_val_pivb) == 8) {
										$cod_iva = 8;
									}

									$xmReembolsos .= ' 
                                        <detalleImpuesto> 
                                            <codigo>2</codigo> 
                                            <codigoPorcentaje>' . $cod_iva . '</codigoPorcentaje>                                   
                                            <tarifa>' . intval($fprr_val_pivb) . '</tarifa>                  
											<baseImponibleReembolso>' . $fprr_val_grab . '</baseImponibleReembolso> 
                                            <impuestoReembolso>' . round($fprr_val_vivb, 2) . '</impuestoReembolso>
                                        </detalleImpuesto>  
                                    ';
								}
								if (($fprr_val_vivs) != 0) {
									if (intval($fprr_val_pivs) == 0) {
										$cod_iva = 0;
									} else if (intval($fprr_val_pivs) == 12) {
										$cod_iva = 2;
									} elseif (intval($fprr_val_pivs) == 14) {
										$cod_iva = 3;
									} elseif (intval($fprr_val_pivs) == 15) {
										$cod_iva = 4;
									} elseif (intval($fprr_val_pivs) == 5) {
										$cod_iva = 5;
									} elseif (intval($fprr_val_pivs) == 13) {
										$cod_iva = 10;
									}
									elseif (intval($fprr_val_pivs) == 8) {
										$cod_iva = 8;
									}
									$xmReembolsos .= ' 
                                        <detalleImpuesto> 
                                            <codigo>2</codigo> 
                                            <codigoPorcentaje>' . $cod_iva . '</codigoPorcentaje>                                   
                                            <tarifa>' . intval($fprr_val_pivs) . '</tarifa>                  
											<baseImponibleReembolso>' . $fprr_val_gras . '</baseImponibleReembolso> 
                                            <impuestoReembolso>' . round($fprr_val_vivs, 2) . '</impuestoReembolso>
                                        </detalleImpuesto>  
                                    ';
								}

								$xmReembolsos .= '</detalleImpuestos>
                                            </reembolsoDetalle>';
							} while ($oIfx->SiguienteRegistro());
						}
					}


					$xml .= '<reembolsos>';
					$xml .= $xmReembolsos;
					$xml .= '</reembolsos>';
				}


				$sql = "select minv_tot_minv,minv_cod_fpagop
							 from saeminv
							 where minv_num_comp = $ret_num_fact
							 and minv_cod_empr = $id_empresa
							 and minv_cod_sucu = $id_sucursal";
				$numero_registros = consulta_string($sql, 'numero_registros', $oIfx, 0);
				$minv_cod_fpagop = consulta_string($sql, 'minv_cod_fpagop', $oIfx, 0);
				$minv_tot_minv = consulta_string($sql, 'minv_tot_minv', $oIfx, 0);

				$xmlpagos = '';
				if ($numero_registros > 0) {
					$xmlpagos = "<formapago>$minv_cod_fpagop</formapago>
					<total>$minv_tot_minv</total>";
				}

				$xml .= "<pagos>
						<pago>
						<formaPago>01</formaPago>
					<total>" . round($valorPago, 2) . "</total>
					</pago>
						</pagos>
						</docSustento>
							</docsSustento>
							";



				$aDataInfoAdic['Direccion'] = $rete_dire_benf;
				$aDataInfoAdic['Telefono'] = $rete_telf_benf;
				$aDataInfoAdic['Email'] = $ret_email_clpv;
				if (!empty($empr_ac2_empr)) {
					$aret = explode('-', $empr_ac2_empr);
					$numret = intval($aret[2]);
					$aDataInfoAdic['Agente de retencion'] = 'No. Resolucion: ' . $numret;
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
				$xmlInf = "";
				if (trim($ret_email_clpv) != '' || trim($rete_telf_benf) != '' || trim($rete_dire_benf) != '' || trim($empr_ac2_empr) != '') {
					if (count($etiqueta) > 0) {
						foreach ($etiqueta as $nom) {
							if ($aDataInfoAdic[$nom] != '')
								$xmlInf .= "<campoAdicional nombre=\"$nom\">$aDataInfoAdic[$nom]</campoAdicional>";
						}
					}
				}
				if ($xmlInf != "") {
					$xml .= "<infoAdicional>";
					$xml .= $xmlInf;
					$xml .= "</infoAdicional>";
				}

				$xml .= "</comprobanteRetencion>";

				//$oReturn->alert($xml);

				if ($empr_tip_firma == 'M') {

					//MultiFirma
					$nombre = $claveAcceso . ".xml";
					$serv = '';
					$archivo = '';
					$serv = DIR_FACTELEC . "modulos/sri_offline/documentoselectronicos/generados";
					$archivo = fopen($serv . '/' . $nombre, "w+");

					//fwrite($archivo, $xml);
					fwrite($archivo, utf8_encode($xml));
					fclose($archivo);
				}

				$sql_gasto = '';
				$id = 0;
				$oReturn->script("firmar('$nombre','$claveAcceso','$ruc_empr', '$ret_email_clpv', 'retencion_gasto', 
                                            '$sql_gasto', $id,  $rete_cod_clpv,  '$ret_num_fact', '$asto_cod_ejer', 
                                            '$rete_cod_asto', '$fprv_fec_emis' , 5, $id_sucursal)");
			} // fin check
		} // fin foreach
	} else {
		$oReturn->alert('Por favor realice una Busqueda...');
	}

	return $oReturn;
}

//NO SE USA
function firmar_reteGastpo($aForm = '')
{

	global $DSN_Ifx, $DSN;
	if (session_status() !== PHP_SESSION_ACTIVE) {
		session_start();
	}

	$oReturn = new xajaxResponse();

	$oIfx = new Dbo;
	$oIfx->DSN = $DSN_Ifx;
	$oIfx->Conectar();

	$oIfx1 = new Dbo;
	$oIfx1->DSN = $DSN_Ifx;
	$oIfx1->Conectar();

	$oIfxA = new Dbo;
	$oIfxA->DSN = $DSN_Ifx;
	$oIfxA->Conectar();

	//VARIABLES GLOBALES
	$id_empresa = $_SESSION['U_EMPRESA'];
	$array_gasto = $_SESSION['U_FACT_ENVIO'];

	unset($_SESSION['aDataGirdErro']);
	unset($array);

	$sql = "select empr_nom_empr, empr_ruc_empr, empr_dir_empr, empr_rimp_sn,
			empr_conta_sn, empr_num_resu, empr_tip_firma 
			from saeempr 
			where empr_cod_empr = $id_empresa ";
	if ($oIfx->Query($sql)) {
		if ($oIfx->NumFilas() > 0) {
			$razonSocial 	= trim($oIfx->f('empr_nom_empr'));
			$ruc_empr 		= $oIfx->f('empr_ruc_empr');
			$dirMatriz 		= trim($oIfx->f('empr_dir_empr'));
			$conta_sn 		= $oIfx->f('empr_conta_sn');
			$empr_num_resu 	= $oIfx->f('empr_num_resu');
			$empr_tip_firma = $oIfx->f('empr_tip_firma');
			$empr_rimp_sn = $oIfx->f('empr_rimp_sn');
		}
	}
	$oIfx->Free();

	//direccion sucursales
	$sql = "select sucu_cod_sucu, sucu_dir_sucu from saesucu where sucu_cod_empr = $id_empresa";
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
		$obligadoContabilidad = 'SI';
	} else {
		$obligadoContabilidad = 'NO';
	}

	//OTROS DATOS SRI
	$codDoc = '07';

	if (count($array_gasto) > 0) {
		foreach ($array_gasto as $val) {
			$rete_cod_clpv 	= $val[0];
			$ret_num_ret 	= $val[8];
			$ret_num_fact 	= $val[6];
			$asto_cod_ejer 	= $val[5];
			$id_sucursal 	= $val[14];
			$serial 		= $rete_cod_clpv . '_' . $ret_num_ret . '_' . $asto_cod_ejer . '_' . $ret_num_fact . '_' . $id_empresa . '_' . $id_sucursal . '_rg';
			$check 			= $aForm[$serial];

			$xml = '';
			$xmlImpu = '';
			$descuento = 0;
			if (!empty($check)) {
				//COMSULTAMOS LOS DATOS Y FORMAMOS LOS XML
				$num8 = 12345678;
				$fprv_num_seri 	= $val[7];
				$codDocSustento = $val[12];
				$rete_nom_benf 	= $val[1];
				$rete_dire_benf = $val[2];
				$fprv_fec_emis	= $val[4];
				$ret_email_clpv = $val[3];
				$rete_cod_asto 	= $val[13];
				$ret_ser_ret 	= $val[9];

				$tipoIdentificacionSujetoRetenido = $val[10];
				$claveAcceso 	= $val[15];

				//totalSinImpuestos
				//tarifa
				//genera clave de acceso
				$ambiente = ambienteEmisionSri($oIfx, $id_empresa, $id_sucursal, 1);
				$tipoEmision = ambienteEmisionSri($oIfx, $id_empresa, $id_sucursal, 2);

				//COMSULTAMOS LOS DATOS Y FORMAMOS LOS XML
				$sqlReteGast = "select rete_ruci_benf, rete_telf_benf,  ret_cta_ret,   
                                    ret_bas_imp, ret_porc_ret, ret_valor,ret_fpag_divi ,ret_anio_divi,ret_ir_divi
                                    from saeret where 
                                    asto_cod_empr = $id_empresa and 
                                    asto_cod_sucu = $id_sucursal and 
                                    ret_num_ret   = '$ret_num_ret' and 
                                    ret_num_fact  = '$ret_num_fact' and 
                                    rete_cod_asto = '$rete_cod_asto'  and 
                                    ret_cod_clpv  = $rete_cod_clpv and 
                                    asto_cod_ejer = $asto_cod_ejer ";


				if ($oIfx->Query($sqlReteGast)) {
					if ($oIfx->NumFilas() > 0) {
						do {
							$rete_ruci_benf 	= $oIfx->f('rete_ruci_benf');
							$rete_telf_benf 	= $oIfx->f('rete_telf_benf');
							$ret_cta_ret 		= $oIfx->f('ret_cta_ret');
							$ret_bas_imp 		= $oIfx->f('ret_bas_imp');
							$ret_porc_ret 		= $oIfx->f('ret_porc_ret');
							$ret_valor 			= $oIfx->f('ret_valor');
							$ret_fpag_divi  	= $oIfx->f('ret_fpag_divi');
							$ret_anio_divi  	= $oIfx->f('ret_anio_divi');
							$ret_ir_divi    	= $oIfx->f('ret_ir_divi');


							$fechaEmisionDocSustento = $fechaEmision = cambioFecha($fprv_fec_emis, 'mm/dd/aaaa', 'dd/mm/aaaa');

							$fecha_pago_dividendo    = cambioFecha($ret_fpag_divi, 'mm/dd/aaaa', 'dd/mm/aaaa');

							$numDocSustento = $fprv_num_seri . $ret_num_fact;

							if (empty($rete_ruci_benf)) {
								$sqlClpv = "select clpv_ruc_clpv from saeclpv where clpv_cod_clpv = $rete_cod_clpv";
								$rete_ruci_benf = consulta_string_func($sqlClpv, 'clpv_ruc_clpv', $oIfx1, '');
							}
							$sql = "select tret_imp_ret ,  tret_cod_imp from saetret where
                                            tret_cod_empr = $id_empresa and
                                            tret_cod      = '$ret_cta_ret' and
                                            tret_porct    = $ret_porc_ret ";
							if ($oIfxA->Query($sql)) {
								$ret_cta_ret  	= $oIfxA->f('tret_cod_imp');
								$codigo       	= $oIfxA->f('tret_imp_ret');
							}

							$sql_cod_sus = "select fprv_cre_fisc from saefprv  where fprv_cod_asto='$rete_cod_asto' and fprv_cod_ejer=$asto_cod_ejer ";
							$cod_suste = consulta_string_func($sql_cod_sus, 'fprv_cre_fisc', $oIfx1, '');

							//si es 327 t
							$xmlDividendo = '';


							if ($ret_cta_ret == '327') {
								$xmlDividendo .= '<dividendos>';
								$xmlDividendo .= "<fechaPagoDiv>" . $fecha_pago_dividendo . "</fechaPagoDiv>";
								$xmlDividendo .= "<imRentaSoc>" . $ret_ir_divi . "</imRentaSoc>";
								$xmlDividendo .= "<ejerFisUtDiv>" . $ret_anio_divi . "</ejerFisUtDiv>";
								$xmlDividendo .= '</dividendos>';
							}

							$xmldocsustento = '';
							$xmldocsustento .= "<docsSustento>";
							$xmldocsustento .= "<docSustento>";
							$xmldocsustento .= "<codSustento>" . $cod_suste . "</codSustento>";
							$xmldocsustento .= "<codDocSustento>" . $codDocSustento . "</codDocSustento>";
							$xmldocsustento .= "<numDocSustento>" . trim($numDocSustento) . "</numDocSustento>";
							$xmldocsustento .= "<fechaEmisionDocSustento>" . $fechaEmisionDocSustento . "</fechaEmisionDocSustento>";
							$xmldocsustento .= "<pagoLocExt>01</pagoLocExt>";
							$xmldocsustento .= "<totalSinImpuestos>" . round($ret_bas_imp, 2) . "</totalSinImpuestos>";
							$xmldocsustento .= "<importeTotal>" . round($ret_valor, 2) . "</importeTotal>";

							$xmldocsustento .= "<impuestosDocSustento>";
							$xmldocsustento .= "<impuestoDocSustento>";

							//$xmldocsustento.="<codImpuestoDocSustento>".$ret_cta_ret."</codImpuestoDocSustento>";
							$xmldocsustento .= "<codImpuestoDocSustento>2</codImpuestoDocSustento>";
							$xmldocsustento .= "<codigoPorcentaje>" . round($ret_porc_ret, 0) . "</codigoPorcentaje>";
							$xmldocsustento .= "<baseImponible>" . round($ret_bas_imp, 2) . "</baseImponible>";
							$xmldocsustento .= "<tarifa>0</tarifa>";
							$xmldocsustento .= "<valorImpuesto>" . round($ret_valor, 2) . "</valorImpuesto>";

							$xmldocsustento .= "</impuestoDocSustento>";
							$xmldocsustento .= "</impuestosDocSustento>";

							$xmldocsustento .= "<retenciones>";
							$xmldocsustento .= "<retencion>";
							$xmldocsustento .= "<codigo>" . $codigo . "</codigo>";
							$xmldocsustento .= "<codigoRetencion>" . $ret_cta_ret . "</codigoRetencion>";
							$xmldocsustento .= "<baseImponible>" . round($ret_bas_imp, 2) . "</baseImponible>";
							$xmldocsustento .= "<porcentajeRetener>" . $ret_porc_ret . "</porcentajeRetener>";
							$xmldocsustento .= "<valorRetenido>" . round($ret_valor, 2) . "</valorRetenido>";

							$xmldocsustento .= $xmlDividendo;
							$xmldocsustento .= "</retencion>";
							$xmldocsustento .= "</retenciones>";

							$xmldocsustento .= "<reembolsos>NA</reembolsos>";
							$xmldocsustento .= "<reembolsoDetalle>NA</reembolsoDetalle>";
							$xmldocsustento .= "</docSustento>";
							$xmldocsustento .= "</docsSustento>";

							$xmlImpu .= $xmldocsustento;
						} while ($oIfx->SiguienteRegistro());
					}
				}
				$oIfx->Free();

				$estab = substr($ret_ser_ret, 0, 3);
				$ptoEmi = substr($ret_ser_ret, 3, 6);

				$periodoFiscal = substr(cambioFecha($fprv_fec_emis, 'mm/dd/aaaa', 'dd/mm/aaaa'), 3, 7);

				$rimpe = "";
				if ($empr_rimp_sn == "S") {
					$rimpe = "<contribuyenteRimpe>CONTRIBUYENTE RÉGIMEN RIMPE</contribuyenteRimpe>";
				}

				$xml .= '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>';
				$xml .= '<comprobanteRetencion id="comprobante" version="1.0.0">';
				$xml .= '<infoTributaria>';
				$xml .= "<ambiente>$ambiente</ambiente>
							<tipoEmision>$tipoEmision</tipoEmision>
							<razonSocial>" . htmlspecialchars($razonSocial) . "</razonSocial>
							<nombreComercial>" . htmlspecialchars($razonSocial) . "</nombreComercial>
							<ruc>$ruc_empr</ruc>
							<claveAcceso>$claveAcceso</claveAcceso>
							<codDoc>$codDoc</codDoc>
							<estab>$estab</estab>
							<ptoEmi>$ptoEmi</ptoEmi>
							<secuencial>$ret_num_ret</secuencial>
							<dirMatriz>$dirMatriz</dirMatriz>
							" . $rimpe . "";
				//tipoSujetoRetenido
				$xml .= '</infoTributaria>';
				$xml .= '<infoCompRetencion>';
				$xml .= "<fechaEmision>$fechaEmision</fechaEmision>
						<dirEstablecimiento>" . $arrayDireSucu[$id_sucursal] . "</dirEstablecimiento>";
				if ($empr_num_resu != '')
					$xml .= '<contribuyenteEspecial>' . $empr_num_resu . '</contribuyenteEspecial>';

				$xml .= "<obligadoContabilidad>$obligadoContabilidad</obligadoContabilidad>
                                    <tipoIdentificacionSujetoRetenido>$tipoIdentificacionSujetoRetenido</tipoIdentificacionSujetoRetenido>
                                    <tipoSujetoRetenido>01</tipoSujetoRetenido>
									<parteRel>SI</parteRel>
									<razonSocialSujetoRetenido>" . htmlspecialchars($rete_nom_benf) . "</razonSocialSujetoRetenido>
                                    <identificacionSujetoRetenido>$rete_ruci_benf</identificacionSujetoRetenido>
                                    <periodoFiscal>$periodoFiscal</periodoFiscal>";
				$xml .= '</infoCompRetencion>';

				//$xml .= '<impuestos>';
				$xml .= $xmlImpu;
				//$xml .= '</impuestos>';
				//codDocSustento
				//$rete_dire_benf = "MANUEL VEGA 6-54 Y PRESIDENTE CORDOVA CUENCA AZU ECUADOR";

				$aDataInfoAdic['Direccion'] = $rete_dire_benf;
				$aDataInfoAdic['Telefono'] = $rete_telf_benf;
				$aDataInfoAdic['Email'] = $ret_email_clpv;
				$aDataInfoAdic['AGENTE RETENCION'] = 'NAC-DNCRASC20-00000001';

				$etiqueta = array_keys($aDataInfoAdic);
				if (count($etiqueta) > 0) {
					$xml .= '<infoAdicional>';
					foreach ($etiqueta as $nom) {
						if ($aDataInfoAdic[$nom] != '')
							$xml .= "<campoAdicional nombre=\"$nom\">$aDataInfoAdic[$nom]</campoAdicional>";
					}
					$xml .= '</infoAdicional>';
				}

				$xml .= '</comprobanteRetencion>';

				//$oReturn->alert($xml);

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

					$nombre = $claveAcceso . ".xml";
					$archivo = fopen($ruta_gene . '/' . $nombre, "w+");

					//fwrite($archivo, $xml);
					fwrite($archivo, utf8_encode($xml));
					fclose($archivo);
				} elseif ($empr_tip_firma == 'M') {

					//MultiFirma
					$nombre = $claveAcceso . ".xml";
					$serv = '';
					$archivo = '';
					$serv = DIR_FACTELEC . "modulos/sri_offline/documentoselectronicos/generados";
					$archivo = fopen($serv . '/' . $nombre, "w+");

					//fwrite($archivo, $xml);
					fwrite($archivo, utf8_encode($xml));
					fclose($archivo);
				}

				$sql_gasto = '';
				$id = 0;

				$oReturn->script("firmar('$nombre','$claveAcceso','$ruc_empr', '$ret_email_clpv', 'retencion_gasto', 
                                            '$sql_gasto', $id,  $rete_cod_clpv,  '$ret_num_fact', '$asto_cod_ejer', 
                                            '$rete_cod_asto', '$fprv_fec_emis' , 5, $id_sucursal)");
			} // fin check
		} // fin foreach
	} else {
		$oReturn->alert('Por favor realice una Busqueda...');
	}

	return $oReturn;
}

function firmar_reteGastCO($aForm = '')
{

	global $DSN_Ifx, $DSN;
	if (session_status() !== PHP_SESSION_ACTIVE) {
		session_start();
	}

	$oReturn = new xajaxResponse();

	$oIfx = new Dbo;
	$oIfx->DSN = $DSN_Ifx;
	$oIfx->Conectar();

	$oIfx1 = new Dbo;
	$oIfx1->DSN = $DSN_Ifx;
	$oIfx1->Conectar();

	$oIfxA = new Dbo;
	$oIfxA->DSN = $DSN_Ifx;
	$oIfxA->Conectar();

	//VARIABLES GLOBALES
	$id_empresa = $_SESSION['U_EMPRESA'];
	$array_gasto = $_SESSION['U_FACT_ENVIO'];

	unset($_SESSION['aDataGirdErro']);
	unset($array);

	$sql = "select empr_nom_empr, empr_ruc_empr, empr_dir_empr, empr_rimp_sn,
			empr_conta_sn, empr_num_resu, empr_tip_firma 
			from saeempr 
			where empr_cod_empr = $id_empresa ";
	if ($oIfx->Query($sql)) {
		if ($oIfx->NumFilas() > 0) {
			$razonSocial 	= trim($oIfx->f('empr_nom_empr'));
			$ruc_empr 		= $oIfx->f('empr_ruc_empr');
			$dirMatriz 		= trim($oIfx->f('empr_dir_empr'));
			$conta_sn 		= $oIfx->f('empr_conta_sn');
			$empr_num_resu 	= $oIfx->f('empr_num_resu');
			$empr_tip_firma = $oIfx->f('empr_tip_firma');
			$empr_rimp_sn = $oIfx->f('empr_rimp_sn');
		}
	}
	$oIfx->Free();

	//direccion sucursales
	$sql = "select sucu_cod_sucu, sucu_dir_sucu from saesucu where sucu_cod_empr = $id_empresa";
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
		$obligadoContabilidad = 'SI';
	} else {
		$obligadoContabilidad = 'NO';
	}

	//OTROS DATOS SRI
	$codDoc = '07';

	if (count($array_gasto) > 0) {
		foreach ($array_gasto as $val) {
			$rete_cod_clpv 	= $val[0];
			$ret_num_ret 	= $val[8];
			$ret_num_fact 	= $val[6];
			$asto_cod_ejer 	= $val[5];
			$id_sucursal 	= $val[14];
			$serial 		= $rete_cod_clpv . '_' . $ret_num_ret . '_' . $asto_cod_ejer . '_' . $ret_num_fact . '_' . $id_empresa . '_' . $id_sucursal . '_rg';
			$check 			= $aForm[$serial];

			$xml = '';
			$xmlImpu = '';
			$descuento = 0;
			if (!empty($check)) {
				//COMSULTAMOS LOS DATOS Y FORMAMOS LOS XML
				$num8 = 12345678;
				$fprv_num_seri 	= $val[7];
				$codDocSustento = $val[12];
				$rete_nom_benf 	= $val[1];
				$rete_dire_benf = $val[2];
				$fprv_fec_emis	= $val[4];
				$ret_email_clpv = $val[3];
				$rete_cod_asto 	= $val[13];
				$ret_ser_ret 	= $val[9];

				$tipoIdentificacionSujetoRetenido = $val[10];
				$claveAcceso 	= $val[15];

				//totalSinImpuestos
				//tarifa
				//genera clave de acceso
				$ambiente = ambienteEmisionSri($oIfx, $id_empresa, $id_sucursal, 1);
				$tipoEmision = ambienteEmisionSri($oIfx, $id_empresa, $id_sucursal, 2);

				//COMSULTAMOS LOS DATOS Y FORMAMOS LOS XML
				$sqlReteGast = "select rete_ruci_benf, rete_telf_benf,  ret_cta_ret,   
                                    ret_bas_imp, ret_porc_ret, ret_valor,ret_fpag_divi ,ret_anio_divi,ret_ir_divi
                                    from saeret where 
                                    asto_cod_empr = $id_empresa and 
                                    asto_cod_sucu = $id_sucursal and 
                                    ret_num_ret   = '$ret_num_ret' and 
                                    ret_num_fact  = '$ret_num_fact' and 
                                    rete_cod_asto = '$rete_cod_asto'  and 
                                    ret_cod_clpv  = $rete_cod_clpv and 
                                    asto_cod_ejer = $asto_cod_ejer ";
				if ($oIfx->Query($sqlReteGast)) {
					if ($oIfx->NumFilas() > 0) {
						do {
							$rete_ruci_benf 	= $oIfx->f('rete_ruci_benf');
							$rete_telf_benf 	= $oIfx->f('rete_telf_benf');
							$ret_cta_ret 		= $oIfx->f('ret_cta_ret');
							$ret_bas_imp 		= $oIfx->f('ret_bas_imp');
							$ret_porc_ret 		= $oIfx->f('ret_porc_ret');
							$ret_valor 			= $oIfx->f('ret_valor');
							$ret_fpag_divi  	= $oIfxA->f('ret_fpag_divi');
							$ret_anio_divi  	= $oIfxA->f('ret_anio_divi');
							$ret_ir_divi    	= $oIfxA->f('ret_ir_divi');

							$fechaEmisionDocSustento = $fechaEmision = cambioFecha($fprv_fec_emis, 'mm/dd/aaaa', 'dd/mm/aaaa');

							$fecha_pago_dividendo    = $fechaEmision = cambioFecha($ret_fpag_divi, 'mm/dd/aaaa', 'dd/mm/aaaa');

							$numDocSustento = $fprv_num_seri . $ret_num_fact;

							if (empty($rete_ruci_benf)) {
								$sqlClpv = "select clpv_ruc_clpv from saeclpv where clpv_cod_clpv = $rete_cod_clpv";
								$rete_ruci_benf = consulta_string_func($sqlClpv, 'clpv_ruc_clpv', $oIfx1, '');
							}
							$sql = "select tret_imp_ret ,  tret_cod_imp from saetret where
                                            tret_cod_empr = $id_empresa and
                                            tret_cod      = '$ret_cta_ret' and
                                            tret_porct    = $ret_porc_ret ";
							if ($oIfxA->Query($sql)) {
								$ret_cta_ret  	= $oIfxA->f('tret_cod_imp');
								$codigo       	= $oIfxA->f('tret_imp_ret');
							}
							//si es 327 t
							$xmlDividendo = '';


							if ($ret_cta_ret == '327') {
								$xmlDividendo .= '<dividendos>';
								$xmlDividendo .= "<fechaPagoDiv>" . $fecha_pago_dividendo . "</fechaPagoDiv>";
								$xmlDividendo .= "<imRentaSoc>" . $ret_ir_divi . "</imRentaSoc>";
								$xmlDividendo .= "<ejerFisUtDiv>" . $ret_anio_divi . "</ejerFisUtDiv >";
								$xmlDividendo .= '</dividendos>';
							}

							$xmldocsustento = "";
							$xmldocsustento .= "<docsSustento>";
							$xmldocsustento .= "<docSustento>";
							$xmldocsustento .= "<codSustento>" . $codDocSustento . "</codSustento>";
							$xmldocsustento .= "<codDocSustento>" . $codDocSustento . "</codDocSustento>";
							$xmldocsustento .= "<numDocSustento>" . trim($numDocSustento) . "</numDocSustento>";
							$xmldocsustento .= "<fechaEmisionDocSustento>" . $fechaEmisionDocSustento . "</fechaEmisionDocSustento>";
							//$xmldocsustento.="<pagoLocExt>01</pagoLocExt>";
							$xmldocsustento .= "<totalSinImpuestos>" . round($ret_bas_imp, 2) . "</totalSinImpuestos>";
							$xmldocsustento .= "<importeTotal>" . round($ret_valor, 2) . "</importeTotal>";

							$xmldocsustento .= "<impuestosDocSustento>";
							$xmldocsustento .= "<impuestoDocSustento>";

							$xmldocsustento .= "<codImpuestoDocSustento>2</codImpuestoDocSustento>";
							$xmldocsustento .= "<codigoPorcentaje>2</codigoPorcentaje>";
							$xmldocsustento .= "<baseImponible>" . round($ret_bas_imp, 2) . "</baseImponible>";
							$xmldocsustento .= "<tarifa>0</tarifa>";
							$xmldocsustento .= "<valorImpuesto>" . round($ret_valor, 2) . "</valorImpuesto>";

							$xmldocsustento .= "</impuestoDocSustento>";
							$xmldocsustento .= "</impuestosDocSustento>";

							$xmldocsustento .= "<retenciones>";
							$xmldocsustento .= "<retencion>";
							$xmldocsustento .= "<codigo>" . $codigo . "</codigo>";
							$xmldocsustento .= "<codigo>" . $ret_cta_ret . "</codigo>";
							$xmldocsustento .= "<baseImponible>" . round($ret_bas_imp, 2) . "</baseImponible>";
							$xmldocsustento .= "<porcentajeRetener>" . $ret_porc_ret . "</porcentajeRetener>";
							$xmldocsustento .= "<valorRetenido>" . round($ret_valor, 2) . "</valorRetenido>";
							$xmldocsustento .= $xmlDividendo;
							$xmldocsustento .= "</retencion>";
							$xmldocsustento .= "</retenciones>";
							$xmldocsustento .= "</docSustento>";
							$xmldocsustento .= "</docsSustento>";
							$xmlImpu .= $xmldocsustento;
						} while ($oIfx->SiguienteRegistro());
					}
				}
				$oIfx->Free();

				$estab = substr($ret_ser_ret, 0, 3);
				$ptoEmi = substr($ret_ser_ret, 3, 6);

				$periodoFiscal = substr(cambioFecha($fprv_fec_emis, 'mm/dd/aaaa', 'dd/mm/aaaa'), 3, 7);

				$rimpe = "";
				if ($empr_rimp_sn == "S") {
					$rimpe = "<contribuyenteRimpe>CONTRIBUYENTE RÉGIMEN RIMPE</contribuyenteRimpe>";
				}

				$xml .= '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>';
				$xml .= '<comprobanteRetencion id="comprobante" version="1.0.0">';
				$xml .= '<infoTributaria>';
				$xml .= "<ambiente>$ambiente</ambiente>
							<tipoEmision>$tipoEmision</tipoEmision>
							<razonSocial>" . htmlspecialchars($razonSocial) . "</razonSocial>
							<nombreComercial>" . htmlspecialchars($razonSocial) . "</nombreComercial>
							<ruc>$ruc_empr</ruc>
							<claveAcceso>$claveAcceso</claveAcceso>
							<codDoc>$codDoc</codDoc>
							<estab>$estab</estab>
							<ptoEmi>$ptoEmi</ptoEmi>
							<secuencial>$ret_num_ret</secuencial>
							<dirMatriz>$dirMatriz</dirMatriz>
							" . $rimpe . "";
				//tipoSujetoRetenido
				$xml .= '</infoTributaria>';
				$xml .= '<infoCompRetencion>';
				$xml .= "<fechaEmision>$fechaEmision</fechaEmision>
						<dirEstablecimiento>" . $arrayDireSucu[$id_sucursal] . "</dirEstablecimiento>";
				if ($empr_num_resu != '')
					$xml .= '<contribuyenteEspecial>' . $empr_num_resu . '</contribuyenteEspecial>';

				$xml .= "<obligadoContabilidad>$obligadoContabilidad</obligadoContabilidad>
                                    <tipoIdentificacionSujetoRetenido>$tipoIdentificacionSujetoRetenido</tipoIdentificacionSujetoRetenido>
                                    <razonSocialSujetoRetenido>" . htmlspecialchars($rete_nom_benf) . "</razonSocialSujetoRetenido>
                                    <identificacionSujetoRetenido>$rete_ruci_benf</identificacionSujetoRetenido>
                                    <periodoFiscal>$periodoFiscal</periodoFiscal>";
				$xml .= '</infoCompRetencion>';

				//$xml .= '<impuestos>';
				$xml .= $xmlImpu;
				//$xml .= '</impuestos>';
				//codDocSustento
				//$rete_dire_benf = "MANUEL VEGA 6-54 Y PRESIDENTE CORDOVA CUENCA AZU ECUADOR";

				$aDataInfoAdic['Direccion'] = $rete_dire_benf;
				$aDataInfoAdic['Telefono'] = $rete_telf_benf;
				$aDataInfoAdic['Email'] = $ret_email_clpv;
				$aDataInfoAdic['AGENTE RETENCION'] = 'NAC-DNCRASC20-00000001';

				$etiqueta = array_keys($aDataInfoAdic);
				if (count($etiqueta) > 0) {
					$xml .= '<infoAdicional>';
					foreach ($etiqueta as $nom) {
						if ($aDataInfoAdic[$nom] != '')
							$xml .= "<campoAdicional nombre=\"$nom\">$aDataInfoAdic[$nom]</campoAdicional>";
					}
					$xml .= '</infoAdicional>';
				}

				$xml .= '</comprobanteRetencion>';

				//$oReturn->alert($xml);

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

					$nombre = $claveAcceso . ".xml";
					$archivo = fopen($ruta_gene . '/' . $nombre, "w+");

					//fwrite($archivo, $xml);
					fwrite($archivo, utf8_encode($xml));
					fclose($archivo);
				} elseif ($empr_tip_firma == 'M') {

					//MultiFirma
					$nombre = $claveAcceso . ".xml";
					$serv = '';
					$archivo = '';
					$serv = DIR_FACTELEC . "modulos/sri_offline/documentoselectronicos/generados";
					$archivo = fopen($serv . '/' . $nombre, "w+");

					//fwrite($archivo, $xml);
					fwrite($archivo, utf8_encode($xml));
					fclose($archivo);
				}

				$sql_gasto = '';
				$id = 0;

				$oReturn->script("firmar('$nombre','$claveAcceso','$ruc_empr', '$ret_email_clpv', 'retencion_gasto', 
                                            '$sql_gasto', $id,  $rete_cod_clpv,  '$ret_num_fact', '$asto_cod_ejer', 
                                            '$rete_cod_asto', '$fprv_fec_emis' , 5, $id_sucursal)");
			} // fin check
		} // fin foreach
	} else {
		$oReturn->alert('Por favor realice una Busqueda...');
	}

	return $oReturn;
}

function firmar_liquidacionCompra($aForm = '')
{

	global $DSN_Ifx, $DSN;
	if (session_status() !== PHP_SESSION_ACTIVE) {
		session_start();
	}

	$oReturn = new xajaxResponse();

	$oIfx = new Dbo;
	$oIfx->DSN = $DSN_Ifx;
	$oIfx->Conectar();

	$oIfx1 = new Dbo;
	$oIfx1->DSN = $DSN_Ifx;
	$oIfx1->Conectar();

	//VARIABLES GLOBALES
	$id_empresa = $_SESSION['U_EMPRESA'];
	$array_fact = $_SESSION['U_FACT_ENVIO'];
	//var_dump($array_fact); exit;
	unset($_SESSION['aDataGirdErro']);
	unset($array);

	$array_imp = $_SESSION['U_EMPRESA_IMPUESTO'];
	$etiqueta_iva=$array_imp ['IVA'];
    $empr_cod_pais = $_SESSION['U_PAIS_COD'];
     // IMPUESTOS POR PAIS
     $sql = "select p.impuesto, p.etiqueta, p.porcentaje from comercial.pais_etiq_imp p where
     p.pais_cod_pais = $empr_cod_pais and etiqueta='$etiqueta_iva'";
    if ($oIfx1->Query($sql)) {
        if ($oIfx1->NumFilas() > 0) {
            do {
                $empr_iva_porc = $oIfx1->f('porcentaje');
            } while ($oIfx1->SiguienteRegistro());
        }
    }
    $oIfx1->Free();

	//datos de la empresa
	$sql = "select empr_nom_empr, empr_ruc_empr, empr_tip_firma,empr_rimp_sn,
			empr_dir_empr, empr_conta_sn, empr_num_resu 
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
		}
	}
	$oIfx->Free();

	//direccion sucursales
	$sql = "select sucu_cod_sucu, sucu_dir_sucu from saesucu where sucu_cod_empr = $id_empresa";
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

	// DATOS
	if (count($array_fact) > 0) {
		foreach ($array_fact as $val) {

			$id_factura = $val[0];
			$check = $aForm[$id_factura . '_f'];     //$fact_cod_fact.'_f'
			$xml = '';
			$xmlDeta = '';
			$descuento = 0;
			if (!empty($check)) {
				//COMSULTAMOS LOS DATOS Y FORMAMOS LOS XML
				$num8 				= 12345678;
				$num_preimp 	= $val[0];
				$ruc_clie 		= $val[1];
				$nom_cliente 	= $val[2];
				$email_clpv 	= $val[3];
				$tlf_cliente 	= $val[4];
				$dir_cliente 	= $val[5];
				$fecha       	= $val[6];
				$nse_fact 		= $val[7];
				$cod_clpv 		= $val[8];
				$tipoIdentificacionComprador = $val[9];
				$id_sucursal 	= $val[10];
				$clave_acceso 	= $val[11];
				$total_graba_0 	= $val[12];
				$total_graba_12 = $val[13];
				$valor_iva  	= $val[14];
				$porc_iva 	    = $val[15];
				$fprv_cod_tpago = $val[16];
				$num_dias       = $val[17];
				$num_comp       = $val[18];
				$totalDescuento 	   = 0;
				$baseImponibleIce 	   = 0;
				$baseImponibleIceTotal = 0;

				//direccion sucursal

				//genera clave de acceso
				$ambiente = ambienteEmisionSri($oIfx, $id_empresa, $id_sucursal, 1);
				$tip_emis = ambienteEmisionSri($oIfx, $id_empresa, $id_sucursal, 2);


				//Validacion por tipo de documento ficha proveedor/cliente SUEJO4967
				$sql = "SELECT clv_con_clpv from saeclpv where clpv_ruc_clpv='$ruc_clie'";
				$tipo_documento = consulta_string($sql, 'clv_con_clpv', $oIfx, '');


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
					$clv_con_clpv = strlen($ruc_clie);
					//$oReturn->alert($clv_con_clpv);
					if ($clv_con_clpv == 13)
						$tipoIdentificacionComprador = '04'; //ruc XML
					else if ($clv_con_clpv == 10)
						$tipoIdentificacionComprador = '05'; //cedula
					else
						$tipoIdentificacionComprador = '06'; //pasaporte
				}


				$totalSinImpuestos  = $total_graba_0 + $total_graba_12;
				$importeTotal 		= round($totalSinImpuestos + $valor_iva, 2);
				$con_iva = 0;
				$sin_iva = 0;
				$con_iva = $total_graba_12;
				$sin_iva = $total_graba_0;
				$estable = substr($nse_fact, 0, 3);
				$serie   = substr($nse_fact, 3, 6);


				$fec_fact = date_format(date_create($fecha), "d/m/Y");
				//$fec_fact = cambioFecha($fecha, 'mm/dd/aaaa', 'dd/mm/aaaa');
				$rimpe = "";
				if ($empr_rimp_sn == "S") {
					$rimpe = "<contribuyenteRimpe>CONTRIBUYENTE RÉGIMEN RIMPE</contribuyenteRimpe>";
				}
				///CABECERA DEL $XML
				$xml .= '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
                                    <liquidacionCompra id="comprobante" version="1.1.0">
                                        <infoTributaria>
                                            <ambiente>' . $ambiente . '</ambiente>
                                            <tipoEmision>' . $tip_emis . '</tipoEmision>
                                            <razonSocial>' . htmlspecialchars($nombre_empr) . '</razonSocial>
                                            <nombreComercial>' . htmlspecialchars($nombre_empr) . '</nombreComercial>
                                            <ruc>' . $ruc_empr . '</ruc>
                                            <claveAcceso>' . $clave_acceso . '</claveAcceso>
                                            <codDoc>03</codDoc>
                                            <estab>' . $estable . '</estab>
                                            <ptoEmi>' . $serie . '</ptoEmi>
                                            <secuencial>' . $num_preimp . '</secuencial>
                                            <dirMatriz>' . $dir_empr . '</dirMatriz>
											' . $rimpe . '
                                        </infoTributaria>
                                        <infoLiquidacionCompra>
                                            <fechaEmision>' . $fec_fact . '</fechaEmision>
											<dirEstablecimiento>' . $arrayDireSucu[$id_sucursal] . '</dirEstablecimiento>';
				if ($empr_num_resu != '')
					$xml .= '<contribuyenteEspecial>' . $empr_num_resu . '</contribuyenteEspecial>';

				$xml .= '<obligadoContabilidad>' . $conta_sn . '</obligadoContabilidad>
                                    <tipoIdentificacionProveedor>' . $tipoIdentificacionComprador . '</tipoIdentificacionProveedor>
                                    <razonSocialProveedor>' . htmlspecialchars($nom_cliente) . '</razonSocialProveedor>
                                    <identificacionProveedor>' . $ruc_clie . '</identificacionProveedor>
                                    <direccionProveedor>' . htmlspecialchars($dir_cliente) . '</direccionProveedor>
                                    <totalSinImpuestos>' . round($totalSinImpuestos, 2) . '</totalSinImpuestos>
                                    <totalDescuento>' . round($totalDescuento, 2) . '</totalDescuento>';
				//// si es reembolso 041
				$sql = "select count(*) as numero_registros, 
                                sum(minr_con_miva) + sum(minr_sin_miva)   as suma_base, 
                                sum(minr_iva_valo ) as suma_iva, 
                                sum(minr_con_miva) + sum(minr_iva_valo)+ sum(minr_sin_miva) as total_fact
                             from saeminr 
                             where minr_num_comp = $num_comp
                             and minr_cod_empr = $id_empresa
                             and minr_cod_sucu = $id_sucursal";
				$numero_registros = consulta_string($sql, 'numero_registros', $oIfx, 0);
				$suma_base = consulta_string($sql, 'suma_base', $oIfx, 0);
				$suma_comp = consulta_string($sql, 'total_fact', $oIfx, 0);
				$suma_iva = consulta_string($sql, 'suma_iva', $oIfx, 0);

				if ($numero_registros > 0) {
					$codDocReembolso = '41';
					$total_base_imponible = $suma_base;
					$tatal_comprobantes = $suma_comp;
					$total_iva = $suma_iva;
					$xml .= '<codDocReembolso>' . $codDocReembolso . '</codDocReembolso> 
							<totalComprobantesReembolso>' . round($tatal_comprobantes, 2) . '</totalComprobantesReembolso>
							<totalBaseImponibleReembolso>' . round($total_base_imponible, 2) . '</totalBaseImponibleReembolso> 
							<totalImpuestoReembolso>' . round($total_iva, 2) . '</totalImpuestoReembolso>';
				} else {
					$codDocReembolso = '00';
					$xml .= '<codDocReembolso>' . $codDocReembolso . '</codDocReembolso> 
							<totalComprobantesReembolso>0.00</totalComprobantesReembolso>
							<totalBaseImponibleReembolso>0.00</totalBaseImponibleReembolso> 
							<totalImpuestoReembolso>0.00</totalImpuestoReembolso>';
				}
				$xml .= "<totalConImpuestos> ";


				//	VALIDACION PORCENTAJE IVA CABECERA SAEDMOV
				$sql_1 = "select dmov_cod_prod, prod_nom_prod, unid_nom_unid, dmov_can_dmov, 
				dmov_cun_dmov, 
				(case when dmov_ds1_dmov > 0 then  ((dmov_can_dmov * dmov_cun_dmov) / dmov_ds1_dmov)
				  else 0
				  end 
				 ) as descuento,
				dmov_iva_porc
				from saedmov, saeprod, saeunid 
				where  prod_cod_prod = dmov_cod_prod
				and prod_cod_empr = dmov_cod_empr
				and prod_cod_sucu = dmov_cod_sucu
				and unid_cod_unid = dmov_cod_unid
				and unid_cod_empr = dmov_cod_empr
				and dmov_cod_empr  = $id_empresa
				and dmov_cod_sucu = $id_sucursal
				and dmov_num_comp = $num_comp";

				if ($oIfx->Query($sql_1)) {
					if ($oIfx->NumFilas() > 0) {
						
						do {
							
							$dmov_iva_porc =  $oIfx->f('dmov_iva_porc');
							if(!empty($dmov_iva_porc) && $dmov_iva_porc>0) $porc_iva=$dmov_iva_porc;
														
						} while ($oIfx->SiguienteRegistro());
					}
				}
				$oIfx->free();				



				if ($con_iva > 0) {
					
					if(empty($porc_iva)||$porc_iva=='') $porc_iva=$empr_iva_porc;
					
					if (round($porc_iva) == 12) $bandera = 2;
					if (round($porc_iva) == 14) $bandera = 3;
					if (round($porc_iva) == 15) $bandera = 4;
					if (round($porc_iva) == 5)  $bandera = 5;
					if (round($porc_iva) == 13) $bandera = 10;
					if (round($porc_iva) == 8) $bandera = 8;

					$xml .= '<totalImpuesto>
                                <codigo>2</codigo>
                                <codigoPorcentaje>' . $bandera . '</codigoPorcentaje>
                                <descuentoAdicional>0.00</descuentoAdicional>
                                <baseImponible>' . round($con_iva, 2) . '</baseImponible>
                                <tarifa>' . round($porc_iva, 0) . '</tarifa>
                                <valor>' . round($valor_iva, 2) . '</valor>
                            </totalImpuesto>';
				}
				if ($sin_iva > 0) {
					// antes
					// <valor>' . round($sin_iva, 2) . '</valor>
					$xml .= '<totalImpuesto>
                                <codigo>2</codigo>
                                <codigoPorcentaje>0</codigoPorcentaje>
                                <descuentoAdicional>00</descuentoAdicional>
                                <baseImponible>' . round($sin_iva, 2) . '</baseImponible>
                                <tarifa>0.00</tarifa>
                                <valor>0.00</valor>
                            </totalImpuesto>';
				}

				//$oReturn->alert($baseImponibleIRBP);

				$xml .= "</totalConImpuestos> ";
				$xml .= '<importeTotal>' . round($importeTotal, 2) . '</importeTotal>
                        <moneda>DOLAR</moneda>';
				//echo $xml; exit;
				//query forma de pago
				$sqlFPago = "select fpag_cod_fpagop, mxfp_val_mxfp, mxfp_num_dias
                            from saemxfp, saefpag
                            where mxfp_cod_empr = fpag_cod_empr 
                            and mxfp_cod_sucu = fpag_cod_sucu 
                            and mxfp_cod_fpag = fpag_cod_fpag
                            and  mxfp_num_comp = $num_comp
                            and mxfp_cod_empr = $id_empresa
                            and mxfp_cod_sucu = $id_sucursal";
				if ($oIfx->Query($sqlFPago)) {
					if ($oIfx->NumFilas() > 0) {
						$xml .= '<pagos>';
						do {
							$fpag_cod_fpagop = $oIfx->f('fpag_cod_fpagop');
							$fxfp_val_fxfp = $oIfx->f('mxfp_val_mxfp');
							$fxfp_num_dias = $oIfx->f('mxfp_num_dias');

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

				$xml .= '</infoLiquidacionCompra>';
				//echo $xml; exit;
				$sql_1 = "select dmov_cod_prod, prod_nom_prod, unid_nom_unid, dmov_can_dmov, 
                          dmov_cun_dmov, 
                          (case when dmov_ds1_dmov > 0 then  ((dmov_can_dmov * dmov_cun_dmov) / dmov_ds1_dmov)
                            else 0
                            end 
                           ) as descuento,
                          dmov_iva_porc
                          from saedmov, saeprod, saeunid 
                          where  prod_cod_prod = dmov_cod_prod
                          and prod_cod_empr = dmov_cod_empr
                          and prod_cod_sucu = dmov_cod_sucu
                          and unid_cod_unid = dmov_cod_unid
                          and unid_cod_empr = dmov_cod_empr
                          and dmov_cod_empr  = $id_empresa
                          and dmov_cod_sucu = $id_sucursal
                          and dmov_num_comp = $num_comp";
				// echo  $sql_1; exit;
				if ($oIfx->Query($sql_1)) {
					if ($oIfx->NumFilas() > 0) {
						$xml .= '<detalles>';
						do {
							$dmov_cod_prod =  $oIfx->f('dmov_cod_prod');
							$prod_nom_prod =  $oIfx->f('prod_nom_prod');
							$unid_nom_unid =  $oIfx->f('unid_nom_unid');
							$dmov_can_dmov =  $oIfx->f('dmov_can_dmov');
							$dmov_cun_dmov =  $oIfx->f('dmov_cun_dmov');
							$descuento     =  $oIfx->f('descuento');
							$dmov_iva_porc =  $oIfx->f('dmov_iva_porc');
							$valor_iva    =  ($dmov_can_dmov * $dmov_cun_dmov * $dmov_iva_porc) / 100;
							$sub_coniva    =  round(($dmov_can_dmov * $dmov_cun_dmov), 2);
							if (empty($descuento)) {
								$descuento = 0;
							}
							$xml .= '<detalle>                            
                                        <codigoPrincipal>' . $dmov_cod_prod . '</codigoPrincipal>
                                        <codigoAuxiliar>' . $dmov_cod_prod . '</codigoAuxiliar>
                                        <descripcion>' . $prod_nom_prod . '</descripcion>
                                        <unidadMedida>' . $unid_nom_unid . '</unidadMedida>
                                        <cantidad>' . $dmov_can_dmov . '</cantidad>
                                        <precioUnitario>' . $dmov_cun_dmov . '</precioUnitario>
                                        <descuento>' . $descuento . '</descuento>
                                        <precioTotalSinImpuesto>' . $sub_coniva . '</precioTotalSinImpuesto>						
                                        <impuestos>';

							if ($dmov_iva_porc == "0") {
								$bandera = '0';
								$xml .= '<impuesto>
                                                        <codigo>2</codigo> 
                                                        <codigoPorcentaje>' . $bandera . '</codigoPorcentaje>                                          
                                                        <tarifa>' . round($dmov_iva_porc, 0) . '</tarifa>                                            
                                                        <baseImponible>' . round($sub_coniva, 2) . '</baseImponible>
                                                        <valor>' . round($valor_iva, 2) . '</valor>
                                                    </impuesto>';
							}
							if ($dmov_iva_porc == "12") {
								$bandera = '2';
								$xml .= '<impuesto>
                                                        <codigo>2</codigo> 
                                                        <codigoPorcentaje>' . $bandera . '</codigoPorcentaje>                                          
                                                        <tarifa>' . round($dmov_iva_porc, 0) . '</tarifa>                                            
                                                        <baseImponible>' . round($sub_coniva, 2) . '</baseImponible>
                                                        <valor>' . round($valor_iva, 2) . '</valor>
                                                    </impuesto>';
							}
							if ($dmov_iva_porc == "14") {
								$bandera = '3';
								$xml .= '<impuesto>
														<codigo>2</codigo> 
                                                        <codigoPorcentaje>' . $bandera . '</codigoPorcentaje>                                          
                                                        <tarifa>' . round($dmov_iva_porc, 0) . '</tarifa>                                            
                                                        <baseImponible>' . round($sub_coniva, 2) . '</baseImponible>
                                                        <valor>' . round($valor_iva, 2) . '</valor>
													</impuesto>';
							}
							if ($dmov_iva_porc == "15") {
								$bandera = '4';
								$xml .= '<impuesto>
														<codigo>2</codigo> 
                                                        <codigoPorcentaje>' . $bandera . '</codigoPorcentaje>                                          
                                                        <tarifa>' . round($dmov_iva_porc, 0) . '</tarifa>                                            
                                                        <baseImponible>' . round($sub_coniva, 2) . '</baseImponible>
                                                        <valor>' . round($valor_iva, 2) . '</valor>
													</impuesto>';
							}
							if ($dmov_iva_porc == "5") {
								$bandera = '5';
								$xml .= '<impuesto>
														<codigo>2</codigo> 
                                                        <codigoPorcentaje>' . $bandera . '</codigoPorcentaje>                                          
                                                        <tarifa>' . round($dmov_iva_porc, 0) . '</tarifa>                                            
                                                        <baseImponible>' . round($sub_coniva, 2) . '</baseImponible>
                                                        <valor>' . round($valor_iva, 2) . '</valor>
													</impuesto>';
							}
							if ($dmov_iva_porc == "13") {
								$bandera = '10';
								$xml .= '<impuesto>
														<codigo>2</codigo> 
                                                        <codigoPorcentaje>' . $bandera . '</codigoPorcentaje>                                          
                                                        <tarifa>' . round($dmov_iva_porc, 0) . '</tarifa>                                            
                                                        <baseImponible>' . round($sub_coniva, 2) . '</baseImponible>
                                                        <valor>' . round($valor_iva, 2) . '</valor>
													</impuesto>';
							}
							if ($dmov_iva_porc == "8") {
								$bandera = '8';
								$xml .= '<impuesto>
														<codigo>2</codigo> 
                                                        <codigoPorcentaje>' . $bandera . '</codigoPorcentaje>                                          
                                                        <tarifa>' . round($dmov_iva_porc, 0) . '</tarifa>                                            
                                                        <baseImponible>' . round($sub_coniva, 2) . '</baseImponible>
                                                        <valor>' . round($valor_iva, 2) . '</valor>
													</impuesto>';
							}
							$xml .= '</impuestos>
                                        </detalle>';
						} while ($oIfx->SiguienteRegistro());
						$xml .= '</detalles>';
					}
				}
				$oIfx->free();
				//////   si es reembolso 041
				$sql_remb = "select minr_tipo_iden, minr_ide_prov, minr_fpagop_prov, minr_tipo_prov, minr_tipo_docu, 
                             minr_num_esta, minr_pto_emis, minr_sec_docu, minr_fec_emis, minr_num_auto, minr_con_miva, 
                             minr_sin_miva, minr_iva_porc, minr_iva_valo
                             from saeminr 
                             where minr_num_comp = $num_comp
                             and minr_cod_empr = $id_empresa
                             and minr_cod_sucu = $id_sucursal";
				//echo  $sql_remb; exit;
				if ($oIfx->Query($sql_remb)) {
					if ($oIfx->NumFilas() > 0) {
						$xml .= '<reembolsos>';
						do {
							$minr_tipo_iden 	=  $oIfx->f('minr_tipo_iden');
							if ($minr_tipo_iden == '01') {
								$minr_tipo_iden = '04';
							} elseif ($minr_tipo_iden == '02') {
								$minr_tipo_iden = '05';
							} elseif ($minr_tipo_iden == '03') {
								$minr_tipo_iden = '06';
							}

							$minr_ide_prov		=  $oIfx->f('minr_ide_prov');
							$minr_fpagop_prov 	=  $oIfx->f('minr_fpagop_prov');
							$minr_tipo_prov 	=  $oIfx->f('minr_tipo_prov');
							$minr_tipo_docu 	=  $oIfx->f('minr_tipo_docu');
							$minr_num_esta 		=  $oIfx->f('minr_num_esta');
							$minr_pto_emis 		=  $oIfx->f('minr_pto_emis');
							$minr_sec_docu 		=  $oIfx->f('minr_sec_docu');
							$minr_fec_emis 		=  date_create($oIfx->f('minr_fec_emis'));
							$minr_fec_emis 		= date_format($minr_fec_emis, "d/m/Y");
							$minr_num_auto 		=  $oIfx->f('minr_num_auto');
							$minr_con_miva 		=  round(($oIfx->f('minr_con_miva')), 2);
							$minr_sin_miva 		=  round(($oIfx->f('minr_sin_miva')), 2);
							$minr_iva_porc 		=  round($oIfx->f('minr_iva_porc'), 0);
							$minr_iva_valo 		=  $oIfx->f('minr_iva_valo');

						

							if ($minr_iva_porc == 0) $cod_iva = 0;
							if ($minr_iva_porc == 12) $cod_iva = 2;
							if ($minr_iva_porc == 14) $cod_iva = 3;
							if ($minr_iva_porc == 15) $cod_iva = 4;
							if ($minr_iva_porc == 5) $cod_iva = 5;
							if ($minr_iva_porc == 13) $cod_iva = 10;
							if ($minr_iva_porc == 8) $cod_iva = 8;

							$xml .= '<reembolsoDetalle> 
                                    <tipoIdentificacionProveedorReembolso>' . $minr_tipo_iden . '</tipoIdentificacionProveedorReembolso>
                                    <identificacionProveedorReembolso>' . $minr_ide_prov . '</identificacionProveedorReembolso>
                                    <codPaisPagoProveedorReembolso>' . $minr_fpagop_prov . '</codPaisPagoProveedorReembolso > 
                                    <tipoProveedorReembolso>' . $minr_tipo_prov . '</tipoProveedorReembolso> 
                                    <codDocReembolso>' . $minr_tipo_docu . '</codDocReembolso> 
                                    <estabDocReembolso>' . $minr_num_esta . '</estabDocReembolso> 
                                    <ptoEmiDocReembolso>' . $minr_pto_emis . '</ptoEmiDocReembolso>
                                    <secuencialDocReembolso>' . $minr_sec_docu . '</secuencialDocReembolso>
                                    <fechaEmisionDocReembolso>' . $minr_fec_emis . '</fechaEmisionDocReembolso>
                                    <numeroautorizacionDocReemb>' . $minr_num_auto . '</numeroautorizacionDocReemb>';

							/*
							if ($cod_iva == 0) {
								$xml .= '<detalleImpuestos> 
                                        <detalleImpuesto> 
                                            <codigo>2</codigo> 
                                            <codigoPorcentaje>' . $cod_iva . '</codigoPorcentaje>                                   
                                            <tarifa>' . $minr_iva_porc . '</tarifa>                  
                                            <baseImponibleReembolso>' . $minr_sin_miva . '</baseImponibleReembolso> 
                                            <impuestoReembolso>' . round($minr_iva_valo, 2) . '</impuestoReembolso>
                                        </detalleImpuesto> 
                                    </detalleImpuestos> 
                                </reembolsoDetalle>';
							} else if ($cod_iva == 2) {
								$xml .= '<detalleImpuestos> 
                                        <detalleImpuesto> 
                                            <codigo>2</codigo> 
                                            <codigoPorcentaje>' . $cod_iva . '</codigoPorcentaje>                                   
                                            <tarifa>' . $minr_iva_porc . '</tarifa>                  
                                            <baseImponibleReembolso>' . $minr_con_miva . '</baseImponibleReembolso> 
                                            <impuestoReembolso>' . round($minr_iva_valo, 2) . '</impuestoReembolso>
                                        </detalleImpuesto> 
                                    </detalleImpuestos> 
                                </reembolsoDetalle>';
							}
							*/

							// --------------------------------------------------------------------------
							// Validacion 12 porciento del IVA
							// --------------------------------------------------------------------------



							if ($minr_con_miva > 0 || $minr_sin_miva > 0) {
								$xml .= '<detalleImpuestos>';
							}
							if ($minr_con_miva > 0) {

								if(empty($minr_iva_porc)||$minr_iva_porc==''){
									$minr_iva_porc= $empr_iva_porc;
								}
								
							if ($minr_iva_porc == 0) $cod_iva = 0;
							if ($minr_iva_porc == 12) $cod_iva = 2;
							if ($minr_iva_porc == 14) $cod_iva = 3;
							if ($minr_iva_porc == 15) $cod_iva = 4;
							if ($minr_iva_porc == 5) $cod_iva = 5;
							if ($minr_iva_porc == 13) $cod_iva = 10;
							if ($minr_iva_porc == 8) $cod_iva = 8;

								$xml .= '<detalleImpuesto> 
                                            <codigo>2</codigo> 
                                            <codigoPorcentaje>' . $cod_iva . '</codigoPorcentaje>                                   
                                            <tarifa>' . $minr_iva_porc . '</tarifa>                  
                                            <baseImponibleReembolso>' . $minr_con_miva . '</baseImponibleReembolso> 
                                            <impuestoReembolso>' . round($minr_iva_valo, 2) . '</impuestoReembolso>
                                        </detalleImpuesto>';
							}

							if ($minr_sin_miva > 0) {
								$minr_iva_porc = 0;
								$xml .= '<detalleImpuesto> 
                                            <codigo>2</codigo> 
                                            <codigoPorcentaje>' . $minr_iva_porc . '</codigoPorcentaje>                                   
                                            <tarifa>' . $minr_iva_porc . '</tarifa>                  
                                            <baseImponibleReembolso>' . $minr_sin_miva . '</baseImponibleReembolso> 
                                            <impuestoReembolso>' . $minr_iva_porc  . '</impuestoReembolso>
                                        </detalleImpuesto>';
							}

							if ($minr_con_miva > 0 || $minr_sin_miva > 0) {
								$xml .= '</detalleImpuestos>';
							}

							$xml .= '</reembolsoDetalle>';

							// --------------------------------------------------------------------------
							// Fin Validacion 12 porciento del IVA
							// --------------------------------------------------------------------------



						} while ($oIfx->SiguienteRegistro());
						$xml .= '</reembolsos>';
					}
				}

				$aDataInfoAdic['Direccion'] 	= $dir_cliente;
				$aDataInfoAdic['Telefono'] 		= $tlf_cliente;
				$aDataInfoAdic['Email'] 		= $email_clpv;
				$aDataInfoAdic['PreEntrada'] 	= $orden_compra;
				$aDataInfoAdic['AGENTE RETENCION'] = 'NAC-DNCRASC20-00000001';

				if (strlen($fact_cm1_fact) > 0) {
					$fact_cm1_fact = str_replace('<br />', "\n", $fact_cm1_fact);
					$aDataInfoAdic['Observaciones'] = $fact_cm1_fact;
				}

				if (!empty($cod_almacen) || strlen($cod_almacen) > 0) {
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
							$xml .= "<campoAdicional nombre=\"$nom\">$aDataInfoAdic[$nom]</campoAdicional>";
					}
					$xml .= '</infoAdicional>';
				}

				$xml .= '</liquidacionCompra>';

				//	$oReturn->alert($xml);

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
					//$oReturn->alert($serv);

					$archivo = fopen($serv . '/' . $nombre, "w+");

					fwrite($archivo, utf8_encode($xml));
					fclose($archivo);
				}

				$sql_fact = "where minv_cod_empr = $id_empresa and minv_cod_sucu = $id_sucursal   and minv_num_comp = $num_comp";

				unset($_SESSION['id']);
				$_SESSION['id'] = $id_factura;

				$_SESSION['contrRepor'] = $clave_acceso;
				$ret_num_fact = '';
				$asto_cod_ejer = 0;
				$rete_cod_asto = '';


				$oReturn->script("firmar('$nombre','$clave_acceso','$ruc_empr', '$email_clpv', 'liqu_compras', '$sql_fact', '$num_comp',
                                   '$cod_clpv', '$ret_num_fact', '$asto_cod_ejer', '$rete_cod_asto', '$fecha', 1, $id_sucursal)");

				//  echo 'asd';exit;
			} // fin check
		} // fin foreach
	} else {
		$oReturn->alert('Por favor realice una Busqueda...');
	}

	return $oReturn;
}

//POSTGRESQL
function firmar_reteInveV1($aForm = '')
{

	global $DSN_Ifx, $DSN;
	if (session_status() !== PHP_SESSION_ACTIVE) {
		session_start();
	}

	$oReturn = new xajaxResponse();

	$oIfx = new Dbo;
	$oIfx->DSN = $DSN_Ifx;
	$oIfx->Conectar();

	$oIfx1 = new Dbo;
	$oIfx1->DSN = $DSN_Ifx;
	$oIfx1->Conectar();

	$oIfx2 = new Dbo;
	$oIfx2->DSN = $DSN_Ifx;
	$oIfx2->Conectar();

	$oIfxA = new Dbo;
	$oIfxA->DSN = $DSN_Ifx;
	$oIfxA->Conectar();

	//VARIABLES GLOBALES
	$id_empresa = $_SESSION['U_EMPRESA'];
	$array_compra = $_SESSION['U_FACT_ENVIO'];

	unset($_SESSION['aDataGirdErro']);
	unset($array);

	//  LECTURA SUCIA
	//

	$sql = "select empr_ac2_empr, empr_nom_empr, empr_ruc_empr , empr_dir_empr,empr_rimp_sn,
		empr_num_resu, empr_tip_firma, empr_conta_sn
		from saeempr 
		where empr_cod_empr = $id_empresa ";
	if ($oIfx->Query($sql)) {
		if ($oIfx->NumFilas() > 0) {
			$razonSocial = trim($oIfx->f('empr_nom_empr'));
			$ruc_empr = $oIfx->f('empr_ruc_empr');
			$dirMatriz = trim($oIfx->f('empr_dir_empr'));
			$conta_sn = $oIfx->f('empr_conta_sn');
			$empr_num_resu = $oIfx->f('empr_num_resu');
			$empr_tip_firma = $oIfx->f('empr_tip_firma');
			$empr_ac2_empr = trim($oIfx->f('empr_ac2_empr'));
			$empr_rimp_sn = $oIfx->f('empr_rimp_sn');
		}
	}
	$oIfx->Free();

	//direccion sucursales
	$sql = "select sucu_cod_sucu, sucu_dir_sucu from saesucu where sucu_cod_empr=$id_empresa";
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
		$obligadoContabilidad = 'SI';
	} else {
		$obligadoContabilidad = 'NO';
	}

	//OTROS DATOS SRI
	$codDoc = '07';
	$codDocSustento = '01';

	if (count($array_compra) > 0) {
		foreach ($array_compra as $val) {
			$rete_cod_clpv = $val[0];
			$ret_num_ret = $val[8];
			$ret_num_fact = $val[6];
			$asto_cod_ejer = $val[5];
			$minv_num_comp = $val[14];
			$id_sucursal = $val[15];
			$serial = $minv_num_comp . '_rc';
			$check = $aForm[$serial];

			$xml = '';
			$xmlImpu = '';
			$descuento = 0;
			if (!empty($check)) {
				//CONSULTAMOS LOS DATOS Y FORMAMOS LOS XML
				$minv_fmov = $val[4];
				$minv_fmov_tmp = $val[4];
				$ret_num_ret = $val[8];
				$ret_num_fact = $val[6];
				$rete_cod_asto = $val[13];
				$rete_cod_clpv = $val[0];
				$asto_cod_ejer = $val[5];
				$rete_nom_benf = $val[1];
				$rete_dire_benf = $val[2];
				$ret_email_clpv = $val[3];
				$minv_ser_docu = $val[7];
				$ret_ser_ret = $val[9];
				$tipoIdentificacionSujetoRetenido = $val[10];
				$claveAcceso = $val[16];

				//genera clave de acceso
				$ambiente = ambienteEmisionSri($oIfx, $id_empresa, $id_sucursal, 1);
				$tipoEmision = ambienteEmisionSri($oIfx, $id_empresa, $id_sucursal, 2);

				$minv_fmov = date_format(date_create($minv_fmov), "d/m/Y");

				//COMSULTAMOS LOS DATOS Y FORMAMOS LOS XML
				$sqlReteGast = "select rete_ruci_benf,rete_telf_benf,ret_cta_ret,ret_bas_imp,COALESCE(ret_porc_ret,0) as ret_porc_ret,ret_valor from saeret where asto_cod_empr='$id_empresa' and asto_cod_sucu='$id_sucursal' and ret_num_ret='$ret_num_ret' and ret_num_fact='$ret_num_fact' and rete_cod_asto='$rete_cod_asto' and ret_cod_clpv='$rete_cod_clpv' and asto_cod_ejer='$asto_cod_ejer'";
				//echo $sqlReteGast; exit;
				if ($oIfx2->Query($sqlReteGast)) {
					if ($oIfx2->NumFilas() > 0) {
						do {
							$rete_ruci_benf = $oIfx2->f('rete_ruci_benf');
							$rete_telf_benf = $oIfx2->f('rete_telf_benf');
							$ret_cta_ret = $oIfx2->f('ret_cta_ret');
							$ret_bas_imp = $oIfx2->f('ret_bas_imp');
							$ret_porc_ret = $oIfx2->f('ret_porc_ret');
							$ret_valor = $oIfx2->f('ret_valor');

							$numDocSustento = $minv_ser_docu . $ret_num_fact;

							//echo "porcentaje: ".$ret_porc_ret; exit;

							/* if ($ret_cta_ret != '') {
                              if ($ret_cta_ret == '721' || $ret_cta_ret == '723' || $ret_cta_ret == '725') {
                              $codigo = '2';
                              switch ($ret_cta_ret) {
                              case '721': $ret_cta_ret = '1';
                              break;
                              case '723': $ret_cta_ret = '2';
                              break;
                              case '725': $ret_cta_ret = '3';
                              break;
                              }
                              } else
                              $codigo = '1'; */

							$sql = "select tret_imp_ret ,  tret_cod_imp  from saetret where
                                            tret_cod_empr = $id_empresa and
                                            tret_cod      = '$ret_cta_ret' and
                                            tret_porct    = $ret_porc_ret";
							if ($oIfxA->Query($sql)) {
								$ret_cta_ret = $oIfxA->f('tret_cod_imp');
								$codigo = $oIfxA->f('tret_imp_ret');
							}
							//$oReturn->alert($sql);

							$xmlImpu .= '<impuesto>';
							$xmlImpu .= "<codigo>$codigo</codigo>
                                                        <codigoRetencion>$ret_cta_ret</codigoRetencion>
                                                        <baseImponible>" . round($ret_bas_imp, 2) . "</baseImponible>
                                                        <porcentajeRetener>$ret_porc_ret</porcentajeRetener>
                                                        <valorRetenido>" . round($ret_valor, 2) . "</valorRetenido>
                                                        <codDocSustento>$codDocSustento</codDocSustento>
                                                        <numDocSustento>" . trim($numDocSustento) . "</numDocSustento>
                                                        <fechaEmisionDocSustento>$minv_fmov</fechaEmisionDocSustento>";
							$xmlImpu .= '</impuesto>';
							// }
						} while ($oIfx2->SiguienteRegistro());
					}
				}
				$oIfx2->Free();

				$estab = substr($ret_ser_ret, 0, 3);
				$ptoEmi = substr($ret_ser_ret, 3, 6);

				$periodoFiscal = substr($minv_fmov, 3, 7);

				$rimpe = "";
				if ($empr_rimp_sn == "S") {
					$rimpe = "<contribuyenteRimpe>CONTRIBUYENTE RÉGIMEN RIMPE</contribuyenteRimpe>";
				}

				$xml .= '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>';
				$xml .= '<comprobanteRetencion id="comprobante" version="1.0.0">';
				$xml .= '<infoTributaria>';
				$xml .= "<ambiente>$ambiente</ambiente>
                                    <tipoEmision>$tipoEmision</tipoEmision>
                                    <razonSocial>" . htmlspecialchars($razonSocial) . "</razonSocial>
                                    <nombreComercial>" . htmlspecialchars($razonSocial) . "</nombreComercial>
                                    <ruc>$ruc_empr</ruc>
                                    <claveAcceso>$claveAcceso</claveAcceso>
                                    <codDoc>$codDoc</codDoc>
                                    <estab>$estab</estab>
                                    <ptoEmi>$ptoEmi</ptoEmi>
                                    <secuencial>$ret_num_ret</secuencial>
                                    <dirMatriz>$dirMatriz</dirMatriz>
									" . $rimpe . "";
				if (!empty($empr_ac2_empr)) {
					$aret = explode('-', $empr_ac2_empr);

					$numret = intval($aret[2]);

					$xml .= '<agenteRetencion>' . $numret . '</agenteRetencion>';
				}
				$xml .= '</infoTributaria>';

				$xml .= '<infoCompRetencion>';
				$xml .= "<fechaEmision>$minv_fmov</fechaEmision>
						<dirEstablecimiento>" . $arrayDireSucu[$id_sucursal] . "</dirEstablecimiento>";
				if ($empr_num_resu != '')
					$xml .= '<contribuyenteEspecial>' . $empr_num_resu . '</contribuyenteEspecial>';

				$xml .= "<obligadoContabilidad>$obligadoContabilidad</obligadoContabilidad>
                                    <tipoIdentificacionSujetoRetenido>$tipoIdentificacionSujetoRetenido</tipoIdentificacionSujetoRetenido>
                                    <razonSocialSujetoRetenido>" . htmlspecialchars($rete_nom_benf) . "</razonSocialSujetoRetenido>
                                    <identificacionSujetoRetenido>$rete_ruci_benf</identificacionSujetoRetenido>
                                    <periodoFiscal>$periodoFiscal</periodoFiscal>";
				$xml .= '</infoCompRetencion>';

				$xml .= '<impuestos>';
				$xml .= $xmlImpu;
				$xml .= '</impuestos>';

				$aDataInfoAdic['Email'] = $ret_email_clpv;
				$aDataInfoAdic['Telefono'] = $rete_telf_benf;
				$aDataInfoAdic['Direccion'] = $rete_dire_benf;

				$etiqueta = array_keys($aDataInfoAdic);
				if (count($etiqueta) > 0) {
					$xml .= '<infoAdicional>';
					foreach ($etiqueta as $nom) {
						if ($aDataInfoAdic[$nom] != '')
							$xml .= "<campoAdicional nombre=\"$nom\">$aDataInfoAdic[$nom]</campoAdicional>";
					}
					$xml .= '</infoAdicional>';
				}

				$xml .= '</comprobanteRetencion>';

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

					$nombre = $claveAcceso . ".xml";
					$archivo = fopen($ruta_gene . '/' . $nombre, "w+");

					//fwrite($archivo, $xml);
					fwrite($archivo, utf8_encode($xml));
					fclose($archivo);
				} elseif ($empr_tip_firma == 'M') {

					//MultiFirma
					$nombre = $claveAcceso . ".xml";
					$serv = '';
					$archivo = '';
					$serv = DIR_FACTELEC . "modulos/sri_offline/documentoselectronicos/generados";
					$archivo = fopen($serv . '/' . $nombre, "w+");

					//fwrite($archivo, $xml);
					fwrite($archivo, utf8_encode($xml));
					fclose($archivo);
				}

				$sql_gasto = '';
				$id = 0;

				$oReturn->script("firmar('$nombre','$claveAcceso','$ruc_empr', '$ret_email_clpv', 'retencion_inventario', 
                                            '$sql_gasto', $id,  $rete_cod_clpv,  '$ret_num_fact', '$asto_cod_ejer', 
                                            '$rete_cod_asto', '$minv_fmov_tmp' , 6, $id_sucursal)");
			} // fin check
		} // fin foreach
	} else {
		$oReturn->alert('Por favor realice una Busqueda...');
	}

	return $oReturn;
}

function firmar_reteInve($aForm = '')
{

	global $DSN_Ifx, $DSN;
	if (session_status() !== PHP_SESSION_ACTIVE) {
		session_start();
	}

	$oReturn = new xajaxResponse();

	$oIfx = new Dbo;
	$oIfx->DSN = $DSN_Ifx;
	$oIfx->Conectar();

	$oIfx1 = new Dbo;
	$oIfx1->DSN = $DSN_Ifx;
	$oIfx1->Conectar();

	$oIfx2 = new Dbo;
	$oIfx2->DSN = $DSN_Ifx;
	$oIfx2->Conectar();

	$oIfxA = new Dbo;
	$oIfxA->DSN = $DSN_Ifx;
	$oIfxA->Conectar();

	//VARIABLES GLOBALES
	$id_empresa = $_SESSION['U_EMPRESA'];
	$array_compra = $_SESSION['U_FACT_ENVIO'];

	unset($_SESSION['aDataGirdErro']);
	unset($array);

	//  LECTURA SUCIA
	//

	$sql = "select empr_ac2_empr, empr_nom_empr, empr_ruc_empr , empr_dir_empr,
		empr_num_resu, empr_tip_firma, empr_conta_sn, empr_rimp_sn
		from saeempr 
		where empr_cod_empr = $id_empresa ";
	if ($oIfx->Query($sql)) {
		if ($oIfx->NumFilas() > 0) {
			$razonSocial = trim($oIfx->f('empr_nom_empr'));
			$ruc_empr = $oIfx->f('empr_ruc_empr');
			$dirMatriz = trim($oIfx->f('empr_dir_empr'));
			$conta_sn = $oIfx->f('empr_conta_sn');
			$empr_num_resu = $oIfx->f('empr_num_resu');
			$empr_tip_firma = $oIfx->f('empr_tip_firma');
			$empr_ac2_empr = trim($oIfx->f('empr_ac2_empr'));
			$empr_rimp_sn = $oIfx->f('empr_rimp_sn');
		}
	}
	$oIfx->Free();

	//direccion sucursales
	$sql = "select sucu_cod_sucu, sucu_dir_sucu from saesucu where sucu_cod_empr=$id_empresa";
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
		$obligadoContabilidad = 'SI';
	} else {
		$obligadoContabilidad = 'NO';
	}

	//OTROS DATOS SRI
	$codDoc = '07';
	$codDocSustento = '01';

	if (count($array_compra) > 0) {
		foreach ($array_compra as $val) {
			$rete_cod_clpv = $val[0];
			$ret_num_ret = $val[8];
			$ret_num_fact = $val[6];
			$asto_cod_ejer = $val[5];
			$minv_num_comp = $val[14];
			$id_sucursal = $val[15];
			$serial = $minv_num_comp . '_rc';
			$check = $aForm[$serial];

			$xml = '';
			$xmlImpu = '';
			$descuento = 0;
			if (!empty($check)) {
				//CONSULTAMOS LOS DATOS Y FORMAMOS LOS XML
				$minv_fmov = $val[4];
				$minv_fmov_tmp = $val[4];
				$ret_num_ret = $val[8];
				$ret_num_fact = $val[6];
				$rete_cod_asto = $val[13];
				$rete_cod_clpv = $val[0];
				$asto_cod_ejer = $val[5];
				$rete_nom_benf = $val[1];
				$rete_dire_benf = $val[2];
				$ret_email_clpv = $val[3];
				$minv_ser_docu = $val[7];
				$ret_ser_ret = $val[9];
				$tipoIdentificacionSujetoRetenido = $val[10];
				$claveAcceso = $val[16];
				$fecha_retencion = $val[17];
				if(empty($fecha_retencion)) $fecha_retencion=$minv_fmov;

				//genera clave de acceso
				$ambiente = ambienteEmisionSri($oIfx, $id_empresa, $id_sucursal, 1);
				$tipoEmision = ambienteEmisionSri($oIfx, $id_empresa, $id_sucursal, 2);

				$minv_fmov = date_format(date_create($minv_fmov), "d/m/Y");
				$fecha_retencion = date_format(date_create($fecha_retencion), "d/m/Y");

				//COMSULTAMOS LOS DATOS Y FORMAMOS LOS XML
				$sqlReteGast = "select rete_ruci_benf,rete_telf_benf,ret_cta_ret,ret_bas_imp,COALESCE(ret_porc_ret,0) as ret_porc_ret,ret_valor from saeret where asto_cod_empr='$id_empresa' and asto_cod_sucu='$id_sucursal' and ret_num_ret='$ret_num_ret' and ret_num_fact='$ret_num_fact' and rete_cod_asto='$rete_cod_asto' and ret_cod_clpv='$rete_cod_clpv' and asto_cod_ejer='$asto_cod_ejer'";
				//echo $sqlReteGast; exit;
				$valorPago = 0;
				$xmlimpdocdocsustento = "";
				$xmldocsustentotsi = "";
				$totalSinImpuestos = 0;
				$importeTotal = 0;
				if ($oIfx2->Query($sqlReteGast)) {
					if ($oIfx2->NumFilas() > 0) {
						do {
							$rete_ruci_benf = $oIfx2->f('rete_ruci_benf');
							$rete_telf_benf = $oIfx2->f('rete_telf_benf');
							$ret_cta_ret = $oIfx2->f('ret_cta_ret');
							$ret_bas_imp = $oIfx2->f('ret_bas_imp');
							$ret_porc_ret = $oIfx2->f('ret_porc_ret');
							$ret_valor = $oIfx2->f('ret_valor');

							$fechaEmisionDocSustento = $minv_fmov;

							$numDocSustento = $minv_ser_docu . $ret_num_fact;

							$sqlClpv = "select clpv_cod_tprov from saeclpv where clpv_cod_clpv = $rete_cod_clpv";
							$clpv_cod_tprov = consulta_string_func($sqlClpv, 'clpv_cod_tprov', $oIfx1, '');

							$sqlClpv = "select clpv_par_rela from saeclpv where clpv_cod_clpv = $rete_cod_clpv";
							$clpv_par_rela = consulta_string_func($sqlClpv, 'clpv_cod_tprov', $oIfx1, '');

							if (empty($rete_ruci_benf)) {
								$sqlClpv = "select clpv_ruc_clpv from saeclpv where clpv_cod_clpv = $rete_cod_clpv";
								$rete_ruci_benf = consulta_string_func($sqlClpv, 'clpv_ruc_clpv', $oIfx1, '');
							}

							//echo "porcentaje: ".$ret_porc_ret; exit;

							/* if ($ret_cta_ret != '') {
                              if ($ret_cta_ret == '721' || $ret_cta_ret == '723' || $ret_cta_ret == '725') {
                              $codigo = '2';
                              switch ($ret_cta_ret) {
                              case '721': $ret_cta_ret = '1';
                              break;
                              case '723': $ret_cta_ret = '2';
                              break;
                              case '725': $ret_cta_ret = '3';
                              break;
                              }
                              } else
                              $codigo = '1'; */

							$codigo_impuesto = $ret_cta_ret;
							$sql = "select tret_imp_ret ,  tret_cod_imp  from saetret where
                                            tret_cod_empr = $id_empresa and
                                            tret_cod      = '$ret_cta_ret' and
                                            tret_porct    = $ret_porc_ret";
							if ($oIfxA->Query($sql)) {
								$ret_cta_ret = $oIfxA->f('tret_cod_imp');
								$codigo = $oIfxA->f('tret_imp_ret');
							}
							//$oReturn->alert($sql);

							/*$xmlImpu .= '<impuesto>';
                            $xmlImpu .= "<codigo>$codigo</codigo>
                                                        <codigoRetencion>$ret_cta_ret</codigoRetencion>
                                                        <baseImponible>" . round($ret_bas_imp, 2) . "</baseImponible>
                                                        <porcentajeRetener>$ret_porc_ret</porcentajeRetener>
                                                        <valorRetenido>" . round($ret_valor, 2) . "</valorRetenido>
                                                        <codDocSustento>$codDocSustento</codDocSustento>
                                                        <numDocSustento>" . trim($numDocSustento) . "</numDocSustento>
                                                        <fechaEmisionDocSustento>$minv_fmov</fechaEmisionDocSustento>";
                            $xmlImpu .= '</impuesto>';*/

							$xmlImpu .= '<retencion>';
							$xmlImpu .= "<codigo>$codigo</codigo>
                                                        <codigoRetencion>$ret_cta_ret</codigoRetencion>
                                                        <baseImponible>" . round($ret_bas_imp, 2) . "</baseImponible>
                                                        <porcentajeRetener>$ret_porc_ret</porcentajeRetener>
                                                        <valorRetenido>" . round($ret_valor, 2) . "</valorRetenido>";
							$xmlImpu .= '</retencion>';
							if ($codigo == 1) $valorPago = $valorPago + $ret_valor;
							// }



							if (substr($codigo_impuesto, 0, 1) == '7') {
								$totalSinImpuestos = $totalSinImpuestos + $ret_bas_imp;
								$importeTotal = $importeTotal + $ret_valor;

								$xmldocsustentotsi = "
					<totalSinImpuestos>" . round($totalSinImpuestos, 2) . "</totalSinImpuestos>";
								$xmldocsustentotsi .= "
					<importeTotal>" . round($totalSinImpuestos, 2) . "</importeTotal>";

								$cod_iva = 0;
								$xmlimpdocdocsustento .= "
								<impuestoDocSustento>
								<codImpuestoDocSustento>2</codImpuestoDocSustento>
								<codigoPorcentaje>" . $cod_iva . "</codigoPorcentaje> 
								<baseImponible>" . round($ret_bas_imp, 2) . "</baseImponible>	
								<tarifa>0</tarifa>
								<valorImpuesto>0</valorImpuesto>
								</impuestoDocSustento>
									";
							} else {
								$totalSinImpuestos = $totalSinImpuestos + $ret_bas_imp;
								$importeTotal = $importeTotal + $ret_valor;

								$xmldocsustentotsi = "
					<totalSinImpuestos>" . round($totalSinImpuestos, 2) . "</totalSinImpuestos>";
								$xmldocsustentotsi .= "
					<importeTotal>" . round($totalSinImpuestos, 2) . "</importeTotal>";


								$xmlimpdocdocsustento .= "
								<impuestoDocSustento>
								<codImpuestoDocSustento>2</codImpuestoDocSustento>
								<codigoPorcentaje>0</codigoPorcentaje> 
								<baseImponible>0</baseImponible>	
								<tarifa>0</tarifa>
								<valorImpuesto>0</valorImpuesto>	
								</impuestoDocSustento>
									";
							}
						} while ($oIfx2->SiguienteRegistro());
					}
				}
				$oIfx2->Free();

				$estab = substr($ret_ser_ret, 0, 3);
				$ptoEmi = substr($ret_ser_ret, 3, 6);

				$periodoFiscal = substr($fecha_retencion, 3, 7);


				// Verificar rimpe
				$rimpe = "";
				if ($empr_rimp_sn == "S") {
					$rimpe = "<contribuyenteRimpe>CONTRIBUYENTE RÉGIMEN RIMPE</contribuyenteRimpe>";
				}


				$xml .= '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>';
				$xml .= '<comprobanteRetencion id="comprobante" version="2.0.0">';
				$xml .= '<infoTributaria>';
				$xml .= "<ambiente>$ambiente</ambiente>
                                    <tipoEmision>$tipoEmision</tipoEmision>
                                    <razonSocial>" . htmlspecialchars($razonSocial) . "</razonSocial>
                                    <nombreComercial>" . htmlspecialchars($razonSocial) . "</nombreComercial>
                                    <ruc>$ruc_empr</ruc>
                                    <claveAcceso>$claveAcceso</claveAcceso>
                                    <codDoc>$codDoc</codDoc>
                                    <estab>$estab</estab>
                                    <ptoEmi>$ptoEmi</ptoEmi>
                                    <secuencial>$ret_num_ret</secuencial>
                                    <dirMatriz>$dirMatriz</dirMatriz>";
				if (!empty($empr_ac2_empr)) {
					$aret = explode('-', $empr_ac2_empr);

					$numret = intval($aret[2]);

					$xml .= '<agenteRetencion>' . $numret . '</agenteRetencion>';
				}

				$xml .= $rimpe;
				$xml .= '</infoTributaria>';

				$xml .= '<infoCompRetencion>';
				$xml .= "<fechaEmision>$fecha_retencion</fechaEmision>
						<dirEstablecimiento>" . $arrayDireSucu[$id_sucursal] . "</dirEstablecimiento>";
				if ($empr_num_resu != '')
					$xml .= '<contribuyenteEspecial>' . $empr_num_resu . '</contribuyenteEspecial>';

				$xml .= "<obligadoContabilidad>$obligadoContabilidad</obligadoContabilidad>
				<tipoIdentificacionSujetoRetenido>$tipoIdentificacionSujetoRetenido</tipoIdentificacionSujetoRetenido>";
				if ($obligadoContabilidad == "08") {
					$xml .= "<tipoSujetoRetenido>$clpv_cod_tprov</tipoSujetoRetenido>";
				}
				if ($clpv_par_rela == 'S') {
					$clpv_par_rela = 'SI';
				} else {
					$clpv_par_rela = 'NO';
				}
				$xml .= "
							<parteRel>$clpv_par_rela</parteRel>";
				$xml .= "<razonSocialSujetoRetenido>" . utf8_encode($rete_nom_benf) . "</razonSocialSujetoRetenido>";
				$xml .= "
							<identificacionSujetoRetenido>$rete_ruci_benf</identificacionSujetoRetenido>
							<periodoFiscal>$periodoFiscal</periodoFiscal>";
				$xml .= '</infoCompRetencion>';

				//DOCUMENTO SUSTENTO
				$sql_suste = "select minv_cod_tpago, minv_cod_tran from saeminv where minv_cod_empr='$id_empresa' 
				and minv_cod_sucu='$id_sucursal' and minv_fac_prov='$ret_num_fact' and minv_comp_cont='$rete_cod_asto' 
				and minv_cod_clpv='$rete_cod_clpv' ";
				$cod_suste = consulta_string_func($sql_suste, 'minv_cod_tpago', $oIfx1, '');
				$cod_tran = consulta_string_func($sql_suste, 'minv_cod_tran', $oIfx1, '');

				if(!empty($cod_tran)){
					$sql_suste = "select defi_tip_comp from saedefi where defi_cod_empr='$id_empresa' 
					and defi_cod_tran='$cod_tran'";

					$codDocSustento = consulta_string_func($sql_suste, 'defi_tip_comp', $oIfx1, '01');
				}
				else{
					$codDocSustento = '01';
				}


				$xmldocsustento = '';
				$xmldocsustento .= "<docsSustento>
				<docSustento>
				<codSustento>" . $cod_suste . "</codSustento>
				<codDocSustento>" . $codDocSustento . "</codDocSustento>
				<numDocSustento>" . trim($numDocSustento) . "</numDocSustento>
				<fechaEmisionDocSustento>" . $fechaEmisionDocSustento . "</fechaEmisionDocSustento>";


				$pagoLocExt = "01";
				$xmldocsustento .= "
					<pagoLocExt>01</pagoLocExt>";
				if ($pagoLocExt == "02") {
					$tipoRegi = "01";
					$xmldocsustento .= "<paisEfecPago>593<paisEfecPago>";
					$aplicConvDobTrib = "NO";
					$xmldocsustento .= "<aplicConvDobTrib>$aplicConvDobTrib</aplicConvDobTrib>";
					if ($aplicConvDobTrib == "NO") {
						$xmldocsustento .= "<pagExtSujRetNorLeg>NO</pagExtSujRetNorLeg>";
					}
					$aplicConvDobTrib = "SI";
					$xmldocsustento .= "<pagoRegFis>$aplicConvDobTrib</pagoRegFis>";
				}


				if ($xmldocsustentotsi != '') {
					$xmldocsustento .= $xmldocsustentotsi;
				}


				//// si es reembolso 041
				if ($codDocSustento == "41") {
					$sql = "select count(*) as numero_registros, 
									sum(minr_con_miva) + sum(minr_sin_miva)   as suma_base, 
									sum(minr_iva_valo ) as suma_iva, 
									sum(minr_con_miva) + sum(minr_iva_valo)+ sum(minr_sin_miva) as total_fact
								 from saeminr 
								 where minr_num_comp = $ret_num_fact
								 and minr_cod_empr = $id_empresa
								 and minr_cod_sucu = $id_sucursal";
					$numero_registros = consulta_string($sql, 'numero_registros', $oIfx, 0);
					$suma_base = consulta_string($sql, 'suma_base', $oIfx, 0);
					$suma_comp = consulta_string($sql, 'total_fact', $oIfx, 0);
					$suma_iva = consulta_string($sql, 'suma_iva', $oIfx, 0);

					if ($numero_registros > 0) {
						$total_base_imponible = $suma_base;
						$tatal_comprobantes = $suma_comp;
						$total_iva = $suma_iva;
						$xmldocsustento .= '<totalComprobantesReembolso>' . round($tatal_comprobantes, 2) . '</totalComprobantesReembolso>
									<totalBaseImponibleReembolso>' . round($total_base_imponible, 2) . '</totalBaseImponibleReembolso> 
									<totalImpuestoReembolso>' . round($total_iva, 2) . '</totalImpuestoReembolso>';
					} else {

						$sql_remb = "select fprr_cod_fprr,fprr_ruc_prov,fprr_tip_iden,fprr_seri_fprv,
                                    fprr_num_seri,fprr_num_esta,fprr_fec_emis,fprr_num_auto, 
                                    fprr_val_vivb,fprr_val_pivb,fprr_val_grab, fprr_val_vivs,fprr_val_pivs,
                                    fprr_val_gras,fprr_val_gra0,fprr_val_grs0,trans_tip_comp,fprr_val_totl
                                    from saefprr, saetran
                                    where fprr_fac_fprv like '%$ret_num_fact%'
                                    and fprr_cod_empr = $id_empresa
                                    and fprr_cod_sucu = $id_sucursal
                                    and tran_cod_modu = 4
                                    and tran_cod_tran = fprr_cod_tran 
                                    group by 1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18
                                    order by fprr_cod_fprr";
						//echo  $sql_remb; exit;
						$xmReembolsos = '';
						$total_comprobantes_reembolso = 0;
						$total_base_imponible_reembolso = 0;
						$total_impuesto_reembolso = 0;
						if ($oIfx->Query($sql_remb)) {
							if ($oIfx->NumFilas() > 0) {
								do {

									$fprr_val_vivb 	=  $oIfx->f('fprr_val_vivb');
									$fprr_val_grab 	=  $oIfx->f('fprr_val_grab');
									$fprr_val_vivs 	=  $oIfx->f('fprr_val_vivs');
									$fprr_val_gras 	=  $oIfx->f('fprr_val_gras');
									$fprr_val_gra0 =  $oIfx->f('fprr_val_gra0');
									$fprr_val_grs0 =  $oIfx->f('fprr_val_grs0');
									$fprr_val_totl =  $oIfx->f('fprr_val_totl');

									//SERVICIOS+BIENES
									$total_comprobantes_reembolso = $total_comprobantes_reembolso + $fprr_val_totl;
									$total_base_imponible_reembolso = $total_base_imponible_reembolso + $fprr_val_gras + $fprr_val_grs0 + $fprr_val_grab + $fprr_val_gra0;
									$total_impuesto_reembolso = $total_impuesto_reembolso + $fprr_val_vivs + $fprr_val_vivb;
								} while ($oIfx->SiguienteRegistro());
								$xmldocsustento .= '<totalComprobantesReembolso>' . round($total_comprobantes_reembolso, 2) . '</totalComprobantesReembolso>
									<totalBaseImponibleReembolso>' . round($total_base_imponible_reembolso, 2) . '</totalBaseImponibleReembolso> 
									<totalImpuestoReembolso>' . round($total_impuesto_reembolso, 2) . '</totalImpuestoReembolso>';
							}
						}
					}
				}



				if ($xmlimpdocdocsustento != "") {
					$xmldocsustento .= "
					<impuestosDocSustento>
					" . $xmlimpdocdocsustento . "
					</impuestosDocSustento>";
				}

				if ($xmlImpu != "") {
					$xmldocsustento .= '
					<retenciones>';
					$xmldocsustento .= $xmlImpu;
					$xmldocsustento .= '</retenciones>';
				}



				//COMSULTAMOS LOS DATOS Y FORMAMOS LOS XML
				$sqlReteGast = "select rete_ruci_benf, rete_telf_benf,  ret_cta_ret,   
                                    ret_bas_imp, ret_porc_ret, ret_valor,ret_fpag_divi ,ret_anio_divi,ret_ir_divi
                                    from saeret where 
                                    asto_cod_empr = $id_empresa and 
                                    asto_cod_sucu = $id_sucursal and 
                                    ret_num_ret   = '$ret_num_ret' and 
                                    ret_num_fact  = '$ret_num_fact' and 
                                    rete_cod_asto = '$rete_cod_asto'  and 
                                    ret_cod_clpv  = $rete_cod_clpv and 
                                    asto_cod_ejer = $asto_cod_ejer ";
				if ($oIfx->Query($sqlReteGast)) {
					if ($oIfx->NumFilas() > 0) {
						do {
							$rete_ruci_benf 	= $oIfx->f('rete_ruci_benf');
							$rete_telf_benf 	= $oIfx->f('rete_telf_benf');
							$ret_cta_ret 		= $oIfx->f('ret_cta_ret');
							$ret_bas_imp 		= $oIfx->f('ret_bas_imp');
							$ret_porc_ret 		= $oIfx->f('ret_porc_ret');
							$ret_valor 			= $oIfx->f('ret_valor');
							$ret_fpag_divi  	= $oIfxA->f('ret_fpag_divi');
							$ret_anio_divi  	= $oIfxA->f('ret_anio_divi');
							$ret_ir_divi    	= $oIfxA->f('ret_ir_divi');

							$fecha_pago_dividendo    = $fechaEmision = cambioFecha($ret_fpag_divi, 'mm/dd/aaaa', 'dd/mm/aaaa');


							if (empty($rete_ruci_benf)) {
								$sqlClpv = "select clpv_ruc_clpv from saeclpv where clpv_cod_clpv = $rete_cod_clpv";
								$rete_ruci_benf = consulta_string_func($sqlClpv, 'clpv_ruc_clpv', $oIfx1, '');
							}
							$sql = "select tret_imp_ret ,  tret_cod_imp from saetret where
                                            tret_cod_empr = $id_empresa and
                                            tret_cod      = '$ret_cta_ret' and
                                            tret_porct    = $ret_porc_ret ";
							if ($oIfxA->Query($sql)) {
								$ret_cta_ret  	= $oIfxA->f('tret_cod_imp');
								$codigo       	= $oIfxA->f('tret_imp_ret');
							}
							//si es 327 t
							$xmlDividendo = '';


							if ($ret_cta_ret == '327') {
								$xmlDividendo .= '<dividendos>';
								$xmlDividendo .= "<fechaPagoDiv>" . $fecha_pago_dividendo . "</fechaPagoDiv>";
								$xmlDividendo .= "<imRentaSoc>" . $ret_ir_divi . "</imRentaSoc>";
								$xmlDividendo .= "<ejerFisUtDiv>" . $ret_anio_divi . "</ejerFisUtDiv >";
								$xmlDividendo .= '</dividendos>';
							}
							$xmldocsustento .= $xmlDividendo;
						} while ($oIfx->SiguienteRegistro());
					}
				}
				$oIfx->Free();
				$xml .= $xmldocsustento;

				//////   si es reembolso 041
				$sql_remb = "select minr_tipo_iden, minr_ide_prov, minr_fpagop_prov, minr_tipo_prov, minr_tipo_docu, 
                             minr_num_esta, minr_pto_emis, minr_sec_docu, minr_fec_emis, minr_num_auto, minr_con_miva, 
                             minr_sin_miva, minr_iva_porc, minr_iva_valo
                             from saeminr 
                             where minr_num_comp = $ret_num_fact
                             and minr_cod_empr = $id_empresa
                             and minr_cod_sucu = $id_sucursal";
				//echo  $sql_remb; exit;
				if ($oIfx->Query($sql_remb)) {
					if ($oIfx->NumFilas() > 0) {
						$xmReembolsos .= '<reembolsos>';
						do {
							$minr_tipo_iden 	=  $oIfx->f('minr_tipo_iden');
							$minr_ide_prov		=  $oIfx->f('minr_ide_prov');
							$minr_fpagop_prov 	=  $oIfx->f('minr_fpagop_prov');
							$minr_tipo_prov 	=  $oIfx->f('minr_tipo_prov');
							$minr_tipo_docu 	=  $oIfx->f('minr_tipo_docu');
							$minr_num_esta 		=  $oIfx->f('minr_num_esta');
							$minr_pto_emis 		=  $oIfx->f('minr_pto_emis');
							$minr_sec_docu 		=  $oIfx->f('minr_sec_docu');
							$minr_fec_emis 		=  fecha_mysql_func($oIfx->f('minr_fec_emis'));
							$minr_num_auto 		=  $oIfx->f('minr_num_auto');
							$minr_con_miva 		=  round(($oIfx->f('minr_con_miva')), 2);
							$minr_sin_miva 		=  round(($oIfx->f('minr_sin_miva')), 2);
							$minr_iva_porc 		=  round($oIfx->f('minr_iva_porc'), 0);
							$minr_iva_valo 		=  $oIfx->f('minr_iva_valo');
							if ($minr_iva_porc == 0) $cod_iva = 0;
							if ($minr_iva_porc == 12) $cod_iva = 2;
							if ($minr_iva_porc == 14) $cod_iva = 3;
							if ($minr_iva_porc == 15) $cod_iva = 4;
							if ($minr_iva_porc == 5) $cod_iva = 5;
							if ($minr_iva_porc == 13) $cod_iva = 10;
							if ($minr_iva_porc == 8) $cod_iva = 8;

							$xmReembolsos .= '<reembolsoDetalle> 
                                    <tipoIdentificacionProveedorReembolso>' . $minr_tipo_iden . '</tipoIdentificacionProveedorReembolso>
                                    <identificacionProveedorReembolso>' . $minr_ide_prov . '</identificacionProveedorReembolso>
                                    <codPaisPagoProveedorReembolso>' . $minr_fpagop_prov . '</codPaisPagoProveedorReembolso > 
                                    <tipoProveedorReembolso>' . $minr_tipo_prov . '</tipoProveedorReembolso> 
                                    <codDocReembolso>' . $minr_tipo_docu . '</codDocReembolso> 
                                    <estabDocReembolso>' . $minr_num_esta . '</estabDocReembolso> 
                                    <ptoEmiDocReembolso>' . $minr_pto_emis . '</ptoEmiDocReembolso>
                                    <secuencialDocReembolso>' . $minr_sec_docu . '</secuencialDocReembolso>
                                    <fechaEmisionDocReembolso>' . $minr_fec_emis . '</fechaEmisionDocReembolso>
                                    <numeroAutorizacionDocReemb>' . $minr_num_auto . '</numeroAutorizacionDocReemb>';

							if ($cod_iva == 0) {
								$xmReembolsos .= '<detalleImpuestos> 
                                        <detalleImpuesto> 
                                            <codigo>2</codigo> 
                                            <codigoPorcentaje>' . $cod_iva . '</codigoPorcentaje>                                   
                                            <tarifa>' . $minr_iva_porc . '</tarifa>                  
                                            <baseImponibleReembolso>' . $minr_sin_miva . '</baseImponibleReembolso> 
                                            <impuestoReembolso>' . round($minr_iva_valo, 2) . '</impuestoReembolso>
                                        </detalleImpuesto> 
                                    </detalleImpuestos> 
                                </reembolsoDetalle>';
							} else if ($cod_iva == 2||$cod_iva == 3||$cod_iva == 4||$cod_iva == 5||$cod_iva == 10||$cod_iva == 8) {
								$xmReembolsos .= '<detalleImpuestos> 
                                        <detalleImpuesto> 
                                            <codigo>2</codigo> 
                                            <codigoPorcentaje>' . $cod_iva . '</codigoPorcentaje>                                   
                                            <tarifa>' . $minr_iva_porc . '</tarifa>                  
                                            <baseImponibleReembolso>' . $minr_con_miva . '</baseImponibleReembolso> 
                                            <impuestoReembolso>' . round($minr_iva_valo, 2) . '</impuestoReembolso>
                                        </detalleImpuesto> 
                                    </detalleImpuestos> 
                                </reembolsoDetalle>';
							}
						} while ($oIfx->SiguienteRegistro());
						$xmReembolsos .= '</reembolsos>';
					}
				}

				if ($codDocSustento == "41") {
					$sql_remb = "select fprr_cod_fprr,fprr_ruc_prov,fprr_tip_iden,fprr_seri_fprv,
                                    fprr_num_seri,fprr_num_esta,fprr_fec_emis,fprr_num_auto, 
                                    fprr_val_vivb,fprr_val_pivb,fprr_val_grab, fprr_val_vivs,fprr_val_pivs,
                                    fprr_val_gras,fprr_val_gra0,fprr_val_grs0,trans_tip_comp
                                    from saefprr, saetran
                                    where fprr_fac_fprv like '%$ret_num_fact%'
                                    and fprr_cod_empr = $id_empresa
                                    and fprr_cod_sucu = $id_sucursal
                                    and tran_cod_modu = 4
                                    and tran_cod_tran = fprr_cod_tran 
                                    order by fprr_cod_fprr";
					//echo  $sql_remb; exit;
					$xmReembolsos = '';

					if ($oIfx->Query($sql_remb)) {
						if ($oIfx->NumFilas() > 0) {
							do {

								$fprr_cod_fprr 	=  $oIfx->f('fprr_cod_fprr');
								$fprr_ruc_prov 	=  $oIfx->f('fprr_ruc_prov');
								$fprr_tip_iden 	=  $oIfx->f('fprr_tip_iden');
								$fprr_num_seri 	=  $oIfx->f('fprr_num_seri');
								$fprr_num_esta 	=  $oIfx->f('fprr_num_esta');
								$fprr_fec_emis 	=  $oIfx->f('fprr_fec_emis');
								$fprr_num_auto 	=  $oIfx->f('fprr_num_auto');
								$fprr_val_vivb 	=  $oIfx->f('fprr_val_vivb');
								$fprr_val_pivb 	=  $oIfx->f('fprr_val_pivb');
								$fprr_val_grab 	=  $oIfx->f('fprr_val_grab');
								$fprr_val_vivs 	=  $oIfx->f('fprr_val_vivs');
								$fprr_val_pivs 	=  $oIfx->f('fprr_val_pivs');
								$fprr_val_gras 	=  $oIfx->f('fprr_val_gras');
								$trans_tip_comp =  $oIfx->f('trans_tip_comp');
								$fprr_val_gra0 =  $oIfx->f('fprr_val_gra0');
								$fprr_val_grs0 =  $oIfx->f('fprr_val_grs0');
								$ll_numeros = strlen($fprr_cod_fprr);

								if ($fprr_tip_iden == '01') {
									$fprr_tip_iden = '04';
								} elseif ($fprr_tip_iden == '02') {
									$fprr_tip_iden = '05';
								} elseif ($fprr_tip_iden == '03') {
									$fprr_tip_iden = '06';
								}
								$fprv_fec_emisDocReemb = explode('-', $fprr_fec_emis);
								$fprv_fec_emisDocReemb = $fprv_fec_emisDocReemb[2] . '/' . $fprv_fec_emisDocReemb[1] . '/' . $fprv_fec_emisDocReemb[0];

								$xmReembolsos .= '<reembolsoDetalle> 
                                    <tipoIdentificacionProveedorReembolso>' . $fprr_tip_iden . '</tipoIdentificacionProveedorReembolso>
                                    <identificacionProveedorReembolso>' . $fprr_ruc_prov . '</identificacionProveedorReembolso>
                                    <codPaisPagoProveedorReembolso>' . $fprv_cod_paisp . '</codPaisPagoProveedorReembolso > 
                                    <tipoProveedorReembolso>' . $trans_tip_comp . '</tipoProveedorReembolso> 
                                    <codDocReembolso>' . $fprv_cre_fisc . '</codDocReembolso> 
                                    <estabDocReembolso>' . $fprr_num_seri . '</estabDocReembolso> 
                                    <ptoEmiDocReembolso>' . $fprr_num_esta . '</ptoEmiDocReembolso>
                                    <secuencialDocReembolso>' . cero_mas_func('0', 9 - $ll_numeros) . $fprr_cod_fprr . '</secuencialDocReembolso>
                                    <fechaEmisionDocReembolso>' . $fprv_fec_emisDocReemb . '</fechaEmisionDocReembolso>
                                    <numeroAutorizacionDocReemb>' . $fprr_num_auto . '</numeroAutorizacionDocReemb>
                                ';


								if (($fprr_val_gra0) != 0) {
									if (intval($fprr_val_pivb) == 0) {
										$cod_iva = 0;
									} else if (intval($fprr_val_pivb) == 12) {
										$cod_iva = 2;
									}
									else if (intval($fprr_val_pivb) == 14) {
										$cod_iva = 3;
									} else if (intval($fprr_val_pivb) == 15) {
										$cod_iva = 4;
									} else if (intval($fprr_val_pivb) == 5) {
										$cod_iva = 5;
									} else if (intval($fprr_val_pivb) == 13) {
										$cod_iva = 10;
									}else if (intval($fprr_val_pivb) == 8) {
										$cod_iva = 8;
									}
									$xmReembolsos .= '<detalleImpuestos> 
                                        <detalleImpuesto> 
                                            <codigo>2</codigo> 
                                            <codigoPorcentaje>' . $cod_iva . '</codigoPorcentaje>                                   
                                            <tarifa>' . intval($fprr_val_pivb) . '</tarifa>                  
											<baseImponibleReembolso>' . $fprr_val_gra0 . '</baseImponibleReembolso> 
                                            <impuestoReembolso>' . round($fprr_val_vivb, 2) . '</impuestoReembolso>
                                        </detalleImpuesto> 
                                    </detalleImpuestos> 
                                    ';
								} else if (($fprr_val_grs0) != 0) {
									if (intval($fprr_val_pivs) == 0) {
										$cod_iva = 0;
									} else if (intval($fprr_val_pivs) == 12) {
										$cod_iva = 2;
									} else if (intval($fprr_val_pivs) == 14) {
										$cod_iva = 3;
									} else if (intval($fprr_val_pivs) == 15) {
										$cod_iva = 4;
									} else if (intval($fprr_val_pivs) == 5) {
										$cod_iva = 5;
									} else if (intval($fprr_val_pivs) == 13) {
										$cod_iva = 10;
									} else if (intval($fprr_val_pivs) == 8) {
										$cod_iva = 8;
									}

									$xmReembolsos .= '<detalleImpuestos> 
                                        <detalleImpuesto> 
                                            <codigo>2</codigo> 
                                            <codigoPorcentaje>' . $cod_iva . '</codigoPorcentaje>                                   
                                            <tarifa>' . intval($fprr_val_pivs) . '</tarifa>                  
											<baseImponibleReembolso>' . $fprr_val_grs0 . '</baseImponibleReembolso> 
                                            <impuestoReembolso>' . round($fprr_val_vivs, 2) . '</impuestoReembolso>
                                        </detalleImpuesto> 
                                    </detalleImpuestos> 
                                    ';
								} else if (($fprr_val_grab) != 0) {
									if (intval($fprr_val_pivb) == 0) {
										$cod_iva = 0;
									} else if (intval($fprr_val_pivb) == 12) {
										$cod_iva = 2;
									} else if (intval($fprr_val_pivb) == 14) {
										$cod_iva = 3;
									} else if (intval($fprr_val_pivb) == 15) {
										$cod_iva = 4;
									} else if (intval($fprr_val_pivb) == 5) {
										$cod_iva = 5;
									} else if (intval($fprr_val_pivb) == 13) {
										$cod_iva = 10;
									} else if (intval($fprr_val_pivb) == 8) {
										$cod_iva = 8;
									}
									$xmReembolsos .= '<detalleImpuestos> 
                                        <detalleImpuesto> 
                                            <codigo>2</codigo> 
                                            <codigoPorcentaje>' . $cod_iva . '</codigoPorcentaje>                                   
                                            <tarifa>' . intval($fprr_val_pivb) . '</tarifa>                  
											<baseImponibleReembolso>' . $fprr_val_grab . '</baseImponibleReembolso> 
                                            <impuestoReembolso>' . round($fprr_val_vivb, 2) . '</impuestoReembolso>
                                        </detalleImpuesto> 
                                    </detalleImpuestos> 
                                    ';
								} else if (($fprr_val_gras) != 0) {
									if (intval($fprr_val_pivs) == 0) {
										$cod_iva = 0;
									} else if (intval($fprr_val_pivs) == 12) {
										$cod_iva = 2;
									} else if (intval($fprr_val_pivs) == 14) {
										$cod_iva = 3;
									} else if (intval($fprr_val_pivs) == 15) {
										$cod_iva = 4;
									} else if (intval($fprr_val_pivs) == 5) {
										$cod_iva = 5;
									} else if (intval($fprr_val_pivs) == 13) {
										$cod_iva = 10;
									} else if (intval($fprr_val_pivs) == 8) {
										$cod_iva = 8;
									}
									$xmReembolsos .= '<detalleImpuestos> 
                                        <detalleImpuesto> 
                                            <codigo>2</codigo> 
                                            <codigoPorcentaje>' . $cod_iva . '</codigoPorcentaje>                                   
                                            <tarifa>' . intval($fprr_val_pivs) . '</tarifa>                  
											<baseImponibleReembolso>' . $fprr_val_gras . '</baseImponibleReembolso> 
                                            <impuestoReembolso>' . round($fprr_val_vivs, 2) . '</impuestoReembolso>
                                        </detalleImpuesto> 
                                    </detalleImpuestos> 
                                    ';
								}

								$xmReembolsos .= '</reembolsoDetalle>';
							} while ($oIfx->SiguienteRegistro());
						}
					}


					$xml .= '<reembolsos>';
					$xml .= $xmReembolsos;
					$xml .= '</reembolsos>';
				}


				$sql = "select minv_tot_minv,minv_cod_fpagop
							 from saeminv
							 where minv_fac_prov = '$ret_num_fact'
							 and minv_cod_empr = $id_empresa
							 and minv_cod_sucu = $id_sucursal";
				$numero_registros = consulta_string($sql, 'numero_registros', $oIfx, 0);
				$minv_cod_fpagop = consulta_string($sql, 'minv_cod_fpagop', $oIfx, 0);
				$minv_tot_minv = consulta_string($sql, 'minv_tot_minv', $oIfx, 0);

				$xmlpagos = '';
				if ($numero_registros > 0) {
					$xmlpagos = "<formapago>$minv_cod_fpagop</formapago>
					<total>$minv_tot_minv</total>";
				}

				$xml .= "<pagos>
						<pago>
						<formaPago>01</formaPago>
					<total>" . round($valorPago, 2) . "</total>
					</pago>
						</pagos>
						</docSustento>
							</docsSustento>
							";


				$aDataInfoAdic['Email'] = trim($ret_email_clpv);
				$aDataInfoAdic['Telefono'] = trim($rete_telf_benf);
				$aDataInfoAdic['Direccion'] = trim($rete_dire_benf);
				/*if (!empty($empr_ac2_empr)) {
					$aret = explode('-', $empr_ac2_empr);
					$numret = intval($aret[2]);
					$aDataInfoAdic['Agente de retencion'] = 'No. Resolucion: ' . $numret;
				}*/
				if (!empty($empr_ac2_empr)) {
					$aDataInfoAdic['Agente de Retencion'] = $empr_ac2_empr;
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
				$xmlInf = "";
				if (trim($ret_email_clpv) != '' || trim($rete_telf_benf) != '' || trim($rete_dire_benf) != '' || trim($empr_ac2_empr) != '') {
					if (count($etiqueta) > 0) {
						foreach ($etiqueta as $nom) {
							if ($aDataInfoAdic[$nom] != '')
								$xmlInf .= "<campoAdicional nombre=\"$nom\">$aDataInfoAdic[$nom]</campoAdicional>";
						}
					}
				}
				if ($xmlInf != "") {
					$xml .= "<infoAdicional>";
					$xml .= $xmlInf;
					$xml .= "</infoAdicional>";
				}

				$xml .= '</comprobanteRetencion>';

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

					$nombre = $claveAcceso . ".xml";
					$archivo = fopen($ruta_gene . '/' . $nombre, "w+");

					//fwrite($archivo, $xml);
					fwrite($archivo, utf8_encode($xml));
					fclose($archivo);
				} elseif ($empr_tip_firma == 'M') {

					//MultiFirma
					$nombre = $claveAcceso . ".xml";
					$serv = '';
					$archivo = '';
					$serv = DIR_FACTELEC . "modulos/sri_offline/documentoselectronicos/generados";
					$archivo = fopen($serv . '/' . $nombre, "w+");

					//fwrite($archivo, $xml);
					fwrite($archivo, utf8_encode($xml));
					fclose($archivo);
				}

				$sql_gasto = '';
				$id = 0;

				$oReturn->script("firmar('$nombre','$claveAcceso','$ruc_empr', '$ret_email_clpv', 'retencion_inventario', 
                                            '$sql_gasto', $id,  $rete_cod_clpv,  '$ret_num_fact', '$asto_cod_ejer', 
                                            '$rete_cod_asto', '$minv_fmov_tmp' , 6, $id_sucursal)");
			} // fin check
		} // fin foreach
	} else {
		$oReturn->alert('Por favor realice una Busqueda...');
	}

	return $oReturn;
}

function firmar_factExpor($aForm = '')
{

	global $DSN_Ifx, $DSN;
	if (session_status() !== PHP_SESSION_ACTIVE) {
		session_start();
	}

	$oReturn = new xajaxResponse();

	$oIfx = new Dbo;
	$oIfx->DSN = $DSN_Ifx;
	$oIfx->Conectar();

	$oIfx1 = new Dbo;
	$oIfx1->DSN = $DSN_Ifx;
	$oIfx1->Conectar();

	//VARIABLES GLOBALES
	$id_empresa = $_SESSION['U_EMPRESA'];
	$id_sucursal = $_SESSION['U_SUCURSAL'];
	$array_fact = $_SESSION['U_FACT_ENVIO'];

	unset($_SESSION['aDataGirdErro']);
	unset($array);

	$sqlTipo = "select  sucu_tip_ambi, sucu_tip_emis  from saesucu where
                    sucu_cod_sucu = $id_sucursal ";

	if ($oIfx->Query($sqlTipo)) {
		if ($oIfx->NumFilas() > 0) {
			$ambiente = $oIfx->f('sucu_tip_ambi');
			$tip_emis = $oIfx->f('sucu_tip_emis');
		}
	}
	$oIfx->Free();

	$sql = "select empr_nom_empr, empr_ruc_empr , empr_dir_empr, empr_conta_sn,empr_rimp_sn,
                empr_num_resu, empr_ac2_empr from saeempr where empr_cod_empr = $id_empresa ";

	if ($oIfx->Query($sql)) {
		if ($oIfx->NumFilas() > 0) {
			$nombre_empr = trim($oIfx->f('empr_nom_empr'));
			$ruc_empr = $oIfx->f('empr_ruc_empr');
			$dir_empr = trim($oIfx->f('empr_dir_empr'));
			$conta_sn = $oIfx->f('empr_conta_sn');
			$empr_num_resu = $oIfx->f('empr_num_resu');
			$empr_rimp_sn = $oIfx->f('empr_rimp_sn');
			$empr_ac2_empr = trim($oIfx->f('empr_ac2_empr'));
		}
	}
	$oIfx->Free();

	if ($conta_sn == 'S') {
		$conta_sn = 'SI';
	} else {
		$conta_sn = 'NO';
	}

	

	// DATOS
	if (count($array_fact) > 0) {
		foreach ($array_fact as $val) {
			$id_factura = $val[0];
			$check = $aForm[$id_factura . '_f'];

			$xml = '';
			$xmlDeta = '';
			$descuento = 0;
			if (!empty($check)) {
				$num8 = 12345678;
				//				print_r($val);exit;
				$fact_nom_cliente = $val[3];
				$fact_tlf_cliente = $val[9];
				$fact_dir_clie = $val[10];
				$fact_ruc_clie = $val[2];
				$fact_fech_fact = $val[11];
				$fact_num_preimp = $val[1];
				$fact_nse_fact = $val[12];
				$fact_cod_clpv = $val[13];
				$fact_con_miva = $val[5];
				$fact_sin_miva = $val[6];
				$fact_iva = $val[4];
				$fact_ice = $val[14];
				$fact_val_irbp = $val[15];
				$fact_email_clpv = $val[8];
				$tipoIdentificacionComprador = $val[16];
				$fact_term_expor = $val[17];
				$puerto_emb = $val[18];
				$puerto_dest = $val[19];
				$pais = $val[20];
				$flete = $val[21];
				$anticipo = $val[22];
				$otros = $val[23];
				$fp_expor = $val[25];
				$fact_tot_fact = $val[7];
				$desc_valor = $val[26];
				$plazo = $val[27];
				$clave_acceso = $val[28];
				$id_sucursal = $val[29];
				$infoAdicional = $val[30];

				$config_jire = obtener_configuracion_jire($id_empresa, $id_sucursal);
				$para_inf_gfac = $config_jire[17];


				//VALIDAICON CONVERSION DE UNBIDADES
				$sqlfa="select  para_conv_sn from saepara where para_cod_empr= $id_empresa and para_cod_sucu=$id_sucursal";
				$para_conv_sn=consulta_string($sqlfa,'para_conv_sn',$oIfx,'');

				//$oReturn->alert($fp_expor);

				$totalSinImpuestos = $fact_con_miva + $fact_sin_miva;
				$importeTotal = $totalSinImpuestos + $fact_iva + $fact_ice + $fact_val_irbp;

				$estable = substr($fact_nse_fact, 0, 3);
				$serie = substr($fact_nse_fact, 3, 6);

				//				$fec_fact = cambioFecha($fact_fech_fact, 'mm/dd/aaaa', 'dd/mm/aaaa');
				$fec_fact = date("d/m/Y", strtotime($fact_fech_fact));

				$sqlDetalle = "select * from saedfac where 
                                    dfac_cod_fact = $id_factura and 
                                    dfac_cod_sucu = $id_sucursal and 
                                    dfac_cod_empr = $id_empresa ";
				$baseImponibleIRBP = 0;
				if ($oIfx1->Query($sqlDetalle)) {
					$xmlDeta .= '<detalles>';
					$bandera = 3;
					do {
						$dfac_cod_prod = $oIfx1->f("dfac_cod_prod");
						$dfac_cant_dfac = $oIfx1->f("dfac_cant_dfac");
						$dfac_precio_dfac = $oIfx1->f("dfac_precio_dfac");
						$dfac_mont_total = $oIfx1->f("dfac_mont_total");
						$dfac_por_iva = $oIfx1->f("dfac_por_iva");
						$dfac_por_irbp = $oIfx1->f("dfac_por_irbp");
						$dfac_des1_dfac = $oIfx1->f("dfac_des1_dfac");
						$dfac_des2_dfac = $oIfx1->f("dfac_des2_dfac");
						$descuento_general = $oIfx1->f("dfac_por_dsg");
						$dfac_cant_conv = $oIfx1->f("dfac_cant_conv");
						$dfac_det_dfac = trim($oIfx1->f("dfac_det_dfac"));
					
						$descuento = $dfac_des1_dfac + $dfac_des2_dfac + $descuento_general;
						if ($descuento > 0)
							$descuento = number_format(($dfac_cant_dfac * number_format($dfac_precio_dfac, 2, '.', '')), 2, '.', '') - $dfac_mont_total;
						else
							$descuento = 0;

						//PRODUCTO
						$sqlDescripcionProd = "select prod_nom_prod, prod_cod_barra from saeprod where 
                                                    prod_cod_prod = '$dfac_cod_prod' and 
                                                    prod_cod_empr = $id_empresa and 
                                                    prod_cod_sucu = $id_sucursal ";
						if ($oIfx->Query($sqlDescripcionProd)) {
							if ($oIfx->NumFilas() > 0) {
								$prod_nom_prod = $oIfx->f('prod_nom_prod');
								$prod_cod_barra = $oIfx->f('prod_cod_barra');
							}
						}

						if (empty($prod_cod_barra)) {
							$prod_cod_barra = $dfac_cod_prod;
						}

						//                        $prod_nom_prod = consulta_string_func($sqlDescripcionProd,"prod_nom_prod", $oIfx, '');


						//VALIDAICON CONVERSION DE UNIDADES
						if($para_conv_sn=='S'){
							if(round($dfac_cant_conv,2)>0){
								$dfac_cant_dfac= $dfac_cant_conv;
								$dfac_precio_dfac=$dfac_mont_total/$dfac_cant_dfac;
							}  
						}

						$sqlfa = "select para_dfa_para from saepara where para_cod_empr= $id_empresa and para_cod_sucu=$id_sucursal";
						$para_dfa_para = consulta_string($sqlfa, 'para_dfa_para', $oIfx, '');

						if ($para_dfa_para == 1) {
							if (!empty($dfac_det_dfac)) {
								$prod_nom_prod = $dfac_det_dfac;
							}
						}

						$xmlDeta .= '<detalle>';
						$xmlDeta .= "<codigoPrincipal>$dfac_cod_prod</codigoPrincipal>";
						$xmlDeta .= "<codigoAuxiliar>$prod_cod_barra</codigoAuxiliar>";
						$xmlDeta .= "<descripcion>$prod_nom_prod</descripcion>";
						$xmlDeta .= "<cantidad>$dfac_cant_dfac</cantidad>";
						$xmlDeta .= "<precioUnitario>" . round($dfac_precio_dfac, 4) . "</precioUnitario>";
						$xmlDeta .= "<descuento>" . round($descuento, 2) . "</descuento>";
						$xmlDeta .= "<precioTotalSinImpuesto>" . round($dfac_mont_total, 2) . "</precioTotalSinImpuesto>";
						$xmlDeta .= '<impuestos>';

						if ($dfac_por_iva == 0) {
							$codigoPorcentaje = 0;
							$valor = 0.00;
							$tarifa = 0.00;
						} elseif ($dfac_por_iva == 12) {
							$codigoPorcentaje = 2;
							$valor = round((($dfac_mont_total * $dfac_por_iva) / 100), 2);
							$tarifa = 12.00;
							$bandera = 2;
						} elseif ($dfac_por_iva == 14) {
							$codigoPorcentaje = 3;
							$valor = round((($dfac_mont_total * $dfac_por_iva) / 100), 2);
							$tarifa = 14.00;
							$bandera = 3;
						} elseif ($dfac_por_iva == 15) {
							$codigoPorcentaje = 4;
							$valor = round((($dfac_mont_total * $dfac_por_iva) / 100), 2);
							$tarifa = 15.00;
							$bandera = 4;
						} elseif ($dfac_por_iva == 5) {
							$codigoPorcentaje = 5;
							$valor = round((($dfac_mont_total * $dfac_por_iva) / 100), 2);
							$tarifa = 5.00;
							$bandera = 5;
						} elseif ($dfac_por_iva == 13) {
							$codigoPorcentaje = 10;
							$valor = round((($dfac_mont_total * $dfac_por_iva) / 100), 2);
							$tarifa = 13.00;
							$bandera = 10;
						} elseif ($dfac_por_iva == 8) {
							$codigoPorcentaje = 8;
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
							$sql_unid = "select COALESCE(prod_uni_caja,1) as prod_uni_caja  from saeprod where
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

						$xmlDeta .= '</impuestos>';
						$xmlDeta .= '</detalle>';
					} while ($oIfx1->SiguienteRegistro());
					$xmlDeta .= '</detalles>';
				} // fin ifx
				//
				$xmlDeta . -'</totalConImpuestos>';

				$rimpe = "";
				if ($empr_rimp_sn == "S") {
					$rimpe = "<contribuyenteRimpe>CONTRIBUYENTE RÉGIMEN RIMPE</contribuyenteRimpe>";
				}

				///CABECERA DEL $XML
				$xml .= '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
                                    <factura id="comprobante" version="1.1.0">
                                        <infoTributaria>
                                            <ambiente>' . $ambiente . '</ambiente>
                                            <tipoEmision>' . $tip_emis . '</tipoEmision>
                                            <razonSocial>' . $nombre_empr . '</razonSocial>
                                            <nombreComercial>' . $nombre_empr . '</nombreComercial>
                                            <ruc>' . $ruc_empr . '</ruc>
                                            <claveAcceso>' . $clave_acceso . '</claveAcceso>
                                            <codDoc>01</codDoc>
                                            <estab>' . $estable . '</estab>
                                            <ptoEmi>' . $serie . '</ptoEmi>
                                            <secuencial>' . $fact_num_preimp . '</secuencial>
                                            <dirMatriz>' . $dir_empr . '</dirMatriz>
											' . $rimpe . '
                                        </infoTributaria>
                                        <infoFactura>
                                            <fechaEmision>' . $fec_fact . '</fechaEmision>
											<dirEstablecimiento>' . $dir_empr . '</dirEstablecimiento>';
				if ($empr_num_resu != '') {
					$xml .= '<contribuyenteEspecial>' . $empr_num_resu . '</contribuyenteEspecial>';
				}


				// Icoterm
				$sql_tem = "select id, nombre from comercial.incoterm where id = $fact_term_expor;";
				$fact_term_expor = consulta_string_func($sql_tem, 'nombre', $oIfx, '0');

				// PUERTO DE EMBARQUE
				$sql = "select  prto_cod_prto, prto_des_prto   from saeprto where
                                                prto_tip_prto = 'E'  and 
                                                prto_cod_prto = '$puerto_emb' ";
				$puerto_embarque = consulta_string_func($sql, 'prto_des_prto', $oIfx, '0');

				$xml .= '<obligadoContabilidad>' . $conta_sn . '</obligadoContabilidad>
									<comercioExterior>EXPORTADOR</comercioExterior>
									<incoTermFactura>' . $fact_term_expor . '</incoTermFactura>
									<lugarIncoTerm>' . $puerto_embarque . '</lugarIncoTerm>
									<paisOrigen>593</paisOrigen>';

				$xml .= '<puertoEmbarque>' . $puerto_embarque . '</puertoEmbarque>';
				// PUERTOD ESTINO
				$sql = "select  prto_cod_prto, prto_des_prto   from saeprto where
                                                prto_pais_prto = $pais and
                                                prto_tip_prto = 'D'  and 
                                                prto_cod_prto = '$puerto_dest' ";
				$puerto_destino = consulta_string_func($sql, 'prto_des_prto', $oIfx, '0');
				$xml .= '<puertoDestino>' . $puerto_destino . '</puertoDestino>';
				// PAIS DESTINO
				$sql = "select  pais_cod_inte from saepais where pais_cod_pais = $pais ";
				$pais_destino = consulta_string_func($sql, 'pais_cod_inte', $oIfx, 0);
				$xml .= '<paisDestino>' . $pais_destino . '</paisDestino>';
				$xml .= '<paisAdquisicion>593</paisAdquisicion>';

				$xml .= '<tipoIdentificacionComprador>' . $tipoIdentificacionComprador . '</tipoIdentificacionComprador>
                                        <razonSocialComprador>' . htmlspecialchars($fact_nom_cliente) . '</razonSocialComprador>
                                        <identificacionComprador>' . $fact_ruc_clie . '</identificacionComprador>
                                        <direccionComprador>' . $fact_dir_clie . '</direccionComprador> 
                                        <totalSinImpuestos>' . round($totalSinImpuestos, 2) . '</totalSinImpuestos>
                                        <incoTermTotalSinImpuestos>' . $fact_term_expor . '</incoTermTotalSinImpuestos>
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
					$xml .= '<totalImpuesto>
                                         <codigo>2</codigo>
                                         <codigoPorcentaje>0</codigoPorcentaje>
                                         <baseImponible>' . round($fact_sin_miva, 2) . '</baseImponible>
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
				//$oReturn->alert($baseImponibleIRBP);
				$xml .= "</totalConImpuestos> ";

				$xml .= '              <propina>0.00</propina>
										<fleteInternacional>' . round($flete, 2) . '</fleteInternacional>
                                        <seguroInternacional>' . round($otros, 2) . '</seguroInternacional>
                                        <gastosAduaneros>0.00</gastosAduaneros> 
                                        <gastosTransporteOtros>' . round($anticipo, 2) . '</gastosTransporteOtros> 
										<importeTotal>' . round(($fact_sin_miva + $fact_con_miva + $fact_iva + $flete + $otros + $anticipo), 2) . '</importeTotal>
                                     <moneda>DOLAR</moneda>
									 <pagos> 																			
                                        <pago> 																		
                                        <formaPago>' . $fp_expor . '</formaPago>
                                        <total>' . round(($fact_sin_miva + $fact_con_miva + $fact_iva + $otros + $flete + $anticipo - $desc_valor), 2) . '</total>
                                        <plazo>' . $plazo . '</plazo>
                                        <unidadTiempo>dias</unidadTiempo>
                                        </pago> 				
                                     </pagos>
                                 </infoFactura>';
				$xml .= $xmlDeta;



				///INFORMACION ADICIONAL FACTURA DE EXPORTACION PARAMETRO
				if(!empty($infoAdicional)&& $para_inf_gfac=='S'){
					// Separar las líneas usando <br /> como delimitador
					$texto_adi = explode('<br />', $infoAdicional);

					// Recorrer cada línea
					foreach ($texto_adi as $val) {
						// Eliminar espacios en blanco extra al principio y al final
						$val = trim($val);

						if (!empty($val)) {
							// Separar por el primer ":"
							$text_linea = explode(':', $val, 2); // Limitar la separación a 2 partes

							if (count($text_linea) == 2) {
								// Si se encontró el ":", agregar al XML
								$campo_nombre = trim($text_linea[0]);
								$campo_valor = trim($text_linea[1]);
								$campo_valor = substr($campo_valor,0,300);
								$aDataInfoAdic[htmlspecialchars($campo_nombre.':')] = htmlspecialchars($campo_valor);
							} 
						}
					}

				}

				if(!empty($empr_ac2_empr)){
					$aDataInfoAdic['AGENTE DE RETENCION'] = $empr_ac2_empr;
				}

				$aDataInfoAdic['Direccion'] = $fact_dir_clie;
				$aDataInfoAdic['Telefono'] = $fact_tlf_cliente;
				$aDataInfoAdic['Email'] = $fact_email_clpv;



				//$aDataInfoAdic['AGENTE RETENCION'] = 'NAC-DNCRASC20-00000001';

				$etiqueta = array_keys($aDataInfoAdic);
				if (count($etiqueta) > 0) {
					$xml .= '<infoAdicional>';
					foreach ($etiqueta as $nom) {
						if ($aDataInfoAdic[$nom] != '')
							$xml .= "<campoAdicional nombre=\"$nom\">$aDataInfoAdic[$nom]</campoAdicional>";
					}
					$xml .= '</infoAdicional>';
				}

				$xml .= '</factura>';

				//$oReturn->alert($xml);
				// CREAR CARPETA ANEXO
				//MultiFirma
				$nombre = $clave_acceso . ".xml";
				$serv = '';
				$archivo = '';

				$serv = DIR_FACTELEC . "modulos/sri_offline/documentoselectronicos/generados";
				//$oReturn->alert($serv);

				$archivo = fopen($serv . '/' . $nombre, "w+");

				fwrite($archivo, utf8_encode($xml));
				fclose($archivo);

				$sql_fact = "where fact_cod_empr = $id_empresa and fact_cod_sucu = $id_sucursal   and fact_cod_clpv = $fact_cod_clpv and fact_cod_fact = $id_factura ";

				unset($_SESSION['id']);
				$_SESSION['id'] = $id_factura;

				$_SESSION['contrRepor'] = $clave_acceso;
				$rete_cod_clpv = 0;
				$ret_num_fact = '';
				$asto_cod_ejer = 0;
				$rete_cod_asto = '';
				$fprv_fec_emis = '';
				$oReturn->script("firmar('$nombre','$clave_acceso','$ruc_empr', '$fact_email_clpv', 'factura_expor', '$sql_fact', $id_factura,
                                         $rete_cod_clpv, '$ret_num_fact', '$asto_cod_ejer', '$rete_cod_asto', '$fprv_fec_emis', 7 ,'$id_sucursal')");
			} // fin check
		} // fin foreach
	} else {
		$oReturn->alert('Por favor realice una Busqueda...');
	}

	//    $_SESSION['aDataGirdErro'] = $array;
	//    $oReturn->script("reporte();");
	return $oReturn;
}

function firmar_factFlor($aForm = '')
{

	global $DSN_Ifx, $DSN;
	if (session_status() !== PHP_SESSION_ACTIVE) {
		session_start();
	}

	$oReturn = new xajaxResponse();

	$oIfx = new Dbo;
	$oIfx->DSN = $DSN_Ifx;
	$oIfx->Conectar();

	$oIfx1 = new Dbo;
	$oIfx1->DSN = $DSN_Ifx;
	$oIfx1->Conectar();

	//VARIABLES GLOBALES
	$id_empresa = $_SESSION['U_EMPRESA'];
	$id_sucursal = $_SESSION['U_SUCURSAL'];
	$array_fact = $_SESSION['U_FACT_ENVIO'];

	unset($_SESSION['aDataGirdErro']);
	unset($array);

	$sqlTipo = "select  sucu_tip_ambi, sucu_tip_emis  from saesucu where
                    sucu_cod_sucu = $id_sucursal ";

	if ($oIfx->Query($sqlTipo)) {
		if ($oIfx->NumFilas() > 0) {
			$ambiente = $oIfx->f('sucu_tip_ambi');
			$tip_emis = $oIfx->f('sucu_tip_emis');
		}
	}
	$oIfx->Free();

	$sql = "select empr_nom_empr, empr_ruc_empr , empr_dir_empr, empr_conta_sn, empr_rimp_sn,
                empr_num_resu from saeempr where empr_cod_empr = $id_empresa ";

	if ($oIfx->Query($sql)) {
		if ($oIfx->NumFilas() > 0) {
			$nombre_empr = trim($oIfx->f('empr_nom_empr'));
			$ruc_empr = $oIfx->f('empr_ruc_empr');
			$dir_empr = trim($oIfx->f('empr_dir_empr'));
			$conta_sn = $oIfx->f('empr_conta_sn');
			$empr_num_resu = $oIfx->f('empr_num_resu');
			$empr_rimp_sn = $oIfx->f('empr_rimp_sn');
		}
	}
	$oIfx->Free();

	if ($conta_sn == 'S') {
		$conta_sn = 'SI';
	} else {
		$conta_sn = 'NO';
	}

	$array_imp = $_SESSION['U_EMPRESA_IMPUESTO'];
    $etiqueta_iva=$array_imp ['IVA'];
    $empr_cod_pais = $_SESSION['U_PAIS_COD'];
     // IMPUESTOS POR PAIS
     $sql = "select p.impuesto, p.etiqueta, p.porcentaje from comercial.pais_etiq_imp p where
     p.pais_cod_pais = $empr_cod_pais and etiqueta='$etiqueta_iva'";
    if ($oIfx->Query($sql)) {
        if ($oIfx->NumFilas() > 0) {
            do {
                $empr_iva_porc = $oIfx->f('porcentaje');
            } while ($oIfx->SiguienteRegistro());
        }
    }
    $oIfx->Free();


	// DATOS
	if (count($array_fact) > 0) {
		foreach ($array_fact as $val) {
			$id_factura = $val[0];
			$check = $aForm[$id_factura . '_f'];

			$xml = '';
			$xmlDeta = '';
			$descuento = 0;
			//            $oReturn->alert($check);
			if (!empty($check)) {
				//COMSULTAMOS LOS DATOS Y FORMAMOS LOS XML
				$num8 = 12345678;
				$fact_nom_cliente = $val[3];
				$fact_tlf_cliente = $val[9];
				$fact_dir_clie = $val[10];
				$fact_ruc_clie = $val[2];
				$fact_fech_fact = $val[11];
				$fact_num_preimp = $val[1];
				$fact_nse_fact = $val[12];
				$fact_cod_clpv = $val[13];
				$fact_con_miva = $val[5];
				$fact_sin_miva = $val[6];
				$fact_iva = $val[4];
				$fact_ice = $val[14];
				$fact_val_irbp = $val[15];
				$fact_email_clpv = $val[8];
				$tipoIdentificacionComprador = $val[16];
				$cod_almacen = $val[17];
				$orden_compra = $val[18];

				$totalSinImpuestos = $fact_con_miva + $fact_sin_miva;
				$importeTotal = $totalSinImpuestos + $fact_iva + $fact_ice + $fact_val_irbp;

				$estable = substr($fact_nse_fact, 0, 3);
				$serie = substr($fact_nse_fact, 3, 6);

				$fec_xml = fecha_clave($fact_fech_fact);
				$fec_fact = cambioFecha($fact_fech_fact, 'mm/dd/aaaa', 'dd/mm/aaaa');

				$clave_acceso = $fec_xml . '01' . $ruc_empr . $ambiente . $fact_nse_fact . $fact_num_preimp . $num8 . $tip_emis;
				$digitoVerificador = digitoVerificador($clave_acceso);
				$clave_acceso = $clave_acceso . $digitoVerificador;

				//PESTAÑA DETALLES
				$sqlDetalle = "select * from saedrfa where 
                                    drfa_cod_fpak = $id_factura and 
                                    drfa_cod_empr = $id_empresa ";
				$baseImponibleIRBP = 0;
				if ($oIfx1->Query($sqlDetalle)) {
					$xmlDeta .= '<detalles>';
					$banderaIva = 3;
					do {
						$dfac_cod_prod = $oIfx1->f("drfa_cod_ramo");
						$dfac_cant_dfac1 = $oIfx1->f("drfa_can_drfa");
						$dfac_cant_dfac2 = $oIfx1->f("drfa_cod_ntal");
						$drfa_num_caja = $oIfx1->f("drfa_num_caja");
						$dfac_cant_dfac = ($dfac_cant_dfac1 * $dfac_cant_dfac2 * $drfa_num_caja);
						$dfac_precio_dfac = $oIfx1->f("drfa_pre_tall");
						$dfac_mont_total = ($dfac_cant_dfac * $dfac_precio_dfac);

						if ($fact_con_miva > 0) {
							$dfac_por_iva = $empr_iva_porc;
						} else {
							$dfac_por_iva = 0;
						}
						$dfac_por_irbp = 0;
						$dfac_des1 = 0.00;
						$dfac_des2 = 0.00;
						$dfac_des3 = 0.00;
						$dfac_des4 = 0.00;
						$dfac_des5 = 0.00;

						$descuento = 0;
						if ($dfac_des1 != 0 || $dfac_des2 != 0 || $dfac_des3 != 0 || $dfac_des4 != 0 || $dfac_des5 != 0) {
							$descuento = ($dfac_cant_dfac * $dfac_precio_dfac) - $dfac_mont_total;
							if ($descuento != 0) {
								$totalDescuento += $descuento;
							}
						} // fin if

						$descuento = number_format($descuento, 2, '.', '');

						//                        $descuento = $dfac_des1_dfac+$dfac_des2_dfac+$descuento_general;
						//                        if($descuento>0)
						//                            $descuento = number_format(($dfac_cant_dfac*number_format($dfac_precio_dfac,2,'.','')),2,'.','')-$dfac_mont_total;
						//                        else
						//                            $descuento = 0;
						//PRODUCTO
						$sqlDescripcionProd = "select vard_nom_vard from saevard where 
                                                      vard_cod_vard = (select ramo_cod_vard from saeramo where ramo_cod_ramo = '$dfac_cod_prod')";
						//$oReturn->alert($sqlDescripcionProd);
						if ($oIfx->Query($sqlDescripcionProd)) {
							if ($oIfx->NumFilas() > 0) {
								$prod_nom_prod = $oIfx->f('vard_nom_vard');
								$prod_cod_barra = '';
							}
						}

						if (empty($prod_cod_barra)) {
							$prod_cod_barra = $dfac_cod_prod;
						}

						//                        $prod_nom_prod = consulta_string_func($sqlDescripcionProd,"prod_nom_prod", $oIfx, '');

						$xmlDeta .= '<detalle>';
						$xmlDeta .= "<codigoPrincipal>$dfac_cod_prod</codigoPrincipal>";
						$xmlDeta .= "<codigoAuxiliar>$prod_cod_barra</codigoAuxiliar>";
						$xmlDeta .= "<descripcion>$prod_nom_prod</descripcion>";
						$xmlDeta .= "<cantidad>$dfac_cant_dfac</cantidad>";
						$xmlDeta .= "<precioUnitario>" . round($dfac_precio_dfac, 4) . "</precioUnitario>";
						$xmlDeta .= "<descuento>" . round($descuento, 4) . "</descuento>";
						$xmlDeta .= "<precioTotalSinImpuesto>" . round($dfac_mont_total, 4) . "</precioTotalSinImpuesto>";
						$xmlDeta .= '<impuestos>';



						if ($dfac_por_iva == 0) {
							$codigoPorcentaje = 0;
							$valor = 0.00;
							$tarifa = 0.00;
						} elseif ($dfac_por_iva == 12) {
							$codigoPorcentaje = 2;
							$valor = round((($dfac_mont_total * $dfac_por_iva) / 100), 2);
							$tarifa = 12.00;
						} elseif ($dfac_por_iva == 14) {
							$codigoPorcentaje = 3;
							$valor = round((($dfac_mont_total * $dfac_por_iva) / 100), 2);
							$tarifa = 14.00;
						} elseif ($dfac_por_iva == 15) {
							$codigoPorcentaje = 4;
							$valor = round((($dfac_mont_total * $dfac_por_iva) / 100), 2);
							$tarifa = 15.00;
						} elseif ($dfac_por_iva == 5) {
							$codigoPorcentaje = 5;
							$valor = round((($dfac_mont_total * $dfac_por_iva) / 100), 2);
							$tarifa = 5.00;
						} elseif ($dfac_por_iva == 13) {
							$codigoPorcentaje = 10;
							$valor = round((($dfac_mont_total * $dfac_por_iva) / 100), 2);
							$tarifa = 13.00;
						} elseif ($dfac_por_iva == 8) {
							$codigoPorcentaje = 8;
							$valor = round((($dfac_mont_total * $dfac_por_iva) / 100), 2);
							$tarifa = 8.00;
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
							$sql_unid = "select COALESCE(prod_uni_caja,1) as prod_uni_caja  from saeprod where
                                                                        prod_cod_empr = $id_empresa and
                                                                        prod_cod_sucu = $id_sucursal and
                                                                        prod_cod_prod = '$dfac_cod_prod' ";
							//$unid_caja = consulta_string_func($sql_unid, 'prod_uni_caja', $oIfx, 1);
							$unid_caja = 1;

							$xmlDeta .= '<impuesto>';
							$xmlDeta .= '<codigo>5</codigo>';
							$xmlDeta .= "<codigoPorcentaje>5001</codigoPorcentaje>";
							$xmlDeta .= "<tarifa>0.00</tarifa>";
							$xmlDeta .= "<baseImponible>" . round($dfac_mont_total, 2) . "</baseImponible>";
							$xmlDeta .= "<valor>" . number_format($dfac_por_irbp * $dfac_cant_dfac * $unid_caja, 2, '.', '') . "</valor>";
							$xmlDeta .= '</impuesto>';
							$baseImponibleIRBP += $dfac_mont_total;
						} // fin

						$xmlDeta .= '</impuestos>';
						$xmlDeta .= '</detalle>';
					} while ($oIfx1->SiguienteRegistro());
					$xmlDeta .= '</detalles>';
				} // fin ifx
				//
				$xmlDeta . -'</totalConImpuestos>';

				$rimpe = "";
				if ($empr_rimp_sn == "S") {
					$rimpe = "<contribuyenteRimpe>CONTRIBUYENTE RÉGIMEN RIMPE</contribuyenteRimpe>";
				}

				///CABECERA DEL $XML
				$xml .= '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
                                    <factura id="comprobante" version="1.1.0">
                                        <infoTributaria>
                                            <ambiente>' . $ambiente . '</ambiente>
                                            <tipoEmision>' . $tip_emis . '</tipoEmision>
                                            <razonSocial>' . $nombre_empr . '</razonSocial>
                                            <nombreComercial>' . $nombre_empr . '</nombreComercial>
                                            <ruc>' . $ruc_empr . '</ruc>
                                            <claveAcceso>' . $clave_acceso . '</claveAcceso>
                                            <codDoc>01</codDoc>
                                            <estab>' . $estable . '</estab>
                                            <ptoEmi>' . $serie . '</ptoEmi>
                                            <secuencial>' . $fact_num_preimp . '</secuencial>
                                            <dirMatriz>' . $dir_empr . '</dirMatriz>
											' . $rimpe . '
                                        </infoTributaria>
                                        <infoFactura>
                                            <fechaEmision>' . $fec_fact . '</fechaEmision>';
				if ($empr_num_resu != '')
					$xml .= '<contribuyenteEspecial>' . $empr_num_resu . '</contribuyenteEspecial>';

				$xml .= '<obligadoContabilidad>' . $conta_sn . '</obligadoContabilidad>
                                    <tipoIdentificacionComprador>' . $tipoIdentificacionComprador . '</tipoIdentificacionComprador>
                                    <razonSocialComprador>' . htmlspecialchars($fact_nom_cliente) . '</razonSocialComprador>
                                    <identificacionComprador>' . $fact_ruc_clie . '</identificacionComprador>
                                    <totalSinImpuestos>' . round($totalSinImpuestos, 2) . '</totalSinImpuestos>
                                    <totalDescuento>' . round($totalDescuento, 2) . '</totalDescuento>';

				$xml .= "<totalConImpuestos> ";
				if ($fact_con_miva != '') {
					$xml .= '<totalImpuesto>
                                         <codigo>2</codigo>
                                         <codigoPorcentaje>2</codigoPorcentaje>
                                         <baseImponible>' . round($fact_con_miva, 2) . '</baseImponible>
                                         <valor>' . round($fact_iva, 2) . '</valor>
                                     </totalImpuesto>';
				}
				if ($fact_sin_miva != '') {
					$xml .= '<totalImpuesto>
                                         <codigo>2</codigo>
                                         <codigoPorcentaje>0</codigoPorcentaje>
                                         <baseImponible>' . round($fact_sin_miva, 2) . '</baseImponible>
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
				//$oReturn->alert($baseImponibleIRBP);
				$xml .= "</totalConImpuestos> ";

				$xml .= '              <propina>0.00</propina>
                                     <importeTotal>' . round($importeTotal, 2) . '</importeTotal>
                                     <moneda>DOLAR</moneda>
                                 </infoFactura>';
				$xml .= $xmlDeta;

				$aDataInfoAdic['Direccion'] = $fact_dir_clie;
				$aDataInfoAdic['Telefono'] = $fact_tlf_cliente;
				$aDataInfoAdic['Email'] = $fact_email_clpv;
				if (!empty($cod_almacen)) {
					$aDataInfoAdic['codigoAlmacen'] = $cod_almacen;
				}

				if (!empty($orden_compra)) {
					$aDataInfoAdic['ordenCompra'] = $orden_compra;
				}

				$etiqueta = array_keys($aDataInfoAdic);
				if (count($etiqueta) > 0) {
					$xml .= '<infoAdicional>';
					foreach ($etiqueta as $nom) {
						if ($aDataInfoAdic[$nom] != '')
							$xml .= "<campoAdicional nombre=\"$nom\">$aDataInfoAdic[$nom]</campoAdicional>";
					}
					$xml .= '</infoAdicional>';
				}

				$xml .= '</factura>';

				//$oReturn->alert($xml);
				// CREAR CARPETA ANEXO
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

				$sql_fact = "where fpak_cod_empr = $id_empresa and fpak_cod_clpv = $fact_cod_clpv and fpak_cod_fpak = $id_factura ";

				unset($_SESSION['id']);
				$_SESSION['id'] = $id_factura;

				$_SESSION['contrRepor'] = $clave_acceso;
				$ret_num_fact = '';
				$asto_cod_ejer = 0;
				$rete_cod_asto = '';
				$fprv_fec_emis = '';
				$oReturn->script("firmar('$nombre','$clave_acceso','$ruc_empr', '$fact_email_clpv', 'factura_flor', '$sql_fact', $id_factura,
                                           $fact_cod_clpv, '$ret_num_fact', '$asto_cod_ejer', '$rete_cod_asto', '$fprv_fec_emis', 8  )");
			} // fin check
		} // fin foreach
	} else {
		$oReturn->alert('Por favor realice una Busqueda...');
	}

	//    $_SESSION['aDataGirdErro'] = $array;
	//    $oReturn->script("reporte();");
	return $oReturn;
}

function firmar_factFlorExpor($aForm = '')
{

	global $DSN_Ifx, $DSN;
	if (session_status() !== PHP_SESSION_ACTIVE) {
		session_start();
	}

	$oReturn = new xajaxResponse();

	$oIfx = new Dbo;
	$oIfx->DSN = $DSN_Ifx;
	$oIfx->Conectar();

	$oIfx1 = new Dbo;
	$oIfx1->DSN = $DSN_Ifx;
	$oIfx1->Conectar();

	//VARIABLES GLOBALES
	$id_empresa = $_SESSION['U_EMPRESA'];
	$id_sucursal = $_SESSION['U_SUCURSAL'];
	$array_fact = $_SESSION['U_FACT_ENVIO'];

	unset($_SESSION['aDataGirdErro']);
	unset($array);

	$sqlTipo = "select  sucu_tip_ambi, sucu_tip_emis  from saesucu where
                    sucu_cod_sucu = $id_sucursal ";

	if ($oIfx->Query($sqlTipo)) {
		if ($oIfx->NumFilas() > 0) {
			$ambiente = $oIfx->f('sucu_tip_ambi');
			$tip_emis = $oIfx->f('sucu_tip_emis');
		}
	}
	$oIfx->Free();

	$sql = "select empr_nom_empr, empr_ruc_empr , empr_dir_empr, empr_conta_sn, empr_rimp_sn,
                empr_num_resu from saeempr where empr_cod_empr = $id_empresa ";

	if ($oIfx->Query($sql)) {
		if ($oIfx->NumFilas() > 0) {
			$nombre_empr = trim($oIfx->f('empr_nom_empr'));
			$ruc_empr = $oIfx->f('empr_ruc_empr');
			$dir_empr = trim($oIfx->f('empr_dir_empr'));
			$conta_sn = $oIfx->f('empr_conta_sn');
			$empr_num_resu = $oIfx->f('empr_num_resu');
			$empr_rimp_sn = $oIfx->f('empr_rimp_sn');
		}
	}
	$oIfx->Free();

	$array_imp = $_SESSION['U_EMPRESA_IMPUESTO'];
    $etiqueta_iva=$array_imp ['IVA'];
    $empr_cod_pais = $_SESSION['U_PAIS_COD'];
     // IMPUESTOS POR PAIS
     $sql = "select p.impuesto, p.etiqueta, p.porcentaje from comercial.pais_etiq_imp p where
     p.pais_cod_pais = $empr_cod_pais and etiqueta='$etiqueta_iva'";
    if ($oIfx->Query($sql)) {
        if ($oIfx->NumFilas() > 0) {
            do {
                $empr_iva_porc = $oIfx->f('porcentaje');
            } while ($oIfx->SiguienteRegistro());
        }
    }
    $oIfx->Free();

	if ($conta_sn == 'S') {
		$conta_sn = 'SI';
	} else {
		$conta_sn = 'NO';
	}


	// DATOS
	if (count($array_fact) > 0) {
		foreach ($array_fact as $val) {
			$id_factura = $val[0];
			$check = $aForm[$id_factura . '_f'];     //$fact_cod_fact.'_f'

			$xml = '';
			$xmlDeta = '';
			$descuento = 0;
			//            $oReturn->alert($check);
			if (!empty($check)) {
				//COMSULTAMOS LOS DATOS Y FORMAMOS LOS XML
				$num8 = 12345678;
				$fact_nom_cliente = $val[3];
				$fact_tlf_cliente = $val[9];
				$fact_dir_clie = $val[10];
				$fact_ruc_clie = $val[2];
				$fact_fech_fact = $val[11];
				$fact_num_preimp = $val[1];
				$fact_nse_fact = $val[12];
				$fact_cod_clpv = $val[13];
				$fact_con_miva = $val[5];
				$fact_sin_miva = $val[6];
				$fact_iva = $val[4];
				$fact_ice = $val[14];
				$fact_val_irbp = $val[15];
				$fact_email_clpv = $val[8];
				$tipoIdentificacionComprador = $val[16];
				$fact_term_expor = $val[17];
				$puerto_emb = $val[18];
				$puerto_dest = $val[19];
				$pais = $val[20];
				$flete = $val[21];
				$anticipo = $val[22];
				$otros = $val[23];
				$fp_expor = $val[25];
				$fact_tot_fact = $val[7];
				$desc_valor = $val[26];
				$plazo = $val[27];

				//$oReturn->alert($fp_expor);

				$totalSinImpuestos = $fact_con_miva + $fact_sin_miva;
				$importeTotal = $totalSinImpuestos + $fact_iva + $fact_ice + $fact_val_irbp;

				$estable = substr($fact_nse_fact, 0, 3);
				$serie = substr($fact_nse_fact, 3, 6);

				$fec_xml = fecha_clave($fact_fech_fact);
				$fec_fact = cambioFecha($fact_fech_fact, 'mm/dd/aaaa', 'dd/mm/aaaa');

				$clave_acceso = $fec_xml . '01' . $ruc_empr . $ambiente . $fact_nse_fact . $fact_num_preimp . $num8 . $tip_emis;
				$digitoVerificador = digitoVerificador($clave_acceso);
				$clave_acceso = $clave_acceso . $digitoVerificador;
				//
				//                 //PESTA;A DETALLES
				$sqlDetalle = "select * from saedrfa where 
                                    drfa_cod_fpak = $id_factura and 
                                    drfa_cod_empr = $id_empresa ";
				$baseImponibleIRBP = 0;
				if ($oIfx1->Query($sqlDetalle)) {
					$xmlDeta .= '<detalles>';
					do {
						$dfac_cod_prod = $oIfx1->f("drfa_cod_ramo");
						$dfac_cant_dfac1 = $oIfx1->f("drfa_can_drfa");
						$dfac_cant_dfac2 = $oIfx1->f("drfa_cod_ntal");
						$drfa_num_caja = $oIfx1->f("drfa_num_caja");
						$dfac_cant_dfac = ($dfac_cant_dfac1 * $dfac_cant_dfac2 * $drfa_num_caja);
						$dfac_precio_dfac = $oIfx1->f("drfa_pre_tall");
						$dfac_mont_total = ($dfac_cant_dfac * $dfac_precio_dfac);

						if ($fact_con_miva > 0) {
							$dfac_por_iva = $empr_iva_porc;
						} else {
							$dfac_por_iva = 0;
						}
						$dfac_por_irbp = 0;
						$dfac_des1 = 0.00;
						$dfac_des2 = 0.00;
						$dfac_des3 = 0.00;
						$dfac_des4 = 0.00;
						$dfac_des5 = 0.00;

						$descuento = 0;
						if ($dfac_des1 != 0 || $dfac_des2 != 0 || $dfac_des3 != 0 || $dfac_des4 != 0 || $dfac_des5 != 0) {
							$descuento = ($dfac_cant_dfac * $dfac_precio_dfac) - $dfac_mont_total;
							if ($descuento != 0) {
								$totalDescuento += $descuento;
							}
						} // fin if

						$descuento = number_format($descuento, 2, '.', '');

						//PRODUCTO
						$sqlDescripcionProd = "select vard_nom_vard from saevard where 
                                                      vard_cod_vard = (select ramo_cod_vard from saeramo where ramo_cod_ramo = '$dfac_cod_prod')";
						//$oReturn->alert($sqlDescripcionProd);
						if ($oIfx->Query($sqlDescripcionProd)) {
							if ($oIfx->NumFilas() > 0) {
								$prod_nom_prod = $oIfx->f('vard_nom_vard');
								$prod_cod_barra = '';
							}
						}

						if (empty($prod_cod_barra)) {
							$prod_cod_barra = $dfac_cod_prod;
						}

						$xmlDeta .= '<detalle>';
						$xmlDeta .= "<codigoPrincipal>$dfac_cod_prod</codigoPrincipal>";
						$xmlDeta .= "<codigoAuxiliar>$prod_cod_barra</codigoAuxiliar>";
						$xmlDeta .= "<descripcion>$prod_nom_prod</descripcion>";
						$xmlDeta .= "<cantidad>$dfac_cant_dfac</cantidad>";
						$xmlDeta .= "<precioUnitario>" . round($dfac_precio_dfac, 4) . "</precioUnitario>";
						$xmlDeta .= "<descuento>" . round($descuento, 2) . "</descuento>";
						$xmlDeta .= "<precioTotalSinImpuesto>" . round($dfac_mont_total, 2) . "</precioTotalSinImpuesto>";
						$xmlDeta .= '<impuestos>';


						if ($dfac_por_iva == 0) {
							$codigoPorcentaje = 0;
							$valor = 0.00;
							$tarifa = 0.00;
						} elseif ($dfac_por_iva == 12) {
							$codigoPorcentaje = 2;
							$valor = round((($dfac_mont_total * $dfac_por_iva) / 100), 2);
							$tarifa = 12.00;
						} elseif ($dfac_por_iva == 14) {
							$codigoPorcentaje = 3;
							$valor = round((($dfac_mont_total * $dfac_por_iva) / 100), 2);
							$tarifa = 14.00;
						} elseif ($dfac_por_iva == 15) {
							$codigoPorcentaje = 4;
							$valor = round((($dfac_mont_total * $dfac_por_iva) / 100), 2);
							$tarifa = 15.00;
						} elseif ($dfac_por_iva == 5) {
							$codigoPorcentaje = 5;
							$valor = round((($dfac_mont_total * $dfac_por_iva) / 100), 2);
							$tarifa = 5.00;
						} elseif ($dfac_por_iva == 13) {
							$codigoPorcentaje = 10;
							$valor = round((($dfac_mont_total * $dfac_por_iva) / 100), 2);
							$tarifa = 13.00;
						} elseif ($dfac_por_iva == 8) {
							$codigoPorcentaje = 8;
							$valor = round((($dfac_mont_total * $dfac_por_iva) / 100), 2);
							$tarifa = 8.00;
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
							$sql_unid = "select COALESCE(prod_uni_caja,1) as prod_uni_caja  from saeprod where
                                                                        prod_cod_empr = $id_empresa and
                                                                        prod_cod_sucu = $id_sucursal and
                                                                        prod_cod_prod = '$dfac_cod_prod' ";
							//$unid_caja = consulta_string_func($sql_unid, 'prod_uni_caja', $oIfx, 1);
							$unid_caja = 1;

							$xmlDeta .= '<impuesto>';
							$xmlDeta .= '<codigo>5</codigo>';
							$xmlDeta .= "<codigoPorcentaje>5001</codigoPorcentaje>";
							$xmlDeta .= "<tarifa>0.00</tarifa>";
							$xmlDeta .= "<baseImponible>" . round($dfac_mont_total, 2) . "</baseImponible>";
							$xmlDeta .= "<valor>" . number_format($dfac_por_irbp * $dfac_cant_dfac * $unid_caja, 2, '.', '') . "</valor>";
							$xmlDeta .= '</impuesto>';
							$baseImponibleIRBP += $dfac_mont_total;
						} // fin

						$xmlDeta .= '</impuestos>';
						$xmlDeta .= '</detalle>';
					} while ($oIfx1->SiguienteRegistro());
					$xmlDeta .= '</detalles>';
				} // fin ifx
				//
				$xmlDeta . -'</totalConImpuestos>';

				$rimpe = "";
				if ($empr_rimp_sn == "S") {
					$rimpe = "<contribuyenteRimpe>CONTRIBUYENTE RÉGIMEN RIMPE</contribuyenteRimpe>";
				}

				///CABECERA DEL $XML
				$xml .= '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
                                    <factura id="comprobante" version="1.1.0">
                                        <infoTributaria>
                                            <ambiente>' . $ambiente . '</ambiente>
                                            <tipoEmision>' . $tip_emis . '</tipoEmision>
                                            <razonSocial>' . $nombre_empr . '</razonSocial>
                                            <nombreComercial>' . $nombre_empr . '</nombreComercial>
                                            <ruc>' . $ruc_empr . '</ruc>
                                            <claveAcceso>' . $clave_acceso . '</claveAcceso>
                                            <codDoc>01</codDoc>
                                            <estab>' . $estable . '</estab>
                                            <ptoEmi>' . $serie . '</ptoEmi>
                                            <secuencial>' . $fact_num_preimp . '</secuencial>
                                            <dirMatriz>' . $dir_empr . '</dirMatriz>
											' . $rimpe . '
                                        </infoTributaria>
                                        <infoFactura>
                                            <fechaEmision>' . $fec_fact . '</fechaEmision>
											<dirEstablecimiento>' . $dir_empr . '</dirEstablecimiento>';
				if ($empr_num_resu != '')
					$xml .= '<contribuyenteEspecial>' . $empr_num_resu . '</contribuyenteEspecial>';

				$xml .= '<obligadoContabilidad>' . $conta_sn . '</obligadoContabilidad>
									<comercioExterior>EXPORTADOR</comercioExterior>
									<incoTermFactura>' . $fact_term_expor . '</incoTermFactura>
									<lugarIncoTerm>QUITO</lugarIncoTerm>
									<paisOrigen>593</paisOrigen>';

				// PUERTO DE EMBARQUE
				$sql = "select  prto_cod_prto, prto_des_prto   from saeprto where
                                                prto_tip_prto = 'E'  and 
                                                prto_cod_prto = '$puerto_emb' ";
				$puerto_embarque = consulta_string_func($sql, 'prto_des_prto', $oIfx, '0');
				$xml .= '<puertoEmbarque>' . $puerto_embarque . '</puertoEmbarque>';
				// PUERTOD DESTINO
				$sql = "select  prto_cod_prto, prto_des_prto   from saeprto where
                                                prto_pais_prto = $pais and
                                                prto_cod_prto = '$puerto_dest' ";
				$puerto_destino = consulta_string_func($sql, 'prto_des_prto', $oIfx, '0');
				$xml .= '<puertoDestino>' . $puerto_destino . '</puertoDestino>';
				// PAIS DESTINO
				$sql = "select  pais_cod_inte from saepais where pais_cod_pais = $pais ";
				$pais_destino = consulta_string_func($sql, 'pais_cod_inte', $oIfx, 0);
				$xml .= '<paisDestino>' . $pais_destino . '</paisDestino>';
				$xml .= '<paisAdquisicion>593</paisAdquisicion>';

				$xml .= '<tipoIdentificacionComprador>' . $tipoIdentificacionComprador . '</tipoIdentificacionComprador>
                                        <razonSocialComprador>' . htmlspecialchars($fact_nom_cliente) . '</razonSocialComprador>
                                        <identificacionComprador>' . $fact_ruc_clie . '</identificacionComprador>
                                        <direccionComprador>' . $fact_dir_clie . '</direccionComprador> 
                                        <totalSinImpuestos>' . round($totalSinImpuestos, 2) . '</totalSinImpuestos>
                                        <incoTermTotalSinImpuestos>' . $fact_term_expor . '</incoTermTotalSinImpuestos>
                                        <totalDescuento>' . round($totalDescuento, 2) . '</totalDescuento>';
				$xml .= "<totalConImpuestos> ";
				if ($fact_con_miva != '') {
					$xml .= '<totalImpuesto>
                                         <codigo>2</codigo>
                                         <codigoPorcentaje>2</codigoPorcentaje>
                                         <baseImponible>' . round($fact_con_miva, 2) . '</baseImponible>
                                         <valor>' . round($fact_iva, 2) . '</valor>
                                     </totalImpuesto>';
				}
				if ($fact_sin_miva != '') {
					$xml .= '<totalImpuesto>
                                         <codigo>2</codigo>
                                         <codigoPorcentaje>0</codigoPorcentaje>
                                         <baseImponible>' . round($fact_sin_miva, 2) . '</baseImponible>
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
				//$oReturn->alert($baseImponibleIRBP);
				$xml .= "</totalConImpuestos> ";

				$xml .= '              <propina>0.00</propina>
										<fleteInternacional>' . round($flete, 2) . '</fleteInternacional>
                                        <seguroInternacional>' . round($otros, 2) . '</seguroInternacional>
                                        <gastosAduaneros>0.00</gastosAduaneros> 
                                        <gastosTransporteOtros>' . round($anticipo, 2) . '</gastosTransporteOtros> 
										<importeTotal>' . round(($fact_sin_miva + $fact_con_miva + $fact_iva + $flete + $otros + $anticipo), 2) . '</importeTotal>
                                     <moneda>DOLAR</moneda>
									 <pagos> 																			
                                        <pago> 																		
                                        <formaPago>' . $fp_expor . '</formaPago>
                                        <total>' . round(($fact_tot_fact + $fact_iva + $otros + $flete + $anticipo - $desc_valor), 2) . '</total>
                                        <plazo>' . $plazo . '</plazo>
                                        <unidadTiempo>dias</unidadTiempo>
                                        </pago> 				
                                     </pagos>
                                 </infoFactura>';
				$xml .= $xmlDeta;

				$aDataInfoAdic['Direccion'] = $fact_dir_clie;
				$aDataInfoAdic['Telefono'] = $fact_tlf_cliente;
				$aDataInfoAdic['Email'] = $fact_email_clpv;

				$etiqueta = array_keys($aDataInfoAdic);
				if (count($etiqueta) > 0) {
					$xml .= '<infoAdicional>';
					foreach ($etiqueta as $nom) {
						if ($aDataInfoAdic[$nom] != '')
							$xml .= "<campoAdicional nombre=\"$nom\">$aDataInfoAdic[$nom]</campoAdicional>";
					}
					$xml .= '</infoAdicional>';
				}

				$xml .= '</factura>';

				//$oReturn->alert($xml);
				// CREAR CARPETA ANEXO
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


				$sql_fact = "where fpak_cod_empr = $id_empresa and fpak_cod_clpv = $fact_cod_clpv and fpak_cod_fpak = $id_factura ";


				unset($_SESSION['id']);
				$_SESSION['id'] = $id_factura;

				$_SESSION['contrRepor'] = $clave_acceso;
				$rete_cod_clpv = 0;
				$ret_num_fact = '';
				$asto_cod_ejer = 0;
				$rete_cod_asto = '';
				$fprv_fec_emis = '';
				$oReturn->script("firmar('$nombre','$clave_acceso','$ruc_empr', '$fact_email_clpv', 'factura_expor_flor', '$sql_fact', $id_factura,
                                         $rete_cod_clpv, '$ret_num_fact', '$asto_cod_ejer', '$rete_cod_asto', '$fprv_fec_emis', 9 )");
			} // fin check
		} // fin foreach
	} else {
		$oReturn->alert('Por favor realice una Busqueda...');
	}

	//    $_SESSION['aDataGirdErro'] = $array;
	//    $oReturn->script("reporte();");
	return $oReturn;
}

// ENVIAR DOCUMENTOS
function enviar_factVent($aForm = '')
{

	global $DSN_Ifx, $DSN;
	if (session_status() !== PHP_SESSION_ACTIVE) {
		session_start();
	}

	$oReturn = new xajaxResponse();

	$oIfx = new Dbo;
	$oIfx->DSN = $DSN_Ifx;
	$oIfx->Conectar();

	$oIfx1 = new Dbo;
	$oIfx1->DSN = $DSN_Ifx;
	$oIfx1->Conectar();

	//VARIABLES GLOBALES
	$id_empresa = $_SESSION['U_EMPRESA'];
	$array_fact = $_SESSION['U_FACT_ENVIO'];


	unset($_SESSION['aDataGirdErro']);
	unset($array);

	// DATOS
	if (count($array_fact) > 0) {
		foreach ($array_fact as $val) {
			$id_factura = $val[0];
			$check = $aForm[$id_factura . '_f'];

			$xml = '';
			$xmlDeta = '';
			$descuento = 0;

			if (!empty($check)) {
				//COMSULTAMOS LOS DATOS Y FORMAMOS LOS XML
				$num8 = 12345678;
				$fact_fech_fact = $val[11];
				$fact_num_preimp = $val[1];
				$fact_nse_fact = $val[12];
				$fact_cod_clpv = $val[13];
				$fact_email_clpv = $val[8];
				$id_sucursal = $val[19];
				$clave_acceso = $val[21];

				$sql_fact = " where fact_cod_empr = $id_empresa and fact_cod_sucu = $id_sucursal and fact_cod_clpv = $fact_cod_clpv and fact_cod_fact = $id_factura ";

				unset($_SESSION['id']);
				$_SESSION['id'] = $id_factura;

				$ret_num_fact = '';
				$asto_cod_ejer = 0;
				$rete_cod_asto = '';
				$fprv_fec_emis = '';

				$oReturn->script("autorizaComprobante('$clave_acceso', '$fact_email_clpv' , 'factura_venta', '$sql_fact', $id_factura, $fact_cod_clpv,  '$ret_num_fact',  $asto_cod_ejer,  '$rete_cod_asto',  '$fprv_fec_emis', $id_sucursal )");
			} // fin check
		} // fin foreach
	} else {
		$oReturn->alert('Por favor realice una Busqueda...');
	}

	return $oReturn;
}

function enviar_notaDebi($aForm = '')
{

	global $DSN_Ifx, $DSN;
	if (session_status() !== PHP_SESSION_ACTIVE) {
		session_start();
	}

	$oReturn = new xajaxResponse();

	$oIfx = new Dbo;
	$oIfx->DSN = $DSN_Ifx;
	$oIfx->Conectar();

	$oIfx1 = new Dbo;
	$oIfx1->DSN = $DSN_Ifx;
	$oIfx1->Conectar();

	//VARIABLES GLOBALES
	$id_empresa = $_SESSION['U_EMPRESA'];
	$id_sucursal = $_SESSION['U_SUCURSAL'];
	$array_fact = $_SESSION['U_FACT_ENVIO'];

	unset($_SESSION['aDataGirdErro']);
	unset($array);

	$sqlTipo = "select  sucu_tip_ambi, sucu_tip_emis  from saesucu where
                    sucu_cod_sucu = $id_sucursal ";

	if ($oIfx->Query($sqlTipo)) {
		if ($oIfx->NumFilas() > 0) {
			$ambiente = $oIfx->f('sucu_tip_ambi');
			$tip_emis = $oIfx->f('sucu_tip_emis');
		}
	}
	$oIfx->Free();

	$sql = "select empr_nom_empr, empr_ruc_empr , empr_dir_empr, empr_conta_sn,
                empr_num_resu from saeempr where empr_cod_empr = $id_empresa ";

	if ($oIfx->Query($sql)) {
		if ($oIfx->NumFilas() > 0) {
			$ruc_empr = $oIfx->f('empr_ruc_empr');
		}
	}
	$oIfx->Free();

	// DATOS
	if (count($array_fact) > 0) {
		foreach ($array_fact as $val) {
			$id_factura = $val[0];
			$check = $aForm[$id_factura . '_nd'];

			$xml = '';
			$xmlDeta = '';
			$descuento = 0;
			//            $oReturn->alert($check);
			if (!empty($check)) {
				//COMSULTAMOS LOS DATOS Y FORMAMOS LOS XML
				$num8 = 12345678;
				$fact_fech_fact = $val[11];
				$fact_num_preimp = $val[1];
				$fact_nse_fact = $val[12];
				$fact_cod_clpv = $val[13];
				$fact_email_clpv = $val[8];

				//$fec_xml = fecha_clave($fact_fech_fact);
				[$ano, $mes, $dia] = preg_split("/(\-|\/)/", $fact_fech_fact);
				$fec_xml = $dia . $mes . $ano;

				$clave_acceso = $fec_xml . '05' . $ruc_empr . $ambiente . $fact_nse_fact . $fact_num_preimp . $num8 . $tip_emis;
				$digitoVerificador = digitoVerificador($clave_acceso);
				$clave_acceso = $clave_acceso . $digitoVerificador;

				$sql_fact = "where fact_cod_empr = $id_empresa and fact_cod_sucu = $id_sucursal   and fact_cod_clpv = $fact_cod_clpv and fact_cod_fact = $id_factura ";

				unset($_SESSION['id']);
				$_SESSION['id'] = $id_factura;

				$ret_num_fact = '';
				$asto_cod_ejer = 0;
				$rete_cod_asto = '';
				$fprv_fec_emis = '';
				//              $oReturn->script("firmar('$nombre','$clave_acceso','$ruc_empr', '$fact_email_clpv', 'factura_venta', '$sql_fact', $id_factura,
				//                                          $fact_cod_clpv, '$ret_num_fact', '$asto_cod_ejer', '$rete_cod_asto', '$fprv_fec_emis', 1  )");

				$oReturn->script("autorizaComprobante('$clave_acceso', '$fact_email_clpv' , 'nota_debito', '$sql_fact', $id_factura, $fact_cod_clpv,  '$ret_num_fact',  $asto_cod_ejer,  '$rete_cod_asto',  '$fprv_fec_emis' )");
			} // fin check
		} // fin foreach
	} else {
		$oReturn->alert('Por favor realice una Busqueda...');
	}

	return $oReturn;
}

function enviar_notaCred($aForm = '')
{

	global $DSN_Ifx, $DSN;
	if (session_status() !== PHP_SESSION_ACTIVE) {
		session_start();
	}

	$oReturn = new xajaxResponse();

	$oIfx = new Dbo;
	$oIfx->DSN = $DSN_Ifx;
	$oIfx->Conectar();

	$oIfx1 = new Dbo;
	$oIfx1->DSN = $DSN_Ifx;
	$oIfx1->Conectar();

	//VARIABLES GLOBALES
	$id_empresa = $_SESSION['U_EMPRESA'];
	$array_ncre = $_SESSION['U_FACT_ENVIO'];

	unset($_SESSION['aDataGirdErro']);
	unset($array);

	//OTROS DATOS SRI
	$codDoc = '04';

	// DATOS
	if (count($array_ncre) > 0) {
		foreach ($array_ncre as $val) {
			$id_ncre = $val[0];
			$check = $aForm[$id_ncre . '_n'];

			$xml = '';
			$xmlDeta = '';
			$descuento = 0;
			if (!empty($check)) {
				//COMSULTAMOS LOS DATOS Y FORMAMOS LOS XML
				$num8 = 12345678;
				$ncre_fech_fact = $val[11];
				$ncre_num_preimp = secuencialSri($val[1]);
				$ncre_nse_ncre = $val[12];
				$ncre_cod_clpv = $val[13];
				$ncre_email_clpv = $val[8];
				$id_sucursal = $val[21];
				$claveAcceso = $val[22];

				$sql_ncre = "where ncre_cod_empr = $id_empresa and ncre_cod_sucu = $id_sucursal and ncre_cod_clpv = $ncre_cod_clpv and ncre_cod_ncre = $id_ncre ";

				$rete_cod_clpv = 0;
				$ret_num_fact = '';
				$asto_cod_ejer = 0;
				$rete_cod_asto = '';
				$fprv_fec_emis = '';

				$oReturn->script("autorizaComprobante('$claveAcceso', '$ncre_email_clpv' , 'nota_credito', '$sql_ncre', $id_ncre, $ncre_cod_clpv,  '$ret_num_fact',  $asto_cod_ejer,  '$rete_cod_asto',  '$fprv_fec_emis', $id_sucursal)");
			} // fin check
		} // fin foreach
	} else {
		$oReturn->alert('Por favor realice una Busqueda...');
	}

	return $oReturn;
}

function enviar_reteGast($aForm = '')
{

	global $DSN_Ifx, $DSN;
	if (session_status() !== PHP_SESSION_ACTIVE) {
		session_start();
	}

	$oReturn = new xajaxResponse();

	$oIfx = new Dbo;
	$oIfx->DSN = $DSN_Ifx;
	$oIfx->Conectar();

	$oIfx1 = new Dbo;
	$oIfx1->DSN = $DSN_Ifx;
	$oIfx1->Conectar();

	//VARIABLES GLOBALES
	$id_empresa = $_SESSION['U_EMPRESA'];
	$array_gasto = $_SESSION['U_FACT_ENVIO'];

	unset($_SESSION['aDataGirdErro']);
	unset($array);

	//OTROS DATOS SRI
	$codDoc = '07';

	if (count($array_gasto) > 0) {
		foreach ($array_gasto as $val) {
			$rete_cod_clpv = $val[0];
			$ret_num_ret = $val[8];
			$ret_num_fact = $val[6];
			$asto_cod_ejer = $val[5];
			$id_sucursal = $val[14];
			$serial = $rete_cod_clpv . '_' . $ret_num_ret . '_' . $asto_cod_ejer . '_' . $ret_num_fact . '_' . $id_empresa . '_' . $id_sucursal . '_rg';
			$check = $aForm[$serial];

			$xml = '';
			$xmlImpu = '';
			$descuento = 0;
			if (!empty($check)) {
				//COMSULTAMOS LOS DATOS Y FORMAMOS LOS XML
				$num8 = 12345678;
				$fprv_fec_emis = $val[4];
				$ret_email_clpv = $val[3];
				$rete_cod_asto = $val[13];
				$ret_ser_ret = $val[9];
				$claveAcceso = $val[15];

				$sql_gasto = '';
				$id = 0;

				$oReturn->script("autorizaComprobante('$claveAcceso', '$ret_email_clpv' , 'retencion_gasto', '$sql_gasto', $id, 
                                                       $rete_cod_clpv,  '$ret_num_fact',  $asto_cod_ejer,  '$rete_cod_asto',  '$fprv_fec_emis', $id_sucursal)");
			} // fin check
		} // fin foreach
	} else {
		$oReturn->alert('Por favor realice una Busqueda...');
	}

	return $oReturn;
}

function enviar_reteInve($aForm = '')
{

	global $DSN_Ifx, $DSN;
	if (session_status() !== PHP_SESSION_ACTIVE) {
		session_start();
	}

	$oReturn = new xajaxResponse();

	$oIfx = new Dbo;
	$oIfx->DSN = $DSN_Ifx;
	$oIfx->Conectar();

	$oIfx1 = new Dbo;
	$oIfx1->DSN = $DSN_Ifx;
	$oIfx1->Conectar();

	//VARIABLES GLOBALES
	$id_empresa = $_SESSION['U_EMPRESA'];
	$array_compra = $_SESSION['U_FACT_ENVIO'];

	unset($_SESSION['aDataGirdErro']);
	unset($array);

	//OTROS DATOS SRI
	$codDoc = '07';
	$codDocSustento = '01';

	if (count($array_compra) > 0) {
		foreach ($array_compra as $val) {
			$rete_cod_clpv = $val[0];
			$ret_num_ret = $val[8];
			$ret_num_fact = $val[6];
			$asto_cod_ejer = $val[5];
			$minv_num_comp = $val[14];
			$id_sucursal = $val[15];
			$serial = $minv_num_comp . '_rc';
			$check = $aForm[$serial];

			$xml = '';
			$xmlImpu = '';
			$descuento = 0;
			if (!empty($check)) {
				//CONSULTAMOS LOS DATOS Y FORMAMOS LOS XML
				$minv_fmov = $val[4];
				$minv_fmov_tmp = $val[4];
				$ret_num_ret = $val[8];
				$ret_num_fact = $val[6];
				$rete_cod_asto = $val[13];
				$rete_cod_clpv = $val[0];
				$asto_cod_ejer = $val[5];
				$ret_email_clpv = $val[3];
				$ret_ser_ret = $val[9];
				$claveAcceso = $val[16];

				$sql_gasto = '';
				$id = 0;

				$oReturn->script("autorizaComprobante('$claveAcceso', '$ret_email_clpv' , 'retencion_inventario', '$sql_gasto', $id, 
                                                       $rete_cod_clpv,  '$ret_num_fact',  $asto_cod_ejer,  '$rete_cod_asto',  '$minv_fmov_tmp', $id_sucursal )");
			} // fin check
		} // fin foreach
	} else {
		$oReturn->alert('Por favor realice una Busqueda...');
	}

	return $oReturn;
}

function enviar_guiaRemi($aForm = '')
{

	global $DSN_Ifx, $DSN;
	if (session_status() !== PHP_SESSION_ACTIVE) {
		session_start();
	}

	$oReturn = new xajaxResponse();

	$oIfx = new Dbo;
	$oIfx->DSN = $DSN_Ifx;
	$oIfx->Conectar();

	$oIfx1 = new Dbo;
	$oIfx1->DSN = $DSN_Ifx;
	$oIfx1->Conectar();

	$oIfx2 = new Dbo;
	$oIfx2->DSN = $DSN_Ifx;
	$oIfx2->Conectar();

	//VARIABLES GLOBALES
	$id_empresa = $_SESSION['U_EMPRESA'];
	$array_guia = $_SESSION['U_FACT_ENVIO'];

	unset($_SESSION['aDataGirdErro']);
	unset($array);

	//OTROS DATOS SRI
	$codDoc = '06';

	if (count($array_guia) > 0) {
		foreach ($array_guia as $val) {
			$id_guia = $val[0];
			$check = $aForm[$id_guia . '_guia'];

			$xml = '';
			$xmlDeta = '';
			$descuento = 0;
			if (!empty($check)) {
				//COMSULTAMOS LOS DATOS Y FORMAMOS LOS XML
				$num8 = 12345678;
				$guia_cod_clpv = $val[1];
				$guia_nom_cliente = $val[2];
				$guia_tlf_cliente = $val[3];
				$guia_dir_clie = $val[4];
				$guia_ruc_clie = $val[5];
				$tipoIdentificacionComprador = $val[6];
				$guia_fech_guia = $val[7];
				$guia_hos_guia = $val[8];
				$guia_hol_guia = $val[9];
				$guia_num_preimp = $val[10];
				$guia_num_plac = $val[11];
				$guia_cm3_guia = $val[12];
				$guia_email_clpv = $val[13];
				$guia_nse_guia = $val[14];
				$nom_trta = $val[15];
				$cid_trta = $val[16];
				$tipoIdentificacionTransportista = $val[17];
				$id_sucursal = $val[18];
				$clave_acceso = $val[19];

				$sql_guia = "where guia_cod_empr = $id_empresa and guia_cod_sucu = $id_sucursal and guia_cod_clpv = $guia_cod_clpv and guia_cod_guia = $id_guia ";

				$asto_cod_ejer = 0;
				$rete_cod_asto = '';

				$oReturn->script("autorizaComprobante('$clave_acceso', '$guia_email_clpv' , 'guia_remision', '$sql_guia', $id_guia, 
                                                       $guia_cod_clpv,  '$guia_num_preimp',  $asto_cod_ejer,  '$rete_cod_asto',  '$guia_fech_guia', $id_sucursal )");
			} // fin check
		} // fin foreach
	} else {
		$oReturn->alert('Por favor realice una Busqueda...');
	}

	return $oReturn;
}

function enviar_guiaRemiFlor($aForm = '')
{

	global $DSN_Ifx, $DSN;
	if (session_status() !== PHP_SESSION_ACTIVE) {
		session_start();
	}

	$oReturn = new xajaxResponse();

	$oIfx = new Dbo;
	$oIfx->DSN = $DSN_Ifx;
	$oIfx->Conectar();

	$oIfx1 = new Dbo;
	$oIfx1->DSN = $DSN_Ifx;
	$oIfx1->Conectar();

	//VARIABLES GLOBALES
	$id_empresa = $_SESSION['U_EMPRESA'];
	$id_sucursal = $_SESSION['U_SUCURSAL'];
	$array_guia = $_SESSION['U_FACT_ENVIO'];

	unset($_SESSION['aDataGirdErro']);
	unset($array);

	$sqlTipo = "select  sucu_tip_ambi, sucu_tip_emis  from saesucu where
                    sucu_cod_sucu = $id_sucursal ";

	if ($oIfx->Query($sqlTipo)) {
		if ($oIfx->NumFilas() > 0) {
			$ambiente = $oIfx->f('sucu_tip_ambi');
			$tip_emis = $oIfx->f('sucu_tip_emis');
		}
	}
	$oIfx->Free();

	$sql = "select empr_nom_empr, empr_ruc_empr , empr_dir_empr, empr_conta_sn,
                empr_num_resu from saeempr where empr_cod_empr = $id_empresa ";

	if ($oIfx->Query($sql)) {
		if ($oIfx->NumFilas() > 0) {
			$ruc_empr = $oIfx->f('empr_ruc_empr');
		}
	}
	$oIfx->Free();

	// DATOS
	if (count($array_guia) > 0) {
		foreach ($array_guia as $val) {
			$id_guia = $val[0];
			$check = $aForm[$id_guia . '_guia'];

			$xml = '';
			$xmlDeta = '';
			$descuento = 0;
			//            $oReturn->alert($check);
			if (!empty($check)) {
				//COMSULTAMOS LOS DATOS Y FORMAMOS LOS XML
				$num8 = 12345678;
				$guia_fech_guia = $val[7];
				$guia_num_preimp = $val[10];
				$guia_nse_guia = $val[14];
				$guia_cod_clpv = $val[1];
				$guia_email_clpv = $val[13];

				$fec_xml = fecha_clave($guia_fech_guia);

				$clave_acceso = $fec_xml . '01' . $ruc_empr . $ambiente . $guia_nse_guia . $guia_num_preimp . $num8 . $tip_emis;
				$digitoVerificador = digitoVerificador($clave_acceso);
				$clave_acceso = $clave_acceso . $digitoVerificador;

				$sql_fact = "where gref_cod_empr = $id_empresa and gref_cod_clpv = $guia_cod_clpv and gref_cod_gref = $id_guia";

				unset($_SESSION['id']);
				$_SESSION['id'] = $id_guia;

				$ret_num_fact = '';
				$asto_cod_ejer = 0;
				$rete_cod_asto = '';
				$fprv_fec_emis = '';

				$oReturn->script("autorizaComprobante('$clave_acceso', '$guia_email_clpv' , 'guia_remision_flor', '$sql_fact', $id_guia, $guia_cod_clpv,  '$ret_num_fact',  $asto_cod_ejer,  '$rete_cod_asto',  '$fprv_fec_emis' )");
			} // fin check
		} // fin foreach
	} else {
		$oReturn->alert('Por favor realice una Busqueda...');
	}

	return $oReturn;
}

function enviar_factExpor($aForm = '')
{

	global $DSN_Ifx, $DSN;
	if (session_status() !== PHP_SESSION_ACTIVE) {
		session_start();
	}

	$oReturn = new xajaxResponse();

	$oIfx = new Dbo;
	$oIfx->DSN = $DSN_Ifx;
	$oIfx->Conectar();

	$oIfx1 = new Dbo;
	$oIfx1->DSN = $DSN_Ifx;
	$oIfx1->Conectar();

	//VARIABLES GLOBALES
	$id_empresa = $_SESSION['U_EMPRESA'];
	$id_sucursal = $_SESSION['U_SUCURSAL'];
	$array_fact = $_SESSION['U_FACT_ENVIO'];

	unset($_SESSION['aDataGirdErro']);
	unset($array);

	$sqlTipo = "select  sucu_tip_ambi, sucu_tip_emis  from saesucu where
                    sucu_cod_sucu = $id_sucursal ";

	if ($oIfx->Query($sqlTipo)) {
		if ($oIfx->NumFilas() > 0) {
			$ambiente = $oIfx->f('sucu_tip_ambi');
			$tip_emis = $oIfx->f('sucu_tip_emis');
		}
	}
	$oIfx->Free();

	$sql = "select empr_nom_empr, empr_ruc_empr , empr_dir_empr, empr_conta_sn,
                empr_num_resu from saeempr where empr_cod_empr = $id_empresa ";

	if ($oIfx->Query($sql)) {
		if ($oIfx->NumFilas() > 0) {
			$ruc_empr = $oIfx->f('empr_ruc_empr');
		}
	}
	$oIfx->Free();

	// DATOS
	if (count($array_fact) > 0) {
		foreach ($array_fact as $val) {
			$id_factura = $val[0];
			$check = $aForm[$id_factura . '_f'];

			$xml = '';
			$xmlDeta = '';
			$descuento = 0;
			//            $oReturn->alert($check);
			if (!empty($check)) {
				//COMSULTAMOS LOS DATOS Y FORMAMOS LOS XML
				$num8 = 12345678;
				$fact_fech_fact = $val[11];
				$fact_num_preimp = $val[1];
				$fact_nse_fact = $val[12];
				$fact_cod_clpv = $val[13];
				$fact_email_clpv = $val[8];
				$clave_acceso = $val[28];
				$id_sucursal = $val[29];

				$sql_fact = "where fact_cod_empr = $id_empresa and fact_cod_sucu = $id_sucursal   and fact_cod_clpv = $fact_cod_clpv and fact_cod_fact = $id_factura ";

				unset($_SESSION['id']);
				$_SESSION['id'] = $id_factura;

				$ret_num_fact = '';
				$asto_cod_ejer = 0;
				$rete_cod_asto = '';
				$fprv_fec_emis = '';
				//              $oReturn->script("firmar('$nombre','$clave_acceso','$ruc_empr', '$fact_email_clpv', 'factura_venta', '$sql_fact', $id_factura,
				//                                          $fact_cod_clpv, '$ret_num_fact', '$asto_cod_ejer', '$rete_cod_asto', '$fprv_fec_emis', 1  )");

				$oReturn->script("autorizaComprobante('$clave_acceso', '$fact_email_clpv' , 'factura_expor', '$sql_fact', $id_factura, $fact_cod_clpv,  '$ret_num_fact',  $asto_cod_ejer,  '$rete_cod_asto',  '$fprv_fec_emis' )");
			} // fin check
		} // fin foreach
	} else {
		$oReturn->alert('Por favor realice una Busqueda...');
	}

	return $oReturn;
}

function enviar_factFlor($aForm = '')
{

	global $DSN_Ifx, $DSN;
	if (session_status() !== PHP_SESSION_ACTIVE) {
		session_start();
	}

	$oReturn = new xajaxResponse();

	$oIfx = new Dbo;
	$oIfx->DSN = $DSN_Ifx;
	$oIfx->Conectar();

	$oIfx1 = new Dbo;
	$oIfx1->DSN = $DSN_Ifx;
	$oIfx1->Conectar();

	//VARIABLES GLOBALES
	$id_empresa = $_SESSION['U_EMPRESA'];
	$id_sucursal = $_SESSION['U_SUCURSAL'];
	$array_fact = $_SESSION['U_FACT_ENVIO'];

	unset($_SESSION['aDataGirdErro']);
	unset($array);

	$sqlTipo = "select  sucu_tip_ambi, sucu_tip_emis  from saesucu where
                    sucu_cod_sucu = $id_sucursal ";

	if ($oIfx->Query($sqlTipo)) {
		if ($oIfx->NumFilas() > 0) {
			$ambiente = $oIfx->f('sucu_tip_ambi');
			$tip_emis = $oIfx->f('sucu_tip_emis');
		}
	}
	$oIfx->Free();

	$sql = "select empr_nom_empr, empr_ruc_empr , empr_dir_empr, empr_conta_sn,
                empr_num_resu from saeempr where empr_cod_empr = $id_empresa ";

	if ($oIfx->Query($sql)) {
		if ($oIfx->NumFilas() > 0) {
			$ruc_empr = $oIfx->f('empr_ruc_empr');
		}
	}
	$oIfx->Free();

	// DATOS
	if (count($array_fact) > 0) {
		foreach ($array_fact as $val) {
			$id_factura = $val[0];
			$check = $aForm[$id_factura . '_f'];

			$xml = '';
			$xmlDeta = '';
			$descuento = 0;
			//            $oReturn->alert($check);
			if (!empty($check)) {
				//COMSULTAMOS LOS DATOS Y FORMAMOS LOS XML
				$num8 = 12345678;
				$fact_fech_fact = $val[11];
				$fact_num_preimp = $val[1];
				$fact_nse_fact = $val[12];
				$fact_cod_clpv = $val[13];
				$fact_email_clpv = $val[8];

				$fec_xml = fecha_clave($fact_fech_fact);

				$clave_acceso = $fec_xml . '01' . $ruc_empr . $ambiente . $fact_nse_fact . $fact_num_preimp . $num8 . $tip_emis;
				$digitoVerificador = digitoVerificador($clave_acceso);
				$clave_acceso = $clave_acceso . $digitoVerificador;

				$sql_fact = "where fpak_cod_empr = $id_empresa and fpak_cod_clpv = $fact_cod_clpv and fpak_cod_fpak = $id_factura ";

				unset($_SESSION['id']);
				$_SESSION['id'] = $id_factura;

				$ret_num_fact = '';
				$asto_cod_ejer = 0;
				$rete_cod_asto = '';
				$fprv_fec_emis = '';
				//              $oReturn->script("firmar('$nombre','$clave_acceso','$ruc_empr', '$fact_email_clpv', 'factura_venta', '$sql_fact', $id_factura,
				//                                          $fact_cod_clpv, '$ret_num_fact', '$asto_cod_ejer', '$rete_cod_asto', '$fprv_fec_emis', 1  )");

				$oReturn->script("autorizaComprobante('$clave_acceso', '$fact_email_clpv' , 'factura_flor', '$sql_fact', $id_factura, $fact_cod_clpv,  '$ret_num_fact',  $asto_cod_ejer,  '$rete_cod_asto',  '$fprv_fec_emis' )");
			} // fin check
		} // fin foreach
	} else {
		$oReturn->alert('Por favor realice una Busqueda...');
	}

	return $oReturn;
}

function enviar_factFlorExpor($aForm = '')
{

	global $DSN_Ifx, $DSN;
	if (session_status() !== PHP_SESSION_ACTIVE) {
		session_start();
	}

	$oReturn = new xajaxResponse();

	$oIfx = new Dbo;
	$oIfx->DSN = $DSN_Ifx;
	$oIfx->Conectar();

	$oIfx1 = new Dbo;
	$oIfx1->DSN = $DSN_Ifx;
	$oIfx1->Conectar();

	//VARIABLES GLOBALES
	$id_empresa = $_SESSION['U_EMPRESA'];
	$id_sucursal = $_SESSION['U_SUCURSAL'];
	$array_fact = $_SESSION['U_FACT_ENVIO'];

	unset($_SESSION['aDataGirdErro']);
	unset($array);

	$sqlTipo = "select  sucu_tip_ambi, sucu_tip_emis  from saesucu where
                    sucu_cod_sucu = $id_sucursal ";

	if ($oIfx->Query($sqlTipo)) {
		if ($oIfx->NumFilas() > 0) {
			$ambiente = $oIfx->f('sucu_tip_ambi');
			$tip_emis = $oIfx->f('sucu_tip_emis');
		}
	}
	$oIfx->Free();

	$sql = "select empr_nom_empr, empr_ruc_empr , empr_dir_empr, empr_conta_sn,
                empr_num_resu from saeempr where empr_cod_empr = $id_empresa ";

	if ($oIfx->Query($sql)) {
		if ($oIfx->NumFilas() > 0) {
			$ruc_empr = $oIfx->f('empr_ruc_empr');
		}
	}
	$oIfx->Free();

	// DATOS
	if (count($array_fact) > 0) {
		foreach ($array_fact as $val) {
			$id_factura = $val[0];
			$check = $aForm[$id_factura . '_f'];

			$xml = '';
			$xmlDeta = '';
			$descuento = 0;
			//            $oReturn->alert($check);
			if (!empty($check)) {
				//COMSULTAMOS LOS DATOS Y FORMAMOS LOS XML
				$num8 = 12345678;
				$fact_fech_fact = $val[11];
				$fact_num_preimp = $val[1];
				$fact_nse_fact = $val[12];
				$fact_cod_clpv = $val[13];
				$fact_email_clpv = $val[8];

				$fec_xml = fecha_clave($fact_fech_fact);

				$clave_acceso = $fec_xml . '01' . $ruc_empr . $ambiente . $fact_nse_fact . $fact_num_preimp . $num8 . $tip_emis;
				$digitoVerificador = digitoVerificador($clave_acceso);
				$clave_acceso = $clave_acceso . $digitoVerificador;

				$sql_fact = "where fpak_cod_empr = $id_empresa and fpak_cod_clpv = $fact_cod_clpv and fpak_cod_fpak = $id_factura";

				unset($_SESSION['id']);
				$_SESSION['id'] = $id_factura;

				$ret_num_fact = '';
				$asto_cod_ejer = 0;
				$rete_cod_asto = '';
				$fprv_fec_emis = '';
				//              $oReturn->script("firmar('$nombre','$clave_acceso','$ruc_empr', '$fact_email_clpv', 'factura_venta', '$sql_fact', $id_factura,
				//                                          $fact_cod_clpv, '$ret_num_fact', '$asto_cod_ejer', '$rete_cod_asto', '$fprv_fec_emis', 1  )");

				$oReturn->script("autorizaComprobante('$clave_acceso', '$fact_email_clpv' , 'factura_expor_flor', '$sql_fact', $id_factura, $fact_cod_clpv,  '$ret_num_fact',  $asto_cod_ejer,  '$rete_cod_asto',  '$fprv_fec_emis' )");
			} // fin check
		} // fin foreach
	} else {
		$oReturn->alert('Por favor realice una Busqueda...');
	}

	return $oReturn;
}

function enviar_liquCompras($aForm = '')
{

	global $DSN_Ifx, $DSN;
	if (session_status() !== PHP_SESSION_ACTIVE) {
		session_start();
	}

	$oReturn = new xajaxResponse();

	$oIfx = new Dbo;
	$oIfx->DSN = $DSN_Ifx;
	$oIfx->Conectar();

	$oIfx1 = new Dbo;
	$oIfx1->DSN = $DSN_Ifx;
	$oIfx1->Conectar();

	//VARIABLES GLOBALES
	$id_empresa = $_SESSION['U_EMPRESA'];
	$id_sucursal = $_SESSION['U_SUCURSAL'];
	$array_fact = $_SESSION['U_FACT_ENVIO'];
	//var_dump($array_fact); exit;
	unset($_SESSION['aDataGirdErro']);
	unset($array);

	// DATOS
	if (count($array_fact) > 0) {
		foreach ($array_fact as $val) {
			$id_factura = $val[0];
			$check = $aForm[$id_factura . '_f'];

			$xml = '';
			$xmlDeta = '';
			$descuento = 0;

			if (!empty($check)) {
				//COMSULTAMOS LOS DATOS Y FORMAMOS LOS XML
				$num8 = 12345678;
				$fact_fech_fact = $val[6];
				$fact_num_preimp = $val[0];
				$fact_nse_fact = $val[7];
				$fact_cod_clpv = $val[8];
				$fact_email_clpv = $val[3];
				//$id_sucursal = $val[19];
				$clave_acceso = $val[11];
				$id_factura = $val[18];
				$sql_fact = " where minv_cod_empr = $id_empresa and minv_cod_sucu = $id_sucursal and minv_cod_clpv = $fact_cod_clpv and minv_num_comp = $id_factura ";
				//echo $sql_fact; exit;
				unset($_SESSION['id']);
				$_SESSION['id'] = $id_factura;

				$ret_num_fact = '';
				$asto_cod_ejer = 0;
				$rete_cod_asto = '';
				$fprv_fec_emis = '';;

				$oReturn->script("autorizaComprobante('$clave_acceso', '$fact_email_clpv' , 'liqu_compras', '$sql_fact', $id_factura, $fact_cod_clpv,  '$ret_num_fact',  $asto_cod_ejer,  '$rete_cod_asto',  '$fprv_fec_emis', $id_sucursal )");
			} // fin check
		} // fin foreach
	} else {
		$oReturn->alert('Por favor realice una Busqueda...');
	}

	return $oReturn;
}

//METODOS SRI

function updateError($tipoDocu, $error, $clave_acceso, $sql_tmp, $clpv, $num_fact, $ejer, $asto, $fec_emis, $id_sucursal)
{

	global $DSN, $DSN_Ifx;
	if (session_status() !== PHP_SESSION_ACTIVE) {
		session_start();
	}

	$oIfx = new Dbo;
	$oIfx->DSN = $DSN_Ifx;
	$oIfx->Conectar();

	$sqlUPdate = $_SESSION['sqlUpdate'];
	$id_empresa = $_SESSION['U_EMPRESA'];

	switch ($tipoDocu) {
		case 'factura_venta':
			$sqlError = "update saefact set fact_erro_sri = '" . $error . "' " . $sql_tmp;
			break;
		case 'nota_debito':
			$sqlError = "update saefact set fact_erro_sri = '" . $error . "' " . $sql_tmp;
			break;
		case 'nota_credito':
			$sqlError = "update saencre set ncre_erro_sri = '" . $error . "' " . $sql_tmp;
			break;
		case 'guia_remision':
			$sqlError = "update saeguia set guia_erro_sri = '" . $error . "' " . $sql_tmp;
			break;
		case 'retencion_gasto':
			$sqlError = "update saefprv set fprv_erro_sri = '$error' where 
                                    fprv_cod_empr = $id_empresa and 
                                    fprv_cod_sucu = $id_sucursal  and 
                                    fprv_cod_clpv = $clpv and 
                                    fprv_num_fact = '$num_fact' and 
                                    fprv_cod_ejer = '$ejer' and  
                                    fprv_cod_asto = '$asto' and  
                                    fprv_fec_emis = '$fec_emis' ";
			break;
		case 'retencion_inventario':
			$sqlError = "update saeminv set minv_erro_sri = '$error' where 
                                    minv_cod_empr = $id_empresa and 
                                    minv_cod_sucu = $id_sucursal  and 
                                    minv_cod_clpv = $clpv and 
                                    minv_fac_prov = '$num_fact' and 
                                    minv_cod_ejer = '$ejer' and  
                                    minv_comp_cont = '$asto' and  
                                    minv_fmov      = '$fec_emis' ";
			break;
		case 'factura_expor':
			$sqlError = "update saefact set fact_erro_sri = '" . $error . "' " . $sql_tmp;
			break;
		case 'factura_flor':
			$sqlError = "update saefpak set fpak_erro_sri = '" . $error . "' " . $sql_tmp;
			break;
		case 'factura_expor_flor':
			$sqlError = "update saefpak set fpak_erro_sri = '" . $error . "' " . $sql_tmp;
			break;
		case 'guia_remision_flor':
			$sqlError = "update saegref set gref_erro_sri = '" . $error . "' " . $sql_tmp;
			break;
		case 'liqu_compras':
			$sqlError = "update saeminv set minv_erro_sri = '" . $error . "' " . $sql_tmp;
			break;
	}

	return $sqlError;
}

function updateComprobanteFirmado($claveAcceso = '', $tipoDocu = '', $numeroAutorizacion = '', $fechaAutorizacion = '', $sql_tmp, $clpv, $num_fact, $ejer, $asto, $fec_emis, $id_sucursal, $estadoSRI)
{
	global $DSN, $DSN_Ifx;
	if (session_status() !== PHP_SESSION_ACTIVE) {
		session_start();
	}

	$oIfx = new Dbo;
	$oIfx->DSN = $DSN_Ifx;
	$oIfx->Conectar();

	$user_sri = $_SESSION['U_ID'];
	$sqlUPdate = $_SESSION['sqlUpdate'];
	$id_empresa = $_SESSION['U_EMPRESA'];

	$oReturn = new xajaxResponse();

	switch ($tipoDocu) {
		case 'factura_venta':
			$sqlUpdaComp = "update saefact set 
                                fact_aprob_sri = '$estadoSRI',
                                fact_auto_sri = '$numeroAutorizacion',
                                fact_user_sri = $user_sri,
                                fact_fech_sri = '$fechaAutorizacion',
                                fact_erro_sri = '',
                                fact_clav_sri  = '$claveAcceso'" . $sql_tmp;
			break;
		case 'nota_debito':
			$sqlUpdaComp = "update saefact set 
                                fact_aprob_sri = '$estadoSRI',
                                fact_auto_sri = '$numeroAutorizacion',
                                fact_user_sri = $user_sri,
                                fact_fech_sri = '$fechaAutorizacion',
                                fact_erro_sri = '',    
                                fact_clav_sri  = '$claveAcceso'" . $sql_tmp;
			break;
		case 'nota_credito':
			$sqlUpdaComp = "update saencre set 
                                ncre_aprob_sri = '$estadoSRI',
                                ncre_auto_sri = '$numeroAutorizacion',
                                ncre_user_sri = $user_sri,
                                ncre_fech_sri = '$fechaAutorizacion',
                                ncre_erro_sri = '',
                                ncre_clav_sri  = '$claveAcceso'" . $sql_tmp;
			break;
		case 'guia_remision':
			$sqlUpdaComp = "update saeguia set 
                                guia_aprob_sri = '$estadoSRI',
                                guia_auto_sri = '$numeroAutorizacion',
                                guia_user_sri = $user_sri,
                                guia_fech_sri = '$fechaAutorizacion',
                                guia_erro_sri = '',
                                guia_clav_sri  = '$claveAcceso'" . $sql_tmp;

			break;
		case 'retencion_gasto':
			$sqlUpdaComp = "update saefprv set 
                                fprv_aprob_sri = '$estadoSRI',
                                fprv_auto_sri = '$numeroAutorizacion',
                                fprv_user_sri = $user_sri,
                                fprv_fech_sri = '$fechaAutorizacion',
                                fprv_erro_sri = '',
                                fprv_clav_sri  = '$claveAcceso',
                                fprv_user_web  = $user_sri  where
                                fprv_cod_empr = $id_empresa and 
                                fprv_cod_sucu = $id_sucursal  and 
                                fprv_cod_clpv = $clpv and 
                                fprv_num_fact = '$num_fact' and 
                                fprv_cod_ejer = '$ejer' and  
                                fprv_cod_asto = '$asto' and  
                                fprv_fec_emis = '$fec_emis'  ";
			break;
		case 'retencion_inventario':
			$sqlUpdaComp = "update saeminv set 
                                minv_aprob_sri = '$estadoSRI',
                                minv_auto_sri = '$numeroAutorizacion',
                                minv_user_sri = $user_sri,
                                minv_fech_sri = '$fechaAutorizacion',
                                minv_erro_sri = '',
                                minv_clav_sri  = '$claveAcceso',
                                minv_user_web  = $user_sri  where
                                minv_cod_empr = $id_empresa and 
                                minv_cod_sucu = $id_sucursal  and 
                                minv_cod_clpv = $clpv and 
                                minv_fac_prov = '$num_fact' and 
                                minv_cod_ejer = '$ejer' and  
                                minv_comp_cont = '$asto' and  
                                minv_fmov      = '$fec_emis'  ";
			break;
		case 'factura_expor':
			$sqlUpdaComp = "update saefact set 
                                fact_aprob_sri = '$estadoSRI',
                                fact_auto_sri = '$numeroAutorizacion',
                                fact_user_sri = $user_sri,
                                fact_fech_sri = '$fechaAutorizacion',
                                fact_erro_sri = '',
                                fact_clav_sri  = '$claveAcceso'" . $sql_tmp;
			break;
		case 'factura_flor':
			$sqlUpdaComp = "update saefpak set 
                                fpak_aprob_sri = '$estadoSRI',
                                fpak_auto_sri = '$numeroAutorizacion',
                                fpak_user_sri = $user_sri,
                                fpak_fech_sri = '$fechaAutorizacion',
                                fact_erro_sri = '',
                                fpak_clav_sri  = '$claveAcceso',
                                fpak_user_web  = $user_sri " . $sql_tmp;
			break;
		case 'factura_expor_flor':
			$sqlUpdaComp = "update saefpak set 
                                fpak_aprob_sri = '$estadoSRI',
                                fpak_auto_sri = '$numeroAutorizacion',
                                fpak_user_sri = $user_sri,
                                fpak_fech_sri = '$fechaAutorizacion',
                                fact_erro_sri = '',
                                fpak_clav_sri  = '$claveAcceso',
                                fpak_user_web  = $user_sri " . $sql_tmp;
			break;
		case 'guia_remision_flor':
			$sqlUpdaComp = "update saegref set 
                                gref_aprob_sri = '$estadoSRI',
                                gref_auto_sri = '$numeroAutorizacion',
                                gref_user_sri = $user_sri,
                                gref_fech_sri = '$fechaAutorizacion',
                                gref_erro_sri = '',
                                gref_clav_sri  = '$claveAcceso',
                                gref_user_web  = $user_sri " . $sql_tmp;
			break;
		case 'liqu_compras':
			$sqlUpdaComp = "update saeminv set 
                                minv_aprob_liqu = '$estadoSRI',
                                minv_auto_sri = '$numeroAutorizacion',
                                minv_aut_usua = '$numeroAutorizacion',
                                minv_user_sri = $user_sri,
                                minv_fech_sri = '$fechaAutorizacion',
                                minv_erro_sri = '',
                                minv_clav_sri  = '$claveAcceso',
                                minv_user_web  = $user_sri " . $sql_tmp;
			break;
	}

	return $sqlUpdaComp;
}

function genera_documento($tipo_documento = 0, $id = '', $clavAcce = 'no_autorizado', $clpv = 0,  $num_fact = '',  $ejer = 0,  $asto = '',  $fec_emis = '', $sucu = 0)
{
	if (session_status() !== PHP_SESSION_ACTIVE) {
		session_start();
	}
	global $DSN_Ifx;

	$oIfx = new Dbo;
    $oIfx->DSN = $DSN_Ifx;
    $oIfx->Conectar();


	$oReturn = new xajaxResponse();
	$estado = 1;
	$ruc_empresa = $_SESSION['EMPRESA_RUC'];
	$idEmpresa = $_SESSION['U_EMPRESA'];
	switch ($tipo_documento) {
		case 1:
			if ($ruc_empresa == '0190020304001') {
				$_SESSION['pdf'] = reporte_factura_dorfzaun($id, $clavAcce, $sucu);
			} else {


				  //VALIDACION FORMATO PERSONALIZADO
				  $sql = "select ftrn_ubi_web from saeftrn  where ftrn_cod_empr=$idEmpresa and ftrn_des_ftrn = 'FACTURA' and ftrn_cod_modu=7 
				  and (ftrn_ubi_web is not null or ftrn_ubi_web != '') and ftrn_ubi_web like '%Formatos%'";
				 $ubi = consulta_string($sql, 'ftrn_ubi_web', $oIfx, '');
				 if (!empty($ubi)) {
					include_once('../../' . $ubi . '');
					$_SESSION['pdf'] = reporte_factura_personalizado($id, $clavAcce, $sucu);
				 }
				 else{
					$_SESSION['pdf'] = reporte_factura($id, $clavAcce, $sucu);
				 }

			}
			break;
		case 2:
			$_SESSION['pdf'] = reporte_notaDebito($id, $clavAcce);
			break;
		case 3:
			$_SESSION['pdf'] = reporte_notaCredito($id, $clavAcce, $sucu);
			break;
		case 4:
			$sql = "select ftrn_ubi_web from saeftrn where ftrn_cod_modu=7 and ftrn_des_ftrn='GUIA REMISION' and ftrn_cod_empr=$idEmpresa";
			$ubigui = consulta_string($sql, 'ftrn_ubi_web', $oIfx, '');
			
			if (!empty($ubigui)) {
				include_once('../../' . $ubigui . '');
				$_SESSION['pdf'] = reporte_guia_personalizado($id, $clavAcce, $sucu);
			}
			else{
					$_SESSION['pdf'] = reporte_guiaRemision($id, $clavAcce, $sucu);
				}

			
			break;
		case 5:
			$estado = 5;
			$id = $_SESSION['sqlId'][$id];
			$_SESSION['pdf'] = reporte_retencionGasto($id, $clavAcce, $rutapdf, $clpv,  $num_fact,  $ejer,  $asto,  $fec_emis, $sucu);
			break;
		case 6:
			//$id = $_SESSION['sqlId'][$id];
			$_SESSION['pdf'] = reporte_retencionInve($id, $clavAcce,  $rutapdf, $clpv,  $num_fact,  $ejer,  $asto,  $fec_emis, $sucu);
			break;
		case 7:
			
			if ($ruc_empresa == '0190020304001') {
				$_SESSION['pdf'] = reporte_factura_export_dorfzaun($id, $clavAcce, $sucu);
			} else {
				//VALIDACION FORMATO PERSONALIZADO
				$sql = "select ftrn_ubi_web from saeftrn  where ftrn_cod_empr=$idEmpresa and ftrn_des_ftrn = 'FACTURA EXPORTADOR' and ftrn_cod_modu=7 
				and (ftrn_ubi_web is not null or ftrn_ubi_web != '') and ftrn_ubi_web like '%Formatos%'";
			   $ubi = consulta_string($sql, 'ftrn_ubi_web', $oIfx, '');

			   if (!empty($ubi)) {
				  include_once('../../' . $ubi . '');
				  $_SESSION['pdf'] = reporte_factura_personalizado($id, $clavAcce, $sucu);
			   }
			   else{
					  $_SESSION['pdf'] = reporte_factura_export($id, $clavAcce, $sucu);
			   }
			}
			break;
		case 8:
			$_SESSION['pdf'] = reporte_factura_flor($id, $clavAcce);
			break;
		case 9:
			$_SESSION['pdf'] = reporte_factura_flor_export($id, $clavAcce);
			break;
		case 10:
			$_SESSION['pdf'] = reporte_guiaRemisionFlor($id, $clavAcce);
			break;
		case 12:
			$_SESSION['pdf'] = reporte_liqu_compras($id, $clavAcce, $sucu, $rutapdf);
			break;
	}



	//$oReturn->script('generar_pdf_rete()');

	
		$oReturn->script('generar_pdf()');
	




	return $oReturn;
}

//METODOS COMPLEMENTARIOS
function genera_reporte()
{
	global $DSN, $DSN_Ifx;
	if (session_status() !== PHP_SESSION_ACTIVE) {
		session_start();
	}

	$aDataGridErro = $_SESSION['aDataGirdErro'];
	$iDataGridErro = count($aDataGridErro);
	$aLabelRepoErro = array("Nº", "COMPROBANTE", "TIPO", "ESTADO");

	$oReturn = new xajaxResponse();

	$html = '<table>';
	$html .= '<tr>';
	$html .= '<th>N.-</th>';
	$html .= '<th>COMPROBANTE</th>';
	$html .= '<th>TIPO</th>';
	$html .= '<th>ESTADO</th>';
	$html .= '</tr>';

	$i = 1;

	if (count($aDataGridErro) > 0) {
		foreach ($aDataGridErro as $val) {
			$a = $val[0];
			list($clave, $tipo, $erro) = $a;
			$comprobante = substr($clave, 24, 15) . ' ';

			$html .= '<tr>';
			$html .= '<td>' . $i . '</td>';
			$html .= '<td>' . $comprobante . '</td>';
			$html .= '<td>' . $tipo . '</td>';
			$html .= '<td>' . $erro . '</td>';
			$html .= '</tr>';
			$i++;
		} // fin foracj
	}
	$html .= '</table>';
	$oReturn->assign("divReporte", "innerHTML", $html);
	return $oReturn;
}

function fecha_informix($fecha)
{
	$m = substr($fecha, 5, 2);
	$y = substr($fecha, 0, 4);
	$d = substr($fecha, 8, 2);

	return ($y . '-' . $m . '-' . $d);
}

function digitoVerificador($cadena)
{
	//$cadena = "040820140117914132530011001001000000063272775261";
	$pivote = 7;

	$longitudCadena = strlen($cadena);
	for ($i = 0; $i < $longitudCadena; $i++) {
		if ($pivote == 1)
			$pivote = 7;
		$caracter = substr($cadena, $i, 1);
		$temporal = $caracter * $pivote;
		$pivote--;
		$cantidadTotal += $temporal;
	}

	$div = $cantidadTotal % 11;
	$digitoVerificador = 11 - $div;

	if ($digitoVerificador == 10)
		$digitoVerificador = 1;
	if ($digitoVerificador == 11)
		$digitoVerificador = 0;

	return $digitoVerificador;
}

function fecha_clave($fecha)
{
	$fecha_array = explode('/', $fecha);
	$m = $fecha_array[0];
	$y = $fecha_array[2];
	$d = $fecha_array[1];

	return ($d . '' . $m . '' . $y);
}

function fecha_sri($fecha)
{
	$fecha_array = explode('/', $fecha);
	$m = $fecha_array[0];
	$y = $fecha_array[2];
	$d = $fecha_array[1];

	return ($d . '/' . $m . '/' . $y);
}

function secuencialSri($secuencial)
{
	$lengSecuencail = strlen(($secuencial));

	if ($lengSecuencail < 9) {
		$AumeCero = 9 - $lengSecuencail;
		for ($i = 0; $i < $AumeCero; $i++)
			$secuencial = '0' . $secuencial;
	}

	return $secuencial;
}

function enviar_mail($aForm = '', $clave_acceso, $correo, $tipoDocu, $serial, $clpv, $num_fact, $ejer, $asto, $fec_emis, $idSucursal)
{
	global $DSN_Ifx, $DSN;

	if (session_status() !== PHP_SESSION_ACTIVE) {
		session_start();
	}

	$oIfx = new Dbo;
	$oIfx->DSN = $DSN_Ifx;
	$oIfx->Conectar();

	$oCon = new Dbo;
	$oCon->DSN = $DSN;
	$oCon->Conectar();

	$fu = new Formulario;
	$fu->DSN = $DSN;

	$ifu = new Formulario;
	$ifu->DSN = $DSN_Ifx;

	$oReturn = new xajaxResponse();

	//variables de sesion
	$idempresa = $_SESSION['U_EMPRESA'];
	$idsucursal = $_SESSION['U_SUCURSAL'];

	//variables del formulario
	$modulo = $aForm['modulo'];
	//echo $tipoDocu; exit;
	try {

		$sHtml .= '<div class="modal-dialog modal-lg" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
							<h4 class="modal-title" id="myModalLabel">REENVIO DOCUMENTOS ELECTRONICOS</h4>
						</div>
						<div class="modal-body">';

		$ifu->AgregarCampoTexto('correo', 'Destinatario|left', false, $correo, 700, 600);

		$sHtml .= '<table class="table table-striped table-condensed" style="width: 99%; margin-top: 2px;" align="center">';
		$sHtml .= '<tr>';
		$sHtml .= '<td>' . $ifu->ObjetoHtmlLBL('correo') . '</td>';
		$sHtml .= '<td>' . $ifu->ObjetoHtml('correo') . '</td>';
		$sHtml .= '</tr>';
		$sHtml .= '</table>';

		$sHtml .= '</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
							<button type="button" class="btn btn-primary" onclick="enviaEmail(\'' . $clave_acceso . '\', \'' . $tipoDocu . '\', 
																					' . $serial . ', ' . $clpv . ',  \'' . $num_fact . '\',
																					' . $ejer . ', \'' . $asto . '\', \'' . $fec_emis . '\', ' . $idSucursal . ')">
																					Procesar</button>
						</div>
					</div>
				</div>';

		$oReturn->assign("miModal", "innerHTML", $sHtml);
	} catch (Exception $e) {
		$oReturn->alert($e->getMessage());
	}

	return $oReturn;
}


function firmar($nombre_archivo, $clave_acceso, $ruc, $correo, $tipoDocu, $sql_tmp, $serial, $clpv, $num_fact, $ejer, $asto, $fec_emis, $doc, $sucu)
{
	global $DSN_Ifx, $DSN;

	if (session_status() !== PHP_SESSION_ACTIVE) {
		session_start();
	}

	$oCon = new Dbo;
	$oCon->DSN = $DSN;
	$oCon->Conectar();

	$oIfx = new Dbo;
	$oIfx->DSN = $DSN_Ifx;
	$oIfx->Conectar();

	$idEmpresa = $_SESSION['U_EMPRESA'];

	$contenido_xml = file_get_contents(DIR_FACTELEC . "modulos/sri_offline/documentoselectronicos/generados/$clave_acceso.xml");

	$oReturn = new xajaxResponse();

	unset($array_erro);

	//try {

	//lectura sucia
	// 

	//tipo firma empresa
	$sqlEmpr = "select empr_tip_firma from saeempr where empr_cod_empr = $idEmpresa";
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
		$tipoAmbiente = ambienteEmisionSri($oIfx, $idEmpresa, $sucu, 1);
		$token = ambienteEmisionSri($oIfx, $idEmpresa, $sucu, 3);

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

		if ($respFirm == null) {
			$oReturn->script("validaAutoriza('$nombre_archivo','$clave_acceso','$correo', '$tipoDocu', '$sql_tmp', $serial,
													  $clpv,  '$num_fact',  $ejer,  '$asto',  '$fec_emis', $sucu)");
		} else {
			$resp = updateError($tipoDocu, $respFirm, $clave_acceso, $sql_tmp, $clpv, $num_fact, $ejer, $asto, $fec_emis, $sucu);
			$oIfx->QueryT($resp);

			$array_erro[] = array($clave_acceso, 'FIRMA', $respFirm);
			$oReturn->alert("FIRMA " . $clave_acceso . ' ' . $respFirm);
		}
	} elseif ($empr_tip_firma == 'M') {

		/* FIRMAMOS EL XML */
		$idSucursal = $_SESSION["U_SUCURSAL"];

		$tipoAmbiente = ambienteEmisionSri($oIfx, $idEmpresa, $idSucursal, 1);
		$tipoEsquema = ambienteEmisionSri($oIfx, $idEmpresa, $idSucursal, 4);

		//datos de la firma
		$sqlEmprToke = "select empr_nom_toke,empr_pass_toke, empr_token_api from saeempr where empr_cod_empr = $idEmpresa";
		if ($oIfx->Query($sqlEmprToke)) {
			$empr_nom_toke = $oIfx->f("empr_nom_toke");
			$empr_pass_toke = $oIfx->f("empr_pass_toke");
			$empr_api_toke = $oIfx->f("empr_token_api");
		}

		// echo $empr_api_toke; exit;
		$oIfx->Free();

		// Creamos el objeto de Firma Electronica
		//$firma = new FirmaElectronica($empr_nom_toke, $empr_pass_toke);

		// Firmamos el XML y capturamos el resultado | RETORNA true|false segun sea el caso
		//$resultado = $firma->FirmarXML($nombre_archivo, $nombre_archivo);
		//$oReturn->alert($resultado.'*'.$nombre_archivo);

		$contenido_xml = file_get_contents(DIR_FACTELEC . "modulos/sri_offline/documentoselectronicos/generados/$clave_acceso.xml");
		if ($contenido_xml) {

			$headers = array(
				"Content-Type:application/json",
				"Token-Api:$empr_api_toke"
			);
			$data = array(
				"clave_acceso" => $clave_acceso,
				"contenido_xml" => base64_encode($contenido_xml),
				"ambiente_prueba" => ($tipoAmbiente == 1) ? true : false
			);

			$ch = curl_init();
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($ch, CURLOPT_URL, URL_JIREH_WS . "/api/facturacion/electronica/firmar/comprobante");
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$respuesta = curl_exec($ch);
			$resultado = json_decode($respuesta, true);



			$documento_firmado = $resultado["documento_firmado"];
			$contenido_xml = base64_decode($resultado["contenido_xml"]);
			file_put_contents(DIR_FACTELEC . "modulos/sri_offline/documentoselectronicos/firmados/$clave_acceso.xml", $contenido_xml);

			if ($documento_firmado == true) {
				$oReturn->assign($clave_acceso, "innerHTML", 'FIRMANDO...');
				$oReturn->script("validaAutoriza('$nombre_archivo','$clave_acceso','$correo', '$tipoDocu', '$sql_tmp', $serial,
												$clpv,  '$num_fact',  $ejer,  '$asto',  '$fec_emis', $sucu)");
			} else {
				$respFirma = "NO FIRMADO";
				$resp = updateError($tipoDocu, $respFirm, $clave_acceso, $sql_tmp, $clpv, $num_fact, $ejer, $asto, $fec_emis, $sucu);
				$oIfx->QueryT($resp);
				//$array_erro [] = array($clave_acceso, 'FIRMA', $respFirm);
				$oReturn->alert("FIRMA " . $clave_acceso . ' API' . $respFirm);
			}
		} else {
			$respFirma = "NO SE ENCUENTRA EL DOCUMENTO GENERADO";
			$oReturn->alert("FIRMA " . $clave_acceso . ' XML' . $respFirm);
		}
	}

	//} catch (SoapFault $e) {
	//	$resp = updateError($tipoDocu, 'NO HUBO CONECCION CON LA FIRMA', $clave_acceso, $sql_tmp, $clpv, $num_fact, $ejer, $asto, $fec_emis, $sucu);
	//	$oIfx->QueryT($resp);
	//	$array_erro [] = array($clave_acceso, 'FIRMA', 'NO HUBO CONECCION CON LA FIRMA');
	//	$oReturn->alert("FIRMA " . $clave_acceso . ' NO HUBO CONECCION CON LA FIRMA');
	//}

	$_SESSION['aDataGirdErro'] = $array_erro;
	return $oReturn;
}

function validaAutoriza($aForm = '', $nombre_archivo, $clave_acceso, $correo, $tipoDocu, $sql_tmp, $serial, $clpv, $num_fact, $ejer, $asto, $fec_emis, $idSucursal)
{
	global $DSN, $DSN_Ifx;
	if (session_status() !== PHP_SESSION_ACTIVE) {
		session_start();
	}

	$oIfx = new Dbo;
	$oIfx->DSN = $DSN_Ifx;
	$oIfx->Conectar();

	$oCon = new Dbo;
	$oCon->DSN = $DSN;
	$oCon->Conectar();

	$oReturn = new xajaxResponse();

	//variables de session
	$idEmpresa = $_SESSION['U_EMPRESA'];

	//variables del formulario
	$idTipo = $aForm['tipo_documento'];

	$arrayTipoAmbiente[1] = 'PRUEBAS';
	$arrayTipoAmbiente[2] = 'PRODUCCION';

	unset($array_erro2);

	/*
    $clientOptions = array(
        "useMTOM" => FALSE,
        'trace' => 1,
        'stream_context' => stream_context_create(array('http' => array('protocol_version' => 1.0)))
    );
	*/

	//HACEMOS LA VALIDACION DEL COMPROBANTE SUBIENDO EL ARCHIVO XML YA FIRMADO
	try {

		//tipo firma empresa
		$sqlEmpr = "select empr_tip_firma, empr_token_api from saeempr where empr_cod_empr = $idEmpresa";
		$empr_tip_firma = consulta_string_func($sqlEmpr, 'empr_tip_firma', $oIfx, 'N');
		$empr_api_toke = consulta_string_func($sqlEmpr, 'empr_token_api', $oIfx, '');

		if(empty($idSucursal)){
			$idSucursal = $_SESSION["U_SUCURSAL"];
		}

		$tipoAmbiente = ambienteEmisionSri($oIfx, $idEmpresa, $idSucursal, 1);
		$tipoEsquema = ambienteEmisionSri($oIfx, $idEmpresa, $idSucursal, 4);

		/*
                //webservices
                $sql = "select web_services from sri_web_services
                        where id_esquema = $tipoEsquema and
                        id_ambiente = $tipoAmbiente and
                        tipo = 'R'";
                $web_services = consulta_string_func($sql, 'web_services', $oCon, '');

                //$wsdlValiComp[$clave_acceso] = new SoapClient($web_services, $clientOptions);
                */

		if ($empr_tip_firma == 'N') {
			$rutaFirm = "C:/Jireh/Comprobantes Electronicos/firmados/" . $nombre_archivo;
		} elseif ($empr_tip_firma == 'M') {
			$rutaFirm = DIR_FACTELEC . "modulos/sri_offline/documentoselectronicos/firmados/" . $nombre_archivo;
		}

		/*
        $xml = file_get_contents($rutaFirm);

        $aArchivo = array("xml" => $xml);

        $valiComp = new stdClass();
        $valiComp = $wsdlValiComp[$clave_acceso]->validarComprobante($aArchivo);

        $RespuestaRecepcionComprobante = $valiComp->RespuestaRecepcionComprobante;
        $estado = $RespuestaRecepcionComprobante->estado;
		*/

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
		$respuesta = curl_exec($ch);
		$resultado = json_decode($respuesta, true);
		//$clave_acceso = $resultado["clave_acceso"];
		$estado = $resultado["estado"];
		$informacion_adicional =  $resultado["informacion_adicional"];
		//$fechaAutorizacion = gmdate("d-m-Y\TH:i:s\Z");
		$fechaAutorizacion = '';
		$mensaje =  $resultado["mensaje"];

		$op_autoriza = false;
		if (strcmp($estado, "RECIBIDA") == 0) {
			$op_autoriza = true;
		}else if(strcmp($estado, "DEVUELTA") == 0 && strcmp($mensaje, "CLAVE ACCESO REGISTRADA") == 0) {
			$op_autoriza = true;
		}

		if ($op_autoriza) {
			$sqlUpdateComp = updateComprobanteFirmado($clave_acceso, $tipoDocu, $clave_acceso, $fechaAutorizacion, $sql_tmp, $clpv, $num_fact, $ejer, $asto, $fec_emis, $idSucursal, 'F');
			$oIfx->QueryT($sqlUpdateComp);
			$oReturn->assign($clave_acceso, "innerHTML", 'FIRMADO');

			switch ($tipoDocu) {
				case 'factura_venta':
					$_SESSION['pdf'] = reporte_factura($serial, $clave_acceso, $idSucursal, $rutaPdf);
					$oReturn->assign($clave_acceso, "innerHTML", 'AUTORIZANDO...');
					$oReturn->script("autorizaComprobante('$clave_acceso', '$correo' , 'factura_venta', '$sql_tmp', $serial, $clpv,  '$num_fact',  $ejer,  '$asto',  '$fec_emis', $idSucursal )");
					//$oReturn->assign($serial . '_f', "checked", false);
					$oReturn->assign($serial . '_f', "innerHTML", '');
					break;
				case 'nota_debito':
					$_SESSION['pdf'] = reporte_notaDebito($serial, $clave_acceso,  $idSucursal, $rutaPdf);
					break;
				case 'nota_credito':
					$_SESSION['pdf'] = reporte_notaCredito($serial, $clave_acceso, $idSucursal, $rutaPdf);
					$oReturn->assign($serial . '_n', "checked", false);
					break;
				case 'guia_remision':
					$_SESSION['pdf'] = reporte_guiaRemision($serial, $clave_acceso, $idSucursal, $rutaPdf);
					break;
				case 'retencion_gasto':
					$_SESSION['pdf'] = reporte_retencionGasto($sqlUpdate, $clave_acceso, $rutaPdf, $clpv, $num_fact, $ejer, $asto, $fec_emis, $idSucursal);
					break;
				case 'retencion_inventario':
					$_SESSION['pdf'] = reporte_retencionInve($sqlUpdate, $clave_acceso, $rutaPdf, $clpv, $num_fact, $ejer, $asto, $fec_emis, $idSucursal);
					$oReturn->assign($serial . '_rc', "checked", false);
					break;
				case 'factura_expor':
					$_SESSION['pdf'] = reporte_factura_export($serial, $clave_acceso, $idSucursal);
					break;
				case 'factura_flor':
					$_SESSION['pdf'] = reporte_factura_flor($serial, $clave_acceso, $rutaPdf);
					break;
				case 'factura_expor_flor':
					$_SESSION['pdf'] = reporte_factura_flor_export($serial, $clave_acceso, $rutaPdf);
					break;
				case 'guia_remision_flor':
					$_SESSION['pdf'] = reporte_guiaRemisionFlor($serial, $clave_acceso, $rutaPdf);
					break;
				case 'liqu_compras':
					$_SESSION['pdf'] = reporte_liqu_compras($serial, $clave_acceso, $idSucursal, $rutaPdf);
					break;
			}

			// SQL CLIENTE
			if ($tipoDocu == 'factura_venta') {
				$sql = "select fact_nom_cliente from saefact where fact_cod_fact = $serial";
				$nom_clpv = consulta_string_func($sql, 'fact_nom_cliente', $oIfx, '');
			} else {
				$sql = "select clpv_nom_clpv from saeclpv where clpv_cod_clpv = '$clpv' ";
				$nom_clpv = consulta_string_func($sql, 'clpv_nom_clpv', $oIfx, '');
			}

			if ($empr_tip_firma == 'N') {
				$rutaDia = "C:/Jireh/Comprobantes Electronicos/firmados";
			} elseif ($empr_tip_firma == 'M') {
				$rutaDia = DIR_FACTELEC . "modulos/sri_offline/documentoselectronicos/firmados";
			}

			$nombre = $clave_acceso . ".xml";
			$ride = '' . $rutaDia . '/' . $nombre;

			$numeDocu = substr($clave_acceso, 24, 15);


			//$correoMsj = envio_correo_adj($correo, $ride, $rutaPdf, $nom_clpv, $clave_acceso, $numeDocu, $clpv, $idTipo, $idSucursal);

			// $oReturn->assign($clave_acceso, "innerHTML", 'FIRMADO: '.$correoMsj);
			
		}else{
			/*
			$comprobantes = $RespuestaRecepcionComprobante->comprobantes;
            $comprobante = $comprobantes->comprobante;
            $mensajes = $comprobante->mensajes;
            $mensaje = $mensajes->mensaje;
            $mensaje2 = $mensaje->mensaje;
            $informacionAdicional = strtoupper($mensaje->informacionAdicional);
			*/
			$resp = updateError($tipoDocu, $mensaje, $clave_acceso, $sql_tmp, $clpv, $num_fact, $ejer, $asto, $fec_emis, $idSucursal);
			$oIfx->QueryT($resp);
			$oReturn->alert($clave_acceso . ' ' . $informacion_adicional . $estado . ' ' . $mensaje);
		}
	} catch (SoapFault $e) {
		$array_erro2[] = array($clave_acceso, 'Validar Comprobante', 'NO HUBO CONECCION AL SRI (VALIDAR)');
		$resp = updateError($tipoDocu, 'NO HUBO CONECCION AL SRI (VALIDAR)', $clave_acceso, $sql_tmp, $clpv, $num_fact, $ejer, $asto, $fec_emis, $idSucursal);
		$oIfx->QueryT($resp);
		$oReturn->alert($clave_acceso . ' NO HUBO CONECCION AL SRI (VALIDAR)');
	}

	//return $array_erro2;
	$_SESSION['aDataGirdErro'] = $array_erro2;
	return $oReturn;
}

function autorizaComprobante($aForm = '', $clave_acceso, $correo, $tipoDocu, $sql_tmp, $serial, $clpv = 0, $num_fact, $ejer, $asto, $fec_emis, $idSucursal)
{
	global $DSN_Ifx, $DSN;

	if (session_status() !== PHP_SESSION_ACTIVE) {
		session_start();
	}

	$oIfx = new Dbo;
	$oIfx->DSN = $DSN_Ifx;
	$oIfx->Conectar();

	$oCon = new Dbo;
	$oCon->DSN = $DSN;
	$oCon->Conectar();


	//variables de session
	$idEmpresa = $_SESSION['U_EMPRESA'];

	if(empty($idSucursal)){
		$idSucursal = $_SESSION['U_SUCURSAL'];
	}

	$oReturn = new xajaxResponse();

	$id = $_SESSION['id'];
	$sqlUpdate = $_SESSION['sqlUpdate'];
	$idTipo = $aForm['tipo_documento'];

	$sqlEmprToke = "select empr_nom_toke,empr_pass_toke, empr_token_api from saeempr where empr_cod_empr = $idEmpresa";
	if ($oIfx->Query($sqlEmprToke)) {
		$empr_nom_toke = $oIfx->f("empr_nom_toke");
		$empr_pass_toke = $oIfx->f("empr_pass_toke");
		$empr_api_toke = $oIfx->f("empr_token_api");
	}

	// echo $empr_api_toke; exit;
	$oIfx->Free();

	try {

		$tipoAmbiente = ambienteEmisionSri($oIfx, $idEmpresa, $idSucursal, 1);
		$tipoEsquema = ambienteEmisionSri($oIfx, $idEmpresa, $idSucursal, 4);

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
		$respuesta = curl_exec($ch);
		$resultado = json_decode($respuesta, true);

		//print_r($resultado);exit;

		$estado = $resultado["estado"];
		$fecha_autorizacion = $resultado["fechaAutorizacion"];
		$claveAccesoConsultada = $resultado["numeroAutorizacion"];

		$tipo_valida = ($tipoAmbiente == 1) ? "NO DISPONIBLE" : "AUTORIZADO";

		if (strcmp($estado, $tipo_valida) == 0) {
		//if(true){

			$sqlUpdateComp = updateComprobanteFirmado($clave_acceso, $tipoDocu, $clave_acceso, $fecha_autorizacion, $sql_tmp, $clpv, $num_fact, $ejer, $asto, $fec_emis, $idSucursal, 'S');
			$oIfx->QueryT($sqlUpdateComp);

			$oReturn->assign($clave_acceso, "innerHTML", 'AUTORIZADO');

			$dia = substr($claveAccesoConsultada[$clave_acceso], 0, 2);
			$mes = substr($claveAccesoConsultada[$clave_acceso], 2, 2);
			$an = substr($claveAccesoConsultada[$clave_acceso], 4, 4);

			switch ($tipoDocu) {

				case 'factura_venta':
					$CarpDocu = 'FACTURAS VENTAS';
					$docu = "Fact_";
					$oReturn->assign($serial . '_f', "checked", false);
					 //VALIDACION FORMATO PERSONALIZADO
					 $sql = "select ftrn_ubi_web from saeftrn  where ftrn_cod_empr=$idEmpresa and ftrn_des_ftrn = 'FACTURA' and ftrn_cod_modu=7 
					 and (ftrn_ubi_web is not null or ftrn_ubi_web != '') and ftrn_ubi_web like '%Formatos%'";
					$ubi = consulta_string($sql, 'ftrn_ubi_web', $oIfx, '');
		
					if (!empty($ubi)) {
						include_once('../../' . $ubi . '');
						$_SESSION['pdf'] = reporte_factura_personalizado($serial, $clave_acceso, $idSucursal, $rutaPdf);
					}
					else{
						$_SESSION['pdf'] = reporte_factura($serial, $clave_acceso, $idSucursal, $rutaPdf);
					}
					break;
				case 'nota_debito':
					$CarpDocu = 'NOTAS DE DEBITO';
					$docu = "NDebi_";
					$_SESSION['pdf'] = reporte_notaDebito($serial, $clave_acceso,  $idSucursal, $rutaPdf);
					break;
				case 'nota_credito':
					$CarpDocu = 'NOTAS DE CREDITO';
					$docu = "NCred_";
					$oReturn->assign($serial . '_n', "checked", false);
					$_SESSION['pdf'] = reporte_notaCredito($serial, $clave_acceso, $idSucursal, $rutaPdf);
					break;
				case 'guia_remision':
					$CarpDocu = 'GUIAS REMISION';
					$docu = "GuiaR_";

					$sql = "select ftrn_ubi_web from saeftrn where ftrn_cod_modu=7 and ftrn_des_ftrn='GUIA REMISION' and ftrn_cod_empr=$idEmpresa";
					$ubigui = consulta_string($sql, 'ftrn_ubi_web', $oIfx, '');
					
					if (!empty($ubigui)) {
						include_once('../../' . $ubigui . '');
						$_SESSION['pdf'] = reporte_guia_personalizado($serial, $clave_acceso, $idSucursal, $rutaPdf);
					}
					else{
						$_SESSION['pdf'] = reporte_guiaRemision($serial, $clave_acceso, $idSucursal, $rutaPdf);
					}
					break;
				case 'retencion_gasto':
					$CarpDocu = 'RETENCIONES PROVEEDOR';
					$docu = "ReteP_";
					$_SESSION['pdf'] = reporte_retencionGasto($sqlUpdate, $clave_acceso, $rutaPdf, $clpv, $num_fact, $ejer, $asto, $fec_emis, $idSucursal);
					break;
				case 'retencion_inventario':
					$CarpDocu = 'RETENCIONES INVENTARIO';
					$docu = "ReteI_";
					$oReturn->assign($serial . '_rc', "checked", false);
					$_SESSION['pdf'] = reporte_retencionInve($sqlUpdate, $clave_acceso, $rutaPdf, $clpv, $num_fact, $ejer, $asto, $fec_emis, $idSucursal);
					break;
				case 'factura_expor':
					$CarpDocu = 'FACTURAS EXPORTADORES';
					$docu = "FactE_";
					//VALIDACION FORMATO PERSONALIZADO
					$sql = "select ftrn_ubi_web from saeftrn  where ftrn_cod_empr=$idEmpresa and ftrn_des_ftrn = 'FACTURA EXPORTADOR' and ftrn_cod_modu=7 
					and (ftrn_ubi_web is not null or ftrn_ubi_web != '') and ftrn_ubi_web like '%Formatos%'";
				   $ubi = consulta_string($sql, 'ftrn_ubi_web', $oIfx, '');
	   
				   if (!empty($ubi)) {
					   include_once('../../' . $ubi . '');
					   $_SESSION['pdf'] = reporte_factura_personalizado($serial, $clave_acceso, $idSucursal, $rutaPdf);
				   }
				   else{
						$_SESSION['pdf'] = reporte_factura_export($serial, $clave_acceso, $idSucursal);
				   }
					break;
				case 'factura_flor':
					$CarpDocu = 'FACTURAS FLOR';
					$docu = "FactF_";
					$_SESSION['pdf'] = reporte_factura_flor($serial, $clave_acceso, $rutaPdf);
					break;
				case 'factura_expor_flor':
					$CarpDocu = 'FACTURAS EXPORT-FLOR';
					$docu = "FactF_";
					$_SESSION['pdf'] = reporte_factura_flor_export($serial, $clave_acceso, $rutaPdf);
					break;
				case 'guia_remision_flor':
					$CarpDocu = 'GUIAS REMISION FLOR';
					$docu = "GuiaR_";
					$_SESSION['pdf'] = reporte_guiaRemisionFlor($serial, $clave_acceso, $rutaPdf);
					break;
				case 'liqu_compras':
					$CarpDocu = 'LIQUIDACION COMPRAS';
					$docu = "LiquC_";
					$_SESSION['pdf'] = reporte_liqu_compras($serial, $clave_acceso, $idSucursal, $rutaPdf);
					break;
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

			$sqlnom = "select clpv_nom_clpv from saeclpv where clpv_cod_clpv=$clpv";
			$nom_clpv = consulta_string($sqlnom, 'clpv_nom_clpv', $oIfx, '');

			$correoMsj = envio_correo_adj($correo, $ride, $rutaPdf, $nom_clpv, $clave_acceso, $numeDocu, $clpv, $idTipo, $idSucursal);
			//$correoMsj = '';
			// $oReturn->alert($correoMsj);
			$oReturn->assign($clave_acceso, "innerHTML", 'AUTORIZADO (' . $correoMsj . ')');
		} else {
			/*
            if ($informacionAdicional != '') {
                $posi = strpos($informacionAdicional, ':');
                $mensBDD = substr($informacionAdicional, $posi + 2, strlen($informacionAdicional));
            } elseif (is_array($mensaje[$clave_acceso])) {

                foreach ($mensaje[$clave_acceso] as $fila) {
                    $mensBDD .= preg_quote($fila->mensaje) . " | ";
                }
            }
			*/

			//$resp = updateError($tipoDocu, $mensBDD, $clave_acceso, $sql_tmp, $clpv, $num_fact, $ejer, $asto, $fec_emis, $idSucursal);
			//$oReturn->alert($resp);
			//$oIfx->QueryT($resp);
			//$oReturn->alert($mensBDD);

			//$msjSRI = $mensaje[$clave_acceso]->mensaje;

			//$oReturn->alert($estado[$clave_acceso] .' - '.$msjSRI.' -c: '.$clave_acceso);
			//$array_erro3 [] = array($clave_acceso, 'Autorizacion Comprobante', $mensBDD);

			$oReturn->alert($clave_acceso . " " . $estado);

			//$oReturn->alert("no se pudo autorizar intentelo mas tarde.");




		}
	} catch (SoapFault $e) {
		$array_erro3[] = array($clave_acceso, 'Autorizacion Comprobante', 'NO HUBO CONECCION AL SRI (AUTORIZAR)');
		$resp = updateError($tipoDocu, 'NO HUBO CONECCION AL SRI (AUTORIZAR)', $clave_acceso, $sql_tmp, $clpv, $num_fact, $ejer, $asto, $fec_emis, $idSucursal);
		$oIfx->QueryT($resp);
		$oReturn->alert($clave_acceso . ' NO HUBO CONECCION AL SRI (AUTORIZAR)');
	}

	// $_SESSION['aDataGirdErro'] = $array_erro3;
	//$oReturn->alert(count($array_erro3));
	//return $array_erro3;
	return $oReturn;
}


function enviaEmail($aForm = '', $clave_acceso, $tipoDocu, $serial, $clpv, $num_fact, $ejer, $asto, $fec_emis, $idSucursal)
{
	global $DSN_Ifx, $DSN;

	if (session_status() !== PHP_SESSION_ACTIVE) {
		session_start();
	}

	$oIfx = new Dbo;
	$oIfx->DSN = $DSN_Ifx;
	$oIfx->Conectar();

	$oCon = new Dbo;
	$oCon->DSN = $DSN;
	$oCon->Conectar();

	//variables de session
	$idEmpresa = $_SESSION['U_EMPRESA'];

	$oReturn = new xajaxResponse();

	$id = $_SESSION['id'];
	$sqlUpdate = '';

	//variables del formulario
	$idTipo = $aForm['tipo_documento'];
	$correo = $aForm['correo'];
	//echo $tipoDocu; exit;
	$sqlEmprToke = "select empr_nom_toke,empr_pass_toke, empr_token_api from saeempr where empr_cod_empr = $idEmpresa";
	if ($oIfx->Query($sqlEmprToke)) {
		$empr_nom_toke = $oIfx->f("empr_nom_toke");
		$empr_pass_toke = $oIfx->f("empr_pass_toke");
		$empr_api_toke = $oIfx->f("empr_token_api");
	}

	// echo $empr_api_toke; exit;
	$oIfx->Free();

	try {
		/*
        $clientOptions = array(
            "useMTOM" => FALSE,
            'trace' => 1,
            'stream_context' => stream_context_create(array('http' => array('protocol_version' => 1.0)))
        );
		*/

		/*
		$tipoAmbiente = ambienteEmisionSri($oIfx, $idEmpresa, $idSucursal, 1);
		$tipoEsquema = ambienteEmisionSri($oIfx, $idEmpresa, $idSucursal, 4);

		$sql = "select web_services from sri_web_services
				where id_esquema = $tipoEsquema and
				id_ambiente = $tipoAmbiente and
				tipo = 'A'";
        $web_services = consulta_string_func($sql, 'web_services', $oCon, '');



		$wsdlAutoComp[$clave_acceso] = new SoapClient($web_services, $clientOptions);

        //RECUPERA LA AUTORIZACION DEL COMPROBANTE
        $aClave = array("claveAccesoComprobante" => $clave_acceso);

        $autoComp[$clave_acceso] = new stdClass();
        $autoComp[$clave_acceso] = $wsdlAutoComp[$clave_acceso]->autorizacionComprobante($aClave);

        $RespuestaAutorizacionComprobante[$clave_acceso] = $autoComp[$clave_acceso]->RespuestaAutorizacionComprobante;
        $claveAccesoConsultada[$clave_acceso] = $RespuestaAutorizacionComprobante[$clave_acceso]->claveAccesoConsultada;
        $autorizaciones[$clave_acceso] = $RespuestaAutorizacionComprobante[$clave_acceso]->autorizaciones;
        $autorizacion[$clave_acceso] = $autorizaciones[$clave_acceso]->autorizacion;

        if (count($autorizacion[$clave_acceso]) > 1) {
            $estado[$clave_acceso] = $autorizacion[$clave_acceso][0]->estado;
            $numeroAutorizacion[$clave_acceso] = $autorizacion[$clave_acceso][0]->numeroAutorizacion;
            $fechaAutorizacion[$clave_acceso] = $autorizacion[$clave_acceso][0]->fechaAutorizacion;
            $ambiente[$clave_acceso] = $autorizacion[$clave_acceso][0]->ambiente;
            $comprobante[$clave_acceso] = $autorizacion[$clave_acceso][0]->comprobante;
            $mensajes[$clave_acceso] = $autorizacion[$clave_acceso][0]->mensajes;
            $mensaje[$clave_acceso] = $mensajes[$clave_acceso]->mensaje;
        } else {
            $estado[$clave_acceso] = $autorizacion[$clave_acceso]->estado;
            $numeroAutorizacion[$clave_acceso] = $autorizacion[$clave_acceso]->numeroAutorizacion;
            $fechaAutorizacion[$clave_acceso] = $autorizacion[$clave_acceso]->fechaAutorizacion;
            $ambiente[$clave_acceso] = $autorizacion[$clave_acceso]->ambiente;
            $comprobante[$clave_acceso] = $autorizacion[$clave_acceso]->comprobante;
            $mensajes[$clave_acceso] = $autorizacion[$clave_acceso]->mensajes;
            $mensaje[$clave_acceso] = $mensajes[$clave_acceso]->mensaje;
        }*/

		$headers = array(
			"Content-Type:application/json",
			"Token-Api:$empr_api_toke"
		);
		$data = array(
			"clave_acceso" => $clave_acceso
		);

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_URL, URL_JIREH_WS . "/api/facturacion/electronica/autorizacion/comprobante");
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$respuesta = curl_exec($ch);
		curl_close($ch);
		$resultado = json_decode($respuesta, true);
		$estado = $resultado["estado"];
		$fecha_autorizacion = $resultado["fechaAutorizacion"];
		$claveAccesoConsultada = $resultado["claveAccesoConsultada"];

		//if (strcmp($estado, "AUTORIZADO") == 0) {//validaicon retirada el 20/06/2024 autorizado

			$dia = substr($claveAccesoConsultada, 0, 2);
			$mes = substr($claveAccesoConsultada, 2, 2);
			$an = substr($claveAccesoConsultada, 4, 4);

			switch ($tipoDocu) {
				case 'factura_venta':
					$CarpDocu = 'FACTURAS VENTAS';
					$docu = "Fact_";
					  //VALIDACION FORMATO PERSONALIZADO
					  $sql = "select ftrn_ubi_web from saeftrn  where ftrn_cod_empr=$idEmpresa and ftrn_des_ftrn = 'FACTURA' and ftrn_cod_modu=7 
					  and (ftrn_ubi_web is not null or ftrn_ubi_web != '') and ftrn_ubi_web like '%Formatos%'";
					 $ubi = consulta_string($sql, 'ftrn_ubi_web', $oIfx, '');
					 if (!empty($ubi)) {
						include_once('../../' . $ubi . '');
						$_SESSION['pdf'] = reporte_factura_personalizado($serial, $clave_acceso, $idSucursal, $rutaPdf);
					 }
					 else{
						$_SESSION['pdf'] = reporte_factura($serial, $clave_acceso, $idSucursal,  $rutaPdf);
					 }
					break;
				case 'nota_debito':
					$CarpDocu = 'NOTAS DE DEBITO';
					$docu = "NDebi_";
					$_SESSION['pdf'] = reporte_notaDebito($id, $clave_acceso, $idSucursal,  $rutaPdf);

					break;
				case 'nota_credito':
					$CarpDocu = 'NOTAS DE CREDITO';
					$docu = "NCred_";
					$_SESSION['pdf'] = reporte_notaCredito($serial, $clave_acceso, $idSucursal, $rutaPdf);
					break;
				case 'guia_remision':
					$CarpDocu = 'GUIAS REMISION';
					$docu = "GuiaR_";

					$sql = "select ftrn_ubi_web from saeftrn where ftrn_cod_modu=7 and ftrn_des_ftrn='GUIA REMISION' and ftrn_cod_empr=$idEmpresa";
			$ubigui = consulta_string($sql, 'ftrn_ubi_web', $oIfx, '');
			
			
				if (!empty($ubigui)) {
					include_once('../../' . $ubigui . '');
					$_SESSION['pdf'] = reporte_guia_personalizado($serial, $clave_acceso, $idSucursal, $rutaPdf);
				}
				else{

					$_SESSION['pdf'] = reporte_guiaRemision($serial, $clave_acceso, $idSucursal,  $rutaPdf);
				}


					break;
				case 'retencion_gasto':
					$CarpDocu = 'RETENCIONES PROVEEDOR';
					$docu = "ReteP_";
					$id = $_SESSION['sqlId'][$id];

					$_SESSION['pdf'] = reporte_retencionGasto($id, $clave_acceso, $rutaPdf, $clpv, $num_fact, $ejer, $asto, $fec_emis, $idSucursal);

					break;
				case 'retencion_inventario':
					$CarpDocu = 'RETENCIONES INVENTARIO';
					$docu = "ReteI_";
					$id = $_SESSION['sqlId'][$id];
					$_SESSION['pdf'] = reporte_retencionInve($id, $clave_acceso, $rutaPdf, $clpv, $num_fact, $ejer, $asto, $fec_emis, $idSucursal);
					break;
				case 'factura_expor':
					$CarpDocu = 'FACTURAS EXPORTADORES';
					$docu = "FactE_";
					 //VALIDACION FORMATO PERSONALIZADO
					 $sql = "select ftrn_ubi_web from saeftrn  where ftrn_cod_empr=$idEmpresa and ftrn_des_ftrn = 'FACTURA EXPORTADOR' and ftrn_cod_modu=7 
					 and (ftrn_ubi_web is not null or ftrn_ubi_web != '') and ftrn_ubi_web like '%Formatos%'";
					$ubi = consulta_string($sql, 'ftrn_ubi_web', $oIfx, '');
					if (!empty($ubi)) {
					   include_once('../../' . $ubi . '');
					   $_SESSION['pdf'] = reporte_factura_personalizado($serial, $clave_acceso, $idSucursal, $rutaPdf);
					}
					else{
						$_SESSION['pdf'] = reporte_factura_export($serial, $clave_acceso, $idSucursal);
					}
					break;
				case 'factura_flor':
					$CarpDocu = 'FACTURAS FLOR';
					$docu = "FactF_";
					break;
				case 'factura_expor_flor':
					$CarpDocu = 'FACTURAS EXPORT-FLOR';
					$docu = "FactF_";
					break;
				case 'guia_remision_flor':
					$CarpDocu = 'GUIAS REMISION FLOR';
					$docu = "GuiaR_";
					break;
				case 'liqu_compras':
					$CarpDocu = 'LIQUIDACION COMPRAS';
					$_SESSION['pdf'] = reporte_liqu_compras($serial, $clave_acceso, $idSucursal,  $rutaPdf);
					$docu = "LiquC_";

					$rutaPdf = DIR_FACTELEC . 'Include/archivos/' . $clave_acceso . '.pdf';
					break;
			}


			//CREO LOS DIRECTORIOS DE LOS RIDES
			$serv = "C:/Jireh";
			$rutaRide = $serv . "/RIDE";
			$rutaComp = $rutaRide . "/" . $CarpDocu;
			$rutaAo = $rutaComp . "/" . $an;
			$rutaMes = $rutaAo . "/" . $mes;
			$rutaDia = $rutaMes . "/" . $dia;

			$numeDocu = substr($claveAccesoConsultada, 24, 15);
			$nombre = $docu . $numeDocu . "_" . "$dia-$mes-$an" . ".xml";

			$ride = '' . $rutaDia . '/' . $nombre;

			// SQL CLIENTE
			if ($tipoDocu == 'factura_venta') {
				$sql = "select fact_nom_cliente from saefact where fact_cod_fact = $serial";
				$nom_clpv = consulta_string_func($sql, 'fact_nom_cliente', $oIfx, '');
			} else {
				$sql = "select clpv_nom_clpv from saeclpv where clpv_cod_clpv = '$clpv' ";
				$nom_clpv = consulta_string_func($sql, 'clpv_nom_clpv', $oIfx, '');
			}

			$ride = DIR_FACTELEC . "modulos/sri_offline/documentoselectronicos/firmados/$clave_acceso.xml";
			$rutaPdf = DIR_FACTELEC . 'Include/archivos/' . $clave_acceso . '.pdf';

			//$oReturn->alert($correo.'*'.$ride.'*'.$rutaPdf.'*'.$nom_clpv.'*'.$clave_acceso.'*'.$numeDocu.'*'.$clpv.'*'.$idTipo.'*'.$idSucursal);

			$correoMsj = envio_correo_adj($correo, $ride, $rutaPdf, $nom_clpv, $clave_acceso, $numeDocu, $clpv, $idTipo, $idSucursal);

			$oReturn->alert($correoMsj);
		/*} else {
			if ($informacionAdicional != '') {
				$posi = strpos($informacionAdicional, ':');
				$mensBDD = substr($informacionAdicional, $posi + 2, strlen($informacionAdicional));
			} elseif (is_array($mensaje[$clave_acceso])) {

				foreach ($mensaje[$clave_acceso] as $fila) {
					$mensBDD .= preg_quote($fila->mensaje) . " | ";
				}
			}

			$msjSRI = $mensaje[$clave_acceso]->mensaje;

			$oReturn->alert($estado[$clave_acceso] . ' - ' . $msjSRI . ' -c: ' . $clave_acceso);
		}*/
	} catch (SoapFault $e) {
		$oReturn->alert($clave_acceso . ' NO HUBO CONECCION AL SRI (AUTORIZAR)');
	}

	return $oReturn;
}

function cambioFechaSri($fecha = '', $fechIni = '', $fechFina = '')
{
	$fecha = explode('-', $fecha);
	$fechIni = explode('-', $fechIni);
	$fechFina = explode('/', $fechFina);

	if (count($fecha) == 3 && count($fechIni) == 3 && count($fechFina) == 3) {
		for ($i = 0; $i < count($fechIni); $i++) {
			if (strtoupper($fechIni[$i]) == 'DD')
				$dia = $fecha[$i];
			else if (strtoupper($fechIni[$i]) == 'MM')
				$mes = $fecha[$i];
			else if (strtoupper($fechIni[$i]) == 'AAAA')
				$a_o = $fecha[$i];
		}

		for ($i = 0; $i < count($fechFina); $i++) {
			if (strtoupper($fechFina[$i]) == 'DD')
				$resu .= $dia . '/';
			else if (strtoupper($fechFina[$i]) == 'MM')
				$resu .= $mes . '/';
			else if (strtoupper($fechFina[$i]) == 'AAAA')
				$resu .= $a_o . '/';
		}
	} else
		return '--/--/--/';

	$resu = substr($resu, 0, -1);
	return $resu;
}


/* :::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::: */
/* PROCESO DE REQUEST DE LAS FUNCIONES MEDIANTE AJAX NO MODIFICAR */
$xajax->processRequest();
/* :::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::: */
