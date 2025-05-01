<?php
if($_GET['param']!='OFF')
{    
if($_SESSION["COD_RETORNO"]!=''){$cod_cliente=$_SESSION["COD_RETORNO"];} else {$cod_cliente= fnDecode($_GET['secur']);} 

$MENUSTARING=fnEncode($cod_cliente);
$sqladm="select * from clientes where cod_empresa=19 and 
                                    cod_univend=955 and 
                                    cod_entidad='39' and
                                    NUM_CARTAO='".$cod_cliente."' and
                                    cod_tpcliente in (7,8,9,10,11)";

$rwadm=mysqli_query(connTemp(19,''), $sqladm);

if($row_cnt = mysqli_num_rows($rwadm)>'0')
{
$listarelatorios=' <li><a href="listaRelatorios.php?secur='.$MENUSTARING.'"><i class="fa fa-files-o" aria-hidden="true"></i>&nbsp;&nbsp; Relatorios</a></li>';
}else{$listarelatorios='';}        
mysqli_free_result($rwadm);
}

if(isset($_GET['idP'])){
    $pag = fnLimpaCampo($_GET['idP']);
    ?>

        <script type="text/javascript">
            $(function(){
                $("#<?=$pag?>").addClass('active');
            });
        </script>

    <?php 
}

$cod_empresa = 19;

$sqlCli = "SELECT * FROM CLIENTES WHERE COD_EMPRESA = 19 AND NUM_CGCECPF = '".$cod_cliente."'";

$arrayCli = mysqli_query(connTemp(19,''), $sqlCli);

$qrCli = mysqli_fetch_assoc($arrayCli);

$cod_profiss = $qrCli[COD_PROFISS];

//busca dados da tabela
$sql = "SELECT * FROM TOTEM_APP WHERE COD_EMPRESA = $cod_empresa";
//fnEscreve($sql);
$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql) or die(mysqli_error());
$qrBuscaSiteTotemApp = mysqli_fetch_assoc($arrayQuery);

if (isset($qrBuscaSiteTotemApp)) {
  
    $cod_app = $qrBuscaSiteTotemApp['COD_APP'];
    $des_logo = $qrBuscaSiteTotemApp['DES_LOGO'];
    $des_imgback = $qrBuscaSiteTotemApp['DES_IMGBACK'];
  
    $cor_fullpag = $qrBuscaSiteTotemApp['COR_FULLPAG'];
    $cor_textfull = $qrBuscaSiteTotemApp['COR_TEXTFULL'];
  
    $cor_backbar = $qrBuscaSiteTotemApp['COR_BACKBAR'];
    $cor_backpag = $qrBuscaSiteTotemApp['COR_BACKPAG'];
  
    $cor_titulos = $qrBuscaSiteTotemApp['COR_TITULOS'];
    $cor_textos = $qrBuscaSiteTotemApp['COR_TEXTOS'];
  
    $cor_botao = $qrBuscaSiteTotemApp['COR_BOTAO'];
    $cor_botaoon = $qrBuscaSiteTotemApp['COR_BOTAOON'];

    $log_ofertas = $qrBuscaSiteTotemApp['LOG_OFERTAS'];
    $log_dados = $qrBuscaSiteTotemApp['LOG_DADOS'];
    $log_extrato = $qrBuscaSiteTotemApp['LOG_EXTRATO'];
    $log_premios = $qrBuscaSiteTotemApp['LOG_PREMIOS'];
    $log_enderecos = $qrBuscaSiteTotemApp['LOG_ENDERECOS'];
    $log_comunica = $qrBuscaSiteTotemApp['LOG_COMUNICA'];
    $log_bannerhome = $qrBuscaSiteTotemApp['LOG_BANNERHOME'];
    $log_bannerlista = $qrBuscaSiteTotemApp['LOG_BANNERLISTA'];

    if($qrBuscaSiteTotemApp['LOG_OFERTAS']=='S'){ $chk_ofertas = "checked"; }else{ $chk_ofertas = ""; }
    if($qrBuscaSiteTotemApp['LOG_DADOS']=='S'){ $chk_dados = "checked"; }else{ $chk_dados = ""; }
    if($qrBuscaSiteTotemApp['LOG_EXTRATO']=='S'){ $chk_extrato = "checked"; }else{ $chk_extrato = ""; }
    if($qrBuscaSiteTotemApp['LOG_PREMIOS']=='S'){ $chk_premios = "checked"; }else{ $chk_premios = ""; }
    if($qrBuscaSiteTotemApp['LOG_ENDERECOS']=='S'){ $chk_enderecos = "checked"; }else{ $chk_enderecos = ""; }
    if($qrBuscaSiteTotemApp['LOG_COMUNICA']=='S'){ $chk_comunica = "checked"; }else{ $chk_comunica = ""; }


  
} else {
    //default se vazio
    
  $cod_app = 0;
  $des_logo = "";
  $des_imgback = "";

  $cor_fullpag = "#34495e";
    $cor_textfull = "#fff";

    $cor_backbar = "34495e";
    $cor_backpag = "f2f3f4";

    $cor_titulos = "#34495e";
    $cor_textos = "#34495e";

    $cor_botao = "#0092d8";
    $cor_botaoon = "#48c9b0";

    $log_ofertas = 'S';
  $log_dados = 'S';
  $log_extrato = 'S';
  $log_premios = 'S';
  $log_enderecos = 'S';
  $log_comunica = 'S';
  $log_bannerhome = "N";
  $log_bannerlista = "N";

    $chk_ofertas = "checked";
    $chk_dados = "checked";
    $chk_extrato = "checked";
    $chk_premios = "checked";
    $chk_enderecos = "checked";
    $chk_comunica = "checked";

}

// if($cod_cliente == "1734200014"){
//     $log_bannerhome = 'S';
//     $log_bannerlista = 'S';
// }

if($_SERVER[SERVER_NAME] == "adm.bunker.mk"){
    $server = $_SERVER[SERVER_NAME]."/appduque";
}else{
    $server = $_SERVER[SERVER_NAME];
}

?>

<style type="text/css">
    body{
        padding: env(safe-area-inset-top) env(safe-area-inset-right) env(safe-area-inset-bottom) env(safe-area-inset-left);

    }
    input{
        font-size: 16px!important;
    }
    .active{
        background-color: #FFF!important;
        color: #03224E!important;
    }
    .link-menu{
        margin-left: 5px!important;
        width: 170px;
        padding: 10px 5px!important; 
    }
    .input-hg {
        background-color: transparent !important;
        border: none;
        border-bottom: 2px solid #5DADE2;  
        border-radius: 0;       
      }
      
      .input-hg:focus {
        border-bottom-color: #4d4d4d;
      }
      .shadow{
        box-shadow: 0 4px 2px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
      }
      #menu{
        margin-top: 0px!important;
            background: #2C3E50!important;
        }

</style>

<meta name="viewport" content="user-scalable=no, initial-scale=1, maximum-scale=1, minimum-scale=1, width=device-width, height=device-height" />

<!-- navbar -->
        <div class="page">
            <div class="header">
                <nav class="navbar navbar-light navbar-fixed-top bg-faded" style="padding-top: 10px;">
                    <div class="menuTitulo"><?php echo $tituloPagina; ?></div>
                    <div class="logoNavbar">
                        <img alt="" style="height: 35px" src="https://<?=$server?>/img/logo_navbar.png">
                    </div>
					<?php if (@$_SESSION['login'] != ""){  ?>
                    <a id="openMenu" href="#menu"><i class="fa fa-bars" aria-hidden="true"></i></a>
					<?php } else {  ?>
					<a id="openMenu" href="index.php"><i class="fa fa-bars" aria-hidden="true"></i></a>
					<?php }  ?>
                    <div class="menuName">Menu</div>
                </nav>
            </div>
        </div>

        <!-- Menu -->
        <nav id="menu" style="margin-top: 12px;">
            <ul>
                <li ><a class="link-menu" id="<?=fnEncode(1)?>" href="novaHome.do?secur=<?php echo $MENUSTARING;?>&idP=<?=fnEncode(1)?>"><i class="fa fa-home" aria-hidden="true"></i>&nbsp; &nbsp;  Home</a></li>
                <!--<li><a href="home.php?secur=<?php //echo $MENUSTARING;?>"><i class="fa fa-user-o" aria-hidden="true"></i>&nbsp; &nbsp;  Meus Dados</a></li>-->
                <li><a class="link-menu" id="<?=fnEncode(2)?>" href="relCompras.do?secur=<?php echo $MENUSTARING;?>&idP=<?=fnEncode(2)?>"><i class="fa fa-shopping-cart" aria-hidden="true"></i>&nbsp;&nbsp;  Minhas Compras</a></li>
                <?php if($log_ofertas == 'S'){ ?>
                <li><a class="link-menu" id="<?=fnEncode(3)?>" href="ofertas.do?secur=<?php echo $MENUSTARING;?>&idP=<?=fnEncode(3)?>"><i class="fa fa-tags" aria-hidden="true"></i>&nbsp;&nbsp;  Minhas Ofertas</a></li>
                <?php } ?>
                <?php 
                    //if($cod_cliente == "42147177830"){ 
                    // if($cod_profiss == 108){ 
                ?>
                    <!-- <li><a class="link-menu" id="<?=fnEncode(5)?>" href="premios.do?secur=<?php echo $MENUSTARING;?>&idP=<?=fnEncode(5)?>"><i class="fa fa-gift" aria-hidden="true"></i>&nbsp;&nbsp; Prêmios</a></li> -->
                <?php 
                    // } 
                ?>
                <li><a class="link-menu" id="<?=fnEncode(6)?>" href="infoCadastro.do?secur=<?php echo $MENUSTARING;?>&idP=<?=fnEncode(6)?>"><i class="fa fa-user-o" aria-hidden="true"></i>&nbsp;&nbsp; Meu Cadastro</a></li>
                <!-- <li><a class="link-menu" id="<?=fnEncode(3)?>" href="token.php?secur=<?php echo $MENUSTARING;?>&idP=<?=fnEncode(3)?>"><i class="fa fa-check" aria-hidden="true"></i>&nbsp;&nbsp;  Gerar Token</a></li> -->
                <li><a class="link-menu" id="<?=fnEncode(4)?>" href="novoRegioes.do?secur=<?php echo $MENUSTARING;?>&idP=<?=fnEncode(4)?>"><i class="fa fa-map-o" aria-hidden="true"></i>&nbsp;&nbsp; Postos</a></li>

                <?php
                    if($cod_cliente == "00590601" || $cod_cliente == "00590539" || $cod_cliente == "00590478"){
                        // fnEscreve($cod_cliente);
                ?>
                    <!-- 
                     -->

                <?php 
                    }
                ?>
				<?php echo $listarelatorios;?>

                <!--
                <li><a href=""><i class="fa fa-files-o" aria-hidden="true"></i>&nbsp;&nbsp; Relatórios</a>
                    <ul>
                        <li><a href="">Não Conformidade</a></li>
                        <li><a href="">Performance</a></li>
                        <li><a href="">Gerencial</a></li>
                        <li><a href="">Clientes</a></li>
                    </ul>
                </li>
				-->
                <li><a class="link-menu" href="novoLogin.do?logoff=1"><i class="fa fa-sign-out" aria-hidden="true"></i>&nbsp;&nbsp; Sair</a></li>
            </ul>
        </nav>

		<div class="push30"></div>

        <script>
            
            // iPhoneX();
            // ['resize','orientationchange'].forEach(function(evt) {
            //     window.addEventListener(evt,iPhoneX,{passive:true});
            // });

            // function iPhoneX() {
            //     if (window.innerWidth == 375 && window.innerHeight == 812) {
            //         if (!document.getElementById('iphone_layout')) {
            //             var img = document.createElement('img');
            //             img.id = 'iphone_layout';
            //             img.style.position = 'fixed';
            //             img.style.zIndex = 9999;
            //             img.style.pointerEvents = 'none';
            //             img.src = 'https://user-images.githubusercontent.com/19612632/41588096-1ac4c128-73b1-11e8-9fc7-db9ab61903ed.png'
            //             document.body.insertBefore(img,document.body.children[0]);
            //         }
            //     } else if (document.getElementById('iphone_layout')) {
            //         document.body.removeChild(document.getElementById('iphone_layout'));
            //     }
            // }

        </script>
