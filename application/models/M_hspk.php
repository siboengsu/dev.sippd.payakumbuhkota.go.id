<?php
class M_hspk extends CI_Model {

	private $KDTAHUN = NULL;
	private $KDTAHAP = NULL;
	
	function __construct()
	{
		$this->KDTAHUN = $this->session->KDTAHUN;
		$this->KDTAHAP = $this->session->KDTAHAP;
	}
	
	public function add($set = [])
	{
		$this->db->insert('PHPKEGHSPK', $set);
		return $this->db->affected_rows();
	}
	
	public function update($where, $set = [])
	{
		$this->db->where($where);
		$this->db->update('PHPKEGHSPK', $set);
		return $this->db->affected_rows();
	}
	
	function getTotal($tipe, $unitkey, $kegrkpdkey, $id_keghspk)
	{
		return (double) $this->db->query("
		SELECT TOTAL
		FROM
		PHPKEGHSPK
		WHERE
			TIPE = ? 
		AND KDTAHUN = ? 
		AND KDTAHAP = ? 
		AND UNITKEY = ? 
		AND KEGRKPDKEY = ? 
		AND ID_KEGHSPK = ?",
		[
			$tipe,
			$this->KDTAHUN,
			$this->KDTAHAP,
			$unitkey,
			$kegrkpdkey,
			$id_keghspk
		])->row_array()['TOTAL'];
	}
	
	public function getAll($filter = NULL, $offset = NULL, $count = FALSE)
	{
		if($count)
		{
			return $this->db->query("
				SELECT COUNT(K.KEGRKPDKEY) AS TOTAL_ROW
				FROM
					KEGRKPD K
				LEFT JOIN MKEGRKPD MK ON K.KEGRKPDKEY = MK.KEGRKPDKEY
					AND K.PGRMRKPDKEY = MK.PGRMRKPDKEY
					AND K.KDTAHUN = MK.KDTAHUN
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
						ROW_NUMBER() OVER(ORDER BY MK.NUKEG) AS ROWNUM,
						K.KEGRKPDKEY,
						MK.NUKEG,
						MK.NMKEG,
						K.PAGUTIF,
						K.KUANTITATIF,
						K.SATUAN,
						K.LOKASI,
						CASE WHEN K.TGLVALID IS NULL THEN '' ELSE CONVERT(VARCHAR(10),K.TGLVALID,105) END AS TGLVALID
					FROM
						KEGRKPD K
					LEFT JOIN MKEGRKPD MK ON K.KEGRKPDKEY = MK.KEGRKPDKEY
						AND K.PGRMRKPDKEY = MK.PGRMRKPDKEY
						AND K.KDTAHUN = MK.KDTAHUN
					WHERE
						1 = 1
						{$filter}
				) X
				{$where}
			");
		}
	}
}
