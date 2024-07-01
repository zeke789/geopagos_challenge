<?php

use function PHPUnit\Framework\throwException;

header('Content-Type: application/json');


require __DIR__ . '/DatabaseConnection.php';

require __DIR__ . '/src/Jugador.php';
require __DIR__ . '/src/RepositorioJugadores.php';
require __DIR__ . '/src/Torneo.php';

require __DIR__ . '/functions.php';

 

// GET RUTA SOLICITADA
$requestUri = $_SERVER['REQUEST_URI'];
$scriptName = $_SERVER['SCRIPT_NAME'];
$base = str_replace(basename($scriptName), "", $scriptName);
$uri = str_replace($base, "", $requestUri);
$uri = parse_url($uri, PHP_URL_PATH);


// RUTAS
switch ($uri) {

    case 'getTorneos':

        if($_SERVER['REQUEST_METHOD'] === 'POST'){

            // GET TORNEOS SEGÚN FILTROS

            $id = isset($_POST['id']) && !empty($_POST['id']) ? $_POST['id'] : null;
            $fecha = isset($_POST['fecha']) && !empty($_POST['fecha']) ? $_POST['fecha'] : null;
            $ronda = isset($_POST['ronda']) && !empty($_POST['ronda']) ? $_POST['ronda'] : null;
            
            $torneos = getTorneos_POST( $id, $fecha, $ronda );
    

            // !VERIFICAR SI NO ENCUENTRA TORNEOS
            if( count($torneos) < 1 )
                returnOk("No se encuentran torneos con los parámetros recibidos", 404);
            
            // RETURN HTTP_CODE 200 Y TORNEOS ENCONTRADOS
            returnOk( $torneos );
        }

        break;

    case 'simularTorneo':

        try {
            // !VERIFICAR PARAM JUGADORES
            if( $jugadoresParam === null )
                returnError( "Debes enviar el parámetro jugadores" );
            
            // PARSE JUGADORES PARAM
            $data = json_decode($jugadoresParam, true);

            // !VERIFICAR POTENCIA DE 2
            if( !esPotenciaDeDos(count($data)) )
                returnError("La cantidad de jugadores debe ser potencia de 2");
            
            // CREAR JUGADORES
            foreach ($data as $jugador) {
                $jugadores[] = new Jugador( $jugador['nombre'],  $jugador['habilidad'], $tipoTorneo,  $jugador['fuerza'],  $jugador['velocidad'],  $jugador['tiempo_reaccion'] );
            }

            // JUGAR TORNEO
            $resultado = simularTorneo_POST($tipoTorneo, $jugadores);
            

            // RETURN HTTP CODE 200 Y RESULTADOS DEL TORNEO
            echo json_encode($resultado);
        } catch (\Exception $e) {
            returnError( $e->getMessage() );
        }
        $tipoTorneo = isset($_POST['tipo']) ? $_POST['tipo'] : null;
        $jugadoresParam = isset($_POST['jugadores']) ? $_POST['jugadores'] : null;
        $jugadores = [];

     
        
        break;
        
    default:
        echo json_encode(["error" => "Ruta no encontrada"]);
        break;
}












/*

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

            break;

        default:
            break;
    }
}*/


