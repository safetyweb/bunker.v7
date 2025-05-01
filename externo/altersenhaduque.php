<?php
include '../_system/_functionsMain.php';

function fngeraSenha($tamanho = 8, $maiusculas = true, $numeros = true, $simbolos = false)
{
    //$lmin = 'abcdefghijklmnopqrstuvwxyz';
    @$lmai = 'ABCDEFGHJKLMNPQRSTUVWXYZ';
    @$num = '123456789';
    @$simb = '@#$!';
    @$retorno = '';
    @$caracteres = '';
    @$caracteres .= $lmin;
    if ($maiusculas) $caracteres .= $lmai;
    if ($numeros) $caracteres .= $num;
    if ($simbolos) $caracteres .= $simb;
    $len = strlen($caracteres);
        for ($n = 1; $n <= $tamanho; $n++) {
                $rand = mt_rand(1, $len);
                $retorno .= $caracteres[$rand-1];
        }
    return $retorno;
}
$QTD_CHARTKN=8;

$cli="SELECT 
		COD_CLIENTE,DES_SENHAUS,DAT_ALTERAC
FROM CLIENTES
        WHERE COD_CLIENTE NOT IN( SELECT COD_CLIENTE FROM personaclassifica
                              WHERE COD_EMPRESA=19 AND 
                              COD_PERSONA=56) 
            AND COD_CLIENTE NOT IN (SELECT COD_CLIENTE FROM cont_pwd)";

$rwcli=mysqli_query(connTemp(19,''), $cli);

while ($row = mysqli_fetch_assoc($rwcli)) {
   /* echo '<pre>';
    print_r($row);
    echo '<pre>';
  */
   $insert="INSERT INTO cont_pwd (COD_CLIENTE, COD_EMPRESA, DES_SENHAUS, COD_USUCADA) VALUES (".$row['COD_CLIENTE'].", 19, '".$row['DES_SENHAUS']."',9999);";
   mysqli_query(connTemp(19,''), $insert);
  // echo "<br>".$insert."<br>";   
   $senha = fnEncode(fngeraSenha($QTD_CHARTKN, 'token', true, False));
   $up="UPDATE clientes set DES_SENHAUS='$senha' where cod_empresa=19 and COD_CLIENTE=".$row['COD_CLIENTE'];
   mysqli_query(connTemp(19,''), $up);
   // echo "<br>".$up."<br>";    
   
}
echo "OK";