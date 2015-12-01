<?php
/*
Copyright 2002-2012 MarkLogic Corporation.  All Rights Reserved.

Licensed under the Apache License, Version 2.0 (the "License");
you may not use this file except in compliance with the License.
You may obtain a copy of the License at

     http://www.apache.org/licenses/LICENSE-2.0

Unless required by applicable law or agreed to in writing, software
distributed under the License is distributed on an "AS IS" BASIS,
WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
See the License for the specific language governing permissions and
limitations under the License.
*/
namespace MarkLogic\MLPHP;

/**
 * Represents an image.
 *
 * @package MLPHP
 * @author Mike Wooldridge <mike.wooldridge@marklogic.com>
 */
class ImageDocument extends Document
{
    protected $exif; // @var array

    /**
     * Create an Image object.
     *
     * @param RESTClient $client A REST client object.
     * @param string $uri A document URI.
     */
    public function __construct($client, $uri = null)
    {
        parent::__construct($client, $uri);
    }


    /**
     * Set the image content from the file system and also set the EXIF data.
     *
     * @param string $file The file location.
     */
    public function setContentFile($file)
    {
        $type = $this->getFileMimeType($file);
        // Check for $type === '' to address MIME check not working on XAMPP Windows
        if ($type === 'image/jpeg' || $type === 'image/tiff' || $type === '') {
            if (function_exists('exif_read_data')) {
                $this->exif = exif_read_data((string)$file);
            }
        }
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
