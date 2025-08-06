<?php

require_once("require/database_connection.php");
//Header start
include("includes/header.php");
//header end


// nav bar start
include("includes/nav_bar.php");

// nav bar end
// category body start


if(isset($_GET['cat_id'])){
    // echo $_GET['cat_id'];

    $query = "SELECT 
        p.post_id,
        p.post_title,
        p.post_description,
        p.featured_image,
        p.created_at,
        p.post_summary,
        p.post_status,
        c.cate_name,
        u.user_id,
        u.first_name,
        u.last_name,
        u.email,
        u.image_path
    FROM post p
    INNER JOIN users u ON p.user_id = u.user_id
    INNER JOIN post_category c ON p.cat_id = c.cat_id
    WHERE p.cat_id = '".$_GET['cat_id']."' AND p.post_status = 'active'";

    $result = mysqli_query($connection, $query);
    if(mysqli_num_rows($result) > 0){
        ?>
           <section class="blog">
                    <div class="container">
                        <div class="row">
                        <div class="col-lg-8 mx-auto">
                            <!--blog title-->
                            <div class="blog-section-title">
                            <h2><?php echo $_GET['cate_name']??""?></h2>
                            <p>View the latest news on <?php echo $_GET['cate_name']??""?></p>
                            </div>
                            <!--blog title-->
        
        
        <?php
        while ($row = mysqli_fetch_assoc($result)){

            ?>

             <!--blog post-->
                            <article class="blog-post">
                            <div class="blog-post-thumb">
                                <img src="<?php echo $row['featured_image']??""?>" alt="blog-thum" />
                            </div>
                            <div class="blog-post-content">
                                <div class="blog-post-title">
                                <a href="single_blog.php?post_id=<?php echo $row['post_id']??""?>"><?php echo $row['post_title']??""?></a>
                                </div>
                                <div class="blog-post-meta">
                                <ul>
                                    <li>By <a href="about.php?user_id=<?php echo $row['user_id']?>"><?php echo $row['first_name'].$row['last_name']??""?></a></li>
                                    <li>
                                    <i class="fa fa-clock-o"></i>
                                    <?php echo $row['created_at']??""?>
                                    </li>
                                </ul>
                                </div>
                                <p>
                                <?echo $row['post_description']?>
                                </p>
                                <a href="single_blog.php?post_id=<?php echo $row['post_id']??""?>" class="blog-post-action">read more <i class="fa fa-angle-right"></i></a>
                            </div>
                            </article>
                            <!--blog post-->
            
            <?php

        }

    }

}
?>




                            

                           

                        </div>
                        </div>
                    </div>
        </section>




<?php
// category body end

//footer start
include("includes/footer.php");
//footer end 


?>