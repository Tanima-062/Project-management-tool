<?php

namespace App\Exports;

use App\Modals\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;

class UsersExport implements FromCollection, WithMapping, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        if(Session::has('user.filter')){
            $query = Session::get('user.filter');
            return User::whereHas('userPrograms.program', function($q) use ($query){
                                    $q->where('name','like','%'.$query.'%');
                                })->orWhere('name','like','%'.$query.'%')
                                ->orWhere('email','like','%'.$query.'%')
                                ->orWhere('pin_number','like','%'.$query.'%')
                                ->orWhere('phone_number','like','%'.$query.'%')
                                ->orWhere('designation','like','%'.$query.'%')
                                ->orWhere('role','like','%'.$query.'%')
                                ->orderBy('created_at', 'DESC')->get();
        }else{
            return User::all();
        }
    }

    public function headings(): array
    {
        return [
            'Name',
            'Email',
            'PIN Number',
            'Phone Number',
            'Designation',
            'Unit',
            'Program',
            'SuperVisor',
            'Role',
            'Status',
            'Created At'
        ] ;
    }

    public function map($row): array
    {
        return [
            $row->name,
            $row->email,
            $row->pin_number,
            $row->phone_number,
            $row->designation,
            $row->unit,
            $row->program,
            $row->supervisor,
            $row->role,
            $row->status,
            Carbon::parse($row->created_at)->toFormattedDateString()
        ];
    }
    public function prepareRows($rows): array
    {
        return array_map(function ($user) {
            if($user->status == '1'){
                $user->status = 'Active';
            }
            if($user->status == '0'){
                $user->status = 'Inactive';
            }

            $supervisor = User::where(['id' => $user->supervisor])->first();
            if(isset($supervisor)){
                $user->supervisor = $supervisor->name;
            }
            $programs = '';
            foreach($user->userPrograms as $key=>$user_program){
                if($key == count($user->userPrograms)-1){
                    $programs .= $user_program->program->name;
                }else{
                    $programs .= $user_program->program->name . ', ';
                }
            }
            $user->program = $programs;
            return $user;
        }, $rows);
    }
}
