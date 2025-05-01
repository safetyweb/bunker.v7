<?php
include '../../_system/_functionsMain.php';
//capturar as empresa para executar a rotina de unidade referencia

    $conncliente= connTemp(77, '');
    $sqlclientes="SELECT 
			TMPVENDA_CLIENTES.COD_CLIENTE,
	   	        NOM_CLIENTE,
			COD_CATEGORIA,
			DAT_PRICOMPR,
			DAT_ULTCOMPR,
			VAL_TOTPRODU,
			VAL_RESGATE,
			VAL_DESCONTO,
			VAL_TOTVENDA,
			CRED.VAL_CREDITO,
                        CRED.DAT_REPROCE,
			CRED.COD_VENDA
		
	 
	FROM(
	
		     SELECT 
		      CLI.COD_CLIENTE,
				CLI.NOM_CLIENTE,
				CLI.COD_CATEGORIA,
				CLI.DAT_PRICOMPR,
				CLI.DAT_ULTCOMPR,
				GROUP_CONCAT( DISTINCT VEN.COD_VENDA SEPARATOR ',') COD_VENDAS ,
				SUM(VEN.VAL_TOTPRODU) VAL_TOTPRODU,
				SUM(VEN.VAL_RESGATE) VAL_RESGATE,
				SUM(VEN.VAL_DESCONTO) VAL_DESCONTO,
				SUM(VEN.VAL_TOTVENDA) VAL_TOTVENDA
				
				
				FROM vendas VEN 
				INNER JOIN clientes CLI ON CLI.COD_CLIENTE=VEN.COD_CLIENTE
				WHERE VEN.COD_EMPRESA=77 
				   AND VEN.COD_AVULSO=2
				   AND DATE(DAT_CADASTR_WS) BETWEEN '2021-10-26' AND '2022-10-26'
				--   AND VEN.COD_CLIENTE IN (17,22)
				  GROUP BY VEN.COD_CLIENTE 
			)TMPVENDA_CLIENTES   
			
	INNER JOIN creditosdebitos CRED ON CRED.COD_CLIENTE=TMPVENDA_CLIENTES.COD_CLIENTE AND CRED.TIP_CREDITO='C'
	WHERE CRED.DAT_REPROCE BETWEEN '2021-10-26' AND '2022-10-26'
       	ORDER BY TMPVENDA_CLIENTES.COD_CLIENTE ASC, CRED.DAT_REPROCE ASC;
        ";
    $rwclientes= mysqli_query($conncliente, $sqlclientes);
    $arquivo = fopen('../../externo/diogo/teste.csv', 'w',0);
    $CABECHALHO[]='NOM_CLIENTE';
    $CABECHALHO[]='COD_CLIENTE';
    $CABECHALHO[]='QTD_DIAS';
    $CABECHALHO[]='VAL_CREDITO';         
    $CABECHALHO[]='QTD_VENDAS';
    fputcsv ($arquivo,$CABECHALHO,';','"','\n');
    unset($CABECHALHO);
    
    $contador=1;
    $VAL_CREDITO='0.00';
    while ($rscliente= mysqli_fetch_assoc($rwclientes))
    {    
      if($VAL_CREDITO=='0.00')
      {    
       $DAT_REPROCE1=@$rscliente[DAT_REPROCE]; 
      }
        if(@$rscliente[COD_CLIENTE]!=@$COD_CLIENTE)
        {              
          @$VAL_CREDITO+=@$rscliente[VAL_CREDITO];
          if($VAL_CREDITO >= 5)
          {
            $NOM_CLIENTE=@$rscliente[NOM_CLIENTE];
            $DAT_REPROCE2=@$rscliente[DAT_REPROCE];
            $date1=date_create($DAT_REPROCE1);
            $date2=date_create($DAT_REPROCE2);
            $diff=date_diff($date1,$date2);
             echo 'QTD_DIAS = '.$diff->days.'<br>';
             echo 'VAL_CREDITO = '.$VAL_CREDITO.'<br>';
             $COD_CLIENTE=@$rscliente[COD_CLIENTE];
             echo 'COD_CLIENTE = '.$COD_CLIENTE.'<br>';
             echo 'Quantidade = '.$contador.'<br>';
             echo '<br>-------------------------------------------------------------<br>';
             $array[]=$NOM_CLIENTE;
             $array[]=$COD_CLIENTE;
             $array[]=$diff->days;
             $array[]=$VAL_CREDITO;
             $array[]=$contador; 
                         
             $VAL_CREDITO='0.00';
             $contador=0;
             $DAT_REPROCE2='';
             $DAT_REPROCE1='';
             $date1='';
             $date2=''; 
             $diff='';
          //   $array1 = array_map("utf8_decode", $array);
             fputcsv($arquivo, $array, ';', '"', '\n\r');
             unset($array);
             unset($array1);
          }    
             $contador++;
        } 
    } 
     fclose($arquivo);