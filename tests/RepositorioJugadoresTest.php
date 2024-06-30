<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;
require_once __DIR__ . '\..\src\RepositorioJugadores.php';
require_once __DIR__ . '\..\src\Jugador.php'; 

final class RepositorioJugadoresTest extends TestCase
{
    public function setUp(): void
    {
        # Turn on error reporting
        error_reporting(E_ALL);
        // ...
    }
    
    public function testObtenerTodosLosRegistrosDeJugadores(): void
    {
        $datos = '[{ "nombre": "Jugador1", "habilidad": 75, "fuerza": 72, "velocidad": 75, "tiempo_reaccion": 0 },
        { "nombre": "Jugador2", "habilidad": 70, "fuerza": 65, "velocidad": 50, "tiempo_reaccion": 0 },
        { "nombre": "Jugador3", "habilidad": 75, "fuerza": 65, "velocidad": 72, "tiempo_reaccion": 0 },
        { "nombre": "Jugador4", "habilidad": 60, "fuerza": 68, "velocidad": 80, "tiempo_reaccion": 0 }]';

        $data = json_decode($datos, true);

        foreach ($data as $jugador) {
            $jugadores[] = new Jugador( $jugador['nombre'],  $jugador['habilidad'], 'masculino',  $jugador['fuerza'],  $jugador['velocidad'],  $jugador['tiempo_reaccion'] );
        }

        $repositorioJugadores = new RepositorioJugadores();
        foreach ($jugadores as $jugador) {
            $repositorioJugadores->agregarJugador($jugador);
        }

        $this->assertSame(4, count($repositorioJugadores->getAll()));
    }

    public function testAgregarJugadorAlRepositorioDeJugadores(): void
    {
        $nombre = 'Jugador1';
        $habilidad = 75;
        $fuerza = 72;
        $tipo = 'masculino';
        $velocidad = 75;
        $tiempo_reaccion = 0; 

        $jugador = new Jugador($nombre,  $habilidad, $tipo,  $fuerza,  $velocidad,  $tiempo_reaccion );
     
        $repositorioJugadores = new RepositorioJugadores();
        $repositorioJugadores->agregarJugador($jugador);
        
        $jugadores = $repositorioJugadores->getAll();
        $jugador = $jugadores[0];

        $this->assertSame($nombre, $jugador->getNombre());
    }
}