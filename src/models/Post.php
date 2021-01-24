<?php

namespace app\models;

use app\Database;

class Post
{
    const SUCCESS = 0;
    const ERROR_DATA_REQUIRED = 1;
    const ERROR_ALREADY_SAVED = 2;

    public int $id;
    public string $body;
    public int $user_id;
    public string $create_date;

    public User $user;

    public function __construct(array $post = null)
    {
        if ($post) {
            // Setting all values
            foreach ($post as $key => $value) {
                if (property_exists($this, $key)) {
                    $this->$key = $value;
                }
            }
        }
    }

    public function save()
    {
        // If this post has an id, it's because it is already saved
        if (isset($this->id)) {
            return [
                'code' => self::ERROR_ALREADY_SAVED
            ];
        }

        // If it has no body
        if (!$this->body) {
            return [
                'code' => self::ERROR_DATA_REQUIRED
            ];
        }

        if (!$this->user_id || !$this->body) {
            return [
                'code' => self::ERROR_DATA_REQUIRED
            ];
        }

        $statement = Database::$pdo->prepare('
                                    INSERT INTO post(user_id, body, create_date)
                                    VALUES(:user_id, :body, :create_date)
                                    ');
        $statement->bindValue(':user_id', $this->user_id);
        $statement->bindValue(':body', $this->body);
        $statement->bindValue(':create_date', date('Y-m-d H:i:s'));

        if ($statement->execute()) {
            return [
                'code' => self::SUCCESS
            ];
        }
    }

    public function getDate(string $format = null)
    {
        if (!$format) return $this->create_date;

        $timestamp = strtotime($this->create_date);
        return date($format, $timestamp);
    }

    public static function getAll()
    {
        $statement = Database::$pdo->prepare('
                                    SELECT * FROM post 
                                    ORDER BY post.create_date DESC
                                    ');
        $statement->execute();

        $posts = $statement->fetchAll(\PDO::FETCH_ASSOC);

        foreach ($posts as $i => $post) {
            $post = new Post($post);
            $post->user = User::byId($post->user_id);

            $posts[$i] = $post;
        }

        return $posts;
    }

    public static function getByUserId(int $user_id)
    {
        $statement = Database::$pdo->prepare('
                                    SELECT * FROM post
                                    WHERE user_id = :user_id 
                                    ORDER BY post.create_date DESC
                                    ');

        $statement->bindValue(':user_id', $user_id);
        $statement->execute();

        $posts = $statement->fetchAll(\PDO::FETCH_ASSOC);

        if (isset($posts)) {
            foreach ($posts as $i => $post) {
                $post = new Post($post);
                $post->user = User::byId($post->user_id);

                $posts[$i] = $post;
            }
        }

        return $posts;
    }
}
