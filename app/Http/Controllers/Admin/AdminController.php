<?php

namespace App\Http\Controllers\Admin;

use App\Enums\AdConfirmationEnum;
use App\Enums\StatusEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\AdminRequest as ObjRequest;
use App\Http\Requests\UpdateProfileRequest as ObjRequestProfile;
use App\Http\Requests\UpdateProfileImageRequest as ObjRequestProfileImage;
use App\Models\Admin;
use App\Services\Admin\AdminService as ObjService;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;

class AdminController extends Controller
{

    public function __construct(protected ObjService $objService){
    }

    public function index(Request $request)
    {
        return $this->objService->index($request);
    }

        public function destroy($id)
    {
        return $this->objService->delete($id);
    }
    public function updateColumnSelected(Request $request)
    {
        return $this->objService->updateColumnSelected($request,'status',StatusEnum::values());
    }

    public function deleteSelected(Request $request){
        return $this->objService->deleteSelected($request);
    }

    public function myProfile()
    {
        return $this->objService->myProfile();
    }

    public function create()
    {
        return $this->objService->create();
    }

    public function store(ObjRequest $request)
    {
//        dd($request->all());
        $data = $request->validated();
        return $this->objService->store($data);
    }

    public function edit(Admin $admin)
    {
        return $this->objService->edit($admin);
    }

    public function update(ObjRequest $request ,$id)
    {
        $data = $request->validated();
        return $this->objService->update($data,$id);
    }
    public function updateProfile(ObjRequestProfile $request)
    {
//        dd($request->all());

        $data = $request->validated();
        return $this->objService->updateProfile($data);
    }

    public function editProfile()
    {
        return $this->objService->editProfile();
    }
    public function editProfileImage()
    {
        return $this->objService->editProfileImage();
    }
    public function updateProfileImage(ObjRequestProfileImage $request)
    {
//        dd($request->all());

        $data = $request->validated();
        return $this->objService->updateProfileImage($data);
    }

}//end class
