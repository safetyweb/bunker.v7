<?php
	
	//echo fnDebug('true');
	
	$id = fnLimpaCampo($_GET['id']);
	$codUnivend = fnLimpaCampo($_GET['codUnivend']);
	$codUsuario = fnLimpaCampo($_GET['codUsuario']);
	$codMaquina = fnLimpaCampo($_GET['codMaquina']);
	//fnEscreve($codMaquina);
	//fnEscreve(is_numeric(fnDecode($codMaquina)));
	
	if (fnDecode($codMaquina) == 0){
		$codMaquina = "";
	}else {
		$codMaquina = fnDecode($codMaquina);
		}
												
	$sql = "select LOG_USUARIO, DES_SENHAUS from usuarios where COD_EMPRESA = ".fnDecode($id)." and COD_USUARIO = ".fnDecode($codUsuario)." and DAT_EXCLUSA is null ";
	
	//fnEscreve($sql);
	$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());	
	$qrBuscaUsuario = mysqli_fetch_assoc($arrayQuery);
	$log_usuario = $qrBuscaUsuario['LOG_USUARIO'];								
	$des_senhaus = $qrBuscaUsuario['DES_SENHAUS'];								
	
	//fnEscreve($des_senhaus);
	//fnMostraForm();
?>

<ul class="nav nav-tabs">
  <li class="active"><a data-toggle="tab" href="#webservice">Acesso Web Service</a></li>
  <li><a data-toggle="tab" href="#totem">Acesso Totem</a></li>
  <li><a data-toggle="tab" href="#usuario">Usuário Base64</a></li>
</ul>

<div class="push30"></div>

<div class="tab-content">
	<!-- aba webservice -->
	<div id="webservice" class="tab-pane fade in active">

<pre>
<xmp>
  <dadoslogin>
	<login><?php echo $log_usuario; ?></login>
	<senha><?php echo fnDecode($des_senhaus); ?></senha>
	<idloja><?php echo fnDecode($codUnivend); ?></idloja>
	<idmaquina><?php echo $codMaquina; ?></idmaquina>
	<idcliente><?php echo fnDecode($id); ?></idcliente>
	<codvendedor>??</codvendedor>
	<nomevendedor>??</nomevendedor>
  </dadoslogin>
</xmp>
</pre>

		<div id="AREACODE_OFF" style="display: none;">
		<textarea id="AREACODE" rows="1" style="width: 100%;">
		  <dadoslogin>
			<login><?php echo $log_usuario; ?></login>
			<senha><?php echo fnDecode($des_senhaus); ?></senha>
			<idloja><?php echo fnDecode($codUnivend); ?></idloja>
			<idmaquina><?php echo $codMaquina; ?></idmaquina>
			<idcliente><?php echo fnDecode($id); ?></idcliente>
			<codvendedor>??</codvendedor>
			<nomevendedor>??</nomevendedor>
		  </dadoslogin>
		</textarea>
		</div>

		<div class="push10"></div>
		<hr>	
		<div class="form-group text-right col-lg-12">
			
			  <button type="button" name="COPIA" id="COPIA" class="btn btn-info getBtn"><i class="fa fa-copy" aria-hidden="true"></i>&nbsp; Copiar para o Clipboard</button>
			
		</div>
		
	</div>
	
	<!-- aba totem -->
	<div id="totem" class="tab-pane fade">

<?php 
$idlojaKey = 0;
$idmaquinaKey = 0;
$codvendedorKey = 0;
$nomevendedorKey = 0;

$urltotem = fnEncode($log_usuario.';'
				.fnDecode($des_senhaus).';'
				.$idlojaKey.';'
				.$idmaquinaKey.';'
				.fnDecode($id).';'
				.$codvendedorKey.';'
				.$nomevendedorKey
);

?>	

<pre>
<xmp>
http://totem.bunker.mk/?key=<?php echo $urltotem; ?>
</xmp>
</pre>

		<div id="AREACODE_OFF2" style="display: none;">
		<textarea id="AREACODE2" rows="1" style="width: 100%;">
			http://totem.bunker.mk/?key=<?php echo $urltotem; ?>
		</textarea>
		</div>
		
		<div class="push10"></div>
		<hr>	
		<div class="form-group text-right col-lg-12">
			  
			  <a href="http://totem.bunker.mk/?key=<?php echo $urltotem; ?>" target="_blank" class="btn btn-default"><i class="fa fa-share" aria-hidden="true"></i>&nbsp; Acessar Totem</a>
			  &nbsp;&nbsp;&nbsp;
			  <button type="button" name="COPIA2" id="COPIA2" class="btn btn-info getBtn"><i class="fa fa-copy" aria-hidden="true"></i>&nbsp; Copiar para o Clipboard</button>
			
		</div>
	
	</div>


	<!-- aba totem -->
	<div id="usuario" class="tab-pane fade">

<?php 
$webhook = 'webhook';



$usuarioEncode = $log_usuario.';'.fnDecode($des_senhaus).';'.fnDecode($codUnivend).';'.$webhook.';'.fnDecode($id);

$autoriz = base64_encode(fnEncode($usuarioEncode));

?>	

<pre>
<xmp>
<?php echo $autoriz; ?>
</xmp>
</pre>

		<div id="AREACODE_OFF3" style="display: none;">
		<textarea id="AREACODE3" rows="1" style="width: 100%;">
			<?php echo $autoriz; ?>
		</textarea>
		</div>
		
		<div class="push10"></div>
		<hr>	
		<div class="form-group text-right col-lg-12">
			  
			  <button type="button" name="COPIA3" id="COPIA3" class="btn btn-info getBtn"><i class="fa fa-copy" aria-hidden="true"></i>&nbsp; Copiar para o Clipboard</button>
			
		</div>
	
	</div>
	
	<script type="text/javascript">
		
		$("#COPIA").click(function(){			
			$("#AREACODE_OFF").show();
			$("#AREACODE").select();
			document.execCommand('copy');
			$("#AREACODE_OFF").hide();
		});	
		
		$("#COPIA2").click(function(){			
			$("#AREACODE_OFF2").show();
			$("#AREACODE2").select();
			document.execCommand('copy');
			$("#AREACODE_OFF2").hide();
		});			

		$("#COPIA3").click(function(){			
			$("#AREACODE_OFF3").show();
			$("#AREACODE3").select();
			document.execCommand('copy');
			$("#AREACODE_OFF3").hide();
		});		

	</script>
