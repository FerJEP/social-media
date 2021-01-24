<?php

namespace app;

// This router supports vairables and queries

class Router
{
    private static $getRoutes = [];
    private static $postRoutes = [];

    public static function redirect(string $path)
    {
        header("Location: $path");
        exit;
    }

    public static function get(string $url, callable $fn)
    {
        $route = self::parseRoute($url);

        self::$getRoutes[] = [
            "regex" => $route['regex'],
            "matches" => $route['matches'],
            "fn" => $fn
        ];
    }


    public static function post(string $url, callable $fn)
    {
        $route = self::parseRoute($url);

        self::$postRoutes[] = [
            "regex" => $route['regex'],
            "matches" => $route['matches'],
            "fn" => $fn
        ];
    }

    private static function parseRoute($url)
    {
        $macthes = [];

        // We check if there is any :variable
        preg_match_all("/:(\w+)/", $url, $macthes);

        // and replace it with (.*)
        $regex = preg_replace("/:(\w+)/", '(.*)', $url);

        // escape all / characters
        $regex = str_replace("/", "\/", $regex);

        // finally create the regex I'll use for matching the requested urls
        $regex = "/$regex/";

        return array(
            "regex" => $regex,
            "matches" => $macthes[1]
        );
    }

    public static function resolve()
    {
        $url = parse_url($_SERVER['REQUEST_URI']);
        $method = $_SERVER['REQUEST_METHOD'];

        // Selecting the routes depending on the request method
        $routes =  $method === 'GET' ? self::$getRoutes : self::$postRoutes;

        // fn is the route callback
        $fn = null;

        // queries will go here (if any)
        $query = [];

        if (isset($url['query'])) {
            parse_str($url['query'], $query);
        }

        // if the route has variables, they will be put in routeMatches
        $routeMatches = [];

        foreach ($routes as $route) {

            $regexMatches = [];
            preg_match($route['regex'], $url['path'], $regexMatches);

            // regexMatches[0] is the full match (if any)
            // regexMatches[+n] are the group matches (our custom variables, if any)

            // Checking if the full match is the requested url
            if (!empty($regexMatches) && $regexMatches[0] === $url['path']) {

                $fn = $route['fn'];

                // regexMatches lenght
                $nMatches = count($regexMatches);

                // Checking for variables
                if ($nMatches > 1) {

                    for ($i = 1; $i < $nMatches; $i++) {
                        // Sorting them in the same order
                        $key = $route['matches'][$i - 1];
                        $routeMatches[$key] = $regexMatches[$i];
                    }
                }
            }
        }


        // Finally, if any route was found, $fn will be the callback
        if ($fn) {

            // And I pass the data we colected as an assoc array
            $routeObj = array(
                "matches" => $routeMatches,
                "query" => $query
            );

            call_user_func($fn, $routeObj);
        } else {
            echo 'page not found';
        }
    }
}
