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
 * Testes para PTK\Mercury\PSRMessengerTest
 *
 * @author Everton
 */
class PSRMessengerTest extends PHPUnit\Framework\TestCase
{
    public function testConstruct()
    {
        $this->assertInstanceOf(\PTK\Mercury\PSRMessenger::class, new \PTK\Mercury\PSRMessenger());
    }
    
    public function testRegisterLoggerWithId()
    {
        $messenger = new \PTK\Mercury\PSRMessenger();
        
        $logger = new \PTK\Mercury\Logger\StdOutLogger();
        
        $this->assertInstanceOf(\PTK\Mercury\PSRMessenger::class, $messenger->registerLogger($logger, 'test'));
        
        $loggerRegistered = $messenger->getLogger('test');
        
        $this->assertEquals($logger, $loggerRegistered);
    }
    
    public function testRegisterLoggerWithNoId()
    {
        $messenger = new \PTK\Mercury\PSRMessenger();
        
        $logger = new \PTK\Mercury\Logger\StdOutLogger();
        
        $this->assertInstanceOf(\PTK\Mercury\PSRMessenger::class, $messenger->registerLogger($logger));
        
        $id = array_key_first($messenger->listLoggers());
        $loggerRegistered = $messenger->getLogger($id);
        
        $this->assertEquals($logger, $loggerRegistered);
    }
    
    public function testListLoggers()
    {
        $messenger = new \PTK\Mercury\PSRMessenger();
        
        $logger1 = new \PTK\Mercury\Logger\StdOutLogger();
        $logger2 = new \PTK\Mercury\Logger\StdOutLogger();
        $logger3 = new \PTK\Mercury\Logger\StdOutLogger();
        
        $messenger->registerLogger($logger1, 'test1');
        $messenger->registerLogger($logger2, 'test2');
        $messenger->registerLogger($logger3, 'test3');
        
        $this->assertSame([
            'test1' => $logger1,
            'test2' => $logger2,
            'test3' => $logger3
        ], $messenger->listLoggers());
    }
    
    public function testUnregisterLogger()
    {
        $messenger = new \PTK\Mercury\PSRMessenger();
        
        $logger1 = new \PTK\Mercury\Logger\StdOutLogger();
        $logger2 = new \PTK\Mercury\Logger\StdOutLogger();
        $logger3 = new \PTK\Mercury\Logger\StdOutLogger();
        
        $messenger->registerLogger($logger1, 'test1');
        $messenger->registerLogger($logger2, 'test2');
        $messenger->registerLogger($logger3, 'test3');
        
        $messenger->unregisterLogger('test2');
        
        $this->assertSame([
            'test1' => $logger1,
            'test3' => $logger3
        ], $messenger->listLoggers());
    }
    
    public function testUnregisterLoggerOutOfBoudsException()
    {
        $messenger = new \PTK\Mercury\PSRMessenger();
        
        $this->expectException(PTK\Exceptlion\Value\OutOfBoundsException::class);
        $messenger->unregisterLogger('test2');
        
    }
    
    public function testGetLogger()
    {
        $messenger = new \PTK\Mercury\PSRMessenger();
        
        $logger1 = new \PTK\Mercury\Logger\StdOutLogger();
        $logger2 = new \PTK\Mercury\Logger\StdOutLogger();
        $logger3 = new \PTK\Mercury\Logger\StdOutLogger();
        
        $messenger->registerLogger($logger1, 'test1');
        $messenger->registerLogger($logger2, 'test2');
        $messenger->registerLogger($logger3, 'test3');
        
        $this->assertEquals($logger2, $messenger->getLogger('test2'));
    }
    
    public function testGetLoggerOutOfBoundsException()
    {
        $messenger = new \PTK\Mercury\PSRMessenger();
        
        $this->expectException(PTK\Exceptlion\Value\OutOfBoundsException::class);
        $messenger->getLogger('test');
    }
    
    public function testHasLogger()
    {
        $messenger = new \PTK\Mercury\PSRMessenger();
        
        $logger1 = new \PTK\Mercury\Logger\StdOutLogger();
        $logger2 = new \PTK\Mercury\Logger\StdOutLogger();
        $logger3 = new \PTK\Mercury\Logger\StdOutLogger();
        
        $messenger->registerLogger($logger1, 'test1');
        $messenger->registerLogger($logger2, 'test2');
        $messenger->registerLogger($logger3, 'test3');
        
        $this->assertTrue($messenger->hasLogger('test2'));
        $this->assertFalse($messenger->hasLogger('test4'));
    }
    
    public function testAlert()
    {
        $messenger = new \PTK\Mercury\PSRMessenger();
        
        $logger = new \PTK\Mercury\Logger\StdOutLogger();

        $messenger->registerLogger($logger, 'test');
        
        $message = 'Hello world';
        
        $this->expectOutputString("[alert]\tHello world");
        $messenger->alert($message);
    }
    
    public function testCritical()
    {
        $messenger = new \PTK\Mercury\PSRMessenger();
        
        $logger = new \PTK\Mercury\Logger\StdOutLogger();

        $messenger->registerLogger($logger, 'test');
        
        $message = 'Hello world';
        
        $this->expectOutputString("[critical]\tHello world");
        $messenger->critical($message);
    }
    
    public function testDebugActive()
    {
        $messenger = new \PTK\Mercury\PSRMessenger(['DEBUG' => true]);
        
        $logger = new \PTK\Mercury\Logger\StdOutLogger();

        $messenger->registerLogger($logger, 'test');
        
        $message = 'Hello world';
        
        $this->expectOutputString("[debug]\tHello world");
        $messenger->debug($message);
    }
    
    public function testDebugInactive()
    {
        $messenger = new \PTK\Mercury\PSRMessenger(['DEBUG' => false]);
        
        $logger = new \PTK\Mercury\Logger\StdOutLogger();

        $messenger->registerLogger($logger, 'test');
        
        $message = 'Hello world';
        
        $this->expectOutputString('');
        $messenger->debug($message);
    }
    
    public function testEmergency()
    {
        $messenger = new \PTK\Mercury\PSRMessenger();
        
        $logger = new \PTK\Mercury\Logger\StdOutLogger();

        $messenger->registerLogger($logger, 'test');
        
        $message = 'Hello world';
        
        $this->expectOutputString("[emergency]\tHello world");
        $messenger->emergency($message);
    }

    public function testError()
    {
        $messenger = new \PTK\Mercury\PSRMessenger();
        
        $logger = new \PTK\Mercury\Logger\StdOutLogger();

        $messenger->registerLogger($logger, 'test');
        
        $message = 'Hello world';
        
        $this->expectOutputString("[error]\tHello world");
        $messenger->error($message);
    }

    public function testInfo()
    {
        $messenger = new \PTK\Mercury\PSRMessenger();
        
        $logger = new \PTK\Mercury\Logger\StdOutLogger();

        $messenger->registerLogger($logger, 'test');
        
        $message = 'Hello world';
        
        $this->expectOutputString("[info]\tHello world");
        $messenger->info($message);
    }

    public function testNotice()
    {
        $messenger = new \PTK\Mercury\PSRMessenger();
        
        $logger = new \PTK\Mercury\Logger\StdOutLogger();

        $messenger->registerLogger($logger, 'test');
        
        $message = 'Hello world';
        
        $this->expectOutputString("[notice]\tHello world");
        $messenger->notice($message);
    }

    public function testWarning()
    {
        $messenger = new \PTK\Mercury\PSRMessenger();
        
        $logger = new \PTK\Mercury\Logger\StdOutLogger();

        $messenger->registerLogger($logger, 'test');
        
        $message = 'Hello world';
        
        $this->expectOutputString("[warning]\tHello world");
        $messenger->warning($message);
    }

}
