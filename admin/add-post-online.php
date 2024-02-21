<?php
session_start();
error_reporting(0);

include('includes/config.php');
include '../analisis/lib/PHPInsight/dictionaries/source.positif.php';
include '../analisis/lib/PHPInsight/dictionaries/source.negatif.php';

$category_id;

if (strlen($_SESSION['login']) == 0) {
    header('location:index.php');
} else {
    // Variabel untuk menyimpan data
    $link = '';
    $title = '';
    $paragraphs = '';
    $images = [];

    if (isset($_POST['submitForm1'])) {
        $link = $_POST['link'];
        $category_id = $_POST['category_id'];

        // Simpan $category_id di $_SESSION
        $_SESSION['category_id'] = $category_id;

        $ch = curl_init($link);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        curl_close($ch);

        $dom = new DOMDocument();
        @$dom->loadHTML($response);

        $titleElement = $dom->getElementsByTagName('title')->item(0);
        $pElements = $dom->getElementsByTagName('p');
        $title = $titleElement ? $titleElement->nodeValue : 'Tidak dapat menemukan judul berita';

        foreach ($pElements as $pElement) {
            $paragraph = $pElement->nodeValue;
            $paragraphs .= $paragraph . "\n";

            if (strlen($paragraph) > 10) {
                $p[] = $paragraph;
            }
        }

        $imgElements = $dom->getElementsByTagName('img');
        foreach ($imgElements as $imgElement) {
            $imageSrc = $imgElement->getAttribute('src');
            if ($imageSrc) {
                $images[] = $imageSrc;
            }
        }

        $showForm2 = true;
    } elseif (isset($_POST['submitForm2'])) {
        $link = $_POST['link'];
        $title = $_POST['title'];
        $description = implode("\n", $_POST['paragraphs']);

        $dataKataPositif = array();
        $dataKataNegatif = array();

        // Mengecek kata-kata positif dalam judul
        foreach ($positiveWords as $positiveWord) {
            if (stripos($title, $positiveWord) !== false) {
                $dataKataPositif[] = $positiveWord;
            }
        }

        // Mengecek kata-kata positif dalam deskripsi
        foreach ($positiveWords as $positiveWord) {
            if (stripos($description, $positiveWord) !== false) {
                $dataKataPositif[] = $positiveWord;
            }
        }

        // Mengecek kata-kata negatif dalam judul
        foreach ($negativeWords as $negativeWord) {
            if (stripos($title, $negativeWord) !== false) {
                $dataKataNegatif[] = $negativeWord;
            }
        }

        // Mengecek kata-kata negatif dalam deskripsi
        foreach ($negativeWords as $negativeWord) {
            if (stripos($description, $negativeWord) !== false) {
                $dataKataNegatif[] = $negativeWord;
            }
        }

        // Menghitung data yang ada di masing-masing array
        $hitungKataPositif = count($dataKataPositif);
        $hitungKataNegatif = count($dataKataNegatif);

        // Menentukan total
        $total = round(($hitungKataPositif / ($hitungKataPositif + $hitungKataNegatif)) * 5);

        // Menentukan hasil
        $result = "";
        if ($total == $hitungKataNegatif) {
            $result = "Negatif";
        } elseif ($total == $hitungKataPositif) {
            $result = "Positif";
        }

        // Simpan ke tabel online_post_analyze
        $insertQuery = "INSERT INTO online_post_analyze (positive, negative, total, result) VALUES ('$hitungKataPositif', '$hitungKataNegatif', '$total', '$result')";
        $con->query($insertQuery);

        // Mendapatkan hasil analisis terbaru
        $selectQuery = "SELECT id, result FROM online_post_analyze ORDER BY id DESC LIMIT 1";
        $result = mysqli_query($con, $selectQuery);

        if ($result) {
            $row = mysqli_fetch_assoc($result);
            $analyze_id = $row['id'];
            $category_id = $_SESSION['category_id'];
            $title = $_POST['title'];
            $descriptions = implode("\n", $_POST['paragraphs']);
            $date = $_POST['date_news'];
            $status=1;
            $sql = "INSERT INTO online_posts (category_id, analyze_id, link, title, date, description, active) VALUES ('$category_id', '$analyze_id','$link', '$title', '$date', '$descriptions', '$status')";

            // Menjalankan query dan memeriksa apakah berhasil
            if ($con->query($sql) === TRUE) {

                // Mendapatkan ID terbaru dari postingan
                $postQuery = "SELECT id FROM online_posts ORDER BY id DESC LIMIT 1";
                $result = mysqli_query($con, $postQuery);

                if ($result) {
                    $row = mysqli_fetch_assoc($result);

                    // Extract data from the selected record
                    $post_id = $row['id'];

                    if (isset($_FILES['images'])) {
                        // Initialize serial number
                        $serialNumber = 1;

                        // Loop through each file
                        foreach ($_FILES['images']['tmp_name'] as $key => $tmp_name) {
                            $imgnewfile = md5($imgfile) . $extension;
                            $name = $_FILES['images']['name'][$key];
                            $url = "postimages/" . $name;

                            move_uploaded_file($tmp_name, $url);

                            // Simpan data ke database
                            $insertQuery = "INSERT INTO online_post_images (post_id, name, serial_number, url) VALUES ('$post_id', '$name', '$serialNumber', '$url')";
                            $con->query($insertQuery);

                            // Increment serial number for the next file
                            $serialNumber++;
                            $msg = "Data berhasil ditambahkan ke database";
                        }
                    } else {
                        echo "Tidak ada gambar yang diunggah.";
                    }
                }
            } else {
                $error = "Error: " . $sql . "<br>" . $con->error;
            }

        } else {
            $error = "Error: " . $sql . "<br>" . $con->error;
        }
    } elseif (isset($_POST['restoreParagraph'])) {
        $index = $_POST['index'];
        $restoredParagraph = $_POST['restoredParagraph'];
        $p[$index] = $restoredParagraph;

        unset($_POST['deletedParagraphs'][$index]);
        $showForm2 = true;
    } else {
        $showForm2 = false;
    }
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

        <div class="content-page">
            <div class="content">
                <div class="container">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="page-title-box">
                                <h4 class="page-title">Tambah Berita Online </h4>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                            <div class="col-sm-6">
                                <!---Success Message--->
                                <?php if ($msg) { ?>
                                    <div class="alert alert-success" role="alert">
                                        <strong><?php echo htmlentities($msg); ?></strong> 
                                    </div>
                                <?php } ?>

                                <!---Error Message--->
                                <?php if ($error) { ?>
                                    <div class="alert alert-danger" role="alert">
                                        <strong><?php echo htmlentities($error); ?></strong> 
                                    </div>
                                <?php } ?>
                            </div>
                    </div>


                    <div class="row">
                        <div class="col-md-10 col-md-offset-1">
                            <div class="p-6">
                                <?php if (!$showForm2) { ?>
                                    <!-- Form Pertama -->
                                    <form method="post" enctype="multipart/form-data">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="form-group m-b-20">
                                                    <label for="link">Link Berita</label>
                                                    <input type="text" class="form-control" id="link" name="link" placeholder="Masukkan Link Berita" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group m-b-20">
                                            <label for="exampleInputEmail1">Kategori</label>
                                            <select class="form-control" name="category_id" id="category_id" onChange="getSubCat(this.value);" required>
                                                <option value="">Pilih Kategori</option>
                                                <?php
                                                $ret = mysqli_query($con, "select id,name from  categories where active=1");
                                                while ($result = mysqli_fetch_array($ret)) {
                                                ?>
                                                    <option value="<?php echo htmlentities($result['id']); ?>"><?php echo htmlentities($result['name']); ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        
                                        <button type="submit" name="submitForm1" class="btn btn-success waves-effect waves-light">Tampilkan Data Berita</button>
                                    </form>
                                <?php } else { ?>
                                    <!-- Formulir Kedua -->
                                    <form method="post" enctype="multipart/form-data">
                                        <h1>category <?php echo $category_id = $_SESSION['category_id']; ?></h1>
                                        <div class="form-group m-b-20">
                                            <label for="title">Judul Berita</label>
                                            <input type="text" class="form-control" name="title" id="title" value="<?php echo $title; ?>" readonly>
                                        </div>
                                        <div class="form-group m-b-20">
                                            <label for="link">Link Berita</label>
                                            <input type="text" class="form-control" name="link" id="link" value="<?php echo $link; ?>" readonly>
                                        </div>
                                        <div class="form-group m-b-20">
                                            <label for="exampleInputEmail1">Tanggal Berita</label>
                                            <div class="input-group mb-3">
                                                <input type="date" name="date_news" id="date" class="form-control" required autofocus>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="form-group m-b-20">
                                                    <label>Gambar Terkait</label>
                                                    <input type="file" class="form-control" id="images" name="images[]" multiple required>
                                                    <div id="image-preview-container" class="mt-3"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <?php foreach ($p as $index => $paragraph) { ?>
                                            <div class="form-group m-b-20">
                                                <div style="display: flex; flex-direction: column; " id="paragraphDiv<?php echo $index; ?>">
                                                    <div style="margin-bottom: 8px; display: flex; flex-direction: row; align-items: center; justify-content: space-between;">
                                                        <label>Paragraf</label>
                                                        <button type="button" style="background-color: red; color: white; outline: 0; border: 0; border-radius: 15px; padding: 5px 10px;" onclick="hapusParagraph(<?php echo $index; ?>)">Hapus</button>
                                                    </div>
                                                    <textarea class="form-control mt-4 " rows="5" name="paragraphs[]" id="paragraph<?php echo $index; ?>" rows="4" readonly><?php echo $paragraph; ?></textarea>
                                                </div>
                                            </div>
                                        <?php } ?>
                                        <button type="submit" name="submitForm2" class="btn btn-success waves-effect waves-light">Simpan ke Database</button>
                                    </form>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
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

    <script>
        function hapusParagraph(index) {
            var paragraphDiv = document.getElementById('paragraphDiv' + index);

            // Simpan paragraph yang dihapus ke dalam input hidden
            var inputHidden = document.createElement('input');
            inputHidden.type = 'hidden';
            inputHidden.name = 'deletedParagraphs[]';
            inputHidden.value = paragraphDiv.querySelector('textarea').value;

            // Hapus div yang berisi textarea dan tombol hapus
            paragraphDiv.parentNode.removeChild(paragraphDiv);

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

<?php

?>