<?php

namespace WMIP2C\Database\Seeders;

use WMIP2C\Common\Enums\ConditionFields;
use WMIP2C\Common\Enums\ConditionUtmFieldValues;
use WMIP2C\Database\Migrations\CreateFieldsTableMigration;
use WMIP2C\Database\Migrations\CreateFieldValuesTableMigration;
use WMIP2C\Database\Models\Field;
use WMIP2C\Database\Models\FieldValue;
use WMIP2C\Database\Seeders\Components\AbstractSeeder;

final class FieldsValuesSeeder extends AbstractSeeder
{
    /**
     * @var Field[]
     */
    protected array $fieldsByAlias;

    public function __construct()
    {
        global $wpdb;

        $fieldsTable = CreateFieldsTableMigration::getTableName();
        $rows = $wpdb->get_results("SELECT * FROM {$fieldsTable}");

        $this->fieldsByAlias = [];

        foreach ($rows as $row) {
            $this->fieldsByAlias[$row->alias] = new Field(
                (int) $row->id,
                $row->alias,
                $row->source,
                (int) $row->default_operator_id,
            );
        }
    }

    public function run(): void
    {
        global $wpdb;

        $valuesTable = CreateFieldValuesTableMigration::getTableName();
        $values = $this->getDump();

        foreach ($values as $value) {
            $wpdb->insert($valuesTable, $value);
        }
    }

    protected function getDump(): array
    {
        return array_merge(
            $this->getIndustryFieldValuesDump(),
            $this->getCompanySizeDump(),
            $this->getCountryDump(),
            $this->getRevenueClassDump(),
            $this->getUTMTagsDump(),
        );
    }

    public function getIndustryFieldValuesDump(): array
    {
        $industryFieldId = $this->fieldsByAlias[ConditionFields::INDUSTRY]->getId();
        $industries = json_decode(
            file_get_contents(__DIR__ . '/files/industry_values.json'),
            true,
            512,
            JSON_THROW_ON_ERROR
        );

        $dump = [];

        foreach ($industries as $industry) {
            $dump[] = [
                'name_en' => $industry['name_en'],
                'name_de' => $industry['name_de'],
                'value' => implode(FieldValue::MULTISELECT_SEPARATOR, $industry['values']),
                'field_id' => $industryFieldId,
            ];
        }

        return $dump;
    }

    public function getCompanySizeDump(): array
    {
        $industryFieldId = $this->fieldsByAlias[ConditionFields::COMPANY_SIZE]->getId();

        $values = [
            '01' => [
                'name_en' => '1 - 4 employees',
                'name_de' => '1 - 4 Mitarbeiter',
            ],
            '02' => [
                'name_en' => '5 - 9 employees',
                'name_de' => '5 - 9 Mitarbeiter',
            ],
            '03' => [
                'name_en' => '10 - 19 employees',
                'name_de' => '10 - 19 Mitarbeiter',
            ],
            '04' => [
                'name_en' => '20 - 49 employees',
                'name_de' => '20 - 49 Mitarbeiter',
            ],
            '05' => [
                'name_en' => '50 - 99 employees',
                'name_de' => '50 - 99 Mitarbeiter',
            ],
            '06' => [
                'name_en' => '100 - 199 employees',
                'name_de' => '100 - 199 Mitarbeiter',
            ],
            '07' => [
                'name_en' => '200 - 499 employees',
                'name_de' => '200 - 499 Mitarbeiter',
            ],
            '08' => [
                'name_en' => '500 - 999 employees',
                'name_de' => '500 - 999 Mitarbeiter',
            ],
            '09' => [
                'name_en' => '1.000 - 1.999 employees',
                'name_de' => '1.000 - 1.999 Mitarbeiter',
            ],
            '10' => [
                'name_en' => 'more than 2.000 employees',
                'name_de' => 'mehr als 2.000 Mitarbeiter',
            ],
        ];

        $dump = [];

        foreach ($values as $code => $attributes) {
            $dump[] = [
                'name_en' => $attributes['name_en'],
                'name_de' => $attributes['name_de'],
                'value' => $code,
                'field_id' => $industryFieldId,
            ];
        }

        return $dump;
    }

    public function getCountryDump(): array
    {
        $industryFieldId = $this->fieldsByAlias[ConditionFields::COUNTRY]->getId();
        $countries = json_decode(
            file_get_contents(__DIR__ . '/files/countries.json'),
            true,
            512,
            JSON_THROW_ON_ERROR
        );

        $mainCountries = [
            'DE',
            'US',
            'IT',
            'PL',
            'JP',
            'CH',
            'FR',
            'GB',
            'AT',
            'ES',
            'NL',
            'IN',
            'CZ',
            'KR',
            'SE',
            'BR',
            'TW',
            'CA',
            'TR',
            'HU',
            'MX',
            'RU',
            'SK',
            'PT',
            'RO',
            'BE',
            'AU',
            'BG',
            'CN',
            'SG',
            'DK',
            'IE',
            'FI',
            'MY',
            'ZA',
            'HR',
            'NO',
            'GR',
            'EG',
            'IL',
            'AR',
            'AE',
        ];

        $rawDump = [];

        foreach ($countries as $country) {
            $rawDump[$country['code']] = [
                'name_en' => $country['name_en'],
                'name_de' => $country['name_de'],
                'value' => $country['code'],
                'field_id' => $industryFieldId
            ];
        }

        $dump = [];

        foreach ($mainCountries as $countryCode) {
            $dump[] = $rawDump[$countryCode];
        }

        array_push($dump, ...array_values(array_diff_key($rawDump, array_flip($mainCountries))));

        return $dump;
    }

    public function getRevenueClassDump(): array
    {
        $industryFieldId = $this->fieldsByAlias[ConditionFields::REVENUE_CLASS]->getId();

        $values = [
            '01' => [
                'name_en' => 'up to 100.000 €',
                'name_de' => 'bis zu 100.000 €',
            ],
            '02' => [
                'name_en' => '100.001 bis 250.000 €',
                'name_de' => '100.001 bis 250.000 €',
            ],
            '03' => [
                'name_en' => '250.001 bis 500.000 €',
                'name_de' => '250.001 bis 500.000 €',
            ],
            '04' => [
                'name_en' => '500.001 bis 2.500.000 €',
                'name_de' => '500.001 bis 2.500.000 €',
            ],
            '05' => [
                'name_en' => '2.500.001 bis 5.000.000 €',
                'name_de' => '2.500.001 bis 5.000.000 €',
            ],
            '06' => [
                'name_en' => '5.000.001 bis 25.000.000 €',
                'name_de' => '5.000.001 bis 25.000.000 €',
            ],
            '07' => [
                'name_en' => '25.000.001 bis 50.000.000 €',
                'name_de' => '25.000.001 bis 50.000.000 €',
            ],
            '08' => [
                'name_en' => '50.000.001 bis 500.000.000 €',
                'name_de' => '50.000.001 bis 500.000.000 €',
            ],
            '09' => [
                'name_en' => 'more than 500.000.000 €',
                'name_de' => 'mehr als 500.000.000 €',
            ],
        ];

        $dump = [];

        foreach ($values as $code => $attributes) {
            $dump[] = [
                'name_en' => $attributes['name_en'],
                'name_de' => $attributes['name_de'],
                'value' => $code,
                'field_id' => $industryFieldId
            ];
        }

        return $dump;
    }

    private function getUTMTagsDump(): array
    {
        $utmFieldId = $this->fieldsByAlias[ConditionFields::UTM]->getId();

        $values = [
            'custom'                          => '',
            ConditionUtmFieldValues::ID       => 'utm_id',
            ConditionUtmFieldValues::SOURCE   => 'utm_source',
            ConditionUtmFieldValues::MEDIUM   => 'utm_medium',
            ConditionUtmFieldValues::CAMPAIGN => 'utm_campaign',
            ConditionUtmFieldValues::TERM     => 'utm_term',
            ConditionUtmFieldValues::CONTENT  => 'utm_content',
        ];

        $dump = [];

        foreach ($values as $alias => $value) {
            $dump[] = [
                'name_en' => $alias,
                'name_de' => $alias,
                'value' => $value,
                'field_id' => $utmFieldId
            ];
        }

        return $dump;
    }
}
