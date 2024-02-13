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

    <link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
    rel="stylesheet"
    integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN"
    crossorigin="anonymous"
    />
    <link rel="stylesheet" href="index.css">

</head>
<body>
    <!-- Navigation -->
    <?php include('includes/header.php');?>

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
                <h4 class="header-offline p-2 mb-4 w-100 bg-body-secondary">Offline</h4>
                <!-- Blog Post -->
                <div class="row">
                    <?php 
                        if (isset($_GET['pageno'])) {
                                $pageno = $_GET['pageno'];
                            } else {
                                $pageno = 1;
                            }
                            $no_of_records_per_page = 8;
                            $offset = ($pageno-1) * $no_of_records_per_page;
                        
                        
                            $total_pages_sql = "SELECT COUNT(*) FROM tblposts_offline";
                            $result = mysqli_query($con,$total_pages_sql);
                            $total_rows = mysqli_fetch_array($result)[0];
                            $total_pages = ceil($total_rows / $no_of_records_per_page);
                        
                        
                        $query=mysqli_query($con,"select tblposts_offline.id as pid,tblposts_offline.PostTitle as posttitle,tblposts_offline.PostImage,tblcategory.CategoryName as category,tblcategory.id as cid,tblposts_offline.PostDetails as postdetails,tblposts_offline.PostingDate as postingdate,tblposts_offline.PostUrl as url from tblposts_offline left join tblcategory on tblcategory.id=tblposts_offline.CategoryId where tblposts_offline.Is_Active=1 order by tblposts_offline.id desc  LIMIT $offset, $no_of_records_per_page");
                        while ($row=mysqli_fetch_array($query)) {
                    ?>
                    <div class="col-md-6">
                        <div class="card mb-2 border-0">
                            <img class="card-img-top" src="admin/postimages/<?php echo htmlentities($row['PostImage']);?>" alt="<?php echo htmlentities($row['posttitle']);?>" height="200px">
                            <div class="card-body">
                                <p class="m-0">
                                    <!--category-->
                                    <a class="badge bg-success text-decoration-none link-light" href="category.php?catid=<?php echo htmlentities($row['cid'])?>" style="color:#fff"><?php echo htmlentities($row['category']);?></a>
                                </p>
                                <p class="mb-2"><small> Posted on <?php echo htmlentities($row['postingdate']);?></small></p>
                                    <a href="news-details.php?nid=<?php echo htmlentities($row['pid'])?>" class="card-title text-decoration-none text-dark">
                                        <h5 class="card-title"><?php echo htmlentities($row['posttitle']);?></h5>
                                    </a>
                                </p>
                                <!-- <a href="news-details.php?nid=<?php echo htmlentities($row['pid'])?>" class="">Read More &rarr;</a> -->
                            </div>
                        </div>
                    </div>
                    <?php } ?>

                    <!-- Pagination -->
                    <ul class="pagination justify-content-center mb-4">
                        <li class="page-item"><a href="?pageno=1"  class="page-link">First</a></li>
                        <li class="<?php if($pageno <= 1){ echo 'disabled'; } ?> page-item">
                            <a href="<?php if($pageno <= 1){ echo '#'; } else { echo "?pageno=".($pageno - 1); } ?>" class="page-link">Prev</a>
                        </li>
                        <li class="<?php if($pageno >= $total_pages){ echo 'disabled'; } ?> page-item">
                            <a href="<?php if($pageno >= $total_pages){ echo '#'; } else { echo "?pageno=".($pageno + 1); } ?> " class="page-link">Next</a>
                        </li>
                        <li class="page-item"><a href="?pageno=<?php echo $total_pages; ?>" class="page-link">Last</a></li>
                    </ul>
                </div>

                <h4 class="header-online p-2 m mb-4 w-100 bg-body-secondary">Online</h4>
                <!-- Blog Post -->
                <div class="row">
                    <?php 
                        if (isset($_GET['pageno'])) {
                            $pageno = $_GET['pageno'];
                        } else {
                            $pageno = 1;
                        }
                        $no_of_records_per_page = 8;
                        $offset = ($pageno-1) * $no_of_records_per_page;
                    
                    
                        $total_pages_sql = "SELECT COUNT(*) FROM tblposts_offline";
                        $result = mysqli_query($con,$total_pages_sql);
                        $total_rows = mysqli_fetch_array($result)[0];
                        $total_pages = ceil($total_rows / $no_of_records_per_page);
                    
                    
                        $query=mysqli_query($con,"select tblposts_offline.id as pid,tblposts_offline.PostTitle as posttitle,tblposts_offline.PostImage,tblcategory.CategoryName as category,tblcategory.id as cid,tblposts_offline.PostDetails as postdetails,tblposts_offline.PostingDate as postingdate,tblposts_offline.PostUrl as url from tblposts_offline left join tblcategory on tblcategory.id=tblposts_offline.CategoryId where tblposts_offline.Is_Active=1 order by tblposts_offline.id desc  LIMIT $offset, $no_of_records_per_page");
                        while ($row=mysqli_fetch_array($query)) {
                    ?>
                    <div class="card mb-3">
                        <div class="card mb-2 border-0">
                            <div class="row g-0 align-items-center">
                                <div class="col-md-5 justify-content-center align-items-center">
                                    <img class="card-img-top" src="admin/postimages/<?php echo htmlentities($row['PostImage']);?>" alt="<?php echo htmlentities($row['posttitle']);?>" height="200px">
                                </div>
                                <div class="col-md-7">
                                    <div class="card-body">
                                        <p class="m-0">
                                            <!--category-->
                                            <a class="badge bg-success text-decoration-none link-light" href="category.php?catid=<?php echo htmlentities($row['cid'])?>" style="color:#fff"><?php echo htmlentities($row['category']);?></a>
                                        </p>
                                        <p class="mb-2"><small> Posted on <?php echo htmlentities($row['postingdate']);?></small></p>
                                            <a href="news-details.php?nid=<?php echo htmlentities($row['pid'])?>" class="card-title text-decoration-none text-dark">
                                                <h5 class="card-title"><?php echo htmlentities($row['posttitle']);?></h5>
                                            </a>
                                        </p>
                                        <!-- <a href="news-details.php?nid=<?php echo htmlentities($row['pid'])?>" class="">Read More &rarr;</a> -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php } ?>

                    <!-- Pagination -->
                    <ul class="pagination justify-content-center mb-4">
                        <li class="page-item"><a href="?pageno=1"  class="page-link">First</a></li>
                        <li class="<?php if($pageno <= 1){ echo 'disabled'; } ?> page-item">
                            <a href="<?php if($pageno <= 1){ echo '#'; } else { echo "?pageno=".($pageno - 1); } ?>" class="page-link">Prev</a>
                        </li>
                        <li class="<?php if($pageno >= $total_pages){ echo 'disabled'; } ?> page-item">
                            <a href="<?php if($pageno >= $total_pages){ echo '#'; } else { echo "?pageno=".($pageno + 1); } ?> " class="page-link">Next</a>
                        </li>
                        <li class="page-item"><a href="?pageno=<?php echo $total_pages; ?>" class="page-link">Last</a></li>
                    </ul>
                </div>
            </div>
            <!-- Sidebar Widgets Column -->
            <?php include('includes/sidebar.php');?>
        </div>
        <!-- /.row -->
    </div>
    <!-- /.container -->

    <!-- Footer -->
    <?php include('includes/footer.php');?>

    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
      integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
      crossorigin="anonymous"
    ></script>
</body>
</html>

