<?php

function microtime_float() {
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
}

function validateDate($unaFecha) {
        if(sizeof(explode("-", $unaFecha)) == 3) {
            $dia = explode("-", $unaFecha)[0];
            $mes = explode("-", $unaFecha)[1];
            $ano = explode("-", $unaFecha)[2];
            if(($dia !== "") && ($mes !== "") && ($ano !== "") && (strlen($ano) === 4)) {
                return checkdate($mes, $dia, $ano);
            }
        }
        return false;
    }
