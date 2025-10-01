<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
<meta http-equiv="X-UA-Compatible" content="ie=edge" />
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">


    <style>
        body {
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
            min-height: 100vh;
            color: white;
        }
        
        .duel-container {
            margin-top: 2rem;
        }
        
        .card-yugioh {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 15px;
            padding: 15px;
            margin: 10px;
            backdrop-filter: blur(10px);
            border: 2px solid rgba(255, 215, 0, 0.3);
            transition: all 0.3s ease;
            cursor: pointer;
        }
        
        .card-yugioh:hover:not(.used) {
            transform: translateY(-10px);
            border-color: gold;
            box-shadow: 0 10px 30px rgba(255, 215, 0, 0.5);
        }
        
        .card-yugioh.selected {
            border-color: #00ff00;
            box-shadow: 0 0 20px rgba(0, 255, 0, 0.8);
        }
        
        .card-yugioh.machine-selected {
            border-color: #ff0000;
            box-shadow: 0 0 20px rgba(255, 0, 0, 0.8);
        }

        .card-yugioh.used {
            opacity: 0.3;
            cursor: not-allowed;
            filter: grayscale(100%);
        }
        
        .card-image {
            width: 100%;
            border-radius: 10px;
            margin-bottom: 10px;
        }
        
        .stat-badge {
            background: rgba(0, 0, 0, 0.7);
            padding: 5px 10px;
            border-radius: 5px;
            margin: 3px;
            display: inline-block;
            font-weight: bold;
        }
        
        .atk-stat {
            color: #ff6b6b;
        }
        
        .def-stat {
            color: #4dabf7;
        }
        
        .player-section {
            background: rgba(0, 123, 255, 0.2);
            border: 2px solid #007bff;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 20px;
        }
        
        .machine-section {
            background: rgba(220, 53, 69, 0.2);
            border: 2px solid #dc3545;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 20px;
        }
        
        .score-board {
            background: rgba(0, 0, 0, 0.6);
            border-radius: 15px;
            padding: 20px;
            margin: 20px 0;
        }

        .round-indicator {
            display: inline-block;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin: 0 5px;
            border: 3px solid #666;
            background: rgba(0, 0, 0, 0.5);
            line-height: 34px;
            text-align: center;
            font-weight: bold;
        }

        .round-indicator.win {
            background: #00ff00;
            border-color: #00ff00;
            color: #000;
        }

        .round-indicator.lose {
            background: #ff0000;
            border-color: #ff0000;
            color: #fff;
        }

        .round-indicator.active {
            border-color: gold;
            animation: pulse-border 1s infinite;
        }

        @keyframes pulse-border {
            0%, 100% { box-shadow: 0 0 10px gold; }
            50% { box-shadow: 0 0 20px gold; }
        }
        
        .battle-log {
            background: rgba(0, 0, 0, 0.6);
            border-radius: 10px;
            padding: 15px;
            margin-top: 20px;
            max-height: 300px;
            overflow-y: auto;
        }
        
        .log-entry {
            padding: 8px;
            margin: 5px 0;
            border-left: 3px solid gold;
            background: rgba(255, 255, 255, 0.05);
        }
        
        .attribute-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            padding: 5px 10px;
            border-radius: 50%;
            font-weight: bold;
            font-size: 12px;
        }
        
        .btn-duel {
            background: linear-gradient(135deg, gold, #ffd700);
            color: #000;
            font-weight: bold;
            border: none;
            padding: 15px 40px;
            font-size: 1.2rem;
            border-radius: 10px;
            transition: all 0.3s ease;
        }
        
        .btn-duel:hover {
            transform: scale(1.1);
            box-shadow: 0 0 30px gold;
        }
        
        .winner-announcement {
            background: linear-gradient(135deg, #ffd700, #ffed4e);
            color: #000;
            padding: 30px;
            border-radius: 20px;
            text-align: center;
            font-size: 2rem;
            font-weight: bold;
            margin: 20px 0;
            animation: pulse 1s infinite;
        }
        
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }

        .current-round {
            font-size: 1.5rem;
            color: gold;
            text-align: center;
            margin: 20px 0;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container duel-container">
        <div class="row">
            <div class="col-12 text-center mb-4">
                <h1 class="display-3" style="color: gold; text-shadow: 3px 3px 6px rgba(0,0,0,0.8);">
                        YU-GI-OH! DUEL 
                </h1>
                
            </div>
        </div>

        <!--  iniciar -->
        <div class="row justify-content-center mb-4">
            <div class="col-auto">
                <button id="btnIniciarDuelo" class="btn btn-duel">
                        INICIAR DUELO 
                </button>
            </div>
        </div>

        <!-- Loading -->
        <div id="loading" class="text-center" style="display: none;">
            <div class="spinner-border text-warning" role="status" style="width: 3rem; height: 3rem;">
                <span class="visually-hidden">Cargando...</span>
            </div>
            <p class="mt-3">cargando cartas...</p>
        </div>

        <!-- anuncios de Ganador -->
        <div id="winnerAnnouncement" style="display: none;"></div>

        <!-- marcador de Rondas -->
        <div id="scoreBoard" class="score-board" style="display: none;">
            <div class="row">
                <div class="col-md-5 text-center">
                    <h5>maquina</h5>
                    <div id="rondasMaquina">
                        <span class="round-indicator">1</span>
                        <span class="round-indicator">2</span>
                        <span class="round-indicator">3</span>
                    </div>
                </div>
                <div class="col-md-2 text-center">
                    <div class="current-round" id="currentRound">RONDA 1</div>
                </div>
                <div class="col-md-5 text-center">
                    <h5>jugador</h5>
                    <div id="rondasJugador">
                        <span class="round-indicator">1</span>
                        <span class="round-indicator">2</span>
                        <span class="round-indicator">3</span>
                    </div>
                </div>
            </div>
        </div>

        <!--  maquina -->
        <div id="machineSection" class="machine-section" style="display: none;">
            <h3 class="text-center mb-3">maquina</h3>
            <div id="cartasMaquina" class="row justify-content-center"></div>
        </div>

        <!-- vs -->
        <div id="vsSection" class="text-center my-4" style="display: none;">
            <h2 style="color: gold; font-size: 3rem; text-shadow: 3px 3px 6px rgba(0,0,0,0.8);">⚡ VS ⚡</h2>
        </div>

        <!--  Jugador -->
        <div id="playerSection" class="player-section" style="display: none;">
            <h3 class="text-center mb-3"> jugador</h3>
            <div id="cartasJugador" class="row justify-content-center"></div>
            <div class="text-center mt-3">
                <button id="btnAtacar" class="btn btn-danger btn-lg" disabled>
                        BATALLA 
                </button>
            </div>
        </div>

        <!-- Log de Batalla -->
        <div id="battleLog" class="battle-log" style="display: none;">
            <h5 class="text-center mb-3">📜 REGISTRO DE BATALLA</h5>
            <div id="logContent"></div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        let cartasJugador = [];
        let cartasMaquina = [];
        let cartaSeleccionadaJugador = null;
        let cartasUsadasJugador = [];
        let cartasUsadasMaquina = [];
        let victoriasJugador = 0;
        let victoriasMaquina = 0;
        let rondaActual = 1;
        let juegoTerminado = false;

        document.getElementById('btnIniciarDuelo').addEventListener('click', iniciarDuelo);
        document.getElementById('btnAtacar').addEventListener('click', realizarBatalla);

        function iniciarDuelo() {
            const loading = document.getElementById('loading');
            loading.style.display = 'block';
            
            // limpiar
            document.getElementById('machineSection').style.display = 'none';
            document.getElementById('playerSection').style.display = 'none';
            document.getElementById('vsSection').style.display = 'none';
            document.getElementById('battleLog').style.display = 'none';
            document.getElementById('winnerAnnouncement').style.display = 'none';
            document.getElementById('scoreBoard').style.display = 'none';
            victoriasJugador = 0;
            victoriasMaquina = 0;
            rondaActual = 1;
            juegoTerminado = false;
            cartaSeleccionadaJugador = null;
            cartasUsadasJugador = [];
            cartasUsadasMaquina = [];
            document.getElementById('logContent').innerHTML = '';

            fetch('/YuGiOh/yugioh/iniciar', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                loading.style.display = 'none';
                
                if (data.success) {
                    cartasJugador = data.jugador;
                    cartasMaquina = data.maquina;
                    
                    mostrarCartas();
                    document.getElementById('machineSection').style.display = 'block';
                    document.getElementById('playerSection').style.display = 'block';
                    document.getElementById('vsSection').style.display = 'block';
                    document.getElementById('battleLog').style.display = 'block';
                    document.getElementById('scoreBoard').style.display = 'block';
                    
                    actualizarMarcador();
                    addLog('¡El duelo ha comenzado! Mejor de 3 rondas. ¡Selecciona tu carta!');
                } else {
                    alert('Error: ' + data.error);
                }
            })
            .catch(error => {
                loading.style.display = 'none';
                console.error('Error:', error);
                alert('Error al cargar las cartas');
            });
        }

        function mostrarCartas() {
            const jugadorContainer = document.getElementById('cartasJugador');
            jugadorContainer.innerHTML = '';
            
            cartasJugador.forEach((carta, index) => {
                const usada = cartasUsadasJugador.includes(index);
                jugadorContainer.innerHTML += crearCartaHTML(carta, index, 'jugador', usada);
            });

            const maquinaContainer = document.getElementById('cartasMaquina');
            maquinaContainer.innerHTML = '';
            
            cartasMaquina.forEach((carta, index) => {
                const usada = cartasUsadasMaquina.includes(index);
                maquinaContainer.innerHTML += crearCartaHTML(carta, index, 'maquina', usada);
            });

            // Agregar eventos de click a las cartas del jugador
            document.querySelectorAll('.carta-jugador:not(.used)').forEach(carta => {
                carta.addEventListener('click', function() {
                    if (juegoTerminado) return;
                    
                    document.querySelectorAll('.carta-jugador').forEach(c => c.classList.remove('selected'));
                    this.classList.add('selected');
                    cartaSeleccionadaJugador = parseInt(this.dataset.index);
                    document.getElementById('btnAtacar').disabled = false;
                });
            });
        }

        function crearCartaHTML(carta, index, tipo, usada = false) {
            const atributoColor = getAttributeColor(carta.attribute);
            const usedClass = usada ? 'used' : '';
            
            return `
                <div class="col-md-4">
                    <div class="card-yugioh carta-${tipo} ${usedClass}" data-index="${index}">
                        <div style="position: relative;">
                            <span class="attribute-badge" style="background: ${atributoColor};">
                                ${carta.attribute || 'N/A'}
                            </span>
                            <img src="${carta.image_url_small}" alt="${carta.name}" class="card-image">
                        </div>
                        <h6 class="text-center" style="color: gold;">${carta.name}</h6>
                        <div class="text-center">
                            <span class="stat-badge atk-stat">ATK: ${carta.atk}</span>
                            <span class="stat-badge def-stat">DEF: ${carta.def}</span>
                        </div>
                        <div class="text-center mt-2">
                            <small style="color: #aaa;">⭐ Nivel ${carta.level}</small><br>
                            <small style="color: #ccc;">${carta.race}</small>
                        </div>
                    </div>
                </div>
            `;
        }

        function realizarBatalla() {
            if (juegoTerminado || cartaSeleccionadaJugador === null) return;

            const cartaJugador = cartasJugador[cartaSeleccionadaJugador];
            
            // La maquina elige una carta aleatoria que no haya usado
            const cartasDisponiblesMaquina = cartasMaquina
                .map((carta, index) => ({carta, index}))
                .filter(item => !cartasUsadasMaquina.includes(item.index));
            
            if (cartasDisponiblesMaquina.length === 0) {
                addLog('¡Error! La maquina no tiene cartas disponibles.', 'danger');
                return;
            }

            const seleccionMaquina = cartasDisponiblesMaquina[Math.floor(Math.random() * cartasDisponiblesMaquina.length)];
            const indiceMaquina = seleccionMaquina.index;
            const cartaMaquina = seleccionMaquina.carta;

            // Marcar cartas como usadas
            cartasUsadasJugador.push(cartaSeleccionadaJugador);
            cartasUsadasMaquina.push(indiceMaquina);

            // Resaltar cartas seleccionadas
            document.querySelectorAll('.carta-maquina').forEach((c, i) => {
                c.classList.remove('machine-selected');
                if (i === indiceMaquina) {
                    c.classList.add('machine-selected');
                }
            });

            addLog(`RONDA ${rondaActual}: ${cartaJugador.name} (ATK: ${cartaJugador.atk}) VS ${cartaMaquina.name} (ATK: ${cartaMaquina.atk})`);

            setTimeout(() => {
                let ganadorRonda = '';
                
                if (cartaJugador.atk > cartaMaquina.atk) {
                    victoriasJugador++;
                    ganadorRonda = 'jugador';
                    addLog(`¡El JUGADOR gana la ronda ${rondaActual}!`, 'success');
                } else if (cartaMaquina.atk > cartaJugador.atk) {
                    victoriasMaquina++;
                    ganadorRonda = 'maquina';
                    addLog(`¡La maquina gana la ronda ${rondaActual}!`, 'danger');
                } else {
                    // En caso de empate, gana quien tenga mayor DEF
                    if (cartaJugador.def > cartaMaquina.def) {
                        victoriasJugador++;
                        ganadorRonda = 'jugador';
                        addLog(`¡Empate en ATK! El JUGADOR gana por DEF (${cartaJugador.def} vs ${cartaMaquina.def})`, 'success');
                    } else if (cartaMaquina.def > cartaJugador.def) {
                        victoriasMaquina++;
                        ganadorRonda = 'maquina';
                        addLog(`¡Empate en ATK! La maquina gana por DEF (${cartaMaquina.def} vs ${cartaJugador.def})`, 'danger');
                    } else {
                        
                        if (Math.random() > 0.5) { // Empate total - se decide aleatoriamente
                            victoriasJugador++;
                            ganadorRonda = 'jugador';
                            addLog(`🎲 ¡Empate total! El JUGADOR gana por suerte`, 'success');
                        } else {
                            victoriasMaquina++;
                            ganadorRonda = 'maquina';
                            addLog(`🎲 ¡Empate total! La maquina gana por suerte`, 'danger');
                        }
                    }
                }

                actualizarMarcador(ganadorRonda);
                verificarGanador();

                if (!juegoTerminado) {
                    rondaActual++;
                    document.getElementById('currentRound').textContent = `RONDA ${rondaActual}`;
                    mostrarCartas();
                    cartaSeleccionadaJugador = null;
                    document.getElementById('btnAtacar').disabled = true;
                }
            }, 1000);
        }

        function actualizarMarcador(ganadorUltimaRonda = null) {
            const indicadoresMaquina = document.querySelectorAll('#rondasMaquina .round-indicator');
            const indicadoresJugador = document.querySelectorAll('#rondasJugador .round-indicator');

            // actualiza indicadores
            indicadoresMaquina.forEach((ind, i) => {
                ind.classList.remove('win', 'lose', 'active');
                if (i < victoriasMaquina) {
                    ind.classList.add('win');
                } else if (i < rondaActual - 1) {
                    ind.classList.add('lose');
                } else if (i === rondaActual - 1) {
                    ind.classList.add('active');
                }
            });

            // Actualizar indicadores de jugador
            indicadoresJugador.forEach((ind, i) => {
                ind.classList.remove('win', 'lose', 'active');
                if (i < victoriasJugador) {
                    ind.classList.add('win');
                } else if (i < rondaActual - 1) {
                    ind.classList.add('lose');
                } else if (i === rondaActual - 1) {
                    ind.classList.add('active');
                }
            });
        }

        function verificarGanador() {
            if (victoriasJugador >= 2) {
                juegoTerminado = true;
                mostrarGanador('¡VICTORIA! ¡GANASTE EL DUELO!', 'success');
                document.getElementById('btnAtacar').disabled = true;
            } else if (victoriasMaquina >= 2) {
                juegoTerminado = true;
                mostrarGanador('LA maquina GANÓ EL DUELO', 'danger');
                document.getElementById('btnAtacar').disabled = true;
            }
        }

        function mostrarGanador(mensaje, tipo) {
            const announcement = document.getElementById('winnerAnnouncement');
            announcement.innerHTML = mensaje;
            announcement.className = 'winner-announcement alert alert-' + tipo;
            announcement.style.display = 'block';
            addLog('═══════════════════════════════');
            addLog(mensaje, tipo);
            addLog('═══════════════════════════════');
        }

        function addLog(mensaje, tipo = 'info') {
            const logContent = document.getElementById('logContent');
            const entry = document.createElement('div');
            entry.className = 'log-entry alert alert-' + tipo;
            entry.textContent = mensaje;
            logContent.appendChild(entry);
            logContent.scrollTop = logContent.scrollHeight;
        }

        function getAttributeColor(attribute) {
            const colors = {
                'DARK': '#8B008B',
                'LIGHT': '#FFD700',
                'EARTH': '#8B4513',
                'WATER': '#1E90FF',
                'FIRE': '#FF4500',
                'WIND': '#00CED1',
                'DIVINE': '#FFD700'
            };
            return colors[attribute] || '#666';
        }
    </script>
</body>
</html>