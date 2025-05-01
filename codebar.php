<?php

include './_system/codebar/BarcodeGenerator.php';
include('./_system/codebar/BarcodeGeneratorPNG.php');
//include('./_system/codebar/BarcodeGeneratorSVG.php');
//include('./_system/codebar/BarcodeGeneratorJPG.php');
include('./_system/codebar/BarcodeGeneratorHTML.php');

echo "HTML<br>";
$generator = new Picqer\Barcode\BarcodeGeneratorHTML();
echo $generator->getBarcode('01734200014', $generator::TYPE_CODE_39,2,30);
echo "PNG<br>";
$generator = new \Picqer\Barcode\BarcodeGeneratorPNG();
echo '<img src="data:image/png;base64,' . base64_encode($generator->getBarcode('01734200014', $generator::TYPE_CODE_39,1,30)) . '">';   


?>
