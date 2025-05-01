<?php
	
	//echo fnDebug('true');
	
	$id = fnLimpaCampo(fnDecode($_GET['id']));
	$idR = fnDecode($_GET['idR']);

	//fnEscreve($codMaquina);
	//fnEscreve(is_numeric(fnDecode($codMaquina)));

	//select dinâmico do relatório
	$sql="select origem_retorno
                from msg_venda
                where  ID = $idR 
                     AND  case when origem_retorno !='' then '1'
                               when origem_retorno IS NOT NULL then '2'
                                ELSE '0' END IN ('1','2')
                      AND origem_retorno NOT IN ('OK')
      ";   
	
	//fnEscreve($sql);
	//fnEscreve($idR);
	$arrayQuery = mysqli_query(connTemp($id, ''),$sql);	
	$qrBuscaXml = mysqli_fetch_assoc($arrayQuery);
	$des_venda = $qrBuscaXml['origem_retorno'];							
	
	//fnMostraForm();
	

$xml = $des_venda;

$dom = new DOMDocument();

// Initial block (must before load xml string)
$dom->preserveWhiteSpace = false;
$dom->formatOutput = true;
$dom->loadXML($xml);
$out = $dom->saveXML();

$outLimpo = str_replace('<?xml version="1.0"?>', '', $out);
 $outLimpo=html_entity_decode($outLimpo, ENT_QUOTES | ENT_XML1, 'UTF-8');	
?>

<div class="tab-content">
	<!-- aba webservice -->
	<div id="webservice" class="tab-pane fade in active">

<pre>
<xmp>
<?php print_R($outLimpo); ?>
</xmp>
</pre>

		<div id="AREACODE_OFF" style="display: none;">
		<textarea id="AREACODE" rows="1" style="width: 100%;">
<?php print_R($outLimpo); ?>
		</textarea>
		</div>
		
		
		<div class="push10"></div>
		<hr>	
		<div class="form-group text-right col-lg-12">
			
			  <button type="button" name="COPIA" id="COPIA" class="btn btn-info getBtn"><i class="fa fa-copy" aria-hidden="true"></i>&nbsp; Copiar para o Clipboard</button>
			
		</div>
		
		<div class="push50"></div>
		
	</div>

	
	<script type="text/javascript">
		
		$("#COPIA").click(function(){			
			$("#AREACODE_OFF").show();
			$("#AREACODE").select();
			document.execCommand('copy');
			$("#AREACODE_OFF").hide();
		});	

	</script>
