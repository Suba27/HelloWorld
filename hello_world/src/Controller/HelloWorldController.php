<?php

namespace Drupal\hello_world\Controller;

class HelloWorldController {
		
	public function viewcontacts(){
	
		$connection = \Drupal::service('database');
		$query = $connection->query("SELECT * FROM {hello_world_contact}");
		$result = $query->fetchAll();
		
		
		$output = '<table><tr><th>Id</th><th>Name</th><th>Email</th><th>Message</th><th>Call me back</th><th>How did you find us</th></tr>';
		
		foreach($result as $row){
			$output .= '<tr>';
			foreach($row as $record){
				$output .= '<td>'.$record.'</td>';
			}
			$output .= '</tr>';
		}	
		$output .= '</table>';
		
		return array(
			'#title' => 'Clients Contact Details',
			'#markup' => $output
		);
	}
}