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

    #[Route('/get_all_products', name: 'get_all_products', methods:['GET'])]
    public function get_all_products($id): JsonResponse
    {
        $seller = $this->sellerRepository->findOneBy(["id" => $id]);
        if (is_null($seller)){
            return new JsonResponse(
                ['status' => "The seller doesn't exists!"], 
                Response::HTTP_NOT_FOUND
            );
        }
        $products = $seller->getProducts();
        $data = [];

        foreach ($products as $product)
        {
            $data[] = [
                'id' => $product->getId(),
                'name' => $product->getName(),
                'width' => $product->getWidth(),
                'price' => $product->getPrice()
            ];
        }

        return new JsonResponse($data, Response::HTTP_OK);
    }

    #[Route('/new_product', name: 'new_product', methods:['POST'])]
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

    #[Route('/update_product/{product_id}', name: 'update_product', methods:['PUT'])]
    public function update_product(Request $request, $id, $product_id): JsonResponse
    {
        // Get the product.
        $product = $this->productRepository->findOneBy(["id" => $product_id]);

        if (is_null($product)){
            return new JsonResponse(
                ['status' => "The product doesn't exists!"], 
                Response::HTTP_NOT_FOUND
            );
        }

        // Check if is the correct seller.
        if ($id != $product->getSeller()->getId()) 
        {
            return new JsonResponse(
                ['status' => "The seller doesn't have permissions!"], 
                Response::HTTP_UNAUTHORIZED
            );
        }

        // Get the request data.
        $data = json_decode($request->getContent(), true);
        empty($data['name']) ? true : $product->setName($data['name']);
        empty($data['width']) ? true : $product->setWidth($data['width']);
        empty($data['price']) ? true : $product->setPrice($data['price']);

        $this->productRepository->updateProduct($product);

        return new JsonResponse(['status' => 'Product updated!'], Response::HTTP_OK);
    }

    #[Route('/remove_product/{product_id}', name: 'remove_product', methods:['DELETE'])]
    public function remove_product($id, $product_id): JsonResponse
    {
        // Get the product.
        $product = $this->productRepository->findOneBy(["id" => $product_id]);
        
        if (is_null($product)){
            return new JsonResponse(
                ['status' => "The product doesn't exists!"], 
                Response::HTTP_NOT_FOUND
            );
        }

        // Check if is the correct seller.
        if ($id != $product->getSeller()->getId()) 
        {
            return new JsonResponse(
                ['status' => "The seller doesn't have permissions!"], 
                Response::HTTP_UNAUTHORIZED
            );
        }

        $this->productRepository->removeProduct($product);

        return new JsonResponse(['status' => 'Product deleted!'], Response::HTTP_OK);
    }
}
