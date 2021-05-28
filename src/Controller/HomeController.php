<?php

namespace App\Controller;

use App\Repository\TagRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends Controller
{
    /**
     * @Route("/", name="default")
     */
    public function default(): Response
    {
        return $this->redirectToRoute('home');
    }

    /**
     * @Route("/home", name="home")
     */
    public function index(TagRepository $tagRepository): Response
    {
        $mostUsedTags = $tagRepository->getMostUsed();
        return $this->render('home/index.html.twig', compact('mostUsedTags'));
    }
}
