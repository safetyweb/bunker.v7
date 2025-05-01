<?php
//echo fnDebug('true');
//inicialização de variáveis
$hoje = fnFormatDate(date("Y-m-d"));
$hojeSql = date("Y-m-d");
//$hoje = fnFormatDate(date('Y-m-d', strtotime($hoje. '- 1 days')));
$dias30 = fnFormatDate(date('Y-m-d', strtotime($dias30. '- 30 days')));
//$dias30 = fnFormatDate(date('Y-m-d', strtotime($dias30. '- 2 days')));

// definir o numero de itens por pagina
$itens_por_pagina = 50;
$pagina = "1";

$hashLocal = mt_rand();

//busca dados da empresa
$cod_empresa = fnDecode($_GET['id']);
$cod_pesquisa = fnDecode($_GET['idP']);

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

//inicialização das variáveis - default 
if (strlen($dat_ini) == 0 || $dat_ini == "1969-12-31" ){
  $dat_ini = fnDataSql($dias30); 
} 
if (strlen($dat_fim ) == 0 || $dat_fim == "1969-12-31"){
  $dat_fim = fnDataSql($hoje); 
} 
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

  table a:not(.btn), .table a:not(.btn) {
    text-decoration: none;
  }
  table a:not(.btn):hover, .table a:not(.btn):hover {
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

                <div class="col-md-3">
                  <div class="form-group">
                    <label for="inputName" class="control-label required">Unidade de Atendimento</label>
                    <?php include "unidadesAutorizadasComboMulti.php"; ?>
                  </div>
                </div>

                <div class="col-md-2">
                  <div class="form-group">
                    <label for="inputName" class="control-label">CPF/CNPJ</label>
                    <input type="text" class="form-control input-sm cpfcnpj"  name="NUM_CGCECPF" id="NUM_CGCECPF" value="<?php echo $num_cgcecpf; ?>">
                  </div>
                </div>

                <div class="col-md-2">
                  <div class="form-group">
                    <label for="inputName" class="control-label">Nome do Cliente</label>
                    <input type="text" class="form-control input-sm" name="NOM_CLIENTE" id="NOM_CLIENTE" maxlength="40" value="<?php echo $nom_cliente; ?>">
                    <div class="help-block with-errors"></div>
                  </div>
                </div>

                <div class="col-md-3">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="inputName" class="control-label">Data Inicial de Cadastro</label>

                        <div class="input-group date datePicker" id="DAT_CHAMADO_GRP">
                          <input type='text' class="form-control input-sm data" name="DAT_INI" id="DAT_INI" value="<?php echo fnDataShort($dat_ini); ?>"/>
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
                          <input type='text' class="form-control input-sm data" name="DAT_FIM" id="DAT_FIM" value="<?php echo fnDataShort($dat_fim); ?>"/>
                          <span class="input-group-addon">
                            <span class="glyphicon glyphicon-calendar"></span>
                          </span>
                        </div>
                        <div class="help-block with-errors"></div>
                      </div>
                    </div>
          
                </div>

                <div class="col-md-2">
                  <div class="push20"></div>
                  <button type="submit" name="ALT" id="ALT" class="btn btn-primary btn-block btn-sm btn-block getBtn"><i class="fa fa-filter" aria-hidden="true"></i>&nbsp; Filtrar</button>
                </div>

              </div>

            </fieldset>

            <div class="push20"></div>

            <div>
              <div class="row">
                <div class="col-lg-12">

                  <div class="no-more-tables">


                    <table class="table table-bordered table-striped table-hover tablesorter" id="tablista">
                      <!--
                      <thead> 
                            <tr>
                              <td class="bg-primary" colspan="100"><input type="search" name="filter" id="filter" class="input-sm tableFilter text-primary pull-right" style="height: 25px;" value=""></td>
                            </tr>
                      </thead>
                      -->
                      <thead>
                        <tr>
                          <th>Código</th>
                          <th>Nome do Cliente</th>
                          <th>Data de Cadastro</th>
                          <th>Loja</th>
                          <th>Pesquisa</th>
                          <th>Cartão</th>
                          <th>CPF</th>
                          <th>Telefone</th>
                          <th>Celular</th>
                          <th>e-Mail</th>
                        </tr>
                      </thead>

                      <tbody id="relatorioConteudo">

                        <?php

                        if ($cod_empresa != 0) {

                          if ($nom_cliente != '') {
                            $andNome = 'AND CL.NOM_CLIENTE LIKE "' . $nom_cliente . '%"';
                          } else {
                            $andNome = ' ';
                          }

                          if ($num_cartao != '') {
                            $andCartao = 'AND CL.NUM_CARTAO=' . $num_cartao;
                          } else {
                            $andCartao = ' ';
                          }

                          if ($num_cgcecpf != '') {
                            $andCpf = 'AND CL.NUM_CGCECPF =' . $num_cgcecpf;
                          } else {
                            $andCpf = ' ';
                          }


                          //paginação
                          $sql = "SELECT COUNT(CL.COD_CLIENTE) AS CONTADOR FROM  " . connTemp($cod_empresa, 'true') . ".CLIENTES CL
                                  WHERE CL.COD_EMPRESA = " . $cod_empresa . "
                                  AND CL.COD_UNIVEND  IN  ($lojasSelecionadas)
                                  AND CL.LOG_CADTOTEM = 'S'
                                  AND CL.COD_CADPESQ != 0
																	" . $andCodigo . "
																	" . $andNome . "
																	" . $andCartao . "
																	" . $andCpf . "
																	ORDER BY NOM_CLIENTE ";

                          $retorno = mysqli_query(connTemp($cod_empresa, ''), $sql);
                          $total_itens_por_pagina = mysqli_fetch_assoc($retorno);

                          $numPaginas = ceil($total_itens_por_pagina['CONTADOR'] / $itens_por_pagina);

                          //variavel para calcular o início da visualização com base na página atual
                          $inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;


                          //lista de clientes
                          $sql = "SELECT CL.*, PQ.DES_PESQUISA, UV.NOM_FANTASI
                                  FROM CLIENTES CL
                                  INNER JOIN PESQUISA PQ ON PQ.COD_PESQUISA = CL.COD_CADPESQ
                                  INNER JOIN WEBTOOLS.UNIDADEVENDA UV ON UV.COD_UNIVEND = CL.COD_UNIVEND
                                  WHERE CL.COD_EMPRESA = $cod_empresa
                                  AND (CL.COD_UNIVEND IN ($lojasSelecionadas) OR CL.COD_UNIVEND IS NULL OR CL.COD_UNIVEND = 0)
                                  AND CL.LOG_CADTOTEM = 'S'
                                  AND CL.COD_CADPESQ != 0
                                    $andNome
                                    $andCartao
                                    $andCpf
                                    $andDatIni
                                    $andDatFim
                                  ORDER BY CL.NOM_CLIENTE 
                                  LIMIT $inicio,$itens_por_pagina";

                          $arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);
                          // fnEscreve($sql);
                          //  echo "___".$sql."___";
                          $count = 0;

                          while ($qrListaEmpresas = mysqli_fetch_assoc($arrayQuery)) {
                            $count++;

                            echo"
                                <tr>
                                  <td><small>" . $qrListaEmpresas['COD_CLIENTE'] . "</small></td>
                                  <td><small><a href='action.do?mod=" . fnEncode(1024) . "&id=" . fnEncode($cod_empresa) . "&idC=" . fnEncode($qrListaEmpresas['COD_CLIENTE']) . "' target='_blank'>" . $qrListaEmpresas['NOM_CLIENTE'] . "&nbsp;" . $mostraCracha . "</a></small></td>
                                  <td><small>" . fnDataFull($qrListaEmpresas['DAT_CADASTR']) . "</small></td>
                                  <td><small>" . $qrListaEmpresas['NOM_FANTASI'] . "</small></td>
                                  <td><small>" . $qrListaEmpresas['DES_PESQUISA'] . "</small></td>
                                  <td><small>" . $qrListaEmpresas['NUM_CARTAO'] . "</small></td>
                                  <td><small>" . $qrListaEmpresas['NUM_CGCECPF'] . "</small></td>
                                  <td><small>" . $qrListaEmpresas['NUM_TELEFON'] . "</small></td>
                                  <td><small>" . $qrListaEmpresas['NUM_CELULAR'] . "</small></td>
                                  <td><small>" . strtolower($qrListaEmpresas['DES_EMAILUS']) . "</small></td>
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
                              <a class="btn btn-info btn-sm exportarCSV" id="novosLojas"> <i class="fa fa-file-excel" aria-hidden="true"></i>&nbsp; Exportar</a>
                              <!-- <a class="btn btn-info btn-sm exportarCSV" id="novosClientes"> <i class="fa fa-file-excel" aria-hidden="true"></i>&nbsp; Exportar Novos Cad. (clientes)</a> -->
                            </th>
                          </tr>
                          <tr>
                            <th class="" colspan="100">
                        <center><ul id="paginacao" class="pagination-sm"></ul></center>
                        </th>
                        </tr>
                        </tfoot>
                      <?php }  //fnEscreve($cod_empresa);   ?>

                    </table>


                    <div class="push"></div>

                    <input type="hidden" name="LOJAS" id="LOJAS" value="<?php echo $lojasSelecionadas; ?>" />
                    <input type="hidden" name="COD_PESQUISA" id="COD_PESQUISA" value="<?=$cod_pesquisa?>">
                    <input type="hidden" name="opcao" id="opcao" value="">
                    <input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />							
                    <input type="hidden" name="hHabilitado" id="hHabilitado" value="S">														

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


  $(document).ready(function () {

    var numPaginas = <?php echo $numPaginas; ?>;
    if (numPaginas != 0) {
      carregarPaginacao(numPaginas);
    }

    $('.datePicker').datetimepicker({
      format: 'DD/MM/YYYY',
      maxDate: 'now',
    }).on('changeDate', function (e) {
      $(this).datetimepicker('hide');
    });

    $("#DAT_INI_GRP").on("dp.change", function (e) {
      $('#DAT_FIM_GRP').data("DateTimePicker").minDate(e.date);
    });

    $("#DAT_FIM_GRP").on("dp.change", function (e) {
      $('#DAT_INI_GRP').data("DateTimePicker").maxDate(e.date);
    });

    //chosen obrigatório
    $.fn.validator.Constructor.INPUT_SELECTOR = ':input:not([type="submit"], button):enabled, .requiredChk';
    $('#formulario').validator();

    //table sorter
    $(function () {
      var tabelaFiltro = $('table.tablesorter')
      tabelaFiltro.find("tbody > tr").find("td:eq(1)").mousedown(function () {
        $(this).prev().find(":checkbox").click()
      });
      $("#filter").keyup(function () {
        $.uiTableFilter(tabelaFiltro, this.value);
      })
      $('#formLista').submit(function () {
        tabelaFiltro.find("tbody > tr:visible > td:eq(1)").mousedown();
        return false;
      }).focus();
    });

    //pesquisa table sorter
    $('.filter-all').on('input', function (e) {
      if ('' == this.value) {
        var lista = $("#filter").find("ul").find("li");
        filtrar(lista, "");
      }
    });

    $(".exportarCSV").click(function() {
        var tipo = $(this).attr("id");
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
              action: function () {
                var nome = this.$content.find('.nome').val();
                if(!nome){
                  $.alert('Por favor, insira um nome');
                  return false;
                }
                
                $.confirm({
                  title: 'Mensagem',
                  type: 'green',
                  icon: 'fa fa-check-square-o',
                  content: function(){
                    var self = this;
                    return $.ajax({
                      url: "relatorios/ajxRelClientesGeralNps.do?opcao=exportar&nomeRel="+nome+"&id=<?php echo fnEncode($cod_empresa); ?>&tipo="+tipo,
                      data: $('#formulario').serialize(),
                      method: 'POST'
                    }).done(function (response) {
                      self.setContentAppend('<div>Exportação realizada com sucesso.</div>');
                      var fileName = '<?php echo $cod_empresa; ?>_' + nome + '.csv';
                      SaveToDisk('media/excel/' + fileName, fileName);
                      console.log(response);
                    }).fail(function(){
                      self.setContentAppend('<div>Erro ao realizar o procedimento!</div>');
                    });
                  },              
                  buttons: {
                    fechar: function () {
                      //close
                    }                 
                  }
                });               
              }
            },
            cancelar: function () {
              //close
            },
          }
        });       
      });

    $("body").delegate('input.placa','paste', function(e) {
        $(this).unmask();
      });
    $("body").delegate('input.placa','input', function(e) {
        $('input.placa').mask(MercoSulMaskBehavior, mercoSulOptions);
    });

  });

  var MercoSulMaskBehavior = function (val) {
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

  $(document).on('change', '#COD_EMPRESA', function () {
    $("#dKey").val($("#COD_EMPRESA").val());
  });


  function reloadPage(idPage) {
    $.ajax({
      type: "POST",
      url: "relatorios/ajxRelClientesGeralNps.do?opcao=paginar&id=<?php echo fnEncode($cod_empresa); ?>&idPage=" + idPage + "&itens_por_pagina=<?php echo $itens_por_pagina; ?>",
      data: $('#formulario').serialize(),
      beforeSend: function () {
        $('#relatorioConteudo').html('<div class="loading" style="width: 100%;"></div>');
      },
      success: function (data) {
        $("#relatorioConteudo").html(data);
        console.log(data);
      },
      error: function () {
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
