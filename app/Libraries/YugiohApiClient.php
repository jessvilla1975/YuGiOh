<?php
namespace App\Libraries;

use App\Models\Card;
use Exception;

class YugiohApiClient
{
    private $apiBaseUrl;

    public function __construct(string $apiBaseUrl)
    {
        $this->apiBaseUrl = $apiBaseUrl;
    }

    /************************obtener cartas aleatorias**************************/
    public function obtenerCartasAleatorias(int $cantidad = 3): array
    {
        try {

            $url = $this->apiBaseUrl;
            $response = $this->performHttpRequest('GET', $url);
            $responseData = json_decode($response, true);
            
            if (!isset($responseData['data']) || empty($responseData['data'])) {
                throw new Exception('No se encontraron cartas');
            }
            $todasLasCartas = $responseData['data'];
            $cartasMonster = array_filter($todasLasCartas, function($carta) { // aqui filtro las monster
                return isset($carta['atk']) && 
                       isset($carta['def']) && 
                       $carta['atk'] !== null && 
                       $carta['def'] !== null &&
                       strpos($carta['type'], 'Monster') !== false;
            });

            if (empty($cartasMonster)) {
                throw new Exception('No se encontraron cartas Monster válidas');
            }

            $cartasMonster = array_values($cartasMonster);
            shuffle($cartasMonster); //mezclar cartas para obtener aleatorias
            $cartasSeleccionadas = array_slice($cartasMonster, 0, $cantidad);

            $cartas = [];
            foreach ($cartasSeleccionadas as $cartaData) {
                $cartas[] = new Card($cartaData);
            }

            return $cartas;

        } catch (Exception $e) {
            log_message('error', 'YugiohApiClient Error: ' . $e->getMessage());
            throw $e;
        }
    }

     /************************funcion buscar carta**************************/
    public function buscarCarta(string $nombreCarta): ?Card
    {
        $nombreCarta = trim($nombreCarta);
        $url = $this->apiBaseUrl . '?name=' . urlencode($nombreCarta);

        try {
            $response = $this->performHttpRequest('GET', $url);
            $responseData = json_decode($response, true);
            
            if (!isset($responseData['data']) || empty($responseData['data'])) {
                return null;
            }
            return new Card($responseData['data'][0]);

        } catch (Exception $e) {
            log_message('error', 'YugiohApiClient Error: ' . $e->getMessage());
            return null;
        }
    }

    /************************funcion para hacer peticiones http**************************/

    private function performHttpRequest($method, $url, $data = false) 
    {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_USERAGENT => 'YugiohDuel-App/1.0'
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