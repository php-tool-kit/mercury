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

    /**
     * Registra um logger para receber as mensagens.
     *
     * @param LoggerInterface $logger
     * @param string $loggerId
     * @return PSRMessenger
     */
    public function registerLogger(LoggerInterface $logger, string $loggerId = ''): PSRMessenger
    {
        if ($loggerId === '') {
            $loggerId = Uid::text(8);
        }
        $this->loggers[$loggerId] = $logger;
        return $this;
    }

    /**
     * Lista os loggers registrados.
     *
     * @return array<LoggerInterface>
     */
    public function listLoggers(): array
    {
        return $this->loggers;
    }

    /**
     * Remove um logger da lista de loggers registrados.
     *
     * @param string $loggerId
     * @return PSRMessenger
     * @throws OutOfBoundsException
     */
    public function unregisterLogger(string $loggerId): PSRMessenger
    {
        if ($this->hasLogger($loggerId) === false) {
            throw new OutOfBoundsException($loggerId, array_keys($this->loggers));
        }

        unset($this->loggers[$loggerId]);
        
        return $this;
    }
    
    /**
     * Retorna um logger específico.
     *
     * @param string $loggerId
     * @return LoggerInterface
     * @throws OutOfBoundsException
     */
    public function getLogger(string $loggerId): LoggerInterface
    {
        if ($this->hasLogger($loggerId) === false) {
            throw new OutOfBoundsException($loggerId, array_keys($this->loggers));
        }
        
        return $this->loggers[$loggerId];
    }
    
    /**
     * Verifica se um logger está registrado.
     *
     * @param string $loggerId
     * @return bool
     */
    public function hasLogger(string $loggerId): bool
    {
        return key_exists($loggerId, $this->loggers);
    }

    /**
     * Envia a mensagem para os loggers.
     *
     * @param string $level
     * @param mixed $message
     * @param array<mixed> $context
     * @return void
     */
    protected function notify(string $level, $message, array $context): void
    {
        foreach ($this->loggers as $logger) {
            $logger->log($level, $message, $context);
        }
    }

    /**
     *
     * @param mixed $message
     * @param array<mixed> $context
     * @return void
     */
    public function sendAlert($message, array $context = []): void
    {
        $this->notify(LogLevel::ALERT, $message, $context);
    }

    /**
     *
     * @param mixed $message
     * @param array<mixed> $context
     * @return void
     */
    public function sendCritical($message, array $context = []): void
    {
        $this->notify(LogLevel::CRITICAL, $message, $context);
    }

    /**
     *
     * @param mixed $message
     * @param array<mixed> $context
     * @return void
     */
    public function sendDebug($message, array $context = []): void
    {
        //só emite a mensagem se o modo debug estiver ativado.
        if (
            key_exists('DEBUG', $this->environment) === true
            && $this->environment['DEBUG'] === true
        ) {
            $this->notify(LogLevel::DEBUG, $message, $context);
        }
    }

    /**
     *
     * @param mixed $message
     * @param array<mixed> $context
     * @return void
     */
    public function sendEmergency($message, array $context = []): void
    {
        $this->notify(LogLevel::EMERGENCY, $message, $context);
    }

    /**
     *
     * @param mixed $message
     * @param array<mixed> $context
     * @return void
     */
    public function sendError($message, array $context = []): void
    {
        $this->notify(LogLevel::ERROR, $message, $context);
    }

    /**
     *
     * @param mixed $message
     * @param array<mixed> $context
     * @return void
     */
    public function sendInfo($message, array $context = []): void
    {
        $this->notify(LogLevel::INFO, $message, $context);
    }

    /**
     *
     * @param mixed $message
     * @param array<mixed> $context
     * @return void
     */
    public function sendNotice($message, array $context = []): void
    {
        $this->notify(LogLevel::NOTICE, $message, $context);
    }

    /**
     *
     * @param mixed $message
     * @param array<mixed> $context
     * @return void
     */
    public function sendWarning($message, array $context = []): void
    {
        $this->notify(LogLevel::WARNING, $message, $context);
    }
}
