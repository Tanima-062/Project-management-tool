<?php
namespace App\Http\Resources\Auth;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\User;

/**
 * @author [Fazlul Kabir Shohag]
 * @email [shohag.fks@mail.com]
 * @create date 2021-03-01 12:45:21
 * @modify date 2021-03-01 12:45:21
 * @desc [description]
 */

class LoginResource extends JsonResource
{
    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'designation' => $this->designation,
            'email' => $this->email,
            'role_id' => $this->role_id,
            'access_token' => $this->access_token,
        ];
    }
}
