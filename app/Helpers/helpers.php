<?php

use App\Models\Country;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

if (!function_exists('loginAccount')) {
    function loginAccount()
    {
        $user = Auth::user();

        if (!$user) {
            return null;
        }

        $Address = $user->address->address ?? '';
        if (!empty($user->address->city->name ?? '')) {
            $Address .= ', ' . $user->address->city->name;
        }
        if (!empty($user->address->state->name ?? '')) {
            $Address .= ', ' . $user->address->state->name;
        }
        if (!empty($user->address->country->name ?? '')) {
            $Address .= ', ' . $user->address->country->name;
        }
        if (!empty($user->address->zipcode ?? '')) {
            $Address .= ' - ' . $user->address->zipcode;
        }

        return [
            'account_id'      => $user->id,
            'account_user_id' => $user->user_id,
            'short_name'      => generateShortName($user->name),
            'account_name'    => $user->name,
            'account_email'   => $user->email,
            'account_phone_code' => $user->phonecode->phonecode,
            'account_phone'   => $user->phone,
            'account_role'    => ucwords($user->role),
            'account_profile' => $user->avatar,
            'login_history'   => $user->loginHistories,
            'account_address' => $Address
        ];
    }
}

if (!function_exists('defaultCountry')) {
    function defaultCountry()
    {
        return Country::find(101);
    }
}

if (!function_exists('generateShortName')) {
    function generateShortName($name)
    {
        return collect(explode(' ', trim($name)))
            ->filter()
            ->map(fn($word) => strtoupper(substr($word, 0, 1)))
            ->implode('');
    }
}

if (!function_exists('dateFormat')) {
    function dateFormat($date)
    {
        return Carbon::parse($date)->format('d M, Y');
    }
}

if (!function_exists('timeFormat')) {
    function timeFormat($date)
    {
        return Carbon::parse($date)->format('h:i A');
    }
}

if (!function_exists('datetimeFormat')) {
    function datetimeFormat($date)
    {
        return Carbon::parse($date)->format('d M, Y (h:i A)');
    }
}

if (!function_exists('getGenders')) {
    function getGenders()
    {
        return collect([
            ['id' => 1, 'name' => 'Male'],
            ['id' => 2, 'name' => 'Female'],
            ['id' => 3, 'name' => 'Other']
        ]);
    }
}

if (!function_exists('getAllCountries')) {
    function getAllCountries()
    {
        return $countries = Country::active()->where('id', 101)->orderBy('name')->get();
    }
}


function generateFullName($listData)
{
    $name = $listData->first_name;
    if (!empty($listData->middle_name)) {
        $name .= ' ' . $listData->middle_name;
    }
    if (!empty($listData->last_name)) {
        $name .= ' ' . $listData->last_name;
    }
    return $name;
}


if (!function_exists('generateUserId')) {

    function generateUserId(): string
    {
        $prefix = 'USR' . now()->format('ym'); // USR2607

        $lastUser = User::where('user_id', 'like', $prefix . '%')
            ->latest('user_id')
            ->first();

        if (!$lastUser) {
            $number = 1;
        } else {
            $number = (int) substr($lastUser->user_id, 7) + 1;
        }
        $userId = $prefix . str_pad($number, 2, '0', STR_PAD_LEFT);

        return $userId;
    }
}

if (!function_exists('digSum1')) {
    function digSum1($mulsum)
    {
        $sum = 0;
        while ($mulsum > 0 || $sum > 9) {
            if ($mulsum == 0) {
                $mulsum = $sum;
                $sum = 0;
            }

            $sum += (int) $mulsum % 10;
            $mulsum = (int) $mulsum / 10;
        }
        return $sum;
    }
}

if (!function_exists('getVedicGrid')) {
    function getVedicGrid($dateOfBirth)
    {
        $date = \Carbon\Carbon::parse($dateOfBirth);

        // DOB Digits
        $digits = str_split($date->format('dmY'));

        // Remove 0
        $digits = array_filter($digits, function ($digit) {
            return $digit != 0;
        });

        $king  = \App\Helpers\LoshuHelper::getKingNumber($dateOfBirth)->king_no;
        $queen = \App\Helpers\LoshuHelper::getQueenNumber($dateOfBirth)->king_no;

        // Day
        $day = (int)$date->format('d');

        // King Number only if day > 9
        if ($day > 9) {
            $digits[] = $king;
        }

        // Queen Number always
        $digits[] = $queen;

        $count = [];

        foreach ($digits as $digit) {
            $count[$digit] = ($count[$digit] ?? 0) + 1;
        }

        $grid = [];

        for ($i = 1; $i <= 9; $i++) {
            $grid[$i] = isset($count[$i])
                ? str_repeat((string)$i, $count[$i])
                : '';
        }



        return [
            $grid[3],
            $grid[1],
            $grid[9],
            $grid[6],
            $grid[7],
            $grid[5],
            $grid[2],
            $grid[8],
            $grid[4]
        ];
    }
}


if (!function_exists('getLoShuGrid')) {
    function getLoShuGrid($dob)
    {
        // Keep only numbers
        $digits = str_split(preg_replace('/[^0-9]/', '', $dob));

        // Initialize grid
        $grid = [];
        for ($i = 1; $i <= 9; $i++) {
            $grid[$i] = '';
        }

        foreach ($digits as $digit) {

            if ($digit == '0') {
                continue;
            }

            $grid[$digit] .= $digit;
        }

        return [
            4 => $grid[4],
            9 => $grid[9],
            2 => $grid[2],

            3 => $grid[3],
            5 => $grid[5],
            7 => $grid[7],

            8 => $grid[8],
            1 => $grid[1],
            6 => $grid[6],
        ];
    }
}

if (!function_exists('chaldean_sum')) {
    function chaldean_sum($name)
    {
        $mapping = [
            'A' => 1,
            'I' => 1,
            'J' => 1,
            'Q' => 1,
            'Y' => 1,
            'B' => 2,
            'K' => 2,
            'R' => 2,
            'C' => 3,
            'G' => 3,
            'L' => 3,
            'S' => 3,
            'D' => 4,
            'M' => 4,
            'T' => 4,
            'E' => 5,
            'H' => 5,
            'N' => 5,
            'X' => 5,
            'U' => 6,
            'V' => 6,
            'W' => 6,
            'O' => 7,
            'Z' => 7,
            'F' => 8,
            'P' => 8,
        ];

        $sum = 0;
        $name = strtoupper($name);

        foreach (str_split($name) as $char) {
            if (isset($mapping[$char])) {
                $sum += $mapping[$char];
            }
        }

        return $sum;
    }
}

if (!function_exists('calculatePresencePercentage')) {
    function calculatePresencePercentage(array $inputArray)
    {
        if (empty($inputArray['loshuGrid']) || !is_array($inputArray['loshuGrid'])) {
            return [];
        }

        // Unique numbers from Lo Shu Grid
        $numbers = array_unique(array_map('intval', $inputArray['loshuGrid']));

        // All Lo Shu planes
        $planes = [
            '9-2-4' => [9, 2, 4], // Mental Plane
            '3-5-7' => [3, 5, 7], // Emotional Plane
            '8-1-6' => [8, 1, 6], // Practical Plane

            '4-3-8' => [4, 3, 8], // Thought Plane
            '9-5-1' => [9, 5, 1], // Will Plane
            '2-7-6' => [2, 7, 6], // Action Plane

            '4-5-6' => [4, 5, 6], // Determination Plane
            '2-5-8' => [2, 5, 8], // Spiritual Plane
        ];

        $result = [];

        foreach ($planes as $key => $plane) {

            $matched = count(array_intersect($plane, $numbers));

            $result[$key] = match ($matched) {
                3 => 100,
                2 => 66.66,
                1 => 33.33,
                default => 0,
            };
        }

        return $result;
    }
}


if (!function_exists('calculateLoshuGrid')) {
    function calculateLoshuGrid($dob)
    {
        $d = (int) date('d', strtotime($dob));
        $m       = (int) date('m', strtotime($dob));
        $y   =  (int) date('Y', strtotime($dob));

        $sumNew = $d . '' . $m . '' . $y;
        $numberNew = $sumNew;
        $arrayNew = array_map('intval', str_split($numberNew));
        $varOneNew = '';
        $varTwoNew = '';
        $varThreeNew = '';
        $varFourNew = '';
        $varFiveNew = '';
        $varSixNew = '';
        $varSevenNew = '';
        $varEightNew = '';
        $varNineNew = '';
        $mulsumNew = $d;
        $bhysumNew = $d . '' . $m . '' . $y;
        $grid = $output = [];
        $grid = ['loshuGrid' => []];

        // Four
        foreach ($arrayNew as $valueNew) {
            if ($valueNew == 4) {
                $varFourNew .= $valueNew;
            }
        }
        if ($d == 4) {
            $varFourNew .= substr($d, 0, 0) . substr($d, 1, 0);
        } else if (digSum1($mulsumNew) == 4) {
            $varFourNew .= digSum1($mulsumNew);
        }
        if (digSum1($bhysumNew) == 4) {
            $varFourNew .= digSum1($bhysumNew);
        }
        $grid['loshuGrid'][4] = $varFourNew;

        // Nine
        foreach ($arrayNew as $valueNew) {
            if ($valueNew == 9) {
                $varNineNew .= $valueNew;
            }
        }
        if ($d == 9) {
            $varNineNew .= substr($d, 0, 0) . substr($d, 1, 0);
        } else if (digSum1($mulsumNew) == 9) {
            $varNineNew .= digSum1($mulsumNew);
        }
        if (digSum1($bhysumNew) == 9) {
            $varNineNew .= digSum1($bhysumNew);
        }
        $grid['loshuGrid'][9] = $varNineNew;

        // Two
        foreach ($arrayNew as $valueNew) {
            if ($valueNew == 2) {
                $varTwoNew .= $valueNew;
            }
        }
        if ($d == 2) {
            $varTwoNew .= substr($d, 0, 0) . substr($d, 1, 0);
        } else if ($d == 20) {
            $varTwoNew .= substr($d, 0, 0) . substr($d, 2);
        } else if (digSum1($mulsumNew) == 2) {
            $varTwoNew .= digSum1($mulsumNew);
        }
        if (digSum1($bhysumNew) == 2) {
            $varTwoNew .= digSum1($bhysumNew);
        }
        $grid['loshuGrid'][2] = $varTwoNew;

        // Three
        foreach ($arrayNew as $valueNew) {
            if ($valueNew == 3) {
                $varThreeNew .= $valueNew;
            }
        }
        if ($d == 3) {
            $varThreeNew .= substr($d, 0, 0) . substr($d, 1, 0);
        } else if ($d == 30) {
            $varThreeNew .= substr($d, 0, 0) . substr($d, 2);
        } else if (digSum1($mulsumNew) == 3) {
            $varThreeNew .= digSum1($mulsumNew);
        }
        if (digSum1($bhysumNew) == 3) {
            $varThreeNew .= digSum1($bhysumNew);
        }
        $grid['loshuGrid'][3] = $varThreeNew;

        // Five
        foreach ($arrayNew as $valueNew) {
            if ($valueNew == 5) {
                $varFiveNew .= $valueNew;
            }
        }
        if ($d == 5) {
            $varFiveNew .= substr($d, 0, 0) . substr($d, 1, 0);
        } else if (digSum1($mulsumNew) == 5) {
            $varFiveNew .= digSum1($mulsumNew);
        }
        if (digSum1($bhysumNew) == 5) {
            $varFiveNew .= digSum1($bhysumNew);
        }
        $grid['loshuGrid'][5] = $varFiveNew;

        // Seven
        foreach ($arrayNew as $valueNew) {
            if ($valueNew == 7) {
                $varSevenNew .= $valueNew;
            }
        }
        if ($d == 7) {
            $varSevenNew .= substr($d, 0, 0) . substr($d, 1, 0);
        } else if (digSum1($mulsumNew) == 7) {
            $varSevenNew .= digSum1($mulsumNew);
        }
        if (digSum1($bhysumNew) == 7) {
            $varSevenNew .= digSum1($bhysumNew);
        }
        $grid['loshuGrid'][7] = $varSevenNew;

        // Eight
        foreach ($arrayNew as $valueNew) {
            if ($valueNew == 8) {
                $varEightNew .= $valueNew;
            }
        }
        if ($d == 8) {
            $varEightNew .= substr($d, 0, 0) . substr($d, 1, 0);
        } else if (digSum1($mulsumNew) == 8) {
            $varEightNew .= digSum1($mulsumNew);
        }
        if (digSum1($bhysumNew) == 8) {
            $varEightNew .= digSum1($bhysumNew);
        }
        $grid['loshuGrid'][8] = $varEightNew;

        // One
        foreach ($arrayNew as $valueNew) {
            if ($valueNew == 1) {
                $varOneNew .= $valueNew;
            }
        }
        if ($d == 1) {
            $varOneNew .= substr($d, 0, 0) . substr($d, 1, 0);
        } else if ($d == 10) {
            $varOneNew .= substr($d, 0, 0) . substr($d, 2, 0);
        } else if (digSum1($mulsumNew) == 1) {
            $varOneNew .= digSum1($mulsumNew);
        }
        if (digSum1($bhysumNew) == 1) {
            $varOneNew .= digSum1($bhysumNew);
        }
        $grid['loshuGrid'][1] = $varOneNew;

        // Six
        foreach ($arrayNew as $valueNew) {
            if ($valueNew == 6) {
                $varSixNew .= $valueNew;
            }
        }
        if ($d == 6) {
            $varSixNew .= substr($d, 0, 0) . substr($d, 1, 0);
        } else if (digSum1($mulsumNew) == 6) {
            $varSixNew .= digSum1($mulsumNew);
        }
        if (digSum1($bhysumNew) == 6) {
            $varSixNew .= digSum1($bhysumNew);
        }
        $grid['loshuGrid'][6] = $varSixNew;
        return $grid;
    }
}
