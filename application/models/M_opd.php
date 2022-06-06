<?php
class M_opd extends CI_Model {

	public function __construct()
	{
		
	}
	
	// opd
	public function getopd($filter = NULL, $offset = NULL, $count = FALSE)
	{
	if($count)
		{
			return $this->db->query("
				SELECT COUNT(A.UNITKEY) AS TOTAL_ROW
				FROM
					ATASBEND AS A
					LEFT JOIN PEGAWAI AS P ON A.NIP = P.NIP
					LEFT JOIN DAFTUNIT AS D ON A.UNITKEY = D.UNITKEY
				WHERE
					KDLEVEL = 3  AND KDUNIT NOT IN (SELECT KDUNIT FROM DAFTUNIT WHERE KDUNIT LIKE '%7.01.0.00.0.00.01.00%')
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
						ROW_NUMBER() OVER(ORDER BY A.UNITKEY) AS ROWNUM,
						A.UNITKEY,
 						A.NIP,
						A.VALUE,
						A.id, 
						P.NAMA,
						D.NMUNIT
					FROM
						ATASBEND AS A
						LEFT JOIN PEGAWAI AS P ON A.NIP = P.NIP
						LEFT JOIN DAFTUNIT AS D ON A.UNITKEY = D.UNITKEY
					WHERE 
						KDLEVEL = 3  AND KDUNIT NOT IN (SELECT KDUNIT FROM DAFTUNIT WHERE KDUNIT LIKE '%7.01.0.00.0.00.01.00%')
						{$filter}
				) X
				{$where}
			");
		}

	}

	public function addopd($set = []){
		print_r($set);
		$this->db->insert('ATASBEND', $set);
		return $this->db->affected_rows();
	}

	public function updateopd($where, $set = [])
	{
		$this->db->where($where);
		$this->db->update('ATASBEND', $set);
		return $this->db->affected_rows();
	}

	// pegawai
	public function getpegawai($filter = NULL, $offset = NULL, $count = FALSE)
	{
	if($count)
		{
			return $this->db->query("
				SELECT COUNT(U.UNITKEY) AS TOTAL_ROW
				FROM
					PEGAWAI AS U
					LEFT JOIN DAFTUNIT AS DU ON U.UNITKEY = DU.UNITKEY
				WHERE
					1=1
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
						ROW_NUMBER() OVER(ORDER BY U.UNITKEY) AS ROWNUM,
						DU.NMUNIT,
						U.NIP,
						U.NAMA,
						U.JABATAN
					FROM
						PEGAWAI AS U
						LEFT JOIN DAFTUNIT AS DU ON U.UNITKEY = DU.UNITKEY
					WHERE
						1=1

						{$filter}
				) X
				{$where}
			");
		}

	}

	public function addpegawai($set = []){
		print_r($set);
		$this->db->insert('PEGAWAI', $set);
		return $this->db->affected_rows();
	}

	public function updatepegawai($where, $set = [])
	{
		$this->db->where($where);
		$this->db->update('PEGAWAI', $set);
		return $this->db->affected_rows();
	}

	public function getGolongan()
	{
		return $this->db->query("SELECT * FROM GOLONGAN")->result_array();
	}

	public function getJabatan()
	{
		return $this->db->query("SELECT * FROM JABATAN")->result_array();
	}
}