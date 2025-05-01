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
            $cod_readme = fnLimpaCampoZero($_REQUEST['COD_README']);
			$nom_titulo = fnLimpaCampo($_REQUEST['NOM_TITULO']);
			$des_conteudo = addslashes(htmlentities($_REQUEST['DES_CONTEUDO']));
			$des_chamada = fnLimpaCampo($_REQUEST['DES_CHAMADA']);
            $cod_categor = fnLimpaCampoZero($_REQUEST['COD_CATEGOR']);
            $cod_subcategor = fnLimpaCampoZero($_REQUEST['COD_SUBCATEGOR']);
            $cod_modulos = fnLimpaCampoZero($_REQUEST['COD_MODULOS']);
			$cod_sistema = fnLimpaCampoZero($_REQUEST['COD_SISTEMA']);
            $des_imagem = fnLimpaCampo($_REQUEST['DES_IMAGEM']);
            if (empty($_REQUEST['LOG_PUBLICO'])) {$log_publico='N';}else{$log_publico=$_REQUEST['LOG_PUBLICO'];}
            
            $cod_usucada = $_SESSION['SYS_COD_USUARIO'];
       
            $opcao = $_REQUEST['opcao'];
            $hHabilitado = $_REQUEST['hHabilitado'];
            $hashForm = $_REQUEST['hashForm'];          
            
            if ($opcao != ''){
                
                switch ($opcao)
                {
                    case 'CAD':
                         $sql = "INSERT INTO README(
                                            NOM_TITULO,
                                            DES_CHAMADA,
                                            DES_CONTEUDO,
                                            COD_CATEGOR,
                                            COD_SUBCATEGOR,
                                            COD_MODULOS,
                                            COD_SISTEMA,
                                            DES_IMAGEM,
                                            LOG_PUBLICO,
                                            COD_USUCADA
                                        )VALUES(
                                            '$nom_titulo',
                                            '$des_chamada',
                                            '$des_conteudo',
                                            '$cod_categor',
                                            '$cod_subcategor',
                                            '$cod_modulos',
                                            '$cod_sistema',
                                            '$des_imagem',
                                            '$log_publico',
                                            '$cod_usucada'
                                            )";
                
                        //echo $sql;
                        //fnEscreve($sql);                             
                        
                        mysqli_query($connAdm->connAdm(),trim($sql));

                        $msgRetorno = "Registro gravado com <strong>sucesso!</strong>"; 
                        break;

                    case 'ALT':
                        $sql = "UPDATE README SET 
										NOM_TITULO='$nom_titulo',
										DES_CHAMADA='$des_chamada',
										DES_CONTEUDO='$des_conteudo',
										COD_CATEGOR='$cod_categor',
										COD_SUBCATEGOR='$cod_subcategor',
										COD_MODULOS='$cod_modulos',
										COD_SISTEMA='$cod_sistema',
										DES_IMAGEM='$des_imagem',
										LOG_PUBLICO='$log_publico',
										COD_ALTERAC='$cod_usucada',
										DAT_ALTERAC=NOW()
                        WHERE COD_README=$cod_readme
                        ";
                        //fnEscreve($sql);exit;
                                    
                        mysqli_query($connAdm->connAdm(),trim($sql));
                            

                        $msgRetorno = "Registro alterado com <strong>sucesso!</strong>";        
                        break;

                    case 'EXC':
                        $sql = "DELETE FROM README WHERE COD_README = $cod_readme;";
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
    if (is_numeric(fnLimpacampo(fnDecode($_GET['id'])))){
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
                                                                <input type="text" class="form-control input-sm leitura"  readonly="readonly" name="COD_README" id="COD_README" value="">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-8">
                                                        <div class="form-group">
                                                            <label for="inputName" class="control-label required">Título</label>
                                                            <input type="text" class="form-control input-sm" name="NOM_TITULO" id="NOM_TITULO" value="">
                                                            <div class="help-block with-errors"></div>
                                                        </div>
                                                    </div>


                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label for="inputName" class="control-label">Chamada</label>
                                                            <input type="text" class="form-control input-sm" name="DES_CHAMADA" id="DES_CHAMADA" maxlength="400" value="">
                                                            <div class="help-block with-errors"></div>
                                                        </div>
                                                    </div>


                                                </div>

                                                <div class="row">

                                                    <div class="col-lg-12">
                                                        <div class="form-group">
                                                            <label for="inputName" class="control-label required">Descri&ccedil;&atilde;o: </label>
                                                            <textarea class="editor form-control input-sm" rows="6" name="DES_CONTEUDO" id="DES_CONTEUDO"></textarea>
                                                            <div class="help-block with-errors"></div>
                                                        </div>
                                                    </div>

                                                </div>

                                                <div class="row">
                                                    
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="inputName" class="control-label">Categoria</label>
                                                                <select  class="chosen-select-deselect" data-placeholder="Selecione a categoria" name="COD_CATEGOR" id="COD_CATEGOR">
                                                                    <option value=""></option>                    
                                                                    <?php                                                                   

                                                                        $sql = "SELECT COD_CATEGOR, DES_CATEGOR FROM CATEGORIA_TUTORIAL order by DES_CATEGOR ";
                                                                        $arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
                                                                    
                                                                        while ($qrListaComunicacao = mysqli_fetch_assoc($arrayQuery))
                                                                          {                                                     
                                                                            ?>
                                                                            <option value="<?php echo $qrListaComunicacao['COD_CATEGOR']; ?>"><?php echo $qrListaComunicacao['DES_CATEGOR']; ?></option> 
                                                                                 
                                                                            <?php } ?>  
                                                                </select>                                                                   
                                                            <div class="help-block with-errors"></div>                                                          
                                                        </div>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <div id="subcatConteudo">
                                                            <div class="form-group">
                                                                <label for="inputName" class="control-label">Subcategoria</label>
                                                                    <select  class="chosen-select-deselect" data-placeholder="Selecione a subcategoria" name="COD_SUBCATEGOR" id="COD_SUBCATEGOR">
                                                                          
                                                                    </select>                                                                   
                                                                <div class="help-block with-errors"></div>                                                          
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <label for="inputName" class="control-label">Módulo</label>
                                                        <div class="input-group">
                                                        <span class="input-group-btn">
                                                        <a type="button" name="btnBusca" id="btnBusca" style="height:35px;" class="btn btn-primary btn-sm addBox" data-url="action.php?mod=<?php echo fnEncode(1477)?>&id=<?php echo fnEncode($cod_modulos)?>&pop=true" data-title="Busca Categoria"><i class="fa fa-search" aria-hidden="true" style="padding-top: 3px;" ></i></a>
                                                        </span>
                                                        <input type="text" name="NOM_MODULOS" id="NOM_MODULOS" value="" maxlength="50" class="form-control input-sm" readonly style="border-radius:0 3px 3px 0;" data-error="Campo obrigatório">
                                                        <input type="hidden"name="COD_MODULOS" id="COD_MODULOS" value="">
                                                        </div>
                                                        <div class="help-block with-errors"></div>                                                      
                                                    </div>
                                                        
                                                </div>

                                                <div class="row">
                                                                                                       

													<div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label">Sistema</label>
																<select data-placeholder="Selecione o tipo de usuário" name="COD_SISTEMA" id="COD_SISTEMA" class="chosen-select-deselect">
																	<option value="">&nbsp;</option>					
																	<?php 																	
																		$sql = "SELECT COD_SISTEMAS FROM EMPRESAS WHERE COD_EMPRESA = '0".$cod_empresa."' ";
																		$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());																	
																		$qrSistemasEmpresa = mysqli_fetch_assoc($arrayQuery);
																		$sistemasEmpresa = $qrSistemasEmpresa['COD_SISTEMAS'];
																		fnEscreve($sistemasEmpresa);
															
																		$sql = "";
																		if ($cod_empresa <= 0){
																			$sql = "SELECT COD_SISTEMA,DES_SISTEMA FROM SISTEMAS";
																		}else{
																			$sql = "SELECT COD_SISTEMA,DES_SISTEMA FROM SISTEMAS WHERE COD_SISTEMA IN(0".$sistemasEmpresa.") ";
																		}
																		$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
																	
																		while ($qrListaTipoUsu = mysqli_fetch_assoc($arrayQuery))
																		  {														
																			echo"
																				  <option value='".$qrListaTipoUsu['COD_SISTEMA']."'>".$qrListaTipoUsu['DES_SISTEMA']."</option> 
																				"; 
																			  }											
																	?>	
																</select>	
															<div class="help-block with-errors"></div>															
														</div>
													</div>	

                                                    <div class="col-md-4">
                                                        <label for="inputName" class="control-label">Imagem</label>
                                                        <div class="input-group">
                                                            <span class="input-group-btn">
                                                                <a type="button" name="btnBusca" id="btnBusca" style="height:35px;" class="btn btn-primary upload" idinput="DES_IMAGEM" extensao="img"><i class="fa fa-cloud-upload" aria-hidden="true"></i></a>
                                                            </span>
                                                            <input type="text" name="DES_IMAGEM" id="DES_IMAGEM" class="form-control input-sm" style="border-radius: 0 3px 3px  0;" maxlength="250" value="">
                                                        </div>                                                              
                                                        <span class="help-block"></span>
                                                    </div>

                                                    <div class="col-md-1">   
                                                        <div class="form-group">
                                                            <label for="inputName" class="control-label">Público</label> 
                                                            <div class="push5"></div>
                                                                <label class="switch">
                                                                <input type="checkbox" name="LOG_PUBLICO" id="LOG_PUBLICO" class="switch" value="S" checked>
                                                                <span></span>
                                                                </label>
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
                                                      <th>Categoria</th>
                                                      <th>Subcategoria</th>
                                                      <th>Módulo</th>
                                                      <th>Título</th> 
                                                      <th>Publico</th>
                                                    </tr>
                                                  </thead>
                                                <tbody>
                                                  
                                                <?php 
                                                
                                                        $sql = "SELECT AT.*, CT.DES_CATEGOR, ST.DES_SUBCATEGOR, M.NOM_MODULOS FROM README AT
                                                                LEFT JOIN CATEGORIA_TUTORIAL CT ON CT.COD_CATEGOR = AT.COD_CATEGOR
                                                                LEFT JOIN SUBCATEGORIA_TUTORIAL ST ON ST.COD_SUBCATEGOR = AT.COD_SUBCATEGOR
                                                                LEFT JOIN MODULOS M ON M.COD_MODULOS = AT.COD_MODULOS
                                                                "; 
                                                                


                                                    $arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
                                                    
                                                    $count=0;
                                                    while ($qrBuscaModulos = mysqli_fetch_assoc($arrayQuery))
                                                      {    

                                                            $categoria ="";
                                                            $publico ="";                                                            


                                                             if($qrBuscaModulos['LOG_PUBLICO'] == 'S'){
                                                                $publico = "<span class='fas fa-check text-success'></span>";
                                                            }else{
                                                                $publico = "<span class='fas fa-times text-danger'></span>";
                                                            }

                                                        $count++;   
                                                        echo"
                                                            <tr>
                                                              <td align='center'><input type='radio' name='radio1' onclick='retornaForm(".$count.")'></th>
                                                              <td>".$qrBuscaModulos['COD_README']."</td>
                                                              <td>".$qrBuscaModulos['DES_CATEGOR']."</td>
                                                              <td>".$qrBuscaModulos['DES_SUBCATEGOR']."</td>
                                                              <td>".$qrBuscaModulos['NOM_MODULOS']."</td>                                                              
                                                              <td>".$qrBuscaModulos['NOM_TITULO']."</td>
                                                              <td class='text-center'>".$publico."</td>                                                                              
                                                            </tr>
                                                            <input type='hidden' id='ret_COD_README_".$count."' value='".$qrBuscaModulos['COD_README']."'>
                                                            <input type='hidden' id='ret_NOM_TITULO_".$count."' value='".$qrBuscaModulos['NOM_TITULO']."'>
                                                            <input type='hidden' id='ret_DES_CHAMADA_".$count."' value='".$qrBuscaModulos['DES_CHAMADA']."'>
                                                            <input type='hidden' id='ret_DES_CONTEUDO_".$count."' value='".$qrBuscaModulos['DES_CONTEUDO']."'>
                                                            <input type='hidden' id='ret_COD_CATEGOR_".$count."' value='".$qrBuscaModulos['COD_CATEGOR']."'>
                                                            <input type='hidden' id='ret_COD_SUBCATEGOR_".$count."' value='".$qrBuscaModulos['COD_SUBCATEGOR']."'>
                                                            <input type='hidden' id='ret_COD_MODULOS_".$count."' value='".$qrBuscaModulos['COD_MODULOS']."'>
															<input type='hidden' id='ret_NOM_MODULOS_".$count."' value='".$qrBuscaModulos['NOM_MODULOS']."'>
                                                            <input type='hidden' id='ret_COD_SISTEMA_".$count."' value='".$qrBuscaModulos['COD_SISTEMA']."'>
                                                            <input type='hidden' id='ret_DES_IMAGEM_".$count."' value='".$qrBuscaModulos['DES_IMAGEM']."'>
                                                            <input type='hidden' id='ret_LOG_PUBLICO_".$count."' value='".$qrBuscaModulos['LOG_PUBLICO']."'>
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

           $('#COD_CATEGOR').change(function() { 
                var cod_categor = $(this).val();
               
                $.ajax({
                    method: "POST",
                    url: "ajxSubcatTutorial.php",
                    data: {COD_CATEGOR: cod_categor},
                    beforeSend:function(){
                        $('#subcatConteudo').html("<div class='loading' style='width:100%'></div>");
                    },
                    success:function(data){
                        $('#subcatConteudo').html(data);
                        // console.log(data);
                    }
                });
                       
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

            $('#COD_USURES').val("<?=$qrChmd[COD_USURES]?>").trigger("chosen:updated");
            $('#COD_STATUS').val("<?=$qrChmd[COD_STATUS]?>").trigger("chosen:updated");

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
            $("#formulario #COD_README").val($("#ret_COD_README_"+index).val());
			$("#formulario #NOM_TITULO").val($("#ret_NOM_TITULO_"+index).val());
			$("#formulario #DES_CHAMADA").val($("#ret_DES_CHAMADA_"+index).val());
            $("#formulario #COD_CATEGOR").val($("#ret_COD_CATEGOR_"+index).val()).trigger('chosen:updated');
            
            var cod_categor = $("#ret_COD_CATEGOR_"+index).val();
            $.ajax({
                method: "POST",
                url: "ajxSubcatTutorial.php",
                data: {COD_CATEGOR: cod_categor},
                beforeSend:function(){
                    $('#subcatConteudo').html("<div class='loading' style='width:100%'></div>");
                },
                success:function(data){
                    $('#subcatConteudo').html(data);
                    $("#formulario #COD_SUBCATEGOR").val($("#ret_COD_SUBCATEGOR_"+index).val()).trigger('chosen:updated');
                    // console.log(data);
                }
            }); 
            $("#formulario #COD_MODULOS").val($("#ret_COD_MODULOS_"+index).val());
            $("#formulario #NOM_MODULOS").val($("#ret_NOM_MODULOS_"+index).val());
			$("#formulario #COD_SISTEMA").val($("#ret_COD_SISTEMA_"+index).val());
            $("#formulario .editor").jqteVal($("#ret_DES_CONTEUDO_"+index).val());
            $("#formulario #DES_IMAGEM").val($("#ret_DES_IMAGEM_"+index).val());

            if ($("#ret_LOG_PUBLICO_"+index).val() == 'S'){

                $('#formulario #LOG_PUBLICO').prop('checked', true);
                $('#empresas').fadeOut('fast');
                $('#COD_EMPRESA').val('').trigger('chosen:updated').prop('required',false);

            }else {

                $('#formulario #LOG_PUBLICO').prop('checked', false);
                $('#empresas').fadeIn('fast');
                $('#COD_EMPRESA').prop('required',true);

            }   

            $('#formulario').validator('validate');         
            $("#formulario #hHabilitado").val('S');                     
        }

        
        

    </script>   