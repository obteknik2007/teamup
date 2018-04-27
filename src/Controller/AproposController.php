<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AproposController extends Controller
{
    /**
     * @Route("/apropos")
     */
    public function index()
    {
        return $this->render('apropos/index.html.twig', [
            
        ]);
    }
}
