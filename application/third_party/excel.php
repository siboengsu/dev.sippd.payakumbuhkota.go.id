
<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
	//17-07-2018
require_once APPPATH . "/third_party/Classes/PHPExcel.php";
class Excel extends PHPExcel {
    public function __construct() {
        parent::__construct();
    }
}
?>
