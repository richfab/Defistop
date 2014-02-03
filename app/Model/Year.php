<?php
App::uses('AuthComponent', 'Controller/Component');

class Year extends AppModel {

	public $hasMany = 'User';

    public $validate = array(
        'id' => array(
            'rule'       => 'numeric',
            'message'    => 'Entrez une année valide',
            'required' => true
        )
    );
    
    public function beforeSave($options = array()) {

	    return true;
	}
}
?>