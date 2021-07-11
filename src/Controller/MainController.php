<?php

namespace App\Controller;

use App\Entity\Crud;
use App\Form\CrudType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    #[Route('/', name: 'main')]
    public function index(): Response
    {
        $curds = $this->getDoctrine()->getRepository(Crud::class)->findAll();
        return $this->render('main/index.html.twig', [
            'datas' => $curds
        ]);
    }

    #[Route('create', name: 'create')]
    public function create(Request $request): Response
    {
        $crud = new Crud();

        $form = $this->createForm(CrudType::class, $crud);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $em->persist($crud);
            $em->flush();

            $this->addFlash('notice', "Successfully crud created!!!");
            return $this->redirectToRoute('main');
        }

        return $this->render('main/create.html.twig', ['form' => $form->createView()]);
    }

    #[Route('update/{crud}', name: 'update')]
    public function update(Request $request, Crud $crud): RedirectResponse|Response
    {
        $form = $this->createForm(CrudType::class, $crud);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $em->persist($crud);
            $em->flush();

            $this->addFlash('notice', "Successfully crud updated!!!");
            return $this->redirectToRoute('main');
        }

        return $this->render('main/create.html.twig', ['form' => $form->createView()]);
    }

    #[Route('delete/{crud}', name: 'delete')]
    public function destroy(Crud $crud): RedirectResponse
    {
        $db = $this->getDoctrine()->getManager();

        $db->remove($crud);

        $db->flush();

        $this->addFlash('delete', 'Successfully crud deleted!!!');

        return $this->redirectToRoute('main');
    }
}
