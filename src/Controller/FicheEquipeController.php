<?php

namespace App\Controller;

use App\Entity\Saison;
use App\Entity\Joueur;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/equipe/fiche", defaults={"id":null})
 */
class FicheEquipeController extends Controller
{
    /**
     * @Route("/{id}")
     * @return type
     */
    public function index(Request $request,$id)
    {
        //Récupération du nom de la dernière saison enregistrée pour le club
        $SaisonClubRepository = $this->getDoctrine()->getRepository(Saison::class);
        $NomderniereSaisonClub = $SaisonClubRepository->findNomLatestSaison($this->getUser()->getClub()->getId());
        $IdDerniereSaisonClub = $SaisonClubRepository->findIdLatestSaison($this->getUser()->getClub()->getId());
        
        $em= $this->getDoctrine()->getManager();
        
        $joueurs = $em->getRepository(Joueur::class )->findAll();
        
        return $this->render('fiche_equipe/index.html.twig', [
            
            'joueurs' => $joueurs,
            'NomderniereSaisonClub' => $NomderniereSaisonClub,
        ]);
    }
}
