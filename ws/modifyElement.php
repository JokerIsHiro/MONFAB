<?php

require "connection.php";
require "models/Element.php";

$elemento = new Element();

echo $elemento->update($conn);


