<?php
/**
*@file
*/

namespace Drupal\hello_world\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

class ContactForm extends FormBase {
	/**
	*{@inheritdoc}
	*/
	 public function getFormId() {
		// Unique ID of the form.
		return 'contact_form';
	}
  
	/**
	*{@inheritdoc}
	*/
	public function buildForm(array $form, FormStateInterface $form_state) {
    
		// Create a $form API array.
		$form['name'] = array(
		  '#type' => 'textfield',
		  '#title' => $this->t('Name'),
		);
		
		$form['email'] = array(
		  '#type' => 'email',
		  '#required' => true,
		  '#title' => $this->t('Email'),
		);
		
		$form['message'] = array(
		  '#type' => 'textarea',
		  '#title' => $this->t('Message'),
		  '#default_value' => ''
		);
		
		$form['call_me'] = array(
		  '#type' => 'checkbox',
		  '#title' => $this->t('Call me back'),
		);
		
		$form['find_us'] = array(
		  '#type' => 'select',
		  '#title' => $this->t('How did you find us'),
		  '#options' => [
			'google' => $this->t('Google'),
			'advert' => $this->t('Advert'),
			'newspaper' => $this->t('Newspaper'),
		  ]
		);
		
		$form['Submit'] = array(
		  '#type' => 'submit',
		  '#value' => $this->t('Submit'),
		);
		return $form;
	}
  
	/**
	*{@inheritdoc}
	*/
	public function validateForm(array &$form, FormStateInterface $form_state) {
	
		// Validate submitted form data.
		if (!preg_match("/^[a-zA-Z ]*$/",$form_state->getValue('name'))){
			$form_state->setErrorByName('name', $this->t('Only letters and white space allowed'));
		}
		
		if (empty($form_state->getValue('email'))) {
			$form_state->setErrorByName('email', $this->t('Email is required'));			
		} else {
			// check if e-mail address is well-formed
			if (!filter_var($form_state->getValue('email'), FILTER_VALIDATE_EMAIL)) {
				$form_state->setErrorByName('email', $this->t('Invalid email format'));
			}
		}
	}
	 

	/**
	*{@inheritdoc}
	*/
	 public function submitForm(array &$form, FormStateInterface $form_state) {
		
		//debug($form_state);
		
		//sanitize submitted form message.		
		$sanitized_messaage = filter_var($form_state->getValue('message'), FILTER_SANITIZE_STRING);
		$fields = array(
			'name' => $form_state->getValue('name'),
			'email' =>  $form_state->getValue('email'),
			'message' =>  $sanitized_messaage,
			'call_me' =>  $form_state->getValue('call_me'),
			'find_us' =>  $form_state->getValue('find_us')			
		);
		
		$call_me = ($fields['call_me'] == 0?'No':'Yes');
		
		// Handle submitted form data.
		$dk = db_insert('hello_world_contact')->fields($fields)->execute();
				
		$to = "giny279@yahoo.com";
		//$to = "code@lightflows.co.uk";
		$subject = "Need to contact client";
		$msg = "Contact information of the client <br />";
		$msg .= "Name: ".$fields['name']."<br />";
		$msg .= "Email: ".$fields['email']."<br />";
		$msg .= "Message: ".$fields['message']."<br />";
		$msg .= "Call me back: ".$call_me."<br />";
		$msg .= "How did you find us: ".$fields['find_us']."<br />";
		
		$headers = "MIME-Version: 1.0" . "\r\n";
		$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
		$headers .= "From: webmaster@example.com";

		mail($to,$subject,$msg,$headers);
	}

}



