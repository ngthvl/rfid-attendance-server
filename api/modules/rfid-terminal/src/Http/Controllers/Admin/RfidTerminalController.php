<?php


namespace Tamani\RfidTerminal\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Spatie\QueryBuilder\QueryBuilder;
use Tamani\RfidTerminal\Http\Resources\TerminalResource;
use Tamani\RfidTerminal\Models\RfidTerminal;

class RfidTerminalController extends Controller
{
    public function index()
    {
        $terminals = QueryBuilder::for(RfidTerminal::class)->paginate();

        return TerminalResource::collection($terminals);
    }

    public function show(string $id)
    {
        $terminal = QueryBuilder::for(RfidTerminal::class)->find($id);

        if(!$terminal){
            return $this->respondWithError('terminal_not_found', 404, 'Terminal not found');
        }

        return new TerminalResource($terminal);
    }

    public function authorizeDevice(string $id)
    {
        /** @var RfidTerminal $terminal */
        $terminal = QueryBuilder::for(RfidTerminal::class)->find($id);

        $token = $terminal->createToken('device_personal')->accessToken;

        $secretPlain = Str::random(64);
        $secret = Hash::make($secretPlain);

        $terminal->secret = $secret;
        $terminal->save();

        $response = $this->respondWithTokenAndMeta($token, $terminal, [
            'attendance_endpoint' => url('/api/v1/terminal/attendance'),
            'secret' => $secretPlain
        ]);

        try {
            Http::post($terminal->ip_address . ':18085', $response);
        } catch (\Exception $e){
            return $this->respondWithError('client_error', 500, $e->getMessage());
        }

        return $this->respondWithEmptyData();
    }
}
