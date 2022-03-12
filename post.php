<?php
include 'connect.php';
include 'nav.php';
$postID = $_GET['_postid'];

$sqlShowPost = "select post.postid,user.username,post.text,post.category,post.created_at from post join user where post.userid=user.userid && post.postid = '$postID' ";
$resultShowPost = mysqli_query($conn, $sqlShowPost);
$rowPost = mysqli_fetch_array($resultShowPost);


//insert the comment in the database
if (isset($_POST['commentButton'])) {
    if (isset($_SESSION['user_id'])) {
        if (!empty($_POST['commentText'])) {
            $comment = $_POST['commentText'];
            $userid = $_SESSION['user_id'];
            date_default_timezone_set("Asia/Dhaka");
            $time = date("d/m/y") . " " . date("h:i:sa");
            $sqlCommentPost = "insert into comment(userid,postid,comment,date) values('$userid','$postID','$comment','$time')";
            $resultCommentPost = mysqli_query($conn, $sqlCommentPost);
            if ($resultCommentPost) {
                echo "<script>alert('Comment added successfully.')</script>";
            } else {
                echo "<script>alert('something wrong')</script>";
            }
        }
    } else {
        echo "<script>alert('Please login first')</script>";
    }
}


?>


<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">
    <script type="text/javascript" src="https://code.jquery.com/jquery-1.7.1.min.js"></script>
    <link rel="stylesheet" type="text/css" href="css/index.css">
    <link rel="stylesheet" href="css/all.min.css">

    <title>Post</title>
</head>

<body>
    <div class="container mt-5 mb-5">
        <div class="row d-flex align-items-center justify-content-center">
            <div class="col-md-12">
                <div class="card border-0 shadow rounded">
                    <!-- username,category,userimage,time of in the post -->
                    <div class="d-flex justify-content-between p-2 px-3">
                        <div class="d-flex flex-row align-items-center"> <img src="images/propic.jpg" width="50" class="rounded-circle">
                            <div class="d-flex flex-column ml-2" style="margin-left: 10px;"> 
                            <h4 class="mt-2"><?php echo $rowPost['username'] ?></h4>
                            <small class="text-primary"><?php echo $rowPost['category'] ?></small> 
                        </div>
                        </div>
                        <div class="d-flex flex-row mt-1 ellipsis"> <small class="mr-2"><?php echo $rowPost['created_at'] ?></small>  </div>
                    </div>
                    <div class="p-2">
                        <!-- post main text -->
                        <p class="text-justify" style="margin-left: 12px;white-space: pre-line;"><?php echo $rowPost['text'] ?></p>
                        <hr>
                        <!-- show total comments in the post, button to show all the comments and hide the comments -->
                        <?php

                        $sqlComment = "select comment.comment,comment.date,user.username from comment join user where comment.userid=user.userid && comment.postid= '$postID'";
                        $resultComment = mysqli_query($conn, $sqlComment);
                        $countComment = $resultComment->num_rows;
                        
                        $sqlTotalLike = "select *from likedpost where postid = '$postID'";
                        $resultTotalLike = mysqli_query($conn,$sqlTotalLike);
                        $countTotalLike = $resultTotalLike->num_rows;
        
                        if (isset($_SESSION['user_id'])) {
                            $userid = $_SESSION['user_id'];
                            $sqlLike = "select *from likedpost where userid = '$userid' && postid = '$postID'";
                            $resultLike = mysqli_query($conn,$sqlLike);
                            $countLike = $resultLike->num_rows;
                        } 
                        
                        ?>
                        <div class="d-flex justify-content-between align-items-center" style="margin-top: 5px;">
                            <div class="d-flex flex-row icons d-flex align-items-center"> 
                                        <?php
                                        if(isset($_SESSION['user_id']))
                                        {
                                            if($countLike>0)
                                            {
                                                ?>
                                                    <i class="fa fa-heart like_btn" title="<?php echo $postID ?>" ></i>
                                                <?php
                                            }else{
                                                ?>
                                                    <i class="far fa-heart like_btn" title="<?php echo $postID ?>" ></i>
                                                <?php
                                            }
                                        }else{
                                            ?>
                                               <i class="far fa-heart" title="" ></i> 
                                            <?php
                                        }    
                                        ?>
                                         
                                        <span style="margin-left: 5px;" id='likesCount'><?php echo $countTotalLike ?></span><span>likes</span> </div>
                            <div class="d-flex flex-row muted-color"> <span style="margin-right: 10px;"><?php echo $countComment ?> comments</span> </div>

                        </div>

                        <hr>


                        <!-- the actual comments in the post -->
                        <div class="comments">
                            <?php
                            while ($rowComment = mysqli_fetch_array($resultComment)) {
                            ?>
                                <div class="d-flex flex-row mb-2"> <img src="images/propic.jpg" width="40" class="rounded-image">
                                    <div class="d-flex flex-column ml-2" style="margin-left: 10px;"> 
                                    <h4><span class="name"><?php echo $rowComment['username'] ?></span></h4> 
                                    <p class="card-text" style="white-space: pre-line;"><?php echo $rowComment['comment'] ?></p>
                                    
                                </div>
                                <div class="d-flex flex-row mt-2 mx-3 ellipsis justify-content-end"> 
                                    <small class="mr-2"><?php echo $rowComment['date'] ?></small>  </div> 
                                </div>
                            <?php

                            }
                            ?>
                        </div>


                        <!-- textbox and button to add a new comment in the post -->
                        <form action="" method="POST">
                            <div class="comment-input" style="margin-top: 10px;">
                                <div class="row">
                                    <div class="col-11">
                                        <textarea name="commentText" class="form-control" style="height: 38px;" placeholder="Comment"></textarea>
                                    </div>
                                    <div class="col-1">
                                        <button type="submit" class="btn btn-primary" name="commentButton">send</button>
                                    </div>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
    $(".like_btn").click(function(){
        
        var post_id = $(this).attr("title");
        if($(this).hasClass("far"))
        {
            $(this).removeClass("far");
            $(this).addClass("fa");
           
           
           
        }
        else{
            $(this).removeClass("fa");
            $(this).addClass("far");
            
        }
        $.post("likeUnlike.php",{data:post_id,check:'1'});
        
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-U1DAWAznBHeqEIlVSCgzq+c9gqGAJn5c/t99JyeKa9xxaYpSvHU5awsuZVVFIhvj" crossorigin="anonymous"></script>
</body>

</html>

<?php
include "footer.php";
?>