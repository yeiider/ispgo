<?php

namespace Ispgo\Wiivo\Process;

abstract class ProcessChat
{
    protected string $_option = "";

    abstract public function processMessage(string $body, array $interactions):array;

    public function getOptions($interactions, $option): array
    {
        return array_filter($interactions, function ($item) use ($option) {
            if ($item['option'] === $option) {
                return $item;
            };
        });
    }
}
