<?php

namespace App\Controller\Admin;

use App\Entity\Saison;
use App\Entity\Staff;
use App\Form\StaffType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/staff")
 */
class StaffController extends Controller
{
    /**
     * @Route("/")
     */
    public function index()
    {
        $repository = $this->getDoctrine()->getRepository(Staff::class);
        
        //on recup ts les matchs
        //$staffs = $repository->findAll();
        
        if(!is_null($this->getUser())){
            //récup staff du club
            $staffClubRepository = $this->getDoctrine()->getRepository(Staff::class);
            $membres = $staffClubRepository->StaffDuClub($this->getUser()->getClub()->getId());
        } else {
            $membres ='';
        }  
        
        return $this->render('admin/staff/index.html.twig', [
            'membres' => $membres
        ]);
    }
    
    
    /**
    * @Route("/edit/{id}", defaults={"id":null})
    */
    public function edit(Request $request,$id)
    {
        //entity manager gere les objets et les lignes en bdd
        $em= $this->getDoctrine()->getManager();
        $originalImage = null;
        
        if(is_null($id)){
            $staff= new Staff();
            $staff->setClub($this->getUser()->getClub());
            
        } else {
            $staff = $em->getRepository(Staff::class)->find($id);
        }        
        
         if (!is_null($staff->getImage())) {
                $originalImage = $staff->getImage();
                $imagePath = $this->getParameter('upload_dir') . '/' . $originalImage;
                // objet File pour éviter une erreur du formulaire
                $staff->setImage(new File($imagePath));
            }
        
        //création du formulaire lié à l'équipe
        $form = $this->createForm(StaffType::class, $staff);
        
        //le formulaire traite la requete HTTP
        $form->handleRequest($request);
        
        //si le formulaire à été envoyé
        if ($form->isSubmitted()){
            //s'il n'y à pas d'erreurs de validation du formulaire
            if ($form->isValid()){
                
                /** @var UploadedFile $image */
                $image = $staff->getImage();
                
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
          
                    $staff->setImage($filename);
                    
                    // suppression de l'ancienne image en modification
                    if (!is_null($originalImage)) {
                        unlink($this->getParameter('upload_dir') . '/' . $originalImage);
                    }
                } else {
                    // getData sur une checkbox = true si cochée, false sinon
                    if ($form->has('remove_image') && $form->get('remove_image')->getData()) {
                        $staff->setImage(null);
                        unlink($this->getParameter('upload_dir') . '/' . $originalImage);
                    } else {
                        $staff->setImage($originalImage);
                    }
                }            

             
                //prepare l'enregistrement en bdd
                $em->persist($staff);
                //fait l'enregistrement en bdd
                $em->flush();
                
                //Ajout du message flash
                $this->addFlash('success', 'Le membre du staff a été enregistré, vous devez à present lui attribuer une équipe');
                //redirection vers la liste
                return $this->redirectToRoute('app_admin_staff_index');                
            } else {
                $this->addFlash('error', 'Le formulaire contient des erreurs');
            }
        }
        $prenom_nom = $staff->getPrenom().' '.$staff->getNom();
         return $this->render('admin/staff/edit.html.twig', 
                 [
                     'form' => $form->createView(),
                     'prenom_nom' => $prenom_nom
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
        $staff = $em->find(Staff::class, $id);
        
        //suppression de la categorie en bdd
        $em->remove($staff);
        $em->flush();
        
        //ajout d'un message
        $this->addFlash('success', 'Le membre du staff a été supprimé');
        //redirection vers la liste des categories
        return $this->redirectToRoute('app_admin_staff_index');
        
    }    
}
