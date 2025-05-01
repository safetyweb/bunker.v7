<?php

//echo fnDebug('true');

$hashLocal = mt_rand();

$cod_geral = 0;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $request = md5(implode($_POST));

    if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
        $msgRetorno = 'Essa página já foi utilizada';
        $msgTipo = 'alert-warning';
    } else {
        $_SESSION['last_request']  = $request;

        $cod_semana = fnLimpaCampoZero($_POST['COD_SEMANA']);
        $cod_campanha = fnLimpaCampoZero($_POST['COD_CAMPANHA']);
        $cod_empresa = fnLimpaCampoZero($_POST['COD_EMPRESA']);
        $cod_limite = fnLimpaCampoZero($_POST['COD_LIMITE']);
        $qtd_extraind = fnvalorSql($_POST['QTD_EXTRAIND'], 2);
        $tip_extraind = fnLimpaCampo($_POST['TIP_EXTRAIND']);
        $cod_dia = fnLimpaCampoZero($_POST['COD_DIA']);

        $cod_usucada = $_SESSION["SYS_COD_USUARIO"];

        $opcao = $_REQUEST['opcao'];
        $hHabilitado = $_REQUEST['hHabilitado'];
        $hashForm = $_REQUEST['hashForm'];

        if ($opcao != '') {
            switch ($opcao) {

                case 'CAD':

                    //busca dados da regra extra (tela) 
                    $sql = "SELECT COD_EXTRA FROM VANTAGEMEXTRA where COD_CAMPANHA = '" . $cod_campanha . "' ";
                    //fnEscreve($sql);

                    $arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);
                    $tem_extra = mysqli_num_rows($arrayQuery);

                    if ($tem_extra == 0) {

                        $sqlExtra = "INSERT INTO VANTAGEMEXTRA(
												COD_CAMPANHA, 
												COD_USUCADA, 
												COD_EMPRESA
											 ) VALUES(
											 	$cod_campanha,
											 	$cod_usucada,
											 	$cod_empresa
											 )";

                        mysqli_query(connTemp($cod_empresa, ''), $sqlExtra);
                    }

                    $sql = "INSERT INTO DIAS_SEMANA_CAMPANHA(
                                                    COD_CAMPANHA,
                                                    COD_EMPRESA,
                                                    COD_LIMITE,
                                                    QTD_EXTRAIND,
                                                    TIP_EXTRAIND,
                                                    COD_DIA,
                                                    COD_USUCADA,
                                                    DAT_CADASTR
                                                    )VALUES(
                                                    $cod_campanha,
                                                    $cod_empresa,
                                                    $cod_limite,
                                                    '$qtd_extraind',
                                                    '$tip_extraind',
                                                    $cod_dia,
                                                    $cod_usucada,
                                                    NOW()
                                                    )";

                    //echo $sql;	
                    //fnTestesql(connTemp($cod_empresa,''),trim($sql)) or die(mysqli_error());	
                    $arrayInsert= mysqli_query(connTemp($cod_empresa, ''), trim($sql));

                    $msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
                    if(!$arrayInsert){
                        $msgRetorno = "Registro já existente, tente <strong>alterar ou excluir!</strong>";
                    }
?>
                    <script>
                        try {
                            parent.$('#REFRESH_DIAS').val("S");
                        } catch (err) {}
                    </script>
                <?php
                    //fnEscreve($sql2); 

                    break;
                case 'ALT':
                    $sql = "UPDATE DIAS_SEMANA_CAMPANHA SET
                                                            COD_LIMITE = $cod_limite,
                                                            QTD_EXTRAIND = $qtd_extraind,
                                                            TIP_EXTRAIND = '$tip_extraind',
                                                            COD_DIA = $cod_dia,
                                                            COD_ALTERAC = $cod_usucada,
                                                            DAT_ALTERAC = NOW()
                            WHERE COD_EMPRESA = $cod_empresa
                            AND COD_CAMPANHA = $cod_campanha
                            AND COD_SEMANA = $cod_semana
                    ";
                    $arrayUpdate = mysqli_query(connTemp($cod_empresa, ''), trim($sql));
                    $msgRetorno = "Registro alterado com <strong>sucesso!</strong>";
                    if(!$arrayUpdate){
                        $msgRetorno = "Registro já existente, tente <strong>Cadastrar ou Excluir!</strong>";
                    }

                ?>
                    <script>
                        try {
                            parent.$('#REFRESH_DIAS').val("S");
                        } catch (err) {}
                    </script>
                <?php
                    break;
                case 'EXC':
                    $sql = "UPDATE DIAS_SEMANA_CAMPANHA SET
                                                    COD_EXCLUSA = $cod_usucada,
                                                    DAT_EXCLUSA = NOW()
                        WHERE COD_SEMANA = $cod_semana
                        AND COD_EMPRESA = $cod_empresa
                        AND COD_CAMPANHA = $cod_campanha";

                    mysqli_query(connTemp($cod_empresa, ''), trim($sql));
                    break;

                ?>
                    <script>
                        try {
                            parent.$('#REFRESH_DIAS').val("S");
                        } catch (err) {}
                    </script>
<?php
            }
            //mensagem de retorno
            switch ($opcao) {
                case 'CAD':
                    if(!$arrayInsert){
                        $msgTipo = 'alert-danger';
                    }else{
                        $msgTipo = 'alert-success';
                    }
                    $msgRetorno = $msgRetorno;
                    break;
                case 'ALT':
                    if(!$arrayUpdate){
                        $msgTipo = 'alert-danger';
                    }else{
                        $msgTipo = 'alert-success';
                    }
                    $msgRetorno =  $msgRetorno;
                    break;
                case 'EXC':
                    $msgRetorno = "Registro excluido com <strong>sucesso!</strong>";
                    break;
                    break;
            }
        }
    }
}

//busca dados da campanha
$cod_campanha = fnDecode($_GET['idc']);
$cod_empresa = fnDecode($_GET['id']);
$sql = "SELECT * FROM CAMPANHA where COD_CAMPANHA = '" . $cod_campanha . "' ";
//fnEscreve($sql);
$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);
$qrBuscaCampanha = mysqli_fetch_assoc($arrayQuery);

if (isset($arrayQuery)) {
    $log_ativo = $qrBuscaCampanha['LOG_ATIVO'];
    $des_campanha = $qrBuscaCampanha['DES_CAMPANHA'];
    $abr_campanha = $qrBuscaCampanha['ABR_CAMPANHA'];
    $des_icone = $qrBuscaCampanha['DES_ICONE'];
    $tip_campanha = $qrBuscaCampanha['TIP_CAMPANHA'];
    $qtd_diasind = $qrBuscaCampanha['QTD_DIASIND'];
    $log_realtime = $qrBuscaCampanha['LOG_REALTIME'];
}

//busca dados do tipo da campanha
$sql = "SELECT * FROM TIPOCAMPANHA where COD_TPCAMPA = '" . $tip_campanha . "' ";
//fnEscreve($sql);
$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
$qrBuscaTpCampanha = mysqli_fetch_assoc($arrayQuery);

if (isset($arrayQuery)) {
    $nom_tpcampa = $qrBuscaTpCampanha['NOM_TPCAMPA'];
    $abv_tpcampa = $qrBuscaTpCampanha['ABV_TPCAMPA'];
    $des_iconecp = $qrBuscaTpCampanha['DES_ICONE'];
    $label_1 = $qrBuscaTpCampanha['LABEL_1'];
    $label_2 = $qrBuscaTpCampanha['LABEL_2'];
    $label_3 = $qrBuscaTpCampanha['LABEL_3'];
    $label_4 = $qrBuscaTpCampanha['LABEL_4'];
    $label_5 = $qrBuscaTpCampanha['LABEL_5'];
}

//busca dados da regra 
$sql = "SELECT NOM_VANTAGE FROM CAMPANHAREGRA where COD_CAMPANHA = '" . $cod_campanha . "' ";
//fnEscreve($sql);
$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);
$qrBuscaTpCampanha = mysqli_fetch_assoc($arrayQuery);

if (isset($arrayQuery)) {
    $nom_vantagem = $qrBuscaTpCampanha['NOM_VANTAGE'];
}

//BUSCA DADOS DA INDICAÇÃO
$sql = "SELECT * FROM INDICA_CLIENTE_CAMPANHA WHERE COD_CAMPANHA = '" . $cod_campanha . "' ";
//fnEscreve($sql);
$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), trim($sql));
$qrBuscaTpCampanha = mysqli_fetch_assoc($arrayQuery);

if (isset($arrayQuery)) {
    $cod_controle = $qrBuscaTpCampanha['COD_CONTROLE'];
    $qtd_extraind = $qrBuscaTpCampanha['QTD_EXTRAIND'];
    $tip_extraind = $qrBuscaTpCampanha['TIP_EXTRAIND'];
    $qtd_diasind = $qrBuscaTpCampanha['QTD_DIASIND'];
    $cod_usoind = $qrBuscaTpCampanha['COD_USOIND'];
} else {
    $cod_controle = 0;
    $cod_usoind = 0;
    $qtd_extraind = "";
    $tip_extraind = "";
    $qtd_diasind = "";
}

/*$sqlSemana = "SELECT * FROM DIAS_SEMANA_CAMPANHA WHERE COD_CAMPANHA = '" . $cod_campanha . "'";

$arraySemana = mysqli_query(connTemp($cod_empresa, ''), trim($sqlSemana));
$qrBuscaSemana = mysqli_fetch_assoc($arraySemana);

if (isset($arraySemana)) {
    $cod_controle = $qrBuscaTpCampanha['COD_CONTROLE'];
    $qtd_extraind = $qrBuscaTpCampanha['QTD_EXTRAIND'];
    $tip_extraind = $qrBuscaTpCampanha['TIP_EXTRAIND'];
    $qtd_diasind = $qrBuscaTpCampanha['QTD_DIASIND'];
    $cod_usoind = $qrBuscaTpCampanha['COD_USOIND'];
} else {
    $cod_controle = 0;
    $cod_usoind = 0;
    $qtd_extraind = "";
    $tip_extraind = "";
    $qtd_diasind = "";
}*/


//fnMostraForm();



$opcoes = " <option value='1'>Domingo</option>
            <option value='2'>Segunda</option>
            <option value='3'>Terça-feira</option>
            <option value='4'>Quarta-feira</option>
            <option value='5'>Quinta-feira</option>
            <option value='6'>Sexta-feira</option>
            <option value='7'>Sábado</option>";

?>


<?php if ($popUp != "true") {  ?>
    <div class="push30"></div>
<?php } ?>

<div class="row">

    <div class="col-md12 margin-bottom-30">
        <!-- Portlet -->
        <?php if ($popUp != "true") {  ?>
            <div class="portlet portlet-bordered">
            <?php } else { ?>
                <div class="portlet" style="padding: 0 20px 20px 20px;">
                <?php } ?>

                <?php if ($popUp != "true") {  ?>
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
                    <?php } ?>

                    <div class="login-form">

                        <form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

                            <fieldset>
                                <legend>Dados da Configuração da Indicação</legend>

                                <div class="row">

                                    <div class="col-md-3">
                                        <h5 class="text-center" style="padding-top: 13px;">CLIENTE INDICADOR GANHA</h5>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="inputName" class="control-label required">Qtd. Extra</label>
                                            <input type="text" class="form-control input-sm text-center money" name="QTD_EXTRAIND" id="QTD_EXTRAIND" maxlength="10" value="<?= fnValor($qtd_extraind, 2) ?>" required>
                                            <span class="help-block">valor</span>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="inputName" class="control-label">Tipo da Vantagem Extra</label>
                                            <select data-placeholder="Selecione a vantagem extra" name="TIP_EXTRAIND" id="TIP_EXTRAIND" class="chosen-select-deselect" required>
                                                <option value="">...</option>
                                                <option value="PCT">Percentual sobre a venda</option>
                                                <!--<option value="PCV">Percentual sobre <?= strtolower($nom_vantagem); ?></option>-->
                                                <option value="ABS"><?= $nom_tpcampa; ?></option>
                                            </select>

                                            <div class="help-block with-errors"></div>
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="inputName" class="control-label">Dia da Semana</label>
                                            <select data-placeholder="Selecione o dia" name="COD_DIA" id="COD_DIA" class="chosen-select-deselect" required>
                                                <?=$opcoes?>
                                            </select>
                                            <script>
                                                $("#COD_DIA").val("<?= $cod_dia; ?>").trigger("chosen:updated");
                                            </script>
                                            <div class="help-block with-errors"></div>
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="inputName" class="control-label required">Limite de Uso</label>
                                            <input type="text" class="form-control input-sm text-center" name="COD_LIMITE" id="COD_LIMITE" maxlength="20" value="<?= $qtd_diasind ?>" required>
                                            <span class="help-block">quantidade máxima</span>
                                        </div>
                                    </div>

                                </div>

                            </fieldset>

                            <div class="push10"></div>
                            <hr>
                            <div class="form-group text-right col-lg-12">

                                <button type="reset" class="btn btn-default"><i class="fal fa-eraser" aria-hidden="true"></i>&nbsp; Apagar</button>
                                <button type="submit" name="EXC" id="EXC" class="btn btn-primary getBtn"><i class="fal fa-times" aria-hidden="true"></i>&nbsp; Excluir</button>
                                <button type="submit" name="ALT" id="ALT" class="btn btn-primary getBtn"><i class="fal fa-sync" aria-hidden="true"></i>&nbsp; Alterar</button>
                                <button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn"><i class="fal fa-plus" aria-hidden="true"></i>&nbsp; Cadastrar</button>


                            </div>

                            <input type="hidden" name="COD_SEMANA" id="COD_SEMANA" value="<?= $cod_semana; ?>">
                            <input type="hidden" name="COD_CAMPANHA" id="COD_CAMPANHA" value="<?= $cod_campanha; ?>">
                            <input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?= $cod_empresa; ?>">

                            <input type="hidden" name="opcao" id="opcao" value="">
                            <input type="hidden" name="hashForm" id="hashForm" value="<?= $hashLocal; ?>" />
                            <input type="hidden" name="hHabilitado" id="hHabilitado" value="N">

                            <div class="push5"></div>

                        </form>

                        <div class="push50"></div>

                        <div class="col-lg-12">

                            <div class="no-more-tables">

                                <form name="formLista">

                                    <table class="table table-bordered table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th width="40"></th>
                                                <th>Código</th>
                                                <th class="text-center">Qtd. Extra</th>
                                                <th class="text-center">Tipo Vantagem</th>
                                                <th class="text-center">Dia da semana</th>
                                                <th class="text-right">Limite</th>
                                            </tr>
                                        </thead>

                                        <tbody>

                                            <?php

                                            $sql = "SELECT * FROM DIAS_SEMANA_CAMPANHA 
                                            WHERE COD_EMPRESA = $cod_empresa 
                                            AND COD_CAMPANHA = $cod_campanha
                                            AND COD_EXCLUSA = 0";
                                            //fnEscreve($sql);		

                                            $arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

                                            while ($qrBuscaModulos = mysqli_fetch_assoc($arrayQuery)) {
                                                $count++;

                                                switch ($qrBuscaModulos['COD_DIA']) {
                                                    case 1:
                                                        $dia = "Domingo";
                                                        break;
                                                    case 2:
                                                        $dia = "Segunda-Feira";
                                                        break;
                                                    case 3:
                                                        $dia = "Terça-Feira";
                                                        break;
                                                    case 4:
                                                        $dia = "Quarta-feira";
                                                        break;
                                                    case 5:
                                                        $dia = "Quinta-feira";
                                                        break;
                                                    case 6:
                                                        $dia = "Sexta-Feira";
                                                        break;
                                                    case 7:
                                                        $dia = "Sábado";
                                                        break;
                                                }


                                                echo "
											<tr>
											  <td><input type='radio' name='radio1' onclick='retornaForm(" . $count . ")'></th>
											  <td class='text-left'>" . $qrBuscaModulos['COD_SEMANA'] . "</td>
											  <td class='text-center'>" . fnvalor($qrBuscaModulos['QTD_EXTRAIND'], 2) . "</td>
											  <td class='text-center'>" . $qrBuscaModulos['TIP_EXTRAIND'] . "</td>
											  <td class='text-center'>" . $dia . "</td>
											  <td class='text-right'>" . $qrBuscaModulos['COD_LIMITE'] . "</td>
											</tr>
											
											<input type='hidden' id='ret_COD_SEMANA_" . $count . "' value='" . $qrBuscaModulos['COD_SEMANA'] . "'>
											<input type='hidden' id='ret_QTD_EXTRAIND_" . $count . "' value='" . fnvalor($qrBuscaModulos['QTD_EXTRAIND'], 2) . "'>
											<input type='hidden' id='ret_TIP_EXTRAIND_" . $count . "' value='" . $qrBuscaModulos['TIP_EXTRAIND'] . "'>
											<input type='hidden' id='ret_COD_DIA_" . $count . "' value='" . $qrBuscaModulos['COD_DIA'] . "'>
											<input type='hidden' id='ret_COD_LIMITE_" . $count . "' value='" . $qrBuscaModulos['COD_LIMITE'] . "'>
											";
                                            }
                                            ?>

                                        </tbody>

                                        <tfoot>
                                        </tfoot>

                                    </table>

                                </form>

                                <div class="push50"></div>


                            </div>

                            <div class="push"></div>

                        </div>

                    </div>
                </div>
                <!-- fim Portlet -->
                </div>

            </div>

<div class="push20"></div>
<!-- modal -->
<div class="modal fade" id="popModalAux" tabindex='-1'>
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
<script>
$(document).ready(function() {

    //chosen obrigatório
    $.fn.validator.Constructor.INPUT_SELECTOR = ':input:not([type="submit"], button):enabled, .requiredChk';
    $('#formulario').validator();

});


function retornaForm(index) {

    $("#formulario #TIP_EXTRAIND").val($("#ret_TIP_EXTRAIND_" + index).val()).trigger("chosen:updated");
    $("#formulario #COD_DIA").val($("#ret_COD_DIA_" + index).val()).trigger("chosen:updated");
    $("#formulario #COD_SEMANA").val($("#ret_COD_SEMANA_" + index).val());
    $("#formulario #QTD_EXTRAIND").val($("#ret_QTD_EXTRAIND_" + index).val());
    $("#formulario #COD_LIMITE").val($("#ret_COD_LIMITE_" + index).val());
    $('#formulario').validator('validate');
    $("#formulario #hHabilitado").val('S');

    $("#COD_DIA").change(function(){
       
    })
}
</script>