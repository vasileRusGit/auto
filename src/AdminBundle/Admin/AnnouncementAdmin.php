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
            $years[$i] = $i;
        }

        // query after the car makers
        $emMakers = $this->modelManager->getEntityManager('AdminBundle\Entity\CarMakers');
        $qbMakers = $emMakers->createQueryBuilder();
        $qbMakers = $qbMakers->add('select', 'c')
                ->add('from', 'AdminBundle\Entity\CarMakers c');

        $date = new \DateTime('now');
        $updated = $date->format('Y-m-d H:m:s');
//        $updated = $this->updated = new \DateTime('now');
//        var_dump($updated);die;
        
        $queryMakers = $qbMakers->getQuery();
        $arrayTypeMakers = $queryMakers->getArrayResult();
        $arrayMakers = array_column($arrayTypeMakers, 'title');
        $arrayMakers1 = array_column($arrayTypeMakers, 'id');
        $resultMakers = array_combine($arrayMakers, $arrayMakers);

        $formMapper->add('product_name', 'text')
                ->add('car_maker', 'choice', array(
                    'choices' => $resultMakers,
                    'attr' => array('class' => 'carMaker')))
                ->add('car_model', 'text'
//                        , array(
//                    'choices' => $arrayModelsTitle,
//                    'required' => false,)
                    )
                ->add('car_year', 'choice', array(
                    'choices' => $years))
                ->add('price', 'text')
                ->add('stock', 'integer', array('required' => true))
                ->add('file', 'file', array('required' => false))
                ->add('description', 'textarea')
                ->add('updated', null, array('data' => new \DateTime('now'), 'attr'=>array('style'=>'display:none;')) )
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
//                ->add('updated')
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
