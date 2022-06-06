<?php
class M_subkegiatan_a extends CI_Model {
    // public function getAll()
    // {
    //     $this->db->from('MSUBKEGRKPD');
    //     $query = $this->db->get();
    //     return $query;
    // }

    public function getAll($filter = NULL, $offset = NULL, $count = FALSE)
	{
		if($count)
			{
				return $this->db->query("
					SELECT COUNT(MK.SUBKEGRKPDKEY) AS TOTAL_ROW
					FROM
						MSUBKEGRKPD MK
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
					ROW_NUMBER() OVER(ORDER BY SUBKEGRKPDKEY ASC, MK.NUSUBKEG ASC) AS ROWNUM,
					MK.SUBKEGRKPDKEY,
					MK.KEGRKPDKEY,
					MK.NUSUBKEG,
					MK.NMSUBKEG
				FROM
					MSUBKEGRKPD MK

				WHERE
					1 = 1
					{$filter}

			) X
					{$where}

			"	);

	}
	}
}
