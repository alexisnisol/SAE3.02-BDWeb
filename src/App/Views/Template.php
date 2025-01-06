<?php
declare(strict_types=1);

namespace Views;

final class Template {
    private string $path;

    private string $layout;

    private string $content;

    public function __construct(string $path) {
        $this->path = $path;
    }

    public function getPath():string {
        return $this->path;
    }

    public function getLayout():string {
        return $this->layout;
    }

    public function getContent():string {
        return $this->content;
    }

    public function setLayout(string $layout):self {
        $this->layout = $layout;
        return $this;
    }

    public function setContent(string $content):self {
        $this->content = $content;
        return $this;
    }

    public function compile():string {
        ob_start();
        require sprintf('%s/%s.php', $this->getPath(), $this->getLayout());

        return ob_get_clean();
        
    }
}
?>