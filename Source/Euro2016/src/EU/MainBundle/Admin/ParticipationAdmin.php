<?php

namespace EU\MainBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

class ParticipationAdmin extends Admin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper->add('pot', 'sonata_type_model', array());
        $formMapper->add('user', 'sonata_type_model', array());
        $formMapper->add('createdAt', 'sonata_type_datetime_picker');
        $formMapper->add('paidAt', 'sonata_type_datetime_picker');
        $formMapper->add('acceptedAt', 'sonata_type_datetime_picker');
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper->addIdentifier('id');
        $listMapper->add('pot', 'sonata_type_model', array(
        ));
        $listMapper->add('user', 'sonata_type_model', array(
        ));
        $listMapper->add('paidAt');
    }
}
