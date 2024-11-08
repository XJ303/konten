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
                <p>Content Media Analysis merupakan website berita karanganyar memberikan analisis tajam untuk membedakan apakah berita tersebut membawa dampak positif atau negatif. Dari inisiatif pembangunan hingga acara budaya,  kami memecahkan setiap informasi menjadi potongan-potongan yang  dapat dimengerti dengan mudah, memberikan Anda wawasan yang lebih dalam tentang realitas sosial dan politik di Karanganyar.</p>    
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
                    /* if (isset($_GET['pageno'])) {
                        $pageno = $_GET['pageno'];
                    } else {
                        $pageno = 1;
                    }
                    $no_of_records_per_page = 5;
                    $offset = ($pageno-1) * $no_of_records_per_page;
        
                    $total_pages_sql = "SELECT COUNT(*) FROM online_posts";
                    // $total_pages_sql = "SELECT COUNT(*) FROM tblposts";
                    $result = mysqli_query($con,$total_pages_sql);
                    $total_rows = mysqli_fetch_array($result)[0];
                    $total_pages = ceil($total_rows / $no_of_records_per_page); */

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
                        online_posts.posting_date AS online_date,
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
                    WHERE online_posts.active=1
                    ORDER BY online_posts.id DESC limit 8 -- dilimit hanya 8 berita yang muncul di beranda berdasarkan input terakhir;
                    ";

                    // $result = $mysqli->query($sql);
                    $result = mysqli_query($con, $sql);

                    // Check if there are results
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {

                    ?>
                            <div class="card pt-2 mb-3">
                                <div class="card mb-2 border-0">
                                    <div class="row g-0 align-items-center">
                                        <div class="col-md-5 justify-content-center align-items-center">
                                            <img class="card-img-top" src="admin/<?php echo $row['url'] ?>" height="200px">
                                        </div>
                                        <div class="col-md-7">
                                            <div class="card-body">
                                                <p class="m-0 row justify-content-between align-items-center">
                                                    <a class="badge bg-success text-decoration-none link-light col-5 w-50" href="">
                                                        <?php echo $row['category_name']; ?>
                                                    </a>
                                                    <span class="rating-label col-2 text-center rounded-5 text-white font-weight-bold" style="padding: 2px 2px; background-color: <?php echo getColorBasedOnRatingOnline($row['total']); ?>">
                                                        <?php echo $row['total']; ?>
                                                    </span>
                                                </p>
                                                <p class="mb-2"><small> Posted on <?php echo $row["online_date"] ?></small></p>
                                                <a target="_blank" href="<?php echo $row['link'] ?>" class="card-title text-decoration-none text-dark">
                                                    <h5 class="card-title">
                                                        <?php echo $row['title'] ?>
                                                    </h5>
                                                </a>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    <?php }
                    } ?>

                    <!-- Pagination -->
                    <!-- <ul class="pagination justify-content-center mb-4">
                        <li class="page-item"><a href="?pageno=1"  class="page-link">First</a></li>
                        <li class="<?php if ($pageno <= 1) {
                                        echo 'disabled';
                                    } ?> page-item">
                            <a href="<?php if ($pageno <= 1) {
                                            echo '#';
                                        } else {
                                            echo "?pageno=" . ($pageno - 1);
                                        } ?>" class="page-link">Prev</a>
                        </li>
                        <li class="<?php if ($pageno >= $total_pages) {
                                        echo 'disabled';} ?> page-item">
                            <a href="<?php if ($pageno >= $total_pages) {
                                            echo '#';
                                        } else {
                                            echo "?pageno=" . ($pageno + 1);
                                        } ?> " class="page-link">Next</a>
                        </li>
                        <li class="page-item"><a href="?pageno=<?php echo $total_pages; ?>" class="page-link">Last</a></li>
                    </ul> -->
                </div>

                <!-- Blog Post -->
                <h4 class="headeroffline p-2 m mb-4 w-100 bg-body-secondary">Offline</h4>
                <div class="row">
                    <?php
                    /* if (isset($_GET['pageno'])) {
                        $pageno = $_GET['pageno'];
                    } else {
                        $pageno = 1;
                    }
                    $no_of_records_per_page = 5;
                    $offset = ($pageno-1) * $no_of_records_per_page;
        
                    $total_pages_sql = "SELECT COUNT(*) FROM offline_posts";
                    // $total_pages_sql = "SELECT COUNT(*) FROM tblposts";
                    $result = mysqli_query($con,$total_pages_sql);
                    $total_rows = mysqli_fetch_array($result)[0];
                    $total_pages = ceil($total_rows / $no_of_records_per_page); */

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
                        offline_posts.posting_date AS offline_date,
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
                    WHERE offline_posts.active=1
                    ORDER BY offline_posts.id DESC LIMIT 100;
                        "; // Adjust the JOIN condition based on your table structure

                    // $result = $mysqli->query($sql);
                    $result = mysqli_query($con, $sql);

                    // Check if there are results
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                    ?>
                            <div class="card mb-3 rounded-4 pt-2">
                                <div class="card mb-2 border-0">
                                    <div class="row g-0 align-items-center">
                                        <div class="col-md-5 justify-content-center align-items-center">
                                            <img class="card-img-top" src="admin/<?php echo $row['url'] ?>" height="200px">
                                        </div>
                                        <div class="col-md-7">
                                            <div class="card-body">
                                                <p class="m-0 row justify-content-between align-items-center">
                                                    <a class="badge bg-success text-decoration-none link-light col-5 w-50" href="">
                                                        <?php echo $row['category_name']; ?>
                                                    </a>
                                                    <span class="rating-label col-2 text-center rounded-5 text-white font-weight-bold" style="padding: 2px 2px; background-color: <?php echo getColorBasedOnRating($row['total']); ?>">
                                                        <?php echo $row['total']; ?>
                                                    </span>
                                                </p>
                                                <p class="mb-2"><small> Posted on <?php echo $row['offline_date'] ?></small></p>
                                                <a href="news-details.php?id_offline_posts=<?php echo $row['id_offline_posts'] ?>" class="card-title text-decoration-none text-dark">
                                                    <h5 class="card-title">
                                                        <?php echo $row['title'] ?>
                                                    </h5>
                                                </a>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    <?php }
                    } ?>

                    <!-- Pagination -->
                    <!-- <ul class="pagination justify-content-center mb-4">
                        <li class="page-item"><a href="?pageno=1"  class="page-link">First</a></li>
                        <li class="<?php if ($pageno <= 1) {
                                        echo 'disabled';
                                    } ?> page-item">
                            <a href="<?php if ($pageno <= 1) {
                                            echo '#';
                                        } else {
                                            echo "?pageno=" . ($pageno - 1);
                                        } ?>" class="page-link">Prev</a>
                        </li>
                        <li class="<?php if ($pageno >= $total_pages) {
                                        echo 'disabled';
                                    } ?> page-item">
                            <a href="<?php if ($pageno >= $total_pages) {
                                            echo '#';
                                        } else {
                                            echo "?pageno=" . ($pageno + 1);
                                        } ?> " class="page-link">Next</a>
                        </li>
                        <li class="page-item"><a href="?pageno=<?php echo $total_pages; ?>" class="page-link">Last</a></li>
                    </ul> -->
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
