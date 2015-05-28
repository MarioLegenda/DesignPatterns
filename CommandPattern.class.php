<?php

abstract class Command
{
    abstract public function execute(CommandContext $context);
}

class LoginCommand extends Command
{
    public function execute(CommandContext $context) {
        $manager = Registry::getAccessManager();
        $user = $context->get('user');
        $pass = $context->get('password');

        $user_obj = $manager->login($user, $pass);
        if(is_null($user_obj)) {
            $context->setError($manager->getError());
            return false;
        }

        $context->addParam('user', $user_obj);
        return true;
    }
}

class CommandContext
{
    private $params = array();
    private $error = '';

    public function __construct() {
        $this->params = $_POST;
    }

    public function addParam($key, $value) {
        $this->params[$key] = $value;
    }

    public function get($key) {
        return $this->params[$key];
    }

    public function setError($error) {
        $this->error = $error;
    }

    public function getError() {
        return $this->error;
    }
}

class CommandNotFoundException extends Exception {}

class CommandFactory
{
    private static $dir = 'commands';

    public static function getCommand($action = 'Default') {
        if (preg_match( '/\W/', $action) ) {
            throw new Exception("illegal characters in action");
        }

        $class = UCFirst(strtolower($action))."Command";
        $file = self::$dir.DIRECTORY_SEPARATOR."{$class}.php";
        if (!file_exists($file)) {
            throw new CommandNotFoundException( "could not find '$file'" );
        }

        require_once($file);
        if (!class_exists($class)) {
            throw new CommandNotFoundException("no '$class' class located");
        }

        $cmd = new $class();
        return $cmd;
    }
}

class Controller {
    private $context;

    function __construct() {
        $this->context = new CommandContext();
    }

    function getContext() {
        return $this->context;
    }

    function process() {
        $cmd = CommandFactory::getCommand($this->context->get('action'));
        if (!$cmd->execute($this->context)) {
            // handle failure
        } else {
            // success
            // dispatch view now..
        }
    }
}

$controller = new Controller();
$context = $controller->getContext();

$context->addParam('action', 'login' );
$context->addParam('username', 'bob' );
$context->addParam('pass', 'tiddles' );
$controller->process();

?>