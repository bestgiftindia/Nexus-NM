<?php

namespace App\Services\Loshugrid;

use FPDF;

class ReportService extends FPDF
{
    public $fontFamily = "Times";
    public $colors = [
        ['HEX' => '#b5843c', 'RGB' => '181, 132, 60'], // GOLDEN
        ['HEX' => '#e5bd60', 'RGB' => '229, 189, 96'], // Light GOLDER
        ['HEX' => '#153825', 'RGB' => '21, 56, 37'], // GREEN
        ['HEX' => '#3c3c3c', 'RGB' => '60,60,60'] // GRAY
    ];
    public function __construct()
    {
        parent::__construct();
    }

    function Header()
    {
        $bgimage = public_path('assets/img/report/destiny/3/innder.png');
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

    function Footer()
    {
        $fontFamily = "Times";
        $this->SetY($this->PageNo() == 1 ? 286 : 282);
        $this->SetFont($fontFamily, 'B', 9);


        $this->SetTextColor(60, 60, 60);
        $this->Cell(0, 10, 'Pages No' . $this->PageNo(), 0, 0, 'C');
        return false;
    }

    function generatePDF()
    {
        $this->AliasNbPages();
        $this->SetMargins(10, 10, 10);
        $this->firstPageHere();
        $this->tableOfContent();
        $this->lastPageHere();
        $this->Output('D', 'Destiny.pdf');
        exit;
    }

    function generatePage($options = [])
    {

        $bgimage = public_path('assets/img/report/destiny/3/' . ($options['baseImg'] ?? 'inner.png'));
        $this->AddPage('P');
        $this->resizeToFit($bgimage);

        $fontFamily = $this->fontFamily;

        $this->SetFont($fontFamily, 'B', 12);
        $this->SetXY(10, 20);

        $title = $options['pageTitle'] ?? '';
        $this->SetFont($fontFamily, 'B', 24);
        $textWidth = $this->GetStringWidth($title);
        $x = (210 - $textWidth) / 2;
        $this->SetXY($x, 20);
        $rgb = explode(",", $this->colors[2]['RGB'] ?? "60,60,60");
        $this->SetTextColor($rgb[0], $rgb[1], $rgb[2]);

        $this->Cell($textWidth, 10, $title, 0, 0, 'L');
    }

    function firstPageHere()
    {
        $this->AcceptPageBreak();
        $bgimage = public_path('assets/img/report/destiny/3/cover.png');
        $this->AddPage('P');
        $this->resizeToFit($bgimage);
    }


    function tableOfContent()
    {
        $this->generatePage([
            'pageTitle' => "TABLE OF CONTENT",
            "baseImg" => 'inner.png'
        ]);

        $contents = [
            'Default Lo-Shu Grid',
            'Lo-Shu Grid',
            'Thought Plane',
            'Success Plane',
            'Mental Plane',
            'Will Plane',
            'Emotional Plane',
            'Outlook Action Plane',
            'Will Power Success 2 Plane',
            'Practical Plane',
            'Lucky & Unlucky Number',
            'Lucky & Unlucky Color',
            'Maha Dasha',
            'Missing Number',
            'Missing Number Remedies',
            'All Available Numbers',
            'Repetitive Numbers',
            'Number Analysis',
            'Bhagyank Number',
            'Moolank Number',
            'Chaldean Name Number',
            'Name Analysis',
            'Month Number',
            'Birth Year Number',
            'Sun Sign Western',
            'Sun Sign Eastern',
            'Vastu Grid',
            'Vastu Dasha As Per Missing Number',
            'Marriage & Relationship Analysis',
            'Finance Analysis',
            'Health Analysis',
            'Personal Year Number',
            'All Remedies',
        ];

        foreach ($contents as $content) {

            $rgb = explode(",", $this->colors[0]['RGB'] ?? "60,60,60");

            $this->SetFillColor($rgb[0], $rgb[1], $rgb[2]);
            // $this->Cell(20, 10, '', 0, 0, 'L');
            $this->Cell(20, 10, $content, 1, 0, 'L');
        }
    }

    function lastPageHere()
    {
        $this->AcceptPageBreak();
        $bgimage = public_path('assets/img/report/destiny/3/last.png');
        $this->AddPage('P');
        $this->resizeToFit($bgimage);
    }
}
