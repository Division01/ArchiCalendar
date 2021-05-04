<?php

namespace App\Controller;

use App\Entity\ArticleSemaine;
use App\Entity\Tache;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ArticleSemaineRepository;
use App\Repository\TacheRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;

class ApiSemaineController extends AbstractController
{


//////////
// GET ///   
//////////

    /**
     * @Route("/api/semaines/", name="api_semaine_index", methods={"GET"})
     */
    public function index(ArticleSemaineRepository $articleSemaineRepository)
    {
        $response = new JsonResponse();
        return $this->json(
            $articleSemaineRepository->findAll(), 
            200,
            [
                'Content-Type'=> 'application/json',
                'Access-Control-Allow-Origin'=> 'http://localhost:4200',
                "Access-Control-Allow-Methods"=>"GET, PUT, POST, DELETE, OPTIONS",
                'Access-Control-Allow-Headers'=> 'Content-Type, Accept, Authorization, X-Requested-With'
            ], 
            ['groups'=>'semaine:read'] 
        );
    }



//////////
// GET ///   
//////////

    /**
     * @Route("/api/semaines/{id}", name="api_une_semaine", methods={"GET"})
     */
    public function une_semaine($id = null, ArticleSemaineRepository $articleSemaineRepository)
    {
        $response = new JsonResponse();
        return $this->json(
            $articleSemaineRepository->find($id),
            200,
            [
                'Content-Type'=> 'application/json',
                'Access-Control-Allow-Origin'=> 'http://localhost:4200',
                "Access-Control-Allow-Methods"=>"GET, PUT, POST, DELETE, OPTIONS",
                'Access-Control-Allow-Headers'=> 'Content-Type, Accept, Authorization, X-Requested-With'
            ], 
            ['groups'=>'semaine:read'] 
        );
    }



//////////
// POST //   
//////////


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



//////////
// PUT ///   
//////////

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


////////////
// DELETE //   
////////////

    /**
     * @Route("/api/semaines/delete/{id}", name="api_delete_tache", methods={"DELETE"})
     */
    public function supprimage_semaine($id = null, ArticleSemaineRepository $asr, EntityManagerInterface $manager)
    {
        $manager->remove($asr->find($id));
        $manager->flush();
    }



///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////// TACHES ///////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////    



//////////
// POST //   
//////////


    /**
     * @Route("/api/tache", name="api_tache_create", methods={"POST"})
     */
    public function creage_tache(Request $request, ValidatorInterface $validator, EntityManagerInterface $em, SerializerInterface $serializer ){

        $jsonRecu = $request->getContent();

        try{
            $tache = $serializer->deserialize($jsonRecu, Tache::class, 'json');

            $errors = $validator->validate($tache);
            
            if(count($errors)>0){
                return $this->json($errors,400);
            }

            $em->persist($tache);
            $em->flush();

            return $this->json([
                'message' => 'Tache bien créé']
            );


        } catch(NotEncodableValueException $e){
            return $this->json([
                'status' => 400,
                'message' => $e->getMessage()], 400
            );
        }
        dd($jsonRecu);
    }

//////////
// GET ///   
//////////

    /**
     * @Route("/api/tache/{id}", name="api_une_tache", methods={"GET"})
     */
    public function une_tache($id = null, TacheRepository $tr)
    {
        $response = new JsonResponse();
        return $this->json(
            $tr->find($id),
            200,
            [
                'Content-Type'=> 'application/json',
                'Access-Control-Allow-Origin'=> 'http://localhost:4200',
                "Access-Control-Allow-Methods"=>"GET, PUT, POST, DELETE, OPTIONS",
                'Access-Control-Allow-Headers'=> 'Content-Type, Accept, Authorization, X-Requested-With'
            ], 
            ['groups'=>'semaine:read'] 
        );
    }

//////////
// PUT ///   pas fé
//////////

    /**
     * @Route("/api/tache/edit/{id}", name="api_tache_update", methods={"PUT"})
     */
    public function update_task(Request $request, ValidatorInterface $validator, EntityManagerInterface $em, SerializerInterface $serializer ){

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



////////////
// DELETE //   
////////////

    /**
     * @Route("/api/tache/delete/{id}", name="api_delete_tache", methods={"DELETE"})
     */
    public function supprimage_tache($id = null, TacheRepository $tr, EntityManagerInterface $manager)
    {
        $manager->remove($tr->find($id));
        $manager->flush();
    }


}