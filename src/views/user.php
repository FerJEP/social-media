<?php

use app\models\Post;
?>
<div class="user page">
    <?php if (isset($user)) : ?>
        <div class="user-hero">
            <div class="user-pic normal user-hero-pic">
                <img src="<?php echo $user->getProfilePic('300x300.jpg') ?>">
            </div>
            <h1 class="user-hero-username">
                <?php echo $user->username ?>
            </h1>
        </div>
        <div class="user-posts">
            <?php
            $posts = Post::getByUserId($user->id);

            if ($posts && !empty($posts)) {

                foreach ($posts as $post) {
                    include __DIR__ . '/partials/post.php';
                }
            } else {
            ?>
                <div class="user-posts-none">This user has no posts :(</div>
            <?php
            }
            ?>
        </div>
    <?php endif ?>
</div>
