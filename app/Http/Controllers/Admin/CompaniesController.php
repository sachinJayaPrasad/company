<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Companies;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use Yajra\DataTables\Facades\DataTables;

class CompaniesController extends Controller
{
    //Landing Page
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data =  Companies::select('*');
            return DataTables::of($data)
            ->addIndexColumn()
            ->editColumn('logo', function($row){
                        return '<img src="'.$row->logo.'" style="width:50px;height:50px;">';
                   })
            ->editColumn('action', function($row){
                $btn = '';
                $btn = $btn.'<a href="javascript:0;" title="edit" data-id="'.$row->id.'" class="fa fa-edit btn btn-info edit-button"></a>';
                $btn = $btn.'<a href="javascript:0;" style="margin-left:2px;"  title="Delete" data-id="'.$row->id.'"  class="fa fa-trash btn btn-danger openDelModal"></a>';
                return $btn;
            })
            ->filter(function ($instance) use ($request) {
                if (!empty($request->get('search'))) {
                        $instance->where(function($w) use($request){
                        $search = $request->get('search');
                        $w->orWhere('name', 'LIKE', "%$search%");
                    });
                }
            })
            ->rawColumns(['logo','action'])
            ->make(true);
        }
        return view('admin.companies.index');
    }
    //Add and Edit in single function
    public function add_companies(Request $request)
    {
        try{
            $rules = [
                'name'         => 'required|max:50',
                'website_link' => 'required',
            ];
            $messages = [
                'name.required'          => 'Company Name is required',
                'website_link.required'  => 'Please provide aWebsite Link',
            ];
            $validation = Validator::make($request->all(),$rules,$messages);
            if($validation->fails()){
                return response()->json(['status' => 0,'response' => $validation->errors()->all()]);
            }
            if($request->company_id){
                $rules['file'] = 'nullable|mimes:jpeg,jpg,png';
                $companies = Companies::find($request->company_id);
                $msg = "Successfully updated company";
            }else{
                $rules['file'] = 'required|mimes:jpeg,jpg,png';
                $messages['file.required'] = 'Image is required';
                $companies = new Companies();
                $msg = "Successfully added Company";
            }
            if($request->file){
                if(File::exists(public_path().$request->current_image)){
                    File::delete(public_path().$request->current_image);
                }
                $image = $request->file;
                $imagename = '/uploads/companies/'.time().rand(1,100).'.'.$image->getClientOriginalExtension();
                $image->move(public_path('/uploads/companies'),$imagename);
                $companies->logo = $imagename;
            }
            $companies->name     = $request->name;
            $companies->website  = $request->website_link;
            $companies->save();
            return response()->json(['status'=> 1,'response'=> $msg]);
        }catch(\Exception $e){
            return response()->json(['status' => 0,'response' => [$e->getMessage()]]);
        }
    }
    //Edit function
    public function edit_company($id)
    {
        if ($id) {
            $company = Companies::find($id);
            return response()->json(['status' => true, 'response' => $company]);
        } else {
            return response()->json(['status' => true, 'response' => 'Something went wrong']);
        }
    }
    //Delete function
    public function delete_company(Request $request){
        if($request->id){
            $company = Companies::find($request->id);
            $company->delete();
            return response()->json(['status'=>true,'response'=>"Successfully deleted company"]);
        }else{
            return response()->json(['status'=>false,'response'=>'Something went wrong']);
        }
    }
}
