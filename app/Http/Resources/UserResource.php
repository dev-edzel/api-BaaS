<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $user_info = new UserInfoResource($this->whenLoaded('userInfo'));
        $role = new RoleResource($this->whenLoaded('role'));

        return [
            'id' => $this->id,
            'username' => $this->username,
            'email' => $this->email,
            'phone_number' => $this->phone_number,
            'password' => $this->password,
            'kyc_status' => $this->kyc_status,
            'two_factor_enabled' => $this->two_factor_enabled,
            'user_info' => $user_info,
            'role' => $role,
        ];
    }
}
