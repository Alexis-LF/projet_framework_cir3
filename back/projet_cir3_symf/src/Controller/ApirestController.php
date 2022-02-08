<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\JsonResponse;

use App\Entity\Echouage;
use App\Entity\Espece;
use App\Entity\Zone;

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
        return $this->render('apirest/index.html.twig', [
            'controller_name' => 'ApirestController',
        ]);
    }

    /**
     * @Route("/echouage", name="echouage_list")
     */
    public function echouage_list(): Response
    {
        $em = $this->getDoctrine()->getManager();
    
        //recuperation de la liste des cours au travers du repository
        $cours = $em->getRepository(Cours::Class)-> findByCoursOrderedRest();
        //reponse du controlleur en indiquant le format json et
        // en ajoutant la balise "Access-Control-Allow-Origin"
        // dans l'en-tete HTTP pour eviter les probleme de refus du au CORS
        $response = new Response();
        $response->setContent(json_encode($cours));
        $response->headers->set('Content-Type', 'application/json');
        $response->headers->set('Access-Control-Allow-Origin', '*');
        return $response;
    }

    /**
     * @Route("/cours/{id}", name="cours_details")
     */
    public function cours_details($id): Response
    {
        $em = $this->getDoctrine()->getManager();
    
        //recuperation de la liste des cours au travers du repository
        $cours = $em->getRepository(Cours::Class)-> findByCoursId($id);
    
        //reponse du controlleur en indiquant le format json et
        // en ajoutant la balise "Access-Control-Allow-Origin"
        // dans l'en-tete HTTP pour eviter les probleme de refus du au CORS
        $response = new Response();
        $response->setContent(json_encode($cours));
        $response->headers->set('Content-Type', 'application/json');
        $response->headers->set('Access-Control-Allow-Origin', '*');
        return $response;
    }


    /**
     * @Route("/type_cours", name="type_cours_list")
     */
    public function type_cours_list(): Response
    {
        // créer un ensemble route/contrôleur(méthode) et repository permettant de
        // récupérer la liste des cours pour un type de cours 

        $em = $this->getDoctrine()->getManager();
    
        //recuperation de la liste des cours au travers du repository
        $type_cours = $em->getRepository(TypeCours::Class)-> findByTypeCoursOrderedRest();
        //reponse du controlleur en indiquant le format json et
        // en ajoutant la balise "Access-Control-Allow-Origin"
        // dans l'en-tete HTTP pour eviter les probleme de refus du au CORS
        $response = new Response();
        $response->setContent(json_encode($type_cours));
        $response->headers->set('Content-Type', 'application/json');
        $response->headers->set('Access-Control-Allow-Origin', '*');
        return $response;
    }


    /**
     * @Route("/type_cours/{id}", name="type_cours_details")
     */    
    public function type_cours_details($id): Response
    {
        $em = $this->getDoctrine()->getManager();
    
        //recuperation de la liste des cours au travers du repository
        $cours = $em->getRepository(Cours::Class)-> findByTypeCoursId($id);
    
        //reponse du controlleur en indiquant le format json et
        // en ajoutant la balise "Access-Control-Allow-Origin"
        // dans l'en-tete HTTP pour eviter les probleme de refus du au CORS


        $response = new Response();
        $response->setContent(json_encode($cours));
        $response->headers->set('Content-Type', 'application/json');
        $response->headers->set('Access-Control-Allow-Origin', '*');
        return $response;
    }


}
