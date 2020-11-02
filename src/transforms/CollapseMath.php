<?php namespace Rewind\Transforms;

use PhpParser\Node;
use PhpParser\Node\Expr\BinaryOp\Minus;
use PhpParser\Node\Expr\BinaryOp\Plus;
use PhpParser\Node\Scalar\LNumber;
use PhpParser\NodeVisitorAbstract;

class CollapseMath extends NodeVisitorAbstract
{
    public function enterNode(Node $node) {
        if($node instanceof Minus) {
            if($node->left instanceof LNumber && $node->right instanceof LNumber) {
                $GLOBALS['TREE_DIRTY'] = 1;
                return new LNumber($node->left->value - $node->right->value);
            }
        }

        if($node instanceof Plus) {
            if($node->left instanceof LNumber && $node->right instanceof LNumber) {
                $GLOBALS['TREE_DIRTY'] = 1;
                return new LNumber($node->left->value + $node->right->value);
            }
        }
    }
}