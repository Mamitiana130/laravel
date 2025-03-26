<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class DepenseTicketController extends Controller
{
    protected $crmApiUrl;

    public function __construct()
    {
        $this->crmApiUrl = env('CRM_API_URL', 'http://localhost:8080');
    }

    public function index()
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . Session::get('token'),
                'Accept' => 'application/json'
            ])->get("{$this->crmApiUrl}/api/depenses/tickets");

            $depenses = $response->successful() ? $response->json() : [];
            $depenses = $depenses ?? [];

            return view('depenses.indexTickets', compact('depenses'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erreur de connexion à l\'API: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . Session::get('token'),
                'Accept' => 'application/json'
            ])->get("{$this->crmApiUrl}/api/depenses/{$id}");

            if (!$response->successful()) {
                throw new \Exception($response->body() ?? 'Dépense non trouvée');
            }

            $depense = $response->json();
            return view('depenses.editTickets', compact('depense'));
        } catch (\Exception $e) {
            return redirect()->route('depensesTickets.index')->with('error', $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . Session::get('token'),
                'Accept' => 'application/json',
                'Content-Type' => 'application/json'
            ])->put("{$this->crmApiUrl}/api/depenses/{$id}", [
                'montant' => $request->input('montant'),
                'description' => $request->input('description')
            ]);

            if (!$response->successful()) {
                throw new \Exception($response->body() ?? 'Échec de la mise à jour');
            }

            return redirect()->route('depensesTickets.index')->with('success', 'Dépense mise à jour avec succès');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . Session::get('token'),
                'Accept' => 'application/json'
            ])->delete("{$this->crmApiUrl}/api/depenses/{$id}");

            if (!$response->successful()) {
                throw new \Exception($response->body() ?? 'Échec de la suppression');
            }

            return redirect()->route('depensesTickets.index')->with('success', 'Dépense supprimée avec succès');
        } catch (\Exception $e) {
            return redirect()->route('depensesTickets.index')->with('error', $e->getMessage());
        }
    }
}