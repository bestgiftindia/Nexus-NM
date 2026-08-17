<?php

namespace App\Services\Child;

use App\Helpers\LoshuHelper;
use App\Models\Child\ChildAffirmation;
use App\Models\Child\ChildMissingNumberRemedy;
use App\Models\Child\ChildReportSignature;
use App\Models\Relationship\RelationshipLoveQuote;
use App\Services\Login\LoginService;
use FPDF;
use Illuminate\Support\Facades\DB;
use App\Models\Child\Child as ChildModel;
use App\Models\Child\ChildKingQueenPrediction;
use App\Models\Child\ChildParentStyle;
use App\Models\Child\ChildProfession;
use App\Models\Child\ChildRemedy;
use App\Models\Child\ChildTableOfContent;
use App\Models\FindLuckyUnluckyNumbersModel;

class ReportService extends FPDF
{
    public const IMAGEPATH = 'assets/img/report/child/';
    protected int $generateReportId;
    protected object $loginUserService;
    protected object $childService;

    protected $loginUser;
    protected object $childInfo;

    protected string $childName, $dateOfBirth;
    protected int $kingNumber, $queenNumber;

    protected $lastPageNumber = 0;

    protected $roots = [
        'primary_color' => ['HEX' => '#041d40', 'RGB' => '4, 29, 64'],
        'secondary_color' => ['HEX' => '#b47a1e', 'RGB' => '180, 122, 30'],
        'black_color' => ['HEX' => '#2e2c29', 'RGB' => '46, 44, 41'],
        'white_color' => ['HEX' => '#ffffff', 'RGB' => '255, 255, 255'],
        'font_family' => "Times"  /// Courier, Helvetica, Times
    ];

    public $affirmationIds = [];

    public function __construct(
        ChildService $childService,
        LoginService $loginService,
        int $generateReportId
    ) {
        parent::__construct();
        $this->generateReportId = $generateReportId;
        $this->loginUserService = $loginService;
        $this->childService = $childService;
        $this->initializeData();
    }

    function initializeData()
    {
        $this->loginUser = $this->loginUserService->findLoginUserService();
        $this->childInfo = $this->childService->findService($this->generateReportId);
        $this->childName = $this->childInfo->first_name ?? '';
        if (!empty($this->childInfo->middle_name ?? '')) {
            $this->childName .= ' ' . $this->childInfo->middle_name ?? '';
        }
        if (!empty($this->childInfo->last_name ?? '')) {
            $this->childName .= ' ' . $this->childInfo->last_name ?? '';
        }
        $this->dateOfBirth = \Carbon\Carbon::parse($this->childInfo->dob)->format('d M Y');
    }

    function Header()
    {
        $this->Ln(5);
        $bgimage = public_path(self::IMAGEPATH . 'blank.png');
        if (file_exists($bgimage)) {
            $this->Image(
                $bgimage,
                0,
                0,
                $this->GetPageWidth(),
                $this->GetPageHeight()
            );
        }
    }

    private function resizeToFit($imgFilename)
    {
        return $this->Image($imgFilename, 0, 0, $this->w, $this->h);
    }

    function Close()
    {
        // Current page hi final/last page hai
        $this->lastPageNumber = $this->PageNo();

        parent::Close();
    }

    function Footer()
    {
        $fontFamily = $this->roots['font_family'];
        $currentPage = $this->PageNo();
        // First page par footer counter hide
        if (
            $currentPage == 1 ||
            ($this->lastPageNumber > 0 && $currentPage == $this->lastPageNumber)
        ) {
            return;
        }

        $this->SetY(282);

        $this->SetFont(
            $fontFamily,
            'B',
            9
        );

        $rgb = array_map(
            'trim',
            explode(
                ',',
                $this->roots['primary_color']['RGB']
            )
        );

        $this->SetTextColor(
            (int) $rgb[0],
            (int) $rgb[1],
            (int) $rgb[2]
        );

        $this->Cell(
            0,
            10,
            '(Pages ' . $this->PageNo() . ' of {nb})',
            0,
            0,
            'C'
        );
    }

    function generatePDF()
    {
        $this->AliasNbPages();
        $this->SetMargins(10, 10, 10);
        $this->firstPageHere();
        $this->tableOfContent();
        $this->NumeroChart();
        $this->BnDnPrediction();
        $this->strengthWeakness();
        $this->behavior();
        $this->parentStyle();
        $this->profession();
        $this->nameSection();
        $this->signature();
        $this->missingremedies();
        $this->remedies();
        $this->lastPageHere();
        $this->Output('D', 'Child_Report_' . (str_replace(" ", "_", $this->childName)) . '.pdf');
        exit;
    }

    function generatePage($secionNo, $options = [])
    {

        $bgimage = public_path(self::IMAGEPATH . ($options['baseImg'] ?? 'inner.png'));
        $this->AddPage('P');
        $this->resizeToFit($bgimage);

        $childName = $this->childName;
        $fontFamily = $this->roots['font_family'];

        $this->SetFont($fontFamily, 'B', 12);
        $this->SetXY(10, 20);

        $title = $options['pageTitle'] ?? '';
        $this->SetFont($fontFamily, 'B', 24);
        $textWidth = $this->GetStringWidth($title);

        $x = (210 - $textWidth) / 2;
        $this->SetXY($x, 37);
        $rgb = array_map('trim', explode(',', $this->roots['primary_color']['RGB']));
        $this->SetTextColor((int)$rgb[0], (int)$rgb[1], (int)$rgb[2]);

        $this->Cell($textWidth, 10, $title, 0, 0, 'L');
    }

    public function loveQuote()
    {
        $quoteData = ChildAffirmation::whereNotIn('id', $this->affirmationIds)->inRandomOrder()->first();
        if ($quoteData) {
            $this->affirmationIds[] = $quoteData->id;
        }
        $fontFamily = $this->roots['font_family'];
        $text = $quoteData->title;

        $this->Ln(10);

        $drawColor = array_map('trim', explode(',', $this->roots['secondary_color']['RGB']));
        $this->SetDrawColor((int) $drawColor[0], (int) $drawColor[1], (int) $drawColor[2]);
        $this->SetLineWidth(0.3);

        $x1 = 10;
        $x2 = 199;
        $y = $this->GetY();

        $this->Line($x1, $y, $x2, $y);

        $this->Ln(4);

        // Calculate text width
        $this->SetFont($fontFamily, 'I', 13);
        $textWidth = $this->GetStringWidth($text);

        $heartWidth = 8;
        $gap = 1;

        $totalWidth = $heartWidth + $gap + $textWidth + $gap + $heartWidth;

        // Center horizontally
        $startX = ($this->GetPageWidth() - $totalWidth) / 2;

        // Left dot
        $this->SetXY($startX, $this->GetY());
        $this->SetFont($fontFamily, 'B', 16);

        $drawColor = array_map('trim', explode(',', $this->roots['primary_color']['RGB']));
        $this->SetTextColor((int)$drawColor[0], (int)$drawColor[1], (int)$drawColor[2]);

        $this->Cell($heartWidth, 8, '*', 0, 0, 'C');

        // Text
        $this->SetFont($fontFamily, 'I', 13);
        $this->Cell($gap, 8, '', 0, 0);
        $this->Cell($textWidth, 8, $text, 0, 0, 'C');

        // Right dot
        $this->Cell($gap, 8, '', 0, 0);
        $this->SetFont($fontFamily, 'B', 16);
        $this->Cell($heartWidth, 8, '*', 0, 1, 'C');

        $this->Ln(2);

        // Bottom Line
        $y = $this->GetY();
        $this->Line($x1, $y, $x2, $y);

        $this->Ln(5);
    }

    public function bulletPoint($text, $options = [])
    {
        $this->SetX($options['setX'] ?? 27);

        // Golden bullet
        if (($options['primaryDot'] ?? true) === true) {
            $rgb = array_map('trim', explode(',', $this->roots['black_color']['RGB']));
            $this->SetTextColor((int) $rgb[0], (int) $rgb[1], (int) $rgb[2],);
        } else {

            $rgb = array_map('trim', explode(',', $this->roots['secondary_color']['RGB']));
            $this->SetTextColor((int) $rgb[0], (int) $rgb[1], (int) $rgb[2],);
        }

        $this->SetFont('Times', '', 18);
        $this->Cell(6, 6, chr(149), 0, 0); // •

        // Text
        $fontFamily = $this->roots['font_family'];
        $this->SetTextColor(60, 60, 60);
        $this->SetFont($fontFamily, '', 13);
        $this->MultiCell(155, 6, $text, 0, 'L');

        $this->Ln(1);
    }

    public function NbLines($w, $txt)
    {
        $cw = &$this->CurrentFont['cw'];

        if ($w == 0) {
            $w = $this->w - $this->rMargin - $this->x;
        }

        $wmax = ($w - 2 * $this->cMargin) * 1000 / $this->FontSize;
        $s = str_replace("\r", '', (string)$txt);
        $nb = strlen($s);

        if ($nb > 0 && $s[$nb - 1] == "\n") {
            $nb--;
        }

        $sep = -1;
        $i = 0;
        $j = 0;
        $l = 0;
        $nl = 1;

        while ($i < $nb) {
            $c = $s[$i];

            if ($c == "\n") {
                $i++;
                $sep = -1;
                $j = $i;
                $l = 0;
                $nl++;
                continue;
            }

            if ($c == ' ') {
                $sep = $i;
            }

            $l += $cw[$c] ?? 0;

            if ($l > $wmax) {
                if ($sep == -1) {
                    if ($i == $j) {
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

    public function generateTable(array $rows = [])
    {
        if (empty($rows)) {
            return;
        }

        $leftMargin = 15;
        $tableWidth = $this->GetPageWidth() - (2 * $leftMargin);

        $lineHeight = 8;
        $paddingX = 2;      // Left & right padding
        $paddingY = 1;      // Top & bottom padding

        // Number of columns
        $columnCount = count($rows[0]);

        // Equal width for all columns
        $widths = array_fill(0, $columnCount, $tableWidth / $columnCount);
        $fontFamily = $this->roots['font_family'];

        foreach ($rows as $rowIndex => $row) {



            $x = $leftMargin;
            $y = $this->GetY();

            // Header Row
            if ($rowIndex == 0) {

                $this->SetXY($x, $y + 1);
                $this->SetFont($fontFamily, 'B', 12);
                $this->SetFillColor(35, 52, 170);
                $this->SetTextColor(255, 255, 255);
                $this->SetDrawColor(150, 150, 150);

                foreach ($row as $i => $cell) {
                    if (!empty($cell)) {
                        $this->Cell($widths[$i], 10, $cell, 1, 0, 'C', true);
                    }
                }

                $this->Ln();
                continue;
            }

            // Calculate max lines for this row
            $this->SetFont($fontFamily, '', 12);

            $maxLines = 1;

            foreach ($row as $i => $cell) {
                $lines = $this->NbLines(
                    $widths[$i] - (2 * $paddingX),
                    $cell
                );

                $maxLines = max($maxLines, $lines);
            }

            // Dynamic row height with top and bottom padding
            $rowHeight = ($maxLines * $lineHeight) + ($paddingY * 2);

            // Page break check
            if ($y + $rowHeight > ($this->GetPageHeight() - 20)) {
                $this->AddPage();
                $y = $this->GetY();
            }

            foreach ($row as $i => $cell) {

                // Alternate row color
                if (($rowIndex % 2) == 1) {
                    $this->SetFillColor(255, 255, 255);
                } else {
                    $this->SetFillColor(236, 239, 246);
                }

                $this->SetDrawColor(200, 200, 200);

                // Draw cell background and border
                $this->Rect($x, $y, $widths[$i], $rowHeight, 'DF');
                $this->Rect($x, $y, $widths[$i], $rowHeight);

                // Font style
                if ($i == 0) {
                    $secondaryColor = array_map('trim', explode(',', $this->roots['secondary_color']['RGB']));
                    $this->SetTextColor((int)$secondaryColor[0], (int)$secondaryColor[1], (int)$secondaryColor[2]);
                    $this->SetFont($fontFamily, 'B', 13);
                } else {
                    $blackColor = array_map('trim', explode(',', $this->roots['black_color']['RGB']));
                    $this->SetTextColor((int)$blackColor[0], (int)$blackColor[1], (int)$blackColor[2]);
                    $this->SetFont($fontFamily, '', 12);
                }

                // Text position with top padding
                $this->SetXY(
                    $x + $paddingX,
                    $y + $paddingY
                );

                // Print text
                $this->MultiCell(
                    $widths[$i] - (2 * $paddingX),
                    $lineHeight,
                    $cell,
                    0,
                    'L'
                );

                // Move to next column
                $x += $widths[$i];
                $this->SetXY($x, $y);
            }

            // Move to next row
            $this->SetXY($leftMargin, $y + $rowHeight);
        }
    }

    public function drawLoshuGrid($x, $y, $title, $data, $footer, $missingnumber = NULL)
    {

        // Card dimensions
        $cardW = 80;
        $cardH = 70;

        $secondaryColor = array_map('trim', explode(',', $this->roots['secondary_color']['RGB']));
        $whiteColor = array_map('trim', explode(',', $this->roots['white_color']['RGB']));
        $tertiaryColor = array_map('trim', explode(',', $this->roots['primary_color']['RGB']));

        // Shadow (slightly offset)
        $this->SetFillColor(210, 210, 210);
        $this->SetDrawColor(210, 210, 210);
        $this->RoundedRect($x + 1.5, $y + 1.5, $cardW, $cardH, 4, 'F');

        // White Card Background
        $this->SetFillColor(255, 255, 255);
        $this->SetDrawColor(240, 240, 240); // Light border
        $this->RoundedRect($x, $y, $cardW, $cardH, 4, 'DF');

        // Title
        $this->SetFont('Arial', 'B', 14);
        $this->SetTextColor((int)$secondaryColor[0], (int)$secondaryColor[1], (int)$secondaryColor[2]);
        $this->SetXY($x, $y + 4);
        $this->Cell($cardW, 8, $title, 0, 1, 'C');

        // Grid settings
        $cellW = 18;
        $cellH = 12;
        $gap   = 4; // Equal gap everywhere

        // Total grid size
        $gridW = (3 * $cellW) + (2 * $gap);
        $gridH = (3 * $cellH) + (2 * $gap);

        // Center grid horizontally
        $startX = $x + ($cardW - $gridW) / 2;

        // Position below title with balanced spacing
        $startY = $y + 16;

        $standardLoshu = [
            4,
            9,
            2,
            3,
            5,
            7,
            8,
            1,
            6
        ];

        $this->SetFont('Arial', 'B', 12);

        for ($row = 0; $row < 3; $row++) {
            for ($col = 0; $col < 3; $col++) {

                $value = !empty($data[$row][$col])
                    ? $data[$row][$col]
                    : '-';

                $cellX = $startX + ($col * ($cellW + $gap));
                $cellY = $startY + ($row * ($cellH + $gap));

                // Cell background

                // success 63, 194, 37


                $standardNumber = $standardLoshu[($row * 3) + $col];

                $this->SetTextColor(
                    (int)$whiteColor[0],
                    (int)$whiteColor[1],
                    (int)$whiteColor[2]
                );


                if (
                    !empty($missingnumber) &&
                    $standardNumber == (int) $missingnumber &&
                    $value == '-'
                ) {
                    // Missing number → Red
                    $this->SetFillColor(204, 16, 26);
                } elseif (
                    !empty($missingnumber) &&
                    $standardNumber != (int) $missingnumber &&
                    $value != '-'
                ) {
                    $this->SetTextColor((int) $tertiaryColor[0], (int) $tertiaryColor[1], (int) $tertiaryColor[2]);
                } else {
                    if (!empty($data[$row][$col])) {
                        $this->SetFillColor((int) $tertiaryColor[0], (int) $tertiaryColor[1], (int) $tertiaryColor[2]);
                    } else {
                        $this->SetFillColor(220, 220, 220);
                    }
                }

                $this->RoundedRect($cellX, $cellY, $cellW, $cellH, 2, 'F');

                // Text color

                // Cell text
                $this->SetXY($cellX, $cellY + 2);
                $this->Cell($cellW, 8, $value, 0, 0, 'C');
            }
        }

        // Footer
        $this->SetFont('Arial', 'I', 9);
        $this->SetTextColor(120, 120, 120);
        $this->SetXY($x, $y + $cardH - 8);
        $this->Cell($cardW, 6, $footer, 0, 0, 'C');
    }


    /**
     * Draw Rounded Rectangle
     */
    function RoundedRect($x, $y, $w, $h, $r, $style = '')
    {
        $k = $this->k;
        $hp = $this->h;

        if ($style == 'F')
            $op = 'f';
        elseif ($style == 'FD' || $style == 'DF')
            $op = 'B';
        else
            $op = 'S';

        $MyArc = 4 / 3 * (sqrt(2) - 1);

        $this->_out(sprintf('%.2F %.2F m', ($x + $r) * $k, ($hp - $y) * $k));

        // Top line
        $xc = $x + $w - $r;
        $yc = $y + $r;
        $this->_out(sprintf('%.2F %.2F l', $xc * $k, ($hp - $y) * $k));

        // Top-right
        $this->_Arc(
            $xc + $r * $MyArc,
            $yc - $r,
            $xc + $r,
            $yc - $r * $MyArc,
            $xc + $r,
            $yc
        );

        // Right line
        $xc = $x + $w - $r;
        $yc = $y + $h - $r;
        $this->_out(sprintf('%.2F %.2F l', ($x + $w) * $k, ($hp - $yc) * $k));

        // Bottom-right
        $this->_Arc(
            $xc + $r,
            $yc + $r * $MyArc,
            $xc + $r * $MyArc,
            $yc + $r,
            $xc,
            $yc + $r
        );

        // Bottom line
        $xc = $x + $r;
        $this->_out(sprintf('%.2F %.2F l', $xc * $k, ($hp - ($y + $h)) * $k));

        // Bottom-left
        $this->_Arc(
            $xc - $r * $MyArc,
            $yc + $r,
            $xc - $r,
            $yc + $r * $MyArc,
            $xc - $r,
            $yc
        );

        // Left line
        $yc = $y + $r;
        $this->_out(sprintf('%.2F %.2F l', $x * $k, ($hp - $yc) * $k));

        // Top-left
        $this->_Arc(
            $x,
            $yc - $r * $MyArc,
            $xc - $r * $MyArc,
            $y,
            $xc,
            $y
        );

        $this->_out($op);
    }

    function _Arc($x1, $y1, $x2, $y2, $x3, $y3)
    {
        $h = $this->h;
        $k = $this->k;

        $this->_out(sprintf(
            '%.2F %.2F %.2F %.2F %.2F %.2F c',
            $x1 * $k,
            ($h - $y1) * $k,
            $x2 * $k,
            ($h - $y2) * $k,
            $x3 * $k,
            ($h - $y3) * $k
        ));
    }

    public function kingQueenSection($luckyNumbers, $unluckyNumbers, $missingNumbers)
    {
        $leftMargin = 22;
        $rightMargin = 24;
        $gap = 2;

        $availableWidth = 210 - $leftMargin - $rightMargin; // A4 width
        $boxWidth = ($availableWidth - (2 * $gap)) / 3;

        $y = $this->GetY() + 15;
        $boxHeight = 23;

        $black  = array_map('trim', explode(',', $this->roots['secondary_color']['RGB']));
        $fontFamily = $this->roots['font_family'];

        $sections = [
            [
                'title' => 'Lucky Numbers',
                'value' => !empty($luckyNumbers) ? implode(", ", $luckyNumbers) : '-',
            ],
            [
                'title' => 'Unlucky Numbers',
                'value' => !empty($unluckyNumbers) ? implode(", ", $unluckyNumbers) : '-',
            ],
            [
                'title' => 'Missing Numbers',
                'value' => !empty($missingNumbers) ? implode(", ", $missingNumbers) : '-',
            ],
        ];

        foreach ($sections as $index => $section) {

            $currentX = $leftMargin + ($index * ($boxWidth + $gap));

            // Shadow (slightly offset)
            $this->SetFillColor(210, 210, 210);
            $this->SetDrawColor(210, 210, 210);
            $this->RoundedRect($currentX + 1.5, $y + 1.5, $boxWidth, $boxHeight, 4, 'F');

            $this->SetFillColor(255, 255, 255);
            $this->SetDrawColor(240, 240, 240); // Light border
            $this->RoundedRect($currentX, $y, $boxWidth, $boxHeight, 4, 'DF');


            // Title
            $this->SetXY($currentX, $y + 3);
            $this->SetFont($fontFamily, 'B', 12);
            $secondaryColor = array_map('trim', explode(',', $this->roots['secondary_color']['RGB']));
            $this->SetTextColor((int)$secondaryColor[0], (int)$secondaryColor[1], (int)$secondaryColor[2]);
            $this->Cell($boxWidth, 6, $section['title'], 0, 1, 'C');

            // Value
            $this->SetXY($currentX, $y + 12);
            $this->SetFont($fontFamily, 'B', 14);
            $this->SetTextColor($black[0], $black[1], $black[2]);
            $this->Cell($boxWidth, 8, $section['value'], 0, 1, 'C');
        }

        // Next content ke liye cursor neeche le jao
        $this->SetY($y + $boxHeight);
    }

    function missingNumbers()
    {
        $dob = $this->childInfo->dob;
        $dobArr = explode("-", $dob);

        $this->kingNumber = LoshuHelper::getKingNumber($this->childInfo['dob'] ?? '')['king_no'] ?? '';
        $this->queenNumber = LoshuHelper::getQueenNumber($this->childInfo['dob'] ?? '')['king_no'] ?? '';

        $calculateLoshuGrid = calculateLoshuGrid($dobArr[2], $dobArr[1], $dobArr[0], $this->kingNumber, $this->queenNumber);
        $calculateLoshuGrid = $calculateLoshuGrid['loshuGrid'];
        $missingNumbers = [];

        foreach ($calculateLoshuGrid as $number => $value) {
            if (empty($value)) {
                $missingNumbers[] = $number;
            }
        }
        return $missingNumbers;
    }

    function numbersInCharts()
    {
        $dob = $this->childInfo->dob;
        $dobArr = explode("-", $dob);

        $this->kingNumber = LoshuHelper::getKingNumber($this->childInfo->dob ?? '')['king_no'] ?? '';
        $this->queenNumber = LoshuHelper::getQueenNumber($this->childInfo->dob ?? '')['king_no'] ?? '';

        $calculateLoshuGrid = calculateLoshuGrid($dobArr[2], $dobArr[1], $dobArr[0], $this->kingNumber, $this->queenNumber);
        $calculateLoshuGrid = $calculateLoshuGrid['loshuGrid'];
        $numbersInCharts = [];



        foreach ($calculateLoshuGrid as $number => $value) {
            if (!empty($value)) {
                $numbersInCharts[] = $number;
            }
        }
        return [$numbersInCharts, $calculateLoshuGrid];
    }

    ///// PDF Pages Starts Here //////

    /// Cover Page
    function firstPageHere()
    {
        $this->AcceptPageBreak();
        $bgimage = public_path(self::IMAGEPATH . 'cover.png');
        $this->AddPage('P');
        $this->resizeToFit($bgimage);

        $reportId = ($this->childInfo->id ?? '');
        switch (true) {
            case $reportId < 10:
                $reportId = 'TBN-CR000' . $reportId;
                break;
            case $reportId < 100:
                $reportId = 'TBN-CR00' . $reportId;
                break;
            case $reportId < 1000:
                $reportId = 'TBN-CR0' . $reportId;
                break;
            default:
                $reportId = 'TBN-CR' . $reportId;
        }

        $childName = $this->childName;
        $birthLocation = $this->childInfo->birth_location ?? '';
        $dateOfBirth = $this->dateOfBirth;
        $timeOfBirth = \Carbon\Carbon::parse($this->childInfo->time_of_birth ?? '')->format('H:i A');
        $this->kingNumber = LoshuHelper::getKingNumber($this->childInfo['dob'] ?? '')['king_no'] ?? '';
        $this->queenNumber = LoshuHelper::getQueenNumber($this->childInfo['dob'] ?? '')['king_no'] ?? '';
        $generateDate = \Carbon\Carbon::now()->format('d M Y');

        $kingNumber = $this->kingNumber;
        $queenNumber = $this->queenNumber;

        if ($kingNumber <= 9) {
            $kingNumber = '0' . $kingNumber;
        }

        if ($queenNumber <= 9) {
            $queenNumber = '0' . $queenNumber;
        }


        $fontFamily = $this->roots['font_family'];

        // Name
        $this->SetFont($fontFamily, 'B', 15);

        $textWidth = $this->GetStringWidth($childName);
        $x = (220 - $textWidth) / 2; // A4 width = 210 mm

        $this->SetXY($x, 226);

        $rgb = array_map('trim', explode(',', $this->roots['white_color']['RGB']));
        $this->SetTextColor((int)$rgb[0], (int)$rgb[1], (int)$rgb[2]);

        $this->Cell($textWidth, 10, $childName, 0, 0, 'L');


        // dateOfBirth
        $this->SetFont($fontFamily, 'B', 13);
        $textWidth = $this->GetStringWidth($dateOfBirth);
        $x = (229 - $textWidth) / 2;
        $this->SetXY($x, 234.3);
        $rgb = array_map('trim', explode(',', $this->roots['white_color']['RGB']));
        $this->SetTextColor((int)$rgb[0], (int)$rgb[1], (int)$rgb[2]);
        $this->Cell($textWidth, 10, $dateOfBirth, 0, 0, 'L');

        // timeOfBirth
        $this->SetFont($fontFamily, 'B', 13);
        $textWidth = $this->GetStringWidth($timeOfBirth);
        $x = (227 - $textWidth) / 2;
        $this->SetXY($x, 242);
        $rgb = array_map('trim', explode(',', $this->roots['white_color']['RGB']));
        $this->SetTextColor((int)$rgb[0], (int)$rgb[1], (int)$rgb[2]);
        $this->Cell($textWidth, 10, $timeOfBirth, 0, 0, 'L');

        $this->SetAutoPageBreak(true, 20);
    }


    /// Table of Content
    public function tableOfContent()
    {
        // $pageHeading = "Your Journey";
        $pageTitle = "WHAT'S INSIDE";
        $this->generatePage(1, [
            'pageTitle' => $pageTitle,
            'baseImg' => 'inner.png'
        ]);

        $lists = ChildTableOfContent::all();
        $fontFamily = $this->roots['font_family'];

        $startY = 50;
        $rowHeight = 16;

        foreach ($lists as $index => $list) {
            $lineY = $startY + (($index + 1) * $rowHeight);
            $textY = $lineY - 8;

            $nimage = public_path(self::IMAGEPATH . 'blank.png');
            $this->Image($nimage, 20, $lineY - 9, 9, 7);


            $this->SetXY(21.5, $textY - 0.5);
            $this->SetFont($fontFamily, 'B', 13);
            $rgb = array_map('trim', explode(',', $this->roots['primary_color']['RGB']));
            $this->SetTextColor((int) $rgb[0], (int) $rgb[1], (int) $rgb[2],);
            $this->Cell(120, 6, ($index + 1), 0, 0, 'L');


            $this->SetFont($fontFamily, 'B', 14);
            $rgb = array_map('trim', explode(',', $this->roots['primary_color']['RGB']));
            $this->SetTextColor((int) $rgb[0], (int) $rgb[1], (int) $rgb[2],);
            $this->SetXY(30, $textY);
            $title = ucwords(strtolower($list->title));
            $this->Cell(120, 6, $title, 0, 0, 'L');
        }
    }


    function contentMargin()
    {
        $leftMargin = 10;
        $rightMargin = 10;
        $pageWidth = $this->GetPageWidth();
        $cellWidth = $pageWidth - $leftMargin - $rightMargin;

        return $cellWidth;
    }

    function NumeroChart()
    {
        $query = ChildTableOfContent::where('id', 1)->first();
        $pageTitle = $query->title ?? "NUMEROLOGY CHART";
        $this->generatePage(1, [
            'pageTitle' => $pageTitle
        ]);

        $queryData =  LoshuHelper::getLuckNumberData($this->childInfo['dob']);

        $balancerNumbers = $queryData->lucky_numbers;
        sort($balancerNumbers);

        $unluckyNumbers = $queryData->unlucky_numbers;
        sort($unluckyNumbers);

        $neutralNumbers = $queryData->neutral_number;
        sort($neutralNumbers);

        $missingNumbers = $this->missingNumbers();
        sort($missingNumbers);

        $numbersInChart = $this->numbersInCharts()[1];

        $personalGrid = array_chunk($numbersInChart, 3);


        $standardGrid = [
            [4, 9, 2],
            [3, 5, 7],
            [8, 1, 6],
        ];

        $this->drawLoshuGrid(
            22,
            53,
            'Standard Loshu Grid',
            $standardGrid,
            'The universal energy pattern'
        );

        $this->drawLoshuGrid(
            105,
            53,
            $this->childName . " Grid",
            $personalGrid,
            'Your personal energy pattern'
        );

        $this->kingQueenSection(
            $balancerNumbers,
            $unluckyNumbers,
            $missingNumbers,
        );

        $this->loveQuote();
    }

    function BnDnPrediction()
    {

        $query = ChildTableOfContent::where('id', 2)->first();
        $pageData = ChildKingQueenPrediction::where([
            'king_number' => LoshuHelper::getKingNumber($this->childInfo->dob ?? '')['king_no'] ?? '',
            'queen_number' => LoshuHelper::getQueenNumber($this->childInfo->dob ?? '')['king_no'] ?? ''
        ])->first();
        $pageTitle = $query->title ?? "BN & DN PREDICTION";
        $this->generatePage(1, [
            'pageTitle' => $pageTitle
        ]);
        $fontFamily = $this->roots['font_family'];

        $this->Ln(15);
        $cellWidth = $this->contentMargin();

        $commanDescription = "Thank you for taking this step towards understanding your child's unique personality. Every child is born with their own strengths, emotional needs, and life lessons. This report is designed to help you understand your child's natural behaviour and guide them with greater confidence, patience, and love. While numerology reveals your child's inborn qualities, your support and parenting will always play the most important role in helping them reach their highest potential.";
        $this->SetFont($fontFamily, '', 13);
        $this->SetTextColor(60, 60, 60);

        $this->SetX(10);
        $this->MultiCell($cellWidth, 7, $commanDescription ?? '', 0, 'L');

        $this->Ln(5);

        $content = $pageData->content ?? [];

        if (!empty($content['description'])) {


            $this->SetFont($fontFamily, 'B', 13);
            $rgb = array_map('trim', explode(',', $this->roots['black_color']['RGB']));
            $this->SetTextColor((int)$rgb[0], (int)$rgb[1], (int)$rgb[2]);
            $this->SetX(10);
            $this->MultiCell(0, 8, "Dear Parent", 0, 'L', false);

            $this->Ln(2);
            // Description
            $this->SetFont($fontFamily, '', 13);
            $this->SetTextColor(60, 60, 60);

            $this->SetX(10);
            $this->MultiCell($cellWidth, 7, $content['description'] ?? '', 0, 'L');
            $this->Ln(4);
        }

        $this->SetFont($fontFamily, 'B', 13);

        $rgb = array_map('trim', explode(',', $this->roots['primary_color']['RGB']));
        $this->SetTextColor((int)$rgb[0], (int)$rgb[1], (int)$rgb[2]);

        $this->SetX(10);
        $this->MultiCell(0, 8, $content['title'], 0, 'L', false);

        if (!empty($content['description_points'])) {
            $this->Ln(3);
            foreach ($content['description_points'] as $dpoint) {
                $this->SetFont($fontFamily, '', 13);
                $this->SetTextColor(60, 60, 60);
                $this->SetX(10);
                $this->MultiCell($cellWidth, 7, $dpoint ?? '', 0, 'L');
                $this->Ln(5);
                // $this->bulletPoint($dpoint, ['setX' => 15]);
            }
            $this->Ln(3);
        }

        if (!empty($content['description_2'])) {

            // Description
            $this->SetFont($fontFamily, '', 13);
            $this->SetTextColor(60, 60, 60);

            $this->SetX(10);
            $this->MultiCell($cellWidth, 7, $content['description_2'] ?? '', 0, 'L');
        }

        if (!empty($content['description_points_2'])) {
            $this->Ln(3);
            foreach ($content['description_points_2'] as $d2point) {
                $this->bulletPoint($d2point, ['setX' => 15]);
            }
            $this->Ln(3);
        }

        if (!empty($content['description_3'])) {
            // Description
            $this->SetFont($fontFamily, '', 13);
            $this->SetTextColor(60, 60, 60);

            $this->SetX(10);
            $this->MultiCell($cellWidth, 7, $content['description_3'] ?? '', 0, 'L');
        }
        $this->Ln(5);


        $this->loveQuote();
    }

    function strengthWeakness()
    {
        $query = ChildTableOfContent::where('id', 3)->first();
        $pageData = ChildKingQueenPrediction::where([
            'king_number' => LoshuHelper::getKingNumber($this->childInfo->dob ?? '')['king_no'] ?? '',
            'queen_number' => LoshuHelper::getQueenNumber($this->childInfo->dob ?? '')['king_no'] ?? ''
        ])->first();
        $pageTitle = $query->title ?? "Behavior";
        $this->generatePage(1, [
            'pageTitle' => $pageTitle
        ]);
        $fontFamily = $this->roots['font_family'];

        $this->Ln(15);
        $strength = $pageData->strength ?? [];

        $this->SetFont($fontFamily, 'B', 13);

        $rgb = array_map('trim', explode(',', $this->roots['primary_color']['RGB']));
        $this->SetTextColor((int)$rgb[0], (int)$rgb[1], (int)$rgb[2]);

        $this->SetX(10);
        $this->MultiCell(0, 8, $strength['title'], 0, 'L', false);

        $this->Ln(3);
        $cellWidth = $this->contentMargin();

        foreach ($strength['points'] as $point) {
            // $this->bulletPoint($point, ['setX' => 15]);
            $this->SetFont($fontFamily, '', 13);
            $this->SetTextColor(60, 60, 60);
            $this->SetX(10);
            $this->MultiCell($cellWidth, 7, $point ?? '', 0, 'L');
            $this->Ln(5);
        }


        $weakness = $pageData->weakness ?? [];
        if (!empty($weakness)) {
            $this->Ln(10);
            $this->SetFont($fontFamily, 'B', 13);
            $rgb = array_map('trim', explode(',', $this->roots['primary_color']['RGB']));
            $this->SetTextColor((int)$rgb[0], (int)$rgb[1], (int)$rgb[2]);

            $this->SetX(10);
            $this->MultiCell(0, 8, $weakness['title'], 0, 'L', false);

            $this->Ln(3);
            foreach ($weakness['points'] as $point) {
                // $this->bulletPoint($point, ['setX' => 15]);

                $this->SetFont($fontFamily, '', 13);
                $this->SetTextColor(60, 60, 60);
                $this->SetX(10);
                $this->MultiCell($cellWidth, 7, $point ?? '', 0, 'L');
                $this->Ln(5);
            }
        }

        $this->loveQuote();
    }
    function behavior()
    {
        $query = ChildTableOfContent::where('id', 4)->first();
        $pageData = ChildKingQueenPrediction::where([
            'king_number' => LoshuHelper::getKingNumber($this->childInfo->dob ?? '')['king_no'] ?? '',
            'queen_number' => LoshuHelper::getQueenNumber($this->childInfo->dob ?? '')['king_no'] ?? ''
        ])->first();
        $pageTitle = $query->title ?? "Behavior";
        $this->generatePage(1, [
            'pageTitle' => $pageTitle
        ]);
        $fontFamily = $this->roots['font_family'];

        $this->Ln(15);
        $content = $pageData->behaviour ?? [];

        $this->SetFont($fontFamily, 'B', 13);

        $rgb = array_map('trim', explode(',', $this->roots['primary_color']['RGB']));
        $this->SetTextColor((int)$rgb[0], (int)$rgb[1], (int)$rgb[2]);

        $this->SetX(10);
        $this->MultiCell(0, 8, $content['title'], 0, 'L', false);

        $cellWidth = $this->contentMargin();
        $this->Ln(3);
        foreach ($content['points'] as $point) {
            // $this->bulletPoint($point, ['setX' => 15]);
            $this->SetFont($fontFamily, '', 13);
            $this->SetTextColor(60, 60, 60);
            $this->SetX(10);
            $this->MultiCell($cellWidth, 7, $point ?? '', 0, 'L');
            $this->Ln(5);
        }

        $this->loveQuote();
    }

    function parentStyle()
    {
        $query = ChildTableOfContent::where('id', 5)->first();
        $pageData = ChildParentStyle::where([
            'king_number' => LoshuHelper::getKingNumber($this->childInfo->dob ?? '')['king_no'] ?? '',
            'queen_number' => LoshuHelper::getQueenNumber($this->childInfo->dob ?? '')['king_no'] ?? ''
        ])->first();
        $pageTitle = $query->title ?? "PARENTING STYLE";
        $this->generatePage(1, [
            'pageTitle' => $pageTitle
        ]);

        $fontFamily = $this->roots['font_family'];
        $this->Ln(15);
        $this->SetFont($fontFamily, 'B', 13);

        $rgb = array_map('trim', explode(',', $this->roots['primary_color']['RGB']));
        $this->SetTextColor((int)$rgb[0], (int)$rgb[1], (int)$rgb[2]);

        $this->SetX(10);
        $this->MultiCell(0, 8, $pageData->title ?? '', 0, 'L', false);

        $cellWidth = $this->contentMargin();

        if (!empty($pageData->description)) {
            // Description
            $this->SetFont($fontFamily, '', 13);
            $this->SetTextColor(60, 60, 60);

            $this->SetX(10);
            $this->MultiCell($cellWidth, 7, $pageData->description ?? '', 0, 'L');
        }

        if (!empty($pageData->content)) {
            $this->Ln(3);
            foreach ($pageData->content as $point) {
                $this->bulletPoint($point, ['setX' => 15]);
            }
        }

        $this->loveQuote();
    }

    function profession()
    {
        $query = ChildTableOfContent::where('id', 6)->first();
        $pageData = ChildProfession::where([
            'king_number' => LoshuHelper::getKingNumber($this->childInfo->dob ?? '')['king_no'] ?? ''
        ])->first();
        $pageTitle = $query->title ?? "PROFESSION";
        $this->generatePage(1, [
            'pageTitle' => $pageTitle
        ]);
        $fontFamily = $this->roots['font_family'];
        $this->Ln(15);
        foreach ($pageData->content ?? [] as $content) {

            $this->SetFont($fontFamily, 'B', 13);
            $this->SetX(10);
            $rgb = array_map('trim', explode(',', $this->roots['primary_color']['RGB']));
            $this->SetTextColor((int)$rgb[0], (int)$rgb[1], (int)$rgb[2]);
            $this->MultiCell(0, 8, $content['title'], 0, 'L', false);

            $cellWidth = $this->contentMargin();

            if (!empty($content['description'])) {
                // Description
                $this->SetFont($fontFamily, '', 13);
                $this->SetTextColor(60, 60, 60);
                $this->SetX(10);
                $this->MultiCell($cellWidth, 7, $content['description'] ?? '', 0, 'L');
            }

            // Bullet Points
            if (!empty($content['points'])) {
                $this->Ln(3);
                foreach ($content['points'] as $point) {
                    $this->bulletPoint($point, ['setX' => 15]);
                }
            }
            $this->Ln(5);
        }

        $this->loveQuote();
    }

    function nameSection()
    {
        $query = ChildTableOfContent::where('id', 7)->first();
        $pageTitle = $query->title ?? "NAME ANALYSIS";
        $this->generatePage(1, [
            'pageTitle' => $pageTitle
        ]);

        $fontFamily = $this->roots['font_family'];

        $this->Ln(15);
        $description = "Your Child's Name is not an accident. Your Child's Name Number must be aligned with your child's Birth Number (Mulank) as well as Life Path Number (Bhagyank). If your child's Life Path Number represents the purpose of their soul's birth, then their Name Number shows how they are going to achieve that purpose.\n\n";

        $description .= "Likewise, your child's Birth Number represents their special talent gifted by God, and the Name Number provides strength to it. If the Name Number is aligned with your child's Birth Number and Life Path Number, then your child's life journey will be smoother, and they will be able to achieve their maximum potential. There are two popular methods in Numerology to calculate the Name Number: Pythagorean and Chaldean.\n\n";

        $description .= "Steps to find your Child's Name Number as per Chaldean Numerology:";
        $cellWidth = $this->contentMargin();

        if (!empty($description)) {
            // Description
            $this->SetFont($fontFamily, '', 13);
            $this->SetTextColor(60, 60, 60);

            $this->SetX(10);
            $this->MultiCell($cellWidth, 7, $description ?? '', 0, 'L');
        }

        $points = [
            "Write out your child's full name and assign the appropriate numerical value to each letter.",
            "Add the numbers for your child's first name, then reduce the total to a single digit.",
            "Repeat the same process for your child's middle and last names.",
            "Add the three single-digit numbers obtained from the above steps, then reduce the total to a single digit to find your Child's Name Number."
        ];

        foreach ($points as $point) {
            $this->bulletPoint($point, ['setX' => 15]);
        }

        $this->Ln(4);

        $description = "Here is the example of How to calculate Name numbers:\n";
        $description .= "For example of Name SUJEET KUMAR MISHRA\n\n";
        $description .= "SUJEET: 3+6+1+5+5+4 = 24 = 2+4 = 6\n";
        $description .= "KUMAR: 2+6+4+1+2 = 15 = 1+5 = 6\n";
        $description .= "MISHRA: 4+1+3+5+2+1 = 16 = 1+6 = 7\n";
        $description .= "Total: 6+6+7 = 19 = 1+9 = 10 = 1+0 = 1\n\n";
        $description .= "Name Number is 1.\n";
        $description .= "In the below section, Your Name has been analyzed as per CHALDEAN NUMEROLOGY method to confirm whether your name is compatible with your Birthday Number(Moolank)as well as Life Path Number(Bhagyank) or not.\n\n";

        if (!empty($description)) {
            // Description
            $this->SetFont($fontFamily, '', 13);
            $this->SetTextColor(60, 60, 60);

            $this->SetX(10);
            $this->MultiCell($cellWidth, 7, $description ?? '', 0, 'L');
        }

        $this->Ln(3);
        $this->AddPage();

        $this->lineDraw(158, 155, 155);

        $results  = LoshuHelper::getLuckNumberData($this->childInfo['dob']);

        $nameOutput = [];
        $firstName = $this->childInfo->first_name ?? '';
        $fNameSum  = digSum1(chaldean_sum($firstName));

        $fullNameSum = digSum1(chaldean_sum($this->childName));
        $nameOutput['fullNameAnalysis'] = "Your child's Full Name Number as per Chaldean Numerology is: "
            . chaldean_sum($this->childName) . ' / ' . digSum1(chaldean_sum($this->childName));

        $nameOutput['firstNameAnalysis'] = "Your child's First Name Number as per Chaldean Numerology is: "
            . chaldean_sum($firstName) . ' / ' . digSum1(chaldean_sum($firstName));



        if (in_array($fNameSum, $results->lucky_numbers)) {
            $nameOutput['result'] = "Great! Your child's first name is compatible with and lucky for their Birth Number.";
        } elseif (in_array($fNameSum, $results->neutral_number)) {
            $nameOutput['result'] = "Your child's first name is neutral with respect to their Birth Number. It is not particularly lucky, but it is still workable.";
        } elseif (in_array($fNameSum, $results->unlucky_numbers)) {
            $nameOutput['result'] = "Your child's first name is not compatible with their Birth Number. We recommend changing it or consulting a qualified numerologist for a detailed Name Analysis. Highly recommended!";
        }

        if (in_array($fullNameSum, $results->lucky_numbers)) {
            $nameOutput['fullNameResult'] = "Great! Your child's full name is compatible with and lucky for their Birth Number.";
        } elseif (in_array($fullNameSum, $results->neutral_number)) {
            $nameOutput['fullNameResult'] = "Your child's full name is neutral with respect to their Birth Number. It is not particularly lucky, but it is still workable.";
        } elseif (in_array($fullNameSum, $results->unlucky_numbers)) {
            $nameOutput['fullNameResult'] = "Your child's full name is not compatible with their Birth Number. We recommend changing it or consulting a qualified numerologist for a detailed Name Analysis. Highly recommended!";
        }

        $nameOutput['favourableNumber'] = "Primary Favourable Number For Your Child: " . implode(", ", $results->lucky_numbers);
        $nameOutput['avoidableNumber']  = "Primary Avoidable Number For Your Child: " . implode(", ", $results->unlucky_numbers);


        if (!empty($nameOutput['firstNameAnalysis'] ?? '')) {

            //// HEADING HERE
            $this->Ln(3);
            $this->SetFont($fontFamily, 'B', 13);
            $rgb = array_map('trim', explode(',', $this->roots['primary_color']['RGB']));
            $this->SetTextColor((int)$rgb[0], (int)$rgb[1], (int)$rgb[2]);
            $this->SetX(10);
            $this->MultiCell(0, 8, "First Name Number:", 0, 'L', false);
            $this->Ln(1);

            // Description HERE
            $this->SetFont($fontFamily, '', 13);
            $this->SetTextColor(60, 60, 60);

            $this->SetX(10);
            $this->MultiCell($cellWidth, 7, ($nameOutput['firstNameAnalysis'] ?? ''), 0, 'L');
            $this->Ln(3);
        }


        $this->lineDraw(158, 155, 155);

        if (!empty($nameOutput['result'] ?? '')) {
            $this->Ln(3);

            //// HEADING HERE
            $this->SetFont($fontFamily, 'B', 13);
            $rgb = array_map('trim', explode(',', $this->roots['primary_color']['RGB']));
            $this->SetTextColor((int)$rgb[0], (int)$rgb[1], (int)$rgb[2]);
            $this->SetX(10);
            $this->MultiCell(0, 8, "CHALDEAN NAME ANALYSIS: Name Compatibility as per Bhagyank", 0, 'L', false);
            $this->Ln(1);

            // Description
            $this->SetFont($fontFamily, '', 13);
            $this->SetTextColor(60, 60, 60);

            $this->SetX(10);
            $this->MultiCell($cellWidth, 7, ($nameOutput['result'] ?? ''), 0, 'L');
            $this->Ln(3);
        }

        $this->lineDraw(158, 155, 155);

        if (!empty($nameOutput['fullNameResult'] ?? '')) {
            $this->Ln(3);

            //// HEADING HERE
            $this->SetFont($fontFamily, 'B', 13);
            $rgb = array_map('trim', explode(',', $this->roots['primary_color']['RGB']));
            $this->SetTextColor((int)$rgb[0], (int)$rgb[1], (int)$rgb[2]);
            $this->SetX(10);
            $this->MultiCell(0, 8, "CHALDEAN NAME ANALYSIS: Overall Name Compatibility as per Mulank & Bhagyank", 0, 'L', false);
            $this->Ln(1);

            // Description
            $this->SetFont($fontFamily, '', 13);
            $this->SetTextColor(60, 60, 60);

            $this->SetX(10);
            $this->MultiCell($cellWidth, 7, ($nameOutput['fullNameResult'] ?? ''), 0, 'L');
            $this->Ln(3);
        }

        $this->lineDraw(158, 155, 155);

        if (!empty($nameOutput['fullNameAnalysis'] ?? '')) {
            $this->Ln(3);

            //// HEADING HERE
            $this->SetFont($fontFamily, 'B', 13);
            $rgb = array_map('trim', explode(',', $this->roots['primary_color']['RGB']));
            $this->SetTextColor((int)$rgb[0], (int)$rgb[1], (int)$rgb[2]);
            $this->SetX(10);
            $this->MultiCell(0, 8, "Full Name Number:", 0, 'L', false);
            $this->Ln(1);

            // Description
            $this->SetFont($fontFamily, '', 13);
            $this->SetTextColor(60, 60, 60);

            $this->SetX(10);
            $this->MultiCell($cellWidth, 7, ($nameOutput['fullNameAnalysis'] ?? ''), 0, 'L');
            $this->Ln(3);
        }


        $this->lineDraw(158, 155, 155);

        $description = "";

        if (!empty($nameOutput['favourableNumber'] ?? '')) {
            $description .= $nameOutput['favourableNumber'] . "\n";
        }
        if (!empty($nameOutput['avoidableNumber'] ?? '')) {
            $description .= $nameOutput['avoidableNumber'] . "\n";
        }

        if (!empty($nameOutput['favourableNumber'] ?? '') || !empty($nameOutput['avoidableNumber'] ?? '')) {
            $this->Ln(3);

            //// HEADING HERE
            $this->SetFont($fontFamily, 'B', 13);
            $rgb = array_map('trim', explode(',', $this->roots['primary_color']['RGB']));
            $this->SetTextColor((int)$rgb[0], (int)$rgb[1], (int)$rgb[2]);
            $this->SetX(10);
            $this->MultiCell(0, 8, "Suggested NAME NUMBER (FIRST & FULL NAME) as per your Birthday (Mulank) & Life Path Number (Bhagyank) :", 0, 'L', false);
            $this->Ln(1);

            // Description
            $this->SetFont($fontFamily, '', 13);
            $this->SetTextColor(60, 60, 60);

            $this->SetX(10);
            $this->MultiCell($cellWidth, 7, $description ?? '', 0, 'L');
            $this->Ln(3);
        }

        $this->loveQuote();
    }

    function lineDraw($rgb1, $rgb2, $rgb3)
    {
        $this->SetDrawColor($rgb1, $rgb2, $rgb3);
        $this->SetLineWidth(0.3);

        $x1 = 10;
        $x2 = 199;
        $y = $this->GetY();

        $this->Line($x1, $y, $x2, $y);
    }

    function signature()
    {
        $query = ChildTableOfContent::where('id', 8)->first();
        $pageData = ChildReportSignature::get();
        $pageTitle = $query->title ?? "SIGNATURE";
        $this->generatePage(1, [
            'pageTitle' => $pageTitle
        ]);

        foreach ($pageData as $index => $data) {
            $fontFamily = $this->roots['font_family'];
            $this->Ln($index == 0 ? 15 : 10);
            $this->SetFont($fontFamily, 'B', 13);

            $rgb = array_map('trim', explode(',', $this->roots['primary_color']['RGB']));
            $this->SetTextColor((int)$rgb[0], (int)$rgb[1], (int)$rgb[2]);

            $this->SetX(10);
            $this->MultiCell(0, 8, $data->title, 0, 'L', false);

            if ($index == 0) {
                $this->Ln(5);
                $nimage = public_path('assets/img/sample-signature-2.png');

                $leftMargin = 10;
                $rightMargin = 10;

                $imgWidth = $this->GetPageWidth() - $leftMargin - $rightMargin;

                $this->Image(
                    $nimage,
                    $leftMargin,
                    $this->GetY(),
                    $imgWidth
                );

                $this->Ln(110);
            }

            if (!empty($data->content)) {
                $this->Ln(3);
                foreach ($data->content as $point) {
                    $this->bulletPoint($point, ['setX' => 15]);
                }
            }
        }

        $this->loveQuote();
    }

    function missingremedies()
    {
        $query = ChildTableOfContent::where('id', 9)->first();

        $pageData = ChildMissingNumberRemedy::whereIn(
            'missing_number',
            $this->missingNumbers()
        )->get();

        $pageTitle = $query->title ?? "MISSING NUMBERS REMEDIES";

        $numbersInChart = $this->numbersInCharts()[1];
        $personalGrid = array_chunk($numbersInChart, 3);

        $gridWidth = 90;

        foreach ($pageData as $index => $data) {

            // First page already generated, next records get a new page
            if ($index > 0) {
                $this->AddPage();
            }

            // Generate page/header
            if ($index === 0) {
                $this->generatePage(1, [
                    'pageTitle' => $pageTitle
                ]);
            }

            // Get actual page width every time
            $pageWidth = $this->GetPageWidth();

            // Center Loshu Grid horizontally
            $x = ($pageWidth - $gridWidth) / 2;

            $this->drawLoshuGrid(
                $x,
                53,
                $this->childName . " Loshu Grid",
                $personalGrid,
                'Your personal energy pattern',
                $data['missing_number'] ?? 0
            );

            $fontFamily = $this->roots['font_family'];

            $this->Ln(20);

            $this->SetFont($fontFamily, 'B', 13);

            $rgb = array_map(
                'trim',
                explode(',', $this->roots['primary_color']['RGB'])
            );

            $this->SetTextColor(
                (int) $rgb[0],
                (int) $rgb[1],
                (int) $rgb[2]
            );

            $this->SetX(10);

            $this->MultiCell(
                0,
                8,
                'GUIDANCE & REMEDIES FOR MISSING NUMBER ' .
                    ($data->missing_number ?? ''),
                0,
                'L',
                false
            );

            if (!empty($data->content)) {

                $this->Ln(3);

                foreach ($data->content as $point) {
                    $this->bulletPoint($point, [
                        'setX' => 15
                    ]);
                }
            }
        }

        $this->loveQuote();
    }


    function remedies()
    {
        $query = ChildTableOfContent::where('id', 10)->first();
        $pageData = ChildRemedy::king($this->kingNumber)->first();
        $pageTitle = $query->title ?? "REMEDIES";
        $this->generatePage(1, [
            'pageTitle' => $pageTitle
        ]);
        $fontFamily = $this->roots['font_family'];
        $cellWidth = $this->contentMargin();

        $this->Ln(15);

        $paragraph = "Every planet carries a unique energy that influences your child's personality, emotions, learning style, and overall life journey. These simple yet effective remedies are designed to strengthen the positive qualities of the ruling planet while balancing its challenging influences. By practising these remedies consistently with sincerity and discipline, your child can develop greater confidence, emotional stability, wisdom, and overall well-being.";
        $this->SetFont($fontFamily, '', 13);
        $this->SetTextColor(60, 60, 60);
        $this->SetX(10);
        $this->MultiCell($cellWidth, 7, $paragraph ?? '', 0, 'L');
        $this->Ln(5);

        $this->printLabelValue('Planet: ', $pageData->content['planet'] . ' (Mulank ' . $pageData->king_number . ')');
        $this->printLabelValue('Day: ', $pageData->content['day']);
        $this->printLabelValue('Best Time: ', $pageData->content['best_time']);

        if (!empty($pageData->content['points'] ?? '')) {
            $this->Ln(3);

            $paragraph = "The following remedies are easy to incorporate into your child's weekly routine. They are safe, practical, and spiritually uplifting habits that gradually enhance positive planetary vibrations and support balanced growth in everyday life.";
            $this->SetFont($fontFamily, '', 13);
            $this->SetTextColor(60, 60, 60);
            $this->SetX(10);
            $this->MultiCell($cellWidth, 7, $paragraph ?? '', 0, 'L');
            $this->Ln(5);

            foreach ($pageData->content['points'] as $point) {
                $this->bulletPoint($point, ['setX' => 15]);
            }
        }


        $this->Ln(6);
        $this->SetFont($fontFamily, 'B', 13);
        $rgb = array_map('trim', explode(',', $this->roots['primary_color']['RGB']));
        $this->SetTextColor((int)$rgb[0], (int)$rgb[1], (int)$rgb[2]);

        $this->SetX(10);
        $this->MultiCell(0, 8, "Student Success Mantra Practice", 0, 'L', false);
        $this->Ln(3);
        $this->printLabelValue('Before the Exam:', "");
        foreach (
            [
                "Ayeim - For confidence and success in examinations.",
                "Saraswati - To enhance learning ability, memory, and understanding of studies.",
                "Gandiv - For sharp focus, concentration, and mental clarity."
            ] as $point
        ) {
            $this->bulletPoint($point, ['setX' => 15]);
        }

        $this->Ln(5);
        $this->SetFont($fontFamily, '', 13);
        $this->SetTextColor(60, 60, 60);
        $this->SetX(10);
        $this->MultiCell($cellWidth, 7, "Chant these mantras with faith and a positive mindset before appearing for the exam.", 0, 'L');

        $this->printLabelValue('After the Exam:', "");
        foreach (
            [
                "Safaltav - For achieving success and receiving favorable results."
            ] as $point
        ) {
            $this->bulletPoint($point, ['setX' => 15]);
        }
        $this->SetTextColor(60, 60, 60);
        $this->SetX(10);
        $this->MultiCell($cellWidth, 7, "Chant this mantra after completing the exam while expressing gratitude and maintaining confidence in a positive outcome.\n\nChant 108 each", 0, 'L');


        $this->loveQuote();
    }

    function printLabelValue($Label, $Value)
    {
        $fontFamily = $this->roots['font_family'];
        if (!empty($Label)) {
            $rgb = array_map('trim', explode(',', $this->roots['black_color']['RGB']));

            $this->SetX(10);

            // Label
            $this->SetFont($fontFamily, 'B', 13);
            $this->SetTextColor((int)$rgb[0], (int)$rgb[1], (int)$rgb[2]);
            $this->Write(8, $Label);

            // Value
            $this->SetFont($fontFamily, '', 13);
            $this->SetTextColor(60, 60, 60);
            $this->Write(8, $Value);

            $this->Ln(10);
        }
    }

    /// Last Page
    function lastPageHere()
    {
        $bgimage = public_path(self::IMAGEPATH . 'last.png');

        $this->AddPage('P');
        $this->resizeToFit($bgimage);
    }
}
