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


function returnOk($response, $codigo = 200)
{ 
    if($codigo != 200)
        http_response_code($codigo);
    echo json_encode($response);
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
        $torneo = new Torneo($repositorioJugadores, $tipoTorneo );
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


function getTorneos_POST($id, $fecha, $ronda)
{ 
    try{
        $torneos = Torneo::getTorneos($fecha, $id, $ronda);
        foreach ($torneos as &$torneo) {
            
            $partidos = $torneo['partidos'];
            if( is_string($partidos) )
                $partidos = json_decode( str_replace(',{"id": ]', ']', $partidos));
            else
                $partidos = json_decode($partidos);
            $torneo['partidos'] = $partidos;
        }
        return $torneos;
    }catch(Exception $e){
        returnError( $e->getMessage() );
    }
}