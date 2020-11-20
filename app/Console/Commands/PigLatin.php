<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

abstract class PigLatin extends Command
{
    public $word;
    abstract public function handle();
}
