<?php

namespace App\Controller;

use App\Entity\Brand;
use App\Form\BrandFormType;
use App\Repository\BrandRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BrandController extends AbstractController
{
    private $em;
    private $brandRepository;
    public function __construct(EntityManagerInterface $em, BrandRepository $brandRepository)
    {
        $this->em = $em;
        $this->brandRepository = $brandRepository;
    }
    #[Route('/manage/brand', name: 'app_brand')]
    public function index(): Response
    {
        $brand = $this->brandRepository->findAll();
        $data = [
            'brand' => $brand,
        ];
        return $this->render('brand/list.html.twig', $data);
    }
    //
    #[Route('/manage/brand-create', name: 'create_brand')]
    public function create(Request $request): Response
    {
        $brand = new Brand();
        $form = $this->createForm(BrandFormType::class, $brand);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $newBrand = $form->getData();
            $this->em->persist($newBrand);
            $this->em->flush();

            $this->addFlash('success', 'Added successfully.');
            return $this->redirectToRoute('create_category');
        }
        $data = [
            'title' => 'ADD NEW BRAND',
            'category' => $brand,
            'form' => $form->createView(),
        ];
        return $this->render('brand/form.html.twig', $data);
    }

    #[Route('/manage/brand-remove-{id}', name: 'destroy_brand')]
    public function destroy($id): Response
    {
        $brand = $this->brandRepository->find($id);
        $this->em->remove($brand);
        $this->em->flush();
        $this->addFlash('success', 'Deleted successfully.');
        return $this->redirectToRoute('app_brand');
    }

    #[Route('/manage/brand-edit-{id}', name: 'update_brand')]
    public function Update(Request $request, $id): Response
    {
        $brand = $this->brandRepository->find($id);
        $form = $this->createForm(BrandFormType::class, $brand);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $newBrand = $form->getData();
            $this->em->persist($newBrand);
            $this->em->flush();

            $this->addFlash('success', 'Update successfully.');
            return $this->redirectToRoute('update_brand', ['id' => $id]);
        }
        $data = [
            'title' => 'EIDT BRAND',
            'category' => $brand,
            'form' => $form->createView(),
        ];
        return $this->render('brand/form.html.twig', $data);
    }
}