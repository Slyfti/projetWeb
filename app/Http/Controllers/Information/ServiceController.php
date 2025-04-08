<?php

namespace App\Http\Controllers\Information;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Inertia\Inertia;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::with('categorie')
            ->get()
            ->groupBy(function ($service) {
                return $service->categorie->nom;
            });

        return Inertia::render('services/Services', [
            'services' => $services
        ]);
    }

    public function restauration()
    {
        return Inertia::render('services/Restauration');
    }

    public function vip()
    {
        return Inertia::render('services/VIP');
    }

    public function securite()
    {
        return Inertia::render('services/Securite');
    }

    public function medical()
    {
        return Inertia::render('services/Medical');
    }

    public function pmr()
    {
        return Inertia::render('services/PMR');
    }
} 