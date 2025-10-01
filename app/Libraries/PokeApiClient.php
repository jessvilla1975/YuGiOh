<?php
namespace App\Libraries;

use App\Models\Pokemon;
use Exception;

class PokeApiClient
{
    private $apiBaseUrl;

    public function __construct(string $apiBaseUrl)
    {
        $this->apiBaseUrl = $apiBaseUrl;
    }

    public function buscarPokemon(string $pokemonName): ?Pokemon
    {
        $pokemonName = strtolower(trim($pokemonName));
        $url = $this->apiBaseUrl . $pokemonName;

        try {
            $response = $this->performHttpRequest('GET', $url);
            $responseData = json_decode($response, true);
            
            if (isset($responseData['detail']) && $responseData['detail'] === 'No encontrado') {
                return null;
            }
            
            if (!$responseData || isset($responseData['error'])) {
                return null;
            }

            return new Pokemon($responseData);

        } catch (Exception $e) {
            log_message('error', 'PokeApiClient Error: ' . $e->getMessage());
            throw $e; // Re-lanzamos la excepción original
        }
    }

    private function performHttpRequest($method, $url, $data = false) {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_USERAGENT => 'Pokedex-App/1.0'
        ]);

        $result = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        
        if (curl_error($curl)) {
            throw new Exception('CURL Error: ' . curl_error($curl));
        }
        
        curl_close($curl);

        if ($httpCode !== 200) {
            throw new Exception('HTTP Error: ' . $httpCode);
        }

        return $result;
    }
}