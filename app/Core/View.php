<?php
/**
 * Renderer view.
 */
class View
{
    /** Render view ke dalam layout */
    public static function render($view, $data = array(), $layout = 'layouts/main')
    {
        $data['_view'] = $view;
        extract($data);

        ob_start();
        require BASE_PATH . '/app/Views/' . $view . '.php';
        $content = ob_get_clean();

        if ($layout === null) {
            echo $content;
            self::clearTemp();
            return;
        }

        require BASE_PATH . '/app/Views/' . $layout . '.php';
        self::clearTemp();
    }

    /** Bersihkan data sementara (old input & error) setelah render */
    protected static function clearTemp()
    {
        Session::remove('_old');
        Session::remove('_errors');
    }
}
