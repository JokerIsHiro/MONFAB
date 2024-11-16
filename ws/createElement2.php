<?php

require "models/Element.php";
require "connection.php";

$elemento = new Element();

echo $elemento->insert($conn);
