<?php
class M_subkegiatan extends CI_Model {

	private $KDTAHUN = NULL;
	private $KDTAHAP = NULL;

	function __construct()
	{
		$this->KDTAHUN = $this->session->KDTAHUN;
		$this->KDTAHAP = $this->session->KDTAHAP;
	}

  public function getAll($filter = NULL, $offset = NULL, $count = FALSE)
  {
    if($count)
    {
      return $this->db->query("
      SELECT COUNT(K.SUBKEGRKPDKEY) AS TOTAL_ROW
      FROM
        SUBKEGRKPD K
      LEFT JOIN MSUBKEGRKPD MK ON K.SUBKEGRKPDKEY = MK.SUBKEGRKPDKEY
        AND K.KEGRKPDKEY = MK.KEGRKPDKEY
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
          ROW_NUMBER() OVER(ORDER BY MK.NUSUBKEG) AS ROWNUM,
          K.SUBKEGRKPDKEY,
          MK.NUSUBKEG,
          MK.NMSUBKEG,
          K.PAGUTIF,
          K.TARGET,
          K.TARGETSEBELUM,
          K.LOKASI,
          IS_RES_GENDER,
		      IS_SPM,
          IS_PKD,
          CASE WHEN K.TGLVALID IS NULL THEN '' ELSE CONVERT(VARCHAR(10),K.TGLVALID,105) END AS TGLVALID
        FROM
          SUBKEGRKPD K
        LEFT JOIN MSUBKEGRKPD MK ON K.SUBKEGRKPDKEY = MK.SUBKEGRKPDKEY
          AND K.KEGRKPDKEY = MK.KEGRKPDKEY
          AND K.KDTAHUN = MK.KDTAHUN
        WHERE
          1 = 1
            {$filter}
        ) X
        {$where}
      ");
    }
  }

  public function add($set = [])
  {
    $this->db->insert('SUBKEGRKPD', $set);
    return $this->db->affected_rows();
  }

  public function update($where, $set = [])
  {
    $this->db->where($where);
    $this->db->update('SUBKEGRKPD', $set);
    return $this->db->affected_rows();
  }



}
