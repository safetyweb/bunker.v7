<?php
//echo fnDebug('true');
//inicialização de variáveis
$hoje = fnFormatDate(date("Y-m-d"));
$dias30 = fnFormatDate(date('Y-m-d', strtotime($dias30 . '- 1 days')));

// definir o numero de itens por pagina
$itens_por_pagina = 50;
$pagina = "1";

$hashLocal = mt_rand();

//busca dados da empresa
$cod_empresa = fnDecode($_GET['id']);

$sql = "SELECT NOM_FANTASI
	FROM empresas where COD_EMPRESA = $cod_empresa ";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $request = md5(implode($_POST));

  if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
    $msgRetorno = 'Essa página já foi utilizada';
    $msgTipo = 'alert-warning';
  } else {
    $_SESSION['last_request'] = $request;

    $cod_empresa = fnLimpaCampoZero($_POST['COD_EMPRESA']);
    $cod_univend = $_POST['COD_UNIVEND'];

    $cod_cliente = fnLimpacampo($_REQUEST['COD_CLIENTE']);
    $nom_cliente = fnLimpacampo($_REQUEST['NOM_CLIENTE']);
    $des_placa = fnLimpacampo($_REQUEST['DES_PLACA']);
    $num_cartao = fnLimpacampo($_REQUEST['NUM_CARTAO']);
    $num_cgcecpf = fnLimpaDoc(fnLimpacampo($_REQUEST['NUM_CGCECPF']));

    $dat_ini = fnDataSql($_POST['DAT_INI']);
    $dat_fim = fnDataSql($_POST['DAT_FIM']);
    $dat_anive_ini = $_POST['DAT_ANIVE_INI'];
		$dat_anive_fim = $_POST['DAT_ANIVE_FIM'];

    if (empty($_REQUEST['LOG_FUNCIONARIO'])) {
      $log_funcionario = 'N';
    } else {
      $log_funcionario = $_REQUEST['LOG_FUNCIONARIO'];
    }
    if (empty($_REQUEST['LOG_INATIVOS'])) {
      $log_inativos = 'N';
    } else {
      $log_inativos = $_REQUEST['LOG_INATIVOS'];
    }


    $opcao = $_REQUEST['opcao'];
    $hHabilitado = $_REQUEST['hHabilitado'];
    $hashForm = $_REQUEST['hashForm'];

    if ($opcao != '') {
    }
  }
}

//fnEscreve($sql);

$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

$nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];

//inicialização das variáveis - default	

if ($log_funcionario == 'S') {
  $check_funcionario = "checked";
} else {
  $check_funcionario = "";
}

if ($log_inativos == "S") {
  $checkInativos = "checked";
  $andInativos = "AND CL.LOG_ESTATUS = 'N'";
} else {
  $checkInativos = "";
  $andInativos = "";
}

//busca revendas do usuário
include "unidadesAutorizadas.php";

//fnEscreve($cod_empresa); 	
//fnEscreve($cod_persona); 	
//fnMostraForm();

?>


<style>
  input[type="search"]::-webkit-search-cancel-button {
    height: 16px;
    width: 16px;
    background: url(images/close-filter.png) no-repeat right center;
    position: relative;
    cursor: pointer;
  }

  input.tableFilter {
    border: 0px;
    background-color: #fff;
  }

  table a:not(.btn),
  .table a:not(.btn) {
    text-decoration: none;
  }

  table a:not(.btn):hover,
  .table a:not(.btn):hover {
    text-decoration: underline;
  }
</style>

<div class="push30"></div>

<div class="row">

  <div class="col-md12 margin-bottom-30">
    <!-- Portlet -->
    <div class="portlet portlet-bordered">
      <div class="portlet-title">
        <div class="caption">
          <i class="glyphicon glyphicon-calendar"></i>
          <span class="text-primary"><?php echo $NomePg; ?> / <?php echo $nom_empresa; ?> </span>
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
              <legend>Filtros</legend>

              <div class="row">

                <div class="col-md-1">
                  <div class="form-group">
                    <label for="inputName" class="control-label">Só Inativos</label>
                    <div class="push5"></div>
                    <label class="switch">
                      <input type="checkbox" name="LOG_INATIVOS" id="LOG_INATIVOS" class="switch" value="S" <?= $checkInativos ?>>
                      <span></span>
                    </label>
                  </div>
                </div>

                <div class="col-md-2">
                  <div class="form-group">
                    <label for="inputName" class="control-label required">Unidade de Atendimento</label>
                    <?php include "unidadesAutorizadasComboMulti.php"; ?>
                  </div>
                </div>

                <div class="col-md-3">
                  <div class="form-group">
                    <label for="inputName" class="control-label">Nome do Cliente</label>
                    <input type="text" class="form-control input-sm" name="NOM_CLIENTE" id="NOM_CLIENTE" maxlength="40" value="<?php echo $nom_cliente; ?>">
                    <div class="help-block with-errors"></div>
                  </div>
                </div>

                <div class="col-md-2">
                  <div class="form-group">
                    <label for="inputName" class="control-label">CPF/CNPJ</label>
                    <input type="text" class="form-control input-sm cpfcnpj" name="NUM_CGCECPF" id="NUM_CGCECPF" value="<?php echo $num_cgcecpf; ?>">
                  </div>
                </div>

                <div class="col-md-4">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="inputName" class="control-label">Data Inicial de Cadastro</label>

                      <div class="input-group date datePicker" id="DAT_CHAMADO_GRP">
                        <input type='text' class="form-control input-sm data" name="DAT_INI" id="DAT_INI" value="<?php echo fnFormatDate($dat_ini); ?>" />
                        <span class="input-group-addon">
                          <span class="glyphicon glyphicon-calendar"></span>
                        </span>
                      </div>
                      <div class="help-block with-errors"></div>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="inputName" class="control-label">Data Final de Cadastro</label>
                      <div class="input-group date datePicker" id="DAT_CHAMADO_GRP">
                        <input type='text' class="form-control input-sm data" name="DAT_FIM" id="DAT_FIM" value="<?php echo fnFormatDate($dat_fim); ?>" />
                        <span class="input-group-addon">
                          <span class="glyphicon glyphicon-calendar"></span>
                        </span>
                      </div>
                      <div class="help-block with-errors"></div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="push10"></div>
              <div class="row">
                <div class="col-md-4">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="inputName" class="control-label">Data Inicial de Aniversário</label>
                      <div class="input-group date datePicker2" id="DAT_FIM_GRP">
                        <input type='text' class="form-control input-sm data" name="DAT_ANIVE_INI" id="DAT_ANIVE_INI" value="<?=$dat_anive_ini?>"/>
                        <span class="input-group-addon">
                          <span class="glyphicon glyphicon-calendar"></span>
                        </span>
                      </div>
                      <div class="help-block with-errors"></div>
                    </div>
                  </div>
                  
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="inputName" class="control-label">Data Final de Aniversário</label>
                      <div class="input-group date datePicker2" id="DAT_FIM_GRP">
                        <input type='text' class="form-control input-sm data" name="DAT_ANIVE_FIM" id="DAT_ANIVE_FIM" value="<?=$dat_anive_fim?>"/>
                        <span class="input-group-addon">
                          <span class="glyphicon glyphicon-calendar"></span>
                        </span>
                      </div>
                      <div class="help-block with-errors"></div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="push10"></div>

              <div class="col-md-2 pull-right">
                <div class="push20"></div>
                <button type="submit" name="ALT" id="ALT" class="btn btn-primary btn-block btn-sm btn-block getBtn"><i class="fa fa-filter" aria-hidden="true"></i>&nbsp; Filtrar</button>
              </div>

        </fieldset>

        <div class="push20"></div>

        <div>
          <div class="row">
            <div class="col-lg-12">

                <table class="table table-bordered table-striped table-hover tablesorter" id="tablista">
                  <thead>
                    <tr>
                      <th class="{sorter:false}" width="40"></th>
                      <th>Código</th>
                      <th>Nome do Dependente</th>
                      <th>Nome do Colaborador</th>
                      <th>Data de Nascimento</th>
                      <th>Idade</th>
                      <th>Loja</th>
                    </tr>
                  </thead>
                  <tbody id="relatorioConteudo">

                    <?php
                    //============================
                    $ARRAY_UNIDADE1 = array(
                      'sql' => "select COD_UNIVEND,cod_empresa,nom_fantasi,NOM_UNIVEND from unidadevenda where cod_empresa=$cod_empresa  and cod_exclusa=0",
                      'cod_empresa' => $cod_empresa,
                      'conntadm' => $connAdm->connAdm(),
                      'IN' => 'N',
                      'nomecampo' => '',
                      'conntemp' => '',
                      'SQLIN' => ""
                    );
                    $ARRAY_UNIDADE = fnUnivend($ARRAY_UNIDADE1);

                    if ($cod_empresa != 0) {

                      if ($nom_cliente != '') {
                        $andNome = 'AND CL.NOM_CLIENTE LIKE "' . $nom_cliente . '%"';
                      } else {
                        $andNome = ' ';
                      }

                      if ($des_placa != '') {
                        $andPlaca = 'AND CL.COD_CLIENTE = (SELECT COD_CLIENTE FROM VEICULOS WHERE DES_PLACA = "' . $des_placa . '")';
                      } else {
                        $andPlaca = ' ';
                      }

                      if ($num_cgcecpf != '') {
                        $andCpf = 'AND CL.NUM_CGCECPF =' . $num_cgcecpf;
                      } else {
                        $andCpf = ' ';
                      }

                      if ($cod_univend != '') {
                        $andLojas = 'AND CL.COD_UNIVEND  IN  (0,' . $lojasSelecionadas . ')';
                      } else {
                        $andLojas = ' ';
                      }

                      if ($log_funcionario == 'S') {
                        $andFuncionarios = " AND CL.LOG_FUNCIONA = 'S' ";
                      } else {
                        $andFuncionarios = "";
                      }

                      if ($dat_ini == "") {
                        $andDatIni = " ";
                      } else {
                        $andDatIni = "AND DATE_FORMAT(CL.DAT_CADASTR, '%Y-%m-%d') >= '$dat_ini' ";
                      }

                      if ($dat_fim == "") {
                        $andDatFim = " ";
                      } else {
                        $andDatFim = "AND DATE_FORMAT(CL.DAT_CADASTR, '%Y-%m-%d') <= '$dat_fim' ";
                      }

                      if($dat_anive_ini != "" || $dat_anive_fim != ""){

                        if($dat_anive_ini != ""){
                          $dat_anive_ini = "RIGHT(STR_TO_DATE('".$dat_anive_ini."', '%d/%m/%Y'),5)";
                        }else{
                          $dat_anive_ini = "RIGHT(STR_TO_DATE('01/01', '%d/%m/%Y'),5)";
                        }

                        if($dat_anive_fim != ""){
                          $dat_anive_fim = "RIGHT(STR_TO_DATE('".$dat_anive_fim."', '%d/%m/%Y'),5)";
                        }else{
                          $dat_anive_fim = "RIGHT(STR_TO_DATE('31/12', '%d/%m/%Y'),5)";
                        }

                        $andAnive = "AND RIGHT(STR_TO_DATE(CL.DAT_NASCIME, '%d/%m/%Y'),5) BETWEEN $dat_anive_ini AND $dat_anive_fim";
                      }else{
                        $andAnive = "";
                      }

                      //paginação
                      $sql = "SELECT COUNT(CL.COD_CLIENTE) AS CONTADOR FROM  " . connTemp($cod_empresa, 'true') . ".CLIENTES CL
                                  WHERE CL.COD_EMPRESA = " . $cod_empresa . " 
								  AND CL.LOG_TITULAR = 'N'
                                  AND CL.COD_UNIVEND  IN  ($lojasSelecionadas)
																	" . $andCodigo . "
																	" . $andNome . "
																	" . $andCpf . "
																	" . $andFuncionarios . "
																	" . $andInativos . "
																	ORDER BY NOM_CLIENTE ";

                      $retorno = mysqli_query(connTemp($cod_empresa, ''), $sql);
                      $total_itens_por_pagina = mysqli_fetch_assoc($retorno);

                      $numPaginas = ceil($total_itens_por_pagina['CONTADOR'] / $itens_por_pagina);

                      //variavel para calcular o início da visualização com base na página atual
                      $inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;

                      //lista de clientes
                      $sql = "SELECT CL.*,
								  (SELECT NOM_CLIENTE FROM CLIENTES WHERE COD_CLIENTE=CL.COD_TITULAR) AS NOM_TITULAR,
								  (SELECT COD_CLIENTE FROM CLIENTES WHERE COD_CLIENTE=CL.COD_TITULAR) AS COD_TITULAR
                                  FROM CLIENTES CL
                                  WHERE CL.COD_EMPRESA = $cod_empresa AND CL.LOG_TITULAR = 'N'
                                  AND (CL.COD_UNIVEND IN ($lojasSelecionadas) OR CL.COD_UNIVEND IS NULL OR CL.COD_UNIVEND = 0)
                                    $andNome
                                    $andCpf
                                    $andFuncionarios
                                    $andInativos
                                    $andDatIni
                                    $andDatFim
                                    $andAnive
                                  ORDER BY CL.NOM_CLIENTE LIMIT $inicio,$itens_por_pagina";

                      $arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);
                       //echo($sql);
                      //echo "___".$sql."___";
                      $count = 0;
                      while ($qrListaEmpresas = mysqli_fetch_assoc($arrayQuery)) {

                        $log_funciona = $qrListaEmpresas['LOG_FUNCIONA'];
                        if ($log_funciona == "S") {
                          $mostraCracha = '<i class="fa fa-address-card" aria-hidden="true"></i>';
                        } else {
                          $mostraCracha = "";
                        }

                        if ($qrListaEmpresas['COD_UNIVEND'] != 0) {
                          $NOM_ARRAY_UNIDADE = (array_search($qrListaEmpresas['COD_UNIVEND'], array_column($ARRAY_UNIDADE, 'COD_UNIVEND')));
                          $unidade = $ARRAY_UNIDADE[$NOM_ARRAY_UNIDADE]['nom_fantasi'];
                        } else {
                          $unidade = "Sem unidade";
                        }

                        $log_estatus = $qrListaEmpresas['LOG_ESTATUS'];
                        if ($log_estatus == "S") {
                          $mostraStatus = '<i class="fal fa-check" aria-hidden="true"></i>';
                        } else {
                          $mostraStatus = '<i class="fal fa-times text-warning" aria-hidden="true"></i>';
                        }


                        $count++;

                        echo "
                                <tr>
                                  <td class='text-center'><input type='radio' name='radio1' onclick='retornaForm(" . $count . ")'></th>
                                  <td><small>" . $qrListaEmpresas['COD_CLIENTE'] . "</small></td>
                                  <td><small><a href='action.do?mod=" . fnEncode(1688) . "&id=" . fnEncode($cod_empresa) . "&idC=" . fnEncode($qrListaEmpresas['COD_CLIENTE']) . "' target='_blank'>" . $qrListaEmpresas['NOM_CLIENTE'] . "&nbsp;" . $mostraCracha . "</a></small></td>
                                  <td><small><a href='action.do?mod=" . fnEncode(1688) . "&id=" . fnEncode($cod_empresa) . "&idC=" . fnEncode($qrListaEmpresas['COD_TITULAR']) . "' target='_blank'>" . $qrListaEmpresas['NOM_TITULAR'] . "&nbsp;" . $mostraCracha . "</a></small></td>
                                  <td><small>" . $qrListaEmpresas['DAT_NASCIME'] . "</small></td>
                                  <td><small>" . $qrListaEmpresas['IDADE'] . "</small></td>
                                  <td><small>" . $unidade . "</small></td>
                                </tr>
                                <input type='hidden' id='ret_COD_CLIENTE_" . $count . "' value='" . fnEncode($qrListaEmpresas['COD_CLIENTE']) . "'>
                                <input type='hidden' id='ret_COD_EMPRESA_" . $count . "' value='" . fnEncode($cod_empresa) . "'>
                                ";
                      }
                    }
                    ?>

                  </tbody>
                  <?php if ($cod_empresa != 0) { ?>
                    <tfoot>
                      <tr>
                        <th colspan="100">
                          <a class="btn btn-info btn-sm exportarCSV"> <i class="fa fa-file-excel" aria-hidden="true"></i>&nbsp; Exportar</a>
                        </th>
                      </tr>
                      <tr>
                        <th class="" colspan="100">
                          <center>
                            <ul id="paginacao" class="pagination-sm"></ul>
                          </center>
                        </th>
                      </tr>
                    </tfoot>
                  <?php }  //fnEscreve($cod_empresa);   
                  ?>

                </table>


                <div class="push"></div>

                <input type="hidden" name="LOJAS" id="LOJAS" value="<?php echo $lojasSelecionadas; ?>" />
                <input type="hidden" class="form-control input-sm" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa; ?>">
                <input type="hidden" name="opcao" id="opcao" value="">
                <input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
                <input type="hidden" name="hHabilitado" id="hHabilitado" value="S">
                <input type="hidden" name="AND_ANIVE" id="AND_ANIVE" value="<?= $andAnive; ?>" />
                <input type="hidden" name="AND_ESTATUS" id="AND_ESTATUS" value="<?= $andEstatus; ?>" />

                </form>

              </div>

            </div>
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

<script type="text/javascript" src="js/plugins/datepicker/moment.js"></script>
<script type="text/javascript" src="js/plugins/datepicker/moment-pt-br.js"></script>
<script type="text/javascript" src="js/plugins/datepicker/bootstrap-datetimepicker.min.js"></script>
<link rel="stylesheet" href="css/bootstrap-datetimepicker.css" />

<script type="text/javascript">
  $(document).ready(function() {

    var numPaginas = <?php echo $numPaginas; ?>;
    if (numPaginas != 0) {
      carregarPaginacao(numPaginas);
    }

    $('.datePicker2').datetimepicker({
				 viewMode: "months",
				 format: 'DD/MM'
				}).on('changeDate', function(e){
					$(this).datetimepicker('hide');
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

    //chosen obrigatório
    $.fn.validator.Constructor.INPUT_SELECTOR = ':input:not([type="submit"], button):enabled, .requiredChk';
    $('#formulario').validator();

    //table sorter
    $(function() {
      var tabelaFiltro = $('table.tablesorter')
      tabelaFiltro.find("tbody > tr").find("td:eq(1)").mousedown(function() {
        $(this).prev().find(":checkbox").click()
      });
      $("#filter").keyup(function() {
        $.uiTableFilter(tabelaFiltro, this.value);
      })
      $('#formLista').submit(function() {
        tabelaFiltro.find("tbody > tr:visible > td:eq(1)").mousedown();
        return false;
      }).focus();
    });

    //pesquisa table sorter
    $('.filter-all').on('input', function(e) {
      if ('' == this.value) {
        var lista = $("#filter").find("ul").find("li");
        filtrar(lista, "");
      }
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
                icon: 'fa fa-check-square',
                content: function() {
                  var self = this;
                  return $.ajax({
                    url: "relatorios/ajxRelDependentesGeral.do?opcao=exportar&nomeRel=" + nome + "&id=<?php echo fnEncode($cod_empresa); ?>",
                    data: $('#formulario').serialize(),
                    method: 'POST'
                  }).done(function(response) {
                    self.setContentAppend('<div>Exportação realizada com sucesso.</div>');
                    var fileName = '<?php echo $cod_empresa; ?>_' + nome + '.csv';
                    SaveToDisk('media/excel/' + fileName, fileName);
                     console.log(response);
                  }).fail(function(response) {
                    self.setContentAppend('<div>Erro ao realizar o procedimento!</div>');
                    console.log(response.responseText);
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

    $("body").delegate('input.placa', 'paste', function(e) {
      $(this).unmask();
    });
    $("body").delegate('input.placa', 'input', function(e) {
      $('input.placa').mask(MercoSulMaskBehavior, mercoSulOptions);
    });

  });

  var MercoSulMaskBehavior = function(val) {
      var myMask = 'SSS0A00';
      var mercosul = /([A-Za-z]{3}[0-9]{1}[A-Za-z]{1})/;
      var normal = /([A-Za-z]{3}[0-9]{2})/;
      var replaced = val.replace(/[^\w]/g, '');
      if (normal.exec(replaced)) {
        myMask = 'SSS-0000';
      } else if (mercosul.exec(replaced)) {
        myMask = 'SSS0A00';
      }
      return myMask;
    },

    mercoSulOptions = {
      onKeyPress: function(val, e, field, options) {
        field.mask(MercoSulMaskBehavior.apply({}, arguments), options);
      }
    };

  $(document).on('change', '#COD_EMPRESA', function() {
    $("#dKey").val($("#COD_EMPRESA").val());
  });


  function reloadPage(idPage) {
    $.ajax({
      type: "POST",
      url: "relatorios/ajxRelDependentesGeral.php.do?opcao=paginar&id=<?php echo fnEncode($cod_empresa); ?>&idPage=" + idPage + "&itens_por_pagina=<?php echo $itens_por_pagina; ?>",
      data: $('#formulario').serialize(),
      beforeSend: function() {
        $('#relatorioConteudo').html('<div class="loading" style="width: 100%;"></div>');
      },
      success: function(data) {
        $("#relatorioConteudo").html(data);
        console.log(data);
      },
      error: function() {
        $('#relatorioConteudo').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> itens_por_pagina não encontrados...</p>');
      }
    });
  }

  function page(index) {

    $("#pagina").val(index);
    $("#formulario")[0].submit();
    //alert(index);	

  }
</script>