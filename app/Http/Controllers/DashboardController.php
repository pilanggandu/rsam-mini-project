<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\DoctorDashboardService;
use App\Services\PharmacistDashboardService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct(
        protected DoctorDashboardService $doctorDashboard,
        protected PharmacistDashboardService $pharmacistDashboard,
    ) {
    }

    public function __invoke(Request $request): View
    {
        /** @var User $user */
        $user = $request->user();

        return match ($user->role) {
            'doctor', 'dokter'        => $this->dokterDashboard($user),
            'pharmacist', 'apoteker'  => $this->apotekerDashboard($user),
            default                   => $this->defaultDashboard($user),
        };
    }

    /**
     * Khusus dashboard dokter.
     */
    protected function dokterDashboard(User $user): View
    {
        $data = $this->doctorDashboard->getSummary($user);

        return view('dashboard', array_merge($data, [
            'user'      => $user,
            'role'      => 'doctor',
            'roleLabel' => 'Dokter',
        ]));
    }

    /**
     * Khusus dashboard apoteker.
     */
    protected function apotekerDashboard(User $user): View
    {
        $data = $this->pharmacistDashboard->getSummary($user);

        return view('dashboard', array_merge($data, [
            'user'      => $user,
            'role'      => 'pharmacist',
            'roleLabel' => 'Apoteker',
        ]));
    }

    /**
     * Fallback untuk role lain.
     */
    protected function defaultDashboard(User $user): View
    {
        return view('dashboard', [
            'user'      => $user,
            'role'      => $user->role,
            'roleLabel' => 'User',
        ]);
    }
}
