<?php
session_start();
error_reporting(0);
include('includes/config.php');
if (isset($_POST['update'])) {
    $pid = $_GET['pid'];

    // Step 1: Select the analyze_id from offline_posts
    $selectQuery = "SELECT analyze_id FROM offline_posts WHERE id = '$pid'";
    $resultSelect = mysqli_query($con, $selectQuery);

    if ($rowSelect = mysqli_fetch_assoc($resultSelect)) {
        $analyze_id_offline_posts = $rowSelect['analyze_id'];

        // Step 2: Remove existing record with the given ID
        $deleteQuery = "DELETE FROM offline_posts WHERE id = '$pid'";
        mysqli_query($con, $deleteQuery);

        // Step 3: Insert updated data
        $title = mysqli_real_escape_string($con, $_POST['title']);
        $source_id = mysqli_real_escape_string($con, $_POST['source_id']);
        $category_id = mysqli_real_escape_string($con, $_POST['category_id']);
        $analyze_id = mysqli_real_escape_string($con, $analyze_id_offline_posts);
        $postingDate = mysqli_real_escape_string($con, $_POST['posting_date']);
        $description = mysqli_real_escape_string($con, $_POST['description']);

        $insertQuery = "INSERT INTO offline_posts (id, title, source_id, category_id, analyze_id, posting_date, description) 
                        VALUES ('$pid', '$title', '$source_id', '$category_id', '$analyze_id', '$postingDate', '$description')";
        $result = mysqli_query($con, $insertQuery);

        if ($result) {
            $msg = "Berita berhasil diperbarui";
        } else {
            $error = "Gagal memperbarui berita";
        }
    } else {
        $error = "Gagal mendapatkan analyze_id dari berita yang akan diperbarui";
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
    <title>Sunting Berita Offline</title>
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
    <style>
        .image-input {
            display: none;
        }

        .image-preview {
            margin-top: 10px;
        }
    </style>


</head>


<body class="fixed-left">

    <!-- Begin page -->
    <div id="wrapper">

        <!-- Top Bar Start -->
        <?php include('includes/topheader.php'); ?>
        <!-- ========== Left Sidebar Start ========== -->
        <?php include('includes/leftsidebar.php'); ?>
        <!-- Left Sidebar End -->


        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="content-page">
            <!-- Start content -->
            <div class="content">
                <div class="container">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="page-title-box">
                                <h4 class="page-title">Sunting Berita Offline</h4>
                            </div>
                        </div>
                    </div>
                    <!-- end row -->

                    <div class="row">
                        <div class="col-sm-6">
                            <!---Success Message--->
                            <?php if ($msg) { ?>
                                <div class="alert alert-success" role="alert">
                                    <strong>Well done!</strong> <?php echo htmlentities($msg); ?>
                                </div>
                            <?php } ?>

                            <!---Error Message--->
                            <?php if ($error) { ?>
                                <div class="alert alert-danger" role="alert">
                                    <strong>Oh snap!</strong> <?php echo htmlentities($error); ?>
                                </div>
                            <?php } ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-10 col-md-offset-1">
                            <div class="p-6">
                                <div class="">
                                    <?php
                                    $pid = $_GET['pid'];
                                    // Feching active categories
                                    $sql = mysqli_query($con, "SELECT * FROM `offline_posts` WHERE id='$pid'");
                                    while ($row = mysqli_fetch_array($sql)) {
                                    ?>
                                        <form name="addpost" method="post">
                                            <div class="form-group m-b-20">
                                                <label for="exampleInputEmail1">Judul Berita</label>
                                                <input type="text" class="form-control" id="title" value="<?= $row['title'] ?>" name="title" placeholder="Enter title" required>
                                            </div>
                                            <div class="form-group m-b-20">
                                                <label for="exampleInputEmail1">Sumber Berita</label>
                                                <select class="form-control" name="source_id" id="source_id" onChange="getSubCat(this.value);" required>
                                                    <?php
                                                    // Fetching all source_ids
                                                    $ret = mysqli_query($con, "SELECT id, name FROM sources WHERE active=1");
                                                    while ($result = mysqli_fetch_array($ret)) {
                                                        $selected = ($row['category_id_id'] == $result['id']) ? 'selected' : '';
                                                    ?>
                                                        <option value="<?php echo htmlentities($result['id']); ?>" <?php echo $selected; ?>><?php echo htmlentities($result['name']); ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>

                                            <div class="form-group m-b-20">
                                                <label for="exampleInputEmail1">Kategori</label>
                                                <select class="form-control" name="category_id" id="category_id" onChange="getSubCat(this.value);" required>
                                                    <?php
                                                    // Fetching all categories
                                                    $ret = mysqli_query($con, "SELECT id, name FROM categories WHERE active=1");
                                                    while ($result = mysqli_fetch_array($ret)) {
                                                        $selected = ($row['category_id_id'] == $result['id']) ? 'selected' : '';
                                                    ?>
                                                        <option value="<?php echo htmlentities($result['id']); ?>" <?php echo $selected; ?>><?php echo htmlentities($result['name']); ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                            <div class="form-group m-b-20">
                                                <label for="exampleInputEmail1">Tanggal Berita</label>
                                                <?php
                                                // Assuming $row['posting_date'] is a timestamp
                                                $postingDate = date('Y-m-d', strtotime($row['posting_date']));
                                                ?>
                                                <input type="date" class="form-control" id="title" value="<?php echo $postingDate; ?>" name="posting_date" placeholder="Enter title" required>
                                            </div>

                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <div class="card-box">
                                                        <h4 class="m-b-30 m-t-0 header-title"><b>Deskripsi Berita</b></h4>
                                                        <textarea class="summernote" name="description" required><?php echo $row['description']; ?></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <div class="card-box">
                                                        <h4 class="m-b-30 m-t-0 header-title"><b>Gambar Terkait</b></h4>
                                                        <?php
                                                        // Fetching active categories
                                                        $ret = mysqli_query($con, "SELECT * FROM offline_post_images WHERE post_id = $pid");
                                                        while ($result = mysqli_fetch_array($ret)) {
                                                        ?>
                                                            <div class="image-upload-container">
                                                                <input type="file" class="form-control image-input" name="images[]" multiple>
                                                                <div class="image-preview mt-3">
                                                                    <img src="<?php echo $result['url']; ?>" alt="">
                                                                </div>
                                                            </div>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <button type="submit" name="update" class="btn btn-success waves-effect waves-light">Update </button>
                                        </form>
                                    <?php } ?>
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

</body>

</html>