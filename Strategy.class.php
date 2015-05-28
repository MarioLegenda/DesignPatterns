<?php

abstract class Question
{
    protected $prompt;
    protected $marker;

    public function __construct($prompt, Marker $marker) {
        $this->marker = $marker;
        $this->prompt = $prompt;
    }

    public function mark($response) {
        return $this->marker->mark($response);
    }
}

class TextQuestion extends Question
{
    public function textMethod() {}
}

class AVQuestion extends Question
{
    public function avMethod() {}
}

// Marker implementacija

abstract class Marker
{
    protected $test;

    abstract public function mark($response);

    public function __construct($test) {
        $this->test = $test;
    }
}

class MarkLoginMarker extends Marker
{
    private $engine;

    public function __construct($test) {
        parent::__construct($test);
    }

    public function mark($response) {
        return true;
    }
}

class MatchMaker extends Marker
{
    public function mark($response) {
        return ($this->test == $response);
    }
}

class RegexpMarker extends Marker
{
    public function mark($response) {
        return preg_match($this->test, $response);
    }
}

//$question = new TextQuestion('how many beans make five', new RegexpMarker('/f.ve/'));

$markers = array(
    new RegexpMarker('/f.ve/'),
    new MatchMaker('five'),
    new MarkLoginMarker('$input equals "five"')
);

foreach($markers as $marker) {
    print get_class($marker) . "<br>";
    $question = new TextQuestion("five", $marker);
    foreach(array("five", "four") as $response) {
        print "response: $response ";
        if($question->mark($response)) {
            print "well done\n";
        }
        else {
            print "never mind";
        }
    }
}

?>