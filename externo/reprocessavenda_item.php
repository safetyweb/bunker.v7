<?php
include '../_system/_functionsMain.php';

$conadmin=$connAdm->connAdm ();

$busca_epre="select cod_empresa from empresas where cod_empresa in('175','139')";
$rs=mysqli_query($conadmin, $busca_epre);
while ($cod_empresa=mysqli_fetch_assoc($rs))
{
	$contemporaria=connTemp($cod_empresa[cod_empresa],'');  
 //capturar vendas problematica	
   $buscavenda="SELECT cod_venda ,cod_vendapdv,c.NUM_CGCECPF,b.COD_EMPRESA emprevenda,c.COD_EMPRESA
						FROM vendas b, clientes c
						WHERE 
						b.cod_empresa=$cod_empresa[cod_empresa] and 
						b.COD_CLIENTE=c.COD_CLIENTE AND 
						b.val_totprodu+b.val_desconto <(SELECT SUM(a.val_totitem)FROM itemvenda a WHERE a.cod_venda=b.cod_venda) AND 
						b.dat_cadastr >'2021-03-05 9:25:00'";
						
	$rwbuscavenda=mysqli_query($contemporaria,   $buscavenda);
    while ($rsbuscavenda=mysqli_fetch_assoc($rwbuscavenda))
	{
		
		$sqlxml="SELECT * FROM origemvenda WHERE  
				cod_empresa='".$cod_empresa[cod_empresa]."' 
				AND cod_pdv='".$rsbuscavenda[cod_vendapdv]."'
				and DATE(dat_cadastr)='2021-03-05'
				ORDER BY COD_ORIGEM desc
				LIMIT 100";
		$rsxml=mysqli_fetch_assoc(mysqli_query($contemporaria,$sqlxml));
		if($rsxml['COD_PDV']!='')
		{
			/*$delete1="delete from vendas where cod_venda='".$rsbuscavenda[cod_venda]."' and cod_empresa='".$cod_empresa[cod_empresa]."';";
            mysqli_query($contemporaria,$delete1);
			$delete2="delete from itemvenda where cod_venda='".$rsbuscavenda[cod_venda]."' and cod_empresa='".$cod_empresa[cod_empresa]."';";
			mysqli_query($contemporaria,$delete2);
			$delete3="delete from creditosdebitos where cod_venda='".$rsbuscavenda[cod_venda]."' and cod_empresa='".$cod_empresa[cod_empresa]."';";
			mysqli_query($contemporaria,$delete3);
			
			
			$curl = curl_init();
            curl_setopt_array($curl, array(
              CURLOPT_URL => "http://ws.bunker.mk/bridge/ws1/fidelidadebridge.do?wsdl",
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => "",
              CURLOPT_MAXREDIRS => 0,
              CURLOPT_TIMEOUT => 0,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => "POST",
              CURLOPT_POSTFIELDS => $rsxml['DES_VENDA'],
              CURLOPT_HTTPHEADER => array(
                "cache-control: no-cache",
                "content-type: text/xml"
              ),
            ));

            $response = curl_exec($curl);
            $err = curl_error($curl);

            curl_close($curl);

            if ($err) {
             echo "cURL Error #:" . $err;


            } else {         
             
             echo $response;
			}
			*/
			
		}else{
			$sqlxml1="SELECT * FROM origemvenda WHERE  
						cod_empresa='".$cod_empresa[cod_empresa]."' and
						num_cgcecpf='".$rsbuscavenda[NUM_CGCECPF]."'		
						and DATE(dat_cadastr)='2021-03-05'
						ORDER BY COD_ORIGEM desc
						LIMIT 100";
		$rsxml1=mysqli_fetch_assoc(mysqli_query($contemporaria,$sqlxml1));
		 $doc = new DOMDocument();
          libxml_use_internal_errors(true);
          $doc->loadHTML($rsxml1[DES_VENDA]);
          libxml_clear_errors();
          $xml = $doc->saveXML($doc->documentElement);
          //$xml = simplexml_load_string($xml);
        $xml = simplexml_load_string($xml,'SimpleXMLElement',LIBXML_NOCDATA);
        $json = json_encode($xml);
        $array = json_decode($json,TRUE);
    
	      echo'<br>'.$array[body][envelope][body][inserirvenda][venda] [items][vendaitem].'<br>';
		
		
			echo 'CPD_PDV:'.$rsxml1['COD_PDV'].'<br>';
			echo 'cpf:'.$rsxml1['NUM_CGCECPF'].'<br>';
			echo 'SQL:'.$sqlxml1.'<br>';
		}
	}

	
	
}


?>