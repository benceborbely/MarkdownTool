<?php
/**
 * @author Bence Borbély
 *
 * Entry point of the application
 */

require_once 'vendor/autoload.php';

$file = 'input.txt';

$content = file_get_contents($file);

$parser = new Bence\MarkdownTool\Parser\Parser();

$parsedContent = $parser->parse($content);

file_put_contents('output.html', $parsedContent);
