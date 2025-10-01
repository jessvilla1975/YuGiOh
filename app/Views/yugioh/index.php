<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
<meta http-equiv="X-UA-Compatible" content="ie=edge" />
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="/YuGiOh/public/css/style.css" rel="stylesheet">
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
    <script src="/YuGiOh/public/js/script.js"></script>
</body>
</html>