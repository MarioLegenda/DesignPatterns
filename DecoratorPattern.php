<?php

abstract class Tile
{
    abstract public function getWealthFactor();
}

class Plains extends Tile
{
    private $wealthFactor = 2;

    public function getWealthFactor() {
        return $this->wealthFactor;
    }
}

abstract class TileDecoration extends Tile
{
    protected $tile;

    public function __construct(Tile $tile) {
        $this->tile = $tile;
    }
}

class DiamonDecorator extends TileDecoration
{
    public function getWealthFactor() {
        return $this->tile->getWealthFactor();
    }
}

class PollutionDecorator extends TileDecoration
{
    public function getWealthFactor() {
        return $this->tile->getWealthFactor();
    }
}