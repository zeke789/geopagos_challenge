<?php

class Jugador {
    private $nombre;
    private $nivel_habilidad;
    private $tipo;
    private $fuerza;
    private $velocidad;
    private $tiempo_reaccion;

    public function __construct($nombre, $nivel_habilidad, $tipo, $fuerza = 0, $velocidad = 0, $tiempo_reaccion = 0) {
        $this->nombre = $nombre;
        $this->nivel_habilidad = $nivel_habilidad;
        $this->tipo = $tipo;
        $this->fuerza = $fuerza;
        $this->velocidad = $velocidad;
        $this->tiempo_reaccion = $tiempo_reaccion;
    }

    public function getNombre() {
        return $this->nombre;
    }

    public function getNivelHabilidad() {
        return $this->nivel_habilidad;
    }

    public function getTipo() {
        return $this->tipo;
    }

    public function getFuerza() {
        return $this->fuerza;
    }

    public function getVelocidad() {
        return $this->velocidad;
    }

    public function getTiempoReaccion() {
        return $this->tiempo_reaccion;
    }
}