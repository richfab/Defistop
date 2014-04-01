<?php
class YearsController extends AppController {

    public $helpers = array('Html', 'Form');
    
    /* Set pagination options */
	public $paginate = array(
		'limit' => 20,
		'order' => array('School.name' => 'ASC','Year.id' => 'DESC')
	);
	
	public function admin_index(){
		//user id = 1 reservé au developpeur du site
		$this->set('years', $this->Paginate('Year'));
        $this->set('users', $this->Paginate('User',array('role' => array('admin','super'),'User.id !=' => 1)));
	}

    public function super_index() {
    	//on ajoute l'année courante dans la table des années. l'année est clé primaire, elle est unique (et donc ajoutée que si elle n'existe pas encore)
    	$this->super_add();
    	//user id = 1 reservé au developpeur du site
        $this->set('years', $this->Paginate('Year'));
        $this->set('users', $this->Paginate('User',array('role' => array('admin','super'),'User.id !=' => 1)));
    }
    
    public function super_add() {
    	//on ajoute l'annee en cours (annee du serveur)
    	$this->request->data['Year']['id']=date("Y");
        $this->Year->create();
        $this->Year->save($this->request->data);
    }
    
    public function super_start($year = null) {
    
    	//on affiche pas le layout
    	$this->layout = false;
    
    	$this->request->onlyAllow('ajax');
    	
    	//on stop toutes les courses (une seule est en cours)
	    $this->super_stop();
	    
	    //on démarre la course passée en parametre
		$this->Year->id = $year;
		$this->Year->saveField('en_cours', true);
		
		//on recupere le statut de la course
		$annee = $this->Year->findById($year);
		$this->set('year',$annee);
    }
    
    public function super_stop($year = null) {
    
    	//on affiche pas le layout
    	$this->layout = false;
    
		//$this->request->onlyAllow('ajax');
		    
	    $this->Year->updateAll(
		    array('Year.en_cours' => false)
		);
		
		//on recupere le statut de la course
		$annee = $this->Year->findById($year);
		$this->set('year',$annee);
    }
}
?>