<?php
include './_system/_functionsMain.php';

$select='select * from teste_matriz';
$sqlcomand=mysqli_query(connTemp(7,''), $select);
$comandosql=mysqli_fetch_assoc($sqlcomand);
//rodar o comando.
$sqlexec=mysqli_query(connTemp(7,''), $comandosql['descricao']);
while($exec=mysqli_fetch_assoc($sqlexec))
{        
echo '<pre>';
print_r($exec);
echo '<pre>';
}

while($namecampos=mysqli_fetch_field($sqlexec))
{
    
    $arraycampos['campos'].= '"'.$namecampos->name.'",';
}
//segundo select 
$arraycampos1 = substr($arraycampos['campos'],0,-1);
echo '<pre>';
print_r($arraycampos);
echo '<pre>';
//buscando  os alias...
$descri2='SELECT * FROM teste_matriz_info WHERE descricao1 IN('.$arraycampos1.')';
$descrir2=mysqli_query(connTemp(7,''), $descri2);
while ($rs= mysqli_fetch_assoc($descrir2))
{
    echo '<pre>';
    print_r($rs);
    echo '<pre>';
}   
?>