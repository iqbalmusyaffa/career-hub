<?php

namespace Tests\Feature;

use Tests\TestCase;

class SalaryBenchmarkApiTest extends TestCase
{
    /**
     * Test single salary benchmark endpoint with query parameters.
     */
    public function test_salary_benchmark_api_calculates_successfully()
    {
        $response = $this->getJson('/api/v1/salary-benchmark?position=Software Engineer&location=Jakarta&level=Mid&skills=Laravel,AWS');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'query',
                    'salary_range' => [
                        'currency',
                        'min_monthly',
                        'median_monthly',
                        'max_monthly',
                        'formatted',
                        'annual_estimate',
                    ],
                    'regional_benchmark',
                    'market_insights',
                    'take_home_pay_estimate',
                    'job_market_availability',
                ]
            ]);

        $data = $response->json('data');
        $this->assertGreaterThan(0, $data['salary_range']['min_monthly']);
        $this->assertGreaterThan($data['salary_range']['min_monthly'], $data['salary_range']['median_monthly']);
        $this->assertGreaterThan($data['salary_range']['median_monthly'], $data['salary_range']['max_monthly']);
    }

    /**
     * Test different positions produce different salaries.
     */
    public function test_different_roles_produce_distinct_salaries()
    {
        $resAi = $this->getJson('/api/v1/salary-benchmark?position=AI / Machine Learning Engineer&location=Jakarta&level=Mid');
        $resMarketing = $this->getJson('/api/v1/salary-benchmark?position=Marketing Specialist&location=Jakarta&level=Mid');
        $resAdmin = $this->getJson('/api/v1/salary-benchmark?position=Administrative Assistant&location=Jakarta&level=Mid');

        $salaryAi = $resAi->json('data.salary_range.median_monthly');
        $salaryMarketing = $resMarketing->json('data.salary_range.median_monthly');
        $salaryAdmin = $resAdmin->json('data.salary_range.median_monthly');

        $this->assertNotEquals($salaryAi, $salaryMarketing);
        $this->assertNotEquals($salaryMarketing, $salaryAdmin);
        $this->assertGreaterThan($salaryMarketing, $salaryAi);
        $this->assertGreaterThan($salaryAdmin, $salaryMarketing);
    }

    /**
     * Test different locations produce distinct salaries based on UMK.
     */
    public function test_different_locations_produce_distinct_salaries()
    {
        $resJakarta = $this->getJson('/api/v1/salary-benchmark?position=Software Engineer&location=Jakarta&level=Mid');
        $resYogyakarta = $this->getJson('/api/v1/salary-benchmark?position=Software Engineer&location=Kota Yogyakarta&level=Mid');

        $salaryJakarta = $resJakarta->json('data.salary_range.median_monthly');
        $salaryYogya = $resYogyakarta->json('data.salary_range.median_monthly');

        $this->assertNotEquals($salaryJakarta, $salaryYogya);
        $this->assertGreaterThan($salaryYogya, $salaryJakarta);
    }

    /**
     * Test roles catalog endpoint.
     */
    public function test_roles_catalog_endpoint()
    {
        $response = $this->getJson('/api/v1/salary-benchmark/roles');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'categories',
                    'seniority_levels',
                    'total_roles',
                ]
            ]);
    }

    /**
     * Test multi-city compare endpoint.
     */
    public function test_compare_locations_endpoint()
    {
        $response = $this->getJson('/api/v1/salary-benchmark/compare?position=Software Engineer&locations=Jakarta,Surabaya,Bandung,Yogyakarta');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'position',
                    'level',
                    'comparisons' => [
                        '*' => [
                            'location',
                            'province',
                            'umk_amount',
                            'salary_median',
                            'net_thp',
                        ]
                    ]
                ]
            ]);
    }

    /**
     * Test locations endpoint.
     */
    public function test_locations_endpoint()
    {
        $response = $this->getJson('/api/v1/salary-benchmark/locations');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    '*' => [
                        'city',
                        'province',
                        'umk_amount',
                        'formatted_umk',
                    ]
                ]
            ]);
    }
}
