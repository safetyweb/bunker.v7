<?php
require('../../sendinblue/Email.php');
include '../../_system/_functionsMain.php'; 

fnDebug('true');

$sql = "select c.* from comunicacao_empresas c
INNER JOIN empresas emp ON emp.COD_EMPRESA=c.COD_EMPRESA AND emp.LOG_ATIVO='S'
group BY c.cod_empresa";
$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());

while ($qrLista = mysqli_fetch_assoc($arrayQuery)){
	if(!empty($qrLista['COD_EMPRESA']) && !empty($qrLista['COD_COMUNIC'])){
		$cod_empresa = $qrLista['COD_EMPRESA'];
		
		echo 'COD_EMPRESA = ' . $qrLista['COD_EMPRESA'] .'<br>';
		echo 'COD_COMUNIC = ' . $qrLista['COD_COMUNIC'] .'<br>';		
		
		//Pega código autenticador da empresa
		$sql = "select DES_AUTHKEY from CONFIGURACAO_ACESSO where COD_EMPRESA = $cod_empresa";
		$arrayQueryAcesso = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());	
		//fnEscreve($sql);
		$row = mysqli_fetch_assoc($arrayQueryAcesso);
                $parceiro = $row['COD_PARCOMU'];
                
		$email = new Email();
		$email->setApiKey($row['DES_AUTHKEY']); 
                

		echo $row['DES_AUTHKEY'] .'<br>';
		
		$sql = "SELECT comunicacao_modelo.COD_COMUNIC,
					   comunicacao_modelo.COD_MODMAIL,
					   gera_comunicacao.DAT_AGENDA,
					   gera_comunicacao.COD_VENDA,
		               clientes.COD_CLIENTE,
					   clientes.NOM_CLIENTE,
					   clientes.DES_EMAILUS
				FROM gera_comunicacao
				INNER JOIN comunicacao_modelo ON gera_comunicacao.COD_COMUNIC = comunicacao_modelo.COD_COMUNIC
				INNER JOIN clientes ON gera_comunicacao.COD_CLIENTE = clientes.COD_CLIENTE
				where gera_comunicacao.COD_TIPCOMU = '1' and log_enviado = 'N' 
				AND DAT_AGENDA BETWEEN DATE_FORMAT(now(), '%Y-%m-%d %H:%i:00') AND 
				DATE_ADD(DATE_FORMAT(now(), '%Y-%m-%d %H:%i:00'), INTERVAL 59 second)";
		//fnEscreve($sql);
		$arrayQueryGera = mysqli_query(connTemp($qrLista['COD_EMPRESA'],''),$sql) or die(mysqli_error());	

		$sqlAtualiza = "";
		while ($qrListaGera = mysqli_fetch_assoc($arrayQueryGera)){
			
			$cod_comunic = $qrListaGera['COD_COMUNIC'];
			$cod_modmail = $qrListaGera['COD_MODMAIL'];
			$cod_cliente = $qrListaGera['COD_CLIENTE'];
			$emailCliente = $qrListaGera['DES_EMAILUS'];
			include "../../montaVariaveisComunicacao.php"; 
                        
                        if($parceiro == 5){
                            $data = array( "id" => $cod_modmail,
                                    "to" => $emailCliente,
                                    //"to" => "maurice@markafidelizacao.com.br",
                                    //"to" => "ricardolara.ti@gmail.com",
                                    "attr" => $qrListaVariaveis
                            );

                            echo '<pre>';
                            print_r($qrListaVariaveis);
                            echo '</<pre>';			

                            //echo '<pre>';
                            //print_r($email->mailin->send_transactional_template($data));
                            //echo '</<pre>';                            
                        }
			
			
			$sqlAtualiza.= "update gera_comunicacao set log_enviado = 'S', dat_enviado = now() where cod_cliente = " . $qrListaGera['COD_CLIENTE'] . " and cod_venda = " . $qrListaGera['COD_VENDA'] . "; ";
		}
		
		if(!empty($sqlAtualiza)){
			mysqli_multi_query(connTemp($qrLista['COD_EMPRESA'],''),$sqlAtualiza) or die(mysqli_error());
		}
	}
}

?>