<?php
/**
*@file
*Code for hello_world module
*/

 /**
 * Implements hook_menu().
 */
 function hello_world_menu(){
	$items = array();
	$items['admin/config/system/contacts'] = array(
		'title' => 'Contacts',
		'description' => 'Conacts Information',
		'route_name' => 'contacts.view',
	};
	return $items;
 }

