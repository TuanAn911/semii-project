<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;

class HomeController extends AbstractController
{
    private $em;
    private $productRepository;
    public function __construct(EntityManagerInterface $em, ProductRepository $productRepository)
    {
        $this->em = $em;
        $this->productRepository = $productRepository;
    }
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        $product = $this->productRepository->findAll();
        $data = [
            'product' => $product,
        ];
        return $this->render('home/index.html.twig', $data);
    }
}