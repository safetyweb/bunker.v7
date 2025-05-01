<?php
//echo fnDebug('true');

$dat_ini = date("Y-m-")."01";
$dat_fim = date("Y-m-").date("t", mktime(0,0,0,date("m"),'01',date("Y")));

$sql = "SELECT COD_USUARIO, NOM_USUARIO FROM usuarios where COD_USUARIO = '".$_SESSION["SYS_COD_USUARIO"]."' ";
$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
$qrBuscaUsuario = mysqli_fetch_assoc($arrayQuery);
$cod_usuario = $qrBuscaUsuario["COD_USUARIO"];
$nom_usuario = $qrBuscaUsuario["NOM_USUARIO"];


// definir o numero de itens por pagina
$itens_por_pagina = 50;
$pagina = "1";

$hashLocal = mt_rand();

$adm = ($cod_usuario == 28 || $cod_usuario == 14213 || $cod_usuario == 16928 || $cod_usuario == 0);
//$adm = true;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $request = md5(implode($_POST));

  if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
    $msgRetorno = 'Essa página já foi utilizada';
    $msgTipo = 'alert-warning';
  } else {
    $_SESSION['last_request'] = $request;

    $dat_ini = fnDataSql($_POST['DAT_INI']);
    $dat_fim = fnDataSql($_POST['DAT_FIM']);
	$cod_usuario = $_POST["COD_USUARIO"];

    $opcao = $_REQUEST['opcao'];
    $hHabilitado = $_REQUEST['hHabilitado'];
    $hashForm = $_REQUEST['hashForm'];

    if ($opcao != '') {
      
    }
  }
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
          <i class="far fa-clock"></i>
          <span class="text-primary"><?php echo $NomePg; ?> </span>
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
        <?php 
            echo ('<div class="push20"></div>');
            $abaControle = 1539;
            include "abasControleHoras.php";
        ?>	

		<div id="msgRetornoPopup"></div>
	    <div class="push30"></div>
        <div class="login-form">

          <form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

            <fieldset>
              <legend>Filtros</legend> 

              <div class="row">             

                <div class="col-md-8">
				
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="inputName" class="control-label">Usuário</label>
							<?php if ($adm){ ?>
							<select data-placeholder="Selecione um usuário" name="COD_USUARIO" id="COD_USUARIO" class="chosen-select-deselect" required>
								<option value=""></option>
								<?php 
								
									$sql = "select COD_USUARIO, NOM_USUARIO from USUARIOS WHERE COD_USUARIO IN (SELECT DISTINCT COD_USUARIO FROM HORAS_TRABALHADAS) order by NOM_USUARIO";
									$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
								
									while ($qrLista = mysqli_fetch_assoc($arrayQuery))
									  {	
																							
										echo"
											  <option value='".$qrLista['COD_USUARIO']."'>".$qrLista['NOM_USUARIO']."</option> 
											"; 
										  }											
								?>	
							</select>
							<?php }else{ ?>
							<input type="text" class="form-control input-sm leitura" readonly="readonly" name="NOM_USUARIO" id="NOM_USUARIO" value="<?php echo $nom_usuario; ?>">
							<input type="hidden" class="form-control input-sm leitura" readonly="readonly" name="COD_USUARIO" id="COD_USUARIO" value="<?php echo $cod_usuario; ?>">
							<?php } ?>
                        <div class="help-block with-errors"></div>
                      </div>
                    </div>


                    <div class="col-md-3">
                      <div class="form-group">
                        <label for="inputName" class="control-label">Data Inicial</label>

                        <div class="input-group date datePicker" id="DAT_INI_GRP">
                          <input type='text' class="form-control input-sm data" name="DAT_INI" id="DAT_INI" value="<?php echo fnFormatDate($dat_ini); ?>"/>
                          <span class="input-group-addon">
                            <span class="glyphicon glyphicon-calendar"></span>
                          </span>
                        </div>
                        <div class="help-block with-errors"></div>
                      </div>
                    </div>

                    <div class="col-md-3">
                      <div class="form-group">
                        <label for="inputName" class="control-label">Data Final</label>

                        <div class="input-group date datePicker" id="DAT_FIN_GRP">
                          <input type='text' class="form-control input-sm data" name="DAT_FIM" id="DAT_FIM" value="<?php echo fnFormatDate($dat_fim); ?>"/>
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

                <div class="col-md-2">
                  <div class="push20"></div>
					<a class="btn btn-info btn-block addBox" data-url="action.do?mod=<?php echo fnEncode(1540)?>&pop=true" data-title="Marcação de Horas"><i class="fas fa-plus" aria-hidden="true" style="margin: 5px 0 5px 0;"></i> Marcação de Horas</a>
					<a style="display:none;" class="btn btn-info btn-block addBox" id="btAltHora" data-url="" data-title="Marcação de Horas"><i class="fas fa-plus" aria-hidden="true" style="margin: 5px 0 5px 0;"></i> Alterar Hora</a>
                </div>


            <div class="push20"></div>

            <div>
              <div class="row">
                <div class="col-lg-12">

                  <div class="no-more-tables">


                    <table class="table table-bordered table-striped table-hover tablesorter" id="tablista">
                      <thead>
                        <tr>
                          <th class="{sorter:false}" width="40"></th>
                          <th>Nome</th>
                          <th>Atividade</th>
                          <th>Centro Custo/Projeto</th>
                          <th>Data</th>
                          <th>Hora Inicial</th>
                          <th>Hora Final</th>
                          <th>Dura&ccedil;&atilde;o</th>
						  <th class="{sorter:false}">&nbsp;</th>
						  <th class="{sorter:false}">&nbsp;</th>
                        </tr>
                      </thead>
                      <tbody id="relatorioConteudo">

                        <?php
                        //============================

						$where = "";
						if ($dat_ini <> "") {
							$where .= "AND DATE_FORMAT(H.DAT_ATIVIDADE, '%Y-%m-%d') >= '$dat_ini' ";
						}
						if ($dat_fim <> "") {
							$where .= "AND DATE_FORMAT(H.DAT_ATIVIDADE, '%Y-%m-%d') <= '$dat_fim' ";
						}
						if (@$cod_usuario <> "") {
							$where .= "AND H.COD_USUARIO = '$cod_usuario' ";
						}

					  //paginação
					  $sql = "SELECT COUNT(0) AS CONTADOR FROM horas_trabalhadas H WHERE 1=1 $where";
					  $retorno = mysqli_query($connAdm->connAdm(), $sql) or die(mysqli_error());
					  $total_itens_por_pagina = mysqli_fetch_assoc($retorno);

					  $numPaginas = ceil($total_itens_por_pagina['CONTADOR'] / $itens_por_pagina);

					  //variavel para calcular o início da visualização com base na página atual
					  $inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;


					  $sql = "SELECT TIME_FORMAT(SEC_TO_TIME(SUM(TIME_TO_SEC(DES_DURACAO))),'%H:%i') TOTAL_HORAS FROM
								(
									SELECT 
										FN_CALCULO_HORAS(H.HOR_INICIAL,H.HOR_FINAL) AS DES_DURACAO
									FROM horas_trabalhadas H
									WHERE 1=1 $where
								) TB";
					  $retorno = mysqli_query($connAdm->connAdm(), $sql) or die(mysqli_error());
					  $total = mysqli_fetch_assoc($retorno);


					  //lista
					  $sql = "SELECT 
								H.COD_HORA,
								H.COD_USUARIO,
								U.NOM_USUARIO,
								H.DAT_ATIVIDADE,
                CT.DESCRICAO,
								TIME_FORMAT(H.HOR_INICIAL,'%H:%i') HOR_INICIAL,
								TIME_FORMAT(H.HOR_FINAL,'%H:%i') HOR_FINAL,
								H.DES_OBSERVACAO,
								FN_CALCULO_HORAS(H.HOR_INICIAL,H.HOR_FINAL) AS DES_DURACAO
							FROM horas_trabalhadas H
							LEFT JOIN usuarios U ON (U.COD_USUARIO = H.COD_USUARIO)
              LEFT JOIN CENTRO_CUSTO CT ON (CT.ID = H.COD_CENTROCUSTO)
							WHERE 1=1 $where
							ORDER BY H.DAT_ATIVIDADE DESC, H.HOR_INICIAL DESC, H.HOR_FINAL DESC, H.COD_HORA DESC LIMIT $inicio,$itens_por_pagina";

					  $arrayQuery = mysqli_query($connAdm->connAdm(), $sql) or die(mysqli_error());
					   //fnEscreve($sql);
					  //  echo "___".$sql."___";
					  $count = 0;
					  while ($qrLista = mysqli_fetch_assoc($arrayQuery)) {
						$count++;

						echo"
							<tr>
							  <td class='text-center'><input type='hidden' name='radio1' onclick='retornaForm(" . $count . ")'></th>
							  <td><small>" . $qrLista['NOM_USUARIO'] . "</small></td>
							  <td><small>" . $qrLista['DES_OBSERVACAO'] . "</small></td>
                <td><small>" . $qrLista['DESCRICAO'] . "</small></td>
							  <td><small>" . fnFormatDate($qrLista['DAT_ATIVIDADE']) . "</small></td>
							  <td><small>" . $qrLista['HOR_INICIAL'] . "</small></td>
							  <td><small>" . $qrLista['HOR_FINAL'] . "</small></td>
							  <td><small>" . $qrLista['DES_DURACAO'] . "</small></td>
							  <td class='text-center'>
								".($adm?"<a class='btn btn-xs btn-info addBox transparency' onClick='retornaForm(" . $count . ")'><i class='fas fa-pencil'></i> Editar </a>":"")."
							  </td>
							  <td class='text-center'>
								".($qrLista['HOR_FINAL'] == ""?"<a class='btn btn-xs btn-success transparency' onClick='retornaForm(" . $count . ")'><i class='fa fas fa-arrow-down'></i> Saída </a>":"")."
							  </td>
							</tr>
							<input type='hidden' id='ret_COD_HORA_" . $count . "' value='" . fnEncode($qrLista['COD_HORA']) . "'>
							";
					  }
                      ?>

                      </tbody>
                        <tfoot>
						  <tr>
							<td style="text-align:right" colspan=6><span class="f21">Total</span></td>
							<td><span class="f21"><?=$total["TOTAL_HORAS"]?></span></td>
						  </tr>
                          <tr>
                            <th colspan="8">
								<?php /*
                              <a class="btn btn-info btn-sm exportarCSV"> <i class="fa fa-file-excel" aria-hidden="true"></i>&nbsp; Exportar</a>
							  */ ?>
                            </th>
                          </tr>
                          <tr>
                            <th class="" colspan="8">
                        <center><ul id="paginacao" class="pagination-sm"></ul></center>
                        </th>
                        </tr>
                        </tfoot>

                    </table>


                    <div class="push"></div>

                    <input type="hidden" name="opcao" id="opcao" value="">
                    <input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />							
                    <input type="hidden" name="hHabilitado" id="hHabilitado" value="S">														
					<input type="hidden" name="ADM" id="ADM" value="<?=$adm?>">
					
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
	$("#DAT_INI").val("<?=fnFormatDate($dat_ini)?>");
	$("#DAT_FIM").val("<?=fnFormatDate($dat_fim)?>");

    //chosen obrigatório
//    $.fn.validator.Constructor.INPUT_SELECTOR = ':input:not([type="submit"], button):enabled, .requiredChk';
	$('#formulario').validator();
	<?php if ($cod_usuario <> ""){ ?>
		$("#formulario #COD_USUARIO").val("<?=$cod_usuario?>").trigger("chosen:updated");
	<?php } ?>


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

    $(".exportarCSV").click(function () {
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
              if (!nome) {
                $.alert('Por favor, insira um nome');
                return false;
              }

              $.confirm({
                title: 'Mensagem',
                type: 'green',
                icon: 'fa fa-check-square',
                content: function () {
                  var self = this;
                  return $.ajax({
                    url: "relatorios/ajxRelHorasTrabalhadas.do?opcao=exportar&nomeRel=" + nome,
                    data: $('#formulario').serialize(),
                    method: 'POST'
                  }).done(function (response) {
                    self.setContentAppend('<div>Exportação realizada com sucesso.</div>');
                    var fileName = nome + '.csv';
                    SaveToDisk('media/excel/' + fileName, fileName);
                    // console.log(response);
                  }).fail(function (response) {
                    self.setContentAppend('<div>Erro ao realizar o procedimento!</div>');
                    // console.log(response.responseText);
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


  });

	function retornaForm(index){
		$("#btAltHora").attr("data-url","action.do?mod=<?=fnEncode(1540)?>&id="+$("#ret_COD_HORA_"+index).val()+"&pop=true");
		$("#btAltHora").click();
	}

  function reloadPage(idPage) {
    $.ajax({
      type: "POST",
      url: "relatorios/ajxRelHorasTrabalhadas.do?opcao=paginar&idPage=" + idPage + "&itens_por_pagina=<?php echo $itens_por_pagina; ?>",
      data: $('#formulario').serialize(),
      beforeSend: function () {
        $('#relatorioConteudo').html('<div class="loading" style="width: 100%;"></div>');
      },
      success: function (data) {
        $("#relatorioConteudo").html(data);
      },
      error: function () {
        $('#relatorioConteudo').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> itens_por_pagina não encontrados...</p>');
      }
    });
  }

  function page(index) {

    $("#pagina").val(index);
    $("#formulario")[0].submit();

  }
  
  function fechaFrame(msgTipo,msgRetorno){
	var msg = "";
	if (msgTipo != undefined && msgRetorno != undefined){
		msg += "<div class='alert "+msgTipo+" alert-dismissible top30 bottom30' role='alert'>";
		msg += "<button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button>";
		msg += msgRetorno;
		msg += "</div>";
	}

	  $(".close").click();
	  reloadPage(1);
	  
	  $("#msgRetornoPopup").html(msg);
  }

</script>
