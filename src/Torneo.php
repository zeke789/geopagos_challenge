<?php

class Torneo {
    private $repositorioJugadores;
    private $tipo;
    private $ganador;
    private $rondas;
    private $torneoId;
    private $pdo;

    public function __construct(?RepositorioJugadores $repositorioJugadores, $tipo, $fecha = null)
    {
        $this->tipo = $tipo;
        $this->ganador = null;

        $database = new DatabaseConnection();
        $pdo = $database->connect();
        $this->pdo = $pdo;
        if($pdo == null)
            throw new Exception("No se puede conectar con la base de datos");

        if( $repositorioJugadores != null ){
            //crear id de torneo
            $date = date( 'Y/m/d', time() );
            $sql = "INSERT INTO torneos (fecha) VALUES (:fecha)";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(":fecha", $date);
            $stmt->execute();
            $this->torneoId = $pdo->lastInsertId();
    
            // Validar que los jugadores sean del tipo correcto
            foreach ($repositorioJugadores->getAll() as $jugador) {
                if ($jugador->getTipo() !== $tipo)
                    throw new Exception("Todos los jugadores deben ser del tipo " . $tipo);
            }
            $this->repositorioJugadores = $repositorioJugadores;
        }
    }


    public function simularTorneo()
    {
        $jugadores = $this->repositorioJugadores->getAll();
        $rondaNum = 1;
        // Simular el torneo hasta que quede un único ganador
        while (count($jugadores) > 1) {
            // Actualizar jugadores para la siguiente ronda
            $ronda = $this->jugarRonda($jugadores, $rondaNum);
            $jugadores = $ronda[0];
            $this->rondas[] = $ronda[1];
            $rondaNum++;
           # echo Fin de la ronda<br>";
        }
        $this->ganador = $jugadores[0];
        $this->guardarTorneo();
    }

    

    private function jugarRonda($jugadores,$rondaNum)
    {
        // Simular los partidos de la ronda actual y retornar ganadores,partidos
        $ganadores = [];
        $partidos = [];
        for ($i = 0; $i < count($jugadores); $i += 2) {
            if ($i + 1 < count($jugadores)) {
                $jugador1 = $jugadores[$i];
                $jugador2 = $jugadores[$i + 1];                    
                // Simular el partido entre jugador1 y jugador2
                $ganador = $this->simularPartido($jugador1, $jugador2);
                $partidoInfo = $jugador1->getNombre() . " VS. " . $jugador2->getNombre() . ' - Ganador: ' . $ganador->getNombre();
                $ganadores[] = $ganador;
                $partidos[] = $partidoInfo;

                $this->guardarPartido($partidoInfo, $rondaNum, $ganador->getNombre() );
        
                //guardar en tabla partidos
                /*
                    id
                    info => $partido
                    torneo_id


                */
                // fin ronda
            }
        }
        return [$ganadores , $partidos];
    }

    
    public function simularPartido($jugador1, $jugador2)
    {
        return $this->calcularGanador($jugador1, $jugador2);
    }

    private function calcularGanador($jugador1, $jugador2)
    {
        $habilidadTotalJugador1 = $jugador1->getNivelHabilidad();
        $habilidadTotalJugador2 = $jugador2->getNivelHabilidad();

        if ($this->tipo === "masculino") {
            // En el torneo masculino, se considera fuerza y velocidad
            $habilidadTotalJugador1 += $jugador1->getFuerza() + $jugador1->getVelocidad();
            $habilidadTotalJugador2 += $jugador2->getFuerza() + $jugador2->getVelocidad();
        } elseif ($this->tipo === "femenino") {
            $habilidadTotalJugador1 += $jugador1->getTiempoReaccion();
            $habilidadTotalJugador2 += $jugador2->getTiempoReaccion();
        }

        // Simulación del enfrentamiento, factor suerte
        $suerte1 = rand(0, 20);
        $suerte2 = rand(0, 20);

        // Suma de habilidad total más suerte
        $puntajeJugador1 = $habilidadTotalJugador1 + $suerte1;
        $puntajeJugador2 = $habilidadTotalJugador2 + $suerte2;

        // Determinar al ganador
        if ($puntajeJugador1 > $puntajeJugador2) {
            #echo "{$jugador1->getNombre()} VS {$jugador2->getNombre()} - Ganador: {$jugador1->getNombre()}<br>";
            return $jugador1;
        } else {
            #echo "{$jugador1->getNombre()} VS {$jugador2->getNombre()} - Ganador: {$jugador2->getNombre()}<br>";
            return $jugador2;
        }
    }

    public function getGanador()
    {
        return $this->ganador;
    }

    public function getRondas()
    {
        $return = [];
        foreach($this->rondas as $k => $ronda){
            
            if( count($this->rondas) === $k+1 )
                $rondaNombre = "Ronda " . $k+1 . " (Final)";
            else
                $rondaNombre = "Ronda " . $k+1;

            $return[$rondaNombre] = [];

            foreach( $ronda as $k2 => $partido ){
                
                if( count($this->rondas) === $k+1 )
                    $partidoNombre = "Partido Final";
                else
                    $partidoNombre = "Partido " . $k2+1;

                $return[$rondaNombre][$partidoNombre]  = $partido;
            }
        }
        return $return;
    }

    public static function getTorneos($fecha = null, $id=null, $ronda = null )
    {

        $database = new DatabaseConnection();
        $pdo = $database->connect();
        if($pdo == null)
            throw new Exception("No se puede conectar con la base de datos");

        $sql = "SELECT 
            t.id AS torneo_id, 
            t.ganador, 
            CONCAT('[', GROUP_CONCAT(
                JSON_OBJECT(
                    'id', p.id, 
                    'ganador', p.ganador, 
                    'info', p.info,
                    'ronda', p.ronda
                ) SEPARATOR ','), ']') AS partidos
            FROM 
                Torneos t
            LEFT JOIN 
                Partidos p ON t.id = p.torneo_id";

        if ($id !== null)
            $sql .= " WHERE t.fecha = :fecha";

        if ($fecha !== null)
            $sql .= " WHERE t.fecha = :fecha";

        if ($ronda !== null)
            $sql .= " WHERE p.ronda = :ronda";


        $sql .= " GROUP BY t.id, t.ganador;";
    
        $stmt = $pdo->prepare($sql);

        if ($id !== null) 
            $stmt->bindParam(':id', $id);
        if ($fecha !== null) 
            $stmt->bindParam(':fecha', $fecha);
        if ($ronda !== null) 
            $stmt->bindParam(':ronda', $ronda);

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC); 
    }
    
    private function guardarTorneo()
    {
        $sql = "UPDATE torneos SET ganador = :ganador WHERE id = :torneoId";
        $stmt = $this->pdo->prepare($sql);
        $ganador =  $this->ganador->getNombre();
        $stmt->bindParam(":ganador", $ganador );
        $stmt->bindParam(":torneoId", $this->torneoId);
        return $stmt->execute(); 
    }

    private function guardarPartido($info, $ronda, $ganador)
    {
        $sql = "INSERT INTO partidos (torneo_id, info, ganador, ronda) VALUES (:torneo_id, :info, :ganador, :ronda)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(":torneo_id", $this->torneoId);
        $stmt->bindParam(":info", $info);
        $stmt->bindParam(":ganador", $ganador);
        $stmt->bindParam(":ronda", $ronda);        
        if($ganador != null)
            $stmt->bindParam(":ganador", $ganador);
        return $stmt->execute();      
    }
}
?>
