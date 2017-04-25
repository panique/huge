<?php

class FilterTest extends PHPUnit_Framework_TestCase
{
    /**
     * When string argument contains bad code the encoded (and therefore un-dangerous) string should be returned
     */
    public function testXSSFilterWithBadCodeInString_byref()
    {
        $codeBefore = "Hello <script>var http = new XMLHttpRequest(); http.open('POST', 'example.com/my_account/delete.php', true);</script>";
        $codeAfter = 'Hello &lt;script&gt;var http = new XMLHttpRequest(); http.open(&#039;POST&#039;, &#039;example.com/my_account/delete.php&#039;, true);&lt;/script&gt;';

        Filter::XSSFilter($codeBefore);
        $this->assertEquals($codeAfter, $codeBefore);
    }

    /**
     * When string argument contains bad code the encoded (and therefore un-dangerous) string should be returned
     */
    public function testXSSFilterWithBadCodeInString_return()
    {
        $codeBefore = "Hello <script>var http = new XMLHttpRequest(); http.open('POST', 'example.com/my_account/delete.php', true);</script>";
        $codeAfter = 'Hello &lt;script&gt;var http = new XMLHttpRequest(); http.open(&#039;POST&#039;, &#039;example.com/my_account/delete.php&#039;, true);&lt;/script&gt;';

        $this->assertEquals($codeAfter, Filter::XSSFilter($codeBefore));
    }


    public function testXSSFilterWithArrayOfBadCode_byref()
    {
        $codeBefore1 = "Hello <script>var http = new XMLHttpRequest(); http.open('POST', 'example.com/my_account/delete.php', true);</script>";
        $codeBefore2 = "Hello <script>var http = new XMLHttpRequest(); http.open('POST', 'example.com/my_account/delete.php', true);</script>";
        $codeAfter = 'Hello &lt;script&gt;var http = new XMLHttpRequest(); http.open(&#039;POST&#039;, &#039;example.com/my_account/delete.php&#039;, true);&lt;/script&gt;';

        $badArray = [$codeBefore1, $codeBefore2];
        Filter::XSSFilter($badArray);         

        $this->assertEquals($codeAfter, $badArray[0]);
        $this->assertEquals($codeAfter, $badArray[1]);
    }

    public function testXSSFilterWithArrayOfBadCode_return()
    {
        $codeBefore1 = "Hello <script>var http = new XMLHttpRequest(); http.open('POST', 'example.com/my_account/delete.php', true);</script>";
        $codeBefore2 = "Hello <script>var http = new XMLHttpRequest(); http.open('POST', 'example.com/my_account/delete.php', true);</script>";
        $codeAfter = 'Hello &lt;script&gt;var http = new XMLHttpRequest(); http.open(&#039;POST&#039;, &#039;example.com/my_account/delete.php&#039;, true);&lt;/script&gt;';

        $badArray = [$codeBefore1, $codeBefore2];

        $this->assertEquals($codeAfter, Filter::XSSFilter($badArray)[1]);
    }

    public function testXSSFilterWithAssociativeArrayOfBadCode()
    {
        $codeBefore1 = "Hello <script>var http = new XMLHttpRequest(); http.open('POST', 'example.com/my_account/delete.php', true);</script>";
        $codeBefore2 = "Hello <script>var http = new XMLHttpRequest(); http.open('POST', 'example.com/my_account/delete.php', true);</script>";
        $codeAfter = 'Hello &lt;script&gt;var http = new XMLHttpRequest(); http.open(&#039;POST&#039;, &#039;example.com/my_account/delete.php&#039;, true);&lt;/script&gt;';

        $badArray = ['foo' => $codeBefore1, 'bar' => $codeBefore2];
        Filter::XSSFilter($badArray);         

        $this->assertEquals($codeAfter, $badArray['foo']);
        $this->assertEquals($codeAfter, $badArray['bar']);
    }
  
    public function testXSSFilterWithSimpleObject_byref()
    {
        $codeBefore = "Hello <script>var http = new XMLHttpRequest(); http.open('POST', 'example.com/my_account/delete.php', true);</script>";
        $codeAfter = 'Hello &lt;script&gt;var http = new XMLHttpRequest(); http.open(&#039;POST&#039;, &#039;example.com/my_account/delete.php&#039;, true);&lt;/script&gt;';
        $integerBefore = 123;
        $integerAfter  = 123;

        $object = new stdClass();
        $object->int = $integerBefore;
        $object->str = 'foo';
        $object->badstr = $codeBefore;

        Filter::XSSFilter($object);         

        $this->assertEquals('foo', $object->str);
        $this->assertEquals($integerAfter, $object->int);
        $this->assertEquals($codeAfter, $object->badstr);
    }

    public function testXSSFilterWithSimpleObject_return()
    {
        $codeBefore = "Hello <script>var http = new XMLHttpRequest(); http.open('POST', 'example.com/my_account/delete.php', true);</script>";
        $codeAfter = 'Hello &lt;script&gt;var http = new XMLHttpRequest(); http.open(&#039;POST&#039;, &#039;example.com/my_account/delete.php&#039;, true);&lt;/script&gt;';
        $integerBefore = 123;
        $integerAfter  = 123;

        $object = new stdClass();
        $object->str = 'foo';
        $object->badstr = $codeBefore;

        $this->assertEquals($codeAfter, Filter::XSSFilter($object)->badstr);
    }

    public function testXSSFilterWithObjectContainingArray_byref()
    {
        $codeBefore1 = "Hello <script>var http = new XMLHttpRequest(); http.open('POST', 'example.com/my_account/delete.php', true);</script>";
        $codeBefore2 = "Hello <script>var http = new XMLHttpRequest(); http.open('POST', 'example.com/my_account/delete.php', true);</script>";
        $codeAfter = 'Hello &lt;script&gt;var http = new XMLHttpRequest(); http.open(&#039;POST&#039;, &#039;example.com/my_account/delete.php&#039;, true);&lt;/script&gt;';

        $badArray = ['foo' => 'bar', 'bad1' => $codeBefore1, 'bad2' => $codeBefore2];
        $object = new stdClass();
        $object->badArray = $badArray;

        Filter::XSSFilter($object);         

        $this->assertEquals('bar', $object->badArray['foo']);
        $this->assertEquals($codeAfter, $object->badArray['bad1']);
        $this->assertEquals($codeAfter, $object->badArray['bad2']);
    }

    public function testXSSFilterWithObjectContainingArray_return()
    {
        $codeBefore = "Hello <script>var http = new XMLHttpRequest(); http.open('POST', 'example.com/my_account/delete.php', true);</script>";
        $codeAfter = 'Hello &lt;script&gt;var http = new XMLHttpRequest(); http.open(&#039;POST&#039;, &#039;example.com/my_account/delete.php&#039;, true);&lt;/script&gt;';

        $badArray = ['foo' => 'bar', 'bad' => $codeBefore];
        $object = new stdClass();
        $object->badArray = $badArray;

        $this->assertEquals($codeAfter,  Filter::XSSFilter($object)->badArray['bad']);
    }

    public function testXSSFilterWithObjectContainingObject_byref()
    {
        $codeBefore1 = "Hello <script>var http = new XMLHttpRequest(); http.open('POST', 'example.com/my_account/delete.php', true);</script>";
        $codeBefore2 = "Hello <script>var http = new XMLHttpRequest(); http.open('POST', 'example.com/my_account/delete.php', true);</script>";
        $codeAfter = 'Hello &lt;script&gt;var http = new XMLHttpRequest(); http.open(&#039;POST&#039;, &#039;example.com/my_account/delete.php&#039;, true);&lt;/script&gt;';


        $object = new stdClass();
        $object->badStr = $codeBefore1;

        $childObject = new stdClass();
        $childObject->badStr = $codeBefore2;

        $object->badObject = $childObject;

        Filter::XSSFilter($object);         

        $this->assertEquals($codeAfter, $object->badStr);
        $this->assertEquals($codeAfter, $object->badObject->badStr);
    }

    public function testXSSFilterWithObjectContainingObject_return()
    {
        $codeBefore = "Hello <script>var http = new XMLHttpRequest(); http.open('POST', 'example.com/my_account/delete.php', true);</script>";
        $codeAfter = 'Hello &lt;script&gt;var http = new XMLHttpRequest(); http.open(&#039;POST&#039;, &#039;example.com/my_account/delete.php&#039;, true);&lt;/script&gt;';

        $object = new stdClass();
        $childObject = new stdClass();
        $childObject->badStr = $codeBefore;
        $object->badObject = $childObject;

        $this->assertEquals($codeAfter, Filter::XSSFilter($object)->badObject->badStr);
    }


    /**
     * For every type other than strings or arrays, the method should return the untouched passed argument
     */
    public function testXSSFilterWithNonStringOrArrayArguments()
    {
        $integerBefore = 123;
        $integerAfter  = 123;
        $arrayBefore   = [1, 2, 3];
        $arrayAfter    = [1, 2, 3];
        $floatsBefore  = 17.001;
        $floatsAfter   = 17.001;
        $null = null;

        Filter::XSSFilter($integerBefore);         
        Filter::XSSFilter($arrayBefore);         
        Filter::XSSFilter($floatsBefore);         
        Filter::XSSFilter($null);         

        $this->assertEquals($integerAfter, $integerBefore);
        $this->assertEquals($arrayBefore, $arrayAfter);
        $this->assertEquals($floatsBefore, $floatsAfter);
        $this->assertNull($null);
    }   

     /**
     * For every type other than strings or arrays, the method should return the untouched passed argument
     */
    public function testXSSFilterWithNonStringOrArrayArguments_return()
    {
        $integerBefore = 123;
        $integerAfter  = 123;
        $arrayBefore   = [1, 2, 3];
        $arrayAfter    = [1, 2, 3];
        $floatsBefore  = 17.001;
        $floatsAfter   = 17.001;
        $null = null;

        $this->assertEquals($integerAfter,  Filter::XSSFilter($integerBefore));
        $this->assertEquals($arrayBefore,  Filter::XSSFilter($arrayBefore));
        $this->assertEquals($floatsBefore, Filter::XSSFilter($floatsBefore));
        $this->assertNull(Filter::XSSFilter($null));
    }   

     /**
     * For every type other than strings or arrays, the method should return the untouched passed argument
     */
    public function testXSSFilterWithNonStringOrArrayArguments_byref()
    {
        $integerBefore = 123;
        $integerAfter  = 123;
        $arrayBefore   = [1, 2, 3];
        $arrayAfter    = [1, 2, 3];
        $floatsBefore  = 17.001;
        $floatsAfter   = 17.001;
        $null = null;

        Filter::XSSFilter($integerBefore);         
        Filter::XSSFilter($arrayBefore);         
        Filter::XSSFilter($floatsBefore);         
        Filter::XSSFilter($null);         

        $this->assertEquals($integerAfter, $integerBefore);
        $this->assertEquals($arrayBefore, $arrayAfter);
        $this->assertEquals($floatsBefore, $floatsAfter);
        $this->assertNull($null);
    }   

    public function testXSSFilterWithComplexArrayOfBadCode()
    {
        $codeBefore1 = "Hello <script>var http = new XMLHttpRequest(); http.open('POST', 'example.com/my_account/delete.php', true);</script>";
        $codeBefore2 = "Hello <script>var http = new XMLHttpRequest(); http.open('POST', 'example.com/my_account/delete.php', true);</script>";
        $codeBefore3 = "Hello <script>var http = new XMLHttpRequest(); http.open('POST', 'example.com/my_account/delete.php', true);</script>";
        $codeBefore4 = "Hello <script>var http = new XMLHttpRequest(); http.open('POST', 'example.com/my_account/delete.php', true);</script>";
        $codeAfter = 'Hello &lt;script&gt;var http = new XMLHttpRequest(); http.open(&#039;POST&#039;, &#039;example.com/my_account/delete.php&#039;, true);&lt;/script&gt;';
        
        $badObject = new stdClass();
        $badObject->badstr = $codeBefore4;

        $badArray = [ 
            'foo', 
            $codeBefore1, 
            'bar', 
            [
                'foo' => $codeBefore2, 
                'bar' => $codeBefore3
            ],
            $badObject
        ];

        Filter::XSSFilter($badArray);         

        $this->assertEquals('foo', $badArray[0]);
        $this->assertEquals($codeAfter, $badArray[1]);
        $this->assertEquals('bar', $badArray[2]);
        $this->assertEquals($codeAfter, $badArray[3]['foo']);
        $this->assertEquals($codeAfter, $badArray[3]['bar']);
        $this->assertEquals($codeAfter, $badArray[4]->badstr);
    }

}
