<?php
    require "functions.php";
    require "header.php";


?>

<div class="container">

    <?php
        if(isset($_GET['user-id']) && isset($_GET['search'])) {
            $posts = searchByUser(
                $_GET['user-id'],
                $_GET['search'],
                (isset($_GET['page']) && $_GET['page'] > 1) ? $_GET['page'] : 1
            );

            foreach($posts as $post) {
                ?>

                <div class="post">
                    <h1 class="post-header"><?php echo $post['title']; ?></h1>
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

        } else {
            header("Location: index.php");
        }
    ?>





    <nav>
        <ul class="pagination">
            <li>
                <a href="#" aria-label="Previous">
                    <span aria-hidden="true">&laquo;</span>
                </a>
            </li>
<!--            <li><a href="#">1</a></li>-->
<!--            <li><a href="#">2</a></li>-->
<!--            <li><a href="#">3</a></li>-->
<!--            <li><a href="#">4</a></li>-->
<!--            <li><a href="#">5</a></li>-->
            <?php
                $perPage = 2;
                $count = getPostsCountSearch($_GET['user-id'], $_GET['search']);
                $pages = ceil($count / $perPage);

                for($i=1;$i<=$pages;$i++) {
                    echo "<li><a href=\"search.php?user-id={$_GET['user-id']}&search={$_GET['search']}&page=$i\">$i</a></li>";
                }


            ?>

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