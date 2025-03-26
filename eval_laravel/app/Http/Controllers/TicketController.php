<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class TicketController extends Controller
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
            ])->get("{$this->crmApiUrl}/api/tickets");

            $tickets = $response->successful() ? $response->json() : [];
            return view('tickets.index', compact('tickets'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erreur de chargement des tickets: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . Session::get('token'),
                'Accept' => 'application/json'
            ])->delete("{$this->crmApiUrl}/api/tickets/{$id}");

            if (!$response->successful()) {
                throw new \Exception($response->body() ?? 'Échec de la suppression');
            }

            return redirect()->route('tickets.index')->with('success', 'Ticket supprimé avec succès.');
        } catch (\Exception $e) {
            return redirect()->route('tickets.index')->with('error', $e->getMessage());
        }
    }

    public function edit($id)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . Session::get('token'),
                'Accept' => 'application/json'
            ])->get("{$this->crmApiUrl}/api/tickets/{$id}/montant");

            if (!$response->successful()) {
                throw new \Exception($response->body() ?? 'Montant non trouvé');
            }

            $montant = $response->json();
            return view('tickets.edit', compact('montant'));
        } catch (\Exception $e) {
            return redirect()->route('tickets.index')->with('error', $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . Session::get('token'),
                'Accept' => 'application/json',
                'Content-Type' => 'application/json'
            ])->put("{$this->crmApiUrl}/api/tickets/{$id}/montant", [
                'montant' => $request->input('montant')
            ]);

            if (!$response->successful()) {
                throw new \Exception($response->body() ?? 'Échec de la mise à jour');
            }

            return redirect()->route('tickets.index')->with('success', 'Montant mis à jour avec succès.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }
}