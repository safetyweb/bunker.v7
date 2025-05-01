    <?php 
        include 'header.php'; 
        $tituloPagina = "Regiões";
        include "navegacao.php";

         if(!isset($_SESSION["usuario"])){
        
           header('Location:app.do?key='.fnEncode($_SESSION["EMPRESA_COD"]));
           
        }

        $sqlCli = "SELECT COD_CLIENTE FROM CLIENTES WHERE NUM_CGCECPF ='".$_SESSION['usuario']."' AND COD_EMPRESA = $cod_empresa";
        $qrCli = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa,''),$sqlCli));
        $cod_cliente = $qrCli['COD_CLIENTE'];

        // fnescreve($cod_cliente);


    ?>  
        
        <div class="container">
            <div class="push30"></div> 

            <div class="row">
                
                <div class="col-xs-12">
                    
                    <a href="enderecosDuque.php?key=<?=fnEncode($_SESSION["EMPRESA_COD"])?>&idp=<?=fnEncode('S')?>" class="btnLoad">
                        <div class="col-xs-2 text-center" style="padding-left: 0;padding-right: 0; padding-top: 7px;">
                            <img src="img/desconto.png" width="35px">
                        </div>

                        <div class="col-xs-10">
                            <h4 style="color:#03204F"><b>Postos promocionais para usuários Duque APP</b></h4>
                        </div>
                    </a>

                </div>

            </div>

            <div class="push20"></div>

            <div class="row">
                
                <div class="col-xs-12">
                    
                    <a href="enderecosDuque.php?key=<?=fnEncode($_SESSION["EMPRESA_COD"])?>&idp=<?=fnEncode('C')?>" class="btnLoad">
                        <div class="col-xs-2 text-center" style="padding-left: 0;padding-right: 0; padding-top: 7px;">
                            <span class="fas fa-dollar-sign" style="font-size: 35px; color:#03204F"></span>
                        </div>

                        <div class="col-xs-10">
                            <h4 style="color:#03204F"><b>Postos com cashback para usuários Duque APP</b></h4>
                        </div>
                    </a>

                </div>

            </div>

            <div class="push20"></div>

            <div class="row">
                
                <div class="col-xs-12">
                    <a href="enderecosDuque.php?key=<?=fnEncode($_SESSION["EMPRESA_COD"])?>&idp=<?=fnEncode('N')?>" class="btnLoad">
                        <div class="col-xs-2 text-center" style="padding-left: 0;padding-right: 0; padding-top: 7px;">
                           <img src="img/gas-pump.png" width="35px">
                        </div>

                        <div class="col-xs-10">
                            <h4 style="color:#03204F"><b>Todos os postos</b></h4>
                        </div>
                    </a>

                </div>

            </div>      
            
            <div class="push50"></div> 
            
        </div> <!-- /container -->  

        <?php
        function get_browser_name($user_agent)
        {
            if (strpos($user_agent, 'Opera') || strpos($user_agent, 'OPR/')) return 'Opera';
            elseif (strpos($user_agent, 'Edge')) return 'Edge';
            elseif (strpos($user_agent, 'Chrome')) return 'Chrome';
            elseif (strpos($user_agent, 'Safari')) return 'Safari';
            elseif (strpos($user_agent, 'Firefox')) return 'Firefox';
            elseif (strpos($user_agent, 'iPhone')) return 'iPhone';
            elseif (strpos($user_agent, 'iPad')) return 'iPad';
             elseif (strpos($user_agent, 'iPod')) return 'iPod';
            elseif (strpos($user_agent, 'MSIE') || strpos($user_agent, 'Trident/7')) return 'Internet Explorer';
            
            return 'Other';
        }

        // Usage:

        $navegador=get_browser_name($_SERVER['HTTP_USER_AGENT']); 

        //if($navegador!= 'Safari' || $navegador!= 'iPhone'||$navegador!= 'iPad'||$navegador!= 'iPod'||$navegador!= 'Other' )
        //{    
        echo'        
        <script type="text/javascript">
        var x = document.getElementById("demo");
          
                    function getLocation()
                      {
                                
                      if (navigator.geolocation)
                        {
                        navigator.geolocation.getCurrentPosition(showPosition);
                                      
                        }
                      else{x.innerHTML="O seu navegador não suporta Geolocalização.";}
                      }
                    function showPosition(position)
                      {
                        document.cookie="RD_localAtual="+position.coords.latitude + "," + position.coords.longitude;
                                   
                      }

                    getLocation()   
        </script>';

        //}
        ?>

    <?php include 'footer.php'; ?>