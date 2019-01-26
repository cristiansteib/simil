<?php
include_once(CODE_ROOT . "/core/errors/BadControllerName.php");
include_once('MatcherRegex.php');
include_once('MatcherPath.php');
include_once('Path.php');

class Path {

    private $classAndMethod;
    /**@var Matcher**/
    private $matcherInstance;

    function __construct($matcher, $classAndMethod) {
        $this->classAndMethod = $classAndMethod;
        $this->matcherInstance = $matcher;
    }

    /**
     * @param $regex
     * @param $function
     * @return Path
     */
    static function path_re($regex, $function) {
        $matcher = new MatcherRegex($regex);
        return new Path($matcher, $function);
    }

    /**
     * @param $path
     * @param $function
     * @return Path
     */
    static function path($path, $function) {
        $matcher = new MatcherPath($path);
        return new Path($matcher, $function);
    }

    private function getClassName(){
        $regex = '/(^[A-Z]{1}.+)(Controller)/';
        $ok = preg_match($regex, $this->classAndMethod, $matches, PREG_OFFSET_CAPTURE);
        if ($ok) {
            return $matches[0][0];
        }
        return null;
    }

    private function needToCallInstanceMethod(){
        $regex = '/(^[A-Z]{1}.+)(Controller)->/';
        $ok = preg_match($regex, $this->classAndMethod, $matches, PREG_OFFSET_CAPTURE);
        if ($ok) {
            return true;
        }
        return false;
    }

    private function getMethodName(){
        $regex = '/^[A-Z]{1}.+Controller(->|::)([a-zA-Z]+)/';
        $ok = preg_match($regex, $this->classAndMethod, $matches, PREG_OFFSET_CAPTURE);
        if ($ok) {
            return $matches[2][0];
        }
        return null;
    }

    /**
     * @throws BadControllerNameException
     */
    private function get_required_path_of_controller(){
        $class_name = $this->getClassName();
        if (!$class_name) {
            throw new BadControllerNameException("Controller name error : {{ " . $this->classAndMethod . " }}", "1");
        }
        return  (CODE_ROOT . '/controllers/' . $class_name . '.php');
    }


    /**
     * @param $url
     * @return mixed
     * @throws BadControllerNameException
     */
    function exec($url_request){
        require_once ($this->get_required_path_of_controller());
        if ($this->needToCallInstanceMethod()){
            $clazz = $this->getClassName();
            $method = $this->getMethodName();
            $obj = new $clazz;
            return call_user_func(array($obj, $method),  $this->matcherInstance->getParameters($url_request));
        } else {
            return call_user_func($this->classAndMethod, $this->matcherInstance->getParameters($url_request));
        }
    }

    function getUrlPattern(){
        return $this->matcherInstance->getUrl();
    }

    function isThis($url_request){
        return $this->matcherInstance->isThis($url_request);
    }
}
