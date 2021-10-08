<?php declare(strict_types=1);

namespace Tom\WhiteNoise;

use Exception;
use Nette;

class WhiteNoise
{

    /** @var int */
    private int $width,
                $height,
                $squareWidth,
                $squareHeigh;

    /** @var string */
    private string $path,
                   $colorScheme;

    /** @var bool */
    private bool $async;

    /**
     * @param array $config conf file --> config\whiteNoise.neon
     */
    public function __construct(array $config)
    {
        $this->width       = $config['width'];
        $this->async       = $config['async'];
        $this->height      = $config['height'];
        $this->path        = $config['dirPath'];
        $this->squareWidth = $config['squareWidth'];
        $this->squareHeigh = $config['squareHeight'];
        $this->colorScheme = $config['colorScheme'];
    }


    /**
     * crossroad
     *
     * @return void
     * @throws Exception
     */
    public function generateImage(): void
    {
        if($this->async){
            $this->asymetric();
        }else{
            $this->symetric();
        }

    }


    /**
     * vytváří symetrický noise image
     *
     * @return bool
     * @throws Exception
     */
    private function symetric(): bool
    {
        $this->checkDimensions();

        $x = $this->getWidth(); // image width
        $y = $this->getHeight(); // image height
        $pixWidth = $this->getSquareWidth(); //  pixel width
        $pixHeigh = $this->getSquareHeigh(); // pixel height

        $columns = $x / $pixWidth;
        $rows    = $y / $pixHeigh;

        $image = imagecreatetruecolor($x, $y);

        //řady
        for($k=0; $k<$rows; $k++) {
            //sloupce
            for ($l = 0; $l < $columns; $l++) {
                $color = $this->getColor($image);
                //počet řádek v pixelu
                for ($i = 0; $i < $pixWidth; $i++) {
                    //počet sloupců v pixelu
                    for ($j = 0; $j < $pixHeigh; $j++) {
                        imagesetpixel($image, $i+ ($l * $pixWidth), $j + ($k * $pixHeigh), $color);
                    }
                }
            }
        }

        header('Content-Type: image/png');

        return imagepng($image);
    }


    /**
     * nezarovnané hrany
     * config -> async === true
     *
     * @throws Exception
     */
    private function asymetric(): bool
    {

        $pixHeigh = $this->getSquareHeigh();
        $x = $this->getWidth(); // image width
        $y = $this->getHeight(); // image height


        //ověčení výšky
        if($y % $pixHeigh) {
            $this->setHeight($y + ($pixHeigh- $y % $pixHeigh));
        }

        $image = imagecreatetruecolor($x, $y);

        for($yCor=0; $yCor<$y; $yCor++) {
            $checker = 0; // kontrola aby nedošlo k insertu barev imo rozměry image
            $base = 0;
            while ($checker<$x) {

                $rope = random_int(5, 10);
                $checker += $rope;
                $color = $this->getColor($image);

                if ($x < $checker) {
                    $rope -= ($checker - $x);
                }

                for ($j = 0; $j < $rope; $j++) {
                    imagesetpixel($image, $j + $base, $yCor, $color);
                }
                $base += $rope; // o kolik je pot5eba odsadit sou5adnice v další iteraci
            }
        }
        header('Content-Type: image/png');

        return imagepng($image);
        }


    /**
     * podle konfigu nastavuje barvy
     *
     * @throws Exception
     */
    private function getColor($image)
    {
        $choice = $this->getColorScheme();

        switch($choice){
            case 'classic':
                return imagecolorallocate($image, random_int(0, 255), random_int(0, 255), random_int(0, 255));

            case 'grey':
                $color = random_int(0, 255);
                return imagecolorallocate($image, $color, $color, $color);

            case 'blackAndWhite':
                $rand = random_int(0, 1);
                if($rand){
                    return imagecolorallocate($image, random_int(0, 0), random_int(0, 0), random_int(0, 0));
                }
                return imagecolorallocate($image, random_int(255, 255), random_int(255, 255), random_int(255, 255));

            case 'red':
                return imagecolorallocate($image, random_int(0, 240), 0, 0);

            case 'blue':
                return imagecolorallocate($image, 0, 0, random_int(0, 240));

            case 'green':
                return imagecolorallocate($image, 0, random_int(0, 240),0);

            case 'yellowIsh':
                return imagecolorallocate($image, random_int(0, 255), random_int(0, 255),0);

            default:
                return imagecolorallocate($image, random_int(0, 254), random_int(0, 255), random_int(0, 255));
        }


    }

    /**
     * Resize, aby se vešly všechny čtverce do image
     *     ________________________                 ___________________________
     *     | □□□□□ ##### □□□□□ ## | ###             | □□□□□ ##### □□□□□ ##### |
     *     | □□□□□ ##### □□□□□ ## | ###             | □□□□□ ##### □□□□□ ##### |
     *     | □□□□□ ##### □□□□□ ## | ###             | □□□□□ ##### □□□□□ ##### |
     *     | ##### □□□□□ ##### □□ | □□□             | ##### □□□□□ ##### □□□□□ |
     *     | ##### □□□□□ ##### □□ | □□□    ---->    | ##### □□□□□ ##### □□□□□ |
     *     | ##### □□□□□ ##### □□ | □□□             | ##### □□□□□ ##### □□□□□ |
     *     | □□□□□ ##### □□□□□ ## | ###             | □□□□□ ##### □□□□□ ##### |
     *     | □□□□□ ##### □□□□□ ## | ###             | □□□□□ ##### □□□□□ ##### |
     *     | □□□□□ ##### □□□□□ ## | ###             | □□□□□ ##### □□□□□ ##### |
     *     ________________________                  __________________________
     */
    private function checkDimensions(): void
    {
        $width = $this->getWidth();
        $heigh = $this->getHeight();
        $squareW = $this->getSquareWidth();
        $squareH = $this->getSquareHeigh();

        if($width % $squareW){
            $this->setWidth($width + ($squareW - $width % $squareW));
        }
        if($heigh % $squareH) {
            $this->setHeight($heigh + ($squareH - $heigh % $squareH));
        }

    }

    /**
     * @param int $width
     */
    private function setWidth(int $width): void
    {
        $this->width = $width;
    }

    /**
     * @param int $height
     */
    private function setHeight(int $height): void
    {
        $this->height = $height;
    }

    /**
     * @return int
     */
    private function getWidth(): int
    {
        return $this->width;
    }

    /**
     * @return int
     */
    private function getHeight(): int
    {
        return $this->height;
    }

    /**
     * @return int
     */
    private function getSquareWidth(): int
    {
        return $this->squareWidth;
    }

    /**
     * @return int
     */
    private function getSquareHeigh(): int
    {
        return $this->squareHeigh;
    }


    /**
     * @return string
     */
    private function getPath(): string
    {
        return $this->path;
    }

    /**
     * @return string
     */
    private function getColorScheme(): string
    {
        return $this->colorScheme;
    }












}