<?php

require 'vendor/autoload.php';

use PhpOffice\PhpWord\TemplateProcessor;


class AppController {
    private $request;

    public function __construct()
    {
        $this->request = $_SERVER['REQUEST_METHOD'];
    }

    protected function isGet(): bool
    {
        return $this->request === 'GET';
    }

    protected function isPost(): bool
    {
        return $this->request === 'POST';
    }

    protected function render(string $template = null, array $variables = [])
    {
        $phpword = new \PhpOffice\PhpWord\TemplateProcessor('public/word/template.docx');

        $phpword->setValue('{name}','Santosh');
        $phpword->saveAs('public/word/edited.docx');

        $templatePath = 'public/views/'. $template.'.php';
        $output = 'File not found';

        if(file_exists($templatePath)){
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

    protected function redirectToLogin(): void
    {
        $url = "http://$_SERVER[HTTP_HOST]";
        header("Location: {$url}/login");
    }
}