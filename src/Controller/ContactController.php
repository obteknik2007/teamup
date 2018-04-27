<?php

namespace App\Controller;

use App\Entity\Saison;
use App\Entity\Contact;
use App\Form\ContactType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;


/**
 * @Route("/contact")
 */
class ContactController extends Controller
{ 
    /**
     * @Route("/")
     */
    public function Add(Request $request)
    {
        //entity manager gere les objets et les lignes en bdd
        $em= $this->getDoctrine()->getManager();
            
            $contact = new Contact();
        
        //création du formulaire lié à l'équipe
        $form = $this->createForm(ContactType::class, $contact);
        
        //le formulaire traite la requete HTTP
        $form->handleRequest($request);
        
        //si le formulaire à été envoyé
        if ($form->isSubmitted()){
            //s'il n'y à pas d'erreurs de validation du formulaire
            if ($form->isValid()){
                //prepare l'enregistrement en bdd
                $em->persist($contact);
                //fait l'enregistrement en bdd
                $em->flush();
                
                //Ajout du message flash
                $this->addFlash('success', 'Votre message a été enregistré');
                //redirection vers la liste
                return $this->redirectToRoute('app_contact_add');                
            } else {
                $this->addFlash('error', 'Le formulaire contient des erreurs');
            }
        }
        
        if(!is_null($this->getUser())){
            $saisonRepository = $this->getDoctrine()->getRepository(Saison::class);
            $saisons = $saisonRepository->listSaisonClub($this->getUser()->getClub()->getId());
            $nbsaison = count($saisons);
            
            //dump($nbsaison);
        } else {
            $nbsaison = 0;
        }
        
        
         return $this->render('contact/index.html.twig', 
                 [
                     'form' => $form->createView(),
                     'nbsaisons' => $nbsaison
                 ]
        );
    }
        
}
