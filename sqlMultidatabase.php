<?php
echo fnDebug('true');


$sql = "SELECT COD_USUARIO, NOM_USUARIO,NUM_CELULAR,DES_EMAILUS FROM usuarios where COD_USUARIO = '".$_SESSION["SYS_COD_USUARIO"]."' ";
$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
$qrBuscaUsuario = mysqli_fetch_assoc($arrayQuery);
$cod_usuario = $qrBuscaUsuario["COD_USUARIO"];
$nom_usuario = $qrBuscaUsuario["NOM_USUARIO"];
$num_celular = $qrBuscaUsuario["NUM_CELULAR"];
$des_emailus = $qrBuscaUsuario["DES_EMAILUS"];

$hashLocal = mt_rand();


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $request = md5(implode($_POST));

  if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
    $msgRetorno = 'Essa página já foi utilizada';
    $msgTipo = 'alert-warning';
  } else {
    $_SESSION['last_request'] = $request;

//    $dat_ini = fnDataSql($_POST['DAT_INI']);
//    $dat_fim = fnDataSql($_POST['DAT_FIM']);
//	$cod_usuario = $_POST["COD_USUARIO"];

    $opcao = $_REQUEST['opcao'];
    $hHabilitado = $_REQUEST['hHabilitado'];
    $hashForm = $_REQUEST['hashForm'];

    if ($opcao != '') {
      
    }
  }
}
?> 

<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/font-awesome/4.2.0/css/font-awesome.min.css">
<style>
.modal-backdrop {
    z-index: 1040 !important;
}
.modal-dialog {
    margin: 2px auto !important;
    z-index: 11000 !important;
}

.spinner-border {
    display: inline-block;
    width: 2rem;
    height: 2rem;
    vertical-align: text-bottom;
    border: .25em solid currentColor;
    border-right-color: transparent;
    border-radius: 50%;
    -webkit-animation: spinner-border .75s linear infinite;
    animation: spinner-border .75s linear infinite;
}
@keyframes spinner-border {
  to { transform: rotate(360deg); }
}

</style>


<div class="push30"></div> 

<div class="row">				

  <div class="col-md12 margin-bottom-30">
    <!-- Portlet -->
    <div class="portlet portlet-bordered">
      <div class="portlet-title">
        <div class="caption">
          <i class="fas fa-database"></i>
          <span class="text-primary"><?php echo $NomePg; ?> </span>
        </div>
        <?php include "atalhosPortlet.php"; ?>
      </div>
      <div class="portlet-body">

		  <div style="display:none;" id="msgBox" class="msgBox">
		  </div>


		<div id="msgRetornoPopup"></div>
	  
        <div class="login-form">

          <form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">


              <div class="row">             

                <div class="col-md-12">
				
                    <div class="col-md-12">
                      <div class="form-group">
                        <label for="inputName" class="control-label">SQL:</label>

                         <textarea class="form-control" style="height:200px;" name="SCRIPT_SQL" id="SCRIPT_SQL"/></textarea>

                        <div class="help-block with-errors"></div>
                      </div>
                    </div>
			
					<div class="push20"></div>


					<div>
						<div class="col-md-12">
							<h4><b>Databases</b></h4>
						</div>
					</div>
					<!--
					<div class="col-md-4 col-xs-12 col-sm-12 ">
					  <div class="push20"></div>
						<a class="btn btn-sm btn-default" href="javascript:" onclick="check_checkbox('input','T');">
							Marcar Todos
						</a> &nbsp;&nbsp;
						
						<a class="btn btn-sm btn-default" href="javascript:" onclick="check_checkbox('.DATABASE','N');">
							Desmarcar Todos
						</a> &nbsp;&nbsp;
						<a class="btn btn-sm btn-default" href="javascript:" onclick="check_checkbox('.DATABASE','I');">
							Inverter Seleção
						</a>
					</div>
					-->
					
					<div class="push"></div>
					
					<div style="column-width:300px;">
						<div class="col-md-12">
							<div class="form-group">
								<?php 
								$sql = "select DISTINCT MIN(COD_DATABASE) COD_DATABASE,COD_SERVIDOR,IP,USUARIODB,SENHADB,NOM_DATABASE,
										(select C.DES_SERVIDOR from servidores C where C.COD_SERVIDOR = A.COD_SERVIDOR ) as NOM_SERVIDOR
										from tab_database A
										WHERE COD_EMPRESA IN (SELECT COD_EMPRESA FROM empresas WHERE LOG_ATIVO='S')
										GROUP BY IP,USUARIODB,SENHADB,NOM_DATABASE order by NOM_SERVIDOR ";										
								$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());

								$count=0;
								$uitem = "";
								while ($qrListaDatabase = mysqli_fetch_assoc($arrayQuery)){
									if ($uitem <> $qrListaDatabase['NOM_SERVIDOR']){
										echo "<h4><b>".$qrListaDatabase['NOM_SERVIDOR']."</b></h4>";
										$uitem = $qrListaDatabase['NOM_SERVIDOR'];
									}
								?>
									<div class="checkbox checkbox-primary">
										<input type="checkbox" class="styled custom-control-input DATABASE" cod="<?=$qrListaDatabase['COD_DATABASE']?>" id="DATABASE_<?=$qrListaDatabase['COD_DATABASE']?>" name="DATABASE[<?=$qrListaDatabase['COD_DATABASE']?>]" value="S">
										<label class="custom-control-label" for="DATABASE_<?=$qrListaDatabase['COD_DATABASE']?>">
											<?=$qrListaDatabase['IP'] ?>
											/ <?=$qrListaDatabase['NOM_DATABASE'] ?>
										</label>
										<span style="display:none" for="DATABASE_<?=$qrListaDatabase['COD_DATABASE']?>">
											<b><?=$qrListaDatabase['NOM_SERVIDOR'] ?></b>
											(<?=$qrListaDatabase['IP'] ?>)
											/ <?=$qrListaDatabase['NOM_DATABASE'] ?>
										</span>
									</div>
									<div class="push1"></div>
								<?php
								}
								?>
							</div>
						</div>
					</div>

					<div class="push20"></div>

					<div class="col-md-4 col-xs-12 col-sm-12 ">
					  <div class="push20"></div>
						<a class="btn btn-xs btn-default" href="javascript:" onclick="check_checkbox('input','T');">
							Marcar todos
						</a> &nbsp;&nbsp;
						
						<a class="btn btn-xs btn-default" href="javascript:" onclick="check_checkbox('.DATABASE','N');">
							Desmarcar todos
						</a> &nbsp;&nbsp;
						<a class="btn btn-xs btn-default" href="javascript:" onclick="check_checkbox('.DATABASE','I');">
							Inverter seleção
						</a>
					</div>

                </div>

              </div>

			<input type="hidden" id="modalAcao">

            <div class="push20"></div>

				  <div style="display:none;" id="msgBox2" class="msgBox">
				  </div>


                <div class="col-md-2">
                  <div class="push20"></div>
					<a class="btn btn-info btn-block" id="btSQL" onClick="execSQL()">
						<i class="fas fa-database" aria-hidden="true" style="margin: 5px 0 5px 0;"></i> Executar
					</a>
                </div>

            </form>

            <div class="push"></div>

        </div>
		
		
		<div class="push20"></div>

		<div class="progress" id="progress" style="display:none">
		  <div class="progress-bar" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
		</div>
		<div id="log_sql"></div>

      </div>
    </div>
    <!-- fim Portlet -->

	
  </div>

</div>	


<!-- modal -->
<div class="modal fade" id="modalToken" tabindex="-1" role="dialog" aria-labelledby="modalToken" >
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Chave de Segurança</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">

		<div class="col-md-12">
		  <div class="form-group">
			<label for="inputName" class="control-label">Digite a chave de segurança,
				<?=($num_celular <> ""?" enviada por SMS para o número <b>".$num_celular."</b>":"")?>
				<?=($des_emailus <> ""?($num_celular <> ""?", e ":"")." enviada para o e-mail <b>".$des_emailus."</b>":"")?>, para executar este script:</label>

			 <input class="form-control" name="TOKEN" id="TOKEN">

			<div class="help-block with-errors"></div>
		  </div>
		</div>
		
		<div class="push20"></div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-primary" onClick="$('#modalAcao').val('CON');" data-dismiss="modal">Confirmar</button>
      </div>
    </div>
  </div>
</div>
	


<div class="push20"></div>

<script type="text/javascript" src="js/plugins/datepicker/moment.js"></script>
<script type="text/javascript" src="js/plugins/datepicker/moment-pt-br.js"></script>
<script type="text/javascript" src="js/plugins/datepicker/bootstrap-datetimepicker.min.js"></script>
<link rel="stylesheet" href="css/bootstrap-datetimepicker.css" />


<script type="text/javascript">

	var token = "";
	$(document).ready(function () {

	});

	function block(acao){
		if (acao == true){
			$("#btSQL").attr("disabled",true);
		}else{
			$("#btSQL").removeAttr("disabled");
		}
	}
	
	function execSQL(){
		$(".msgBox").hide();
		$("#log_sql").html("");
		$("#progress").hide();
		$("#progress .progress-bar").attr("style","width: 0%");
		if ($("#btSQL").attr("disabled") == "disabled"){
			return false;
		}
		if ($.trim($("#SCRIPT_SQL").val()) == ""){
			msgBox("alert-warning","SQL não informado!");
			$("#SCRIPT_SQL").focus();
			return false;
		}
		if ($(".DATABASE:checked").length <= 0){
			msgBox("alert-warning","Escolha o(s) banco(s) de dados para aplicar o script!");
			return false;
		}

		block(true);

		geraToken(function(){
			validaToken(function(){
				$("#progress").show();
				$(".DATABASE:checked").each(function() {
					id = this.id;
					cod = $("#"+id).attr("cod");
					html = "";
					html += "<div id='log_"+id+"' cod="+cod+" class='itemlog pendente'>"+
								"<h4><i class='spinner-border'></i> "+$("span[for="+id+"]").html()+"</h4>"+
								"<div class='retornoSQL'>"+
								"</div>"+
							"</div>";
					$("#log_sql").html($("#log_sql").html()+html);
				});

				var ajxSQLT = setInterval(ajxSQL, 100);
				function ajxSQL() {
					if ($("#log_sql .processando").length > 0){
						return false;
					}
					if ($("#log_sql .pendente").length <= 0){
						block(false);
						clearInterval(ajxSQLT);
						return false;
					}

					var id = "#"+$("#log_sql .pendente").first().attr("id");
					$(id).removeClass("pendente");
					$(id).addClass("processando");
					var cod = $(id).attr("cod");

					$.ajax({
						type: "POST",
						url: "ajxMultidatabase.php?acao=executa",
						data: "token="+token+"&bd="+cod,
						success: function (data) {
							$(id+" .retornoSQL").html(data);
							processaItem(id);
						},
						error: function(XMLHttpRequest, textStatus, errorThrown){
							$(id+" .retornoSQL").html("<span class='text-danger'>"+errorThrown+"</span>");
							processaItem(id);
						}
					});
				}
				function processaItem(id){
					$(id+" h4 i").removeClass();
					$(id+" h4 i").addClass("fas fa-check");
					$(id).removeClass("processando");
					$(id).addClass("concluido");
					
					var pc = $("#log_sql .concluido").length / $("#log_sql .itemlog").length * 100;
					$("#progress .progress-bar").attr("style","width: "+pc+"%");
					$("#progress .progress-bar").attr("aria-valuenow",pc);
				}

			})
		});
	}
	
	function msgBox(msgTipo,msgTexto){
		var html = "<div class='alert "+msgTipo+" alert-dismissible top30 bottom30' role='alert' id='msgRetorno'>";
		html = html + "<button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button>";
		html = html + "<div class='msgTexto'>"+msgTexto+"</div>";
		html = html + "</div>";
		$(".msgBox").html(html);
		$(".msgBox").show();
	}
  
	function geraToken(callback){
		$("#modalAcao").val("");
		$("#TOKEN").val("");
		token = "";

		$.ajax({
			type: "POST",
			url: "ajxMultidatabase.php?acao=token",
			data: $("#formulario").serialize(),
			success: function (data) {
				if ($.trim(data) != "ok"){
					msgBox("alert-danger",data);
					block(false);
				}else{
					$("#modalToken").modal("show");
					$("#modalToken").appendTo("body");

					var popAtivoT = setInterval(popAtivo, 100);
					function popAtivo() {
						$("#TOKEN").focus();
						if (!$(".modal-backdrop").is(":visible")){
							clearInterval(popAtivoT);
							if ($("#modalAcao").val() == "CON") {
								token = $("#TOKEN").val();
								if (typeof callback == "function"){
									callback.call(this);
								}
							}else{
								block(false);
							}
						}
					}
				}
			},
			error: function(XMLHttpRequest, textStatus, errorThrown){
				msgBox("alert-danger",errorThrown);
				block(false);
			}
		});

	}

	function validaToken(callback){
		$.ajax({
			type: "POST",
			url: "ajxMultidatabase.php?acao=valida",
			data: "token="+token,
			success: function (data) {
				if ($.trim(data) != "ok"){
					msgBox("alert-danger",data);
					block(false);
				}else{
					if (typeof callback == "function"){
						callback.call(this);
					}
				}
			},
			error: function(XMLHttpRequest, textStatus, errorThrown){
				msgBox("alert-danger",errorThrown);
				block(false);
			}
		});
	}

	function check_checkbox(check,acao = "I"){
		if (acao == "T" || acao == "N"){
			$(check).prop("checked", (acao == "T"));
		}else{
			$(check).each(function() {
				$(this).prop("checked", !(this.checked));
			});
		}
	}

</script>
