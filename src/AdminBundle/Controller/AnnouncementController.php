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

    public function searchEngineAction(Request $request) {
        $carMakerDropdown = $request->get('car_makers');
        $carModelDropdown = $request->get('car_models');
        $carStartYearDropdown = $request->get('car-year-start');
        $carEndYearDropdown = $request->get('car-year-end');
        $carPriceFromDropdown = $request->get('car-price-from');
        $carPriceToDropdown = $request->get('car-price-to');
        $stockCheckbox = $request->get('stock');
        $searchEngine = $request->get('search_engine');
//        var_dump($carEndYearDropdown);die;

        $em = $this->getDoctrine()->getManager();
        $announcement_default = $em->getRepository('AdminBundle:Announcement');

        $carMakers = $em->getRepository('AdminBundle:CarMakers')->findAll();
        $announcement = $em->getRepository('AdminBundle:Announcement')->findAll();

        $query = $announcement_default->createQueryBuilder('p');

        // condition if just the car model is selected
        if (!(empty($searchEngine))) {
            $query->select('p')
                    ->where('p.product_name LIKE :searchEngine')
                    ->setParameter('searchEngine', '%' . $searchEngine . '%');
        }
        // check if are any filter added when the search is completed
        if (!empty($carMakerDropdown)) {
            $query->select('p')
                    ->andWhere('p.car_maker = :carMakerValue')
                    ->setParameter('carMakerValue', $carMakerDropdown);
        }
        if (!empty($carModelDropdown)) {
            $carModel = $em->getRepository('AdminBundle:CarModels')->find($carModelDropdown);
            $query->select('p')
                    ->andWhere('p.car_model = :carModelValue')
                    ->setParameter('carModelValue', $carModel->getTitle());
        }
        if (!empty($carStartYearDropdown)) {
            $query->select('p')
                    ->andWhere('p.car_year >= :carStartYearValue')
                    ->setParameter('carStartYearValue', $carStartYearDropdown);
        }
        if (!empty($carEndYearDropdown)) {
            $query->select('p')
                    ->andWhere('p.car_year <= :carEndYearValue')
                    ->setParameter('carEndYearValue', $carEndYearDropdown);
        }
        if (!empty($carPriceFromDropdown)) {
            $query->select('p')
                    ->andWhere('p.price >= :carPriceFromValue')
                    ->setParameter('carPriceFromValue', $carPriceFromDropdown);
        }
        if (!empty($carPriceToDropdown)) {
            $query->select('p')
                    ->andWhere('p.price <= :carPriceToValue')
                    ->setParameter('carPriceToValue', $carPriceToDropdown);
        }
        if ($stockCheckbox == 'on') {
            $query->select('p')
                    ->andWhere('p.stock > 0');
        }
        if (empty($carMakerDropdown) && empty($carModelDropdown) && empty($carStartYearDropdown) && empty($carEndYearDropdown)) {
            $query->select('p');
        }

        $filter = $query->getQuery()->getResult();
        $pagination = $this->paginationFunction($filter);

        return $this->render('AdminBundle:Default:index.html.twig', array(
                    'carMakers' => $carMakers,
                    'carModels' => '',
                    'announcement' => $announcement,
                    'filter' => $filter,
                    'pagination' => $pagination
        ));
    }

    public function paginationFunction($query) {
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
                $query, $this->get('request')->query->get('page', 1), $this->get('request')->query->get('limit', 6)
        );
        return $pagination;
    }

}
