<?php
namespace App\Controllers;

use CodeIgniter\Controller;
use App\Libraries\YugiohApiClient;

class YugiohDuelController extends BaseController
{
    protected $apiBaseUrl;
    protected $yugiohApiClient;

    public function __construct()
    {
        $this->apiBaseUrl = getenv('API_BASE_URL');
        $this->yugiohApiClient = new YugiohApiClient($this->apiBaseUrl);
    }
    /*************************funcion para cargar view de inicio***************************************/

    public function index()
    {
        return view('yugioh/index');
    }

    /*************************funcion para iniciar duelo***************************************/

    public function iniciarDuelo()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON([
                'success' => false,
                'error' => 'Método no permitido'
            ]);
        }

        try {   
            $cartasJugador = $this->yugiohApiClient->obtenerCartasAleatorias(3); // selecccionna las tres cartas aleatorias para jugador o maquina
            $cartasMaquina = $this->yugiohApiClient->obtenerCartasAleatorias(3);

            $jugadorArray = array_map(function($carta) {
                return $carta->toArray();
            }, $cartasJugador);

            $maquinaArray = array_map(function($carta) {
                return $carta->toArray();
            }, $cartasMaquina);

            return $this->response->setJSON([
                'success' => true,
                'jugador' => $jugadorArray,
                'maquina' => $maquinaArray
            ]);

        } catch (\Exception $e) {  
            log_message('error', 'Error al iniciar duelo: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'error' => 'Error al obtener las cartas: ' . $e->getMessage()
            ]);
        }
    }
}