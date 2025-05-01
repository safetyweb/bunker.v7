	<?php 
		include 'header.php'; 
		$tituloPagina = "Faça seu login";
		include "navegacao.php"; 
	?>	
		
        <div class="container">

			<div class="push20"></div> 

            <div class="text-center pagination-centered">
                <a class="">
                    <img alt="" class="logo img-responsive center-block" src="img/user_icon.png">
                </a>
            </div>

            <div class="push10"></div> 
				
			<style>

			.bigCheck {width:18px; height: 18px;}

			</style>			

            <form class="form-signin" method="post" action="login.php">
                <label for="login" class="sr-only">Email</label>
                <input type="email" name="login" id="login" value="<?php echo $_COOKIE['login'];?>" class="form-control" placeholder="e-Mail" required="" autofocus="">
                <label for="senha" class="sr-only">Senha</label>
                <div class="push10"></div> 	
                <input type="password" name="senha" id="senha"  value="<?php echo $_COOKIE['senha'];?>" class="form-control" placeholder="Senha" required="">

                <!-- <button class="btn btn-primary btn-block" type="submit">ENTRAR</button> -->
                <a href="menu.php" class="btn btn-primary btn-block">ENTRAR</a>
				<div class="push10"></div> 
			    <?php if($_COOKIE['login'] != "" && $_COOKIE['senha'] != ""){ ?>
                  <a href="javascript:void(0)" onclick="zeraLogin(this)" class="text-white">Não sou este usuário</a>
                <?php } ?>

            </form>
			
            <div class="push10"></div>
			
            <center><a href="login2.php?param=OFF" class="btn btn-default">Esqueci a senha</a></center>
			
            <div class="push20"></div>

        </div> <!-- /container -->

    <?php include 'footer.php'; ?>

    <script type="text/javascript">
        function zeraLogin(btn){
          $(btn).fadeOut(1,function(){
            $("#email,#senha").val("");
            document.cookie = "login=";
            document.cookie = "senha=";
          });
        }
    </script>