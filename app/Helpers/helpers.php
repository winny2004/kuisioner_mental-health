<?php

if (!function_exists('getDASS21Label')) {
    /**
     * Get DASS-21 severity label based on score and type
     *
     * @param int $score The DASS-21 score
     * @param string $type The type: 'depression', 'anxiety', or 'stress'
     * @return string The severity label
     */
    function getDASS21Label($score, $type)
    {
        $labels = [
            'depression' => [
                [0, 9, 'Normal'],
                [10, 13, 'Mild (Ringan)'],
                [14, 20, 'Moderate (Sedang)'],
                [21, 27, 'Severe (Berat)'],
                [28, 42, 'Extremely Severe (Sangat Berat)'],
            ],
            'anxiety' => [
                [0, 7, 'Normal'],
                [8, 9, 'Mild (Ringan)'],
                [10, 14, 'Moderate (Sedang)'],
                [15, 19, 'Severe (Berat)'],
                [20, 42, 'Extremely Severe (Sangat Berat)'],
            ],
            'stress' => [
                [0, 14, 'Normal'],
                [15, 18, 'Mild (Ringan)'],
                [19, 25, 'Moderate (Sedang)'],
                [26, 33, 'Severe (Berat)'],
                [34, 42, 'Extremely Severe (Sangat Berat)'],
            ],
        ];

        $ranges = $labels[$type] ?? [];
        foreach ($ranges as $range) {
            if ($score >= $range[0] && $score <= $range[1]) {
                return $range[2];
            }
        }

        return 'Unknown';
    }
}
