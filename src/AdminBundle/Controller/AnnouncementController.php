<?php

namespace AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class AnnouncementController extends Controller {

    public function carMakerAction() {
        $em = $this->getDoctrine()->getManager();
        $carMakers = $em->getRepository('AdminBundle:CarMakers')->findAll();

        $car = $em->getRepository('AdminBundle:CarModels');
        $carModels = $car->createQueryBuilder('p')
                        ->select('p.id')
////                        ->where('p.make_id = :carModelId')
////                        ->setParameter('carModelId', $carId)
                        ->getQuery()->getResult();

//        $carModels = $this->carModel();

        return $this->render('AdminBundle:Default:index.html.twig', array(
                    'carMakers' => $carMakers,
                    'carModels' => $carModels
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

    public function callJavascriptAction(Request $request) {
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

}
