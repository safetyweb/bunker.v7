 <?php
 /*
A - Aguardando envio
C - Entregue com confirmação
O - Entregue sem confirmação
P - Não recebido
G - Erro - envio
S- erro
I- não enviado

*/
include '../../_system/_functionsMain.php';
 $conadmin = $connAdm->connAdm();

if($_GET['empresa']!='')
{	
 $empresaand="AND apar.COD_EMPRESA=".$_GET['empresa'];
 $disparoGET="and cod_disparo=".$_GET['disparo'];

}else{
 $datepesquiza=date('Y-m-d H:i:s', strtotime("-30 days"));
 $datepesquizaand="and DAT_CADASTR > '".$datepesquiza."'";
 //$empresaand="AND apar.COD_EMPRESA=60";
 $empresaand=""; 
 $disparoGET='';
} 
 $sqlempresa = "SELECT * FROM senhas_parceiro apar
				INNER JOIN parceiro_comunicacao par ON par.COD_PARCOMU=apar.COD_PARCOMU 
				WHERE par.COD_TPCOM='2' AND apar.COD_PARCOMU='16' AND  apar.LOG_ATIVO='S' $empresaand";
$rwempresa = mysqli_query($conadmin, $sqlempresa);
while ($rscomunicaao= mysqli_fetch_assoc($rwempresa)){ 

    $cod_empresa = $rscomunicaao['COD_EMPRESA'];
	$contemporaria = connTemp($cod_empresa, '');

	//unset($arraydadosinser);
	$arraydadosinser=array();
    $clientestatus="SELECT count(*) temnao,cod_empresa,cod_disparo from status_sms_nexux WHERE 
                                                                        cod_empresa=$cod_empresa 
                                                                        $datepesquizaand 
                                                                        $disparoGET    
                                                                        GROUP BY cod_disparo";
    echo '<br>'.$clientestatus.'<br>';
	$rwstatus1=mysqli_query($contemporaria,$clientestatus);
	while($rsclientestatus=mysqli_fetch_assoc($rwstatus1))
	{		
	
			if($rsclientestatus[temnao] >0 )
			{
				
				$contador="SELECT 
								 ifnull((SELECT COUNT(*) FROM status_sms_nexux where cod_empresa=$cod_empresa AND STATUS_ENVIO='A' AND cod_disparo=$rsclientestatus[cod_disparo] group by STATUS_ENVIO,COD_DISPARO),0) AS A,
								 ifnull((SELECT ifnull(COUNT(*),0) FROM status_sms_nexux where cod_empresa=$cod_empresa AND STATUS_ENVIO='C' AND cod_disparo=$rsclientestatus[cod_disparo] group by STATUS_ENVIO,COD_DISPARO),0) AS C,
								 ifnull((SELECT ifnull(count(*),0) FROM status_sms_nexux where cod_empresa=$cod_empresa AND STATUS_ENVIO='G' AND cod_disparo=$rsclientestatus[cod_disparo] group by STATUS_ENVIO,COD_DISPARO),0) AS G,
								 ifnull((SELECT ifnull(count(*),0) FROM status_sms_nexux where cod_empresa=$cod_empresa AND STATUS_ENVIO='O' AND cod_disparo=$rsclientestatus[cod_disparo] group by STATUS_ENVIO,COD_DISPARO),0) AS O,
								 ifnull((SELECT ifnull(count(*),0) FROM status_sms_nexux where cod_empresa=$cod_empresa AND STATUS_ENVIO='P' AND cod_disparo=$rsclientestatus[cod_disparo] group by STATUS_ENVIO,COD_DISPARO),0) AS P,
								 ifnull((SELECT ifnull(count(*),0) FROM status_sms_nexux where cod_empresa=$cod_empresa AND STATUS_ENVIO='S' AND cod_disparo=$rsclientestatus[cod_disparo] group by STATUS_ENVIO,COD_DISPARO),0) AS S,
								ifnull((SELECT ifnull(count(*),0) FROM status_sms_nexux where cod_empresa=$cod_empresa AND STATUS_ENVIO='I' AND cod_disparo=$rsclientestatus[cod_disparo] group by STATUS_ENVIO,COD_DISPARO),0) AS I,
								cod_disparo,
								 cod_campanha,
								 Id_tamplate,
								 status_envio,
								 DAT_CADASTR,
								 cod_empresa
									from status_sms_nexux 
									WHERE cod_empresa=$cod_empresa
									AND cod_disparo=$rsclientestatus[cod_disparo]
								group by COD_DISPARO";
				echo '<br>'.$contador.'<br>';
				$rwcontador=mysqli_query($contemporaria,$contador);					
				$rscontador=mysqli_fetch_all($rwcontador, MYSQLI_ASSOC);
				//echo '<pre>';
				//echo '<br>'.$contador.'<br>';
				//echo '</pre>';
				
				$dadosunificado=array();
			   foreach($rscontador as $key => $dadosreg)
			   {
					   //verificar se o cod_disparo ja existe na controle_entrega_sms
					$disparo="SELECT COUNT(*) temnao FROM controle_entrega_sms WHERE cod_empresa=$dadosreg[cod_empresa] AND 
																			cod_campanha=$dadosreg[cod_campanha] AND 
																			cod_disparo=$dadosreg[cod_disparo]";																
					$rsdisparo=mysqli_fetch_assoc(mysqli_query($contemporaria,$disparo));													
					if($rsdisparo[temnao]<=0)
					{
					   $qtd_disparados=$dadosreg[C] + $dadosreg[O];
					   $qtd_falha=$dadosreg[G]+$dadosreg[S]+$dadosreg[I];
					   $entregue="INSERT INTO controle_entrega_sms (cod_empresa, 
																	  cod_campanha_ext, 
																	  id_templete, 
																	  cod_campanha, 
																	  dat_cadastr, 
																	  cod_disparo, 
																	  dat_envio,
																	  qtd_disparados, 
																	  qtd_sucesso, 
																	  qtd_falha,
																	  QTD_AGUARADANDO,
																	  QTD_CCONFIRMACAO,
																	  QTD_SCONFIRMACAO,
																	  QTD_NRECEBIDO) 
															  VALUES 
																	  ('$cod_empresa', 
																	  '$dadosreg[cod_disparo]', 
																	  '$dadosreg[Id_tamplate]', 
																	  '$dadosreg[cod_campanha]', 
																	  '$dadosreg[DAT_CADASTR]', 
																	  '$dadosreg[cod_disparo]', 
																	  '$dadosreg[DAT_CADASTR]',
																	  '$qtd_disparados', 
																	  '$qtd_disparados', 
																	  '$qtd_falha',
																	  '$dadosreg[A]',
																	  '$dadosreg[C]',
																	  '$dadosreg[O]',
																	  '$dadosreg[P]'
																	  );";
						echo '<br>'.$entregue.'<br>';
					   $OK=mysqli_query($contemporaria,$entregue);
						if(!$OK)
						{}else{
							/*$delete="DELETE FROM status_sms_nexux WHERE  cod_disparo=$dadosreg[cod_disparo] and 
														 cod_empresa=$dadosreg[cod_empresa] and 
														 cod_campanha=$dadosreg[cod_campanha];";
							mysqli_query($contemporaria,$delete);	*/
						}		
					}else{
						$optoutsql="SELECT COUNT(*) as optout from sms_lista_ret
									WHERE cod_empresa=$cod_empresa AND ID_DISPARO='$dadosreg[cod_disparo]' AND cod_OPTOUT_ATIVO='1'
									GROUP BY cod_OPTOUT_ATIVO";
						$rs_optout= mysqli_fetch_assoc(mysqli_query($contemporaria,$optoutsql));
						if($rs_optout[optout]==''){$opout='0';}else{$opout=$rs_optout[optout];}	
						
						$qtd_disparados=$dadosreg[C] + $dadosreg[O];
						$qtd_falha=$dadosreg[G]+$dadosreg[S]+$dadosreg[I];
						//verificar se o quantidade de disparo e diferente da table
					
						$updateentrega="UPDATE controle_entrega_sms SET 
														  dat_atualizacao='".date('Y-m-d H:i:s')."',	
														  qtd_disparados='$qtd_disparados', 
														  qtd_sucesso ='$qtd_disparados', 
														  qtd_falha='$qtd_falha',
														  QTD_AGUARADANDO='$dadosreg[A]',
														  QTD_CCONFIRMACAO='$dadosreg[C]',
														  QTD_SCONFIRMACAO='$dadosreg[O]',
														  QTD_NRECEBIDO='$dadosreg[P]',
														  qtd_optout='$opout'
						WHERE  cod_empresa=$cod_empresa and 
						      cod_disparo='$dadosreg[cod_disparo]' and 
							  cod_campanha='$dadosreg[cod_campanha]'";
						echo '<br>'.$updateentrega.'<br>';
						$OK=mysqli_query($contemporaria,$updateentrega);				
					   if(!$OK)
						{
						
						}else{
							/*$delete="DELETE FROM status_sms_nexux WHERE  cod_disparo=$dadosreg[cod_disparo] and 
														 cod_empresa=$dadosreg[cod_empresa] and 
														 cod_campanha=$dadosreg[cod_campanha];";
							mysqli_query($contemporaria,$delete);	*/
						}		
						
					}		
				}

			}
    }
}	
?>