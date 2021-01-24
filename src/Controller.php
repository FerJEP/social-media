<?php


namespace app;

use app\models\Post;
use app\models\User;

require_once __DIR__ . '/../vendor/autoload.php';

class Controller
{
    public static function login()
    {
        if (User::isLoggedIn()) {
            Router::redirect('/');
        }

        $messages = new Messages();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $login = $_POST['login'] ?? '';
            $password = $_POST['password'] ?? '';

            $user = null;
            // Checking if its an email (not really)
            if (strpos($login, '@')) {
                $user = User::byEmail($login);
            } else {
                $user = User::byUsername($login);
            }

            // If user found
            if ($user) {
                // if password match
                if ($user->verifyPassword($password)) {
                    $_SESSION['user'] = $user;
                    Router::redirect('/');
                } else {
                    $messages->setMessage(
                        Messages::MESSAGE_ERROR,
                        'Wrong password'
                    );
                }
            } else {
                $messages->setMessage(
                    Messages::MESSAGE_ERROR,
                    'User not found'
                );
            }
        }

        self::render([
            'view' => 'login.php',
            'messages' => $messages
        ]);
    }

    public static function signup()
    {
        if (User::isLoggedIn()) {
            Router::redirect('/');
        }

        $messages = new Messages();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? null;
            $email = $_POST['email'] ?? null;
            $password = $_POST['password'] ?? null;
            $repeatPass = $_POST['repeatPass'] ?? null;

            if (!$username) $messages->setMessage(Messages::MESSAGE_ERROR, 'Provide a username');
            if (!$email) $messages->setMessage(Messages::MESSAGE_ERROR, 'Provide a email');
            if (!$password) $messages->setMessage(Messages::MESSAGE_ERROR, 'Provide a password');
            if ($password !== $repeatPass) $messages->setMessage(Messages::MESSAGE_ERROR, 'Password and Repeat password must be equal');


            if (empty($messages->getAll())) {

                $user = new User([
                    'username' => $username,
                    'email' => $email,
                    'password' => $password
                ]);


                $response = $user->save();

                switch ($response['code']) {

                    case User::SUCCESS:
                        $_SESSION['user'] = $user;
                        Router::redirect('/');
                        break;

                    case User::ERROR_DATA_REQUIRED:
                        $messages->setMessage(
                            Messages::MESSAGE_ERROR,
                            'Some fields are required'
                        );
                        break;



                    case User::USERNAME_ALREADY_TAKEN:
                        $messages->setMessage(
                            Messages::MESSAGE_ERROR,
                            'Username already taken'
                        );
                        break;

                    case User::EMAIL_ALREADY_TAKEN:
                        $messages->setMessage(
                            Messages::MESSAGE_ERROR,
                            'Email already taken'
                        );
                        break;

                    default:
                        $messages->setMessage(
                            Messages::MESSAGE_ERROR,
                            'Something went wrong'
                        );
                }
            }
        }

        self::render([
            'view' => 'signup.php',
            'messages' => $messages
        ]);
    }

    public static function logout()
    {
        if (User::isLoggedIn()) {
            $_SESSION['user'] = null;
        }

        Router::redirect('/login');
    }

    public static function create_post()
    {
        if (!User::isLoggedIn()) {
            Router::redirect('/');
        }

        $body = $_POST['body'] ?? null;

        if ($body) {
            $post = new Post();
            $post->user_id = $_SESSION['user']->id;
            $post->body = trim($body);
            $post->save();
        }

        Router::redirect('/');
    }

    public static function home()
    {

        if (!User::isLoggedIn()) {
            Router::redirect('/login');
        }

        self::render([
            'view' => 'home.php',
            'posts' => Post::getAll()
        ]);
    }

    public static function user(array $route)
    {
        if (!User::isLoggedIn()) {
            Router::redirect('/login');
        }

        $username = $route['matches']['username'] ?? null;

        if (!$username) {
            Router::redirect('/');
        }

        $user = null;

        if ($username === $_SESSION['user']->username) {
            $user = $_SESSION['user'];
        } else {
            $user = User::byUsername($username);
        }

        if (!$user) {
        }

        self::render([
            'view' => 'user.php',
            'username' => $username,
            'user' => $user
        ]);
    }

    public static function settings()
    {
        if (!User::isLoggedIn()) {
            Router::redirect('/login');
        }

        $messages = new Messages();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            // All inputs goes here
            $profileImg = $_FILES['profile-img'] ?? null;

            // All input validation goes here

            // Profile Picture:  Up to 2 MB
            if ($profileImg && $profileImg['size'] > 16777216) {
                $messages->setMessage(Messages::MESSAGE_ERROR, 'Image must be up to 2MB');
            }

            // If no errors
            if (empty($messages->getAll())) {

                // Handling profile picture
                if ($profileImg) {
                    $response = $_SESSION['user']->setProfilePic($profileImg['tmp_name']);

                    switch ($response['code']) {

                        case User::SUCCESS:
                            $messages->setMessage(Messages::MESSAGE_SUCCESS, 'Profile picture changed');
                            break;
                        default:
                            $messages->setMessage(Messages::MESSAGE_ERROR, 'Something went wrong with profile picture');
                    }
                }
            }
        }

        self::render([
            'view' => 'settings.php',
            'messages' => $messages
        ]);
    }

    private static function render(array $globals)
    {
        foreach ($globals as $key => $value) {
            $$key = $value;
        }

        require_once __DIR__ . '/views/_layout.php';
    }
}
