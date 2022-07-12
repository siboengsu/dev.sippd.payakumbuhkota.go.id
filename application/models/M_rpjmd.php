<?php
class M_rpjmd extends CI_Model {

	private $KDTAHUN = NULL;
	private $KDTAHAP = NULL;
	private $USERID = NULL;

	function __construct()
	{
		$this->KDTAHUN = $this->session->KDTAHUN;
		$this->KDTAHAP = $this->session->KDTAHAP;
		$this->USERID = $this->session->USERID;
	}

	public function getTahapan(){
		$tahapan = $this->db->get('tbl_TAHAPAN');
		return $tahapan;
	}

	public function getSubTahap(){
		$subtahap = $this->db->get('tbl_SUBTAHAPAN');
		return $subtahap;
	}

	public function getJadwal($filter = NULL, $offset = NULL, $count = FALSE){ 
		if($count){
			return $this->db->query("SELECT COUNT(ID) AS TOTAL_ROW FROM tbl_JADWAL WHERE 1=1 {$filter} ");
		}else{
		if($offset !== NULL){
			$page = $offset[1];
			$limit = $offset[0];
			$where = "WHERE X.ROWNUM BETWEEN (($page - 1) * $limit) + 1 AND $limit * ($page)";
		}
		return $this->db->query("SELECT * FROM ( SELECT ROW_NUMBER() OVER(ORDER BY J.ID ASC) AS ROWNUM, J.ID, ST.SUBTAHAPAN, J.PERIODE_AWAL, J.PERIODE_AKHIR, J.JADWAL_AWAL, J.JADWAL_AKHIR FROM tbl_JADWAL J 
		LEFT JOIN tbl_SUBTAHAPAN ST ON J.ID_SUBTAHAPAN = ST.ID
		WHERE 1=1 {$filter}) X");}
	}

	public function addJadwal($set = []){
		$this->db->insert('tbl_JADWAL', $set);
		return $this->db->affected_rows();
	}

	public function updateJadwal($where, $set = []){
		$this->db->where($where);
	  	$this->db->update('tbl_JADWAL', $set);
	  	return $this->db->affected_rows();
	}

	public function deleteJadwal($idjadwal){
		$this->db->query("DELETE FROM tbl_JADWAL WHERE ID IN ?",[$idjadwal]);
	}

    public function getVisi($filter = NULL, $offset = NULL, $count = FALSE){ 
		if($count){
			return $this->db->query("SELECT COUNT(IDVISI) AS TOTAL_ROW FROM VISI WHERE 1=1 {$filter} ");
		}else{
		if($offset !== NULL){
			$page = $offset[1];
			$limit = $offset[0];
			$where = "WHERE X.ROWNUM BETWEEN (($page - 1) * $limit) + 1 AND $limit * ($page)";
		}
		return $this->db->query("SELECT * FROM ( SELECT ROW_NUMBER() OVER(ORDER BY IDVISI ASC) AS ROWNUM, IDVISI, NMVISI FROM VISI WHERE 1=1 {$filter}) X");}
	}

	public function addVisi($set = []){
		$this->db->insert('VISI', $set);
		return $this->db->affected_rows();
	}

	public function updateVisi($where, $set = []){
	  	$this->db->where($where);
		$this->db->update('VISI', $set);
		return $this->db->affected_rows();
	}

	public function getMisi($filter = NULL, $offset = NULL, $count = FALSE){
		if($count){
			return $this->db->query("SELECT COUNT(MISIKEY) AS TOTAL_ROW FROM MISI WHERE 1=1 {$filter} ");
		}else{
		if($offset !== NULL){
			$page = $offset[1];
			$limit = $offset[0];
			$where = "WHERE X.ROWNUM BETWEEN (($page - 1) * $limit) + 1 AND $limit * ($page)";
		}
		return $this->db->query("
			SELECT * FROM ( SELECT ROW_NUMBER() OVER(ORDER BY MISIKEY ASC) AS ROWNUM, MISIKEY, NOMISI, URAIMISI FROM MISI WHERE 1=1 {$filter}) X");}
	}

	public function addMisi($set = []){
		$this->db->insert('MISI', $set);
		return $this->db->affected_rows();
	}

	public function updateMisi($where, $set = []){
	  	$this->db->where($where);
		$this->db->update('MISI', $set);
		return $this->db->affected_rows();
	}

	public function deleteMisi($misikey){
		$this->db->query("DELETE FROM MISI WHERE MISIKEY IN ?",[$misikey]);
	}

	public function getTujuan($filter = NULL, $offset = NULL, $count = FALSE){
		if($count){
			return $this->db->query("SELECT COUNT(TUJUKEY) AS TOTAL_ROW FROM TUJUAN WHERE 1=1 {$filter} ");
		}else{
		if($offset !== NULL){
			$page = $offset[1];
			$limit = $offset[0];
			$where = "WHERE X.ROWNUM BETWEEN (($page - 1) * $limit) + 1 AND $limit * ($page)";
		}
		return $this->db->query("SELECT * FROM ( SELECT ROW_NUMBER() OVER(ORDER BY TUJUKEY ASC) AS ROWNUM, TUJUKEY, NOTUJU, URAITUJU FROM TUJUAN WHERE 1=1 {$filter}) X");}
	}

	public function addTujuan($set = []){
		$this->db->insert('TUJUAN', $set);
		return $this->db->affected_rows();
	}

	public function updateTujuan($where, $set = []){
	  	$this->db->where($where);
		$this->db->update('TUJUAN', $set);
		return $this->db->affected_rows();
	}

	public function deleteTujuan($tujukey){
		$this->db->query("DELETE FROM TUJUAN WHERE TUJUKEY IN ?",[$tujukey]);
	}

	public function getSasaran($filter = NULL, $offset = NULL, $count = FALSE){
		if($count){
			return $this->db->query("SELECT COUNT(ID) AS TOTAL_ROW FROM tbl_SASARAN WHERE 1=1 {$filter} ");
		}else{
		if($offset !== NULL){
			$page = $offset[1];
			$limit = $offset[0];
			$where = "WHERE X.ROWNUM BETWEEN (($page - 1) * $limit) + 1 AND $limit * ($page)";
		}
		return $this->db->query("SELECT * FROM ( SELECT ROW_NUMBER() OVER(ORDER BY ID ASC) AS ROWNUM, ID, NOSASARAN, SASARAN, INDIKATOR FROM tbl_SASARAN WHERE 1=1 {$filter}) X");}
	}

	public function getSubSasaran($filter = NULL, $offset = NULL, $count = FALSE){
		if($count){
			return $this->db->query("SELECT COUNT(ID_SASARAN) AS TOTAL_ROW FROM tbl_SUBSASARAN WHERE 1=1 {$filter} ");
		}else{
		if($offset !== NULL){
			$page = $offset[1];
			$limit = $offset[0];
			$where = "WHERE X.ROWNUM BETWEEN (($page - 1) * $limit) + 1 AND $limit * ($page)";
		}
		return $this->db->query("SELECT * FROM ( SELECT ROW_NUMBER() OVER(ORDER BY ID_SASARAN ASC) AS ROWNUM, ID_SASARAN, TAHUN, TARGET, SATUAN FROM tbl_SUBSASARAN WHERE 1=1 {$filter}) X");}
	}

	public function addSasaran($set = []){
		$this->db->insert('tbl_SASARAN', $set);
		return $this->db->affected_rows();
	}

	public function updateSasaran($where, $set = []){
		$this->db->where($where);
		$this->db->update('tbl_SASARAN', $set);
		return $this->db->affected_rows();
	}

	public function addSubSasaran($subset = []){
		$this->db->insert('tbl_SUBSASARAN', $subset);
		return $this->db->affected_rows();
	}

	public function updateSubsasaran($where, $subset = []){
		$this->db->where($where);
		$this->db->update('tbl_SUBSASARAN', $subset);
		return $this->db->affected_rows();
	}

	public function deleteSasaran($idsasaran){
		$this->db->query("DELETE FROM tbl_SASARAN WHERE ID IN ?",[$idsasaran]);
		$this->db->query("DELETE FROM tbl_SUBSASARAN WHERE ID_SASARAN IN ?",[$idsasaran]);
	}

	public function getProgram($filter = NULL, $offset = NULL, $count = FALSE){
		if($count){ 
			return $this->db->query("SELECT COUNT(ID) AS TOTAL_ROW FROM tbl_PROGRAM WHERE 1=1 {$filter} ");
		}else{
		if($offset !== NULL){
			$page = $offset[1];
			$limit = $offset[0];
			$where = "WHERE X.ROWNUM BETWEEN (($page - 1) * $limit) + 1 AND $limit * ($page)";
		}
		return $this->db->query("
			SELECT * FROM ( SELECT ROW_NUMBER() OVER(ORDER BY P.ID ASC) AS ROWNUM, P.ID, D.NMUNIT, MP.NMPRGRM,P.INDIKATOR FROM tbl_PROGRAM P
			LEFT JOIN DAFTUNIT D ON P.UNITKEY = D.UNITKEY
			LEFT JOIN MPGRMRKPD MP ON P.PGRMRKPDKEY = MP.PGRMRKPDKEY
			WHERE KDTAHUN = 22 {$filter}) X");}
	}

	public function addProgram($set = []){
		$this->db->insert('tbl_PROGRAM', $set);
		return $this->db->affected_rows();
	}

	public function addSubProgram($subset = []){
		$this->db->insert('tbl_SUBPROGRAM', $subset);
		return $this->db->affected_rows();
	}

	public function getSubProgram($filter = NULL, $offset = NULL, $count = FALSE){
		if($count){ 
			return $this->db->query("SELECT COUNT(ID_PROGRAM) AS TOTAL_ROW FROM tbl_SUBPROGRAM WHERE 1=1 {$filter} ");
		}else{
		if($offset !== NULL){
			$page = $offset[1];
			$limit = $offset[0];
			$where = "WHERE X.ROWNUM BETWEEN (($page - 1) * $limit) + 1 AND $limit * ($page)";
		}
		return $this->db->query("SELECT * FROM ( SELECT ROW_NUMBER() OVER(ORDER BY ID_PROGRAM ASC) AS ROWNUM, ID_PROGRAM, TAHUN, TARGET, SATUAN, PAGU FROM tbl_SUBPROGRAM WHERE 1=1 {$filter}) X");}
	}

	public function deleteProgram($idprogram){
		$this->db->query("DELETE FROM tbl_PROGRAM WHERE ID IN ?",[$idprogram]);
		$this->db->query("DELETE FROM tbl_SUBPROGRAM WHERE ID_PROGRAM IN ?",[$idprogram]);
	}

	public function updateProgram($where, $set = []){
		$this->db->where($where);
		$this->db->update('tbl_PROGRAM', $set);
		return $this->db->affected_rows();
	}

	public function updateSubProgram($where, $subset = []){
		$this->db->where($where);
		$this->db->update('tbl_SUBPROGRAM', $subset);
		return $this->db->affected_rows();
	}
}