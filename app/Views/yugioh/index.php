<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Yu-Gi-Oh! Duel Arena</title>
    <link href="/YuGiOh/public/css/style.css" rel="stylesheet">
</head>
<body>
    <!-- Partículas decorativas -->
    <div class="particles" id="particles"></div>

    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1 class="title">YU-GI-OH!</h1>
            <div class="subtitle">Duel Arena</div>
        </div>

        <!-- Botón Inicio -->
        <div style="text-align: center; margin-bottom: 10px;">
            <button class="start-btn" id="btnStart">⚡ Iniciar Duelo ⚡</button>
        </div>

        <!-- Loading -->
        <div class="loading" id="loading">
            <div class="spinner"></div>
            <p style="font-size: 1.2rem; color: #ffd700;">Invocando cartas...</p>
        </div>

        <!-- Winner Announcement -->
        <div class="winner-announcement" id="winnerAnnouncement"></div>

        <!-- Scoreboard -->
        <div class="scoreboard" id="scoreboard">
            <div class="score-grid">
                <div class="machine-score">
                    <div class="score-label">🤖 Máquina</div>
                    <div class="rounds" id="machineRounds">
                        <div class="round-dot">1</div>
                        <div class="round-dot">2</div>
                        <div class="round-dot">3</div>
                    </div>
                </div>
                <div class="current-round" id="currentRound">RONDA 1</div>
                <div class="player-score">
                    <div class="score-label">👤 Jugador</div>
                    <div class="rounds" id="playerRounds">
                        <div class="round-dot">1</div>
                        <div class="round-dot">2</div>
                        <div class="round-dot">3</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Battle Area -->
        <div class="battle-area" id="battleArea">
            <div class="battle-grid">
                <!-- Machine Section -->
                <div class="section machine-section">
                    <h2 class="section-title">🤖 Cartas de la Máquina</h2>
                    <div class="cards-grid" id="machineCards"></div>
                </div>

                <!-- Player Section -->
                <div class="section player-section">
                    <h2 class="section-title">👤 Tus Cartas</h2>
                    <div class="cards-grid" id="playerCards"></div>
                </div>

                <!-- Battle Button (debajo de ambos mazos) -->
                <div class="battle-btn-container">
                    <button class="battle-btn" id="btnBattle" disabled>⚔️ Atacar ⚔️</button>
                </div>
            </div>
        </div>

        <!-- Battle Log -->
        <div class="battle-log" id="battleLog">
            <div class="log-title">📜 Registro de Batalla</div>
            <div class="log-content-wrapper" id="logContent"></div>
        </div>
    </div>

    <script src="/YuGiOh/public/js/script.js"></script>
</body>
</html>