// Variables globales
let cartasJugador = [];
let cartasMaquina = [];
let cartaSeleccionadaJugador = null;
let cartasUsadasJugador = [];
let cartasUsadasMaquina = [];
let victoriasJugador = 0;
let victoriasMaquina = 0;
let rondaActual = 1;
let juegoTerminado = false;

// Crear partículas decorativas
function crearParticulas() {
    const container = document.getElementById('particles');
    for (let i = 0; i < 30; i++) {
        const particle = document.createElement('div');
        particle.className = 'particle';
        particle.style.left = Math.random() * 100 + '%';
        particle.style.animationDelay = Math.random() * 15 + 's';
        particle.style.animationDuration = (10 + Math.random() * 10) + 's';
        container.appendChild(particle);
    }
}

// Inicializar partículas al cargar
crearParticulas();

// Event Listeners
document.getElementById('btnStart').addEventListener('click', iniciarDuelo);
document.getElementById('btnBattle').addEventListener('click', realizarBatalla);

function iniciarDuelo() {
    const loading = document.getElementById('loading');
    loading.style.display = 'block';
    
    // Limpiar y ocultar secciones
    document.getElementById('battleArea').style.display = 'none';
    document.getElementById('scoreboard').style.display = 'none';
    document.getElementById('battleLog').style.display = 'none';
    document.getElementById('winnerAnnouncement').style.display = 'none';
    
    // Resetear variables
    victoriasJugador = 0;
    victoriasMaquina = 0;
    rondaActual = 1;
    juegoTerminado = false;
    cartaSeleccionadaJugador = null;
    cartasUsadasJugador = [];
    cartasUsadasMaquina = [];
    document.getElementById('logContent').innerHTML = '';

    // Llamada al servidor
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
            document.getElementById('battleArea').style.display = 'block';
            document.getElementById('scoreboard').style.display = 'block';
            document.getElementById('battleLog').style.display = 'block';
            
            actualizarMarcador();
            agregarLog('¡El duelo ha comenzado! Mejor de 3 rondas. ¡Selecciona tu carta!');
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
    // Mostrar cartas del jugador
    const jugadorContainer = document.getElementById('playerCards');
    jugadorContainer.innerHTML = '';
    
    cartasJugador.forEach((carta, index) => {
        const usada = cartasUsadasJugador.includes(index);
        jugadorContainer.innerHTML += crearCartaHTML(carta, index, 'player', usada);
    });

    // Mostrar cartas de la máquina
    const maquinaContainer = document.getElementById('machineCards');
    maquinaContainer.innerHTML = '';
    
    cartasMaquina.forEach((carta, index) => {
        const usada = cartasUsadasMaquina.includes(index);
        maquinaContainer.innerHTML += crearCartaHTML(carta, index, 'machine', usada);
    });

    // Agregar eventos de click a las cartas del jugador
    document.querySelectorAll('.card-player:not(.used)').forEach(carta => {
        carta.addEventListener('click', function() {
            if (juegoTerminado) return;
            
            document.querySelectorAll('.card-player').forEach(c => c.classList.remove('selected'));
            this.classList.add('selected');
            cartaSeleccionadaJugador = parseInt(this.dataset.index);
            document.getElementById('btnBattle').disabled = false;
        });
    });
}

function crearCartaHTML(carta, index, tipo, usada = false) {
    const atributoColor = obtenerColorAtributo(carta.attribute);
    const usedClass = usada ? 'used' : '';
    
    return `
        <div class="card card-${tipo} ${usedClass}" data-index="${index}">
            <div class="attribute-badge" style="background: ${atributoColor};">
                ${carta.attribute || 'N/A'}
            </div>
            <img src="${carta.image_url_small}" alt="${carta.name}" class="card-image" 
                 onerror="this.src='https://via.placeholder.com/200x280/1a1a2e/ffd700?text=Yu-Gi-Oh'">
            <div class="card-name">${carta.name}</div>
            <div class="card-stats">
                <div class="stat atk">ATK: ${carta.atk}</div>
                <div class="stat def">DEF: ${carta.def}</div>
            </div>
            <div class="card-info">
                ⭐ Nivel ${carta.level}<br>
                ${carta.race}
            </div>
        </div>
    `;
}

function realizarBatalla() {
    if (juegoTerminado || cartaSeleccionadaJugador === null) return;

    const cartaJugador = cartasJugador[cartaSeleccionadaJugador];
    
    // La máquina elige una carta aleatoria que no haya usado
    const cartasDisponiblesMaquina = cartasMaquina
        .map((carta, index) => ({carta, index}))
        .filter(item => !cartasUsadasMaquina.includes(item.index));
    
    if (cartasDisponiblesMaquina.length === 0) {
        agregarLog('¡Error! La máquina no tiene cartas disponibles.', 'danger');
        return;
    }

    const seleccionMaquina = cartasDisponiblesMaquina[Math.floor(Math.random() * cartasDisponiblesMaquina.length)];
    const indiceMaquina = seleccionMaquina.index;
    const cartaMaquina = seleccionMaquina.carta;

    // Marcar cartas como usadas
    cartasUsadasJugador.push(cartaSeleccionadaJugador);
    cartasUsadasMaquina.push(indiceMaquina);

    // Resaltar carta seleccionada por la máquina
    setTimeout(() => {
        document.querySelectorAll('.card-machine').forEach((c, i) => {
            c.classList.remove('machine-selected');
            if (i === indiceMaquina) {
                c.classList.add('machine-selected');
            }
        });
    }, 100);

    agregarLog(`RONDA ${rondaActual}: ${cartaJugador.name} (ATK: ${cartaJugador.atk}) VS ${cartaMaquina.name} (ATK: ${cartaMaquina.atk})`);

    setTimeout(() => {
        let ganadorRonda = '';
        
        if (cartaJugador.atk > cartaMaquina.atk) {
            victoriasJugador++;
            ganadorRonda = 'jugador';
            agregarLog(`¡El JUGADOR gana la ronda ${rondaActual}!`, 'success');
        } else if (cartaMaquina.atk > cartaJugador.atk) {
            victoriasMaquina++;
            ganadorRonda = 'maquina';
            agregarLog(`¡La MÁQUINA gana la ronda ${rondaActual}!`, 'danger');
        } else {
            // En caso de empate, gana quien tenga mayor DEF
            if (cartaJugador.def > cartaMaquina.def) {
                victoriasJugador++;
                ganadorRonda = 'jugador';
                agregarLog(`¡Empate en ATK! El JUGADOR gana por DEF (${cartaJugador.def} vs ${cartaMaquina.def})`, 'success');
            } else if (cartaMaquina.def > cartaJugador.def) {
                victoriasMaquina++;
                ganadorRonda = 'maquina';
                agregarLog(`¡Empate en ATK! La MÁQUINA gana por DEF (${cartaMaquina.def} vs ${cartaJugador.def})`, 'danger');
            } else {
                // Empate total - se decide aleatoriamente
                if (Math.random() > 0.5) {
                    victoriasJugador++;
                    ganadorRonda = 'jugador';
                    agregarLog(`🎲 ¡Empate total! El JUGADOR gana por suerte`, 'success');
                } else {
                    victoriasMaquina++;
                    ganadorRonda = 'maquina';
                    agregarLog(`🎲 ¡Empate total! La MÁQUINA gana por suerte`, 'danger');
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
            document.getElementById('btnBattle').disabled = true;
        }
    }, 1000);
}

function actualizarMarcador(ganadorUltimaRonda = null) {
    const indicadoresMaquina = document.querySelectorAll('#machineRounds .round-dot');
    const indicadoresJugador = document.querySelectorAll('#playerRounds .round-dot');

    // Actualizar indicadores de la máquina
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

    // Actualizar indicadores del jugador
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
        mostrarGanador('¡VICTORIA! ¡GANASTE EL DUELO! 🏆', 'success');
        document.getElementById('btnBattle').disabled = true;
    } else if (victoriasMaquina >= 2) {
        juegoTerminado = true;
        mostrarGanador('LA MÁQUINA GANÓ EL DUELO 💀', 'danger');
        document.getElementById('btnBattle').disabled = true;
    }
}

function mostrarGanador(mensaje, tipo) {
    const announcement = document.getElementById('winnerAnnouncement');
    announcement.textContent = mensaje;
    announcement.style.display = 'block';
    
    if (tipo === 'danger') {
        announcement.style.background = 'linear-gradient(135deg, #ff0000, #ff6b6b)';
        announcement.style.color = '#fff';
    }
    
    agregarLog('═══════════════════════════════');
    agregarLog(mensaje, tipo);
    agregarLog('═══════════════════════════════');
}

function agregarLog(mensaje, tipo = 'info') {
    const logContent = document.getElementById('logContent');
    const entry = document.createElement('div');
    entry.className = 'log-entry';
    
    if (tipo === 'success') {
        entry.classList.add('log-success');
    } else if (tipo === 'danger') {
        entry.classList.add('log-danger');
    }
    
    entry.textContent = mensaje;
    logContent.appendChild(entry);
    logContent.scrollTop = logContent.scrollHeight;
}

function obtenerColorAtributo(attribute) {
    const colores = {
        'DARK': '#8B008B',
        'LIGHT': '#FFD700',
        'EARTH': '#8B4513',
        'WATER': '#1E90FF',
        'FIRE': '#FF4500',
        'WIND': '#00CED1',
        'DIVINE': '#FFD700'
    };
    return colores[attribute] || '#666';
}