<?php
include('includes/config.php');

// Variabel untuk menyimpan data yang akan di-scrape
$linkToScrape = '';
$title = '';
$paragraphs = '';
$images = [];

// Form pertama telah disubmit
if (isset($_POST['submitForm1'])) {
    // Mengambil tautan dari formulir pertama
    $linkToScrape = $_POST['linkToScrape'];

    // Inisialisasi cURL session
    $ch = curl_init($linkToScrape);

    // Set opsi cURL untuk mengambil konten
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // Eksekusi cURL dan dapatkan kontennya
    $response = curl_exec($ch);

    // Tutup cURL session
    curl_close($ch);

    // Buat objek DOMDocument
    $dom = new DOMDocument();

    // Muat HTML dari respons cURL ke dalam objek DOMDocument
    @$dom->loadHTML($response);

    // Cari elemen berdasarkan kelas atau ID atau tag tertentu
    // Misalnya, jika Anda ingin mengambil judul berita, Anda bisa mencari elemen dengan tag 'title'
    $titleElement = $dom->getElementsByTagName('title')->item(0);
    $pElements = $dom->getElementsByTagName('p');
    $title = $titleElement ? $titleElement->nodeValue : 'Tidak dapat menemukan judul berita';

    // Dapatkan teks dari setiap elemen 'p'
    foreach ($pElements as $pElement) {
        $paragraph = $pElement->nodeValue;
        // Append each paragraph to the $paragraphs array
        $paragraphs .= $paragraph . "\n";

        // Pemeriksaan panjang teks untuk memastikan bahwa itu adalah kalimat atau paragraf
        if (strlen($paragraph) > 10) {
            $p[] = $paragraph;
        }
    }

    // Dapatkan semua elemen gambar
    $imgElements = $dom->getElementsByTagName('img');
    foreach ($imgElements as $imgElement) {
        $imageSrc = $imgElement->getAttribute('src');
        if ($imageSrc) {
            $images[] = $imageSrc;
        }
    }

    // Setelah scraping, tampilkan formulir kedua
    $showForm2 = true;
} elseif (isset($_POST['submitForm2'])) {
    // Formulir kedua telah disubmit
    // Proses penyimpanan ke database
    $link = $_POST['link'];
    $title = $_POST['title'];
    $paragraphs = implode("\n", $_POST['paragraphs']);

    // Lakukan validasi data jika diperlukan

    // Simpan ke database
    $sql = "INSERT INTO online_posts (link, title, description) VALUES ('$link', '$title', '$paragraphs')";
    $result = mysqli_query($con, $sql);

    // Cek apakah penyimpanan berhasil
    if ($result) {
        echo "Data berhasil disimpan ke database.";
    } else {
        echo "Error: " . mysqli_error($cnn);
    }
    exit; // Setelah penyimpanan ke database, hentikan eksekusi script
} elseif (isset($_POST['restoreParagraph'])) {
    // Tombol restore paragraph diklik
    // Ambil index paragraph yang dihapus
    $index = $_POST['index'];

    // Tambahkan kembali paragraph yang dihapus ke dalam $p array
    $restoredParagraph = $_POST['restoredParagraph'];
    $p[$index] = $restoredParagraph;

    // Hapus paragraph yang dihapus dari array deletedParagraphs
    unset($_POST['deletedParagraphs'][$index]);

    // Kembalikan formulir kedua dengan menampilkan kembali paragraph yang dihapus
    $showForm2 = true;
} else {
    $showForm2 = false;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Post Online</title>
</head>

<body>
    <?php if (!$showForm2) { ?>
        <!-- Formulir Pertama -->
        <h2>Formulir Pertama</h2>
        <form method="post" action="">
            <label for="linkToScrape">Masukkan Tautan:</label>
            <input type="text" name="linkToScrape" id="linkToScrape" required>
            <button type="submit" name="submitForm1">Submit</button>
        </form>
    <?php } else { ?>
        <!-- Formulir Kedua -->
        <h2>Formulir Kedua</h2>
        <form method="post" action="">
            <label for="title">Nama Berita:</label>
            <input type="text" name="title" id="title" value="<?php echo $title; ?>" readonly>
            <br>
            <label for="link">Link:</label>
            <input type="text" name="link" id="link" value="<?php echo $linkToScrape; ?>" readonly>
            <br>
            <label for="paragraphs">Paragraphs:</label>
            <?php foreach ($p as $index => $paragraph) { ?>
                <div style="display: flex; align-items: center;" id="paragraphDiv<?php echo $index; ?>">
                    <textarea name="paragraphs[]" id="paragraph<?php echo $index; ?>" rows="4" readonly><?php echo $paragraph; ?></textarea>
                    <button type="button" onclick="hapusParagraph(<?php echo $index; ?>)">Hapus</button>
                </div>
            <?php } ?>
            <br>
            <?php foreach ($images as $image) { ?>
                <img src="<?php echo $image; ?>" alt="Scraped Image">
            <?php } ?>
            <br>
            <button type="submit" name="submitForm2">Simpan ke Database</button>
        </form>
    <?php } ?>

    <script>
        function hapusParagraph(index) {
            var textarea = document.getElementById('paragraph' + index);
            var paragraphDiv = document.getElementById('paragraphDiv' + index);
            
            // Simpan paragraph yang dihapus ke dalam input hidden
            var inputHidden = document.createElement('input');
            inputHidden.type = 'hidden';
            inputHidden.name = 'deletedParagraphs[]';
            inputHidden.value = textarea.value;
            
            // Hapus textarea dan tombol hapus
            paragraphDiv.removeChild(textarea);
            paragraphDiv.removeChild(event.currentTarget);
            
            // Tambahkan tombol restore
            var restoreButton = document.createElement('button');
            restoreButton.type = 'button';
            restoreButton.textContent = 'Restore';
            restoreButton.onclick = function() { restoreParagraph(index); };
            
            // Sisipkan input hidden dan tombol restore ke dalam form
            var form = document.querySelector('form');
            form.appendChild(inputHidden);
            form.appendChild(restoreButton);
        }

        function restoreParagraph(index) {
            // Ambil paragraph yang dihapus dari input hidden
            var deletedParagraphInput = document.querySelector('input[name="deletedParagraphs[]"]');
            var restoredParagraph = deletedParagraphInput.value;

            // Hapus input hidden
            deletedParagraphInput.parentNode.removeChild(deletedParagraphInput);

            // Tambahkan kembali paragraph yang dihapus ke dalam textarea
            var textarea = document.getElementById('paragraph' + index);
            textarea.value = restoredParagraph;

            // Hapus tombol restore
            var restoreButton = event.currentTarget;
            restoreButton.parentNode.removeChild(restoreButton);
        }
    </script>
</body>

</html>
