<?php
namespace App\Controller\Admin;

use App\Entity\Article;
use App\Entity\Rencontre;
use App\Entity\Saison;
use App\Form\ArticleType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/article")
 */
class ArticleController extends Controller
{
    /**
     * @Route("/")
     */
    public function index()
    {
        if(!is_null($this->getUser())){
            $saisonRepository = $this->getDoctrine()->getRepository(Saison::class);
            $saisons = $saisonRepository->listSaisonClub($this->getUser()->getClub()->getId());
            $nbsaison = count($saisons);
            $NomderniereSaisonClub = $saisonRepository->findNomLatestSaison($this->getUser()->getClub()->getId());
            $saison = $saisonRepository->findIdLatestSaison($this->getUser()->getClub()->getId());
            
        } else {
            $nbsaison = 0;
        }
        
        //Récupération de tous les articles d'une rencontre de la saison du club
        $articlesRepository = $this->getDoctrine()->getRepository(Article::class);
        $articles = $articlesRepository->ArticlesSaisonClub(
            $this->getUser()->getClub()->getId(),
            $saison);
            $nbArticles= count($articles);  
            
       //vue 
        return $this->render(
            '/admin/article/index.html.twig',
            [
                'nbsaisons' => $nbsaison,
                'NomderniereSaisonClub' => $NomderniereSaisonClub,
                'nbArticles' => $nbArticles,
                'articles' => $articles
            ]
        );
    }
    
    /**
     * @Route("/edit/{id}", defaults={"id": null})
     */
    public function edit(Request $request, $id)
    {
         $em = $this->getDoctrine()->getManager();
        $originalImage = null;
        
        if (is_null($id)) { // création
            $article = new Article();
            // l'auteur de l'article est l'utilisateur connecté
            $article->setAuthor($this->getUser());
        } else { // modification
            $article = $em->find(Article::class, $id);
            
            if (!is_null($article->getImage())) {
                $originalImage = $article->getImage();
                $imagePath = $this->getParameter('upload_dir') . '/' . $originalImage;
                // objet File pour éviter une erreur du formulaire
                $article->setImage(new File($imagePath));
            }
        }
        
            //alimentation de la clé étrangère CLUB
            $article->setClub($this->getUser()->getClub());

            //alimentation de la clé étrangère SAISON
            //Récupération id de la dernière saison enregistrée pour le club
            $SaisonClubRepository = $this->getDoctrine()->getRepository(Saison::class);
            $IdDerniereSaisonClub = $SaisonClubRepository->findIdLatestSaison($this->getUser()->getClub()->getId());

            $saison = $SaisonClubRepository->find($IdDerniereSaisonClub['id']);
            $article->setSaison($saison);          
            
        //création du formulaire lié à l'équipe
        $form = $this->createForm(ArticleType::class, $article);
        
        $form->handleRequest($request);
        
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                /** @var UploadedFile $image */
                $image = $article->getImage();
                
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
                    
                    $article->setImage($filename);
                    
                    // suppression de l'ancienne image en modification
                    if (!is_null($originalImage)) {
                        unlink($this->getParameter('upload_dir') . '/' . $originalImage);
                    }
                } else {
                    // getData sur une checkbox = true si cochée, false sinon
                    if ($form->has('remove_image') && $form->get('remove_image')->getData()) {
                        $article->setImage(null);
                        unlink($this->getParameter('upload_dir') . '/' . $originalImage);
                    } else {
                        $article->setImage($originalImage);
                    }
                }
                
                $em->persist($article);
                $em->flush();
                
                $this->addFlash('success', "L'article est enregistré");
                
                return $this->redirectToRoute('app_admin_article_index');
            } else {
                $this->addFlash('error', 'Le formulaire contient des erreurs');
            }
        }
        
        return $this->render(
            '/admin/article/edit.html.twig',
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
        $article = $em->find(Article::class, $id);
        
        $em->remove($article);
        $em->flush();
        
        // ajout d'un message
        $this->addFlash('success', "L'article est supprimé");
        // redirection vers la liste
        return $this->redirectToRoute('app_admin_article_index');
    }
}
