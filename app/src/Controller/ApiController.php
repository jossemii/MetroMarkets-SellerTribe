<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

use App\Repository\SellerRepository;

#[Route('/api', name:'api')]
class ApiController extends AbstractController
{
    private $sellerRepository;

    public function __construct(SellerRepository $sellerRepository)
    {
        $this->sellerRepository = $sellerRepository;
    }

    #[Route('/new_seller', name:'new_seller', methods:['POST'])]
    public function new_seller(Request $request): JsonResponse
    {
        return new JsonResponse(
            ["id" => $this->sellerRepository->saveSeller(
                json_decode($request->getContent(), true)
            )],
            Response::HTTP_CREATED
        );
    }

    #[Route('/update_seller/{seller_id}', name: 'seller_id', methods:['PUT'])]
    public function update_seller(Request $request, $seller_id): JsonResponse
    {
        // Get the seller.
        $seller = $this->sellerRepository->findOneBy(["id" => $seller_id]);

        if (is_null($seller)){
            return new JsonResponse(
                ['status' => "The seller doesn't exists!"], 
                Response::HTTP_NOT_FOUND
            );
        }

        // Get the request data.
        $data = json_decode($request->getContent(), true);
        empty($data['name']) ? true : $seller->setName($data['name']);
        empty($data['width']) ? true : $seller->setCountry($data['country']);
        empty($data['price']) ? true : $seller->setPostalCode($data['postal_code']);

        $this->sellerRepository->updateSeller($seller);

        return new JsonResponse(['status' => 'Seller updated!'], Response::HTTP_OK);
    }

    #[Route('/remove_seller/{seller_id}', name: 'remove_seller', methods:['DELETE'])]
    public function remove_seller($seller_id): JsonResponse
    {
        // Get the seller.
        $seller = $this->sellerRepository->findOneBy(["id" => $seller_id]);
        
        if (is_null($seller)){
            return new JsonResponse(
                ['status' => "The seller doesn't exists!"], 
                Response::HTTP_NOT_FOUND
            );
        }

        $this->sellerRepository->removeSeller($seller);

        return new JsonResponse(['status' => 'Seller deleted!'], Response::HTTP_OK);
    }
}