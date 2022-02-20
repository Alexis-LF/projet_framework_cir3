<?php
/**
 * Controller du Web-service API
 *
 * @package    Projet Framework
 * @author     Alexis Le Floch <alexis.le-floch@isen-ouest.yncrea.fr> , Noam Nedelec-Salmon <noam.nedelec-salmon@isen-ouest.yncrea.fr>
 * @version    1.0 
 */

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
     * @Route("", name="api_index")
     */
    public function index(): Response
    {
        $data = [
            "l'API marche" => True,
            "liste des endpoints" =>
            [
                "/api",
                "/api/echouages/espece/{espece_id}",
                "/api/echouages/espece/{espece_id}/zone/{zone_id}",
                "/api/echouages/espece/{espece_id}/zones/date",
                "/api/echouages/espece/{espece_id}/zone/{zone_id}/date",
                "/api/echouages/espece/{espece_id}/zones/date/{min}/{max}",
                "/api/echouages/espece/{espece_id}/zone/{zone_id}/date/{min}/{max}",
                "/api/espece/",
                "/api/espece/{espece_id}",
                "/api/espece/{espece_id}/date/",
                "/api/espece/{espece_id}/date/min",                   
                "/api/espece/{espece_id}/date/max",                   
                "/api/zone/",
                "/api/zone/{zone_id}",

            ],
        ];
        $response = new Response();
        $response->setContent(json_encode($data));
        $response->headers->set('Content-Type', 'application/json');
        $response->headers->set('Access-Control-Allow-Origin', '*');
        return $response;
    }

    /**
     * @Route("/echouages/espece/{espece_id}", name="echouages_espece")
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
     * @Route("/echouages/espece/{espece_id}/zone/{zone_id}", name="echouages_espece_zone")
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
    public function espece_id($id, EntityManagerInterface $em): Response
    {
        $data = $em ->getRepository(Espece::class) ->get($id);
    
        $response = new Response();
        $response->setContent(json_encode($data));
        $response->headers->set('Content-Type', 'application/json');
        $response->headers->set('Access-Control-Allow-Origin', '*');
        return $response;
    } 

    /**
     * @Route("/espece/{id}/date", name="espece_dates")
     */
    public function espece_dates($id, EntityManagerInterface $em): Response
    {
        $data = $em ->getRepository(Echouage::class) ->tab_dates($id, "ASC");
        $response = new Response();
        $response->setContent(json_encode($data));
        $response->headers->set('Content-Type', 'application/json');
        $response->headers->set('Access-Control-Allow-Origin', '*');
        return $response;
    } 

    /**
     * @Route("/espece/{id}/date/{min_ou_max}", name="espece_date_min_max")
     */
    public function espece_date_min_max($id,$min_ou_max, EntityManagerInterface $em): Response
    {
        $data = $em ->getRepository(Echouage::class);
        if ($min_ou_max == "min"){
            $data = $data ->date_min($id);
        }
        elseif ($min_ou_max == "max"){
            $data = $data ->date_max($id);
        }
        else {
            $data = NULL;
        }
    
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

    /**
     * @Route("/echouages/espece/{espece_id}/zones/date", name="echouages_dates_zones")
     */
    public function echouages_dates_zones($espece_id,EntityManagerInterface $em): Response
    {
        $nb_zones = $em ->getRepository(Zone::Class) ->get_count();
        $data = $em ->getRepository(Echouage::Class)
                    ->get_tab_dates_zone($espece_id,$nb_zones,0);
        $response = new Response();
        $response->setContent(json_encode($data));
        $response->headers->set('Content-Type', 'application/json');
        $response->headers->set('Access-Control-Allow-Origin', '*');
        return $response;
    }

    /**
     * @Route("/echouages/espece/{espece_id}/zone/{zone_id}/date", name="echouages_dates_zone")
     */
    public function echouages_dates_zone($espece_id, $zone_id, EntityManagerInterface $em): Response
    {
        $nb_zones = $em ->getRepository(Zone::Class) ->get_count();
        $data = $em ->getRepository(Echouage::Class)
                    ->get_tab_dates_zone($espece_id,$nb_zones,$zone_id);

        $response = new Response();
        $response->setContent(json_encode($data));
        $response->headers->set('Content-Type', 'application/json');
        $response->headers->set('Access-Control-Allow-Origin', '*');
        return $response;
    }

    
    /**
     * @Route("/echouages/espece/{espece_id}/zones/date/{min}/{max}", name="echouages_select_dates_zones")
     */
    public function echouages_select_dates_zones($espece_id,$min,$max,EntityManagerInterface $em): Response
    {
    $nb_zones = $em ->getRepository(Zone::Class) ->get_count();
        $data = $em ->getRepository(Echouage::Class)
                    ->get_tab_dates_zone($espece_id,$nb_zones,0,$min,$max);
        $response = new Response();
        $response->setContent(json_encode($data));
        $response->headers->set('Content-Type', 'application/json');
        $response->headers->set('Access-Control-Allow-Origin', '*');
        return $response;
    }

    
    /**
     * @Route("/echouages/espece/{espece_id}/zone/{zone_id}/date/{min}/{max}", name="echouages_select_dates_zone")
     */
    public function echouages_select_dates_zone($espece_id,$zone_id,$min,$max,EntityManagerInterface $em): Response
    {
        $nb_zones = $em ->getRepository(Zone::Class) ->get_count();
        $data = $em ->getRepository(Echouage::Class)
                    ->get_tab_dates_zone($espece_id,$nb_zones,$zone_id,$min,$max);
        $response = new Response();
        $response->setContent(json_encode($data));
        $response->headers->set('Content-Type', 'application/json');
        $response->headers->set('Access-Control-Allow-Origin', '*');
        return $response;
    }
}
