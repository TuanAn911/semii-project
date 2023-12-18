<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryFormType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CategoryController extends AbstractController
{
    private $em;
    private $categoryRepository;
    public function __construct(EntityManagerInterface $em, CategoryRepository $categoryRepository)
    {
        $this->em = $em;
        $this->categoryRepository = $categoryRepository;
    }
    #[Route('/manage/category', name: 'app_category')]
    public function index(): Response
    {
        $category = $this->categoryRepository->findAll();
        $data = [
            'category' => $category,
        ];
        return $this->render('category/list.html.twig', $data);
    }

    #[Route('/manage/category-create', name: 'create_category')]
    public function create(Request $request, SessionInterface $session): Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryFormType::class, $category);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $newCate = $form->getData();
            $this->em->persist($newCate);
            $this->em->flush();

            $this->addFlash('success', 'Added successfully.');
            return $this->redirectToRoute('create_category');
        }
        $data = [
            'category' => $category,
            'form' => $form->createView(),
        ];
        return $this->render('category/form.html.twig', $data);
    }

    #[Route('/manage/category-remove-{id}', name: 'destroy_category')]
    public function destroy($id): Response
    {
        $category = $this->categoryRepository->find($id);
        $this->em->remove($category);
        $this->em->flush();
        $this->addFlash('success', 'Deleted successfully.');
        return $this->redirectToRoute('app_category');
    }

    #[Route('/manage/category-edit-{id}', name: 'update_category')]
    public function Update(Request $request, $id): Response
    {
        $category = $this->categoryRepository->find($id);
        $form = $this->createForm(CategoryFormType::class, $category);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $newCate = $form->getData();
            $this->em->persist($newCate);
            $this->em->flush();

            $this->addFlash('success', 'Update successfully.');
            return $this->redirectToRoute('update_category', ['id' => $id]);
        }
        $data = [
            'category' => $category,
            'form' => $form->createView(),
        ];
        return $this->render('category/form.html.twig', $data);
    }
}