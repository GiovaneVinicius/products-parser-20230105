<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Product;
use App\Models\ApiToken;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ProductApiTest extends TestCase
{
    use DatabaseTransactions;

    protected $apiToken;

    public function setUp(): void
    {
        parent::setUp();

        // Execute o teste para gerar o token e armazene-o na propriedade $apiToken
        $this->test_generate_token();
    }

    public function test_generate_token()
    {
        $response = $this->postJson('/api/token');

        $response->assertStatus(200)
                 ->assertJsonStructure(['access_token']);

        // Armazene o token gerado para uso posterior
        $this->apiToken = $response['access_token'];
        
    }

    public function test_get_api_details()
    {
        // Verifique se o token está definido
        $this->assertNotNull($this->apiToken, 'Token de API não definido');

        $response = $this->withHeaders(['API-Key' => $this->apiToken])
                         ->getJson('/api/');
        
        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'status_da_conexao',
                     'ultima_execucao_do_cron',
                     'uso_de_memoria'
                 ]);
    }

    public function test_update_product()
    {
        // Crie um produto para teste
        $product = Product::factory()->create();

        $data = [
            // adicione aqui os dados que você deseja atualizar no produto
        ];

        $response = $this->withHeaders(['API-Key' => $this->apiToken])
                         ->putJson("/api/products/{$product->code}", $data);

        $response->assertStatus(200);
    }

    public function test_delete_product()
    {
        // Crie um produto para teste
        $product = Product::factory()->create();

        $response = $this->withHeaders(['API-Key' => $this->apiToken])
                         ->deleteJson("/api/products/{$product->code}");

        $response->assertStatus(200);
    }

    public function test_get_product_by_code()
    {
        // Crie um produto para teste
        $product = Product::factory()->create();

        $response = $this->withHeaders(['API-Key' => $this->apiToken])
                         ->getJson("/api/products/{$product->code}");

        $response->assertStatus(200)
                 ->assertJsonFragment(['code' => $product->code]);
    }

    public function test_get_all_products()
    {
        $response = $this->withHeaders(['API-Key' => $this->apiToken])
                         ->getJson("/api/products");
    
        $response->assertStatus(200)
                 ->assertJson([]);
    }
    
}
