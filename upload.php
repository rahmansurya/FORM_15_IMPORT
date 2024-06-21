<?php
if (!empty($_FILES['file']['name'][0])) {
    $totalFiles = count($_FILES['file']['name']);
    $inserted = 0;

    for ($i = 0; $i < $totalFiles; $i++) {
        $fileName = $_FILES['file']['name'][$i];
        $extension = pathinfo($fileName, PATHINFO_EXTENSION);
        $allowExtn = array('png', 'jpeg', 'jpg', 'txt', 'json');

        if (in_array($extension, $allowExtn)) {
            $newName = $fileName; // Biarkan nama file tetap sama

            /* Pecah nama file */
            $filename_pecah = $newName;			
            $parts = explode('_', $filename_pecah); // Memisahkan nama file berdasarkan karakter underscore ('_')
            if (count($parts) === 2) {
                $ktpNumber = str_replace('.txt', '', $parts[1]); // Menghapus ekstensi ".txt"
            }

            $uploadFilePath = "uploads/" . $newName;
            if (move_uploaded_file($_FILES['file']['tmp_name'][$i], $uploadFilePath)) {					
				$file_link = $uploadFilePath;
				

				
            }
        }
    }

    if ($inserted == $totalFiles) {
        echo "true";
    } else {
        echo "false";
    }
}
?>


