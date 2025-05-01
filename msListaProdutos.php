<?php

if ($_SESSION['SYS_COD_USUARIO'] == 127937) {
  echo fnDebug('true');
  ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);
  error_reporting(E_ALL);
}

$hashLocal = "";
$cod_orcamento = "";
$arrayQuery = [];
$qrBuscaEmpresa = "";
$nom_empresa = "";
$qrBuscaConfiguracao = "";
$cod_configu = "";
$log_ativo_tkt = "";
$mostraLOG_ATIVO_TKT = "";
$log_emisdia = "";
$mostraLOG_EMISDIA = "";
$cod_template_tkt = "";
$qtd_compras_tkt = 0;
$qtd_ofertas_tkt = 0;
$qtd_ofertws_tkt = 0;
$qtd_ofertas_lst = 0;
$qtd_categor_tkt = 0;
$qtd_produtos_tkt = 0;
$qtd_produtos_cat = 0;
$num_historico_tkt = "";
$min_historico_tkt = "";
$max_historico_tkt = "";
$cod_blklist = "";
$des_pratprc = "";
$des_validade = "";
$log_listaws = "";
$mostraLOG_LISTAWS = "";
$popUp = "";
$formBack = "";
$msgRetorno = "";
$msgTipo = "";
$arrayQueryTipo = [];
$qrTipo = "";
$personaliza = "";
$tmpCampo = "";
$sqlSistemas = "";
$arraySis = [];
$qrSis = "";
$sqlLinha = "";
$arrayQueryLinha = [];
$qrLinha = "";
$i = "";
$tmp = "";
$cardIsHom = "";

//echo fnDebug('true');

$hashLocal = mt_rand();

//busca dados da url	
if (is_numeric(fnLimpacampo(fnDecode(@$_GET['id'])))) {
  //busca dados da empresa
  $cod_orcamento = "";
  $cod_empresa = fnDecode(@$_GET['id']);
  $sql = "SELECT COD_EMPRESA, NOM_FANTASI FROM empresas where COD_EMPRESA = '" . $cod_empresa . "' ";

  //fnEscreve($sql);
  $arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
  $qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

  if (isset($qrBuscaEmpresa)) {
    $nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];
  }
} else {
  $cod_empresa = 0;
  $nom_empresa = "";
  $cod_orcamento = 0;
}


//busca dados da configuração	
if (is_numeric(fnLimpacampo(fnDecode(@$_GET['id'])))) {
  //busca dados da empresa
  $cod_empresa = fnDecode(@$_GET['id']);
  $sql = "SELECT * FROM CONFIGURACAO_TICKET where COD_EMPRESA = '" . $cod_empresa . "' ";

  //fnEscreve($sql);
  //fnTesteSql(connTemp($cod_empresa,""),trim($sql));

  $arrayQuery = mysqli_query(connTemp($cod_empresa, ""), trim($sql));
  $qrBuscaConfiguracao = mysqli_fetch_assoc($arrayQuery);

  //print_r($arrayQuery);	

  if (isset($qrBuscaConfiguracao)) {
    $cod_configu = $qrBuscaConfiguracao['COD_CONFIGU'];
    $log_ativo_tkt = $qrBuscaConfiguracao['LOG_ATIVO_TKT'];
    if ($log_ativo_tkt == "S") {
      $mostraLOG_ATIVO_TKT = "checked";
    } else {
      $mostraLOG_ATIVO_TKT = "";
    }
    $log_emisdia = $qrBuscaConfiguracao['LOG_EMISDIA'];
    if ($log_emisdia == "S") {
      $mostraLOG_EMISDIA = "checked";
    } else {
      $mostraLOG_EMISDIA = "";
    }
    $cod_template_tkt = $qrBuscaConfiguracao['COD_TEMPLATE_TKT'];
    $qtd_compras_tkt = $qrBuscaConfiguracao['QTD_COMPRAS_TKT'];
    $qtd_ofertas_tkt = $qrBuscaConfiguracao['QTD_OFERTAS_TKT'];
    $qtd_ofertws_tkt = $qrBuscaConfiguracao['QTD_OFERTWS_TKT'];
    $qtd_ofertas_lst = $qrBuscaConfiguracao['QTD_OFERTAS_LST'];
    $qtd_categor_tkt = $qrBuscaConfiguracao['QTD_CATEGOR_TKT'];
    $qtd_produtos_tkt = $qrBuscaConfiguracao['QTD_PRODUTOS_TKT'];
    $qtd_produtos_cat = $qrBuscaConfiguracao['QTD_PRODUTOS_CAT'];
    $num_historico_tkt = $qrBuscaConfiguracao['NUM_HISTORICO_TKT'];
    $min_historico_tkt = $qrBuscaConfiguracao['MIN_HISTORICO_TKT'];
    $max_historico_tkt = $qrBuscaConfiguracao['MAX_HISTORICO_TKT'];
    $cod_blklist = $qrBuscaConfiguracao['COD_BLKLIST'];
    $des_pratprc = $qrBuscaConfiguracao['DES_PRATPRC'];
    $des_validade = $qrBuscaConfiguracao['DES_VALIDADE'];
    $log_listaws = $qrBuscaConfiguracao['LOG_LISTAWS'];
    if ($log_listaws == "S") {
      $mostraLOG_LISTAWS = "checked";
    } else {
      $mostraLOG_LISTAWS = "";
    }
  } else {
    $cod_configu = 0;
    $log_ativo_tkt = "";
    $log_emisdia = "";
    $cod_template_tkt = 0;
    $qtd_compras_tkt = "";
    $qtd_ofertas_tkt = "";
    $qtd_ofertws_tkt = "";
    $qtd_ofertas_lst = "";
    $qtd_categor_tkt = "";
    $qtd_produtos_tkt = "1";
    $qtd_produtos_cat = "1";
    $num_historico_tkt = "";
    $min_historico_tkt = "0";
    $max_historico_tkt = "30";
    $cod_blklist = "0";
    $des_validade = "0";
    $mostraLOG_EMISDIA = '';
    $mostraLOG_ATIVO_TKT = '';
    $mostraLOG_LISTAWS = '';
  }
} else {
  $cod_configu = 0;
  $log_ativo_tkt = "";
  $log_emisdia = "";
  $cod_template_tkt = 0;
  $qtd_compras_tkt = "";
  $qtd_ofertas_tkt = "";
  $qtd_ofertws_tkt = "";
  $qtd_categor_tkt = "";
  $qtd_produtos_tkt = "";
  $num_historico_tkt = "";
  $min_historico_tkt = "0";
  $max_historico_tkt = "30";
  $des_validade = "0";
  $cod_blklist = "0";
  $mostraLOG_EMISDIA = '';
  $mostraLOG_ATIVO_TKT = '';
  $mostraLOG_LISTAWS = '';
}
//print_r(explode(@$_REQUEST['NUM_HISTORICO_TKT']));	
//fnMostraForm();
//fnEscreve($min_historico_tkt);	
//fnEscreve($cod_empresa);	
//fnEscreve($nom_empresa);	
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
              <i class="glyphicon glyphicon-calendar"></i>
              <span class="text-primary"><?php echo $NomePg; ?> / <?php echo $nom_empresa; ?></span>
            </div>

            <?php
            switch ($_SESSION["SYS_COD_SISTEMA"]) {
              case 18: //mais cash
                $formBack = "1681";
                break;
            }

            include "atalhosPortlet.php";
            ?>

          </div>
        <?php } ?>

        <div class="portlet-body">

          <?php if ($msgRetorno <> '') { ?>
            <div class="alert <?php echo $msgTipo; ?> alert-dismissible top30 bottom30" role="alert" id="msgRetorno">
              <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <?php echo $msgRetorno; ?>
            </div>
          <?php } ?>

          <div class="push30"></div>

          <div class="login-form">

            <div id="relatorioConteudo">

              <form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">


                <div class="row">


                  <div class="col-md-2"></div>

                  <div class="row">

                    <div class="col-md-12 ">

                      <center>
                        <img src="images/banner_marka_store2.jpg">
                      </center>

                      <div class="push20"></div>

                      <h4 style="margin-left: 30px;">Selecione a comunicação e a quantidade desejada</h4>

                      <div class="push20"></div>

                      <?php
                      $sql = "SELECT COD_CANALCOM, DES_CANALCOM, LOG_PERSONALIZA FROM CANAL_COMUNICACAO ORDER BY NUM_ORDENAC ";
                      $arrayQueryTipo = mysqli_query($connAdm->connAdm(), trim($sql));
                      while ($qrTipo = mysqli_fetch_assoc($arrayQueryTipo)) {

                        if ($qrTipo['LOG_PERSONALIZA'] == 'S') {
                          $personaliza = "<span class='fas fa-check text-success'></span>";
                        } else {
                          $personaliza = "<span class='fas fa-times text-danger'></span>";
                        }

                        switch ($qrTipo['COD_CANALCOM']) {
                          case 13:
                            $tmpCampo = "QTD_EMAIL";
                            break;
                          case 20:
                            $tmpCampo = "QTD_WPP";
                            break;
                          case 21:
                            $tmpCampo = "QTD_SMS";
                            break;

                          default:
                            break;
                        }
                      ?>


                        <div class="col-md-4">

                          <div class="col-md-12">

                            <div class="no-more-tables">

                              <table id="tbl_<?= $tmpCampo ?>" class="table table-bordered table-striped table-hover">
                                <thead>
                                  <tr>
                                    <th class="text-center f21" colspan="4"><?= $qrTipo['DES_CANALCOM'] ?></th>
                                  </tr>
                                  <tr>
                                    <th class="text-center">QUANTIDADE</th>
                                    <th class="text-center">UNITÁRIO</th>
                                    <th class="text-center">VALIDADE</th>
                                    <th class="text-center">TOTAL</th>
                                    <th class="text-center" width="40">&nbsp</th>
                                  </tr>
                                </thead>

                                <tbody align="center">
                                  <?php

                                  $sqlSistemas = "SELECT COD_SISTEMAS FROM empresas E WHERE COD_EMPRESA=$cod_empresa";
                                  $arraySis = mysqli_query($connAdm->connAdm(), trim($sqlSistemas));
                                  $qrSis = mysqli_fetch_assoc($arraySis);

                                  $sqlLinha = " SELECT CP.COD_COMFAIXA,
                                                         CF.NOM_FAIXA,
                                                         (CF.NUM_FAIXAFIM - CF.NUM_FAIXAINI) AS QTD_TOTAL,
                                                         CF.NUM_FAIXAINI,
                                                         CF.NUM_FAIXAFIM,
                                                         CONCAT(CF.NUM_FAIXAINI,' a ', CF.NUM_FAIXAFIM) AS NOME,
                                                         CP.VAL_UNITARIO,
                                                         CP.COD_PRECO,
                                                         CP.VAL_TOTAL,
                                                         CP.QTD_DIASVALID
                                                  FROM COMUNICACAO_PRECO CP
                                                  LEFT JOIN COMUNICACAO_FAIXAS CF ON CF.COD_COMFAIXA = CP.COD_COMFAIXA
                                                  WHERE CP.COD_CANALCOM = $qrTipo[COD_CANALCOM]
                                                  AND CP.COD_SISTEMA IN ($qrSis[COD_SISTEMAS])";

                                  // fnEscreve($sqlLinha);

                                  $arrayQueryLinha = mysqli_query($connAdm->connAdm(), trim($sqlLinha));



                                  // var_dump($sqlLinha);
                                  while ($qrLinha = mysqli_fetch_assoc($arrayQueryLinha)) {
                                    // fnEscreve($qrLinha['LOG_PERSONALIZA']);
                                  ?>

                                    <tr class="tr_<?= $tmpCampo ?>">
                                      <td><b><?= $qrLinha['NOM_FAIXA'] ?></b></td>

                                      <td><b><?= $qrLinha['VAL_UNITARIO'] ?></b></td>
                                      <td><b><?= $qrLinha['QTD_DIASVALID'] ?></b> <small>dias</small></td>

                                      <td class="text-right">R$ <span id="VAL_TOTAL_<?= $qrLinha['COD_PRECO'] ?>"><?= fnValor($qrLinha['VAL_TOTAL'], 2) ?></span></td>
                                      <td>&nbsp;
                                        <i class="fal fa-cart-plus cart"></i>
                                        <input type="hidden" class="val_<?= $tmpCampo ?>" cod_canal="<?= $qrTipo['COD_CANALCOM'] ?>" cod_faixa="<?= $qrLinha['COD_COMFAIXA'] ?>" faixa_ini="<?= $qrLinha['NUM_FAIXAINI'] ?>" faixa_fim="<?= $qrLinha['NUM_FAIXAFIM'] ?>" value="<?= $qrLinha['VAL_UNITARIO'] ?>">
                                      </td>

                                    </tr>

                                  <?php
                                  }
                                  ?>

                                </tbody>

                                <tfoot>

                                  <tr>
                                    <th class="text-center" colspan="4">PERSONALIZAÇÃO&nbsp;&nbsp;<?= $personaliza ?></th>
                                  </tr>
                                </tfoot>

                              </table>

                            </div>

                          </div>

                        </div>

                      <?php
                        $personaliza = "";
                      }
                      ?>

                      <div class="col-md-4">
                        <div class="form-group">
                          <label for="inputName" class="control-label">Quantos <b>e-Mails</b> você deseja? </label>
                          <input type="text" class="form-control input-lg text-center int" name="QTD_EMAIL" id="QTD_EMAIL" value="" placeholder="Emails desejados" required="" maxlength="10">
                          <input type="hidden" name="QTD_EMAIL_TOTAL" id="QTD_EMAIL_TOTAL" value="">
                          <input type="hidden" name="QTD_EMAIL_COD_CANALCOM" id="QTD_EMAIL_COD_CANALCOM" value="">
                          <input type="hidden" name="QTD_EMAIL_COD_COMFAIXA" id="QTD_EMAIL_COD_COMFAIXA" value="">
                          <div class="help-block with-errors"></div>
                        </div>
                      </div>

                      <div class="col-md-4">
                        <div class="form-group">
                          <label for="inputName" class="control-label">Quantos <b>Whats App</b> você deseja? </label>
                          <input type="text" class="form-control input-lg text-center int" name="QTD_WPP" id="QTD_WPP" value="" placeholder="Whats app desejados" required="" maxlength="10">
                          <input type="hidden" name="QTD_WPP_TOTAL" id="QTD_WPP_TOTAL" value="">
                          <input type="hidden" name="QTD_WPP_COD_CANALCOM" id="QTD_WPP_COD_CANALCOM" value="">
                          <input type="hidden" name="QTD_WPP_COD_COMFAIXA" id="QTD_WPP_COD_COMFAIXA" value="">

                          <div class="help-block with-errors"></div>
                        </div>
                      </div>

                      <div class="col-md-4">
                        <div class="form-group">
                          <label for="inputName" class="control-label">Quantos <b>SMS</b> você deseja? </label>
                          <input type="text" class="form-control input-lg text-center int" name="QTD_SMS" id="QTD_SMS" value="" placeholder="SMS desejados" required="" maxlength="10">
                          <input type="hidden" name="QTD_SMS_TOTAL" id="QTD_SMS_TOTAL" value="">
                          <input type="hidden" name="QTD_SMS_COD_CANALCOM" id="QTD_SMS_COD_CANALCOM" value="">
                          <input type="hidden" name="QTD_SMS_COD_COMFAIXA" id="QTD_SMS_COD_COMFAIXA" value="">
                          <div class="help-block with-errors"></div>
                        </div>
                      </div>


                      <div class="push30"></div>

                      <div class="col-md-4">
                        <div class="form-group">
                          <label for="inputName" class="control-label required">Forma de Pagamento</label>
                          <select class="form-control input-lg text-center" name="TIP_PAGTO" id="TIP_PAGTO">
                            <option value="0"></option>
                            <option value="1">Cartão</option>
                            <option value="2">Boleto</option>
                          </select>
                          <div class="help-block with-errors"></div>
                        </div>
                      </div>

                      <div class="col-md-8 camposCartao">
                        <div class="form-group">
                          <label for="inputName" class="control-label required">Nome Impresso no Cartão</label>
                          <input type="text" class="form-control input-lg text-center" name="NOM_CARTAO" id="NOM_CARTAO" value="" placeholder="Nome Impresso no Cartão" required="" maxlength="50">
                          <div class="help-block with-errors"></div>
                        </div>
                      </div>


                      <div class="col-md-5 camposCartao">
                        <div class="form-group">
                          <label for="inputName" class="control-label required">Número do Cartão</label>
                          <input type="text" class="form-control input-lg text-center int" name="NUM_CARTAO" id="NUM_CARTAO" value="" placeholder="Número do Cartão" required="" maxlength="16">
                          <div class="help-block with-errors"></div>
                        </div>
                      </div>

                      <div class="col-md-1 camposCartao">
                        <div class="form-group">
                          <img id="NUM_CARTAO_IMG" src="" />
                          <input type="hidden" id="CARTAO_BANDEIRA" name="CARTAO_BANDEIRA" value="" />
                        </div>
                      </div>


                      <div class="col-md-2 camposCartao">
                        <div class="form-group">
                          <label for="inputName" class="control-label required">Mês de Validade</label>
                          <select class="form-control input-lg text-center" name="MES_VALIDO" id="MES_VALIDO">
                            <?php
                            for ($i = 1; $i <= 12; $i++) {
                              $tmp = str_pad($i, 2, "0", STR_PAD_LEFT);
                              echo "<option value='$tmp'>$tmp</option>";
                            }
                            ?>
                          </select>
                          <div class="help-block with-errors"></div>
                        </div>
                      </div>

                      <div class="col-md-2 camposCartao">
                        <div class="form-group">
                          <label for="inputName" class="control-label required">Ano de Validade</label>
                          <select class="form-control input-lg text-center" name="ANO_VALIDO" id="ANO_VALIDO">
                            <?php
                            for ($i = date("Y"); $i < date("Y") + 20; $i++) {
                              echo "<option value='$i'>$i</option>";
                            }
                            ?>
                          </select>
                          <div class="help-block with-errors"></div>
                        </div>
                      </div>

                      <div class="col-md-2 camposCartao">
                        <div class="form-group">
                          <label for="inputName" class="control-label required">CVV</label>
                          <input type="text" class="form-control input-lg text-center int" name="NUM_CVV" id="NUM_CVV" value="" placeholder="CVV" required="" maxlength="4">
                          <div class="help-block with-errors"></div>
                        </div>
                      </div>


                      <div class="col-md-4 camposPessoa">
                        <div class="form-group">
                          <label for="inputName" class="control-label required">CPF/CNPJ do Cartão</label>
                          <input type="text" class="form-control input-lg text-center cpfcnpj" name="NUM_DOCUMENTO" id="NUM_DOCUMENTO" value="" placeholder="CPF/CNPJ" required="" maxlength="20">
                          <div class="help-block with-errors"></div>
                        </div>
                      </div>


                      <div class="col-md-8 camposPessoa">
                        <div class="form-group">
                          <label for="inputName" class="control-label required">Nome Completo</label>
                          <input type="text" class="form-control input-lg text-center" name="NOM_COMPRADOR" id="NOM_COMPRADOR" value="" placeholder="Nome Completo" required="" maxlength="100">
                          <div class="help-block with-errors"></div>
                        </div>
                      </div>

                      <div class="col-md-12 camposPessoaCompl">
                        <div class="form-group">
                          <label for="inputName" class="control-label required">Email</label>
                          <input type="text" class="form-control input-lg text-center" name="DES_EMAIL" id="DES_EMAIL" value="" placeholder="E-mail" required="" maxlength="100">
                          <div class="help-block with-errors"></div>
                        </div>
                      </div>

                      <div class="col-md-3 camposPessoaCompl camposCPF">
                        <div class="form-group">
                          <label for="inputName" class="control-label required">Nascimento</label>
                          <input type="text" class="form-control input-lg text-center int" name="DAT_NASCIMENTO" id="DAT_NASCIMENTO" value="" placeholder="Nascimento" required="" maxlength="10">
                          <div class="help-block with-errors"></div>
                        </div>
                      </div>

                      <div class="col-md-2 camposPessoaCompl">
                        <div class="form-group">
                          <label for="inputName" class="control-label required">DDD</label>
                          <input type="text" class="form-control input-lg text-center int" name="NUM_TELEFDDD" id="NUM_TELEFDDD" value="" placeholder="DDD" required="" maxlength="2">
                          <div class="help-block with-errors"></div>
                        </div>
                      </div>

                      <div class="col-md-4 camposPessoaCompl">
                        <div class="form-group">
                          <label for="inputName" class="control-label required">Telefone</label>
                          <input type="text" class="form-control input-lg text-center int" name="NUM_TELEFONE" id="NUM_TELEFONE" value="" placeholder="Telefone" required="" maxlength="10">
                          <div class="help-block with-errors"></div>
                        </div>
                      </div>

                      <div class="col-md-3 camposPessoaCompl">
                        <div class="form-group">
                          <label for="inputName" class="control-label required">CEP</label>
                          <input type="text" class="form-control input-lg text-center int" name="NUM_CEP" id="NUM_CEP" value="" placeholder="CEP" required="" maxlength="9">
                          <div class="help-block with-errors"></div>
                        </div>
                      </div>

                      <div class="col-md-8 camposPessoaCompl">
                        <div class="form-group">
                          <label for="inputName" class="control-label required">Endereço</label>
                          <input type="text" class="form-control input-lg text-center" name="DES_ENDERECO" id="DES_ENDERECO" value="" placeholder="Endereço" required="" maxlength="100">
                          <div class="help-block with-errors"></div>
                        </div>
                      </div>

                      <div class="col-md-4 camposPessoaCompl">
                        <div class="form-group">
                          <label for="inputName" class="control-label required">Número</label>
                          <input type="text" class="form-control input-lg text-center int" name="NUM_ENDERECO" id="NUM_ENDERECO" value="" placeholder="Número" required="" maxlength="10">
                          <div class="help-block with-errors"></div>
                        </div>
                      </div>


                      <div class="col-md-6 camposPessoaCompl">
                        <div class="form-group">
                          <label for="inputName" class="control-label required">Complemento</label>
                          <input type="text" class="form-control input-lg text-center" name="DES_COMPLEMENTO" id="DES_COMPLEMENTO" value="" placeholder="Complemento" required="" maxlength="50">
                          <div class="help-block with-errors"></div>
                        </div>
                      </div>

                      <div class="col-md-6 camposPessoaCompl">
                        <div class="form-group">
                          <label for="inputName" class="control-label required">Bairro</label>
                          <input type="text" class="form-control input-lg text-center" name="NOM_BAIRRO" id="NOM_BAIRRO" value="" placeholder="Bairro" required="" maxlength="50">
                          <div class="help-block with-errors"></div>
                        </div>
                      </div>

                      <div class="col-md-8 camposPessoaCompl">
                        <div class="form-group">
                          <label for="inputName" class="control-label required">Cidade</label>
                          <input type="text" class="form-control input-lg text-center" name="NOM_CIDADE" id="NOM_CIDADE" value="" placeholder="Complemento" required="" maxlength="50">
                          <div class="help-block with-errors"></div>
                        </div>
                      </div>

                      <div class="col-md-4 camposPessoaCompl">
                        <div class="form-group">
                          <label for="inputName" class="control-label required">UF</label>
                          <input type="text" class="form-control input-lg text-center" name="ABR_UF" id="ABR_UF" value="" placeholder="Bairro" required="" maxlength="9">
                          <div class="help-block with-errors"></div>
                        </div>
                      </div>


                      <div class="push30"></div>

                      <div class="col-md-6">

                        <div class="form-group">
                          <label for="inputName" class="control-label">Total da Compra</label>
                          <input type="text" class="form-control input-lg leituraOff text-center" name="VAL_TOTAL" id="VAL_TOTAL" value="" placeholder="0.00" required="" maxlength="10" readonly>
                          <div class="help-block with-errors"></div>
                        </div>

                        <?php
                        //echo $_SESSION["SYS_COD_EMPRESA"];
                        if ($_SESSION["SYS_COD_EMPRESA"] == "2" || $_SESSION["SYS_COD_EMPRESA"] == "3") {
                        ?>
                          <div class="push5"></div>
                          <div class="form-group">
                            <label for="inputName" class="control-label"><b>Valor do Desconto</b></label>
                            <input type="text" class="form-control input-lg text-center money" name="VAL_TOTAL_DESC" id="VAL_TOTAL_DESC" value="" placeholder="0.00" required="" maxlength="10">
                            <div class="help-block with-errors text-danger"><b>Perfil Master</b></div>
                          </div>
                        <?php
                        }
                        ?>

                      </div>

                      <div class="col-md-6">
                        <div class="push20"></div>
                        <button type="button" name="CARRINHO" id="CARRINHO" class="btn btn-success btn-lg btn-block getBtn" tabindex="5"><i class="fal fa-1x fa-cart-plus" aria-hidden="true"></i>&nbsp; Comprar </button>

                        <?php
                        //echo $_SESSION["SYS_COD_EMPRESA"];
                        if ($_SESSION["SYS_COD_EMPRESA"] == "2" || $_SESSION["SYS_COD_EMPRESA"] == "3") {
                        ?>
                          <div class="push20"></div>
                          <button type="button" name="CREDITAR" id="CREDITAR" class="btn btn-danger btn-lg btn-block getBtn" tabindex="5"><i class="fal fa-1x fa-cart-plus" aria-hidden="true"></i>&nbsp; Creditar Administrativo</button>
                        <?php
                        }
                        ?>



                      </div>


                    </div>
                  </div>


                  <div class="col-md-2"></div>

                </div>

                <input type="hidden" class="form-control input-sm" name="COD_ORCAMENTO" id="COD_ORCAMENTO" value="<?php echo $cod_orcamento ?>">
                <input type="hidden" class="form-control input-sm" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa ?>">
                <input type="hidden" name="opcao" id="opcao" value="">
                <input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
                <input type="hidden" name="hHabilitado" id="hHabilitado" value="S">

                <div class="push5"></div>

              </form>

            </div>

            <div class="push50"></div>

          </div>
        </div>
        </div>
        <!-- fim Portlet -->
      </div>
  </div>

  <style>
    #NUM_CARTAO_IMG {
      display: none;
      margin-top: 40px;
    }

    .camposCartao,
    .camposPessoa,
    .camposPessoaCompl {
      display: none;
    }

    .table {
      font-size: 12px;
    }

    .table tr:nth-child(even).on,
    .table tr:nth-child(odd).on {
      background-color: #80ccff;
    }

    .table tr:nth-child(even).on td .cart,
    .table tr:nth-child(odd).on td .cart {
      color: #444444;
    }

    .tr_QTD_EMAIL,
    .tr_QTD_WPP,
    .tr_QTD_SMS {
      cursor: pointer;
    }

    .tr_QTD_EMAIL:hover td .cart,
    .tr_QTD_WPP:hover td .cart,
    .tr_QTD_SMS:hover td .cart {
      color: #444444;
    }

    .cart {
      color: #DEDEDE;
    }

    .cart:hover {
      color: #444444;
    }
  </style>

  <script src="js/plugins/ion.rangeSlider.js"></script>
  <link rel="stylesheet" href="css/ion.rangeSlider.css" />
  <link rel="stylesheet" href="css/ion.rangeSlider.skinHTML5.css" />

  <div class="push20"></div>

  <?php
  if ($cod_empresa == 7) {
    $cardIsHom = "N";
  } else {
    $cardIsHom = "N";
  }
  if ($cardIsHom == "S") {
  ?>
    <script type="text/javascript" src="https://stc.sandbox.pagseguro.uol.com.br/pagseguro/api/v2/checkout/pagseguro.directpayment.js"></script>
  <?php } else { ?>
    <script type="text/javascript" src="https://stc.pagseguro.uol.com.br/pagseguro/api/v2/checkout/pagseguro.directpayment.js"></script>
  <?php } ?>

  <script type="text/javascript">
    $(document).ready(function() {

      PagSeguroDirectPayment.setSessionId('<?php echo sessions_PagSeguro(); ?>');


      $("#TIP_PAGTO").change(function() {
        $(".camposCartao").hide();
        $(".camposPessoa").hide();
        $(".camposPessoaCompl").hide();
        if ($(this).val() === "1") {
          $(".camposCartao").show();
        }

        if ($(this).val() !== "0") {
          $(".camposPessoa").show();
        }

      });
      $("#TIP_PAGTO").trigger("change");
      $(".tr_QTD_EMAIL, .tr_QTD_WPP, .tr_QTD_SMS").click(function() {
        var idObjQtd = "#" + $(this).attr("class").replace("tr_", "");
        var qtdComprada = parseInt("0" + $(this).find(":input").attr("faixa_ini"));
        $(idObjQtd).val(qtdComprada).trigger("change");
      });
      $("#QTD_EMAIL, #QTD_WPP, #QTD_SMS").change(function() {
        var qtdComprada = parseFloat($(this).val());
        var idObj = $(this).attr("id");
        var idObjTotal = "#" + idObj + "_TOTAL";
        var idObjCanal = "#" + idObj + "_COD_CANALCOM";
        var idObjFaixa = "#" + idObj + "_COD_COMFAIXA";
        var className = ".val_" + idObj;
        var faixaIni = 0;
        var faixaFim = 0;
        var faixaVal = 0;
        $(className).each(function() {
          if ($(this).closest("tr").hasClass("on")) {
            $(this).closest("tr").removeClass("on");
          }
          faixaIni = parseFloat("0" + $(this).attr("faixa_ini"));
          faixaFim = parseFloat("0" + $(this).attr("faixa_fim"));
          if (qtdComprada >= faixaIni && qtdComprada <= faixaFim) {
            faixaVal = parseFloat("0" + $(this).val(), 4);
            $(idObjTotal).val(parseFloat(qtdComprada * faixaVal).toFixed(2));
            $(idObjCanal).val($(this).attr("cod_canal"));
            $(idObjFaixa).val($(this).attr("cod_faixa"));
            calculaTotalCompra();
            $(this).closest("tr").addClass("on");
          }
        });
      });
      $("#NUM_CARTAO").change(function() {
        PagSeguroDirectPayment.getBrand({
          cardBin: $(this).val(),
          success: function(response) {
            var urlImg = "https://stc.pagseguro.uol.com.br/public/img/payment-methods-flags/68x30/" + response.brand.name + ".png";
            $("#CARTAO_BANDEIRA").val(response.brand.name);
            $("#NUM_CARTAO_IMG").attr("src", urlImg)
            $("#NUM_CARTAO_IMG").show();
          },
          error: function(response) {
            $("#CARTAO_BANDEIRA").val("");
            $("#NUM_CARTAO_IMG").hide();
          }
        });
      });

      $("#NUM_DOCUMENTO").change(function() {
        var val = $(this).val().replace(/\D/g, '');
        var valMsk = "";
        $.ajax({
          method: 'POST',
          url: 'msBlocoCompra.do?id=<?= fnEncode($cod_empresa) ?>&acao=busca_pagador',
          data: $("#formulario").serialize(),
          dataType: 'json',
          /*
           beforeSend: function () {
           $("#relatorioConteudo").html("<div class='loading' style='width:100%'></div>");
           },
           */
          success: function(data) {
            $.each(data, function(data, item) {
              $("#NOM_COMPRADOR").val(item.NOM_COMPRADOR);
              $("#DAT_NASCIMENTO").val(item.DAT_NASCIMENTO);
              $("#NUM_TELEFDDD").val(item.NUM_TELEFDDD);
              $("#NUM_TELEFONE").val(item.NUM_TELEFONE);
              $("#DES_EMAIL").val(item.DES_EMAIL);
              $("#NUM_CEP").val(item.NUM_CEP);
              $("#DES_ENDERECO").val(item.DES_ENDERECO);
              $("#NUM_ENDERECO").val(item.NUM_ENDERECO);
              $("#DES_COMPLEMENTO").val(item.DES_COMPLEMENTO);
              $("#NOM_BAIRRO").val(item.NOM_BAIRRO);
              $("#NOM_CIDADE").val(item.NOM_CIDADE);
              $("#ABR_UF").val(item.ABR_UF);
            });
          },
          error: function(data) {
            //$("#relatorioConteudo").html("Ops... Dados <b>não encontrados</b>.");
          }
        });
        $(".camposPessoaCompl").show();
        //MASCADA CPF/CNPJ
        if (val.length === 14) {
          valMsk = val.substr(0, 2) +
            "." + val.substr(2, 3) +
            "." + val.substr(5, 3) +
            "/" + val.substr(8, 4) +
            "-" + val.substr(12, 2);
          $(".camposCPF").hide();
        } else if (val.length === 11) {
          valMsk = val.substr(0, 3) +
            "." + val.substr(3, 3) +
            "." + val.substr(6, 3) +
            "-" + val.substr(9, 2);
          $(".camposCNPJ").hide();
        } else {
          valMsk = "";
        }
        $(this).val(valMsk);
      });
      $("#DAT_NASCIMENTO").change(function() {
        var val = $(this).val().replace(/\D/g, '');
        var valMsk = "";
        if (val.length === 8) {
          valMsk = val.substr(0, 2) +
            "/" + val.substr(2, 2) +
            "/" + val.substr(4, 4);
        } else {
          valMsk = "";
        }
        $(this).val(valMsk);
      });
      $("#NUM_TELEFONE").change(function() {
        var val = $(this).val().replace(/\D/g, '');
        var valMsk = "";
        if (val.length === 8) {
          valMsk = val.substr(0, 4) + "-" + val.substr(4, 4);
        } else if (val.length === 9) {
          valMsk = val.substr(0, 5) + "-" + val.substr(5, 4);
        } else {
          valMsk = $(this).val();
        }
        $(this).val(valMsk);
      });
      $("#NUM_CEP").change(function() {
        var val = $(this).val().replace(/\D/g, '');
        var valMsk = "";
        var cep = val;
        //Verifica se campo cep possui valor informado.
        if (cep != "") {

          //Expressão regular para validar o CEP.
          var validacep = /^[0-9]{8}$/;
          //Valida o formato do CEP.
          if (validacep.test(cep)) {
            //Consulta o webservice viacep.com.br/
            $.getJSON("https://viacep.com.br/ws/" + cep + "/json/?callback=?", function(dados) {

              if (!("erro" in dados)) {
                $("#DES_ENDERECO").val(dados.logradouro);
                $("#NOM_BAIRRO").val(dados.bairro);
                $("#NOM_CIDADE").val(dados.localidade);
                $("#ABR_UF").val(dados.uf);
                $("#NUM_ENDERECO").focus();
              } //end if.
              else {
                //CEP pesquisado não foi encontrado.
                alert("CEP não encontrado. Preencha os dados manualmente!");
              }
            });
          } //end if.
          else {
            //cep é inválido.
            alert("Formato de CEP inválido.");
          }
        }

        //marcara cep
        if (val.length === 8) {
          valMsk = val.substr(0, 5) + "-" + val.substr(5, 3);
        } else {
          valMsk = "";
        }
        $(this).val(valMsk);
      });
      //chosen

      function cadastraPedido(cardToken, cardHash, tpOperacao) {
        var urlPost = "msBlocoCompra.do?id=<?= fnEncode($cod_empresa) ?>" +
          "&acao=cad_pedido" +
          "&cardToken=" + cardToken +
          "&cardHash=" + cardHash +
          "&cardIsHom=<?= $cardIsHom ?>" +
          "&tpOperacao=" + tpOperacao;

        $.ajax({
          method: "POST",
          url: urlPost,
          data: $("#formulario").serialize(),
          beforeSend: function() {
            $("#relatorioConteudo").html("<div id='blocker'> <div style='text-align: center;'><img src='images/loading2.gif'><br/> Aguarde. Processando... ;-)</div> </div>");
          },
          success: function(data) {
            $("#relatorioConteudo").html(data);
          },
          error: function(data) {
            $("#relatorioConteudo").html(data);
          }
        });

      }

      $.fn.validator.Constructor.INPUT_SELECTOR = ':input:not([type="submit"], button):enabled, .requiredChk';
      $('#formulario').validator();


      $("#CARRINHO, #CREDITAR").click(function() {
        var tpOperacao = ($(this).attr("id") === "CARRINHO" ? "COMPRA" : "CREDITO");

        if ($("#VAL_TOTAL").val() <= 0 || $("#VAL_TOTAL").val() == "") {
          $.alert({
            title: tpOperacao,
            content: "Nenhum produto escolhido!",
            buttons: {
              Ok: function() {}
            }
          });
          return;
        }

        if ($("#TIP_PAGTO").val() <= 0) {
          $.alert({
            title: tpOperacao,
            content: "Escolha a forma de pagamento!",
            buttons: {
              Ok: function() {}
            }
          });
          return;
        }

        if (tpOperacao === "COMPRA") {
          if ($("#NUM_DOCUMENTO").val() == "") {
            $.alert({
              title: tpOperacao,
              content: "CPF/CNPJ não preenchido!",
              buttons: {
                Ok: function() {}
              }
            });
            return;
          }

          if ($("#TIP_PAGTO").val() == 2) { //BOLETO

            //53018sender area code is required.
            //53020sender phone is required.
            if ($("#NUM_TELEFDDD").val() == "" || $("#NUM_TELEFONE").val() == "") {
              $.alert({
                title: tpOperacao,
                content: "Telefone não preenchido!",
                buttons: {
                  Ok: function() {}
                }
              });
              return;
            }

            //53010sender email is required.
            if ($("#DES_EMAIL").val() == "") {
              $.alert({
                title: tpOperacao,
                content: "Telefone não preenchido!",
                buttons: {
                  Ok: function() {}
                }
              });
              return;
            }

            //53013sender name is required.
            if ($("#NOM_COMPRADOR").val() == "") {
              $.alert({
                title: tpOperacao,
                content: "Nome não preenchido!",
                buttons: {
                  Ok: function() {}
                }
              });
              return;
            }

            //53024shipping address street is required.
            //53029shipping address district is required.
            //53022shipping address postal code is required.
            //53031shipping address city is required.
            //53026shipping address number is required.
            //53033shipping address state is required.
            if ($("#DES_ENDERECO").val() == "" || $("#NOM_BAIRRO").val() == "" ||
              $("#CEP").val() == "" || $("#NOM_CIDADE").val() == "" ||
              $("#ABR_UF").val() == "" || $("#NUM_ENDERECO").val() == "") {
              $.alert({
                title: tpOperacao,
                content: "Nome não preenchido!",
                buttons: {
                  Ok: function() {}
                }
              });
              return;
            }
          }




        }

        $.alert({
          title: tpOperacao,
          content: (tpOperacao === "COMPRA" ? "Confirma a compra no valor total de R$ " + $("#VAL_TOTAL").val() : "Confirma o crédito sem cobrança dos produtos?"),
          buttons: {
            Confirmar: function() {

              var cardToken = "";
              var cardHash = PagSeguroDirectPayment.getSenderHash();

              if ($("#TIP_PAGTO").val() == "1") {
                console.log("CARTAO");
                PagSeguroDirectPayment.createCardToken({
                  cardNumber: $("#NUM_CARTAO").val(),
                  cvv: $("#NUM_CVV").val(),
                  expirationMonth: $("#MES_VALIDO").val(),
                  expirationYear: $("#ANO_VALIDO").val(),
                  success: function(response) {
                    console.log(response);
                    cardToken = response.card.token;
                    console.log(cardToken, "....");
                    cadastraPedido(cardToken, cardHash, tpOperacao);
                  },
                  error: function(response) {
                    let erros = "";
                    Object.keys(response.errors).map(item => {
                      let erro = response.errors[item];
                      if (item == 30400) {
                        erro = "Dados do cartão de crédito inválidos";
                      } else if (item == 10006) {
                        erro = "CVV inválido";
                      } else if (item == 10001) {
                        erro = "Número do cartão de crédito inválido";
                      }
                      return erros += (erros != "" ? "; " : "") + erro;
                    });
                    $.alert({
                      title: tpOperacao,
                      content: erros,
                      buttons: {
                        Ok: function() {}
                      }
                    });
                    cardToken = "";
                  }
                });
              } else {
                cadastraPedido(cardToken, cardHash, tpOperacao);
              }
            },
            Cancelar: function() {

            }
          }

        });
      });

      <?php /*
      $("#TIP_PAGTO").val("2").trigger("change");
      $("#TIP_PAGTO").val("0").trigger("change");
      $("#NOM_CARTAO").val("Teste");
      $("#NUM_CARTAO").val("4111111111111111").trigger("change");
      $("#MES_VALIDO").val("12");
      $("#ANO_VALIDO").val("2030");
      $("#NUM_CVV").val("123");
      $("#NUM_DOCUMENTO").val("163.708.088-30").trigger("change");
 */ ?>
    });



    function calculaTotalCompra() {
      var total = parseFloat("0" + $("#QTD_EMAIL_TOTAL").val()) +
        parseFloat("0" + $("#QTD_WPP_TOTAL").val()) +
        parseFloat("0" + $("#QTD_SMS_TOTAL").val());
      $("#VAL_TOTAL").val(parseFloat(total).toFixed(2));
    }

    $(function() {
      $("#NUM_HISTORICO_TKT").ionRangeSlider({
        hide_min_max: true,
        keyboard: true,
        min: 0,
        max: 120,
        from: <?php echo $min_historico_tkt; ?>,
        to: <?php echo $max_historico_tkt; ?>,
        type: 'int',
        step: 5,
        //prettify_enabled: true,
        //prettify_separator: "."
        //prefix: "Idade ",
        postfix: " dias",
        max_postfix: ""
        //grid: true
      });
      /*
       $("#range").ionRangeSlider();
       */

    });
  </script>