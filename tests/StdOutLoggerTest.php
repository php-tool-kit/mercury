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

/**
 * Description of StdOutLoggerTest
 *
 * @author Everton
 */
class StdOutLoggerTest extends PHPUnit\Framework\TestCase
{
    public function testConstruct()
    {
        $this->assertInstanceOf(\PTK\Mercury\Logger\StdOutLogger::class, new \PTK\Mercury\Logger\StdOutLogger());
    }
    
    public function testAlert()
    {
        $logger = new \PTK\Mercury\Logger\StdOutLogger();

        $message = 'Hello world';
        
        $this->expectOutputString("[alert]\tHello world");
        $logger->alert($message);
    }
    
    public function testCritical()
    {
        $logger = new \PTK\Mercury\Logger\StdOutLogger();

        $message = 'Hello world';
        
        $this->expectOutputString("[critical]\tHello world");
        $logger->critical($message);
    }
    
    public function testDebug()
    {
        $logger = new \PTK\Mercury\Logger\StdOutLogger();

        $message = 'Hello world';
        
        $this->expectOutputString("[debug]\tHello world");
        $logger->debug($message);;
    }
    
    public function testEmergency()
    {
        $logger = new \PTK\Mercury\Logger\StdOutLogger();

        $message = 'Hello world';
        
        $this->expectOutputString("[emergency]\tHello world");
        $logger->emergency($message);
    }

    public function testError()
    {
        $logger = new \PTK\Mercury\Logger\StdOutLogger();

        $message = 'Hello world';
        
        $this->expectOutputString("[error]\tHello world");
        $logger->error($message);
    }

    public function testInfo()
    {
        $logger = new \PTK\Mercury\Logger\StdOutLogger();

        $message = 'Hello world';
        
        $this->expectOutputString("[info]\tHello world");
        $logger->info($message);
    }

    public function testNotice()
    {
        $logger = new \PTK\Mercury\Logger\StdOutLogger();

        $message = 'Hello world';
        
        $this->expectOutputString("[notice]\tHello world");
        $logger->notice($message);
    }

    public function testWarning()
    {
        $logger = new \PTK\Mercury\Logger\StdOutLogger();

        $message = 'Hello world';
        
        $this->expectOutputString("[warning]\tHello world");
        $logger->warning($message);
    }
    
    public function testLog()
    {
        $logger = new \PTK\Mercury\Logger\StdOutLogger();

        $message = 'Hello world';
        
        $this->expectOutputString("[info]\tHello world");
        $logger->log(\Psr\Log\LogLevel::INFO, $message);
    }
    
    public function testApplyContext()
    {
        $logger = new \PTK\Mercury\Logger\StdOutLogger();

        $message = '{token1} {token2} test';
        
        $this->expectOutputString("[info]\thello world test");
        $logger->log(\Psr\Log\LogLevel::INFO, $message, [
            'token1' => 'hello',
            'token2' => 'world'
        ]);
    }
}
