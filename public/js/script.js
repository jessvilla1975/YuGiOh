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