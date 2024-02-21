<?php
session_start();
include('includes/config.php');
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Diskominfo News</title>
    <link rel="icon" type="image/x-icon" href="asset/Logo.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
    <link rel="stylesheet" href="index.css">

</head>

<body>
    <!-- Navigation -->
    <?php include('includes/header.php'); ?>

    <!-- Full Page Image Header with Vertically Centered Content -->
    <header class="hero-section">
        <div class="container d-flex justify-content-between align-items-center text-white">
            <div class="text-section col-6 mt-5">
                <h1 class="fw-bolder">Content Media Analysis </h1>
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Molestiae voluptate voluptatum nulla magnam praesentium expedita, possimus atque? Nostrum, explicabo facere?</p>
            </div>
            <img class="img-fluid w-25 col-4 mt-5" src="asset/Logo.png" alt="">
        </div>
    </header>

    <!-- Page Content -->
    <div class="container">
        <div class="row" style="margin-top: 4%">


            <!-- Blog Entries Column -->
            <div class="col-md-8">
                <!-- Blog Post -->
                <h4 class="header-online p-2 mb-4 w-100 bg-body-secondary">Online</h4>
                <!-- Blog Post -->
                <div class="row">
                    <?php
                    function getColorBasedOnRatingOnline($rating)
                    {
                        if ($rating == 3) {
                            return 'blue';  // Netral, berwarna biru
                        } elseif ($rating > 3) {
                            return 'green';  // Lebih dari 3, semakin hijau
                        } else {
                            return 'red';  // Kurang dari 3, semakin merah
                        }
                    }

                    $sql = "
                    SELECT 
                        online_posts.id AS online_posts,
                        online_posts.title AS title,
                        online_posts.link AS link,
                        online_post_images.id AS id_images,
                        online_post_images.*, 
                        online_post_analyze.id AS id_analyze,
                        online_post_analyze.*,
                        categories.name AS category_name,
                        categories.*  -- tambahkan kolom-kolom lain yang ingin ditampilkan dari tabel categories
                    FROM online_posts
                    JOIN (
                        SELECT post_id, MIN(serial_number) AS min_serial_number
                        FROM online_post_images
                        GROUP BY post_id
                    ) AS min_images ON online_posts.id = min_images.post_id
                    JOIN online_post_images ON online_posts.id = online_post_images.post_id AND min_images.min_serial_number = online_post_images.serial_number
                    JOIN online_post_analyze ON online_posts.analyze_id = online_post_analyze.id
                    LEFT JOIN categories ON online_posts.category_id = categories.id  -- menggunakan LEFT JOIN agar tidak kehilangan data jika tidak ada kategori yang cocok
                    ORDER BY online_posts.id DESC;
                    ";

                    // $result = $mysqli->query($sql);
                    $result = mysqli_query($con, $sql);

                    // Check if there are results
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {

                    ?>
                            <div class="card mb-3">
                                <div class="card mb-2 border-0">
                                    <div class="row g-0 align-items-center">
                                        <div class="col-md-5 justify-content-center align-items-center">
                                            <span class="rating-label" style="padding: 5px 10px; background-color: <?php echo getColorBasedOnRatingOnline($row['total']); ?>">
                                                <?php echo $row['total']; ?>
                                            </span>

                                            <img class="card-img-top" src="admin/<?php echo $row['url'] ?>" height="200px">
                                        </div>
                                        <div class="col-md-7">
                                            <div class="card-body">
                                                <p class="m-0">
                                                    <a class="badge bg-success text-decoration-none link-light" href="">
                                                        <?php echo $row['category_name']; ?>
                                                    </a>
                                                    <!--category-->
                                                    <!-- <a class="badge bg-success text-decoration-none link-light" href="category.php?catid=" style="color:#fff">
                                                    </a> -->
                                                </p>
                                                <p class="mb-2"><small> Posted on <?php echo $row['posting_date'] ?></small></p>
                                                <a target="_blank" href="<?php echo $row['link'] ?>" class="card-title text-decoration-none text-dark">
                                                    <h4 class="card-title">
                                                        <?php echo $row['title'] ?>
                                                    </h4>
                                                </a>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    <?php }
                    } ?>
                </div>

                <!-- Blog Post -->
                <h4 class="header-offline p-2 m mb-4 w-100 bg-body-secondary">Offline</h4>
                <div class="row">
                    <?php
                    function getColorBasedOnRating($rating)
                    {
                        if ($rating == 3) {
                            return 'blue';  // Netral, berwarna biru
                        } elseif ($rating > 3) {
                            return 'green';  // Lebih dari 3, semakin hijau
                        } else {
                            return 'red';  // Kurang dari 3, semakin merah
                        }
                    }

                    $sql = "
                    SELECT 
                        offline_posts.id AS id_offline_posts,
                        offline_posts.slug AS slug,
                        offline_posts.title AS title,
                        offline_post_images.id AS id_images,
                        offline_post_images.*, 
                        offline_post_analyze.id AS id_analyze,
                        offline_post_analyze.*,
                        categories.name AS category_name,
                        categories.*  -- tambahkan kolom-kolom lain yang ingin ditampilkan dari tabel categories
                    FROM offline_posts
                    JOIN (
                        SELECT post_id, MIN(serial_number) AS min_serial_number
                        FROM offline_post_images
                        GROUP BY post_id
                    ) AS min_images ON offline_posts.id = min_images.post_id
                    JOIN offline_post_images ON offline_posts.id = offline_post_images.post_id AND min_images.min_serial_number = offline_post_images.serial_number
                    JOIN offline_post_analyze ON offline_posts.analyze_id = offline_post_analyze.id
                    LEFT JOIN categories ON offline_posts.category_id = categories.id  -- menggunakan LEFT JOIN agar tidak kehilangan data jika tidak ada kategori yang cocok
                    ORDER BY offline_posts.id DESC;
                        "; // Adjust the JOIN condition based on your table structure

                    // $result = $mysqli->query($sql);
                    $result = mysqli_query($con, $sql);

                    // Check if there are results
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {

                    ?>
                            <div class="card mb-3">
                                <div class="card mb-2 border-0">
                                    <div class="row g-0 align-items-center">
                                        <div class="col-md-5 justify-content-center align-items-center">
                                            <span class="rating-label" style="padding: 5px 10px; background-color: <?php echo getColorBasedOnRating($row['total']); ?>">
                                                <?php echo $row['total']; ?>
                                            </span>

                                            <img class="card-img-top" src="/cma-karanganyar/admin/<?php echo $row['url'] ?>" height="200px">
                                        </div>
                                        <div class="col-md-7">
                                            <div class="card-body">
                                                <p class="m-0">
                                                    <a class="badge bg-success text-decoration-none link-light" href="">
                                                        <?php echo $row['category_name']; ?>
                                                    </a>
                                                    <!--category-->
                                                    <!-- <a class="badge bg-success text-decoration-none link-light" href="category.php?catid=" style="color:#fff">
                                                    </a> -->
                                                </p>
                                                <p class="mb-2"><small> Posted on <?php echo $row['posting_date'] ?></small></p>
                                                <a href="news-details.php?id_offline_posts=<?php echo $row['id_offline_posts'] ?>" class="card-title text-decoration-none text-dark">
                                                    <h4 class="card-title">
                                                        <?php echo $row['title'] ?>
                                                    </h4>
                                                </a>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    <?php }
                    } ?>
                </div>
            </div>
            <!-- Sidebar Widgets Column -->
            <?php include('includes/sidebar.php'); ?>
        </div>
        <!-- /.row -->
    </div>
    <!-- /.container -->

    <!-- Footer -->
    <?php include('includes/footer.php'); ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>