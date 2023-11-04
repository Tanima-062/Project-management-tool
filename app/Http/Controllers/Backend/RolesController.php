<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Imports\RolesImport;
use App\Modals\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use  Maatwebsite\Excel\Facades\Excel;
use App\Exports\RolesExport;
use App\Models\Notification\Notifier;
use Illuminate\Support\Facades\Auth;

class RolesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roles = Role::all();
        $notifications = Notifier::getNotifier(Auth::user()['id']);
        return view('backend.roles.list',compact('roles', 'notifications'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $permissions = Permission::all();
        $permission_groups = User::getPermissionGroups();
        $notifications = Notifier::getNotifier(Auth::user()['id']);
        return view('backend.roles.create',compact('permissions','permission_groups', 'notifications'));
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
            'name' => 'required|max:100|unique:roles'
        ],[
            'name.required' => 'Please enter a role name'
        ]);
        $role = Role::create(['name' => $request->name, 'status' => $request->status]);
        $permissions = $request->input('permissions');
        if(!empty($permissions)){
            $role->syncPermissions($permissions);
        }
        session()->flash('success','Role has been created!');
        return redirect()->route('roles.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $role = Role::findById($id);
        $permissions = Permission::all();
        $permission_groups = User::getPermissionGroups();
        $notifications = Notifier::getNotifier(Auth::user()['id']);
        return view('backend.roles.edit',compact('role','permissions','permission_groups', 'notifications'));
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
            'name' => 'required|max:100|unique:roles,name,' .$id
        ],[
            'name.required' => 'Please enter a role name'
        ]);
        $role = Role::findById($id);
        $permissions = $request->input('permissions');

        $role->name = $request->name;
        $role->status = $request->status;
        $role->save();
        $role->syncPermissions($permissions);
        
        session()->flash('success','Role has been updated!');
        return redirect()->to(route('roles.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $role = Role::findById($id);
        if(!is_null($role)){
            $role->delete();
        }
        session()->flash('success','Role has been deleted!');
        return 'deleted';
    }
    public function export(){
        return Excel::download(new RolesExport, 'roles.xlsx');
    }
}
