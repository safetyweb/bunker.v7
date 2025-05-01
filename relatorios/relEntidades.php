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
$nom_municipio = "";
$count_filtros = "";
$Arr_COD_FILTRO = "";
$i = "";
$cod_filtro = "";
$hHabilitado = "";
$hashForm = "";
$arrayQuery = [];
$qrBuscaEmpresa = "";
$nom_empresa = "";
$log_funcionario = "";
$check_funcionario = "";
$log_inativos = "";
$checkInativos = "";
$andInativos = "";
$cod_persona = "";
$countFiltros = "";
$countObjeto = "";
$qrTipo = "";
$sqlFiltro = "";
$arrayFiltros = [];
$qrFiltros = "";
$sqlChosen = "";
$arrayChosen = [];
$qrChosen = "";
$cod_filtros = "";
$andEntidad = "";
$andMunicipio = "";
$andRespon = "";
$andFiltro = "";
$retorno = "";
$totalitens_por_pagina = 0;
$inicio = "";
$qrBuscaModulos = "";
$municipio = "";
$estado = "";
$sqlCidade = "";
$arrayMunicipio = [];
$qrMunicipio = "";
$sqlEstado = "";
$arrayEstado = [];
$qrEstado = "";
$lojasSelecionadas = "";
$content = "";


//inicialização de variáveis
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

    $nom_entidad = fnLimpacampo(@$_REQUEST['NOM_ENTIDAD']);
    $nom_respon = fnLimpacampo(@$_REQUEST['NOM_RESPON']);
    $nom_municipio = fnLimpacampo(@$_REQUEST['NOM_MUNICIPIO']);
    $count_filtros = fnLimpacampo(@$_REQUEST['COUNT_FILTROS']);

    if (isset($_POST['COD_FILTRO_0'])) {
      $Arr_COD_FILTRO = @$_POST['COD_FILTRO_0'];
      //print_r($Arr_COD_FILTRO);			 

      for ($i = 0; $i < count($Arr_COD_FILTRO); $i++) {
        $cod_filtro = $cod_filtro . $Arr_COD_FILTRO[$i] . ",";
      }

      $cod_filtro = substr($cod_filtro, 0, -1);
    } else {
      $cod_filtro = "0";
    }

    $opcao = @$_REQUEST['opcao'];
    $hHabilitado = @$_REQUEST['hHabilitado'];
    $hashForm = @$_REQUEST['hashForm'];

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
          <i class="fal fa-terminal"></i>
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
                    <label for="inputName" class="control-label">Nome da Entidade</label>
                    <input type="text" class="form-control input-sm" name="NOM_ENTIDAD" id="NOM_ENTIDAD" maxlength="100" value="<?= $nom_entidad ?>">
                  </div>
                  <div class="help-block with-errors"></div>
                </div>

                <div class="col-md-3">
                  <div class="form-group">
                    <label for="inputName" class="control-label">Nome do Responsável</label>
                    <input type="text" class="form-control input-sm" name="NOM_RESPON" id="NOM_RESPON" maxlength="60" value="<?= $nom_respon ?>">
                  </div>
                  <div class="help-block with-errors"></div>
                </div>

                <div class="col-md-3">
                  <div class="form-group">
                    <label for="inputName" class="control-label">Cidade</label>
                    <input type="text" class="form-control input-sm" name="NOM_MUNICIPIO" id="NOM_MUNICIPIO" maxlength="60" value="<?= $nom_municipio ?>">
                  </div>
                  <div class="help-block with-errors"></div>
                </div>
                <?php
                //FILTROS DINÂMICOS
                $countFiltros = 0;

                $sql = "SELECT COD_TPFILTRO, DES_TPFILTRO FROM TIPO_FILTRO
                WHERE COD_EMPRESA = $cod_empresa
                AND COD_TPFILTRO = 28
                ORDER BY NUM_ORDENAC";
                $arrayQuery = mysqli_query(connTemp($cod_empresa, ''), trim($sql));

                if (mysqli_num_rows($arrayQuery) > 0) {

                  $countObjeto = 0
                ?>
                  <?php
                  while ($qrTipo = mysqli_fetch_assoc($arrayQuery)) {
                  ?>

                    <div class="col-xs-3">
                      <div class="form-group">
                        <label for="inputName" class="control-label"><?= $qrTipo['DES_TPFILTRO'] ?></label>
                        <div id="relatorioFiltro_<?= $countFiltros ?>">
                          <input type="hidden" name="COD_TPFILTRO_<?= $countFiltros ?>" id="COD_TPFILTRO_<?= $countFiltros ?>" value="<?= $qrTipo['COD_TPFILTRO'] ?>">
                          <select data-placeholder="Selecione os filtros" name="COD_FILTRO_<?= $countFiltros ?>[]" id="COD_FILTRO_<?= $qrTipo['COD_TPFILTRO'] ?>" multiple="multiple" class="chosen-select-deselect last-chosen-link">
                            <option value=""></option>

                            <?php
                            $sqlFiltro = "SELECT COD_FILTRO, DES_FILTRO FROM FILTROS_CLIENTE
                                          WHERE COD_TPFILTRO = $qrTipo[COD_TPFILTRO]
                                          ORDER BY DES_FILTRO";

                            $arrayFiltros = mysqli_query(connTemp($cod_empresa, ''), trim($sqlFiltro));
                            while ($qrFiltros = mysqli_fetch_assoc($arrayFiltros)) {
                            ?>

                              <option value="<?= $qrFiltros['COD_FILTRO'] ?>"><?= $qrFiltros['DES_FILTRO'] ?></option>

                            <?php
                            }


                            $sqlChosen = "SELECT COD_FILTRO FROM FILTROS_PERSONA
                                                                                        WHERE COD_PERSONA = $cod_persona AND COD_TPFILTRO =" . $qrTipo['COD_TPFILTRO'];
                            $arrayChosen = mysqli_query(connTemp($cod_empresa, ''), $sqlChosen);

                            while ($qrChosen = mysqli_fetch_assoc($arrayChosen)) {
                              $cod_filtros .= $qrChosen['COD_FILTRO'] . ",";
                            }

                            $cod_filtros = rtrim(ltrim($cod_filtros, ','), ',');

                            ?>
                            <script>

                            </script>

                          </select>
                          <div class="help-block with-errors"></div>
                          <a class="btn btn-default btn-sm" id="iAll_<?= $countFiltros ?>" style="padding: 0 2px ; font-size: 10px;"><i class="fa fa-check-square" aria-hidden="true"></i> selecionar todos</a>&nbsp;
                          <a class="btn btn-default btn-sm" id="iNone_<?= $countFiltros ?>" style="padding: 0 2px ; font-size: 10px;"><i class="fa fa-square-o" aria-hidden="true"></i>&nbsp; deselecionar todos</a>
                          <script>
                            $(function() {
                              $('#iAll_<?= $countFiltros ?>').on('click', function(e) {
                                e.preventDefault();
                                $('#COD_FILTRO_<?= $qrTipo['COD_TPFILTRO'] ?> option').prop('selected', true).trigger('chosen:updated');
                              });

                              $('#iNone_<?= $countFiltros ?>').on('click', function(e) {
                                e.preventDefault();
                                $("#COD_FILTRO_<?= $qrTipo['COD_TPFILTRO'] ?> option:selected").removeAttr("selected").trigger('chosen:updated');
                              });
                            });
                          </script>
                        </div>
                      </div>
                    </div>

                  <?php
                    if ($countObjeto == 3) {
                      $countObjeto = 0;
                      echo '<div class="push10"></div>';
                    } else {
                      $countObjeto++;
                    }
                    $countFiltros++;
                  }
                  ?>
                  <div class="push20"></div>

              </div>

            <?php
                }
            ?>
            <div class="form-group text-right col-lg-12">

              <button type="reset" class="btn btn-default"><i class="fal fa-eraser" aria-hidden="true"></i>&nbsp; Apagar</button>
              <button type="submit" name="BUS" id="BUS" class="btn btn-primary getBtn"><i class="fal fa-filter" aria-hidden="true"></i>&nbsp; Filtrar</button>

            </div>

            </fieldset>

        </div>

      </div>

    </div>

    <div class="push20"></div>

    <div class="portlet portlet-bordered">

      <div class="portlet-body">

        <div class="login-form">

          <div class="row">

            <div class="col-lg-12">

              <div class="no-more-tables">


                <table class="table table-bordered table-striped table-hover tablesorter buscavel" id="tablista">
                  <thead>
                    <tr>
                      <th class="{sorter:false}" width="40">
                      <th>Código</th>
                      <!-- <th>Empresa</th> -->
                      <th>Nome da Entidade</th>
                      <th>Nome do Responsável</th>
                      <th>Cidade</th>
                      <th>Região</th>
                      <th>Estado</th>

                    </tr>
                  </thead>
                  <tbody id="relatorioConteudo">

                    <?php

                    if ($nom_entidad != '' && $nom_entidad != 0) {
                      $andEntidad = "AND ENTIDADE.NOM_ENTIDAD LIKE '%$nom_entidad%'";
                    } else {
                      $andEntidad = "";
                    }

                    if ($nom_municipio != '' && $nom_municipio != 0) {
                      $andMunicipio = "AND ENTIDADE.NOM_CIDADES LIKE '%$nom_municipio%'";
                    } else {
                      $andMunicipio = "";
                    }

                    if ($nom_respon != '' && $nom_respon != 0) {
                      $andRespon = "AND ENTIDADE.NOM_RESPON LIKE '%$nom_respon%'";
                    } else {
                      $andRespon = "";
                    }
                    if ($cod_filtro == 0) {
                      $andFiltro = "";
                    } elseif ($cod_filtro != '' && $cod_filtro != 0) {
                      $andFiltro = "AND B.COD_FILTRO IN($cod_filtro)";
                    } else {
                      $andFiltro = "";
                    }

                    //============================
                    $sql = "SELECT 1 from ENTIDADE 
                                INNER  JOIN entidade_grupo  A ON A.COD_GRUPOENT=ENTIDADE.COD_GRUPOENT AND A.COD_EMPRESA=ENTIDADE.COD_EMPRESA
                                INNER JOIN FILTROS_CLIENTE B ON B.COD_FILTRO=A.COD_REGITRA AND B.COD_EMPRESA=A.COD_EMPRESA
                                where ENTIDADE.COD_EMPRESA = $cod_empresa
                                $andEntidad
                                $andMunicipio
                                $andRespon
                                $andFiltro";
                    //echo $sql;    
                    $retorno = mysqli_query(connTemp($cod_empresa, ''), $sql);
                    $totalitens_por_pagina = mysqli_num_rows($retorno);
                    $numPaginas = ceil($totalitens_por_pagina / $itens_por_pagina);

                    // fnEscreve($numPaginas);

                    $inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;



                    $sql = "SELECT ENTIDADE.COD_ENTIDAD, 
                                                ENTIDADE.COD_GRUPOENT, 
                                                ENTIDADE.COD_TPENTID, 
                                                ENTIDADE.COD_EXTERNO, 
                                                ENTIDADE.COD_EMPRESA, 
                                                ENTIDADE.COD_MUNICIPIO, 
                                                ENTIDADE.COD_ESTADO, 
                                                ENTIDADE.NOM_ENTIDAD, 
                                                ENTIDADE.NUM_CGCECPF, 
                                                ENTIDADE.DES_ENDERC, 
                                                ENTIDADE.NUM_ENDEREC, 
                                                ENTIDADE.DES_BAIRROC, 
                                                ENTIDADE.NUM_CEPOZOF, 
                                                ENTIDADE.NOM_CIDADES, 
                                                ENTIDADE.NOM_ESTADOS, 
                                                ENTIDADE.NUM_TELEFONE, 
                                                ENTIDADE.NUM_CELULAR, 
                                                ENTIDADE.EMAIL, 
                                                ENTIDADE.NOM_RESPON, 
                                                ENTIDADE.QTD_MEMBROS,
                                                B.COD_FILTRO,
                                                B.DES_FILTRO
                                FROM ENTIDADE 
                                INNER  JOIN entidade_grupo  A ON A.COD_GRUPOENT=ENTIDADE.COD_GRUPOENT AND A.COD_EMPRESA=ENTIDADE.COD_EMPRESA
                                INNER JOIN FILTROS_CLIENTE B ON B.COD_FILTRO=A.COD_REGITRA AND B.COD_EMPRESA=A.COD_EMPRESA
                                $andFiltro
                                $andRespon 
                                $andMunicipio
                                $andEntidad
                                WHERE ENTIDADE.COD_EMPRESA = $cod_empresa
                                ORDER BY ENTIDADE.NOM_ENTIDAD
                                LIMIT $inicio,$itens_por_pagina";


                    $arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

                    // echo ($sql);

                    $count = 0;
                    while ($qrBuscaModulos = mysqli_fetch_assoc($arrayQuery)) {

                      // $municipio = "";
                      $estado = "";

                      //  if($qrBuscaModulos['COD_MUNICIPIO'] != ""){
                      //    $sqlCidade = "SELECT NOM_MUNICIPIO FROM MUNICIPIOS WHERE COD_MUNICIPIO = $qrBuscaModulos['COD_MUNICIPIO']";
                      //    $arrayMunicipio = mysqli_query(connTemp($cod_empresa,''),$sqlCidade);   
                      //    $qrMunicipio = mysqli_fetch_assoc($arrayMunicipio);
                      //    $municipio = $qrMunicipio['NOM_MUNICIPIO'];
                      // }

                      if ($qrBuscaModulos['COD_ESTADO'] != "") {

                        $sqlEstado = "SELECT UF FROM ESTADO WHERE COD_ESTADO = $qrBuscaModulos[COD_ESTADO]";
                        $arrayEstado = mysqli_query(connTemp($cod_empresa, ''), $sqlEstado);
                        $qrEstado = mysqli_fetch_assoc($arrayEstado);
                        $estado = $qrEstado['UF'];
                      }

                      $count++;
                    ?>
                      <tr>
                        <td><input type='radio' name='radio1' onclick='retornaForm(<?= $count; ?>)'></td>
                        <td><?= $qrBuscaModulos['COD_ENTIDAD'] ?></td>
                        <td><a href="action.do?mod=<?= fnEncode(1075) ?>&id=<?= fnEncode($cod_empresa) ?>&idE=<?= fnEncode($qrBuscaModulos['COD_ENTIDAD']) ?>" class="f14" target="_blank"><?= $qrBuscaModulos['NOM_ENTIDAD'] ?></td>
                        <td><?= $qrBuscaModulos['NOM_RESPON'] ?></td>
                        <td><?= $qrBuscaModulos['NOM_CIDADES'] ?></td>
                        <td><?= $qrBuscaModulos['DES_FILTRO'] ?></td>
                        <td><?= $estado ?></td>
                      </tr>

                      <input type='hidden' id='ret_COD_ENTIDAD_<?= $count ?>' value='<?= $qrBuscaModulos['COD_ENTIDAD'] ?>'>
                      <input type='hidden' id='ret_COD_GRUPOENT_<?= $count ?>' value='<?= $qrBuscaModulos['COD_GRUPOENT'] ?>'>
                      <input type='hidden' id='ret_COD_TPENTID_<?= $count ?>' value='<?= $qrBuscaModulos['COD_TPENTID'] ?>'>
                      <input type='hidden' id='ret_COD_EMPRESA_<?= $count ?>' value='<?= $qrBuscaModulos['COD_EMPRESA'] ?>'>
                      <input type='hidden' id='ret_des_filtro_<?= $count ?>' value='<?= $resultset['des_filtro'] ?>'>
                      <input type='hidden' id='ret_NOM_ENTIDAD_<?= $count ?>' value='<?= $qrBuscaModulos['NOM_ENTIDAD'] ?>'>
                      <input type='hidden' id='ret_COD_FILTRO_<?= $count ?>' value='<?= $qrBuscaModulos['COD_FILTRO'] ?>'>
                      <input type='hidden' id='ret_NUM_CGCECPF_<?= $count ?>' value='<?= $qrBuscaModulos['NUM_CGCECPF'] ?>'>
                      <input type='hidden' id='ret_DES_ENDERC_<?= $count ?>' value='<?= $qrBuscaModulos['DES_ENDERC'] ?>'>
                      <input type='hidden' id='ret_NUM_ENDEREC_<?= $count ?>' value='<?= $qrBuscaModulos['NUM_ENDEREC'] ?>'>
                      <input type='hidden' id='ret_DES_BAIRROC_<?= $count ?>' value='<?= $qrBuscaModulos['DES_BAIRROC'] ?>'>
                      <input type='hidden' id='ret_NUM_CEPOZOF_<?= $count ?>' value='<?= $qrBuscaModulos['NUM_CEPOZOF'] ?>'>
                      <input type='hidden' id='ret_COD_MUNICIPIO_<?= $count ?>' value='<?= $qrBuscaModulos['COD_MUNICIPIO'] ?>'>
                      <input type='hidden' id='ret_NOM_CIDADES_<?= $count ?>' value='<?= $qrBuscaModulos['NOM_CIDADES'] ?>'>
                      <input type='hidden' id='ret_COD_ESTADO_<?= $count ?>' value='<?= $qrBuscaModulos['COD_ESTADO'] ?>'>
                      <input type='hidden' id='ret_NUM_TELEFONE_<?= $count ?>' value='<?= $qrBuscaModulos['NUM_TELEFONE'] ?>'>
                      <input type='hidden' id='ret_NUM_CELULAR_<?= $count ?>' value='<?= $qrBuscaModulos['NUM_CELULAR'] ?>'>
                      <input type='hidden' id='ret_EMAIL_<?= $count ?>' value='<?= $qrBuscaModulos['EMAIL'] ?>'>
                      <input type='hidden' id='ret_NOM_RESPON_<?= $count ?>' value='<?= $qrBuscaModulos['NOM_RESPON'] ?>'>
                      <input type='hidden' id='ret_COD_EXTERNO_<?= $count ?>' value='<?= $qrBuscaModulos['COD_EXTERNO'] ?>'>
                      <input type='hidden' id='ret_QTD_MEMBROS_<?= $count ?>' value='<?= $qrBuscaModulos['QTD_MEMBROS'] ?>'>
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

                <input type="hidden" name="LOJAS" id="LOJAS" value="<?php echo $lojasSelecionadas; ?>" />
                <input type="hidden" name="COUNT_FILTROS" id="COUNT_FILTROS" value="<?= $countFiltros ?>">
                <input type="hidden" name="AND_FILTROS" id="AND_FILTROS" value="<?= $andFiltro ?>">
                <input type="hidden" class="form-control input-sm" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa; ?>">
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
  $(document).ready(function() {

    var numPaginas = <?php echo $numPaginas; ?>;
    if (numPaginas != 0) {
      carregarPaginacao(numPaginas);
    }

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
                  url: "relatorios/ajxRelEntidades.do?opcao=exportar&nomeRel=" + nome + "&id=<?php echo fnEncode($cod_empresa); ?>",
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
      url: "relatorios/ajxRelEntidades.do?opcao=paginar&id=<?php echo fnEncode($cod_empresa); ?>&idPage=" + idPage + "&itens_por_pagina=<?php echo $itens_por_pagina; ?>",
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
    $("#formulario #NOM_ENTIDAD").val($("#ret_NOM_ENTIDAD_" + index).val());
    $("#formulario #NOM_RESPON").val($("#ret_NOM_RESPON_" + index).val());
    $("#formulario #NOM_MUNICIPIO").val($("#ret_NOM_CIDADES_" + index).val());
    var filtros = $('#ret_COD_FILTRO_' + index).val();
    if (filtros != 0 && filtros != "") {
      //retorno combo multiplo - USUARIOS_AGE
      $("#formulario #COD_FILTRO_28").val('').trigger("chosen:updated");

      var sistemasUni = filtros;
      var sistemasUniArr = sistemasUni.split(',');
      //opções multiplas
      for (var i = 0; i < sistemasUniArr.length; i++) {
        $("#formulario #COD_FILTRO_28 option[value=" + sistemasUniArr[i] + "]").prop("selected", "true");
      }
      $("#formulario #COD_FILTRO_28").trigger("chosen:updated");
    }
  }
</script>