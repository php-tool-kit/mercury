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
namespace PTK\Mercury;

use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;
use PTK\Exceptlion\Value\OutOfBoundsException;
use PTK\Uid\Uid;

/**
 * Mensageiro do Mercury baseado na PSR3.
 * 
 * Responsável por gerenciar o recebimento/envio de mensagens aos diferentes 
 * loggers.
 *
 * @author Everton
 */
class PSRMessenger
{

    /**
     *
     * @var array<mixed> Variáveis personalizadas para o ambiente do messenger.
     */
    private array $environment = [];

    /**
     *
     * @var array<\Psr\Log\LoggerInterface> Lista de loggers registrados.
     */
    private array $loggers = [];

    /**
     * 
     * @param array<mixed> $environment Conjunto de variáveis de ambiente do messenger.
     * Um exemplo é a variável DEBUG = true|false, que ativa e desativa o debug.
     * As chaves (array keys) sempre devem estar em maíuscula.
     */
    public function __construct(array $environment = [])
    {
        $this->environment = $environment;
    }

    public function registerLogger(LoggerInterface $logger, string $id = ''): PSRMessenger
    {
        if ($id === '') {
            $id = Uid::text(8);
        }
        $this->loggers[$id] = $logger;
        return $this;
    }

    public function listLoggers(): array
    {
        return $this->loggers;
    }

    public function unregisterLogger(string $id): PSRMessenger
    {
        if ($this->hasLogger($id) === false) {
            throw new OutOfBoundsException($id, array_keys($this->loggers));
        }

        unset($this->loggers[$id]);
        
        return $this;
    }
    
    public function getLogger(string $id): LoggerInterface
    {
        if($this->hasLogger($id) === false){
            throw new OutOfBoundsException($id, array_keys($this->loggers));
        }
        
        return $this->loggers[$id];
    }
    
    public function hasLogger(string $id): bool
    {
        return key_exists($id, $this->loggers);
    }

    protected function notify(string $level, $message, array $context): void
    {
        foreach ($this->loggers as $logger) {
            $logger->log($level, $message, $context);
        }
    }

    public function alert($message, array $context = []): void
    {
        $this->notify(LogLevel::ALERT, $message, $context);
    }

    public function critical($message, array $context = []): void
    {
        $this->notify(LogLevel::CRITICAL, $message, $context);
    }

    public function debug($message, array $context = []): void
    {
        //só emite a mensagem se o modo debug estiver ativado.
        if (
            key_exists('DEBUG', $this->environment) === true 
            && $this->environment['DEBUG'] === true
        ) {
            $this->notify(LogLevel::DEBUG, $message, $context);
        }
    }

    public function emergency($message, array $context = []): void
    {
        $this->notify(LogLevel::EMERGENCY, $message, $context);
    }

    public function error($message, array $context = []): void
    {
        $this->notify(LogLevel::ERROR, $message, $context);
    }

    public function info($message, array $context = []): void
    {
        $this->notify(LogLevel::INFO, $message, $context);
    }

    public function notice($message, array $context = []): void
    {
        $this->notify(LogLevel::NOTICE, $message, $context);
    }

    public function warning($message, array $context = []): void
    {
        $this->notify(LogLevel::WARNING, $message, $context);
    }
}
