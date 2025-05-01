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
        }
        else
        {
            $_SESSION['last_request']  = fnEscreve($request);          

            $cod_comfaixa = fnLimpaCampoZero($_POST['COD_COMFAIXA']);           
            $nom_faixa = fnLimpacampo($_POST['NOM_FAIXA']);         
            $num_faixaini = fnLimpaCampo($_POST['NUM_FAIXAINI']);
            $num_faixafim =fnLimpaCampo($_POST['NUM_FAIXAFIM']);
            // $des_icone = fnLimpaCampo($_REQUEST['DES_ICONE']);
            // $des_cor = fnLimpaCampoHtml($_REQUEST['DES_COR']);           
            if (empty($_REQUEST['LOG_BESTVAL'])) {$log_bestval='N';}else{$log_bestval=$_REQUEST['LOG_BESTVAL'];}
            // if (empty($_REQUEST['LOG_PRECO'])) {$log_preco='N';}else{$log_preco=$_REQUEST['LOG_PRECO'];}
            $num_ordenac = $_POST['NUM_ORDENAC'];
            
            //fnEscreve($nom_submenus);

            $cod_usucada = $_SESSION['SYS_COD_USUARIO'];
            
       
            $opcao = $_REQUEST['opcao'];
            $hHabilitado = $_REQUEST['hHabilitado'];
            $hashForm = $_REQUEST['hashForm'];          
            
            if ($opcao != ''){
            
                // $sql = "CALL SP_ALTERA_COMUNICACAO (
                //  '".$cod_canalcom."',
                //  '".$cod_tpcom."',
                //  '".$abv_canalcom."',                
                //  '".$des_canalcom."',                
                //  '".$des_icone."', 
                //  '".$des_cor."', 
                //  '".$log_personaliza."', 
                //  '".$log_preco."', 
                //  '".$opcao."'    
                // ) ";
                
                //echo $sql;
                //fnEscreve($cod_submenus);
    
                // mysqli_query($connAdm->connAdm(),trim($sql)) or die(mysqli_error());             
                
                //mensagem de retorno
                switch ($opcao)
                {
                    case 'CAD':
                        $sql = "INSERT INTO COMUNICACAO_FAIXAS(
                                    NOM_FAIXA,
                                    NUM_FAIXAINI,
                                    NUM_FAIXAFIM,
                                    LOG_BESTVAL,
                                    COD_USUCADA
                                    )VALUES(
                                    '$nom_faixa',
                                    '".fnValorSql($num_faixaini)."',
                                    '".fnValorSql($num_faixafim)."',
                                    '$log_bestval', 
                                    $cod_usucada
                                    );";
                
                //echo $sql;
                //fnEscreve($sql);                              
                
                // mysqli_query($connAdm->connAdm(),trim($sql)) or die(mysqli_error());

                        $msgRetorno = "Registro gravado com <strong>sucesso!</strong>"; 
                        break;

                    case 'ALT':
                        $sql = "UPDATE COMUNICACAO_FAIXAS SET
                                -- COD_COMFAIXA = $cod_comfaixa, 
                                NOM_FAIXA = '$nom_faixa', 
                                NUM_FAIXAINI = '".fnValorSql($num_faixaini)."', 
                                NUM_FAIXAFIM = '".fnValorSql($num_faixafim)."', 
                                LOG_BESTVAL = '$log_bestval'                                                            
                                WHERE COD_COMFAIXA = $cod_comfaixa;";
                                    
                // mysqli_query($connAdm->connAdm(),trim($sql)) or die(mysqli_error());
                            

                        $msgRetorno = "Registro alterado com <strong>sucesso!</strong>";        
                        break;

                    case 'EXC':
                        $sql = "DELETE FROM COMUNICACAO_FAIXAS WHERE COD_COMFAIXA = $cod_comfaixa;";
                        mysqli_query($connAdm->connAdm(),trim($sql)) or die(mysqli_error());
                        $msgRetorno = "Registro excluido com <strong>sucesso!</strong>";        
                        break;
                    break;
                }           
                $msgTipo = 'alert-success';
                
            }  
            

        }
    }
      
    //fnMostraForm();

?>
<style>
    
    .table-icons button{
        background: #fff;
        color: #3c3c3c;
    }

    .table-icons button:hover{ background: #2c3e50; }

</style>
            
                    <div class="push30"></div> 
                    
                    <div class="row">               
                    
                        <div class="col-md12 margin-bottom-30">
                            <!-- Portlet -->
                            <div class="portlet portlet-bordered">
                                <div class="portlet-title">
                                    <div class="caption">
                                        <i class="glyphicon glyphicon-calendar"></i>
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
                                                                
                                    <div class="login-form">
                                    
                                        <form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">
                                                                                
                                        <fieldset>
                                            <legend>Dados Gerais</legend> 
                                            
                                                <div class="row">
                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                            <label for="inputName" class="control-label required">Código Faixa</label>
                                                            <input type="text" class="form-control input-sm leitura" readonly="readonly" name="COD_COMFAIXA" id="COD_COMFAIXA" value="">
                                                        </div>
                                                    </div>

                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label for="inputName" class="control-label required">Nome Faixa</label>
                                                            <input type="text" class="form-control input-sm" name="NOM_FAIXA" id="NOM_FAIXA" maxlength="50" required>
                                                            <div class="help-block with-errors"></div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label for="inputName" class="control-label">Faixa Inicial</label>
                                                            <input type="text" class="form-control input-sm money" name="NUM_FAIXAINI" id="NUM_FAIXAINI" maxlength="20">
                                                            <div class="help-block with-errors"></div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label for="inputName" class="control-label">Faixa Final</label>
                                                            <input type="text" class="form-control input-sm money" name="NUM_FAIXAFIM" id="NUM_FAIXAFIM" maxlength="20">
                                                            <div class="help-block with-errors"></div>
                                                        </div>
                                                    </div>      

                                                    <div class="col-md-1">   
                                                        <div class="form-group">
                                                            <label for="inputName" class="control-label">Melhor Valor</label> 
                                                            <div class="push5"></div>
                                                                <label class="switch">
                                                                <input type="checkbox" name="LOG_BESTVAL" id="LOG_BESTVAL" class="switch" value="S" checked>
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
                                              <button type="submit" name="EXC" id="EXC" class="btn btn-primary getBtn"><i class="fa fa-times" aria-hidden="true"></i>&nbsp; Excluir</button>
                                            
                                        </div>

                                        <input type="hidden" name="NUM_ORDENAC" id="NUM_ORDENAC" value="">
                                        
                                        <input type="hidden" name="opcao" id="opcao" value="">
                                        <input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" /> 
                                        <input type="hidden" name="hHabilitado" id="hHabilitado" value="N">     
                                        
                                        <div class="push5"></div> 
                                        
                                        </form>
                                        
                                        <div class="push50"></div>
                                        
                                        <div class="col-lg-12">
                                        
                                        <div id="divId_sub">
                                        </div>

                                            <div class="no-more-tables">
                                        
                                                <form name="formLista">
                                                
                                                <table class="table table-bordered table-striped table-hover table-sortable">
                                                  <thead>
                                                    <tr>
                                                      <th width="40"></th>
                                                      <th width="40"></th>
                                                      <th>Código</th>
                                                      <th>Nome da Faixa</th>
                                                      <th>Faixa Inicial</th>
                                                      <th>Faixa Final</th>                                                    
                                                      <!-- <th>Ícone</th> -->
                                                    </tr>
                                                  </thead>
                                                <tbody>
                                                  
                                                <?php 
                                                
                                                    $sql = "select * from COMUNICACAO_FAIXAS ORDER BY NUM_ORDENAC";
                                                    $arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
                                                    
                                                    $count=0;
                                                    while ($qrBuscaModulos = mysqli_fetch_assoc($arrayQuery))
                                                      {                                                       
                                                        $count++;   
                                                        echo"
                                                            <tr>
                                                              <td align='center'><span class='fal fa-equals grabbable' data-id='".$qrBuscaModulos['COD_COMFAIXA']."'></span></td>
                                                              <td align='center'><input type='radio' name='radio1' onclick='retornaForm(".$count.")'></th>
                                                              <td>".$qrBuscaModulos['COD_COMFAIXA']."</td>
                                                              <td>".$qrBuscaModulos['NOM_FAIXA']."</td>
                                                              <td>".fnValor($qrBuscaModulos['NUM_FAIXAINI'],2)."</td>
                                                              <td>".fnValor($qrBuscaModulos['NUM_FAIXAFIM'],2)."</td>                                                                                                                 
                                                            </tr>
                                                            <input type='hidden' id='ret_COD_COMFAIXA_".$count."' value='".$qrBuscaModulos['COD_COMFAIXA']."'>
                                                            <input type='hidden' id='ret_NOM_FAIXA_".$count."' value='".$qrBuscaModulos['NOM_FAIXA']."'>
                                                            <input type='hidden' id='ret_NUM_FAIXAINI_".$count."' value='".fnValor($qrBuscaModulos['NUM_FAIXAINI'],2)."'>                                                           
                                                            <input type='hidden' id='ret_NUM_FAIXAFIM_".$count."' value='".fnValor($qrBuscaModulos['NUM_FAIXAFIM'],2)."'>
                                                            <input type='hidden' id='ret_LOG_BESTVAL_".$count."' value='".$qrBuscaModulos['LOG_BESTVAL']."'>                                                            
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
                                
                                </div>
                            </div>
                            <!-- fim Portlet -->
                        </div>
                        
                    </div>                  
                        
                    <div class="push20"></div> 
                    
    
    <link rel="stylesheet" href="../css/bootstrap-iconpicker.min.css"/>
    
    <script type="text/javascript" src="../js/bootstrap-iconpicker-iconset-fa5.js"></script>
    <script type="text/javascript" src="../js/bootstrap-iconpicker.js"></script>
    <script src="js/plugins/minicolors/jquery.minicolors.min.js"></script>
    <link rel="stylesheet" href="js/plugins/minicolors/jquery.minicolors.css">
    
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    
    <script>
        $(function() {
            
            $( ".table-sortable tbody" ).sortable();
                
            $('.table-sortable tbody').sortable({
                handle: 'span'
            });

           $(".table-sortable tbody").sortable({
           
                    stop: function(event, ui) {
                        
                        var Ids = "";
                        $('table tr').each(function( index ) {
                            if(index != 0){
                                    Ids =  Ids + $(this).children().find('span.fa-equals').attr('data-id') +",";
                            }
                        });
                        
                        //update ordenação
                        //console.log(Ids.substring(0,(Ids.length-1)));
                        
                        var arrayOrdem = Ids.substring(0,(Ids.length-1));
                        //alert(arrayOrdem);
                        execOrdenacao(arrayOrdem,14);
                    
                        function execOrdenacao(p1,p2) {
                            //alert(p2);
                            $.ajax({
                                type: "GET",
                                url: "ajxOrdenacao.php",
                                data: { ajx1:p1,ajx2:p2},
                                beforeSend:function(){
                                    //$('#divId_sub').html('<div class="loading" style="width: 100%;"></div>');
                                },
                                success:function(data){
                                    //$("#divId_sub").html(data); 
                                },
                                error:function(){
                                    $('#divId_sub').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> Falha no processamento...</p>');
                                }
                            });     
                        }
                        
                    }
                    
            });
                    

            $( ".table-sortable tbody" ).disableSelection();        
            
        });
    </script>
    
    <script type="text/javascript">
        
        $(document).ready( function() {
            
            //arrastar 
            $('.grabbable').on('change', function(e) { 
                //console.log(e.icon);
                $("#DES_ICONE").val(e.icon);        
            }); 

            $(".grabbable").click(function() {
                $(this).parent().addClass('selected').siblings().removeClass('selected');

            });

            //color picker
            $('.pickColor').minicolors({
                control: $(this).attr('data-control') || 'hue',             
                theme: 'bootstrap'
            });
            
            // //icon picker
            // $('.btnSearchIcon').iconpicker({ 
            //  cols: 8,
            //  iconset: 'fontawesome',   
            //  rows: 6,
            //  searchText: 'Procurar  &iacute;cone'
            // });  
            
            //capturando o ícone selecionado no botão
            $('#btniconpicker').on('change', function(e) {
                $('#DES_ICONE').val(e.icon);
                //alert($('#DES_ICONE').val());
            });
            
        });

                
        function retornaForm(index){
            $("#formulario #COD_COMFAIXA").val($("#ret_COD_COMFAIXA_"+index).val());
            $("#formulario #NOM_FAIXA").val($("#ret_NOM_FAIXA_"+index).val());
            $("#formulario #NUM_FAIXAINI").val($("#ret_NUM_FAIXAINI_"+index).val());
            $("#formulario #NUM_FAIXAFIM").val($("#ret_NUM_FAIXAFIM_"+index).val());
            if ($("#ret_LOG_BESTVAL_"+index).val() == 'S'){$('#formulario #LOG_BESTVAL').prop('checked', true);}
            else {$('#formulario #LOG_BESTVAL').prop('checked', false);}
            // $("#formulario #COD_TPCOM").val($("#ret_COD_TPCOM_"+index).val());
            // $("#formulario #COD_TPCOM").val($("#ret_COD_TPCOM_"+index).val()).trigger("chosen:updated");
            // $('#btniconpicker').iconpicker('setIcon', $("#ret_DES_ICONE_"+index).val());
            // $("#formulario #DES_COR").val($("#ret_DES_COR_"+index).val());
            // $("#formulario #NUM_ORDENAC").val($("#ret_NUM_ORDENAC_"+index).val());
            $('#formulario').validator('validate');         
            $("#formulario #hHabilitado").val('S');                     
        }
        
    </script>   
   