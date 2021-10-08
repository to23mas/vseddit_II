<?php declare(strict_types=1);

namespace Tom\Hello;

use Nette;

class Hello
{

    use Nette\SmartObject;

    /** @var string  */
    private string $message;
    /** @var int  */
    private int $times;

    /**
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->message = $config['text'];
        $this->times = $config['times'];
    }


    /**
     * @return string|null
     */
    public function getText(): ?string
    {
        $x       = $this->getMultiplier();
        $message = $this->getMessage();

        $finalMessage = str_repeat(' '. $message, $x);

        return $finalMessage.'!';

    }

    private function getMultiplier(): int{
        return $this->times;
    }

    private function getMessage(): string{
        return $this->message;
    }

}