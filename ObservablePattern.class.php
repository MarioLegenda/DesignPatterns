<?php

interface Observable
{
    public function attach(Observer $observer);
    public function detach(Observer $observer);
    public function notify();
}

interface Observer
{
    public function update(Observable $observable);
}

abstract class LoginObserver implements Observer
{
    private $login;

    public function __construct(Login $login) {
        $this->login = $login;
        $login->attach($this);
    }

    public function update(Observable $observable) {
        if($observable == $this->login) {
            $this->doUpdate($observable);
        }
    }

    abstract function doUpdate(Login $login);
}

class SecurityMonitor extends LoginObserver
{
    public function doUpdate(Login $observable) {
        /*
             u slučaju veće apstrakcije, instanceof operater umjesto type checking argumentima

        */
        $status = $observable->getStatus();
        if($status[0] == LOGIN::LOGIN_WRONG_PASS) {
            print __CLASS__ . ' sending mail to sysadmin';
        }
    }
}

class Login implements Observable
{
    private $observers;

    const LOGIN_USER_UNKNOWN = 1;
    const LOGIN_WRONG_PASS = 2;
    const LOGIN_ACCESS = 3;
    private $status = array();

    public function __construct() {
        $this->observers = array();
    }

    public function handleLogin($user, $pass, $ip) {
        switch ( rand(1,3) ) {
            case 1:
                $this->setStatus( self::LOGIN_ACCESS, $user, $ip );
                $ret = true; break;
            case 2:
                $this->setStatus( self::LOGIN_WRONG_PASS, $user, $ip );
                $ret = false; break;
            case 3:
                $this->setStatus( self::LOGIN_USER_UNKNOWN, $user, $ip );
                $ret = false; break;
        }
        $this->notify();
        return $ret;
    }

    public function attach(Observer $observer) {
        $this->observers[] = $observer;
    }

    public function detach(Observer $observer) {
        $newobserver = array();
        foreach($this->observers as $obs) {
            if($obs !== $observer) {
                $newobserver[] = $obs;
            }
        }

        $this->observers = $newobserver;
    }

    public function notify() {
        foreach($this->observers as $obs) {
            $obs->update($this);
        }
    }

    private function setStatus( $status, $user, $ip ) {
        $this->status = array( $status, $user, $ip );
    }

    public function getStatus() {
        return $this->status;
    }
}

$login = new Login();
$login->attach(new SecurityMonitor());
$login->handleLogin('Mario', 'digital', '127.0.0.1');


?>