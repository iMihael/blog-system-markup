<?php
    require "functions.php";
    if(!isset($_SESSION['user'])) {
        header("Location: index.php");
    }

    if(isset($_POST['title']) && isset($_POST['content'])) {
        if(addPost(
            $_SESSION['userId'], $_POST['title'],
            $_POST['content'],
            ((isset($_FILES['image']) && $_FILES['image']['error'] == 0) ? $_FILES['image']['tmp_name'] : false),
            ((isset($_FILES['image']) && $_FILES['image']['error'] == 0) ? $_FILES['image']['name'] : false))
        ) {
            header("Location: index.php");
        }
    }


    require "header.php";
?>

<div class="container">

    <div class="col-lg-8 col-lg-offset-2">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h2>Add Post</h2>
            </div>
            <div class="panel-body">
                <form method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label>Title</label>
                        <input name="title" type="text" class="form-control" />
                    </div>
                    <div class="form-group">
                        <label>Content</label>
                        <textarea name="content" class="form-control" rows="5"></textarea>
                    </div>
                    <div class="form-group">
                        <label>Picture (optional)</label>
                        <input name="image" type="file" class="form-control" />
                    </div>
                    <div class="form-group">
                        <input type="submit" class="btn btn-primary form-control" />
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>


<?php
    require "footer.php";
?>