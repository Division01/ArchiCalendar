<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use App\Entity\ArticleSemaine;
use App\Repository\ArticleSemaineRepository;
use App\Form\SemaineType;

class CalendarController extends AbstractController
{
    /**
     * @Route("/calendar", name="calendar")
     */
    public function index(ArticleSemaineRepository $repo)
    {
        $articles = $repo->findAll();

        return $this->render('calendar/index.html.twig', [
            'controller_name' => 'CalendarController',
            'articles' => $articles
        ]);
    }

    /**
     * @Route("/", name="home")
     */
    public function home(){
        return $this->render('calendar/home.html.twig', [
            'title' => "Bienvenue sur mon calendrier !"
        ]);
    }

    /**
     * @Route("/calendar/new", name="calendar_create")
     * @Route("/calendar/{id}/edit", name="calendar_edit")
     */
     public function form(ArticleSemaine $newSemaine = null, Request $request, EntityManagerInterface $manager){


        if(!$newSemaine){
            $newSemaine = new ArticleSemaine();
        }

        // Utilise le formulaire qui se situe dans le fichier SemaineType.php
        $form = $this->createForm(SemaineType::class, $newSemaine);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            if(!$newSemaine->getId()){
                $newSemaine->setCreatedAt(new \DateTime());
            }
            
            $manager->persist($newSemaine);
            $manager->flush();

            return $this->redirectToRoute('calendar_show',[
                'id' => $newSemaine->getId()]);
        }

        return $this->render('calendar/create.html.twig',[
            'formSemaine' => $form->createView(),
            'editMode' => $newSemaine->getId() !== null
        ]);
    }

    /**
     * @Route("/calendar/{id}", name="calendar_show")
     */
    public function show(ArticleSemaine $article){
        return $this->render('calendar/show.html.twig', [
            'element' => $article
        ]);
    }
}
