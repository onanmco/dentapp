<?php

namespace core;

use app\utility\CommonValidator;
use Exception;

class Router
{
    /**
     * Routing table
     * 
     * @var array $routes
     */
    private $routes = [];

    /**
     * Current arguments
     * 
     * @var array $args
     */
    private $args = [];

    /**
     * Pre-defined regexp pattern table
     * 
     * @var array $patterns
     */
    private $patterns = [
        ':default' => '([a-z-]+)',
        ':letter' => '([a-zA-Z]+)',
        ':number' => '(\d+)',
        ':any' => '([^/]+)',
        ':all' => '(.*)',
        ':string' => '(\w+)',
        ':slug' => '([\w\-_]+)',
        ':uuid' => '([0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12})',
        ':date' => '([0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1]))',
    ];

    /**
     * Get all the routes from the routing table.
     * 
     * @return array $this->routes
     */
    public function getRoutes()
    {
        return $this->routes;
    }

    /**
     * Get all the arguments of current route (e.g. controller, action, etc...).
     * 
     * @return array $this->args
     */
    public function getArgs()
    {
        return $this->args;
    }

    /**
     * Get all the patterns from the patterns table.
     * 
     * @return array $this->patterns
     */
    public function getPatterns()
    {
        return $this->patterns;
    }

    /**
     * Removes hyphens from the passed uri and converts it to studly caps format (e.g. default-controller => DefaultController).
     * 
     * @param string $uri
     * 
     * @return string
     */
    private function toStudlyCaps($uri)
    {
        if (!empty($uri)) {
            return str_replace(' ', '', ucwords(str_replace('-', ' ',str_replace(' ', '', $uri))));
        }
        return $uri;        
    }

    /**
     * Removes hyphens from the passed uri and converts it to camel case format (e.g. index-action => indexAction).
     * 
     * @param string $uri
     * 
     * @return string
     */
    private function toCamelCase($uri)
    {
        if (!empty($uri)) {
            return lcfirst($this->toStudlyCaps($uri));
        }
    }

    /**
     * Convert the named group with custom pattern (e.g. <controller:letter>) to proper regexp
     * 
     * @param array $matches
     *
     * @return string
     * 
     * @throws Exception
     */
    private function convertNamedGroupWithPattern($matches)
    {
        $pattern = explode(':', trim($matches[2], ':'));
        if (count($pattern) > 1) {
            throw new Exception('Variable route element: \'' . $matches[0] . '\' cannot have more than one pattern.', 500);
        }
        $pattern = ':' . $pattern[0];
        if ($pattern == ':') {
            $default_pattern = '([a-z-]+)';
            if (isset($this->patterns[':default'])) {
                $default_pattern = $this->patterns[':default'];
            }
            return '(?P<' . $matches[1] . '>' . $default_pattern . ')';
        }
        if (isset($this->patterns[$pattern])) {
            return '(?P<' . $matches[1] . '>' . $this->patterns[$pattern] . ')';
        } else {
            $pattern = ltrim($pattern, ':');
            if (!(CommonValidator::isRegexp($pattern))) {
                throw new Exception('Custom pattern: \'' . $pattern . '\' is not a valid regexp in route element: \'' . $matches[0] . '\'.', 500);
            }
            return '(?P<' . $matches[1] . '>' . $pattern . ')';
        }
    }

    /**
     * Convert the named groups (e.g. <controller>) to proper regexp
     * 
     * @param array $matches
     * 
     * @return string
     */
    private function convertNamedGroup($matches)
    {
        $default_pattern = '([a-z-]+)';
        if (isset($this->patterns[':default'])) {
            $default_pattern = $this->patterns[':default'];
        }
        return '(?P<' . $matches[1] . '>' . $default_pattern . ')';
    }

    /**
     * Add a new route to the routing table.
     * 
     * @param string $route - Pattern for the URI
     * 
     * @param array $args - Arguments for the particular route (optional)
     * 
     * @return void
     * 
     * @throws Exception
     */
    public function add($route, $args = [])
    {
        $route = explode('/', $route);
        $route = preg_replace('/:+/', ':', $route);
        $route = preg_replace('/\s/', '', $route);
        foreach ($route as $key => $value) {
            if (preg_match('/^<([a-z-]+)(:[^>]*)>$/', $value, $matches)) {
                $route[$key] = $this->convertNamedGroupWithPattern($matches);
                continue;
            } else if (preg_match('/^<([a-z-]+)>$/', $value, $matches)) {
                $route[$key] = $this->convertNamedGroup($matches);
                continue;
            } else if (preg_match('/^\w*$/', $value)) {
                continue;
            } else {
                throw new Exception('Illegal route element: \''. $value . '\'. Route cannot be added.', 500);
            }
        }
        $temp = implode('\\/', $route);
        $temp = '/^' . $temp . '$/i';
        $this->routes[$temp] = $args;
    }

    /**
     * Maps the arguments of the current route from the routing table if it's matched one of the routes
     * 
     * @return boolean true if matches - false otherwise
     */
    public function match($uri)
    {
        foreach ($this->routes as $route => $args) {
            if (preg_match($route, $uri, $matches)) {
                foreach ($matches as $key => $match) {
                    if (is_string($key) && !isset($args[$key])) {
                        $args[$key] = $match;
                    }
                }
                $this->args = $args;
                return true;
            }
        }
        return false;
    }

    /**
     * Get the namespace for the controller class of the current route.
     * 
     * @return string $namespace is app\controller\ by default if not a specific namespace definition found at $this->params
     */
    private function getNamespace()
    {
        $namespace = 'app\controller\\';
        if (isset($this->args['namespace'])) {
            $namespace .= $this->args['namespace'] . '\\';
        }
        return $namespace;
    }

    /**
     * Dispatch the arguments of the current route if it's matched with one of the routes at the routing table.
     * 
     * @param string $uri
     * 
     * @return void
     * 
     * @throws Exception
     */
    public function dispatch($uri)
    {
        if ($this->match($uri)) {
            $controller = $this->args['controller'];
            $controller = $this->toStudlyCaps($controller);
            $controller = $this->getNamespace() . $controller . 'Controller';
            if (class_exists($controller)) {
                $controller_instance = new $controller($this->args);
                $action = $this->args['action'];
                $action = $this->toCamelCase($action);
                if (preg_match('/action$/i', $action) == 0) {
                    $controller_instance->$action();
                } else {
                    throw new Exception("\"$action\" (in controller: \"$controller\") cannot be called directly. Please remove \"action\" suffix on the URI.", 401);
                }
            } else {
                throw new Exception("\"Controller: $controller\" cannot be found.", 404);
            }
        } else {
            throw new Exception("Page cannot be found", 404);
        }
    }

    public static function redirect($uri, $code = 302)
    {
        $codes = [301, 302, 303, 307, 308];
        if (!in_array($code, $codes)) {
            $code = 302;
        }
        $uri = '/' . $uri;
        $uri = preg_replace('/\/\/+/', '/', $uri);
        header('Location: http://' . $_SERVER['HTTP_HOST'] . $uri, true, $code);
        exit;
    }

    public static function redirectAfterPost($uri)
    {
        self::redirect($uri, 303);
    }

}