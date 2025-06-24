<?php
include '../../_system/_functionsMain.php';
//and cod_empresa=77 and cod_empresa in (77,292)
if ($_GET['dtexec'] != '') {
  $dat_inicialparam = $_GET['dtexec'] . '-01';
  $dat_inicial = date("Y-m-d", strtotime("-1 month", strtotime($dat_inicialparam)));
  $dat_final = date("Y-m-t", strtotime($dat_inicial));
} else {
  $dat_inicialparam = date('Y-m') . '-01';
  $dat_inicial = date("Y-m-d", strtotime("-1 month", strtotime($dat_inicialparam)));
  $dat_final = date("Y-m-t", strtotime($dat_inicial));
}
if ($_GET['emp'] != '') {
  $empresa = "and e.cod_empresa=$_GET[emp]";
}
echo $dat_inicialparam . '<br>' . $dat_inicial . '<br>' . $dat_final;



$conadmmysql = $connAdm->connAdm();
$sqlempresa = "SELECT e.COD_EMPRESA,e.NOM_FANTASI,e.COD_SISTEMAS,e.COD_SEGMENT FROM empresas e 
                INNER JOIN tab_database t ON t.cod_empresa=e.COD_EMPRESA
                WHERE e.log_ativo='S' $empresa";

$rwempresa = mysqli_query($conadmmysql, $sqlempresa);
while ($rsempresa = mysqli_fetch_assoc($rwempresa)) {

  $connboard = $Cdashboard->connUser();
  $contemp = connTemp($rsempresa['COD_EMPRESA'], '');

  $limparepro = "DELETE FROM dash_consultor WHERE DATA_INICIAL='$dat_inicial' and COD_EMPRESA=$rsempresa[COD_EMPRESA];";
  mysqli_query($connboard, $limparepro);

  $sqldados = "SELECT  
                       COD_EMPRESA,
                       DATE_FORMAT('$dat_inicial','%Y-%m') ANO_MES,
                       CAST('$dat_inicial' AS DATE) DATA_INICIAL,
                       CAST('$dat_final' AS DATE) DATA_FINAL,
                       (SELECT COUNT(*) QTD_TOT_FILTRO FROM clientes WHERE 	date(DAT_CADASTR) between '$dat_inicial' and '$dat_final' AND COD_EMPRESA=$rsempresa[COD_EMPRESA]) QTD_CLIENTE_PERIODO,
                       (SELECT COUNT(*) QTD_TOT_FILTRO FROM clientes WHERE 	date(DAT_CADASTR) < '$dat_inicial' AND COD_EMPRESA=$rsempresa[COD_EMPRESA]) QTD_TOT_CLIENTE, 
                            SUM(QT_TOTAL) QT_TOTAL,
                            SUM(QT_AVULSA) QT_AVULSA,	
                            SUM(QT_FIDELIZA) QT_FIDELIZA,	
                            SUM(QTD_COMPRA_MASC) QTD_COMPRA_MASC,
                            SUM(QTD_COMPRA_FEMI) QTD_COMPRA_FEMI,
                            truncate(SUM(VAL_COMPRA_MASC),2) VAL_COMPRA_MASC,
                            truncate(SUM(VAL_COMPRA_FEMI),2) VAL_COMPRA_FEMI,
                       SUM(QTD_CLIENTE_FIDELIZ) QTD_CLIENTE_FIDELIZ,
                       truncate(SUM(PC_FIDELIZADO),2) PC_FIDELIZADO,	
                            truncate(SUM(VAL_TOTVENDA),2) VAL_TOTVENDA, 
                            truncate(SUM(VAL_TOTAL_FIDELI),2) VAL_TOTAL_FIDELI,
			    truncate(SUM(VAL_TOTAL_AV),2) VAL_TOTAL_AV,
                            truncate(SUM(VAL_TOTPRODU),2) VAL_TOTPRODU,	 
                            truncate(SUM(VAL_TOTPRODU_FID),2) VAL_TOTPRODU_FID,
                            SUM(QT_TICKET) QT_TICKET,
                            truncate(SUM(VAL_TICKET),2) VAL_TICKET,	
                       SUM(QTD_RESGATE) QTD_RESGATE,
                       truncate(SUM(VAL_RESGATE),2) VAL_RESGATE,
                            SUM(QTD_CLIENTE_RESGATE) QTD_CLIENTE_RESGATE,
                            SUM(QTD_CLIENTE_GERADO) QTD_CLIENTE_GERADO,
                            SUM(QTD_CREDITO_GERADO) QTD_CREDITO_GERADO,
                            truncate(SUM(VAL_CREDITOS_GERADO),2) VAL_CREDITOS_GERADO,
                            truncate(SUM(VAL_VINCULADO1),2) VAL_VINCULADO1,
                            (SELECT case when DAT_PRODUCAO IS NOT NULL then  DAT_PRODUCAO ELSE '0000-00-00' end from empresas where COD_EMPRESA=$rsempresa[COD_EMPRESA]) DAT_PROD_EMPRESA,
                            truncate(SUM(VAL_CRED_EXPIRADO),2) VAL_CRED_EXPIRADO,
                            truncate(SUM(QTD_EXPIRA_SALDO),2) QTD_EXPIRA_SALDO,
                            truncate(SUM(QTD_VINCULADO1),2) QTD_VINCULADO1,
                            TRUNCATE(SUM(VAL_FREQUENCIA),2) VAL_FREQUENCIA


                    FROM (
                                                            SELECT DISTINCT

                                                                A.COD_EMPRESA, 
                                                                     SUM(QTD_venda) QT_TOTAL, 
                                                                     SUM(CASE WHEN A.COD_AVULSO= 1 THEN A.QTD_VENDA ELSE 0 END) QT_AVULSA, 
                                                                     SUM(CASE WHEN A.COD_AVULSO= 2 THEN A.QTD_VENDA ELSE 0 END) QT_FIDELIZA, 
                                                                     SUM(CASE WHEN A.COD_AVULSO= 2 AND B.COD_SEXOPES=1 THEN A.QTD_VENDA ELSE 0 END) QTD_COMPRA_MASC, 
                                                                     SUM(CASE WHEN A.COD_AVULSO= 2 AND B.COD_SEXOPES=2 THEN A.QTD_VENDA ELSE 0 END) QTD_COMPRA_FEMI, 
                                                                     SUM(CASE WHEN A.COD_AVULSO= 2 AND B.COD_SEXOPES=1 THEN A.VAL_TOTVENDA  ELSE 0 END) VAL_COMPRA_MASC, 
                                                                     SUM(CASE WHEN A.COD_AVULSO= 2 AND B.COD_SEXOPES=2 THEN A.VAL_TOTVENDA ELSE 0 END) VAL_COMPRA_FEMI, 
                                                                     COUNT(DISTINCT CASE WHEN A.COD_AVULSO= 2 THEN B.COD_CLIENTE ELSE null END) QTD_CLIENTE_FIDELIZ, 
                                                                     (SUM(CASE WHEN A.COD_AVULSO= 2 THEN A.QTD_VENDA ELSE 0 END)/ SUM(QTD_venda)) * 100 PC_FIDELIZADO, 
                                                                     SUM(VAL_TOTVENDA) AS VAL_TOTVENDA, 
                                                                     SUM(CASE WHEN A.COD_AVULSO= 2 THEN A.VAL_TOTVENDA ELSE 0 END) VAL_TOTAL_FIDELI, 
                                                                     SUM(CASE WHEN A.COD_AVULSO=1 THEN A.VAL_TOTVENDA ELSE 0 END) VAL_TOTAL_AV, 
                                                                     SUM(VAL_TOTPRODU) AS VAL_TOTPRODU, 
                                                                     SUM(CASE WHEN A.COD_AVULSO= 2 THEN A.VAL_TOTPRODU ELSE 0 END) VAL_TOTPRODU_FID, 
                                                                     SUM(CASE WHEN A.LOG_TICKET='S'THEN QTD_VENDA ELSE 0 END)QT_TICKET, 
                                                                     SUM(CASE WHEN A.LOG_TICKET='S'THEN VAL_TOTVENDA ELSE 0 END) VAL_TICKET,
                                                                    '0' QTD_RESGATE, 
                                                                    '0' VAL_RESGATE, 
                                                                    '0' QTD_CLIENTE_RESGATE, 
                                                                    '0' QTD_CLIENTE_GERADO, 
                                                                    '0' QTD_CREDITO_GERADO, 
                                                                    '0' VAL_CREDITOS_GERADO, 
                                                                    '0' VAL_VINCULADO1,
                                                                    '0' VAL_CRED_EXPIRADO,
								    '0' QTD_EXPIRA_SALDO,
                                                                    '0' QTD_VINCULADO1,
                                                                   (SUM(CASE WHEN A.COD_AVULSO= 2 THEN A.QTD_VENDA ELSE 0 END) / COUNT(DISTINCT CASE WHEN A.COD_AVULSO= 2 THEN B.COD_CLIENTE ELSE NULL END))/(SELECT COUNT(COD_UNIVEND) FROM unidadevenda WHERE COD_EMPRESA=$rsempresa[COD_EMPRESA] ) VAL_FREQUENCIA

                                                            FROM VENDAS A
                                                            left JOIN CLIENTES B ON B.COD_CLIENTE = A.COD_CLIENTE
                                                            WHERE A.COD_STATUSCRED IN(0,1,2,3,4,5,7,8,9) AND DATE(A.DAT_CADASTR_WS) BETWEEN '$dat_inicial' AND '$dat_final' 
                                                            AND A.COD_EMPRESA=$rsempresa[COD_EMPRESA]
                                                            GROUP BY A.COD_UNIVEND
                                                                    union	ALL
                                                     
                                                            SELECT 	
                                                                 A.COD_EMPRESA, 
                                                                     '0' QT_TOTAL, 
                                                                     '0' QT_AVULSA, 
                                                                     '0' QT_FIDELIZA, 
                                                                     '0' QTD_COMPRA_MASC, 
                                                                     '0' QTD_COMPRA_FEMI, 
                                                                     '0' VAL_COMPRA_MASC, 
                                                                     '0' VAL_COMPRA_FEMI, 
                                                                     '0' QTD_CLIENTE_FIDELIZ, 
                                                                     '0' PC_FIDELIZADO,
                                                                     '0' VAL_TOTVENDA, 
                                                                     '0' VAL_TOTAL_FIDELI, 
								     '0' VAL_TOTAL_AV,
                                                                     '0' VAL_TOTPRODU, 
                                                                     '0' VAL_TOTPRODU_FID, 
                                                                     '0' QT_TICKET, 
                                                                     '0' VAL_TICKET,	  
                                                                    COUNT(CASE WHEN TIP_CREDITO ='D' THEN 1 END) AS QTD_RESGATE, 
                                                                    IFNULL(SUM(CASE WHEN TIP_CREDITO ='D' THEN VAL_CREDITO END),0) AS VAL_RESGATE, 
                                                                    COUNT(DISTINCT CASE WHEN TIP_CREDITO ='D' THEN A.COD_CLIENTE END) AS QTD_CLIENTE_RESGATE, 
                                                                    IFNULL(COUNT(DISTINCT CASE WHEN TIP_CREDITO ='C' THEN A.COD_CLIENTE END),0) AS QTD_CLIENTE_GERADO, 
                                                                    COUNT(CASE WHEN TIP_CREDITO ='C' THEN 1 END) AS QTD_CREDITO_GERADO, 
                                                                    IFNULL(SUM(CASE WHEN TIP_CREDITO ='C' THEN VAL_CREDITO END),0) AS VAL_CREDITOS_GERADO, 
                                                                    IFNULL(SUM(CASE WHEN TIP_CREDITO ='D' THEN VAL_VINCULADO END),0) AS VAL_VINCULADO1,
                                                                  (SELECT SUM(val_saldo) FROM creditosdebitos
                                                                        where  TIP_CREDITO ='C'  AND 
                                                                        date(dat_expira) between '$dat_inicial' AND '$dat_final' AND
                                                                        cod_empresa=$rsempresa[COD_EMPRESA] AND COD_STATUSCRED IN(4) AND log_expira = 'S') VAL_CRED_EXPIRADO,
                                                                            
                                                                   (SELECT count(1) FROM creditosdebitos
                                                                        where  TIP_CREDITO ='C'  AND 
                                                                        date(dat_expira) between '$dat_inicial' AND '$dat_final' AND
                                                                        cod_empresa=$rsempresa[COD_EMPRESA] AND COD_STATUSCRED IN(4) AND log_expira = 'S')	 QTD_EXPIRA_SALDO,
                                                                    IFNULL(COUNT(DISTINCT CASE WHEN TIP_CREDITO ='D' THEN COD_VENDA END),0) AS QTD_VINCULADO1,
                                                                    '0' VAL_FREQUENCIA
                                                            FROM CREDITOSDEBITOS A
                                                            WHERE A.COD_STATUSCRED IN(0,1,2,3,4,5,7,8,9) AND 
                                                                DATE(A.DAT_CADASTR) BETWEEN '$dat_inicial' AND '$dat_final'
                                                                AND A.COD_EMPRESA=$rsempresa[COD_EMPRESA]
                                                            	GROUP BY A.COD_UNIVEND
                                                            ORDER BY QTD_RESGATE desc
                    )tmpvendadas GROUP BY COD_EMPRESA 
             ";

  /* echo $sqldados;
   exit();*/

  $rwdash = mysqli_query($contemp, $sqldados);
  while ($camposfild = mysqli_fetch_field($rwdash)) {

    $fildscampos .= $camposfild->name . ',';
  }
  $fildscampos = rtrim($fildscampos, ',');
  $dadosinserir = mysqli_fetch_assoc($rwdash);

  if (isset($dadosinserir['COD_EMPRESA'])) {
    $dados = "'" . implode("','", $dadosinserir) . "'";
    $insert = "INSERT INTO dash_consultor ($fildscampos)values($dados)";

    $insdash = mysqli_query($connboard, $insert);
    if (!$insdash) {
      echo '<br>' . $insert . '<br>';
    }
    //capturar ultimo ID gerado
    $COD_LOG = mysqli_insert_id($connboard);
    //selecionar a lista para atualização
    $LOGACESSO = "SELECT
					        LG.COD_EMPRESA,
					        COUNT(LG.ID_CESSO) QTD_ACESSO,
					         max(LG.DATA_ACESSO) DAT_ULT_ACESSO,
					(SELECT COUNT(1) FROM usuarios 
					  WHERE cod_empresa =$rsempresa[COD_EMPRESA] and 
					        COD_TPUSUARIO IN ('9','16','6','15','1','3')
				    ) QTD_USUARIO
					FROM log_acesso LG
					WHERE LG.cod_empresa=$rsempresa[COD_EMPRESA] AND date(LG.DATA_ACESSO) BETWEEN '$dat_inicial' AND '$dat_final';
			";
    $rwlogacesso = mysqli_fetch_assoc(mysqli_query($conadmmysql, $LOGACESSO));
    if (isset($rwlogacesso['DAT_ULT_ACESSO'])) {
      //alterar a quantidade de log X acesso no sistema
      $updatelogacess = "UPDATE dash_consultor 
                                                                    SET 
                                                                          QTD_ACESSO='$rwlogacesso[QTD_ACESSO]', 
                                                                          DAT_ULT_ACESSO='$rwlogacesso[DAT_ULT_ACESSO]',
                                                                          QTD_USUARIO='$rwlogacesso[QTD_USUARIO]' 
								WHERE  ID=$COD_LOG;";
      $rwupdatelogacess = mysqli_query($connboard, $updatelogacess);
      if (!$rwupdatelogacess) {
        echo '<br>' . $updatelogacess . '<br>';
      }
    }
    //LGPD_POR EMPRESA
    $lgpd = "SELECT LOG_LGPD FROM controle_termo WHERE LOG_LGPD = 'S' and cod_empresa=$rsempresa[COD_EMPRESA]";
    $rwlgpd = mysqli_fetch_assoc(mysqli_query($contemp, $lgpd));
    if (isset($rwlgpd['LOG_LGPD'])) {
      $iploglgpd = "UPDATE dash_consultor SET LGPD='$rwlgpd[LOG_LGPD]' WHERE  ID=$COD_LOG;";
      $rwiploglgpd = mysqli_query($connboard, $iploglgpd);
      if (!$rwiploglgpd) {
        echo '<br>' . $iploglgpd . '<br>';
      }
    }
    //produtos do Ticke de ofertas
    $vrificatemplate = "SELECT COD_EMPRESA FROM TEMPLATE WHERE cod_empresa = $rsempresa[COD_EMPRESA] AND LOG_ATIVO='S' ORDER BY NOM_TEMPLATE";
    $RWvrificatemplate = mysqli_query($contemp, $vrificatemplate);
    if ($RWvrificatemplate->num_rows > 0) {
      //contador de produtos do ticket
      $qtdprod = "SELECT  COUNT(1) QTD_PRODTKT FROM produtotkt
                                                                            WHERE LOG_ATIVOTK='S' AND 
                                                                            date(DAT_INIPTKT) >= '$dat_inicial' AND
                                                                        cod_empresa=$rsempresa[COD_EMPRESA]";
      $rwqtdprod = mysqli_fetch_assoc(mysqli_query($contemp, $qtdprod));
      if ($rwqtdprod['QTD_PRODTKT'] > 0) {
        $upprodtkt = "UPDATE dash_consultor SET QTD_PRODTKT='$rwqtdprod[QTD_PRODTKT]' WHERE  ID=$COD_LOG";
        $rwupprodtkt = mysqli_query($connboard, $upprodtkt);
        if (!$rwupprodtkt) {
          echo '<br>' . $upprodtkt . '<br>';
        }
      }
    }
    //quantidade de envio comunicação
    $qtd_disparo = "SELECT 
                                (SELECT COUNT(1) FROM sms_lista_ret where cod_empresa=$rsempresa[COD_EMPRESA] AND date(DAT_CADASTR) BETWEEN '$dat_inicial' AND '$dat_final') QTD_COMUNICACAO_SMS,
                                (SELECT  count(COD_LISTA) FROM email_lista_ret where cod_empresa=$rsempresa[COD_EMPRESA] AND date(DAT_CADASTR) BETWEEN '$dat_inicial' AND '$dat_final') QTD_COMUNICACAO_EMAIL
		";
    $RWqtd_disparo = mysqli_fetch_assoc(mysqli_query($contemp, $qtd_disparo));
    $upcomunica = "UPDATE dash_consultor 
                                            SET 
                                                QTD_COMUNICACAO_SMS='$RWqtd_disparo[QTD_COMUNICACAO_SMS]' ,
                                                QTD_COMUNICACAO_EMAIL='$RWqtd_disparo[QTD_COMUNICACAO_EMAIL]'
		 WHERE  ID=$COD_LOG";
    $rwcomunica = mysqli_query($connboard, $upcomunica);
    if (!$rwcomunica) {
      echo '<br>' . $upcomunica . '<br>';
    }
    //QUANTIDADE DE DEBITOS EMAIL/SMS
    /*
		  canal.cod_tpcom =1 email
           canal.cod_tpcom= 2 sms 
		*/

    $SQLDEBITOSCOM = "SELECT 
							(SELECT 
							case when SUM(ROUND(pedido.QTD_PRODUTO,0)) is NOT NULL then SUM(ROUND(pedido.QTD_PRODUTO,0)) ELSE '0' end		
							FROM pedido_marka pedido 
							INNER JOIN produto_marka prod ON prod.COD_PRODUTO = pedido.COD_PRODUTO 
							INNER JOIN canal_comunicacao canal ON canal.COD_CANALCOM = prod.COD_CANALCOM
							INNER JOIN empresas emp ON emp.COD_EMPRESA = pedido.COD_EMPRESA 
							 WHERE pedido.COD_ORCAMENTO > 0 AND pedido.COD_EMPRESA = $rsempresa[COD_EMPRESA] AND 
                                                        pedido.TIP_LANCAMENTO='D' and
							date(pedido.DAT_CADASTR) BETWEEN '$dat_inicial' AND '$dat_final' AND canal.COD_TPCOM=1) QTD_DEBITOS_EMAIL,
							(SELECT 
							case when SUM(ROUND(pedido.QTD_PRODUTO,0)) is NOT NULL then SUM(ROUND(pedido.QTD_PRODUTO,0)) ELSE '0' end
							
                                                        FROM pedido_marka pedido 
							INNER JOIN produto_marka prod ON prod.COD_PRODUTO = pedido.COD_PRODUTO 
							INNER JOIN canal_comunicacao canal ON canal.COD_CANALCOM = prod.COD_CANALCOM
							INNER JOIN empresas emp ON emp.COD_EMPRESA = pedido.COD_EMPRESA 
							WHERE pedido.COD_ORCAMENTO > 0 AND pedido.COD_EMPRESA = $rsempresa[COD_EMPRESA] AND 
                                                         pedido.TIP_LANCAMENTO='D' and   
							date(pedido.DAT_CADASTR) BETWEEN '$dat_inicial' AND '$dat_final' AND canal.COD_TPCOM=2) QTD_DEBITOS_SMS
							";
    $RWDEBITOSCOM = mysqli_fetch_assoc(mysqli_query($conadmmysql, $SQLDEBITOSCOM));
    $upDEBITOSCOM = "UPDATE dash_consultor 
		                                 SET 
                                                        QTD_DEBITOS_EMAIL='$RWDEBITOSCOM[QTD_DEBITOS_EMAIL]',
                                                        QTD_DEBITOS_SMS='$RWDEBITOSCOM[QTD_DEBITOS_SMS]'
		 WHERE  ID=$COD_LOG";
    $rwDEBITOSCOM = mysqli_query($connboard, $upDEBITOSCOM);
    if (!$rwDEBITOSCOM) {
      echo '<br>' . $upDEBITOSCOM . '<br>';
    }

    //quantidade de itens 

    $qtd_ITENS = "
                                SELECT
                                       IFNULL(SUM(CASE WHEN A.COD_AVULSO= 1 THEN itm.QTD_PRODUTO ELSE 0 END),0) QTD_ITEM_AVULSA, 
                                       IFNULL(SUM(CASE WHEN A.COD_AVULSO= 2 THEN itm.QTD_PRODUTO ELSE 0 END),0) QTD_ITEM_FIDELIZA
                                  FROM VENDAS A
                                  INNER JOIN itemvenda itm ON itm.COD_VENDA = A.COD_VENDA
                                  WHERE A.COD_STATUSCRED IN(0,1,2,3,4,5,7,8,9) AND DATE(A.DAT_CADASTR) BETWEEN '$dat_inicial' AND '$dat_final' 
                                  AND A.COD_EMPRESA=$rsempresa[COD_EMPRESA] GROUP BY A.COD_EMPRESA
                             ";
    $RWqtd_itens = mysqli_fetch_assoc(mysqli_query($contemp, $qtd_ITENS));
    $upitens = "UPDATE dash_consultor 
                                            SET 
                                                QTD_ITEM_AVULSA='$RWqtd_itens[QTD_ITEM_AVULSA]' ,
                                                QTD_ITEM_FIDELIZA='$RWqtd_itens[QTD_ITEM_FIDELIZA]'
		 WHERE  ID=$COD_LOG";
    $rwitens = mysqli_query($connboard, $upitens);
    if (!$rwitens) {
      echo '<br>' . $upitens . '<br>';
    }





    //update in dados empresa
    $upempresadash = "UPDATE dash_consultor 
		                                 SET 
                                                NOM_FANTASI='$rsempresa[NOM_FANTASI]',
                                                COD_SISTEMAS='$rsempresa[COD_SISTEMAS]',
                                                COD_SEGMENT= '$rsempresa[COD_SEGMENT]'    
		             WHERE  ID=$COD_LOG";
    $rwDEBITOSCOM = mysqli_query($connboard, $upempresadash);
    if (!$rwDEBITOSCOM) {
      echo '<br>' . $upempresadash . '<br>';
    }
  }
  unset($fildscampos);
  unset($insert);

  mysqli_close($connboard);
}
