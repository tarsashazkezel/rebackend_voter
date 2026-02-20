<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;

class UserController
{
    use ApiResponse;

    public function __construct(
        protected UserService $service
    ) {}
    public function index()
    {
       $this->authorize('viewAny', User::class);

        $users = $this->service->index();

        return $this->success(
            UserResource::collection($users),
            'Felhasználók listája',200
        );
    }
    public function store(Request $request)
    {
        //
    }
    public function show(string $id)
    {
        $this->authorize('view', $user);

        return $this->ok(
            new UserResource(
                $this->service->show($user)
            ),
            'Felhasználó adatai'
        );
    }
    public function update(Request $request, string $id)
    {
        $this->authorize('update', $user);

        $updated = $this->service->update(
            $user,
            $request->only(['role_id', 'ownership_ratio'])
        );

        return $this->show(
            new UserResource($updated),
            'Felhasználó frissítve'
        );
    }
    public function destroy(string $id)
    {
        $this->authorize('delete', $user);

        $this->service->delete($user);

        return $this->noContent();
    }
}
