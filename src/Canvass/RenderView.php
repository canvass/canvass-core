<?php

namespace Canvass;

use Canvass\Contract\View;
use Canvass\Exception\FileNotFoundException;

class RenderView implements View
{
    /** @var string */
    private $folder_path;

    public function __construct(string $folder_path)
    {
        $this->folder_path = rtrim($folder_path, '/');
    }

    public function render($data = [], string $type = null): string
    {
        $file = $type ?? $data['html_type'];

        $path = "{$this->folder_path}/{$file}/{$file}.php";

        if (! file_exists($path)) {
            throw new FileNotFoundException(
                "Could not find file {$file}.php in views."
            );
        }

        $data['renderer'] = $this;

        $level = ob_get_level();
        try {
            $this->display($path, $data);
        } catch (\Throwable $e) {
            while (ob_get_level() > $level) {
                ob_end_clean();
            }

            throw $e;
        }

        return ob_get_clean();
    }

    private function display($path, $data)
    {
        extract($data, EXTR_OVERWRITE);

        include $path;
    }
}
