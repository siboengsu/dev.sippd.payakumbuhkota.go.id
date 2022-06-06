<?php
class M_user extends CI_Model {

	public function __construct()
	{
		
	}

	public function get($id = null)
    {
        $this->db->from('WEBUSER');
        if($id != null){

        }
        $query = $this->db->get();
        return $query;
    }
	
	public function login($tahun, $userid)
	{
		$sql = "
		SELECT
			H.KDTAHUN,
			H.NMTAHUN,

			T.KDTAHAP,
			T.NMTAHAP,

			U.UNITKEY,
			D.KDUNIT,
			D.NMUNIT,

			U.USERID,
			U.PHPPWD,
			U.GROUPID,
			G.NMGROUP,
			U.NAMA,

			U.STINSERT,
			U.STUPDATE,
			U.STDELETE,
			U.TRANSECURE,
			
			(
				STUFF((
					SELECT
						'#' + O.ID_MENU
					FROM
						PHPWEBOTOR O
						JOIN PHPWEBROLE R ON O.ID_MENU = R.ID_MENU
					WHERE
						O.GROUPID = U.GROUPID
					AND R.TIPE = 'D'
					AND (R.KDTAHAP = U.KDTAHAP OR R.KDTAHAP IS NULL)
					
					FOR XML PATH('')
				), 1, 1, '' )
			) AS ID_MENU
		FROM 
			WEBUSER U
			JOIN TAHUN H ON H.KDTAHUN = ?
			JOIN TAHAP T ON U.KDTAHAP = T.KDTAHAP
			JOIN WEBGROUP G ON U.GROUPID = G.GROUPID
			LEFT JOIN DAFTUNIT D ON U.UNITKEY = D.UNITKEY
		WHERE 
			U.USERID = ?";
		
		return $this->db->query($sql, [$tahun, $userid]);
	}

// ari
	public function getuser($filter = NULL, $offset = NULL, $count = FALSE)
	{
	if($count)
		{
			return $this->db->query("
				SELECT
					COUNT(USERID) AS TOTAL_ROW
				FROM
					WEBUSER
					LEFT JOIN DAFTUNIT ON WEBUSER.UNITKEY=DAFTUNIT.UNITKEY
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
						ROW_NUMBER() OVER(ORDER BY USERID ASC) AS ROWNUM,
						USERID,
						KET,
						NMUNIT,
						NAMA,
						NIP
					FROM
						WEBUSER
						LEFT JOIN DAFTUNIT ON WEBUSER.UNITKEY=DAFTUNIT.UNITKEY
					WHERE
						1=1

						{$filter}
				) X
				{$where}
			");
		}

	}
	
	public function adduser($set = []){
		print_r($set);
		$this->db->insert('WEBUSER', $set);
		return $this->db->affected_rows();
	}

	public function update($userid, $set = [])
	{
		$this->db->where('userid', $userid);
		$this->db->update('WEBUSER', $set);
		return $this->db->affected_rows();
	}
	
	public function updateuser($where, $set = []){
		$this->db->where($where);
		$this->db->update('WEBUSER', $set);
		return $this->db->affected_rows();
	}
	
	public function getUnitName($unitkey)
	{
		return $this->db->query("SELECT NMUNIT FROM DAFTUNIT WHERE UNITKEY = ?", $unitkey)->row_array()['NMUNIT'];
	}
	
	public function getAll($filter = NULL, $offset = NULL, $count = FALSE)
	{
		if($count)
		{
			return $this->db->query("
				SELECT COUNT(U.USERID) AS TOTAL_ROW
				FROM
					WEBUSER AS U
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
						ROW_NUMBER() OVER(ORDER BY U.USERID) AS ROWNUM,
						U.USERID,
						U.NIP,
						U.NAMA,
						DU.KDUNIT
					FROM
						WEBUSER AS U
						LEFT JOIN DAFTUNIT AS DU ON U.UNITKEY = DU.UNITKEY
					WHERE
						1=1
						{$filter}
				) X
				{$where}
			");
		}
	}
}
