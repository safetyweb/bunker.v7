<?php
if ($_SESSION['SYS_COD_USUARIO'] == 127937) {
  echo fnDebug('true');
  ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);
  error_reporting(E_ALL);
}
$hoje = "";
$dias30 = "";
$hashLocal = "";
$msgRetorno = "";
$msgTipo = "";
$nom_entidad = "";
$nom_respon = "";
$cod_filtro = "";
$cod_grupoent = "";
$hHabilitado = "";
$hashForm = "";
$cod_usucada = "";
$cod_persona = "";
$arrayQuery = [];
$qrFiltro = "";
$andEntidad = "";
$andRegitra = "";
$andRespon = "";
$retorno = "";
$totalitens_por_pagina = 0;
$inicio = "";
$qrBuscaModulos = "";
$content = "";

$hoje = fnFormatDate(date("Y-m-d"));
$dias30 = fnFormatDate(date('Y-m-d', strtotime($dias30 . '- 1 days')));

// definir o numero de itens por pagina
$itens_por_pagina = 50;
$pagina = "1";

$hashLocal = mt_rand();

//busca dados da empresa
$cod_empresa = fnDecode(@$_GET['id']);

$sql = "SELECT NOM_FANTASI
	FROM empresas where COD_EMPRESA = $cod_empresa ";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $request = md5(serialize($_POST));

  if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
    $msgRetorno = 'Essa página já foi utilizada';
    $msgTipo = 'alert-warning';
  } else {
    $_SESSION['last_request'] = $request;

    $cod_empresa = fnLimpaCampoZero(@$_POST['COD_EMPRESA']);
    $nom_entidad = fnLimpacampo(@$_REQUEST['DES_GRUPOENT']);
    $nom_respon = fnLimpacampo(@$_REQUEST['NOM_RESPON']);
    $cod_filtro = fnLimpacampo(@$_REQUEST['COD_REGITRA']);
    $cod_grupoent = fnLimpacampo(@$_REQUEST['COD_GRUPOENT']);

    $opcao = @$_REQUEST['opcao'];
    $hHabilitado = @$_REQUEST['hHabilitado'];
    $hashForm = @$_REQUEST['hashForm'];

    if ($opcao != '') {
    }
  }
}

$cod_usucada = $_SESSION['SYS_COD_USUARIO'];


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
          <i class="fal fa-terminal"></i>
          <span class="text-primary"><?php echo $NomePg; ?></span>
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

                <div class="col-md-2">
                  <div class="form-group">
                    <label for="inputName" class="control-label">Código</label>
                    <input type="text" class="form-control input-sm leitura" readonly="readonly" name="COD_GRUPOENT" id="COD_GRUPOENT" value="">
                  </div>
                </div>

                <div class="col-md-3">
                  <div class="form-group">
                    <label for="inputName" class="control-label">Nome do Distrito</label>
                    <input type="text" class="form-control input-sm" name="DES_GRUPOENT" id="DES_GRUPOENT" maxlength="100" value="<?= $nom_entidad ?>">
                  </div>
                  <div class="help-block with-errors"></div>
                </div>
                <div class="col-md-2">
                  <div class="form-group">
                    <label for="inputName" class="control-label">Agrupador</label>
                    <select data-placeholder="Selecione um estado civil" name="COD_REGITRA" id="COD_REGITRA" class="chosen-select-deselect">
                      <option value="">&nbsp;</option>
                      <?php
                      $sql = "SELECT COD_FILTRO, DES_FILTRO FROM FILTROS_CLIENTE
                                                    WHERE COD_TPFILTRO = 28
                                                    AND COD_EMPRESA = $cod_empresa
                                                    ORDER BY DES_FILTRO";
                      $arrayQuery =  mysqli_query(connTemp($cod_empresa, ''), $sql);

                      while ($qrFiltro = mysqli_fetch_assoc($arrayQuery)) {
                        echo "
                                                  <option value='" . $qrFiltro['COD_FILTRO'] . "'>" . $qrFiltro['DES_FILTRO'] . "</option> 
                                                ";
                      }
                      ?>
                    </select>
                    <div class="help-block with-errors"></div>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group">
                    <label for="inputName" class="control-label">Nome do Responsável</label>
                    <input type="text" class="form-control input-sm" name="NOM_RESPON" id="NOM_RESPON" maxlength="60" value="<?= $nom_respon ?>">
                  </div>
                  <div class="help-block with-errors"></div>
                </div>

                <div class="col-md-1">
                  <label>&nbsp;</label>
                  <button type="submit" name="ALT" id="ALT" class="btn btn-primary btn-block btn-sm getBtn"><i class="fal fa-filter" aria-hidden="true"></i>&nbsp; Filtrar</button>
                </div>
                <div class="col-md-1">
                  <label>&nbsp;</label>
                  <button type="reset" class="btn btn-default btn-block btn-sm getBtn"><i class="fal fa-eraser" aria-hidden="true"></i>&nbsp; Apagar</button>
                </div>

              </div>
            </fieldset>
            <input type="hidden" class="form-control input-sm" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa; ?>">
            <input type="hidden" name="opcao" id="opcao" value="">
            <input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
            <input type="hidden" name="hHabilitado" id="hHabilitado" value="S">
            <input type="hidden" name="REFRESH_LISTA" id="REFRESH_LISTA" value="N">

          </form>
        </div>

        <div class="push20"></div>

        <div>
          <div class="row">
            <div class="col-lg-12">

              <div class="no-more-tables">

                <form name="formLista">


                  <table class="table table-bordered table-striped table-hover tablesorter" id="tablista">
                    <thead>
                      <tr>
                        <th width="40" class="{sorter:false}"></th>
                        <th>Código</th>
                        <!-- <th>Empresa</th> -->
                        <th>Nome do Distrito</th>
                        <th>Agrupador</th>
                        <th>Responsável</th>
                        <th class="{sorter:false}"></th>
                      </tr>
                    </thead>
                    <tbody id="relatorioConteudo">

                      <?php

                      if ($nom_entidad != '') {
                        $andEntidad = "AND A.DES_GRUPOENT LIKE '%$nom_entidad%'";
                      } else {
                        $andEntidad = "";
                      }

                      if ($cod_filtro != '') {
                        $andRegitra = "AND C.COD_FILTRO = '$cod_filtro'";
                      } else {
                        $andRegitra = "";
                      }

                      if ($nom_respon != '') {
                        $andRespon = "AND B.NOM_RESPON LIKE '%$nom_respon%'";
                      } else {
                        $andRespon = "";
                      }

                      //============================
                      $sql = "SELECT 1
                                FROM  entidade_grupo A
                                INNER JOIN ENTIDADE B ON B.COD_GRUPOENT=A.COD_GRUPOENT AND B.COD_EMPRESA=A.COD_EMPRESA 
                                INNER JOIN FILTROS_CLIENTE C ON C.COD_FILTRO= A.COD_REGITRA AND C.COD_EMPRESA=A.COD_EMPRESA
                                WHERE A.COD_EMPRESA=$cod_empresa
                                $andEntidad
                                $andRegitra
                                $andRespon
                                GROUP BY A.DES_GRUPOENT 
                                ORDER BY DES_GRUPOENT";
                      //echo $sql;    
                      $retorno = mysqli_query(connTemp($cod_empresa, ''), $sql);
                      $totalitens_por_pagina = mysqli_num_rows($retorno);
                      $numPaginas = ceil($totalitens_por_pagina / $itens_por_pagina);

                      //echo($numPaginas);

                      $inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;

                      $sql = "SELECT A.COD_GRUPOENT,
                                A.DES_GRUPOENT,
                                C.DES_FILTRO,
                                C.COD_FILTRO,
                                B.NOM_RESPON
                                FROM  entidade_grupo A
                                INNER JOIN ENTIDADE B ON B.COD_GRUPOENT=A.COD_GRUPOENT AND B.COD_EMPRESA=A.COD_EMPRESA 
                                INNER JOIN FILTROS_CLIENTE C ON C.COD_FILTRO= A.COD_REGITRA AND C.COD_EMPRESA=A.COD_EMPRESA
                                WHERE A.COD_EMPRESA=$cod_empresa
                                $andEntidad
                                $andRegitra
                                $andRespon
                                GROUP BY A.DES_GRUPOENT 
                                ORDER BY DES_GRUPOENT
                                limit $inicio,$itens_por_pagina";


                      $arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

                      //echo($sql);

                      $count = 0;
                      while ($qrBuscaModulos = mysqli_fetch_assoc($arrayQuery)) {


                        $count++;
                      ?>
                        <tr>
                          <td><input type='radio' name='radio1' onclick='retornaForm("<?= $count ?>")'></th>
                          <td><?= $qrBuscaModulos['COD_GRUPOENT'] ?></td>
                          <td><?= $qrBuscaModulos['DES_GRUPOENT'] ?></td>
                          <td><?= $qrBuscaModulos['DES_FILTRO'] ?></td>
                          <td><?= $qrBuscaModulos['NOM_RESPON'] ?></td>
                          <td class="text-center">
                            <small>
                              <div class="btn-group dropdown dropleft">
                                <button type="button" class="btn btn-info btn-xs dropdown-toggle transparency" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                  ações &nbsp;
                                  <span class="fas fa-caret-down"></span>
                                </button>
                                <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu">
                                  <li><a href="javascript:void(0)" class="addBox" data-title="Alteração do Responsável" data-url="action.do?mod=<?php echo fnEncode(1779) ?>&id=<?php echo fnEncode($cod_empresa) ?>&idg=<?php echo fnEncode($qrBuscaModulos['COD_GRUPOENT']) ?>&pop=true">Alterar</a></li>
                                  <!-- <li><a tabindex="-1" href="#">Separated link</a></li> -->
                                </ul>
                              </div>
                            </small>
                          </td>
                        </tr>
                        <input type='hidden' id='ret_COD_GRUPOENT_<?= $count ?>' value='<?= $qrBuscaModulos['COD_GRUPOENT'] ?>'>
                        <input type='hidden' id='ret_DES_GRUPOENT_<?= $count ?>' value='<?= $qrBuscaModulos['DES_GRUPOENT'] ?>'>
                        <input type='hidden' id='ret_COD_EMPRESA_<?= $count ?>' value='<?= $qrBuscaModulos['COD_EMPRESA'] ?>'>
                        <input type='hidden' id='ret_COD_FILTRO_<?= $count ?>' value='<?= $qrBuscaModulos['COD_FILTRO'] ?>'>
                        <input type='hidden' id='ret_NOM_RESPON_<?= $count ?>' value='<?= $qrBuscaModulos['NOM_RESPON'] ?>'>
                      <?php
                      }
                      ?>

                    </tbody>

                    <tfoot>
                      <tr>
                        <th colspan="100">
                          <a class="btn btn-info btn-sm exportarCSV"> <i class="fal fa-file-excel" aria-hidden="true"></i>&nbsp; Exportar</a>
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


                  </table>


                  <div class="push"></div>

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
  $(document).ready(function() {

    var numPaginas = <?php echo $numPaginas; ?>;
    if (numPaginas != 0) {
      carregarPaginacao(numPaginas);
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
                  url: "relatorios/ajxRelAlteraResponsavel.do?opcao=exportar&nomeRel=" + nome + "&id=<?php echo fnEncode($cod_empresa); ?>",
                  data: $('#formulario').serialize(),
                  method: 'POST'
                }).done(function(response) {
                  self.setContentAppend('<div>Exportação realizada com sucesso.</div>');
                  var fileName = '<?php echo $cod_empresa; ?>_' + nome + '.csv';
                  SaveToDisk('media/excel/' + fileName, fileName);
                  // console.log(response);
                }).fail(function(response) {
                  self.setContentAppend('<div>Erro ao realizar o procedimento!</div>');
                  // console.log(response.responseText);
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

  function reloadPage(idPage) {
    $.ajax({
      type: "POST",
      url: "relatorios/ajxRelAlteraResponsavel.do?opcao=paginar&id=<?php echo fnEncode($cod_empresa); ?>&idPage=" + idPage + "&itens_por_pagina=<?php echo $itens_por_pagina; ?>",
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

  function retornaForm(index) {
    $("#formulario #COD_GRUPOENT").val($("#ret_COD_GRUPOENT_" + index).val());
    $("#formulario #DES_GRUPOENT").val($("#ret_DES_GRUPOENT_" + index).val());
    $("#formulario #NOM_RESPON").val($("#ret_NOM_RESPON_" + index).val());
    $("#formulario #COD_REGITRA").val($("#ret_COD_FILTRO_" + index).val()).trigger('chosen:updated');
    $('#formulario').validator('validate');
    $("#formulario #hHabilitado").val('S');
  }
</script>