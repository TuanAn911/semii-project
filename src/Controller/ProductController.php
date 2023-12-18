<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductFormType;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class ProductController extends AbstractController
{
    private $em;
    private $productRepository;
    public function __construct(EntityManagerInterface $em, ProductRepository $productRepository)
    {
        $this->em = $em;
        $this->productRepository = $productRepository;
    }
    #[Route('/manage/product', name: 'app_product')]
    public function index(): Response
    {
        $product = $this->productRepository->findAll();
        $data = [
            'product' => $product,
        ];
        return $this->render('product/list.html.twig', $data);
    }
    #[Route('/manage/product-create', name: 'create_product')]
    public function create(Request $request): Response
    {
        $product = new Product();
        $form = $this->createForm(ProductFormType::class, $product);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $newProduct = $form->getData();
            $imagePath = $form->get('image')->getData();
            if ($imagePath) {
                $newFileName = uniqid() . '.' . $imagePath->guessExtension();

                try {
                    //code...
                    $imagePath->move(
                        $this->getParameter('kernel.project_dir') . '/public/uploads',
                        $newFileName
                    );
                } catch (FileException $e) {
                    //throw $th;
                    return new Response($e->getMessage());
                }
                $newProduct->setImage('/uploads/' . $newFileName);
            }
            // dd($newProduct);
            // exit;
            $this->em->persist($newProduct);
            $this->em->flush();

            $this->addFlash('success', 'Added successfully.');
            return $this->redirectToRoute('create_product');
        }
        $data = [
            'product' => $product,
            'form' => $form->createView(),
            'title' => 'ADD NEW PRODUCT',
        ];
        return $this->render('product/form.html.twig', $data);
    }
    #[Route('/manage/edit-product-{id}', name: 'update_product')]
    public function update(Request $request, $id): Response
    {
        $product = $this->productRepository->find($id);
        $form = $this->createForm(ProductFormType::class, $product);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // $newProduct = $form->getData();
            $imagePath = $form->get('image')->getData();
            if ($imagePath) {
                $img = $product->getImage();
                if ($img !== null) {
                    // $file = file_exists($this->getParameter('kernel.project_dir') . $actor->getImage());
                    $file = $this->getParameter('kernel.project_dir') . '/public' . $product->getImage();
                    if ($file) {
                        $this->GetParameter('kernel.project_dir') . $product->getImage();
                        $newFileName = uniqid() . '.' . $imagePath->guessExtension();

                        try {
                            //code...
                            $imagePath->move(
                                $this->getParameter('kernel.project_dir') . '/public/uploads',
                                $newFileName
                            );
                        } catch (FileException $e) {
                            //throw $th;
                            return new Response($e->getMessage());
                        }

                        $product->setImage('/uploads/' . $newFileName);
                        $this->em->flush();

                        $this->addFlash('success', 'Update successfully.');
                        return $this->redirectToRoute('update_product', ['id' => $id]);
                    }
                }
            } else {
                $product->setName($form->get('name')->getData());
                $product->setPrice($form->get('price')->getData());
                $product->setQty($form->get('qty')->getData());
                $product->setDescription($form->get('description')->getData());
                $product->setCategory($form->get('category')->getData());
                $product->setBrand($form->get('brand')->getData());

                $this->em->flush();
                $this->addFlash('success', 'Update successfully.');
                return $this->redirectToRoute('update_product', ['id' => $id]);
            }
        }
        $data = [
            'title' => 'EDIT PRODUCT',
            'product' => $product,
            'form' => $form->createView(),
        ];
        return $this->render('product/form.html.twig', $data);
    }
    //
    #[Route('/manage/product-remove-{id}', name: 'destroy_product')]
    public function destroy($id): Response
    {
        $product = $this->productRepository->find($id);
        $this->em->remove($product);
        $this->em->flush();
        $this->addFlash('success', 'Deleted successfully.');
        return $this->redirectToRoute('app_product');
    }
}