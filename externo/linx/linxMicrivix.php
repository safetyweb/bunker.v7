<?php
include '../../_system/_functionsMain.php';
include '../totvs/funcao.php';
include '../email/envio_sac.php';
//fnDebug('TRUE');
function fnFormatvalorlinx($brl, $casasDecimais = 2) {
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
    $admconex=$connAdm->connAdm();
    $sqlconfig="SELECT * FROM webhook web WHERE tip_webhook=8 AND web.LOG_ESTATUS='S'";
    $rwconfigorigem= mysqli_query($admconex, $sqlconfig);
    while($rsconfig= mysqli_fetch_assoc($rwconfigorigem))
    {
        //truncate table  controle_linx;
        // $SQLtrocas=" truncate table  linxmovimentotrocas;";
        // mysqli_query(connTemp($rsconfig['COD_EMPRESA']), $SQLtrocas); 
     
       $data=date('Y-m-d',strtotime(date('Y-m-d'). ' - 1 days'));
       echo $data.'<br />';
       //  $data=date('Y-m-d');   
      //$data='2022-04-19';
          
                unset($dadoslogin);
                $key_marka= fnDecode(base64_decode($rsconfig[DES_SENHAMARKA]));
                $dadoslogin=explode(';', $key_marka);
                if($dadoslogin[2]=='99999')
                {
                    // and COD_UNIVEND='97552'
                   $sqluni= "SELECT COD_UNIVEND,NUM_CGCECPF,NOM_FANTASI FROM unidadevenda WHERE COD_EMPRESA=$dadoslogin[4] AND LOG_ESTATUS='S' ORDER BY COD_UNIVEND desc";        
                }elseif ($dadoslogin[2]=='') {
                   $sqluni="SELECT COD_UNIVEND,NUM_CGCECPF,NOM_FANTASI FROM unidadevenda WHERE COD_UNIVEND= $dadoslogin[2] and  COD_EMPRESA=$dadoslogin[4] AND LOG_ESTATUS='S' ORDER BY COD_UNIVEND desc";
                }



                $rsuni= mysqli_query($admconex, $sqluni);
                while ($rwuni= mysqli_fetch_assoc($rsuni))
                {
                    
                    ob_start();
                    $NUM_CGCECPF=fnLimpaDoc($rwuni[NUM_CGCECPF]);
                    $dadoslogin[2]=$rwuni[COD_UNIVEND];


                           // $contmp= connTemp($rsconfig['COD_EMPRESA'],'');
                            
                            $sqlcontador="SELECT * FROM controle_linx WHERE COD_EMPRESA=$rsconfig[COD_EMPRESA] AND COD_UNIVEND=$rwuni[COD_UNIVEND] AND TIP_CONTROLE IN (1,2,3)";
                            $rwcontador= mysqli_query(connTemp($rsconfig['COD_EMPRESA'],''), $sqlcontador);
                            if($rwcontador->num_rows <= '0')
                           {
                                 $insercontrole="INSERT INTO controle_linx (COD_CONSULTA, COD_EMPRESA, COD_UNIVEND, TIP_CONTROLE, DES_CONTROLE) VALUES (0, $rsconfig[COD_EMPRESA], $rwuni[COD_UNIVEND], 1, 'VENDA');
                                                 INSERT INTO controle_linx (COD_CONSULTA, COD_EMPRESA, COD_UNIVEND, TIP_CONTROLE, DES_CONTROLE) VALUES (0, $rsconfig[COD_EMPRESA], $rwuni[COD_UNIVEND], 2, 'CADASTRO');
                                                 INSERT INTO controle_linx (COD_CONSULTA, COD_EMPRESA, COD_UNIVEND, TIP_CONTROLE, DES_CONTROLE) VALUES (0, $rsconfig[COD_EMPRESA], $rwuni[COD_UNIVEND], 3, 'TROCAS');";
                                mysqli_multi_query(connTemp($rsconfig['COD_EMPRESA'],''), $insercontrole);
                           }


                           if($rwcontador->num_rows > '0')
                           {    
                                while($rscontador= mysqli_fetch_assoc($rwcontador))
                                {
                                    //timestamp de venda
                                    if($rscontador[COD_CONSULTA] > '0' && $rscontador[TIP_CONTROLE]=='1' )
                                    {
                                        
                                        $timestampven='<Parameter id="timestamp">'.$rscontador[COD_CONSULTA].'</Parameter>
                                                        <Parameter id="data_inicial">'.$data.'</Parameter>
                                                        <Parameter id="data_fim">'.$data.'</Parameter>';                                         
                                    }elseif ($rscontador[COD_CONSULTA] <= '0' && $rscontador[TIP_CONTROLE]=='1' ){
                                         $timestampven=' <Parameter id="timestamp">'.$rscontador[COD_CONSULTA].'</Parameter>
                                                         <Parameter id="data_inicial">'.$data.'</Parameter>
                                                         <Parameter id="data_fim">'.$data.'</Parameter> '; 
                                    }

                                    //timestamp de cadastro
                                    if($rscontador[COD_CONSULTA] > '0' && $rscontador[TIP_CONTROLE]=='2' )
                                    {
                                        $timestampcad='<Parameter id="timestamp">'.$rscontador[COD_CONSULTA].'</Parameter>'; 
                                    }
                                    //timestamp de TROCAS
                                    if($rscontador[COD_CONSULTA] > '0' && $rscontador[TIP_CONTROLE]=='3' )
                                    {
                                        
                                        $timestamptroca=' <Parameter id="timestamp">'.$rscontador[COD_CONSULTA].'</Parameter>
                                                          <Parameter id="data_inicial">'.$data.'</Parameter>
                                                          <Parameter id="data_fim">'.$data.'</Parameter>'; 
                                    }elseif ($rscontador[COD_CONSULTA] <= '0' && $rscontador[TIP_CONTROLE]=='3' ) {
                                        $timestamptroca=' <Parameter id="timestamp">'.$rscontador[COD_CONSULTA].'</Parameter> 
                                                          <Parameter id="data_inicial">'.$data.'</Parameter>
                                                          <Parameter id="data_fim">'.$data.'</Parameter>'; 
                                    }
                                }
                           }else {
                              $timestampven=' <Parameter id="timestamp">0</Parameter>
                                               <Parameter id="data_inicial">'.$data.'</Parameter>
                                               <Parameter id="data_fim">'.$data.'</Parameter> '; 
                               
                               
                                $timestamptroca=' <Parameter id="timestamp">0</Parameter> 
                                                  <Parameter id="data_inicial">'.$data.'</Parameter>
                                                  <Parameter id="data_fim">'.$data.'</Parameter> '; 
                               
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
                                                                </Parameters>
                                                            </Command>
                                                        </LinxMicrovix>',
                              CURLOPT_HTTPHEADER => array(
                                'Content-Type: application/xml; charset=UTF-8'
                              ),
                            ));
                            $err = curl_error($curl);
                            $response = curl_exec($curl);
                            $info = curl_getinfo($curl);
                          //  echo 'Took ' . $info['total_time'] . ' seconds to transfer a request to ' . $info['url'].'<br>';
                            if ($err) {
                             echo "cURL Error #:" . $err;
                            }
                            curl_close($curl);
                            $xml = simplexml_load_string($response, "SimpleXMLElement", LIBXML_NOCDATA);
                            $json = json_encode($xml);
                            $arraytroca = json_decode($json,TRUE);  
                          
                            foreach ($arraytroca['ResponseData']['R'] as $key => $valuetrocanew){                                
                                if(!empty($valuetrocanew[D][2]))
                                {
                                    $trocsql="SELECT * FROM linxmovimentotrocas where identificador='".$valuetrocanew[D][2]."'";                                  
                                    $rwtroc= mysqli_query(connTemp($rsconfig['COD_EMPRESA'],''), $trocsql);
                                        if($rwtroc->num_rows <= '0'){                                        
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
                                                    $rwconfig0= mysqli_query(connTemp($rsconfig['COD_EMPRESA'],''), $insertintotroca);
                                                 
                                            $timestamptrocaUP= $valuetrocanew[D][11];      
                                                $sqlcontrolestorno="UPDATE controle_linx SET COD_CONSULTA='".$valuetrocanew[D][11]."' 
                                                                             WHERE COD_EMPRESA=".$rsconfig['COD_EMPRESA']." 
                                                                                    AND COD_UNIVEND=".$rwuni[COD_UNIVEND]." 
                                                                                    AND TIP_CONTROLE='3'";
                                               $controlestorno=mysqli_query(connTemp($rsconfig['COD_EMPRESA'],''), $sqlcontrolestorno);
                                        }                                      
                                }
                            }
                            
                           
                            //======FIM DA TROCA

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
                                                                     </Parameters>
                                                                 </Command>
                                                             </LinxMicrovix>',
                                   CURLOPT_HTTPHEADER => array(
                                     'Content-Type: application/xml; charset=UTF-8'
                                   ),
                                 ));

                                $err = curl_error($curl);
                                $info = curl_getinfo($curl);
                               // echo 'Took ' . $info['total_time'] . ' seconds to transfer a request to ' . $info['url'].'<br>';
                                $response = curl_exec($curl);
                                if ($err) {
                                 echo "cURL Error #:" . $err;
                                }

                                 curl_close($curl);
                                 $xml = simplexml_load_string($response, "SimpleXMLElement", LIBXML_NOCDATA);
                                 $json = json_encode($xml);
                                 $array = json_decode($json,TRUE);
                                    foreach ($array['ResponseData']['R'] as $dadosmov2) {
                                        if(!empty($dadosmov2['D'][84]))
                                        {
                                           
                                            $insertintomovfull1="insert into linx_movimento (
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
                                                                                            NULL
                                                                                            );";
                                            $rwinsertintomov1=mysqli_query(connTemp($rsconfig['COD_EMPRESA'],''), $insertintomovfull1);
                                          //   mysqli_free_result($rwinsertintomov1);
                                          //  mysqli_next_result($contmp);
                                          
                                            if(!$rwinsertintomov1)
                                            {
                                              echo '<pre>';
                                              print_r($insertintomovfull1);
                                              echo '</pre>';
                                            }
                                            $timestampVENDA= $dadosmov2['D'][84]; 
                                               $sqlcontrolestorno="UPDATE controle_linx SET COD_CONSULTA='".$dadosmov2['D'][84]."' 
                                                                     WHERE COD_EMPRESA=".$rsconfig['COD_EMPRESA']." 
                                                                            AND COD_UNIVEND=".$rwuni[COD_UNIVEND]." 
                                                                             AND TIP_CONTROLE='1'";
                                                $controlestorno=mysqli_query(connTemp($rsconfig['COD_EMPRESA'],''), $sqlcontrolestorno);   
                                        }
                                    }
                                     
                                             unset($array);
                                             unset($insertintomovfull);





                            // fim da inserção de vendas
                            //======================atualizar cliente na lista  linx==================================
                           $sqlclienebusca="SELECT codigo_cliente FROM linx_movimento WHERE cnpj_emp='$NUM_CGCECPF' and CPF is null GROUP BY identificador";
                               $rwclienebusca= mysqli_query(connTemp($rsconfig['COD_EMPRESA'],''),$sqlclienebusca);
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
                                                                     CURLOPT_POSTFIELDS =>'<LinxMicrovix>
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
                                                                       'Content-Type: application/xml; charset=UTF-8'
                                                                     ),
                                                               ));
                                                             //  $responsecli = curl_exec($curlcli);
                                                                $err = curl_error($curlcli);
                                                                $responsecli = curl_exec($curlcli);
                                                                $info = curl_getinfo($curlcli);
                                                              //  echo 'starttransfer_time:'.$info[starttransfer_time].'Took ' . $info['total_time'] . ' seconds to transfer a request to ' . $info['url'].'<br>';
                                                                if ($err) {
                                                                 echo "cURL Error #:" . $err;
                                                                }
                                                               curl_close($curlcli);
                                                               $xmlcli = simplexml_load_string($responsecli, "SimpleXMLElement", LIBXML_NOCDATA);
                                                               $jsoncli = json_encode($xmlcli);
                                                               $arraycli = json_decode($jsoncli,TRUE);

                                                                    foreach ($arraycli[ResponseData][R] as $keycli => $valuecli) {
                                                                                    $altercli="UPDATE linx_movimento SET CPF='$valuecli[4]' WHERE codigo_cliente=$valuecli[1] AND cnpj_emp='$NUM_CGCECPF'";
                                                                                  // echo $altercli.'<br>';
                                                                                    $rwupdatevenda=mysqli_query(connTemp($rsconfig['COD_EMPRESA'],''), $altercli);
                                                                                    if(!$rwupdatevenda)
                                                                                    {
                                                                                           // echo 'erroupdate:'. $altercli;
                                                                                    }							
                                                                    }

                                               }
                                    }   
                              // se não existir o vendedor cod 7 inserir na listas
                                    
                    //                                                                                                                     
                            //==================================FIM====================================
                            //=======================================Estorno marka================================================
                              $sqlextorno="SELECT 
                                                            mov.cnpj_emp,	
                                                            ven.COD_VENDA,
                                                            mov.usuario atendente,
                                                            usu.COD_EXTERNO,
                                                            usu.NOM_USUARIO,
                                                            ven.COD_VENDAPDV,
                                                            ven.COD_CUPOM,
                                                            ven.DAT_CADASTR_WS,
                                                            mov.identificador,                                                    
                                                            truncate(SUM(mov.preco_custo),2) preco_custo,
                                                            truncate(SUM(mov.valor_liquido),2) valor_liquido,
                                                            truncate(mov.desconto,2) desconto,
                                                            ROUND(sum(mov.valor_total)  + mov.desconto,2) valor_total,
                                                            ROUND(CAST(case when (sum(mov.valor_total) - tro.valor_vale)+ mov.desconto = '0' then '0.00' ELSE (sum(mov.valor_total)- tro.valor_vale) + mov.desconto END AS DECIMAL(15,3)),2)  AS VL_LIQUIDOVENDA,
                                                            mov.forma_dinheiro,
                                                            truncate(mov.total_dinheiro,2) total_dinheiro,
                                                            mov.total_cartao,
                                                            ROUND(tro.valor_vale,2) valor_vale,
                                                            truncate(ven.VAL_TOTPRODU,2) VAL_TOTPRODU,
                                                            truncate(ven.VAL_TOTVENDA,2) VAL_TOTVENDA,
                                                            truncate(ven.VAL_DESCONTO,2) VAL_DESCONTO,
                                                            cli.NUM_CARTAO,
                                                            mov.operacao,
                                                            ven.VAL_RESGATE,
                                                            pag.DES_FORMAPA,
                                                            ven.COD_UNIVEND
                                            FROM linx_movimento mov
                                            inner join linxmovimentotrocas tro ON tro.identificador=mov.identificador
                                            inner JOIN vendas ven ON cod_vendapdv=mov.identificador                                  
                                            inner JOIN clientes cli ON cli.COD_CLIENTE=ven.COD_CLIENTE
                                            inner JOIN usuarios usu ON usu.COD_USUARIO=ven.COD_VENDEDOR
                                            INNER JOIN formapagamento pag ON pag.COD_FORMAPA=ven.COD_FORMAPA
                                              WHERE  ven.cod_empresa=".$rsconfig['COD_EMPRESA']." 
                                            GROUP BY mov.identificador;"; 
                            $rwextorno=  mysqli_query(connTemp($rsconfig['COD_EMPRESA'],''), $sqlextorno);
                            if($rwextorno->num_rows > '0'){
                                $enviaemail='1';
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
                                                                                                    <idloja>'.$rsestono[COD_UNIVEND].'</idloja>
                                                                                                    <idcliente>'.$dadoslogin[4].'</idcliente>
                                                                                                   <idmaquina>EstornoOffline</idmaquina>    
                                                                                          </dadosLogin>
                                                                                   </fid:EstornaVenda>
                                                                            </soapenv:Body>
                                                                         </soapenv:Envelope>';
                                             $retornoestrno= estornovenda($estorno);
                                             
                                             // echo $estorno.'/r/n <br>';   
                                           
                                             $tdEstorno.="<tr>
                                                            <td>troca</td>    
                                                             <td>$rsestono[NUM_CARTAO]</td>
                                                            <td>$dadoslogin[4]</td>
                                                            <td>$dadoslogin[2]</td>
                                                            <td>$rwuni[NOM_FANTASI]</td>    
                                                            <td>".date('d/m/Y H:m:s')."</td>
                                                            <td>".$rsestono[identificador]."</td>     
                                                        </tr>";    
                                            
                                        $sqlitem="
                                            SELECT 
                                                       itemtmp.identificador,
                                                       iten.COD_ITEMEXT COD_ITEMEXT,
                                                       prod.DES_PRODUTO,
                                                       prod.COD_EXTERNO cod_produto,
                                                       truncate(iten.QTD_PRODUTO,0) quantidade,
                                                       truncate(iten.VAL_LIQUIDO,2) VAL_LIQUIDO_PROD,
                                                       truncate(iten.VAL_TOTITEM,2) VAL_TOTITEM_PROD,
                                                       truncate(iten.VAL_DESCONTO,2) VAL_DESCONTO_PROD,
                                                       itemtmp.cod_barra,                                                  
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
                                                       param12.des_parametro  des_parametro12
                                                    FROM (
                                                             SELECT 
                                                                               ven.COD_VENDA,
                                                                               mov.identificador,
                                                                               '' COD_ITEMEXT,
                                                                               '' DES_PRODUTO,
                                                                               mov.cod_produto,
                                                                               mov.quantidade,
                                                                               '' VAL_LIQUIDO_PROD,
                                                                               '' VAL_TOTITEM_PROD,
                                                                               '' VAL_DESCONTO_PROD,
                                                                               mov.cod_barra,                                                  
                                                                               '' des_parametro1,
                                                                               '' des_parametro2,
                                                                               '' des_parametro3,
                                                                               '' des_parametro4,
                                                                               '' des_parametro5,
                                                                               '' des_parametro6,
                                                                               '' des_parametro7,
                                                                               '' des_parametro8,
                                                                               '' des_parametro9,
                                                                               '' des_parametro10,
                                                                               '' des_parametro11,
                                                                               '' des_parametro12
                                                                               FROM linx_movimento mov
                                                                               inner join linxmovimentotrocas tro ON tro.identificador=mov.identificador
                                                                               INNER JOIN vendas ven ON cod_vendapdv=mov.identificador                                         
                                                                                WHERE  ven.cod_empresa=".$rsconfig['COD_EMPRESA']." and mov.identificador ='$rsestono[identificador]'
                                                                               GROUP BY mov.identificador
                                               )itemtmp                       
                                                INNER JOIN itemvenda iten ON  itemtmp.COD_VENDA=iten.COD_VENDA
                                                INNER JOIN produtocliente prod ON prod.COD_EXTERNO=iten.COD_EXTERNO  
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
                                                ORDER BY iten.COD_ITEMVEN asc";
                                        $rwiten= mysqli_query(connTemp($rsconfig['COD_EMPRESA'],''), $sqlitem);                             
                                        while ($rsiten= mysqli_fetch_assoc($rwiten))
                                        {        
                                             $vendaitem[]=array(
                                                                 'id_item'=>$rsiten[COD_ITEMEXT],
                                                                 'produto'=>$rsiten[DES_PRODUTO],
                                                                 'codigoproduto'=>$rsiten[cod_produto],
                                                                 'quantidade'=>$rsiten[quantidade],
                                                                 'valorbruto'=>$rsiten[VAL_LIQUIDO_PROD],
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
                                                                                                'cod_univend'=>$rsestono[COD_UNIVEND],
                                                                                                'vendaitem'=> $vendaitem                                                                                            
                                                                                 );               
                                     $vendaitem=ARRAY(); 

                                   // array_push($ARRAYVENDAPROD, $vendaitem);
                                       /////////////////////////////////////////////////////////////////////////////////////////
                                }  

                            }

                            //execta e monta o xml de venda 

                          foreach ($ARRAYVENDAPROD[venda] as $keyvenda => $dadosvenda) {   
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
                            //===================================inserir vendedor ou atendente se nao existir
                            $vendedor='<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:fid="fidelidade">
                                <soapenv:Header/>
                                <soapenv:Body>
                                   <fid:InsereVendedor>
                                      <Cod_Atendente></Cod_Atendente>
                                      <Nome_atendente></Nome_atendente>
                                      <Cod_vendedor>'.$dadosvenda['codvendedor'].'</Cod_vendedor>
                                      <Nome_vendedor>'.$dadosvenda['nomevendedor'].'</Nome_vendedor>
                                    <dadosLogin>
                                            <login>'.$dadoslogin[0].'</login>
                                            <senha>'.$dadoslogin[1].'</senha>
                                            <idloja>'.$dadosvenda[cod_univend].'</idloja>
                                            <idcliente>'.$dadoslogin[4].'</idcliente>
                                            <codvendedor>'.$dadosvenda['codvendedor'].'</codvendedor>
                                            <nomevendedor>'.$dadosvenda['nomevendedor'].'</nomevendedor>
                                          <idmaquina>EstornoOffline</idmaquina>    
                                    </dadosLogin>
                                   </fid:InsereVendedor>
                                </soapenv:Body>
                             </soapenv:Envelope>';
                                  fnCadvendedor($vendedor);  
                            $tdEstorno.="<tr>
                                                            <td>inserçao de vendedor_atendente</td>  
                                                            <td>$rsestonoC[Identcliente]</td>
                                                            <td>$dadoslogin[4]</td>
                                                            <td>$dadoslogin[2]</td>
                                                            <td>$rwuni[NOM_FANTASI]</td>    
                                                            <td>".date('d/m/Y H:m:s')."</td>
                                                            <td>".$rsestonoC[identificador]."</td>     
                                                        </tr>";        
                            //===========================================
                            
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
                                                                                                <idloja>'.$dadosvenda[cod_univend].'</idloja>
                                                                                                <idcliente>'.$dadoslogin[4].'</idcliente>
                                                                                                <codvendedor>'.$dadosvenda['codvendedor'].'</codvendedor>
                                                                                                <nomevendedor>'.$dadosvenda['nomevendedor'].'</nomevendedor>
                                                                                              <idmaquina>EstornoOffline</idmaquina>    
                                                                                     </dadosLogin>
                                                                                      </fid:InsereVenda>
                                                                               </soapenv:Body>
                                                                            </soapenv:Envelope>';
                             //   echo $vendaxml; 
                                    $retornvendaxml= estornovenda($vendaxml);  
                                       //deletar o estorno feito sobre as trocas
                                    
                                    $cod_ok_delet=$retornvendaxml[body][envelope][body][inserevendaresponse][inserevendaresponse][coderro];
                                    
                                    if($cod_ok_delet!='7')
                                    {    
                                        
                                        $SQLtrocas="DELETE FROM linxmovimentotrocas WHERE identificador='$dadosvenda[id_vendapdvOriginal]';
                                                    DELETE FROM linx_movimento WHERE identificador='$dadosvenda[id_vendapdvOriginal]';
                                                  ";
                                       mysqli_multi_query(connTemp($rsconfig['COD_EMPRESA'],''), $SQLtrocas);
                                    }else{
                                        echo $vendaxml.'<br>';
                                    }
                                   
                                    unset($vendaxml);
                                    unset($itm);
                                 
                                 
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
                                                     mov.cancelado,
                                                     mov.CPF Identcliente,
                                                     ven.COD_UNIVEND
                                     FROM linx_movimento mov
                                      inner JOIN vendas ven ON ven.COD_VENDAPDV=mov.identificador
                                     WHERE  
                                            mov.cancelado='S'                                             
                                      group by mov.identificador order by  mov.identificador asc;"; 
                            $rwextornoC= mysqli_query(connTemp($rsconfig['COD_EMPRESA'],''), $sqlextornocancelamento);

                            if($rwextornoC->num_rows > '0'){
                                while ($rsestonoC= mysqli_fetch_assoc($rwextornoC))
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
                                                                                                   <idloja>'.$rsestonoC[COD_UNIVEND].'</idloja>
                                                                                                   <idcliente>'.$dadoslogin[4].'</idcliente>
                                                                                                  <idmaquina>CancelamentoOffline</idmaquina>    
                                                                                         </dadosLogin>
                                                                                  </fid:EstornaVenda>
                                                                           </soapenv:Body>
                                                                        </soapenv:Envelope>';
                                            $retornoestrno= estornovenda($estornoC);  
                                            $SQLCancelados=" DELETE FROM linx_movimento WHERE cnpj_emp=$rsestonoC[cnpj_emp] AND identificador='$rsestonoC[identificador]';";
                                            mysqli_query(connTemp($rsconfig['COD_EMPRESA'],''), $SQLCancelados);
                                       $tdEstorno.="<tr>
                                                            <td>CANCELAMENTO</td>  
                                                            <td>$rsestonoC[Identcliente]</td>
                                                            <td>$dadoslogin[4]</td>
                                                            <td>$dadoslogin[2]</td>
                                                            <td>$rwuni[NOM_FANTASI]</td>    
                                                            <td>".date('d/m/Y H:m:s')."</td>
                                                            <td>".$rsestonoC[identificador]."</td>     
                                                        </tr>";        
                                } 
                            }
                        echo 'COD_UNIVEND:'.$rwuni[COD_UNIVEND].'<br>';
                            //==============================================fim===================================================
                        ob_end_flush();
                        ob_flush();
                        flush();
                        

                } 
                if($enviaemail=='1')
                {    
                    $emailDestino = array('email1'=>'diogo_tank@hotmail.com',
                                          'email5'=>'coordenacaoti@markafidelizacao.com.br;');
                    fnsacmail(  $emailDestino,
                                "Carga da linx",
                                "<html>
                                            <head>
                                                <title>Carga Linx</title>
                                                <meta charset='UTF-8'>
                                                <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                                            </head>
                                            <body>
                                                <table border='1'>
                                                   <tr>
                                                   <th>tipo_rotina</th>
                                                   <th>CLIENTE</th>
                                                    <th>COD_EMPRESA</th>
                                                    <th>COD_UNIVEND</th>
                                                    <th>NOME_UNIDADE</th>
                                                    <th>data execucao</th>
                                                    <th>PDV_EXTORNADO</th>
                                                  </tr>
                                                  $tdEstorno
                                                </table>    
                                            </body>
                                        </html>
                                 ",
                                "Carga da linx",
                                "Carga da linx",
                                $connAdm->connAdm(),
                                connTemp($rsconfig['COD_EMPRESA'],''),"3");
                }else{
                $emailDestino = array('email1'=>'diogo_tank@hotmail.com',
                                          'email5'=>'coordenacaoti@markafidelizacao.com.br;');
                    fnsacmail(  $emailDestino,
                                "Carga da linx",
                                "<html>
                                            <head>
                                                <title>Carga Linx</title>
                                                <meta charset='UTF-8'>
                                                <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                                            </head>
                                            <body>
                                                <table border='1'>
                                                   <tr>
                                                    <th>Processo executado</th>
                                                  </tr>
                                                    <tr>
                                                    <td>Exeutou a rotina mas não processou vendas ou estornos.</td>     
                                                    </tr>
                                                </table>    
                                            </body>
                                        </html>
                                 ",
                                "Carga da linx",
                                "Carga da linx",
                                $connAdm->connAdm(),
                                connTemp($rsconfig['COD_EMPRESA'],''),"3");
                    
                }
                echo 'FIM DO PRIMEIRO PERIODO.<br>';
           
    }
 ?>