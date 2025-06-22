<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../RegisterRequestLib.php';

final class RegisterRequestTest extends TestCase
{
    public function testValidateUsernameValid(): void
    {
        $data = ['username' => 'valid_user123'];
        $result = validateUsername($data);
        $this->assertSame('valid_user123', $result);
    }

    public function testValidateUsernameInvalidEmpty(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Invalid username');
        validateUsername(['username' => '']);
    }

    public function testValidateUsernameInvalidPattern(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Invalid username');
        validateUsername(['username' => 'ab']);
    }

    public function testValidateUsernameMissing(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Invalid username');
        validateUsername([]);
    }

    public function testGetRequestDataInvalidJson(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Invalid JSON');
        getRequestData('not-json');
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
