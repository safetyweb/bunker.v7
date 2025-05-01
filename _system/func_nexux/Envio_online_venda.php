<?php
//include '../_functionsMain.php';
function envio_teste_sms($array)
{

		$campanhaSMS="SELECT 	G.COD_EMPRESA,
								G.COD_CAMPANHA,
								G.LOG_STATUS,
								G.LOG_PROCESS,
								G.HOR_ESPECIF,
								T.DES_TEMPLATE,
								C.LOG_ATIVO,
								C.DAT_INI,
								C.DAT_FIM,
								C.LOG_PROCESSA_SMS	
					FROM gatilho_sms G
					INNER JOIN campanha C ON C.COD_CAMPANHA=G.COD_CAMPANHA
					INNER JOIN mensagem_sms M ON M.COD_CAMPANHA=G.COD_CAMPANHA
					INNER JOIN template_sms T ON T.COD_TEMPLATE=M.COD_TEMPLATE_SMS
					WHERE G.cod_empresa='".$array[COD_EMPRESA]."' AND G.TIP_GATILHO='vendaOn';";
		$rwcampanhaSMS=mysqli_query($array[CONNTMP],$campanhaSMS);			
		$rscampanhaSMS=mysqli_fetch_assoc($rwcampanhaSMS);
		$COD_CAMPANHA=$rscampanhaSMS['COD_CAMPANHA'];
	
			if($rscampanhaSMS[LOG_PROCESSA_SMS]=='N' || $rscampanhaSMS[LOG_ATIVO]=='')
			{			
				
					return array('msgerro'=>'Campanha Inativa',
								  'coderro'=>'01');
					exit();
				
			};
		//capturando dominio inicial
		$sqldominio="SELECT DES_DOMINIO,COD_DOMINIO from site_extrato WHERE cod_empresa='".$array[COD_EMPRESA]."'";
		$rsdominio=mysqli_fetch_assoc(mysqli_query($array[CONNTMP],$sqldominio));
		$DES_DOMINIO=$rsdominio['DES_DOMINIO'];
		$COD_DOMINIO=$rsdominio['COD_DOMINIO'];
		//completar  as variaveis					
			$tagsPersonaliza=procpalavras($rscampanhaSMS[DES_TEMPLATE],$array[CONNADM]);

			$tags = explode(',',$tagsPersonaliza);

			$selectCliente = "";	

			for ($i=0; $i < count($tags) ; $i++) {
				switch($tags[$i]){

					case '<#NOME>';
						$selectCliente .= "C.NOM_CLIENTE,";
					break;
					case '<#CARTAO>';
						$selectCliente .= "";
					break;
					case '<#ESTADOCIVIL>';
						$selectCliente .= "";
					break;
					case '<#SEXO>';
						$selectCliente .= "";
					break;
					case '<#PROFISSAO>';
						$selectCliente .= "";
					break;
					case '<#NASCIMENTO>';
						$selectCliente .= "";
					break;
					case '<#ENDERECO>';
						$selectCliente .= "";
					break;
					case '<#NUMERO>';
						$selectCliente .= "";
					break;
					case '<#BAIRRO>';
						$selectCliente .= "";
					break;
					case '<#CIDADE>';
						$selectCliente .= "";
					break;
					case '<#ESTADO>';
						$selectCliente .= "";
					break;
					case '<#CEP>';
						$selectCliente .= "";
					break;
					case '<#COMPLEMENTO>';
						$selectCliente .= "";
					break;
					case '<#TELEFONE>';
						$selectCliente .= "";
					break;
					case '<#CELULAR>';
						$selectCliente .= "";
					break;
					case '<#SALDO>';
						$selectCliente .= "FORMAT(TRUNCATE((SELECT 
											IFNULL((SELECT Sum(val_saldo) FROM creditosdebitos f 
											WHERE f.cod_cliente = cred.cod_cliente AND 
													f.tip_credito = 'C' AND 
													f.cod_statuscred = 1 AND 
													f.tip_campanha = cred.tip_campanha AND 
													(( f.log_expira = 'S' AND DATE_FORMAT(f.dat_expira, '%Y-%m-%d') >= Date_format(Now(), '%Y-%m-%d') ) OR ( f.log_expira = 'N' ) )),0)+
											IFNULL((SELECT Sum(val_saldo) FROM creditosdebitos_bkp g
											WHERE g.cod_cliente = cred.cod_cliente AND 
													g.tip_credito = 'C' AND 
													g.cod_statuscred = 1 AND 
													g.tip_campanha = cred.tip_campanha AND 
													((g.log_expira = 'S' AND DATE_FORMAT(g.dat_expira, '%Y-%m-%d') >= Date_format(Now(), '%Y-%m-%d') ) OR (g.log_expira = 'N' ) )),0) AS CREDITO_DISPONIVEL
													FROM creditosdebitos cred 
													WHERE cred.cod_cliente=C.cod_CLIENTE
													GROUP BY cred.cod_cliente ),$array[CASAS_DEC]),$array[CASAS_DEC],'pt_BR') AS CREDITO_DISPONIVEL,";
					break;
					case '<#PRIMEIRACOMPRA>';
						$selectCliente .= "";
					break;
					case '<#ULTIMACOMPRA>';
						$selectCliente .= "";
					break;
					case '<#TOTALCOMPRAS>';
						$selectCliente .= "";
					break;
					case '<#CODIGO>';
						$selectCliente .= "C.COD_CLIENTE,";
					break;
					case '<#CUPOMSORTEIO>';
						$selectCliente .= "";
					break;
					case '<#CUPOM_INDICACAO>';
						$selectCliente .= "";
					break;
					case '<#NUMEROLOJA>';
						$selectCliente .= "";
					break;
					case '<#BAIRROLOJA>';
						$selectCliente .= "";
					break;
					case '<#NOMELOJA>';
						$selectCliente .= "(SELECT NOM_FANTASI 
											  FROM unidadevenda UNI 
												WHERE UNI.COD_UNIVEND=C.COD_UNIVEND 
												AND UNI.COD_EMPRESA=C.COD_EMPRESA) AS NOM_FANTASI,";
					break;
					case '<#ENDERECOLOJA>';
						$selectCliente .= "";
					break;
					case '<#TELEFONELOJA>';
						$selectCliente .= "";
					break;
					case '<#ANIVERSARIO>';
						$selectCliente .= "C.DAT_NASCIME,";
					break;
					case '<#DATAEXPIRA>';
						$selectCliente .= "(SELECT 
										        MIN(DAT_EXPIRA) AS DAT_EXPIRA
									        	FROM creditosdebitos 
											    WHERE DAT_EXPIRA >= NOW() AND  cod_CLIENTE=C.cod_CLIENTE) AS DAT_EXPIRA,";
					break;
                                        case '<#DATAEXPIRAMAX>';
						$selectCliente .= "(SELECT 
										        MAX(DAT_EXPIRA) AS DAT_EXPIRAMAX
									        	FROM creditosdebitos 
											    WHERE DAT_EXPIRA >= NOW() AND  cod_CLIENTE=C.cod_CLIENTE) AS DAT_EXPIRAMAX,";
					break;
                                    
					default:
						$selectCliente .= "C.NUM_CELULAR,";
					break;
					
				}
			}

			$selectCliente = rtrim($selectCliente,',');

			$sqlEnvio = "SELECT $selectCliente FROM CLIENTES C
							WHERE 
							C.COD_CLIENTE=$array[COD_CLIENTE]";
							
			$rsEnvio=mysqli_fetch_assoc(mysqli_query($array[CONNTMP],$sqlEnvio));				
		
		$NOM_CLIENTE=explode(" ", ucfirst(strtolower(fnAcentos($rsEnvio['NOM_CLIENTE']))));
		
		//==========================================			
		//alterar o variavel peolo texto
		$TEXTOENVIO=str_replace('<#TOKEN>', $senha, $rscampanhaSMS[DES_TEMPLATE]);
		if($COD_DOMINIO=='1')
		{	 
		$TEXTOENVIO=str_replace('<#LINKTOKEN>', 'https://'.$DES_DOMINIO.'.mais.cash/ativacao.do', $TEXTOENVIO);
		$TEXTOENVIO=str_replace('<#LINKATIVACAO>', 'https://'.$DES_DOMINIO.'.mais.cash/ativacao.do', $TEXTOENVIO);
		}
		if($COD_DOMINIO=='2')
		{	 
		$TEXTOENVIO=str_replace('<#LINKTOKEN>', 'https://'.$DES_DOMINIO.'.fidelidade.mk/ativacao.do', $TEXTOENVIO);
		$TEXTOENVIO=str_replace('<#LINKATIVACAO>', 'https://'.$DES_DOMINIO.'.fidelidade.mk/ativacao.do', $TEXTOENVIO);
		}
       	$TEXTOENVIO=str_replace('<#NOMELOJA>', fnAcentos($rsEnvio[NOM_FANTASI]), $TEXTOENVIO);                    
		$TEXTOENVIO=str_replace('<#NOME>', $NOM_CLIENTE[0], $TEXTOENVIO);
		$TEXTOENVIO=str_replace('<#DATAEXPIRA>',fnDataShort($rsEnvio[DAT_EXPIRA]), $TEXTOENVIO);
		$TEXTOENVIO=str_replace('<#SALDO>', $rsEnvio[CREDITO_DISPONIVEL], $TEXTOENVIO);
		$TEXTOENVIO=str_replace('<#ANIVERSARIO>', fnDataShort($rsEnvio[DAT_NASCIME]), $TEXTOENVIO);
		$TEXTOENVIO=str_replace('<#CREDITOVENDA>', $array[CRED_VENDA], $TEXTOENVIO);
		$TEXTOENVIO=str_replace('<#DATAEXPIRAMAX>', fnDataShort($rsEnvio[DAT_EXPIRAMAX]), $TEXTOENVIO);
                
	//senha para autenticar o envio
		$sqlsenhasms = "SELECT * FROM senhas_parceiro apar
		INNER JOIN parceiro_comunicacao par ON par.COD_PARCOMU=apar.COD_PARCOMU
		WHERE par.COD_TPCOM='2' AND apar.COD_PARCOMU='16' AND apar.LOG_ATIVO='S'
		AND apar.COD_EMPRESA = '".$array[COD_EMPRESA]."'";
		$rssenhasms=mysqli_fetch_assoc(mysqli_query($array[CONNADM],$sqlsenhasms));
	//verificar o saldo antes do envio
		$sqlcomdebt="SELECT                
						pedido.TIP_LANCAMENTO 
						,pedido.COD_VENDA
						,pedido.COD_PRODUTO 
						,emp.NOM_EMPRESA
						,pedido.DAT_CADASTR 
						, pedido.COD_ORCAMENTO
						, canal.DES_CANALCOM
						, canal.COD_CANALCOM
						,SUM(round(pedido.QTD_PRODUTO,0)) AS QTD_PRODUTO
						, pedido.VAL_UNITARIO
						, pedido.VAL_UNITARIO * pedido.QTD_PRODUTO AS VAL_TOTAL 
						, if(pedido.PAG_CONFIRMACAO='S', 'Pagamento Confirmado', 'Aguardando Confirmação de Pagamento') AS DES_SITUACAO
						FROM pedido_marka pedido 
						INNER JOIN produto_marka prod ON prod.COD_PRODUTO = pedido.COD_PRODUTO 
						INNER JOIN canal_comunicacao canal ON canal.COD_CANALCOM = prod.COD_CANALCOM 
						INNER JOIN empresas emp ON emp.COD_EMPRESA = pedido.COD_EMPRESA
						WHERE pedido.COD_ORCAMENTO > 0 AND 
						pedido.COD_EMPRESA =".$array[COD_EMPRESA]." AND
						PAG_CONFIRMACAO='S' and
						canal.COD_TPCOM=2
						GROUP BY  pedido.TIP_LANCAMENTO	            
						ORDER BY pedido.TIP_LANCAMENTO desc ";
		$rwarraysql=mysqli_query($array[CONNADM], $sqlcomdebt);
		while($rssaldo= mysqli_fetch_assoc($rwarraysql)) 
		{
			if($rssaldo['TIP_LANCAMENTO']=='D'){$DebSaldo=  $rssaldo['QTD_PRODUTO'];}
			if($rssaldo['TIP_LANCAMENTO']=='C') {$CredSaldo= $rssaldo['QTD_PRODUTO'];}
		} 
		
        $saldorestante=bcsub($CredSaldo, $DebSaldo);
        $saldoDiferenca=abs(bcsub('1', $CredSaldo));	
		
        if($saldorestante <= '1')
        {   
			return array('msgerro'=>'Saldo insuficiente',
					     'coderro'=>'02');
						
		}	
	//==========================================envio			
	$id_campanha1=date('YmdHis');
   
    $enviosmsmsg=EnvioSms($rssenhasms["DES_USUARIO"],
	    				  $rssenhasms["DES_AUTHKEY"],
						  $rssenhasms['DES_CLIEXT'],
						  $TEXTOENVIO,
						  $array[TELEFONE],
						  $id_campanha1);
	$enviosmsmsg=json_decode($enviosmsmsg,true);
			
	if($enviosmsmsg[infomacoes][0]=='SMS enviado')
	{	
		//==========envio de debitos				
		$arraydebitos=array('quantidadeEmailenvio'=>'1',
		                    'COD_EMPRESA'=>$array[COD_EMPRESA],
		                    'PERMITENEGATIVO'=>'N',
		                    'COD_CANALCOM'=>'2',
		                    'CONFIRMACAO'=>'S',
		                    'COD_CAMPANHA'=>$COD_CAMPANHA,    
		                    'LOG_TESTE'=> 'N',
		                    'DAT_CADASTR'=> date('Y-m-d H:i:s'),
		                    'CONNADM'=>$array[CONNADM]
		                    ); 
		$retornoDeb=FnDebitos($arraydebitos);
		if($retornoDeb[cod_msg] =='2' ||  $retornoDeb[cod_msg]=='5' )
		{
			return array('msgerro'=>$retornoDeb[MSG_ATERACAO],
					     'coderro'=>'03');
							exit();	
		}
		
	}else{
			return array('msgerro'=>'Erro ao enviar',
			             'erroNEXUX'=>$enviosmsmsg[infomacoes][0],
					     'coderro'=>'04');
	}		
					 
	return array('msgerro'=>$retornoDeb,
					     'coderro'=>'05');
							

};

/*
[msgerro] => Array
        (
            [status] => 
            [infomacoes] => Array
                (
                    [0] 
$array=array('CONNADM'=>$connAdm->connAdm(),
			 'CONNTMP'=>connTemp(194,''),
			 'COD_EMPRESA'=>'194',
			 'LOJA'=>'TESTE',
			 'NOMECLIENTE'=>'RONE',
			 'COD_CLIENTE',
			 'TELEFONE'=>'15988034772',
			 'CASAS_DEC'
);

$teste1=envio_teste_sms($array);

echo '<pre>';
print_r($teste1);	
echo '</pre>';*/


?>