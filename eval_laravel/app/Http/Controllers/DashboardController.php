<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class DashboardController extends Controller
{
    protected $crmApiUrl;

    public function __construct()
    {
        $this->crmApiUrl = env('CRM_API_URL', 'http://localhost:8080');
    }

    public function index()
    {
        $chartData = [
            'labels' => ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'],
            'data' => [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
        ];

        return view('dashboard', compact('chartData'));
    }

    /**========================================================================
     * CLIENTS
     *=======================================================================*/
    public function getCustomersByYear(Request $request)
    {
        try {
            $year = $request->query('year');
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . Session::get('token'),
                'Accept' => 'application/json'
            ])->get("{$this->crmApiUrl}/api/customers/by-year", [
                'year' => $year
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return response()->json([
                    'labels' => array_keys($data),
                    'data' => array_values($data),
                ]);
            }

            return response()->json([
                'error' => 'Failed to fetch data',
                'details' => $response->body()
            ], $response->status());

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Exception occurred',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getTotalCustomers()
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . Session::get('token'),
                'Accept' => 'application/json'
            ])->get("{$this->crmApiUrl}/api/customers/total");

            if ($response->successful()) {
                return $response->json();
            }

            return response()->json([
                'error' => 'Failed to fetch total customers',
                'details' => $response->body()
            ], $response->status());

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Exception occurred',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**========================================================================
     * TICKETS
     *=======================================================================*/
    public function getTotalTickets()
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . Session::get('token'),
                'Accept' => 'application/json'
            ])->get("{$this->crmApiUrl}/api/tickets/total");

            if ($response->successful()) {
                return $response->json();
            }

            return response()->json([
                'error' => 'Failed to fetch total tickets',
                'details' => $response->body()
            ], $response->status());

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Exception occurred',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getTicketsByStatus()
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . Session::get('token'),
                'Accept' => 'application/json'
            ])->get("{$this->crmApiUrl}/api/tickets/by-status");

            if ($response->successful()) {
                $data = $response->json();
                return response()->json([
                    'labels' => array_keys($data),
                    'data' => array_values($data),
                ]);
            }

            return response()->json([
                'error' => 'Failed to fetch tickets by status',
                'details' => $response->body()
            ], $response->status());

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Exception occurred',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getTicketsByYear(Request $request)
    {
        try {
            $year = $request->query('year');
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . Session::get('token'),
                'Accept' => 'application/json'
            ])->get("{$this->crmApiUrl}/api/tickets/by-year", [
                'year' => $year
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return response()->json([
                    'labels' => array_keys($data),
                    'data' => array_values($data),
                ]);
            }

            return response()->json([
                'error' => 'Failed to fetch tickets by year',
                'details' => $response->body()
            ], $response->status());

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Exception occurred',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getAllTickets()
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . Session::get('token'),
                'Accept' => 'application/json'
            ])->get("{$this->crmApiUrl}/api/tickets");

            if ($response->successful()) {
                return response()->json($response->json());
            }

            return response()->json([
                'error' => 'Failed to fetch tickets',
                'details' => $response->body()
            ], $response->status());

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Exception occurred',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**========================================================================
     * LEADS
     *=======================================================================*/
    public function getTotalLeads()
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . Session::get('token'),
                'Accept' => 'application/json'
            ])->get("{$this->crmApiUrl}/api/leads/total");

            if ($response->successful()) {
                return $response->json();
            }

            return response()->json([
                'error' => 'Failed to fetch total leads',
                'details' => $response->body()
            ], $response->status());

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Exception occurred',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getLeadsByStatus()
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . Session::get('token'),
                'Accept' => 'application/json'
            ])->get("{$this->crmApiUrl}/api/leads/by-status");

            if ($response->successful()) {
                $data = $response->json();
                return response()->json([
                    'labels' => array_keys($data),
                    'data' => array_values($data),
                ]);
            }

            return response()->json([
                'error' => 'Failed to fetch leads by status',
                'details' => $response->body()
            ], $response->status());

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Exception occurred',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getLeadsByYear(Request $request)
    {
        try {
            $year = $request->query('year');
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . Session::get('token'),
                'Accept' => 'application/json'
            ])->get("{$this->crmApiUrl}/api/leads/by-year", [
                'year' => $year
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return response()->json([
                    'labels' => array_keys($data),
                    'data' => array_values($data),
                ]);
            }

            return response()->json([
                'error' => 'Failed to fetch leads by year',
                'details' => $response->body()
            ], $response->status());

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Exception occurred',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    /**========================================================================
         * DEPENSES
         *=======================================================================*/
        public function getTotalDepensesTickets()
        {
            try {
                $response = Http::withHeaders([
                    'Authorization' => 'Bearer ' . Session::get('token'),
                    'Accept' => 'application/json'
                ])->get("{$this->crmApiUrl}/api/depenses/total/tickets");

                if ($response->successful()) {
                    return $response->json();
                }

                return response()->json([
                    'error' => 'Failed to fetch total ticket expenses',
                    'details' => $response->body()
                ], $response->status());

            } catch (\Exception $e) {
                return response()->json([
                    'error' => 'Exception occurred',
                    'message' => $e->getMessage()
                ], 500);
            }
        }

        public function getTotalDepensesLeads()
        {
            try {
                $response = Http::withHeaders([
                    'Authorization' => 'Bearer ' . Session::get('token'),
                    'Accept' => 'application/json'
                ])->get("{$this->crmApiUrl}/api/depenses/total/leads");

                if ($response->successful()) {
                    return $response->json();
                }

                return response()->json([
                    'error' => 'Failed to fetch total lead expenses',
                    'details' => $response->body()
                ], $response->status());

            } catch (\Exception $e) {
                return response()->json([
                    'error' => 'Exception occurred',
                    'message' => $e->getMessage()
                ], 500);
            }
        }
}