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
use Symfony\Component\Form\FormTypeInterface;

use App\Entity\ArticleSemaine;
use App\Entity\Tache;
use App\Repository\ArticleSemaineRepository;
use App\Repository\TacheRepository;
use App\Form\SemaineType;
use App\Form\TacheType;

class CalendarController extends AbstractController
{


    /**
     * @Route("/calendar", name="calendar")
     */
    public function index(ArticleSemaineRepository $repo)
    {
        $week = $repo->findAll();
        // $week = $week[-2:-1];
    

        return $this->render('calendar/index.html.twig', [
            'controller_name' => 'CalendarController',
            'articles' => $week
        ]);
    }

    /**
     * @Route("/", name="home")
     */
    public function home(ArticleSemaineRepository $repo){
        
        $week = $repo->findAll();
        
        return $this->render('calendar/home.html.twig', [
            'title' => "Bienvenue sur mon calendrier !"
        ]);
    }

    /**
     * @Route("/calendar/new", name="calendar_create")
     * @Route("/calendar/{id}/edit", name="calendar_edit")
     */
     public function form(ArticleSemaine $newSemaine = null, ArticleSemaineRepository $repo, Request $request, EntityManagerInterface $manager){

        $week = $repo->findAll();

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
     * @Route("/calendar/task/new", name="task_create")
     * @Route("/calendar/task/{id}/edit", name="task_edit")
     */
    public function formTask($id = null, TacheRepository $repo, ArticleSemaineRepository $reposit ,Request $request, EntityManagerInterface $manager){

        $week = $reposit->findAll();

        if(!$id){
            $newTask = new Tache();
        }
        else{
            $newTask = $repo->find($id);
        }
        
        // Utilise le formulaire qui se situe dans le fichier SemaineType.php
        $form = $this->createForm(TacheType::class, $newTask);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){            
            $manager->persist($newTask);
            $manager->flush();

            return $this->redirectToRoute('calendar');
        }

        return $this->render('calendar/addTask.html.twig',[
            'formTache' => $form->createView(),
            'editMode' => $newTask->getId() !== null
        ]);
    }


    /**
     * @Route("/calendar/{id}", name="calendar_show")
     */
    public function show(ArticleSemaine $article, ArticleSemaineRepository $repo){

        $week = $repo->findAll();


        return $this->render('calendar/show.html.twig', [
            'element' => $article
        ]);
        
    }


    /**
     * @Route("/delete/{id}", name="delete_task")
     */
    public function DeleteTask($id, TacheRepository $repo, EntityManagerInterface $manager)
    {
        $taskList = $repo->find($id);
        $listID = $taskList->getSemaine();
        $manager->remove($taskList);
        $manager->flush();
        
        $this->addFlash("notice", sprintf("Task has been deleted"));

        return $this->redirectToRoute("calendar_show",[
            'id' => $listID->getId()
        ]);
    }

}
