(($0 % 2) == 0) { count2++ }
(($0 % 3) == 0) { count3++ }
END { print count2, count3 }
