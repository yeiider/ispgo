<?php

namespace Ispgo\Wiivo\Process;

abstract class ProcessChat
{
    protected string $responseTemplate = "";
    protected string $_option = "";

    abstract public function processMessage($body):string;
}
