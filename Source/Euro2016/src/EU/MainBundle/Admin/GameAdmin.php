<?php

namespace EU\MainBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

class GameAdmin extends Admin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
        ->with('Content', array('class' => 'col-md-6'))
            ->add('team1', 'sonata_type_model', array(
            ))
            ->add('team2', 'sonata_type_model', array(
            ))
            ->add('score1', 'text', array(
                'required' => false
            ))
            ->add('score2', 'text', array(
                'required' => false
            ))
        ->end()
        ->with('Date and time', array('class' => 'col-md-6'))
            ->add('startTime', 'sonata_type_datetime_picker')
        ->end();
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper->add('team1');
        $datagridMapper->add('team2');
        $datagridMapper->add('startTime', 'doctrine_orm_datetime_range', array(
                'field_type' => 'sonata_type_datetime_range_picker'
        ));
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper->addIdentifier('id');
        $listMapper->add('team1', 'sonata_type_model', array(
        ));
        $listMapper->add('team2', 'sonata_type_model', array(
        ));
        $listMapper->add('score1', 'text');
        $listMapper->add('score2', 'text');
        $listMapper->add('startTime', 'datetime');
    }
}
