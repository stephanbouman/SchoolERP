<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use App\Models\StudentAdmission;
use App\Models\StudentPromotion;
use App\Models\TransportRoute;
use App\Models\User;
use App\Models\UserInformation;
use Illuminate\Http\Request;
use App\Datatables\UserExporter;
use Illuminate\Routing\Redirector;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\RedirectResponse;
use Illuminate\Contracts\View\Factory;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Contracts\Foundation\Application;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request) : Application|Factory|View
    {

        if (! Auth()->user()->can('user_access')) {
            abort(403, "You don't have permission!");
        }

        if (! $request->ajax()) {
            return view('user.index');
        }

        $users = DB::table('users as u')
            ->leftJoin('model_has_roles as mhr', 'u.id', 'mhr.model_id')
            ->leftJoin('roles as r', 'mhr.role_id', 'r.id')
            ->leftJoin('student_admissions as sa', 'u.id', 'sa.user_id')
            ->leftJoin('student_classes as sc', 'sa.current_class_id', 'sc.id')
            ->leftJoin('student_sections as ss', 'sa.current_section_id', 'ss.id')
            ->leftJoin('transport_routes as tr', 'u.transport_id', 'tr.id')
            ->leftJoin('transport_vehicles as tv', 'tr.vehicle_id', 'tv.id')
            ->leftJoin('transport_types as tt', 'tv.transport_type_id', 'tt.id')
            ->leftJoin('users as ucn', 'u.created_by', 'ucn.id')
            ->leftJoin('users as uun', 'u.updated_by', 'uun.id')
            ->whereNotIn('r.name', ['Super Admin', 'STUDENT'])
            ->select('u.id', 'u.title', 'u.first_name', 'u.middle_name', 'u.last_name', 'u.contact_number', 'u.contact_number2', 'u.address_line1', 'u.city', 'u.state', 'u.pin_code', 'u.country', 'u.aadhaar_number', 'u.mother_tongue', DB::raw("date(u.date_of_birth) as date_of_birth"), 'u.gender', 'u.father_name', 'u.mother_name', 'u.remarks', 'u.status', 'u.email', 'u.email_alternate', 'u.created_at', 'u.updated_at', 'r.name as role_name', 'tr.route_name', 'sc.name as class_name', 'ss.name as section_name', 'tt.name as transport_type_name', DB::raw("concat(ucn.first_name, ' ', ucn.middle_name, ' ', ucn.last_name) as created_by_name"), DB::raw("concat(uun.first_name, ' ', uun.middle_name, ' ', uun.last_name) as updated_by_name"))
            ->get();

        $datatables = UserExporter::index($users);

        $allGenders = ["Male","Female","Other"];
        $allRoles = Role::distinct('name')->pluck('name');
        $allStatus = ["Active","Inactive"];

        $datatables->with([
            'allGenders' => $allGenders,
            'allRoles' => $allRoles,
            'allStatus' => $allStatus,
        ]);

        return $datatables->make();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View
     */
    public function create()
    {
        if (! Auth()->user()->can('user_create')) {
            abort(403, "You don't have permission!");
        }

        $roles = Role::all()
            ->whereNotIn('name', 'Super Admin')
            ->whereNotIn('name', 'STUDENT');

        return view('user.create', [
            'roles' => $roles,
            'routes' => TransportRoute::all()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Application|Factory|View
     */
    public function store(Request $request)
    {
        if ($this->userExists($request)) {
            abort(422, 'User already exists!');
        }

        if ($this->emailExists($request)) {
            abort(422, 'Email id already exists');
        }

        $user = User::create([
            'title' => strtoupper($request->title),
            'first_name' => strtoupper($request->first_name),
            'middle_name' => strtoupper($request->middle_name),
            'last_name' => strtoupper($request->last_name),
            'contact_number' => strtoupper($request->contact_number),
            'contact_number2' => strtoupper($request->contact_number2),
            'address_line1' => strtoupper($request->address_line1),
            'city' => strtoupper($request->city),
            'state' => strtoupper($request->state),
            'pin_code' => strtoupper($request->pin_code),
            'country' => strtoupper($request->country),
            'transport_id' => strtoupper($request->transport_id),
            'aadhaar_number' => strtoupper($request->aadhaar_number),
            'blood_group' => strtoupper($request->blood_group),
            'mother_tongue' => strtoupper($request->mother_tongue),
            'date_of_birth' => $request->date_of_birth,
            'place_of_birth' => strtoupper($request->place_of_birth),
            'gender' => strtoupper($request->gender),
            'father_name' => strtoupper($request->father_name),
            'mother_name' => strtoupper($request->mother_name),
            'remarks' => strtoupper($request->remarks),
            'termination_date' => $request->termination_date,
            'status' => strtoupper($request->status),
            'email' => strtolower($request->email),
            'password' => '12345678',
            'created_by' => strtoupper(Auth()->user()->id),
            'updated_by' => strtoupper(Auth()->user()->id),
        ])->syncRoles($request->role);

        $user->user_information()->create([
            'user_id' => $user->id,
            'joining_date' => $request->joining_date,
            'allocated_casual_leave' => $request->allocated_casual_leave,
            'allocated_sick_leave' => $request->allocated_sick_leave,
            'pf_number' => $request->pf_number,
            'esi_number' => $request->esi_number,
            'bank_account_number' => $request->bank_account_number,
            'ifsc_code' => $request->ifsc_code,
            'un_number' => $request->un_number,
            'pan_number' => $request->pan_number,
            'travel_allowance' => $request->travel_allowance,
            'gross_salary' => $request->gross_salary,
            'basic_salary' => $request->basic_salary,
            'grade_salary' => $request->grade_salary,
            'pf' => $request->pf,
        ]);

        return view('user.index')->with([
            "status" => "success",
            "message" => "User successfully created | User ID: " . $user->id
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        if (Auth()->user()->can('user_show')) {
            if (User::find($id) && !User::find($id)->hasAnyRole('STUDENT', 'Super Admin')) {
                $user = DB::table('users as u')
                    ->leftJoin('model_has_roles as mhr', 'u.id', 'mhr.model_id')
                    ->leftJoin('roles as r', 'mhr.role_id', 'r.id')
                    ->leftJoin('user_information as ui', 'u.id', 'ui.user_id')
                    ->leftJoin('student_admissions as sa', 'u.id', 'sa.user_id')
                    ->leftJoin('student_classes as sc', 'sa.current_class_id', 'sc.id')
                    ->leftJoin('student_sections as ss', 'sa.current_section_id', 'ss.id')
                    ->leftJoin('transport_routes as tr', 'u.transport_id', 'tr.id')
                    ->leftJoin('transport_vehicles as tv', 'tr.vehicle_id', 'tv.id')
                    ->leftJoin('transport_types as tt', 'tv.transport_type_id', 'tt.id')
                    ->whereNotIn('r.name', ['Super Admin'])
                    ->where('u.id', $id)
                    ->select('u.id', 'u.title', 'u.first_name', 'u.middle_name', 'u.last_name', 'u.contact_number', 'u.contact_number2', 'u.address_line1', 'u.city', 'u.state', 'u.pin_code', 'u.country', 'tr.route_name as transport_route_name', 'u.aadhaar_number', 'u.blood_group', 'u.mother_tongue', 'u.date_of_birth', 'u.place_of_birth', 'u.gender', 'u.father_name', 'u.mother_name', 'u.remarks', 'u.status', 'u.email', 'u.email_alternate', 'u.created_at', 'u.updated_at', 'r.name as role_name', 'tr.route_name', 'sc.name as class_name', 'ss.name as section_name', 'ui.joining_date', 'ui.termination_date', 'ui.allocated_casual_leave', 'ui.allocated_sick_leave', 'ui.pf_number', 'ui.esi_number', 'ui.bank_account_number', 'ui.ifsc_code', 'ui.un_number', 'ui.pan_number', 'ui.travel_allowance', 'ui.gross_salary', 'ui.basic_salary', 'ui.grade_salary', 'ui.salary_review_date', 'ui.pf')
                    ->get();

                $admissions = StudentAdmission::all();
                $routes = TransportRoute::all();
                $roles = Role::all()
                    ->whereNotIn('name', 'Super Admin')
                    ->whereNotIn('name', 'STUDENT');
                return view('user.show')->with(['user' => $user[0], 'roles' => $roles, 'routes' => $routes, 'admissions' => $admissions]);
            } elseif (User::find($id) && User::find($id)->hasAnyRole('STUDENT')) {
                return redirect(route('student.show', $id));
            } else return abort(404);
        } else return abort(403, "You don't have permission!");
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param User $user
     * @return Application|RedirectResponse|Redirector
     */
    public function edit(User $user)
    {
        if (! Auth()->user()->can('user_edit')) {
            abort(403, "You don't have permission!");
        }

        return redirect(route('index'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param User $user
     * @return RedirectResponse
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        if (! $user->hasRole('Super Admin')) {
            abort(404);
        }

        $user->title = strtoupper($request->title);
        $user->first_name = strtoupper($request->first_name);
        $user->middle_name = strtoupper($request->middle_name);
        $user->last_name = strtoupper($request->last_name);
        $user->contact_number = strtoupper($request->contact_number);
        $user->contact_number2 = strtoupper($request->contact_number2);
        $user->address_line1 = strtoupper($request->address_line1);
        $user->city = strtoupper($request->city);
        $user->state = strtoupper($request->state);
        $user->pin_code = strtoupper($request->pin_code);
        $user->country = strtoupper($request->country);
        $user->transport_id = strtoupper($request->transport_id);
        $user->aadhaar_number = strtoupper($request->aadhaar_number);
        $user->blood_group = strtoupper($request->blood_group);
        $user->mother_tongue = strtoupper($request->mother_tongue);
        $user->date_of_birth = $request->date_of_birth;
        $user->place_of_birth = strtoupper($request->place_of_birth);
        $user->gender = strtoupper($request->gender);
        $user->father_name = strtoupper($request->father_name);
        $user->mother_name = strtoupper($request->mother_name);
        $user->remarks = strtoupper($request->remarks);
        $user->termination_date = $request->termination_date;
        $user->status = strtoupper($request->status);
        $user->email = strtolower($request->email);
        $user->updated_by = Auth()->user()->id;
        $user->save();

        $user->syncRoles($request->role);

        return Redirect::route('user.show', $user)
            ->with([
                "status" => "success",
                "message" => "Success"
            ]);
    }

    public function informationUpdate(Request $request, $id)
    {
        if (Auth()->user()->can('user_create')) {
            $request->validate([
                'joining_date' => 'required|date',
                'allocated_casual_leave' => 'required|integer',
                'allocated_sick_leave' => 'required|integer',
                'pf_number' => 'nullable|integer',
                'esi_number' => 'nullable|integer',
                'bank_account_number' => 'nullable|integer',
                'ifsc_code' => 'nullable',
                'un_number' => 'nullable|integer',
                'pan_number' => 'nullable',
                'travel_allowance' => 'nullable|integer',
                'gross_salary' => 'nullable|integer',
                'basic_salary' => 'nullable|integer',
                'grade_salary' => 'nullable|integer',
                'pf' => 'required',
            ]);
            if (UserInformation::where('user_id', $id)
                ->exists()) {

                    $user_information = UserInformation::where('user_id', $id)->firstOrFail();

                    $user_information->joining_date = $request->joining_date;
                    $user_information->allocated_casual_leave = $request->allocated_casual_leave;
                    $user_information->allocated_sick_leave = $request->allocated_sick_leave;
                    $user_information->pf_number = $request->pf_number;
                    $user_information->esi_number = $request->esi_number;
                    $user_information->bank_account_number = $request->bank_account_number;
                    $user_information->ifsc_code = $request->ifsc_code;
                    $user_information->un_number = $request->un_number;
                    $user_information->pan_number = $request->pan_number;
                    $user_information->travel_allowance = $request->travel_allowance;
                    $user_information->gross_salary = $request->gross_salary;
                    $user_information->basic_salary = $request->basic_salary;
                    $user_information->grade_salary = $request->grade_salary;
                    $user_information->pf = $request->pf;
                    $user_information->save();

                    return view('user.index')->with(["status" => "success", "message" => "User successfully updated | User ID: " . $id]);

            } elseif (UserInformation::where('user_id', $id)
            ->doesntExist()) {

                $user_information = UserInformation::create([
                    'user_id' => $id,
                    'joining_date' => $request->joining_date,
                    'allocated_casual_leave' => $request->allocated_casual_leave,
                    'allocated_sick_leave' => $request->allocated_sick_leave,
                    'pf_number' => $request->pf_number,
                    'esi_number' => $request->esi_number,
                    'bank_account_number' => $request->bank_account_number,
                    'ifsc_code' => $request->ifsc_code,
                    'un_number' => $request->un_number,
                    'pan_number' => $request->pan_number,
                    'travel_allowance' => $request->travel_allowance,
                    'gross_salary' => $request->gross_salary,
                    'basic_salary' => $request->basic_salary,
                    'grade_salary' => $request->grade_salary,
                    'pf' => $request->pf,
                ]);

                return view('user.index')->with(["status" => "success", "message" => "User successfully created | User ID: " . $id]);
            }
        } else return abort(403, "You don't have permission!");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        return abort(403, "You don't have permission!");
    }

    // Profile
    public function profile()
    {
        return view('profile')->with(['user' => Auth()->user()]);
    }

    protected function userExists(Request $request)
    {
        return User::where('first_name', $request->first_name)
            ->where('contact_number', $request->contact_number)
            ->exists();
    }

    protected function emailExists(Request $request): bool
    {
        return User::where('email', $request->email)->exists() && $request->email != '';
    }
}
