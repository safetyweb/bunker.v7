<?php


	include '../_system/_functionsMain.php';

	$opcao = fnLimpaCampo($_GET['opcao']);
	$cod_empresa = fnLimpaCampoZero(fnDecode($_GET['id']));	
	$urltotem = fnDecode($_POST['URL_TOTEM']);

	// echo "$opcao";

	switch($opcao){

		case 'TKNALT':

			include_once 'funWS/GeraToken.php';
			include_once 'funWS/buscaConsumidor.php';
			include_once 'funWS/buscaConsumidorCNPJ.php';

			$sql = "SELECT COD_EMPRESA, NOM_FANTASI, QTD_CHARTKN, TIP_TOKEN, TIP_RETORNO, NUM_DECIMAIS_B  FROM empresas where COD_EMPRESA = '".$cod_empresa."' ";
			//fnEscreve($sql);
			$arrayQuery = mysqli_query($connAdm->connAdm(),$sql);
			$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);
			
			if (isset($arrayQuery)){
				$cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
				$nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];
				$qtd_chartkn = $qrBuscaEmpresa['QTD_CHARTKN'];
				$tip_token = $qrBuscaEmpresa['TIP_TOKEN'];


				if($qrBuscaEmpresa['TIP_RETORNO'] == 1){
					$casasDec = 0;
				}else{
					$casasDec = $qrBuscaEmpresa['NUM_DECIMAIS_B'];
					$pref = "R$ ";
				}

				// echo($casasDec);
			}

			// $sql = "SELECT * FROM  USUARIOS
			// 		WHERE LOG_ESTATUS='S' AND
			// 			  COD_EMPRESA = $cod_empresa AND
			// 			  COD_TPUSUARIO = 10  limit 1  ";
			// //fnEscreve($sql);
			// $arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
			// $qrBuscaUsuTeste = mysqli_fetch_assoc($arrayQuery);
							
			// if (isset($arrayQuery)) {
			// 	$log_usuario = $qrBuscaUsuTeste['LOG_USUARIO'];
			// 	$des_senhaus = fnDecode($qrBuscaUsuTeste['DES_SENHAUS']);
			// }

			// $sqlUn = "SELECT COD_UNIVEND FROM UNIDADEVENDA 
			// 		  WHERE COD_EMPRESA = $cod_empresa 
			// 		  AND LOG_ESTATUS = 'S' 
			// 		  ORDER BY 1 ASC LIMIT 1";

			// $arrayUn = mysqli_query($connAdm->connAdm(), $sqlUn);
			// $qrLista = mysqli_fetch_assoc($arrayUn);

			// $idlojaKey = $qrLista['COD_UNIVEND'];
			// $idmaquinaKey = 0;
			// $codvendedorKey = 0;
			// $nomevendedorKey = 0;

			// $urltotem = $log_usuario.';'
			// 			.$des_senhaus.';'
			// 			.$idlojaKey.';'
			// 			.$idmaquinaKey.';'
			// 			.$cod_empresa.';'
			// 			.$codvendedorKey.';'
			// 			.$nomevendedorKey;

			$arrayCampos = explode(";", $urltotem);

			$num_celular = fnLimpaCampo(fnLimpaDoc($_POST['NUM_CELULAR']));
			$num_cgcecpf = fnLimpaCampo(fnLimpaDoc($_POST['NUM_CGCECPF']));
			$nom_cliente = fnLimpaCampo(fnAcentos($_POST['NOM_CLIENTE']));

			if($num_celular == ""){
				$num_celular = fnLimpaCampo(fnLimpaDoc($_POST['KEY_NUM_CELULAR']));
			}

			if($num_cgcecpf == ""){
				$num_cgcecpf = fnLimpaCampo(fnLimpaDoc($_POST['KEY_NUM_CGCECPF']));
				$k_num_cgcecpf = fnLimpaCampo(fnLimpaDoc($_POST['KEY_NUM_CGCECPF']));
				if(strlen($k_num_cgcecpf) <= '11'){

					// echo '<pre>';

		            $buscaconsumidor = fnconsulta(fnCompletaDoc($k_num_cgcecpf,'F'), $arrayCampos);

		            // print_r($buscaconsumidor);

		            // echo '</pre>';
		            
		        }else{

		        	// echo 'else';

		            $buscaconsumidor = fnconsultacnpf(fnCompletaDoc($k_num_cgcecpf,'J'), $arrayCampos); 
		            
				}
			}

			if($num_cgcecpf == "00000000000"){
				$num_cgcecpf = $num_celular;
			}

			$dadosenvio = array(
								 'tipoGeracao'=>'1',
								 'nome'=>"$nom_cliente",
								 'cpf'=>"$num_cgcecpf",
								 'celular'=>"$num_celular",
								 'email'=>''
								);

			$retornoEnvio = GeraToken($dadosenvio, $arrayCampos);

	     // echo '<pre>';
		 //    print_r($dadosenvio);
		 //    print_r($retornoEnvio);
		 //    echo '</pre>';
		 //    exit();

			$cod_envio = $retornoEnvio[body][envelope][body][geratokenresponse][retornatoken][coderro];

?>

<style>
	.p-r-0{
		padding-right: 0;
	}

	.p-l-0{
		padding-left: 0;
	}

	.img-g{
		display: none;
	}

	.img-m{
		display: block;
	}

	.img-p{
		display: none;
	}	

@media only screen and (min-device-width: 320px) and (max-device-width: 480px) {

	.img-g{
		display: none;
	}

	.img-m{
		display: none;
	}

	.img-p{
		display: block;
	}

	#roteiro{
		display: none;
	}	

	.p-r-0{
		padding-right: 15px;
		padding-left: 15px;
		margin-bottom: 10px;
	}

	.p-l-0{
		padding-left: 15px;
		padding-right: 15px;
	}

	.p-0{
		padding: 0;
	}

	.nav-tabs li{
		width:100%;
	}

	.nav-tabs li:last-child{
		margin-bottom:5px;
	}

    
}
 
/* (320x480) Smartphone, Portrait */
@media only screen and (device-width: 320px) and (orientation: portrait) {

	.img-g{
		display: none;
	}

	.img-m{
		display: none;
	}

	.img-p{
		display: block;
	}

	#roteiro{
		display: none;
	}	
    
	.p-r-0{
		padding-right: 15px;
		padding-left: 15px;
		margin-bottom: 10px;
	}

	.p-l-0{
		padding-left: 15px;
		padding-right: 15px;
	}

	.p-0{
		padding: 0;
	}

	.nav-tabs li{
		width:100%;
	}

	.nav-tabs li:last-child{
		margin-bottom:5px;
	}

}
 
/* (320x480) Smartphone, Landscape */
@media only screen and (device-width: 480px) and (orientation: landscape) {

	.img-g{
		display: none;
	}

	.img-m{
		display: none;
	}

	.img-p{
		display: block;
	}

	#roteiro{
		display: none;
	}	
    
	.p-r-0{
		padding-right: 15px;
		padding-left: 15px;
		margin-bottom: 10px;
	}

	.p-l-0{
		padding-left: 15px;
		padding-right: 15px;
	}

	.p-0{
		padding: 0;
	}

	.nav-tabs li{
		width:100%;
	}

	.nav-tabs li:last-child{
		margin-bottom:5px;
	}

}
 
/* (1024x768) iPad 1 & 2, Landscape */
@media only screen and (min-device-width: 768px) and (max-device-width: 1367px) and (orientation: landscape) {

	.img-g{
		display: none;
	}

	.img-m{
		display: none;
	}

	.img-p{
		display: block;
	}

	#roteiro{
		display: none;
	}
    
	.p-r-0{
		padding-right: 15px;
15px}

	.p-l-0{
		padding-left: 0;
	15p15px

}

/* (1280x800) Tablets, Portrait */
@media only screen and (max-width: 800px) and (orientation : portrait) {

	.img-g{
		display: none;
	}

	.img-m{
		display: none;
	}

	.img-p{
		display: block;
	}

	#roteiro{
		display: none;
	}
	 
	.p-r-0{
		padding-right: 15px;
		padding-left: 15px;
		margin-bottom: 10px;
	}

	.p-l-0{
		padding-left: 15px;
		padding-right: 15px;
	}

	.p-0{
		padding: 0;
	}

	.nav-tabs li{
		width:100%;
	}

	.nav-tabs li:last-child{
		margin-bottom:5px;
	}

}

/* (768x1024) iPad 1 & 2, Portrait */
@media only screen and (max-width: 768px) and (orientation : portrait) {

	.img-g{
		display: none;
	}

	.img-m{
		display: none;
	}

	.img-p{
		display: block;
	}

	#roteiro{
		display: none;
	}
    
	.p-r-0{
		padding-right: 15px;
		padding-left: 15px;
		margin-bottom: 10px;
	}

	.p-l-0{
		padding-left: 15px;
		padding-right: 15px;
	}

	.p-0{
		padding: 0;
	}

	.nav-tabs li{
		width:100%;
	}

	.nav-tabs li:last-child{
		margin-bottom:5px;
	}

}
 
/* (2048x1536) iPad 3 and Desktops*/
@media only screen and (min-device-width: 1536px) and (max-device-width: 2048px) {

	.img-g{
		display: block;
	}

	.img-m{
		display: none;
	}

	.img-p{
		display: none;
	}

	.p-r-0{
		padding-right: 0;
	}

	.p-l-0{
		padding-left: 0;
	}
		 
}

@media only screen and (min-device-width: 1100px) and (orientation : portrait) {

	.img-g{
		display: none;
	}

	.img-m{
		display: none;
	}

	.img-p{
		display: block;
	}

	#roteiro{
		display: none;
	}
    
	.p-r-0{
		padding-right: 15px;
		padding-left: 15px;
		margin-bottom: 10px;
	}

	.p-l-0{
		padding-left: 15px;
		padding-right: 15px;
	}

	.p-0{
		padding: 0;
	}

	.nav-tabs li{
		width:100%;
	}

	.nav-tabs li:last-child{
		margin-bottom:5px;
	}

}

@media (max-height: 824px) and (max-width: 416px){

	.img-g{
		display: none;
	}

	.img-m{
		display: none;
	}

	.img-p{
		display: block;
	}

	#roteiro{
		display: none;
	}
	
	.p-r-0{
		padding-right: 15px;
		padding-left: 15px;
		margin-bottom: 10px;
	}

	.p-l-0{
		padding-left: 15px;
		padding-right: 15px;
	}

	.p-0{
		padding: 0;
	}

	.nav-tabs li{
		width:100%;
	}

	.nav-tabs li:last-child{
		margin-bottom:5px;
	}

}	

/* (320x480) iPhone (Original, 3G, 3GS) */
@media (max-device-width: 737px) and (max-height: 416px) {

	.img-g{
		display: none;
	}

	.img-m{
		display: none;
	}

	.img-p{
		display: block;
	}

	#roteiro{
		display: none;
	}	

	.p-r-0{
		padding-right: 15px;
		padding-left: 15px;
		margin-bottom: 10px;
	}

	.p-l-0{
		padding-left: 15px;
		padding-right: 15px;
	}

	.p-0{
		padding: 0;
	}

	.nav-tabs li{
		width:100%;
	}

	.nav-tabs li:last-child{
		margin-bottom:5px;
	}

		
}
</style>

<?php 

			// echo "_".$cod_envio;

			if($cod_envio == 39){

				if($tip_token == 2){
					$type = "number";
				}else{
					$type = "text";
				}

?>

			

				<div class="col-md-12 col-xs-12 text-left">

					<div class="alert alert-success" role="alert">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					 	Token enviado! Verifique o SMS recebido, e digite o token no campo abaixo:
					</div>

				</div>

				<script type="text/javascript">$("#btnCadastro").fadeOut('fast');</script>

<?php 
			}else if($cod_envio == 0){
?>

				<div class="col-md-12 col-xs-12 text-left">

					<div class="alert alert-warning" role="alert">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					 	Token não enviado, pois não há celular/email de destino. É necessário configurar a matriz de campos.
					</div>

				</div>

				<script type="text/javascript">$("#btnCadastro").fadeOut('fast');</script>

<?php
				exit();
			}else if($cod_envio == 96){
?>

				<div class="col-md-12 col-xs-12 text-left">

					<div class="alert alert-danger" role="alert">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					 	Rotinas de token incompletas.<br>Contate o suporte.
					</div>

				</div>

				<script type="text/javascript">$("#btnCadastro").fadeOut('fast');</script>

<?php
				exit();
			}else{

?>

				<div class="col-md-12 col-xs-12 text-left">

					<div class="alert alert-success" role="alert">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					 	O token já enviado. Verifique o SMS recebido, e digite o token no campo abaixo. Caso não tenha recebido, por favor aguarde 5 minutos e tente enviar o token novamente.
					</div>

				</div>

				<script type="text/javascript">$("#btnCadastro").fadeOut('fast');</script>

<?php 
				
			}

			
?>

			
				<div id="camposToken">

					<div class="col-md-12 col-xs-12 text-left" id="erroTkn" style="display: none;">

						<div class="alert alert-danger" role="alert">
						<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						 	Token inválido.
						</div>

					</div>

					<div class="col-md-8 col-xs-12 text-left p-r-0">
						<div class="form-group">
		            		<!-- <label for="inputName" class="control-label required">Token</label> -->
							<input type="<?=$type?>" placeholder="Digite o token" name="DES_TOKEN" id="DES_TOKEN" value="" maxlength="<?=$qtd_chartkn?>" class="form-control input-sm" style="height:43px; border-radius:0 3px 3px 0;">
							<div class="help-block with-errors"></div>
						</div>
					</div>

					<div class="col-md-4 col-xs-12 p-l-0">
						<!-- <label>&nbsp;</label> -->
						<a style="width: 100%; border-radius: 0!important;  height:43px; margin-top: 0px;" class="btn btn-success btn-sm f18" onclick='ajxValidaTkn()'>Clique aqui para validar o token</a>
					</div>
					
				</div>

			<div class="push20"></div>

			


<?php

		break;

		case 'TKN':

			include_once 'funWS/GeraToken.php';
			include_once 'funWS/buscaConsumidor.php';
			include_once 'funWS/buscaConsumidorCNPJ.php';

			$sql = "SELECT COD_EMPRESA, NOM_FANTASI, QTD_CHARTKN, TIP_TOKEN, TIP_RETORNO, NUM_DECIMAIS_B  FROM empresas where COD_EMPRESA = '".$cod_empresa."' ";
			//fnEscreve($sql);
			$arrayQuery = mysqli_query($connAdm->connAdm(),$sql);
			$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);
			
			if (isset($arrayQuery)){
				$cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
				$nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];
				$qtd_chartkn = $qrBuscaEmpresa['QTD_CHARTKN'];
				$tip_token = $qrBuscaEmpresa['TIP_TOKEN'];


				if($qrBuscaEmpresa['TIP_RETORNO'] == 1){
					$casasDec = 0;
				}else{
					$casasDec = $qrBuscaEmpresa['NUM_DECIMAIS_B'];
					$pref = "R$ ";
				}

				// echo($casasDec);
			}

			// $sql = "SELECT * FROM  USUARIOS
			// 		WHERE LOG_ESTATUS='S' AND
			// 			  COD_EMPRESA = $cod_empresa AND
			// 			  COD_TPUSUARIO = 10  limit 1  ";
			// //fnEscreve($sql);
			// $arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
			// $qrBuscaUsuTeste = mysqli_fetch_assoc($arrayQuery);
							
			// if (isset($arrayQuery)) {
			// 	$log_usuario = $qrBuscaUsuTeste['LOG_USUARIO'];
			// 	$des_senhaus = fnDecode($qrBuscaUsuTeste['DES_SENHAUS']);
			// }

			// $sqlUn = "SELECT COD_UNIVEND FROM UNIDADEVENDA 
			// 		  WHERE COD_EMPRESA = $cod_empresa 
			// 		  AND LOG_ESTATUS = 'S' 
			// 		  ORDER BY 1 ASC LIMIT 1";

			// $arrayUn = mysqli_query($connAdm->connAdm(), $sqlUn);
			// $qrLista = mysqli_fetch_assoc($arrayUn);

			// $idlojaKey = $qrLista['COD_UNIVEND'];
			// $idmaquinaKey = 0;
			// $codvendedorKey = 0;
			// $nomevendedorKey = 0;

			// $urltotem = $log_usuario.';'
			// 			.$des_senhaus.';'
			// 			.$idlojaKey.';'
			// 			.$idmaquinaKey.';'
			// 			.$cod_empresa.';'
			// 			.$codvendedorKey.';'
			// 			.$nomevendedorKey;

			$arrayCampos = explode(";", $urltotem);

			$num_celular = fnLimpaCampo(fnLimpaDoc($_POST['NUM_CELULAR']));
			$num_cgcecpf = fnLimpaCampo(fnLimpaDoc($_POST['NUM_CGCECPF']));
			$nom_cliente = fnLimpaCampo(fnAcentos($_POST['NOM_CLIENTE']));

			if($num_celular == ""){
				$num_celular = fnLimpaCampo(fnLimpaDoc($_POST['KEY_NUM_CELULAR']));
			}

			if($num_cgcecpf == ""){
				$num_cgcecpf = fnLimpaCampo(fnLimpaDoc($_POST['KEY_NUM_CGCECPF']));
				$k_num_cgcecpf = fnLimpaCampo(fnLimpaDoc($_POST['KEY_NUM_CGCECPF']));
				if(strlen($k_num_cgcecpf) <= '11'){

					// echo '<pre>';

		            $buscaconsumidor = fnconsulta(fnCompletaDoc($k_num_cgcecpf,'F'), $arrayCampos);

		            // print_r($buscaconsumidor);

		            // echo '</pre>';
		            
		        }else{

		        	// echo 'else';

		            $buscaconsumidor = fnconsultacnpf(fnCompletaDoc($k_num_cgcecpf,'J'), $arrayCampos); 
		            
				}
			}

			if($num_cgcecpf == "00000000000"){
				$num_cgcecpf = $num_celular;
			}

			$dadosenvio = array(
								 'tipoGeracao'=>'1',
								 'nome'=>"$nom_cliente",
								 'cpf'=>"$num_cgcecpf",
								 'celular'=>"$num_celular",
								 'email'=>''
								);

			$retornoEnvio = GeraToken($dadosenvio, $arrayCampos);

	     // echo '<pre>';
		 //    print_r($dadosenvio);
		 //    print_r($retornoEnvio);
		 //    echo '</pre>';
		 //    exit();

			$cod_envio = $retornoEnvio[body][envelope][body][geratokenresponse][retornatoken][coderro];

?>

<style>
	.p-r-0{
		padding-right: 0;
	}

	.p-l-0{
		padding-left: 0;
	}

	.img-g{
		display: none;
	}

	.img-m{
		display: block;
	}

	.img-p{
		display: none;
	}	

@media only screen and (min-device-width: 320px) and (max-device-width: 480px) {

	.img-g{
		display: none;
	}

	.img-m{
		display: none;
	}

	.img-p{
		display: block;
	}

	#roteiro{
		display: none;
	}	

	.p-r-0{
		padding-right: 15px;
		padding-left: 15px;
		margin-bottom: 10px;
	}

	.p-l-0{
		padding-left: 15px;
		padding-right: 15px;
	}

	.p-0{
		padding: 0;
	}

	.nav-tabs li{
		width:100%;
	}

	.nav-tabs li:last-child{
		margin-bottom:5px;
	}

    
}
 
/* (320x480) Smartphone, Portrait */
@media only screen and (device-width: 320px) and (orientation: portrait) {

	.img-g{
		display: none;
	}

	.img-m{
		display: none;
	}

	.img-p{
		display: block;
	}

	#roteiro{
		display: none;
	}	
    
	.p-r-0{
		padding-right: 15px;
		padding-left: 15px;
		margin-bottom: 10px;
	}

	.p-l-0{
		padding-left: 15px;
		padding-right: 15px;
	}

	.p-0{
		padding: 0;
	}

	.nav-tabs li{
		width:100%;
	}

	.nav-tabs li:last-child{
		margin-bottom:5px;
	}

}
 
/* (320x480) Smartphone, Landscape */
@media only screen and (device-width: 480px) and (orientation: landscape) {

	.img-g{
		display: none;
	}

	.img-m{
		display: none;
	}

	.img-p{
		display: block;
	}

	#roteiro{
		display: none;
	}	
    
	.p-r-0{
		padding-right: 15px;
		padding-left: 15px;
		margin-bottom: 10px;
	}

	.p-l-0{
		padding-left: 15px;
		padding-right: 15px;
	}

	.p-0{
		padding: 0;
	}

	.nav-tabs li{
		width:100%;
	}

	.nav-tabs li:last-child{
		margin-bottom:5px;
	}

}
 
/* (1024x768) iPad 1 & 2, Landscape */
@media only screen and (min-device-width: 768px) and (max-device-width: 1367px) and (orientation: landscape) {

	.img-g{
		display: none;
	}

	.img-m{
		display: none;
	}

	.img-p{
		display: block;
	}

	#roteiro{
		display: none;
	}
    
	.p-r-0{
		padding-right: 15px;
15px}

	.p-l-0{
		padding-left: 0;
	15p15px

}

/* (1280x800) Tablets, Portrait */
@media only screen and (max-width: 800px) and (orientation : portrait) {

	.img-g{
		display: none;
	}

	.img-m{
		display: none;
	}

	.img-p{
		display: block;
	}

	#roteiro{
		display: none;
	}
	 
	.p-r-0{
		padding-right: 15px;
		padding-left: 15px;
		margin-bottom: 10px;
	}

	.p-l-0{
		padding-left: 15px;
		padding-right: 15px;
	}

	.p-0{
		padding: 0;
	}

	.nav-tabs li{
		width:100%;
	}

	.nav-tabs li:last-child{
		margin-bottom:5px;
	}

}

/* (768x1024) iPad 1 & 2, Portrait */
@media only screen and (max-width: 768px) and (orientation : portrait) {

	.img-g{
		display: none;
	}

	.img-m{
		display: none;
	}

	.img-p{
		display: block;
	}

	#roteiro{
		display: none;
	}
    
	.p-r-0{
		padding-right: 15px;
		padding-left: 15px;
		margin-bottom: 10px;
	}

	.p-l-0{
		padding-left: 15px;
		padding-right: 15px;
	}

	.p-0{
		padding: 0;
	}

	.nav-tabs li{
		width:100%;
	}

	.nav-tabs li:last-child{
		margin-bottom:5px;
	}

}
 
/* (2048x1536) iPad 3 and Desktops*/
@media only screen and (min-device-width: 1536px) and (max-device-width: 2048px) {

	.img-g{
		display: block;
	}

	.img-m{
		display: none;
	}

	.img-p{
		display: none;
	}

	.p-r-0{
		padding-right: 0;
	}

	.p-l-0{
		padding-left: 0;
	}
		 
}

@media only screen and (min-device-width: 1100px) and (orientation : portrait) {

	.img-g{
		display: none;
	}

	.img-m{
		display: none;
	}

	.img-p{
		display: block;
	}

	#roteiro{
		display: none;
	}
    
	.p-r-0{
		padding-right: 15px;
		padding-left: 15px;
		margin-bottom: 10px;
	}

	.p-l-0{
		padding-left: 15px;
		padding-right: 15px;
	}

	.p-0{
		padding: 0;
	}

	.nav-tabs li{
		width:100%;
	}

	.nav-tabs li:last-child{
		margin-bottom:5px;
	}

}

@media (max-height: 824px) and (max-width: 416px){

	.img-g{
		display: none;
	}

	.img-m{
		display: none;
	}

	.img-p{
		display: block;
	}

	#roteiro{
		display: none;
	}
	
	.p-r-0{
		padding-right: 15px;
		padding-left: 15px;
		margin-bottom: 10px;
	}

	.p-l-0{
		padding-left: 15px;
		padding-right: 15px;
	}

	.p-0{
		padding: 0;
	}

	.nav-tabs li{
		width:100%;
	}

	.nav-tabs li:last-child{
		margin-bottom:5px;
	}

}	

/* (320x480) iPhone (Original, 3G, 3GS) */
@media (max-device-width: 737px) and (max-height: 416px) {

	.img-g{
		display: none;
	}

	.img-m{
		display: none;
	}

	.img-p{
		display: block;
	}

	#roteiro{
		display: none;
	}	

	.p-r-0{
		padding-right: 15px;
		padding-left: 15px;
		margin-bottom: 10px;
	}

	.p-l-0{
		padding-left: 15px;
		padding-right: 15px;
	}

	.p-0{
		padding: 0;
	}

	.nav-tabs li{
		width:100%;
	}

	.nav-tabs li:last-child{
		margin-bottom:5px;
	}

		
}
</style>

<div class="row">
	<div class="push"></div>
<?php 

			// echo "_".$cod_envio;

			if($cod_envio == 39){

				if($tip_token == 2){
					$type = "number";
				}else{
					$type = "text";
				}

?>

			

				<div class="col-md-12 col-xs-12 text-left">

					<div class="alert alert-success" role="alert">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					 	Token enviado! Verifique o SMS recebido, e digite o token no campo abaixo:
					</div>

				</div>

				<script type="text/javascript">$("#btnCadastro").fadeOut('fast');</script>

<?php 
			}else if($cod_envio == 0){
?>

				<div class="col-md-12 col-xs-12 text-left">

					<div class="alert alert-warning" role="alert">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					 	Token não enviado, pois não há celular/email de destino. É necessário configurar a matriz de campos.
					</div>

				</div>

				<script type="text/javascript">$("#btnCadastro").fadeOut('fast');</script>

<?php
				exit();
			}else if($cod_envio == 96){
?>

				<div class="col-md-12 col-xs-12 text-left">

					<div class="alert alert-danger" role="alert">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					 	Rotinas de token incompletas.<br>Contate o suporte.
					</div>

				</div>

				<script type="text/javascript">$("#btnCadastro").fadeOut('fast');</script>

<?php
				exit();
			}else{

?>

				<div class="col-md-12 col-xs-12 text-left">

					<div class="alert alert-success" role="alert">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					 	O token já enviado. Verifique o SMS recebido, e digite o token no campo abaixo. Caso não tenha recebido, por favor aguarde 5 minutos e tente enviar o token novamente.
					</div>

				</div>

				<script type="text/javascript">$("#btnCadastro").fadeOut('fast');</script>

<?php 
				
			}

?>

			
				<div id="camposToken">

					<div class="push20"></div>

					<div class="col-md-12 col-xs-12 text-left" id="erroTkn" style="display: none;">

						<div class="alert alert-danger" role="alert">
						<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						 	Token inválido.
						</div>

					</div>

					<div class="col-md-8 col-xs-12 text-left p-r-0">
						<div class="form-group">
		            		<!-- <label for="inputName" class="control-label required">Token</label> -->
							<input type="<?=$type?>" placeholder="Digite o token" name="DES_TOKEN" id="DES_TOKEN" value="" maxlength="<?=$qtd_chartkn?>" class="form-control input-sm" style="height:43px; border-radius:0 3px 3px 0;">
							<div class="help-block with-errors"></div>
						</div>
					</div>

					<div class="col-md-4 col-xs-12 p-l-0">
						<!-- <label>&nbsp;</label> -->
						<a style="width: 100%; border-radius: 0!important;  height:43px; margin-top: 0px;" class="btn btn-success btn-sm f18" onclick='ajxValidaTkn()'>Clique aqui para validar o token</a>
					</div>
					
				</div>

			<div class="push20"></div>

</div>


<?php

		break;

		case "VALTKNCAD":

			include_once '../totem/funWS/GeraToken.php';

			$des_token = fnLimpaCampo(fnLimpaDoc($_POST['DES_TOKEN']));
			$nom_cliente = fnLimpaCampo(fnAcentos($_POST['NOM_USUARIO']));
			$num_celular = fnLimpaCampo(fnLimpaDoc($_POST['NUM_CELULAR']));
			$num_cgcecpf = fnLimpaCampo(fnLimpaDoc($_POST['NUM_CGCECPF']));

			if($num_celular == ""){
				$num_celular = fnLimpaCampo(fnLimpaDoc($_POST['CAD_NUM_CELULAR']));
			}

			if($num_cgcecpf == ""){
				$num_cgcecpf = fnLimpaCampo(fnLimpaDoc($_POST['CAD_NUM_CGCECPF']));
			}

			if($num_cgcecpf == ""){
				$num_cgcecpf = fnLimpaCampo(fnLimpaDoc($_POST['KEY_NUM_CGCECPF']));
			}

			// $sql = "SELECT * FROM  USUARIOS
			// 		WHERE LOG_ESTATUS='S' AND
			// 			  COD_EMPRESA = $cod_empresa AND
			// 			  COD_TPUSUARIO = 10  limit 1  ";
			// //fnEscreve($sql);
			// $arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
			// $qrBuscaUsuTeste = mysqli_fetch_assoc($arrayQuery);
							
			// if (isset($arrayQuery)) {
			// 	$log_usuario = $qrBuscaUsuTeste['LOG_USUARIO'];
			// 	$des_senhaus = fnDecode($qrBuscaUsuTeste['DES_SENHAUS']);
			// }

			// $sqlUn = "SELECT COD_UNIVEND FROM UNIDADEVENDA 
			// 		  WHERE COD_EMPRESA = $cod_empresa 
			// 		  AND LOG_ESTATUS = 'S' 
			// 		  ORDER BY 1 ASC LIMIT 1";

			// $arrayUn = mysqli_query($connAdm->connAdm(), $sqlUn);
			// $qrLista = mysqli_fetch_assoc($arrayUn);

			// $idlojaKey = $qrLista['COD_UNIVEND'];
			// $idmaquinaKey = 0;
			// $codvendedorKey = 0;
			// $nomevendedorKey = 0;

			// $urltotem = $log_usuario.';'
			// 			.$des_senhaus.';'
			// 			.$idlojaKey.';'
			// 			.$idmaquinaKey.';'
			// 			.$cod_empresa.';'
			// 			.$codvendedorKey.';'
			// 			.$nomevendedorKey;

			$arrayCampos = explode(";", $urltotem);
			
			$dadosenvio = array(
									'tipoGeracao'=>'1',
									'token'=>"$des_token",
									'celular'=>"$num_celular",		
									'cpf'=>"$num_cgcecpf"
								);

			$retornoEnvio = ValidaToken($dadosenvio, $arrayCampos);

			// echo '<pre>';
		 //    print_r($dadosenvio);
		 //    print_r($retornoEnvio);
		 //    echo '</pre>';
		    // exit();

			$cod_envio = $retornoEnvio[body][envelope][body][validatokenresponse][retornatoken][coderro];

			if($cod_envio == 39){
				echo "validado";
			}else{
				echo 0;
			}

		break;

		default:

			// $cod_cliente = fnLimpaCampoZero(fnDecode($_POST['COD_CLIENTE']));	

			// $sql = "CALL `SP_EXCLUI_CLIENTES`($cod_cliente, $cod_empresa, '9998', 'exc', 3)";
		 //    // fnEscreve($sql);
		 //    mysqli_query(connTemp($cod_empresa, ''), $sql);
		break;
	}

?>