<?php

include "_system/_functionsMain.php";

echo fnDebug('true');

// don't forget to change the path!
require_once 'js/plugins/Spout/Autoloader/autoload.php';

use Box\Spout\Writer\WriterFactory;
use Box\Spout\Common\Type;

$writer = WriterFactory::create(Type::XLSX); // for XLSX files
//$writer = WriterFactory::create(Type::CSV); // for CSV files
//$writer = WriterFactory::create(Type::ODS); // for ODS files

//$writer->setFieldDelimiter(';');

$writer->openToFile('teste_geracao.xlsx'); // write data to a file or to a PHP stream
//$writer->openToBrowser($fileName); // stream data directly to the browser

fnEscreve('Horário de Inicio: '. date("h:i:sa")); 

$sql="select * from cg_promocao_orig";

fnEscreve($sql);
$arrayQuery = mysqli_query($connAdm->connAdm(),trim($sql)) or die(mysqli_error());


$array = array();
while($row = mysqli_fetch_assoc($arrayQuery)){
    $array[] = $row;
}

fnEscreve('Array criado');

$writer->addRow(array('sequence', 'nome', 'cartao',	'cpf', 'grupo',	'Loja',	'sexo',	'chave')); // add a row at a time
$writer->addRows($array); // add multiple rows at a time

fnEscreve('arquivo finalizado');

$writer->close();


fnEscreve('Horário de Fim: '. date("h:i:sa")); 
?>
