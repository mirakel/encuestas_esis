<?php 
	require_once('../config/config.php');
	require_once('recursos/includes.php');
?>
<?php head();?>
	<!-- menu -->
	<div class="navbar navbar-inverse navbar-fixed-top">
		<div class="navbar-inner">
			<div class="container">
				<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</a>
				<a class="brand" href="#">Flisol 2013</a>
				<div class="nav-collapse">
					<ul class="nav">
						<li><a href="index.php">Home</a></li>
						<li class="active"><a href="encuestas.php">Encuestas</a></li>
					</ul>
				</div><!--/.nav-collapse -->
			</div>
		</div>
	</div>
	<!-- Fin menu -->

	<div class="container">		
		<ul class="breadcrumb">
			<li><a href="#"><i class="icon-home"></i></a><span class="divider">/</span>
			</li>
	    	<li><a href="#">Home</a> <span class="divider">/</span></li>
	    	<li class="active">Encuestas</li>
    	</ul>

    	<div class="row">
    		<div class="span1">
				<a href="" class="btn btn-success">
					<i class="icon-plus icon-white"></i><br>Agregar
				</a>
			</div>
    	</div>
    	
    	<div class="row-fluid">
    		<div class="span12">
    			<div class="box">
    				<h4 class="box-header round-top">Gestión de Encuestas</h4>
    				<div class="box-content">
    					<table class="table table-striped table-bordered dataTable">
    						<thead>
    							<th>Titulo</th>
    							<th>Fecha registro</th>
    							<th>Acciones</th>
    						</thead>
    						<tbody>
    							<tr>
    								<td>hola</td>
    								<td>hola</td>
    								<td>hola</td>
    							</tr>
    						</tbody>
    					</table>
    				</div>
    			</div>
    		</div>
    	</div>
	</div> <!-- /container -->

<?php footer();?>