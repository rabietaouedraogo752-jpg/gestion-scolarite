<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
        ]);

        $remember = $request->filled('remember');
        $login = $credentials['login'];
        $password = $credentials['password'];

        $authenticated = Auth::attempt(['username' => $login, 'password' => $password], $remember)
            || Auth::attempt(['email' => $login, 'password' => $password], $remember);

        if ($authenticated) {
            $request->session()->regenerate();

            $user = Auth::user();
            
            // Correction ici : alignement sur la valeur 'chef_departement'
            switch ($user->role) {
                case 'admin':
                    return redirect()->intended(route('admin.tableau_bord'));
                case 'enseignant':
                    return redirect()->intended(route('enseignant.tableau_bord'));
                case 'etudiant':
                    return redirect()->intended(route('etudiant.tableau_bord'));
                case 'chef_departement':
                    return redirect()->intended('/departement/tableau_bord');
                default:
                    return redirect()->intended('/');
            }
        }

        return back()
            ->withErrors(['username' => 'Identifiants invalides. Vérifiez votre nom d’utilisateur et votre mot de passe.'])
            ->onlyInput('username');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}