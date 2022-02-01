<?php

require 'vendor/autoload.php';


class AppController
{
    protected function render(string $template = null, array $variables = [])
    {
        $templatePath = 'public/views/' . $template . '.php';
        $output = 'File not found';

        if (file_exists($templatePath)) {
            extract($variables);

            ob_start();
            include $templatePath;
            $output = ob_get_clean();
        }
        print $output;
    }

    protected function getContentType(): string
    {
        return isset($_SERVER["CONTENT_TYPE"]) ? trim($_SERVER["CONTENT_TYPE"]) : '';
    }

    protected function checkAuth(): void
    {
        session_start();
        if (!isset($_SESSION['userID'])) {
            $this->redirect("login");
        }
    }

    protected function redirect($path): void
    {
        $url = "http://$_SERVER[HTTP_HOST]";
        header("Location: {$url}/{$path}");
    }
}