<?php

use BitPayKeyUtils\KeyHelper\Key;
use BitPayKeyUtils\Storage\FilesystemStorage;
use PHPUnit\Framework\TestCase;

class FilesystemStorageTest extends TestCase
{
    public function testInstanceOf()
    {
        $filesystemStorage = $this->createClassObject();

        $this->assertInstanceOf(FilesystemStorage::class, $filesystemStorage);
    }

    public function testPersist()
    {
        $filesystemStorage = $this->createClassObject();
        $keyInterface = $this->getMockBuilder(Key::class)->getMock();
        $keyInterface->method('getId')->willReturn(__DIR__ . '/test1.txt');
        $this->assertFileExists(__DIR__ . '/test1.txt');
        @chmod(__DIR__ . 'test1.txt', 0777);
        $this->assertEquals(null, $filesystemStorage->persist($keyInterface));
    }

    public function testLoadNotFindException()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Could not find "'.__DIR__.'/test2.txt"');

        $filesystemStorage = $this->createClassObject();
        $filesystemStorage->load(__DIR__ . '/test2.txt');
    }

    protected function tearDown(): void
    {
        @chmod(__DIR__ . '/test3.txt', 0755);
    }

    public function testLoadNotPermissionException()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('"test3.txt" cannot be read, check permissions');

        @chmod(__DIR__ . '/test3.txt', 0755);
        $this->tearDown();

        $filesystemStorage = $this->createClassObject();
        $filesystemStorage->load(__DIR__ . '/test3.txt');
    }

    private function createClassObject()
    {
        return new FilesystemStorage();
    }
}
