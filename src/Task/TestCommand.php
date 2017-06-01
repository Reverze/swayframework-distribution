<?php

namespace Sway\Distribution\Test;

use Sway\Component\Console\Command\Command;

class TestCommand extends Command
{
    public function configure()
    {
        $this->setName('framework:test');
        $this->setDescription('Test command');
    }

    public function before()
    {

    }


}

?>