<?php

namespace App\Http\Controllers;

use App\Services\SeedingService;
use Illuminate\Http\Response;

class MaskingController extends Controller
{
    public SeedingService $seedingService;

    public function __construct()
    {
        $this->seedingService = new SeedingService();
    }

    /**
     * @return Response
     */
    public function maskData(): Response
    {
        // Seed Fake DB Data
        $dbData = $this->seedingService->seedDBData();
        [$validCustomers, $invalidLines, $invalidCustomers] = $this->seedingService->seedFileData();
        $validData = $dbData->merge($validCustomers);
        return response()->view('results', ['validData' => $validData, 'invalidLines' => $invalidLines, 'invalidData' => $invalidCustomers]);
    }
}
