<?php
/**
 * Router sederhana berbasis pola URL.
 * Pola mendukung parameter dinamis, contoh: 'pendaftaran/{id}'.
 */
class Router
{
    protected $routes = array();

    public function get($pattern, $action, $roles = array())
    {
        $this->add('GET', $pattern, $action, $roles);
    }

    public function post($pattern, $action, $roles = array())
    {
        $this->add('POST', $pattern, $action, $roles);
    }

    public function any($pattern, $action, $roles = array())
    {
        $this->add('GET', $pattern, $action, $roles);
        $this->add('POST', $pattern, $action, $roles);
    }

    public function add($method, $pattern, $action, $roles = array())
    {
        $this->routes[] = array(
            'method'  => $method,
            'pattern' => $pattern,
            'action'  => $action,
            'roles'   => $roles,
        );
    }

    /** Jalankan routing */
    public function dispatch()
    {
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $uri = str_replace('\\', '/', $uri);

        if (BASE_URL !== '' && strpos($uri, BASE_URL) === 0) {
            $uri = substr($uri, strlen(BASE_URL));
        }
        $uri = trim($uri, '/');
        $method = method();

        foreach ($this->routes as $route) {
            if ($route['method'] !== $method) {
                continue;
            }
            $params = $this->match($route['pattern'], $uri);
            if ($params === false) {
                continue;
            }

            // Periksa hak akses
            if (!empty($route['roles'])) {
                if (!Auth::check()) {
                    Session::flash('error', 'Silakan login terlebih dahulu.');
                    redirect('login');
                }
                if (!Auth::hasRole($route['roles'])) {
                    $this->forbidden();
                }
            }

            return $this->call($route['action'], $params);
        }

        $this->notFound();
    }

    /** Cocokkan pola dengan URI; kembalikan array parameter atau false */
    protected function match($pattern, $uri)
    {
        $pattern = trim($pattern, '/');
        $regex = preg_replace('#\{[a-zA-Z0-9_]+\}#', '([^/]+)', $pattern);
        $regex = '#^' . $regex . '$#u';
        if (preg_match($regex, $uri, $m)) {
            array_shift($m);
            return $m;
        }
        return false;
    }

    /** Panggil controller@method */
    protected function call($action, $params)
    {
        $parts = explode('@', $action);
        $controller = $parts[0];
        $meth = isset($parts[1]) ? $parts[1] : 'index';

        if (!class_exists($controller)) {
            $this->notFound();
        }

        $obj = new $controller();
        if (!method_exists($obj, $meth)) {
            $this->notFound();
        }

        call_user_func_array(array($obj, $meth), $params);
    }

    protected function notFound()
    {
        http_response_code(404);
        echo '<h1>404 - Halaman Tidak Ditemukan</h1>';
        exit;
    }

    protected function forbidden()
    {
        http_response_code(403);
        echo '<h1>403 - Akses Ditolak</h1>';
        exit;
    }
}
