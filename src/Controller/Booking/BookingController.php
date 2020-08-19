<?php

namespace App\Controller\Booking;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BookingController extends AbstractController
{
    /**
     * @Route("/booking", name="booking_view")
     */
    public function bookingView(){

        return $this->render('booking/bookingView.html.twig', [

        ]);

    }

    /**
     * @Route("/api/booking/get", name="list_booking")
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function getListBooking(){
        $em= $this->getDoctrine()->getManager();
        $listBooking = $em->getRepository('App:Booking')->getListBooking();



       //return $this->json($listBooking);


        $response = new Response();

        $response->headers->set('Content-Type', 'application/json');
        $response->headers->set('Access-Control-Allow-Origin', '*');

        $response->setContent(json_encode($listBooking,true));

        return $response;


    }




}
