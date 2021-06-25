<?php

namespace App\Tests;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;

class SellerProductsTest extends ApiTestCase
{
    
    // It use the seller_tribe_test database.
    // This trait provided by HautelookAliceBundle will take care of refreshing the database content to a known state before each test
    use RefreshDatabaseTrait;

    public function testGetAllProducts(): void
    {
        
        $client = static::createClient();
        $client->request('POST', '/api/new_seller', ['json' => [
            'name' => 'Estonio',
            'country' => 'Rusia',
        ]]);

        $this->assertResponseStatusCodeSame(201);
        $this->assertResponseIsSuccessful();
    }
}
