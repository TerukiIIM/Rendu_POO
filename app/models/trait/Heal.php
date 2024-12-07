<?php

trait Heal 
{
    public function heal(): void
    {
        $this->hp = $this->max_hp;
        echo "{$this->name} has been healed and now has {$this->hp} HP!<br>";
    }
}
