<div class="home page">
    <?php
    include_once __DIR__ . '/partials/postForm.php';

    foreach ($posts as $post) {
        include __DIR__ . '/partials/post.php';
    }
    ?>
</div>
