<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\User as ResourceUser;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * 
 * [Description UserController]
 */
class UserController extends Controller
{
    /**
     * Creates a user
     * @param User $user
     * 
     * @return [type]
     */
    public function store(UserRequest $request)
    {
        $input = $request->all();

        if ($image = $request->file('image')) {
            $imageDestinationPath = '/images/userimages/' . $request->user_name . '/';
            $imageName = date('YmdHis') . "." . $image->getClientOriginalExtension();

            if ($request->image->move(public_path($imageDestinationPath), $imageName)) {
                $request->image = $imageDestinationPath . $imageName;
                $input['image'] = $imageDestinationPath . $imageName;
            }
        }

        $input['password'] = bcrypt($input['password']);

        $user = User::create($input);
        $success['token'] = $user->createToken('auth_token')->plainTextToken;

        return $this->successResponse('Done', 'User Created Successfully');
    }



    /**
     * Logs in a user
     *
     * @param  mixed $request
     * @return void
     */
    public function login(Request $request)
    {

        if (empty($request->email) || empty($request->password)) {
            return $this->failedResponse('No Credentials Received', ['error' => 'Request Body is Empty']);
        }

        if (Auth::attempt([

            'email' => $request->email,
            'password' => $request->password

        ])) {
            $auth = Auth::user();
            $success['token'] = $auth->createToken('auth_token')->plainTextToken;
            $success['email'] = $auth->email;
            return $this->successResponse($success, 'User Logged In!');
        } else {
            return $this->failedResponse('Unauthorized', ['error' => 'Unauthorized']);
        }
    }


    /**
     * Lists all users
     *
     * @return void
     */
    public function index()
    {
        $allUsers = User::all();
        $fetchedUsers = ResourceUser::collection($allUsers);
        if (count($fetchedUsers) > 0) {
            return $this->successResponse($fetchedUsers, 'All Users have been successfully Retrieved');
        } else {
            return $this->failedResponse('No Users Found', ['error' => 'No Users Found']);
        }
    }

    /**
     * show specified User
     *
     * @param  mixed $user
     * @return void
     */
    public function show(User $user)
    {
        return $this->successResponse(new ResourceUser($user), 'User Fetching Successful');
    }


    /**
     * Updates A User
     * @param User $user
     * 
     * @return [type]
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        try {
            $newData = $request->all();
            // dd($newData);
            if ($image = $request->file('image')) {
                $imageDestinationPath = '/images/userimages/' . $user->user_name . '/';
                $imageName = date('YmdHis') . "." . $image->getClientOriginalExtension();

                $userOldThumbnail = $user->image;
                if (File::exists($userOldThumbnail)) {
                    File::delete($userOldThumbnail);
                }
                if ($user->image->move(public_path($imageDestinationPath), $imageName)) {
                    $user->image = $imageDestinationPath . $imageName;
                    $input['image'] = $imageDestinationPath . $imageName;
                } else {
                    return $this->failedResponse('I/O Error', 'Could not Update Image');
                }
                $newData['image'] = $imageDestinationPath . $imageName;
            }
            $user->update($newData);
            return $this->successResponse($user, 'User updated successfully');
        } catch (Exception $exception) {
            return $this->failedResponse($exception->getMessage(), 'Error in updating user data');
        }
    }

    /**
     * Deletes the User
     * @param User $user
     *
     * @return [type]
     */
    public function destroy(User $user)
    {
        $user->delete();
        return $this->successResponse('Done', 'Data deleted Successfully');
    }
}
