<?php declare(strict_types=1);

namespace Tom\WhiteNoise\DI;

use Nette;
use Nette\Schema\Expect;
use Nette\Schema\Schema;
use Tom\WhiteNoise\WhiteNoise;

class Extension extends Nette\DI\CompilerExtension
{


    public function getConfigSchema(): Schema {
        return Expect::structure([
            'dirPath'      => Expect::string(),
            'async'        => Expect::bool(),
            'width'        => Expect::int(),
            'height'       => Expect::int(),
            'squareWidth'  => Expect::int(),
            'squareHeight' => Expect::int(),
            'colorScheme'  => Expect::string(),
        ]);
    }

    public function loadConfiguration() :void
    {
        $builder = $this->getContainerBuilder();
        $config  = (array)  $this->getConfig();
        $builder->addDefinition($this->prefix('noise'))
            ->setFactory( WhiteNoise::class, [$config]);

    }


}