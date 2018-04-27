<?php

namespace App\Controller\Admin;

use App\Entity\Rencontre;
use App\Entity\Saison;
use App\Form\SaisonType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/saison")
 */
class SaisonController extends Controller
{
    /**
     * @Route("/")
     */
    public function index()
    {
        
        //$repository = $this->getDoctrine()->getRepository(Saison::class);
        
        //récupération des saisons du club
        $saisonRepository = $this->getDoctrine()->getRepository(Saison::class);
        $saisons = $saisonRepository->listSaisonClub($this->getUser()->getClub()->getId());
        
        if(!is_null($this->getUser())){
            $saisonRepository = $this->getDoctrine()->getRepository(Saison::class);
            $saisons = $saisonRepository->listSaisonClub($this->getUser()->getClub()->getId());
            $nbsaison = count($saisons);
            
            //dump($nbsaison);
        } else {
            $nbsaison = 0;
        }
        
        return $this->render(
            'admin/saison/index.html.twig', [
            'saisons' => $saisons,
            'nbsaisons' => $nbsaison
            ]    
        );
    }

     /**
     * @Route("/edit/{id}", defaults={"id":null})
     */
    public function edit(Request $request,$id)
    {
        //entity manager gere les objets et les lignes en bdd
        $em= $this->getDoctrine()->getManager();
        
        if(is_null($id)){
            $saison= new Saison();
            $saison->setClub($this->getUser()->getClub());
        } else {
            $saison = $em->getRepository(Saison::class)->find($id);
        }        
        
        //création du formulaire lié à l'équipe
        $form = $this->createForm(SaisonType::class, $saison);
        
        //le formulaire traite la requete HTTP
        $form->handleRequest($request);
        
        //si le formulaire à été envoyé
        if ($form->isSubmitted()){
            //s'il n'y à pas d'erreurs de validation du formulaire
            if ($form->isValid()){
                //prepare l'enregistrement en bdd
                $em->persist($saison);
                //fait l'enregistrement en bdd
                $em->flush();
                
                //Ajout du message flash
                $this->addFlash('success', 'La saison a été enregistrée, vous devez à présent ajouter un entraineur');
                //redirection vers la liste
                return $this->redirectToRoute('app_admin_saison_index');                
            } else {
                $this->addFlash('error', 'Le formulaire contient des erreurs');
            }
        }
        
        //récupération des saisons du club
        $saisonRepository = $this->getDoctrine()->getRepository(Saison::class);
        $saisons = $saisonRepository->listSaisonClub($this->getUser()->getClub()->getId());
        
        $nbsaison = count($saisons);
        
        $nom = $saison->getNom();
        
        if(!is_null($this->getUser())){
            $saisonRepository = $this->getDoctrine()->getRepository(Saison::class);
            $saisons = $saisonRepository->listSaisonClub($this->getUser()->getClub()->getId());
            $nbsaison = count($saisons);
            
            //dump($nbsaison);
        } else {
            $nbsaison = 0;
        }
        
        $nom = $saison->getNom();
         return $this->render('admin/saison/edit.html.twig', 
                 [
                     'form' => $form->createView(),
                     'nom' => $nom,
                     'nbsaisons' => $nbsaison
                     
                 ]
        );
    }
    
        /**
     * @Route("/delete/{id}")
     * @param int $id
     */
    public function delete($id)
    {
        $em = $this->getDoctrine()->getManager();
        $saison = $em->find(Saison::class, $id);
        
        //suppression de la categorie en bdd
        $em->remove($saison);
        $em->flush();
        
        //ajout d'un message
        $this->addFlash('success', 'La saison a été supprimée');
        //redirection vers la liste des categories
        return $this->redirectToRoute('app_admin_saison_index');
        
    }    
 
}
    
