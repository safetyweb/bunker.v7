<?php
include '../_system/_functionsMain.php';

$conadmin=$connAdm->connAdm ();
//where LOG_CONSEXT='S'
$busca_epre="select cod_empresa from empresas ";
$rs=mysqli_query($conadmin, $busca_epre);
while ($cod_empresa=mysqli_fetch_assoc($rs))
{
      ob_start();

    $atualiza="insert into LOG_CONSULTA
(
    ID,
	NUM_QTD,
	DATA_HORA,
	CPF,
	NOME,
	CONSULTA_ORIGINAL,
	CONSULTA_OUTRAS
	)
SELECT 
            Q.ID,
            count(Q.CPF) as qtd,
            Q.DATA_HORA,
            Q.CPF,           
            Q.NOME,
            Q.COD_EMPRESA as CONSULTA_ORIGINAL,
            l.COD_EMPRESA as  CONSULTA_OUTRAS
            from log_cpfqtd  Q
        inner join  log_cpf l on Q.CPF=l.CPF
         WHERE 
                Q.COD_EMPRESA=".$cod_empresa['cod_empresa']." and 
                Q.DATA_HORA between '2021-08-01 00:00:00' and
									'2021-08-31 23:59:59' 
												 
									                          
        group by Q.CPF,Q.COD_EMPRESA
          HAVING COUNT(Q.CPF) >=1";
   
    mysqli_query($conadmin, $atualiza);
    echo 'cod_empresa atualizada fim > '.$cod_empresa['cod_empresa'].'<br>';
    ob_end_flush();
ob_flush();
flush();
}    
mysqli_close($conadmin);
