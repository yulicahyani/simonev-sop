<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\JWT\JWT;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Session;

class SSOBrokerController extends Controller
{
    private $protocol, $ssoServerLink, $logoutLink, $ssoDomain, $logoutLinkSelf;

	public function __construct()
    {
		// ----------------------------------------------------------------------------------
		// PENTING UNTUK DIPERHATIKAN !!!
		//
		// Ini adalah link autentikasi untuk menuju SSO server
		// Silahkan diganti bagian domainnya
		// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
		$this->ssoServerLink  = config('sso.sso_domain').'/authBroker/';
        // ----------------------------------------------------------------------------------
        $this->protocol = ((new Request)->secure()) ? 'https' : 'http';
        $this->logoutLinkSelf   = $this->protocol . '://' . $_SERVER['HTTP_HOST'] . '/logout';
        $this->ssoDomain = config('sso.sso_domain').'/';
        $this->logoutLink = config('sso.sso_domain') . '/logout';
	}

    public function authenticateToSSO(Request $request)
    {
    	# Jika terdapat kiriman data dari server SSO
    	if (!empty($request->authData)) {
            $client = new Client;
    		$response = $client->request('POST', $this->ssoDomain . 'api/v1/auth/jwt/verify', [
                'form_params' => [
                    'token' => $request->authData
                ]
            ]);

            $res = json_decode($response->getBody());

            if ($res->status) {
                $JWT = new JWT();
                $JWT->setJWTString($res->data);

                if ($JWT->decodeJWT()) {
                    # If session is valid then set session and redirect page
                    if(session()->getId()==$JWT->getPayloadJWT()->sessionRequest){
                        if ($JWT->getPayloadJWT()->two_factor) {
                            header('Location:'.$this->ssoDomain.'/verify');
                            exit;
                        } else {
                            session(['UserIsAuthenticated'=>1]);
                            session(['authUserData'=>$JWT->getPayloadJWT()]);
    
                            $data = $JWT->getPayloadJWT();
                            if(!empty($data->roles)){

                                $role = $data->roles[0]->name;
                                $role_code = $data->roles[0]->role_code;
                                $roles = $data->roles;
                                $userid = $data->user->id;
                                $nama = $data->user->name;
                                $nik = $data->user->nik;
                                $nip = $data->user->nip;
                                $unit = $data->roles[0]->unit;
                                $unit_id = $data->roles[0]->unit_id;
                                $unit_code = $data->roles[0]->unit_code;

                                session(['nip' => $nip]);
                                session(['nik' => $nik]);
                                session(['nama' => $nama]);
                                session(['id' => $userid]);
                                session(['defaultRole'=>$role]);
                                session(['defaultRoleCode'=>$role_code]);
                                session(['roles' => $roles]);
                                session(['unit'=>$unit]);
                                session(['unit_id'=>$unit_id]);
                                session(['unit_code'=>$unit_code]);
                                return redirect(session('authUserData')->urlToRedirect);
                            }else{
                                return view('errors.409');
                            }
                        }
                    } else {
                        # if session is invalid
                        echo("Invalid Browser Session !");
                        exit;
                    }
                } else {
                    echo("Invalid JWT data !");
                    exit;
                }
            } else {
                echo("Invalid JWT String Data !");
                exit;
            }
    	}

	    # User already authenticated
		if (!empty(session('authUserData'))){

            return 1;

		} 
        else {
			# If user not authenticated yet, then redirect to SSO server
			$payloadJWT = [
				'redirect' 			=> $this->protocol . '://' . $_SERVER['HTTP_HOST'] . '/authData',
				'urlToRedirect'		=> session('urlToRedirect'),
				'logoutLink' 		=> $this->logoutLinkSelf,
				'kode_broker'		=> config('sso.broker_code'),
				'sessionRequest' 	=> session()->getId()
			];
            
			$JWT = new JWT();
			$JWT->setPayloadJWT($payloadJWT);
			$JWT->encodeJWT();

			session()->flush();
			header('Location:'.$this->ssoServerLink.$JWT->getJWTString());
            exit;
		}
    }

    function myfunction($arrays, $field, $value)
    {
        foreach($arrays as $key => $array)
        {
            if ( $array->$field === $value )
                return $array;
        }
        return false;
    }

    public function changeRole($role, $unit_id)
	{
		session(['defaultRole' => $role]);
		foreach(session('authUserData')->roles as $roles){
			//if($roles->id == $role){
			if($roles->name == $role && $roles->unit_id == (int) decrypt($unit_id)){
				session(['unit'=>$roles->unit]);
				session(['unit_id'=>$roles->unit_id]);
                session(['unit_code'=>$roles->unit_code]);
				session(['defaultRoleCode'=>$roles->role_code]);
			}

		}
		return redirect()->route('backend.beranda');
	}

    public function logoutSSO(Request $request){
        $logoutLink = session('authUserData')->ssoLogoutLink;
        $logoutLink = $this->logoutLink;
        $request->session()->flush();

        return redirect($logoutLink);
    }

    public function logout(Request $request){
		// Log::info('Logout SSO Broker');
		if($request->sessionId){
			Session::getHandler()->destroy($request->sessionId);
        }
        Session::flush();
        // Auth::logout();
		return redirect($this->logoutLink);
    }

    public function login_sso_by_token($token){
        $user = decrypt($token);

        session(['UserIsAuthenticated'=>1]);
        session(['authUserData' => $user]);
        session(['defaultRole'=> $user->roles[0]]);

        return redirect(session('authUserData')->urlToRedirect);
    }
}
