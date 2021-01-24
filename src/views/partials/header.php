<?php
require_once __DIR__ . '/../../../vendor/autoload.php';

use app\Functions;
use app\models\User;

?>
<header class="app-header">
    <div class="header-body center">
        <?php
        if (User::isLoggedIn()) :

            $userLink = '/user/' . $_SESSION['user']->username;
        ?>
            <div class="header-profile header-hover">
                <div class="user-pic small">
                    <a href="<?php echo $userLink ?>">
                        <img src="<?php echo $_SESSION['user']->getProfilePic('40x40.jpg') ?>" alt="">
                    </a>
                </div>
                <a href="<?php echo $userLink ?>" class="header-profile-username">
                    <?php echo $_SESSION['user']->username ?>
                </a>
            </div>
            <div class="header-home header-icon">
                <a href="/">
                    <?php Functions::getIcon('ant-design:home-filled') ?>
                </a>
            </div>
            <ul>
                <li class="header-settings header-icon">
                    <a href="/settings">
                        <?php Functions::getIcon('bytesize:settings') ?>
                    </a>
                </li>
                <li class="header-settings header-icon">
                    <a href="/logout">
                        <?php Functions::getIcon('ant-design:logout-outlined') ?>
                    </a>
                </li>
            </ul>
        <?php else : ?>
            <div>
                Social Media
            </div>
        <?php endif ?>
    </div>
</header>
