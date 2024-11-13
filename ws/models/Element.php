<?php

require_once("../ws/interfaces/ItoJson.php");
class Element implements ItoJson{
    public $fullname;
    public $desc;
    public $serial_number;
    public $status;
    public $priority;

    public function __construct($fullname, $desc, $serial_number, $status, $priority) {
        $this->fullname = $fullname;
        $this->desc = $desc;
        $this->serial_number = $serial_number;
        $this->status = $status;
        $this->priority = $priority;
    }

	public function setFullname($fullname) {
		$this->fullname = $fullname;
	}

	public function setDesc($desc) {
		$this->desc = $desc;
	}

	public function setSerial_number($serial_number) {
		$this->serial_number = $serial_number;
	}

    public function setStatus($status) {
		$this->status = $status;
	}

    public function setPriority($priority) {
		$this->priority = $priority;
	}
    
    public function getFullName() {
        return $this->fullname;
    }
    public function getDesc(){
        return $this->desc;
    }
    public function getSerial_number() {
        return $this->serial_number;
    }
    public function getStatus() {
        return $this->status;
    }
    public function getPriority() {
        return $this->priority;
    }

    public function toJson(){


        $file = file_get_contents("datos.txt");

        $array = unserialize($file);

        $output = array (
            'content' => $array
        );

        print_r(json_decode(json_encode($output, JSON_PRETTY_PRINT)));

    }
}