<?php

abstract class Expression
{
    private static $keyCount = 0;
    private $key;

    abstract function interpret(InterpreterContext $context);

    public function getKey() {
        if(!isset($this->key)) {
            self::$keyCount++;
            $this->key = self::$keyCount;
        }

        return $this->key;
    }
}

class LiteralExpression extends Expression
{
    private $value;

    public function __construct($value) {
        $this->value = $value;
    }

    public function interpret(InterpreterContext $context) {
        $context->replace($this, $this->value);
    }
}

class VariableExpression extends Expression
{
    private $name;
    private $val;

    public function __construct($name, $val = null) {
        $this->name = $name;
        $this->val = $val;
    }

    public function interpret(InterpreterContext $context) {
        if(!is_null($this->val)) {
            $context->replace($this, $this->val);
            $this->val = null;
        }
    }

    public function setValue($value) {
        $this->val = $value;
    }

    public function getKey() {
        return $this->name;
    }
}

abstract class OperatorExpression extends Expression
{
    protected $l_op;
    protected $r_op;

    public function __construct(Expression $l_op, Expression $r_op) {
        $this->l_op = $l_op;
        $this->r_op = $r_op;
    }

    public function interpret(InterpreterContext $context) {
        $this->l_op->interpret($context);
        $this->r_op->interpret($context);
        $result_l = $context->lookup($this->l_op);
        $result_r = $context->lookup($this->r_op);
        $this->doInterpret($context, $result_l, $result_r);
    }

    protected abstract function doInterpret(InterpreterContext $context, $result_l, $resul_r);
}

class EqualsExpression extends OperatorExpression
{
    protected function doInterpret(InterpreterContext $context, $result_l, $result_r) {
        $context->replace($this, $result_l == $result_r);
    }
}

class InterpreterContext
{
    private $expressionStore = array();

    public function replace(Expression $exp, $value) {
        $this->expressionStore[$exp->getKey()] = $value;
    }

    public function lookup(Expression $exp) {
        return $this->expressionStore[$exp->getKey()];
    }

    public function view() {
        echo '<pre>';
        var_dump($this->expressionStore);
    }
}

$context = new InterpreterContext();
$varOne = new VariableExpression('num_one', 1);
$varTwo = new VariableExpression('num_two', 2);
$equals = new EqualsExpression($varOne, $varTwo);

$equals->interpret($context);
echo $context->lookup($equals);
$context->view();

?>