<?php

class RequestTest extends PHPUnit_Framework_TestCase
{
    /**
     * Testing the post() method of the Request class
     */
    public function testPost()
    {
        $_POST["test"] = 22;
        $this->assertEquals(22, Request::post('test'));
        $this->assertEquals(null, Request::post('not_existing_key'));

        // test trim & strip_tags: Method is used with second argument "true", triggering a cleaning of the input
        $_POST["attacker_string"] = '   <script>alert("yo!");</script>   ';
        $this->assertEquals('alert("yo!");', Request::post('attacker_string', true));
    }

    /**
     * Testing the postCheckbox() method of the Request class
     */
    public function testPostCheckbox()
    {
        // Weird side-fact: a checked checkbox that has no manually set value will mostly contain 'on' as the default
        // value in most modern browsers btw, so it makes sense to test this
        $_POST['checkboxName'] = 'on';
        $this->assertEquals(1, Request::postCheckbox('checkboxName'));

        $_POST['checkboxName'] = 1;
        $this->assertEquals(1, Request::postCheckbox('checkboxName'));

        $_POST['checkboxName'] = null;
        $this->assertEquals(null, Request::postCheckbox('checkboxName'));
    }

    /**
     * Testing the get() method of the Request class
     */
    public function testGet()
    {
        $_GET["test"] = 33;
        $this->assertEquals(33, Request::get('test'));
        $this->assertEquals(null, Request::get('not_existing_key'));
    }

    /**
     * Testing the cookie() method of the Request class
     */
    public function testCookie()
    {
        $_COOKIE["test"] = 44;
        $this->assertEquals(44, Request::cookie('test'));
        $this->assertEquals(null, Request::cookie('not_existing_key'));
    }
}
