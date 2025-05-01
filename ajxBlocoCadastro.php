<?php include "_system/_functionsMain.php"; 
include './totem/funWS/buscaConsumidor.php';
include './totem/funWS/buscaConsumidorCNPJ.php';
//echo fnDebug('true');
//fnMostraForm();

$cartao = "";
@$cpf=$_REQUEST['c1'];
if($_REQUEST['c10'] && $_REQUEST['c10'] != ""){
	$cpf = $_REQUEST['c10'];
	$cartao = "true";
}
@$COD_UNIVEND=$_REQUEST['COD_UNIVEND'];
@$cod_empresa=$_REQUEST['COD_EMPRESA'];

@$COD_USUARIO=$_SESSION["SYS_COD_USUARIO"];
@$NOM_USUARIO=$_SESSION["SYS_NOM_USUARIO"];

$sql = "select LOG_USUARIO, DES_SENHAUS,COD_UNIVEND from usuarios where cod_empresa = ".$cod_empresa." AND COD_TPUSUARIO=10 and DAT_EXCLUSA is null limit 1 ";
//fnEscreve($sql);

$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());	
$qrBuscaUsuario = mysqli_fetch_assoc($arrayQuery);
$log_usuario = $qrBuscaUsuario['LOG_USUARIO'];								
$des_senhaus = $qrBuscaUsuario['DES_SENHAUS'];	
	
$arrayCampos=array( '0'=>$log_usuario,
                    '1'=> fnDecode($des_senhaus),
                    '2'=>$COD_UNIVEND,
                    '3'=>$_SESSION["USU_COD_USUARIO"],
                    '4'=>$cod_empresa
                    );

// fnEscreve($cpf); 
                   
if(strlen(fnLimpaDoc($cpf))=='11' || $cartao == "true"){    
	$buscaconsumidor = fnconsulta(fnLimpaDoc($cpf), $arrayCampos);
}else{
	$buscaconsumidor = fnconsultacnpf(fnLimpaDoc($cpf), $arrayCampos);  
}  


if($buscaconsumidor['msg']=='CPF digitado é inválido!')
{
//  header("Refresh:0;url=http://adm.bunker.mk/action.do?mod=apC2A333ahM1VYcC2A2&id=QunXraEOVrgC2A2&erro=1");   
echo '<META HTTP-EQUIV="Refresh" Content="0; URL='.fnurl ().'/action.do?mod='. fnEncode('1240').'&id='.fnEncode($cod_empresa).'&erro=1">';

} 
   
// busca info empresa
$sqlEmp = "SELECT TIP_RETORNO, NUM_DECIMAIS FROM EMPRESAS WHERE COD_EMPRESA = $cod_empresa";
$qrEmp = mysqli_fetch_assoc(mysqli_query($connAdm->connAdm(),$sqlEmp));

if($qrEmp['TIP_RETORNO'] == 1){
	$casasDec = 0;
}else{
	$casasDec = $qrEmp['NUM_DECIMAIS'];
}

if($buscaconsumidor['cartao'] != ""){
	$c1 = $buscaconsumidor['cartao'];
	$c10 = $buscaconsumidor['cartao'];
}

if($c10 != ""){
	$readonly = "readonly";
}else{
	$readonly = "";
}

unset($_POST);

?>


<div class="push"></div>

<!-- -------------- bloco saldo  --------------- -->
<?php
        //busca saldos de resgate
        $cod_clientesql="select COD_CLIENTE from clientes where cod_empresa=$cod_empresa and NUM_CGCECPF='".fnLimpaDoc($cpf)."'";
       // fnEscreve($cod_clientesql);
        $cod_cliereturn=mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa,''), $cod_clientesql));
	$sql = "CALL SP_CONSULTA_SALDO_CLIENTE('".$cod_cliereturn['COD_CLIENTE']."') ";
	//fnEscreve($sql);
	$qrBuscaSaldoResgate = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa,''),$sql));
        
        
	if (isset($qrBuscaSaldoResgate)){		
		$credito_disponivel = $qrBuscaSaldoResgate['CREDITO_DISPONIVEL'];
                $credito_aliberar = $qrBuscaSaldoResgate['TOTAL_CREDITO']-$qrBuscaSaldoResgate['CREDITO_DISPONIVEL'];
                $saldototal =$qrBuscaSaldoResgate['TOTAL_CREDITO'];  
	}

   
?>

<div class="blkSaldo row">
	<div class="col-md-3 "></div>

	<div class="col-md-6" style="padding: 0 25px;">
			<?php 
				if ( !empty($buscaconsumidor['datanascimento'])){
					$niver = $buscaconsumidor['datanascimento'];
					$arrayNiver = explode('/', $niver);    
					$mes_atual = date("m");
					$mes_niver = $arrayNiver[1];
					//fnEscreve($mes_niver);
					//fnEscreve($mes_atual);
				}
				
				if ($mes_atual == $mes_niver ){
			?>
			
			<div class="alert alert-warning top30" role="alert" id="msgRetorno">
				<button type="button" class="close" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<i class="fa fa-gift fa-2x" aria-hidden="true"></i> &nbsp; <span class="f18">Mês de aniversário do cliente </span>
			</div>
			
			<?php 
				}	
			?>
			
			<div class="push"></div>
	
			<div class="col-md-4 blkSaldo-left">
				<h3 style="color: white; margin: auto;" class=""><?php echo fnValor($credito_disponivel,$casasDec);?></h3>						
				<span>Saldo Disponível</span>
			</div>
			
			<div class="col-md-4 blkSaldo-left blkSaldo-middle">
				<h3 style="color: white; margin: auto;"><?php echo fnValor($credito_aliberar,$casasDec); ?></h3> 						
				<span  class="resgatado">Saldo a Liberar</span>
			</div>
			
			<div class="col-md-4 blkSaldo-left blkSaldo-lost">
				<h3 style="color: white; margin: auto;"><?php echo fnValor($saldototal,$casasDec); ?></h3> 			   
			   <span class="liberar">Saldo Total</span>
			</div>						
	</div>
</div>	

<div class="push20"></div>

<!-- -------------- bloco saldo  --------------- -->							

<div class="col-md-3"></div>	

<div class="col-md-6">
	<div class="form-group">
		<label for="inputName" class="control-label"></label>
                <input type="text" class="form-control input-lg" name="c2" id="c2" value="<?php echo $buscaconsumidor['nome'];?>" placeholder="Nome" required>
		<div class="help-block with-errors"></div>
	</div>
</div>

<div class="col-md-3"></div>	

<div class="push10"></div> 	

<div class="col-md-3"></div> 

<div class="col-xs-6 col-sm-6 col-md-3 col-lg-3">
	<div class="form-group">
		<label for="inputName" class="control-label"></label>
		<input type="text" class="form-control input-lg text-center data" name="c6" id="c6" value="<?php echo $buscaconsumidor['datanascimento'];?>" placeholder="Data de Nascimento" required>
		<div class="help-block with-errors"></div>
	</div>
</div>

<div class="col-xs-6 col-sm-6 col-md-3 col-lg-3">
	<div class="form-group">
		<select data-placeholder="Selecione o sexo" name="sexo" id="sexo" class="chosen-select-deselect">
			<option value=""></option>					
			<?php 
			
				$sql = "select COD_SEXOPES, DES_SEXOPES from sexo order by DES_SEXOPES";
				$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
			
				while ($qrLayout = mysqli_fetch_assoc($arrayQuery)) {														
					echo "<option value='".$qrLayout['COD_SEXOPES']."'>".$qrLayout['DES_SEXOPES']."</option>"; 
				}											
			?>	 							
		</select>
		<script>$("#sexo").val("<?php echo $buscaconsumidor['sexo']; ?>").trigger("chosen:updated"); </script>	
		<div class="help-block with-errors"></div>
	</div>
</div>				

<div class="col-md-3"></div> 
	
<div class="push10"></div> 	

<div class="col-md-3"></div>	

<div class="col-md-6">
	<div class="form-group">
		<label for="inputName" class="control-label"></label>
		<input type="email" class="form-control input-lg" name="c3" id="c3" value="<?php echo $buscaconsumidor['email']; ?>" placeholder="e-Mail">
		<div class="help-block with-errors"></div>
	</div>
</div> 

<div class="col-md-3"></div>	
				
<div class="push10"></div> 	

<div class="col-md-3"></div>	

<div class="col-md-6">
	<div class="form-group">
		<label for="inputName" class="control-label"></label>
		<input type="text" class="form-control input-lg text-center celular" name="c4" id="c4" value="<?php fnCorrigeTelefone($buscaconsumidor['telcelular']); ?>" placeholder="Telefone Celular" required>
		<div class="help-block with-errors"></div>
	</div>
</div>
 
<div class="col-md-3"></div>

<div class="push10"></div>

<?php

switch ($cod_empresa) {
	case 7:
    case 91: //Renaza
    case 176: // AMIGAO
    case 178: // central


?>

		<div class="col-md-3"></div>				
		
		<div class="push10"></div> 				
		
		<div class="col-md-3"></div>


		<div class="col-md-6 col-sm-10">
			<div class="input-group">
				<span class="input-group-btn">
				<a type="button" name="btnBusca" id="btnBusca"  class="btn btn-primary btn-lg addBox" data-url="action.php?mod=<?php echo fnEncode(1601)?>&id=<?php echo fnEncode($cod_empresa)?>&idc=<?=fnEncode($cod_cliente)?>&pop=true" data-title="Troca de Cartão"><i class="fa fa-search" aria-hidden="true" style="padding-top: 3px;" ></i></a>
				</span>
				<input type="text" name="c10" id="c10" readonly maxlength="50" class="form-control input-lg" style="border-radius:0 3px 3px 0;" placeholder="Número do Cartão" data-error="Campo obrigatório" value="<?=$c10?>" required>
			</div>
		</div>

<?php

    break;
	case 121: //águia postos
	?>

		<div class="col-md-3"></div>				
		
		<div class="push10"></div> 				
		
		<div class="col-md-3"></div>

		<div class="col-md-6 col-sm-10">
			<div class="form-group">
				<label for="inputName" class="control-label"></label>
				<input type="text" class="form-control input-lg text-center cartao" name="c10" id="c10" value="<?=$c10?>" maxlength="10" placeholder="Número do Cartão" autocomplete="off" <?=$readonly?> required>
				<div class="help-block with-errors"></div>
			</div>
		</div>

	<?php 
		
	break;
	case 143: //águia postos

	?>

		<div class="col-md-3"></div>				
		
		<div class="push10"></div> 				
		
		<div class="col-md-3"></div>

		<div class="col-md-6 col-sm-10">
			<div class="form-group">
				<label for="inputName" class="control-label"></label>
				<input type="text" class="form-control input-lg text-center cartao" name="c10" id="c10" value="<?=$c10?>" maxlength="10" placeholder="Número do Cartão" autocomplete="off" <?=$readonly?> required>
				<div class="help-block with-errors"></div>
			</div>
		</div>

	<?php 
		
	break;

	case 28: // golden motos

	if($buscaconsumidor['codatendente'] != ""){
		$orAtend = "OR COD_EXTERNO = $buscaconsumidor[codatendente]";
		$disable = "disabled";
	}else{
		$orAtend = "";
		$disable = "required";
	}

?>

		<input type="hidden" name="c8" id="c8" value="<?=$buscaconsumidor['codatendente']?>">
		<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-md-offset-3">
			<div class="form-group">
				<select data-placeholder="Selecione o atendente" name="COD_ATENDENTE" id="COD_ATENDENTE" autocomplete="off" class="chosen-select-deselect" <?=$disable?>>
					<option value=""></option>					
					<?php 	

						$sql = "SELECT COD_EXTERNO, NOM_USUARIO FROM usuarios WHERE cod_tpusuario IN (11, 7) and cod_empresa = $cod_empresa AND cod_univend=$COD_UNIVEND AND cod_exclusa=0 $orAtend";
						$arrayQuery = mysqli_query($connAdm->connAdm(),$sql);
						// fnEscreve($sql);
						
						while ($qrListaProfi = mysqli_fetch_assoc($arrayQuery))
						  {														
							echo"
								  <option value='".$qrListaProfi['COD_EXTERNO']."'>".$qrListaProfi['NOM_USUARIO']."</option> 
								"; 
							  }											
					?>	
				</select>
			</div>
		</div>
		<script type="text/javascript">

			$("#COD_ATENDENTE").chosen();

			if($('#c8').val() != 0){
				$('#COD_ATENDENTE').val("<?=$buscaconsumidor['codatendente']?>").trigger('chosen:updated');
				if($('#COD_ATENDENTE').val()){
					// alert($('#COD_ATENDENTE').val());
					$('#COD_ATENDENTE').prop('disabled', true);
				}
				// alert("<?=$buscaconsumidor['codatendente']?>");
			}

			$('#COD_ATENDENTE').change(function(){
				$('#c8').val($('#COD_ATENDENTE').val());
				
			});

			$('#COD_ATENDENTE').prop('required', true);

		</script>

<?php 
	break;
}
?>

<div class="push30"></div> 				

<div class="col-md-3"></div>	

<div class="col-md-6">
	<button type="button" name="ATUALIZA" id="ATUALIZA" class="btn btn-success btn-lg btn-block getBtn" tabindex="5"><i class="fa fa-1x fa-shopping-basket" aria-hidden="true"></i>&nbsp; Atualizar Cadastro e Continuar Compra </button>
	<div class="push10"></div> 
	<a name="HOME" id="HOME" class="btn btn-info btn-lg btn-block"><i class="fa fa-1x fa-home" aria-hidden="true"></i>&nbsp; Voltar Menu Principal </a>
</div>

<div class="col-md-3"></div>
<?php 
	if($cartao == 'true'){
		$cpf = "";
	} 
?>
<input type="hidden" class="form-control input-lg" name="c1" id="c1" value="<?php echo $cpf; ?>">
<input type="hidden" class="form-control input-lg" name="COD_UNIVEND" id="COD_UNIVEND" value="<?php echo @$COD_UNIVEND; ?>">
		