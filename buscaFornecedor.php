<?php
	
//echo fnDebug('true');

$hashLocal = mt_rand();	

if( $_SERVER['REQUEST_METHOD']=='POST' )
{

        //$cod_empresa = fnLimpacampo(fnDecode($_REQUEST['COD_EMPRESA']));
        //$cod_empresaCode = fnLimpacampo($_REQUEST['COD_EMPRESA']);
        $cod_cliente  = fnLimpacampo($_REQUEST['COD_CLIENTE']);
        $nom_cliente  = fnLimpacampo($_REQUEST['NOM_CLIENTE']);	
        $num_cartao = fnLimpacampo($_REQUEST['NUM_CARTAO']);	
        $des_emailus = fnLimpacampo($_REQUEST['DES_EMAILUS']);	
        $num_telefon = fnLimpacampo($_REQUEST['NUM_TELEFON']);	
        $num_cgcecpf = fnLimpacampo(fnLimpaDoc($_REQUEST['NUM_CGCECPF']));

        // fnEscreve($num_cgcecpf);

}else{ 

        //$cod_empresa = 0;
        //$cod_empresaCode = 0;
        $cod_cliente  = 0;
        $nom_cliente  = "";

} 

$cod_empresa = fnDecode($_GET['id']);

if (isset($_GET['op'])) {
                $opcao = fnLimpacampo($_GET['op']);
                $cod_indicado = fnLimpacampoZero(fnDecode($_GET['idC']));
                $sql = "SELECT NUM_CGCECPF FROM CLIENTES WHERE COD_CLIENTE = $cod_indicado";
                $qrCpf = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa,''),$sql));
                $cpfIndicado = $qrCpf[NUM_CGCECPF];
                if(strlen($cpfIndicado) == 10){
                        $cpfIndicado = "0".$cpfIndicado;
                }
        }else{
                $cod_indicado = 0;
                $cpfIndicado = 0;
        }	

//fnEscreve($cod_indicado);
//fnEscreve($nom_cliente);

// fnEscreve($cod_empresa);	
//fnMostraForm();
                                                                        
?> 
<style>
    .disabled {
        cursor:not-allowed;
        z-index: 99!important;
    }
</style>

                                <?php if ($popUp != "true"){  ?>							
                                <div class="push30"></div> 
                                <?php } ?>

                                <div class="row">				

                                        <div class="col-md12 margin-bottom-30">
                                                <!-- Portlet -->
                                                <?php if ($popUp != "true"){  ?>							
                                                <div class="portlet portlet-bordered">
                                                <?php } else { ?>
                                                <div class="portlet" style="padding: 0 20px 20px 20px;" >
                                                <?php } ?>

                                                        <?php if ($popUp != "true"){  ?>
                                                        <div class="portlet-title">
                                                                <div class="caption">
                                                                        <i class="glyphicon glyphicon-calendar"></i>
                                                                        <span class="text-primary"><?php echo $NomePg; ?> / <?php echo $nom_empresa; ?></span>
                                                                </div>
                                                                <?php include "atalhosPortlet.php"; ?>
                                                        </div>
                                                        <?php } ?>	

                                                        <div class="portlet-body">

                                                                <?php if ($msgRetorno <> '') { ?>	
                                                                <div class="alert <?php echo $msgTipo; ?> alert-dismissible top30 bottom30" role="alert" id="msgRetorno">
                                                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                                 <?php echo $msgRetorno; ?>
                                                                </div>
                                                                <?php } 

                                                                switch ($_SESSION["SYS_COD_SISTEMA"]) {
                                                                        case 16: //gabinete
                                                                                $usuario = "Colaborador";
                                                                                $cliente = "Apoiador";
                                                                                $plural = "es";
                                                                                $pref = 'S';
                                                                                break;
                                                                        default;
                                                                                $usuario = "Usuário";
                                                                                $cliente = "Cliente";
                                                                                $plural = "s";
                                                                                $pref = 'N';
                                                                        break;
                                                                }

                                                                ?>	

                                                                <div class="login-form">

                                                                        <form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>"> 

                                                                        <fieldset>
                                                                                <legend>Dados para Pesquisa</legend> 

                                                                                        <div class="row">

                                                                                                <?php if($pref != "S"){ ?>

                                                                                                        <div class="col-xs-3">
                                                                                                                <div class="form-group">
                                                                                                                        <label for="inputName" class="control-label">Código</label>
                                                                                                                        <input type="text" class="form-control input-sm"  name="COD_CLIENTE" id="COD_CLIENTE" value="">
                                                                                                                </div>
                                                                                                        </div>

                                                                                                        <div class="col-xs-3">
                                                                                                                <div class="form-group">
                                                                                                                        <label for="inputName" class="control-label">Nome do <?=$cliente?></label>
                                                                                                                        <input type="text" class="form-control input-sm" name="NOM_CLIENTE" id="NOM_CLIENTE" maxlength="40">
                                                                                                                        <div class="help-block with-errors"></div>
                                                                                                                </div>
                                                                                                        </div>

                                                                                                        <div class="col-xs-3">
                                                                                                                <div class="form-group">
                                                                                                                        <label for="inputName" class="control-label">CPF/CNPJ</label>
                                                                                                                        <input type="text" class="form-control input-sm cpfcnpj" name="NUM_CGCECPF" id="NUM_CGCECPF" maxlength="18">
                                                                                                                        <div class="help-block with-errors"></div>
                                                                                                                </div>
                                                                                                        </div>

                                                                                                        <div class="col-xs-3">
                                                                                                                <div class="form-group">
                                                                                                                        <label for="inputName" class="control-label">Número do Cartão</label>
                                                             <input type="text" class="form-control input-sm" name="NUM_CARTAO" id="NUM_CARTAO" value="" maxlength="18">
                                                                                                                        <div class="help-block with-errors"></div>
                                                                                                                </div>
                                                                                                        </div>

                                                                                                <?php }else{ ?>

                                                                                                        <div class="col-xs-3">
                                                                                                                <div class="form-group">
                                                                                                                        <label for="inputName" class="control-label">Código</label>
                                                                                                                        <input type="text" class="form-control input-sm"  name="COD_CLIENTE" id="COD_CLIENTE" value="">
                                                                                                                </div>
                                                                                                        </div>

                                                                                                        <div class="col-xs-4">
                                                                                                                <div class="form-group">
                                                                                                                        <label for="inputName" class="control-label">CPF/CNPJ</label>
                                                                                                                        <input type="text" class="form-control input-sm cpfcnpj" name="NUM_CGCECPF" id="NUM_CGCECPF" maxlength="18">
                                                                                                                        <div class="help-block with-errors"></div>
                                                                                                                </div>
                                                                                                        </div>

                                                                                                        <div class="col-xs-5">
                                                                                                                <div class="form-group">
                                                                                                                        <label for="inputName" class="control-label">Nome do <?=$cliente?></label>
                                                                                                                        <input type="text" class="form-control input-sm" name="NOM_CLIENTE" id="NOM_CLIENTE" maxlength="40">
                                                                                                                        <div class="help-block with-errors"></div>
                                                                                                                </div>
                                                                                                        </div>

                                                                                                <?php } ?>


                                                                                        </div>

                                                                                        <?php if($opcao == "IND" || $opcao == "LISTA"){

                                                                                                ?>
                                                                                                <div class="row">

                                                                                                        <div class="col-md-4">
                                                                                                                <div class="form-group">
                                                                                                                        <label for="inputName" class="control-label">e-Mail</label>
                                                            <input type="text" class="form-control input-sm" name="DES_EMAILUS" id="DES_EMAILUS"  maxlength="100" value="" data-error="Campo obrigatório">
                                                                                                                        <div class="help-block with-errors"></div>
                                                                                                                </div>
                                                                                                        </div>

                                                                                                        <div class="col-md-2">
                                                                                                                <div class="form-group">
                                                                                                                        <label for="inputName" class="control-label">Celular/Telefone</label>
                                                            <input type="text" class="form-control input-sm fone" name="NUM_TELEFON" id="NUM_TELEFON" maxlength="20">
                                                                                                                        <div class="help-block with-errors"></div>
                                                                                                                </div>
                                                                                                        </div>

                                                                                                </div>

                                                                                                <?php
                                                                                                  } 
                                                                                        ?>

                                                                        </fieldset>	

                                                                        <div class="push10"></div>
                                                                        <hr>	
                                                                        <div class="form-group text-right col-lg-12">

                                                               
                                                                                <button type="reset" class="btn btn-default"><i class="fal fa-eraser" aria-hidden="true"></i>&nbsp; Apagar</button>
                                                                                <?php

                                                                                        //if($cod_empresa == 136 || $cod_empresa == 224){
                                                                                            
                                                                                            
                                                                                ?>

                                                                                        <a href="action.php?mod=<?=fnEncode(1024)?>&id=<?=fnEncode($cod_empresa)?>&idc=<?=fnEncode(0)?>" target="_blank" class="btn btn-info" id ="CADBTN"><i class="fal fa-plus" aria-hidden="true"></i>&nbsp; Cadastrar Novo</a>

                                                                                <?php
                                                                                        //}

                                                                                ?>
                                                                                <button type="submit" name="BUS" id="BUS" class="btn btn-primary getBtn"><i class="fal fa-search" aria-hidden="true"></i>&nbsp; Pesquisar</button>

                                                                        </div>

                                                                        <?php
                                                                        if (!is_null($RedirectPg)) {
                                                                                $DestinoPg = fnEncode($RedirectPg);		
                                                                        }else {
                                                                                $DestinoPg = "";		
                                                                                }
                                                                        ?>											

                                                                        <input type="hidden" name="opcao" id="opcao" value="">
                                                                        <input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />	
                                                                        <input type="hidden" name="hHabilitado" id="hHabilitado" value="N">		

                                                                        <div class="push5"></div> 

                                                                        </form>

                                                                        <div class="push50"></div>

                                                                        <?php 

                                                                                if ($_SERVER['REQUEST_METHOD']=='POST'){
                                                                                //if ($cod_empresa != 0 ){

                                                                                        $pagina = (isset($_GET['pagina']))? $_GET['pagina'] : 1;

                                                                                        if ($cod_cliente!=0){
                                                                                                $andCodigo = 'and cod_cliente='.$cod_cliente; }
                                                                                                else { $andCodigo = ' ';}

                                                                                        if ($num_cartao!=0){
                                                                                                $andNumCartao = 'and num_cartao='.$num_cartao; }
                                                                                                else { $andNumCartao = ' ';}

                                                                                        if ($num_cgcecpf!=0){
                                                                                                $andcpf = 'and num_cgcecpf='.$num_cgcecpf; }
                                                                                                else { $andcpf = ' ';}

                                                                                        if ($nom_cliente!=''){ 
                                                                                                 $andNome = 'and nom_cliente like "'.$nom_cliente.'%"';	} 
                                                                                                else {$andNome = ' '; }

                                                                                        if ($des_emailus!=''){ 
                                                                                                 $andEmail = 'and des_emailus like "'.$des_emailus.'%"';														
                                                                                        }else{
                                                                                                 $andEmail ="";
                                                                                        } 

                                                                                        if ($num_telefon!=''){ 
                                                                                                 $andTelefone = 'and (num_celular like "'.$num_telefon.'%" or num_telefon like "'.$num_telefon.'%")';
                                                                                        }else{
                                                                                                 $andTelefone ="";
                                                                                        }
                                                                                        $sql = "select count(COD_CLIENTE) as CONTADOR from  $connUser->DB.clientes where cod_empresa = ".$cod_empresa." 
                                                                                                                                            ".$andCodigo."
                                                                                                                                            ".$andNome."
                                                                                                                                            ".$andNumCartao."
                                                                                                                                            ".$andcpf."
                                                                                                                                            ".$andEmail."
                                                                                                                                                                                ".$andTelefone."
                                                                                                                                            order by NOM_CLIENTE ";
                                                                                //fnEscreve($sql);

                                                                                $resPagina = mysqli_query($connUser ->connUser(),$sql) or die(mysqli_error());
                                                                                $total = mysqli_fetch_assoc($resPagina);
                                                                                //seta a quantidade de itens por página, neste caso, 2 itens
                                                                                $registros =100;
                                                                                //fnEscreve($total['CONTADOR']);
                                                                                //calcula o número de páginas arredondando o resultado para cima
                                                                                $numPaginas = ceil($total['CONTADOR']/$registros);                                                                                  
                                                                                //variavel para calcular o início da visualização com base na página atual
                                                                                $inicio = ($registros*$pagina)-$registros;

                                                                                } else {
                                                                                $numPaginas = 1;	
                                                                                }		

                                                                        if ($_SERVER['REQUEST_METHOD']=='POST'){	
                                                                        ?>

                                                                        <div class="col-lg-12">

                                                                                <div class="no-more-tables">

                                                                                        <form name="formLista" id="formLista" method="post" action="">

                                                                                        <table class="table table-bordered table-striped table-hover" id="tablista">
                                                                                          <thead>
                                                                                                <tr>
                                                                                                  <th class="{ sorter: false }" width="40"></th>
                                                                                                  <th>Código</th>
                                                                                                  <th>Cartão</th>
                                                                                                  <th>Nome do <?=$cliente?></th>
                                                                                                  <th>e-Mail</th>
                                                                                                  <th>Celular/Telefone</th>
                                                                                                  <th>CPF</th>
                                                                                                </tr>
                                                                                          </thead>
                                                                                        <tbody>

                                                                                        <?php
                                                                                        if ($_SERVER['REQUEST_METHOD']=='POST'){

                                                                                                // fnEscreve('teste');
                                                                                        //if ($cod_empresa != 0 ){

                                                                                                if ($cod_cliente!=0){
                                                                                                        $andCodigo = 'and cod_cliente='.$cod_cliente;
                                            }

                                                                                                if ($nom_cliente!=''){ 
                                                                                                         $andNome = 'and nom_cliente like "%'.$nom_cliente.'%"';														
                                                                                                }

                                                                                                if ($des_emailus!=''){ 
                                                                                                         $andEmail = 'and des_emailus like "'.$des_emailus.'%"';														
                                                                                                } 

                                                                                                if ($num_telefon!=''){ 
                                                                                                         $andTelefone = 'and (num_celular like "'.$num_telefon.'%"  or num_telefon like "'.$num_telefon.'%" )';
                                                                                                } 
                                                                                                $sql = "select COD_CLIENTE, NOM_CLIENTE, DES_EMAILUS, NUM_CGCECPF, NUM_TELEFON, NUM_CELULAR, COD_INDICAD from clientes where cod_empresa = ".$cod_empresa." 
                                                                                                        ".$andCodigo."
                                                                                                        ".$andNome."
                                                                                                        ".$andNumCartao."
                                                                                                        ".$andcpf."
                                                                                                        ".$andEmail."
                                                                                                        ".$andTelefone."
                                                                                                        order by NOM_CLIENTE limit $inicio,$registros";
                                                                                                // fnEscreve($sql);
                                                                                                $arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());

                                                                                                $count=0;

                                                                                                while ($qrListaEmpresas = mysqli_fetch_assoc($arrayQuery))
                                                                                                  {														  
                                                                                                        $count++;

                                                                                                        echo"
                                                                                                                <tr>
                                                                                                                  <td><a href='javascript: downForm(".$count.")' style='margin-left: 10px;'><i class='fa fa-arrow-circle-down' aria-hidden='true'></i></a></th>
                                                                                                                  <td>".$qrListaEmpresas['COD_CLIENTE']."</td>
                                                                                                                  <td></td>
                                                                                                                  <td>".$qrListaEmpresas['NOM_CLIENTE']."</td>
                                                                                                                  <td>".$qrListaEmpresas['DES_EMAILUS']."</td>
                                                                                                                  <td>".$qrListaEmpresas['NUM_CELULAR']."/".$qrListaEmpresas['NUM_TELEFON']."</td>
                                                                                                                  <td>".$qrListaEmpresas['NUM_CGCECPF']."</td>
                                                                                                                </tr>
                                                                                                                <input type='hidden' id='ret_ENCODE_".$count."' value='".fnEncode($qrListaEmpresas['COD_CLIENTE'])."'>
                                                                                                                <input type='hidden' id='ret_COD_CLIENTE_".$count."' value='".$qrListaEmpresas['COD_CLIENTE']."'>
                                                                                                                <input type='hidden' id='ret_COD_INDICADOR_".$count."' value='".$qrListaEmpresas['COD_INDICAD']."'>
                                                                                                                <input type='hidden' id='ret_NOM_CLIENTE_".$count."' value='".$qrListaEmpresas['NOM_CLIENTE']."'>
                                                                                                                <input type='hidden' id='ret_COD_EMPRESA_".$count."' value='".$cod_empresa."'>
                                                                                                                <input type='hidden' class='cpfcnpj' id='ret_CPF_INDICADO_".$count."' value='".$cpfIndicado."'>
                                                                                                                "; 
                                                                                                          }											
                                                                                        }	
                                                                                        ?>

                                                                                        </tbody>
                                                                                        <?php if ($cod_empresa != 0) {  ?>
                                                                                        <tfoot>
                                                                                                <tr>
                                                                                                  <th colspan="100"><ul class="pagination pagination-sm pull-right">
                                                                                                  <?php 
                                                                                                        for($i = 1; $i < $numPaginas + 1; $i++) {
                                                                                                        echo "<li class='pagination'><a href='{$_SERVER['PHP_SELF']}?mod=NN7xULiFM88¢&pagina=$i' style='text-decoration: none;'>".$i."</a></li>";   
                                                                                                        }													  
                                                                                                  ?></ul>
                                                                                                  </th>
                                                                                                </tr>
                                                                                        </tfoot>
                                                                                        <?php }   ?>

                                                                                        </table>


                                                                                        <div class="push"></div>

                                                                                        </form>

                                                                                </div>

                                                                        </div>

                                                                        <?php 
                                                                                $count = 0;
                                                                                if($pref == "S" && mysqli_num_rows($arrayQuery) == 0){
                                                                                        ?>
                                                                                                <div class="row">
                                                                                                        <div class="col-md-4 col-md-offset-4 text-center">
                                                                                                                <a href="javascript:void(0)" data-target="action.php?mod=<?=fnEncode(1423)?>&id=<?=fnEncode($cod_empresa)?>&idc=<?=fnEncode(0)?>" class="btn btn-info btnCadCli"><i class="fas fa-plus" aria-hidden="true"></i>&nbsp; Adicionar Cliente</a>
                                                                                                        </div>
                                                                                                </div>
                                                                                        <?php
                                                                                }

                                                                                }   

                                                                        ?>										

                                                                <div class="push"></div>

                                                                </div>								

                                                        </div>
                                                </div>
                                                <!-- fim Portlet -->
                                        </div>

                                </div>	

                                <div class="push20"></div>

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
                                                </div><!-- /.modal-content -->
                                        </div><!-- /.modal-dialog -->
                                </div><!-- /.modal -->


<script type="text/javascript">	

        $(document).keypress(function(event){
            var keycode = (event.keyCode ? event.keyCode : event.which);
            if(keycode == '13'){
                $('#BUS').click();  
            }
        });


        $(document).ready(function(){

                $(".btnCadCli").click(function(){
                        parent.$('#popModal').modal('hide');
                        parent.window.location.replace($(this).attr("data-target"));
                });		

                //chosen obrigatório
                $.fn.validator.Constructor.INPUT_SELECTOR = ':input:not([type="submit"], button):enabled, .requiredChk';
                $('#formulario').validator();

                //table sorter
                $(function() { 
                  var tabelaFiltro = $('table.tablesorter')
                  tabelaFiltro.find("tbody > tr").find("td:eq(1)").mousedown(function(){
                        $(this).prev().find(":checkbox").click()
                  });
                  $("#filter").keyup(function() {
                        $.uiTableFilter( tabelaFiltro, this.value );
                  })
                  $('#formLista').submit(function(){
                        tabelaFiltro.find("tbody > tr:visible > td:eq(1)").mousedown();
                        return false;
                  }).focus();
                }); 

                //pesquisa table sorter
                $('.filter-all').on('input', function(e) {
                        if('' == this.value) {
                        var lista = $("#filter").find("ul").find("li");  
                        filtrar(lista, "");
                        }
                });			

        });
        
        <?php
        if($total['CONTADOR'] > 0){
         ?>
        $("#CADBTN").addClass("disabled").prop("disabled",true);
        <?php
        } 
        ?>


        function retornaForm(index){

                $('#formulario').attr('action', 'action.php?mod=<?php echo $DestinoPg; ?>&id='+$("#ret_COD_EMPRESA_"+index).val()+'&idC='+$("#ret_COD_CLIENTE_"+index).val());					
                $("#formulario #hHabilitado").val('S');
                $( "#formulario" )[0].submit();   			

        }

        function downForm(index){

                if("<?=$opcao?>" == "IND"){
                        // alert('entrou');
                        cod_cliente = <?=$cod_indicado?>,
                        cod_indicador_novo = $("#ret_COD_CLIENTE_"+index).val();
                        cod_indicador = $("#ret_COD_INDICADOR_"+index).val();

                        if(cod_cliente != cod_indicador_novo && cod_cliente != cod_indicador && cod_cliente != 0){

                                // alert('entrou if');

                                $.ajax({
                                        type: "POST",
                                        url: "ajxClienteIndicador.php?id="+<?=$cod_empresa?>,
                                        data: {COD_INDICADOR:cod_indicador_novo,COD_CLIENTE:cod_cliente},
                                        success:function(data){
                                                //console.log(data);

                                                try { 
                                                        parent.$('#NOM_INDICA').val($("#ret_NOM_CLIENTE_"+index).val());
                                                        parent.$('#NOM_INDICA').attr("readonly", "readonly");
                                                        parent.$('#NOM_INDICA').addClass('leitura');
                                                } catch(err) {}		
                                                try { 
                                                        parent.$('#COD_INDICA').val($("#ret_COD_CLIENTE_"+index).val()); 
                                                        parent.$('#btnBuscaInd').hide();
                                                } catch(err) {}
                                                try { 
                                                        parent.$('#DAT_INDICA').val(data); 
                                                } catch(err) {}

                                        },
                                        error:function(){
                                                alert('Algo deu errado :(');
                                        }
                                });

                        }else if(cod_cliente == cod_indicador_novo && cod_cliente != cod_indicador && cod_cliente != 0){

                                // alert('entrou else if 1');
                                $.alert({
                title: "Mensagem",
                content: "Cliente indicado não pode ser igual a indicador.",
                type: 'red'
            });
                        }else if(cod_cliente != cod_indicador_novo && cod_cliente == cod_indicador && cod_cliente != 0){

                                cpfIndicado = $("#ret_CPF_INDICADO_"+index).val();
                                $.alert({
                title: "Mensagem",
                content: "Cliente já foi Indicado por CPF "+ cpfIndicado,
                type: 'red'
            });
                        }else{
                                // alert('entrou else');
                                try { 
                                        parent.$('#NOM_INDICA').val($("#ret_NOM_CLIENTE_"+index).val());
                                        parent.$('#COD_INDICA').val($("#ret_COD_CLIENTE_"+index).val());
                                } catch(err) {}	

                        }

                }else if("<?=$opcao?>" == "LISTA"){
                        // alert('entrou');
                        cod_cliente = <?=$cod_indicado?>,
                        cod_indicador_novo = $("#ret_COD_CLIENTE_"+index).val();

                        $.ajax({
                                type: "POST",
                                url: "ajxClienteIndicadorLista.php?id="+<?=$cod_empresa?>,
                                data: {COD_INDICADOR:cod_indicador_novo,COD_CLIENTE:cod_cliente},
                                success:function(data){
                                        // console.log(data);												
                                },
                                error:function(){
                                        alert('Algo deu errado :(');
                                }
                        });

                }else if("<?=$opcao?>" == "AGE"){ 
                        // try { parent.$("#COD_CLIENTES_ENV").append('<option value="'+$("#ret_COD_CLIENTE_"+index).val()+'">'+$("#ret_NOM_CLIENTE_"+index).val()+'</option>').trigger("chosen:updated"); } catch(err) {}
                        try { parent.$('#COD_CLIENTE_ENV').val($("#ret_COD_CLIENTE_"+index).val()); } catch(err) {}
                        try { parent.$('#NOM_CLIENTE_ENV').val($("#ret_NOM_CLIENTE_"+index).val()); } catch(err) {}
                        try { parent.$('#REFRESH_CLIENTE').val("S"); } catch(err) {}
                }else if("<?=$opcao?>" == "REM"){

                        try { parent.$('#COD_RESPONSAVEL').val($("#ret_COD_CLIENTE_"+index).val()); } catch(err) {}
                        try { parent.$('#NOM_RESPONSAVEL').val($("#ret_NOM_CLIENTE_"+index).val()); } catch(err) {}
                        // try { parent.$('#REFRESH_CLIENTE').val("S"); } catch(err) {}

                }else if("<?=$opcao?>" == "BEM"){

                        try { parent.$('#COD_BENEFICIARIO').val($("#ret_COD_CLIENTE_"+index).val()); } catch(err) {}
                        try { parent.$('#NOM_BENEFICIARIO').val($("#ret_NOM_CLIENTE_"+index).val()); } catch(err) {}
                        // try { parent.$('#REFRESH_CLIENTE').val("S"); } catch(err) {}

                }else{
                        try { parent.$('#NOM_USUARIO').val($("#ret_NOM_CLIENTE_"+index).val()); } catch(err) {}		
                        try { parent.$('#COD_USUARIO').val($("#ret_COD_CLIENTE_"+index).val()); } catch(err) {}		
                        try { parent.$('#NOM_CLIENTE').val($("#ret_NOM_CLIENTE_"+index).val()); } catch(err) {}			
                        try { parent.$('#COD_CLIENTE').val($("#ret_COD_CLIENTE_"+index).val()); } catch(err) {}	
                        try { parent.$('#NOVO_CLIENTE').val($("#ret_ENCODE_"+index).val()); } catch(err) {}	
                        try { parent.$('#REFRESH_CLIENTE').val("S"); } catch(err) {}
                }


                $(this).removeData('bs.modal');	
                parent.$('.modal').modal('hide');

                // alert('passou o hide');

        }	
</script>

