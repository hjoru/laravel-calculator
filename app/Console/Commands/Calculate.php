<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Utils\CalculateUtil;
use Exception;

class Calculate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'calculate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        for (;;) {
            $expression = $this->ask('Please input your expression(q for exit):');
            if ($expression == 'q') {
                break;
            }
            $this->info($this->calculateExpression($expression));
        }        
    }
    
    public function calculateExpression($expression) {
        $tokens = CalculateUtil::tokenizeExpression($expression);
        $output = [];
        $operators = [];

        foreach ($tokens as $token) {
            if (is_numeric($token)) {
                $output[] = $token;
            } elseif ($token === '(') {
                array_push($operators, $token);
            } elseif ($token === ')') {
                while (!empty($operators) && end($operators) !== '(') {
                    $output[] = array_pop($operators);
                }
                array_pop($operators);
            } elseif (CalculateUtil::isOperator($token)) {
                while (!empty($operators) && CalculateUtil::precedence($operators[count($operators) - 1]) >= CalculateUtil::precedence($token)) {
                    $output[] = array_pop($operators);
                }
                array_push($operators, $token);
            }
        }
        while (!empty($operators)) {
            $output[] = array_pop($operators);
        }

        return CalculateUtil::processOperator($output);
    }
    
}
