<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Mensahe\Lib\RegisterRequestLib;

final class RegisterRequestTest extends TestCase
{
    public function testValidateUsernameValidEmail(): void
    {
        $data = ['username' => 'user@example.com'];
        $result = RegisterRequestLib::validateUsername($data);
        $this->assertSame('user@example.com', $result);
    }

    public function testValidateUsernameValidEmailWithSubdomain(): void
    {
        $data = ['username' => 'user@sub.example.com'];
        $result = RegisterRequestLib::validateUsername($data);
        $this->assertSame('user@sub.example.com', $result);
    }

    public function testValidateUsernameInvalidEmail(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Invalid email address');
        RegisterRequestLib::validateUsername(['username' => 'invalid-email']);
    }

    public function testValidateUsernameInvalidEmailNoAt(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Invalid email address');
        RegisterRequestLib::validateUsername(['username' => 'user.example.com']);
    }

    public function testValidateUsernameInvalidEmailNoDomain(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Invalid email address');
        RegisterRequestLib::validateUsername(['username' => 'user@']);
    }

    public function testValidateUsernameInvalidEmailNoLocalPart(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Invalid email address');
        RegisterRequestLib::validateUsername(['username' => '@example.com']);
    }

    public function testValidateUsernameInvalidEmpty(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Invalid email address');
        RegisterRequestLib::validateUsername(['username' => '']);
    }

    public function testValidateUsernameMissing(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Invalid email address');
        RegisterRequestLib::validateUsername([]);
    }

    public function testGetRequestDataInvalidJson(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Invalid JSON');
        RegisterRequestLib::getRequestData('not-json');
    }
}

// Helper class to mock php://input for testing
class MockPhpStream {
    private $index = 0;
    private $data;

    public function stream_open($path, $mode, $options, &$opened_path) {
        $this->data = file_get_contents(__DIR__ . '/mock_input.txt');
        return true;
    }

    public function stream_read($count) {
        $ret = substr($this->data, $this->index, $count);
        $this->index += strlen($ret);
        return $ret;
    }

    public function stream_eof() {
        return $this->index >= strlen($this->data);
    }

    public function stream_stat() {
        return [];
    }
}
