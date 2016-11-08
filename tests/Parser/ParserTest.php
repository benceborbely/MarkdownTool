<?php

namespace Bence\MarkdownTool\Tests\Parser;

use Bence\MarkdownTool\Parser\Parser;

/**
 * Class Parser
 *
 * @author Bence Borbély
 */
class ParserTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Parser
     */
    protected $parser;

    public function setUp()
    {
        $this->parser = new Parser();
    }

    public function testIfNotContainsMarkdown()
    {
        $text = "text";

        $result = $this->parser->parse($text);

        $this->assertEquals($text, $result);
    }

    public function testIfContainsItalic()
    {
        $text = "_text_";

        $result = $this->parser->parse($text);

        $this->assertEquals("<i>text</i>", $result);
    }

    public function testIfContainsInline()
    {
        $text = "'text'";

        $result = $this->parser->parse($text);

        $this->assertEquals("<pre>text</pre>", $result);
    }

    public function testIfContainsStrong()
    {
        $text = "**text**";

        $result = $this->parser->parse($text);

        $this->assertEquals("<strong>text</strong>", $result);
    }

    public function testIfContainsLink()
    {
        $text = "[text1](text2)";

        $result = $this->parser->parse($text);

        $this->assertEquals("<a href=\"text1\">text2</a>", $result);
    }

    public function testIfContainsImage()
    {
        $text = "![text1](text2)";

        $result = $this->parser->parse($text);

        $this->assertEquals("<img src=\"text1\" alt=\"text2\" />", $result);
    }

    public function testIfContainsEverything()
    {
        $text = "_text1_ 'text2' **text3** [text4](text5) ![text6](text7)";

        $result = $this->parser->parse($text);

        $this->assertEquals("<i>text1</i> <pre>text2</pre> <strong>text3</strong> <a href=\"text4\">text5</a> <img src=\"text6\" alt=\"text7\" />", $result);
    }

    public function testIfContainsEmbedded()
    {
        $text = "This **is** simple _[http://index.hu](link)_ and this is a picture ![http://r.ddmcdn.com/s_f/o_1/cx_633/cy_0/cw_1725/ch_1725/w_720/APL/uploads/2014/11/too-cute-doggone-it-video-playlist.jpg](of a dog).";

        $result = $this->parser->parse($text);

        $this->assertEquals("This <strong>is</strong> simple <i><a href=\"http://index.hu\">link</a></i> and this is a picture <img src=\"http://r.ddmcdn.com/s_f/o_1/cx_633/cy_0/cw_1725/ch_1725/w_720/APL/uploads/2014/11/too-cute-doggone-it-video-playlist.jpg\" alt=\"of a dog\" />.", $result);
    }
}
