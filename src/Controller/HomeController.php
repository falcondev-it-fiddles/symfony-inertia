<?php

namespace App\Controller;

use Symfony\Component\HttpKernel\Kernel;
use Rompetomp\InertiaBundle\Service\InertiaInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\ToDo;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class HomeController extends AbstractController
{
    /** @var \Rompetomp\InertiaBundle\Service\InertiaInterface */
    protected $inertia;

    /**
      * AppSubscriber constructor.
      *
      * @param \Rompetomp\InertiaBundle\Service\InertiaInterface $inertia
      */
    public function __construct(InertiaInterface $inertia)
    {
        $this->inertia = $inertia;
    }

    public function index(ManagerRegistry $doctrine): Response
    {
        return $this->inertia->render('home', [
            'todos' => $doctrine->getRepository(ToDo::class)->findAll()
        ]);
    }

    public function create(Request $request, ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
        $todo = new ToDo();

        $data = json_decode($request->getContent(), true);
        $todo->setName($data['name']);
        $todo->setDone($data['done']);

        $entityManager->persist($todo);

        $entityManager->flush();

        return $this->redirect('/');
    }

    public function update(Request $request, ManagerRegistry $doctrine, int $id): Response
    {
        $entityManager = $doctrine->getManager();
        $todo = $entityManager->getRepository(ToDo::class)->find($id);

        if (!$todo) {
            throw $this->createNotFoundException(
                'No todo found for id '.$id
            );
        }

        $data = json_decode($request->getContent(), true);
        $todo->setName($data['name']);
        $todo->setDone($data['done']);

        $entityManager->flush();

        return $this->redirect('/');
    }

    public function delete(ManagerRegistry $doctrine, int $id): Response
    {
        $entityManager = $doctrine->getManager();
        $todo = $entityManager->getRepository(ToDo::class)->find($id);

        if (!$todo) {
            throw $this->createNotFoundException(
                'No todo found for id '.$id
            );
        }

        $entityManager->remove($todo);

        $entityManager->flush();

        return $this->redirect('/');
    }
}
