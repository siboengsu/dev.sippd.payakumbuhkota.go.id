<?php
class M_kegiatan extends CI_Model {

	private $KDTAHUN = NULL;
	private $KDTAHAP = NULL;

	function __construct()
	{
		$this->KDTAHUN = $this->session->KDTAHUN;
		$this->KDTAHAP = $this->session->KDTAHAP;
	}

	public function getKegiatanName($kegrkpdkey)
	{
		return $this->db->query("SELECT NMKEG FROM MKEGRKPD WHERE KDTAHUN = ? AND KEGRKPDKEY = ?", [$this->KDTAHUN, $kegrkpdkey])->row_array()['NMKEG'];
	}

	public function getPgrmrkpdkey($kegrkpdkey)
	{
		return $this->db->query("SELECT PGRMRKPDKEY FROM MKEGRKPD WHERE KDTAHUN = ? AND KEGRKPDKEY = ?", [$this->KDTAHUN, $kegrkpdkey])->row_array()['PGRMRKPDKEY'];
	}

	public function add($set = [])
	{
		$this->db->insert('KEGRKPD', $set);
		return $this->db->affected_rows();
	}

	public function update($where, $set = [])
	{
		$this->db->where($where);
		$this->db->update('KEGRKPD', $set);
		return $this->db->affected_rows();
	}
	
	function getTotal($unitkey, $kegrkpdkey = NULL)
	{
		$this->db->select('SUM(COALESCE((PAGUTIF,0)) AS NILAI', FALSE);

		if($kegrkpdkey != NULL):
		$this->db->where('KEGRKPDKEY', $kegrkpdkey);
		endif;
		$this->db->where([
			'KDTAHUN' => $this->KDTAHUN,
			'KDTAHAP' => $this->KDTAHAP,
			'UNITKEY' => $unitkey

		]);
		return (double) $this->db->get('KEGRKPD')->row_array()['NILAI'];
	}

	function updateTotal($unitkey, $kegrkpdkey)
	{
		$this->db->query("
		UPDATE KEGRKPD
		SET PAGUTIF = (
			SELECT SUM(COALESCE((TOTAL,0)) FROM PHPKEGHSPK WHERE
				KDTAHUN = ?
				AND KDTAHAP = ?
				AND UNITKEY = ?
				AND KEGRKPDKEY = ?
		)
		WHERE
			KDTAHUN = ?
		AND KDTAHAP = ?
		AND UNITKEY = ?
		AND KEGRKPDKEY = ?",
		[
			$this->KDTAHUN, $this->KDTAHAP, $unitkey, $kegrkpdkey,
			$this->KDTAHUN, $this->KDTAHAP, $unitkey, $kegrkpdkey
		]);
		return $this->db->affected_rows();
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
						K.TARGETSEBELUM,
						K.SATUAN,
						K.LOKASI,
						IS_RES_GENDER,
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
