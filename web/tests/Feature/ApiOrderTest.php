<?php

namespace Tests\Feature;

use App\Services\ExchangeRateService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ApiOrderTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->exchangeRateService = new ExchangeRateService();
    }


    public function test_normal_case(): void
    {
        $response = $this->postJson(route('api.orders'), [
            "id"       => "A0000001",
            "name"     => "Melody Holiday Inn",
            "address"  => [
                "city"     => "taipei-city",
                "district" => "da-an-district",
                "street"   => "fuxing-south-road"
            ],
            "price"    => "2000",
            "currency" => "TWD"
        ]);

        $response->assertStatus(200);
    }


    public function test_non_english_characters(): void
    {
        $response = $this->postJson(route('api.orders'), [
            "id"       => "A0000001",
            "name"     => "Melody Holiday Inn美樂地",
            "address"  => [
                "city"     => "taipei-city",
                "district" => "da-an-district",
                "street"   => "fuxing-south-road"
            ],
            "price"    => "2000",
            "currency" => "TWD"
        ]);

        $response->assertStatus(400)
            ->assertJson([
                "success" => false,
                "errors"  => [
                    "name" => [
                        "Name contains non-English characters"
                    ]
                ]
            ]);
    }


    public function test_non_capitalized(): void
    {
        $response = $this->postJson(route('api.orders'), [
            "id"       => "A0000001",
            "name"     => "melody holiday inn",
            "address"  => [
                "city"     => "taipei-city",
                "district" => "da-an-district",
                "street"   => "fuxing-south-road"
            ],
            "price"    => "2000",
            "currency" => "TWD"
        ]);

        $response->assertStatus(400)
            ->assertJson([
                "success" => false,
                "errors"  => [
                    "name" => [
                        "Name is not capitalized"
                    ]
                ]
            ]);
    }


    public function test_price_is_over(): void
    {
        // price is over
        $response = $this->postJson(route('api.orders'), [
            "id"       => "A0000001",
            "name"     => "Melody Holiday Inn",
            "address"  => [
                "city"     => "taipei-city",
                "district" => "da-an-district",
                "street"   => "fuxing-south-road"
            ],
            "price"    => "200000",
            "currency" => "TWD"
        ]);

        $response->assertStatus(400)
            ->assertJson([
                "success" => false,
                "errors"  => [
                    "price" => [
                        "Price is over " . config('order.max_price')
                    ]
                ]
            ]);
    }


    public function test_currency_value(): void
    {
        $response = $this->postJson(route('api.orders'), [
            "id"       => "A0000001",
            "name"     => "Melody Holiday Inn",
            "address"  => [
                "city"     => "taipei-city",
                "district" => "da-an-district",
                "street"   => "fuxing-south-road"
            ],
            "price"    => "2000",
            "currency" => "JPY"
        ]);

        $response->assertStatus(400)
            ->assertJson([
                "success" => false,
                "errors"  => [
                    "currency" => [
                        "Currency format is wrong"
                    ]
                ]
            ]);
    }


    public function test_from_usd_to_twd(): void
    {
        $response = $this->postJson(route('api.orders'), [
            "id"       => "A0000001",
            "name"     => "Melody Holiday Inn",
            "address"  => [
                "city"     => "taipei-city",
                "district" => "da-an-district",
                "street"   => "fuxing-south-road"
            ],
            "price"    => "2000",
            "currency" => "USD"
        ]);

        $response->assertStatus(200)
            ->assertJson([
                "id"       => "A0000001",
                "name"     => "Melody Holiday Inn",
                "address"  => [
                    "city"     => "taipei-city",
                    "district" => "da-an-district",
                    "street"   => "fuxing-south-road"
                ],
                "price"    => 2000 * $this->exchangeRateService->getRate("USD"),
                "currency" => "TWD"
            ]);
    }
}
