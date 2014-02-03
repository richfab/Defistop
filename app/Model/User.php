<?php
App::uses('AuthComponent', 'Controller/Component');

class User extends AppModel {

	public $belongsTo = array('Year','School');
	public $hasMany = array(
        'Position' => array(
            'className' => 'Position',
            'order' => 'Position.created ASC'
        )
    );
	
	public $validate = array(
    	'email' => array(
        	'format' => array(
	            'rule'       => 'email',
	            'message'    => 'Entrez un email valide',
	            'allowEmpty' => false
            ),
            'isUnique' => array(
		        'rule'    => 'isUnique',
		        'message' => 'Cet email est déjà utilisé'
		    )
        ),
        'password' => array(
            'rule'    => array('minLength', '6'),
            'message' => 'Le mot de passe doit comporter 6 caractères au minimum',
            'allowEmpty' => false
        ),
        'password_confirm' => array(
            'rule'    => array('minLength', '6'),
            'message' => 'Le mot de passe doit comporter 6 caractères au minimum',
            'allowEmpty' => false
        ),
        'team_name' => array(
            'between' => array(
                'rule'    => array('maxLength', '20'),
                'message' => 'Le nom d\'équipage doit comporter 20 caractères au maximum',
                'allowEmpty' => false
            ),
            'isUnique' => array(
		        'rule'    => 'isUnique',
		        'message' => 'Ce nom d\'équipage est déjà utilisé'
		    )
        ),
        'mobile_phone' => array(
        	'between' => array(
                'rule'    => array('between', 10, 10),
                'message' => 'Le numéro de portable doit être du type 0612345678',
                'allowEmpty' => false
            ),
            'numeric' => array(
                'rule'       => 'numeric',
                'message'    => 'Le numéro de portable doit être du type 0612345678'
            )           
        ),
        'team_number' => array(
        	'number' => array(
        		'rule'       => array('range', -1, 201), //le numero sert a l'inscription si le nextTeamNumber disponible est supérieur à 200
            	'message'    => 'Entrez un numéro compris entre 1 et 200')
        ),
        'member_name_one' => array(
            'maxLength' => array(
                'rule'    => array('maxLength', '20'),
                'message' => 'Le nom doit comporter 20 caractères au maximum',
                'allowEmpty' => false
            )
        ),
        'member_name_two' => array(
            'maxLength' => array(
                'rule'    => array('maxLength', '20'),
                'message' => 'Le nom doit comporter 20 caractères au maximum',
                'allowEmpty' => false
            )
        ),
        'distance_penalty' => array(
        	'number' => array(
        		'rule'       => array('range', -1, 10000), 
            	'message'    => 'La pénalité de distance est un nombre positif en km',
            	'allowEmpty' => false)
        )
    );
    
    //chiffrage du mot de passe avant enregistrement
    public function beforeSave($options = array()) {
	    if (isset($this->data[$this->alias]['password'])) {
	        $this->data[$this->alias]['password'] = AuthComponent::password($this->data[$this->alias]['password']);
	    }
	    if (isset($this->data[$this->alias]['team_name'])) {
	        $this->data[$this->alias]['team_name'] = ucwords(strtolower($this->data[$this->alias]['team_name']));
	    }
	    if (isset($this->data[$this->alias]['member_name_two'])) {
	        $this->data[$this->alias]['member_name_two'] = ucwords(strtolower($this->data[$this->alias]['member_name_two']));
	    }
	    if (isset($this->data[$this->alias]['member_name_one'])) {
	        $this->data[$this->alias]['member_name_one'] = ucwords(strtolower($this->data[$this->alias]['member_name_one']));
	    }
	    return true;
	}
}
?>