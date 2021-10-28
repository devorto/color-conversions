<?php

namespace Devorto\ColorConversions;

/**
 * Class HSV
 *
 * @package Devorto\ColorConversions
 */
class HSV
{
    /**
     * @var int
     */
    protected int $hue;

    /**
     * @var int
     */
    protected int $saturation;

    /**
     * @var int
     */
    protected int $value;

    /**
     * @param int $hue
     * @param int $saturation
     * @param int $value
     *
     * @throws ColorException
     */
    public function __construct(int $hue = 0, int $saturation = 0, int $value = 0)
    {
        $this->setHSV($hue, $saturation, $value);
    }

    /**
     * Sets HSV color code.
     *
     * @param int $hue 360°
     * @param int $saturation %
     * @param int $value %
     *
     * @return HSV
     * @throws ColorException
     */
    public function setHSV(int $hue = 0, int $saturation = 0, int $value = 0): HSV
    {
        $this->setHue($hue);
        $this->setSaturation($saturation);
        $this->setValue($value);

        return $this;
    }

    /**
     * Gets HSV colors.
     *
     * @return int[]
     */
    public function getHSV(): array
    {
        return [
            'hue' => $this->hue,
            'saturation' => $this->saturation,
            'value' => $this->value
        ];
    }

    /**
     * Sets the hue color degrees.
     *
     * @param int $hue
     *
     * @return HSV
     * @throws ColorException
     */
    public function setHue(int $hue): HSV
    {
        if ($hue < 0 || $hue > 360) {
            throw new ColorException('hue', $hue, 0, 360);
        }
        $this->hue = $hue;

        return $this;
    }

    /**
     * Gets the hue color degrees.
     *
     * @return int
     */
    public function getHue(): int
    {
        return $this->hue;
    }

    /**
     * Sets the saturation percentage.
     *
     * @param int $saturation
     *
     * @return HSV
     * @throws ColorException
     */
    public function setSaturation(int $saturation): HSV
    {
        if ($saturation < 0 || $saturation > 100) {
            throw new ColorException('saturation', $saturation, 0, 100);
        }
        $this->saturation = $saturation;

        return $this;
    }

    /**
     * Gets the saturation percentage.
     *
     * @return int
     */
    public function getSaturation(): int
    {
        return $this->saturation;
    }

    /**
     * Sets the value percentage.
     *
     * @param int $value
     *
     * @return HSV
     * @throws ColorException
     */
    public function setValue(int $value): HSV
    {
        if ($value < 0 || $value > 100) {
            throw new ColorException('value', $value, 0, 100);
        }
        $this->value = $value;

        return $this;
    }

    /**
     * Gets the value percentage.
     *
     * @return int
     */
    public function getValue(): int
    {
        return $this->value;
    }

    /**
     * Converts CMYK color format to HSV color format.
     *
     * @param CMYK $cmyk
     *
     * @return HSV
     * @throws ColorException
     */
    public function fromCMYK(CMYK $cmyk): HSV
    {
        return $this->fromRGB($cmyk->toRGB());
    }

    /**
     * Converts HSV color format to CMYK color format.
     *
     * @return CMYK
     * @throws ColorException
     */
    public function toCMYK(): CMYK
    {
        $cmyk = new CMYK();
        $cmyk->fromHSV($this);

        return $cmyk;
    }

    /**
     * Converts HTML color format to HSV color format.
     *
     * @param HTML $html
     *
     * @return HSV
     * @throws ColorException
     */
    public function fromHTML(HTML $html): HSV
    {
        return $this->fromRGB($html->toRGB());
    }

    /**
     * Converts HSV color format to HTML color format.
     *
     * @return HTML
     */
    public function toHTML(): HTML
    {
        $html = new HTML();
        $html->fromHSV($this);

        return $html;
    }

    /**
     * Converts RGB color format to HSV color format.
     *
     * @param RGB $rgb
     *
     * @return HSV
     * @throws ColorException
     */
    public function fromRGB(RGB $rgb): HSV
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
     * Converts HSV color format to RGB color format.
     *
     * @return RGB
     */
    public function toRGB(): RGB
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
            }

            $red = round($red * 255);
            $green = round($green * 255);
            $blue = round($blue * 255);
        }

        return new RGB($red, $green, $blue);
    }
}
