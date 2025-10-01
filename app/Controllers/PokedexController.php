<?php
namespace App\Controllers;

use CodeIgniter\Controller;
use App\Libraries\PokeApiClient;

class PokedexController extends BaseController
{
    protected $apiBaseUrl;
    protected $pokeApiClient;

    public function __construct()
    {
        $this->apiBaseUrl = getenv('API_BASE_URL');
        $this->pokeApiClient = new PokeApiClient($this->apiBaseUrl);
    }

    public function index()
    {
        return view('pokedex/index');
    }

    public function buscarPokemon()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON([
                'success' => false,
                'error' => 'Método no permitido'
            ]);
        }

        $pokemon = $this->request->getPost('pokemon');
        $pokemon = strtolower(trim($pokemon));

        try {   
            $pokemonModel = $this->pokeApiClient->buscarPokemon($pokemon);
            
            if ($pokemonModel === null) {
                return $this->response->setJSON([
                    'success' => false,
                    'error' => 'Pokémon no encontrado'
                ]);
            }

            return $this->response->setJSON([
                'success' => true,
                'pokemon' => $pokemonModel->toArray()
            ]);

        } catch (\Exception $e) {  
            log_message('error', 'Error al obtener Pokémon: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'error' => 'Error al buscar el Pokémon: ' . $e->getMessage()
            ]);
        }
    }
}