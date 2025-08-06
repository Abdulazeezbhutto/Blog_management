<?php
//Header start
include("includes/header.php");
//header end

// nav bar start
include("includes/nav_bar.php");

require_once("require/database_connection.php");

// fetching latest post with user profile
$query = "SELECT 
    p.*, 
    u.image_path, 
    u.first_name, 
    u.email,
    (SELECT COUNT(*) FROM post_likes pl WHERE pl.post_id = p.post_id) AS total_likes,
    (SELECT COUNT(*) FROM post_comments pc WHERE pc.post_id = p.post_id) AS total_comments
    FROM post p
    INNER JOIN users u ON p.user_id = u.user_id
    WHERE p.post_status = 'active'
    ORDER BY p.created_at DESC
    LIMIT 1";

$result = mysqli_query($connection, $query);

// nav bar end

        if (mysqli_num_rows($result) > 0) {
            $raw = mysqli_fetch_assoc($result);
        ?>
        <section class="featured py-4">
                <div class="container">
                    <div class="row">
                        <div class="col-12">
                            <article class="featured-post row align-items-center">
                                <!-- Featured Post Content (Left Side) -->
                                <div class="col-md-8">
                                    <div class="featured-post-content">
                                        <div class="featured-post-author d-flex align-items-center mb-2">
                                            <img src="<?php echo $raw['image_path'] ?? ''; ?>" alt="author" class="rounded-circle me-2" width="40" height="40" />
                                            <p class="mb-0">By <span><?php echo $raw['first_name'] ?? ''; ?></span></p>
                                        </div>

                                        <a href="single_blog.php?post_id=<?php echo $raw['post_id'] ?? ''; ?>" class="featured-post-title h5 d-block mb-2">
                                            <?php echo $raw['post_summary'] ?? ''; ?>
                                        </a>

                                        <ul class="featured-post-meta list-unstyled d-flex gap-5 text-muted small">
                                            <li class="me-4">
                                                <i class="fa fa-clock-o me-2"></i>
                                                <?php echo $raw['created_at'] ?? ''; ?>
                                            </li>

                                            <li class="me-4"><?php
                                                if(isset($_SESSION['user'])){
                                                        ?>
                                                        <a href = "process.php?post_id=<?php echo $raw['post_id']?>&action=like_process">
                                                             <i class="fa fa-thumbs-up me-2"></i></a>
                                                        <?php
                                                }else{
                                                    ?>
                                                      <i class="fa fa-thumbs-up me-2"></i>
                                                    <?php
                                                }
                                            ?>
                                                <?php echo $raw['total_likes']?>
                                                
                                            </li>

                                            <li class="me-4">
                                                <i class="fa fa-comments me-2"></i>
                                                <a href="post_comments.php?post_id=<?php echo $raw['post_id'] ?? ''; ?>">See Comments <?php echo $raw['total_comments']?> </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>

                                <!-- Featured Image (Right Side) -->
                                <div class="col-md-4">
                                    <div class="featured-post-thumb text-center">
                                        <img src="<?php echo $raw['featured_image'] ?? ''; ?>" alt="feature-post-thumb" class="img-fluid rounded shadow-sm" style="max-width: 100%; height: auto;" />
                                    </div>
                                </div>
                            </article>
                        </div>
                    </div>
                </div>
        </section>


        <?php
        }
?>

        <section class="blog">
        <div class="container">
            <div class="row">
            <div class="col-lg-8">
                <div class="blog-section-title">
                <h2>Articles</h2>
                <p>View the latest news on Blogger</p>
                </div>
                <?php
                $query = "SELECT 
                            p.post_id,
                            p.post_title,
                            p.post_summary,
                            p.post_description,
                            p.featured_image,
                            p.created_at,
                            u.first_name AS author_name,
                            u.image_path,
                            c.cate_name,
                            c.cat_id,
                            (
                                SELECT COUNT(*) 
                                FROM post_likes pl 
                                WHERE pl.post_id = p.post_id
                            ) AS total_likes,
                            (
                                SELECT COUNT(*) 
                                FROM post_comments pc 
                                WHERE pc.post_id = p.post_id
                            ) AS total_comments
                        FROM post p
                        INNER JOIN users u ON p.user_id = u.user_id
                        LEFT JOIN post_category c ON p.cat_id = c.cat_id
                        WHERE p.post_status = 'active'
                        ORDER BY p.created_at DESC;";

                $result = mysqli_query($connection, $query);
                if (mysqli_num_rows($result) > 0) {
                    while ($raw = mysqli_fetch_assoc($result)) {
                ?>
                        <article class="blog-post">
                        <div class="blog-post-thumb">
                            <img src="<?php echo $raw['featured_image'] ?? "" ?>" alt="blog-thum" />
                        </div>
                        <div class="blog-post-content">
                            <div class="blog-post-tag">
                            <a href="category.php?cat_id=<?php echo $raw['cat_id']??""?>&cate_name=<?php echo $raw['cate_name']??""?>"><?php echo $raw['cate_name'] ?? "" ?></a>
                            </div>
                            <div class="blog-post-title">
                            <a href="single_blog.php?post_id=<?php echo $raw['post_id'] ?? "" ?>">
                                <?php echo $raw['post_summary'] ?? "" ?>
                            </a>
                            </div>
                            <div class="blog-post-meta">
                            <ul>
                                <li>By <a href="about.php"><?php echo $raw['author_name'] ?? "" ?></a></li>
                                <li>
                                <i class="fa fa-clock-o"></i>
                                <?php echo $raw['created_at'] ?? "" ?>
                                </li>
                                 <li class="me-4"><?php
                                                if(isset($_SESSION['user'])){
                                                        ?>
                                                        <a href = "process.php?post_id=<?php echo $raw['post_id']?>&action=like_process">
                                                             <i class="fa fa-thumbs-up me-2"></i></a>
                                                        <?php
                                                }else{
                                                    ?>
                                                      <i class="fa fa-thumbs-up me-2"></i>
                                                    <?php
                                                }
                                            ?>
                                                <?php echo $raw['total_likes']?>
                                                
                                </li>

                                 <li class="me-4">
                                     <i class="fa fa-comments me-2"></i>
                                     <a href="post_comments.php?post_id=<?php echo $raw['post_id'] ?? ''; ?>">See Comments <?php echo $raw['total_comments']?> </a>
                                 </li>
                            </ul>
                            </div>
                            <p>
                            <?php echo $raw['post_description'] ?? "" ?>
                            </p>
                            <a href="single_blog.php?post_id=<?php echo $raw['post_id'] ?? "" ?>" class="blog-post-action">read more <i class="fa fa-angle-right"></i></a>
                        </div>
                        </article>
                <?php
                    }
                }
                ?>
            </div>

            <!--Trending Posts-->
            <div class="col-lg-4">
                <div class="blog-post-widget">
                <div class="latest-widget-title">
                    <h2>Trending post</h2>
                </div>

                <?php
                $query = "SELECT 
                            p.post_id,
                            p.post_summary,
                            p.post_description,
                            p.post_title,
                            p.featured_image,
                            p.created_at,
                            (
                                SELECT COUNT(*) 
                                FROM post_likes pl 
                                WHERE pl.post_id = p.post_id
                            ) AS total_likes,
                            (
                                SELECT COUNT(*) 
                                FROM post_comments pc 
                                WHERE pc.post_id = p.post_id
                            ) AS total_comments
                        FROM post p
                        WHERE p.post_status = 'active'
                        ORDER BY p.created_at DESC
                        LIMIT 4;
                        ;";

                $result = mysqli_query($connection, $query);
                if (mysqli_num_rows($result) > 0) {
                    while ($raw = mysqli_fetch_assoc($result)) {
                ?>
                        <div class="latest-widget">
                            <div class="latest-widget-thum">
                            <a href="single_blog.php?post_id=<?php echo $raw['post_id'] ?? "" ?>">
                                <img src="<?php echo $raw['featured_image'] ?? "" ?>" alt="blog-thum" />
                            </a>
                            <div class="icon">
                                <a href="single_blog.php?post_id=<?php echo $raw['post_id'] ?? "" ?>">
                                <img src="images/blog/icon.svg" alt="icon" />
                                </a>
                            </div>
                            </div>
                            <div class="latest-widget-content">
                            <div class="content-title">
                                <a href="single_blog.php?post_id=<?php echo $raw['post_id'] ?? "" ?>">
                                <?php echo $raw['post_summary'] ?? "" ?>
                                </a>
                            </div>
                            <div class="content-meta">
                                <ul>
                                <li>
                                    <i class="fa fa-clock-o"></i>
                                    <?php echo $raw['created_at'] ?? "" ?>
                                </li>
                                 <li class="me-4"><?php
                                                if(isset($_SESSION['user'])){
                                                        ?>
                                                        <a href = "process.php?post_id=<?php echo $raw['post_id']?>&action=like_process">
                                                             <i class="fa fa-thumbs-up me-2"></i></a>
                                                        <?php
                                                }else{
                                                    ?>
                                                      <i class="fa fa-thumbs-up me-2"></i>
                                                    <?php
                                                }
                                            ?>
                                                <?php echo $raw['total_likes']?>
                                                
                                </li>

                                 <li class="me-4">
                                     <i class="fa fa-comments me-2"></i>
                                     <a href="post_comments.php?post_id=<?php echo $raw['post_id'] ?? ''; ?>">See Comments <?php echo $raw['total_comments']?> </a>
                                 </li>
                                </ul>
                            </div>
                            </div>
                        </div>
                <?php
                    }
                }
                ?>
                </div>
            </div>
            </div>
        </div>
        </section>

<?php
//footer start
include("includes/footer.php");
//footer end
?>