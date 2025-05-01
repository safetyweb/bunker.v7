<?php
	
//echo "<h5>_".$opcao."</h5>";

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
                $_SESSION['last_request']  = $request;

                $cod_grupoent = fnLimpaCampo($_REQUEST['COD_GRUPOENT']);
                $nom_respon = fnLimpaCampo($_REQUEST['NOM_RESPON']);
                $cod_empresa = fnLimpaCampo($_REQUEST['COD_EMPRESA']);
                $cod_usucada = $_SESSION[SYS_COD_USUARIO];

                $opcao = $_REQUEST['opcao'];
                $hHabilitado = $_REQUEST['hHabilitado'];
                $hashForm = $_REQUEST['hashForm'];

                if ($opcao != ''){

                        $sql = "UPDATE  entidade SET
                                NOM_RESPON='$nom_respon',
                                COD_ALTERAC='$cod_usucada',
                                DAT_ALTERAC= NOW()
                                WHERE COD_GRUPOENT=$cod_grupoent AND 
                                COD_EMPRESA=$cod_empresa";

                        //echo $sql;

                         mysqli_query(connTemp($cod_empresa,''),$sql);
?>
<script>
    //parent.$('#REFRESH_LISTA').val('S');
    parent.reloadPage('1');
    parent.$('#popModal').modal('toggle');
</script>
<?php
                        //mensagem de retorno
                        switch ($opcao)
                        {
                                case 'CAD':
                                        $msgRetorno = "Registro gravado com <strong>sucesso!</strong>";	
                                        break;
                                case 'ALT':
                                        $msgRetorno = "Registro alterado com <strong>sucesso!</strong>";		
                                        break;
                                case 'EXC':
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
        $cod_grupoent = fnDecode($_GET['idg']);
        $sql = "SELECT COD_EMPRESA, NOM_FANTASI FROM empresas where COD_EMPRESA = '".$cod_empresa."' ";
        //fnEscreve($sql);
        $arrayQuery = mysqli_query($connAdm->connAdm(),$sql);
        $qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);
       
        if (isset($arrayQuery)){
                $cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
                $nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];
        }

}else {
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
                                        <i class="fal fa-terminal"></i>
                                        <span class="text-primary"><?php echo $NomePg; ?> / <?php echo $nom_empresa; ?></span>
                                </div>

                                <?php 
                                $formBack = "1019";
                                include "atalhosPortlet.php"; 
                                ?>	

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

                                        <form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

                                        <fieldset>
                                                <legend>Dados Gerais</legend> 
                                                
                                                        <div class="row">
                                                                <div class="col-md-2">
                                                                        <div class="form-group">
                                                                                <label for="inputName" class="control-label required">Código</label>
                                                                                <input type="text" class="form-control input-sm leitura" readonly="readonly" name="COD_GRUPOENT" id="COD_GRUPOENT" value="<?=$cod_grupoent?>">
                                                                        </div>
                                                                </div>
                                                                <div class="col-md-4">
                                                                        <div class="form-group">
                                                                                <label for="inputName" class="control-label">Nome do Responsável</label>
                                                                                <input type="text" class="form-control input-sm" name="NOM_RESPON" id="NOM_RESPON" maxlength="50" value="" required>
                                                                                <div class="help-block with-errors"></div>
                                                                        </div>
                                                                </div>

                                                        </div>

                                        </fieldset>	

                                        <div class="push10"></div>
                                        <hr>	
                                        <div class="form-group text-right col-lg-12">

                                                  <button type="reset" class="btn btn-default"><i class="fal fa-eraser" aria-hidden="true"></i>&nbsp; Apagar</button>
                                                  <!--<button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn"><i class="fal fa-plus" aria-hidden="true"></i>&nbsp; Cadastrar</button>-->
                                                  <button type="submit" name="ALT" id="ALT" class="btn btn-primary getBtn"><i class="fal fa-sync" aria-hidden="true" onclick="recarregaTela()"></i>&nbsp; Alterar</button>
                                                  <!--<button type="submit" name="EXC" id="EXC" class="btn btn-primary getBtn"><i class="fal fa-times" aria-hidden="true"></i>&nbsp; Excluir</button>-->

                                        </div>

                                        <input type="hidden" name="opcao" id="opcao" value="">
                                        <input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />	
                                        <input type="hidden" name="hHabilitado" id="hHabilitado" value="S">
                                        <input type="hidden" class="form-control input-sm" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa ?>">
                                        <input type="hidden" class="form-control input-sm" name="COD_GRUPOENT" id="COD_GRUPOENT" value="<?php echo $cod_grupoent ?>">

                                        <div class="push5"></div> 

                                        </form>

                                        <!--<div class="push50"></div>

                                        <div class="col-lg-12">

                                                <div class="no-more-tables">

                                                        <form name="formLista">

                                                        <table class="table table-bordered table-striped table-hover tableSorter">
                                                          <thead>
                                                                <tr>
                                                                  <th class="{ sorter: false }" width="40"></th>                                                                 
                                                                  <th>Nome do Responsável</th>
                                                                </tr>
                                                          </thead>
                                                        <tbody>

                                                        <?php /*

                                                                $sql = "select * from grupotrabalho where cod_empresa = $cod_empresa order by DES_GRUPOTR";
                                                                $arrayQuery = mysqli_query($connAdm->connAdm(),$sql);

                                                                $count=0;
                                                                while ($qrBuscaModulos = mysqli_fetch_assoc($arrayQuery))
                                                                  {														  
                                                                        $count++;	
                                                                        echo"
                                                                                <tr>
                                                                                  <td class='text-center'><input type='radio' name='radio1' onclick='retornaForm(".$count.")'></th>
                                                                                  <td>".$qrBuscaModulos['COD_GRUPOTR']."</td>
                                                                                  <td>".$qrBuscaModulos['DES_GRUPOTR']."</td>
                                                                                </tr>
                                                                                <input type='hidden' id='ret_COD_GRUPOTR_".$count."' value='".$qrBuscaModulos['COD_GRUPOTR']."'>
                                                                                <input type='hidden' id='ret_DES_GRUPOTR_".$count."' value='".$qrBuscaModulos['DES_GRUPOTR']."'>
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
*/?>
-->                                            
<script type="text/javascript">

function recarregaTela(){
    location.reload(true);
}

</script>	