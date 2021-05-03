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
use Symfony\Component\Serializer\Exception\NotEncodableValueException;

class ApiSemaineController extends AbstractController
{
    /**
     * @Route("/api/semaine", name="api_semaine_index", methods={"GET"})
     */
    public function index(ArticleSemaineRepository $articleSemaineRepository)
    {
        $response = new JsonResponse();

        $response->headers->set('Content-Type', 'application/json');

        $response->headers->set('Access-Control-Allow-Origin', '*');

        $response->headers->set("Access-Control-Allow-Methods", "GET, PUT, POST, DELETE, OPTIONS");

        $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, Accept, Authorization, X-Requested-With', true);

        $response->setContent($articleSemaineRepository->findAll(), 200, [], ['groups'=>'semaine:read']);

        return $response
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

                return $this->json([
                    'message' => 'Bien créé']
                );


            } catch(NotEncodableValueException $e){
                return $this->json([
                    'status' => 400,
                    'message' => $e->getMessage()], 400
                );
            }
            dd($jsonRecu);
    }

    /**
     * @Route("/api/semaine", name="api_semaine_update", methods={"PUT"})
     */
    public function update(Request $request, ValidatorInterface $validator, EntityManagerInterface $em, SerializerInterface $serializer ){

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