<?php

declare(strict_types=1);

namespace WMIP2C\Http\Mapper;

use WMIP2C\Database\Models\DetectedCompany;

final class DetectedCompaniesCollectionMapper
{
    /**
     * @param DetectedCompany[] $detectedCompanies
     *
     * @return array
     */
    public function mapCollectionToArray(array $detectedCompanies): array
    {
        $mappedCompanies = [];

        foreach ($detectedCompanies as $detectedCompany) {
            $mappedCompanies[] = [
                'company' => $detectedCompany->getName(),
                'views' => $detectedCompany->getViews(),
                'branch_code' => $detectedCompany->getBranch(),
            ];
        }

        return $mappedCompanies;
    }
}
