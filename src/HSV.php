<?php
/**
 * @author Geoffrey Dijkstra
 */
namespace gdwebs\colorformats;

/**
 * Class HSV
 * @package gdwebs\Colors
 */
class HSV
{
    /** @var int */
    protected $hue;
    /** @var int */
    protected $saturation;
    /** @var int */
    protected $value;

    /**
     * HSV constructor
     *
     * @param int $hue
     * @param int|string $saturation
     * @param int|string $value
     */
    public function __construct($hue = 0, $saturation = 0, $value = 0)
    {
        $this->setHSV($hue, $saturation, $value);
    }

    /**
     * Sets HSV color code
     *
     * @param int $hue 360°
     * @param string|int $saturation %
     * @param string|int $value %
     * @return self
     */
    public function setHSV($hue, $saturation, $value)
    {
        $this->setHue($hue);
        $this->setSaturation($saturation);
        $this->setValue($value);

        return $this;
    }

    /**
     * Gets HSV colors
     *
     * @param bool $includePercentage
     * @return array
     */
    public function getHSV($includePercentage = true)
    {
        return [
            'hue' => $this->hue . ($includePercentage ? '%' : ''),
            'saturation' => $this->saturation . ($includePercentage ? '%' : ''),
            'value' => $this->value . ($includePercentage ? '%' : '')
        ];
    }

    /**
     * Sets the hue color degrees
     *
     * @param int $hue
     * @return self
     * @throws ColorException
     */
    public function setHue($hue)
    {
        $hue = (int)$hue;
        if ($hue < 0 || $hue > 360) {
            throw new ColorException('hue', $hue, 0, 360);
        }
        $this->hue = $hue;

        return $this;
    }

    /**
     * Gets the hue color degrees
     *
     * @return int
     */
    public function getHue()
    {
        return $this->hue;
    }

    /**
     * Sets the saturation percentage
     *
     * @param $saturation
     * @return self
     * @throws ColorException
     */
    public function setSaturation($saturation)
    {
        $saturation = (int)$saturation;
        if ($saturation < 0 || $saturation > 100) {
            throw new ColorException('saturation', $saturation, 0, 100);
        }
        $this->saturation = $saturation;

        return $this;
    }

    /**
     * Gets the saturation percentage
     *
     * @return int
     */
    public function getSaturation()
    {
        return $this->saturation;
    }

    /**
     * Sets the value percentage
     *
     * @param $value
     * @return self
     * @throws ColorException
     */
    public function setValue($value)
    {
        $value = (int)$value;
        if ($value < 0 || $value > 100) {
            throw new ColorException('value', $value, 0, 100);
        }
        $this->value = $value;

        return $this;
    }

    /**
     * Gets the value percentage
     *
     * @return int
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Converts CMYK color format to HSV color format
     *
     * @param CMYK $cmyk
     * @return HSV
     */
    public function fromCMYK(CMYK $cmyk)
    {
        return $this->fromRGB($cmyk->toRGB());
    }

    /**
     * Converts HSV color format to CMYK color format
     *
     * @return CMYK
     */
    public function toCMYK()
    {
        $cmyk = new CMYK();
        $cmyk->fromHSV($this);
        
        return $cmyk;
    }

    /**
     * Converts HTML color format to HSV color format
     *
     * @param HTML $html
     * @return HSV
     */
    public function fromHTML(HTML $html)
    {
        return $this->fromRGB($html->toRGB());
    }

    /**
     * Converts HSV color format to HTML color format
     *
     * @return HTML
     */
    public function toHTML()
    {
        $html = new HTML();
        $html->fromHSV($this);
        
        return $html;
    }

    /**
     * Converts RGB color format to HSV color format
     *
     * @param RGB $rgb
     * @return $this
     * @throws ColorException
     */
    public function fromRGB(RGB $rgb)
    {
        $red = $rgb->getRed() / 255;
        $green = $rgb->getGreen() / 255;
        $blue = $rgb->getBlue() / 255;

        $minVal = min($red, $green, $blue);
        $maxVal = max($red, $green, $blue);
        $delta = $maxVal - $minVal;

        $hue = 0;
        $saturation = 0;
        $value = $maxVal;

        if ($delta != 0) {
            $saturation = $delta / $maxVal;
            $delRed = ((($maxVal - $red) / 6) + ($delta / 2)) / $delta;
            $delGreen = ((($maxVal - $green) / 6) + ($delta / 2)) / $delta;
            $delBlue = ((($maxVal - $blue) / 6) + ($delta / 2)) / $delta;

            if ($red == $maxVal) {
                $hue = $delBlue - $delGreen;
            } elseif ($green == $maxVal) {
                $hue = (1 / 3) + $delRed - $delBlue;
            } elseif ($blue == $maxVal) {
                $hue = (2 / 3) + $delGreen - $delRed;
            }

            if ($hue < 0) {
                $hue += 1;
            }
            if ($hue > 1) {
                $hue -= 1;
            }
        }

        $this->setHue(round($hue * 360));
        $this->setSaturation(round($saturation * 100));
        $this->setValue(round($value * 100));

        return $this;
    }

    /**
     * Converts HSV color format to RGB color format
     *
     * @return RGB
     */
    public function toRGB()
    {
        $hue = $this->hue / 360;
        $saturation = $this->saturation / 100;
        $value = $this->value / 100;

        if ($saturation == 0) {
            $red = $value * 255;
            $green = $value * 255;
            $blue = $value * 255;
        } else {
            $hue = $hue * 6;
            $var1 = floor($hue);
            $var2 = $value * (1 - $saturation);
            $var3 = $value * (1 - $saturation * ($hue - $var1));
            $var4 = $value * (1 - $saturation * (1 - ($hue - $var1)));

            if ($var1 == 0) {
                $red = $value;
                $green = $var4;
                $blue = $var2;
            } elseif ($var1 == 1) {
                $red = $var3;
                $green = $value;
                $blue = $var2;
            } elseif ($var1 == 2) {
                $red = $var2;
                $green = $value;
                $blue = $var4;
            } elseif ($var1 == 3) {
                $red = $var2;
                $green = $var3;
                $blue = $value;
            } elseif ($var1 == 4) {
                $red = $var4;
                $green = $var2;
                $blue = $value;
            } else {
                $red = $value;
                $green = $var2;
                $blue = $var3;
            };

            $red = round($red * 255);
            $green = round($green * 255);
            $blue = round($blue * 255);
        }

        return new RGB($red, $green, $blue);
    }
}
