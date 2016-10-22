<?php

namespace Sensorario\WheelEngine;

class Engine
{
    private $wheelContent;

    private $wheelGlobalVars;

    private $template;

    private $baseWheelFolder;

    private $model;

    private function ensureTemplateExists()
    {
        if (!file_exists($this->templatePath)) {
            throw new \RuntimeException(
                'Template ' . $this->template . ' not exists!'
            );
        }
    }

    public function setWheelFolder($folder)
    {
        $this->baseWheelFolder = $folder;
    }

    private function buildPHPCode($template)
    {
        $this->template = $template;

        $virtualPath = $this->baseWheelFolder
            . $this->template
            . '.wheel.html';

        $this->templatePath = realpath($virtualPath);
        $this->ensureTemplateExists();

        $this->wheelContent = file_get_contents($this->templatePath);

        $wheelToPHPMap = [
            '/{{([ ]{0,})([\w]{1,})([ ]{0,})}}/i' => '<?php echo $${2}; ?' . '>',
        ];

        foreach ($wheelToPHPMap as $wheelPattern => $phpReplacement) {
            preg_match_all($wheelPattern, $this->wheelContent, $matchParts);

            foreach ($matchParts[2] as $variable) {
                if (!isset($this->model[$variable])) {
                    throw new \RuntimeException(
                        '$'.$variable.' is not defined!'
                    );
                }
            }

            foreach ($matchParts[0] as $match) {
                $compiled = preg_replace(
                    $wheelPattern,
                    $phpReplacement,
                    $match
                );

                $this->wheelContent = str_replace(
                    $match,
                    $compiled,
                    $this->wheelContent
                );
            }
        }
    }

    private function buildGlobalVars($params)
    {
        $this->wheelGlobalVars = '';
        foreach ($params as $name => $value) {
            $this->wheelGlobalVars .= '<?php '
                . '$' . $name . ' = "' . $value . '"; '
                . '?>';
        }
    }

    public function render($view, $model)
    {
        $this->model = $model;
        $this->buildGlobalVars($model);
        $this->buildPHPCode($view);

        ob_start();

        eval(
            '?>'
            . $this->wheelGlobalVars
            . $this->wheelContent
        );

        return ob_get_clean();
    }
}
