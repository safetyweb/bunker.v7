<?php

include "../_system/_functionsMain.php";
include "../_system/_FUNCTION_WS.php";

//habilitando o cors
header("Access-Control-Allow-Origin: *");

$cpf = fnLimpacampo($_GET['cpf']);
$cod_empresa = fnLimpacampo($_GET['codEmpresa']);
$tipo = fnLimpacampo($_GET['tipo']);
$idCliente = 0;
$anoNascimento = "";

// echo($tipo);


$sql = "SELECT COD_DOMINIO, DES_DOMINIO FROM SITE_EXTRATO WHERE COD_EMPRESA = $cod_empresa ";
// echo($sql);
$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);
$qrBuscaSiteExtrato = mysqli_fetch_assoc($arrayQuery);

$des_dominio = $qrBuscaSiteExtrato['DES_DOMINIO'];
$cod_dominio = $qrBuscaSiteExtrato['COD_DOMINIO'];

if($cod_dominio == 2){
	$extensaoDominio = ".fidelidade.mk";
}else{
	$extensaoDominio = ".mais.cash";
}

// fnEscreve($cod_empresa);

//echo fnDebug('true');

// echo $tipo;
        
switch ($tipo) {
	case 'buscaCpf':
		$sql = "SELECT * from clientes where num_cgcecpf = '$cpf' and COD_EMPRESA = $cod_empresa";
		// echo($sql);	
		$result = mysqli_query(connTemp($cod_empresa,''),trim($sql));
		$linhas = mysqli_num_rows($result);

		$qrCliente = mysqli_fetch_assoc($result);

		// echo($linhas);
		
		if($linhas == 0){
			$sql = "SELECT log_cadastro from site_extrato where cod_empresa = $cod_empresa";	
			//fnEscreve($sql);	
			
                        $linha = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa,''),$sql));
                        $valor = $linha['log_cadastro']; 
			
        
			if($valor === 'S'){
				
				header("Location:https://".$des_dominio.$extensaoDominio."/cadastrarSe2.do?codEmpresa=$cod_empresa&idCpf=".fnEncode($cpf)."&pop=true");
				
			}else{
				echo 'sem_resultado';
			}
		}else{
                       
				//print_r($qrCliente);
				$idCliente = $qrCliente['COD_CLIENTE'];
				$anoNascimento = substr($qrCliente['DAT_NASCIME'], 6, 4);

				// fnEscreve($idCliente);
				 // fnEscreve($anoNascimento);
                    
			?>
			<form id='cadastrar-se'>
				<h6 style="margin-bottom: 15px;">CPF validado.<br/> Informe o Ano de nascimento:<h6>
				<input type="text" id="ano" name="ano" class="form-control input-hg" maxlength="4" placeholder="Ano Ex: 1990" />
				<button type="button" class="btn btn-primary btn-hg btn-block" name="btnBuscarAno" id="btnBuscarAno">Buscar</button>
				<div class="push10"></div>
				<div class="errorLogin" style="color: red; text-align: center; display: none; margin-top: 15px;">Ano Inválido</div>
				<div id="loadStep"></div>
			</form>	
			<?php
		}			

		break;
	case 'buscaCnpj':
		$sql = "SELECT * from clientes where num_cgcecpf = '$cpf' and COD_EMPRESA = $cod_empresa";
		// echo($sql);	
		$result = mysqli_query(connTemp($cod_empresa,''),trim($sql));
		$linhas = mysqli_num_rows($result);

		$qrCliente = mysqli_fetch_assoc($result);

		// echo($linhas);
		
		if($linhas == 0){
			$sql = "SELECT log_cadastro from site_extrato where cod_empresa = $cod_empresa";	
			//fnEscreve($sql);	
			
                        $linha = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa,''),$sql));
                        $valor = $linha['log_cadastro']; 
			
        
			if($valor === 'S'){
				
				header("Location:https://".$des_dominio.$extensaoDominio."/cadastrarSe2.do?codEmpresa=$cod_empresa&idCpf=".fnEncode($cpf)."&pop=true");
				
			}else{
				echo 'sem_resultado';
			}
		}else{
                       
				//print_r($qrCliente);
				$idCliente = $qrCliente['COD_CLIENTE'];
				$email = strtolower($qrCliente['DES_EMAILUS']);

				// fnEscreve($idCliente);
				 // fnEscreve($anoNascimento);
                    
			?>
			<form id='cadastrar-se'>
				<h6 style="margin-bottom: 15px;">CPF validado.<br/> Informe o email de cadastro:<h6>
				<input type="text" id="EMAIL" name="EMAIL" class="form-control input-hg" placeholder="Ex: empresa@email.com" />
				<button type="button" class="btn btn-primary btn-hg btn-block" name="btnBuscarCnpj" id="btnBuscarCnpj">Buscar</button>
				<div class="push10"></div>
				<div class="errorLogin" style="color: red; text-align: center; display: none; margin-top: 15px;">Email Inválido</div>
				<div id="loadStep"></div>
			</form>	
			<?php
		}			

		break;
	case 'confirmarSenha':
		$idCliente = fnLimpacampo($_GET['idCliente']);

		$sql = "select DES_EMAILUS from clientes where cod_cliente = $idCliente";
		//fnEscreve($sql);	
		$qrEmail = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa,''),trim($sql)));
		?>
		<form id='cadastrar-se'>
			<h6 style="margin-bottom: 15px;">Cadastro validado.<br/> Crie sua senha.<h6>
			<input type="email" id="emailALT" name="emailALT" class="form-control input-hg" placeholder="Email" value="<?=$qrEmail['DES_EMAILUS']?>" required/>
			<?php

				if($cod_empresa == 124){

			?>
				<input type="number" maxlength="4" style="-webkit-text-security: disc;" oninput="checkNumberFieldLength(this);" id="senha" name="senha" class="form-control input-hg int" placeholder="Senha" />
				<input type="number" maxlength="4" style="-webkit-text-security: disc;" oninput="checkNumberFieldLength(this);" id="senhaConfirma" name="senhaConfirma" class="form-control input-hg int" placeholder="Confirmar Senha" />
			<?php 

				}else{

			?>
				<input type="password" maxlength="8" id="senha" name="senha" class="form-control input-hg" placeholder="Senha" />
				<input type="password" maxlength="8" id="senhaConfirma" name="senhaConfirma" class="form-control input-hg" placeholder="Confirmar Senha" />
			<?php 

				} 

			?>
			<button type="button" class="btn btn-primary btn-hg btn-block" name="btnConfirmarSenha" id="btnConfirmarSenha">Confirmar Senha</button>
			<div class="push10"></div>
			<div class="errorLogin" style="color: red; text-align: center; display: none; margin-top: 15px;">Senhas diferentes</div>
			<div id="loadStep"></div>
		</form>	
		<?php	
		break;

	case 'reenviaSenha':
		$idCliente = fnLimpacampo($_GET['idCliente']);

		$sqlEmp = "SELECT NOM_FANTASI FROM EMPRESAS WHERE COD_EMPRESA = $cod_empresa";
		$qrEmp = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa,''),$sqlEmp));

		$sql = "SELECT DES_EMAILUS, DES_SENHAUS FROM CLIENTES WHERE COD_CLIENTE = $idCliente";
		// echo($sql);	
		$qrEmail = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa,''),trim($sql)));

		include '../externo/email/envio_sac.php';

		if($qrEmail['DES_SENHAUS'] != ""){

			$texto='Sua senha de acesso ao extrato é: '.fnDecode($qrEmail['DES_SENHAUS']);
			$texto_confirma = "Sua senha foi enviada para seu email de cadastro.";

		}else{

		    $alphabet = '1234567890';
		    $pass = array(); 
		    $alphaLength = strlen($alphabet) - 1; 
		    for ($i = 0; $i < 4; $i++) {
		        $n = rand(0, $alphaLength);
		        $pass[] = $alphabet[$n];
		    } 

			$novaSenha = implode($pass);

			$sqlUpdate = "UPDATE CLIENTES SET DES_SENHAUS = '".fnEncode($novaSenha)."' WHERE COD_EMPRESA = $cod_empresa AND COD_CLIENTE = $idCliente";
			mysqli_query(connTemp($cod_empresa,''),trim($sqlUpdate));

			$texto='Sua nova senha de acesso ao extrato é: '.$novaSenha;
			$texto_confirma = "Sua nova senha foi gerada e enviada para seu email de cadastro.";

		}

			$email['email1']=$qrEmail['DES_EMAILUS'];
			fnsacmail($email,
			          'Suporte Marka',
			          '<html>'.$texto.'<html>',
			          'Recuperação de senha - Extrato',
			          $qrEmp['NOM_FANTASI'],
			          $connAdm->connAdm(),
			          connTemp($cod_empresa,''),'3');

			?>
			<form id='cadastrar-se'>
				<h6 style="margin-bottom: 15px;">Cadastro validado.<br/><?=$texto_confirma?><h6>
			</form>	
			<?php

		break;
		
	case 'cadastrarSenha':
		$senha = fnLimpacampo($_GET['senhaCliente']);
		$email = fnLimpacampo($_GET['emailCliente']);
		$idCliente = fnLimpacampo($_GET['idCliente']);
               
		$sql = "update clientes set des_senhaus = '".fnEncode($senha)."', DES_EMAILUS= '$email' where cod_cliente = $idCliente and COD_EMPRESA = $cod_empresa";	
		//fnEscreve($sql);	
		$result = mysqli_query(connTemp($cod_empresa,''),trim($sql));
		$linhas = mysqli_num_rows($result);
            
		echo '<br><h5>Parabéns! <br/><br> Sua senha foi alteradada com <b>sucesso</b>!</h5></center><br><br><br>';
		?>
		<button type="button" class="btn btn-primary btn-hg btn-block" id="btnFecharModal" data-dismiss="modal">Sair</button>
		<?php
                
		break;	

	case 'cadastrarUsuario':
                       
                $sql ="select log_usuario, des_senhaus, cod_univend from usuarios where COD_TPUSUARIO = 10 and cod_empresa = $cod_empresa and log_estatus = 'S' LIMIT 1"; 
		//$sql = "select log_usuario, des_senhaus, cod_univend from usuarios where COD_TPUSUARIO = 12 and cod_empresa = $cod_empresa and log_estatus = 'S'";
		$arrayQuery = mysqli_query($connAdm->connAdm(),$sql);
		$linha = mysqli_fetch_assoc($arrayQuery);
		$usuario = $linha['log_usuario']; 
		$senha = $linha['des_senhaus'];
		$senha_cli = $_GET['senhaCA'];
		$cod_univend = $linha['cod_univend'];
		$arrayCampos = explode(",", $cod_univend); 
		
		$dadosatualiza=Array('NOM_CLIENTE'=>fnLimpacampo($_GET['nome']),
                                    'COD_SEXOPES'=>fnLimpacampo($_GET['sexo']),
                                    'DES_EMAILUS'=>fnLimpacampo($_GET['email']), 
                                    'NUM_CELULAR'=>fnLimpacampo($_GET['celular']), 
                                    'NUM_CARTAO'=>fnLimpaDoc($_REQUEST['NUM_CARTAO']), 
                                    'NUM_CGCECPF'=>fnLimpaDoc($_REQUEST['NUM_CGCECPF']),
                                    'senha_cli'=> $senha_cli, 
                                    'COD_EMPRESA'=>$cod_empresa,
                                    'login'=>$usuario, 
                                    'senha'=>fnDecode($senha), 
                                    'COD_UNIVEND'=>$arrayCampos[0],
                                    'DAT_NASCIME'=> fnDataSql($_GET['dt_nascimento'])); 
		    $atualiza=atualizacadastro($dadosatualiza);
                 
			if(strtoupper($atualiza['msgerro'][0]) == 'OK'){
				echo '<br><h5>Cadastro realizado com sucesso!</h5></center><br><br><br>';
			}else{
				echo '<br><h5>Cadastro realizado com sucesso!</h5></center><br><br><br>';
			}
            
		?>
		<button type="button" class="btn btn-primary btn-hg btn-block" id="btnFecharModal" data-dismiss="modal">Sair</button>
		<?php
		break;		
}


?>

<!--
<script>
    window.iFrameResizer = {
        //targetOrigin: 'http://clubesidneyoliveira.fidelidade.mk'
		targetOrigin: 'http://clubeuvline.fidelidade.mk'
    }
</script>
-->

<script src="js/iframeResizer.contentWindow.min.js"></script>		

<script type="text/javascript">


	$(document).ready( function() {	
	
		$('.data').mask('00/00/0000');
		$('.celular').mask('(00) 00000-0000');

		$('#btnFecharModal').click(function() {
			//parent.$('#popModal').modal('hide');
			//parentIFrame.close();
			//alert('teste');
			parentIFrame.sendMessage('close','https://<?php echo $des_empresa; ?>.fidelidade.mk');
		});
		
		$('#btnBuscarAno').click(function() {
			
			var anoBD = '<?php echo $anoNascimento ?>';
			
			// alert(anoBD);
			
			if($('#ano').val() == anoBD){
				$.ajax({
					type: "GET",
					url: "ajxCadastrarSe.do",
					data: {codEmpresa: <?php echo $cod_empresa ?>, idCliente: <?php echo $idCliente ?>, tipo: 'confirmarSenha' },
					beforeSend:function(){
						$('#loadStep').html('<div class="loading" style="width: 100%;"></div>');
					},				
					success: function(msg) {
						var retorno = msg.trim().substring(0, 13).trim();
						
						if(retorno != 'sem_resultado'){
							$('#loadStep').hide();
							$('#containerCadastrar').html(msg);
						}else{
							$('#loadStep').hide();
							$('.errorLogin').show();
						}
					}
				});
			}else{
				$('.errorLogin').show();
			}
		});

		$('#btnBuscarCnpj').click(function() {
			
			var email = '<?php echo $email ?>';
			
			// alert(anoBD);
			
			if($('#EMAIL').val().trim() == email.trim()){
				$.ajax({
					type: "GET",
					url: "ajxCadastrarSe.do",
					data: {codEmpresa: <?php echo $cod_empresa ?>, idCliente: <?php echo $idCliente ?>, tipo: 'reenviaSenha' },
					beforeSend:function(){
						$('#loadStep').html('<div class="loading" style="width: 100%;"></div>');
					},				
					success: function(msg) {
						$('#loadStep').hide();
						$('#containerCadastrar').html(msg);
					}
				});
			}else{
				$('.errorLogin').show();
			}
		});

		$('#btnConfirmarSenha').click(function() {
			var senha = $('#senha').val();
			var email = $('#emailALT').val();
			var confirmarSenha = $('#senhaConfirma').val();
			
			$("#btnFecharModal").show();
			
			if(senha == confirmarSenha && email != ""){
				$.ajax({
					type: "GET",
					url: "ajxCadastrarSe.do",
					data: {senhaCliente: senha, emailCliente: email, codEmpresa: <?php echo $cod_empresa ?>, idCliente: <?php echo $idCliente ?>,  tipo: 'cadastrarSenha' },
					beforeSend:function(){
						$('#loadStep').html('<div class="loading" style="width: 100%;"></div>');
					},				
					success: function(msg) {
						var retorno = msg.trim().substring(0, 13).trim();
						
						if(retorno != 'sem_resultado'){
							$('#loadStep').hide();
							$('#containerCadastrar').html(msg);
							$(this).closest('form').find("input[type=text], textarea").val("");
						}else{
							$('#loadStep').hide();
							$('.errorLogin').show();			
						}
					}
				});				
			}else {
				$('.errorLogin').show();
			}
		});

		$('.cadastrarUsuario').click(function() {
			var email = $('#email').val();
			var senha = $('#senhaCA').val();
			var confirmarSenha = $('#confirmarSenhaCA').val();
			
			//$("#btnFecharModal").show();
			
			if(senha == confirmarSenha && email != ""){
				$.ajax({
					type: "GET",
					url: "ajxCadastrarSe.do",
					data: $('#formCadastro').serialize() + '&tipo=cadastrarUsuario',
					beforeSend:function(){
						$('#loadStep').html('<div class="loading" style="width: 100%;"></div>');
					},				
					success: function(msg) {
						var retorno = msg.trim().substring(0, 13).trim();
						
						if(retorno != 'sem_resultado'){
							$('#loadStep').hide();
							$('#containerCadastrar').html(msg);
						}else{
							$('#loadStep').hide();
							$('.errorLogin').show();			
						}
					}
				});				
			}else {
				$('.errorLogin').show();
			}
		});		
	});

	function checkNumberFieldLength(elem){
	    if (elem.value.length > 4) {
	        elem.value = elem.value.slice(0,4); 
	    }
	}			

</script>


