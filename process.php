<?php
session_start(); // Required for login session
require_once("require/database_connection.php");


// REGISTER
if (isset($_REQUEST['submit']) && $_REQUEST['submit'] == "register") {
    echo "<pre>";
    print_r($_REQUEST);
    print_r($_FILES);
    echo "</pre>";

    // Step 1: Check if user already exists
    $email = $_REQUEST['email'];
    $check = true;

    $query = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($connection, $query);

    if (mysqli_num_rows($result) > 0) {
        $check = false;
        header("location: auth/register.php?msg=Account already exists&color=red");
        exit();
    }

    // Step 2: Process profile image
    $flag = false;

    if (isset($_FILES['profile']) && $check) {
        $tempName = $_FILES['profile']['tmp_name'];
        $fileName = time() . $_FILES['profile']['name'];
        $uploadDir = "uploads/";
        $destination = $uploadDir . $fileName;

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        if (move_uploaded_file($tempName, $destination)) {
            $flag = true;
        } else {
            header("location: auth/register.php?msg=Failed to move file&color=red");
            exit();
        }
    } else {
        header("location: auth/register.php?msg=No file uploaded or there was an error&color=red");
        exit();
    }

    // Step 3: Insert new user if everything is OK
    if ($flag) {
        $first_name      = $_REQUEST['first_name'];
        $middle_name     = $_REQUEST['middle_name'];
        $last_name       = $_REQUEST['last_name'];
        $raw_password    = $_REQUEST['password'];
        $password        = password_hash($raw_password, PASSWORD_DEFAULT);
        $date_of_birth   = $_REQUEST['dob'];
        $address         = $_REQUEST['address'];
        $contact_number  = $_REQUEST['contact_no'];
        $gender          = $_REQUEST['gender'];

        $query = "INSERT INTO users 
        (first_name, middle_name, last_name, email, password, gender, date_of_birth, image_path, address, contact_no) 
        VALUES (
            '$first_name',
            '$middle_name',
            '$last_name',
            '$email',
            '$password',
            '$gender',
            '$date_of_birth',
            '$destination',
            '$address',
            '$contact_number'
        )";

        if (mysqli_query($connection, $query)) {
            header("location: auth/login.php?msg=Registration successful&color=green");
        } else {
            header("location: auth/register.php?msg=Database error: " . mysqli_error($connection) . "&color=red");
        }
    }
}


// LOGIN
    elseif (isset($_REQUEST['submit']) && $_REQUEST['submit'] == "login") {
        $email = trim($_REQUEST['email'] ?? '');
        $password = trim($_REQUEST['password'] ?? '');

        // Basic input validation
        if (empty($email) || empty($password)) {
            header("Location: auth/login.php?msg=Please fill all fields&color=red");
            exit();
        }

        // Fetch user by email
        $query = "SELECT * FROM users WHERE email = '$email'";
        $result = mysqli_query($connection, $query);

        if ($result && mysqli_num_rows($result) === 1) {
            $user = mysqli_fetch_assoc($result);

            // Verify hashed password
            if (password_verify($password, $user['password'])) {
                $_SESSION['user'] = $user;

                if ($user['role_id'] == 1) {
                    header("Location: index.php?msg=Login successful&color=green");
                } elseif ($user['role_id'] == 2) {
                    header("Location: index.php?msg=Login successful&color=green");
                } else {
                    header("Location: auth/login.php?msg=Unknown role&color=red");
                }
                exit();
            } else {
                header("Location: auth/login.php?msg=Invalid password&color=red");
                exit();
            }
        } else {
            header("Location: auth/login.php?msg=No user found with this email&color=red");
            exit();
        }
    }

    // post_Add
        elseif (isset($_REQUEST["submit"]) && $_REQUEST["submit"] == "add_post") {

        $user_id     = intval($_POST['user_id']);
        $title       = mysqli_real_escape_string($connection, $_POST['title']);
        $summary     = mysqli_real_escape_string($connection, $_POST['summary']);
        $description = mysqli_real_escape_string($connection, $_POST['description']);
       
        $category_id = intval($_POST['category']);
        $tags        = $_POST['tags'] ?? [];
        $created_at  = date('Y-m-d H:i:s');

        // Validation 
        if (empty($title) || empty($description) || empty($category_id)) {
            header("location: admin/add_post.php?msg=Please fill all required fields&color=red");
            exit;
        }

        // Validate category exists
        $cat_check = mysqli_query($connection, "SELECT cat_id FROM post_category WHERE cat_id = '$category_id'");
        if (mysqli_num_rows($cat_check) == 0) {
            header("location: admin/add_post.php?msg=Invalid category selected&color=red");
            exit;
        }

        // Handling image 
        $image_path = "";
        if (isset($_FILES['post_image']) && $_FILES['post_image']['error'] == 0) {
            $uploadDir = "uploads/";
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            $fileName = time() . "_" . basename($_FILES['post_image']['name']);
            $destination = $uploadDir . $fileName;

            if (move_uploaded_file($_FILES['post_image']['tmp_name'], $destination)) {
                $image_path = $destination;
            } else {
                header("location: admin/add_post.php?msg=Failed to upload image&color=red");
                exit;
            }
        }

        // Insert into database 
        $query = "
            INSERT INTO post (cat_id, user_id, post_title, post_summary, post_description, featured_image)
            VALUES ('$category_id', '$user_id', '$title', '$summary', '$description', '$image_path')
        ";

        if (mysqli_query($connection, $query)) {
            $post_id = mysqli_insert_id($connection);

            // Insert tags if selected
            if (!empty($tags)) {
                foreach ($tags as $tag_id) {
                    $tag_id = intval($tag_id);
                    mysqli_query($connection, "INSERT INTO post_tags (post_id, tag_id) VALUES ('$post_id', '$tag_id')");
                }
            }

            header("location: admin/all_posts.php?msg=Post added successfully&color=green");
            exit;
        } else {
            header("location: admin/add_post.php?msg=Cannot add post. Database error: " . mysqli_error($connection) . "&color=red");
            exit;
        }
    }

    // update post
    elseif (isset($_REQUEST['submit']) && $_REQUEST['submit'] == "update_post") {

        // Get form data
        $post_id = $_POST['post_id'];
        $category_id = $_POST['category'];
        $user_id = $_POST['user_id'];
        $tags = $_POST['tags'] ?? [];
        $title       = mysqli_real_escape_string($connection, $_POST['title']);
        $summary     = mysqli_real_escape_string($connection, $_POST['summary']);
        $description = mysqli_real_escape_string($connection, $_POST['description']);

        // Handle image upload
        $image_path = "";
        if (!empty($_FILES['post_image']['name'])) {
            $target_dir = "uploads/";
            $image_name = time() . "_" . $_FILES['post_image']['name'];
            $target_file = $target_dir . $image_name;

            if (move_uploaded_file($_FILES['post_image']['tmp_name'], $target_file)) {
                $image_path = $target_file;
            }
        }

        // Update post query
        if ($image_path != "") {
            $update_post = "UPDATE post 
                            SET post_title='$title', 
                                post_summary='$summary', 
                                post_description='$description', 
                                cat_id='$category_id', 
                                featured_image='$image_path' 
                            WHERE post_id='$post_id' AND user_id='$user_id'";
        } else {
            $update_post = "UPDATE post 
                            SET post_title='$title', 
                                post_summary='$summary', 
                                post_description='$description', 
                                cat_id='$category_id'
                            WHERE post_id='$post_id' AND user_id='$user_id'";
        }

        if (mysqli_query($connection, $update_post)) {

            // Delete old tags
            mysqli_query($connection, "DELETE FROM post_tags WHERE post_id='$post_id'");

            // Insert new tags
            if (!empty($tags)) {
                foreach ($tags as $tag_id) {
                    mysqli_query($connection, "INSERT INTO post_tags (post_id, tag_id) VALUES ('$post_id', '$tag_id')");
                }
            }

            header("location: admin/all_posts.php?msg=Post updated successfully");
            exit;
        } else {
            echo "Error updating post: " . mysqli_error($connection);
        }
    }

    // delete post
    elseif (isset($_GET['submit']) && $_GET['submit'] === "delete_post" && !empty($_GET['post_id'])) {
        $post_id = (int) $_GET['post_id']; 

        $query = "DELETE FROM post_tags WHERE post_id = $post_id";
        // Step 1: Delete related tags (if table exists)
        mysqli_query($connection, $query);

        // Step 3: Delete the post
        $query = "DELETE FROM post WHERE post_id = $post_id";
        $result = mysqli_query($connection, $query);

        if ($result) {
            header("Location: admin/your_posts.php?msg=Post deleted successfully&color=green");
            exit;
        } else {
            die("Error deleting post: " . mysqli_error($connection));
        }
    }


    // update add comment 
   elseif (isset($_POST['submit']) && $_POST['submit'] == "add_comment" && !empty($_POST['post_id']) && !empty($_SESSION['user']['user_id']) && !empty($_POST['comment']))
    {
    echo "<pre>";
    print_r($_POST);  
    print_r($_SESSION);
    echo "</pre>";

    $post_id = (int) $_POST['post_id'];
    $user_id = (int) $_SESSION['user']['user_id'];
    $comment = mysqli_real_escape_string($connection, trim($_POST['comment']));

    $query = "INSERT INTO post_comments (comment_message, post_id, user_id) 
              VALUES ('$comment', $post_id, $user_id)";

    $result = mysqli_query($connection, $query);

    if ($result) {
        header("Location: post_comments.php?msg=Comment added&color=green");
        exit();
    } else {
        header("Location: post_comments.php?msg=Something went wrong&color=red");
        exit();
    }
    }


    // like process
    elseif(isset($_GET['post_id']) && $_GET['action'] === "like_process"){
        echo "<pre>";
        print_r($_REQUEST);
        echo "</pre>";
        $user_id = $_SESSION['user']['user_id'];
        $post_id = mysqli_real_escape_string($connection, $_REQUEST['post_id']);

        $check = "SELECT * FROM post_likes WHERE post_id = $post_id AND user_id = $user_id";
        $result = mysqli_query($connection, $check);
        if (mysqli_num_rows($result) > 0) {
        // Unlike (delete)
        $delete = "DELETE FROM post_likes WHERE post_id = $post_id AND user_id = $user_id";
        mysqli_query($connection, $delete);
        } else {
            // Like (insert)
            $insert = "INSERT INTO post_likes (post_id, user_id) VALUES ($post_id, $user_id)";
            mysqli_query($connection, $insert);
        }
        header("Location: single_blog.php?post_id=$post_id");
    }
   


// Invalid access
else {
    header("Location: ../auth/login.php?msg=Invalid request&color=red");
    exit();
}
?>
