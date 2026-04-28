<?php
function factorSum($n) {
    // Only handle odd numbers greater than 1
    if ($n < 1 || $n % 2 == 0) {
        return "Please provide an odd number greater than 1.";
    }

    // Try to find factors
    for ($i = 2; $i <= floor(sqrt($n)); $i++) {//floor means like get without decimal
        if ($n % $i == 0) {
            $j = $n / $i; //this is to divide the divisible number(after it has calculated throught the square root to go up to which number) after finding it and now it divides with the original
            // Found a factor pair
            return "Factors: $i * $j, Sum: " . ($i + $j);
        }
    }

    return "No non-trivial factors found (prime number).";
}

// Example
echo factorSum(367);  // Output: Factors: 3 * 3, Sum: 6
