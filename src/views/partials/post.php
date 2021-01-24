<?php
require_once __DIR__ . '/../../../vendor/autoload.php';

$userLink = '/user/' . $post->user->username;
?>

<div class="post">
    <div class="post-header">
        <div class="user-pic small post-pic">
            <a href="<?php echo $userLink ?>">
                <img src="<?php echo $post->user->getProfilePic('40x40.jpg') ?>" alt="">
            </a>
        </div>
        <div class="post-username">
            <a href="<?php echo $userLink ?>">
                <?php echo $post->user->username ?>
            </a>
        </div>
        <div class="post-date">
            <?php echo $post->getDate('M d, Y') ?>
        </div>
    </div>
    <div class="post-body">
        <?php echo $post->body ?>
    </div>
</div>
