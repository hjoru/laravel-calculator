<?php

namespace App\Utils;

class CalculateUtil
{
    static public function tokenizeExpression($expression) {
        return preg_split('/([\+\-\*\/x\(\)])/', $expression, null, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
    }

    static public function isOperator($token) {
        return in_array($token, ['+', '-', '*', '/', 'x', 'X']);
    }

    static public function precedence($operator) {
        switch ($operator) {
            case '+':
            case '-':
                return 1;
            case '*':
            case 'x':
            case '/':
                return 2;
            default:
                return 0;
        }
    }

    static public function processOperator($tokens) {
        $stack = [];

        foreach ($tokens as $token) {
            if (is_numeric($token)) {
                array_push($stack, $token);
            } elseif (CalculateUtil::isOperator($token)) {
                $right = array_pop($stack);
                $left = array_pop($stack);
    
                switch ($token) {
                    case '+':
                        array_push($stack, $left + $right);
                        break;
                    case '-':
                        array_push($stack, $left - $right);
                        break;
                    case '*':
                    case 'x':
                        array_push($stack, $left * $right);
                        break;
                    case '/':
                        if ($right != 0) {
                            array_push($stack, $left / $right);
                        } else {
                            throw new Exception('Division by zero');
                        }
                        break;
                }
            }
        }
        return $stack[0]; 
    }
}