<!DOCTYPE html>
<html lang="en">
<head>
    <title>FORM 15</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="bootstrap.min.css">
	<link rel="stylesheet" href="css/font-awesome-4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="css/dropzone.css">  
</head>
<body>

<div class="card text-center" style="padding:20px;">
  <h3>Lapbul Import TXT to LBBPRK</h3>
</div>
<br>

<div class="container">
  <div class="row">
    <div class="col-md-3"></div>
      <div class="col-md-6">
        <div id="message"></div>
        <form action="upload.php" class="dropzone" enctype="multipart/form-data"></form><br>
        <button class="btn btn-success" style="float: left;" onclick="refreshPage()">Refresh</button>
		<button class="btn btn-success" style="float: right;" id="uploadBtn">Upload</button>		
		<script>
			function refreshPage() {
				window.location.reload();
			}
		</script>		
      </div>
  </div>
</div>
<?php 
require_once "../conn_sqlsrv.php";

// Menentukan direktori
$dir = "uploads/";

// Mendapatkan daftar file
$files = scandir($dir);
$files = array_diff($files, array('.', '..'));

// Memeriksa apakah folder kosong
if (empty($files)) {
    echo "Tidak ada file di folder uploads.";
} else {
    echo "<br>Daftar Nama File:<br>";
    foreach ($files as $filename) {
        
        // Ekstrak periode menggunakan regular expression
        preg_match('/\d{8}/', $filename, $matches);

        // Memeriksa apakah tanggal ditemukan
        if (isset($matches[0])) {
            $periode = $matches[0];
        } else {
            die("Tanggal tidak ditemukan dalam nama file.");
        }

        // Query untuk memeriksa apakah periode sudah ada di database
        $sql = "SELECT COUNT(*) as count FROM form_15 WHERE periode = ?";
        $params = array($periode);
        $stmt = sqlsrv_query($conn, $sql, $params);

        if ($stmt === false) {
            die(print_r(sqlsrv_errors(), true));
        }

        $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
echo "
<div class='container'>
	<div class='row'>
		<div class='col-sm'>";
        if ($row['count'] > 0) {
			echo "<a href='?page=proses&filename=$filename'>$filename - $periode</a> <i class='fa fa-check-square' style='color:green;'></i><br>";
        } else {
            echo "<a href='?page=proses&filename=$filename'>$filename - $periode</a> <i class='fa fa-window-close-o' style='color:red;'></i><br>";
        }
echo "
		</div>   
  </div>
</div>
";	
		
		
    }
}

if (isset($_GET['page']) && isset($_GET['filename'])) {
    // Mendapatkan nama file dan memastikan tidak ada karakter berbahaya
    $filename = basename($_GET['filename']);
    $filepath = "uploads/$filename";

    // Mengecek apakah file ada
    if (!file_exists($filepath)) {
        die("File tidak ditemukan.");
    }

    // Membaca data file
    $fileData = file_get_contents($filepath);

    // Extract the period using regular expression
    preg_match('/\d{8}/', $filename, $matches);

    // Check if a date was found
    if (isset($matches[0])) {
        $periode = $matches[0];
    } else {
        die("Tanggal tidak ditemukan dalam nama file.");
    }

    echo "Periode: " . $periode . "<br>";

    try {
        // Menghapus data di tabel FORM_15 untuk periode yang diberikan
        $deleteSql = "DELETE FROM FORM_15 WHERE PERIODE = ?";
        $deleteParams = array($periode);
        $deleteStmt = sqlsrv_query($conn, $deleteSql, $deleteParams);
        if ($deleteStmt === false) {
            throw new Exception(print_r(sqlsrv_errors(), true));
        }

        // Membaca file TXT
        $file = fopen($filepath, "r");
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
            if (count($data) != 18) {
                throw new Exception("Jumlah kolom tidak sesuai pada baris: " . $line);
            }

            // Persiapan parameter untuk query
            $params = array_merge($data, array($periode));

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
}

?>




<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script type="text/javascript" src="js/dropzone-min.js"></script>

<script type="text/javascript">
//Disabling autoDiscover
Dropzone.autoDiscover = false;
$(function() {
  //Dropzone class
  var myDropzone = new Dropzone(".dropzone", {
      url: "upload.php",
      paramName: "file",
      parallelUploads:12,
      uploadMultiple:true,
      acceptedFiles: '.txt',
      autoProcessQueue: false,
      success:function(file, response){
        //if (response == "true") {
		if (file.type != "txt" && file.type != "image/jpg") {
            $("#message").append("<div class='alert alert-success'>Files Uploaded successfully</div>");
        } else {
            $("#message").append("<div class='alert alert-danger'>Files can not uploaded</div>");
        } 
	// Auto-refresh the page immediately
	location.reload();
      } 
	  
  });
    
  $('#uploadBtn').click(function(){    
    myDropzone.processQueue();
  });

});
</script>




</body>
</html>
