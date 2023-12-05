<?php
class RestService
{  
	function __construct()
	{
	}

	function getAllData($id)
	{ 
		switch($id)
		{
			case "pegawai-protokol-upacara":				
				$sql = 'SELECT a.id, a.nama, a.nip, b.nama_jabatan
						FROM db_siap.pegawai a
						LEFT JOIN db_ortala.jabatan b ON b.id = a.id_jabatan 
						WHERE a.id_satker IN (145, 618) AND a.status = 1
						UNION ALL 
						SELECT a.id, a.nama, a.nip, a.jabatan
						FROM db_siap.ptt a
						WHERE a.id IN (110000062, 110000064, 110000067) AND a.status = 1';
						break;
			case "protokol":
				$sql = "SELECT a.id, a.nama, a.nip, b.nama_jabatan
						FROM db_siap.pegawai a
						LEFT JOIN db_ortala.jabatan b ON b.id = a.id_jabatan 
						WHERE a.id_satker IN (120, 143, 144, 145, 618) AND a.status = 1
						UNION ALL 
						SELECT a.id, a.nama, a.nip, a.jabatan
						FROM db_siap.ptt a
						WHERE a.id_satker IN (120, 143, 144, 145, 618) AND a.status = 1";
				break;

			case "akdfraksi":
				$sql = "SELECT id, singkatan, akd AS nama, 'akd' AS tipe
					FROM db_minangwan.akd
					WHERE STATUS = 1 and id NOT IN (12, 23)
					UNION 
					SELECT id, singkatan, fraksi AS nama, 'fraksi' AS tipe
					FROM db_minangwan.fraksi
					WHERE STATUS = 1";
				break;
			
			case "akd-fraksi-dapil-anggota":
				$sql = "SELECT a.id, a.akd AS nama, 'akd' AS tipe
						FROM db_minangwan.akd a
						WHERE a.status = 1 AND a.id NOT IN (23) AND id BETWEEN 1 AND 23
						UNION ALL
						SELECT a.id, a.fraksi AS nama, 'fraksi' AS tipe
						FROM db_minangwan.fraksi a
						WHERE a.status = 1 AND a.id NOT IN (10, 12, 13, 14) AND urutan_fraksi IS NOT NULL
						UNION ALL
						SELECT a.id, concat('DAPIL ',a.dapil) AS nama, 'dapil' AS tipe
						FROM db_minangwan.dapil a
						WHERE a.status = 1
						UNION ALL
						SELECT a.id, CONCAT('(',CONCAT('A-', REPEAT('0', 3-LENGTH(a.no_anggota)), a.no_anggota),') ',  a.nama,' - ',  a.fraksi) AS nama, 'anggota' AS tipe
						FROM db_minangwan.view_anggota_sitanang_aktif a";
				break;
				
			case "fraksi":
				$sql = "SELECT id, fraksi AS nama
						FROM db_minangwan.fraksi
						WHERE STATUS = 1";
				break;
				
			case "fraksi2":
				$sql = "SELECT id, fraksi AS nama
						FROM db_minangwan.fraksi
						WHERE STATUS = 1 AND urutan_fraksi IS NOT NULL";
				break;

			case "akd":
				$sql = "SELECT id, akd AS nama
						FROM db_minangwan.akd
						WHERE STATUS = 1";
				break;

			case "akd2":
				$sql = "SELECT id, akd AS nama
						FROM db_minangwan.akd
						WHERE STATUS = 1 AND id BETWEEN 1 AND 23";
				break;

			case "pansus":
				$sql = "SELECT id, pansus AS nama
						FROM db_minangwan.pansus";
				break;

			case "satker":
				$sql = "SELECT id, nama_satker AS nama
						FROM db_siap.satker";
				break;

			case "satker1":
				$sql = "SELECT a.id AS id_satker, a.nama_satker, SUM(CASE WHEN m.status = 1 THEN 1 ELSE 0 END) AS jum 
						FROM db_siap.satker AS a 
						LEFT JOIN db_itjen.itjen_menu AS m ON m.id_satker = a.id 
						WHERE (a.status = 1) 
						GROUP BY a.id 
						ORDER BY jum DESC, id_satker ASC";
				break;

			case "anggota":
				$sql = "SELECT id, CONCAT('A-', REPEAT('0', 3-LENGTH(no_anggota)), no_anggota) AS nomor, nama, fraksi AS unit_kerja, 
						'' AS jabatan, 'Anggota' AS golongan, '' AS eselon
						FROM db_minangwan.view_anggota_sitanang_aktif
						ORDER BY no_anggota";
				break;

			case "pegawai":
				$sql = "SELECT a.id, a.nip AS nomor, a.nama, b.nama_satker AS unit_kerja, 
						c.nama_jabatan AS jabatan, SUBSTRING_INDEX(a.golongan, '/', 1) AS golongan, 
						CASE 
							WHEN c.nama_jabatan = 'Sekretaris Jenderal' THEN 1
							WHEN c.nama_jabatan = 'Wakil Sekretaris Jenderal' THEN 1
							WHEN c.nama_jabatan LIKE 'Deputi%' THEN 1
							WHEN c.nama_jabatan LIKE 'Kepala Biro%' OR c.nama_jabatan LIKE 'Kepala Pusat%' THEN 2
							WHEN c.nama_jabatan LIKE 'Kepala Bagian%' OR c.nama_jabatan LIKE 'Kepala Bidang%' THEN 3
							WHEN c.nama_jabatan LIKE 'Kepala Subbagian%' OR c.nama_jabatan LIKE 'Kepala Subbidang%' THEN 4
							ELSE ''
						END AS eselon
						FROM db_siap.pegawai a
						LEFT JOIN db_siap.satker b ON a.id_satker = b.id
						LEFT JOIN db_ortala.jabatan c ON a.id_jabatan = c.id
						WHERE a.status = 1";
				break;

			case "tenaga-ahli":
				$sql = "SELECT a.id, nik AS nomor, a.nama, 
						CASE 
							WHEN a.departemen = 'Anggota Dewan' 
							THEN CONCAT('A-', LPAD(b.id_divisi, 3, '0'), ' (', b.fraksi, ')')
							ELSE b.divisi
						END AS unit_kerja, 
						a.posisi AS jabatan, '' AS golongan, '' AS eselon
						FROM db_sitanang.ta a
						LEFT JOIN (
							SELECT id, divisi, id_divisi, departemen, fraksi
							FROM db_sitanang.bidang 
							UNION 
							SELECT id, nama AS divisi, no_anggota AS id_divisi, 'Anggota Dewan' AS departemen, fraksi
							FROM db_minangwan.view_anggota_sitanang_aktif
						) b ON a.departemen = b.departemen AND a.id_bidang = b.id
						WHERE a.status = 1";
				break;

			case "pihak-ketiga":
				$sql = "SELECT id, nomor, nama, unit_kerja, jabatan, golongan, '' AS eselon
						FROM db_perjadin.pihak_ketiga
						WHERE status = 1";
				break;
			
			case "unit":
				$sql = "SELECT id, kode_unit, nama_unit
						FROM db_perencanaan.unit
						WHERE status = 1";
				break;
			
			case "propinsi":
				$sql = "SELECT id, kode_propinsi, nama_propinsi
						FROM propinsi
						WHERE status = 1";
				break;
		}

		$db = Zend_Registry::get('db');		
		$stmt = $db->query($sql);
		$stmt->setFetchMode(Zend_Db::FETCH_OBJ); 
		$result = $stmt->fetchAll();
		return $result;
	}

	function getData($id, $id_pengguna)
	{ 
		switch($id)
		{
			case "fraksi":
				$sql = "SELECT id, fraksi AS nama
						FROM db_minangwan.fraksi";
				break;

			case "satker":
				$sql = "SELECT a.id, a.nama_satker AS nama, a.id_akd, b.singkatan
						FROM db_siap.satker a
						JOIN db_minangwan.akd b ON a.id_akd = b.id
						WHERE a.id = " . $id_pengguna;
				break;

			case "anggota":
				$sql = "SELECT id, CONCAT('A-', REPEAT('0', 3-LENGTH(no_anggota)), no_anggota) AS nomor, nama, fraksi AS unit_kerja, 
						'' AS jabatan, 'Anggota' AS golongan, '' AS eselon
						FROM db_minangwan.view_anggota_sitanang_aktif
						WHERE id = " . $id_pengguna;
				break;

			case "pegawai":
				$sql = "SELECT a.id, a.nip AS nomor, a.nama, b.nama_satker AS unit_kerja, 
						c.nama_jabatan AS jabatan, SUBSTRING_INDEX(a.golongan, '/', 1) AS golongan, 
						CASE 
							WHEN c.nama_jabatan = 'Sekretaris Jenderal' THEN 1
							WHEN c.nama_jabatan = 'Wakil Sekretaris Jenderal' THEN 1
							WHEN c.nama_jabatan LIKE 'Deputi%' THEN 1
							WHEN c.nama_jabatan LIKE 'Kepala Biro%' OR c.nama_jabatan LIKE 'Kepala Pusat%' THEN 2 
							WHEN c.nama_jabatan LIKE 'Kepala Bagian%' OR c.nama_jabatan LIKE 'Kepala Bidang%' THEN 3
							WHEN c.nama_jabatan LIKE 'Kepala Subbagian%' OR c.nama_jabatan LIKE 'Kepala Subbidang%' THEN 4
							ELSE ''
						END AS eselon
						FROM db_siap.pegawai a
						LEFT JOIN db_siap.satker b ON a.id_satker = b.id
						LEFT JOIN db_ortala.jabatan c ON a.id_jabatan = c.id
						WHERE a.id = " . $id_pengguna;
				break;

			case "tenaga-ahli":
				$sql = "SELECT a.id, nik AS nomor, a.nama, 
						CASE 
							WHEN a.departemen = 'Anggota Dewan' 
							THEN CONCAT('A-', LPAD(b.id_divisi, 3, '0'), ' (', b.fraksi, ')')
							ELSE b.divisi
						END AS unit_kerja, 
						a.posisi AS jabatan, '' AS golongan, '' AS eselon
						FROM db_sitanang.ta a
						LEFT JOIN (
							SELECT id, divisi, id_divisi, departemen, fraksi
							FROM db_sitanang.bidang 
							UNION 
							SELECT id, nama AS divisi, no_anggota AS id_divisi, 'Anggota Dewan' AS departemen, fraksi
							FROM db_minangwan.view_anggota_sitanang_aktif
						) b ON a.departemen = b.departemen AND a.id_bidang = b.id
						WHERE a.id = " . $id_pengguna;
				break;

			case "pihak-ketiga":
				$sql = "SELECT id, nomor, nama, unit_kerja, jabatan, golongan, '' AS eselon
						FROM db_perjadin.pihak_ketiga
						WHERE id = " . $id_pengguna;
				break;
		}

		$db = Zend_Registry::get('db');		
		$stmt = $db->query($sql);
		$stmt->setFetchMode(Zend_Db::FETCH_OBJ); 
		$result = $stmt->fetch();
		return $result;
	}
	
	function getAllDataPegawaiProtokol()
	{ 	
		$sql = 'SELECT  a.id, a.nama, a.nip, b.nama_jabatan
			FROM db_siap.pegawai a
			LEFT JOIN db_ortala.jabatan b ON b.id = a.id_jabatan 
			WHERE a.id_satker IN (120,143,144,145) AND a.status = 1 ';

		$db = Zend_Registry::get('db');		
		$stmt = $db->query($sql);
		$stmt->setFetchMode(Zend_Db::FETCH_OBJ); 
		$result = $stmt->fetchAll();
		return $result;
	}

}