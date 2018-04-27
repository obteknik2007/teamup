<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Form\CategoryType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/category")
 * Toutes les routes vont être pré&fixées par /admin du fait de l'inscription
 * 'admin' dans le fichier annotations.yaml
 */
class CategoryController extends Controller
{
    /**
     * @Route("/") //ttes méthodes vont alors être dans Admin/category/
     */
    public function index()
    {
        $repository = $this->getDoctrine()->getRepository(Category::class);
        
        //on recup ttes les catégories
        $categories = $repository->findAll();
        
        return $this->render('admin/category/index.html.twig', [
           'categories' => $categories
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
            $category = new Category();
        } else {
            $category = $em->getRepository(Category::class)->find($id);
        }        
        
        //création du formulaire lié à la catégorie
        $form = $this->createForm(CategoryType::class, $category);
        
        //le formulaire traite la requete HTTP
        $form->handleRequest($request);
        
        //si le formulaire à été envoyé
        if ($form->isSubmitted()){
            //s'il n'y à pas d'erreurs de validation du formulaire
            if ($form->isValid()){
                //prepare l'enregistrement en bdd
                $em->persist($category);
                //fait l'enregistrement en bdd
                $em->flush();
                
                //Ajout du message flash
                $this->addFlash('success', 'La catégorie '.$category->getName().' a été enregistrée');
                //redirection vers la liste
                return $this->redirectToRoute('app_admin_category_index');                
            } else {
                $this->addFlash('error', 'Le formulaire contient des erreurs');
            }
        }
        
         return $this->render('admin/category/edit.html.twig', 
                 [
                     'form' => $form->createView()
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
        //racourci pour $em->getRepository(Category::class)->find($id)
        $category = $em->find(Category::class, $id);
        
        //suppression de la categorie en bdd
        $em->remove($category);
        $em->flush();
        
        //ajout d'un message
        $this->addFlash('success', 'La catégorie a été supprimée');
        //redirection vers la liste des categories
        return $this->redirectToRoute('app_admin_category_index');
        
    }
}
