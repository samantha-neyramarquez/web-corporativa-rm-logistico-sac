<?php

class Logger
{
    private string $archivo;
    private int $tamMaximo;

    public function __construct(
        string $archivo,
        int $tamMaximo = 5242880 // 5 MB
    ) {

        $this->archivo = $archivo;
        $this->tamMaximo = $tamMaximo;
    }

    /**
     * ============================
     * REGISTRO INFO
     * ============================
     */
    public function info(
        string $ip,
        string $nombre = "",
        string $correo = "",
        string $servicio = "",
        string $detalle = ""
    ): void {

        $this->escribir(
            "INFO",
            $ip,
            $nombre,
            $correo,
            $servicio,
            $detalle
        );

    }

    /**
     * ============================
     * REGISTRO WARNING
     * ============================
     */

    public function warning(
        string $ip,
        string $nombre = "",
        string $correo = "",
        string $servicio = "",
        string $detalle = ""
    ): void {

        $this->escribir(

            "WARNING",

            $ip,

            $nombre,

            $correo,

            $servicio,

            $detalle

        );

    }

    /**
     * ============================
     * REGISTRO ERROR
     * ============================
     */

    public function error(
        string $ip,
        string $nombre = "",
        string $correo = "",
        string $servicio = "",
        string $detalle = ""
    ): void {

        $this->escribir(

            "ERROR",

            $ip,

            $nombre,

            $correo,

            $servicio,

            $detalle

        );

    }

    /**
     * ============================
     * ESCRIBIR LOG
     * ============================
     */

    private function escribir(

        string $nivel,

        string $ip,

        string $nombre,

        string $correo,

        string $servicio,

        string $detalle

    ): void {

        $this->rotarLog();

        $ip = $this->anonimizarIP($ip);

        $correo = $this->anonimizarCorreo($correo);

        $registro = "";

        $registro .= "------------------------------------------\n";

        $registro .= "[" . date("Y-m-d H:i:s") . "]\n";

        $registro .= "Nivel: {$nivel}\n";

        $registro .= "IP: {$ip}\n";

        if ($nombre !== "") {

            $registro .= "Nombre: {$nombre}\n";

        }

        if ($correo !== "") {

            $registro .= "Correo: {$correo}\n";

        }

        if ($servicio !== "") {

            $registro .= "Servicio: {$servicio}\n";

        }

        if ($detalle !== "") {

            $registro .= "Detalle: {$detalle}\n";

        }

        file_put_contents(

            $this->archivo,

            $registro,

            FILE_APPEND | LOCK_EX

        );

        if (file_exists($this->archivo)) {

            @chmod($this->archivo,0640);

        }

    }

    /**
     * ============================
     * ANONIMIZAR IP
     * ============================
     */

    private function anonimizarIP(string $ip): string
    {

        if (

            filter_var(

                $ip,

                FILTER_VALIDATE_IP,

                FILTER_FLAG_IPV4

            )

        ) {

            $partes = explode(".", $ip);

            $partes[2] = "xxx";

            $partes[3] = "xxx";

            return implode(".", $partes);

        }

        return $ip;

    }

    /**
     * ============================
     * ANONIMIZAR CORREO
     * ============================
     */

    private function anonimizarCorreo(string $correo): string
    {

        if ($correo == "") {

            return "";

        }

        $partes = explode("@",$correo);

        if(count($partes)!=2){

            return $correo;

        }

        $usuario = $partes[0];

        $dominio = $partes[1];

        $usuario = substr($usuario,0,2).

            str_repeat("*",max(strlen($usuario)-2,1));

        return $usuario."@".$dominio;

    }

    /**
     * ============================
     * ROTACIÓN AUTOMÁTICA
     * ============================
     */

    private function rotarLog(): void
    {

        if(

            !file_exists($this->archivo)

        ){

            return;

        }

        if(

            filesize($this->archivo)

            <

            $this->tamMaximo

        ){

            return;

        }

        $nuevoNombre = dirname($this->archivo)

            ."/cotizaciones_"

            .date("Ymd_His")

            .".log";

        rename(

            $this->archivo,

            $nuevoNombre

        );

    }

}