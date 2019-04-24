<?php

namespace App\Controller\Api;

use App\Entity\CardCollection;
use App\Entity\Note;
use App\Entity\User;
use App\Repository\CardCollectionRepository;
use App\Repository\CardRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

/**
 * @Route("/api/collections", name="api_collections_")
 */

class CollectionsController extends Controller
{

    /**
     * AJAX
     * @Route("/create", name="create")
     * @Method({"POST"})
     */
    public function create(Request $request, CardRepository $cardRepo)
    {
        $user = $this->getUser();

        $responseContent = [];

        if($user instanceof User){

            $cardsIds = $request->request->get("cards");
            $cards = $cardRepo->search(["cards" => $cardsIds]);
            $name = $request->request->get("name");

            $collection = new CardCollection();
            $collection->setUser($user);
            $collection->setCards($cards);
            $collection->setName($name);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($collection);
            $entityManager->flush();

            $responseContent = [
                "html" => $this->render('front/_shared/collection.html.twig', ['collection' => $collection])->getContent(),
                "id" => $collection->getId()
            ];
        }


        return new JsonResponse($responseContent);
    }

    /**
     * AJAX
     * @Route("/update", name="update")
     * @Method({"POST"})
     */
    public function update(Request $request, CardRepository $cardRepo, CardCollectionRepository $collectionRepo)
    {
        $user = $this->getUser();

        $responseContent = [];

        if($user instanceof User){

            $cardsIds = $request->request->get("cards");
            $cards = $cardRepo->search(["cards" => $cardsIds]);
            $name = $request->request->get("name");
            $id = $request->request->get("id");

            $collection = $collectionRepo->findOneBy(["id" => $id, "user" => $user]);

            if($collection) {
                $collection->setCards($cards);
                $collection->setName($name);

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($collection);
                $entityManager->flush();
            }

            $responseContent = [
                "html" => $this->render('front/_shared/collection.html.twig', ['collection' => $collection])->getContent(),
                "id" => $collection->getId()
            ];
        }

        return new JsonResponse($responseContent);
    }

    /**
     * AJAX
     * @Route("/delete", name="delete")
     * @Method({"POST"})
     */
    public function delete(Request $request, CardRepository $cardRepo, CardCollectionRepository $collectionRepo)
    {
        $user = $this->getUser();
        if($user instanceof User){

            $id = $request->request->get("id");
            $collection = $collectionRepo->findOneBy(["id" => $id, "user" => $user]);

            if($collection) {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->remove($collection);
                $entityManager->flush();
            }

        }

        return new JsonResponse();
    }



    /**
     * AJAX
     * @Route("/note/add", name="note_add")
     * @Method({"POST"})
     */
    public function note_add(Request $request, CardCollectionRepository $collectionRepo)
    {
        $user = $this->getUser();

        $responseContent = [];

        if($user instanceof User){

            $text = $request->request->get("text");
            $id = $request->request->get("id");

            $collection = $collectionRepo->findOneBy(["id" => $id, "user" => $user]);

            if($collection) {

                $note = new Note();
                $note->setText($text);

                $collection->addNote($note);

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($collection);
                $entityManager->flush();

            }

            $responseContent = [
                "html" => $this->render('front/_shared/notes.html.twig', ['id' => $collection->getId(), 'notes' => $collection->getNotes()])->getContent(),
                "id" => $collection->getId(),
                "total" => count($collection->getNotes())
            ];
        }


        return new JsonResponse($responseContent);
    }

    /**
     * AJAX
     * @Route("/note/remove", name="note_remove")
     * @Method({"POST"})
     */
    public function note_remove(Request $request, CardCollectionRepository $collectionRepo)
    {
        $user = $this->getUser();

        $responseContent = [];

        if($user instanceof User){

            $id = $request->request->get("id");
            $idNote = $request->request->get("idNote");

            $collection = $collectionRepo->findOneBy(["id" => $id, "user" => $user]);

            if($collection) {

                foreach($collection->getNotes()->getIterator() as $note){
                    if($note instanceof Note) {
                        if ($note->getId() === intval($idNote)) {
                            $collection->removeNote($note);
                        }
                    }
                }

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($collection);
                $entityManager->flush();

            }

            $responseContent = [
                "html" => $this->render('front/_shared/notes.html.twig', ['id' => $collection->getId(), 'notes' => $collection->getNotes()])->getContent(),
                "id" => $collection->getId(),
                "total" => count($collection->getNotes())
            ];
        }


        return new JsonResponse($responseContent);
    }
}