<?php 

function redondear_dos_decimal($valor) {
	$float_redondeado=round($valor * 100) / 100;
	return $float_redondeado;
}

function retornarStringValido($cadena)
{
    $login = strtolower($cadena);
    $b     = array("á","é","í","ó","ú","ä","ë","ï","ö","ü","à","è","ì","ò","ù","ñ"," ",",",".",";",":","¡","!","¿","?",'"');
    $c     = array("a","e","i","o","u","a","e","i","o","u","a","e","i","o","u","n","","","","","","","","","",'');
    $login = str_replace($b,$c,$login);
    return $login;
}
function fecha_hora()
{
	$gmt_peru = -5;
	$fecha_gmt = gmmktime(gmdate("H")+$gmt_peru,gmdate("i"),gmdate("s"),gmdate("n"),gmdate("j"),gmdate("Y"));
	$fecha_hora = gmdate('Y-n-j H:i:s',$fecha_gmt);
	return $fecha_hora;
}
function fecha()
{
	$gmt_peru = -5;
	$fecha_gmt = gmmktime(gmdate("H")+$gmt_peru,gmdate("i"),gmdate("s"),gmdate("n"),gmdate("j"),gmdate("Y"));
	$fecha = gmdate('Y-n-j',$fecha_gmt);
	return $fecha;
}

function hora()
{
	$gmt_peru = -5;
	$fecha_gmt = gmmktime(gmdate("H")+$gmt_peru,gmdate("i"),gmdate("s"),gmdate("n"),gmdate("j"),gmdate("Y"));
	$hora = gmdate('H:i:s',$fecha_gmt);
	return $hora;
}

function traducir($text)
{
	$url = 'http://ajax.googleapis.com/ajax/services/language/translate?v=1.0&q='.urlencode($text).'&langpair=en|es';
	$curl_handle = curl_init();
	curl_setopt($curl_handle,CURLOPT_URL, $url);		     curl_setopt($curl_handle,CURLOPT_CONNECTTIMEOUT,2);
	curl_setopt($curl_handle,CURLOPT_RETURNTRANSFER,1);
	$code = curl_exec($curl_handle);
	curl_close($curl_handle);
	$json = json_decode($code);
	$traduc = $json->responseData;
	$text = $traduc->translatedText;
	$traduccion = utf8_decode($text);
	return $traduccion;
}

function nro_boleta($nro)
{
	$tmp=7;			
	$nro_ceros=$tmp-strlen($nro);
	for($z=1; $z<=$nro_ceros; $z++):
		@$ceros.='0';
	endfor;
	if(empty($ceros)):
		$nro_boleta_imp=$nro;				
	else:
		$nro_boleta_imp=$ceros.$nro;				
	endif;
	
	return $nro_boleta_imp;
		
}

function nota_letra($nivel,$nota)
{
	if($nivel==1):
		/*
		Esto dentra a funcionar apartir del 4 bimestre
		if(0<=$nota && $nota<=10):
			$nota_nueva='C';
		else:
			if(11<=$nota && $nota <=13):
				$nota_nueva='B';	
			else:
				if(14<=$nota && $nota <=16):
					$nota_nueva='A';				
				else: 
					if(17<=$nota && $nota <=20):
						$nota_nueva='AD';							
					endif; 
				endif;
			endif; 
		endif;
		*/
		if(0<=$nota && $nota<=10):
			$nota_nueva='C';
		else:
			if(11<=$nota && $nota <=12):
				$nota_nueva='B';	
			else:
				if(13<=$nota && $nota <=20):
					$nota_nueva='A';
				endif;
			endif; 
		endif;
	else:
		$nota_nueva=$nota;
	endif;
	
	return $nota_nueva;
}
?>

<?php 
function promedio_alumno($id_grado,$id_institucion,$id_anio,$id_alumna,$id_bimestre)
{

	$area_data=@query_data("SELECT * FROM view_areas_por_grado_anio WHERE id_grado=$id_grado AND id_institucion=$id_institucion AND id_anio=$id_anio ORDER BY nombre_area");

	$suma_promedio_area=0.00;

	foreach($area_data as $area):
	$id_area=$area['id_area'];
		$promedio_area=@query("SELECT * FROM vista_promedio_area
							WHERE id_alumna=$id_alumna AND id_anio=$id_anio AND id_bimestre=$id_bimestre AND id_area=$id_area");
				
		$prueba_bimestral=@query("SELECT * FROM prueba_bimestral AS pb INNER JOIN sesion_clase AS sc ON pb.id_sesion=sc.id_sesion
				WHERE id_alumna=$id_alumna AND id_anio=$id_anio AND id_bimestre=$id_bimestre AND id_area=$id_area");
						
		$suma_tmp=number_format($promedio_area['promedio_area'],0)+ number_format($prueba_bimestral['nota_prueba'],0); 
		$promedio_area=$suma_tmp/2;
		$suma_promedio_area=$suma_promedio_area+$promedio_area;
	endforeach;
	return $promedio_alumno=$suma_promedio_area/count($area_data);
}
?>

<?php 
function ver_documento($nombre_anio,$id_institucion)
{
	$documento_data=@query_data("SELECT * FROM documento WHERE id_institucion=$id_institucion AND YEAR(fecha_subida)=$nombre_anio");
?>
	<table class="tabladetalle">
	<?php if(!empty($documento_data)):?>
	<thead>
		<th colspan="2">NOMBRE DOCUMENTO</th>
		<th>FECHA</th>
		<th>HORA</th>
		<th>OPCIONES</th>
	</thead>
	<?php $i=1;?>
	<?php foreach($documento_data as $doc):?>
		<tr>
			<td><img src="<?php echo URL_APP; ?>recursos/images/icons/pdf24.png" /></td>
			<td><?php echo utf8_encode($doc['nombre_documento']);?></td>
			<?php 
			$fecha=$doc['fecha_subida'];
			setlocale(LC_TIME, "spanish");
			$fecha_upload= strftime("%A, %d de %B del %Y",strtotime($fecha));
			$hora_upload=date("g:i:s A",strtotime($fecha));
			?>
			<td><?php echo utf8_encode($fecha_upload);?></td>
			<td align="center"><?php echo utf8_encode($hora_upload);?></td>
			<td align="center">
				<a href="<?php echo URL_APP; ?>recursos/documento/<?php echo utf8_encode($doc['nombre_documento']);?>" title="Ver"><img src="<?php echo URL_APP; ?>recursos/images/icons/magnifier.png" alt="Ver" /></a>
			</td>
		</tr>
	<?php $i++;?>
	<?php endforeach;?>
	<?php else:?>
	<tr>
		<td colspan="4" align="center"><span>NO HAY NING&Uacute;N REGISTRO</span></td>
	</tr>
	<?php endif;?>
	</table>

<?php 
}
?>


<?php 
function ver_prueba_bimestral($id_grado,$id_institucion)
{
?>
	<div class="publicacion-box">
		<div class="publicacion-box-content">
			<?php $articulo_prueba=@query("SELECT * FROM articulo WHERE id_categoria=8 AND id_grado=$id_grado AND id_institucion=$id_institucion");?>
			<?php if(!empty($articulo_prueba)):?>
				<h1 align="center"><?php echo utf8_encode($articulo_prueba['titulo_articulo']); ?></h1>
				
				<div style="text-align: justify; line-height: 1.5em ">
					<p><?php echo utf8_encode($articulo_prueba['descripcion_articulo']);?><p/>
				</div>
			<?php else:?>
				<h3 align="center">No disponible</h3>
			<?php endif;?>
			
		</div>
	</div>

<?php 
}
?>

<?php 
function ver_prueba_universitaria($id_grado,$id_institucion)
{
?>
	<div class="publicacion-box">
		<div class="publicacion-box-content">
			<?php $articulo_prueba=@query("SELECT * FROM articulo WHERE id_categoria=9 AND id_grado=$id_grado AND id_institucion=$id_institucion");?>
			<?php if(!empty($articulo_prueba)):?>
				<h1 align="center"><?php echo utf8_encode($articulo_prueba['titulo_articulo']); ?></h1>
				
				<div style="text-align: justify; line-height: 1.5em ">
					<p><?php echo utf8_encode($articulo_prueba['descripcion_articulo']);?><p/>
				</div>
			<?php else:?>
				<h3 align="center">No disponible</h3>
			<?php endif;?>
			
		</div>
	</div>

<?php 
}
?>


<?php 
function periodo_evaluacion($nombre_anio)
{
 $periodos_data=@query_data("SELECT * FROM view_periodos_evaluacion WHERE anio_academico=$nombre_anio"); 
?>
	<table id="tabladetalle3" width="100%">		
		<thead>
			<tr>
				<th>PERIODO</th>
				<th>FECHA DE INICIO</th>
				<th>FECHA DE T&Eacute;RMINO</th>
				<th>ESTADO ACAD&Eacute;MICO</th>
			</tr>
		</thead>
		<tbody>
			<?php if(!empty($periodos_data)): ?>
			<?php foreach($periodos_data as $periodo): ?>
			<tr>
				<td align="center"><?php echo utf8_encode($periodo['nombre']); ?></td>
				<?php 
						setlocale(LC_TIME, "spanish");
		
				?>
				<td><?php echo utf8_encode(strftime("%d de %B del %Y",strtotime($periodo['fecha_inicio']))); ?></td>
				<td><?php echo utf8_encode(strftime("%d de %B del %Y",strtotime($periodo['fecha_termino']))); ?></td>
				<td align="center"><?php echo utf8_encode($periodo['estado_academico']); ?></td>
			</tr>
			<?php endforeach; ?>
			<?php endif; ?>
		</tbody>
	</table>

<?php 
}
?>




<?php 
function libreta_alumno($id_alumna,$nombre_anio,$id_anio,$pagina)
{
 
 	$alumno_data=@query("SELECT * FROM vista_alumnos_matriculados WHERE YEAR(fecha_matricula)=$nombre_anio AND id_alumna=$id_alumna");
	$id_grado=$alumno_data['id_grado'];
	$id_seccion=$alumno_data['id_seccion'];
	$id_institucion=$alumno_data['id_institucion'];
	$nivel=query("SELECT * FROM grado WHERE id_grado=$id_grado");
	$id_nivel=$nivel['id_nivel'];
	$nombre_alumno=utf8_encode($alumno_data['apellido_paterno'].' '.$alumno_data['apellido_materno'].', '.$alumno_data['nombres']);
	$area_data=@query_data("SELECT * FROM view_areas_por_grado_anio WHERE id_grado=$id_grado AND id_institucion=$id_institucion AND id_anio=$id_anio ORDER BY nombre_area");
	$periodo_data=@query_data("SELECT * FROM periodo");
	
	info_sistema('<strong>ALUMNO :</strong> '.$nombre_alumno.'<br /><strong>GRADO :</strong> '.utf8_encode($alumno_data['nombre_grado']).' <br /><strong>SECCI&Oacute;N : </strong>'.$alumno_data['nombre_seccion'].''); 

?>
	<table class="tablaasistencia">
	<thead>
		<tr>
			<th rowspan="2" class="titulo">AREA ACAD&Eacute;MICA</th>
			<th colspan="4" class="titulo">BIMESTRE</th>
		</tr>
		<tr>
			<th class="titulo">I</th>
			<th class="titulo">II</th>
			<th class="titulo">III</th>
			<th class="titulo">IV</th>
		</tr>
	</thead>
	<tbody>
	<?php foreach($area_data as $area):?>
	<tr>
	<?php
	$id_area=$area['id_area'];
	?>
		<td class="tituloarea"><?php echo utf8_encode($area['nombre_area']);?></td>
		<?php for($z=0;$z<count($periodo_data);$z++): ?>
		<?php 
		$id_bimestre=$periodo_data[$z]['id_periodo'];
		$promedio_area=@query("SELECT * FROM vista_promedio_area
			WHERE id_alumna=$id_alumna AND id_anio=$id_anio AND id_bimestre=$id_bimestre AND id_area=$id_area");
		
		$prueba_bimestral=@query("SELECT * FROM prueba_bimestral AS pb INNER JOIN sesion_clase AS sc ON pb.id_sesion=sc.id_sesion
			WHERE id_alumna=$id_alumna AND id_anio=$id_anio AND id_bimestre=$id_bimestre AND id_area=$id_area");

		$suma=number_format($promedio_area['promedio_area'],0)+ number_format($prueba_bimestral['nota_prueba'],0); 
		$promedio_final=$suma/2;
		
		?>
		<?php if(!empty($promedio_final)): ?>
			<td class="promedionota"><a href="<?php echo $pagina; ?>codigo=<?php echo $id_alumna;?>&area=<?php echo $id_area; ?>&bimestre=<?php echo $id_bimestre;?>"><?php echo nota_letra($id_nivel,number_format($promedio_final,0));?></a></td>
		<?php else:?>
			<td class="promedionota"><img src="<?php echo URL_APP; ?>recursos/images/icons/cross_circle.png" alt="No hay registro" /></td>
		<?php endif;?>
							
		<?php endfor;?>
						
	</tr>
	<?php endforeach;?>
	</tbody>
	</table>

<?php 
}
?>

<?php 
function promedio_capacidad($id_alumna,$id_area,$id_bimestre,$nombre_anio,$id_anio,$pagina)
{
 
	$alumno_data=@query("SELECT * FROM vista_alumnos_matriculados WHERE YEAR(fecha_matricula)=$nombre_anio AND id_alumna=$id_alumna");
	$id_grado=$alumno_data['id_grado'];
	$nivel=query("SELECT * FROM grado WHERE id_grado=$id_grado");
	$id_nivel=$nivel['id_nivel'];
	
	$nombre_alumno=utf8_encode($alumno_data['apellido_paterno'].' '.$alumno_data['apellido_materno'].', '.$alumno_data['nombres']);

	$periodo_data=@query("SELECT * FROM periodo WHERE id_periodo=$id_bimestre");
	$area_data=@query("SELECT * FROM area WHERE id_area=$id_area");
				
	$promedio_capacidad=@query_data("SELECT * FROM vista_promedio_capacidad
						WHERE id_alumna=$id_alumna AND id_anio=$id_anio AND id_bimestre=$id_bimestre AND id_area=$id_area ");

	if(!empty($promedio_capacidad)):
	foreach($promedio_capacidad as $capacidad):
		$id_capacidad=$capacidad['id_capacidad'];
		$total=query("SELECT COUNT(*) AS total FROM registro_notas AS rn INNER JOIN sesion_clase AS sc ON rn.id_sesion=sc.id_sesion
							WHERE id_alumna=$id_alumna AND id_capacidad=$id_capacidad AND id_area=$id_area AND id_bimestre=$id_bimestre AND id_anio=$id_anio");
		$numero_cantidad[]=$total['total'];
	endforeach;
	$maximo= max($numero_cantidad);
	endif;
	
	info_sistema('<strong>ALUMNO :</strong> '.$nombre_alumno.'<br /><strong>GRADO :</strong> '.utf8_encode($alumno_data['nombre_grado']).' <br /><strong>SECCI&Oacute;N : </strong>'.$alumno_data['nombre_seccion'].'<br/><strong>BIMESTRE :</strong> '.utf8_encode($periodo_data['nombre']).'<br/><strong>AREA ACAD&Eacute;MICA: </strong>'.utf8_encode($area_data['nombre_area']).'');
	?>
				
	<br />
	<table id="tabladetalle3" width="100%">
	<?php if(!empty($promedio_capacidad)):?>
	<thead>
		<th>NRO</th>
		<th>CAPACIDAD</th>
		<th colspan="<?php echo $maximo;?>">NOTAS</th>
		<th>PROMEDIO</th>
	</thead>
	<tbody>
	<?php $i=1;?>
	<?php foreach($promedio_capacidad as $capacidad):?>
	<tr>
		<td align="center"><?php echo $i;?></td>
		<?php 
		$id_capacidad=$capacidad['id_capacidad'];
		$capacidad_data=@query("SELECT * FROM capacidad WHERE id_capacidad=$id_capacidad");
		?>
		<td><?php echo utf8_encode($capacidad_data['nombre_capacidad']);?></td>
		<?php 
			$nota_data=@query_data("SELECT id_registro,id_alumna,rn.id_sesion,id_capacidad,nota,nombre_instrumento,fecha,
				id_docente,id_anio,id_bimestre,id_area,id_grado,id_seccion
				FROM registro_notas AS rn INNER JOIN sesion_clase AS sc ON rn.id_sesion=sc.id_sesion
				WHERE id_alumna=$id_alumna AND id_capacidad=$id_capacidad AND id_area=$id_area AND id_bimestre=$id_bimestre AND id_anio=$id_anio");
		?>
		<?php for($k=0;$k<$maximo;$k++):?>
			<?php if(isset($nota_data[$k]['nota'])):?>
				<td align="center"><a href="<?php echo $pagina;?>codigo=<?php echo $id_alumna;?>&fecha=<?php echo $nota_data[$k]['fecha'];?>&capacidad=<?php echo $nota_data[$k]['id_capacidad'];?>" title="<?php echo utf8_encode($nota_data[$k]['nombre_instrumento']); ?>" ><?php echo number_format($nota_data[$k]['nota'],0);?></a></td>
			<?php else:?>
				<td>&nbsp;</td>
			<?php endif;?>
		<?php endfor;?>
			<td align="center"><?php echo number_format($capacidad['promedio_capacidad'],0);?></td>
	</tr>
		<?php $i++;?>
		<?php endforeach;?>
	</tbody>
	<tfoot>
	<tr>
		<?php 
		$promedio_area=@query("SELECT * FROM vista_promedio_area
			WHERE id_alumna=$id_alumna AND id_anio=$id_anio AND id_bimestre=$id_bimestre AND id_area=$id_area");
		$prueba_bimestral=@query("SELECT * FROM prueba_bimestral AS pb INNER JOIN sesion_clase AS sc ON pb.id_sesion=sc.id_sesion
			WHERE id_alumna=$id_alumna AND id_anio=$id_anio AND id_bimestre=$id_bimestre AND id_area=$id_area");
		
		$suma=number_format($promedio_area['promedio_area'],0)+ number_format($prueba_bimestral['nota_prueba'],0); 
				$promedio_final=$suma/2;
		
		?>
		<td class="totalpromedio" colspan="<?php echo $maximo +2;?>">Promedio Bimestral</td>
		<td class="totalnota"><?php echo number_format($promedio_area['promedio_area'],0);?></td>
	</tr>
	<tr>
		<td class="totalpromedio" colspan="<?php echo $maximo +2;?>">Prueba Bimestral</td>
		<td class="totalnota"><?php echo number_format($prueba_bimestral['nota_prueba'],0);?></td>
	</tr>
	<tr>
		<td class="totalpromedio" colspan="<?php echo $maximo +2;?>">Promedio Final</td>
		<td class="totalnota"><?php echo nota_letra($id_nivel,number_format($promedio_final,0));?></td>
	</tr>
	
	</tfoot>
	<?php else:?>
	<tr>
			<td colspan="5" align="center"><span>NO HAY NING&Uacute;N REGISTRO</span></td>
	</tr>
	<?php endif;?>
	</table>

<?php 
}
?>

<?php 
function detalle_indicador($id_alumna,$id_capacidad,$fecha_nota,$nombre_anio,$id_anio)
{
 
	$alumno_data=@query("SELECT * FROM vista_alumnos_matriculados WHERE YEAR(fecha_matricula)=$nombre_anio AND id_alumna=$id_alumna");
						
	$nombre_alumno=utf8_encode($alumno_data['apellido_paterno'].' '.$alumno_data['apellido_materno'].', '.$alumno_data['nombres']);

	$capacidad_data=query("SELECT * FROM capacidad WHERE id_capacidad=$id_capacidad");						
		
	$indicador_data=@query_data("SELECT id_registro,id_alumna,rn.id_sesion,id_capacidad,nota,nombre_instrumento,fecha,
		id_docente,id_anio,id_bimestre,id_area,id_grado,id_seccion
		FROM registro_notas AS rn INNER JOIN sesion_clase AS sc ON rn.id_sesion=sc.id_sesion
		WHERE id_alumna=$id_alumna AND id_capacidad=$id_capacidad AND fecha='$fecha_nota'");
						
	$id_bimestre=$indicador_data[0]['id_bimestre'];
	$id_area=$indicador_data[0]['id_area'];
	$area_data=query("SELECT * FROM AREA WHERE id_area=$id_area");
	$periodo_data=@query("SELECT * FROM periodo WHERE id_periodo=$id_bimestre");
		
	info_sistema('<strong>ALUMNO :</strong> '. $nombre_alumno.'<br/><strong>GRADO : </strong>'.utf8_encode($alumno_data['nombre_grado']).'<br/><strong>SECCI&Oacute;N : </strong>'.$alumno_data['nombre_seccion'].'<br/><strong>BIMESTRE : </strong>'.utf8_encode($periodo_data['nombre']).'<br/><strong>AREA ACAD&Eacute;MICA : </strong>'.utf8_encode($area_data['nombre_area']).'<br/><strong>CAPACIDAD : </strong> '.utf8_encode($capacidad_data['nombre_capacidad']).' '); 

?>
	<br/>
	<br/>
				
	<table class="tabladetalle">
	<thead>
		<th>NRO</th>
		<th>FECHA</th>
		<th>HORA</th>
		<th>INDICADOR</th>
		<th>NOTA</th>
	</thead>
	<tbody>
	<?php $i=1;?>
	<?php foreach($indicador_data as $indicador):?>
	<tr>
		<td align="center"><?php echo $i;?></td>
		<?php 
			$fecha= $indicador['fecha'];
			setlocale(LC_TIME, "spanish");
			$fecha_nota= strftime("%A, %d de %B del %Y",strtotime($fecha));
			$hora_nota=date("g:i:s A",strtotime($fecha));
		?>
		<td  align="center"><?php echo utf8_encode($fecha_nota);?></td>
		<td  align="center"><?php echo $hora_nota;?></td>
		<td><?php echo utf8_encode($indicador['nombre_instrumento']);?></td>
		<td align="center"><?php echo number_format($indicador['nota'],2); ?></td>
	</tr>
		<?php $i++;?>
	<?php endforeach;?>
	</tbody>
	</table>

<?php 
}
?>


<?php 
function ver_horario($id_grado,$id_institucion)
{
?>
	<div class="publicacion-box">
		<div class="publicacion-box-content">
			<?php $articulo_prueba=@query("SELECT * FROM articulo WHERE id_categoria=10 AND id_grado=$id_grado AND id_institucion=$id_institucion");?>
			<?php if(!empty($articulo_prueba)):?>
				<h1 align="center"><?php echo utf8_encode($articulo_prueba['titulo_articulo']); ?></h1>
				
				<div style="text-align: justify; line-height: 1.5em ">
					<p><?php echo utf8_encode($articulo_prueba['descripcion_articulo']);?><p/>
				</div>
			<?php else:?>
				<h3 align="center">No disponible</h3>
			<?php endif;?>
			
		</div>
	</div>

<?php 
}
?>