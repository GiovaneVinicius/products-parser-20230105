<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ApiToken;
use App\Models\APIStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use OpenApi\Annotations as OA;
use Illuminate\Support\Str;

/**
 * @OA\Info(
 *      version="1.0.0",
 *      title="API de Produtos",
 *      description="Documentação da API de Produtos",
 *      @OA\Contact(
 *          email="gio_vinicius@hotmail.com",
 *          name="Giovane Vinicius"
 *      ),
 *      @OA\License(
 *          name="MIT",
 *          url="https://opensource.org/licenses/MIT"
 *      )
 * )
 * 
 * @OA\Schema(
 *     schema="Product",
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         format="int64"
 *     ),
 *     @OA\Property(
 *         property="code",
 *         type="integer",
 *         format="int64"
 *     ),
 *     @OA\Property(
 *         property="status",
 *         type="string"
 *     ),
 *     @OA\Property(
 *         property="imported_t",
 *         type="string",
 *         format="date-time"
 *     ),
 *     @OA\Property(
 *         property="url",
 *         type="string"
 *     ),
 *     @OA\Property(
 *         property="creator",
 *         type="string"
 *     ),
 *     @OA\Property(
 *         property="created_t",
 *         type="string",
 *         format="date-time"
 *     ),
 *     @OA\Property(
 *         property="last_modified_t",
 *         type="string",
 *         format="date-time"
 *     ),
 *     @OA\Property(
 *         property="product_name",
 *         type="string"
 *     ),
 *     @OA\Property(
 *         property="quantity",
 *         type="string"
 *     ),
 *     @OA\Property(
 *         property="brands",
 *         type="string"
 *     ),
 *     @OA\Property(
 *         property="categories",
 *         type="string"
 *     ),
 *     @OA\Property(
 *         property="labels",
 *         type="string"
 *     ),
 *     @OA\Property(
 *         property="cities",
 *         type="string"
 *     ),
 *     @OA\Property(
 *         property="purchase_places",
 *         type="string"
 *     ),
 *     @OA\Property(
 *         property="stores",
 *         type="string"
 *     ),
 *     @OA\Property(
 *         property="ingredients_text",
 *         type="string"
 *     ),
 *     @OA\Property(
 *         property="traces",
 *         type="string"
 *     ),
 *     @OA\Property(
 *         property="serving_size",
 *         type="string"
 *     ),
 *     @OA\Property(
 *         property="serving_quantity",
 *         type="integer",
 *         format="int64"
 *     ),
 *     @OA\Property(
 *         property="nutriscore_score",
 *         type="integer",
 *         format="int64"
 *     ),
 *     @OA\Property(
 *         property="nutriscore_grade",
 *         type="string"
 *     ),
 *     @OA\Property(
 *         property="main_category",
 *         type="string"
 *     ),
 *     @OA\Property(
 *         property="image_url",
 *         type="string"
 *     ),
 * )
 * 
 * @OA\SecurityScheme(
 *   securityScheme="APIKeyAuth",
 *   in="header",
 *   name="API-Key",
 *   type="apiKey",
 *   description="Autenticação por chave de API"
 * )
 */

class ProductController extends Controller
{
    /**
     * @OA\Post(
     *      path="/api/token",
     *      operationId="generateToken",
     *      tags={"Token"},
     *      summary="Gerar uma chave de API",
     *      description="Gera uma nova chave de API para o usuário consultar os dados com segurança.",
     *      @OA\Response(
     *          response=200,
     *          description="Chave de API gerada com sucesso",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(
     *                  property="api_key",
     *                  type="string",
     *                  description="Chave de API gerada"
     *              )
     *          )
     *      )
     * )
     */
    public function generateToken(Request $request)
    {
        // Gera um token aleatório
        $token = Str::random(60);
    
        // Salva o token no banco de dados
        ApiToken::create([
            'api_key' => $token,
        ]);
        return response()->json(['access_token' => $token]);
    }
    /**
     * @OA\Get(
     *      path="/api/",
     *      operationId="apiDetails",
     *      tags={"API"},
     *      summary="Obter detalhes da API",
     *      description="Retorna detalhes da API, incluindo status de conexão, última execução do cron, tempo de atividade e uso de memória.",
     *      security={{"APIKeyAuth": {}}},
     *      @OA\Response(
     *          response=200,
     *          description="Operação bem-sucedida"
     *      )
     * )
     */
    public function apiDetails(Request $request)
    {
        // Verifica se a chave de API é válida
        $apiKey = $request->header('API-Key');
        $apiToken = ApiToken::where('api_key', $apiKey)->first();
        if (!$apiToken) {
            return response()->json(['message' => 'Chave de API inválida'], 401);
        }
    
        // Lógica para obter os detalhes da API...
        $connectionStatus = DB::connection()->getPdo() ? 'Conexão OK' : 'Falha na conexão';
    
        // Busca os dados mais recentes da model APIStatus
        $apiStatus = APIStatus::latest()->first();
    
        // Verifica se existem dados na tabela APIStatus
        if ($apiStatus) {
            $lastCronExecution = $apiStatus->dateImport;
            $memoryUsage = $apiStatus->memoryConsumed;
            // Implemente a lógica para calcular o tempo de atividade ($uptime)
        } else {
            $lastCronExecution = 'N/A';
            $memoryUsage = 'N/A';
            // Implemente a lógica para calcular o tempo de atividade ($uptime) quando não houver registros
        }
    
        $details = [
            'status_da_conexao' => $connectionStatus,
            'ultima_execucao_do_cron' => $lastCronExecution,
            'uso_de_memoria' => $memoryUsage,
        ];
    
        return response()->json($details);
    }
    

    /**
     * @OA\Put(
     *      path="/api/products/{code}",
     *      operationId="updateProduct",
     *      tags={"Produtos"},
     *      summary="Atualizar produto",
     *      description="Atualiza um produto específico",
     *      security={{"APIKeyAuth": {}}},
     *      @OA\Parameter(
     *          name="code",
     *          in="path",
     *          description="Código do produto",
     *          required=true,
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/Product")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Produto atualizado com sucesso"
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Produto não encontrado"
     *      )
     * )
     */
    public function updateProduct(Request $request, $code)
    {
        // Verifica se a chave de API é válida
        $apiKey = $request->header('API-Key');
        $apiToken = ApiToken::where('api_key', $apiKey)->first();
        if (!$apiToken) {
            return response()->json(['message' => 'Chave de API inválida'], 401);
        }

        // Lógica para atualizar um produto específico...
        $product = Product::where('code', $code)->first();

        if (!$product) {
            return response()->json(['message' => 'Produto não encontrado'], 404);
        }
        // Atualize os atributos do produto com os dados fornecidos no corpo da solicitação
        $product->update($request->all());
        return response()->json(['message' => 'Produto atualizado com sucesso']);
    }

    /**
     * @OA\Delete(
     *      path="/api/products/{code}",
     *      operationId="deleteProduct",
     *      tags={"Produtos"},
     *      summary="Excluir produto",
     *      description="Exclui um produto específico",
     *      security={{"APIKeyAuth": {}}},
     *      @OA\Parameter(
     *          name="code",
     *          in="path",
     *          description="Código do produto",
     *          required=true,
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Produto excluído com sucesso"
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Produto não encontrado"
     *      )
     * )
     */
    public function deleteProduct(Request $request, $code)
    {
        // Verifica se a chave de API é válida
        $apiKey = $request->header('API-Key');
        $apiToken = ApiToken::where('api_key', $apiKey)->first();
        if (!$apiToken) {
            return response()->json(['message' => 'Chave de API inválida'], 401);
        }

        // Lógica para deletar um produto específico...
        $product = Product::where('code', $code)->first();

        if (!$product) {
            return response()->json(['message' => 'Produto não encontrado'], 404);
        }
        $product->delete();
        return response()->json(['message' => 'Produto excluído com sucesso']);
    }

    /**
     * @OA\Get(
     *      path="/api/products",
     *      operationId="getAllProducts",
     *      tags={"Produtos"},
     *      summary="Obter todos os produtos",
     *      description="Retorna todos os produtos",
     *      security={{"APIKeyAuth": {}}},
     *      @OA\Response(
     *          response=200,
     *          description="Operação bem-sucedida",
     *          @OA\JsonContent(
     *              type="array",
     *              @OA\Items(ref="#/components/schemas/Product")
     *          )
     *      )
     * )
     */


    public function getAllProducts(Request $request)
    {
        // Verifica se a chave de API é válida
        $apiKey = $request->header('API-Key');
        $apiToken = ApiToken::where('api_key', $apiKey)->first();
        if (!$apiToken) {
            return response()->json(['message' => 'Chave de API inválida'], 401);
        }

        // Lógica para obter todos os produtos...
        $products = Product::all();
        return response()->json($products);
    }

    /**
     * @OA\Get(
     *      path="/api/products/{code}",
     *      operationId="getProductByCode",
     *      tags={"Produtos"},
     *      summary="Obter informações do produto pelo código",
     *      description="Retorna informações do produto pelo seu código",
     *      security={{"APIKeyAuth": {}}},
     *      @OA\Parameter(
     *          name="code",
     *          in="path",
     *          required=true,
     *          description="Código do produto",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Operação bem-sucedida",
     *          @OA\JsonContent(
     *              type="object",
     *              ref="#/components/schemas/Product"
     *          )
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Produto não encontrado"
     *      )
     * )
     */
    public function getProductByCode(Request $request, $code)
    {
        // Verifica se a chave de API é válida
        $apiKey = $request->header('API-Key');
        $apiToken = ApiToken::where('api_key', $apiKey)->first();
        if (!$apiToken) {
            return response()->json(['message' => 'Chave de API inválida'], 401);
        }

        // Lógica para obter um único produto pelo código...
        $product = Product::where('code', $code)->first();

        if (!$product) {
            return response()->json(['message' => 'Produto não encontrado'], 404);
        }
        return response()->json($product);
    }
}
