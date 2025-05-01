<?php include "_system/_functionsMain.php"; 

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

	$buscaAjx1 = fnLimpacampo($_GET['ajx1']);
	//$buscaAjx2 = fnLimpacampo($_GET['ajx2']);
	
	$contaLoop = 1;
	$montaUpdate = "update campanha set ord_online = null, log_online = 'N';  \r\n". PHP_EOL;
	$valores = json_decode($_GET['ajx2'], true);
	foreach($valores as $codigo) {
		$montaUpdate .= "update campanha set ord_online = " . $contaLoop . ", log_online = 'S' where cod_campanha = ".$codigo. "; \r\n". PHP_EOL ;
		$contaLoop ++;
	}
	
	$arrayQuery2 = mysqli_multi_query(connTemp($buscaAjx1,''),$montaUpdate) or die(mysqli_error());
	
?>
			
	<?php
	$sql = " SELECT campanha.*,campanharegra.*,vantagemextra.*,campanharesgate.*,
				tipocampanha.ABV_TPCAMPA FROM CAMPANHA
				left join campanharegra on CAMPANHA.COD_CAMPANHA=campanharegra.COD_CAMPANHA 
				left join vantagemextra on CAMPANHA.COD_CAMPANHA=vantagemextra.COD_CAMPANHA 
				left join campanharesgate on CAMPANHA.COD_CAMPANHA=campanharesgate.COD_CAMPANHA
				inner join tipocampanha on campanha.TIP_CAMPANHA=COD_TPCAMPA 
				where campanha.COD_EMPRESA=$buscaAjx1 and campanha.LOG_REALTIME = 'S' and campanha.LOG_ONLINE = 'S' 
				order by ord_online";
		//fnEscreve($sql);
		
		$arrayQuery = mysqli_query(connTemp($buscaAjx1,''),$sql) or die(mysqli_error());
		
		$count=0;
		while ($qrListaCampanha = mysqli_fetch_assoc($arrayQuery))
		  {	                                           
			$count++;                                                                                                
	?>										  

		  
		<div id="widget-<?php echo $qrListaCampanha['COD_CAMPANHA']?>" class="box widget widget-default widget-item-icon shadow" style="width: 100%;">
			<div class="widget-item-left" style="width: 50px;">
				<span class="fa <?php echo $qrListaCampanha['DES_ICONE'] ?> " style="color: #<?php echo $qrListaCampanha['DES_COR'] ?>"></span>
			</div>                             
			<div class="widget-data" style="padding-left: 70px;">
				<div class="widget-title" style="font-size: 14px;"><?php echo $qrListaCampanha['DES_CAMPANHA'] ?></div>
				<div class="widget-subtitle">
				<b>Tipo:</b> <?php echo $qrListaCampanha['ABV_TPCAMPA'] ?> <br/>
				<b>Hit:</b> <?php echo fnValor($qrListaCampanha['NUM_PESSOAS'],0); ?> <br/>
				<b>Reversão:</b> <?php echo fnValor($qrListaCampanha['PCT_VANTAGEM'],2); ?> <br/>
				</div>
			</div>      
		</div>                            

	<?php
		  }											

	?>
