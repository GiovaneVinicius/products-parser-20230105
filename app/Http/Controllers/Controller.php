<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\APIStatus;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    function importProductsFromUrl() {
        for($i = 1; $i <= 9; $i++)
        {
            // Buscar todos os arquivos .gz
            $gzip_file = 'https://challenges.coode.sh/food/data/json/products_0'.$i.'.json.gz';
            $destination = 'products_0'.$i.'.json';

            $gzip_data = file_get_contents($gzip_file);

            file_put_contents($destination . '.gz', $gzip_data);

            $gzip_handle = gzopen($destination . '.gz', 'rb');
            $json_data = [];

            //Apenas os primeiros 100 registros de cada arquivo
            for ($j = 0; $j < 100; $j++) {
                $line = gzgets($gzip_handle);
                if ($line === false) {
                    break;
                }
                array_push($json_data, $line);
            }

            gzclose($gzip_handle);

            //Removendo caracteres desnecessários
            $formatterOne = str_replace(';;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;', '', $json_data);
            $formatterTwo = str_replace(';;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;', '', $formatterOne);
            $formatterThree = str_replace('"\\', '', $formatterTwo);
            $formatterFour = str_replace(':r"', ':""', $formatterThree);
            $productFinal = str_replace('} {', '},{', $formatterFour );
            
            $status = "";
            foreach ($productFinal as  $productUnit) {
                $productData = json_decode($productUnit, true);
                $product = Product::updateOrCreate(
                    ['code' => intval( $productData['code'] )],
                    [
                        'status'           => 'published',
                        'imported_t'       => now(),
                        'url'              => $productData['url'] ?? null,
                        'creator'          => $productData['creator'] ?? null,
                        'created_t'        => $productData['created_t'] ?? null,
                        'last_modified_t'  => $productData['last_modified_t'] ?? null,
                        'product_name'     => $productData['product_name'] ?? null,
                        'quantity'         => $productData['quantity'] ?? null,
                        'brands'           => $productData['brands'] ?? null,
                        'categories'       => $productData['categories'] ?? null,
                        'labels'           => $productData['labels'] ?? null,
                        'cities'           => $productData['cities'] ?? null,
                        'purchase_places'  => $productData['purchase_places'] ?? null,
                        'stores'           => $productData['stores'] ?? null,
                        'ingredients_text' => $productData['ingredients_text'] ?? null,
                        'traces'           => $productData['traces'] ?? null,
                        'serving_size'     => $productData['serving_size'] ?? null,
                        'serving_quantity' => intval($productData['serving_quantity']) ?? null,
                        'nutriscore_score' => intval($productData['nutriscore_score']) ?? null,
                        'nutriscore_grade' => $productData['nutriscore_grade'] ?? null,
                        'main_category'    => $productData['main_category'] ?? null,
                        'image_url'        => $productData['image_url'] ?? null,
                    ]
                );

                if ($product) $status = "ARQUIVOS BAIXADOS COM SUCESSO!";
                else $status = "FALHA NO DOWNLOAD, POR FAVRO VERIFIQUE O ARQUIVO laravel.log.";
            }
        }
        $memory = memory_get_usage();
        $memoryConsumed = round($memory / 1024) . 'KB';

        //Grava o status de importação no banco
        APIStatus::create(
            [
                'dateImport'     => now(),
                'status'         => $status,
                'memoryConsumed' => $memoryConsumed,
            ]
        );
    }
}
