<? /* * ***************************************************************** */ ?>
<? /* NO MODIFICAR ESTA SECCION */ ?>
<? include_once('../_Modulo.inc.php'); ?>
<? include_once(HEADER_MODULO); ?>
<? if ($ejecuta) { ?>
    <? /*     * ***************************************************************** */ ?>

   <!-- Bootstrap 3.3.7 -->
<link rel="stylesheet" type="text/css"
          href="<?= $_COOKIE["JIREH_COMPONENTES"] ?>bower_components/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="<?= $_COOKIE["JIREH_INCLUDE"] ?>css/dataTables/dataTables.buttons.min.css"
          media="screen">
    <!-- Font Awesome -->
    <link rel="stylesheet" type="text/css"
          href="<?= $_COOKIE["JIREH_COMPONENTES"] ?>bower_components/font-awesome/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" type="text/css"
          href="<?= $_COOKIE["JIREH_COMPONENTES"] ?>bower_components/Ionicons/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" type="text/css" href="<?= $_COOKIE["JIREH_COMPONENTES"] ?>dist/css/AdminLTE.min.css">
    <!-- AdminLTE Skins. Choose a skin from the css/skinsfolder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="<?= $_COOKIE["JIREH_COMPONENTES"] ?>dist/css/skins/_all-skins.min.css">
    <link rel="stylesheet" type="text/css" href="<?= $_COOKIE["JIREH_INCLUDE"] ?>css/dataTables/dataTables.bootstrap.min.css"
          media="screen">
    <!-- Style -->
    <link rel="stylesheet" type="text/css" href="<?= $_COOKIE["JIREH_INCLUDE"] ?>css/style.css">

    <!--JavaScript-->
    <script type="text/javascript" language="JavaScript"
            src="<?= $_COOKIE["JIREH_INCLUDE"] ?>js/dataTables/jquery.dataTables.min.js"></script>
    <script type="text/javascript" language="JavaScript"
            src="<?= $_COOKIE["JIREH_INCLUDE"] ?>js/dataTables/dataTables.bootstrap.min.js"></script>
    <script type="text/javascript" language="JavaScript"
            src="<?= $_COOKIE["JIREH_INCLUDE"] ?>js/dataTables/dataTables.buttons.min.js"></script>
    <script type="text/javascript" language="JavaScript"
            src="<?= $_COOKIE["JIREH_INCLUDE"] ?>js/dataTables/dataTables.buttons.flash.min.js"></script>
    <script type="text/javascript" language="JavaScript"
            src="<?= $_COOKIE["JIREH_INCLUDE"] ?>js/dataTables/dataTables.jszip.min.js"></script>
    <script type="text/javascript" language="JavaScript"
            src="<?= $_COOKIE["JIREH_INCLUDE"] ?>js/dataTables/dataTables.pdfmake.min.js"></script>
    <script type="text/javascript" language="JavaScript"
            src="<?= $_COOKIE["JIREH_INCLUDE"] ?>js/dataTables/dataTables.vfs_fonts.js"></script>
    <script type="text/javascript" language="JavaScript"
            src="<?= $_COOKIE["JIREH_INCLUDE"] ?>js/dataTables/dataTables.buttons.html5.min.js"></script>
    <script type="text/javascript" language="JavaScript"
            src="<?= $_COOKIE["JIREH_INCLUDE"] ?>js/dataTables/dataTables.buttons.print.min.js"></script>
	
    <script>

    function imprime_guia(ubi, id, sucu){
            

            var opciones = "toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=no, width=730, height=700, top=255, left=30";
			var pagina = '../../'+ubi+'?sesionId=<?= session_id() ?>&codigo='+id+'&sucursal='+sucu;
				window.open(pagina, "", opciones);
           
     }

        function valida_btn(){
            var tipo_documento = $("#tipo_documento").val();
            if(tipo_documento == 1){
                $("#btn_enviar").hide();
            }else{
                $("#btn_enviar").show();
            }    
        }

        function valida_btn_2(){
            var estado = $("#estado").val();
            var tipo_documento = $("#tipo_documento").val();

            if(tipo_documento == 1){
                if(estado == 'N'){
                    $("#btn_enviar").hide();
                    $("#btn_firmar").show();
                }else if(estado == 'F'){
                    $("#btn_enviar").show();
                    $("#btn_firmar").hide();
                }else{
                    $("#btn_enviar").hide();
                    $("#btn_firmar").hide();
                }
            }else{
                $("#btn_enviar").show();
                $("#btn_firmar").show();
            }
           
            
        }

        function genera_formulario() {
            xajax_genera_formulario();
        }

        function init(table) {
            var table = $('#'+table).DataTable({
                scrollY: '80vh',
                scrollX: true,
                scrollCollapse: true,
                paging: false,
                dom: 'Bfrtip',
                columnDefs: [
                { "orderable": false, "targets": 10 },//ocultar para columna 0
                { "orderable": false, "targets": 11 },
                { "orderable": false, "targets": 15 },
                ],
                processing: "<i class='fa fa-spinner fa-spin' style='font-size:24px; color: #34495e;'></i>",
                "language": {
                    "search": "<i class='fa fa-search'></i>",
                    "searchPlaceholder": "Buscar",
                    'paginate': {
                        'previous': 'Anterior',
                        'next': 'Siguiente'
                    },
                    "zeroRecords": "No se encontro datos",
                    "info": "Mostrando _START_ a _END_ de  _TOTAL_ Total",
                    "infoEmpty": "",
                    "infoFiltered": "(Mostrando _MAX_ Registros Totales)",
                },
                "ordering": true,
                "info": true,
            });
            table.buttons().remove();
            
        }


        function consultar_documento() {
            var tipo_documento = document.getElementById('tipo_documento').value;

            //if (ProcesarFormulario() == true) {
            if (tipo_documento =='') {
                alert('Seleccione el tipo de documento');
            }

            if (tipo_documento ==0) {
                alert('Seleccione el tipo de documento');
            }
            else{
                switch (tipo_documento) {
                    case '1':
                        jsShowWindowLoad();
                        cerrar_div_msn();
                        xajax_consultar_factVent(xajax.getFormValues("form1"));
                        break;
                    case '2':
                        xajax_consultar_notaDebi(xajax.getFormValues("form1"));
                        break;
                    case '3':
                        xajax_consultar_notaCred(xajax.getFormValues("form1"));
                        break;
                    case '4':
                        xajax_consultar_guiaRemi(xajax.getFormValues("form1"));
                        break;
                    case '5':
                        xajax_consultar_reteGast(xajax.getFormValues("form1"));
                        break;
                    case '6':
                        xajax_consultar_reteInve(xajax.getFormValues("form1"));
                        break;
                    case '7':
                        xajax_consultar_factExpor(xajax.getFormValues("form1"));
                        break;
                    case '8':
                        xajax_consultar_factFlor(xajax.getFormValues("form1"));
                        break;
                    case '9':
                        xajax_consultar_factFlorExpor(xajax.getFormValues("form1"));
                        break;
                    case '10':
                        xajax_consultar_guiaRemiFlor(xajax.getFormValues("form1"));
                        break;
                    case '12':
                        xajax_consultar_liquidacionCompra(xajax.getFormValues("form1"));
                        break;
                }
            }
        }

        function cerrar_div_msn(){
            $('#div_msn').hide();
            $('#div_msn_2').hide();
            $('#div_dashboard').hide();
            $('#btn_close').hide();
        }

        async function ejecutar_facturas() {
            var documentos_seleccionados = [];
            $("input[name='documentos']:checked").each(function () {
                documentos_seleccionados.push($(this).val());
            });

            if (documentos_seleccionados.length == 0) {
                Swal.fire({
                    type: 'error',
                    title: 'Error',
                    text: 'Seleccione al menos un documento para continuar.'
                });
                return;
            }

            Swal.fire({
                title: '¿Está seguro de que desea firmar ' + documentos_seleccionados.length + ' documentos?',
                width: '30%',
                type: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, continuar'
            }).then(async (result) => {
                if (result.value) {
                    let total_documentos = documentos_seleccionados.length;
                    let documentos_procesados = 0;
                    let documentos_restantes = total_documentos;
                    let documentos_con_error = 0;
                    let tiempos_respuesta = [];
                    let tiempo_inicio;

                    function mostrar_div_progreso() {
                        $('#div_msn').show();
                        $('#div_msn_2').show();
                        $('#div_dashboard').show();
                    }

                    function ocultar_div_progreso() {
                        $('#div_msn').hide();
                        $('#div_msn_2').hide();
                        $('#div_dashboard').hide();
                    }

                    function actualizar_resumen() {
                        let tiempo_promedio = tiempos_respuesta.length ? tiempos_respuesta.reduce((a, b) => a + b) / tiempos_respuesta.length : 0;
                        let tiempo_estimado_restante = tiempo_promedio * documentos_restantes;
                        let minutos = Math.floor(tiempo_estimado_restante / 60);
                        let segundos = Math.floor(tiempo_estimado_restante % 60);

                        $('#num_documentos_total').text(total_documentos);
                        $('#num_documentos_procesados').text(documentos_procesados);
                        $('#num_documentos_con_error').text(documentos_con_error);
                        //$('#tiempo_restante').text(`${minutos}m ${segundos}s`);
                    }

                    function procesar_lote(documentos) {
                        return Promise.all(documentos.map(dato_documento => {
                            return new Promise((resolve, reject) => {
                                tiempo_inicio = Date.now(); // Registrar el tiempo de inicio de la solicitud

                                var dato_documento_json = atob(dato_documento);
                                var dato_documento_obj = JSON.parse(dato_documento_json);
                                var clave_acceso_documento = dato_documento_obj[21];

                                $("#" + clave_acceso_documento + "").html('<i class="fa-solid fa-spinner fa-spin"></i> Firmando...');

                                $.ajax({
                                    url: 'envio_documentos.php',
                                    method: 'POST',
                                    data: { documento: dato_documento },
                                    success: function (respuesta) {
                                        var data = JSON.parse(respuesta);
                                        var status = data["status"];
                                        var clave_acceso = data['clave_acceso'];
                                        var id_factura = data['id_factura'];
                                        var mensaje = '';

                                        let tiempo_respuesta = (Date.now() - tiempo_inicio) / 1000; // Tiempo en segundos
                                        tiempos_respuesta.push(tiempo_respuesta);

                                        if (status != 200) {
                                            mensaje = data['mensaje'];
                                            documentos_con_error++;
                                            $('#check_' + id_factura + '').prop('checked', false);
                                        } else {
                                            mensaje = data['respuesta'];
                                            documentos_procesados++;
                                            $('#div_check_' + id_factura + '').html('');
                                        }

                                        $('#num_documentos_procesados').text(documentos_procesados);
                                        $('#num_documentos_restantes').text(documentos_restantes);
                                        $('#num_documentos_con_error').text(documentos_con_error);
                                        $("#" + clave_acceso + "").html(mensaje);

                                        documentos_restantes--;
                                        resolve();
                                    },
                                    error: function () {
                                        let tiempo_respuesta = (Date.now() - tiempo_inicio) / 1000; // Tiempo en segundos
                                        tiempos_respuesta.push(tiempo_respuesta);
                                        documentos_con_error++;
                                        documentos_restantes--;

                                        $('#num_documentos_procesados').text(documentos_procesados);
                                        $('#num_documentos_restantes').text(documentos_restantes);
                                        $('#num_documentos_con_error').text(documentos_con_error);
                                        resolve();
                                    }
                                });
                            });
                        }));
                    }

                    mostrar_div_progreso();
                    actualizar_resumen();

                    for (let i = 0; i < documentos_seleccionados.length; i += 10) {
                        const lote = documentos_seleccionados.slice(i, i + 10);
                        await procesar_lote(lote);
                    }

                    $('#num_documentos_total').text(total_documentos);
                    $('#num_documentos_procesados').text(documentos_procesados);
                    $('#num_documentos_restantes').text(documentos_restantes);
                    $('#num_documentos_con_error').text(documentos_con_error);
                    $('#tiempo_restante').text('Proceso completado');
                    $('#btn_close').show();
                    //ocultar_div_progreso();
                }
            });
        }





        function firmar_documento( ) {
            var tipo_documento = document.getElementById('tipo_documento').value;

            if(tipo_documento != 1){
                alert('Procesando...');
            }
            
            switch (tipo_documento) {
                case '1':
                    ejecutar_facturas();
                    //xajax_firmar_factVent(xajax.getFormValues("form1"));
                    break;
                case '2':
                    xajax_firmar_notaDebi(xajax.getFormValues("form1"));
                    break;
                case '3':
                    xajax_firmar_notaCred(xajax.getFormValues("form1"));
                    break;
                case '4':
                    xajax_firmar_guiaRemi(xajax.getFormValues("form1"));
                    break;
                case '5':
                    xajax_firmar_reteGast(xajax.getFormValues("form1"));
                    break;
                case '6':
                    xajax_firmar_reteInve(xajax.getFormValues("form1"));
                    break;
                case '7':
                    xajax_firmar_factExpor(xajax.getFormValues("form1"));
                    break;
                case '8':
                    xajax_firmar_factFlor(xajax.getFormValues("form1"));
                    break;
                case '9':
                    xajax_firmar_factFlorExpor(xajax.getFormValues("form1"));
                    break;
                case '10':
                    xajax_firmar_guiaRemiFlor(xajax.getFormValues("form1"));
                    break;
                case '12':
                    xajax_firmar_liquidacionCompra(xajax.getFormValues("form1"));
                    break;
            }

        }

        function generar_reporte(base64Data){
            var jsonData1 = atob(base64Data);

            // Convertir la cadena JSON a un objeto JavaScript
            var data = JSON.parse(jsonData1);

            var cabeceras       = data.cabecera;
            var contenido       = data.contenido;
            var pie             = data.pie;
            var nombre_archivo  = data.nombre_archivo;

            generarTablaPaginada('#divFormularioDetalle', cabeceras[0], contenido, 1000, 'tabla_facturacion', nombre_archivo, pie);
            jsRemoveWindowLoad();

        }

        function enviar_sri( ) {
            var tipo_documento = document.getElementById('tipo_documento').value;
            alert('Procesando...');
            switch (tipo_documento) {
                case '1':
                    firmar_documento();
                    //xajax_enviar_factVent(xajax.getFormValues("form1"));
                    break;
                case '2':
                    xajax_enviar_notaDebi(xajax.getFormValues("form1"));
                    break;
                case '3':
                    xajax_enviar_notaCred(xajax.getFormValues("form1"));
                    break;
                case '4':
                    xajax_enviar_guiaRemi(xajax.getFormValues("form1"));
                    break;
                case '5':
                    xajax_enviar_reteGast(xajax.getFormValues("form1"));
                    break;
                case '6':
                    xajax_enviar_reteInve(xajax.getFormValues("form1"));
                    break;
                case '7':
                    xajax_enviar_factExpor(xajax.getFormValues("form1"));
                    break;
                case '8':
                    xajax_enviar_factFlor(xajax.getFormValues("form1"));
                    break;
                case '9':
                    xajax_enviar_factFlorExpor(xajax.getFormValues("form1"));
                    break;
                case '10':
                    xajax_enviar_guiaRemiFlor(xajax.getFormValues("form1"));
                    break;
				case '12':
                    xajax_enviar_liquCompras(xajax.getFormValues("form1"));
                    break;	
            }
        }



        //FUNCIONES SRI 
        function firmar(nombre_archivo, clave_acceso, ruc, correo, tipoDocu, sql, id, clpv, num_fact, ejer, asto, fec_emis, op, sucu) {
		//alert('123');
            xajax_firmar(nombre_archivo, clave_acceso, ruc, correo, tipoDocu, sql, id, clpv, num_fact, ejer, asto, fec_emis, op, sucu);
        }

        function validaAutoriza(nombre_archivo, clave_acceso, correo, tipoDocu, sql, id, clpv, num_fact, ejer, asto, fec_emis, sucu) {
            xajax_validaAutoriza(xajax.getFormValues("form1"), nombre_archivo, clave_acceso, correo, tipoDocu, sql, id, clpv, num_fact, ejer, asto, fec_emis, sucu);
        }

        function autorizaComprobante(clave_acceso, correo, tipoDocu, sql, id, clpv, num_fact, ejer, asto, fec_emis, sucu) {
            xajax_autorizaComprobante(xajax.getFormValues("form1"), clave_acceso, correo, tipoDocu, sql, id, clpv, num_fact, ejer, asto, fec_emis, sucu);
        }

        function update_comprobante(numeroAutorizacion, fechaAutorizacion, fact_cod_fact, clpv, num_fact, ejer, asto, fec_emis, sucu) {
            xajax_update_comprobante(numeroAutorizacion, fechaAutorizacion, fact_cod_fact, clpv, num_fact, ejer, asto, fec_emis, sucu);
        }

        function reporte() {
            AjaxWin('<?=$_COOKIE['JIREH_INCLUDE']?>', '../envioloteseco/reporte.php?sesionId=<?= session_id() ?>&mOp=true&mVer=false&', 'DetalleShow', 'iframe', 'Reporte', '690', '250', '10', '10', '1', '1');
        }

        function cerrar_ventana() {
            CloseAjaxWin();
        }

		function marcar(source) {
            checkboxes = document.getElementsByTagName('input'); //obtenemos todos los controles del tipo Input
            for (i = 0; i < checkboxes.length; i++) //recoremos todos los controles
            {
                if (checkboxes[i].type == "checkbox") //solo si es un checkbox entramos
                {
                    checkboxes[i].checked = source.checked; //si es un checkbox le damos el valor del checkbox que lo llamÃ³ (Marcar/Desmarcar Todos)
                }
            }
            //xajax_suma(xajax.getFormValues("form1"));
        }
		
		function genera_documento(tipo_documento, id, clavAcce, clpv, num_fact, ejer, asto, fec_emis, sucu) {
            xajax_genera_documento(tipo_documento, id, clavAcce, clpv, num_fact, ejer, asto, fec_emis, sucu);
        }
		
		function generar_pdf() {
            //if (ProcesarFormulario() == true) {
                var opciones = "toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=no, width=730, height=370, top=255, left=130";
                var pagina = '../../Include/documento_pdf.php?sesionId=<?= session_id() ?>';
                window.open(pagina, "", opciones);
            //}
        }
		
		function descargar() {
			var opciones = "toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=no, width=730, height=370, top=255, left=130";
			var pagina = "../sri_offline/Ficha_Tecnica_SRI.pdf";
			window.open(pagina, "", opciones);
		}
		
		function listaErrores(){
			document.getElementById('miModal').innerHTML = '';
			$("#miModal").modal("show");
		}
		
		function enviar_mail(clave_acceso, correo, tipoDocu, id, clpv, num_fact, ejer, asto, fec_emis, sucu){
			document.getElementById('miModal').innerHTML = '';
			$("#miModal").modal("show");
			xajax_enviar_mail(xajax.getFormValues("form1"), clave_acceso, correo, tipoDocu, id, clpv, num_fact, ejer, asto, fec_emis, sucu);
		}
		
		function enviaEmail(clave_acceso, tipoDocu, id, clpv, num_fact, ejer, asto, fec_emis, sucu){
			xajax_enviaEmail(xajax.getFormValues("form1"), clave_acceso, tipoDocu, id, clpv, num_fact, ejer, asto, fec_emis, sucu);
		}
		
        function autocompletar(event, op) {
			if (event.keyCode == 115 || event.keyCode == 13) { // F4
				searchClientes(op);
			}
		}

		function searchClientes(op) {
			//var consumidor = document.getElementById('consumidor').checked;
			var consumidor = '';
			var search = '';

            var tipo_documento = document.getElementById('tipo_documento').value;

			if (op == 1) {
				search = document.getElementById('cliente_nombre').value;
			} else {
				search = document.getElementById('ruc').value;
			}

			var pagina_in = 'busca_cliente.php?search=' + search + '&consumidor=' + consumidor+ '&tipodoc=' + tipo_documento;
			$('#modal_load_body').load(encodeURI(pagina_in), function() {
				$("#miModalLoad").modal("show");
				$("#modal_load_titulo").html('<b>Buscar Cliente</b>');
			});
		}

        function edita_contactos(id, empr, sucu, clpv, fac, ejer, asto, fec, numcomp){
            $("#ModalEdita").modal("show");
            var tipo_documento = document.getElementById('tipo_documento').value;
            xajax_editar_contactos(tipo_documento,id, empr, sucu, clpv, fac, ejer, asto, fec,numcomp);
        }

        function guarda_edita_contactos(tipo, id, empr, sucu, clpv, fac, ejer, asto, fec, numcomp){

            Swal.fire({
            title: 'Desea guardar los cambios?',
            text: "",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Aceptar',
            allowOutsideClick: false,
            width: '25%',
            }).then((result) => {
                if (result.value) {
                    xajax_guardar_editar_contactos(xajax.getFormValues("form1"),tipo, id, empr, sucu, clpv, fac, ejer, asto, fec, numcomp);
                }
            })

        }


        function cerrarModalEdita() {
            $("#ModalEdita").html("");
            $("#ModalEdita").modal("hide");
        }

        function descarga_xml_b64(base64String, filename) {
            // Decodificar Base64 a binario
            const binaryString = atob(base64String);
            
            // Convertir el binario a un array de bytes
            const arrayBuffer = new ArrayBuffer(binaryString.length);
            const uint8Array = new Uint8Array(arrayBuffer);
            
            for (let i = 0; i < binaryString.length; i++) {
                uint8Array[i] = binaryString.charCodeAt(i);
            }
            
            // Crear un Blob con el array de bytes
            const blob = new Blob([uint8Array], { type: 'application/xml' });
            
            // Crear una URL para el Blob
            const url = URL.createObjectURL(blob);
            
            // Crear un enlace de descarga
            const a = document.createElement('a');
            a.href = url;
            a.download = filename;
            
            // Simular un clic en el enlace para iniciar la descarga
            document.body.appendChild(a);
            a.click();
            
            // Limpiar
            document.body.removeChild(a);
            URL.revokeObjectURL(url);
        }


    </script>

    <!--DIBUJA FORMULARIO FILTRO-->
    <div class="container-fluid">
        <form id="form1" name="form1" action="javascript:void(null);">
        <script type="text/javascript" language="JavaScript" src="<?= $_COOKIE["JIREH_INCLUDE"] ?>js/tablasPaginadas/paginacion.js"></script>
         <!-- Font Awesome -->
         <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
            <div class="main row">
				<div id="divFormularioCabecera" class="col-md-12"></div>	
                <br>
                <br>
                <div class="container-fluid">
                    <div class="row" id="div_dashboard" style="display:none">
                        <div class="col-md-12">
                           
                            <div class="thumbnail" >
                                <div class="caption">
                                    <p>
                                    <div class="row" style="display:none" id="btn_close">
                                        <div class="col-md-12" align="right">
                                            <button type="button" class="btn btn-danger btn-xs" onclick="cerrar_div_msn()"><i class="fa-solid fa-circle-xmark"></i></button>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div id="div_pending" style="display:none">
                                            <div class="col-md-3">
                                                <div class="panel panel-success" style="border-radius:10px">
                                                    <div class="panel-heading  bg-green" style="border-radius:10px">
                                                        <div class="panel-heading">
                                                            <div class="row">
                                                                <div class="col-xs-3" id="icono_img">
                                                                   
                                                                </div>
                                                                <div class="col-xs-9 text-right">
                                                                    <div style="font-size:25px" id="num_doc_estatus">0</div>
                                                                    <div id="nombre_docu_estado"></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="div_msn" style="display:none">
                                            <div class="col-md-3">
                                                <div class="panel panel-primary" style="border-radius:10px">
                                                    <div class="panel-heading  bg-blue" style="border-radius:10px">
                                                        <div class="panel-heading">
                                                            <div class="row">
                                                                <div class="col-xs-3">
                                                                    <i class="fa fa-solid fa-paper-plane fa-5x"></i>
                                                                </div>
                                                                <div class="col-xs-9 text-right">
                                                                    <div style="font-size:25px" id="num_documentos_total">0</div>
                                                                    <div>Documentos a procesar</div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="panel panel-success" style="border-radius:10px">
                                                    <div class="panel-heading  bg-green" style="border-radius:10px">
                                                        <div class="panel-heading">
                                                            <div class="row">
                                                                <div class="col-xs-3">
                                                                    <i class="fa fa-solid fa-clipboard-check fa-5x"></i>
                                                                </div>
                                                                <div class="col-xs-9 text-right">
                                                                    <div style="font-size:25px" id="num_documentos_procesados">0</div>
                                                                    <div>Documentos con exito</div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="panel panel-danger" style="border-radius:10px">
                                                    <div class="panel-heading  bg-red" style="border-radius:10px">
                                                        <div class="panel-heading">
                                                            <div class="row">
                                                                <div class="col-xs-3">
                                                                    <i class="fa fa-solid fa-circle-exclamation fa-5x"></i>
                                                                </div>
                                                                <div class="col-xs-9 text-right">
                                                                    <div style="font-size:25px" id="num_documentos_con_error">0</div>
                                                                    <div>Documentos con error</div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- <div class="col-md-12" align="center">
                                            <span class="label label-primary">Número de documentos a procesar: <label id="num_documentos_total">0</label> </span>
                                            <br><br>
                                            <span class="label label-success">Número de documentos con exito: <label id="num_documentos_procesados">0</label> </span>
                                            <br><br>
                                            <span class="label label-danger">Número de documentos con error: <label id="num_documentos_con_error">0</label> </span>
                                            <br><br>
                                            <span class="label label-warning">Tiempo restante estimado: <label id="tiempo_restante">0</label> </span>
                                        </div> -->

                                    </div>
                                    <div class="row" id="div_msn_2" style="display:none">
                                        <div class="col-md-12" align="center">
                                            <span class="label label-warning"><label id="tiempo_restante"><i class="fa-solid fa-spinner fa-spin"></i> Procesando, espere por favor.</label> </span>
                                        </div>
                                    </div>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
				<div id="divFormularioDetalle" class="col-md-12"></div>
               
                
				<div id="divBoton" class="col-md-12"></div>
				<div class="col-md-12">
					<div class="modal fade" id="miModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
						<div class="modal-dialog modal-lg" role="document">
							<div class="modal-content">
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
										<span aria-hidden="true">&times;</span>
									</button>
									<h4 class="modal-title" id="myModalLabel">Listado de Mensajes SRI</h4>
								</div>
								<div class="modal-body">
									<table id="example" class="table table-striped table-bordered table-hover table-condensed" style="width: 100%;" align="center">
										<thead>
											<tr class="primary">
												<th style="width: 10%;">Codigo</th>
												<th style="width: 45%;">Mensaje</th>
												<th style="width: 45%;">Detalle</th>
											</tr>
										</thead>
										<tbody>
										</tbody>
									</table>  
								</div>
								<div class="modal-footer">
									<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
								</div>
							</div>
						</div>
					</div>
				</div>
            </div>

            <div style="width: 100%; margin: 0px;">
		<div class="modal fade" id="miModalLoad" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-lg" role="document" style="width: 90%;">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
						<h4 class="modal-title" id="modal_load_titulo"> TITULO </h4>
					</div>
					<div class="modal-body" id="modal_load_body">

					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">
							Cerrar
						</button>
					</div>
				</div>
			</div>
		</div>
	</div>
        <div class="modal fade" id="ModalEdita" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"aria-hidden="true"></div>
        </form>
    </div>
    <script>genera_formulario();</script>
    <? /*     * ***************************************************************** */ ?>
    <? /* NO MODIFICAR ESTA SECCION */ ?>
<? } ?>
<? include_once(FOOTER_MODULO); ?>
<? /* * ***************************************************************** */ ?>