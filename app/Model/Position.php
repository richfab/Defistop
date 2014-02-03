<?php
App::uses('AuthComponent', 'Controller/Component');

class Position extends AppModel {

	public $belongsTo = 'User';

    public $validate = array(
        'lieu' => array(
            'maxLength' => array(
                'rule'    => array('maxLength', '50'),
                'message' => 'Le lieu doit comporter 50 caractères au maximum',
                'allowEmpty' => false
            )
        ),
        'created' => array(
            'dateformat' => array(
                'rule'    => 'notEmpty',
                'message' => 'La date doit être au format 07-04-2014 13:21',
                'allowEmpty' => false
            )
        ),
        'commentaire' => array(
            'maxLength' => array(
                'rule'    => array('maxLength', '255'),
                'message' => 'Le commentaire doit comporter 255 caractères au maximum',
                'allowEmpty' => true
            )
        ),
        'lat' => array(
            'isnumeric' => array(
                'rule'    => 'numeric',
                'message' => 'Veuillez renseigner un lieu proposé dans la liste au moment de la saisie'
            )
        ),
        'lon' => array(
            'isnumeric' => array(
                'rule'    => 'numeric',
                'message' => 'Veuillez renseigner un lieu proposé dans la liste au moment de la saisie'
            )
        )
    );
    
    public function beforeSave($options = array()) {
	    return true;
	}
}
?>