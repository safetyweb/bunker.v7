<?php
	
	//echo fnDebug('true');

	$connAdmSACV = $connAdmSAC->connAdm();

	$hashLocal = mt_rand();
	if(isset($_GET['idC'])){
		$cod_chamado = fnLimpaCampoZero(fnDecode($_GET['idC']));
	}else{
		$cod_chamado = 0;
	}
	$des_email = "";
	$num_telefone = "";
	$cod_usucada = $_SESSION["SYS_COD_USUARIO"];
	$cod_usuario = $cod_usucada;
	
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
			$_SESSION['last_request']  = $request;
			
			$cod_chamado = fnLimpacampo($_REQUEST['COD_CHAMADO']);
			$nom_chamado = fnLimpacampo($_REQUEST['NOM_CHAMADO']);
			$cod_empresa = fnLimpacampo($_REQUEST['COD_EMPRESA']);
			$dat_cadastr = fnDateSql(fnLimpacampo($_REQUEST['DAT_CADASTR']));
			$dat_chamado = fnDataSql(fnLimpacampo($_REQUEST['DAT_CHAMADO']));
			$cod_externo = fnLimpacampo($_REQUEST['COD_EXTERNO']);
			$url = fnLimpacampo($_REQUEST['URL']);
			$des_email = fnLimpacampo($_REQUEST['DES_EMAIL']);
			$num_telefone = fnLimpacampo($_REQUEST['NUM_TELEFONE']);
			$cod_status = fnLimpacampoZero($_REQUEST['COD_STATUS']);
			$cod_integradora = fnLimpacampoZero($_REQUEST['COD_INTEGRADORA']);
			$cod_tpsolicitacao = fnLimpacampoZero($_REQUEST['COD_TPSOLICITACAO']);
			$cod_versaointegra = fnLimpacampoZero($_REQUEST['COD_VERSAOINTEGRA']);
			$cod_prioridade = fnLimpacampoZero($_REQUEST['COD_PRIORIDADE']);
			$des_sac = addslashes(htmlentities($_REQUEST['DES_SAC']));
			$sac_anexo = fnLimpacampo($_REQUEST['SAC_ANEXO']);
			$cod_refdown = fnLimpacampo($_REQUEST['COD_REFDOWN']);
			$primeiroUp = fnLimpaCampo($_REQUEST['PRIMEIRO_UP']);

			if (isset($_POST['COD_USUARIOS_ENV'])){
				$Arr_COD_USUARIOS_ENV = $_POST['COD_USUARIOS_ENV'];			 
				 
				   for ($i=0;$i<count($Arr_COD_USUARIOS_ENV);$i++) 
				   { 
					$cod_usuarios_env = $cod_usuarios_env.$Arr_COD_USUARIOS_ENV[$i].",";
				   }				   
				   $cod_usuarios_env = substr($cod_usuarios_env,0,-1);					
			}else{$cod_usuarios_env = "0";}

			if (isset($_POST['COD_CONSULTORES'])){
				$Arr_COD_CONSULTORES = $_POST['COD_CONSULTORES'];			 
				 
				   for ($i=0;$i<count($Arr_COD_CONSULTORES);$i++) 
				   { 
					$cod_consultores = $cod_consultores.$Arr_COD_CONSULTORES[$i].",";
				   } 				   
				   $cod_consultores = substr($cod_consultores,0,-1);					
			}else{$cod_consultores = "0";}

			//busca revendas do usuário
			include "unidadesAutorizadas.php";

			// if (isset($_POST['COD_UNIVEND'])){
			// 	$Arr_COD_UNIVEND = $_POST['COD_UNIVEND'];			 
				 
			// 	   for ($i=0;$i<count($Arr_COD_UNIVEND);$i++) 
			// 	   { 
			// 		$cod_univend = $cod_univend.$Arr_COD_UNIVEND[$i].",";
			// 	   } 				   
			// 	   $cod_univend = substr($cod_univend,0,-1);					
			// }else{$cod_univend = "0";}

			$opcao = $_REQUEST['opcao'];
			$hHabilitado = $_REQUEST['hHabilitado'];
			$hashForm = $_REQUEST['hashForm'];

			$zero = "0";
			$default = "3";
			
			//fnEscreve($opcao);
						
			if ($opcao != ''){

				if($opcao == 'CAD'){

				$msgChamado = "Chamado aberto em <i>".date('d/m/Y H:i:s')."</i>";					
					
				$sql = "INSERT INTO SAC_CHAMADOS(
									NOM_CHAMADO,
									COD_EMPRESA,
									DAT_CADASTR,
									DAT_CHAMADO,
									COD_EXTERNO,
									COD_STATUS,
									URL,
									DES_EMAIL,
									NUM_TELEFONE,
									COD_TPSOLICITACAO,
									DES_SAC,
									COD_USUARIO,
									COD_USURES,
									COD_USUARIOS_ENV,
									COD_CONSULTORES,
									COD_UNIVEND,
									USU_CADASTR,
									LOG_ANALISE
									) VALUES(
									'$nom_chamado',
									'$cod_empresa',
									 $dat_cadastr,
									'$dat_chamado',
									'$cod_externo',
									 12,
									'$url',
									'$des_email',
									'$num_telefone',
									'$cod_tpsolicitacao',
									'$des_sac',
									'$cod_usuario',
									 0,
									'$cod_usuarios_env',
									'$cod_consultores',
									'$lojasSelecionadas',
									'$cod_usuario',
									'S'
									);

						INSERT INTO SAC_COMENTARIO(
									COD_CHAMADO,
									DES_COMENTARIO,
									TP_COMENTARIO,
									COD_EMPRESA,
									COD_USUARIO,
									DAT_CADASTRO,
									COD_COR,
									COD_STATUS
									) VALUES(
									(SELECT MAX(COD_CHAMADO) FROM SAC_CHAMADOS WHERE COD_EMPRESA = $cod_empresa AND USU_CADASTR = $cod_usucada),
									'$msgChamado',
									 1,
									'$cod_empresa',
									$cod_usucada,
									$dat_cadastr,
									'',
									12
									 );

						UPDATE SAC_ANEXO SET 
								   COD_CHAMADO = (SELECT MAX(COD_CHAMADO) FROM SAC_CHAMADOS WHERE COD_EMPRESA = $cod_empresa AND USU_CADASTR = $cod_usucada)
								   WHERE 
								   COD_REFDOWN = $cod_refdown
								   ";

					// fnEscreve($sql);
					
					//fnTestesql($connAdm->connAdm(), $sql);
					mysqli_multi_query($connAdmSAC->connAdm(),$sql) or die(mysqli_error());
					
					//busca id do chamado
					// $sql = "SELECT MAX(COD_CHAMADO) as COD_CHAMADO FROM SAC_CHAMADOS where COD_EMPRESA = '".$cod_empresa."' ";
					// $arrayQuery = mysqli_query($connAdmSACV,$sql) or die(mysqli_error());
					// $qrBuscaId = mysqli_fetch_assoc($arrayQuery);
					// $cod_chamado = $qrBuscaId['COD_CHAMADO'];
					// //fnEscreve($cod_chamado);

					

					

					// mysqli_query(connTemp($cod_empresa,''),$sql);
					// fnEscreve($sql);
					
					
				}
				elseif($opcao == 'EXC'){
				$sql = "DELETE FROM SAC_CHAMADOS WHERE COD_CHAMADO = $cod_chamado";
				mysqli_query($connAdmSACV,$sql) or die(mysqli_error()); 

				}
				else{

				if($cod_status == 14){
					$cod_status = 15;
				}

				 $sql = "UPDATE SAC_CHAMADOS SET
				 				NOM_CHAMADO='$nom_chamado',
								DAT_CADASTR=$dat_cadastr,
								DAT_CHAMADO='$dat_chamado',
								COD_EXTERNO='$cod_externo',
								URL='$url',
								DES_EMAIL='$des_email',
								NUM_TELEFONE='$num_telefone',
								COD_TPSOLICITACAO='$cod_tpsolicitacao',
								DES_SAC='$des_sac',
								SAC_ANEXO='$sac_anexo',
								COD_STATUS='$cod_status',
								COD_USUARIO='$cod_usuario',
								COD_USUARIOS_ENV='$cod_usuarios_env',
								COD_CONSULTORES='$cod_consultores',
								COD_UNIVEND='$lojasSelecionadas'
								WHERE COD_CHAMADO = $cod_chamado";
									
					mysqli_query($connAdmSACV,$sql) or die(mysqli_error());
					//fnTestesql($connAdmSACV, $sql);
					//fnMostraForm('#formulario');
					//fnEscreve($sql);

				}			
				
				//mensagem de retorno
				switch ($opcao) 
				{
					case 'CAD':
						$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
						$tipo_email = "Criado";
						$novo_chamado = "Novo ";
						break;
					case 'ALT':
						$msgRetorno = "Registro alterado com <strong>sucesso!</strong>";
						$tipo_email = "Alterado";
						$novo_chamado = "Atualização - ";		
						break;
					case 'EXC':
						$msgRetorno = "Registro excluido com <strong>sucesso!</strong>";		
						break;
					break;
				}

				$cod_chamado_sql = $cod_chamado;

				/////////////////--Envio do Email--/////////////////
				/**/       include 'envioEmailSac.php';		    /**/
				////////////////////////////////////////////////////

				$msgTipo = 'alert-success';
				
			}  	

		}
	}

	
	//busca dados da url - empresa
	if (is_numeric(fnLimpacampo(fnDecode($_GET['id'])))){
		//busca dados da empresa
		$cod_empresa = fnDecode($_GET['id']);	
		$sql = "SELECT COD_EMPRESA, NOM_FANTASI, COD_CONSULTOR FROM empresas where COD_EMPRESA = '".$cod_empresa."' ";
		//fnEscreve($sql);
		$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
		$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);
		
		if (isset($arrayQuery)){
			$cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
			$nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];
			$cod_consultor = $qrBuscaEmpresa['COD_CONSULTOR'];
		}
												
	}else {
		$cod_empresa = 0;		
	}
		
	if ($cod_chamado != 0) {
		$sqlSac = "SELECT * FROM SAC_CHAMADOS WHERE COD_CHAMADO = '".$cod_chamado."' ";
		$sqlSac = mysqli_query($connAdmSACV,$sqlSac) or die(mysqli_error());
		$qrSac = mysqli_fetch_assoc($sqlSac);

		if(isset($qrSac)){

		$cod_chamado = $qrSac['COD_CHAMADO'];
		$nom_chamado = $qrSac['NOM_CHAMADO'];
		$dat_cadastr = fnDataFull($qrSac['DAT_CADASTR']);
		$dat_chamado = fnDateRetorno($qrSac['DAT_CHAMADO']);
		$cod_externo = $qrSac['COD_EXTERNO'];
		$cod_status = $qrSac['COD_STATUS'];
		$cod_tpsolicitacao = $qrSac['COD_TPSOLICITACAO'];
		$url = $qrSac['URL'];
		$cod_univend = $qrSac['COD_UNIVEND'];
		$cod_usuarios_env = $qrSac['COD_USUARIOS_ENV'];
		$des_sac = $qrSac['DES_SAC'];
		$sac_anexo = $qrSac['SAC_ANEXO'];
		}

	}else {
		$cod_externo = "";
		$dat_cadastr = (new \DateTime())->format('d/m/Y H:i:s');
		$dat_chamado = (new \DateTime())->format('d/m/Y');
		$nom_chamado = "";
		$cod_tpsolicitacao = "0";
		$cod_status = "0";
		$url = "";
		$cod_univend = "0";
		$cod_usuarios_env = "0";
		$des_sac = "";
		$sac_anexo = "Sem Anexo";
	}
	
	//fnEscreve($cod_empresa);	
	//fnEscreve($nom_empresa);	
	//fnMostraForm();

	$sql = "SELECT NOM_USUARIO, DES_EMAILUS, NUM_CELULAR, NUM_TELEFON FROM USUARIOS WHERE COD_USUARIO = $cod_usuario";
	$qrUsu = mysqli_fetch_assoc(mysqli_query($connAdm->connAdm(),$sql));
	// fnEscreve($cod_usuario);

	if($des_email == ""){
		$des_email = $qrUsu['DES_EMAILUS'];
	}
	
	if($num_telefone == ""){
		
		if($qrUsu['NUM_CELULAR'] != ""){
			$num_telefone = $qrUsu['NUM_CELULAR'];
		}else{
			$num_telefone = $qrUsu['NUM_TELEFON'];
		}
	
	}

	if($cod_chamado == 0){

		$sql = "UPDATE CONTADOR SET NUM_CONTADOR = (NUM_CONTADOR+1) WHERE COD_CONTADOR = 2";
		mysqli_query($connAdmSAC->connAdm(),$sql) or die(mysqli_error());

		$sql = "SELECT NUM_CONTADOR FROM CONTADOR WHERE COD_CONTADOR = 2";
		$arrayQuery = mysqli_query($connAdmSACV,$sql) or die(mysqli_error());
		$qrCont = mysqli_fetch_assoc($arrayQuery);

		$conta = $qrCont['NUM_CONTADOR'];
		$primeiroUp = "S";

	}else{

		$sql = "SELECT COD_REFDOWN FROM SAC_ANEXO WHERE COD_CHAMADO = $cod_chamado";
		$arrayQuery = mysqli_query($connAdmSACV,$sql) or die(mysqli_error());
		$qrCont = mysqli_fetch_assoc($arrayQuery);

		if(!isset($qrCont['COD_REFDOWN'])) {

			$sql = "UPDATE CONTADOR SET NUM_CONTADOR = (NUM_CONTADOR+1) WHERE COD_CONTADOR = 2";
			mysqli_query($connAdmSAC->connAdm(),$sql) or die(mysqli_error());

			$sql = "SELECT NUM_CONTADOR FROM CONTADOR WHERE COD_CONTADOR = 2";
			$arrayQuery = mysqli_query($connAdmSACV,$sql) or die(mysqli_error());
			$qrCont = mysqli_fetch_assoc($arrayQuery);

			$conta = $qrCont['NUM_CONTADOR'];
			$primeiroUp = "S";

		}else { 
			$conta = $qrCont['COD_REFDOWN']; 
			$primeiroUp = "N";
		}

	}

	// fnEscreve($cod_chamado);

?>

<style>
	
.leitura2{
	border: none transparent !important;
	outline: none !important;
	background: #fff !important;
	font-size: 18px;
	padding: 0;
}

.collapse-chevron .fa {
  transition: .3s transform ease-in-out;
}

.collapse-chevron .collapsed .fa {
  transform: rotate(-90deg);
}

.collapse-plus .fas {
  transition: .2s transform ease-in-out;
}

.collapse-plus .collapsed .fas {
  transform: rotate(45deg);
}

.area {
  width: 100%;
  padding: 7px;
}

#dropZone {
  display: block;
  border: 2px dashed #bbb;
  -webkit-border-radius: 5px;
  border-radius: 5px;
  margin-left: -7px;
}

#dropZone p{
	font-size: 10pt;
	letter-spacing: -0.3pt;
	margin-bottom: 0px;
}

#dropzone .fa{
	font-size: 15pt;
}

.jqte {
    border: #dce4ec 2px solid!important;
    border-radius: 3px!important;
    -webkit-border-radius: 3px!important;    
    box-shadow: 0 0 2px #dce4ec!important;
    -webkit-box-shadow: 0 0 0px #dce4ec!important;
    -moz-box-shadow: 0 0 3px #dce4ec!important;    
    transition: box-shadow 0.4s, border 0.4s;
    margin-top: 0px!important;
    margin-bottom: 0px!important;
}

.jqte_toolbar {   
    background: #fff!important;
    border-bottom: none!important;
}

.jqte_focused {
	border: none!important;
	box-shadow:0 0 3px #00BDFF; -webkit-box-shadow:0 0 3px #00BDFF; -moz-box-shadow:0 0 3px #00BDFF;
}

.jqte_titleText {
	border: none!important;
	border-radius:3px; -webkit-border-radius:3px; -moz-border-radius:3px;
	word-wrap:break-word; -ms-word-wrap:break-word
}

.jqte_tool, .jqte_tool_icon, .jqte_tool_label{
	border: none!important;
}

.jqte_tool_icon:hover{
	border: none!important;
	box-shadow: 1px 5px #EEE;
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
										<span class="text-primary"><?php echo $NomePg; ?> <?php echo $nom_empresa; ?></span>
									</div>
									
									<?php 
									$formBack = "1280";
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

									<?php $abaInfoSuporte = 1278; 
									include "abasInfoSuporteEmpresa.php"; ?>
									
									<div class="push20"></div> 
			
									<div class="login-form">
									
										<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

											<fieldset>
												<legend>Dados do Chamado</legend>
											
												<div class="row" >

													<div class="col-md-3">
														<div class="form-group">
															<label for="inputName" class="control-label">Solicitante (Usuário)</label>
															<input type="text" class="form-control input-sm leitura2" readonly="readonly" name="NOM_USUARIO" id="NOM_USUARIO" value="<?=$qrUsu['NOM_USUARIO']?>">
														</div>
													</div>
													
													<div class="col-md-3">
														<div class="form-group">
															<label for="inputName" class="control-label">Data de Cadastro</label>
															<input type="text" class="form-control input-sm leitura2" readonly="readonly" name="DAT_CADASTR" id="DAT_CADASTR" value="<?php echo $dat_cadastr; ?>" required>
														</div>
													</div>

												</div>

												<div class="push10"></div>

												<div class="row">

													<div class="col-md-3">
														<div class="form-group">
															<label for="inputName" class="control-label">Título do Chamado</label>
															<input type="text" class="form-control input-sm" name="NOM_CHAMADO" id="NOM_CHAMADO" maxlength="100" value="<?php echo $nom_chamado; ?>" required>
														</div>
													</div>

													<div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label required">Tipo de Solicitação</label>
															<select class="chosen-select-deselect requiredChk" data-placeholder="Selecione o tipo" name="COD_TPSOLICITACAO" id="COD_TPSOLICITACAO" required>
																<?php 
																	
																		$sql = "SELECT * FROM SAC_TPSOLICITACAO WHERE COD_TPSOLICITACAO IN(1,3,5) ";
																		$arrayQuery = mysqli_query($connAdmSACV,$sql) or die(mysqli_error());
																	
																		while ($qrSolicitacao = mysqli_fetch_assoc($arrayQuery))
																		  {
																		  	?>
																		  	<option value="<?php echo $qrSolicitacao['COD_TPSOLICITACAO']; ?>"><?php echo $qrSolicitacao['DES_TPSOLICITACAO']; ?></option>
																		  	<?php } ?>
															</select>
															<div class="help-block with-errors"></div>
														</div>
													</div>

													<div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label required">Data da Ocorrência</label>
															
															<div class="input-group date datePicker" id="DAT_CHAMADO_GRP">
																<input type='text' class="form-control input-sm data" name="DAT_CHAMADO" id="DAT_CHAMADO" value="<?php echo $dat_chamado; ?>" required/>
																<span class="input-group-addon">
																	<span class="glyphicon glyphicon-calendar"></span>
																</span>
															</div>
															<div class="help-block with-errors"></div>
														</div>
													</div>											

													<div class="col-md-3">
														<div class="form-group">
															<label for="inputName" class="control-label">Url</label>
															<input type="text" class="form-control input-sm" name="URL" id="URL" maxlength="200" value="<?php echo $url; ?>">
														</div>
													</div>

													<div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label required">Email</label>
																<input type="text" class="form-control input-sm" name="DES_EMAIL" id="DES_EMAIL" maxlength="70" value="<?php echo $des_email; ?>" required>
															<div class="help-block with-errors"></div>
														</div>
													</div>

												</div>

												<div class="push10"></div>

												<div class="row">

													<div class="col-md-1">
														<div class="form-group">
															<label for="inputName" class="control-label">Código Externo</label>
															<input type="text" class="form-control input-sm" name="COD_EXTERNO" id="COD_EXTERNO" maxlength="45" value="<?php echo $cod_externo; ?>">
														</div>
													</div>


													<div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label required">Telefone</label>
																<input type="text" class="form-control input-sm phone" name="NUM_TELEFONE" id="NUM_TELEFONE" maxlength="15" value="<?php echo $num_telefone; ?>" required>
															<div class="help-block with-errors"></div>
														</div>
													</div>

													<div class="col-md-3">
														<div class="form-group">
															<label for="inputName" class="control-label required">Unidades Envolvidas</label>
																<?php include "unidadesAutorizadasComboMulti.php"; ?>
															<div class="help-block with-errors">Unidades autorizadas para o usuário</div>									
														</div>
													</div>

													<div class="col-md-3">
														<div class="form-group">
															<label for="inputName" class="control-label required">Usuários Envolvidos</label>
															
																<select data-placeholder="Selecione um usuários" name="COD_USUARIOS_ENV[]" id="COD_USUARIOS_ENV" multiple="multiple" class="chosen-select-deselect requiredChk" style="width:100%;" tabindex="1">
																	<?php 
																	
																		$sql = "select COD_USUARIO, NOM_USUARIO from usuarios 
																		where usuarios.COD_EMPRESA = $cod_empresa
																		and usuarios.DAT_EXCLUSA is null order by  usuarios.NOM_USUARIO ";
																		$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
																	
																		while ($qrLista = mysqli_fetch_assoc($arrayQuery))
																		  {														
																			echo"
																				  <option value='".$qrLista['COD_USUARIO']."'>".$qrLista['NOM_USUARIO']."</option> 
																				"; 
																			  }											
																	?> 
																</select>
															<div class="help-block with-errors"></div>
														</div>
													</div>

													<div class="col-md-3">
														<div class="form-group">
															<label for="inputName" class="control-label">Consultores Envolvidos</label>
																<select data-placeholder="Selecione um consultor" name="COD_CONSULTORES[]" id="COD_CONSULTORES" multiple="multiple" class="chosen-select-deselect requiredChk" tabindex="1">
																	<?php 
																	
																		$sql = "select COD_USUARIO, NOM_USUARIO from usuarios 
																		where usuarios.COD_EMPRESA = 3
																		and usuarios.DAT_EXCLUSA is null order by  usuarios.NOM_USUARIO ";
																		$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
																	
																		while ($qrLista = mysqli_fetch_assoc($arrayQuery))
																		  {														
																			echo"
																				  <option value='".$qrLista['COD_USUARIO']."'>".$qrLista['NOM_USUARIO']."</option> 
																				"; 
																			  }											
																	?> 
																</select>
															<div class="help-block with-errors"></div>
															<script type="text/javascript">$('#COD_CONSULTORES').val("<?=$cod_consultor?>").trigger('chosen:updated');</script>
														</div>
													</div>

												</div>

												<div class="push10"></div>
												
												<div class="row">
													<div class="col-lg-12">
														<div class="form-group">
															<label for="inputName" class="control-label required">Descrição: </label>
															<textarea class="editor form-control input-sm" rows="6" name="DES_SAC" id="DES_SAC"><?php echo $des_sac; ?></textarea>
															<div class="help-block with-errors"></div>
														</div>
													</div>
												</div>

											</fieldset>

											<div class="push10"></div>

											<div class="row">															

												<div class="col-md-2" style="margin-right: -43px;">
													<div class="collapse-chevron">
														<a data-toggle="collapse" class="collapsed btn btn-sm btn-default" href="#collapseFilter" style="width: 90%;">
													    	<span class="fa fa-chevron-down" aria-hidden="true"></span>&nbsp;
													    	Visualizar Anexos 
														</a>
													</div>
												</div>

												<div class="col-md-2">
													<div class="collapse-plus">
														<a data-toggle="collapse" class="collapsed btn btn-sm btn-success" href="#collapseFilter2" style="width: 90%;">
													    	<span class="fas fa-times" aria-hidden="true"></span>&nbsp;
													    	Criar Novo Anexo
														</a>
													</div>
												</div>										

											</div>

											<div class="row">

												<div class="col-md-4">
													<?php include "addAnexoSac.php"; ?>
												</div>

											</div>

											<div class="row">

												<div class="col-md-4">
													<?php include "listaUploadSac.php"; ?>
												</div>

											</div>
																				
										<div class="push10"></div>
										<hr>	
										<div class="form-group text-right col-lg-12">

											<a href="action.php?mod=<?php echo fnEncode(1280);?>&id=<?php echo fnEncode($cod_empresa);?>" name="ADD" id="ADD" class="btn btn-default pull-left" style="margin-right: 5px;"><i class="fa fa-arrow-left" aria-hidden="true"></i>&nbsp; Voltar à Lista</a>
											
											<button type="reset" class="btn btn-default"><i class="fa fa-star-half-o" aria-hidden="true"></i>&nbsp; Apagar</button>
											<?php if ($cod_chamado == 0) { ?>
											<button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp; Cadastrar Chamado</button>
											<?php } else { ?>
											<button type="submit" name="ALT" id="ALT" class="btn btn-primary getBtn"><i class="fa fa-repeat" aria-hidden="true"></i>&nbsp; Alterar</button>
											<?php } ?>
											
										</div>
										
										<input type="hidden" name="COD_CHAMADO" id="COD_CHAMADO" value="<?php echo $cod_chamado; ?>">
										<input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa; ?>">
										<input type="hidden" name="opcao" id="opcao" value="">
										<input type="hidden" name="COD_STATUS" id="COD_STATUS" value="<?php echo $cod_status; ?>">
										<input type="hidden" name="COD_REFDOWN" id="COD_REFDOWN" value="<?php echo $conta; ?>">
										<input type="hidden" name="PRIMEIRO_UP" id="PRIMEIRO_UP" value="<?php echo $primeiroUp; ?>">
										<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />	
										<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">
										
										<div class="push5"></div> 
										
										</form>
										 
										<div class="push50"></div>
										
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
	<link rel="stylesheet" type="text/css" href="js/plugins/jQuery-TE/jquery-te-1.4.0.css">
	<link rel="stylesheet" type="text/css" href="js/plugins/jQuery-TE/jquery-te.png">
	<script type="text/javascript" src="js/plugins/jQuery-TE/jquery-te-1.4.0.min.js"></script>

	<script>

		function retornaForm(index){
			$("#formulario #COD_TPSOLICITACAO").val(<?php echo $cod_tpsolicitacao; ?>).trigger("chosen:updated");
			
			//retorno combo multiplo - lojas
			$("#formulario #COD_UNIVEND").val('').trigger("chosen:updated");

				var sistemasUni = '<?php echo $cod_univend; ?>';				
				var sistemasUniArr = sistemasUni.split(',');				
				//opções multiplas
				for (var i = 0; i < sistemasUniArr.length; i++) {
				  $("#formulario #COD_UNIVEND option[value=" + sistemasUniArr[i] + "]").prop("selected", "true");				  
				}
				$("#formulario #COD_UNIVEND").trigger("chosen:updated");    
			

				//retorno combo multiplo - USUARIOS_ENV
			$("#formulario #COD_USUARIOS_ENV").val('').trigger("chosen:updated");

				var sistemasUni = '<?php echo $cod_usuarios_env; ?>';				
				var sistemasUniArr = sistemasUni.split(',');				
				//opções multiplas
				for (var i = 0; i < sistemasUniArr.length; i++) {
				  $("#formulario #COD_USUARIOS_ENV option[value=" + sistemasUniArr[i] + "]").prop("selected", "true");				  
				}
				$("#formulario #COD_USUARIOS_ENV").trigger("chosen:updated");
			
			// $('#formulario').validator('validate');			
			$("#formulario #hHabilitado").val('S');						
		}
		

		$(document).ready(function(){

			retornaForm(0);

			// TextArea
			$(".editor").jqte(
				{sup: false,
				sub: false,
				outdent: false,
				indent: false,
				left: false,
        		center: false,
        		color: false,
        		right: false,
        		strike: false,
        		source: false,
		        link:false,
		        unlink: false,		        
		        remove: false,
		    	rule: false,
		    	fsize: false,
		    	format: false,
		    });
			
			$('.datePicker').datetimepicker({
				 format: 'DD/MM/YYYY',
				 maxDate : 'now',
				}).on('changeDate', function(e){
					$(this).datetimepicker('hide');
				});
				
			//chosen obrigatório
			$.fn.validator.Constructor.INPUT_SELECTOR = ':input:not([type="submit"], button):enabled, .requiredChk';
			$('#formulario').validator();

		});

	$('.upload').on('click', function (e) {
        var idField = 'arqUpload_' + $(this).attr('idinput');
        var typeFile = $(this).attr('extensao');

        $.dialog({
            title: 'Arquivo',
            content: '' +
                    '<form method = "POST" enctype = "multipart/form-data">' +
                    '<input id="' + idField + '" type="file" name="image" style="margin-bottom: 20px;" />' +
                    '<div class="progress" style="display: none">' +
                    '<div class="progress-bar progress-bar-info progress-bar-striped" role="progressbar" aria-valuemin="0" aria-valuemax="100" style="width:0%;">'+
                    '   <span style="position: absolute; display: block; width: 100%; color:#2c3e50;">12</span></div>' +
                    '</div>' +
                    '<a type="button" id="btnUploadFile" class="btn btn-primary btn-sm" style="font-weight: bold" onClick="uploadFile(\'' + idField + '\', \'' + typeFile + '\')">UPLOAD</a>' +
                    '</form>'
        });
    });

    function uploadFile(idField, typeFile) {
        var formData = new FormData();
        var nomeArquivo = $('#' + idField)[0].files[0]['name'];

        formData.append('arquivo', $('#' + idField)[0].files[0]);
        formData.append('diretorio', '../media/clientes/');
        formData.append('diretorioAdicional', 'helpdesk');
        formData.append('id', <?php echo $cod_empresa ?>);
        formData.append('typeFile', typeFile);

        $('.progress').show();
        $.ajax({
            xhr: function () {
                var xhr = new window.XMLHttpRequest();
                $('#btnUploadFile').addClass('disabled');
                xhr.upload.addEventListener("progress", function (evt) {
                    if (evt.lengthComputable) {
                        var percentComplete = evt.loaded / evt.total;
                        percentComplete = parseInt(percentComplete * 100);
                        if (percentComplete !== 100) {
                            $('.progress-bar').css('width', percentComplete + "%");
                            $('.progress-bar > span').html(percentComplete + "%");
                        }
                    }
                }, false);
                return xhr;
            },
            url: '../uploads/uploaddocSac.php',
            type: 'POST',
            data: formData,
            processData: false, // tell jQuery not to process the data
            contentType: false, // tell jQuery not to set contentType
            success: function (data) {
                $('.jconfirm-open').fadeOut(300, function () {
                    $(this).remove();
                });
                if (!data.trim()) {
                    $('#' + idField.replace("arqUpload_", "")).val(nomeArquivo);
                    $.alert({
                        title: "Mensagem",
                        content: "Upload feito com sucesso",
                        type: 'green'
                    });

                    //ajax da gravação do anexo
                    $.ajax({
						type: "POST",
						url: "ajxSacAnexo.php",
						data: $('#formulario').serialize(),
						success:function(data){
							//console.log(data);	
							$('#relatorioConteudo').html(data);
							$('#PRIMEIRO_UP').val("N");			
						},
						error:function(){
							alert("Algo saiu errado no upload do arquivo. Tente novamente.");
						}
					});

                } else {
                    $.alert({
                        title: "Erro ao efetuar o upload",
                        content: data,
                        type: 'red'
                    });
                }
            }
        });
    }
		
	</script>

	<?php 
	mysqli_close($connAdmSACV);
	?>	