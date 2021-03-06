<?php

namespace App\Http\Controllers\Api;

use App\Hairdresser;
use App\Http\Controllers\Controller;
use App\Http\Requests\QueryRequest;
use App\Http\Responses\HairdresserRegistrationResponse;
use App\QueryAdapter;
use App\Services\HairdresserRegistrationService;

class HairdressersController extends Controller
{

    // public function index(Request $request)
    // {
    //     $hairdressers = Query::execute(Hairdresser::class, $request->all());
    //     return $hairdressers;
    // }

    public function register(QueryRequest $request)
    {
        $hairdresserRegistrationService = new HairdresserRegistrationService();
        $hairdresserRegistrationServiceOutput = $hairdresserRegistrationService->register(
            $request->identifier,
            $request->password,
            $request->name,
            $request->gender,
            $request->birthday
        );
        if ($request->hasQuery()) {
            $queryAdapter = new QueryAdapter();
            $hairdresserRegistrationServiceOutput->hairdresser = $queryAdapter->executeWithId(
                Hairdresser::class,
                $request->all(),
                $hairdresserRegistrationServiceOutput->hairdresser->id
            )[0];
        }
        return new HairdresserRegistrationResponse(
            $hairdresserRegistrationServiceOutput->hairdresser,
            $hairdresserRegistrationServiceOutput->accessToken,
            $hairdresserRegistrationServiceOutput->refreshToken
        );
    }
}
