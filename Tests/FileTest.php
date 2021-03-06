<?php

namespace AC\Servedown\Tests;

use AC\Servedown\File;

class FileTest extends \PHPUnit_Framework_TestCase
{

    public function testInstantiate()
    {
        $f = new File(__DIR__."/mock_content/test.md");
        $this->assertNotNull($f);
        $this->assertTrue($f instanceof File);
    }

    public function testGetAndSetConfig()
    {
        $f = new File(__DIR__."/mock_content/test.md");
        $this->assertSame(array(), $f->getConfig());
        $defaultTitle = "nothing";
        $this->assertSame($defaultTitle, $f->get('title', $defaultTitle));

        $f = new File(__DIR__."/mock_content/test_with_config.md");
        $this->assertSame("Test File", $f->get('title'));
        $f->set('title', "changed");
        $this->assertSame('changed', $f->get('title'));
        $this->assertFalse($f->get('foo', false));

        $f->setConfig(array(
            'title' => 'changed again',
            'foo' => true
        ));
        $this->assertSame('changed again', $f->get('title'));
        $this->assertTrue($f->get('foo', false));
    }

    public function testGetContent()
    {
        $f = new File(__DIR__."/mock_content/test.md");
        $expected = <<<EOF
# Test #

This is test content.
EOF;
        $this->assertSame($expected, $f->getContent());
        $this->assertSame($expected, (string) $f);
    }
    
    public function testHasContent()
    {
        $f = new File(__DIR__."/mock_content/test.md");
        $this->assertTrue($f->hasContent());
        
        $f = new File(__DIR__."/mock_content/nested/more/empty.md");
        $this->assertFalse($f->hasContent());
    }

    public function testGetRaw()
    {
        $f = new File(__DIR__."/mock_content/test_with_config.md");

        $expected = file_get_contents($f->getPath());
        $this->assertSame($expected, $f->getRaw());
    }

    public function testIsDirectory()
    {
        $f = new File(__DIR__."/mock_content/test.md");

        $this->assertFalse($f->isDirectory());
    }

    public function testGetParent()
    {
        $f = new File(__DIR__."/mock_content/test.md");
        $this->assertFalse($f->getParent());
    }

    public function testIsIndex()
    {
        $f = new File(__DIR__."/mock_content/test.md");
        $this->assertFalse($f->isIndex());
        $f->setIsIndex(true);
        $this->assertTrue($f->isIndex());
    }

    public function testExpectDirectoryException()
    {
        $this->setExpectedException("InvalidArgumentException");
        $f = new File(__DIR__."/mock_content/");
    }

    public function testConfigAsArray()
    {
        $f = new File(__DIR__."/mock_content/test_with_config.md");
        $this->assertSame($f->get('title'), $f['title']);
        $this->assertFalse(isset($f['foo']));
        $f['foo'] = 'bar';
        $this->assertSame('bar', $f['foo']);
        $this->assertTrue(isset($f['foo']));
        unset($f['foo']);
        $this->assertFalse(isset($f['foo']));
    }

}
