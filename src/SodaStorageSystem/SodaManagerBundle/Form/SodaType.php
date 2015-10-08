<?php

namespace SodaStorageSystem\SodaManagerBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SodaType extends AbstractType {
	public function buildForm(FormBuilderInterface $builder,array $option){
		$builder->add('title')->add('description')->add('quantity')->add('price');
	}

    /**
    *tell the form type which entity is going to be using. we tell it to use soda entity
    */

	public function setDefaultOptions(OptionsResolverInterface $resolver){
		$resolver->setDefaults(array('data_class'=>'SodaStorageSystem\SodaManagerBundle\Entity\Soda'));
	}

	public function getName(){
		return 'sodastoragesystem_sodamanagerbundle_soda';
	}
}