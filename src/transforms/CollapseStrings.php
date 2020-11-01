<?php namespace Rewind\Transforms;

use PhpParser\Node;
use PhpParser\Node\Expr\BinaryOp\Concat;
use PhpParser\Node\Scalar\String_;
use PhpParser\NodeVisitorAbstract;

class CollapseStrings extends NodeVisitorAbstract
{
    public function enterNode(Node $node) {
        if($node instanceof Concat) {
            if($node->left instanceof String_ && $node->right instanceof String_) {
                $GLOBALS['TREE_DIRTY'] = 1;
                return new String_($node->left->value . $node->right->value);
            }
        }
    }
}