<?php namespace Rewind\Transforms;

// This transform removes the use of functions that serve only to make
// otherwise readable strings slightly unreadable - base64_decode(),
// rawurldecode() etc. I am on a quest to find a better name for it so
// I can remove this annoying comment. It used to be called UnfuckStrings
// if that makes it any better?

// WARNING! We execute the function here to get the result. So ONLY
// functions that are safe on untrusted input get to go in here.

use PhpParser\Node;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Scalar\LNumber;
use PhpParser\NodeVisitorAbstract;

class EliminateStringFunctions extends NodeVisitorAbstract
{
    public function __construct() {
        $this->FUNCTIONS = array('base64_decode', 'rawurldecode', 'chr');
    }

    public function enterNode(Node $node) {
        // TODO: we should probably only do this if the resulting string will be
        // printable, rather than always. haven't run into it yet but i assume
        // it will break something at some point - especially with chr() etc.
        if($node instanceof FuncCall && in_array($node->name, $this->FUNCTIONS)) {
            if(count($node->args) == 1 &&
             ($node->args[0]->value instanceof String_ || $node->args[0]->value instanceof LNumber)) {
                $GLOBALS['TREE_DIRTY'] = 1;
                $deobbed = call_user_func($node->name->parts[0], $node->args[0]->value->value);
                return new String_($deobbed);
            }
        }
    }
}