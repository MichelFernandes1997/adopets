<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\User;
use App\Product;

class ProductsTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_it_shouldnt_create_product_with_wrong_params()
    {
        $password = 'its-a-test-Rick';

        $user = factory(User::class)->create([
            'password' => bcrypt($password),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => $password,
        ]);

        $response->assertStatus(201);

        $token = json_decode($response->getContent());

        $data = [
            'name' => 120,
            'description' => 'Soquete AMD AM4: Pronto para os processadores AMD Ryzen™ de 2a e 3a geração.',
            'category' => [0 =>'MotherBoard'],
            'price' => '1.78812',
            'stock' => 292
        ];

        $response = $this->postJson(route('products.store'), $data, ['Authorization' => 'Bearer ' . $token->access_token]);

        $response->assertStatus(422);

        $response->assertJson([
            'message' => 'The given data was invalid.'
        ]);
    }

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_it_should_create_product()
    {
        $password = 'its-a-test-Rick';

        $user = factory(User::class)->create([
            'password' => bcrypt($password),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => $password,
        ]);

        $response->assertStatus(201);

        $token = json_decode($response->getContent());

        $data = [
            'name' => 'Placa-Mãe Asus TUF',
            'description' => 'Soquete AMD AM4: Pronto para os processadores AMD Ryzen™ de 2a e 3a geração ',
            'category' => 'MotherBoard',
            'price' => 1.78812 ,
            'stock' => 292
        ];

        $response = $this->postJson(route('products.store'), $data, ['Authorization' => 'Bearer ' . $token->access_token]);

        $response->assertStatus(201);

        $response->assertJsonStructure([
            'product' => [
                'name',
                'description',
                'category',
                'price',
                'stock',
                'created_at',
                'updated_at'
            ]
        ]);
    }

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_it_should_list_products()
    {
        $password = 'its-a-test-Rick';

        factory(Product::class, 10)->create();

        $user = factory(User::class)->create([
            'password' => bcrypt($password),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => $password,
        ]);

        $response->assertStatus(201);

        $token = json_decode($response->getContent());

        $response = $this->get(route('products.index'), ['Authorization' => 'Bearer ' . $token->access_token]);

        $response->assertStatus(201);

        $response->AssertJsonStructure([
            'products' => [
                [
                    'id',
                    'name',
                    'description',
                    'category',
                    'price',
                    'stock',
                    'created_at',
                    'updated_at'
                ]
            ]
        ]);
    }

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_it_shouldnt_search_products_without_filters()
    {
        $password = 'its-a-test-Rick';

        factory(Product::class, 100)->create();

        $user = factory(User::class)->create([
            'password' => bcrypt($password),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => $password,
        ]);

        $response->assertStatus(201);

        $token = json_decode($response->getContent());

        $params = [
            'Authorization' => 'Bearer ' . $token->access_token,
        ];

        $response = $this->get(route('products.search'), $params);

        $response->assertStatus(404);

        $response->AssertExactJson([
            'message' => 'Header query with search params not found'
        ]);
    }

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_it_should_search_products_with_filters()
    {
        $password = 'its-a-test-Rick';

        factory(Product::class, 100)->create();

        $user = factory(User::class)->create([
            'password' => bcrypt($password),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => $password,
        ]);

        $response->assertStatus(201);

        $token = json_decode($response->getContent());

        $search = json_encode(['name' => 'te', 'description' => 'pla', 'category' => 'Ms.']);

        $params = [
            'Authorization' => 'Bearer ' . $token->access_token,
            'query' => [$search]
        ];

        $response = $this->get(route('products.search'), $params);

        $response->assertStatus(201);

        $response->AssertJsonStructure([
            'filtered_products' => [
                [
                    'id',
                    'name',
                    'description',
                    'category',
                    'price',
                    'stock',
                    'created_at',
                    'updated_at'
                ]
            ]
        ]);
    }

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_it_shouldnt_update_product_not_found_with_wrong_id()
    {
        $password = 'its-a-test-Rick';

        $productUpdate = factory(Product::class, 100)->create();

        $user = factory(User::class)->create([
            'password' => bcrypt($password),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => $password,
        ]);

        $response->assertStatus(201);

        $token = json_decode($response->getContent());

        $id = "Morty this isn't id";

        $response = $this->putJson(route('products.update', $id), ['name' => 'Rx 5700 shaphire'], ['Authorization' => 'Bearer ' . $token->access_token]);

        $response->assertStatus(500);

        $response->assertExactJson([
            'error' => 'Product could not be found with the passed ID',
            'stackTrace' => "No query results for model [App\\Product] Morty this isn't id"
        ]);
    }

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_it_shouldnt_update_product_wrong_params()
    {
        $password = 'its-a-test-Rick';

        $productUpdate = factory(Product::class, 100)->create();

        $user = factory(User::class)->create([
            'password' => bcrypt($password),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => $password,
        ]);

        $response->assertStatus(201);

        $token = json_decode($response->getContent());

        $id = $productUpdate[77]->id;

        $response = $this->putJson(route('products.update', $id), [
            'name' => 120,
            'description' => 1.20000,
            'category' => [1,2,3],
            'price' => 'Its a error',
            'stock' => 'string'
        ], ['Authorization' => 'Bearer ' . $token->access_token]);

        $response->assertStatus(422);

        $response->assertJson([
            'message' => 'The given data was invalid.'
        ]);
    }

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_it_should_update_product()
    {
        $password = 'its-a-test-Rick';

        $productUpdate = factory(Product::class, 100)->create();

        $user = factory(User::class)->create([
            'password' => bcrypt($password),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => $password,
        ]);

        $response->assertStatus(201);

        $token = json_decode($response->getContent());

        $id = $productUpdate[7]->id;

        $response = $this->put(route('products.update', $id), ['name' => 'Rx 5700 shaphire'], ['Authorization' => 'Bearer ' . $token->access_token]);

        $response->assertStatus(201);

        $response->AssertJsonStructure([
            'product' => [
                'id',
                'name',
                'description',
                'category',
                'price',
                'stock',
                'created_at',
                'updated_at',
                'deleted_at'
            ]
        ]);
    }

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_it_shouldnt_delete_product_with_wrong_id()
    {
        $password = 'its-a-test-Rick';

        $productUpdate = factory(Product::class, 100)->create();

        $user = factory(User::class)->create([
            'password' => bcrypt($password),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => $password,
        ]);

        $response->assertStatus(201);

        $token = json_decode($response->getContent());

        $id = 'The ID are wrong again bro? Oh my god.';

        $response = $this->delete(route('products.destroy', $id), [], ['Authorization' => 'Bearer ' . $token->access_token]);

        $response->assertStatus(500);

        $response->assertJson([
            'error' => 'Product could not be found with the passed ID',
            'stackTrace' => 'No query results for model [App\Product] The ID are wrong again bro'
        ]);
    }

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_it_should_delete_product()
    {
        $password = 'its-a-test-Rick';

        $productUpdate = factory(Product::class, 100)->create();

        $user = factory(User::class)->create([
            'password' => bcrypt($password),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => $password,
        ]);

        $response->assertStatus(201);

        $token = json_decode($response->getContent());

        $id = $productUpdate[71]->id;

        $response = $this->delete(route('products.destroy', $id), [], ['Authorization' => 'Bearer ' . $token->access_token]);

        $response->assertStatus(201);

        $response->assertExactJson(['deleted' => true]);
    }
}
