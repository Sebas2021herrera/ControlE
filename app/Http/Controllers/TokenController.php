<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Token;
use App\Models\User;
use App\Models\Usuario;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class TokenController extends Controller
{
    public function generateToken(Request $request)
    {
        $request->validate([
            'correo_personal' => 'required|email',
            'contraseña' => 'required',
        ]);

        $user = User::where('correo_personal', $request->correo_personal)->first();

        if ($user && Hash::check($request->contraseña, $user->getAuthPassword())) {
            $token = Str::random(64);

            Token::create([
                'user_id' => $user->id,
                'token' => $token,
                'expires_at' => now()->addHours(2),
            ]);

            return response()->json(['token' => $token]);
        }

        return response()->json(['message' => 'Credenciales inválidas'], 401);
    }

    public function validateToken(Request $request)
    {
        $request->validate([
            'token' => 'required|string',
        ]);

        $token = Token::where('token', $request->token)->first();

        if ($token && $token->expires_at > now()) {
            return response()->json(['message' => 'Token válido']);
        }

        return response()->json(['message' => 'Token inválido o expirado'], 401);
    }
}
