<?php

define("RECEPCION_ESTADO_ENVIADO",  3);
define("RECEPCION_ESTADO_RECIBIDO", 4);

define("PIEZA_ESTADO_EN_GESTION",             1);
define("PIEZA_ESTADO_EN_DISTRIBUCION",        2);
define("PIEZA_ESTADO_EN_TRNSITO",             15);
define("PIEZA_ESTADO_RECIBIDA",               3);
define("PIEZA_ESTADO_NO_EXISTE_NUMERO",       4);
define("PIEZA_ESTADO_NO_EXISTE_DIRECCION",    5);
define("PIEZA_ESTADO_DESTINO_DESCONOCIDO",    6);
define("PIEZA_ESTADO_DOMICILIO_ABANDONADO",   7);
define("PIEZA_ESTADO_DOMICILIO_INSUFICIENTE", 8);
define("PIEZA_ESTADO_SE_MUDO",                9);
define("PIEZA_ESTADO_FALLECIO",               10);
define("PIEZA_ESTADO_SE_NIEGA_A_RECIBIR",     11);
define("PIEZA_ESTADO_OTRO",                   12);
define("PIEZA_ESTADO_ENTREGADA",              13);
define("PIEZA_ESTADO_NO_RESPONDE",            14);

define("PIEZA_TIPO_SIMPLE", 1);
define("PIEZA_TIPO_NORMAL", 2);