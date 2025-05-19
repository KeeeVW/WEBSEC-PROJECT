@extends('layouts.master')
@section('title', 'iOS Calculator')
@section('content')
<div class="container d-flex justify-content-center align-items-center vh-100">
    <div class="calculator">
        <div id="display" class="display">0</div>
        <div class="buttons">
            <button class="btn btn-light" onclick="clearDisplay()">AC</button>
            <button class="btn btn-light" onclick="toggleSign()">+/-</button>
            <button class="btn btn-light" onclick="percentage()">%</button>
            <button class="btn btn-warning" onclick="pressOperator('/')">÷</button>

            <button class="btn btn-dark" onclick="pressNumber(7)">7</button>
            <button class="btn btn-dark" onclick="pressNumber(8)">8</button>
            <button class="btn btn-dark" onclick="pressNumber(9)">9</button>
            <button class="btn btn-warning" onclick="pressOperator('*')">×</button>

            <button class="btn btn-dark" onclick="pressNumber(4)">4</button>
            <button class="btn btn-dark" onclick="pressNumber(5)">5</button>
            <button class="btn btn-dark" onclick="pressNumber(6)">6</button>
            <button class="btn btn-warning" onclick="pressOperator('-')">−</button>

            <button class="btn btn-dark" onclick="pressNumber(1)">1</button>
            <button class="btn btn-dark" onclick="pressNumber(2)">2</button>
            <button class="btn btn-dark" onclick="pressNumber(3)">3</button>
            <button class="btn btn-warning" onclick="pressOperator('+')">+</button>

            <button class="btn btn-dark zero-btn" onclick="pressNumber(0)">0</button>
            <button class="btn btn-dark" onclick="pressDot()">.</button>
            <button class="btn btn-warning" onclick="calculateResult()">=</button>
        </div>
    </div>
</div>

<style>
    body {
        background-color:rgb(255, 255, 255);
    }
    .calculator {
        width: 320px;
        background: #000;
        padding: 20px;
        border-radius: 30px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.5);
    }
    .display {
        font-size: 48px;
        color: white;
        text-align: right;
        padding: 10px;
        margin-bottom: 10px;
        min-height: 60px;
        overflow: hidden;
    }
    .buttons {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 10px;
    }
    .btn {
        width: 60px;
        height: 60px;
        font-size: 24px;
        font-weight: bold;
        border-radius: 50%;
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .btn-dark {
        background-color: #333;
        color: white;
    }
    .btn-light {
        background-color: #a5a5a5;
        color: black;
    }
    .btn-warning {
        background-color: #ff9500;
        color: white;
    }
    .zero-btn {
        width: 140px;
        border-radius: 50px;
        grid-column: span 2;
    }
</style>

<script>
    let display = document.getElementById("display");
    let currentInput = "0";
    let operator = null;
    let previousInput = null;

    function updateDisplay() {
        display.innerText = currentInput;
    }

    function pressNumber(num) {
        if (currentInput === "0") {
            currentInput = num.toString();
        } else {
            currentInput += num.toString();
        }
        updateDisplay();
    }

    function pressDot() {
        if (!currentInput.includes(".")) {
            currentInput += ".";
        }
        updateDisplay();
    }

    function pressOperator(op) {
        if (previousInput === null) {
            previousInput = currentInput;
        } else {
            previousInput = calculate(previousInput, currentInput, operator);
        }
        currentInput = "0";
        operator = op;
        updateDisplay();
    }

    function calculateResult() {
        if (previousInput !== null && operator !== null) {
            currentInput = calculate(previousInput, currentInput, operator);
            previousInput = null;
            operator = null;
        }
        updateDisplay();
    }

    function calculate(a, b, op) {
        a = parseFloat(a);
        b = parseFloat(b);
        switch (op) {
            case "+": return (a + b).toString();
            case "-": return (a - b).toString();
            case "*": return (a * b).toString();
            case "/": return b !== 0 ? (a / b).toString() : "Error";
            default: return b;
        }
    }

    function clearDisplay() {
        currentInput = "0";
        previousInput = null;
        operator = null;
        updateDisplay();
    }

    function toggleSign() {
        currentInput = (parseFloat(currentInput) * -1).toString();
        updateDisplay();
    }

    function percentage() {
        currentInput = (parseFloat(currentInput) / 100).toString();
        updateDisplay();
    }
</script>
@endsection
