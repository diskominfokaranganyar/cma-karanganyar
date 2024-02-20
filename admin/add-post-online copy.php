<?php
$Data = '{"link":"https://news.detik.com/pemilu/d-7199563/prabowo-gibran-unggul-di-quick-count-cucun-ungkap-kondisi-internal-pkb","title":"Prabowo-Gibran Unggul di Quick Count, Cucun Ungkap Kondisi Internal PKB","paragraphs":["Ketua DPP PKB Cucun Ahmad Syamsurijal menjelaskan kondisi di internal partai usai beberapa lembaga survei menempatkan pasangan calon nomor urut 2, Prabowo Subianto dan Gibran Rakabuming Raka, di posisi teratas. PKB sendiri masih menunggu hasil dari KPU.","\"Di internal PKB kita sudah lakukan rapat koordinasi tidak ada hal-hal yang menjadi hal yang luar biasa, tetap saja menjalankan bagaimana setiap saat mengevaluasi pemenangan,\" kata Cucun kepada wartawan di Kantor DPP PKB, Jalan Raden Saleh, Jakarta Pusat, Minggu (18/2/2024).","Cucun menyebut pihaknya tengah mengumpulkan hasil pemungutan suara dari para saksi di TPS. \"Kemudian juga kondisi struktur kita bahkan laporan-laporan saksi-saksi di TPS semua dilakukan, tidak ada yang beda, ya tetap saja seperti biasa,\" katanya.","\r\nADVERTISEMENT\r\n","\r\nSCROLL TO CONTINUE WITH CONTENT\r\n","Cucun mengatakan PKB belum menentukan sikap untuk bergabung dengan pemerintahan mendatang atau menjadi bagian oposisi. PKB masih menunggu hasil pemilu 2024.","\"Tadi yang saya sampaikan, kita lihat dulu hasil pemilu ini kalau terkait rekam jejak bagaimana selama ini ada di pemerintahan, itu akan ditentukan bukan oleh sendiri- sendiri, tapi melalui rapat yang resmi struktur di PKB termasuk di Timnas AMIN ada pembicaraan,\" ungkapnya.","Cucun menyebut hingga saat ini belum ada kesimpulan terkait posisi PKB ke depan. Ia mengatakan komunikasi dengan kubu lain juga belum terlaksana.","\"Tidak berbicara masalah PKB ada kesiapan atau melakukan suatu sikap koalisi atau oposisi belum ada sampai sekarang,\" ujar Cucun.","\"Belum ada sampai sekarang (komunikasi). Peta semacam itu tidak akan berbanding lurus dengan langkah-langkah karena setiap partai punya integrity-nya ya, setiap partai punya sikap yang pasti akan diambil dan itu pasti,\" sambungnya."]}';

// Decode data JSON
$dataDecoded = json_decode($Data, true);

// Mendapatkan link, title, dan paragraphs
$link = $dataDecoded['link'];
$title = $dataDecoded['title'];
$paragraphs = $dataDecoded['paragraphs'];

// Menggabungkan paragraphs menjadi satu teks
$fullParagraph = implode(' ', $paragraphs);

// Menampilkan hasil
echo "Link: $link<br>";
echo "Title: $title<br>";
echo "Full Paragraph: $fullParagraph<br>";
?>


<?php
include('includes/config.php');

$l;
$t;
$p = [];

if (isset($_POST['scrap'])) {
    // Form telah disubmit, proses link yang dimasukkan
    $url = $_POST['link'];
    $link = $url;

    // Inisialisasi cURL session
    $ch = curl_init($url);

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

    $l = $url;
    $t = $title;

    // Dapatkan teks dari setiap elemen 'p'
    foreach ($pElements as $pElement) {
        $paragraph = $pElement->nodeValue;
        // Append each paragraph to the $p array
        $p[] = $paragraph;

        // Pemeriksaan panjang teks untuk memastikan bahwa itu adalah kalimat atau paragraf
        if (strlen($paragraph) > 10) {
            $paragraphs[] = $paragraph;
        }
    }
}

$Data = "<script>document.write(localStorage.getItem('beritaData'));</script>";

if (isset($_POST['simpan'])) {
    // Mendapatkan data dari localStorage (JavaScript) yang telah disimpan sebelumnya
    // $dataFromLocalStorage = json_decode($_POST['beritaData'], true);
    // echo $dataFromLocalStorage;
    // Memasukkan data ke dalam variabel
    // $link = $Data['link'];
    // $title = $Data['title'];
    // $paragraphs = $Data['paragraphs'];
    echo $Data;

    // Membagi string data menjadi komponen yang sesuai
    $parts = explode('","', $Data);

    // Mendapatkan link
    $link = trim($parts[0], '["');

    // Mendapatkan title
    $title = trim($parts[1]);

    // Mendapatkan paragraphs
    $paragraphsRaw = trim($parts[2], '["]');
    $paragraphs = json_decode('["' . $paragraphsRaw . '"]', true);

    // Menampilkan hasil
    echo "HAII";
    echo "Link: $link<br>";
    echo "Title: $title<br>";
    echo "Paragraphs: <pre>" . print_r($paragraphs, true) . "</pre>";

    // Menyimpan data ke database
    // $sql = "INSERT INTO online_posts (link, title, description) VALUES ('$link', '$title', '" . json_encode($paragraphs) . "')";

    // if ($con->query($sql) === TRUE) {
    //     echo "Data berhasil disimpan ke database.";
    // } else {
    //     echo "Error: " . $sql . "<br>" . $con->error;
    // }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="A fully featured admin theme which can be used to build CRM, CMS, etc.">
    <meta name="author" content="Coderthemes">

    <!-- App favicon -->
    <link rel="shortcut icon" href="assets/images/favicon.ico">
    <!-- App title -->
    <title>Tambah Berita Online</title>
    <link rel="icon" type="image/x-icon" href="../asset/Logo.png">

    <!-- Summernote css -->
    <link href="../plugins/summernote/summernote.css" rel="stylesheet" />

    <!-- Select2 -->
    <link href="../plugins/select2/css/select2.min.css" rel="stylesheet" type="text/css" />

    <!-- Jquery filer css -->
    <link href="../plugins/jquery.filer/css/jquery.filer.css" rel="stylesheet" />
    <link href="../plugins/jquery.filer/css/themes/jquery.filer-dragdropbox-theme.css" rel="stylesheet" />

    <!-- App css -->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/core.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/components.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/icons.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/pages.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/menu.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/responsive.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="../plugins/switchery/switchery.min.css">
    <script src="assets/js/modernizr.min.js"></script>
</head>

<body class="fixed-left">

    <!-- Begin page -->
    <div id="wrapper">

        <!-- Top Bar Start -->
        <?php include('includes/topheader.php'); ?>
        <!-- ========== Left Sidebar Start ========== -->
        <?php include('includes/leftsidebar.php'); ?>
        <!-- Left Sidebar End -->

        <div class="content-page">
            <!-- Start content -->
            <div class="content">
                <div class="container">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="page-title-box">
                                <h4 class="page-title">Tambah Berita Online </h4>
                                <ol class="breadcrumb p-0 m-0">
                                    <li>
                                        <a href="#">Admin</a>
                                    </li>
                                    <li>
                                        <a href="#">Berita Online</a>
                                    </li>
                                    <li class="active">
                                        Tambah Berita Online
                                    </li>
                                </ol>
                                <div class="clearfix"></div>
                            </div>
                        </div>
                    </div>
                    <!-- end row -->

                    <div class="row">
                        <div class="col-md-10 col-md-offset-1">
                            <div class="p-6">
                                <div class="">
                                    <form name="scrap" method="post" enctype="multipart/form-data">
                                        <div class="form-group m-b-20">
                                            <?php
                                            // echo $sql;
                                            // echo $dataFromLocalStorage;
                                            // echo $Data;
                                            // echo $Data['link'];
                                            // echo $Data['title'];
                                            // echo $Data['paragraphs'];
                                            ?>
                                            <label for="exampleInputEmail1">Link Berita</label>
                                            <input type="text" class="form-control" id="link" name="link" placeholder="Masukkan Link Berita" required>
                                        </div>
                                        <button type="submit" name="scrap" class="btn btn-success waves-effect waves-light">Scrap Berita</button>
                                        <button type="button" class="btn btn-danger waves-effect waves-light">Discard</button>
                                    </form>
                                    <!-- end row -->
                                </div>
                            </div> <!-- end p-20 -->
                        </div> <!-- end col -->
                    </div>
                    <!-- end row -->

                    <!-- Hasil Scrap Berita -->

                    <form name="add" method="post" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-md-10 col-md-offset-1">
                                <div class="p-6" style="margin-top: 28px;">
                                    <div class="form-group m-b-20 mt-4">
                                        <h4 class="mt-4">
                                            <label for="exampleInputEmail1">Judul Berita</label>
                                        </h4>
                                        <input type="text" class="form-control" value="<?php echo $title; ?>" readonly>
                                    </div>
                                </div> <!-- end p-20 -->
                                <?php if ($_SERVER["REQUEST_METHOD"] == "POST") {
                                    foreach ($paragraphs as $index => $paragraph) { ?>
                                        <div class="form-group m-b-20 w-full" id="paragraph-<?php echo $index; ?>">
                                            <div style="display: flex; flex-direction: row; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                                                <h4>
                                                    <label for="exampleInputEmail1">Paragraf <?php echo $index + 1 ?></label>
                                                </h4>
                                                <button type="button" class="btn btn-danger" onclick="hapusTextarea(<?php echo $index; ?>)">Hapus</button>
                                            </div>
                                            <textarea class="form-control mt-4 " rows="5" readonly><?php echo $paragraph; ?></textarea>
                                        </div>
                                    <?php }
                                    ?>
                                    <input type="hidden" name="beritaData" id="beritaData" />
                                    <button type="submit" name="simpan" class="btn btn-success waves-effect waves-light" onclick=simpanKeLocalStorage()>Simpan Berita</button>
                            </div> <!-- end col -->
                        </div>
                    </form>
                    <!-- end row -->
                <?php } ?>

                </div> <!-- container -->

            </div> <!-- content -->

            <?php include('includes/footer.php'); ?>
        </div>


        <!-- ============================================================== -->
        <!-- End Right content here -->
        <!-- ============================================================== -->


    </div>
    <!-- END wrapper -->

    <!-- Tambahkan script ini di bagian head atau sebelum tag </body> -->
    <script>
        // Fungsi untuk menyimpan data ke localStorage
        function simpanKeLocalStorage() {
            // Mendapatkan data yang ingin disimpan
            var dataToSave = {
                link: "<?php echo $l; ?>",
                title: "<?php echo $t; ?>",
                paragraphs: <?php echo json_encode($paragraphs); ?>
            };

            // Menyimpan data ke localStorage dengan kunci "beritaData"
            localStorage.setItem("beritaData", JSON.stringify(dataToSave));

            // Menampilkan data di console.log
            console.log("Data Berita Disimpan di localStorage:", dataToSave);
        }

        // Menambahkan event listener pada tombol "Simpan Berita" pada form addForm
        document.addEventListener("DOMContentLoaded", function() {
            var simpanButtonAdd = document.querySelector('form[name="addForm"] button[name="simpan"]');
            simpanButtonAdd.addEventListener("click", function() {
                // Memanggil fungsi untuk menyimpan data ke localStorage
                simpanKeLocalStorage();

                // Menampilkan data di console.log
                console.log("Data Berita Disimpan di Console:", {
                    link: "<?php echo $l; ?>",
                    title: "<?php echo $t; ?>",
                    paragraphs: <?php echo json_encode($paragraphs); ?>
                });
            });
        });
    </script>



    <script>
        var resizefunc = [];
    </script>


    <script>
        function hapusTextarea(index) {
            var element = document.getElementById('paragraph-' + index);
            element.parentNode.removeChild(element);
            console.log('tes')
        }
    </script>

    <!-- jQuery  -->
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="assets/js/detect.js"></script>
    <script src="assets/js/fastclick.js"></script>
    <script src="assets/js/jquery.blockUI.js"></script>
    <script src="assets/js/waves.js"></script>
    <script src="assets/js/jquery.slimscroll.js"></script>
    <script src="assets/js/jquery.scrollTo.min.js"></script>
    <script src="../plugins/switchery/switchery.min.js"></script>

    <!--Summernote js-->
    <script src="../plugins/summernote/summernote.min.js"></script>
    <!-- Select 2 -->
    <script src="../plugins/select2/js/select2.min.js"></script>
    <!-- Jquery filer js -->
    <script src="../plugins/jquery.filer/js/jquery.filer.min.js"></script>

    <!-- page specific js -->
    <script src="assets/pages/jquery.blog-add.init.js"></script>

    <!-- App js -->
    <script src="assets/js/jquery.core.js"></script>
    <script src="assets/js/jquery.app.js"></script>

    <script>
        jQuery(document).ready(function() {

            $('.summernote').summernote({
                height: 240, // set editor height
                minHeight: null, // set minimum height of editor
                maxHeight: null, // set maximum height of editor
                focus: false // set focus to editable area after initializing summernote
            });
            // Select2
            $(".select2").select2();

            $(".select2-limiting").select2({
                maximumSelectionLength: 2
            });
        });
    </script>
    <script src="../plugins/switchery/switchery.min.js"></script>

    <!--Summernote js-->
    <script src="../plugins/summernote/summernote.min.js"></script>

    <script>
        document.getElementById('images').addEventListener('change', function(e) {
            var container = document.getElementById('image-preview-container');
            container.innerHTML = ''; // Bersihkan container

            for (var i = 0; i < e.target.files.length; i++) {
                var reader = new FileReader();
                reader.onload = function(event) {
                    var img = document.createElement('img');
                    img.src = event.target.result;
                    img.classList.add('img-thumbnail', 'mr-2', 'mb-2');
                    container.appendChild(img);
                };
                reader.readAsDataURL(e.target.files[i]);
            }
        });
    </script>
</body>

</html>

<?php

?>