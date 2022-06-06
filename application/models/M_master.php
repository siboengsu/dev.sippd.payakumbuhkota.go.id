<?php
class M_master extends CI_Model {

	private $KDTAHUN = NULL;
	private $KDTAHAP = NULL;
	private $USERID = NULL;

	function __construct()
	{
		$this->KDTAHUN = $this->session->KDTAHUN;
		$this->KDTAHAP = $this->session->KDTAHAP;
		$this->USERID = $this->session->USERID;
	}

  public function getAll($filter = NULL, $offset = NULL, $count = FALSE)
	{

		if($count)
		{
			return $this->db->query("
				SELECT COUNT(MP.PGRMRKPDKEY) AS TOTAL_ROW
				FROM
					MPGRMRKPD MP
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
        ROW_NUMBER() OVER(ORDER BY D.KDUNIT ASC, MP.NUPRGRM ASC) AS ROWNUM,
        MP.PGRMRKPDKEY,
        ISNULL(D.KDUNIT, '0.00') AS KDUNIT,
        MP.NUPRGRM,
        MP.NMPRGRM
      FROM
        MPGRMRKPD MP
      LEFT JOIN DAFTUNIT D ON MP.UNITKEY = D.UNITKEY

      WHERE 
        1 = 1
        	{$filter} OR PGRMRKPDKEY = '000000_' 
    ) X

				{$where}
			");
		}
	}

  public function add($set = [])
	{
		$this->db->insert('MPGRMRKPD', $set);
		return $this->db->affected_rows();
	}

  public function update($where, $set = [])
  {
    $this->db->where($where);
    $this->db->update('MPGRMRKPD', $set);
    return $this->db->affected_rows();
  }

  public function loadUnitkeyLevel2($unitkey){
    $sql =  $this->db->query("SELECT KDUNIT FROM DAFTUNIT WHERE UNITKEY = '{$unitkey}'")->row_array()['KDUNIT'] ;
    $query = $this->db->query("SELECT UNITKEY FROM DAFTUNIT WHERE KDUNIT = SUBSTRING('{$sql}',1,8)")->row_array()['UNITKEY'];
    return $query;
  }

	public function getmasterKegiatanAll($filter = NULL, $offset = NULL, $count = FALSE)
	{
		if($count)
			{
				return $this->db->query("
					SELECT COUNT(MK.KEGRKPDKEY) AS TOTAL_ROW
					FROM
						MKEGRKPD MK
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
					ROW_NUMBER() OVER(ORDER BY KEGRKPDKEY ASC, MK.NUKEG ASC) AS ROWNUM,
					MK.KEGRKPDKEY,
					MK.PGRMRKPDKEY,
					MK.NUKEG,
					MK.NMKEG
				FROM
					MKEGRKPD MK

				WHERE
					1 = 1
					{$filter}

			) X
					{$where}

			"	);

	}
	}

	public function addKegiatan($set = [])
	{
		$this->db->insert('MKEGRKPD', $set);
		return $this->db->affected_rows();
	}
	// add sub kegiatan
	public function addSubKegiatan($set = [])
	{
		$this->db->insert('MSUBKEGRKPD', $set);
		return $this->db->affected_rows();
	}
	// end add sub kegiatan

	public function KegiatanUpdate($where, $set = [])
	{
		$this->db->where($where);
		$this->db->update('MKEGRKPD', $set);
		return $this->db->affected_rows();
	}

	public function subKegiatanUpdate($where, $set = [])
	{
		$this->db->where($where);
		$this->db->update('MSUBKEGRKPD', $set);
		return $this->db->affected_rows();
	}

	public function getPagu($filter = NULL, $offset = NULL, $count = FALSE)
	{
		if($count)
		{
			return $this->db->query("
			SELECT COUNT(P.NILAI) AS TOTAL_ROW
				FROM
				PAGUSKPD P
				JOIN DAFTUNIT U ON P.UNITKEY = U.UNITKEY
			WHERE
				1 = 1
				AND KDTAHAP= $this->KDTAHAP
				AND KDTAHUN = $this->KDTAHUN
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
							ROW_NUMBER() OVER(ORDER BY KDUNIT ASC, K.NILAI ASC) AS ROWNUM,
							(SELECT KDUNIT FROM DAFTUNIT WHERE UNITKEY = U.UNITKEY) AS KDUNIT,
							(SELECT NMUNIT FROM DAFTUNIT WHERE UNITKEY = U.UNITKEY) AS NMUNIT,
							U.UNITKEY,
							NILAI AS PAGU
						FROM PAGUSKPD K
						JOIN DAFTUNIT U ON K.UNITKEY = U.UNITKEY
						WHERE
						1 = 1
								{$filter}

						) X

						{$where}
						ORDER BY KDUNIT ASC
			");
		}
	}

	public function addPagu($set = [])
	{
			$this->db->insert('PAGUSKPD', $set);
			return $this->db->affected_rows();
	}

	public function updatePagu($where, $set = [])
	{
		$this->db->where($where);
		$this->db->update('PAGUSKPD', $set);
		return $this->db->affected_rows();
	}

	public function getPemda($filter = NULL, $offset = NULL, $count = FALSE)
	{
		if($count)
		{
			return $this->db->query("
			SELECT COUNT(CONFIGDES) AS TOTAL_ROW
				FROM
				PEMDA
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
							ROW_NUMBER() OVER(ORDER BY CONFIGDES ASC) AS ROWNUM,
							CONFIGDES,
							CONFIGVAL,
							CONFIGID
							FROM
							PEMDA
						WHERE
							1 = 1
								{$filter}

						) X
						{$where}

			");
		}
	}

	public function UpdatePemda($where, $set = [])
	{
		$this->db->where($where);
		$this->db->update('PEMDA', $set);
		return $this->db->affected_rows();
	}

	//11-10-18
public function getSSH1($filter = NULL, $offset = NULL, $count = FALSE)
	{
		if($count)
		{
			return $this->db->query("
			SELECT COUNT(KDSSH) AS TOTAL_ROW
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
							ROW_NUMBER() OVER(ORDER BY KDSSH ASC) AS ROWNUM,
							KDSSH,
							KDREK,
							KDTAHUN,
							SSH_NAMA,
							SSH_SATUAN,
							SSH_HARGA,
							SSH_SPEK,
							SSH_AKTIF
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

	public function addSSH($set = [])
	{
			print_r($set);
			$this->db->insert('PHPSSH', $set);
			return $this->db->affected_rows();


	}

	public function UpdateSSH($where, $set = [])
	{
		$this->db->where($where);
		$this->db->update('PHPSSH', $set);
		return $this->db->affected_rows();
	}

public function getRekeningSshAll($filter = NULL, $offset = NULL, $count = FALSE)
	{

	if($count)
		{
			return $this->db->query("
				SELECT
					COUNT(MTGKEY) AS TOTAL_ROW
				FROM
					MATANGR
				WHERE 1=1
				AND KDTAHUN = {$this->KDTAHUN}
				AND TYPE='D'
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
						ROW_NUMBER() OVER(ORDER BY KDPER ASC) AS ROWNUM,
						MTGLEVEL,
						TYPE,
						MTGKEY,
						KDPER,
						NMPER

					FROM
						MATANGR
					WHERE
						1=1
						AND KDTAHUN = {$this->KDTAHUN}
						AND TYPE='D'
						{$filter}
				) X
				{$where}
			");
		}

	}


		public function getRekeningMatangR($filter = NULL, $offset = NULL, $count = FALSE)
		{

		if($count)
			{
				return $this->db->query("
					SELECT
						COUNT(MTGKEY) AS TOTAL_ROW
					FROM
						MATANGR
					WHERE 1=1

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
							ROW_NUMBER() OVER(ORDER BY KDPER ASC) AS ROWNUM,
							MTGLEVEL,
							TYPE,
							MTGKEY,
							KDPER,
							NMPER

						FROM
							MATANGR
						WHERE
							1=1

							{$filter}
					) X
					{$where}
				");
			}

		}


	public function addMatangR($set = []){
		print_r($set);
		$this->db->insert('MATANGR', $set);
		return $this->db->affected_rows();

	}

	public function UpdateMatangR($where, $set = []){
		$this->db->where($where);
		$this->db->update('MATANGR', $set);
		return $this->db->affected_rows();
	}

	//20190525 history entry data
	public function cHistory($set= NULL, $nmTable = NULL, $unitkey = NULL ){
		$tanggal = date("Y-m-d H:i:s");
		$set1 = [
			'USERID'				=> 	$this->USERID,
			'TGL_UPDATE'		=> $tanggal,
			'NMTABLE'				=> $nmTable,
			'DATAUPDATE'		=> $set,
			'UNITKEY'				=> $unitkey
		];
		$this->db->insert('HISTORY', $set1);
		return $this->db->affected_rows();
	}





}
