<?php

function sudaca_url_segmentos(){
	$segmentos = array(
	      'web' => '',
	      'cart' => '',
	      '_' => ' ',
	      'add' => 'nuevo',
	      'edit' => 'editar',
	      'groups' => 'grupos',
	      'auth' => 'usuarios',
	      'user' => 'usuario',
	      'change password' => 'Cambiar Password',
	      'codegen' => 'CRUD',
	      'auth' => 'Usuario'
  	);
  	return $segmentos;
}

function formatdate($date)
{
	return date("Y-m-d", strtotime($date));
}