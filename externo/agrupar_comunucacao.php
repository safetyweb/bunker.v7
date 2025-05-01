<?php
include '../_system/_functionsMain.php';
fndebug('true');
$conadmf=$connAdm->connAdm ();
$sqlempresa = "SELECT * FROM senhas_parceiro apar
				INNER JOIN parceiro_comunicacao par ON par.COD_PARCOMU=apar.COD_PARCOMU
				WHERE par.COD_TPCOM='2' AND apar.COD_PARCOMU='22' AND apar.LOG_ATIVO='S'
				";
$comunic=mysqli_query($conadmf, $sqlempresa);
while ($rwcomunic = mysqli_fetch_assoc($comunic)) {
 
   $listaretorigem="select * from sms_lista_ret where cod_empresa=".$rwcomunic['COD_EMPRESA']." and date(DAT_CADASTR) between '2024-04-01' and '2024-04-04' ";
   $rs_lista_origem=mysqli_query(connTemp($rwcomunic['COD_EMPRESA'],''), $listaretorigem);
   unset($columns);
   $columns = mysqli_fetch_fields($rs_lista_origem);
   unset($values_origem);
    foreach ($columns as $column) {
       $values_origem.= $column->name.',';
    }
   while ($rw_lista_origem= mysqli_fetch_assoc($rs_lista_origem)) {
        unset($escaped_values);
        $escaped_values = array_map(function ($value) {
            return !empty($value) ? "'" . addslashes($value) . "'" : "NULL";
        }, $rw_lista_origem);
         $values = implode(', ', $escaped_values);
         $valinsert='('.$values.')'. PHP_EOL;
         $insert="insert into sms_lista (".rtrim($values_origem,',').")value".$valinsert; 
         mysqli_query($Cdashboard->connAdm(), $insert);
    } 
    
   // $sms_lista=$Cdashboard->connAdm();
    
}
