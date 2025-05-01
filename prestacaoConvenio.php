<style type="text/css">
	#blocker
	{
	    display:none; 
		position: fixed;
	    top: 0;
	    left: 0;
	    width: 100%;
	    height: 100%;
	    opacity: .8;
	    background-color: #fff;
	    z-index: 1000;
	}
	    
	#blocker div
	{
		position: absolute;
		top: 30%;
		left: 48%;
		width: 200px;
		height: 2em;
		margin: -1em 0 0 -2.5em;
		color: #000;
		font-weight: bold;
	}
</style>
<div id="blocker">
	<div style="text-align: center;"><img src="images/loading2.gif"><br/> Aguarde. Gerando PDF... ;-)<br/><small>(este processo pode demorar vários minutos)</small></div>
</div>
<?php
	
	//echo fnDebug('true');
 
    $hashLocal = mt_rand();	
	$cod_tpmodal = 0;
	
	if( $_SERVER['REQUEST_METHOD']=='POST' )
	{
		$request = md5( implode( $_POST ) );
		
		if( isset( $_SESSION['last_request'] ) && $_SESSION['last_request']== $request )
		{
			$msgRetorno = 'Essa página já foi utilizada';
			$msgTipo = 'alert-warning';
		}
		else
		{
echo "<pre>";
			print_r($_POST);
			exit;

			$_SESSION['last_request']  = $request;
			
			$cod_tpmodal;
			
			$cod_licitac = fnLimpaCampoZero($_REQUEST['COD_LICITAC']);
			$num_licitac = fnLimpaCampo($_REQUEST['NUM_LICITAC']);
			$des_licitac = fnLimpaCampo($_REQUEST['DES_LICITAC']);
			$cod_tpmodal = fnLimpaCampoZero($_REQUEST['COD_TPMODAL']);
			$num_adminis = fnLimpaCampo($_REQUEST['NUM_ADMINIS']);
			$dat_habilit = fnLimpaCampo($_REQUEST['DAT_HABILIT']);
			$dat_propost = fnLimpaCampo($_REQUEST['DAT_PROPOST']);
			$dat_edital = fnLimpaCampo($_REQUEST['DAT_EDITAL']);
			
			$opcao = $_REQUEST['opcao'];
			$hHabilitado = $_REQUEST['hHabilitado'];
			$hashForm = $_REQUEST['hashForm'];
			
			$cod_usucada = $_SESSION["SYS_COD_USUARIO"];
                      
			if ($opcao != ''){			
				
				//mensagem de retorno
				switch ($opcao)
				{
					case 'CAD':
						$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";	
						break;
					case 'ALT':
						$msgRetorno = "Registro alterado com <strong>sucesso!</strong>";		
						break;
					case 'EXC':
						$msgRetorno = "Registro excluido com <strong>sucesso!</strong>";		
						break;
					break;
				}			
				$msgTipo = 'alert-success';
			}                
		}
	}
	
	//busca dados da url	
	if (is_numeric(fnLimpacampo(fnDecode($_GET['id'])))){
            
		//busca dados da empresa
		$cod_empresa = fnDecode($_GET['id']);	
		$cod_conveni = fnDecode($_GET['idC']);

		// echo "<pre>";
		// print_r($tarefas);
		// echo "</pre>";

		$sql = "SELECT * FROM CONVENIO WHERE COD_CONVENI = ".$cod_conveni;	
			
		//fnEscreve($sql);
		$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);
		$qrBuscaTemplate = mysqli_fetch_assoc($arrayQuery);
			
		if (isset($qrBuscaTemplate)){
			$cod_conveni = $qrBuscaTemplate['COD_CONVENI'];
			$cod_entidad = $qrBuscaTemplate['COD_ENTIDAD'];
			$num_process = $qrBuscaTemplate['NUM_PROCESS'];
			$num_conveni = $qrBuscaTemplate['NUM_CONVENI'];
			$cod_tpconveni = $qrBuscaTemplate['COD_TPCONVENI'];
			$nom_conveni = $qrBuscaTemplate['NOM_CONVENI'];
			$nom_abrevia = $qrBuscaTemplate['NOM_ABREVIA'];
			$des_descric = $qrBuscaTemplate['DES_DESCRIC'];
			$val_valor = fnValor($qrBuscaTemplate['VAL_VALOR'],2);
			$val_conced = fnValor($qrBuscaTemplate['VAL_CONCED'],2);
			$val_contpar = fnValor($qrBuscaTemplate['VAL_CONTPAR'],2);
			$dat_inicinv = fnDataShort($qrBuscaTemplate['DAT_INICINV']);
			$dat_fimconv = fnDataShort($qrBuscaTemplate['DAT_FIMCONV']);
			$dat_assinat = fnDataShort($qrBuscaTemplate['DAT_ASSINAT']);
			$log_licitacao = $qrBuscaTemplate['LOG_LICITACAO'];
		
		}
		$sql = "SELECT NOM_EMPRESA FROM EMPRESAS WHERE COD_EMPRESA = ".$cod_empresa;	
				
		//fnEscreve($sql);
		$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
		$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);
			
		if (isset($qrBuscaEmpresa)){
			$nom_empresa = $qrBuscaEmpresa['NOM_EMPRESA'];
		}
												
	}else {	
		$nom_empresa = "";
	}

	$cod_conveni = fnDecode($_GET['idC']);

	//busca dados do usuário
	$cod_usucada = $_SESSION["SYS_COD_USUARIO"];
	$sql = "SELECT NOM_USUARIO FROM USUARIOS WHERE COD_USUARIO = ".$cod_usucada;	
			
	//fnEscreve($sql);
	$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
	$qrBuscaUsuario = mysqli_fetch_assoc($arrayQuery);
		
	if (isset($qrBuscaUsuario)){
		$nom_usuario = $qrBuscaUsuario['NOM_USUARIO'];
	}
	      
	//fnMostraForm();
	//fnEscreve($cod_checkli);

?>
	
<?php if ($popUp != "true"){  ?>							
	<div class="push30"></div> 
	<?php } ?>
	
	<div class="row">				
	
		<div class="col-md12 margin-bottom-30">
			<!-- Portlet -->
			<?php if ($popUp != "true"){  ?>							
			<div class="portlet portlet-bordered">
			<?php } else { ?>
			<div class="portlet" style="padding: 0 20px 20px 20px;" >
			<?php } ?>
			
				<?php if ($popUp != "true"){  ?>
				<div class="portlet-title">
					<div class="caption">
						<i class="glyphicon glyphicon-calendar"></i>
						<span class="text-primary"><?php echo $NomePg; ?> / <?php echo $nom_empresa; ?></span>
					</div>
					<?php include "atalhosPortlet.php"; ?>
				</div>
				<?php } ?>

				<div class="portlet-body">

					<div class="push30"></div> 
					
					<div class="row">  

						<div class="col-md-1">
							
							<div class="tabbable-line">
		
								<ul class="nav nav-tabs ">
									<li>
										<a href="action.do?mod=<?php echo fnEncode(1098)."&id=".fnEncode($cod_empresa)."&idC=".fnEncode($cod_conveni); ?>">
										<span class="fal fa-arrow-circle-left fa-2x"></span></a>
									</li>
								</ul>
							</div>	

						</div>
							
						<div class="col-md-4">
							<div class="form-group">
								<label for="inputName" class="control-label">Nome</label>
								<input type="text" class="form-control input-sm leitura" name="NOM_CONVENI" id="NOM_CONVENI" value="<?php echo $nom_conveni; ?>" maxlength="60" readonly>
							</div>
							<div class="help-block with-errors"></div>
						</div>


						<div class="col-md-2">
							<div class="form-group">
								<label for="inputName" class="control-label required">Data Inicial</label>
								<input type='text' class="form-control input-sm data leitura" name="DAT_INICINV" id="DAT_INICINV" value="<?=$dat_inicinv?>" readonly/>
							</div>
						</div>       
			
						<div class="col-md-2">
							<div class="form-group">
								<label for="inputName" class="control-label required">Data Final</label>
								<input type='text' class="form-control input-sm data leitura" name="DAT_FIMCONV" id="DAT_FIMCONV" value="<?=$dat_fimconv?>" readonly/>
							</div>
						</div>						
										
						<?php																	
							$sql = "SELECT * FROM ENTIDADE WHERE COD_ENTIDAD = $cod_entidad";
							$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);
							//fnEscreve($cod_entidad);
							while ($qrListaTipoEntidade = mysqli_fetch_assoc($arrayQuery))
							{
							?>

							<div class="col-md-3">
								<div class="form-group">
									<label for="inputName" class="control-label">Entidade</label>
									<input type="text" class="form-control input-sm leitura" name="NOM_CONVENI" id="NOM_CONVENI" value="<?php echo $qrListaTipoEntidade['NOM_ENTIDAD']; ?>" maxlength="60" readonly>
								</div>
								<div class="help-block with-errors"></div>
							</div>
							
							<?php 													
							}											
						?>	
									

					</div>
					
					<div class="push30"></div>
					
<link href="js/plugins/hummingbird-treeview.css" rel="stylesheet" type="text/css">

<style>
.stylish-input-group .input-group-addon{
    background: white !important;
}
.stylish-input-group .form-control{
    /*border-right:0;*/
    box-shadow:0 0 0;
    border-color:#ccc;
}
.stylish-input-group button{
    border:0;
    background:transparent;
}

.h-scroll {
    height: 260px;
    overflow-y: scroll;
}
</style>
					
					
					
					
						<form data-toggle="validator" role="form2" method="post" id="formulario_prn" a_ction="pdfPrestacaoConvenio.php?mod=<?=@$_GET["idC"]?>&id=<?=@$_GET["id"]?>&idC=<?=@$_GET["idC"]?>" target="_blank">
						<!-- inicio -->
						
					
	<div id="treeview_container" class="hummingbird-treeview h-scroll-large">

		<ul id="treeview" class="hummingbird-base">
		<?php
			$arrayConvenio = array(
								'Convênio' => ['CONVENIO','COD_CONVENI'],
								'Aditivos' => ['ADITIVOS'],
								'Contrato' => ['CONTRATO','COD_CONTRAT_CON'],
								'Dados da Entidade' => ['ENTIDADE'],
								'Dados Bancários da Entidade' => ['ENTIDADE_BANC']
							);

			$arrayLicitac = array(
								'Cotação Prévia' => [''],
								'Dados da Licitação' => ['LICITACAO','COD_LICITAC'],
								'Itens do Objeto' => ['ITENS_OBJETO','COD_OBJETO'],
								'Propostas' => ['PROPOSTAS','COD_PROPOSTA'],
								'Ata da Proposta' => ['ATA_PROPOSTA','COD_PUBLICA'],
								'Tarefas' => ['TAREFAS'],
								'Contrato da Licitação' => ['LICITACAO_CTR','COD_CONTRAT_LIC']
							);

			$arrayExec = array(
								'Créditos' => ['CREDITOS','COD_CAIXA'],
								'Subtarefas' => ['SUBTAREFAS'],
								'Boletim de Medição' => ['MEDICAO','COD_RECEBIM'],
								'Movimentação Finaneira' => ['MOVIMENTACAO','COD_EMPENHO'],
								'Pagamento' => ['PAGAMENTO','COD_PAGAMEN'],
								'Controle de Despesas' => ['DESPESAS'],
								'Estatística de Cumprimento' => ['CUMPRIMENTO'],
								'Extrato Financeiro' => ['FINANCEIRO']
							);

			$arrayDocumentos = array(
								'Convênio' => $arrayConvenio,
								'Licitação' => $arrayLicitac,
								'Execução' => $arrayExec
							);

			$count = 0;
			foreach ($arrayDocumentos as $nom_indice_principal => $itens_indice) {
				echo "<li>";
				echo "<i class='fa fa-plus'></i>
						<label>
						<input
							name='print[]'
							value='".json_encode(["titulo"=>$nom_indice_principal])."'
							type='checkbox'
						> $nom_indice_principal
						</label>";

				$count_2 = 0;
				echo "<ul>";
				foreach ($itens_indice as $nom_linha => $chave) {
					if (is_array($chave)){
						$chave_linha = @$chave[1];
					}else{
						$chave_linha = $chave;
					}

					echo "<li>";
					echo "<i class='fa fa-plus'></i>
							<label>
							<input
								name='print[]'
								value='".json_encode(["subtitulo"=>$nom_linha])."'
								type='checkbox'
							> $nom_linha
							</label>";

					echo "<ul>";

					if (@$chave[0] <> ""){
						echo "<li>";
						echo "<label> <input
										name='print[]'
										value='".json_encode(["tela"=>@$chave[0]])."'
										type='checkbox'
									> Dados Gerais</label>";
						echo "</li>";
					}

					if ($chave_linha <> ""){
						$completaCont = "  AND COD_CONTRAT_CON = 0
						AND COD_COTACAO = 0
						AND COD_LICITAC = 0
						AND COD_OBJETO = 0
						AND COD_PROPOSTA = 0
						AND COD_PUBLICA = 0
						AND COD_CONTRAT_LIC = 0
						AND COD_CAIXA = 0
						AND COD_RECEBIM = 0
						AND COD_EMPENHO = 0
						AND COD_PAGAMEN = 0
						AND COD_PROJETO = 0
					";

						$completaCont = str_replace("$chave_linha = 0", "$chave_linha != 0", $completaCont);

						$sqlDocConvenio = "SELECT AC.*, SA.DES_STATUS, US.NOM_USUARIO FROM ANEXO_CONVENIO AC 
											INNER JOIN WEBTOOLS.STATUS_ANEXO SA ON SA.COD_STATUS = AC.COD_STATUS
											LEFT JOIN WEBTOOLS.USUARIOS US ON US.COD_USUARIO = AC.COD_USUCADA
											WHERE COD_CONVENI = $cod_conveni $completaCont ORDER BY DAT_CADASTR DESC";

						//echo($sqlDocConvenio);

						$arDocConvenio = mysqli_query(connTemp($cod_empresa,''),$sqlDocConvenio);

						$count_3 = 0;

						while($qrDocConvenio = mysqli_fetch_assoc($arDocConvenio)){
							$sqlHist = "SELECT A.*,
											CASE WHEN A.COD_STATUS =3 THEN
											B.DES_JUSTIFICA
											ELSE 
											A.DES_JUSTIFICA
											END AS JUSTIFICA
											FROM HISTORICO_ANEXO A
											LEFT JOIN JUSTIFICATIVA B ON A.DES_JUSTIFICA=COD_JUSTIFICA AND A.COD_STATUS=3
											WHERE A.COD_ANEXO = $qrDocConvenio[COD_DOCUMENTO]
											ORDER BY 1 DESC
											LIMIT 1";
							$arrayHist = mysqli_query(connTemp($cod_empresa,''),$sqlHist);
							$qrHist = mysqli_fetch_assoc($arrayHist);

							if ($qrHist['COD_STATUS'] == 2){
								echo "<li>";
								echo "<label> <input
												name='print[]'
												value='".json_encode(["url"=>"https://adm.bunker.mk/media/clientes/$cod_empresa/convenios/convenio.$cod_conveni/".$qrDocConvenio['NOM_ORIGEM'],"dir"=>__DIR__."/media/clientes/$cod_empresa/convenios/convenio.$cod_conveni/".$qrDocConvenio['NOM_ORIGEM']])."'
												type='checkbox'
											> ".$qrDocConvenio['NOM_REFEREN']."</label>";
								echo "</li>";
								$count_3++;
							}
						}
					}
					echo "</ul>";

					echo "</li>";
					$count_2++;
				}
				echo "</ul>";

				echo "</li>";
				$count++;
			}
		?>
        </ul>	
	  
	  
	  
	  
	</div>
	  
	<div class="push20"></div>
	  
	<button class="btn btn-primary btn-sm" id="checkAll" onClick="return false;">Selecionar Todos</button>
	<button class="btn btn-primary btn-sm" id="uncheckAll" onClick="return false;">Desselecionar Todos</button>
	<button type="submit" name="PRN" id="PRN" class="btn btn-danger btn-sm getBtn" onClick="geraPDF();return false;"><i class="fal fa-print" aria-hidden="true"></i>&nbsp; Imprimir</button>
						
						<!-- fim -->
						</form>

																	
					
					<div class="push"></div>
					
					</div>								
				
				</div>
			</div>
			<!-- fim Portlet -->
		</div>
		
	</div>					
		
	<div class="push20"></div> 

<script src="js/plugins/hummingbird-treeview.js"></script>
<script>
$("#treeview").hummingbird();
$( "#checkAll" ).click(function() {
  $("#treeview").hummingbird("checkAll");
});
$( "#uncheckAll" ).click(function() {
  $("#treeview").hummingbird("uncheckAll");
});
$( "#collapseAll" ).click(function() {
  $("#treeview").hummingbird("collapseAll");
});
$( "#checkNode" ).click(function() {
  $("#treeview").hummingbird("checkNode",{attr:"id",name: "node-0-2-2",expandParents:false});
});

var merge_pdf = [];
function geraPDF(){
	var arr = $("#formulario_prn").serializeArray();
	var p = [];
	var prn = [];

	merge_pdf = [];

	if (arr.length <= 0){
		return false;
	}

	$('#blocker').show();

	$.each(arr, function( index, el ) {
		if (el.name == 'print[]'){
			var v = JSON.parse(el.value);
			if (v.url != undefined){
				var ext = v.url.substr((v.url.lastIndexOf('.')+1)).toLowerCase();
				if (ext == "pdf"){
					if (p.length > 0){
						prn.push(p);
					}
					p = [];
					p.push(el.value);
					prn.push(p);
					p = [];
					return true;
				}
			}
			p.push(el.value);
			
		}
	});
	if (p.length > 0){
		prn.push(p);
	}
	geraPDF_dir(prn,0);
	//prn.push(p);

	//console.log(prn)
	//action="pdfPrestacaoConvenio.php?mod=<?=@$_GET["idC"]?>&id=<?=@$_GET["id"]?>&idC=<?=@$_GET["idC"]?>"

}

function geraPDF_dir(arr,indx){
	//console.log(arr,indx,JSON.stringify(arr[indx]));
	var filename="pdf_"+indx;

	$.ajax({
		type: "POST",
		data: {"data":JSON.stringify(arr[indx])},
		url: "pdfPrestacaoConvenio.php?filename="+filename+"&mod=<?=@$_GET["idC"]?>&id=<?=@$_GET["id"]?>&idC=<?=@$_GET["idC"]?>",
		success: function(data){
			merge_pdf.push(filename);
			console.log('Gerado ',filename,data);
			if (indx+1 < arr.length){
				geraPDF_dir(arr,indx+1)
			}else{
				mergePDF();
			}
		},
		error: function(XMLHttpRequest, textStatus, errorThrown){
			console.log("erro",errorThrown);
			$('#blocker').hide();
		}
	});	

}
function mergePDF(){
	console.log(JSON.stringify(merge_pdf));
	var filename = "output";
	$.ajax({
		type: "POST",
		data: {"data":JSON.stringify(merge_pdf)},
		url: "pdfPrestacaoConvenio.php?acao=merge&filename="+filename,
		success: function(data){
			console.log('Merge ',filename,data);
			if (data != ""){
				$.confirm({
					title: 'Erro ao gerar PDF!',
					animation: 'opacity',
					closeAnimation: 'opacity',
					content: data,
					buttons: {
						ok: function () {
							
						},
					}
				});
			}else{
				$.confirm({
					title: 'PDF Gerado!',
					animation: 'opacity',
					closeAnimation: 'opacity',
					content: 'O documento foi gerado com sucesso!',
					buttons: {
						abrir: function () {
							window.open('temp_pdf/'+filename+'.pdf', '_blank');
						},
						cancelar: function () {
							
						},
					}
				});
				}
			$('#blocker').hide();
		},
		error: function(XMLHttpRequest, textStatus, errorThrown){
			console.log("erro",errorThrown);
			$('#blocker').hide();
		}
	});	
}
</script>

