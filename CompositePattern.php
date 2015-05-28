<?php

abstract class Unit
{
    abstract public function bombardStrength();

    protected $depth = 0;

    public function getComposite() {
        return null;
    }

    public function accept(ArmyVisitor $visitor) {
        // $visitor je TextDumpArmyVisitor
        // $this je Army objekt
        // $metod = visitArmy();
        // TextDumpArmyVisitor->visitArmy(Army);
        $method = "visit" . get_class( $this );
        $visitor->$method($this);
    }

    protected function setDepth( $depth ) {
        $this->depth = $depth;
    }

    public function getDepth() {
        return $this->depth;
    }
}

class Archer extends Unit
{
    public function bombardStrength() {
        return 4;
    }
}

class LaserCannonUnit extends Unit
{
    public function bombardStrength() {
        return 44;
    }
}

abstract class CompositeUnit extends Unit
{
    protected $units = array();

    public function getComposite() {
        return $this;
    }

    public function addUnit(Unit $unit) {
        if(in_array($unit, $this->units, true)) {
            return;
        }

        $unit->setDepth($this->depth + 1);
        $this->units[] = $unit;
    }

    public function removeUnit(Unit $unit) {
        $this->units = array_udiff($this->units, array($unit), function( $a, $b ) { return ($a === $b) ? 0:1; });
    }

    public function accept(ArmyVisitor $visitor) {
        parent::accept($visitor);
        foreach ($this->units as $thisunit) {
            $thisunit->accept($visitor);
        }
    }
}

class Army extends CompositeUnit
{
    public function bombardStrength() {
        $total = 0;
        foreach($this->units as $unit) {
            $total += $unit->bombardStrength();
        }

        return $total;
    }

    public function viewUnits() {
        echo '<pre>';
        print_r($this->units);
    }
}

abstract class ArmyVisitor {
    abstract function visit(Unit $node);

    public function visitArcher(Archer $node) {
        $this->visit($node);
    }

    public function visitLaserCannonUnit(LaserCannonUnit $node) {
        $this->visit($node);
    }

    public function visitArmy(Army $node) {
        // $this -> TextDumpArmyVisitor
        $this->visit($node);
    }
}

class TextDumpArmyVisitor extends ArmyVisitor {
    private $text="";

    public function visit(Unit $node) {
        $ret = "";
        $pad = 4*$node->getDepth();
        $ret .= sprintf( "%{$pad}s", "" );
        $ret .= get_class($node).": ";
        $ret .= "bombard: ".$node->bombardStrength()."<br>";
        $this->text .= $ret;
    }
    function getText() {
        return $this->text;
    }
}

class UnitScript
{
    public static function joinExisting(Unit $newUnit, Unit $occupyingUnit) {
        $comp = '';

        if(!is_null($comp = $occupyingUnit->getComposite())) {
            $comp->addUnit($newUnit);
        }
        else {
            $comp = new Army();
            $comp->addUnit($occupyingUnit);
            $comp->addUnit($newUnit);
        }

        return $comp;
    }
}

$main_army = new Army();
$main_army->addUnit(new Archer());
$main_army->addUnit(new LaserCannonUnit());

$textdump = new TextDumpArmyVisitor();
$main_army->accept($textdump);
echo '<pre>';
print $textdump->getText();

/*
 *    CompositeUnit je napravljen da bi se izbjeglo definiranje metoda addUnit() i removeUnit() iz leaf klasa koje se metode nisu upotrebljavale.
 *    Klijentski kod nije mogao znati da li objekt ima te metode ili ne. Tome služi getComposite() metoda u CompositeUnit te UnitScript i njena joinExisting()
 *    metoda.
 *
 *    addUnit() i removeUnit() metode mogu pozvati samo na Composite objektima. Za te metode, trebaju mi dva objekta. Objekt koji dodajem i objekt u kojeg
 *    dodajem. Objekt u kojeg dodajem mora imati addUnit() metodu. UnitScript to radi. Provjerava da je objekt u kojeg pokušavam dodati Composite objekt te
 *    ako jest, dodaje u njega novi objekt. Ako nije, stvara novi Composite objekt i u njega dodaje objekt. Tako mogu uvijek biti siguran da će se stvoriti
 *    Composite objekt ili upotrebljavati postojeći.
 * */

?>