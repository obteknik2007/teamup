<?php

namespace App\Controller\Admin;

use App\Entity\Saison;
use App\Entity\Rencontre;
use App\Form\RencontreType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/rencontre")
 */
class RencontreController extends Controller
{
    /**
     * @Route("/")
     */
    public function index()
        {
        $repository = $this->getDoctrine()->getRepository(Rencontre::class);
        
        if(!is_null($this->getUser())){
            $saisonRepository = $this->getDoctrine()->getRepository(Saison::class);
            $saisons = $saisonRepository->listSaisonClub($this->getUser()->getClub()->getId());
            $nbsaison = count($saisons);    
           
        } else {
            $nbsaison = 0;
        }
          
        //on recup toutes les rencontres
        //$rencontres = $repository->findAll();
        
        //on recup toutes les rencontres de la saison du club
        if($this->getUser()){
            $RencontreRepository = $this->getDoctrine()->getRepository(Rencontre::class);
            $rencontres = $RencontreRepository->afficheLesRencontres3($this->getUser()->getClub()->getId(),$saisons);
       } else {
            $rencontres ='';
       }
       
        return $this->render(
            'admin/rencontre/index.html.twig', [
            'rencontres' => $rencontres,
             'nbsaisons' => $nbsaison
        ]);
    }
    
    
     /**
     * @Route("/edit/{id}", defaults={"id":null})
     */
    public function edit(Request $request,$id)
    {
        //entity manager gere les objets et les lignes en bdd
        $em= $this->getDoctrine()->getManager();
        
        if(is_null($id)){
            $rencontre= new Rencontre();
            
        } else {
            $rencontre = $em->getRepository(Rencontre::class)->find($id);
        }        
        
        //alimentation de la clé étrangère club
        $rencontre->setClub($this->getUser()->getClub());

        //alimentation de la clé étrangère SAISON
            //Récupération id de la dernière saison enregistrée pour le club
            $SaisonClubRepository = $this->getDoctrine()->getRepository(Saison::class);
            $IdDerniereSaisonClub = $SaisonClubRepository->findIdLatestSaison($this->getUser()->getClub()->getId());

            $saison = $SaisonClubRepository->find($IdDerniereSaisonClub['id']);
            $rencontre->setSaison($saison);
            

            //dump($rencontre);
                

                
        //création du formulaire lié à l'équipe
        $form = $this->createForm(RencontreType::class, $rencontre);
        
        //le formulaire traite la requete HTTP
        $form->handleRequest($request);
       
                 $equipe1 = $rencontre->getEquipe1();    
                $equipe2 = $rencontre->getEquipe2();
                $date = $rencontre->getDate();
                
        //si le formulaire à été envoyé
        if ($form->isSubmitted()){
            //s'il n'y à pas d'erreurs de validation du formulaire
            if ($form->isValid()){
                
                //alimentation du libellé de la rencontre ("eq1 contre eq2 du jj/mm/aaaa")
                $libelle = 'Rencontre : '.$rencontre->getEquipe1().' contre '.$rencontre->getEquipe2()." le ".$date->format('d/m/Y');
                $rencontre->setLibelle($libelle);
                
                //prepare l'enregistrement en bdd
                $em->persist($rencontre);
                //fait l'enregistrement en bdd  
                $em->flush();

                $equipe1 = $rencontre->getEquipe1();    
                $equipe2 = $rencontre->getEquipe2();
                $date = $rencontre->getDate();
                //Ajout du message flash
                $this->addFlash('success', "La rencontre opposant l'équipe ".$equipe1." à l'équipe ".$equipe2." le ".$date->format('d/m/Y')." a été enregistrée");
                //redirection vers la liste
                return $this->redirectToRoute('app_admin_rencontre_index');                
            } else {
                $this->addFlash('error', 'Le formulaire contient des erreurs');
            }
        }

         return $this->render('admin/rencontre/edit.html.twig', 
                 [
                     'form' => $form->createView(),
                     'equipe1' => $equipe1,
                     'equipe2' => $equipe2,
                     'date' => $date
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
        $rencontre = $em->find(Rencontre::class, $id);
        
        //suppression de la categorie en bdd
        $em->remove($rencontre);
        $em->flush();
        
        //ajout d'un message
        $this->addFlash('success', 'La rencontre a été supprimée');
        //redirection vers la liste des categories
        return $this->redirectToRoute('app_admin_rencontre_index');
        
    }
    
}