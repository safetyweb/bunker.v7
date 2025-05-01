<?php
include_once '../_system/_functionsMain.php';

$sql="SELECT TOKEN,VERSAO_SISTEMA,p.DAT_ALTERAC,c.NOM_CLIENTE,p.COD_CLIENTE from cliente_push p
INNER JOIN clientes c ON c.COD_CLIENTE=p.COD_CLIENTE AND  c.num_cgcecpf IN 
(93933312415,
31508584885,
28626916825,
40332253821,
42147177830,
41159453896,
11224696832,
04026226859,
26097913800,
45747283880,
16370808830,
01734200014,
35196685804,
00596694547)
";
$query=mysqli_query(connTemp(19, ''), $sql);
while($qr=mysqli_fetch_assoc($query))
{
echo '<pre>';
print_r($qr);
echo '</pre>';
}