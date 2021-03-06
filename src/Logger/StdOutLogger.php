<?php

/*
 * The MIT License
 *
 * Copyright 2020 Everton.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */
namespace PTK\Mercury\Logger;

/**
 * Logger na STDOUT
 *
 * @author Everton
 */
class StdOutLogger implements \Psr\Log\LoggerInterface
{

    public function alert($message, array $context = []): void
    {
        $this->log(\Psr\Log\LogLevel::ALERT, $message, $context);
    }

    public function critical($message, array $context = []): void
    {
        $this->log(\Psr\Log\LogLevel::CRITICAL, $message, $context);
    }

    public function debug($message, array $context = []): void
    {
        $this->log(\Psr\Log\LogLevel::DEBUG, $message, $context);
    }

    public function emergency($message, array $context = []): void
    {
        $this->log(\Psr\Log\LogLevel::EMERGENCY, $message, $context);
    }

    public function error($message, array $context = []): void
    {
        $this->log(\Psr\Log\LogLevel::ERROR, $message, $context);
    }

    public function info($message, array $context = []): void
    {
        $this->log(\Psr\Log\LogLevel::INFO, $message, $context);
    }

    public function log($level, $message, array $context = []): void
    {
        /*switch ($level) {
            case \Psr\Log\LogLevel::ALERT:
            case \Psr\Log\LogLevel::EMERGENCY:
            case \Psr\Log\LogLevel::WARNING:
            case \Psr\Log\LogLevel::ERROR:
            case \Psr\Log\LogLevel::CRITICAL:
                $handle = fopen('php://stderr', 'r');
                break;
            case \Psr\Log\LogLevel::INFO:
            case \Psr\Log\LogLevel::NOTICE:
            case \Psr\Log\LogLevel::DEBUG:
                $handle = fopen('php://stdin', 'r');
                break;
            default :
                throw new InvalidValueException($level, [
                    \Psr\Log\LogLevel::ALERT,
                    \Psr\Log\LogLevel::CRITICAL,
                    \Psr\Log\LogLevel::DEBUG,
                    \Psr\Log\LogLevel::EMERGENCY,
                    \Psr\Log\LogLevel::ERROR,
                    \Psr\Log\LogLevel::INFO,
                    \Psr\Log\LogLevel::NOTICE,
                    \Psr\Log\LogLevel::WARNING
                ]);
        }*/
//        print_r($context);exit;
        if ($context) {
            $message = $this->applyContext($message, $context);
        }

//        fwrite($handle, $message);
        printf("[%s]\t%s", $level, $message);
    }

/**
 *
 * @param string $message
 * @param array<mixed> $context
 * @return string
 */
    protected function applyContext(string $message, array $context): string
    {
        foreach ($context as $key => $value) {
            $message = str_replace('{' . $key . '}', $value, $message);
        }
        
        return $message;
    }

    public function notice($message, array $context = []): void
    {
        $this->log(\Psr\Log\LogLevel::NOTICE, $message, $context);
    }

    public function warning($message, array $context = []): void
    {
        $this->log(\Psr\Log\LogLevel::WARNING, $message, $context);
    }
}
