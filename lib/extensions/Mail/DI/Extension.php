<?php declare(strict_types=1);

namespace Tom\Mail\DI;

use Tom\Mail\Mail;


use Nette;
use Nette\Schema\Expect;
use Nette\Schema\Schema;

class Extension extends Nette\DI\CompilerExtension
{


    public function getConfigSchema(): Schema
    {
        return Expect::structure([
            'host'      => Expect::string(),
            'username'  => Expect::string(),
            'password'  => Expect::string(),
            'secure'    => Expect::string(),
            'port'      => Expect::int(),
        ]);
    }

    public function loadConfiguration(): void
    {
        $builder = $this->getContainerBuilder();
        $config = (array)$this->getConfig();
        $builder->addDefinition($this->prefix('hello'))
            ->setFactory(Mail::class, [$config]);

    }
}

