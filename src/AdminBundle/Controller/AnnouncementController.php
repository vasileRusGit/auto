<?php

namespace AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class AnnouncementController extends Controller {

    public function carMakerAction() {
        $em = $this->getDoctrine()->getManager();
        $carMakers = $em->getRepository('AdminBundle:CarMakers')->findAll();

//        if ($form->isSubmitted() & $form->isValid()) {
//            echo '<script type="text/javascript">alert("enter1");</script>';
//            $announcement = '';
//        } else {
//            echo '<script type="text/javascript">alert("entere2");</script>';
        $announcement = $em->getRepository('AdminBundle:Announcement')->findAll();


        return $this->render('AdminBundle:Default:index.html.twig', array(
                    'carMakers' => $carMakers,
                    'carModels' => '',
                    'announcement' => $announcement
        ));
    }

    public function carModel($carmakerId) {
        $em = $this->getDoctrine()->getManager();

        $car = $em->getRepository('AdminBundle:CarModels');
        $carModels = $car->createQueryBuilder('p')
                        ->select('p.title, p.id')
                        ->where('p.make_id = :carModelId')
                        ->setParameter('carModelId', $carmakerId)
                        ->getQuery()->getResult();

        return $carModels;
    }

    public function callAjaxAction(Request $request) {
        $carMakerName = $request->get('carMaker');

        $em = $this->getDoctrine()->getManager();
        $car = $em->getRepository('AdminBundle:CarMakers');
        $carmaker = $car->createQueryBuilder('p')
                        ->select('p.id')
                        ->where('p.title = :car')
                        ->setParameter('car', $carMakerName)
                        ->getQuery()->getResult();

        return new JsonResponse($this->carModel($carmaker));
    }

    public function formSubmitAction(Request $request) {
        $carMakerValue = $request->get('carMakerDropdown');
        $carModelDropdown = $request->get('carModelDropdown');
        $carStartYearDropdown = $request->get('carStartYearDropdown');
        $carEndYearDropdown = $request->get('carEndYearDropdown');

        $em = $this->getDoctrine()->getManager();
        $announcement = $em->getRepository('AdminBundle:Announcement');

        $filter = $announcement->createQueryBuilder('p')
                        ->select('p')
                        ->where('p.car_maker = :carMakerValue AND p.car_model = :carModelValue AND p.car_year >= :carStartYearValue AND p.car_year <= :carEndYearValue')
                        ->setParameter('carMakerValue', $carMakerValue)
                        ->setParameter('carModelValue', $carModelDropdown)
                        ->setParameter('carStartYearValue', $carStartYearDropdown)
                        ->setParameter('carEndYearValue', $carEndYearDropdown)
                        ->getQuery()->getResult();

        $retunQueryResult = $this->render('AdminBundle:Default:filter-form.html.twig', array(
                    'filter' => $filter
                ))->getContent();

        return new JsonResponse($retunQueryResult);
    }

}
