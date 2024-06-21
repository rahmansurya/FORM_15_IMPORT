Untuk Form 06.00 
Line 115 Rubah s/d line 160
/*
try {
    // Menghapus data di tabel form_06 untuk periode yang diberikan
    $deleteSql = "DELETE FROM form_06 WHERE PERIODE = ".$periode."";
    $deleteParams = array($periode);
    $deleteStmt = sqlsrv_query($conn, $deleteSql, $deleteParams);
    if ($deleteStmt === false) {
        throw new Exception(print_r(sqlsrv_errors(), true));
    }	
	
    // Membaca file TXT
    $file = fopen($filename, "r");
    if ($file === false) {
        throw new Exception("Tidak dapat membuka file: $filename");
    }
    // Mengabaikan baris pertama (header)
    fgets($file);

    // Persiapan query insert
    $sql = "INSERT INTO form_06 (
                FLAG, 
				SANDI_KANTOR, NOMOR_CIF, NO_IDENTITAS, KODE_KREDIT, NO_REK, JENIS, STATUS_RESTRU, JENIS_PENGGUNAAN, HUB_BANK, SUMBER_DANA, 
				PERIODE_POKOK, PERIODE_BUNGA, TGL_MULAI, TGL_JTEMPO, TGL_MULAI_BAYAR, KUALITAS, TGL_MULAI_MACET, HARI_TUNG_POKOK, HARI_TUNG_BUNGA, NOM_TUNG_POKOK, 
				NOM_TUNG_BUNGA, GOLDEB, SANDI_BANK, SEKON, KATEGORI_USAHA, LOKASI_PENGGUNAAN, SUKU_BUNGA_PERSEN, SUKU_BUNGA_HITUNG, GOL_PENJAMIN,BAGIAN_DIJAMIN, 
				NILAI_AGUNAN_LIKUID, PPAP_NON_LIKUID, KELONGGARAN_TARIK, PLAFOND_AWAL, PLAFOND, BAKI_DEBET, PROVISI, PROVISI_BLMAMOR, BIAYA_TRANS, PEND_BUNGA_TANGGUH,
				CADANGAN_RESTRU, BAKI_DEBET_NET, PPAPWD, KELEBIHAN_PPAPWD, PEND_BUNGA_PYD, PEND_BUNGA_DPY, STATUS_BMPK, 
				PERIODE
            ) VALUES (
			?, 
			?, ?, ?, ?, ?, ?, ?, ?, ?, ?,
			?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 
			?, ?, ?, ?, ?, ?, ?, ?, ?, ?,
			?, ?, ?, ?, ?, ?, ?, ?, ?, ?,
			?, ?, ?, ?, ?, ?, ?, 
			?)";
			
    // Membaca setiap baris file TXT
    while (($line = fgets($file)) !== false) {
        $data = explode("|", trim($line));

        // Periksa apakah jumlah kolom sesuai
        //if (count($data) != 47) {
		if (count($data) != 47) {
            throw new Exception("Jumlah kolom tidak sesuai pada baris: " . $line);
        }
         $FLAG = $data[0];
		
        $SANDI_KANTOR = $data[1];
        $NOMOR_CIF = $data[2];
        $NO_IDENTITAS = $data[3];
        $KODE_KREDIT = $data[4];
        $NO_REK = $data[5];
        $JENIS = $data[6];
        $STATUS_RESTRU = $data[7];
        $JENIS_PENGGUNAAN = $data[8];
        $HUB_BANK = $data[9];
		$SUMBER_DANA = $data[10];
		
        $PERIODE_POKOK = $data[11];
        $PERIODE_BUNGA = $data[12];
        $TGL_MULAI = $data[13];
        $TGL_JTEMPO = $data[14];
        $TGL_MULAI_BAYAR = $data[15];
        $KUALITAS = $data[16];
        $TGL_MULAI_MACET = $data[17];
        $HARI_TUNG_POKOK = (int)$data[18];
        $HARI_TUNG_BUNGA = (int)$data[19];	
        $NOM_TUNG_POKOK = (int)$data[20];
		
        $NOM_TUNG_BUNGA = (int)$data[21];
        $GOLDEB = $data[22];
        $SANDI_BANK = $data[23];
        $SEKON = $data[24];
        $KATEGORI_USAHA = $data[25];
        $LOKASI_PENGGUNAAN = $data[26];
        $SUKU_BUNGA_PERSEN = (float)$data[27];
        $SUKU_BUNGA_HITUNG = $data[28];
        $GOL_PENJAMIN = $data[29];		
        $BAGIAN_DIJAMIN = (float)$data[30];
		
        $NILAI_AGUNAN_LIKUID = (int)$data[31];
        $PPAP_NON_LIKUID = (int)$data[32];
        $KELONGGARAN_TARIK = (int)$data[33];
        $PLAFOND_AWAL = (int)$data[34];
        $PLAFOND = (int)$data[35];
        $BAKI_DEBET = (int)$data[36];
		$PROVISI = "0"; // Menambahkan nilai 0 untuk kolom PROVISI
        $PROVISI_BLMAMOR = (int)$data[37];
        $BIAYA_TRANS = (int)$data[38];		
        $PEND_BUNGA_TANGGUH = (int)$data[39];
		
        $CADANGAN_RESTRU = (int)$data[40];
        $BAKI_DEBET_NET = (int)$data[41];
        $PPAPWD = (int)$data[42];
        $KELEBIHAN_PPAPWD = (int)$data[43];
        $PEND_BUNGA_PYD = (int)$data[44];
        $PEND_BUNGA_DPY = (int)$data[45];
        $STATUS_BMPK = $data[46];		
		
		
		$PERIODE = $periode;
        // Persiapan parameter untuk query
        $params = array(
            $FLAG, 
			$SANDI_KANTOR, $NOMOR_CIF, $NO_IDENTITAS, $KODE_KREDIT, $NO_REK, $JENIS, $STATUS_RESTRU, $JENIS_PENGGUNAAN, $HUB_BANK, $SUMBER_DANA, 
			$PERIODE_POKOK, $PERIODE_BUNGA, $TGL_MULAI, $TGL_JTEMPO, $TGL_MULAI_BAYAR, $KUALITAS, $TGL_MULAI_MACET, $HARI_TUNG_POKOK, $HARI_TUNG_BUNGA, $NOM_TUNG_POKOK, 
			$NOM_TUNG_BUNGA, $GOLDEB, $SANDI_BANK, $SEKON, $KATEGORI_USAHA, $LOKASI_PENGGUNAAN, $SUKU_BUNGA_PERSEN, $SUKU_BUNGA_HITUNG, $GOL_PENJAMIN, $BAGIAN_DIJAMIN, 
			$NILAI_AGUNAN_LIKUID, $PPAP_NON_LIKUID, $KELONGGARAN_TARIK, $PLAFOND_AWAL, $PLAFOND, $BAKI_DEBET, $PROVISI, $PROVISI_BLMAMOR, $BIAYA_TRANS,
			$PEND_BUNGA_TANGGUH, $CADANGAN_RESTRU, $BAKI_DEBET_NET, $PPAPWD, $KELEBIHAN_PPAPWD, $PEND_BUNGA_PYD, $PEND_BUNGA_DPY, $STATUS_BMPK, 
			$PERIODE
        );		
        // Eksekusi query
        $stmt = sqlsrv_query($conn, $sql, $params);
        if ($stmt === false) {
            throw new Exception(print_r(sqlsrv_errors(), true));
        }
    }

    fclose($file);
    echo "Data berhasil diimport.";
}
*/
