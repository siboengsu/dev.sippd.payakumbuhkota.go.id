<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
	//20-07-2018
require_once APPPATH."/third_party/third_party/PHPWord.php";

class Word extends PHPWord {
public function __construct() {
parent::__construct();
}
}
?>
