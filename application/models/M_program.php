<?php
class M_program extends CI_Model {

	private $KDTAHUN = NULL;
	private $KDTAHAP = NULL;

	function __construct()
	{
		$this->KDTAHUN = $this->session->KDTAHUN;
		$this->KDTAHAP = $this->session->KDTAHAP;
	}

	public function getProgramName($pgrmrkpdkey)
	{
		return $this->db->query("SELECT NMPRGRM FROM MPGRMRKPD WHERE KDTAHUN = ? AND PGRMRKPDKEY = ?", [$this->KDTAHUN, $pgrmrkpdkey])->row_array()['NMPRGRM'];
	}

	public function add($set = [])
	{
		$this->db->insert('PGRRKPD', $set);
		return $this->db->affected_rows();
	}

	public function update($where, $set = [])
	{
		$this->db->where($where);
		$this->db->update('PGRRKPD', $set);
		return $this->db->affected_rows();
	}

	function getuser(){
		return $this->db->get('WEBUSER');
	}

	public function getAll($filter = NULL, $offset = NULL, $count = FALSE)
	{
		if($count)
		{
			return $this->db->query("
				SELECT COUNT(P.PGRMRKPDKEY) AS TOTAL_ROW
				FROM
					PGRRKPD P
				LEFT JOIN MPGRMRKPD MP ON P.PGRMRKPDKEY = MP.PGRMRKPDKEY AND P.KDTAHUN = MP.KDTAHUN
				LEFT JOIN DAFTUNIT D ON MP.UNITKEY = D.UNITKEY
				LEFT JOIN PRIOPPAS O ON P.PRIOPPASKEY = O.PRIOPPASKEY AND P.KDTAHUN = O.KDTAHUN
				LEFT JOIN SASARAN S ON P.IDSAS = S.IDSAS
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
						P.PGRMRKPDKEY,
						ISNULL(D.KDUNIT, '0.00.') AS KDUNIT,
						MP.NUPRGRM,
						MP.NMPRGRM,
						P.INDIKATOR,
						P.SASARAN,
						(SELECT DISTINCT SUBSTRING((SELECT ', ' +NMPRIOPPAS
 			FROM EPRIOPPAS EPR LEFT JOIN PRIOPPAS PR ON PR.KDTAHUN = EPR.KDTAHUN AND PR.PRIOPPASKEY = EPR.PRIOPPASKEY
 			WHERE EPR.KDTAHAP = P.KDTAHAP AND EPR.KDTAHUN = P.KDTAHUN AND EPR.PGRMRKPDKEY = P.PGRMRKPDKEY AND EPR.UNITKEY = P.UNITKEY
			FOR XML PATH('')),3,500)) AS NMPRIOPPAS,
			(SELECT DISTINCT SUBSTRING((SELECT ', ' +NMSAS
 			FROM
 			ESASARAN ESAS LEFT JOIN SASARAN SAS ON SAS.IDSAS = ESAS.IDSAS
 			WHERE ESAS.KDTAHAP = P.KDTAHAP AND ESAS.KDTAHUN = P.KDTAHUN AND ESAS.PGRMRKPDKEY = P.PGRMRKPDKEY AND ESAS.UNITKEY = P.UNITKEY
			FOR XML PATH('')),3,500)) AS NMSAS,

						P.TOLOKUR,
						P.TARGET,
						P.TARGETSEBELUM,
						CASE WHEN P.TGLVALID IS NULL THEN '' ELSE CONVERT(VARCHAR(10),P.TGLVALID,105) END AS TGLVALID
					FROM
						PGRRKPD P
					LEFT JOIN MPGRMRKPD MP ON P.PGRMRKPDKEY = MP.PGRMRKPDKEY AND P.KDTAHUN = MP.KDTAHUN
					LEFT JOIN DAFTUNIT D ON MP.UNITKEY = D.UNITKEY
					LEFT JOIN PRIOPPAS O ON P.PRIOPPASKEY = O.PRIOPPASKEY AND P.KDTAHUN = O.KDTAHUN
					LEFT JOIN SASARAN S ON P.IDSAS = S.IDSAS
					WHERE
						1 = 1
						{$filter}
				) X
				{$where}
			");
		}
	}

	public function prioritas_getall($filter = NULL, $offset = NULL, $count = FALSE){
		if($count)
		{
			return $this->db->query("
				SELECT COUNT(E.PRIOPPASKEY) AS TOTAL_ROW
				FROM
					EPRIOPPAS E
				LEFT JOIN PRIOPPAS P ON P.KDTAHUN = E.KDTAHUN AND P.PRIOPPASKEY = E.PRIOPPASKEY
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
						ROW_NUMBER() OVER(ORDER BY NOPRIOPPAS ASC) AS ROWNUM,
						PGRMRKPDKEY,
					KDTAHAP,
					E.PRIOPPASKEY,
					E.KDTAHUN,
					UNITKEY,
					NOPRIOPPAS,
					NMPRIOPPAS

					FROM
						EPRIOPPAS E
						LEFT JOIN PRIOPPAS P ON P.KDTAHUN = E.KDTAHUN AND P.PRIOPPASKEY = E.PRIOPPASKEY
					WHERE
						1 = 1
						{$filter}
				) X
				{$where}
			");
		}
	}


	public function sasaran_getall($filter = NULL, $offset = NULL, $count = FALSE){
		if($count)
		{
			return $this->db->query("
				SELECT COUNT(E.IDSAS) AS TOTAL_ROW
				FROM
					ESASARAN E
				LEFT JOIN SASARAN S ON  S.IDSAS = E.IDSAS
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
						ROW_NUMBER() OVER(ORDER BY E.IDSAS ASC) AS ROWNUM,
						PGRMRKPDKEY,
					KDTAHAP,
					E.PRIOPPASKEY,
					E.IDSAS,
					E.KDTAHUN,
					UNITKEY,
					NOSAS,
					NMSAS
					FROM ESASARAN E
					LEFT JOIN PRIOSAS P ON P.IDSAS = E.IDSAS AND P.KDTAHUN = E.KDTAHUN AND P.PRIOPPASKEY = E.PRIOPPASKEY
					LEFT JOIN  SASARAN S ON S.IDSAS = P.IDSAS
					WHERE
						1 = 1
						{$filter}
				) X
				{$where}
			");
		}
	}


	public function prioritas_provinsi_getall($filter = NULL, $offset = NULL, $count = FALSE){
		if($count)
		{
			return $this->db->query("
				SELECT COUNT(E.PRIOPROVKEY) AS TOTAL_ROW
				FROM
					EPRIOPPASPROV E
				LEFT JOIN PRIOPROVINSI P ON P.PRIOPROVKEY = E.PRIOPROVKEY
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
						ROW_NUMBER() OVER(ORDER BY NOPRIO ASC) AS ROWNUM,
						PGRMRKPDKEY,
					KDTAHAP,
					E.PRIOPROVKEY,
					E.KDTAHUN,
					UNITKEY,
					NOPRIO,
					NMPRIO

					FROM
						EPRIOPPASPROV E
						LEFT JOIN PRIOPROVINSI P ON P.PRIOPROVKEY = E.PRIOPROVKEY
					WHERE
						1 = 1
						{$filter}
				) X
				{$where}
			");
		}
	}

	public function sasaran_prov_getall($filter = NULL, $offset = NULL, $count = FALSE){
		if($count)
		{
			return $this->db->query("
				SELECT COUNT(E.IDSASPROV) AS TOTAL_ROW
				FROM
					ESASARANPROV E
				LEFT JOIN SASARANPROV S ON S.IDSASPROV = E.IDSASPROV
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
						ROW_NUMBER() OVER(ORDER BY E.IDSASPROV ASC) AS ROWNUM,
						PGRMRKPDKEY,
					KDTAHAP,
					E.PRIOPROVKEY,
					E.IDSASPROV,
					E.KDTAHUN,
					UNITKEY,
					NOSAS,
					NMSAS
					FROM ESASARANPROV E
					LEFT JOIN PRIOSASPROV P ON P.IDSASPROV = E.IDSASPROV AND P.KDTAHUN = E.KDTAHUN AND P.PRIOPROVKEY = E.PRIOPROVKEY
					LEFT JOIN  SASARANPROV S ON S.IDSASPROV = P.IDSASPROV
					WHERE
						1 = 1
						{$filter}
				) X
				{$where}
			");
		}
	}


	public function prioritas_nasional_getall($filter = NULL, $offset = NULL, $count = FALSE){
		if($count)
		{
			return $this->db->query("
				SELECT COUNT(E.PRIONASKEY) AS TOTAL_ROW
				FROM
					EPRIOPPASNAS E
				LEFT JOIN PRIONAS P ON P.PRIONASKEY = E.PRIONASKEY
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
						ROW_NUMBER() OVER(ORDER BY NUPRIO ASC) AS ROWNUM,
						PGRMRKPDKEY,
					KDTAHAP,
					E.PRIONASKEY,
					E.KDTAHUN,
					UNITKEY,
					NUPRIO,
					NMPRIO
					FROM
						EPRIOPPASNAS E
						LEFT JOIN PRIONAS P ON P.PRIONASKEY = E.PRIONASKEY
					WHERE
						1 = 1
						{$filter}
				) X
				{$where}
			");
		}
	}

	public function sasaran_nas_getall($filter = NULL, $offset = NULL, $count = FALSE){
		if($count)
		{
			return $this->db->query("
				SELECT COUNT(E.IDSASNAS) AS TOTAL_ROW
				FROM
					ESASARANNAS E
				LEFT JOIN SASARANNAS S ON S.KDTAHUN = E.KDTAHUN AND S.IDSASNAS = E.IDSASNAS
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
						ROW_NUMBER() OVER(ORDER BY E.IDSASNAS ASC) AS ROWNUM,
						PGRMRKPDKEY,
					KDTAHAP,
					E.PRIONASKEY,
					E.IDSASNAS,
					E.KDTAHUN,
					UNITKEY,
					NOSAS,
					NMSAS
					FROM ESASARANNAS E
					LEFT JOIN PRIOSASNAS P ON P.IDSASNAS = E.IDSASNAS AND P.KDTAHUN = E.KDTAHUN AND P.PRIONASKEY = E.PRIONASKEY
					LEFT JOIN  SASARANNAS S ON S.IDSASNAS = P.IDSASNAS
					WHERE
						1 = 1
						{$filter}
				) X
				{$where}
			");
		}
	}



}
