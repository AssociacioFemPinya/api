<?php

namespace App\Actions\Fortify;

use App\Models\ApiUser;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use App\Models\CastellerConfig;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): ApiUser
    {
        Validator::make($input, [
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique(ApiUser::class),
            ],
            'password' => $this->passwordRules(),
            'token' => [
                'required',
                'string',
                'max:255',
                Rule::exists(CastellerConfig::class, 'api_token'),
            ],
        ])->validate();

        $casteller = CastellerConfig::where('api_token', $input['token'])->first()->getCasteller();

        $userApi = ApiUser::create([
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
        ]);

        $casteller->apiUsers()->attach($userApi->id_api_user);

        //$userApi->castellers()->attach($casteller->id_casteller);
        //$userApi->save();

        return $userApi;
    }
}
