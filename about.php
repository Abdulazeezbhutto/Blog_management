<?php
require_once("require/database_connection.php");
include("includes/header.php");
include("includes/nav_bar.php");
?>

<!-- Custom Styles -->
<style>
    .profile-img {
        width: 150px;
        height: 150px;
        object-fit: cover;
        border-radius: 50%;
        border: 3px solid #dee2e6;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }
</style>

<?php
if (isset($_GET['user_id'])) {
    $user_id = mysqli_real_escape_string($connection, $_GET['user_id']);

    // Fetch user info and their posts
    $query = "SELECT 
        p.post_id,
        p.post_title,
        p.post_summary,
        p.post_description,
        p.featured_image,
        p.created_at,
        p.post_status,
        c.cate_name,
        u.first_name,
        u.last_name,
        u.image_path
    FROM post p
    INNER JOIN users u ON p.user_id = u.user_id
    INNER JOIN post_category c ON p.cat_id = c.cat_id
    WHERE p.user_id = '$user_id' AND p.post_status = 'active'
    ORDER BY p.created_at DESC";

    $result = mysqli_query($connection, $query);

    if (mysqli_num_rows($result) > 0) {
        // Fetch first row for user profile info
        $first_row = mysqli_fetch_assoc($result);
        $full_name = htmlspecialchars($first_row['first_name'] . " " . $first_row['last_name']);
        ?>

        <!-- User Profile Section -->
        <section class="about py-5 bg-light">
            <div class="container">
                <div class="row justify-content-center text-center">
                    <div class="col-lg-8">
                        <img src="<?php echo !empty($first_row['image_path']) ? $first_row['image_path'] : 'images/default-user.png'; ?>" alt="User Image" class="profile-img mb-3">
                        <h3>Hi, I Am <?php echo $full_name; ?></h3>
                        <h5 class="text-muted">I am a social person</h5>
                        <ul class="social-icon list-inline mt-3">
                            <li class="list-inline-item"><a href="#"><i class="fa fa-facebook"></i></a></li>
                            <li class="list-inline-item"><a href="#"><i class="fa fa-twitter"></i></a></li>
                            <li class="list-inline-item"><a href="#"><i class="fa fa-instagram"></i></a></li>
                            <li class="list-inline-item"><a href="#"><i class="fa fa-linkedin"></i></a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>

        <!-- All Posts Heading -->
        <section class="py-4">
            <div class="container">
                <div class="row">
                    <div class="col text-center">
                        <h2 class="fw-bold">All Posts by <?php echo $full_name; ?></h2>
                    </div>
                </div>
            </div>
        </section>

        <?php
        // Reset pointer to include first row again in loop
        mysqli_data_seek($result, 0);

        while ($row = mysqli_fetch_assoc($result)) {
            ?>
            <section class="about py-4 border-bottom">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="about-me">
                                <div class="banner mb-3">
                                    <div class="about-shape-right-top">
                                        <img src="images/about/blob1.svg" alt="svg">
                                    </div>
                                    <div class="about-shape-left-bottom">
                                        <img src="images/about/blob2.svg" alt="svg">
                                    </div>
                                    <img src="<?php echo !empty($row['featured_image']) ? $row['featured_image'] : 'images/default-post.png'; ?>" alt="Post Image" class="img-fluid">
                                </div>
                                <h3><?php echo htmlspecialchars($row['post_title']); ?></h3>
                                <p><?php echo htmlspecialchars($row['post_description']); ?></p>
                                <p><strong>Category:</strong> <?php echo htmlspecialchars($row['cate_name']); ?> |
                                   <strong>Date:</strong> <?php echo date("F j, Y", strtotime($row['created_at'])); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <?php
        }
    } else {
        echo "<h3 class='text-center'>No active posts found for this user.</h3>";
    }

} else {
    // Default About Section when no user_id is passed
    ?>
    <section class="about py-5 bg-light">
        <div class="container">
            <div class="row justify-content-center text-center">
                <div class="col-lg-8">
                    <img src="images/about/actior.png" alt="image" class="profile-img mb-3">
                    <h3>Hi I Am Mary Astor</h3>
                    <p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr...</p>
                    <div class="banner mt-3">
                        <div class="about-shape-right-top">
                            <img src="images/about/blob1.svg" alt="svg">
                        </div>
                        <div class="about-shape-left-bottom">
                            <img src="images/about/blob2.svg" alt="svg">
                        </div>
                        <img src="images/about/banner.png" alt="banner" class="img-fluid">
                    </div>
                    <h5 class="mt-4">I am a social person</h5>
                    <ul class="social-icon list-inline mt-3">
                        <li class="list-inline-item"><a href="#"><i class="fa fa-facebook"></i></a></li>
                        <li class="list-inline-item"><a href="#"><i class="fa fa-twitter"></i></a></li>
                        <li class="list-inline-item"><a href="#"><i class="fa fa-instagram"></i></a></li>
                        <li class="list-inline-item"><a href="#"><i class="fa fa-linkedin"></i></a></li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
    <?php
}

include("includes/footer.php");
?>