<?php declare(strict_types=1);

namespace Tom\Mail;

use Nette;

use Nette\Application\UI\Form;

class Mail
{
    /** @var Nette\Mail\Message @persistent*/
    public Nette\Mail\Message $mail;

    /** @var string  */
    private string $host;
    /** @var string  */
    private string $username;
    /** @var string  */
    private string $password;
    /** @var string  */
    private string $secure ;

    /** @var int  */
    private int $port;

    /**
     * @param string $host
     * @param string $username
     * @param string $password
     * @param string $secure
     */
    public function __construct(array $config)
    {
        $this->host     = $config['host'];
        $this->port     = $config['port'];
        $this->username = $config['username'];
        $this->password = $config['password'];
        $this->secure   = $config['secure'];
    }

    public function setupMail(string $message, string $addressee): void
    {
        $mail = new Nette\Mail\Message;
        $mail   ->setFrom($this->username, 'Server-Tom')
                ->addTo($addressee)
                ->setSubject('Testing')
                ->setBody($message);
        $this->mail = $mail;
    }

    public function sendMail(): void
    {
        $mailer = new Nette\Mail\SmtpMailer([
            'host' => $this->host,
            'username' => $this->username,
            'password' => $this->password,
            'secure' => $this->secure,
            'port' => $this->port,
        ]);
        $mailer->send($this->mail);
    }
}