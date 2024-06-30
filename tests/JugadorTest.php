<?php declare(strict_types=1);
#require '../vendor/autoload.php';

use PHPUnit\Framework\TestCase;
require_once __DIR__ . '\..\src\Jugador.php';

final class JugadorTest extends TestCase
{
    private $nombre;
    private $habilidad;
    private $fuerza;
    private $tipo;
    private $velocidad;
    private $tiempo_reaccion;
    private $jugadorCls;

    public function setUp(): void {
        parent::setUp();
        # Turn on error reporting
        error_reporting(E_ALL);
        // ...
        $this->nombre = 'Jugador1';
        $this->habilidad = 75;
        $this->fuerza = 72;
        $this->tipo = 'masculino';
        $this->velocidad = 75;
        $this->tiempo_reaccion = 0;

        $this->jugadorCls = new Jugador($this->nombre, $this->habilidad, $this->tipo, $this->fuerza, $this->velocidad, $this->tiempo_reaccion);
    }

    public function testObtenerNombreDelJugador(): void
    {
        $this->assertSame($this->nombre, $this->jugadorCls->getNombre());
    }

    public function testObtenerNivelDeHabilidadDelJugador(): void
    {
        $this->assertSame($this->habilidad, $this->jugadorCls->getNivelHabilidad());
    }

    public function testObtenerELGeneroDelJugador(): void
    {
        $this->assertSame($this->tipo, $this->jugadorCls->getTipo());
    }

    public function testObtenerLaFuerzaDelJugador(): void
    {
        $this->assertSame($this->fuerza, $this->jugadorCls->getFuerza());
    }

    public function testObtenerElTiempoDeReaccionDelJugador(): void
    {
        $this->assertSame($this->tiempo_reaccion, $this->jugadorCls->getTiempoReaccion());
    }
}