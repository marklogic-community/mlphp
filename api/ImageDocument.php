<?php

require_once('Document.php');

/**
 * Represents an image.
 *
 * @package mlphp\document
 * @author Mike Wooldridge <mike.wooldridge@marklogic.com>
 */
class ImageDocument extends Document
{
    protected $exif; // @var array

    /**
     * Create an Image object.
     *
     * @param RESTClient $restClient A REST client object.
     * @param string $uri A document URI.
     */
    public function __construct($restClient, $uri = null)
    {
        parent::__construct($restClient, $uri);
    }


    /**
     * Set the image content from the file system and also set the EXIF data.
     *
     * @param string $file The file location.
     */
    public function setContentFile($file)
    {
        $this->exif = exif_read_data((string)$file);
        parent::setContentFile((string)$file);
    }

    /**
     * Get the image EXIF metadata.
     *
     * @return array The EXIF data.
     */
    public function getExif()
    {
        return $this->exif;
    }
}