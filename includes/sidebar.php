<!-- Bootstrap core CSS -->
<link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

<div class="col-md-4 mt-5">
  <!-- Search Widget -->
  <div class="card mb-4">
    <h5 class="card-header">Search</h5>
    <div class="card-body">
      <form name="search" action="search.php" method="post">
        <div class="input-group">
          <input type="text" name="searchtitle" class="form-control rounded me-3" placeholder="Masukkan Kata Kunci" required>
          <span class="input-group-btn">
            <button class="btn btn-info ps-3 pe-3 rounded-2" type="submit">Cari</button>
          </span>
        </div>
      </form>
    </div>
  </div>

  <!-- Categories Widget -->
  <div class="card my-4">
    <h5 class="card-header">Categories</h5>
    <div class="card-body">
      <div class="row">
        <div class="col-lg-6">
          <ul class="list-unstyled mb-0">
            <?php 
              $query=mysqli_query($con,"select categories.id as catid,name from categories");
              while($row=mysqli_fetch_array($query))
              {
            ?>

            <li  class="mb-2">
              <a href="category.php?catid=<?php echo htmlentities($row['catid'])?>" class="text-dark"><?php echo htmlentities($row['name']);?></a>
            </li>
            <?php } ?>
          </ul>
        </div>
      </div>
    </div>
  </div>

  <!-- Side Widget -->
  <div class="card my-4">
    <h5 class="card-header">Recent News</h5>
    <div class="card-body">
      <ul class="mb-0 list-unstyled">
        <?php
          // Your SQL query to fetch data from the tables
          $sql = "
          select offline_posts.id as id_offline_posts,offline_posts.title as title from offline_posts left join categories on categories.id=offline_posts.category_id ORDER BY offline_posts.id DESC limit 8;
          "; // Adjust the JOIN condition based on your table structure

          // $result = $mysqli->query($sql);
          $result = mysqli_query($con, $sql);
          if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
        ?>
        
          <li class="d-flex mb-2 align-items-center">
            <a href="news-details.php?id_offline_posts=<?php echo htmlentities($row['id_offline_posts'])?>" class="text-dark"><?php echo htmlentities($row['title']);?></a>
          </li>
        <?php }
        } ?>
      </ul>
    </div>
  </div>

  <!-- Side Widget -->
  <div class="card my-4">
    <h5 class="card-header">Popular  News</h5>
    <div class="card-body">
      <ul class="list-unstyled">
        <?php
          // Your SQL query to fetch data from the tables
          $query1 = mysqli_query($con, "select offline_posts.id as id_offline_posts,offline_posts.title as title from offline_posts left join categories on categories.id=offline_posts.category_id order by view_counter desc limit 5"); // Adjust the JOIN condition based on your table structure

          while ($result=mysqli_fetch_array($query1)) 
          {
        ?>
        
          <li class="mb-2">
            <a href="news-details.php?id_offline_posts=<?php echo htmlentities($result['id_offline_posts'])?>" class="text-dark"><?php echo htmlentities($result['title']);?></a>
          </li>
        <?php } ?>
      </ul>
    </div>
  </div>

</div>
