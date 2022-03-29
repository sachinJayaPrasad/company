<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employees;
use App\Models\Companies;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use Yajra\DataTables\Facades\DataTables;
use DB;

class EmployeesController extends Controller
{
    //Landing Page
    public function index(Request $request)
    {
        $data['companies'] =  Companies::select('*')->get();
        if ($request->ajax()) {
            $data['employees'] =  DB::table('employees')
                                    ->join('companies','employees.company_id','=','companies.id')
                                    ->select('companies.name as company_name','employees.*','companies.*','employees.id as emp_id');
            return DataTables::of($data['employees'])
            ->addIndexColumn()
            ->editColumn('action', function($row){
                $btn = '';
                $btn = $btn.'<a href="javascript:0;" title="edit" data-id="'.$row->emp_id.'" class="fa fa-edit btn btn-info edit-button"></a>';
                $btn = $btn.'<a href="javascript:0;" style="margin-left:2px;"  title="Delete" data-id="'.$row->emp_id.'"  class="fa fa-trash btn btn-danger openDelModal"></a>';
                return $btn;
            })
            ->filter(function ($instance) use ($request) {
                if (!empty($request->get('search'))) {
                        $instance->where(function($w) use($request){
                        $search = $request->get('search');
                        $w->orWhere('employees.first_name', 'LIKE', "%$search%");
                    });
                }
            })
            ->rawColumns(['action'])
            ->make(true);
        }
        return view('admin.employees.index',$data);
    }
    //Add and Edit in single function
    public function add_employees(Request $request)
    {
        try{
            $rules = [
                'first_name'   => 'required|max:50',
                'last_name'    => 'required|max:50',
                'company_id'   => 'required',
                'email'        => 'required|email',
                'phone'        => 'required|numeric',
            ];
            $messages = [
                'first_name.required' => 'First Name is required',
                'last_name.required'  => 'Last Name is required',
                'company_id.required' => 'Company Name is required',
                'email.required'      => 'Email is required',
                'phone.required'      => 'Phone Number is required',
            ];
            $validation = Validator::make($request->all(),$rules,$messages);
            if($validation->fails()){
                return response()->json(['status' => 0,'response' => $validation->errors()->all()]);
            }
            if($request->employee_id){
                $employee = Employees::find($request->employee_id);
                $msg = "Successfully updated employee";
            }else{
                $employee = new Employees();
                $msg = "Successfully added employee";
            }
            $employee->first_name  = $request->first_name;
            $employee->last_name   = $request->last_name;
            $employee->email       = $request->email;
            $employee->phone       = $request->phone;
            $employee->company_id  = $request->company_id;
            $employee->save();
            return response()->json(['status'=> 1,'response'=> $msg]);
        }catch(\Exception $e){
            return response()->json(['status' => 0,'response' => [$e->getMessage()]]);
        }
    }
    //Edit function
    public function edit_employee(Request $request)
    {
        if ($request->id) {
            $employee = Employees::where('id',$request->id)->first();
            return response()->json(['status' => true, 'response' => $employee]);
        } else {
            return response()->json(['status' => true, 'response' => 'Something went wrong']);
        }
    }
    //Delete function
    public function delete_employee(Request $request){
        if($request->id){
            $employee = Employees::find($request->id);
            $employee->delete();
            return response()->json(['status'=>true,'response'=>"Successfully deleted employee"]);
        }else{
            return response()->json(['status'=>false,'response'=>'Something went wrong']);
        }
    }
}
