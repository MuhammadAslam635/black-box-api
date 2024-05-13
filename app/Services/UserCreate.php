<?php

namespace App\Services;

use App\Models\Profile;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class UserCreate
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        // Initialize any dependencies or configurations here if needed.
    }

    /**
     * Create a new user in the system.
     *
     * @param array $data User data for creating a new user.
     * @return User The newly created user instance.
     */
    public function createUser(array $data): User
    {
        // Ensure the password is properly hashed before saving.
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        // Create and return the new user instance.
        $user = User::create([
            'name' => $data['name'] ?? null,
            'email' => $data['email'] ?? null,
            'password' => $data['password'] ?? null,
            'utype' => $data['utype'] ?? 'user',
            'status' => $data['status'] ?? 'pending',
            'remember_token' => $data['remember_token'] ?? null,
        ]);

        // Prepare profile data
        $profileData = [
            'user_id' => $user->id,
            'profile_picture' => $data['profile_picture'] ?? null,
            'bio' => $data['bio'] ?? 'user',
        ];

        // Create the user profile
        $this->userProfile($profileData);


        return $user;
    }

    /**
     * Create or update the user profile.
     *
     * @param array $data The data used to create or update the profile.
     * @return Profile The created or updated profile instance.
     * @throws Exception If there is an error during the profile creation process.
     */
    public function userProfile(array $data): Profile
    {
        // Handle image upload
        if (!empty($data['profile_picture'])) {
            $imageName = Carbon::now()->timestamp . '.' . $data['profile_picture']->getClientOriginalExtension();
            $data['profile_picture']->storeAs('profiles', $imageName, 'public');
            $data['profile_picture'] = 'profiles/' . $imageName;
        }

        // Create and return the profile
        return Profile::create([
            'user_id' => $data['user_id'],
            'profile_picture' => $data['profile_picture'] ?? 'profiles/user.jpg',
            'bio' => $data['bio']
        ]);
    }
    public function edit(int $id, array $data): ?User
    {
        $user = User::find($id);

        if ($user) {
            $user->update([
                'name' => $data['name'] ?? $user->name,
                'email' => $data['email'] ?? $user->email,
                'password' => isset($data['password']) ? bcrypt($data['password']) : $user->password,
                'remember_token' => $data['remember_token'] ?? $user->remember_token,
            ]);
        }

        return $user;
    }
    public function delete(int $id): bool
    {
        $user = User::find($id);

        if ($user) {
            return $user->delete();
        }

        return false;
    }
}
