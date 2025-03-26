<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class TauxController extends Controller
{
    protected $crmApiUrl;

    public function __construct()
    {
        $this->crmApiUrl = env('CRM_API_URL', 'http://localhost:8080');
    }

    public function create()
    {
        return view('taux.add');
    }

    public function store(Request $request)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . Session::get('token'),
                'Accept' => 'application/json',
                'Content-Type' => 'application/json'
            ])->post("{$this->crmApiUrl}/api/taux", [
                'valeur' => $request->input('valeur')
            ]);

            if (!$response->successful()) {
                throw new \Exception($response->body() ?? 'Échec de l\'ajout');
            }

            return redirect()->route('dashboard')->with('success', 'Taux ajouté avec succès.');
        } catch (\Exception $e) {
            return redirect()->route('taux.create')->withInput()->with('error', $e->getMessage());
        }
    }
}