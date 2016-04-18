<?php
/**
 * @author Geoffrey Dijkstra
 */
namespace gdwebs\ColorFormats;

/**
 * Class CMYK
 * @package gdwebs\Colors
 */
class CMYK
{
    /** @var int */
    protected $cyan;
    /** @var int */
    protected $magenta;
    /** @var int */
    protected $yellow;
    /** @var int */
    protected $key;

    /**
     * CMYK constructor.
     *
     * @param int|string $cyan
     * @param int|string $magenta
     * @param int|string $yellow
     * @param int|string $key
     */
    public function __construct($cyan = 0, $magenta = 0, $yellow = 0, $key = 0)
    {
        $this->setCMYK($cyan, $magenta, $yellow, $key);
    }

    /**
     * Set CMYK Color Code
     *
     * @param int|string $cyan %
     * @param int|string $magenta %
     * @param int|string $yellow %
     * @param int|string $key % (Black)
     * @return self
     */
    public function setCMYK($cyan, $magenta, $yellow, $key)
    {
        $this->setCyan($cyan);
        $this->setMagenta($magenta);
        $this->setYellow($yellow);
        $this->setKey($key);

        return $this;
    }

    /**
     * Get CMYK Color Code
     *
     * @param bool $includePercentage
     * @return array
     */
    public function getCMYK($includePercentage = true)
    {
        return [
            'cyan' => $this->cyan . ($includePercentage ? '%' : ''),
            'magenta' => $this->magenta . ($includePercentage ? '%' : ''),
            'yellow' => $this->yellow . ($includePercentage ? '%' : ''),
            'key' => $this->key . ($includePercentage ? '%' : '')
        ];
    }

    /**
     * Sets the Cyan color percentage
     *
     * @param int|string $cyan
     * @throws ColorException
     * @return self
     */
    public function setCyan($cyan)
    {
        $cyan = (int)$cyan;
        if ($cyan < 0 || $cyan > 100) {
            throw new ColorException('cyan', $cyan, 0, 100);
        }
        $this->cyan = $cyan;

        return $this;
    }

    /**
     * Get the Cyan color percentage
     *
     * @return int
     */
    public function getCyan()
    {
        return $this->cyan;
    }

    /**
     * Sets the Magenta color percentage
     *
     * @param int|string $magenta
     * @throws ColorException
     * @return self
     */
    public function setMagenta($magenta)
    {
        $magenta = (int)$magenta;
        if ($magenta < 0 || $magenta > 100) {
            throw new ColorException('magenta', $magenta, 0, 100);
        }
        $this->magenta = $magenta;

        return $this;
    }

    /**
     * Gets the Magenta color percentage
     *
     * @return int
     */
    public function getMagenta()
    {
        return $this->magenta;
    }

    /**
     * Sets the Yellow color percentage
     *
     * @param $yellow
     * @throws ColorException
     * @return self
     */
    public function setYellow($yellow)
    {
        $yellow = (int)$yellow;
        if ($yellow < 0 || $yellow > 100) {
            throw new ColorException('yellow', $yellow, 0, 100);
        }
        $this->yellow = $yellow;

        return $this;
    }

    /**
     * Gets the Yellow color percentage
     *
     * @return int
     */
    public function getYellow()
    {
        return $this->yellow;
    }

    /**
     * Sets the Key percentage
     *
     * @param int|string $key
     * @throws ColorException
     * @return self
     */
    public function setKey($key)
    {
        $key = (int)$key;
        if ($key < 0 || $key > 100) {
            throw new ColorException('key', $key, 0, 100);
        }
        $this->key = $key;

        return $this;
    }

    /**
     * Gets the Key percentage
     *
     * @return int
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * Converts from HTML color format to CMYK color format
     *
     * @param HTML $html
     * @return self
     */
    public function fromHTML(HTML $html)
    {
        $this->fromRGB($html->toRGB());

        return $this;
    }

    /**
     * Converts from CMYK color format to HTML color format
     *
     * @return HTML
     */
    public function toHTML()
    {
        $html = new HTML();
        $html->fromCMYK($this);

        return $html;
    }

    /**
     * Converts from RGB color format to CMYK color format
     *
     * @param RGB $rgb
     * @return self
     */
    public function fromRGB(RGB $rgb)
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
     * Converts from CMYK color format to RGB color format
     *
     * @return RGB
     */
    public function toRGB()
    {
        $rgb = new RGB();
        $rgb->fromCMYK($this);

        return $rgb;
    }

    /**
     * Converts HSV color format to CMYK color format
     *
     * @param HSV $hsv
     * @return self
     */
    public function fromHSV(HSV $hsv)
    {
        return $this->fromRGB($hsv->toRGB());
    }

    /**
     * Converts CMYK color format to HSV color format
     *
     * @return HSV
     */
    public function toHSV()
    {
        $hsv = new HSV();
        $hsv->fromCMYK($this);
        
        return $hsv;
    }
}
