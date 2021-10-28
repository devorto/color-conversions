<?php

namespace Devorto\ColorConversions;

/**
 * Class RGB
 *
 * @package Devorto\ColorConversions
 */
class RGB
{
    /**
     * @var int
     */
    protected int $red;

    /**
     * @var int
     */
    protected int $green;

    /**
     * @var int
     */
    protected int $blue;

    /**
     * @param int $red
     * @param int $green
     * @param int $blue
     *
     * @throws ColorException
     */
    public function __construct(int $red = 0, int $green = 0, int $blue = 0)
    {
        $this->setRGB($red, $green, $blue);
    }

    /**
     * Set RGB Colors.
     *
     * @param int $red
     * @param int $green
     * @param int $blue
     *
     * @return RGB
     * @throws ColorException
     */
    public function setRGB(int $red = 0, int $green = 0, int $blue = 0): RGB
    {
        $this->setRed($red);
        $this->setGreen($green);
        $this->setBlue($blue);

        return $this;
    }

    /**
     * Get RGB Colors.
     *
     * @return int[]
     */
    public function getRGB(): array
    {
        return [
            'red' => $this->red,
            'green' => $this->green,
            'blue' => $this->blue
        ];
    }

    /**
     * Set RGB Red Color.
     *
     * @param int $red
     *
     * @return RGB
     * @throws ColorException
     */
    public function setRed(int $red): RGB
    {
        if ($red < 0 || $red > 255) {
            throw new ColorException('red', $red, 0, 255);
        }
        $this->red = $red;

        return $this;
    }

    /**
     * Get RGB Red Color.
     *
     * @return int
     */
    public function getRed(): int
    {
        return $this->red;
    }

    /**
     * Set RGB Green Color.
     *
     * @param int $green
     *
     * @return RGB
     * @throws ColorException
     */
    public function setGreen(int $green): RGB
    {
        if ($green < 0 || $green > 255) {
            throw new ColorException('green', $green, 0, 255);
        }
        $this->green = $green;

        return $this;
    }

    /**
     * Get RGB Green Color.
     *
     * @return int
     */
    public function getGreen(): int
    {
        return $this->green;
    }

    /**
     * Set RGB Blue Color.
     *
     * @param int $blue
     *
     * @return RGB
     * @throws ColorException
     */
    public function setBlue(int $blue): RGB
    {
        if ($blue < 0 || $blue > 255) {
            throw new ColorException('blue', $blue, 0, 255);
        }
        $this->blue = $blue;

        return $this;
    }

    /**
     * Get RGB Blue Color.
     *
     * @return int
     */
    public function getBlue(): int
    {
        return $this->blue;
    }

    /**
     * Converts CMYK color format to RGB color format.
     *
     * @param CMYK $cmyk
     *
     * @return RGB
     * @throws ColorException
     */
    public function fromCMYK(CMYK $cmyk): RGB
    {
        $cyan = $cmyk->getCyan() / 100;
        $magenta = $cmyk->getMagenta() / 100;
        $yellow = $cmyk->getYellow() / 100;
        $key = $cmyk->getKey() / 100;

        $red = 1 - min(1, ($cyan * (1 - $key)) + $key);
        $green = 1 - min(1, ($magenta * (1 - $key)) + $key);
        $blue = 1 - min(1, ($yellow * (1 - $key)) + $key);

        $this->setRed(round($red * 255));
        $this->setGreen(round($green * 255));
        $this->setBlue(round($blue * 255));

        return $this;
    }

    /**
     * Converts from RGB color format to CMYK color format.
     *
     * @return CMYK
     * @throws ColorException
     */
    public function toCMYK(): CMYK
    {
        $cmyk = new CMYK();
        $cmyk->fromRGB($this);

        return $cmyk;
    }

    /**
     * Converts from HTML color format to RGB color format.
     *
     * @param HTML $html
     *
     * @return RGB
     * @throws ColorException
     */
    public function fromHTML(HTML $html): RGB
    {
        $this->setRed(hexdec(substr(trim($html->getHTML(), '#'), 0, 2)));
        $this->setGreen(hexdec(substr(trim($html->getHTML(), '#'), 2, 2)));
        $this->setBlue(hexdec(substr(trim($html->getHTML(), '#'), 4, 2)));

        return $this;
    }

    /**
     * Converts from RGB color format to HTML color format.
     *
     * @return HTML
     * @throws ColorException
     */
    public function toHTML(): HTML
    {
        $html = new HTML();
        $html->fromRGB($this);

        return $html;
    }
}
