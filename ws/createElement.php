<?php

require_once("models/Element.php");
require_once("interfaces/ItoJson.php");

$fullname = $_POST["fullname"];
$desc = $_POST["desc"];
$serial_number = $_POST["serial"];
$status = isset( $_POST["status"] ) ? 1: 0;
$prio = $_POST["prio"];

$elemento = new Element($fullname, $desc,$serial_number,$status,$prio);

$newFile = fopen("datos.txt","a+") or die("Esto no se abre");

$elementoSerial = serialize($elemento);

if(!empty($_POST["fullname"]) && !empty($_POST["desc"]) && !empty($_POST["serial"]) && !empty($_POST["prio"])){
    file_put_contents("datos.txt", $elementoSerial,  FILE_APPEND);
}

$elemento->toJson();


