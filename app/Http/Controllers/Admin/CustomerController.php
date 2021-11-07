<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\account_detail;
use App\Models\attachments;
use App\Models\customer_address;
use App\Models\notes;
use App\Models\other_contact;
use App\Models\pricing_plans;
use App\Models\primary_contact;
use App\Models\secondary_contact;
use App\Models\sites;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CustomerController extends Controller
{

    public function index()
    {
        $title = 'Customers';
        return view('admin.customers.index',compact('title'));
    }


    public function getCustomers(Request $request){
        $columns = array(
            0 => 'id',
            1 => 'name',
            2 => 'plan',
            3 => 'active',
            4 => 'created_at',
            5 => 'action'
        );

        $totalData = User::where('is_admin',0)->count();
        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if(empty($request->input('search.value'))){
            $users = User::where('is_admin',0)->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get();
            $totalFiltered = User::where('is_admin',0)->count();
        }else{
            $search = $request->input('search.value');
            $users = User::where([
                ['is_admin',0],
                ['name', 'like', "%{$search}%"]
            ])->orwhere([
                ['is_admin',0],
                ['plan', 'like', "%{$search}%"],
            ])->orwhere([
                ['is_admin',0],
                ['created_at', 'like', "%{$search}%"],
            ])->orWhereHas('account_detail', function ($query) use ($search) {
                $query->where('payment_terms', 'like', '%'.$search.'%');
            })->orWhereHas('address', function ($query) use ($search) {
                $query->where('p_suburb', 'like', '%'.$search.'%');
            })
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
            $totalFiltered = User::where([
                ['is_admin',0],
                ['name', 'like', "%{$search}%"],
            ])->where([
                ['is_admin',0],
            ])->where([
                ['is_admin',0],
                ['plan', 'like', "%{$search}%"],
            ])->where([
                ['is_admin',0],
                ['created_at', 'like', "%{$search}%"],
            ])->orWhereHas('account_detail', function ($query) use ($search) {
                $query->where('payment_terms', 'like', '%'.$search.'%');
            })->orWhereHas('address', function ($query) use ($search) {
                $query->where('p_suburb', 'like', '%'.$search.'%');
            })
                ->count();
        }


        $data = array();

        if($users){
            foreach($users as $r){
                $edit_url = route('customers.edit',$r->id);
                $nestedData['id'] = '<td><label class="checkbox checkbox-outline checkbox-success"><input type="checkbox" name="clients[]" value="'.$r->id.'"><span></span></label></td>';
                $nestedData['name'] = $r->name;
                $nestedData['plan'] = $r->plan;
                if(count($r->account_detail) > 0){
                    $nestedData['payment_term'] = $r->account_detail[0]['payment_terms'];
                }else{
                    $nestedData['payment_term'] = '-';
                }

                if(count($r->address) > 0){
                    $nestedData['suburb'] = $r->address[0]['p_suburb'];
                }else{
                    $nestedData['suburb'] = '-';
                }

                if($r->active){
                    $nestedData['active'] = '<span class="label label-success label-inline mr-2">Active</span>';
                }else{
                    $nestedData['active'] = '<span class="label label-danger label-inline mr-2">Inactive</span>';
                }

                $nestedData['created_at'] = date('d-m-Y',strtotime($r->created_at));
                $nestedData['action'] = '
                                <div>
                                <td>
                                    <a class="btn btn-sm btn-clean btn-icon" onclick="event.preventDefault();viewInfo('.$r->id.');" title="View Customer" href="javascript:void(0)">
                                        <i class="icon-1x text-dark-50 flaticon-eye"></i>
                                    </a>
                                    <a title="Edit customer" class="btn btn-sm btn-clean btn-icon"
                                       href="'.$edit_url.'">
                                       <i class="icon-1x text-dark-50 flaticon-edit"></i>
                                    </a>
                                    <a class="btn btn-sm btn-clean btn-icon" onclick="event.preventDefault();del('.$r->id.');" title="Delete Customer" href="javascript:void(0)">
                                        <i class="icon-1x text-dark-50 flaticon-delete"></i>
                                    </a>
                                </td>
                                </div>
                            ';
                $data[] = $nestedData;
            }
        }

        $json_data = array(
            "draw"			=> intval($request->input('draw')),
            "recordsTotal"	=> intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data"			=> $data
        );

        echo json_encode($json_data);

    }
    public function create()
    {
        $title = 'Add New Customer';
        $all_plans = pricing_plans::all();
        return view('admin.customers.create',['title'=>$title,'plans'=>$all_plans]);

    }

    public function store(Request $request)
    {
        $title = 'Edit customer';
        $this->validate($request, [
            'name' => 'required|max:255',
            'plan' => 'required|max:255'
        ]);

        $input = $request->all();
        $user = new User();
        $res = array_key_exists('active', $input);
        if ($res == false) {
            $user->active = 0;
        } else {
            $user->active = 1;

        }

        $user->name = $input['name'];
        $user->plan = $input['plan'];
        $user->save();

        //Session::flash('success_message', 'Great! Customer has been saved successfully!');
        $user->save();

        $all_plans = pricing_plans::all();

        if($user){
            return view('admin.customers.edit',['user'=>$user,'plans'=>$all_plans,'title'=>$title]);
        }
    }


    public function show($id)
    {
        $user = User::find($id);
        return view('admin.customers.single', ['title' => 'Customer detail', 'user' => $user]);
    }

    public function customerDetail(Request $request)
    {

        $user = User::findOrFail($request->id);


        return view('admin.customers.detail', ['title' => 'Customer Detail', 'user' => $user]);
    }

    public function edit($id)
    {
        $user = User::where('id',$id)
            ->with('attachments')
            ->with('notes')
            ->with('sites')
            ->with('secondary_contact')
            ->with('primary_contact')
            ->with('other_contact')
            ->with('address')
            ->with('account_detail')->first();
        $all_plans = pricing_plans::all();
        $title = "Edit customer details";
        return view('admin.customers.edit',['user'=>$user,'plans'=>$all_plans,'title'=>$title]);
    }


    public function update(Request $request, $id)
    {
        $user = User::find($id);
        $this->validate($request, [
            'name' => 'required|max:255',
            'email' => 'required|unique:users,email,'.$user->id,
        ]);
        $input = $request->all();

        $user->name = $input['name'];
        $user->email = $input['email'];
        $res = array_key_exists('active', $input);
        if ($res == false) {
            $user->active = 0;
        } else {
            $user->active = 1;

        }
        if(!empty($input['password'])) {
            $user->password = bcrypt($input['password']);
        }

        $user->save();

        Session::flash('success_message', 'Great! customer successfully updated!');
        return redirect()->back();
    }


    public function destroy($id)
    {
        $user = User::find($id);
        if($user->is_admin == 0){
            $user->delete();
            Session::flash('success_message', 'User successfully deleted!');
        }
        return redirect()->route('customers.index');

    }
    public function deleteSelectedCustomers(Request $request)
    {
        $input = $request->all();
        $this->validate($request, [
            'clients' => 'required',

        ]);
        foreach ($input['clients'] as $index => $id) {

            $user = User::find($id);
            if($user->is_admin == 0){
                $user->delete();
            }

        }
        Session::flash('success_message', 'customer successfully deleted!');
        return redirect()->back();

    }


    public function store_customer_contacts(Request $request){
        $user = User::find($request['c_id']);
        $user->name = $request['c_name'];
        $user->plan = $request['c_plan'];
        $user->active = $request['c_active'];
        $user->save();
        if($user){
            $customer_other_contact = new other_contact();
            $customer_other_contact->customer_id = $request['c_id'];
            $customer_other_contact->contact_name = $request['o_contact_name'];
            $customer_other_contact->position = $request['o_position'];
            $customer_other_contact->mobile = $request['o_mobile'];
            $customer_other_contact->office_phone = $request['o_office_phone'];
            $customer_other_contact->fax = $request['o_fax'];
            $customer_other_contact->email = $request['o_email'];
            $customer_other_contact->save();

            $customer_primary_contact = new primary_contact();
            $customer_primary_contact->customer_id = $request['c_id'];
            $customer_primary_contact->contact_name = $request['p_contact_name'];
            $customer_primary_contact->position = $request['p_position'];
            $customer_primary_contact->mobile = $request['p_mobile'];
            $customer_primary_contact->office_phone = $request['p_office_phone'];
            $customer_primary_contact->fax = $request['p_fax'];
            $customer_primary_contact->email = $request['p_email'];
            $customer_primary_contact->save();

            $customer_secondary_contact = new secondary_contact();
            $customer_secondary_contact->customer_id = $request['c_id'];
            $customer_secondary_contact->contact_name = $request['s_contact_name'];
            $customer_secondary_contact->position = $request['s_position'];
            $customer_secondary_contact->mobile = $request['s_mobile'];
            $customer_secondary_contact->office_phone = $request['s_office_phone'];
            $customer_secondary_contact->fax = $request['s_fax'];
            $customer_secondary_contact->email = $request['s_email'];
            $customer_secondary_contact->save();

            Session::flash('success_message', 'Great! Customer has been updated successfully!');
            return redirect()->back();

        }

    }

    public function store_customer_address(Request $request){

        $user = User::find($request['c_id']);
        $user->name = $request['c_name'];
        $user->plan = $request['c_plan'];
        $user->active = $request['c_active'];
        $user->save();
        if($user){
            $customer_address = new customer_address();
            $customer_address->customer_id = $request['c_id'];
            $customer_address->p_address_line_1 = $request['p_address_line_1'];
            $customer_address->p_address_line_2 = $request['p_address_line_2'];
            $customer_address->p_suburb = $request['p_suburb'];
            $customer_address->p_postal_code = $request['p_postal_code'];
            $customer_address->p_state = $request['p_state'];
            $customer_address->p_opening_time = $request['p_opening_time'];

            $customer_address->b_address_line_1 = $request['b_address_line_1'];
            $customer_address->b_address_line_2 = $request['b_address_line_2'];
            $customer_address->b_suburb = $request['b_suburb'];
            $customer_address->b_postal_code = $request['b_postal_code'];
            $customer_address->b_state = $request['b_state'];
            $customer_address->b_opening_time = $request['b_opening_time'];

            $customer_address->r_address_line_1 = $request['r_address_line_1'];
            $customer_address->r_address_line_2 = $request['r_address_line_2'];
            $customer_address->r_suburb = $request['r_suburb'];
            $customer_address->r_postal_code = $request['r_postal_code'];
            $customer_address->r_state = $request['r_state'];
            $customer_address->r_opening_time = $request['r_opening_time'];

            $customer_address->save();

            Session::flash('success_message', 'Great! Customer has been updated successfully!');
            return redirect()->back();
        }
    }

    public function store_account_detail(Request $request){
        $user = User::find($request['c_id']);
        $user->name = $request['c_name'];
        $user->plan = $request['c_plan'];
        $user->active = $request['c_active'];
        $user->save();
        if($user){
            $account_detail = new account_detail();
            $account_detail->customer_id = $request['c_id'];
            $account_detail->business_name = $request['business_name'];
            $account_detail->trading_name = $request['trading_name'];
            $account_detail->account_manager = $request['account_manager'];
            $account_detail->account_status = $request['account_status'];
            $account_detail->account_code = $request['account_code'];
            $account_detail->industry = $request['industry'];
            $account_detail->ABN = $request['abn'];
            $account_detail->ACN = $request['acn'];
            $account_detail->payment_terms = $request['payment_term'];
            $account_detail->credit_limit = $request['billing_method'];
            $account_detail->billing_method = $request['c_id'];
            $account_detail->review_date = $request['Review_date'];

            $account_detail->save();

            Session::flash('success_message', 'Great! Customer has been updated successfully!');
            return redirect()->back();
        }
    }

    public function store_notes(Request $request){
        $user = User::find($request['c_id']);
        $user->name = $request['c_name'];
        $user->plan = $request['c_plan'];
        $user->active = $request['c_active'];
        $user->save();
        if($user) {
            for ($i=0; $i<count($request['note']); $i++){
                $note = new notes();
                $note->customer_id = $request['c_id'];
                $note->note = $request['note'][$i];
                $note->author = $request['author_name'][$i];
                $note->date = $request['date'][$i];
                $note->save();
            }
            Session::flash('success_message', 'Great! Customer has been updated successfully!');
            return redirect()->back();
        }

    }

    public function store_customer_file(Request $request){
        $request->validate([
            'file' => 'required|max:2048',
        ]);

        $fileName = time().'.'.$request->file->extension();
        $request->file->move(public_path('uploads'), $fileName);
        $user = User::find($request['c_id']);
        $user->name = $request['c_name'];
        $user->plan = $request['c_plan'];
        $user->active = $request['c_active'];
        $user->save();
        if($user) {
            $file = new attachments();
            $file->customer_id = $request['c_id'];
            $file->file = $fileName;
            $file->save();
        }

        Session::flash('success_message', 'Great! Customer has been updated successfully!');
        return redirect()->back();
    }


    public function store_customer_site(Request $request){
        $user = User::find($request['c_id']);
        $user->name = $request['c_name'];
        $user->plan = $request['c_plan'];
        $user->active = $request['c_active'];
        $user->save();
        if($user){
            for ($i=0; $i<count($request->site_name); $i++){
                $new_site = new sites();
                $new_site->customer_id = $request['c_id'];
                $new_site->site_name = $request['site_name'][$i];
                $new_site->address_line_1 = $request['address_line_1'][$i];
                $new_site->address_line_2 = $request['address_line_2'][$i];
                $new_site->suburb = $request['suburb'][$i];
                $new_site->postal_code = $request['postal_code'][$i];
                $new_site->state = $request['state'][$i];
                $new_site->opening_time = $request['opening_time'][$i];
                $new_site->save();
            }

            Session::flash('success_message', 'Great! Customer has been updated successfully!');
            return redirect()->back();
        }
    }
}
