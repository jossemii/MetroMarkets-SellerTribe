<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

use App\Repository\ProductRepository;
use App\Repository\SellerRepository;

#[Route('/api/seller/{id}', name:'seller_products')]
class SellerProductsController extends AbstractController
{

    private $productRepository;
    private $sellerRepository;

    public function __construct(ProductRepository $productRepository, SellerRepository $sellerRepository)
    {
        $this->productRepository = $productRepository;
        $this->sellerRepository = $sellerRepository;
    }

    #[Route('/get_all_products', name: 'get_all_products')]
    public function get_all_products($id): Response
    {
        $seller = $this->sellerRepository->findOneBy(["id" => $id]);
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/SellerProductsController.php',
        ]);
    }

    #[Route('/get_info', name: 'get_info')]
    public function get_info($id): Response
    {
        $seller = $this->sellerRepository->findOneBy(["id" => $id]);
        return $this->json([
            'name' => $seller->getName(),
        ]);
    }

    #[Route('/new_product', name: 'new_product')]
    public function new_product(Request $request, $id): JsonResponse
    {
        $seller = $this->sellerRepository->findOneBy(["id" => $id]);
        return new JsonResponse(
            ["id" => $this->productRepository->saveProduct(
                json_decode($request->getContent(), true) + ['seller' => $seller]
            )],
            Response::HTTP_CREATED
        );
    }
}
