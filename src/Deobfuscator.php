<?php namespace Rewind;

use PhpParser\ParserFactory;
use PhpParser\PrettyPrinter;
use PhpParser\NodeDumper;
use PhpParser\NodeTraverser;

class Deobfuscator
{
    public function __construct() {
        $DEFAULT_SET = array(
            '\Rewind\Transforms\CollapseStrings',
            '\Rewind\Transforms\CollapseMath',
            '\Rewind\Transforms\RemoveComments',
            '\Rewind\Transforms\EliminateStringFunctions',
        );

        $this->traverser = new NodeTraverser();

        foreach($DEFAULT_SET as $transform) {
            $this->traverser->addVisitor(new $transform);
        }
    }

    public function parse($code) {
        $parser = (new ParserFactory)->create(ParserFactory::PREFER_PHP7);

        try {
            $this->ast = $parser->parse($code);
        } catch (Error $error) {
            echo "Parse Error: {$error->getMessage()}\n";
            return;
        }
    }

    public function run() {
        // TODO: Remove this icky global by coming up with a better way to
        // detect situations where we need to re-run the transforms.
        $newAst = $this->traverser->traverse($this->ast);
        do {
            $GLOBALS['TREE_DIRTY'] = 0;
            $newAst = $this->traverser->traverse($newAst);
        } while ($GLOBALS['TREE_DIRTY'] == 1);

        $this->ast = $newAst;
    }

    public function dumpCode() {
        $printer = new PrettyPrinter\Standard;
        echo $printer->prettyPrintFile($this->ast);
        echo "\n";
    }

    public function dumpTree() {
        $dumper = new NodeDumper;
        echo $dumper->dump($this->ast);
    }
}