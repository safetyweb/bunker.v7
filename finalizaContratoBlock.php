<?php

//echo fnDebug('true');

include '_system/Gera_block.php';

$hashLocal = mt_rand();

$conn = conntemp($cod_empresa, "");
$adm = $connAdm->connAdm();

$regEncontrado = 'N';
$campoReadonly = '';
$cod_tipobem ='';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $request = md5(implode($_POST));

  if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
    $msgRetorno = 'Essa página já foi utilizada';
    $msgTipo = 'alert-warning';
} else {
    $_SESSION['last_request'] = $request;

    $cod_cliente = fnLimpaCampoZero($_REQUEST['COD_CLIENTE']);
    $num_contrato = fnLimpaCampoZero($_REQUEST['NUM_CONTRATO']);
    $nom_contrato = fnLimpaCampo($_REQUEST['NOM_CONTRATO']);
    $divisao_cotas = fnLimpaCampoZero($_REQUEST['DIVISAO_COTAS']);
    $val_contrato = fnLimpaCampoZero(fnValorSql($_REQUEST['VAL_CONTRATO']));
    $num_cotas = fnLimpaCampoZero(fnValorSql($_REQUEST['NUM_COTAS']));

    $cod_empresa = fnLimpaCampoZero($_POST['COD_EMPRESA']);
    $cod_usucada = $_SESSION["SYS_COD_USUARIO"];

    $opcao = $_REQUEST['opcao'];
    $hHabilitado = $_REQUEST['hHabilitado'];
    $hashForm = $_REQUEST['hashForm'];

    if ($opcao == 'CAD') {
      $sql = "INSERT INTO Tokemgeral (
                COD_EMPRESA,
                COD_CLIENTE,
                NUM_CONTRATO,
                COD_USUCADA,
                NOM_CONTRATO,
                DIVISAO_COTAS,
                VAL_CONTRATO,
                NUM_COTAS
                ) VALUES (
                '$cod_empresa',
                '$cod_cliente',
                '$num_contrato',
                '$cod_usucada',
                '$nom_contrato',
                '$divisao_cotas',
                '$val_contrato',
                '$num_cotas'
        )";

        $arrayInsert = mysqli_query($conn, $sql);

        if($arrayInsert) {
            $sql = "SELECT * FROM TOKEMGERAL WHERE COD_EMPRESA = '$cod_empresa' AND NUM_CONTRATO = '$num_contrato'";
            $query = mysqli_query($conn, $sql);

            $i = 0;
            if(mysqli_num_rows($query) > 0){
                $qrBusca = mysqli_fetch_assoc($query);
                $cod_token = $qrBusca['COD_TOKEN'];
                
                while($i < $divisao_cotas){

                    $sql = "INSERT INTO TOKEN_GERADO (
                            COD_EMPRESA,
                            COD_TOKEN,
                            NUM_CONTRATO,
                            NUM_TOKENGERADO,
                            COD_USUCADA,
                            VAL_TOKEN,
                            DAT_CADASTR
                            )VALUES(
                            '$cod_empresa',
                            '$cod_token',
                            '$num_contrato',
                            '0',
                            '$cod_usucada',
                            '$num_cotas',
                            NOW()
                            )";

                    mysqli_query($conn, $sql);

                    $i++;
                }
            }
        }


        if (!$arrayInsert) {

            $cod_erro = Log_error_comand($connAdm->connAdm(), conntemp($cod_empresa, ''), $cod_empresa, $actual_link, $MODULO, $COD_MODULO, $sql, $nom_usuarioSESSION);
        }

        if ($cod_erro == 0 || $cod_erro ==  "") {
            $msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
        } else {
            $msgRetorno = "Não foi possível cadastrar o registro : $cod_erro";
        }
        if ($cod_erro == 0 || $cod_erro == "") {
            $msgTipo = 'alert-success';
        } else {
            $msgTipo = 'alert-danger';
        }
    }
}
}

//busca dados da url
if (is_numeric(fnLimpacampo(fnDecode($_GET['id'])))) {
    //busca dados da empresa
    $cod_empresa = fnDecode($_GET['id']);

    $sql = "SELECT EMPRESAS.NOM_FANTASI,CATEGORIA.* FROM $connAdm->DB.EMPRESAS
    left JOIN CATEGORIA ON CATEGORIA.COD_EMPRESA=EMPRESAS.COD_EMPRESA
    where EMPRESAS.COD_EMPRESA = $cod_empresa ";

    $arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql) or die(mysqli_error(connTemp($cod_empresa, '')));
    $qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

    if (isset($qrBuscaEmpresa)) {
      $nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];
  }
} else {
    $cod_empresa = 0;
}

//busca cliente
if (is_numeric(fnLimpacampo(fnDecode($_GET['idC'])))) {

    $cod_cliente = fnDecode($_GET['idC']);
    $sql = "SELECT COD_CLIENTE, NOM_CLIENTE FROM CLIENTES where COD_CLIENTE = '" . $cod_cliente . "' AND COD_EMPRESA = '" . $cod_empresa . "' ";

    $query = mysqli_query(connTemp($cod_empresa, ''), $sql);
    $qrBuscaCliente = mysqli_fetch_assoc($query);

    if (isset($query)) {
      $cod_cliente = $qrBuscaCliente['COD_CLIENTE'];
      $nom_cliente = $qrBuscaCliente['NOM_CLIENTE'];
  } else {
      $cod_cliente = 0;
  }
}


if (isset($_GET['idBem'])) {
    $auxi = explode(',', $_GET['idBem']);
    foreach ($auxi as $valor) {

       //$cod_bem[] = fnDecode($valor);

        $cod_bem .= fnDecode($valor) . ',';
    }
    $cod_bem = rtrim($cod_bem, ',');
}


$num_contrato = fnDecode($_GET['idCt']);
$sqlOrcamento = "SELECT * FROM CONTRATO_ORCAMENTO WHERE COD_EMPRESA = '$cod_empresa' AND NUM_CONTRATO = '$num_contrato' AND COD_CLIENTE = '$cod_cliente' LIMIT 1";

$myquery = mysqli_query(connTemp($cod_empresa, ''), $sqlOrcamento);

if (mysqli_num_rows($myquery) > 0) {
  $result2 = mysqli_fetch_assoc($myquery);

  $val_contrato = $result2['RECEITA_ESP_ORCAMENTO'];
}

if (is_numeric(fnLimpacampo(fnDecode($_GET['idCt'])))) {
    $sql = "SELECT * FROM CONTRATO_BLOCK WHERE COD_EMPRESA = '$cod_empresa' AND NUM_CONTRATO = '$num_contrato' AND COD_CLIENTE = '$cod_cliente'";

    $query = mysqli_query($conn, $sql);
    $qrBusca = mysqli_fetch_assoc($query);
    $cod_tipobem = $qrBusca['COD_TIPOBEM'];
}


$sqlTok = "SELECT * FROM Tokemgeral WHERE COD_EMPRESA = '$cod_empresa' AND COD_CLIENTE = '$cod_cliente' AND NUM_CONTRATO = '$num_contrato'";
$query = mysqli_query($conn, $sqlTok);

if($query && mysqli_num_rows($query) > 0){
    $qrBusca = mysqli_fetch_assoc($query);
    $nom_contrato = $qrBusca['NOM_CONTRATO'];
    $divisao_cotas = $qrBusca['DIVISAO_COTAS'];
    $num_cotas = $qrBusca['NUM_COTAS'];

    $campoReadonly = "readonly='readonly'";
    $regEncontrado = "S";
}else{
    $regEncontrado = 'N';
    $campoReadonly = '';
}

if($cod_tipobem != 6){

    /*$sqlVal= "SELECT 
    TF1.*, 
    TF2.NOM_TAREFA AS TAREFA_PRINCIPAL, 
    SUM(TF2.VAL_PROJETO) AS val_projeto 
    FROM 
    TAREFA_GARANTIA TF1 
    LEFT JOIN 
    TAREFA_GARANTIA TF2 ON TF1.COD_SUBTAREFA = TF2.COD_TAREFA 
    WHERE 
    TF1.COD_EMPRESA = '$cod_empresa' 
    AND TF1.COD_BEM IN ($cod_bem) 
    AND TF1.LOG_ATIVO = 'S' 
    AND TF1.COD_SUBTAREFA = 0 
    GROUP BY 
    TF1.COD_TAREFA 
    ORDER BY 
    TF2.COD_TAREFA
    ";*/

    $sqlVal = "SELECT SUM(VAL_PROJETO) AS val_projeto FROM TAREFA_GARANTIA WHERE COD_EMPRESA = '$cod_empresa' AND COD_BEM = '$cod_bem' AND COD_SUBTAREFA = 0 AND LOG_ATIVO ='S'";

    $queryVal = mysqli_query($conn, $sqlVal);
    $qrBusca = mysqli_fetch_assoc($queryVal);
    $val_contrato = $qrBusca['val_projeto'];

}



?>

<div class="push30"></div>

<div class="row">

  <div class="col-md12 margin-bottom-30">
    <!-- Portlet -->
    <?php if ($popUp != "true") { ?>
        <div class="portlet portlet-bordered">
        <?php } else { ?>
          <div class="portlet" style="padding: 0 20px 20px 20px;">
          <?php } ?>

          <?php if ($popUp != "true") { ?>
            <div class="portlet-title">
              <div class="caption">
                <i class="fal fa-terminal"></i>
                <span class="text-primary">
                  <?php echo $NomePg; ?>
              </span>
          </div>
      </div>

  <?php } ?>

  <div class="portlet-body">

      <?php if ($msgRetorno <> '') { ?>
          <div class="alert <?php echo $msgTipo; ?> alert-dismissible top30 bottom30" role="alert" id="msgRetorno">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                aria-hidden="true">&times;</span></button>
                <?php echo $msgRetorno; ?>
            </div>
        <?php } ?>

        <div class="push30"></div>

        <div class="login-form">

            <form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

              <?php include "bensHeader.php"; ?>

              <div class="push20"></div>

              <fieldset>
                <legend>Dados do Contrato</legend>

                <div class="col-md-5">
                    <div class="form-group">
                        <label for="inputName" class="control-label ">Nome do Projeto</label>
                        <input type="text" class="form-control input-sm" name="NOM_CONTRATO" id="NOM_CONTRATO" <?=$campoReadonly?> value="<?=$nom_contrato?>">
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="form-group">
                        <label for="inputName" class="control-label ">Divisão</label>
                        <input type="text" class="form-control input-sm" name="DIVISAO_COTAS" id="DIVISAO_COTAS" <?=$campoReadonly?> value="<?=$divisao_cotas?>">
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="form-group">
                        <label for="inputName" class="control-label required">Valor Total</label>
                        <input type="text" class="form-control input-sm leitura" readonly="readonly" name="VAL_CONTRATO" id="VAL_CONTRATO" value="<?= fnValor($val_contrato, 2) ?>">
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="form-group">
                        <label for="inputName" class="control-label required">Valor de Cotas</label>
                        <input type="text" class="form-control input-sm leitura" readonly="readonly"
                        name="NUM_COTAS" id="NUM_COTAS"
                        value="<?= fnValor($num_cotas, 2) ?>">
                    </div>
                </div>

            </fieldset>

            <div class="push10"></div>
            <hr>
            <div class="form-group text-right col-lg-12">

                <?php if($regEncontrado == 'N'){ ?>
                    <button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn"><i class="fal fa-plus" aria-hidden="true"></i>&nbsp; Cadastrar</button>
                <?php } ?>
            </div>

            <input type="hidden" name="opcao" id="opcao" value="">
            <input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
            <input type="hidden" name="hHabilitado" id="hHabilitado" value="S">
            <input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa; ?>">
            <input type="hidden" name="COD_CLIENTE" id="COD_CLIENTE" value="<?php echo $cod_cliente; ?>">

            <div class="push5"></div>

        </form>

        <div class="push"></div>

    </div>
</div>
</div>
<!-- fim Portlet -->
</div>
</div>

<script>

    $(document).ready(function() {
        $('#DIVISAO_COTAS').change(function(){
            var divisaoCotas = parseInt($(this).val());

            var valContrato = parseFloat($('#VAL_CONTRATO').val().replace('.', ''));
            var valLimpo = limpaValor($('#VAL_CONTRATO').val())
            var result = valLimpo / divisaoCotas;
            var roundedResult = result.toFixed(2).replace('.', ',');
            $('#NUM_COTAS').val(roundedResult);

            console.log(divisaoCotas);
            console.log(valLimpo);
            console.log(result);

        });
    });

</script>