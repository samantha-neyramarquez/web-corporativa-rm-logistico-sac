<?php

class RateLimiter
{
    private string $archivo;

    private int $maxIntentos;

    private int $ventanaSegundos;

    public function __construct(
        string $archivo,
        int $maxIntentos = 5,
        int $ventanaMinutos = 10
    ) {

        $this->archivo = $archivo;

        $this->maxIntentos = $maxIntentos;

        $this->ventanaSegundos = $ventanaMinutos * 60;

        if (!file_exists($archivo)) {

            file_put_contents($archivo, json_encode([]));

        }

    }

    public function verificar(string $ip, string $correo): bool
    {

        $clave = md5($ip . "|" . strtolower($correo));

        $datos = json_decode(file_get_contents($this->archivo), true);

        if (!$datos) {

            $datos = [];

        }

        $ahora = time();

        if (!isset($datos[$clave])) {

            $datos[$clave] = [];

        }

        // eliminar registros antiguos

        $datos[$clave] = array_filter(

            $datos[$clave],

            fn($t) => ($ahora - $t) < $this->ventanaSegundos

        );

        if (count($datos[$clave]) >= $this->maxIntentos) {

            file_put_contents(

                $this->archivo,

                json_encode($datos, JSON_PRETTY_PRINT),

                LOCK_EX

            );

            return false;

        }

        $datos[$clave][] = $ahora;

        file_put_contents(

            $this->archivo,

            json_encode($datos, JSON_PRETTY_PRINT),

            LOCK_EX

        );

        return true;

    }

}