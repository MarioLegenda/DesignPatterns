<?php

/*
 *    Decorator pattern je najbolje shvaćen u klijentskom kodu. U ovom slučaju, validacija forme počinje u klasi ValidateInput
 *    ValidateInput kao parametre prima parametre u smislu Decorator objekata koji se pozovu kada su za to stvoreni uvjeti.
 *    Te objekte sprema u svoju varijblu. Ti objekti spremaju svoje objekte u varijablu i tako sve to zadnjeg parametra u obliku
 *    objekta.
 *    Ti objekti dijele zajedničko Decorator sučelje koje je opisano u abstraktnoj Decorator klasi. Ta klasa zahtjeva deklariranje
 *    dvaju metoda:
 *       - getProcessOutcome()
 *       - clearCache()
 *    Klijentski kod se inicijalizira na sljedeći način:
 *       $validate = new ValidateInput(new SanitizeInput(new WriteUser()));
 *    Svaki od ovih objekata ima zajedničko sučelje sa gore spomenutim metodama zajedno sa metodama specifičnim za taj objekt.
 *    Egzekucija ovog paterna počinje u metodi getProcessOutcome()
 *       $validate->getProcessOutcome()
 *    Ta metoda koja se poziva je unutar ValidateInput klase. Ako ta metoda uspješno obavi svoj posao (vrati true), poziva se
 *    sljedeća metoda spremljenog objekta u ValidateInput klasi. U ovom slučaju, to je SanitizeInput. SanitizeInput poziva svoju
 *    getProcessOutcome() metodu i, svojstveno se, odlučuje da li će pozvati getProcessOutcome metodu svog Decorator objekta, u
 *    ovom slučaju WriteUser objekta.
 *    Cijeli proces završava u WriteUser objektu, odnosno bilo kojem zadnjem objektu koji smo inicijalizirali kao zadnji parametar
 *    ValidateUser konstruktora.
 *
 *    Ovaj pattern najbolji je u slučaju da moramo napraviti niz operacija koje ne moraju vratiti neke podatke, npr. pisanje podataka
 *    na tekstualni file. U slučaju potrebe vraćanja podataka, najbolje je napraviti jedan zajednički objekt u kojem bi ti podaci bili.
 *    Tada bi Decorator pattern vračao taj objekt. U ovom primjeru, to je InputErrors objekt.
 *
 * */

$post = array(
    'ime' => 'Mario',
    'prezime' => 'Legenda',
    'mail' => 'maslec.krlec10@gmailc.om',
    'lozinka' => 'digital',
    'reg-button' => 'Registriraj se'
);

define('USER_WRITTEN', 1986);

abstract class Decorator
{
    protected $ladder;

    abstract function getProcessOutcome();
    abstract protected function clearCache();
}

class WriteUser extends Decorator
{
    private $signal;

    public function __construct() {

    }

    public function getProcessOutcome() {
        echo 'Writing new user to database. Please wait...' . '<br>';
        $this->signal = 1986;
        return $this->signal;
    }

    protected function clearCache() {
    }
}

class SanitizeInput extends Decorator
{
    public function __construct($ladder) {
        $this->ladder = $ladder;
    }

    public function getProcessOutcome() {
        echo 'Sanitizing input. Next step...' . '<br>';
        return $this->ladder->getProcessOutcome();
    }

    protected function clearCache() {
        $this->ladder->clearCache();
        $this->ladder = null;
    }
}

class ValidateInput extends Decorator
{
    private $errorHandler;

    public function __construct($ladder) {
        $this->ladder = $ladder;
    }

    public function getProcessOutcome() {
        echo 'Validating input. Please hold...' . '<br><br>';
        if($this->validate() === true) {
            return $this->ladder->getProcessOutcome();
        }
        else {
            $this->clearCache();
            return $this->errorHandler;
        }
    }

    protected function clearCache() {
        $this->ladder->clearCache();
        $this->ladder = null;
    }

    private function validate() {
        global $post;

        foreach($post as $value) {
            if(empty($value)) {
                $this->errorHandler = new InputErrors($post);
                return false;
            }
        }

        return true;
    }
}

class InputErrors
{
    private $errors = array(
        'ime' => '',
        'prezime' => '',
        'mail' => '',
        'lozinka' => '',
     );

    public function __construct($data) {
        foreach($data as $key => $value) {
            if(array_key_exists($key, $this->errors)) {
                $this->errors[$key] = $value;
            }
        }
    }

    public function getError($type) {
        if(array_key_exists($type, $this->errors) && !empty($this->errors[$type])) {
            return $this->errors[$type];
        }

        return false;
    }
}

$process = new ValidateInput(new SanitizeInput(new WriteUser()));
$outcome = $process->getProcessOutcome();

if($outcome instanceof InputErrors) {
    echo 'Ime: ' . $outcome->getError('ime') . '<br>';
    echo 'Prezime: ' . $outcome->getError('prezime') . '<br>';
    echo 'Mail: ' . $outcome->getError('mail') . '<br>';
    echo 'Lozinka: ' . $outcome->getError('lozinka') . '<br>';
    die('<br>'. 'User not validated. Change code and come back, you stupid cry baby bitch!');
}
else if($outcome == USER_WRITTEN) {
    die('User validated, input sanitized, user registered. You may proceed...');
}
?>