@php
    use App\Helpers\LoshuHelper;
    $meta = [
        'title' => 'View Loshu Grid Report',
        'description' => 'View Loshu Grid report details in the Numerology software dashboard.',
        'keywords' => 'loshu grid, numerology, view report, dashboard',
    ];

    $kingNumber = App\Helpers\LoshuHelper::getKingNumber($loshugrid->date_of_birth);
@endphp

@extends('layouts.account')

@section('content')
    <x-account.breadcrumb pageTitle="Manage Loshu Grid Mastery" :lists="[
        route('account.loshugrid.lists') => 'Loshu Grid Mastery',
        '' => 'View Report',
    ]" />

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header border-light d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="card-title mb-1">Report Detail</h2>
                        <p class="text-muted mb-0">
                            View the complete details of the selected Loshu Grid report.
                        </p>
                    </div>

                    <div>
                        <a href="{{ route('account.loshugrid.lists') }}" class="btn btn-primary">
                            <i data-lucide="table" class="me-1" style="width:16px;height:16px;"></i>
                            Report Lists
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row row-gap-4">
                        <div class="col-md-4">
                            <table class="table mb-0 border">
                                <thead>
                                    <tr>
                                        <th>Date of Birth:</th>
                                        <td>{{ dateFormat($loshugrid->date_of_birth) }}</td>
                                    </tr>
                                    <tr>
                                        <th>Full Name:</th>
                                        <td>{{ generateFullName($loshugrid) }}</td>
                                    </tr>
                                    <tr>
                                        <th>Created Date:</th>
                                        <td>{{ dateFormat($loshugrid->created_at) }}</td>
                                    </tr>
                                </thead>
                            </table>

                            <div class="mt-3 d-flex justify-content-center gap-2">
                                <a href="{{ route('account.loshugrid.lists') }}" class="btn btn-info">
                                    <i data-lucide="download" class="me-1" style="width:16px;height:16px;"></i>
                                    English Report
                                </a>
                                <a href="{{ route('account.loshugrid.lists') }}" class="btn btn-success">
                                    <i data-lucide="download" class="me-1" style="width:16px;height:16px;"></i>
                                    Hindi Report
                                </a>
                            </div>

                        </div>
                        <div class="col-md-4">
                            <div class="grid-title">
                                <code>LoShu Grid</code>
                            </div>

                            <div class="numerology-grid">
                                @foreach (getLoShuGrid($loshugrid->date_of_birth) as $losgrid)
                                    <div class="grid-box {{ !$losgrid ?'bg-danger-subtle':'bg-success-subtle' }}">{{ $losgrid }}</div>
                                @endforeach


                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="grid-title">
                                <code>Vedic Grid</code>
                            </div>

                            <div class="numerology-grid">
                                @foreach (getVedicGrid($loshugrid->date_of_birth) as $vedicGrid)
                                    <div class="grid-box {{ !$vedicGrid ?'bg-danger-subtle':'bg-success-subtle' }}">
                                        {{ $vedicGrid }}
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <div class="flex-grow-1">
                        <h4 class="card-title">Dasha Chart</h4>
                    </div>
                    <div class="card-action">
                        <a href="#!" class="card-action-item" data-action="card-toggle">
                            <i data-lucide="chevron-up" class="align-middle"></i>
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <p>
                        Ruling Planet Is: {{ $kingNumber->name ?? '' }}
                    </p>
                    {!! LoshuHelper::calculateDasha($loshugrid->date_of_birth) !!}
                </div>

            </div>

            <div class="card">
                <div class="card-header">
                    <div class="flex-grow-1">
                        <h4 class="card-title">Name Analysis</h4>
                    </div>
                    <div class="card-action">
                        <a href="#!" class="card-action-item" data-action="card-toggle">
                            <i data-lucide="chevron-up" class="align-middle"></i>
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    @php
                        $nameAnalysis = LoshuHelper::nameAnalysis(
                            $loshugrid->first_name,
                            generateFullName($loshugrid),
                            $loshugrid->date_of_birth,
                        );
                    @endphp
                    Your Name is not an accident. Your Name number must be aligned with your Birthday number (Moolank) as
                    well as Life Path Number (Bhagyank). If your life path number says the purpose of birth of your soul
                    then name number says how you are going to achieve that purpose.<br /><br />
                    Likewise, your Birthday number is your special talent you got from got and Name number provide strength
                    to that. If Name number is aligned with your Birthday number and Life path number then your life journey
                    will be smooth and you will be able to achieve your maximum potential. There are two important popular
                    method in Numerology to decide Name number, one is Pythagorean and another one is Chaldean.</br><br />
                    Steps to find your Name number as per chaldean Numerology :
                    <ul>
                        <li> Write out your full name, and place the appropriate numerical value against each letter.</li>
                        <li> Add the number of your first name, and then reduce it to a single digit.</li>
                        <li> Do the same for your middle and last names.</li>
                        <li> Add the three single-digit numbers from above steps, and reduce them to another single-digit
                            number to find your Name Number.</li>
                    </ul>
                    Here is the example of How to calculate Name numbers:<br /><br />
                    For example of Name <strong>VINAY DORIYA</strong><br /><br />

                    <strong>VINAY:</strong> 6+1+5+1+1 = 14 = 1+4 = 5<br /><br />

                    <strong>DORIYA:</strong> 4+7+2+1+1+1 = 16 = 1+6 = 7<br /><br />

                    <strong>Total:</strong> 5+7 = 12 = 1+2 = 3<br /><br />

                    <p>Name Number is <strong>3</strong>.<br /><br />
                        In the below section, Your Name has been analyzed as per CHALDEAN NUMEROLOGY method to confirm
                        whether
                        your name is compatible with your Birthday Number(Moolank)as well as Life Path Number(Bhagyank) or
                        not.</p>

                    <div class="card border border-dashed border-light card-body text-bg-light p-2 mb-2">
                        <p><strong>Your Name Number</strong></p>
                        {!! $nameAnalysis['fullNameAnalysis'] ?? '' !!}
                    </div>

                    <div class="card border border-dashed border-light card-body text-bg-light mb-2 p-2">
                        <p><strong>CHALDEAN NAME ANALYSIS:</strong></p>
                        <p>Name Compatibility as per Bhagyank</p>
                        {!! $nameAnalysis['result'] ?? '' !!}
                    </div>

                    <div class="card border border-dashed border-light card-body text-bg-light mb-2 p-2">
                        <p><strong>CHALDEAN NAME ANALYSIS: </strong></p>
                        <p>Overall Name Compatibility as per Moolank & Bhagyank</p>
                        {!! $nameAnalysis['fullNameResult'] ?? '' !!}
                    </div>

                    <div class="card border border-dashed border-light card-body text-bg-light mb-2 p-2">
                        <p><strong>Your Name Number</strong></p>
                        {!! $nameAnalysis['firstNameAnalysis'] ?? '' !!}
                    </div>

                    <div class="card border border-dashed border-light card-body text-bg-light mb-0 p-2">
                        <p><strong>Suggested NAME NUMBER (FIRST & FULL NAME) as per your Birthday (Moolank) & Life Path
                                Number (Bhagyank) :</strong></p>
                        {!! $nameAnalysis['favourableNumber'] ?? '' !!}
                        {!! $nameAnalysis['avoidableNumber'] ?? '' !!}
                    </div>
                </div>

            </div>

            <div class="card">
                <div class="card-header">
                    <div class="flex-grow-1">
                        <h4 class="card-title">Chart</h4>
                    </div>
                    <div class="card-action">
                        <a href="#!" class="card-action-item" data-action="card-toggle">
                            <i data-lucide="chevron-up" class="align-middle"></i>
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <table class="table table-bordered">
                        <tr>
                            <th>King Number (Moolank):</th>
                            <td>
                               
                                {{ $kingNumber->king_no }} ({{ $kingNumber->name }})
                            </td>

                            <th>Queen Number (Bhagyank):</th>
                            <td>
                                @php
                                    $queenNumber = App\Helpers\LoshuHelper::getQueenNumber($loshugrid->date_of_birth);
                                @endphp
                                {{ $queenNumber->king_no }} ({{ $queenNumber->name }})
                            </td>
                        </tr>

                        <tr>
                            <th>Kua Number:</th>
                            <td>{{ \App\Helpers\LoshuHelper::calculateKuaNumber($loshugrid->date_of_birth, $loshugrid->gender) }}
                            </td>

                            <th>Personal Year:</th>
                            <td>{{ \App\Helpers\LoshuHelper::calculatePersonalYear($loshugrid->date_of_birth) }}</td>
                        </tr>

                        <tr>
                            <th>Completed Age:</th>
                            <td>
                                @php
                                    $completedAge = App\Helpers\LoshuHelper::calculateAge($loshugrid->date_of_birth);
                                @endphp
                                {{ $completedAge['years'] }} Years {{ $completedAge['months'] }} Months
                                {{ $completedAge['days'] }} Days
                            </td>

                            <th>Running Age:</th>
                            <td>{{ App\Helpers\LoshuHelper::calculateCurrentAge($loshugrid->date_of_birth) }} Years</td>
                        </tr>

                        <tr>
                            <th>Elements:</th>
                            <td>
                                @php
                                    $missingData = App\Helpers\LoshuHelper::missingData($loshugrid->date_of_birth);
                                @endphp
                                {{ $missingData['elements'] }}
                            </td>

                            <th>Mobile Number:</th>
                            <td>
                                @if ($loshugrid->phone)
                                    @php
                                        $mobile = $loshugrid->phone;
                                        $array = array_map('intval', str_split($mobile));
                                    @endphp
                                    <ul>
                                        <li>
                                            <strong>Compound Number:</strong> {{ array_sum($array) }}
                                        </li>
                                        <li>
                                            <strong>Mobile Number (Single):</strong> {{ digSum1($mobile) }}
                                        </li>
                                    </ul>
                                @endif
                            </td>
                        </tr>

                        <tr>
                            <th>Name Number:</th>
                            <td>
                                <ul>
                                    <li>
                                        <strong>Compound Number:</strong> {{ chaldean_sum(generateFullName($loshugrid)) }}
                                    </li>
                                    <li>
                                        <strong>Name Number (Single):</strong> {{ chaldean_sum($loshugrid->first_name) }}
                                    </li>
                                </ul>
                            </td>

                            <th>Missing Direction:</th>
                            <td>{{ $missingData['missingDirection'] }}</td>
                        </tr>

                        <tr>

                            <th>Missing Number:</th>
                            <td> {{ $missingData['missingNumbers'] }}</td>

                            <th>Maha Dasha:</th>
                            <td>{{ \App\Helpers\LoshuHelper::mahaDasha($loshugrid->date_of_birth) }}
                            </td>
                        </tr>

                        <tr>


                            <th>Antar Dasha:</th>
                            <td>{{ \App\Helpers\LoshuHelper::antarDasha($loshugrid->date_of_birth) }}</td>

                            <th>Zodiac Sign:</th>
                            <td>{{ \App\Helpers\LoshuHelper::ZodiacSign($loshugrid->date_of_birth) }}</td>
                        </tr>

                        <tr>


                            @php
                                $luckyData = \App\Helpers\LoshuHelper::getLuckNumberData($loshugrid->date_of_birth);
                            @endphp
                            <th>Lucky Number:</th>
                            <td>{{ implode(', ', $luckyData->lucky_numbers) }}</td>

                            <th>Unlucky Number:</th>
                            <td>{{ implode(', ', $luckyData->unlucky_numbers) }}</td>
                        </tr>

                        <tr>


                            <th>Neutral Number:</th>
                            <td>{{ implode(', ', $luckyData->neutral_number) }}</td>

                            <th>Lucky Colors:</th>
                            <td>{{ implode(', ', $luckyData->lucky_color) }}</td>
                        </tr>

                        <tr>

                            <th>Unlucky Colors:</th>
                            <td colspan="3">{{ implode(', ', $luckyData->unlucky_color) }}</td>
                        </tr>

                        <tr>
                            <th>Grid Numerology:</th>
                            <td colspan="3">
                                @php
                                    $outPut = calculateLoshuGrid($luckyData->date_of_birth);
                                    $presencePercentage = calculatePresencePercentage($outPut);
                                @endphp
                                <ul>
                                    <li><strong>Memory Plane is :</strong> {{ $presencePercentage['9-2-4'] ?? 0 }}%</li>
                                    <li><strong>Emotional Plane is :</strong> {{ $presencePercentage['3-5-7'] ?? 0 }}%</li>
                                    <li><strong>Practical Plane is :</strong> {{ $presencePercentage['8-1-6'] ?? 0 }}%</li>
                                    <li><strong>Thought Plane is :</strong> {{ $presencePercentage['4-3-8'] ?? 0 }}%</li>
                                    <li><strong>Will Plane is :</strong> {{ $presencePercentage['9-5-1'] ?? 0 }}%</li>
                                    <li><strong>Action Plane is :</strong> {{ $presencePercentage['2-7-6'] ?? 0 }}%</li>
                                    <li><strong>Success Plane 1 is :</strong> {{ $presencePercentage['4-5-6'] ?? 0 }}%</li>
                                    <li><strong>Success Plane 2 is :</strong> {{ $presencePercentage['2-5-8'] ?? 0 }}%</li>
                                </ul>
                            </td>
                        </tr>

                        <tr>
                            <th>Missing Number Symptoms:</th>
                            <td colspan="3">
                                {!! $missingData['missingNumberSymptoms'] ?? '' !!}
                            </td>
                        </tr>

                        <tr>
                            <th>Missing Number Remedies:</th>
                            <td colspan="3">
                                {!! $missingData['missingNumbersRemedies'] ?? '' !!}
                            </td>
                        </tr>

                    </table>
                </div>

            </div>
        </div>
    </div>
@endsection
