<?php
session_start();
error_reporting(0);
include('includes/config.php');
include '../analisis/lib/PHPInsight/dictionaries/source.positif.php';
include '../analisis/lib/PHPInsight/dictionaries/source.negatif.php';

if (strlen($_SESSION['login']) == 0) {
    header('location:index.php');
} else {
    if (isset($_POST['submit'])) {

        // Membuat Slug dari Judul
        function generateSlug($title)
        {
            // Konversi ke huruf kecil
            $slug = strtolower($title);

            // Ganti spasi dengan garis bawah
            $slug = str_replace(' ', '_', $slug);

            // Hapus karakter yang tidak valid untuk URL
            $slug = preg_replace('/[^A-Za-z0-9\-_]/', '', $slug);

            return $slug;
        }

        $title = $_POST['title'];
        $description = $_POST['description'];
        
        $dataPositiveWord = array();
        $dataNegativeWord = array();
        
        // Mengecek kata-kata positif dalam judul
        foreach ($positiveWords as $positiveWord) {
            if (stripos($title, $positiveWord) !== false) {
                $dataPositiveWord[] = $positiveWord;
            }
        }
        
        // Mengecek kata-kata positif dalam deskripsi
        foreach ($positiveWords as $positiveWord) {
            if (stripos($description, $positiveWord) !== false) {
                $dataPositiveWord[] = $positiveWord;
            }
        }
        
        // Mengecek kata-kata negatif dalam judul
        foreach ($negativeWords as $negativeWord) {
            if (stripos($title, $negativeWord) !== false) {
                $dataNegativeWord[] = $negativeWord;
            }
        }
        
        // Mengecek kata-kata negatif dalam deskripsi
        foreach ($negativeWords as $negativeWord) {
            if (stripos($description, $negativeWord) !== false) {
                $dataNegativeWord[] = $negativeWord;
            }
        }
        
        // Menghitung data yang ada di masing-masing array
        $countPositiveWord = count($dataPositiveWord);
        $countNegativeWord = count($dataNegativeWord);
        
        // Menentukan total
        $total = round(($countPositiveWord/($countPositiveWord+$countNegativeWord))*5);

        // Menentukan hasil
        $result = "";
        if ($total == $countNegativeWord) {
            $result = "Negatif";
        } elseif ($total == $countPositiveWord) {
            $result = "Positif";
        }

        // Simpan ke tabel offline_post_analyze
        $insertQuery = "INSERT INTO offline_post_analyze (positive, negative, total, result) VALUES ('$countPositiveWord', '$countNegativeWord', '$total', '$result')";
        $con->query($insertQuery);

        // Mendapatkan hasil analisis terbaru
        $selectQuery = "SELECT id, result FROM offline_post_analyze ORDER BY id DESC LIMIT 1";
        $result = mysqli_query($con, $selectQuery);

        if ($result) {
            $row = mysqli_fetch_assoc($result);

            // Extract data from the selected record
            $analyze_id = $row['id'];

            // Nilai yang dimasukkan
            $category_id = $_POST['category_id'];
            $title = $_POST['title'];
            $description = $_POST['description'];
            $postedby=$_SESSION['login'];
            $slug = generateSlug($title);
            $source_id = $_POST['source_id'];
            $date = $_POST['date_news'];

            // Query untuk melakukan insert
            $status=1;
            $sql = "INSERT INTO offline_posts (category_id, analyze_id, title, slug, source_id, date, description, active, posted_by) VALUES ('$category_id', '$analyze_id', '$title', '$slug', '$source_id', '$date', '$description', '$status', '$postedby')";

            // Menjalankan query dan memeriksa apakah berhasil
            if ($con->query($sql) === TRUE) {

                // Mendapatkan ID terbaru dari postingan
                $postQuery = "SELECT id FROM offline_posts ORDER BY id DESC LIMIT 1";
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
                            $imgnewfile=md5($imgfile).$extension;
                            $name = $_FILES['images']['name'][$key];
                            $url = "postimages/" . $name; // Gantilah dengan path yang sesuai

                            move_uploaded_file($tmp_name, $url);

                            // Simpan data ke database
                            $insertQuery = "INSERT INTO offline_post_images (post_id, name, serial_number, url) VALUES ('$post_id', '$name', '$serialNumber', '$url')";
                            $con->query($insertQuery);

                            // Increment serial number for the next file
                            $serialNumber++;
                            $msg = "Data berhasil ditambahkan ke database";
                        }
                    } else {
                        $gagal = "Tidak ada gambar yang diunggah.";
                    }
                }
            } else {
                $error = "Error: " . $sql . "<br>" . $con->error;
            }
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
        <title>Tambah Berita Offline</title>
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
                                    <h4 class="page-title">Tambah Berita Offline </h4>
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
                                    <div class="">
                                        <form name="addpost" method="post" enctype="multipart/form-data">
                                            <div class="form-group m-b-20">
                                                <label for="exampleInputEmail1">Judul Berita</label>
                                                <input type="text" class="form-control" id="title" name="title" placeholder="Masukkan Judul Berita" required>
                                            </div>
                                            <div class="form-group m-b-20">
                                                <label for="exampleInputEmail1">Sumber Berita</label>
                                                <!-- <input type="text" class="form-control" id="source" name="source" placeholder="Masukkan Sumber Berita" required> -->
                                                <select class="form-control" name="source_id" id="source_id" onChange="getSubCat(this.value);" required>
                                                    <option value="">Pilih Sumber Berita</option>
                                                    <?php
                                                    // Feching active sources
                                                    $ret = mysqli_query($con, "select id,name from  sources where active=1");
                                                    while ($result = mysqli_fetch_array($ret)) {
                                                    ?>
                                                        <option value="<?php echo htmlentities($result['id']); ?>"><?php echo htmlentities($result['name']); ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                            <div class="form-group m-b-20">
                                                <label for="exampleInputEmail1">Kategori</label>
                                                <select class="form-control" name="category_id" id="category_id" onChange="getSubCat(this.value);" required>
                                                    <option value="">Pilih Kategori</option>
                                                    <?php
                                                    // Feching active categories
                                                    $ret = mysqli_query($con, "select id,name from  categories where active=1");
                                                    while ($result = mysqli_fetch_array($ret)) {
                                                    ?>
                                                        <option value="<?php echo htmlentities($result['id']); ?>"><?php echo htmlentities($result['name']); ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                            <div class="form-group m-b-20">
                                                <label for="exampleInputEmail1">Tanggal Berita</label>
                                                <div class="input-group mb-3">
                                                    <input type="date" name="date_news" class="form-control" required autofocus>
                                                </div>
                                            </div>
                                            
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <div class="card-box">
                                                        <h4 class="m-b-30 m-t-0 header-title"><b>Deskripsi Berita</b></h4>
                                                        <textarea class="summernote" name="description" required></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <div class="card-box">
                                                        <h4 class="m-b-30 m-t-0 header-title"><b>Gambar Terkait</b></h4>
                                                        <input type="file" class="form-control" id="images" name="images[]" multiple required>
                                                        <div id="image-preview-container" class="mt-3"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <button type="submit" name="submit" class="btn btn-success waves-effect waves-light">Save and Post</button>
                                            <button type="button" class="btn btn-danger waves-effect waves-light">Discard</button>
                                        </form>
                                    </div>
                                </div> <!-- end p-20 -->
                            </div> <!-- end col -->
                        </div>
                        <!-- end row -->

                    </div> <!-- container -->

                </div> <!-- content -->

                <?php include('includes/footer.php'); ?>
            </div>


            <!-- ============================================================== -->
            <!-- End Right content here -->
            <!-- ============================================================== -->


        </div>
        <!-- END wrapper -->



        <script>
            var resizefunc = [];
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
<?php } ?>