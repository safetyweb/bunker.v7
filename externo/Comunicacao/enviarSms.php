<?php

include '../../_system/_functionsMain.php';

//fnDebug('true');

$sql1 = "select c. from comunicacao_empresas c
            INNER JOIN empresas emp ON emp.COD_EMPRESA=c.COD_EMPRESA AND emp.LOG_ATIVO='S'
             WHERE c.tipo = 'SMS'
            group BY c.cod_empresa";
$arrayQuery = mysqli_query($connAdm->connAdm(), $sql1);
//fnEscreve($sql);
while ($qrLista = mysqli_fetch_assoc($arrayQuery)) {
    if (!empty($qrLista['COD_EMPRESA']) && !empty($qrLista['COD_COMUNIC'])) {
        $cod_empresa = $qrLista['COD_EMPRESA'];

        $sql2 = "SELECT comunicacao_modelo.COD_COMUNIC,
					 comunicacao_modelo.COD_MODMAIL,
					 comunicacao_modelo.DES_TEXTO_SMS,
					 clientes.COD_CLIENTE,
					 clientes.NOM_CLIENTE,
					 clientes.NUM_CELULAR,
					 gera_comunicacao.COD_TIPCOMU
			FROM gera_comunicacao
			INNER JOIN comunicacao_modelo ON gera_comunicacao.COD_COMUNIC = comunicacao_modelo.COD_COMUNIC
			INNER JOIN clientes ON gera_comunicacao.COD_CLIENTE = clientes.COD_CLIENTE
			WHERE gera_comunicacao.COD_TIPCOMU = 2 and log_enviado = 'N'";

       // fnEscreve($sql);
        $arrayQueryGera = mysqli_query(connTemp($qrLista['COD_EMPRESA'], ''), $sql2) or die(mysqli_error());
      
        while ($qrListaGera = mysqli_fetch_assoc($arrayQueryGera)) {

            $cod_comunic = $qrListaGera['COD_COMUNIC'];
            $cod_cliente = $qrListaGera['COD_CLIENTE'];
            $des_texto_sms = $qrListaGera['DES_TEXTO_SMS'];
            include "../../montaVariaveisComunicacao.php";

            foreach ($qrListaVariaveis as $key => $value) {
                $des_texto_sms = str_replace($key, $value, $des_texto_sms);
            }

            $cliente = $qrListaGera['NOM_CLIENTE'];
            $msg = $des_texto_sms;
            $telefone = fnLimpaDoc($qrListaGera['NUM_CELULAR']);
            $urlmessage = urlencode($msg);
            
            $sqlLogin = "SELECT * FROM CONFIGURACAO_ACESSO WHERE COD_EMPRESA = $cod_empresa AND DES_ULTNOME = 'SMS'";
            $arrayQuery = mysqli_query($connAdm->connAdm(), $sqlLogin) or die(mysqli_error());
            $qrDadosLogin = mysqli_fetch_assoc($arrayQuery);
            $emailLogin = $qrDadosLogin['DES_EMAILUS'];
            $senhaLogin = $qrDadosLogin['DES_SENHAUS'];
            $parceiro = $qrDadosLogin['COD_PARCOMU'];
            if($parceiro == 6){
                $result = "http://painel.maciv.com/SendAPI/Send.aspx?usr=$emailLogin&pwd=$senhaLogin&number=55$telefone&sender=&msg=$urlmessage";
              // fnEscreve($result);

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $result);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
                curl_setopt($ch, CURLOPT_MAXREDIRS, 3);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
                $data = curl_exec($ch);
                $data_hora = date("Y-m-d H:i:s");
                //$update="update envia_sms set COD_SMS=$data,DATA_HORA='".$data_hora."' where id_venda=".$rsenvio['id_venda'];    
                //mysqli_query($connSMSEgrava->connAdm(),$update);
                $err = curl_error($ch);

                curl_close($ch);

                if ($err) {
                  echo "cURL Error #:" . $err;
                } else {
                        //precisa gravar os logs de sms para controle
                        /*
                            -1 Erro de Envios – Instabilidade do sistema.
                            -2 Sem Crédito
                            -5 Login ou Senha inválidos
                            -7 Mensagem inválida.
                            -8 Remetente inválido.
                            -9 Número do GSM no formato inválido
                            -13 Número do GSM inválido.
                            -20 Serviço fora do ar.
                            -30 Data de Agendamento inválida
                            >0 Sucesso, retorna o Id da mensagem SMS. 
                            1 Não Processado
                            2 Não Enviado
                            3 Sendo Processado
                            4 Enviado
                         */
                        echo $data;
                    
                }

            }

            $sql .= "update gera_comunicacao set log_enviado = 'S' where cod_cliente = " . $qrListaGera['COD_CLIENTE'] . "; ";
        }

        if (!empty($sql)) {
            mysqli_multi_query(connTemp($qrLista['COD_EMPRESA'], ''), $sql) or die(mysqli_error());
        }
    }
}
?>