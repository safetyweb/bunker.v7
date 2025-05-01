<?php
function fnFormatvalor_v2($brl, $casasDecimais = 2) {
    // Se já estiver no formato USD, retorna como float e formatado
    if(preg_match('/^\d+\.{1}\d+$/', $brl))
        return (float) number_format($brl, $casasDecimais, '.', '');
    // Tira tudo que não for número, ponto ou vírgula
    $brl = preg_replace('/[^\d\.\,]+/', '', $brl);
    // Tira o ponto
    $decimal = str_replace('.', '', $brl);
    // Troca a vírgula por ponto
    $decimal = str_replace(',', '.', $decimal);
    return (float) number_format($decimal, $casasDecimais, '.', '');
}
include '../../_system/_functionsMain.php';
include '../totvs/funcao.php';
//fnDebug('TRUE');
$tempoPROC='1';
$tempoPROCfinal='0';
$dateinicia=date('Y-m-d',strtotime(date('Y-m-d'). ' - '.$tempoPROC.' days'));
$dateatal=date('Y-m-d');
$diferenca = strtotime($dateatal) - strtotime($dateinicia) ;
$dias = floor($diferenca / (60 * 60 * 24));
echo 'DIA INICIAL: '.$dateinicia.'<br>';
echo 'QTD DIA: '.$dias.'<br>';

for ($ini = 0; $ini <= $dias; $ini++) {
  $datefinal=date('Y-m-d',strtotime(date($dateinicia). ' + '.$ini.' days'));
  echo 'DIA PROCESSADO: '.$datefinal.'<br>';

    $admconex=$connAdm->connAdm();
    $sqlconfig="SELECT * FROM webhook web WHERE tip_webhook=8 AND web.LOG_ESTATUS='S'";
    $rwconfigorigem= mysqli_query($admconex, $sqlconfig);
    while($rsconfig= mysqli_fetch_assoc($rwconfigorigem))
    { 
        $contmp=connTemp($rsconfig['COD_EMPRESA'],'');
        $SQLtrocas=" truncate table  linxmovimentotrocas;
                     truncate table  linx_movimento;
                    ";
        mysqli_multi_query($contmp, $SQLtrocas); 



                unset($dadoslogin);
                $key_marka= fnDecode(base64_decode($rsconfig[DES_SENHAMARKA]));
                $dadoslogin=explode(';', $key_marka);
                if($dadoslogin[2]=='99999')
                {
                    // and COD_UNIVEND='97552'
                   $sqluni= "SELECT COD_UNIVEND,NUM_CGCECPF FROM unidadevenda WHERE COD_EMPRESA=$dadoslogin[4] AND LOG_ESTATUS='S' ORDER BY COD_UNIVEND DESC";        
                }elseif ($dadoslogin[2]=='') {
                   $sqluni="SELECT COD_UNIVEND,NUM_CGCECPF FROM unidadevenda WHERE COD_UNIVEND= $dadoslogin[2] and  COD_EMPRESA=$dadoslogin[4] AND LOG_ESTATUS='S' ORDER BY COD_UNIVEND DESC";
                }



                $rsuni= mysqli_query($admconex, $sqluni);
                while ($rwuni= mysqli_fetch_assoc($rsuni))
                {
                    ob_start();
                    $NUM_CGCECPF=fnLimpaDoc($rwuni[NUM_CGCECPF]);
                    $dadoslogin[2]=$rwuni[COD_UNIVEND];


                            $contmp= connTemp($rsconfig['COD_EMPRESA'],'');
                            $sqlcontador="SELECT * FROM controle_linx WHERE COD_EMPRESA=$rsconfig[COD_EMPRESA] AND COD_UNIVEND=$rwuni[COD_UNIVEND] AND TIP_CONTROLE IN (1,2,3)";
                            $rwcontador= mysqli_query($contmp, $sqlcontador);
                            if($rwcontador->num_rows <= '0')
                           {
                                 $insercontrole="INSERT INTO controle_linx (COD_CONSULTA, COD_EMPRESA, COD_UNIVEND, TIP_CONTROLE, DES_CONTROLE) VALUES (0, $rsconfig[COD_EMPRESA], $rwuni[COD_UNIVEND], 1, 'VENDA');
                                                 INSERT INTO controle_linx (COD_CONSULTA, COD_EMPRESA, COD_UNIVEND, TIP_CONTROLE, DES_CONTROLE) VALUES (0, $rsconfig[COD_EMPRESA], $rwuni[COD_UNIVEND], 2, 'CADASTRO');
                                                 INSERT INTO controle_linx (COD_CONSULTA, COD_EMPRESA, COD_UNIVEND, TIP_CONTROLE, DES_CONTROLE) VALUES (0, $rsconfig[COD_EMPRESA], $rwuni[COD_UNIVEND], 3, 'TROCAS');";
                                mysqli_multi_query($contmp, $insercontrole);
                           }


                           if($rwcontador->num_rows > '0')
                           {    
                                while($rscontador= mysqli_fetch_assoc($rwcontador))
                                {
                                    //timestamp de venda
                                    if($rscontador[COD_CONSULTA] > '0' && $rscontador[TIP_CONTROLE]=='1' )
                                    {
                                        $timestampven='<Parameter id="timestamp">'.$rscontador[COD_CONSULTA].'</Parameter>'; 
                                         $dateconven='';
                                    }elseif ($rscontador[COD_CONSULTA] <= '0' && $rscontador[TIP_CONTROLE]=='1' ) {

                                          $timestampven='';
                                          $dateconven='<Parameter id="data_inicial">'.$datefinal.'</Parameter>
                                                       <Parameter id="data_fim">'.$datefinal.'</Parameter>';
                                    }

                                    //timestamp de cadastro
                                    if($rscontador[COD_CONSULTA] > '0' && $rscontador[TIP_CONTROLE]=='2' )
                                    {
                                        $timestampcad='<Parameter id="timestamp">'.$rscontador[COD_CONSULTA].'</Parameter>'; 

                                    }elseif ($rscontador[COD_CONSULTA] <= '0' && $rscontador[TIP_CONTROLE]=='2' ) {

                                          $timestampcad='';
                                    }

                                    //timestamp de TROCAS
                                    if($rscontador[COD_CONSULTA] > '0' && $rscontador[TIP_CONTROLE]=='3' )
                                    {
                                        $timestamptroca='<Parameter id="timestamp">'.$rscontador[COD_CONSULTA].'</Parameter>'; 
                                        $datecontroca='';

                                    }elseif ($rscontador[COD_CONSULTA] <= '0' && $rscontador[TIP_CONTROLE]=='3' ) {

                                        $timestamptroca='';
                                        $datecontroca='<Parameter id="data_inicial">'.$datefinal.'</Parameter>
                                                       <Parameter id="data_fim">'.$datefinal.'</Parameter>';
                                    } 
                                }
                           } else {
                               $timestampven='<Parameter id="timestamp">0</Parameter>'; 
                               $timestampcad='<Parameter id="timestamp">0</Parameter>'; 
                               $timestamptroca='<Parameter id="timestamp">0</Parameter>'; 
                           }    
                     //==================================consultar linxmovimentotrocas=================

                         $curl = curl_init();
                            curl_setopt_array($curl, array(
                              CURLOPT_URL => 'https://webapi.microvix.com.br/1.0/api/integracao',
                              CURLOPT_RETURNTRANSFER => true,
                              CURLOPT_ENCODING => '',
                              CURLOPT_MAXREDIRS => 100000000,
                              CURLOPT_TIMEOUT => 3000,
                              CURLOPT_FOLLOWLOCATION => true,
                              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                              CURLOPT_CUSTOMREQUEST => 'POST',
                              CURLOPT_POSTFIELDS =>'<?xml version=\'1.0\' encoding=\'utf-8\' ?>
                                                        <LinxMicrovix>
                                                            <Authentication user="linx_export" password="linx_export" />
                                                            <ResponseFormat>xml</ResponseFormat>
                                                            <Command>
                                                                <Name>LinxMovimentoTrocas</Name>
                                                                <Parameters>
                                                                    <Parameter id="chave">'.$rsconfig[DES_SENHA].'</Parameter>
                                                                    <Parameter id="cnpjEmp">'.$NUM_CGCECPF.'</Parameter>
                                                                    '.$timestamptroca.'
                                                                   '.$datecontroca.'
                                                                </Parameters>
                                                            </Command>
                                                        </LinxMicrovix>',
                              CURLOPT_HTTPHEADER => array(
                                'Content-Type: application/xml'
                              ),
                            ));

                            $response = curl_exec($curl);

                            curl_close($curl);
                            $xml = simplexml_load_string($response, "SimpleXMLElement", LIBXML_NOCDATA);
                            $json = json_encode($xml);
                            $arraytroca = json_decode($json,TRUE);
                            foreach ($arraytroca['ResponseData']['R'] as $key => $valuetrocanew){		   	
                                if(!empty($valuetrocanew[D][2]))
                                {
                                              $insertintotroca="insert into linxmovimentotrocas (   portal,
                                                                                                    cnpj_emp,
                                                                                                    identificador,
                                                                                                    num_vale,
                                                                                                    valor_vale,
                                                                                                    motivo,
                                                                                                    doc_origem,
                                                                                                    serie_origem,
                                                                                                    doc_venda,
                                                                                                    serie_venda,
                                                                                                    excluido,
                                                                                                    timestamp,
                                                                                                    desfazimento
                                                                                                  ) values(
                                                                                                  '".$valuetrocanew[D][0]."',
                                                                                                  '".$valuetrocanew[D][1]."',
                                                                                                  '".$valuetrocanew[D][2]."',
                                                                                                  '".$valuetrocanew[D][3]."',
                                                                                                  '".$valuetrocanew[D][4]."',
                                                                                                  '".$valuetrocanew[D][5]."',
                                                                                                  '".$valuetrocanew[D][6]."',
                                                                                                  '".$valuetrocanew[D][7]."',
                                                                                                  '".$valuetrocanew[D][8]."',
                                                                                                  '".$valuetrocanew[D][9]."',
                                                                                                  '".$valuetrocanew[D][10]."',
                                                                                                  '".$valuetrocanew[D][11]."',
                                                                                                  '".$valuetrocanew[D][12]."'
                                                                                                  );";
                                                    $rwconfig0= mysqli_query($contmp, $insertintotroca);
                                                    if(!$rwconfig0){
                                                         //   echo 'erro0: '.$insertintotroca;
                                                    }																
                                }
                            }


                    //=======verificando as venda que estão no LinxMovimento como trocas
                          $sqlTroca= "SELECT identificador,timestamp,cnpj_emp from linxmovimentotrocas  where  cnpj_emp='$NUM_CGCECPF' ORDER BY TIMESTAMP asc"; 
                          $rwtroca= mysqli_query($contmp, $sqlTroca);
                          if($rwtroca->num_rows > 0)
                          {
                               while ($rstroca= mysqli_fetch_assoc($rwtroca)) 
                               {  
                                   /*
                                       <Parameter id="data_inicial">'.date('Y-m-d',strtotime(date('Y-m-d'). ' - 31 days')).'</Parameter>
                                       <Parameter id="data_fim">'.date('Y-m-d',strtotime(date('Y-m-d'). ' - 0 days')).'</Parameter>  
                                    */

                                    //inicio da inseção da venda
                                      $curl = curl_init();
                                      curl_setopt_array($curl, array(
                                        CURLOPT_URL => 'https://webapi.microvix.com.br/1.0/api/integracao',
                                        CURLOPT_RETURNTRANSFER => true,
                                        CURLOPT_ENCODING => '',
                                        CURLOPT_MAXREDIRS => 100000000,
                                        CURLOPT_TIMEOUT => 3000,
                                        CURLOPT_FOLLOWLOCATION => true,
                                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                                        CURLOPT_CUSTOMREQUEST => 'POST',
                                        CURLOPT_POSTFIELDS =>'<?xml version=\'1.0\' encoding=\'utf-8\' ?>
                                                                  <LinxMicrovix>
                                                                      <Authentication user="linx_export" password="linx_export" />
                                                                      <ResponseFormat>xml</ResponseFormat>
                                                                      <Command>
                                                                          <Name>LinxMovimento</Name>
                                                                          <Parameters>
                                                                              <Parameter id="chave">'.$rsconfig[DES_SENHA].'</Parameter>
                                                                              <Parameter id="cnpjEmp">'.$rstroca['cnpj_emp'].'</Parameter>
                                                                              <Parameter id="identificador">'.$rstroca[identificador].'</Parameter>
                                                                              <Parameter id="data_inicial">'.date('Y-m-d',strtotime(date('Y-m-d'). ' - 31 days')).'</Parameter>
                                                                              <Parameter id="data_fim">'.date('Y-m-d',strtotime(date('Y-m-d'). ' - 0 days')).'</Parameter>      
                                                                          </Parameters>
                                                                      </Command>
                                                                  </LinxMicrovix>',
                                        CURLOPT_HTTPHEADER => array(
                                          'Content-Type: application/xml'
                                        ),
                                      ));
                                      $response = curl_exec($curl);
                                      curl_close($curl);

                                      $xml = simplexml_load_string($response, "SimpleXMLElement", LIBXML_NOCDATA);
                                      $json = json_encode($xml);
                                      $array = json_decode($json,TRUE);

                                       foreach ($array['ResponseData']['R'] as $dadosmovimento) {

                                                                  $insertintomov0="insert into linx_movimento (
                                                                                                                portal,
                                                                                                                cnpj_emp,
                                                                                                                transacao,
                                                                                                                usuario,
                                                                                                                documento,
                                                                                                                chave_nf,
                                                                                                                ecf,
                                                                                                                numero_serie_ecf,
                                                                                                                modelo_nf,
                                                                                                                data_documento,
                                                                                                                data_lancamento,
                                                                                                                codigo_cliente,
                                                                                                                serie,
                                                                                                                desc_cfop,
                                                                                                                id_cfop,
                                                                                                                cod_vendedor,
                                                                                                                quantidade,
                                                                                                                preco_custo,
                                                                                                                valor_liquido,
                                                                                                                desconto,
                                                                                                                cst_icms,
                                                                                                                cst_pis,
                                                                                                                cst_cofins,
                                                                                                                cst_ipi,
                                                                                                                valor_icms,
                                                                                                                aliquota_icms,
                                                                                                                base_icms,
                                                                                                                valor_pis,
                                                                                                                aliquota_pis,
                                                                                                                base_pis,
                                                                                                                valor_cofins,
                                                                                                                aliquota_cofins,
                                                                                                                base_cofins,
                                                                                                                valor_icms_st,
                                                                                                                aliquota_icms_st,
                                                                                                                base_icms_st,
                                                                                                                valor_ipi,
                                                                                                                aliquota_ipi,
                                                                                                                base_ipi,
                                                                                                                valor_total,
                                                                                                                forma_dinheiro,
                                                                                                                total_dinheiro,
                                                                                                                forma_cheque,
                                                                                                                total_cheque,
                                                                                                                forma_cartao,
                                                                                                                total_cartao,
                                                                                                                forma_crediario,
                                                                                                                total_crediario,
                                                                                                                forma_convenio,
                                                                                                                total_convenio,
                                                                                                                frete,
                                                                                                                operacao,
                                                                                                                tipo_transacao,
                                                                                                                cod_produto,
                                                                                                                cod_barra,
                                                                                                                cancelado,
                                                                                                                excluido,
                                                                                                                soma_relatorio,
                                                                                                                identificador,
                                                                                                                deposito,
                                                                                                                obs,
                                                                                                                preco_unitario,
                                                                                                                hora_lancamento,
                                                                                                                natureza_operacao,
                                                                                                                tabela_preco,
                                                                                                                nome_tabela_preco,
                                                                                                                cod_sefaz_situacao,
                                                                                                                desc_sefaz_situacao,
                                                                                                                protocolo_aut_nfe,
                                                                                                                dt_update,
                                                                                                                forma_cheque_prazo,
                                                                                                                total_cheque_prazo,
                                                                                                                cod_natureza_operacao,
                                                                                                                preco_tabela_epoca,
                                                                                                                desconto_total_item,
                                                                                                                conferido,
                                                                                                                transacao_pedido_venda,
                                                                                                                codigo_modelo_nf,
                                                                                                                acrescimo,
                                                                                                                mob_checkout,
                                                                                                                aliquota_iss,
                                                                                                                base_iss,
                                                                                                                ordem,
                                                                                                                codigo_rotina_origem,
                                                                                                                timestamp,
                                                                                                                troco,
                                                                                                                CPF,
                                                                                                                controlemarka
                                                                                                                ) 
                                                                                                                values
                                                                                                                (
                                                                                                                '".$dadosmovimento[0]."',
                                                                                                                '".$dadosmovimento[1]."',
                                                                                                                '".$dadosmovimento[2]."',
                                                                                                                '".$dadosmovimento[3]."',
                                                                                                                '".$dadosmovimento[4]."',
                                                                                                                '".$dadosmovimento[5]."',
                                                                                                                '".$dadosmovimento[6]."',
                                                                                                                '".$dadosmovimento[7]."',
                                                                                                                '".$dadosmovimento[8]."',
                                                                                                                '".$dadosmovimento[9]."',
                                                                                                                '".$dadosmovimento[10]."',
                                                                                                                '".$dadosmovimento[11]."',
                                                                                                                '".$dadosmovimento[12]."',
                                                                                                                '".$dadosmovimento[13]."',
                                                                                                                '".$dadosmovimento[14]."',
                                                                                                                '".$dadosmovimento[15]."',
                                                                                                                '".$dadosmovimento[16]."',
                                                                                                                '".$dadosmovimento[17]."',
                                                                                                                '".$dadosmovimento[18]."',
                                                                                                                '".$dadosmovimento[19]."',
                                                                                                                '".$dadosmovimento[20]."',
                                                                                                                '".$dadosmovimento[21]."',
                                                                                                                '".$dadosmovimento[22]."',
                                                                                                                '".$dadosmovimento[23]."',
                                                                                                                '".$dadosmovimento[24]."',
                                                                                                                '".$dadosmovimento[25]."',
                                                                                                                '".$dadosmovimento[26]."',
                                                                                                                '".$dadosmovimento[27]."',
                                                                                                                '".$dadosmovimento[28]."',
                                                                                                                '".$dadosmovimento[29]."',
                                                                                                                '".$dadosmovimento[30]."',
                                                                                                                '".$dadosmovimento[31]."',
                                                                                                                '".$dadosmovimento[32]."',
                                                                                                                '".$dadosmovimento[33]."',
                                                                                                                '".$dadosmovimento[34]."',
                                                                                                                '".$dadosmovimento[35]."',
                                                                                                                '".$dadosmovimento[36]."',
                                                                                                                '".$dadosmovimento[37]."',
                                                                                                                '".$dadosmovimento[38]."',
                                                                                                                '".$dadosmovimento[39]."',
                                                                                                                '".$dadosmovimento[40]."',
                                                                                                                '".$dadosmovimento[41]."',
                                                                                                                '".$dadosmovimento[42]."',
                                                                                                                '".$dadosmovimento[43]."',
                                                                                                                '".$dadosmovimento[44]."',
                                                                                                                '".$dadosmovimento[45]."',
                                                                                                                '".$dadosmovimento[46]."',
                                                                                                                '".$dadosmovimento[47]."',
                                                                                                                '".$dadosmovimento[48]."',
                                                                                                                '".$dadosmovimento[49]."',
                                                                                                                '".$dadosmovimento[50]."',
                                                                                                                '".$dadosmovimento[51]."',
                                                                                                                '".$dadosmovimento[52]."',
                                                                                                                '".$dadosmovimento[53]."',
                                                                                                                '".$dadosmovimento[54]."',
                                                                                                                '".$dadosmovimento[55]."',
                                                                                                                '".$dadosmovimento[56]."',
                                                                                                                '".$dadosmovimento[57]."',
                                                                                                                '".$dadosmovimento[58]."',
                                                                                                                '".$dadosmovimento[59]."',
                                                                                                                '".$dadosmovimento[60]."',
                                                                                                                '".$dadosmovimento[61]."',
                                                                                                                '".$dadosmovimento[62]."',
                                                                                                                '".$dadosmovimento[63]."',
                                                                                                                '".$dadosmovimento[64]."',
                                                                                                                '".$dadosmovimento[65]."',
                                                                                                                '".$dadosmovimento[66]."',
                                                                                                                '".$dadosmovimento[67]."',
                                                                                                                '".$dadosmovimento[68]."',
                                                                                                                '".$dadosmovimento[69]."',
                                                                                                                '".$dadosmovimento[70]."',
                                                                                                                '".$dadosmovimento[71]."',
                                                                                                                '".$dadosmovimento[72]."',
                                                                                                                '".$dadosmovimento[73]."',
                                                                                                                '".$dadosmovimento[74]."',
                                                                                                                '".$dadosmovimento[75]."',
                                                                                                                '".$dadosmovimento[76]."',
                                                                                                                '".$dadosmovimento[77]."',
                                                                                                                '".$dadosmovimento[78]."',
                                                                                                                '".$dadosmovimento[79]."',
                                                                                                                '".$dadosmovimento[80]."',
                                                                                                                '".$dadosmovimento[81]."',
                                                                                                                '".$dadosmovimento[82]."',
                                                                                                                '".$dadosmovimento[83]."',
                                                                                                                '".$dadosmovimento[84]."',
                                                                                                                '".$dadosmovimento[85]."',
                                                                                                                '0',
                                                                                                                '1'
                                                                                                                );";
                                                                            $rwinsertintomov0=mysqli_query($contmp, $insertintomov0);
                                                                        if(!$rwinsertintomov0)
                                                                        {
                                                                        //    echo 'erro2: '.$insertintomov0;
                                                                        } 								  
                                        }
                                        $timestamp=$rstroca['timestamp'];
                                } 
                               //laterar timestamp do metodo de troca
                                    $sqlcontrolestorno="UPDATE controle_linx SET COD_CONSULTA='".$timestamp."' 
                                                    WHERE COD_EMPRESA=".$rsconfig['COD_EMPRESA']." 
                                                          AND COD_UNIVEND=".$rwuni[COD_UNIVEND]." 
                                                          AND TIP_CONTROLE='3'";
                                   $controlestorno=mysqli_query($contmp, $sqlcontrolestorno);
                                    if(!$controlestorno)
                                    {
                                      //  echo 'erro2: '.$sqlcontrolestorno;
                                    }
                                    UNSET($sqlcontrolestorno);
                       //====================================FIM CONSULTA DOS ITENS APRA A TROCA===================================
                          }   

                                //inicio da inserção da venda
                                 $curl = curl_init();
                                 curl_setopt_array($curl, array(
                                   CURLOPT_URL => 'https://webapi.microvix.com.br/1.0/api/integracao',
                                   CURLOPT_RETURNTRANSFER => true,
                                   CURLOPT_ENCODING => '',
                                   CURLOPT_MAXREDIRS => 100000000,
                                   CURLOPT_TIMEOUT => 3000,
                                   CURLOPT_FOLLOWLOCATION => true,
                                   CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                                   CURLOPT_CUSTOMREQUEST => 'POST',
                                   CURLOPT_POSTFIELDS =>'<?xml version=\'1.0\' encoding=\'utf-8\' ?>
                                                             <LinxMicrovix>
                                                                 <Authentication user="linx_export" password="linx_export" />
                                                                 <ResponseFormat>xml</ResponseFormat>
                                                                 <Command>
                                                                     <Name>LinxMovimento</Name>
                                                                     <Parameters>
                                                                         <Parameter id="chave">'.$rsconfig[DES_SENHA].'</Parameter>
                                                                         <Parameter id="cnpjEmp">'.$NUM_CGCECPF.'</Parameter>
                                                                         '.$timestampven.'
                                                                         '.$dateconven.'
                                                                     </Parameters>
                                                                 </Command>
                                                             </LinxMicrovix>',
                                   CURLOPT_HTTPHEADER => array(
                                     'Content-Type: application/xml'
                                   ),
                                 ));

                                 $response = curl_exec($curl);

                                 curl_close($curl);
                                 $xml = simplexml_load_string($response, "SimpleXMLElement", LIBXML_NOCDATA);
                                 $json = json_encode($xml);
                                 $array = json_decode($json,TRUE);

                                    foreach ($array['ResponseData']['R'] as $dadosmov2) {

                                        $verifcasetem="SELECT * FROM linx_movimento WHERE identificador='".$dadosmov2['D'][58]."' and controlemarka='1';";
                                        $rwvirifica=mysqli_query($contmp, $verifcasetem);
                                        if($rwvirifica->num_rows <= '0'){

                                            $insertintomovfull="insert into linx_movimento (
                                                                                            portal,
                                                                                            cnpj_emp,
                                                                                            transacao,
                                                                                            usuario,
                                                                                            documento,
                                                                                            chave_nf,
                                                                                            ecf,
                                                                                            numero_serie_ecf,
                                                                                            modelo_nf,
                                                                                            data_documento,
                                                                                            data_lancamento,
                                                                                            codigo_cliente,
                                                                                            serie,
                                                                                            desc_cfop,
                                                                                            id_cfop,
                                                                                            cod_vendedor,
                                                                                            quantidade,
                                                                                            preco_custo,
                                                                                            valor_liquido,
                                                                                            desconto,
                                                                                            cst_icms,
                                                                                            cst_pis,
                                                                                            cst_cofins,
                                                                                            cst_ipi,
                                                                                            valor_icms,
                                                                                            aliquota_icms,
                                                                                            base_icms,
                                                                                            valor_pis,
                                                                                            aliquota_pis,
                                                                                            base_pis,
                                                                                            valor_cofins,
                                                                                            aliquota_cofins,
                                                                                            base_cofins,
                                                                                            valor_icms_st,
                                                                                            aliquota_icms_st,
                                                                                            base_icms_st,
                                                                                            valor_ipi,
                                                                                            aliquota_ipi,
                                                                                            base_ipi,
                                                                                            valor_total,
                                                                                            forma_dinheiro,
                                                                                            total_dinheiro,
                                                                                            forma_cheque,
                                                                                            total_cheque,
                                                                                            forma_cartao,
                                                                                            total_cartao,
                                                                                            forma_crediario,
                                                                                            total_crediario,
                                                                                            forma_convenio,
                                                                                            total_convenio,
                                                                                            frete,
                                                                                            operacao,
                                                                                            tipo_transacao,
                                                                                            cod_produto,
                                                                                            cod_barra,
                                                                                            cancelado,
                                                                                            excluido,
                                                                                            soma_relatorio,
                                                                                            identificador,
                                                                                            deposito,
                                                                                            obs,
                                                                                            preco_unitario,
                                                                                            hora_lancamento,
                                                                                            natureza_operacao,
                                                                                            tabela_preco,
                                                                                            nome_tabela_preco,
                                                                                            cod_sefaz_situacao,
                                                                                            desc_sefaz_situacao,
                                                                                            protocolo_aut_nfe,
                                                                                            dt_update,
                                                                                            forma_cheque_prazo,
                                                                                            total_cheque_prazo,
                                                                                            cod_natureza_operacao,
                                                                                            preco_tabela_epoca,
                                                                                            desconto_total_item,
                                                                                            conferido,
                                                                                            transacao_pedido_venda,
                                                                                            codigo_modelo_nf,
                                                                                            acrescimo,
                                                                                            mob_checkout,
                                                                                            aliquota_iss,
                                                                                            base_iss,
                                                                                            ordem,
                                                                                            codigo_rotina_origem,
                                                                                            timestamp,
                                                                                            troco,
                                                                                            CPF
                                                                                            ) 
                                                                                            values
                                                                                            (
                                                                                            '".$dadosmov2['D'][0]."',
                                                                                            '".$dadosmov2['D'][1]."',
                                                                                            '".$dadosmov2['D'][2]."',
                                                                                            '".$dadosmov2['D'][3]."',
                                                                                            '".$dadosmov2['D'][4]."',
                                                                                            '".$dadosmov2['D'][5]."',
                                                                                            '".$dadosmov2['D'][6]."',
                                                                                            '".$dadosmov2['D'][7]."',
                                                                                            '".$dadosmov2['D'][8]."',
                                                                                            '".$dadosmov2['D'][9]."',
                                                                                            '".$dadosmov2['D'][10]."',
                                                                                            '".$dadosmov2['D'][11]."',
                                                                                            '".$dadosmov2['D'][12]."',
                                                                                            '".$dadosmov2['D'][13]."',
                                                                                            '".$dadosmov2['D'][14]."',
                                                                                            '".$dadosmov2['D'][15]."',
                                                                                            '".$dadosmov2['D'][16]."',
                                                                                            '".$dadosmov2['D'][17]."',
                                                                                            '".$dadosmov2['D'][18]."',
                                                                                            '".$dadosmov2['D'][19]."',
                                                                                            '".$dadosmov2['D'][20]."',
                                                                                            '".$dadosmov2['D'][21]."',
                                                                                            '".$dadosmov2['D'][22]."',
                                                                                            '".$dadosmov2['D'][23]."',
                                                                                            '".$dadosmov2['D'][24]."',
                                                                                            '".$dadosmov2['D'][25]."',
                                                                                            '".$dadosmov2['D'][26]."',
                                                                                            '".$dadosmov2['D'][27]."',
                                                                                            '".$dadosmov2['D'][28]."',
                                                                                            '".$dadosmov2['D'][29]."',
                                                                                            '".$dadosmov2['D'][30]."',
                                                                                            '".$dadosmov2['D'][31]."',
                                                                                            '".$dadosmov2['D'][32]."',
                                                                                            '".$dadosmov2['D'][33]."',
                                                                                            '".$dadosmov2['D'][34]."',
                                                                                            '".$dadosmov2['D'][35]."',
                                                                                            '".$dadosmov2['D'][36]."',
                                                                                            '".$dadosmov2['D'][37]."',
                                                                                            '".$dadosmov2['D'][38]."',
                                                                                            '".$dadosmov2['D'][39]."',
                                                                                            '".$dadosmov2['D'][40]."',
                                                                                            '".$dadosmov2['D'][41]."',
                                                                                            '".$dadosmov2['D'][42]."',
                                                                                            '".$dadosmov2['D'][43]."',
                                                                                            '".$dadosmov2['D'][44]."',
                                                                                            '".$dadosmov2['D'][45]."',
                                                                                            '".$dadosmov2['D'][46]."',
                                                                                            '".$dadosmov2['D'][47]."',
                                                                                            '".$dadosmov2['D'][48]."',
                                                                                            '".$dadosmov2['D'][49]."',
                                                                                            '".$dadosmov2['D'][50]."',
                                                                                            '".$dadosmov2['D'][51]."',
                                                                                            '".$dadosmov2['D'][52]."',
                                                                                            '".$dadosmov2['D'][53]."',
                                                                                            '".$dadosmov2['D'][54]."',
                                                                                            '".$dadosmov2['D'][55]."',
                                                                                            '".$dadosmov2['D'][56]."',
                                                                                            '".$dadosmov2['D'][57]."',
                                                                                            '".$dadosmov2['D'][58]."',
                                                                                            '".$dadosmov2['D'][59]."',
                                                                                            '".$dadosmov2['D'][60]."',
                                                                                            '".$dadosmov2['D'][61]."',
                                                                                            '".$dadosmov2['D'][62]."',
                                                                                            '".$dadosmov2['D'][63]."',
                                                                                            '".$dadosmov2['D'][64]."',
                                                                                            '".$dadosmov2['D'][65]."',
                                                                                            '".$dadosmov2['D'][66]."',
                                                                                            '".$dadosmov2['D'][67]."',
                                                                                            '".$dadosmov2['D'][68]."',
                                                                                            '".$dadosmov2['D'][69]."',
                                                                                            '".$dadosmov2['D'][70]."',
                                                                                            '".$dadosmov2['D'][71]."',
                                                                                            '".$dadosmov2['D'][72]."',
                                                                                            '".$dadosmov2['D'][73]."',
                                                                                            '".$dadosmov2['D'][74]."',
                                                                                            '".$dadosmov2['D'][75]."',
                                                                                            '".$dadosmov2['D'][76]."',
                                                                                            '".$dadosmov2['D'][77]."',
                                                                                            '".$dadosmov2['D'][78]."',
                                                                                            '".$dadosmov2['D'][79]."',
                                                                                            '".$dadosmov2['D'][80]."',
                                                                                            '".$dadosmov2['D'][81]."',
                                                                                            '".$dadosmov2['D'][82]."',
                                                                                            '".$dadosmov2['D'][83]."',
                                                                                            '".$dadosmov2['D'][84]."',
                                                                                            '".$dadosmov2['D'][85]."',
                                                                                            '0'
                                                                                            );";	

                                                            $rwinsertintomov1=mysqli_query($contmp, $insertintomovfull);
                                                            if(!$rwinsertintomov1)
                                                            {
                                                            //echo 'erro1 : '	.$insertintomovfull;
                                                            }
                                         $timestampVENDA=  $dadosmov2['D'][84];                 
                                        }
                                    }
                                     IF($timestampVENDA!='')
                                     {    
                                        $sqlcontrolestorno="UPDATE controle_linx SET COD_CONSULTA='".$timestampVENDA."' 
                                                    WHERE COD_EMPRESA=".$rsconfig['COD_EMPRESA']." 
                                                          AND COD_UNIVEND=".$rwuni[COD_UNIVEND]." 
                                                          AND TIP_CONTROLE='1'";
                                        $controlestorno=mysqli_query($contmp, $sqlcontrolestorno);   
                                     }
                                             unset($array);
                                             unset($insertintomovfull);





                            // fim da inserção de vendas
                            //======================atualizar cliente na lista  linx==================================
                           $sqlclienebusca="SELECT codigo_cliente FROM linx_movimento WHERE cnpj_emp='$NUM_CGCECPF' GROUP BY identificador";
                               $rwclienebusca= mysqli_query($contmp,$sqlclienebusca);
                               if($rwclienebusca->num_rows > '0'){
                                               while ($rsclienebusca = mysqli_fetch_assoc($rwclienebusca)) {  
                                                                    $curlcli = curl_init();
                                                                     curl_setopt_array($curlcli, array(
                                                                     CURLOPT_URL => 'https://webapi.microvix.com.br/1.0/api/integracao',
                                                                     CURLOPT_RETURNTRANSFER => true,
                                                                     CURLOPT_ENCODING => '',
                                                                     CURLOPT_MAXREDIRS => 100000000,
                                                                     CURLOPT_TIMEOUT => 300,
                                                                     CURLOPT_FOLLOWLOCATION => true,
                                                                     CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                                                                     CURLOPT_CUSTOMREQUEST => 'POST',
                                                                     CURLOPT_POSTFIELDS =>'<?xml version=\'1.0\' encoding=\'utf-8\' ?>
                                                                                                               <LinxMicrovix>
                                                                                                                       <Authentication user="linx_export" password="linx_export" />
                                                                                                                       <ResponseFormat>xml</ResponseFormat>
                                                                                                                       <Command>
                                                                                                                               <Name>LinxClientesFornec</Name>
                                                                                                                               <Parameters>
                                                                                                                                       <Parameter id="chave">'.$rsconfig[DES_SENHA].'</Parameter>
                                                                                                                                       <Parameter id="cnpjEmp">'.$NUM_CGCECPF.'</Parameter>
                                                                                                                                       '.$timestampcad.'
                                                                                                                                        <Parameter id="cod_cliente">'.$rsclienebusca[codigo_cliente].'</Parameter>
                                                                                                                                       <Parameter id="data_inicial">NULL</Parameter>
                                                                                                                                       <Parameter id="data_fim">NULL</Parameter>                                           
                                                                                                                               </Parameters>
                                                                                                                       </Command>
                                                                                                               </LinxMicrovix>',
                                                                     CURLOPT_HTTPHEADER => array(
                                                                       'Content-Type: application/xml'
                                                                     ),
                                                               ));
                                                               $responsecli = curl_exec($curlcli);
                                                               curl_close($curlcli);
                                                               $xmlcli = simplexml_load_string($responsecli, "SimpleXMLElement", LIBXML_NOCDATA);
                                                               $jsoncli = json_encode($xmlcli);
                                                               $arraycli = json_decode($jsoncli,TRUE);

                                                                    foreach ($arraycli[ResponseData][R] as $keycli => $valuecli) {
                                                                                    $altercli="UPDATE linx_movimento SET CPF='$valuecli[4]' WHERE codigo_cliente=$valuecli[1] AND cnpj_emp='$NUM_CGCECPF'";
                                                                                    $rwupdatevenda=mysqli_query($contmp, $altercli);
                                                                                    if(!$rwupdatevenda)
                                                                                    {
                                                                                           // echo 'erroupdate:'. $altercli;
                                                                                    }							
                                                                    }

                                               }
                                    }   

                            //==================================FIM====================================
                            //=======================================Estorno marka================================================
                              $sqlextorno="SELECT 
                                                    cnpj_emp,	
                                                    COD_VENDA,
                                                    atendente,
                                                    COD_VENDAPDV,
                                                    COD_CUPOM,
                                                    DAT_CADASTR_WS,
                                                    identificador,
                                                    cod_produto,
                                                    cod_barra,
                                                    COD_ITEMEXT,
                                                    quantidade,
                                                    preco_custo,
                                                    desconto,
                                                    valor_total,
                                                     forma_dinheiro,
                                                     total_dinheiro,
                                                     total_cartao,
                                                    valor_liquido,
                                                     valor_vale,
                                                    VAL_TOTPRODU,
                                                     VAL_TOTVENDA,
                                                    VAL_DESCONTO,
                                                     ABS(truncate(CAST(case when valor_total - valor_vale = '0' then '0.00' ELSE (valor_total-valor_vale) +VAL_RESGATE END AS DECIMAL(15,2)),2))  AS VL_LIQUIDOVENDA,
                                                     DES_PRODUTO,
                                                     NUM_CARTAO,
                                                     operacao,
                                                     COD_EXTERNO,
                                                     NOM_USUARIO,
                                                     des_parametro1,
                                                     des_parametro2,
                                                     des_parametro3,
                                                    des_parametro4,
                                                     des_parametro5,
                                                     des_parametro6,
                                                     des_parametro7,
                                                     des_parametro8,
                                                    des_parametro9,
                                                    des_parametro10,
                                                    des_parametro11,
                                                    des_parametro12,
                                                     COD_STATUSCRED,
                                                     DES_FORMAPA,
                                                    VAL_LIQUIDO_PROD,
                                                    VAL_TOTITEM_PROD,
                                                    VAL_DESCONTO_PROD
                                             FROM 
                                             (SELECT 
                                                    mov.cnpj_emp,	
                                                    ven.COD_VENDA,
                                                     mov.usuario atendente,
                                                     ven.COD_VENDAPDV,
                                                     ven.COD_CUPOM,
                                                     ven.DAT_CADASTR_WS,
                                                     mov.identificador,
                                                     mov.cod_produto,
                                                     mov.cod_barra,
                                                     iten.COD_ITEMEXT,
                                                     mov.quantidade,
                                                     SUM(truncate(mov.preco_custo,2)) preco_custo,
                                                     SUM(truncate(mov.valor_liquido,2)) valor_liquido,
                                                     SUM(truncate(mov.desconto,2)) desconto,
                                                     SUM(truncate(mov.valor_total,2)) valor_total,
                                                     mov.forma_dinheiro,
                                                     truncate(mov.total_dinheiro,2) total_dinheiro,
                                                     mov.total_cartao,
                                                     truncate(tro.valor_vale,2) valor_vale,
                                                     truncate(ven.VAL_TOTPRODU,2) VAL_TOTPRODU,
                                                     truncate(ven.VAL_TOTVENDA,2) VAL_TOTVENDA,
                                                     truncate(ven.VAL_DESCONTO,2) VAL_DESCONTO,
                                                     prod.DES_PRODUTO,
                                                     cli.NUM_CARTAO,
                                                     mov.operacao,
                                                     usu.COD_EXTERNO,
                                                     usu.NOM_USUARIO,
                                                     param1.des_parametro des_parametro1,
                                                     param2.des_parametro des_parametro2,
                                                     param3.des_parametro des_parametro3,
                                                     param4.des_parametro des_parametro4,
                                                     param5.des_parametro des_parametro5,
                                                     param6.des_parametro des_parametro6,
                                                     param7.des_parametro des_parametro7,
                                                     param8.des_parametro des_parametro8,
                                                     param9.des_parametro des_parametro9,
                                                     param10.des_parametro des_parametro10,
                                                     param11.des_parametro des_parametro11,
                                                     param12.des_parametro des_parametro12,
                                                     ven.COD_STATUSCRED,
                                                     pag.DES_FORMAPA,
                                                     iten.VAL_LIQUIDO VAL_LIQUIDO_PROD,
                                                     iten.VAL_TOTITEM VAL_TOTITEM_PROD,
                                                     iten.VAL_DESCONTO VAL_DESCONTO_PROD,
                                                     	ven.VAL_RESGATE
                                     FROM linx_movimento mov
                                     inner join linxmovimentotrocas tro ON tro.identificador=mov.identificador
                                     INNER JOIN vendas ven ON cod_vendapdv=mov.identificador
                                     INNER JOIN itemvenda iten ON iten.COD_EXTERNO=mov.cod_produto AND ven.COD_VENDA=iten.COD_VENDA
                                     INNER JOIN produtocliente prod ON prod.COD_EXTERNO=mov.cod_produto
                                     INNER JOIN clientes cli ON cli.COD_CLIENTE=ven.COD_CLIENTE
                                     inner JOIN usuarios usu ON usu.COD_USUARIO=ven.COD_VENDEDOR
                                     INNER JOIN formapagamento pag ON pag.COD_FORMAPA=ven.COD_FORMAPA
                                     left JOIN parametro1 param1 ON param1.cod_parametro=iten.DES_PARAM1
                                     left JOIN parametro2 param2 ON param2.cod_parametro=iten.DES_PARAM2
                                     left JOIN parametro3 param3 ON param3.cod_parametro=iten.DES_PARAM3
                                     left JOIN parametro4 param4 ON param4.cod_parametro=iten.DES_PARAM4
                                     left JOIN parametro5 param5 ON param5.cod_parametro=iten.DES_PARAM5
                                     left JOIN parametro6 param6 ON param6.cod_parametro=iten.DES_PARAM6
                                     left JOIN parametro7 param7 ON param7.cod_parametro=iten.DES_PARAM7
                                     left JOIN parametro8 param8 ON param8.cod_parametro=iten.DES_PARAM8
                                     left JOIN parametro9 param9 ON param9.cod_parametro=iten.DES_PARAM9
                                     left JOIN parametro10 param10 ON param10.cod_parametro=iten.DES_PARAM10
                                     left JOIN parametro11 param11 ON param11.cod_parametro=iten.DES_PARAM11
                                     left JOIN parametro12 param12 ON param12.cod_parametro=iten.DES_PARAM12
                                     WHERE mov.cnpj_emp='$NUM_CGCECPF' and  ven.cod_empresa=".$rsconfig['COD_EMPRESA']." 
                                      group by mov.identificador)tmpveenda;"; 
                            $rwextorno=  mysqli_query($contmp, $sqlextorno);
                            if($rwextorno->num_rows > '0'){
                                $ARRAYVENDAPROD=ARRAY();   
                                while ($rsestono= mysqli_fetch_assoc($rwextorno))
                                {               

                                            $estorno='<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:fid="fidelidade">
                                                                            <soapenv:Header/>
                                                                            <soapenv:Body>
                                                                                   <fid:EstornaVenda>
                                                                                          <fase>fase7</fase>
                                                                                          <id_vendapdv>'.$rsestono[identificador].'</id_vendapdv>
                                                                                          <dadosLogin>
                                                                                                    <login>'.$dadoslogin[0].'</login>
                                                                                                    <senha>'.$dadoslogin[1].'</senha>
                                                                                                    <idloja>'.$dadoslogin[2].'</idloja>
                                                                                                    <idcliente>'.$dadoslogin[4].'</idcliente>
                                                                                                   <idmaquina>EstornoOffline</idmaquina>    
                                                                                          </dadosLogin>
                                                                                   </fid:EstornaVenda>
                                                                            </soapenv:Body>
                                                                         </soapenv:Envelope>';
                                             $retornoestrno= estornovenda($estorno);
                                             
                                              echo $estorno.'/r/n <br>';   
                                                
                                                     
                                            //  echo '<pre>';
                                            // print_r($retornoestrno);


                                        $sqlitem="SELECT 
                                                    cnpj_emp,	
                                                    COD_VENDA,
                                                    atendente,
                                                    COD_VENDAPDV,
                                                    COD_CUPOM,
                                                    DAT_CADASTR_WS,
                                                    identificador,
                                                    cod_produto,
                                                    cod_barra,
                                                    COD_ITEMEXT,
                                                    quantidade,
                                                    preco_custo,
                                                    desconto,
                                                    valor_total,
                                                     forma_dinheiro,
                                                     total_dinheiro,
                                                     total_cartao,
                                                    valor_liquido,
                                                     valor_vale,
                                                    VAL_TOTPRODU,
                                                     VAL_TOTVENDA,
                                                    VAL_DESCONTO,
                                                     ABS(truncate(CAST(case when valor_total - valor_vale = '0' then '0.00' ELSE (valor_total-valor_vale) +VAL_RESGATE END AS DECIMAL(15,2)),2))  AS VL_LIQUIDOVENDA,
                                                    DES_PRODUTO,
                                                     NUM_CARTAO,
                                                     operacao,
                                                     COD_EXTERNO,
                                                     NOM_USUARIO,
                                                     des_parametro1,
                                                     des_parametro2,
                                                     des_parametro3,
                                                    des_parametro4,
                                                     des_parametro5,
                                                     des_parametro6,
                                                     des_parametro7,
                                                     des_parametro8,
                                                    des_parametro9,
                                                    des_parametro10,
                                                    des_parametro11,
                                                    des_parametro12,
                                                     COD_STATUSCRED,
                                                     DES_FORMAPA,
                                                    VAL_LIQUIDO_PROD,
                                                    VAL_TOTITEM_PROD,
                                                    VAL_DESCONTO_PROD
                                             FROM 
                                             (SELECT 
                                                         mov.cnpj_emp,	
                                                    ven.COD_VENDA,
                                                     mov.usuario atendente,
                                                     ven.COD_VENDAPDV,
                                                     ven.COD_CUPOM,
                                                     ven.DAT_CADASTR_WS,
                                                     mov.identificador,
                                                     mov.cod_produto,
                                                     mov.cod_barra,
                                                     iten.COD_ITEMEXT,
                                                     mov.quantidade,
                                                      truncate(mov.preco_custo,2) preco_custo,
                                                      truncate(mov.desconto,2) desconto,
                                                      truncate(mov.valor_total,2) valor_total,
                                                     mov.forma_dinheiro,
                                                      truncate(mov.total_dinheiro,2) total_dinheiro,
                                                     mov.total_cartao,
                                                      truncate(mov.valor_liquido,2) valor_liquido,
                                                      truncate(tro.valor_vale,2) valor_vale,
                                                     truncate(ven.VAL_TOTPRODU,2) VAL_TOTPRODU,
                                                      truncate(ven.VAL_TOTVENDA,2) VAL_TOTVENDA,
                                                      truncate(ven.VAL_DESCONTO,2) VAL_DESCONTO,
                                                      '0.00' VL_LIQUIDOVENDA,
                                                     prod.DES_PRODUTO,
                                                     cli.NUM_CARTAO,
                                                     mov.operacao,
                                                     usu.COD_EXTERNO,
                                                     usu.NOM_USUARIO,
                                                     param1.des_parametro des_parametro1,
                                                     param2.des_parametro des_parametro2,
                                                     param3.des_parametro des_parametro3,
                                                     param4.des_parametro des_parametro4,
                                                     param5.des_parametro des_parametro5,
                                                     param6.des_parametro des_parametro6,
                                                     param7.des_parametro des_parametro7,
                                                     param8.des_parametro des_parametro8,
                                                     param9.des_parametro des_parametro9,
                                                     param10.des_parametro des_parametro10,
                                                     param11.des_parametro des_parametro11,
                                                     param12.des_parametro des_parametro12,
                                                     ven.COD_STATUSCRED,
                                                     pag.DES_FORMAPA,
                                                     iten.VAL_LIQUIDO VAL_LIQUIDO_PROD,
                                                     iten.VAL_TOTITEM VAL_TOTITEM_PROD,
                                                     iten.VAL_DESCONTO VAL_DESCONTO_PROD,
                                                     	ven.VAL_RESGATE
                                          FROM linx_movimento mov
                                          inner join linxmovimentotrocas tro ON tro.identificador=mov.identificador
                                          INNER JOIN vendas ven ON cod_vendapdv=mov.identificador
                                          INNER JOIN itemvenda iten ON iten.COD_EXTERNO=mov.cod_produto AND ven.COD_VENDA=iten.COD_VENDA
                                          INNER JOIN produtocliente prod ON prod.COD_EXTERNO=mov.cod_produto
                                          INNER JOIN clientes cli ON cli.COD_CLIENTE=ven.COD_CLIENTE
                                          inner JOIN usuarios usu ON usu.COD_USUARIO=ven.COD_VENDEDOR
                                          INNER JOIN formapagamento pag ON pag.COD_FORMAPA=ven.COD_FORMAPA
                                          left JOIN parametro1 param1 ON param1.cod_parametro=iten.DES_PARAM1
                                          left JOIN parametro2 param2 ON param2.cod_parametro=iten.DES_PARAM2
                                          left JOIN parametro3 param3 ON param3.cod_parametro=iten.DES_PARAM3
                                          left JOIN parametro4 param4 ON param4.cod_parametro=iten.DES_PARAM4
                                          left JOIN parametro5 param5 ON param5.cod_parametro=iten.DES_PARAM5
                                          left JOIN parametro6 param6 ON param6.cod_parametro=iten.DES_PARAM6
                                          left JOIN parametro7 param7 ON param7.cod_parametro=iten.DES_PARAM7
                                          left JOIN parametro8 param8 ON param8.cod_parametro=iten.DES_PARAM8
                                          left JOIN parametro9 param9 ON param9.cod_parametro=iten.DES_PARAM9
                                          left JOIN parametro10 param10 ON param10.cod_parametro=iten.DES_PARAM10
                                          left JOIN parametro11 param11 ON param11.cod_parametro=iten.DES_PARAM11
                                          left JOIN parametro12 param12 ON param12.cod_parametro=iten.DES_PARAM12
                                          WHERE mov.cnpj_emp='$NUM_CGCECPF' and  ven.cod_empresa=".$rsconfig['COD_EMPRESA']." and mov.identificador ='$rsestono[identificador]' ORDER BY iten.COD_ITEMVEN asc)tmpveenda2;";
                                        $rwiten= mysqli_query($contmp, $sqlitem);                             
                                        while ($rsiten= mysqli_fetch_assoc($rwiten))
                                        {        
                                             $vendaitem[]=array(
                                                                 'id_item'=>$rsiten[COD_ITEMEXT],
                                                                 'produto'=>$rsiten[DES_PRODUTO],
                                                                 'codigoproduto'=>$rsiten[cod_produto],
                                                                 'quantidade'=>$rsiten[quantidade],
                                                                 'valorbruto'=>$rsiten[VAL_TOTITEM_PROD],
                                                                 'descontovalor'=>$rsiten[VAL_DESCONTO_PROD],
                                                                 'valorliquido'=>$rsiten[VAL_LIQUIDO_PROD],
                                                                 'ean'=>$rsiten['cod_barra'],
                                                                 'atributo1'=>$rsiten['des_parametro1'],
                                                                 'atributo2'=>$rsiten['des_parametro2'],
                                                                 'atributo3'=>$rsiten['des_parametro3'],
                                                                 'atributo4'=>$rsiten['des_parametro4'],
                                                                 'atributo5'=>$rsiten['des_parametro5'],
                                                                 'atributo6'=>$rsiten['des_parametro6'],
                                                                 'atributo7'=>$rsiten['des_parametro7'],
                                                                 'atributo8'=>$rsiten['des_parametro8'],
                                                                 'atributo9'=>$rsiten['des_parametro9'],
                                                                 'atributo10'=>$rsiten['des_parametro10'],
                                                                 'atributo11'=>$rsiten['des_parametro11'],
                                                                 'atributo12'=>$rsiten['des_parametro12'],
                                                                 'atributo13'=>$rsiten['des_parametro13']
                                                             );
                                        }
                                         $ARRAYVENDAPROD[venda][$rsestono[identificador]]=array('id_vendapdv'=>$rsestono[identificador].'_Troca',
                                                                                                'id_vendapdvOriginal'=>$rsestono[identificador],    
                                                                                                'datahora'=>date('Y-m-d H:i:s',strtotime( $rsestono[DAT_CADASTR_WS].' + 1 seconds')),
                                                                                                'cartao'=>$rsestono[NUM_CARTAO],
                                                                                                'valortotalbruto'=>$rsestono['valor_total'],
                                                                                                'descontototalvalor'=>$rsestono['valor_vale'],
                                                                                                'valortotalliquido'=> $rsestono['VL_LIQUIDOVENDA'],
                                                                                                'valor_resgate'=>$rsestono['VAL_RESGATE'],
                                                                                                'cupomfiscal'=>$rsestono[COD_CUPOM],
                                                                                                'cupomdesconto'=>'',
                                                                                                'formapagamento'=>$rsestono[DES_FORMAPA],
                                                                                                'codatendente'=>$rsestono[atendente],
                                                                                                'nomevendedor'=>$rsestono['NOM_USUARIO'],
                                                                                                'codvendedor'=>$rsestono['COD_EXTERNO'],
                                                                                                'cnpj_emp'=>$rsestono['cnpj_emp'],
                                                                                                'vendaitem'=> $vendaitem                                                                                            
                                                                                 );               
                                     $vendaitem=ARRAY(); 

                                   // array_push($ARRAYVENDAPROD, $vendaitem);
                                       /////////////////////////////////////////////////////////////////////////////////////////
                                }  

                            }

                            //execta e monta o xml de venda 

                          foreach ($ARRAYVENDAPROD[venda] as $keyvenda => $dadosvenda) {   
                             /*  if($dadosvenda[id_vendapdv]=='f5ced583-2e5f-4a16-aadc-d6f225d95b06_Troca')
                                {    
                                  echo '<pre>';
                                     print_r($ARRAYVENDAPROD);
                                     echo '</pre>';
                                }*/ 

                            foreach ($dadosvenda[vendaitem] as $keyitn => $DADOSITM) 
                            {

                                                                                               $itm.= '<vendaitem>
                                                                                                              <id_item>'.$DADOSITM[id_item].'</id_item>
                                                                                                              <produto>'.$DADOSITM[produto].'</produto>
                                                                                                              <codigoproduto>'.$DADOSITM[codigoproduto].'</codigoproduto>
                                                                                                              <quantidade>'.$DADOSITM[quantidade].'</quantidade>
                                                                                                              <valorbruto>'.$DADOSITM[valorbruto].'</valorbruto>
                                                                                                              <descontovalor>'.$DADOSITM[descontovalor].'</descontovalor>
                                                                                                              <valorliquido>'.$DADOSITM[valorliquido].'</valorliquido>
                                                                                                              <ean>'.$DADOSITM[ean].'</ean>
                                                                                                              <atributo1>'.$DADOSITM[atributo1].'</atributo1>
                                                                                                              <atributo2>'.$DADOSITM[atributo2].'</atributo2>
                                                                                                              <atributo3>'.$DADOSITM[atributo3].'</atributo3>
                                                                                                              <atributo4>'.$DADOSITM[atributo4].'</atributo4>
                                                                                                              <atributo5>'.$DADOSITM[atributo5].'</atributo5>
                                                                                                              <atributo6>'.$DADOSITM[atributo6].'</atributo6>
                                                                                                              <atributo7>'.$DADOSITM[atributo7].'</atributo7>
                                                                                                              <atributo8>'.$DADOSITM[atributo8].'</atributo8>
                                                                                                              <atributo9>'.$DADOSITM[atributo9].'</atributo9>
                                                                                                              <atributo10>'.$DADOSITM[atributo10].'</atributo10>
                                                                                                              <atributo11>'.$DADOSITM[atributo11].'</atributo11>
                                                                                                              <atributo12>'.$DADOSITM[atributo12].'</atributo12>
                                                                                                              <atributo13>'.$DADOSITM[atributo13].'</atributo13>
                                                                                                       </vendaitem>';	
                            } 
                                $vendaxml='<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:fid="fidelidade">
                                                                               <soapenv:Header/>
                                                                               <soapenv:Body>
                                                                                      <fid:InsereVenda>
                                                                                             <fase>fase7</fase>
                                                                                             <venda>
                                                                                                    <id_vendapdv>'.$dadosvenda[id_vendapdv].'</id_vendapdv>
                                                                                                    <datahora>'.$dadosvenda[datahora].'</datahora>
                                                                                                    <cartao>'.$dadosvenda[cartao].'</cartao>
                                                                                                    <valortotalbruto>'.$dadosvenda['valortotalbruto'].'</valortotalbruto>
                                                                                                    <descontototalvalor>'.$dadosvenda['descontototalvalor'].'</descontototalvalor>
                                                                                                    <valortotalliquido>'.$dadosvenda['valortotalliquido'].'</valortotalliquido>
                                                                                                    <valor_resgate>'.$dadosvenda['valor_resgate'].'</valor_resgate>
                                                                                                    <cupomfiscal>'.$dadosvenda[cupomfiscal].'</cupomfiscal>
                                                                                                    <cupomdesconto></cupomdesconto>
                                                                                                    <formapagamento>'.$dadosvenda[formapagamento].'</formapagamento>
                                                                                                    <codatendente>'.$dadosvenda[codatendente].'</codatendente>
                                                                                                    <itens>
                                                                                                      '.$itm.'									   
                                                                                                    </itens>
                                                                                             </venda>
                                                                                             <dadosLogin>
                                                                                                <login>'.$dadoslogin[0].'</login>
                                                                                                <senha>'.$dadoslogin[1].'</senha>
                                                                                                <idloja>'.$dadoslogin[2].'</idloja>
                                                                                                <idcliente>'.$dadoslogin[4].'</idcliente>
                                                                                                <codvendedor>'.$dadosvenda['codvendedor'].'</codvendedor>
                                                                                                <nomevendedor>'.$dadosvenda['nomevendedor'].'</nomevendedor>
                                                                                              <idmaquina>EstornoOffline</idmaquina>    
                                                                                     </dadosLogin>
                                                                                      </fid:InsereVenda>
                                                                               </soapenv:Body>
                                                                            </soapenv:Envelope>';
                                   
                                    echo $vendaxml.'</br> </br>';
                                    $retornvendaxml= estornovenda($vendaxml);  
                                    unset($vendaxml);
                                    unset($itm);
                                 //   echo 'PDV:'.$dadosvenda[id_vendapdv].'<br>valor:'.abs($dadosvenda['valortotalliquido']);
                                    //deletar o estorno feito sobre as trocas
                                    $SQLtrocas="DELETE FROM linxmovimentotrocas WHERE cnpj_emp=$dadosvenda[cnpj_emp] AND identificador='$dadosvenda[id_vendapdvOriginal]';
                                                DELETE FROM linx_movimento WHERE cnpj_emp=$dadosvenda[cnpj_emp] AND identificador='$dadosvenda[id_vendapdvOriginal]';
                                              ";
                                   mysqli_multi_query($contmp, $SQLtrocas);
                            }

                            ////////////////////////////////////////////////Cancelamento de venda///////////////////////////////////////////////////////////
                            $sqlextornocancelamento="SELECT 
                                                     mov.cnpj_emp,	
                                                     mov.identificador,
                                                     mov.cod_produto,
                                                     mov.cod_barra,
                                                     mov.quantidade,
                                                     mov.preco_custo,
                                                     mov.valor_liquido,
                                                     mov.desconto,
                                                     mov.valor_total,
                                                     mov.forma_dinheiro,
                                                     mov.total_dinheiro,
                                                     mov.total_cartao,
                                                     mov.valor_liquido,
                                                     mov.operacao,
                                                     mov.cancelado
                                                   --  ,ven.COD_STATUSCRED
                                     FROM linx_movimento mov
                                   --  left JOIN vendas ven ON cod_vendapdv=mov.identificador
                                     WHERE  
                                            mov.cancelado='S'  and  
                                            mov.cnpj_emp='$NUM_CGCECPF' 
                                      group by mov.identificador order by  mov.identificador asc;"; 
                            $rwextornoC= mysqli_query($contmp, $sqlextornocancelamento);

                            if($rwextornoC->num_rows > '0'){
                                while ($rsestonoC= mysqli_fetch_assoc($rwextornoC))
                                {  
                                    if($rsestonoC['COD_STATUSCRED']!='')
                                    {    
                                        if($rsestonoC['COD_STATUSCRED']!='6')
                                        {    
                                            $estornoC='<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:fid="fidelidade">
                                                                           <soapenv:Header/>
                                                                           <soapenv:Body>
                                                                                  <fid:EstornaVenda>
                                                                                         <fase>fase7</fase>
                                                                                         <id_vendapdv>'.$rsestonoC[identificador].'</id_vendapdv>
                                                                                         <dadosLogin>
                                                                                                   <login>'.$dadoslogin[0].'</login>
                                                                                                   <senha>'.$dadoslogin[1].'</senha>
                                                                                                   <idloja>'.$dadoslogin[2].'</idloja>
                                                                                                   <idcliente>'.$dadoslogin[4].'</idcliente>
                                                                                                  <idmaquina>CancelamentoOffline</idmaquina>    
                                                                                         </dadosLogin>
                                                                                  </fid:EstornaVenda>
                                                                           </soapenv:Body>
                                                                        </soapenv:Envelope>';
                                         //   echo $estornoC;
                                           $retornoestrno= estornovenda($estornoC);
                                        }
                                    }

                                        $SQLCancelados=" DELETE FROM linx_movimento WHERE cnpj_emp=$rsestonoC[cnpj_emp] AND identificador='$rsestonoC[identificador]';";
                                        mysqli_query($contmp, $SQLCancelados);
                                } 
                            }
                            //==============================================fim===================================================
                           //alterar o codigo do controle timestemp venda
                            $verificakey="SELECT TIMESTAMP FROM linx_movimento WHERE cnpj_emp='$NUM_CGCECPF' ORDER BY TIMESTAMP DESC LIMIT 1";
                            $rsverificakey= mysqli_fetch_assoc(mysqli_query($contmp, $verificakey));

                            "UPDATE controle_linx SET COD_CONSULTA='0' WHERE COD_EMPRESA=".$rsconfig['COD_EMPRESA']." AND COD_UNIVEND=$rwuni[COD_UNIVEND] AND TIP_CONTROLE='1'";

                      //  echo '<br>Cnpj Processado: '.$rwuni[COD_UNIVEND].'<br>';
                      //  unset($NUM_CGCECPF);
                       // unset($dadoslogin);
                        ob_end_flush();
                        ob_flush();
                        flush();
                } 
                echo 'FIM DO PRIMEIRO PERIODO.<br>';
                //dropar residual 
                /*$SQLtrocas="truncate table  linxmovimentotrocas;
                            truncate table  linx_movimento;
                          ";
                mysqli_multi_query($contmp, $SQLtrocas); */
                //verificar se o cliente existe
                //verificar se a venda ja existe
    }
    
}
 ?>