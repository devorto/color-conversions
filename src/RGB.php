<?php
/**
 * @author Geoffrey Dijkstra
 */
namespace gdwebs\ColorFormats;

/**
 * Class RGB
 * @package gdwebs\Colors
 */
class RGB
{
    /** @var int */
    private $red;
    /** @var int */
    private $green;
    /** @var int */
    private $blue;

    /**
     * RGB constructor
     *
     * @param int $red
     * @param int $green
     * @param int $blue
     */
    public function __construct($red = 0, $green = 0, $blue = 0)
    {
        $this->setRGB($red, $green, $blue);
    }

    /**
     * Set RGB Colors
     *
     * @param int $red
     * @param int $green
     * @param int $blue
     * @return self
     */
    public function setRGB($red = 0, $green = 0, $blue = 0)
    {
        $this->setRed($red);
        $this->setGreen($green);
        $this->setBlue($blue);

        return $this;
    }

    /**
     * Get RGB Colors
     *
     * @return array
     */
    public function getRGB()
    {
        return [
            'red' => $this->red,
            'green' => $this->green,
            'blue' => $this->blue
        ];
    }

    /**
     * Set RGB Red Color
     *
     * @param int $red
     * @return self
     * @throws ColorException
     */
    public function setRed($red)
    {
        $red = (int)$red;
        if ($red < 0 || $red > 255) {
            throw new ColorException('red', $red, 0, 255);
        }
        $this->red = $red;

        return $this;
    }

    /**
     * Get RGB Red Color
     *
     * @return int
     */
    public function getRed()
    {
        return $this->red;
    }

    /**
     * Set RGB Green Color
     *
     * @param int $green
     * @return self
     * @throws ColorException
     */
    public function setGreen($green)
    {
        $green = (int)$green;
        if ($green < 0 || $green > 255) {
            throw new ColorException('green', $green, 0, 255);
        }
        $this->green = $green;

        return $this;
    }

    /**
     * Get RGB Green Color
     *
     * @return int
     */
    public function getGreen()
    {
        return $this->green;
    }

    /**
     * Set RGB Blue Color
     *
     * @param int $blue
     * @return self
     * @throws ColorException
     */
    public function setBlue($blue)
    {
        $blue = (int)$blue;
        if ($blue < 0 || $blue > 255) {
            throw new ColorException('blue', $blue, 0, 255);
        }
        $this->blue = $blue;

        return $this;
    }

    /**
     * Get RGB Blue Color
     *
     * @return int
     */
    public function getBlue()
    {
        return $this->blue;
    }

    /**
     * Converts CMYK color format to RGB color format
     *
     * @param CMYK $cmyk
     * @return self
     * @throws ColorException
     */
    public function fromCMYK(CMYK $cmyk)
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
     * Converts from RGB color format to CMYK color format
     *
     * @return CMYK
     */
    public function toCMYK()
    {
        $cmyk = new CMYK();
        $cmyk->fromRGB($this);

        return $cmyk;
    }

    /**
     * Converts from HTML color format to RGB color format
     *
     * @param HTML $html
     * @return self
     * @throws ColorException
     */
    public function fromHTML(HTML $html)
    {
        $this->setRed(hexdec(substr($html->getHTML(false), 0, 2)));
        $this->setGreen(hexdec(substr($html->getHTML(false), 2, 2)));
        $this->setBlue(hexdec(substr($html->getHTML(false), 4, 2)));

        return $this;
    }

    /**
     * Converts from RGB color format to HTML color format
     *
     * @return HTML
     */
    public function toHTML()
    {
        $html = new HTML();
        $html->fromRGB($this);
        
        return $html;
    }
}
