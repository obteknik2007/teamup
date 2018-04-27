<?php

namespace App\Controller\Admin;

use App\Entity\Joueur;
use App\Entity\User;
use App\Entity\Saison;
use App\Form\JoueurType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use function dump;
/**
* @Route("/joueur")
*/
class JoueurController extends Controller
{
    /**
     * @Route("/")
     */
    public function index()
    {
        
        //Récupération infos saison
        if(!is_null($this->getUser())){
            $saisonRepository = $this->getDoctrine()->getRepository(Saison::class);
            $saisons = $saisonRepository->listSaisonClub($this->getUser()->getClub()->getId());
            $NomderniereSaisonClub = $saisonRepository->findNomLatestSaison($this->getUser()->getClub()->getId());
            $nbsaison = count($saisons);    
           
        } else {
            
            $nbsaison = 0;
        }
        
         //on recup ts les joueurs
        //$joueurs = $repository->findAll();
        
        //Récup infos joueurs de la saison du club de l'user connecté

            $saisons = 1;
            $JoueurRepository = $this->getDoctrine()->getRepository(Joueur::class);
            $joueurs = $JoueurRepository->JoueursSaisonClub($this->getUser()->getClub()->getId(),$saisons);


       
        return $this->render('admin/joueur/index.html.twig', [
           'joueurs' => $joueurs,
            'nbsaisons' => $nbsaison,
            'NomderniereSaisonClub' => $NomderniereSaisonClub
        ]);
    }
    /**
     * @Route("/edit/{id}", defaults={"id":null})
     */
    public function edit(Request $request, UserPasswordEncoderInterface $passwordEncoder, $id)
    {
         $em= $this->getDoctrine()->getManager();
          // tentative gestion image---1 
        $originalImage = null;
        // fin tentative--1

        if(is_null($id)){
            $joueur = new Joueur();
        } else {
            $joueur = $em->getRepository(Joueur::class)->find($id);
          //tentative gestion image --2
            if (!is_null($joueur->getImage()) ) {
                $originalImage = $joueur->getImage();
                $imagePath = $this->getParameter('upload_dir') . '/' . $originalImage;
                // objet File pour éviter une erreur du formulaire
                $joueur->setImage(new File($imagePath)); //fin tentative --2
            }  
        }        
          // tentative gestion PDF---1 
        $originalFile = null;
        // fin tentative--1
        
        if(is_null($id)){
            $joueur = new Joueur();
        } else {
            $joueur = $em->getRepository(Joueur::class)->find($id);
          //tentative gestion PDF --2
            if (!is_null($joueur->getCertificat()) ) {
                $originalFile = $joueur->getCertificat();
                $filePath = $this->getParameter('upload_dir') . '/' . $originalFile;
                // objet File pour éviter une erreur du formulaire
                $joueur->setCertificat(new File($filePath)); //fin tentative --2
            }  
        }        
        
        //alimentation de la clé étrangère IDclub
        $joueur->setClub($this->getUser()->getClub());

        //alimentation de la clé étrangère IDjoueur
        //$joueur->setClub($this->getUser()->getClub());
        
        //alimentation de la clé étrangère IDsaison
            //Récupération id de la dernière saison enregistrée pour le club
            $SaisonClubRepository = $this->getDoctrine()->getRepository(Saison::class);
            $IdDerniereSaisonClub = $SaisonClubRepository->findIdLatestSaison($this->getUser()->getClub()->getId());

            $saison = $SaisonClubRepository->find($IdDerniereSaisonClub['id']);
            //dump($saison);
            $joueur->setSaison($saison);
        
        //Création du formulaire    
        $form = $this->createForm(JoueurType::class, $joueur);
        $form->handleRequest($request);
        
        //si le formulaire à été envoyé
        if ($form->isSubmitted()){
            //s'il n'y à pas d'erreurs de validation du formulaire
            if ($form->isValid()){
                $password = $passwordEncoder->encodePassword($joueur->getUser(), $joueur->getUser()->getPlainPassword());
                
                $joueur->getUser()
                    ->setPassword($password)
                    ->setRole('ROLE_USER')
                    ->setClub($this->getUser()->getClub())
                ;
                //prepare l'enregistrement en bdd
             //tentative gestion PDF--3
                /** @var UploadedFile $certificat */
                $certificat = $joueur->getCertificat();
                 // s'il y a une image uploadée
                if (!is_null($certificat)) {
                    // nom du fichier que l'on va enregistrer
                    $filename = uniqid() . '.' . $certificat->guessExtension();
                    // équivalent move_uploaded_file()
                    $certificat->move(
                        // upload_dir défini dans service.yaml
                        $this->getParameter('upload_dir'),
                        $filename
                    );
                    
                    $joueur->setCertificat($filename);
                    
                    // suppression de l'ancienne image en modification
                    if (!is_null($originalFile)) {
                        unlink($this->getParameter('upload_dir') . '/' . $originalFile);
                    }
                } 
                // fin tentative 3   
             //tentative gestion image--3
                /** @var UploadedFile $image */
                $image = $joueur->getImage();
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
                    
                    $joueur->setImage($filename);
                    
                    // suppression de l'ancienne image en modification
                    if (!is_null($originalImage)) {
                        unlink($this->getParameter('upload_dir') . '/' . $originalImage);
                    }
                } 
                // fin tentative 3   
   
                $em->persist($joueur);
                //fait l'enregistrement en bdd
                $em->flush();   
                
                ///ENVOI MAIL
                
                //Ajout du message flash
                //$this->addFlash('success', 'Le joueur '.$joueur->getFullName().' a été enregistré');
                $this->addFlash('success', 'Le joueur a été enregistré');
                //redirection vers la liste
                return $this->redirectToRoute('app_admin_joueur_index');                
            } else {
                $this->addFlash('error', 'Le formulaire contient des erreurs');
            }
        }
        
                $repository2 = $this->getDoctrine()->getRepository(User::class);
                $user = $repository2->findAll();
                
        
         return $this->render('admin/joueur/edit.html.twig', 
                 [
                     'form' => $form->createView(),
                     'joueur' => $joueur,
                     'user' => $user
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
        $joueur = $em->find(Joueur::class, $id);

        $em->remove($joueur);
        $em->flush();
        
        //ajout d'un message
        $this->addFlash('success', 'Le joueur a été supprimée');
        //redirection vers la liste 
        return $this->redirectToRoute('app_admin_joueur_index');
        
    }    
}
