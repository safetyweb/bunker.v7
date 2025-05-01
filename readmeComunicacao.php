<?php
    //echo fnDebug('true');

    $hashLocal = mt_rand(); 

    if( $_SERVER['REQUEST_METHOD']=='POST' )
    {
        $request = md5( implode( $_POST ) );
        
        if( isset( $_SESSION['last_request'] ) && $_SESSION['last_request']== $request )
        {
            $msgRetorno = 'Essa página já foi utilizada';
            $msgTipo = 'alert-warning';
            var_dump($msgTipo);
        }
        else
        {
            $_SESSION['last_request']  = $request;            
            $cod_regra = fnLimpaCampoZero($_REQUEST['COD_REGRA']);
            $cod_empresa = fnLimpaCampoZero($_REQUEST['COD_EMPRESA']);
            $cod_tpusuario = fnLimpaCampoZero($_REQUEST['COD_TPUSUARIO']);
            $dat_gerar_ate = $_REQUEST['DAT_GERAR_ATE'];
			$des_status = fnLimpaCampo($_REQUEST['DES_STATUS']);

			$dat_gerar_ate = (@$dat_gerar_ate == ""?"NULL":"'$dat_gerar_ate'");


            $cod_usucada = $_SESSION['SYS_COD_USUARIO'];
       
            $opcao = $_REQUEST['opcao'];
            $hHabilitado = $_REQUEST['hHabilitado'];
            $hashForm = $_REQUEST['hashForm'];          
            
            if ($opcao != ''){

                switch ($opcao)
                {
                    case 'CAD':
                         $sql = "INSERT INTO REGRAS_COMUNICACAO(
                                            COD_EMPRESA,
                                            COD_TPUSUARIO,
											DES_STATUS,
                                            DAT_GERAR_ATE,
                                            COD_USUCADA
                                            )VALUES(
                                            '$cod_empresa',
                                            '$cod_tpusuario',
											'$des_status',
                                            $dat_gerar_ate,
                                            $cod_usucada
                                            )";
                
                        //echo $sql;
                        //fnEscreve($sql);exit;
                        
                        mysqli_query($connAdm->connAdm(),trim($sql));

                        $msgRetorno = "Registro gravado com <strong>sucesso!</strong>"; 
                        break;

                    case 'ALT':
                        $sql = "UPDATE REGRAS_COMUNICACAO SET 
                                        COD_EMPRESA='$cod_empresa',
                                        COD_TPUSUARIO='$cod_tpusuario',
										DES_STATUS='$des_status',
                                        DAT_GERAR_ATE=$dat_gerar_ate,
										COD_ALTERAC='$cod_usucada',
										DAT_ALTERAC=NOW()
                        WHERE COD_REGRA=$cod_regra
                        ";
                        //fnEscreve($sql);exit;
                                    
                        mysqli_query($connAdm->connAdm(),trim($sql));
                            

                        $msgRetorno = "Registro alterado com <strong>sucesso!</strong>";        
                        break;

                    case 'EXC':
                        $sql = "DELETE FROM REGRAS_COMUNICACAO WHERE COD_REGRA = $cod_regra;";
                        // fnEscreve($sql);
                        mysqli_query($connAdm->connAdm(),trim($sql));
                        $msgRetorno = "Registro excluido com <strong>sucesso!</strong>";        
                        break;
                    break;
                }           
                $msgTipo = 'alert-success';
                
            }  
            

        }
    }

    
    //busca dados da url    
    if (is_numeric(fnLimpacampo(fnDecode(@$_GET['id'])))){
        //busca dados da empresa
        $cod_empresa = fnDecode($_GET['id']);   
        $sql = "SELECT COD_EMPRESA, NOM_EMPRESA FROM empresas where COD_EMPRESA = '".$cod_empresa."' ";
        //fnEscreve($sql);
        $arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
        $qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);
        
        if (isset($arrayQuery)){
            $cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
            $nom_empresa = $qrBuscaEmpresa['NOM_EMPRESA'];
        }
                                                
    }else{
        $cod_empresa = 0;       
        //fnEscreve('entrou else');
    }
	$tipoUsuario = 0;


    //fnMostraForm(); 

?>
    
                    <div class="push30"></div>
                    
                    <div class="row">               
                    
                        <div class="col-md12 margin-bottom-30">
                            <!-- Portlet -->

                            <div class="portlet portlet-bordered">
                            

                                <div class="portlet-title">
                                    <div class="caption">
                                        <i class="fas fa-exclamation-circle"></i>
                                        <span class="text-primary"><?php echo $NomePg; ?></span>
                                    </div>
                                    <?php include "atalhosPortlet.php"; ?>
                                </div>

                                <div class="portlet-body">
                                    
                                    <?php if ($msgRetorno <> '') { ?>   
                                    <div class="alert <?php echo $msgTipo; ?> alert-dismissible top30 bottom30" role="alert" id="msgRetorno">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                     <?php echo $msgRetorno; ?>
                                    </div>
                                    <?php } ?>  
                                    
                                    <div class="push30"></div>



                                    <div class="login-form">

                                        <?php 
                                            include "abasReadme.php";
                                        ?>
                                    
                                        <form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">
                                        <div class="push30"></div>
                                            <fieldset>

                                                <legend>Dados</legend> 
                                                
                                                <div class="row">
                                                    <div class="col-md-1">
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label for="inputName" class="control-label required">Código</label>
                                                                <input type="text" class="form-control input-sm leitura"  readonly="readonly" name="COD_REGRA" id="COD_REGRA" value="">
                                                            </div>
                                                        </div>
                                                    </div>
													
													<div class="col-md-3">
														<div class="form-group">
															<label for="inputName" class="control-label required">Empresa</label>
																<select data-placeholder="Selecione uma empresa" name="COD_EMPRESA" id="COD_EMPRESA" class="chosen-select-deselect" required>
																	<option value=""></option>
																	<?php 
																	
																		$sql = "select COD_EMPRESA, NOM_EMPRESA from empresas where COD_EMPRESA IN (1,2,3) order by NOM_EMPRESA";
																		$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
																	
																		while ($qrListaEempresas = mysqli_fetch_assoc($arrayQuery))
																		  {	
																																
																			echo"
																				  <option value='".$qrListaEempresas['COD_EMPRESA']."'>".$qrListaEempresas['NOM_EMPRESA']."</option> 
																				"; 
																			  }											
																	?>	
																</select>
															<div class="help-block with-errors"></div>
														</div>
													</div>

													<div class="col-md-2">
														<div class="form-group">
														
															<label for="inputName" class="control-label required">Tipo de Usuário</label>
																<select data-placeholder="Selecione o tipo de usuário" name="COD_TPUSUARIO" id="COD_TPUSUARIO" class="chosen-select-deselect requiredChk" required>
																	<option value="">&nbsp;</option>					
																	<?php
																		//$sql = "select COD_TPUSUARIO, DES_TPUSUARIO from tipousuario WHERE COD_TPUSUARIO IN ($tipoUsuario) order by des_tpusuario ";
																		$sql = "select COD_TPUSUARIO, DES_TPUSUARIO from tipousuario order by des_tpusuario ";
																		$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
																	
																		while ($qrListaTipoUsu = mysqli_fetch_assoc($arrayQuery))
																		  {														
																			echo"
																				  <option value='".$qrListaTipoUsu['COD_TPUSUARIO']."'>".$qrListaTipoUsu['DES_TPUSUARIO']."</option> 
																				"; 
																			  }											

																	?>	
																</select>

															<div class="help-block with-errors"></div>
														</div>
													</div>	
													
													<div class="col-md-2">
														<div class="form-group">
														
															<label for="inputName" class="control-label">Status da Comunicação</label>
																<select data-placeholder="Selecione o tipo de usuário" name="DES_STATUS" id="DES_STATUS" class="chosen-select-deselect">
																	<option value="">&nbsp;</option>
																	<option value="Gerado">Gerado</option>
																	<option value="Pendente">Pendente</option>
																	<option value="Ativo">Ativo</option>
																</select>

															<div class="help-block with-errors"></div>
														</div>
													</div>	

													<div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label required">Gerar Comunicação Até</label>
															<input type="date" class="form-control input-sm" name="DAT_GERAR_ATE" id="DAT_GERAR_ATE" value="">
															<div class="help-block with-errors"></div>
														</div>
													</div>	

                                                </div>


                                            </fieldset>

                                            <div class="push10"></div>
                                            <hr>    
                                            <div class="form-group text-right col-lg-12">

                                                <button type="reset" class="btn btn-default"><i class="fa fa-star-half-o" aria-hidden="true"></i>&nbsp; Apagar</button>
                                                <button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp; Cadastrar</button>
                                                <button type="submit" name="ALT" id="ALT" class="btn btn-primary getBtn"><i class="fa fa-repeat" aria-hidden="true"></i>&nbsp; Alterar</button>
                                                <button type="cl" name="EXC" id="EXC" class="btn btn-primary getBtn"><i class="fa fa-times" aria-hidden="true"></i>&nbsp; Excluir</button>

                                            </div>

                                        <div class="push5"></div>
                                        <input type="hidden" name="opcao" id="opcao" value="">
                                        <input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" /> 
                                        <input type="hidden" name="hHabilitado" id="hHabilitado" value="S">                                         
                                        
                                        </form>
                                        
                                        <div class="push50"></div>


                                        <div class="no-more-tables">
                                        
                                                <form name="formLista">
                                                

                                                <table class="table table-bordered table-striped table-hover table-sortable">
                                                  <thead>
                                                    <tr>                                                      
                                                      <th width="40"></th>
                                                      <th>Código</th>
                                                      <th>Empresa</th>
                                                      <th>Tipo Usu&aacute;rio</th>
                                                      <th>Status</th>
                                                    </tr>
                                                  </thead>
                                                <tbody>
                                                  
                                                <?php 
                                                
                                                        $sql = "SELECT RC.*,E.NOM_EMPRESA,TU.DES_TPUSUARIO FROM REGRAS_COMUNICACAO RC
																	LEFT JOIN EMPRESAS E ON E.COD_EMPRESA = RC.COD_EMPRESA
																	LEFT JOIN TIPOUSUARIO TU ON TU.COD_TPUSUARIO = RC.COD_TPUSUARIO
                                                                "; 
                                                                


                                                    $arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
                                                    
                                                    $count=0;
                                                    while ($qrBuscaModulos = mysqli_fetch_assoc($arrayQuery))
                                                      {    

                                                        $count++;   
                                                        echo"
                                                            <tr>
                                                              <td align='center'><input type='radio' name='radio1' onclick='retornaForm(".$count.")'></th>
                                                              <td>".$qrBuscaModulos['COD_REGRA']."</td>
                                                              <td>".$qrBuscaModulos['NOM_EMPRESA']."</td>
                                                              <td>".$qrBuscaModulos['DES_TPUSUARIO']."</td>
                                                              <td>".$qrBuscaModulos['DES_STATUS']."</td>                                                                                                                                       
                                                            </tr>
                                                            <input type='hidden' id='ret_COD_REGRA_".$count."' value='".$qrBuscaModulos['COD_REGRA']."'>
                                                            <input type='hidden' id='ret_COD_EMPRESA_".$count."' value='".$qrBuscaModulos['COD_EMPRESA']."'>
                                                            <input type='hidden' id='ret_COD_TPUSUARIO_".$count."' value='".$qrBuscaModulos['COD_TPUSUARIO']."'>
                                                            <input type='hidden' id='ret_DES_STATUS_".$count."' value='".$qrBuscaModulos['DES_STATUS']."'>
                                                            <input type='hidden' id='ret_DAT_GERAR_ATE_".$count."' value='".$qrBuscaModulos['DAT_GERAR_ATE']."'>
                                                            "; 
                                                             
                                                          }                                         

                                                ?>
                                                    
                                                </tbody>
                                                </table>
                                                
                                                </form>

                                            </div>

                                    </div>                                      
                                    
                                    <div class="push"></div>
                                    
                                </div>                              
                                
                            </div><!-- fim Portlet -->
                        </div>
                            
                    </div>

                </div>

                <!-- modal -->                                  
                    <div class="modal fade" id="popModal" tabindex='-1'>
                        <div class="modal-dialog" style="">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
                                    <h4 class="modal-title"></h4>
                                </div>
                                <div class="modal-body">
                                    <iframe frameborder="0" style="width: 100%; height: 80%"></iframe>
                                </div>
                            <div class="modal-footer">
                                <button type="button" id="mymodal" class="btn btn-default" data-dismiss="modal">Close</button>
                                
                            </div>                                      
                            </div><!-- /.modal-content -->
                        </div><!-- /.modal-dialog -->
                    </div><!-- /.modal --> 
    
                        
                    <div class="push20"></div>

    <script type="text/javascript" src="js/plugins/datepicker/moment.js"></script>
    <script type="text/javascript" src="js/plugins/datepicker/moment-pt-br.js"></script>
    <script type="text/javascript" src="js/plugins/datepicker/bootstrap-datetimepicker.min.js"></script>
    <link rel="stylesheet" href="css/bootstrap-datetimepicker.css" />
    <link rel="stylesheet" type="text/css" href="js/plugins/jQuery-TE/jquery-te-1.4.0.css">
    <link rel="stylesheet" type="text/css" href="js/plugins/jQuery-TE/jquery-te.png">
    <script type="text/javascript" src="js/plugins/jQuery-TE/jquery-te-1.4.0.min.js"></script>
    
    
    <script type="text/javascript">
        $(function(){

            var idEmp = "<?=fnLimpaCampoZero($cod_empresa)?>";

            $("#COD_EMPRESA_COMBO").change(function() {
                idEmp = $('#COD_EMPRESA_COMBO').val();
                $("#COD_EMPRESA").val(idEmp);
                buscaCombo(idEmp);
            });

            
            // TextArea
            $(".editor").jqte(
            {
                sup: false,
                sub: false,
                outdent: false,
                indent: false,
                left: false,
                center: false,
                color: false,
                right: false,
                strike: false,
                source: false,
                link:false,
                unlink: false,              
                remove: false,
                rule: false,
                fsize: false,
                format: false,
            });


            $('.datePicker').datetimepicker({
                 format: 'DD/MM/YYYY'
                }).on('changeDate', function(e){
                    $(this).datetimepicker('hide');
            });

        });

        $('.upload').on('click', function (e) {
        var idField = 'arqUpload_' + $(this).attr('idinput');
        var typeFile = $(this).attr('extensao');

            $.dialog({
                title: 'Arquivo',
                content: '' +
                    '<form method = "POST" enctype = "multipart/form-data">' +
                    '<input id="' + idField + '" type="file" name="image" style="margin-bottom: 20px;" />' +
                    '<div class="progress" style="display: none">' +
                    '<div class="progress-bar progress-bar-info progress-bar-striped" role="progressbar" aria-valuemin="0" aria-valuemax="100" style="width:0%;">'+
                    '   <span style="position: absolute; display: block; width: 100%; color:#2c3e50;">12</span></div>' +
                    '</div>' +
                    '<a type="button" id="btnUploadFile" class="btn btn-primary btn-sm" style="font-weight: bold" onClick="uploadFile(\'' + idField + '\', \'' + typeFile + '\')">UPLOAD</a>' +
                    '</form>'
            });
        });

        function uploadFile(idField, typeFile) {
            var formData = new FormData();
            var nomeArquivo = $('#' + idField)[0].files[0]['name'];

            formData.append('arquivo', $('#' + idField)[0].files[0]);
            formData.append('diretorio', '../media/clientes/');
            formData.append('diretorioAdicional', 'artigo');
            formData.append('id', <?php echo $cod_empresa ?>);
            formData.append('typeFile', typeFile);

            $('.progress').show();
            $.ajax({
                xhr: function () {
                    var xhr = new window.XMLHttpRequest();
                    $('#btnUploadFile').addClass('disabled');
                    xhr.upload.addEventListener("progress", function (evt) {
                        if (evt.lengthComputable) {
                            var percentComplete = evt.loaded / evt.total;
                            percentComplete = parseInt(percentComplete * 100);
                            if (percentComplete !== 100) {
                                $('.progress-bar').css('width', percentComplete + "%");
                                $('.progress-bar > span').html(percentComplete + "%");
                            }
                        }
                    }, false);
                    return xhr;
                },
                url: '../uploads/uploaddoc.php',
                type: 'POST',
                data: formData,
                processData: false, // tell jQuery not to process the data
                contentType: false, // tell jQuery not to set contentType
                success: function (data) {
                    $('.jconfirm-open').fadeOut(300, function () {
                        $(this).remove();
                    });
                    if (!data.trim()) {
                        $('#' + idField.replace("arqUpload_", "")).val(nomeArquivo);
                        $.alert({
                            title: "Mensagem",
                            content: "Upload feito com sucesso",
                            type: 'green'
                        });

                    } else {
                        $.alert({
                            title: "Erro ao efetuar o upload",
                            content: data,
                            type: 'red'
                        });
                    }
                }
            });
        }

        function retornaForm(index){
            $("#formulario #COD_REGRA").val($("#ret_COD_REGRA_"+index).val());            
            $("#formulario #COD_TPUSUARIO").val($("#ret_COD_TPUSUARIO_"+index).val()).trigger('chosen:updated');
            $("#formulario #COD_EMPRESA").val($("#ret_COD_EMPRESA_"+index).val()).trigger('chosen:updated');
			$("#formulario #DES_STATUS").val($("#ret_DES_STATUS_"+index).val()).trigger('chosen:updated');
			$("#formulario #DAT_GERAR_ATE").val($("#ret_DAT_GERAR_ATE_"+index).val());
            $('#formulario').validator('validate');         
            $("#formulario #hHabilitado").val('S');                     
        }

        
        

    </script>    