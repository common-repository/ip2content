<?php

declare(strict_types=1);

namespace WMIP2C\Http\Services\Statistics;

use WMIP2C\Database\Models\DetectedCompany;
use WMIP2C\Database\Repositories\DetectedCompanyRepository;

final class DetectedCompaniesStatisticsManager
{
    private const KEEP_COMPANIES = 10;

    private DetectedCompanyRepository $detectedCompaniesRepository;

    public function __construct()
    {
        $this->detectedCompaniesRepository = new DetectedCompanyRepository();
    }

    /**
     * @return DetectedCompany[]
     */
    public function getLatestDetectedCompanies(): array
    {
        return $this->detectedCompaniesRepository->getFirstTenOrderedByCreatedAtDesc();
    }

    public function updateCompanyViewsByNameAndIndustry(string $companyName, string $industry): bool
    {
        $branchCode = $this->normalizeBranchCode($industry);
        $detectedCompany = $this->detectedCompaniesRepository->getFirstByNameAndIndustry($companyName, $branchCode);

        if ($detectedCompany !== null) {
            $this->detectedCompaniesRepository->updateById(
                $detectedCompany->getId(),
                ['views' => $detectedCompany->getViews() + 1]
            );

            return false;
        }

        $this->detectedCompaniesRepository->store([
            'views' => 1,
            'branch' => $branchCode,
            'name' => $companyName
        ]);

        $this->deleteExtraDetectedCompanies();

        return true;
    }

    public function deleteExtraDetectedCompanies(): void
    {
        $extraCompaniesIds = [];
        $extraCompanies = $this->detectedCompaniesRepository->getOrderedByIdWithOffset(self::KEEP_COMPANIES);

        foreach ($extraCompanies as $extraCompany) {
            $extraCompaniesIds[] = $extraCompany->getId();
        }

        $this->detectedCompaniesRepository->deleteWhereIdIn($extraCompaniesIds);
    }

    private function normalizeBranchCode(string $industry): string
    {
        if (strpos($industry, '.') !== false) {
            [$parentCode] = explode('.', $industry);

            return $parentCode;
        }

        return substr($industry, 0, 2);
    }
}
