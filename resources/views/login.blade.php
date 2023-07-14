@extends('layouts.chat')
 <head>	
<?php 
session_start();
if(isset($_SESSION['login_id']))
header("location:index.php?page=home");

?>

</head>

<style>
	body{
		width: 100%;
	    height: calc(100%);
	    /*background: #007bff;*/
	}
	main#main{
		width:100%;
		height: calc(100%);
		background:white;
	}
	#login-right{
		position: absolute;
		right:0;
		width:40%;
		height: calc(100%);
		background:white;
		display: flex;
		align-items: center;
	}
	#login-left{
		position: absolute;
		left:0;
		width:60%;
		height: calc(100%);
		background:#59b6ec61;
		display: flex;
		align-items: center;
		
	}
	#login-right .card{
		margin: auto;
		z-index: 1
	}
	.logo {
    margin: auto;
    font-size: 8rem;
    background: white;
    padding: .5em 0.7em;
    border-radius: 50% 50%;
    color: #000000b3;
    z-index: 10;
}
div#login-right::before {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    width: calc(100%);
    height: calc(100%);
    background: #000000e0;
}

.card{
	background: #5ff5f5;
}

</style>

<body> 


  <main id="main" class=" bg-dark">
  		<div id="login-left">
  			<div class="logo"><span class="fa fa-comments"></span></div>
  		</div>

  		<div id="login-right">
  			<div class="card col-md-8">
  				<div class="card-body">
  						
  					<form id="login-form" >
  						<div class="form-group">
  							<!--<label for="username" class="control-label">Username</label>-->
  							<label for="icon-user" class="control-label"></label>
  							<input type="text" id="username" placeholder="Nome do usuario" name="username" class="form-control">
  						</div>
  						<div class="form-group">
  							<!--<label for="password" class="control-label">Password</label>-->
  							<label for="icon-password" class="control-label"></label>
  							<input type="password" id="password" placeholder="Senha" name="password" class="form-control">
  						</div>
  						<div class="col-md-12">
  						<button class="btn-sm btn-block btn-wave col-md-5 btn-info float-right " type="button" id="create_account">Criar conta</button>
  						<button class="btn-sm btn-block btn-wave col-md-4 btn-primary float-right mr-2 mt-0">Entrar</button>
  							
  						</div>
  					</form>
  				</div>
  			</div>
  		</div>
   

  </main>

  <a href="#" class="back-to-top"><i class="icofont-simple-up"></i></a>

  <div class="modal fade" id="uni_modal" role='dialog'>
    <div class="modal-dialog modal-md" role="document">
      <div class="modal-content">
        <div class="modal-header">
        <h5 class="modal-title"></h5>
      </div>
      <div class="modal-body">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" id='submit' onclick="$('#uni_modal form').submit()">Salvar</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
      </div>
      </div>
    </div>
  </div>

</body>
<script>
	window.start_load = function(){
	    $('body').prepend('<di id="preloader2"></di>')
	  }
	  window.end_load = function(){
	    $('#preloader2').fadeOut('fast', function() {
	        $(this).remove();
	      })
	  }
	 window.uni_modal = function($title = '' , $url='',$size=""){
		start_load()
		$.ajax({
		    url:$url,
		    error:err=>{
		        console.log()
		        alert("Ocorreu um erro")
		    },
		    success:function(resp){
		        if(resp){
		            $('#uni_modal .modal-title').html($title)
		            $('#uni_modal .modal-body').html(resp)
		            if($size != ''){
		                $('#uni_modal .modal-dialog').addClass($size)
		            }else{
		                $('#uni_modal .modal-dialog').removeAttr("class").addClass("modal-dialog modal-md")
		            }
		            $('#uni_modal').modal({
		              show:true,
		              backdrop:'static',
		              keyboard:false,
		              focus:true
		            })
		            end_load()
		        }
		    }
		})
		}
	$('#login-form').submit(function(e){
		e.preventDefault()
		start_load()
		if($(this).find('.alert-danger').length > 0 )
			$(this).find('.alert-danger').remove();
		$.ajax({
			url:'ajax.php?action=login',
			method:'POST',
			data:$(this).serialize(),
			error:err=>{
				console.log(err)
				end_load()
			},
			success:function(resp){
				if(resp == 1){
					location.href ='index.php?page=home';
				}else if(resp == 2){
					location.href ='voting.php';
				}else{
					$('#login-form').prepend('<div class="alert alert-danger">Nome do usuario/palavra-passe incorrecta.</div>')
					end_load()
				}
			}
		})
	})
	$('#create_account').click(function(){
		uni_modal('Signup','signup')
	})
</script>	
</html>