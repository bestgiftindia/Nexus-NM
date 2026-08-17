<?php

namespace App\Services\Child;

use App\Repositories\Child\ChildRepo;
use Illuminate\Support\Facades\DB;
use App\Helpers\LoshuHelper;

class ChildService
{
    protected ChildRepo $childRepo;

    function __construct(ChildRepo $Child)
    {
        $this->childRepo = $Child;
    }

    function getSuggestedData($data)
    {
        $kingNumber = LoshuHelper::getKingNumber($data['dob']);
        $queenNumber = LoshuHelper::getQueenNumber($data['dob']);

        $queryData =  LoshuHelper::getLuckNumberData($data['dob']);


        $luckyNumbers = array_map('intval', $queryData->lucky_numbers);
        $neutralNumbers = array_map('intval', $queryData->neutral_number);
        $unluckyNumbers = array_map('intval', $queryData->unlucky_numbers);

        $suggestedNames = processNameSuggestions(
            $data['first_name'] ?? '',
            $data['middle_name'] ?? '',
            $data['last_name'] ?? '',
            $data['gender'] ?? '',
            $luckyNumbers ?? [],
            $neutralNumbers ?? [],
            $unluckyNumbers ?? [],
            '',
            '',
            ''
        );

        

        foreach ($suggestedNames as $case => &$caseData) {
            $uniqueEntries = [];
            $seenNames = [];

            // Process each name entry
            foreach ($caseData['name'] as $index => $originalName) {
                // Convert to lowercase for comparison, but we'll keep original for display
                $lowerName = strtolower($originalName);

                // If we haven't seen this name before
                if (!in_array($lowerName, $seenNames)) {
                    $seenNames[] = $lowerName;

                    // Properly capitalize the name (first letter of each word)
                    $properName = ucwords(strtolower($originalName));

                    // Store all data for this unique entry
                    $uniqueEntries[] = [
                        'firstName' => $caseData['firstName'][$index],
                        'firstNameNumerologySum' => $caseData['firstNameNumerologySum'][$index],
                        'name' => $properName,
                        'numerologySum' => $caseData['numerologySum'][$index],

                    ];
                }
            }

            // Rebuild the case data with unique entries
            $caseData = [
                'firstName' => array_column($uniqueEntries, 'firstName'),
                'firstNameNumerologySum' => array_column($uniqueEntries, 'firstNameNumerologySum'),
                'name' => array_column($uniqueEntries, 'name'),
                'numerologySum' => array_column($uniqueEntries, 'numerologySum'),
                'message' => $caseData['message'] ?? null,
                'shortLastNameMessage' => $caseData['shortLastNameMessage'] ?? null,
                'recommendationMessage' => $caseData['recommendationMessage'] ?? null,
            ];
        }

        return $suggestedNames;
    }

    function createService(array $data)
    {
        $data['suggested_names'] = $this->getSuggestedData($data);
        return $this->childRepo->create($data);
    }

    function updateService(array $data, int $id)
    {
        $data['suggested_names'] = $this->getSuggestedData($data);
        return $this->childRepo->update($data, $id);
    }

    function deleteService(int $id)
    {
        return $this->childRepo->delete($id);
    }

    function findService(int $id)
    {
        return $this->childRepo->find($id);
    }

    function allService(array $options)
    {
        return $this->childRepo->all($options);
    }
}
