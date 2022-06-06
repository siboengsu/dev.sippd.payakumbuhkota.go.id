<?php
class M_set extends CI_Model {

	private $KDTAHUN = NULL;
	private $KDTAHAP = NULL;

	function __construct()
	{
		$this->KDTAHUN = $this->session->KDTAHUN;
		$this->KDTAHAP = $this->session->KDTAHAP;
	}

	public function getTahun()
	{
		return $this->db->query("SELECT KDTAHUN, NMTAHUN FROM TAHUN")->result_array();
	}

	public function getTahap()
	{
		return $this->db->query("SELECT KDTAHAP, NMTAHAP FROM TAHAP")->result_array();
	}

	public function getKota()
	{
		return $this->db->query("SELECT
					ISNULL((SELECT p.CONFIGVAL FROM PEMDA p WHERE CONFIGID='pemda_s'),'') as PEMERINTAH,
					ISNULL((SELECT p.CONFIGVAL FROM PEMDA p WHERE CONFIGID='pemda_i'),'') AS NMKOTA ,
					ISNULL((SELECT p.CONFIGVAL FROM PEMDA p WHERE CONFIGID='pemda_j'),'')AS JABATAN,
					ISNULL((SELECT p.CONFIGVAL FROM PEMDA p WHERE CONFIGID='pemda_k'),'')AS NMPIMPINAN,
					NMTAHUN AS TAHUN
					FROM
					TAHUN
					WHERE KDTAHUN='{$this->KDTAHUN}'")->result_array();
	}

	public function matrik51_perubahan_peropd_SKPD($unitkey){
		$query = $this->db->query("EXEC [WSPR_MATRIK51_PERUBAHAN_RENJA] @KDTAHUN = '{$this->KDTAHUN}', @KDTAHAP ='{$this->KDTAHAP}', @UNITKEY ='{$unitkey}'");
		$resultdata = $query->result();
		//print_r($resultdata);
		return $resultdata;
	}

	public function getNextKey($tblid)
	{
		$oldkey = $this->db->query("SELECT NEXTKEY FROM NEXTKEY WHERE TABLEID = ?", $tblid)->row_array()['NEXTKEY'];
		$oldkey = filter_var($oldkey, FILTER_SANITIZE_NUMBER_INT);
		$newkey = $oldkey + 1;
		$newkey = $newkey . "_";
		return $newkey;
	}

	public function matrik51_perubahan_peropd($unitkey){
		$query = $this->db->query("EXEC [WSPR_MATRIK51_PERUBAHAN_PEROPD] @KDTAHUN = '{$this->KDTAHUN}', @KDTAHAP ='{$this->KDTAHAP}', @UNITKEY ='{$unitkey}'");
		$resultdata = $query->result();
		//print_r($resultdata);
		return $resultdata;
	}

	public function matrik51_perubahan (){

		//$query= $this->db->query("select * from KEGRKPD");
		$query = $this->db->query("EXEC [WSPR_MATRIK51_PERUBAHAN] @KDTAHUN = '{$this->KDTAHUN}', @KDTAHAP ='{$this->KDTAHAP}'");
		$resultdata = $query->result_array();
		return $resultdata;

	}

	public function matrik51perperangkatdaerahopdbluduptd ($UNITKEY, $periode){
		$query = $this->db->query("EXEC [WSPR_MATRIK51_OPD_GABUNG_UPTD] @KDTAHUN = '{$this->KDTAHUN}', @KDTAHAP ='{$this->KDTAHAP}', @UNITKEY = '{$UNITKEY}', @PERIODE = '{$periode}'");
		$resultdata = $query->result_array();
		return $resultdata;
	}

	public function matrik51perubahanperperangkatdaerahopdbluduptd ($UNITKEY){
		$query = $this->db->query("EXEC [WSPR_MATRIK51_OPD_GABUNG_UPTD_PERUBAHAN] @KDTAHUN = '{$this->KDTAHUN}', @KDTAHAP ='{$this->KDTAHAP}', @UNITKEY = '{$UNITKEY}'");
		$resultdata = $query->result_array();
	return $resultdata;
	}
	
	public function updateNextKey($tblid, $newkey)
	{
		$this->db->query("UPDATE NEXTKEY SET NEXTKEY = ? WHERE TABLEID = ?", [$newkey, $tblid]);
		return $this->db->affected_rows();
	}

	public function getPagu($unitkey)
	{
		return (double) $this->db->query("
		SELECT
			ISNULL(NILAI,0) AS NILAI
		FROM
			PAGUSKPD
		WHERE
			KDTAHUN = ?
		AND KDTAHAP = ?
		AND UNITKEY = ?",
		[
			$this->KDTAHUN,
			$this->KDTAHAP,
			$unitkey
		])->row_array()['NILAI'];
	}

	public function getAllPagu()
	{
		return $this->db->query(
				"SELECT
					(SELECT KDUNIT FROM DAFTUNIT WHERE UNITKEY = U.UNITKEY) AS KDUNIT,
					(SELECT NMUNIT FROM DAFTUNIT WHERE UNITKEY = U.UNITKEY) AS NMUNIT,
					ISNULL((SELECT p.CONFIGVAL FROM PEMDA p WHERE CONFIGID='pemda_s'),'') as PEMERINTAH,
					ISNULL((SELECT p.CONFIGVAL FROM PEMDA p WHERE CONFIGID='pemda_i'),'') AS KOTA ,
					ISNULL((SELECT NMTAHUN FROM TAHUN t WHERE t.KDTAHUN='{$this->KDTAHUN}'),'') AS TAHUN,
					ISNULL((SELECT p.CONFIGVAL FROM PEMDA p WHERE CONFIGID='pemda_j'),'')AS JABATAN,
					ISNULL((SELECT p.CONFIGVAL FROM PEMDA p WHERE CONFIGID='pemda_k'),'')AS NMPIMPINAN,
					SUM(K.PAGUTIF) AS PAGUTIF
				FROM KEGRKPD K
				JOIN DAFTUNIT U ON K.UNITKEY = U.UNITKEY
				WHERE
						K.KDTAHUN = '{$this->KDTAHUN}'
				AND K.KDTAHAP = '{$this->KDTAHAP}'
				GROUP BY U.UNITKEY
				ORDER BY KDUNIT" )->result_array();


	}
// PAGU OPD PER UPTD
	// public function rekapitulasi_pagu_opd(){
	// return $this->db->query(
			// "SELECT NMUNIT, NILAI FROM PAGUSKPD P
			 // LEFT JOIN DAFTUNIT D ON D.UNITKEY = P.UNITKEY
			 // WHERE
					// KDTAHUN = '{$this->KDTAHUN}'
				// AND KDTAHAP = '{$this->KDTAHAP}'
				// AND NILAI > 0
					// ORDER BY KDUNIT ASC" )->result_array();
	// }

	//update tanggal 27-06-2020 sebelum fasilitasi penggabungan uptd ke opd
	public function rekapitulasi_pagu_opd(){

		$query = $this->db->query("EXEC PAGU_PER_OPD_UPTD_GABUNG @KDTAHUN = '{$this->KDTAHUN}', @KDTAHAP ='{$this->KDTAHAP}'");

		$result = $query->result_array();
	 return $result;
	}








	public function paguSKPD()
	{
			return  $this->db->query("
					SELECT
						P.UNITKEY,
						KDLEVEL,
						(SELECT KDUNIT FROM DAFTUNIT WHERE UNITKEY = U.UNITKEY) AS KDUNIT,
						(SELECT NMUNIT FROM DAFTUNIT WHERE UNITKEY = U.UNITKEY) AS NMUNIT,
						COALESCE(P.NILAI,0) AS PAGU,
						(
							SELECT SUM(COALESCE(PAGUTIF,0))
							FROM KEGRKPD
							WHERE
								KDTAHUN = P.KDTAHUN
							AND KDTAHAP = P.KDTAHAP
							AND UNITKEY = P.UNITKEY
						) AS PAGUUSED,
						(
							COALESCE(P.NILAI,0) - (
								SELECT SUM(COALESCE(PAGUTIF,0))
								FROM KEGRKPD
								WHERE
									KDTAHUN = P.KDTAHUN
								AND KDTAHAP = P.KDTAHAP
								AND UNITKEY = P.UNITKEY
							)
						) AS SELISIH
					FROM PAGUSKPD P
					JOIN DAFTUNIT U ON P.UNITKEY = U.UNITKEY
					WHERE
						KDTAHUN = '{$this->KDTAHUN}'
					AND KDTAHAP = '{$this->KDTAHAP}'
					AND KDLEVEL = 3
					ORDER BY KDUNIT ASC")->result_array();
	}

	public function pagukec(){
		return  $this->db->query("
		SELECT KDUNIT AS KODEUNIT, SUM(PAGU) AS PAGUKEC, SUM(PAGUUSED) AS PAGUUSEKEC
			FROM

			(SELECT
					 P.UNITKEY,
					 KDLEVEL,
					(SELECT SUBSTRING(KDUNIT,0,18) FROM DAFTUNIT WHERE UNITKEY = U.UNITKEY) AS KDUNIT,
					(SELECT NMUNIT FROM DAFTUNIT WHERE UNITKEY = U.UNITKEY) AS NMUNIT,
					 COALESCE(P.NILAI,0) AS PAGU,
					(
						SELECT SUM(COALESCE(PAGUTIF,0))
						FROM KEGRKPD
						WHERE
							KDTAHUN = P.KDTAHUN
						AND KDTAHAP = P.KDTAHAP
						AND UNITKEY = P.UNITKEY
					) AS PAGUUSED

				FROM PAGUSKPD P
				JOIN DAFTUNIT U ON P.UNITKEY = U.UNITKEY
				WHERE
				KDTAHUN = '{$this->KDTAHUN}'
				AND KDTAHAP = '{$this->KDTAHAP}'
				AND KDLEVEL = 4

				) X
				GROUP BY KDUNIT")->result_array();
	}

	public function m_pagu_indikatif_nonADK(){
		return  $this->db->query("
				SELECT
			 P.UNITKEY,
			 KDLEVEL,
			(SELECT KDUNIT FROM DAFTUNIT WHERE UNITKEY = U.UNITKEY) AS KDUNIT,
			(SELECT NMUNIT FROM DAFTUNIT WHERE UNITKEY = U.UNITKEY) AS NMUNIT,
			 COALESCE(P.NILAI,0) AS PAGU,
			(
				SELECT SUM(COALESCE(PAGUTIF,0))
				FROM KEGRKPD
				WHERE
					KDTAHUN = P.KDTAHUN
				AND KDTAHAP = P.KDTAHAP
				AND UNITKEY = P.UNITKEY
			) AS PAGUUSED,
			(
				COALESCE(P.NILAI,0) - (
					SELECT SUM(COALESCE(PAGUTIF,0))
					FROM KEGRKPD
					WHERE
						KDTAHUN = P.KDTAHUN
					AND KDTAHAP = P.KDTAHAP
					AND UNITKEY = P.UNITKEY
				)
			) AS SELISIH
		FROM PAGUSKPD P
		JOIN DAFTUNIT U ON P.UNITKEY = U.UNITKEY
		WHERE
			KDTAHUN = '{$this->KDTAHUN}'
		AND KDTAHAP = '{$this->KDTAHAP}'
		AND P.UNITKEY NOT IN (SELECT UNITKEY FROM DAFTUNIT WHERE UNITKEY NOT IN ('151_','170_','180_','190_','197_') AND KDLEVEL = 4)
		ORDER BY KDUNIT ASC")->result_array();
	}

	public function getUrusanAll()
	{
		//$sql = "EXEC WSPR_RENJASNKURUSALL @KDTAHUN = '{$this->KDTAHUN}', @KDTAHAP ='{$this->KDTAHAP}'";
		$query = $this->db->query("EXEC WSPR_RENJASNKURUSALL @KDTAHUN = '{$this->KDTAHUN}', @KDTAHAP ='{$this->KDTAHAP}'");
		$result = $query->result_array();
		//print_r($query);
 	   return $result;
	}

	public function getRenjaAll($unit)
	{

		 $query = $this->db->query("EXEC [WSPR_RENJASNK-TAB5] @UNITKEY ='{$unit}', @KDTAHUN = '{$this->KDTAHUN}', @KDTAHAP ='{$this->KDTAHAP}'");
		 $result = $query->result();
		 //print_r($result);
		  return $result;
	}

	public function getPraRKAAll($unitkey){

		$sql =  $this->db->query("SELECT KDUNIT FROM DAFTUNIT WHERE UNITKEY = '{$unitkey}'")->row_array()['KDUNIT'] ;
		 //print_r($sql);
		 $query = $this->db->query("SELECT * FROM DAFTUNIT WHERE KDUNIT = SUBSTRING('{$sql}',1,5)")->row_array()['NMUNIT'];
		 //$result = $query->result();
		// print_r($sql);
			return $query;

	}

	public function getProgramReport($kegrkpdkey){
		   $sql = $this->db->query("SELECT NUPRGRM, NMPRGRM, NUKEG FROM MPGRMRKPD MP JOIN MKEGRKPD MK ON MK.PGRMRKPDKEY = MP.PGRMRKPDKEY
			AND MK.KDTAHUN = MP.KDTAHUN WHERE MK.KEGRKPDKEY = '{$kegrkpdkey}' AND MK.KDTAHUN = '{$this->KDTAHUN}'")->result();
		//	print_r($sql);
			return $sql;
	 }

	public function getTOLAKUKUR($kegrkpdkey, $unitkey) {
		$query = $this->db->query("SELECT JK.KDJKK, URJKK,TOLOKUR,TARGET FROM KINKEGRKPD KK JOIN JKINKEG JK ON KK.KDJKK = JK.KDJKK  WHERE UNITKEY ='{$unitkey}' AND KDTAHUN='{$this->KDTAHUN}' AND KDTAHAP = '{$this->KDTAHAP}' AND KEGRKPDKEY = '{$kegrkpdkey}' AND KK.KDJKK NOT IN ('11')");
		 $result = $query->result();
		 //print_r($result);
		  return $result;
	 }

	public function getDetailPraRKA ($unitkey, $kegrkpdkey){
		 //$SQL ="EXEC [WSPR_PRARKACETAK] @UNITKEY ='{$unitkey}', @KEGRKPDKEY = '{$kegrkpdkey}', @KDTAHUN = '{$this->KDTAHUN}', @KDTAHAP ='{$this->KDTAHAP}' ";
		 $query = $this->db->query("EXEC [WSPR_PRARKACETAK] @UNITKEY ='{$unitkey}', @KEGRKPDKEY = '{$kegrkpdkey}', @KDTAHUN = '{$this->KDTAHUN}', @KDTAHAP ='{$this->KDTAHAP}' ");
		 $result = $query->result();
		// print_r($SQL);
			return $result;

	 }

	public function getTotalPrarka ($unitkey, $kegrkpdkey){
		 $query = $this->db->query("SELECT SUM(PR.NILAI) AS total
					 FROM PRARASKR PR
					JOIN MATANGR M ON PR.MTGKEY = M.MTGKEY AND PR.KDTAHUN = M.KDTAHUN
					WHERE PR.KDTAHUN='{$this->KDTAHUN}' AND UNITKEY='{$unitkey}' AND KEGRKPDKEY = '{$kegrkpdkey}' AND KDTAHAP ='{$this->KDTAHAP}'");
		 $result = $query->result();
		 return $result;
	 }

	public function getLokasi ($unitkey, $kegrkpdkey){
		 $query = $this->db->query("SELECT *FROM KEGRKPD pr	WHERE KDTAHUN='{$this->KDTAHUN}' AND UNITKEY='{$unitkey}' and KEGRKPDKEY = '{$kegrkpdkey}'AND KDTAHAP ='{$this->KDTAHAP}'");
		 $result = $query->result();
		 return $result;

	}

	public function getRPagu ($unitkey, $kegrkpdkey){
		 $query = $this->db->query("SELECT *FROM KINKEGRKPD KK	WHERE KDTAHUN='{$this->KDTAHUN}' AND KDJKK = '01' AND UNITKEY='{$unitkey}' and KEGRKPDKEY = '{$kegrkpdkey}'AND KDTAHAP ='{$this->KDTAHAP}'")->row_array()['TARGETMIN1'] ;
		// $result = $query->result();

		 return $query;

	 }

	public function getPaguOpdSummary($unitkey)
	{
		$pagu = $this->db->query("
		SELECT
			COALESCE(P.NILAI,0) AS PAGU,
			(
				SELECT SUM(COALESCE(PAGUTIF,0))
				FROM KEGRKPD
				WHERE
					KDTAHUN = P.KDTAHUN
				AND KDTAHAP = P.KDTAHAP
				AND UNITKEY = P.UNITKEY
			) AS PAGUUSED,
			(
				COALESCE(P.NILAI,0) - (
					SELECT SUM(COALESCE(PAGUTIF,0))
					FROM KEGRKPD
					WHERE
						KDTAHUN = P.KDTAHUN
					AND KDTAHAP = P.KDTAHAP
					AND UNITKEY = P.UNITKEY
				)
			) AS SELISIH
		FROM PAGUSKPD P
		WHERE
			KDTAHUN = ?
		AND KDTAHAP = ?
		AND UNITKEY = ?",
		[
			$this->KDTAHUN,
			$this->KDTAHAP,
			$unitkey
		])->row_array();

		return [
			'PAGU' => (isset($pagu['PAGU']) ? $pagu['PAGU'] : 0),
			'PAGUUSED' => (isset($pagu['PAGUUSED']) ? $pagu['PAGUUSED'] : 0),
			'SELISIH' => (isset($pagu['SELISIH']) ? $pagu['SELISIH'] : 0)
		];
	}

	public function getPaguKegiatanSummary($unitkey, $kegrkpdkey)
	{
		$pagu = $this->db->query("
		SELECT
			SUM(COALESCE(PAGUTIF,0)) AS PAGU,
			(
				SELECT SUM(COALESCE(SUBTOTAL,0))
				FROM PRARASKDETR
				WHERE
					KDTAHUN = '{$this->KDTAHUN}'
				AND KDTAHAP = '{$this->KDTAHAP}'
				AND UNITKEY = '{$unitkey}'
				AND KEGRKPDKEY = '{$kegrkpdkey}'
				AND TYPE = 'D'
			) AS PAGUUSED,
			(
				SUM(COALESCE(PAGUTIF,0)) - (
					SELECT SUM(COALESCE(SUBTOTAL,0))
					FROM PRARASKDETR
					WHERE
						KDTAHUN = '{$this->KDTAHUN}'
					AND KDTAHAP = '{$this->KDTAHAP}'
					AND UNITKEY = '{$unitkey}'
					AND KEGRKPDKEY = '{$kegrkpdkey}'
					AND TYPE = 'D'
				)
			) AS SELISIH
		FROM KEGRKPD K
		WHERE
			KDTAHUN = '{$this->KDTAHUN}'
		AND KDTAHAP = '{$this->KDTAHAP}'
		AND UNITKEY = '{$unitkey}'
		AND KEGRKPDKEY = '{$kegrkpdkey}'")->row_array();

		return [
			'PAGU' => (($pagu) ? $pagu['PAGU'] : 0),
			'PAGUUSED' => (($pagu) ? $pagu['PAGUUSED'] : 0),
			'SELISIH' => (($pagu) ? $pagu['SELISIH'] : 0)
		];
	}

	public function getUrusan($filter = NULL, $offset = NULL, $count = FALSE)
	{
		if($count)
		{
			return $this->db->query("
				SELECT COUNT(D.UNITKEY) AS TOTAL_ROW
				FROM
					DAFTUNIT D
				WHERE
					1 = 1
					{$filter}
			");
		}
		else
		{
			if($offset !== NULL)
			{
				$page = $offset[1];
				$limit = $offset[0];

				$where = "
				WHERE
					X.ROWNUM BETWEEN (($page - 1) * $limit) + 1 AND $limit * ($page)";
			}

			return $this->db->query("
				SELECT * FROM
				(
					SELECT
						ROW_NUMBER() OVER(ORDER BY D.KDUNIT) AS ROWNUM,
						D.UNITKEY,
						D.KDUNIT,
						D.NMUNIT
					FROM
						DAFTUNIT D
					WHERE
						1 = 1
						{$filter}
				) X
				{$where}
			");
		}
	}

	public function getHspk2($filter = NULL, $offset = NULL, $count = FALSE)
	{
		if($count)
		{
			return $this->db->query("
				SELECT
					COUNT(KDHSPK2) AS TOTAL_ROW
				FROM
					PHPHSPK2
				WHERE
					1 = 1
					{$filter}
			");
		}
		else
		{
			if($offset !== NULL)
			{
				$page = $offset[1];
				$limit = $offset[0];

				$where = "
				WHERE
					X.ROWNUM BETWEEN (($page - 1) * $limit) + 1 AND $limit * ($page)";
			}

			return $this->db->query("
				SELECT * FROM
				(
					SELECT
						ROW_NUMBER() OVER(ORDER BY KDHSPK2) AS ROWNUM,
						KDHSPK2,
						HSPK2_NAMA
					FROM
						PHPHSPK2
					WHERE
						1 = 1
						{$filter}
				) X
				{$where}
			");
		}
	}

	public function getHspk3($filter = NULL, $offset = NULL, $count = FALSE)
	{
		if($count)
		{
			return $this->db->query("
				SELECT
					COUNT(H3.KDHSPK3) AS TOTAL_ROW
				FROM
					PHPHSPK3 H3
				WHERE
					1 = 1
					{$filter}
			");
		}
		else
		{
			if($offset !== NULL)
			{
				$page = $offset[1];
				$limit = $offset[0];

				$where = "
				WHERE
					X.ROWNUM BETWEEN (($page - 1) * $limit) + 1 AND $limit * ($page)";
			}

			return $this->db->query("
				SELECT * FROM
				(
					SELECT
						ROW_NUMBER() OVER(ORDER BY H3.KDHSPK3) AS ROWNUM,
						H3.*,
						H2.HSPK2_NAMA
					FROM
						PHPHSPK3 H3
						LEFT JOIN PHPHSPK2 H2 ON H3.KDHSPK2 = H2.KDHSPK2
					WHERE
						1 = 1
						{$filter}
				) X
				{$where}
			");
		}
	}

	public function getSsh($filter = NULL, $offset = NULL, $count = FALSE)
	{
		if($count)
		{
			return $this->db->query("
				SELECT
					COUNT(KDSSH) AS TOTAL_ROW
				FROM
					PHPSSH
				WHERE
					1 = 1
					{$filter}
			");
		}
		else
		{
			if($offset !== NULL)
			{
				$page = $offset[1];
				$limit = $offset[0];

				$where = "
				WHERE
					X.ROWNUM BETWEEN (($page - 1) * $limit) + 1 AND $limit * ($page)";
			}

			return $this->db->query("
				SELECT * FROM
				(
					SELECT
						ROW_NUMBER() OVER(ORDER BY KDSSH) AS ROWNUM,
						*
					FROM
						PHPSSH
					WHERE
						1 = 1
						{$filter}
				) X
				{$where}
			");
		}
	}
//baru
	public function getMatrikRenjaUrusanAll (){

		//$query= $this->db->query("select * from KEGRKPD");
		$query = $this->db->query("EXEC [WSPR_RENJASNKRKPDALL] @KDTAHUN = '{$this->KDTAHUN}', @KDTAHAP ='{$this->KDTAHAP}'");
		$resultdata = $query->result();
 	   return $resultdata;

	}

	public function matrik51all (){

		//$query= $this->db->query("select * from KEGRKPD");
		$query = $this->db->query("EXEC [WSPR_MATRIK51] @KDTAHUN = '{$this->KDTAHUN}', @KDTAHAP ='{$this->KDTAHAP}'");
		$resultdata = $query->result_array();
		return $resultdata;

	}

	public function matrik51all2018 (){

		//$query= $this->db->query("select * from KEGRKPD");
		$query = $this->db->query("EXEC [WSPR_MATRIK512018] @KDTAHUN = '{$this->KDTAHUN}', @KDTAHAP ='{$this->KDTAHAP}'");
		$resultdata = $query->result();
		 return $resultdata;

	}

	public function bappedadata(){
		$query = $this->db->query("SELECT * FROM PEGAWAI WHERE JABATAN LIKE '%KEPALA BAPPEDA%'");
		$resultdata = $query->result();
		return $resultdata;
	}

 	public function getNextKeySSH($tblid)
	{
		$oldkey = $this->db->query("SELECT NEXTKEY FROM NEXTKEY WHERE TABLEID = ?", $tblid)->row_array()['NEXTKEY'];
		$oldkey = filter_var($oldkey, FILTER_SANITIZE_NUMBER_INT);
		$newkey = $oldkey + 1;
		return $newkey;
	}

	//201903050925
	 public function getProgramKegiatanAll($unit)
	 {
		$query = $this->db->query("EXEC [WSPR_RENJASNK] @UNITKEY ='{$unit}', @KDTAHUN = '{$this->KDTAHUN}', @KDTAHAP ='{$this->KDTAHAP}'");
		$result = $query->result();
		 return $result;
	 }

	 //==================
	 //20190405 penambahan filter cetak prarka
	public function adjustmentkinkegrka($unitkey, $kegrkpdkey){
	 $query = $this->db->query("
				SELECT
			UNITKEY,
			KDJKK,
			KEGRKPDKEY,
			KDTAHUN,
			TARGET,
			(SELECT
			CONVERT(varchar, CAST(SUM(NILAI) AS money), 1) FROM PRARASKR WHERE UNITKEY = KK.UNITKEY AND KDTAHUN = KK.KDTAHUN AND KEGRKPDKEY = KK.KEGRKPDKEY AND KDTAHAP = KK.KDTAHAP) AS PRARASKR,
			(SELECT CONVERT(varchar, CAST(SUM(PAGUTIF) AS money), 1) FROM KEGRKPD WHERE UNITKEY = KK.UNITKEY AND KEGRKPDKEY = KK.KEGRKPDKEY AND KDTAHUN = KK.KDTAHUN AND KDTAHAP = KK.KDTAHAP ) AS KEG
			FROM KINKEGRKPD KK WHERE UNITKEY = '{$unitkey}' AND KDTAHUN = {$this->KDTAHUN} AND KDJKK = '01'  AND KEGRKPDKEY = '{$kegrkpdkey}' AND KDTAHAP = {$this->KDTAHAP}");
	$resultdata = $query->result();
	return $resultdata;

	}

	public function CetakSSH($filter){
			$query = $this->db->query("SELECT * FROM PHPSSH WHERE KDTAHUN = {$this->KDTAHUN} {$filter} ");
			$resultdata = $query->result_array();
			return $resultdata;
	}

	public function cKinkeg($unitkey, $kegrkpdkey){
		$query = $this->db->query("SELECT COUNT(KDJKK) AS TKDJKK	FROM KINKEGRKPD	WHERE	KDTAHUN = {$this->KDTAHUN}	AND KDTAHAP = {$this->KDTAHAP}	AND UNITKEY = '{$unitkey}'	AND KEGRKPDKEY = '{$kegrkpdkey}' AND KDJKK IN('00','01','02','03','04','11')");
		$resultdata = $query->result();
		return $resultdata;
}

	Public function cDana($unitkey, $kegrkpdkey){
		$query = $this->db->query("SELECT KD.KDDANA, NMDANA FROM KEGRKPDDANA KD JOIN JDANA JD ON JD.KDDANA = KD.KDDANA WHERE UNITKEY = '{$unitkey}' AND KDTAHUN = {$this->KDTAHUN} AND KDTAHAP = {$this->KDTAHAP} AND  KEGRKPDKEY = '{$kegrkpdkey}'")->row_array()['NMDANA'] ;
		return $query;
	}

	public function getUrusanAllPerubahan()
	{
		//$sql = "EXEC WSPR_RENJASNKURUSALL @KDTAHUN = '{$this->KDTAHUN}', @KDTAHAP ='{$this->KDTAHAP}'";
		$query = $this->db->query("EXEC REKAP_PAGU @KDTAHUN = '{$this->KDTAHUN}', @KDTAHAP ='{$this->KDTAHAP}'");
		$result = $query->result();
		//print_r($query);
		 return $result;
	}

	public function pagukecPerubahan(){
		return  $this->db->query("
		SELECT KDUNIT AS KODEUNIT, SUM(PAGU) AS PAGUKEC, SUM(PAGUDPA) AS PAGUDPA
		FROM

		(SELECT
				 P.UNITKEY,
				 KDLEVEL,
				(SELECT SUBSTRING(KDUNIT,0,12) FROM DAFTUNIT WHERE UNITKEY = U.UNITKEY) AS KDUNIT,
				(SELECT NMUNIT FROM DAFTUNIT WHERE UNITKEY = U.UNITKEY) AS NMUNIT,
				(
					SELECT SUM(COALESCE(PAGUTIFDPA,0))
					FROM KEGRKPD
					WHERE
						KDTAHUN = P.KDTAHUN
					AND KDTAHAP = P.KDTAHAP
					AND UNITKEY = P.UNITKEY
				) AS PAGUDPA,
				 COALESCE(P.NILAI,0) AS PAGU

			FROM PAGUSKPD P
			JOIN DAFTUNIT U ON P.UNITKEY = U.UNITKEY
			WHERE
			KDTAHUN = '{$this->KDTAHUN}'
			AND KDTAHAP = '{$this->KDTAHAP}'
			AND KDLEVEL = 4

			) X
			GROUP BY KDUNIT
				")->result_array();
	}

	public function paguSKPDPerubahan()
	{
		return  $this->db->query("
		SELECT
	(SELECT KDUNIT FROM DAFTUNIT WHERE UNITKEY = U.UNITKEY) AS KODEUNIT,
	(SELECT NMUNIT FROM DAFTUNIT WHERE UNITKEY = U.UNITKEY) AS NMUNIT,
	COALESCE(P.NILAI,0) AS PAGUKEC,
	(
		SELECT SUM(COALESCE(PAGUTIFDPA,0))
		FROM KEGRKPD
		WHERE
			KDTAHUN = P.KDTAHUN
		AND KDTAHAP = P.KDTAHAP
		AND UNITKEY = P.UNITKEY
	) AS PAGUDPA
FROM PAGUSKPD P
JOIN DAFTUNIT U ON P.UNITKEY = U.UNITKEY
WHERE
	KDTAHUN = 21
AND KDTAHAP = 5
AND KDLEVEL = 3
 
UNION 
 
SELECT KDUNIT AS KODEUNIT, '' AS NMUNIT, SUM(PAGU) AS PAGUKEC, SUM(PAGUDPA) AS PAGUDPA FROM
	(SELECT
		 P.UNITKEY,
		 KDLEVEL,
		(SELECT SUBSTRING(KDUNIT,0,18) FROM DAFTUNIT WHERE UNITKEY = U.UNITKEY) AS KDUNIT,
		(SELECT NMUNIT FROM DAFTUNIT WHERE UNITKEY = U.UNITKEY) AS NMUNIT,
		
		(
			SELECT SUM(COALESCE(PAGUTIFDPA,0))
			FROM KEGRKPD
			WHERE
				KDTAHUN = P.KDTAHUN
			AND KDTAHAP = P.KDTAHAP
			AND UNITKEY = P.UNITKEY
		)AS PAGUDPA,
		COALESCE(P.NILAI,0) AS PAGU
	FROM PAGUSKPD P
	JOIN DAFTUNIT U ON P.UNITKEY = U.UNITKEY
WHERE
KDTAHUN = '21'
AND KDTAHAP = '5'
AND KDLEVEL = 4
)X
GROUP BY KDUNIT")->result_array();
	}

	public function matrik51perperangkatdaerah($UNITKEY){
		$query = $this->db->query("EXEC [WSPR_MATRIK51PERPERANGKATDAERAH] @KDTAHUN = '{$this->KDTAHUN}', @KDTAHAP ='{$this->KDTAHAP}', @UNITKEY = '{$UNITKEY}'");
		$resultdata = $query->result_array();
		return $resultdata;
	}


	public function getPaguOPDGabung2021()
	{

		$query = $this->db->query("EXEC WSPR_CEK_PAGU_SELISIH @KDTAHUN = '{$this->KDTAHUN}', @KDTAHAP ='{$this->KDTAHAP}'");
		$result = $query->result_array();
		//print_r($query);
		 return $result;
	}



}
