<?php

class ClientScript extends CClientScript
{
    /**
     * Inserts the scripts in the head section.
     * @param string $output the output to be inserted with scripts.
     */
    public function renderHead(&$output)
    {
        $cssFiles = array();
        foreach ($this->cssFiles as $url => $media)
            $cssFiles[$this->appendCacheBuster($url)] = $media;
        $this->cssFiles = $cssFiles;
        foreach ($this->scriptFiles as $position => $scripts)
        {
            foreach ($scripts as $index => $script)
                $this->scriptFiles[$position][$index] = $this->appendCacheBuster($script);
        }
        parent::renderHead($output);
    }

    /**
     * @param $url
     * @return string the url.
     */
    protected function resolveFilePath($url)
    {
        $baseUrl = Yii::app()->request->baseUrl;
        if (!empty($baseUrl) && strpos($url, $baseUrl) === false)
            return false; // not a local file
        $filePath = $_SERVER['DOCUMENT_ROOT'] . $url;
        return file_exists($filePath) ? $filePath : false;
    }

    /**
     * @param $url
     * @return string the url.
     */
    protected function appendCacheBuster($url)
    {
        if (($filePath = $this->resolveFilePath($url)) !== false)
        {
            $modified = filemtime($filePath);
            $url .= '?_=' . $modified;
        }
        return $url;
    }
}