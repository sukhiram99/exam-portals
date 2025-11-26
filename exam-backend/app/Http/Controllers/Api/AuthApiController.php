<?php

namespace App\Http\Controllers\API;
  
use App\Http\Controllers\API\BaseController as BaseController;
use Validator;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\StoreRegistrationRequest;
use App\Http\Requests\LoginUserRequest;
use App\Models\User; // Assuming your User model namespace
use Exception; // Required for catching generic exceptions
use Illuminate\Database\QueryException;

class AuthApiController extends BaseController
{
 
    /**
     * Register a User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(StoreRegistrationRequest $request) 
    {
        // Start the database transaction
        DB::beginTransaction();

        try {

            $input = $request->validated(); // Use validated() for validated data

            // Hash the password before creation
            $input['password'] = bcrypt($input['password']);
            // Create the user within the transaction
            $user = User::create($input);
            $user->roles()->sync([2]); // user roles

            Log::channel('exam_portals')->info('User registration successful for email: ' . $user->email);

            DB::commit();

            $success['user'] = $user;
    
            // Assuming this is a method for standardized API responses
            return $this->sendResponse($success, 'User registered successfully.');

        }catch (QueryException $e) {
            // Default error message for other QueryExceptions
            Log::channel('exam_portals')->error('QueryException caught: ' . $e->getMessage(), [
                'request_data' => $request->all(),
                'error_trace' => $e->getTraceAsString()
            ]);

            return $this->sendError('QueryException caught', $e->getMessage(), 500);

        }catch (Exception $e) {
            // Rollback the transaction on error
            DB::rollBack();
            // Log the error to the 'exam_portals' channel
            Log::channel('exam_portals')->error('User registration failed: ' . $e->getMessage(), [
                'request_data' => $request->all(),
                'error_trace' => $e->getTraceAsString()
            ]);

            // Return a standardized error response (adjust based on your BaseController methods)
            return $this->sendError('Registration Failed', $e->getMessage(), 500);
        }
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
   
    public function login(LoginUserRequest $request)
    {
        try {

            $credentials = $request->validated();

            // MUST use auth('api') to activate JWT guard
            if (! $token = auth('api')->attempt($credentials)) {
                Log::channel('exam_portals')->warning('Failed login attempt for email: ' . $request->email);

                return $this->sendError('Please check your credentials.', [
                    'error' => 'credentials not match.'
                ]);
            }

            Log::channel('exam_portals')->info('Successful login for email: ' . $request->email);

            // Pass token to formatter
            $success = $this->respondWithToken($token);

            return $this->sendResponse($success, 'User login successfully.');

        }catch (QueryException $e) {
            // Default error message for other QueryExceptions
            Log::channel('exam_portals')->error('QueryException caught: ' . $e->getMessage(), [
                'request_data' => $request->all(),
                'error_trace' => $e->getTraceAsString()
            ]);

            return $this->sendError('QueryException caught', $e->getMessage(), 500);

        } catch (Exception $e) {

            Log::channel('exam_portals')->error('Login error for email: ' . $request->email, [
                'exception' => $e->getMessage(),
            ]);

            return $this->sendError('Login Failed', $e->getMessage(), 500);
        }
    }


    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function profile()
    {
        $success = auth()->user();
   
        return $this->sendResponse($success, 'User details fetched successfully.');
    }
  
    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();
        
        return $this->sendResponse([], 'Successfully logged out.');
    }
  
    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        $success = $this->respondWithToken(auth()->refresh());
   
        return $this->sendResponse($success, 'Refresh token return successfully.');
    }
  
    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return [
            'access_token' => $token,
            'token_type'   => 'bearer',
            'expires_in'   => auth('api')->factory()->getTTL() * 60,
        ];
    }

}
