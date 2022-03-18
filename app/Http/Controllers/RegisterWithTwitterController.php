<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Abraham\TwitterOAuth\TwitterOAuth;
use Laravel\Socialite\Facades\Socialite;
use App\Models\twitter\RegisterWithTwitter;
use App\Http\Requests\StoreRegisterWithTwitterRequest;

class RegisterWithTwitterController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**hand over to twitter for user data permission
     * @return [type]
     */
    public function loginWithTwitter()
    {
        $connection = new TwitterOAuth(config('twitter.twitter_consumer_key'), config('twitter.twitter_secret_key'));
        try {
            $request_token = $connection->oauth('oauth/request_token', array('oauth_callback' => env('TWITTER_REDIRECT')));
            // return $url = $connection->url('oauth/authorize', array('oauth_token' => $request_token['oauth_token']));
            $url = $connection->url('oauth/authorize', array('oauth_token' => $request_token['oauth_token']));
            return redirect($url);
        } catch (Exception $e) {
            return $this->failedResponse($e->getMessage(), "Failed to connect to Twitter");
        }
    }

    /**logs in or registers user to system
     * @param Request $request
     * 
     * @return [type]
     */
    public function twitterCallBack(Request $request)
    {
        try {

            $request_token['oauth_token'] = $request->get('oauth_token');
            $request_token['oauth_token_secret'] = $request->get('oauth_verifier');

            $request_for_access_token = new TwitterOAuth(config('twitter.twitter_consumer_key'), config('twitter.twitter_secret_key'), $request_token['oauth_token'], $request_token['oauth_token_secret']);

            $access_token = $request_for_access_token->oauth("oauth/access_token", ["oauth_verifier" => $request->get('oauth_verifier')]);

            $request_for_verification = new TwitterOAuth(config('twitter.twitter_consumer_key'), config('twitter.twitter_secret_key'), $access_token['oauth_token'], $access_token['oauth_token_secret']);

            $user = $request_for_verification->get('account/verify_credentials', ['tweet_mode' => 'extended', 'include_email' => 'true', 'include_entities' => 'true']);
            $previouslyRegisteredUser = User::where('social_id', $user->id_str)->first();

            if ($previouslyRegisteredUser) {

                Auth::login($previouslyRegisteredUser);

                return $this->successResponse($previouslyRegisteredUser, "Previously Registered User Found.");
            } else {
                $newlyRegisteredUser = User::create([
                    'name' => $user->name,
                    'user_name' => $user->screen_name,
                    'email' => $user->email,
                    'social_id' => $user->id_str,
                    'image' => $user->profile_image_url_https,
                    'oauth_type' => 'twitter',
                    'password' => bcrypt($user->name),
                ]);

                Auth::login($newlyRegisteredUser);

                return $this->successResponse($newlyRegisteredUser, "Success in Registering using Data Retrieved from twitter.");
            }
        } catch (Exception $e) {
            return $this->failedResponse($e->getMessage(), "Failed Due an Exception");
        }
    }


    public function destroy(RegisterWithTwitter $registerWithTwitter)
    {
        //
    }
}
