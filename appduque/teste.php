<?php
include '_system/codebar/BarcodeGeneratorHTML.php';
    //$generator = new \Picqer\Barcode\BarcodeGeneratorPNG();
    $generator = new \Picqer\Barcode\BarcodeGeneratorHTML;    
    echo $generator->getBarcode('diogo123', $generator::TYPE_CODE_39,3.3,60); 
    
?>
