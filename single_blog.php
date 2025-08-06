<?php
require_once("require/database_connection.php");

// Header
include("includes/header.php");

// Nav bar
include("includes/nav_bar.php");

if (isset($_GET["post_id"])) {
    $post_id = mysqli_real_escape_string($connection, $_GET["post_id"]);

    $query = "SELECT 
        p.post_id,
        p.post_title,
        p.post_description,
        p.featured_image,
        p.created_at,
        p.post_summary,
        p.post_status,
        c.cate_name,
        c.cat_id,
        c.cate_name,
        u.user_id,
        u.first_name,
        u.last_name,
        u.email,
        u.image_path
    FROM post p
    INNER JOIN users u ON p.user_id = u.user_id
    INNER JOIN post_category c ON p.cat_id = c.cat_id
    WHERE p.post_id = '$post_id' AND p.post_status = 'active'
    LIMIT 1";

    $result = mysqli_query($connection, $query);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        ?>

        <section class="blog-single">
            <div class="container">
                <div class="row">
                    <div class="col-lg-2 order-2 order-lg-1">
                        <div class="share-now">
                            <a href="#" class="scrol">Share</a>
                            <div class="sociel-icon">
                                <ul>
                                    <li><a href="#"><i class="fa fa-facebook"></i></a></li>
                                    <li><a href="#"><i class="fa fa-twitter"></i></a></li>
                                    <li><a href="#"><i class="fa fa-instagram"></i></a></li>
                                    <li><a href="#"><i class="fa fa-linkedin"></i></a></li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-10 order-1 order-lg-2">
                        <article class="single-blog">
                            <a href="category.php?cat_id=<?php echo $row['cat_id']??""?>&cate_name=<?php echo $row['cate_name']??""?>" class="tag"><?php echo htmlspecialchars($row['cate_name']); ?></a>
                            <p class="title"><?php echo htmlspecialchars($row['post_title']); ?></p>
                            <ul class="meta">
                                <li>By 
                                    <a href="about.php?user_id=<?php echo $row['user_id']??""?>">
                                        <img src="<?php echo htmlspecialchars($row['image_path']); ?>" alt="author" style="width: 30px; height: 30px; border-radius: 50%; object-fit: cover;">
                                        <?php echo htmlspecialchars($row['first_name'] . ' ' . $row['last_name']); ?>
                                    </a>
                                </li>
                                <li>
                                    <i class="fa fa-clock-o"></i>
                                    <?php echo htmlspecialchars($row['created_at']); ?>
                                </li>
                            </ul>
                            <img src="<?php echo htmlspecialchars($row['featured_image']); ?>" alt="banner" class="img-fluid" />
                            <blockquote>
                                <?php echo htmlspecialchars($row['post_summary']); ?>
                            </blockquote>
                            <p><?php echo nl2br(htmlspecialchars($row['post_description'])); ?></p>
                        </article>
                    </div>
                </div>
            </div>
        </section>

        <?php
    } else {
        echo "<p style='text-align:center; padding: 20px;'>Post not found or inactive.</p>";
    }
}

// Footer
include("includes/footer.php");
?>