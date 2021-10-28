<?php

namespace Devorto\ColorConversions;

/**
 * Class HTML
 *
 * @package Devorto\ColorConversions
 */
class HTML
{
    /**
     * @var string HTML hex code without "#"
     */
    protected string $htmlColorCode;

    /**
     * @var string[]
     */
    protected static array $named = [
        'white' => 'ffffff',
        'black' => '000000',
        'red' => 'ff0000',
        'blue' => '0000ff',
        'green' => '00ff00'
    ];

    /**
     * @param string $htmlColorCode
     *
     * @throws ColorException
     */
    public function __construct(string $htmlColorCode = '#000000')
    {
        $this->setHTML($htmlColorCode);
    }

    /**
     * Sets HTML color code.
     *
     * @param string $htmlColorCode Code or HTML name
     *
     * @return HTML
     * @throws ColorException
     */
    public function setHTML(string $htmlColorCode = '#000000'): HTML
    {
        $htmlColorCode = strtolower(trim($htmlColorCode));
        if (substr($htmlColorCode, 0, 1) != '#' && array_key_exists($htmlColorCode, self::$named)) {
            $htmlColorCode = self::$named[$htmlColorCode];
        } else {
            $htmlColorCode = trim($htmlColorCode, '#');
            // Assuming short syntax
            if (strlen($htmlColorCode) == 3) {
                $pieces = str_split($htmlColorCode);
                array_walk($pieces, function ($char) {
                    return str_repeat($char, 2);
                });
                $htmlColorCode = implode('', $pieces);
            }
            // Validate HTML input
            if (!preg_match('/^[a-f0-9]{6}$/i', $htmlColorCode)) {
                throw new ColorException('html', $htmlColorCode, '#ffffff', '#000000');
            }
        }
        $this->htmlColorCode = $htmlColorCode;

        return $this;
    }

    /**
     * Gets HTML color code.
     *
     * @return string
     */
    public function getHTML(): string
    {
        return '#' . $this->htmlColorCode;
    }

    /**
     * Converts CMYK color format to HTML color format.
     *
     * @param CMYK $cmyk
     *
     * @return HTML
     * @throws ColorException
     */
    public function fromCMYK(CMYK $cmyk): HTML
    {
        $this->fromRGB($cmyk->toRGB());

        return $this;
    }

    /**
     * Converts HTML color format to CMYK color format.
     *
     * @return CMYK
     * @throws ColorException
     */
    public function toCMYK(): CMYK
    {
        $cmyk = new CMYK();
        $cmyk->fromHTML($this);

        return $cmyk;
    }

    /**
     * Converts RGB color format to HTML color format.
     *
     * @param RGB $rgb
     *
     * @return HTML
     * @throws ColorException
     */
    public function fromRGB(RGB $rgb): HTML
    {
        $red = str_pad(dechex($rgb->getRed()), 2, '0', STR_PAD_LEFT);
        $green = str_pad(dechex($rgb->getGreen()), 2, '0', STR_PAD_LEFT);
        $blue = str_pad(dechex($rgb->getBlue()), 2, '0', STR_PAD_LEFT);

        $this->setHTML(implode('', [$red, $green, $blue]));

        return $this;
    }

    /**
     * Converts HTML color format to RGB color format.
     *
     * @return RGB
     * @throws ColorException
     */
    public function toRGB(): RGB
    {
        $rgb = new RGB();
        $rgb->fromHTML($this);

        return $rgb;
    }

    /**
     * Converts HSV color format to HTML color format.
     *
     * @param HSV $hsv
     *
     * @return HTML
     * @throws ColorException
     */
    public function fromHSV(HSV $hsv): HTML
    {
        return $this->fromRGB($hsv->toRGB());
    }

    /**
     * Converts HTML color format to HSV color format.
     *
     * @return HSV
     * @throws ColorException
     */
    public function toHSV(): HSV
    {
        $hsv = new HSV();
        $hsv->fromHTML($this);

        return $hsv;
    }
}
