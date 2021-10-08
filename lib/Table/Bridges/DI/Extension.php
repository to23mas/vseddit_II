<?php declare(strict_types=1);


namespace Tom\Table\Bridges\DI;

use Nette\DI\CompilerExtension;
use Nette\Schema\Expect;
use Nette\Schema\Schema;

use Tom\Table\TableFactoryInterface;

class Extension extends CompilerExtension {

    public function getConfigSchema(): Schema {
        return Expect::structure([
            'perPage' => Expect::int(),
        ]);
    }

    public function loadConfiguration(): void  {
        $builder = $this->getContainerBuilder();

        $builder->addFactoryDefinition($this->prefix('table.factory'))
                ->setImplement(TableFactoryInterface::class);

    }
}