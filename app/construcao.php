<?php 
include 'header.php';
$tituloPagina = "Em breve";
include "navegacao.php";

// if(!isset($_SESSION["usuario"])){
        
//    header('Location:app.do?key='.fnEncode($_SESSION["EMPRESA_COD"]));
   
// }

list($r_cor_backpag, $g_cor_backpag, $b_cor_backpag) = sscanf($cor_backpag, "#%02x%02x%02x");

if($r_cor_backpag > 50){
    $r = ($r_cor_backpag-50);
}else{
    $r =($r_cor_backpag+50);
    if($r_cor_backpag < 30){
        $r = $r_cor_backpag;
    }
}
if($g_cor_backpag > 50){
    $g = ($g_cor_backpag-50);
}else{
    $g =($g_cor_backpag+50);
    if($g_cor_backpag < 30){
        $g = $g_cor_backpag;
    }
}
if($b_cor_backpag > 50){
    $b = ($b_cor_backpag-50);
}else{
    $b =($b_cor_backpag+50);
    if($b_cor_backpag < 30){
        $b = $b_cor_backpag;
    }
}

if($r_cor_backpag <= 50 && $g_cor_backpag <= 50 && $b_cor_backpag <= 50){
    $r =($r_cor_backpag+40);
    $g =($g_cor_backpag+40);
    $b =($b_cor_backpag+40);
}
 

$dat_ini = date("Y-m-d",strtotime("-30 days"));


$sqlCli = "SELECT COD_CLIENTE, NOM_CLIENTE
            FROM CLIENTES 
            WHERE NUM_CGCECPF = $usuario 
            AND COD_EMPRESA = $cod_empresa";

$arrayCli = mysqli_query(connTemp($cod_empresa,''),$sqlCli);

$qrCli = mysqli_fetch_assoc($arrayCli);

$cod_cliente = $qrCli[COD_CLIENTE];
$nom_cliente = $qrCli[NOM_CLIENTE];
$nom_cliente = explode(" ", $nom_cliente);
$nom_cliente = ucfirst(strtolower($nom_cliente[0]));

$sqlCount = "SELECT 1
        FROM CREDITOSDEBITOS A      
        WHERE A.COD_CLIENTE = $cod_cliente
        AND A.COD_STATUSCRED <> 6
        AND A.COD_STATUS <> 15  
        AND A.COD_EMPRESA = $cod_empresa";

// echo($sql);

$arrayQueryCount = mysqli_query(connTemp($cod_empresa,''),$sqlCount);

// $sql = "SELECT 
//      A.TIP_CREDITO, 
//      A.DAT_CADASTR, 
//      A.VAL_CREDITO,
//      A.DAT_EXPIRA,
//      A.DES_STATUSCRED,
//      G.NOM_FANTASI
//      FROM CREDITOSDEBITOS A
//      LEFT JOIN VENDAS F ON F.COD_VENDA=A.COD_VENDA
//      and A.COD_VENDA > 0
//      LEFT JOIN WEBTOOLS.UNIDADEVENDA G ON G.COD_UNIVEND=A.COD_UNIVEND        
//      WHERE A.COD_CLIENTE = $cod_cliente
//      AND A.COD_STATUSCRED <> 6
//      AND A.COD_STATUS <> 15  
//      AND A.COD_EMPRESA = $cod_empresa
//      -- AND A.DAT_CADASTR >= '$dat_ini 00:00:01'                                         
//      ORDER BY A.DAT_CADASTR DESC
//      LIMIT 5";

// $sql = "CALL LISTA_WALLET($cod_cliente, '$cod_empresa', 0, 5)";
// // echo($sql);

// $arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);

$sql2 = "CALL total_wallet($cod_cliente, '$cod_empresa')";
                
//fnEscreve($sql);

$arrayQuery2 = mysqli_query(connTemp($cod_empresa,''),$sql2);
$qrBuscaTotais = mysqli_fetch_assoc($arrayQuery2);


if (isset($arrayQuery2)){
    
    $total_creditos = $qrBuscaTotais['TOTAL_CREDITOS'];
    $total_debitos = $qrBuscaTotais['TOTAL_DEBITOS'];
    $credito_disponivel = $qrBuscaTotais['CREDITO_DISPONIVEL'];
    $credito_aliberar = $qrBuscaTotais['CREDITO_ALIBERAR'];
    $credito_expirados = $qrBuscaTotais['CREDITO_EXPIRADOS'];
    $credito_bloqueado = $qrBuscaTotais['CREDITO_BLOQUEADO'];
}else{
    
    $total_creditos = 0;
    $total_debitos = 0;
    $credito_disponivel = 0;
    $credito_aliberar = 0;
    $credito_expirados = 0;
    $credito_bloqueado = 0;
    
}


?>

<style>
            
    body {
        background-color: <?=$cor_backpag?>;
    }
                
    .shadow{
        -webkit-box-shadow: 0px 0px 18px -2px rgba(<?=$r?>,<?=$g?>,<?=$b?>,0.8);
        -moz-box-shadow: 0px 0px 18px -2px rgba(<?=$r?>,<?=$g?>,<?=$b?>,0.8);
        box-shadow: 0px 0px 18px -2px rgba(<?=$r?>,<?=$g?>,<?=$b?>,0.8);
        width: 100%;
        border-radius: 5px;
    }

    .shadow2{
        -webkit-box-shadow: 0px 5px 8px 0px rgba(<?=$r?>,<?=$g?>,<?=$b?>,0.8);
        -moz-box-shadow: 0px 5px 8px 0px rgba(<?=$r?>,<?=$g?>,<?=$b?>,0.8);
        box-shadow: 0px 5px 8px 0px rgba(<?=$r?>,<?=$g?>,<?=$b?>,0.8);
        width: 100%;
        border-radius: 5px;
    }

    .reduzMargem{
        margin-bottom: 10px;
    }

</style>    

<div class="container">

    <div class="push30"></div>
    <div class="push30"></div>
    <div class="push30"></div>
        <form class="form-signin" method="post">
            <div class="row">
                <div class="col-xs-12 text-center">
                    <span class="fal fa-construction fa-3x"></span>
                    <div class="push10"></div>
                    <p style=" font-size: 21px;">Módulo em construção.</p>
                </div>
            </div>
        </form>
        </div>

    

</div> <!-- /container -->

<script type="text/javascript">

    var cont = 0;

    $('#loadMore').click(function(){
        
        cont +=10;

        if(cont >= "<?=mysqli_num_rows($arrayQueryCount)?>"){
            $('#loadMore').addClass('disabled');
            $('#loadMore').text('Não há mais movimentações');
        }

        $.ajax({
            type: "POST",
            url: "ajxRelGanhos.do",
            data: {itens: cont, casasDec: "<?=$casasDec?>", corTextos: "<?=$cor_textos?>", key: "<?=fnEncode($cod_empresa)?>", TIP_CAMPANHA: "<?=$tip_campanha?>"},
            beforeSend:function(){  
                $('#loadMore').text('Carregando...');
            },
            success:function(data){

                if(cont >= "<?=mysqli_num_rows($arrayQueryCount)?>"){
                    $('#loadMore').addClass('disabled');
                    $('#loadMore').text('Não há mais movimentações');
                }else{
                    $('#loadMore').text('Carregar Mais');
                }
                    $('#relConteudo').append(data);
                
                // console.log(data);
            },
            error:function(){
                alert('Erro ao carregar...');
            }
        });
    });
</script>

<?php include 'footer.php' ?>