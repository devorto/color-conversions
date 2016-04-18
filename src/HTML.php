<?php
/**
 * @author Geoffrey Dijkstra
 */
namespace gdwebs\ColorFormats;

/**
 * Class HTML
 * @package gdwebs\Colors
 */
class HTML
{
    /** @var string HTML hex code without "#" */
    protected $htmlColorCode;
    /** @var array */
    protected static $named = [
        'white' => 'ffffff',
        'black' => '000000',
        'red' => 'ff0000',
        'blue' => '0000ff',
        'green' => '00ff00'
    ];

    /**
     * HTML constructor
     *
     * @param string $htmlColorCode
     */
    public function __construct($htmlColorCode = '#000000')
    {
        $this->setHTML($htmlColorCode);
    }

    /**
     * Sets HTML color code
     *
     * @param string $htmlColorCode Code or HTML name
     * @throws ColorException
     * @return self
     */
    public function setHTML($htmlColorCode)
    {
        $htmlColorCode = strtolower(trim($htmlColorCode));
        if (substr($htmlColorCode, 0, 1) != '#' && array_key_exists($htmlColorCode, self::$named)) {
            $htmlColorCode = self::$named[$htmlColorCode];
        } else {
            $htmlColorCode = trim($htmlColorCode, '#');
            // Assuming short syntax
            if (strlen($htmlColorCode) == 3) {
                $pieces = str_split($htmlColorCode, 1);
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
     * Gets HTML color code
     *
     * @param bool $includeHashtag
     * @return string
     */
    public function getHTML($includeHashtag = true)
    {
        return ($includeHashtag ? '#' : '') . $this->htmlColorCode;
    }

    /**
     * Converts CMYK color format to HTML color format
     *
     * @param CMYK $cmyk
     * @return self
     */
    public function fromCMYK(CMYK $cmyk)
    {
        $this->fromRGB($cmyk->toRGB());

        return $this;
    }

    /**
     * Converts HTML color format to CMYK color format
     *
     * @return CMYK
     */
    public function toCMYK()
    {
        $cmyk = new CMYK();
        $cmyk->fromHTML($this);

        return $cmyk;
    }

    /**
     * Converts RGB color format to HTML color format
     *
     * @param RGB $rgb
     * @return self
     * @throws ColorException
     */
    public function fromRGB(RGB $rgb)
    {
        $red = str_pad(dechex($rgb->getRed()), 2, '0', STR_PAD_LEFT);
        $green = str_pad(dechex($rgb->getGreen()), 2, '0', STR_PAD_LEFT);
        $blue = str_pad(dechex($rgb->getBlue()), 2, '0', STR_PAD_LEFT);

        $this->setHTML(implode('', [$red, $green, $blue]));

        return $this;
    }

    /**
     * Converts HTML color format to RGB color format
     *
     * @return RGB
     */
    public function toRGB()
    {
        $rgb = new RGB();
        $rgb->fromHTML($this);

        return $rgb;
    }

    /**
     * Converts HSV color format to HTML color format
     *
     * @param HSV $hsv
     * @return HTML
     */
    public function fromHSV(HSV $hsv)
    {
        return $this->fromRGB($hsv->toRGB());
    }

    /**
     * Converts HTML color format to HSV color format
     *
     * @return HSV
     */
    public function toHSV()
    {
        $hsv = new HSV();
        $hsv->fromHTML($this);
        
        return $hsv;
    }
}
