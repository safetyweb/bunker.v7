<?php include "_system/_functionsMain.php"; 
$cod_empresa = fnLimpacampo($_GET['ajx1']);
$cod_mapa = fnLimpacampo($_GET['ajx2']);
//fnEscreve($buscaAjx2);



$sql = "SELECT
		NOM_MAPA_ITEM,
		(SELECT COUNT(0) FROM mapas_itens_dados WHERE mapas_itens_dados.COD_MAPA_ITEM=mapas_itens.COD_MAPA_ITEM) AS QTD_ITENS
	FROM MAPAS_ITENS WHERE COD_EMPRESA='$cod_empresa' AND COD_MAPA='$cod_mapa' ORDER BY NOM_MAPA_ITEM";
//echo $sql;
$arrayQuery = mysqli_query(connTemp($cod_empresa,""),$sql) or die(mysqli_error());

$count=0;
while ($qrBusca = mysqli_fetch_assoc($arrayQuery))
  {
	echo"
		<tr>
		  <td>".$qrBusca['NOM_MAPA_ITEM']."</td>
		  <td class='text-right'>".$qrBusca['QTD_ITENS']."</td>
		</tr>
		"; 
	  }											
