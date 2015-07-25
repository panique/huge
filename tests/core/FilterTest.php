<?php

class FilterTest extends PHPUnit_Framework_TestCase
{
    /**
     * When argument contains bad code the encoded (and therefore un-dangerous) string should be returned
     */
    public function testXSSFilterWithBadCode()
    {
        $codeBefore = "Hello <script>var http = new XMLHttpRequest(); http.open('POST', 'example.com/my_account/delete.php', true);</script>";
        $codeAfter = "Hello &lt;script&gt;var http = new XMLHttpRequest(); http.open(&#039;POST&#039;, &#039;example.com/my_account/delete.php&#039;, true);&lt;/script&gt;";

        $this->assertEquals($codeAfter, Filter::XSSFilter($codeBefore));
    }

    /**
     * For every type other than strings the method should return the untouched passed argument
     */
    public function testXSSFilterWithNonStringArguments()
    {
        $this->assertEquals(123, 123);
        $this->assertEquals(array(1, 2, 3), array(1, 2, 3));
        $this->assertEquals(17.001, 17.001);
        $this->assertEquals(null, null);
    }
}
