<?php

if ($_SESSION['SYS_COD_USUARIO'] == 127937) {
  echo fnDebug('true');
  ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);
  error_reporting(E_ALL);
}

$itens_por_pagina = 50;
$pagina = 1;
$cod_canalcom = 0;
$dias30 = '';
$dat_fim = '';
$dat_ini = '';
$cod_campanha = '';

$hashLocal = mt_rand();

//inicialização de variáveis
$hoje = fnFormatDate(date("Y-m-d"));
$dias30 = fnFormatDate(date('Y-m-d', strtotime($dias30 . '- 1 month')));

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $request = md5(serialize($_POST));

  if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
    $msgRetorno = 'Essa página já foi utilizada';
    $msgTipo = 'alert-warning';
  } else {
    $_SESSION['last_request'] = $request;

    $cod_empresa = fnLimpaCampo(@$_REQUEST['COD_EMPRESA']);
    $cod_campanha = fnLimpaCampo(@$_REQUEST['COD_CAMPANHA']);
    $cod_canalcom = fnLimpaCampo($_REQUEST['COD_CANALCOM']);
    $dat_ini = fnDataSql(@$_POST['DAT_INI']);
    $dat_fim = fnDataSql(@$_POST['DAT_FIM']);

    $opcao = $_REQUEST['opcao'];
    $hHabilitado = $_REQUEST['hHabilitado'];
    $hashForm = $_REQUEST['hashForm'];

    if ($opcao != '') {


      //mensagem de retorno
      switch ($opcao) {
        case 'CAD':
          $msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
          break;
        case 'ALT':
          $msgRetorno = "Registro alterado com <strong>sucesso!</strong>";
          break;
        case 'EXC':
          $msgRetorno = "Registro excluido com <strong>sucesso!</strong>";
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
  $sql = "SELECT COD_EMPRESA, NOM_FANTASI FROM empresas where COD_EMPRESA = '" . $cod_empresa . "' ";
  //fnEscreve($sql);
  $arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
  $qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

  if (isset($arrayQuery)) {
    $cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
    $nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];
  }
} else {
  $cod_empresa = 0;
  //fnEscreve('entrou else');
}

//inicialização das variáveis - default 
if (strlen($dat_ini) == 0 || $dat_ini == "1969-12-31") {
  $dat_ini = fnDataSql($dias30);
  $dat_ini = fnmesanosql($dat_ini) . "-01";
}
if (strlen($dat_fim) == 0 || $dat_fim == "1969-12-31") {
  $dat_fim = fnDataSql($hoje);
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
          <span class="text-primary"><?php echo $NomePg; ?></span>
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

          <?php

          if ($_SESSION['SYS_COD_EMPRESA'] == 2 || $_SESSION['SYS_COD_EMPRESA'] == 3) {

          ?>

            <!-- <div class="form-group col-lg-12">

              <button type="button" name="CAD" id="CAD" class="btn btn-info getBtn addBox" data-url="action.php?mod=<?php echo fnEncode(1564) ?>&id=<?php echo fnEncode($cod_empresa) ?>&pop=true" data-title="Adicionar Créditos - <?= $nom_empresa ?>"><i class="fal fa-usd-circle" aria-hidden="true"></i>&nbsp; Adicionar Crédito Avulso</button>

            </div>

            <div class="push50"></div> -->

          <?php

          }

          ?>

          <form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

            <fieldset>
              <legend>Dados Gerais</legend>

              <div class="col-md-2">
                <div class="form-group">
                  <label for="inputName" class="control-label required">Data Inicial</label>

                  <div class="input-group date datePicker" id="DAT_INI_GRP">
                    <input type='text' class="form-control input-sm" name="DAT_INI" id="DAT_INI" value="<?= fnDataShort($dat_ini) ?>" required />
                    <span class="input-group-addon">
                      <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                  </div>
                  <div class="help-block with-errors"></div>
                </div>
              </div>

              <div class="col-md-2">
                <div class="form-group">
                  <label for="inputName" class="control-label required">Data Final</label>

                  <div class="input-group date datePicker" id="DAT_FIM_GRP">
                    <input type='text' class="form-control input-sm" name="DAT_FIM" id="DAT_FIM" value="<?= fnDataShort($dat_fim) ?>" required />
                    <span class="input-group-addon">
                      <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                  </div>
                  <div class="help-block with-errors"></div>
                </div>
              </div>

              <div class="col-md-2">
                <div class="form-group">

                  <label for="inputName" class="control-label">Tipo do Canal</label>
                  <select data-placeholder="Selecione o canal" name="COD_CANALCOM" id="COD_CANALCOM" class="chosen-select-deselect">
                    <option value="0">Todos</option>
                    <?php

                    $sql = "SELECT COD_CANALCOM, DES_CANALCOM FROM CANAL_COMUNICACAO";
                    $arrayQuery = mysqli_query($connAdm->connAdm(), trim($sql));
                    while ($qrCanal = mysqli_fetch_assoc($arrayQuery)) {
                    ?>

                      <option value="<?= $qrCanal['COD_CANALCOM'] ?>"><?= $qrCanal['DES_CANALCOM'] ?></option>

                    <?php
                    }
                    ?>
                  </select>
                  <div class="help-block with-errors"></div>
                  <script type="text/javascript">
                    $("#formulario #COD_CANALCOM").val('<?= $cod_canalcom ?>').trigger("chosen:updated");
                  </script>
                </div>
              </div>

              <!-- <div class="col-md-2">
                  <div class="form-group">
                  
                    <label for="inputName" class="control-label">Campanha</label>
                      <select data-placeholder="Selecione a campanha" name="COD_CAMPANHA" id="COD_CAMPANHA" class="chosen-select-deselect">
                        <option value=""></option>            
                        <?php

                        $sql = "SELECT COD_CAMPANHA, DES_CAMPANHA FROM CAMPANHA 
                              WHERE COD_EMPRESA = $cod_empresa 
                              AND COD_EXT_CAMPANHA IS NOT NULL";
                        $arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);
                        while ($qrCamp = mysqli_fetch_assoc($arrayQuery)) {
                        ?>

                            <option value="<?= $qrCamp['COD_CAMPANHA'] ?>"><?= $qrCamp['DES_CAMPANHA'] ?></option>

                        <?php
                        }
                        ?>                        
                      </select> 
                    <div class="help-block with-errors"></div>
                    <script type="text/javascript">$("#formulario #COD_CAMPANHA").val('<?= $cod_campanha ?>').trigger("chosen:updated");</script>
                  </div>
                </div> -->

              <div class="col-md-2">
                <div class="push20"></div>
                <button type="submit" name="BUS" id="BUS" class="btn btn-primary btn-sm btn-block getBtn"><i class="fa fa-filter" aria-hidden="true"></i>&nbsp; Filtrar</button>
              </div>

            </fieldset>

            <div class="push10"></div>

            <input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?= $cod_empresa ?>">
            <input type="hidden" name="FEZ_AVULSO" id="FEZ_AVULSO" value="N">
            <input type="hidden" name="opcao" id="opcao" value="">
            <input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
            <input type="hidden" name="hHabilitado" id="hHabilitado" value="S">

            <div class="push5"></div>

          </form>

          <div class="push20"></div>

          <div class="col-lg-12">

            <div class="no-more-tables">

              <form name="formLista">

                <table class="table table-bordered table-striped table-hover tableSorter">
                  <thead>
                    <tr>
                      <!-- <th>Cod</th> -->
                      <th>Data</th>
                      <th>Empresa</th>
                      <th>ID</th>
                      <th>Descrição</th>
                      <th>Vl. Unitário</th>
                      <th>Total</th>
                      <th>Quantidade</th>
                      <th>Situação</th>
                      <th>Dt. Nota Fiscal</th>
                    </tr>
                  </thead>
                  <tbody>

                    <?php

                    if ($cod_campanha != 0) {
                      $andCampanha = "AND pedido.COD_CAMPANHA = $cod_campanha";
                    } else {
                      $andCampanha = "";
                    }

                    if ($cod_canalcom != 0) {
                      $andCanal = "AND prod.COD_CANALCOM = $cod_canalcom";
                    } else {
                      $andCanal = "";
                    }

                    $sql = "SELECT pedido.TIP_LANCAMENTO,
                                   pedido.COD_VENDA,
                                   pedido.COD_CAMPANHA,
                                   pedido.DAT_NOTA,
                                   pedido.COD_EMPRESA,
                                   emp.NOM_EMPRESA,
                                   pedido.DAT_CADASTR,
                                   CONCAT(DATE_FORMAT(MID(CONVERT(pedido.COD_ORCAMENTO,CHAR),1,6), '%d/%m/%Y')
                                   ,' ' , MID(pedido.COD_ORCAMENTO,7,2),':',MID(pedido.COD_ORCAMENTO,9,2),':', MID(pedido.COD_ORCAMENTO,11,2)) AS DAT_CADASTRO,
                                   pedido.COD_ORCAMENTO,
                                   canal.DES_CANALCOM,
                                   round(pedido.QTD_PRODUTO,0) AS QTD_PRODUTO,
                                   pedido.VAL_UNITARIO,
                                   round(pedido.VAL_UNITARIO * pedido.QTD_PRODUTO,2) AS VAL_TOTAL ,
                                   (CASE pedido.PAG_CONFIRMACAO
                                 		WHEN 'S' THEN 'Pagamento Confirmado'
                                 		WHEN 'C' THEN 'Cancelado'
                                 		WHEN 'D' THEN 'Devolvido'
                                 		ELSE 'Aguardando Confirmação de Pagamento'
                                 	END) AS DES_SITUACAO,
                                   emp.NOM_FANTASI
                            FROM pedido_marka pedido 
                            INNER JOIN produto_marka prod ON prod.COD_PRODUTO = pedido.COD_PRODUTO 
                            INNER JOIN canal_comunicacao canal ON canal.COD_CANALCOM = prod.COD_CANALCOM 
                            INNER JOIN empresas emp ON emp.COD_EMPRESA = pedido.COD_EMPRESA
                            WHERE pedido.COD_ORCAMENTO > 0
                            AND pedido.COD_ORCAMENTO != 1
                            AND pedido.TIP_LANCAMENTO ='C'
                            AND pedido.DAT_CADASTR BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59'
                            $andCampanha
                            $andCanal
                            ORDER BY pedido.DAT_CADASTR DESC, canal.DES_CANALCOM";

                    //fnEscreve($sql);

                    $arrayQuery = mysqli_query($connAdm->connAdm(), trim($sql));

                    $count = 0;

                    $qtd_contrato = 0;
                    $qtd_envio = 0;
                    $qtd_email = 0;
                    $qtd_sms = 0;
                    $qtd_wpp = 0;

                    while ($qrLista = mysqli_fetch_assoc($arrayQuery)) {

                      $count++;

                      if ($qrLista['TIP_LANCAMENTO'] == 'D') {

                        $qtd_produto = "<span class='text-danger' style='font-size:14px;'><b>-</b></span>&nbsp;" . fnValor($qrLista['QTD_PRODUTO'], 0);
                        $val_unitario = "";
                        $val_total = "";
                        $qtd_envio = $qtd_envio + $qrLista['QTD_PRODUTO'];
                        $msg = "Débito";

                        $sql = "SELECT DES_CAMPANHA FROM CAMPANHA WHERE COD_CAMPANHA = $qrLista[COD_CAMPANHA]";
                        $qrCamp = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa, ''), trim($sql)));
                        $id = $qrCamp['DES_CAMPANHA'];
                      } else {

                        $qtd_produto = "<span class='text-success' style='font-size:14px;'><b>+</b></span>&nbsp;" . fnValor($qrLista['QTD_PRODUTO'], 0);
                        $val_unitario = fnValor($qrLista['VAL_UNITARIO'], 6);
                        $val_total = fnValor($qrLista['VAL_TOTAL'], 2);
                        $qtd_contrato = $qtd_contrato + $qrLista['QTD_PRODUTO'];
                        $msg = $qrLista["DES_SITUACAO"];
                        $id = $qrLista['COD_ORCAMENTO'];

                        if ($id == 1) {
                          $id = "Crédito Avulso";
                          $msg = "Crédito Avulso";
                        }
                      }

                      if (isset($qrLista['DAT_NOTA'])) {
                        $data = fnDataShort($qrLista['DAT_NOTA']);
                      } else {
                        $data = '<small>___/___/_______</small>';
                      }

                      switch ($qrLista['DES_CANALCOM']) {

                        case 'SMS':
                          if ($qrLista['TIP_LANCAMENTO'] == 'D') {
                            $qtd_sms = $qtd_sms - $qrLista['QTD_PRODUTO'];
                          } else {
                            $qtd_sms = $qtd_sms + $qrLista['QTD_PRODUTO'];
                          }
                          break;

                        case 'Whats App':
                          if ($qrLista['TIP_LANCAMENTO'] == 'D') {
                            $qtd_wpp = $qtd_wpp - $qrLista['QTD_PRODUTO'];
                          } else {
                            $qtd_wpp = $qtd_wpp + $qrLista['QTD_PRODUTO'];
                          }
                          break;

                        default:
                          if ($qrLista['TIP_LANCAMENTO'] == 'D') {
                            $qtd_email = $qtd_email - $qrLista['QTD_PRODUTO'];
                          } else {
                            $qtd_email = $qtd_email + $qrLista['QTD_PRODUTO'];
                          }
                          break;
                      }

                      // fnEscreve($qrLista['COD_EMPRESA']);

                    ?>

                      <tr>
                        <td><?= fnDataFull($qrLista['DAT_CADASTR']) ?></td>
                        <td><?= $qrLista['NOM_FANTASI'] ?></td>
                        <td><?= $id ?></td>
                        <td><?= $qrLista['DES_CANALCOM'] ?></td>
                        <td class='text-right'><?= $val_unitario ?></td>
                        <td class='text-right'><?= $val_total ?></td>
                        <td class='text-right'><?= $qtd_produto ?></td>
                        <td><?= $msg ?></td>
                        <td class='text-center'>
                          <a href="#" class="editable-data"
                            data-type='date'
                            data-title='Editar Data'
                            data-pk="<?php echo fnEncode($qrLista['COD_VENDA']); ?>"
                            data-codempresa="<?php echo fnEncode($qrLista['COD_EMPRESA']); ?>"
                            data-name="DAT_NOTA">
                            <?= $data ?>
                          </a>
                        </td>
                      </tr>

                    <?php
                    }
                    ?>

                  </tbody>

                  <tfoot>
                    <tr>
                      <th colspan="100">
                        <a class="btn btn-info btn-sm exportarCSV"> <i class="fa fa-file-excel" aria-hidden="true"></i>&nbsp; Exportar</a>
                      </th>
                    </tr>
                  </tfoot>

                </table>

                <script>
                  $(function() {
                    // $("#QTD_CONTRATO").text("<?= fnValor($qtd_contrato, 0) ?>");
                    // $("#QTD_ENVIO").text("<?= fnValor($qtd_envio, 0) ?>");
                    // $("#QTD_SALDO_EMAIL").text("<?= fnValor($qtd_email, 0) ?>");
                    // $("#QTD_SALDO_SMS").text("<?= fnValor($qtd_sms, 0) ?>");
                    // $("#QTD_SALDO_WPP").text("<?= fnValor($qtd_wpp, 0) ?>");
                  });
                </script>

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

<div class="push20"></div>
<script type="text/javascript" src="js/plugins/datepicker/moment.js"></script>
<script type="text/javascript" src="js/plugins/datepicker/moment-pt-br.js"></script>
<script type="text/javascript" src="js/plugins/datepicker/bootstrap-datetimepicker.min.js"></script>
<link rel="stylesheet" href="css/bootstrap-datetimepicker.css" />

<script type="text/javascript">
  // LOCALIZANDO O CALENDÁRIO DO EDITÁVEL
  $.fn.bdatepicker.dates['pt-br'] = {
    days: ["Domingo", "Segunda", "Terça", "Quarta", "Quinta", "Sexta", "Sábado", "Domingo"],
    daysShort: ["Dom", "Seg", "Ter", "Qua", "Qui", "Sex", "Sab", "Dom"],
    daysMin: ["D", "S", "T", "Q", "Q", "S", "S", "D"],
    months: ["Janeiro", "Fevereiro", "Março", "Abril", "Maio", "Junho", "Julho", "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro"],
    monthsShort: ["Jan", "Fev", "Mar", "Abr", "Mai", "Jun", "Jul", "Ago", "Set", "Out", "Nov", "Dez"],
    today: "Hoje",
    clear: "Limpar",
    weekStart: 0
  };

  $(function() {

    $('.modal').on('hidden.bs.modal', function() {
      // alert('fechou');
      if ($('#FEZ_AVULSO').val() == "S") {
        // alert('S');
        location.reload();
      }
    });

    $('.editable-data').editable({
      viewformat: 'dd/mm/yyyy',
      url: 'ajxstoreComunicacaoData.php',
      ajaxOptions: {
        type: 'post'
      },
      params: function(params) {
        params.codempresa = $(this).data('codempresa');
        return params;
      },
      success: function(data) {
        console.log(data);
      }
    });

    $('.datePicker').datetimepicker({
      format: 'DD/MM/YYYY',
      maxDate: 'now',
    }).on('changeDate', function(e) {
      $(this).datetimepicker('hide');
    });

    $("#DAT_INI_GRP").on("dp.change", function(e) {
      $('#DAT_FIM_GRP').data("DateTimePicker").minDate(e.date);
    });

    $("#DAT_FIM_GRP").on("dp.change", function(e) {
      $('#DAT_INI_GRP').data("DateTimePicker").maxDate(e.date);
    });

    $(".exportarCSV").click(function() {
      $.confirm({
        title: 'Exportação',
        content: '' +
          '<form action="" class="formName">' +
          '<div class="form-group">' +
          '<label>Insira o nome do arquivo:</label>' +
          '<input type="text" placeholder="Nome" class="nome form-control" required />' +
          '</div>' +
          '</form>',
        buttons: {
          formSubmit: {
            text: 'Gerar',
            btnClass: 'btn-blue',
            action: function() {
              var nome = this.$content.find('.nome').val();
              if (!nome) {
                $.alert('Por favor, insira um nome');
                return false;
              }

              $.confirm({
                title: 'Mensagem',
                type: 'green',
                icon: 'fa fa-check-square-o',
                content: function() {
                  var self = this;
                  return $.ajax({
                    url: "ajxComunicacaoCompras.do?opcao=exportar&nomeRel=" + nome + "&id=<?php echo fnEncode($cod_empresa); ?>",
                    data: $('#formulario').serialize(),
                    method: 'POST'
                  }).done(function(response) {
                    self.setContentAppend('<div>Exportação realizada com sucesso.</div>');
                    var fileName = '<?php echo $cod_empresa; ?>_' + nome + '.csv';
                    SaveToDisk('media/excel/' + fileName, fileName);
                    console.log(response);
                  }).fail(function() {
                    self.setContentAppend('<div>Erro ao realizar o procedimento!</div>');
                  });
                },
                buttons: {
                  fechar: function() {
                    //close
                  }
                }
              });
            }
          },
          cancelar: function() {
            //close
          },
        }
      });
    });

  });

  function retornaForm(index) {
    $("#formulario #COD_TIPOCLI").val($("#ret_COD_TIPOCLI_" + index).val());
    $("#formulario #DES_TIPOCLI").val($("#ret_DES_TIPOCLI_" + index).val());
    $("#formulario #COD_EXTERNO").val($("#ret_COD_EXTERNO_" + index).val());
    $('#formulario').validator('validate');
    $("#formulario #hHabilitado").val('S');
  }
</script>