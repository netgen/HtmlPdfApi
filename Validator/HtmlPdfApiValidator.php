<?php

namespace Netgen\HtmlPdfApi\Validator;

use Netgen\HtmlPdfApi\ValidatorInterface;
use Netgen\HtmlPdfApi\Exception\FileNotFoundException;
use Netgen\HtmlPdfApi\Exception\InvalidParameterException;
use Netgen\HtmlPdfApi\Exception\WrongFileExtensionException;

class HtmlPdfApiValidator implements ValidatorInterface {

    /**
     * Validates request parameters for HtmlPdfApi
     *
     * @param array $params     Parameters to validate
     * @return array $params    Validated parameters
     * @throws \Netgen\HtmlPdfApi\Exception\InvalidParameterException
     */
    public function validate($params)
    {
        $params = $this->boolParams($params);

        if (!empty($params['html']) && (!empty($params['url']) || !empty($params['file'])))
            throw new InvalidParameterException("Only one of [html, url, file] parameters can be set!");

        if (!empty($params['url']) && !empty($params['file']))
            throw new InvalidParameterException("Only one of [html, url, file] parameters can be set!");

        if ((!empty($params['page_width']) && empty($params['page_height']))
            ||(empty($params['page_width']) && !empty($params['page_height'])))
        {
            throw new InvalidParameterException('Page width and page height must both be set or unset!');
        }
        if (!empty($params['filename']) && !is_string($params['filename']))
            throw new InvalidParameterException('Filename must be a string.');

        if (!empty($params['footer']) && !is_string($params['footer']))
            throw new InvalidParameterException('Footer must be a string.');

        if (!empty($params['header']) && !is_string($params['header']))
            throw new InvalidParameterException('Header must be a string.');

        if (!empty($params['header_spacing']) && !is_int($params['header_spacing']))
            throw new InvalidParameterException('Header spacing parameter must be an integer.');

        if (!empty($params['footer_spacing']) && !is_int($params['footer_spacing']))
            throw new InvalidParameterException('Footer spacing parameter must be an integer.');

        if (!empty($params['page_size']) && !is_string($params['page_size']))
            throw new InvalidParameterException('Page size must be a string of some standard page size.');

        if (!empty($params['dpi']) && (!is_int($params['dpi']) || $params['dpi']<75))
            throw new InvalidParameterException('DPI must be an integer larger than 75.');

        if (!empty($params['image_dpi']) && !is_int($params['image_dpi']))
            throw new InvalidParameterException('Image DPI must be an integer.');

        if (!empty($params['lowquality']) && (!is_int($params['lowquality']) || !in_array($params['lowquality'], array(
                    0, 1 ) )))
            throw new InvalidParameterException(
                'Lowquality must be either 0 or 1 (alternative: use bool values');

        if (!empty($params['orientation']) && (!is_string($params['orientation']) || !in_array($params['orientation'],
                array("landscape", "portrait"))))
            throw new InvalidParameterException('Orientation must be set to either "landscape" or "portrait"');

        if (!empty($params['margin_top']) && !is_int($params['margin_top']))
            throw new InvalidParameterException('Margin top must be an integer.');

        if (!empty($params['margin_bottom']) && !is_int($params['margin_bottom']))
            throw new InvalidParameterException('Margin top must be an integer.');

        if (!empty($params['margin_left']) && !is_int($params['margin_left']))
            throw new InvalidParameterException('Margin top must be an integer.');

        if (!empty($params['margin_right']) && !is_int($params['margin_right']))
            throw new InvalidParameterException('Margin top must be an integer.');

        if (!empty($params['background']) && (!is_int($params['background']) || !in_array($params['background'], array(
                    0, 1 ) )))
            throw new InvalidParameterException(
                'Background must be either 0 or 1 (alternative: use bool values');

        if (!empty($params['images']) && (!is_int($params['images']) || !in_array($params['images'], array(
                    0, 1 ) )))
            throw new InvalidParameterException(
                'Images must be either 0 or 1 (alternative: use bool values');

        if (!empty($params['page_width']) && !is_int($params['page_width']))
            throw new InvalidParameterException('Page width must be an integer.');

        if (!empty($params['page_height']) && !is_int($params['page_height']))
            throw new InvalidParameterException('Page height must be an integer.');

        if (!empty($params['disposition']) && (!is_string($params['disposition']) || !in_array($params['disposition'],
                    array("attachment", "inline"))))
            throw new InvalidParameterException('Disposition can only be "attachment or "inline"');

        if (!empty($params['group']) && !is_string($params['group']))
            throw new InvalidParameterException('Group must be a string.');

        if (!empty($params['engine_version']) && (!is_int($params['engine_version']) || !in_array($params['engine_version'],
        array( 11, 12 ) )))
            throw new InvalidParameterException(
                'Engine version can be integer 11 or 12');

        if (!empty($params['title']) && !is_string($params['title']))
            throw new InvalidParameterException('Title must be a string.');

        if (!empty($params['outline']) && (!is_int($params['outline']) || !in_array($params['outline'], array(
                    0, 1 ) )))
            throw new InvalidParameterException(
                'Outline must be either 0 or 1 (alternative: use bool values');

        if (!empty($params['outline_depth']) && !is_int($params['outline_depth']))
            throw new InvalidParameterException('Outline depth must be an integer.');

        if (!empty($params['encoding']) && !is_string($params['encoding']))
            throw new InvalidParameterException('Encoding must be a string.');

        if (!empty($params['javascript']) && (!is_int($params['javascript']) || !in_array($params['javascript'], array(
                    0, 1 ) )))
            throw new InvalidParameterException(
                'Javascript must be either 0 or 1 (alternative: use bool values');

        if (!empty($params['javascript_delay']) &&
            (!is_int($params['javascript_delay']) || $params['javascript_delay']<1 || $params['javascript_delay']>800))
            throw new InvalidParameterException('Javascript delay must be an integer larger than 1, but smaller than 800.');

        if (!empty($params['internal_links']) &&
            (!is_int($params['internal_links']) || !in_array($params['internal_links'], array( 0, 1 ) )))
            throw new InvalidParameterException(
                'Internal links must be either 0 or 1 (alternative: use bool values');

        if (!empty($params['external_links']) &&
            (!is_int($params['external_links']) || !in_array($params['external_links'], array( 0, 1 ) )))
            throw new InvalidParameterException(
                'External links must be either 0 or 1 (alternative: use bool values');

        if (!empty($params['page_offset']) && !is_int($params['page_offset']))
            throw new InvalidParameterException('Page offset must be an integer.');

        if (!empty($params['username']) && !is_string($params['username']))
            throw new InvalidParameterException('Username must be a string.');

        if (!empty($params['password']) && !is_string($params['password']))
            throw new InvalidParameterException('Password must be a string.');

        if (!empty($params['use_print_media']) &&
            (!is_int($params['use_print_media']) || !in_array($params['use_print_media'], array(
                    0, 1 ) )))
            throw new InvalidParameterException(
                'Use print media must be either 0 or 1 (alternative: use bool values');

        if (!empty($params['zoom']) && !is_numeric($params['zoom']))
            throw new InvalidParameterException('Zoom must be an integer.');

        if (!empty($params['viewport_size']) &&
            (!is_string($params['viewport_size'] || preg_match("^\\d+x\\d+$", $params['viewport_size']))))
            throw new InvalidParameterException('Viewport must be a string of a following format: [width]x[height].');

        if (!empty($params['id']) && !is_string($params['id']))
            throw new InvalidParameterException('ID must be a string.');

        /*if (!empty($params['file']))
            $this->validateFile($params['file']);*/

        return $params;
    }

    /**
     * Checks if asset exists at provided path, and is of supported format
     *
     * @param string $file     Path to file (prefixed with @)
     *
     * @throws \Netgen\HtmlPdfApi\Exception\FileNotFoundException
     * @throws \Netgen\HtmlPdfApi\Exception\WrongFileExtensionException
     */
    public function validateAssetFile($file)
    {
        $filePath = substr($file, 1);

        if (!is_file($filePath))
            throw new FileNotFoundException("File ".$filePath." was not found");

        $path_parts = pathinfo($filePath);

        if( !in_array($path_parts['extension'], array(
            "js", "css", "png", "jpg", "jpeg", "gif", "ttf", "otf", "woff"
        )))
            throw new WrongFileExtensionException("Wrong file format\n
            Supported formats: js, css, png, jpg, jpeg, gif, ttf, otf, woff");
    }

    /**
     * Checks if file exists at provided path, and is of supported format
     *
     * @param string $file     Path to file (prefixed with @)
     *
     * @throws \Netgen\HtmlPdfApi\Exception\FileNotFoundException
     * @throws \Netgen\HtmlPdfApi\Exception\WrongFileExtensionException
     */
    public function validateHtmlFile($file)
    {
        $filePath = substr($file, 1);

        if (!is_file($filePath))
            throw new FileNotFoundException("File ".$filePath." was not found");

        $path_parts = pathinfo($filePath);

        if( !in_array($path_parts['extension'], array(
            "html", "htm", "zip", "tar.gz", "tgz", "tar.bz2"
        )))
            throw new WrongFileExtensionException("Wrong file format\n
            Supported formats: html, htm, zip, tar.gz, tgz, tar.bz2");
    }

    /**
     * Casts bool parameters to integer if set to false
     *
     * @param array $params Parameters
     * @return array $params
     */
    private function boolParams($params)
    {
        if (isset($params['lowquality']) && $params['lowquality']===false)
            $params['lowquality'] = (int) $params['lowquality'];

        if (isset($params['images']) && $params['images']===false)
            $params['images'] = (int) $params['images'];

        if (isset($params['outline']) && $params['outline']===false)
            $params['outline'] = (int) $params['outline'];

        if (isset($params['javascript']) && $params['javascript']===false)
            $params['javascript'] = (int) $params['javascript'];

        if (isset($params['internal_links']) && $params['internal_links']===false)
            $params['internal_links'] = (int) $params['internal_links'];

        if (isset($params['external_links']) && $params['external_links']===false)
            $params['external_links'] = (int) $params['external_links'];

        if (isset($params['use_print_media']) && $params['use_print_media']===false)
            $params['use_print_media'] = (int) $params['use_print_media'];

        if (isset($params['background']) && $params['background']===false)
            $params['background'] = (int) $params['background'];

        return $params;
    }
} 