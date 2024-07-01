<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;
require_once __DIR__ . '\..\DatabaseConnection.php';
require_once __DIR__ . '\..\src\Torneo.php';
require_once __DIR__ . '\..\src\RepositorioJugadores.php';
require_once __DIR__ . '\..\src\Jugador.php'; 

final class TorneoTest extends TestCase
{
    public function setUp(): void
    {
        # Turn on error reporting
        error_reporting(E_ALL);
        // ...
    }
  

    public function testSimularTorneoVaLidarNumeroDeRondas(): void
    {  
        $datos = '[{ "nombre": "Jugador1", "tipo": "masculino", "habilidad": 75, "fuerza": 72, "velocidad": 75, "tiempo_reaccion": 0 },
        { "nombre": "Jugador2", "tipo": "masculino", "habilidad": 70, "fuerza": 65, "velocidad": 50, "tiempo_reaccion": 0 },
        { "nombre": "Jugador3","tipo": "masculino", "habilidad": 75, "fuerza": 65, "velocidad": 72, "tiempo_reaccion": 0 },
        { "nombre": "Jugador4","tipo": "masculino", "habilidad": 60, "fuerza": 68, "velocidad": 80, "tiempo_reaccion": 0 }]';

        $data = json_decode($datos, true);

        foreach ($data as $jugador) {
            $jugadores[] = new Jugador( $jugador['nombre'],  $jugador['habilidad'], $jugador['tipo'],  $jugador['fuerza'],  $jugador['velocidad'],  $jugador['tiempo_reaccion'] );
        }

        $repositorioJugadores = new RepositorioJugadores();
        foreach ($jugadores as $jugador) {
            $repositorioJugadores->agregarJugador($jugador);
        }

        $torneo = new Torneo($repositorioJugadores, "masculino");
        $torneo->simularTorneo();
        $ganador = $torneo->getGanador();
        $rondas = $torneo->getRondas();       

        $this->assertSame(2, count($rondas));
    }

    public function testSimularTorneoVaLidarGanador(): void
    {  
        $datos = '[{ "nombre": "Jugador1", "tipo": "masculino", "habilidad": 75, "fuerza": 72, "velocidad": 75, "tiempo_reaccion": 100 },
        { "nombre": "Jugador2", "tipo": "masculino", "habilidad": 0, "fuerza": 5, "velocidad": 0, "tiempo_reaccion": 0 },
        { "nombre": "Jugador3","tipo": "masculino", "habilidad": 5, "fuerza": 5, "velocidad": 2, "tiempo_reaccion": 0 },
        { "nombre": "Jugador4","tipo": "masculino", "habilidad": 0, "fuerza": 8, "velocidad": 0, "tiempo_reaccion": 0 }]';

        $data = json_decode($datos, true);

        foreach ($data as $jugador) {
            $jugadores[] = new Jugador( $jugador['nombre'],  $jugador['habilidad'], $jugador['tipo'],  $jugador['fuerza'],  $jugador['velocidad'],  $jugador['tiempo_reaccion'] );
        }

        $repositorioJugadores = new RepositorioJugadores();
        foreach ($jugadores as $jugador) {
            $repositorioJugadores->agregarJugador($jugador);
        }

        $torneo = new Torneo($repositorioJugadores, "masculino");
        $torneo->simularTorneo();
        $ganador = $torneo->getGanador();
        $rondas = $torneo->getRondas();       

        $this->assertSame('Jugador1',  $ganador->getNombre());
    }
    
    public function testGetTorneoGenrarYObtenerPorId(): void
    {  
        $datos = '[{ "nombre": "Jugador1", "tipo": "masculino", "habilidad": 75, "fuerza": 72, "velocidad": 75, "tiempo_reaccion": 0 },
        { "nombre": "Jugador2", "tipo": "masculino", "habilidad": 70, "fuerza": 65, "velocidad": 50, "tiempo_reaccion": 0 },
        { "nombre": "Jugador3","tipo": "masculino", "habilidad": 75, "fuerza": 65, "velocidad": 72, "tiempo_reaccion": 0 },
        { "nombre": "Jugador4","tipo": "masculino", "habilidad": 60, "fuerza": 68, "velocidad": 80, "tiempo_reaccion": 0 }]';

        $data = json_decode($datos, true);

        foreach ($data as $jugador) {
            $jugadores[] = new Jugador( $jugador['nombre'],  $jugador['habilidad'], $jugador['tipo'],  $jugador['fuerza'],  $jugador['velocidad'],  $jugador['tiempo_reaccion'] );
        }

        $repositorioJugadores = new RepositorioJugadores();
        foreach ($jugadores as $jugador) {
            $repositorioJugadores->agregarJugador($jugador);
        }

        $torneo = new Torneo($repositorioJugadores, "masculino");
        $torneo->simularTorneo();
        $ganador = $torneo->getGanador();
        $rondas = $torneo->getRondas();
        $torneoId = $torneo->getTorneoId();

        $gettorneo = Torneo::getTorneos(null, $torneoId, null);

        $this->assertSame(intVal($torneoId), intVal($gettorneo[0]['torneo_id']));
    }

    public function testGetTorneoGenrarYObtenerPorFechas(): void
    {  
        $datos = '[{ "nombre": "Jugador1", "tipo": "masculino", "habilidad": 75, "fuerza": 72, "velocidad": 75, "tiempo_reaccion": 0 },
        { "nombre": "Jugador2", "tipo": "masculino", "habilidad": 70, "fuerza": 65, "velocidad": 50, "tiempo_reaccion": 0 },
        { "nombre": "Jugador3","tipo": "masculino", "habilidad": 75, "fuerza": 65, "velocidad": 72, "tiempo_reaccion": 0 },
        { "nombre": "Jugador4","tipo": "masculino", "habilidad": 60, "fuerza": 68, "velocidad": 80, "tiempo_reaccion": 0 }]';

        $data = json_decode($datos, true);

        foreach ($data as $jugador) {
            $jugadores[] = new Jugador( $jugador['nombre'],  $jugador['habilidad'], $jugador['tipo'],  $jugador['fuerza'],  $jugador['velocidad'],  $jugador['tiempo_reaccion'] );
        }

        $repositorioJugadores = new RepositorioJugadores();
        foreach ($jugadores as $jugador) {
            $repositorioJugadores->agregarJugador($jugador);
        }

        $torneo = new Torneo($repositorioJugadores, "masculino");
        $torneo->simularTorneo();
        $ganador = $torneo->getGanador();
        $rondas = $torneo->getRondas();
        $torneoId = (int)$torneo->getTorneoId();

        $gettorneos = Torneo::getTorneos('2024-07-01', null, null);

        $exist = array_search($torneoId, array_column($gettorneos, 'torneo_id'));

        $this->assertNotFalse($exist, "El torneo con torneo_id $torneoId no se encontró en el array gettorneos");

        $this->assertSame($torneoId, (int)$gettorneos[$exist]['torneo_id'] ); 
    }




    public function testTorneoNoValidoParticipantesDeDiferenteTipo(): void
    {  
        $datos = '[{ "nombre": "Jugador1", "tipo": "masculino", "habilidad": 75, "fuerza": 72, "velocidad": 75, "tiempo_reaccion": 0 },
        { "nombre": "Jugador2", "tipo": "masculino", "habilidad": 70, "fuerza": 65, "velocidad": 50, "tiempo_reaccion": 0 },
        { "nombre": "Jugador3","tipo": "masculino", "habilidad": 75, "fuerza": 65, "velocidad": 72, "tiempo_reaccion": 0 },
        { "nombre": "Jugador4","tipo": "femenino", "habilidad": 60, "fuerza": 68, "velocidad": 80, "tiempo_reaccion": 0 }]';

        $data = json_decode($datos, true);

        foreach ($data as $jugador) {
            $jugadores[] = new Jugador( $jugador['nombre'],  $jugador['habilidad'], $jugador['tipo'],  $jugador['fuerza'],  $jugador['velocidad'],  $jugador['tiempo_reaccion'] );
        }

        $repositorioJugadores = new RepositorioJugadores();
        foreach ($jugadores as $jugador) {
            $repositorioJugadores->agregarJugador($jugador);
        }

        $this->expectException(\Exception::class);

        $torneo = new Torneo($repositorioJugadores, "masculino");
    }
    
}