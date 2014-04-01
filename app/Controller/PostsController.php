<?php

class PostsController extends AppController{

	public $components = array('Paginator');

    public $paginate = array(
        'limit' => 4,
        'order' => array(
            'Post.date' => 'desc'
        )
    );

	public function index(){
		$this->Paginator->settings = $this->paginate;
		$this->set('posts', $this->Paginator->paginate('Post'));
	}
          
	public function view($id) {
		if (!$id) {
			throw new NotFoundException(__('La news n\a pas pu être trouvée'));
		}
		$post = $this->Post->findById($id);
		if (!$post) {
			throw new NotFoundException(__('La news n\a pas pu être trouvée'));
		}
		$this->set('post', $post);
	}

	public function admin_index() {
		$this->Paginator->settings = array(
        	'limit' => 10,
       		 'order' => array(
            	'Post.date' => 'desc'
        	)
   		);
		$this->set('posts', $this->Paginator->paginate('Post'));
	}

	public function admin_add() {
		if ($this->request->is('post')) {
			$this->Post->create();
			$this->request->data['id'] = null;
			if ($this->Post->save($this->request->data)) {
				$this->Session->setFlash(__('La news a bien été sauvegardée'), 'notification', array('type' => 'success'));
				return $this->redirect(array('action' => 'index'));
			}
			else{
				$this->Session->setFlash(__('La news n\'a pas pu être sauvegardée'), 'notification', array('type' => 'warning'));
			}
		}
	}

	public function admin_edit($id = null) {
		if (!$id) {
			throw new NotFoundException(__('La news n\a pas pu être trouvée'));
		}
		$post = $this->Post->findById($id);
		if (!$post) {
			throw new NotFoundException(__('La news n\a pas pu être trouvée'));
		}
		$this->set('idPost', $id);
		if ($this->request->is(array('post', 'put'))) {
			$this->Post->id = $id;
			if ($this->Post->save($this->request->data)) {
				$this->Session->setFlash(__('La modification a été enregistrée avec succés'), 'notification', array('type' => 'success'));
				return $this->redirect(array('action' => 'index'));
			}
			else{
				$this->Session->setFlash(__('La news n\'a pas pu être sauvegardée'), 'notification', array('type' => 'warning'));
			}
		}
		if (!$this->request->data) {
			$this->request->data = $post;
		}
	}

	public function admin_delete($id) {
		if ($this->request->is('get')) {
			throw new MethodNotAllowedException();
		}
		if ($this->Post->delete($id)) {
			$this->Session->setFlash(
				__('Le post a été supprimé.'), 'notification', array('type' => 'success')
			);
			return $this->redirect(array('action' => 'index'));
		}
	}
}