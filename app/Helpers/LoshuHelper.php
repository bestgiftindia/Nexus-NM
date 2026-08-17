<?php

namespace App\Helpers;

use App\Models\Mahadasha;
use App\Models\MissingNumber;

class LoshuHelper
{
    public const NUMBER_ARR = [1, 2, 3, 4, 5, 6, 7, 8, 9];

    public static function calculateAge($dateOfBirth)
    {
        $dob = new \DateTime($dateOfBirth);
        $now = new \DateTime();
        $age = $now->diff($dob);
        return [
            'years' => $age->y,
            'months' => $age->m,
            'days' => $age->d
        ];
    }

    public static function calculateCurrentAge($dateOfBirth)
    {
        $dob = new \DateTime($dateOfBirth);
        $now = new \DateTime();
        $age = $now->diff($dob);
        return $age->y + 1; // Return the age in years
    }

    public static function getKingNumber(string $dob)
    {
        $day = (int) date('d', strtotime($dob));

        while ($day > 9) {
            $day = array_sum(str_split($day));
        }

        $planet = \App\Models\Planet::where('king_no', $day)->first();

        return $planet;
    }

    public static function getQueenNumber(string $dob)
    {
        $digits = preg_replace('/[^0-9]/', '', $dob);

        $sum = array_sum(str_split($digits));

        while ($sum > 9) {
            $sum = array_sum(str_split($sum));
        }

        $planet = \App\Models\Planet::where('king_no', $sum)->first();
        return $planet;
    }

    public static function calculateKuaNumber(string $dob, string $gender): int
    {
        $year = (int) date('Y', strtotime($dob));

        // Last two digits
        $sum = array_sum(str_split(substr($year, -2)));

        while ($sum > 9) {
            $sum = array_sum(str_split($sum));
        }

        if (strtolower($gender) == 'male') {
            if ($year >= 2000) {
                $kua = 9 - $sum;
            } else {
                $kua = 10 - $sum;
            }
        } else {
            if ($year >= 2000) {
                $kua = $sum + 6;
            } else {
                $kua = $sum + 5;
            }

            while ($kua > 9) {
                $kua = array_sum(str_split($kua));
            }
        }

        // Kua number 5 adjustment
        if ($kua == 5) {
            $kua = strtolower($gender) == 'male' ? 2 : 8;
        }

        return $kua;
    }

    public static function calculatePersonalYear(string $dob): int
    {
        $day = (int) date('d', strtotime($dob));
        $month = (int) date('m', strtotime($dob));
        $year = date('Y');

        $sum = array_sum(str_split($day))
            + array_sum(str_split($month))
            + array_sum(str_split($year));

        while ($sum > 9) {
            $sum = array_sum(str_split($sum));
        }

        return $sum;
    }


    static function missingData($dob)
    {
        $day = (int) date('d', strtotime($dob));
        $month = (int) date('m', strtotime($dob));
        $year = (int) date('Y', strtotime($dob));
        $moolank = self::getKingNumber($dob)->king_no;
        $bhagyank = self::getQueenNumber($dob)->king_no;

        $array1 = self::NUMBER_ARR;
        $dob1              = $day . '-' . $month . '-' . $year . '-' . $moolank . '-' . $bhagyank;
        $numberDob1        = $dob1;
        $array2            = array_map('intval', str_split($numberDob1));
        $diffDob1          = array_diff($array1, $array2);

        return [
            'missingDirection' => self::missingDirection($diffDob1),
            'missingNumbers' => self::missingNumbers($diffDob1),
            'missingNumberSymptoms' => self::missingNumberSymptoms($diffDob1),
            'missingNumbersRemedies' => self::missingNumbersRemedies($diffDob1),
            'elements' => self::missingElements($diffDob1)
        ];
    }

    public static function missingDirection(array $diffDob): string
    {
        $directionMap = [
            1 => 'North',
            2 => 'South West',
            3 => 'East',
            4 => 'South East',
            5 => 'Center',
            6 => 'North West',
            7 => 'West',
            8 => 'North East',
            9 => 'South',
        ];

        $directions = [];

        foreach ($diffDob as $number) {
            if (isset($directionMap[$number])) {
                $directions[] = $directionMap[$number];
            }
        }

        return implode(', ', array_unique($directions));
    }

    static function missingNumbers(array $diffDob)
    {
        return implode(', ', $diffDob);
    }

    static function missingNumberSymptoms(array $diffDob)
    {
        $missingNumberSymptoms = '';
        foreach ($diffDob as $diffs) {
            $missingNumbers = MissingNumber::missing($diffs)->get();
            $missingNumberSymptoms .= "<ul>";
            foreach ($missingNumbers as $resMNT) {
                $missingNumberSymptoms .= "<li>" . $resMNT->missing_number_msg . "</li>";
            }
            $missingNumberSymptoms .= "</ul>";
        }
        return $missingNumberSymptoms;
    }

    static function missingNumbersRemedies(array $diffDob)
    {
        $missingNumbersRemedies = '';
        foreach ($diffDob as $diffs) {
            $missingNumberRemedies = MissingNumber::missing($diffs)->get();
            $missingNumbersRemedies .= "<ul class='card border-dark border border-dashed py-3 mb-2'>";
            foreach ($missingNumberRemedies as $remedy) {
                $missingNumbersRemedies .= '<li>' . $remedy->remedies . "</li>";
            }
            $missingNumbersRemedies .= "</ul>";
        }
        return $missingNumbersRemedies;
    }

    public static function missingElements(array $diffDob): string
    {
        $elementMap = [
            1 => 'Water',
            2 => 'Earth',
            3 => 'Wood',
            4 => 'Wood',
            5 => 'Earth',
            6 => 'Metal',
            7 => 'Metal',
            8 => 'Earth',
            9 => 'Fire',
        ];

        $elements = [];

        foreach ($diffDob as $number) {
            if (isset($elementMap[$number])) {
                $elements[] = $elementMap[$number];
            }
        }

        return implode(', ', array_unique($elements));
    }

    static function ZodiacSign($dob)
    {
        $day = (int) date('d', strtotime($dob));
        $month = (int) date('m', strtotime($dob));

        $zodiacSign  = '';
        if ($day >= 21 && $month == 3 || $day <= 19 && $month == 4) {
            $zodiacSign = "Arise";
        }
        if ($day >= 20 && $month == 4 || $day <= 20 && $month == 5) {
            $zodiacSign = "Taurus";
        }
        if ($day >= 21 && $month == 5 || $day <= 20 && $month == 6) {
            $zodiacSign = "Gemini";
        }
        if ($day >= 21 && $month == 6 || $day <= 22 && $month == 7) {
            $zodiacSign = "Cancer";
        }
        if ($day >= 23 && $month == 7 || $day <= 22 && $month == 8) {
            $zodiacSign = "Leo";
        }
        if ($day >= 23 && $month == 8 || $day <= 22 && $month == 9) {
            $zodiacSign = "Virgo";
        }
        if ($day >= 23 && $month == 9 || $day <= 22 && $month == 10) {
            $zodiacSign = "Libra";
        }
        if ($day >= 23 && $month == 10 || $day <= 21 && $month == 11) {
            $zodiacSign = "Scorpio";
        }
        if ($day >= 22 && $month == 11 || $day <= 21 && $month == 12) {
            $zodiacSign = "Saggiterius";
        }
        if ($day >= 22 && $month == 12 || $day <= 19 && $month == 1) {
            $zodiacSign = "Capricorn";
        }
        if ($day >= 20 && $month == 1 || $day <= 18 && $month == 2) {
            $zodiacSign = "Aquarius";
        }
        if ($day >= 19 && $month == 2 || $day <= 20 && $month == 3) {
            $zodiacSign = "Pisces";
        }

        return $zodiacSign;
    }

    static function mahaDasha($dob)
    {
        $mulank = self::getKingNumber($dob)->king_no;
        $mahaDasha = '';
        $d = (int) date('d', strtotime($dob));
        $sum       = (int) date('Y', strtotime($dob));
        $a         = (int) date('Y', strtotime($dob));

        $planets = [
            1 => '1 (Sun)',
            2 => '2 (Moon)',
            3 => '3 (Jupiter)',
            4 => '4 (Rahu)',
            5 => '5 (Mercury)',
            6 => '6 (Venus)',
            7 => '7 (Ketu)',
            8 => '8 (Saturn)',
            9 => '9 (Mars)',
        ];

        //// LOOP FOR ALL PLANETS
        for ($A = 1; $A <= count($planets); $A++) {

            if ($mulank == $A || $d == $A) {
                $loop = 1;
                $c1 = $sum;
                $chk = date('Y');

                //// LOOP First
                for ($i = $mulank; $i <= 9; $i++) {
                    $sum = $sum + $i;
                    $cnt = $loop == 1 ? $a : $c1 + 1;
                    while ($cnt <= $sum) {
                        if ($cnt == $chk) {
                            $mahaDasha = $planets[$i];
                        }
                        $cnt++;
                    }
                    $loop++;
                    $c1 = $sum;
                }

                //// LOOP Second
                $c2 = $c1;
                for ($i = 1; $i <= 9; $i++) {
                    $sum = $sum + $i;
                    $cnt = $loop == 1 ? $a : $c2 + 1;
                    while ($cnt <= $sum) {
                        if ($cnt == $chk) {
                            $mahaDasha = $planets[$i];
                        }
                        $cnt++;
                    }
                    $loop++;
                    $c2 = $sum;
                }

                //// LOOP Third
                $c3 = $c2;
                for ($i = 1; $i <= 9; $i++) {
                    $sum = $sum + $i;
                    $cnt = $loop == 1 ? $a : $c3 + 1;
                    while ($cnt <= $sum) {
                        if ($cnt == $chk) {
                            $mahaDasha = $planets[$i];
                        }
                        $cnt++;
                    }
                    $loop++;
                    $c3 = $sum;
                }
            }
        }

        return $mahaDasha;
    }

    static function antarDasha($dob)
    {
        $d = (int) date('d', strtotime($dob));
        $m       = (int) date('m', strtotime($dob));
        $y         = (int) date('Y', strtotime($dob));

        $antdasha = 0;
        date("D", mktime(0, 0, 0, $m, $d, $y));
        if (date("D", mktime(0, 0, 0, $m, $d, $y)) == 'Sun') {
            $antdasha = $d + $m + $y + 1;
        }
        if (date("D", mktime(0, 0, 0, $m, $d, $y)) == 'Mon') {
            $antdasha = $d + $m + $y + 2;
        }
        if (date("D", mktime(0, 0, 0, $m, $d, $y)) == 'Tue') {
            $antdasha = $d + $m + $y + 9;
        }
        if (date("D", mktime(0, 0, 0, $m, $d, $y)) == 'Wed') {
            $antdasha = $d + $m + $y + 5;
        }
        if (date("D", mktime(0, 0, 0, $m, $d, $y)) == 'Thu') {
            $antdasha = $d + $m + $y + 3;
        }
        if (date("D", mktime(0, 0, 0, $m, $d, $y)) == 'Fri') {
            $antdasha = $d + $m + $y + 6;
        }
        if (date("D", mktime(0, 0, 0, $m, $d, $y)) == 'Sat') {
            $antdasha = $d + $m + $y + 8;
        }
        $antdashaa = $antdasha;

        $AD        = '';
        if (digSum1($antdashaa) == 1) {
            $AD = '1 (Sun)';
        } else if (digSum1($antdashaa) == 2) {
            $AD = '2 (Moon)';
        } else if (digSum1($antdashaa) == 3) {
            $AD = '3 (Jupiter)';
        } else if (digSum1($antdashaa) == 4) {
            $AD = '4 (Rahu)';
        } else if (digSum1($antdashaa) == 5) {
            $AD = '5 (Mercury)';
        } else if (digSum1($antdashaa) == 6) {
            $AD = '6 (Venus)';
        } else if (digSum1($antdashaa) == 7) {
            $AD = '7 (Ketu)';
        } else if (digSum1($antdashaa) == 8) {
            $AD = '8 (Saturn)';
        } else if (digSum1($antdashaa) == 9) {
            $AD = '9 (Mars)';
        }
        return $AD;
    }

    static function getLuckNumberData($dob)
    {
        $kingNumber = self::getKingNumber($dob)->king_no;
        $queenNumber = self::getQueenNumber($dob)->king_no;
        return \App\Models\LuckyUnluckyNumber::king($kingNumber)->queen($queenNumber)->first();
    }

    static function getBase64Image($path)
    {
        $fullPath = public_path($path);

        if (file_exists($fullPath)) {
            $imageData = file_get_contents($fullPath);
            $extension = pathinfo($fullPath, PATHINFO_EXTENSION);
            $base64    = 'data:image/' . $extension . ';base64,' . base64_encode($imageData);

            return $base64;
        }
        return '';
    }

    static function nameAnalysis(string $firstName, string $fullName, $dob)
    {
        $nameOutput = [];
        $results = self::getLuckNumberData($dob);

        $balancerNumber                 = $results->lucky_numbers;
        $unluckyNumber                  = $results->unlucky_numbers;
        $neutralNumber                  = $results->neutral_number;

        $fNameSum                       = digSum1(chaldean_sum($firstName));
        if (in_array($fNameSum, $balancerNumber)) {
            $nameOutput['result'] = 'Great! Your first Name is Compatible / Lucky with your date of Birth.';
        } elseif (in_array($fNameSum, $neutralNumber)) {
            $nameOutput['result'] = 'First name is Neutral to your date of birth, that means it is not lucky but it is still workable.<br>';
        } elseif (in_array($fNameSum, $unluckyNumber)) {
            $nameOutput['result'] = 'First name is Anti(Not Suitable) to your date of birth, You need to change it or consult with a Good Numerologist for Name Analysis. Highly Recommended!!!<br>';
        }

        $fullNameSum                   = digSum1(chaldean_sum($fullName));
        if (in_array($fullNameSum, $balancerNumber)) {
            $nameOutput['fullNameResult'] = 'Great! Your Full Name is compatible/Lucky with your date of birth.<br>';
        } elseif (in_array($fullNameSum, $neutralNumber)) {
            $nameOutput['fullNameResult'] = 'Full name is Neutral to your date of birth, that means it is not lucky but it is still workable.<br>';
        } elseif (in_array($fullNameSum, $unluckyNumber)) {
            $nameOutput['fullNameResult'] = 'Full name is Anti(Not Suitable) to your date of birth, You need to change it or consult with a Good Numerologist for Name Analysis. Highly Recommended!!!<br>';
        }

        $nameOutput['fullNameAnalysis']  = '<p>Your Full Name number as per Chaldean Numerology is : ' . chaldean_sum($fullName) . ' / ' . digSum1(chaldean_sum($fullName)) . '</p>';
        $nameOutput['firstNameAnalysis'] = '<p>Your First Name number as per Chaldean Numerology is: ' . chaldean_sum($firstName) . ' / ' . digSum1(chaldean_sum($firstName)) . '</p>';
        $nameOutput['favourableNumber'] = 'Primary Favourable Number: ' . implode(", ", $balancerNumber) . '<br>';
        $nameOutput['avoidableNumber']  = 'Primary Avoidable Number: ' . implode(", ", $unluckyNumber) . '<br>';

        return $nameOutput;
    }

    static function kingMahadashaInfo(array $options, $lastRun = false)
    {
        $outputValue = '';

        $day = $options['day'];
        $month = $options['month'];
        $year = $options['year'];
        $sumRuling = $options['sumRuling'];
        $startDasha = $options['startDasha'];

        for ($i = $startDasha; $i <= 9; $i++) {
            $sumRuling        = $sumRuling + $i;
            $rulingPlanetData = Mahadasha::king($i)->first();
            if ($rulingPlanetData) {

                $outputValue .= "<span class='fw-bold'>".$sumRuling . " => " . ($rulingPlanetData->kingPlanet->king_no ?? '') . ' (' . ($rulingPlanetData->kingPlanet->name ?? '') . ')' . "</span>";
                $outputValue .= '<ul class="card border-dark border border-dashed py-3 my-2 mb-3" style="line-height: 1.8rem;">';
                foreach ($rulingPlanetData->message as $message) {
                    $outputValue .= '<li>' . $message . '</li>';
                }
                $outputValue .= '</ul>';
                if (!$lastRun || $i < 9) {
                    $outputValue .= "<span class='fw-bold'>Dasha is :" . $day . " - " . $month . " - " . $sumRuling . " - </span>";
                }
            }
        }
        return ['output' => $outputValue, 'sumRuling' => $sumRuling];
    }

    static function calculateDasha($dob)
    {
        $outputValue = '';
        $day = (int) date('d', strtotime($dob));
        $month = (int) date('m', strtotime($dob));
        $year = (int) date('Y', strtotime($dob));
        $sumRuling = $year;

        $options = [
            'day' => $day,
            'month' => $month,
            'year' => $year,
            'sumRuling' => $sumRuling,
            'startDasha' => self::getKingNumber($dob)->king_no
        ];



        $outputValue .= "<span class='fw-bold'>Dasha is : " . $day . " - " . $month . " - " . $year . " - </span>";

        $kingMahadashaInfo = self::kingMahadashaInfo($options);
        $outputValue .= $kingMahadashaInfo['output'];

        for ($A = 1; $A <= 2; $A++) {
            $lastRun = $A == 2 ? true : false;
            $options['startDasha'] = 1;
            $options['sumRuling'] = $kingMahadashaInfo['sumRuling'];
            $kingMahadashaInfo = self::kingMahadashaInfo($options, $lastRun);
            $outputValue .= $kingMahadashaInfo['output'];
        }


        return $outputValue;
    }
}
