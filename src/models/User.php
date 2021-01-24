<?php

namespace app\models;

require_once __DIR__ . '/../../vendor/autoload.php';

use app\Database;

// php-imagick extension is required
use Imagine\Imagick\Imagine;
use Imagine\Image\Box;

class User
{
    public int $id;
    public string $username;
    public string $email;
    public string $picture_folder;
    private string $password;

    const SUCCESS = 0;
    const ERROR_NOT_HANDLED = 1;
    const ERROR_NOT_FOUND = 2;
    const ERROR_WRONG_PASSWORD = 3;
    const ERROR_DATA_REQUIRED = 4;
    const USERNAME_ALREADY_TAKEN = 5;
    const EMAIL_ALREADY_TAKEN = 6;
    const ERROR_ALREADY_SAVED = 7;

    public function __construct(array $user = null)
    {
        if ($user) {
            // Setting all values
            foreach ($user as $key => $value) {
                if (property_exists($this, $key) && $value) {
                    $this->$key = $value;
                }
            }
        }
    }

    public function getProfilePic(string $filename)
    {
        $pathToPublic = $GLOBALS['pathToPublic'];

        // Checking if folder and file exist
        if (
            isset($this->picture_folder)  &&
            file_exists($pathToPublic . $this->picture_folder)
        ) {
            $path = $this->picture_folder . $filename;

            // Checking if image file exists
            if (file_exists($pathToPublic . $path)) {
                return $path;
            }
        }

        // If it has not returned yet
        return '/images/profile/default/' . $filename;
    }

    public function verifyPassword(string $password)
    {
        return password_verify($password, $this->password);
    }

    public function save()
    {
        // If user has an id, it is already saved in db
        if (isset($this->id)) {
            return ['code' => self::ERROR_ALREADY_SAVED];
        }

        if (
            !isset($this->username) ||
            !isset($this->email) ||
            !isset($this->password)
        ) {
            return ['code' => self::ERROR_DATA_REQUIRED];
        }

        // Let's see if username or email is already taken

        // Username first
        $user = self::byUsername($this->username);

        if ($user) {
            return [
                'code' => User::USERNAME_ALREADY_TAKEN
            ];
        }

        // Email now
        $user = self::byEmail($this->email);

        if ($user) {
            return [
                'code' => User::EMAIL_ALREADY_TAKEN
            ];
        }

        // Encrypting password
        $this->password = password_hash($this->password, PASSWORD_DEFAULT);

        $statement = Database::$pdo->prepare('
                                INSERT INTO user(username, email, password, create_date)
                                VALUES(:username, :email, :password, :create_date)
                                ');
        $statement->bindValue(':username', $this->username);
        $statement->bindValue(':email', $this->email);
        $statement->bindValue(':password', $this->password);
        $statement->bindValue(':create_date', date('Y-m-d H:i:s'));

        if ($statement->execute()) {

            // Getting last inserted (singed up) user
            $this->id = Database::$pdo->lastInsertId();

            return [
                'code' => User::SUCCESS
            ];
        }
    }

    public function setProfilePic(string $imagePath)
    {
        $pathToPublic = $GLOBALS['pathToPublic'];

        // Getting a random and unique filename
        $uniqueFile = tempnam($pathToPublic . '/images/profile/', '');
        unlink($uniqueFile);

        // Converting the above filname to a dirname
        $dirname = '/images/profile/' . basename($uniqueFile) . '/';

        $imagine = new Imagine();
        $image = $imagine->open($imagePath);

        if (mkdir($pathToPublic . $dirname)) {

            // Deleting current folder if any
            if ($this->picture_folder) {
                // Getting all file paths inside the folder
                $files = glob($pathToPublic . $this->picture_folder . '*');

                if ($files) {
                    foreach ($files as $file) {
                        unlink($file);
                    }
                }

                rmdir($pathToPublic . $this->picture_folder);
            }

            $small = $image->thumbnail(new Box(40, 40));
            $small->save($pathToPublic . $dirname . '40x40.jpg');

            $normal = $image->thumbnail(new Box(300, 300));
            $normal->save($pathToPublic . $dirname . '300x300.jpg');

            $statement = Database::$pdo->prepare('UPDATE user SET picture_folder = :picture_folder WHERE id = :id');
            $statement->bindValue(':picture_folder', $dirname);
            $statement->bindValue(':id', $this->id);

            if ($statement->execute()) {
                $this->picture_folder = $dirname;

                return [
                    'code' => User::SUCCESS
                ];
            }
        }

        return [
            'code' => User::ERROR_NOT_HANDLED
        ];
    }

    public static function byUsername(string $username)
    {
        $statement = Database::$pdo->prepare('
                                SELECT * FROM user 
                                WHERE username = :username
                                ');
        $statement->bindValue(':username', $username);
        $statement->execute();

        $user = $statement->fetch(\PDO::FETCH_ASSOC);

        if ($user) {
            return new User($user);
        } else {
            return null;
        }
    }

    public static function byEmail(string $email)
    {
        $statement = Database::$pdo->prepare('
                                SELECT * FROM user 
                                WHERE email = :email
                                ');
        $statement->bindValue(':email', $email);
        $statement->execute();

        $user = $statement->fetch(\PDO::FETCH_ASSOC);

        if ($user) {
            return new User($user);
        } else {
            return null;
        }
    }

    public static function byId(int $id)
    {
        $statement = Database::$pdo->prepare('
                                SELECT * FROM user 
                                WHERE id = :id
                                ');
        $statement->bindValue(':id', $id);
        $statement->execute();

        $user = $statement->fetch(\PDO::FETCH_ASSOC);

        if ($user) {
            return new User($user);
        } else {
            return null;
        }
    }

    public static function isLoggedIn()
    {
        if (isset($_SESSION['user']) && $_SESSION['user'] instanceof User) {
            return true;
        } else {
            return false;
        }
    }
}
