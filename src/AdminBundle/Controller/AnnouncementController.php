<?php

namespace AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class AnnouncementController extends Controller {

    public function carMakerAction() {
        $em = $this->getDoctrine()->getManager();
        $carMakers = $em->getRepository('AdminBundle:CarMakers')->findAll();

        $announcement = $em->getRepository('AdminBundle:Announcement')->findAll();

        $pagination = $this->paginationFunction($announcement);

        return $this->render('AdminBundle:Default:index.html.twig', array(
                    'carMakers' => $carMakers,
                    'carModels' => '',
                    'pagination' => $pagination,
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
        $carMakerDropdown = $request->get('carMakerDropdown');
        $carModelDropdown = $request->get('carModelDropdown');
        $carStartYearDropdown = $request->get('carStartYearDropdown');
        $carEndYearDropdown = $request->get('carEndYearDropdown');
        $stockCheckbox = $request->get('stockCheckbox');

        $em = $this->getDoctrine()->getManager();
        $announcement = $em->getRepository('AdminBundle:Announcement');

        $query = $announcement->createQueryBuilder('p');

        // condition if just the car model is selected
        if (!preg_match("/marca/", $carMakerDropdown)) {
            $query->select('p')
                    ->where('p.car_maker = :carMakerValue')
                    ->setParameter('carMakerValue', $carMakerDropdown);
        }
        if (!preg_match("/model/", $carModelDropdown)) {
            $query->select('p')
                    ->andWhere('p.car_model = :carModelValue')
                    ->setParameter('carModelValue', $carModelDropdown);
        }
        if (!preg_match("/An/", $carStartYearDropdown)) {
            $query->select('p')
                    ->andWhere('p.car_year >= :carStartYearValue')
                    ->setParameter('carStartYearValue', $carStartYearDropdown);
        }
        if (!preg_match("/An/", $carEndYearDropdown)) {
            $query->select('p')
                    ->andWhere('p.car_year <= :carEndYearValue')
                    ->setParameter('carEndYearValue', $carEndYearDropdown);
        }
        if ($stockCheckbox == 'true') {
            $query->select('p')
                    ->andWhere('p.stock > 0');
        }
        if (preg_match("/marca/", $carMakerDropdown) && preg_match("/model/", $carModelDropdown) && preg_match("/An/", $carStartYearDropdown) && preg_match("/An/", $carEndYearDropdown)) {
            $query->select('p');
        }

        $filter = $query->getQuery()->getResult();

        $pagination = $this->paginationFunction($filter);

        $returnQueryResult = $this->render('AdminBundle:Default:filter-form.html.twig', array(
                    'filter' => $filter,
                    'pagination' => $pagination
                ))->getContent();

        return new JsonResponse($returnQueryResult);
    }

    public function searchEngineAction(Request $request) {
        $searchEngine = $request->get('searchEngine');

        $em = $this->getDoctrine()->getManager();
        $announcement = $em->getRepository('AdminBundle:Announcement');

        $query = $announcement->createQueryBuilder('p');

        // condition if just the car model is selected

        $query->select('p')
                ->where('p.product_name = :searchEngine')
                ->setParameter('searchEngine', $searchEngine);


        $filter = $query->getQuery()->getResult();

        $pagination = $this->paginationFunction($filter);

        $returnQueryResult = $this->render('AdminBundle:Default:search-form.html.twig', array(
                    'filter' => $filter,
                    'pagination' => $pagination
                ))->getContent();

        return new JsonResponse($returnQueryResult);
    }

    public function paginationFunction($query) {
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
                $query, $this->get('request')->query->get('page', 1), $this->get('request')->query->get('limit', 4)
        );
        return $pagination;
    }

}
