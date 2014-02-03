<?php
class School extends AppModel {

	public $hasMany = array(
        'User' => array(
            'className' => 'User',
            'order' => 'User.team_number ASC'
        )
    );
	
	public $validate = array(
        'name' => array(
            'between' => array(
                'rule'    => array('between', 1, 20),
                'message' => 'Le nom doit comporter entre 1 et 20 caractères',
                'allowEmpty' => false
            ),
            'isUnique' => array(
		        'rule'    => 'isUnique',
		        'message' => 'Ce nom d\'école est déjà utilisé'
		    )
        ),
        'lieu' => array(
            'maxLength' => array(
                'rule'    => array('maxLength', '255'),
                'message' => 'Le lieu doit comporter 255 caractères au maximum',
                'allowEmpty' => false
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
    	//majuscules au nom
	    if (isset($this->data[$this->alias]['name'])) {
	        $this->data[$this->alias]['name'] = ucwords(strtolower($this->data[$this->alias]['name']));
	    }
	    return true;
	}
}
?>