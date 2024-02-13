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
              $query=mysqli_query($con,"select id,CategoryName from tblcategory");
              while($row=mysqli_fetch_array($query))
              {
            ?>

            <li  class="mb-2">
              <a href="category.php?catid=<?php echo htmlentities($row['id'])?>" class="text-dark"><?php echo htmlentities($row['CategoryName']);?></a>
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
          $query=mysqli_query($con,"select tblposts_offline.id as pid,tblposts_offline.PostImage,tblposts_offline.PostTitle as posttitle from tblposts_offline left join tblcategory on tblcategory.id=tblposts_offline.CategoryId limit 8");
          while ($row=mysqli_fetch_array($query)) 
          {
        ?>
        
        <li class="d-flex mb-2 align-items-center">
          <img class="mr-2 rounded-circle" src="admin/postimages/<?php echo htmlentities($row['PostImage']);?>" alt="<?php echo htmlentities($row['posttitle']);?>" width="50px" height="50px">
          <a href="news-details.php?nid=<?php echo htmlentities($row['pid'])?>" class="text-dark"><?php echo htmlentities($row['posttitle']);?></a>
        </li>
        <?php } ?>
      </ul>
    </div>
  </div>

  <!-- Side Widget -->
  <div class="card my-4">
    <h5 class="card-header">Popular  News</h5>
    <div class="card-body">
      <ul class="list-unstyled">
        <?php
          $query1=mysqli_query($con,"select tblposts_offline.id as pid,tblposts_offline.PostTitle as posttitle from tblposts_offline left join tblcategory on tblcategory.id=tblposts_offline.CategoryId order by viewCounter desc limit 5");
          while ($result=mysqli_fetch_array($query1)) 
          {
        ?>
        
        <li class="mb-2">
          <a href="news-details.php?nid=<?php echo htmlentities($result['pid'])?>" class="text-dark"><?php echo htmlentities($result['posttitle']);?></a>
        </li>
        <?php } ?>
      </ul>
    </div>
  </div>

</div>
