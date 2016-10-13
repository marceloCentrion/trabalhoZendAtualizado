<?php
namespace Application\Model;

use Zend\InputFilter\InputFilter;

class Beer{
	public $id;
	public $name;
	public $style;
	public $img;

	//configura os filtros dos campos da classe
	//@retyrb Zend\InputFilter\InputFilter
	public function getInputFilter(){
		$inputFilter = new InputFilter();
		$inputFilter->add(array(
			'name'     =>'id',
			'required' =>false,
			'filters'  =>array(
				array('name' => 'Int'),
			),
		));
		$inputFilter->add(array(
			'name'     =>'name',
			'required' => true,
			'filters'  => array(
				array('name' => 'StripTags'),
				array('name' => 'StringTrim'),
			),
			'validators' => array(
				array(
					'name'    => 'StringLenght',
					'options' => array(
						'encoding' => 'UTF-8',
						'min'      => 1,
						'max'      =>100,
					),
				),
			),
		));
		$inputFilter->add(array(
			'name'   => 'img',
			'required' => false,
			'filters'  => array(
				array('name' => 'StripTags'),
				array('name' => 'StringTrim'),
			),
		));
	}
}