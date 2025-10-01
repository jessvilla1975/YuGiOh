<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pokédex</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
 
<style>
    .pokemon-card {
        border-radius: 15px;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        padding: 20px;
        margin-bottom: 20px;
        background: linear-gradient(135deg, #f5f5f5 0%, #e8e8e8 100%);
    }
    .pokemon-image {
        background: white;
        border-radius: 10px;
        padding: 10px;
        text-align: center;
    }
    .pokemon-image img {
        max-width: 200px;
        height: auto;
    }
    .ability-badge {
        margin: 2px;
    }
    .type-badge {
        margin: 2px;
        color: white;
    }
    .stat-bar {
        height: 10px;
        background-color: #e9ecef;
        border-radius: 5px;
        margin-bottom: 5px;
    }
    .stat-fill {
        height: 100%;
        border-radius: 5px;
        background: linear-gradient(90deg, #ff6b6b, #ffa500);
    }
    .vs-container {
        margin-top: 2rem;
    }
</style>
</head>
<body>
    <div class="container mt-5">
        <div class="row">
            <div class="col-12 text-center mb-4">
                <h1 class="display-4 text-primary">Pokedex</h1>
                <p class="lead">Batalla</p>
            </div>
        </div>

        <!-- Formulario de búsqueda -->
        <div class="row justify-content-center mb-4">
            <div class="col-md-8">
                <div class="card pokemon-card">
                    <div class="card-body">
                        <form id="pokemonForm">
                            <div class="row">
                                <div class="col-md-5">
                                    <div class="mb-3">
                                        <label for="pokemonInput1" class="form-label">Pokémon 1:</label>
                                        <input type="text" class="form-control" id="pokemonInput1" 
                                               placeholder="Ej: pikachu" required>
                                    </div>
                                </div>
                                <div class="col-md-2 text-center">
                                    <div class="vs-container mt-4">
                                        <h3 class="text-secondary">VS</h3>
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="mb-3">
                                        <label for="pokemonInput2" class="form-label">Pokémon 2:</label>
                                        <input type="text" class="form-control" id="pokemonInput2" 
                                               placeholder="Ej: charizard" required>
                                    </div>
                                </div>
                            </div>
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary btn-lg">Buscar</button>
                            </div>
                            <div class="form-text text-center">Ingresa los nombres</div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Loading Spinner -->
        <div id="loading" class="text-center" style="display: none;">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Buscando...</span>
            </div>
            <p>Buscando Pokémon...</p>
        </div>

        <!-- Resultados - Dos columnas -->
        <div class="row justify-content-center">
            <!-- Pokémon 1 -->
            <div class="col-md-5">
                <div id="resultadosPokemon1"></div>
            </div>
            
            <!-- Separador VS -->
            <div class="col-md-2 text-center">
                <div id="vsResultado" class="mt-5" style="display: none;">
                    <h3 class="text-secondary">VS</h3>
                </div>
            </div>
            
            <!-- Pokémon 2 -->
            <div class="col-md-5">
                <div id="resultadosPokemon2"></div>
            </div>
        </div>
    </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.getElementById('pokemonForm').addEventListener('submit', function(e) {
        e.preventDefault();
        buscarPokemon();
    });

    function buscarPokemon() {
        const pokemon1 = document.getElementById('pokemonInput1').value.trim();
        const pokemon2 = document.getElementById('pokemonInput2').value.trim();
        
        if (!pokemon1 || !pokemon2) {
            alert('Por favor ingresa ambos Pokémon');
            return;
        }

        const loading = document.getElementById('loading');
        const vsResultado = document.getElementById('vsResultado');
        const resultadosPokemon1 = document.getElementById('resultadosPokemon1');
        const resultadosPokemon2 = document.getElementById('resultadosPokemon2');
        
        loading.style.display = 'block';
        vsResultado.style.display = 'none';
        resultadosPokemon1.innerHTML = '';
        resultadosPokemon2.innerHTML = '';

        // Buscar ambos Pokémon en paralelo
        Promise.all([
            buscarPokemonAPI(pokemon1),
            buscarPokemonAPI(pokemon2)
        ])
        .then(([data1, data2]) => {
            loading.style.display = 'none';
            vsResultado.style.display = 'block';
            
            if (data1.success) {
                mostrarPokemon(data1.pokemon, 'resultadosPokemon1');
            } else {
                resultadosPokemon1.innerHTML = `
                    <div class="alert alert-danger">
                        <strong>Error Pokémon 1:</strong> ${data1.error}
                    </div>
                `;
            }
            
            if (data2.success) {
                mostrarPokemon(data2.pokemon, 'resultadosPokemon2');
            } else {
                resultadosPokemon2.innerHTML = `
                    <div class="alert alert-danger">
                        <strong>Error Pokémon 2:</strong> ${data2.error}
                    </div>
                `;
            }
        })
        .catch(error => {
            loading.style.display = 'none';
            console.error('Error:', error);
        });
    }

    function buscarPokemonAPI(pokemonName) {
        return fetch('/porcolDev/pokedex/buscar', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: 'pokemon=' + encodeURIComponent(pokemonName)
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Error en la respuesta del servidor: ' + response.status);
            }
            return response.json();
        });
    }

    function mostrarPokemon(pokemon, contenedorId) {
        const contenedor = document.getElementById(contenedorId);
        
        // Crear barras de estadísticas
        const statsHTML = Object.entries(pokemon.stats).map(([statName, value]) => `
            <div class="row align-items-center mb-2">
                <div class="col-5">
                    <small><strong>${statName.replace('-', ' ').toUpperCase()}:</strong></small>
                </div>
                <div class="col-3">
                    <small>${value}</small>
                </div>
                <div class="col-4">
                    <div class="stat-bar">
                        <div class="stat-fill" style="width: ${(value / 255) * 100}%"></div>
                    </div>
                </div>
            </div>
        `).join('');

        contenedor.innerHTML = `
            <div class="card pokemon-card">
                <div class="card-body text-center">
                    <!-- IMAGEN CENTRADA ARRIBA -->
                    <div class="pokemon-image mb-3">
                        <img src="${pokemon.sprites.front_default || 'https://via.placeholder.com/200?text=Imagen+no+disponible'}" 
                             alt="${pokemon.name}" 
                             class="img-fluid">
                        <h4 class="mt-2">${pokemon.name} #${pokemon.id}</h4>
                    </div>
                    
                    <!-- ESTADÍSTICAS -->
                    <div class="mb-3">
                        <h5>Estadísticas Base</h5>
                        <div class="stats-container bg-light p-2 rounded">
                            ${statsHTML}
                        </div>
                    </div>
                    
                    <!-- INFORMACIÓN GENERAL -->
                    <div class="row text-start">
                        <div class="col-12 mb-2">
                            <small><strong>Altura:</strong> ${pokemon.height} m</small>
                        </div>
                        <div class="col-12 mb-2">
                            <small><strong>Peso:</strong> ${pokemon.weight} kg</small>
                        </div>
                        <div class="col-12 mb-2">
                            <small><strong>Tipos:</strong> 
                                ${pokemon.types.map(type => `
                                    <span class="badge type-badge" style="background-color: ${getTypeColor(type)}">
                                        ${type.toUpperCase()}
                                    </span>
                                `).join('')}
                            </small>
                        </div>
                        <div class="col-12">
                            <small><strong>Habilidades:</strong> 
                                ${pokemon.abilities.map(ability => `
                                    <span class="badge bg-secondary">
                                        ${ability.name.replace('-', ' ')} 
                                        ${ability.is_hidden ? '(Oculta)' : ''}
                                    </span>
                                `).join('')}
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        `;
    }

    function getTypeColor(type) {
        const colors = {
            normal: '#A8A878', fire: '#F08030', water: '#6890F0',
            electric: '#F8D030', grass: '#78C850', ice: '#98D8D8',
            fighting: '#C03028', poison: '#A040A0', ground: '#E0C068',
            flying: '#A890F0', psychic: '#F85888', bug: '#A8B820',
            rock: '#B8A038', ghost: '#705898', dragon: '#7038F8',
            dark: '#705848', steel: '#B8B8D0', fairy: '#EE99AC'
        };
        return colors[type] || '#68A090';
    }

    // Valores por defecto
    window.addEventListener('load', function() {
        document.getElementById('pokemonInput1').value = 'pikachu';
        document.getElementById('pokemonInput2').value = 'charizard';
    });
</script>
</body>
</html>