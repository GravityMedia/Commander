<?php
/**
 * This file is part of the Commander project.
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\Commander\Console\Helper;

use Symfony\Component\Console\Helper\Helper;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Serializer;

/**
 * Config serializer helper class
 *
 * @package GravityMedia\Commander\Console\Helper
 */
class ConfigSerializerHelper extends Helper
{
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return static::class;
    }

    /**
     * Create serializer
     *
     * @return Serializer
     */
    public function createSerializer()
    {
        $encoders = [new JsonEncoder()];
        $normalizers = [new GetSetMethodNormalizer()];

        return new Serializer($normalizers, $encoders);
    }
}
