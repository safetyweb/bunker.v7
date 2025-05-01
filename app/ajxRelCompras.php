<?php 
include '_system/_functionsMain.php';

$cod_empresa = fnDecode($_POST['key']);
$casasDec = fnLimpaCampoZero($_POST['casasDec']);
$inicio = fnLimpaCampoZero($_POST['itens']);
$cor_textos = $_POST['corTextos'];

$dat_ini = date("Y-m-d",strtotime("-30 days"));

$sqlCli = "SELECT COD_CLIENTE 
			FROM CLIENTES 
			WHERE NUM_CGCECPF = $_SESSION[usuario] 
			AND COD_EMPRESA = $cod_empresa";

$arrayCli = mysqli_query(connTemp($cod_empresa,''),$sqlCli);

$qrCli = mysqli_fetch_assoc($arrayCli);

$cod_cliente = $qrCli[COD_CLIENTE];

$sql = "SELECT * FROM(

                            (SELECT  B.DES_LANCAMEN, 
                                        C.DES_OCORREN, 
                                        D.NOM_FANTASI, 
                                        E.DES_FORMAPA, 
                                        A.COD_STATUSCRED,
                                        A.VAL_TOTPRODU,
                                        A.VAL_RESGATE,
                                        A.VAL_DESCONTO,
                                        A.VAL_TOTVENDA,
                                        A.DAT_CADASTR_WS,


                                        ROUND(IFNULL((SELECT SUM(VAL_CREDITO) 
                                                    FROM CREDITOSDEBITOS 
                                                    WHERE CREDITOSDEBITOS.COD_VENDA = A.COD_VENDA AND 
                                                    CREDITOSDEBITOS.COD_CLIENTE = A.COD_CLIENTE AND 
                                                    TIP_CREDITO = 'C'), 0), 2) VAL_CREDITOS,
                                        (SELECT MIN(DAT_EXPIRA) 
                                                FROM CREDITOSDEBITOS 
                                                WHERE CREDITOSDEBITOS.COD_VENDA = A.COD_VENDA AND 
                                                      CREDITOSDEBITOS.COD_CLIENTE = A.COD_CLIENTE AND 
                                                        TIP_CREDITO = 'C') DAT_EXPIRA, 
                                        (select count(*) from 
                                                            itemvenda 
                                                            where cod_venda=a.cod_venda and 
                                                                  itemvenda.cod_exclusa > 0)as EXCLUIDO 
                            FROM VENDAS a 
                            LEFT JOIN webtools.tipolancamentomarka b ON b.COD_LANCAMEN = a.COD_LANCAMEN 
                            LEFT JOIN webtools.ocorrenciamarka c ON c.COD_OCORREN = a.COD_OCORREN 
                            LEFT JOIN webtools.unidadevenda d ON d.COD_UNIVEND = a.COD_UNIVEND 
                            LEFT JOIN formapagamento e ON e.COD_FORMAPA = a.COD_FORMAPA 
                            WHERE a.COD_CLIENTE = $cod_cliente AND 
                                  a.COD_EMPRESA = $cod_empresa)
                            UNION
                            (SELECT   'AVULSO', 
                                        'AVULSO', 
                                        D.NOM_FANTASI, 
                                        'AVULSO' AS DES_FORMAPA, 
                                      A.COD_STATUSCRED,
                                        0,
                                        0,
                                        0,
                                        0,
                                        A.DAT_REPROCE,
                                     A.VAL_CREDITO,
                                       A.DAT_EXPIRA, 
                                        '' AS EXCLUIDO 
                            FROM CREDITOSDEBITOS a 
                            LEFT JOIN webtools.unidadevenda d ON d.COD_UNIVEND = a.COD_UNIVEND 
                            WHERE a.COD_CLIENTE = $cod_cliente AND 
                                  a.COD_EMPRESA = $cod_empresa AND 
                                  A.TIP_CREDITO='C' AND 
                                  A.COD_VENDA=0)
                        ) saldoCli
                        ORDER BY DAT_CADASTR_WS DESC
                        LIMIT $inicio, 10";
// echo $sql;                                           
$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);

$count = 0;
$valorTTotal = 0;
$valorTRegaste = 0;
$valorTDesconto = 0;
$valorTvenda = 0;
$classeExc = "";
//pegar o ultimo tokem gerado 
                                            
//==fim==============
while ($qrBuscaProdutos = mysqli_fetch_assoc($arrayQuery)){

    $count++;
    $background="#F2F3F4";
    
    if ($qrBuscaProdutos['EXCLUIDO'] == 0) {
        $classeExc2 = "";
        $mostraItemExcluido = "";
    }else{
        $classeExc2 = "text-danger";    
        $mostraItemExcluido = "<i class='fa fa-minus-circle' aria-hidden='true'></i>";  
    }
    
    
    
   $tokem="select itemvenda.COD_VENDA,itemvenda.DES_PARAM1,
                  itemvenda.DES_PARAM2,vendas.COD_VENDAPDV 
                  from itemvenda 
            inner join vendas on itemvenda.COD_VENDA= vendas.COD_VENDA
            where vendas.COD_VENDAPDV='".$qrBuscaProdutos['COD_VENDAPDV']."'";

    // echo $tokem;
    // exit();
   $tokemexec=mysqli_query(connTemp($cod_empresa,''),$tokem);
   $rwtokem=mysqli_fetch_assoc($tokemexec);
   $colunaEspecial = $rwtokem['DES_PARAM2'];
   if($colunaEspecial=='' || $colunaEspecial=='None')
   {
        $colunaEspecial = '<i class="fa fa-times text-danger fa-1x" aria-hidden="true"></i>';
   }     
           
    

    $data = explode(" ", $qrBuscaProdutos['DAT_CADASTR_WS']);

    $txtExpira = "Expira: <b>".fnDataShort($qrBuscaProdutos['DAT_EXPIRA'])."</b>";
    $corExpira = "";

    $val_venda = fnValor($qrBuscaProdutos['VAL_TOTVENDA'],2);

    if($qrBuscaProdutos['DES_LANCAMEN'] == "CRED. AVULSO"){
        $val_venda = "Créd. Avulso";
    } else if($qrBuscaProdutos['DES_LANCAMEN'] == "RESG. AVULSO"){
        $val_venda = "Resg. Avulso";
    }

    if($qrBuscaProdutos['DAT_EXPIRA'] == ""){
        $txtExpira = "&nbsp;";
        $corExpira = "";
    }else if($qrBuscaProdutos['DAT_EXPIRA'] < date("Y-m-d")){
        $txtExpira = "Expirado: <b>".fnDataShort($qrBuscaProdutos['DAT_EXPIRA'])."</b>";
        $corExpira = "text-danger";
    }

    if ($qrBuscaProdutos['COD_STATUSCRED'] != 6) {
        $valorTTotal = $valorTTotal + $qrBuscaProdutos['VAL_TOTPRODU'];
        $valorTRegaste = $valorTRegaste + $qrBuscaProdutos['VAL_RESGATE'];
        $valorTDesconto = $valorTDesconto + $qrBuscaProdutos['VAL_DESCONTO'];
        $valorTvenda = $valorTvenda + $qrBuscaProdutos['VAL_TOTVENDA'];
        $classeExc = "";
    }else{
        $classeExc = "text-danger";
        $background="#FADBD8";
        $txtExpira="<b>Estornado</b>";
        $corExpira="text-danger";
    }

    $val_credito = $qrBuscaProdutos['VAL_CREDITOS'];
    $cred_extra = 0;

    if($cod_empresa == 19){
        $cred_extra = $qrBuscaProdutos['VAL_CREDITOS_EXTRA'];
        $val_credito = $val_credito - $cred_extra;
    }

    $txtGanho = "Cashback:";
    
    if($tip_campanha == 12){
        $txtGanho = "Pontos:";
    }

?>

    <div class="col-xs-12 reduzMargem corIcones" style="color: <?=$cor_textos?>">
        <div class="shadow2" style="background-color: <?=$background?>;">
            <div class="push5"></div>
            <div class="col-xs-4 zeraPadLateral text-center">
                <h5 class="f12"><b><?=fnDataShort($data[0])?></b><br/><span class="f9"><?=$data[1]?></span></h5>
            </div>
            <div class="col-xs-2 zeraPadLateral text-center">
                <h5 class="f9"><?=$colunaEspecial?></h5>
            </div>
            <div class="col-xs-4 zeraPadLateral text-center">
                <h5 class="f12"><?=$qrBuscaProdutos['NOM_FANTASI']?>
                <?php if($qrBuscaCliente['COD_PROFISS'] == 108){ ?>
                    <div class="push5"></div>
                <?php } ?>
                </h5>
            </div>
            <div class="col-xs-2 zeraPadLateral text-center">
                <h5 class="f12"><?=$val_venda?>
                </h5>
            </div>
            <?php  if($qrBuscaProdutos['DES_LANCAMEN'] == "RESG. AVULSO"){ ?>
                <div class="push"></div>
                <div class="col-xs-6" style="margin-top: -10px;">
                    <span class="f9 <?=$corExpira?>"><?=$txtExpira?></span>
                </div>
                <div class="col-xs-6 text-right" style="margin-top: -10px;">
                    <span class="f9 text-danger">Resgate: <b>- <?=fnValor($val_credito,2)?></b></span>
                </div>
            <?php }else{ ?>
            <div class="push"></div>
            <div class="col-xs-6" style="margin-top: -10px;">
                <span class="f9 <?=$corExpira?>"><?=$txtExpira?></span>
            </div>
            <div class="col-xs-6 text-right" style="margin-top: -10px;">
                <span class="f9 <?=$corExpira?>"><?=$txtGanho?> <b>+ <?=fnValor($val_credito,2)?></b></span>
            </div>
            <?php }?>
            <?php if($cred_extra > 0){ ?>
                <div class="push10"></div>
                <div class="col-xs-6 col-xs-offset-6 text-right" style="margin-top: -10px;">
                    <span class="f9 <?=$corExpira?>">Extra: <b>+ <?=fnValor($cred_extra,2)?></b></span>
                </div>
            <?php } ?>
            <?php if($qrBuscaProdutos['VAL_RESGATE'] > 0){ ?>
                <div class="push10"></div>
                <div class="col-xs-6 col-xs-offset-6 text-right" style="margin-top: -10px;">
                    <span class="f9 text-danger">Resgate: <b>- <?=fnValor($qrBuscaProdutos['VAL_RESGATE'],2)?></b></span>
                </div>
            <?php } ?>
            
            <div class="push5"></div>
        </div>
    </div>

<?php 

    $totCredito+=$qrBuscaProdutos['VAL_CREDITO'];

}
?>