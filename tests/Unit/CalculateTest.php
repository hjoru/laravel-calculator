<?php

use App\Utils\CalculateUtil;

test('calculate', function () {
    $this->artisan('calculate')
        ->expectsQuestion('Please input your expression(q for exit):', '1 + 2')->expectsOutput('3')
        ->expectsQuestion('Please input your expression(q for exit):', 'q');
    $this->artisan('calculate')
        ->expectsQuestion('Please input your expression(q for exit):', '1 + 2')->expectsOutput('3')
        ->expectsQuestion('Please input your expression(q for exit):', 'q');
    $this->artisan('calculate')
        ->expectsQuestion('Please input your expression(q for exit):', '1+2x3')->expectsOutput('7')
        ->expectsQuestion('Please input your expression(q for exit):', 'q');
    $this->artisan('calculate')
        ->expectsQuestion('Please input your expression(q for exit):', '(1 + 2) x3)')->expectsOutput('9')
        ->expectsQuestion('Please input your expression(q for exit):', 'q');
});

test('tokenizeExpression', function () {
    $this->assertEquals(["1", "+", "2", "x", "3"], CalculateUtil::tokenizeExpression("1+2x3"));
    $this->assertEquals(["1", "+", "2", "*", "3"], CalculateUtil::tokenizeExpression("1+2*3"));
    $this->assertEquals(["2", "*", "3", "-", "1"], CalculateUtil::tokenizeExpression("2*3-1"));

    $this->assertEquals(["(", "1", "+", "2", ")", "*", "3"], CalculateUtil::tokenizeExpression("(1+2)*3"));
    $this->assertEquals(["2", "*", "(", "3", "-", "1", ")"], CalculateUtil::tokenizeExpression("2*(3-1)"));
    $this->assertEquals(["(", "4", "+", "5", ")", "*", "2"], CalculateUtil::tokenizeExpression("(4+5)*2"));
});

test('isOperator', function () {
    $this->assertTrue(CalculateUtil::isOperator('+'));
    $this->assertTrue(CalculateUtil::isOperator('-'));
    $this->assertTrue(CalculateUtil::isOperator('*'));
    $this->assertTrue(CalculateUtil::isOperator('/'));
    $this->assertTrue(CalculateUtil::isOperator('x'));
    $this->assertFalse(CalculateUtil::isOperator(''));
    $this->assertFalse(CalculateUtil::isOperator('123'));
});

test('precedence', function () {
    $this->assertEquals(1, CalculateUtil::precedence('+'));
    $this->assertEquals(1, CalculateUtil::precedence('-'));
    $this->assertEquals(2, CalculateUtil::precedence('*'));
    $this->assertEquals(2, CalculateUtil::precedence('/'));
    $this->assertEquals(2, CalculateUtil::precedence('x'));
    $this->assertEquals(0, CalculateUtil::precedence(''));
    $this->assertEquals(0, CalculateUtil::precedence('123'));
});

test('processOperator', function () {
    $this->assertEquals(7, CalculateUtil::processOperator(["1", "2", "3", "x", "+"]));
    $this->assertEquals(5, CalculateUtil::processOperator(["2", "3", "*", "1", "-"]));
    $this->assertEquals(9, CalculateUtil::processOperator(["1", "2", "+", "3", "*"]));
});