<?php
//Header start
include("includes/header.php");
//header end

// nav bar start
include("includes/nav_bar.php");

require_once("require/database_connection.php");

// fetching latest post with user profile
$query = "SELECT p.*, u.image_path, u.first_name, u.email 
FROM post p
INNER JOIN users u ON p.user_id = u.user_id
WHERE p.post_status = 'active'
ORDER BY p.created_at DESC
LIMIT 1;";

$result = mysqli_query($connection, $query);

// nav bar end
        if (mysqli_num_rows($result) > 0) {
            $raw = mysqli_fetch_assoc($result);
        ?>
            <section class="featured">
            <div class="container">
                <div class="row">
                <div class="col-12">
                    <article class="featured-post">
                    <div class="featured-post-content">
                        <div class="featured-post-author">
                        <img src="<?php echo $raw['image_path'] ?? "" ?>" alt="author" />
                        <p>By <span><?php echo $raw['first_name'] ?? ""; ?></span></p>
                        </div>
                        <a href="single_blog.php?post_id=<?php echo $raw['post_id'] ?? "" ?>" class="featured-post-title">
                        <?php echo $raw['post_summary'] ?? ""; ?>
                        </a>
                        <ul class="featured-post-meta">
                        <li>
                            <i class="fa fa-clock-o"></i>
                            <?php echo $raw['created_at'] ?? ""; ?>
                        </li>
                        </ul>
                    </div>
                    <div class="featured-post-thumb">
                        <img src="<?php echo $raw['featured_image'] ?? ""; ?>" alt="feature-post-thumb" />
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
                    c.cat_id
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
                            p.created_at
                            FROM post p
                            WHERE p.post_status = 'active'
                            ORDER BY p.created_at DESC
                            LIMIT 4;";

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