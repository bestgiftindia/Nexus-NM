<?php

namespace App\Services\Relationship;

use App\Models\Relationship\HowManageYourRelationship;
use App\Models\Relationship\Relationship as RelationshipModel;
use App\Models\Relationship\RelationshipFinalConclusion;
use App\Models\Relationship\RelationshipLoveQuote;
use App\Services\Login\LoginService;
use FPDF;
use Illuminate\Support\Facades\DB;
use App\Models\Relationship\RelationshipBehaviorPattern;
use App\Models\Relationship\RelationshipBestMarriageDateContent;
use App\Models\Relationship\RelationshipBirthDestinyRelation;
use App\Models\Relationship\RelationshipIntroductionContent;
use App\Models\Relationship\RelationshipMarriagePatternNextSevenYear;
use App\Models\Relationship\RelationshipRemedy;
use App\Models\Relationship\RelationshipSelectYourPartner;
use App\Models\Relationship\RelationshipTableOfContent;

class ReportService extends FPDF
{
    const DPI = 150;
    const A4_HEIGHT = 210;
    const A4_WIDTH = 297;
    const MAX_WIDTH = 500;
    const MAX_HEIGHT = 700;
    const MM_IN_INCH = 25.4;

    protected $brandColor = ['HEX' => '#e7a90f', 'RGB' => '231, 169, 15'];
    protected $textColor = ['HEX' => '#22357f', 'RGB' => '30, 42, 158'];
    protected $blackColor = ['HEX' => '#00000', 'RGB' => '0, 0, 0'];

    protected $fontFamily = "Times";
    protected $processingCoverPage = false;

    protected RelationshipService $relationshipService;
    protected LoginService $loginUserService;
    protected int $generateReportId;
    protected object $relationshipInfo, $loginUser;
    protected string $customerName;
    protected string $dateOfBirth;
    protected int $kingNumber, $queenNumber;
    protected $defaultUnlucky = [4, 7, 8];


    public function __construct(
        RelationshipService $relationshipService,
        LoginService $loginService,
        int $generateReportId
    ) {
        parent::__construct();
        $this->generateReportId = $generateReportId;
        $this->loginUserService = $loginService;
        $this->relationshipService = $relationshipService;
        $this->initializeData();
    }

    function initializeData()
    {
        $this->loginUser = $this->loginUserService->findLoginUserService();
        $this->relationshipInfo = $this->relationshipService->findService($this->generateReportId);
        $this->customerName = $this->relationshipInfo->first_name ?? '';
        if (!empty($this->relationshipInfo->middle_name ?? '')) {
            $this->customerName .= ' ' . $this->relationshipInfo->middle_name ?? '';
        }
        if (!empty($this->relationshipInfo->last_name ?? '')) {
            $this->customerName .= ' ' . $this->relationshipInfo->last_name ?? '';
        }
        $this->dateOfBirth = \Carbon\Carbon::parse($this->relationshipInfo->dob)->format('d M Y');
    }

    private function resizeToFit($imgFilename)
    {
        //     list($imgW, $imgH) = getimagesize($imgFilename);

        //     $pageW = $this->GetPageWidth();
        //     $pageH = $this->GetPageHeight();

        //     // Desired margins
        //     $marginX = 0;
        //     $marginY = 0;

        //     $availableW = $pageW - ($marginX * 2);
        //     $availableH = $pageH - ($marginY * 2);

        //     $imgRatio = $imgW / $imgH;

        //     // Fit image inside available area
        //     $newW = $availableW;
        //     $newH = $newW / $imgRatio;

        //     if ($newH > $availableH) {
        //         $newH = $availableH;
        //         $newW = $newH * $imgRatio;
        //     }

        //     // Center image
        //     $x = ($pageW - $newW) / 2;
        //     $y = ($pageH - $newH) / 2;

        //    return  $this->Image($imgFilename, $x, $y, $newW, $newH);
        return $this->Image($imgFilename, 0, 0, $this->w, $this->h);
    }

    public function Header()
    {
        $bgimage = public_path('assets/media/pdf-image/relationship/page.jpg');
        if (file_exists($bgimage)) {
            $this->Image(
                $bgimage,
                0,
                0,
                $this->GetPageWidth(),
                $this->GetPageHeight()
            );
            $this->headerContent();
        }
        $this->SetXY(88, 30);
    }

    public function Footer()
    {
        if ($this->PageNo() > 1) {
            $this->SetXY(55.5, 277.1);
            $this->SetFont($this->fontFamily, 'B', 9);
            $this->SetTextColor(106, 90, 122);
            $this->Cell(0, 10, $this->PageNo(), 0, 0, 'C');
        }
        return false;
    }

    function generatePDF()
    {
        $this->SetMargins(10, 10, 10);

        $this->processingCoverPage = true;

        $dob = $this->relationshipInfo->dob;
        $this->kingNumber = getKingNumber($dob);
        $this->queenNumber = getQueenNumber($dob);

        $this->firstPageHere();
        $this->TableOfContentHere();
        $this->IntroductionHere(01);
        $this->yourNumerologyReport(02);
        $this->yourBehaviourPattern(03);
        $this->yourBirthDestinyReport(04);
        $this->yourMarriageStatus(05);
        $this->bestDateMarriage(06);
        $this->manageRelationship(07);
        $this->magicalGuide(8);

        $missingNumbers = $this->missingNumber();
        $finalConclusionNumber = 10;
        if (count(array_intersect([2, 5, 6], $missingNumbers)) > 0) {
            $this->remediesLoveLife(9);
        } else {
            $finalConclusionNumber = 9;
        }

        $this->finalConclusion($finalConclusionNumber);
        $this->lastPageHere();

        $this->processingCoverPage = false;
        $this->Output('D', 'Relationship_Report_' . ($this->customerName) . '.pdf');
        exit;
    }


    function missingNumber()
    {
        $dob = $this->relationshipInfo->dob;
        $dobArr = explode("-", $dob);
        $calculateLoshu = calculateLoshuGrid($dobArr[2], $dobArr[1], $dobArr[0], $this->kingNumber, $this->queenNumber);

        $calculateLoshuGrid = $calculateLoshu['loshuGrid'];
        $missingNumbers = [];
        foreach ($calculateLoshuGrid as $number => $value) {
            if (!empty($value)) {
                $numbersInChart[] = $number;
            } else {
                $missingNumbers[] = $number;
            }
        }
        return $missingNumbers;
    }

    function firstPageHere()
    {
        $bgimage = public_path('assets/media/pdf-image/relationship/first-page.jpg');
        $this->AddPage('P');
        // Background Image
        // $this->Image($bgimage, 0, 0, $this->w, $this->h);
        $this->resizeToFit($bgimage);


        $this->SetXY(18, 175);
        $this->SetFont($this->fontFamily, 'B', 24);
        $rgb = array_map('trim', explode(',', $this->textColor['RGB']));
        $this->SetTextColor((int) $rgb[0], (int) $rgb[1], (int) $rgb[2],);
        $this->Cell(0, 10, $this->customerName, 0, 1, 'C');

        $locationString = '';
        if (!empty($this->relationshipInfo->dob)) {
            $locationString .= \Carbon\Carbon::parse($this->relationshipInfo->dob)->format('d M, Y');
        }
        if (!empty($this->relationshipInfo->tob)) {
            $locationString .= ' | ' . \Carbon\Carbon::parse($this->relationshipInfo->tob)->format('h:i A');
        }
        if (!empty($this->relationshipInfo->location)) {
            $locationString .= ' | ' . $this->relationshipInfo->location;
        }

        $this->SetXY(18, 195);
        $this->SetFont($this->fontFamily, 'B', 11);
        $rgb = array_map('trim', explode(',', $this->blackColor['RGB']));
        $this->SetTextColor((int) $rgb[0], (int) $rgb[1], (int) $rgb[2],);
        $this->Cell(0, 10, $locationString, 0, 1, 'C');
    }

    function headerContent()
    {
        $this->SetXY(88, 15.5);
        $this->SetFont($this->fontFamily, 'B', 9);
        $this->SetTextColor(149, 148, 148);
        $this->Cell(100, 10, $this->customerName ?: 'TEST', 0, 1, 'R');
    }

    function BlankPageHere(string $title, int $headerCount = 0)
    {
        $imageName = 'blank-page';
        $bgimage = public_path('assets/media/pdf-image/relationship/' . $imageName . '.jpg');

        $this->AddPage('P');
        $this->resizeToFit($bgimage);
        $this->headerContent();

        // Header Heading Count Number
        $this->SetXY(25, 27.5);
        $this->SetFont($this->fontFamily, 'B', 18);
        $rgb = array_map('trim', explode(',', $this->brandColor['RGB']));
        $this->SetTextColor((int) $rgb[0], (int) $rgb[1], (int) $rgb[2],);

        if ($headerCount > 0) {
            $headerCount = $headerCount <= 9 ? '0' . $headerCount : $headerCount;
            $this->Cell(0, 10, $headerCount, 0, 1, 'L');
            $this->SetXY(35, 27.5);
        } else {
            $this->Cell(0, 10, "*", 0, 1, 'L');
            $this->SetXY(31, 27.5);
        }

        /// Header Heading Content

        $this->SetFont($this->fontFamily, 'B', 16);
        $this->SetTextColor(255, 255, 255);
        $this->Cell(0, 10, $title, 0, 1, 'L');
    }

    function lastPageHere()
    {
        $bgimage = public_path('assets/media/pdf-image/relationship/last-page.jpg');
        $this->AddPage('P');
        $this->resizeToFit($bgimage);
        $this->headerContent();
    }

    public function insightBox(string $text)
    {
        $this->AddPage();
        $x = 23.5;
        $y = $this->GetY() + 10;

        $width = 163.5;
        $padding = 4;
        $lineHeight = 6;

        // Font set before height calculation
        $this->SetFont($this->fontFamily, 'B', 13);

        // Estimate lines
        $usableWidth = $width - ($padding * 2);

        $words = explode(' ', $text);
        $line = '';
        $lines = 1;

        foreach ($words as $word) {
            $testLine = $line . $word . ' ';

            if ($this->GetStringWidth($testLine) > $usableWidth) {
                $lines++;
                $line = $word . ' ';
            } else {
                $line = $testLine;
            }
        }

        $height = ($lines * $lineHeight) + ($padding * 2);

        // Background
        $this->SetFillColor(248, 243, 232);

        // Border
        $this->SetDrawColor(230, 175, 20);
        $this->SetLineWidth(0.5);

        // Box
        $this->Rect($x, $y, $width, $height, 'DF');

        // Text
        $this->SetXY($x + $padding, $y + $padding);

        $this->SetFont($this->fontFamily, 'B', 13);
        $this->SetTextColor(18, 38, 130);

        $this->MultiCell(
            $usableWidth,
            $lineHeight,
            $text,
            0,
            'L'
        );

        // Cursor box ke niche le jao
        $this->SetY($y + $height + 5);
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

        $leftMargin = 23;
        $tableWidth = $this->GetPageWidth() - (2 * $leftMargin);

        $lineHeight = 8;
        $paddingX = 2;      // Left & right padding
        $paddingY = 1;      // Top & bottom padding

        // Number of columns
        $columnCount = count($rows[0]);

        // Equal width for all columns
        $widths = array_fill(0, $columnCount, $tableWidth / $columnCount);

        foreach ($rows as $rowIndex => $row) {

            $x = $leftMargin;
            $y = $this->GetY();

            // Header Row
            if ($rowIndex == 0) {

                $this->SetXY($x, $y + 1);
                $this->SetFont($this->fontFamily, 'B', 12);
                $this->SetFillColor(35, 52, 170);
                $this->SetTextColor(255, 255, 255);
                $this->SetDrawColor(150, 150, 150);

                foreach ($row as $i => $cell) {
                    $this->Cell($widths[$i], 10, $cell, 1, 0, 'C', true);
                }

                $this->Ln();
                continue;
            }

            // Calculate max lines for this row
            $this->SetFont($this->fontFamily, '', 12);

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
                    $this->SetTextColor(204, 51, 102);
                    $this->SetFont($this->fontFamily, 'B', 13);
                } else {
                    $this->SetTextColor(0, 0, 0);
                    $this->SetFont($this->fontFamily, '', 12);
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

    /// (◆) Star Sign Here
    function startSign($headingText = null)
    {
        $this->SetX(22);

        $this->SetTextColor(212, 165, 0); // Gold
        $this->SetFont('ZapfDingbats', '', 12);
        $this->Cell(8, 6, chr(117), 0, 0);

        $this->SetX(27);
        $this->SetFont($this->fontFamily, 'B', 14);
        $rgb = array_map('trim', explode(',', $this->textColor['RGB']));
        $this->SetTextColor((int) $rgb[0], (int) $rgb[1], (int) $rgb[2],);
        $this->MultiCell(160, 8, $headingText, 0, 'L');
    }

    public function favourBox($text)
    {
        $this->Ln(5);

        $x = 23;
        $w = 162;
        $h = 12;

        // Current Y position
        $y = $this->GetY();

        // Background color
        $this->SetFillColor(255, 247, 230);

        // Draw filled rectangle
        $this->Rect($x, $y, $w, $h, 'F');

        // Top and bottom golden lines
        $this->SetDrawColor(226, 180, 30);
        $this->Line($x, $y, $x + $w, $y);
        $this->Line($x, $y + $h, $x + $w, $y + $h);

        // Text
        $this->SetXY($x, $y + 2);

        $this->SetFont($this->fontFamily, 'B', 15);
        $this->SetTextColor(25, 45, 160);

        $this->Cell($w, 8, $text, 0, 1, 'C');

        $this->Ln(5);
    }

    public function loveQuote()
    {
        $quoteData = RelationshipLoveQuote::inRandomOrder()->first();
        $text = $quoteData->title;

        $this->Ln(10);

        // Top Line
        $this->SetDrawColor(209, 154, 32);
        $this->SetLineWidth(0.3);

        $x1 = 20;
        $x2 = 190;
        $y = $this->GetY();

        $this->Line($x1, $y, $x2, $y);

        $this->Ln(4);

        // Calculate text width
        $this->SetFont($this->fontFamily, 'I', 13);
        $textWidth = $this->GetStringWidth($text);

        $heartWidth = 8;
        $gap = 1;

        $totalWidth = $heartWidth + $gap + $textWidth + $gap + $heartWidth;

        // Center horizontally
        $startX = ($this->GetPageWidth() - $totalWidth) / 2;

        // Left heart
        $this->SetXY($startX, $this->GetY());
        $this->SetFont('ZapfDingbats', '', 12);
        $this->SetTextColor(200, 50, 110);
        $this->Cell($heartWidth, 8, chr(170), 0, 0, 'C');

        // Text
        $this->SetFont($this->fontFamily, 'I', 13);
        $this->Cell($gap, 8, '', 0, 0);
        $this->Cell($textWidth, 8, $text, 0, 0, 'C');

        // Right heart
        $this->Cell($gap, 8, '', 0, 0);
        $this->SetFont('ZapfDingbats', '', 12);
        $this->Cell($heartWidth, 8, chr(170), 0, 1, 'C');

        $this->Ln(2);

        // Bottom Line
        $y = $this->GetY();
        $this->Line($x1, $y, $x2, $y);

        $this->Ln(5);
    }

    function redTextHeading(string $headingText)
    {
        // Draw Line
        $this->SetDrawColor(203, 70, 113);
        $this->SetLineWidth(1.1);
        $this->Line(24, 55, 24, 59);

        /// Heading Content
        $this->Ln(5);
        $this->SetX(26);
        $this->SetFont($this->fontFamily, 'B', 13);
        $this->SetTextColor(203, 70, 113);
        $this->MultiCell(160, 5, $headingText, 0, 'J');
    }

    public function bulletPoint($text, $options = [])
    {
        $this->SetX($options['setX'] ?? 27);

        // Golden bullet
        if (($options['pinkDot'] ?? true) === true) {
            $this->SetTextColor(191, 49, 101);
        } else {
            $this->SetTextColor(212, 175, 55);
        }

        $this->SetFont('Times', '', 18);
        $this->Cell(6, 8, chr(149), 0, 0); // •

        // Text
        $this->SetTextColor(60, 60, 60);
        $this->SetFont($this->fontFamily, '', 13);
        $this->MultiCell(155, 6, $text, 0, 'L');

        $this->Ln(1);
    }

    public function behaviorTitle($title)
    {
        $y = $this->GetY();

        // Background
        $this->SetFillColor(249, 239, 242); // light pink
        $this->Rect(15, $this->GetY(), 180, 12, 'F');

        // Vertical dark pink line with top/bottom margin
        $this->SetFillColor(191, 49, 101);
        $this->Rect(23, $y + 2, 1, 7, 'F');

        // Text
        $this->SetXY(26, $this->GetY() + 2);
        $this->SetFont('Times', 'B', 12);
        $this->SetTextColor(191, 49, 101);

        $this->Cell(0, 7, $title, 0, 1);
    }

    function luckyUnluckyModel()
    {
        return $queryData =  DB::table('lucky_unlucky_number_table')->where('king', $this->kingNumber)
            ->where('queen', $this->queenNumber)
            ->first();
    }

    function tableOfContent($id)
    {
        return RelationshipTableOfContent::find($id);
    }


    // Table of content 1

    function TableOfContentHere(int $HeadingCount = 0)
    {
        $lists = RelationshipTableOfContent::all();

        $title = 'Contents';
        $this->BlankPageHere($title, $HeadingCount);

        $missingNumbers = $this->missingNumber();
        $A = 1;
        foreach ($lists as $key => $list) {

            if (
                $key == 8 &&
                count(array_intersect([2, 5, 6], $missingNumbers)) == 0
            ) {
                continue;
            }



            $countNum = $A;
            $countNum = ($countNum <= 9) ? '0' . $countNum : $countNum;

            // Number
            if ($key === 0) {
                $this->Ln(3);
            }

            $this->SetX(20);
            $this->SetFont($this->fontFamily, 'B', 16);
            $this->SetTextColor(194, 133, 0); // Gold
            $this->Cell(20, 5, $countNum, 0, 0, 'C');

            // Title
            $this->SetFont($this->fontFamily, '', 15);
            $this->SetTextColor(38, 52, 156); // Blue
            $this->Cell(100, 5, $list->title, 0, 1, 'L');

            $this->Ln(4);
            $A++;
        }
    }

    function IntroductionHere(int $HeadingCount)
    {
        $intoData = RelationshipIntroductionContent::all();
        $title = $this->tableOfContent(1)->title ?? 'Introduction';
        $this->BlankPageHere($title, $HeadingCount);

        foreach ($intoData as $into) {
            $this->Ln(3);
            $this->startSign($into->title);

            if (!empty($into->content['description'])) {
                $this->Ln(3);
                $this->SetX(23);
                $this->SetFont($this->fontFamily, '', 13);
                $this->SetTextColor(60, 60, 60);
                $text = trim($into->content['description'] ?? '');
                $this->MultiCell(160, 6, $text, 0, 'J');
            }

            $la = 0;
            foreach ($into->content['lists_points'] ?? [] as $label => $listpoints) {
                $this->Ln(3);
                $this->SetX(25);
                $this->SetFont($this->fontFamily, 'B', 13);
                $this->SetTextColor(191, 49, 101);
                $text = trim($label);
                $this->MultiCell(160, 6, $text . ' : ', 0, 'J');

                foreach ($listpoints as $index => $listpoint) {
                    if ($index === 0) {
                        $this->Ln(3);
                    }
                    $this->bulletPoint($listpoint);
                }
                $la++;
            }

            foreach ($into->content['points'] ?? [] as $key => $points) {
                if ($key === 0) {
                    $this->Ln(3);
                }
                $this->bulletPoint($points);
            }
        }
    }

    public function drawLoshuGrid($x, $y, $title, $data, $footer)
    {
        $rgb = array_map('trim', explode(',', $this->textColor['RGB']));

        // Card dimensions
        $cardW = 80;
        $cardH = 70;

        // Card background
        $this->SetFillColor(245, 245, 245);
        $this->SetDrawColor(220, 220, 220);
        $this->RoundedRect($x, $y, $cardW, $cardH, 4, 'DF');

        // Title
        $this->SetFont('Arial', 'B', 14);
        $this->SetTextColor(204, 51, 102);
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

        $this->SetFont('Arial', 'B', 12);

        for ($row = 0; $row < 3; $row++) {
            for ($col = 0; $col < 3; $col++) {

                $value = !empty($data[$row][$col])
                    ? $data[$row][$col]
                    : '-';

                $cellX = $startX + ($col * ($cellW + $gap));
                $cellY = $startY + ($row * ($cellH + $gap));

                // Cell background
                if (!empty($data[$row][$col])) {
                    $this->SetFillColor(215, 225, 240);
                } else {
                    $this->SetFillColor(236, 239, 246);
                }

                $this->RoundedRect($cellX, $cellY, $cellW, $cellH, 2, 'F');

                // Text color
                $this->SetTextColor(
                    (int)$rgb[0],
                    (int)$rgb[1],
                    (int)$rgb[2]
                );

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

    public function kingQueenSection($luckyNumbers, $unluckyNumbers, $neutralNumbers)
    {
        $leftMargin = 22;
        $rightMargin = 24;
        $gap = 2;

        $availableWidth = 210 - $leftMargin - $rightMargin; // A4 width
        $boxWidth = ($availableWidth - (2 * $gap)) / 3;

        $y = $this->GetY() + 15;
        $boxHeight = 23;

        $black = explode(",", $this->textColor['RGB']);

        $gold = [204, 51, 102];
        $border = [220, 220, 220];
        $bg = [248, 248, 248];

        $sections = [
            [
                'title' => 'Lucky Number',
                'value' => !empty($luckyNumbers) ? str_replace(',', ", ", $luckyNumbers) : '-',
            ],
            [
                'title' => 'Unlucky Number',
                'value' => !empty($unluckyNumbers) ? str_replace(',', ", ", $unluckyNumbers) : '-',
            ],
            [
                'title' => 'Neutral Numbers',
                'value' => !empty($neutralNumbers) ? str_replace(',', ", ", $neutralNumbers) : '-',
            ],
        ];

        foreach ($sections as $index => $section) {

            $currentX = $leftMargin + ($index * ($boxWidth + $gap));

            $this->SetFillColor(248, 248, 248);
            $this->SetDrawColor(220, 220, 220);
            $this->RoundedRect($currentX, $y, $boxWidth, $boxHeight, 3, 'DF');

            // Title
            $this->SetXY($currentX, $y + 3);
            $this->SetFont($this->fontFamily, 'B', 12);
            $this->SetTextColor(204, 51, 102);
            $this->Cell($boxWidth, 6, $section['title'], 0, 1, 'C');

            // Value
            $this->SetXY($currentX, $y + 12);
            $this->SetFont($this->fontFamily, 'B', 14);
            $this->SetTextColor($black[0], $black[1], $black[2]);
            $this->Cell($boxWidth, 8, $section['value'], 0, 1, 'C');
        }

        // Next content ke liye cursor neeche le jao
        $this->SetY($y + $boxHeight);
    }

    function yourNumerologyReport($HeadingCount)
    {

        $title = $this->tableOfContent(2)->title ?? 'Your Numerology Scope';

        $dob = $this->relationshipInfo->dob;
        $queryData =  $this->luckyUnluckyModel();

        $supportiveNumbers = explode(",", $queryData->balancer);
        /// lucky number
        $supportiveNumbersContent = '';

        sort($supportiveNumbers);
        // foreach ($supportiveNumbers as $supportiveNumber) {
        //     $planetData = getNumberDetails()[$supportiveNumber];
        //     $supportiveNumbersContent .= chr(149) . ' ' . $planetData['planet'] . ' (' . $supportiveNumber . ') - ' . $planetData['traits'] . "\n";
        // }


        /// unlucky number
        $natureNumbers = !empty($queryData->unlucky_number) ? explode(",", $queryData->unlucky_number) : $this->defaultUnlucky;
        $natureNumberContent = '';
        sort($natureNumbers);
        // foreach ($natureNumbers as $natureNumber) {
        //     $planetData = getNumberDetails()[$natureNumber] ?? [];
        //     if ($planetData) {
        //         $natureNumberContent .= chr(149) . ' ' . $planetData['planet'] . ' (' . $natureNumber . ') - ' . ($planetData['traits'] ?? '') . "\n";
        //     }
        // }

        /// Numbers in chart
        $dobArr = explode("-", $dob);
        $calculateLoshu = calculateLoshuGrid($dobArr[2], $dobArr[1], $dobArr[0], $this->kingNumber, $this->queenNumber);

        $calculateLoshuGrid = $calculateLoshu['loshuGrid'];
        $numbersInChart = [];
        foreach ($calculateLoshuGrid as $number => $value) {
            if (!empty($value)) {
                $numbersInChart[] = $number;
            }
        }

        $missingNumbers = $this->missingNumber();

        sort($numbersInChart);
        sort($missingNumbers);

        $table = [
            ['Energy', 'Detail'],
            ['Mulank Number', $this->kingNumber . ' - ' . planets($this->kingNumber)],
            ['Bhagyank Number', $this->queenNumber . ' - ' . planets($this->queenNumber)],
            ['Numbers in chart', implode(', ', $numbersInChart)],
            ['Missing Numbers', implode(', ', $missingNumbers)],
            // ['Supportive numbers present', $supportiveNumbersContent],
            // ['Number to nurture', $natureNumberContent]
        ];

        $this->BlankPageHere($title, $HeadingCount);



        /// Grid Chat Here

        $personalGrid = array_chunk($calculateLoshuGrid, 3);


        $standardGrid = [
            [4, 9, 2],
            [3, 5, 7],
            [8, 1, 6],
        ];

        $this->drawLoshuGrid(
            22,
            43,
            'Standard Loshu Grid',
            $standardGrid,
            'The universal energy pattern'
        );

        $this->drawLoshuGrid(
            105,
            43,
            $this->customerName . " Grid",
            $personalGrid,
            'Your personal energy pattern'
        );

        $this->kingQueenSection(
            $queryData->balancer,
            $queryData->unlucky_number,
            $queryData->neutral_number
        );

        $this->Ln(10);
        $starHeading = $this->customerName . ' - ' . $this->dateOfBirth;
        $this->startSign($starHeading);

        $this->Ln(2);
        $this->generateTable($table);

        $this->Ln(5);
        $this->startSign('Supportive numbers present');
        foreach ($supportiveNumbers as $supportiveNumber) {
            $planetData = getNumberDetails()[$supportiveNumber];
            $this->bulletPoint($planetData['planet'] . ' (' . $supportiveNumber . ') - ' . $planetData['traits'], [
                'setX' => 35,
                'pinkDot' => true
            ]);
        }

        // $this->Ln(5);
        // $this->startSign('Number to nurture');
        // foreach ($natureNumbers as $natureNumber) {
        //     $planetData = getNumberDetails()[$natureNumber] ?? [];
        //     if ($planetData) {
        //         $this->bulletPoint($planetData['planet'] . ' (' . $natureNumber . ') - ' . ($planetData['traits'] ?? ''), [
        //             'setX' => 35,
        //             'pinkDot' => true
        //         ]);
        //     }
        // }

        $this->loveQuote();
    }

    // Table of content 2
    function yourBehaviourPattern($HeadingCount)
    {
        $title = $this->tableOfContent(3)->title ??  'Your Behaviour Pattern';
        $this->BlankPageHere($title, $HeadingCount);

        $behaviorData = RelationshipBehaviorPattern::where('number', $this->kingNumber)->first();

        $headingText = $this->customerName;
        $headingText .= ' - Mulank ' . $this->kingNumber . ' (' . $behaviorData->planet . ') : "' . $behaviorData->title . '" ';

        $description = $behaviorData->core_energy;

        if (!empty($description)) {
            /// Page Content Here
            $this->Ln(4);
            $this->startSign("Your Core Energy");
            $this->Ln(2);
            $this->SetX(22);
            $this->SetFont($this->fontFamily, '', 13);
            $this->SetTextColor(60, 60, 60);
            $text = trim($description);
            $this->MultiCell(160, 6, $text, 0, 'J');
        }

        if (!empty($headingText)) {
            $this->Ln(3);
            $this->behaviorTitle($headingText);
            $this->Ln(5);
        }


        if (!empty($behaviorData->hidden_traits)) {
            $this->startSign("Hidden Behavioral Traits");
            $this->Ln(3);
            foreach ($behaviorData->hidden_traits as $traits) {
                $this->bulletPoint($traits);
            }
        }

        $this->loveQuote();
    }

    // Table of content 3
    function yourBirthDestinyReport($HeadingCount)
    {
        $destinyRelation = RelationshipBirthDestinyRelation::where([
            'king_number' => $this->kingNumber,
            'queen_number' => $this->queenNumber
        ])->first();

        $title = $this->tableOfContent(4)->title ??  'Your Birth & Destiny Relation';
        $this->BlankPageHere($title, $HeadingCount);

        $this->Ln(2);
        $this->SetX(23);
        $this->SetFont($this->fontFamily, '', 13);
        $this->SetTextColor(60, 60, 60);

        $text = trim($destinyRelation->description ?? '');
        if (!empty($text)) {
            $this->MultiCell(160, 6, $text, 0, 'J');
            $this->Ln(5);
        }

        foreach ($destinyRelation->content ?? [] as $key => $content) {
            $this->Ln(3);
            $this->startSign($content['title']);
            foreach ($content['points'] as $index => $points) {
                if ($index == 0) {
                    $this->Ln(3);
                }
                $this->bulletPoint($points);
            }
            $this->Ln(5);
        }

        $this->loveQuote();
    }

    // Table of content 4
    function yourMarriageStatus($HeadingCount)
    {
        $title = $this->tableOfContent(5)->title ??  'Your Marriage Status For Next 7 Years';
        $this->BlankPageHere($title, $HeadingCount);

        for ($A = 0; $A <= 6; $A++) {
            $this->Ln(4);
            $dob = $this->relationshipInfo->dob;
            $dobArr = explode('-', $dob);
            $year = date('Y', strtotime('+' . $A . 'years'));
            $personalYear = getPersonalYear($dobArr[2], $dobArr[1], $year);

            $nextyears = RelationshipMarriagePatternNextSevenYear::where('personal_year', $personalYear)->first();
            $headingText = $year . ' - ' . $nextyears->title;
            $this->startSign($headingText);

            foreach ($nextyears->content as $point) {
                $this->Ln(3);
                $this->bulletPoint($point);
            }
        }

        $this->loveQuote();
    }

    function bestDateMarriage($HeadingCount)
    {
        $title = $this->tableOfContent(6)->title ??  'Best Date of Marriage';
        $this->BlankPageHere($title, $HeadingCount);
        $queryData =  $this->luckyUnluckyModel();
        $bestDateData = RelationshipBestMarriageDateContent::all();

        foreach ($bestDateData as $key => $listdata) {

            if ($key == 0) {
                $this->Ln(3);
            }
            $this->startSign($listdata['title']);

            $this->Ln(2);
            $this->SetX(23);
            $this->SetFont($this->fontFamily, '', 13);
            $this->SetTextColor(60, 60, 60);

            $text = trim($listdata['content']['description'] ?? '');
            if (!empty($text)) {
                $this->MultiCell(160, 5, $text, 0, 'J');
            }

            foreach (($listdata['content']['points'] ?? []) as $index => $points) {
                if ($index === 0) {
                    $this->Ln(3);
                }

                // $unlucky_number = !empty($queryData->unlucky_number) ? str_replace(",", ", ", $queryData->unlucky_number) : implode(", ", $this->defaultUnlucky);

                if (!empty($queryData->unlucky_number)) {
                    $unluckyNumbers = explode(',', $queryData->unlucky_number);
                    sort($unluckyNumbers, SORT_NUMERIC);

                    $unlucky_number = implode(', ', $unluckyNumbers);
                } else {
                    $unluckyNumbers = $this->defaultUnlucky;
                    sort($unluckyNumbers, SORT_NUMERIC);

                    $unlucky_number = implode(', ', $unluckyNumbers);
                }

                // $lucky_number = str_replace(",", ", ", $queryData->balancer);
                if (!empty($queryData->balancer)) {
                    $luckyNumbers = explode(',', $queryData->balancer);
                    sort($luckyNumbers, SORT_NUMERIC);

                    $lucky_number = implode(', ', $luckyNumbers);
                } else {
                    $luckyNumbers = $this->defaultUnlucky;
                    sort($luckyNumbers, SORT_NUMERIC);

                    $lucky_number = implode(', ', $luckyNumbers);
                }




                $pointText = $points;
                $pointText = str_replace("[king_number]", $this->kingNumber, $pointText);
                $pointText = str_replace("[lucky_number]", $lucky_number, $pointText);
                $pointText = str_replace("[unlucky_number]", $unlucky_number, $pointText);

                $this->bulletPoint($pointText);
            }

            $this->SetX(23);
            $text = trim($listdata['content']['description_2'] ?? '');
            if (!empty($text)) {
                $this->Ln(2);
                $this->SetX(23);
                $this->MultiCell(160, 5, $text, 0, 'J');
                $this->Ln(3);
            }


            $note = trim($listdata['content']['note'] ?? '');
            if (!empty($note)) {
                $this->Ln(3);
                $this->SetX(23);
                $note = str_replace("[unlucky_number]", $unlucky_number, $note);
                $this->SetFont($this->fontFamily, 'B', 11);
                $rgb = array_map('trim', explode(',', $this->textColor['RGB']));
                $this->SetTextColor((int) $rgb[0], (int) $rgb[1], (int) $rgb[2],);
                $this->MultiCell(160, 5, 'NOTE: ' . $note, 0, 'J');
            }

            $this->Ln(5);
        }

        $weddingDate = [];
        if (!empty($queryData->balancer)) {
            $weddingDate = array_merge($weddingDate, explode(",", $queryData->balancer));
        }
        // if (!empty($queryData->neutral_number)) {
        //     $weddingDate = array_merge($weddingDate, explode(",", $queryData->neutral_number));
        // }

        // $this->favourBox("Best wedding-date totals : " . implode(", ", $weddingDate));

        $this->loveQuote();
    }


    function manageRelationship($HeadingCount)
    {
        $title = $this->tableOfContent(7)->title ??  'How You Manage Your Relationship';
        $this->BlankPageHere($title, $HeadingCount);
        $queryData =  $this->luckyUnluckyModel();
        $listData = HowManageYourRelationship::where([
            'king_number' => $this->kingNumber,
            'queen_number' => $this->queenNumber
        ])->first();

        $this->Ln(2);
        $this->SetX(23);
        $this->SetFont($this->fontFamily, '', 13);
        $this->SetTextColor(60, 60, 60);

        $text = trim($listData->description ?? '');
        if (!empty($text)) {
            $this->MultiCell(160, 6, $text, 0, 'J');
            $this->Ln(5);
        }

        foreach ($listData->content ?? [] as $key => $content) {

            $this->Ln(3);
            $this->startSign($content['title']);
            foreach ($content['points'] as $index => $points) {
                if ($index == 0) {
                    $this->Ln(3);
                }
                $this->bulletPoint($points);
            }
        }

        $this->loveQuote();
    }

    function magicalGuide($HeadingCount)
    {
        $queryData =  $this->luckyUnluckyModel();
        $lists = RelationshipSelectYourPartner::all();

        $title = $this->tableOfContent(8)->title ??  'Magical Guide to Select Your Partner';
        $this->BlankPageHere($title, $HeadingCount);

        foreach ($lists as $listData) {

            if (!empty($listData->title)) {
                $this->startSign($listData->title);
            }

            $text = $listData->description ?? '';
            $paragraphs = preg_split('/\n\s*\n/', $text);
            foreach ($paragraphs as $ky => $paragraph) {
                if ($ky == 0) {
                    $this->Ln(2);
                }

                $this->SetX(23);
                $this->SetFont($this->fontFamily, '', 13);
                $this->SetTextColor(60, 60, 60);

                $dob = $this->relationshipInfo->dob;
                $dobArr = explode("-", $dob);
                $calculateLoshuGrid = calculateLoshuGrid($dobArr[2], $dobArr[1], $dobArr[0], $this->kingNumber, $this->queenNumber);
                $calculateLoshuGrid = $calculateLoshuGrid['loshuGrid'];
                $missingNumbers = $this->missingNumber();

                $unlucky_number = !empty($queryData->unlucky_number) ? explode(",", $queryData->unlucky_number) : $this->defaultUnlucky;
                sort($unlucky_number);
                $unlucky_number = implode(", ", $unlucky_number);

                $lucky_number = explode(",", $queryData->balancer ?? []);
                sort($lucky_number);
                $lucky_number = implode(", ", $lucky_number);

                $neutral_number = explode(",", $queryData->neutral_number ?? []);
                sort($neutral_number);
                $neutral_number = implode(", ", $neutral_number);

                $lucky_neutral_number = explode(",", $queryData->balancer . ',' . $queryData->neutral_number ?? []);
                sort($lucky_neutral_number);
                $lucky_neutral_number = implode(", ", $lucky_neutral_number);


                sort($missingNumbers);

                $placeholders = [
                    '[customer_name]'  => $this->customerName,
                    '[king_number]'    => $this->kingNumber,
                    '[queen_number]'   => $this->queenNumber,
                    '[missing_numbers]' => implode(", ", $missingNumbers),
                    '[lucky]'          => $lucky_number,
                    '[unlucky]'        => $unlucky_number,
                ];

                $text = str_replace(
                    array_keys($placeholders),
                    array_values($placeholders),
                    $paragraph ?? ''
                );

                $text = trim($text);
                if (!empty($text)) {
                    $this->MultiCell(160, 6, $text, 0, 'J');
                    $this->Ln(5);
                }
            }

            foreach ($listData->content ?? [] as $key => $content) {
                if (!empty($content['title'])) {
                    $this->SetX(25);
                    $this->SetFont($this->fontFamily, 'B', 12);
                    $this->SetTextColor(191, 49, 101);
                    $this->Cell(0, 6, $content['title'] . ' : ', 0, 1);
                }

                foreach ($content['points'] as $index => $points) {
                    if ($index == 0) {
                        $this->Ln(1);
                    }
                    $placeholders = [
                        '[lucky_neutral]' => $lucky_neutral_number,
                        '[lucky]'          => $lucky_number,
                        '[neutral]'          => $neutral_number,
                        '[unlucky]'        => $unlucky_number,
                    ];

                    $points = str_replace(
                        array_keys($placeholders),
                        array_values($placeholders),
                        $points ?? ''
                    );


                    $this->bulletPoint($points);
                }
                $this->Ln(5);
            }
        }

        $this->loveQuote();
    }

    function remediesLoveLife($HeadingCount)
    {
        $title = $this->tableOfContent(9)->title ??  'Remedies for Your Love Life';
        $this->BlankPageHere($title, $HeadingCount);

        $missingNumbers = $this->missingNumber();

        $lists = RelationshipRemedy::whereIn('king_number', $missingNumbers)->get();

        foreach ($lists as $listData) {
            if (!empty($listData->title)) {
                $this->Ln(3);
                $this->startSign($listData->title);

                $text = trim($listData->description);
                if (!empty($text)) {
                    $this->Ln(3);
                    $this->SetX(23);
                    $this->SetFont($this->fontFamily, '', 13);
                    $this->SetTextColor(60, 60, 60);
                    $this->MultiCell(160, 6, $text, 0, 'L');
                    $this->Ln(2);
                }

                foreach ($listData->content ?? [] as $key => $content) {
                    if (!empty($content['title'])) {
                        $this->SetX(25);
                        $this->SetFont($this->fontFamily, 'BU', 12);
                        $rgb = array_map('trim', explode(',', $this->textColor['RGB']));
                        $this->SetTextColor((int) $rgb[0], (int) $rgb[1], (int) $rgb[2],);
                        $this->Cell(0, 6, $content['title'] . ' : ', 0, 1);
                    }

                    foreach ($content['points'] ?? [] as $index => $points) {
                        if ($index == 0) {
                            $this->Ln(1);
                        }
                        $this->bulletPoint($points);
                    }

                    foreach ($content['list_points'] ?? [] as $index => $points) {
                        if ($index == 0) {
                            $this->Ln(3);
                        }
                        if (!empty($content['title'])) {
                            $this->SetX(28);
                            $this->SetFont($this->fontFamily, 'B', 12);
                            $this->SetTextColor(191, 49, 101);
                            $this->Cell(0, 6, $points['title'] . ' : ', 0, 1);
                        }
                        foreach ($points['points'] ?? [] as $index => $points) {
                            if ($index == 0) {
                                $this->Ln(2);
                            }
                            $this->bulletPoint($points, [
                                'setX' => 35,
                                'pinkDot' => true
                            ]);
                        }
                        $this->Ln(4);
                    }

                    $this->Ln(5);
                }
            }
        }

        $this->loveQuote();
    }

    function finalConclusion($HeadingCount)
    {
        $finalData = RelationshipFinalConclusion::all();
        $title = $this->tableOfContent(10)->title ??  'Final Conclusion';
        $this->BlankPageHere($title, $HeadingCount);

        foreach ($finalData as $finallist) {

            $this->Ln(3);
            $this->startSign($finallist->title);

            $this->Ln(2);
            $this->SetX(23);
            $this->SetFont($this->fontFamily, '', 13);
            $this->SetTextColor(60, 60, 60);

            $text = trim($finallist->description ?? '');
            if (!empty($text)) {
                $this->MultiCell(160, 6, $text, 0, 'J');
                $this->Ln(3);
            }


            if (isset($finallist->content['points'])) {
                $this->Ln(3);
                foreach ($finallist->content['points'] as $point) {
                    $this->bulletPoint($point);
                }
                $this->Ln(5);
            }

            if (isset($finallist->content['list_points'])) {
                foreach ($finallist->content['list_points'] as $section) {

                    // optional title
                    if (!empty($section['title'])) {
                        $this->Ln(3);
                        $this->SetX(25);
                        $this->SetFont($this->fontFamily, 'B', 12);
                        $this->SetTextColor(191, 49, 101);
                        $this->Cell(0, 6, $section['title'] . ' : ', 0, 1);
                        $this->Ln(3);
                    }

                    foreach ($section['points'] as $point) {
                        $this->bulletPoint($point);
                    }

                    $this->Ln(4);
                }
                $this->Ln(5);
            }
        }

        $this->loveQuote();
    }
}
