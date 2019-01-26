<?php
include_once(CODE_ROOT . "/core/errors/NotFound404.php");
include_once(CODE_ROOT . "/core/Singleton.php");

class Dispatcher {
    private $urls;


    /**
     * @param $url_request
     * @return mixed
     * @throws NotFound404Exception
     * @throws BadControllerNameException
     */

    public function run($url_request) {
        foreach ($this->urls as $path) {
            /** @var Path $path */
            if($path->isThis($url_request)) {
                return $path->exec($url_request);
            }
        }
        if(DEBUG) {
            $this->show_available_urls();
        }
        throw new NotFound404Exception("Dispatcher don't found a resource that matching", 1);
    }

    public function show_available_urls() {
        echo "<html lang=\"en\"><h1>Not found - Available Urls</h1><h4>You are seeing this because DEBUG=true</h4><ul>";
        foreach ($this->urls as $path) {
            echo "<li>" . htmlspecialchars($path->getUrlPattern()) . "</li>\n";
        }
        echo "</ul></html>";
    }

    public function hasUrls() {
        return isset($this->urls);
    }

    public function setUrls($urls_array) {
        $this->urls = $urls_array;
    }

}
