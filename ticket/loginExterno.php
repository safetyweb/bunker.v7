<?php

include "../_system/_functionsMain.php";

//habilitando o cors
header("Access-Control-Allow-Origin: *");

//echo "<h1>".$_GET['param']."</h1>";

	//echo fnDebug('true');

	//busca dados da url	
	if (fnLimpacampo($_GET['param']) != ""){
		//busca codigo da empresa
		$cod_busca = strtolower(fnLimpacampo($_GET['param']));	
		$sql = "select COD_EMPRESA from DOMINIO WHERE DES_DOMINIO = '$cod_busca' ";
        //fnEscreve($sql);
		$arrayQuery = mysqli_query($connAdm->connAdm(),$sql);
		$qrBuscaCodEmpresa = mysqli_fetch_assoc($arrayQuery);
		//fnEscreve($qrBuscaCodEmpresa['COD_EMPRESA']);                
        $cod_empresa = $qrBuscaCodEmpresa['COD_EMPRESA'];
        //$nom_fantasi = $qrBuscaCodEmpresa['NOM_FANTASI'];
                
		if (isset($qrBuscaCodEmpresa)){
			$cod_empresa = $qrBuscaCodEmpresa['COD_EMPRESA'];
			$siteGo = "OK";
		}else {
			$siteGo = "NOK";
		}
												
	}  

	// fnEscreve($siteGo);

	if ($siteGo == "OK"){
		
		//fnEscreve($siteGo);
		//fnEscreve($cod_empresa);
		
		//busca nome da empresa
		$sql2 = "select NOM_FANTASI from EMPRESAS WHERE COD_EMPRESA = $cod_empresa ";
		$arrayQuery = mysqli_query($connAdm->connAdm(),$sql2);
		$qrBuscaDadosEmpresa = mysqli_fetch_assoc($arrayQuery);
        $nom_fantasi = $qrBuscaDadosEmpresa['NOM_FANTASI'];

		//busca dados da tabela
		$sql = "SELECT * FROM SITE_EXTRATO WHERE COD_EMPRESA = '" . $cod_empresa . "' ";
		//fnEscreve($sql);
		$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql) or die(mysqli_error());
		$qrBuscaSiteExtrato = mysqli_fetch_assoc($arrayQuery);

		if (isset($arrayQuery)) {
			//fnEscreve("entrou if");
			$cod_extrato = $qrBuscaSiteExtrato['COD_EXTRATO'];
			$des_dominio = $qrBuscaSiteExtrato['DES_DOMINIO'];
			$des_logo = $qrBuscaSiteExtrato['DES_LOGO'];
			$des_banner = $qrBuscaSiteExtrato['DES_BANNER'];
			$des_email = $qrBuscaSiteExtrato['DES_EMAIL'];
			$log_vantagem = $qrBuscaSiteExtrato['LOG_VANTAGEM'];
			$txt_vantagem = $qrBuscaSiteExtrato['TXT_VANTAGEM'];
			$log_regula = $qrBuscaSiteExtrato['LOG_REGULA'];
			$txt_regula = $qrBuscaSiteExtrato['TXT_REGULA'];
			$log_lojas = $qrBuscaSiteExtrato['LOG_LOJAS'];
			$txt_lojas = $qrBuscaSiteExtrato['TXT_LOJAS'];
			$log_faq = $qrBuscaSiteExtrato['LOG_FAQ'];
			$txt_faq = $qrBuscaSiteExtrato['TXT_FAQ'];
			$log_premios = $qrBuscaSiteExtrato['LOG_PREMIOS'];
			$txt_premios = $qrBuscaSiteExtrato['TXT_PREMIOS'];
			$log_extrato = $qrBuscaSiteExtrato['LOG_EXTRATO'];
			$txt_extrato = $qrBuscaSiteExtrato['TXT_EXTRATO'];
			$log_contato = $qrBuscaSiteExtrato['LOG_CONTATO'];
			$log_cadastro = $qrBuscaSiteExtrato['LOG_CADASTRO'];
			$txt_contato = $qrBuscaSiteExtrato['TXT_CONTATO'];
			$cor_titulos = $qrBuscaSiteExtrato['COR_TITULOS'];
			$cor_textos = $qrBuscaSiteExtrato['COR_TEXTOS'];
			$cor_rodapebg = $qrBuscaSiteExtrato['COR_RODAPEBG'];
			$cor_rodape = $qrBuscaSiteExtrato['COR_RODAPE'];
			$cor_botao = $qrBuscaSiteExtrato['COR_BOTAO'];
			$cor_botaoon = $qrBuscaSiteExtrato['COR_BOTAOON'];
			$des_vantagem = $qrBuscaSiteExtrato['DES_VANTAGEM'];
			$ico_bloco1 = $qrBuscaSiteExtrato['ICO_BLOCO1'];
			$ico_bloco2 = $qrBuscaSiteExtrato['ICO_BLOCO2'];
			$ico_bloco3 = $qrBuscaSiteExtrato['ICO_BLOCO3'];
			$tit_bloco1 = $qrBuscaSiteExtrato['TIT_BLOCO1'];
			$des_bloco1 = $qrBuscaSiteExtrato['DES_BLOCO1'];
			$tit_bloco2 = $qrBuscaSiteExtrato['TIT_BLOCO2'];
			$des_bloco2 = $qrBuscaSiteExtrato['DES_BLOCO2'];
			$tit_bloco3 = $qrBuscaSiteExtrato['TIT_BLOCO3'];
			$des_bloco3 = $qrBuscaSiteExtrato['DES_BLOCO3'];
			$des_regras = $qrBuscaSiteExtrato['DES_REGRAS'];
			$des_programa = $qrBuscaSiteExtrato['DES_PROGRAMA'];
		} 
	}

?>

		<link href="css/main.css" rel="stylesheet">
		<link href="css/custom.css" rel="stylesheet">
		
		<!-- SISTEMA -->
		<link href="css/jquery-confirm.min.css" rel="stylesheet"/>
		<link href="css/jquery.webui-popover.min.css" rel="stylesheet" />
		<link href="css/chosen-bootstrap.css" rel="stylesheet" />
		<link href="css/font-awesome.min.css" rel="stylesheet" />
		
		<!-- complement -->
		<link href="css/default.css" rel="stylesheet" />


		<section id="extrato" style="background-color: #fff;">

			<div class="container" id="containerExtrato">

				<header>
					<h1><?php echo $txt_extrato; ?></h1>
					<p class="lead">Faça seu login e visualize seus ganhos!</p>
				</header>

				<div class="row">
					<div class="col-md-6 col-md-offset-3">
					
						<?php
							//depyl
							if($log_cadastro == "S"){
						
								$imgLogin = "images/icons/support.svg";
						?>
						
						<div class="row contact-intro">
							<div class="col-md-3 text-right"><img src="<?php echo $imgLogin; ?>" alt="" title="" /></div>
                                                        <div class="col-md-9"><p class="lead">É seu primeiro acesso? <br> <a style="cursor: pointer" class="addBox" data-url="https://<?php echo $des_dominio;?>.fidelidade.mk/cadastrarSe.do?codEmpresa=<?php echo $cod_empresa ?>&pop=true" data-title="Crie sua senha">Clique aqui</a></p></div>                        
						</div>

						<br/>
						
						<?php
							}
						?>
						
						<!--////////// CONTACT FORM //////////-->
						<form>
							<input type="text" id="cpf" name="cpf" class="form-control input-hg cpf" placeholder="Seu CPF" />
							<input type="password" id="senha" name="senha" class="form-control input-hg" placeholder="Sua Senha" />
							<button type="button" class="btn btn-primary btn-hg btn-block" name="btLogin" id="btLogin">Fazer login</button>
							<div class="push10"></div>
							<div class="errorLogin" style="color: red; text-align: center; display: none">Usuário/senha inválidos.</div>
						</form>

						<div id="contact-error"></div>
											
						<div class="row">
							<div class="col-md-12 text-center"><p class="lead">Esqueceu sua senha? <a style="cursor: pointer" class="addBox" data-url="https://<?php echo $des_dominio;?>.fidelidade.mk/cadastrarSe.do?codEmpresa=<?php echo $cod_empresa ?>&pop=true" data-title="Alterar senha">Clique aqui</a></p></div>                        
						</div>
						
						<br/>

					</div><!-- /col-md-6-->
				</div>
			</div>

		</section>

		<!-- modal -->									
		<div class="modal fade" id="popModal" tabindex='-1'>
			<div class="modal-dialog" style="">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title"></h4>
					</div>
					<div class="modal-body">
						<iframe frameborder="0" style="width: 100%; height: 600px !important"></iframe>
					</div>		
				</div><!-- /.modal-content -->
			</div><!-- /.modal-dialog -->
		</div><!-- /.modal -->

		<script src="js/jquery.min.js"></script>
		<script src="js/jquery-ui-1.10.3.custom.min.js"></script>
		<script src="js/jquery.ui.touch-punch.min.js"></script>
		<script src="js/bootstrap.min.js"></script>
		<script src="js/jquery.isotope.min.js"></script>
		<script src="js/bootstrap-select.js"></script>
		<script src="js/custom.js"></script>
		<script src="js/jquery.mask.min.js"></script>
		<script src="js/iframeResizer.min.js"></script>	

<script>
			
			//modal
			$("body").on("click", ".addBox", function() {												
				var popLink = $(this).attr("data-url");
				var popTitle = $(this).attr("data-title");
				//alert(popLink);	
				setIframe(popLink, popTitle);
				$('.modal').appendTo("body").modal('show');
			});				
			
			$('.cpf').mask('000.000.000-00', {reverse: true});
			
			$('#btLogin').click(function() {
				
				var pCpf = $('#cpf').val().replace(/[^0-9]/g, '');
				var pSenha = $('#senha').val().trim();
				
				$.ajax({
					type: "GET",
                                          
					url: "https://<?php echo $des_dominio;?>.fidelidade.mk/ajxLogin.php",
					data: { cpf:pCpf, senha:pSenha, codEmpresa: <?php echo $cod_empresa; ?> },
					success: function(msg) {
						if(msg.trim() != 'sem_resultado'){
							//alert(msg);
							$('#containerExtrato').html(msg);
						}else{
							$('.errorLogin').show();
						}
					}
				});	
					
			});
			
			$('#btMensagem').click(function() {
				
				var nome = $('#name').val();
				var email = $('#email').val();
				var mensagem = $('#message').val();
				
				if(nome != "" && email != "" && mensagem != ""){
					$.ajax({
						type: "GET",
						url: "https://<?php echo $des_dominio;?>.fidelidade.mk/ajxEnviarMensagem.do",
						data: { nome:$('#name').val(), email:$('#email').val(), mensagem:$('#message').val(), codEmpresa: <?php echo $cod_empresa; ?>, programa: '<?php echo $des_programa; ?>' },
						success: function(msg) {
							$('#contato-info').html(msg);
						}
					});						
				}else{
					alert('Preencha todos os campos por favor');
				}

			});				
			
		//call modal
		//$('#popModal').iFrameResize({closedCallback:function(){$('#popModal').modal('hide');}});
		function setIframe(src, title) {
			$(".modal iframe").attr({
				'src': src
			}).iFrameResize({messageCallback:function(){$('#popModal').modal('hide');}});
			if (title) {
				$(".modal-title").text(title);
			} else {
				$(".modal-title").text("");
			}
		}

</script>
