<?php

interface Fighter
{
    public function getFight(Pokemon $target);
    public function useSpeAtk(Pokemon $target);
}
