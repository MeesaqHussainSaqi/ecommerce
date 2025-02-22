<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notifiable;
use Illuminate\Validation\Rule;

class Task extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'username',
        'email',
        'phone',
        'status',
        'password'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    public static function Rules(Request $request, $method = null)
    {
        if ($method == null)
            $method = $request->method();

        $rules = [];

        $rules = match ($method) {
            'POST' => [
                'username' => [
                    'required',
                    // Rule::unique('users')->where(function ($query) {
                    //     self::scopeDeleted($query);
                    // }),
                    'unique:users,username',
                    'min:3',
                    'max:100' //'custom_alpha_num_dash_dot_nosp',
                ],
                'first_name' => 'required|min:3|max:100', //custom_alpha_dash_dot
                'last_name' => 'required|min:3|max:100', //custom_alpha_dash_dot
                'email' => [
                    'required',
                    'unique:users,email',
                    // Rule::unique('users')->where(function ($query) {
                    //     self::scopeDeleted($query);
                    // }),
                    'min:3',
                    'max:100' //'custom_alpha_num_dash_dot_nosp',
                ],
                // 'password' => [
                //     'required',
                //     'min:8',
                //     'max:100',
                //     'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]/', // Password criteria
                // ],
            ],
            'PUT' => [
                'id' => 'required|integer',
                'username' => [
                    'nullable',
                    // Rule::unique('users')->where(function ($query) use ($request) {
                    //     self::scopeDeleted($query)
                    //         ->where('id', '!=', $request->id);
                    // }),
                ],
                'first_name' => 'nullable|min:3|max:100', //custom_alpha_dash_dot
                'last_name' => 'nullable|min:3|max:100', //custom_alpha_dash_dot
                'email' => [
                    'nullable',
                    // Rule::unique('users')->where(function ($query) use ($request) {
                    //     self::scopeDeleted($query)
                    //         ->where('id', '!=', $request->id);
                    // }),
                    'min:3',
                    'max:100' //'custom_alpha_num_dash_dot_nosp',
                ],

            ],
            'PATCH' => [
                'id' => 'required|integer',
                'activate' => 'required|numeric|between:0,10'
            ],
            'CHANGE_PASSWORD' => [
                'user_id' => 'nullable|integer',
                'new_password' => [
                    'required',
                    'min:8',
                    'max:100',
                    'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]/', // Password criteria
                ]
            ],
            'CHANGE_PASSWORD_ADMIN' => [
                'user_id' => 'nullable|integer',
                'old_password' => 'required|min:8|max:100',
                'new_password' => [
                    'required',
                    'min:8',
                    'max:100',
                    'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]/', // Password criteria
                ]
            ],
            'DELETE' => [
                'id' => 'required|integer',
            ],
            'GET_ONE' => [
                'id' => 'required|integer'
            ],
            'GET_ALL' => [
                // 'fields' => ''
            ],
        };


        return $rules;
    }

    /**
     * Get the validation custom messages.
     *
     * @return array
     */
    public static function Messages($request, $method = null)
    {
        if ($method == null)
            $method = $request->method();

        $messages = [];

        $commonMessages = [
            'username.required' => "Please provide username.",
            'username.unique' => "Username already exists.",
            'username.min' => "The username must be at least :min characters.",
            'username.max' => "The username may not be greater than :max characters.",

            'firstName.required' => "Please provide first name.",
            'firstName.min' => "The first name must be at least :min characters.",
            'firstName.max' => "The first name may not be greater than :max characters.",

            'last_name.required' => "Please provide last name.",
            'last_name.min' => "The last name must be at least :min characters.",
            'last_name.max' => "The last name may not be greater than :max characters.",

            'email.required' => "Please provide email.",
            'email.unique' => "Email already exists.",
            'email.email' => "Please provide a valid email.",
            'email.min' => "The email must be at least :min characters.",
            'email.max' => "The email may not be greater than :max characters.",

            'phone.required' => "Please provide a phone number.",
            'phone.numeric' => "The phone number may only contain numbers.",
            'phone.digits_between' => "The phone number must be between 8 and 15 digits.",

            'address.min' => "The address must be at least :min characters.",
            'address.max' => "The address may not be greater than :max characters.",

            'designation.required' => "Please select user designation",
            'designation.exists' => "Designation not exists. Please select a valid designation",

            'user_group_id.required' => "Please select user group",
            'user_group_id.exists' => "User group not exists. Please select a valid user group",

            'password.required' => "Please provide a password.",
            'password.min' => "The password must be at least :min characters.",
            'password.max' => "The password may not be greater than :max characters.",
            'password.regex' => 'Password should contain at least one uppercase letter, one small letter, one number, & one special characters.',

            'dealer_id.required' => 'Please select dealer when creating user.',
            'dealer_id.exists' => 'Dealer dont exist with the provided id.',
        ];

        $idMessages = [
            'id.required' => "Please provide user id.",
            'id.integer' => "Id must be an integer.",
        ];

        $passChangeMessages = [
            'user_id.integer' => "UserID must be an integer.",

            'old_password.required' => "Please provide old password.",
            'new_password.required' => "Please provide new password.",

            'old_password.min' => "The old password must be at least :min characters.",
            'old_password.max' => "The old password may not be greater than :max characters.",

            'new_password.min' => "The new password must be at least :min characters.",
            'new_password.max' => "The new password may not be greater than :max characters.",

            'new_password.regex' => 'Password should contain at least one uppercase letter, one small letter, one number, & one special characters.',
        ];

        $statusMessage = [
            'activate.required' => "Please provide activate flag.",
            'activate.numeric' => "Activate flag must be an integer.",
            'activate.between' => "The activate flag must be between :min and :max.",
        ];

        $messages = match ($method) {
            'POST' => $commonMessages,
            'PUT' => $commonMessages + $idMessages,
            'PATCH' => $idMessages + $statusMessage,
            'DELETE' => $idMessages,
            'GET_ONE' => $idMessages,
            'CHANGE_PASSWORD' => $passChangeMessages,
            'CHANGE_PASSWORD_ADMIN' => $passChangeMessages,
            'GET_ALL' => $messages = [],
        };

        return $messages;
    }
}
