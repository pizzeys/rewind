<?php namespace Rewind\Transforms;

use PhpParser\Node;
use PhpParser\NodeVisitorAbstract;

class RemoveComments extends NodeVisitorAbstract
{
    public function enterNode(Node $node) {
        if($node->getComments()) {
            $node->setAttribute('comments', null);
        }
    }
}