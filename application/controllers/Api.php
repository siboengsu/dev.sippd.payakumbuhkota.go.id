<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends CI_Controller {
	private $KDTAHUN = NULL;
	private $KDTAHAP = NULL;

	function __construct()
	{
		parent::__construct();

		$this->KDTAHUN = $this->session->KDTAHUN;
		$this->KDTAHAP = $this->session->KDTAHAP;
	}

	public function index()
	{

	}

	public function UNIT()
	{
		# /API/UNIT/

		$json = [];

		try
		{
			$rows = $this->db->query("
			SELECT
				UNITKEY,
				KDLEVEL,
				KDUNIT,
				NMUNIT,
				AKROUNIT,
				ALAMAT,
				TELEPON,
				TYPE
			FROM
				DAFTUNIT
			ORDER BY KDUNIT, NMUNIT")->result_array();

			foreach($rows as $k => $v)
			{
				$v = settrim($v);
				//foreach($v as $x)
				//{
				//	$json[$k][] = $x;
				//}

					$data['UNITKEY']  = $v['UNITKEY'];
					$data['KDLEVEL']  = $v['KDLEVEL'];
					$data['KDUNIT']  = $v['KDUNIT'];
					$data['NMUNIT']  = $v['NMUNIT'];
					$data['AKROUNIT']  = $v['AKROUNIT'];
					$data['ALAMAT']  = $v['ALAMAT'];
					$data['TELEPON']  = $v['TELEPON'];
					$data['TYPE']  = $v['TYPE'];
					$json[$k] =$data;
					//$json[$k][] = $data;

			}
			print_r($json);
		}
		catch (Exception $e)
		{
			$json[] = $e->getMessage();
		}

		$this->output->set_content_type('application/json')->set_output(json_encode($json));
	}

	public function URUSAN($unitkey = '', $uruskey = '')
	{
		# /API/URUSAN/

		$json = [];
		$data1 = Array();

		try
		{
			$where = '';
			$params = [];

			if( ! in_array($unitkey, ['', 'ALL']))
			{
				$where .= " AND UNITKEY = ? ";
				$params[] = $unitkey;
			}
			if( ! in_array($uruskey, ['', 'ALL']))
			{
				$where .= " AND URUSKEY = ? ";
				$params[] = $uruskey;
			}

			$rows = $this->db->query("
			SELECT
				UNITKEY,
				(SELECT NMUNIT FROM DAFTUNIT WHERE UNITKEY = U.UNITKEY )AS NAMUNIT,
				URUSKEY,
				(SELECT NMUNIT FROM DAFTUNIT WHERE UNITKEY = U.URUSKEY ) AS NAMAURUSAN
			FROM
				URUSANUNIT U
			WHERE
				1 = 1
				$where
			ORDER BY UNITKEY, URUSKEY", $params)->result_array();

			foreach($rows as $k => $v)
			{
				$v = settrim($v);
				//foreach($v as $x)
				//{
				//	$json[$k][] = $x;
				//}

				$data1['UNITKEY']  = $v['UNITKEY'];
				$data1['NAMAUNIT']  = $v['NAMUNIT'];
				$data1['URUSKEY']  = $v['URUSKEY'];
				$data1['URUSAN']  = $v['NAMAURUSAN'];
				$json[$k] = $data1;
			}
		}
		catch (Exception $e)
		{
			$json[] = $e->getMessage();
		}

		$this->output->set_content_type('application/json')->set_output(json_encode($json));
	}

	public function PROGRAM($kdtahun = '', $unitkey = '', $pgrmrkpdkey = '')
	{
		# /API/PROGRAM/

		$json = [];
		$data2 = Array();

		try
		{
			$where = '';
			$params = [];

			if($kdtahun == '')
			{
				throw new Exception('Kode Tahun Dibutuhkan.');
			}
			else
			{
				$where .= " AND KDTAHUN = ? ";
				$params[] = $kdtahun;
			}


			if( ! in_array($unitkey, ['', 'ALL']))
			{
				$where .= ($unitkey == "NULL") ? " AND UNITKEY IS NULL " : " AND UNITKEY = ? ";
				$params[] = $unitkey;
			}
			if( ! in_array($pgrmrkpdkey, ['', 'ALL']))
			{
				$where .= " AND PGRMRKPDKEY = ? ";
				$params[] = $pgrmrkpdkey;
			}

			$rows = $this->db->query("
			SELECT
				KDTAHUN,
				UNITKEY,
				PGRMRKPDKEY,
				NUPRGRM,
				NMPRGRM
			FROM
				MPGRMRKPD
			WHERE
				1 = 1
				$where
			ORDER BY KDTAHUN, UNITKEY, PGRMRKPDKEY, NUPRGRM, NMPRGRM", $params)->result_array();

			foreach($rows as $k => $v)
			{
				$v = settrim($v);
				//foreach($v as $x)
				//{
				//	$json[$k][] = $x;
				//}

				$data2['KDTAHUN']  = $v['KDTAHUN'];
				$data2['UNITKEY']  = $v['UNITKEY'];
				$data2['PGRMRKPDKEY']  = $v['PGRMRKPDKEY'];
				$data2['NUPRGRM']  = $v['NUPRGRM'];
				$data2['NMPRGRM']  = $v['NMPRGRM'];
				$json[$k] = $data2;
			}
		}
		catch (Exception $e)
		{
			$json[] = $e->getMessage();
		}

		$this->output->set_content_type('application/json')->set_output(json_encode($json));
	}

	public function KEGIATAN($kdtahun = '', $kegrkpdkey = '')
	{
		# /API/KEGIATAN/

		$json = [];
		$data3 = Array();

		try
		{
			$where = '';
			$params = [];

			if($kdtahun == '')
			{
				throw new Exception('Kode Tahun Dibutuhkan.');
			}
			else
			{
				$where .= " AND KDTAHUN = ? ";
				$params[] = $kdtahun;
			}


			if( ! in_array($kegrkpdkey, ['','ALL']))
			{
				$where .= " AND KEGRKPDKEY = ? ";
				$params[] = $kegrkpdkey;
			}

			$rows = $this->db->query("
			SELECT
				KDTAHUN,
				KEGRKPDKEY,
				PGRMRKPDKEY,
				KDPERSPEKTIF,
				NUKEG,
				NMKEG
			FROM
				MKEGRKPD
			WHERE
				1 = 1
				$where
			ORDER BY KDTAHUN, KEGRKPDKEY, NUKEG, NMKEG", $params)->result_array();

			foreach($rows as $k => $v)
			{
				$v = settrim($v);
				//foreach($v as $x)
				//{
				//	$json[$k][] = $x;
				//}
				$data3['KDTAHUN']  = $v['KDTAHUN'];
				$data3['KEGRKPDKEY']  = $v['KEGRKPDKEY'];
				$data3['PGRMRKPDKEY']  = $v['PGRMRKPDKEY'];
				$data3['KDPERSPEKTIF']  = $v['KDPERSPEKTIF'];
				$data3['NUKEG']  = $v['NUKEG'];
				$data3['NMKEG']  = $v['NMKEG'];
				$json[$k] = $data3;
			}
		}
		catch (Exception $e)
		{
			$json[] = $e->getMessage();
		}

		$this->output->set_content_type('application/json')->set_output(json_encode($json));
	}

	public function REKENING($mtgkey = '')
	{
		# /API/REKENING/

		$json = [];

		try
		{
			$where = '';
			$params = [];

			if( ! in_array($mtgkey, ['', 'ALL']))
			{
				$where .= " AND MTGKEY = ? ";
				$params[] = $mtgkey;
			}

			$rows = $this->db->query("
			SELECT
				MTGKEY,
				MTGLEVEL,
				KDPER,
				NMPER,
				TYPE
			FROM
				MATANGR
			WHERE
				1 = 1
				$where
			ORDER BY KDPER, NMPER", $params)->result_array();

			foreach($rows as $k => $v)
			{
				$v = settrim($v);
				foreach($v as $x)
				{
					$json[$k][] = $x;
				}
			}
		}
		catch (Exception $e)
		{
			$json[] = $e->getMessage();
		}

		$this->output->set_content_type('application/json')->set_output(json_encode($json));
	}

	public function RKAHEAD($kdtahun = '', $kdtahap = '', $unitkey = '', $kegrkpdkey = '', $mtgkey = '')
	{
		# /API/RKAHEAD/18/4/37_/1_

		$json = [];

		try
		{
			if($kdtahun == '')
			{
				throw new Exception('Kode Tahun Dibutuhkan.');
			}
			elseif($kdtahap == '')
			{
				throw new Exception('Kode Tahap Dibutuhkan.');
			}
			else
			{
				$where = "
					WHERE
						R.KDTAHUN = ?
					AND R.KDTAHAP = ?
				";
				$params = [
					$kdtahun,
					$kdtahap
				];
			}

			if( ! in_array($unitkey, ['','ALL'])) {
				$where .= " AND R.UNITKEY = ? ";
				$params[] = $unitkey;
			}
			if( ! in_array($kegrkpdkey, ['','ALL'])) {
				$where .= " AND R.KEGRKPDKEY = ? ";
				$params[] = $kegrkpdkey;
			}
			if( ! in_array($mtgkey, ['','ALL'])) {
				$where .= " AND R.MTGKEY = ? ";
				$params[] = $mtgkey;
			}

			$rows = $this->db->query("
			SELECT
				R.UNITKEY,
				R.KEGRKPDKEY,
				R.MTGKEY,
				(CASE WHEN R.NILAI = 0 THEN 0 WHEN R.NILAI = NULL THEN 0 ELSE CAST(R.NILAI AS NUMERIC(32,2)) END) AS NILAI
			FROM PRARASKR R
			JOIN MKEGRKPD MK ON R.KEGRKPDKEY = MK.KEGRKPDKEY
			JOIN MATANGR T ON R.MTGKEY = T.MTGKEY AND  R.KDTAHUN = T.KDTAHUN
			JOIN DAFTUNIT U ON R.UNITKEY = U.UNITKEY
			{$where}
			ORDER BY
				R.UNITKEY,
				R.KEGRKPDKEY,
				R.MTGKEY
			", $params)->result_array();

			foreach($rows as $r)
			{
				$r = settrim($r);
				$json[$r['UNITKEY']][$r['KEGRKPDKEY']][$r['MTGKEY']] = (double)$r['NILAI'];
			}
		}
		catch (Exception $e)
		{
			$json[] = $e->getMessage();
		}

		$this->output->set_content_type('application/json')->set_output(json_encode($json));
	}

	public function RKADETAIL($kdtahun = '', $kdtahap = '', $unitkey = '', $kegrkpdkey = '', $mtgkey = '')
	{
		# /API/RKADETAIL/18/4/37_/1_

		$json = [];

		try
		{
			if($kdtahun == '')
			{
				throw new Exception('Kode Tahun Dibutuhkan.');
			}
			elseif($kdtahap == '')
			{
				throw new Exception('Kode Tahap Dibutuhkan.');
			}
			else
			{
				$where = "
					WHERE
						RD.KDTAHUN = ?
					AND RD.KDTAHAP = ?
				";
				$params = [
					$kdtahun,
					$kdtahap
				];
			}

			if( ! in_array($unitkey, ['','ALL'])) {
				$where .= " AND RD.UNITKEY = ? ";
				$params[] = $unitkey;
			}
			if( ! in_array($kegrkpdkey, ['','ALL'])) {
				$where .= " AND RD.KEGRKPDKEY = ? ";
				$params[] = $kegrkpdkey;
			}
			if( ! in_array($mtgkey, ['','ALL'])) {
				$where .= " AND RD.MTGKEY = ? ";
				$params[] = $mtgkey;
			}

			$rows = $this->db->query("
			SELECT
				RD.UNITKEY,
				RD.KEGRKPDKEY,
				RD.MTGKEY,
				RD.KDNILAI,
				RD.TYPE,
				RD.KDJABAR,
				RD.URAIAN,
				(CASE WHEN RD.JUMBYEK = 0 THEN 0 WHEN RD.JUMBYEK = NULL THEN 0 ELSE CAST(RD.JUMBYEK AS NUMERIC(32,2)) END) AS JUMBYEK,
				RD.SATUAN,
				(CASE WHEN RD.TARIF = 0 THEN 0 WHEN RD.TARIF = NULL THEN 0 ELSE CAST(RD.TARIF AS NUMERIC(32,2)) END) AS TARIF,
				(CASE WHEN RD.SUBTOTAL = 0 THEN 0 WHEN RD.SUBTOTAL = NULL THEN 0 ELSE CAST(RD.SUBTOTAL AS NUMERIC(32,2)) END) AS SUBTOTAL,
				RD.KDSSH
			FROM PRARASKDETR RD
			JOIN PRARASKR R ON
					RD.KDTAHUN = R.KDTAHUN
				AND RD.KDTAHAP = R.KDTAHAP
				AND RD.UNITKEY = R.UNITKEY
				AND RD.KEGRKPDKEY = R.KEGRKPDKEY
				AND RD.MTGKEY = R.MTGKEY
			JOIN MKEGRKPD MK ON RD.KEGRKPDKEY = MK.KEGRKPDKEY
			JOIN MATANGR T ON RD.MTGKEY = T.MTGKEY AND T.KDTAHUN = RD.KDTAHUN
			JOIN DAFTUNIT U ON RD.UNITKEY = U.UNITKEY
			{$where}
			ORDER BY
				RD.UNITKEY,
				RD.KEGRKPDKEY,
				RD.MTGKEY,
				RD.KDJABAR
			", $params)->result_array();

			foreach($rows as $r)
			{
				$r = settrim($r);
				$json[$r['UNITKEY']][$r['KEGRKPDKEY']][$r['MTGKEY']][] = [
					$r['KDNILAI'],
					$r['TYPE'],
					$r['KDJABAR'],
					$r['URAIAN'],
					(double)$r['JUMBYEK'],
					$r['SATUAN'],
					(double)$r['TARIF'],
					(double)$r['SUBTOTAL'],
					$r['KDSSH']
				];
			}
		}
		catch (Exception $e)
		{
			$json[] = $e->getMessage();
		}

		$this->output->set_content_type('application/json')->set_output(json_encode($json));
	}

	//POST DATA RANKHIR RKPD
	function postRKPDBangda($postdata){

		$bearer = "3f872ef07f61e81ef0dcdcaf90f77a46";
		$tahun = $this->db->query("SELECT * FROM TAHUN WHERE KDTAHUN = '{$this->KDTAHUN}'")->row_array()['NMTAHUN'] ;

		$opts = array(
		  'ssl' => array(
			'verify_peer' => false,
		  ),
		  'http'=>array(
			'ignore_errors' => true,
			'method'=>  "POST",
			'header'=>  "Accept: application/json\r\n" .
				  "Authorization: Bearer {$bearer}\r\n" .
				  "Content-Type: application/json\r\n",
			'content' => $postdata
		  )
		);


		$context = stream_context_create($opts);
		$result = file_get_contents("https://sipd.go.id/run/serv/push.php?tahun=$tahun&kodepemda=1376", false, $context);
		$result = json_encode($http_response_header,JSON_FORCE_OBJECT|JSON_PRETTY_PRINT)."\n".$result;
		print_r($result);
    return $result;
  }

  //POST DATA  RANWAL RKPD

	{

		$bearer = "3f872ef07f61e81ef0dcdcaf90f77a46";
		$tahun = $this->db->query("SELECT * FROM TAHUN WHERE KDTAHUN = '{$this->KDTAHUN}'")->row_array()['NMTAHUN'] ;

		$opts = array(
		  'ssl' => array(
			'verify_peer' => false,
		  ),
		  'http'=>array(
			'ignore_errors' => true,
			'method'=>  "POST",
			'header'=>  "Accept: application/json\r\n" .
				  "Authorization: Bearer {$bearer}\r\n" .
				  "Content-Type: application/json\r\n",
			'content' => $postdataranwal
		  )
		);


		$context = stream_context_create($opts);
		$result = file_get_contents("https://sipd.go.id/run/serv/push_ranwal.php?tahun=$tahun&kodepemda=1376", false, $context);
		$result = json_encode($http_response_header,JSON_FORCE_OBJECT|JSON_PRETTY_PRINT)."\n".$result;
		print_r($result);
    return $result;
  }


  //POST DATA RANCANGAN RKPD
  function postRancanganBangda($postdatarancangan)
	{

		$bearer = "3f872ef07f61e81ef0dcdcaf90f77a46";
		$tahun = $this->db->query("SELECT * FROM TAHUN WHERE KDTAHUN = '{$this->KDTAHUN}'")->row_array()['NMTAHUN'] ;

		$opts = array(
		  'ssl' => array(
			'verify_peer' => false,
		  ),
		  'http'=>array(
			'ignore_errors' => true,
			'method'=>  "POST",
			'header'=>  "Accept: application/json\r\n" .
				  "Authorization: Bearer {$bearer}\r\n" .
				  "Content-Type: application/json\r\n",
			'content' => $postdatarancangan
		  )
		);


		$context = stream_context_create($opts);
		$result = file_get_contents("https://sipd.go.id/run/serv/push_rancangan.php?tahun=$tahun&kodepemda=1376", false, $context);
		$result = json_encode($http_response_header,JSON_FORCE_OBJECT|JSON_PRETTY_PRINT)."\n".$result;
		print_r($result);
    return $result;
  }

	public function postkemendagri()
		{
			$dataBappeda = array();

			$unit = $this->db->query("
			SELECT UNITKEY, KDLEVEL, KDUNIT, NMUNIT,
			(SELECT KDUNIT FROM DAFTUNIT D WHERE KDLEVEL = 2 AND KDUNIT = SUBSTRING(F.KDUNIT,0,9) ) AS KD1,
			(SELECT NMUNIT FROM DAFTUNIT D WHERE KDLEVEL = 2 AND KDUNIT = SUBSTRING(F.KDUNIT,0,9)) AS NM1,
			(SELECT NMUNIT FROM DAFTUNIT D WHERE KDLEVEL = 1 AND KDUNIT = SUBSTRING(F.KDUNIT,0,6)) AS URAIANURUSAN
			 FROM DAFTUNIT F WHERE KDLEVEL NOT IN (1,2) AND UNITKEY NOT IN ('40_','55_') ORDER BY KDUNIT ASC")->result_array();

			foreach($unit as $u){
				$UNITKEY 	= $u['UNITKEY'];

				$DATAPEJABAT = $this->db->query("SELECT A.NIP,P.NAMA,P.JABATAN, PANGKAT
												FROM ATASBEND A
												LEFT JOIN PEGAWAI P ON P.NIP = A.NIP
												LEFT JOIN GOLONGAN G ON G.KDGOL = P.KDGOL
												WHERE A.UNITKEY = '{$UNITKEY}'")->result_array();
				foreach ($DATAPEJABAT as $PEJABAT){
					$KEPALANIP	=  $PEJABAT['NIP'];
								$KEPALANAMA	=  $PEJABAT['NAMA'];
								$KEPALAJABATAN	=  $PEJABAT['JABATAN'];
								$KEPALAPANGKAT	=  $PEJABAT['PANGKAT'];}
				$b=array();
				$DATAPILIHANBIDANG = $this->db->query("SELECT KDUNIT FROM URUSANUNIT U LEFT JOIN DAFTUNIT D ON D.UNITKEY = U.URUSKEY WHERE U.UNITKEY = '{$UNITKEY}'")->result_array();
				foreach ($DATAPILIHANBIDANG as $BIDANG){
							$b[]	=  $BIDANG['KDUNIT'];
				}

				$tahun1 = $this->db->query("SELECT * FROM TAHUN WHERE KDTAHUN = '{$this->KDTAHUN}'")->row_array()['NMTAHUN'] ;


				$a['kodepemda']	 = "1376";
				$a['tahun']		 	= $tahun1;
				$a['kodebidang'] 		= $u['KD1'];
				$a['uraibidang'] 		= $u['NM1'];
				$a['kodeskpd']			 = $u['KDUNIT'];
				$a['uraiskpd']	 = $u['NMUNIT'];
				$a['pejabat'] 	 = array(
									'kepalanip'   =>  $KEPALANIP,
									'kepalanama'   => $KEPALANAMA,
									'kepalajabatan'   => $KEPALAJABATAN,
									'kepalapangkat'   => $KEPALAPANGKAT
									);
				$a['pilihanbidang']= $b;
				$a['uraiurusan'] = $u['URAIANURUSAN'];
				$a['program'] = array();

				$c = array();
				$DATAPROGRAM = $this->db->query("
					SELECT * FROM
						(
							SELECT

								P.PGRMRKPDKEY,
								ISNULL(D.KDUNIT, '0.00.') AS KDUNIT,
								MP.NUPRGRM,
								MP.NMPRGRM,
								P.INDIKATOR,
								P.SASARAN,
								O.NMPRIOPPAS,
								S.NMSAS,
								P.TOLOKUR,
								P.TARGET,
								(SELECT SUM(PAGUTIF) FROM KEGRKPD WHERE KDTAHUN = '{$this->KDTAHUN}' AND KDTAHAP = '{$this->KDTAHAP}' AND UNITKEY = P.UNITKEY AND PGRMRKPDKEY = P.PGRMRKPDKEY) AS PAGUTIF,
								(SELECT SUM(PAGUPLUS) FROM KEGRKPD WHERE KDTAHUN = '{$this->KDTAHUN}' AND KDTAHAP = '{$this->KDTAHAP}' AND UNITKEY = P.UNITKEY AND PGRMRKPDKEY = P.PGRMRKPDKEY) AS PAGUPLUS,
								CASE WHEN P.TGLVALID IS NULL THEN '' ELSE CONVERT(VARCHAR(10),P.TGLVALID,105) END AS TGLVALID
							FROM
								PGRRKPD P
							LEFT JOIN MPGRMRKPD MP ON P.PGRMRKPDKEY = MP.PGRMRKPDKEY AND P.KDTAHUN = MP.KDTAHUN
							LEFT JOIN DAFTUNIT D ON MP.UNITKEY = D.UNITKEY
							LEFT JOIN PRIOPPAS O ON P.PRIOPPASKEY = O.PRIOPPASKEY AND P.KDTAHUN = O.KDTAHUN
							LEFT JOIN SASARAN S ON P.IDSAS = S.IDSAS
							WHERE
								1 = 1 AND
								P.KDTAHUN = '{$this->KDTAHUN}'
								AND KDTAHAP =  '{$this->KDTAHAP}'
								AND P.UNITKEY = '{$UNITKEY}'

						) X ")->result_array();

						foreach ($DATAPROGRAM as $PROG){
							$pgrmrkpdkey = $PROG['PGRMRKPDKEY'];
							$noprg = str_replace(" ", "", $PROG['NUPRGRM']);
									$c['kodebidang']					= $u['KD1'];
									$c['uraibidang']					= $u['NM1'];
									$c['kodeprogram']					=  $PROG['KDUNIT'] . $noprg ;
									$c['uraiprogram']					=  $PROG['NMPRGRM'];
									$c['prioritas']						=  array([
																				'prioritasdaerah'	=> $PROG['NMPRIOPPAS']]
																			);
									$c['capaian']	= array([
												'kodeindikator' 		=>	$PROG['SASARAN'],
												'tolokukur'				=>  $PROG['INDIKATOR'],
												'satuan'				=>  '',
												'real_p3'				=> 0,
												'pagu_p3' 				=> 0,
												'real_p2' 				=> 0,
												'pagu_p2'		 		=> 0,
												'real_p1' 				=> 0,
												'pagu_p1' 				=>0,
												'target' 				=> $PROG['TARGET'],
												'pagu' 					=> number_format($PROG['PAGUTIF'], 0, ',', ''),
												'pagu_p' 				=> 0,
												'target_n1' 			=> "",
												'pagu_n1' 				=>  number_format($PROG['PAGUPLUS'], 0, ',', '')
									]);
									$c['kegiatan']	=  array();
									$DATAKEGIATAN = $this->db->query("
										SELECT* FROM
										(
										SELECT K.KEGRKPDKEY,
											ISNULL(D.KDUNIT, '0.00.') AS KDUNIT,
											NUPRGRM,
											NUKEG,
											NMKEG,
											PAGUTIF,
											PAGUPLUS,
											NMPRIOPPAS,
											LOKASI,
											KK.TARGET,
											KK.TARGET1,
											URJKK,
											KK.TOLOKUR
										FROM
											KEGRKPD K
											LEFT JOIN MKEGRKPD MP ON MP.KEGRKPDKEY = K.KEGRKPDKEY AND  MP.KDTAHUN = K.KDTAHUN
											LEFT JOIN DAFTUNIT D ON D.UNITKEY = K.UNITKEY
											LEFT JOIN MPGRMRKPD MK ON MK.PGRMRKPDKEY = K.PGRMRKPDKEY AND MK.KDTAHUN = K.KDTAHUN
											LEFT JOIN PGRRKPD P ON P.PGRMRKPDKEY = K.PGRMRKPDKEY AND P.KDTAHUN = K.KDTAHUN AND P.KDTAHAP = K.KDTAHAP AND P.UNITKEY = K. UNITKEY
											LEFT JOIN PRIOPPAS O ON P.PRIOPPASKEY = O.PRIOPPASKEY AND P.KDTAHUN = O.KDTAHUN
											LEFT JOIN KINKEGRKPD KK ON KK.UNITKEY = K.UNITKEY AND KK.KDTAHUN = K. KDTAHUN AND KK.KDTAHAP = K.KDTAHAP AND KK.KEGRKPDKEY = K.KEGRKPDKEY
											LEFT JOIN JKINKEG J ON J.KDJKK = KK.KDJKK
											WHERE 1 = 1 AND
											K.KDTAHUN = '{$this->KDTAHUN}'
											AND K.KDTAHAP = '{$this->KDTAHAP}'
											AND K.UNITKEY = '{$UNITKEY}'
											AND KK.KDJKK = '02'
											AND K.PGRMRKPDKEY = '{$pgrmrkpdkey}'
											) X ")->result_array();

							foreach ($DATAKEGIATAN as $KEG){
								$KEGRKPDKEY 	  		= $KEG['KEGRKPDKEY'];
								$nuprogkeg 				= str_replace(" ", "", $KEG['NUPRGRM']);
								$d['kodekegiatan']	= $KEG['KDUNIT'] . $nuprogkeg . $KEG['NUKEG'];
								$d['uraikegiatan'] 	= $KEG['NMKEG'];
								$d['pagu'] 			= number_format($KEG['PAGUTIF'], 0, ',', '');
								$d['pagu_p'] 			= 0;
								$d['sumberdana']		= array();

								$DATADANA = $this->db->query("SELECT K.KDDANA, NMDANA, NILAI FROM KEGRKPDDANA K LEFT JOIN JDANA J ON J.KDDANA = K.KDDANA WHERE KDTAHUN = '{$this->KDTAHUN}' AND KDTAHAP = '{$this->KDTAHAP}' AND  KEGRKPDKEY = '{$KEGRKPDKEY}' AND UNITKEY = '{$UNITKEY}'")->result_array();
								foreach ($DATADANA as $DANA){
									$kodesumberdana 			= str_replace(" ", "", $DANA['KDDANA']);
									$e['pagu']					=  number_format($DANA['NILAI'], 0, ',', '');
									$e['sumberdana']			=  $DANA['NMDANA'];
									$e['kodesumberdana']		= $kodesumberdana ;
									array_push($d['sumberdana'], $e);
								}

								$d['prioritas']				=  array(['prioritasdaerah'	=> $KEG['NMPRIOPPAS']]);
								$d['lokasi']					= array([
																	'lokasi'		=> $KEG['LOKASI'],
																	'kodelokasi'	=>"",
																	'detaillokasi'	=>""]);

								$d['indikator']	= array([
									'kodeindikator' 			=>	$KEG['SASARAN'],
									'jenis'						=>	$KEG['URJKK'],
									'tolokukur'					=>  $KEG['TOLOKUR'],
									'satuan'					=>  '',
									'real_p3'					=> 0,
									'pagu_p3' 					=> 0,
									'real_p2' 					=> 0,
									'pagu_p2'		 			=> 0,
									'real_p1' 					=> 0,
									'pagu_p1' 					=>0,
									'target' 					=> $KEG['TARGET'],
									'pagu' 						=> number_format($KEG['PAGUTIF'], 0, ',', ''),
									'pagu_p' 					=> 0,
									'target_n1' 				=> $KEG['TARGET1'],
									'pagu_n1' 					=> number_format($KEG['PAGUPLUS'], 0, ',', '')
								]);
								array_push($c['kegiatan'], $d);

							}
						array_push(	$a['program']	, $c);
						}
					array_push($dataBappeda, $a);
			}

			echo json_encode($dataBappeda);

		//$data =  json_encode($dataBappeda);

	//$this->postBangda($data);


	}


	public function postkemendagri11()
		{
			$dataBappeda = array();

			$unit = $this->db->query("
			SELECT UNITKEY, KDLEVEL, KDUNIT, NMUNIT,
			(SELECT KDUNIT FROM DAFTUNIT D WHERE KDLEVEL = 2 AND KDUNIT = SUBSTRING(F.KDUNIT,0,9) ) AS KD1,
			(SELECT NMUNIT FROM DAFTUNIT D WHERE KDLEVEL = 2 AND KDUNIT = SUBSTRING(F.KDUNIT,0,9)) AS NM1,
			(SELECT NMUNIT FROM DAFTUNIT D WHERE KDLEVEL = 1 AND KDUNIT = SUBSTRING(F.KDUNIT,0,6)) AS URAIANURUSAN
			 FROM DAFTUNIT F WHERE KDLEVEL NOT IN (1,2) AND UNITKEY NOT IN ('40_','55_') ORDER BY KDUNIT ASC")->result_array();

			foreach($unit as $u){
				$UNITKEY 	= $u['UNITKEY'];

				$DATAPEJABAT = $this->db->query("SELECT A.NIP,P.NAMA,P.JABATAN, PANGKAT
												FROM ATASBEND A
												LEFT JOIN PEGAWAI P ON P.NIP = A.NIP
												LEFT JOIN GOLONGAN G ON G.KDGOL = P.KDGOL
												WHERE A.UNITKEY = '{$UNITKEY}'")->result_array();
				foreach ($DATAPEJABAT as $PEJABAT){
					$KEPALANIP	=  $PEJABAT['NIP'];
								$KEPALANAMA	=  $PEJABAT['NAMA'];
								$KEPALAJABATAN	=  $PEJABAT['JABATAN'];
								$KEPALAPANGKAT	=  $PEJABAT['PANGKAT'];}
				$b=array();
				$DATAPILIHANBIDANG = $this->db->query("SELECT KDUNIT FROM URUSANUNIT U LEFT JOIN DAFTUNIT D ON D.UNITKEY = U.URUSKEY WHERE U.UNITKEY = '{$UNITKEY}'")->result_array();
				foreach ($DATAPILIHANBIDANG as $BIDANG){
							$b[]	=  $BIDANG['KDUNIT'];
				}

				$tahun1 = $this->db->query("SELECT * FROM TAHUN WHERE KDTAHUN = '{$this->KDTAHUN}'")->row_array()['NMTAHUN'] ;


				$a['"kodepemda"']	 = "1376";
				$a['"tahun"']		 	= $tahun1;
				$a['"kodebidang"'] 		= $u['KD1'];
				$a['"uraibidang"'] 		= $u['NM1'];
				$a['"kodeskpd"']			 = $u['KDUNIT'];
				$a['"uraiskpd"']	 = $u['NMUNIT'];
				$a['"pejabat"'] 	 = array(
									'"kepalanip"'   =>  $KEPALANIP,
									'"kepalanama"'   => $KEPALANAMA,
									'"kepalajabatan"'   => $KEPALAJABATAN,
									'"kepalapangkat"'   => $KEPALAPANGKAT
									);
				$a['"pilihanbidang"']= $b;
				$a['"uraiurusan"'] = $u['URAIANURUSAN'];
				$a['"program"'] = array();

				$c = array();
				$DATAPROGRAM = $this->db->query("
					SELECT * FROM
						(
							SELECT

								P.PGRMRKPDKEY,
								ISNULL(D.KDUNIT, '0.00.') AS KDUNIT,
								MP.NUPRGRM,
								MP.NMPRGRM,
								P.INDIKATOR,
								P.SASARAN,
								O.NMPRIOPPAS,
								S.NMSAS,
								P.TOLOKUR,
								P.TARGET,
								(SELECT SUM(PAGUTIF) FROM KEGRKPD WHERE KDTAHUN = '{$this->KDTAHUN}' AND KDTAHAP = '{$this->KDTAHAP}' AND UNITKEY = P.UNITKEY AND PGRMRKPDKEY = P.PGRMRKPDKEY) AS PAGUTIF,
								(SELECT SUM(PAGUPLUS) FROM KEGRKPD WHERE KDTAHUN = '{$this->KDTAHUN}' AND KDTAHAP = '{$this->KDTAHAP}' AND UNITKEY = P.UNITKEY AND PGRMRKPDKEY = P.PGRMRKPDKEY) AS PAGUPLUS,
								CASE WHEN P.TGLVALID IS NULL THEN '' ELSE CONVERT(VARCHAR(10),P.TGLVALID,105) END AS TGLVALID
							FROM
								PGRRKPD P
							LEFT JOIN MPGRMRKPD MP ON P.PGRMRKPDKEY = MP.PGRMRKPDKEY AND P.KDTAHUN = MP.KDTAHUN
							LEFT JOIN DAFTUNIT D ON MP.UNITKEY = D.UNITKEY
							LEFT JOIN PRIOPPAS O ON P.PRIOPPASKEY = O.PRIOPPASKEY AND P.KDTAHUN = O.KDTAHUN
							LEFT JOIN SASARAN S ON P.IDSAS = S.IDSAS
							WHERE
								1 = 1 AND
								P.KDTAHUN = '{$this->KDTAHUN}'
								AND KDTAHAP =  '{$this->KDTAHAP}'
								AND P.UNITKEY = '{$UNITKEY}'

						) X ")->result_array();

						foreach ($DATAPROGRAM as $PROG){
							$pgrmrkpdkey = $PROG['PGRMRKPDKEY'];
							$noprg = str_replace(" ", "", $PROG['NUPRGRM']);
									$c['"kodebidang"']					= $u['KD1'];
									$c['"uraibidang"']					= $u['NM1'];
									$c['"kodeprogram"']					=  $PROG['KDUNIT'] . $noprg ;
									$c['"uraiprogram"']					=  $PROG['NMPRGRM'];
									$c['"prioritas"']						=  array([
																				'"prioritasdaerah"'	=> $PROG['NMPRIOPPAS']]
																			);
									$c['"capaian"']	= array([
												'"kodeindikator"' 		=>	$PROG['SASARAN'],
												'"tolokukur"'				=>  $PROG['INDIKATOR'],
												'"satuan"'				=>  '',
												'"real_p3"'				=> 0,
												'"pagu_p3"' 				=> 0,
												'"real_p2"' 				=> 0,
												'"pagu_p2"'		 		=> 0,
												'"real_p1"' 				=> 0,
												'"pagu_p1"' 				=>0,
												'"target"' 				=> $PROG['TARGET'],
												'"pagu"' 					=> number_format($PROG['PAGUTIF'], 0, ',', ''),
												'"pagu_p"' 				=> 0,
												'"target_n1"' 			=> "",
												'"pagu_n1"' 				=>  number_format($PROG['PAGUPLUS'], 0, ',', '')
									]);
									$c['"kegiatan"']	=  array();
									$DATAKEGIATAN = $this->db->query("
										SELECT* FROM
										(
										SELECT K.KEGRKPDKEY,
											ISNULL(D.KDUNIT, '0.00.') AS KDUNIT,
											NUPRGRM,
											NUKEG,
											NMKEG,
											PAGUTIF,
											PAGUPLUS,
											NMPRIOPPAS,
											LOKASI,
											KK.TARGET,
											KK.TARGET1,
											URJKK,
											KK.TOLOKUR
										FROM
											KEGRKPD K
											LEFT JOIN MKEGRKPD MP ON MP.KEGRKPDKEY = K.KEGRKPDKEY AND  MP.KDTAHUN = K.KDTAHUN
											LEFT JOIN DAFTUNIT D ON D.UNITKEY = K.UNITKEY
											LEFT JOIN MPGRMRKPD MK ON MK.PGRMRKPDKEY = K.PGRMRKPDKEY AND MK.KDTAHUN = K.KDTAHUN
											LEFT JOIN PGRRKPD P ON P.PGRMRKPDKEY = K.PGRMRKPDKEY AND P.KDTAHUN = K.KDTAHUN AND P.KDTAHAP = K.KDTAHAP AND P.UNITKEY = K. UNITKEY
											LEFT JOIN PRIOPPAS O ON P.PRIOPPASKEY = O.PRIOPPASKEY AND P.KDTAHUN = O.KDTAHUN
											LEFT JOIN KINKEGRKPD KK ON KK.UNITKEY = K.UNITKEY AND KK.KDTAHUN = K. KDTAHUN AND KK.KDTAHAP = K.KDTAHAP AND KK.KEGRKPDKEY = K.KEGRKPDKEY
											LEFT JOIN JKINKEG J ON J.KDJKK = KK.KDJKK
											WHERE 1 = 1 AND
											K.KDTAHUN = '{$this->KDTAHUN}'
											AND K.KDTAHAP = '{$this->KDTAHAP}'
											AND K.UNITKEY = '{$UNITKEY}'
											AND KK.KDJKK = '02'
											AND K.PGRMRKPDKEY = '{$pgrmrkpdkey}'
											) X ")->result_array();

							foreach ($DATAKEGIATAN as $KEG){
								$KEGRKPDKEY 	  		= $KEG['KEGRKPDKEY'];
								$nuprogkeg 				= str_replace(" ", "", $KEG['NUPRGRM']);
								$d['"kodekegiatan"']	= $KEG['KDUNIT'] . $nuprogkeg . $KEG['NUKEG'];
								$d['"uraikegiatan"'] 	= $KEG['NMKEG'];
								$d['"pagu"'] 			= number_format($KEG['PAGUTIF'], 0, ',', '');
								$d['"pagu_p"'] 			= 0;
								$d['"sumberdana"']		= array();

								$DATADANA = $this->db->query("SELECT K.KDDANA, NMDANA, NILAI FROM KEGRKPDDANA K LEFT JOIN JDANA J ON J.KDDANA = K.KDDANA WHERE KDTAHUN = '{$this->KDTAHUN}' AND KDTAHAP = '{$this->KDTAHAP}' AND  KEGRKPDKEY = '{$KEGRKPDKEY}' AND UNITKEY = '{$UNITKEY}'")->result_array();
								foreach ($DATADANA as $DANA){
									$kodesumberdana 			= str_replace(" ", "", $DANA['KDDANA']);
									$e['"pagu"']					=  number_format($DANA['NILAI'], 0, ',', '');
									$e['"sumberdana"']			=  $DANA['NMDANA'];
									$e['"kodesumberdana"']		= $kodesumberdana ;
									array_push($d['"sumberdana"'], $e);
								}

								$d['"prioritas"']				=  array(['"prioritasdaerah"'	=> $KEG['NMPRIOPPAS']]);
								$d['"lokasi"']					= array([
																	'"lokasi"'		=> $KEG['LOKASI'],
																	'"kodelokasi"'	=>"",
																	'"detaillokasi"'	=>""]);

								$d['"indikator"']	= array([
									'"kodeindikator"' 			=>	$KEG['SASARAN'],
									'"jenis"'						=>	$KEG['URJKK'],
									'"tolokukur"'					=>  $KEG['TOLOKUR'],
									'"satuan"'					=>  '',
									'"real_p3"'					=> 0,
									'"pagu_p3"' 					=> 0,
									'"real_p2"' 					=> 0,
									'"pagu_p2"'		 			=> 0,
									'"real_p1"' 					=> 0,
									'"pagu_p1"' 					=>0,
									'"target"' 					=> $KEG['TARGET'],
									'"pagu"' 						=> number_format($KEG['PAGUTIF'], 0, ',', ''),
									'"pagu_p"' 					=> 0,
									'"target_n1"' 				=> $KEG['TARGET1'],
									'"pagu_n1"' 					=> number_format($KEG['PAGUPLUS'], 0, ',', '')
								]);
								array_push($c['"kegiatan"'], $d);

							}
						array_push(	$a['"program"']	, $c);
						}
					array_push($dataBappeda, $a);
			}

			echo json_encode($dataBappeda);

		//$data =  json_encode($dataBappeda);

	//$this->postBangda($data);


	}

	public function post_ranwal_kinkeg_lama()
	{
		$dataBappeda = array();

		$unit = $this->db->query("
		SELECT UNITKEY, KDLEVEL,KDUNIT, NMUNIT,
		(SELECT KDUNIT FROM DAFTUNIT D WHERE KDLEVEL = 2 AND KDUNIT1 = SUBSTRING(F.KDUNIT1,0,5) ) AS KD1,
		(SELECT NMUNIT FROM DAFTUNIT D WHERE KDLEVEL = 2 AND KDUNIT1 = SUBSTRING(F.KDUNIT1,0,5)) AS NM1,
		(SELECT KDUNIT FROM DAFTUNIT D WHERE KDLEVEL = 1 AND KDUNIT1 = SUBSTRING(F.KDUNIT1,0,2)) AS KD2,
		(SELECT NMUNIT FROM DAFTUNIT D WHERE KDLEVEL = 1 AND KDUNIT1 = SUBSTRING(F.KDUNIT1,0,2)) AS URAIANURUSAN
		 FROM DAFTUNIT F WHERE KDLEVEL NOT IN (1,2)  AND TYPE = 'D'
		  ORDER BY KDUNIT ASC")->result_array();

		foreach($unit as $u){
			$UNITKEY 	= $u['UNITKEY'];
			$KDINDIKTOR = $u['KD2'];
			$DATAPEJABAT = $this->db->query("SELECT A.NIP,P.NAMA,P.JABATAN, PANGKAT
											FROM ATASBEND A
											LEFT JOIN PEGAWAI P ON P.NIP = A.NIP
											LEFT JOIN GOLONGAN G ON G.KDGOL = P.KDGOL
											LEFT JOIN DAFTUNIT D ON D.UNITKEY = A.UNITKEY
											WHERE A.UNITKEY = '{$UNITKEY}'")->result_array();
			foreach ($DATAPEJABAT as $PEJABAT){
				$KEPALANIP	=  $PEJABAT['NIP'];
							$KEPALANAMA	=  $PEJABAT['NAMA'];
							$KEPALAJABATAN	=  $PEJABAT['JABATAN'];
							$KEPALAPANGKAT	=  $PEJABAT['PANGKAT'];}
			$b=array();
			$DATAPILIHANBIDANG = $this->db->query("SELECT KDUNIT FROM URUSANUNIT U LEFT JOIN DAFTUNIT D ON D.UNITKEY = U.URUSKEY WHERE U.UNITKEY = '{$UNITKEY}'")->result_array();
			foreach ($DATAPILIHANBIDANG as $BIDANG){
						$b[]	=  $BIDANG['KDUNIT'];
			}

			$tahun1 = $this->db->query("SELECT * FROM TAHUN WHERE KDTAHUN = '{$this->KDTAHUN}'")->row_array()['NMTAHUN'] ;


			$a['kodepemda']	 = "1376";
			$a['tahun']		 	= $tahun1;
			$a['kodebidang'] 		= $u['KD1'];
			$a['uraibidang'] 		= $u['NM1'];
			$a['kodeskpd']			 = $u['KDUNIT'];
			$a['uraiskpd']	 = $u['NMUNIT'];
			$a['pejabat'] 	 = array(
								'kepalanip'   =>  $KEPALANIP,
								'kepalanama'   => $KEPALANAMA,
								'kepalajabatan'   => $KEPALAJABATAN,
								'kepalapangkat'   => $KEPALAPANGKAT
								);
			$a['pilihanbidang']= $b;
			$a['uraiurusan'] = $u['URAIANURUSAN'];
			$a['program'] = array();

			$c = array();
			$DATAPROGRAM = $this->db->query("
			SELECT * FROM
								(
								SELECT

									P.PGRMRKPDKEY,
									ISNULL(D.KDUNIT, '0.00') AS KDUNIT,
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
									(SELECT SUM(PAGUTIF) FROM KEGRKPD WHERE KDTAHUN = '{$this->KDTAHUN}' AND KDTAHAP = '{$this->KDTAHAP}' AND UNITKEY = P.UNITKEY AND PGRMRKPDKEY = P.PGRMRKPDKEY) AS PAGUTIF,
									(SELECT SUM(PAGUPLUS) FROM KEGRKPD WHERE KDTAHUN =  '{$this->KDTAHUN}' AND KDTAHAP = '{$this->KDTAHAP}' AND UNITKEY = P.UNITKEY AND PGRMRKPDKEY = P.PGRMRKPDKEY) AS PAGUPLUS,
									CASE WHEN P.TGLVALID IS NULL THEN '' ELSE CONVERT(VARCHAR(10),P.TGLVALID,105) END AS TGLVALID
								FROM
									PGRRKPD P
								LEFT JOIN MPGRMRKPD MP ON P.PGRMRKPDKEY = MP.PGRMRKPDKEY AND P.KDTAHUN = MP.KDTAHUN
								LEFT JOIN DAFTUNIT D ON MP.UNITKEY = D.UNITKEY
								LEFT JOIN PRIOPPAS O ON P.PRIOPPASKEY = O.PRIOPPASKEY AND P.KDTAHUN = O.KDTAHUN
								LEFT JOIN SASARAN S ON P.IDSAS = S.IDSAS
								WHERE
									1 = 1 AND
									P.KDTAHUN =  '{$this->KDTAHUN}'
									AND P.KDTAHAP =  '{$this->KDTAHAP}'
									AND P.UNITKEY = '{$UNITKEY}')X ")->result_array();

					foreach ($DATAPROGRAM as $PROG){
						$pgrmrkpdkey = $PROG['PGRMRKPDKEY'];
						$noprg = str_replace(" ", "", $PROG['NUPRGRM']);
								$c['kodebidang']					= $u['KD1'];
								$c['uraibidang']					= $u['NM1'];
								$c['kodeprogram']					=  $PROG['KDUNIT'] . "." . $noprg ;
								$c['uraiprogram']					=  $PROG['NMPRGRM'];
								$c['prioritas']						=  array([
																			'prioritasdaerah'	=> $PROG['NMPRIOPPAS']]
																		);
								$c['capaian']	= array([
											'kodeindikator' 		=>	$KDINDIKTOR,
											'tolokukur'				=>  $PROG['INDIKATOR'],
											'satuan'				=>  '',
											'real_p3'				=> 0,
											'pagu_p3' 				=> 0,
											'real_p2' 				=> 0,
											'pagu_p2'		 		=> 0,
											'real_p1' 				=> 0,
											'pagu_p1' 				=>0,
											'target' 				=> $PROG['TARGET'],
											'pagu' 					=> number_format($PROG['PAGUTIF'], 0, ',', ''),
											'pagu_p' 				=> 0,
											'target_n1' 			=> "",
											'pagu_n1' 				=>  number_format($PROG['PAGUPLUS'], 0, ',', '')
								]);
								$c['kegiatan']	=  array();
								$DATAKEGIATAN = $this->db->query("
								SELECT* FROM
									(
									SELECT K.KEGRKPDKEY,
										ISNULL(D.KDUNIT, '0.00') AS KDUNIT,
										NUPRGRM,
										NUKEG,
										NMKEG,
										PAGUTIF,
										PAGUPLUS,
										(SELECT DISTINCT SUBSTRING((SELECT ', ' +NMPRIOPPAS
										FROM EPRIOPPAS EPR LEFT JOIN PRIOPPAS PR ON PR.KDTAHUN = EPR.KDTAHUN AND PR.PRIOPPASKEY = EPR.PRIOPPASKEY
										WHERE EPR.KDTAHAP = P.KDTAHAP AND EPR.KDTAHUN = P.KDTAHUN AND EPR.PGRMRKPDKEY = P.PGRMRKPDKEY AND EPR.UNITKEY = P.UNITKEY
										FOR XML PATH('')),3,500)) AS NMPRIOPPAS,
										(SELECT DISTINCT SUBSTRING((SELECT ', ' +NMSAS
										FROM
										ESASARAN ESAS LEFT JOIN SASARAN SAS ON SAS.IDSAS = ESAS.IDSAS
										WHERE ESAS.KDTAHAP = P.KDTAHAP AND ESAS.KDTAHUN = P.KDTAHUN AND ESAS.PGRMRKPDKEY = P.PGRMRKPDKEY AND ESAS.UNITKEY = P.UNITKEY
										FOR XML PATH('')),3,500)) AS NMSAS,
										LOKASI,
										P.SASARAN,
										KK.TARGET,
										KK.TARGET1,
										URJKK,
										KK.KDJKK,
										KK.TOLOKUR
									FROM
										KEGRKPD K
										LEFT JOIN MKEGRKPD MP ON MP.KEGRKPDKEY = K.KEGRKPDKEY AND  MP.KDTAHUN = K.KDTAHUN
										LEFT JOIN MPGRMRKPD MK ON MK.PGRMRKPDKEY = K.PGRMRKPDKEY AND MK.KDTAHUN = K.KDTAHUN
										LEFT JOIN DAFTUNIT D ON D.UNITKEY = MK.UNITKEY
										LEFT JOIN PGRRKPD P ON P.PGRMRKPDKEY = K.PGRMRKPDKEY AND P.KDTAHUN = K.KDTAHUN AND P.KDTAHAP = K.KDTAHAP AND P.UNITKEY = K. UNITKEY
										LEFT JOIN KINKEGRKPD KK ON KK.UNITKEY = K.UNITKEY AND KK.KDTAHUN = K. KDTAHUN AND KK.KDTAHAP = K.KDTAHAP AND KK.KEGRKPDKEY = K.KEGRKPDKEY
										LEFT JOIN JKINKEG J ON J.KDJKK = KK.KDJKK
										WHERE 1 = 1 AND

										K.KDTAHUN = '{$this->KDTAHUN}'
										AND K.KDTAHAP = '{$this->KDTAHAP}'
										AND K.UNITKEY = '{$UNITKEY}'
										AND KK.KDJKK = '02'
										AND K.PGRMRKPDKEY = '{$pgrmrkpdkey}'
										) X ")->result_array();

						foreach ($DATAKEGIATAN as $KEG){
							$KEGRKPDKEY 	  		= $KEG['KEGRKPDKEY'];
							$nuprogkeg 				= str_replace(" ", "", $KEG['NUPRGRM']);
							$d['kodekegiatan']	= $KEG['KDUNIT'] . "." . $nuprogkeg . "." . $KEG['NUKEG'];
							$d['uraikegiatan'] 	= $KEG['NMKEG'];
							$d['pagu'] 			= number_format($KEG['PAGUTIF'], 0, ',', '');
							$d['pagu_p'] 			= 0;
							$d['sumberdana']		= array();

							$DATADANA = $this->db->query("SELECT K.KDDANA, NMDANA, NILAI FROM KEGRKPDDANA K LEFT JOIN JDANA J ON J.KDDANA = K.KDDANA WHERE KDTAHUN = '{$this->KDTAHUN}' AND KDTAHAP = '{$this->KDTAHAP}' AND  KEGRKPDKEY = '{$KEGRKPDKEY}' AND UNITKEY = '{$UNITKEY}'")->result_array();
							foreach ($DATADANA as $DANA){
								$kodesumberdana 			= str_replace(" ", "", $DANA['KDDANA']);
								$e['pagu']					=  number_format($DANA['NILAI'], 0, ',', '');
								$e['sumberdana']			=  $DANA['NMDANA'];
								$e['kodesumberdana']		= $kodesumberdana ;
								array_push($d['sumberdana'], $e);
							}

							$d['prioritas']				=  array(['prioritasdaerah'	=> $KEG['NMPRIOPPAS']]);
							$d['lokasi']					= array([
																'lokasi'		=> $KEG['LOKASI'],
																'kodelokasi'	=>"",
																'detaillokasi'	=>""]);

							$d['indikator']	= array([
								'kodeindikator' 			=>	$KEG['KDJKK'],
								'jenis'						=>	$KEG['URJKK'],
								'tolokukur'					=>  $KEG['TOLOKUR'],
								'satuan'					=>  '',
								'real_p3'					=> 0,
								'pagu_p3' 					=> 0,
								'real_p2' 					=> 0,
								'pagu_p2'		 			=> 0,
								'real_p1' 					=> 0,
								'pagu_p1' 					=>0,
								'target' 					=> $KEG['TARGET'],
								'pagu' 						=> number_format($KEG['PAGUTIF'], 0, ',', ''),
								'pagu_p' 					=> 0,
								'target_n1' 				=> $KEG['TARGET1'],
								'pagu_n1' 					=> number_format($KEG['PAGUPLUS'], 0, ',', '')
							]);

										$d['subkegiatan']	=  array();
										$DATASUBKEGIATAN = $this->db->query("
										SELECT* FROM
											(
											SELECT K.SUBKEGRKPDKEY,
												ISNULL(D.KDUNIT, '0.00') AS KDUNIT,
												NUPRGRM,
												NUKEG,
												NUSUBKEG,
												NMSUBKEG,
												K.PAGUTIF,
												K.PAGUPLUS,
												(SELECT DISTINCT SUBSTRING((SELECT ', ' +NMPRIOPPAS
												FROM EPRIOPPAS EPR LEFT JOIN PRIOPPAS PR ON PR.KDTAHUN = EPR.KDTAHUN AND PR.PRIOPPASKEY = EPR.PRIOPPASKEY
												WHERE EPR.KDTAHAP = P.KDTAHAP AND EPR.KDTAHUN = P.KDTAHUN AND EPR.PGRMRKPDKEY = P.PGRMRKPDKEY AND EPR.UNITKEY = P.UNITKEY
												FOR XML PATH('')),3,500)) AS NMPRIOPPAS,
												(SELECT DISTINCT SUBSTRING((SELECT ', ' +NMSAS
												FROM
												ESASARAN ESAS LEFT JOIN SASARAN SAS ON SAS.IDSAS = ESAS.IDSAS
												WHERE ESAS.KDTAHAP = P.KDTAHAP AND ESAS.KDTAHUN = P.KDTAHUN AND ESAS.PGRMRKPDKEY = P.PGRMRKPDKEY AND ESAS.UNITKEY = P.UNITKEY
												FOR XML PATH('')),3,500)) AS NMSAS,
												K.LOKASI,
												KK.TARGET,
												KK.TARGET1,
												URJKK,
												KK.KDJKK,
												KK.TOLOKUR
											FROM
												SUBKEGRKPD K
												LEFT JOIN MSUBKEGRKPD MP ON MP.SUBKEGRKPDKEY = K.SUBKEGRKPDKEY AND  MP.KDTAHUN = K.KDTAHUN
												LEFT JOIN KEGRKPD M ON M.KEGRKPDKEY = K.KEGRKPDKEY AND M.KDTAHUN = K.KDTAHUN AND M.KDTAHAP = K.KDTAHAP AND M.UNITKEY = K.UNITKEY
												LEFT JOIN MKEGRKPD MKR ON MKR.KEGRKPDKEY = M.KEGRKPDKEY AND MKR.KDTAHUN = M.KDTAHUN
												LEFT JOIN PGRRKPD P ON P.PGRMRKPDKEY = M.PGRMRKPDKEY AND P.KDTAHUN = M.KDTAHUN AND P.KDTAHAP = M.KDTAHAP AND P.UNITKEY = M.UNITKEY
												LEFT JOIN MPGRMRKPD MK ON MK.PGRMRKPDKEY = P.PGRMRKPDKEY AND MK.KDTAHUN = P.KDTAHUN
												LEFT JOIN DAFTUNIT D ON D.UNITKEY = MK.UNITKEY
												LEFT JOIN SUBKINKEGRKPD KK ON KK.UNITKEY = K.UNITKEY AND KK.KDTAHUN = K.KDTAHUN AND KK.KDTAHAP = K.KDTAHAP AND KK.SUBKEGRKPDKEY = K.SUBKEGRKPDKEY
												LEFT JOIN JKINKEG J ON J.KDJKK = KK.KDJKK
												WHERE 1 = 1 AND
												K.KDTAHUN = '{$this->KDTAHUN}'
												AND K.KDTAHAP = '{$this->KDTAHAP}'
												AND K.UNITKEY = '{$UNITKEY}'
												AND KK.KDJKK = '02'
												AND K.KEGRKPDKEY = '{$KEGRKPDKEY}'
												) X ")->result_array();

								foreach ($DATASUBKEGIATAN as $SUBKEG){
									$SUBKEGRKPDKEY 	  		= $SUBKEG['SUBKEGRKPDKEY'];
									$nuprogkeg 				= str_replace(" ", "", $SUBKEG['NUPRGRM']);
									$j['kodesubkegiatan']	= $SUBKEG['KDUNIT']  . "." . $nuprogkeg  . "." . $SUBKEG['NUKEG']  . "." . $SUBKEG['NUSUBKEG'];
									$j['uraisubkegiatan'] 	= $SUBKEG['NMSUBKEG'];
									$j['pagu'] 			= number_format($SUBKEG['PAGUTIF'], 0, ',', '');
									$j['pagu_p'] 			= 0;
									$j['sumberdana']		= array();

									$DATASUBDANA = $this->db->query("SELECT K.KDDANA, NMDANA, NILAI FROM SUBKEGRKPDDANA K LEFT JOIN JDANA J ON J.KDDANA = K.KDDANA  WHERE KDTAHUN = '{$this->KDTAHUN}' AND KDTAHAP = '{$this->KDTAHAP}' AND  SUBKEGRKPDKEY = '{$SUBKEGRKPDKEY}' AND UNITKEY = '{$UNITKEY}'")->result_array();
									foreach ($DATASUBDANA as $DANA1){
										$kodesumberdana 			= str_replace(" ", "", $DANA1['KDDANA']);
										$h['pagu']					=  number_format($DANA1['NILAI'], 0, ',', '');
										$h['sumberdana']			=  $DANA1['NMDANA'];
										$h['kodesumberdana']		= $kodesumberdana ;
										array_push($j['sumberdana'], $h);
									}

									$j['prioritas']				=  array(['prioritasdaerah'	=> $SUBKEG['NMPRIOPPAS']]);
									$j['lokasi']					= array([
																		'lokasi'		=> $SUBKEG['LOKASI'],
																		'kodelokasi'	=>"",
																		'detaillokasi'	=>""]);

									$j['indikator']	= array([
										'kodeindikator' 			=>	$SUBKEG['KDJKK'],
										'jenis'						=>	$SUBKEG['URJKK'],
										'tolokukur'					=>  $SUBKEG['TOLOKUR'],
										'satuan'					=>  '',
										'real_p3'					=> 0,
										'pagu_p3' 					=> 0,
										'real_p2' 					=> 0,
										'pagu_p2'		 			=> 0,
										'real_p1' 					=> 0,
										'pagu_p1' 					=>0,
										'target' 					=> $SUBKEG['TARGET'],
										'pagu' 						=> number_format($SUBKEG['PAGUTIF'], 0, ',', ''),
										'pagu_p' 					=> 0,
										'target_n1' 				=> $SUBKEG['TARGET1'],
										'pagu_n1' 					=> number_format($SUBKEG['PAGUPLUS'], 0, ',', '')
									]);
									array_push($d['subkegiatan'], $j);

								}




							array_push($c['kegiatan'], $d);

						}
					array_push(	$a['program']	, $c);
					}
				array_push($dataBappeda, $a);
		}

	//	echo json_encode($dataBappeda);

	$data =  json_encode($dataBappeda);

$this->postRanwalBangda($data);


}

	public function post_ranwal()
	{
		$dataBappeda = array();

		$unit = $this->db->query("
		SELECT UNITKEY, KDLEVEL,KDUNIT, NMUNIT,
		(SELECT KDUNIT FROM DAFTUNIT D WHERE KDLEVEL = 2 AND KDUNIT1 = SUBSTRING(F.KDUNIT1,0,5) ) AS KD1,
		(SELECT NMUNIT FROM DAFTUNIT D WHERE KDLEVEL = 2 AND KDUNIT1 = SUBSTRING(F.KDUNIT1,0,5)) AS NM1,
		(SELECT KDUNIT FROM DAFTUNIT D WHERE KDLEVEL = 1 AND KDUNIT1 = SUBSTRING(F.KDUNIT1,0,2)) AS KD2,
		(SELECT NMUNIT FROM DAFTUNIT D WHERE KDLEVEL = 1 AND KDUNIT1 = SUBSTRING(F.KDUNIT1,0,2)) AS URAIANURUSAN
		 FROM DAFTUNIT F WHERE KDLEVEL NOT IN (1,2)  AND TYPE = 'D'
		  ORDER BY KDUNIT ASC")->result_array();

		foreach($unit as $u){
			$UNITKEY 	= $u['UNITKEY'];
			$KDINDIKTOR = $u['KD2'];
			$DATAPEJABAT = $this->db->query("SELECT A.NIP,P.NAMA,P.JABATAN, PANGKAT
											FROM ATASBEND A
											LEFT JOIN PEGAWAI P ON P.NIP = A.NIP
											LEFT JOIN GOLONGAN G ON G.KDGOL = P.KDGOL
											LEFT JOIN DAFTUNIT D ON D.UNITKEY = A.UNITKEY
											WHERE A.UNITKEY = '{$UNITKEY}'")->result_array();
			foreach ($DATAPEJABAT as $PEJABAT){
				$KEPALANIP	=  $PEJABAT['NIP'];
							$KEPALANAMA	=  $PEJABAT['NAMA'];
							$KEPALAJABATAN	=  $PEJABAT['JABATAN'];
							$KEPALAPANGKAT	=  $PEJABAT['PANGKAT'];}
			$b=array();
			$DATAPILIHANBIDANG = $this->db->query("SELECT KDUNIT FROM URUSANUNIT U LEFT JOIN DAFTUNIT D ON D.UNITKEY = U.URUSKEY WHERE U.UNITKEY = '{$UNITKEY}'")->result_array();
			foreach ($DATAPILIHANBIDANG as $BIDANG){
						$b[]	=  $BIDANG['KDUNIT'];
			}

			$tahun1 = $this->db->query("SELECT * FROM TAHUN WHERE KDTAHUN = '{$this->KDTAHUN}'")->row_array()['NMTAHUN'] ;


			$a['kodepemda']	 = "1376";
			$a['tahun']		 	= $tahun1;
			$a['kodebidang'] 		= $u['KD1'];
			$a['uraibidang'] 		= $u['NM1'];
			$a['kodeskpd']			 = $u['KDUNIT'];
			$a['uraiskpd']	 = $u['NMUNIT'];
			$a['pejabat'] 	 = array(
								'kepalanip'   =>  $KEPALANIP,
								'kepalanama'   => $KEPALANAMA,
								'kepalajabatan'   => $KEPALAJABATAN,
								'kepalapangkat'   => $KEPALAPANGKAT
								);
			$a['pilihanbidang']= $b;
			$a['uraiurusan'] = $u['URAIANURUSAN'];
			$a['program'] = array();

			$c = array();
			$DATAPROGRAM = $this->db->query("
			SELECT * FROM
								(
								SELECT

									P.PGRMRKPDKEY,
									ISNULL(D.KDUNIT, '0.00') AS KDUNIT,
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
									(SELECT SUM(PAGUTIF) FROM KEGRKPD WHERE KDTAHUN = '{$this->KDTAHUN}' AND KDTAHAP = '{$this->KDTAHAP}' AND UNITKEY = P.UNITKEY AND PGRMRKPDKEY = P.PGRMRKPDKEY) AS PAGUTIF,
									(SELECT SUM(PAGUPLUS) FROM KEGRKPD WHERE KDTAHUN =  '{$this->KDTAHUN}' AND KDTAHAP = '{$this->KDTAHAP}' AND UNITKEY = P.UNITKEY AND PGRMRKPDKEY = P.PGRMRKPDKEY) AS PAGUPLUS,
									CASE WHEN P.TGLVALID IS NULL THEN '' ELSE CONVERT(VARCHAR(10),P.TGLVALID,105) END AS TGLVALID
								FROM
									PGRRKPD P
								LEFT JOIN MPGRMRKPD MP ON P.PGRMRKPDKEY = MP.PGRMRKPDKEY AND P.KDTAHUN = MP.KDTAHUN
								LEFT JOIN DAFTUNIT D ON MP.UNITKEY = D.UNITKEY
								LEFT JOIN PRIOPPAS O ON P.PRIOPPASKEY = O.PRIOPPASKEY AND P.KDTAHUN = O.KDTAHUN
								LEFT JOIN SASARAN S ON P.IDSAS = S.IDSAS
								WHERE
									1 = 1 AND
									P.KDTAHUN =  '{$this->KDTAHUN}'
									AND P.KDTAHAP =  '{$this->KDTAHAP}'
									AND P.UNITKEY = '{$UNITKEY}')X ")->result_array();

					foreach ($DATAPROGRAM as $PROG){
						$pgrmrkpdkey = $PROG['PGRMRKPDKEY'];
						$noprg = str_replace(" ", "", $PROG['NUPRGRM']);
								$c['kodebidang']					= $u['KD1'];
								$c['uraibidang']					= $u['NM1'];
								$c['kodeprogram']					=  $PROG['KDUNIT'] . "." . $noprg ;
								$c['uraiprogram']					=  $PROG['NMPRGRM'];
								$c['prioritas']						=  array([
																			'prioritasdaerah'	=> $PROG['NMPRIOPPAS']]
																		);
								$c['capaian']	= array([
											'kodeindikator' 		=>	$KDINDIKTOR,
											'tolokukur'				=>  $PROG['INDIKATOR'],
											'satuan'				=>  '',
											'real_p3'				=> 0,
											'pagu_p3' 				=> 0,
											'real_p2' 				=> 0,
											'pagu_p2'		 		=> 0,
											'real_p1' 				=> 0,
											'pagu_p1' 				=>0,
											'target' 				=> $PROG['TARGET'],
											'pagu' 					=> number_format($PROG['PAGUTIF'], 0, ',', ''),
											'pagu_p' 				=> 0,
											'target_n1' 			=> "",
											'pagu_n1' 				=>  number_format($PROG['PAGUPLUS'], 0, ',', '')
								]);
								$c['kegiatan']	=  array();
								$DATAKEGIATAN = $this->db->query("
								SELECT* FROM
									(
									SELECT K.KEGRKPDKEY,
										ISNULL(D.KDUNIT, '0.00') AS KDUNIT,
										NUPRGRM,
										NUKEG,
										NMKEG,
										PAGUTIF,
										PAGUPLUS,
										(SELECT DISTINCT SUBSTRING((SELECT ', ' +NMPRIOPPAS
										FROM EPRIOPPAS EPR LEFT JOIN PRIOPPAS PR ON PR.KDTAHUN = EPR.KDTAHUN AND PR.PRIOPPASKEY = EPR.PRIOPPASKEY
										WHERE EPR.KDTAHAP = P.KDTAHAP AND EPR.KDTAHUN = P.KDTAHUN AND EPR.PGRMRKPDKEY = P.PGRMRKPDKEY AND EPR.UNITKEY = P.UNITKEY
										FOR XML PATH('')),3,500)) AS NMPRIOPPAS,
										(SELECT DISTINCT SUBSTRING((SELECT ', ' +NMSAS
										FROM
										ESASARAN ESAS LEFT JOIN SASARAN SAS ON SAS.IDSAS = ESAS.IDSAS
										WHERE ESAS.KDTAHAP = P.KDTAHAP AND ESAS.KDTAHUN = P.KDTAHUN AND ESAS.PGRMRKPDKEY = P.PGRMRKPDKEY AND ESAS.UNITKEY = P.UNITKEY
										FOR XML PATH('')),3,500)) AS NMSAS,
										LOKASI,
										P.SASARAN,
										(SELECT TARGET FROM KINKEGRKPD KK WHERE KK.UNITKEY = K.UNITKEY AND KK.KDTAHUN = K. KDTAHUN AND KK.KDTAHAP = K.KDTAHAP AND KK.KEGRKPDKEY = K.KEGRKPDKEY AND KK.KDJKK = '02') AS TARGET,

										(SELECT TARGET1 FROM KINKEGRKPD KK WHERE KK.UNITKEY = K.UNITKEY AND KK.KDTAHUN = K. KDTAHUN AND KK.KDTAHAP = K.KDTAHAP AND KK.KEGRKPDKEY = K.KEGRKPDKEY AND KK.KDJKK = '02') AS TARGET1,

										(SELECT URJKK FROM KINKEGRKPD KK LEFT JOIN JKINKEG J ON J.KDJKK = KK.KDJKK WHERE KK.UNITKEY = K.UNITKEY AND KK.KDTAHUN = K. KDTAHUN AND KK.KDTAHAP = K.KDTAHAP AND KK.KEGRKPDKEY = K.KEGRKPDKEY AND KK.KDJKK = '02') AS URJKK ,

										(SELECT KDJKK FROM KINKEGRKPD KK WHERE KK.UNITKEY = K.UNITKEY AND KK.KDTAHUN = K. KDTAHUN AND KK.KDTAHAP = K.KDTAHAP AND KK.KEGRKPDKEY = K.KEGRKPDKEY AND KK.KDJKK = '02') AS KDJKK,

										(SELECT TOLOKUR FROM KINKEGRKPD KK WHERE KK.UNITKEY = K.UNITKEY AND KK.KDTAHUN = K. KDTAHUN AND KK.KDTAHAP = K.KDTAHAP AND KK.KEGRKPDKEY = K.KEGRKPDKEY AND KK.KDJKK = '02')AS TOLOKUR

									FROM
										KEGRKPD K
										LEFT JOIN MKEGRKPD MP ON MP.KEGRKPDKEY = K.KEGRKPDKEY AND  MP.KDTAHUN = K.KDTAHUN
										LEFT JOIN MPGRMRKPD MK ON MK.PGRMRKPDKEY = K.PGRMRKPDKEY AND MK.KDTAHUN = K.KDTAHUN
										LEFT JOIN DAFTUNIT D ON D.UNITKEY = MK.UNITKEY
										LEFT JOIN PGRRKPD P ON P.PGRMRKPDKEY = K.PGRMRKPDKEY AND P.KDTAHUN = K.KDTAHUN AND P.KDTAHAP = K.KDTAHAP AND P.UNITKEY = K. UNITKEY
										WHERE 1 = 1 AND

										K.KDTAHUN = '{$this->KDTAHUN}'
										AND K.KDTAHAP = '{$this->KDTAHAP}'
										AND K.UNITKEY = '{$UNITKEY}'
										AND K.PGRMRKPDKEY = '{$pgrmrkpdkey}'
										) X ")->result_array();

						foreach ($DATAKEGIATAN as $KEG){
							$KEGRKPDKEY 	  		= $KEG['KEGRKPDKEY'];
							$nuprogkeg 				= str_replace(" ", "", $KEG['NUPRGRM']);
							$d['kodekegiatan']	= $KEG['KDUNIT'] . "." . $nuprogkeg . "." . $KEG['NUKEG'];
							$d['uraikegiatan'] 	= $KEG['NMKEG'];
							$d['pagu'] 			= number_format($KEG['PAGUTIF'], 0, ',', '');
							$d['pagu_p'] 			= 0;
							$d['sumberdana']		= array();

							$DATADANA = $this->db->query("SELECT K.KDDANA, NMDANA, NILAI FROM KEGRKPDDANA K LEFT JOIN JDANA J ON J.KDDANA = K.KDDANA WHERE KDTAHUN = '{$this->KDTAHUN}' AND KDTAHAP = '{$this->KDTAHAP}' AND  KEGRKPDKEY = '{$KEGRKPDKEY}' AND UNITKEY = '{$UNITKEY}'")->result_array();
							foreach ($DATADANA as $DANA){
								$kodesumberdana 			= str_replace(" ", "", $DANA['KDDANA']);
								$e['pagu']					=  number_format($DANA['NILAI'], 0, ',', '');
								$e['sumberdana']			=  $DANA['NMDANA'];
								$e['kodesumberdana']		= $kodesumberdana ;
								array_push($d['sumberdana'], $e);
							}

							$d['prioritas']				=  array(['prioritasdaerah'	=> $KEG['NMPRIOPPAS']]);
							$d['lokasi']					= array([
																'lokasi'		=> $KEG['LOKASI'],
																'kodelokasi'	=>"",
																'detaillokasi'	=>""]);

							$d['indikator']	= array([
								'kodeindikator' 			=>	$KEG['KDJKK'],
								'jenis'						=>	$KEG['URJKK'],
								'tolokukur'					=>  $KEG['TOLOKUR'],
								'satuan'					=>  '',
								'real_p3'					=> 0,
								'pagu_p3' 					=> 0,
								'real_p2' 					=> 0,
								'pagu_p2'		 			=> 0,
								'real_p1' 					=> 0,
								'pagu_p1' 					=>0,
								'target' 					=> $KEG['TARGET'],
								'pagu' 						=> number_format($KEG['PAGUTIF'], 0, ',', ''),
								'pagu_p' 					=> 0,
								'target_n1' 				=> $KEG['TARGET1'],
								'pagu_n1' 					=> number_format($KEG['PAGUPLUS'], 0, ',', '')
							]);

										$d['subkegiatan']	=  array();
										$DATASUBKEGIATAN = $this->db->query("
										SELECT* FROM
											(
											SELECT K.SUBKEGRKPDKEY,
												ISNULL(D.KDUNIT, '0.00') AS KDUNIT,
												NUPRGRM,
												NUKEG,
												NUSUBKEG,
												NMSUBKEG,
												K.PAGUTIF,
												K.PAGUPLUS,
												(SELECT DISTINCT SUBSTRING((SELECT ', ' +NMPRIOPPAS
												FROM EPRIOPPAS EPR LEFT JOIN PRIOPPAS PR ON PR.KDTAHUN = EPR.KDTAHUN AND PR.PRIOPPASKEY = EPR.PRIOPPASKEY
												WHERE EPR.KDTAHAP = P.KDTAHAP AND EPR.KDTAHUN = P.KDTAHUN AND EPR.PGRMRKPDKEY = P.PGRMRKPDKEY AND EPR.UNITKEY = P.UNITKEY
												FOR XML PATH('')),3,500)) AS NMPRIOPPAS,
												(SELECT DISTINCT SUBSTRING((SELECT ', ' +NMSAS
												FROM
												ESASARAN ESAS LEFT JOIN SASARAN SAS ON SAS.IDSAS = ESAS.IDSAS
												WHERE ESAS.KDTAHAP = P.KDTAHAP AND ESAS.KDTAHUN = P.KDTAHUN AND ESAS.PGRMRKPDKEY = P.PGRMRKPDKEY AND ESAS.UNITKEY = P.UNITKEY
												FOR XML PATH('')),3,500)) AS NMSAS,
												K.LOKASI,
												(SELECT TARGET FROM SUBKINKEGRKPD KK WHERE KK.UNITKEY = K.UNITKEY AND KK.KDTAHUN = K. KDTAHUN AND KK.KDTAHAP = K.KDTAHAP AND KK.SUBKEGRKPDKEY = K.SUBKEGRKPDKEY AND KK.KDJKK = '02') AS TARGET,

												(SELECT TARGET1 FROM SUBKINKEGRKPD KK WHERE KK.UNITKEY = K.UNITKEY AND KK.KDTAHUN = K. KDTAHUN AND KK.KDTAHAP = K.KDTAHAP AND KK.SUBKEGRKPDKEY = K.SUBKEGRKPDKEY AND KK.KDJKK = '02') AS TARGET1,

												(SELECT URJKK FROM SUBKINKEGRKPD KK LEFT JOIN JKINKEG J ON J.KDJKK = KK.KDJKK WHERE KK.UNITKEY = K.UNITKEY AND KK.KDTAHUN = K. KDTAHUN AND KK.KDTAHAP = K.KDTAHAP AND KK.SUBKEGRKPDKEY = K.SUBKEGRKPDKEY AND KK.KDJKK = '02') AS URJKK ,

												(SELECT KDJKK FROM SUBKINKEGRKPD KK WHERE KK.UNITKEY = K.UNITKEY AND KK.KDTAHUN = K. KDTAHUN AND KK.KDTAHAP = K.KDTAHAP AND KK.SUBKEGRKPDKEY = K.SUBKEGRKPDKEY AND KK.KDJKK = '02') AS KDJKK,

												(SELECT TOLOKUR FROM SUBKINKEGRKPD KK WHERE KK.UNITKEY = K.UNITKEY AND KK.KDTAHUN = K. KDTAHUN AND KK.KDTAHAP = K.KDTAHAP AND KK.SUBKEGRKPDKEY = K.SUBKEGRKPDKEY AND KK.KDJKK = '02')AS TOLOKUR

											FROM
												SUBKEGRKPD K
												LEFT JOIN MSUBKEGRKPD MP ON MP.SUBKEGRKPDKEY = K.SUBKEGRKPDKEY AND  MP.KDTAHUN = K.KDTAHUN
												LEFT JOIN KEGRKPD M ON M.KEGRKPDKEY = K.KEGRKPDKEY AND M.KDTAHUN = K.KDTAHUN AND M.KDTAHAP = K.KDTAHAP AND M.UNITKEY = K.UNITKEY
												LEFT JOIN MKEGRKPD MKR ON MKR.KEGRKPDKEY = M.KEGRKPDKEY AND MKR.KDTAHUN = M.KDTAHUN
												LEFT JOIN PGRRKPD P ON P.PGRMRKPDKEY = M.PGRMRKPDKEY AND P.KDTAHUN = M.KDTAHUN AND P.KDTAHAP = M.KDTAHAP AND P.UNITKEY = M.UNITKEY
												LEFT JOIN MPGRMRKPD MK ON MK.PGRMRKPDKEY = P.PGRMRKPDKEY AND MK.KDTAHUN = P.KDTAHUN
												LEFT JOIN DAFTUNIT D ON D.UNITKEY = MK.UNITKEY

												WHERE 1 = 1 AND
												K.KDTAHUN = '{$this->KDTAHUN}'
												AND K.KDTAHAP = '{$this->KDTAHAP}'
												AND K.UNITKEY = '{$UNITKEY}'

												AND K.KEGRKPDKEY = '{$KEGRKPDKEY}'
												) X ")->result_array();

								foreach ($DATASUBKEGIATAN as $SUBKEG){
									$SUBKEGRKPDKEY 	  		= $SUBKEG['SUBKEGRKPDKEY'];
									$nuprogkeg 				= str_replace(" ", "", $SUBKEG['NUPRGRM']);
									$j['kodesubkegiatan']	= $SUBKEG['KDUNIT']  . "." . $nuprogkeg  . "." . $SUBKEG['NUKEG']  . "." . $SUBKEG['NUSUBKEG'];
									$j['uraisubkegiatan'] 	= $SUBKEG['NMSUBKEG'];
									$j['pagu'] 			= number_format($SUBKEG['PAGUTIF'], 0, ',', '');
									$j['pagu_p'] 			= 0;
									$j['sumberdana']		= array();

									$DATASUBDANA = $this->db->query("SELECT K.KDDANA, NMDANA, NILAI FROM SUBKEGRKPDDANA K LEFT JOIN JDANA J ON J.KDDANA = K.KDDANA  WHERE KDTAHUN = '{$this->KDTAHUN}' AND KDTAHAP = '{$this->KDTAHAP}' AND  SUBKEGRKPDKEY = '{$SUBKEGRKPDKEY}' AND UNITKEY = '{$UNITKEY}'")->result_array();
									foreach ($DATASUBDANA as $DANA1){
										$kodesumberdana 			= str_replace(" ", "", $DANA1['KDDANA']);
										$h['pagu']					=  number_format($DANA1['NILAI'], 0, ',', '');
										$h['sumberdana']			=  $DANA1['NMDANA'];
										$h['kodesumberdana']		= $kodesumberdana ;
										array_push($j['sumberdana'], $h);
									}

									$j['prioritas']				=  array(['prioritasdaerah'	=> $SUBKEG['NMPRIOPPAS']]);
									$j['lokasi']					= array([
																		'lokasi'		=> $SUBKEG['LOKASI'],
																		'kodelokasi'	=>"",
																		'detaillokasi'	=>""]);

									$j['indikator']	= array([
										'kodeindikator' 			=>	$SUBKEG['KDJKK'],
										'jenis'						=>	$SUBKEG['URJKK'],
										'tolokukur'					=>  $SUBKEG['TOLOKUR'],
										'satuan'					=>  '',
										'real_p3'					=> 0,
										'pagu_p3' 					=> 0,
										'real_p2' 					=> 0,
										'pagu_p2'		 			=> 0,
										'real_p1' 					=> 0,
										'pagu_p1' 					=>0,
										'target' 					=> $SUBKEG['TARGET'],
										'pagu' 						=> number_format($SUBKEG['PAGUTIF'], 0, ',', ''),
										'pagu_p' 					=> 0,
										'target_n1' 				=> $SUBKEG['TARGET1'],
										'pagu_n1' 					=> number_format($SUBKEG['PAGUPLUS'], 0, ',', '')
									]);
									array_push($d['subkegiatan'], $j);

								}




							array_push($c['kegiatan'], $d);

						}
					array_push(	$a['program']	, $c);
					}
				array_push($dataBappeda, $a);
		}

		echo json_encode($dataBappeda);

		//$data =  json_encode($dataBappeda);

	//$this->postRanwalBangda($data);


	}


	public function post_rancangan()
	{
		$dataBappeda = array();

		$unit = $this->db->query("
		SELECT UNITKEY, KDLEVEL,KDUNIT, NMUNIT,
		(SELECT KDUNIT FROM DAFTUNIT D WHERE KDLEVEL = 2 AND KDUNIT1 = SUBSTRING(F.KDUNIT1,0,5) ) AS KD1,
		(SELECT NMUNIT FROM DAFTUNIT D WHERE KDLEVEL = 2 AND KDUNIT1 = SUBSTRING(F.KDUNIT1,0,5)) AS NM1,
		(SELECT KDUNIT FROM DAFTUNIT D WHERE KDLEVEL = 1 AND KDUNIT1 = SUBSTRING(F.KDUNIT1,0,2)) AS KD2,
		(SELECT NMUNIT FROM DAFTUNIT D WHERE KDLEVEL = 1 AND KDUNIT1 = SUBSTRING(F.KDUNIT1,0,2)) AS URAIANURUSAN
		 FROM DAFTUNIT F WHERE KDLEVEL NOT IN (1,2)  AND TYPE = 'D'
		  ORDER BY KDUNIT ASC")->result_array();

		foreach($unit as $u){
			$UNITKEY 	= $u['UNITKEY'];
			$KDINDIKTOR = $u['KD2'];
			$DATAPEJABAT = $this->db->query("SELECT A.NIP,P.NAMA,P.JABATAN, PANGKAT
											FROM ATASBEND A
											LEFT JOIN PEGAWAI P ON P.NIP = A.NIP
											LEFT JOIN GOLONGAN G ON G.KDGOL = P.KDGOL
											LEFT JOIN DAFTUNIT D ON D.UNITKEY = A.UNITKEY
											WHERE A.UNITKEY = '{$UNITKEY}'")->result_array();
			foreach ($DATAPEJABAT as $PEJABAT){
				$KEPALANIP	=  $PEJABAT['NIP'];
							$KEPALANAMA	=  $PEJABAT['NAMA'];
							$KEPALAJABATAN	=  $PEJABAT['JABATAN'];
							$KEPALAPANGKAT	=  $PEJABAT['PANGKAT'];}
			$b=array();
			$DATAPILIHANBIDANG = $this->db->query("SELECT KDUNIT FROM URUSANUNIT U LEFT JOIN DAFTUNIT D ON D.UNITKEY = U.URUSKEY WHERE U.UNITKEY = '{$UNITKEY}'")->result_array();
			foreach ($DATAPILIHANBIDANG as $BIDANG){
						$b[]	=  $BIDANG['KDUNIT'];
			}

			$tahun1 = $this->db->query("SELECT * FROM TAHUN WHERE KDTAHUN = '{$this->KDTAHUN}'")->row_array()['NMTAHUN'] ;


			$a['kodepemda']	 = "1376";
			$a['tahun']		 	= $tahun1;
			$a['kodebidang'] 		= $u['KD1'];
			$a['uraibidang'] 		= $u['NM1'];
			$a['kodeskpd']			 = $u['KDUNIT'];
			$a['uraiskpd']	 = $u['NMUNIT'];
			$a['pejabat'] 	 = array(
								'kepalanip'   =>  $KEPALANIP,
								'kepalanama'   => $KEPALANAMA,
								'kepalajabatan'   => $KEPALAJABATAN,
								'kepalapangkat'   => $KEPALAPANGKAT
								);
			$a['pilihanbidang']= $b;
			$a['uraiurusan'] = $u['URAIANURUSAN'];
			$a['program'] = array();

			$c = array();
			$DATAPROGRAM = $this->db->query("
			SELECT * FROM
								(
								SELECT

									P.PGRMRKPDKEY,
									ISNULL(D.KDUNIT, '0.00') AS KDUNIT,
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
									(SELECT SUM(PAGUTIF) FROM KEGRKPD WHERE KDTAHUN = '{$this->KDTAHUN}' AND KDTAHAP = '{$this->KDTAHAP}' AND UNITKEY = P.UNITKEY AND PGRMRKPDKEY = P.PGRMRKPDKEY) AS PAGUTIF,
									(SELECT SUM(PAGUPLUS) FROM KEGRKPD WHERE KDTAHUN =  '{$this->KDTAHUN}' AND KDTAHAP = '{$this->KDTAHAP}' AND UNITKEY = P.UNITKEY AND PGRMRKPDKEY = P.PGRMRKPDKEY) AS PAGUPLUS,
									CASE WHEN P.TGLVALID IS NULL THEN '' ELSE CONVERT(VARCHAR(10),P.TGLVALID,105) END AS TGLVALID
								FROM
									PGRRKPD P
								LEFT JOIN MPGRMRKPD MP ON P.PGRMRKPDKEY = MP.PGRMRKPDKEY AND P.KDTAHUN = MP.KDTAHUN
								LEFT JOIN DAFTUNIT D ON MP.UNITKEY = D.UNITKEY
								LEFT JOIN PRIOPPAS O ON P.PRIOPPASKEY = O.PRIOPPASKEY AND P.KDTAHUN = O.KDTAHUN
								LEFT JOIN SASARAN S ON P.IDSAS = S.IDSAS
								WHERE
									1 = 1 AND
									P.KDTAHUN =  '{$this->KDTAHUN}'
									AND P.KDTAHAP =  '{$this->KDTAHAP}'
									AND P.UNITKEY = '{$UNITKEY}')X ")->result_array();

					foreach ($DATAPROGRAM as $PROG){
						$pgrmrkpdkey = $PROG['PGRMRKPDKEY'];
						$noprg = str_replace(" ", "", $PROG['NUPRGRM']);
								$c['kodebidang']					= $u['KD1'];
								$c['uraibidang']					= $u['NM1'];
								$c['kodeprogram']					=  $PROG['KDUNIT'] . "." . $noprg ;
								$c['uraiprogram']					=  $PROG['NMPRGRM'];
								$c['prioritas']						=  array([
																			'prioritasdaerah'	=> $PROG['NMPRIOPPAS']]
																		);
								$c['capaian']	= array([
											'kodeindikator' 		=>	$KDINDIKTOR,
											'tolokukur'				=>  $PROG['INDIKATOR'],
											'satuan'				=>  '',
											'real_p3'				=> 0,
											'pagu_p3' 				=> 0,
											'real_p2' 				=> 0,
											'pagu_p2'		 		=> 0,
											'real_p1' 				=> 0,
											'pagu_p1' 				=>0,
											'target' 				=> $PROG['TARGET'],
											'pagu' 					=> number_format($PROG['PAGUTIF'], 0, ',', ''),
											'pagu_p' 				=> 0,
											'target_n1' 			=> "",
											'pagu_n1' 				=>  number_format($PROG['PAGUPLUS'], 0, ',', '')
								]);
								$c['kegiatan']	=  array();
								$DATAKEGIATAN = $this->db->query("
								SELECT* FROM
									(
									SELECT K.KEGRKPDKEY,
										ISNULL(D.KDUNIT, '0.00') AS KDUNIT,
										NUPRGRM,
										NUKEG,
										NMKEG,
										PAGUTIF,
										PAGUPLUS,
										(SELECT DISTINCT SUBSTRING((SELECT ', ' +NMPRIOPPAS
										FROM EPRIOPPAS EPR LEFT JOIN PRIOPPAS PR ON PR.KDTAHUN = EPR.KDTAHUN AND PR.PRIOPPASKEY = EPR.PRIOPPASKEY
										WHERE EPR.KDTAHAP = P.KDTAHAP AND EPR.KDTAHUN = P.KDTAHUN AND EPR.PGRMRKPDKEY = P.PGRMRKPDKEY AND EPR.UNITKEY = P.UNITKEY
										FOR XML PATH('')),3,500)) AS NMPRIOPPAS,
										(SELECT DISTINCT SUBSTRING((SELECT ', ' +NMSAS
										FROM
										ESASARAN ESAS LEFT JOIN SASARAN SAS ON SAS.IDSAS = ESAS.IDSAS
										WHERE ESAS.KDTAHAP = P.KDTAHAP AND ESAS.KDTAHUN = P.KDTAHUN AND ESAS.PGRMRKPDKEY = P.PGRMRKPDKEY AND ESAS.UNITKEY = P.UNITKEY
										FOR XML PATH('')),3,500)) AS NMSAS,
										LOKASI,
										P.SASARAN,
										(SELECT TARGET FROM KINKEGRKPD KK WHERE KK.UNITKEY = K.UNITKEY AND KK.KDTAHUN = K. KDTAHUN AND KK.KDTAHAP = K.KDTAHAP AND KK.KEGRKPDKEY = K.KEGRKPDKEY AND KK.KDJKK = '02') AS TARGET,

										(SELECT TARGET1 FROM KINKEGRKPD KK WHERE KK.UNITKEY = K.UNITKEY AND KK.KDTAHUN = K. KDTAHUN AND KK.KDTAHAP = K.KDTAHAP AND KK.KEGRKPDKEY = K.KEGRKPDKEY AND KK.KDJKK = '02') AS TARGET1,

										(SELECT URJKK FROM KINKEGRKPD KK LEFT JOIN JKINKEG J ON J.KDJKK = KK.KDJKK WHERE KK.UNITKEY = K.UNITKEY AND KK.KDTAHUN = K. KDTAHUN AND KK.KDTAHAP = K.KDTAHAP AND KK.KEGRKPDKEY = K.KEGRKPDKEY AND KK.KDJKK = '02') AS URJKK ,

										(SELECT KDJKK FROM KINKEGRKPD KK WHERE KK.UNITKEY = K.UNITKEY AND KK.KDTAHUN = K. KDTAHUN AND KK.KDTAHAP = K.KDTAHAP AND KK.KEGRKPDKEY = K.KEGRKPDKEY AND KK.KDJKK = '02') AS KDJKK,

										(SELECT TOLOKUR FROM KINKEGRKPD KK WHERE KK.UNITKEY = K.UNITKEY AND KK.KDTAHUN = K. KDTAHUN AND KK.KDTAHAP = K.KDTAHAP AND KK.KEGRKPDKEY = K.KEGRKPDKEY AND KK.KDJKK = '02')AS TOLOKUR

									FROM
										KEGRKPD K
										LEFT JOIN MKEGRKPD MP ON MP.KEGRKPDKEY = K.KEGRKPDKEY AND  MP.KDTAHUN = K.KDTAHUN
										LEFT JOIN MPGRMRKPD MK ON MK.PGRMRKPDKEY = K.PGRMRKPDKEY AND MK.KDTAHUN = K.KDTAHUN
										LEFT JOIN DAFTUNIT D ON D.UNITKEY = MK.UNITKEY
										LEFT JOIN PGRRKPD P ON P.PGRMRKPDKEY = K.PGRMRKPDKEY AND P.KDTAHUN = K.KDTAHUN AND P.KDTAHAP = K.KDTAHAP AND P.UNITKEY = K. UNITKEY
										WHERE 1 = 1 AND

										K.KDTAHUN = '{$this->KDTAHUN}'
										AND K.KDTAHAP = '{$this->KDTAHAP}'
										AND K.UNITKEY = '{$UNITKEY}'
										AND K.PGRMRKPDKEY = '{$pgrmrkpdkey}'
										) X ")->result_array();

						foreach ($DATAKEGIATAN as $KEG){
							$KEGRKPDKEY 	  		= $KEG['KEGRKPDKEY'];
							$nuprogkeg 				= str_replace(" ", "", $KEG['NUPRGRM']);
							$d['kodekegiatan']	= $KEG['KDUNIT'] . "." . $nuprogkeg . "." . $KEG['NUKEG'];
							$d['uraikegiatan'] 	= $KEG['NMKEG'];
							$d['pagu'] 			= number_format($KEG['PAGUTIF'], 0, ',', '');
							$d['pagu_p'] 			= 0;
							$d['sumberdana']		= array();

							$DATADANA = $this->db->query("SELECT K.KDDANA, NMDANA, NILAI FROM KEGRKPDDANA K LEFT JOIN JDANA J ON J.KDDANA = K.KDDANA WHERE KDTAHUN = '{$this->KDTAHUN}' AND KDTAHAP = '{$this->KDTAHAP}' AND  KEGRKPDKEY = '{$KEGRKPDKEY}' AND UNITKEY = '{$UNITKEY}'")->result_array();
							foreach ($DATADANA as $DANA){
								$kodesumberdana 			= str_replace(" ", "", $DANA['KDDANA']);
								$e['pagu']					=  number_format($DANA['NILAI'], 0, ',', '');
								$e['sumberdana']			=  $DANA['NMDANA'];
								$e['kodesumberdana']		= $kodesumberdana ;
								array_push($d['sumberdana'], $e);
							}

							$d['prioritas']				=  array(['prioritasdaerah'	=> $KEG['NMPRIOPPAS']]);
							$d['lokasi']					= array([
																'lokasi'		=> $KEG['LOKASI'],
																'kodelokasi'	=>"",
																'detaillokasi'	=>""]);

							$d['indikator']	= array([
								'kodeindikator' 			=>	$KEG['KDJKK'],
								'jenis'						=>	$KEG['URJKK'],
								'tolokukur'					=>  $KEG['TOLOKUR'],
								'satuan'					=>  '',
								'real_p3'					=> 0,
								'pagu_p3' 					=> 0,
								'real_p2' 					=> 0,
								'pagu_p2'		 			=> 0,
								'real_p1' 					=> 0,
								'pagu_p1' 					=>0,
								'target' 					=> $KEG['TARGET'],
								'pagu' 						=> number_format($KEG['PAGUTIF'], 0, ',', ''),
								'pagu_p' 					=> 0,
								'target_n1' 				=> $KEG['TARGET1'],
								'pagu_n1' 					=> number_format($KEG['PAGUPLUS'], 0, ',', '')
							]);

										$d['subkegiatan']	=  array();
										$DATASUBKEGIATAN = $this->db->query("
										SELECT* FROM
											(
											SELECT K.SUBKEGRKPDKEY,
												ISNULL(D.KDUNIT, '0.00') AS KDUNIT,
												NUPRGRM,
												NUKEG,
												NUSUBKEG,
												NMSUBKEG,
												K.PAGUTIF,
												K.PAGUPLUS,
												(SELECT DISTINCT SUBSTRING((SELECT ', ' +NMPRIOPPAS
												FROM EPRIOPPAS EPR LEFT JOIN PRIOPPAS PR ON PR.KDTAHUN = EPR.KDTAHUN AND PR.PRIOPPASKEY = EPR.PRIOPPASKEY
												WHERE EPR.KDTAHAP = P.KDTAHAP AND EPR.KDTAHUN = P.KDTAHUN AND EPR.PGRMRKPDKEY = P.PGRMRKPDKEY AND EPR.UNITKEY = P.UNITKEY
												FOR XML PATH('')),3,500)) AS NMPRIOPPAS,
												(SELECT DISTINCT SUBSTRING((SELECT ', ' +NMSAS
												FROM
												ESASARAN ESAS LEFT JOIN SASARAN SAS ON SAS.IDSAS = ESAS.IDSAS
												WHERE ESAS.KDTAHAP = P.KDTAHAP AND ESAS.KDTAHUN = P.KDTAHUN AND ESAS.PGRMRKPDKEY = P.PGRMRKPDKEY AND ESAS.UNITKEY = P.UNITKEY
												FOR XML PATH('')),3,500)) AS NMSAS,
												K.LOKASI,
												(SELECT TARGET FROM SUBKINKEGRKPD KK WHERE KK.UNITKEY = K.UNITKEY AND KK.KDTAHUN = K. KDTAHUN AND KK.KDTAHAP = K.KDTAHAP AND KK.SUBKEGRKPDKEY = K.SUBKEGRKPDKEY AND KK.KDJKK = '02') AS TARGET,

												(SELECT TARGET1 FROM SUBKINKEGRKPD KK WHERE KK.UNITKEY = K.UNITKEY AND KK.KDTAHUN = K. KDTAHUN AND KK.KDTAHAP = K.KDTAHAP AND KK.SUBKEGRKPDKEY = K.SUBKEGRKPDKEY AND KK.KDJKK = '02') AS TARGET1,

												(SELECT URJKK FROM SUBKINKEGRKPD KK LEFT JOIN JKINKEG J ON J.KDJKK = KK.KDJKK WHERE KK.UNITKEY = K.UNITKEY AND KK.KDTAHUN = K. KDTAHUN AND KK.KDTAHAP = K.KDTAHAP AND KK.SUBKEGRKPDKEY = K.SUBKEGRKPDKEY AND KK.KDJKK = '02') AS URJKK ,

												(SELECT KDJKK FROM SUBKINKEGRKPD KK WHERE KK.UNITKEY = K.UNITKEY AND KK.KDTAHUN = K. KDTAHUN AND KK.KDTAHAP = K.KDTAHAP AND KK.SUBKEGRKPDKEY = K.SUBKEGRKPDKEY AND KK.KDJKK = '02') AS KDJKK,

												(SELECT TOLOKUR FROM SUBKINKEGRKPD KK WHERE KK.UNITKEY = K.UNITKEY AND KK.KDTAHUN = K. KDTAHUN AND KK.KDTAHAP = K.KDTAHAP AND KK.SUBKEGRKPDKEY = K.SUBKEGRKPDKEY AND KK.KDJKK = '02')AS TOLOKUR

											FROM
												SUBKEGRKPD K
												LEFT JOIN MSUBKEGRKPD MP ON MP.SUBKEGRKPDKEY = K.SUBKEGRKPDKEY AND  MP.KDTAHUN = K.KDTAHUN
												LEFT JOIN KEGRKPD M ON M.KEGRKPDKEY = K.KEGRKPDKEY AND M.KDTAHUN = K.KDTAHUN AND M.KDTAHAP = K.KDTAHAP AND M.UNITKEY = K.UNITKEY
												LEFT JOIN MKEGRKPD MKR ON MKR.KEGRKPDKEY = M.KEGRKPDKEY AND MKR.KDTAHUN = M.KDTAHUN
												LEFT JOIN PGRRKPD P ON P.PGRMRKPDKEY = M.PGRMRKPDKEY AND P.KDTAHUN = M.KDTAHUN AND P.KDTAHAP = M.KDTAHAP AND P.UNITKEY = M.UNITKEY
												LEFT JOIN MPGRMRKPD MK ON MK.PGRMRKPDKEY = P.PGRMRKPDKEY AND MK.KDTAHUN = P.KDTAHUN
												LEFT JOIN DAFTUNIT D ON D.UNITKEY = MK.UNITKEY

												WHERE 1 = 1 AND
												K.KDTAHUN = '{$this->KDTAHUN}'
												AND K.KDTAHAP = '{$this->KDTAHAP}'
												AND K.UNITKEY = '{$UNITKEY}'

												AND K.KEGRKPDKEY = '{$KEGRKPDKEY}'
												) X ")->result_array();

								foreach ($DATASUBKEGIATAN as $SUBKEG){
									$SUBKEGRKPDKEY 	  		= $SUBKEG['SUBKEGRKPDKEY'];
									$nuprogkeg 				= str_replace(" ", "", $SUBKEG['NUPRGRM']);
									$j['kodesubkegiatan']	= $SUBKEG['KDUNIT']  . "." . $nuprogkeg  . "." . $SUBKEG['NUKEG']  . "." . $SUBKEG['NUSUBKEG'];
									$j['uraisubkegiatan'] 	= $SUBKEG['NMSUBKEG'];
									$j['pagu'] 			= number_format($SUBKEG['PAGUTIF'], 0, ',', '');
									$j['pagu_p'] 			= 0;
									$j['sumberdana']		= array();

									$DATASUBDANA = $this->db->query("SELECT K.KDDANA, NMDANA, NILAI FROM SUBKEGRKPDDANA K LEFT JOIN JDANA J ON J.KDDANA = K.KDDANA  WHERE KDTAHUN = '{$this->KDTAHUN}' AND KDTAHAP = '{$this->KDTAHAP}' AND  SUBKEGRKPDKEY = '{$SUBKEGRKPDKEY}' AND UNITKEY = '{$UNITKEY}'")->result_array();
									foreach ($DATASUBDANA as $DANA1){
										$kodesumberdana 			= str_replace(" ", "", $DANA1['KDDANA']);
										$h['pagu']					=  number_format($DANA1['NILAI'], 0, ',', '');
										$h['sumberdana']			=  $DANA1['NMDANA'];
										$h['kodesumberdana']		= $kodesumberdana ;
										array_push($j['sumberdana'], $h);
									}

									$j['prioritas']				=  array(['prioritasdaerah'	=> $SUBKEG['NMPRIOPPAS']]);
									$j['lokasi']					= array([
																		'lokasi'		=> $SUBKEG['LOKASI'],
																		'kodelokasi'	=>"",
																		'detaillokasi'	=>""]);

									$j['indikator']	= array([
										'kodeindikator' 			=>	$SUBKEG['KDJKK'],
										'jenis'						=>	$SUBKEG['URJKK'],
										'tolokukur'					=>  $SUBKEG['TOLOKUR'],
										'satuan'					=>  '',
										'real_p3'					=> 0,
										'pagu_p3' 					=> 0,
										'real_p2' 					=> 0,
										'pagu_p2'		 			=> 0,
										'real_p1' 					=> 0,
										'pagu_p1' 					=>0,
										'target' 					=> $SUBKEG['TARGET'],
										'pagu' 						=> number_format($SUBKEG['PAGUTIF'], 0, ',', ''),
										'pagu_p' 					=> 0,
										'target_n1' 				=> $SUBKEG['TARGET1'],
										'pagu_n1' 					=> number_format($SUBKEG['PAGUPLUS'], 0, ',', '')
									]);
									array_push($d['subkegiatan'], $j);

								}




							array_push($c['kegiatan'], $d);

						}
					array_push(	$a['program']	, $c);
					}
				array_push($dataBappeda, $a);
		}

		//echo json_encode($dataBappeda);

		$data =  json_encode($dataBappeda);

	$this->postRancanganBangda($data);


	}



}
