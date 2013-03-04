<?php 
	require_once 'config/conexion.php';

	if(isset($_POST['btn_ingresar'])){
		$usuario = mysql_real_escape_string(stripslashes($_POST['usuario']));
		$password = mysql_real_escape_string(stripslashes($_POST['password']));	
		$consulta = query("SELECT * FROM usuarios WHERE username = '$usuario' AND password = MD5('$password')");

		
		if($consulta){
			session_start();
			$_SESSION['session']=true;
			$_SESSION['usuario_id']=$consulta['id'];
			$url= URL_APP;
			header("location:$url");
		}
		else{
			//header("location:index.php");
			var_dump($consulta);
		}
	}

?>