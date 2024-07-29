UPDATE wallets
SET balance = balance + 1000.00
WHERE id = 1;


SELECT * FROM lottery_three_digit_pivots 
WHERE match_start_date BETWEEN '2024-05-17' AND '2024-06-01'
AND res_date BETWEEN '2024-05-17' AND '2024-06-01'
AND prize_sent = 0
AND bet_digit = '788'
AND DATE(created_at) = '2024-05-28';

SELECT * FROM lottery_three_digit_pivots WHERE bet_digit = '788';

SELECT * FROM lottery_three_digit_pivots WHERE bet_digit = '788' AND prize_sent = 0;

SELECT * FROM lottery_three_digit_pivots 
WHERE bet_digit = '788' 
AND prize_sent = 0 
AND match_start_date BETWEEN '2024-05-17' AND '2024-06-01'
AND res_date BETWEEN '2024-05-17' AND '2024-06-01';

ALTER TABLE lottery_three_digit_copies
ADD COLUMN win_lose BOOLEAN DEFAULT 0;
