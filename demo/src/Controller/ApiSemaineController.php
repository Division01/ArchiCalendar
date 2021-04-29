<?php

namespace App\Controller;

use App\Entity\ArticleSemaine;
use App\Repository\ArticleSemaineRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\SerializerInterface;


class ApiSemaineController extends AbstractController
{
    /**
     * @Route("/api/semaine", name="api_semaine_index", methods={"GET"})
     */
    public function index(ArticleSemaineRepository $articleSemaineRepository)
    {
        return $this->json($articleSemaineRepository->findAll(), 200, [], ['groups'=>'semaine:read']);
    }

    /**
     * @Route("/api/semaine", name="api_semaine_create", methods={"POST"})
     */
    public function create(Request $request, ValidatorInterface $validator, EntityManagerInterface $em, SerializerInterface $serializer ){

            $jsonRecu = $request->getContent();

            try{
                $semaine = $serializer->deserialize($jsonRecu, ArticleSemaine::class, 'json');
                $semaine->setCreatedAt(new \DateTime());

                $errors = $validator->validate($semaine);

                if(count($errors)>0){
                    return $this->json($errors,400);
                }

                $em->persist($semaine);
                $em->flush();

            } catch(NotEncodableValueException $e){
                return $this->json([
                    'status' => 400,
                    'message' => $e->getMessage()], 400
                );
            }
            dd($jsonRecu);
    }
}