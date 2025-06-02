<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;
use App\Models\Carrera;
use Illuminate\Support\Facades\DB;

class EditorController extends Controller
{
    public function index(){
        //return redirect()->route('inicio');
        return view('uaslp.inicio');
    }

    public function login(Request $request)
    {
        //Contraseña maestra 12345
        
        $respuesta = $this->validar_sesion($request->rpe, $request->password);
        

        if (!$respuesta) 
            return view('login');

        //Consultar usuario por rpe
        $usuario = Usuario::where('rpe',$request->rpe)->firstOrFail();

        if (!$usuario) 
            return view('login');

        session([
            'rpe' => $usuario->rpe,
            'cve_carrera' => $usuario->cve_carrera,
            'rol' => $usuario->rol,
        ]);

        return redirect()->route('inicio');
    }

    public function inicio(Request $request)
    {
        $rol = session('rol');
        if ($rol == 1) {
            $carreras = Carrera::where('tipo', 'LICENCIATURA')->get();
            $usuarios = Usuario::all();
            return view('uaslp.inicio', ['carreras' => $carreras, 'usuarios' => $usuarios]);
        } else {
            return redirect('/')->with('alert', 'Usa rpe 1');
        }
    }

    public function logout(Request $request)
    {
        session([
            'rpe' => null,
            'cve_carrera' => null,
            'rol' => null,
        ]);
        return view('login');
    }

    

    public function validar_sesion($rpe, $pass)
    {

        if(strcmp($pass,'12345') === 0)
            return 1;

        $conectado = 0;

        # Checar la conexión con el servidor nuevo
        $hostldap = '148.224.94.22';
        $userldap = 'buaslp\\'.strval($rpe);
        $passldap = $pass;

        $ldapconn = ldap_connect($hostldap) or die("Imposible conectar...");     

        if ($ldapconn) {
            # Especifico la versión del protocolo LDAP
            ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3) or die ("Imposible asignar el protocolo LDAP !");
            ldap_set_option($ldapconn, LDAP_OPT_REFERRALS, 0);
            
            # Valido las credenciales para accesar al servidor LDAP
            $login = @ldap_bind($ldapconn, $userldap, $passldap); # or die ("Imposible validar en el servidor LDAP !");

            if ($login) $conectado = 1;
        }

        # Si no se logró la conexión con el servidor nuevo, intentarlo con el servidor anterior
        if (!$conectado) {
            # $hostldap = 'uaslp.local';    
            $hostldap = '148.224.97.71';
            $userldap = 'uaslp\\'.strval($rpe);
            $passldap = $pass;

            $ldapconn = ldap_connect($hostldap) or die("Imposible conectar...");     

            if ($ldapconn) {
                # Especifico la versión del protocolo LDAP
                ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3) or die ("Imposible asignar el protocolo LDAP !");
                ldap_set_option($ldapconn, LDAP_OPT_REFERRALS, 0);
                
                # Valido las credenciales para accesar al servidor LDAP
                $login = @ldap_bind($ldapconn, $userldap, $passldap); # or die ("Imposible validar en el servidor LDAP !");

                if ($login) $conectado = 1;
            }
        }
        
        return $conectado;
    }
}
