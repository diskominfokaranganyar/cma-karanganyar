<?php
session_start();
include('includes/config.php');

// echo "HAHAHA";

function http_request($url)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $output = curl_exec($ch);
    curl_close($ch);

    return $output;
}

$list = http_request("http://localhost:4000/list");
$list = json_decode($list, TRUE);

$hot_news = http_request("http://localhost:4000/HotNews");
$hot_news = json_decode($hot_news, TRUE);

// echo "<pre>";
// print_r($rows);
// echo "</pre>";

// return;

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Diskominfo News</title>
    <link rel="icon" type="image/x-icon" href="asset/Logo.png">

    <!-- Bootstrap core CSS -->
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/modern-business.css" rel="stylesheet">

</head>

<body>

    <!-- Navigation -->
    <?php include('includes/header.php'); ?>
    asd
    asd
    <!-- Page Content -->
    <div class="container">
        <div class="row" style="margin-top: 4%">
            <!-- Blog Entries Column -->
            <div class="col-md-8">
                <!-- Blog Post -->
                <h4 class="header-online p-2 mb-4 w-100 bg-body-secondary">Stream</h4>
                <!-- Blog Post -->
                <div class="row">
                    <?php
                    function getColorBasedOnRatingOnline($rating)
                    {
                        if ($rating === "Positif") {
                            return "success";
                        } else if ($rating === "Negatif") {
                            return "danger";
                        } else {
                            return "secondary";
                        }
                    }

                    // Check if there are results
                    if (count($list) > 0) :
                        foreach ($list as $row) :

                    ?>
                            <div class="card pt-2 mb-3">
                                <div class="card mb-2 border-0">
                                    <div class="row g-0 align-items-center">
                                        <div class="col-md-5 justify-content-center align-items-center">
                                            <img class="card-img-top" src="<?php echo $row['imgSrc'] ?>" height="200px">
                                        </div>
                                        <div class="col-md-7">
                                            <div class="card-body">
                                                <a target="_blank" href="<?php echo $row['link'] ?>" class="card-title text-decoration-none text-dark">
                                                    <h5 class="card-title">
                                                        <?php echo $row['title'] ?>
                                                    </h5>
                                                </a>
                                                <p class="m-0 row justify-content-between align-items-center">
                                                    <a class="badge bg-<?php echo getColorBasedOnRatingOnline($row['analisisSentimen']); ?> text-decoration-none link-light col-3 w-50 p-2" href="">
                                                        <?php echo $row['analisisSentimen']; ?>
                                                    </a>
                                                </p>
                                                <p class="mb-2"><small><?php echo $row["date"] ?> | Penulis : <?php echo $row["author"] ?></small></p>
                                                <p class="mb-2"><?php echo $row["description"] ?></p>
                                                <p class="mb-2"><small>Kata-kata sentimen : <?php echo count($row["kataKata"]) > 0 ? implode(", ", array_map(fn ($kata) => $kata["kata"] . " (" . $kata["skor"] . ")", $row["kataKata"])) : "-" ?></small></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    <?php
                        endforeach;
                    endif;
                    ?>

                </div>

            </div>

            <!-- Sidebar Widgets Column -->
            <!-- Bootstrap core CSS -->
            <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

            <div class="col-md-4">

                <?php if (count($hot_news) > 0) : ?>
                    <?php foreach ($hot_news as $row) : ?>

                        <div class="card my-4">
                            <img class="card-img-top" src="<?= $row["gambarURL"] ?>" alt="<?= $row["judul"] ?>">
                            <div class="card-body">
                                <h5 class="card-title">
                                    <a href="<?= $row["beritaLink"] ?>" target="_blank" class="text-decoration-none text-dark">
                                        <?= $row["judul"] ?>
                                    </a>
                                </h5>
                                <span class="badge badge-<?php echo getColorBasedOnRatingOnline($row['analisisSentimen']); ?> p-2"><?= $row['analisisSentimen'] ?></span>
                                <p class="card-text"><small><?= $row["tanggal"] ?></small></p>
                                <p class="card-text"><?= $row["isi"] ?></p>
                            </div>
                            <div class="card-footer text-muted">
                                <small>Kata-kata sentimen : <?php echo count($row["kataKata"]) > 0 ? implode(", ", array_map(fn ($kata) => $kata["kata"] . " (" . $kata["skor"] . ")", $row["kataKata"])) : "-" ?></small>
                            </div>
                        </div>

                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
        <!-- /.row -->
    </div>
    <?php include('includes/footer.php'); ?>


    <!-- Bootstrap core JavaScript -->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>

</body>

</html>