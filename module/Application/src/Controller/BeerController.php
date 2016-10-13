<?php
namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class BeerController extends AbstractActionController{
	public $tableGateway;
	public function __construct($tableGateway){
		$this->tableGateway = $tableGateway;
	}
	public function indexAction(){
		$beers = $this->tableGateway->select()->toArray();
			return new ViewModel(['beers'=> $beers]);
	}
	public function deleteAction(){
		$id = (int) $this->params()->fromRoute('id');
		$beer = $this->tableGateway->select(['id' => $id]);
		if(cont($beer) == 0){
			throw new \Exception("Beer not found", 404);
		}
		$this->tableGateway->delete(['id' => $id]);
		return $this->redirect()->toUrl('/beer');
	}
	public function createAction(){
		$form = new \Application\Form\Beer;
		$form->setAttribute('action', '/beer/create');
		$request = $this->getRequest();

		if($request->isPost()){
			$beer = new \Application\Model\Beer;
			$form->setInputFilter($beer->getInputFilter());
			$form->setData($request->getPost());
			if($form->isValid()){
				$data = $form->getData();
				unset($data['send']);
				$this->tableGateway->insert($data);
				return $this->redirect()->toUrl('/beer');
			}
		}
		$view = new ViewModel(['form' => $form]);
		$view->setTemplate('application/beer/save.phtml');
		return $view;
	}
	public function editAction(){
		$form = new \Application\Form\Beer;
		$form->get('send')->setAttribute('action','/beer/edit');

		$form->add([
			'name' => 'id',
			'type' => 'hidden',

		]);
		view = new ViewModel(['form' => $form]);
		$view->setTemplate('application/beer/save.phtml');

		$request = $this->getRequest();

		if($request->isPost()){
			$beer = new \Application\Model\Beer;
			$form->setInputFilter($beer->getInputFilter());
			$form->setData($request->getPost());
			if(!$form->isValid()){
				return $view;
			}
			$data = $form->getData();
			$this->tableGateway->update($data, 'id = '.$data['id']);

			return $this->redirect()->toUrl('/beer');

		}

		$id = (int) $this->params()->fromRoute('id',0);
		$beer = $this=>tableGateway->select(['id' => $id])->toArray();
		if(count($beer) == 0){
			throw new \Exception("Beer not found", 404);
		}

		$form->get('id')->setValue($beer[0]['id']);
		$form->get('name')->setValue($beer[0]['name']);
		$form->get('style')->setValue($beer[0]['style']);
		$form->get('img')->setValue($beer[0]['img']);

		return $view;
	}
}