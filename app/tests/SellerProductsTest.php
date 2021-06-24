<?php

namespace App\Tests;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;

class SellerProductsTest extends ApiTestCase
{

    // This trait provided by HautelookAliceBundle will take care of refreshing the database content to a known state before each test
    use RefreshDatabaseTrait;

    public function testGetAllProducts(): void
    {
        $client = static::createClient();
        $seller_id = $this->findIriBy(Seller::class, ['name' => 'Juan']);   

        $client->request('GET', $seller_id);

        $this->assertResponseStatusCodeSame(200);
        $this->assertResponseIsSuccessful();
        // Asserts that the returned content type is JSON-LD (the default)
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

        // Asserts that the returned JSON is a superset of this one
        //$this->assertJsonContains([]);

        //$this->assertMatchesResourceItemJsonSchema(Product::class);
    }
}
