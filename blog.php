<?php
    require "functions.php";
    require "header.php";
?>

<div class="container">

    <?php
        if(!isset($_GET['user-id'])) {
            header("Location: index.php");
        } else {
            $posts = getPostsByUserId($_GET['user-id'],
                (isset($_GET['page']) && $_GET['page'] > 1) ? $_GET['page'] : 1);

            foreach($posts as $post) {
                ?>

                <div class="post">
                    <h1 class="post-header"><?php echo $post['title'] ?></h1>
                    <?php if($post['image']) { ?>
                    <hr />
                    <img src="img/<?php echo $post['image']; ?>" />
                    <?php } ?>
                    <hr />
                    <p class="post-content">
                        <?php echo $post['body']; ?>
                    </p>
                    <hr />
                    <p class="post-when-by">
                        <?php echo $post['createdAt']; ?>
                    </p>
                </div>
    <?php


            }

        }

    ?>


    <nav>
        <ul class="pagination">
            <li>
                <a href="#" aria-label="Previous">
                    <span aria-hidden="true">&laquo;</span>
                </a>
            </li>

            <?php
                $postCount = getPostsCount($_GET['user-id']);
                $pageCount = ceil($postCount / 2);

            for($i=0;$i<$pageCount;$i++) {

            ?>

            <li><a href="blog.php?user-id=<?php
                echo $_GET['user-id'] . "&page=" . ($i + 1)
            ?>"><?php echo $i + 1; ?></a></li>

            <?php } ?>
            <li>
                <a href="#" aria-label="Next">
                    <span aria-hidden="true">&raquo;</span>
                </a>
            </li>
        </ul>
    </nav>

</div>
</body>
</html>