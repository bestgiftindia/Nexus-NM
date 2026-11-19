<?php

namespace App\Services\Pronology;

use App\Models\LuckyUnluckyNumber;
use Illuminate\Support\Facades\DB;
use FPDF;

class ReportService extends FPDF
{
    const DPI = 96;
    const MM_IN_INCH = 25.4;
    const MAX_WIDTH = 500;
    const MAX_HEIGHT = 700;

    // Page layout in mm. Increase MARGIN_TOP / MARGIN_BOTTOM if text touches the inner.png header/footer art.
    const MARGIN_X = 15;
    const MARGIN_TOP = 32;
    const MARGIN_BOTTOM = 25;

    // Colours (RGB)
    const BRAND  = [166, 42, 42];
    const TEXT   = [45, 45, 45];
    const MUTED  = [120, 120, 120];
    const LIGHT  = [250, 242, 236];
    const WHITE  = [255, 255, 255];
    const BORDER = [215, 190, 178];
    const GOLD   = [190, 145, 70];

    const OK_FILL   = [232, 245, 233];
    const OK_TEXT   = [30, 110, 50];
    const WARN_FILL = [255, 244, 222];
    const WARN_TEXT = [150, 95, 0];
    const BAD_FILL  = [253, 235, 233];
    const BAD_TEXT  = [176, 40, 40];

    const CHALDEAN = [
        'A' => 1,
        'B' => 2,
        'C' => 3,
        'D' => 4,
        'E' => 5,
        'F' => 8,
        'G' => 3,
        'H' => 5,
        'I' => 1,
        'J' => 1,
        'K' => 2,
        'L' => 3,
        'M' => 4,
        'N' => 5,
        'O' => 7,
        'P' => 8,
        'Q' => 1,
        'R' => 2,
        'S' => 3,
        'T' => 4,
        'U' => 6,
        'V' => 6,
        'W' => 6,
        'X' => 5,
        'Y' => 1,
        'Z' => 7,
        '1' => 1,
        '2' => 2,
        '3' => 3,
        '4' => 4,
        '5' => 5,
        '6' => 6,
        '7' => 7,
        '8' => 8,
        '9' => 9,
    ];

    const DIRECTIONS = [
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

    // Lo-Shu positions: top row 4 9 2, middle 3 5 7, bottom 8 1 6
    const LOSHU_LAYOUT = [[4, 9, 2], [3, 5, 7], [8, 1, 6]];

    // Order in which missing-number remedies are printed
    const REMEDY_ORDER = [4, 9, 2, 3, 5, 7, 8, 1, 6];

    const SUGGESTION_SETS = [
        'Name Suggestion-1' => 'Name Fixed Suggestion 1',
        'Name Suggestion-2' => 'Name Fixed Suggestion 2',
        'Name Suggestion-3' => 'Name Fixed Suggestion 3',
    ];

    private $generateReportId;
    private $record;
    private $day;          // birth day with zeros removed (same as before)
    private $month;
    private $year;
    private $dob;          // Y-m-d
    private $firstName;
    private $calcName;     // first + middle + last without spaces, used for all calculations (unchanged)
    private $displayName;  // same name with spaces, used only for display
    private $moolank;
    private $bhagyank;
    private $lucky;
    private $fullPages = [];
    private $fullPagePending = false;

    public function __construct($generateReportId)
    {
        parent::__construct('P', 'mm', 'A4');
        $this->generateReportId = $generateReportId;
        $this->SetMargins(self::MARGIN_X, self::MARGIN_TOP, self::MARGIN_X);
        $this->SetAutoPageBreak(true, self::MARGIN_BOTTOM);
    }

    public function pixelsToMM($val)
    {
        return $val * self::MM_IN_INCH / self::DPI;
    }

    public function resizeToFit($imgFilename)
    {
        list($width, $height) = getimagesize($imgFilename);
        $scale = min(self::MAX_WIDTH / $width, self::MAX_HEIGHT / $height);

        return [
            round($this->pixelsToMM($scale * $width)),
            round($this->pixelsToMM($scale * $height)),
        ];
    }

    public function centerImage($img)
    {
        if (!file_exists($img)) {
            return;
        }

        list($width, $height) = $this->resizeToFit($img);
        $this->Image(
            $img,
            ($this->GetPageWidth() - $width) / 2,
            ($this->GetPageHeight() - $height) / 2,
            $width,
            $height
        );
    }

    /* ------------------------------------------------------------------
     | Page frame
     * ------------------------------------------------------------------ */

    public function Header()
    {
        // Cover and last page draw their own full-page image, so skip the inner background.
        if ($this->fullPagePending) {
            $this->fullPages[$this->PageNo()] = true;
            $this->fullPagePending = false;
            return;
        }

        $bg = public_path('assets/img/report/name/inner.png');
        if (file_exists($bg)) {
            $this->Image($bg, 0, 0, $this->GetPageWidth(), $this->GetPageHeight());
        }
    }

    public function Footer()
    {
        if (isset($this->fullPages[$this->PageNo()])) {
            return;
        }
        $this->SetY(-14);
        $this->SetFont('Arial', '', 9);
        $this->SetTextColor(...self::MUTED);
        // $this->Cell(0, 6, 'Page ' . ($this->PageNo() - 1), 0, 0, 'C');
    }

    /* ------------------------------------------------------------------
     | Entry point
     * ------------------------------------------------------------------ */

    public function generatePDF()
    {
        $this->loadReport();

        $this->firstPage();
        $this->InnerPage();
        $this->LastPage();

        $safeName = trim(preg_replace('/[^A-Za-z0-9]+/', '_', $this->displayName), '_');
        $this->Output('D', 'Pronology_Report_' . ($safeName ?: $this->generateReportId) . '.pdf');
        exit;
    }

    public function firstPage()
    {
        $this->addFullPageImage(public_path('assets/img/report/name/cover.png'));
    }

    public function LastPage()
    {
        $this->addFullPageImage(public_path('assets/img/report/name/last.png'));
    }

    public function InnerPage()
    {
        $grid = $this->buildLoShu();

        // Ganesh page
        $this->AddPage('P');
        $this->centerImage(public_path('assets/images/ganesh.png'));

        $suggestions = $this->manualSuggestions();

        $this->tableOfContents($suggestions !== null);

        if ($suggestions !== null) {
            $this->manualNamePage($suggestions);
        }

        $this->loShuPage($grid);
        $this->predictionPage();
        $this->firstNamePage();
        $this->nameNumberPage();
        $this->soundVibrationPage();
        $this->firstLetterPage();
        $this->personalYearPage();
        $this->missingNumberRemedies($grid);
    }

    /* ------------------------------------------------------------------
     | Data
     * ------------------------------------------------------------------ */

    private function loadReport(): void
    {
        $this->record = DB::table('namenumerology')
            ->where('id', $this->generateReportId)
            ->first();

        if (!$this->record) {
            abort(404, 'Report not found');
        }

        $r = $this->record;
        $ts = strtotime($r->date_of_birth);

        $this->dob = date('Y-m-d', $ts);
        $this->day = str_replace('0', '', date('d', $ts));
        $this->month = date('m', $ts);
        $this->year = date('Y', $ts);

        $this->firstName = (string) $r->first_name;
        $this->calcName = $r->first_name . $r->middle_name . $r->last_name;
        $this->displayName = trim(preg_replace('/\s+/', ' ', $r->first_name . ' ' . $r->middle_name . ' ' . $r->last_name));

        $this->moolank = digSum1($this->day);
        $this->bhagyank = digSum1($this->day . $this->month . $this->year);

        $this->lucky = LuckyUnluckyNumber::where('king_planet_id', $this->moolank)
            ->where('queen_planet_id', $this->bhagyank)
            ->first();

        $this->SetTitle('Pronology Report - ' . $this->displayName, true);
    }

    /**
     * Lo-Shu grid values for 1-9. Same rules as before:
     * every DOB digit, plus Moolank (unless the day itself is that digit), plus Bhagyank.
     */
    private function buildLoShu(): array
    {
        $digits = array_map('intval', str_split($this->day . $this->month . $this->year));
        $grid = [];

        for ($n = 1; $n <= 9; $n++) {
            $value = '';

            foreach ($digits as $digit) {
                if ($digit == $n) {
                    $value .= $n;
                }
            }

            if ($this->day != $n && $this->moolank == $n) {
                $value .= $this->moolank;
            }

            if ($this->bhagyank == $n) {
                $value .= $this->bhagyank;
            }

            $grid[$n] = $value;
        }

        return $grid;
    }

    private function missingFromDob(): array
    {
        $dob = $this->day . '-' . $this->month . '-' . $this->year . '-' . $this->moolank . '-' . $this->bhagyank;
        $digits = array_map('intval', str_split($dob));

        return array_values(array_diff([1, 2, 3, 4, 5, 6, 7, 8, 9], $digits));
    }

    private function manualSuggestions(): ?array
    {
        if (empty($this->record->manual_name)) {
            return null;
        }

        $data = json_decode($this->record->manual_name, true);
        if (!is_array($data)) {
            return null;
        }

        foreach (array_keys(self::SUGGESTION_SETS) as $set) {
            if (!empty($data[$set]['first_name']) || !empty($data[$set]['full_name'])) {
                return $data;
            }
        }

        return null;
    }

    private function compoundSum(string $text): int
    {
        $sum = 0;
        foreach (str_split(strtoupper($text)) as $char) {
            $sum += self::CHALDEAN[$char] ?? 0;
        }

        return $sum;
    }

    private function compoundDescription(int $number): ?string
    {
        $desc = DB::table('pronologycompoundtable')
            ->where('compoundNumber', $number)
            ->value('compoundDesc');

        return $desc ? (string) $desc : null;
    }

    /** Returns [message, fillColour, textColour] or null when there is no match. */
    private function verdict($number, string $subject): ?array
    {
        if (!$this->lucky) {
            return null;
        }

        $Subject = ucfirst($subject);

        if (in_array($number, $this->toArray($this->lucky->lucky_numbers))) {
            return ["Great! Your {$subject} is compatible / lucky with your date of birth.", self::OK_FILL, self::OK_TEXT];
        }

        if (in_array($number, $this->toArray($this->lucky->neutral_number))) {
            return ["{$Subject} is neutral to your date of birth. It is not lucky, but it is still workable.", self::WARN_FILL, self::WARN_TEXT];
        }

        if (in_array($number, $this->toArray($this->lucky->unlucky_number))) {
            return ["{$Subject} is Anti (Not Suitable) to your date of birth. You need to change it or consult a good Numerologist for Name Analysis. Highly Recommended!", self::BAD_FILL, self::BAD_TEXT];
        }

        return null;
    }

    /* ------------------------------------------------------------------
     | Sections
     * ------------------------------------------------------------------ */

    private function tableOfContents(bool $hasSuggestions): void
    {
        $items = [];
        if ($hasSuggestions) {
            $items[] = 'Name Suggestions As Per Numerology';
        }
        array_push(
            $items,
            'Lo-Shu Grid',
            'Name Numerology Prediction',
            'First Name Number',
            'Name Number',
            'Sound And Vibrations',
            'Prediction Based On First Letter',
            '5 Year Analysis',
            'Missing Number Remedies'
        );

        $this->startSection('Table Of Contents');

        $tableW = 140;
        $numW = 18;
        $x = ($this->GetPageWidth() - $tableW) / 2;

        $this->SetDrawColor(...self::BORDER);

        foreach ($items as $i => $item) {
            $this->SetX($x);
            $this->SetFillColor(...($i % 2 === 0 ? self::LIGHT : self::WHITE));

            $this->SetFont('Arial', 'B', 12);
            $this->SetTextColor(...self::BRAND);
            $this->Cell($numW, 12, (string) ($i + 1), 1, 0, 'C', true);

            $this->SetFont('Arial', '', 12);
            $this->SetTextColor(...self::TEXT);
            $this->Cell($tableW - $numW, 12, '   ' . $this->pdfText($item), 1, 1, 'L', true);
        }
    }

    private function manualNamePage(array $manualData): void
    {
        $this->startSection('Name Suggestions As Per Numerology');

        foreach (self::SUGGESTION_SETS as $key => $label) {
            $firstName = trim((string) ($manualData[$key]['first_name'] ?? ''));
            $fullName = trim((string) ($manualData[$key]['full_name'] ?? ''));
            $fScore = $manualData[$key]['first_name_score'] ?? '';
            $flScore = $manualData[$key]['full_name_score'] ?? '';

            if ($firstName === '' && $fullName === '') {
                continue;
            }

            $rows = [];
            if ($firstName !== '') {
                $rows[] = ['First Name', $firstName];
                if (!empty($fScore)) {
                    $rows[] = ['Name Total', (string) $fScore];
                }
            }
            if ($fullName !== '') {
                $rows[] = ['Full Name', $fullName];
                if (!empty($flScore)) {
                    $rows[] = ['Name Total', (string) $flScore];
                }
            }

            $this->ensureSpace(10 + count($rows) * 11);
            $this->bar($label);
            foreach ($rows as $row) {
                $this->infoRow($row[0], $row[1]);
            }
            $this->Ln(7);
        }

        $this->SetFont('Arial', 'I', 10);
        $this->SetTextColor(...self::MUTED);
        $this->MultiCell(0, 6, 'Note: The names above are suggested in alignment with your date of birth.', 0, 'L');
    }

    private function loShuPage(array $grid): void
    {
        $this->startSection('Lo-Shu Grid');

        $this->infoRow('Bhagyank', (string) $this->bhagyank);
        $this->infoRow('Moolank', (string) $this->moolank);
        $this->infoRow('Kua Number (Male)', (string) (11 - digSum1($this->year)));
        $this->infoRow('Kua Number (Female)', (string) digSum1(digSum1($this->year) + 4));

        $this->Ln(12);
        $this->drawLoShuGrid($grid, 32, false);
        $this->Ln(5);

        $this->SetFont('Arial', 'I', 10);
        $this->SetTextColor(...self::MUTED);
        $this->Cell(0, 6, 'Digits of your date of birth, Moolank and Bhagyank placed in the Lo-Shu grid.', 0, 1, 'C');
    }

    private function predictionPage(): void
    {
        $this->startSection('Name Numerology Prediction');

        $age = date_diff(date_create($this->dob), date_create('today'))->y;
        $missing = $this->missingFromDob();
        $directions = array_map(function ($n) {
            return self::DIRECTIONS[$n];
        }, $missing);

        $l = $this->lucky;

        $this->infoRow('Age', 'Completed Age: ' . $age . '     Running Age: ' . ($age + 1));
        $this->infoRow('Missing Directions', $directions ? implode(', ', $directions) : 'None');
        $this->infoRow('Missing Numbers', $missing ? implode(', ', $missing) : 'None');
        $this->infoRow('Lucky Numbers', $l ? $this->listText($l->balancer) : '');
        $this->infoRow('Unlucky Numbers', $l ? $this->listText($l->unlucky_number) : '');
        $this->infoRow('Neutral Numbers', $l ? $this->listText($l->neutral_number) : '');
        $this->infoRow('Lucky Color', $l ? strtoupper($this->listText($l->lucky_colour)) : '');
        $this->infoRow('Unlucky Color', $l ? strtoupper($this->listText($l->unlucky_colour)) : '');
    }

    private function firstNamePage(): void
    {
        $this->startSection('First Name Number');

        $total = digSum1(chaldean_sum($this->firstName));
        $compound = $this->compoundSum($this->firstName);

        $this->infoRow('First Name', $this->firstName);
        $this->infoRow('First Name Total', (string) $total);
        $this->infoRow('Compound Number', (string) $compound);

        $verdict = $this->verdict($total, 'first name');
        if ($verdict) {
            $this->subHeading('Is your first name compatible with your date of birth?');
            $this->callout($verdict[0], $verdict[1], $verdict[2]);
        }

        $desc = $this->compoundDescription($compound);
        if ($desc) {
            $this->subHeading('Compound Number ' . $compound);
            $this->paragraph($desc);
        }
    }

    private function nameNumberPage(): void
    {
        $this->startSection('Name Number');

        $total = digSum1(chaldean_sum($this->calcName));
        $compound = $this->compoundSum($this->calcName);

        $this->infoRow('Full Name', $this->displayName);
        $this->infoRow('Name Number', (string) $total);
        $this->infoRow('Compound Number', (string) $compound);

        $verdict = $this->verdict($total, 'full name');
        if ($verdict) {
            $this->subHeading('Is your full name compatible with your date of birth?');
            $this->callout($verdict[0], $verdict[1], $verdict[2]);
        }

        $desc = $this->compoundDescription($compound);
        if ($desc) {
            $this->subHeading('Compound Number ' . $compound);
            $this->paragraph($desc);
        }
    }

    private function soundVibrationPage(): void
    {
        $this->startSection('Sound And Vibrations');

        $rows = DB::table('namenumerologymaster')
            ->select('nameString', 'vibration')
            ->whereRaw("? LIKE CONCAT('%', nameString, '%')", [strtoupper($this->calcName)])
            ->get();

        if ($rows->isEmpty()) {
            $this->paragraph('There is no sound vibration as per pronology in your name.');
            return;
        }

        $this->paragraph('The following sounds present in your name carry these vibrations:');
        $this->Ln(2);
        $this->tableHeader('Sound', 'Vibration');
        foreach ($rows as $row) {
            $this->infoRow((string) $row->nameString, (string) $row->vibration);
        }
    }

    private function firstLetterPage(): void
    {
        $this->startSection('Prediction Based On First Letter');

        $letter = strtoupper(substr($this->calcName, 0, 1));
        $row = DB::table('namenumerologyalphamaster')
            ->where('alphabet', $letter)
            ->first();

        $this->infoRow('First Letter', $letter);
        $this->Ln(5);

        if ($row && !empty($row->description)) {
            $this->paragraph(str_replace(';', "'", $row->description));
        } else {
            $this->paragraph("No prediction found for the first letter '{$letter}'.");
        }
    }

    private function personalYearPage(): void
    {
        $this->startSection('5 Year Analysis');

        $this->paragraph(
            'The personal year number gives you a clue as to what lies ahead in the coming year. '
                . 'Your maturity and development take place in nine-year stages. Each nine-year period marks '
                . 'the beginning and the end of a particular stage of development. In a nutshell, this number '
                . 'tells you how a particular year will be for you.'
        );

        $ts = strtotime($this->dob);
        $d = date('d', $ts);
        $m = date('m', $ts);
        $currentYear = (int) date('Y');

        for ($i = $currentYear; $i <= $currentYear + 4; $i++) {
            $number = digSum1($d . $m . $i);
            $this->subHeading('Year ' . $i . '  -  Personal Year Number ' . $number);

            $row = DB::table('personal_year_table')
                ->where('personal_year_number', $number)
                ->first();

            if ($row && !empty($row->msg1)) {
                foreach (explode('()', $row->msg1) as $line) {
                    $line = trim(str_replace(';', "'", $line));
                    if ($line !== '') {
                        $this->bullet($line);
                    }
                }
            }
        }
    }

    private function missingNumberRemedies(array $grid): void
    {
        $this->startSection('Missing Number Remedies');

        $this->drawLoShuGrid($grid, 26, true);
        $this->Ln(6);

        $this->SetFont('Arial', 'B', 12);
        $this->SetTextColor(...self::BRAND);
        $this->Cell(0, 7, 'As per your date of birth, your missing numbers are highlighted.', 0, 1, 'C');
        $this->SetFont('Arial', '', 11);
        $this->SetTextColor(...self::TEXT);
        $this->Cell(0, 6, 'These numbers do not appear in your Lo-Shu grid.', 0, 1, 'C');
        $this->Ln(2);

        $anyMissing = false;

        foreach (self::REMEDY_ORDER as $n) {
            if ($grid[$n] !== '') {
                continue;
            }
            $anyMissing = true;

            $this->subHeading('Missing Number ' . $n);

            $row = DB::table('missing_number_remedies_table')
                ->where('missingNumber', (string) $n)
                ->first();

            if ($row && !empty($row->remedies)) {
                foreach (explode('()', $row->remedies) as $line) {
                    $line = trim($line);
                    if ($line !== '') {
                        $this->bullet($line);
                    }
                }
            }
        }

        if (!$anyMissing) {
            $this->paragraph('Great! There are no missing numbers in your Lo-Shu grid.');
        }

        $warning = $this->checkDiezn($this->calcName);
        if ($warning) {
            $this->subHeading('Disclaimer');
            $this->callout($warning, self::BAD_FILL, self::BAD_TEXT);
        }
    }

    private function checkDiezn($fullName)
    {
        $cleanName = strtolower($fullName);
        $targetChars = ['d', 'i', 'e', 'z', 'h', 'n'];

        foreach ($targetChars as $char) {
            if (substr_count($cleanName, $char) >= 3) {
                return "Since the character '$char' comes 3 times or more in your name, you could face health issues in life. Please contact a numerologist to fix your name.";
            }
        }

        return null;
    }

    /* ------------------------------------------------------------------
     | Drawing helpers
     * ------------------------------------------------------------------ */

    private function addFullPageImage(string $path): void
    {
        $this->fullPagePending = true;
        $this->AddPage('P');

        if (file_exists($path)) {
            $this->Image(
                $path,
                0,
                0,
                $this->GetPageWidth(),
                $this->GetPageHeight()
            );
        }

        $this->SetFont('Arial', 'B', 18);
        $this->SetTextColor(...self::BRAND);

        $text = $this->pdfText(ucwords($this->displayName));

        $pageWidth = $this->GetPageWidth();
        $pageHeight = $this->GetPageHeight();

        // Exact center alignment
        $this->SetXY(0,182);

        $this->Cell($pageWidth, 20, $text, 0, 0, 'C');
    }

    private function startSection(string $title): void
    {
        $this->AddPage('P');

        $this->SetFont('Arial', 'B', 18);
        $this->SetTextColor(...self::BRAND);
        $this->Cell(0, 10, $this->pdfText($title), 0, 1, 'C');

        $y = $this->GetY() + 1;
        $mid = $this->GetPageWidth() / 2;
        $this->SetDrawColor(...self::GOLD);
        $this->SetLineWidth(0.7);
        $this->Line($mid - 30, $y, $mid + 30, $y);
        $this->SetLineWidth(0.2);

        $this->Ln(9);
    }

    private function subHeading(string $text): void
    {
        $this->ensureSpace(16);
        $this->Ln(4);
        $this->SetFont('Arial', 'B', 13);
        $this->SetTextColor(...self::BRAND);
        $this->Cell(0, 8, $this->pdfText($text), 0, 1, 'L');
    }

    private function paragraph(string $text): void
    {
        $this->SetFont('Arial', '', 11);
        $this->SetTextColor(...self::TEXT);
        $this->MultiCell(0, 6, $this->pdfText($text), 0, 'J');
        $this->Ln(2);
    }

    private function bullet(string $text): void
    {
        $this->SetFont('Arial', '', 11);
        $this->SetTextColor(...self::TEXT);
        $this->SetX($this->lMargin);
        $this->Cell(6, 6, chr(149), 0, 0, 'L');
        $this->MultiCell(0, 6, $this->pdfText($text), 0, 'L');
        $this->Ln(1);
    }

    private function bar(string $text): void
    {
        $this->SetX($this->lMargin);
        $this->SetFillColor(...self::BRAND);
        $this->SetDrawColor(...self::BRAND);
        $this->SetTextColor(...self::WHITE);
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(0, 10, '  ' . $this->pdfText($text), 1, 1, 'L', true);
    }

    private function tableHeader(string $left, string $right): void
    {
        $labelW = 55;
        $this->ensureSpace(20);
        $this->SetX($this->lMargin);
        $this->SetFillColor(...self::BRAND);
        $this->SetDrawColor(...self::BRAND);
        $this->SetTextColor(...self::WHITE);
        $this->SetFont('Arial', 'B', 11);
        $this->Cell($labelW, 9, '  ' . $this->pdfText($left), 1, 0, 'L', true);
        $this->Cell($this->contentWidth() - $labelW, 9, '  ' . $this->pdfText($right), 1, 1, 'L', true);
    }

    /** Two-column row: label on a tinted cell, value on white. Grows to fit long values. */
    private function infoRow(string $label, string $value): void
    {
        $label = $this->pdfText($label);
        $value = $this->pdfText(trim($value) === '' ? '-' : $value);

        $labelW = 55;
        $valueW = $this->contentWidth() - $labelW;
        $lineH = 6.5;

        $this->SetFont('Arial', 'B', 11);
        $labelLines = $this->nbLines($labelW - 4, $label);
        $this->SetFont('Arial', '', 11);
        $valueLines = $this->nbLines($valueW - 4, $value);
        $h = max($labelLines, $valueLines) * $lineH + 4;

        $this->ensureSpace($h);
        $x = $this->lMargin;
        $y = $this->GetY();

        $this->SetDrawColor(...self::BORDER);
        $this->SetFillColor(...self::LIGHT);
        $this->Rect($x, $y, $labelW, $h, 'DF');
        $this->SetFillColor(...self::WHITE);
        $this->Rect($x + $labelW, $y, $valueW, $h, 'DF');

        $this->SetXY($x + 2, $y + 2);
        $this->SetFont('Arial', 'B', 11);
        $this->SetTextColor(...self::BRAND);
        $this->MultiCell($labelW - 4, $lineH, $label, 0, 'L');

        $this->SetXY($x + $labelW + 2, $y + 2);
        $this->SetFont('Arial', '', 11);
        $this->SetTextColor(...self::TEXT);
        $this->MultiCell($valueW - 4, $lineH, $value, 0, 'L');

        $this->SetXY($x, $y + $h);
    }

    private function callout(string $text, array $fill, array $color): void
    {
        $text = $this->pdfText($text);
        $w = $this->contentWidth();

        $this->SetFont('Arial', 'B', 11);
        $h = $this->nbLines($w - 10, $text) * 6 + 7;

        $this->ensureSpace($h + 4);
        $x = $this->lMargin;
        $y = $this->GetY();

        $this->SetFillColor(...$fill);
        $this->SetDrawColor(...$color);
        $this->Rect($x, $y, $w, $h, 'DF');
        $this->SetFillColor(...$color);
        $this->Rect($x, $y, 2, $h, 'F');

        $this->SetXY($x + 6, $y + 3.5);
        $this->SetTextColor(...$color);
        $this->MultiCell($w - 10, 6, $text, 0, 'L');

        $this->SetXY($x, $y + $h + 4);
    }

    private function drawLoShuGrid(array $grid, float $cell, bool $highlightMissing): void
    {
        $this->ensureSpace(3 * $cell + 4);

        $x0 = ($this->GetPageWidth() - 3 * $cell) / 2;
        $y0 = $this->GetY();

        $this->SetDrawColor(...self::BRAND);
        $this->SetLineWidth(0.5);
        $this->SetFont('Arial', 'B', $cell >= 30 ? 18 : 15);

        foreach (self::LOSHU_LAYOUT as $r => $row) {
            foreach ($row as $c => $n) {
                $value = $grid[$n];

                if ($value === '' && $highlightMissing) {
                    $this->SetFillColor(...self::BRAND);
                    $this->SetTextColor(...self::WHITE);
                    $label = (string) $n;
                } elseif ($value === '') {
                    $this->SetFillColor(...self::WHITE);
                    $label = '';
                } else {
                    $this->SetFillColor(...self::LIGHT);
                    $this->SetTextColor(...self::BRAND);
                    $label = $value;
                }

                $this->SetXY($x0 + $c * $cell, $y0 + $r * $cell);
                $this->Cell($cell, $cell, $label, 1, 0, 'C', true);
            }
        }

        $this->SetLineWidth(0.2);
        $this->SetXY($this->lMargin, $y0 + 3 * $cell);
    }

    private function ensureSpace(float $h): void
    {
        if ($this->GetY() + $h > $this->PageBreakTrigger) {
            $this->AddPage($this->CurOrientation);
        }
    }

    private function contentWidth(): float
    {
        return $this->w - $this->lMargin - $this->rMargin;
    }

    /** Number of lines MultiCell will use for $txt at width $w with the current font. */
    private function nbLines(float $w, string $txt): int
    {
        $cw = $this->CurrentFont['cw'];
        $wmax = ($w - 2 * $this->cMargin) * 1000 / $this->FontSize;
        $s = str_replace("\r", '', $txt);
        $nb = strlen($s);
        if ($nb > 0 && $s[$nb - 1] === "\n") {
            $nb--;
        }

        $sep = -1;
        $i = 0;
        $j = 0;
        $l = 0;
        $nl = 1;

        while ($i < $nb) {
            $c = $s[$i];
            if ($c === "\n") {
                $i++;
                $sep = -1;
                $j = $i;
                $l = 0;
                $nl++;
                continue;
            }
            if ($c === ' ') {
                $sep = $i;
            }
            $l += $cw[$c] ?? 0;
            if ($l > $wmax) {
                if ($sep === -1) {
                    if ($i === $j) {
                        $i++;
                    }
                } else {
                    $i = $sep + 1;
                }
                $sep = -1;
                $j = $i;
                $l = 0;
                $nl++;
            } else {
                $i++;
            }
        }

        return $nl;
    }

    /* ------------------------------------------------------------------
     | Text helpers
     * ------------------------------------------------------------------ */

    /** FPDF core fonts only understand Windows-1252, so convert UTF-8 text from the DB. */
    private function pdfText($text): string
    {
        $text = str_replace(['``', '`'], "'", (string) $text);

        $converted = @iconv('UTF-8', 'windows-1252//TRANSLIT//IGNORE', $text);
        if ($converted === false && function_exists('mb_convert_encoding')) {
            $converted = mb_convert_encoding($text, 'Windows-1252', 'UTF-8');
        }

        return $converted === false ? $text : $converted;
    }

    private function toArray($value): array
    {
        if (is_array($value)) {
            return $value;
        }
        if ($value === null || $value === '') {
            return [];
        }

        $decoded = json_decode((string) $value, true);
        if (is_array($decoded)) {
            return $decoded;
        }

        return array_map('trim', explode(',', (string) $value));
    }

    private function listText($value): string
    {
        return implode(', ', $this->toArray($value));
    }
}
