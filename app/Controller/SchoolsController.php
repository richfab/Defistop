<?php
class SchoolsController extends AppController {
    
    public function super_add() {
    
    	//on inclut le script google maps pour l'autocomplete des lieux
    	$this->set('jsIncludes',array('http://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&libraries=places','address_autocomplete'));
    
        if ($this->request->is('post')) {
        	if (!($this->data['User']['password'] === $this->data['User']['password_confirm'])) {
			    $this->Session->setFlash("Les mots de passe ne correspondent pas", 'default', array('class' => 'alert-box radius warning'));                
			    return;
			}
			//creation de l'ecole
			$this->School->create();
			if ($this->School->save($this->request->data)){
				//recuperation de l'id de l'ecole
				$school_name = $this->request->data['School']['name'];
				$school = $this->School->find('first',array('order' => array('School.created' => 'DESC'),
																		'fields' => 'School.id',
																		'conditions' => array('School.name' => $school_name)));
	            $school_id = $school['School']['id'];
	            
	            $this->School->User->create();
	            //on ajoute l'id de l'ecole a l'utiisateur
	            $this->request->data['User']['school_id'] = $school_id;
	            //on ajoute la derniere annee de la bdd a l'utilisateur
	            $year = $this->School->User->Year->find('first',array('order' => array('Year.id' => 'DESC'),
																		'fields' => 'Year.id'));
				$this->request->data['User']['year_id'] = $year['Year']['id'];
				
	            if ($this->School->User->save($this->request->data)) {
	                $this->Session->setFlash("Les modifications ont bien été enregistrées", 'default', array('class' => 'alert-box radius alert-success'));
	                return $this->redirect(array('controller'=>'years','action' => 'index'));
	            }	
			}
			
            $this->Session->setFlash("Erreur lors de l'enregistrement", 'default', array('class' => 'alert-box radius warning'));
        }
    }

    public function super_edit($school_id = null, $user_id = null) {
    
    	//on inclut le script google maps pour l'autocomplete des lieux
    	$this->set('jsIncludes',array('https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&libraries=places','address_autocomplete'));
    
        $this->School->User->id = $user_id;
        if (!$this->School->User->exists()) {
            throw new NotFoundException(__("Cette école n'existe pas"));
        }
        if ($this->request->is('post') || $this->request->is('put')) {
        	$this->School->id = $school_id;
        	//si on veut attribuer le role d'admin, on doit verifier qu'un super admin est bien présent dans la table
        	if($this->request->data['User']['role']=='admin'){
        		if($this->School->User->find('count',array('conditions' => array('User.role' => 'super','User.id !=' => $user_id)))==0){
	        		$this->Session->setFlash("Pour donner le role de responsable école, attribuer d'abord le role d'organisateur à une autre personne", 'default', array('class' => 'alert-box radius warning'));
	        		return;
        		}
        	}
            if ($this->School->User->save($this->request->data)&&$this->School->save($this->request->data)) {
                $this->Session->setFlash("Les modifications ont bien été enregistrées", 'default', array('class' => 'alert-box radius alert-success'));
                return $this->redirect(array('controller'=>'years','action' => 'index'));
            }
            $this->Session->setFlash("Erreur lors de l'enregistrement", 'default', array('class' => 'alert-box radius warning'));
        } else {
        	$user = $this->School->User->read(null, $user_id);
        	if($user['User']['role']==='user'){
        		$this->Session->setFlash("Une erreur s'est produite", 'default', array('class' => 'alert-box radius warning'));
                return $this->redirect(array('controller'=>'years','action' => 'index'));
        	}
            $this->request->data = $this->School->User->read(null, $user_id);
            unset($this->request->data['User']['password']);
        }
    }

    //ATTENTION ici l'id est celui de l'ecole
    public function super_delete($id = null, $user_id = null) {
        $this->request->onlyAllow('post');

        $this->School->id = $id;
        if (!$this->School->exists()) {
            throw new NotFoundException(__("Cette école n'existe pas"));
        }
        //si on veut supprimer un admin on doit verifier qu'il y en a plus qu'un
		if($this->School->User->find('count',array('conditions' => array('User.role' => 'super','User.id !=' => $user_id)))==0){
    		$this->Session->setFlash("Pour supprimer cet organisateur, attribuer d'abord le role d'organisateur à une autre personne", 'default', array('class' => 'alert-box radius warning'));
    		return $this->redirect($this->referer());
		}
        if ($this->School->delete()) {
            $this->Session->setFlash("L'école a bien été supprimée", 'default', array('class' => 'alert-box radius alert-success'));
            return $this->redirect($this->referer());
        }
        $this->Session->setFlash("L'école n'a pas pu être supprimée", 'default', array('class' => 'alert-box radius warning'));
        return $this->redirect($this->referer());
    }
}
?>