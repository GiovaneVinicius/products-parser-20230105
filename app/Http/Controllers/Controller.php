<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    function importProductsFromUrl() {

        Http::timeout(0);

        // Obter as URLs do arquivo de índice do arquivo .env
        $urlIndex = env('URL_INDEX');
        $urlDownload = env('URL_DOWNLOAD');

        // Obter o conteúdo do arquivo de índice
        $indexContent = Http::withoutVerifying()->get($urlIndex)->body();

        // Dividir o conteúdo do índice em linhas para obter os nomes dos arquivos
        $fileNames = explode("\n", $indexContent);

        foreach ($fileNames as $fileName) {
            // Certifique-se de que estamos lidando com um nome de arquivo válido
            if (!empty($fileName)) {
                // Montar a URL de download para o arquivo .json.gz
                $downloadUrl = $urlDownload . $fileName;

                // Fazer o download do arquivo .json.gz
                $fileContent = Http::withoutVerifying()->get($downloadUrl)->body();

                // Salvar o arquivo .json.gz em uma pasta temporária
                $tempFilePath = storage_path('app/temp/') . $fileName;
                file_put_contents($tempFilePath, $fileContent);

                // Descompactar o arquivo .json.gz
                $jsonContent = gzdecode(file_get_contents($tempFilePath));

                // Decodificar o JSON e inserir as informações na tabela products
                $data = json_decode($jsonContent, true);
                foreach ($data as $product) {
                    DB::table('products')->insert($product);
                }

                // Excluir o arquivo temporário
                unlink($tempFilePath);
            }
        }
    }
}
