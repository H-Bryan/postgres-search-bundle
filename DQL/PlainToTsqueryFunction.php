<?php
namespace Intaro\PostgresSearchBundle\DQL;

use Doctrine\ORM\Query\AST\Functions\FunctionNode;
use Doctrine\ORM\Query\Lexer;

/**
 * TsqueryFunction ::= "PLAINTO_TSQUERY" "(" StringPrimary "," StringPrimary ")"
 */
class PlainToTsqueryFunction extends FunctionNode
{
    public $fieldName = null;
    public $queryString = null;

    public function parse(\Doctrine\ORM\Query\Parser $parser)
    {
        $parser->match(Lexer::T_IDENTIFIER);
        $parser->match(Lexer::T_OPEN_PARENTHESIS);
        $this->fieldName = $parser->StringPrimary();
        $parser->match(Lexer::T_COMMA);
        $this->queryString = $parser->StringPrimary();
        $parser->match(Lexer::T_CLOSE_PARENTHESIS);
    }

    public function getSql(\Doctrine\ORM\Query\SqlWalker $sqlWalker)
    {
        return
            $this->fieldName->dispatch($sqlWalker) .
            ' @@ plainto_tsquery(' . $this->queryString->dispatch($sqlWalker) . ')';
    }
}
