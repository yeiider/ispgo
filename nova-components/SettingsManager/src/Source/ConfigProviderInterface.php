<?php

namespace Ispgo\SettingsManager\Source;

interface ConfigProviderInterface
{
    static public function getConfig(): array;
}
