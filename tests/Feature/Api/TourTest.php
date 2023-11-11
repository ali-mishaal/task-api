<?php

namespace Tests\Feature\api;

use App\Models\Tour;
use App\Models\Travel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TourTest extends TestCase
{
    use RefreshDatabase;

    public function test_tours_paginate(): void
    {
        $travel = Travel::factory()->create();

        $tours = Tour::factory(16)->create(['travel_id' => $travel->id]);

        $response = $this->get('/api/v1/travels/'.$travel->slug.'/tours');

        $response->assertStatus(200);
        $response->assertJsonCount(15, 'data');
        $response->assertJsonPath('meta.last_page', 2);
    }

    public function test_tours_return_by_travel_tour_success(): void
    {
        $travel = Travel::factory()->create();

        $tour = Tour::factory()->create(['travel_id' => $travel->id]);

        $response = $this->get('/api/v1/travels/'.$travel->slug.'/tours');

        $response->assertStatus(200);
        $response->assertJsonCount(1, 'data');
        $response->assertJsonFragment(['id' => $tour->id]);
    }

    public function test_tours_price_show_correctly(): void
    {
        $travel = Travel::factory()->create();

        $tour = Tour::factory()->create([
            'travel_id' => $travel->id,
            'price' => 20.25,
        ]);

        $response = $this->get('/api/v1/travels/'.$travel->slug.'/tours');

        $response->assertStatus(200);
        $response->assertJsonCount(1, 'data');
        $response->assertJsonFragment(['price' => '20.25']);
    }

    public function test_tours_filter_by_date_From_correctly(): void
    {
        $travel = Travel::factory()->create();

        $tour = Tour::factory()->create([
            'travel_id' => $travel->id,
            'start_date' => now(),
        ]);

        $tourLater = Tour::factory()->create([
            'travel_id' => $travel->id,
            'start_date' => now()->addDay(5),
        ]);

        $response = $this->get('/api/v1/travels/'.$travel->slug.'/tours?dateFrom='.now()->addDay(2)->format('Y-m-d'));

        $response->assertStatus(200);
        $response->assertJsonCount(1, 'data');
        $response->assertJsonPath('data.0.id', $tourLater->id);
    }

    public function test_tours_filter_by_date_to_correctly(): void
    {
        $travel = Travel::factory()->create();

        $tour = Tour::factory()->create([
            'travel_id' => $travel->id,
            'start_date' => now(),
        ]);

        $tourLater = Tour::factory()->create([
            'travel_id' => $travel->id,
            'start_date' => now()->addDay(5),
        ]);

        $response = $this->get('/api/v1/travels/'.$travel->slug.'/tours?dateTo='.now()->addDay(1)->format('Y-m-d'));

        $response->assertStatus(200);
        $response->assertJsonCount(1, 'data');
        $response->assertJsonPath('data.0.id', $tour->id);
    }

    public function test_tours_filter_by_price_from_correctly(): void
    {
        $travel = Travel::factory()->create();

        $tour = Tour::factory()->create([
            'travel_id' => $travel->id,
            'price' => 200,
        ]);

        $tourExpensive = Tour::factory()->create([
            'travel_id' => $travel->id,
            'price' => 300,
        ]);

        $tourHighExpensive = Tour::factory()->create([
            'travel_id' => $travel->id,
            'price' => 400,
        ]);

        $response = $this->get('/api/v1/travels/'.$travel->slug.'/tours?priceFrom=30500');

        $response->assertStatus(200);
        $response->assertJsonCount(1, 'data');
        $response->assertJsonPath('data.0.id', $tourHighExpensive->id);

        $response = $this->get('/api/v1/travels/'.$travel->slug.'/tours?priceFrom=20500');

        $response->assertStatus(200);
        $response->assertJsonCount(2, 'data');
        $response->assertJsonPath('data.0.id', $tourExpensive->id);
    }

    public function test_tours_filter_by_price_to_correctly(): void
    {
        $travel = Travel::factory()->create();

        $tour = Tour::factory()->create([
            'travel_id' => $travel->id,
            'price' => 200,
        ]);

        $tourExpensive = Tour::factory()->create([
            'travel_id' => $travel->id,
            'price' => 300,
        ]);

        $tourHighExpensive = Tour::factory()->create([
            'travel_id' => $travel->id,
            'price' => 400,
        ]);

        $response = $this->get('/api/v1/travels/'.$travel->slug.'/tours?priceTo=30500');

        $response->assertStatus(200);
        $response->assertJsonCount(2, 'data');
        $response->assertJsonPath('data.0.id', $tour->id);

        $response = $this->get('/api/v1/travels/'.$travel->slug.'/tours?priceTo=20500');

        $response->assertStatus(200);
        $response->assertJsonCount(1, 'data');
        $response->assertJsonPath('data.0.id', $tour->id);
    }

    public function test_tours_sorted_by_price_and_sorted_type_asc_correctly(): void
    {
        $travel = Travel::factory()->create();

        $tour = Tour::factory()->create([
            'travel_id' => $travel->id,
            'price' => 200,
        ]);

        $tourExpensive = Tour::factory()->create([
            'travel_id' => $travel->id,
            'price' => 300,
        ]);

        $tourHighExpensive = Tour::factory()->create([
            'travel_id' => $travel->id,
            'price' => 400,
        ]);

        $response = $this->get('/api/v1/travels/'.$travel->slug.'/tours?sortBy=price&sortOrder=asc');

        $response->assertStatus(200);
        $response->assertJsonCount(3, 'data');
        $response->assertJsonPath('data.0.id', $tour->id);
    }

    public function test_tours_sorted_by_price_and_sorted_type_desc_correctly(): void
    {
        $travel = Travel::factory()->create();

        $tour = Tour::factory()->create([
            'travel_id' => $travel->id,
            'price' => 200,
        ]);

        $tourExpensive = Tour::factory()->create([
            'travel_id' => $travel->id,
            'price' => 300,
        ]);

        $tourHighExpensive = Tour::factory()->create([
            'travel_id' => $travel->id,
            'price' => 400,
        ]);

        $response = $this->get('/api/v1/travels/'.$travel->slug.'/tours?sortBy=price&sortOrder=desc');

        $response->assertStatus(200);
        $response->assertJsonCount(3, 'data');
        $response->assertJsonPath('data.0.id', $tourHighExpensive->id);
    }
}
