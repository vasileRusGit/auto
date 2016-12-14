<?php

namespace AdminBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

class AnnouncementAdmin extends AbstractAdmin {

    protected function configureFormFields(FormMapper $formMapper) {
        // for loop years
        for ($i = 2016; $i >= 1970; $i--) {
            $time = new \DateTime('now');
            $years[$i] = (string) $i;
        }

//        // query after the car makers
//        $em = $this->modelManager->getEntityManager('AdminBundle\Entity\CarMakers');
//
//        $qb = $em->createQueryBuilder();
//
//        $qb = $qb->add('select', 'c')
//                ->add('from', 'AdminBundle\Entity\CarMakers c');
//
//        $query = $qb->getQuery();
//        $arrayType = $query->getArrayResult();
//        $array = array_column($arrayType, 'title');

        $formMapper->add('product_name', 'text')
                ->add('car_maker', 'choice', array(
//                    'choices_as_values' => true,
                    'choices' => array('audi'=>'audi'),
                    'required' => false))
                ->add('car_model', 'text')
                ->add('car_year', 'choice', array(
                    'choices' => $years))
                ->add('stock', 'integer', array('required' => true))
                ->add('file', 'file', array('required' => false))
                ->add('description', 'textarea')
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper) {
        $datagridMapper->add('product_name');
    }

    protected function configureListFields(ListMapper $listMapper) {
        $listMapper->addIdentifier('product_name')
                ->add('car_maker')
                ->add('car_model')
                ->add('car_year')
                ->add('stock')
                ->add('imageName')
                ->add('description')
                ->add('created')
        ;
    }

    // IMAGE UPLOAD
    public function prePersist($product) {
        $this->saveFile($product);
    }

    public function preUpdate($product) {
        $this->saveFile($product);
    }

    public function saveFile($product) {
        $basepath = $this->getRequest()->getBasePath();
        $product->upload($basepath);
    }

}
