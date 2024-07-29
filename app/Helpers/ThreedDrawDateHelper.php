<?php

if (! function_exists('getThaiLotteryDrawDates')) {
    function getThaiLotteryDrawDates()
    {
        return [
            'January' => [
                '1st January',
                '16th January',
            ],
            'February' => [
                '1st February',
                '16th February',
            ],
            'March' => [
                '1st March',
                '16th March',
            ],
            'April' => [
                '1st April',
                '16th April',
            ],
            'May' => [
                '2nd May', // Changed from 1st to 2nd May
                '16th May',
            ],
            'June' => [
                '1st June',
                '16th June',
            ],
            'July' => [
                '1st July',
                '16th July',
            ],
            'August' => [
                '1st August',
                '16th August',
            ],
            'September' => [
                '1st September',
                '16th September',
            ],
            'October' => [
                '1st October',
                '16th October',
            ],
            'November' => [
                '1st November',
                '16th November',
            ],
            'December' => [
                '1st December',
                '16th December',
                '31st December', // Added 31st December
            ],
        ];
    }
}
