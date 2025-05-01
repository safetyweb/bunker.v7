<?php
include '../_system/_functionsMain.php';
$cod_empresa='103';
$contemporaria=connTemp($cod_empresa,'');

$conadmf=$connAdm->connAdm ();


	$cliente="SELECT NUM_CGCECPF FROM clientes WHERE cod_empresa=$cod_empresa and  nom_cliente = 'anyType{}'";
	$rs=mysqli_query($contemporaria, $cliente);
	while ($row = mysqli_fetch_assoc($rs)) 
	{  	
	
			 ob_start();
			$sqlDT="SELECT * FROM log_cpf WHERE CPF='".fnCompletaDoc($row['NUM_CGCECPF'],'F')."'";
			echo $sqlDT;
			$rwDT=mysqli_query($conadmf,$sqlDT);
			while($rsdt=mysqli_fetch_assoc($rwDT))
			{
					echo '<pre>';
						print_r($rsdt);
						echo '</pre>';

					 $update1='UPDATE clientes SET  
								  NOM_CLIENTE="'.$rsdt[NOME].'"											
								  WHERE  NUM_CGCECPF="'.$row['NUM_CGCECPF'].'";'; 

				//	  mysqli_query($contemporaria, $update1);
				  echo '<br>'.$update1.'<br>'; 

		
		
		}
	
	ob_end_flush();
	ob_flush();
	flush();        
}