<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Echouage;
use App\Entity\Zone;
use App\Entity\Espece;

/**
 * @Route("/api")
 */
class ApirestController extends AbstractController
{
    /**
     * @Route("", name="main")
     */
    public function index(): Response
    {
        $data = [
            "l'API marche" => True,
            "liste des endpoints" =>
            [
                "/api",
                "/echouages_espece/{espece_id}",
                "/echouages_espece/{espece_id}/zone/{zone_id}",
                "/espece/",
                "/espece/{espece_id}",
                "/zone/",
                "/zone/{zone_id}"                
            ],
        ];
        $response = new Response();
        $response->setContent(json_encode($data));
        $response->headers->set('Content-Type', 'application/json');
        $response->headers->set('Access-Control-Allow-Origin', '*');
        return $response;
    }

    /**
     * @Route("/echouages_espece/{espece_id}", name="echouages_espece")
     */
    public function echouages_espece($espece_id, EntityManagerInterface $em): Response
    {
        $data = $em ->getRepository(Echouage::Class)
                    ->get_echouages_espece($espece_id);

        $response = new Response();
        $response->setContent(json_encode($data));
        $response->headers->set('Content-Type', 'application/json');
        $response->headers->set('Access-Control-Allow-Origin', '*');
        return $response;
    }


    /**
     * @Route("/echouages_espece/{espece_id}/zone/{zone_id}", name="echouages_espece_zone")
     */
    public function echouages_espece_zone($espece_id, $zone_id, EntityManagerInterface $em): Response
    {
        $data = $em ->getRepository(Echouage::Class)
                    ->get_echouages_espece($espece_id,$zone_id);

        $response = new Response();
        $response->setContent(json_encode($data));
        $response->headers->set('Content-Type', 'application/json');
        $response->headers->set('Access-Control-Allow-Origin', '*');
        return $response;
    }

    /**
     * @Route("/espece", name="especes_list")
     */
    public function especes_list(EntityManagerInterface $em): Response
    {
        $data = $em ->getRepository(Espece::class) ->get();
    
        $response = new Response();
        $response->setContent(json_encode($data));
        $response->headers->set('Content-Type', 'application/json');
        $response->headers->set('Access-Control-Allow-Origin', '*');
        return $response;
    }

    /**
     * @Route("/espece/{id}", name="espece_id")
     */
    public function espece_id($id,EntityManagerInterface $em): Response
    {
        $data = $em ->getRepository(Espece::class) ->get($id);
    
        $response = new Response();
        $response->setContent(json_encode($data));
        $response->headers->set('Content-Type', 'application/json');
        $response->headers->set('Access-Control-Allow-Origin', '*');
        return $response;
    }    

    /**
     * @Route("/zone", name="zones_list")
     */
    public function zones_list(EntityManagerInterface $em): Response
    {
        $data = $em ->getRepository(Zone::class) ->get();
    
        $response = new Response();
        $response->setContent(json_encode($data));
        $response->headers->set('Content-Type', 'application/json');
        $response->headers->set('Access-Control-Allow-Origin', '*');
        return $response;
    }

    /**
     * @Route("/zone/{id}", name="zone_id")
     */
    public function zone_id($id,EntityManagerInterface $em): Response
    {
        $data = $em ->getRepository(Zone::class) ->get($id);
    
        $response = new Response();
        $response->setContent(json_encode($data));
        $response->headers->set('Content-Type', 'application/json');
        $response->headers->set('Access-Control-Allow-Origin', '*');
        return $response;
    }  
}
