<?php

namespace Devorto\ColorConversions;

/**
 * Class CMYK
 *
 * @package Devorto\ColorConversions
 */
class CMYK
{
    /**
     * @var int
     */
    protected int $cyan;

    /**
     * @var int
     */
    protected int $magenta;

    /**
     * @var int
     */
    protected int $yellow;

    /**
     * @var int
     */
    protected int $key;

    /**
     * @param int $cyan
     * @param int $magenta
     * @param int $yellow
     * @param int $key
     *
     * @throws ColorException
     */
    public function __construct(int $cyan = 0, int $magenta = 0, int $yellow = 0, int $key = 0)
    {
        $this->setCMYK($cyan, $magenta, $yellow, $key);
    }

    /**
     * Set CMYK Color Code.
     *
     * @param int $cyan %
     * @param int $magenta %
     * @param int $yellow %
     * @param int $key % (Black)
     *
     * @return CMYK
     * @throws ColorException
     */
    public function setCMYK(int $cyan = 0, int $magenta = 0, int $yellow = 0, int $key = 0): CMYK
    {
        $this->setCyan($cyan);
        $this->setMagenta($magenta);
        $this->setYellow($yellow);
        $this->setKey($key);

        return $this;
    }

    /**
     * Get CMYK Color Code.
     *
     * @return int[]
     */
    public function getCMYK(): array
    {
        return [
            'cyan' => $this->cyan,
            'magenta' => $this->magenta,
            'yellow' => $this->yellow,
            'key' => $this->key
        ];
    }

    /**
     * Sets the Cyan color percentage.
     *
     * @param int $cyan
     *
     * @return CMYK
     * @throws ColorException
     */
    public function setCyan(int $cyan): CMYK
    {
        if ($cyan < 0 || $cyan > 100) {
            throw new ColorException('cyan', $cyan, 0, 100);
        }
        $this->cyan = $cyan;

        return $this;
    }

    /**
     * Get the Cyan color percentage.
     *
     * @return int
     */
    public function getCyan(): int
    {
        return $this->cyan;
    }

    /**
     * Sets the Magenta color percentage.
     *
     * @param int $magenta
     *
     * @return CMYK
     * @throws ColorException
     */
    public function setMagenta(int $magenta): CMYK
    {
        if ($magenta < 0 || $magenta > 100) {
            throw new ColorException('magenta', $magenta, 0, 100);
        }
        $this->magenta = $magenta;

        return $this;
    }

    /**
     * Gets the Magenta color percentage.
     *
     * @return int
     */
    public function getMagenta(): int
    {
        return $this->magenta;
    }

    /**
     * Sets the Yellow color percentage.
     *
     * @param int $yellow
     *
     * @return CMYK
     * @throws ColorException
     */
    public function setYellow(int $yellow): CMYK
    {
        if ($yellow < 0 || $yellow > 100) {
            throw new ColorException('yellow', $yellow, 0, 100);
        }
        $this->yellow = $yellow;

        return $this;
    }

    /**
     * Gets the Yellow color percentage.
     *
     * @return int
     */
    public function getYellow(): int
    {
        return $this->yellow;
    }

    /**
     * Sets the Key percentage.
     *
     * @param int $key
     *
     * @return CMYK
     * @throws ColorException
     */
    public function setKey(int $key): CMYK
    {
        if ($key < 0 || $key > 100) {
            throw new ColorException('key', $key, 0, 100);
        }
        $this->key = $key;

        return $this;
    }

    /**
     * Gets the Key percentage.
     *
     * @return int
     */
    public function getKey(): int
    {
        return $this->key;
    }

    /**
     * Converts from HTML color format to CMYK color format.
     *
     * @param HTML $html
     *
     * @return CMYK
     * @throws ColorException
     */
    public function fromHTML(HTML $html): CMYK
    {
        $this->fromRGB($html->toRGB());

        return $this;
    }

    /**
     * Converts from CMYK color format to HTML color format.
     *
     * @return HTML
     */
    public function toHTML(): HTML
    {
        $html = new HTML();
        $html->fromCMYK($this);

        return $html;
    }

    /**
     * Converts from RGB color format to CMYK color format.
     *
     * @param RGB $rgb
     *
     * @return CMYK
     * @throws ColorException
     */
    public function fromRGB(RGB $rgb): CMYK
    {
        $red = $rgb->getRed() / 255;
        $green = $rgb->getGreen() / 255;
        $blue = $rgb->getBlue() / 255;

        $key = min(1 - $red, 1 - $green, 1 - $blue);
        $cyan = (1 - $red - $key) / (1 - $key);
        $magenta = (1 - $green - $key) / (1 - $key);
        $yellow = (1 - $blue - $key) / (1 - $key);

        $this->setCyan(round($cyan * 100));
        $this->setMagenta(round($magenta * 100));
        $this->setYellow(round($yellow * 100));
        $this->setKey(round($key * 100));

        return $this;
    }

    /**
     * Converts from CMYK color format to RGB color format.
     *
     * @return RGB
     * @throws ColorException
     */
    public function toRGB(): RGB
    {
        $rgb = new RGB();
        $rgb->fromCMYK($this);

        return $rgb;
    }

    /**
     * Converts HSV color format to CMYK color format.
     *
     * @param HSV $hsv
     *
     * @return CMYK
     * @throws ColorException
     */
    public function fromHSV(HSV $hsv): CMYK
    {
        return $this->fromRGB($hsv->toRGB());
    }

    /**
     * Converts CMYK color format to HSV color format.
     *
     * @return HSV
     */
    public function toHSV(): HSV
    {
        $hsv = new HSV();
        $hsv->fromCMYK($this);

        return $hsv;
    }
}
