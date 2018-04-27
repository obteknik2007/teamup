<?php

namespace App\Controller\Admin;

use App\Entity\Saison;
use App\Entity\Club;
use App\Form\ClubType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/club")
 * Toutes les routes vont être pré&fixées par /admin du fait de l'inscription
 * 'admin' dans le fichier annotations.yaml
 */
class ClubController extends Controller
{
    /**
     * @Route("/")
     */
    public function index()
    {
        $repository = $this->getDoctrine()->getRepository(Club::class);
        //on recup ttes les catégories
        $clubs = $repository->findAll();
        
        if(!is_null($this->getUser())){
            $saisonRepository = $this->getDoctrine()->getRepository(Saison::class);
            $saisons = $saisonRepository->listSaisonClub($this->getUser()->getClub()->getId());
            $nbsaison = count($saisons);
            
            //dump($nbsaison);
        } else {
            $nbsaison = 0;
        }
        
        return $this->render('admin/club/index.html.twig', [
            'clubs' => $clubs,
            'nbsaisons' => $nbsaison
        ]);
    }
    
    /**
     * @Route("/edit", defaults={"id":null})
     */

        public function edit(Request $request,$id)
    {
        $id = $this->getUser()->getClub()->getId();
        
        //entity manager gere les objets et les lignes en bdd
        $em= $this->getDoctrine()->getManager();
        
        if(is_null($id) ) {
            $club = new Club();
        } else {
            $club = $em->getRepository(Club::class)->find($id);
            $originalImageLogo = null;
            
            if (!is_null($club->getLogo()) ) {
                $originalImageLogo = $club->getLogo();
                $imagePathLogo = $this->getParameter('upload_dir') . '/' . $originalImageLogo;
                // objet File pour éviter une erreur du formulaire
                $club->setLogo(new File($imagePathLogo)); //fin tentative --2
            }
        }        
        
        //FORMULAIRE
        //création du formulaire lié à la catégorie
        $form = $this->createForm(ClubType::class, $club);
        
        //le formulaire traite la requete HTTP
        $form->handleRequest($request);
        
        //si le formulaire à été envoyé
        if ($form->isSubmitted()){
            //s'il n'y à pas d'erreurs de validation du formulaire
            if ($form->isValid()){
                //prepare l'enregistrement en bdd
                
                //TRAITEMENT LOGO
                /** @var UploadedFile $logo */
                $image = $club->getLogo();
                
                // s'il y a une image uploadée
                if (!is_null($image)) {
                    // nom du fichier que l'on va enregistrer
                    $filename = uniqid() . '.' . $image->guessExtension();
                    
                    // équivalent move_uploaded_file()
                    $image->move(
                        // upload_dir défini dans service.yaml
                        $this->getParameter('upload_dir'),
                        $filename
                    );
                    
                    $club->setLogo($filename);
                    
                    // suppression de l'ancienne image en modification
                    if (!is_null($originalImageLogo)) {
                        unlink($this->getParameter('upload_dir') . '/' . $originalImageLogo);
                    }
                } else {
                    // getData sur une checkbox = true si cochée, false sinon
                    if ($form->has('remove_image') && $form->get('remove_image')->getData()) {
                        $equipe->setImage(null);
                        unlink($this->getParameter('upload_dir') . '/' . $originalImageLogo);
                    } else {
                        $club->setImage($originalImageLogo);
                    }
                }
                $em->persist($club);
                //fait l'enregistrement en bdd
                $em->flush();
                
                //Ajout du message flash
                $this->addFlash('success', 'Le club '.$club->getNom().' a été enregistré');
                //redirection vers la liste
                return $this->redirectToRoute('app_admin_club_profil');                
            } else {
                $this->addFlash('error', 'Le formulaire contient des erreurs');
            }
        }
        
        $nomClub = $club->getNom();
        return $this->render('admin/club/edit.html.twig', 
                 [
                     'form' => $form->createView(),
                     'nomClub' => $nomClub
                     
                 ]
        );
    }

    
    /**
     * @Route("/delete/{id}")
     * @param int $id
     */
    public function delete($id)
    {
        $club = new Club();
        $em = $this->getDoctrine()->getManager();
        //racourci pour $em->getRepository(Club::class)->find($id)
        $club = $em->find(Club::class, $id);
        
        //suppression de la categorie en bdd
        $em->remove($club);
        $em->flush();
        
        //ajout d'un message
        $this->addFlash('success', 'Le club a été supprimé');
        //redirection vers la liste des categories
        return $this->redirectToRoute('app_admin_club_index');
        
    }
    
     /**
     * @Route("/profil")
     */
    public function profil()
    {
         
        if(!is_null($this->getUser())){
            $saisonRepository = $this->getDoctrine()->getRepository(Saison::class);
            $saisons = $saisonRepository->listSaisonClub($this->getUser()->getClub()->getId());
            $nbsaison = count($saisons);
            
            //dump($nbsaison);
        } else {
            $nbsaison = 0;
        }

        $club = $this->getDoctrine()->getRepository(Club::class )->find($this->getUser()->getClub()->getId());
        $nomClub = $this->getUser()->getClub()->getNom();
        $idClub = $this->getUser()->getClub()->getId();
      
        
        return $this->render('admin/club/profil.html.twig', 
                 [
                     'club' => $club,
                     'nomClub' => $nomClub,
                     'nbsaisons' => $nbsaison
                     
                 ]
        );
    }
}
