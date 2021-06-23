<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use App\Entity\Product;


#[Route('/api/seller/{id}', name:'seller_products')]
class SellerProductsController extends AbstractController
{
    #[Route('/get_all_products', name: 'get_all_products')]
    public function get_all_products(): Response
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/SellerProductsController.php',
        ]);
    }

    #[Route('/get_info', name: 'get_info')]
    public function get_info(): Response
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/SellerProductsController.php',
        ]);
    }

    #[Route('/new_product', name: 'new_product')]
    public function new_product(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        # check if the request has a name.
        if (empty($data['name']) || empty($data['price'])){
            throw new NotFoundHttpException("Expecting mandatory parameters!");
        }

        //create new seller obj.
        $product = new Product();
        $product->setName($data["name"]);

        //instantiate the entity manager
        $em = $this->getDoctrine()->getManager();
        //save post to database
        $em->persist($product);
        $em->flush();

        //return response to client
        return new JsonResponse(
            ["id" => $product['id']], 
            Response::HTTP_CREATED
        );
    }
}
