<?php

namespace App\Http\Controllers;

use BaconQrCode\Renderer\Image\ImagickImageBackEnd;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use PragmaRX\Google2FA\Google2FA;

class DashboardController extends Controller
{
    public function index()
    {
        $google2fa = new Google2FA;
        $user = Auth::user();

        $qrcodeUrl = $google2fa->getQRCodeUrl(
            'Aziz Ramdan Lab',
            $user->email,
            $user->google2fa_secret
        );

        $writer = new Writer(
            new ImageRenderer(
                new RendererStyle(400),
                new ImagickImageBackEnd()
            )
        );

        $qrcodeImage = base64_encode($writer->writeString($qrcodeUrl));
        
        return Inertia::render('Dashboard', [
            'qrcode' => $qrcodeImage
        ]);
    }

    public function check2fa(Request $request)
    {
        $request->validate([
            'code' => ['required', 'string']
        ]);

        $google2fa = new Google2FA;
        $secret = $request->code;
        $user = Auth::user();

        $valid = $google2fa->verifyKey($user->google2fa_secret, $secret);

        return response()->json([
            'success' => $valid
        ]);
    }
}
