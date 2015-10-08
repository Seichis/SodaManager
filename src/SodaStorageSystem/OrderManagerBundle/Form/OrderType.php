<?php

namespace SodaStorageSystem\OrderManagerBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class OrderType extends AbstractType {
	public function buildForm(FormBuilderInterface $builder,array $option){
		$builder->add('title')->add('quantity');
	}

    /**
    *tell the form type which entity is going to be using. we tell it to use soda entity
    */

	public function setDefaultOptions(OptionsResolverInterface $resolver){
		$resolver->setDefaults(array('data_class'=>'SodaStorageSystem\OrderManagerBundle\Entity\Order'));
	}

	public function getName(){
		return 'sodastoragesystem_ordermanagerbundle_order';
	}
}