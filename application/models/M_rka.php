<?php
class M_rka extends CI_Model {

	private $KDTAHUN = NULL;
	private $KDTAHAP = NULL;
	
	function __construct()
	{
		$this->KDTAHUN = $this->session->KDTAHUN;
		$this->KDTAHAP = $this->session->KDTAHAP;
	}
	
	public function addRekening($set = [])
	{
		$this->db->insert('PRARASKR', $set);
		return $this->db->affected_rows();
	}
	
	public function updateRekening($where, $set = [])
	{
		$this->db->where($where);
		$this->db->update('PRARASKR', $set);
		return $this->db->affected_rows();
	}
	
	public function addDetail($set = [])
	{
		$this->db->insert('PRARASKDETR', $set);
		return $this->db->affected_rows();
	}
	
	public function updateDetail($where, $set = [])
	{
		$this->db->where($where);
		$this->db->update('PRARASKDETR', $set);
		return $this->db->affected_rows();
	}
	
	public function getRekeningAll($filter = NULL, $offset = NULL, $count = FALSE)
	{
		if($count)
		{
			return $this->db->query("
				SELECT
					COUNT(R.MTGKEY) AS TOTAL_ROW,
					SUM(COALESCE(R.NILAI,0)) AS GRAND_TOTAL
				FROM
					PRARASKR R
				LEFT JOIN MATANGR T ON R.MTGKEY = T.MTGKEY AND  R.KDTAHUN = T.KDTAHUN
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
						ROW_NUMBER() OVER(ORDER BY T.KDPER ASC) AS ROWNUM,
						R.MTGKEY,
						T.KDPER,
						T.NMPER,
						R.NILAI
					FROM
						PRARASKR R
					LEFT JOIN MATANGR T ON R.MTGKEY = T.MTGKEY AND  R.KDTAHUN = T.KDTAHUN
					WHERE
						1 = 1
						{$filter}
				) X
				{$where}
			");
		}
	}
	
	public function getRekeningForm($unitkey, $kegrkpdkey, $filter = NULL, $offset = NULL, $count = FALSE)
	{
		if($count)
		{
			return $this->db->query("
				SELECT
					COUNT(MTGKEY) AS TOTAL_ROW
				FROM
					MATANGR
				WHERE
					MTGKEY NOT IN (
						SELECT MTGKEY
						FROM PRARASKR
						WHERE
							KDTAHUN = '{$this->KDTAHUN}'
						AND KDTAHAP = '{$this->KDTAHAP}'
						AND UNITKEY = '{$unitkey}'
						AND KEGRKPDKEY = '{$kegrkpdkey}'
					)
					AND KDTAHUN = '{$this->KDTAHUN}'
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
						MTGKEY,
						KDPER,
						NMPER,
						TYPE
					FROM
						MATANGR 
					WHERE
						MTGKEY NOT IN (
							SELECT MTGKEY
							FROM PRARASKR
							WHERE
								KDTAHUN = '{$this->KDTAHUN}'
							AND KDTAHAP = '{$this->KDTAHAP}'
							AND UNITKEY = '{$unitkey}'
							AND KEGRKPDKEY = '{$kegrkpdkey}'
						)
						AND KDTAHUN = '{$this->KDTAHUN}'
						{$filter}
				) X
				{$where}
			");
		}
	}
	
	public function getDetailAll($filter = NULL, $offset = NULL, $count = FALSE)
	{
		if($count)
		{
			return $this->db->query("
				SELECT
					COUNT(KDNILAI) AS TOTAL_ROW,
					SUM(CASE WHEN TYPE = 'D' THEN COALESCE(SUBTOTAL,0) ELSE '' END) AS GRAND_TOTAL,
					(
						SELECT NILAI
						FROM PRARASKR 
						WHERE
							1 = 1 
							{$filter}
					) AS HDR_NILAI
				FROM
					PRARASKDETR
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
						ROW_NUMBER() OVER(ORDER BY KDJABAR) AS ROWNUM,
						KDNILAI,
						KDJABAR,
						URAIAN,
						JUMBYEK,
						SATUAN,
						TARIF,
						SUBTOTAL,
						TYPE
					FROM
						PRARASKDETR
					WHERE
						1 = 1
						{$filter}
				) X
				{$where}
			");
		}
	}
	
	
	public function getSumberDanaDetail($unitkey, $kegrkpdkey){
				$query =  $this->db->query("SELECT K.KDDANA, J.NMDANA
							  	FROM KEGRKPDDANA  K JOIN JDANA J  ON K.KDDANA = J.KDDANA
								  WHERE UNITKEY = '{$unitkey}' AND KDTAHAP = {$this->KDTAHAP} AND KDTAHUN = {$this->KDTAHUN} AND KEGRKPDKEY = '{$kegrkpdkey}'");
 				$resultdata = $query->result_array();
 	  		return $resultdata;



	}
}
