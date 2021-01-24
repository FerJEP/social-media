<?php

use app\Messages;

require_once __DIR__ . '/../../../vendor/autoload.php';
?>

<div class="app-messages" id="app-messages">
    <?php
    if (isset($messages) && $messages instanceof Messages) :
        $classType = [];
        $classType[Messages::MESSAGE_ERROR] = 'error';
        $classType[Messages::MESSAGE_SUCCESS] = 'success';


        foreach ($messages->getAll() as $message) :
    ?>
            <div class="app-message <?php echo $classType[$message['type']] ?>">
                <span><?php echo $message['body'] ?></span>
                <button class="app-message-close">&#10005;</button>
            </div>
        <?php
        endforeach
        ?>
        <script src="js/messages.js" charset="utf-8"></script>
    <?php
    endif;
    ?>
</div>
