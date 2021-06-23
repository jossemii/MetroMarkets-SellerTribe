<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;


use App\Repository\SellerRepository;

class ApiController extends AbstractController
{
    private $sellerRepository;

    public function __construct(SellerRepository $sellerRepository)
    {
        $this->$sellerRepository = $sellerRepository;
    }

    #[Route('/api/new_seller', name:'new_seller')]
    public function new_seller(Request $request): JsonResponse
    {
        return new JsonResponse(
            ["id" => $this->sellerRepository->saveSeller(
                json_decode($request->getContent(), true)
            )],
            Response::HTTP_CREATED
        );
    }
}
?>