<?php
// backend/app/Http/Controllers/Api/ClientController.php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function index()
    {
        return Client::all();
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:clients,email',
        ]);
        return Client::create([
            'name' => $request->name,
            'email' => $request->email,
            'user_id' => $request->user()->id, // Assure-toi que l'utilisateur est authentifié
        ]);
    }

    public function update(Request $request, Client $client)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:clients,email,' . $client->id,
        ]);
        $client->update($request->only(['name', 'email']));
        return $client;
    }

    public function destroy(Client $client)
    {
        $client->delete();
        return response()->noContent();
    }
}
