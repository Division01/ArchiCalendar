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
        // On récupère chaque semaines du repo
        $week = $repo->findAll();
        // On renvoie vers index.html.twig pour rendre la page, avec les semaines sous le nom d'articles
        return $this->render('calendar/index.html.twig', [
            'controller_name' => 'CalendarController',
            'articles' => $week
        ]);
    }

    /**
     * @Route("/", name="home")
     */
    public function home(){
        // On renvoie vers home.html.twig pour rendre la page, avec le titre renommable
        return $this->render('calendar/home.html.twig', [
            'title' => "Bienvenue sur mon calendrier !"
        ]);
    }

    /**
     * @Route("/calendar/new", name="calendar_create")
     * @Route("/calendar/{id}/edit", name="calendar_edit")
     */
     public function form(ArticleSemaine $newSemaine = null, Request $request, EntityManagerInterface $manager){

        // Comme on utilise le même pour créer et edit, il faut vérifier si elle existe et la créer sinon
        if(!$newSemaine){
            $newSemaine = new ArticleSemaine();
        }

        // On utilise le formulaire qui se situe dans le fichier SemaineType.php
        $form = $this->createForm(SemaineType::class, $newSemaine);

        $form->handleRequest($request);

        // On vérifie que le formulaire est bien respecté et que la forme
        // est bonne pour conclure l'opération et renvoyer à la page de la semaine
        if($form->isSubmitted() && $form->isValid()){
            if(!$newSemaine->getId()){
                $newSemaine->setCreatedAt(new \DateTime());
            }
            
            // Tout est bon, on valide et on envoie
            $manager->persist($newSemaine);
            $manager->flush();

            // On redirect vers la page de la nouvelle semaine
            return $this->redirectToRoute('calendar_show',[
                'id' => $newSemaine->getId()]);
        }

        // On envoie au html pour que l'utilisateur puisse remplir le formulaire
        // Donc oui, paradoxalement se passe avant le if au-dessus
        return $this->render('calendar/create.html.twig',[
            'formSemaine' => $form->createView(),
            'editMode' => $newSemaine->getId() !== null
        ]);
    }



    /**
     * @Route("/calendar/task/new", name="task_create")
     * @Route("/calendar/task/{id}/edit", name="task_edit")
     */
    public function formTask($id = null, TacheRepository $repo, Request $request, EntityManagerInterface $manager){

        // Soit une nouvelle tâche a créé, soit on la trouve à partir de son ID
        if(!$id){
            $newTask = new Tache();
        }
        else{
            $newTask = $repo->find($id);
        }
        
        // Utilise le formulaire qui se situe dans le fichier SemaineType.php
        $form = $this->createForm(TacheType::class, $newTask);

        $form->handleRequest($request);

        // Une fois le formulaire renvoyé, on vérifie que le formulaire est bien respecté 
        // et que la forme est bonne et on renvoie a la vue de la semaine de la tâche modifiée/créée
        if($form->isSubmitted() && $form->isValid()){            
            $manager->persist($newTask);
            $manager->flush();
            return $this->redirectToRoute('calendar_show',[
                'id' => $newTask->getSemaine()->getId()]);
        }

        // On envoie au html pour que l'utilisateur puisse remplir le formulaire
        // Donc oui, paradoxalement se passe avant le if au-dessus
        return $this->render('calendar/addTask.html.twig',[
            'formTache' => $form->createView(),
            'editMode' => $newTask->getId() !== null
        ]);
    }


    /**
     * @Route("/calendar/{id}", name="calendar_show")
     */
    public function show(ArticleSemaine $semaine, ArticleSemaineRepository $asr){
        //$semaine = $asr->find($id)
        //C'est ce que j'aurais dû mettre mais symfony est assez intelligent
        //Que pour lier tout seul l'ID a la semaine
        return $this->render('calendar/show.html.twig', [
            'element' => $semaine
        ]);
    }


    /**
     * @Route("/delete_task/{id}", name="delete_task")
     */
    public function DeleteTask($id, TacheRepository $repo, EntityManagerInterface $manager)
    {
        // On trouve la tache a partir de son ID 
        $task = $repo->find($id);
        // On prends l'ID de la semaine à laquelle est associée la tache
        $listID = $task->getSemaine();
        // On supprime la tache de la bdd
        $manager->remove($task);
        $manager->flush();
        
        $this->addFlash("notice", sprintf("Task has been deleted"));

        return $this->redirectToRoute("calendar_show",[
            'id' => $listID->getId()
        ]);
    }


    /**
     * @Route("/delete_week/{id}", name="delete_week")
     */
    public function DeleteWeek($id, ArticleSemaineRepository $repo, EntityManagerInterface $manager)
    {
        // On trouve la semaine associée a l'id
        $week = $repo->find($id);
        // On trouve les taches associées a la semaine
        $tasksAssociated = $week->getTaches();
        // On supprime chaque tache une a une
        foreach ($tasksAssociated as $tache){
            $manager->remove($tache);
        }
        // On supprime la semaine
        $manager->remove($week);
        $manager->flush();
        
        $this->addFlash("notice", sprintf("Week and associated tasks have been deleted"));

        return $this->redirectToRoute('calendar');
    }
}
