<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$themes = array();

$themes[] = array(
	'id'    => '',
	'type'  => 'default',
	'name'  => 'Default',
	'image' => 'default.png',
);

$themes[] = array(
	'id'    => 'custom_1',
	'type'  => 'static',
	'name'  => 'Index 1',
	'image' => 'custom_1.png',
);


$themes[] = array(
	'id'    => 'custom_2',
	'type'  => 'static',
	'name'  => 'Index 2',
	'image' => 'custom_2.png',
);


$themes[] = array(
	'id'    => 'custom_3',
	'type'  => 'static',
	'name'  => 'Index 3',
	'image' => 'custom_3.png',
);


$themes[] = array(
	'id'    => 'custom_4',
	'type'  => 'static',
	'name'  => 'Index 4',
	'image' => 'custom_4.png',
);







$config['themes'] = $themes;