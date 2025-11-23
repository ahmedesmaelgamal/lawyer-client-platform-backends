<?php

namespace App\Services\Admin;

use App\Models\Admin;
use App\Models\Setting as ObjModel;
use App\Models\Setting as settingObj;
use App\Services\BaseService;
use Illuminate\Support\Facades\Auth;
use PragmaRX\Google2FA\Google2FA;
use Yajra\DataTables\DataTables;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;

class SettingService extends BaseService
{
    protected string $folder = 'admin/setting';
    protected string $route = 'settings';
    protected SettingObj $settingObj;

    public function __construct(ObjModel $objModel , SettingObj $settingObj , protected Admin $admin , protected Google2FA $google2fa)
    {
        $this->settingObj=$settingObj;
        parent::__construct($objModel);
    }

    public function index($request)
    {
        // $settings = $this->settingObj->find(1);

        $settings=$this->settingObj->all();
        if ($request->ajax()) {
            $obj = $this->getDataTable();
            return DataTables::of($obj)
            ->editColumn('key', function ($obj) {
                return $obj->key;
            })
            ->editColumn('value', function ($obj) {
                return $obj->value;
            })
                ->addColumn('action', function ($obj) {
                        $buttons = ' ';
                    if (Auth::user()->can("update_setting_management")) {

                        $buttons .= '
                        <button type="button" data-id="' . $obj->id . '" class="btn btn-pill btn-info-light editBtn">
                            <i class="fa fa-edit"></i>
                        </button>
                    ';
                    }
                    if (Auth::user()->can("delete_setting_management")) {

                        $buttons .= '<button class="btn btn-pill btn-danger-light" data-bs-toggle="modal"
                            data-bs-target="#delete_modal" data-id="' . $obj->id . '" data-title="' . $obj->name . '">
                            <i class="fas fa-trash"></i>
                        </button>';
                    }
                    return $buttons;
                })
                ->addIndexColumn()
                ->escapeColumns([])
                ->make(true);
        } else {
            return view($this->folder . '/index', [
                'updateRoute' => route("{$this->route}.update", $request),
                'bladeName' => trns($this->route),
                'route' => $this->route,
                'settings'=>$settings
            ]);
        }
    }

    public function create()
    {

        return view("{$this->folder}/parts/create", [
            'storeRoute' => route("{$this->route}.store"),

        ]);
    }

    public function store($data): \Illuminate\Http\JsonResponse
    {
        if (isset($data['image'])) {
            $data['image'] = $this->handleFile($data['image'], 'Setting');
        }
        try {
            $this->createData($data);
            return response()->json(['status' => 200, 'message' => trns('Data created successfully.')]);
        } catch (\Exception $e) {
            return response()->json(['status' => 500, 'message' => trns('Something went wrong.'), trns('error') => $e->getMessage()]);
        }
    }

    public function edit($obj)
    {
        $settings=$this->settingObj->all();
        return view("{$this->folder}/parts/edit", [
            'obj' => $obj,
            'updateRoute' => route("{$this->route}.update"),
            'settings'=>$settings
        ]);
    }

    public function update($data)
    {
        $loader = $this->settingObj->where('key','loader' )->first();
        $logo = $this->settingObj->where('key','logo' )->first();
        $favIcon = $this->settingObj->where('key','fav_icon' )->first();

        if (isset($data['logo'])) {
            $data['logo'] = $this->handleFile($data['logo'], 'Setting');

            if ($logo != 'null') {
            $this->deleteFile($logo);
            }
        }
        if (isset($data['fav_icon'])) {
            $data['fav_icon'] = $this->handleFile($data['fav_icon'], 'Setting');

            if ($favIcon != 'null') {
            $this->deleteFile($logo);
            }
        }
        if (isset($data['loader'])) {
            $data['loader'] = $this->handleFile($data['loader'], 'Setting');

            if ($loader != 'null') {
            $this->deleteFile($loader);
            }
        }

        if (isset($data['system_language'])) {
            $language = $data['system_language'];

            // Validate that the language is either 'ar' or 'en'
            if (in_array($language, ['ar', 'en'])) {
                // Set the application locale
                app()->setLocale($language);

                // Store the selected language in the session
                session(['system_language' => $language]);

                // Set the direction (RTL for Arabic, LTR for English)
                $direction = ($language === 'ar') ? 'rtl' : 'ltr';
                session(['direction' => $direction]);
            }
        }

        try {
            if(isset($data['logo'])){
                $this->settingObj->where('key','logo')->update(['value'=>$data['logo']]);
            }
            if(isset($data['fav_icon'])){
                $this->settingObj->where('key','fav_icon')->update(['value'=>$data['fav_icon']]);
            }
            if(isset($data['loader'])){
                $this->settingObj->where('key','loader')->update(['value'=>$data['loader']]);
            }
            if(isset($data['app_mentainance'])){
            $this->settingObj->where('key','app_mentainance')->update(['value'=>$data['app_mentainance']]);
            }
            if(isset($data['app_version_android'])){
            $this->settingObj->where('key','app_version_android')->update(['value'=>$data['app_version_android']]);
            }
            if(isset($data['app_version_ios'])){
                $this->settingObj->where('key','app_version_ios')->update(['value'=>$data['app_version_ios']]);
            }
            if(isset($data['referral_receiver_points'])){
            $this->settingObj->where('key','referral_receiver_points')->update(['value'=>$data['referral_receiver_points']]);
            }
            if(isset($data['referral_sender_points'])){
            $this->settingObj->where('key','referral_sender_points')->update(['value'=>$data['referral_sender_points']]);
            }
            if(isset($data['court_case_vat'])){
            $this->settingObj->where('key','court_case_vat')->update(['value'=>$data['court_case_vat']]);
            }
            if(isset($data['about_ar'])){
            $this->settingObj->where('key','about_ar')->update(['value'=>$data['about_ar']]);
            }
            if(isset($data['about_en'])){
                $this->settingObj->where('key','about_en')->update(['value'=>$data['about_en']]);
            }
            if(isset($data['filePrice'])){
                $this->settingObj->where('key','filePrice')->update(['value'=>$data['filePrice']]);
            }
            return response()->json(['status' => 200, 'message' => trns('Data updated successfully.'),'redirect'=>route('settings.index')]);
        } catch (\Exception $e) {
            return response()->json(['status' => 500, 'message' => trns('Something went wrong.'), trns('error') => $e->getMessage(), 'redirect' => route('settings.index')]);
        }
    }




    public function secure_settings()
    {
        $user = Admin::find(Auth::id());

        if (!$user->twofa_secret || !$user->twofa_qr) {
            $secret = $this->google2fa->generateSecretKey();
            $encrypted_secret = encrypt($secret);

            $qrCodeDataUri = $this->generateQrCode($this->google2fa->getQRCodeUrl(
                config('app.name'),
                $user->email,
                $secret
            ));

            $user->update([
                'twofa_secret' => $encrypted_secret,
                'twofa_qr' => $qrCodeDataUri,
            ]);
        } else {
            // Get the existing secret if already set
            $secret = decrypt($user->twofa_secret);
        }

        return view("{$this->folder}/parts/secure", [
            'qrCode' => $user->twofa_qr,
            'manualCode' => $secret // Add the plain secret key for manual entry
        ]);
    }

    private function generateQrCode(string $qrCodeUrl): string
    {
        $renderer = new ImageRenderer(
            new RendererStyle(300),
            new SvgImageBackEnd()
        );
        $writer = new Writer($renderer);
        $svg = $writer->writeString($qrCodeUrl);

        // Convert SVG to Data URI
        $dataUri = 'data:image/svg+xml;base64,' . base64_encode($svg);

        return $dataUri;
    }

    public function secure_update($request)
    {
        $user = Admin::find(Auth::id());
        $secret = decrypt($user->twofa_secret);

        if ($request->input('twofa_code') && $this->google2fa->verifyKey($secret, $request->input('twofa_code'))) {
            $user->update(['twofa_verify' => 1]);
            return redirect()->back()->with('success', trns('Two-factor authentication enabled successfully.'));
        } else {
            return redirect()->back()->with('error', trns('Invalid verification code.'));
        }
    }

    public function secure_disable($request)
    {
        $user = Admin::find(Auth::id());
        // $secret = decrypt($user->twofa_secret);

        // if ($request->input('twofa_code') && $this->google2fa->verifyKey($secret, $request->input('twofa_code'))) {
        $user->update(['twofa_verify' => 0]);
        return redirect()->back()->with('success', trns('Two-factor authentication enabled successfully.'));
        // } else {
        // return redirect()->back()->with('error', trns('Invalid verification code.'));
        // }
    }
}
