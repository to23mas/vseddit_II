<?php declare(strict_types=1);

namespace  Tom\Hello\DI;

use  Tom\Hello\Hello;

use Nette;
use Nette\Schema\Expect;
use Nette\Schema\Schema;

class Extension extends Nette\DI\CompilerExtension
{


    public function getConfigSchema(): Schema {
        return Expect::structure([
            'text' => Expect::string(),
            'times' => Expect::int(),
        ]);
    }

    public function loadConfiguration(): void
    {
        $builder = $this->getContainerBuilder();
        $config  = (array)  $this->getConfig();
        $builder->addDefinition($this->prefix('hello'))
                ->setFactory(Hello::class, [$config]);

    }


}