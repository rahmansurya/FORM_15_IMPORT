<?php
require_once "../conn_sqlsrv.php";

$filename = "LBBPRK-1500-R-M-20240531-602047-01.txt";

// Extract the period using regular expression
preg_match('/\d{8}/', $filename, $matches);

// Check if a date was found
if (isset($matches[0])) {
  $periode = $matches[0];
} else {
  $periode = "Date not found in filename";
}

echo "Filename: " . $filename . "<br>";
echo "Periode: " . $periode. "<br>";;


try {
    // Menghapus data di tabel form_06 untuk periode yang diberikan
    $deleteSql = "DELETE FROM FORM_15 WHERE PERIODE = ".$periode."";
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
    $sql = "INSERT INTO FORM_15 (
                FLAG, SANDI_KANTOR, NO_CIF, NO_REK, JENIS, GOLDEB, HUBUNGAN_BANK, TGL_HAPUS_BUKU, SALDO_POKOK, AKUM_TERTAGIH, 
				SALDO_POKOK_POS, TUNG_BUNGA, TUNG_BUNGA_AKUM, AKUM_TAMBAHAN, SALDO_BUNGA_POS, JENIS_AGUNAN, ALAMAT_AGUNAN, NILAI_AGUNAN, PERIODE
            ) VALUES (
			?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 
			?, ?, ?, ?, ?, ?, ?, ?, ? 
			)";
			
    // Membaca setiap baris file TXT
    while (($line = fgets($file)) !== false) {
        $data = explode("|", trim($line));

        // Periksa apakah jumlah kolom sesuai
        //if (count($data) != 47) {
		if (count($data) != 18) {
            throw new Exception("Jumlah kolom tidak sesuai pada baris: " . $line);
        }
        $FLAG = $data[0];		
        $SANDI_KANTOR = $data[1];
        $NO_CIF = $data[2];       
        $NO_REK = $data[3];
        $JENIS = $data[4];
		$GOLDEB = $data[5];
		$HUBUNGAN_BANK = $data[6];
		$TGL_HAPUS_BUKU = $data[7];
		$SALDO_POKOK = $data[8];
		$AKUM_TERTAGIH = $data[9];
		$SALDO_POKOK_POS = $data[10];
		$TUNG_BUNGA = $data[11];
		$TUNG_BUNGA_AKUM = $data[12];
		$AKUM_TAMBAHAN = $data[13];
		$SALDO_BUNGA_POS = $data[14];
		$JENIS_AGUNAN = $data[15];
		$ALAMAT_AGUNAN = $data[16];
		$NILAI_AGUNAN = $data[17];

		$PERIODE = $periode;
        // Persiapan parameter untuk query
        $params = array(
            $FLAG, 
			$SANDI_KANTOR, $NO_CIF, $NO_REK, $JENIS, $GOLDEB, $HUBUNGAN_BANK, $TGL_HAPUS_BUKU, $SALDO_POKOK, $AKUM_TERTAGIH, $SALDO_POKOK_POS, 
			$TUNG_BUNGA, $TUNG_BUNGA_AKUM, $AKUM_TAMBAHAN, $SALDO_BUNGA_POS, $JENIS_AGUNAN, $ALAMAT_AGUNAN, $NILAI_AGUNAN, 
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
} catch (Exception $e) {
    echo "Terjadi kesalahan: " . $e->getMessage();
}

sqlsrv_close($conn);
?>
