<?php

class Controller
{
    private $applicationHelper;
    private function __construct() {}

    public static function run() {
        $control = new Controller();
        $control->init();
        $control->requestHelper();
    }

    public function init() {
        $this->applicationHelper = new ApplicationHelper();
        $this->applicationHelper->init();
    }

    public function requestHelper() {
        $request = new Request();
        $cmd_r = new CommandResolver();
        $cmd = $cmd_r->getCommand($request);
        $cmd->execute($request);
    }
}