<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use GuzzleHttp\Client;
use App\Models\Score;

class ApiController extends Controller
{
    private $token = '';
    private $profileID = '';


    /**
     * Define LinkedIn config.
     */
    public function config() {
        define('CLIENT_ID', '7846vlaalrg8j3');
        define('CLIENT_SECRET', 'wNdwAELtpUoCCiTd');
        define('REDIRECT_URL', 'https://quiz.webmaster.sh/linkedin');
        define('SCOPES', 'r_emailaddress,r_liteprofile,w_member_social');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($game_id)
    {
        if (Auth::check()) {
            $this->config();

            $result = Score::where('game_id', $game_id)->where('score', 1)->count();

            $url = "https://www.linkedin.com/oauth/v2/authorization?response_type=code&client_id=".CLIENT_ID."&redirect_uri=".REDIRECT_URL."&scope=".SCOPES;
            $_GET['code'] = !empty($_GET['code']) ? $_GET['code'] : null;

            $return = '<a href="'. $url .'">UDOSTĘPNIJ</a>';

            if ( !empty( $_GET['code'] ) ) {

                try {
                    $client = new Client(['base_uri' => 'https://www.linkedin.com']);
                    $response = $client->request('POST', '/oauth/v2/accessToken', [
                        'form_params' => [
                                "grant_type" => "authorization_code",
                                "code" => $_GET['code'],
                                "redirect_uri" => REDIRECT_URL,
                                "client_id" => CLIENT_ID,
                                "client_secret" => CLIENT_SECRET,
                        ],
                    ]);
                    $data = json_decode($response->getBody()->getContents(), true);
                    $access_token = $data['access_token']; 
                    
                    $this->token = $access_token;
                } catch(\Exception $e) {
                    $return = $e->getMessage();
                }
        
        
                try {
                    $client = new Client(['base_uri' => 'https://api.linkedin.com']);
                    $response = $client->request('GET', '/v2/me', [
                        'headers' => [
                            "Authorization" => "Bearer " . $access_token,
                        ],
                    ]);
                    $data = json_decode($response->getBody()->getContents(), true);
                    $this->profileID = $data['id']; // store this id somewhere
                } catch(\Exception $e) {
                    $return = $e->getMessage();
                }
        
                
                $link = 'https://quiz.webmaster.sh/';
                $access_token = $this->token;
                $linkedin_id = $this->profileID;
                $body = new \stdClass();
                $body->content = new \stdClass();
                $body->content->contentEntities[0] = new \stdClass();
                $body->text = new \stdClass();
                $body->content->contentEntities[0]->thumbnails[0] = new \stdClass();
                $body->content->contentEntities[0]->entityLocation = $link;
                $body->content->contentEntities[0]->thumbnails[0]->resolvedUrl = "https://media-exp1.licdn.com/dms/image/C4E21AQHmxi4aShUFTg/plfm-displayapplogo-shrink_200_200/0/1643833945322?e=1644091200&v=beta&t=9nohxgH3lJUlf76wBMtM5_vHiXKG9_-XM5erkbFWBi8";
                $body->content->title = 'Gratulacje wygrałeś w quizie!';
                $body->owner = 'urn:li:person:'.$linkedin_id;
                $body->text->text = 'Wynik Twoich osiągnięć wynosi: '. $result ;
                $body_json = json_encode($body, true);
                
                try {
                    $client = new Client(['base_uri' => 'https://api.linkedin.com']);
                    $response = $client->request('POST', '/v2/shares', [
                        'headers' => [
                            "Authorization" => "Bearer " . $access_token,
                            "Content-Type"  => "application/json",
                            "x-li-format"   => "json"
                        ],
                        'body' => $body_json,
                    ]);
                
                    $return = 'Post is shared on LinkedIn successfully.';
                } catch(\Exception $e) {
                    $return = $e->getMessage(). ' for link '. $link;
                }
        
                return $return;
            }
            return $return;
        }
    }

    /**
     * API LinkedIn post on wall.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendPost($result)
    {
        $this->config();

            $client = new Client(['base_uri' => 'https://www.linkedin.com']);
            $response = $client->request('POST', '/oauth/v2/accessToken', [
                'form_params' => [
                        "grant_type" => "authorization_code",
                        "code" => $_GET['code'],
                        "redirect_uri" => REDIRECT_URL,
                        "client_id" => CLIENT_ID,
                        "client_secret" => CLIENT_SECRET,
                ],
            ]);
            $data = json_decode($response->getBody()->getContents(), true);
            $access_token = $data['access_token']; 
            
            $this->token = $access_token;



        try {
            $client = new Client(['base_uri' => 'https://api.linkedin.com']);
            $response = $client->request('GET', '/v2/me', [
                'headers' => [
                    "Authorization" => "Bearer " . $access_token,
                ],
            ]);
            $data = json_decode($response->getBody()->getContents(), true);
            $this->profileID = $data['id']; // store this id somewhere
        } catch(\Exception $e) {
            $return = $e->getMessage();
        }

        
        $link = 'https://quiz.webmaster.sh/';
        $access_token = $this->token;
        $linkedin_id = $this->profileID;
        $body = new \stdClass();
        $body->content = new \stdClass();
        $body->content->contentEntities[0] = new \stdClass();
        $body->text = new \stdClass();
        $body->content->contentEntities[0]->thumbnails[0] = new \stdClass();
        $body->content->contentEntities[0]->entityLocation = $link;
        $body->content->contentEntities[0]->thumbnails[0]->resolvedUrl = "https://media-exp1.licdn.com/dms/image/C4E21AQHmxi4aShUFTg/plfm-displayapplogo-shrink_200_200/0/1643833945322?e=1644091200&v=beta&t=9nohxgH3lJUlf76wBMtM5_vHiXKG9_-XM5erkbFWBi8";
        $body->content->title = 'Gratulacje wygrałeś w quizie!';
        $body->owner = 'urn:li:person:'.$linkedin_id;
        $body->text->text = 'Wynik Twoich osiągnięć wynosi: '. $result ;
        $body_json = json_encode($body, true);
        
        try {
            $client = new Client(['base_uri' => 'https://api.linkedin.com']);
            $response = $client->request('POST', '/v2/shares', [
                'headers' => [
                    "Authorization" => "Bearer " . $access_token,
                    "Content-Type"  => "application/json",
                    "x-li-format"   => "json"
                ],
                'body' => $body_json,
            ]);
          
            $return = 'Post is shared on LinkedIn successfully.';
        } catch(\Exception $e) {
            $return = $e->getMessage(). ' for link '. $link;
        }

        return $return;
    }

    /**
     * Redirect to profile page
     *
     * @return \Illuminate\Http\Redirect
     */
    public function show()
    {
        $result = 1;
        $api = new ApiController;
        $send = $api->sendPost($result);
        return redirect( route('user.show') );
    }
}
