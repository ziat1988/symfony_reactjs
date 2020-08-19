<?php

namespace App\Controller\Booking;

use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/** @Route("/api/booking") */
class BookingApiController extends AbstractController
{


    /**
     * @Route("/get/list-booking",options={"expose"=true}, name="content_booking_by_day")
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    public function contentBooking(Request $request) {


        $em = $this->getDoctrine()->getManager();
        $date =  new \DateTime(date('Y-m-d H:i:s')); // par défault au jourd'hui

        $dateTS = $request->get('dateTS');
        $restaurantToken = $request->get('restaurantToken');

        $orderBy = 0;

        if ($request->get('orderBy') != ""){
            $orderBy = $request->get('orderBy');
        }

        if($dateTS == null || $dateTS == 0) $dateTS = time();


        $start = date('Y-m-d 00:00:00',$dateTS);
        $end = date('Y-m-d 23:59:59',$dateTS);

        $restaurant = $em->getRepository('App:Restaurant')->findOneBy(array('token' =>$restaurantToken));


        // prendre tous les booking concerné du jour

        $listBooking =  $em->getRepository('App:Booking')->getListBookingByDay($start,$end,$restaurant,$orderBy);

        // le nombre de jour

        $dayOfWeek = (int)date('N',$dateTS);  // 1: Lundi , 2: Mardi, 3:Mercredi ...



        $tabResultOpen =  $this->get('opening.hours')->isOpenCloseService($restaurant,$dateTS,null ); // service null <=> prendre tous les service résultat


        // end call service



        $keyDate  = key($listBooking);

        $listBooking[$keyDate]['tabResultOpen'] =$tabResultOpen;

        // $viewOld = 'MonAppMNBundle:Back:contentBookList.component.twig';
        return $this->render('@MonAppMN/Back/BookingRestoPro/compnents/contentBookList.component.twig',array(
            'dateLink'=>$date,
            'listBooking' =>$listBooking,
            'dayOfWeek' =>$dayOfWeek,
            //'tabResultOpen' =>$tabResultOpen,


        ));

    }

    private function numberOfWeek($firstMondayOfMonth,$mondayThisWeek){

        // 1 mois a 4 -5 semaine <=> loop 5

        for ($i = 1; $i <=5; $i ++) {

            if( $firstMondayOfMonth == $mondayThisWeek ){
                return $i ;
            }
            $firstMondayOfMonth +=7;
        }

        return 5;

    }


    /**
     * @Route("/get/day-week",options={"expose"=true}, name="day_week_booking_navigate")
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    public function dayWeekBooking(Request $request){
        $em = $this->getDoctrine()->getManager();

        $dateTS = $request->get('dateTS');
        $action = $request->get('action');
        $restaurantToken = $request->get('restaurantToken');

        $restaurant = $em->getRepository('App:Restaurant')->findOneBy(array('token' =>$restaurantToken));
        
        if($dateTS == null || $dateTS == 0) $dateTS = time(); // par défault au jourd'hui
        
        switch ($action){
            case 1:
                // semaine précédente
                $date =  new \DateTime(date('Y-m-d H:i:s',strtotime('-1 week', $dateTS)));
                $start= date('Y-m-d 00:00:00',strtotime('monday last week',$dateTS));
                $end= date('Y-m-d 23:59:59',strtotime('sunday last week',$dateTS));

                $numMondayOfWeek = date( "j", strtotime( 'monday last week',$dateTS ) );
                $monthByMonDay = new \DateTime(date('Y-m-d H:i:s',strtotime('monday last week',$dateTS)));

                $firstMondayOfMonth = date("j", strtotime("first monday of this month",strtotime('-1 week', $dateTS)));

                break;
            case 2:
                // semaine suivante

                $date =  new \DateTime(date('Y-m-d H:i:s',strtotime('+1 week', $dateTS)));
                $start= date('Y-m-d 00:00:00',strtotime('monday next week',$dateTS));
                $end=  date('Y-m-d 23:59:59',strtotime('sunday next week',$dateTS));

                $numMondayOfWeek = date( "j", strtotime( 'monday next week',$dateTS ) );
                $monthByMonDay = new \DateTime(date('Y-m-d H:i:s',strtotime('monday next week',$dateTS)));

                $firstMondayOfMonth = date("j", strtotime("first monday of this month",strtotime('+1 week', $dateTS)));

                break;

            default:

                $date =  new \DateTime(date('Y-m-d H:i:s', $dateTS));
                $start= date('Y-m-d 00:00:00',strtotime('monday this week',$dateTS));
                $end=  date('Y-m-d 23:59:59',strtotime('sunday this week',$dateTS));

                $numMondayOfWeek = date( "j", strtotime( 'monday this week',$dateTS ) );
                $monthByMonDay = new \DateTime(date('Y-m-d H:i:s',strtotime('monday this week',$dateTS)));

                $firstMondayOfMonth = date("j", strtotime("first monday of this month",$dateTS));
                break;

        }

        $numWeek = $this->numberOfWeek((int)$firstMondayOfMonth, (int)$numMondayOfWeek);  // get numéro de semaine

        $listDayWeek = $em->getRepository('App:Booking')->getListDayOfWeek($start,$end,$restaurant);

        $arrResponseApi =  array(
            'listDayWeek'=>$listDayWeek,
            'date'=>$date,
            'numWeek'=>$numWeek,
            'monthByMonDay'=> $monthByMonDay,
            //'dateLink'=>$date
        );



        $response = new Response();

        $response->headers->set('Content-Type', 'application/json');
        $response->headers->set('Access-Control-Allow-Origin', '*');

        $response->setContent(json_encode($arrResponseApi,true));

        return $response;


    }


}