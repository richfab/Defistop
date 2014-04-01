<?php
class UsersController extends AppController {
	
	/* Load the paginator helper for use */
	public $helpers = array('Paginator' => array('Paginator'));
	
	/* Set pagination options */
	public $paginate = array(
		'limit' => 10,
		'order' => array('team_number' => 'asc')
	);

    public function beforeFilter() {
	    parent::beforeFilter();
	    $this->Auth->allow('signup','password','phone_signup','index'); // Letting users signup themselves and retrieve password
	}
	
	public function login() {
	    if ($this->request->is('post')) {
	        if ($this->Auth->login()) {
	            return $this->redirect($this->Auth->redirect());
	        }
	        $this->Session->setFlash("Mot de passe ou email incorrect", 'default', array('class' => 'alert-box radius warning'));
	    }
	}
	
	public function logout() {
	    return $this->redirect($this->Auth->logout());
	}
	
	public function signup() {
		//selectionne les ecole par ordre alphabetique
		$this->set('schools', $this->User->School->find('list', array(
        			'order' => array('School.name' => 'ASC'))));
        
        if ($this->request->is('post')) {
        
        	//on teste si le formulaire d'ecole absente de la liste a été rempli ////option désactivée////
        	/*
	        	if(!empty($this->data['School']['name'])){
        	
        		//on envoie un email a l'admin pour le prévenir de la demande de participation
        		App::uses('CakeEmail','Network/Email');
            	$school_name = $this->data['School']['name'];
				$email = new CakeEmail('to_admin');
				$email->subject('Demande de participation école')
					->emailFormat('html')
					->template('school_request')
					->viewVars(array('school_name'=>$school_name))
					->send();  
	        	$this->Session->setFlash("L'organisateur a été informé de votre demande", 'default', array('class' => 'alert-box radius alert-success'));           
			    return;
        	}*/
        
        	if (!($this->data['User']['password'] === $this->data['User']['password_confirm'])) {
			    $this->Session->setFlash("Les mots de passe ne correspondent pas", 'default', array('class' => 'alert-box radius warning'));               
			    return;
			}
			
            $this->User->create();
            
            //on recupere l'annee en cours
            $this->User->Year->recursive = 0;
            $annee = $this->User->Year->find('first',array('order' => array('Year.id' => 'DESC')));
            $year_id = $annee['Year']['id'];
            $this->request->data['User']['year_id'] = $year_id;
            
            //on recupere le prochain numéro d'equipage pour l'ajouter
            $nextTeamNumber = $this->getNextTeamNumber($year_id);
            //s'il est supérieur à 200, on attribut le numéro 0
            if($nextTeamNumber <= 200){
	            $this->request->data['User']['team_number'] = $nextTeamNumber;
            }
            else{
	            $this->request->data['User']['team_number'] = 0;
            }
            
            if ($this->User->save($this->request->data)) {
            
            	App::uses('CakeEmail','Network/Email');
            	$user = $this->request->data;
				$email = new CakeEmail('default');
				$email->to($user['User']['email'])
					->subject('Inscription au défistop')
					->emailFormat('html')
					->template('signup')
					->viewVars(array('team_name'=>$user['User']['team_name'],'year_id'=>$year_id))
					->send();
				$this->Session->setFlash("Votre inscription a bien été prise en compte. Un email de confirmation vient de vous être envoyé", 'default', array('class' => 'alert-box radius alert-success'));
                return $this->redirect(array('controller'=>'pages','action' => 'home'));
            }
            
            $this->Session->setFlash("Erreur lors de l'inscription", 'default', array('class' => 'alert-box radius warning'));
        }
    }
	
	public function password(){
		if(!empty($this->request->params['named']['token'])){
			$token = $this->request->params['named']['token'];
			$token = explode('-',$token);
			$user = $this->User->find('first',array('conditions'=>
												array('User.id'=>$token[0],
														'MD5(User.password)'=>$token[1])));
			if($user){
				App::uses('AuthComponent', 'Controller/Component');
			
				$this->User->id = $user['User']['id'];
				$password = substr(md5(uniqid(rand(),true)),0,8);
				$this->User->saveField('password',$password);
				$this->Session->setFlash("Voici votre nouveau mot de passe : $password. Vous pouvez le changer dans votre compte.", 'default', array('class' => 'alert-box radius alert-success'));
			}
			else{
				$this->Session->setFlash("Le lien n'est pas valide", 'default', array('class' => 'alert-box radius warning'));
			}
		}
		
		if ($this->request->is('post')) {
			$user = $this->User->find('first',array('conditions'=>
												array('email'=>$this->request->data['User']['email'])));
			if(!$user){
				$this->Session->setFlash("Aucun utilisateur ne correspond à cet email", 'default', array('class' => 'alert-box radius warning'));
			}
			else{
				App::uses('CakeEmail','Network/Email');
				$link = array('controller'=>'users','action'=>'password','token'=>$user['User']['id'].'-'.md5($user['User']['password']));
				$email = new CakeEmail('default');
				$email->to($user['User']['email'])
					->subject('Nouveau mot de passe')
					->emailFormat('html')
					->template('password')
					->viewVars(array('team_name'=>$user['User']['team_name'],'link'=>$link))
					->send();
				$this->Session->setFlash("Un email avec un lien pour réinitialiser votre mot de passe vient de vous être envoyé", 'default', array('class' => 'alert-box radius alert-success'));
			}
		}
	}
	
	//methode qui retourne vrai si l'utilisateur connecté a payé, false sinon
	public function userHasPayed($user_id = null){
		
		if($user_id != null){
			$user = $this->User->find('first', array('conditions' => array('User.id' => $user_id), 'fields' => array('User.payed')));
			return $user['User']['payed'];
		}
		else{
			return false;
		}
	}
	
	//methode qui retourne vrai si l'utilisateur est de l'année en cours
	public function userBelongsToCurrentYear($user_id = null){
		
		$year = $this->User->Year->find('first',array('order' => array('Year.id' => 'DESC'),'fields' => 'Year.id'));
		$year_id = $year['Year']['id'];
		
		if($user_id != null){
			$user = $this->User->find('first', array('conditions' => array('User.id' => $user_id), 'fields' => array('User.year_id')));
			if($user['User']['year_id'] == $year_id){
				return true;
			}
			else{
				return false;
			}
		}
		else{
			return false;
		}
	}
	
	//fonction qui retourne vrai si (team_number,year_id) est unique (ne compte pas le user_id passé en paramètre)
	private function isUniqueTeamNumber($year_id = null, $team_number = null, $user_id = null){
		if($year_id != null && $team_number != null){
			$count = $this->User->find('count', array('conditions' => array('User.role' => 'user', 'User.id !=' => $user_id, 'User.team_number' => $team_number, 'User.year_id' => $year_id)));
			return(!$count);
		}
		else{
			return true;
		}
	}
	
	//fonction privée qui retourne le numéro d'équipe le plus élevé de l'année passée en paramètre (+1)
	private function getNextTeamNumber($year_id = null){
		if($year_id != null){
			$user = $this->User->find('first', array('conditions' => array('User.year_id' => $year_id), 
													'fields' => array('User.team_number'),
													'order' => array('User.team_number' => 'DESC')));
			if($user){
				return $user['User']['team_number']+1;
			}
			else{
				//premiere inscription de l'année
				return 1;
			}
		}
		else{
			return 0;
		}
	}
    
    public function profile() {
    	App::uses('AuthComponent', 'Controller/Component');
		
		if($this->Auth->loggedIn()){
			$id = $this->Auth->user('id');
			$this->User->id = $id;
		}
		else{
			return $this->redirect(array('action' => 'login'));
		}
		//selectionne les ecole par ordre alphabetique
        $this->set('schools', $this->User->School->find('list', array(
    			'order' => array('School.name' => 'ASC'))));
    			
    	//pour le status de l'inscription
    	$this->User->recursive = 0;
        $this->set('user', $this->User->find('first', array(
    			'conditions' => array('User.id' => $id),
    			'fields' => array('User.payed'))));
    			
        if (!$this->User->exists()) {
            throw new NotFoundException(__("Cet équipage n'existe pas"));
        }
        if ($this->request->is('post') || $this->request->is('put')) {
            if ($this->User->save($this->request->data)) {
                $this->Session->setFlash("Les modifications ont bien été enregistrées", 'default', array('class' => 'alert-box radius alert-success'));
                return $this->redirect(array('action' => 'profile'));
            }
            $this->Session->setFlash("Erreur lors de l'enregistrement", 'default', array('class' => 'alert-box radius warning'));
        } else {
            $this->request->data = $this->User->read(null, $id);
            unset($this->request->data['User']['password']);
        }
    }
    
    public function admin_profile() {
    	App::uses('AuthComponent', 'Controller/Component');
		
		if($this->Auth->loggedIn()){
			$id = $this->Auth->user('id');
			$this->User->id = $id;
		}
		else{
			return $this->redirect(array('action' => 'login','admin'=>false,'super'=>false));
		}
		//selectionne les ecole par ordre alphabetique
        $this->set('schools', $this->User->School->find('list', array(
    			'order' => array('School.name' => 'ASC'))));
    			
        if (!$this->User->exists()) {
            throw new NotFoundException(__("Cet équipage n'existe pas"));
        }
        if ($this->request->is('post') || $this->request->is('put')) {
            if ($this->User->save($this->request->data)) {
                $this->Session->setFlash("Les modifications ont bien été enregistrées", 'default', array('class' => 'alert-box radius alert-success'));
                return $this->redirect(array('action' => 'profile'));
            }
            $this->Session->setFlash("Erreur lors de l'enregistrement", 'default', array('class' => 'alert-box radius warning'));
        } else {
            $this->request->data = $this->User->read(null, $id);
            unset($this->request->data['User']['password']);
        }
    }
    
    //page qui peut etre appelée a partir de la page profile et qui permet de changer son mot de passe
    public function change_password() {
    	App::uses('AuthComponent', 'Controller/Component');
		
		if($this->Auth->loggedIn()){
			$id = $this->Auth->user('id');
			$this->User->id = $id;
		}
		else{
			return $this->redirect(array('action' => 'login','admin'=>false,'super'=>false));
		}
        if (!$this->User->exists()) {
            throw new NotFoundException(__("Cet équipage n'existe pas"));
        }
        if ($this->request->is('post') || $this->request->is('put')) {
        	if (!($this->data['User']['password'] === $this->data['User']['password_confirm'])) {
			    $this->Session->setFlash("Les mots de passe ne correspondent pas", 'default', array('class' => 'alert-box radius warning'));            
			    return;
			}
            if ($this->User->save($this->request->data)) {
                $this->Session->setFlash("Les modifications ont bien été enregistrées", 'default', array('class' => 'alert-box radius alert-success'));
                if($this->Auth->user('role')=='user'){
                	return $this->redirect(array('action' => 'profile'));
                }
                else{
	                return $this->redirect(array('action' => 'profile','admin'=>true,'super'=>false));
                }
            }
            $this->Session->setFlash("Erreur lors de l'enregistrement", 'default', array('class' => 'alert-box radius warning'));
        } else {
            $this->request->data = $this->User->read(null, $id);
            unset($this->request->data['User']['password']);
        }
    }
    
    public function index() {
    
    	/* Set pagination options */
		$this->paginate = array(
			'User' => array(
				'order' => array(
					'year_id' => 'DESC',
					'School.name' => 'ASC'
				),
				'conditions' => array('role' => 'user'),
				'limit' => 1000,
				'recursive' => 0
			)
		);
		
		//recupere les années
		$years = $this->User->Year->find('all',array('order' => array('Year.id' => 'DESC'), 'recursive' => 0));
        $this->set('years',$years);
        
        //on ne recupere que les equipes, pas l'admin
        $this->set('users', $this->Paginate('User'));
    }
    
    public function super_index($year_id = null) {
    
    	//on inclut le script pour le switch
    	$this->set('jsIncludes',array('switch'));
    
        $this->User->recursive = 0;

        $this->set('users', $this->Paginate('User',array('role' => 'user','year_id' => $year_id)));
        
        $this->User->Year->recursive = 0;
        $annee = $this->User->Year->find('first',array('conditions' => array('Year.id' => $year_id)));

        $this->set('year',$annee);
    }

    public function admin_index($year_id = null) {
    
        $this->User->recursive = 0;
        //on ne recupere que les equipes, pas l'admin
        //recupere l'id de l'ecole geree par l'admin
        $school_id = AuthComponent::user('school_id');
        //ce n'est pas le super admin donc on ne recupere que les participants de l'ecole geree
        
        //le nom de l'ecole pour le breadcrumbs
        $this->User->School->recursive = 0;
        $school = $this->User->School->find('first',array('conditions' => array('School.id' => $school_id)));
        $this->set('school',$school);

        $this->set('users', $this->Paginate('User',array('role' => 'user','year_id' => $year_id,array('User.school_id' => $school_id))));
        
        $this->User->Year->recursive = 0;
        $annee = $this->User->Year->find('first',array('conditions' => array('Year.id' => $year_id)));

        $this->set('year',$annee);
    }
    
    public function admin_add($year = null) {
    	//selectionne les ecole par ordre alphabetique
		$this->set('schools', $this->User->School->find('list', array(
        			'order' => array('School.name' => 'ASC'))));
        //on recupère le prochain numéro d'équipage
        $this->set('nextTeamNumber', $this->getNextTeamNumber($year));
        			
        if ($this->request->is('post')) {
        	if (!($this->data['User']['password'] === $this->data['User']['password_confirm'])) {
			    $this->Session->setFlash("Les mots de passe ne correspondent pas", 'default', array('class' => 'alert-box radius warning'));       
			    return;
			}
			//check que le numéro d'équipage est unique pour cette année
			if(!$this->isUniqueTeamNumber($year, $this->request->data['User']['team_number'], null)){
				$this->Session->setFlash("Un équipage possède déjà ce numéro cette année", 'default', array('class' => 'alert-box radius warning'));       
			    return;
			}
            $this->User->create();
            if ($this->User->save($this->request->data)) {
            
            	App::uses('CakeEmail','Network/Email');
            	$user = $this->request->data;
				$email = new CakeEmail('default');
				$email->to($user['User']['email'])
					->subject('Inscription au défistop')
					->emailFormat('html')
					->template('signup')
					->viewVars(array('team_name'=>$user['User']['team_name'],'year_id'=>$user['User']['year_id']))
					->send();
				$this->Session->setFlash("Les modifications ont bien été enregistrées. Un email a été envoyé au participant", 'default', array('class' => 'alert-box radius alert-success'));
                
                //selon que l'utilisateur soit admin ou super, on redirige vers la page des equipages qui correspond
                if($this->Auth->user('role') == 'super'){
			        return $this->redirect(array('action' => 'index',$year,'super'=>true,'admin'=>false));
			    }
			    else if($this->Auth->user('role') == 'admin'){
			        return $this->redirect(array('action' => 'index',$year,'super'=>false,'admin'=>true));
			    }
            }
            $this->Session->setFlash("Erreur lors de l'enregistrement", 'default', array('class' => 'alert-box radius warning'));
        }
    }

    public function admin_edit($year = null, $id = null) {
        $this->User->id = $id;
        //selectionne les ecole par ordre alphabetique
        $this->set('schools', $this->User->School->find('list', array(
    			'order' => array('School.name' => 'ASC'))));
        if (!$this->User->exists()) {
            throw new NotFoundException(__("Cet équipage n'existe pas"));
        }
        if ($this->request->is('post') || $this->request->is('put')) {
        
        	//check que le numéro d'équipage est unique pour cette année
			if(!$this->isUniqueTeamNumber($year, $this->request->data['User']['team_number'], $id)){
				$this->Session->setFlash("Un équipage possède déjà ce numéro cette année", 'default', array('class' => 'alert-box radius warning'));       
			    return;
			}
			
			//importation et instantiation du controller Positions pour l'appel a la methode update_distance() pour les pénalités
			App::import('Controller', 'Positions');
	    	$PositionsController = new PositionsController;
        
            if ($this->User->save($this->request->data) && $PositionsController->update_distance($id)) {
                $this->Session->setFlash("Les modifications ont bien été enregistrées", 'default', array('class' => 'alert-box radius alert-success'));
                //selon que l'utilisateur soit admin ou super, on redirige vers la page des equipages qui correspond
                if($this->Auth->user('role') == 'super'){
			        return $this->redirect(array('action' => 'index',$year,'super'=>true,'admin'=>false));
			    }
			    else if($this->Auth->user('role') == 'admin'){
			        return $this->redirect(array('action' => 'index',$year,'super'=>false,'admin'=>true));
			    }
            }
            $this->Session->setFlash("Erreur lors de l'enregistrement", 'default', array('class' => 'alert-box radius warning'));
        } else {
            $this->request->data = $this->User->read(null, $id);
            unset($this->request->data['User']['password']);
        }
    }

    public function admin_delete($id = null) {
        $this->request->onlyAllow('post');

        $this->User->id = $id;
        if (!$this->User->exists()) {
            throw new NotFoundException(__("Cet équipage n'existe pas"));
        }
        if ($this->User->delete()) {
        	$this->Session->setFlash("L'équipage a bien été supprimé", 'default', array('class' => 'alert-box radius alert-success'));
            return $this->redirect($this->referer());
        }
        $this->Session->setFlash("L'équipage n'a pas pu être supprimé", 'default', array('class' => 'alert-box radius warning'));
        return $this->redirect($this->referer());
    }
}
?>