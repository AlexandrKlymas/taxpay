<?php


namespace EvolutionCMS\Main\Console;


use Illuminate\Console\Command;

class EchoCommand extends Command
{
    protected $signature = 'echo:echo';

    protected $description = 'Echo test command';


    public function handle(){
        evolutionCMS()->logEvent(1,2,'echo','echo');
    }
}