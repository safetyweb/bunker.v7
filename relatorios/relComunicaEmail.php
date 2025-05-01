<?php
//echo fnDebug('true');
//inicialização de variáveis
$dias30="";
$dat_ini="";
$dat_fim="";

//inicialização de variáveis
$hoje = fnFormatDate(date("Y-m-d"));
//$dias30 = fnFormatDate(date('Y-m-d', strtotime($dias30. '- 1 days')));
$dias30 = fnFormatDate(date("Y-m-d"));

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
    $cod_campanha = fnLimpaCampoZero($_REQUEST['COD_CAMPANHA']);

    $dat_ini = fnDataSql($_POST['DAT_INI']);
    $dat_fim = fnDataSql($_POST['DAT_FIM']);


    $opcao = $_REQUEST['opcao'];
    $hHabilitado = $_REQUEST['hHabilitado'];
    $hashForm = $_REQUEST['hashForm'];

    if ($opcao != '') {
      
    }
  }
}

//fnEscreve($sql);

$arrayQuery = mysqli_query($connAdm->connAdm(), $sql) or die(mysqli_error());
$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

$nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];

//inicialização das variáveis - default 
if (strlen($dat_ini) == 0 || $dat_ini == "1969-12-31" ){
  $dat_ini = fnDataSql($dias30);
  $dat_ini = fnmesanosql($dat_ini)."-01"; 
} 
if (strlen($dat_fim ) == 0 || $dat_fim == "1969-12-31"){
  $dat_fim = fnDataSql($hoje); 
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

  table a:not(.btn), .table a:not(.btn) {
    text-decoration: none;
  }
  table a:not(.btn):hover, .table a:not(.btn):hover {
    text-decoration: underline;
  }

  .no-weight tr td{
    font-weight: normal!important;
  }

  .bold{
    font-weight: bold;
  }

  .overflown{
    overflow-x: scroll;
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

                <div class="col-md-2">
                  <div class="form-group">
                    <label for="inputName" class="control-label required">Empresa</label>
                    <input type="text" class="form-control input-sm leitura" readonly="readonly" name="NOM_EMPRESA" id="NOM_EMPRESA" value="<?php echo $nom_empresa ?>">
                    <input type="hidden" class="form-control input-sm" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa; ?>">
                  </div>														
                </div>

                <div class="col-md-4">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="inputName" class="control-label">Data Inicial do Envio</label>

                        <div class="input-group date datePicker" id="DAT_CHAMADO_GRP">
                          <input type='text' class="form-control input-sm data" name="DAT_INI" id="DAT_INI" value="<?=fnDataShort($dat_ini)?>"/>
                          <span class="input-group-addon">
                            <span class="glyphicon glyphicon-calendar"></span>
                          </span>
                        </div>
                        <div class="help-block with-errors"></div>
                      </div>
                    </div>

                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="inputName" class="control-label">Data Final do Envio</label>

                        <div class="input-group date datePicker" id="DAT_CHAMADO_GRP">
                          <input type='text' class="form-control input-sm data" name="DAT_FIM" id="DAT_FIM" value="<?=fnDataShort($dat_fim)?>"/>
                          <span class="input-group-addon">
                            <span class="glyphicon glyphicon-calendar"></span>
                          </span>
                        </div>
                        <div class="help-block with-errors"></div>
                      </div>
                    </div>
					
                </div>

                <div class="col-md-2">
                  <div class="form-group">
                  
                    <label for="inputName" class="control-label">Campanha</label>
                      <select data-placeholder="Selecione a campanha" name="COD_CAMPANHA" id="COD_CAMPANHA" class="chosen-select-deselect">
                        <option value=""></option>            
                        <?php

                          $sql = "SELECT COD_CAMPANHA, DES_CAMPANHA FROM CAMPANHA 
                              WHERE COD_EMPRESA = $cod_empresa 
                              AND COD_EXT_CAMPANHA IS NOT NULL";
                          $arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);
                          while($qrCamp = mysqli_fetch_assoc($arrayQuery)){
                        ?>

                            <option value="<?=$qrCamp[COD_CAMPANHA]?>"><?=$qrCamp['DES_CAMPANHA']?></option>

                        <?php
                          }
                        ?>                        
                      </select> 
                    <div class="help-block with-errors"></div>
                    <script type="text/javascript">$("#formulario #COD_CAMPANHA").val('<?=$cod_campanha?>').trigger("chosen:updated");</script>
                  </div>
                </div>
				
                <div class="col-md-2">
                  <div class="push20"></div>
                  <button type="submit" name="ALT" id="ALT" class="btn btn-primary btn-block btn-sm btn-block getBtn"><i class="fa fa-filter" aria-hidden="true"></i>&nbsp; Filtrar</button>
                </div>					

              </div>

            </fieldset>

            <div class="push30"></div>

            <div>
              <div class="row">
                <div class="col-md-12 overflown">

                  <div class="no-more-tables">

                    <table class="table table-bordered table-striped table-hover tablesorter" id="tablista">
				 
          						<thead>
                        <tr>
                          <th class="{ sorter: false }"></th>
                          <th class="{ sorter: false }"></th>
                          <th>Campanha</th>
                          <th>Data de Envio</th>
                          <th>Enviados</th>
                          <th>Filtrados</th>
                          <th>Exclusão</th>
                          <th>Disparados</th>
                          <th>Sucesso</th>
                          <th>% Sucesso </th>
                          <th>Falhas </th>
                          <th>% Falhas </th>
                          <th>Lidos </th>
                          <th>% Lidos </th>
                          <th>Não Lidos </th>
                          <th>% Não Lidos </th>
                          <th>Opt Out </th>
                          <th>Cliques </th>
                        </tr>
                        </thead>

            						<tbody id="listaTemplates">

            							<?php

                            $andData = "AND (EL.DAT_AGENDAMENTO BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59' /*OR CEM.DAT_ENVIO IS NULL*/)";

            								if($cod_campanha != 0){
                              $andCampanha = "AND EL.COD_CAMPANHA = $cod_campanha";
                              $andData = "";
                            }else{
                              $andCampanha = "";
                            }
            								
            								$sql = "SELECT
                                    CEM.COD_DISPARO,
                                    SUM(CEM.QTD_DIFERENCA) AS QTD_DIFERENCA,
                                    SUM(CEM.QTD_CONTATOS) AS QTD_CONTATOS,
                                    SUM(CEM.QTD_EXCLUSAO) AS QTD_EXCLUSAO,
                                    CP.COD_CAMPANHA,
                                    SUM(CEM.QTD_DISPARADOS) AS QTD_DISPARADOS,
                                    SUM(CEM.QTD_SUCESSO) AS QTD_SUCESSO,
                                    SUM(CEM.QTD_FALHA) AS QTD_FALHA,
                                    SUM(CEM.QTD_LIDOS) AS QTD_LIDOS,
                                    SUM(CEM.QTD_NLIDOS) AS QTD_NLIDOS,
                                    SUM(CEM.QTD_OPTOUT) AS QTD_OPTOUT,
                                    SUM(CEM.QTD_CLIQUES) AS QTD_CLIQUES,
                                    -- CEM.DAT_ENVIO,
                                    TE.NOM_TEMPLATE,
                                    SUM(EL.QTD_LISTA) AS QTD_LISTA,
                                    EL.DES_PATHARQ,
                                    EL.COD_GERACAO,
                                    EL.COD_CONTROLE,
                                    EL.COD_LOTE,
                                    CP.DES_CAMPANHA,
                                    MAX(EL.DAT_AGENDAMENTO) AS DAT_ENVIO
                                  FROM EMAIL_LOTE EL
                                  LEFT JOIN CONTROLE_ENTREGA_MAIL CEM ON EL.COD_DISPARO_EXT = CEM.COD_DISPARO
                                  LEFT JOIN TEMPLATE_EMAIL TE ON TE.COD_EXT_TEMPLATE = CEM.ID_TEMPLETE
                                  LEFT JOIN CAMPANHA CP ON CP.COD_CAMPANHA = EL.COD_CAMPANHA
                                  WHERE EL.COD_EMPRESA = $cod_empresa 
                                  AND EL.LOG_ENVIO = 'S'
                                  $andCampanha
                                  $andData
                                  GROUP BY EL.COD_CAMPANHA
                                  ORDER BY EL.COD_CONTROLE DESC 
                                    ";
            								
            								// fnEscreve($sql);
            								
            								$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
            								
            								$count=0;
            								while ($qrCampanhasEmail = mysqli_fetch_assoc($arrayQuery)){

                            //    echo("<pre>");
                            // print_r($qrCampanhasEmail);
                            // echo("</pre>");											  
            									$count++;

            									$pct_sucesso = ($qrCampanhasEmail['QTD_SUCESSO']/$qrCampanhasEmail['QTD_DISPARADOS'])*100;
            									$pct_falha = ($qrCampanhasEmail['QTD_FALHA']/$qrCampanhasEmail['QTD_DISPARADOS'])*100;
            									$pct_lidos = ($qrCampanhasEmail['QTD_LIDOS']/$qrCampanhasEmail['QTD_DISPARADOS'])*100;
            									$pct_nlidos = ($qrCampanhasEmail['QTD_NLIDOS']/$qrCampanhasEmail['QTD_DISPARADOS'])*100;

                              if($qrCampanhasEmail['DAT_ENVIO'] == ""){
                                $dat_envio = "Em Andamento";
                              }else{
                                $dat_envio = fnDataFull($qrCampanhasEmail['DAT_ENVIO']);
                              }

            									echo"
                                  <tr id='CAMPANHA_".$qrCampanhasEmail['COD_CAMPANHA']."'>                              
                                    <td class='text-center'><a href='javascript:void(0);' onclick='abreDetail(".$qrCampanhasEmail['COD_CAMPANHA'].")'><i class='fal fa-angle-right' aria-hidden='true'></i></a></td>
                                    <td><a onclick='exportarCSV(this)' value='".$qrCampanhasEmail['COD_CAMPANHA']."'><span class='fas fa-download'></span></a></td>
                                    <td><small><small>".$qrCampanhasEmail['COD_CAMPANHA']."</small>&nbsp;".$qrCampanhasEmail['DES_CAMPANHA']."</small></td>
                                    <td><small><small>".$dat_envio."</small></small></td>
                                    <td class='text-right bold'><small>".fnValor($qrCampanhasEmail['QTD_LISTA'],0)."</small></td>
                                    <td class='text-right bold'><small>".fnValor($qrCampanhasEmail['QTD_CONTATOS'],0)."</small></td>
                                    <td class='text-right bold'><small>".fnValor($qrCampanhasEmail['QTD_EXCLUSAO']+$qrCampanhasEmail['QTD_DIFERENCA'],0)."</small></td>
                                    <td class='text-right bold'><small>".fnValor($qrCampanhasEmail['QTD_DISPARADOS'],0)."</small></td>
                                    <td class='text-right bold'><small>".fnValor($qrCampanhasEmail['QTD_SUCESSO'],0)."</small></td>
                                    <td class='text-right'><small>".fnValor($pct_sucesso,2)."%</small></td>
                                    <td class='text-right bold'><small>".fnValor($qrCampanhasEmail['QTD_FALHA'],0)."</small></td>
                                    <td class='text-right'><small>".fnValor($pct_falha,2)."%</small></td>
                                    <td class='text-right'><small>".fnValor($qrCampanhasEmail['QTD_LIDOS'],0)."</small></td>
                                    <td class='text-right'><small>".fnValor($pct_lidos,2)."%</small></td>
                                    <td class='text-right'><small>".fnValor($qrCampanhasEmail['QTD_NLIDOS'],0)."</small></td>
                                    <td class='text-right'><small>".fnValor($pct_nlidos,2)."%</small></td>
                                    <td class='text-right bold'><small>".fnValor($qrCampanhasEmail['QTD_OPTOUT'],0)."</small></td>
                                    <td class='text-right bold'><small>".fnValor($qrCampanhasEmail['QTD_CLIQUES'],0)."</small></td>
                                  </tr>                      
                                    ";
                                  
                              echo"
                                  <thead class='no-weight' style='display:none; background-color: #fff;' id='abreDetail_".$qrCampanhasEmail['COD_CAMPANHA']."'>
                                   
                                  </thead>                             
                                  ";

                                  $tot_qtd += $qrCampanhasEmail['QTD_LISTA'];
                                  $tot_disparados += $qrCampanhasEmail['QTD_DISPARADOS'];
                                  $tot_sucesso += $qrCampanhasEmail['QTD_SUCESSO'];
                                  $tot_falha += $qrCampanhasEmail['QTD_FALHA'];
                                  $tot_contatos += $qrCampanhasEmail['QTD_CONTATOS'];
                                  $tot_exclusao += $qrCampanhasEmail['QTD_EXCLUSAO']+$qrCampanhasEmail['QTD_DIFERENCA'];
                                  $tot_optout += $qrCampanhasEmail['QTD_OPTOUT'];
                                  $tot_cliques += $qrCampanhasEmail['QTD_CLIQUES'];

            								}

            							?>

            						</tbody>
          					  
            						<tfoot>
                          <tr>
                            <td colspan="4"></td>
                            <td class="text-right"><b><?=fnValor($tot_qtd,0)?></b></td>
                            <td class="text-right"><b><?=fnValor($tot_contatos,0)?></b></td>
                            <td class="text-right"><b><?=fnValor($tot_exclusao,0)?></b></td>
                            <td class="text-right"><b><?=fnValor($tot_disparados,0)?></b></td>
                            <td class="text-right"><b><?=fnValor($tot_sucesso,0)?></b></td>
                            <td></td>
                            <td class="text-right"><b><?=fnValor($tot_falha,0)?></b></td>
                            <td colspan="5"></td>
                            <td class="text-right"><b><?=fnValor($tot_optout,0)?></b></td>
                            <td class="text-right"><b><?=fnValor($tot_cliques,0)?></b></td>
                          </tr>
                          <tr>
                            <th colspan="100">
                              <a class="btn btn-info btn-sm exportarCSV" onclick="exportarCSV(this)" value="N"><i class="fa fa-file-excel" aria-hidden="true"></i> &nbsp; Exportar </a> &nbsp;&nbsp;
                              <!-- <a class="btn btn-info btn-sm exportarCSV" onclick="exportarCSV(this)" value="S"><i class="fa fa-file-excel" aria-hidden="true"></i> &nbsp; Exportar Detalhes</a> &nbsp;&nbsp; -->
                            </th>
                          </tr>
                        </tfoot>

                    </table>


                    <div class="push"></div>

                    <input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?=$cod_empresa?>">
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

  });

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

    function exportarCSV(btn) {
    log_detalhes = $(btn).attr('value');
    // alert(id);
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
                icon: 'fa fa-check-square',
                content: function(){
                  var self = this;
                  return $.ajax({
                    url: "relatorios/ajxRelComunicaEmail.do?opcao=exportar&nomeRel="+nome+"&id=<?php echo fnEncode($cod_empresa); ?>&detalhes="+log_detalhes, 
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
    }

  function abreDetail(idCampanha){
    RefreshCampanha(<?php echo $cod_empresa; ?>, idCampanha);
  }
  
  function RefreshCampanha(idEmp, idCampanha) {
    var idItem = $('#abreDetail_'+idCampanha);
    
    if (!idItem.is(':visible')){
      $.ajax({
        type: "POST",
        url: "relatorios/ajxRelComunicaEmail.do",
        data: { COD_EMPRESA:idEmp, COD_CAMPANHA:idCampanha, DATA: "<?=fnEncode($andData)?>" },
        beforeSend:function(){
          $("#abreDetail_"+idCampanha).html('<div class="loading" style="width: 100%;"></div>');
        },
        success:function(data){
          $("#abreDetail_"+idCampanha).html(data); 
        },
        error:function(data){
          $("#abreDetail_"+idCampanha).html(data);
          // $("#mostraDetail_"+idCampanha).html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> Registros não encontrados...</p>');
        }
      });
      
      idItem.show();
      
      $('#CAMPANHA_'+idCampanha).find($(".fa-angle-right")).removeClass('fa-angle-right').addClass('fa-angle-down');
    }else{
      idItem.hide();
      $('#CAMPANHA_'+idCampanha).find($(".fa-angle-down")).removeClass('fa-angle-down').addClass('fa-angle-right');
    }
  }

</script>
