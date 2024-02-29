<?php
session_start();
include('includes/config.php');

$postid = intval($_GET['id_offline_posts']);
$sql = "SELECT view_counter FROM offline_posts  WHERE id = '$postid'";
$result = $con->query($sql);
if ($result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    $visits = $row["view_counter"];
    $sql = "UPDATE offline_posts SET view_counter = $visits+1  WHERE id = '$postid'";
    $con->query($sql);
  }
} else {
  echo "no results";
}
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

  <!-- Page Content -->
  <div class="container">
    <div class="row" style="margin-top: 4%">
      <!-- Blog Entries Column -->
      <div class="col-md-8">
        <!-- Blog Post -->
        <?php
        $pid = intval($_GET['id_offline_posts']);
        $currenturl = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];;

        // Your SQL query to fetch data from the tables
        // SELECT offline_posts.*, offline_post_images.*, offline_post_analyze.*, categories.id AS cid, categories.name AS category_name FROM offline_posts JOIN offline_post_images ON offline_posts.id = offline_post_images.post_id JOIN offline_post_analyze ON offline_posts.analyze_id = offline_post_analyze.id LEFT JOIN categories ON offline_posts.category_id = categories.id where offline_posts.id='$pid';
        $query = "
            SELECT 
              offline_posts.*, 
              offline_post_images.*, 
              offline_post_analyze.*, 
              tblcategory.CategoryName AS category_name 
            FROM 
              offline_posts 
              JOIN offline_post_images ON offline_posts.id = offline_post_images.post_id 
              JOIN offline_post_analyze ON offline_posts.analyze_id = offline_post_analyze.id 
              LEFT JOIN tblcategory ON offline_posts.category_id = tblcategory.id 
            WHERE 
              offline_posts.id = $pid
            ORDER BY 
              offline_posts.id DESC;
            "; // Adjust the JOIN condition based on your table structure

        // $result = $mysqli->query($sql);
        $result = mysqli_query($con, $query);
        if ($result->num_rows > 0) {
          while ($row = $result->fetch_assoc()) {
        ?>

            <div class="card mb-4">
              <div class="card-body">
                <h2 class="card-title"><?php echo htmlentities($row['title']); ?></h2>
                <!--category-->
                <a class="badge bg-secondary text-decoration-none link-light" href="category.php?catid=<?php echo $row['cid']; ?>" style="color:#fff"><?php echo htmlentities($row['category_name']); ?></a>
                <p>
                  <?php echo $query ?>
                  <b>Posted by </b> <?php echo htmlentities($row['posted_by']); ?> on </b><?php echo htmlentities($row['posting_date']); ?> |
                  <?php if ($row['last_updated_by'] != '') : ?>
                    <b>Last Updated by </b> <?php echo htmlentities($row['last_updated_by']); ?> on </b><?php echo htmlentities($row['updated_date']); ?>
                </p>
              <?php endif; ?>
              <p><strong>Share:</strong> <a href="http://www.facebook.com/share.php?u=<?php echo $currenturl; ?>" target="_blank">Facebook</a> |
                <a href="https://twitter.com/share?url=<?php echo $currenturl; ?>" target="_blank">Twitter</a> |
                <a href="https://web.whatsapp.com/send?text=<?php echo $currenturl; ?>" target="_blank">Whatsapp</a> |
                <a href="http://www.linkedin.com/shareArticle?mini=true&amp;url=<?php echo $currenturl; ?>" target="_blank">Linkedin</a> <b>Visits:</b> <?php print $visits; ?>
              </p>
              <hr />
              <div class="row">
                <div class="col">
                  <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="true">
                    <div class="carousel-indicators">
                      <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                      <!-- Tambahkan button untuk setiap gambar jika perlu -->
                    </div>
                    <div class="carousel-inner">
                      <?php
                      // Loop untuk menampilkan gambar dalam carousel
                      $result->data_seek(0); // Kembalikan indeks hasil query ke awal
                      while ($row = $result->fetch_assoc()) {
                      ?>
                        <div class="carousel-item <?php echo ($result->num_rows == 1) ? 'active' : ''; ?>">
                          <img src="admin/<?php echo $row['url'] ?>" alt="<?php echo htmlentities($row['title']); ?>" class="d-block w-100">
                        </div>
                      <?php } ?>
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
                      <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                      <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
                      <span class="carousel-control-next-icon" aria-hidden="true"></span>
                      <span class="visually-hidden">Next</span>
                    </button>
                  </div>
                </div>
              </div>

              <p class="card-text">
                <?php
                $pt = $row['description'];
                echo (substr($pt, 0)); ?></p>
              </div>
              <div class="card-footer text-muted">
              </div>
            </div>
        <?php }
        } ?>
      </div>

      <!-- Sidebar Widgets Column -->
      <?php include('includes/sidebar.php'); ?>
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