<?php

class ContactsController extends AppController{

	public function index(){
		$contacts = $this->Contact->find('all');
		foreach($contacts as $key => $contact):
			$contacts[$key]['Contact']['person'] = $contact['Contact']['firstname'] . ' ' . $contact['Contact']['name'];
			$contacts[$key]['Contact']['mail'] = 'mailto:' . $contact['Contact']['mail'];
		endforeach;
		$this->set('contacts', $contacts);
	}
}