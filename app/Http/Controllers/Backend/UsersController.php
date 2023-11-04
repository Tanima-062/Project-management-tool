<?php

namespace App\Http\Controllers\Backend;

use App\Enums\Notification\NotificationCodeEnum;
use App\Exports\UsersExport;
use App\Http\Controllers\Controller;
use App\Imports\UsersImport;
use App\Modals\Member;
use App\Modals\Unit;
use App\Modals\Program;
use Illuminate\Http\Request;
use App\Modals\User;
use App\Modals\UserProgram;
use App\Modals\Team;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use App\Services\Notification\EmailNotificationService;
use Spatie\Permission\Models\Role;
use App\Models\Notification\Notifier;
use App\Http\Controllers\Backend\TasksController;
use Illuminate\Support\Facades\Session;
use Config;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(User::hasSpecificPermission(Auth::user(),'user.view')){
            $users = User::orderBy('created_at', 'DESC')->paginate(10);
            $notifications = Notifier::getNotifier(Auth::user()['id']);
            $serial = $users->perPage() * ($users->currentPage() - 1);
            return view('backend.users.list',compact('users', 'notifications','serial'));
        }else{
            session()->flash('error', 'You are not authorized for this page!');
            return back();
        }
    }

    public function search(Request $request){
        Session::put('user.filter', $request['query']);
        if($request['query']){
            $query = $request['query'];
            $user_query = User::whereHas('userPrograms.program', function($q) use ($query){
                                   $q->where('name','like','%'.$query.'%');
                                })->orWhere('name','like','%'.$request['query'].'%')
                                ->orWhere('email','like','%'.$request['query'].'%')
                                ->orWhere('pin_number','like','%'.$request['query'].'%')
                                ->orWhere('phone_number','like','%'.$request['query'].'%')
                                ->orWhere('designation','like','%'.$request['query'].'%')
                                ->orWhere('role','like','%'.$request['query'].'%');
            if(isset($request->coloumn)){
                if($request->coloumn != 'status'){
                    $users = $users_query->orderBy($request->coloumn, $request->order)->paginate(10);

                    if(($request->coloumn == 'id') && $request->order == 'asc'){
                        $serial = $users->total();
                        if($users->currentPage() > 1){
                            $serial = $users->total() - (($users->currentPage()-1) * $users->perPage());
                        }
                        return response()->json( array('success' => true, 'view'=>view('backend.users.fetch_user',['users' => $users,'serial' => $serial, 'sort_id' => 'asc'])->render()) );
                    }
                }
                elseif($request->coloumn == status){
                    if($request->order == 'asc'){
                        $users = $user_query->orderByRaw('FIELD(status, 1,0)')->paginate(10);
                    }elseif($request->order == 'desc'){
                        $users = $user_query->orderByRaw('FIELD(status, 0,1)')->paginate(10);
                    }
                }
            }else{
                $users = $user_query->orderBy('created_at', 'DESC')->paginate(10);
            } 
            $serial = $users->perPage() * ($users->currentPage() - 1);                   
            return response()->json( array('success' => true, 'view'=>view('backend.users.fetch_user',['users'=> $users,'serial' => $serial])->render()) );
        }else{
            $users = User::orderBy('created_at', 'DESC')->paginate(10);
            if(isset($request->coloumn)){
                if($request->coloumn != 'status'){
                    $users = User::orderBy($request->coloumn, $request->order)->paginate(10);

                    if(($request->coloumn == 'id') && $request->order == 'asc'){
                        $serial = $users->total();
                        if($users->currentPage() > 1){
                            $serial = $users->total() - (($users->currentPage()-1) * $users->perPage());
                        }
                        return response()->json( array('success' => true, 'view'=>view('backend.users.fetch_user',['users' => $users,'serial' => $serial, 'sort_id' => 'asc'])->render()) );
                    }
                }
                elseif($request->coloumn == 'status'){
                    if($request->order == 'asc'){
                        $users = User::orderByRaw('FIELD(status, 1,0)')->paginate(10);
                    }elseif($request->order == 'desc'){
                        $users = User::orderByRaw('FIELD(status, 0,1)')->paginate(10);
                    }
                }
            }
            $serial = $users->perPage() * ($users->currentPage() - 1);   
            return response()->json( array('success' => true, 'view'=>view('backend.users.fetch_user',['users'=> $users, 'serial' => $serial])->render()) );
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $teams = Team::orderBy('name')->get();
        $roles = Role::orderBy('name')->get();
        $units = Unit::orderBy('name')->get();
        $programs = Program::orderBy('name')->get();
        $supervisors = User::orderBy('name')->get();
        $notifications = Notifier::getNotifier(Auth::user()['id']);
        return view('backend.users.create',compact('roles', 'units','programs','supervisors','teams', 'notifications'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:100',
            'email' => 'required|max:100|unique:users,email,NULL,id,deleted_at,NULL',
            'phone_number' => 'required|max:11|min:11',
            'designation' => 'required|max:100',
            'unit' => 'required',
            'programs' => 'required',
            'role' => 'required',
            'team' => 'required',
            'password' => 'required|min:6|confirmed',
            'image' => 'nullable| mimes:jpeg,jpg,png',
        ]);

        $taskController = new TasksController();

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->phone_number = $request->phone_number;
        $user->designation = $request->designation;
        $user->unit = $request->unit;
        if(isset($request->supervisor)){
            $user->supervisor = $request->supervisor;
        }
        $user->role = $request->role;
        $user->assignRole($request->role);
        $user->status = $request->status;

        $image = '';
        $image_name = '';
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $image_name = $image->getClientOriginalName();
            $user->image = $image_name;
        }

        $user->save();

        $user_programs = [];
        foreach($request->programs as $program){
            array_push($user_programs,['user_id' => $user->id, 'program_id' => $program]);
        }
        DB::table('user_programs')->insert($user_programs);
        $program = User::programNameConcate($user->userPrograms);

        // if(!empty($user->email)){
        //     // Email Notification

        //     $emailService = new EmailNotificationService(NotificationCodeEnum::UserCreate()->getValue(), 'email', $user->email);
        //     $emailService->replaceTempateVariable([
        //         'SiteUrl'=> Config::get('app.url'),
        //         'UserFullName'=> $user->name,
        //         'Email' => $user->email,
        //         'Designation' => $user->designation,
        //         'PhoneNo' => $user->phone_number,
        //         'Unit' => $user->unit,
        //         'Program ' => $program,
        //         'Password ' => empty($user->pin_number) ? $request->password : ''
        //         ]);
        //     $emailService->sendEmail();
        // }

        $member = new Member();
        $member->team_id = $request->team;
        $member->user_id = $user->id;
        $member->save();


        if ($request->hasFile('image')) {
            $destination_path = public_path('/backend/uploads/profile_images/' . $user->id);
            $image->move($destination_path, $image_name);
        }

        session()->flash('success','User has been created!');
        return redirect()->route('users.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if($id != Auth::user()['id']){
            session()->flash('error','You are not authorized!');
            return back();
        }
        $user = User::with('userPrograms')->where(['id' => $id])->first();
        $member = Member::where(['user_id' => $id])->first();
        $supervisor = User::where(['id' => $user->supervisor])->first();
        $notifications = Notifier::getNotifier(Auth::user()['id']);
        return view('backend.users.profile',compact('user','member','supervisor', 'notifications'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $teams = Team::orderBy('name')->get();
        $roles = Role::orderBy('name')->get();
        $user = User::find($id);
        $units = Unit::orderBy('name')->get();
        $programs = Program::orderBy('name')->get();
        $supervisors = User::orderBy('name')->get();
        $selected_team = Member::where(['user_id' => $id])->first();
        $notifications = Notifier::getNotifier(Auth::user()['id']);
        return view('backend.users.edit',compact('user','roles', 'notifications', 'units','programs','supervisors','teams','selected_team'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $request->validate([
            'name' => 'required|max:100',
            'email' => 'required|max:100|unique:users,email,'.$id.',id,deleted_at,NULL',
            'phone_number' => 'required|max:11|min:11',
            'designation' => 'required|max:100',
            'unit' => 'required',
            'programs' => 'required',
            'role' => 'required',
            'team' => 'required',
            'image' => 'nullable| mimes:jpeg,jpg,png',
            'password' => 'nullable|min:6|confirmed',
        ]);

        $user = User::find($id);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone_number = $request->phone_number;
        $user->designation = $request->designation;
        $user->unit = $request->unit;
      
        $user->supervisor = $request->supervisor;
        $user->role = $request->role;
        $user->status = $request->status;

        if(isset($request->team)){
            $member = Member::where(['user_id' => $id])->first();
            if($member){
                $member->team_id = $request->team;
                $member->save();
            }else{
                $member = new Member();
                $member->team_id = $request->team;
                $member->user_id = $id;
                $member->save();
            }
        }

        
        if($request->password) {
            $user->password = Hash::make($request->password);
        }
        
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $name = $image->getClientOriginalName();
            $destination_path = public_path('backend/uploads/profile_images/'.$user->id);
            $image->move($destination_path, $name);
            $user->image = $name;
        }
        $user->save();

        $user_programs = [];
        foreach($request->programs as $program){
            array_push($user_programs,['user_id' => $user->id, 'program_id' => $program]);
        }

        UserProgram::where(['user_id' => $user->id])->delete();
        DB::table('user_programs')->insert($user_programs);

        $user->roles()->detach();
        $user->assignRole($request->role);

        session()->flash('success','User has been updated!');
        return redirect()->route('users.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::find($id);
        if(!is_null($user)){
            $user->delete();
        }
        session()->flash('success','User has been deleted!');
        return 'deleted';
    }
    public function export(){
        return Excel::download(new UsersExport, 'users.xlsx');
    }

    public function changePassword($id)
    {
        if($id != Auth::user()['id']){
            session()->flash('error','You are not authorized!');
            return back();
        }
        $user = User::where(['id' => $id])->first();
        $notifications = Notifier::getNotifier(Auth::user()['id']);
        return view('backend.users.change_password',compact('user', 'notifications'));
    }

    public function updatePassword(Request $request, $id)
    {
        if($id != Auth::user()['id']){
            session()->flash('error','You are not authorized!');
            return back();
        }
        $user = Auth::user();
        $request->validate([
            'password' => 'nullable|min:6|confirmed',
            'image' => 'nullable| mimes:jpeg,jpg,png',
        ]);

        if($request->password) {
            $user->password = Hash::make($request->password);
        }
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $name = $image->getClientOriginalName();
            $destination_path = public_path('backend/uploads/profile_images/'.$user->id);
            $image->move($destination_path, $name);
            $user->image = $name;
        }
        $user->save();
        session()->flash('success','Profile has been updated successfully!');
        return redirect()->route('users.show',$id);
    }

    public function showBulkUpload(){
        $notifications = Notifier::getNotifier(Auth::user()['id']);
        return view('backend.users.bulk_upload', compact('notifications'));
    }

    public function import(Request $request){
        $request->validate([
            'select_file' => 'required|mimes:xlsx'
        ]);

        $data = Excel::toArray(new UsersImport(), $request->select_file);
        $user_emails = User::whereNotNull('email')->groupBy('email')->pluck('email')->toArray();
        $insert_data = [];
        $failed_data = 0;
        if ($data) {
            foreach ($data as $value) {
                foreach (array_slice($value,1) as $row) {
                    if( isset($row[0]) && isset($row[1]) && isset($row[2]) && isset($row[3]) && isset($row[4])
                        && isset($row[6]) && isset($row[7]) && isset($row[8]) ){
                        if(!in_array($row[2], $user_emails)){
                            $superVisor = User::where(['name' => ucwords($row[5])])->first();        
                            $programs = explode(",", $row[7]);
                            $programIds = [];
                            foreach($programs as $single_program){
                                $program = Program::where(['name' => ucwords($single_program)])->first();
                                if(empty($program)){
                                    $program = new Program();
                                    $program->name = ucwords($single_program);
                                    $program->save();
                                }
                                array_push($programIds,$program->id);
                            }
                            $unit = Unit::where(['name' => ucwords($row[6])])->first();
                            if(empty($unit)){
                                $unit = new Unit();
                                $unit->name = ucwords($row[6]);
                                $unit->save();
                            }
                            $password = Hash::make('123456');
                            $team = Team::where(['name' => ucwords($row[8])])->first();
                            $role = Role::where(['name' => ucwords($row[4])])->first();
                            if(!empty($team) && !empty($role)){
                                $insert_data[] = array(
                                    'name' => $row[0],
                                    'email' => $row[2],
                                    'phone_number' => $row[3],
                                    'designation' => $row[1],
                                    'unit' => $unit->name,
                                    'supervisor' => $superVisor->id,
                                    'role' => $role->name,
                                    'status' => 1,
                                    'password' => $password,
                                    'created_at' => new \DateTime(),
                                    'updated_at' => new \DateTime()
                                );
                                $user = new User();
                                $user->name = $row[0];
                                $user->email = $row[2];
                                $user->phone_number = $row[3];
                                $user->designation = $row[1];
                                $user->unit = $unit->name;
                                $user->supervisor = isset($superVisor->id) ? $superVisor->id : null;
                                $user->role = $role->name;
                                $user->status = 1;
                                $user->password = $password;
                                $user->save();

                                $member = new Member();
                                $member->user_id = $user->id;
                                $member->team_id = $team->id;
                                $member->save();

                                $user_programs = [];
                                foreach($programIds as $program_id){
                                    array_push($user_programs,['user_id' => $user->id, 'program_id' => $program_id]);
                                }

                                DB::table('user_programs')->insert($user_programs);


                            }else{
                                $failed_data++;
                            }
                        }else{
                            $failed_data++;
                        }
                    }else{
                        $failed_data++;
                        session()->flash('error','Something is wrong! Please give all informations!');
                        return back();
                    }
                }
            }
            if(count($insert_data) > 0){
                $message = count($insert_data).' data saved successfully. '.$failed_data.' data failed.';
                if(count($insert_data) > 0){
                    session()->flash('success', $message);
                }else{
                    session()->flash('success', 'File process completed successfully. No data saved.');
                }
                return redirect()->route('users.index');
            }else{
                session()->flash('error','Something is wrong!');
                return back();
            }
        }
    }

    public function downloadExcelTemplate(){
        $path = public_path('backend'.DIRECTORY_SEPARATOR.'downloads'.DIRECTORY_SEPARATOR.'template.xlsx');
        return response()->download($path);
    }
}
