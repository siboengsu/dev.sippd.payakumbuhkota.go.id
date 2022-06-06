<?php
    defined('BASEPATH') OR exit('No direct script access allowed');

    class Import extends CI_Controller 
    {
        function __construct()
        {
            parent::__construct();
    
            $this->sip->is_logged();
    
            $this->KDTAHUN = $this->session->KDTAHUN;
            $this->KDTAHAP = $this->session->KDTAHAP;
    
            $this->thn_hspk = ((int)$this->KDTAHUN + 2000 - 1);
            $this->thn_musrenbang = ((int)$this->KDTAHUN - 1);
            $this->thn_epokir = ((int)$this->KDTAHUN - 1);
        }
    
        public function index()
        {
            $sql = "
            SELECT
                R.ID_MENU,
                R.ID_PARE,
                R.NMMENU,
                R.TIPE
            FROM
                PHPWEBROLE R
                JOIN PHPWEBOTOR O ON R.ID_MENU = O.ID_MENU
            WHERE
                O.GROUPID = ?
                AND (
                    R.KDTAHAP IS NULL
                    OR
                    R.KDTAHAP = ?
                )
            ORDER BY R.ID_MENU ASC";
    
            $data['menu'] = $this->db->query($sql, [$this->session->GROUPID, $this->session->KDTAHAP])->result_array();
    
            echo $this->session->flashdata('msg');
            $this->load->view('v_dashboard', $data);
            
        }
        public function coba()
        {
            $this->load->view('renja/v_renja_import_data');
        }
        public function excel()
        {
            if(isset($_FILES["file"]["name"]))
            {
                $file_tmp = $_FILES['file']['tmp_name'];
                $file_name = $_FILES['file']['name'];
                $file_size =$_FILES['file']['size'];
                $file_type=$_FILES['file']['type'];
                $object = PHPExcel_IOFactory::load($file_tmp);        
                foreach($object->getWorksheetIterator() as $worksheet)
                {
                    $highestRow = $worksheet->getHighestRow();
                    $highestColumn = $worksheet->getHighestColumn();
                    for($row=2; $row<=$highestRow; $row++)
                    {
                        $unit           = $worksheet->getCellByColumnAndRow(0, $row)->getValue();
                        $dafunit        = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
                        $program        = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
                        $kegiatan       = $worksheet->getCellByColumnAndRow(3, $row)->getValue();
                        $subkegiatan    = $worksheet->getCellByColumnAndRow(4, $row)->getValue();
                        $KDTAHUN        = $worksheet->getCellByColumnAndRow(5, $row)->getValue();
                        $KDTAHAP        = $worksheet->getCellByColumnAndRow(6, $row)->getValue();
                        $INDIKATOR      = $worksheet->getCellByColumnAndRow(8, $row)->getValue();
                        $SASARAN        = $worksheet->getCellByColumnAndRow(9, $row)->getValue(); 
                        $KEGSUBKEG      = $worksheet->getCellByColumnAndRow(10, $row)->getValue();
                        $TARGETSUBKEG   = $worksheet->getCellByColumnAndRow(11, $row)->getValue();
                        $PAGUTIF        = $worksheet->getCellByColumnAndRow(15, $row)->getValue();
                        $PAGUTPLUS      = $worksheet->getCellByColumnAndRow(16, $row)->getValue();
                        $SUBKEGRKPDKEY  = $worksheet->getCellByColumnAndRow(17, $row)->getValue();
                        $KEGRKPDKEY     = $worksheet->getCellByColumnAndRow(18, $row)->getValue();
                        $PGRMRKPDKEY    = $worksheet->getCellByColumnAndRow(19, $row)->getValue();
                        $UNITKEY        = $worksheet->getCellByColumnAndRow(20, $row)->getValue();
                        $JENIS          = $worksheet->getCellByColumnAndRow(21, $row)->getValue();
                        if($JENIS == 'PRO')
                        {
                            $TOLOKUR        = $worksheet->getCellByColumnAndRow(12, $row)->getValue();
                            $TARGET         = $worksheet->getCellByColumnAndRow(13, $row)->getValue();
                        }
                        if($JENIS == 'PRO')
                        {
                            $cekprog = $this->db->query("SELECT PGRMRKPDKEY FROM PGRRKPD WHERE PGRMRKPDKEY = '$PGRMRKPDKEY' AND UNITKEY = '$UNITKEY' AND KDTAHAP = $this->KDTAHAP AND KDTAHUN = $this->KDTAHUN")->row_array();
                            if(count($cekprog) > 0)
                            {
                                $message ['msg'] = '<script> alert("Data duplicate");</script>';               
                                $this->session->set_flashdata($message);
                                continue;
                            }else{
                                $data[] = array(
                                    'UNITKEY'          =>$UNITKEY,
                                    'KDTAHUN'          =>$this->KDTAHUN,
                                    'KDTAHAP'          =>$this->KDTAHAP,
                                    'PGRMRKPDKEY'      =>"$PGRMRKPDKEY",
                                    'SASARAN'          =>"$SASARAN",
                                    'INDIKATOR'        =>"$INDIKATOR",
                                    'KET'              =>NULL,
                                    'TGLVALID'         =>NULL,
                                    'IDSAS'            =>NULL,
                                    'PRIONASKEY'       =>NULL,
                                    'PRIOPPASKEY'      =>NULL,
                                    'TOLOKUR'          =>"$TOLOKUR",
                                    'TARGET'           =>"$TARGET"
                                );
                            }
                        }
                            
                        if($JENIS == 'KEG')
                        {
                            $cekprog = $this->db->query("SELECT KEGRKPDKEY FROM KEGRKPD WHERE KEGRKPDKEY = '$KEGRKPDKEY' AND UNITKEY = '$UNITKEY' AND KDTAHAP = $this->KDTAHAP AND KDTAHUN = $this->KDTAHUN")->row_array();
                            if(count($cekprog) > 0)
                            {
                                $message ['msg'] = '<script> alert("Data duplicate");</script>';               
                                $this->session->set_flashdata($message);
                                continue;
                            }else{
                                $dataK[] = array
                                (
                                    'UNITKEY'           =>$UNITKEY,
                                    'KDTAHUN'           =>$this->KDTAHUN,
                                    'KDTAHAP'           =>$this->KDTAHAP,
                                    'KEGRKPDKEY'        =>"$KEGRKPDKEY",
                                    'UNITUSUL'          =>$UNITKEY,
                                    'PRIOPPASKEY'       =>NULL,
                                    'IDSAS'             =>NULL,
                                    'PGRMRKPDKEY'       =>"$PGRMRKPDKEY",
                                    'KDSIFAT'           =>1,
                                    'TARGET'            =>NULL,
                                    'KET'               =>"$KEGSUBKEG",
                                    'IDDESA'            =>NULL,
                                    'LOKASI'            =>NULL,
                                    'TARGETSEN'         =>NULL,
                                    'TARGETTIF'         =>NULL,
                                    'KUANTITATIF'       =>"$TARGETSUBKEG",
                                    'SATUAN'            =>NULL,
                                    'PAGUPLUS'          =>"$PAGUTPLUS",
                                    'PAGUTIF'           =>"$PAGUTIF",
                                    'TGLVALID'          =>NULL,
                                    'IS_RES_GENDER'     =>NULL,
                                    'PAGUTIFDPA'        =>0
                                );

                                $dataRK[] = array
                                (
                                    'KDJKK'             =>'00',
                                    'KEGRKPDKEY'        =>"$KEGRKPDKEY",
                                    'KDTAHAP'           =>$this->KDTAHAP,
                                    'KDTAHUN'           =>$this->KDTAHUN,
                                    'UNITKEY'           =>$UNITKEY,
                                    'TOLOKUR'           =>$TOLOKUR,
                                    'TARGET'            =>"$TARGET",
                                    'TARGET1'           =>NULL,
                                    'TARGETMIN1'        =>NULL
                                );
                                
                                $dataRK[] = array
                                (
                                    'KDJKK'             =>'01',
                                    'KEGRKPDKEY'        =>"$KEGRKPDKEY",
                                    'KDTAHAP'           =>$this->KDTAHAP,
                                    'KDTAHUN'           =>$this->KDTAHUN,
                                    'UNITKEY'           =>$UNITKEY,
                                    'TOLOKUR'           =>NULL,
                                    'TARGET'            =>NULL,
                                    'TARGET1'           =>NULL,
                                    'TARGETMIN1'        =>NULL
                                );

                                $dataRK[] = array
                                (
                                    'KDJKK'             =>'02',
                                    'KEGRKPDKEY'        =>"$KEGRKPDKEY",
                                    'KDTAHAP'           =>$this->KDTAHAP,
                                    'KDTAHUN'           =>$this->KDTAHUN,
                                    'UNITKEY'           =>$UNITKEY,
                                    'TOLOKUR'           =>"$KEGSUBKEG",
                                    'TARGET'            =>"$TARGETSUBKEG",
                                    'TARGET1'           =>NULL,
                                    'TARGETMIN1'        =>NULL
                                );
                            }
                        }
                        
                        if($JENIS == 'SUBKEG')
                        {
                            $cekprog = $this->db->query("SELECT SUBKEGRKPDKEY FROM SUBKEGRKPD WHERE SUBKEGRKPDKEY = '$SUBKEGRKPDKEY' AND UNITKEY = '$UNITKEY' AND KDTAHAP = $this->KDTAHAP AND KDTAHUN = $this->KDTAHUN")->row_array();
                            if(count($cekprog) > 0)
                            {
                                $message ['msg'] = '<script> alert("Data duplicate");</script>';               
                                $this->session->set_flashdata($message);
                                continue;
                            }else{
                                $dataSK[] = array
                                (
                                    'UNITKEY'           =>$UNITKEY ,
                                    'KDTAHUN'           =>$this->KDTAHUN,
                                    'KDTAHAP'           =>$this->KDTAHAP,
                                    'SUBKEGRKPDKEY'     =>"$SUBKEGRKPDKEY",
                                    'KEGRKPDKEY'        =>"$KEGRKPDKEY",
                                    'KET'               =>"$KEGSUBKEG",
                                    'LOKASI'            =>NULL,
                                    'TARGET'            =>"$TARGETSUBKEG",
                                    'SATUAN'            =>NULL,
                                    'PAGUPLUS'          =>"$PAGUTPLUS",
                                    'PAGUTIF'           =>"$PAGUTIF",
                                    'PAGUTIFDPA'        =>0,
                                    'TGLVALID'          =>NULL,
                                    'IS_RES_GENDER'     =>NULL,
                                    'KDSIFAT'           =>1,
                                    'IS_SPM'            =>NULL
                                );
                                                            
                                $dataRSK[] = array
                                (
                                    'KDJKK'             =>'00',
                                    'SUBKEGRKPDKEY'     =>"$SUBKEGRKPDKEY",
                                    'KDTAHAP'           =>$this->KDTAHAP,
                                    'KDTAHUN'           =>$this->KDTAHUN,
                                    'UNITKEY'           =>$UNITKEY,
                                    'TOLOKUR'           =>$TOLOKUR,
                                    'TARGET'            =>"$TARGET",
                                    'TARGET1'           =>NULL,
                                    'TARGETMIN1'        =>NULL
                                );

                                $dataRSK[] = array
                                (
                                    'KDJKK'             =>'01',
                                    'SUBKEGRKPDKEY'     =>"$SUBKEGRKPDKEY",
                                    'KDTAHAP'           =>$this->KDTAHAP,
                                    'KDTAHUN'           =>$this->KDTAHUN,
                                    'UNITKEY'           =>$UNITKEY,
                                    'TOLOKUR'           =>NULL,
                                    'TARGET'            =>preg_replace("/[^0-9]/", "",$PAGUTIF),
                                    'TARGET1'           =>NULL,
                                    'TARGETMIN1'        =>NULL
                                );

                                $dataRSK[] = array
                                (
                                    'KDJKK'             =>'02',
                                    'SUBKEGRKPDKEY'     =>"$SUBKEGRKPDKEY",
                                    'KDTAHAP'           =>$this->KDTAHAP,
                                    'KDTAHUN'           =>$this->KDTAHUN,
                                    'UNITKEY'           =>$UNITKEY,
                                    'TOLOKUR'           =>"$KEGSUBKEG",
                                    'TARGET'            =>"$TARGETSUBKEG",
                                    'TARGET1'           =>NULL,
                                    'TARGETMIN1'        =>NULL
                                );
                            }
                        }
                    } 
                    if ($data > 0) {$this->db->insert_batch('PGRRKPD', $data);}
                    if ($dataK > 0) {$this->db->insert_batch('KEGRKPD', $dataK);}
                    if ($dataRK > 0) {$this->db->insert_batch('KINKEGRKPD', $dataRK);}
                    if ($dataSK > 0) {$this->db->insert_batch('SUBKEGRKPD', $dataSK);}
                    if ($dataRSK > 0) {$this->db->insert_batch('SUBKINKEGRKPD', $dataRSK);}
                }
                $message ['msg'] = '<script> alert("Data berhasil diimport");</script>';               
                $this->session->set_flashdata($message);
                redirect('import/index');
            }
            else
            {
                $message ['msg'] = '<script> alert("Data gagal diimport");</script>';   
                $this->session->set_flashdata($message);
                redirect('import/index');
            }
        }
    }