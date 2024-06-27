<?php


function esPotenciaDeDos($numero)
{
    return ($numero > 0) && (($numero & ($numero - 1)) == 0);
}

function returnError($mensaje, $codigo = 400)
{
    http_response_code($codigo);
    echo json_encode(['error' => $mensaje]);
    exit;
}

function simularTorneo_POST($tipoTorneo, $jugadores )
{
    // Crear un repositorio de jugadores y agregarlos
    $repositorioJugadores = new RepositorioJugadores();
    foreach ($jugadores as $jugador) {
        $repositorioJugadores->agregarJugador($jugador);
    }

    try {
        // Crear una instancia de Torneo
        $resultados = [];
        $torneo = new Torneo($repositorioJugadores, "masculino");
        $torneo->simularTorneo();
        $ganador = $torneo->getGanador();
        $rondas = $torneo->getRondas();
    

        $resp = [
            'ganador' => $ganador->getNombre(),
            'resultados' => $rondas
        ];

        return $resp;
    } catch (Exception $e) {
        returnError( $e->getMessage() );
    }
}