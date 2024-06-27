<?php

class RepositorioJugadores
{
    private $jugadores;

    public function __construct()
    {
        $this->jugadores = [];
    }

    public function agregarJugador(Jugador $jugador)
    {
        $this->jugadores[] = $jugador;
    }

    public function getAll()
    {
        return $this->jugadores;
    }
}