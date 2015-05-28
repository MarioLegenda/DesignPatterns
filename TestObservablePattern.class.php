<?php

$input = array(
    'ime' => '',
    'prezime' => '',
    'lozinka' => ''
);

interface TestObservable
{
    public function attach(Observer $observer);
    public function detach(Observer $observer);
    public function notify();
}

interface TestObserver
{
    public function update();
}

class GenericHandler
{

}

?>