<?php

namespace App\Controller;

use App\Entity\Recette;
use App\Form\RecetteType;
use App\Repository\RecetteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RecetteController extends AbstractController
{
    /**
     * Controller for display all recipes
     *
     * @param RecetteRepository $repository
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */
    #[Route('/recette', name: 'recette', methods: ['GET'])]
    public function index(RecetteRepository $repository, PaginatorInterface $paginator, Request $request): Response
    {

        $recettes = $paginator->paginate(
            $repository->findAll(),
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('pages/recette/index.html.twig', [
            'recettes' => $recettes
        ]);
    }

    /**
     * Controller for create a new recipe
     *
     * @param Recette $recette
     * @return Response
     */
    #[Route('/recette/nouveau', name: 'recette.create', methods: ['GET', 'POST'])]
    public function create(Request $request, EntityManagerInterface $manager): Response
    {

        $recette = new Recette();
        $form = $this->createForm(RecetteType::class, $recette);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $recette = $form->getData();
            $manager->persist($recette);
            $manager->flush();

            $this->addFlash('success', 'La recette a bien été ajouté');

            return $this->redirectToRoute('recette');
        } else {
            $this->addFlash('danger', 'La recette n\'a pas été ajouté');
        }


        return $this->render('pages/recette/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * Controller for edit a recipe
     *
     * @param Recette $recette
     * @return Response
     */
    #[Route('/recette/edition/{id}', name: 'recette.edit', methods: ['GET', 'POST'])]
    public function edit(Recette $recette, Request $request, EntityManagerInterface $manager): Response
    {

        $form = $this->createForm(RecetteType::class, $recette);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $recette = $form->getData();
            $manager->persist($recette);
            $manager->flush();

            $this->addFlash('success', 'La recette a bien été modifié');

            return $this->redirectToRoute('recette');
        } else {
            $this->addFlash('danger', 'La recette n\'a pas été modifié');
        }

        return $this->render("pages/recette/edit.html.twig", [
            "recette" => $recette,
            "form" => $form->createView()
        ]);
    }

    /**
     * This controller delete one ingredient
     *
     * @param Recette $recette
     * @return Response
     */
    #[Route('/recette/suppression/{id}', name: 'recette.delete', methods: ['GET', 'POST'])]
    public function delete(Recette $recette, EntityManagerInterface $manager): Response
    {

        $manager->remove($recette);
        $manager->flush();

        $this->addFlash('success', 'La recette a bien été supprimé');

        return $this->redirectToRoute('recette');
    }
}
