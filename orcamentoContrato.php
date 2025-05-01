<?php

//echo fnDebug('true');

$hashLocal = mt_rand();

$conn = conntemp($cod_empresa, "");
$adm = $connAdm->connAdm();

$regEncontrado = 'N';

$cod_bem = fnLimpacampoZero(fnDecode($_GET['idBem']));

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $request = md5(implode($_POST));

    if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
        $msgRetorno = 'Essa página já foi utilizada';
        $msgTipo = 'alert-warning';
    } else {
        $_SESSION['last_request'] = $request;
        
        $cod_orcamento = fnLimpaCampoZero($_REQUEST['COD_ORCAMENTO']);
        $cod_tpunidades = fnLimpaCampoZero($_REQUEST['COD_TPUNIDADES']);
        $cod_empresa = fnLimpaCampoZero($_REQUEST['COD_EMPRESA']);
        $cod_cliente = fnLimpaCampoZero($_REQUEST['COD_CLIENTE']);
        $num_contrato = fnLimpaCampoZero($_REQUEST['NUM_CONTRATO']);
        $area_orcamento = fnLimpaCampoZero($_REQUEST['AREA_ORCAMENTO']);
        $tip_plantacao = fnLimpaCampo($_REQUEST['TIP_PLANTACAO']);
        $produtividade_orcamento = fnLimpaCampoZero($_REQUEST['PRODUTIVIDADE_ORCAMENTO']);
        $producao_orcamento = fnLimpaCampo(fnValorSql($_REQUEST['PRODUCAO_ORCAMENTO']));
        $valor_unit_orcamento = fnLimpaCampo(fnValorSql($_REQUEST['VALOR_UNIT_ORCAMENTO']));
        $receita_esp_orcamento = fnLimpaCampo(fnValorSql($_REQUEST['RECEITA_ESP_ORCAMENTO']));

        $cod_empresa = fnLimpaCampoZero($_POST['COD_EMPRESA']);
        $cod_usucada = $_SESSION["SYS_COD_USUARIO"];

        $opcao = $_REQUEST['opcao'];
        $hHabilitado = $_REQUEST['hHabilitado'];
        $hashForm = $_REQUEST['hashForm'];

        if ($opcao == 'CAD') {
            $sql = "INSERT INTO CONTRATO_ORCAMENTO (
				COD_TPUNIDADES,
				AREA_ORCAMENTO,
				PRODUTIVIDADE_ORCAMENTO,
				PRODUCAO_ORCAMENTO,
				VALOR_UNIT_ORCAMENTO,
				RECEITA_ESP_ORCAMENTO,
				TIP_PLANTACAO,
				COD_EMPRESA,
				COD_CLIENTE,
				NUM_CONTRATO,
				COD_USUCADA
				) VALUES (
				'$cod_tpunidades',
				'$area_orcamento',
				'$produtividade_orcamento',
				'$producao_orcamento',
				'$valor_unit_orcamento',
				'$receita_esp_orcamento',
				'$tip_plantacao',
				'$cod_empresa',
				'$cod_cliente',
				'$num_contrato',
				'$cod_usucada'
			)";

            mysqli_query(connTemp($cod_empresa, ''), $sql);



            $sql_ultimo = "SELECT *
				FROM CONTRATO_ORCAMENTO
				WHERE COD_EMPRESA = '$cod_empresa' AND NUM_CONTRATO = '$num_contrato' AND COD_CLIENTE = '$cod_cliente'
				ORDER BY DAT_CADASTR DESC
				LIMIT 1";

            $query = mysqli_query(connTemp($cod_empresa, ''), $sql_ultimo);
            //fnEscreve($sql_ultimo);

            if ($query) {
                $result = mysqli_fetch_assoc($query);

                $cod_orcamento = $result['COD_ORCAMENTO'];
                $cod_tpunidades = $result['COD_TPUNIDADES'];
                $produtividade_orcamento = $result['PRODUTIVIDADE_ORCAMENTO'];
                $producao_orcamento = $result['PRODUCAO_ORCAMENTO'];
                $valor_unit_orcamento = $result['VALOR_UNIT_ORCAMENTO'];
                $receita_esp_orcamento = $result['RECEITA_ESP_ORCAMENTO'];
                $tip_plantacao = $result['TIP_PLANTACAO'];

                //fnEscreve2($cod_tpunidades);
            }

        }
        if ($opcao == 'ALT') {

            $sqlUpdate = "UPDATE CONTRATO_ORCAMENTO SET
                AREA_ORCAMENTO = '$area_orcamento',
                PRODUTIVIDADE_ORCAMENTO = '$produtividade_orcamento',
                PRODUCAO_ORCAMENTO = '$producao_orcamento',
                VALOR_UNIT_ORCAMENTO = '$valor_unit_orcamento',
                RECEITA_ESP_ORCAMENTO = '$receita_esp_orcamento',
                TIP_PLANTACAO = '$tip_plantacao',
                COD_ALTERAC = '$cod_usucada',
                DAT_ALTERAC = NOW()
                    WHERE COD_ORCAMENTO = '$cod_orcamento' AND
                    NUM_CONTRATO = '$num_contrato' AND
                    COD_CLIENTE = '$cod_cliente' AND
                    COD_EMPRESA = '$cod_empresa'
            ";
            //fnEscreve($sqlUpdate);
            //fnTestesql(connTemp($cod_empresa, ''), $sqlUpdate);
            mysqli_query(connTemp($cod_empresa, ''), $sqlUpdate);

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

//busca contrato
if (is_numeric(fnLimpacampo(fnDecode($_GET['idCt'])))) {

    $num_contrato = fnDecode($_GET['idCt']);

    $sql = "SELECT NUM_CONTRATO, COD_STATUS, TIPO_FINALIDADE FROM CONTRATO_BLOCK where NUM_CONTRATO = '" . $num_contrato . "' AND COD_CLIENTE = '" . $cod_cliente . "' AND COD_EMPRESA = '" . $cod_empresa . "' ";

    //fnEscreve2($sql);
    $query = mysqli_query(connTemp($cod_empresa, ''), $sql);
    $qrBuscaContrato = mysqli_fetch_assoc($query);

    if (isset($query)) {
        $num_contrato = $qrBuscaContrato['NUM_CONTRATO'];
        $cod_status = $qrBuscaContrato['COD_STATUS'];
        $tipo_finalidade = $qrBuscaContrato['TIPO_FINALIDADE'];

        if ($cod_status == 1) {
            $status = "Proposta";
        } else {
            $status = "";
        }
    } else {
        $num_contrato = 0;
        $cod_status = 0;
    }

    $sqlOrcamento = "SELECT * FROM CONTRATO_ORCAMENTO WHERE COD_EMPRESA = '$cod_empresa' AND NUM_CONTRATO = '$num_contrato' AND COD_CLIENTE = '$cod_cliente' LIMIT 1";
    //fnEscreve2($sqlOrcamento);
    $myquery = mysqli_query(connTemp($cod_empresa, ''), $sqlOrcamento);

    if (mysqli_num_rows($myquery) > 0) {
        $result2 = mysqli_fetch_assoc($myquery);

        $cod_orcamento = $result2['COD_ORCAMENTO'];
        $cod_tpunidades = $result2['COD_TPUNIDADES'];
        $produtividade_orcamento = $result2['PRODUTIVIDADE_ORCAMENTO'];
        $producao_orcamento = $result2['PRODUCAO_ORCAMENTO'];
        $valor_unit_orcamento = $result2['VALOR_UNIT_ORCAMENTO'];
        $receita_esp_orcamento = $result2['RECEITA_ESP_ORCAMENTO'];
        $tip_plantacao = $result2['TIP_PLANTACAO'];
        $regEncontrado = 'S';
    }
}

// busca hect da proposta
$sql = "SELECT SUM(QTD_HECT_USO) AS QTD_HECT_USO
	FROM contrato_block_compl
	WHERE COD_EMPRESA = $cod_empresa
	AND COD_CLIENTE = $cod_cliente
	AND NUM_CONTRATO = $num_contrato";

$arrayQuery2 = mysqli_query(connTemp($cod_empresa, ''), $sql) or die(mysqli_error(connTemp($cod_empresa, '')));
$buscaHect = mysqli_fetch_assoc($arrayQuery2);

if (isset($buscaHect)) {
    $area_ha = $buscaHect['QTD_HECT_USO'];
} else {
    $area_ha = 0;
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
                <legend>Dados de Produção</legend>

                <div class="row">

                  <div class="col-md-1">
                    <div class="form-group">
                      <label for="inputName" class="control-label required">Cód. Orçamento</label>
                      <input type="text" class="form-control input-sm leitura" readonly="readonly" name="COD_ORCAMENTO"
                        id="COD_ORCAMENTO" value="<?= $cod_orcamento ?>">
                    </div>
                  </div>

                  <?php
                                    if ($tipo_finalidade == 1) {
                                        ?>
                  <div class="col-md-2">
                    <div class="form-group">
                      <label for="inputName" class="control-label required">Plantação</label>

                      <div>
                        <input type='radio' name='radioGroup' id='1' onclick='checkRadioButton(this)'>
                        <label for='1'>Mata Nativa</label>

                        <span style="margin-right: 10px;"></span>
                        <input type='radio' name='radioGroup' id='2' onclick='checkRadioButton(this)'>
                        <label for='2'>Reflorestamento</label>
                      </div>

                      <div class="help-block with-errors"></div>
                    </div>
                  </div>
                  <?php
                                    } else {
                                        ?>
                  <div class="col-md-2">
                    <div class="form-group">
                      <label for="inputName" class="control-label required">Tipo de
                        Floresta</label>
                      <input type="text" class="form-control input-sm" name="TIP_PLANTACAO" id="TIP_PLANTACAO"
                        step="0.01" value="<?= $tip_plantacao ?>" required>
                      <div class="help-block with-errors"></div>
                    </div>
                  </div>
                  <?php
                                    }
                                    ?>

                  <div class="col-md-2">
                    <div class="form-group">
                      <label for="inputName" class="control-label required">ÁREA (HA)</label>
                      <input type="text" class="form-control input-sm" name="AREA" id="AREA" step="0.01"
                        value="<?= fnValor($area_ha, 2); ?>" disabled>
                      <div class="help-block with-errors"></div>
                    </div>
                  </div>

                  <div class="col-md-2">
                    <div class="form-group">
                      <label for="inputName" class="control-label required">PRODUTIVIDADE
                        (HA)</label>
                      <input type="number" class="form-control input-sm" name="PRODUTIVIDADE_ORCAMENTO"
                        id="PRODUTIVIDADE_ORCAMENTO" step="0.01" value="<?= $produtividade_orcamento ?>">
                      <div class="help-block with-errors"></div>
                    </div>
                  </div>

                  <div class="col-md-2">
                    <div class="form-group">
                      <label for="inputName" class="control-label">Tipo de Unidade</label>
                      <select data-placeholder="Selecione a Unidade" name="COD_TPUNIDADES" id="COD_TPUNIDADES"
                        class="chosen-select-deselect" style="width:100%;">
                        <option value=""></option>
                        <?php
                                                $sql = "SELECT * FROM TIP_UNIDADES WHERE COD_EMPRESA = $cod_empresa AND DAT_EXCLUSA IS NULL";
                                                $arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);
                                                while ($qrListaCamp = mysqli_fetch_assoc($arrayQuery)) {
                                                    $optionValue = $qrListaCamp['COD_TPUNIDADES'];
                                                    $optionText = ucfirst($qrListaCamp['DES_TPUNIDADES']);
                                                    $selected = ($cod_tpunidades == $optionValue) ? 'selected' : '';

                                                    echo "<option value='$optionValue' $selected>$optionText</option>";
                                                }

                                                ?>
                        <option class="fas fa-plus" value="add">&nbsp;Adicionar Novo</option>
                      </select>

                      <script type="text/javascript">
                      $('#COD_TPUNIDADES').change(function() {
                        valor = $(this).val();
                        console.log("Entrou aqui");
                        if (valor == "add") {
                          $(this).val('').trigger("chosen:updated");
                          $('#btnCad_COD_TPUNIDADES').click();
                        }
                      });

                      $('#COD_TPUNIDADES').val("<?= $cod_tpunidades ?>").trigger('chosen:updated')
                      </script>

                      <div class="help-block with-errors"></div>
                      <a type="hidden" name="btnCad_COD_TPUNIDADES" id="btnCad_COD_TPUNIDADES" class="addBox"
                        data-url="action.php?mod=<?php echo fnEncode(1963) ?>&id=<?php echo fnEncode($cod_empresa) ?>&pop=true"
                        data-title="Cadastro de Unidade"></a>
                    </div>
                  </div>

                  <div class="col-md-2">
                    <div class="form-group">
                      <label for="inputName" class="control-label required">PRODUÇÃO</label>
                      <input type="text" class="form-control input-sm leitura" readonly="readonly"
                        name="PRODUCAO_ORCAMENTO" id="PRODUCAO_ORCAMENTO"
                        value="<?= fnValor($producao_orcamento, 2) ?>">
                    </div>
                  </div>

                </div>

              </fieldset>

              <div class="push20"></div>

              <fieldset>
                <legend>Receitas Esperadas</legend>

                <div class="row">
                  <div class="col-md-2">
                    <div class="form-group">
                      <label for="inputName" class="control-label required">ÁREA (HA)</label>
                      <input type="text" class="form-control input-sm" name="AREA_RECEITAS" id="AREA_RECEITAS"
                        value="<?= fnValor($area_ha, 2); ?>" step="0.01" disabled>
                      <div class="help-block with-errors"></div>
                    </div>
                  </div>

                  <div class="col-md-2">
                    <div class="form-group">
                      <label for="inputName" class="control-label required">PRODUTIVIDADE
                        (HA)</label>
                      <input type="number" class="form-control input-sm" name="PRODUTIVIDADE_RECEITAS"
                        id="PRODUTIVIDADE_RECEITAS" step="0.01" disabled value="<?= $produtividade_orcamento ?>">
                      <div class="help-block with-errors"></div>
                    </div>
                  </div>

                  <div class="col-md-2">
                    <div class="form-group">
                      <label for="inputName" class="control-label">VALOR UNITÁRIO</label>
                      <input type="text" class="form-control input-sm money" name="VALOR_UNIT_ORCAMENTO"
                        id="VALOR_UNIT_ORCAMENTO" maxlength="100" value="<?= fnValor($valor_unit_orcamento, 2) ?>">
                      <div class="help-block with-errors"></div>
                    </div>
                  </div>

                  <div class="col-md-2">
                    <div class="form-group">
                      <label for="inputName" class="control-label required">VALOR TOTAL DO CONTRATO</label>
                      <input type="text" class="form-control input-sm leitura" readonly="readonly"
                        name="RECEITA_ESP_ORCAMENTO" id="RECEITA_ESP_ORCAMENTO"
                        value="<?= fnValor($receita_esp_orcamento, 2) ?>">
                    </div>
                  </div>
                </div>

              </fieldset>

              <div class="push10"></div>
              <hr>
              <div class="form-group text-right col-lg-12">
                <?php
                if ($regEncontrado == "N") { ?>
                <button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn"><i class="fal fa-plus"
                    aria-hidden="true"></i>&nbsp; Cadastrar</button>
                <?php } else { ?>
                <button type="submit" name="ALT" id="ALT" class="btn btn-primary getBtn">Alterar</button>
                <?php
                }
                ?>
              </div>

              <input type="hidden" name="opcao" id="opcao" value="">
              <input type="hidden" name="AREA_ORCAMENTO" id="AREA_ORCAMENTO" value="<?= $area_ha ?>">
              <input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
              <input type="hidden" name="hHabilitado" id="hHabilitado" value="S">
              <input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa; ?>">
              <input type="hidden" name="COD_CLIENTE" id="COD_CLIENTE" value="<?php echo $cod_cliente; ?>">
              <input type="hidden" name="TIP_PLANTACAO" id="TIP_PLANTACAO" value="<?php echo $tip_plantacao; ?>">
              <input type="hidden" name="COD_ORCAMENTO" id="COD_ORCAMENTO" value="<?php echo $cod_orcamento; ?>">

              <div class="push5"></div>

            </form>

            <div class="push"></div>

          </div>
        </div>
      </div>
      <!-- fim Portlet -->
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
      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
  </div><!-- /.modal -->

  <script>
  function fnValor(numero) {
    return numero.toLocaleString('pt-BR', {
      minimumFractionDigits: 2,
      maximumFractionDigits: 2
    });
  }

  $(document).ready(function() {

    $('#AREA_ORCAMENTO, #PRODUTIVIDADE_ORCAMENTO').on('change', function() {

      var valorTotal = parseFloat($('#AREA_ORCAMENTO').val());
      var produtividade = parseFloat($('#PRODUTIVIDADE_ORCAMENTO').val());

      if (!isNaN(valorTotal) && !isNaN(produtividade)) {
        var resultado = valorTotal * produtividade;

        $('#PRODUTIVIDADE_RECEITAS').val(produtividade);
        $('#PRODUCAO_ORCAMENTO').val(fnValor(resultado));
      }
    });
  });

  $(document).ready(function() {
    $('#VALOR_UNIT_ORCAMENTO').on('change', function() {
      //console.log("entrou aqui");
      var areaReceitas = parseFloat($('#AREA_ORCAMENTO').val());
      var produtividadeReceitas = parseFloat($('#PRODUTIVIDADE_ORCAMENTO').val());
      var valorUnitario = parseFloat($('#VALOR_UNIT_ORCAMENTO').val().replace(',', '.'));

      if (!isNaN(areaReceitas) && !isNaN(produtividadeReceitas) && !isNaN(valorUnitario)) {
        var result = (areaReceitas * produtividadeReceitas) * valorUnitario;

        $('#RECEITA_ESP_ORCAMENTO').val(fnValor(result));
      }
    });
  });

  function checkRadioButton(radioButton) {
    var radioGroup = radioButton.name;

    var radioButtons = document.getElementsByName(radioGroup);
    for (var i = 0; i < radioButtons.length; i++) {
      radioButtons[i].checked = false;
    }

    radioButton.checked = true;

    var radioValue = radioButton.id;

    $('#TIP_PLANTACAO').val(radioValue);
  }

  var tip_plantacao = $('#TIP_PLANTACAO').val();
  if (tip_plantacao == 1) {
    $('#1').prop('checked', true);
  } else if (tip_plantacao == 2) {
    $('#2').prop('checked', true);
  }
  </script>