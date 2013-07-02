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

use MarkLogic\MLPHP;

/**
 * Represents an iPhone image.
 * @package mlphp\document
 */
class IPhoneImageDocument extends MLPHP\ImageDocument
{
    /**
     * Create an iPhone Image object.
     *
     * @param RESTClient $restClient A REST client object.
     * @param string $uri A document URI.
     */
    public function __construct($restClient, $uri = null)
    {
        parent::__construct($restClient, $uri);
    }

    /**
     * Get the latitude metadata.
     *
     * @return float The latitude as a single numeric value.
     */
    public function getLatitude()
    {
        if (isset($this->exif)) {
            return (float)$this->getGps($this->exif['GPSLatitude'], $this->exif['GPSLatitudeRef']);
        } else {
            return null;
        }
    }

    /**
     * Get the longitude metadata.
     *
     * @return float The longitude as a single numeric value.
     */
    public function getLongitude()
    {
        if (isset($this->exif)) {
            return (float)$this->getGps($this->exif['GPSLongitude'], $this->exif['GPSLongitudeRef']);
        } else {
            return null;
        }
    }

    /**
     * Get the height dimension of the image.
     *
     * @return int The height in pixels.
     */
    public function getHeight()
    {
        if (isset($this->exif)) {
            return (int)$this->exif['ExifImageLength'];
        } else {
            return null;
        }
    }

    /**
     * Get the width dimension of the image.
     *
     * @return int The width in pixels.
     */
    public function getWidth()
    {
        if (isset($this->exif)) {
            return (int)$this->exif['ExifImageWidth'];
        } else {
            return null;
        }
    }

    /**
     * Get the filename of the image.
     *
     * @return string The filename.
     */
    public function getFilename()
    {
        if (isset($this->exif)) {
            return (string)$this->exif['FileName'];
        } else {
            return null;
        }
    }

    /**
     * Get the date taken of the image.
     *
     * @return string The date taken.
     */
    public function getDate()
    {
        if (isset($this->exif)) {
            return (string)$this->exif['DateTimeOriginal'];
        } else {
            return null;
        }
    }

    /**
     * Convert a GPS coordinate from degrees, minutes, seconds, and direction to a single numeric value.
     * @see http://stackoverflow.com/questions/2526304/php-extract-gps-exif-data
     * @example 57 deg 38' 56.83" N is converted to 57.64911
     *
     * @param array The GPSLatitude or GPSLongitude EXIF information.
     * @param string $hemi The GPS direction information.
     * @return float The GPS coordinate as a single numeric value.
     */
    public function getGps($exifCoord, $hemi)
    {
        $degrees = count($exifCoord) > 0 ? $this->gps2Num($exifCoord[0]) : 0;
        $minutes = count($exifCoord) > 1 ? $this->gps2Num($exifCoord[1]) : 0;
        $seconds = count($exifCoord) > 2 ? $this->gps2Num($exifCoord[2]) : 0;

        $flip = ($hemi == 'W' or $hemi == 'S') ? -1 : 1;

        return $flip * ($degrees + $minutes / 60 + $seconds / 3600);
    }

    /**
     * Convert degree, minutes, or seconds data.
     *
     * @param string $coordPart Coordinate data.
     * @return float The coordinate data as a single numeric value.
     */
    public function gps2Num($coordPart)
    {
        $parts = explode('/', $coordPart);

        if (count($parts) <= 0)
            return 0;

        if (count($parts) == 1)
            return $parts[0];

        return floatval($parts[0]) / floatval($parts[1]);
    }
}
