<?php
header('Content-Type: application/json');



require __DIR__ . '/DatabaseConnection.php';

require __DIR__ . '/src/Jugador.php';
require __DIR__ . '/src/RepositorioJugadores.php';
require __DIR__ . '/src/Torneo.php';

require __DIR__ . '/functions.php';




// Obtener los parámetros de la solicitud
$tipoTorneo = isset($_POST['tipo']) ? $_POST['tipo'] : null;
$jugadoresParam = isset($_POST['jugadores']) ? $_POST['jugadores'] : null;
$funcion = isset($_POST['function']) ? $_POST['function'] : null;




if($funcion != null){
    
    switch ($funcion)
    {
        // SIMULAR TORNEO
        case 'simularTorneo':
            if($tipoTorneo !== "masculino" && $tipoTorneo !== "femenino")
                returnError( "Debe seleccionar tipo de torneo masculino o femenino" );

            if( $jugadoresParam != null){
                $jugadores = [];
                $data = json_decode($jugadoresParam, true);
                if( !esPotenciaDeDos(count($data)) )
                    returnError("La cantidad de jugadores debe ser potencia de 2");
                
                foreach ($data as $jugador) {
                    $jugadores[] = new Jugador( $jugador['nombre'],  $jugador['habilidad'], $tipoTorneo,  $jugador['fuerza'],  $jugador['velocidad'],  $jugador['tiempo_reaccion'] );
                }
                $resultado = simularTorneo_POST($tipoTorneo, $jugadores);
                
                echo json_encode($resultado);
            }
            break;

        // GET TORNEOS
        case 'getTorneos':
            $id = isset($_POST['id']) ? $_POST['id'] : null;
            $fecha = isset($_POST['fecha']) ? $_POST['fecha'] : null;
            $ronda = isset($_POST['ronda']) ? $_POST['ronda'] : null;
            
            try{
                $torneos = Torneo::getTorneos($fecha, $id, $ronda);
                foreach ($torneos as &$torneo) {
                    $partidos = json_decode($torneo['partidos']);
                    $torneo['partidos'] = $partidos;
                }
                echo json_encode($torneos);
            }catch(Exception $e){
                returnError( $e->getMessage() );
            }
            break;

        default:
            break;
    }
}


