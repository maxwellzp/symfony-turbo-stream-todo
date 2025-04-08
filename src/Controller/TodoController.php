<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Todo;
use App\Form\TodoType;
use App\Repository\TodoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\UX\Turbo\TurboBundle;

final class TodoController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly TodoRepository         $todoRepository,
    )
    {
    }

    #[Route('/todo', name: 'app_todo')]
    public function index(
        Request $request,
    ): Response
    {
        $todo = new Todo();
        $todos = $this->todoRepository->findAll();

        $form = $this->createForm(TodoType::class, $todo);

        $emptyForm = clone $form;
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($todo);
            $this->entityManager->flush();

            if (TurboBundle::STREAM_FORMAT === $request->getPreferredFormat()) {
                $request->setRequestFormat(TurboBundle::STREAM_FORMAT);
                return $this->renderBlock('todo/_stream.html.twig', 'append', [
                    'todo' => $todo,
                    'form' => $emptyForm,
                ]);
            }

            return $this->redirectToRoute('app_todo');
        }

        return $this->render('todo/index.html.twig', [
            'form' => $form,
            'todos' => $todos
        ]);
    }

    #[Route('/remove/{id}', name: 'remove_todo', methods: ['POST'])]
    public function remove(
        int    $id,
        Request $request
    ): Response
    {
        $todo = $this->todoRepository->find($id);
        $this->entityManager->remove($todo);
        $this->entityManager->flush();
        if (TurboBundle::STREAM_FORMAT === $request->getPreferredFormat()) {
            $request->setRequestFormat(TurboBundle::STREAM_FORMAT);
            return $this->renderBlock('todo/_stream.html.twig', 'remove', ['id' => $id]);
        }
        return $this->redirectToRoute('app_todo');
    }
}
