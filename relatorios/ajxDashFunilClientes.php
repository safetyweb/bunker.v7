<?php
include '../_system/_functionsMain.php'; 

$opcao = $_GET['opcao'];
$valor_slider = $_POST['valor_slider'];
$soma_cliente = $_POST['soma_cliente'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    switch ($opcao) {
        case 'casual':

            $result = ($valor_slider * $soma_cliente) / 100;
            echo "<span style='margin-right: 15px;'>$valor_slider%</span>" . fnValor($result, 0);

        break;

        case 'frequente':

            $result = ($valor_slider * $soma_cliente) / 100;
            echo "<span style='margin-right: 15px;'>$valor_slider%</span>" . fnValor($result, 0);

        break;

        case 'fiel':

            $result = ($valor_slider * $soma_cliente) / 100;
            echo "<span style='margin-right: 15px;'>$valor_slider%</span>" . fnValor($result, 0);

        break;

        default:

            $result = ($valor_slider * $soma_cliente) / 100;
            echo "<span style='margin-right: 15px;'>$valor_slider%</span>" . fnValor($result, 0);
        break;

    }
}
?>