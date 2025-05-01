<?php

//echo fnDebug('true');

$hashLocal = mt_rand();
$cod_tpmodal = 0;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $request = md5(implode($_POST));

    if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
        $msgRetorno = 'Essa página já foi utilizada';
        $msgTipo = 'alert-warning';
    } else {
        $_SESSION['last_request']  = $request;

        $cod_empresa = fnLimpaCampoZero($_REQUEST['COD_EMPRESA']);
        $des_item = fnLimpaCampo($_REQUEST['DES_ITEM']);

        //fnEscreve($cod_licitac);

        $opcao = $_REQUEST['opcao'];
        $hHabilitado = $_REQUEST['hHabilitado'];
        $hashForm = $_REQUEST['hashForm'];

        $cod_usucada = $_SESSION["SYS_COD_USUARIO"];

        if ($opcao != '') {

            //mensagem de retorno
            switch ($opcao) {
                case 'CAD':

                    $sql = "INSERT INTO LICITACAO_OBJETO(
											COD_EMPRESA,
											COD_CONVENI,
											COD_LICITAC,
											NOM_OBJETO,
											DES_OBJETO,
											COD_USUCADA
											) VALUES(
											$cod_empresa,
											$cod_conveni,
											$cod_licitac,
											'$nom_objeto',
											'$des_objeto',
											$cod_usucada
											)";

                    //fnEscreve($sql);
                    mysqli_query(connTemp($cod_empresa, ''), $sql);

                    $sqlCod = "SELECT MAX(COD_OBJETO) COD_OBJETO FROM LICITACAO_OBJETO WHERE COD_EMPRESA = $cod_empresa AND COD_CONVENI = $cod_conveni";
                    $arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sqlCod);
                    $qrCod = mysqli_fetch_assoc($arrayQuery);
                    $cod_objeto = $qrCod[COD_OBJETO];

                    $sqlArquivos = "SELECT 1 FROM ANEXO_CONVENIO WHERE COD_EMPRESA = $cod_empresa AND COD_PROVISORIO = $num_contador";
                    $arrayCont = mysqli_query(connTemp($cod_empresa, ''), $sqlArquivos);

                    if (mysqli_num_rows($arrayCont) > 0) {
                        $sqlUpd = "UPDATE ANEXO_CONVENIO SET COD_OBJETO = $cod_objeto, LOG_STATUS = 'S' WHERE COD_EMPRESA = $cod_empresa AND COD_PROVISORIO = $num_contador";
                        mysqli_query(connTemp($cod_empresa, ''), $sqlUpd);
                    }

                    $msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
                    break;
                case 'ALT':

                    $sql = "UPDATE LICITACAO_OBJETO SET
											COD_LICITAC=$cod_licitac,
											NOM_OBJETO='$nom_objeto',
											DES_OBJETO='$des_objeto',
											COD_ALTERAC=$cod_usucada
								WHERE COD_OBJETO = $cod_objeto";

                    //fnEscreve($sql);
                    mysqli_query(connTemp($cod_empresa, ''), $sql);

                    $sqlUpd = "UPDATE ANEXO_CONVENIO SET LOG_STATUS = 'S' WHERE COD_EMPRESA = $cod_empresa AND COD_OBJETO = $cod_objeto AND LOG_STATUS = 'N'";
                    mysqli_query(connTemp($cod_empresa, ''), $sqlUpd);

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
if (is_numeric(fnLimpacampo(fnDecode($_GET['id'])))) {

    //busca dados da empresa
    $cod_empresa = fnDecode($_GET['id']);
    $sql = "SELECT NOM_EMPRESA FROM EMPRESAS WHERE COD_EMPRESA = " . $cod_empresa;

    //fnEscreve($sql);
    $arrayQuery = mysqli_query($connAdm->connAdm(), $sql) or die(mysqli_error());
    $qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

    if (isset($qrBuscaEmpresa)) {
        $nom_empresa = $qrBuscaEmpresa['NOM_EMPRESA'];
    }
} else {
    $nom_empresa = "";
}

if (isset($_GET['idC'])) {
    if (is_numeric(fnLimpacampo(fnDecode($_GET['idC'])))) {

        //busca dados do convênio
        $cod_conveni = fnDecode($_GET['idC']);
        $sql = "SELECT NOM_CONVENI FROM CONVENIO WHERE COD_CONVENI = " . $cod_conveni;

        //fnEscreve($sql);
        $arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql) or die(mysqli_error());
        $qrBuscaTemplate = mysqli_fetch_assoc($arrayQuery);

        if (isset($qrBuscaTemplate)) {
            $nom_conveni = $qrBuscaTemplate['NOM_CONVENI'];
        }
    }
}

//busca dados do usuário
$cod_usucada = $_SESSION["SYS_COD_USUARIO"];
$sql = "SELECT NOM_USUARIO FROM USUARIOS WHERE COD_USUARIO = " . $cod_usucada;

//fnEscreve($sql);
$arrayQuery = mysqli_query($connAdm->connAdm(), $sql) or die(mysqli_error());
$qrBuscaUsuario = mysqli_fetch_assoc($arrayQuery);

if (isset($qrBuscaUsuario)) {
    $nom_usuario = $qrBuscaUsuario['NOM_USUARIO'];
}

//fnEscreve(fnDecode($_GET['idC']));

if (is_numeric(fnLimpacampo(fnDecode($_GET['idC'])))) {
    $cod_conveni = fnDecode($_GET['idC']);
}

//fnMostraForm();
//fnEscreve($cod_checkli);

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

                    <?php
                    //menu superior - licitação
                    $abaProposta = 1364;
                    include "abasLicitacao.php";
                    ?>

                    <div class="push30"></div>


                    <form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

                        <fieldset>
                            <legend>Dados Gerais</legend>

                            <div class="row">

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label required">Código</label>
                                        <input type="text" class="form-control input-sm leitura" readonly="readonly" name="COD_OBJETO" id="COD_OBJETO" value="">
                                    </div>
                                    <div class="help-block with-errors"></div>
                                </div>

                                <div class="col-md-4">
									<div class="form-group">
										<label for="inputName" class="control-label">Descrição do Bloco</label>
										<textarea type="text" class="form-control input-sm" rows="3" name="DES_OBJETO" id="DES_OBJETO" value="" maxlength="250"></textarea>
									</div>
									<div class="help-block with-errors"></div>
								</div>
                            </div>  
                        </fieldset>
                    </form>