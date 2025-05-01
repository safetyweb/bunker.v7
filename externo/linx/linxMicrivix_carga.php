<?php
include '../../_system/_functionsMain.php';
include '../totvs/funcao.php';
//fnDebug('TRUE');
$tempoPROC='0';
$tempoPROCfinal='0';
$dateinicia=date('Y-m-d',strtotime(date('Y-m-d'). ' - '.$tempoPROC.' days'));
$datefim=date('Y-m-d',strtotime(date('Y-m-d'). ' - '.$tempoPROCfinal.' days'));

echo 'DIA INICIAL: '.$dateinicia.'<br>';
echo 'DIA PROCESSADO: '.$datefim.'<br>';

    $admconex=$connAdm->connAdm();
    $sqlconfig="SELECT * FROM webhook web WHERE tip_webhook=8 AND web.LOG_ESTATUS='S'";
    $rwconfigorigem= mysqli_query($admconex, $sqlconfig);
    while($rsconfig= mysqli_fetch_assoc($rwconfigorigem))
    {           
        
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
                          

                          
                                     
                                        $timestampcad='<Parameter id="timestamp">'.$rscontador[COD_CONSULTA].'</Parameter>'; 
                                        $datecontroca='';
                                 
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
                                                                    <Parameter id="data_inicial">'.date('Y-m-d',strtotime(date('Y-m-d'). ' - '.$tempoPROC.' days')).'</Parameter>
                                                                    <Parameter id="data_fim">'.date('Y-m-d',strtotime(date('Y-m-d'). ' - '.$tempoPROCfinal.' days')).'</Parameter>
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
                                        $timestamptrocaUP= $valuetrocanew[D][11];              																
                                }
                            }
                                if($timestamptrocaUP!='')
                                {    
                                $sqlcontrolestorno="UPDATE controle_linx SET COD_CONSULTA='".$timestamptrocaUP."' 
                                                        WHERE COD_EMPRESA=".$rsconfig['COD_EMPRESA']." 
                                                              AND COD_UNIVEND=".$rwuni[COD_UNIVEND]." 
                                                              AND TIP_CONTROLE='3'";
                                       $controlestorno=mysqli_query($contmp, $sqlcontrolestorno);
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
                                                                         <Parameter id="data_inicial">'.date('Y-m-d',strtotime(date('Y-m-d'). ' - '.$tempoPROC.' days')).'</Parameter>
                                                                        <Parameter id="data_fim">'.date('Y-m-d',strtotime(date('Y-m-d'). ' - '.$tempoPROCfinal.' days')).'</Parameter>
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

                                        $verifcasetem="SELECT * FROM linx_movimento WHERE identificador='".$dadosmov2['D'][58]."';";
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
                                                             $timestampVENDA= $dadosmov2['D'][84];
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

                        ob_end_flush();
                        ob_flush();
                        flush();
                } 
                echo 'FIM DO PRIMEIRO PERIODO.<br>';
               
    }

 ?>