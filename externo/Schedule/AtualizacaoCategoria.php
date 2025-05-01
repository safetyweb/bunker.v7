<?php
$diasemana = array('Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sabado');
$diasemana_numero = date('w', strtotime(date('Y-m-d')));
	
require '../../_system/_functionsMain.php';
$conadmmysql=$connAdm->connAdm();
//capturando as empresas com a comunicação
$sqlEmpresa= "SELECT COD_EMPRESA,NOM_FANTASI from empresas where LOG_CATEGORIA='S' AND LOG_ATIVO='S'";
$rwempresas=mysqli_query($conadmmysql,$sqlEmpresa);
while($rsempresas=mysqli_fetch_assoc($rwempresas))
{
	$contemporaria = connTemp($rsempresas['COD_EMPRESA'], '');
	
	//pegar o periodo na com TEMP
    //executar todo domingo se for Igual a S
	$sqlparamentros="SELECT * FROM EMPRESA_CLASSIFICA WHERE cod_empresa=".$rsempresas['COD_EMPRESA'];
	$rsparamentros=mysqli_fetch_assoc(mysqli_query($contemporaria,$sqlparamentros));
   if($rsparamentros['QTD_MRECLASS']!='')
   {
		IF($diasemana[$diasemana_numero]=='Domingo')
		{
			if($rsparamentros['QTD_MRECLASS']=='S')
			{
				echo '<br>HOJE é : Domingo<br>';  
				$sqlcategoria="call SP_RECLASSIFICA_CATEGORIA(".$rsempresas['COD_EMPRESA'].");";
				mysqli_query($contemporaria,$sqlcategoria);
			}
		} 
		if($rsparamentros['QTD_MRECLASS']=='M')
		{
			if(date('d')=='01')
			{
				$sqlcategoria="call SP_RECLASSIFICA_CATEGORIA(".$rsempresas['COD_EMPRESA'].");";
				mysqli_query($contemporaria,$sqlcategoria);
			}
		}
   }

	
	mysqli_close($contemporaria);	
}